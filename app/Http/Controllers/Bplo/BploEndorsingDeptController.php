<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\BploEndorsingDept;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class BploEndorsingDeptController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_BploEndorsingDept = new BploEndorsingDept(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','edept_name'=>'','tfoc_id'=>'','fees' => '','force_mark_complete' => '','requirement_json'=>'');  
        $this->slugs = 'endorsing-department';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.BploEndorsingDept.index');
    }

    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_BploEndorsingDept->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/BploEndorsingDept/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Endorsing Department">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }

            
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->edept_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['edept_name']=$row->edept_name;
            $arr[$i]['tfoc_id']=$row->description;
            $arr[$i]['edept_status']=($row->edept_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('edept_status' => $is_activeinactive);
        $this->_BploEndorsingDept->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Formula ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    public function reload_fees($id)
    {
        return $this->_BploEndorsingDept->getCtoTfocById($id);
    }
    public function getNatureDetails($json=''){
        $arrDetails= array();
        if(empty($json)){
            $arrDetails[0]['id']='';
            $arrDetails[0]['requirement_id']='';
            $arrDetails[0]['requirement_name']='';
            $arrDetails[0]['is_active']='';
            $arrDetails[0]['remark']='';
            $arrDetails[0]['is_required']='';
            
        }else{
            $arr = json_decode($json,true);
            foreach($arr as $key=>$val){
                $arrDetails[$key]['id']=$key;
                $arrDetails[$key]['requirement_id']=$val['requirement_id'];
                $arrDetails[$key]['requirement_name']=$val['requirement_name'];
                $arrDetails[$key]['is_active']=$val['is_active'];
                $arrDetails[$key]['remark']=$val['remark'];
                $arrDetails[$key]['is_required']=isset($val['is_required'])?$val['is_required']:'0';
            }
        }
        return $arrDetails;
    } 
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
         $arrrequirement = array(); $apptypes =array();
        $arrrequirement[''] ="Select Requirement";
        foreach ($this->_BploEndorsingDept->requirementcode() as $val) {
           $arrrequirement[$val->id]=$val->req_code_abbreviation.'-'.$val->req_description;
        }
        $arrAccount = array(""=>"Please Select");
        foreach ($this->_BploEndorsingDept->getCtoTfoc() as $val) {
            $arrAccount[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
        $arrSection=array();
        foreach ($this->_BploEndorsingDept->apptypes() as $val) {
           $apptypes[$val->id]=$val->app_type;
        }
        foreach ($this->_BploEndorsingDept->getSection($request->sid) as $val) {
           $arrSection[$val->id]=$val->section_description;
        }
        $arrDetails = $this->getNatureDetails();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_BploEndorsingDept->getEditDetails($request->input('id'));
            $arrDetails = $this->getNatureDetails($data->requirement_json);
        }
        
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
             $arr = $request->input('requirement_id');
            $arrJson=array();
            foreach ($arr as $key => $val) {
                $is_required = 0; 
                if ($request->has('is_required_'.$val) && $request->input('is_required_'.$val) == '1') {
                    $is_required = 1;
                }

                $arrDetails = [
                    'requirement_id' => $val,
                    'requirement_name' => $arrrequirement[$val],
                    'is_active' => $request->input('is_active')[$key] ?? null,
                    'remark' => $request->input('remark')[$key] ?? null,
                    'is_required' => $is_required,
                ];

                $arrJson[] = $arrDetails;
            }


            $this->data['requirement_json']=json_encode($arrJson);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_BploEndorsingDept->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated TFOC Basis '".$this->data['edept_name']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['edept_status'] = 1;
                $request->id = $this->_BploEndorsingDept->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added TFOC Basis '".$this->data['edept_name']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('bploEndorsingDept.index')->with('success', __($success_msg));
        }
        return view('Bplo.BploEndorsingDept.create',compact('data','arrAccount','arrrequirement','apptypes','arrDetails','arrSection'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'edept_name'=>'required|unique:bplo_endorsing_dept,edept_name,'.(int)$request->input('id'),
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
    public function deleteBploEndorsingDept(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arrEndrosment = $this->_BploEndorsingDept->getBploEndorsingDept($id);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->requirement_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'requirement_id'));
                if($key !== false){
                    
                   unset($arrJson[$key]);
                
                // Reset array keys to consecutive integers
                $arrJson = array_values($arrJson);
                
                // Encode the updated JSON data
                $data['requirement_json'] = json_encode($arrJson);
                    $this->_BploEndorsingDept->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }
   
}
