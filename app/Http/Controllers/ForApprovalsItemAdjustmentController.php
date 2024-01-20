<?php

namespace App\Http\Controllers;
use App\Models\GsoDepartmentalRequisition;
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
use PDF;

class ForApprovalsItemAdjustmentController extends Controller
{   
    private GsoInventoryInterface $gsoInventoryRepository;
    private GsoItemRepositoryInterface $gsoItemRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoInventoryInterface $gsoInventoryRepository, 
        GsoItemRepositoryInterface $gsoItemRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoInventoryRepository = $gsoInventoryRepository;
        $this->gsoItemRepository = $gsoItemRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/item-adjustment';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $adjustments = $this->gsoInventoryRepository->allAdjustmentTypes();
        return view('for-approvals.item-adjustment.index')->with(compact('adjustments'));
    }

    public function lists(Request $request)
    {           
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = ''; $actions3 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="read this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
            $actions3 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoInventoryRepository->approval_listItems($request);
        $res = $result->data->map(function($adjustment) use ($actions, $actions2, $actions3, $statusClass) {            
            $division = $adjustment->requestor ? ($adjustment->requestor->acctg_department_division_id > 0) ? '[' . $adjustment->requestor->division->code . ']' : '' : '';
            $department = $adjustment->requestor ? wordwrap($adjustment->requestor->department->code . ' - ' . $adjustment->requestor->department->name . ' '.$division, 25, "\n") : '';
            $requestedDate = $adjustment->identitySent ? date('d-M-Y H:i a', strtotime($adjustment->identitySent)) : '';
            $item = wordwrap($adjustment->item->code . ' - ' . $adjustment->item->name, 25, "\n");
            if ($adjustment->identityDisapprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($adjustment->identityDisapprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($adjustment->identityDisapprovedAt));
            } else if($adjustment->identityApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($adjustment->identityApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($adjustment->identityApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $adjustment->identity,
                'control_no' => $adjustment->identityNo,
                'control_no_label' => '<strong class="text-primary">'.$adjustment->identityNo.'</strong>',
                'requested_by' => $adjustment->requestor ? '<strong>'.$adjustment->requestor->fullname.'</strong><br/>'. $requestedDate : '',
                'department' => '<div class="showLess">' . $department . '</div>',
                'item' => '<div class="showLess">' . $item . '</div>',
                'quantity' => $adjustment->quantity,
                'uom' => $adjustment->item->uom->code,
                'modified' => ($adjustment->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($adjustment->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($adjustment->identityUpdatedAt)) : date('d-M-Y', strtotime($adjustment->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($adjustment->identityCreatedAt)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$adjustment->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$adjustment->identityStatus]->bg. ' p-2">' . $statusClass[$adjustment->identityStatus]->status . '</span>',
                'actions' => ($adjustment->identityStatus != 'cancelled') ? ($adjustment->identityStatus == 'issued') ? $actions3 : $actions : $actions2
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm align-items-center" title="View"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoInventoryRepository->item_listItems($request, $id);
        $res = $result->data->map(function($issueItem, $iteration = 0) use ($actions) {
            if (strlen($issueItem->item->remarks) > 0) { 
                $description = wordwrap($issueItem->itemCode . ' - ' . $issueItem->item->name . ' (' . $issueItem->item->remarks . ')', 25, "\n");
                $descriptions = $issueItem->itemCode . ' - ' . $issueItem->item->name . ' (' . $issueItem->item->remarks . ')';
            } else { 
                $description = wordwrap($issueItem->itemCode . ' - ' . $issueItem->item->name, 25, "\n"); 
                $descriptions = $issueItem->itemCode . ' - ' . $issueItem->item->name; 
            } 
            return [
                'no' => $iteration = $iteration + 1,
                'category' => $issueItem->category->code,
                'type' => $issueItem->type->code,
                'id' => $issueItem->identity,
                'description' => '<div class="showLess">' . $description . '</div>',
                'descriptions' => $descriptions,
                'quantity' => $issueItem->itemQuantity,
                'uom' => $issueItem->uom->code,
                'unit_cost' => $this->money_format($issueItem->itemCost),
                'total_cost' => $this->money_format(floatval(floatval($issueItem->itemQuantity) * floatval($issueItem->itemCost))),
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

    public function fetch_status(Request $request, $adjustmentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoInventoryRepository->find_adjustment($adjustmentID)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoInventoryRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $adjustmentID)
    {
        $approvers = explode(',',$this->gsoInventoryRepository->find_adjustment($adjustmentID)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $adjustmentID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'completed',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            $this->gsoInventoryRepository->update_request($adjustmentID, $details);
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $adjustmentID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoInventoryRepository->disapprove_request($adjustmentID, $details);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_remarks(Request $request, $adjustmentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoInventoryRepository->find_adjustment($adjustmentID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $adjustment = $this->gsoInventoryRepository->find_adjustment($id);
        return response()->json([
            'data' => 
            (object) [
                'adjustment_type_id' => $adjustment->adjustment_type_id,
                'quantity' => $adjustment->quantity,
                'remarks' => $adjustment->remarks,
            ]
        ]);
    }
}
