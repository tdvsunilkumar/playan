<?php

namespace App\Http\Controllers;

use App\Models\RptPlantTressUnitValue;
use App\Models\CommonModelmaster;
use App\Models\RptBuildingUnitValue;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

class RptPlantTressUnitValueController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     public $arrLocalCode = array();
     public $arrBrgyCode = array();
     public $arrPlantTressCode = array(""=>"Please Select");
     public $arrClassCode = array(""=>"Please Select");
     public $arrSubclassCode = array(""=>"Please Select");
     public $arrRevisionCode = array();
     private $slugs;
     private $slugs2;



    public function __construct(){
        $this->_rptplanttressunitvalue = new RptPlantTressUnitValue();
        $this->_rptbuildingunitvalue = new RptBuildingUnitValue();
        
  $this->data = array('id'=>'','mun_no'=>'','loc_group_brgy_no'=>'','pt_ptrees_code'=>'','rvy_revision_year'=>'','pc_class_code'=>'','ps_subclass_code'=>'','ptuv_unit_value'=>'');
        
        foreach ($this->_rptplanttressunitvalue->getPlantTressCode() as $val) {
            $this->arrPlantTressCode[$val->id]=$val->pt_ptrees_code.'-'.$val->pt_ptrees_description;
        } 
        foreach ($this->_rptplanttressunitvalue->getRevisionDefult() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        foreach ($this->_rptplanttressunitvalue->getYear() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        foreach ($this->_rptplanttressunitvalue->getRptClass() as $val) {
            $this->arrClassCode[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
        } 
        foreach ($this->_rptplanttressunitvalue->getRptSubClass() as $val) {
            $this->arrSubclassCode[$val->id]='['.$val->pc_class_code.'-'.$val->pc_class_description.' => '.$val->ps_subclass_code.'-'.$val->ps_subclass_desc.']';
        }
        foreach ($this->_rptplanttressunitvalue->getLocal() as $val) {
            $this->arrLocalCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
        }
        foreach ($this->_rptplanttressunitvalue->getBrgy() as $val) {
            $this->arrBrgyCode[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        } 
        $this->slugs = 'real-property/unit-value/plant-trees';
        $this->slugs2="real-property/property-data/generalrevision";


    }
    public function getsubclass(Request $request){
       $getgroups = $this->_rptplanttressunitvalue->getsubclass($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->ps_subclass_desc.'</option>';
      }
      echo $htmloption;
    } 
    
    public function getPlantTreesSubClassDetailss(Request $request){
        $id= $request->input('id');
        $data = $this->_rptplanttressunitvalue->getSubClassDetailss($id);
        echo json_encode($data);
    }  
      
      
    public function index(Request $request)
    {   
        $read1 = $this->is_permitted($this->slugs, 'read', 1);
        $read2 = $this->is_permitted($this->slugs2, 'read', 1);
        if (!($read1 > 0) && !($read2 > 0)){
            return abort(401);
        }
        $revisionyear = array(""=>"Select year");
        // Changes By Ali
        foreach ($this->_rptbuildingunitvalue->getRevisionActive() as $key => $value) {
            $revisionyear[$value->id] = $value->rvy_revision_year."-".$value->rvy_revision_code;
         }

         $selected_revision = $this->_rptbuildingunitvalue->getOneRevisionActiveDefault();
         return view('rptplanttressunitvalue.index',compact('revisionyear', 'selected_revision'));

    }
    
     public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 3);
    }
    
    public function getList(Request $request){
        $data=$this->_rptplanttressunitvalue->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status2 =($row->ptuv_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm ti-trash " style="background: #8d8a8a;color:#fff;" ></a>': 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center  ti-reload text-white"  style="background: #8d8a8a"></a>';

            $status =($row->ptuv_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['pt_ptrees_code']=$row->pt_ptrees_code.'-'.$row->pt_ptrees_description;
            $arr[$i]['pc_class_code']=$row->pc_class_code.'-'.$row->pc_class_description;
            $arr[$i]['ps_subclass_code']=$row->ps_subclass_code.'-'.$row->ps_subclass_desc;
            $arr[$i]['rvy_revision_year']=$row->rvy_revision_year.'-'.$row->rvy_revision_code;
            $arr[$i]['ptuv_unit_value']=$this->money_format($row->ptuv_unit_value);            
            if($row->is_approve =='1'){
                $approve = '<div class="action-btn  bg-success ms-2"><a href="#" class="mx-3 btn btn-sm approveunapprove ti ti-lock-open text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';
           }else{ $approve = '<div class="action-btn  bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm unapproveunapprove ti ti-lock-off text-white text-white" name="stp_print" value="1" id='.$row->id.'></a></div>'; }
           $arr[$i]['isapprove']=($row->ptuv_is_active==1)?$approve:'';
            $arr[$i]['is_active']=($row->ptuv_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            if($row->is_approve =='0'){
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptplanttressunitvalue/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Plant|Trees Unit Value">
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
        $data=array('ptuv_is_active' => $is_activeinactive);
        $this->_rptplanttressunitvalue->updateActiveInactive($id,$data);
    }

    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptplanttressunitvalue->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $brgyId=$this->_rptplanttressunitvalue->getBrgyId();
        if($brgyId != null)
        {
            $this->data['loc_group_brgy_no'] = $brgyId;
        }
        $data = (object)$this->data;
        $arrPlantTressCode = [];
        $arrClassCode = $this->arrClassCode;
        $arrSubclassCode = [];
        $arrRevisionCode = $this->arrRevisionCode;
        $arrLocalCode = $this->arrLocalCode;
        $arrBrgyCode = $this->arrBrgyCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPlantTressUnitValue::where('rpt_plant_tress_unit_values.id',$request->input('id'))
                                          ->join('rpt_property_classes','rpt_property_classes.id','=','rpt_plant_tress_unit_values.pc_class_code')
                                          ->join('rpt_property_subclassifications','rpt_property_subclassifications.id','=','rpt_plant_tress_unit_values.ps_subclass_code')
                                          ->join('rpt_plant_tress','rpt_plant_tress.id','=','rpt_plant_tress_unit_values.pt_ptrees_code')
                                          ->select('rpt_plant_tress_unit_values.*',DB::raw('CONCAT(rpt_plant_tress.pt_ptrees_code,"-",rpt_plant_tress.pt_ptrees_description) as plantText'),DB::raw('CONCAT("[",rpt_property_classes.pc_class_code,"-",rpt_property_classes.pc_class_description,"=>",rpt_property_subclassifications.ps_subclass_code,"-",rpt_property_subclassifications.ps_subclass_desc,"]") as text'))
                                          ->first();
          //  dd($data);
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
                $this->_rptplanttressunitvalue->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Plant Trees Unit Value updated successfully.';
            }else{
                $access1 = $this->is_permitted($this->slugs, 'create', 1);
                $access2 = $this->is_permitted($this->slugs2, 'create', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ptuv_is_active'] = 1;
               
                $this->_rptplanttressunitvalue->addData($this->data);
                $success_msg = 'Rpt Plant Trees Unit Value added successfully.';
            }
            
            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('rptplanttressunitvalue.index')->with('success', __($success_msg));
            }
        }
        return view('rptplanttressunitvalue.create',compact('data','brgyId','arrPlantTressCode','arrClassCode','arrRevisionCode','arrSubclassCode','arrLocalCode','arrBrgyCode'));
    }
    

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'pt_ptrees_code'=>'required',
                'ps_subclass_code'=>'required',
                'pc_class_code'=>'required',
                'ptuv_unit_value'=>'required',
                'rvy_revision_year' => 'required|unique:rpt_plant_tress_unit_values,rvy_revision_year,' .$request->input('id'). ',id,loc_group_brgy_no,' .$request->input('loc_group_brgy_no').',pc_class_code,' .$request->input('pc_class_code').',ps_subclass_code,' .$request->input('ps_subclass_code').',pt_ptrees_code,' .$request->input('pt_ptrees_code'),

              ],[
                'pt_ptrees_code.required' => 'Required Field',
                'ps_subclass_code.required' => 'Required Field',
                'pc_class_code.required' => 'Required Field',
                'ptuv_unit_value.required' => 'Required Field',
                'rvy_revision_year.required' => 'Required Field',
                'rvy_revision_year.unique' => 'Already Exists for this Revision Year',
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
            $RptPlantTressUnitValue = RptPlantTressUnitValue::find($id);
            if($RptPlantTressUnitValue->created_by == \Auth::user()->creatorId()){
                $RptPlantTressUnitValue->delete();
            }
    }

    public function plantTreeAjaxRequest(Request $request){
         $data = $this->_rptplanttressunitvalue->plantTreeAjaxRequest($request);
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


    public function classSubAjaxRequest(Request $request){
         $data = $this->_rptplanttressunitvalue->classSubAjaxRequest($request);
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
}
