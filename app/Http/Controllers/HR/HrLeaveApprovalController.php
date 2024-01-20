<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrLeave;
use App\Models\CommonModelmaster;
use App\Interfaces\HrInterface;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrLeaveApprovalController extends Controller
{
    private HrInterface $hrRepository;
    public $data = [];
     public $postdata = [];
    
     public function __construct(HrInterface $hrRepository, Carbon $carbon){
        $this->hrRepository = $hrRepository;
		$this->_leaves= new HrLeave(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hrl_start_date'=>'','hrl_end_date'=>'','hrlt_id'=>'','hrla_id'=>'','hrla_reason'=>'');  
        $this->slugs = 'hr-leaves-approval'; 
        foreach ($this->_leaves->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
        $this->application_status = [
            'Draft',
            'Cancelled',
            'Disapproved',
            'Submitted',
            'Pending',
            'For Approval',
            'Approved',
        ];
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.leavesapproval.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_leaves->getListApprover($request);
        // dd($data);
        //echo "<pre>"; print_r($data); exit;
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;   
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1; $positionname =""; $reviewname =""; $notedname ="";
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['applicationno']=$row->applicationno;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['hrl_start_date']=$row->hrl_start_date; 
            $arr[$i]['hrl_end_date']=$row->hrl_end_date;
            $arr[$i]['hrlt_leave_type']=$row->hrlt_leave_type;
            $arr[$i]['days']="";
            $arr[$i]['dayswithpay']=$row->dayswithpay;
            $arr[$i]['reason']=$row->hrla_reason;
            $arr[$i]['status']=$arrChangeSchedulestatus[$row->hrla_status];
            if(!empty($row->hrla_approved_by)){
            $position = $this->_leaves->Get_hrfullname($row->hrla_approved_by);
            $positionname = $position->fullname;
            }
            $arr[$i]['approve']=$positionname;
            if(!empty($row->hrla_reviewed_by)){
            $review = $this->_leaves->Get_hrfullname($row->hrla_reviewed_by);
            $reviewname = $review->fullname;
            }
            $arr[$i]['review']=$reviewname;
            if(!empty($row->hrla_noted_by)){
            $noted = $this->_leaves->Get_hrfullname($row->hrla_noted_by);
            $notedname = $noted->fullname;
            }
            $arr[$i]['noted']=$notedname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-leaves-approval/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Leaves">
                        <i class="ti-pencil text-white"></i>
                    </a>
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
        $this->_leaves->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_leaves->validate_approver($this->_leaves->getRecordforEdit($id)->department_id, $sequence, 'sub modules', 'hr-leaves', Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_leaves->validate_approver($this->_leaves->getRecordforEdit($id)->department_id, $sequence, 'sub modules', 'hr-leaves', Auth::user()->id);
    }


    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_leaves->getRecordforEdit($id)->hrla_approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $approvers = 2 ; $Status= '6';
            }
            $positionname ="";
            $position = $this->_leaves->fetch_destination(Auth::user()->id);
            $positionname = $position->description;
            $timestamp = $this->carbon::now();
            $details = array(
                'hrla_status' =>$Status,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            
            if($sequence=='1'){
                $details['hrla_approved_by'] = Auth::user()->id;
                //$details['hrla_approved_at'] = $positionname;  
                $details['hrla_approved_at']= $this->carbon::now();
            }else if($sequence=='2'){
                $details['hrla_reviewed_by'] = Auth::user()->id;
                //$details['reviewed_position'] = $positionname; 
                $details['hrla_reviewed_at']= $this->carbon::now(); 
            }else if($sequence=='3'){
                $details['hrla_noted_by'] = Auth::user()->id;
                //$details['noted_position'] = $positionname;  
                $details['hrla_noted_at']= $this->carbon::now();
                // $leave = $this->_leaves->find($id)->useLeaves();
            }

            $this->_leaves->updateData($id, $details);

            return response()->json([
                'text' => 'The leaves has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request)
    {
        $id = $request->input('id');
        $reason = $request->input('reason');
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($request->sequence === '0') {
                $status = 1;//cancel
                $reason = 'Canceled By '.Auth::user()->hr_employee->fullname;
            } else {
                $status = 2;//disapprove
            }
            $timestamp = $this->carbon::now();
            $details = array(
                'hrla_status' => $status,
                'hrla_disapproved_at' => $timestamp,
                'hrla_disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'hrla_disapproved_remarks' => $reason,
                'updated_by' => Auth::user()->id
            );
            $this->_leaves->updateData($id, $details);
            $leave = $this->_leaves->find($id)->useLeaves('cancel');
            return response()->json([
                'text' => 'The leaves has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        $date = date('Y-m-d');
        $arrDocuments = array();
        $data->applicationno ="";
        $arrChangeSchedulestatus = $this->application_status;
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $arrLeavetypes = array();
        foreach ($this->_leaves->getLeavetypes() as $val) {
                $arrLeavetypes[$val->id]=$val->hrlt_leave_type;
        } 
        $arrApplicationtypes = array();
        foreach ($this->_leaves->getApplicationtypes() as $val) {
                $arrApplicationtypes[$val->id]=$val->hrla_description;
        }
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id; 
        $data->hrla_status = 0;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_leaves->find($request->input('id'));
            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$data->department_id, $this->slugs,$data->hrla_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];
            $arrDocuments =$this->_leaves->GetDocumentfiles($request->input('id')); 
            $data->emp_name= $this->_hrEmployee->empDataById($data->hr_employeesid)->fullname;
            // dd($data->emp_name);

        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_leaves->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Leave Application updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Leave Application '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $appNumber = $this->getappNumber();
                $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                $applicationno = date('Y')."-".$applicationno;

                $this->data['applicationno'] = $applicationno;
                $lastinsertid = $this->_leaves->addData($this->data);
                $success_msg = 'Leave Application added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Leave Application '".$this->data['hr_employeesid']."'";
            }
            if(isset($_POST['totalfiles'])){
             foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/leaves/'.$lastinsertid;
                            if(!File::exists($destinationPath)){ 
                                File::makeDirectory($destinationPath, 0755, true, true);
                            }
                         $filename =  date('his').'document'.$lastinsertid;  
                         $filename = str_replace(" ", "", $filename);   
                         $documentpdf = $filename. "." . $image->extension();
                         $extension =$image->extension();
                         $image->move($destinationPath, $documentpdf);
                        // print_r($image); exit;
                         $filearray = array();
                         $filearray['hrl_id'] = $lastinsertid;
                         $filearray['hrcos_file_name'] = $documentpdf;
                         $filearray['hrcos_file_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['hrcos_file_path'] = 'humanresource/leaves/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_leaves->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_leaves->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                }
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrleaves.index')->with('success', __($success_msg));
    	}
       
        return view('HR.leavesapproval.create',compact('data','arrLeavetypes','arrEmployee','validateapprove','status','approve_btn','date','arrApplicationtypes','arrDocuments'));
	}
    
    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_leaves->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_leaves->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->hrcos_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->hrcos_file_path."/".$arrDocumentss[0]->hrcos_file_name;
                if(File::exists($path)) { 
                    unlink($path);
                }
                $this->_leaves->deleteimagerowbyid($rid); 
                echo "deleted";
            }
        }
    }

    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hr_employeesid'=>'required|unique:hr_leaves,hr_employeesid,'.(int)$request->input('id'),'hrl_start_date,'.$request->input('hrl_start_date'),
                'hrl_start_date'=>'required',
                'hrl_end_date'=>'required',
                'hrlt_id'=>'required',
                'hrla_id'=>'required',
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
