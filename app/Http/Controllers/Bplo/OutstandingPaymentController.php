<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\OutstandingPayment;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use App\Models\Bplo\TreasurerAssessment;
use App\Models\BploAssessmentCalculationCommon;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;
use App\Models\Barangay;
use Illuminate\Support\Facades\Mail;

class OutstandingPaymentController extends Controller
{
    public $data = [];
    private $slugs;
    public $arrPayMode = [];
    public function __construct(){
        $this->_OutstandingPayment = new OutstandingPayment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_treasurerAssessment = new TreasurerAssessment(); 
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'','pm_id'=>'','period'=>'');
        $this->slugs = 'business-outstanding-payment';
        $this->arrPayMode= array(""=>"Please Select");
        foreach ($this->_OutstandingPayment->getPaymentMode() as $val) {
            $this->arrPayMode[$val->id]=$val->pm_desc;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arrMode =array(1=>'Annual',2=>'Bi-Annual',3=>'Quarterly');
        $arr = $this->_OutstandingPayment->getBarangayList();
        $arrBarngay = array(""=>"Please Select");
        foreach($arr AS $key=>$val){
            $arrBarngay[$val->id]=$val->brgy_name;
        }
        return view('Bplo.OutstandingPayment.index',compact('arrMode','arrBarngay'));
    }
    public function getList(Request $request){
        $data=$this->_OutstandingPayment->getList($request);
        $arr=array();
        $i="0"; 
        $pm_id =$request->input('pm_id');
        $period =$request->input('period');
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $ownar_name=$row->full_name;
            /* if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            } */
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['brgy_name']=$row->brgy_name;
            //$arr[$i]['email']=$ownar_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['app_code']=($row->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$row->app_code]:'';
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['tax_due']=number_format($row->sub_amount,2);
            $arr[$i]['penalty']=number_format($row->surcharge_fee,2);
            $arr[$i]['interest']=number_format($row->interest_fee,2);
            $arr[$i]['total_amount']=number_format($row->total_amount,2);
            $arr[$i]['is_approved'] = ""; 
            $arrResp = $this->_OutstandingPayment->checkEmailResponse($row->id,$row->app_code);
            if(isset($arrResp)){
                $dated = (!empty($arrResp->acknowledged_date))?'dated '.date("M d, Y h:i a",strtotime($arrResp->acknowledged_date)):'';
                $approveDtls=($arrResp->is_read==1)?'Acknowledged '.$dated:'';
                $approveDtls = wordwrap($approveDtls, 20, "<br>\n");
                $arr[$i]['is_approved'] = "<span class='showLess'>".$approveDtls."</span>";
            }
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-outstanding-payment/store?busn_id='.$row->id).'&pm_id='.$pm_id.'&period='.$period.'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage Business Tax: Outstanding Payment">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center sendEmail" title="Send Email" d_id="'.$row->id.'" email="'.$row->p_email_address.'">
                        <i class="ti-email text-white"></i>
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
        if($request->input('busn_id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        if($request->input('busn_id')>0 && $request->input('submit')==""){
            $data = $this->_OutstandingPayment->getEditDetails($request->input('busn_id'));
            if(isset($data)){
                $data->period=$request->input('period');
                if($request->input('pm_id')>0){
                    $data->pm_id=$request->input('pm_id');
                }
                $data->ownar_name=$data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
                if(!empty($data->suffix)){
                    $data->ownar_name .=", ".$data->suffix;
                }
            }
        }
        return view('Bplo.OutstandingPayment.create',compact('data'));
    }
    public function getOutstandingDetails(Request $request){
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $reqData=array();
        $reqData['busn_id']=$request->input('busn_id');
        $reqData['year']=$request->input('year');
        $reqData['pm_id']=$request->input('pm_id');
        $reqData['pm_id']=(empty($reqData['pm_id']))?1:$reqData['pm_id'];
        $reqData['assesment_period']=$request->input('assesment_period');
        $reqData['app_code']=$request->input('app_code');
        $assessDtlsIds = $this->_assessmentCalculationCommon->getPerticularOutstandingDetails($reqData);

        $perticularHtml="";
        if(isset($assessDtlsIds)){
            $finalAssesIds = explode(",",$assessDtlsIds->assessment_details_ids);
            $arrFinal = $this->_assessmentCalculationCommon->getFinalOutstandingDetails($finalAssesIds,$reqData['busn_id']);
            $finalTotal=0;
            foreach($arrFinal AS $key=>$val){
                $surchage_interest= $val->surcharge_fee+$val->interest_fee;
                $finalTotal +=$val->tfoc_amount+$surchage_interest;
                $perticularHtml .=$this->getPerticularHtml($val);
            }
            $perticularHtml .=$this->generateFinalTotalHtml($finalTotal);
        }
        $arrShecudle = $this->_assessmentCalculationCommon->getScheduleOutstandingDetails($reqData);
        $arrpm = config('constants.payModePartition')[$reqData['pm_id']];
        $payScheduleHtml="";
        if(count($arrShecudle)>0){
            $finalTotal=0;
            foreach($arrShecudle AS $key=>$val){
                $finalTotal +=$val->total_amount;
                $payScheduleHtml .=$this->getPaymentSchduleHtml($arrpm[$val->assessment_period],$val->assess_due_date,$val->payment_status,$val->sub_amount,$val->total_surcharge_interest_fee,$val->total_amount);
            }
            $payScheduleHtml .=$this->scheduleFinalHtml($finalTotal);
        }
        $data=array();
        $data['year'] = $reqData['year'];
        $data['perticular_details'] = $perticularHtml;
        $data['payment_schedule'] = $payScheduleHtml;
        $data['year_type'] = 1;
        $data['isShowTab'] = 1;
        $data['payment_mode'] = $this->arrPayMode[(int)$reqData['pm_id']];
        $html = view('Bplo.TreasurerAssessment.calculation',compact('data'));
        echo $html;

    }
    public function getPerticularHtml($val){
        $surchage_interest = $val->surcharge_fee+$val->interest_fee;
        $total = $val->tfoc_amount+$surchage_interest;
        $html ='<tr class="font-style">
            <td>'.$val->description.'</td>
            <td>'.number_format($val->tfoc_amount,2).'</td>
            <td>'.number_format($val->interest_fee,2).'</td>
            <td>'.number_format($val->surcharge_fee,2).'</td>
            <td>'.number_format($total,2).'</td>
        </tr>';
        return $html;
    }

    public function getPaymentSchduleHtml($period,$dueDate,$paymentStatus,$amount,$intSurAmt,$totalAmount){
        $paymentStatus = ($paymentStatus==1)?'<span style="color:green;">Paid</span>':'<span style="color:red;">Pending</span>';
        $html ='<tr class="font-style">
            <td>'.$period.'</td>
            <td>'.$dueDate.'</td>
            <td>'.$paymentStatus.'</td>
            <td>'.number_format($amount,2).'</td>
            <td>'.number_format($intSurAmt,2).'</td>
            <td>'.number_format($totalAmount,2).'</td>
        </tr>';
        return $html;
    }
    public function scheduleFinalHtml($finalTotal){
        $html ='<tr class="font-style">
            <td colspan="5" style="text-align: right;"><b>Total Amount</b></td>
            <td class="red" style="text-align: right !important;">'.number_format($finalTotal,2).'</td>
        </tr>';
        return $html;
    }
    public function generateFinalTotalHtml($finalTotal){
         $html ='<tr class="font-style">
            <td colspan="4" style="text-align: right;"><b>Grand Total</b></td>
            <td class="red">'.number_format($finalTotal,2).'</td>
        </tr>';
        return $html;
    }
    public function sendEmail(Request $request){
        $busn_id=$request->input('busn_id');
        $pm_id=$request->input('pm_id');
        $period=$request->input('period');
        $type=$request->input('type');
        $arrDtls = $this->_OutstandingPayment->getEditDetails($busn_id);
        if(isset($arrDtls)){
            if(!empty($arrDtls->p_email_address))
            {
                $emailResId = $this->addEmailResponseDtls($busn_id,$arrDtls->pm_id,$arrDtls->app_code);
                $data=array();
                $encrypt = $this->_commonmodel->encryptData($emailResId);
                $approve_url = url('/approveOutstandingPayment/'.$encrypt);
                $description = 'Your payment still pending, Please pay as soon as possible.';
                $html = $this->generateHtmlOrPdf($arrDtls->busn_id,$arrDtls->app_code,$pm_id,$period,$type);
                $html = str_replace("{APPROVE_URL}",$approve_url, $html);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->p_email_address, $html);
                
                $data['to_name']=$arrDtls->rpo_first_name;
                $data['to_email']=$arrDtls->p_email_address;
                //$data['to_email']='tushalburungale11@gmail.com';
                if($type=='deliqnuencyOutstanding'){
                    $html = str_replace("OUTSTANDING",'DELINQUENCY AND OUTSTANDING', $html);
                    $data['subject']='Delinquency and Outstanding Payment Notice';
                }else{
                    $data['subject']='Outstanding Payment Notice';
                }
                $data['message'] = $html;
                Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                }); 
                
            }
        }
    }
    public function addEmailResponseDtls($busn_id,$pm_id,$app_code){
        $arrExist = $this->_OutstandingPayment->checkExistEmailDlts($busn_id,$pm_id,$app_code);
        $arr['year']=date("Y");
        $arr['busn_id']=$busn_id;
        $arr['app_code']=$app_code;
        $arr['pm_id']=(int)$pm_id;
        $arr['updated_at']=date('Y-m-d H:i:s');
        if(isset($arrExist)){
            $this->_OutstandingPayment->upateEmailResponse($arrExist->id,$arr);
            $last_id=$arrExist->id;
        }else{
            $arr['created_at']=date('Y-m-d H:i:s');
            $last_id = $this->_OutstandingPayment->addEmailResponse($arr);
            
        }
        return $last_id;
    }
    public function generateHtmlOrPdf($bus_id,$app_code=0,$pm_id=0,$period=0,$type=''){
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();

        $html="";
        $finalData=array('feeName'=>'','subtotal'=>0,'surcharge'=>0,'interest'=>0,'finaltotal'=>0);
        $arrPerFixed = array("0"=>"","1"=>"%","2"=>"F.A.");
        $cnt=1;
        $periodCoverd="";
        $finalAssessIds="";

        // This For Current year
        $yr = date("Y");
        $arrFinal = $this->_OutstandingPayment->getFinalAssessementDetails($bus_id,$yr,$app_code,$pm_id,$period);

        if(count($arrFinal)>0){
            $html .=$this->generateTaxOrderHtml($yr,'Year'); 
            $subtotal=0;$surcharge=0;$interest=0;$finaltotal=0;
            foreach($arrFinal AS $key=>$final){
                $finalAssessIds .=",".$final->id;
                $pm_id = $final->payment_mode;
                $assessment_period = $final->assessment_period;
                $finalAssesIds = explode(",",$final->assessment_details_ids);
                $arrAssDtls = $this->_assessmentCalculationCommon->getFinalOutstandingDetails($finalAssesIds,$bus_id);
                $data= array('period'=>'','perticular'=>'','subtotal'=>'','surcharge'=>'','surcharge_rate'=>'','interest'=>'','interest_rate'=>'','finaltotal'=>'');
                $data['period']=config('constants.payModePartitionShortCut')[$pm_id][$assessment_period];
                 
                foreach($arrAssDtls AS $a_key=>$a_val){
                    $interest_rate = ($a_val->interest_rate>0)?$a_val->interest_rate.$arrPerFixed[(int)$a_val->interest_rate_type]:'-';
                    $surcharge_rate = ($a_val->surcharge_rate>0)?$a_val->surcharge_rate.$arrPerFixed[(int)$a_val->surcharge_rate_type]:'-';
                    $data['perticular'] .=$a_val->description.'<br>';
                    $data['subtotal'] .=number_format($a_val->tfoc_amount,2).'<br>';
                    $data['surcharge'] .=number_format($a_val->surcharge_fee,2).'<br>';
                    $data['surcharge_rate'] .=$surcharge_rate.'<br>';
                    $data['interest'] .=number_format($a_val->interest_fee,2).'<br>';
                    $data['interest_rate'] .= $interest_rate.'<br>';

                    $total = $a_val->tfoc_amount+$a_val->surcharge_fee+$a_val->interest_fee;
                    $data['finaltotal'] .=number_format($total,2).'<br>';

                    $finalData['subtotal'] +=$a_val->tfoc_amount;
                    $finalData['surcharge'] +=$a_val->surcharge_fee;
                    $finalData['interest'] +=$a_val->interest_fee;
                    $finalData['finaltotal'] +=$total;
                }
                $html .=$this->generateTaxOrderHtml($data,'Perticulars'); 
            }
        }
        
        // This For Previous year
        if($type=='deliqnuencyOutstanding'){
            $year = date("Y");
            $intilizeYear = date("Y")-1;
            $paymentYear = $this->getPaymentYear($bus_id,'',$app_code);
            //$paymentYear=2021; // For testing
            if($paymentYear>0){
                $html.='<tr class="sub-details">
                    <td></td>
                    <td colspan="8" class="year-title" style="text-align: left;">Following Taxes For Previous Payments</td>
                </tr>';

                $year =$paymentYear;
                for($i=$intilizeYear; $year<=$i;$i--){
                    $yr = $i;
                    $arrFinal = $this->_OutstandingPayment->getFinalAssessementDetails($bus_id,$yr,$app_code);
                    if(count($arrFinal)>0){
                        $isDataAvailable=0;
                        $feesHtml='';
                        $subtotal=0;$surcharge=0;$interest=0;$finaltotal=0;
                        foreach($arrFinal AS $key=>$final){
                            $finalAssessIds .=",".$final->id;
                            $pm_id = $final->payment_mode;
                            $assessment_period = $final->assessment_period;

                            $arrAssDtls = $this->_OutstandingPayment->getTaxAssessementDetails($bus_id,$yr,$pm_id,$assessment_period,$final->app_code);
                            $data= array('period'=>'','perticular'=>'','subtotal'=>'','surcharge'=>'','surcharge_rate'=>'','interest'=>'','interest_rate'=>'','finaltotal'=>'');
                            $data['period']=config('constants.payModePartitionShortCut')[$pm_id][$assessment_period];
                            if(count($arrAssDtls)>0){
                                $isDataAvailable++;
                                foreach($arrAssDtls AS $a_key=>$a_val){
                                    $interest_rate = ($a_val->interest_rate>0)?$a_val->interest_rate.$arrPerFixed[(int)$a_val->interest_rate_type]:'-';
                                    $surcharge_rate = ($a_val->surcharge_rate>0)?$a_val->surcharge_rate.$arrPerFixed[(int)$a_val->surcharge_rate_type]:'-';
                                    $data['perticular'] .=$a_val->description.'<br>';
                                    $data['subtotal'] .=number_format($a_val->tfoc_amount,2).'<br>';
                                    $data['surcharge'] .=number_format($a_val->surcharge_fee,2).'<br>';
                                    $data['surcharge_rate'] .=$surcharge_rate.'<br>';
                                    $data['interest'] .=number_format($a_val->interest_fee,2).'<br>';
                                    $data['interest_rate'] .= $interest_rate.'<br>';

                                    $total = $a_val->tfoc_amount+$a_val->surcharge_fee+$a_val->interest_fee;
                                    $data['finaltotal'] .=number_format($total,2).'<br>';

                                    $finalData['subtotal'] +=$a_val->tfoc_amount;
                                    $finalData['surcharge'] +=$a_val->surcharge_fee;
                                    $finalData['interest'] +=$a_val->interest_fee;
                                    $finalData['finaltotal'] +=$total;
                                }
                                $feesHtml .=$this->generateTaxOrderHtml($data,'Perticulars'); 
                            }
                        }
                        if($isDataAvailable>0){
                            $html .=$this->generateTaxOrderHtml($yr,'Year'); 
                            $html .=$feesHtml;
                        }
                    }
                }
            }
        }

        $finalData['feeName'] = "BUSINESS/PERMIT";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 

        $finalData['feeName'] = "GRAND TOTAL";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 
        
        $arrBussDtls = $this->_treasurerAssessment->getBusinessDetails($bus_id);

        $this->_Barangay = new Barangay();
        $address=$this->_Barangay->findDetails($arrBussDtls->busn_office_main_barangay_id);

        $arrPayment= $this->_treasurerAssessment->getPaidPaymentDate($bus_id);

        $data = array();
        $data['activityDetails']=$this->getActivityDetails($bus_id,$app_code);
        $ownar_name=$arrBussDtls->rpo_first_name.' '.$arrBussDtls->rpo_middle_name.' '.$arrBussDtls->rpo_custom_last_name;
        if(!empty($arrBussDtls->suffix)){
            $ownar_name .=", ".$arrBussDtls->suffix;
        }   

        $data['ownar_name']=$ownar_name;
        $data['busns_id_no']=$arrBussDtls->busns_id_no;
        $data['busn_name']=$arrBussDtls->busn_name;
        $data['app_type']=config('constants.arrBusinessApplicationType')[(int)$app_code];
        $data['address']=$address;
        $data['period_coverd']=$periodCoverd;
        $data['total_employee']=$arrBussDtls->busn_employee_total_no;
        $data['mobile']=$arrBussDtls->p_mobile_no;
        $data['date_assessed']=date("d M Y",strtotime($arrBussDtls->created_at));
        
        $data['last_amount_paid']='';
        if(isset($arrPayment)){
            $data['last_amount_paid']=number_format($arrPayment->total_amount,2);
        }
        // ************* Start Display Content Details ************************
        $data['isShowBtn']=1;
        $data['username']=$arrBussDtls->rpo_custom_last_name;
        $Finalhtml = view('mails.outstandingPaymentEmail',compact('html','data'));
        return $Finalhtml;
        // ************* End Display Content Details ************************
    }
    public function getPaymentYear($bus_id,$assType="",$app_code=0){
        $this->_TreasurerAssessmentCont = new TreasurerAssessmentController(); 
        $year = $this->_TreasurerAssessmentCont->getPaymentYear($bus_id,$assType="",$app_code=0);
        $prevYear=0;
        if($year!=date("Y")){
            $prevYear = $year;
        }
        return $prevYear;
    }
    public function generateTaxOrderHtml($data,$type){
        $html="";
        if($type=="Year"){
            $html.='<tr class="year-dtls">
                <td></td>
                <td colspan="7" class="year-title" style="text-align: left;">Tax for '.(int)$data.'</td>
            </tr>';
        }elseif($type=="Perticulars"){
            $html.='<tr class="perticular-dtls">
                <td style="padding-right:10px;">'.$data['period'].'</td>
                <td style="text-align:left;" class="align-left">'.$data["perticular"].'</td>
                <td>'.$data["subtotal"].'</td>
                <td>'.$data["surcharge"].'</td>
                <td>'.$data["surcharge_rate"].'</td>
                <td>'.$data["interest"].'</td>
                <td>'.$data["interest_rate"].'</td>
                <td>'.$data["finaltotal"].'</td>
            </tr>';
        }
        elseif($type=="Total"){
            $html.='<tr class="sub-details">
                <td></td>
                <td colspan="2" style="text-align: right;padding-top: 10px;padding-bottom: 10px;">'.$data["feeName"].' :&nbsp;&nbsp;&nbsp; <b>'.number_format($data["subtotal"],2).'</b></td>
                <td><b>'.number_format($data["surcharge"],2).'</b></td>
                <td></td>
                <td><b>'.number_format($data["interest"],2).'</b></td>
                <td></td>
                <td><b>'.number_format($data["finaltotal"],2).'</b></td>
            </tr>';
        }
        return $html;
    }
    public function getActivityDetails($bus_id,$app_code){
        $arrAct = $this->_treasurerAssessment->getActivityDetails($bus_id,$app_code);
        $html = "";
        foreach($arrAct AS $key=>$val){
            $html.="<tr>
                <td><b>".ucfirst($val->subclass_description)."</b></td>
                <td>".number_format($val->busp_capital_investment,2)."</td>
                <td>".number_format($val->busp_non_essential,2)."</td>
                <td>".number_format($val->busp_essential,2)."</td>
            </tr>";
        }
        return $html;

    }

    public function approveOutstandingPayment(Request $request,$encrypt){
        $id = $this->_commonmodel->decryptData($encrypt);
        if($id>0){
            $arrData['is_read']=1;
            $arrData['acknowledged_date']=date('Y-m-d H:i:s');
            $this->_OutstandingPayment->upateEmailResponse($id,$arrData);
            return view('errors.DelinquencyThankyou');
        }
    }
}