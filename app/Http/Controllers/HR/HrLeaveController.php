<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrLeave;
use App\Models\HR\HrLeavetype;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use PDF;

class HrLeaveController extends Controller
{
    public $data = [];
     public $postdata = [];
    
     public function __construct(Carbon $carbon){
		$this->_leaves= new HrLeave(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array(
            'id'=>'',
            'hr_employeesid'=>'',
            'hrl_start_date'=>'',
            'hrl_end_date'=>'',
            'hrlt_id'=>'',
            'hrla_id'=>'',
            'hrla_reason'=>'',
            'remainingdays'=>'',
            'hrl_incase_special_leave_women_remarks'=>'',
            'hrl_incase_vl_special_privilege'=>'',
            'hrl_incase_vl_sp_speficy_remarks'=>'',
            'hrl_incase_sl'=>'',
            'hrl_incase_sl_specify_remarks'=>'',
            'hrl_incase_study_leave'=>'',
            'hrl_incase_others'=>'',
        );  
        $this->slugs = 'hr-leaves'; 
        foreach ($this->_leaves->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.leaves.index');
    }
   public function getList(Request $request){
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $hr_employeesid = $hr_emp->id;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_leaves->getList($request,$hr_employeesid);
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
            $arr[$i]['days']=$row->days;
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
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-leaves/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Leaves">
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
                'updated_by' => Auth::user()->id
            );
            $this->_leaves->updateData($id, $details);
            $leave = $this->_leaves->find($id)->useLeaves('minus');
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
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $arrLeavetypes = array(
            ''=> 'Please Select',
            0 => 'Leave Without Pay',
        ); 
        $status="";
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
            $status=$arrChangeSchedulestatus[$data->hrla_status];
            $date = $data->created_at;
        }
    //    dd($request->all());
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['hrla_status'] =$request->input('submit_type');
            $userdata = $this->_leaves->getUserdapartment(Auth::user()->id);
            $this->data['department_id']=$userdata->acctg_department_id;
            $this->data['days'] = 0.5;  
            if ($this->data['hrla_id'] === '1') {
            $start = Carbon::parse($this->data['hrl_start_date']);
                $end = Carbon::parse($this->data['hrl_end_date']);
                $this->data['days'] = $start->diffInDays($end) + 1;
            }
            $this->data['leave_hours'] = $this->data['days'] * 8;
            $this->data['dayswithpay'] = 0;
            if ($this->data['hrlt_id'] != '0') {
                $this->data['dayswithpay'] = $this->data['days'];
            }
            $this->data['hrl_incase_others'] = $request->hrl_incase_others ? implode(',',$request->hrl_incase_others) : '';  
            if($request->input('id')>0){
                $this->_leaves->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Leave Application updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Leave Application '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $appNumber = $this->getappNumber();
                // $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                // $applicationno = date('Y')."-".$applicationno;

                $this->data['applicationno'] = $appNumber;
                $lastinsertid = $this->_leaves->addData($this->data);
                $success_msg = 'Leave Application added successfully.';

                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Leave Application '".$this->data['hr_employeesid']."'";
            }
            if ($request->input('submit_type') === '3') {
                $leave = $this->_leaves->find($lastinsertid)->useLeaves();
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
                         $filearray['hrl_file_name'] = $documentpdf;
                         $filearray['hrl_file_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['hrl_file_path'] = 'humanresource/leaves/'.$lastinsertid;
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
        return view('HR.leaves.create',compact('data','status','arrLeavetypes','arrEmployee','validateapprove','validatenoted','validatereview','date','arrApplicationtypes','arrDocuments'));
	}
    
    public function getappNumber(){
        $number = $this->_leaves->getApplicationNumber();
        
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
                // 'hr_employeesid'=>'required|unique:hr_leaves,hr_employeesid,'.(int)$request->input('id').',id,hrl_start_date,'.$request->input('hrl_start_date'),
                // 'hrl_start_date'=>'required|date|after_or_equal:today',
                'hrl_end_date'=>'required|date|after_or_equal:hrl_start_date',
                'hrlt_id'=>'required',
                'hrla_reason'=>'required',
                'hr_employeesid' => [
                    'required',
                    Rule::unique('hr_leaves')->where(function ($query) use ($request) {
                        return $query->where('hrl_start_date', $request->input('hrl_start_date'))
                                    ->where('id', '!=',$request->input('id'))
                                    ->whereIn('hrla_status', [0,3,4,5,6]);
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
        if ($request->hrlt_id != 0 && $request->remainingdays === '0') {
            $arr['field_name'] = 'remainingdays';
            $arr['error'] = 'No more remaining Leaves';
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function print(Request $request, $id) {
        $data = HrLeave::find($id);
        PDF::SetTitle('Assistance Application Form ');    
        PDF::SetMargins(10, 10, 10,true);     
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');

        $border = 0;
        $cell_height = 5;
        // MAX WIDTH 190

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 40, $y = 10, $w = 20, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City Government of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City Hall, Brgy. Singalat, Palayan City", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('helvetica','B',12);
        PDF::MultiCell(0, 0, "APPLICATION FOR LEAVE", $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(60, 0, "1. OFFICE/DEPARTMENT", "LT", 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, "2. NAME :", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, "(Last)", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, "(First)", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "(Middle) ", 'TR', 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','',8);
        PDF::MultiCell(80, 7, $data->employee->department->name , 'L', 'L', 0, 0, '', '', true, 0, false, true, 7, 'M');
        PDF::MultiCell(40, 7, $data->employee->lastname , '', 'C', 0, 0, '', '', true, 0, false, true, 7, 'M');
        PDF::MultiCell(40, 7, $data->employee->firstname , '', 'C', 0, 0, '', '', true, 0, false, true, 7, 'M');
        PDF::MultiCell(30, 7, $data->employee->middlename , 'R', 'C', 0, 1, '', '', true, 0, false, true, 7, 'M');

        PDF::MultiCell(30, 5, "3. DATE OF FILING", 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, "<u>".Carbon::parse($data->created_at)->format('F j, Y')."</u>", 'T', 'L', 0, 0, '', '', true, 0, true);
        
        PDF::MultiCell(20, 5, "4. POSITION", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 5, "<u>".$data->employee->designation->description."</u>", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(17, 5, "5. SALARY", 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 5, "<u>".currency_format($data->appointment->hra_monthly_rate)."</u>", 'TR', 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',10);
        PDF::MultiCell(0, 0, "6. DETAILS OF APPLICATION", "LTR", 'C', 0, 1, '', '', true, 0, true);
        PDF::SetFont('helvetica','',10);
        $article_px = 6;
        $leave_type_px = 7;

        $leave_type_tbl = '<table id="leave_type_tbl" width="100%"   cellspacing="0" cellpadding="1" >
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                6.A TYPE OF LEAVE TO BE AVAILED OF
                            </td>
                            
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                
                            </td>
                            
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('VL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp;Vacation Leave <span style="font-size: '.$article_px.' px">(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('MFL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp;Mandatory/Forced Leave <span style="font-size: '.$article_px.' px">(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('SL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp;Sick Leave <span style="font-size: '.$article_px.' px">(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('ML', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Maternity Leave <span style="font-size: '.$article_px.' px">(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('PL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Paternity Leave <span style="font-size: '.$article_px.' px">(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('SPRL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Special Privilege Leave <span style="font-size: '.$article_px.' px">(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('SPL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Solo Parent Leave <span style="font-size: '.$article_px.' px">(R.A. No. 8972 / CSC MC No. 8, s. 2004)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('STL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked'))  .'&nbsp;&nbsp;&nbsp; Study Leave <span style="font-size: '.$article_px.' px">(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('VAWC', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; 10-Day VAWC Leave <span style="font-size: '.$article_px.' px">(R.A. No. 9262 / CSC MC No. 15, s. 2005)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('RPL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp; Rehabilitation Privilege <span style="font-size: '.$article_px.' px">(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('SLBW', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp; Special Leave Benefits for Women <span style="font-size: '.$article_px.' px">(R.A. No. 9710 / CSC MC No. 25, s. 2010)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('SEL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp; Special Emergency (Calamity) Leave <span style="font-size: '.$article_px.' px">(CSC MC No. 2, s. 2012, as amended)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            '.$data->checker('AL', null, null, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Adoption Leave <span style="font-size: '.$article_px.' px">(R.A. No. 8552)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                            &nbsp; Others:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px;">
                            &nbsp; _________________________________________
                            </td>
                        </tr>
                    
                </table>';

        $other_leave = '';
        foreach (config('constants.hrIncaseOther') as $other) {
            $check = in_array($other['value'],explode(',',$data->hrl_incase_others)) ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $other_leave .= '<tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                &nbsp;&nbsp;&nbsp;'. $check .'&nbsp;&nbsp;&nbsp; '.$other['name'].'
                                </td>
                            </tr>';
        }
        $leave_details_tbl = '<table id="leave_details_tbl" width="100%"   cellspacing="0" cellpadding="1">
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        6.B TYPE OF LEAVE TO BE AVAILED OF
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        <i>&nbsp;&nbsp;&nbsp;In case of Vacation/Special Privilege Leave:</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('VL', 'hrl_incase_vl_special_privilege', 0, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp; Within the Philippines _____________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('VL', 'hrl_incase_vl_special_privilege', 1, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Abroad (Specifiy) _________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 3px">
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        <i>&nbsp;&nbsp;&nbsp;In case of Sick Leave Leave:</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('SL', 'hrl_incase_sl', 0, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; In Hospital (Specify Illness) ________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('SL', 'hrl_incase_sl', 1, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; Out Patient (Specify Illness) ________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 3px">
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        <i>&nbsp;&nbsp;&nbsp;In case of Special Leave Benefits for Women:</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;(Specify Illness) _____________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 3px">
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        <i>&nbsp;&nbsp;&nbsp;In case of Study Leave</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('STL', 'hrl_incase_study_leave', 0, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')).'&nbsp;&nbsp;&nbsp; Completion of Master\'s Degree
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: '.$leave_type_px.' px">
                                    &nbsp;&nbsp;&nbsp;'.$data->checker('STL', 'hrl_incase_study_leave', 1, config('constants.checkbox.checked'), config('constants.checkbox.unchecked')) .'&nbsp;&nbsp;&nbsp; BAR/Board Examination Review
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 3px">
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        <i>&nbsp;&nbsp;&nbsp;Other purpose:</i>
                                    </td>
                                </tr>
                                '.$other_leave.'
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 3.21px">
                                    
                                    </td>
                                </tr>
                </table>';
        
                PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell(100, 0, $leave_type_tbl, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, $leave_details_tbl, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',10);
        PDF::MultiCell(0, 0, "7. DETAILS OF ACTION ON APPLICATION", 'LR', 'C', 0, 1, '', '', true, 0, true);
        PDF::SetFont('helvetica','',10);

        $leave_credits = '<table id="leave_type_tbl" width="100%"   cellspacing="0" cellpadding="1" >
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        7.A CERTIFICATION OF LEAVE CREDITS
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="30%" style="font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="left" width="65%" style="font-size: 8px">
                                        As of <u>September 21, 2023</u>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;font-size: 8px">
                                        Vacation Leave
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black; border-right: 0.5px solid black; font-size: 8px">
                                        Sick Leave
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;font-size: 8px">
                                        Total Earned
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                        '.($data->leave_code === 'VL' ? $data->days + $data->remainingdays : $data->appointment->remaining_leave('VL')).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black; border-right: 0.5px solid black; font-size: 8px">
                                        '.($data->leave_code === 'SL' ? $data->days + $data->remainingdays : $data->appointment->remaining_leave('SL')).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;font-size: 8px">
                                        Less this application
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;font-size: 8px">
                                        '.($data->leave_code === 'VL' ? $data->days : 0).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;border-right: 0.5px solid black; font-size: 8px">
                                        '.($data->leave_code === 'SL' ? $data->days : 0).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;border-bottom: 0.5px solid black; font-size: 8px">
                                        Balance
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;border-bottom: 0.5px solid black; font-size: 8px">
                                        '.($data->leave_code === 'VL' ? $data->remainingdays : 0).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="30%" style="border-top: 0.5px solid black; border-left: 0.5px solid black;border-bottom: 0.5px solid black; font-size: 8px">
                                        '.($data->leave_code === 'SL' ? $data->remainingdays : 0).'
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style="border-left: 0.5px solid black; font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="30%" style="font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="30%" style="font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style="border-top: 0.5px solid black; font-size: 8px">
                                        DUDLEY S. ROMERO
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                            
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style=" font-size: 8px">
                                        CHRMO
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>

                                </tr>
                            </table>';
        $approve_checkbox = $data->hrla_status > 2 ? config('constants.checkbox.checked') : config('constants.checkbox.unchecked');
        $disapprove_checkbox = $data->hrla_status === 1 || $data->hrla_status === 2 ? config('constants.checkbox.checked') : config('constants.checkbox.unchecked') ;
        $leave_recommendation = '<table id="leave_type_tbl" width="100%"   cellspacing="0" cellpadding="1" >
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    7.B RECOMMENDATION
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;'.$approve_checkbox .'&nbsp;&nbsp;&nbsp; For approval
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;'.$disapprove_checkbox .'&nbsp;&nbsp;&nbsp; For disapproval due to ______________________________
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" rowspan="1" align="left" width="100%" style=" font-size: 8px">
                                    
                                </td>
                            </tr>
                            <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style="border-top: 0.5px solid black; font-size: 8px">
                                        (Authorized Officer)
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                            
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="90%" style=" font-size: 8px">
                                        
                                    </td>
                                    <td colspan="1" rowspan="1" align="center" width="5%" style=" font-size: 8px">
                                        
                                    </td>

                                </tr>
        
        </table>';

        PDF::MultiCell(100, 0, $leave_credits, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, $leave_recommendation, 1, 'L', 0, 1, '', '', true, 0, true);

        $leave_approved = '<table id="leave_type_tbl" width="100%"   cellspacing="0" cellpadding="1" >
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        7.C APPROVED FOR
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________ days with pay
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________ days without pay
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________ others (Specify)
                                    </td>
                                </tr>
                                
                            </table>';
        $leave_disapproved = '<table id="leave_type_tbl" width="100%"   cellspacing="0" cellpadding="1" >
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                        7.D DISAPPROVED DUE TO
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1" rowspan="1" align="left" width="100%" style="font-size: 8px">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ________________________________________________
                                    </td>
                                </tr>
                                
                            </table>';
        PDF::MultiCell(100, 0, $leave_approved, "L", 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, $leave_disapproved, "R", 'L', 0, 1, '', '', true, 0, true);

        PDF::Cell(0, 5, "", 'LR', 1, 'C');
        PDF::Cell(0, 5, "", 'LR', 1, 'C');
        PDF::Cell(0, 5, "", 'LR', 1, 'C');

        PDF::SetFont('helvetica','B',10);

        PDF::Cell(0, 5, strtoupper(user_mayor()->fullname), 'LR', 1, 'C');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(0, 5, "CITY MAYOR", 'LRB', 1, 'C');

        //data overlap 
        $dayswoutpay = $data->days - $data->dayswithpay;
        PDF::MultiCell(15, 0, $data->dayswithpay, 0, 'C', 0, 1, 17, 188, true, 0, true);
        PDF::MultiCell(15, 0, $dayswoutpay, 0, 'C', 0, 1, 17, 192, true, 0, true);

        $other_leave_type = HrLeavetype::whereNotIn(
            'hrlt_leave_code', 
            [
                'VL',
                'SL',
                'MFL',
                'SPRL',
                'ML',
                'PL',
                'SPL',
                'STL',
                'VAWC',
                'RPL',
                'SBLW',
                'SEL',
                'AL',
            ]
        )
        ->where('id', $data->hrlt_id)
        ->first();
        $other_leave_type = $other_leave_type ? $other_leave_type->hrlt_leave_type : 'Leave Without Pay';
        PDF::MultiCell(60, 0, $other_leave_type, 0, 'L', 0, 1, 15, 128, true, 0, true);//Others
        PDF::MultiCell(55, 0, $data->checker('SL', 'hrl_incase_vl_special_privilege', 1, $data->hrl_incase_vl_special_privilege, ''), 0, 'L', 0, 1, 140, 74, true, 0, true);//abroad
        PDF::MultiCell(45, 0, $data->checker('SL', 'hrl_incase_sl', 0, $data->hrl_incase_sl_specify_remarks, ''), 0, 'L', 0, 1, 150, 84, true, 0, true);//in hospital
        PDF::MultiCell(45, 0, $data->checker('SL', 'hrl_incase_sl', 1, $data->hrl_incase_sl_specify_remarks, ''), 0, 'L', 0, 1, 150, 88, true, 0, true);//out hospital
        PDF::MultiCell(60, 0, $data->checker('SLBW', null, null, $data->hrl_incase_special_leave_women_remarks, ''), 0, 'L', 0, 1, 133, 98, true, 0, true);//out hospital

        //disapprove
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(75, 0, $data->hrla_disapproved_remarks, 0, 'L', 0, 1, 120, 187.5, true, 0, true);
        PDF::MultiCell(75, 0, '<p style="text-indent:85px">'.$data->hrla_disapproved_remarks.' </p>', 0, 'L', 0, 1, 120, 145, true, 0, true);
        

        PDF::Output('leave_application.pdf'); 
    }
    
    public function automateLeaves(){
        if (hr_policy('leave_deduction') === 'Yes') {
            $yesterday = Carbon::yesterday()->toDateString();
            // dd($yesterday);
            $yesterday = '2023-09-27';
            $this->_leaves->leaveAutoDeduct($yesterday);
        }
    }
}
