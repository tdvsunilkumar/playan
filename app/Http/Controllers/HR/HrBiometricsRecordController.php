<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrBiometricsRecord;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrBiometricsRecordController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $division = array(""=>"Please Select");
    public $department = array(""=>"Please Select");

     public function __construct(){
		$this->_hrBiometricsRecord= new HrBiometricsRecord(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrbr_emp_id'=>'','hrtc_emp_id_no'=>'','hrbr_department_id'=>'','hrbr_division_id'=>'','hrbr_date'=>'','hrbr_time'=>'');  
        $this->slugs = 'employee-biometric-record'; 
        foreach ($this->_hrBiometricsRecord->getDepartment() as $val) {
            $this->department[$val->id]=$val->name;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            $department =$this->department;
            $division=  $this->division;
            return view('HR.HrBiometricsRecord.index',compact('department','division'));
    }
    public function getDivByDept(Request $request){
        $getDiv = $this->_hrBiometricsRecord->getDivByDept($request->input('dept_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getDiv as $key => $value) {
          $htmloption .='<option value="'.$value->id.'">'.$value->name.'</option>';
        }
        echo $htmloption;
      }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrBiometricsRecord->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['user_id_no']=$row->user_id_no;
            $arr[$i]['emp_name']=$row->emp_name;
            $arr[$i]['dept_name']=$row->dept_name;
            $arr[$i]['div_name']=$row->div_name;
            $arr[$i]['hrbr_date']=date("M d, Y",strtotime($row->hrbr_date));
            $arr[$i]['hrbr_time']=date("h:i a", strtotime($row->hrbr_time)); 
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
}
