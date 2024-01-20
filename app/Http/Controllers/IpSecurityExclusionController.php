<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\IpExclusion;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class IpSecurityExclusionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $hrEmployee  = array(""=>"Please Select");
     public function __construct(){
		$this->IpExclusion= new IpExclusion(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','employee_id'=>'','remarks'=>'');  
        $this->slugs = 'ip-security-exclusion'; 
        foreach ($this->IpExclusion->getHrEmployee() as $val) {
                    $this->hrEmployee[$val->id]=$val->name;
            }
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('IpExclusion.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->IpExclusion->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['emp_name']=$row->emp_name;
            $arr[$i]['emp_email']=$row->emp_email;
            $arr[$i]['emp_position']=$row->emp_position;
            $remarks = wordwrap($row->remarks, 40, "<br />\n");
            $arr[$i]['remarks']="<div class='showLess'>".$remarks."</div>";
            $arr[$i]['emp_dept_name']=$row->emp_dept_name;
            $arr[$i]['created_by']=$row->fullname;
            $arr[$i]['updated_at']=Carbon::parse($row->updated_at)->format('d-M-Y h:i A');

            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ip-security-exclusion/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Policy Exclusion">
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
        // $is_activeinactive = $request->input('is_activeinactive');
        // $data=array('status' => $is_activeinactive);
        $this->IpExclusion->deleteById($id);
        // Log Details Start
        // $action = $is_activeinactive==1?'Restored':'Soft Deleted';
           $action = 'Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'Policy Exclusion ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->IpExclusion->getEditDetails($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       $arrHrEmployee = $this->hrEmployee;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->IpExclusion->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Policy Exclusion Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."'Updated Policy Exclusion '".$this->data['employee_id']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['updated_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $lastinsertid = $this->IpExclusion->addData($this->data);
                $success_msg = 'Policy Exclusion added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."'Added Policy Exclusion '".$this->data['employee_id']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('IpSecurityExclusion.index')->with('success', __($success_msg));
    	}
        return view('IpExclusion.create',compact('data','arrHrEmployee'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'employee_id'=>'required|unique:ip_exclusion,employee_id,'.(int)$request->input('id'),
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

    public function get_employee_details(Request $request,$id)
    {
        $data=$this->IpExclusion->getEmpDetails($id);
        return response()->json(['data' => $data]);
    }
}
