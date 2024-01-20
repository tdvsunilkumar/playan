<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\HelSafRegistration;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HealthRegistrationController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrcitizens = array("Select" => "Please Select");
     public function __construct(){
        $this->_HelSafRegistration = new HelSafRegistration(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','reg_remarks'=>'','cit_id'=>'','reg_date'=>'');  
        $this->slugs = 'setup-data-assistance-type';
		foreach ($this->_HelSafRegistration->getcitizens() as $val) {
            $this->arrcitizens[$val->id] = $val->cit_first_name." ".$val->cit_middle_name." ".$val->cit_last_name;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$isopen=$request->input('isopenAddform');
        return view('HelSafRegistration.index',compact('isopen'));
    }
    public function getList(Request $request){
        // $this->is_permitted($this->slugs, 'read');
		
        $data=$this->_HelSafRegistration->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
			$arrcitizens =$this->arrcitizens;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/Health-and-safety/registration/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Registration">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->reg_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['cit_id']=$arrcitizens[$row->cit_id];
			//$arr[$i]['reg_year']=$row->reg_year;
			$arr[$i]['is_opd']=($row->is_opd==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
			$arr[$i]['is_lab']=($row->is_lab==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
			$arr[$i]['is_family_planning']=($row->is_family_planning==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['is_sanitary']=($row->is_sanitary==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
			$arr[$i]['reg_date']=Carbon::parse($row->reg_date)->format('M d, Y g:i A');
            $arr[$i]['reg_remarks']=$row->reg_remarks;
            //$arr[$i]['reg_status']=($row->reg_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            //$arr[$i]['action']=$actions;
           
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
        $data=array('reg_status' => $is_activeinactive);
        $this->_HelSafRegistration->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Registration ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        $arrcitizens =$this->arrcitizens;
		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_HelSafRegistration->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_HelSafRegistration->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Registration'".$this->data['reg_remarks']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['reg_status'] = 1;
				$this->data['reg_date'] = date('Y-m-d');
				$this->data['reg_year'] = date("Y");
                $request->id = $this->_HelSafRegistration->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Registration'".$this->data['reg_remarks']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            //return redirect()->route('healsaftregistration.index')->with('success', __($success_msg));
			return redirect()->back();
        }
        return view('HelSafRegistration.create',compact('data','arrcitizens'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'reg_remarks'=>'required|:ho_registration,reg_remarks,'.(int)$request->input('id'),
				'cit_id'=>'required|unique:ho_registration,cit_id,'.(int)$request->input('id'),
            ],[
                'reg_remarks.required'=>'Remarks is required',
				'cit_id.required'=>'Patient Name is required',
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
	
	public function getRefreshHelSaf(Request $request){
       $getgroups = $this->_HelSafRegistration->getcitizens();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
            $htmloption .='<option value="'.$value->id.'">'.$value->cit_first_name.'  '.$value->cit_middle_name.' '.$value->cit_last_name.'</option>';
      }
      echo $htmloption;
    }
   
}
