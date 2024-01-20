<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrAppointment;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use File;

class HrAppointmentController extends Controller
{
     public $data = [];
     public $postdata = [];
    //  public $busn_log_status = ['0'=>"Draft",'1'=>"Filed",'2'=>"Approved",'3'=>"Decline"];
    public $department = array(""=>"Please Select");
    public $division = array(""=>"Please Select");
    public $employee = array(""=>"Please Select");

    public $employee_status = array(""=>"Please Select");
    public $employee_appointment_status = array(""=>"Please Select");
    public $payment_term = array(""=>"Please Select");
    public $occupation_lev = array(""=>"Please Select");
    public $salary_grade = array(""=>"Please Select");
    public $salary_grade_step = array(""=>"Please Select");

     public function __construct(Carbon $carbon){
		$this->_hrAppointment= new HrAppointment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->data = array('id'=>'','hr_emp_id'=>'','hra_department_id'=>'','hra_division_id'=>'','hra_employee_no'=>'','hra_date_hired'=>'','hra_designation'=>'','hres_id'=>'','hras_id'=>'','hrpt_id'=>'','hrol_id'=>'','hrsg_id'=>'','hrsgs_id'=>'','hra_monthly_rate'=>'','hra_annual_rate'=>'');  
        $this->slugs = 'hr-appointment'; 
        foreach ($this->_hrAppointment->getDepartment() as $val) {
            $this->department[$val->id]=$val->name;
        } 

        foreach ($this->_hrAppointment->getEmpStatus() as $val) {
            $this->employee_status[$val->id]=$val->hres_description;
        } 
        foreach ($this->_hrAppointment->getAptStatus() as $val) {
            $this->employee_appointment_status[$val->id]=$val->hras_description;
        } 
        foreach ($this->_hrAppointment->getPaymentTerm() as $val) {
            $this->payment_term[$val->id]=$val->hr_payment_term;
        } 
        foreach ($this->_hrAppointment->getOccuLev() as $val) {
            $this->occupation_lev[$val->id]=$val->hrol_description;
        } 
        foreach ($this->_hrAppointment->getSalaryGrade() as $val) {
            $this->salary_grade[$val->id]=$val->hrsg_salary_grade;
        } 
        foreach ($this->_hrAppointment->getSalaryGradeStep() as $val) {
            $this->salary_grade_step[$val->id]=$val->hrsgs_description;
        } 
        
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            $departments = $this->department;
            return view('HR.appointment.index',compact('departments'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrAppointment->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['emp_name']=$row->emp_name;
            $arr[$i]['emp_id']=$row->emp_id;
            $arr[$i]['dept_name']=$row->dept_name;
            $arr[$i]['div_name']=$row->div_name;
            $arr[$i]['designation']=$row->designation;
            $arr[$i]['hra_monthly_rate']=currency_format($row->hra_monthly_rate);
            $arr[$i]['salary_grade']=$row->hrsg_salary_grade;
            $arr[$i]['salary_step']=$row->hrsgs_description;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');                 
            $arr[$i]['action']='
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-appointment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit">
                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    '.$status.'
                    </div>';
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_hrAppointment->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Appointment ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_hrAppointment->validate_approver($this->_hrAppointment->getUserdapartment($id)->acctg_department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

    public function store(Request $request){
        $data = (object)$this->data;
        $currentYear = date('Y');
        $department =$this->department;
        $division=  $this->division;
        $employee=  $this->employee;
        $employee_status= $this->employee_status;
        $employee_appointment_status= $this->employee_appointment_status;
        $payment_term= config('constants.arrHrPaymentTerm');
        $occupation_lev= $this->occupation_lev;
        $salary_grade= $this->salary_grade;
        $salary_grade_step= $this->salary_grade_step;


        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrAppointment->find($request->input('id'));
            foreach ($this->_hrAppointment->getDivByDept($data->hra_department_id) as $val) {
                $this->division[$val->id]=$val->name;
            } 
            foreach ($this->_hrAppointment->getEmpByDiv($data->hra_division_id) as $val) {
                $this->employee[$val->id]=$val->fullname;
            } 
            $division=  $this->division;
            $employee=  $this->employee;
        
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['hra_hourly_rate'] = (currency_to_float($request->input('hra_monthly_rate')) / hr_policy('work_days')) / 8;
            if($request->input('id')>0){
                $this->_hrAppointment->updateData($request->input('id'),$this->data);
                $success_msg = 'Missed Log updated successfully.';
                // $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Work Schedule '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_hrAppointment->addData($this->data);
                $success_msg = 'Appointment created successfully.';
                // $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Default Schedule '".$this->data['hr_employeesid']."'";
            }
            return redirect()->route('hr-appointment.index')->with('success', __($success_msg));
    	}
        return view('HR.appointment.create',compact('data','employee_status','employee_appointment_status','payment_term','occupation_lev','salary_grade','salary_grade_step','department','division','employee'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hr_emp_id'=>'required',
                'hra_department_id'=>'required',
                'hra_division_id'=>'required',
                'hra_employee_no'=>'required',
                'hra_date_hired'=>'required',
                'hra_designation'=>'required',
                'hres_id'=>'required',
                'hras_id'=>'required',
                'hrpt_id'=>'required',
                'hrol_id'=>'required',
                'hrsg_id'=>'required',
                'hrsgs_id'=>'required',
                'hra_monthly_rate'=>'required',
                'hra_annual_rate'=>'required',
            ]
         ); 
       
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
    
    public function getDivByDept(Request $request){
        $getDiv = $this->_hrAppointment->getDivByDept($request->input('dept_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getDiv as $key => $value) {
          $htmloption .='<option value="'.$value->id.'">'.$value->name.'</option>';
        }
        echo $htmloption;
      }
    public function getEmpByDiv(Request $request){
        $getEmp = $this->_hrAppointment->getEmpByDiv($request->input('div_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getEmp as $key => $value) {
          $htmloption .='<option value="'.$value->id.'">'.$value->fullname.'</option>';
        }
        echo $htmloption;
      }
    public function getEmpdetById(Request $request){
        $getEmpDet = $this->_hrAppointment->getEmpdetById($request->input('emp_id'));
        return $getEmpDet;
      } 
    public function getSalaryDet(Request $request){
        $step='hrsg_step_'.$request->input('step_id');
        $getSalaryDet = $this->_hrAppointment->getSalaryDet($request->input('grade_id'));
        return $getSalaryDet->$step;
      }           
    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_hrAppointment->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->fhml_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->fhml_file_name."/".$arrDocumentss[0]->fhml_file_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_hrAppointment->deleteimagerowbyid($rid); 
              
                echo "deleted";
            }
        }
    }

    public function getEmployees(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Employee = $this->_hrAppointment->getEmployee($q);
        foreach ($Employee['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;//emp id
            $data['data'][$key]['text']=$value->fullname;
        }
        $data['data_cnt']=$Employee['data_cnt'];
        echo json_encode($data);
    }

    public function getEmployeesByDiv(Request $request, $div)
    {
        $q = $request->input('search');
        $data = [];
        $Employee = $this->_hrAppointment->getEmployee($q,$div);
        foreach ($Employee['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;//emp id
            $data['data'][$key]['text']=$value->fullname;
        }
        $data['data_cnt']=$Employee['data_cnt'];
        echo json_encode($data);
    }
}
