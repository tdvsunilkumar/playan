<?php

namespace App\Http\Controllers\SocialWelfare;
use App\Http\Controllers\Controller;
use App\Models\SocialWelfare\AssistanceTypeRequirement;
use App\Models\SocialWelfare\ApplicationtypeModel;
use App\Models\SocialWelfare\RequirementsModel;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AssistanceTypeRequirements extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;
    public function __construct(){
        $this->_AssistanceTypeRequirement = new AssistanceTypeRequirement(); 
		$this->_ApplicationtypeModel = new ApplicationtypeModel();
		$this->_RequirementsModel = new RequirementsModel();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','wsat_id'=>'','wsr_id'=>'');  
        $this->slugs = 'social-welfare/assistance-type-requirements';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$wsat_type=$this->_ApplicationtypeModel->allAppType();
		$wsr_type=$this->_RequirementsModel->allAppType();
        return view('SocialWelfare.assistancetyperequirements.index')->with(compact('wsat_type','wsr_type'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_AssistanceTypeRequirement->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/social-welfare/assistance-type-requirements/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Assistance Type Requirements">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
                
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wsatr_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Assistance Type Requirements"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Assistance Type Requirements"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['wsat_id']=$row->wsat_description;
			$arr[$i]['wsr_id']='<div class="showLess">'.$row->wsr_description.'</div>';
            $arr[$i]['wsatr_is_active']=($row->wsatr_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
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
        $data=array('wsatr_is_active' => $is_activeinactive);
        $this->_AssistanceTypeRequirement->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Assistance Type Requirements ".$action; 
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
		$wsat_id = array(""=>"Please Select");
        foreach ($this->_ApplicationtypeModel->allAppType() as $val) {
            $wsat_id[$val->id]=$val->wsat_description;
        }
		
		$wsr_id = array(""=>"Please Select");
        foreach ($this->_RequirementsModel->allAppType() as $val) {
            $wsr_id[$val->id]=$val->wsr_description;
        }
		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_AssistanceTypeRequirement->getEditDetails($request->input('id'));
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['created_by']=\Auth::user()->creatorId();
            $this->data['created_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_AssistanceTypeRequirement->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Assistance Type Requirements '".$this->data['wsat_id']."'"; 
            }else{
                $this->data['modified_by']=\Auth::user()->creatorId();
                $this->data['modified_date'] = date('Y-m-d H:i:s');
                $this->data['wsatr_is_active'] = 1;
                $request->id = $this->_AssistanceTypeRequirement->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Assistance Type Requirements '".$this->data['wsat_id']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('assistance-type-requirements.index')->with('success', __($success_msg));
        }
        return view('SocialWelfare.assistancetyperequirements.create',compact('data','wsat_id','wsr_id'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'wsat_id'=>'required|:welfare_swa_assistance_type_requirements,wsat_id,'.(int)$request->input('id'),
				'wsr_id'=>'required|:welfare_swa_assistance_type_requirements,wsr_id,'.(int)$request->input('id'),
            ],[
			  'wsat_id.required' => 'The Assistance Type is required.',
			  'wsr_id.required' => 'The Assistance Requirements is required.'
			]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        $exist = $this->_AssistanceTypeRequirement->where('wsat_id',$request->wsat_id)->where('wsr_id',$request->wsr_id)->first();
        if ($exist && !($request->id)) {
            $arr['field_name'] = 'wsat_id';
            $arr['error'] = 'Already Exist';
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
}
