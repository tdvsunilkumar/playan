<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CashierRealPropertyController;
use App\Models\Bplo\CashierBusinessPermit;
use App\Models\CashierRealProperty;
use App\Models\Cpdo\CpdoCashering;
use App\Models\CommonModelmaster;
use App\Models\BploBussinessPermit;
use App\Models\BploAssessmentCalculationCommon;
use App\Models\Engneering\OccupancyCashering;
use App\Models\Engneering\EngCashering;
use App\Models\Barangay;
use Session;
use DB;
use Carbon\Carbon;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Repositories\ComponentSMSNotificationRepository;
use App\Helpers\Helper;

class OnlinePaymentHistory extends Model
{
    public $data = [];
    public $dataDtls = [];
    public $palanDevtdata = [];
    public $occupancyData = [];
    public $engData = [];
    private $slugs;
    public $ortype_id ="";
    public $realPropDataFromCashering;
    public $casheringContrObj;
    public $realPropDetailsFromCashering;
    public function __construct(){
        $this->_CashierBusinessPermit = new CashierBusinessPermit(); 
        $this->_CashierRealProperty = new CashierRealProperty();
        $this->_occupancycashering = new OccupancyCashering();
        $this->_cpdocashering = new CpdoCashering();
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->_bplobusinesspermit = new BploBussinessPermit();
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("d/m/Y"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'','total_paid_interest'=>'','payment_terms'=>'1','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'1','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');

        $this->dataDtls = array('cashier_year'=>date('Y'),'top_transaction_id'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'');
        $this->rpData = array('cashier_year'=>date('Y'),'top_transaction_id'=>'','cb_code'=>'','rp_code'=>'','pk_code'=>'','rp_tax_declaration_no'=>'');

        $this->occupancyData = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','payment_terms'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Occupancy Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'');

        $this->_engineeringcashering = new EngCashering();
        $this->_cpdocashering = new CpdoCashering();
        $this->engData = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','payment_terms'=>'','or_no'=>'','total_amount'=>'','createdat'=>date("d/m/Y"),'total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Engineering Fee','ctc_place_of_issuance'=>'');
        $this->palanDevtdata = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Planning And Development Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'','payment_terms'=>'','cpdo_type'=>'');

        $this->slugs = 'cashier/cashier-business-permits';
        $getortype = $this->_bplobusinesspermit->GetOrtypeid('1');
        $this->ortype_id =  $getortype->ortype_id; 
        $this->casheringContrObj = new CashierRealPropertyController;
        $this->realPropDataFromCashering = $this->casheringContrObj->dataRealProp;
        $this->realPropDetailsFromCashering = $this->casheringContrObj->dataDtls;
    }
    public function updateData($id,$columns){
        return DB::table('payment_history')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('payment_history')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('payment_history')->where('id',$id)->first();
    }
    public function getEditDetailsphistory($id){
        $remortServer = DB::connection('remort_server');
        return $remortServer->table('payment_history')->where('id',$id)->first();
       // return DB::table('payment_history')->where('id',$id)->first();
    }
    public function getEditDetailsphistoryByTopNo($topNo){
        //dd($topNo);
        $remortServer = DB::connection('remort_server');
        return $remortServer->table('payment_history')->where('transaction_no',$topNo)->get();
       // return DB::table('payment_history')->where('id',$id)->first();
    }
    public function getTransactionsedit(){
         return DB::table('cto_top_transactions as ctt')->join('cpdo_application_forms as caf','caf.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->where('ctt.top_transaction_type_id','19')->get();
    }
    public function getTransactionsfordevelopedit(){
         return DB::table('cto_top_transactions as ctt')->join('cpdo_development_permits as cad','cad.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->where('ctt.top_transaction_type_id','44')->get();
    }
    public function getAllDepartment(){
        return DB::table('cto_payment_cashier_system')->orderBy('id')->get();
   }

   public function Gettaxfees(){
   return DB::table('cto_tfocs AS ctot')
          ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctot.fund_id') 
          ->leftjoin('cto_charge_types AS cct', 'cct.id', '=', 'ctot.ctype_id')
          ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
          ->select('ctot.id','aas.description as accdesc')->where('tfoc_is_applicable','5')->get();
    }

   public function updateRealPropertyCashier($res,$request){ 
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$res['transaction_no'])->pluck('id')->first();
        if($top_transaction_id>0){
            $this->data['top_transaction_id'] = $this->realPropDetailsFromCashering['top_transaction_id'] = $top_transaction_id;
            $this->data['or_no'] = $request->input('or_no');
            $this->data['total_amount'] = $this->data['net_tax_due_amount'] = $res['total_amount'];
            $this->data['total_paid_amount'] = $res['total_paid_amount'];
            $this->data['total_paid_interest'] ='';
            $this->data['total_paid_surcharge'] ='';
            $this->data['payment_terms'] ='5'; //Online Payment

            $clientdata = $this->_commonmodel->getClientName($res['client_id']);
            $taxpayername = $clientdata->full_name;
            $this->data['taxpayers_name'] = $taxpayername;
            $this->data['cashier_particulars']='Real Property Tax Fee';
            $getortype = $this->_CashierRealProperty->GetOrtypeid('2');
            $this->data['ortype_id'] =  $getortype->ortype_id; // Accountable Form No. 51-C

            $this->realPropDetailsFromCashering['cashier_year'] = $this->data['cashier_year'] = $res['bill_year'];
            $this->realPropDetailsFromCashering['cashier_month'] = $this->data['cashier_month'] = $res['bill_month'];
            $this->realPropDetailsFromCashering['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='2';
            $this->realPropDetailsFromCashering['payee_type'] = $this->data['payee_type'] = "1";
            $this->realPropDetailsFromCashering['client_citizen_id'] =$this->data['client_citizen_id']=$res['client_id'];

            $this->realPropDetailsFromCashering['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->realPropDetailsFromCashering['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
           
            $this->realPropDetailsFromCashering['created_by'] = $this->data['created_by']=\Auth::user()->id;
            $this->realPropDetailsFromCashering['created_at'] = $this->data['created_at'] = date('Y-m-d H:i:s');
            $this->data['status'] = '1'; 
            $this->data['payment_type'] = 'Online';
            $this->data['cashier_or_date'] = date("Y-m-d");

            $issueNumber = $this->getPrevIssueNumber();
            $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
            $cashier_batch_no = date('Y')."-".$cashier_issue_no;
            $coaddata = '';
            //dd($request->or_no;
            $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$request->or_no);
           
            if($getorRegister != Null){
                $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $res['or_no']);
                $this->_CashierRealProperty->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                  
                $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                $this->data['or_register_id'] =  $getorRegister->id; 
                $this->data['coa_no'] =  $coaddata->coa_no; 
                if($getorRegister->or_count == 1){
                    $uptregisterarr = array('cpor_status'=>'2');
                    $this->_CashierRealProperty->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                    $uptassignmentrarr = array('ora_is_completed'=>'1');
                    $this->_CashierRealProperty->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                }   
            }

            $this->data['cashier_issue_no'] = $issueNumber; 
            $this->data['cashier_batch_no'] = $cashier_batch_no; 
            //dd( $this->data);
            $lastinsertid = $this->_CashierRealProperty->addData($this->data);
            //$lastinsertid = 619;

            //Convert application For Issuance
            Session::put('REMOTE_UPDATED_REALPROPERTY_TABLE',$res['busn_id']); // This for remote server
            $this->realPropDetailsFromCashering['cashier_id'] = $lastinsertid;
            $this->realPropDetailsFromCashering['cashier_issue_no'] =$issueNumber;
            $this->realPropDetailsFromCashering['cashier_batch_no'] =$cashier_batch_no;
            $success_msg = 'Cashiering added successfully.';
            
            $Cashierid = $lastinsertid;
            $acceptedTds =DB::table('rpt_cto_billings')->where('transaction_id',$this->data['top_transaction_id'])->pluck('id')->toArray();
            $arrDetails = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])->whereIn('id',$acceptedTds)->get();
            
            $iterations = 1;
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    //$arrTfoc = $this->_cashierrealproperty->getTfocDtls($value);
                    $this->realPropDataFromCashering['cashier_year'] = date("Y");
                    $this->realPropDataFromCashering['cashier_month'] = date("m");
                    $this->realPropDataFromCashering['cashier_id'] = $lastinsertid;
                    $this->realPropDataFromCashering['top_transaction_id'] = $value->transaction_id;
                    $this->realPropDataFromCashering['tfoc_is_applicable'] = 2;
                    $this->realPropDataFromCashering['or_no'] = $this->data['or_no'];
                    // Apply tax credit
                    
                    if($iterations == count($arrDetails)){
                    if($request->input('tcm_id')>0 && $this->data['total_amount_change']>0){
                        $propertyDetails = RptCtoBilling::select('rp_property_code')->where('id',(isset($acceptedTds[0]))?$acceptedTds[0]:'')->first();
                        $this->realPropDataFromCashering['tax_credit_amount'] = $this->data['total_amount_change'];
                    }
                    $this->realPropDataFromCashering['tcm_id'] = $this->data['tcm_id'];
                    $this->realPropDataFromCashering['tax_credit_gl_id'] = $this->data['tax_credit_gl_id'];
                    $this->realPropDataFromCashering['tax_credit_sl_id'] = $this->data['tax_credit_sl_id'];
                    $this->realPropDataFromCashering['previous_cashier_id'] = $this->data['previous_cashier_id'];
                    }
                    $iterations++;
                    $this->realPropDataFromCashering['cb_code'] = $value->id;
                    $this->realPropDataFromCashering['rp_code'] = $value->rp_code;
                    $this->realPropDataFromCashering['rp_property_code'] = $value->rp_property_code;
                    $this->realPropDataFromCashering['pk_code'] =  $value->pk_code; // Accountable Form No. 56
                    $this->realPropDataFromCashering['rp_tax_declaration_no'] = $value->rp_tax_declaration_no;
                    $this->realPropDataFromCashering['cb_billing_mode'] = $value->cb_billing_mode;
                    $this->realPropDataFromCashering['cb_control_no'] = $value->cb_control_no;
                    $this->realPropDataFromCashering['transaction_no'] = $value->transaction_no;
                    $this->realPropDataFromCashering['created_by'] = \Auth::user()->id;
                    $this->realPropDataFromCashering['created_at'] = date("Y-m-d H:i:s");
                    $checkdetailexist =  $this->casheringContrObj->_cashierrealproperty->checkCBBillingRecordIsExist($value->id,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->casheringContrObj->_cashierrealproperty->updateCashierDetailsRealPropertyData($checkdetailexist[0]->id,$this->realPropDataFromCashering);
                    } else{
                        $this->casheringContrObj->_cashierrealproperty->addCashierDetailsRealPropertyData($this->realPropDataFromCashering);
                    }
                    /* Update is paid status of billing */
                    $billingObj = RptCtoBilling::find($value->id);
                    $billingObj->cb_is_paid = 1;
                    $billingObj->cb_or_no   = $this->data['or_no'];
                    $billingObj->save();
                     $this->casheringContrObj->deleteDelinquencyData($value->id);
                    /* Update is paid status of billing */
                    /* Save Data in cashier details */
                    if($value->billingDetails != null){
                    foreach ($value->billingDetails as $bill) {
                        $arrayData = $bill->toArray();
                        foreach((array)$this->realPropDetailsFromCashering as $key=>$val){
                            if(in_array($key,array_keys($arrayData))){
                                $this->realPropDetailsFromCashering[$key] = $arrayData[$key];
                            }
                        
                     }
                     $this->realPropDetailsFromCashering['cbd_code'] = $bill->id;
                     $this->realPropDetailsFromCashering['pk_id'] = $value->rptProperty->pk_id;
                     $penltyData = DB::table('rpt_cto_billing_details_penalties')->where('cb_code',$bill->cb_code)->where('sd_mode',$bill->sd_mode)->where('cbd_covered_year',$bill->cbd_covered_year)->first();
                     if($penltyData != null){
                        $penltyData = (array)$penltyData;
                        foreach((array)$this->realPropDetailsFromCashering as $key=>$val){
                            if(in_array($key,array_keys($penltyData))){
                                $this->realPropDetailsFromCashering[$key] = $penltyData[$key];
                            }
                        
                     }
                     }
                     $discData = DB::table('rpt_cto_billing_details_discounts')->where('cb_code',$bill->cb_code)->where('sd_mode',$bill->sd_mode)->where('cbd_covered_year',$bill->cbd_covered_year)->first();
                     if($discData != null){
                        $discData = (array)$discData;
                        foreach((array)$this->realPropDetailsFromCashering as $key=>$val){
                            if(in_array($key,array_keys($discData))){
                                $this->realPropDetailsFromCashering[$key] = $discData[$key];
                            }
                        
                     }
                     }
                     $checkdetailexist = $this->casheringContrObj->_cashierrealproperty->checkCBBillingDetailsRecordIsExist($bill->id,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->casheringContrObj->_cashierrealproperty->updateCashierDetailsData($checkdetailexist[0]->id,$this->realPropDetailsFromCashering);
                    } else{
                        $savedID =$this->casheringContrObj->_cashierrealproperty->addCashierDetailsData($this->realPropDetailsFromCashering);
                       $this->casheringContrObj->_cashierrealproperty->addDataInCasheringIncome($savedID);
                    }
                    $this->casheringContrObj->_cashierrealproperty->updateAccountReceibables($Cashierid,true);
                     //dd($this->dataDtls);
                    }
                }
                    /* Save Data in cashier details */
                }
            }

            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Business Permit Cashiering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            // Log Details End
