<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngServiceRequirements;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngServiceRequirementsController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $getServicefee = array(""=>"Please Select");
     public $arrService = array(""=>"Please Select");
     public $arrRequirements = array(""=>"Please Select");
     public function __construct(){
		$this->_engservicereqments= new EngServiceRequirements(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tfoc_id'=>'','es_id'=>'','req_id'=>'','esr_is_required'=>'');  
        $this->slugs = 'engneering/master-data/engservice'; 
        foreach ($this->_engservicereqments->getServicefees() as $val) {
             $this->getServicefee[$val->id]=$val->tfoc_short_name;
         }
         foreach ($this->_engservicereqments->getServices() as $val) {
             $this->arrService[$val->id]=$val->eba_description;
         }
         foreach ($this->_engservicereqments->getRequirements() as $val) {
             $this->arrRequirements[$val->id]=$val->req_description;
         }    
    }
    
    public function index(Request $request)
    {
            
        return view('Engneering.servicerequirements.index');
            
    }


    public function getList(Request $request){
        //$this->is_permitted($this->slugs, 'read');
        $data=$this->_engservicereqments->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->esr_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['tfoc_id']=$row->tfoc_short_name;
            $arr[$i]['service']=$row->service;
            $arr[$i]['requirements']=$row->req_description;
            $arr[$i]['esr_is_required']=($row->esr_is_required==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['esr_is_active']=($row->esr_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/servicerequirements/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Eng Service Requirements">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    </div>' 
              ;
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
        $data=array('esr_is_active' => $is_activeinactive);
        $this->_engservicereqments->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_service_requirements",'action' =>"update",'id'=>$request->input('id')]);
}
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $getServicefee = $this->getServicefee;
        $arrService = $this->arrService;
        $arrRequirements = $this->arrRequirements;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngServiceRequirements::find($request->input('id'));
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engservicereqments->updateData($request->input('id'),$this->data);
                $success_msg = 'Engneering Service Requirements updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_service_requirements",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['esr_is_active'] = 1;
               
                $last_insert_id=$this->_engservicereqments->addData($this->data);
                $success_msg = 'Engneering Service Requirements added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_service_requirements",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('Engneering.servicerequirements.index')->with('success', __($success_msg));
    	}
        return view('Engneering.servicerequirements.create',compact('data','getServicefee','arrService','arrRequirements'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tfoc_id'=>'required',
                'es_id'=>'required',
                'req_id'=>'required',
                'esr_is_required'=>'required'
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
