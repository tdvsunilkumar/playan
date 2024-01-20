<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use App\Models\Engneering\EngCashering;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Cpdo\CpdoCashering;
use PDF;
use Carbon\Carbon;
use Auth;
use Session; 
use File;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class EngCasheringController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $clientsarr = array(""=>" ");
    public $feesaray = array(""=>"Select fees");
    public $fundarray = array(""=>"Select fund");
    public $bankaray = array(""=>"Select bank");
    public $arrcancelreason = array(""=>"Please Select");
    public $arrgetTransactions = array(""=>"Please Select");
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_engineeringcashering = new EngCashering();
        $this->_commonmodel = new CommonModelmaster();
        $this->_cpdocashering = new CpdoCashering();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','payment_terms'=>'','or_no'=>'','total_amount'=>'','createdat'=>date("d/m/Y"),'total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Engineering Fee','ctc_place_of_issuance'=>'');
        $this->checkdetail = array('payment_terms' =>'1');
        $this->slugs = 'engcashering'; 
        
        foreach ($this->_engineeringcashering->getEngOwners() as $val) {
            $this->clientsarr[$val->id]=$val->full_name;
        }
         foreach ($this->_engineeringcashering->Gettaxfees() as $val) {
            $this->feesaray[$val->id]=$val->accdesc;
        }
        // foreach ($this->_engineeringcashering->getTransactions() as $val) {
        //     $this->arrgetTransactions[$val->id]=$val->transaction_no;
        // } 
        foreach ($this->_engineeringcashering->getfundcode() as $val) {
            $this->fundarray[$val->id]=$val->code;
        } 
        foreach ($this->_engineeringcashering->getBankarray() as $val) {
            $this->bankaray[$val->id]=$val->bank_code;
        }
        foreach ($this->_engineeringcashering->getCancelReason() as $val) {
            $this->arrcancelreason[$val->id]=$val->ocr_reason;
        }  
         $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                $this->ortype_id =  $getortype->ortype_id;                                
        
    }
    public function index(Request $request)
    {   $this->is_permitted($this->slugs, 'read'); 
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
		
        return view('Engneering.cashering.index',compact('startdate','enddate'));
    }

    public function getClientsbussiness(Request $request){
    	$id= $request->input('cid'); $businesshtml ="";
    	$businessdata = $this->_engineeringcashering->getBusinessDetails($id);
    	foreach ($businessdata as $key => $value) {
    		$businesshtml .= '<option value='.$value->id.'>'.$value->busn_name.'</option>';
    	}
    	echo $businesshtml;
    }

     public function cancelOrPayment(Request $request){
        $pswVeriStatus = (session()->has('casheringVerifyPsw'))?((session()->get('casheringVerifyPsw') == true)?true:false):false;
       // dd($pswVeriStatus);
        if(!$pswVeriStatus){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
        session()->forget('casheringVerifyPsw');
        $id= $request->input('cashier_id');
        $ocr_id= $request->input('ocr_id');
        $remark= $request->input('remark');
        $top_transaction_id = $request->input('toptno');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'status'=>'0');
        $this->_engineeringcashering->updateData($id,$updataarray);
        if($request->input('paymentterms')=='5'){
          $updatremoteaarray = array('is_approved'=>'0');
          $this->_engineeringcashering->updateremotepaymentData($top_transaction_id,$updatremoteaarray);
        }
        $data=array('status'=>'success',"message"=>"");
        $arrData=array();  
        $arrData['is_paid']=0;
        $arrData['updated_by']=\Auth::user()->id;
        $arrData['updated_at']= date('Y-m-d H:i:s');
        $this->_engineeringcashering->updateTopTransaction($top_transaction_id,$arrData);
        $this->_commonmodel->deletecashierincome($id);
        echo json_encode($data);
    }


    public function getallFeesejredit(Request $request){
            $ejrid = $request->input('ejrid'); $surchargefee= 0;
        $defaultFeesarr = $this->_engineeringcashering->GetReqiestfees($ejrid);
        $getsurchargefee = $this->_engineeringcashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->ejr_surcharge_fee;
        $year = date('Y',strtotime($getsurchargefee->created_at));
        $html ="";
        foreach ($defaultFeesarr as $key => $val) {
			if($val->tax_amount > 0 ){
					  $html .='<div class="row">
							<div class="col-lg-1 col-md-1 col-sm-1">
								<div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year[]" type="text" value="'.$year.'" fdprocessedid="3w2mkr"></div>
								</div>
							</div>';
							
						   $html .='<div class="col-lg-7 col-md-7 col-sm-7"><div class="form-group">';
							   if($val->is_default == '1'){
								  $html .='<div class="form-icon-user hidden">
											 <input class="form-control" readonly="readonly" id="year" name="taxfees[]" type="text" value="'.$val->tfoc_id.'" fdprocessedid="3w2mkr">
										  </div><div class="form-icon-user">
											 <input class="form-control" readonly="readonly" id="year" name="textonly[]" type="text" value="'.$val->fees_description.'" fdprocessedid="3w2mkr">
										  </div>'; 
								} else {
								$html .='<div class="form-icon-user">
										 <input class="form-control" readonly="readonly" id="year" name="desc[]" type="text" value="'.$val->fees_description.'" fdprocessedid="3w2mkr">
									</div>';
								}
								$html .='</div>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2">
								<div class="form-group">
								 <input class="form-control taxableamount" readonly="readonly" id="taxableamount" name="taxableamount[]" type="text" value="" fdprocessedid="elxr4w">
							   </div>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2">
								<div class="form-group">';
								   if($val->is_default =='1'){
									$html .='<input class="form-control amount" id="cpdoamount" readonly="readonly" name="amount[]" type="text" value="'.$val->tax_amount.'" fdprocessedid="nh806j">';
									}else{
										$html .='<input class="form-control amount" id="cpdoamount" readonly="readonly" name="amountnosave[]" type="text" value="'.$val->tax_amount.'" fdprocessedid="nh806j">';
									}
								$html .='</div>
							</div>
						   
					   </div>';
				   }
        }
        if($surchargefee > 0){
            $html.='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year" type="text" value="'.$year.'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div><div class="col-lg-7 col-md-7 col-sm-7"><div class="form-group"><div class="form-icon-user">
                                                 <input class="form-control" readonly="readonly" id="year" name="surchargeshow" type="text" value="Surcharge" fdprocessedid="3w2mkr">
                                            </div></div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         <input class="form-control taxableamount" readonly="readonly" id="taxableamountshow" name="taxableamountshow" type="text" value="" fdprocessedid="elxr4w">
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group"><input class="form-control amount" id="cpdoamountshow" readonly="readonly" name="amountnosaveshow" type="text" value="'.$surchargefee.'" fdprocessedid="nh806j"></div>
                                    </div>
                                    
                               </div>';
        }
        echo $html;
    }

    public function getallFeesejr(Request $request){
        $ejrid = $request->input('ejrid');
        $defaultFeesarr = $this->_engineeringcashering->GetReqiestfees($ejrid);
        $html ="";
        $getsurchargefee = $this->_engineeringcashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->ejr_surcharge_fee;
        //print_r($defaultFeesarr); exit;
        foreach ($defaultFeesarr as $key => $val) {
                    if($val->tax_amount > 0){
                              $html .='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year[]" type="text" value="'.Date('Y').'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div>';
                                    
                                      $html .='<div class="col-lg-7 col-md-7 col-sm-7"><div class="form-group">';
                                       if($val->is_default == '1'){
                                          $html .='<div class="form-icon-user hidden">
                                                     <input class="form-control" readonly="readonly" id="year" name="taxfees[]" type="text" value="'.$val->tfoc_id.'" fdprocessedid="3w2mkr">
                                              </div><div class="form-icon-user">
                                                     <input class="form-control" readonly="readonly" id="year" name="textonly[]" type="text" value="'.$val->fees_description.'" fdprocessedid="3w2mkr">
                                                  </div>'; 
                                        } else {
                                        $html .='<div class="form-icon-user">
                                                 <input class="form-control" readonly="readonly" id="year" name="desc[]" type="text" value="'.$val->fees_description.'" fdprocessedid="3w2mkr">
                                            </div>';
                                        }
                                        $html .='</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         <input class="form-control taxableamount" readonly="readonly" id="taxableamount" name="taxableamount[]" type="text" value="" fdprocessedid="elxr4w">
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">';
                                          if($val->is_default == '1'){
                                            $html .='<input class="form-control amount" id="cpdoamount" readonly="readonly" name="amount[]" type="text" value="'.$val->tax_amount.'" fdprocessedid="nh806j">';
                                            }else{
                                                $html .='<input class="form-control amount" id="cpdoamount" readonly="readonly" name="amountnosave[]" type="text" value="'.$val->tax_amount.'" fdprocessedid="nh806j">';
                                            }
                                        $html .='</div>
                                    </div>
                                    
                               </div>';
                           }
        }
        if($surchargefee > 0){
            $html.='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year" type="text" value="'.Date('Y').'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div><div class="col-lg-7 col-md-7 col-sm-7"><div class="form-group"><div class="form-icon-user">
                                                 <input class="form-control" readonly="readonly" id="year" name="surchargeshow" type="text" value="Surcharge" fdprocessedid="3w2mkr">
                                            </div></div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                         <input class="form-control taxableamount" readonly="readonly" id="taxableamountshow" name="taxableamountshow" type="text" value="" fdprocessedid="elxr4w">
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group"><input class="form-control amount" id="cpdoamountshow" readonly="readonly" name="amountnosaveshow" type="text" value="'.$surchargefee.'" fdprocessedid="nh806j"></div>
                                    </div>
                                    
                               </div>';
        }
        echo $html;
    }

    public function printengCasheringtax(Request $request){
    	    $id = $request->input('id'); 
     		$ctcdata = $data = $this->_engineeringcashering->getCertificateDetails($id);
            $bankaray = $this->bankaray;
     		$mpdf = new \Mpdf\Mpdf();
     		$mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;

            $getjobreqid = $this->_engineeringcashering->GetJobrequestId($ctcdata->top_transaction_id);
            //echo "<pre>"; print_r($getjobreqid); exit;

             $dynamicfeehtml= "";
             $defaultFeesarr = $this->_engineeringcashering->GetReqiestfees($getjobreqid->transaction_ref_no);  $i= 1;
             foreach ($defaultFeesarr as $key => $value) {
                $dynamicfeehtml .='<tr>
                           <td  style="text-align:left;">
                          <p style="font-size: 15px;font-weight: 400;"> '.$i.'.'.$value->fees_description.' :<span class="textborder"></span></p><td></td></td><td style="text-align:left;">'.$value->tax_amount.'</td>

                        </tr>';
                        $i++;
             }
             
            $html = file_get_contents(resource_path('views/layouts/templates/engcasheringreceipt.html'));
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $unchecked = url('/assets/images/unchecked-checkbox.png');
            $checked = url('/assets/images/checked-checkbox.png');
            $html = str_replace('{{DYNAMICTABLE}}',$dynamicfeehtml, $html); 
            $cash =$unchecked; $check=$unchecked; $money=$unchecked;
            if($ctcdata->payment_terms =='1'){ $cash =  $checked; }
            if($ctcdata->payment_terms =='3'){ $check =  $checked; }
            if($ctcdata->payment_terms =='2'){ $money =  $checked; }

            $html = str_replace('{{CHKCASH}}',$cash, $html);
            $html = str_replace('{{CHKCHECK}}',$check, $html);
            $html = str_replace('{{CHKMONEY}}',$money, $html);
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{ornumrer}}',$ctcdata->or_no, $html);
            $html = str_replace('{{Year}}',$ctcdata->cashier_year, $html);
            $html = str_replace('{{city}}','Palayan City', $html);
            $html = str_replace('{{dateissued}}',date('Y-m-d',strtotime($ctcdata->created_at)), $html);
            $html = str_replace('{{PAYERNAME}}',$ctcdata->full_name, $html);
            $html = str_replace('{{tinno}}',$ctcdata->p_tin_no, $html);
            // $html = str_replace('{{Address}}',$ctcdata->rpo_address_house_lot_no.",".$ctcdata->rpo_address_street_name.",".$ctcdata->rpo_address_subdivision, $html);
            $html = str_replace('{{TOTALDUE}}',$ctcdata->total_amount, $html);
            $html = str_replace('{{TOTALAMT}}',$ctcdata->total_amount, $html);
            $amountinworld =  $this->_commonmodel->numberToWord($ctcdata->total_amount);
            $html = str_replace('{{AMTINFIGURE}}',$amountinworld, $html); 

            $htmldynahistory='';
            if($ctcdata->payment_terms =='1'){ $htmldynahistory =  ""; }
            if($ctcdata->payment_terms =='3'){  
                $arrPaymentDetails = $this->_engineeringcashering->GetPaymentcheckdetails($id);
                foreach ($arrPaymentDetails as $key => $val) {
                    $htmldynahistory .='<tr>
                           <td  style="text-align:left;">
                          <p style="font-size: 15px;font-weight: 400;">'.$bankaray[$val->bank_id].' :<span class="textborder"></span></p><td></td>'.$val->opayment_amount.'</td><td style="text-align:left;">'.$val->opayment_date.'</td>
                        </tr>';
                }
            }
            if($ctcdata->payment_terms =='3'){  
                 $arrPaymentbankDetails = $this->_engineeringcashering->GetPaymentbankdetails($id);
                  foreach ($arrPaymentbankDetails as $key => $val) {
                    $htmldynahistory .='<tr>
                           <td  style="text-align:left;">
                          <p style="font-size: 15px;font-weight: 400;">'.$bankaray[$val->bank_id].' :<span class="textborder"></span></p><td></td>'.$val->opayment_amount.'</td><td style="text-align:left;">'.$val->opayment_date.'</td>
                        </tr>';
                }
            }
            $html = str_replace('{{PAYMENTHISTORY}}',$htmldynahistory, $html); 
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
            $orderfilename = $id.$filename."engcashering.pdf";
            
            $mpdf->Output($orderfilename, "I");
           
    }

    public function printReceipt(Request $request)
    {
        $id = $request->input('id'); 
        $ctcdata = $this->_engineeringcashering->getCertificateDetails($id);
        $cashiername = $this->_commonmodel->getemployeefullname($ctcdata->created_by);
        $bankaray = $this->bankaray;
        $data = $this->_engineeringcashering->Gettransactionnobyid($ctcdata->top_transaction_id); 

        $getjobreqid = $this->_engineeringcashering->GetJobrequestId($ctcdata->top_transaction_id);
        $defaultFeesarr = $this->_engineeringcashering->GetReqiestfees($getjobreqid->transaction_ref_no);
        //echo "<pre>"; print_r($defaultFeesarr); exit;
        // cash details
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
        // dd($data->ejr_surcharge_fee);
        $data = [
            'transacion_no' => $ctcdata->or_no,
            'date' => $ctcdata->created_at,
            'or_number' => $ctcdata->or_no,
            'payor' => $ctcdata->full_name,
            'transactions' => $defaultFeesarr,
            'total' => $ctcdata->total_amount,
            'payment_terms' => $ctcdata->payment_terms,
            'cash_details' => $arrPaymentbankDetails,
            'surcharge' => $data->ejr_surcharge_fee,
            'cashiername' => $cashiername->fullname,
            'cashierid' => $ctcdata->created_by,
            'varName'=>'cashier_engineering_collecting_officer'
            
        ];
        // return $this->_commonmodel->printReceipt($data);
        return $this->printReceiptEng($data);
    }

    public function printReceiptEng($data){
        $paymnet_or_setups = $this->_cpdocashering->getPaymentOrSetup($this->ortype_id);
        if(!$paymnet_or_setups){
            return "OR SETUP Not Found...";
        }
        $setup_details = json_decode($paymnet_or_setups->setup_details);
        $setup_details = (array)$setup_details;
        $border = 0;
        $pdfwidth = $paymnet_or_setups->width != null ? $paymnet_or_setups->width : 0;
        $pdfheight = $paymnet_or_setups->height != null ? $paymnet_or_setups->height : 0;
        $orientation = "L";
        if($paymnet_or_setups->is_portrait == 1){
            $orientation = "P";
        }
        $resolution  = array($pdfheight,$pdfwidth);
        $width = 0; $height = 0;
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        //PDF::SetMargins(20, 30, 20);  
        PDF::SetMargins(20, 10, 3,10);  
        PDF::SetAutoPageBreak(FALSE, 0);
        // PDF::AddPage($orientation, 'cm', array(10, 20), true, 'UTF-8', false);
        PDF::AddPage($orientation, $resolution);
        PDF::SetFont('Helvetica', '', 10);

        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        $ln=0; $fill=0; $reset=""; $align='L';

        // Positioning For Or No
        $or_is_bold = "";
        $or_is_visible = 0;
        if(isset($setup_details['af51c_or_no'])){
            if($setup_details['af51c_or_no']){
                $or_top = $setup_details['af51c_or_no']->af51c_or_no_position_top;
                $or_left = $setup_details['af51c_or_no']->af51c_or_no_position_left;
                $or_font = $setup_details['af51c_or_no']->af51c_or_no_font_size;
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_font_is_bold)){
                    $or_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_visible)){
                    $or_is_visible = 1;
                };
            }
            if($or_is_visible == 1){
                PDF::SetFont('Helvetica', $or_is_bold, $or_font);
                PDF::writeHTMLCell($width, $height, $or_left , $or_top, $data['or_number'], $border,$ln,$fill,$reset,$align);    
            }
        }
        
        // Positioning For Or Date
        $or_date_is_bold = "";
        $or_date_is_visible = 0; $border =0; $align='L';
        if(isset($setup_details['af51c_or_date'])){
            if($setup_details['af51c_or_date']){
                $or_date_top = $setup_details['af51c_or_date']->af51c_or_date_position_top;
                $or_date_left = $setup_details['af51c_or_date']->af51c_or_date_position_left;
                $or_date_font = $setup_details['af51c_or_date']->af51c_or_date_font_size;
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_font_is_bold)){
                    $or_date_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_visible)){
                    $or_date_is_visible = 1;
                };
            }
            if($or_date_is_visible == 1){
                PDF::SetFont('Helvetica', $or_date_is_bold, $or_date_font);
                PDF::writeHTMLCell($width, $height, $or_date_left , $or_date_top, Carbon::parse($data['date'])->toFormattedDateString(), $border,$ln,$fill,$reset,$align);    
            }
        }
        $or_agency_is_visible = 0; $border =0; $align='L';
        $or_agency_is_bold = "";
        if(isset($setup_details['af51c_agency'])){
            if($setup_details['af51c_agency']){
                $or_agency_top = $setup_details['af51c_agency']->af51c_agency_position_top;
                $or_agency_left = $setup_details['af51c_agency']->af51c_agency_position_left;
                $or_agency_font = $setup_details['af51c_agency']->af51c_agency_font_size;
                if(isset($setup_details['af51c_agency']->af51c_agency_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_font_is_bold)){
                    $or_agency_is_bold = "B";
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_is_visible)){
                    $or_agency_is_visible = 1;
                };
            }
            if($or_agency_is_visible == 1){
                PDF::SetFont('Helvetica', $or_agency_is_bold, $or_agency_font);
                PDF::writeHTMLCell($width, $height, $or_agency_left , $or_agency_top,config('constants.defaultCityCode.city'), $border,$ln,$fill,$reset,$align);    
            }
        }
        // Positioning For Constant City
       //PDF::writeHTMLCell(50, 0, 20,52,config('constants.defaultCityCode.city'), $border);//agency

        // Positioning For Payor
        $payor_is_bold = ""; $border =0; $align='L';
        $payor_is_visible = 0;
        if(isset($setup_details['af51c_payor'])){
            if($setup_details['af51c_payor']){
                $payor_top = $setup_details['af51c_payor']->af51c_payor_position_top;
                $payor_left = $setup_details['af51c_payor']->af51c_payor_position_left;
                $payor_font = $setup_details['af51c_payor']->af51c_payor_font_size;
                if(isset($setup_details['af51c_payor']->af51c_payor_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_font_is_bold)){
                    $payor_is_bold = "B";
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_is_visible)){
                    $payor_is_visible = 1;
                };
            }
            if($payor_is_visible == 1){
                PDF::SetFont('Helvetica', $payor_is_bold, $payor_font);
                PDF::writeHTMLCell($width, $height, $payor_left, $payor_top, $data['payor'], $border,$ln,$fill,$reset,$align);    
            }
        }
        $aligntext ="left";  $alignamt ="left"; 
         if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_right_justify)){
                    $aligntext='right';
                };
         if(isset($setup_details['af51c_bfees_amount']->af51c_bfees_amount_right_justify)){
                    $alignamt='right';
        };
       
        // $htmldynahistory='<table border="'.$border.'">';
        // foreach ($data['transactions'] as $key => $value) {
        //     if ($value->tax_amount != 0) {
        //         $htmldynahistory .='<tr>
        //                 <td style="text-align:'.$aligntext.';">
        //                 '.$value->fees_description.'
        //                 </td>
        //                 <td></td>
        //                 <td style="text-align:'.$alignamt.';">'.number_format($value->tax_amount,2).'</td>
        //             </tr>';
        //     }
        // }
       
       
        //$htmldynahistory .='</table>';
        //echo $htmldynahistory; exit;
        // Positioning For transactions, tax_amount
        $bfees_nature_col_is_bold = ""; $border =0; $align='L';
        $bfees_nature_col_is_visible = 0;
        if(isset($setup_details['af51c_bfees_nature_col'])){
            if($setup_details['af51c_bfees_nature_col']){
                $bfees_nature_col_top = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_top;
                $bfees_nature_col_left = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_left;
                $bfees_nature_col_font = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_size;
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_show_border)){
                    $border = 1;
                };

                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_is_bold)){
                    $bfees_nature_col_is_bold = "B";
                };
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_visible)){
                    $bfees_nature_col_is_visible = 1;
                };
            }
            if($bfees_nature_col_is_visible == 1){
                 foreach ($data['transactions'] as $key => $value) { 
                        $htmldynahistory='<table border="'.$border.'">';
                        if ($value->tax_amount != 0) {
                            $htmldynahistory .='<tr>
                                    <td width="60%" style="'.$aligntext.';font-size:8px;">
                                    '.$value->fees_description.'
                                    </td>
                                    <td width="40%" style="text-align:'.$alignamt.';font-size:8px;">'.number_format($value->tax_amount,2).'</td>
                                </tr>';
                       
                        $htmldynahistory .='</table>';
                         PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                         PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);  
                         $bfees_nature_col_top = $bfees_nature_col_top + 4;  
                       }
                    }
                  if (isset($data['surcharge']) && $data['surcharge']) {
                      $htmldynahistory ='<table border="'.$border.'">
                      <tr>
                          <td style="text-align:'.$aligntext.';font-size:8px;">
                          Surcharge Fee
                          </td>
                          <td style="text-align:'.$alignamt.';font-size:8px;">'.number_format($data['surcharge'],2).'</td>
                      </tr></table>';
                     PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                     PDF::writeHTMLCell($width, $height, $bfees_nature_col_left, $bfees_nature_col_top, $htmldynahistory, $border);  
                  }  
            }
        }
        
        // Positioning For Total
        $total_is_bold = ""; $border =0; $align='L';
        $total_is_visible = 0;
        if(isset($setup_details['af51c_total'])){
            if($setup_details['af51c_total']){
                $total_top = $setup_details['af51c_total']->af51c_total_position_top;
                $total_left = $setup_details['af51c_total']->af51c_total_position_left;
                $total_font = $setup_details['af51c_total']->af51c_total_font_size;
                if(isset($setup_details['af51c_total']->af51c_total_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_total']->af51c_total_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_total']->af51c_total_font_is_bold)){
                    $total_is_bold = "B";
                };
                if(isset($setup_details['af51c_total']->af51c_total_is_visible)){
                    $total_is_visible = 1;
                };
            }
            if($total_is_visible == 1){
                PDF::SetFont('Helvetica', $total_is_bold, $total_font);
                PDF::writeHTMLCell($width, $height, $total_left , $total_top, number_format($data['total'],2), $border,$ln,$fill,$reset,$align);
            }
        }
        
        
        // Positioning for Amount In Words
        $amount_words_is_bold = ""; $border =0; $align='L';
        $amount_words_is_visible = 0;
        if(isset($setup_details['af51c_amount_words'])){
            if($setup_details['af51c_amount_words']){
                $amount_words_top = $setup_details['af51c_amount_words']->af51c_amount_words_position_top;
                $amount_words_left = $setup_details['af51c_amount_words']->af51c_amount_words_position_left;
                $amount_words_font = $setup_details['af51c_amount_words']->af51c_amount_words_font_size;
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_font_is_bold)){
                    $amount_words_is_bold = "B";
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_visible)){
                    $amount_words_is_visible = 1;
                };
            }
            $alignwordtext ="left"; 
            if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };

            if($amount_words_is_visible == 1){
                $amountinworld =  $this->_commonmodel->numberToWord($data['total']);  
                // PDF::writeHTMLCell(55, 0, 33,129,$amountinworld, $border);//amount in words
                PDF::SetFont('Helvetica', $amount_words_is_bold, $amount_words_font);
                PDF::writeHTMLCell($width, $height, $amount_words_left , $amount_words_top, $amountinworld, $border,$ln,$fill,$reset,$align);
            }
        }
        
        
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
        
        // Positioning for Amount In Words
        $htmldynahistory='<table border="'.$border.'" style="text-align:center">';
        foreach ($data['cash_details'] as $key => $value) {
            // dd($value);
                $htmldynahistory .='<tr>
                        <td>'.$this->_commonmodel->bank($value->bank_id)->bank_code.'</td>
                        <td>'.$value->opayment_check_no.'</td>
                        <td>'.Carbon::parse($value->opayment_date)->format('m/d/y').'</td>
                    </tr>';
        }
        $htmldynahistory .='</table>';

        $cashier_details_is_bold = ""; $border =0; $align='L';
        $cashier_details_is_visible = 0;
        if(isset($setup_details['af51c_cashier_details'])){
            if($setup_details['af51c_cashier_details']){
                $cashier_details_top = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_top;
                $cashier_details_left = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_left;
                $cashier_details_font = $setup_details['af51c_cashier_details']->af51c_cashier_details_font_size;
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_font_is_bold)){
                    $cashier_details_is_bold = "B";
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_visible)){
                    $cashier_details_is_visible = 1;
                };
            }
            if($cashier_details_is_visible == 1){
                PDF::SetFont('Helvetica', $cashier_details_is_bold, $cashier_details_font);
                PDF::writeHTMLCell($width, $height, $cashier_details_left , $cashier_details_top, $htmldynahistory, $border,$ln,$fill,$reset,$align);
            }
        }
        // Positioning For Collecting Officer
        $collecting_officer_is_bold = ""; $border =0; $align='L';
        $collecting_officer_is_visible = 0;
        if(isset($setup_details['af51c_collecting_officer'])){
            if($setup_details['af51c_collecting_officer']){
                $collecting_officer_top = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_top;
                
                $collecting_officer_left = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_left;
                $collecting_officer_font = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_size;
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_is_bold)){
                    $collecting_officer_is_bold = "B";
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_visible)){
                    $collecting_officer_is_visible = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_right_justify)){
                    $align='R';
                };
            }
            if($collecting_officer_is_visible == 1){
                PDF::SetFont('Helvetica', $collecting_officer_is_bold, $collecting_officer_font);
                PDF::writeHTMLCell($width, $height, $collecting_officer_left , $collecting_officer_top, $data['cashiername'], $border,$ln,$fill,$reset,$align);
            }
        }
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['transacion_no'].'.pdf';
       // PDF::Output($filename,'I'); exit;
        $arrSign= $this->_commonmodel->isSignApply('cashier_engineering_collecting_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        if(!$signType || !$isSignVeified){
            PDF::Output($folder.$filename);
        }else{
            $signature = $this->_commonmodel->getuserSignature($data['cashierid']);
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

    public function getfeeamount(Request $request){
    	$id = $request->input('tfocid');
    	$feeamount = $this->_engineeringcashering->GetFeeamount($id);
    	echo $feeamount->tfoc_amount;
    }

    public function getamountinword(Request $request){
       $number = $request->input('amount'); $pointamount="";
        $amountinworld =  $this->_commonmodel->numberToWord($number);
       //  $amountinworld = str_replace("and","",$amountinworld);
       //  $amountinworld = str_replace("thous","thousand",$amountinworld);
       //  $arramount = explode(".", $number);
       //  if(count($arramount) > 1){
       //    if($arramount[1] > 0){
       //      $pointamount = $arramount[1]."/100";
       //    }
       // }
       echo $amountinworld;
    }
    public function getTransactionid(Request $request){
    		$id=$request->input('pid');
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
            echo json_encode($data);
    }

    public function getClientsDropdown(Request $request){
    	 echo $id = $request->input('id');  $htmloption ='<option value="">Please Select</option>';
    	 if($id =='1'){
    	 		$data = $this->_engineeringcashering->getRptOwners();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'">'.$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name.'</option>';
              }
    	 }else{
    	 		$data = $this->_engineeringcashering->getCitizens();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'">'.$val->cit_fullname.'</option>';
              }
    	 }
    	 echo $htmloption;
    }

    public function getOrnumber(Request $request){
    	$checkflag = $request->input('orflag');
    	if($checkflag == '1'){
    		$getorno = $this->_engineeringcashering->GetcpdolatestOrNumber();
            if(!empty($getorno->or_no)){
                echo $getorno = $getorno->or_no +1;
            }
    	}else{
            $getorrange = $this->_commonmodel->getGetOrrange($this->ortype_id,\Auth::user()->id);
            if(empty($getorrange->latestusedor)){
                if(!empty($getorrange->ora_from)){
                   echo $orNumber = $getorrange->ora_from;
                }
            }else{
                echo $getorrange = $getorrange->latestusedor + 1;
            }
    	}
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engineeringcashering->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['cashier_year']=$row->cashier_year;  
            $arr[$i]['taxpayername']=$row->full_name;
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            $addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->client_citizen_id), 40, "<br />\n");
            $arr[$i]['completeaddress']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['top_no']=$row->top_no;
            $arr[$i]['total_amount']=number_format($row->total_amount,2);
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['cashier']=$row->fullname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engcashering/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Engineering Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div><div class="action-btn bg-info ms-2">
                        <a href="'.url('/engcashering/printengCasheringtax?id='.$row->id).'" target="_blank" title="Print Eng Cashiering"  data-title="Print Eng Cashiering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
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
	        $data = (object)$this->data;
	        $checkdetail = (object)$this->checkdetail; 
	        $clientsarr = $this->clientsarr;
	        $feesaray = $this->feesaray;
	        $fundarray = $this->fundarray;
	        $bankaray = $this->bankaray;
	        $arrgetTransactions =array("Please Select");
            $arrcancelreason = $this->arrcancelreason;
	        $arrFeeDetails = array();  $arrPaymentDetails = array();  $arrPaymentbankDetails = array();
	        $data->createdat = date('Y-m-d');   $data->cashier_year = date('Y');
            foreach ($this->_engineeringcashering->getTransactions($request->input('id')) as $val) {
               $arrgetTransactions[$val->id]=$val->transaction_no;
            } 
	        if($request->input('id')>0 && $request->input('submit')==""){
	            $data = $this->_engineeringcashering->Geteditrecord($request->input('id'));
	            //echo "<pre>"; print_r($healthcertreq); exit;
	            $data->createdat = date('Y-m-d',strtotime($data->created_at));
	            $arrFeeDetails = $this->_engineeringcashering->GetFeedetails($request->input('id'));
	            $arrPaymentDetails = $this->_engineeringcashering->GetPaymentcheckdetails($request->input('id'));
	            $arrPaymentbankDetails = $this->_engineeringcashering->GetPaymentbankdetails($request->input('id'));
	            //echo "<pre>"; print_r($arrPaymentDetails); exit;
	            if(count($arrPaymentDetails)> 0){ $checkdetail->payment_terms = $arrPaymentDetails[0]->payment_terms; }
	            if(count($arrPaymentbankDetails)> 0){ $checkdetail->payment_terms = $arrPaymentbankDetails[0]->payment_terms; }
	         }
            if($request->input('submit')!=""){
	            foreach((array)$this->data as $key=>$val){
	                $this->data[$key] = $request->input($key);
	            }
                //echo "<pre>"; print_r($_POST); exit;
	            $cashierdetails = array();
	            $cashierdetails['cashier_year'] = date('Y');
	            $cashierdetails['cashier_month'] = date('m');
	            $cashierdetails['tfoc_is_applicable'] ='3';
	            $cashierdetails['payee_type'] = "1";
	            $cashierdetails['client_citizen_id'] =$this->data['client_citizen_id'];
                $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
                $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
                $this->data['taxpayers_name'] = $taxpayername;
	            //echo "<pre>"; print_r($this->data); exit;
	            $this->data['tfoc_is_applicable'] = '3'; 
	            $this->data['payee_type'] = '1'; 
                $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                $this->data['ortype_id'] =  $getortype->ortype_id;
                unset($this->data['createdat']);
	            unset($this->data['cashier_batch_no']); 
	            $this->data['updated_by']=\Auth::user()->id;
	            $this->data['updated_at'] = date('Y-m-d H:i:s');
	            if($request->input('id')>0){
	                $this->_engineeringcashering->updateData($request->input('id'),$this->data);
	                $success_msg = 'Engineering Cashiering updated successfully.';
	                $lastinsertid = $request->input('id');
	            }else{
                    $updateremotedata = array();
                    $transactionno = str_pad($this->data['top_transaction_id'], 6, '0', STR_PAD_LEFT);
                    $updateremotedata['topno'] = $transactionno;
                    $updateremotedata['orno'] = $this->data['or_no'];
                    $updateremotedata['ordate'] = date("Y-m-d");
                    $updateremotedata['payment_status'] = 1;
                    $updateremotedata['cashieramount'] = $this->data['total_amount'];
                    $getappid =  $this->_engineeringcashering->getappidbytoptransaction($this->data['top_transaction_id']);
                    $this->_engineeringcashering->updatelocaldata($getappid->transaction_ref_no,$updateremotedata);
                    $this->_engineeringcashering->updateremotedata($getappid->transaction_ref_no,$updateremotedata);


	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
	                $this->data['status'] = '1'; 
                    $this->data['payment_type'] = 'Walk-In';
                    $this->data['cashier_or_date'] = $_POST['applicationdate'];
                    $this->data['net_tax_due_amount'] = $this->data['total_amount'];
                    $issueNumber = $this->getPrevIssueNumber();
                    $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                    $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                    $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->data['or_no']);
                    if($getorRegister != Null){
                      $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_engineeringcashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no; 
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_engineeringcashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_engineeringcashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }   
                    }
                    
                    $this->data['cashier_issue_no'] = $issueNumber; 
                    $this->data['cashier_batch_no'] = $cashier_batch_no;
                    $this->data['cashier_year'] = date('Y');
                    $this->data['cashier_month'] = date('m');
                  //echo "<pre>"; print_r($_POST); exit;  
	                $lastinsertid = $this->_engineeringcashering->addData($this->data);
	                $success_msg = 'Engineering Cashiering added successfully.';
	                Session::put('ENGG_PRINT_CASHIER_ID',$lastinsertid);
	                $uptdata = array('latestusedor' => $this->data['or_no']);

                    $prmitsrno = 1;
                    $getseries = $this->_engineeringcashering->getlatestseries();
                    if(!empty($getseries)){
                       $prmitsrno = $getseries->permitnoseries; 
                    }
                    $permitsrno= $prmitsrno + 1; 
                    $id = $request->input('id');
                    $appPermitNo = date('Y').'-'.date('m').'-'.str_pad($permitsrno, 4, '0', STR_PAD_LEFT);
                    $updatearray = array('ebpa_permit_no'=>$appPermitNo);
                    $this->_engineeringcashering->updatePermitAppData($request->input('engjobrequestno'),$updatearray);
	                //$this->_engineeringcashering->UpdateOrused('3',$uptdata);
                    $getapptype = $this->_engineeringcashering->getapplicationtype($request->input('engjobrequestno'));
                    if($getapptype->es_id == '1'){
                       $ejrdata =array('ejr_or_no'=>$this->data['or_no'],'permitnoseries'=>$permitsrno); 
                   }else{
                    $ejrdata =array('ejr_or_no'=>$this->data['or_no']);
                   }
                     
                    //,'ejr_date_paid'=>date('Y-m-d')
                    $this->_engineeringcashering->updateDatajobrequest($request->input('engjobrequestno'),$ejrdata);

                    $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                    $cashierdetails['cashier_id'] = $lastinsertid;
                    $cashierdetails['top_transaction_id'] = $this->data['top_transaction_id'];
                    $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
                    
                     if($request->input('total_paid_surcharge') > 0){
                        $cashierdetails['tfoc_id'] =$request->input('maintfoc_id');
                        $cashdata = $this->_engineeringcashering->getCasheringsurchargeIds($request->input('maintfoc_id'));
                        $fundid = "0"; $glaccountid ="0"; $slid="0";
                        if(!empty($cashdata)){
                                 $fundid = $cashdata->fund_id; 
                                 $glaccountid = $cashdata->tfoc_surcharge_gl_id;
                                 $slid = $cashdata->tfoc_surcharge_sl_id;
                        }
                        $cashierdetails['ctc_taxable_amount'] ="0";
                        $cashierdetails['tfc_amount'] =$_POST['total_paid_surcharge'];
                        $cashierdetails['or_no'] = $this->data['or_no'];
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
                        $addincomedata['amount'] = $_POST['total_paid_surcharge'];
                        $addincomedata['tfoc_id'] = $request->input('maintfoc_id');
                        $addincomedata['fund_id'] = $fundid;
                        $addincomedata['gl_account_id'] = $glaccountid;
                        $addincomedata['sl_account_id'] = $slid;
                        $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
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
	          
	            $Cashierid = $lastinsertid;
               
                if(isset($_POST['taxfees'])){
	            if(count($_POST['taxfees']) >0){
	            	foreach ($_POST['taxfees'] as $key => $value){
	            		$cashdata = $this->_engineeringcashering->getCasheringIds($value);
	            		$cashierdetails['tfoc_id'] =$value;
	            		$cashierdetails['ctc_taxable_amount'] =$_POST['taxableamount'][$key];
	            		$cashierdetails['tfc_amount'] =$_POST['amount'][$key];
                        $cashierdetails['top_transaction_id'] = $this->data['top_transaction_id'];
                        $cashierdetails['all_total_amount'] = $_POST['amount'][$key];
	            		$cashierdetails['or_no'] = $this->data['or_no'];
                        $getortype = $this->_engineeringcashering->GetOrtypeid('3');
                        $cashierdetails['ortype_id'] = $getortype->ortype_id; 
                        $fundidnew = "0";
                        if(!empty($cashdata)){
                             $fundidnew = $cashdata->fund_id; 
                        }
                        if($request->input('id') <= 0 ){
                            if($value == $request->input('maintfoc_id')){
                            $othertaxesarr = $this->_engineeringcashering->GetOthercharges($value);
                            $amountreduce =0; 
                            foreach ($othertaxesarr as $keyot => $valot) {
                                $insertothettaxesarray = array();
                                $insertothettaxesarray['cashier_year'] = date('Y');
                                $insertothettaxesarray['cashier_month'] = date('m');
                                $insertothettaxesarray['tfoc_is_applicable'] ='3';
                                $insertothettaxesarray['payee_type'] = "1";
                                $insertothettaxesarray['cashier_batch_no'] =$cashier_batch_no;
                                $insertothettaxesarray['top_transaction_id'] = $this->data['top_transaction_id'];
                                $insertothettaxesarray['client_citizen_id'] =$this->data['client_citizen_id'];
                                $insertothettaxesarray['cashier_issue_no'] = $cashier_issue_no;
                                $insertothettaxesarray['cashier_id'] =$lastinsertid;
                                $insertothettaxesarray['tfoc_id'] =$value;
                                $insertothettaxesarray['ortype_id'] =  $getortype->ortype_id;
                                $insertothettaxesarray['agl_account_id'] =$valot->otaxes_gl_id;
                                $insertothettaxesarray['sl_id'] =$valot->otaxes_sl_id;
                                $deductamount = $_POST['amount'][$key] * $valot->otaxes_percent /100;
                                $amountreduce = $amountreduce + $deductamount;
                                $insertothettaxesarray['tfc_amount'] =$deductamount;
                                $insertothettaxesarray['or_no'] = $this->data['or_no'];
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
                                $addincomedata['tfoc_id'] = $_POST['maintfoc_id'];
                                $addincomedata['fund_id'] = $fundidnew;
                                $addincomedata['gl_account_id'] = $valot->otaxes_gl_id;
                                $addincomedata['sl_account_id'] = $valot->otaxes_sl_id;
                                $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
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
                             $cashierdetails['tfc_amount'] = $_POST['amount'][$key] -$amountreduce; 
                           }
                        }
                        //echo "<pre>";  print_r($cashierdetails);
                        $fundid = "0"; $glaccountid ="0"; $slid="0";
                        $cashierdetails['ortype_id'] =  $getortype->ortype_id;
                        //$cashierdetails['cashier_remarks'] = $this->data['cashier_remarks'];
                        if(!empty($cashdata)){
                             $cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
                             $cashierdetails['sl_id'] = $cashdata->sl_id;  
                             $fundid = $cashdata->fund_id; 
                             $glaccountid = $cashdata->gl_account_id;
                             $slid = $cashdata->sl_id;
                        }
      	            		$checkdetailexist =  $this->_engineeringcashering->checkrecordisexist($value,$Cashierid);
      	            		if(count($checkdetailexist) > 0){
                                   $cashierdetailsupt = array();
                                   $cashierdetailsupt['tfoc_id'] =$value;
                                   $cashierdetailsupt['ctc_taxable_amount'] =$_POST['taxableamount'][$key];
                                   $cashierdetailsupt['tfc_amount'] =$_POST['amount'][$key];
                                   $cashierdetailsupt['or_no'] = $this->data['or_no'];
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
                            $addincomedata['tfoc_id'] = $value;
                            $addincomedata['fund_id'] = $fundid;
                            $addincomedata['gl_account_id'] = $glaccountid;
                            $addincomedata['sl_account_id'] = $slid;
                            $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
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
                            $this->_commonmodel->insertCashReceipt($value, $addincomedata['amount'], $this->data['or_no'],$request->cashier_particulars);
	            		}
	            	}
	              }
                }
                if(isset($_POST['desc'])){
                if(count($_POST['desc']) > 0){
                        $detailsoccupancy = array();
                        foreach ($_POST['desc'] as $key => $value){
                        $cashdata = $this->_engineeringcashering->getCasheringIds($_POST['maintfoc_id']);
                        $detailsoccupancy['top_transaction_id'] =$this->data['top_transaction_id'];
                        $detailsoccupancy['cashier_id'] = $lastinsertid;
                        $detailsoccupancy['tfoc_is_applicable'] =  "3";
                        $detailsoccupancy['tfoc_id'] = $_POST['maintfoc_id'];
                        $detailsoccupancy['fees_description'] = $value;
                        $detailsoccupancy['tfc_amount'] = $_POST['amountnosave'][$key];
                        $fundid = "0"; $glaccountid ="0"; $slid="0";
                        if(!empty($cashdata)){
                             $detailsoccupancy['agl_account_id'] = $cashdata->gl_account_id;
                             $detailsoccupancy['sl_id'] = $cashdata->sl_id; 
                             $fundid = $cashdata->fund_id; 
                             $glaccountid = $cashdata->gl_account_id;
                             $slid = $cashdata->sl_id;  
                        }
                        $checkdetailexist =  $this->_engineeringcashering->checkeng_occupancy_details($value,$lastinsertid);
                        if(count($checkdetailexist) > 0){
                            $detailsoccupancy = array();
                            $detailsoccupancy['fees_description'] = $value;
                            $detailsoccupancy['tfc_amount'] = $_POST['amountnosave'][$key];
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
                            $addincomedata['amount'] = $_POST['amountnosave'][$key];
                            $addincomedata['tfoc_id'] = $request->input('maintfoc_id');
                            $addincomedata['fund_id'] = $fundid;
                            $addincomedata['gl_account_id'] = $glaccountid;
                            $addincomedata['sl_account_id'] = $slid;
                            $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                            $addincomedata['or_no'] = $this->data['or_no'];
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

                if($_POST['payment_terms'] !=1){
    	            if(!empty($_POST['fundid'.$_POST['payment_terms']])){
    	            	foreach ($_POST['fundid'.$_POST['payment_terms']] as $key => $value){   $paymentdata = array();
    	            		if($_POST['payment_terms'] =='2'){
    	            			$paymentdata['bank_account_no'] =  $_POST['accountno'.$_POST['payment_terms']][$key];
    	            		   $paymentdata['opayment_transaction_no'] =  $_POST['transactionno'.$_POST['payment_terms']][$key];
    	            		}else{
    	            			$paymentdata['check_type_id'] =$_POST['checktype'.$_POST['payment_terms']][$key];
    	            			$paymentdata['opayment_check_no'] =  $_POST['checkno'.$_POST['payment_terms']][$key];
    	            		}
    	            		$paymentdata['cashier_id'] =$lastinsertid;
    	            		$paymentdata['opayment_date'] =$_POST['checkdate'.$_POST['payment_terms']][$key];
    	            		$paymentdata['payment_terms'] = $_POST['payment_terms'];
    	            		$paymentdata['fund_id'] =  $_POST['fundid'.$_POST['payment_terms']][$key];
    	            		$paymentdata['bank_id'] =  $_POST['bank'.$_POST['payment_terms']][$key];
    	            		$paymentdata['opayment_amount'] = $_POST['checkamount'.$_POST['payment_terms']][$key];
    	            		if(!empty($_POST['pid'][$key]) > 0){
    	            				$this->_engineeringcashering->updateCashierPaymentData($_POST['pid'][$key],$paymentdata);
    	            		} else{
    	            			$paymentdata['opayment_year'] = date('Y');
    	            			$paymentdata['opayment_month'] = date('m');
    	            			$this->_engineeringcashering->addCashierPaymentData($paymentdata);
    	            		}
    	            	}
    	              }
                }
                $arrData=array();  $top_transaction_id = $this->data['top_transaction_id'];
                $arrData['is_paid']=1;
                $arrData['updated_by']=\Auth::user()->id;
                $arrData['updated_at']= date('Y-m-d H:i:s');
                $this->_engineeringcashering->updateTopTransaction($top_transaction_id,$arrData);
                $smsTemplate=SmsTemplate::where('id',64)->where('is_active',1)->first();
                $arrData = $this->_engineeringcashering->getengappdata($getappid->transaction_ref_no);
                if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
                {
                        $receipient=$arrData->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                        $msg = str_replace('<TOP_NO>',$transactionno,$msg);
                        $msg = str_replace('<AMOUNT>',$this->data['total_amount'],$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                       $this->send($msg, $receipient);
                }
                $this->data['eng_jobrequest_id'] = $getappid->transaction_ref_no;
                $this->addPaymentHistory($this->data);
	            return redirect()->route('engcashering.index')->with('success', __($success_msg));
	        }
	        return view('Engneering.cashering.create',compact('data','clientsarr','arrFeeDetails','feesaray','arrgetTransactions','fundarray','bankaray','checkdetail','arrPaymentDetails','arrPaymentbankDetails','arrcancelreason'));
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
           $payment_history['eng_jobrequest_id']=$billdata['eng_jobrequest_id'];
               
            //echo "<pre>"; print_r($payment_history); exit;
            $checktransexist =  $this->_commonmodel->checktrnsactionexist($billdata['top_transaction_id']);
            if(count($checktransexist) <=0){
                $pfrngid = $this->_commonmodel->addPaymentHistory($payment_history);
                $payment_history['frgn_payment_id']=$pfrngid;
                $this->_commonmodel->addPaymentHistoryremote($payment_history);
            }
    }

    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_engineeringcashering->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'or_no'=>'required|unique:cto_cashier,or_no,'.(int)$request->input('id'),
                'top_transaction_id'=>'required|unique:cto_cashier,top_transaction_id,'.(int)$request->input('id').',id,status,1',
                'client_citizen_id'=>'required', 
                'total_amount'=>'required', 
                'total_paid_amount'=>'required|gte:total_amount',
                'total_amount_change'=>'required'
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

    public function getbploappdetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_engineeringcashering->getBploApplictaiondetails($id);
        echo json_encode($data);
    } 
	
}