//dd('d');
            Session::put('remote_cashier_id_for_rpt',$Cashierid);
            Session::put('PRINT_CASHIER_ID_FOR_RPT',$Cashierid);
            return $Cashierid;
        }
    }
    
    public function approve($request){
    try{
           DB::beginTransaction();
            $remortServer = DB::connection('remort_server');
            //$remortServer->table('payment_history')->where('id',$request->input('payment_history_id'))->update(['or_no' => $request->input('orno')]);
            $or_no = $request->input('or_num'); $ordate = $request->input('cashier_or_date');
            $remortServer->table('payment_history')->where('id',$request->input('payment_history_id'))->update(['or_no' => $or_no,'or_date'=>$ordate]);
            $rowToUpdate = $remortServer->table('payment_history')->where('id',$request->input('payment_history_id'))->first();
            //$rowToUpdate = DB::table('payment_history')->where('id',$request->input('payment_history_id'))->first();
           
            $rowToUpdate = get_object_vars($rowToUpdate);
            
            unset($rowToUpdate['id']);
            unset($rowToUpdate['frgn_payment_id']);
            unset($rowToUpdate['is_approved']);
            unset($rowToUpdate['is_synced']);
            $rowToUpdate['is_approved'] = 1;
            $rowToUpdate['is_synced'] = 1;
            $trans_no=$rowToUpdate['transaction_no'];
            $client_id=$rowToUpdate['client_id'];
            $paid_amt=$rowToUpdate['total_paid_amount'];
            if($rowToUpdate['department_id'] == 1){
               // dd($rowToUpdate['department_id']);
                $this->updateCashier($rowToUpdate,$request);
                 $smsTemplate=SmsTemplate::where('id',71)->where('is_active',1)->first();
                 $arrData = $this->_CashierBusinessPermit->getappdatataxpayer($client_id);
            }elseif($rowToUpdate['department_id'] == 4){
                $this->updateOccupancyCashier($rowToUpdate);
                $smsTemplate=SmsTemplate::where('id',75)->where('is_active',1)->first();
                $arrData = $this->_CashierBusinessPermit->getappdatataxpayer($client_id);
            }elseif($rowToUpdate['department_id'] == 3){
                $this->updateEngineeringCashier($rowToUpdate);
                $smsTemplate=SmsTemplate::where('id',74)->where('is_active',1)->first();
                $arrData = $this->_CashierBusinessPermit->getappdatataxpayer($client_id);
            }elseif($rowToUpdate['department_id'] == 2){
                $acceptedTds =DB::table('rpt_cto_billings')->where('transaction_no',$rowToUpdate['transaction_no'])->pluck('id')->toArray();
                $billingData = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
                ->addSelect(
                            [
                                'totalBasicDue' => RptCtoBillingDetail::select(DB::raw("SUM(basic_total_due) AS totalBasicDue"))
                                ->whereColumn('cb_code', 'rpt_cto_billings.id'),
                                'totalPenaltyDue' => RptCtoBillingDetailsPenalty::select(DB::raw("SUM(penalty_total_due)"))
                                ->whereColumn('cb_code', 'rpt_cto_billings.id') ,
                                'totalDiscountDue' => RptCtoBillingDetailsDiscount::select(DB::raw("SUM(dicount_total_due)"))
                                ->whereColumn('cb_code', 'rpt_cto_billings.id')            
                        ]
                    )
                ->whereIn('rpt_cto_billings.id',$acceptedTds)
                ->get();  
                $totalTaXDue = $billingData->sum('totalBasicDue');
                $totalPenaltyDue = $billingData->sum('totalPenaltyDue');
                $totalDiscountDue = $billingData->sum('totalDiscountDue');
                $subTotal = $totalTaXDue+$totalPenaltyDue;
                $netTaxDue = $subTotal-$totalDiscountDue;
                $rowToUpdate['total_paid_amount'] = $netTaxDue;
                $rowToUpdate['total_amount'] = $netTaxDue;
                //dd($rowToUpdate);
                $smsTemplate=SmsTemplate::where('id',72)->where('is_active',1)->first();
                $rptCashierid=$this->updateRealPropertyCashier($rowToUpdate,$request);
                //$this->sensCashierSMS($rptCashierid);
            }else{
                $this->updatePlanDevtCashier($rowToUpdate);
                $smsTemplate=SmsTemplate::where('id',73)->where('is_active',1)->first();
                $arrData = $this->_CashierBusinessPermit->getappdatataxpayer($client_id);
            }
            $arr = $remortServer->table('payment_history')->where('transaction_no',$rowToUpdate['transaction_no'])->get()->toArray();
            foreach($arr AS $val){
                $rowToUpdate = get_object_vars($val);
                //dd($rowToUpdate);
                $pay_id = $rowToUpdate['id'];
                unset($rowToUpdate['id']);
                unset($rowToUpdate['frgn_payment_id']);
                unset($rowToUpdate['is_approved']);
                unset($rowToUpdate['is_synced']);
                $rowToUpdate['is_approved'] = 1;
                $rowToUpdate['is_synced'] = 1;
                DB::table('payment_history')->insert($rowToUpdate);
                $l_payment_id=DB::getPdo()->lastInsertId();
                if ($request->has('or_num')) {
                    // 'or_num' is set in the request
                    $or_no = $request->input('or_num');
                } else {
                    // 'or_num' is not set in the request
                    $or_no = null;
                }
                if ($request->has('cashier_or_date')) {
                    // 'or_num' is set in the request
                    $or_date = date("Y-m-d",strtotime($request->input('cashier_or_date')));
                } else {
                    // 'or_num' is not set in the request
                    $or_date = null;
                }
                $remortServer->table('payment_history')->where('id',$pay_id)->update(['or_no' => $or_no,'or_date' => $or_date ,'frgn_payment_id' => $l_payment_id,'is_approved' => 1,'is_synced' => 1]);
                DB::commit();
            }
           
            if(!empty($smsTemplate) && $arrData->p_mobile_no != null && $rowToUpdate['department_id'] != 2)
            {
                    $receipient=$arrData->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                    $msg = str_replace('<TOP_NO>',$trans_no,$msg);
                    $msg = str_replace('<AMOUNT>',$paid_amt,$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                   $this->send($msg, $receipient);
            }  
            if($rowToUpdate['department_id'] == 2)
            {
                $this->sensCashierSMS($rptCashierid,$paid_amt);
            }
            return $request->input('payment_history_id');
      } 
       catch (\Exception $e) {
           echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 

    public function send($message, $receipient)
    {   
        $interface = new ComponentSMSNotificationRepository;
        $validate = $interface->validate();
        if ($validate > 0) {
            $setting = $interface->fetch_setting();
            $details = array(
                'message_type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => Carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $interface->create($details);
           
                //$this->sendSms($receipient, $message);
                $interface->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }
    public function sensCashierSMS($cashierId='',$paid_amt=""){
        $cashDetails = DB::table('cto_cashier as cc')
                                ->join('clients as c','c.id','=','cc.client_citizen_id')
                                ->join('cto_cashier_real_properties as ccrp','ccrp.cashier_id','=','cc.id')
                                ->join('rpt_properties as rp','rp.id','=','ccrp.rp_code')
                                ->join('rpt_property_kinds as rpk','rpk.id','=','rp.pk_id')
                                ->join('cto_top_transactions as ctt','ctt.id','=','cc.top_transaction_id')
                                ->select('cc.cashier_or_date','c.full_name','c.p_mobile_no','cc.total_amount','ctt.transaction_no as tran_num',DB::raw('GROUP_CONCAT(DISTINCT rp.rp_tax_declaration_no) as tdNo'),DB::raw('GROUP_CONCAT(DISTINCT rpk.pk_description) as kind'),'cc.net_tax_due_amount')
                                ->where('cc.id',$cashierId)
                                ->first();
                                //dd($cashDetails);
        $smsTemplate=SmsTemplate::where('id',72)->where('is_active',1)->first();;
        if(!empty($smsTemplate) && $cashDetails->p_mobile_no != null)
        {
            $receipient = $cashDetails->p_mobile_no;
            $msg=$smsTemplate->template;
            $msg = str_replace('<NAME>', $cashDetails->full_name,$msg);
            $msg = str_replace('<DATE>', date('d/m/Y',strtotime($cashDetails->cashier_or_date)),$msg);
            $msg = str_replace('<PROPERTY_KIND>', $cashDetails->kind,$msg);
            $msg = str_replace('<TAX_DECLARATION_NO>', $cashDetails->tdNo,$msg);
            $msg = str_replace('<TOP_NO>',$cashDetails->tran_num,$msg);
            $msg = str_replace('<AMOUNT>',Helper::decimal_format($cashDetails->net_tax_due_amount),$msg);
            $msg = preg_replace("/[\n\r]/","\\n", $msg);
            try {
                $this->send($msg, $receipient);
            } catch (\Exception $e) {
               return false;
            }
        }
    }

    // public function send($message, $receipient)
    // {   
    //     $validate = $this->componentSMSNotificationRepository->validate();
    //     if ($validate > 0) {
    //         $setting = $this->componentSMSNotificationRepository->fetch_setting();
    //         $details = array(
    //             'message_type_id' => 1,
    //             'masking_code' => $setting->mask->code,
    //             'messages' => $message,
    //             'created_at' => $this->carbon::now(),
    //             'created_by' => \Auth::user()->id
    //         );
    //         $message = $this->componentSMSNotificationRepository->create($details);
           
    //             //$this->sendSms($receipient, $message);
    //             $this->componentSMSNotificationRepository->send($receipient, $message);

    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    
    public function decline($id){
        return $remortServer->table('payment_history')->where('id',$id)->update(['is_approved' => 3]);
    }
    
    public function updatePlanDevtCashier($res){
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$res['transaction_no'])->pluck('id')->first();
        if($top_transaction_id>0)
        {
            $trans_details=$this->getPlanDevtTransactionid($top_transaction_id);
            //echo "<pre>"; print_r($trans_details); exit;
            if($trans_details->appid != null)
            {
            $getAllFees=$this->getpenaltyfee($trans_details->appid,$trans_details->topid,1);
            }
            // foreach((array)$this->data as $key=>$val){
            //     $this->data[$key] = $request->input($key);
            // }
            $this->palanDevtdata['top_transaction_id']=$top_transaction_id;
            $this->palanDevtdata['client_citizen_id']=$res['client_id'];
            $this->palanDevtdata['or_no']=$res['or_no'];
            // $this->occupancyData['payment_terms']=$top_transaction_id;
            $this->palanDevtdata['total_amount']=$res['total_amount'];
            $this->palanDevtdata['total_paid_amount']=$res['total_paid_amount'];
            $curramount = floatval($res['total_amount']); 
            $paidamount = floatval($res['total_paid_amount']); 
            $change = $curramount - $paidamount;
            $change = abs($change);
            $change = number_format($change, 2);
            $this->palanDevtdata['total_amount_change']=$change;
            // $this->palanDevtdata['total_paid_surcharge']=$trans_details->ejr_surcharge_fee;


            $cashierdetails = array();
            $cashierdetails['cashier_year'] = date('Y');
            $cashierdetails['cashier_month'] = date('m');
            $cashierdetails['tfoc_is_applicable'] ='5';
            $cashierdetails['payee_type'] = $res['cpdo_type'];
            $cashierdetails['client_citizen_id'] =$this->palanDevtdata['client_citizen_id'];
            $clientdata = $this->_commonmodel->getClientName($this->palanDevtdata['client_citizen_id']);
            $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
            $this->palanDevtdata['taxpayers_name'] = $taxpayername;
            //echo "<pre>"; print_r($this->palanDevtdata); exit;
            
            unset($this->palanDevtdata['cashier_batch_no']); 
            $this->palanDevtdata['updated_by']=\Auth::user()->id;
            $this->palanDevtdata['updated_at'] = date('Y-m-d H:i:s');
                $this->palanDevtdata['payment_terms'] ='5';
                $this->palanDevtdata['cpdo_type'] = $trans_details->permittype;
                $this->palanDevtdata['cashier_year'] = date('Y');
                $this->palanDevtdata['cashier_month'] = date('m');
                $this->palanDevtdata['tfoc_is_applicable'] = '5'; 
                $this->palanDevtdata['payee_type'] = '1'; 
                $this->palanDevtdata['created_by']=\Auth::user()->id;
                $this->palanDevtdata['created_at'] = date('Y-m-d H:i:s');
                $this->palanDevtdata['status'] = '1';
                $this->palanDevtdata['payment_type'] = 'Online';
                $this->palanDevtdata['cashier_or_date'] = $res['or_date'];
                $getortype = $this->_cpdocashering->GetOrtypeid('5');
                $this->palanDevtdata['ortype_id'] =  $getortype->ortype_id; 
                $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->palanDevtdata['or_no']);
                //print_r($getorRegister); exit;
                if($getorRegister != Null){
                $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->palanDevtdata['or_no']);
                    $this->_cpdocashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                $this->palanDevtdata['or_assignment_id'] =  $getorRegister->assignid; 
                $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                $this->palanDevtdata['or_register_id'] =  $getorRegister->id; 
                $this->palanDevtdata['coa_no'] =  $coaddata->coa_no; 
                if($getorRegister->or_count == 1){
                    $uptregisterarr = array('cpor_status'=>'2');
                    $this->_cpdocashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                    $uptassignmentrarr = array('ora_is_completed'=>'1');
                    $this->_cpdocashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                } 
                }
                
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $this->palanDevtdata['cashier_issue_no'] = $issueNumber; 
                $this->palanDevtdata['cashier_batch_no'] = $cashier_batch_no; 
                 //echo "<pre>"; print_r($this->palanDevtdata); exit;
                $lastinsertid = $this->_cpdocashering->addData($this->palanDevtdata);
                Session::put('CPDO_PRINT_CASHIER_ID',$lastinsertid);
                $success_msg = 'Cpdo Cashering added successfully.';

                $updateremotedata = array();
                $transactionno = str_pad($this->palanDevtdata['top_transaction_id'], 6, '0', STR_PAD_LEFT);
                $updateremotedata['topno'] = $transactionno;
                $updateremotedata['orno'] = $this->palanDevtdata['or_no'];
                $updateremotedata['ordate'] = date("Y-m-d");
                $updateremotedata['payment_status'] = 1;
                $updateremotedata['csd_id'] = '2';
                $updateremotedata['cashieramount'] = $this->palanDevtdata['total_amount'];
                $getappid =  $this->_cpdocashering->getappidbytoptransaction($this->palanDevtdata['top_transaction_id']);
                if($this->palanDevtdata['cpdo_type']){
                $this->_cpdocashering->updatelocaldata($getappid->transaction_ref_no,$updateremotedata);
                $this->_cpdocashering->updateremotedata($getappid->transaction_ref_no,$updateremotedata);  
                }
                $uptdata = array('latestusedor' => $this->palanDevtdata['or_no']);
                //$this->_cpdocashering->UpdateOrused('3',$uptdata);
                $cashierdetails['cashier_id'] = $lastinsertid;
                $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
                $cashierdetails['top_transaction_id'] = $this->palanDevtdata['top_transaction_id'];

                if($trans_details->penaltyamount > 0){
                    $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
                    $cashdata = $this->_cpdocashering->getCasheringIds($trans_details->tfoc_id);
                    $fundid = "0"; $glaccountid ="0"; $slid="0";
                    if(!empty($cashdata)){
                            $fundid = $cashdata->fund_id; 
                            $glaccountid = $cashdata->tfoc_surcharge_gl_id;
                            $slid = $cashdata->tfoc_surcharge_sl_id;
                    }
                    $cashierdetails['ctc_taxable_amount'] ="0";
                    $cashierdetails['tfc_amount'] =$trans_details->penaltyamount;
                    $cashierdetails['or_no'] = $this->palanDevtdata['or_no'];
                    $cashierdetails['isotehrtaxes'] ='1';
                    $getortype = $this->_cpdocashering->GetOrtypeid('5');
                    $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                    if(!empty($cashdata)){
                        $cashierdetails['agl_account_id'] = $cashdata->tfoc_surcharge_gl_id;
                        $cashierdetails['sl_id'] = $cashdata->tfoc_surcharge_sl_id;
                    }
                    $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                    $cashierdetails['updated_at']= date('Y-m-d H:i:s');
                    $cashierdetails['created_at'] = date('Y-m-d H:i:s');
                    $cashierdetailid = $this->_cpdocashering->addCashierDetailsData($cashierdetails);

                    $addincomedata = array();
                    $addincomedata['cashier_id'] = $lastinsertid;
                    $addincomedata['cashier_details_id'] = $cashierdetailid;
                    $addincomedata['tfoc_is_applicable'] = '3';
                    $addincomedata['taxpayer_name'] = $taxpayername;
                    $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                    $addincomedata['amount'] = $trans_details->penaltyamount;
                    $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                    $addincomedata['fund_id'] = $fundid;
                    $addincomedata['gl_account_id'] = $glaccountid;
                    $addincomedata['sl_account_id'] = $slid;
                    // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                    $addincomedata['or_no'] = $this->palanDevtdata['or_no'];
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
            // }
            $Cashierid = $lastinsertid;
            if($trans_details->tfoc_id != null){
        
            
                    $cashdata = $this->_cpdocashering->getCasheringIds($trans_details->tfoc_id);
                    $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
                    $cashierdetails['ctc_taxable_amount'] ="";
                    $cashierdetails['all_total_amount'] = $trans_details->caf_total_amount;
                    $cashierdetails['tfc_amount'] =$trans_details->caf_total_amount;
                    $cashierdetails['or_no'] = $this->palanDevtdata['or_no'];
                    $getortype = $this->_cpdocashering->GetOrtypeid('5');
                    $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                    $cashierdetails['cashier_remarks'] = $this->palanDevtdata['cashier_remarks'];
                    $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
                    $cashierdetails['sl_id'] = $cashdata->sl_id;
                    $cashierdetails['isotehrtaxes'] ='0';
                    //echo $value; echo $Cashierid;
                    $checkdetailexist =  $this->_cpdocashering->checkrecordisexist($trans_details->tfoc_id,$Cashierid);
                    //print_r($checkdetailexist); exit;
                    if(count($checkdetailexist) > 0){
                        $cashierdetailsupt = array();
                        $cashierdetailsupt['tfoc_id'] =$trans_details->tfoc_id;
                        $cashierdetailsupt['ctc_taxable_amount'] ="";
                        $cashierdetailsupt['tfc_amount'] =$trans_details->caf_total_amount;
                        $cashierdetailsupt['or_no'] = $this->palanDevtdata['or_no'];
                        //$cashierdetails['ortype_id'] =  $this->palanDevtdata['ortype_id'];
                        $cashierdetails['cashier_remarks'] = $this->palanDevtdata['cashier_remarks'];
                        $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
                        $cashierdetails['sl_id'] = $cashdata->sl_id;
                            $this->_cpdocashering->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetailsupt);
                    } else{
                        $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                        $cashierdetailid = $this->_cpdocashering->addCashierDetailsData($cashierdetails);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $cashierdetailid;
                        $addincomedata['tfoc_is_applicable'] = '5';
                        $addincomedata['taxpayer_name'] = $taxpayername;
                        $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                        $addincomedata['amount'] = $trans_details->caf_total_amount;
                        $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                        $addincomedata['fund_id'] = $cashdata->fund_id;
                        $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                        $addincomedata['sl_account_id'] = $cashdata->sl_id;
                        // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                        $addincomedata['or_no'] = $this->palanDevtdata['or_no'];
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
           
            $arrData=array();  $top_transaction_id = $this->palanDevtdata['top_transaction_id'];
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_cpdocashering->updateTopTransaction($top_transaction_id,$arrData);
            // return redirect()->route('cpdocashering.index')->with('success', __($success_msg));
        }
    }

    public function getpenaltyfee($appid,$cashierid,$apptype){
        $penaltyfee= 0; $html ="";  $feename="";       
            if($apptype == 1){
                $getpenamltyfee = $this->_cpdocashering->getZoningappfee($appid); $feename="Zoning Clearance Penalty";
            }else{
                $getpenamltyfee = $this->_cpdocashering->getDevelopmentappfee($appid); $feename="Development Clearance Penalty";
            }
            
            $penaltyfee= $getpenamltyfee->penaltyamount;
            $data=array();
            if($penaltyfee > 0){
                $data=[
                    'taxfeeshow' => $getpenamltyfee->tfoc_id,
                    'descshow' => $feename,
                    'penaltyamount' => $penaltyfee,
                ];
            }
            return $data;
    }

    public function getPlanDevtTransactionid($id){
            $toptypeid = $this->_cpdocashering->getToptransactiontypeid($id);
            $arrApptype = config('constants.arrCpdoAppModule');
            if($toptypeid->top_transaction_type_id == 19){
                $data = $this->_cpdocashering->Gettransactionnobyid($id); 
                $htmloption ="";
                $data->cm_id = 'Zoning Permit';
                $data->permittype = 1;
            }else{
                $data = $this->_cpdocashering->Gettransactionnobydevelop($id);
                $htmloption =""; 
                $data->permittype = 2;
            }
            
            return $data;
    }

    public function updateEngineeringCashier($res){
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$res['transaction_no'])->pluck('id')->first();
        if($top_transaction_id>0)
        {
            $trans_details=$this->getEngTransactionid($top_transaction_id);
        
            if($trans_details->id != null)
            {
            $allFeesejr=$this->getallFeesejr($trans_details->id);
            }

            $this->engData['top_transaction_id']=$top_transaction_id;
            $this->engData['client_citizen_id']=$res['client_id'];
            $this->engData['or_no']=$res['or_no'];
            // $this->occupancyData['payment_terms']=$top_transaction_id;
            $this->engData['total_amount']=$res['total_amount'];
            $this->engData['total_paid_amount']=$res['total_paid_amount'];
            $curramount = floatval($res['total_amount']); 
            $paidamount = floatval($res['total_paid_amount']); 
            $change = $curramount - $paidamount;
            $change = abs($change);
            $change = number_format($change, 2);
            $this->engData['total_amount_change']=$change;
            $this->engData['total_paid_surcharge']=$trans_details->ejr_surcharge_fee;
            //echo "<pre>"; print_r($_POST); exit;
            $cashierdetails = array();
            $cashierdetails['cashier_year'] = date('Y');
            $cashierdetails['cashier_month'] = date('m');
            $cashierdetails['tfoc_is_applicable'] ='3';
            $cashierdetails['payee_type'] = "1";
            $cashierdetails['client_citizen_id'] =$this->engData['client_citizen_id'];
            $clientdata = $this->_commonmodel->getClientName($this->engData['client_citizen_id']);
            $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
            $this->engData['taxpayers_name'] = $taxpayername;
           
            $this->engData['tfoc_is_applicable'] = '3'; 
            $this->engData['payee_type'] = '1'; 
            $getortype = $this->_engineeringcashering->GetOrtypeid('3');
            $this->engData['ortype_id'] =  $getortype->ortype_id;
            unset($this->engData['createdat']);
            unset($this->engData['cashier_batch_no']); 
            $this->engData['updated_by']=\Auth::user()->id;
            $this->engData['updated_at'] = date('Y-m-d H:i:s');
            
                $updateremotedata = array();
                $transactionno = str_pad($this->engData['top_transaction_id'], 6, '0', STR_PAD_LEFT);
                $updateremotedata['topno'] = $transactionno;
                $updateremotedata['orno'] = $this->engData['or_no'];
                $updateremotedata['ordate'] = date("Y-m-d");
                $updateremotedata['payment_status'] = 1;
                $updateremotedata['cashieramount'] = $this->engData['total_amount'];
                $getappid =  $this->_engineeringcashering->getappidbytoptransaction($this->engData['top_transaction_id']);
                $this->_engineeringcashering->updatelocaldata($getappid->transaction_ref_no,$updateremotedata);
                $this->_engineeringcashering->updateremotedata($getappid->transaction_ref_no,$updateremotedata);


                $this->engData['created_by']=\Auth::user()->id;
                $this->engData['created_at'] = date('Y-m-d H:i:s');
                $this->engData['status'] = '1'; 
                $this->engData['payment_type'] = 'Walk-In';
                $this->engData['cashier_or_date'] = $res['or_date'];
                
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->engData['or_no']);
                if($getorRegister != Null){
                $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->engData['or_no']);
                    $this->_engineeringcashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                $this->engData['or_assignment_id'] =  $getorRegister->assignid; 
                $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                $this->engData['or_register_id'] =  $getorRegister->id; 
                $this->engData['coa_no'] =  $coaddata->coa_no; 
                if($getorRegister->or_count == 1){
                    $uptregisterarr = array('cpor_status'=>'2');
                    $this->_engineeringcashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                    $uptassignmentrarr = array('ora_is_completed'=>'1');
                    $this->_engineeringcashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                }   
                }
                $this->engData['payment_terms'] ='5';
                $this->engData['cashier_issue_no'] = $issueNumber; 
                $this->engData['cashier_batch_no'] = $cashier_batch_no;
                $this->engData['cashier_year'] = date('Y');
                $this->engData['cashier_month'] = date('m');
                //echo "<pre>"; print_r($this->engData); exit;  
                $lastinsertid = $this->_engineeringcashering->addData($this->engData);
                $success_msg = 'Engineering Cashiering added successfully.';
                Session::put('ENGG_PRINT_CASHIER_ID',$lastinsertid);
                $uptdata = array('latestusedor' => $this->engData['or_no']);

                $prmitsrno = 1;
                $getseries = $this->_engineeringcashering->getlatestseries();
                if(!empty($getseries)){
                $prmitsrno = $getseries->permitnoseries; 
                }
                $permitsrno= $prmitsrno + 1; 
                //$id = $request->input('id');
                $appPermitNo = date('Y').'-'.date('m').'-'.str_pad($permitsrno, 4, '0', STR_PAD_LEFT);
                $updatearray = array('ebpa_permit_no'=>$appPermitNo);
                $this->_engineeringcashering->updatePermitAppData($trans_details->id,$updatearray);
                //$this->_engineeringcashering->UpdateOrused('3',$uptdata);
                $getapptype = $this->_engineeringcashering->getapplicationtype($trans_details->id);
                if($getapptype->es_id == '1'){
                $ejrdata =array('ejr_or_no'=>$this->engData['or_no'],'permitnoseries'=>$permitsrno); 
                }else{
                    $ejrdata =array('ejr_or_no'=>$this->engData['or_no']);
                }
                //,'ejr_date_paid'=>date('Y-m-d')
                $this->_engineeringcashering->updateDatajobrequest($trans_details->id,$ejrdata);

                $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                $cashierdetails['cashier_id'] = $lastinsertid;
                $cashierdetails['top_transaction_id'] = $this->engData['top_transaction_id'];
                $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
                if($trans_details->ejr_surcharge_fee > 0){
                    $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
                    $cashdata = $this->_engineeringcashering->getCasheringsurchargeIds($trans_details->tfoc_id);
                    $fundid = "0"; $glaccountid ="0"; $slid="0";
                    if(!empty($cashdata)){
                            $fundid = $cashdata->fund_id; 
                            $glaccountid = $cashdata->tfoc_surcharge_gl_id;
                            $slid = $cashdata->tfoc_surcharge_sl_id;
                    }
                    $cashierdetails['ctc_taxable_amount'] ="0";
                    $cashierdetails['tfc_amount'] =$trans_details->ejr_surcharge_fee;
                    $cashierdetails['or_no'] = $this->engData['or_no'];
                    $cashierdetails['isotehrtaxes'] ='1';
                    $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                    $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                    if(!empty($cashdata)){
                        $cashierdetails['agl_account_id'] = $cashdata->tfoc_surcharge_gl_id;
                        $cashierdetails['sl_id'] = $cashdata->tfoc_surcharge_sl_id;
                    }
                    $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                    $cashierdetails['updated_at']= date('Y-m-d H:i:s');
                    $cashierdetails['created_at'] = date('Y-m-d H:i:s');
                    $cashierdetailid = $this->_engineeringcashering->addCashierDetailsData($cashierdetails);

                    $addincomedata = array();
                    $addincomedata['cashier_id'] = $lastinsertid;
                    $addincomedata['cashier_details_id'] = $cashierdetailid;
                    $addincomedata['tfoc_is_applicable'] = '3';
                    $addincomedata['taxpayer_name'] = $taxpayername;
                    $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                    $addincomedata['amount'] = $trans_details->ejr_surcharge_fee;
                    $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                    $addincomedata['fund_id'] = $fundid;
                    $addincomedata['gl_account_id'] = $glaccountid;
                    $addincomedata['sl_account_id'] = $slid;
                    // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                    $addincomedata['or_no'] = $this->engData['or_no'];
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
        
               $Cashierid = $lastinsertid;
                $defaultamount  = $trans_details->amountdefault - $trans_details->ejr_surcharge_fee;
                $cashdata = $this->_engineeringcashering->getCasheringIds($trans_details->tfoc_id);
                $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
                $cashierdetails['ctc_taxable_amount'] ='0';
                $cashierdetails['tfc_amount'] = $defaultamount;
                $cashierdetails['top_transaction_id'] = $this->engData['top_transaction_id'];
                $cashierdetails['all_total_amount'] = $defaultamount;
                $cashierdetails['or_no'] = $this->engData['or_no'];
                $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                $cashierdetails['ortype_id'] = $getortype->ortype_id; 
                $fundidnew = "0";
                if(!empty($cashdata)){
                    $fundidnew = $cashdata->fund_id; 
                }
                // if($request->input('id') <= 0 ){
                    if($trans_details->tfoc_id == $trans_details->tfoc_id){
                    $othertaxesarr = $this->_engineeringcashering->GetOthercharges($trans_details->tfoc_id);
                    $amountreduce =0; 
                    foreach ($othertaxesarr as $keyot => $valot) {
                        $insertothettaxesarray = array();
                        $insertothettaxesarray['cashier_year'] = date('Y');
                        $insertothettaxesarray['cashier_month'] = date('m');
                        $insertothettaxesarray['tfoc_is_applicable'] ='3';
                        $insertothettaxesarray['payee_type'] = "1";
                        $insertothettaxesarray['cashier_batch_no'] =$cashier_batch_no;
                        $insertothettaxesarray['top_transaction_id'] = $this->engData['top_transaction_id'];
                        $insertothettaxesarray['client_citizen_id'] =$this->engData['client_citizen_id'];
                        $insertothettaxesarray['cashier_issue_no'] = $cashier_issue_no;
                        $insertothettaxesarray['cashier_id'] =$lastinsertid;
                        $insertothettaxesarray['tfoc_id'] =$trans_details->tfoc_id;
                        $insertothettaxesarray['ortype_id'] =  $getortype->ortype_id;
                        $insertothettaxesarray['agl_account_id'] =$valot->otaxes_gl_id;
                        $insertothettaxesarray['sl_id'] =$valot->otaxes_sl_id;
                        $deductamount = $defaultamount * $valot->otaxes_percent /100;
                        $amountreduce = $amountreduce + $deductamount;
                        $insertothettaxesarray['tfc_amount'] =$deductamount;
                        $insertothettaxesarray['or_no'] = $this->engData['or_no'];
                        $insertothettaxesarray['isotehrtaxes'] ="1";
                        $insertothettaxesarray['updated_at']= date('Y-m-d H:i:s');
                        $insertothettaxesarray['created_at'] = date('Y-m-d H:i:s');
                        $cashierdetailid = $this->_engineeringcashering->addCashierDetailsData($insertothettaxesarray);
                        //echo "<pre>";  print_r($insertothettaxesarray);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $cashierdetailid;
                        $addincomedata['tfoc_is_applicable'] = '3';
                        $addincomedata['taxpayer_name'] = $taxpayername;
                        $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                        $addincomedata['amount'] = $deductamount;
                        $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                        $addincomedata['fund_id'] = $fundidnew;
                        $addincomedata['gl_account_id'] = $valot->otaxes_gl_id;
                        $addincomedata['sl_account_id'] = $valot->otaxes_sl_id;
                        // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                        $addincomedata['or_no'] = $this->engData['or_no'];
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
                    $cashierdetails['tfc_amount'] = $defaultamount - $amountreduce; 
                }
                // }
                //echo "<pre>";  print_r($cashierdetails);
                $fundid = "0"; $glaccountid ="0"; $slid="0";
                $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                //$cashierdetails['cashier_remarks'] = $this->engData['cashier_remarks'];
                if(!empty($cashdata)){
                    $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
                    $cashierdetails['sl_id'] = $cashdata->sl_id;  
                    $fundid = $cashdata->fund_id; 
                    $glaccountid = $cashdata->gl_account_id;
                    $slid = $cashdata->sl_id;
                }
                $checkdetailexist =  $this->_engineeringcashering->checkrecordisexist($trans_details->tfoc_id,$Cashierid);
                if(count($checkdetailexist) > 0){
                    $cashierdetailsupt = array();
                    $cashierdetailsupt['tfoc_id'] =$trans_details->tfoc_id;
                    $cashierdetailsupt['ctc_taxable_amount'] ='0';
                    $cashierdetailsupt['tfc_amount'] =$cashierdetails['tfc_amount'];
                    $cashierdetailsupt['or_no'] = $this->engData['or_no'];
                        $this->_engineeringcashering->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetailsupt);
                } else{
                    $cashierdetails['updated_at']= date('Y-m-d H:i:s');
                    $cashierdetails['created_at'] = date('Y-m-d H:i:s');
                    $cashierdetailid = $this->_engineeringcashering->addCashierDetailsData($cashierdetails);

                    $addincomedata = array();
                    $addincomedata['cashier_id'] = $lastinsertid;
                    $addincomedata['cashier_details_id'] = $cashierdetailid;
                    $addincomedata['tfoc_is_applicable'] = '3';
                    $addincomedata['taxpayer_name'] = $taxpayername;
                    $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                    $addincomedata['amount'] = $cashierdetails['tfc_amount'];
                    $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                    $addincomedata['fund_id'] = $fundid;
                    $addincomedata['gl_account_id'] = $glaccountid;
                    $addincomedata['sl_account_id'] = $slid;
                    // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                    $addincomedata['or_no'] = $this->engData['or_no'];
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
             //echo "<pre>"; print_r($allFeesejr); exit;   
             if(!empty($allFeesejr))
                {  
                    $cashierdetails=array();
                    $detailsoccupancy = array();
                    foreach ($allFeesejr as $key => $value){
                        $value = (object)$value;
                        if(isset($value->taxfees)){
                            $cashdata = $this->_engineeringcashering->getCasheringIds($value->taxfees);
                            $cashierdetails['tfoc_id'] =$value->taxfees;
                            $cashierdetails['ctc_taxable_amount'] =$value->taxableamount;
                            $cashierdetails['tfc_amount'] = $value->amount;
                            $cashierdetails['top_transaction_id'] = $this->engData['top_transaction_id'];
                            $cashierdetails['all_total_amount'] = $value->amount;
                            $cashierdetails['or_no'] = $this->engData['or_no'];
                            $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                            $cashierdetails['ortype_id'] = $getortype->ortype_id; 
                            $fundidnew = "0";
                            if(!empty($cashdata)){
                                $fundidnew = $cashdata->fund_id; 
                            }
                            // if($request->input('id') <= 0 ){
                            $cashierdetails['tfc_amount'] = $value->amount;
                            $fundid = "0"; $glaccountid ="0"; $slid="0";
                            $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                            //$cashierdetails['cashier_remarks'] = $this->engData['cashier_remarks'];
                            if(!empty($cashdata)){
                                $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
                                $cashierdetails['sl_id'] = $cashdata->sl_id;  
                                $fundid = $cashdata->fund_id; 
                                $glaccountid = $cashdata->gl_account_id;
                                $slid = $cashdata->sl_id;
                            }
                            $checkdetailexist =  $this->_engineeringcashering->checkrecordisexist($value->taxfees,$Cashierid);
                            if(count($checkdetailexist) > 0){
                                $cashierdetailsupt = array();
                                $cashierdetailsupt['tfoc_id'] =$value->taxfees;
                                $cashierdetailsupt['ctc_taxable_amount'] =$value->taxableamount;
                                $cashierdetailsupt['tfc_amount'] =$value->amount;
                                $cashierdetailsupt['or_no'] = $this->engData['or_no'];
                                    $this->_engineeringcashering->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetailsupt);
                            } else{
                                $cashierdetails['updated_at']= date('Y-m-d H:i:s');
                                $cashierdetails['created_at'] = date('Y-m-d H:i:s');
                                $cashierdetailid = $this->_engineeringcashering->addCashierDetailsData($cashierdetails);
            
                                $addincomedata = array();
                                $addincomedata['cashier_id'] = $lastinsertid;
                                $addincomedata['cashier_details_id'] = $cashierdetailid;
                                $addincomedata['tfoc_is_applicable'] = '3';
                                $addincomedata['taxpayer_name'] = $taxpayername;
                                $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                                $addincomedata['amount'] = $cashierdetails['tfc_amount'];
                                $addincomedata['tfoc_id'] = $value->taxfees;
                                $addincomedata['fund_id'] = $fundid;
                                $addincomedata['gl_account_id'] = $glaccountid;
                                $addincomedata['sl_account_id'] = $slid;
                                // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                                $addincomedata['or_no'] = $this->engData['or_no'];
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
                         
                        if(isset($value->desc)){
                            $cashdata = $this->_engineeringcashering->getCasheringIds($trans_details->tfoc_id);
                            $detailsoccupancy['top_transaction_id'] =$this->engData['top_transaction_id'];
                            $detailsoccupancy['cashier_id'] = $lastinsertid;
                            $detailsoccupancy['tfoc_is_applicable'] =  "3";
                            $detailsoccupancy['tfoc_id'] = $trans_details->tfoc_id;
                            $detailsoccupancy['fees_description'] = $value->desc;
                            $detailsoccupancy['tfc_amount'] = $value->amountnosave;
                            $fundid = "0"; $glaccountid ="0"; $slid="0";
                            if(!empty($cashdata)){
                                $detailsoccupancy['agl_account_id'] = $cashdata->gl_account_id;
                                $detailsoccupancy['sl_id'] = $cashdata->sl_id; 
                                $fundid = $cashdata->fund_id; 
                                $glaccountid = $cashdata->gl_account_id;
                                $slid = $cashdata->sl_id;  
                            }
                            $checkdetailexist =  $this->_engineeringcashering->checkeng_occupancy_details($value->desc,$lastinsertid);
                            if(count($checkdetailexist) > 0){
                                $detailsoccupancy = array();
                                $detailsoccupancy['fees_description'] = $value->desc;
                                $detailsoccupancy['tfc_amount'] = $value->amountnosave;
                                    $this->_engineeringcashering->updateeng_occupancy_detailsData($checkdetailexist[0]->id,$detailsoccupancy);
                            } else{
                             
                                $detailsoccupancy['cashier_year'] = date('Y');
                                $detailsoccupancy['cashier_month'] = date('m');
                                $this->_engineeringcashering->addeng_occupancy_detailsData($detailsoccupancy);
                               
                                $addincomedata = array();
                                $addincomedata['cashier_id'] = $lastinsertid;
                                $addincomedata['cashier_details_id'] = '0';
                                $addincomedata['tfoc_is_applicable'] = '3';
                                $addincomedata['taxpayer_name'] = $taxpayername;
                                $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                                $addincomedata['amount'] = $value->amountnosave;
                                $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                                $addincomedata['fund_id'] = $fundid;
                                $addincomedata['gl_account_id'] = $glaccountid;
                                $addincomedata['sl_account_id'] = $slid;
                                // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                                $addincomedata['or_no'] = $this->engData['or_no'];
                                $addincomedata['form_code'] = $coaddata->cpor_series;
                                $addincomedata['or_register_id'] =  $getorRegister->id;
                                $addincomedata['or_from'] =  $getorRegister->ora_from;
                                $addincomedata['or_to'] =  $getorRegister->ora_to;
                                $addincomedata['coa_no'] =  $coaddata->coa_no;
                                $addincomedata['is_collected'] =  0;
                                $addincomedata['created_by'] =  \Auth::user()->id;
                                $addincomedata['created_at'] =  date('Y-m-d H:i:s');
                                //$this->_commonmodel->addcashierIncomeData($addincomedata);
                            }
                        }
                    }
                }
           
            $arrData=array();  $top_transaction_id = $this->engData['top_transaction_id'];
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_engineeringcashering->updateTopTransaction($top_transaction_id,$arrData);
            // return redirect()->route('engcashering.index')->with('success', __($success_msg));
        }    
    }
    public function getallFeesejr($ejrid){
        $defaultFeesarr = $this->_engineeringcashering->GetReqiestfees($ejrid);
        $html ="";
        $getsurchargefee = $this->_engineeringcashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->ejr_surcharge_fee;
        //print_r($defaultFeesarr); exit;
        $resultArray = array();

       foreach ($defaultFeesarr as $key => $val) {
           if ($val->tax_amount > 0) {
               $row = array(
                   'year' => Date('Y'),
                   'fees_description' => $val->fees_description,
                   'taxableamount' => '', // You need to fill in the logic for this field
               );
       
               if ($val->is_default == '1') {
                   $row['taxfees'] = $val->tfoc_id;
                   $row['textonly'] = $val->fees_description;
                   $row['amount'] = $val->tax_amount;
               } else {
                   $row['desc'] = $val->fees_description;
                   $row['amountnosave'] = $val->tax_amount;
               }
       
               $resultArray[] = $row;
           }
       }
       return $resultArray;
    }

    public function getEngTransactionid($id){
            $arrApptype = config('constants.arrCpdoAppModule');
            $data = $this->_engineeringcashering->Gettransactionnobyid($id); 
            $htmloption ="";
            $defaultFeesarr = $this->_engineeringcashering->GetReqiestfeesaddon($data->ejrid);
            $addonfee =0;
            foreach ($defaultFeesarr as $key => $val) {
                    if($val->tax_amount > 0){
                        $addonfee = $addonfee + $val->tax_amount;
                }
            }
            $finalamount = $data->ejr_totalfees - $addonfee;
            $finalamount = $finalamount - $data->ejr_surcharge_fee;
            $data->amountdefault = $finalamount;
            return $data;
    }
    
    public function updateOccupancyCashier($res){
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$res['transaction_no'])->pluck('id')->first();
        if($top_transaction_id>0)
        {
            $trans_details=$this->getTransactionid($top_transaction_id);
            //echo "<pre>"; print_r($trans_details); exit;
            if($trans_details->id != null)
            {
               $getallFeeseeoa=$this->getallFeeseeoa($trans_details->id);
            }
           //echo "<pre>"; print_r($getAllFees); exit;
            $this->occupancyData['top_transaction_id']=$top_transaction_id;
            $this->occupancyData['client_citizen_id']=$res['client_id'];
            $this->occupancyData['or_no']=$res['or_no'];
            // $this->occupancyData['payment_terms']=$top_transaction_id;
            $this->occupancyData['total_amount']=$res['total_amount'];
            $this->occupancyData['total_paid_amount']=$res['total_paid_amount'];
            $curramount = floatval($res['total_amount']); 
            $paidamount = floatval($res['total_paid_amount']); 
            $change = $curramount - $paidamount;
            $change = abs($change);
            $change = number_format($change, 2);
            $this->occupancyData['total_amount_change']=$change;
            $this->occupancyData['total_paid_surcharge']=$trans_details->eoa_surcharge_fee;
            // $this->occupancyData['ctc_place_of_issuance']=$top_transaction_id;
            // $this->occupancyData['cashier_remarks']=$top_transaction_id;

            $cashierdetails = array();
            $clientdata = $this->_commonmodel->getClientName($this->occupancyData['client_citizen_id']);
            $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
            $this->occupancyData['taxpayers_name'] = $taxpayername;
            unset($this->occupancyData['cashier_batch_no']); 
            $this->occupancyData['updated_by']=\Auth::user()->id;
            $this->occupancyData['updated_at'] = date('Y-m-d H:i:s');
           
                $this->occupancyData['created_by']=\Auth::user()->id;
                $this->occupancyData['created_at'] = date('Y-m-d H:i:s');
                $this->occupancyData['cashier_or_date'] = date("Y-m-d");
                $issueNumber = $this->getOccupancyPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;
                $this->occupancyData['cashier_issue_no'] = $issueNumber; 
                $this->occupancyData['cashier_batch_no'] = $cashier_batch_no; 
                $this->occupancyData['cashier_year'] = date('Y');
                $this->occupancyData['cashier_month'] = date('m');
                $this->occupancyData['tfoc_is_applicable'] = '4'; 
                $this->occupancyData['payee_type'] = '1'; 
                $this->occupancyData['status'] = '1'; 
                $this->occupancyData['payment_type'] = 'Online';
                $this->occupancyData['payment_terms'] ='5';
                $this->occupancyData['cashier_issue_no'] = $cashier_issue_no;
                $this->occupancyData['cashier_or_date'] = $res['or_date'];
                $getortype = $this->_occupancycashering->GetOrtypeid('4');
                $this->occupancyData['ortype_id'] =  $getortype->ortype_id;
                $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->occupancyData['or_no']);
                if($getorRegister != Null){
                $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->occupancyData['or_no']);
                    $this->_occupancycashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                $this->occupancyData['or_assignment_id'] =  $getorRegister->assignid; 
                $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                $this->occupancyData['or_register_id'] =  $getorRegister->id; 
                $this->occupancyData['coa_no'] =  $coaddata->coa_no;  
                if($getorRegister->or_count == 1){
                    $uptregisterarr = array('cpor_status'=>'2');
                    $this->_occupancycashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                    $uptassignmentrarr = array('ora_is_completed'=>'1');
                    $this->_occupancycashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                  }    
                }
                $lastinsertid = $this->_occupancycashering->addData($this->occupancyData);
                $success_msg = 'Occupancy Cashiering added successfully.';
                Session::put('OCCUPANCY_PRINT_CASHIER_ID',$lastinsertid);

                $updateremotedata = array();
                $transactionno = str_pad($this->occupancyData['top_transaction_id'], 6, '0', STR_PAD_LEFT);
                $updateremotedata['topno'] = $transactionno;
                $updateremotedata['orno'] = $this->occupancyData['or_no'];
                $updateremotedata['ordate'] = date("Y-m-d");
                $updateremotedata['payment_status'] = 1;
                $updateremotedata['cashieramount'] = $this->occupancyData['total_amount'];
                $getappid =  $this->_occupancycashering->getappidbytoptransaction($this->occupancyData['top_transaction_id']);
                $this->_occupancycashering->updatelocaldata($getappid->transaction_ref_no,$updateremotedata);
                $this->_occupancycashering->updateremotedata($getappid->transaction_ref_no,$updateremotedata);

                $cashier_issue_no = str_pad($lastinsertid, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;
                $updatedata = array('cashier_issue_no'=>$cashier_issue_no,'cashier_batch_no'=>$cashier_batch_no);
                $this->_occupancycashering->updateData($lastinsertid,$updatedata);
                $uptdata = array('latestusedor' => $this->occupancyData['or_no']);
                $this->_occupancycashering->UpdateOrused('3',$uptdata);

                $cashierdetails['cashier_id'] = $lastinsertid;
                $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
                if($trans_details->eoa_surcharge_fee > 0){
                    $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
                    $cashdata = $this->_occupancycashering->getCasheringsurchargeIds($trans_details->tfoc_id);
                    $cashierdetails['ctc_taxable_amount'] ="0";
                    $cashierdetails['tfc_amount'] =$trans_details->eoa_surcharge_fee;
                    $cashierdetails['top_transaction_id'] = $this->occupancyData['top_transaction_id'];
                    $cashierdetails['or_no'] = $this->occupancyData['or_no'];
                    $cashierdetails['isotehrtaxes'] ='1';
                    $getortype = $this->_occupancycashering->GetOrtypeid('4');
                    $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                    $fundid = "0"; $glaccountid ="0"; $slid="0";
                    if(!empty($cashdata)){
                        if(isset($cashdata->tfoc_surcharge_gl_id) && isset($cashdata->tfoc_surcharge_sl_id)){
                            $cashierdetails['agl_account_id'] = $cashdata->tfoc_surcharge_gl_id;
                            $cashierdetails['sl_id'] = $cashdata->tfoc_surcharge_sl_id;
                            $fundid = $cashdata->fund_id; 
                            $glaccountid = $cashdata->tfoc_surcharge_gl_id;
                            $slid = $cashdata->tfoc_surcharge_sl_id;
                        }
                    }
                    $cashierdetails['cashier_year'] = date('Y');
                    $cashierdetails['cashier_month'] = date('m');
                    $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                    $cashierdetails['updated_at']= date('Y-m-d H:i:s');
                    $cashierdetails['created_at'] = date('Y-m-d H:i:s');
                    $cashierdetailid = $this->_occupancycashering->addCashierDetailsData($cashierdetails);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $cashierdetailid;
                        $addincomedata['tfoc_is_applicable'] = '4';
                        $addincomedata['taxpayer_name'] = $taxpayername;
                        $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                        $addincomedata['amount'] = $trans_details->eoa_surcharge_fee;
                        $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                        $addincomedata['fund_id'] = $fundid;
                        $addincomedata['gl_account_id'] = $glaccountid;
                        $addincomedata['sl_account_id'] = $slid;
                        // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                        $addincomedata['or_no'] = $this->occupancyData['or_no'];
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
            $Cashierid = $lastinsertid;    
            $defaultamount  = $trans_details->amountdefault - $trans_details->eoa_surcharge_fee; 
            $cashdata = $this->_occupancycashering->getCasheringIds($trans_details->tfoc_id);
            $cashierdetails['tfoc_id'] =$trans_details->tfoc_id;
            $cashierdetails['ctc_taxable_amount'] ='0';
            $cashierdetails['tfc_amount'] =$defaultamount;
            $cashierdetails['all_total_amount'] =$defaultamount;
            $cashierdetails['top_transaction_id'] = $this->occupancyData['top_transaction_id'];
            $cashierdetails['or_no'] = $this->occupancyData['or_no'];
            $getortype = $this->_occupancycashering->GetOrtypeid('4');
            $cashierdetails['ortype_id'] =  $getortype->ortype_id;
            $cashierdetails['cashier_remarks'] = $this->occupancyData['cashier_remarks'];
            $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
            $cashierdetails['sl_id'] = $cashdata->sl_id;

            $checkdetailexist =  $this->_occupancycashering->checkrecordisexist($trans_details->tfoc_id,$Cashierid);
            if(count($checkdetailexist) > 0){
                    $this->_occupancycashering->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetails);
            } else{
                $cashierdetails['cashier_year'] = date('Y');
                $cashierdetails['cashier_month'] = date('m');
                $cashierdetails['tfoc_is_applicable'] ='4';
                $cashierdetails['payee_type'] = "1";
                $cashierdetails['client_citizen_id'] =$this->occupancyData['client_citizen_id'];
                $cashierdetailid = $this->_occupancycashering->addCashierDetailsData($cashierdetails);

                $addincomedata = array();
                $addincomedata['cashier_id'] = $lastinsertid;
                $addincomedata['cashier_details_id'] = $cashierdetailid;
                $addincomedata['tfoc_is_applicable'] = '4';
                $addincomedata['taxpayer_name'] = $taxpayername;
                $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                $addincomedata['amount'] = $defaultamount;
                $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                $addincomedata['fund_id'] = $cashdata->fund_id;
                $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                $addincomedata['sl_account_id'] = $cashdata->sl_id;
                // $addincomedata['cashier_or_date'] = $value->amount $_POST['applicationdate'];
                $addincomedata['or_no'] = $this->occupancyData['or_no'];
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
            
            $Cashierid = $lastinsertid;
            if(!empty($getallFeeseeoa) )
            {
                $cashierdetails=array();
                $detailsoccupancy = array();
                foreach ($getallFeeseeoa as $key => $value){
                    $value = (object)$value;
                    if(isset($value->desc)){
                        $cashdata = $this->_occupancycashering->getCasheringIds($trans_details->tfoc_id);
                        $detailsoccupancy['top_transaction_id'] =$this->occupancyData['top_transaction_id'];
                        $detailsoccupancy['cashier_id'] = $lastinsertid;
                        $detailsoccupancy['tfoc_is_applicable'] =  "4";
                        $detailsoccupancy['tfoc_id'] = $trans_details->tfoc_id;
                        $detailsoccupancy['fees_description'] = $value->desc;
                        $detailsoccupancy['tfc_amount'] = $value->amountnosave;
                        if(!empty($cashdata)){
                            $detailsoccupancy['agl_account_id'] = $cashdata->gl_account_id;
                            $detailsoccupancy['sl_id'] = $cashdata->sl_id;   
                        }
                        $checkdetailexist =  $this->_occupancycashering->checkeng_occupancy_details($value->desc,$lastinsertid);
                        if(count($checkdetailexist) > 0){
                            $detailsoccupancy = array();
                            $detailsoccupancy['fees_description'] = $value->desc;
                            $detailsoccupancy['tfc_amount'] = $value->amountnosave;
                                $this->_occupancycashering->updateeng_occupancy_detailsData($checkdetailexist[0]->id,$detailsoccupancy);
                        } else{
                            $detailsoccupancy['cashier_year'] = date('Y');
                            $detailsoccupancy['cashier_month'] = date('m');
                            $this->_occupancycashering->addeng_occupancy_detailsData($detailsoccupancy);

                            $addincomedata = array();
                            $addincomedata['cashier_id'] = $lastinsertid;
                            $addincomedata['cashier_details_id'] = '0';
                            $addincomedata['tfoc_is_applicable'] = '4';
                            $addincomedata['taxpayer_name'] = $taxpayername;
                            $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                            $addincomedata['amount'] = $value->amountnosave;
                            $addincomedata['tfoc_id'] = $trans_details->tfoc_id;
                            $addincomedata['fund_id'] = $cashdata->fund_id;
                            $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                            $addincomedata['sl_account_id'] = $cashdata->sl_id;
                           // $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                            $addincomedata['or_no'] = $this->occupancyData['or_no'];
                            $addincomedata['form_code'] = $coaddata->cpor_series;
                            $addincomedata['or_register_id'] =  $getorRegister->id;
                            $addincomedata['or_from'] =  $getorRegister->ora_from;
                            $addincomedata['or_to'] =  $getorRegister->ora_to;
                            $addincomedata['coa_no'] =  $coaddata->coa_no;
                            $addincomedata['is_collected'] =  0;
                            $addincomedata['created_by'] =  \Auth::user()->id;
                            $addincomedata['created_at'] =  date('Y-m-d H:i:s');

                        // $this->_commonmodel->addcashierIncomeData($addincomedata);
                        }
                    }
                }
            }
          
            $arrData=array();  $top_transaction_id = $this->occupancyData['top_transaction_id'];
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_occupancycashering->updateTopTransaction($top_transaction_id,$arrData);
            //return redirect()->route('occupancycashering.index')->with('success', __($success_msg));
        }     
    }

    public function getallFeeseeoa($id){
        $ejrid = $id;
        $defaultFeesarr = $this->_occupancycashering->GetReqiestfees($ejrid);
        $html ="";
        $getsurchargefee = $this->_occupancycashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->eoa_surcharge_fee;
       // return $defaultFeesarr;
       $resultArray = array();

       foreach ($defaultFeesarr as $key => $val) {
           if ($val->tax_amount > 0) {
               $row = array(
                   'year' => Date('Y'),
                   'fees_description' => $val->fees_description,
                   'taxableamount' => '', // You need to fill in the logic for this field
               );
       
               if ($val->is_default == '1') {
                   $row['taxfees'] = $val->tfoc_id;
                   $row['textonly'] = $val->fees_description;
                   $row['amount'] = $val->tax_amount;
               } else {
                   $row['desc'] = $val->fees_description;
                   $row['amountnosave'] = $val->tax_amount;
               }
       
               $resultArray[] = $row;
           }
       }
       return $resultArray;
    }
   
    public function getOccupancyPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_occupancycashering->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function getTransactionid($trans_id){
            $id=$trans_id;
            $arrApptype = config('constants.arrCpdoAppModule');
            $data = $this->_occupancycashering->Gettransactionnobyid($id); 
            $htmloption ="";
            // foreach ($data as $key => $value) {
            // 	$htmloption .='<option value='.$value->transaction_no.'>'.$value->transaction_no.'</option>';
            // }
            //echo $htmloption."#".$value->caf_control_no."#".$value->caf_date."#".$arrApptype[$value->cm_id]; exit;
            //$data->es_id = $arrApptype[$data->es_id];
            $finalamount = $data->eoa_total_fees - $data->eoa_surcharge_fee;
            $data->amountdefault = $finalamount;
            return $data;
    }
    public function updateCashier($res,$request){ 
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$res['transaction_no'])->pluck('id')->first();
        if($top_transaction_id>0){
            $this->data['top_transaction_id'] = $this->dataDtls['top_transaction_id'] = $top_transaction_id;
            $this->data['or_no'] = $request->input('or_num');
            $this->data['total_amount'] = $this->data['net_tax_due_amount'] = $res['total_amount'];
            $this->data['total_paid_amount'] = $res['total_paid_amount'];

            $this->data['busn_id'] = $this->dataDtls['busn_id'] = $res['busn_id'];
            $this->data['app_code'] = $this->dataDtls['app_code'] =  $res['app_code'];
            $this->data['pm_id'] = $this->dataDtls['pm_id'] = $res['pm_id'];
            $this->data['pap_id'] = $this->dataDtls['pap_id'] = $res['pap_id'];
            $this->data['total_paid_interest'] ='';
            $this->data['total_paid_surcharge'] ='';
            $this->data['payment_terms'] ='5'; //Online Payment

            $clientdata = $this->_commonmodel->getClientName($res['client_id']);
            $taxpayername = $clientdata->full_name;
            $this->data['taxpayers_name'] = $taxpayername;
            $this->data['cashier_particulars']='Business Permit Fee';
            $this->data['ortype_id'] =  $this->ortype_id; // Accountable Form No. 51-C

            $this->dataDtls['cashier_year'] = $this->data['cashier_year'] = $res['bill_year'];
            $this->dataDtls['cashier_month'] = $this->data['cashier_month'] = $res['bill_month'];
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='1';
            $this->dataDtls['payee_type'] = $this->data['payee_type'] = "1";
            $this->dataDtls['client_citizen_id'] =$this->data['client_citizen_id']=$res['client_id'];

            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
           
            $this->dataDtls['created_by'] = $this->data['created_by']=\Auth::user()->id;
            $this->dataDtls['created_at'] = $this->data['created_at'] = date('Y-m-d H:i:s');
            $this->data['status'] = '1'; 
            $this->data['payment_type'] = 'Online';
            $this->data['cashier_or_date'] = $request->input('cashier_or_date');

            $issueNumber = $this->getPrevIssueNumber();
            $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
            $cashier_batch_no = date('Y')."-".$cashier_issue_no;
            $coaddata = '';
           // dd($res['or_no']);
            $getorRegister = $this->_commonmodel->Getorregisterid($this->ortype_id,$res['or_no']);
           
            if($getorRegister != Null){
                $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $res['or_no']);
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
            Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$res['busn_id']); // This for remote server
            $this->_CashierBusinessPermit->updateBusinessStatus((int)$res['busn_id'],array('busn_app_status'=>'5','is_final_assessment'=>'1'));
            
            $this->dataDtls['cashier_id'] = $lastinsertid;
            $this->dataDtls['cashier_issue_no'] =$issueNumber;
            $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
            $success_msg = 'Cashiering added successfully.';
            

            $Cashierid = $lastinsertid;
            $arrEndDpt= $this->_CashierBusinessPermit->getEndDeptDetails();

            $arrDetails=$this->getPaymentDetails($top_transaction_id);
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $val){
                    $tfoc_id = $val->tfoc_id;
                    $arrTfoc = $this->_CashierBusinessPermit->getTfocDtls($tfoc_id);
                    $this->dataDtls['tfoc_id'] =$tfoc_id;
                    $this->dataDtls['interest_fee'] = $val->interest_fee;
                    $all_total_amount = $val->tfoc_amount + $val->surcharge_fee + $val->interest_fee;
                    $this->dataDtls['tfc_amount'] = $val->tfoc_amount;
                    $this->dataDtls['all_total_amount'] = $all_total_amount;
                    $this->dataDtls['surcharge_sl_id'] = $val->surcharge_sl_id;
                    $this->dataDtls['surcharge_fee'] = $val->surcharge_fee;
                    $this->dataDtls['interest_sl_id'] = $val->interest_sl_id;
                    $this->dataDtls['interest_fee'] = $val->interest_fee;
                    $this->dataDtls['or_no'] = $this->data['or_no'];
                    $this->dataDtls['ortype_id'] =  2; // Accountable Form No. 51-C
                    $this->dataDtls['agl_account_id'] = $arrTfoc->gl_account_id;
                    $this->dataDtls['sl_id'] = $arrTfoc->sl_id;
                    $this->dataDtls['subclass_id'] = $val->subclass_id;
                    $arrSubClass = $this->_CashierBusinessPermit->getSubClassDtls($val->subclass_id);
                    if(isset($arrSubClass)){
                        $this->dataDtls['section_id'] = $arrSubClass->section_id;
                        $this->dataDtls['division_id'] = $arrSubClass->division_id;
                        $this->dataDtls['group_id'] = $arrSubClass->group_id;
                        $this->dataDtls['class_id'] = $arrSubClass->class_id;
                    }
                    $checkdetailexist =  $this->_CashierBusinessPermit->checkRecordIsExist($tfoc_id,$Cashierid);
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
                        $this->addBPLOCasheringIncomeDtls($cashierDetailsId,$getorRegister,$coaddata,$clientdata->p_barangay_id_no);
                    }
                }
            }
            $this->addIssuanceDetails($res['busn_id'],$res['app_code']);
            //Delete Delinquncy because payment paid
            $this->_assessmentCalculationCommon->deleteDelinquency($res['busn_id'],$res['app_code'],date("Y"));

            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Business Permit Cashiering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            // Log Details End
            $arrAssesmentData=array();
            $arrAssesmentData['payment_status']=1;
            $trans_no=$res['transaction_no'];
            $this->_CashierBusinessPermit->updateFinalAssessmentByTransNo($trans_no,$arrAssesmentData);//update assesmet to paid
            Session::put('remote_cashier_id',$Cashierid);
            Session::put('PRINT_CASHIER_ID',$Cashierid);
        }
    }

    public function addBPLOCasheringIncomeDtls($cashier_details_id,$getorRegister,$coaddata,$barangayid){
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

    public function addIssuanceDetails($busn_id,$app_code){
        $user_id= \Auth::user()->id;
        $user= $this->_bplobusinesspermit->employeeData($user_id);
        $position = '';
        if(isset($user)){
            $position=$user->description;
        }
        $year = date('Y');
        if($this->data['app_code']==3){ 
            $this->addRetirementIssuance($busn_id,$year,$position);
        }else{
            // Only for Renew And New
            $data = array();
            $arrBrng = $this->_CashierBusinessPermit->getBusinessDetails($busn_id);
            if(isset($arrBrng)){
                $data['brgy_id'] = $arrBrng->busn_office_barangay_id;
                $data['client_id'] = $arrBrng->client_id;
                $data['pm_id'] = (int)$arrBrng->pm_id;
            }
            $data['busn_id'] = $busn_id;
            $data['bpi_year'] = $year;
            $data['bpi_issued_date'] = date("Y-m-d");
            $data['bpi_month']=date("m");
            $data['app_type_id']=$app_code;
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
    public function getPrevIssuanceNumber(){
        $number=1;
        $arrPrev = $this->_bplobusinesspermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->bpi_no+1;
        }
        return $number;
    }
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_CashierBusinessPermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function getPaymentDetails($transactionId){
        $arrFinal = array();
        $arrTrans = $this->_CashierBusinessPermit->getTopTransactionDtls($transactionId);
        if(isset($arrTrans)){
            $finalIds = explode(",",$arrTrans->final_assessment_ids);
            $assessDtlsIds = $this->_CashierBusinessPermit->getAssessmentDetails($arrTrans->busn_id,$finalIds);
            if(!empty($assessDtlsIds)){
                $finalAssesIds = explode(",",$assessDtlsIds);
                $arrFinal = $this->_CashierBusinessPermit->getFinalAssessmentDetails($arrTrans->busn_id,$finalAssesIds);
            }
        }
        return $arrFinal;
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

        $html['assess_year'] =$val->assess_year;
        $html['description'] =$val->description;
        $html['tfoc_amount'] =number_format($val->tfoc_amount,2);
        $html['surchage_interest'] =number_format($surchage_interest,2);
        $html['total'] =number_format($total,2);

        $html['surcharge_sl_id'] =$val->surcharge_sl_id;
        $html['surcharge_fee'] =$val->surcharge_fee;
        $html['interest_sl_id'] =$val->interest_sl_id;
        $html['interest_fee'] =$val->interest_fee;
        $html['subclass_id'] =$val->subclass_id;
        $html['tfoc_id'] =$val->tfoc_id;
        $html['tfoc_amount'] =$val->tfoc_amount;
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
    public function getList($request){
        $remortServer = DB::connection('remort_server');
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $flt_Status=$request->input('flt_Status');
        $department_flt=$request->input('department_flt');

        if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
        }

        $columns = array( 
        0 =>"id",
        1 =>"department_id",
        2 =>"clients.full_name",
        3 =>"bill_year",
        4 =>"bill_month",
        5 =>"total_amount",
        6 =>"total_paid_amount",
        7 =>"transaction_no",
        8 =>"payment_date",
        9 =>"payment_status",
        );

        $sql = $remortServer->table('payment_history')
         //$sql = DB::table('payment_history')
            ->leftJoin('clients', 'clients.client_frgn_id', '=', 'payment_history.client_id')
            //->leftJoin('clients', 'clients.id', '=', 'payment_history.client_id')
            ->select('payment_history.*','clients.full_name');
            $sql->where('payment_history.is_approved',0);
            //$sql->groupBy('payment_history.transaction_no');
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where($remortServer->raw('LOWER(payment_history.department_id)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(clients.full_name)'),'like',"%".strtolower($q)."%");
                });
            }
        if($flt_Status != null){
            $sql->where('payment_history.payment_status',$flt_Status);
        } 
        if($department_flt != null){
            $sql->where('payment_history.department_id',$department_flt);
        }    
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
        $sql->orderBy('payment_history.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=count($sql->get());
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
