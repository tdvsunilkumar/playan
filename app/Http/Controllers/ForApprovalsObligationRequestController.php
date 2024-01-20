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
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsObligationRequestController extends Controller
{   
    private GsoObligationRequestInterface $gsoObligationRequestRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoObligationRequestInterface $gsoObligationRequestRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        HrEmployeeRepositoryInterface $hrEmployeeRepository,
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoObligationRequestRepository = $gsoObligationRequestRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/obligation-request';
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
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        $permissions = explode(',', $this->load_privileges($this->slugs));
        return view('for-approvals.obligation-request.index')->with(compact('categories', 'permissions', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'pending' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
            
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
        }        
        $result = $this->gsoObligationRequestRepository->approval_listItems($request, Auth::user()->id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions, $actions2) {  
            $department = ($requisition->obligation->department_id > 0) ? wordwrap($requisition->obligation->department->code . ' - ' . $requisition->obligation->department->name . ' [' . (($requisition->obligation->division) ? $requisition->obligation->division->code : '') . ']', 25, "\n") : '';
            $controlNo  = $requisition->obligation->requisition ? $requisition->obligation->requisition->control_no : '';
            $reqType    = $requisition->obligation->requisition ? $requisition->obligation->requisition->req_type->description : '';
            $requestor  = $requisition->obligation->requisition ? $requisition->obligation->requisition->employee->fullname : '';
            $remarks    = wordwrap($requisition->obligation->prRemarks, 25, "\n");
            $particulars = wordwrap($requisition->particulars, 25, "\n");
            if ($requisition->obligDisapprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->obligDisapprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->obligDisapprovedAt));
            } else if($requisition->obligApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->obligApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->obligApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $requisition->obligId,
                'departmental_request' => $requisition->departmental_request_id,
                'payee' => ($requisition->obligation->payee_id !== NULL)? $requisition->obligation->payee->paye_name : '',
                'particulars' => '<div class="showLess">' . $particulars . '</div>',
                'control' => $requisition->obligation->budget_control_no,
                'budget_control' => '<strong class="text-primary">'.$requisition->obligation->budget_control_no.'</strong>',
                'control_no' => '<strong>' . $controlNo . '</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'request_type' => $reqType,
                'requestor' => '<strong>' . $requestor . '</strong>',
                'total' => $this->money_format($requisition->obligation->total_amount),
                'modified' => ($requisition->obligUpdatedAt !== NULL) ? date('d-M-Y', strtotime($requisition->obligUpdatedAt)).'<br/>'. date('h:i A', strtotime($requisition->obligUpdatedAt)) : date('d-M-Y', strtotime($requisition->obligCreatedAt)).'<br/>'. date('h:i A', strtotime($requisition->obligCreatedAt)),
                'approved_by' => $approvedBy,
                'status' => $requisition->obligStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->obligStatus]->bg . ' p-2">' .  $statusClass[$requisition->obligStatus]->status . '</span>',
                'actions' => ($requisition->obligStatus == 'cancelled') ? $actions2 : $actions
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
            return $this->cboBudgetAllocationRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetAllocationRepository->find_obligation($id)->status
        ]);
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->cboBudgetAllocationRepository->find_obligation($id)->disapproved_remarks
        ]);
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->cboBudgetAllocationRepository->find_obligation($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $allotmentID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'completed',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            $this->cboBudgetAllocationRepository->update_request($allotmentID, $details);
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $allotmentID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details);
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->cboBudgetAllocationRepository->update_request($allotmentID, $details);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
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

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_items($purchase_type)
        ]);
    }

    public function fetch_allotment_via_pr2(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read');
        $column = $request->get('column');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->get_alob($allotmentID)->first()->$column,
            'title' => 'Well done!',
            'text' => 'The allotment has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
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
        $result = $this->cboBudgetAllocationRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass) {
            $unitPrice  = (floatval($requisition->purchase_unit_price) > 0) ? $requisition->purchase_unit_price : $requisition->request_unit_price;
            $totalPrice = (floatval($requisition->purchase_total_price) > 0) ? $requisition->purchase_total_price : $requisition->request_total_price;
            $items = wordwrap($requisition->item->code.' - ' .$requisition->item->name, 25, "\n");
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

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
        ]);
    }
    
    public function alob_lists2(Request $request, $id)
    {       
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->cboBudgetAllocationRepository->listAlobLines2($request, $id);
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

    public function find_obligation(Request $request, $allotmentID): JsonResponse 
    {
        return response()->json([
            'alob' => $this->cboBudgetAllocationRepository->get_alob($allotmentID)->map(function($alob) {
                return (object) [
                    'allob_requested_date2' => $alob->date_requested,
                    'control_no2' => $alob->budget_control_no,
                    'budget_no2' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id2' => $alob->department_id,                    
                    'allob_division_id2' => $alob->division_id,
                    'budget_year2' => $alob->budget_year,
                    'payee_id2' => $alob->payee_id,
                    'fund_code_id2' => $alob->fund_code_id,
                    'address2' => $alob->address,
                    'particulars2' => $alob->particulars,
                    'with_pr2' => $alob->with_pr,
                    'employee_id2' => $alob->employee_id,
                    'designation_id2' => $alob->designation_id,
                    'budget_category_id2' => $alob->budget_category_id
                ];
            })
        ]);
    }
}
