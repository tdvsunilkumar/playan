<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Interfaces\HrInterface;
use App\Models\HR\HrMissedLog;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use File;

class HrMissedLogsApprovalController extends Controller
{
    private HrInterface $hrRepository;
    public $data = [];
    public $postdata = [];

     public function __construct(HrInterface $hrRepository, Carbon $carbon){
		$this->_hrMissedLog= new HrMissedLog(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->hrRepository = $hrRepository;
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hml_application_no'=>'','hml_work_date'=>'','hrlog_id'=>'','hml_actual_time'=>'','hml_reason'=>'');  
        $this->slugs = 'hr-missed-logapproval'; 
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
            // $approver = $this->_hrMissedLog->getFirstapproverid('hr-missed-logs',$this->_hrMissedLog->getUserdapartment(\Auth::user()->id)->acctg_department_id);
            // echo "<pre>"; print_r($approver); exit;
            $this->is_permitted($this->slugs, 'read');
            return view('HR.missedLogsApproval.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $data=$this->_hrMissedLog->getListApv($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['emp_name']=$row->emp_name;
            $arr[$i]['applicationno']=$row->applicationno;
            $arr[$i]['filed_date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['work_date']=date("M d, Y",strtotime($row->hml_work_date));
            $arr[$i]['time']=date("h:i a", strtotime($row->hml_actual_time));
            $arr[$i]['log_type']=$row->hrlog_description;
            $arr[$i]['reason']=$row->hml_reason;
            $arr[$i]['status']=$arrChangeSchedulestatus[$row->hml_status];
            $arr[$i]['apv_by']=$row->apv_by;
            $arr[$i]['review_by']=$row->review_by;
            $arr[$i]['noted_by']=$row->noted_by;
            $arr[$i]['action']='
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-missed-logapproval/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Approve Missed Log">
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
    
    public function RemoveMissedLog(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $missed_log=$this->_hrMissedLog->find($id);
        if($missed_log->hml_status < 2)
        {
            $arr=array('icon'=>'error','title' => "You can't cancel the missed log, Because it's already approved.");
            echo json_encode($arr);exit;
        }
        if($missed_log->hml_status == 6)
        {
            $arr=array('icon'=>'error','title' => "The missed log already Cancelled.");
            echo json_encode($arr);exit;
        }

        $data=array('hml_status' => 6);
        $this->_hrMissedLog->updateData($id,$data);
        $arr=array('icon'=>'success','title' => 'Missed Log Cancelled Successfully.');
        echo json_encode($arr);exit;
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_hrMissedLog->validate_approver($this->_hrMissedLog->getRecordforEdit($id)->department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_hrMissedLog->validate_approver($this->_hrMissedLog->getRecordforEdit($id)->department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_hrMissedLog->find($id)->hml_approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $approvers = 2 ; $Status= '6';
            }
            $positionname ="";
            $position = $this->_hrMissedLog->fetch_destination(Auth::user()->id);
            $positionname = $position->description;
            $timestamp = $this->carbon::now();
            $details = array(
                'hml_status' =>$Status,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if($sequence=='1'){
                $details['hml_approved_by'] = Auth::user()->id;
                //$details['hml_approved_at'] = $positionname;  
                $details['hml_approved_at']= $this->carbon::now();
            }else if($sequence=='2'){
                $details['hml_reviewed_by'] = Auth::user()->id;
                //$details['reviewed_position'] = $positionname; 
                $details['hml_reviewed_at']= $this->carbon::now(); 
            }else{
                $details['hml_noted_by'] = Auth::user()->id;
                //$details['noted_position'] = $positionname;  
                $details['hml_noted_at']= $this->carbon::now();
            }

            $this->_hrMissedLog->updateData($id, $details);

            return response()->json([
                'text' => 'The missed log has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    

    public function store(Request $request){
        $data = (object)$this->data;
        $arrChangeSchedulestatus = $this->application_status;
        $arrDocuments = array();
        $filed_date=Carbon::now();
        $currentYear = date('Y');
        $validateapprove=""; $validatereview=""; $validatenoted="";
        $lastData = DB::table('hr_missed_log')->where('hml_application_no', 'like', "{$currentYear}%")
                        ->orderByDesc('id')
                        ->first();
            if ($lastData) {
                $lastNumber = intval(substr($lastData->hml_application_no, -6));
                $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '000001';
            }
        $application_number = $currentYear . '-' . $newNumber;
        $status="";
        $log_type = array();
        foreach ($this->_hrMissedLog->getLogType() as $val) {
                $log_type[$val->id]=$val->hrlog_description;
        } 
        $data->hr_emp_id = \Auth::user()->id;
        $data->hml_status = 0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrMissedLog->find($request->input('id'));
            $application_number=$data->hml_application_no;
            
            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$this->_hrMissedLog->getRecordforEdit($data->id)->department_id, $this->slugs,$data->hml_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];
            $arrDocuments =$this->_hrMissedLog->GetDocumentfiles($request->input('id')); 
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if($request->input('submit') == "submit"){
                    $this->data['hml_status'] = 1;
                }else{
                    $this->data['hml_status'] = 0; 
                }
                $this->_hrMissedLog->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Missed Log updated successfully.';
                // $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Work Schedule '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['hr_emp_id']=HrEmployee::hrEmpIdByUserId(\Auth::user()->id);
                $this->data['created_at'] = date('Y-m-d H:i:s');
                if($request->input('submit') == "submit"){
                    $this->data['hml_status'] = 1;
                }else{
                    $this->data['hml_status'] = 0; 
                }
               
                $lastinsertid = $this->_hrMissedLog->addData($this->data);
                $success_msg = 'Missed Log Filed successfully.';
                // $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Default Schedule '".$this->data['hr_employeesid']."'";
            }
            if(isset($_POST['totalfiles']))
            {
            //echo "<pre>"; print_r($request->file('documents')); exit;
             foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/missedLogs/'.$lastinsertid;
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
                         $filearray['hml_id'] = $lastinsertid;
                         $filearray['fhml_file_name'] = $documentpdf;
                         $filearray['fhml_file_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['fhml_file_path'] = 'humanresource/missedLogs/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_hrMissedLog->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_hrMissedLog->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                }
            // $logDetails['module_id'] =$request->id;
            // $this->_commonmodel->updateLog($logDetails);
            }
            return redirect()->route('hr-missed-logs.index')->with('success', __($success_msg));
    	}
        return view('HR.missedLogsApproval.create',compact('data','filed_date','application_number','status','log_type','approve_btn','arrDocuments'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hml_application_no'=>'required|unique:hr_missed_log,hml_application_no',
                'hml_work_date'=>'required',
                'hrlog_id'=>'required',
                'hml_actual_time'=>'required',
                'hml_reason'=>'required',
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

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_hrMissedLog->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->fhml_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->fhml_file_name."/".$arrDocumentss[0]->fhml_file_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_hrMissedLog->deleteimagerowbyid($rid); 
              
                echo "deleted";
            }
        }
    }
}
