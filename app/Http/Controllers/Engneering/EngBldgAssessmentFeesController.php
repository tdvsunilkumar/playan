<?php

namespace App\Http\Controllers\Engneering;

use App\Models\Engneering\EngBldgAssessmentFees;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EngBldgAssessmentFeesController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     public $arrClassCode = array(""=>"Please Select");

    public function __construct(){
        $this->_engbldgassessmentfees= new EngBldgAssessmentFees();
        
  $this->data = array('id'=>'','ebpa_id'=>'','ebaf_zoning_amount'=>'','ebaf_zoning_assessed_by'=>'','ebaf_zoning_or_no'=>'','ebaf_zoning_date_paid'=>'','ebaf_linegrade_amount'=>'','ebaf_linegrade_assessed_by'=>'','ebaf_linegrade_or_no'=>'','ebaf_linegrade_date_paid'=>'','ebaf_bldg_amount'=>'','ebaf_bldg_assessed_by'=>'','ebaf_bldg_or_no'=>'','ebaf_bldg_date_paid'=>'','ebaf_plum_amount'=>'','ebaf_plum_assessed_by'=>'','ebaf_plum_or_no'=>'','ebaf_plum_date_paid'=>'','ebaf_elec_amount'=>'','ebaf_elec_assessed_by'=>'','ebaf_elec_or_no'=>'','ebaf_elec_date_paid'=>'','ebaf_mech_amount'=>'','ebaf_mech_assessed_by'=>'','ebaf_mech_or_no'=>'','ebaf_mech_date_paid'=>'','ebaf_others_amount'=>'','ebaf_others_assessed_by'=>'','ebaf_others_or_no'=>'','ebaf_others_date_paid'=>'','ebaf_total_amount'=>'','ebaf_total_assessed_by'=>'','ebaf_total_or_no'=>'','ebaf_total_date_paid'=>'');
        
        foreach ($this->_engbldgassessmentfees->getPermit() as $val) {
            if($val->ebpa_owner_suffix_name){
              $this->arrClassCode[$val->id]=$val->ebpa_owner_first_name.' '.$val->ebpa_owner_mid_name.' '.$val->ebpa_owner_last_name.','.$val->ebpa_owner_suffix_name;  
            }
            else{
               $this->arrClassCode[$val->id]=$val->ebpa_owner_first_name.' '.$val->ebpa_owner_mid_name.' '.$val->ebpa_owner_last_name; 
            }
            
        }  

    }
    
    
    public function index(Request $request)
    {
       
        return view('Engneering.engbldgassessmentfees.index');
        
    }
    
    
    
    public function getList(Request $request){
        $data=$this->_engbldgassessmentfees->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->ebaf_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            if($row->ebpa_owner_suffix_name){
              $arr[$i]['ebpa_owner_first_name']=$row->ebpa_owner_first_name.' '.$row->ebpa_owner_mid_name.' '.$row->ebpa_owner_last_name.','.$row->ebpa_owner_suffix_name;  
              }else{
                $arr[$i]['ebpa_owner_first_name']=$row->ebpa_owner_first_name.' '.$row->ebpa_owner_mid_name.' '.$row->ebpa_owner_last_name;
              }
            $arr[$i]['ebaf_total_amount']='₱ '.(number_format($row->ebaf_total_amount,3));
            $arr[$i]['ebaf_total_assessed_by']=$row->ebaf_total_assessed_by;
            $arr[$i]['ebaf_total_or_no']=$row->ebaf_total_or_no;
            $arr[$i]['ebaf_total_date_paid']=$row->ebaf_total_date_paid;
           
         
            $arr[$i]['is_active']=($row->ebaf_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgassessmentfees/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Engneering Module">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                     </div>'  
                ;
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
        $data=array('ebaf_is_active' => $is_activeinactive);
        $this->_engbldgassessmentfees->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = $this->arrClassCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngBldgAssessmentFees::find($request->input('id'));
            
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engbldgassessmentfees->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Assessment Fees updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebaf_is_active'] = 1;
               
                $this->_engbldgassessmentfees->addData($this->data);
                $success_msg = 'Engineering Assessment Fees added successfully.';
            }
            return redirect()->route('engbldgassessmentfees.index')->with('success', __($success_msg));
        }
        return view('Engneering.engbldgassessmentfees.create',compact('data','arrClassCode'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                

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
            $EngBldgAssessmentFees = EngBldgAssessmentFees::find($id);
            if($EngBldgAssessmentFees->created_by == \Auth::user()->creatorId()){
                $EngBldgAssessmentFees->delete();
            }
    }
}
