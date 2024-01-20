<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrleaveParameterDetail;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class HrleaveParameterDetailController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $hrleavetype = array(""=>"Please Select");
     public function __construct(){
        $this->_HrleaveParameterDetail = new HrleaveParameterDetail(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrlp_id'=>'','hrlt_id'=>'','hrlpc_days'=>'','hrat_id'=>'','hrlpc_credits'=>'');  
        $this->slugs = 'formula';
		foreach ($this->_HrleaveParameterDetail->HrleaveType() as $val) {
             $this->hrleavetype[$val->id]=$val->hrlt_leave_type;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('HR.hrleaveparameterDetail.index');
    }


    public function getList(Request $request){
		
        $this->is_permitted($this->slugs, 'read');
		$Arrhrleavetype  = $this->hrleavetype;
        $data=$this->_HrleaveParameterDetail->getList($request);
		
		$arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/HrleaveParameterDetail/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Hr Leave Parameter Detail">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->hrlpc_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['hrlt_id']=$row->hrlt_id;
			$arr[$i]['hrlpc_days']=$row->hrlpc_days;
			$arr[$i]['hrat_id']=$row->hrat_id;
			$arr[$i]['hrlpc_credits']=$row->hrlpc_credits;
			$arr[$i]['hrlpc_is_active']=($row->hrlpc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
           
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
        $data=array('hrlpc_is_active' => $is_activeinactive);
        $this->_HrleaveParameterDetail->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Hr leave parameter ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$Arrhrleavetype  = $this->hrleavetype;
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_HrleaveParameterDetail->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_HrleaveParameterDetail->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hrlpc_is_active'] = 1;
                $request->id = $this->_HrleaveParameterDetail->addData($this->data);
                $success_msg = 'Added successfully.';
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('leaveparameterdetail.index')->with('success', __($success_msg));
        }
        return view('HR.hrleaveparameterDetail.create',compact('data','Arrhrleavetype'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                //'hrlp_description'=>'required|unique:hr_leave_parameter_detail,hrlp_description,'.(int)$request->input('id'),
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
