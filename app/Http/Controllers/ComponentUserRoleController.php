<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentUserRoleInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentUserRoleController extends Controller
{
    private ComponentUserRoleInterface $componentUserRoleRepository;
    private $carbon;

    public function __construct(ComponentUserRoleInterface $componentUserRoleRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentUserRoleRepository = $componentUserRoleRepository;
        $this->carbon = $carbon;
    }

    public function validated($Role) 
    {
       
        return redirect()->back()->with('error', __('Role denied.'));
        
    }

    public function index(Request $request)
    {   
        // $this->validated('manage admin gso role');
        return view('components.users.roles.index');
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $result = $this->componentUserRoleRepository->listItems($request, Auth::user()->roles()->first()->role_id);
        $res = $result->data->map(function($role) use ($statusClass) {
            $description = wordwrap($role->description, 25, "<br />\n");
            $actions = ($role->is_active > 0) ? '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a><a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a><a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            return [
                'id' => $role->id,
                'code' => $role->code,
                'name' => $role->name,
                'description' => '<div class="showLess" title="' . $role->description . '">' . $description . '</div>',
                'modified' => ($role->updated_at !== NULL) ? 
                '<strong>'.$role->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($role->updated_at)) : 
                '<strong>'.$role->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($role->created_at)),
                'status' => $statusClass[$role->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$role->is_active]->bg. ' p-2">' . $statusClass[$role->is_active]->status . '</span>' ,
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
        // $this->validated('create admin gso role');

        $rows = $this->componentUserRoleRepository->validate(strtolower(str_replace(' ', '-', $request->name)));
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a role with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => strtolower(str_replace(' ', '-', $request->name)),
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->componentUserRoleRepository->create($details, $request, $timestamp, Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The role has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso role');
        return response()->json([
            'data' => $this->componentUserRoleRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit admin gso role');

        $rows = $this->componentUserRoleRepository->validate(strtolower(str_replace(' ', '-', $request->name)), $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a role with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'code' => strtolower(str_replace(' ', '-', $request->name)),
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->componentUserRoleRepository->update($id, $details, $request, $timestamp, Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The role has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso role');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->componentUserRoleRepository->update($id, $details, '', '', ''),
            'title' => 'Well done!',
            'text' => 'The role has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        // $this->validated('delete admin gso role');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->componentUserRoleRepository->update($id, $details, '', '', ''),
            'title' => 'Well done!',
            'text' => 'The role has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function load_menus(Request $request, $id, $user = 0)
    {   
        return response()->json([
            'data' => $this->componentUserRoleRepository->load_menus($id, $user),
            'permissions' => $this->componentUserRoleRepository->load_available_permissions()
        ]);
    }
    public function load_menus_dash(Request $request, $id, $user = 0)
    {   
        return response()->json([
            'data' => $this->componentUserRoleRepository->load_menus_dash($id, $user),
            'permissions' => ["test","test2","test3"]
        ]);
    }
}
