<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrHolidays;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrHolidaysController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $arrholidaytype = array(""=>"Please Select");
     public function __construct(){
		$this->_hrholidays= new HrHolidays(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrh_date'=>'','hrh_description'=>'','hrht_id'=>'','hrh_is_paid'=>null);  
        $this->slugs = 'hr-holidays'; 
        foreach ($this->_hrholidays->getHolidayType() as $val) {
            $this->arrholidaytype[$val->id]=$val->hrht_description;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.holidays.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrholidays->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrh_date']=date('m/d/Y',strtotime($row->hrh_date));
            $arr[$i]['hrh_description']=$row->hrh_description;
            $arr[$i]['hrht_id']=$row->holiday_type;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-holidays/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Holidays">
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
        $this->_hrholidays->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        // $arrholidaytype = config('constants.arrHolidaysType');
        $arrholidaytype =  $this->arrholidaytype;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrHolidays::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
        
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if ($request->hrht_id === '1' || $request->hrht_id === '2') {
                $this->data['hrh_is_paid'] = 1;
            }
            if($request->input('id')>0){
                $this->_hrholidays->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Holiday Udated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Holiday '".$this->data['hrh_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrholidays->addData($this->data);
                $success_msg = 'Holiday added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Holiday '".$this->data['hrh_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrholiday.index')->with('success', __($success_msg));
    	}
        return view('HR.holidays.create',compact('data','arrholidaytype'));
	}
    
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrh_date'=>'required',//|unique:hr_holidays,hrh_date,'.(int)$request->input('id'),
                'hrh_description'=>'required',
                'hrht_id'=>'required',
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
