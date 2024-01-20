<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\PdoBploEndosement;
use App\Models\HrEmployee; 
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Carbon\Carbon;
use App\Models\BploBusiness;
class PdoBploEndosementController extends Controller
{
     public $data = [];
     public $bussData = [];
     public $postdata = [];
     public $getServices = array(""=>"Please Select");
     public $arrowners = array(""=>"Please Select");
     public $hremployees = array(""=>"Please Select");
     private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
     private $carbon;
     public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness();
        $this->_pdobploendosement= new PdoBploEndosement(); 
        $this->_pdobploendosement= new PdoBploEndosement(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','pend_year'=>date('Y'),'pend_date'=>date('Y-m-d'),'busn_id'=>'','bend_id'=>'','client_id'=>'','pend_remarks'=>'','pend_status'=>'','pend_inspected_by'=>'','pend_inspected_status'=>'','pend_inspected_officer_position'=>'','pend_approved_by'=>'','pend_approved_status'=>'','pend_officer_position'=>'','pend_remarks'=>'','or_no'=>'','or_amount'=>'','or_date'=>'','cashierd_id'=>'','cashier_id'=>'');  

        $this->bussData= array('id'=>'','busn_name'=>'','business_address'=>'','client_id'=>'','busn_office_main_barangay_id'=>'');
        $this->slugs = 'planning-and-development'; 
         foreach ($this->_pdobploendosement->gethremployess() as $val){
                $this->hremployees[$val->id]=$val->fullname;
         } 
    }
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.location.index');
           
    }
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('cs_is_active' => $is_activeinactive);
        $this->_pdobploendosement->updateActiveInactive($id,$data);
    }
    public function printreport(Request $request){
    	$id = $request->input('id');
    	$data = $this->_pdobploendosement->getPrintdata($id);
		$hremployees = $this->hremployees;
	    $mpdf = new \Mpdf\Mpdf();
 		$mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;

        $html = file_get_contents(resource_path('views/layouts/templates/locationclearancedesign.html'));

        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        $logo = url('/assets/images/issuanceLogo.png');
        $logo2 = url('/assets/images/logo2.jpg');  
        $bgimage = url('/assets/images/clearancebackground.jpg');
        $html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{barangay}}',$data->brgy_name, $html);
        $html = str_replace('{{businessname}}',$data->busn_name, $html);
        $html = str_replace('{{DATE}}',date('M d, Y',strtotime($data->pend_date)), $html);
		$html = str_replace('{{inspectedzoneofficer}}',$hremployees[$data->pend_inspected_by], $html);
        $html = str_replace('{{inspectedposition}}',$data->pend_inspected_officer_position, $html);
        $html = str_replace('{{zoneofficer}}',$hremployees[$data->pend_approved_by], $html);
        $html = str_replace('{{position}}',$data->pend_officer_position, $html);
        $ordynamic  ="";
        if(!empty($data->or_no)){
           $ordynamic ='<div style="width: 100%; margin-left:30px; margin-top:50px; font-size:15px">
        <div style="">
            <p style="width:100px; float:left;">Amount</p>
            <p style="width: 100px; border-bottom: solid 1px black; float:left;">:'.$data->or_amount.'</p>
        </div>
        <div style="">
            <p style="width:100px; float:left;">O. R. Number</p>
            <p style="width: 100px; border-bottom: solid 1px black; float:left;">: '.$data->or_no.'</p>
        </div>
        <div style="">
            <p style="width:100px; float:left;">Date</p>
            <p style="width: 100px; border-bottom: solid 1px black; float:left;">: '.$data->or_date.'</p>
        </div>
        </div>';  
        }
        $html = str_replace('{{ordynamicdata}}',$ordynamic, $html);
        // $html = str_replace('{{total}}',$orderdata->caf_amount, $html);
        $filename="";
        $mpdf->WriteHTML($html);
        $filename = $id.$filename."locationclearance.pdf";
        $arrSign= $this->_commonmodel->isSignApply('planning_development_bplo_location_clearance_inspected_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('planning_development_bplo_location_clearance_approved_by');
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
        $varifiedId="";
        $certifiedId="";
        $varifiedId = HrEmployee::where('id', $data->pend_inspected_by)->first();
        $certifiedId = HrEmployee::where('id', $data->pend_approved_by)->first();
        // echo $certifiedId->user_id;exit;

        $varifiedSignature = $this->_commonmodel->getuserSignature($varifiedId->user_id);
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($certifiedId->user_id);
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
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
    }
     
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_pdobploendosement->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->pend_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['clientname']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $arr[$i]['businessname']=$row->busn_name;
            $arr[$i]['is_active']=($row->pend_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoservice/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Planning &  Devt Service">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
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

    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $bussData = (object)$this->bussData;
        $getServices = $this->getServices;
        $arrowners = $this->arrowners;
        $hremployees = $this->hremployees;
        $data->pend_year = $request->input('year');
        $reqids =""; 
		$data->busn_id = $request->input('busn_id'); 
		$data->bend_id = $request->input('bend_id'); 
        $ornumberarray = array(""=>"select  OR");
        $ordataclass ="disabled-field";
        $checkcasheringexist = $this->_pdobploendosement->checkcasheringexist($request->input('busn_id'),$request->input('bend_id'));
        if(count($checkcasheringexist) > 0){
            foreach ($checkcasheringexist as $key => $value) {
                $ornumberarray[$value->id] = $value->or_no;
            }
            $ordataclass =" ";
        }
        //echo "<pre>"; print_r($checkcasheringexist); exit;
		
        if($request->input('busn_id')>0 && $request->input('bend_id')>0){
            $complete_address=$this->_commonmodel->getbussinesAddress($request->input('busn_id'));
            $bussData = $this->_pdobploendosement->getBusinesDetails($request->input('busn_id'));
            $bussData->business_address =$this->_Barangay->findDetails($bussData->busn_office_main_barangay_id);
            foreach ($this->_pdobploendosement->getbploOwners($bussData->client_id) as $val) {
                $arrowners[$val->id]= $val->rpo_first_name."  ".$val->rpo_middle_name." ".$val->rpo_custom_last_name.",  ".$val->suffix;
            }
            $dataexist = $this->_pdobploendosement->getpdoendosementexist($request->input('busn_id'),$request->input('bend_id'),$data->pend_year);
            if(!empty($dataexist)){
            	$data = $dataexist;

            }else{
             $getdatausersave = $this->_pdobploendosement->CheckFormdataExist('4',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
				  $data->pend_inspected_by =(isset($usersaved->pend_inspected_by))?$usersaved->pend_inspected_by:' ';
                  $data->pend_inspected_officer_position =(isset($usersaved->pend_inspected_officer_position))?$usersaved->pend_inspected_officer_position:' ';
                  $data->pend_approved_by = $usersaved->pend_approved_by;
                  $data->pend_officer_position = $usersaved->pend_officer_position;
               }

           }

        }
		
		if($data->pend_status > 0){
			$arrdocDtls = $this->generateDocumentListInspection($data->pend_document,$data->bend_id,$data->pend_status);
			if(isset($arrdocDtls)){
				$data->pend_document = $arrdocDtls;
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
                $ext_data=$this->_pdobploendosement->findDataById($request->input('id'));
                $this->_pdobploendosement->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $this->data['pend_inspected_status']=($this->data['pend_inspected_status']=="")?'0':'1';
				$this->data['pend_approved_status']=($this->data['pend_approved_status']=="")?'0':'1';
                $success_msg = 'Bplo Location Clearance updated successfully.';
                if($ext_data->pend_approved_status == 1)
                {
                    $apv_send=0;
                }
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['pend_inspected_status']=($this->data['pend_inspected_status']=="")?'0':'1';
				$this->data['pend_approved_status']=($this->data['pend_approved_status']=="")?'0':'1';
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_pdobploendosement->addData($this->data);
                $success_msg = 'Bplo Location Clearance added successfully.';
                $user_savedata = array();
				$user_savedata['pend_inspected_by'] = $request->input('pend_inspected_by');
                $user_savedata['pend_inspected_officer_position'] = $request->input('pend_inspected_officer_position');
                $user_savedata['pend_approved_by'] = $request->input('pend_approved_by');
                $user_savedata['pend_officer_position'] = $request->input('pend_officer_position');
                $userlastdata = array();
                $userlastdata['form_id'] = 4;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_pdobploendosement->CheckFormdataExist('4',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_pdobploendosement->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_pdobploendosement->addusersaveData($userlastdata);
                }
            }
            if($request->input('pend_approved_status')  == 1 && $apv_send == 1){
                $arrBuss = $this->_BploBusiness->getBussClientDetails($request->input('busn_id'));
                $smsTemplate=SmsTemplate::where('id',10)->where('is_active',1)->first();
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
            
			
            //echo "<pre>"; print_r($_POST['requiremets_ids']); e//xit;
            return redirect()->route('endorsement.pdoIndex')->with('success', __($success_msg));
    	}
        
        $current_user_id=\Auth::user()->id;
        if($data->pend_approved_by > 0 && $data->pend_inspected_by >0){
            $pendapproved_user_id = $this->_pdobploendosement->getUserhremployess($data->pend_approved_by); 
			$pendinspected_user_id = $this->_pdobploendosement->getUserhremployess($data->pend_inspected_by);
        }else{
            $pendapproved_user_id = 0;
			$pendinspected_user_id =0;
        }
       
        return view('Bplo.location.create',compact('data','current_user_id','pendapproved_user_id','pendinspected_user_id','getServices','arrowners','hremployees','bussData','complete_address','ordataclass','ornumberarray'));
	}
    public function positionbyid(Request $request){
        $id= $request->input('id');
        $data = $this->_pdobploendosement->getPosition($id);
        return $data->description;

    }
    public function getordata(Request $request){
        $id= $request->input('id');
        $data = $this->_pdobploendosement->getORdetails($id);
        echo json_encode($data);
    }
	  public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'pend_approved_by'=>'required',
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
	public function uploadAttachmentInspection(Request $request){
		
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('pend_year');
        $bbendo_id =  $request->input('bend_id');
		
        $arrEndrosment = $this->_pdobploendosement->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
		
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
                    $arrJson = json_decode($arrEndrosment->pend_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['pend_document'] = json_encode($finalJsone);
				
                $this->_pdobploendosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['pend_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
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
	
	public function generateDocumentListInspection($arrJson,$bbendo_id, $pend_status=''){
        $html = "";
        $dclass = ($pend_status==2 || $pend_status==3)?'disabled-status':'';
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
		
        $arrEndrosment = $this->_pdobploendosement->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->pend_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['pend_document'] = json_encode($arrJson);
                    $this->_pdobploendosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
}
