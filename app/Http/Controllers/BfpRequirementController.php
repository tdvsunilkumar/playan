<?php

namespace App\Http\Controllers;

use App\Models\BfpRequirement;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class BfpRequirementController extends Controller
{
    
    public $data = [];
    public $arrApplicationType = array(""=>"Please Select Application Type");
    public $arrPurpose = array(""=>"Please Select Purpose"); 
    public $arrCategory = array(""=>"Please Select Category");
    public $arrRequirements = array(""=>"Please Select Requirements"); 
    private $slugs;
    public function __construct(){
        $this->_bfprequirement = new BfpRequirement();
        
  $this->data = array('id'=>'','btype_id'=>'','bap_id'=>'','bac_id'=>'','req_id'=>'');
       
        foreach ($this->_bfprequirement->getApplicationType() as $val) {
            $this->arrApplicationType[$val->id]=$val->btype_description;   
        }
        foreach ($this->_bfprequirement->getPurpose() as $val) {
            $this->arrPurpose[$val->id]=$val->bap_desc;   
        }
        foreach ($this->_bfprequirement->getCategory() as $val) {
            $this->arrCategory[$val->id]=$val->bac_desc;   
        }
        foreach ($this->_bfprequirement->getRequirements() as $val) {
            $this->arrRequirements[$val->id]=$val->req_description;   
        } 
        $this->slugs = 'fire-safety-requirements';    
    }
    
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('bfprequirement.index');
    }
    
    public function getList(Request $request){
        $data=$this->_bfprequirement->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->bac_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            $arr[$i]['btype_description']=$row->btype_description;
            $arr[$i]['bap_desc']=$row->bap_desc;
            $arr[$i]['bac_desc']=$row->bac_desc;
            $arr[$i]['req_description']=$row->req_description;
            $arr[$i]['is_active']=($row->bac_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfprequirement/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Bfp Requirement">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               </div>
                    '.$status.'
                </div>
                ';
                //  <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
                
            
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

    
   public function appraisersActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('bac_status' => $bt_is_activeinactive);
        $this->_bfprequirement->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrApplicationType = $this->arrApplicationType;
        $arrPurpose = $this->arrPurpose;
        $arrCategory = $this->arrCategory;
        $arrRequirements = $this->arrRequirements;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BfpRequirement::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bfprequirement->updateData($request->input('id'),$this->data);
                $success_msg = 'Requirement updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bac_status'] = 1;
                $this->_bfprequirement->addData($this->data);
                $success_msg = 'Requirement added successfully.';
            }
            return redirect()->route('bfprequirement.index')->with('success', __($success_msg));
        }
        return view('bfprequirement.create',compact('data','arrApplicationType','arrPurpose','arrCategory','arrRequirements'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bac_id' => 'required|unique:bfp_requirements,bac_id,' .$request->input('id'). ',id,req_id,' .$request->input('req_id'),
                
                

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
    public function Delete(Request $request){
        $id = $request->input('id');
            $BfpRequirement = BfpRequirement::find($id);
            if($BfpRequirement->created_by == \Auth::user()->creatorId()){
                $BfpRequirement->delete();
            }
    }

}
