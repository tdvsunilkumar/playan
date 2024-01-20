<?php

namespace App\Http\Controllers;

use App\Models\HoHematologyCategory;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class HematologyCategoryController extends Controller
{
    
    public $data = [];
    public function __construct(){
        $this->_hematologycategory = new HoHematologyCategory();
        
  $this->data = array('id'=>'','chg_category'=>'');
       
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
        
        return view('hemacategory.index');
        
    }
    
    

    // public function getEmployeeDetails(Request $request){
    //     $id= $request->input('id');
    //     $data = $this->_rptappraisers->getEmployeeDetails($id);
    //     echo json_encode($data);
    // }
    
    public function getList(Request $request){
        $data=$this->_hematologycategory->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->cc_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            // if($row->suffix){
            //  $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname.', '.$row->suffix;   
            //  }else{
            //    $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname;  
            //  }
              
            $arr[$i]['chg_category']=$row->chg_category;
            
            $arr[$i]['cc_is_active']=($row->cc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hemacategory/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Hematology Category">
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

    
   public function categoryActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('cc_is_active' => $bt_is_activeinactive);
        $this->_hematologycategory->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        // $arrHrEmpCode = $this->arrHrEmpCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoHematologyCategory::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['cc_is_active'] = 1;
            if($request->input('id')>0){
                $this->_hematologycategory->updateData($request->input('id'),$this->data);
                $success_msg = 'Hematology Category updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['cc_is_active'] = 1;
                $this->_hematologycategory->addData($this->data);
                $success_msg = 'Hematology Category added successfully.';
            }
            return redirect()->route('hemacategory.index')->with('success', __($success_msg));
        }
        return view('hemacategory.create',compact('data'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                chg_category,' .$request->input('chg_category'),
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
            $HematologyCategory = HoHematologyCategory::find($id);
            if($HematologyCategory->created_by == \Auth::user()->creatorId()){
                $HematologyCategory->delete();
            }
    }

}
