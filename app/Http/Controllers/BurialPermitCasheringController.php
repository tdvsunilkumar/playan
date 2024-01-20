<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\BurialPermitCashering;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use App\Models\EcoDataCemetery;
use App\Models\RegCauseOfDeath;
use App\Models\SocialWelfare\Citizen;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use PDF;
use Carbon\Carbon;
use Auth;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class BurialPermitCasheringController extends Controller
{
    public $data = [];
    public $dataDtls = [];
    public $burialarray = [];
    private $slugs;
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->_Burialpermit = new BurialPermitCashering(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','cashier_or_date'=>date("Y-m-d"),'client_citizen_id'=>'','or_no'=>'','total_amount'=>'0','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','total_paid_interest'=>'','payment_terms'=>'1','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'','cashier_remarks'=>'','payee_type'=>'2','cashier_particulars'=>'');

        $this->dataDtls = array('client_citizen_id'=>'','payee_type'=>'');
        $this->burialarray = array('expired_id'=>'','expired_name'=>'','death_caused'=>'','death_date'=>'','cm_id'=>'','disposition_date'=>'','is_infectious'=>'','is_embalmed'=>'');
        $this->slugs = 'cashier/burial-permit';
        $getortype = $this->_Burialpermit->GetOrtypeid('8');
                $this->ortype_id =  $getortype->ortype_id; 
    }
    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        return view('BurialPermit.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Burialpermit->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        $arrStatus=array();
        $arrStatus[0] ='<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>';
        $arrStatus[1] ='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>';

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['cashier_year']=$row->cashier_year;  
            if($row->payee_type==2){
                $arr[$i]['completeaddress']= $this->_commonmodel->getCitizenAddress($row->client_citizen_id);
                $arr[$i]['taxpayername']= $row->cit_fullname;
            }
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            $address = wordwrap($arr[$i]['completeaddress'], 40,"<br>\n");
            $arr[$i]['completeaddress']= '<span class="showLess">'.$address.'</span>';
            $arr[$i]['payee_type']=config('constants.arrPayeeType')[(int)$row->payee_type];
            $arr[$i]['expired_name']=$row->burial_expired_name;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['status']=$arrStatus[$row->status];
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['cashier']=$row->fullname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('cashier/burial-permit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Burial Permit Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div><div class="action-btn bg-info ms-2">
                        <a href="'.url('cashier/burial-permit/printReceipt?id='.$row->id).'" target="_blank" title="Print Engg. Cashiering"  data-title="Print Engg. Cashiering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
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
            $arrCheque=array();  $deathcauseresonarray = array(); $getCemeteriesarray = array();
            $arrBankDtls=array();
            $arrChequeBankDtls=array("id"=>"","check_type_id"=>"","opayment_date"=>"","fund_id"=>"","bank_id"=>"","bank_account_no"=>"","opayment_transaction_no"=>"","opayment_check_no"=>"","opayment_amount"=>"");
             $i=0;

            $data = (object)$this->data;
            $burialarray = (object)$this->burialarray;
            $getdatausersave = $this->_Burialpermit->CheckFormdataExist('25',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->payee_type = $usersaved->payee_type;
               } 

            foreach($arrChequeBankDtls as $key=>$val){
                $arrCheque[$i][$key]=$val;
                $arrBankDtls[$i][$key]=$val;
            }
            foreach ($this->_Burialpermit->getFundCode() as $val) {
                $arrFund[$val->id]=$val->code;
            } 
            foreach ($this->_Burialpermit->getBankList() as $val) {
                $arrBank[$val->id]=$val->bank_code;
            }
            foreach ($this->_Burialpermit->getTransactions($request->input('id')) as $val) {
                $arrTransactionNum[$val->id]=$val->transaction_no;
            } 
            foreach ($this->_Burialpermit->getCancelReason() as $val) {
                $arrCancelReason[$val->id]=$val->ocr_reason;
            }
            foreach ($this->_Burialpermit->getCemeteries() as $val) {
                $getCemeteriesarray[$val->id]="[".$val->brgy_name."]=>[".$val->cem_name."]";
            }
            foreach ($this->_Burialpermit->getChequeTypes() as $val) {
                $arrChequeTypes[$val->id]=$val->ctm_description;
            }
            foreach ($this->_Burialpermit->getCitizens() as $val) {
                $arrUser[$val->id]=$val->cit_fullname;
            }
            foreach ($this->_Burialpermit->getTaxFees($data->payee_type) as $val) {
                $arrTfocFees[$val->id]=$val->description;
            }

            $isOrAssigned = (int)$this->_Burialpermit->checkORAssignedORNot();
            $arrNature=array();
            $arrDocumentDetailsHtml='';
            
        }
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_Burialpermit->getEditDetails($request->input('id'));
            if(isset($data)){
                if($data->payee_type==2){
                    foreach ($this->_Burialpermit->getCitizens($data->client_citizen_id) as $val) {
                        $arrUser[$val->id]=$val->cit_fullname;
                    }
                }
                $arrTfocFees = array();
                foreach ($this->_Burialpermit->getTaxFeesEdit($data->payee_type) as $val) {
                $arrTfocFees[$val->id]=$val->description;
                }
                $data->created_at = date("d/m/Y",strtotime($data->created_at));
                $data->cashier_or_date = date("d/m/Y",strtotime($data->cashier_or_date));
                $arrFeesDtls = $this->_Burialpermit->getNatureFeeDetails($data->id);
                if(count($arrFeesDtls)>0){
                    $arrNature = json_decode(json_encode($arrFeesDtls), true);
                }
            }
            $arrPaymentDetails = $this->_Burialpermit->getPaymentModeDetails($request->input('id'),3);
            $arrCheque = json_decode(json_encode($arrPaymentDetails), true);
            $arrPaymentDetails = $this->_Burialpermit->getPaymentModeDetails($request->input('id'),2);
            $arrBankDtls = json_decode(json_encode($arrPaymentDetails), true);

            $burialarray = $this->_Burialpermit->getEditDetailsofburial($request->input('id'));

            foreach ($this->_Burialpermit->getDeathreasonsedit($burialarray->death_caused) as $val) {
                $deathcauseresonarray[$val->id]=$val->cause_of_death;
            } 

        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            foreach((array)$this->dataDtls as $key=>$val){
                $this->dataDtls[$key] = $request->input($key);
            }
            foreach((array)$this->burialarray as $key=>$val){
                $this->burialarray[$key] = $request->input($key);
            }

            $this->data['ortype_id'] =  $this->ortype_id; // Accountable Form No. 51-C
            if($this->data['payee_type'] == 1){
              $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
              $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
             }else{
              $clientdata = $this->_commonmodel->getCitizenName($this->data['client_citizen_id']);
              $taxpayername = $this->_commonmodel->getUserName($clientdata->cit_first_name,$clientdata->cit_middle_name,$clientdata->cit_last_name,$clientdata->cit_suffix_name);
              }
            $this->data['taxpayers_name'] = $taxpayername;  
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='8';
            $this->data['net_tax_due_amount'] = $this->data['total_amount'];

            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
          
            if($request->input('id')>0){
                unset($this->data['created_at']);
                unset($this->data['cashier_batch_no']);
                unset($this->data['cashier_or_date']);
                $this->_Burialpermit->updateData($request->input('id'),$this->data);
                $success_msg = 'Cashiering updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = '1'; 
                $this->data['payment_type'] = 'Walk-In';
                $this->data['payee_type'] = '2';
                $this->data['cashier_or_date'] = date("Y-m-d");
                $this->data['burial_expired_name'] = $request->input('expired_name');
                $this->data['cashier_year'] = date('Y');
                $this->data['cashier_month'] = date('m');
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $getorRegister = $this->_commonmodel->Getorregisterid($this->data['ortype_id'],$this->data['or_no']);
                if($getorRegister != Null){
                     $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_Burialpermit->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no; 
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_Burialpermit->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_Burialpermit->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }       
                }

                $this->data['cashier_issue_no'] = $issueNumber; 
                $this->data['cashier_batch_no'] = $cashier_batch_no; 
                $lastinsertid = $this->_Burialpermit->addData($this->data);
                Session::put('BURIAL_PRINT_CASHIER_ID',$lastinsertid);
                // update latest used OR
                if($request->input('isuserrange')){
                    $arrOrData = array('latestusedor' => $this->data['or_no']);
                    $ortype_id=2;  // Accountable Form No. 51-C
                    $this->_Burialpermit->updateOrUsed($ortype_id,$arrOrData);
                }
                $this->dataDtls['cashier_id'] = $lastinsertid;
                $this->dataDtls['cashier_issue_no'] =$issueNumber;
                $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
                $success_msg = 'Cashiering added successfully.';
            }

            $Cashierid = $lastinsertid;
            $arrDetails = $request->input('tfoc_id');
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    $arrTfoc = $this->_Burialpermit->getTfocDtls($value);
                    $this->dataDtls['tfoc_id'] =$value;
                    $this->dataDtls['tfc_amount'] = $request->input('tfc_amount')[$key];
                    $this->dataDtls['all_total_amount'] = $request->input('tfc_amount')[$key];
                    $this->dataDtls['ctc_taxable_amount'] = $request->input('ctc_taxable_amount')[$key];
                    $this->dataDtls['or_no'] = $this->data['or_no'];
                    $this->dataDtls['ortype_id'] =  2; // Accountable Form No. 51-C
                    $this->dataDtls['payee_type'] =  2; 
                    $fundid = "0"; $glaccountid ="0"; $slid="0";
                    if(!empty($arrTfoc)){
                    $this->dataDtls['agl_account_id'] = $arrTfoc->gl_account_id;
                    $this->dataDtls['sl_id'] = $arrTfoc->sl_id;
                    $fundid = $arrTfoc->fund_id; 
                     $glaccountid = $arrTfoc->gl_account_id;
                     $slid = $arrTfoc->sl_id;
                    }
                    
                    $checkdetailexist =  $this->_Burialpermit->checkRecordIsExist($value,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_Burialpermit->updateCashierDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $this->dataDtls['created_by'] =\Auth::user()->id;
                        $this->dataDtls['created_at'] = date('Y-m-d H:i:s');
                        $this->dataDtls['cashier_year'] = date('Y');
                        $this->dataDtls['cashier_month'] = date('m');
                        $Cashierdid = $this->_Burialpermit->addCashierDetailsData($this->dataDtls);
                        $this->_commonmodel->insertCashReceipt($value, $request->total_paid_amount, $request->or_no,$request->cashier_particulars);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $Cashierdid;
                        $addincomedata['tfoc_is_applicable'] = '8';
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

            $checkburialexist =  $this->_Burialpermit->checkBurialIsExist($Cashierid,$Cashierdid );
            if(count($checkburialexist) > 0){
                $this->_Burialpermit->updateregBurialData($checkburialexist[0]->id,$this->burialarray);
            } else{
                $this->burialarray['created_by'] =\Auth::user()->id;
                $this->burialarray['created_at'] = date('Y-m-d H:i:s');
                $this->burialarray['cashierd_id'] = $Cashierdid;
                $this->burialarray['cashier_id'] = $Cashierid;
                $this->burialarray['or_no'] = $this->data['or_no'];
                $this->burialarray['or_date'] = $this->data['cashier_or_date'];
                $this->burialarray['or_amount'] = $this->data['total_amount'];
                $this->burialarray['expired_name'] = $request->input('expired_name');
               $burial_id = $this->_Burialpermit->addregBurialData($this->burialarray);
            }
            $arrayupdate = array('burial_id'=>$burial_id);
            $this->_Burialpermit->updateData($Cashierid,$arrayupdate);
            $this->_Burialpermit->updateCashierDetailsData($Cashierdid,$arrayupdate);
            // Log Details Start
            $smsTemplate=SmsTemplate::where('id',68)->where('is_active',1)->first();
            $arrData = $this->_Burialpermit->getappdatacitizen($this->data['client_citizen_id']); 
           
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
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Miscellaneous Cashering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            // Log Details End
            return redirect()->route('burialcashering.index')->with('success', __($success_msg));
        }
        //echo "<pre>"; print_r($arrTfocFees); exit;
        return view('BurialPermit.create',compact('data','arrTransactionNum','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned','arrUser','arrNature','arrTfocFees','burialarray','deathcauseresonarray','getCemeteriesarray'));
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
    public function printReceipt(Request $request){
            $id = $request->input('id');
            $ctcdata = $data = $this->_Burialpermit->getCertificateDetails($id);;
            $expired = Citizen::find($ctcdata->expired_id);
            // print reciept
            $data = [
                'transacion_no' => $ctcdata->or_no,
                'date' => $ctcdata->created_at,
                'or_number' => $ctcdata->or_no,
                'payor' => $ctcdata->cit_fullname,
                'total' => $ctcdata->total_amount,
                'payment_terms' => $ctcdata->payment_terms,
            ];
            // $this->printReceiptburial($data);

            // PDF PRINTING
            
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'DL');
        PDF::SetFont('Helvetica', '', 10);

        $border = 0;
        $topPos = 32.5;
        PDF::writeHTMLCell(50, 0, 45,$topPos +2 ,config('constants.defaultCityCode.city'), $border);//City
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::writeHTMLCell(50, 0, 70, 16+ $topPos, $data['or_number'], $border);//Or number

        PDF::SetFont('Helvetica', '', 10);

        PDF::writeHTMLCell(50, 0, 15,40 + $topPos,$data['payor'], $border);//Payor

        PDF::writeHTMLCell(50, 0, 40,50  + $topPos,config('constants.defaultCityCode.city').' City', $border);//City
        PDF::writeHTMLCell(50, 0, 40,60  + $topPos,config('constants.defaultCityCode.province_name'), $border);//Province 
        
        $birth_date = Carbon::parse($expired->cit_date_of_birth);
        $death_date = Carbon::parse($ctcdata->death_date);
        $age = $death_date->diffInYears($birth_date);
        $is_infectious = 'Non-Infectious';
        $is_embalmed = 'Not Embalmed';
        if ($ctcdata->is_infectious) {
            $is_infectious = 'Infectious';
        }
        if ($ctcdata->is_embalmed) {
            $is_embalmed = 'Embalmed';
        }

        PDF::writeHTMLCell(50, 0, 30,75 + $topPos,$ctcdata->expired_name, $border);//expired name
        PDF::writeHTMLCell(50, 0, 37,80 + $topPos,$expired->nationality(), $border);//nationality
        PDF::writeHTMLCell(50, 0, 27,85 + $topPos,$age, $border);//age
        PDF::writeHTMLCell(50, 0, 59,85 + $topPos,$expired->gender, $border);//gender
        PDF::writeHTMLCell(50, 0, 40,90 + $topPos,$death_date->toDateString(), $border);//death date
        PDF::writeHTMLCell(50, 0, 43,94 + $topPos,RegCauseOfDeath::find($ctcdata->death_caused)->cause_of_death, $border);//cause of death
        PDF::writeHTMLCell(50, 0, 47,99 + $topPos,EcoDataCemetery::find($ctcdata->cm_id)->cem_name, $border);//cemetery
        PDF::writeHTMLCell(50, 0, 60,108 + $topPos,$is_infectious, $border);//infectious
        PDF::writeHTMLCell(50, 0, 68,113 + $topPos,$is_embalmed, $border);//embalmed
        PDF::writeHTMLCell(50, 0, 52,118 + $topPos,$ctcdata->disposition_date, $border);//disposition
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(35, 0, 10,134 + $topPos,number_format($data['total'],2), $border); //total

        PDF::writeHTMLCell(50, 0, 50,132  + $topPos,Carbon::now()->toDateString(), $border);//City

        PDF::writeHTMLCell(50, 0, 20,142  + $topPos,config('constants.defaultCityCode.city').' City', $border);//City
        PDF::writeHTMLCell(50, 0, 58,142  + $topPos,config('constants.defaultCityCode.province_name'), $border);//Province 

        PDF::writeHTMLCell(50, 0, 20,149  + $topPos,Carbon::now()->toDateString(), $border);//City
        PDF::writeHTMLCell(35, 0, 35,162 + $topPos,number_format($data['total'],2), $border); //total
        
        PDF::writeHTMLCell(45, 0, 53,165 + $topPos,Auth::user()->hr_employee->fullname, $border,0,0,true,'L'); //collecting office

        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['transacion_no'].'.pdf';

        $arrSign= $this->_commonmodel->isSignApply('cashier_burial_permit_collecting_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        if(!$signType || !$isSignVeified){
            PDF::Output($folder.$filename);
        }else{
            $signature = $this->_commonmodel->getuserSignature(Auth::user()->id);
            $path =  public_path().'/uploads/e-signature/'.$signature;
            if($isSignVeified==1 && $signType==2){
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                if(!empty($signature) && File::exists($path)){
                    // Apply Digital Signature
                    PDF::Output($folder.$filename,'F');
                    $arrData['signaturePath'] = $signature;
                    $arrData['filename'] = $filename;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
            if($isSignVeified==1 && $signType==1){
                // Apply E-Signature
                if(!empty($signature) && File::exists($path)){
                    PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                }
            }
        }
        PDF::Output($folder.$filename);
    }

    public function printReceiptburial($data)
    {
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'cm', array(10, 20), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', 10);

        $border = 0;
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::writeHTMLCell(50, 0, 50,37, $data['or_number'], $border);//Or number

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(50, 0, 45,43,Carbon::parse($data['date'])->toFormattedDateString(), $border);//Date

        PDF::writeHTMLCell(50, 0, 20,59,$data['payor'], $border);//Payor

        PDF::writeHTMLCell(52, 0, 25,66,$data['loacalitydata']->mun_desc, $border);
        //agency
        PDF::writeHTMLCell(52, 5, 25,72,'Nueva Ecija', $border);//agency

        PDF::writeHTMLCell(52, 5, 25,78,$data['burialinfo']->expired_name, $border);

        PDF::writeHTMLCell(52, 5, 25,82,$data['burialinfo']->nationality, $border);
        PDF::writeHTMLCell(52, 5, 25,85,$data['burialinfo']->death_date, $border);
        
        $htmldynahistory='<table border="'.$border.'">';
        foreach ($data['transactions'] as $key => $value) {
            if ($value['tax_amount'] != 0) {
                $htmldynahistory .='<tr>
                        <td colspan="2" style="text-align:left;font-size:10px;">
                        '.$value['fees_description'].'
                        </td>
                        <td></td>
                        <td style="text-align:left;font-size:10px;">'.number_format($value['tax_amount'],2).'</td>
                    </tr>';
            }
        }
        $htmldynahistory .='</table>';
        PDF::SetFont('Helvetica', '', 7);
        //PDF::writeHTMLCell(90, 5, 6,76,$htmldynahistory, $border); //collection table

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(35, 0, 73,121,number_format($data['total'],2), $border); //total

        $amountinworld =  $this->_commonmodel->numberToWord($data['total']); 
        PDF::writeHTMLCell(55, 0, 33,129,$amountinworld, $border);//amount in words

        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, 9,142,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, 9,148,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, 9,154,$order, $border);// check money order
        
        $htmldynahistory='<table border="'.$border.'" style="text-align:center">';
        foreach ($data['cash_details'] as $key => $value) {
            // dd($value);
                $htmldynahistory .='<tr>
                        <td>'.$this->bank($value->bank_id)->bank_code.'</td>
                        <td>'.$value->opayment_check_no.'</td>
                        <td>'.Carbon::parse($value->opayment_date)->format('m/d/y').'</td>
                    </tr>';
        }
        $htmldynahistory .='</table>';
        PDF::writeHTMLCell(65, 0, 31,146,$htmldynahistory, $border);// bank

        PDF::writeHTMLCell(61, 0, 24,170,Auth::user()->hr_employee->fullname, $border,0,0,true,'C'); //collecting office

        PDF::Output('Receipt: '.$data['transacion_no'].'.pdf');
    }

    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_Burialpermit->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }

    public function checkOrUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $id = $cashierId = $request->input('cashier_id');
        $isUsed = $this->_Burialpermit->checkOrUsedOrNot($or_no,$id);
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
        $this->_Burialpermit->updateData($id,$updataarray);
        $this->_commonmodel->deletecashierincome($id);
        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Miscellaneous Cashering O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
        $data=array('status'=>'success',"message"=>"");
        echo json_encode($data);
        //return redirect()->route('burialcashering.index')->with('success', __('O.R Cancelled Successfully.'));
    }
    public function cancelNaturePaymentOption(Request $request){
        $f_id =  $request->input('f_id');
        $this->_Burialpermit->deleteCashieringDetails($f_id);
        $arr['ESTATUS']=0;
        $arr['message']="Deleted Successfully";
        echo json_encode($arr);exit;
    
    }
    public function getOrnumber(Request $request){ 
        $checkflag = $request->input('orflag');
        $orNumber=1;
        $ortype_id=$this->ortype_id;  // Accountable Form No. 51-C
        if($checkflag == '1'){
            $getorno = $this->_Burialpermit->getLatestOrNumber($ortype_id);
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
            foreach ($this->_Burialpermit->getCitizens() as $val) {
                $name = $this->_commonmodel->getUserName($val->cit_first_name,$val->cit_middle_name,$val->cit_last_name,$val->cit_suffix_name);
                $options .="<option value=".$val->id.">".$name."</option>";
            }
        $arr['ESTATUS']=0;
        $arr['option']=$options;
        echo json_encode($arr);exit;
    }
    public function getTfocDropdown(Request $request){
        $payee_type = $request->input('payee_type');
        $options="<option>Please Select</opton>";
        foreach ($this->_Burialpermit->getTaxFeesajax($payee_type) as $val) {
                $options .="<option value=".$val->id.">".$val->description."</option>";
            }
        $arr['ESTATUS']=0;
        $arr['option']=$options;
        echo json_encode($arr);exit;
    }
    public function getAmountDetails(Request $request){
        $tfoc_id = $request->input('tfoc_id');
        $arrFee = $this->_Burialpermit->getTaxFeesDetails($tfoc_id);
        $arr['ESTATUS']=1;
        $arr['amount']="0.00";
        if(isset($arrFee)){
            $arr['ESTATUS']=0;
            $arr['amount']=$arrFee->tfoc_amount;
        }
        echo json_encode($arr);exit;
    }
    public function getUserDetails(Request $request){
        $payee_type = $request->input('payee_type'); $gender = array("0"=>"Male","1"=>"Female");
        $user_id = $request->input('user_id');
        $arrDtls = $this->_Burialpermit->getUserDetails($payee_type,$user_id);
        $arr['ESTATUS']=1;
        if(isset($arrDtls)){
            $arr['ESTATUS']=0;
                $arr['address']= $this->_commonmodel->getCitizenAddress($user_id);
                $arr['name']= $this->_commonmodel->getUserName($arrDtls->cit_first_name,$arrDtls->cit_middle_name,$arrDtls->cit_last_name,$arrDtls->cit_suffix_name);
                $arr['cit_date_of_birth']=$arrDtls->cit_date_of_birth;
                $arr['cit_gender']= $gender[$arrDtls->cit_gender];
                $arr['country']=$this->_commonmodel->getcountryname($arrDtls->country_id);
        }
        echo json_encode($arr);exit;
    }

    public function getDeathcauses(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_Burialpermit->getDeathreasons($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->cause_of_death;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
}
