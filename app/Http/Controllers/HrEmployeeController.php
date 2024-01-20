<?php

namespace App\Http\Controllers;
use App\Models\HrEmployee;
use App\Models\CommonModelmaster;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\HrEmployeeRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrEmployeeController extends Controller
{
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private $carbon;
    private $slugs;

    public function __construct(HrEmployeeRepositoryInterface $hrEmployeeRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'human-resource/designations';
        // added
        $this->data = array(
            'id'=>'',
            "firstname" => null,
            "middlename" => null,
            "lastname" => null,
            "suffix" => null,
            "title" => null,
            "birthdate" => null,
            "hr_emp_birth_place" => null,
            "gender" => null,
            "hr_emp_civil_status" => "1",
            "hr_emp_height" => null,
            "hr_emp_weight" => null,
            "hr_emp_blood_type" => null,
            "mobile_no" => null,
            "telephone_no" => null,
            "fax_no" => null,
            "email_address" => null,
            "hr_emp_gsis_no" => null,
            "pag_ibig_no" => null,
            "philhealth_no" => null,
            "sss_no" => null,
            "tin_no" => null,
            "hr_emp_agency_emp_no" => null,
            "c_house_lot_no" => null,
            "c_street_name" => null,
            "c_subdivision" => null,
            "barangay_id" => null,
            "c_zip" => null,
            "hr_emp_house_lot_no_permanent" => null,
            "hr_emp_street_name_permanent" => null,
            "hr_emp_subdivision_permanent" => null,
            "hr_emp_brgy_code_permanent" => null,
            "hr_emp_city_code_permanent" => null,
            "hr_emp_province_code_permanent" => null,
            "acctg_department_id" => null,
            "acctg_department_division_id" => null,
            "hr_designation_id" => null,
            "identification_no" => null,
            "is_dept_restricted" => null,
            'hr_emp_citizenship' => null,
            'hr_emp_if_dual' => null,
            'hr_emp_if_dual_country' => null,
            'hr_emp_is_same_permanent' => null,
            'hr_emp_zip_code_permanent' => null,
        );  
    }    

    public function validateFormRequest($requests)
    {   
        foreach ($requests as $request) {
            if (!$request->departmental_access) {
                if(strpos($request, '<script>') !== false) {
                    return abort(401);
                }
            }
        }
        return true;
    }
    public function index(Request $request)
    {           
        $this->is_permitted($this->slugs, 'read');
        $gender = ['' => 'select a gender', 'Male' => 'Male', 'Female' => 'Female'];
        //$barangays = $this->hrEmployeeRepository->allBarangays();
        $barangays = ['' => 'select a barangay']; 
        $departments = $this->hrEmployeeRepository->allDepartments();
        $access = $this->hrEmployeeRepository->allDepartmentsMultiple();
        $divisions = ['' => 'select a division'];
        $designations = $this->hrEmployeeRepository->allDesignations();
        $restrictions = ['' => 'select a restriction', 'Yes' => 'Yes', 'No' => 'No'];
        $isopen=$request->input('isopenAddform');
        return view('human-resource.employees.index')->with(compact('access', 'barangays', 'gender', 'departments', 'divisions', 'designations', 'restrictions','isopen'));
    }

    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            // $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions .= '<div class="action-btn bg-warning ms-2">
                            <a class="mx-3 btn btn-sm  align-items-center" data-url="{{row_id}}" data-ajax-popup="true"  data-size="xxl" data-bs-toggle="tooltip" title="Manage Employee" data-title="Manage Employee">
                                <i class="ti-pencil text-white"></i>
                            </a>
                        </div>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->hrEmployeeRepository->listItems($request);
        $res = $result->data->map(function($employee) use ($statusClass, $actions, $canDelete) {
            $fullnamex = (strlen($employee->middlename) > 0) ? ucwords($employee->firstname).' '.ucwords($employee->middlename).' '.ucwords($employee->lastname) : ucwords($employee->firstname).' '.ucwords($employee->lastname);
            $fullname = $fullnamex ? wordwrap($fullnamex, 25, "\n") : '';     
            $address = $employee->current_address ? wordwrap($employee->current_address, 25, "\n") : '';            
            $designation = $employee->desigName ? wordwrap($employee->desigName, 25, "\n") : '';           
            $department = $employee->department->name ? wordwrap($employee->department->code . ' - ' . $employee->department->name . ' [' . $employee->division->code . ']', 25, "\n") : '';       
            // $division = $employee->divName ? wordwrap($employee->divName, 25, "\n") : '';    
            if ($canDelete > 0) {
                $actions .= ($employee->empStatus > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $employee->empId,
                'identification_no' => $employee->identification_no,
                'fullname' => '<div class="showLess" title="' . ($fullnamex ? $fullnamex : '') . '">' . $fullname . '</div>',
                'title' => $employee->title,
                'address' => '<div class="showLess" title="' . $employee->current_address . '">' . $address . '</div>',
                'mobile_no' => $employee->mobile_no,
                'designation' => '<div class="showLess" title="' . ($employee->depName  ? $employee->depName : ''). '">' . $designation . '</div>',
                'department' => '<div class="showLess" title="' . $employee->depName . '">' . $department . '</div>',
                // 'division' => '<div class="showLess" title="' . $employee->divName . '">' . $division . '</div>',
                'modified' => ($employee->empUpdatedAt !== NULL) ? date('d-M-Y', strtotime($employee->empUpdatedAt)).'<br/>'. date('h:i A', strtotime($employee->empUpdatedAt)) : date('d-M-Y', strtotime($employee->empCreated_at)).'<br/>'. date('h:i A', strtotime($employee->empCreated_at)),
                'status' => $statusClass[$employee->empStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$employee->empStatus]->bg. ' p-2">' . $statusClass[$employee->empStatus]->status . '</span>' ,
                'actions' => str_replace('{{row_id}}',route('hr.employees.create',['id'=>$employee->empId]),$actions)
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function upload_lists(Request $request, $id) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'download', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn download-btn bg-secondary btn m-1 btn-sm align-items-center" title="Download"><i class="ti-download text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->hrEmployeeRepository->listItemsUpload($request, $id);
        $res = $result->data->map(function($item) use ($statusClass, $actions, $canDelete) {
            $filename = ($item->name) ? wordwrap($item->name, 25, "\n") : '';
            return [
                'id' => $item->id,
                'file' => $item->name,
                'filename' => $filename,
                'type' => $item->type,
                'size' => $this->hrEmployeeRepository->formatSizeUnits($item->size),
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
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

    public function upload(Request $request, $id) 
    {   
        $this->is_permitted($this->slugs, 'upload');
        $timestamp = date('Y-m-d H:i:s');
        $uploaddir = $request->get('category') . '/' . $id;
        Storage::disk('uploads')->makeDirectory($uploaddir);

        foreach($_FILES as $file)
        {   
            $filename = basename($file['name']);
            if(Storage::put($uploaddir . '/' . $filename, (string) file_get_contents($file['tmp_name'])))
            {
                $files[] = $uploaddir . '/'. $filename;
                $exist = FileUpload::where(['name' => $file['name'], 'type' => $file['type'], 'category' => $request->get('category'), 'category_id' => $id])->get();
                if ($exist->count() > 0) {
                    $file = FileUpload::find($exist->first()->id);
                    $file->name = $file['name'];
                    $file->type = $file['type'];
                    $file->size = $file['size'];
                    $file->updated_at = $timestamp;
                    $file->updated_by = Auth::user()->id;

                    if (!$file->update()) {
                        throw new NotFoundHttpException();
                    }
                    
                    // audit logs here
                } else {
                    $file = FileUpload::create([
                        "category" => $request->get('category'),
                        "category_id" => $id,
                        "name" => $file['name'],
                        "type" => $file['type'],
                        "size" => $file['size'],
                        'created_at' => $timestamp,
                        'created_by' => Auth::user()->id
                    ]);

                    if(!$file) {
                        throw new NotFoundHttpException();
                    }

                    // audit logs here
                }
            }
        }

        return response()->json([
            'data' => $_FILES,
            'text' => 'The request has been successfully uploaded.',
            'type' => 'success',
            'status' => 'success'
        ]);
    }

    public function download(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'download');
        return response()->download(public_path('uploads/'.$request->get('category').'/'.$id.'/'.$request->get('file')));
    }

    public function delete(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'remove');
        File::delete(public_path('uploads/'.$request->get('category').'/'.$id.'/'.$request->get('file')));
        return response()->json([
            'data' => $this->hrEmployeeRepository->delete($request->get('id')),
            'title' => 'Well done!',
            'text' => 'The uploaded file from employee has been successfully deleted.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        // $this->validateFormRequest($request->all());

        $rows = $this->hrEmployeeRepository->validate($request->identification_no);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create an employee with an existing id no.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $addr = array();
        if ($request->c_house_lot_no !== NULL) { $addr[] = $request->c_house_lot_no; }
        if ($request->c_street_name !== NULL) { $addr[] = $request->c_street_name; }
        if ($request->c_subdivision !== NULL) { $addr[] = $request->c_subdivision; }

        $fullname = '';
        if (strlen($request->title) > 0 && $request->title != NULL) {
            $fullname .= ucwords($request->title).' ';
        } 
        $fullname .= ucwords($request->firstname).' ';
        if (strlen($request->middlename && $request->middlename != NULL) > 0) { 
            $fullname .= ucwords($request->middlename).' ';
        }
        $fullname .= ucwords($request->lastname);
        if (strlen($request->suffix) > 0 && $request->suffix != NULL) { 
            $fullname .= ', '.ucwords($request->suffix);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'barangay_id' => $request->barangay_id,
            'acctg_department_id' => $request->acctg_department_id,
            'acctg_department_division_id' => $request->acctg_department_division_id,
            'hr_designation_id' => $request->hr_designation_id,
            'identification_no' => $request->identification_no,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'fullname' => $fullname,
            'suffix' => $request->suffix,
            'title' => $request->title,
            'gender' => $request->gender,
            'birthdate' => date('Y-m-d', strtotime($request->birthdate)),
            'c_house_lot_no' => $request->c_house_lot_no,
            'c_street_name' => $request->c_street_name,
            'c_subdivision' => $request->c_subdivision,
            'current_address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'email_address' => $request->email_address,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'fax_no' => $request->fax_no,
            'sss_no' => $request->sss_no,
            'tin_no' => $request->tin_no,
            'pag_ibig_no' => $request->pag_ibig_no,
            'philhealth_no' => $request->philhealth_no,
            'is_dept_restricted' => ($request->is_dept_restricted == 'Yes') ? 1 : 0,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [   
                'data' => $this->hrEmployeeRepository->create($details, $request->departmental_access, $timestamp, Auth::user()->id),
                'title' => 'Well done!',
                'text' => 'The employee has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $data = $this->hrEmployeeRepository->find($id);
        foreach ($this->_commonmodel->getBarangay($data->barangay_id)['data'] as $val) {
            $data->barangay_id .= "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        return response()->json([
            'data' => $data,
            'access' => $this->hrEmployeeRepository->findAccess($id),
        ]);
    }
    
    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        // $this->validateFormRequest($request->all());

        $rows = $this->hrEmployeeRepository->validate($request->code, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a employee with an existing id no.',
                'type' => 'error',
                'class' => 'btn-danger'
            ]);
        }

        $addr = array();
        if ($request->c_house_lot_no !== NULL) { $addr[] = $request->c_house_lot_no; }
        if ($request->c_street_name !== NULL) { $addr[] = $request->c_street_name; }
        if ($request->c_subdivision !== NULL) { $addr[] = $request->c_subdivision; }

        $fullname = '';
        if (strlen($request->title) > 0 && $request->title != NULL) {
            $fullname .= ucwords($request->title).' ';
        } 
        $fullname .= ucwords($request->firstname).' ';
        if (strlen($request->middlename && $request->middlename != NULL) > 0) { 
            $fullname .= ucwords($request->middlename).' ';
        }
        $fullname .= ucwords($request->lastname);
        if (strlen($request->suffix) > 0 && $request->suffix != NULL) { 
            $fullname .= ', '.ucwords($request->suffix);
        }

        $timestamp = $this->carbon::now();
        $details = array(
            'barangay_id' => $request->barangay_id,
            'acctg_department_id' => $request->acctg_department_id,
            'acctg_department_division_id' => $request->acctg_department_division_id,
            'hr_designation_id' => $request->hr_designation_id,
            'identification_no' => $request->identification_no,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'fullname' => $fullname,
            'suffix' => $request->suffix,
            'title' => $request->title,
            'gender' => $request->gender,
            'birthdate' => date('Y-m-d', strtotime($request->birthdate)),
            'c_house_lot_no' => $request->c_house_lot_no,
            'c_street_name' => $request->c_street_name,
            'c_subdivision' => $request->c_subdivision,
            'current_address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'email_address' => $request->email_address,
            'telephone_no' => $request->telephone_no,
            'mobile_no' => $request->mobile_no,
            'fax_no' => $request->fax_no,
            'sss_no' => $request->sss_no,
            'tin_no' => $request->tin_no,
            'pag_ibig_no' => $request->pag_ibig_no,
            'philhealth_no' => $request->philhealth_no,
            'is_dept_restricted' => ($request->is_dept_restricted == 'Yes') ? 1 : 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->hrEmployeeRepository->update($id, $details, $request->departmental_access, $timestamp, Auth::user()->id),
            'title' => 'Well done!',
            'text' => 'The employee has been successfully updated.',
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
            'data' => $this->hrEmployeeRepository->remove_restore($id, $details),
            'title' => 'Well done!',
            'text' => 'The employee has been successfully removed.',
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
            'data' => $this->hrEmployeeRepository->remove_restore($id, $details),
            'title' => 'Well done!',
            'text' => 'The employee has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
        ]);
    }

    // new employee create
    public function index2(Request $request)
    {
        $isopen = '';
        $departments = $this->hrEmployeeRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        return view('human-resource.employees.index-new')->with(compact('departments', 'divisions','isopen'));

    }
    public function create(Request $request)
    {
        if($request->id > 0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        // select
        $gender = ['' => 'select a gender', 'Male' => 'Male', 'Female' => 'Female'];
        $civil_status = config('constants.citCivilStatus');
        $educ_background = config('constants.hrEducationBackground');
        $barangays = ['' => 'select a barangay']; 
        $barangays_perm = ['' => 'select a barangay']; 
        $departments = $this->hrEmployeeRepository->allDepartments();
        $access = $this->hrEmployeeRepository->allDepartmentsMultiple();
        $divisions = ['' => 'select a division'];
        $designations = $this->hrEmployeeRepository->allDesignations();
        $restrictions = ['' => 'select a restriction', '1' => 'Yes', '0' => 'No'];
        $emp_status =  $this->hrEmployeeRepository->selectEmploymentStatus();
        $emp_appointment_status = $this->hrEmployeeRepository->selectAppointmentStatus();
        $emp_pay_term = config('constants.arrHrPaymentTerm');
        $emp_occupation_lvl = $this->hrEmployeeRepository->selectOccupationalLevel();
        $emp_salary_grade = $this->hrEmployeeRepository->selectSalaryGrade();
        $emp_salary_step = $this->hrEmployeeRepository->selectSalaryGradeStep();

        $data = (object)$this->data;
        $emp = new HrEmployee();
        // dd($emp->getData());

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrEmployee::find($request->input('id'));
            $emp = HrEmployee::find($request->input('id'));
            $barangays_perm = ($data->brgy_perm) ? [$data->brgy_perm->id => $data->brgy_perm->brgy_name] : []; 
            $barangays = [$data->brgy->id => $data->brgy->brgy_name]; 
            $divisions = [$data->division->id => $data->division->name]; 
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['is_various'] = 0;
            $this->data['is_address'] = 0;
            
                HrEmployee::addEmployee(
                    $this->data,
                    [
                        'access'=>$request->departmental_access,
                        "family" => $request->family,
                        "children" => $request->Children,
                        "educ" => $request->educ,
                        "civil" => $request->civil,
                        "work" => $request->work,
                        "voluntary" => $request->voluntary,
                        "training" => $request->training,
                        "skills" => $request->skills,
                        "recognition" => $request->recognition,
                        "orgs" => $request->orgs,
                        "other" => $request->other,
                        "reference" => $request->reference,
                        "appoint" => $request->appoint,
                    ]
                );
                $success_msg = "Employee Added";
            // dd('done');
            return redirect()->route('hr.employees.index')->with('success', __($success_msg));
        }
        
        return view('human-resource.employees.create-new',compact('data','gender','barangays','barangays_perm','departments','access','divisions','designations','restrictions','civil_status','educ_background','emp_status','emp_appointment_status','emp_pay_term','emp_occupation_lvl','emp_salary_grade','emp_salary_step','emp'));
    }

    public function validation(Request $request){
        $rules = [];
        if ((int)$request->btn_step === 1) {
            $rules = [
                'firstname'=> 'required',
                'lastname'=> 'required',
                'barangay_id'=> 'required',
                'gender'=> 'required',
                'lastname'=> 'required',
            ];
        }
        if ($request->button) {
            $rules = [
                'acctg_department_id'=> 'required',
                'acctg_department_division_id'=> 'required',
                'hr_designation_id'=> 'required',
                'identification_no'=> 'required',
                'is_dept_restricted'=> 'required',
                'appoint.hra_date_hired'=> 'required',
                'appoint.hres_id'=> 'required',
                'appoint.hras_id'=> 'required',
                'appoint.hrpt_id'=> 'required',
                'appoint.hrol_id'=> 'required',
                'appoint.hrsg_id'=> 'required',
                'appoint.hrsgs_id'=> 'required',
                'appoint.hra_monthly_rate'=> 'required',
                'appoint.hra_annual_rate'=> 'required',
            ];
        }
        $validator = \Validator::make(
            $request->all(), $rules
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $fieldname = $messages->keys()[0];
            $fieldname = explode('.',$fieldname);
            $arr['field_name'] = end($fieldname);
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        
        echo json_encode($arr);exit;
    }
    public function selectDivision(Request $request, $department)
    {
        $q = $request->input('search');
        $data = [];
        $divisions = $this->hrEmployeeRepository->reload_division($department,$q);
        foreach ($divisions as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->name;
        }
        $data['data_cnt']=$divisions->count();
        echo json_encode($data);
    }
    public function removeRowRelation(Request $request)
    {
        HrEmployee::removeRelation($request->type, $request->id);
        echo json_encode('Removed');
    }
}
