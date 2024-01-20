<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\BploRequirements;
use App\Models\PsicSubclass;
use App\Models\BploRequirementRelation;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class BblorequirementController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $headingName='';
    public function __construct(Request $request){
		$this->_bplorequirement = new BploRequirements(); 
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','section_id'=>'','division_id'=>'','group_id'=>'','class_id'=>'','subclass_id'=>'','req_id'=>'','req_code_abbreviation'=>'','req_description'=>'','apptype_id'=>'','is_active2'=>'1');  
        if(isset($_GET['sid'])){
            $sid = $_GET['sid'];
            if($sid>0){
                $this->getsubClassDetails($sid);
                
            }
        }
    }
    public function getsubClassDetails($sid){
        $details = PsicSubclass::find($sid);
        $this->data['section_id']=$details->section_id;
        $this->data['division_id']=$details->division_id;
        $this->data['group_id']=$details->group_id;
        $this->data['class_id']=$details->class_id;
        $this->data['subclass_id']=$details->id;
        $this->headingName = $details->subclass_description.' ['.$details->subclass_code.']';
    }
    public function index(Request $request)
    {
        $headingName = $this->headingName;
        return view('bplorequirement.index',compact('headingName'));
        
    }

    public function view(Request $request)
    {
        $id=$request->input('id');
        $Requirement = $this->_bplorequirement->getRequirdmentRequiet($id);
        return view('bplorequirement.view', compact('Requirement'));
         
    }


    
    public function getList(Request $request){
        $sid= '';
        if($request->input('sid')>0){
            $sid = '&sid='.$request->input('sid');
        }
        // $id=$;
        // $data2=$this->_bplorequirement->requirementRelations($id);
        // $data=$data2->is_active;
        // print_r($data2);exit();
        $data=$this->_bplorequirement->getbplorequirements($request);
        $arr=array();
        $i="0";  
		$sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;		
        foreach ($data['data'] as $row){
			$sr_no=$sr_no+1;			
            $arr[$i]['id']=$row->id;
			$arr[$i]['srno']=$sr_no;
            $countRelation=$this->_bplorequirement->requirementRelations($row->id);
            // print_r($data2);exit();
            //$section_code = wordwrap($row->section_code.'-'.$row->section_description, 40, "<br />\n");
            //$arr[$i]['division_description'] = wordwrap($row->division_description, 40, "<br />\n");
            //$arr[$i]['group_description'] = wordwrap($row->group_description, 40, "<br />\n");
            //$arr[$i]['class_description'] = wordwrap($row->class_description, 40, "<br />\n");
            //$arr[$i]['subclass_description'] = wordwrap($row->subclass_description, 40, "<br />\n");
            //$arr[$i]['section_code']="<div class='showLess'>".$section_code."</div>";
            //$arr[$i]['group_description']="<div class='showLess'>".$arr[$i]['group_description']."</div>";
            //$arr[$i]['class_description']="<div class='showLess'>".$arr[$i]['class_description']."</div>";
            //$arr[$i]['subclass_description']="<div class='showLess'>".$arr[$i]['subclass_description']."</div>";
            //$arr[$i]['division_description']="<div class='showLess'>".$arr[$i]['division_description']."</div>";
            $arr[$i]['app_type']=$row->app_type;
            // $arr[$i]['req_code_abbreviation']=$row->req_code_abbreviation.'-'.$row->req_description;
            // $arr[$i]['br_remarks']=$row->br_remarks;

            $arr[$i]['is_active2']=($countRelation>0?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['requirement']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplorequirements/view?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="View"  data-title="BPLO Requirements View" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';
            $arr[$i]['action']='
               
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplorequirements/store?id='.$row->id.$sid).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit BPLO Requirements">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                ';
                //  <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinesspermitfee/destroy?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Delete"  data-title=""Bussiness Permit Fee Delete">
                //        <i class="ti-trash text-white text-white"></i>
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
    public function getNatureDetails($id=''){
        $arrNature= array();
        if(empty($id)){
            $arrNature[0]['id']='';
            $arrNature[0]['requirement_id']='';
            $arrNature[0]['is_active']='';
            $arrNature[0]['remark']='';
            $arrNature[0]['is_required']='';
            
        }else{
            $arr = $this->_bplorequirement->getrequirementRelation($id);
            //echo "<pre>"; print_r($arr); exit;
            foreach($arr as $key=>$val){
                $arrNature[$key]['id']=$val->id;
                $arrNature[$key]['requirement_id']=$val->requirement_id;
                $arrNature[$key]['is_active']=$val->is_active;
                $arrNature[$key]['remark']=$val->remark;
                $arrNature[$key]['is_required']=$val->is_required;
               
            }
        }
        return $arrNature;
    }
    public function store(Request $request){
        $data = (object)$this->data;
         $arrsection = array();  $arrdivision =array(); $arrgroup = array(); $arrclass = array(); $arrsubclass = array(); $arrrequirement = array(); $apptypes =array();
         $arrsection[''] ="Select Section";
        foreach ($this->_bplorequirement->sectioncode() as $val) {
           $arrsection[$val->id]=$val->section_code.'-'.$val->section_description;
        }
        $arrdivision[''] ="Select Division";
        $arrgroup[''] ="Select Group";
        $arrclass[''] ="Select Class";
        $arrsubclass[''] ="Select SubClass";
        $arrrequirement[''] ="Select Requirement";
        foreach ($this->_bplorequirement->requirementcode() as $val) {
           $arrrequirement[$val->id]=$val->req_code_abbreviation.'-'.$val->req_description;
        }
          $apptypes[''] ="Select Type";
        foreach ($this->_bplorequirement->apptypes() as $val) {
           $apptypes[$val->id]=$val->app_type;
        }
        
        $arrNature = $this->getNatureDetails();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploRequirements::find($request->input('id'));
            $arrNature = $this->getNatureDetails($request->input('id'));
            
            $checkdetails = $this->_bplorequirement->UpdateRequirdmentRequietExit($data->id);
        }
        foreach ($this->_bplorequirement->divisioncode($data->section_id) as $val) {
           $arrdivision[$val->id]=$val->division_description;
        }
        foreach ($this->_bplorequirement->groupcode($data->division_id,$data->section_id) as $val) {
           $arrgroup[$val->id]=$val->group_description;
        }
        foreach ($this->_bplorequirement->classcode($data->group_id,$data->division_id,$data->section_id) as $val) {
           $arrclass[$val->id]=$val->class_description;
        }
        foreach ($this->_bplorequirement->subclasscode($data->class_id,$data->group_id,$data->division_id,$data->section_id) as $val) {
           $arrsubclass[$val->id]=$val->subclass_description;
        }

		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if($request->input('id')>0){
            	$this->data['updated_by']=\Auth::user()->creatorId();
            	$this->data['updated_at'] = date('Y-m-d H:i:s');
                
                $this->_bplorequirement->updateData($request->input('id'),$this->data);
                $bploRequirmentId=$request->input('id');
                $success_msg = 'Bplo Requirement updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bplo Requirement ".$this->data['req_description']; 
                Session::put('remort_serv_session_det', ['table' => "bplo_requirements",'action' =>"update",'id'=>$request->input('id')]);
            }else{
            	$this->data['created_by']=\Auth::user()->creatorId();
            	$this->data['created_at'] = date('Y-m-d H:i:s');
                // print_r($this->data);
                // exit();
                $bploRequirmentId=$this->_bplorequirement->addData($this->data);
                // echo $bploRequirmentId;
                // exit();
                $success_msg = 'Bplo Requirement added successfully.';
                $content = "User ".\Auth::user()->name." Added Bplo Requirement ".$this->data['req_description']; 
                Session::put('remort_serv_session_det', ['table' => "bplo_requirements",'action' =>"store",'id'=>$bploRequirmentId]);
            }

            
            if($bploRequirmentId>0){
               $this->addRequirmentRelation($request,$bploRequirmentId);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
             session()->flash('success', $success_msg);
             return redirect()->back();
    	}
        return view('bplorequirement.create',compact('data','arrsection','arrdivision','arrgroup','arrclass','arrsubclass','arrrequirement','apptypes','arrNature'));
	}

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                'section_id'=>'required',
                'division_id'=>'required',
                'group_id'=>'required',
                'class_id'=>'required',
                'apptype_id' => 'required|unique:bplo_requirements,apptype_id,'.$request->input('id').',id,section_id,'.$request->input('section_id').',division_id,'.$request->input('division_id').',group_id,'.$request->input('group_id').',class_id,'.$request->input('class_id').',subclass_id,'.$request->input('subclass_id').',apptype_id,'.$request->input('apptype_id'),
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
    
    public function addRequirmentRelation($request,$bploRequirmentId){
        $requirement_id = $request->input('requirement_id');
        $arr = array();
        $i=0;
        $bplo_req_rltn_ids=[];
        foreach ($requirement_id as $key => $value) {
            $is_required = 0; // Default to 0
               $isChecked = $request->input('is_required', []);
                // Check if the checkbox input exists in the request and if it's checked
                if ($request->has('is_required_' . $value) && $request->input('is_required_' . $value) == '1') {
                    $is_required = 1;
                }
            if(!empty($request->input('requirement_id')[$key])){
                $arr[$i]['bplo_requirement_id']=$bploRequirmentId;
                $arr[$i]['requirement_id']=$request->input('requirement_id')[$key];
                $arr[$i]['subclass_id']=$request->input('subclass_id');
                $arr[$i]['is_active']=$request->input('is_active')[$key];
                $arr[$i]['remark']=$request->input('remark')[$key];
                $arr[$i]['is_required']=$is_required;
                $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                $check= $this->_bplorequirement->checkRequirdmentRequietExit($arr[$i]);
                if(count($check)>0){

                   $this->_bplorequirement->updateRequiredmentRelationData($check[0]->id,$arr[$i]);
                   $bplo_req_rltn_ids[$i]=$check[0]->id;
                }
                else{
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $last_insert_id=$this->_bplorequirement->addRequiredmentRelationData($arr[$i]);
                    $bplo_req_rltn_ids[$i]=$last_insert_id;
                }
                $i++;
            }
        }
        Session::put('remort_serv_add_req_rltn', ['remort_req_rltn_table' => "bplo_requirement_relations",'remort_req_rltn_action' =>"storeAndUpdate",'remort_req_rltn_ids'=>$bplo_req_rltn_ids]);
    }


    public function Delete(Request $request){
            $id = $request->input('id');
            $BploRequirementRelation = BploRequirementRelation::find($id);
            if($BploRequirementRelation->created_by == \Auth::user()->creatorId()){
                $BploRequirementRelation->delete();
            }
    }
    public function destroy($id)
    {
        $Subclass = PsicSubclass::find($id);
        if($Subclass->subclass_generated_by == \Auth::user()->creatorId()){
            $Subclass->delete();
            return redirect()->route('psicdivision.index')->with('success', __('PSIC Sub Class successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
}
