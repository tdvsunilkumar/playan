<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrOffset;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use App\Interfaces\HrInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;

class HrOffsetApprovalController extends Controller
{
    private HrInterface $hrRepository;
    public $data = [];
    public $postdata = [];
    
     public function __construct(HrInterface $hrRepository, Carbon $carbon){
        $this->hrRepository = $hrRepository;
		$this->_offset= new HrOffset(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hro_work_date'=>'','hro_id'=>'','hro_remaining_offset_hours'=>'','hro_reason'=>'');  
        $this->slugs = 'hr-offset-approval'; 
        foreach ($this->_offset->getEmployee() as $val) {
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
            return view('HR.offsetapproval.index');
    }


    public function getList(Request $request){
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $hr_employeesid = $hr_emp->id;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_offset->getListApproval($request,$hr_employeesid);
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
            $arr[$i]['hro_work_date']=$row->hro_work_date; 
            $arr[$i]['hrla_description']=$row->hrla_description;
            $arr[$i]['hro_reason']=$row->hro_reason;
            $arr[$i]['hro_status']=$arrChangeSchedulestatus[$row->hro_status];
            if(!empty($row->hro_approved_by)){
            $position = $this->_offset->Get_hrfullname($row->hro_approved_by);
            $positionname = $position->fullname;
            }
            $arr[$i]['approve']=$positionname;
            if(!empty($row->hro_reviewed_by)){
            $review = $this->_offset->Get_hrfullname($row->hro_reviewed_by);
            $reviewname = $review->fullname;
            }
            $arr[$i]['review']=$reviewname;
            if(!empty($row->hro_noted_by)){
            $noted = $this->_offset->Get_hrfullname($row->hro_noted_by);
            $notedname = $noted->fullname;
            }
            $arr[$i]['noted']=$notedname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-offset-approval/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Offset">
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
        $this->_offset->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_offset->validate_approver($this->_offset->getRecordforEdit($id)->department_id, $sequence, 'sub modules','hr-offset', Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_offset->validate_approver($this->_offset->getRecordforEdit($id)->department_id, $sequence, 'sub modules','hr-offset', Auth::user()->id);
    }


    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_offset->getRecordforEdit($id)->hro_approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $approvers = 2 ; $Status= '6';
            }
            $positionname ="";
            $position = $this->_offset->fetch_destination(Auth::user()->id);
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
            }else{
                $details['hrla_noted_by'] = Auth::user()->id;
                //$details['noted_position'] = $positionname;  
                $details['hrla_noted_at']= $this->carbon::now();
            }

            $this->_offset->updateData($id, $details);

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
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => '2',
                'hrla_disapproved_at' => $timestamp,
                'hrla_disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $offset_data = $this->_offset->getRecordforEdit($id);
            $remaining_hours = $this->_offset->chkBalanceHour($offset_data->	hr_employeesid)[0];
            $balance_hrs = $remaining_hours->hroh_balance_offset_hours;
            $used_hrs = ($offset_data->hro_id === 1) ? 8 : 4;
            if ($offset_data->hro_status > '2') {
                dd('sshs');
                $updatedata = array();
                $updatedata['hroh_balance_offset_hours'] = $balance_hrs + $used_hrs;
                $updatedata['hroh_used_offset_hours'] = $remaining_hours->hroh_used_offset_hours - $used_hrs;
                $this->_offset->updateOffserHourData($offset_data->hr_employeesid,$updatedata);
            }
            $this->_offset->updateData($id, $details);
            return response()->json([
                'text' => 'The leaves has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
     }

    public function chkOffsetHour(Request $request){
            $uid = $request->input('uid'); $hroid = $request->input('hroid');
            $avlhours = "0";  $offsethours = 0;  $dbavlhours = 0;
            if($hroid == '1'){ $offsethours = 8; } else{ $offsethours = 4; }
            $hourdata = $this->_offset->chkBalanceHour($uid);
            if(count($hourdata) > 0){
                $dbavlhours = $hourdata[0]->hroh_balance_offset_hours;
                $avlhours = $dbavlhours - $offsethours;
            }else{ $avlhours = $dbavlhours - $offsethours; }
            echo $avlhours;
    }
       
    public function store(Request $request){

        $data = (object)$this->data;
        $date = date('Y-m-d');
        $arrDocuments = array();
        $data->applicationno ="";
        $arrChangeSchedulestatus = $this->application_status;
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted=""; $status ="";
        $arrApplicationtypes = array(""=>"Select App Type");
        foreach ($this->_offset->getApplicationtypes() as $val) {
                $arrApplicationtypes[$val->id]=$val->hrla_description;
        }
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id; 
        $data->hro_status =0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_offset->getRecordforEdit($request->input('id'));
            
            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$data->department_id, $this->slugs,$data->hro_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];

            $arrDocuments =$this->_offset->GetDocumentfiles($request->input('id')); 
        }
        //echo "<pre>"; print_r($request->input('submit')); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['hro_status'] =$request->input('submit_type');
            if($request->input('id')>0){
                $this->_offset->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'HR Offset updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated HR Offset '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $appNumber = $this->getappNumber();
                $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                $applicationno = date('Y')."-".$applicationno;

                $this->data['applicationno'] = $applicationno;
                $lastinsertid = $this->_offset->addData($this->data);
                $success_msg = 'HR Offset added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added HR Offset '".$this->data['hr_employeesid']."'";
            }
            $hourdata = $this->_offset->chkBalanceHour($request->input('hr_employeesid'));
            if(count($hourdata) > 0){
                $hroid = $request->input('hroid'); $offsethours = 0; 
                if($hroid == '1'){ $offsethours = 8; } else{ $offsethours = 4; }
                $dbavlhours = $hourdata[0]->hroh_balance_offset_hours;
                $avlhours = $dbavlhours - $offsethours;
                $totalused = $hourdata[0]->hroh_used_offset_hours + $offsethours;
                $updatedata = array();
                $updatedata['hroh_balance_offset_hours'] = $avlhours;
                $updatedata['hroh_used_offset_hours'] = $totalused;
                $this->_offset->updateOffserHourData($request->input('hr_employeesid'),$updatedata);
            }
            if(isset($_POST['totalfiles'])){
             foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/offset/'.$lastinsertid;
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
                         $filearray['hro_id'] = $lastinsertid;
                         $filearray['fhro_file_name'] = $documentpdf;
                         $filearray['fhro_file_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['fhro_file_path'] = 'humanresource/offset/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_offset->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_offset->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                }
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hroffset.index')->with('success', __($success_msg));
    	}
        return view('HR.offsetapproval.create',compact('data','status','arrEmployee','approve_btn','date','arrApplicationtypes','arrDocuments'));
	}
    
    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_offset->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_offset->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->hrcos_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->hrcos_file_path."/".$arrDocumentss[0]->hrcos_file_name;
                if(File::exists($path)) { 
                    unlink($path);
                }
                $this->_offset->deleteimagerowbyid($rid); 
                echo "deleted";
            }
        }
    }

    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hr_employeesid'=>'required|unique:hr_offsets,hr_employeesid,'.(int)$request->input('id').
                ',id,hro_work_date,'.$request->input('hro_work_date'),
                'hro_work_date'=>'required',
                'hro_id'=>'required',
                'hro_remaining_offset_hours'=>'required|numeric|gte:0',
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
