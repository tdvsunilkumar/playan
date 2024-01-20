<?php

namespace App\Http\Controllers;

use App\Models\CheckTypeMaster;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class CheckTypeMasterController extends Controller
{
   
     public $data = [];
  
  
   
    public function __construct(){
        $this->_checktypemaster = new CheckTypeMaster();
        
  $this->data = array('id'=>'','ctm_code'=>'','ctm_description'=>'','ctm_short_name'=>'');
        
        
    }
    public function index(Request $request)
    {
        return view('checktypemaster.index');
    }

    
    public function getList(Request $request){
        $data=$this->_checktypemaster->getList($request);
        $arr=array();
        $i="0";    
       
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['ctm_code']=$row->ctm_code;
            $arr[$i]['ctm_description']=$row->ctm_description;
            $arr[$i]['ctm_short_name']=$row->ctm_short_name;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/payment-system/side-menu/check-type-master-file/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Check Type Master">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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
        $data=array('is_active' => $is_activeinactive);
        $this->_checktypemaster->updateActiveInactive($id,$data);
}
    
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CheckTypeMaster::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_checktypemaster->updateData($request->input('id'),$this->data);
                $success_msg = 'Check Type Master updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_checktypemaster->addData($this->data);
                $success_msg = 'Check Type Master added successfully.';
            }
            return redirect()->route('checktypemaster.index')->with('success', __($success_msg));
        }
        return view('checktypemaster.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ctm_code'=>'required',
                'ctm_description'=>'required',
               
                
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
            $CheckTypeMaster = CheckTypeMaster::find($id);
            if($CheckTypeMaster->created_by == \Auth::user()->creatorId()){
                $CheckTypeMaster->delete();
            }
    }
}
