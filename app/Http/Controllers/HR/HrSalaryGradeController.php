<?php
namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrSalaryGrade;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;


class HrSalaryGradeController extends Controller
{
     public $data = [];
     public $postdata = [];
    
     public function __construct(){
		$this->_hrsalarygrade= new HrSalaryGrade(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrsg_salary_grade'=>'','hrsg_step_1'=>'','hrsg_step_2'=>'','hrsg_step_3'=>'','hrsg_step_4'=>'','hrsg_step_5'=>'','hrsg_step_6'=>'','hrsg_step_7'=>'','hrsg_step_8'=>'');  
        $this->slugs = 'hr-salary-grade'; 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.salarygrade.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrsalarygrade->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrsg_salary_grade']=$row->hrsg_salary_grade;
            $arr[$i]['hrsg_step_1']= number_format($row->hrsg_step_1, 2, '.', ',');
            $arr[$i]['hrsg_step_2']= number_format($row->hrsg_step_2, 2, '.', ',');
            $arr[$i]['hrsg_step_3']= number_format($row->hrsg_step_3, 2, '.', ','); 
            $arr[$i]['hrsg_step_4']= number_format($row->hrsg_step_4, 2, '.', ',');
            $arr[$i]['hrsg_step_5']= number_format($row->hrsg_step_5, 2, '.', ',');
            $arr[$i]['hrsg_step_6']= number_format($row->hrsg_step_6, 2, '.', ',');
            $arr[$i]['hrsg_step_7']= number_format($row->hrsg_step_7, 2, '.', ',');
            $arr[$i]['hrsg_step_8']= number_format($row->hrsg_step_8, 2, '.', ',');
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-salary-grade/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Salary Grade">
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
        $this->_hrsalarygrade->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrSalaryGrade::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrsalarygrade->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Salary grade updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Salary grade '".$this->data['hrsg_salary_grade']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrsalarygrade->addData($this->data);
                $success_msg = 'Salary grade added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Salary grade '".$this->data['hrsg_salary_grade']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrsalarygrade.index')->with('success', __($success_msg));
    	}
        return view('HR.salarygrade.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrsg_salary_grade'=>'required|unique:hr_salary_grades,hrsg_salary_grade,'.(int)$request->input('id'),
                'hrsg_step_1'=>'required'
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
}
