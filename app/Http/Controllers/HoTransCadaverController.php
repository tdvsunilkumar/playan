<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\HoDeceasedCert;
use App\Models\HealthSafetySetupDataService;
use App\Models\BploBusiness;
use App\Models\RequestPermit;
use App\Models\Barangay;
use App\Models\BploBusinessPsic;
use App\Models\HrEmployee;
use App\Models\BfpApplicationForm;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\CarbonPeriod;
use PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DB;
class HoTransCadaverController extends Controller
{
    public $data = [];
    public $getcitizens = array(""=>"Please Select");
    public $getbrgy = array(""=>"Please Select");
    public $arrgetdeathplace = array(""=>"Please Select");
    public $gethealthofficer = array(""=>"Please Select");
    public $getformtype = array(""=>"Please Select");
    public $getrelation = array(""=>"Please Select");
    private $slugs;
    
    public function __construct(){
        $this->slugs = 'civil-registrar/permits';
        $this->_hodeceasedcert = new HoDeceasedCert();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','requester_id'=>'','issue_date'=>'','brgy_id'=>'','form_type'=>'','relation_type'=>'','or_no'=>'','health_officer_id'=>'','is_approved'=>'','health_officer_position'=>'','deceased_id'=>'','place_of_death_id'=>'','transfer_location'=>'','death_date'=>'','transfer_add_id'=>'','req_permit_id'=>'');
        foreach ($this->_hodeceasedcert->getCitizenId() as $val) {
            $this->getcitizens[$val->id]=$val->cit_fullname;
       }
        foreach ($this->_hodeceasedcert->gethelofficerId() as $val) {
            $this->gethealthofficer[$val->id]=$val->fullname;
        }
        foreach ($this->_hodeceasedcert->getFormTypeId() as $val) {
            $this->getformtype[$val->id]=$val->ho_service_name;
        }
    }
    public function index(Request $request)
    {   
            return view('hotranscadaver.index');
    }
    public function getList(Request $request){
        $data=$this->_hodeceasedcert->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
             $actions = '';
             if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                 $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/civil-registrar/permits/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit" >
                        <i class="ti-pencil text-white"></i>
                    </a>
                 </div>';
             }
			 if($row->top_transaction_type_id == 43){
             $actions .='<div class="action-btn bg-info ms-2">
                 <a href="'.url('/civil-registrar/permits/print-transfer-cadaver/'.$row->id).'" target="_blank" class="mx-1 btn btn-sm  align-items-center" title="Print"  data-title="Print"><i class="ti-printer text-white" ></i></a>
                 </div>';
			 }elseif($row->top_transaction_type_id == 41){
				$actions .='<div class="action-btn bg-info ms-2">
                 <a href="'.url('/civil-registrar/permits/print-transfer-remain/'.$row->id).'" target="_blank" class="mx-1 btn btn-sm  align-items-center" title="Print"  data-title="Print"><i class="ti-printer text-white" ></i></a>
                 </div>'; 
			 }elseif($row->top_transaction_type_id == 40){
				 $actions .='<div class="action-btn bg-info ms-2">
                 <a href="'.url('/civil-registrar/permits/print-open-niche/'.$row->id).'" target="_blank" class="mx-1 btn btn-sm  align-items-center" title="Print"  data-title="Print"><i class="ti-printer text-white" ></i></a>
                 </div>';
			 }else{
				 
			 }
			 
             if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                 $actions .=($row->status == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                     '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
             }

