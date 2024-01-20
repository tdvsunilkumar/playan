<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\ChangeofSchedule;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use App\Models\HR\HrWorkSchedule;

class ChangeofScheduleController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(Carbon $carbon){
		$this->_chnageofschedule= new ChangeofSchedule(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array(
            'id'=>'',
            'hr_employeesid'=>'',
            'hrcos_start_date'=>'',
            'hrcos_end_date'=>'',
            'hrcos_original_schedule'=>'',
            'hrcos_new_schedule'=>'',
            'reason'=>''
        );  
        $this->slugs = 'hr-change-schedule'; 
        foreach ($this->_chnageofschedule->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
    }
    
    public function index(Request $request)
    {       $slug = $this->slugs;  
            $this->is_permitted($this->slugs, 'read');
            return view('HR.changeofschedule.index',compact('slug'));
    }


    public function getList(Request $request){
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $hr_employeesid = $hr_emp->id;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_chnageofschedule->getList($request,$hr_employeesid);
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;   
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1; $positionname =""; $reviewname =""; $notedname ="";
            $status ='<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="6" id='.$row->id.'></a></div>'; 
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['applicationno']=$row->applicationno;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['hrcos_start_date']=$row->hrcos_start_date; 
            $arr[$i]['hrcos_end_date']=$row->hrcos_end_date;
            $arr[$i]['schedule']=$row->hrcos_end_date;
            $arr[$i]['reason']=$row->reason;
            $arr[$i]['status']=$arrChangeSchedulestatus[$row->status];
            if(!empty($row->approved_by)){
            $position = $this->_chnageofschedule->Get_hrfullname($row->approved_by);
            $positionname = $position->fullname;
            }
            $arr[$i]['approve']=$positionname;
            if(!empty($row->reviewd_by)){
            $review = $this->_chnageofschedule->Get_hrfullname($row->reviewd_by);
            $reviewname = $review->fullname;
            }
            $arr[$i]['review']=$reviewname;
            if(!empty($row->noted_by)){
            $noted = $this->_chnageofschedule->Get_hrfullname($row->noted_by);
            $notedname = $noted->fullname;
            }
            $arr[$i]['noted']=$notedname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-change-schedule/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Change of Schedule">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>';
            if($row->status <=1){
                $arr[$i]['action'].=$status;
            }        
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
        $this->_chnageofschedule->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_chnageofschedule->validate_approver($this->_chnageofschedule->getRecordforEdit($id)->department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_chnageofschedule->validate_approver($this->_chnageofschedule->getRecordforEdit($id)->department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }


    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_chnageofschedule->getRecordforEdit($id)->approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $approvers = 2 ; $Status= '6';
            }
            $positionname ="";
            $position = $this->_chnageofschedule->fetch_destination(Auth::user()->id);
            $positionname = $position->description;
            $timestamp = $this->carbon::now();
            $details = array(
                'status' =>$Status,
                'approved_counter' => $approvers + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if($sequence=='1'){
                $details['approved_by'] = Auth::user()->id;
                $details['approvedbyposition'] = $positionname;  
            }else if($sequence=='2'){
                $details['reviewd_by'] = Auth::user()->id;
                $details['reviewed_position'] = $positionname;  
            }else{
                $recorddata = $this->_chnageofschedule->getRecordforEdit($id);
                $startdate = $recorddata->hrcos_start_date; $newschedule =$recorddata->hrcos_new_schedule;
                $enddate = $recorddata->hrcos_end_date; $employeeid = $recorddata->hr_employeesid;
                HrWorkSchedule::updateWorkSched([
                    'start_date' => $recorddata->hrcos_start_date,
                    'end_date' => $recorddata->hrcos_end_date,
                    'hrds_id' => $recorddata->hrcos_new_schedule,
                    'hr_employeesid' => $recorddata->hr_employeesid,
                ],$recorddata->hr_employeesid);
                // $this->updateworkschedule($startdate,$enddate,$newschedule,$employeeid);
                $details['noted_by'] = Auth::user()->id;
                $details['noted_position'] = $positionname;  
            }

            $this->_chnageofschedule->updateData($id, $details);

            return response()->json([
                'text' => 'The change of schedule has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function updateworkschedule($startdate,$enddate,$newschedule,$employeeid){

            while (strtotime($startdate) <= strtotime($enddate)) { 
                   $month =  date ("m",strtotime($startdate));
                   $day =  date ("d",strtotime($startdate));
                   $year =  date ("Y",strtotime($startdate));
                    $arr = $this->_chnageofschedule->getScheduleDetails($employeeid,$year,$month);
                    dd($arr);
                    if(isset($arr)){
                        $arrJson = json_decode($arr->monthdate_json,true);
                        $key  = array_search($day, array_column($arrJson, 'day'));
                           if($key !== false){
                                $arrJson[$key]['schedule'] = $newschedule;
                            }
                            if(isset($arrJson)){
                                $finalJsone = $arrJson;
                            }
                            $data['monthdate_json'] = json_encode($finalJsone);
                            $this->_chnageofschedule->updateWorkData($employeeid,$year,$month,$data);
                    }
                   //echo      "$startdate\n";
                   $startdate = date ("Y-m-d", strtotime("+1 day", strtotime($startdate)));
                  
              }
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
            $recorddata = $this->_chnageofschedule->getRecordforEdit($id);
            if ($recorddata->status === 6) {
                // dd($recorddata);
                HrWorkSchedule::updateWorkSched([
                    'start_date' => $recorddata->hrcos_start_date,
                    'end_date' => $recorddata->hrcos_end_date,
                    'hrds_id' => $recorddata->hrcos_original_schedule,
                    'hr_employeesid' => $recorddata->hr_employeesid,
                ],$recorddata->hr_employeesid);
            }
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => $status,
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->_chnageofschedule->updateData($id, $details);
            return response()->json([
                'text' => 'The change of schedule has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        
        $arrDocuments = array();
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $data->applicationno =""; $status="";
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $arrdefaultschedule = array();
        foreach ($this->_chnageofschedule->getDefaultschedule() as $val) {
                $arrdefaultschedule[$val->id]=date("h:i a", strtotime($val->hrds_start_time))." ".date("h:i a", strtotime($val->hrds_end_time));
        } 
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id;
        $data->status = 0;

        $now = Carbon::today();
        $hrcos_original_schedule = HrWorkSchedule::where([
                ['year',$now->year],
                ['month',$now->month],
                ['hr_employeesid',$hr_emp->id]
            ])->first();
        $data->hrcos_original_schedule = ($hrcos_original_schedule) ? $hrcos_original_schedule->hrds_id : 1;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_chnageofschedule->getRecordforEdit($request->input('id'));
            $status=$arrChangeSchedulestatus[$data->status];
            if($data->approved_by){ 
                $validateapprove =1; 
            }
            if($data->reviewd_by){ 
                $validatereview =1; 
            }
            if($data->noted_by){ 
                $validatenoted =1; 
            }
            if(empty($data->approved_by)){
            $Sequence = 1;  $validateapprove = $this->validate_approver($request->input('id'),1); 
            }
            else if(empty($data->reviewd_by)){  
                $validatereview = $this->validate_approver($request->input('id'),2); 
                $Sequence = 2;
            } 
            else if(empty($data->noted_by)){ 
                $Sequence = 3; 
                $validatenoted = $this->validate_approver($request->input('id'),3); 
            }

            $arrDocuments =$this->_chnageofschedule->GetDocumentfiles($request->input('id'));
             
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['status'] =$request->input('submit_type');
               //print_r($_POST); exit;
            $userdata = $this->_chnageofschedule->getUserdapartment(Auth::user()->id);
            $this->data['department_id']=$userdata->acctg_department_id;
            if($request->input('id')>0){
                $this->_chnageofschedule->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Change Of Schedule updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Change Of Schedule '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                
                $appNumber = $this->getappNumber();
                $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                $applicationno = date('Y')."-".$applicationno;

                $this->data['applicationno'] = $applicationno;
                $lastinsertid = $this->_chnageofschedule->addData($this->data);
                $success_msg = 'Change Of Schedule added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Change Of Schedule '".$this->data['hr_employeesid']."'";
            }
            if(isset($_POST['totalfiles'])){
             foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/changeschedule/'.$lastinsertid;
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
                         $filearray['hrcos_id'] = $lastinsertid;
                         $filearray['hrcos_file_name'] = $documentpdf;
                         $filearray['hrcos_file_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['hrcos_file_path'] = 'humanresource/changeschedule/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_chnageofschedule->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_chnageofschedule->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                }
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrchnageschedule.index')->with('success', __($success_msg));
    	}
        return view('HR.changeofschedule.create',compact('data','status','arrdefaultschedule','arrEmployee','validateapprove','validatenoted','validatereview','arrDocuments'));
	}

    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_chnageofschedule->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_chnageofschedule->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->hrcos_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->hrcos_file_path."/".$arrDocumentss[0]->hrcos_file_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_chnageofschedule->deleteimagerowbyid($rid); 
              
                echo "deleted";
            }
        }
    }
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                // 'hr_employeesid'=>'required|unique:hr_changeof_schedules,hr_employeesid,'.(int)$request->input('id').',id,hrcos_start_date,'.$request->input('hrcos_start_date'),
                'hrcos_start_date'=>'required',
                'hrcos_end_date'=>'required|date|after_or_equal:hrcos_start_date',
                'hrcos_original_schedule'=>'required',
                'hrcos_new_schedule'=>'required',
                'hr_employeesid' => [
                    'required',
                    Rule::unique('hr_changeof_schedules')->where(function ($query) use ($request) {
                        return $query->where('hrcos_start_date', $request->input('hrcos_start_date'))
                                    ->where('id', '!=',$request->input('id'))
                                    ->whereIn('status', [0,3,4,5,6]);
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
}
