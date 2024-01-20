<?php

namespace App\Http\Controllers\EconAndInvestment;
use App\Http\Controllers\Controller;
use App\Models\EconAndInvestment\CemeteryCashering;
use Illuminate\Support\Facades\Auth;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use PDF;
use Carbon\Carbon;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class CemeteryCasheringController extends Controller
{
     public $data = [];
    public $dataDtls = [];
    private $slugs;
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->_CemeteryCashering = new CemeteryCashering(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','cashier_or_date'=>date("Y-m-d"),'client_citizen_id'=>'','or_no'=>'','total_amount'=>'0','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','total_paid_interest'=>'','payment_terms'=>'1','payee_type'=>'2','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'','cashier_remarks'=>'','cashier_particulars'=>'');

        $this->dataDtls = array('client_citizen_id'=>'','payee_type'=>'');
        $this->slugs = 'cemetery-cashering';
        $getortype = $this->_CemeteryCashering->GetOrtypeid('10');
                $this->ortype_id =  $getortype->ortype_id; 
    }
    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        return view('econandinvcashering.index');
    }
    public function getRefreshCitizen(Request $request){
       $getgroups = $this->_CemeteryCashering->getCitizens();
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
        $ecaid = $request->input('ecaid');
        $typeid = $request->input('typeid');
        if($typeid =='47'){
            $defaultFeesarr = $this->_CemeteryCashering->GetReqiestfeesdefault($ecaid);
        }
        if($typeid =='48'){
            $defaultFeesarr = $this->_CemeteryCashering->GetReqiestfeesdefaultrental($ecaid);
        }
        if($typeid =='49'){
            $defaultFeesarr = $this->_CemeteryCashering->GetReqiestfeesdefaulthousing($ecaid);
        }
        
        $html ="";
        foreach ($defaultFeesarr as $key => $val) {
                    if($val->total_amount > 0 ){  $disabledclass = "";
                          if($val->remaining_amount > 0){
                              $val->total_amount = $val->remaining_amount;
                            }
                            if($val->total_amount != $val->remaining_amount){
                                    $disabledclass ="disabled-field";
                            }
                              $html .='<div class="row removeNatureData">';
                                   $html .='<div class="col-lg-7 col-md-5 col-sm-7">
                                           <div class="form-group">
                                                 <div class="form-icon-user hidden">
                                                     <input class="form-control"  id="year" name="tfoc_id[]" type="text" value="'.$val->tfoc_id.'" fdprocessedid="3w2mkr">
                                                  </div>
                                                 <div class="form-icon-user">
                                                 <input class="form-control disabled-field"  id="desc1" name="desc[]" type="text" value="'.$val->tfoc_name.'" fdprocessedid="3w2mkr">
                                                 </div>
                                            </div>     
                                       </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                             <input class="form-control ctc_taxable_amount disabled-field" id="ctc_taxable_amount" name="ctc_taxable_amount[]" type="text" value="" fdprocessedid="elxr4w">
                                           </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">';
                                                $html .='<input class="form-control amount " id="tfc_amount"  name="tfc_amount[]" type="text" value="'.$val->total_amount.'" fdprocessedid="nh806j">';
                                            $html .='</div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                        </div>
                               </div>';
                           }
        }
        echo $html;
    } 

    public function getbillingdetails(Request $request){
         $ecaid = $request->input('ecaid'); $dynamichtml ="";
         $html ='<div class="row field-requirement-details-status">
                <div class="col-lg-1">
                    <label for="month" class="form-label" style="color:#fff;padding-top: 10px;">Month</label>
                </div>
                <div class="col-lg-2">
                    <label for="duedate" class="form-label" style="color:#fff;padding-top: 10px;">DueDate</label>
                </div>
                <div class="col-lg-1">
                    <label for="orno" class="form-label numeric" style="color:#fff;padding-top: 10px;">Or No</label>
                </div>
                <div class="col-lg-2">
                    <label for="dueamount" class="form-label numeric" style="color:#fff;padding-top: 10px;">Due</label>
                </div>
                <div class="col-lg-1">
                    <label for="payment" class="form-label" style="color:#fff;padding-top: 10px;">Payment</label>
                </div>
                 <div class="col-lg-2">
                    <label for="Balance" class="form-label" style="color:#fff;padding-top: 10px;">Balance</label>
                </div>
                <div class="col-lg-2">
                    <label for="status" class="form-label" style="color:#fff;padding-top: 10px;">Status</label>
                </div>
                 <div class="col-lg-1">
                    <input class="select_all" name="select_all" id_name="cemetery-open" value="1"  type="checkbox" style="z-index: 999;" >
                </div>
                </div>';
                $typeid = $request->input('typeid');
                if($typeid =='47'){
                   $getdata = $this->_CemeteryCashering->getMonthlyinstallment($ecaid);
                }
                if($typeid =='48'){
                   $getdata = $this->_CemeteryCashering->getMonthlyinstallmentrental($ecaid);
                }
                if($typeid =='49'){
                   $getdata = $this->_CemeteryCashering->getMonthlyinstallmenthousing($ecaid);
                }
                if(count($getdata) > 0){ $i=1; $dynamichtml.= $html;  $Total =0; $paiamount = 0; $remainingamount =0 ;
                foreach ($getdata as $key => $value) {  $status="Unpaid";
                $checked = ($value->is_paid ==2) ?'checked':'';  
                if($value->is_paid ==1){ $status="Partial";} if($value->is_paid ==2){ $status="Paid";}
                $dynamichtml.='<div class="row">';
                if($checked){
                $dynamichtml.='<div class="col-lg-1">
                    <label for="month" class="form-label" style="padding-top: 10px;">'.$i.'</label><input type="hidden" name="receivableidpais" value="'.$value->id.'"></div>';
                }else{
                   $dynamichtml.='<div class="col-lg-1">
                    <label for="month" class="form-label" style="padding-top: 10px;">'.$i.'</label><input type="hidden" name="receivableids[]" value="'.$value->id.'"></div>'; 
                }
                $dynamichtml.='<div class="col-lg-2">
                    <label for="duedate" class="form-label" style="padding-top: 10px;">'.$value->due_date.'</label>
                </div>
                <div class="col-lg-1">
                    <label for="orno" class="form-label numeric" style="padding-top: 10px;">'.$value->or_no.'</label>
                </div>
                <div class="col-lg-2">
                <input class="form-control disabled-field"  id="dueamount'.$value->id.'" name="dueamount'.$value->id.'" type="text" value="'.$value->amount_due.'" fdprocessedid="3w2mkr">
                </div>
                <div class="col-lg-1">
                    <label for="payment" class="form-label" style="padding-top: 10px;">'.$value->amount_pay.'</label>
                </div>
                 <div class="col-lg-2">
                    <label for="Balance" class="form-label" style="padding-top: 10px;">'.$value->remaining_amount.'</label>
                </div>
                <div class="col-lg-2">
                    <label for="status" class="form-label" style="padding-top: 10px;">'.$status.'</label>
                </div>
                <div class="col-lg-1">';
                if($checked){
                $dynamichtml .='<input class="form-check disabled-field"  idval="'.$value->id.'" name="checkboxpaid'.$value->id.'"  type="checkbox" value="1" disabled fdprocessedid="3w2mkr">';
                }else{
                    $dynamichtml .='<input class="form-check  linecheckbox"  idval="'.$value->id.'" name="checkbox'.$value->id.'" checked="'.$checked.'" type="checkbox" value="1" fdprocessedid="3w2mkr">';
                }
                $dynamichtml.='</div>
                </div>';  $i++;  $Total = $Total + $value->amount_due;  $paiamount = $paiamount + $value->amount_pay;
                 }
                 $remainingamount = $Total - $paiamount;
                 $dynamichtml .='<div class="row"><div class="col-lg-1"></div><div class="col-lg-2">
                 </div><div class="col-lg-1">
                    <label for="orno" class="form-label numeric" style="padding-top: 10px;">TOTAL</label>
                </div><div class="col-lg-2">
                <input class="form-control disabled-field"  type="text" name="monthtotalamount" value="'.$Total.'" fdprocessedid="3w2mkr">
                </div> <div class="col-lg-1">
                    <label for="payment" class="form-label" style="padding-top: 10px;">'.$paiamount.'</label>
                </div>
                 <div class="col-lg-2">
                    <label for="Balance" class="form-label" style="padding-top: 10px;">'.$remainingamount.'</label>
                </div><div class="col-lg-2"></div><div class="col-lg-1"></div></div';
                }
               echo $dynamichtml;

    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CemeteryCashering->getList($request);
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
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            if($row->payee_type==1){
                $arr[$i]['completeaddress']= $this->_commonmodel->getTaxPayerAddress($row->client_citizen_id);
                //$arr[$i]['taxpayername']= $this->_commonmodel->getUserName($row->rpo_first_name,$row->rpo_middle_name,$row->rpo_custom_last_name,$row->suffix);
                $arr[$i]['taxpayername']= $row->cit_fullname;
            }else{
                $arr[$i]['completeaddress']= $this->_commonmodel->getCitizenAddress($row->client_citizen_id);
                $arr[$i]['taxpayername']= $row->cit_fullname;
            }
            $address = wordwrap($arr[$i]['completeaddress'], 15, "<br>\n");
            $arr[$i]['completeaddress']= '<span class="showLess">'.$address.'</span>';
            $arr[$i]['cashier_particulars']= $row->cashier_particulars; 
            if($row->top_transaction_id > 0){
              $topdata = $this->_CemeteryCashering->GetToptransaction($row->top_transaction_id);
               $toptno =  $topdata->transaction_no;
            } 
            $arr[$i]['topno']= $toptno;
            $arr[$i]['payee_type']=config('constants.arrPayeeType')[(int)$row->payee_type];
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['status']=$arrStatus[$row->status];
            $arr[$i]['cashier']=$row->fullname;
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('cemetery-cashering/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Econ & Investment Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="'.url('cemetery-cashering/printReceipt?id='.$row->id).'" target="_blank" title="Print Cemetary Cashering"  data-title="Print Cemetary Cashering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
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
        $ctcdata = $this->_CemeteryCashering->getCertificateDetails($id);
        $cashiername = $this->_commonmodel->getemployeefullname($ctcdata->created_by);
        $getappid =  $this->_CemeteryCashering->getappidbytoptransaction($ctcdata->top_transaction_id);
        $defaultFeesarr = $this->_CemeteryCashering->GetReqiestfees($getappid->transaction_ref_no);
        if ($getappid->tbl_ref === 'eco_housing_application') {
            $defaultFeesarr = $this->_CemeteryCashering->GetReqiestHousingfees($getappid->transaction_ref_no);
        }
        if($getappid->tbl_ref === 'eco_event_application'){
           $defaultFeesarr = $this->_CemeteryCashering->GetReqiestRentalfees($getappid->transaction_ref_no); 
        }
        switch ($ctcdata->payment_terms) {
            case 2:
                $arrPaymentbankDetails = $this->_engineeringcashering->GetPaymentbankdetails($id);
                break;

            case 3:
                $arrPaymentbankDetails = $this->_engineeringcashering->GetPaymentcheckdetails($id);
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
            'payor' => $ctcdata->cit_fullname,
            'transactions' => $defaultFeesarr,
            'total' => $ctcdata->total_amount,
            'payment_terms' => $ctcdata->payment_terms,
            'cash_details' => $arrPaymentbankDetails,
            'surcharge' => $ctcdata->total_paid_surcharge,
            'interest' => $ctcdata->total_paid_interest,
            'cashierid' => $ctcdata->created_by,
            'cashiername' => $cashiername->fullname,
            'varName'=> 'cashier_investment_collecting_officer',
        ];
        //return $this->printReceiptcemetary($data);
         return $this->_commonmodel->printReceiptoccu($data,$this->ortype_id);
    }

    public function printReceiptcemetary($data)
    {
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'DL');
        PDF::SetFont('Helvetica', '', 10);

        $border = 0;
        $topPos = 36;
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::writeHTMLCell(50, 0, 60,$topPos, $data['or_number'], $border);//Or number

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(50, 0, 45,6 + $topPos,Carbon::parse($data['date'])->toFormattedDateString(), $border);//Date

        PDF::writeHTMLCell(50, 0, 20,15  + $topPos,config('constants.defaultCityCode.city'), $border);//agency

        PDF::writeHTMLCell(50, 0, 20,22 + $topPos,$data['payor'], $border);//Payor
        
        $htmldynahistory='<table border="'.$border.'">
                            <tr>
                                <td width="150px"></td>
                                <td width="40px"></td>
                                <td width="83px"></td>
                            </tr>
        ';
        foreach ($data['transactions'] as $key => $value) {
            if ($value->tax_amount != 0) {
                $htmldynahistory .='<tr>
                        <td style="text-align:left;">
                        '.$value->fees_description.'
                        </td>
                        <td></td>
                        <td style="text-align:left;">'.number_format($data['total'],2).'</td>
                    </tr>';
            }
        }
        if (isset($data['surcharge']) && $data['surcharge']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Surcharge Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['surcharge'],2).'</td>
            </tr>
            ';
        }
        if (isset($data['interest']) && $data['interest']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Interest Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['interest'],2).'</td>
            </tr>
            ';
        }
        $htmldynahistory .='</table>';
        PDF::writeHTMLCell(90, 0, 6,35 + $topPos,$htmldynahistory, $border); //collection table

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(35, 0, 73,84 + $topPos,number_format($data['total'],2), $border); //total

        $amountinworld =  $this->_commonmodel->numberToWord($data['total']); 
        PDF::writeHTMLCell(55, 0, 33,92 + $topPos,$amountinworld, $border);//amount in words

        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, 8,105 + $topPos,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, 8,111 + $topPos,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, 8,117 + $topPos,$order, $border);// check money order
        
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
        PDF::writeHTMLCell(65, 0, 31,109 + $topPos,$htmldynahistory, $border);// bank

        PDF::writeHTMLCell(61, 0, 24,133 + $topPos,Auth::user()->hr_employee->fullname, $border,0,0,true,'C'); //collecting office

        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['transacion_no'].'.pdf';
        //echo $filename;exit;
        PDF::Output($folder.$filename,'F');
        
        // Apply Digital Signature
        $arrData['filename'] = $filename;
        $arrData['signerXyPage'] = '320,200,100,140,1';
        $arrData['signaturePath'] = \Auth::user()->e_signature;
        return $this->_commonmodel->applyDigitalSignature($arrData);

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
            foreach ($this->_CemeteryCashering->getFundCode() as $val) {
                $arrFund[$val->id]=$val->code;
            } 
            foreach ($this->_CemeteryCashering->getBankList() as $val) {
                $arrBank[$val->id]=$val->bank_code;
            }
            foreach ($this->_CemeteryCashering->getTransactions($request->input('id')) as $val) {
                $arrTransactionNum[$val->id]=$val->transaction_no;
            } 
            foreach ($this->_CemeteryCashering->getCancelReason() as $val) {
                $arrCancelReason[$val->id]=$val->ocr_reason;
            }
            foreach ($this->_CemeteryCashering->getChequeTypes() as $val) {
                $arrChequeTypes[$val->id]=$val->ctm_description;
            }
            foreach ($this->_CemeteryCashering->getCitizens() as $val) {
                $arrUser[$val->id]=$val->cit_fullname;
            }
            
            $isOrAssigned = (int)$this->_CemeteryCashering->checkORAssignedORNot();
            $arrNature=array();
            $arrDocumentDetailsHtml='';
        }
        
        $data = (object)$this->data;
        $data->top_transaction_id ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CemeteryCashering->getEditDetails($request->input('id'));
            if(isset($data)){
                if($data->payee_type==1){
                    foreach ($this->_CemeteryCashering->getTaxpayers($data->client_citizen_id) as $val) {
                        $arrUser[$val->id]=$val->full_name;
                    }
                }else{
                    foreach ($this->_CemeteryCashering->getCitizens($data->client_citizen_id) as $val) {
                        $arrUser[$val->id]=$val->cit_fullname;
                    }
                }
                 foreach ($this->_CemeteryCashering->getservice() as $val) {
                    $arrTfocFees[$val->tfoc_id]=$val->tfoc_name;
                }
                $data->created_at = date("d/m/Y",strtotime($data->created_at));
                $data->cashier_or_date = date("d/m/Y",strtotime($data->cashier_or_date));
                $arrFeesDtls = $this->_CemeteryCashering->getNatureFeeDetails($data->id);
                if(count($arrFeesDtls)>0){
                    $arrNature = json_decode(json_encode($arrFeesDtls), true);
                }
            }
            $arrPaymentDetails = $this->_CemeteryCashering->getPaymentModeDetails($request->input('id'),3);
            $arrCheque = json_decode(json_encode($arrPaymentDetails), true);

            $arrPaymentDetails = $this->_CemeteryCashering->getPaymentModeDetails($request->input('id'),2);
            $arrBankDtls = json_decode(json_encode($arrPaymentDetails), true);
            
        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            foreach((array)$this->dataDtls as $key=>$val){
                $this->dataDtls[$key] = $request->input($key);
            }
            $getortype = $this->_CemeteryCashering->GetOrtypeid('10');
            $this->data['ortype_id'] =  $getortype->ortype_id;
            //$this->data['ortype_id'] =  2; // Accountable Form No. 51-C
            $clientdata = $this->_commonmodel->getCitizenName($this->data['client_citizen_id']);
            $taxpayername = $clientdata->cit_fullname;
            
            $this->data['taxpayers_name'] = $taxpayername;
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='10';
            $this->data['net_tax_due_amount'] = $this->data['total_amount'];
            $this->data['payee_type'] = '2';
            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
          
            if($request->input('id')>0){
                unset($this->data['created_at']);
                unset($this->data['cashier_batch_no']);
                unset($this->data['cashier_or_date']);
                $this->_CemeteryCashering->updateData($request->input('id'),$this->data);
                $success_msg = 'Cashering updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = '1'; 
                $this->data['payment_type'] = 'Walk-In';
                $this->data['cashier_or_date'] = date("Y-m-d");
                $this->data['cashier_year'] = date('Y');
                $this->data['cashier_month'] = date('m');
                $this->data['net_tax_due_amount'] = $this->data['total_amount'];
                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->data['or_no']);
                if($getorRegister != Null){
                     $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_CemeteryCashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no;
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_CemeteryCashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_CemeteryCashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }     
                }

                $this->data['cashier_issue_no'] = $issueNumber; 
                $this->data['cashier_batch_no'] = $cashier_batch_no; 
                if(!empty($request->input('client_topno'))){
                   $this->data['top_transaction_id'] = $request->input('client_topno'); 
                }
                $checkcasheringexist = $this->_CemeteryCashering->checkcasheringexist($this->data['top_transaction_id']);
                $lastinsertid = $this->_CemeteryCashering->addData($this->data);
                $balance = $_POST['final_totalamt'] - $this->data['total_paid_amount'];
                $pstatus = 0;  $appstatus = "";
                if($this->data['total_paid_amount'] >= $_POST['final_totalamt']){
                      $appstatus ='completed'; 
                }else{  $appstatus ='partial';}

                if($this->data['total_paid_amount'] >= $_POST['finaltotalamt']){
                    $pstatus = 2;   
                }else{ $pstatus = 1;  }
                $updateapdata = array();
                $updateapdata['or_no'] = $this->data['or_no'];
                $updateapdata['or_date'] = date("Y-m-d");
                $updateapdata['remaining_amount'] = $balance;
                $updateapdata['status'] = $appstatus;
                $getappid =  $this->_CemeteryCashering->getappidbytoptransaction($this->data['top_transaction_id']);
                if($request->input('transaction_typeid') == '47'){
                    if(count($checkcasheringexist) == 0){
                        $updateapdata['downpayment'] = $this->data['total_paid_amount'];
                    }
                    $this->_CemeteryCashering->updateappdata($getappid->transaction_ref_no,$updateapdata);
                }
                if($request->input('transaction_typeid') == '48'){
                     $this->_CemeteryCashering->updateappdatarental($getappid->transaction_ref_no,$updateapdata);
                }
                if($request->input('transaction_typeid') == '49'){
                    if(count($checkcasheringexist) == 0){
                        $updateapdata['initial_monthly'] = $this->data['total_paid_amount'];
                    }
                    unset($updateapdata['status']);
                    $this->_CemeteryCashering->updateappdatahousing($getappid->transaction_ref_no,$updateapdata);
                }
                //$this->_CemeteryCashering->updateapppaymentdata($getappid->transaction_ref_no,$updateapdata);

                // $apppaymentdata = array();
                // $apppaymentdata['cemetery_application_id'] = $getappid->transaction_ref_no;
                // $apppaymentdata['or_no'] = $this->data['or_no'];
                // $apppaymentdata['or_date'] = date("Y-m-d");
                // $apppaymentdata['citizen_id'] = $this->data['client_citizen_id'];
                // $apppaymentdata['total_amount'] = $_POST['final_totalamt'];
                // $apppaymentdata['paid_amount'] = $this->data['total_paid_amount'];
                // $apppaymentdata['status'] = $pstatus; 
                // $apppaymentdata['remaining_balance'] = $balance;
                // $this->_CemeteryCashering->addDataPayment($apppaymentdata);
                // update latest used OR
                if($request->input('isuserrange')){
                    $arrOrData = array('latestusedor' => $this->data['or_no']);
                    $ortype_id=$getortype->ortype_id; // Accountable Form No. 51-C
                    $this->_CemeteryCashering->updateOrUsed($ortype_id,$arrOrData);
                }
                $this->dataDtls['cashier_id'] = $lastinsertid;
                $this->dataDtls['cashier_issue_no'] =$issueNumber;
                $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
                $success_msg = 'Cashering Health And safety added successfully.';
                Session::put('CEMETERY_PRINT_CASHIER_ID',$lastinsertid);
            }
            
            $Cashierid = $lastinsertid;
            $arrDetails = $request->input('tfoc_id');
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    $arrTfoc = $this->_CemeteryCashering->getTfocDtls($value);
                    $this->dataDtls['tfoc_id'] =$value;
                    $this->dataDtls['payee_type'] = '2';
                    $this->dataDtls['tfc_amount'] = $request->input('tfc_amount')[$key];
                    $this->dataDtls['all_total_amount'] = $request->input('tfc_amount')[$key];
                    if(!empty($request->input('ctc_taxable_amount')[$key])){
                    $this->dataDtls['ctc_taxable_amount'] = $request->input('ctc_taxable_amount')[$key];
                    }
                    $this->dataDtls['top_transaction_id'] = $this->data['top_transaction_id'];
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
                    $this->dataDtls['cemetery_application_id'] = $getappid->transaction_ref_no;
                    $this->dataDtls['cem_total_amount'] = $_POST['final_totalamt'];
                    $this->dataDtls['cem_paid_amount'] = $this->data['total_paid_amount'];
                    $this->dataDtls['cem_status'] = $pstatus; 
                    $this->dataDtls['cem_remaining_balance'] = $balance;
                    
                    $checkdetailexist =  $this->_CemeteryCashering->checkRecordIsExist($value,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_CemeteryCashering->updateCashierDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $this->dataDtls['created_by'] =\Auth::user()->id;
                        $this->dataDtls['created_at'] = date('Y-m-d H:i:s');
                        $this->dataDtls['cashier_year'] = date('Y');
                        $this->dataDtls['cashier_month'] = date('m');
                        $cashierdetailid = $this->_CemeteryCashering->addCashierDetailsData($this->dataDtls);
                        $this->_commonmodel->insertCashReceipt($value, $request->total_paid_amount, $request->or_no,$request->cashier_particulars);

                        $addincomedata = array();
                        $addincomedata['cashier_id'] = $lastinsertid;
                        $addincomedata['cashier_details_id'] = $cashierdetailid;
                        $addincomedata['tfoc_is_applicable'] = '10';
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
            
            if(isset($_POST['receivableids'])){
               if(count($_POST['receivableids']) > 0){ $i=1; $totalinstallamt = 0;
                    foreach ($_POST['receivableids'] as $keyr => $valr) {
                        $updatecrdata = array();
                        $updatecrdata['or_no'] = $this->data['or_no'];
                        $updatecrdata['or_date'] = date("Y-m-d");
                        $updatecrdata['is_paid'] = '2';
                        if(isset($_POST['checkbox'.$valr])){
                           if($i == 1) { $updatecrdata['amount_pay'] = $_POST['dueamount'.$valr];
                                if($this->data['total_paid_amount'] > $_POST['dueamount'.$valr]){
                                    $updatecrdata['remaining_amount'] = 0;
                                }else{
                                  $updatecrdata['remaining_amount']= $_POST['dueamount'.$valr] - $this->data['total_paid_amount'];
                                  $updatecrdata['is_paid'] = '2';
                                  $updatecrdata['amount_pay'] = $this->data['total_paid_amount'];
                                }
                            }else{
                                $updatecrdata['amount_pay'] = $_POST['dueamount'.$valr];
                                $updatecrdata['remaining_amount'] = 0;
                            }
                            $crid = $valr; $i++;
                            $totalinstallamt = $totalinstallamt + $_POST['dueamount'.$valr];
                            $updatecrdata['updated_by'] = \Auth::user()->id;
                            $updatecrdata['updated_at']= date('Y-m-d H:i:s');
                            $this->_CemeteryCashering->updatereceivabledata($crid,$updatecrdata); 
                            $lastmonthid = $valr;  $installmentamount = $_POST['dueamount'.$valr]; 
                        }
                    }
                    if($i > 1){
                        $this->data['total_paid_amount']."##".$totalinstallamt;
                        if($this->data['total_paid_amount'] > $totalinstallamt){
                            $extraamt= $this->data['total_paid_amount'] - $totalinstallamt;
                            $updatecrdata = array();
                            $updatecrdata['remaining_amount'] = $installmentamount - $extraamt;
                            $updatecrdata['amount_pay'] = $extraamt;
                            $updatecrdata['or_no'] = $this->data['or_no'];
                            $updatecrdata['is_paid'] = '1';
                            $updatecrdata['updated_by'] = \Auth::user()->id;
                            $updatecrdata['updated_at']= date('Y-m-d H:i:s');
                            $nextmontid = $lastmonthid + 1;
                            $this->_CemeteryCashering->updatereceivabledata($nextmontid,$updatecrdata);
                        } 
                    }
                }
            } 
            $smsTemplate=SmsTemplate::where('id',69)->where('is_active',1)->first(); 
            $arrData = $this->_CemeteryCashering->getappdatacitizen($this->data['client_citizen_id']);
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
            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Econ And Investment Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            if($this->data['total_paid_amount'] >= $_POST['final_totalamt']){
            $arrData=array();  $top_transaction_id = $request->input('client_topno');
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            if($request->input('transaction_typeid') == '47'){
             $this->_CemeteryCashering->updateTopTransaction($top_transaction_id,$arrData);
              }
            }
            // Log Details End 
            return redirect()->route('cemeterycashering.index')->with('success', __($success_msg));
        }
        return view('econandinvcashering.create',compact('data','arrTransactionNum','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned','arrUser','arrNature','arrTfocFees','arrDocumentDetailsHtml'));
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
 
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_CemeteryCashering->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function checkOrUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $id = $cashierId = $request->input('cashier_id');
        $isUsed = $this->_CemeteryCashering->checkOrUsedOrNot($or_no,$id);
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
        $this->_CemeteryCashering->updateData($id,$updataarray);
        $uptdetlarray = array('cem_status'=>'3');
        $this->_CemeteryCashering->updateDetailData($id,$uptdetlarray);
        if(!empty($request->input('toptno'))){
            $getappid =  $this->_CemeteryCashering->getappidbytoptransaction($request->input('toptno'));
            $remainingdata = $this->_CemeteryCashering->getRemainingAmount($getappid->transaction_ref_no);
            $remainingamout = $remainingdata->remaining_amount + $request->input('paidamountcancel');
            $updateapdata = array('remaining_amount'=>$remainingamout);
            $this->_CemeteryCashering->updateappdata($getappid->transaction_ref_no,$updateapdata);
            $arrData=array();  $top_transaction_id = $request->input('toptno');
            $arrData['is_paid']=0;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $this->_CemeteryCashering->updateTopTransaction($top_transaction_id,$arrData);
        }
        if(!empty($request->input('cancelorno'))){
            $cancelorno = $request->input('cancelorno');
            $getrecordbyorno =$this->_CemeteryCashering->getrecordbyorno($cancelorno); 
            foreach ($getrecordbyorno as $keyrec => $valuerec) {
                $updatearray = array('amount_pay'=>'0','remaining_amount'=>$valuerec->amount_due,'is_paid'=>'0','or_date'=>NULL,'or_no'=>NULL);
                $this->_CemeteryCashering->updateCtoreceivable($valuerec->id,$updatearray);
            }
        }
        $this->_commonmodel->deletecashierincome($id);
        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Econ And Investment Cashering O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
        $data=array('status'=>'success',"message"=>"");
        echo json_encode($data);
        //return redirect()->route('cemeterycashering.index')->with('success', __('O.R Cancelled Successfully.'));
    }
    public function cancelNaturePaymentOption(Request $request){
        $f_id =  $request->input('f_id');
        $this->_CemeteryCashering->deleteCashieringDetails($f_id);
        $arr['ESTATUS']=0;
        $arr['message']="Deleted Successfully";
        echo json_encode($arr);exit;
    
    }
    public function getOrnumber(Request $request){ 
        $checkflag = $request->input('orflag');
        $orNumber=1;
        $ortype_id=$this->ortype_id;  // Accountable Form No. 51-C
        if($checkflag == '1'){
            $getorno = $this->_CemeteryCashering->getLatestOrNumber($ortype_id);
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
            foreach ($this->_CemeteryCashering->getTaxpayers() as $val) {
                $name = $this->_commonmodel->getUserName($val->rpo_first_name,$val->rpo_middle_name,$val->rpo_custom_last_name,$val->suffix);
                $options .="<option value=".$val->id.">".$name."</option>";
            }
        }else{
            foreach ($this->_CemeteryCashering->getCitizens() as $val) {
                $name = $this->_commonmodel->getUserName($val->cit_first_name,$val->cit_middle_name,$val->cit_last_name,$val->cit_suffix_name);
                $options .="<option value=".$val->id.">".$name."</option>";
            }
        }
        $arr['ESTATUS']=0;
        $arr['option']=$options;
        echo json_encode($arr);exit;
    }

    public function getUserbytoid(Request $request){
        $payee_type = 2;
        $topid = $request->input('topid'); $perticulars = "";
        $transactiontypeid = $this->_CemeteryCashering->getappidbytoptransaction($topid);
        if($transactiontypeid->top_transaction_type_id =='47'){
             $arrDtls = $this->_CemeteryCashering->getUserDetailsbytopid($topid);
             $perticulars ="Cemetery Application";
        }
        if($transactiontypeid->top_transaction_type_id =='48'){
             $arrDtls = $this->_CemeteryCashering->getUserDetailsbytopidrental($topid);
             $perticulars ="Rental Application";
        }

        if($transactiontypeid->top_transaction_type_id =='49'){
             $arrDtls = $this->_CemeteryCashering->getUserDetailsbytopidhousing($topid);
             $perticulars ="Housing Application";
        }
       
        //$checkallpaidamount = $this->_CemeteryCashering->getallpaidamount($arrDtls->ecaid);
        //print_r($checkallpaidamount); exit;
        
        $arr['ESTATUS']=1;
        if(isset($arrDtls)){
            $finaltotalamt = $arrDtls->total_amount;
            $arr['ESTATUS']=0;
                $arr['address']= $this->_commonmodel->getCitizenAddress($arrDtls->requestor_id);
                $arr['name']= $this->_commonmodel->getUserName($arrDtls->cit_first_name,$arrDtls->cit_middle_name,$arrDtls->cit_last_name,$arrDtls->cit_suffix_name);
                $arr['requestor_id'] = $arrDtls->requestor_id;
                $arr['ecaid']=$arrDtls->ecaid;
                $arr['amount']= $arrDtls->remaining_amount;
                $arr['finaltotalamt']= $finaltotalamt;
                $arr['perticulars']= $perticulars;
                $arr['typeid'] = $transactiontypeid->top_transaction_type_id;
        }
        echo json_encode($arr);exit;
    }

    public function getAmountDetails(Request $request){
        $tfoc_id = $request->input('tfoc_id');
        $arrFee = $this->_CemeteryCashering->getTaxFeesDetails($tfoc_id);
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
        $arrDtls = $this->_CemeteryCashering->getUserDetails($payee_type,$user_id);
        $arr['ESTATUS']=1;
        if(isset($arrDtls)){
            $arr['ESTATUS']=0;
                $arr['address']= $this->_commonmodel->getCitizenAddress($user_id);
                $arr['name']= $this->_commonmodel->getUserName($arrDtls->cit_first_name,$arrDtls->cit_middle_name,$arrDtls->cit_last_name,$arrDtls->cit_suffix_name);
           
        }
        echo json_encode($arr);exit;
    }
}
