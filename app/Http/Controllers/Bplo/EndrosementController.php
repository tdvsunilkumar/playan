<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Exports\EndrosementList;
use App\Models\Bplo\Endrosement;
use App\Models\BfpApplicationForm;
use App\Models\CommonModelmaster;
use App\Models\BploBusinessPsic;
use App\Models\HoAppHealthCert;
use App\Models\HrEmployee;
use App\Models\HoApplicationSanitary;
use App\Models\Barangay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\CarbonPeriod;
use App\Models\BploAssessmentCalculationCommon;
use DateTime;
use Session;
use Response;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Carbon\Carbon;
use App\Models\BploBusiness;





class EndrosementController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrprofile = array(""=>"Select Owner");
    public $yeararr = array(""=>"Select Year");
    public $arrBarangay = array(""=>"Please Select");
    public $accountnos = array();
    public $nofbusscode = array(""=>"Select Code");
    public $arrOcupancy = array(""=>"Please Select");
    public $gend = ['0'=>"Male",'1'=>"Female"];   
    public $apv_status = ['0'=>"InActive",'1'=>"Active"];    
    public $arrYears = array(""=>"Select Year");
    private $slugs;
    private $slugs_health;

    public $arrbfpapplication = array(""=>"Select App");
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
	
    public $employee = array(""=>"Please Select");
    public $busn_end_status = ['0'=>"Not Started",'1'=>"In Progress",'2'=>"Completed",'3'=>"Decline"];
    public $isNational=0;
    public $endrlDeptDtls = [];
    public $currentYear;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness(); 
        $this->_Endrosement = new Endrosement(); 
        $this->_bfpapplicationform = new BfpApplicationForm();
        $this->_commonmodel = new CommonModelmaster();
        $this->_BploBusinessPsic = new BploBusinessPsic(); 
        $this->_hoapphealthcert = new HoAppHealthCert();
        $this->_barangay = new Barangay(); 
        $this->_hoappsanitary = new HoApplicationSanitary();
        $this->_hrEmployee = new HrEmployee(); 
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->data = array('id'=>'','busns_id_no'=>'','busn_name'=>'','arrAssesment'=>array(),'totalSurcharges'=>'0','totalInterest'=>'0','end_fee_name'=>'','end_tfoc_id'=>'','enddept_fee'=>'','bbendo_id'=>'','document_details'=>'','document_detailsInspection'=>'','bplo_documents'=>array(),'bend_status'=>'','documetary_req_json'=>'','bend_year'=>'','force_mark_complete'=>'0','app_type_id'=>'','payment_mode'=>'');  
        $this->slugs = 'pdo-endorsement';
        $this->slugs_health = 'healthy-and-safety/health-certificate';
        // Don't Remove This code
        $arrYrs = $this->_Endrosement->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bend_year] =$val->bend_year;
        }

        $this->currentYear=date('Y');
        $this->sanitaryData = array('id'=>'','bend_id'=>'','busn_id'=>'','has_app_year'=>'','has_app_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>'','has_expired_date'=>'','has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
        $this->reldata = array('id'=>'','req_id'=>'','req_id_abbreviation'=>'','hasr_is_complete'=>'','hasr_completed_date'=>'','hasr_remarks'=>'');

        foreach($this->_hoappsanitary->getbploApplications() as $val) {
            $this->arrbfpapplication[$val->id]=$val->ba_business_account_no;
        } 
        foreach ($this->_hoappsanitary->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_hoappsanitary->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
		$this->arrApplication_status=config('constants.arrBusinessApplicationStatus');
        // foreach ($this->_hoapphealthcert->getEmployee() as $val) {
        //     $this->employee[$val->id]=$val->fullname;
        // }
        $arrLoc = $this->_commonmodel->bploLocalityDetails();
        if(isset($arrLoc)){
            $this->isNational=($arrLoc->asment_id)==2?1:0;
        }

    }


    public function environmentalEndrosementIndex(Request $request){
        $bbendo_id =  4;
        $title =  'Environmental';
        $this->slugs = 'environmental-endorsement';
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        $currentYear = $this->currentYear;
		$application_status = $this->arrApplication_status;
        $barangay=array(""=>"Please select");
        return view('Bplo.endorsement.index',compact('bbendo_id','title','arrYears','currentYear','application_status','barangay'));
    }
    public function healtSafetyEndrosementIndex(Request $request){
        $bbendo_id =  3;
        $title =  'Health & Safety';
        $this->slugs = 'health-safety-endorsement-business-permit';
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        $currentYear = date('Y');
		$application_status = $this->arrApplication_status;
        $barangay=array(""=>"Please select");
        return view('Bplo.endorsement.index',compact('bbendo_id','title','arrYears','currentYear','application_status','barangay'));
    }
    public function pdoIndex(Request $request){
        $bbendo_id =  2;
        $title =  'Planning & Development';
        $this->slugs = 'pdo-endorsement';
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        $currentYear = $this->currentYear;
		$application_status = $this->arrApplication_status;
        $barangay=array(""=>"Please select");
        return view('Bplo.endorsement.index',compact('bbendo_id','title','arrYears','currentYear','application_status','barangay'));
    }
    public function index(Request $request){
		
        $bbendo_id =  1;
        $title =  'Fire Protection';
        $this->slugs = 'fire-protection/endorsement';
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        $currentYear = $this->currentYear;
        $barangay=array(""=>"Please select");
		$application_status = $this->arrApplication_status;
        return view('Bplo.endorsement.index',compact('bbendo_id','title','arrYears','currentYear','application_status','barangay'));
    }
    public function setPermissionSlug($id=0){
        $this->slugs ='fire-protection/endorsement';
        if($id==1){
            $this->slugs = 'fire-protection/endorsement';
        }elseif($id==2){
             $this->slugs = 'pdo-endorsement';
        }elseif($id==3){
            $this->slugs = 'health-safety-endorsement-business-permit';
        }elseif($id==4){
            $this->slugs = 'environmental-endorsement';
        }
    }
    public function getList(Request $request){
        $bbendo_id=$request->input('bbendo_id');
        $this->setPermissionSlug($bbendo_id);
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Endrosement->getList($request);
        $pageTitle = $request->input('pageTitle');
        
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){

            $sr_no=$sr_no+1;
            $actions = '';
            $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/Endrosement/store?id='.$row->id).'&bbendo_id='.$row->endorsing_dept_id.'&year='.$row->bend_year.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage '.$pageTitle.' Endorsement">

                            <i class="ti-eye text-white"></i>
                        </a>
                    </div>';

			if($bbendo_id==4 && $row->bend_status >=1 ){
                    $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/environmental-inspection-report/store?busn_id='.$row->id).'&bend_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Inspection Report"  data-title="Manage Inspection Report">
                            <i class="ti-map-alt text-white"></i>
                        </a>
                    </div>
					<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/environmental-clearance/store?busn_id='.$row->id).'&bend_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Environmental Clearance"  data-title="Manage Environmental Clearance">
                            <i class="ti-book text-white"></i>
                        </a>
                    </div>';
            }
            elseif($bbendo_id==2 && $row->bend_status >=1 ){
                    $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/locationclearance/store?busn_id='.$row->id).'&bend_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Location Clearance"  data-title="Manage Location Clearance">
                            <i class="ti-map-alt text-white"></i>
                        </a>
                    </div>';
            }
            elseif($bbendo_id==3 && $row->bend_status>=1){
                    $actions .= '<div class="action-btn bg-info ms-2">
                                    <a href="#" class="mx-3 btn btn-sm  align-items-center add_health_cert" data-url="'.url('/Endrosement/healthCertificate?id='.$row->id).'&bbendo_id='.$row->endorsing_dept_id.'&end_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Manage Health Certificate[Multiple Employees]"  data-title="Manage Health Certificate[Multiple Employees]">
                                        <i class="ti-book text-white"></i>
                                    </a>
                                </div>
                                <div class="action-btn bg-info ms-2">
                                    <a href="#" class="mx-3 btn btn-sm  align-items-center add_sanitary_cert" data-url="'.url('/Endrosement/sanitaryPermit?busn_id='.$row->id).'&end_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Manage Sanitary Permit"  data-title="Manage Sanitary Permit">
                                        <i class="ti-plus text-white"></i>
                                    </a>
                                </div>';
            }
            elseif($bbendo_id==1 && $row->bend_status>=1){
                $checkApplicationExist = $this->_Endrosement->checkApplicationExist($row->id,$row->end_id,$row->bend_year);
                $checkLocalityExist = $this->_Endrosement->checkLocalityExist();
                if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                   $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpapplicationform/store?busn_id='.$row->id).'&bend_id='.$row->end_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Application Form"  data-title="Manage Application Form">
                            <i class="ti-book text-white"></i>
                        </a>
                    </div>';
  
  
                    if($checkApplicationExist && $this->isNational){

                        $app_form_id = $checkApplicationExist;
                        $actions .= '<div class="action-btn bg-info ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fire-protection/cashiering/store?busn_id='.$row->id).'&end_id='.$row->end_id.'&year='.$row->bend_year.'&bff_id='.(int)$app_form_id.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Assessment (& Fees)"  data-title="Manage Assessment & Fees">
                            <i class="ti-clipboard text-white"></i>
                            </a>
                        </div>';
                    }
                    if($checkLocalityExist){
                    $actions .= '
                     <div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpinspectionorder/store?busn_id='.$row->id).'&bbendo_id='.$row->end_id.'&year='.$row->bend_year.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Inspection Order"  data-title="Manage Inspection Order">
                            <i class="ti-notepad text-white"></i>
                        </a>
                    </div>';
                  }
                   $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/Endrosement/application?id='.$row->id).'&bbendo_id='.$row->endorsing_dept_id.'&year='.$row->bend_year.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="After Inspection Report (AIR) Document Management"  data-title="After Inspection Report (AIR) Document Management">
                            <i class=" ti-zip text-white"></i>
                        </a>
                    </div>

                    ';
                }

                
                // $checkInspectionsExist = $this->_Endrosement->checkInspectionsExist($row->id,$row->end_id,$row->bend_year);
                $checkAssessmentsExist = $this->_Endrosement->checkAssessmentsExist($row->id,$row->end_id,$row->bend_year);
                if($checkApplicationExist && $checkAssessmentsExist){
                    if($bbendo_id==1 && $row->bend_status>=1){
                        $actions .= '<div class="action-btn bg-info ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpcertificate/store?busn_id='.$row->id).'&bend_id='.$row->endorsing_dept_id.'&year='.$row->bend_year.'"  data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="FSIC Certificate"   data-title="Manage Fire Safety Inspection Certificate" >
                                <i class="fa fa-certificate text-white"></i>

                            </a>
                         </div>';
                    }
                }
            }
            $ownar_name=$row->rpo_first_name.' '.$row->rpo_middle_name.' '.$row->rpo_custom_last_name;
            if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            }
            $startCarbon = Carbon::parse($row->start_date)->format('Y-m-d');

            if ($row->bend_completed_date != null) {
                $endCarbon = Carbon::parse($row->bend_completed_date)->format('Y-m-d');
            } else {
                $endCarbon = Carbon::now()->format('Y-m-d'); // Use Carbon to get the current date and time
            }
            //  dd($endCarbon);
            $startDate = new DateTime($startCarbon);
            $endDate = new DateTime($endCarbon);
            $diff = $startDate->diff($endDate);
            
            if ($diff->days == 0) {
                $duration = "";
            } elseif ($diff->days == 1) {
                $duration = $diff->days . " Day";
            } else {
                $duration = $diff->days . " Days";
            }
            $busn_name = wordwrap($row->busn_name, 30, "<br />\n");
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_registration_no']=$row->busns_id_no;
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['busn_name']="<div class='showLess'>".$busn_name."</div>";
			$arr[$i]['barangay'] = $row->brgy_name;
            $arr[$i]['app_type']=($row->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$row->app_code]:'';
            $arr[$i]['end_status']=config('constants.arrBusEndorsementStatus')[(int)$row->bend_status];
            $arr[$i]['created_at']=$row->created_at;
            $arr[$i]['busn_app_method']=$row->busn_app_method;
            $arr[$i]['busn_app_status']=config('constants.arrBusinessApplicationStatus')[$row->busn_app_status];
            $arr[$i]['duration']=$duration;
            $arr[$i]['barangay']=$row->brgy_name;
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
    
    public function getBarangayAjax(Request $request){
		
        $search = $request->input('search');
        $arrRes = $this->_Endrosement->getBarangayAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
	
    public function firePrint(Request $request){
            $id= $request->input('id');
            // $data = $this->_bfpinspectionorder->getPrintDetails($id);
             
            // if(count($data)>0){
            //     $data = $data[0];
            // }
            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/inspectioncertificateFire.html'));
            $logo = url('/assets/images/logo.png');
            $signeture = url('/assets/images/signeture2.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            
            $mpdf->WriteHTML($html);
            $applicantname = "inspectioncertificateFire.pdf";
            $folder =  public_path().'/uploads/certoflandholdingprint/';
            // if(!File::exists($folder)) { 
            //     File::makeDirectory($folder, 0777, true, true);
            // }
            $filename = public_path() . "/uploads/certoflandholdingprint/" . $applicantname;
            $mpdf->Output($filename, "F");
            // @chmod($filename,  0777);
            echo url('/uploads/certoflandholdingprint/' . $applicantname);
    }

    public function updateEndorsementStatus(Request $request){
        $busn_id = $request->input('id');
        $app_code = $request->input('app_code');
        $payment_mode = $request->input('payment_mode');
        $year = $request->input('year');
        $end_tfoc_id = $request->input('end_tfoc_id');
        $enddept_fee = $request->input('enddept_fee');
        $end_fee_name = $request->input('end_fee_name');
        $busEndorsementStatus = $request->input('busEndorsementStatus');
        $bbendo_id =  $request->input('bbendo_id');
        $prev_bend_status=  $request->input('prev_bend_status');
        $busn_dept_completed=0;
        $arrprev = $this->_Endrosement->getPeviouscompletedDept($busn_id);
        $arrbussData = array();
        $arr = array();
        $arr['busn_app_status']='';
        if($busEndorsementStatus==1 && $prev_bend_status!=1){
            $busn_dept_completed = $arrprev->busn_dept_completed + 1;
            $arrbussData['busn_dept_completed']=$busn_dept_completed;
        }elseif($busEndorsementStatus==2 && empty($prev_bend_status)){
            $busn_dept_completed = $arrprev->busn_dept_completed + 1;
            $arrbussData['busn_dept_completed']=$busn_dept_completed;
        }
        elseif($prev_bend_status==1 && $busEndorsementStatus==3){
            $busn_dept_completed = $arrprev->busn_dept_completed - 1;
            $arrbussData['busn_dept_completed']=$busn_dept_completed;
        }

        if($busn_dept_completed>=$arrprev->busn_dept_involved){
            Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$busn_id); // This for remote server
            $arrbussData['busn_app_status'] = $arr['busn_app_status'] = 3; // Converted to Assessesment
        }

        if($busEndorsementStatus=='restore'){
            $busEndorsementStatus=0;
        }elseif($busEndorsementStatus=='incomplete'){
            $busEndorsementStatus=1;
        }
        $data['bend_status'] = $busEndorsementStatus;
        $data['tfoc_amount'] = $enddept_fee;
        if($busEndorsementStatus == 2){
            $data['bend_completed_date'] = date('Y-m-d');
        }else{
            $data['bend_completed_date'] = null;
        }
        $this->_Endrosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
        if(count($arrbussData)>0){
            $this->_Endrosement->updateData($busn_id,$arrbussData);
            
        }
        if($busEndorsementStatus==1 || $busEndorsementStatus==2){
            $isExist = $this->_Endrosement->checkLocalGovermentFeeExist($busn_id,$year,$end_tfoc_id,$enddept_fee);
            if(!$isExist){
                $this->_assessmentCalculationCommon->calculateAssessmentDetails($busn_id,$app_code,$payment_mode,'saveData',$year,$bbendo_id);
                //$this->_assessmentCalculationCommon->updateDelinquencyDetails($busn_id,$app_code,$year);
            }
        }
        if($busEndorsementStatus==3){
            //Delete Local or National fee from assessment
            $this->_Endrosement->deleteLocalNationalAssessmentFees($busn_id,$year,$end_tfoc_id);
            $this->_assessmentCalculationCommon->saveFinalAssessmentDetails($busn_id,$app_code,$payment_mode,$year,$bbendo_id);
            //$this->_assessmentCalculationCommon->updateDelinquencyDetails($busn_id,$app_code,$year);
        }
        session()->flash('success', 'Saved successfully');
        $arr['ESTATUS']=1;
        if($busEndorsementStatus == 2 && $bbendo_id == 1){
            $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('id'));
            $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',4)->where('type_id',1)->where('is_active',1)->first();
            if(!empty($smsTemplate))
            {
                $receipient=$arrBuss->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }
            
        }
        if($busEndorsementStatus == 2 && $bbendo_id == 2){
            $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('id'));
            $smsTemplate=SmsTemplate::where('group_id',8)->where('module_id',46)->where('action_id',4)->where('type_id',1)->where('is_active',1)->first();
            if(!empty($smsTemplate))
            {
                $receipient=$arrBuss->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }
            
        }
        if($busEndorsementStatus == 2 && $bbendo_id == 4){
            $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('id'));
            $smsTemplate=SmsTemplate::where('id',15)->where('is_active',1)->first();
            if(!empty($smsTemplate))
            {
                $receipient=$arrBuss->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }
            
        }
        if($busEndorsementStatus == 2 && $bbendo_id == 3){
            $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('id'));
            $smsTemplate=SmsTemplate::where('id',18)->where('is_active',1)->first();
            if(!empty($smsTemplate))
            {
                $receipient=$arrBuss->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }
            
        }
        echo json_encode($arr);exit;
    }
    public function send($message, $receipient)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $setting = $this->componentSMSNotificationRepository->fetch_setting();
            $details = array(
                'message_type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => $this->carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
           
                //$this->sendSms($receipient, $message);
                $this->componentSMSNotificationRepository->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }

    public function store(Request $request){
        $bbendo_id =  $request->input('bbendo_id');
        $this->setPermissionSlug($bbendo_id);
        $this->is_permitted($this->slugs, 'update');
        $year=  $request->input('year');
        $arrDocumentDtls=[];
        $arrDocumentDetailsHtml=[];
        $data = (object)$this->data;
        $isNational = $this->isNational;
        $arrReq[] ="Select Requirement";

        $req_doc = $this->_BploBusiness->getBploDocReqByDept($bbendo_id);
        $arrayReqDocData = json_decode($req_doc->requirement_json, true);
        $arrFireReqDoc=array();
        if(!empty($arrayReqDocData))
        {
                foreach($arrayReqDocData as $i=>$item){
                    $arrReq[$item['requirement_id']]=$item['requirement_name'];
                }
        }           
        // foreach ($this->_Endrosement->newRequirementCode($bbendo_id) as $val) {
        //    $arrReq[$val->id]=$val->req_code_abbreviation.'-'.$val->req_description;
        // }
        $getBploBusinessStatus = $this->_Endrosement->getBploBusinessStatus($request->input('id'),$bbendo_id,$year);
        $getBploBusinessissueDate = $this->_Endrosement->getPermitIsseuDate($request->input('id'),$year);
        // print_r($getBploBusinessissueDate);exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $editDtls = $this->_Endrosement->getEditDetails($request->input('id'),$bbendo_id,$year);
            if(isset($editDtls)){
                $data = $editDtls;
            }
            $arrDocument = $this->_Endrosement->getBploDocuments($request->input('id'),$year);
            if(isset($arrDocument)){
                $arrDocumentDtls = $arrDocument;
            }
           
            $arrdocDtls = $this->generateDocumentList($data->documetary_req_json,$data->bbendo_id,$data->bend_status);
            if(isset($arrdocDtls)){
                $arrDocumentDetailsHtml = $arrdocDtls;
            }
        }
        $this->endrlDeptDtls = $this->_Endrosement->getEndorsingDept($bbendo_id);
        if(isset($this->endrlDeptDtls)){
            $data->end_fee_name = $this->endrlDeptDtls->fees;
            $data->end_tfoc_id = $this->endrlDeptDtls->tfoc_id;
        }
        $sunmary_url=url("/fire-protection/print-summary/".$request->input('id'));
        return view('Bplo.endorsement.create',compact('data','sunmary_url','arrReq','arrDocumentDtls','arrDocumentDetailsHtml','isNational','getBploBusinessStatus','getBploBusinessissueDate'));
    }
    public function print_summary(Request $request,$busn_id)
    {
        $bplo_business=$this->_BploBusiness->reload_summary($busn_id);
        $bplo_business_plan=$this->_BploBusinessPsic->reload_busn_plan($busn_id);
        $total_capitalisation=$this->_BploBusinessPsic->busn_plan_sum($busn_id);
        $data = [
                    'bplo_business' => $bplo_business, 
                    'bplo_business_plan' => $bplo_business_plan,
                    'total_capitalisation' => $total_capitalisation
                ];


        // Setup a filename 
        $documentFileName = $busn_id."-Summary.pdf";
        // Create the mPDF document
         $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '8',
            'margin_bottom' => '8',
            'margin_footer' => '2',
        ]);        
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];	
        $html = view('BploBusiness.print_new', $data)->render();
        $document->WriteHTML($html);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
        $folder =  public_path().'/uploads/summary/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder,  0777, true, true);
        }
        $filename = public_path() . "/uploads/summary/" . $documentFileName;
        $document->Output($filename, "F");
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }

    public function healthCertificate(Request $request){
        $busn_id=$request->input('id');
        $bbendo_id =  $request->input('bbendo_id');
        $year =  $request->input('year');
        $end_id =  $request->input('end_id');
        $data = $this->_Endrosement->getEditDetails($busn_id,$bbendo_id,$year);
        $healthCert =array(""=>"Please Select");
        // $data=$this->_Endrosement->getHealthCertLists($id,$bbendo_id);
        return view('Bplo.endorsement.addHealthCertificate',compact('data','healthCert','busn_id','bbendo_id','end_id'));
    }
    

    public function sanitaryPermit(Request $request){
            $user_id= \Auth::user()->id;
            $auth=$this->_hrEmployee->empIdByUserId($user_id);
            $busn_id=$request->input('busn_id');
            $end_id =  $request->input('end_id');
            $busn_plan=$this->_BploBusiness->reload_busn_plan($busn_id);
            $bplo_business=$this->_hoapphealthcert->getBusn($busn_id);
            $busn_name=$bplo_business->busn_name;
            $year=date('Y');
            $Sanitarytotal_employees ="0";
            $total_employeeHealthCard="";
            $arrDocumentDetailsHtml='';
            $has_issuance_date=Carbon::now()->format('Y-m-d');
            $has_expired_date=Carbon::now()->endOfYear()->toDateString();
            $sanitary_data=$this->_hoappsanitary->getDataByBusnId($busn_id);
            $total_employee ="";
                $total_employee = $this->_hoappsanitary->total_employees($busn_id,$year);
            if ($total_employee !== null) {
               $Sanitarytotal_employees = $total_employee->busn_employee_no_male + $total_employee->busn_employee_no_female;
            }
            $total_employeeHealthCard = $this->_hoappsanitary->totalHealthCard($busn_id,$year);
            // echo $total_employee->busn_employee_no_female; exit;
            if($sanitary_data != NUll)
            {
                $sanitary_id=$sanitary_data->id;
            }
            else{
                $sanitary_id=$request->input('id');
            }
            if(empty($sanitary_id) && $request->input('submit')=="")
            {
                $hr_emp=$this->_hrEmployee->empIdByUserId(\Auth::user()->id);
                $has_recommending_approver_position=$this->_hoapphealthcert->getPosition($hr_emp->id);
                $latest = HoApplicationSanitary::orderBy('id','DESC')->first();
                    $has_approver=NULL;
                $row = $this->_hoapphealthcert->getBusnComAddress($end_id,$busn_id);
                $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
                $owner=(!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name . ' ' : '');
                $complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
                $data = array('id'=>'','bend_id'=>$end_id,'busn_id'=>$busn_id,'has_app_year'=>$year,'has_app_no'=>'','has_transaction_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>$has_issuance_date,'has_expired_date'=>$has_expired_date,'has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
            }
            else{
                $complete_address = "";
                $owner= "";
                $data = array('id'=>'','bend_id'=>$end_id,'busn_id'=>$busn_id,'has_app_year'=>$year,'has_app_no'=>'','has_transaction_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>$has_issuance_date,'has_expired_date'=>$has_expired_date,'has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
            }
            $data = (object)$data;
              $getdatausersave = $this->_hoappsanitary->CheckFormdataExist('8',\Auth::user()->id);
                       if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $data->has_recommending_approver = $usersaved->has_recommending_approver;
                          $data->has_recommending_approver_position = $usersaved->has_recommending_approver_position;
                          $data->has_approver = $usersaved->has_approver;
                          $data->has_approver_position = $usersaved->has_approver_position;
                   }
            $reldata = array();
            $bfpapplications =$this->arrbfpapplication;
            $arrTaxClasses = $this->arrTaxClasses;
            $arrTaxTypes = $this->arrTaxTypes;
            foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                $this->employee[$val->id]=$val->fullname;
            }
            foreach ($this->_bfpapplicationform->getEmployeeUser($user_id) as $val) {
                $this->employee[$val->id]=$val->fullname;
            }
            $employee= $this->employee;
           
            $countries = array('Select Country'); $requirements = array(); $healthcertreq = array();
            
            foreach ($this->_hoappsanitary->getRequirements() as $val) {
                $requirements[$val->id]=$val->req_description;
            } 
            foreach ($this->_hoappsanitary->getCountries() as $val) {
                $countries[$val->id]=$val->country_name;
            } 
            if($sanitary_id>0 && $request->input('submit')==""){
                
                $data = HoApplicationSanitary::find($sanitary_id);
                $total_employee = $this->_hoappsanitary->total_employees($busn_id,$data->has_app_year);
            $total_employee = $this->_hoappsanitary->total_employees($busn_id,$data->has_app_year);
            if ($total_employee !== null) {
               $Sanitarytotal_employees = $total_employee->busn_employee_no_male + $total_employee->busn_employee_no_female;
            }
            $total_employeeHealthCard = $this->_hoappsanitary->totalHealthCard($busn_id,$data->has_app_year);
                if(isset($data)){
                        if($data->has_recommending_approver == $auth->id || $data->has_approver == $auth->id)
                        {
                            $user_id=0;
                            foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                                $this->employee[$val->id]=$val->fullname;
                            }
                            $employee= $this->employee;
                        }
                    $reldata = $this->_hoappsanitary->getappSanitaryReqData($sanitary_id);
                    
                    $row = $this->_hoapphealthcert->getBusnComAddress($end_id,$busn_id);
                    $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
                    $owner=(!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name . ' ' : '');
                    $complete_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
                }
               
                $arrdocDtls = $this->genSanitaryDocList($sanitary_id);
                if(isset($arrdocDtls)){
                    $arrDocumentDetailsHtml = $arrdocDtls;
                }
            }

            if(!empty($data->has_document)){
            $arrdocDtls = $this->generateDocumentListsanitry($data->has_document,$data->id);
                if(isset($arrdocDtls)){
                    $data->arrDocumentDetailsHtml = $arrdocDtls;
                }
            }
            if($request->input('submit')!=""){
                foreach((array)$this->sanitaryData as $key=>$val){
                    $this->sanitaryData[$key] = $request->input($key);
                }
                $this->sanitaryData['busn_id'] = $busn_id;
                $currentYear = $this->sanitaryData['has_app_year'];
                $apv_send=1;
                if($sanitary_id>0){
                    $ext_data=$this->_hoappsanitary->findDataById($sanitary_id);
                    $sanitary_data = HoApplicationSanitary::where('id',$sanitary_id)->first();
                    $this->sanitaryData['updated_by']=\Auth::user()->id;
                    $this->sanitaryData['updated_at'] = date('Y-m-d H:i:s');
                    $this->sanitaryData['has_status'] = $sanitary_data->has_status;
                    if($this->sanitaryData['has_recommending_approver'] == NULL)
                    {
                        $this->sanitaryData['has_recommending_approver']=$sanitary_data->has_recommending_approver; 
                    }
                    if($this->sanitaryData['has_approver'] == NULL)
                    {
                        $this->sanitaryData['has_approver']=$sanitary_data->has_approver; 
                    }   
                    if($auth->id == $sanitary_data->has_recommending_approver || $auth->id == $sanitary_data->has_approver){
                        if($request->has('has_approver_status'))
                        {
                                if($sanitary_data->has_permit_no == NULL)
                                {
                                    $newValue = sprintf('%06d', $sanitary_data->has_app_no);
                                    $this->sanitaryData['has_permit_no']=$newValue;
                                    $this->sanitaryData['has_approved_date']=date('Y-m-d H:i:s');
                                }      
                                $this->sanitaryData['has_approver_status']=1;
                        }else{
                            $this->sanitaryData['has_approver_status']=$sanitary_data->has_approver_status;
                        }
                        if($request->has('has_recommending_approver_status'))
                        {
                            $this->sanitaryData['has_recommending_approver_status']=1; 
                        }else{
                            $this->sanitaryData['has_recommending_approver_status']=$sanitary_data->has_recommending_approver_status; 
                        }
                    }else{
                        $this->sanitaryData['has_recommending_approver_status']=$sanitary_data->has_recommending_approver_status;
                        $this->sanitaryData['has_approver_status']=$sanitary_data->has_approver_status;
                    }
                   
                    $this->_hoappsanitary->updateData($sanitary_id,$this->sanitaryData);
                    $success_msg = 'Sanitary Permit updated successfully.';
                    $lastinsertid = $sanitary_id;
                        if($ext_data->has_approver_status == 1)
                        {
                            $apv_send=0;
                        }
                    }else{
                        $lastRecord = HoApplicationSanitary::where('has_app_year',$currentYear)->orderBy('id','DESC')->first();
                        if (empty($lastRecord)) {
                            $lastNumber = 0;
                        }else{
                            $lastNumber =$lastRecord->has_app_no;
                        }
                        $newNumber = $lastNumber + 1;
                        $hahc_app_no = $newNumber;
                        $newValue = sprintf('%04d-%06d', $currentYear, $newNumber);
                        $this->sanitaryData['has_app_no']=$hahc_app_no;
                        $this->sanitaryData['has_transaction_no']=$newValue;
                        $this->sanitaryData['has_status']=1;
                        $this->sanitaryData['created_by']=\Auth::user()->id;
                        $this->sanitaryData['created_at'] = date('Y-m-d H:i:s');
                    
                        $lastinsertid = $this->_hoappsanitary->addData($this->sanitaryData);
                        $success_msg = 'Sanitary Permit added successfully.';
                        $user_savedata = array();
                        $user_savedata['has_recommending_approver'] = $request->input('has_recommending_approver');
                        $user_savedata['has_recommending_approver_position'] = $request->input('has_recommending_approver_position');
                        $user_savedata['has_approver'] = $request->input('has_approver');
                        $user_savedata['has_approver_position'] = $request->input('has_approver_position');
                        $userlastdata = array();
                        $userlastdata['form_id'] = 8;
                        $userlastdata['user_id'] = \Auth::user()->id;
                        $userlastdata['is_data'] = json_encode($user_savedata);
                        $userlastdata['created_at'] = date('Y-m-d H:i:s');
                        $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                        $checkisexist = $this->_hoappsanitary->CheckFormdataExist('8',\Auth::user()->id);
                        if(count($checkisexist) >0){
                            $this->_hoappsanitary->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                        }else{
                            $this->_hoappsanitary->addusersaveData($userlastdata);
                        }
                    }
                    if($this->sanitaryData['has_approver_status'] == 1 && $apv_send == 1){
                        $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                        $smsTemplate=SmsTemplate::where('id',19)->where('is_active',1)->first();
                        if(!empty($smsTemplate))
                        {
                            $receipient=$arrBuss->p_mobile_no;
                            $msg=$smsTemplate->template;
                            $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                            $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                            $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                            $this->send($msg, $receipient);
                        }
                    }
                // return redirect()->route('hoappsanitary.index')->with('success', __($success_msg));
                return redirect()->to('health-safety-endorsement-business-permit')->with('success', __($success_msg));
            }
            
            return view('Bplo.endorsement.addSanitaryPermit',compact('data','busn_id','busn_plan','auth','end_id','busn_name','owner','employee','complete_address','countries','requirements','healthcertreq','reldata','arrDocumentDetailsHtml','Sanitarytotal_employees','total_employeeHealthCard'));
        
    }
    public function generateDocumentListsanitry($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>   
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn'  href='".asset('uploads/sanitaryReqDoc').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }

    public function formValidation(Request $request){
        
            $validator = \Validator::make(
                $request->all(), [
                    'bend_id'=>'required',
                    'has_app_year'=>'required',
                    'has_type_of_establishment'=>'required', 
                    'has_issuance_date'=>'required', 
                    'has_expired_date'=>'required',
                    'has_recommending_approver'=>'required',
                    'has_approver'=>'required',
                    'has_recommending_approver_position'=>'required',
                ],[
                    'has_app_year.required' => 'Year is Required',
                    'has_type_of_establishment.required' => 'establishment is Required',
                    'has_issuance_date.required' => 'issuance date is Required',
                    'has_expired_date.required' => 'expired date is Required',
                    'has_recommending_approver_position.required' => 'position is Required',

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
    
    public function storeHealthCert(Request $request,$id){
        $updateData=array();
        $updateData['busn_id']=$request->input('busn_id');
        $updateData['bend_id']=$request->input('end_id');
        $this->_hoapphealthcert->updateData($id,$updateData);
        return response()->json($updateData);
    }
    public function removeHealthCert(Request $request,$id){
        $timestamp = Carbon::now();
        $updateData=array();
        $updateData['busn_id']=NULL;
        $updateData['bend_id']=NULL;
        $updateData['updated_at']=$timestamp;
        $updateData['updated_by']=Auth::user()->id;
        $this->_hoapphealthcert->updateData($id,$updateData);
        return response()->json($updateData);
    }
    public function approveHealthCert(Request $request,$id){
        $this->is_permitted($this->slugs_health, 'update');
        $timestamp = Carbon::now();
        $updateData=array();
        $ret = HoAppHealthCert::find($id);
        if($ret->hahc_registration_no == NULL)
        {
            $currentYear = $ret->hahc_app_year;
            $newNumber = sprintf('%06d', $ret->hahc_app_no);
            $newValue =  $currentYear."-".$newNumber;
            $updateData['hahc_registration_no']=$newValue;
        }
        $updateData['hahc_approver_status']=1;
        $updateData['updated_at']=$timestamp;
        $updateData['updated_by']=Auth::user()->id;
        $this->_hoapphealthcert->updateData($id,$updateData);
        return response()->json($updateData);
    }

    public function apvRcmHealthCert(Request $request,$id){
        $this->is_permitted($this->slugs_health, 'update');
        $timestamp = Carbon::now();
        $updateData=array();
        $updateData['hahc_recommending_approver_status']=1;
        $updateData['updated_at']=$timestamp;
        $updateData['updated_by']=Auth::user()->id;
        $this->_hoapphealthcert->updateData($id,$updateData);
        return response()->json($updateData);
    }
    
    
    public function getSelectHealthCert(Request $request){
        $getHealthCert = $this->_Endrosement->getHealthCert();
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($getHealthCert as $key => $row) {
          $htmloption .='<option value="'.$row->id.'">'.(!empty($row->cit_first_name) ? $row->cit_first_name . ' ' : '') . (!empty($row->cit_middle_name) ? $row->cit_middle_name . ' ' : '') . (!empty($row->cit_last_name) ? $row->cit_last_name : ''). (!empty($row->cit_suffix_name) ? $row->cit_suffix_name . ' ' : '').'</option>';
        }
        echo $htmloption;
      }
    

    public function getHealthCertificateList(Request $request){
        $data=$this->_Endrosement->getHealthCertLists($request->busn_id,$request->end_id);
        $hr_emp=$this->_hrEmployee->empIdByUserId(\Auth::user()->id);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";  $z="1";  
        foreach ($data['data'] as $row){
            $hr_emp=$this->_hrEmployee->empIdByUserId(Auth::user()->id);
            // if($row->hahc_approver_status == 0 && $row->hahc_approver == $hr_emp->id)
            // {
            //     $apv='
            //     <div class="action-btn bg-success ms-2">
            //             <a href="#" title="Approve"  data-title="Approve Health Certificate" class="mx-3 btn approve btn-sm  align-items-center" id="'.$row->id.'">
            //                 <i class="ti-check text-white"></i>
            //             </a>
            //     </div>';
            // }
            // else{
            //     $apv="";  
            // }
            // if($row->hahc_recommending_approver_status == 0 && $row->hahc_recommending_approver == $hr_emp->id)
            // {
            //     $rcm_approve='
            //     <div class="action-btn bg-info ms-2">
            //             <a href="#" title="Recomending Approve"  data-title="Approve Health Certificate" class="mx-3 btn rcm_approve btn-sm  align-items-center" id="'.$row->id.'">
            //                 <i class="ti-check text-white"></i>
            //             </a>
            //     </div>';
            // }
            // else{
            //     $rcm_approve="";  
            // }
			$actions = '';
            $now = Carbon::now();
            $age = $now->diffInYears(Carbon::parse($row->cit_date_of_birth));
            $arr[$i]['srno']=$z;    
            $arr[$i]['citizen_name']= $row->cit_first_name." ". $row->cit_middle_name." ".$row->cit_last_name." ".$row->cit_suffix_name;
            $arr[$i]['hahc_app_year']=$row->hahc_app_year;
            $arr[$i]['status']=$this->apv_status[$row->hahc_status];
            $arr[$i]['gend_age']=$this->gend[$row->cit_gender]."[".$age."]";
            $arr[$i]['hahc_app_no']=$row->hahc_app_no;
            $arr[$i]['hahc_transaction_no']=$row->hahc_transaction_no;
            $arr[$i]['hahc_registration_no']=$row->hahc_registration_no;
            $arr[$i]['hahc_issuance_date']=date("M d, Y",strtotime($row->hahc_issuance_date));
            $arr[$i]['hahc_expired_date']=date("M d, Y",strtotime($row->hahc_expired_date));
            $arr[$i]['applied_date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['hahc_approver_status']=($row->hahc_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['hahc_remarks']=$row->hahc_remarks;
			if($row->hahc_approver_status){
			$print = '<div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Health Certificate"  data-title="Print Health Certificate" class="mx-3 btn print btn-sm  align-items-center" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a>
                </div>';
			}
            else{
                $print='';
            }
			$actions .= '
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/Endrosement/addHealthCertificateDoc?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Upload" data-title="Health Certificate Document Management">
                        <i class="ti-cloud-up text-white"></i>
                    </a>
                </div>
                '.$print.'
                <div class="action-btn bg-danger ms-2">
                        <a href="#" title="Remove Health Certificate"  data-title="Remove Health Certificate" class="mx-3 btn remove btn-sm  align-items-center" id="'.$row->id.'">
                            <i class="ti-trash text-white"></i>
                        </a>
                </div>';
			
			
            $arr[$i]['action']=$actions;
                
            $i++; $z++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function addHealthCertificateDoc(Request $request){
        $id=$request->input('id'); 
        $data = HoAppHealthCert::find($id);
        $arrdocDtls = $this->generateHealthDocumentList($data->hahc_document_json,$data->id);
        if(isset($arrdocDtls)){
            $arrDocumentDetailsHtml = $arrdocDtls;
        }     
        return view('Bplo.endorsement.upload_health_doc',compact('id','arrDocumentDetailsHtml'));
    
    }

    public function generateHealthDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn' href='".asset('uploads/health_certificate').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }

    

    public function uploadDocument(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('year');
        $bbendo_id =  $request->input('bbendo_id');
        $end_requirement_id =  $request->input('end_requirement_id');
        $end_requirement_name =  $request->input('end_requirement_name');

        $arrEndrosment = $this->_Endrosement->getBusinessEndorsementDetails($busn_id,$bbendo_id,$year);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->documetary_req_json,true);
            $key  = array_search($end_requirement_id, array_column($arrJson, 'requirement_id'));
            if($key !== false){
                $message="This requirement is already exist";
                $ESTATUS=1;
            }
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/document_requirement/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['requirement_id'] = $end_requirement_id;
                $arrData['requirement_name'] = $end_requirement_name;
                $arrData['business_id'] = $busn_id;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->documetary_req_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['documetary_req_json'] = json_encode($finalJsone);
                $this->_Endrosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentList($data['documetary_req_json'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }

    public function uploadSanitaryDoc(Request $request){
        $sanitary_id =  $request->input('sanitary_id');
        $end_requirement_id =  $request->input('end_requirement_id');
        $end_requirement_name =  $request->input('end_requirement_name');
        $sanitary_data = HoApplicationSanitary::where('id',$sanitary_id)->first();

        $arrSanitary= DB::table('ho_application_sanitary_req')->where('has_id',$sanitary_id)->where('req_id',$end_requirement_id)->first();
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrSanitary)){
                $message="This requirement is already exist";
                $ESTATUS=1;
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/sanitaryReqDoc/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $appsanitaryreq = array();
                        $appsanitaryreq['hasr_document'] = $filename;
                        $appsanitaryreq['has_id'] = $sanitary_id;
                        $appsanitaryreq['busn_id'] = $sanitary_data->busn_id;
                        $appsanitaryreq['bend_id'] = $sanitary_data->bend_id;
                        $appsanitaryreq['req_id'] = $end_requirement_id;
                $this->_hoappsanitary->addAppsanitaryReqlData($appsanitaryreq);
                $arrDocumentList = $this->genSanitaryDocList($sanitary_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
   
    public function generateDocumentList($arrJson,$bbendo_id, $bend_status=''){
        $html = "";
        $dclass = ($bend_status==2 || $bend_status==3)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['requirement_name']."</td>
                        <td><a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteEndrosment ti-trash text-white text-white ".$dclass."' rid='".$val['requirement_id']."' eid='".$bbendo_id."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }

    public function genSanitaryDocList($sanitary_id){
        $html = "";
        if(isset($sanitary_id)){
            $arr = $this->_hoappsanitary->getappSanitaryReqData($sanitary_id);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val->req_description."</td>
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn'  href='".asset('uploads/sanitaryReqDoc').'/'.$val->hasr_document."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteSanitaryReq ti-trash text-white text-white' sid='".$val->id."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    
    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $busn_id = $request->input('id');
        $year = $request->input('year');
        $eid = $request->input('eid');
        $arrEndrosment = $this->_Endrosement->getBusinessEndorsementDetails($busn_id,$eid,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->documetary_req_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'requirement_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['documetary_req_json'] = json_encode($arrJson);
                    $this->_Endrosement->updateBusinessEndorsement($busn_id,$eid,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
    
    public function deleteSanitaryReq(Request $request){
        $sid = $request->input('sid');
        $arrSanitary = $this->_hoappsanitary->findSanitaryReq($sid);
        if(isset($arrSanitary)){
                if($arrSanitary->hasr_document !== NULL){
                    $path =  public_path().'/uploads/sanitaryReqDoc/'.$arrSanitary->hasr_document;
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    $this->_hoappsanitary->deleteSanitaryReq($sid);
                    echo "deleted";
                }
        }
    }
    
    public function assessmentDetails(Request $request){
        $bbendo_id = $request->input('bbendo_id');
        $year = $request->input('year');
        $end_tfoc_id = $request->input('end_tfoc_id');
        $enddept_fee = $request->input('enddept_fee');
        $end_fee_name = $request->input('end_fee_name');
        $app_code= $request->input('app_code');
        $data = $this->_Endrosement->getEditDetails($request->input('id'),$bbendo_id,$year);
        $data->arrAssesment = $this->_Endrosement->getAssesmentDetails($request->input('id'),$year,$app_code);
        $data->totalInterest = 0;
        $data->totalSurcharges = 0;
        $FinalTotal=0;
        $feeExist=0;

        $endDetFeeDetails = "";
        foreach ($data->arrAssesment as $key => $val){ 
            if($end_tfoc_id==$val->tfoc_id && !$val->subclass_id && !$val->busn_psic_id){  //This is local or nation Fee
                $feeExist=1;
            }
            if($val->assess_is_interest && $val->interest_fee>0){
                $data->totalInterest += $val->interest_fee;
            }
            if($val->assess_is_surcharge && $val->surcharge_fee>0){
                $data->totalSurcharges += $val->surcharge_fee;
            }
            /*if($end_tfoc_id==$val->tfoc_id){
                $enddept_fee = $val->tfoc_amount+$enddept_fee;
                $feeExist=1;
            }else{ */
                $subclass_description = wordwrap($val->subclass_description, 25, " <br>");
                ?>
                <tr>
                    <td><?=$val->fee_name?>
                        <?php
                        if(!empty($subclass_description)){ ?>
                            <br><small class="showLess" style="font-size: 9px;">(<?=$subclass_description?>)</small><?php 
                        } ?>
                    </td>
                    <td><?=number_format($val->tfoc_amount,2);?></td>
                </tr><?php
                $FinalTotal +=$val->tfoc_amount;
            /*}*/
        } 
        $FinalTotal +=$data->totalInterest+$data->totalSurcharges;

       /* if($enddept_fee>0 && !$feeExist){ 
            $FinalTotal +=$enddept_fee;
            
            <!-- <tr>
                <td><?=$end_fee_name</td>
                <td> number_format($enddept_fee,2)</td>
            </tr> --><?php 
        }*/ ?>

        <tr>
            <td>Interest</td>
            <td><?=number_format($data->totalInterest,2)?></td>
        </tr>
        <tr>
            <td>Surcharges</td>
            <td><?=number_format($data->totalSurcharges,2)?></td>
        </tr>
        <tr>
            <th>Total</th>
            <th><?=number_format($FinalTotal,2)?></th>
        </tr><?php
    }
    public function getSizeByCap($val){
        if($val <= 150000){
            return "Micro";
        }elseif ($val >= 150001 && $val <= 5000000) {
            return "Small";
        }elseif ($val >= 5000001 && $val <= 20000000) {
            return "Medium";
        }
        elseif ($val >= 20000001) {
            return "Large";
        }
    }

    public function multi_array_key_exists($key, array $array): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        } else {
            foreach ($array as $nested) {
                if (is_array($nested) && multi_array_key_exists($key, $nested))
                    return true;
            }
        }
        return false;
    }

    public function exportreportsEndrosementlists (Request $request){
        return Excel::download(new EndrosementList($request->get('keywords')), 'Endrosementlist_sheet'.time().'.xlsx');
    }
}