<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGroupMajor;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgAccountGroupMajorRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgAccountGroupMajorController extends Controller
{
    private AcctgAccountGroupMajorRepositoryInterface $accountGroupMajorRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountGroupMajorRepositoryInterface $accountGroupMajorRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->accountGroupMajorRepository = $accountGroupMajorRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/chart-of-accounts/major-account-groups';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $account_groups = $this->accountGroupMajorRepository->allAccountGroups();
        return view('accounting.chart-of-accounts.major-account-groups.index')->with(compact('account_groups', 'can_create'));
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
        $result = $this->accountGroupMajorRepository->listItems($request);
        $res = $result->data->map(function($accountGroupMajor) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($accountGroupMajor->agmPrefix . ' - ' . $accountGroupMajor->agmDesc, 25, "\n");            
            if ($canDelete > 0) {
                $actions .= ($accountGroupMajor->agmStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            $accountGroup = $accountGroupMajor->account_group ? $accountGroupMajor->account_group->code .' - ' . $accountGroupMajor->account_group->description : '';
            return [
                'id' => $accountGroupMajor->agmId,
                'group' => $accountGroup,
                'code' => $accountGroupMajor->agmCode,
                'description' => '<div class="showLess" title="' . $accountGroupMajor->agmPrefix . ' - ' . $accountGroupMajor->agmDesc . '">' . $description . '</div>',
                'modified' => ($accountGroupMajor->agmUpdatedAt !== NULL) ? date('d-M-Y', strtotime($accountGroupMajor->agmUpdatedAt)).'<br/>'. date('h:i A', strtotime($accountGroupMajor->agmUpdatedAt)) : date('d-M-Y', strtotime($accountGroupMajor->agmCreatedAt)).'<br/>'. date('h:i A', strtotime($accountGroupMajor->agmCreatedAt)),
                'status' => $statusClass[$accountGroupMajor->agmStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$accountGroupMajor->agmStatus]->bg. ' p-2">' . $statusClass[$accountGroupMajor->agmStatus]->status . '</span>' ,
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
        $rows = $this->accountGroupMajorRepository->validate($request->get('code'), $request->acctg_account_group_id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a major account group with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->accountGroupMajorRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The major account group has been successfully saved.',
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
            'data' => $this->accountGroupMajorRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->accountGroupMajorRepository->validate($request->get('code'), $request->acctg_account_group_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a major account group with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_account_group_id' => $request->acctg_account_group_id,
            'prefix' => $request->prefix,
            'code' => $request->get('code'),
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->accountGroupMajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The major account group has been successfully updated.',
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
            'data' => $this->accountGroupMajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The major account group has been successfully removed.',
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
            'data' => $this->accountGroupMajorRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The major account group has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
