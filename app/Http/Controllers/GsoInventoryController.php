<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoInventoryInterface;
use App\Interfaces\GsoItemRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoInventoryController extends Controller
{
    private GsoInventoryInterface $gsoInventoryRepository;
    private GsoItemRepositoryInterface $gsoItemRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoInventoryInterface $gsoInventoryRepository, GsoItemRepositoryInterface $gsoItemRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoInventoryRepository = $gsoInventoryRepository;
        $this->gsoItemRepository = $gsoItemRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/inventory';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $gl_accounts = $this->gsoItemRepository->allGLAccounts();
        $item_categories = $this->gsoItemRepository->allItemCategories();
        $item_types = $this->gsoItemRepository->allItemTypes();
        $pur_types = $this->gsoItemRepository->allPurchaseTypes();
        $unit_of_measurements = $this->gsoItemRepository->allUOMs();
        $adjustments = $this->gsoInventoryRepository->allAdjustmentTypes();
        return view('general-services.inventory.index')->with(compact('adjustments', 'gl_accounts', 'item_categories', 'item_types', 'pur_types', 'unit_of_measurements'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-secondary btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn adjust-btn bg-warning ms-05 btn btn-sm align-items-center" title="adjust this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoInventoryRepository->listItems($request);
        $res = $result->data->map(function($item) use ($statusClass, $actions) {
            $gl_account = ($item->gl_account) ? wordwrap($item->gl_account->code . ' - '. $item->gl_account->description, 25, "\n") : '';
            $type = ($item->type) ? wordwrap($item->type->code . ' - '. $item->type->description, 25, "\n") : '';
            $category = ($item->category) ? wordwrap($item->category->code . ' - '. $item->category->description, 25, "\n") : '';
            $uom = ($item->uom) ? $item->uom->code : '';
            return [
                'id' => $item->identity,
                'gl_account' => ($item->gl_account) ? $item->gl_account->code . ' - '. $item->gl_account->description : '',
                'gl_account_label' => '<div class="showLess">' . $gl_account . '</div>',
                'type' => ($item->type) ? $item->type->code . ' - '. $item->type->description : '',
                'type_label' => '<div class="showLess">' . $type . '</div>',
                'category' => ($item->category) ? $item->category->code . ' - '. $item->category->description : '',
                'category_label' => '<div class="showLess">' . $category . '</div>',
                'code' => $item->identityCode,
                'code_label' => '<strong class="text-primary">'.$item->identityCode.'</strong>',
                'name' => $item->identityName,
                'name_label' => '<div class="showLess"">' . $item->identityName . '</div>',
                'quantity' => '<strong>'. $item->quantity_inventory . ' / <span class="text-danger">' . $item->quantity_reserved .'</span></strong>',
                'uom' => $uom,
                'unit_cost' => '<strong>'.$item->identityWeightedCost. ' / <span class="text-danger">' .$item->identityLatestCost.'</span></strong>',
                'latest_cost' => $item->identityLatestCost,
                'latest_cost_date' => $item->identityLatestCostDate,
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
                'status' => $statusClass[$item->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$item->identityStatus]->bg. ' p-2">' . $statusClass[$item->identityStatus]->status . '</span>' ,
                'actions' => $actions
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
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-secondary btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn adjust-btn bg-warning ms-05 btn btn-sm align-items-center" title="adjust this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoInventoryRepository->history_listItems($request, $id);
        $res = $result->data->map(function($inventory) use ($statusClass, $actions) {
            $issued_by = ($inventory->trans_by) ? wordwrap($inventory->issuer->fullname, 25, "\n") : '';
            $recieved_by = ($inventory->rcv_by) ? wordwrap($inventory->receiver->fullname, 25, "\n") : '';
            return [
                'id' => $inventory->identity,
                'transaction' => $inventory->trans_type,
                'datetime' => '<strong>'.date('d-M-Y', strtotime($inventory->trans_datetime)).'</strong><br/>'.date('H:i A', strtotime($inventory->trans_datetime)),
                'issued_by' => ($inventory->trans_by) ? $inventory->issuer->fullname : '',
                'issued_by_label' => '<div class="showLess">' . $issued_by . '</div>',
                'received_by' => ($inventory->rcv_by) ? $inventory->receiver->fullname : '',
                'received_by_label' => '<div class="showLess">' . $recieved_by . '</div>',
                'based_from' => $inventory->based_from,
                'based_qty' => $inventory->based_qty,
                'posted_qty' => ($inventory->trans_class == 'Deduction Inventory' || $inventory->trans_type == 'Issuance') ? '<span class="text-danger">(-' . $inventory->posted_qty . ')</span>' : '(+' . $inventory->posted_qty.')',
                'balanced_qty' => '<strong>'.$inventory->balanced_qty.'</strong>'
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $res = $this->gsoItemRepository->find($id);
        return response()->json([
            'data' => (object) [
                'id' => $res->id,
                'item_category_id' => $res->category->code . ' - ' . $res->category->description,
                'item_type_id' => $res->type->code . ' - ' . $res->category->description,
                'gl_account_id' => $res->gl_account->code . ' - ' . $res->gl_account->description,
                'item_code' => $res->code,
                'item_name' => $res->name,
                'item_desc' => $res->remarks ? $res->description . ' ('. $res->remarks .')' : $res->description,
                'uom' => $res->uom->code,
                'unit_cost' => '<strong>Weighted Cost: <span class="text-danger">'. $res->weighted_cost .'</span> / Latest Cost: <span class="text-danger">' . $res->latest_cost . '</span></strong>',
                'quantity' => '<strong>Inventory Qty: <span class="text-danger">'. $res->quantity_inventory .'</span> / Reserved Qty: <span class="text-danger">' . $res->quantity_reserved . '</span></strong>'
            ]
        ]);
    }

    public function send(Request $request, $id)
    {   
        $timestamp = $this->carbon::now();
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $details = array(
                'adjustment_type_id' => $request->adjustment_type_id,
                'item_id' => $id,
                'control_no' => $this->gsoInventoryRepository->generateNo(),
                'quantity' => $request->quantity,
                'remarks' => $request->remarks,
                'status' => 'completed',
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id,
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
        } else {
            $details = array(
                'adjustment_type_id' => $request->adjustment_type_id,
                'item_id' => $id,
                'control_no' => $this->gsoInventoryRepository->generateNo(),
                'quantity' => $request->quantity,
                'remarks' => $request->remarks,
                'status' => 'for approval',
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id,
            );
            
        }
        return response()->json([
            'data' => $this->gsoInventoryRepository->create_request($details),
            'text' => 'The request has been successfully sent.',
            'type' => 'success',
            'status' => 'success'
        ]);
    }
}
