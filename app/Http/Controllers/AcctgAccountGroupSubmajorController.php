<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGroupSubsubmajor;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgAccountGroupSubmajorRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgAccountGroupSubmajorController extends Controller
{
    private AcctgAccountGroupSubmajorRepositoryInterface $accountGroupSubmajorRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountGroupSubmajorRepositoryInterface $accountGroupSubmajorRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->accountGroupSubmajorRepository = $accountGroupSubmajorRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/chart-of-accounts/submajor-account-groups';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $account_groups = $this->accountGroupSubmajorRepository->allAccountGroups();
        $major_account_groups = ['' => 'select a major account group'];
        return view('accounting.chart-of-accounts.submajor-account-groups.index')->with(compact('account_groups', 'major_account_groups', 'can_create'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }  
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->accountGroupSubmajorRepository->listItems($request);
        $res = $result->data->map(function($accountGroupSubmajor) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($accountGroupSubmajor->agsPrefix . ' - ' . $accountGroupSubmajor->agsDesc, 25, "\n");            
            $accountGroup = $accountGroupSubmajor->account_group ? $accountGroupSubmajor->account_group->code . ' - ' . $accountGroupSubmajor->account_group->description : '';
            $majorAccountGroup = $accountGroupSubmajor->major_account_group ? $accountGroupSubmajor->major_account_group->prefix . ' - ' . $accountGroupSubmajor->major_account_group->description : '';
            if ($canDelete > 0) {
                $actions .= ($accountGroupSubmajor->agsStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $accountGroupSubmajor->agsId,
                'group' => $accountGroup,
                'major' => $majorAccountGroup,
                'code' => $accountGroupSubmajor->agsCode,
                'description' => '<div class="showLess" title="' . $accountGroupSubmajor->agsPrefix . ' - ' . $accountGroupSubmajor->agsDesc . '">' . $description . '</div>',
                'modified' => ($accountGroupSubmajor->agsUpdatedAt !== NULL) ? date('d-M-Y', strtotime($accountGroupSubmajor->agsUpdatedAt)).'<br/>'. date('h:i A', strtotime($accountGroupSubmajor->agsUpdatedAt)) : date('d-M-Y', strtotime($accountGroupSubmajor->agsCreatedAt)).'<br/>'. date('h:i A', strtotime($accountGroupSubmajor->agsCreatedAt)),
                'status' => $statusClass[$accountGroupSubmajor->agsStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$accountGroupSubmajor->agsStatus]->bg. ' p-2">' . $statusClass[$accountGroupSubmajor->agsStatus]->status . '</span>' ,
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
    

    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $rows = $this->accountGroupSubmajorRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a submajor account group with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'acctg_account_group_major_id' => $request->acctg_account_group_major_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountGroupSubmajorRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The submajor account group has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGroupSubmajorRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->accountGroupSubmajorRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a submajor account group with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'acctg_account_group_major_id' => $request->acctg_account_group_major_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->accountGroupSubmajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The submajor account group has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
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

        return response()->json([
            'data' => $this->accountGroupSubmajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The submajor account group has been successfully removed.',
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

        return response()->json([
            'data' => $this->accountGroupSubmajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The submajor account group has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_group_code(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $account = (!empty($request->get('account'))) ? $this->accountGroupSubmajorRepository->findAcctGrp($request->get('account'))->code : '';
        $major = (!empty($request->get('major'))) ? $this->accountGroupSubmajorRepository->findMajorAcctGrp($request->get('major'))->prefix : '';
        return response()->json([
            'account' => $account,
            'major' => $major
        ]);
    }

    public function reload_major_account(Request $request, $account) 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->accountGroupSubmajorRepository->reload_major_account($account)
        ]);
    }
}