            $arr[$i]['no']=$j;
            $arr[$i]['service_name']=$row->ho_service_name;
            $arr[$i]['fullname']=$row->cit_fullname;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['issue_date']=Carbon::parse($row->issue_date)->format('M d, Y');
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $bt_is_activeinactive);
        $this->_hodeceasedcert->updateActiveInactive($id,$data);
    }  

    public function store(Request $request){
		
		$getcitizens  = $this->getcitizens;
        $getformtype  = $this->getformtype;
        $gethealthofficer  = $this->gethealthofficer;
        $getrelation  = $this->getrelation;
		// $or_no=$this->generateOrNumber("00");
        // $this->data['or_no']= $or_no;
		$user_savedata = array();
		$user_savedata['health_officer_id']       = $request->input('health_officer_id');
		$user_savedata['health_officer_position'] = $request->input('health_officer_position');
		if($request->input('id')>0){
			$user_savedata['hodeceasedcert_id']       = $request->input('id');
		}else{
			$user_savedata['hodeceasedcert_id']       = $request->id;	
		}
		$userlastdata = array();
		$userlastdata['form_id'] = 39;
		$userlastdata['user_id'] = \Auth::user()->id;
		$userlastdata['is_data'] = json_encode($user_savedata);
		$userlastdata['created_at'] = date('Y-m-d H:i:s');
		$userlastdata['updated_at'] = date('Y-m-d H:i:s');
		$checkisexist = $this->_hodeceasedcert->CheckFormdataExist('34',\Auth::user()->id);
		if(!empty($checkisexist[0]->is_data)){
			$last_user_data = json_decode($checkisexist[0]->is_data);
		}else{
			$aaaa= json_encode($user_savedata);
			$last_user_data = json_decode($aaaa);
		}
		
		foreach ($this->_hodeceasedcert->getBarangay() as $val) {
            $arrgetdeathplace[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc." ".$val->reg_region;
        }
        $getdeathplace  = $arrgetdeathplace;
		
		if (($request->input('permit_id'))) {
			$permit= RequestPermit::where('id',$request->input('permit_id'))->select('*')->first();
			$this->data['req_permit_id'] = $permit->id;
			$this->data['requester_id'] = $permit->requestor_id;
			$this->data['brgy_id'] = $permit->brgy_id;
			$this->data['or_no'] = $permit->or_no;
			$this->data['issue_date'] = $permit->request_date;
			$this->data['permit'] = $permit;
			$this->data['service'] = HealthSafetySetupDataService::find($request->input('service_id'));
			$this->data['form_type'] = $request->input('service_id');
		}
        $permit_data = $this->_hodeceasedcert->where([['req_permit_id',$request->input('permit_id')],['form_type',$request->input('service_id')]])->first();
        if ($permit_data) {
            $this->data = $permit_data;
            $this->data['top_trans_type'] = HoDeceasedCert::getTopTransId($permit_data->form_type);
        }

        $data = (object)$this->data;
        $adddeceasedata = array();
        $adddeceasedata = $this->_hodeceasedcert->getAddDeceasecadaverData($request->input('id'));

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoDeceasedCert::find($request->input('id'));
            //dd(HoDeceasedCert::getTopTransId($data->form_type));
            $data->top_trans_type = HoDeceasedCert::getTopTransId($data->form_type);
        }
		
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
                if ($key='transfer_add_id') {
                    # code...
                }
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['status'] = 1;
            if($request->input('id')>0){
                // dd($this->data);
                $this->_hodeceasedcert->updateData($request->input('id'),$this->data);
				$checkisexist = $this->_hodeceasedcert->CheckFormdataExist('39',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_hodeceasedcert->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_hodeceasedcert->addusersaveData($userlastdata);
                }
                $success_msg = 'Civil Registrar Permits updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $lastinsertid = $this->_hodeceasedcert->addData($this->data);
				$checkisexist = $this->_hodeceasedcert->CheckFormdataExist('39',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_hodeceasedcert->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_hodeceasedcert->addusersaveData($userlastdata);
                }
                $success_msg = 'Civil Registrar Permits added successfully.';
                
            }
            if(!empty($_POST['dec_id']))
            {
                $cit_id=1;
                $loop = count($_POST['dec_id']); 
                $adddeceasedata = array();
                    for($i=0; $i < $loop;$i++){
                        $adddeceasedata['deceased_cert_id'] = $lastinsertid;
                    
                        $adddeceasedata['cit_id'] = $cit_id;
                        $adddeceasedata['death_date'] = $_POST['death_date_data'][$i];
                        $adddeceasedata['status'] = $_POST['status'][$i];
                        if(!empty($_POST['deceaseid'][$i])){
                            $this->_hodeceasedcert->updateDeceasecadaverData($_POST['deceaseid'][$i],$adddeceasedata);
                        }else{
                            $this->_hodeceasedcert->addDeceasecadaverData($adddeceasedata);
                        }
                    }
            }
			
            return redirect()->back()->with('success', __($success_msg));	
        }
		if($data->health_officer_id > 0){ 
            $is_approveduser = $this->_hodeceasedcert->selectHRemployees($data->health_officer_id); 
        }else{
            $is_approveduser = 0;
        }
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
        return view('hotranscadaver.create',compact('last_user_data','getcitizens','getrelation','getformtype','gethealthofficer','getdeathplace','adddeceasedata','data','is_approveduser'));
    }
	public function generateOrNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_deceased_cert')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->or_no;
            } else {
              $last_booking='00';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                        $last_booking='00';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 4, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
    public function deleteCertificateReq(Request $request,$id){
        $this->_hodeceasedcert->deleteCertificateReq($id);
        
    }

    public function getCitizenDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_hodeceasedcert->getCitizenDetails($id);
       
        $brgy_id=$data->brgy_name. '-' .$data->mun_desc;
        $details=[
                    'brgy_id' => $brgy_id,
        ];
        echo json_encode($details);
    }

    public function getPosition(Request $request){
    	$id= $request->input('id');
        $data = $this->_hodeceasedcert->getPosition($id);
        $details=[
            'health_officer_position' => $data
            ];
        echo json_encode($details);
    }
    
    public function openNichePrint(Request $request, $id)
    {

        $data = HoDeceasedCert::find($id);
        // dd($data);
        PDF::SetTitle('Permit to Open Niche PDF');    
        PDF::SetMargins(15, 15, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        $font_size = 12;
        $border = 0;
        PDF::SetFont('helvetica','',$font_size);
        // $cell_height = 5;
        // 185.9 max width

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 92.5, $y = 15, $w = 30, $h = 0, $type = 'PNG');
        PDF::ln(35);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "CITY OF PALAYAN", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "OFFICE OF THE CITY HEALTH OFFICER", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        PDF::SetFont('helvetica','',15);
        PDF::MultiCell(0, 0, "<u>PERMIT TO OPEN NICHE</u>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::SetFont('helvetica','',$font_size);
        PDF::MultiCell(0, 0, "TO WHOM IT MAY CONCERN:", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);
        
        
        // PDF::MultiCell(15, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        $requestor = strtoupper($data->requestor->cit_fullname);
        $deceased = strtoupper($data->deceased->cit_fullname);
        $relation = strtoupper($data->relation_type);        
        $death_date = Carbon::parse($data->death_date)->format('jS \\d\\a\\y \\of F Y');
        $issue_date = Carbon::parse($data->issue_date)->format('jS \\d\\a\\y \\of F Y');
        $req_brgy = $data->brgy_add->brgy_name;
        $req_mun = $data->brgy_add->municipality->mun_desc;
		
		if(isset($data->transfer_add_id)){
			$death_brgy = ($data->death_add->brgy_name);
			$death_mun = ($data->death_add->municipality->mun_desc);
			$death_prov = ($data->death_add->province->prov_desc);
		}else{
			$death_brgy='';
			$death_mun='';
			$death_prov='';
			
		}
        

        // dd($data->brgy_add->municipality->mun_desc);
        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Permit is hereby granted to <u><b>".$requestor."</b></u> (requesting party) of Barangay ".$req_brgy.", ".$req_mun." City to open the grave of late <u><b>".$deceased."</b></u>, died on <u><b>". $death_date ."</b></u> located at Barangay ".$death_brgy.", ".$death_mun." City", 0, 'J', 0, 1, '', '', true, 0, true);
        // PDF::MultiCell(0, 0, ", and enter the remain of late ALBERT U. LUTAP, died on MARCH 22, 2023", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "<b>Death certificate here to attached:</b>", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This Certification being issued upon request of <u><b>".$requestor." ($relation)"."</b></u>  for her any legal purposes.", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this <u><b>".$issue_date."</b></u>, here at Palayan City, Nueva Ecija.", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(45);

        $health_officer = strtoupper($data->employee->fullname);
        // dd($health_officer);
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$health_officer."</b>", 0, 'C', 0, 1, '', '', true, 0, true);
        // PDF::Cell(0,0,$health_officer,0,1,'C');
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(0,0,$data->health_officer_position,0,0,'C');


        //PDF::Output('Permit_Open_Niche.pdf');
		$filename ='Permit_Open_Niche'.$id.'.pdf';
		
		$arrSign= $this->_commonmodel->isSignApply('civil_registrar_permit_open_niche_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('civil_registrar_permit_open_niche_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->employee->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
		
        if($isSignVeified==1 && $signType==2 && $data->is_approved ==1){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->is_approved ==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        PDF::Output($filename,"I");
    }

    public function transferCadaverPrint(Request $request, $id)
    {
        $data = HoDeceasedCert::find($id);

        PDF::SetTitle('Transfer Of Cadaver PDF');    
        PDF::SetMargins(15, 15, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',11);

        $font_size = 12;
        $border = 0;
        PDF::SetFont('helvetica','',$font_size);
        // $cell_height = 5;
        // 185.9 max width

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 92.5, $y = 15, $w = 30, $h = 0, $type = 'PNG');
        PDF::ln(35);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "CITY OF PALAYAN", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "OFFICE OF THE CITY HEALTH OFFICER", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('helvetica','',15);
        PDF::MultiCell(0, 0, "<u>TRANSFER OF CADAVER</u>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        $requestor = strtoupper($data->requestor->cit_fullname);
        $deceased = strtoupper($data->deceased->cit_fullname);
        $relation = strtoupper($data->relation_type);        
        $death_date = Carbon::parse($data->death_date)->format('jS \\d\\a\\y \\of F Y');
        $issue_date = Carbon::parse($data->issue_date)->format('jS \\d\\a\\y \\of F Y');
        $req_brgy = $data->brgy_add->brgy_name;
        $req_mun = $data->brgy_add->municipality->mun_desc;	
		
		if(isset($data->transfer_add_id)){
			$death_brgy = strtoupper($data->death_add->brgy_name);
			$death_mun = strtoupper($data->death_add->municipality->mun_desc);
			$death_prov = strtoupper($data->death_add->province->prov_desc);
		}else{
			$death_brgy='';
			$death_mun='';
			$death_prov='';
			
		}
        // $transfer_location = strtoupper($data->transfer_location);
        $transfer_location = strtoupper(($data->transfer_location)?$data->transfer_location:" ");
        if(isset($data->transfer_add_id)){
			$transfer_brgy = strtoupper($data->transfer_add->brgy_name);
			$transfer_mun = strtoupper($data->transfer_add->municipality->mun_desc);
			$transfer_prov = strtoupper($data->transfer_add->province->prov_desc);
		}else{
			$transfer_brgy ='';
			$transfer_mun ='';
			$transfer_prov ='';
		}

        $age_died = Carbon::parse($data->requestor->cit_date_of_birth)->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]) . ' old';

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that <b><u>".$deceased.",</u></b> <b><u>".$age_died."</u></b> died at BARANGAY ".$death_brgy.", ".$death_mun.", ".$death_prov, 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In lieu, the cadaver of remain of Late ".$deceased." is authorized to travel to ".$transfer_location." ".$transfer_brgy.", ".$transfer_mun.", ".$transfer_prov, 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "For the cause of death; Death certificate here to attached:", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This Certification is being issued upon request of ".$requestor."(".$relation.") for his/her Any legal purposes. ", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this ".$issue_date." here at Palayan City, Nueva Ecija.", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(45);

        $health_officer = strtoupper($data->employee->fullname);
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$health_officer."</b>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(0,0,$data->health_officer_position,0,0,'C');

        //PDF::Output('Transfer_of_Cadaver.pdf');
		$filename ='Transfer_of_Cadaver'.$id.'.pdf';
		
		$arrSign= $this->_commonmodel->isSignApply('civil_registrar_transfer_cadaver_outside_province_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('civil_registrar_transfer_cadaver_outside_province_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->employee->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
		
        if($isSignVeified==1 && $signType==2 && $data->is_approved == 1){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->is_approved ==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        PDF::Output($filename,"I");
    }

    public function transferRemainPrint(Request $request, $id)
    {
        $data = HoDeceasedCert::find($id);

        PDF::SetTitle('Transfer of Remains PDF');    
        PDF::SetMargins(15, 15, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',11);

        $font_size = 12;
        $border = 0;
        PDF::SetFont('helvetica','',$font_size);
        // $cell_height = 5;
        // 185.9 max width

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 92.5, $y = 15, $w = 30, $h = 0, $type = 'PNG');
        PDF::ln(35);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "CITY OF PALAYAN", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "OFFICE OF THE CITY HEALTH OFFICER", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('helvetica','',15);
        PDF::MultiCell(0, 0, "<u>TRANSFER OF REMAINS</u>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        $requestor = strtoupper($data->requestor->cit_fullname);
        $deceased = strtoupper($data->deceased->cit_fullname);
        $relation = strtoupper($data->relation_type);        
        $death_date = Carbon::parse($data->death_date)->format('jS \\d\\a\\y \\of F Y');
        $issue_day = Carbon::parse($data->issue_date)->format('jS');
        $issue_month_year = Carbon::parse($data->issue_date)->format('F Y');
        $req_brgy = $data->brgy_add->brgy_name;
        $req_mun = $data->brgy_add->municipality->mun_desc; 

		if(isset($data->transfer_add_id)){
			$death_brgy = strtoupper($data->death_add->brgy_name);
			$death_mun = strtoupper($data->death_add->municipality->mun_desc);
			$death_prov = strtoupper($data->death_add->province->prov_desc);
		}else{
			$death_brgy='';
			$death_mun='';
			$death_prov='';
		}
        $transfer_location = strtoupper(($data->transfer_location)?$data->transfer_location:" ");
		if(isset($data->transfer_add_id)){
			$transfer_brgy = strtoupper($data->transfer_add->brgy_name);
			$transfer_mun = strtoupper($data->transfer_add->municipality->mun_desc);
			$transfer_prov = strtoupper($data->transfer_add->province->prov_desc);
		}else{
			$transfer_brgy ='';
			$transfer_mun ='';
			$transfer_prov ='';
		}
        PDF::SetFont('helvetica','',$font_size);
        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that ".$deceased.", died at <u>Barangay ".$death_brgy.", ".$death_mun." City, ".$death_prov." on ".$death_date."</u>.", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In lieu, the REMAIN of the deceased is authorized to travel to <b><u>".$transfer_location." ".$transfer_mun." CITY, ".$transfer_prov."</b></u>", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "For the cause of death; Death Certificate here to attached;", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is being issued upon request of <b><u>".$requestor."</b></u> for his/her any legal purposes", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this <b><u>".$issue_day."</b></u> day of <b><u>".$issue_month_year."</b></u>, here at Palayan City, Nueva Ecija.", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(45);

        $health_officer = strtoupper($data->employee->fullname);
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$health_officer."</b>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(110, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(0,0,$data->health_officer_position,0,0,'C');
		//PDF::Output('Transfer_of_Remains.pdf');
		$filename ='Transfer_of_Remains'.$id.'.pdf';
		
		$arrSign= $this->_commonmodel->isSignApply('civil_registrar_permit_transfer_remain_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('civil_registrar_permit_transfer_remain_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->employee->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
		
        if($isSignVeified==1 && $signType==2 && $data->is_approved ==1){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->is_approved ==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        PDF::Output($filename,"I");

        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'issue_date'=>'required',
                'brgy_id'=>'required',
                'form_type'=>'required',
                'relation_type'=>'required',
                'health_officer_id'=>'required',
                'health_officer_position'=>'required',
                'deceased_id'=>'required',
				'place_of_death_id'=>'required',
                'death_date'=>'required'
				
                
            ],[
                'issue_date.required' => 'Date is Required',
                'brgy_id.required' => 'Address is Required',
                'form_type.required' => 'Form Type is Required',
                'relation_type.required' => 'Relation Type is Required',
                'health_officer_id.required' => 'Health Officer is Required',
                'health_officer_position.required' => 'Position is Required',
                'deceased_id.required' => 'Deceased is Required',
                'place_of_death_id.required' => 'Place of death is Required',
				'death_date.required' => 'Date is required'
               
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
   
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoDeceasedCert::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/hodeceasedcert/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['doc_id'] = count($arrJson)+1;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->doc_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['doc_json'] = json_encode($finalJsone);
                $this->_hodeceasedcert->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['doc_json'],$healthCertId);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn ms-2'>
                                <a class='btn' href='".asset('uploads/hodeceasedcert').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
    public function deleteAttachment(Request $request){
        $healthCertid = $request->input('healthCertid');
        $doc_id = $request->input('doc_id');
        $arrEndrosment = HoDeceasedCert::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/hodeceasedcert/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_hodeceasedcert->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
    
}
