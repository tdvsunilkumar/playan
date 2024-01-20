<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\IpRegistration;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class IpSecurityManageController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->IpRegistration= new IpRegistration(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ip_address'=>'','local_name'=>'','remarks'=>'');  
        $this->slugs = 'ip-security-manage'; 
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        $ip_settings=$this->IpRegistration->getIpSettingStatus();
        if($ip_settings){
            $ip_settings_status = $ip_settings->value;
        }else{
            $ip_settings_status = 0;
        }
        return view('IpRegistration.index',compact('ip_settings_status'));

    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->IpRegistration->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ip_address']=$row->ip_address;
            $arr[$i]['local_name']=$row->local_name;
            $remarks = wordwrap($row->remarks, 40, "<br />\n");
            $arr[$i]['remarks']="<div class='showLess'>".$remarks."</div>";
            $arr[$i]['created_by']=$row->fullname;
            $arr[$i]['created_at']= Carbon::parse($row->created_at)->format('d-M-Y h:i A');
            $arr[$i]['updated_at']=Carbon::parse($row->updated_at)->format('d-M-Y h:i A');

            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ip-security-manage/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit IP Security">
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
        $data=array('status' => $is_activeinactive);
        $this->IpRegistration->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'Ip Security ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->IpRegistration->getEditDetails($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->IpRegistration->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Ip Security Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."'Updated Ip Security '".$this->data['local_name']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['updated_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $lastinsertid = $this->IpRegistration->addData($this->data);
                $success_msg = 'Ip Security added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."'Added Ip Security '".$this->data['local_name']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('IpSecurityManage.index')->with('success', __($success_msg));
    	}
        return view('IpRegistration.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'ip_address'=>'required|unique:ip_registration,ip_address,'.(int)$request->input('id'),
                'local_name' => 'required'
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

    public function get_current_ip_address(Request $request)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return response()->json(['ip_address' => $ip]);
    }
    public function updateIpSettings(Request $request){
       return $this->IpRegistration->updateIpSettings($request);
    }
   
}
