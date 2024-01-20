<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrTimekeeping;
use App\Models\HR\HrAppointment;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class HrTimekeepingController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $division = array(""=>"Please Select");
    public $department = array(""=>"Please Select");
    public $employee = array(""=>"Please Select");
    public $cut_off_period = array(""=>"Please Select");


     public function __construct(){
		$this->_hrTimekeeping= new HrTimekeeping(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrtk_emp_id'=>'','hrtk_department_id'=>'','hrtk_division_id'=>'','hrtk_date'=>'','hrtk_total_hours'=>'','hrtk_total_aut'=>'','hrtk_total_overtime'=>'','hrtk_total_leave'=>'','hrtk_is_processed'=>'');  
        $this->slugs = 'hr-timekeeping'; 
        foreach ($this->_hrTimekeeping->getDepartment() as $val) {
            $this->department[$val->id]=$val->name;
        } 
        foreach ($this->_hrTimekeeping->getCutoffPeriod() as $val) {
            $this->cut_off_period[$val->id]=$val->hrcp_description;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            $department =$this->department;
            $division=  $this->division;
            $employee=  $this->employee;
            $cut_off_period=$this->cut_off_period;
            $current_cutoff = $this->_hrTimekeeping->currentCutoff(Carbon::now()->toDateString());
            return view('HR.HrTimekeeping.index',compact('department','division','employee','cut_off_period','current_cutoff'));
    }
    public function getDivByDept(Request $request){
        $getDiv = $this->_hrTimekeeping->getDivByDept($request->input('dept_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getDiv as $key => $value) {
          $htmloption .='<option value="'.$value->id.'">'.$value->name.'</option>';
        }
        echo $htmloption;
      }
    public function getEmpByDiv(Request $request){
        $getDiv = $this->_hrTimekeeping->getEmpByDiv($request->input('div_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getDiv as $key => $value) {
          $htmloption .='<option value="'.$value->id.'">'.$value->fullname.'</option>';
        }
        echo $htmloption;
      }  
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrTimekeeping->getList($request);
        $arr=array();
        $i="0"; 
        $cut_off_period=$request->input('cut_off_period');
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            // dd($row);
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$row->hr_emp_id;
            $arr[$i]['user_id_no']=$row->hra_employee_no;
            $arr[$i]['emp_name']=$row->employee->fullname;
            $arr[$i]['dept_name']=$row->employee->department->shortname;
            $arr[$i]['div_name']=$row->employee->division->name;
            $arr[$i]['hrtk_date']=$row->time_keep_hours($cut_off_period)->process_date;
            $arr[$i]['hrtk_total_hours']=$row->time_keep_hours($cut_off_period)->hours; 
            $arr[$i]['hrtk_total_aut']=$row->time_keep_hours($cut_off_period)->aut; 
            $arr[$i]['hrtk_total_leave']=$row->time_keep_hours($cut_off_period)->leaves; 
            $arr[$i]['hrtk_total_overtime']=$row->time_keep_overtime($cut_off_period); 
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
    public function process_timekeeping(Request $request){
        $this->is_permitted($this->slugs, 'update');
        // $q=$request->input('q');
        $hrtk_department_id=$request->input('hrtk_department_id');
        $hrtk_division_id=$request->input('hrtk_division_id');
        $hrtk_emp_id=$request->input('hrtk_emp_id');
        $cut_off_period=$request->input('cut_off_period');
        $hrtk_is_processed=$request->input('hrtk_is_processed');
        // $sql = DB::table('hr_timekeeping');
        // //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        // if(!empty($hrtk_department_id) && isset($hrtk_department_id)){
        //     $sql->where('hrtk_department_id',$hrtk_department_id);
        // }
        // if(!empty($hrtk_division_id) && isset($hrtk_division_id)){
        //     $sql->where('hrtk_division_id',$hrtk_division_id);
        // }
        // if(!empty($hrtk_emp_id) && isset($hrtk_emp_id)){
        //     $sql->where('hrtk_emp_id',$hrtk_emp_id);
        // }
        // if(!empty($hrtk_is_processed) && isset($hrtk_is_processed)){
        //     $sql->where('hrtk_is_processed',$hrtk_is_processed);
        // }
        // if(!empty($cut_off_period) && isset($cut_off_period)){
        //     $valData=DB::table('hr_cutoff_period')->where('id',$cut_off_period)->first();
        //     $sql->whereDate('hrtk_date','>=',$valData->hrcp_date_from);
        //     $sql->whereDate('hrtk_date','<=',$valData->hrcp_date_to);
        // }
        // $u_data=array('hrtk_is_processed' => 1);
        // $data=$sql->update($u_data);    
        
        try {
            if ($request->hrtk_department_id && $request->hrtk_division_id) {
                $processing_employees = HrAppointment::where([['hra_department_id',$request->hrtk_department_id],['hra_division_id',$request->hrtk_division_id]])->get();
                
                if ($request->hrtk_emp_id){
                    $processing_employees = HrAppointment::where('hr_emp_id',$request->hrtk_emp_id)->get();
                }

                if (hr_policy('leave_deduction') === 'Yes') {
                    HrTimekeeping::leaveAutoDeduct($cut_off_period);
                }
                
                foreach ($processing_employees as $employee) {
                    // dd($employee);
                    HrTimekeeping::updateOrCreate(
                        [
                            'hrtk_emp_id'=>$employee->hr_emp_id,
                            'hrtk_department_id'=>$employee->hra_department_id,
                            'hrtk_division_id'=>$employee->hra_division_id,
                            'hrcp_id'=>$cut_off_period,
                        ],
                        [
                            'hrtk_date'=>Carbon::now()->toDateString(),
                            'hrtk_total_hours'=>$employee->time_keep_hours($cut_off_period)->hours,
                            'hrtk_total_aut'=>$employee->time_keep_hours($cut_off_period)->aut,
                            'hrtk_total_overtime'=>$employee->time_keep_overtime($cut_off_period),
                            'hrtk_total_leave'=>$employee->time_keep_leave($cut_off_period),
                        ]
                    );
                }
                return json_encode(
                    [
                        'ESTATUS'=>0,
                        'msg'=>'Success',
                    ]
                );
            }
            return json_encode(
                [
                    'ESTATUS'=>1,
                    'msg'=>'Missing Data',
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
            return json_encode(
                [
                    'ESTATUS'=>1,
                    'msg'=>$th,
                ]
            );
        }
    }
    
}
