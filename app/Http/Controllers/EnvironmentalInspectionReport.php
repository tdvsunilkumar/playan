<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\EnvirInspeReport;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use File;
use Auth;
use App\Models\HrEmployee;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Carbon\Carbon;
use App\Models\BploBusiness;

class EnvironmentalInspectionReport extends Controller
{

    public $data = [];
    public $postdata = [];
    public $arrHrEmplyees = array("" => "Please Select");
	public $arrYears = array(""=>"Select Year");
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness(); 
        $this->_EnvirInspeReport = new EnvirInspeReport();
        $this->_Barangay = new Barangay();
        $this->_commonmodel = new CommonModelmaster();
		$this->data = array('id' =>'','busn_id'=>'','bend_id'=>'','ebir_year'=>date('Y'),'ebir_no'=>'',
		'ebir_control_no'=>'','ebir_date'=>date('Y-m-d'),'ebir_inspection_date'=>'','ebir_recommendation'=>'',
		'ebir_inspected_by'=>'','ebir_inspected_date'=>'','ebir_inspected_status'=>'','ebir_inspector_position'=>'',
		'ebir_approved_by'=>'','ebir_approved_date'=>'','ebir_approved_status'=>'','ebir_approver_position'=>'','created_by'=>'');
		$arrYrs = $this->_EnvirInspeReport->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->ebir_year] =$val->ebir_year;
        }
		foreach($this->_EnvirInspeReport->getHRemployees() as $val) {
            $this->arrHrEmplyees[$val->id]=$val->fullname;
        }
		$this->slugs = 'environmental-inspection-report';
    }

    public function index(Request $request)
    {	
		$this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        return view('environmental.index',compact('arrYears'));
    }
	public function positionbyid(Request $request){
        $id= $request->input('id');
        $data = $this->_EnvirInspeReport->getPosition($id);
        return $data->description;

    }	
	public function store(Request $request){
		$busn_id =  $request->input('busn_id');
        $bend_id =  $request->input('bend_id');
		$sent_year= $request->input('year');
		$hremployees =$this->arrHrEmplyees; 
		$current_user_id = \Auth::user()->id;
		$data = (object)$this->data;

		$arrbploBusiness  = $this->_EnvirInspeReport->getbploBusiness($busn_id);
		$complete_address = $this->_commonmodel->getbussinesAddress($busn_id);
		// $complete_address = $this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
		if($request->input('id')>0 && $request->input('submit')==""){
			$data = DB::table('enro_bplo_inspection_report')->where('busn_id',$busn_id)
										->where('bend_id',$bend_id)
										->where('ebir_year',$sent_year)
										->select('*')->first();
	    
		}elseif(DB::table('enro_bplo_inspection_report')->where('busn_id',$busn_id)->where('bend_id',$bend_id)->where('ebir_year',$sent_year)->exists()){						
			$data = DB::table('enro_bplo_inspection_report')->where('busn_id',$busn_id)
										->where('bend_id',$bend_id)
										->where('ebir_year',$sent_year)
										->select('*')->first();
		
		}
		else{
			$getdatausersave = $this->_EnvirInspeReport->CheckFormdataExist('5',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->ebir_inspected_by = $usersaved->ebir_inspected_by;
                  $data->ebir_inspector_position = $usersaved->ebir_inspector_position;
                  $data->ebir_approved_by = $usersaved->ebir_approved_by;
                  $data->ebir_approver_position = $usersaved->ebir_approver_position;
               }
		}
		if($data->bend_id > 0){
			$arrdocDtls = $this->generateDocumentListInspection($data->ebir_document,$data->bend_id,$data->ebir_status);
			if(isset($arrdocDtls)){
				$data->ebir_document = $arrdocDtls;
			}
		}
		
		if($request->input('submit')!=""){
			foreach((array)$this->data as $key=>$val){
				$this->data[$key] = $request->input($key);
			}
			$this->data['updated_by']=\Auth::user()->id;
			$this->data['updated_at'] = date('Y-m-d H:i:s');
            $apv_send=1;
			if($request->input('id')>0){
                $ext_data=$this->_EnvirInspeReport->findDataById($request->input('id'));
				$this->data['ebir_inspected_date']= ($this->data['ebir_inspected_status']==1)?'0':date('Y-m-d H:i:s');
				$this->data['ebir_approved_date']=   ($this->data['ebir_approved_status']==1)?'0':date('Y-m-d H:i:s');
				$this->_EnvirInspeReport->updateData($request->input('id'),$this->data);
				$success_msg = 'Inspection Report updated successfully.';	
                if($ext_data->ebir_approved_status == 1)
                {
                    $apv_send=0;
                }
			}else{
				$curr_years = date('Y');
				$appali_no=$this->generateApplictionNumber('00');
				$this->data['ebir_no']=$appali_no;
				$appali_no_yers=$this->generateApplictionNumberyears($curr_years."-");
				$this->data['ebir_control_no']= $appali_no_yers;
				$this->data['ebir_inspected_date']=   date('Y-m-d H:i:s');
				$this->data['ebir_inspected_status']=($this->data['ebir_inspected_status']=="")?'0':'1';
				$this->data['ebir_approved_date']=   date('Y-m-d H:i:s');
				$this->data['ebir_approved_status']= ($this->data['ebir_approved_status']=="")?'0':'1';				
				$this->data['ebir_status']=1;
				$this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['created_by'] =\Auth::user()->id;
				$this->_EnvirInspeReport->addData($this->data);
				$success_msg = 'Inspection Report added successfully.';
				$user_savedata = array();
                $user_savedata['ebir_inspected_by'] = $request->input('ebir_inspected_by');
                $user_savedata['ebir_inspector_position'] = $request->input('ebir_inspector_position');
                $user_savedata['ebir_approved_by'] = $request->input('ebir_approved_by');
                $user_savedata['ebir_approver_position'] = $request->input('ebir_approver_position');
                $userlastdata = array();
                $userlastdata['form_id'] = 5;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_EnvirInspeReport->CheckFormdataExist('5',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_EnvirInspeReport->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_EnvirInspeReport->addusersaveData($userlastdata);
                }
			}
            if($request->input('ebir_approved_status') == 1 && $apv_send == 1){
                $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('busn_id'));
                $smsTemplate=SmsTemplate::where('id',16)->where('is_active',1)->first();
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
			return redirect()->back()->with('success', __($success_msg));
		}
		if($data->ebir_inspected_by > 0 && $data->ebir_approved_by){
            $ebirinspected_user_id = $this->_EnvirInspeReport->selectHRemployees($data->ebir_inspected_by); 
			$ebirapproved_user_id = $this->_EnvirInspeReport->selectHRemployees($data->ebir_approved_by);
        }else{
            $ebirinspected_user_id = 0;
			$ebirapproved_user_id = 0;
        }
		
		
		return view('environmental.create',compact('data','ebirinspected_user_id','ebirapproved_user_id','bend_id','busn_id','current_user_id','hremployees','complete_address','arrbploBusiness'));
		
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

	public function generateApplictionNumberyears($company_code) {
        $prefix = $company_code;
		$curr_years = date('Y');
        $last_bookingq=DB::table('enro_bplo_inspection_report')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->ebir_control_no;
            } else {
              $last_booking=$curr_years."-";
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                $last_booking=$curr_years."-";
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 6, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
	public function generateApplictionNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('enro_bplo_inspection_report')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->ebir_no;
            } else {
              $last_booking='000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                $last_booking='000';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 6, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'bff_code'=>'required',
                // 'p_code'=>'required', 
                // 'brgy_code'=>'required', 
                // 'ba_business_account_no'=>'required'
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
    
	
    public function getList(Request $request)
    {
        $data = $this->_EnvirInspeReport->getList($request);
		
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row) {
			$sr_no=$sr_no+1;
			$actions = '';
			$hremployees =$this->arrHrEmplyees; 
			$arrbploBusiness=$this->_EnvirInspeReport->getbploBusiness($row->busn_id);
			$brgy_name = $this->_commonmodel->getbussinesAddress($row->busn_id);
			// if(!empty($arrbploBusiness->busn_office_main_barangay_id)){
			// 	$brgy_name = $this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
			// }else{
			// 	$brgy_name ="";
			// }
			$hremployees =$this->arrHrEmplyees; 
			$actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center getPositionslected" data-url="' . url('/environmental-inspection-report/store?id='.$row->id).'&busn_id='.$row->busn_id.'&bend_id='.$row->bend_id.'&year='.$row->ebir_year.'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Inspection Report">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
			if($row->ebir_inspected_status == 1 && $row->ebir_approved_status == 1){	
				$actions .= '<div class="action-btn bg-info ms-2">
					<a href="'.url('/environmental-inspection-report/printreport?id='.$row->id).'" target="_blank"  title="Print Inspection Report"  data-title="Print Inspection Report" class="mx-3 btn print btn-sm digital-sign-btn" id="'.$row->id.'">
						<i class="ti-printer text-white"></i>
					</a>
				</div>';
			}
			$actions .=($row->ebir_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
						'<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['ebir_year'] = $row->ebir_year;
            $arr[$i]['busn_name'] = $row->busn_name;
			$arr[$i]['brgy_name'] = "<div class='showLess'>".wordwrap($brgy_name, 40, "<br />\n")."</div>";
			$arr[$i]['taxper'] = $row->full_name;
			$arr[$i]['ebir_date'] = $row->ebir_date;
			$arr[$i]['ebir_inspection_date'] = $row->ebir_inspection_date;
			$arr[$i]['ebir_control_no'] = $row->ebir_control_no;
            $arr[$i]['ebir_inspected_by'] =($row->ebir_inspected_status > 0)?$hremployees[$row->ebir_inspected_by]:'';
			$arr[$i]['ebir_approved_by'] =($row->ebir_inspected_status > 0 && $row->ebir_approved_status > 0)?$hremployees[$row->ebir_approved_by]:''; 
			$arr[$i]['ebir_status']=($row->ebir_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
			$arr[$i]['action'] = $actions;
            $i++;
        }

        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
	
    
	public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ebir_status' => $is_activeinactive);
        $this->_EnvirInspeReport->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Inspection Report ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
	
	public function printreport(Request $request){
    	$id = $request->input('id');
    	$data = $this->_EnvirInspeReport->getPrintDetails($id);
		$buss_type = $this->_EnvirInspeReport->bussinessType($data->btype_id);
		$OwnerName = $this->_EnvirInspeReport->OwnerName($data->client_id);
		// $complete_address = $this->_Barangay->findDetails($data->busn_office_main_barangay_id);
		$complete_address = $this->_commonmodel->getbussinesAddress($data->busn_id);
		$hremployees =$this->arrHrEmplyees; 
		$ebir_inspected_by =$hremployees[$data->ebir_inspected_by];
		$name_and_type =$data->busn_name." / ".$buss_type;
		$OwnerNames=$OwnerName->full_name;
	    $mpdf = new \Mpdf\Mpdf();
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;
		$mpdf->text_input_as_HTML = true;
		$filename="";
		$html = file_get_contents(resource_path('views/layouts/templates/InspectionReport.html'));
		$logo = url('/assets/images/issuanceLogo.png');
		$signeture = url('/assets/images/signeture2.png');
		$html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{complete_address}}',str_replace(',', ', ', $complete_address), $html);
        $html = str_replace('{{owner_name}}',$OwnerNames, $html);
		$html = str_replace('{{name_and_type}}',$name_and_type, $html);
		$html = str_replace('{{recommendation}}',$data->ebir_recommendation, $html);
        $html = str_replace('{{ebir_inspection_date}}',date('M d,Y',strtotime($data->ebir_inspection_date)), $html);
        $html = str_replace('{{ebir_inspected_by}}',$ebir_inspected_by, $html); 
		$html = str_replace('{{ebir_approved_by}}',$hremployees[$data->ebir_approved_by], $html);
		$html = str_replace('{{ebir_approver_position}}',$data->ebir_approver_position, $html);
        $filename="";
        $mpdf->WriteHTML($html);
		
        $approvedId = HrEmployee::where('id', $data->ebir_approved_by)->first();
		$inspectedId = HrEmployee::where('id', $data->ebir_inspected_by)->first();
		$filename = $data->id."-InspectionReport.pdf";
        
		$arrSign= $this->_commonmodel->isSignApply('environ_endorsement_inspection_bplo_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('environ_endorsement_inspection_bplo_inspected_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            $mpdf->Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($approvedId->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                        $arrData['isDisplayPdf'] = 1;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature($inspectedId->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
		
		
    }
	
	public function printreportajax(Request $request){
		
    	$id = $request->input('id');
    	$data = $this->_EnvirInspeReport->getPrintDetails($id);
		$buss_type = $this->_EnvirInspeReport->bussinessType($data->btype_id);
		$OwnerName = $this->_EnvirInspeReport->OwnerName($data->client_id);
		$complete_address = $this->_commonmodel->getbussinesAddress($data->busn_id);
		// $complete_address = $this->_Barangay->findDetails($data->busn_office_main_barangay_id);
		$hremployees =$this->arrHrEmplyees; 
		$ebir_inspected_by =$hremployees[$data->ebir_inspected_by];
		$name_and_type =$data->busn_name." / ".$buss_type;
		$OwnerNames=$OwnerName->full_name;
		
	    $mpdf = new \Mpdf\Mpdf();
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;
		$mpdf->text_input_as_HTML = true;
		$filename="";
		$html = file_get_contents(resource_path('views/layouts/templates/InspectionReport.html'));
		$logo = url('/assets/images/issuanceLogo.png');
		$signeture = url('/assets/images/signeture2.png');
		$html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{complete_address}}',str_replace(',', ', ', $complete_address), $html);
        $html = str_replace('{{owner_name}}',$OwnerNames, $html);
		$html = str_replace('{{name_and_type}}',$name_and_type, $html);
		$html = str_replace('{{recommendation}}',$data->ebir_recommendation, $html);
        $html = str_replace('{{ebir_inspection_date}}',date('M d,Y',strtotime($data->ebir_inspection_date)), $html);
        $html = str_replace('{{ebir_inspected_by}}',$ebir_inspected_by, $html); 
		$html = str_replace('{{ebir_approved_by}}',$hremployees[$data->ebir_approved_by], $html);
		$html = str_replace('{{ebir_approver_position}}',$data->ebir_approver_position, $html);
        $mpdf->WriteHTML($html);
		
		$approvedId = HrEmployee::where('id', $data->ebir_approved_by)->first();
		$inspectedId = HrEmployee::where('id', $data->ebir_inspected_by)->first();
		$filename = "InspectionReport.pdf";
        
		$arrSign= $this->_commonmodel->isSignApply('environ_endorsement_inspection_bplo_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('environ_endorsement_inspection_bplo_inspected_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            $mpdf->Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($approvedId->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                        $arrData['isDisplayPdf'] = 1;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature($inspectedId->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
		
    }
	public function uploadAttachmentInspection(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('ebir_year');
        $bbendo_id =  $request->input('bend_id');
		
        $arrEndrosment = $this->_EnvirInspeReport->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
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
                $arrData = array();
                $arrData['business_id'] = $busn_id;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->ebir_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['ebir_document'] = json_encode($finalJsone);
				
                $this->_EnvirInspeReport->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['ebir_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
	
	public function generateDocumentListInspection($arrJson,$bbendo_id, $ebir_status=''){
        $html = "";
        $dclass = ($ebir_status==2 || $ebir_status==3)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td>
				  <a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
				  <div class='action-btn bg-danger ms-2'>
					<a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' rid='".$val['filename']."'".$dclass." bbendo_id='".$bbendo_id."'></a>
				  </div>
				  </td>
                </tr>";
            }
        }
        return $html;
    }
	public function deleteEndrosmentInspectionAttachment(Request $request){
        $rid = $request->input('rid');
        $busn_id = $request->input('id');
        $year = $request->input('year');
        $bbendo_id = $request->input('bbendo_id');
		
        $arrEndrosment = $this->_EnvirInspeReport->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->ebir_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['ebir_document'] = json_encode($arrJson);
                    $this->_EnvirInspeReport->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
   
}
