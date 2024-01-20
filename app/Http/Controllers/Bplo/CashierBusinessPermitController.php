<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CashierBusinessPermit;
use App\Models\CommonModelmaster;
use App\Models\BploBussinessPermit;
use App\Models\BploAssessmentCalculationCommon;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\Carbon;
use DB;
use Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;

class CashierBusinessPermitController extends Controller
{
    public $data = [];
    public $dataDtls = [];
    private $slugs;
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->_CashierBusinessPermit = new CashierBusinessPermit(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->_bplobusinesspermit = new BploBussinessPermit();
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("Y-m-d"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'','total_paid_interest'=>'','payment_terms'=>'1','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'1','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');

        $this->dataDtls = array('cashier_year'=>date('Y'),'top_transaction_id'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'');
        $this->slugs = 'cashier/cashier-business-permits';
        $getortype = $this->_bplobusinesspermit->GetOrtypeid('1');
        $this->ortype_id =  $getortype->ortype_id; 
    }
    public function index(Request $request){
        //$this->is_permitted($this->slugs, 'read');
        return view('Bplo.casheir.index');
    }
    public function getList(Request $request){
        $data=$this->_CashierBusinessPermit->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $ownar_name=$row->full_name;
            /* if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            } */
            $cashier_name='';
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['transaction_no']=$row->transaction_no;
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_amount']=number_format($row->total_amount,2);
            $arr[$i]['tax_credit_amount']=number_format($row->tax_credit_amount,2);
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';

            $arr[$i]['cashier_name']=$this->_commonmodel->getCreatedByName($row->created_by);
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cashier/cashier-business-permit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Business Permit Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="'.url('/cashier/cashier-business-permit/printReceipt?id='.$row->id).'" target="_blank" title="Print Business Permit"  data-title="Print Business Permit"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
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
    
    public function store(Request $request){
        $arrTransactionNum=array(""=>"Please Select");
        $arrFund=array(""=>"Select");
        $arrBank=array(""=>"Select");
        $arrCancelReason = array(""=>"Please Select");
        $arrChequeTypes = array(""=>"Select");
        $arrCheque=array();
        $arrBankDtls=array();
        $arrChequeBankDtls=array("id"=>"","check_type_id"=>"","opayment_date"=>"","fund_id"=>"","bank_id"=>"","bank_account_no"=>"","opayment_transaction_no"=>"","opayment_check_no"=>"","opayment_amount"=>"");
        $i=0;
        foreach($arrChequeBankDtls as $key=>$val){
           // $arrCheque[$i][$key]=$val;
            //$arrBankDtls[$i][$key]=$val;
        }
        foreach ($this->_CashierBusinessPermit->getFundCode() as $val) {
            $arrFund[$val->id]=$val->code;
        } 
        foreach ($this->_CashierBusinessPermit->getBankList() as $val) {
            $arrBank[$val->id]=$val->bank_code;
        }
        foreach ($this->_CashierBusinessPermit->getTransactions($request->input('id')) as $val) {
            $arrTransactionNum[$val->id]=$val->transaction_no;
        } 
        foreach ($this->_CashierBusinessPermit->getCancelReason() as $val) {
            $arrCancelReason[$val->id]=$val->ocr_reason;
        }
        foreach ($this->_CashierBusinessPermit->getChequeTypes() as $val) {
            $arrChequeTypes[$val->id]=$val->ctm_description;
        }
        $isOrAssigned = (int)$this->_CashierBusinessPermit->checkORAssignedORNot();

        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CashierBusinessPermit->getEditDetails($request->input('id'));
            $data->created_at = date("d/m/Y",strtotime($data->created_at));
            $arrPaymentDetails = $this->_CashierBusinessPermit->getPaymentModeDetails($request->input('id'),3);
            $arrCheque = json_decode(json_encode($arrPaymentDetails), true);

            $arrPaymentDetails = $this->_CashierBusinessPermit->getPaymentModeDetails($request->input('id'),2);
            $arrBankDtls = json_decode(json_encode($arrPaymentDetails), true);
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            foreach((array)$this->dataDtls as $key=>$val){
                $this->dataDtls[$key] = $request->input($key);
            }
            $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
            $taxpayername = $clientdata->full_name;
            $this->data['taxpayers_name'] = $taxpayername;
            $this->data['cashier_particulars']='Business Permit Fee';
            $this->data['ortype_id'] =  $this->ortype_id; // Accountable Form No. 51-C

            $this->dataDtls['cashier_year'] = $this->data['cashier_year'] = date('Y');
            $this->dataDtls['cashier_month'] = $this->data['cashier_month'] = date('m');
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='1';
            $this->dataDtls['payee_type'] = $this->data['payee_type'] = "1";
            $this->dataDtls['client_citizen_id'] =$this->data['client_citizen_id'];

            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
            // Apply tax credit
            if($request->input('tcm_id')>0 && $this->data['total_amount_change']>0){
                $this->data['tax_credit_amount'] = $this->data['total_amount_change'];
            }

            if($request->input('id')>0){
                unset($this->data['created_at']);
                unset($this->data['cashier_batch_no']);
                $this->_CashierBusinessPermit->updateData($request->input('id'),$this->data);
                $success_msg = 'Cashiering updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->dataDtls['created_by'] = $this->data['created_by']=\Auth::user()->id;
                $this->dataDtls['created_at'] = $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = '1'; 
                $this->data['payment_type'] = 'Walk-In';
                $this->data['cashier_or_date'] = date("Y-m-d");
                
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;
                $coaddata = '';
                $getorRegister = $this->_commonmodel->Getorregisterid($this->ortype_id,$this->data['or_no']);
                if($getorRegister != Null){
                    $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                    $this->_CashierBusinessPermit->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                      
                    $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                    $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                    $this->data['or_register_id'] =  $getorRegister->id; 
                    $this->data['coa_no'] =  $coaddata->coa_no; 
                    if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_CashierBusinessPermit->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_CashierBusinessPermit->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                    }   
                }

                $this->data['cashier_issue_no'] = $issueNumber; 
                $this->data['cashier_batch_no'] = $cashier_batch_no; 
                $lastinsertid = $this->_CashierBusinessPermit->addData($this->data);

                //Convert application For Issuance
                Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$this->data['busn_id']); // This for remote server
                $this->_CashierBusinessPermit->updateBusinessStatus((int)$this->data['busn_id'],array('busn_app_status'=>'5','is_final_assessment'=>'1'));
                
                // update latest used OR
                if($request->input('isuserrange')){
                    $arrOrData = array('latestusedor' => $this->data['or_no']);
                    $ortype_id=2;  // Accountable Form No. 51-C
                    $this->_CashierBusinessPermit->updateOrUsed($ortype_id,$arrOrData);
                }

                // set used credit amount
                $this->updateUsedCreditAmount($request->input('previous_cashier_id'),1);

                $this->dataDtls['cashier_id'] = $lastinsertid;
                $this->dataDtls['cashier_issue_no'] =$issueNumber;
                $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
                $success_msg = 'Cashiering added successfully.';
            }

            $Cashierid = $lastinsertid;
            $arrEndDpt= $this->_CashierBusinessPermit->getEndDeptDetails();
            $arrDetails = $request->input('tfoc_id');
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    $arrTfoc = $this->_CashierBusinessPermit->getTfocDtls($value);
                    $this->dataDtls['tfoc_id'] =$value;
                    $this->dataDtls['interest_fee'] = $request->input('interest_fee')[$key];
                    $all_total_amount = $request->input('tfc_amount')[$key] + $request->input('surcharge_fee')[$key] + $request->input('interest_fee')[$key];
                    $this->dataDtls['tfc_amount'] = $request->input('tfc_amount')[$key];
                    $this->dataDtls['all_total_amount'] = $all_total_amount;
                    $this->dataDtls['surcharge_sl_id'] = $request->input('surcharge_sl_id')[$key];
                    $this->dataDtls['surcharge_fee'] = $request->input('surcharge_fee')[$key];
                    $this->dataDtls['interest_sl_id'] = $request->input('interest_sl_id')[$key];
                    $this->dataDtls['interest_fee'] = $request->input('interest_fee')[$key];
                    $this->dataDtls['or_no'] = $this->data['or_no'];
                    $this->dataDtls['ortype_id'] =  2; // Accountable Form No. 51-C
                    $this->dataDtls['agl_account_id'] = $arrTfoc->gl_account_id;
                    $this->dataDtls['sl_id'] = $arrTfoc->sl_id;
                    $this->dataDtls['subclass_id'] = $request->input('subclass_id')[$key];
                    $arrSubClass = $this->_CashierBusinessPermit->getSubClassDtls($this->dataDtls['subclass_id']);
                    if(isset($arrSubClass)){
                        $this->dataDtls['section_id'] = $arrSubClass->section_id;
                        $this->dataDtls['division_id'] = $arrSubClass->division_id;
                        $this->dataDtls['group_id'] = $arrSubClass->group_id;
                        $this->dataDtls['class_id'] = $arrSubClass->class_id;
                    }
                    $checkdetailexist =  $this->_CashierBusinessPermit->checkRecordIsExist($value,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_CashierBusinessPermit->updateCashierDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $cashierDetailsId = $this->_CashierBusinessPermit->addCashierDetailsData($this->dataDtls);
                        // Update OR Details In Location Clearance
                        if(isset($arrEndDpt)){
                            if($arrEndDpt->tfoc_id==$this->dataDtls['tfoc_id'] && $this->dataDtls['tfc_amount']>0){
                                $this->_CashierBusinessPermit->updateORInLocationClearance($this->data['busn_id'],$cashierDetailsId,$this->dataDtls);
                            }
                        }
                        // Insert Data in income table
                        $this->addCasheringIncomeDtls($cashierDetailsId,$getorRegister,$coaddata,$clientdata->p_barangay_id_no);
                        $this->_commonmodel->insertCashReceipt($this->dataDtls['tfoc_id'], $this->dataDtls['tfc_amount'], $this->data['or_no'], $this->data['cashier_particulars']);                    }
                }
            }
            $p_type = $request->input('payment_terms');
            if(!empty($request->input('fund_id'.$p_type))){
                foreach ($request->input('fund_id'.$p_type) as $key => $value){   
                    $paymentdata = array();
                    foreach($arrChequeBankDtls as $p_kay=>$p_val){
                        if($p_kay!='id' && isset($request->input($p_kay.$p_type)[$key])){
                            $paymentdata[$p_kay]=$request->input($p_kay.$p_type)[$key];
                        }
                    }
                    $paymentdata['opayment_amount'] = str_replace(",","", $paymentdata['opayment_amount']);
                    $paymentdata['cashier_id'] =$Cashierid;
                    $paymentdata['payment_terms'] = $p_type;
                    $paymentdata['updated_by'] =\Auth::user()->id;
                    $paymentdata['updated_at'] = date('Y-m-d H:i:s');
                    $pid = $request->input('pid'.$p_type)[$key];
                    if($pid > 0){
                        $this->_CashierBusinessPermit->updateCashierPaymentData($pid,$paymentdata);
                    }else{
                        $paymentdata['opayment_month'] = date('m');
                        $paymentdata['created_by'] = \Auth::user()->id;
                        $paymentdata['created_at'] = date('Y-m-d H:i:s');
                        $paymentdata['opayment_year'] = date('Y');
                        $paymentdata['status'] = 1;
                        $this->_CashierBusinessPermit->addCashierPaymentData($paymentdata);
                    }
                }
                $this->_CashierBusinessPermit->deleteOtherPaymentMode($p_type,$Cashierid);
            }
            // Save Payment done details
            $this->savePaymentDetails($Cashierid,$this->data['top_transaction_id']);
            
            //Add entry into for issuance
            $this->addIssuanceDetails();

            //Delete Delinquncy because payment paid
            $this->_assessmentCalculationCommon->deleteDelinquency($this->data['busn_id'],$this->data['app_code'],date("Y"));

            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Business Permit Cashiering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            // Log Details End

            Session::put('remote_cashier_id',$Cashierid);
            Session::put('PRINT_CASHIER_ID',$Cashierid);
            $smsTemplate=SmsTemplate::where('id',61)->where('is_active',1)->first();
                $arrData = $this->_CashierBusinessPermit->getappdatataxpayer($this->data['client_citizen_id']);
                if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
                {
                        $receipient=$arrData->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                        $msg = str_replace('<TOP_NO>',$this->data['top_transaction_id'],$msg);
                        $msg = str_replace('<AMOUNT>',$this->data['total_paid_amount'],$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                       $this->send($msg, $receipient);
                }
            $this->addPaymentHistory($this->data);    
            return redirect()->route('bplocashier.index')->with('success', __($success_msg));
        }
        return view('Bplo.casheir.create',compact('data','arrTransactionNum','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned'));
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

     public function addPaymentHistory($billdata){
            
            $payment_history = [
                'frgn_payment_id' => '0',
                'department_id' => '1',
                'client_id' => $billdata['client_citizen_id'],
                'bill_year' => $billdata['cashier_year'],
                'bill_month' => $billdata['cashier_month'],
                'bill_due_date' => $billdata['cashier_or_date'],
                'pm_id' => $billdata['pm_id'],
                'pap_id' => $billdata['pap_id'],
                'particulars' => $billdata['cashier_particulars'],
                'total_amount' => $billdata['total_amount'],
                'total_paid_amount' => $billdata['total_paid_amount'],
                'or_no' => $billdata['or_no'],
                'or_date' => $billdata['cashier_or_date'],
                'transaction_no' => $billdata['top_transaction_id'],
                'payment_status' => "2",
                'payment_date' => $billdata['cashier_or_date'],
                'payment_taransaction_id' => "",
                'is_approved' => 1,
                'is_synced' => 1
            ];
           
                $payment_history['busn_id']=$billdata['busn_id'];
                $payment_history['app_code']=$billdata['app_code'];
            //echo "<pre>"; print_r($payment_history); exit;
            $checktransexist =  $this->_commonmodel->checktrnsactionexist($billdata['top_transaction_id']);
            if(count($checktransexist) <=0){
                $pfrngid = $this->_commonmodel->addPaymentHistory($payment_history);
                $payment_history['frgn_payment_id']=$pfrngid;
                $this->_commonmodel->addPaymentHistoryremote($payment_history);
            }
        
    }

    public function addCasheringIncomeDtls($cashier_details_id,$getorRegister,$coaddata,$barangayid){
        $dataToSaveInIncomeTable = [];
        $commonIncomeData        = [
            'cashier_id' => $this->dataDtls['cashier_id'],
            'cashier_details_id' => $cashier_details_id,
            'tfoc_is_applicable' => 1,
            'taxpayer_name' => $this->data['taxpayers_name'],
            'barangay_id' => $barangayid,
            'cashier_or_date' => $this->data['cashier_or_date'],
            'or_no' => $this->dataDtls['or_no'],
            'is_collected'=>0,
            'created_by' => \Auth::user()->id,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        $arrTfoc = $this->_CashierBusinessPermit->getTfocDtls($this->dataDtls['tfoc_id']);
        if(isset($arrTfoc) && !empty($arrTfoc)){
            $commonIncomeData['fund_id'] = $arrTfoc->fund_id;
        }
        if(isset($getorRegister) && !empty($getorRegister)){
            $commonIncomeData['or_from'] = $getorRegister->ora_from;
            $commonIncomeData['or_to'] = $getorRegister->ora_to;
            $commonIncomeData['or_register_id'] = $getorRegister->id;
        }
        if(isset($coaddata) && !empty($coaddata)){
            $commonIncomeData['form_code'] = $coaddata->cpor_series;
            $commonIncomeData['coa_no'] = $coaddata->coa_no;
        }

        /* Basic Data */
        $commonIncomeData['tfoc_id'] = $this->dataDtls['tfoc_id']; 
        $commonIncomeData['gl_account_id'] = $this->dataDtls['agl_account_id'];
        $commonIncomeData['sl_account_id'] = $this->dataDtls['sl_id'];
        $commonIncomeData['amount'] = $this->dataDtls['tfc_amount'];
        $dataToSaveInIncomeTable[] = $commonIncomeData;
        /* Basic Data */

        /* Surcharge Details */
        if($this->dataDtls['surcharge_fee'] > 0){
            $arrTfoc = $this->_CashierBusinessPermit->getTfocDtlsFromSLID($this->dataDtls['surcharge_sl_id']);
            $commonIncomeData['tfoc_id'] = $this->dataDtls['tfoc_id']; 
            $commonIncomeData['gl_account_id'] = isset($arrTfoc)?$arrTfoc->gl_account_id:0;
            $commonIncomeData['sl_account_id'] = $this->dataDtls['surcharge_sl_id'];
            $commonIncomeData['amount'] = $this->dataDtls['surcharge_fee'];
            $dataToSaveInIncomeTable[] = $commonIncomeData;
        }
        /* Surcharge Details */

        /* Interest Details */
        if($this->dataDtls['interest_fee'] > 0){
            $arrTfoc = $this->_CashierBusinessPermit->getTfocDtlsFromSLID($this->dataDtls['interest_sl_id']);
            $commonIncomeData['tfoc_id'] = $this->dataDtls['tfoc_id']; 
            $commonIncomeData['gl_account_id'] = isset($arrTfoc)?$arrTfoc->gl_account_id:0;
            $commonIncomeData['sl_account_id'] = $this->dataDtls['interest_sl_id'];
            $commonIncomeData['amount'] = $this->dataDtls['interest_fee'];
            $dataToSaveInIncomeTable[] = $commonIncomeData;
        }
        /* Interest Details */

        
        /* Finally Insert Data in income table */
        foreach ($dataToSaveInIncomeTable as $finalData) {
            DB::table('cto_cashier_income')->insert($dataToSaveInIncomeTable);
        }
        /* Finally Insert Data in income table */
    }
    public function updateUsedCreditAmount($cashierId,$status){
        if($cashierId>0){
            $arr['tax_credit_is_useup']=(int)$status;
            $this->_CashierBusinessPermit->updateData($cashierId,$arr);
        }
    }
    public function addIssuanceDetails(){
        $user_id= \Auth::user()->id;
        $user= $this->_bplobusinesspermit->employeeData($user_id);
        $position = '';
        if(isset($user)){
            $position=$user->description;
        }
        $year = date('Y');
        if($this->data['app_code']==3){ 
            $this->addRetirementIssuance($this->data['busn_id'],$year,$position);
        }else{
            // Only for Renew And New
            $data = array();
            $arrBrng = $this->_CashierBusinessPermit->getBusinessDetails($this->data['busn_id']);
            if(isset($arrBrng)){
                $data['brgy_id'] = $arrBrng->busn_office_barangay_id;
                $data['client_id'] = $arrBrng->client_id;
                $data['pm_id'] = (int)$arrBrng->pm_id;
            }
            $data['busn_id'] = $this->data['busn_id'];
            $data['bpi_year'] = $year;
            $data['bpi_issued_date'] = date("Y-m-d");
            $data['bpi_month']=date("m");
            $data['app_type_id']=$this->data['app_code'];
            $data['bpi_issued_by']=\Auth::user()->id;
            $data['bpi_issued_position']=$position;
            $data['updated_by'] =  \Auth::user()->id;
            $data['updated_at'] =  date('Y-m-d H:i:s');
            $data['created_by']=\Auth::user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $issueNumber = $this->getPrevIssuanceNumber();
            $locality=$this->_bplobusinesspermit->getLocality();

            $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
            $bpi_permit_no = date('Y')."-".$locality."000-".$cashier_issue_no;
            $bpi_date_expired = date('Y') . '-12-31';
            $data['bpi_no'] = $cashier_issue_no; 
            $data['bpi_permit_no'] = $bpi_permit_no; 
            $data['bpi_date_expired'] = $bpi_date_expired; 
            $issuance_id = $this->_bplobusinesspermit->addData($data);
        }
    }
    public function addRetirementIssuance($busn_id,$year,$position){
        $arrRetire = $this->_CashierBusinessPermit->getRetirementDetails($busn_id,$year);
        if(isset($arrRetire)){
            $arr=array();
            $arr['busn_id']=$busn_id;
            $arr['retire_id']=$arrRetire->id;
            $arr['bri_year']=$year;
            $arr['bri_month']=date("m");
            $arr['retire_date_closed']=$arrRetire->retire_date_closed;
            $arr['bri_issued_date']=date("Y-m-d");
            $arr['client_id']=$arrRetire->client_id;
            $arr['pm_id']= (int)$arrRetire->pm_id;
            //$arr['bri_issued_by']=\Auth::user()->id;
            // $arr['bri_issued_position']="";
            $arr['status']=0;
            $arr['updated_by'] = \Auth::user()->id;
            $arr['updated_at'] = date('Y-m-d H:i:s');
            $arr['created_by']=\Auth::user()->id;
            $arr['created_at'] = date('Y-m-d H:i:s');
            $last_id = $this->_CashierBusinessPermit->addRetirementIssuance($arr);

            $address ="";
            foreach ($this->_commonmodel->getBarangay($arrRetire->busn_office_main_barangay_id)['data'] as $valadd) {
               $address =$valadd->brgy_name.", ".$valadd->mun_desc. ", ".$valadd->prov_desc. ", ".$valadd->reg_region;
            }

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/busicertificationsretirement.html'));
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $clientname = $arrRetire->full_name;
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{TAXPAYER}}',$clientname, $html);
            $html = str_replace('{{BUSINESSNAME}}',$arrRetire->busn_name, $html);
            $html = str_replace('{{CLOSEDDATE}}',date("M d, Y",strtotime($arrRetire->retire_date_closed)), $html);
            $html = str_replace('{{ISSUEDDATE}}',date("M d, Y",strtotime($arr['bri_issued_date'])), $html);
            $html = str_replace('{{POSITION}}','', $html);
            $html = str_replace('{{PERSONNELNAME}}','', $html);
            $html = str_replace('{{ADDRESS}}',$address, $html);

            $mpdf->WriteHTML($html);
            $retirefilename = $busn_id."retirecertificate.pdf";
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/digital_certificates/" . $retirefilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            $uptarray = array('bri_retirement_certificate_name'=>$retirefilename);
            $this->_CashierBusinessPermit->updateIssuanceData($last_id,$uptarray);
        }
    }
    public function getPrevIssuanceNumber(){
        $number=1;
        $arrPrev = $this->_bplobusinesspermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->bpi_no+1;
        }
        return $number;
    }

    public function creditAmountApply(){
        $arrExist = $this->_CashierBusinessPermit->checkCreditFacilityExist();
        if(isset($arrExist)){
            $arr['isValid']=1;
            $arr['tcm_id']=$arrExist->id;
            $arr['tax_credit_gl_id']=$arrExist->tcm_gl_id;
            $arr['tax_credit_sl_id']=$arrExist->tcm_sl_id;
        }else{
            $arr['isValid']=0;
            $url = url('/treasurer-tax-credit');
            $arr['errMsg']='Please add Tax Credit (Account Assignment) for Apply credit <a href="'.$url.'" target="_blank">Click Here</a>';
        }
        echo json_encode($arr);
    }
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_CashierBusinessPermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function checkOrUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $isUsed = $this->_CashierBusinessPermit->checkOrUsedOrNot($or_no);
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
    public function savePaymentDetails($cashierId,$top_transaction_id){
        if($cashierId>0){
            $arrData=array();
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_CashierBusinessPermit->updateTopTransaction($top_transaction_id,$arrData);

            //Update payment status in final assessments
            $arrAss = $this->_CashierBusinessPermit->getTopTransAssessmentIds($top_transaction_id);
            if(isset($arrAss)){
                $arrData=array();
                $arrData['payment_status']=1;
                $arrData['cashier_id']=$cashierId;
                $arrData['payment_date']=date('Y-m-d');
                $arrData['updated_by']=\Auth::user()->id;
                $arrData['updated_at']= date('Y-m-d H:i:s');
                $finalIds = explode(",",$arrAss->final_assessment_ids);
                $this->_CashierBusinessPermit->updateFinalAssessment($finalIds,$arrData);
            }
        }
    }
    public function cancelOr(Request $request){

        $id = $cashierId = $request->input('cashier_id');
        $transactionId = $request->input('top_id');
        $prev_cashier_id = $request->input('prev_cashier_id');
        $ocr_id= $request->input('cancelreason');
        $remark= $request->input('remarkother');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'status'=>'0');
        $this->_CashierBusinessPermit->updateData($id,$updataarray);
        
        $arrData=array();
        $arrData['is_paid']=0;
        $arrData['updated_by']=\Auth::user()->id;
        $arrData['updated_at']= date('Y-m-d H:i:s');
        $this->_CashierBusinessPermit->updateTopTransaction($transactionId,$arrData);

        //Update payment status in final assessments
        $arrAss = $this->_CashierBusinessPermit->getTopTransAssessmentIds($transactionId);
        if(isset($arrAss)){
            $arrData=array();
            $arrData['payment_status']=2; //Cancelled
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $finalIds = explode(",",$arrAss->final_assessment_ids);
            $this->_CashierBusinessPermit->updateFinalAssessment($finalIds,$arrData);
        }
        // update flag to zero for used credit amount
        $this->updateUsedCreditAmount($prev_cashier_id,0);


        DB::table('cto_cashier_income')->where('cashier_id',(int)$cashierId)->delete();
        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Business Permit O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End

        return redirect()->route('bplocashier.index')->with('success', __('O.R Cancelled Successfully.'));
    }
    public function getOrnumber(Request $request){ 
        $checkflag = $request->input('orflag');
        $orNumber=1;
        $ortype_id=$this->ortype_id;  // Accountable Form No. 51-C
        if($checkflag == '1'){
             $getorno = $this->_CashierBusinessPermit->getLatestOrNumber($ortype_id);
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

    public function getPaymentDetails(Request $request){
        $transactionId = $request->input('transactionId');
        $cashierId = $request->input('id');
        $previous_cashier_id = $request->input('previous_cashier_id');
        $arrTrans = $this->_CashierBusinessPermit->getTopTransactionDtls($transactionId);
		
        $html="";
        $totalAmount=0;
        $finalTotal=0;
        $totalCharges=0;
        $totalSurcharge=0;
        $totalInterest=0;
        $arr['from_pm_id']=0;$arr['from_period_id']=0;$arr['to_pm_id']=0;$arr['to_period_id']=0;
        $arr['from_year']=0;$arr['to_year']=0;
        if(isset($arrTrans)){
            $arr['busn_name']=$arrTrans->busn_name;
            $ownar_name=$arrTrans->full_name;
            /* if(!empty($arrTrans->suffix)){
                $ownar_name .=", ".$arrTrans->suffix;
            } */
            $arr['app_code_name']=($arrTrans->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$arrTrans->app_code]:''; 
            $arr['ownar_name']=$ownar_name;
            $arr['busn_address']=$this->_Barangay->findDetails($arrTrans->busn_office_main_barangay_id);
            $html .=$this->addHiddendField($arrTrans);
            $finalIds = explode(",",$arrTrans->final_assessment_ids);
            $assessDtlsIds = $this->_CashierBusinessPermit->getAssessmentDetails($arrTrans->busn_id,$finalIds);
            if(!empty($assessDtlsIds)){
                $finalAssesIds = explode(",",$assessDtlsIds);
                $arrFinal = $this->_CashierBusinessPermit->getFinalAssessmentDetails($arrTrans->busn_id,$finalAssesIds);
                $totalRecord = count($arrFinal);
                foreach($arrFinal AS $key=>$val){
                    if($key==0){
                        $arr['from_pm_id']=$val->payment_mode;
                        $arr['from_period_id']=$val->assessment_period;
                        $arr['from_year']=$val->assess_year;
                    }
                    $surchage_interest= $val->surcharge_fee+$val->interest_fee;
                    $totalSurcharge +=$val->surcharge_fee;
                    $totalInterest +=$val->interest_fee;
                    $totalCharges +=$surchage_interest;
                    $totalAmount +=$val->tfoc_amount;
                    $finalTotal +=$val->tfoc_amount+$surchage_interest;
                    $html .=$this->getPertucularHtml($val);
                }
                $html .=$this->generateFinalTotalHtml($totalAmount,$finalTotal,$totalCharges);
            }
            $arr['to_pm_id']=$arrTrans->pm_id;
            $arr['to_period_id']=$arrTrans->pap_id;
            $arr['to_year']=$arrTrans->top_year;

            // Check applied credit details
            $arrCreditDtls = $this->getAppliedCreditDetails($arrTrans->busn_id,$previous_cashier_id,$cashierId);
            $arr['tax_credit_amount']=$arrCreditDtls['tax_credit_amount'];
            $arr['previous_cashier_id']=$arrCreditDtls['previous_cashier_id'];
            $arr['previous_or_date']=$arrCreditDtls['previous_or_date'];
            $arr['previous_or_no']=$arrCreditDtls['previous_or_no'];
        }
        $arr['html']=$html;
        $arr['from_period']=$this->getPeriod($arr['from_pm_id'],$arr['from_period_id']);
        $arr['to_period']=$this->getPeriod($arr['to_pm_id'],$arr['to_period_id']);
        $arr['or_number']='4964651';
        $arr['cashier_date']=date("d/m/Y");
        
        $arr['totalAmount']=number_format($totalAmount,2);
        $arr['totalAmount']=number_format($totalAmount,2);
        $arr['finalTotal']=number_format($finalTotal,2);
        $arr['totalSurcharge']=number_format($totalSurcharge,2);
        $arr['totalInterest']=number_format($totalInterest,2);
        echo json_encode($arr);
    }

    public function getAppliedCreditDetails($busn_id,$previous_cashier_id,$cashierId){
        $arr['tax_credit_amount']='0.00';
        $arr['previous_cashier_id']='0';
        $arr['previous_or_date']='';
        $arr['previous_or_no']='N/A';
      
        $arrExist = $this->_CashierBusinessPermit->checkExistCreditAmout($busn_id,$previous_cashier_id,$cashierId);
        if(isset($arrExist)){
            $arr['tax_credit_amount']=number_format($arrExist->tax_credit_amount,2);
            $arr['previous_cashier_id']=$arrExist->id;
            if(isset($arrExist->cashier_or_date)){
                $arr['previous_or_date']=date("d/m/Y",strtotime($arrExist->cashier_or_date));
            }
            $arr['previous_or_no']=$arrExist->or_no;
        }
        return $arr;
    }
    public function getPeriod($pm_id,$period_id){
        $html='';
        if($pm_id>0 && $period_id>0){
            $priod = config('constants.payModePartitionShortCut')[$pm_id][$period_id];
            $html='<option value="'.$period_id.'" selected>'.$priod.'</option>';
        }
        return $html;
    }
    public function addHiddendField($val){
        $html ='
        <input type="hidden" name="client_citizen_id" value="'.$val->client_id.'">
        <input type="hidden" name="busn_id" value="'.$val->busn_id.'">
        <input type="hidden" name="app_code" value="'.$val->app_code.'">
        <input type="hidden" name="pm_id" value="'.$val->pm_id.'">
        <input type="hidden" name="pap_id" value="'.$val->pap_id.'">';
        return $html;
    }
    public function getPertucularHtml($val){
        $surchage_interest = $val->surcharge_fee+$val->interest_fee;
        $total = $val->tfoc_amount+$surchage_interest;
        $html = '<tr class="font-style">
            <td>'.$val->assess_year.'</td>
            <td>'.$val->description.'</td>
            <td>'.number_format($val->tfoc_amount,2).'</td>
            <td>'.number_format($surchage_interest,2).'</td>
            <td>'.number_format($total,2).'</td>
        </tr>';

        $html .='
        <input type="hidden" name="surcharge_sl_id[]" value="'.$val->surcharge_sl_id.'">
        <input type="hidden" name="surcharge_fee[]" value="'.$val->surcharge_fee.'">
        <input type="hidden" name="interest_sl_id[]" value="'.$val->interest_sl_id.'">
        <input type="hidden" name="interest_fee[]" value="'.$val->interest_fee.'">
        <input type="hidden" name="subclass_id[]" value="'.$val->subclass_id.'">
        <input type="hidden" name="tfoc_id[]" value="'.$val->tfoc_id.'">
        <input type="hidden" name="tfc_amount[]" value="'.$val->tfoc_amount.'">';
        return $html;
    }
    public function generateFinalTotalHtml($totalAmount,$finalTotal,$totalCharges){
        $html = '<tr class="font-style">
            <td colspan="2" style="text-align: right;"><b>Total</b></td>
            <td>'.number_format($totalAmount,2).'</td>
            <td>'.number_format($totalCharges,2).'</td>
            <td class="red">'.number_format($finalTotal,2).'</td>
        </tr>';
        return $html;
    }

    public function printReceipt(Request $request)
    {
        $id = $request->input('id'); 
        $ctcdata = $this->_CashierBusinessPermit->getCertificateDetails($id);
        $cashiername = $this->_commonmodel->getemployeefullname($ctcdata->created_by);
        $defaultFeesarr = $this->_CashierBusinessPermit->GetReqiestfees($id);
        // dd($defaultFeesarr);
        // cash details
        if(isset($ctcdata)){
            switch ($ctcdata->payment_terms) {
                case 2:
                    $arrPaymentbankDetails = $this->_CashierBusinessPermit->GetPaymentbankdetails($id);
                    break;

                case 3:
                    $arrPaymentbankDetails = $this->_CashierBusinessPermit->GetPaymentcheckdetails($id);
                    break;
                
                default:
                    $arrPaymentbankDetails =  (object)[]; 
                    break;
            }

            // print reciept
            $data = [
                'transacion_no' => $ctcdata->or_no,
                'date' => $ctcdata->created_at,
                'or_number' => $ctcdata->or_no,
                'payor' => $ctcdata->full_name,
                'transactions' => $defaultFeesarr,
                'total' => $ctcdata->total_amount,
                'payment_terms' => $ctcdata->payment_terms,
                'cash_details' => $arrPaymentbankDetails,
                'surcharge' => $ctcdata->total_paid_surcharge,
                'interest' => $ctcdata->total_paid_interest,
                'cashierid' => $ctcdata->created_by,
                'cashiername' => $cashiername->fullname,
                'varName'=>'cashier_business_permit_collecting_officer'
                
            ];
            // echo "<pre>"; print_r($data); exit;   
            return $this->_commonmodel->printReceiptoccu($data,$this->ortype_id);
        }
    }
    public function updateCashierBillHistoryTaxpayers(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            
            $cashier_id = $request->input('remote_cashier_id');
            $arrCash = $this->_CashierBusinessPermit->getEditDetails($cashier_id);
            if(isset($arrCash)){
                $transaction_no = DB::table('cto_top_transactions')->where('id',$arrCash->top_transaction_id)->pluck('transaction_no')->first();
                if(isset($transaction_no)){
                    $data['or_no'] = $arrCash->or_no;
                    $data['or_date'] = $arrCash->cashier_or_date;
                    $data['total_paid_amount'] = $arrCash->total_paid_amount;
                    $data['payment_status'] = 1;
                    $data['payment_date'] = date("Y-m-d");
                    $data['is_synced'] = 0;

                    //This is for Main Server
                    DB::table('bplo_bill_summary')->where('busn_id',$arrCash->busn_id)->where('transaction_no',$transaction_no)->where('client_id',$arrCash->client_citizen_id)->update($data);


                    // This is for Remote Server
                    try {
                        $remortServer = DB::connection('remort_server');
                        $remortServer->table('bplo_bill_summary')->where('busn_id',$arrCash->busn_id)->where('transaction_no',$transaction_no)->where('client_id',$arrCash->client_citizen_id)->update($data);
                        DB::table('bplo_bill_summary')->where('busn_id',$arrCash->busn_id)->where('transaction_no',$transaction_no)->where('client_id',$arrCash->client_citizen_id)->update(array('is_synced'=>1));
                    }catch (\Throwable $error) {
                        return $error;
                    }
                    echo json_encode(array("message"=>"Successfully updated"));
                }
            }
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }
}
