<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrWorkSchedule;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HR\HrAppointment;

class HrWorkScheduleController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $department = array(""=>"Please Select");
        public $division = array(""=>"Please Select");
    
     public function __construct(){
		$this->_hrworkschedule= new HrWorkSchedule(); 
		$this->_hrAppointment= new HrAppointment(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hrds_id'=>'');  
        $this->slugs = 'hr-work-schedule'; 
        foreach ($this->_hrworkschedule->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
        foreach ($this->_hrAppointment->getDepartment() as $val) {
            $this->department[$val->id]=$val->name;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
        $departments =$this->department;
        return view('HR.workschedule.index',compact('departments'));
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrworkschedule->getList($request);
        $select_date=$request->input('select_date') ? $request->input('select_date') : Carbon::today()->toDateString();
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['hrds_date']=date('F', strtotime($row->year."-".$row->month."-01")); 
            $arr[$i]['schedule']=date("h:i a", strtotime($row->hrds_start_time))." to ".date("h:i a", strtotime($row->hrds_end_time));
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-work-schedule/store?id='.$row->id).'&date='.$select_date.'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Holidays">
                        <i class="ti-pencil text-white"></i>
                    </a>
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
        $this->_hrworkschedule->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Leave Adjustment ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $department =$this->department;
        $division=  $this->division;
        $arrEmployee = $this->employee;
        $arrdefaultschedule = array();
        $data->start_date = Carbon::now()->toDateString();
        foreach ($this->_hrworkschedule->getDefaultschedule() as $val) {
                $arrdefaultschedule[$val->id]=date("h:i a", strtotime($val->hrds_start_time))." ".date("h:i a", strtotime($val->hrds_end_time));
        } 
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrWorkSchedule::find($request->input('id'));
        }
        if($request->input('date')>0 && $request->input('submit')==""){
            $data->start_date = $request->input('date');
        }
		if($request->input('submit')!=""){
            // Save Work Schedule
            foreach ($request->sched as $value) {
                // dd($value);
                $value = (object)$value;
                HrWorkSchedule::updateWorkSched($value,Auth::user()->hr_employee->id);
                
            }
            $success_msg = 'Work Schedule Updated';
            return redirect()->route('hrworkschedule.index')->with('success', __($success_msg));
    	}
        return view('HR.workschedule.create',compact('data','arrdefaultschedule','arrEmployee','division','department'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                // 'hr_employeesid'=>'required|unique:hr_work_schedules,hr_employeesid,'.(int)$request->input('id').',id,year,'.date('Y'),
                // 'end_date'=>'required|date|after_or_equal:start_date',
                'hrds_id'=>'required',
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

    public function storeEmployee(Request $request){
        HrWorkSchedule::updateWorkSched($request);
        $success_msg = 'Work Schedule Updated';
        return redirect()->route('hrworkschedule.index')->with('success', __($success_msg));
    }

    public function formValidationEmployee(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                // 'hr_employeesid'=>'required|unique:hr_work_schedules,hr_employeesid,'.(int)$request->input('id').',id,year,'.date('Y'),
                // 'sched.*.end_date'=>'required|date|after_or_equal:sched.*.start_date',
                'hrds_id'=>'required',
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
    public function getEmployeeSched(Request $request){
        $sched_id = $request->sched_id;
        $emp_id = $request->emp_id;
        $date = $request->date;

        $now = Carbon::now();
        if ($emp_id && $date) {
            return json_encode(HrWorkSchedule::where([['hrds_date',$date],['hr_employeesid',$emp_id]])->first());
        }
        if ($emp_id) {
            return json_encode(HrWorkSchedule::where([['hrds_id',$sched_id],['hrds_date',$now->toDateString()],['hr_employeesid',$emp_id]])->first());
        }
        if ($sched_id) {
            return json_encode(HrWorkSchedule::where([['hrds_id',$sched_id],['hrds_date',$now->toDateString()]])->get());
        }
    }

    public function getEmployeeDateSched(Request $request){
        $emp_id = $request->emp_id;
        $now = Carbon::now();
        return json_encode(HrWorkSchedule::where([['hrds_date',$date],['hr_employeesid',$emp_id]])->get());
    }
}
