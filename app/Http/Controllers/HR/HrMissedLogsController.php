<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrMissedLog;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use File;
use Auth;
class HrMissedLogsController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $busn_log_status = ['0'=>"Draft",'1'=>"Filed",'2'=>"Approved",'3'=>"Decline"];

     public function __construct(){
		$this->_hrMissedLog= new HrMissedLog(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->employee = array(""=>"Please Select");
        $this->data = array(
            'id'=>'',
            'hml_application_no'=>'',
            'hml_work_date'=>'',
            'hrlog_id'=>'',
            'hml_actual_time'=>'',
            'hml_reason'=>'',
            'hr_emp_id'=> '',
        );  
        $this->slugs = 'hr-missed-logs'; 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.missedLogs.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $data=$this->_hrMissedLog->getList($request);
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
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
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-missed-logs/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Missed Log">
                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-danger ms-2">
                        <a href="#" class="mx-3 btn btn-sm remove ti-trash text-white text-white" name="remove" value="0" id='.$row->id.'></a>
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

    public function validate_approver($id, $sequence)
    {
        return $this->_chnageofschedule->validate_approver($this->_hrMissedLog->find($id)->department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }
    public function disapprove(Request $request)
    {
        $id = $request->input('id');
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($request->sequence === '0') {
                $status = 1;//cancel
            } else {
                $status = 2;//disapprove
            }
            $recorddata = $this->_hrMissedLog->getRecordforEdit($id);
            if ($recorddata->hml_status === 6) {
                $remove = [
                    'hrtc_employeesid' => $recorddata->hr_emp_id,
                    'hrtc_date' => $recorddata->hml_work_date,
                    'hrtc_undertime' => 0,
                    'hrtc_late' => 0,
                    'hrtc_hours_work' => 0,
                ];
                if ($recorddata->hrlog_id === 1) {
                    $remove['hrtc_time_in'] = null;
                } else {
                    $remove['hrtc_time_out'] = null;
                }
                // dd($remove);
                $this->_hrMissedLog->disapprove($remove);
                
            }
            $timestamp = Carbon::now();
            $details = array(
                'hml_status' => $status,
                'hml_disapproved_at' => $timestamp,
                'hml_disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->_hrMissedLog->updateData($id, $details);
            return response()->json([
                'text' => 'The missed log has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function store(Request $request){
        
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $data = (object)$this->data;
        // dd(auth()->user()->hr_employee->id);
        $data->hr_emp_id = auth()->user()->hr_employee->id;
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
        $data->hml_status = 0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrMissedLog->find($request->input('id'));
            $arrDocuments =$this->_hrMissedLog->GetDocumentfiles($request->input('id'));
            $application_number=$data->hml_application_no;
            $status=$arrChangeSchedulestatus[$data->hml_status];
            $filed_date=Carbon::parse($data->created_at);
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->data['hml_status'] =$request->input('submit_type');
                $this->_hrMissedLog->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Missed Log updated successfully.';
                // $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Work Schedule '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['hr_emp_id']=HrEmployee::hrEmpIdByUserId(\Auth::user()->id);
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hml_status'] =$request->input('submit_type');
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
        return view('HR.missedLogs.create',compact('data','filed_date','application_number','status','log_type','validateapprove','validatenoted','validatereview','arrDocuments'));
	}
    
    
    public function formValidation(Request $request){
            $id=$request->input('id');
            $validator = \Validator::make(
            $request->all(), [
                'hml_application_no' => [
                    'required',
                    Rule::unique('hr_missed_log')->ignore($id, 'id'),
                ],
                'hml_work_date'=>'required|before_or_equal:created_at',
                'hrlog_id'=>'required',
                'hml_actual_time'=>'required',
                'hml_reason'=>'required',
                'hr_emp_id' => [
                    'required',
                    Rule::unique('hr_missed_log')->where(function ($query) use ($request) {
                        return $query->where('hml_work_date', $request->input('hml_work_date'))
                                    ->where('id', '!=',$request->input('id'))
                                    ->where('hrlog_id', $request->input('hrlog_id'))
                                    ->whereIn('hml_status', [0,3,4,5,6]);
                    }),
                ],
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
