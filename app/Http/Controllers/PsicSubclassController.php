<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\PsicSubclass;
use App\Models\PsicDivision;
use App\Models\PsicClass;
use App\Models\BploRequirements;
use App\Models\Bplo\PsicTfoc;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class PsicSubclassController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;
    public function __construct(){
		$this->_psicsubclass = new PsicSubclass(); 
        $this->_PsicTfoc = new PsicTfoc(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_bplorequirement = new BploRequirements(); 
        $this->data = array('id'=>'','section_id'=>'','division_id'=>'','group_id'=>'','class_id'=>'','subclass_code'=>'','subclass_description'=>'','is_active'=>'1');  
        $this->slugs = 'administrative/psic-libraries/sub-class';
    }
    public function sectioncodeList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_psicsubclass->sectioncodeList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']="[".$val->section_code." - ".$val->section_description."]=>[".$val->division_code." - ".$val->division_description."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function sectionAjaxList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_psicsubclass->sectionAjaxList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->section_code." - ".$val->section_description;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function divisionAjaxList(Request $request){
       $data = $this->_psicsubclass->divisionAjaxList($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
    public function groupAjaxList(Request $request){
       $data = $this->_psicsubclass->groupAjaxList($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
    public function classAjaxList(Request $request){
       $data = $this->_psicsubclass->classAjaxList($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
    public function classcodeList(Request $request){
       $data = $this->_psicsubclass->classcodeList($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
    public function divisionAllData(Request $request){
      $getgroups = $this->_psicsubclass->divisionAllData($request->input('section_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
       $htmloption .= '<option value="' . $value->id . '">' . $value->division_code . ' - ' . $value->division_description . '</option>';
      }
      echo $htmloption;
    }
    public function groupAllData(Request $request){
      $getgroups = $this->_psicsubclass->groupAllData($request->input('division_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
       $htmloption .= '<option value="' . $value->id . '">'. $value->group_code . ' - ' . $value->group_description . '</option>';
      }
      echo $htmloption;
    }
    public function classAllData(Request $request){
      $getgroups = $this->_psicsubclass->classAllData($request->input('group_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
       $htmloption .= '<option value="' . $value->id . '">'. $value->class_code . ' - ' . $value->class_description . '</option>';
      }
      echo $htmloption;
    }
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        $arrsection = array();  $arrdivision =array(); $arrgroup = array(); $arrclass = array();
         $arrsection[''] ="Select Section";
        foreach ($this->_psicsubclass->sectionAllData() as $val) {
           $arrsection[$val->id]=$val->section_code." - ".$val->section_description;
        }
        $arrdivision[''] ="Select Division";
        foreach ($this->_psicsubclass->divisionAllData() as $val) {
           $arrdivision[$val->id]=$val->division_code." - ".$val->division_description;
        }
        $arrgroup[''] ="Select Group";
        foreach ($this->_psicsubclass->groupAllData() as $val) {
           $arrgroup[$val->id]=$val->group_code." - ".$val->group_description;
        }
        $arrclass[''] ="Select Class";
        foreach ($this->_psicsubclass->classAllData() as $val) {
           $arrclass[$val->id]=$val->class_code." - ".$val->class_description;
        }
		return view('psicsubclass.index',compact('arrsection','arrdivision','arrgroup','arrclass'));
    }
    public function Establishmentview(Request $request)
    {
        $view_subclass_id=$request->input('id');
        return view('psicsubclass.view',compact('view_subclass_id'));
    }
    public function getViewList(Request $request){
        $data = $this->_psicsubclass->getEstabilshment($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;

        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $or_no='';
            $or_date='';
            $arrOr = $this->_psicsubclass->getLastORDetails($row->busn_id);
            if(isset($arrOr)){
                $or_no=$arrOr->or_no;
                $or_date=date("d M, Y",strtotime($arrOr->cashier_or_date));
            }
            $arr[$i]['last_or_no']=$or_no;
            $arr[$i]['last_or_date']=$or_date;
            $arr[$i]['payment_status']='<span style="color:red;">Pending</span>';
            $arrStatus = $this->_psicsubclass->getPaymentStatus($row->busn_id);
            if(isset($arrStatus)){
                if($arrStatus->payment_status==1){
                    $arr[$i]['payment_status']='<span style="color:green;">Paid</span>';
                }
            }

            if($row->app_code=='3'){
                $arr[$i]['srno']='<strike style="color:red;">'.$arr[$i]['srno'].' </strike>';
                $arr[$i]['busn_name']='<strike style="color:red;">'.$arr[$i]['busn_name'].' </strike>';
                $arr[$i]['last_or_no']='<strike style="color:red;">'.$arr[$i]['last_or_no'].' </strike>';
                $arr[$i]['last_or_date']='<strike style="color:red;">'.$arr[$i]['last_or_date'].' </strike>';
                $arr[$i]['payment_status']='<strike style="color:red;">'.$arr[$i]['payment_status'].' </strike>';
            }
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
    public function getsubclassbyclass(Request $request){
        $getsubclasses = $this->_psicsubclass->getsubclassbyclass($request->input('class_id'),$request->input('group_id'),$request->input('division_id'),$request->input('section_id'));
          $htmloption ="<option value=''>Please Select</option>";
          foreach ($getsubclasses as $key => $value) {
            $htmloption .='<option value="'.$value->id.'">'.$value->subclass_description.'</option>';
          }
          echo $htmloption;
    }
    
    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_psicsubclass->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
  
            $arr[$i]['subclass_code']=$row->subclass_code;
            $section_description = wordwrap($row->section_code.'-'.$row->section_description, 40, "<br />\n");
            $division_description = wordwrap($row->division_description, 40, "<br />\n");
            $arr[$i]['section_description']="<div class='showLess'>".$section_description."</div>";
            $arr[$i]['division_description']="<div class='showLess'>".$division_description."</div>";
            $group_description = wordwrap($row->group_description, 40, "<br />\n");
            $class_description = wordwrap($row->class_description, 40, "<br />\n");
            $arr[$i]['group_description']="<div class='showLess'>".$group_description."</div>";
            $arr[$i]['class_description']="<div class='showLess'>".$class_description."</div>";
            $subclass_description = wordwrap($row->subclass_description, 40, "<br />\n");
            $arr[$i]['subclass_description']="<div class='showLess'>".$subclass_description."</div>";
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
             $arr[$i]['default']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/psicsubclass/view?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="View"  data-title="Business Establishment View" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';
            
            
            $deleteAction=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  

            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/psicsubclass/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage PSIC SubClass">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="'.url('/PsicTfoc?type=2&name='.$row->subclass_description.'['.$row->subclass_code.']'.'&sid='.$row->id).'" title="Set Charges" class="btn btn-sm align-items-center">
                        <i class="ti ti-currency-dollar text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="'.url('/bplorequirements?sid='.$row->id).'" title="Set Requirement" class="btn btn-sm align-items-center">
                        <i class="ti-write text-white"></i>
                    </a>
                </div>'.$deleteAction;
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
    public function getgroupbyClass(Request $request){
      $getgroups = $this->_psicsubclass->groupClassCode($request->input('division_id'));
      $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
       $htmloption .= '<option value="' . $value->id . '">' . '[' . $value->group_code . ' - ' . $value->group_description . ' => ' . $value->class_code . ' - ' . $value->class_description . ']' . '</option>';
      }
      echo $htmloption;
    }
    
    public function getPsicSectionsId(Request $request){
      $data = PsicDivision::find($request->input('division_id'));
      $htmloption = $data->section_id;

      echo $htmloption;
    }
    public function getPsicGroupId(Request $request){
      $data = PsicClass::find($request->input('classId'));
      $htmloption = $data->group_id;

      echo $htmloption;
    }
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
         $arrsection = array();  $arrdivision =array(); $arrgroup = array(); $arrclass = array();
         $arrsection[''] ="Select Section";
        foreach ($this->_psicsubclass->sectioncode() as $val) {
           $arrsection[$val->id]="[".$val->section_code." - ".$val->section_description."]=>[".$val->division_code." - ".$val->division_description."]";
        }
        $arrdivision[''] ="Select Division";
        $arrgroup[''] ="Select Group";
        $arrclass[''] ="Select Class";
        
        //print_r($arrsection); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PsicSubclass::find($request->input('id'));
            $arrdivision[''] ="Select Division";
            foreach ($this->_psicsubclass->divisioncode($request->input('section_id')) as $val) {
               $arrdivision[$val->id]=$val->division_description;
            }

            foreach ($this->_psicsubclass->groupcode($data->division_id,$data->section_id) as $val) {
               $arrgroup[$val->id]=$val->group_description;
            }

            foreach ($this->_psicsubclass->classcode($data->division_id) as $val) {
               $arrclass[$val->id]="[".$val->group_code." - ".$val->group_description."]=>[".$val->class_code." - ".$val->class_description."]";
            }
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
            	$this->data['subclass_modified_by']=\Auth::user()->creatorId();
            	 $this->data['subclass_modified_date'] = date('Y-m-d H:i:s');
                $this->_psicsubclass->updateData($request->input('id'),$this->data);
                $success_msg = 'Psic Subclass updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "psic_subclasses",'action' =>"update",'id'=>$request->input('id')]);
                $content = "User ".\Auth::user()->name." Updated Subclass ".$this->data['subclass_code']; 
            }else{
            	$this->data['subclass_generated_by']=\Auth::user()->creatorId();
            	$this->data['subclass_generated_date'] = date('Y-m-d H:i:s');
                $last_insert_id=$subclass_id = $this->_psicsubclass->addData($this->data);
                $this->copyChargesDetails($request->input('section_id'),$subclass_id);
                $this->copyRequirementDetails($subclass_id);
                $success_msg = 'Psic Subclass added successfully.';
                Session::put('remort_serv_session_det', ['table' => "psic_subclasses",'action' =>"store",'id'=>$last_insert_id]);
                $content = "User ".\Auth::user()->name." Added Subclass ".$this->data['subclass_code']; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('psicsubclass.index')->with('success', __($success_msg));
    	}
        return view('psicsubclass.create',compact('data','arrsection','arrdivision','arrgroup','arrclass'));
	}
    public function copyRequirementDetails($subclass_id)
    {
       $arrDetails = $this->_psicsubclass->getSectionRequirement($this->data['section_id']);
        if(count($arrDetails)>0){
            $bplo_req_ids=[];
            $bplo_req_rltn_ids=[];
            $rel_key=0;
            foreach($arrDetails as $key=>$val){
                $data['section_id']=$this->data['section_id'];
                $data['division_id']=$this->data['division_id'];
                $data['group_id']=$this->data['group_id'];
                $data['class_id']=$this->data['class_id'];
                $data['subclass_id']=$subclass_id;
                $data['apptype_id']=$val->apptype_id;
                $data['created_by']= $data['updated_by'] =\Auth::user()->id;
                $data['created_at']= $data['updated_at'] = date('Y-m-d H:i:s');
                $bploRequirmentId=$this->_bplorequirement->addData($data);
                $bplo_req_ids[$key]=$bploRequirmentId;
                if(isset($val->requirement_json)){
                    $arrJson = json_decode($val->requirement_json,true);
                    foreach($arrJson as $r_key=>$r_val){
                        $arrReq['bplo_requirement_id']=$bploRequirmentId;
                        $arrReq['requirement_id']=$r_val['requirement_id'];
                        $arrReq['subclass_id']=$subclass_id;
                        $arrReq['is_required']=isset($r_val['is_required'])?$r_val['is_required']:'0';
                        $arrReq['is_active']=$r_val['is_active'];
                        $arrReq['remark']=$r_val['remark'];
                        $arrReq['updated_by'] = $arrReq['created_by'] = \Auth::user()->creatorId();
                        $arrReq['updated_at'] = $arrReq['created_at'] = date('Y-m-d H:i:s');
                        $bplo_req_rltn_id=$this->_bplorequirement->addRequiredmentRelationData($arrReq);
                        $bplo_req_rltn_ids[$rel_key]=$bplo_req_rltn_id;
                        $rel_key++;
                    }
                }
            }
            Session::put('remort_serv_add_req_rltn', ['remort_req_rltn_table' => "bplo_requirement_relations",'remort_req_rltn_action' =>"store",'remort_req_rltn_ids'=>$bplo_req_rltn_ids]);
            Session::put('remort_serv_add_array_data', ['remort_array_table' => "bplo_requirements",'remort_array_action' =>"store",'remort_array_id'=>$bplo_req_ids]);
        }
    }
    public function copyChargesDetails($section_id,$subclass_id)
    {
       $arrDetails = $this->_psicsubclass->getSectionCharges($section_id);
        if(isset($arrDetails)){
            $data = $arrDetails;
            foreach($arrDetails as $key=>$val){
                $data = (array)$val;
                unset($data['id']);
                $data['subclass_id']=$subclass_id;
                $data['ptfoc_access_type']=2;
                $this->_PsicTfoc->addData($data);
            }
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                
                'division_id'=>'required',
                'subclass_code'=>'required|unique:psic_subclasses,subclass_code,'.$request->input('id'),
                'group_id'=>'required',
                'class_id'=>'required',
                'subclass_description'=>'required',
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
    public function ActiveInactive(Request $request){
        //$this->is_permitted($this->slugs, 'delete');
        $is_activeinactive = $request->input('is_activeinactive');
        $id = $request->input('id');
        $data=array('is_active' => $is_activeinactive);
        $this->_psicsubclass->updateData($request->input('id'),$data);
        Session::put('remort_serv_session_det', ['table' => "psic_subclasses",'action' =>"update",'id'=>$request->input('id')]);
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Sub Class ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function destroy($id)
    {
		$this->is_permitted($this->slugs, 'delete');
            $PsicSubclass = PsicSubclass::find($id);
            if($PsicSubclass->subclass_generated_by == \Auth::user()->creatorId()){
                $PsicSubclass->delete();
                return redirect()->route('psicdivision.index')->with('success', __('Psic Subclass successfully deleted.'));
            }
           
    }
}
