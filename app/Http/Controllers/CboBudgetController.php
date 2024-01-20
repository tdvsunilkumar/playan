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
use App\Interfaces\HrEmployeeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class CboBudgetController extends Controller
{
    private CboBudgetInterface $cboBudgetRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CboBudgetInterface $cboBudgetRepository,
        HrEmployeeRepositoryInterface $hrEmployeeRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->carbon = $carbon;
        $this->slugs = 'finance/budget-proposal';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $fund_codes = $this->cboBudgetRepository->allFundCodes();
        $departments = $this->cboBudgetRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $gl_accounts = $this->cboBudgetRepository->allGLAccounts();
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        return view('finance.budget-proposals.index')->with(compact('categories', 'fund_codes', 'departments', 'divisions', 'can_create', 'gl_accounts'));
    }

    public function lists(Request $request)
    {      
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'locked' => 'bg-info',
            'adjusted' => 'requested-bg',
            'cancelled' => 'cancelled-bg'
        ]; 
        $actions = ''; $actions2 = ''; $actions3 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="edit this"><i class="ti-pencil text-white"></i></a>';
            $actions2 = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="view this"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn lock-btn bg-info btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="lock this"><i class="ti-lock text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn unlock-btn draft-bg btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="unlock"><i class="ti-unlock text-white"></i></a>';
        }
        $result = $this->cboBudgetRepository->listItems($request, $request->get('year'));
        $res = $result->data->map(function($budget) use ($actions, $actions2, $statusClass) {
            $department = $budget->department ? wordwrap($budget->department->code . ' - ' . $budget->department->name, 25, "\n") : '';
            $division = $budget->division ? wordwrap($budget->division->code . ' - ' . $budget->division->name, 25, "\n") : '';
            $fundcode = $budget->fund_code ? wordwrap($budget->fund_code->code . ' - ' . $budget->fund_code->description, 25, "\n") : '';
            
            if ($budget->identityStatus == 'locked') {
                $actions = $actions2;
            } else {
                $actions = $actions;
            }
            return [
                'id' => $budget->identity,
                'checkbox' => ($budget->identityStatus == 'locked') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$budget->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$budget->identity.'" disabled="disabled"></div>',
                'year' => $budget->budget_year,
                'dep' => $budget->department ? $budget->department->code . ' - ' . $budget->department->name : '',
                'department' => '<div class="showLess">' . $department . '</div>',
                'division' => '<div class="showLess">' . $division . '</div>',
                'fundcode' => '<div class="showLess">' . $fundcode . '</div>',
                'total_buget' => $this->money_format($budget->total_budget),
                'total_used' => $this->money_format($budget->total_used),
                'modified' => ($budget->identityUpdatedAt !== NULL) ? 
                '<strong>'.$budget->modified->name.'</strong><br/>'.date('d-M-Y h:i A', strtotime($budget->identityUpdatedAt)) : '<strong>'.$budget->inserted->name.'</strong><br/>'.date('d-M-Y h:i A', strtotime($budget->identityCreatedAt)),
                'status' => $budget->identityStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$budget->identityStatus]. ' '. (($budget->is_adjusted > 0) ? 'requested-bg' : '') .' p-2">' .  $budget->identityStatus . '</span>',
                'actions' =>  $actions
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
            $category = $breakdown->category ? wordwrap($breakdown->category->code.' - '. $breakdown->category->description, 25, "\n") : '';
            if ($breakdown->budget->status == 'locked' || $breakdown->is_adjusted > 0) {
                $actionx = '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="View"><i class="ti-search text-white"></i></a>';
            } else {
                if ($breakdown->identityStatus == 1) {
                    $actionx = $actions.' '.$actions2;
                } else {
                    $actionx = $actions.' '.$actions3;
                }
            }
            $final_budget = $breakdown->final_budget ? floatval($breakdown->final_budget) : floatval($breakdown->annual_budget);
            $balance_budget = floatval($final_budget) - floatval($breakdown->amount_used);
            $alignment = floatval($final_budget) - floatval($breakdown->annual_budget);
            return [
                'id' => $breakdown->identity,
                'no' => $iteration = $iteration + 1,
                'gl_account' => '<div class="showLess" title="' . $breakdown->gl_account->code . ' - ' . $breakdown->gl_account->description . '">' . $gl_account . '</div>',
                'quarterly' => $this->money_format($breakdown->quarterly_budget),
                'quarterly2' => $breakdown->quarterly_budget,
                'annual' => $this->money_format($breakdown->annual_budget),
                'annual2' => $breakdown->annual_budget,
                'amount_used' => $this->money_format($breakdown->amount_used),
                'amount_used2' => $breakdown->amount_used,
                'alignment' => $this->money_format($alignment),
                'alignment2' => $alignment,
                'final_budget' => $this->money_format($final_budget),
                'final_budget2' => $final_budget,
                'balance_budget' => $this->money_format($balance_budget),
                'balance_budget2' => $balance_budget,
                'category' => '<div class="showLess" title="' . ($breakdown->category ? $breakdown->category->code.' - '. $breakdown->category->description : '') . '">' . $category . '</div>',
                'is_ppmp' => ($breakdown->is_ppmp == 1) ? '<span class="badge badge-yes-no rounded-pill bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no rounded-pill bg-secondary p-2">No</span>', 
                'is_ppmp_data' => $breakdown->is_ppmp,
                'modified' => ($breakdown->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($breakdown->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($breakdown->identityUpdatedAt)) : date('d-M-Y', strtotime($breakdown->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($breakdown->identityCreatedAt)),
                'status' => $statusClass[$breakdown->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$breakdown->identityStatus]->bg. ' p-2">' . $statusClass[$breakdown->identityStatus]->status . '</span>' ,
                'actions' => $actionx
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

    public function update(Request $request, $budgetID)
    {   
        if ($budgetID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'budget_year' => $request->get('budget_year') ? $request->get('budget_year') : NULL,
                'department_id' => $request->get('department_id') ? $request->get('department_id') : NULL,
                'fund_code_id' => $request->get('fund') ? $request->get('fund') : NULL,
                'remarks' => urldecode($request->get('remarks')),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $issuance = $this->cboBudgetRepository->create($details);
            $budgetID = $issuance->id;
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'budget_year' => $request->get('budget_year') ? $request->get('budget_year') : NULL,
                'department_id' => $request->get('department_id') ? $request->get('department_id') : NULL,
                'fund_code_id' => $request->get('fund') ? $request->get('fund') : NULL,
                'remarks' => urldecode($request->get('remarks')),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->cboBudgetRepository->update($budgetID, $details);
        }
        return response()->json([
            'data' => $this->cboBudgetRepository->find($budgetID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_status(Request $request, $budgetID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetRepository->find($budgetID)->status
        ]);
    }

    public function fetch_breakdown_status(Request $request, $breakdownID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        $breakdown = $this->cboBudgetRepository->find_breakdown($breakdownID);
        return response()->json([
            'adjusted' => ($breakdown->budget->status == 'locked' || $breakdown->is_adjusted == 1) ? 'locked' : 'draft' 
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
                'remarks' => $budget->remarks,
            ]
        ]);
    }

    public function store_breakdown(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->cboBudgetRepository->validate_breakdown($request->gl_account_id, $request->budget_category_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a budget breakdown with an existing gl account.',
                'label' => 'This is an existing gl account.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'gl_account_id',
            ]);
        }

        $details = array(
            'budget_id' => $id,
            'gl_account_id' => $request->gl_account_id,
            'quarterly_budget' => $request->quarterly_budget,
            'annual_budget' => $request->annual_budget,
            'is_ppmp' => $request->is_ppmp,
            'budget_category_id' => $request->budget_category_id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $breakdown = $this->cboBudgetRepository->create_breakdown($details);

        $totalAmt = $this->cboBudgetRepository->getTotalAmount($id);
        $this->cboBudgetRepository->update($id, ['total_budget' => $totalAmt]);
        return response()->json(
            [
                'data' => $breakdown,
                'total' => $totalAmt,
                'title' => 'Well done!',
                'text' => 'The budget breakdown has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update_breakdown(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->cboBudgetRepository->validate_breakdown($request->gl_account_id, $request->budget_category_id, $request->get('budget_id'), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a budget breakdown with an existing gl account.',
                'label' => 'This is an existing gl account.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'gl_account_id',
            ]);
        }

        $budget = $this->cboBudgetRepository->find($request->get('budget_id'));
        if ($budget->status == 'locked') {
            $breakdown = $this->cboBudgetRepository->find_breakdown($id);
            $alignment = floatval($budget->alignment) + floatval($request->alignment);
            $final_budget = $breakdown->final_budget ? floatval($breakdown->final_budget) + floatval($alignment) : floatval($breakdown->annual_budget) + floatval($alignment);
            $details = array(
                'alignment' => $alignment,
                'final_budget' => $final_budget,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $details2 = array(
                'budget_breakdown_id' => $id,
                'amount' => $request->alignment,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $this->cboBudgetRepository->insertAlignment($details2);
        } else {
            $breakdown = $this->cboBudgetRepository->find_breakdown($id);
            if ($breakdown->final_budget != NULL) {
                $details = array(
                    'gl_account_id' => $request->gl_account_id,
                    // 'quarterly_budget' => $request->quarterly_budget,
                    // 'annual_budget' => $request->annual_budget,
                    'budget_category_id' => $request->budget_category_id,
                    'is_ppmp' => $request->is_ppmp,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'gl_account_id' => $request->gl_account_id,
                    'quarterly_budget' => $request->quarterly_budget,
                    'annual_budget' => $request->annual_budget,
                    'budget_category_id' => $request->budget_category_id,
                    'is_ppmp' => $request->is_ppmp,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
            }
        }

        $this->cboBudgetRepository->update_breakdown($id, $details);
        $totalAmt = $this->cboBudgetRepository->getTotalAmount($request->get('budget_id'));
        return response()->json([
            'data' => $this->cboBudgetRepository->update($request->get('budget_id'), ['total_budget' => $totalAmt]),
            'total' => $totalAmt,
            'title' => 'Well done!',
            'text' => 'The budget breakdown has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_breakdown(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $breakdown = $this->cboBudgetRepository->find_breakdown($id);
        return response()->json([
            'data' => (object) [
                'gl_account_id' => $breakdown->gl_account_id,
                'budget_category_id' => $breakdown->budget_category_id,
                'quarterly_budget' => $breakdown->quarterly_budget,
                'is_ppmp' => $breakdown->is_ppmp,
                'annual_budget' => $breakdown->annual_budget,
                'balance' => $breakdown->final_budget ? floatval($breakdown->final_budget) - floatval($breakdown->amount_used) : floatval($breakdown->annual_budget) - floatval($breakdown->amount_used),
                'final_budget' => $breakdown->final_budget ? $breakdown->final_budget : $breakdown->annual_budget,
                'finals' => $breakdown->final_budget
            ]
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        $this->cboBudgetRepository->update_breakdown($id, $details);

        $budgetID = $this->cboBudgetRepository->find_breakdown($id)->budget_id;
        $totalAmt = $this->cboBudgetRepository->getTotalAmount($budgetID);
        return response()->json([
            'data' => $this->cboBudgetRepository->update($budgetID, ['total_budget' => $totalAmt]),
            'total' => $totalAmt,
            'title' => 'Well done!',
            'text' => 'The budget breakdown has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );
        $this->cboBudgetRepository->update_breakdown($id, $details);

        $budgetID = $this->cboBudgetRepository->find_breakdown($id)->budget_id;
        $totalAmt = $this->cboBudgetRepository->getTotalAmount($budgetID);
        return response()->json([
            'data' => $this->cboBudgetRepository->update($budgetID, ['total_budget' => $totalAmt]),
            'total' => $totalAmt,
            'title' => 'Well done!',
            'text' => 'The budget breakdown has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $budgetID)
    {   
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval') {
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'locked',
                    'is_locked' => 1,
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'status' => 'for approval',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id
                );
            }
            return response()->json([
                'data' => $this->cboBudgetRepository->update($budgetID, $details),
                'text' => 'The request has been successfully sent to locked.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else if ($status == 'unlock') {
            $details = array(
                'status' => 'draft',
                'is_adjusted' => 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->cboBudgetRepository->update($budgetID, $details);
            $breakdownDetails = array(
                'is_adjusted' => 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->cboBudgetRepository->update_breakdowns($budgetID, $breakdownDetails);
            return response()->json([
                'text' => 'The request has been successfully unlocked.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'type' => 'danger',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function validate_budget(Request $request, $budgetID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'validate' => $this->cboBudgetRepository->validate_budget($budgetID)
        ]);
    }

    public function validate_amount(Request $request, $budgetID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'amount' => $this->cboBudgetRepository->getTotalAmount($budgetID)
        ]);
    }

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
        ]);
    }

    public function year_lists(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetRepository->year_lists(),
            'category' => $this->cboBudgetRepository->category_lists()
        ]);
    }

    public function copy_proposal(Request $request)
    {
        $this->is_permitted($this->slugs, 'create');
        return response()->json(
            [
                'data' => $this->cboBudgetRepository->copy($request->lists, $request->get('budget_year'), $this->carbon::now(), Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The budget proposal has been successfully copied.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }
}
