<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CertificateIssuance;
use App\Models\CommonModelmaster;
use App\Models\BploBusiness;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class CertificateIssuanceController extends Controller
{
    public $data = [];
     public $postdata = [];
     private $slugs;
     private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
     private $carbon;
     public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_certissuance = new CertificateIssuance(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_BploBusiness = new BploBusiness(); 
        $this->employee = array(""=>"Please Select");
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'bplo-retirementcertificate';
        foreach ($this->_certissuance->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        } 
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrBusinessnames = array('0'=>'All');
        return view('Bplo.certissuance.index',compact('startdate','enddate','arrBusinessnames'));
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_certissuance->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['businessid']=$row->busns_id_no;
            $arr[$i]['ownar_name']=$row->full_name;
            $businessname = wordwrap($row->busn_name, 100, "<br />\n");
            $arr[$i]['busn_name']="<div class='showLess'>".$businessname."</div>";
            $arr[$i]['remark']=$row->bri_remarks;
            $arr[$i]['establish']=date("M d, Y",strtotime($row->retire_date_start));
            $arr[$i]['actualclosure']=date("M d, Y",strtotime($row->retire_date_closed));
            $arr[$i]['certdate']=$row->bri_issued_date;
            $arr[$i]['duration']=$this->_commonmodel->calculateTotalYearMonth($row->retire_date_start,$row->retire_date_closed);
             $arr[$i]['status']=($row->status == 1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Issued</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['action']='<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplo-retirementcertificate/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Retirement Certificate">
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

      public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $arrDocumentDtls = array();
        $data = (object)$this->data;
        $employee = $this->employee;
        $data->document_details ="";
        $arrDocumentDetailsHtml=""; $data->bri_issued_byold ="";
         if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_certissuance->getEditDetails($request->input('id'));
            $data->bri_issued_byold = $data->bri_issued_by;
            $arrdocDtls = $this->generateDocumentList($data->retire_documentary_json,$data->id);
            if(isset($arrdocDtls)){
                $arrDocumentDetailsHtml = $arrdocDtls;
            }
            $arrdocDtls = $this->generateDocumentListnew($data->bri_upload_documents_json,$data->id);
            if(isset($arrdocDtls)){
                $data->document_details = $arrdocDtls;
            }
            if(empty($data->bri_issued_by) || $data->bri_issued_by==""){
               $getdatausersave = $this->_certissuance->CheckFormdataExist('24',\Auth::user()->id);
                 if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->bri_issued_by = $usersaved->bri_issued_by;
                  $data->bri_issued_position = $usersaved->bri_issued_position;
                 }  
             }
            $lineofbusiness = array();
            $arrClss=$this->_certissuance->getLineOfBusiness($data->retireid);
           
            $sunmary_url=asset('uploads/digital_certificates').'/'.$data->bri_retirement_certificate_name;
            //$sunmary_url = $data->bri_retirement_certificate_name;
        }
        

        return view('Bplo.certissuance.create',compact('data','arrDocumentDtls','arrDocumentDetailsHtml','sunmary_url','arrClss','employee'));
    }

     public function generateDocumentList($arrJson,$id, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $sub_class_name = wordwrap($val['sub_class_name'], 50, "<br>\n");
                    $requirement_name = wordwrap($val['requirement_name'], 50, "<br>\n");
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$requirement_name."</span></td>
                        <td><a class='btn' href='".asset('uploads/retire_documents').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arr = $this->_certissuance->getEditDetails($id);
        if(isset($arr)){
            $arrJson = json_decode($arr->bri_upload_documents_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/retire_documents/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bri_upload_documents_json'] = json_encode($arrJson);
                    $this->_certissuance->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function updateremark(Request $request){
    	$ESTATUS=1; $message="";
    	$id=$request->input('id');
    	$remark = $request->input('remark');
        $bri_issued_by = $request->input('bri_issued_by');
        $data1 = $this->_certissuance->getEditDetails($request->input('id'));
        if(empty($data1->bri_issued_by)){
                    $user_savedata = array();
                    $user_savedata['bri_issued_by'] = $request->input('bri_issued_by');
                    $user_savedata['bri_issued_position'] = $request->input('position');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 24;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_certissuance->CheckFormdataExist('24',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_certissuance->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_certissuance->addusersaveData($userlastdata);
                    }
        }
        $position = $request->input('position');
        $busnid = $request->input('busn_id');
        $year = $request->input('retire_year');
    	$data['bri_remarks'] = $remark;
        $data['bri_issued_by'] = $bri_issued_by;
        $data['bri_issued_position'] = $position;
                $this->_certissuance->updateData($id,$data);

    	$arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        
        if($request->input('bri_issued_by') != $request->input('bri_issued_byold')){
            $this->GenerateCertificate($busnid,$year);
        }
        echo json_encode($arr); exit;
        
    }

    public function updatestatus(Request $request){
        $ESTATUS=1; $message="";
        $year=date("Y");
        $details=array();
        $id=$request->input('certificateid');
        $busn_id=$request->input('busn_id');
        $status = $request->input('status');
        $data['status'] = $status;
        $this->_certissuance->updateData($id,$data);
        // For Retire Business
        Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$busn_id); // This for remote server
        if($status==1){
            $arrData['busn_app_status']=6; // Permit Cancelled
            $arrData['app_code']=3; //Don't change this app code 3
            $l_bplo_business = $this->_BploBusiness->findForUpdateHistory($busn_id);
            foreach($l_bplo_business as $key=>$val){
                $details[$key] = $l_bplo_business->$key;
            }
            $bplo_busn_history = $this->_BploBusiness->BploBusinessHistoryByBusnId($busn_id,$year,3);  
                if(count($bplo_busn_history) == 0){
                    $newHistory=array(
                        'busn_id' => $busn_id,
                        );
                    $details['app_code']=3;
                    $details = array_merge($details, $newHistory);                
                    $this->_BploBusiness->addBploBusinessHistory($details);
                }  
                else{
                    $bplo_business = $this->_BploBusiness->updateBploHistoryData($bplo_busn_history[0]->id, $details);               
                }
               
                    $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                    $smsTemplate=SmsTemplate::where('id',8)->where('is_active',1)->first();
                    if(!empty($smsTemplate))
                    {
                        $receipient=$arrBuss->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                        $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                        $this->send($msg, $receipient);
                    }
        }else{
            $arrData['busn_app_status']=8; // Permit Cancelled  
        }
        $this->_certissuance->updateBploBusinessData($busn_id,$arrData);
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message; 
              
        echo json_encode($arr); exit;
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


    public function GenerateCertificate($busnid,$year){
            $arrRetire = $this->_certissuance->getRetirementDetails($busnid,$year);
            if($arrRetire){
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/busicertificationsretirement.html'));
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $clientname = $arrRetire->full_name;
            $address ="";
            foreach ($this->_commonmodel->getBarangay($arrRetire->busn_office_main_barangay_id)['data'] as $valadd) {
               $address =$valadd->brgy_name.", ".$valadd->mun_desc. ", ".$valadd->prov_desc. ", ".$valadd->reg_region;
            }
            //echo $address; exit;
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{TAXPAYER}}',$clientname, $html);
             $html = str_replace('{{BUSINESSNAME}}',$arrRetire->busn_name, $html);
            $html = str_replace('{{CLOSEDDATE}}',date("M d, Y",strtotime($arrRetire->retire_date_closed)), $html);
            $html = str_replace('{{ISSUEDDATE}}',date("M d, Y",strtotime($arrRetire->bri_issued_date)), $html);
            $html = str_replace('{{POSITION}}',$arrRetire->bri_issued_position, $html);
            $html = str_replace('{{ADDRESS}}',$address, $html);
            
            $permitpersonnel = $this->_certissuance->getEmployeeDetails($arrRetire->bri_issued_by);
            $employeename =$permitpersonnel->fullname; 
            $html = str_replace('{{PERSONNELNAME}}',$employeename, $html);

             $path =  public_path().'/uploads/digital_certificates/'.$arrRetire->bri_retirement_certificate_name;
                    if(File::exists($path)) { 
                        if(!empty($arrRetire->bri_retirement_certificate_name)){
                          unlink($path);  
                      }
                    }
            $mpdf->WriteHTML($html);
            $retirefilename = $busnid.date('his')."retirecertificate.pdf";
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/digital_certificates/" . $retirefilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            $uptarray = array('bri_retirement_certificate_name'=>$retirefilename);
            $this->_certissuance->updateIssuanceData($arrRetire->certificateid,$uptarray);

            // $filename = $retirefilename;
            //     // PDF::Output($filename,'I'); exit;
            // $arrSign= $this->_commonmodel->isSignApply('bplo_retirement_certificate_approved_by');
            // $isSignVeified = isset($arrSign)?$arrSign->status:0;
                
            //     $signType = $this->_commonmodel->getSettingData('sign_settings');
            //     if(!$signType || !$isSignVeified){
            //         $mpdf->Output($folder.$filename,"F");
            //     }else{
            //         $signature = $this->_commonmodel->getuserSignature(\Auth::user()->id);
            //         $path =  public_path().'/uploads/e-signature/'.$signature;
            //         if($isSignVeified==1 && $signType==2){
            //             $arrData['isSavePdf'] = 1;
            //             $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            //             if(!empty($signature) && File::exists($path)){
            //                 // Apply Digital Signature
            //                 $mpdf->Output($folder.$filename,'F');
            //                 $arrData['signaturePath'] = $signature;
            //                 $arrData['filename'] = $filename;
            //                 return $this->_commonmodel->applyDigitalSignature($arrData);
            //             }
            //         }
            //         if($isSignVeified==1 && $signType==1){
            //             // Apply E-Signature
            //             if(!empty($signature) && File::exists($path)){
            //                 $mpdf->Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            //             }
            //         }
            //     }
            //     $mpdf->Output($folder.$filename,"F");
         }
    }

    public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $arrDtls = $this->_certissuance->getEditDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(isset($arrDtls)){
            $arrJson = (array)json_decode($arrDtls->bri_upload_documents_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/retire_documents/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrDtls)){
                    $arrJson = json_decode($arrDtls->bri_upload_documents_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['bri_upload_documents_json'] = json_encode($finalJsone);
                $this->_certissuance->updateData($id,$data);
                $arrDocumentList = $this->generateDocumentListnew($data['bri_upload_documents_json'],$id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
     public function generateDocumentListnew($arrJson,$id, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$val['filename']."</span></td>
                        <td><a class='btn' href='".asset('uploads/retire_documents').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteDocument ti-trash text-white text-white' id='".$id."' rid='".$val['filename']."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
}
