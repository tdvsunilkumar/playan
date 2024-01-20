<?php

namespace App\Http\Controllers;

use App\Models\RptPropertySubclassification;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RptPropertySubclassificationController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     public $arrClassCode = array(""=>"Please Select");

    public function __construct(){
        $this->_rptPropertysubclassification = new RptPropertySubclassification();
        
  $this->data = array('id'=>'','pc_class_code'=>'','ps_subclass_code'=>'','ps_subclass_desc'=>'','ps_is_for_plant_trees'=>'');
        
        foreach ($this->_rptPropertysubclassification->getRptClass() as $val) {
            $this->arrClassCode[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
        }  

    }
    
    
    public function index(Request $request)
    {
        
            return view('rptPropertysubclassification.index');
        
    }
    public function getList(Request $request){
        $data=$this->_rptPropertysubclassification->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->ps_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            $arr[$i]['pc_class_code']=$row->pc_class_code.'-'.$row->pc_class_description;
            $arr[$i]['ps_subclass_code']=$row->ps_subclass_code;
            $arr[$i]['ps_subclass_desc']=$row->ps_subclass_desc;

           $arr[$i]['ps_is_for_plant_trees']=($row->ps_is_for_plant_trees==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

            $arr[$i]['is_active']=($row->ps_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptPropertysubclassification/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Sub-Class">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                     </div>'  
                ;
                // <div class="action-btn bg-danger ms-2">
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

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ps_is_active' => $is_activeinactive);
        $this->_rptPropertysubclassification->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = $this->arrClassCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertySubclassification::find($request->input('id'));
            
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptPropertysubclassification->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Property Sub-Class updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ps_is_active'] = 1;
               
                $this->_rptPropertysubclassification->addData($this->data);
                $success_msg = 'Rpt Property Sub-Class added successfully.';
            }
            return redirect()->route('sub-classproperty.index')->with('success', __($success_msg));
        }
        return view('rptPropertysubclassification.create',compact('data','arrClassCode'));
        
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                 'ps_subclass_code' => 'required|unique:rpt_property_subclassifications,ps_subclass_code,' .$request->input('id'). ',id,pc_class_code,' .$request->input('pc_class_code').',ps_subclass_desc,' .$request->input('ps_subclass_desc'),
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
            $RptPropertySubclassification = RptPropertySubclassification::find($id);
            if($RptPropertySubclassification->created_by == \Auth::user()->creatorId()){
                $RptPropertySubclassification->delete();
            }
    }
}
