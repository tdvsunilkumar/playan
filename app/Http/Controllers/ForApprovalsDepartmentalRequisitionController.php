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
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsDepartmentalRequisitionController extends Controller
{   
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoPurchaseRequestInterface $gsoPurchaseRequestRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoPurchaseRequestInterface $gsoPurchaseRequestRepository, 
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseRequestRepository = $gsoPurchaseRequestRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/departmental-requisition';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->gsoDepartmentalRequisitionRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoDepartmentalRequisitionRepository->allEmployees();
        $designations = $this->gsoDepartmentalRequisitionRepository->allDesignations();
        $request_types = $this->gsoDepartmentalRequisitionRepository->allRequestTypes();
        $purchase_types = $this->gsoDepartmentalRequisitionRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        return view('for-approvals.departmental-requisition.index')->with(compact('categories', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'requested' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for alob approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'allocated' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for pr approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'prepared' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'quoted' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for rfq approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'estimated' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for abstract approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'awarded' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for resolution approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'for po approval' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'purchased' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'partial' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoDepartmentalRequisitionRepository->approval_listItems($request, 'modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions, $actions2) {
            $department = wordwrap($requisition->department->code . ' - ' . $requisition->department->name . ' [' . $requisition->division->code . ']', 25, "\n");
            $remarks = wordwrap($requisition->prRemarks, 25, "\n");
            if ($requisition->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->disapproved_at));
            } else if($requisition->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->approved_at));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $requisition->prId,
                'control' => $requisition->control_no,
                'sequence' => $requisition->approved_counter,
                'control_no' => '<strong class="text-primary">'.$requisition->control_no.'</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'request_type' => $requisition->req_type->description,
                'requestor' => '<strong>'.$requisition->employee->fullname.'</strong>',
                'total' => $this->money_format($requisition->total_amount),
                'remarks' => '<div class="showLess">' . $remarks. '</div>',
                'modified' => ($requisition->prUpdatedAt !== NULL) ? date('d-M-Y', strtotime($requisition->prUpdatedAt)).'<br/>'. date('h:i A', strtotime($requisition->prUpdatedAt)) : date('d-M-Y', strtotime($requisition->prCreatedAt)).'<br/>'. date('h:i A', strtotime($requisition->prCreatedAt)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$requisition->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->status]->bg. ' p-2">' . $statusClass[$requisition->status]->status . '</span>' ,
                'actions' => ($requisition->status == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoDepartmentalRequisitionRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoDepartmentalRequisitionRepository->fetch_remarks($id)->disapproved_remarks
        ]);
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->gsoDepartmentalRequisitionRepository->validate_approver(
            $this->gsoDepartmentalRequisitionRepository->find($id)->department_id,
            $sequence, 'modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $requisitionID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $counter = $this->gsoDepartmentalRequisitionRepository->find_levels($this->slugs, 'modules');

            $requisition = $this->gsoDepartmentalRequisitionRepository->find($requisitionID);
            if ($requisition->approved_by == NULL) {
                $approvers = array();
                $approvers[] = Auth::user()->id;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'requested' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                );
                $itemDetails = array(
                    'status' => (count($approvers) == $counter) ? 'requested' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers)
                );
            } else {
                $approvers = explode(',', $res->approved_by);
                $approvers[] = Auth::user()->id;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'requested' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                );
                $itemDetails = array(
                    'status' => (count($approvers) == $counter) ? 'requested' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers)
                );
            }

            if (count($approvers) == $counter) {
                $allotmentDetail = array(
                    'obligation_type_id' => 1,
                    'budget_control_no' => $this->cboBudgetAllocationRepository->generateBudgetControlNo(date('Y')),
                    'departmental_request_id' => $requisitionID,
                    'department_id' => $requisition->department_id,
                    'division_id' => $requisition->division_id,
                    'budget_category_id' => $requisition->budget_category_id,
                    'fund_code_id' => $requisition->fund_code_id,
                    'employee_id' => $requisition->employee_id,
                    'designation_id' => $requisition->designation_id,
                    'with_pr' => 1,
                    'budget_year' => date('Y', strtotime($requisition->requested_date)),
                    'created_at' => $timestamp,
                    'created_by' => Auth::user()->id
                );
                $allotment = $this->cboBudgetAllocationRepository->create($allotmentDetail);
                $allotmentRequestDetail = array(
                    'allotment_id' => $allotment->id,
                    'status' => 'completed',                    
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                );
                $allotmentRequest = $this->cboBudgetAllocationRepository->create_request($allotmentRequestDetail);
            }
            $data = $this->gsoDepartmentalRequisitionRepository->update($requisitionID, $details);
            $data2 = $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $itemDetails);
            return response()->json([
                'data' => $data,
                'data2' => $data2,                
                'tracking' => $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisitionID),
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
            $this->gsoDepartmentalRequisitionRepository->update($requisitionID, $details);
            $details2 = array(
                'departmental_request_id' => $requisitionID,
                'disapproved_from' => 'Departmental Request',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoDepartmentalRequisitionRepository->disapprove_request($details2);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'tracking' => $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisitionID),
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_designation($employee)
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

    public function fetch_status(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoDepartmentalRequisitionRepository->find($requisitionID)->status
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
            'for pr approval' => 'for-approval-bg',
            'prepared' => 'prepared-bg',
            'quoted' => 'quoted-bg',
            'for rfq approval' => 'for-approval-bg',
            'estimated' => 'estimated-bg',
            'for abstract approval' => 'for-approval-bg',
            'awarded' => 'awarded-bg',
            'for resolution approval' => 'for-approval-bg',
            'for po approval' => 'for-approval-bg',
            'purchased' => 'purchased-bg',
            'partial' => 'partial-bg',
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

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->find($id),
            'alob' => $this->cboBudgetAllocationRepository->findAlobViaPr($id)->map(function($alob) {
                return (object) [
                    'allob_requested_date' => $alob->requisition->requested_date,
                    'control_no' => $alob->budget_control_no,
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
}
