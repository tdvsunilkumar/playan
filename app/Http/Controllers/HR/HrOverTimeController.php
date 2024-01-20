<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrOverTimeModels;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use App\Models\HR\HrHolidays;
use App\Models\HR\HrAppointment;
use App\Interfaces\HrInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;

class HrOverTimeController extends Controller
{
    private HrInterface $hrRepository;
    public $data = [];
    public $postdata = [];
    
     public function __construct(HrInterface $hrRepository, Carbon $carbon){
        $this->hrRepository = $hrRepository;
		$this->_hrovertime= new HrOverTimeModels(); 
		$this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hrot_application_no'=>'','hrot_work_date'=>'','hrwc_id'=>'','hro_status'=>'','hrot_start_time'=>'','hrot_end_time'=>'','hro_reason'=>'','hrot_following_day'=>'','hrot_considered_hours'=>'','hrot_multiplier'=>'');  
        $this->slugs = 'hr-overtime'; 
        $this->application_status = [
            'Draft',
            'Cancelled',
            'Disapproved',
            'Submitted',
            'Pending',
            'For Approval',
            'Approved',
        ];
        foreach ($this->_hrovertime->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.overtime.index');
    }
	public function store(Request $request){
		$data = (object)$this->data;
        $arrDocuments = array();  $date = date('Y-m-d');
		$arrDaystype = array('1'=>'No','2'=>'Yes');
        $arrHrWorkCredit = config('constants.arrHrWorkCredit');
        $arrOTMultiplier = config('constants.arrOTMultiplier');
        $arrChangeSchedulestatus = $this->application_status;
        $data->applicationno ="";
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $status="";
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id;
        $data->status = 0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrovertime->getRecordforEdit($request->input('id'));

            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$data->department_id, 'hr-overtime-approval',$data->hro_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];

            $date = date('Y-m-d',strtotime($data->created_at));
            $arrDocuments =$this->_hrovertime->GetDocumentfiles($request->input('id'));
             
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            // 
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['hro_status'] = $request->input('submit_type');
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $userdata = $this->_hrovertime->getUserdapartment(Auth::user()->id);
            
            if ($request->hrwc_id === '2') {
                $appointment = HrAppointment::where('hr_emp_id',Auth::user()->hr_employee->id)->first();
                $workdate = $request->hrot_work_date;
                $holiday = HrHolidays::where('hrh_date',$workdate);
                // dd($holiday);
                if ($holiday->count() > 1) {
                    $this->data['hrot_is_double_holiday'] = 1;
                    // dd($holiday->get()[0]);
                } else if ($holiday->count() === 1) {
                    if ($holiday->get()[0]->hrht_id === 1) {
                        $this->data['hrot_is_regular_holiday'] = 1;
                    } else if ($holiday->get()[0]->hrht_id === 2){
                        $this->data['hrot_is_special_holiday'] = 1;
                    } else {
                        $this->data['hrot_is_regular_day'] = 1;
                    }
                    if ($holiday->get()[0]->hrh_is_paid) {
                        $is_paid = true;
                    }
                }

                if (Carbon::parse($workdate)->isWeekend()) {
                    $this->data['hrot_is_rest_day'] = 1;
                }

                $hourly_rate = $appointment->hra_hourly_rate;
                if (isset($this->data['hrot_is_double_holiday']) && isset($this->data['hrot_is_rest_day'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['double_holiday_rest_day'];
                } else if (isset($this->data['hrot_is_double_holiday'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['double_holiday'];
                } else if (isset($this->data['hrot_is_regular_holiday']) && isset($this->data['hrot_is_rest_day'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['regular_holiday_rest_day'];
                } else if (isset($this->data['hrot_is_special_holiday']) && isset($this->data['hrot_is_rest_day'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['special_holiday_rest_day'];
                } else if (isset($this->data['hrot_is_regular_holiday'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['regular_holiday'];
                } else if (isset($this->data['hrot_is_special_holiday'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['special_holiday'];
                } else if (isset($this->data['hrot_is_rest_day'])) {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['rest_day'];
                } else {
                    $this->data['hrot_multiplier'] = config('constants.arrOTMultiplier')['ordinary'];
                    $this->data['hrot_is_regular_day'] = 1;
                }
                if (isset($is_paid)) {
                    $this->data['hrot_ot_cost'] = $hourly_rate * $this->data['hrot_multiplier'] * $this->data['hrot_considered_hours'];
                }
            }
            
            if($request->input('id')>0){
                // dd($this->data);
                $this->_hrovertime->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Overtime Work updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Overtime Work '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $appNumber = $this->getappNumber();
                $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                $applicationno = date('Y')."-".$applicationno;

                $this->data['hrot_application_no'] = $applicationno;
                $lastinsertid = $this->_hrovertime->addData($this->data);
                $success_msg = 'overtime Work added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Overtime Work '".$this->data['hr_employeesid']."'";
            }
            
           
             if(isset($_POST['totalfiles'])){
               foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/overtime/'.$lastinsertid;
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
                         $filearray['hrow_id'] = $lastinsertid;
                         $filearray['fhow_file_name'] = $documentpdf;
                         $filearray['fhow_file_type'] = $extension;
                         //$filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['fhow_file_path'] = 'humanresource/overtime/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_hrovertime->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_hrovertime->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                  } 
               }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrovertime.index')->with('success', __($success_msg));
    	}
        return view('HR.overtime.create',compact('data','status','arrEmployee','arrDaystype','arrOTMultiplier','validateapprove','validatenoted','validatereview','arrDocuments','arrHrWorkCredit','date'));
	}

	
   public function getList(Request $request){
        //$this->is_permitted($this->slugs, 'read');
		
        $data=$this->_hrovertime->getList($request);
		$arrHrWorkCredit = config('constants.arrHrWorkCredit');
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;   
        $url = '/hr-overtime';
        if ($request->input('type') === 'approval') {
            $url = '/hr-overtime-approval';
        }
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1; $positionname =""; $reviewname =""; $notedname ="";
            $arr[$i]['srno']=$sr_no;
            $status =($row->hro_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['applicationno']=$row->applicationno;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['hrot_application_no']=$row->hrot_application_no;
            $arr[$i]['hrot_work_date']=$row->hrot_work_date; 
            $arr[$i]['hrwc_id']=$arrHrWorkCredit[$row->hrwc_id];
            $arr[$i]['hrot_start_time']= date("h:i a", strtotime($row->hrot_start_time));
            $arr[$i]['hrot_end_time']= date("h:i a", strtotime($row->hrot_end_time));
            $arr[$i]['hro_reason']=$row->hro_reason;
            $arr[$i]['hro_status']=$arrChangeSchedulestatus[$row->hro_status];
            if(!empty($row->hro_approved_by)){
            $position = $this->_hrovertime->Get_hrfullname($row->hro_approved_by);
            $positionname = $position->fullname;
            }
            $arr[$i]['approve']=$positionname;
            if(!empty($row->hro_reviewed_by)){
            $review = $this->_hrovertime->Get_hrfullname($row->hro_reviewed_by);
            $reviewname = $review->fullname;
            }
            $arr[$i]['review']=$reviewname;
            if(!empty($row->hro_noted_by)){
            $noted = $this->_hrovertime->Get_hrfullname($row->hro_noted_by);
            $notedname = $noted->fullname;
            }
            $arr[$i]['noted']=$notedname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" 
                    data-url="'.url($url.'/store?id='.$row->id).'" 
                    data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Application - Overtime">
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
    public function validate_approveredit($id, $sequence)
    {
        return $this->_hrovertime->validate_approver($this->_hrovertime->getUserdapartment($id)->acctg_department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_hrovertime->validate_approver($this->_hrovertime->getUserdapartment($id)->acctg_department_id, $sequence, 'sub modules', $this->slugs, Auth::user()->id);
    }
	public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_hrovertime->getRecordforEdit($id)->hro_approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $ot_data = $this->_hrovertime->getRecordforEdit($id);
                $approvers = 2 ; $Status= '6';
                $hourdata = $this->_hrovertime->chkBalanceHour($ot_data->hr_employeesid);
                $offsethours = $ot_data->hrot_considered_hours; 
                if($ot_data->hrwc_id =='1'){
                    if(count($hourdata) > 0){
                        $dbavlhours = $hourdata[0]->hroh_balance_offset_hours;
                        $avlhours = $dbavlhours + $offsethours;
                        $totaloffset = $hourdata[0]->hroh_total_offset_hours + $offsethours;
                        $updatedata = array();
                        $updatedata['hroh_balance_offset_hours'] = $avlhours;
                        $updatedata['hroh_total_offset_hours'] = $totaloffset;
                        $this->_hrovertime->updateOffserHourData($ot_data->hr_employeesid,$updatedata);
                    }else{
                        $adddata = array();
                        $adddata['hroh_balance_offset_hours'] = $offsethours;
                        $adddata['hroh_total_offset_hours'] = $offsethours;
                        $adddata['hr_employeesid'] = $ot_data->hr_employeesid;
                        $adddata['hroh_used_offset_hours'] = 0;
                        $this->_hrovertime->addOffsetHoursData($adddata);
                    } 
                }
            }
            $positionname ="";
            $position = $this->_hrovertime->fetch_destination(Auth::user()->id);
            $positionname = $position->description;
            $timestamp = $this->carbon::now();
            $details = array(
                'hro_status' =>$Status,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if($sequence=='1'){
                $details['hro_approved_by'] = Auth::user()->id;
                 $details['hro_approved_at']= $this->carbon::now(); 
            }else if($sequence=='2'){
                $details['hro_reviewed_by'] = Auth::user()->id; 
                $details['hro_reviewed_at']= $this->carbon::now(); 
            }else{
                $details['hro_noted_by'] = Auth::user()->id; 
                $details['hro_noted_at']= $this->carbon::now();
            }

            $this->_hrovertime->updateData($id, $details);

            return response()->json([
                'text' => 'The Overtime work has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
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
            $timestamp = $this->carbon::now();
            $details = array(
                'hro_status' => $status,
                'hro_disapproved_at' => $timestamp,
                'hro_disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->_hrovertime->updateData($id, $details);
            return response()->json([
                'text' => 'The Overtime work has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('hro_status' => $is_activeinactive);
        $this->_hrovertime->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       


    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_hrovertime->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }
  
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                //'hr_employeesid'=>'required|unique:hr_overtime,hr_employeesid,'.(int)$request->input('id'),
                'hrwc_id'=>'required',
                'hrot_work_date'=>'required',
                'hrot_start_time'=>'required',
                'hrot_end_time'=>'required',
                // 'hrot_multiplier'=>'required_if:hrwc_id,2',
                'hr_employeesid' => [
                    'required',
                    Rule::unique('hr_overtime')->where(function ($query) use ($request) {
                        return $query->where('hrot_work_date', $request->input('hrot_work_date'))
                                    ->where('id', '!=',$request->input('id'))
                                    ->whereIn('hro_status', [0,3,4,5,6]);
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

    public function approvalIndex(Request $request) {
        $this->is_permitted($this->slugs.'-approval', 'read');
            return view('HR.overtime.approvalView');
    }
    public function approvalCreate(Request $request) {
        $data = (object)$this->data;
        $arrDocuments = array();  $date = date('Y-m-d');
		$arrDaystype = array('1'=>'No','2'=>'Yes');
        $arrHrWorkCredit = config('constants.arrHrWorkCredit');
        $arrOTMultiplier = config('constants.arrOTMultiplier');
        $arrChangeSchedulestatus = $this->application_status;
        $data->applicationno ="";
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $status="";
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id;
        $data->status = 0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrovertime->getRecordforEdit($request->input('id'));
            
            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$data->department_id, 'hr-overtime-approval',$data->hro_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];

            $date = date('Y-m-d',strtotime($data->created_at));
            $arrDocuments =$this->_hrovertime->GetDocumentfiles($request->input('id'));
             
        }
            return view('HR.overtime.approvalCreate',compact('data','status','arrEmployee','arrDaystype','arrOTMultiplier','approve_btn','arrDocuments','arrHrWorkCredit','date'));
    }
}
