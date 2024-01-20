<?php

namespace App\Http\Controllers;

use App\Models\HoHematologyParameters;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class HematologyParametersController extends Controller
{
    
    public $data = [];
    public function __construct(){
        $this->_hematologyparameter = new HoHematologyParameters();
        $this->data = array('id'=>'','chp_parameter'=>'');
       
        // foreach ($this->_hematologycategory->getHrEmployeeCode() as $val) {
        //     if($val->suffix){
        //       $this->arrHrEmpCode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;   
        //     }
        //     else{
        //       $this->arrHrEmpCode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname;
        //     }
           
        // }     
    }
    
    
    public function index(Request $request)
    {
        
        return view('hemaparameter.index');
        
    }
    public function getList(Request $request){
        $data=$this->_hematologyparameter->getList($request);
        
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->hp_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            // if($row->suffix){
            //  $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname.', '.$row->suffix;   
            //  }else{
            //    $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname;  
            //  }
              
            $arr[$i]['chp_parameter']=$row->chp_parameter;
            $arr[$i]['hp_is_active']=($row->hp_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hemaparameter/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Hematology Parameters">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               </div>
                    '.$status.'
                </div>
                ';
                //  <div class="action-btn bg-danger ms-2">
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

    
   public function parametersActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('hp_is_active' => $bt_is_activeinactive);
        $this->_hematologyparameter->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        // $arrHrEmpCode = $this->arrHrEmpCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoHematologyParameters::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hematologyparameter->updateData($request->input('id'),$this->data);
                $success_msg = 'Hematology Parameter updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hp_is_active'] = 1;
                $this->_hematologyparameter->addData($this->data);
                $success_msg = 'Hematology Parameter added successfully.';
            }
            return redirect()->route('hemaparameter.index')->with('success', __($success_msg));
        }
        return view('hemaparameter.create',compact('data'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                chp_parameter,' .$request->input('chp_parameter'),
                // 'ra_appraiser_id'=>'required|unique:rpt_appraisers,ra_appraiser_id,'.$request->input('id'),
                // 'ra_appraiser_position'=>'required'
                

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
            $HematologyParameter = HoHematologyParameter::find($id);
            if($HematologyParameter->created_by == \Auth::user()->creatorId()){
                $HematologyParameter->delete();
            }
    }

}