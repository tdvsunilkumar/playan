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
use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Interfaces\CtoDisburseInterface;
use App\Interfaces\HrInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsBudgetAllocationController extends Controller
{   
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private CtoDisburseInterface $treasuryDisburseRepository;
    private HrInterface $payrollRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        HrEmployeeRepositoryInterface $hrEmployeeRepository,
        CtoDisburseInterface $treasuryDisburseRepository,
        HrInterface $payrollRepository,
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->treasuryDisburseRepository = $treasuryDisburseRepository;
        $this->payrollRepository = $payrollRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/budget-allocation';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->cboBudgetAllocationRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->cboBudgetAllocationRepository->allEmployees();
        $designations = $this->cboBudgetAllocationRepository->allDesignations();
        $request_types = $this->cboBudgetAllocationRepository->allRequestTypes();
        $purchase_types = $this->cboBudgetAllocationRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        $funding_by = $this->cboBudgetAllocationRepository->allEmployees();
        $approval_by = $this->cboBudgetAllocationRepository->allEmployees();
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        return view('for-approvals.budget-allocation.index')->with(compact('funding_by', 'approval_by', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements', 'categories'));
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
        
        $result = $this->cboBudgetAllocationRepository->approval_listItems($request, 'modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions, $actions2) {  
            $department = ($requisition->department_id != NULL) ?  wordwrap($requisition->department->code . ' - ' . $requisition->department->name . ' [' . (($requisition->division) ? $requisition->division->code : '') . ']', 25, "\n") : '';
            $controlNo  = $requisition->requisition ? $requisition->requisition->control_no : '';
            $reqType    = $requisition->requisition ? $requisition->requisition->req_type->description : '';
            $requestor  = $requisition->requisition ? $requisition->requisition->employee->fullname : '';
            $remarks    = wordwrap($requisition->prRemarks, 25, "\n");
            $particulars = wordwrap($requisition->particulars, 25, "\n");
            $type = $requisition->type ? $requisition->type->name : ''; 
            if ($requisition->obligDisapprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->obligDisapprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->obligDisapprovedAt));
            } else if($requisition->obligApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($requisition->obligApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($requisition->obligApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $requisition->obligId,
                'sequence' => $requisition->approved_counter,
                'type' => '<div class="showLess" title="'. ($requisition->type ? $requisition->type->name : '') .'">' . $type . '</div>',
                'departmental_request' => $requisition->departmental_request_id,
                'payee' => ($requisition->payee_id !== NULL)? $requisition->payee->paye_name : '',
                'particulars' => '<div class="showLess">' . $particulars . '</div>',
                'control' => $requisition->budget_control_no,
                'budget_control' => '<strong class="text-primary">'.$requisition->budget_control_no.'</strong>',
                'control_no' => '<strong>' . $controlNo . '</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'request_type' => $reqType,
                'requestor' => '<strong>' . $requestor . '</strong>',
                'total_pr' => ($requisition->departmental_request_id != NULL) ? $requisition->requisition->total_amount : 0,
                'total_alob' => $requisition->allotmentTotal,
                'total' => $this->money_format($requisition->allotmentTotal),
                'modified' => ($requisition->obligUpdatedAt != NULL) ? date('d-M-Y', strtotime($requisition->obligUpdatedAt)).'<br/>'. date('h:i A', strtotime($requisition->obligUpdatedAt)) : date('d-M-Y', strtotime($requisition->obligCreatedAt)).'<br/>'. date('h:i A', strtotime($requisition->obligCreatedAt)),
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
            'status' => $this->cboBudgetAllocationRepository->find_alob($id)->status
        ]);
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->cboBudgetAllocationRepository->find_alob($id)->disapproved_remarks
        ]);
    }

    // public function validate_approver(Request $request, $id)
    // {
    //     $approvers = explode(',',$this->cboBudgetAllocationRepository->find_alob($id)->approved_by);
    //     if (in_array(Auth::user()->id, $approvers)) {
    //         return true;
    //     }
    //     return false;
    // }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->cboBudgetAllocationRepository->validate_approver($this->cboBudgetAllocationRepository->find_alob($id)->department_id, $sequence, 'modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $allotmentID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $res = $this->cboBudgetAllocationRepository->find_alob($allotmentID);
            $counter = $this->cboBudgetAllocationRepository->find_levels($this->slugs, 'modules');
            if ($res->approved_by == NULL) {
                $approvers = array(); $timestamps = array();
                $approvers[] = Auth::user()->id;
                $timestamps[] = $timestamp;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'completed' : 'for approval',
                    'approved_at' => $timestamp, 
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_datetime' => (count($approvers) == 1) ? implode('', $timestamps) : implode(',', $timestamps),
                    'funding_by' => Auth::user()->hr_employee->id,
                    'funding_designation' => Auth::user()->hr_employee->id ? $this->cboBudgetAllocationRepository->fetch_designation(Auth::user()->hr_employee->id) : NULL,
                    'approved_counter' => count($approvers) + 1,
                );
                $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details);
            } else {
                $approvers = explode(',', $res->approved_by);
                $approvers[] = Auth::user()->id;
                $timestamps = explode(',', $res->approved_datetime);
                $timestamps[] = $timestamp;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'completed' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_datetime' => (count($approvers) == 1) ? implode('', $timestamps) : implode(',', $timestamps),
                    'approval_by' => Auth::user()->hr_employee->id,
                    'approval_designation' => Auth::user()->hr_employee->id ? $this->cboBudgetAllocationRepository->fetch_designation(Auth::user()->hr_employee->id) : NULL, 
                    'approved_counter' => count($approvers) + 1,
                );
                $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details);
            }
            
            if (count($approvers) == $counter) {
                $details3 = array(
                    'budget_no' => $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo(),   
                    'alobs_control_no' => $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID, $timestamp),                 
                    'status' => 'completed',  
                );
                $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details3);

                $details2 = array(
                    'status' => 'allocated',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                if ($res->departmental_request_id > 0) {
                    $this->cboBudgetAllocationRepository->updateRequest($res->departmental_request_id, $details2);
                    $this->gsoDepartmentalRequisitionRepository->track_dept_request($res->departmental_request_id);
                }
                if ($res->obligation_type_id == 2) {
                    $this->treasuryDisburseRepository->reimburse($allotmentID, $timestamp, Auth::user()->id);
                }
                // LANIE'S PAYROLL
                if ($res->obligation_type_id == 4) {
                    //generate debit memo
                    $this->payrollRepository->sendToDebitMemo($allotmentID, Auth::user());
                }
            }
            return response()->json([
                'budget_no' => (count($approvers) == $counter) ? $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo() : '',
                // 'alobs_control_no' => (count($approvers) > 1) ? $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID, $timestamp) : '',  
                'alobs_control_no' => (count($approvers) == $counter) ?  $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID, $timestamp) : '',  
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
            $res = $this->cboBudgetAllocationRepository->find_alob($allotmentID);
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details);
            if ($res->departmental_request_id > 0) {
                $details2 = array(
                    'status' => 'cancelled',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $this->cboBudgetAllocationRepository->updateRequest($res->departmental_request_id, $details2);
                $this->gsoDepartmentalRequisitionRepository->track_dept_request($res->departmental_request_id);
                $details3 = array(
                    'departmental_request_id' => $res->departmental_request_id,
                    'disapproved_from' => 'Budget Allocation',
                    'disapproved_at' => $timestamp,
                    'disapproved_by' => Auth::user()->id,
                    'disapproved_remarks' => $request->disapproved_remarks
                );
                $this->gsoDepartmentalRequisitionRepository->disapprove_request($details3);

            }
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
                    'particulars' => $alob->particulars,
                    'funding_byz' => $alob->funding_by,
                    'approval_byz' => $alob->approval_by,
                    'budget_category_id' => $alob->budget_category_id,
                    'budget_category_id2' => $alob->budget_category_id
                ];
            })
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
                    'funding_byx' => $alob->funding_by,
                    'approval_byx' => $alob->approval_by,
                    'budget_category_id2' => $alob->budget_category_id
                ];
            })
        ]);
    }
}
