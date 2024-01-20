<?php

namespace App\Http\Controllers;
use App\Models\MenuGroup;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoPreRepairInspectionInterface;
use App\Interfaces\AcctgFixedAssetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoRepairInspectionController extends Controller
{
    private GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository;
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository, 
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPreRepairInspectionRepository = $gsoPreRepairInspectionRepository;
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/repairs-and-inspections/inspection';
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
        $fixed_assets = $this->gsoPreRepairInspectionRepository->allFixedAssets();
        $employees = $this->gsoPreRepairInspectionRepository->allEmployees();
        $items = $this->gsoPreRepairInspectionRepository->allItems();
        $uoms = $this->gsoPreRepairInspectionRepository->allUOMs();
        return view('general-services.repairs-and-inspections.inspection.index')->with(compact('permission', 'fixed_assets', 'employees', 'items', 'uoms'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'requested' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'for inspection approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->inpsection_listItems($request);
        $res = $result->data->map(function($repair) use ($statusClass, $actions, $actions2) {
            $requested_by = $repair->employee ? wordwrap($repair->employee->fullname, 25, "\n") : ''; 
            $issues = $repair->issues ?  wordwrap($repair->issues, 25, "\n") : ''; 
            return [
                'id' => $repair->id,
                'repair_no' => $repair->repair_no,
                'repair_no_label' => '<strong class="text-primary">' . $repair->repair_no . '</strong>',
                'fa_no' => $repair->property ? $repair->property->fixed_asset_no : '',
                'fa_no_label' => '<strong>' . ($repair->property ? $repair->property->fixed_asset_no : '') . '</strong>',
                'requested_by' => '<div class="showLess" title="' . ($repair->employee ? $repair->employee->fullname : '') . '">' . $requested_by . '</div>',
                'requested_date' => date('d-M-Y', strtotime($repair->requested_date)),
                'issues' => '<div class="showLess" title="' . ($repair->issues ? $repair->issues : '') . '">' . $issues . '</div>',
                'modified' => ($repair->updated_at !== NULL) ? 
                '<strong>'.$repair->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($repair->updated_at)) : 
                '<strong>'.$repair->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($repair->created_at)),
                'status' => $statusClass[$repair->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$repair->status]->bg. ' p-2">' . $statusClass[$repair->status]->status . '</span>' ,
                'actions' => ($repair->status == 'requested') ? $actions : $actions2
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
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->history_listItems($request, $id, $request->get('fixed_asset'));
        $res = $result->data->map(function($history) use ($statusClass, $actions) {
            $concerns = $history->concerns ? wordwrap($history->concerns, 25, "\n") : ''; 
            $remarks = $history->remarks ?  wordwrap($history->remarks, 25, "\n") : ''; 
            return [
                'id' => $history->id,
                'date_requested' => date('d-M-Y', strtotime($history->requested_date)),
                'concerns' => '<div class="showLess" title="' . ($history->concerns ? $history->concerns : '') . '">' . $concerns . '</div>',
                'date_accomplished' => date('d-M-Y', strtotime($history->completion_date)),
                'remarks' => '<div class="showLess" title="' . ($history->remarks ? $history->remarks : '') . '">' . $remarks . '</div>',
                'modified' => ($history->updated_at !== NULL) ? date('d-M-Y', strtotime($history->updated_at)).'<br/>'. date('h:i A', strtotime($history->updated_at)) : date('d-M-Y', strtotime($history->created_at)).'<br/>'. date('h:i A', strtotime($history->created_at)),
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function item_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 = '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->item_listItems($request, $id, $actions);
        $res = $result->data->map(function($item) use ($statusClass, $actions, $actions2) {
            $items = $item->item ? wordwrap($item->item->code.'-'.$item->item->name, 25, "\n") : ''; 
            $remarks = $item->remarks ?  wordwrap($item->remarks, 25, "\n") : ''; 
            return [
                'id' => $item->id,
                'code' => $item->item ? $item->item->code : '',
                'uom' => $item->uom ? ($item->quantity > 1 ? $item->uom->codex : $item->uom->code) : '',
                'item' => '<div class="showLess" title="' . ($item->item ? $item->item->code.' - '.$item->item->name : '') . '">' . $items . '</div>',
                'remarks' => '<div class="showLess" title="' . ($item->remarks ? $item->remarks : '') . '">' . $remarks . '</div>',
                'quantity' => $item->quantity ? $item->quantity : '',
                'amount' => $item->amount ? $this->money_format($item->amount) : '',
                'total' => $item->total_amount ? $this->money_format($item->total_amount) : '',
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
                'actions' => ($item->request->status == 'requested') ? $actions : $actions2
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function preload_fixed_asset($id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgFixedAssetRepository->get($id)->map(function($fixedAsset) {
                return (object) [
                    'requested_by' => $fixedAsset->received_by,
                    'model' => $fixedAsset->model,
                    'engine_no' => $fixedAsset->engine_no,
                    'plate_no' => $fixedAsset->plate_no,
                    'received_date' => $fixedAsset->received_date,
                    'unit_cost' => $fixedAsset->unit_cost,
                    'type' => $fixedAsset->type->code.' - '.$fixedAsset->type->name
                ];
            })
        ]);
    }

    public function find(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($id)
        ]);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPreRepairInspectionRepository->find($id)->status
        ]);
    }

    public function update(Request $request, $requestID)
    {
        if ($requestID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'property_id' => $request->property_id,
                'requested_by' => $request->requested_by ? $request->requested_by : NULL,
                'requested_date' => $request->requested_date ? $request->requested_date : NULL,
                'issues' => $request->issues ? $request->issues : NULL,
                'repair_no' => $this->gsoPreRepairInspectionRepository->generate(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $inspection = $this->gsoPreRepairInspectionRepository->create($details);
            $requestID = $inspection->id;
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'property_id' => $request->property_id,
                'requested_by' => $request->requested_by ? $request->requested_by : NULL,
                'requested_date' => $request->requested_date ? $request->requested_date : NULL,
                'issues' => $request->issues ? $request->issues : NULL,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoPreRepairInspectionRepository->update($requestID, $details);
        }
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($requestID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $requestID)
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $timestamp = $this->carbon::now();
        $res = $this->gsoPreRepairInspectionRepository->find($requestID);
        if ($status == 'for-approval' && $res->status == 'draft') {
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->gsoPreRepairInspectionRepository->update($requestID, $details),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function reload_item_cost(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->getItems($id)->map(function($item) {
                return (object) [
                    'uom_id' => $item->uom_id,
                    'unit_cost' => $item->weighted_cost
                ];
            })
        ]);
    }

    public function add_item(Request $request, $repairID): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'create'); 
        $details = array(
            'repair_id' => $repairID,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'uom_id' => $request->get('uom_id'),
            'amount' => $request->get('unit_cost'),
            'total_amount' =>  floatval($request->get('unit_cost')) * floatval($request->quantity),
            'remarks' => urldecode($request->get('remarks')),
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->createItem($details),
            'title' => 'Well done!',
            'text' => 'The pre-repair item has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_item(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $details = array(
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'uom_id' => $request->get('uom_id'),
            'amount' => $request->get('unit_cost'),
            'total_amount' =>  floatval($request->get('unit_cost')) * floatval($request->quantity),
            'remarks' => urldecode($request->get('remarks')),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->gsoPreRepairInspectionRepository->updateItem($id, $details);
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->findItem($id),
            'title' => 'Well done!',
            'text' => 'The pre-repair item has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_item(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->getItem($id)->map(function($item) {
                return (object) [
                    'item_id' => $item->item_id,
                    'uom_id' => $item->uom_id,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->amount,
                    'total_cost' => $item->total_amount,
                    'remarks' => $item->remarks,
                    'request' => $item->request
                ];
            })
        ]);
    }

    public function remove_item(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'is_active' => 0,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->updateItem($id, $details),
            'title' => 'Well done!',
            'text' => 'The pre-repair item has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_inspection(Request $request, $requestID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        $details = array(
            'inspected_remarks' => urldecode($request->inspected_remarks),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->gsoPreRepairInspectionRepository->update($requestID, $details);

        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($requestID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send_inspection(Request $request, $status, $requestID)
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $timestamp = $this->carbon::now();
        $res = $this->gsoPreRepairInspectionRepository->find($requestID);
        if ($status == 'for-inspection-approval' && $res->status == 'requested') {
            $details = array(                
                'status' => str_replace('-', ' ', $status),
                'is_inspected' => 1,
                'inspected_at' => $timestamp,
                'inspected_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->gsoPreRepairInspectionRepository->update($requestID, $details),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }
}
