<?php

namespace App\Http\Controllers;
use App\Models\GsoItemCategory;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoItemCategoryRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminGsoItemCategoryController extends Controller
{
    private GsoItemCategoryRepositoryInterface $gsoItemCategoryRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoItemCategoryRepositoryInterface $gsoItemCategoryRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoItemCategoryRepository = $gsoItemCategoryRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/setup-data/item-categories';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $gl_accounts = $this->gsoItemCategoryRepository->allGLAccounts();
        return view('general-services.setup-data.item-categories.index')->with(compact('gl_accounts', 'can_create'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->gsoItemCategoryRepository->listItems($request);
        $res = $result->data->map(function($itemCategory) use ($statusClass, $actions, $canDelete) {
            $gl_account = wordwrap($itemCategory->gl_account->code . ' - ' . $itemCategory->gl_account->description, 25, "<br />\n");  
            $description = wordwrap($itemCategory->itcDescription, 25, "<br />\n"); 
            $remarks = wordwrap($itemCategory->itcRemarks, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($itemCategory->itcStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $itemCategory->itcId,
                'gl_account' => $itemCategory->gl_account->code . ' - ' . $itemCategory->gl_account->description,
                'gl_account_label' => '<div class="showLess">' . $gl_account . '</div>',
                'code' => $itemCategory->itcCode,
                'description' => $itemCategory->itcDescription,
                'description_label' => '<div class="showLess" title="' . $itemCategory->itcDescription . '">' . $description . '</div>',
                'remarks' => $itemCategory->itcRemarks,
                'remarks_label' => '<div class="showLess" title="' . $itemCategory->itcRemarks . '">' . $remarks . '</div>',
                'health_safety' => ($itemCategory->is_health_safety == 1) ? '<span class="badge badge-yes-no rounded-pill bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no rounded-pill bg-secondary p-2">No</span>',
                'modified' => ($itemCategory->itcUpdated_at !== NULL) ? date('d-M-Y', strtotime($itemCategory->itcUpdated_at)).'<br/>'. date('h:i A', strtotime($itemCategory->itcUpdated_at)) : date('d-M-Y', strtotime($itemCategory->itcCreated_at)).'<br/>'. date('h:i A', strtotime($itemCategory->itcCreated_at)),
                'status' => $statusClass[$itemCategory->itcStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$itemCategory->itcStatus]->bg. ' p-2">' . $statusClass[$itemCategory->itcStatus]->status . '</span>' ,
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
        $rows = $this->gsoItemCategoryRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a item category with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'gl_account_id' => $request->gl_account_id,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'remarks' => $request->remarks,
            'is_health_safety' => $request->get('safety'),
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoItemCategoryRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The item category has been successfully saved.',
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
            'data' => $this->gsoItemCategoryRepository->find($id),
            'validate' => $this->gsoItemCategoryRepository->validate_items($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->gsoItemCategoryRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a item category with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $row2 = $this->gsoItemCategoryRepository->validate_items($id);
        if ($row2 > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'Unable to proceed, this item category is already been used.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'gl_account_id' => $request->gl_account_id,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'remarks' => $request->remarks,
            'is_health_safety' => $request->get('safety'),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->gsoItemCategoryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item category has been successfully updated.',
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
            'data' => $this->gsoItemCategoryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item category has been successfully removed.',
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
            'data' => $this->gsoItemCategoryRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The item category has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
