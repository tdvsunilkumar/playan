<?php

namespace App\Http\Controllers\Bplo;
use App\Models\CommonModelmaster;
use App\Http\Controllers\Controller;
use App\Models\Bplo\SectionRequirement;
use Illuminate\Http\Request;

class SectionRequirementController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $headingName='';
    public function __construct(Request $request){
        $this->_SectionRequirement = new SectionRequirement(); 
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','section_id'=>'','apptype_id'=>'','requirement_json'=>'');  
    }
    
    public function index(Request $request)
    {
        $headingName="";
        $arr = $this->_SectionRequirement->getSection($request->sid);
        if(count($arr)>0){
            $headingName=$arr[0]->section_description;
        }
        return view('Bplo.SectionRequirement.index',compact('headingName'));
    }
    public function view(Request $request)
    {
        $data = SectionRequirement::find($request->input('id'));
        $Requirement = json_decode($data->requirement_json,true);
        return view('Bplo.SectionRequirement.view', compact('Requirement'));
    }
    public function getList(Request $request){
        $sid= '';
        if($request->input('sid')>0){
            $sid = '&sid='.$request->input('sid');
        }
      
        $data=$this->_SectionRequirement->getList($request);
        $arr=array();
        $i="0";  
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;        
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;            
            $arr[$i]['id']=$row->id;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['app_type']=$row->app_type;
            $arr[$i]['requirement']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplo-section-requirements/view?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="View"  data-title="Requirements List" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';
            $arr[$i]['action']='
               
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplo-section-requirements/store?id='.$row->id.$sid).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Requirements">
                        <i class="ti-pencil text-white"></i>
                    </a>
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
        $data = (object)$this->data;
        $arrrequirement = array(); $apptypes =array();
        $arrrequirement[''] ="Select Requirement";
        foreach ($this->_SectionRequirement->requirementcode() as $val) {
           $arrrequirement[$val->id]=$val->req_code_abbreviation.'-'.$val->req_description;
        }

        $apptypes[''] ="Select Type";
        $arrSection=array();
        foreach ($this->_SectionRequirement->apptypes() as $val) {
           $apptypes[$val->id]=$val->app_type;
        }
        foreach ($this->_SectionRequirement->getSection($request->sid) as $val) {
           $arrSection[$val->id]=$val->section_description;
        }
        $arrDetails = $this->getNatureDetails();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = SectionRequirement::find($request->input('id'));
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
            if($request->input('id')>0){
                $this->data['updated_by']=\Auth::user()->creatorId();
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->_SectionRequirement->updateData($request->input('id'),$this->data);
                $bploRequirmentId=$request->input('id');
                $success_msg = 'Section Requirement updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Section Requirement, Section id = ".$this->data['section_id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $bploRequirmentId=$this->_SectionRequirement->addData($this->data);
                $success_msg = 'Section Requirement added successfully.';
                $content = "User ".\Auth::user()->name." Added Section Requirement, Section id = ".$this->data['section_id']; 
            }
            
         
            $systemlogdata = array(); 
            $systemlogdata['module_id'] =$this->data['section_id'];
            $systemlogdata['log_content'] = $content;
            $this->_commonmodel->updateLog($systemlogdata);

            session()->flash('success', $success_msg);
            return redirect()->back();
        }
        return view('Bplo.SectionRequirement.create',compact('data','arrrequirement','apptypes','arrDetails','arrSection'));
    }


     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'section_id'=>'required',
                'apptype_id' => 'required|unique:psic_section_requirements,apptype_id,'.$request->input('id').',id,section_id,'.$request->input('section_id').',apptype_id,'.$request->input('apptype_id')
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
    public function deleteSectionRequirement(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arrEndrosment = $this->_SectionRequirement->getSectionRequirement($id);
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
                    $this->_SectionRequirement->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }
}
