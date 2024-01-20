<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploBussinessPermit;
use App\Models\BploBusinessPsic;
use App\Models\BploBusiness;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use App\Models\Barangay;
use Carbon\Carbon;
use Illuminate\Http\Response;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
class BploBussinessPermitController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrYears = array(""=>"Select Year");
    public $arrbploapplication = array(""=>"Select App");
    private $slugs;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_bplobusinesspermit = new BploBussinessPermit();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->_BploBusinessPsic = new BploBusinessPsic(); 
        $this->_BploBusiness = new BploBusiness();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','document_detailsInspection'=>'','bpi_upload_signed_permit'=>'','app_type_id'=>'','issuance_id'=>'','bpi_year'=>'','bpi_issued_date'=>'');
        $this->slugs = 'business-permit';
        $arrYrs = $this->_bplobusinesspermit->getYearDetails();
        $this->carbon = $carbon;
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bpi_year] =$val->bpi_year;
        }
    }
    public function index(Request $request)
    {   
        $arrYears = $this->arrYears;
        $this->slugs = 'business-permit';
        $this->is_permitted($this->slugs, 'read');
        return view('bplobusinesspermit.index',compact('arrYears'));
        
        
    }


     public function getList(Request $request){
        //$this->is_permitted($this->slugs, 'read');
        $data=$this->_bplobusinesspermit->getList($request);

        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $actions = '';
            
                $actions .= '<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinesspermit/store?id='.$row->id).'&year='.$row->busn_tax_year.'&issuance_id='.$row->issuance_id.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage  Permit Issuance">

                            <i class="ti-eye text-white"></i>
                        </a>
                    </div>';
           
            $serch_status =config('constants.arrBusinessApplicationStatus');
            $arr[$i]['srno']=$j;
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['full_name']=$row->full_name;
            $arr[$i]['busn_tax_year']=$row->busn_tax_year;
            $arr[$i]['busn_plate_number']=$row->busn_plate_number;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['app_type']=config('constants.arrBusinessApplicationType')[(int)$row->app_type_id];
            $arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
            $arr[$i]['busn_app_status']= $serch_status[$row->busn_app_status];
            $arr[$i]['app_method']=$row->pm_desc;
            $arr[$i]['action']=$actions;
            $i++;
        }
        $totalRecords=$data['data_cnt'];
        $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    
    public function print_summary(Request $request,$issuance_id)
    {
        $busn_id = 0;$yr =0;$app_code=0;
        $getPermitIsseuPrint = $this->_bplobusinesspermit->getPermitIsseuPrint($issuance_id);
          // print_r($getPermitIsseuPrint);exit;
        if(isset($getPermitIsseuPrint)){
            $busn_id = $getPermitIsseuPrint->busn_id;
            $yr = $getPermitIsseuPrint->bpi_year;
            $app_code=$getPermitIsseuPrint->app_type_id;
        }
        $bplo_business=$this->_BploBusiness->reload_summary($busn_id);
        $bplo_business_plan=$this->_bplobusinesspermit->reload_busn_plan($busn_id);
        // print_r($bplo_business_plan);exit;
        $bplo_business_address=$this->_BploBusiness->reload_address($busn_id);
        $arrBussDtls = $this->_bplobusinesspermit->getBusinessDetails($busn_id);
        $paymentMode=$arrBussDtls->pm_desc;
        $arrFinal = $this->_bplobusinesspermit->getFinalAssessementDetails($busn_id,$app_code,$yr);
        $prevTrans = $this->_bplobusinesspermit->checkTopPaidTransaction($busn_id,$app_code,$yr);
         // print_r($bplo_business_address);exit;   
        
        foreach ($this->_bplobusinesspermit->getBusinessType() as $val) {
         $this->BusinessType[$val->id]=$val->btype_desc;
        }
        $BusinessType =$this->BusinessType;
        $BusinessTypeData = $BusinessType[$bplo_business->btype_id];
          // print_r($BusinessTypeData);exit;
         
        $arrAssDtls = [];     
        if(count($arrFinal)>0){
            
            $subtotal=0;$surcharge=0;$interest=0;$finaltotal=0;
            foreach($arrFinal AS $key=>$final){
                $pm_id = $final->payment_mode;
                $assessment_period = $final->assessment_period;
                
                $arrAssDtlss = $this->_bplobusinesspermit->getTaxAssessementDetails($busn_id,$app_code,$yr,$pm_id,$assessment_period);

                if(isset($arrAssDtlss)){
                    $arrAssDtls = $arrAssDtlss;
                }
            // dd($arrAssDtls);exit;   
            }
        }

        $data = [
                    'bplo_business' => $bplo_business, 
                    'bplo_business_plan' => $bplo_business_plan,
                    'bplo_business_address' => $bplo_business_address,
                    'arrAssDtls' => $arrAssDtls,
                    'prevTrans'  =>$prevTrans,
                    'getPermitIsseuPrint'  =>$getPermitIsseuPrint,
                    'BusinessTypeData' =>$BusinessTypeData,
                    'paymentMode' =>$paymentMode,    
                ];


        // Setup a filename 
        $documentFileName = "SummaryPermitIssueance.pdf";
 
        // Create the mPDF document
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
 
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('BploBusiness.PrintPermitIssuance', $data)->render();
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
    public function store(Request $request){
        // $this->is_permitted($this->slugs, 'update');
        $busn_id =  $request->input('id');
        $year=  $request->input('year');
        $issuance_id = $request->input('issuance_id');
        $bend_id1="";
        $bend_id2="";
        $bend_id3="";
        $bend_id4="";
        $arrDocumentDtls=[];
        $arrDocumentDetailsHtml=[];
        $data = (object)$this->data;
        $arrReq[] ="Select Requirement";
        $bplo_business=$this->_BploBusiness->reload_summary($busn_id);
        $endorsment=$this->_bplobusinesspermit->getEndorsmentDetails($busn_id,$year);
         // print_r($endorsment);exit;
        $getEndorsmentFire=$this->_bplobusinesspermit->getEndorsmentFireProtection($busn_id,$year);
        
        foreach($getEndorsmentFire as $endorsments){
         $bend_id1=$endorsments->end_id;
         $Endbend_id=$endorsments->bendId;
        }
        $getEndorsmentPlaning=$this->_bplobusinesspermit->getEndorsmentPlaning($busn_id,$year);
         // print_r($getEndorsmentPlaning);exit;
         foreach($getEndorsmentPlaning as $Planing){
         $bend_id2=$Planing->end_id;
          
        }
        $getEndorsmentHealth=$this->_bplobusinesspermit->getEndorsmentHealth($busn_id,$year);
         // print_r($getEndorsmentPlaning);exit;
         foreach($getEndorsmentHealth as $health){
         $bend_id3=$health->end_id;
          
        }
        $getEndorsmentHealth=$this->_bplobusinesspermit->getEndorsmentHealth($busn_id,$year);
         // print_r($getEndorsmentPlaning);exit;
         foreach($getEndorsmentHealth as $health){
         $bend_id3=$health->end_id;
          
        }
        $getEnv=$this->_bplobusinesspermit->getEnv($busn_id,$year);
          // print_r($getEnv);exit;
         foreach($getEnv as $env){
         $bend_id4=$env->end_id;
          
        }
        
         $bend_Deprt_Id=1;
         // print_r($bend_id3);exit;
        $getApplicationDetails=$this->_bplobusinesspermit->getFireProtection($busn_id,$bend_Deprt_Id,$year);
         $ApplicationFireProtection = json_decode($getApplicationDetails);
          // print_r($ApplicationFireProtection);exit;
         // $getApplicationDetails=$this->_bplobusinesspermit->getApplicationDetails($busn_id,$bend_id1,$year);
         // $ApplicationFireProtection = json_decode($getApplicationDetails);
        // print_r($ApplicationFireProtection);exit;

        $getAssessmentDetails=$this->_bplobusinesspermit->getAssessmentDetails($busn_id,$bend_id1,$year);
        $Assessment = json_decode($getAssessmentDetails);
         // print_r($Assessment);exit;
        $getInspectionOrderDetails=$this->_bplobusinesspermit->getInspectionOrderDetails($busn_id,$bend_id1,$year);
        $InspectionOrder = json_decode($getInspectionOrderDetails);
        $getCertificateDetails=$this->_bplobusinesspermit->getCertificateDetails($busn_id,$bend_id1,$year);
        $Certificate = json_decode($getCertificateDetails);
        $bend_Deprt_Id2=2;
        $getPlaningDetails=$this->_bplobusinesspermit->getFireProtection($busn_id,$bend_Deprt_Id2,$year);
        $Planing = json_decode($getPlaningDetails);
        $bend_Deprt_Id2=3;
        $getHealthDetails=$this->_bplobusinesspermit->getFireProtection($busn_id,$bend_Deprt_Id2,$year);
        $healthArray = json_decode($getHealthDetails);

        // $getHealthDetails=$this->_bplobusinesspermit->getHealthDetails($busn_id,$bend_id3,$year);
        // $healthArray =""; 
        // if(isset($healthArray)){
        //     $healthArray = array_map(function ($item) {
        //     $item->hahc_document_json = json_decode($item->hahc_document_json);
        //     return $item;
        //     }, $getHealthDetails);
        // }




        
        // print_r($healthArray);exit;

        $Sanitary=$this->_bplobusinesspermit->getSanitaryDetails($busn_id,$bend_id3,$year);

        $bend_Deprt_Id2=4;
        $getEnvReport=$this->_bplobusinesspermit->getFireProtection($busn_id,$bend_Deprt_Id2,$year);
        $EnvReport = json_decode($getEnvReport);

        
        // $getEnvReport=$this->_bplobusinesspermit->getEnvReport($busn_id,$bend_id4,$year);
        // $EnvReport = json_decode($getEnvReport);
        $EnvClearance=$this->_bplobusinesspermit->getEnvClearance($busn_id,$bend_id4,$year);
        $clearance = json_decode($EnvClearance);
           // print_r($clearance);exit;  
        // $this->generateDocumentList($request->input('id'),$year);
        if($issuance_id>0 && $request->input('submit')==""){
            $data = $this->_bplobusinesspermit->getPermitIsseuPrint($issuance_id);
           
            $arrdocDtls=$this->generateDocumentList($issuance_id);
            // print_r($arrdocDtls);exit;
            if(isset($arrdocDtls)){
                $data->document_detailsInspection = $arrdocDtls;
            }
        }
        
        $sunmary_url=url("/business-permit/business-permit/".$issuance_id);
        return view('bplobusinesspermit.create',compact('data','sunmary_url','arrReq','bplo_business','endorsment','ApplicationFireProtection','InspectionOrder','Certificate','Assessment','Planing','healthArray','Sanitary','EnvReport','clearance'));
    }
    
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_bplobusinesspermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->bpi_no+1;
        }
        return $number;
    }
    public function updateBusinessPermit(Request $request){
        $user_id= \Auth::user()->id;
        $user= $this->_bplobusinesspermit->employeeData($user_id);
        $busn_id=$request->input('id');
        $bpi_year=$request->input('bpi_year');
        $issuance_id=$request->input('issuance_id');
        // $app_code= $this->_bplobusinesspermit->getbploBusinessAppCode($busn_id,$bpi_year);
        $request->input('id');
        $data = array();
        $data['business_plate_no'] = $request->input('business_plate_no');
        // $data['app_type_id']=$app_code;
        $data['bpi_remarks'] = $request->input('bpi_remarks');
        // $data['bpi_issued_by']=\Auth::user()->id;
        // $data['bpi_issued_status']=1;
        $data['bpi_issued_date']=date('Y-m-d H:i:s');
        $data['updated_by'] = $arrTrans['updated_by'] = \Auth::user()->id;
        $data['updated_at'] = $arrTrans['updated_at'] = date('Y-m-d H:i:s');
        if($issuance_id>0){
            $this->_bplobusinesspermit->updateData($issuance_id,$data);
            $busn_id=$request->input('id');
            $business_plate_no=$request->input('business_plate_no');
            $dataArray=array('busn_plate_number' => $business_plate_no);
            $this->_bplobusinesspermit->updateBploBusinessData($busn_id,$dataArray);
        }
        else{
            $data['created_by']=\Auth::user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $issueNumber = $this->getPrevIssueNumber();
            $locality=$this->_bplobusinesspermit->getLocality();
            // dd($locality);exit;
            $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
            $bpi_permit_no = date('Y')."-".$locality."000-".$cashier_issue_no;
            $bpi_date_expired = date('Y') . '-12-31';
            $data['bpi_no'] = $cashier_issue_no; 
            $data['bpi_permit_no'] = $bpi_permit_no; 
            $data['bpi_date_expired'] = $bpi_date_expired; 
            $this->_bplobusinesspermit->addData($data);
            
            $busn_id=$request->input('id');
            $business_plate_no=$request->input('business_plate_no');
            $dataArray=array('busn_plate_number' => $business_plate_no);
            $this->_bplobusinesspermit->updateBploBusinessData($busn_id,$dataArray);
        }
        $request->session()->put('REMOTE_UPDATED_BUSINESS_TABLE',$busn_id); // This for remote server
        session()->flash('success', 'Submit successfully');
        $data['ESTATUS']=1;
        echo json_encode($data);exit;
    }
    public function cancelBusinessPermit(Request $request){
        $user_id= \Auth::user()->id;
        $user= $this->_bplobusinesspermit->employeeData($user_id);
        $issuance_id = $request->input('issuance_id');
        // dd($user);exit;
        $data = array();
        $data['busn_id'] = $request->input('id');
        $data['bpi_year'] = $request->input('bpi_year');
        $data['bpi_issued_by']=\Auth::user()->id;
        $data['bpi_issued_status']=2;
        $data['bpi_issued_position']=$user->description;
        $data['updated_by'] = $arrTrans['updated_by'] = \Auth::user()->id;
        $data['updated_at'] = $arrTrans['updated_at'] = date('Y-m-d H:i:s');
        if($issuance_id>0){
            $this->_bplobusinesspermit->updateData($issuance_id,$data);
            $busn_id=$request->input('id');
            $business_plate_no=$request->input('business_plate_no');
            $dataArray=array('busn_app_status'=>8);
            $this->_bplobusinesspermit->updateBploBusinessData($busn_id,$dataArray);
            Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$busn_id); // This for remote server
        }
        
        session()->flash('success', 'Cancel successfully');
        $data['ESTATUS']=1;
        echo json_encode($data);exit;
    }
    public function approverBusinessPermit(Request $request){
        $user_id= \Auth::user()->id;
        $user= $this->_bplobusinesspermit->employeeData($user_id);
        $issuance_id = $request->input('issuance_id');
        $app_code = $request->input('app_type_id');
        // dd($user);exit;
        $data = array();
        $data['busn_id'] = $request->input('id');
        $data['bpi_year'] = $request->input('bpi_year');
        $data['bpi_issued_by']=\Auth::user()->id;
        $data['bpi_issued_status']=1;
        $data['bpi_issued_position']=$user->description;
        $data['bpi_issued_date']=date('Y-m-d H:i:s');
        $data['updated_by'] = $arrTrans['updated_by'] = \Auth::user()->id;
        $data['updated_at'] = $arrTrans['updated_at'] = date('Y-m-d H:i:s');
        if($issuance_id>0){
            $this->_bplobusinesspermit->updateData($issuance_id,$data);
            $busn_id=$request->input('id');
            $business_plate_no=$request->input('business_plate_no');
            $dataArray=array('busn_app_status'=>6);
            $this->_bplobusinesspermit->updateBploBusinessData($busn_id,$dataArray);
            Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$busn_id); // This for remote server
        }
        
        session()->flash('success', 'Approved successfully');
        $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('id'));
        $smsTemplate=SmsTemplate::where('group_id',2)->where('module_id',4)->where('action_id',3)->where('type_id',1)->where('is_active',1)->first();
        if(!empty($smsTemplate))
            {
                $receipient=$arrBuss->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }
        $data['ESTATUS']=1;
        echo json_encode($data);exit;
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

    public function generateDocumentList($issuance_id){
        $getPermitIsseuPrint = $this->_bplobusinesspermit->getPermitIsseuPrint($issuance_id);
        $html = "";
        if($getPermitIsseuPrint==''){

        }else{
            if($getPermitIsseuPrint->bpi_upload_signed_permit){
              $html = "<tr >
                  <td style='border:1px solid #ccc;'>".$getPermitIsseuPrint->bpi_upload_signed_permit." </td>
                  <td style='border:1px solid #ccc;'><a class='btn' href='".asset('uploads/document_requirement').'/'.$getPermitIsseuPrint->bpi_upload_signed_permit."' target='_blank'><i class='ti-download'></i></a></td>
                   <td style='border:1px solid #ccc;'>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' issuance_id='".$issuance_id."'></a>
                        </div>
                    </td>
                </tr>";  
            }
            
           
        return $html;
        }
        
    }
    
    public function deleteAttachment(Request $request){
        $issuance_id = $request->input('issuance_id');
        $arrEndrosment = $this->_bplobusinesspermit->getPermitIsseuPrint($issuance_id);
        if(isset($arrEndrosment)){
            $filename = $arrEndrosment->bpi_upload_signed_permit;
                   $data['bpi_upload_signed_permit'] = "";
                   $this->_bplobusinesspermit->updateDataAttachment($issuance_id,$data);
                    echo "deleted";
        }
             
    }

    public function uploadAttachment(Request $request){
        $issuance_id =  $request->input('issuance_id');
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/document_requirement/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                
                $data['bpi_upload_signed_permit'] = $filename;
                $this->_bplobusinesspermit->updateDataAttachment($issuance_id,$data);
                 $arrDocumentList = $this->generateDocumentList($issuance_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ba_code'=>'required',
                'bbp_year'=>'required', 
                'bbp_record_no'=>'required', 
                'bbp_date_expired'=>'required',
                'ba_registered_date'=>'required',
                'bbp_approved_date'=>'required'
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
    public function getPermitIsseuDetails(Request $request){
        $issuance_id= $request->input('issuance_id');
        $data = $this->_bplobusinesspermit->getPermitIsseuDetails($issuance_id);
        // dd($data);exit;
        echo json_encode($data);
    }
    public function getbploappdetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_bplobusinesspermit->getBploApplictaiondetails($id);
        echo json_encode($data);
    }


    public function bplobusinesspermitPrint(Request $request){
    	    $id= $request->input('id'); 
            $data = $this->_bplobusinesspermit->getBussinessPermitdata($id);
          
            //echo "<pre>"; print_r($data); exit;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/mayorspermit.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{OWNERNAME}}',$data->p_complete_name_v1, $html);
            $html = str_replace('{{PERMITNO}}',$data->bbp_permit_no, $html);
            $businessaddress = $data->brgy_name.",".$data->ba_address_house_lot_no.", ".$data->ba_address_street_name;
            $html = str_replace('{{BUSINESSADDRESS}}',$data->ba_business_name, $html);
            $date = date("F d, Y",strtotime($data->bbp_approved_date));
            $expirydate = date("F d, Y",strtotime($data->bbp_date_expired));
            $html = str_replace('{{DATEAPPROVED}}',$date, $html);
            $html = str_replace('{{DATEEXPIRED}}',$expirydate, $html);
            // $html = str_replace('{{SIGN}}',$sign, $html);
            // $html = str_replace('{{TABLE1}}',$table1, $html);
            // $html = str_replace('{{TABLE2}}',$table2, $html);
            // $html = str_replace('{{TABLE3}}',$table3, $html);
            $mpdf->WriteHTML($html);
            $filename = str_replace(' ','', $data->p_complete_name_v1);
            //$filename = "bplobusinesspermit.pdf";
            $applicantname = date('ymdhis').$filename."bplobusinesspermit.pdf";
            $folder =  public_path().'/uploads/bplobusinesspermit/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/bplobusinesspermit/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/bplobusinesspermit/' . $applicantname);
    }
}
