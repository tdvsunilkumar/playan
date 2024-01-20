<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrPhilHealth;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrPhilHealthController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_hrphilhealth= new HrPhilHealth(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrpt_description'=>'','hrpt_amount_from'=>'','hrpt_amount_to'=>'','hrpt_personal_share'=>'','hrpt_gov_share'=>'','hrpt_personal_type'=>'','hrpt_gov_type'=>'');  
        $this->slugs = 'hr-philhealth'; 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.philhealth.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrphilhealth->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrpt_description']=$row->hrpt_description;
            $arr[$i]['hrpt_amount_from']=currency_format($row->hrpt_amount_from);
            $arr[$i]['hrpt_amount_to']=currency_format($row->hrpt_amount_to);
            $arr[$i]['hrpt_personal']=$row->hrpt_personal_type === 1 ? 'PHP '.$row->hrpt_personal_share : $row->hrpt_personal_share . '%';
            $arr[$i]['hrpt_gov']=$row->hrpt_gov_type === 1 ? 'PHP '.$row->hrpt_gov_share : $row->hrpt_gov_share . '%';
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-philhealth/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Occupation Level">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_hrphilhealth->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrPhilHealth::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrphilhealth->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Phil Health Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Phil Health '".$this->data['hrpt_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrphilhealth->addData($this->data);
                $success_msg = 'Phil Health added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Phil Health '".$this->data['hrpt_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrphilhealth.index')->with('success', __($success_msg));
    	}
        return view('HR.philhealth.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrpt_description'=>'required|unique:hr_phil_healths,hrpt_description,'.(int)$request->input('id'),
                'hrpt_amount_from'=>'required',
                'hrpt_amount_to'=>'required',
                'hrpt_personal_share'=>'required',
                'hrpt_gov_share'=>'required'
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
}
