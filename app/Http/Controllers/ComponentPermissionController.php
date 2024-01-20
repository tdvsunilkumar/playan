<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentPermissionInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentPermissionController extends Controller
{
    private ComponentPermissionInterface $componentPermissionRepository;
    private $carbon;

    public function __construct(ComponentPermissionInterface $componentPermissionRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentPermissionRepository = $componentPermissionRepository;
        $this->carbon = $carbon;
    }

    public function validated($permission) 
    {
        
        return redirect()->back()->with('error', __('Permission denied.'));
       
    }

    public function index(Request $request)
    {   
        // $this->validated('manage admin gso permisison');
        return view('components.permissions.index');
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->componentPermissionRepository->listItems($request);
        $res = $result->data->map(function($permission) use ($statusClass) {
            $description = wordwrap($permission->description, 25, "<br />\n");
            $actions = ($permission->is_active > 0) ? '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a><a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a><a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            return [
                'id' => $permission->id,
                'code' => $permission->code,
                'name' => $permission->name,
                'description' => '<div class="showLess" title="' . $permission->description . '">' . $description . '</div>',
                'modified' => ($permission->updated_at !== NULL) ? 
                '<strong>'.$permission->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($permission->updated_at)) : 
                '<strong>'.$permission->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($permission->created_at)),
                'status' => $statusClass[$permission->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$permission->is_active]->bg. ' p-2">' . $statusClass[$permission->is_active]->status . '</span>' ,
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
        // $this->validated('create admin gso permisison');

        $rows = $this->componentPermissionRepository->validate(strtolower(str_replace(' ', '-', $request->name)));
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a permisison with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => strtolower(str_replace(' ', '-', $request->name)),
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->componentPermissionRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The permisison has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso permisison');
        return response()->json([
            'data' => $this->componentPermissionRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso permisison');

        $rows = $this->componentPermissionRepository->validate(strtolower(str_replace(' ', '-', $request->name)), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a permisison with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => strtolower(str_replace(' ', '-', $request->name)),
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->componentPermissionRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The permisison has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso permisison');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->componentPermissionRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The permisison has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso permisison');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->componentPermissionRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The permisison has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
