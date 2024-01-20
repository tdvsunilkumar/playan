<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentUserAccountInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Hash;

class ComponentUserAccountController extends Controller
{
    private ComponentUserAccountInterface $componentUserAccountRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentUserAccountInterface $componentUserAccountRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentUserAccountRepository = $componentUserAccountRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/users/accounts';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $employees = $this->componentUserAccountRepository->allEmployees();
        $roles = $this->componentUserAccountRepository->allRoles(Auth::user()->roles()->first()->role_id);
        return view('components.users.accounts.index')->with(compact('employees', 'roles'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="edit this"><i class="ti-pencil text-white"></i></a>';
        }
        $actions .= '<a href="javascript:;" class="action-btn edit-dash-per-btn bg-success btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="edit this"><i class="ti-pencil text-white"></i></a>';
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->componentUserAccountRepository->listItems($request, Auth::user()->roles()->first()->role_id, Auth::user()->id);
        $res = $result->data->map(function($account) use ($statusClass, $actions, $canDelete) {
            $description = wordwrap($account->description, 25, "\n");
            if ($canDelete > 0) {
                $actions .= ($account->userStatus == 1) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="remove this"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm  align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="restore this"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $account->userID,
                'name' => $account->userName,
                'username' => $account->userEmail,
                'role' =>  $account->userRole,
                'modified' => $account->modified ? 
                '<strong>'.$account->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($account->userUpdatedAt)) : 
                '<strong>'.$account->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($account->userCreatedAt)),
                'status' => $statusClass[$account->userStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$account->userStatus]->bg. ' p-2">' . $statusClass[$account->userStatus]->status . '</span>' ,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            'recordsTotal'    => intval($result->count),  
			'recordsFiltered' => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $rows = $this->componentUserAccountRepository->validate($request->email, 'email');
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'The email is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'email',
            ]);
        }

        $rows = $this->componentUserAccountRepository->validate($request->get('name'), 'name');
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'This user is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'employee_id'
            ]);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'name' => $request->get('name'),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->componentUserAccountRepository->create($details, $request, $timestamp, Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The user account has been successfully saved.',
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
            'data' => $this->componentUserAccountRepository->find($id)
        ]);
    }
    public function editDeshPermission(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->componentUserAccountRepository->find($id)
        ]);
    }
    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->componentUserAccountRepository->validate($request->email, 'email', $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'The email is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'email'
            ]);
        }

        $rows = $this->componentUserAccountRepository->validate($request->get('name'), 'name', $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'This user is already existing.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'employee_id'
            ]);
        }

        $timestamp = $this->carbon::now();
        if ($request->password !== NULL) {
            $details = array(
                'name' => $request->get('name'),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        } else {
            $details = array(
                'name' => $request->get('name'),
                'email' => $request->email,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        }

        return response()->json([
            'data' => $this->componentUserAccountRepository->update($id, $details, $request, $timestamp, Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The user account has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
    public function updateDash(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        // $rows = $this->componentUserAccountRepository->validate($request->email, 'email', $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'The email is already existing.',
        //         'type' => 'error',
        //         'class' => 'btn-danger',
        //         'column' => 'email'
        //     ]);
        // }

        // $rows = $this->componentUserAccountRepository->validate($request->get('name'), 'name', $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'This user is already existing.',
        //         'type' => 'error',
        //         'class' => 'btn-danger',
        //         'column' => 'employee_id'
        //     ]);
        // }

        $timestamp = $this->carbon::now();
        // if ($request->password !== NULL) {
        //     $details = array(
        //         'name' => $request->get('name'),
        //         'email' => $request->email,
        //         'password' => Hash::make($request->password),
        //         'updated_at' => $this->carbon::now(),
        //         'updated_by' => Auth::user()->id
        //     );
        // } else {
        //     $details = array(
        //         'name' => $request->get('name'),
        //         'email' => $request->email,
        //         'updated_at' => $this->carbon::now(),
        //         'updated_by' => Auth::user()->id
        //     );
        // }

        return response()->json([
            'data' => $this->componentUserAccountRepository->updateDash($id, $request, $timestamp, Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The Dashboard Permission has been successfully updated.',
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
            'data' => $this->componentUserAccountRepository->modify($id, $details),
            'title' => 'Well done!',
            'text' => 'The user account has been successfully removed.',
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
            'data' => $this->componentUserAccountRepository->modify($id, $details),
            'title' => 'Well done!',
            'text' => 'The user account has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function load_menus(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->componentUserAccountRepository->load_menus($id),
            'permissions' => $this->componentUserAccountRepository->load_available_permissions()
        ]);
    }
}
