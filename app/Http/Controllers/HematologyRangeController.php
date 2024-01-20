<?php

namespace App\Http\Controllers;

use App\Models\HoHematologyRange;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class HematologyRangeController extends Controller
{
    
    public $data = [];
    public $arrchcCode = array(""=>"Please Select");
    public $arrchpCode = array(""=>"Please Select");
    public function __construct(){
        $this->_hematologyrange = new HoHematologyRange();
        
  $this->data = array('id'=>'','chp_id'=>'','chc_id'=>'','chr_range'=>'','hr_is_active'=>'');
       
        foreach ($this->_hematologyrange->getchcId() as $val) {
            $this->arrchcCode[$val->id]=$val->chg_category;
        }
        foreach ($this->_hematologyrange->getchpId() as $val) {
            $this->arrchpCode[$val->id]=$val->chp_parameter;
        }
    }
    
    
    public function index(Request $request)
    {
        
        return view('hemarange.index');
    }
    
    public function getList(Request $request){
        $data=$this->_hematologyrange->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->hr_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            // if($row->suffix){
            //  $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname.', '.$row->suffix;   
            //  }else{
            //    $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname;  
            //  }
              
            $arr[$i]['parameter']=$row->chp_parameter;
            $arr[$i]['category']=$row->chg_category;
            $arr[$i]['range']=$row->chr_range;
            
            $arr[$i]['hr_is_active']=($row->hr_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hemarange/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Hematology Range">
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

    
   public function rangeActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('hr_is_active' => $bt_is_activeinactive);
        $this->_hematologyrange->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrchcCode = $this->arrchcCode;
        $arrchpCode = $this->arrchpCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoHematologyRange::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['hr_is_active'] = 1;
            if($request->input('id')>0){
                $this->_hematologyrange->updateData($request->input('id'),$this->data);
                $success_msg = 'Hematology Range updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hr_is_active'] = 1;
                $this->_hematologyrange->addData($this->data);
                $success_msg = 'Hematology Range added successfully.';
            }
            return redirect()->route('hemarange.index')->with('success', __($success_msg));
        }
        return view('hemarange.create',compact('data','arrchcCode','arrchpCode'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                parameter,' .$request->input('parameter'),
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
            $HematologyRange = HoHematologyRange::find($id);
            if($HematologyRange->created_by == \Auth::user()->creatorId()){
                $HematologyRange->delete();
            }
    }

}
