<?php

namespace App\Http\Controllers;

use App\Models\RegCauseOfDeath;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class EcoCauseOfDeathController extends Controller
{
 public $data = [];
 public $barangay = array(""=>"Select Barangay");

 public function __construct(){
 $this->_ecocauseofdeath = new RegCauseOfDeath();
        
  $this->data = array('id'=>'','cause_of_death'=>'','remarks'=>'','status'=>'');
            
    }
    
    public function index(Request $request)
    {
      return view('ecocauseofdeath.index');
        
    }

    public function getList(Request $request){
        $data=$this->_ecocauseofdeath->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;        
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['cause_of_death']=$row->cause_of_death;
            $arr[$i]['remark']=$row->remarks;
            $arr[$i]['is_active']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ecocauseofdeath/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Cause Of Death">
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
        $data=array('status' => $is_activeinactive);
        $this->_ecocauseofdeath->updateActiveInactive($id,$data);
    }
    public function store(Request $request){
        $data = (object)$this->data;
      
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RegCauseOfDeath::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['status']);
                $this->_ecocauseofdeath->updateData($request->input('id'),$this->data);
                $success_msg = 'Cause Of Death updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
               
                $this->_ecocauseofdeath->addData($this->data);
                $success_msg = 'Cause Of Death added successfully.';
            }
            return redirect('burial-cause-of-death')->with('success', __($success_msg));
        }
        return view('ecocauseofdeath.create',compact('data'));
        
    }


    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cause_of_death'=>'required', 
            ],[
				'cause_of_death.required' => 'The Cause of death field is required.',
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
            $Country = RegCauseOfDeath::find($id);
            if($Country->created_by == \Auth::user()->creatorId()){
                $Country->delete();
            }
    }
}
