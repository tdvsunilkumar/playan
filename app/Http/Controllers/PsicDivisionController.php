<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\PsicDivision;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class PsicDivisionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public function __construct(){
		$this->_psicDivision = new PsicDivision(); 
         $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','section_id'=>'','division_code'=>'','division_description'=>'','division_status'=>'1');  
		$this->slugs = 'administrative/psic-libraries/division';
    }
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        
            //$Psicdivision = PsicDivision::where('division_generated_by', '=', \Auth::user()->creatorId())->get();
            $Psicdivision = $this->_psicDivision->getdivisions();
            $arrsection[''] ="Select Section";
            foreach ($this->_psicDivision->sectioncode() as $val) {
               $arrsection[$val->id]=$val->section_code.' - '.$val->section_description;
            }

        return view('psicdivision.index', compact('Psicdivision','arrsection'));
        
    }
    

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_psicDivision->getList($request);

        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $actions = '';
            
                $actions .= '<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('psicdivision/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage PSIC Division">

                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>';
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['division_code']=$row->division_code;
            $section_description = wordwrap($row->section_code.'-'.$row->section_description, 40, "<br />\n");
            $division_description = wordwrap($row->division_description, 40, "<br />\n");
            $arr[$i]['section_description']="<div class='showLess'>".$section_description."</div>";
            $arr[$i]['division_description']="<div class='showLess'>".$division_description."</div>";
            $arr[$i]['is_active']=($row->division_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
    public function getgroupbydivision(Request $request){
      $getgroups = $this->_psicDivision->groupcodebydivision($request->input('division_id'),$request->input('section_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->group_description.'</option>';
      }
      echo $htmloption;
    }
    
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
         $arrsection = array();
         $arrsection[''] ="Select Section";
        foreach ($this->_psicDivision->sectioncode() as $val) {
           $arrsection[$val->id]=$val->section_code.' - '.$val->section_description;
        }
        //print_r($arrsection); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PsicDivision::find($request->input('id'));
            
        }
        
		   if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                   $id = $request->input('id');
                   $PsicDivision = PsicDivision::find($request->input('id'));
                   if($PsicDivision->division_status != $request->division_status){
                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->division_status;
                   $updatecolumn['group_modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['group_modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('division_id' => $id);
                   print_r($ids);
                   $this->_commonmodel->updatestatusmaster('psic_groups',$ids,'division_id',$updatecolumn);

                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->division_status;
                   $updatecolumn['modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('division_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_classes',$ids,'division_id',$updatecolumn);
                   $updatecolumn =array();
                   $updatecolumn['is_active'] = $request->division_status;
                   $updatecolumn['subclass_modified_by'] = \Auth::user()->creatorId();
                   $updatecolumn['subclass_modified_date'] = date('Y-m-d H:i:s');
                   $ids = array('division_id' => $id);
                   $this->_commonmodel->updatestatusmaster('psic_subclasses',$ids,'division_id',$updatecolumn);
                  }
            	  $this->data['division_modified_by']=\Auth::user()->creatorId();
                $this->data['division_modified_date'] = date('Y-m-d H:i:s');
                $this->_psicDivision->updateData($request->input('id'),$this->data);
                $success_msg = 'PSIC Division updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Division ".$this->data['division_code']; 
                Session::put('remort_serv_session_det', ['table' => "psic_divisions",'action' =>"update",'id'=>$request->input('id')]);
              }else{
                $content = "User ".\Auth::user()->name." Added Division ".$this->data['division_code']; 
                $this->data['division_generated_by']=\Auth::user()->creatorId();
                $this->data['division_generated_date'] = date('Y-m-d H:i:s');
                  $last_insert_id=$this->_psicDivision->addData($this->data);
                  $success_msg = 'PSIC Division added successfully.';
                  Session::put('remort_serv_session_det', ['table' => "psic_divisions",'action' =>"store",'id'=>$last_insert_id]);
              }

             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('psicdivision.index')->with('success', __($success_msg));
    	}
        return view('psicdivision.create',compact('data','arrsection'));
	}

  public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                'section_id'=>'required',
                'division_code'=>'required|unique:psic_divisions,division_code,'.$request->input('id'),
                'division_description'=>'required'
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
       
            $PsicDivision = PsicDivision::find($id);
            if($PsicDivision->division_generated_by == \Auth::user()->creatorId()){
                $PsicDivision->delete();
                return redirect()->route('psicdivision.index')->with('success', __('PSIC Division successfully deleted.'));
            }
            
    }
}
