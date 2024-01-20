<?php

namespace App\Http\Controllers;

use App\Models\RptPropertyClass;
use App\Models\RptPropertySubclassification;
use App\Models\RptPropertyActualUse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptPropertyClassController extends Controller
{
    
    public $data = [];
    private $slugs;
    public $rpt_class=array('' => "Please Select");
    public function __construct(){
        $this->_rptpropertyclass = new RptPropertyClass();
        $this->_rptPropertysubclassification = new RptPropertySubclassification();
        $this->_rptpropertyactualuse = new RptPropertyActualUse();
        $this->data = array('id'=>'','pc_class_code'=>'','pc_class_no'=>'','pc_class_description'=>'','pc_unit_value_option'=>'','pc_taxability_option'=>'','class_id'=>'');
        $this->subclassdata = array('id'=>'','pc_class_code'=>'','is_td_display'=>'','ps_subclass_code'=>'','ps_subclass_desc'=>'','ps_is_for_plant_trees'=>'0');   
        $this->actualdata = array('id'=>'','pc_class_code'=>'','pau_actual_use_code'=>'','pau_actual_use_desc'=>'','pau_with_land_stripping'=>'');    
        $this->slugs = 'real-property/property/class'; 
        foreach ($this->_rptpropertyclass->allRptClass() as $val) {
                $this->rpt_class[$val->id]=$val->description;
        }   
    }
   
    
    public function index(Request $request)
    {
       $this->is_permitted($this->slugs, 'read');
       return view('rptpropertyclass.index');
       
    }
       public function getList(Request $request){
        $data=$this->_rptpropertyclass->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->pc_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            $arr[$i]['pc_class_code']=$row->pc_class_code;
            $arr[$i]['pc_class_description']=$row->pc_class_description;
            $arr[$i]['pc_class_no']=$row->pc_class_no;
            $pc_unit_value_option = "";
            if($row->pc_unit_value_option == '1') {
                $pc_unit_value_option = 'Scheduled By District';
            } elseif ($row->pc_unit_value_option == '2') {
                $pc_unit_value_option = 'Scheduled By Property Location';
            }
            $arr[$i]['pc_unit_value_option'] = $pc_unit_value_option;

            $pc_taxability_option = "";
            if ($row->pc_taxability_option == '1') {
                $pc_taxability_option = 'Taxable';
            } else {
                $pc_taxability_option = 'Exempt';
            }
            $arr[$i]['pc_taxability_option'] = $pc_taxability_option;
            $arr[$i]['is_active']=($row->pc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['subclass']='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/real-property/property/class/subclasssStore?pc_class_code='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Sub-Class"  data-title="Manage ['.$row->pc_class_code.'-'.$row->pc_class_description.']  Sub-Class">
                        <i class="ti-eye text-white"></i>
                    </a> 
                    </div>';
            $arr[$i]['actualUs']='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/real-property/property/class/actualUseStore?pc_class_code='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Actual Use"  data-title="Manage ['.$row->pc_class_code.'-'.$row->pc_class_description.'] Actual Use">
                        <i class="ti-eye text-white"></i>
                    </a> 
                    </div>';
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/real-property/property/class/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Property Class">
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
        // dd('hiii');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('pc_is_active' => $is_activeinactive);
        $this->_rptpropertyclass->updateActiveInactive($id,$data);
     }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $rpt_class = $this->rpt_class;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyClass::find($request->input('id'));
            if($data->class_id != null){
                foreach ($this->_rptpropertyclass->allRptClass($data->class_id) as $val) {
                    $this->sub_moddule[$val->id]=$val->description;
               }
            }
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptpropertyclass->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Property Class updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['pc_is_active'] = 1;
               
                $this->_rptpropertyclass->addData($this->data);
                $success_msg = 'Rpt Property Class added successfully.';
            }
            return redirect()->route('class.index')->with('success', __($success_msg));
        }
        return view('rptpropertyclass.create',compact('data','rpt_class'));
        
    }

    
    public function subclasssStore(Request $request){
                
         $validator = \Validator::make($request->all(), [
        'ps_subclass_code' => 'required|unique:rpt_property_subclassifications,ps_subclass_code,' .$request->input('id'). ',id,pc_class_code,' .$request->input('pc_class_code').',ps_subclass_desc,' .$request->input('ps_subclass_desc'),
        ]);

        $this->subclassdata = array(
            'id' => '',
            'pc_class_code' => '',
            'ps_subclass_code' => '',
            'ps_subclass_desc' => '',
            'is_td_display'=>'',
            'ps_is_for_plant_trees' => '0'
        );
        $subclassdata = (object)$this->subclassdata;
        $clasId=$request->input('pc_class_code');
        // print_r($clasId);exit;
        $SubclasDetails=$this->_rptPropertysubclassification->getRptSubclassDetails($request->input('pc_class_code'));
        if($request->input('id')>0 && $request->input('submit')==""){
            $clasId=$request->input('pc_class_code');
            $subclassdata = RptPropertySubclassification::find($request->input('id'));
        }
        $clasId=$request->input('pc_class_code');
         // print_r($clasId);exit;       
        if($request->input('submit')!=""){
            foreach((array)$this->subclassdata as $key=>$val){
                $this->subclassdata[$key] = $request->input($key);
               
            }
            $this->subclassdata['is_td_display'] = ($request->input('is_td_display')>0)? $request->input('is_td_display'):'0';
            $this->subclassdata['updated_by']=\Auth::user()->creatorId();
            $this->subclassdata['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptPropertysubclassification->updateData($request->input('id'),$this->subclassdata);
                $success_msg = 'Rpt Property Sub-Class updated successfully.';
            }else{
               
                if(!empty($request->input('ps_is_for_plant_trees'))){
                   $this->subclassdata['ps_is_for_plant_trees']=$request->input('ps_is_for_plant_trees');
                }else{
                   $this->subclassdata['ps_is_for_plant_trees']=0; 
                }
                $this->subclassdata['pc_class_code']=$clasId;
                $this->subclassdata['created_by']=\Auth::user()->creatorId();
                $this->subclassdata['created_at'] = date('Y-m-d H:i:s');
                $this->subclassdata['ps_is_active'] = 1;
                $this->_rptPropertysubclassification->addData($this->subclassdata);
                $success_msg = 'Rpt Property Sub-Class added successfully.';
            }
             return redirect()->route('class.index')->with('success', __($success_msg));
        }
        return view('rptpropertyclass.subclassCreate',compact('subclassdata','SubclasDetails'));
        
    }
    public function actualUseStore(Request $request){
        $this->actualdata = array('id'=>'','pc_class_code'=>'','pau_actual_use_code'=>'','pau_actual_use_desc'=>'','pau_with_land_stripping'=>'');         
        
        $actualdata = (object)$this->actualdata;
        $clasId=$request->input('pc_class_code');
        // print_r($clasId);exit;
        $ActualDetails=$this->_rptpropertyactualuse->getRptActualDetails($request->input('pc_class_code'));
        // print_r($ActualDetails);exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $actualdata = $this->_rptpropertyactualuse->find($request->input('id'));
        }
       //dd($data);
        if($request->input('submit')!=""){
            
            foreach((array)$this->actualdata as $key=>$val){
                $this->actualdata[$key] = ($request->has($key))?$request->input($key):0;
            }
            $this->actualdata['pau_modified_by']=\Auth::user()->creatorId();
            $this->actualdata['updated_at'] = date('Y-m-d H:i:s');
            //dd($this->data);
            if($request->input('id')>0){
                if(isset($this->actualdata['pau_registered_by']) && $this->actualdata['pau_registered_by'] == 0){
                    unset($this->actualdata['pau_registered_by']);
                }
                $this->_rptpropertyactualuse->updateData($request->input('id'),$this->actualdata);
                $success_msg = 'Property Actual Use updated successfully.';
            }else{
                $this->actualdata['pau_registered_by']=\Auth::user()->creatorId();
                $this->actualdata['created_at'] = date('Y-m-d H:i:s');
                $this->actualdata['pau_is_active'] = 1;
               
                $this->_rptpropertyactualuse->addData($this->actualdata);
                $success_msg = 'Property Actual Use added successfully.';
            }
             return redirect()->route('class.index')->with('success', __($success_msg));
        }
        return view('rptpropertyclass.actualUsCreate',compact('actualdata','ActualDetails'));
        
    }
    public function formValidationSubclass(Request $request){
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
    
    public function formValidationActualUse(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                 'pc_class_code' => 'required|unique:rpt_property_actual_uses,pc_class_code,' .$request->input('id'). ',id,pau_actual_use_code,' .$request->input('pau_actual_use_code').',pau_actual_use_desc,' .$request->input('pau_actual_use_desc'),
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
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                'pc_class_code' => 'required|unique:rpt_property_classes,pc_class_code,' .$request->input('id'). ',id,pc_class_description,' .$request->input('pc_class_description'),
                'pc_class_no'=>'required',
                'pc_class_description'=>'required',
                
                'pc_unit_value_option'=>'required',
                 'pc_taxability_option'=>'required',
                
                'class_id'=>'required'
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
            $RptPropertyClass = RptPropertyClass::find($id);
            if($RptPropertyClass->created_by == \Auth::user()->creatorId()){
                $RptPropertyClass->delete();
            }
    }
}
