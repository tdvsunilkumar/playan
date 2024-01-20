<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\LeaveAdjustment;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class LeaveAdjustmentController extends Controller
{
    public $data = [];
     public $postdata = [];
    
     public function __construct(Carbon $carbon){
		$this->_leaveadjustment= new LeaveAdjustment(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hrlp_id'=>'','hrlea_date_effective'=>'');  
        $this->slugs = 'hr-offset'; 
        foreach ($this->_leaveadjustment->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.leaveadjustment.index');
    }

    public function empleaves(Request $request)
    {
        $this->is_permitted('my-leaves', 'read');
        return view('HR.leaveadjustment.employee');
    }
    public function empleaveslist(Request $request){
        $this->is_permitted('my-leaves', 'read');
        $data=$this->_leaveadjustment->getLeaveList($request);
        $arr=array();
        $i="0"; 
        // $sr_no=(int)$request->input('start')-1; 
        // $sr_no=$sr_no>0? $sr_no+1:0;   
        foreach ($data['data'] as $row){
            // $status =($row->hrlea_status > 0) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>' : 
            // '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
            // $arr[$i]['srno']=$sr_no;
            $arr[$i]['leave_type']=$row->leave_type;
            $arr[$i]['days']=$row->hrlpc_days; 
            $arr[$i]['used']=$row->hrlead_used; 
            $arr[$i]['balance']=$row->hrlead_balance; 
            $arr[$i]['action']=''; 
            // $arr[$i]['status']=$arrayStatus[$row->hrlea_status];
            // $arr[$i]['action']='
            //     <div class="action-btn bg-warning ms-2">
            //         <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-leaveearn-adjustment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Leave Adjustment">
            //             <i class="ti-pencil text-white"></i>
            //         </a>
            //         </div>'.$status;
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
    public function getAdjustmentdetails(Request $request){
        $id =$request->input('pid');
        $arrLeaveType = array(""=>"Select Leave type");
        foreach ($this->_leaveadjustment->getLeaveType() as $val) {
                $arrLeaveType[$val->id]=$val->hrlt_leave_code."-".$val->hrlt_leave_type;
        }
        $defaultAdjustments = $this->_leaveadjustment->getParameterdetails($id);
        $html ="";
        foreach ($defaultAdjustments as $key => $value) {
            $html .='<div class="row removeadjustmentdata">';
                                   $html .='<div class="col-lg-3 col-md-3 col-sm-3">
                                           <div class="form-group">
                                                 <div class="form-icon-user hidden">
                                                     <input class="form-control"  id="hrlt_id" name="hrlt_id[]" type="text" value="'.$value->hrlt_id.'" fdprocessedid="3w2mkr" >
                                                  </div>
                                                 <div class="form-icon-user">
                                                 <input class="form-control" readonly="readonly" id="year" name="desc[]" type="text" value="'.$arrLeaveType[$value->hrlt_id].'" fdprocessedid="3w2mkr" readonly>
                                                 </div>
                                            </div>     
                                       </div>
                                         <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user"><input class="form-control" name="entitled[]" type="text" value="'.$value->hrlpc_days.'" readonly></div>
                                           </div>
                                      </div>
                                       <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user"><input class="form-control" name="adjustment[]" type="text" value="0"></div>
                                           </div>
                                      </div><div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user"><input class="form-control" name="used[]" type="text" value="0" readonly></div>
                                           </div>
                                      </div><div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user"><input class="form-control" name="balance[]" type="text" value="'.$value->hrlpc_days.'" readonly></div>
                                           </div>
                                      </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                        </div>
                               </div>';
        }
        echo $html;

    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_leaveadjustment->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arrayStatus = array('0'=>'Inactive','1'=>'Pending','2'=>'Approved');
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;   
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1; $positionname =""; $reviewname =""; $notedname ="";
            $status =($row->hrlea_status > 0) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['date']=$row->hrlea_date_effective; 
            $arr[$i]['status']=$arrayStatus[$row->hrlea_status];
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-leaveearn-adjustment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Leave Adjustment">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>'.$status;
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
        $data=array('hrlea_status' => $is_activeinactive);
        $this->_leaveadjustment->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_leaveadjustment->validate_approver($this->_leaveadjustment->getUserdapartment($id)->acctg_department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_leaveadjustment->validate_approver($this->_leaveadjustment->getUserdapartment($id)->acctg_department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }


    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $adjust = $this->_leaveadjustment->getAdjustmentDetails($id);
            foreach ($adjust as $value) {
                $adjustdata = [
                    'hrlpc_days' => $value->hrlpc_days + $value->hrlad_adjustment,
                    'hrlead_balance' => $value->hrlead_balance + $value->hrlad_adjustment,
                    // 'hrlad_adjustment' => 0,
                ];
                $this->_leaveadjustment->updateearnadjustdetailData($value->id,$adjustdata);
                if ($value->hlad_id) {
                    $this->_leaveadjustment->updateadjustdetailData($value->hlad_id,['hrlad_approved_by'=>Auth::user()->hr_employee->id]);
                }
            }
            $details = array(
                'hrlea_status' =>'2',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            

            $this->_leaveadjustment->updateData($id, $details);

            return response()->json([
                'text' => 'The Leave Adjustment has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }
       
    public function store(Request $request){

        $data = (object)$this->data;
        $date = date('Y-m-d');
        $arrDocuments = array();
        $data->applicationno ="";  $arrAdjustmentDetials = array();
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arrEmployee = $this->employee;   $status ="";
        $arrLeaveparameters = array(""=>"Select Leave Parameter");

        $userroleid = "";
        $getroleofuserdata = $this->_leaveadjustment->getUserrole(\Auth::user()->id);
        if(count($getroleofuserdata) > 0){
           $userroleid = $getroleofuserdata[0]->id; 
        }

        foreach ($this->_leaveadjustment->getLeaveParameters() as $val) {
                $arrLeaveparameters[$val->id]=$val->hrlp_description;
        }
        $arrLeaveType = array(""=>"Select Leave type");
        foreach ($this->_leaveadjustment->getLeaveType() as $val) {
                $arrLeaveType[$val->id]=$val->hrlt_leave_code."-".$val->hrlt_leave_type;
        }
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id; 
        $data->hro_status =0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_leaveadjustment->getRecordforEdit($request->input('id'));
            $arrAdjustmentDetials = $this->_leaveadjustment->getAdjustmentDetails($request->input('id'));
        }
        //echo "<pre>"; print_r($arrAdjustmentDetials); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if (array_sum($request->input('adjustment')) > 0) {
            	    $this->data['hrlea_status']=1;
                }
                $this->_leaveadjustment->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Leave Adjustment Added successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated 'Leave Adjustment Added '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['hrlea_status']=1;
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_leaveadjustment->addData($this->data);
                $success_msg = 'Leave Adjustment added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Leave Adjustment '".$this->data['hr_employeesid']."'";
            }
            //print_r($request->input('hrlt_id')); exit;
            if($request->input('hrlt_id') != null){
                foreach ($request->input('hrlt_id') as $key => $value) {
                    $addetaildata = array(); 
                    $addetaildata['hrlea_id'] = $lastinsertid;
                    $addetaildata['hrlt_id'] =$request->input('hrlt_id')[$key];
                    $addetaildata['hrlpc_days'] = $request->input('entitled')[$key];
                    // $addetaildata['hrlead_used'] = $request->input('used')[$key];
                    // $addetaildata['hrlead_balance'] = $request->input('balance')[$key];
                    $checktypesdata = $this->_leaveadjustment->checkdetailexistearn($lastinsertid,$request->input('hrlt_id')[$key]);
                    if(count($checktypesdata) > 0){
                        $addetaildata['updated_by']=\Auth::user()->id;
                        $addetaildata['updated_at'] = date('Y-m-d H:i:s');
                        $detaillastid = $checktypesdata[0]->id;
                        $this->_leaveadjustment->updateearnadjustdetailData($checktypesdata[0]->id,$addetaildata);
                    } else{
                        $addetaildata['created_by']=\Auth::user()->id;
                        $addetaildata['created_at'] = date('Y-m-d H:i:s');
                        $addetaildata['hrlead_used'] = 0;
                        $addetaildata['hrlead_balance'] = $request->input('entitled')[$key];
                        $detaillastid = $this->_leaveadjustment->adddearnjustdetailData($addetaildata);
                    }
                    if($request->input('adjustment')[$key] != 0){
                        $adjustdata = array(); 
                        $adjustdata['hrlead_id'] =$detaillastid;
                        $adjustdata['hrlt_id'] =$request->input('hrlt_id')[$key];
                        $adjustdata['hrlad_adjustment'] = $request->input('adjustment')[$key];
                        $adjustdata['hrlad_status'] = 1;
                        $adjustdata['hrlad_requested_by'] = Auth::user()->hr_employee->id;
                        $checkdetaildata = $this->_leaveadjustment->checkdetailexist($detaillastid,$request->input('hrlt_id')[$key]);
                        // if ($key === 1) {
                        //     dd($checkdetaildata);
                        //     # code...
                        // }
                        if($checkdetaildata){
                            $adjustdata['updated_by']=\Auth::user()->id;
                            $adjustdata['updated_at'] = date('Y-m-d H:i:s');
                            $this->_leaveadjustment->updateadjustdetailData($checkdetaildata->id,$adjustdata);
                        } else{
                            $adjustdata['created_by']=\Auth::user()->id;
                            $adjustdata['created_at'] = date('Y-m-d H:i:s');
                            $this->_leaveadjustment->adddjustdetailData($adjustdata);
                        }  
                    }

                }
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrleaveadjustment.index')->with('success', __($success_msg));
    	}
        return view('HR.leaveadjustment.create',compact('data','status','arrEmployee','date','arrLeaveparameters','arrLeaveType','arrAdjustmentDetials','userroleid'));
	}
    
    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_leaveadjustment->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }

    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hr_employeesid'=>'required|unique:hr_leave_adjustments,hr_employeesid,'.(int)$request->input('id').
                ',id,hrlea_date_effective,'.$request->input('hrlea_date_effective'),
                'hrlp_id'=>'required',
                'hrlea_date_effective'=>'required',
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

    public function getRemaining(Request $request){
        $employee_id = $request->employee;
        $leave_type = $request->type;
        $leaves = $this->_leaveadjustment->getRemaining($leave_type,$employee_id);
        echo json_encode($leaves);exit;
    }

    public function triggerAccuralMonthly(){
        $today = Carbon::today()->subMonth()->endOfMonth();
        $leaves = LeaveAdjustment::where('hrlea_date_effective', '<=', $today->toDateString())->get();
        foreach ($leaves as $leave) {
            $details = $this->_leaveadjustment->getAccuralBy($leave->id,2);
            foreach ($details as $detail) {

                $adjust = [
                    'hrlpc_days' => $detail->hrlpc_days + $detail->hrlpc_credits,
                    'hrlead_balance' => $detail->hrlead_balance + $detail->hrlpc_credits,
                ];
                $this->_leaveadjustment->updateearnadjustdetailData($detail->hlea_id,$adjust);
                if($detail->hrla_id == 2){
                }
            }
        }
        return 'done';
    }
    public function triggerAccuralAnnual(){
        $today = Carbon::today()->subYear()->endOfYear();
        $leaves = LeaveAdjustment::where('hrlea_date_effective', '<=', $today->toDateString())->get();
        foreach ($leaves as $leave) {
            $details = $this->_leaveadjustment->getAccuralBy($leave->id,3);
            foreach ($details as $detail) {

                $adjust = [
                    'hrlpc_days' => $detail->hrlpc_days + $detail->hrlpc_credits,
                    'hrlead_balance' => $detail->hrlead_balance + $detail->hrlpc_credits,
                ];
                $this->_leaveadjustment->updateearnadjustdetailData($detail->hlea_id,$adjust);
                if($detail->hrla_id == 2){
                }
            }
        }
        return 'done';
    }
    
}
