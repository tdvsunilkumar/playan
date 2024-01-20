<?php

namespace App\Http\Controllers;
use App\Models\AcctgDepartment;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgDepartmentRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgDepartmentController extends Controller
{
    private AcctgDepartmentRepositoryInterface $departmentRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgDepartmentRepositoryInterface $departmentRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->departmentRepository = $departmentRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/departments';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $functions = $this->departmentRepository->allDepartmentFunctions();
        $employees = $this->departmentRepository->allEmployees('head/oic');
        $designations = $this->departmentRepository->allDesignations();
        return view('accounting.departments.index')->with(compact('functions', 'employees', 'designations', 'can_create'));
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
        $result = $this->departmentRepository->listItems($request);
        $res = $result->data->map(function($department) use ($statusClass, $actions, $canDelete) {
            $name = wordwrap($department->depName, 25, "<br />\n"); 
            $head = wordwrap(($department->employee ? $department->employee->fullname : ''), 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($department->depStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $department->depId,
                'code' => $department->depCode,
                'financial' => $department->financial_code,
                'name' => '<div class="showLess" title="' . $department->depName . '">' . $name . '</div>',
                'head' => '<div class="showLess" title="' . ($department->employee ? $department->employee->fullname : '') . '">' . $head . '</div>',
                'designation' => $department->desigName,
                'modified' => ($department->depUpdatedAt !== NULL) ? date('d-M-Y', strtotime($department->depUpdatedAt)).'<br/>'. date('h:i A', strtotime($department->depUpdatedAt)) : date('d-M-Y', strtotime($department->depCreatedAt)).'<br/>'. date('h:i A', strtotime($department->depCreatedAt)),
                'status' => $statusClass[$department->depStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$department->depStatus]->bg. ' p-2">' . $statusClass[$department->depStatus]->status . '</span>' ,
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

    public function line_lists(Request $request, $id) 
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
        $result = $this->departmentRepository->line_listItems($request, $id);
        $res = $result->data->map(function($division) use ($statusClass, $actions, $canDelete) {
            $name = wordwrap($division->name, 25, "<br />\n");       
            if ($canDelete > 0) {
                $actions .= ($division->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $division->id,
                'code' => $division->code,
                'name' => '<div class="showLess" title="' . $division->name . '">' . $name . '</div>',
                'modified' => ($division->updated_at !== NULL) ? date('d-M-Y', strtotime($division->updated_at)).'<br/>'. date('h:i A', strtotime($division->updated_at)) : date('d-M-Y', strtotime($division->created_at)).'<br/>'. date('h:i A', strtotime($division->created_at)),
                'status' => $statusClass[$division->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$division->is_active]->bg. ' p-2">' . $statusClass[$division->is_active]->status . '</span>' ,
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
        $rows = $this->departmentRepository->validate($request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a department with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'financial_code' => $request->financial_code,
            'shortname' => $request->shortname,
            'program' => $request->program,
            'remarks' => $request->remarks,
            'acctg_department_function_id' => $request->acctg_department_function_id,
            'hr_employee_id' => $request->hr_employee_id,
            'hr_designation_id' => $request->hr_designation_id,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->departmentRepository->create($details),
                'title' => 'Well done!',
                'text' => 'The department has been successfully saved.',
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
            'data' => $this->departmentRepository->find($id),
            'items' => $this->departmentRepository->findLineItems($id)
            ->map(function($division) {
                $status = ($division->is_active == 1) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>';
                $icon   = ($division->is_active == 1) ? '<i class="ti-trash"></i>' : '<i class="ti-reload"></i>';
                return (object) [
                    'id' => $division->id,
                    'code' => $division->code,
                    'name' => $division->name,
                    'modified' => ($division->updated_at !== NULL) ? date('d-M-Y', strtotime($division->updated_at)).'<br/>'. date('h:i A', strtotime($division->updated_at)) : date('d-M-Y', strtotime($division->created_at)).'<br/>'. date('h:i A', strtotime($division->created_at)),
                    'status' => $status,
                    'icon' => $icon
                ];
            })
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $rows = $this->departmentRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a department with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }
        
        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'financial_code' => $request->financial_code,
            'shortname' => $request->shortname,
            'program' => $request->program,
            'remarks' => $request->remarks,
            'acctg_department_function_id' => $request->acctg_department_function_id,
            'hr_employee_id' => $request->hr_employee_id,
            'hr_designation_id' => $request->hr_designation_id,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->departmentRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The department has been successfully updated.',
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
            'data' => $this->departmentRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The department has been successfully removed.',
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
            'data' => $this->departmentRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The department has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function storeLineItem(Request $request, $departmentId): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();

        $rows = $this->departmentRepository->validateDivision($departmentId, $request->code);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a department division with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'acctg_department_id' => $departmentId,
            'code' => $request->code,
            'name' => $request->name,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->departmentRepository->createLineItem($details),
                'title' => 'Well done!',
                'text' => 'The department division has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand',
                'status' =>  '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>',
                'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
            ],
            Response::HTTP_CREATED
        );
    }

    public function findLineItem(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->departmentRepository->findLineItem($id)
        ]);
    }

    public function updateLineItem(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        
        $rows = $this->departmentRepository->validateDivision($this->departmentRepository->findLineItem($id)->acctg_department_id, $request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a department division with an existing code.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $details = array(
            'code' => $request->code,
            'name' => $request->name,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        
        return response()->json([
            'data' => $this->departmentRepository->updateLineItem($id, $details),
            'title' => 'Well done!',
            'text' => 'The department division has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand',
            'status' => ($this->departmentRepository->findLineItem($id)->is_active > 0) ? '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>' : '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>',
            'modified_at' => date('d-M-Y', strtotime($timestamp)).'<br/>'. date('h:i A', strtotime($timestamp))
        ]);
    }

    public function removeLineItem(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->departmentRepository->updateLineItem($id, $details),
            'title' => 'Well done!',
            'text' => 'The department division has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-reload"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-secondary p-2">Inactive</span>'
        ]);
    }

    public function restoreLineItem(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->departmentRepository->updateLineItem($id, $details),
            'title' => 'Well done!',
            'text' => 'The department division has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand',
            'icon' => '<i class="ti-trash"></i>',
            'status' => '<span class="badge badge-status rounded-pill bg-info p-2">Active</span>'
        ]);
    }

    public function fetch_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->departmentRepository->fetch_designation($employee)
        ]);
    }
}
