<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptAssessmentLevel;
use App\Models\RptAssessmentLevelsRelation;
use App\Models\CommonModelmaster;
use App\Models\RptBuildingUnitValue;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;

class AssessmentLevelController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrLocalCode = array();
     public $arrBrgyCode = array();
     public $arrRevisionCode = array();
     public $arrpkCode = array(""=>"Please Select"); 
     public $arrClassCode = array(""=>"Please Select"); 
     public $arrSubClassCode = array(""=>"Please Select");
     public $arrActualCode = array(""=>"Please Select"); 
     private $slugs;
     private $slugs2;
     
     
     public function __construct(){
        $this->_rptassessmentlevelRelation = new RptAssessmentLevelsRelation();
        $this->_rptassessmentlevel = new RptAssessmentLevel();
        $this->_rptbuildingunitvalue = new RptBuildingUnitValue();
            $this->data = array('id'=>'','mun_no'=>'','loc_group_brgy_no'=>'','ps_subclass_code'=>'','pk_code'=>'','pc_class_code'=>'','pau_actual_use_code'=>'','rvy_revision_year'=>'','al_minimum_unit_value'=>'','al_maximum_unit_value'=>'','al_assessment_level'=>'');
        
        foreach ($this->_rptassessmentlevel->getpkCode() as $val) {
            $this->arrpkCode[$val->id]=$val->pk_code.'-'.$val->pk_description;
            $pk_code=$val->pk_code;
        } 
        
        foreach ($this->_rptassessmentlevel->getActualCode() as $val) {
            $this->arrActualCode[$val->id]=$val->pau_actual_use_code.'-'.$val->pau_actual_use_desc;
        } 
        
        foreach ($this->_rptassessmentlevel->getLocal() as $val) {
            $this->arrLocalCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
        }
        foreach ($this->_rptassessmentlevel->getBrgy() as $val) {
            $this->arrBrgyCode[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        } 
        foreach ($this->_rptassessmentlevel->getRevisionDefult() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        foreach ($this->_rptassessmentlevel->getRevision() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        $this->slugs = 'real-property/assessment-level';
        $this->slugs2="real-property/property-data/generalrevision";

    }
    public function getKindDetailss(Request $request){
         $data = $this->_rptassessmentlevel->getRptClass($request);
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
    public function getKindDetailssActualUs(Request $request){
         $data = $this->_rptassessmentlevel->getRptSubClass($request);
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
    public function getNatureDetails($id=''){
        $arrNature= array();
        if(empty($id)){
            $arrNature[0]['id']='';
            $arrNature[0]['minimum_unit_value']='';
            $arrNature[0]['maximum_unit_value']='';
            $arrNature[0]['assessment_level']='';
            $arrNature[0]['re_is_active']='';
            
            
        }else{
            $arr = $this->_rptassessmentlevel->getAssRequiet($id);
            // echo "<pre>"; print_r($arr); exit;
            foreach($arr as $key=>$val){
                $arrNature[$key]['id']=$val->relationId;
                $arrNature[$key]['minimum_unit_value']=$val->minimum_unit_value;
                $arrNature[$key]['maximum_unit_value']=$val->maximum_unit_value;
                $arrNature[$key]['assessment_level']=$val->assessment_level;
                $arrNature[$key]['re_is_active']=$val->re_is_active;
                
               
            }
        }
        return $arrNature;
    }

    public function index(Request $request)
    {
            $read1 = $this->is_permitted($this->slugs, 'read', 1);
            $read2 = $this->is_permitted($this->slugs2, 'read', 1);
            if (!($read1 > 0) && !($read2 > 0)){
                return abort(401);
            }
            $revisionyear = array(""=>"Select year");
            $propertykind = array(""=>"All");
            //$propertykind = $this->arrpkCode;
             foreach ($this->_rptassessmentlevel->getpkCode() as $val) {
                $propertykind[$val->id]=$val->pk_description;
                $pk_code=$val->pk_code;
            } 
            // Changes By Ali
            foreach ($this->_rptbuildingunitvalue->getRevisionActive() as $key => $value) {
                $revisionyear[$value->id] = $value->rvy_revision_year."-".$value->rvy_revision_code;
             }
    
             $selected_revision = $this->_rptbuildingunitvalue->getOneRevisionActiveDefault();
             //    
            return view('assessmentlevel.index',compact('revisionyear', 'selected_revision','propertykind'));
           
    }
    public function view(Request $request)
    {
        $id=$request->input('id');
        $serial_no=$request->input('serial_no');
            $DefaultData = $this->_rptassessmentlevel->getAssRequiet($id);
            $DefaultAssData = $this->_rptassessmentlevel->getAssmentDetails($id);

            // print_r($DefaultAssData);exit;
            return view('assessmentlevel.view', compact('DefaultData','DefaultAssData','serial_no'));
         
    }
    public function viewLand(Request $request)
    {
        $id=$request->input('id');
        $serial_no=$request->input('serial_no');
            $DefaultData = $this->_rptassessmentlevel->getAssRequiet($id);
            $DefaultAssData = $this->_rptassessmentlevel->getAssmentDetails($id);

            // print_r($DefaultAssData);exit;
            return view('assessmentlevel.view', compact('DefaultData','DefaultAssData','serial_no'));
         
    }
     
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 3);
    }
    public function getList(Request $request){
        $data=$this->_rptassessmentlevel->getList($request);
        $arr=array();
        $i="0";  
        $count = $request->start+1;  
        foreach ($data['data'] as $row){
             $status2 =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm ti-trash " style="background: #8d8a8a;color:#fff;"></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center  ti-reload text-white"  style="background: #8d8a8a"></a>'; 

            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$count;
            $arr[$i]['pk_code']=$row->pk_code.'-'.$row->pk_description;
            $arr[$i]['pc_class_code']=$row->pc_class_code.'-'.$row->pc_class_description;
            if($row->kindId == 3){
              $arr[$i]['pau_actual_use_code']="";
            }else{
               $arr[$i]['pau_actual_use_code']=$row->pau_actual_use_code.'-'.$row->pau_actual_use_desc; 
            }
            $arr[$i]['rvy_revision_year']=$row->rvy_revision_year.'-'.$row->rvy_revision_code;
            $arr[$i]['al_minimum_unit_value']=$this->money_format($row->al_minimum_unit_value);
            $arr[$i]['al_maximum_unit_value']=$this->money_format($row->al_maximum_unit_value);
            $arr[$i]['al_assessment_level']=$row->al_assessment_level;
            $arr[$i]['default']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/assessmentlevel/view?id='.$row->id).'&serial_no='.$count.'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="View"  data-title="Detailed View" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';
            if($row->is_approve =='1'){
                $approve = '<div class="action-btn  bg-success ms-2"><a href="#" class="mx-3 btn btn-sm approveunapprove ti ti-lock-open text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';
           }else{ $approve = '<div class="action-btn  bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm unapproveunapprove ti ti-lock-off text-white text-white" name="stp_print" value="1" id='.$row->id.'></a></div>'; }
           $arr[$i]['isapprove']=$approve;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            if($row->is_approve =='0'){
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/assessmentlevel/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Property Assessment Level">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>' 
              ;
            }
            else{
                $arr[$i]['action']='
            <div class="action-btn bg- ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center ti-pencil" style="background: #8d8a8a;color:#fff;"  data-size="lg"  title="Edit"  data-title="Edit Land Unit Value">
                       
                    </a>
                    </div>
                '.$status2.'
                </div>';
            }
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
            $i++;
            $count++;
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
        $data=array('is_active' => $is_activeinactive);
        $this->_rptassessmentlevel->updateActiveInactive($id,$data);
    }
    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptassessmentlevel->updateActiveInactive($id,$data);
    }
    public function getClassDetailss(Request $request){
        $id= $request->input('id');
        $data = $this->_rptassessmentlevel->getClassDetailss($id);
        echo json_encode($data);
    }  
     public function getSubClassDetailss(Request $request){
        $id= $request->input('id');
        $data = $this->_rptassessmentlevel->getSubClassDetailss($id);
        echo json_encode($data);
    } 
   

    public function store(Request $request){
        $brgyId=$this->_rptassessmentlevel->getBrgyId();
        if($brgyId != null)
        {
            $this->data['loc_group_brgy_no'] = $brgyId;
        }
        $data = (object)$this->data;
        $arrpkCode = $this->arrpkCode;
        $arrClassCode = $this->arrClassCode;
        $arrSubClassCode = $this->arrSubClassCode;
        $arrActualCode = $this->arrActualCode;
        $arrRevisionCode = $this->arrRevisionCode;
        $arrLocalCode = $this->arrLocalCode;
        $arrBrgyCode = $this->arrBrgyCode;
        $arrNature = $this->getNatureDetails();
        if($request->input('id')>0 && $request->input('submit')==""){

            $data = RptAssessmentLevel::where('rpt_assessment_levels.id',$request->input('id'))
                                        ->leftJoin('rpt_property_classes','rpt_property_classes.id','=','rpt_assessment_levels.pc_class_code')
                                        ->leftJoin('rpt_property_actual_uses','rpt_property_actual_uses.id','=','rpt_assessment_levels.pau_actual_use_code')
                                        ->select('rpt_assessment_levels.*',
                                            DB::raw('CASE WHEN pk_code = 3 THEN CONCAT(rpt_property_classes.pc_class_code,"-",rpt_property_classes.pc_class_description) ELSE CONCAT("[",rpt_property_classes.pc_class_code,"-",rpt_property_classes.pc_class_description,"=>",rpt_property_actual_uses.pau_actual_use_code,"-",rpt_property_actual_uses.pau_actual_use_desc,"]") END as text')
                                        )
                                        ->first();
                                        
            $arrNature = $this->getNatureDetails($request->input('id'));
             $checkdetails = $this->_rptassessmentlevel->UpdateAssRequietExit($data->id);
        }
        
        if($request->isMethod('post')){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }    
            //dd($this->data);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $access1 = $this->is_permitted($this->slugs, 'update', 1);
                $access2 = $this->is_permitted($this->slugs2, 'update', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->_rptassessmentlevel->updateData($request->input('id'),$this->data);
                $assId=$request->input('id');
                $success_msg = 'Assessment Level updated successfully.';
            }else{
                $access1 = $this->is_permitted($this->slugs, 'create', 1);
                $access2 = $this->is_permitted($this->slugs2, 'create', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                // if($pk_code = $request->input('pk_code')==3){
                //   $this->data['pau_actual_use_code'] = "";
                    
                // }else{
                //   $this->data['pau_actual_use_code'] = $request->input('pau_actual_use_code');  
                // }
                // print_r($this->data);
                // exit();
                $assId=$this->_rptassessmentlevel->addData($this->data);
                // echo $assId;
                // exit();
                $success_msg = 'Assessment Level added successfully.';
            }
            if($assId>0){
                //dd($request->all());
               $this->addAssRelation($request,$assId);
            }
             // $systemlogdata = array(); 
             // $systemlogdata['created_by'] = \Auth::user()->creatorId();
             // $systemlogdata['log_content'] = $content;
             // $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             // $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             // $this->_commonmodel->addSystemActivityLog($systemlogdata);
             if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('assessmentlevel.index')->with('success', __($success_msg));
            }
            
    	}
        return view('assessmentlevel.create',compact('data','brgyId','arrpkCode','arrClassCode','arrActualCode','arrLocalCode','arrBrgyCode','arrRevisionCode','arrSubClassCode','arrNature'));
	}
    public function addAssRelation($request,$assId){
        $minimum_unit_value = $request->input('minimum_unit_value');
        $arr = array();
        $i=0;
         // print_r($minimum_unit_value);exit;
        foreach ($minimum_unit_value as $key => $value) {
           
                $arr[$i]['assessment_id']=$assId;
                
                $arr[$i]['minimum_unit_value']=$request->input('minimum_unit_value')[$key];
                $arr[$i]['maximum_unit_value']=$request->input('maximum_unit_value')[$key];
                $arr[$i]['assessment_level']=$request->input('assessment_level')[$key];
                $arr[$i]['re_is_active']=$request->input('re_is_active')[$key];
                $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                // print_r($arr);exit;
                $check= $this->_rptassessmentlevel->checkAssRequietExit($request->input('relationId')[$key]);
                 
                if(count($check)>0){
                   
                   $this->_rptassessmentlevel->updateAssRelationData($check[0]->id,$arr[$i]);
                }
                else{
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_rptassessmentlevel->addAssRelationData($arr[$i]);
                }
                $i++;
            }
       
      
        
    }
    
    
    public function formValidation(Request $request){
        if($request->input('pau_actual_use_code')){
        $newValidation = [
             'rvy_revision_year' => 'required|unique:rpt_assessment_levels,rvy_revision_year,' .$request->input('id'). ',id,pc_class_code,' .$request->input('pc_class_code').',pau_actual_use_code,' .$request->input('pau_actual_use_code').',loc_group_brgy_no,' .$request->input('loc_group_brgy_no').',pk_code,'.$request->input('pk_code'),
             "minimum_unit_value.*"  => "required",
             "maximum_unit_value.*"  => "required|gt:minimum_unit_value.*",
             "assessment_level.*"    => "required",
        ];
        $validator = \Validator::make(
            $request->all(),  $newValidation,
            [
            'minimum_unit_value.*.required' => 'Required Field',
            'maximum_unit_value.*.required' => 'Required Field',
            'maximum_unit_value.*.gt' => 'Should be greater than Min Unit Value',
            'assessment_level.*.required' => 'Required Field'
        ]
        );
        }
        else{
         
        $validator = \Validator::make(
            $request->all(), [

                 'pk_code' => 'required|unique:rpt_assessment_levels,pk_code,' .$request->input('id'). ',id,pc_class_code,' .$request->input('pc_class_code').',rvy_revision_year,' .$request->input('rvy_revision_year'),
                 "minimum_unit_value.*"  => "required",
             "maximum_unit_value.*"  => "required|gt:minimum_unit_value.*",
             "assessment_level.*"    => "required",
              ],
              [
            'minimum_unit_value.*.required' => 'Required Field',
            'maximum_unit_value.*.required' => 'Required Field',
            'maximum_unit_value.*.gt' => 'Should be greater than Min Unit Value',
            'assessment_level.*.required' => 'Required Field'
        ]
        );

        }
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
    
    public function DeleteRelation(Request $request){
        $id = $request->input('id');
            $RptAssessmentLevel = RptAssessmentLevelsRelation::find($id);
            if($RptAssessmentLevel->created_by == \Auth::user()->creatorId()){
                $RptAssessmentLevel->delete();
            }
    }

}
