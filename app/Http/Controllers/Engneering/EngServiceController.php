<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngService;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngServiceController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $getServices = array(""=>"Please Select");
     public $arrApptype = array(""=>"Please Select");
     public $arrModuleform = array(""=>"Please Select");
     public $arrtransactiontype = array(""=>"Please Select");
     public $arrRequirements = array();
     public function __construct(){
		$this->_engservice= new EngService(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tfoc_id'=>'','eat_id'=>'','emf_id'=>'','is_main_service'=>'','top_transaction_type_id'=>'','is_flcno_required'=>'0');  
        $this->slugs = 'engneering/master-data/engservice'; 
        foreach ($this->_engservice->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }
         foreach ($this->_engservice->getAppType() as $val) {
             $this->arrApptype[$val->id]=$val->eat_module_desc;
         }
         foreach ($this->_engservice->getEngModules() as $val) {
             $this->arrModuleform[$val->id]=$val->em_module_desc;
         }
          foreach ($this->_engservice->getRequirements() as $val) {
             $this->arrRequirements[$val->id]=$val->req_code_abbreviation." - ".$val->req_description;
         }
         foreach ($this->_engservice->getTransactiontype() as $val) {
             $this->arrtransactiontype[$val->id]=$val->id." - ".$val->ttt_desc;
         }            
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engservice.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engservice->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->es_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['tfoc_id']=$row->description;
            $arr[$i]['eat_id']=$row->eat_module_desc;
            $arr[$i]['emf_id']=$row->em_module_desc;
            $arr[$i]['viewreq']='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm viewreq align-items-center"  title="view requirement"  data-title="Assess Now" id='.$row->id.'>
                    <i class="ti-eye text-white"></i>
                    </a></div>';
            $arr[$i]['es_is_active']=($row->es_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engservice/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Engineering Service">
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
        $data=array('es_is_active' => $is_activeinactive);
        $this->_engservice->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_services",'action' =>"update",'id'=>$request->input('id')]);
    }

    public function getServicefeename(Request $request){
          $id = $request->input('id');
          $name = $this->_engservice->GetServicename($id);
          if(count($name)>0){
            echo $name[0]->chartofaccount;
          }else{
            echo "";
          }
         //echo "<pre>"; print_r($name); exit;
    }


    public function viewrequiremets(Request $request)
    {
        $id=$request->input('id');
        $html ="";
        $requirements = $this->_engservice->getRequirementforview($id); $i=1;
         foreach ($requirements as $k => $val) { 
            $html .='<tr><td>'.$i.'</td><td>'.$val->req_description.'</td></tr>';
            $i++;
        }
        echo $html;
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $getServices = $this->getServices;
        $arrApptype = $this->arrApptype;
        $arrModuleform = $this->arrModuleform;
        $arrRequirements = $this->arrRequirements;
        $arrtransactiontype = $this->arrtransactiontype;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngService::find($request->input('id'));
            $requirements = $this->_engservice->getRequirementforedit($request->input('id'));
               
        }
        //echo "<pre>"; print_r($data); exit;
       
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if(empty($this->data['is_main_service'])){
                $this->data['is_main_service'] = '0';
            }
            if($request->input('id')>0){
                $this->_engservice->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Engineering Service updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_services",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['es_is_active'] = 1;
               
               $lastinsertid = $this->_engservice->addData($this->data);
                $success_msg = 'Engineering Service added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_services",'action' =>"store",'id'=>$lastinsertid]);
            }
            Session::put('REMOTE_SYNC_SERVICEID',$lastinsertid);
            if(!empty($_POST['req_id'])){
            $requirements = $_POST['req_id'];
            $remort_req_id=[];
                foreach ($requirements as $k => $val) {
                    $reqids .=$val.",";
                    $remort_req_id[$k]=$val;
                }
            $reqids =substr($reqids, 0, -1);
            $requiremets_ids = $reqids; 
            $requiremets_ids = explode(",", $requiremets_ids); 
            $this->_engservice->deleteRequirements($request->input('id'),$requiremets_ids);
            }
            //echo "<pre>"; print_r($requirements); print_r($_POST['requiremets_ids']); exit;
            
            if(!empty($_POST['req_id'])){    
            foreach ($_POST['req_id'] as $key => $value) {
                    $requirementarray = array();
                    $checkdata = $this->_engservice->checkRequirementExists($lastinsertid,$value);
                    $requirementarray['tfoc_id'] =$this->data['tfoc_id'];
                    $requirementarray['es_id'] = $lastinsertid;
                    $requirementarray['req_id'] = $value;
                    $requirementarray['orderno'] = $_POST['order'][$key];
                    $requirementarray['esr_is_required'] = isset($_POST['esr_is_required'][$key]) ? $_POST['esr_is_required'][$key] : '0';
                    $requirementarray['esr_is_active'] = '1';
                    if(count($checkdata) > 0){
                        $requirementarray['updated_by']=\Auth::user()->id;
                        $requirementarray['updated_at'] = date('Y-m-d H:i:s');
                        $this->_engservice->updateRequirementData($checkdata[0]->id,$requirementarray);
                        // $remort_req_id=$checkdata[0]->id;
                         //Session::put('remort_serv_session_det', ['table' => "eng_service_requirements",'action' =>"update",'id'=>$checkdata[0]->id]);
                         //echo $checkdata[0]->id; exit;
                    }else{
                        $requirementarray['created_by']=\Auth::user()->id;
                        $requirementarray['created_at'] = date('Y-m-d H:i:s');
                        $last_insert_id=$this->_engservice->addRequirementData($requirementarray);
                        // $remort_req_id=$last_insert_id;
                         //Session::put('remort_serv_session_det', ['table' => "eng_service_requirements",'action' =>"store",'id'=>$last_insert_id]);
                    }
                 }
                 Session::put('remort_serv_session_det_req', ['remort_req_table' => "eng_service_requirements",'remort_req_action' =>"addAndUpdate",'remort_req_id'=>$remort_req_id,'remort_req_er_serv_id'=>$request->input('id')]);
             }
            return redirect()->route('Engneering.engservice.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engservice.create',compact('data','getServices','arrApptype','arrModuleform','arrRequirements','arrtransactiontype','requirements'));
	}
    
    
    public function formValidation(Request $request){
        if(empty($request->input('is_main_service'))){
            if(empty($request->input('eat_id'))){
               $validator = \Validator::make(
               $request->all(), [
                'tfoc_id'=>'required|unique:eng_services,tfoc_id,'.(int)$request->input('id'),
                 ]
              ); 
            }else{
                $validator = \Validator::make(
               $request->all(), [
                'tfoc_id'=>'required|unique:eng_services,tfoc_id,'.(int)$request->input('id'),
                'eat_id'=>'required|unique:eng_services,eat_id,'.(int)$request->input('id'),
                 ]
              ); 
            }
             
        }else{
            $validator = \Validator::make(
            $request->all(), [
                'tfoc_id'=>'required|unique:eng_services,tfoc_id,'.(int)$request->input('id'),
                'eat_id'=>'required|unique:eng_services,eat_id,'.(int)$request->input('id'),
                'emf_id'=>'required'
            ]
         ); 
        }
       
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
