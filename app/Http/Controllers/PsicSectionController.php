<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\PsicSection;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class PsicSectionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public function __construct(){
        $this->_section = new PsicSection(); 
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','section_code'=>'','section_description'=>'','section_status'=>'1');  
    }
    
    public function index(Request $request)
    {
        $PsicSections = $this->_section->getSections();
        return view('psicsection.index', compact('PsicSections'));
    }

    public function getdivisionbysection(Request $request){
      $getdivisions = $this->_section->divisioncode($request->input('section_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getdivisions as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->division_description.'</option>';
      }
      echo $htmloption;
    }

    public function store(Request $request)
    {
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PsicSection::find($request->input('id'));
        }

        $loginuser = \Auth::user();
        //echo "<pre>"; print_r($loginuser); exit;
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if($request->input('id')>0){
                  $id= $request->input('id');
                  if($data->section_status != $request->section_status){
                   $updatecolumn =array();
                   $updatecolumn['division_status'] = $request->section_status;
                   $updatecolumn['division_modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['division_modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('section_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_divisions',$ids,'section_id',$updatecolumn);

                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->section_status;
                   $updatecolumn['group_modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['group_modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('section_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_groups',$ids,'section_id',$updatecolumn);

                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->section_status;
                   $updatecolumn['modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('section_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_classes',$ids,'section_id',$updatecolumn);
                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->section_status;
                   $updatecolumn['subclass_modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['subclass_modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('section_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_subclasses',$ids,'section_id',$updatecolumn);
                }
                 $this->data['section_generated_by']=\Auth::user()->creatorId();
                 $this->data['section_generated_date'] = date('Y-m-d H:i:s');
                 $this->_section->updateData($request->input('id'),$this->data);
                 Session::put('remort_serv_session_det', ['table' => "psic_sections",'action' =>"update",'id'=>$request->input('id')]);
                 $success_msg = 'PSIC Section updated successfully.'; 
                 $systemlogdata = array(); $content = "User ".\Auth::user()->name." Updated Section ".$this->data['section_code']; 
                 $systemlogdata['created_by'] = \Auth::user()->creatorId();
                 $systemlogdata['log_content'] = $content;
                 $systemlogdata['created_at'] = date('Y-m-d H:i:s');
                 $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
                 $this->_commonmodel->addSystemActivityLog($systemlogdata);

            }else{
                $systemlogdata = array(); $content = "User ".\Auth::user()->name." Added Section ".$this->data['section_code']; 
                 $systemlogdata['created_by'] = \Auth::user()->creatorId();
                 $systemlogdata['log_content'] = $content;
                 $systemlogdata['created_at'] = date('Y-m-d H:i:s');
                 $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
                 $this->_commonmodel->addSystemActivityLog($systemlogdata);
                 $this->data['section_modified_by']=\Auth::user()->creatorId();
                 $this->data['section_modified_date'] = date('Y-m-d H:i:s');
                 $this->data['section_generated_date'] = date('Y-m-d H:i:s');
                 $last_insert_id=$this->_section->addData($this->data);
                 $success_msg = 'PSIC Section added successfully.';
                 Session::put('remort_serv_session_det', ['table' => "psic_sections",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('psicsection.index')->with('success', __($success_msg));
        }
        return view('psicsection.create',compact('data'));
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'section_code'=>'required|unique:psic_sections,section_code,'.(int)$request->input('id'),
                'section_description'=>'required'
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
            $Section = PsicSection::find($id);
            if($Section->created_by == \Auth::user()->creatorId()){
                $Section->delete();
                return redirect()->route('psicsection.index')->with('success', __('PSIC Section successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
    }
}
