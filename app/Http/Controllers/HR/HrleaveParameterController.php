<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrleaveParameter;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\HR\LeaveAdjustment;
use App\Models\HR\HrleaveParameterDetail;

class HrleaveParameterController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
		$this->_leaveadjustment= new LeaveAdjustment(); 
        $this->_HrleaveParameter = new HrleaveParameter(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrlp_description'=>'');  
        $this->slugs = 'formula';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('HR.hrleaveparameter.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_HrleaveParameter->getList($request);
		$arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/HrleaveParameter/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Hr leave parameter">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->hrlp_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrlp_description']=$row->hrlp_description;
			$arr[$i]['hrlp_is_active']=($row->hrlp_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('hrlp_is_active' => $is_activeinactive);
        $this->_HrleaveParameter->updateActiveInactive($id,$data);

    
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

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_HrleaveParameter->find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_HrleaveParameter->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Hr leave parameter '".$this->data['hrlp_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hrlp_is_active'] = 1;
                $request->id = $this->_HrleaveParameter->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Hr leave parameter '".$this->data['hrlp_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_HrleaveParameter->addParams($request);
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('leaveparameter.index')->with('success', __($success_msg));
        }
        $arrLeaveType = array(""=>"Select Leave type");
        foreach ($this->_leaveadjustment->getLeaveType() as $val) {
                $arrLeaveType[$val->id]=$val->hrlt_leave_code."-".$val->hrlt_leave_type;
        }
        $arrHrAccrualType = config('constants.arrHrAccrualType');
        return view('HR.hrleaveparameter.create',compact('data','arrLeaveType','arrHrAccrualType'));
    }
    
    
    public function formValidation(Request $request){
        $rule = [
            'hrlp_description'=>'required|unique:hr_leave_parameter,hrlp_description,'.(int)$request->input('id'),
        ];
        if ($request->id) {
            $rule = array_merge($rule,[
                'hrlp_description'=>'required'
            ]);
        }
        $validator = \Validator::make(
            $request->all(), $rule
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

    public function removeParams(Request $request){
        $user = HrleaveParameterDetail::find($request->id);
        $user->hrlpc_is_active = $request->hrlpc_is_active;
        $user->save();
        echo json_encode('success');
    }
    
}
