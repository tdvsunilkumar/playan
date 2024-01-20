<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\OnlinePaymentHistory;
use App\Models\Bplo\CashierBusinessPermit;
use App\Models\Barangay;
use App\Models\BploBussinessPermit;
use App\Models\BploAssessmentCalculationCommon;
use App\Models\CommonModelmaster;
use App\Models\RptCtoBilling;
use App\Models\RptCtoBillingDetail;
use App\Models\RptCtoBillingDetailsPenalty;
use App\Models\RptCtoBillingDetailsDiscount;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class onlinePaymentHistoryController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $department = array(""=>"Please Select");

     public function __construct(){
		$this->_OnlinePaymentHistory= new OnlinePaymentHistory(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_CashierBusinessPermit = new CashierBusinessPermit(); 
        $this->_Barangay = new Barangay();
        $this->_bplobusinesspermit = new BploBussinessPermit();
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("Y-m-d"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'','total_paid_interest'=>'','payment_terms'=>'1','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'1','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');
        $this->slugs = 'online-payment-history'; 
        foreach ($this->_OnlinePaymentHistory->getAllDepartment() as $val) {
            $this->department[$val->id]=$val->pcs_name;
        }
    }
    
    public function index(Request $request)
    {
        $department=$this->department;
		//$this->is_permitted($this->slugs, 'read');
        //dd($department);
		return view('onlinePaymentHistory.index',compact('department'));
    }

    public function showcpdo(Request $request){
        $this->data = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Planning And Development Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'','payment_terms'=>'','cpdo_type'=>'');
        $data = (object)$this->data; $paymentstatus = 0;
        $arrtypeofcashering = array('0'=>'Please Select','1'=>'Zoning Permit','2'=>'Development Permit');
        $arrgetTransactions = array();  $data->createdat = date('Y-m-d');
        $paymentdata = $this->_OnlinePaymentHistory->getEditDetailsphistory($request->input('pid'));
        //echo "<pre>"; print_r($paymentdata); exit;
        $data->total_paid_amount = $paymentdata->total_paid_amount;  $data->id = 1; 
        $paymentstatus=$paymentdata->payment_status;
        $clientname = $this->_commonmodel->getClientName($paymentdata->client_id);
        $topnumber = $this->_commonmodel->gettopnumber($paymentdata->transaction_no);
        $data->clientname ="";
        if(!empty($clientname->full_name)){
          $data->clientname = $clientname->full_name;   
        }
        $data->pid = $request->input('pid');
        foreach ($this->_commonmodel->getTopnumberarray($topnumber->id) as $val) {
         $arrgetTransactions[$val->id]=$val->transaction_no;
          
        }
        $feesaray = array();
        foreach ($this->_OnlinePaymentHistory->Gettaxfees() as $val) {
            $feesaray[$val->id]=$val->accdesc;
        }
        return view('onlinePaymentHistory.cpdoview',compact('data','arrtypeofcashering','feesaray','arrgetTransactions','paymentstatus'));
    }

    public function showeng(Request $request){
        $this->data = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Engineering Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'','payment_terms'=>'');
        $data = (object)$this->data; $paymentstatus=0;
        $arrgetTransactions = array();  $data->createdat = date('Y-m-d');
        $paymentdata = $this->_OnlinePaymentHistory->getEditDetailsphistory($request->input('pid'));
        //echo "<pre>"; print_r($paymentdata); exit;
        $paymentstatus=$paymentdata->payment_status;
        $data->total_paid_amount = $paymentdata->total_paid_amount;  $data->id = 1;
        $clientname = $this->_commonmodel->getClientName($paymentdata->client_id);
        $topnumber = $this->_commonmodel->gettopnumber($paymentdata->transaction_no);
        $data->clientname = $clientname->full_name; 
        $data->pid = $request->input('pid');
        foreach ($this->_commonmodel->getTopnumberarray($topnumber->id) as $val) {
         $arrgetTransactions[$val->id]=$val->transaction_no;
        }
        return view('onlinePaymentHistory.engview',compact('data','arrgetTransactions','paymentstatus'));
    }

     public function showoccu(Request $request){
        $this->data = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Occupancy Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'','payment_terms'=>'');

        $data = (object)$this->data; $paymentstatus=0;
        $arrgetTransactions = array();  $data->createdat = date('Y-m-d');
        $paymentdata = $this->_OnlinePaymentHistory->getEditDetailsphistory($request->input('pid'));
        //echo "<pre>"; print_r($paymentdata); exit;
        $paymentstatus=$paymentdata->payment_status;
        $data->total_paid_amount = $paymentdata->total_paid_amount;  $data->id = 1;
        $clientname = $this->_commonmodel->getClientName($paymentdata->client_id);
        $topnumber = $this->_commonmodel->gettopnumber($paymentdata->transaction_no);
        $data->clientname = $clientname->full_name; 
        $data->pid = $request->input('pid');
        foreach ($this->_commonmodel->getTopnumberarray($topnumber->id) as $val) {
         $arrgetTransactions[$val->id]=$val->transaction_no;
        }
        return view('onlinePaymentHistory.occuview',compact('data','arrgetTransactions','paymentstatus'));
    }

    public function showrealproperty(Request $request){
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("d/m/Y"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','payment_terms'=>'5','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'2','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');

        $data = (object)$this->data;
        $arrgetTransactions = array();  $data->createdat = date('Y-m-d');
        $paymentdata = $this->_OnlinePaymentHistory->getEditDetailsphistory($request->input('pid'));
          
        $data->id = 1;
        $clientname = $this->_commonmodel->getClientName($paymentdata->client_id);
        $topnumber = $this->_commonmodel->gettopnumber($paymentdata->transaction_no);
        $paymentdataByTop = $this->_OnlinePaymentHistory->getEditDetailsphistoryByTopNo($paymentdata->transaction_no);
        //dd($paymentdataByTop);
        $data->clientname = $clientname->full_name; 
       
        $data->pid = $request->input('pid');
        $data->payment_status = $paymentdata->payment_status;
        foreach ($this->_commonmodel->getTopnumberarray($topnumber->id) as $val) {
         $arrgetTransactions[$val->id]=$val->transaction_no;
        }
        $acceptedTds = DB::table('rpt_cto_billings')->where('transaction_no',(isset($paymentdata->transaction_no))?$paymentdata->transaction_no:0)->pluck('id')->toArray();
        $startEndYearAndQtrsObj = DB::table('rpt_cto_billing_details')
                                      ->whereIn('cb_code',$acceptedTds)
                                      ->select(
                                        DB::raw('MAX(rpt_cto_billing_details.cbd_covered_year) as maxYear'),
                                        DB::raw('MIN(rpt_cto_billing_details.cbd_covered_year) as minYear'),
                                        DB::raw("CASE WHEN rpt_cto_billing_details.sd_mode != 14 THEN MIN(rpt_cto_billing_details.sd_mode) ELSE 11 END as startMode"),
                                        DB::raw("CASE WHEN rpt_cto_billing_details.sd_mode != 14 THEN MAX(rpt_cto_billing_details.sd_mode) ELSE 44 END as endMode")
                                    )
                                      ->first();
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
        $data->total_paid_amount = $netTaxDue;                            
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$paymentdata->transaction_no)->pluck('id')->first();                              
        $data->top_transaction_id=$top_transaction_id;
        $data->payment_history_id=$paymentdata->id;                              
        return view('onlinePaymentHistory.realpropertyview',compact('data','arrgetTransactions','startEndYearAndQtrsObj'));
    }

        public function loadCasheringInfo(Request $request){
         $acceptedTds = DB::table('rpt_cto_billings')->where('transaction_id',$request->id)->pluck('id')->toArray();
        /*$sessionData = session()->get('acceptedTdsForComputation');
        $acceptedTds = ($sessionData != null)?$sessionData:[];*/
        //$casheringInfo = $this->_cashierrealproperty->getEditDetails($request->input('id'));
        //dd($casheringInfo);
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
        return view('onlinePaymentHistory.rpt.casheringInfo',compact('billingData'));

    }

     public function loadacceptedtds(Request $request){
     
        $acceptedTds = DB::table('rpt_cto_billings')->where('transaction_id',$request->id)->pluck('id')->toArray();
         //dd($acceptedTds);
        //$billingData = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
                                  //->whereIn('id',$acceptedTds)->get();
        $billingDetails = DB::table('rpt_cto_billing_details as cbd')
                             ->join('rpt_properties as rp','rp.id','=','cbd.rp_code')
                             ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                                 $j->on('cbdd.cb_code','=','cbd.cb_code')
                                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
                             })
                             ->leftJoin('rpt_cto_billing_details_penalties as cbdp',function($j){
                                 $j->on('cbdp.cb_code','=','cbd.cb_code')
                                  ->on('cbdp.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdp.sd_mode','=','cbd.sd_mode');
                             })
                             ->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no',
                                DB::raw('COALESCE(cbd.basic_amount,0) as basicAmount'),
                                DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basicDiscount'),
                                DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basicPenalty'),

                                DB::raw('COALESCE(cbd.sef_amount,0) as sefAmount'),
                                DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sefDiscount'),
                                DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sefPenalty'),

                                DB::raw('COALESCE(cbd.sh_amount,0) as shAmount'),
                                DB::raw('COALESCE(cbdd.sh_discount_amount,0) as shDiscount'),
                                DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                             ->whereIn('cbd.cb_code',$acceptedTds)->get();
         //dd($billingData);
        return view('cashierrealproperty.ajax.show',compact('billingDetails'));
    }

     public function showbplo(Request $request){
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("d/m/Y"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','busn_id'=>'','app_code'=>'','pm_id'=>'','pap_id'=>'','total_paid_interest'=>'','payment_terms'=>'1','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'1','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');
        $data = (object)$this->data;
        $arrgetTransactions = array();  $data->cashier_or_date = date('Y-m-d');
        $paymentdata = $this->_OnlinePaymentHistory->getEditDetailsphistory($request->input('pid'));
        //echo "<pre>"; print_r($paymentdata); exit;
        $data->total_paid_amount = $paymentdata->total_paid_amount;  $data->id = 1;
        $clientname = $this->_commonmodel->getClientName($paymentdata->client_id);
        $topnumber = $this->_commonmodel->gettopnumber($paymentdata->transaction_no);
        $data->clientname = $clientname->full_name; 
        $data->pid = $request->input('pid');
        foreach ($this->_commonmodel->getTopnumberarray($topnumber->id) as $val) {
         $arrTransactionNum[$val->id]=$val->transaction_no;
          
        }
        return view('onlinePaymentHistory.bploview',compact('data','arrTransactionNum'));
    }

    public function getList(Request $request){
        //$this->_OnlinePaymentHistory->sensCashierSMS(597, 0);
        $data=$this->_OnlinePaymentHistory->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['department_id']=config('constants.arrApplicableDept')[$row->department_id];
            $arr[$i]['full_name']=$row->full_name;
            $arr[$i]['bill_year']=$row->bill_year;
            $arr[$i]['bill_month']=$row->bill_month;
            $arr[$i]['total_amount']=number_format($row->total_amount, 2);
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount, 2);
            $arr[$i]['transaction_no']=str_pad($row->transaction_no, 6, '0', STR_PAD_LEFT);;
            $arr[$i]['payment_date']=Carbon::parse($row->payment_date)->format('d/m/Y');
			$paymentstatus =config('constants.bploPaymentStatus');
			if($row->payment_status > 0 ){
				$arr[$i]['payment_status']='<button type="button" class="btn btn-success">'.$paymentstatus[$row->payment_status].'</button>';
			}elseif($row->payment_status == '0'){
				$arr[$i]['payment_status']='<button type="button" class="btn btn-danger">'.$paymentstatus[$row->payment_status].'</button>';
			}else{
				$arr[$i]['payment_status']='<button type="button" class="btn btn-danger">'.$row->payment_status.'</button>';
			}	

            $action = "";
            if($row->department_id == '5'){
                $action .='<div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-payment-history/showcpdo?pid='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Show Payment Info">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>';
            }	
            if($row->department_id == '3'){
                $action .='<div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-payment-history/showeng?pid='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Show Payment Info">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>';
            }  
            if($row->department_id == '4'){
                $action .='<div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-payment-history/showoccu?pid='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Show Payment Info">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>';
            }    
            if($row->department_id ==1){
                 $action .='<div class="action-btn bg-success ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-payment-history/approveView?pid='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Show Payment Info">
                        <i class="ti-eye text-white"></i></a>
                    </div>';
            }
            if($row->department_id ==2){
                 $action .='<div class="action-btn bg-success ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-payment-history/showrealproperty?pid='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Show Payment Info">
                        <i class="ti-eye text-white"></i></a>
                    </div>';
            }	
            $arr[$i]['action']=$action;
                   
            $i++;
        }

        // <div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center declined ti ti-close text-white"  name="declined" value="3" id='.$row->id.'
                    // </div>
                    // <div class="action-btn bg-success ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center approved  ti ti-pencil text-white"  name="approve" value="1" id='.$row->id.'</div>
        
        $totalRecords=$data['data_cnt'];
        //dd($totalRecords);
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    public function approve(Request $request){
        // dd($request->input());
         $id = $request->input('id');
        $this->_OnlinePaymentHistory->approve($request);
        // Log Details Start
        $action = "Approved";
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'Online Payment History ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
        return redirect()->route('onlinePaymentHistory.index')->with('success', __('Data updated successfully'));
    }

    public function decline(Request $request){
        $id = $request->input('id');
        $this->_OnlinePaymentHistory->decline($id);
        // Log Details Start
        $action = "Declined";
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'Online Payment History ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    
       
    public function approveView(Request $request){
        $remortServer = DB::connection('remort_server');
        $payment_history = $remortServer->table('payment_history')->where('id',$request->input('pid'))->first();
        if($payment_history->department_id == 1){
            return $this->BusinessPermitView($payment_history);
        }
	}

    public function BusinessPermitView($payment_history){
        $top_transaction_id = DB::table('cto_top_transactions')->where('transaction_no',$payment_history->transaction_no)->pluck('id')->first();
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
            $arrCheque[$i][$key]=$val;
            $arrBankDtls[$i][$key]=$val;
        }
        foreach ($this->_CashierBusinessPermit->getFundCode() as $val) {
            $arrFund[$val->id]=$val->code;
        } 
        foreach ($this->_CashierBusinessPermit->getBankList() as $val) {
            $arrBank[$val->id]=$val->bank_code;
        }
        // foreach ($this->_CashierBusinessPermit->getTransactions(null) as $val) {
        //     $arrTransactionNum[$val->id]=$val->transaction_no;
        // } 
        foreach ($this->_commonmodel->getTopnumberarray($payment_history->transaction_no) as $val) {
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
        $data->top_transaction_id=$top_transaction_id;
        $data->payment_history_id=$payment_history->id;
        $data->cashier_or_date = date('Y-m-d');
        $payment_status=$payment_history->payment_status;
        //dd($arrTransactionNum);
        
        return view('onlinePaymentHistory.BusinessPermitView',compact('data','arrTransactionNum','arrFund','payment_status','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned'));
    }
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrht_description'=>'required|unique:hr_holiday_types,hrht_description,'.(int)$request->input('id'),
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
}
