<?php

namespace App\Http\Controllers\Cpdo;
use App\Http\Controllers\Controller;
use App\Models\Cpdo\LocationClearance;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CpdoLocationClearanceController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $getServices = array(""=>"Please Select");
     public $arrowners = array(""=>"Please Select");
    
     public function __construct(){
		$this->_cpdoservice= new LocationClearance(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','year'=>'','client_id'=>'','businessname'=>'','date'=>'','preparedby'=>'','position'=>'','completeaddress'=>'');  
        $this->slugs = 'planning/locationclearance'; 
        foreach ($this->_cpdoservice->getCpdoOwners() as $val) {
             $this->arrowners[$val->id]="[".$val->rpo_first_name." - ".$val->rpo_middle_name." ".$val->rpo_custom_last_name." ".$val->suffix;
         }
    }
    
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
                return view('cpdo.location.index');
           
    }
     public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('cs_is_active' => $is_activeinactive);
        $this->_cpdoservice->updateActiveInactive($id,$data);
    }

    public function getserviceName(Request $request){
         $id = $request->input('id');
          $name = $this->_cpdoservice->GetServicename($id);
          if(count($name)>0){
            echo $name[0]->chartofaccount;
          }else{
            echo "";
          }
    }

     public function viewrequiremets(Request $request)
    {
        $id=$request->input('id');
        $html ="";
        $requirements = $this->_cpdoservice->getRequirementforview($id); $i=1;
         foreach ($requirements as $k => $val) { 
            $html .='<tr><td>'.$i.'</td><td>'.$val->req_description.'</td></tr>';
            $i++;
        }
        echo $html;
    }
       
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdoservice->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['clientname']=$row->rpo_first_name." ".$row->rpo_middle_name."".$row->rpo_custom_last_name;
            $arr[$i]['completeaddress']=$row->completeaddress;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoservice/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Planning &  Devt Service">
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

    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $getServices = $this->getServices;
        $arrowners = $this->arrowners;
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CpdoService::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['cm_id'] = '0';
            if($request->input('id')>0){
                $this->_cpdoservice->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Cpdo Service updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['cs_is_active'] = 1;
               
                $lastinsertid = $this->_cpdoservice->addData($this->data);
                $success_msg = 'Cpdo Service added successfully.';
            }
            //echo "<pre>"; print_r($_POST['requiremets_ids']); e//xit;
            foreach ($_POST['requiremets_ids'] as $key => $value) {
                    $requirementarray = array();
                    $checkdata = $this->_cpdoservice->checkRequirementExists($lastinsertid,$value);
                    $requirementarray['tfoc_id'] =$this->data['tfoc_id'];
                    $requirementarray['cs_id'] = $lastinsertid;
                    $requirementarray['req_id'] = $value;
                    $requirementarray['csr_is_required'] = '1';
                    $requirementarray['csr_is_active'] = '1';
                    if(count($checkdata) > 0){
                        $requirementarray['updated_by']=\Auth::user()->id;
                        $requirementarray['updated_at'] = date('Y-m-d H:i:s');
                        $this->_cpdoservice->updateRequirementData($checkdata[0]->id,$requirementarray);
                    }else{
                        $requirementarray['created_by']=\Auth::user()->id;
                        $requirementarray['created_at'] = date('Y-m-d H:i:s');
                        $this->_cpdoservice->addRequirementData($requirementarray);
                    }
                    
            }
             $requirements = $this->_cpdoservice->deleteRequirements($request->input('id'),$_POST['requiremets_ids']);
            return redirect()->route('cpdoservice.index')->with('success', __($success_msg));
    	}
        return view('cpdo.location.create',compact('data','getServices','arrowners'));
	}
	  public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tfoc_id'=>'required| unique:cpdo_services,cm_id,'.(int)$request->input('id'),
                'top_transaction_type_id'=>'required',
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
