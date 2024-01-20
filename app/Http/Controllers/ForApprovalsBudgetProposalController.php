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
use App\Interfaces\CboBudgetInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\HrEmployeeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsBudgetProposalController extends Controller
{   
    private CboBudgetInterface $cboBudgetRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private $carbon;
    private $slugs;

    public function __construct(CboBudgetInterface $cboBudgetRepository, HrEmployeeRepositoryInterface $hrEmployeeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/budget-proposal';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $fund_codes = $this->cboBudgetRepository->allFundCodes();
        $departments = $this->cboBudgetRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $gl_accounts = $this->cboBudgetRepository->allGLAccounts();
        return view('for-approvals.budget-proposal.index')->with(compact('fund_codes', 'departments', 'divisions', 'can_create', 'gl_accounts'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'locked' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'adjusted' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
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
        $result = $this->cboBudgetRepository->approval_listItems($request);
        $res = $result->data->map(function($budget) use ($actions, $actions2, $statusClass) {
            $department = $budget->department ? wordwrap($budget->department->code . ' - ' . $budget->department->name, 25, "\n") : '';
            $division = $budget->division ? wordwrap($budget->division->code . ' - ' . $budget->division->name, 25, "\n") : '';
            $fundcode = $budget->fund_code ? wordwrap($budget->fund_code->code . ' - ' . $budget->fund_code->description, 25, "\n") : '';
            if ($budget->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($budget->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($budget->disapproved_at));
            } else if($budget->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($budget->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($budget->approved_at));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $budget->identity,
                'year' => $budget->budget_year,
                'dep' => $budget->department ? $budget->department->code . ' - ' . $budget->department->name : '',
                'department' => '<div class="showLess">' . $department . '</div>',
                'division' => '<div class="showLess">' . $division . '</div>',
                'fundcode' => '<div class="showLess">' . $fundcode . '</div>',
                'total_buget' => $this->money_format($budget->total_budget),
                'modified' => ($budget->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($budget->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($budget->identityUpdatedAt)) : date('d-M-Y', strtotime($budget->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($budget->identityCreatedAt)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$budget->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$budget->identityStatus]->bg. ' p-2">' . $statusClass[$budget->identityStatus]->status . '</span>',
                'actions' =>  ($budget->identityStatus == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function line_lists(Request $request, $id)
    {      
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = ''; $actions2 = ''; $actions3 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="View"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions2 = '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions3 = '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
        }
        $result = $this->cboBudgetRepository->line_listItems($request, $id);
        $res = $result->data->map(function($breakdown, $iteration = 0) use ($actions, $actions2, $actions3, $statusClass) {
            $gl_account = wordwrap($breakdown->gl_account->code . ' - ' . $breakdown->gl_account->description, 25, "\n");
            return [
                'id' => $breakdown->identity,
                'no' => $iteration = $iteration + 1,
                'gl_account' => '<div class="showLess" title="' . $breakdown->gl_account->code . ' - ' . $breakdown->gl_account->description . '">' . $gl_account . '</div>',
                'quarterly' => $this->money_format($breakdown->quarterly_budget),
                'annual' => $this->money_format($breakdown->annual_budget),
                'modified' => ($breakdown->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($breakdown->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($breakdown->identityUpdatedAt)) : date('d-M-Y', strtotime($breakdown->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($breakdown->identityCreatedAt)),
                'status' => $statusClass[$breakdown->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$breakdown->identityStatus]->bg. ' p-2">' . $statusClass[$breakdown->identityStatus]->status . '</span>' ,
                'actions' => ($breakdown->identityStatus == 1) ? $actions.' '.$actions2 : $actions.' '.$actions3
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
            return $this->cboBudgetRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->cboBudgetRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $budgetID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'locked',
                'is_locked' => 1,
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id,
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );

            return response()->json([
                'data' => $this->cboBudgetRepository->update($budgetID, $details),
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $budgetID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $this->carbon::now(),
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->cboBudgetRepository->update($budgetID, $details);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
        ]);
    }

    public function fetch_status(Request $request, $budgetID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetRepository->find($budgetID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $budgetID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->cboBudgetRepository->find($budgetID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $budget = $this->cboBudgetRepository->find($id);
        return response()->json([
            'data' => 
            (object) [
                'budget_year' => $budget->budget_year,
                'department_id' => $budget->department_id,
                'division_id' => $budget->division_id,
                'fund_code_id' => $budget->fund_code_id,
                'remarks' => $budget->remarks
            ]
        ]);
    }
}
