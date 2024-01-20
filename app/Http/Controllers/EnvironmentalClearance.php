<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\EnvirClearance;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee; 
use App\Models\Barangay;
use App\Models\BploBusiness;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use File;
use Auth;
use Mpdf\Mpdf;
use DateTime;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Carbon\Carbon;
class EnvironmentalClearance extends Controller
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
        $this->_EnvirClearance = new EnvirClearance();
		$this->_BploBusiness = new BploBusiness();
        $this->_Barangay = new Barangay();
        $this->_commonmodel = new CommonModelmaster();
		$this->data = array('id' =>'','busn_id'=>'','bend_id'=>'','ebac_app_code'=>'','ebac_date'=>date('Y-m-d'),'ebac_app_year'=>date('Y'),'ebac_app_no'=>'',
		'ebac_issuance_date'=>'','ebac_remarks'=>'','ebac_approved_by'=>'','ebac_approved_by_status'=>'','ebac_approver_position'=>'','ebac_approved_date'=>'','created_by'=>'');
		
		$arrYrs = $this->_EnvirClearance->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->ebac_app_year] =$val->ebac_app_year;
        }
		foreach($this->_EnvirClearance->getHRemployees() as $val) {
                $this->arrHrEmplyees[$val->id]=$val->fullname;
        }

		$this->slugs = 'environmental-clearance';
    }

    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        return view('EnvirClearance.index',compact('arrYears'));
    }
	public function positionbyid(Request $request){
        $id= $request->input('id');
        $data = $this->_EnvirClearance->getPosition($id);
        return $data->description;

    }
    	
	public function store(Request $request){
		$busn_id     =  $request->input('busn_id');
        $bend_id     =  $request->input('bend_id');
		$sent_year   = $request->input('year');
		$hremployees = $this->arrHrEmplyees; 
		$current_user_id = \Auth::user()->id;
		$data = (object)$this->data;

		$arrbploBusiness  = $this->_EnvirClearance->getbploBusiness($busn_id);
		$busn_plan=$this->_BploBusiness->reload_busn_plan($busn_id);
		// $complete_address = $this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
		$complete_address=$this->_commonmodel->getbussinesAddress($busn_id);
		if($request->input('id')>0 && $request->input('submit')==""){
			$data = DB::table('enro_bplo_app_clearances')->where('busn_id',$busn_id)
										->where('bend_id',$bend_id)
										->where('ebac_app_year',$sent_year)
										->select('*')->first();
			
		}elseif(DB::table('enro_bplo_app_clearances')->where('busn_id',$busn_id)->where('bend_id',$bend_id)->where('ebac_app_year',$sent_year)->exists()){						
			$data = DB::table('enro_bplo_app_clearances')->where('busn_id',$busn_id)
										->where('bend_id',$bend_id)
										->where('ebac_app_year',$sent_year)
										->select('*')->first();
		}
        else{
            $getdatausersave = $this->_EnvirClearance->CheckFormdataExist('6',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->ebac_approved_by = $usersaved->ebac_approved_by;
                  $data->ebac_approver_position = $usersaved->ebac_approver_position;
               }
        }
		
		if($data->bend_id > 0){
			$arrdocDtls = $this->generateDocumentListInspection($data->ebac_document,$data->bend_id,$data->ebac_status);
			if(isset($arrdocDtls)){
				$data->ebac_document = $arrdocDtls;
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
                $ext_data=$this->_EnvirClearance->findDataById($request->input('id'));
				$this->data['ebac_approved_date']= ($this->data['ebac_approved_by_status']==1)?'0':date('Y-m-d H:i:s');
				$this->data['ebac_approved_by_status']=($this->data['ebac_approved_by_status']=="")?'0':'1';
				$this->_EnvirClearance->updateData($request->input('id'),$this->data);
				$success_msg = 'Environmental Clearance updated successfully.';	
				if($ext_data->ebac_approved_by_status == 1)
                {
                    $apv_send=0;
                }
			}else{
				$curr_years = date('Y');
				$appali_no=$this->generateApplictionNumber($curr_years."-");
				$this->data['ebac_date']=date('Y-m-d');
				$this->data['ebac_app_no']= $appali_no;
				$this->data['ebac_approved_date']=   date('Y-m-d H:i:s');
				$this->data['ebac_approved_by_status']=($this->data['ebac_approved_by_status']=="")?'0':'1';			
				$this->data['ebac_status']=1;
				$this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['created_by'] =\Auth::user()->id;
				$this->_EnvirClearance->addData($this->data);
				$success_msg = 'Environmental Clearance added successfully.';
                $user_savedata = array();
                $user_savedata['ebac_approved_by'] = $request->input('ebac_approved_by');
                $user_savedata['ebac_approver_position'] = $request->input('ebac_approver_position');
                $userlastdata = array();
                $userlastdata['form_id'] = 6;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_EnvirClearance->CheckFormdataExist('6',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_EnvirClearance->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_EnvirClearance->addusersaveData($userlastdata);
                }
			}
            if($request->input('ebac_approved_by_status') == 1 && $apv_send == 1){
                $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('busn_id'));
                $smsTemplate=SmsTemplate::where('id',17)->where('is_active',1)->first();
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
		
		if($data->ebac_approved_by > 0){
            $ebacapproved_user_id = $this->_EnvirClearance->selectHRemployees($data->ebac_approved_by); 
        }else{
            $ebacapproved_user_id = 0;
        }
		return view('EnvirClearance.create',compact('data','busn_plan','ebacapproved_user_id','bend_id','busn_id','current_user_id','hremployees','complete_address','arrbploBusiness'));
		
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
	public function generateApplictionNumber($company_code) {
        $prefix = $company_code;
		$curr_years = date('Y');
        $last_bookingq=DB::table('enro_bplo_app_clearances')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->ebac_app_no;
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
        $data = $this->_EnvirClearance->getList($request);
        /* echo "<pre>";
		print_r($data);
		exit; */
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row) {
			$sr_no=$sr_no+1;
			$actions = '';
			$hremployees =$this->arrHrEmplyees; 
			$arrbploBusiness=$this->_EnvirClearance->getbploBusiness($row->busn_id);
			$brgy_name=$this->_commonmodel->getbussinesAddress($row->busn_id);
			// if(!empty($arrbploBusiness->busn_office_main_barangay_id)){
			// 	$brgy_name = $this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
			// }else{
			// 	$brgy_name="";
			// }
			$hremployees =$this->arrHrEmplyees; 
			$actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center " data-url="' . url('/environmental-clearance/store?id='.$row->id).'&busn_id='.$row->busn_id.'&bend_id='.$row->bend_id.'&year='.$row->ebac_app_year.'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Environmental Clearance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
			if($row->ebac_approved_by_status == 1){
			// $actions .= '<div class="action-btn bg-info ms-2">
			// 		<a href="#" title="Print Environmental clearance"  data-title="Print Environmental clearance" class="mx-3 btn print btn-sm" id="'.$row->id.'">
			// 			<i class="ti-printer text-white"></i>
			// 		</a>
			// 	</div>';
			$actions .='<div class="action-btn bg-info ms-2">
                    <a title="Print Environmental clearance"  data-title="Print Environmental clearance" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/envirclearance/EnvirClearance?id='.(int)$row->id).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
			}	
			$actions .=($row->ebac_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
						'<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
			
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebac_app_year'] = $row->ebac_app_year;
            $arr[$i]['busn_name'] = $arrbploBusiness->busn_name;
            $arr[$i]['brgy_name'] = "<div class='showLess'>".wordwrap($brgy_name, 40, "<br />\n")."</div>";
            $arr[$i]['taxper'] = $row->full_name;
            $arr[$i]['ebac_date'] = $row->ebac_date;
			$arr[$i]['ebac_issuance_date'] = $row->ebac_issuance_date;
            $arr[$i]['ebac_approved_by'] =($row->ebac_approved_by_status > 0)?$hremployees[$row->ebac_approved_by]:'';
			$arr[$i]['ebac_status']=($row->ebac_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('ebac_status' => $is_activeinactive);
        $this->_EnvirClearance->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Environmental Clearance ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    public function printreport(Request $request){
		
    	$id = $request->input('id');
    	$data = $this->_EnvirClearance->getPrintDetails($id);
		
		$complete_address = $this->_commonmodel->getbussinesAddress($data->busn_id);
		$OwnerName = $this->_EnvirClearance->OwnerName($data->client_id);
		$OwnerNames=$OwnerName->full_name;
		$hremployees =$this->arrHrEmplyees; 
		$ebir_inspected_by =$hremployees[$data->ebac_approved_by];
		$hr_postion = $this->_EnvirClearance->getPosition($data->ebac_approved_by);
		
	    $mpdf = new \Mpdf\Mpdf();
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;
		$mpdf->text_input_as_HTML = true;
		$filename="";
		
		$html = file_get_contents(resource_path('views/layouts/templates/EnvirClearance.html'));
		$logo = url('/assets/images/CEC_Certificate1.jpg');
		$signeture = url('/assets/images/CEC_Certificate_Sign.png');
		$subdomain = config('constants.sub_domain');
		$html = str_replace('{{signeture}}',$signeture, $html);
        $html = str_replace('{{subdomain}}',$subdomain, $html);
		$html = str_replace('{{LOGO}}',$logo, $html);
		$html = str_replace('{{business_name}}',$data->busn_name, $html);
		$html = str_replace('{{owner_name}}',$OwnerNames, $html);
        $html = str_replace('{{complete_address}}',str_replace(',', ', ', $complete_address), $html);
        $html = str_replace('{{give_date}}',date('M d,Y',strtotime($data->ebac_issuance_date)), $html);
		$html = str_replace('{{hr_postion}}',$data->ebac_approver_position, $html);
        $html = str_replace('{{ebir_inspected_by}}',$ebir_inspected_by, $html); 
        $filename="";
        $mpdf->WriteHTML($html);
        $filename = $id.$filename."EnvirClearance.pdf";
        $mpdf->Output($filename, "I");
    }
	
	public function printreportajax(Request $request){
		
    	$id = $request->input('id');
    	$data = $this->_EnvirClearance->getPrintDetails($id);
		$complete_address = $this->_commonmodel->getbussinesAddress($data->busn_id);
		$OwnerName = $this->_EnvirClearance->OwnerName($data->client_id);
		$OwnerNames=$OwnerName->full_name;
		$hremployees =$this->arrHrEmplyees; 
		$ebir_inspected_by =$hremployees[$data->ebac_approved_by];
		$hr_postion = $this->_EnvirClearance->getPosition($data->ebac_approved_by);
	    $mpdf = new \Mpdf\Mpdf();
	    $mpdf->shrink_tables_to_fit = 00;
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;
		$mpdf->text_input_as_HTML = true;
		$filename="";
		$html = file_get_contents(resource_path('views/layouts/templates/EnvirClearance.html'));
		
		$logo = url('/assets/images/CEC_Certificate1.jpg');
		$signeture = url('/assets/images/CEC_Certificate_Sign.png');
		$subdomain = config('constants.sub_domain');
		$html = str_replace('{{signeture}}',$signeture, $html);
		$html = str_replace('{{subdomain}}',$subdomain, $html);
		$html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{business_name}}',$data->busn_name, $html);
		$html = str_replace('{{owner_name}}',$OwnerNames, $html);
        $html = str_replace('{{complete_address}}',str_replace(',', ', ', $complete_address), $html);
        $html = str_replace('{{give_date}}',date('M d,Y',strtotime($data->ebac_issuance_date)), $html);
		$html = str_replace('{{hr_postion}}',$data->ebac_approver_position, $html);
        $html = str_replace('{{ebir_inspected_by}}',$ebir_inspected_by, $html); 
        $filename="";
        $mpdf->WriteHTML($html);
        $inspectedId = HrEmployee::where('id', $data->ebac_approved_by)->first();
        
		$filename = $data->id."-EnvirClearance.pdf";
		$arrSign= $this->_commonmodel->isSignApply('environ_clearance_endorsement_bplo_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('environ_clearance_endorsement_bplo_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($inspectedId->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                $mpdf->Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                $mpdf->Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        
        $mpdf->Output($folder.$filename,"I");

    }
	
	public function uploadAttachmentInspection(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('ebac_app_year');
        $bbendo_id =  $request->input('bend_id');
		
        $arrEndrosment = $this->_EnvirClearance->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
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
                    $arrJson = json_decode($arrEndrosment->ebac_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['ebac_document'] = json_encode($finalJsone);
				
                $this->_EnvirClearance->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['ebac_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
	
	public function generateDocumentListInspection($arrJson,$bbendo_id, $ebac_status=''){
        $html = "";
        $dclass = ($ebac_status==2 || $ebac_status==3)?'disabled-status':'';
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
                  <!-- <td>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' rid='".$val['filename']."'".$dclass." bbendo_id='".$bbendo_id."'></a>
                        </div>
                    </td>--> 
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
		
        $arrEndrosment = $this->_EnvirClearance->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->ebac_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['ebac_document'] = json_encode($arrJson);
                    $this->_EnvirClearance->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
}
