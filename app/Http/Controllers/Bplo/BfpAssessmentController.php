<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\BfpAssessment;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Mpdf\Mpdf;
use DateTime;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class BfpAssessmentController extends Controller
{
    public $data = [];
    public $dataDtls = [];
    public $arrBusiness = [];
    private $slugs;
    public $arrYears = array(""=>"Select Year");
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness(); 
        $this->_BfpAssessment = new BfpAssessment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->slugs = 'fire-protection/cashiering';
        //$this->slugs = 'fire-protection/endorsement';
        $this->data=array("id"=>"","bfpas_payment_type"=>"1","bfpas_total_amount"=>"0.00","bfpas_total_amount_paid"=>"0.00","bfpas_payment_or_no"=>"","created_at"=>date("d/m/Y"),"busn_id"=>"","bend_id"=>"","bfpas_ops_year"=>"","bff_application_no"=>"","bff_application_type"=>"1","barangay_id"=>"",'client_id'=>'','busn_id'=>'','bend_id'=>'','bff_id'=>'','bfpas_remarks'=>'','bfpas_control_no'=>'','bfpas_is_fully_paid'=>'','ocr_id'=>'','cancellation_reason'=>'','payment_status'=>'');
        $this->arrBusiness=array("ownar_name"=>"","busn_name"=>"","app_code"=>"","client_id"=>"","busn_address"=>"","busn_office_main_barangay_id"=>"");
        $arrYrs = $this->_BfpAssessment->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bfpas_ops_year] =$val->bfpas_ops_year;
        }
    }
    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        return view('Bplo.BfpAssessment.index',compact('arrYears'));
    }
    public function getList(Request $request){
        $data=$this->_BfpAssessment->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        $status=array();
        $status[0] ='<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>';
        $status[1] ='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Paid</span>';
        $status[2] ='<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>';
        $isNational=0;
        $arrLoc = $this->_commonmodel->bploLocalityDetails();
        if(isset($arrLoc)){
            $isNational=$arrLoc->asment_id==2?1:0;
        }
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $ownar_name=$row->rpo_first_name.' '.$row->rpo_middle_name.' '.$row->rpo_custom_last_name;
            if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            }
			$arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['ownar_name']=$ownar_name;
			$arr[$i]['busn_name']=$row->busn_name;
			$arr[$i]['app_type']=($row->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$row->app_code]:'';
			$arr[$i]['busn_app_status']=config('constants.arrBusinessApplicationStatus')[$row->busn_app_status];
			$arr[$i]['end_status']=config('constants.arrBusEndorsementStatus')[(int)$row->bend_status];
            //$arr[$i]['or_no']=$row->bfpas_payment_or_no;
			//$arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
			$createDate = new DateTime($row->created_at);
			$arr[$i]['Date']=$createDate->format('Y-m-d');
            $arr[$i]['bfpas_total_amount_paid']=number_format($row->bfpas_total_amount_paid,2);
            $arr[$i]['status']=$status[$row->payment_status];
            //$arr[$i]['busn_app_method']=$row->busn_app_method;

            $arr[$i]['action']='';
            if($isNational){
                $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fire-protection/cashiering/store?busn_id='.(int)$row->busn_id.'&end_id='.(int)$row->bend_id.'&year='.(int)$row->bfpas_ops_year).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Assessment & Fees">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
                $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a title="Print Order Of Payment"  data-title="Print Order Of Payment" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/fire-protection/cashiering/generatePaymentPdf?busn_id='.(int)$row->busn_id.'&end_id='.(int)$row->bend_id.'&year='.(int)$row->bfpas_ops_year).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
            }

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
        $arrFund=array(""=>"Select");
        $arrBank=array(""=>"Select");
        $arrCancelReason = array(""=>"Please Select");
        $arrFees = array(""=>"Please Select");
        $arrBfpType = array(""=>"Please Select");
        $arrChequeTypes = array(""=>"Select");
        $arrCheque=array();
        $arrBankDtls=array();
        $arrDocumentDetailsHtml='';

        $arrNature[0]['id']=0;
        $arrNature[0]['fmaster_id']=0;
        $arrNature[0]['baaf_assessed_amount']='0.00';
        $arrNature[0]['baaf_amount_fee']='0.00';
        $arrNature[0]['fmaster_subdetails_json']='';
        $arrNature[0]['fee_option_json']='';


        $arrChequeBankDtls=array("id"=>"","check_type_id"=>"","opayment_date"=>"","fund_id"=>"","bank_id"=>"","bank_account_no"=>"","opayment_transaction_no"=>"","opayment_check_no"=>"","opayment_amount"=>"");
        $i=0;
        foreach($arrChequeBankDtls as $key=>$val){
            $arrCheque[$i][$key]=$val;
            $arrBankDtls[$i][$key]=$val;
        }
        foreach ($this->_BfpAssessment->getFundCode() as $val) {
            $arrFund[$val->id]=$val->code;
        } 
        foreach ($this->_BfpAssessment->getBankList() as $val) {
            $arrBank[$val->id]=$val->bank_code;
        }
        
        foreach ($this->_BfpAssessment->getCancelReason() as $val) {
            $arrCancelReason[$val->id]=$val->ocr_reason;
        }
        foreach ($this->_BfpAssessment->getChequeTypes() as $val) {
            $arrChequeTypes[$val->id]=$val->ctm_description;
        }
        foreach ($this->_BfpAssessment->getBfpType() as $val) {
            $arrBfpType[$val->id]=$val->btype_name;
        }
        foreach ($this->_BfpAssessment->getFeeList() as $val) {
            $arrFees[$val->id]='['.$val->fmaster_code.'] => '.$val->fmaster_description;
        }

        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        $arrBusiness = (object)$this->arrBusiness;
        $data->busn_id = $request->input('busn_id');
        $data->bend_id = $request->input('end_id');
        $data->bfpas_ops_year = $request->input('year');
        $data->bff_id = $request->input('bff_id');
        if($request->input('busn_id')>0 && $request->input('end_id')>0 && $request->input('submit')==""){
            $arrBusiness = $this->_BfpAssessment->getBusinessDetails($request->input('busn_id'));
            $arrBusiness->ownar_name=$arrBusiness->rpo_first_name.' '.$arrBusiness->rpo_middle_name.' '.$arrBusiness->rpo_custom_last_name;
            if(!empty($arrBusiness->suffix)){
                $arrBusiness->ownar_name .=", ".$arrBusiness->suffix;
            }  
            
            $arrBusiness->busn_address=$this->_Barangay->findDetails($arrBusiness->busn_office_main_barangay_id);
            $arrDtls = $this->_BfpAssessment->getEditDetails($request->input('busn_id'),$request->input('end_id'),$request->input('year'));
            if(isset($arrDtls)){
                $data = $arrDtls;
                $arrFeesDtls = $this->_BfpAssessment->getAssessFeeDetails($data->id);
                $arrFeesDtls = json_decode(json_encode($arrFeesDtls), true);
                if(count($arrFeesDtls)>0){
                    $arrNature = $arrFeesDtls;
                }
                $data->created_at = date("d/m/Y",strtotime($data->created_at));
                $data->bfpas_date_paid = date("d/m/Y",strtotime($data->bfpas_date_paid));
                $arrdocDtls = $this->generateDocumentList($data->bfpas_document_json,$data->id,$data->payment_status);
                if(isset($arrdocDtls)){
                    $arrDocumentDetailsHtml = $arrdocDtls;
                }
            }else{
                $data->bff_application_no = $this->_BfpAssessment->getApplicationNo($data->bff_id);
            }
        }
        if($request->input('submit')!=""){
            $submitAction = $request->input('submitAction');
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
            $total_paid=$this->data['bfpas_total_amount_paid'];
            $payment_or_no=$this->data['bfpas_payment_or_no'];
    
            if($submitAction=="Make Payment"){
                // $this->data['bfpas_date_paid'] = date("Y-m-d");
                $this->data['bfpas_is_fully_paid'] = 1; 
                $this->data['payment_status'] = 1; 
                $url = "?busn_id=".$request->input('busn_id')."&end_id=".$request->input('bend_id')."&year=".$request->input('bfpas_ops_year');
                // dd($url);
                // $url = '?id='.$request->input('id');
                Session::put('BFP_PRINT_CASHIER_ID',$url);
            }
            else{
                // unset($this->data['bfpas_date_paid']);
                unset($this->data['bfpas_is_fully_paid']); 
                unset($this->data['payment_status']); 
            }
           if($request->input('id')>0){
                unset($this->data['bfpas_ops_year']);
                // unset($this->data['bfpas_control_no']);
                unset($this->data['created_at']);
                $this->data['bfpas_date_paid'] = $request->input('bfpas_date_paid');
                $this->data['bfpas_ops_code'] = 'RO3-419';
                $this->data['bfpas_ops_year'] = date('Y');
                $this->data['bfpas_control_no']='RO3-419-'.date('y').'-'.$request->input('bfpas_ops_no');
                $this->data['bfpas_ops_no'] = $request->input('bfpas_ops_no'); 
                $this->_BfpAssessment->updateData($request->input('id'),$this->data);
                $success_msg = 'Assessment updated successfully.';
                $bfpas_id = $request->input('id');
            }else{
                $this->dataDtls['created_by'] = $this->data['created_by']=\Auth::user()->id;
                $this->dataDtls['created_at'] = $this->data['created_at'] = date('Y-m-d H:i:s');
                $opsNo = $this->getPrevIssueNumber();
                $this->data['bfpas_ops_code'] = 'RO3-419';

                $bfpas_ops_no = str_pad($opsNo, 5, '0', STR_PAD_LEFT);
                $bfpas_control_no = $this->data['bfpas_ops_code'].'-'.date('y')."-".$request->input('bfpas_ops_no');
                $this->data['bfpas_date_paid'] = $request->input('bfpas_date_paid');
                $this->data['bfpas_ops_year'] = date('Y');
                $this->data['bfpas_ops_no'] = $request->input('bfpas_ops_no'); 
                $this->data['bfpas_control_no'] = $bfpas_control_no;
                
                $bfpas_id = $this->_BfpAssessment->addData($this->data);
                $success_msg = 'Assessment added successfully.';
            }

            $arrDetails = $request->input('fmaster_id');
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    $this->dataDtls['bfpas_id'] = $bfpas_id;
                    $this->dataDtls['bend_id'] =$this->data['bend_id'];
                    $this->dataDtls['busn_id'] =$this->data['busn_id'];
                    $this->dataDtls['bff_id'] =$this->data['bff_id'];
                    $this->dataDtls['fmaster_id'] =$value;
                    $this->dataDtls['baaf_assessed_amount'] = $request->input('baaf_assessed_amount')[$key];
                    $this->dataDtls['baaf_amount_fee'] = $request->input('baaf_amount_fee')[$key];
                    $this->dataDtls['fee_option_json'] ='';
                    $arrOption = $request->input('option_'.$value);
                    if(isset($arrOption)){
                        $this->dataDtls['fee_option_json'] = json_encode($arrOption);
                    }

                    $checkdetailexist =  $this->_BfpAssessment->checkRecordIsExist($value,$bfpas_id);
                    if(count($checkdetailexist) > 0){
                        $this->_BfpAssessment->updateAssessmentDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $this->_BfpAssessment->addAssessmentDetailsData($this->dataDtls);
                    }
                }
            }

            // Log Details Start
            $logDetails['module_id'] =$bfpas_id;
            $logDetails['log_content'] = 'Fire protection assessment created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            // Log Details End
            if($submitAction=="Make Payment"){
                $arrBuss = $this->_BploBusiness->getBussClientDetails($this->data['busn_id']);
                $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',15)->where('type_id',1)->where('is_active',1)->first();
                if(!empty($smsTemplate))
                {
                    $receipient=$arrBuss->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                    $msg = str_replace('<OR_NO>',  $payment_or_no,$msg);
                    $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                    $msg = str_replace('<CASHIER_AMOUNT>',  $total_paid,$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                    $this->send($msg, $receipient);
                }
            }

            // return redirect()->route('BfpAssessment.index')->with('success', __($success_msg));
            return redirect('fire-protection/endorsement')->with('success', __($success_msg));
        }
        return view('Bplo.BfpAssessment.create',compact('data','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','arrBusiness','arrBfpType','arrNature','arrFees','arrDocumentDetailsHtml'));
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
    
    public function getOptionDetails(Request $request){
        $bf_id = $request->input('bf_id');
        $arrFees = $this->_BfpAssessment->getFeeDetails($bf_id);
        $arr['ESTATUS']=1;
        $arr['option']='';
        if(isset($arrFees)){
            if(!empty($arrFees->fmaster_subdetails_json)){
                $arrOpt = json_decode($arrFees->fmaster_subdetails_json,true);
                $arr['ESTATUS']=0;
                $html='';
                foreach($arrOpt AS $key=>$val){
                    $html .=$this->getCheckBoxHtml($val,$bf_id);
                }
                $arr['option']=$html;
            }
        }
        echo json_encode($arr);
    }
    public function getCheckBoxHtml($val,$bf_id){
        $html='';
        if(isset($val)){
            $value =$val['value'];
            if($value){
                $value = wordwrap($value, 25, "\n");
            $html = '<div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <input id="option_'.$bf_id.'_'.$val['key'].'" class="form-check-input code" name="option_'.$bf_id.'[]" type="checkbox" value="'.$val['value'].'">
                        <label for="option_'.$bf_id.'_'.$val['key'].'" class="form-label"><span class="showLess">'.$value.'</span></label>
                    </div>
                    
                </div>
            </div>';
        }else{
            
            
        }
        return $html;
        }
    }
    public function checkOrAppNoUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $app_no = $request->input('app_no');
        $id = $request->input('id');

        $isOrUsed=0;
        $isAppUsed=0;
        if(!empty($or_no)){
            $isOrUsed = $this->_BfpAssessment->checkOrUsedOrNot($or_no,$id);
        }
        if(!empty($app_no)){
            $isAppUsed = $this->_BfpAssessment->checkAppNoUsedOrNot($app_no,$id);
        }
        
        $arr['isOrUsed']=$isOrUsed;
        $arr['isAppUsed']=$isAppUsed;

        if($isOrUsed){
            $arr['errORMsg']='This O.R No already used. Please try other';
        }
        if($isAppUsed){
            $arr['errAppMsg']='This Application No. already used. Please try other';
        }
        echo json_encode($arr);
    }
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_BfpAssessment->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->bfpas_ops_no+1;
        }
        return $number;
    }

    public function cancelOr(Request $request){
        $id = $request->input('app_ass_id');
        $ocr_id= $request->input('cancelreason');
        $remark= $request->input('remarkother');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'payment_status'=>'2','bfpas_is_fully_paid'=>0);
        $this->_BfpAssessment->updateData($id,$updataarray);

        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Fire Protection Assessment O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End

        return redirect()->route('BfpAssessment.index')->with('success', __('O.R Cancelled Successfully.'));
    }
    public function generateDocumentList($arrJson,$aid, $status=''){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $filename = wordwrap($val['filename'], 10, "<br>\n");
                    $html .= "<tr>
                        <td><span class='showLess'>".$filename."</span></td>
                        <td><a class='btn' href='".asset('uploads/bfp_fireprotection_assesment').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteEndrosment ti-trash text-white text-white ".$dclass."' fname='".$val['filename']."' aid='".$aid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function deleteAttachment(Request $request){
        $id = $request->input('id');
        $fname = $request->input('fname');
        
        $arrAss = $this->_BfpAssessment->getAssDetails($id);
        if(isset($arrAss)){
            $arrJson = json_decode($arrAss->bfpas_document_json,true);
            if(isset($arrJson)){
                $key  = array_search($fname, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/bfp_fireprotection_assesment/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bfpas_document_json'] = json_encode($arrJson);
                    $this->_BfpAssessment->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function cancelNaturePaymentOption(Request $request){
        $f_id =  $request->input('f_id');
        $this->_BfpAssessment->deleteAssessmentFeeOption($f_id);
        $arr['ESTATUS']=0;
        $arr['message']="Deleted Successfully";
        echo json_encode($arr);exit;
    
    }

    public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $image = $request->file('file');
        $filename=$image->getClientOriginalName();
        $arrAss = $this->_BfpAssessment->getAssDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrAss)){
            $arrJson = (array)json_decode($arrAss->bfpas_document_json,true);
            $key  = array_search($filename, array_column($arrJson, 'filename'));
            if($key !== false){
                $message="This document is already exist";
                $ESTATUS=1;
            }
        }
        if(empty($message)){
            $destinationPath =  public_path().'/uploads/bfp_fireprotection_assesment/';
            if(!File::exists($destinationPath)) { 
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $image->move($destinationPath, $filename);
            $arrData = array();
            $arrData['filename'] = $filename;
            $finalJsone[] = $arrData;
            if(isset($arrAss)){
                $arrJson = json_decode($arrAss->bfpas_document_json,true);
                if(isset($arrJson)){
                    $arrJson[] = $arrData;
                    $finalJsone = $arrJson;
                }
            }
            $data['bfpas_document_json'] = json_encode($finalJsone);
            $this->_BfpAssessment->updateData($id,$data);
            $arrDocumentList = $this->generateDocumentList($data['bfpas_document_json'],$id);
            
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generatePaymentPdf(Request $request){
        $url = $request->input('end_id');
        $arrBusiness = $this->_BfpAssessment->getBusinessDetails($request->input('busn_id'));
        $arrBusiness->ownar_name=$arrBusiness->rpo_first_name.' '.$arrBusiness->rpo_middle_name.' '.$arrBusiness->rpo_custom_last_name;
        if(!empty($arrBusiness->suffix)){
            $arrBusiness->ownar_name .=", ".$arrBusiness->suffix;
        }   
        $arrBusiness->busn_address=$this->_Barangay->findDetails($arrBusiness->busn_office_main_barangay_id);
        $arrDtls = $this->_BfpAssessment->getEditDetails($request->input('busn_id'),$request->input('end_id'),$request->input('year'));
        $data=array();
        $arrNature = array();
        $amountinWord="";
        $employee="";
        if(isset($arrDtls)){
            $data = $arrDtls;
            $arrFeesDtls = $this->_BfpAssessment->getAssessFeeDetailsForPrint($data->id);
            $employee = $this->_BfpAssessment->getEmployee($data->created_by);
            // echo $employee;exit;
            $employee->employee_name=$employee->title.' '.$employee->firstname.' '.$employee->middlename.' '.$employee->lastname;
            if(!empty($employee->suffix)){
                $employee->employee_name .=", ".$employee->suffix;
            } 
            $arrFeesDtls = json_decode(json_encode($arrFeesDtls), true);
            if(count($arrFeesDtls)>0){
                $arrNature = $arrFeesDtls;
            }
            $data->created_at = date("F d, Y", strtotime($data->created_at));
            $data->bfpas_date_paid = date("F d, Y", strtotime($data->bfpas_date_paid));
            $amountinWord = $this->_commonmodel->numberToWord($data->bfpas_total_amount_paid).' Only';
        }
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [360, 288]]);
        $mpdf->SetDisplayMode('fullpage', 'single');
        $mpdf->shrink_tables_to_fit = 00;
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        $mpdf->SetMargins(0, 0, 0);
        $mpdf->SetAutoPageBreak(false, 0);   
        $mpdf->AddPage('L','','','','',10,10,2,2,10,10);
         // Adds a new page in Landscape orientation

        $Finalhtml = view('Bplo.BfpAssessment.taxOrderPayment',compact("data","arrNature","arrBusiness","employee",'amountinWord'));
        $mpdf->WriteHTML($Finalhtml);
        $filename = isset($data->id) ? $data->id . "-AssessmentFees.pdf" : "AssessmentFees.pdf";
        $arrSign= $this->_commonmodel->isSignApply('fire_protection_endorsement_assessment_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('fire_protection_endorsement_assessment_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
       
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->created_by);
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
    public function printReceipt(Request $request){
        $arrDtls = $this->_BfpAssessment->getEditDetails($request->input('busn_id'),$request->input('end_id'),$request->input('year'));
        $ctcdata = $this->_BfpAssessment->find($arrDtls->id);
        $defaultFeesarr = $this->_BfpAssessment->GetReqiestfees($arrDtls->id);
        // dd($defaultFeesarr);
        // cash details
        $arrPaymentbankDetails =  (object)[]; 
        switch ($ctcdata->bfpas_payment_type) {
            case 2: //Check 
                // $arrPaymentbankDetails = $this->_BfpAssessment->GetPaymentbankdetails($id);
                $payment_terms = 3;
                break;
            case 3: //Money Order
                // $arrPaymentbankDetails = $this->_BfpAssessment->GetPaymentcheckdetails($id);
                $payment_terms = 2;
                break;
            default:
                $payment_terms = $ctcdata->bfpas_payment_type;
                $arrPaymentbankDetails =  (object)[]; 
                break;
        }

        // print reciept
        $data = [
            'transacion_no' => $ctcdata->bff_application_no,
            'date' => $ctcdata->bfpas_date_paid,
            'or_number' => $ctcdata->bfpas_payment_or_no,
            'payor' => $ctcdata->full_name,
            'transactions' => $defaultFeesarr,
            'total' => $ctcdata->bfpas_total_amount,
            'payment_terms' => $payment_terms,
            'cash_details' => $arrPaymentbankDetails,
        ];
        //reciept is different from other reciepts
        $this->_BfpAssessment->printReceipt($data);
    }
}
 
