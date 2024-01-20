<?php

namespace App\Http\Controllers;
use App\Models\GsoItem;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgFixedAssetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgFixedAssetController extends Controller
{
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgFixedAssetInterface $acctgFixedAssetRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/fixed-assets';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = (object) array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $property_types = $this->acctgFixedAssetRepository->allProperties();
        $gl_accounts = $this->acctgFixedAssetRepository->allGLAccounts();
        $depreciation_types = $this->acctgFixedAssetRepository->allDepreciations();
        $employees = $this->acctgFixedAssetRepository->allEmployees();
        $categories = $this->acctgFixedAssetRepository->allCategories();
        $items = ['' => 'select an item'];
        $salvage_values = ['' => 'select a salvage value', '5' => '5%', '10' => '10%'];
        $life_spans = ['' => 'select estimated life span', '60' => '5 years', '120' => '10 years', '180' => '15 years', '240' => '20 years', '300' => '25 years', '360' => '30 years'];
        return view('accounting.fixed-assets.index')->with(compact('permission', 'items', 'categories', 'employees', 'property_types', 'gl_accounts', 'depreciation_types', 'salvage_values', 'life_spans'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            'acquired' => (object) ['bg' => 'requested-bg', 'status' => 'acquired'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-placement="top" data-bs-toggle="tooltip"><i class="ti-pencil text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn lock-btn bg-info btn m-1 btn-sm align-items-center" title="lock this" data-bs-placement="top" data-bs-toggle="tooltip"><i class="ti-lock text-white"></i></a>';
        }
        $result = $this->acctgFixedAssetRepository->listItems($request);
        $res = $result->data->map(function($fixedAsset) use ($statusClass, $actions, $actions2) {
            $gl_account = ($fixedAsset->gl_account) ? wordwrap($fixedAsset->gl_account->code . ' - '. $fixedAsset->gl_account->description, 25, "\n") : '';
            $item = ($fixedAsset->item) ? wordwrap($fixedAsset->item->code . ' - '. $fixedAsset->item->name, 25, "\n") : '';            
            return [
                'id' => $fixedAsset->id,
                'fa_no' => $fixedAsset->fixed_asset_no ? $fixedAsset->fixed_asset_no : '',
                'fa_no_label' => $fixedAsset->fixed_asset_no ? '<strong class="text-primary">'.$fixedAsset->fixed_asset_no.'</strong>' : '',
                'par_no' => $fixedAsset->property_no,
                'category' => $fixedAsset->category->name,
                'type' => $fixedAsset->type->name,
                'gl_account' => '<div class="showLess" title="' . ($fixedAsset->gl_account ? $fixedAsset->gl_account->code . ' - '. $fixedAsset->gl_account->description : '') . '">' . $gl_account . '</div>',
                'item' => '<div class="showLess" title="' . ($fixedAsset->item ? $fixedAsset->item->code . ' - '. $fixedAsset->item->name : '') . '">' . $item . '</div>',
                'unit_cost' => $fixedAsset->unit_cost,
                'modified' => ($fixedAsset->updated_at !== NULL) ? 
                '<strong>'.$fixedAsset->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($fixedAsset->updated_at)) : 
                '<strong>'.$fixedAsset->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($fixedAsset->created_at)),
                'status' => $statusClass[$fixedAsset->status]->status,
                'status_label' => ($fixedAsset->is_locked > 0) ?                 
                '<span class="badge badge-status rounded-pill bg-info p-2">' . $statusClass[$fixedAsset->status]->status . '</span>' : '<span class="badge badge-status rounded-pill ' . $statusClass[$fixedAsset->status]->bg. ' p-2">' . $statusClass[$fixedAsset->status]->status . '</span>' ,
                'actions' => ($fixedAsset->is_locked > 0) ? $actions : $actions2,
                'locked' => $fixedAsset->is_locked
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function history_lists(Request $request, $id) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            'acquired' => (object) ['bg' => 'bg-info', 'status' => 'acquired'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-placement="top" data-bs-toggle="tooltip"><i class="ti-pencil text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn lock-btn bg-secondary btn m-1 btn-sm align-items-center" title="lock this" data-bs-placement="top" data-bs-toggle="tooltip"><i class="ti-lock text-white"></i></a>';
        }
        $result = $this->acctgFixedAssetRepository->history_listItems($request, $id);
        $res = $result->data->map(function($history) use ($statusClass, $actions, $actions2) {    
            return [
                'id' => $history->id,
                'acquired_date' => $history->acquired_date ? date('d-M-Y', strtotime($history->acquired_date)) : '',
                'acquired_by' => $history->acquiree ? $history->acquiree->fullname : '',
                'issued_by' => $history->issuer ? $history->issuer->fullname : '',
                'returned_date' => $history->returned_date ? date('d-M-Y', strtotime($history->returned_date)) : '',
                'returned_by' => $history->returnee ? $history->returnee->fullname : '',
                'received_by' => $history->receiver ? $history->receiver->fullname : '',
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function reload_items_via_gl(Request $request, $gl_account): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgFixedAssetRepository->reload_items_via_gl($gl_account, 1)
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgFixedAssetRepository->get($id)->map(function($fixedAsset) {
                return (object) [
                    'fixed_asset_no' => $fixedAsset->fixed_asset_no,
                    'property_category_id' => $fixedAsset->property_category_id,
                    'received_by' => $fixedAsset->received_by,
                    'issued_by' => $fixedAsset->issued_by,
                    'property_type_id' => $fixedAsset->property_type_id,
                    'property_no' => $fixedAsset->property_no,
                    'gl_account' => $fixedAsset->gl_account->code.' - '.$fixedAsset->gl_account->description,
                    'gl_account_id' => $fixedAsset->gl_account_id,
                    'item_id' => $fixedAsset->item_id,
                    'item' => $fixedAsset->item->code.' - '.$fixedAsset->item->name. ' '.$fixedAsset->item->description,
                    'received_date' => $fixedAsset->received_date,
                    'unit_cost' => $fixedAsset->unit_cost,
                    'estimated_life_span' => $fixedAsset->estimated_life_span,
                    'model' => $fixedAsset->model ? $fixedAsset->model : '',
                    'engine_no' => $fixedAsset->engine_no ? $fixedAsset->engine_no : '',
                    'mv_file_no' => $fixedAsset->mv_file_no ? $fixedAsset->mv_file_no : '',
                    'chasis_no' => $fixedAsset->chasis_no ? $fixedAsset->chasis_no : '',
                    'plate_no' => $fixedAsset->plate_no ? $fixedAsset->plate_no : '',
                    'depreciation_type_id' => $fixedAsset->depreciation_type_id,
                    'salvage_value' => $fixedAsset->salvage_value ? $fixedAsset->salvage_value : '',
                    'monthly_depreciation' => $fixedAsset->monthly_depreciation ? $fixedAsset->monthly_depreciation : '',
                    'remarks' => $fixedAsset->remarks ? $fixedAsset->remarks : '',
                    'is_depreciative' => $fixedAsset->is_depreciative,
                    'is_departmental' => $fixedAsset->is_departmental,
                    'effectivity_date' => $fixedAsset->effectivity_date
                ];
            })
        ]);
    }

    public function truncate_number($number, $decimals = 0)
    {
        $negation = ($number < 0) ? (-1) : 1;
        $coefficient = 10 ** $decimals;
        return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
    }

    public function store(Request $request): JsonResponse
    {  
        $item = GsoItem::find($request->item_id);
        $fixedAssetNo = $this->acctgFixedAssetRepository->generate();
        $details = array(
            'fixed_asset_no' => $fixedAssetNo,
            'received_by' => $request->received_by,
            'issued_by' => $request->issued_by,
            'property_category_id' => $request->property_category_id,
            'gl_account_id' => $request->gl_account_id,
            'item_id' => $request->item_id,
            'property_type_id' => $request->property_type_id,
            'model' => $request->model,
            'engine_no' => $request->engine_no,
            'mv_file_no' => $request->mv_file_no,
            'chasis_no' => $request->chasis_no,
            'plate_no' => $request->plate_no,
            'received_date' => date('Y-m-d', strtotime($request->received_date)),
            'unit_cost' => $request->unit_cost,
            'quantity' => 1,
            'uom_id' => $item->uom_id,            
            'estimated_life_span' => $request->estimated_life_span,
            'remarks' => $request->remarks,
            'depreciation_type_id' => $request->depreciation_type_id,
            'salvage_value' => $request->salvage_value,
            'monthly_depreciation' => ($request->get('is_depreciative') > 0) ? $this->truncate_number($request->get('monthly_depreciation'),5) : NULL,
            'is_depreciative' => $request->get('is_depreciative'),
            'is_departmental' => 0,
            'effectivity_date' => $request->effectivity_date ? date('Y-m-d', strtotime($request->effectivity_date)) : NULL,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->acctgFixedAssetRepository->create($details),
            'fixed_asset_no' => $fixedAssetNo,
            'title' => 'Well done!',
            'text' => 'The fixed asset has been successfully saved.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');     
        
        $fixedAsset = $this->acctgFixedAssetRepository->find($id);     
        if ($fixedAsset->fixed_asset_no == NULL) {
            $fixedAssetNo = $this->acctgFixedAssetRepository->generate();
        } else {
            $fixedAssetNo = $fixedAsset->fixed_asset_no;
        }
        if ($fixedAsset->is_departmental > 0) {
            $details = array(
                'fixed_asset_no' => $fixedAssetNo,
                'property_type_id' => $request->property_type_id,
                'model' => $request->model,
                'engine_no' => $request->engine_no,
                'mv_file_no' => $request->mv_file_no,
                'chasis_no' => $request->chasis_no,
                'plate_no' => $request->plate_no,
                'estimated_life_span' => $request->estimated_life_span,
                'remarks' => $request->remarks,
                'depreciation_type_id' => $request->depreciation_type_id,
                'salvage_value' => $request->salvage_value,
                'monthly_depreciation' => ($request->get('is_depreciative') > 0) ? $this->truncate_number($request->get('monthly_depreciation'),5) : NULL,
                'is_depreciative' => $request->get('is_depreciative'),
                'is_departmental' => ($fixedAsset->is_departmental > 0) ? $fixedAsset->is_departmental : 0,
                'effectivity_date' => $request->effectivity_date ? date('Y-m-d', strtotime($request->effectivity_date)) : NULL,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        } else {
            $details = array(
                'fixed_asset_no' => $fixedAssetNo,
                'received_by' => $request->received_by,
                'issued_by' => $request->issued_by,
                'property_category_id' => $request->property_category_id,
                'gl_account_id' => $request->gl_account_id,
                'item_id' => $request->item_id,
                'property_type_id' => $request->property_type_id,
                'model' => $request->model,
                'engine_no' => $request->engine_no,
                'mv_file_no' => $request->mv_file_no,
                'chasis_no' => $request->chasis_no,
                'plate_no' => $request->plate_no,
                'received_date' => date('Y-m-d', strtotime($request->received_date)),
                'unit_cost' => $request->unit_cost,
                'quantity' => 1,
                'uom_id' => $fixedAsset->item->uom_id,
                'estimated_life_span' => $request->estimated_life_span,
                'remarks' => $request->remarks,
                'depreciation_type_id' => $request->depreciation_type_id,
                'salvage_value' => $request->salvage_value,
                'monthly_depreciation' => ($request->get('is_depreciative') > 0) ? $this->truncate_number($request->get('monthly_depreciation'),5) : NULL,
                'is_depreciative' => $request->get('is_depreciative'),
                'is_departmental' => ($fixedAsset->is_departmental > 0) ? $fixedAsset->is_departmental : 0,
                'effectivity_date' => $request->effectivity_date ? date('Y-m-d', strtotime($request->effectivity_date)) : NULL,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        }

        return response()->json([
            'data' => $this->acctgFixedAssetRepository->update($id, $details),
            'fixed_asset_no' => $fixedAssetNo,
            'title' => 'Well done!',
            'text' => 'The fixed asset has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function lock(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $fixedAsset = $this->acctgFixedAssetRepository->find($id);
        $exist = $this->acctgFixedAssetRepository->get_history($id);
        if ($exist->count() > 0) {
            $history = array(
                'acquired_date' => $fixedAsset->received_date,
                'acquired_by' => $fixedAsset->received_by,
                'issued_by' => $fixedAsset->issued_by,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->acctgFixedAssetRepository->update_history($exist->first()->id, $history);
        } else {
            $history = array(
                'property_id' => $id,
                'acquired_date' => $fixedAsset->received_date,
                'acquired_by' => $fixedAsset->received_by,
                'issued_by' => $fixedAsset->issued_by,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $this->acctgFixedAssetRepository->create_history($history);
        }

        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_locked' => 1
        );      

        return response()->json([
            'data' => $this->acctgFixedAssetRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The fixed asset has been successfully locked.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
