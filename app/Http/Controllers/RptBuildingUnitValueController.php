<?php

namespace App\Http\Controllers;

use App\Models\RptBuildingUnitValue;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

class RptBuildingUnitValueController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     public $arrLocalCode = array();
     public $arrBrgyCode = array();
     public $arrKindCode = array(""=>"Please Select");
     public $arrBulidingTypeCode = array(""=>"Please Select");
     public $arrRevisionCode = array();
     private $slugs;
     private $slugs2;

    

    public function __construct(){
        $this->_rptbuildingunitvalue = new RptBuildingUnitValue();
        
        $this->data = array('id'=>'','mun_no'=>'','loc_group_brgy_no'=>'','bk_building_kind_code'=>'','bt_building_type_code'=>'','rvy_revision_year'=>'','buv_minimum_unit_value'=>'0.000','buv_maximum_unit_value'=>'0.000','buv_revision_year'=>'');
        
        foreach ($this->_rptbuildingunitvalue->getKind() as $val) {
            $this->arrKindCode[$val->id]=$val->bk_building_kind_code.'-'.$val->bk_building_kind_desc;
        } 
        foreach ($this->_rptbuildingunitvalue->getBulidingType() as $val) {
            $this->arrBulidingTypeCode[$val->id]=$val->bt_building_type_code.'-'.$val->bt_building_type_desc;
        }
        foreach ($this->_rptbuildingunitvalue->getLocal() as $val) {
            $this->arrLocalCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
        }
        foreach ($this->_rptbuildingunitvalue->getBrgy() as $val) {
            $this->arrBrgyCode[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
        foreach ($this->_rptbuildingunitvalue->getRevisionDefult() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        foreach ($this->_rptbuildingunitvalue->getRevision() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        $this->slugs = 'real-property/unit-value/building';
        $this->slugs2="real-property/property-data/generalrevision";
        
    }

    public function getsubclass(Request $request){
       $getgroups = $this->_rptbuildingunitvalue->getsubclass($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->ps_subclass_desc.'</option>';
      }
      echo $htmloption;
    }  
    public function index(Request $request)
    {   
        $read1 = $this->is_permitted($this->slugs, 'read', 1);
        $read2 = $this->is_permitted($this->slugs2, 'read', 1);
        if (!($read1 > 0) && !($read2 > 0)){
            return abort(401);
        }
        $revisionyear = array(""=>"Select year");
        // Changes By Alli
        foreach ($this->_rptbuildingunitvalue->getRevisionActive() as $key => $value) {
            $revisionyear[$value->id] = $value->rvy_revision_year."-".$value->rvy_revision_code;
         }

         $selected_revision = $this->_rptbuildingunitvalue->getOneRevisionActiveDefault();
         // 
        return view('rptbuildingunitvalue.index',compact('revisionyear', 'selected_revision')); 
       
    }
     public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 3);
    }
    public function getList(Request $request){
        $data=$this->_rptbuildingunitvalue->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;

             $status2 =($row->buv_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm ti-trash " style="background: #8d8a8a;color:#fff;"></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center  ti-reload text-white"  style="background: #8d8a8a"></a>'; 

            $status =($row->buv_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['bt_building_type_code']=$row->bt_building_type_code.'-'.$row->bt_building_type_desc;
            $arr[$i]['pk_code']=$row->bk_building_kind_code.'-'.$row->bk_building_kind_desc;
            $arr[$i]['rvy_revision_year']=$row->rvy_revision_year.'-'.$row->rvy_revision_code;
            $arr[$i]['buv_minimum_unit_value']=$this->money_format($row->buv_minimum_unit_value);
            $arr[$i]['buv_maximum_unit_value']=$this->money_format($row->buv_maximum_unit_value);
            if($row->is_approve =='1'){
                $approve = '<div class="action-btn  bg-success ms-2"><a href="#" class="mx-3 btn btn-sm approveunapprove ti ti-lock-open text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';
           }else{ $approve = '<div class="action-btn  bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm unapproveunapprove ti ti-lock-off text-white text-white" name="stp_print" value="1" id='.$row->id.'></a></div>'; }
           $arr[$i]['isapprove']=($row->buv_is_active==1)?$approve:'';
            $arr[$i]['is_active']=($row->buv_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');


            if($row->is_approve =='1'){
                 $approve = '<div class="action-btn  bg-success ms-2"><a href="#" class="mx-3 btn btn-sm approveunapprove ti ti-lock-open text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';
            }else{ $approve = '<div class="action-btn  bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm unapproveunapprove ti ti-lock-off text-white text-white" name="stp_print" value="1" id='.$row->id.'></a></div>'; }
            $arr[$i]['isapprove']=($row->buv_is_active==1)?$approve:'';
             if($row->is_approve =='0'){

            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center"  data-url="'.url('/rptbuildingunitvalue/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Building Unit Value">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'

                    </div>';
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
    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptbuildingunitvalue->updateActiveInactive($id,$data);
    }
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('buv_is_active' => $is_activeinactive);
        $this->_rptbuildingunitvalue->updateActiveInactive($id,$data);

    }

    
    
    
    public function store(Request $request){
        $brgyId=$this->_rptbuildingunitvalue->getBrgyId();
        if($brgyId != null)
        {
            $this->data['loc_group_brgy_no'] = $brgyId;
        }
        $data = (object)$this->data;
        $arrKindCode = [];
        $arrBulidingTypeCode = [];
        $arrRevisionCode = $this->arrRevisionCode;
        $arrLocalCode = $this->arrLocalCode;
        $arrBrgyCode = $this->arrBrgyCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptBuildingUnitValue::where('rpt_building_unit_values.id',$request->input('id'))
                                       ->leftJoin('rpt_building_kinds','rpt_building_kinds.id','=','rpt_building_unit_values.bk_building_kind_code')
                                       ->leftJoin('rpt_building_types','rpt_building_types.id','=','rpt_building_unit_values.bt_building_type_code')
                                       ->select('rpt_building_unit_values.*',DB::raw('CONCAT(rpt_building_kinds.bk_building_kind_code,"-",rpt_building_kinds.bk_building_kind_desc) as kindText'),DB::raw('CONCAT(rpt_building_types.bt_building_type_code,"-",rpt_building_types.bt_building_type_desc) as text'))
                                       ->first();
                                       //dd($data);
        }
        if($request->isMethod('post')){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $access1 = $this->is_permitted($this->slugs, 'update', 1);
                $access2 = $this->is_permitted($this->slugs2, 'update', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->_rptbuildingunitvalue->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Building Unit Value updated successfully.';
            }else{
                $access1 = $this->is_permitted($this->slugs, 'create', 1);
                $access2 = $this->is_permitted($this->slugs2, 'create', 1);
                if (!($access1 > 0) && !($access2 > 0)){
                    return abort(401);
                }
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['buv_is_active'] = 1;
               
                $this->_rptbuildingunitvalue->addData($this->data);
                $success_msg = 'Rpt Building Unit Value added successfully.';
            }

            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('rptbuildingunitvalue.index')->with('success', __($success_msg));
            }
            
        }
        return view('rptbuildingunitvalue.create',compact('data','brgyId','arrKindCode','arrBulidingTypeCode','arrRevisionCode','arrLocalCode','arrBrgyCode'));
        
    }


    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bk_building_kind_code'=>'required',
                'bt_building_type_code'=>'required',
                'rvy_revision_year'=>'required',
                'buv_minimum_unit_value' => 'required|numeric|min:0.001',
                'buv_maximum_unit_value' => 'required|numeric|min:0.001|gte:buv_minimum_unit_value',
              ],[
                'bk_building_kind_code.required' => 'Required Field',
                'bt_building_type_code.required' => 'Required Field',
                'rvy_revision_year.required' => 'Required Field',
                'buv_minimum_unit_value.required' => 'Required Field',
                'buv_minimum_unit_value.numeric' => 'Only Numeric Allowed',
                'buv_minimum_unit_value.min' => 'Min value should be greater than 0',

                'buv_maximum_unit_value.required' => 'Required Field',
                'buv_maximum_unit_value.numeric' => 'Only Numeric Allowed',
                'buv_maximum_unit_value.min' => 'Min value should be greater than 0',
                'buv_maximum_unit_value.gte' => 'Should be equal or greater than min value',
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
            $RptBuildingUnitValue = RptBuildingUnitValue::find($id);
            if($RptBuildingUnitValue->created_by == \Auth::user()->creatorId()){
                $RptBuildingUnitValue->delete();
            }
    }

    public function kindAjaxRequest(Request $request){
         $data = $this->_rptbuildingunitvalue->kindAjaxRequest($request);
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

    public function typeAjaxRequest(Request $request){
         $data = $this->_rptbuildingunitvalue->typeAjaxRequest($request);
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
