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
use App\Interfaces\GsoPPMPInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;

class GsoPPMPController extends Controller
{
    private GsoPPMPInterface $gsoPPMPRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPPMPInterface $gsoPPMPRepository, 
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPPMPRepository = $gsoPPMPRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/project-procurement-management-plan';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        Session::forget('identity');
        $departments = $this->gsoPPMPRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $fund_codes = $this->gsoPPMPRepository->allFundCodes();
        $categories = $this->gsoPPMPRepository->allBudgetCategories();
        return view('general-services.project-procurement-management-plan.index')->with(compact('departments', 'fund_codes', 'categories'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'locked' => (object) ['bg' => 'bg-info', 'status' => 'locked'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = ''; $actions3 = ''; $actions4 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn copy-btn btn-blue btn me-05 ms-05 btn-sm align-items-center" title="copy this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="flaticon-layers text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn copy-btn btn-blue btn me-05 btn-sm align-items-center" title="copy this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="flaticon-layers text-white"></i></a>';
            $actions3 .= '<a href="javascript:;" class="action-btn unlock-btn draft-bg btn me-05 btn-sm align-items-center" title="unlock this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-unlock text-white"></i></a>';
            $actions4 .= '<a href="javascript:;" class="action-btn send-btn bg-print btn btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn ms-05 me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
        }
        $canCreate = $this->is_permitted($this->slugs, 'create', 1);
        $result = $this->gsoPPMPRepository->listItems($request, Auth::user()->id);
        $res = $result->data->map(function($ppmp) use ($statusClass, $actions, $actions2, $actions3, $actions4, $canCreate) {
            $department = $ppmp->department ? wordwrap($ppmp->department->code.' - '.$ppmp->department->name, 25, "\n") : '';   
            $funds = $ppmp->fund ? wordwrap($ppmp->fund->code.' - '.$ppmp->fund->description, 25, "\n") : '';             
            $remarks = wordwrap($ppmp->identityRemarks, 25, "\n");    
            $categories = $ppmp->category ? wordwrap($ppmp->category->code.' - '.$ppmp->category->description, 25, "\n") : '';   

            $hrefs  = '';
            $hrefs .= '<div class="btn-group" title="toggle this" data-bs-toggle="tooltip" data-bs-placement="top">';
            $hrefs .= '<button type="button" class="action-btn btn btn-warning me-05 btn-sm" data-bs-toggle="dropdown" aria-expanded="false">';
            $hrefs .= '<i class="flaticon-more text-white"></i>';
            $hrefs .= '</button>';
            $hrefs .= '<ul class="dropdown-menu">';
            foreach ($ppmp->department->divisions as $div) {
                $locked = $this->gsoPPMPRepository->check_if_division_locked($ppmp->identity, $div->id);
                if ($locked > 0) {
                    $hrefs .= '<li class="active"><a class="dropdown-item" href="'.url('/general-services/project-procurement-management-plan/edit/'.$ppmp->identity).'?division='.$div->id.'">['.$div->code .'] '.$div->name.'</a></li>';
                } else {
                    $hrefs .= '<li><a class="dropdown-item" href="'.url('/general-services/project-procurement-management-plan/edit/'.$ppmp->identity).'?division='.$div->id.'">['.$div->code .'] '.$div->name.'</a></li>';
                }
            }
            if ($canCreate > 0) {
                $hrefs .= '<li><a class="dropdown-item" href="'.url('/general-services/project-procurement-management-plan/manage/'.$ppmp->identity).'">Manage</a></li>';
            }
            $hrefs .= '</ul>';
            $hrefs .= '</div>';

            $buttons = '';
            if ($statusClass[$ppmp->identityStatus]->status == 'cancelled') {
                $buttons .= $actions;
            } else if ($statusClass[$ppmp->identityStatus]->status == 'locked') {
                $buttons .= $hrefs . ' ' . $actions2 . ' ' . $actions3;
            } else {
                $buttons .= $hrefs . ' ' . $actions4;
            }

            return [
                'id' => $ppmp->identity,
                'budget_year' => $ppmp->budget_year,
                'control_no' => $ppmp->control_no,
                'control_no_label' => '<strong class="text-primary">'.$ppmp->control_no.'</strong>',
                'department' => '<div class="showLess" title="' . ($ppmp->department ? $ppmp->department->code.' - '.$ppmp->department->name : '') . '">' . $department . '</div>',
                'funds' => '<div class="showLess" title="' . ($ppmp->fund ? $ppmp->fund->code.' - '.$ppmp->fund->description : '') . '">' . $funds . '</div>',
                'remarks' => '<div class="showLess" title="' . $ppmp->identityRemarks . '">' . $remarks . '</div>',
                'categories' => '<div class="showLess" title="' . ($ppmp->category ? $ppmp->category->code.' - '.$ppmp->category->description : '') . '">' . $categories . '</div>',
                'total' => $this->money_format($ppmp->total_amount),
                'modified' => ($ppmp->identityUpdatedAt !== NULL) ? 
                '<strong>'.$ppmp->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($ppmp->identityUpdatedAt)) : 
                '<strong>'.$ppmp->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($ppmp->identityCreatedAt)),
                'status' => $statusClass[$ppmp->identityStatus]->status,
                'app_status' => $ppmp->budget_status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$ppmp->identityStatus]->bg. ' p-2">' . $statusClass[$ppmp->identityStatus]->status . '</span>' ,
                'actions' => $buttons
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function validate_item_request($month, $item, $fund_code, $department, $division = '', $year)
    {
        $count = $this->gsoPPMPRepository->validate_item_request($month, $item, $fund_code, $department, $division, $year);
        return ($count > 0) ? $count : '';
    }

    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');  

        $rows = $this->gsoPPMPRepository->validate_if_budget_exist(
            $request->fund_code_id, 
            $request->department_id, 
            $request->budget_year, 
            $request->budget_category_id
        );
        if (!($rows > 0)) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'The budget is not exist.',
                'label' => 'this is not exist.',
                'type' => 'error2',
                'class' => 'btn-danger',
                'columns' => array('department_id', 'budget_year', 'budget_category_id'),
            ]);
        }
        
        $rows = $this->gsoPPMPRepository->validate($request->fund_code_id, $request->department_id, $request->budget_year, $request->budget_category_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a ppmp with an existing department/year.',
                'label' => 'this is an existing department.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'department_id',
            ]);
        }

        $details = array(
            'control_no' => $this->gsoPPMPRepository->generate_control_no($request->budget_year),
            'fund_code_id' => $request->fund_code_id,
            'department_id' => $request->department_id,
            'budget_category_id' => $request->budget_category_id,
            'budget_year' => $request->budget_year,
            'remarks' => $request->remarks,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $ppmp = $this->gsoPPMPRepository->create($details);

        $arr = array();
        foreach ($request->input('gl_account') as $gl_account) {
            foreach ($request->input('division')[$gl_account] as $key => $division_budget) {
                $arr[] = $division_budget;
                $plans = $this->gsoPPMPRepository->check_if_budget_plan($ppmp->id, $gl_account, $key, $request->budget_category_id);
                if ($plans->count() > 0) {
                    $budget_details = array(
                        'ppmp_id' => $ppmp->id,
                        'gl_account_id' => $gl_account,
                        'division_id' => $key,
                        'budget_category_id' => $request->budget_category_id,
                        'amount' => $division_budget ? $division_budget : 0,
                        'is_active' => 1,
                        'updated_at' => $this->carbon::now(),
                        'updated_by' => Auth::user()->id
                    );
                    $this->gsoPPMPRepository->update_budget_plan($plans->first()->id, $budget_details);
                } else {
                    $budget_details = array(
                        'ppmp_id' => $ppmp->id,
                        'gl_account_id' => $gl_account,
                        'division_id' => $key,
                        'budget_category_id' => $request->budget_category_id,
                        'amount' => $division_budget ? $division_budget : 0,
                        'created_at' => $this->carbon::now(),
                        'created_by' => Auth::user()->id
                    );
                    $this->gsoPPMPRepository->create_budget_plan($budget_details);
                }
            }
        }

        return response()->json(
            [
                'data' => $ppmp,
                'data2' => $arr,
                'title' => 'Well done!',
                'text' => 'The ppmp has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }
    
    public function copy(Request $request, $id): JsonResponse 
    {       
        $rows = $this->gsoPPMPRepository->validate_if_budget_exist(
            $request->fund_code_id, 
            $request->department_id, 
            $request->budget_year, 
            $request->budget_category_id
        );
        if (!($rows > 0)) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'The budget is not exist.',
                'label' => 'this is not exist.',
                'type' => 'error2',
                'class' => 'btn-danger',
                'columns' => array('department_id', 'budget_year', 'budget_category_id'),
            ]);
        }
        
        $rows = $this->gsoPPMPRepository->validate($request->fund_code_id, $request->department_id, $request->budget_year, $request->budget_category_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a ppmp with an existing department/year.',
                'label' => 'this is an existing department.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'department_id',
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'control_no' => $this->gsoPPMPRepository->generate_control_no($request->budget_year),
            'fund_code_id' => $request->fund_code_id,
            'department_id' => $request->department_id,
            'budget_category_id' => $request->budget_category_id,
            'budget_year' => $request->budget_year,
            'remarks' => $request->remarks,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        $ppmp = $this->gsoPPMPRepository->create($details);        

        return response()->json(
            [
                'data' => $this->gsoPPMPRepository->copy($id, $ppmp->id, $timestamp, Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The ppmp has been successfully copied.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function edit(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'update');
        Session::put('identity', $id);
        $departments = $this->gsoPPMPRepository->allDepartmentsWithRestriction(Auth::user()->id);
        
        // $lines = $this->gsoPPMPRepository->find_lines($id, $request->get('division'));
        $ppmp = $this->gsoPPMPRepository->find($id);
        $divisions = $this->gsoPPMPRepository->allDivisions();
        $budgets = $this->gsoPPMPRepository->find_budgets($id);
        $budgetx = $this->gsoPPMPRepository->get_budgets($id);
        $items = array(); $budget_plans = array();
        foreach ($budgets as $budget) {
            $items[$budget->gl_account->id] = $this->gsoPPMPRepository->allItemsViaGL($budget->gl_account->id);
            $budget_plans[$budget->gl_account->id] = $this->gsoPPMPRepository->find_budget_plan($id, $budget->gl_account->id, $request->get('division'), $ppmp->budget_category_id);
        }
        $fund_codes = $this->gsoPPMPRepository->allFundCodes();
        $categories = $this->gsoPPMPRepository->allBudgetCategories();
        return view('general-services.project-procurement-management-plan.edit')->with(compact('budget_plans', 'fund_codes', 'budgetx', 'budgets', 'ppmp', 'departments', 'divisions', 'items', 'categories'));
    }

    public function manage(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'create');
        Session::put('identity', $id);
        $departments = $this->gsoPPMPRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $items = $this->gsoPPMPRepository->allItems();
        $divisions = $this->gsoPPMPRepository->allDivisions();
        $budgets = $this->gsoPPMPRepository->find_budgets($id);
        $budgetx = $this->gsoPPMPRepository->get_budgets($id);
        $items = array();
        foreach ($budgets as $budget) {
            $items[$budget->gl_account->id] = $this->gsoPPMPRepository->allItemsViaGL($budget->gl_account->id);
        }
        $ppmp = $this->gsoPPMPRepository->find($id);
        $fund_codes = $this->gsoPPMPRepository->allFundCodes();
        $categories = $this->gsoPPMPRepository->allBudgetCategories();
        return view('general-services.project-procurement-management-plan.manage')->with(compact('fund_codes', 'budgetx', 'budgets', 'ppmp', 'departments', 'divisions', 'items', 'categories'));
    }

    public function view(Request $request, $id)
    {   
        Session::put('identity', $id);
        $departments = $this->gsoPPMPRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $items = $this->gsoPPMPRepository->allItems();
        $divisions = $this->gsoPPMPRepository->allDivisions();
        $budgets = $this->gsoPPMPRepository->find_budgets($id);
        $budgetx = $this->gsoPPMPRepository->get_budgets($id);
        $items = array();
        foreach ($budgets as $budget) {
            $items[$budget->gl_account->id] = $this->gsoPPMPRepository->allItemsViaGL($budget->gl_account->id);
        }
        $ppmp = $this->gsoPPMPRepository->find($id);
        $fund_codes = $this->gsoPPMPRepository->allFundCodes();
        return view('general-services.project-procurement-management-plan.manage')->with(compact('fund_codes', 'budgetx', 'budgets', 'ppmp', 'departments', 'divisions', 'items'));
    }

    public function find_lines($ppmpID, $gl_account, $division)
    {
        return $this->gsoPPMPRepository->find_lines($ppmpID, $gl_account, $division);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetch_item_details(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoPPMPRepository->fetch_item_details($id),
            'validate' => $this->gsoPPMPRepository->validate_item_details($request->get('id'), $request->get('division'), $id)
        ]);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPPMPRepository->find($id)->status
        ]);
    }

    public function fetch_division_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPPMPRepository->fetch_division_status($id, $request->get('division'))
        ]);
    }

    public function update(Request $request, $ppmpID)
    {
        if ($ppmpID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                // 'department_id' => $request->department_id,
                // 'budget_year' => $request->budget_year,
                'remarks' => $request->remarks,
                'control_no' => $this->gsoPPMPRepository->generate_control_no($request->budget_year),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $ppmp = $this->gsoPPMPRepository->create($details);
            $ppmpID = $ppmp->id;
            Session::put('identity', $ppmpID);
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                // 'department_id' => $request->department_id,
                // 'budget_year' => $request->budget_year,
                'remarks' => $request->remarks,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoPPMPRepository->update($ppmpID, $details);
        }
        return response()->json([
            'data' => $this->gsoPPMPRepository->find($ppmpID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function get_identity()
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'identity' => (Session::get('identity') > 0) ? Session::get('identity') : 0,
            'data' =>  (Session::get('identity') > 0) ? $this->gsoPPMPRepository::find(Session::get('identity')) : ''
        ]);
    }

    public function get_item_field($gl_account)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'items' => $this->gsoPPMPRepository->allItemsViaGL($gl_account, 1)
        ]);
    }

    public function update_lines(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'data' => $this->gsoPPMPRepository->update_lines($ppmpID, $request, $this->carbon::now(), Auth::user()->id)
        ]);
    }

    public function lock_division(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'locked' => $this->gsoPPMPRepository->lock_division($ppmpID, $request->get('division'), $this->carbon::now(), Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully locked.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function check_if_division_locked($ppmpID, $division)
    {
        return $this->gsoPPMPRepository->check_if_division_locked($ppmpID, $division);
    }

    public function remove_lines(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        return response()->json([
            'data' => $this->gsoPPMPRepository->remove_lines($id, $details),
            'title' => 'Well done!',
            'text' => 'The item has been sucessfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $ppmpID)
    {   
        $res = $this->gsoPPMPRepository->find($ppmpID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->gsoPPMPRepository->update($ppmpID, $details),
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

    public function find(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPPMPRepository->find($ppmpID)
        ]);
    }

    public function fetch_remarks(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoPPMPRepository->find($ppmpID)->disapproved_remarks
        ]);
    }

    public function validate_division_status(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPPMPRepository->validate_division_status($ppmpID)
        ]);
    }

    public function fetch_budgets(Request $request, $ppmpID)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPPMPRepository->find_budgets($ppmpID)
        ]);
    }

    public function unlock(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'status' => 'draft',
            'is_adjusted' => 1,
            'approved_counter' => 1,
            'approved_at' => NULL,
            'approved_by' => NULL,
            'sent_at' => NULL,
            'sent_by' => NULL
        );
        $this->gsoPPMPRepository->update($id, $details);
        $details2 = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'status' => 'draft'
        );
        $this->gsoPPMPRepository->update_division_status($id, $details2);
        return response()->json([
            'title' => 'Well done!',
            'text' => 'The ppmp has been sucessfully unlocked.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function year_lists(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetRepository->year_lists()
        ]);
    }

    public function validate_item_removal(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'validate' => $this->gsoPPMPRepository->validate_item_removal($id)
        ]);
    }

    public function fetch_budget_lists(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPPMPRepository->fetch_budget_lists($request->get('fund'), $request->get('department'), $request->get('category'), $request->get('year'))
        ]);
    }
}
