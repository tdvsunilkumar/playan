<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\PsicGroup;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class PsicGroupController extends Controller
{
    public $data = [];
    public $postdata = [];
    public function __construct(){
		$this->_psicGroup = new PsicGroup(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','section_id'=>'','division_id'=>'','group_code'=>'','group_description'=>'','is_active'=>'1'); 
		$this->slugs = 'administrative/psic-libraries/group';		
    }
    public function index(Request $request)
    {
        
		$this->is_permitted($this->slugs, 'read');
		$Psicgroup = $this->_psicGroup->getgroups();
         $arrsection = array();  $arrdivision =array(); $arrgroup = array(); $arrclass = array();
         $arrsection[''] ="Select Section";
        // foreach ($this->_psicsubclass->sectionAllData() as $val) {
        //    $arrsection[$val->id]=$val->section_code." - ".$val->section_description;
        // }
        $arrdivision[''] ="Select Division";
        // foreach ($this->_psicsubclass->divisionAllData() as $val) {
        //    $arrdivision[$val->id]=$val->division_code." - ".$val->division_description;
        // }
        return view('psicgroup.index', compact('Psicgroup','arrsection','arrdivision'));
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_psicGroup->getList($request);

        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;

            $actions = '';
            
                $actions .= '<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('psicgroup/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage PSIC Group">

                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>';
                        $arr[$i]['group_code']=$row->group_code;
            $section_description = wordwrap($row->section_code.'-'.$row->section_description, 40, "<br />\n");
            $division_description = wordwrap($row->division_code.'-'.$row->division_description, 40, "<br />\n");
            $group_description = wordwrap($row->group_description, 40, "<br />\n");
            $arr[$i]['section_description']="<div class='showLess'>".$section_description."</div>";
            $arr[$i]['division_description']="<div class='showLess'>".$division_description."</div>";
            $arr[$i]['group_description']="<div class='showLess'>".$group_description."</div>";
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
            $i++;
        }
       
        $totalRecords=$data['data_cnt'];
        $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    public function getclassbygroup(Request $request){
        $getclasses = $this->_psicGroup->classcodebygroup($request->input('group_id'),$request->input('division_id'),$request->input('section_id'));
          $htmloption ="<option value=''>Please Select</option>";
          foreach ($getclasses as $key => $value) {
            $htmloption .='<option value="'.$value->id.'">'.$value->class_description.'</option>';
          }
          echo $htmloption;
    }
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
         $arrsection = array();  $arrdivision =array();
         $arrsection[''] ="Select Section";
        foreach ($this->_psicGroup->sectioncode() as $val) {
           $arrsection[$val->id]="[".$val->section_code." - ".$val->section_description."]=>[".$val->division_code." - ".$val->division_description."]";
        }
        $arrdivision[''] ="Select Division";
        
        //print_r($arrsection); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PsicGroup::find($request->input('id'));
            if(isset($data)){
                $arrdivision[''] ="Select Division";
                $getdivisions = $this->_psicGroup->divisioncode($data->section_id);
                //print_r($getdivisions); exit;
                foreach ($getdivisions  as $key => $val) {
                    $arrdivision[$val->id]=$val->division_description;
                }
            }
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $arrPrevData = PsicGroup::find($request->input('id'));
                if($arrPrevData->is_active != $request->is_active){
                    $updatecolumn =array();
                    $updatecolumn['is_active'] = $request->is_active;
                    $updatecolumn['modified_by'] = \Auth::user()->creatorId();
                    $updatecolumn['modified_date'] = date('Y-m-d H:i:s');
                    $ids = array('group_id' => $request->id);
                    $this->_commonmodel->updatestatusmaster('psic_classes',$ids,'group_id',$updatecolumn);
                    $updatecolumn =array();
                    $updatecolumn['is_active'] = $request->is_active;
                    $updatecolumn['subclass_modified_by'] = \Auth::user()->creatorId();
                    $updatecolumn['subclass_modified_date'] = date('Y-m-d H:i:s');
                    $ids = array('group_id' => $request->id);
                    $this->_commonmodel->updatestatusmaster('psic_subclasses',$ids,'group_id',$updatecolumn);
                }
            	$this->data['group_modified_by']=\Auth::user()->creatorId();
            	$this->data['group_modified_date'] = date('Y-m-d H:i:s');
                $this->_psicGroup->updateData($request->input('id'),$this->data);
                $content = "User ".\Auth::user()->name." Updated Group ".$this->data['group_code']; 
                $success_msg = 'PSIC Group updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "psic_groups",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $content = "User ".\Auth::user()->name." Added Group ".$this->data['group_code']; 
            	$this->data['group_generated_date'] = date('Y-m-d H:i:s');
            	$this->data['group_generated_by']=\Auth::user()->creatorId();
                $last_insert_id=$this->_psicGroup->addData($this->data);
                $success_msg = 'PSIC Group added successfully.';
                Session::put('remort_serv_session_det', ['table' => "psic_groups",'action' =>"store",'id'=>$last_insert_id]);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('psicgroup.index')->with('success', __($success_msg));
    	}
        return view('psicgroup.create',compact('data','arrsection','arrdivision'));
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                'division_id'=>'required',
                'group_code'=>'required|unique:psic_groups,group_code,'.$request->input('id'),
                'group_description'=>'required'
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
    

    public function destroy($id)
    {
		$this->is_permitted($this->slugs, 'delete');
       
            $PsicGroup = PsicGroup::find($id);
            if($PsicGroup->group_generated_by == \Auth::user()->creatorId()){
                $PsicGroup->delete();
                return redirect()->route('psicdivision.index')->with('success', __('PSIC Group successfully deleted.'));
            }
            
    }
}
