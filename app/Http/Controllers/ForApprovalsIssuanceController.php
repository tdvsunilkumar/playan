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
use App\Interfaces\GsoIssuanceInterface;
use App\Interfaces\GsoPurchaseOrderInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsIssuanceController extends Controller
{   
    private GsoIssuanceInterface $gsoIssuanceRepository;
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoIssuanceInterface $gsoIssuanceRepository, 
        GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoIssuanceRepository = $gsoIssuanceRepository;
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/issuance';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $requestor = $this->gsoIssuanceRepository->allEmployees();
        $pr_po = $this->gsoIssuanceRepository->allPrPo();
        return view('for-approvals.issuance.index')->with(compact('requestor', 'pr_po'));
    }

    public function lists(Request $request)
    {           
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'issued' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
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
        $result = $this->gsoIssuanceRepository->approval_listItems($request);
        $res = $result->data->map(function($issuance) use ($actions, $actions2, $actions3, $statusClass) {            
            $division = $issuance->requestor ? ($issuance->requestor->acctg_department_division_id > 0) ? '[' . $issuance->requestor->division->code . ']' : '' : '';
            $department = $issuance->requestor ? wordwrap($issuance->requestor->department->code . ' - ' . $issuance->requestor->department->name . ' '.$division, 25, "\n") : '';
            $requestedDate = $issuance->requested_date ? date('d-M-Y', strtotime($issuance->requested_date)) : '';
            $issuanceDate = $issuance->issued_date ? date('d-M-Y', strtotime($issuance->issued_date)) : '';
            if ($issuance->identityDispprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($issuance->identityDispprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($issuance->identityDisapprovedAt));
            } else if($issuance->identityApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($issuance->identityApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($issuance->identityApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $issuance->identity,
                'control_no' => $issuance->identityNo,
                'control_no_label' => '<strong class="text-primary">'.$issuance->identityNo.'</strong>',
                'requested_by' => $issuance->requestor ? '<strong>'.$issuance->requestor->fullname.'</strong><br/>'. $requestedDate : '',
                'issued_by' => $issuance->issuer ? '<strong>'.$issuance->issuer->fullname.'</strong><br/>'. $issuanceDate : '',
                'department' => '<div class="showLess">' . $department . '</div>',
                'total_amount' => $this->money_format($issuance->identityTotal),
                'modified' => ($issuance->identityUpdated !== NULL) ? date('d-M-Y', strtotime($issuance->identityUpdated)).'<br/>'. date('h:i A', strtotime($issuance->identityUpdated)) : date('d-M-Y', strtotime($issuance->identityCreated)).'<br/>'. date('h:i A', strtotime($issuance->identityCreated)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$issuance->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$issuance->identityStatus]->bg. ' p-2">' . $statusClass[$issuance->identityStatus]->status . '</span>',
                'actions' => ($issuance->identityStatus != 'cancelled') ? ($issuance->identityStatus == 'issued') ? $actions3 : $actions : $actions2
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
        $result = $this->gsoIssuanceRepository->item_listItems($request, $id);
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
    
    public function get_agencies($issuanceID)
    {
        return $this->bacRequestForQuotationRepository->get_agencies($issuanceID);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoIssuanceRepository->find($id)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoIssuanceRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->gsoIssuanceRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $issuanceID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $validated = $this->gsoIssuanceRepository->validate_issuance($issuanceID);
            if ($validated > 0) {
                $details = array(
                    'status' => 'issued',
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'approved_by_designation' => $this->gsoPurchaseOrderRepository->fetch_designation(Auth::user()->id)
                );
                $this->gsoIssuanceRepository->update($issuanceID, $details);
                $this->gsoIssuanceRepository->credit_inventory($issuanceID, $timestamp, Auth::user()->id);
                return response()->json([
                    'text' => 'The request has been successfully approved.',
                    'type' => 'success',
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'title' =>  'Oops...',
                    'status' => 'failed',
                    'type' => 'danger',
                    'text' => 'Unable to send, item invetory is not available.',
                ]);
            }
        }
    }

    public function disapprove(Request $request, $issuanceID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoIssuanceRepository->update($issuanceID, $details);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_remarks(Request $request, $issuanceID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoIssuanceRepository->find($issuanceID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $issuance = $this->gsoIssuanceRepository->find($id);
        return response()->json([
            'data' => 
            (object) [
                'control_no' => $issuance->control_no,
                'requested_by' => $issuance->requested_by,
                'requested_date' => $issuance->requested_date,
                'issued_by' => $issuance->issued_by,
                'issued_date' => $issuance->issued_date,
                'received_by' => $issuance->received_by,
                'received_date' => $issuance->received_date,
                'department' => $issuance->requestor ? $issuance->requestor->department->code . ' - ' . $issuance->requestor->department->name : '',
                'designation' => $issuance->requestor ? $issuance->requestor->department->designation->description : '',
                'remarks' => $issuance->remarks,
                'purchase_order_id' => $issuance->purchase_order_id
            ]
        ]);
    }
}
