<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrOfficialWork;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use App\Interfaces\HrInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;

class HrOfficialWorkApprovalController extends Controller
{
    private HrInterface $hrRepository;
    public $data = [];
    public $postdata = [];
    public $arrWorktype = array(""=>"Please Select");
     public function __construct(HrInterface $hrRepository, Carbon $carbon){
        $this->hrRepository = $hrRepository;
		$this->_hrofficialwork= new HrOfficialWork(); 
		$this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','hr_employeesid'=>'','hrow_work_date'=>'','hrwt_id'=>'','hrow_time_in'=>'','hrow_time_out'=>'','hrow_reason'=>'');  
        $this->slugs = 'hr-offwork-approval'; 
        foreach ($this->_hrofficialwork->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
        foreach ($this->_hrofficialwork->getWorkType() as $val) {
            $this->arrWorktype[$val->id]=$val->hrwt_description;
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
            //$this->is_permitted($this->slugs, 'read');
            return view('HR.officialworkapproval.index');
    }


   public function getList(Request $request){
        //$this->is_permitted($this->slugs, 'read');
        $data=$this->_hrofficialwork->getListApproval($request);
        // $arrWorktype = config('constants.arrHrWorkType');
        $arrChangeSchedulestatus = config('constants.arrChangeSchedulestatus');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;   
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1; $positionname =""; $reviewname =""; $notedname ="";
            $arr[$i]['srno']=$sr_no;
            $status =($row->hrow_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['applicationno']=$row->applicationno;
            $arr[$i]['hrow_work_date']=$row->hrow_work_date; 
            $arr[$i]['hrwt_id']= $row->hrwt_description;
            $arr[$i]['hrow_time_in']= date("h:i a", strtotime($row->hrow_time_in));
            $arr[$i]['hrow_time_out']= date("h:i a", strtotime($row->hrow_time_out));
            $arr[$i]['hrow_reason']=$row->hrow_reason;
            $arr[$i]['hrow_status']=$arrChangeSchedulestatus[$row->hrow_status];
            if(!empty($row->hrow_approved_by)){
            $position = $this->_hrofficialwork->Get_hrfullname($row->hrow_approved_by);
            $positionname = $position->fullname;
            }
            $arr[$i]['approve']=$positionname;
            if(!empty($row->hrow_reviewed_by)){
            $review = $this->_hrofficialwork->Get_hrfullname($row->hrow_reviewed_by);
            $reviewname = $review->fullname;
            }
            $arr[$i]['review']=$reviewname;
            if(!empty($row->hrow_noted_by)){
            $noted = $this->_hrofficialwork->Get_hrfullname($row->hrow_noted_by);
            $notedname = $noted->fullname;
            }
            $arr[$i]['noted']=$notedname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-offwork-approval/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Holidays">
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
        $this->_hrofficialwork->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function validate_approveredit($id, $sequence)
    {
        return $this->_hrofficialwork->validate_approver($this->_hrofficialwork->getRecordforEdit($id)->department_id, $sequence, 'sub modules', 'hr-official-work', Auth::user()->id);
    }

     public function validate_approver($id, $sequence)
    {
        return $this->_hrofficialwork->validate_approver($this->_hrofficialwork->getRecordforEdit($id)->department_id, $sequence, 'sub modules', 'hr-official-work', Auth::user()->id);
    }


    public function approve(Request $request)
    {   $id = $request->input('id');
        $sequence = $request->input('sequence');
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->_hrofficialwork->getRecordforEdit($id)->hrow_approved_by == NULL) {
                $approvers = 0 ; $Status= '4';
            } else if($sequence=='2'){
                $approvers = 1 ; $Status= '5';
            }else if($sequence=='3'){
                $approvers = 2 ; $Status= '6';
            }
            $positionname ="";
            $position = $this->_hrofficialwork->fetch_destination(Auth::user()->id);
            $positionname = $position->description;
            $timestamp = $this->carbon::now();
            $details = array(
                'hrow_status' =>$Status,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if($sequence=='1'){
                $details['hrow_approved_by'] = Auth::user()->id;
                //$details['approvedbyposition'] = $positionname; 
                 $details['hrow_approved_at']= $this->carbon::now(); 
            }else if($sequence=='2'){
                $details['hrow_reviewed_by'] = Auth::user()->id;
                //$details['reviewed_position'] = $positionname; 
                $details['hrow_reviewed_at']= $this->carbon::now(); 
            }else{
                $details['hrow_noted_by'] = Auth::user()->id;
                //$details['noted_position'] = $positionname; 
                $details['hrow_noted_at']= $this->carbon::now();
            }

            $this->_hrofficialwork->updateData($id, $details);

            return response()->json([
                'text' => 'The Official work has been successfully approved.',
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
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->_hrofficialwork->updateData($id, $details);
            return response()->json([
                'text' => 'The The Official work has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $arrDocuments = array();  $date = date('Y-m-d');
        // $arrWorktype = config('constants.arrHrWorkType');
        $arrWorktype =  $this->arrWorktype;
        $arrChangeSchedulestatus = $this->application_status;
        $data->applicationno =""; $status ="";
        $arrEmployee = $this->employee;  $validateapprove=""; $validatereview=""; $validatenoted="";
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $data->hr_employeesid = $hr_emp->id;
        $data->hrow_status = 0;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrofficialwork->getRecordforEdit($request->input('id'));
            
            $approve_btn = $this->hrRepository->approveButton(Auth::user()->id,$data->department_id, $this->slugs,$data->hrow_status);
            $status=$arrChangeSchedulestatus[$approve_btn['status']];

            $date = date('Y-m-d',strtotime($data->created_at));
            $arrDocuments =$this->_hrofficialwork->GetDocumentfiles($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $userdata = $this->_hrofficialwork->getUserdapartment(Auth::user()->id);
            $this->data['department_id']=$userdata->acctg_department_id;
            if($request->input('id')>0){
                $this->_hrofficialwork->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Official Work updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Official Work '".$this->data['hr_employeesid']."'"; 
            }else{
            	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $appNumber = $this->getappNumber();
                $applicationno = str_pad($appNumber, 5, '0', STR_PAD_LEFT);
                $applicationno = date('Y')."-".$applicationno;

                $this->data['applicationno'] = $applicationno;
                $lastinsertid = $this->_hrofficialwork->addData($this->data);
                $success_msg = 'Official Work added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Official Work '".$this->data['hr_employeesid']."'";
            }
            //echo "<pre>"; print_r($request->file('documents')); exit;
             foreach ($_POST['totalfiles'] as $key => $value){  
                       if(isset($request->file('documents')[$key])){     
                         if($image = $request->file('documents')[$key]){
                          $destinationPath =  public_path().'/uploads/humanresource/officialwork/'.$lastinsertid;
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
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['fhow_file_path'] = 'humanresource/officialwork/'.$lastinsertid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['fileid'][$key])){
                            $this->_hrofficialwork->UpdateDocumentFilesData($_POST['fileid'][$key],$filearray);
                         }else{ $this->_hrofficialwork->AddDocumentFilesData($filearray); }
                     
                        }
                     }
                }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrofficialwork.index')->with('success', __($success_msg));
    	}
        return view('HR.officialworkapproval.create',compact('data','arrEmployee','approve_btn','arrDocuments','arrWorktype','date','status'));
	}

    public function getappNumber(){
        $number=1;
        $arrPrev = $this->_hrofficialwork->getApplicationNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        return $number;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $arrDocumentss = $this->_hrofficialwork->GetDocumentfilebyid($rid);
        if(count($arrDocumentss) > 0){
            if($arrDocumentss[0]->hrcos_file_name){
                $path =  public_path().'/uploads/'.$arrDocumentss[0]->hrcos_file_path."/".$arrDocumentss[0]->hrcos_file_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_hrofficialwork->deleteimagerowbyid($rid); 
              
                echo "deleted";
            }
        }
    }
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hr_employeesid'=>'required|unique:hr_official_works,hr_employeesid,'.(int)$request->input('id'),
                'hrwt_id'=>'required',
                'hrow_work_date'=>'required',
                'hrow_time_in'=>'required',
                'hrow_time_out'=>'required',
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
