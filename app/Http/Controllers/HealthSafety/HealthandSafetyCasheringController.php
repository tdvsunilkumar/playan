<?php

namespace App\Http\Controllers\HealthSafety;
use App\Http\Controllers\Controller;
use App\Models\HealthSafety\HealthandSafetyCashering;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Session;
use Carbon\Carbon;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class HealthandSafetyCasheringController extends Controller
{
    public $data = [];
    public $dataDtls = [];
    private $slugs;
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->_HealthandSafety = new HealthandSafetyCashering(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','cashier_or_date'=>date("Y-m-d"),'client_citizen_id'=>'','or_no'=>'','total_amount'=>'0','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','total_paid_interest'=>'','payment_terms'=>'1','payee_type'=>'2','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'','cashier_remarks'=>'','cashier_particulars'=>'');

        $this->dataDtls = array('client_citizen_id'=>'','payee_type'=>'');
        $this->slugs = 'cashier/HealthandSafety';
        $getortype = $this->_HealthandSafety->GetOrtypeid('6');
                $this->ortype_id =  $getortype->ortype_id; 
    }
    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        return view('health-and-safety.Cashering.index');
    }
    public function getRefreshCitizen(Request $request){
       $getgroups = $this->_HealthandSafety->getCitizens();
       $htmloption ='<option value="">Select Citizen</option>';
      foreach ($getgroups as $key => $value) {
        if($value->cit_suffix_name){
            $htmloption .='<option value="'.$value->id.'">'.$value->cit_first_name.'  '.$value->cit_middle_name.' '.$value->cit_last_name.', '.$value->cit_suffix_name.'</option>';
        }else{
            $htmloption .='<option value="'.$value->id.'">'.$value->cit_first_name.'  '.$value->cit_middle_name.' '.$value->cit_last_name.'</option>';
        }
        
      }
      echo $htmloption;
    } 
    public function getallFees(Request $request){
        $hoid = $request->input('hoid');
        $defaultFeesarr = $this->_HealthandSafety->GetReqiestfeesdefault($hoid);
        $html ="";
        foreach ($defaultFeesarr as $key => $val) {
                    if($val->hlf_fee > 0 ){
                              $html .='<div class="row removeNatureData">';
                                   $html .='<div class="col-lg-7 col-md-5 col-sm-7">
                                           <div class="form-group">
                                                 <div class="form-icon-user hidden">
                                                     <input class="form-control" readonly="readonly" id="year" name="tfoc_id[]" type="text" value="'.$val->tfoc_id.'" fdprocessedid="3w2mkr">
                                                  </div>
                                                 <div class="form-icon-user">
                                                 <input class="form-control" readonly="readonly" id="year" name="desc[]" type="text" value="'.$val->hlf_service_name.'" fdprocessedid="3w2mkr">
                                                 </div>
                                            </div>     
                                       </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                             <input class="form-control ctc_taxable_amount" readonly="readonly" id="ctc_taxable_amount" name="ctc_taxable_amount[]" type="text" value="" fdprocessedid="elxr4w">
                                           </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">';
                                                $html .='<input class="form-control amount" id="tfc_amount" readonly="readonly" name="tfc_amount[]" type="text" value="'.$val->hlf_fee.'" fdprocessedid="nh806j">';
                                            $html .='</div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                        </div>
                               </div>';
                           }
        }
        echo $html;
    } 

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_HealthandSafety->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        $arrStatus=array();
        $arrStatus[0] ='<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>';
        $arrStatus[1] ='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>';

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;   $toptno ="";
            $arr[$i]['cashier_year']=$row->cashier_year;  
            if($row->payee_type==1){
                $arr[$i]['completeaddress']= $this->_commonmodel->getTaxPayerAddress($row->client_citizen_id);
                //$arr[$i]['taxpayername']= $this->_commonmodel->getUserName($row->rpo_first_name,$row->rpo_middle_name,$row->rpo_custom_last_name,$row->suffix);
                $arr[$i]['taxpayername']= $row->full_name;
            }else{
                $arr[$i]['completeaddress']= $this->_commonmodel->getCitizenAddress($row->client_citizen_id);
                $arr[$i]['taxpayername']= $row->cit_fullname;
            }
           
			$address = wordwrap($arr[$i]['completeaddress'], 20, "<br />\n");
            $arr[$i]['completeaddress']= "<div class='showLess'>".$address."</div>";
            $arr[$i]['cashier_particulars']= $row->cashier_particulars; 
            if($row->top_transaction_id > 0){
              $topdata = $this->_HealthandSafety->GetToptransaction($row->top_transaction_id);
               $toptno =  $topdata->transaction_no;
            } 
            $arr[$i]['topno']= $toptno;
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            $arr[$i]['payee_type']=config('constants.arrPayeeType')[(int)$row->payee_type];
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['status']=$arrStatus[$row->status];
            $arr[$i]['cashier']=$row->fullname;
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('cashier/HealthandSafety/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Health and Safety Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="'.url('cashier/HealthandSafety/printReceipt?id='.$row->id).'" target="_blank" title="Print Eng Cashering"  data-title="Print Eng Cashering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
                            <i class="ti-printer text-white"></i>
                </a></div>';
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
    public function printReceipt(Request $request)
    {
		
        $id = $request->input('id'); 
        $ctcdata = $this->_HealthandSafety->getCertificateDetails($id);
        $cashiername = $this->_commonmodel->getemployeefullname($ctcdata->created_by);
        $defaultFeesarr = $this->_HealthandSafety->GetReqiestfees($id);
        // dd($defaultFeesarr);
        // cash details
        switch ($ctcdata->payment_terms) {
            default:
                $arrPaymentbankDetails =  (object)[]; 
                break;
        }

        // print reciept
        $data = [
            'transacion_no' => $ctcdata->or_no,
            'date' => $ctcdata->created_at,
            'or_number' => $ctcdata->or_no,
            'payor' => $ctcdata->cit_fullname,
            'transactions' => $defaultFeesarr,
            'total' => $ctcdata->total_amount,
            'payment_terms' => $ctcdata->payment_terms,
            'cash_details' => $arrPaymentbankDetails,
            'surcharge' => $ctcdata->total_paid_surcharge,
            'interest' => $ctcdata->total_paid_interest,
            'cashierid' => $ctcdata->created_by,
            'cashiername' => $cashiername->fullname,
            'varName'=>'cashier_health_safety_collecting_officer'
        ];
        return $this->_commonmodel->printReceiptoccu($data,$this->ortype_id);
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        
        if($request->input('submit')==""){
            $arrTransactionNum=array(""=>"Please Select");
            $arrFund=array(""=>"Select");
            $arrBank=array(""=>"Select");
            $arrCancelReason = array(""=>"Please Select");
            $arrChequeTypes = array(""=>"Select");
            $arrUser = array(""=>"Please Select");
            $arrTfocFees = array(""=>"Please Select");
            $arrCheque=array();
            $arrBankDtls=array();
            $arrChequeBankDtls=array("id"=>"","check_type_id"=>"","opayment_date"=>"","fund_id"=>"","bank_id"=>"","bank_account_no"=>"","opayment_transaction_no"=>"","opayment_check_no"=>"","opayment_amount"=>"");
             $i=0;
            foreach($arrChequeBankDtls as $key=>$val){
                $arrCheque[$i][$key]=$val;
                $arrBankDtls[$i][$key]=$val;
            }
            foreach ($this->_HealthandSafety->getFundCode() as $val) {
                $arrFund[$val->id]=$val->code;
            } 
            foreach ($this->_HealthandSafety->getBankList() as $val) {
                $arrBank[$val->id]=$val->bank_code;
            }
            foreach ($this->_HealthandSafety->getTransactions($request->input('id')) as $val) {
                $arrTransactionNum[$val->id]=$val->transaction_no;
            } 
            foreach ($this->_HealthandSafety->getCancelReason() as $val) {
                $arrCancelReason[$val->id]=$val->ocr_reason;
            }
            foreach ($this->_HealthandSafety->getChequeTypes() as $val) {
                $arrChequeTypes[$val->id]=$val->ctm_description;
            }
            foreach ($this->_HealthandSafety->getTaxFees($request->input('id')) as $val) {
                $arrTfocFees[$val->id]=$val->description;
            }
            $isOrAssigned = (int)$this->_HealthandSafety->checkORAssignedORNot();
            $arrNature=array();
            $arrDocumentDetailsHtml='';
        }
        
        $data = (object)$this->data;
        $data->top_transaction_id ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_HealthandSafety->getEditDetails($request->input('id'));
            if(isset($data)){
                if($data->payee_type==1){
                    foreach ($this->_HealthandSafety->getTaxpayers($data->client_citizen_id) as $val) {
                        $arrUser[$val->id]=$val->full_name;
                    }
                }else{
                    foreach ($this->_HealthandSafety->getCitizens($data->client_citizen_id) as $val) {
                        $arrUser[$val->id]=$val->cit_fullname;
                    }
                }
                $data->created_at = date("d/m/Y",strtotime($data->created_at));
                $data->cashier_or_date = date("d/m/Y",strtotime($data->cashier_or_date));
                $arrFeesDtls = $this->_HealthandSafety->getNatureFeeDetails($data->id);
                if(count($arrFeesDtls)>0){
                    $arrNature = json_decode(json_encode($arrFeesDtls), true);
                }
                $arrdocDtls = $this->generateDocumentList($data->document_json,$data->id);
                if(isset($arrdocDtls)){
                    $arrDocumentDetailsHtml = $arrdocDtls;
                }
            }
            $arrPaymentDetails = $this->_HealthandSafety->getPaymentModeDetails($request->input('id'),3);
            $arrCheque = json_decode(json_encode($arrPaymentDetails), true);

            $arrPaymentDetails = $this->_HealthandSafety->getPaymentModeDetails($request->input('id'),2);
            $arrBankDtls = json_decode(json_encode($arrPaymentDetails), true);
            
        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            foreach((array)$this->dataDtls as $key=>$val){
                $this->dataDtls[$key] = $request->input($key);
            }
            $getortype = $this->_HealthandSafety->GetOrtypeid('6');
            $this->data['ortype_id'] =  $getortype->ortype_id;
            //$this->data['ortype_id'] =  2; // Accountable Form No. 51-C
            $clientdata = $this->_commonmodel->getCitizenName($this->data['client_citizen_id']);
            $taxpayername = $clientdata->cit_fullname;
            
            $this->data['taxpayers_name'] = $taxpayername;
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='6';
            $this->data['net_tax_due_amount'] = $this->data['total_amount'];
     		$this->data['payee_type'] = '2';
            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
          
            if($request->input('id')>0){
                unset($this->data['created_at']);
                unset($this->data['cashier_batch_no']);
                unset($this->data['cashier_or_date']);
                $this->_HealthandSafety->updateData($request->input('id'),$this->data);
                $success_msg = 'Cashering updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = '1'; 
                $this->data['payment_type'] = 'Walk-In';
                $this->data['cashier_or_date'] = $request->input('cashier_or_date');
                $this->data['cashier_year'] = date('Y');
                $this->data['cashier_month'] = date('m');
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->data['or_no']);
                if($getorRegister != Null){
                     $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_HealthandSafety->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no;
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_HealthandSafety->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_HealthandSafety->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }     
                }

                $this->data['cashier_issue_no'] = $issueNumber; 
                $this->data['cashier_batch_no'] = $cashier_batch_no; 
                if(!empty($request->input('client_topno'))){
                   $this->data['top_transaction_id'] = $request->input('client_topno'); 
                }
                $lastinsertid = $this->_HealthandSafety->addData($this->data);
                if($request->input('client_topno')>0){
                 $gethodata = $this->_HealthandSafety->getHoappid($request->input('client_topno'));   
                 $hoappupdata = array();
                 $hoappupdata['cashier_id'] = $lastinsertid;
                 $hoappupdata['or_date'] = date("Y-m-d");
                 $hoappupdata['lab_req_or'] = $this->data['or_no'];
                 $this->_HealthandSafety->updateHoappData($gethodata->transaction_ref_no,$hoappupdata);
                }
                // update latest used OR
                if($request->input('isuserrange')){
                    $arrOrData = array('latestusedor' => $this->data['or_no']);
                    $ortype_id=$getortype->ortype_id; // Accountable Form No. 51-C
                    $this->_HealthandSafety->updateOrUsed($ortype_id,$arrOrData);
                }
                $this->dataDtls['cashier_id'] = $lastinsertid;
                $this->dataDtls['cashier_issue_no'] =$issueNumber;
                $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
                $success_msg = 'Cashering Health And safety added successfully.';
                Session::put('HEALT_PRINT_CASHIER_ID',$lastinsertid);

            }

            $Cashierid = $lastinsertid;
            $arrDetails = $request->input('tfoc_id');
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    $arrTfoc = $this->_HealthandSafety->getTfocDtls($value);
                    $this->dataDtls['tfoc_id'] =$value;
                    $this->dataDtls['payee_type'] = '1';
                    $this->dataDtls['tfc_amount'] = $request->input('tfc_amount')[$key];
                    $this->dataDtls['all_total_amount'] = $request->input('tfc_amount')[$key];
                    if(!empty($request->input('ctc_taxable_amount')[$key])){
                    $this->dataDtls['ctc_taxable_amount'] = $request->input('ctc_taxable_amount')[$key];
                    }
                    $this->dataDtls['or_no'] = $this->data['or_no'];
                    $this->dataDtls['ortype_id'] =  $getortype->ortype_id; // Accountable Form No. 51-C

                    $fundid = "0"; $glaccountid ="0"; $slid="0";
                    if(!empty($arrTfoc)){
                    $this->dataDtls['agl_account_id'] = $arrTfoc->gl_account_id;
                    $this->dataDtls['sl_id'] = $arrTfoc->sl_id;
                     $fundid = $arrTfoc->fund_id; 
                     $glaccountid = $arrTfoc->gl_account_id;
                     $slid = $arrTfoc->sl_id;
                    }
                    
                    $checkdetailexist =  $this->_HealthandSafety->checkRecordIsExist($value,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_HealthandSafety->updateCashierDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $this->dataDtls['created_by'] =\Auth::user()->id;
                        $this->dataDtls['created_at'] = date('Y-m-d H:i:s');
                        $this->dataDtls['cashier_year'] = date('Y');
                        $this->dataDtls['cashier_month'] = date('m');
                        $cashierdetailid = $this->_HealthandSafety->addCashierDetailsData($this->dataDtls);
                        $this->_commonmodel->insertCashReceipt($value, $request->input('tfc_amount')[$key], $request->or_no,$request->cashier_particulars);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $cashierdetailid;
                        $addincomedata['tfoc_is_applicable'] = '6';
                        $addincomedata['taxpayer_name'] = $taxpayername;
                        $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                        $addincomedata['amount'] = $request->input('tfc_amount')[$key];
                        $addincomedata['tfoc_id'] = $value;
                        $addincomedata['fund_id'] = $fundid;
                        $addincomedata['gl_account_id'] = $glaccountid;
                        $addincomedata['sl_account_id'] = $slid;
                        $addincomedata['cashier_or_date'] = $request->input('cashier_or_date');
                        $addincomedata['or_no'] = $this->data['or_no'];
                        $addincomedata['form_code'] = $coaddata->cpor_series;
                        $addincomedata['or_register_id'] =  $getorRegister->id;
                        $addincomedata['or_from'] =  $getorRegister->ora_from;
                        $addincomedata['or_to'] =  $getorRegister->ora_to;
                        $addincomedata['coa_no'] =  $coaddata->coa_no;
                        $addincomedata['is_collected'] =  0;
                        $addincomedata['created_by'] =  \Auth::user()->id;
                        $addincomedata['created_at'] =  date('Y-m-d H:i:s');

                        $this->_commonmodel->addcashierIncomeData($addincomedata);
                    }
                }
            }
            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Health And safety Cashering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);

            $arrData=array();  $top_transaction_id = $request->input('client_topno');
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_HealthandSafety->updateTopTransaction($top_transaction_id,$arrData);
            // Log Details End
            $smsTemplate=SmsTemplate::where('id',66)->where('is_active',1)->first();
            if($this->data['payee_type']==1){
              $arrData = $this->_HealthandSafety->getappdatataxpayer($gethodata->transaction_ref_no);
            }else{
               $arrData = $this->_HealthandSafety->getappdatacitizen($gethodata->transaction_ref_no); 
            }
            if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
            {
                    $receipient=$arrData->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                    //$msg = str_replace('<TOP_NO>',$transactionno,$msg);
                    $msg = str_replace('<AMOUNT>',$this->data['total_amount'],$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                   $this->send($msg, $receipient);
            }
            return redirect()->route('HealthAndSafetyCashiering.index')->with('success', __($success_msg));
        }
        return view('health-and-safety.Cashering.create',compact('data','arrTransactionNum','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned','arrUser','arrNature','arrTfocFees','arrDocumentDetailsHtml'));
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

    public function deleteAttachment(Request $request){
        $id = $request->input('id');
        $fname = $request->input('fname');
        
        $arrAss = $this->_HealthandSafety->getCasheirDtls($id);
        if(isset($arrAss)){
            $arrJson = json_decode($arrAss->document_json,true);
            if(isset($arrJson)){
                $key  = array_search($fname, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/cashier_documents/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['document_json'] = json_encode($arrJson);
                    $this->_HealthandSafety->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $image = $request->file('file');
        $filename=$image->getClientOriginalName();
        $arrAss = $this->_HealthandSafety->getCasheirDtls($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrAss)){
            $arrJson = (array)json_decode($arrAss->document_json,true);
            $key  = array_search($filename, array_column($arrJson, 'filename'));
            if($key !== false){
                $message="This document is already exist";
                $ESTATUS=1;
            }
        }
        if(empty($message)){
            $destinationPath =  public_path().'/uploads/cashier_documents/';
            if(!File::exists($destinationPath)) { 
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $image->move($destinationPath, $filename);
            $arrData = array();
            $arrData['filename'] = $filename;
            $finalJsone[] = $arrData;
            if(isset($arrAss)){
                $arrJson = json_decode($arrAss->document_json,true);
                if(isset($arrJson)){
                    $arrJson[] = $arrData;
                    $finalJsone = $arrJson;
                }
            }
            $data['document_json'] = json_encode($finalJsone);
            $this->_HealthandSafety->updateData($id,$data);
            $arrDocumentList = $this->generateDocumentList($data['document_json'],$id);
            
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$aid, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $filename = wordwrap($val['filename'], 10, "<br>\n");
                    $html .= "<tr>
                        <td><span class='showLess'>".$filename."</span></td>
                        <td><a class='btn' href='".asset('uploads/cashier_documents').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
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
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_HealthandSafety->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function checkOrUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $id = $cashierId = $request->input('cashier_id');
        $isUsed = $this->_HealthandSafety->checkOrUsedOrNot($or_no,$id);
        $arr['isUsed']=$isUsed;
        if($isUsed){
            $arr['errMsg']='This O.R No already used. Please try other';
        }
        if(empty($isUsed)){
        $isUsed = $this->_commonmodel->checkOrinrange($or_no,$this->ortype_id);
        if(empty($isUsed)){
            $arr['isUsed']= 1;
            $arr['errMsg']='This O.R No not available in Or range';
        } }
        echo json_encode($arr);
    }
   
    public function cancelOr(Request $request){
        $pswVeriStatus = (session()->has('casheringVerifyPsw'))?((session()->get('casheringVerifyPsw') == true)?true:false):false;
       // dd($pswVeriStatus);
        if(!$pswVeriStatus){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
        session()->forget('casheringVerifyPsw');
        $id = $cashierId = $request->input('cashier_id');
        $ocr_id= $request->input('cancelreason');
        $remark= $request->input('remarkother');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'status'=>'0');
        $this->_HealthandSafety->updateData($id,$updataarray);
        if(!empty($request->input('toptno'))){
            $arrData=array();  $top_transaction_id = $request->input('toptno');
            $arrData['is_paid']=0;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_HealthandSafety->updateTopTransaction($top_transaction_id,$arrData);
        }
        $this->_commonmodel->deletecashierincome($id);
        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Health And Safety Cashering O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
        $data=array('status'=>'success',"message"=>"");
        echo json_encode($data);
        //return redirect()->route('HealthAndSafetyCashiering.index')->with('success', __('O.R Cancelled Successfully.'));
    }
    public function cancelNaturePaymentOption(Request $request){
        $f_id =  $request->input('f_id');
        $this->_HealthandSafety->deleteCashieringDetails($f_id);
        $arr['ESTATUS']=0;
        $arr['message']="Deleted Successfully";
        echo json_encode($arr);exit;
    
    }
    public function getOrnumber(Request $request){ 
        $checkflag = $request->input('orflag');
        $orNumber=1;
        $ortype_id=$this->ortype_id;  // Accountable Form No. 51-C
        if($checkflag == '1'){
            $getorno = $this->_HealthandSafety->getLatestOrNumber($ortype_id);
            if(!empty($getorno->or_no)){
                $orNumber = $getorno->or_no +1;
            }
        }else{
            $getorrange = $this->_commonmodel->getGetOrrange($this->ortype_id,\Auth::user()->id);
            if(empty($getorrange->latestusedor)){
               if(!empty($getorrange->ora_from)){
                    $orNumber = $getorrange->ora_from;
                }
            }else{
                $orNumber = $getorrange->latestusedor + 1;
            }
        }
        $orNumber = str_pad($orNumber, 7, '0', STR_PAD_LEFT);
        echo $orNumber;
    }
    public function getUserList(Request $request){
        $payee_type = $request->input('payee_type');
        $options="<option>Please Select</opton>";
        if($payee_type==1){
            foreach ($this->_HealthandSafety->getTaxpayers() as $val) {
                $name = $val->full_name;
                $options .="<option value=".$val->id.">".$name."</option>";
            }
        }else{
            foreach ($this->_HealthandSafety->getCitizens() as $val) {
                $name = $val->cit_fullname;
                $options .="<option value=".$val->id.">".$name."</option>";
            }
        }
        $arr['ESTATUS']=0;
        $arr['option']=$options;
        echo json_encode($arr);exit;
    }

    public function getUserbytoid(Request $request){
        $payee_type = 2;
        $topid = $request->input('topid');
        $arrDtls = $this->_HealthandSafety->getUserDetailsbytopid($topid);
        $arr['ESTATUS']=1;
        if(isset($arrDtls)){
            $arr['ESTATUS']=0;
                $arr['address']= $this->_commonmodel->getCitizenAddress($arrDtls->cit_id);
                $arr['name']= $this->_commonmodel->getUserName($arrDtls->cit_first_name,$arrDtls->cit_middle_name,$arrDtls->cit_last_name,$arrDtls->cit_suffix_name);
                $arr['citid'] = $arrDtls->cit_id;
                $arr['hoid']=$arrDtls->hlrid;
                $arr['amount']= $arrDtls->lab_req_amount;
        }
        echo json_encode($arr);exit;
    }

    public function getAmountDetails(Request $request){
        $tfoc_id = $request->input('tfoc_id');
        $arrFee = $this->_HealthandSafety->getTaxFeesDetails($tfoc_id);
        $arr['ESTATUS']=1;
        $arr['amount']="0.00";
        if(isset($arrFee)){
            $arr['ESTATUS']=0;
            $arr['amount']=$arrFee->tfoc_amount;
        }
        echo json_encode($arr);exit;
    }
    public function getUserDetails(Request $request){
        $payee_type = 2;
        $user_id = $request->input('user_id');
        $arrDtls = $this->_HealthandSafety->getUserDetails($payee_type,$user_id);
        $arr['ESTATUS']=1;
        if(isset($arrDtls)){
            $arr['ESTATUS']=0;
                $arr['address']= $this->_commonmodel->getCitizenAddress($user_id);
                $arr['name']= $this->_commonmodel->getUserName($arrDtls->cit_first_name,$arrDtls->cit_middle_name,$arrDtls->cit_last_name,$arrDtls->cit_suffix_name);
           
        }
        echo json_encode($arr);exit;
    }
    
}
