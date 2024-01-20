<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use App\Models\Engneering\OccupancyCashering;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class OccupancyCasheringController extends Controller
{
     public $data = [];
    public $postdata = [];
    public $clientsarr = array(""=>"Select Year");
    public $feesaray = array(""=>"Select fees");
    public $fundarray = array(""=>"Select fund");
    public $bankaray = array(""=>"Select bank");
    public $arrcancelreason = array(""=>"Please Select");
    public $arrgetTransactions = array(""=>"Please Select");
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_occupancycashering = new OccupancyCashering();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','top_transaction_id'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','payee_type'=>'1','or_no'=>'','payment_terms'=>'','total_amount'=>'','total_paid_amount'=>'','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Occupancy Fee','ctc_place_of_issuance'=>'','cashier_remarks'=>'');
        $this->checkdetail = array('payment_terms' =>'1');
        $this->slugs = 'occupancycashering'; 

        foreach ($this->_occupancycashering->getEngOwners() as $val) {
            $this->clientsarr[$val->id]=$val->full_name;
        }
         foreach ($this->_occupancycashering->Gettaxfees() as $val) {
            $this->feesaray[$val->id]=$val->accdesc;
        }
        foreach ($this->_occupancycashering->getTransactions() as $val) {
            $this->arrgetTransactions[$val->id]=$val->transaction_no;
        } 
        foreach ($this->_occupancycashering->getfundcode() as $val) {
            $this->fundarray[$val->id]=$val->code;
        } 
        foreach ($this->_occupancycashering->getBankarray() as $val) {
            $this->bankaray[$val->id]=$val->bank_code;
        }
        foreach ($this->_occupancycashering->getCancelReason() as $val) {
            $this->arrcancelreason[$val->id]=$val->ocr_reason;
        } 
        $getortype = $this->_occupancycashering->GetOrtypeid('4');
                $this->ortype_id =  $getortype->ortype_id;    
    }

    public function index(Request $request)
    {   $this->is_permitted($this->slugs, 'read'); 
          $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
          $startdate=Date('Y-m-d', strtotime('-30 days'));
          return view('Engneering.casheringoccupancy.index',compact('startdate','enddate'));
    }

    public function getClientsbussiness(Request $request){
    	$id= $request->input('cid'); $businesshtml ="";
    	$businessdata = $this->_occupancycashering->getBusinessDetails($id);
    	foreach ($businessdata as $key => $value) {
    		$businesshtml .= '<option value='.$value->id.'>'.$value->busn_name.'</option>';
    	}
    	echo $businesshtml;
    }
    
    public function getallFeeseoaedit(Request $request){
            $ejrid = $request->input('ejrid');
        $defaultFeesarr = $this->_occupancycashering->GetReqiestfees($ejrid);
        $getsurchargefee = $this->_occupancycashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->eoa_surcharge_fee;
        $year = date('Y',strtotime($getsurchargefee->created_at));
        $html ="";
        foreach ($defaultFeesarr as $key => $val) {
                    if($val->tax_amount > 0 ){
                              $html .='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year[]" type="text" value="'.Date('Y').'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div>';
                                    
                                     $html .='<div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group">';
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
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                    </div>
                               </div>';
                           }
        }
         if($surchargefee > 0){
            $html.='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year" type="text" value="'.$year.'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><div class="form-icon-user">
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
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                    </div>
                               </div>';
        }
        echo $html;
    }

    public function getallFeeseeoa(Request $request){
        $ejrid = $request->input('ejrid');
        $defaultFeesarr = $this->_occupancycashering->GetReqiestfees($ejrid);
        $html ="";
        $getsurchargefee = $this->_occupancycashering->Getsurchargefee($ejrid);
        $surchargefee= $getsurchargefee->eoa_surcharge_fee;
        foreach ($defaultFeesarr as $key => $val) {
                    if($val->tax_amount > 0){
                              $html .='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year[]" type="text" value="'.Date('Y').'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div>';
                                    
                                     $html .='<div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group">';
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
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                    </div>
                               </div>';
                           }
        }
         if($surchargefee > 0){
            $html.='<div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group"> <div class="form-icon-user"><input class="form-control" readonly="readonly" id="year" name="year" type="text" value="'.Date('Y').'" fdprocessedid="3w2mkr"></div>
                                        </div>
                                    </div><div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"><div class="form-icon-user">
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
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                    </div>
                               </div>';
        }
        echo $html;
    }

    public function printoccupancyCasheringtax(Request $request){
            $id = $request->input('id'); 
            $ctcdata = $data = $this->_occupancycashering->getCertificateDetails($id);
            $cashiername = $this->_commonmodel->getemployeefullname($ctcdata->created_by);
            $bankaray = $this->bankaray;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;

            $getoccupancyid = $this->_occupancycashering->GetJobrequestId($ctcdata->top_transaction_id);
            //echo "<pre>"; print_r($getjobreqid); exit;

             $dynamicfeehtml= "";
             $defaultFeesarr = $this->_occupancycashering->GetReqiestfees($getoccupancyid->transaction_ref_no);  $i= 1;
       

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
            // dd($ctcdata->total_amount);
            $reciep_data = [
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
                'varName'=>'cashier_occupancy_collecting_officer'
            ];
            //echo "<pre>"; print_r($reciep_data); exit;        
            return $this->_commonmodel->printReceiptoccu($reciep_data,$this->ortype_id);
            // $mpdf->Output($orderfilename, "I");
    }


    public function getfeeamount(Request $request){
    	$id = $request->input('tfocid');
    	$feeamount = $this->_occupancycashering->GetFeeamount($id);
    	echo $feeamount->tfoc_amount;
    }

   public function getAmountInWord(Request $request)
    {
        $number = $request->input('amount');
        $arrAmount = explode(".", $number);
        $wholePartInWords = $this->_commonmodel->numberToWord($arrAmount[0]);
        $wholePartInWords = str_replace("thous", "thousand", $wholePartInWords);
       
        $amountInWords = $wholePartInWords;
        echo $amountInWords;
    }
    public function getTransactionid(Request $request){
    		$id=$request->input('pid');
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
            echo json_encode($data);
    }

    public function getClientsDropdown(Request $request){
    	 echo $id = $request->input('id');  $htmloption ='<option value="">Please Select</option>';
    	 if($id =='1'){
    	 		$data = $this->_occupancycashering->getRptOwners();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'">'.$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name.'</option>';
              }
    	 }else{
    	 		$data = $this->_occupancycashering->getCitizens();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'">'.$val->cit_first_name." ".$val->cit_middle_name." ".$val->cit_last_name.'</option>';
              }
    	 }
    	 echo $htmloption;
    }

    public function getOrnumber(Request $request){
    	$checkflag = $request->input('orflag');
    	if($checkflag == '1'){
    		$getorno = $this->_occupancycashering->GetcpdolatestOrNumber();
        if(!empty($getorno->or_no)){
          echo $getorno = $getorno->or_no +1;
        }
    	}else{
         $getorrange = $this->_commonmodel->getGetOrrange('2',\Auth::user()->id);
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
        $data=$this->_occupancycashering->getList($request);
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
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/occupancycashering/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Occupancy Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div><div class="action-btn bg-info ms-2">
                        <a href="'.url('/occupancycashering/printoccupancyCasheringtax?id='.$row->id).'" target="_blank" title="Print Eng Cashiering"  data-title="Print Eng Cashiering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
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

     public function getTopNumbersAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_occupancycashering->gettopnoAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->transaction_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

     public function store(Request $request){
	        $data = (object)$this->data;
	        $checkdetail = (object)$this->checkdetail; 
	        $clientsarr = $this->clientsarr;
	        $feesaray = $this->feesaray;
	        $fundarray = $this->fundarray;
	        $bankaray = $this->bankaray;
	        $arrgetTransactions = $this->arrgetTransactions;
            $arrcancelreason = $this->arrcancelreason;
	        $arrFeeDetails = array();  $arrPaymentDetails = array();  $arrPaymentbankDetails = array();
	        $data->createdat = date('Y-m-d'); $data->cashier_year =date('Y');
	        if($request->input('id')>0 && $request->input('submit')==""){
	            $data = $this->_occupancycashering->Geteditrecord($request->input('id'));
                $arrgetTransactions = array();
                foreach ($this->_occupancycashering->getTransactionsedit() as $val) {
                    $arrgetTransactions[$val->id]=$val->transaction_no;
                } 
	            //echo "<pre>"; print_r($healthcertreq); exit;
	            $data->createdat = date('Y-m-d',strtotime($data->created_at));
	            $arrFeeDetails = $this->_occupancycashering->GetFeedetails($request->input('id'));
	            $arrPaymentDetails = $this->_occupancycashering->GetPaymentcheckdetails($request->input('id'));
	            $arrPaymentbankDetails = $this->_occupancycashering->GetPaymentbankdetails($request->input('id'));
	            //echo "<pre>"; print_r($arrPaymentbankDetails); exit;
	            if(count($arrPaymentDetails)> 0){ $checkdetail->payment_terms = $arrPaymentDetails[0]->payment_terms; }
	            if(count($arrPaymentbankDetails)> 0){ $checkdetail->payment_terms = $arrPaymentbankDetails[0]->payment_terms; }

	        }
            if($request->input('submit')!=""){
	            foreach((array)$this->data as $key=>$val){
	                $this->data[$key] = $request->input($key);
	            }

	            $cashierdetails = array();
	            //echo "<pre>"; print_r($_POST); exit;
	            $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
              $taxpayername = $this->_commonmodel->getUserName($clientdata->rpo_first_name,$clientdata->rpo_middle_name,$clientdata->rpo_custom_last_name,$clientdata->suffix);
              $this->data['taxpayers_name'] = $taxpayername;
	            unset($this->data['cashier_batch_no']); 
	            $this->data['updated_by']=\Auth::user()->id;
	            $this->data['updated_at'] = date('Y-m-d H:i:s');
	            if($request->input('id')>0){
	                $this->_occupancycashering->updateData($request->input('id'),$this->data);
	                $success_msg = 'Occupancy Casheiring updated successfully.';
	                $lastinsertid = $request->input('id');
	            }else{
	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
                    $this->data['cashier_or_date'] = date("Y-m-d");
	                $issueNumber = $this->getPrevIssueNumber();
                    $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                    $cashier_batch_no = date('Y')."-".$cashier_issue_no;
                    $this->data['cashier_issue_no'] = $issueNumber; 
                    $this->data['cashier_batch_no'] = $cashier_batch_no; 
                    $this->data['cashier_year'] = date('Y');
                    $this->data['cashier_month'] = date('m');
                    $this->data['tfoc_is_applicable'] = '4'; 
                    $this->data['payee_type'] = '1'; 
                    $this->data['status'] = '1'; 
                    $this->data['payment_type'] = 'Walk-In';
                    $this->data['cashier_issue_no'] = $cashier_issue_no;
                    $this->data['net_tax_due_amount'] = $this->data['total_amount'];
                    $getortype = $this->_occupancycashering->GetOrtypeid('4');
                    $this->data['ortype_id'] =  $getortype->ortype_id;
                    $getorRegister = $this->_commonmodel->Getorregisterid($getortype->ortype_id,$this->data['or_no']);
                    if($getorRegister != Null){
                      $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_occupancycashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                       $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no;  
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_occupancycashering->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_occupancycashering->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }    
                    }
                    //echo "<pre>"; print_r($_POST); exit;
	                $lastinsertid = $this->_occupancycashering->addData($this->data);
	                $success_msg = 'Occupancy Cashiering added successfully.';
                    Session::put('OCCUPANCY_PRINT_CASHIER_ID',$lastinsertid);

                    $updateremotedata = array();
                    $transactionno = str_pad($this->data['top_transaction_id'], 6, '0', STR_PAD_LEFT);
                    $updateremotedata['topno'] = $transactionno;
                    $updateremotedata['orno'] = $this->data['or_no'];
                    $updateremotedata['ordate'] = date("Y-m-d");
                    $updateremotedata['payment_status'] = 1;
                    $updateremotedata['cashieramount'] = $this->data['total_amount'];
                    $getappid =  $this->_occupancycashering->getappidbytoptransaction($this->data['top_transaction_id']);
                    $this->_occupancycashering->updatelocaldata($getappid->transaction_ref_no,$updateremotedata);
                    $this->_occupancycashering->updateremotedata($getappid->transaction_ref_no,$updateremotedata);

	                $cashier_issue_no = str_pad($lastinsertid, 5, '0', STR_PAD_LEFT);
	                $cashier_batch_no = date('Y')."-".$cashier_issue_no;
	                $updatedata = array('cashier_issue_no'=>$cashier_issue_no,'cashier_batch_no'=>$cashier_batch_no);
	                $this->_occupancycashering->updateData($lastinsertid,$updatedata);
	                $uptdata = array('latestusedor' => $this->data['or_no']);
	                $this->_occupancycashering->UpdateOrused('3',$uptdata);

                    $cashierdetails['cashier_id'] = $lastinsertid;
                    $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
                    $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
                    if($request->input('total_paid_surcharge') > 0){
                        $cashierdetails['tfoc_id'] =$request->input('maintfoc_id');
                        $cashdata = $this->_occupancycashering->getCasheringsurchargeIds($request->input('maintfoc_id'));
                        $cashierdetails['ctc_taxable_amount'] ="0";
                        $cashierdetails['tfc_amount'] =$_POST['total_paid_surcharge'];
                        $cashierdetails['top_transaction_id'] = $this->data['top_transaction_id'];
                        $cashierdetails['or_no'] = $this->data['or_no'];
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
                        $this->_commonmodel->insertCashReceipt($value, $request->total_paid_amount, $request->or_no,$request->cashier_particulars);

                            $addincomedata = array();
                            $addincomedata['cashier_id'] = $lastinsertid;
                            $addincomedata['cashier_details_id'] = $cashierdetailid;
                            $addincomedata['tfoc_is_applicable'] = '4';
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
	            		$cashdata = $this->_occupancycashering->getCasheringIds($value);
	            		$cashierdetails['tfoc_id'] =$value;
	            		$cashierdetails['ctc_taxable_amount'] =$_POST['taxableamount'][$key];
	            		$cashierdetails['tfc_amount'] =$_POST['amount'][$key];
                        $cashierdetails['all_total_amount'] = $_POST['amount'][$key];
                        $cashierdetails['top_transaction_id'] = $this->data['top_transaction_id'];
	            		$cashierdetails['or_no'] = $this->data['or_no'];
                        $getortype = $this->_occupancycashering->GetOrtypeid('4');
                        $cashierdetails['ortype_id'] =  $getortype->ortype_id;
	            		$cashierdetails['cashier_remarks'] = $this->data['cashier_remarks'];
	            		$cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
            			$cashierdetails['sl_id'] = $cashdata->sl_id;

	            		$checkdetailexist =  $this->_occupancycashering->checkrecordisexist($value,$Cashierid);
	            		if(count($checkdetailexist) > 0){
	            				$this->_occupancycashering->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetails);
	            		} else{
                            $cashierdetails['cashier_year'] = date('Y');
                            $cashierdetails['cashier_month'] = date('m');
                            $cashierdetails['tfoc_is_applicable'] ='4';
                            $cashierdetails['payee_type'] = "1";
                            $cashierdetails['client_citizen_id'] =$this->data['client_citizen_id'];
	            			$cashierdetailid = $this->_occupancycashering->addCashierDetailsData($cashierdetails);
                            $this->_commonmodel->insertCashReceipt($value, $request->total_paid_amount, $request->or_no,$request->cashier_particulars);

                            $addincomedata = array();
                            $addincomedata['cashier_id'] = $lastinsertid;
                            $addincomedata['cashier_details_id'] = $cashierdetailid;
                            $addincomedata['tfoc_is_applicable'] = '4';
                            $addincomedata['taxpayer_name'] = $taxpayername;
                            $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                            $addincomedata['amount'] = $_POST['amount'][$key];
                            $addincomedata['tfoc_id'] = $value;
                            $addincomedata['fund_id'] = $cashdata->fund_id;
                            $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                            $addincomedata['sl_account_id'] = $cashdata->sl_id;
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
	              }
              }
              if(isset($_POST['desc'])){
               if(count($_POST['desc']) > 0){
                        $detailsoccupancy = array();
                        foreach ($_POST['desc'] as $key => $value){
                        $cashdata = $this->_occupancycashering->getCasheringIds($_POST['maintfoc_id']);
                        $detailsoccupancy['top_transaction_id'] =$this->data['top_transaction_id'];
                        $detailsoccupancy['cashier_id'] = $lastinsertid;
                        $detailsoccupancy['tfoc_is_applicable'] =  "3";
                        $detailsoccupancy['tfoc_id'] = $_POST['maintfoc_id'];
                        $detailsoccupancy['fees_description'] = $value;
                        $detailsoccupancy['tfc_amount'] = $_POST['amountnosave'][$key];
                        if(!empty($cashdata)){
                             $detailsoccupancy['agl_account_id'] = $cashdata->gl_account_id;
                             $detailsoccupancy['sl_id'] = $cashdata->sl_id;   
                        }
                        $checkdetailexist =  $this->_occupancycashering->checkeng_occupancy_details($value,$lastinsertid);
                        if(count($checkdetailexist) > 0){
                            $detailsoccupancy = array();
                            $detailsoccupancy['fees_description'] = $value;
                            $detailsoccupancy['tfc_amount'] = $_POST['amountnosave'][$key];
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
                            $addincomedata['amount'] = $_POST['amountnosave'][$key];
                            $addincomedata['tfoc_id'] = $_POST['maintfoc_id'];
                            $addincomedata['fund_id'] = $cashdata->fund_id;
                            $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                            $addincomedata['sl_account_id'] = $cashdata->sl_id;
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

                           // $this->_commonmodel->addcashierIncomeData($addincomedata);
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
	            				$this->_occupancycashering->updateCashierPaymentData($_POST['pid'][$key],$paymentdata);
	            		} else{
	            			$paymentdata['opayment_year'] = date('Y');
	            			$paymentdata['opayment_month'] = date('m');
	            			$this->_occupancycashering->addCashierPaymentData($paymentdata);
	            		}
	            	}
	            }
             }
                $arrData=array();  $top_transaction_id = $this->data['top_transaction_id'];
                $arrData['is_paid']=1;
                $arrData['updated_by']=\Auth::user()->id;
                $arrData['updated_at']= date('Y-m-d H:i:s');
                $this->_occupancycashering->updateTopTransaction($top_transaction_id,$arrData);
                $smsTemplate=SmsTemplate::where('id',65)->where('is_active',1)->first();
                $arrData = $this->_occupancycashering->getoccuappdata($getappid->transaction_ref_no);
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
                $this->data['occupancy_id'] = $getappid->transaction_ref_no;
                $this->addPaymentHistory($this->data);
	            return redirect()->route('occupancycashering.index')->with('success', __($success_msg));
	        }
	        return view('Engneering.casheringoccupancy.create',compact('data','clientsarr','arrFeeDetails','feesaray','arrgetTransactions','fundarray','bankaray','checkdetail','arrPaymentDetails','arrPaymentbankDetails','arrcancelreason'));
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
           $payment_history['occupancy_id']=$billdata['occupancy_id'];
               
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
        $arrPrev = $this->_occupancycashering->getPreviousIssueNumber();
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
        $data = $this->_occupancycashering->getBploApplictaiondetails($id);
        echo json_encode($data);
    }
}
