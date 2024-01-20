<?php

namespace App\Http\Controllers;
use App\Models\TreasurerCashierPos;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BploBussiPermitandLicenceController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $yeararr = array(""=>"Select Year ");
    public $accountnos = array(""=>"Select Account Number");
    public function __construct(){
		$this->_bplopermitlicence = new TreasurerCashierPos();
        $this->data = array('id'=>'','ba_cover_year'=>'2022','ba_business_account_no'=>'','order_number'=>'','totalamt_due'=>'','bas_id'=>'','totaltax_due'=>'','surcharge'=>'','interest'=>'','subtotal'=>'','otherdeduction'=>'','appliedtax_credit'=>'','nettax_due'=>'','checkamount_paid'=>'','cashamount_paid'=>'');
         foreach ($this->_bplopermitlicence->getaccountnumbers() as $val) {
            $this->accountnos[$val->id]=$val->ba_business_account_no;
        } 
    }
    public function index(Request $request)
    {   
        $yeararr= $this->yeararr;
        $year ='2020';
        for($i=0;$i<=10;$i++){
            $yeararr[$year] =$year; 
            $year = $year +1;
        }
        return view('bplopermitandlicence.index',compact('yeararr'));
    }
     public function getList(Request $request){
        $data=$this->_bplopermitlicence->getList($request);
        //print_r($data); exit;
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['ba_business_name']=$row->ba_business_name;
            $arr[$i]['order_number']=$row->order_number;
            $arr[$i]['totalamt_due']=$row->totalamt_due;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplopermitandlicence/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Manage permit & licence">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Payment"  data-title="Print Payment" class="print" id="'.$row->id.'">
                            <i class="ti ti-printer text-white"></i>
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
        $data = (object)$this->data; $arrFees = array();
        $accountnos = $this->accountnos;  $checkdetails =array();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = TreasurerCashierPos::find($request->input('id'));
            $checkdetails = $this->_bplopermitlicence->getPaymentdetialData($data->id);
        }

		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            //echo "<pre>"; print_r($this->data); exit;
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['totalamt_due'] = str_replace(',', '', $this->data['totalamt_due']);
            $this->data['totaltax_due'] = str_replace(',', '', $this->data['totaltax_due']);
            $this->data['subtotal'] = str_replace(',', '', $this->data['subtotal']);
            $this->data['nettax_due'] = str_replace(',', '', $this->data['nettax_due']);
            if($request->input('id')>0){
                $this->data['bas_id']= $_POST['bas_id'];
                $this->data['ba_business_account_no']= $_POST['accountnumber'];    
                $this->_bplopermitlicence->updateData($request->input('id'),$this->data);
                $success_msg = 'Business Permit & Licence updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['bas_id']= $this->data['ba_business_account_no'];
                $this->data['ba_business_account_no']= $_POST['accountnumber'];    
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_bplopermitlicence->addData($this->data);
                $success_msg = 'Business Permit & Licence added successfully.';
            }
             if(!empty($_POST['fund'])){
                 $loop = count($_POST['fund']);
                 $paymnetdetail = array();
              
                for($i=0; $i<$loop;$i++){
                    $paymnetdetail['tcp_id'] = $lastinsertid;
                    $paymnetdetail['fund'] = $_POST['fund'][$i];
                    $paymnetdetail['checknumber'] = $_POST['checkno'][$i];
                    $paymnetdetail['bankname'] = $_POST['bank'][$i];
                    $paymnetdetail['date'] = $_POST['date'][$i];
                    $paymnetdetail['checktype'] = $_POST['checktype'][$i];
                    $paymnetdetail['amount'] = $_POST['amount'][$i];
                    if(!empty($_POST['licence_payment_detailid'][$i])){
                        $this->_bplopermitlicence->updatePaymentdetialData($_POST['licence_payment_detailid'][$i],$paymnetdetail);
                    }else{
                        $this->_bplopermitlicence->addPaymentdetialData($paymnetdetail);
                    }
                }
             }
            return redirect()->route('bplopermitandlicence.index')->with('success', __($success_msg));
    	}
        return view('bplopermitandlicence.create',compact('data','arrFees','accountnos','checkdetails'));
	}

	public function getAssesmentData(Request $request){
		$id =$request->input('pid');
		$arrApplAss = $this->_bplopermitlicence->getApplicationAssessments($id);
        
        $allfees = $this->_bplopermitlicence->getAllFeeMaster();  $arrFeesDetails =array();
        foreach ($allfees as $keyf => $valf) {
            $arrFeesDetails[$valf->id] = $valf->fee_name;
        }
       // $arrFeesDetails = array('1'=>'Mayors Permit Fee','2'=>'Sanitary Fee','3'=>'Garbage Fee','4'=>'Fire Inspection Fee');
        $getassessmentdata = $this->_bplopermitlicence->getAssesmentData($id);
        $penaltyrates  = $this->_bplopermitlicence->getmasterpenaltyrates();
        $receiptno = $this->_bplopermitlicence->getORnumber();
        $ordernumber = $receiptno->serial_no_from;
        $accountno = $getassessmentdata[0]->ba_business_account_no;
        $suchargepercent = $penaltyrates->prate_surcharge_percent;  $anualinterestpercent = $penaltyrates->prate_annual_interest_percentage;
        
        $taxpayername = $getassessmentdata[0]->p_first_name." ".$getassessmentdata[0]->p_middle_name." ".$getassessmentdata[0]->p_family_name;
        $bussinessname = $getassessmentdata[0]->ba_business_name;
        
        $i=0;
        $arrFees=array();
        $arrtaxFees=array();
        foreach($arrFeesDetails as $key=>$val){
            $fee = 0;
            foreach($arrApplAss as $f_key=>$f_val){
                if($key==2){
                    $fee +=$f_val->permit_amount;
                }elseif($key==3){
                    $fee +=$f_val->sanitary_amount;
                }elseif($key==4){
                    $fee +=$f_val->garbage_amount;
                }
            }
            $arrFees[$i]['cover_year']=$getassessmentdata[0]->ba_cover_year;
            $arrFees[$i]['tax_type_fee']=$val;
            $arrFees[$i]['top_code']='0201';
            $arrFees[$i]['1_qutr_fee']='0.00';
            $arrFees[$i]['2_qutr_fee']='0.00';
            $arrFees[$i]['3_qutr_fee']='0.00';
            $arrFees[$i]['4_qutr_fee']='0.00';
            $arrFees[$i]['total_fee']=$fee;

            $arrtaxFees[$i]['cover_year']=$getassessmentdata[0]->ba_cover_year;
            $arrtaxFees[$i]['tax_type_fee']=$val;
            $arrtaxFees[$i]['top_code']='0201';
            $arrtaxFees[$i]['tax_amount']=$fee;
            $arrtaxFees[$i]['excess_tax']='0.00';
            $arrtaxFees[$i]['rate']='0.00';
            $arrtaxFees[$i]['sircharge']='0.00';
            $arrtaxFees[$i]['interest']='0.00';
            $arrtaxFees[$i]['totalTax']=$fee;
            $i++;
        }
        // echo "<pre>"; print_r($arrtaxFees); 
        $permitlicense = array();
        $html ="<table class='table'>"; $header ="<thead><tr><th>Tax Year</th><th>Particulars</th><th>Taxes/Fees Amount</th><th>Surcharge/Interest</th><th>Total Amount Due</th></tr></thead><tbody>";
        $html .=$header;  $totalfee =0;
        foreach ($arrtaxFees as $key => $value) {
            $totalfee +=$value['tax_amount'];
            $html .="<tr><td>".$value['cover_year']."</td><td>".$value['tax_type_fee']."</td><td>".$value['tax_amount']."</td><td>0.00</td><td>".$value['tax_amount']."</td></tr>";
        }
        $html .="<tr><td colspan='2'><b>Total Assessment....</b></td><td class='sky-blue'>".number_format($totalfee,2)."</td><td class='sky-blue'>".number_format($totalfee,2)."</td><td class='red'>".number_format($totalfee,2)."</td></tr>";
        $html .="</tbody></table>";
        //echo $html;
        $permitlicense['feetable'] = $html;
        $permitlicense['finalamt'] = number_format($totalfee,2);  $surcharge = ($totalfee * $suchargepercent) /100;  $surcharge = number_format($surcharge,2); 
        $anuualinterest =($totalfee * $anualinterestpercent)/100;  $anuualinterest = number_format($anuualinterest,2); 
        $subtotal = $totalfee + $surcharge + $anuualinterest;
        $permitlicense['taxpayer'] = $taxpayername;
        $permitlicense['bussinessname'] = $bussinessname;
        $permitlicense['surcharge'] = $surcharge;
        $permitlicense['interest'] = $anuualinterest;
        $permitlicense['subtotal'] = number_format($subtotal,2);
        $permitlicense['otherdeduction'] = '0.00';
        $permitlicense['taxcredit'] = '0.00';
        $permitlicense['date'] = $getassessmentdata[0]->ba_date_started;
        $permitlicense['nettaxdue'] = number_format($subtotal,2);
        $permitlicense['ornumber'] = $ordernumber;
        $permitlicense['accountno']= $accountno;
        $permitlicense['subtotalview'] = str_replace(',', '', $subtotal);
        $permitlicense['nettaxdueview'] = str_replace(',', '', $subtotal);
        $permitlicense['finalamtview'] = str_replace(',', '', $totalfee);

           
        echo json_encode($permitlicense); exit;
		
	}

    public function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    public function printPayment(Request $request){
           //echo "here"; exit;
             $id= $request->input('id');
             $checkdetails = $this->_bplopermitlicence->getPaymentdetialData($id);
            // echo "<pre>"; print_r($checkdetails); exit;
             $fbankname="";$fchecknumber="";$fdate=""; $sbankname="";$schecknumber="";$sdate="";
             $z=0;
             foreach ($checkdetails as $kcheck => $valcheck) {
                 if($z=='0'){
                      $fbankname =$valcheck->bankname; $fchecknumber=$valcheck->checknumber; $fdate=$valcheck->date;
                 }else{
                      $sbankname =$valcheck->bankname; $schecknumber=$valcheck->checknumber; $sdate=$valcheck->date;
                 }

                $z++;
             }
             $datapdf =$this->_bplopermitlicence->getPermitLicencedata($id);
             $ordernumber = $datapdf->order_number; $totalamt_due = $datapdf->totalamt_due; $interest = $datapdf->interest; $surcharge=$datapdf->surcharge;
             $arrApplAss = $this->_bplopermitlicence->getApplicationAssessments($datapdf->bas_id);
             $getassessmentdata = $this->_bplopermitlicence->getAssesmentData($id);
             $taxpayername = $getassessmentdata[0]->p_first_name." ".$getassessmentdata[0]->p_middle_name." ".$getassessmentdata[0]->p_family_name;
             $bussinessname = $getassessmentdata[0]->ba_business_name;
             $allfees = $this->_bplopermitlicence->getAllFeeMaster();  $arrFeesDetails =array();
             foreach ($allfees as $keyf => $valf) {
                $arrFeesDetails[$valf->id] = $valf->fee_name;
             }
             $i=0;   
             $arrFees=array();
             $arrtaxFees=array();
                foreach($arrFeesDetails as $key=>$val){
                    $fee = 0; $code ="";
                    foreach($arrApplAss as $f_key=>$f_val){
                        if($key==2){
                            $fee +=$f_val->permit_amount;
                            $code = $f_val->mayrol_permit_code;
                        }elseif($key==3){
                            $fee +=$f_val->sanitary_amount;
                             $code = $f_val->sanitary_code;
                        }elseif($key==4){
                            $fee +=$f_val->garbage_amount;
                             $code = $f_val->garbage_code;
                        }
                        elseif($key==14){
                            $fee =$interest + $surcharge;
                        }
                    }

                    $arrtaxFees[$i]['cover_year']=$getassessmentdata[0]->ba_cover_year;
                    $arrtaxFees[$i]['tax_type_fee']=$val;
                    $arrtaxFees[$i]['code']=$code;
                    $arrtaxFees[$i]['totalfee']=number_format($fee,2);
                    $arrtaxFees[$i]['totalfeecal']=$fee;
                    $i++;
            }
             $dynatablehtml ="";  $totalfee =0;
             foreach ($arrtaxFees as $key => $value) {
                $totalfee +=$value['totalfeecal'];
                $dynatablehtml .="<tr><td>".$value['tax_type_fee']."</td><td>".$value['code']."</td><td>".$value['totalfee']."</td></tr>";
            }
            $totalamt_dueinfigure = $this->getIndianCurrency($totalamt_due); 
            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/licensepaymentsave.html'));
            $logo = url('/assets/images/logo.png');
            $checked = url('/assets/images/checked-checkbox.png');
            $unchecked = url('/assets/images/unchecked-checkbox.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{CHKCASH}}',$checked, $html);
            $html = str_replace('{{ORNO}}',$ordernumber, $html);
            $html = str_replace('{{AGENCY}}',$bussinessname, $html);
            $html = str_replace('{{PAYERNAME}}',$taxpayername, $html);
            $html = str_replace('{{DATE}}',$getassessmentdata[0]->ba_date_started,$html);
            $html = str_replace('{{DYNAMICTABLE}}',$dynatablehtml, $html);
            $html = str_replace('{{TOTALAMT}}',number_format($totalfee,2), $html);
            $html = str_replace('{{TOTALDUE}}',number_format($totalamt_due,2), $html);
            $html = str_replace('{{AMTINFIGURE}}',$totalamt_dueinfigure, $html);
            $html = str_replace('{{FBANK}}',$fbankname, $html);
            $html = str_replace('{{FNUMBER}}',$fchecknumber, $html);
            $html = str_replace('{{FDATE}}',$fdate, $html);
            $html = str_replace('{{SBANK}}',$sbankname, $html);
            $html = str_replace('{{SNUMBER}}',$schecknumber, $html);
            $html = str_replace('{{SDATE}}',$sdate, $html);
            $html = str_replace('{{CHKMONEY}}',$unchecked, $html);
            $html = str_replace('{{CHKCHECK}}',$unchecked, $html);
            $mpdf->WriteHTML($html);
            $applicantname = date('ymdhis').$getassessmentdata[0]->p_first_name."licensepayment.pdf";
            $folder =  public_path().'/uploads/paymentreceipt/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/paymentreceipt/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/paymentreceipt/'.$applicantname);
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'class_code'=>'required|unique:psic_classes,class_code,'.$request->input('id'),
                // 'section_id'=>'required',
                // 'division_id'=>'required', 
                // 'group_id'=>'required', 
                // 'class_description'=>'required'
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
