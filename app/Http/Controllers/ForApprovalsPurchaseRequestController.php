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
use App\Interfaces\GsoObligationRequestInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\GsoPurchaseOrderInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPurchaseRequestController extends Controller
{   
    private GsoPurchaseRequestInterface $gsoPurchaseRequestRepository;
    private GsoObligationRequestInterface $gsoObligationRequestRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPurchaseRequestInterface $gsoPurchaseRequestRepository, 
        GsoObligationRequestInterface $gsoObligationRequestRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository,
        GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseRequestRepository = $gsoPurchaseRequestRepository;
        $this->gsoObligationRequestRepository = $gsoObligationRequestRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/purchase-request';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->gsoObligationRequestRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoObligationRequestRepository->allEmployees();
        $designations = $this->gsoObligationRequestRepository->allDesignations();
        $request_types = $this->gsoObligationRequestRepository->allRequestTypes();
        $purchase_types = $this->gsoObligationRequestRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        return view('for-approvals.purchase-request.index')->with(compact('years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements'));
    }

    public function lists(Request $request)
    {           
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoPurchaseRequestRepository->approval_listItems($request, 'modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($purchase) use ($actions, $actions2, $statusClass) {
            if (isset($purchase->allotment->department)) { 
                $department = wordwrap($purchase->allotment->department->code . ' - ' . $purchase->allotment->department->name . ' [' . $purchase->allotment->division->code . ']', 25, "\n");
            } else {
                $department = '';
            }
            $requestor = $purchase->allotment->requestor ? wordwrap($purchase->allotment->requestor->fullname, 25, "\n") : '';
            
            $remarks    = wordwrap($purchase->prRemarks, 25, "\n");
            if ($purchase->prDisapprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($purchase->prDisapprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($purchase->prDisapprovedAt));
            } else if($purchase->prApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($purchase->prApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($purchase->prApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $purchase->prId,
                'pr_no' => $purchase->prNo,
                'sequence' => $purchase->prSequence,
                'pr_no_label' => '<strong class="text-primary">'.$purchase->prNo.'</strong>',
                'control_no' => $purchase->control_no,
                'control_no_label' => '<strong>'.$purchase->control_no.'</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                // 'request_type' => $purchase->requisition->req_type->description,
                'request_type' => $purchase->allotment->type ? $purchase->allotment->type->name : '',
                'purchase_type' => '', //$purchase->requisition->pur_type->description,
                'requestor' => '<div class="showLess" title="'.($purchase->allotment->requestor ? $purchase->allotment->requestor->fullname : '').'">' . $requestor . '</div>',
                'total' => $purchase->allotment->total_amount ? $this->money_format($purchase->allotment->total_amount) : '',
                'remarks' => '<div class="showLess">' . $remarks. '</div>',
                'approved_by' => $approvedBy,
                'modified' => ($purchase->prUpdatedAt !== NULL) ? date('d-M-Y', strtotime($purchase->prUpdatedAt)).'<br/>'. date('h:i A', strtotime($purchase->prUpdatedAt)) : date('d-M-Y', strtotime($purchase->prCreatedAt)).'<br/>'. date('h:i A', strtotime($purchase->prCreatedAt)),
                'status' => $statusClass[$purchase->prStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$purchase->prStatus]->bg. ' p-2">' . $statusClass[$purchase->prStatus]->status . '</span>' ,
                'actions' => ($purchase->status == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            // 'status' => $this->gsoPurchaseRequestRepository->find($id)->status,
            'status' => $this->gsoPurchaseRequestRepository->find_via_pr($id)->first()->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoPurchaseOrderRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    // public function validate_approver(Request $request, $id)
    // {
    //     $approvers = explode(',',$this->gsoPurchaseRequestRepository->find($id)->approved_by);
    //     if (in_array(Auth::user()->id, $approvers)) {
    //         return true;
    //     }
    //     return false;
    // }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->gsoPurchaseRequestRepository->validate_approver($this->gsoPurchaseRequestRepository->find_via_pr($id)->first()->allotment->department_id, $sequence, 'modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $requisitionID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $counter = $this->gsoPurchaseRequestRepository->find_levels($this->slugs, 'modules');
            $res = $this->gsoPurchaseRequestRepository->find_via_pr($requisitionID)->first();
            if ($res->approved_by == NULL) {
                $approvers = array(); $timestamps = array();
                $approvers[] = Auth::user()->id;
                $timestamps[] = $timestamp;
            } else {
                $approvers = explode(',', $res->approved_by);
                $approvers[] = Auth::user()->id;
                $timestamps = explode(',', $res->approved_datetime);
                $timestamps[] = $timestamp;
            }                

            if (count($approvers) == $counter) {
                $details = array(
                    'status' => 'prepared',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $details2 = array(
                    'purchase_request_no' => $this->gsoPurchaseRequestRepository->fetchPurchaseRequestNo(),
                    'status' => 'completed',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                    'approved_datetime' => (count($approvers) == 1) ? implode('', $timestamps) : implode(',', $timestamps),
                );
                $lineDetails = array(
                    'status' => 'prepared',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $details4 = array(
                    'pr_status' => 'completed',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $this->gsoPurchaseRequestRepository->updateRequest($requisitionID, $details);
                $this->gsoPurchaseRequestRepository->update($requisitionID, $details2);
                $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $lineDetails);
                $this->gsoPurchaseRequestRepository->update_alob($res->allotment->id, $details4);
                $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisitionID);
            } else {
                $details2 = array(
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                    'approved_datetime' => (count($approvers) == 1) ? implode('', $timestamps) : implode(',', $timestamps),
                );
                $this->gsoPurchaseRequestRepository->update($requisitionID, $details2);
            }

            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $requisitionID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoPurchaseRequestRepository->update($requisitionID, $details);
            $details2 = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoPurchaseRequestRepository->updateRequest($requisitionID, $details2);
            $lineDetails = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $lineDetails);
            $details3 = array(
                'departmental_request_id' => $requisitionID,
                'disapproved_from' => 'Purchase Request',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoPurchaseRequestRepository->disapprove_request($details3);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'tracking' => $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisitionID),
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoDepartmentalRequisitionRepository->fetch_remarks($id)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->find($id),
            'alob' => $this->cboBudgetAllocationRepository->findAlobViaPr($id)->map(function($alob) {
                return (object) [
                    'allob_requested_date' => $alob->requisition->requested_date,
                    'control_no' => $alob->requisition->control_no,
                    'budget_no' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id' => $alob->department_id,                    
                    'allob_division_id' => $alob->division_id,
                    'budget_year' => $alob->budget_year,
                    'payee_id' => $alob->payee_id,
                    'fund_code_id' => $alob->fund_code_id,
                    'address' => $alob->address,
                    'particulars' => $alob->particulars
                ];
            }),
            'pr' => $this->gsoPurchaseRequestRepository->find_via_pr($id)->map(function($pr) {
                return (object) [
                    'id' => $pr->id,
                    'departmental_request_id' => $pr->departmental_request_id,
                    'purchase_request_no' => $pr->purchase_request_no,
                    'prepared_date' => date('d-M-Y', strtotime($pr->prepared_date)),
                    'prepared_by' => $pr->prepared_by,
                    'pr_remarks' => $pr->remarks,
                    'approved_date' => $pr->approved_at ? date('d-M-Y', strtotime($pr->approved_at)) : ''
                ];
            })
        ]);
    }

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_items($purchase_type)
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'employees' => $this->gsoDepartmentalRequisitionRepository->reload_employees($department),
            'divisions' => $this->gsoDepartmentalRequisitionRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_designation($employee)
        ]);
    }

    public function fetch_allotment_via_pr(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read');
        $column = $request->get('column');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->findAlobViaPr($requisitionID)->first()->$column,
            'title' => 'Well done!',
            'text' => 'The allotment has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function item_lists(Request $request, $id)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg',
            'prepared' => 'prepared-bg',
            'quoted' => 'quoted-bg',
            'for rfq approval' => 'for-approval-bg',
            'estimated' => 'estimated-bg',
            'awarded' => 'awarded-bg',
            'for po approval' => 'for-approval-bg',
            'purchased' => 'purchased-bg',
            'partial' => 'purchased-bg',
            'posted' => 'completed-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoDepartmentalRequisitionRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions) {
            $unitPrice  = (floatval($requisition->purchase_unit_price) > 0) ? $requisition->purchase_unit_price : $requisition->request_unit_price;
            $totalPrice = (floatval($requisition->purchase_total_price) > 0) ? $requisition->purchase_total_price : $requisition->request_total_price;
            $items = wordwrap($requisition->item->code.' - ' .$requisition->item->name, 25, "<br />\n");
            return [
                'id' => $requisition->itemId,
                'item' => $requisition->item->code.' - ' .$requisition->item->name,
                'item_details' => '<div class="showLess">' . $items. '</div>',
                'uom' => $requisition->uom->code,
                'req_quantity' => $requisition->quantity_requested,    
                'pr_quantity' => ($requisition->quantity_pr > 0) ? $requisition->quantity_pr : '',    
                'po_quantity' => ($requisition->quantity_po > 0) ? $requisition->quantity_po : '',    
                'posted_quantity' => ($requisition->quantity_posted > 0) ? $requisition->quantity_posted : '',  
                'unit_price' => $this->money_format($unitPrice), 
                'total_price' => '<strong>' . $this->money_format($totalPrice) . '</strong>',   
                'status' => $requisition->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->status]. ' p-2">' . $requisition->status . '</span>' ,
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function alob_lists(Request $request, $id)
    {       
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->cboBudgetAllocationRepository->listAlobLines($request, $id);
        $res = $result->data->map(function($alob) use ($actions) {
            $glDesc = wordwrap($alob->glDesc, 25, "\n");
            return [
                'id' => $alob->alobId,
                'gl_code' => $alob->glCode,
                'gl_desc' => '<div class="showLess">' . $glDesc. '</div>',   
                'budget_id' => $alob->budgetId,  
                'total' => $this->money_format($alob->budgetTotal), 
                'amount' => '<strong>' . $this->money_format($alob->alobAmt) . '</strong>', 
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
}
