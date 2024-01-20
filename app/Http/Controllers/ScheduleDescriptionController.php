<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ScheduleDescription;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ScheduleDescriptionController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
		$this->_scheduledescription = new ScheduleDescription(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','sd_mode'=>'','sd_description'=>'','sd_description_short'=>'');  
        $this->slugs = 'real-property/schedule-description';    
    }
    
    public function index(Request $request)
    {
       $this->is_permitted($this->slugs, 'read');
       return view('scheduledescription.index');
    
    }

    public function getList(Request $request){

        $data=$this->_scheduledescription->getList($request);
        $arr=array();
        $i="0";    
        $j=$request->input('start')+1; 
        foreach ($data['data'] as $row){
            
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['sd_mode']=$row->sd_mode;
            $arr[$i]['sd_description']=$row->sd_description;
            $arr[$i]['sd_description_short']=$row->sd_description_short;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
                                 
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/scheduledescription/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Schedule Description">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                </div>
                    '.$status.'
                </div>'  
             
              ;
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
            $i++;
            $j++;
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
        $this->_scheduledescription->updateActiveInactive($id,$data);
}
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_scheduledescription->editScheduleDescription($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_scheduledescription->updateData($request->input('id'),$this->data);
                $success_msg = 'Schedule Description updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_scheduledescription->addData($this->data);
                $success_msg = 'Schedule Description added successfully.';
            }
            return redirect()->route('scheduledescription.index')->with('success', __($success_msg));
    	}
        return view('scheduledescription.create',compact('data'));
	}
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'sd_mode'=>'required|unique:schedule_descriptions,sd_mode,'.(int)$request->input('id'),
                'sd_description'=>'required|unique:schedule_descriptions,sd_description,'.(int)$request->input('id'),
                'sd_description_short'=>'required|unique:schedule_descriptions,sd_description_short,'.(int)$request->input('id'),

            ],[
                'sd_mode.required' => 'Required Field',
                'sd_mode.unique'   => 'Already Exists',
                'sd_description.required' => 'Required Field',
                'sd_description.unique'   => 'Already Exists',
                'sd_description_short.required' => 'Required Field',
                'sd_description_short.unique'   => 'Already Exists',
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
            $scheduledescription = ScheduleDescription::find($id);
            if($scheduledescription->created_by == \Auth::user()->creatorId()){
                $scheduledescription->delete();
            }
    }
   

}
