<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\TreasurerAssessment;
use App\Models\BploAssessmentCalculationCommon;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Session;
use DB;
use File;
use DateTime;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class TreasurerAssessmentController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;
    public $arrPayMode = [];
    public $policyName;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->_treasurerAssessment = new TreasurerAssessment(); 
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->_Barangay = new Barangay();
        $this->_commonmodel = new CommonModelmaster();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','cctype_desc'=>'','cctype_remarks'=>'');  
        $this->slugs = 'treasurer/assessment';
        $this->arrPayMode= array(""=>"Please Select");
        foreach ($this->_treasurerAssessment->getPaymentMode() as $val) {
            $this->arrPayMode[$val->id]=$val->pm_desc;
        }
        $this->policyName = $this->_treasurerAssessment->getReAssessModeDtls();
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arrYears=array(""=>"Select Year");
        $arrYrs = $this->_treasurerAssessment->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $arrYears[$val->assess_year] =$val->assess_year;
        }
        return view('Bplo.TreasurerAssessment.index',compact('arrYears'));
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_treasurerAssessment->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $year=(!empty($request->input('year')))?$request->input('year'):date("Y");
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $actions = '';
            $serch_status =config('constants.arrBusinessApplicationStatus');
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arrOtherDtls = $this->_treasurerAssessment->checkAllTopPaidTransaction($row->id,$row->app_code,$year);
            $topStatus='';
            $assIDs=array();
            foreach($arrOtherDtls as $p_val){
                if(!empty($p_val->top_transaction_no)){
                    $assIDs[]=$p_val->id;
                }
                $topStatus .=$p_val->top_transaction_no;
                if($p_val->payment_status==1){
                    $topStatus .=' - <span style="color:green;">Paid</span>';
                }else{
                    $topStatus .=' - <span style="color:red;">Pending</span>';
                }
                $topStatus .="<br>";
            }
            if(empty($topStatus)){
                $topStatus ='<span style="color:red;">Pending</span>';
            }
            $arr[$i]['top_no']=$topStatus;
            $arr[$i]['owner']=$row->full_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $app_code = (!isset($row->app_code))?'':config('constants.arrBusinessApplicationType')[(int)$row->app_code];
            $arr[$i]['app_type']=$app_code;
			$dt = new DateTime($row->created_at);
            $arr[$i]['app_date']= $dt->format('Y-m-d');
			//$arr[$i]['app_date']= Carbon::parse($row->created_at)->format('d-M-Y');
            $arr[$i]['busn_app_status']= $serch_status[$row->busn_app_status];
            $arr[$i]['app_method']=$row->pm_desc;

            if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
                $actions .= '<div class="action-btn bg-info ms-2">
                   <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasurer/assessment/asses?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Assessment"  data-title="Assess Now" data-backdrop="static" data-keyboard="false">
                    <i class="ti ti-currency-dollar text-white"></i>
                    </a>
               </div>';

                if(count($assIDs)>0){
                    $ids = implode(",", $assIDs);
                    $parms = '?id='.$this->_commonmodel->encryptData((int)$row->id).'&year='.$this->_commonmodel->encryptData((int)$row->busn_tax_year).'&app_code='.$this->_commonmodel->encryptData((int)$row->app_code).'&ids='.$this->_commonmodel->encryptData($ids);
                    $actions .= '<div class="action-btn bg-info ms-2">
                       <a class="mx-3 btn btn-sm  align-items-center" href="'.url('treasurer/assessment/generateORPaymentPdf'.$parms).'" title="Assessment" target="_blank">
                        <i class="ti-printer text-white"></i>
                        </a>
                    </div>';
                }
            }
            $arr[$i]['action']=$actions;
            $i++;
        }
       
        $totalRecords=$data['data_cnt'];
        $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    public function assessNow(Request $request){
        $bus_id = $request->input('id');
        $data = $this->_treasurerAssessment->getBusinessDetails($request->input('id'));
        $data->ownar_name=$data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
        if(!empty($data->suffix)){
            $data->ownar_name .=", ".$data->suffix;
        }   
        /*$prevTrans = $this->_treasurerAssessment->checkAllTopPaidTransaction($bus_id,$data->app_code,date("Y"));
        $topStatus='';
        foreach($prevTrans as $p_val){
            $topStatus .=$p_val->transaction_no;
            if($p_val->is_paid){
                $topStatus .='[<span style="color:green;">Paid</span>]';
            }else{
                $topStatus .='[<span style="color:red;">Pending</span>]';
            }
            $topStatus .="<br>";
        }
        if(empty($topStatus)){
            $topStatus ='<span style="color:red;">Pending</span>';
        }
        $transaction_no = $topStatus;*/
        $transaction_no ='';
        $arrPayMode = $this->arrPayMode;
        $data->pm_id = (!$data->pm_id)?1:$data->pm_id;
        $arrpm = config('constants.payModePartition')[$data->pm_id];
        if($data->app_code==1){
            unset($arrPayMode[2]);
            unset($arrPayMode[3]);
            $arrpm = config('constants.payModePartition')[1];
        }
        $arrModePartition= array(""=>"Please Select");
        if(count($arrpm)>0){
            $arrModePartition = $arrpm;
        }
        $policyName = $this->policyName;
        return view('Bplo.TreasurerAssessment.asses',compact('arrPayMode','data','arrModePartition','policyName','transaction_no'));
    }
    public function getPeriodDetails(Request $request){
        $pm_id = $request->input('pm_id');
        $pm_id = (!$pm_id)?1:$pm_id;
        $arrpm = config('constants.payModePartition')[$pm_id];
        $html="<option value=''>Please Select</option>";
        foreach($arrpm AS $key=>$val){
            $selected = ($key==1)?'selected':'';
            $html .="<option value='".$key."' ".$selected.">".$val."</option>";
        }
        echo $html;
    }
    public function getPaymentYear($bus_id,$assType="",$app_code=0){
        $arrPayment= $this->_treasurerAssessment->getPaidPaymentDate($bus_id);
        $year=0;
        if(isset($arrPayment)){
            $dayMonth=date("md",strtotime($arrPayment->assess_due_date));
            $year = $arrPayment->assess_year;
            if($app_code==3){
                return $year;
            }
            if($arrPayment->payment_mode==1){
                $year = $year+1;
            }elseif($arrPayment->payment_mode==2 && $dayMonth>=0720){
                $year = $year+1;
            }elseif($arrPayment->payment_mode==3 && $dayMonth>=1020){
                $year = $year+1;
            }else{
                if($assType=="saveData"){
                    $year = $year+1;
                }
            }
        }else{
            $arr= $this->_treasurerAssessment->getPendingAssessedYear($bus_id);
            if(isset($arr)){
                $year = $arr->assess_year;
            }
        }
        return $year;
    }
    public function getAssessmentDetails(Request $request){
        $this->displayOrReAssessment($request,'display');
    }
    public function displayOrReAssessment($request,$assType){
        $bus_id = $request->input('id');
        $retire_id = $request->input('retire_id');
        $year_type = $request->input('year_type');
        $year = $request->input('year');
        $isIndvidual = $request->input('isIndvidual');
        $app_code = $request->input('app_code');
        $pm_id = (int)$request->input('pm_id');
        $pm_id = (!$pm_id)?1:$pm_id;

        $this->_assessmentCalculationCommon->arrFinalAssessDtls=array();
        $this->_assessmentCalculationCommon->assesEndDate=date("Y-m-d");

        $assesment_period = (int)$request->input('assesment_period');
        $assesment_period = ($assesment_period=='')?1:$assesment_period;
        if((!$isIndvidual && $year_type==2) || $app_code==3){
            $paymentYear = $this->getPaymentYear($bus_id,$assType,$app_code);
            if($paymentYear>0){
                $year =$paymentYear;
            }
            $startYear = date("Y");
            if($assType!="saveData" && $app_code!=3){
                $startYear= date("Y")-1;
            }elseif($request->input('isSaveDelinquencyData')==1){
                $startYear= date("Y")-1; //Delinquency always create previous year
            }
            
            for($i=$startYear; $year<=$i;$i--){
                $yr = $i;
                $arrPrevDtls = $this->_treasurerAssessment->getPreviousYearDetails($bus_id,$yr);
                if(isset($arrPrevDtls)){
                    if($assType=="saveData"){
                        if($this->policyName==2 && $yr!=date("Y")){
                            $pm_id = $arrPrevDtls->payment_mode;
                        }
                    }else{
                        $pm_id = $arrPrevDtls->payment_mode;
                    }
                    if($app_code<=2){
                        $app_code = $arrPrevDtls->app_code;
                    }
                    if($app_code==1){
                        $pm_id=1; //Anually for new Application
                    }
                }
                if($app_code==3){
                    $pm_id=1; //Anually for Retire Application
                }

                $this->_assessmentCalculationCommon->data=array();
                $this->_assessmentCalculationCommon->calculateAssessmentDetails($bus_id,$app_code,$pm_id,$assType,$yr,0,$retire_id);
                if($assType=='display'){
                    $isShowTab=($year==$i)?1:0;
                    $this->getAllAssessmentDtls($yr,$app_code,$bus_id,$pm_id,1,$isIndvidual,$year_type,$isShowTab);
                }
            }
        }else{
            $this->_assessmentCalculationCommon->data=array();
            $this->_assessmentCalculationCommon->calculateAssessmentDetails($bus_id,$app_code,$pm_id,$assType,$year,0,$retire_id);
            if($assType=='display'){
                $this->getAllAssessmentDtls($year,$app_code,$bus_id,$pm_id,$assesment_period,$isIndvidual,$year_type,1);
            }
        }
        //Update Delinquency Details VIMP
        if($assType=='saveData' && $request->input('isSaveDelinquencyData')==1){
            $this->_assessmentCalculationCommon->updateDelinquencyDetails($bus_id,$app_code,$request->input('year'),$retire_id);
        }

    }
    public function getAllAssessmentDtls($year,$app_code,$bus_id,$pm_id,$assesment_period,$isIndvidual,$year_type,$isShowTab){
        $dividedBy=($pm_id==3)?4:$pm_id;
        $perticularHtml = $this->generatePerticularDetails($bus_id,$app_code,$pm_id,$year,$dividedBy,$assesment_period);
        $payScheduleHtml = $this->generatePaymentScheduleDetails($bus_id,$app_code,$pm_id,$year,$dividedBy,$year_type);
        if($isIndvidual==1){
            echo $perticularHtml.'#######'.$payScheduleHtml;
        }else{
            $data=array();
            $arrModePartition= array(""=>"Please Select");
            $arrpm = config('constants.payModePartition')[$pm_id];
            if(count($arrpm)>0){
                $arrModePartition = $arrpm;
            }
            $data['year'] = $year;
            $data['perticular_details'] = $perticularHtml;
            $data['payment_schedule'] = $payScheduleHtml;
            $data['arrPayMode'] = $this->arrPayMode;
            $data['arrModePartition'] = $arrModePartition;
            $data['pm_id'] = $pm_id;
            $data['year_type'] = $year_type;
            $data['isShowTab'] = $isShowTab;
            $data['payment_mode'] = $this->arrPayMode[(int)$pm_id];
            $html = view('Bplo.TreasurerAssessment.calculation',compact('data'));
            echo $html;
        }
    }
    public function generatePaymentScheduleDetails($bus_id,$app_code,$pm_id,$year,$dividedBy,$year_type=0){
        $payScheduleHtml="";
        $payScheduleFinal=0;
        $arrpm = config('constants.payModePartition')[$pm_id];
        $arrPaidDtls = $this->_assessmentCalculationCommon->checkPaidFinalAssessmentDetails($bus_id,$year,$app_code);
        if(count($arrPaidDtls)>0){
            foreach ($arrPaidDtls as $key => $val){
                $totalAmount = $val->total_amount;
                $payScheduleFinal +=$totalAmount;
                $transaction_no=$val->top_transaction_no;
                /*$arrtop = $this->_treasurerAssessment->checkTopPaidTransaction($bus_id,$app_code,$year,$pm_id,$val->assessment_period);
                if(isset($arrtop)){
                    $transaction_no = $arrtop->transaction_no;
                }
                */
                $payScheduleHtml .=$this->getPaymentSchduleHtml($arrpm[$val->assessment_period],$val->assess_due_date,$val->payment_status,$val->sub_amount,$val->total_surcharge_interest_fee,$totalAmount,$year_type,$val->assessment_period,$transaction_no);
            }
        }else{
            $arrAssesment = $this->_assessmentCalculationCommon->data;
            if(isset($arrAssesment)){
                $cnt=0;
                foreach($arrpm as $key=>$val){
                    $amount = 0;
                    $intSurAmt = 0;
                    $dueDate=$this->_assessmentCalculationCommon->getPaymentDueDate($app_code,$pm_id,$key,$year); 
                    $isPaid=$this->_treasurerAssessment->checkPaidRecord($bus_id,$pm_id,$key,$year,$app_code);
                    $isValidDate = $this->_assessmentCalculationCommon->checkExpireDueDate($dueDate);
                    foreach ($arrAssesment as $a_key => $a_val){
                        $arrDelData['tfoc_id']=$a_val['tfoc_id'];
                        $arrDelData['busn_id']=$bus_id;
                        $arrDelData['year']=$year;
                        $arrDelData['app_code']=$app_code;
                        $arrDelData['pm_id']=$pm_id;
                        $arrDelData['subclass_id']=$a_val['subclass_id'];
                        $arrDelData['pap_id']=$key;
                        $isDeleted = $this->_assessmentCalculationCommon->checkDeletedFee($arrDelData);
                        if(!isset($isDeleted)){
                            $isPartition=0;
                            if($cnt==0){
                                if($a_val['tfoc_divided_fee']==1){
                                    $isPartition=1;
                                }else{
                                    $amount +=$a_val['tfoc_amount'];
                                    if(!$isValidDate){
                                        if($a_val['assess_is_surcharge']==1 && isset($a_val['surcharge_fee'])){
                                            $intSurAmt +=$a_val['surcharge_fee'];
                                        }
                                        if($a_val['assess_is_interest']==1 && isset($a_val['interest_fee'])){
                                            $intSurAmt +=$a_val['interest_fee'];
                                        }
                                    }
                                }
                            }else{
                                if($a_val['tfoc_divided_fee']==1){
                                    $isPartition=1;
                                }
                            }
                            if($isPartition==1){
                                $amount +=$a_val['tfoc_amount']/$dividedBy;
                                if(!$isValidDate){
                                    if($a_val['assess_is_surcharge']==1 && isset($a_val['surcharge_fee'])){
                                        $intSurAmt +=$a_val['surcharge_fee']/$dividedBy;
                                    }
                                
                                    if($a_val['assess_is_interest']==1 && isset($a_val['interest_fee'])){
                                        $intSurAmt +=$a_val['interest_fee']/$dividedBy;
                                    }
                                }
                            }
                        }
                    }
                    $totalAmount = $amount+$intSurAmt;
                    $payScheduleFinal +=$totalAmount;

                    $transaction_no=0;
                    $arrtop = $this->_treasurerAssessment->getTopTransactionFinalAsess($bus_id,$app_code,$year,$pm_id,$key);
                    if(isset($arrtop)){
                        $transaction_no = $arrtop->top_transaction_no;
                    }
                    $payScheduleHtml .=$this->getPaymentSchduleHtml($val,$dueDate,$isPaid,$amount,$intSurAmt,$totalAmount,$year_type,$key,$transaction_no);

                    $cnt++;
                }
            }
        }
        $payScheduleHtml .='<tr class="font-style">
            <td colspan="5" style="text-align: right;"><b>Total Amount</b></td>
            <td class="red" style="text-align: right !important;">'.number_format($payScheduleFinal,2).'</td>
        </tr>
        <input id="chk_hidden_final_total" name="chk_hidden_final_total" type="hidden" value="'.$payScheduleFinal.'">';
        return $payScheduleHtml;
    }
    public function getPaymentSchduleHtml($period,$dueDate,$paymentStatus,$amount,$intSurAmt,$totalAmount,$year_type=0,$assesment_period=0,$transaction_no=0){
        $periodDtls = $period;
        if($year_type==1 && $paymentStatus!=1){
            $periodDtls = '<input type="checkbox" class="period_checkbox" name="period_checkbox[]" style="margin-right:10px;" id="'.$assesment_period.'period" value="'.$assesment_period.'"><span id="'.$assesment_period.'periodLebel">'.$period.'</span>';
        }
        $top="";
        if($transaction_no>0){
            $top = $transaction_no.' - ';
        }
        $paymentStatus = ($paymentStatus==1)?$top.'<span style="color:green;">Paid</span>':$top.'<span style="color:red;">Pending</span>';
        $payScheduleHtml ='<tr class="font-style">
            <td>'.$periodDtls.'</td>
            <td>'.$dueDate.'</td>
            <td>'.$paymentStatus.'</td>
            <td>'.number_format($amount,2).'</td>
            <td>'.number_format($intSurAmt,2).'</td>
            <td>'.number_format($totalAmount,2).'</td>
        </tr>
        <input id="chk_hidden_final_total_'.$assesment_period.'" name="chk_hidden_final_total_'.$assesment_period.'" type="hidden" value="'.$totalAmount.'">';
        return $payScheduleHtml;
    }

    public function getPerticularHtml($feeName,$amount,$totalInterest,$totalSurcharges,$totalAmount,$extraData=array()){
        $deleteHtml='';
        $strike='';
        $is_deleteFee = isset($_REQUEST['is_deleteFee'])?$_REQUEST['is_deleteFee']:0;
        if(!empty($extraData)){
            if(isset($extraData['is_deleted'])){
                $tfoc_id = (isset($extraData['tfoc_id']))?$extraData['tfoc_id']:0;
                $attributes = ' tfoc_id="'.$tfoc_id.'"  app_code="'.$extraData['app_code'].'" pm_id="'.$extraData['pm_id'].'" subclass_id="'.$extraData['subclass_id'].'" year="'.$extraData['year'].'"';
                if($extraData['is_deleted']=='deleted'){
                    if(!$extraData['isFinalAssessment'] && $is_deleteFee==1){
                        $deleteHtml = '&nbsp;&nbsp;<a href="javascript:void(0)" class="mx-3 btn btn-sm  ti-reload restoreDeleteAssesFee" style="color:#20B7CC;" type="restore" title="Undo" '.$attributes.'></a>';
                    }
                    $strike="style='text-decoration: line-through;color:red;'";
                }elseif($extraData['is_deleted']=='notDeleted'){
                    $deleteHtml = '&nbsp;&nbsp;<a href="javascript:void(0)" style="color:red;" class="restoreDeleteAssesFee" type="delete" '.$attributes.'><i class="ti-trash"></i></a>';
                }
            }
        }

        $perticularHtml ='<tr class="font-style" '.$strike.'>
            <td>'.$feeName.$deleteHtml.'</td>
            <td>'.number_format($amount,2).'</td>
            <td>'.number_format($totalInterest,2).'</td>
            <td>'.number_format($totalSurcharges,2).'</td>
            <td>'.number_format($totalAmount,2).'</td>
        </tr>';
        return $perticularHtml;
    }
    public function generatePerticularDetails($bus_id,$app_code,$pm_id,$year,$dividedBy,$assesment_period){
        $perticularHtml="";
        $peticularFinalTotal=0;
        $arrPaidDtls = $this->_assessmentCalculationCommon->checkPaidAssessmentDetails($bus_id,$year,$pm_id,$assesment_period,$app_code);
        if(count($arrPaidDtls)>0){
            foreach ($arrPaidDtls as $key => $val){
                $feeName = $this->_treasurerAssessment->getSubsidiaryLedgerName($val->sl_id);
                $totalAmount =$val->tfoc_amount+$val->surcharge_fee+$val->interest_fee;
                $perticularHtml.= $this->getPerticularHtml($feeName,$val->tfoc_amount,$val->interest_fee,$val->surcharge_fee,$totalAmount);
                $peticularFinalTotal +=$totalAmount;
            }
        }else{
            $dueDate=$this->_assessmentCalculationCommon->getPaymentDueDate($app_code,$pm_id,$assesment_period,$year); 
            $isValidDate = $this->_assessmentCalculationCommon->checkExpireDueDate($dueDate);
            $arrAssesment = $this->_assessmentCalculationCommon->data;
            if(isset($arrAssesment)){
                foreach ($arrAssesment as $key => $val){
                    $totalInterest = 0;
                    $totalSurcharges = 0;
                    $isPartition=0;
                    $isDisplay=1;
                    if($assesment_period==1){
                        if($val['tfoc_divided_fee']==1){
                            $isPartition=1;
                        }
                    }else{
                        if($val['tfoc_divided_fee']==1){
                            $isPartition=1;
                        }else{
                            $isDisplay=0;
                        }
                    }
                    if($isDisplay==1){
                        $feeName = $this->_treasurerAssessment->getSubsidiaryLedgerName($val['sl_id']);
                        $amount = $val['tfoc_amount'];
                        if($isPartition==1){
                            $amount = $val['tfoc_amount']/$dividedBy;
                        }
                        if(!$isValidDate){
                            if(isset($val['interest_fee'])){
                                if($val['assess_is_interest'] && $val['interest_fee']>0){
                                    $totalInterest = $val['interest_fee'];
                                    if($isPartition==1){
                                        $totalInterest = $val['interest_fee']/$dividedBy;
                                    }
                                }
                            }
                            if(isset($val['surcharge_fee'])){
                                if($val['assess_is_surcharge'] &&$val['surcharge_fee']>0){
                                    $totalSurcharges = $val['surcharge_fee'];
                                    if($isPartition==1){
                                        $totalSurcharges = $val['surcharge_fee']/$dividedBy;
                                    }
                                }
                            }
                        }
                        $totalAmount =$amount+$totalInterest+$totalSurcharges;
                        $isOptional = $this->_assessmentCalculationCommon->checkOptionalFee($val['tfoc_id']);
                        $isFinalAssessment = $this->_assessmentCalculationCommon->isFinalAssessment($bus_id,$app_code);
                        $is_deleteFee = isset($_REQUEST['is_deleteFee'])?$_REQUEST['is_deleteFee']:0;
                        $arrDeletedDtls=array();
                        $arrDeletedDtls['isFinalAssessment']=$isFinalAssessment;
                        $arrDeletedDtls['tfoc_id']=$val['tfoc_id'];
                        $arrDeletedDtls['app_code']=$app_code;
                        $arrDeletedDtls['pm_id']=$pm_id;
                        $arrDeletedDtls['year']=$year;
                        $arrDeletedDtls['subclass_id']=$val['subclass_id'];
                        
                        if($isOptional==1 && !$isFinalAssessment && $is_deleteFee==1){
                            $arrDeletedDtls['is_deleted']='notDeleted';
                        }
                        $arrDelData['tfoc_id']=$val['tfoc_id'];
                        $arrDelData['busn_id']=$bus_id;
                        $arrDelData['year']=$year;
                        $arrDelData['app_code']=$app_code;
                        $arrDelData['pm_id']=$pm_id;
                        $arrDelData['pap_id']=$assesment_period;
                        $arrDelData['subclass_id']=$val['subclass_id'];
                        $isDeleted = $this->_assessmentCalculationCommon->checkDeletedFee($arrDelData);
                        if(isset($isDeleted)){
                            $arrDeletedDtls['is_deleted']='deleted';
                        }else{
                            $peticularFinalTotal +=$totalAmount;
                        }
                        $perticularHtml.= $this->getPerticularHtml($feeName,$amount,$totalInterest,$totalSurcharges,$totalAmount,$arrDeletedDtls);
                        
                        
                    }
                } 
            }
        }
        $perticularHtml .='<tr class="font-style">
            <td colspan="4" style="text-align: right;"><b>Grand Total</b></td>
            <td class="red">'.number_format($peticularFinalTotal,2).'</td>
        </tr>';
        return $perticularHtml;
    }
    public function saveFinalAssessDtls(Request $request){
        $bus_id = $request->input('id');
        $year = $request->input('year');
        $app_code = $request->input('app_code');
        $pm_id = $request->input('pm_id');
        $type = $request->input('type');
        $isReassess = $request->input('isReassess');
        $transaction_no='';
        if($type==1){
            $this->_treasurerAssessment->updateBusinessData($bus_id,array("pm_id"=>$pm_id));
            $this->displayOrReAssessment($request,'saveData');
            $smsTemplate=SmsTemplate::where('id',53)->where('is_active',1)->first();
        }
        if($type==3){
            //Converted to make payment
            Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$bus_id); // This for remote server
            $this->_treasurerAssessment->updateBusinessData($bus_id,array("is_final_assessment"=>1,'busn_app_status'=>'4'));
            if($app_code==3){
                $this->_treasurerAssessment->updateBusnRetirement($bus_id,array("retire_is_final_assessment"=>1));
            }
            $transaction_no = $this->_treasurerAssessment->getTransactionNumber($bus_id,$app_code,$year);
        }
        if($app_code==1){ 
            $smsTemplate=SmsTemplate::where('id',53)->where('is_active',1)->first();
        }
        if($app_code==3){ 
            $smsTemplate=SmsTemplate::where('id',54)->where('is_active',1)->first();
        }

        
        $arrData = $this->_treasurerAssessment->getBusinessDetails($request->input('id'));
        if(!empty($smsTemplate) && $arrData->p_mobile_no != null){
            $arrOtherDtls = $this->_treasurerAssessment->checkAllTopPaidTransaction($bus_id,$app_code);
            $topNo='';
            $totalAmt=0;
            foreach($arrOtherDtls as $p_val){
                if($p_val->payment_status!=1){
                    $topNo .=$p_val->top_transaction_no.'/';
                    $totalAmt +=$p_val->total_amount;
                }
            }
            if($totalAmt>0){
                $totalAmt = number_format($totalAmt,2);
                $topNo = trim($topNo,'/');
                $receipient=$arrData->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                $msg = str_replace('<BUSINESS_NAME>', $arrData->busn_name,$msg);
                $msg = str_replace('<TOP_NO>', $topNo,$msg);
                $msg = str_replace('<BILLING_AMOUNT>',$totalAmt,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                
                $this->send($msg, $receipient);
            }
        }
        $arrJson = array();
        $arrJson['transaction_no']=$transaction_no;
        $arrJson['ESTATUS']=0;
        return response()->json($arrJson);
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
    public function getActivityDetails($bus_id,$app_code,$retire_id){
        $arrAct = $this->_treasurerAssessment->getActivityDetails($bus_id,$app_code,$retire_id);
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

    public function displayTaxOrderOfPayment(Request $request){
        $checkedPeriod = array();
        if(!empty($request->input('checkedPeriod'))){
            $checkedPeriod = $request->input('checkedPeriod');
        }
        
        $bus_id = $request->input('id');
        $year = $request->input('year');
        $retire_id = $request->input('retire_id');
        $app_code = $request->input('app_code');
        $this->generateHtmlOrPdf($bus_id,$year,$app_code,'html',$retire_id,$checkedPeriod);
    }
    public function generatePaymentPdf(Request $request){
        $checkedPeriod = array();
        if(!empty($request->input('checkedPeriod'))){
            $checkedPeriod = explode(",",$request->input('checkedPeriod'));
        }
        $bus_id = $this->_commonmodel->decryptData($request->input('id'));
        $year = $this->_commonmodel->decryptData($request->input('year'));
        $app_code = $this->_commonmodel->decryptData($request->input('app_code'));
        $retire_id = $request->input('retire_id');
        $this->generateHtmlOrPdf($bus_id,$year,$app_code,'pdf',$retire_id,$checkedPeriod);
    }

    public function generateORPaymentPdf(Request $request){
       
        $bus_id = $this->_commonmodel->decryptData($request->input('id'));
        $year = $this->_commonmodel->decryptData($request->input('year'));
        $app_code = $this->_commonmodel->decryptData($request->input('app_code'));
        $assIDs = $this->_commonmodel->decryptData($request->input('ids'));
        $retire_id = $request->input('retire_id');
        $this->generateHtmlOrGeneratedPdf($bus_id,$year,$app_code,'pdf',$retire_id,$assIDs);
    }

    public function generateHtmlOrGeneratedPdf($bus_id,$yr,$app_code=0,$displayType,$retire_id,$assIDs){
        //$year=2021; //For Testing
        $html="";
        $finalData=array('feeName'=>'','subtotal'=>0,'surcharge'=>0,'interest'=>0,'finaltotal'=>0);
        $arrPerFixed = array("0"=>"","1"=>"%","2"=>"F.A.");
        $cnt=1;
        $periodCoverd="";
        $finalAssessIds="";

        $paymentSchduleHtml = "";

        $ids = explode(",",$assIDs);
        $arrFinal = DB::table('cto_bplo_final_assessment_details')->whereIn('id',$ids)->orderBy('id','ASC')->get()->toArray();
        $arrTopNo=[];
        if(count($arrFinal)>0){
            $subtotal=0;$surcharge=0;$interest=0;$finaltotal=0;
            $isDataAvailable=0;
            $feesHtml='';
            foreach($arrFinal AS $key=>$final){
                $arrTopNo[$final->top_transaction_no]=$final->top_transaction_no;
                $finalAssessIds .=",".$final->id;
                $pm_id = $final->payment_mode;
                $assessment_period = $final->assessment_period;
                $arrAssDtls = $this->_treasurerAssessment->getTaxAssessementDetails($bus_id,$yr,$pm_id,$assessment_period,$app_code);
                $data= array('period'=>'','perticular'=>'','subtotal'=>'','surcharge'=>'','surcharge_rate'=>'','interest'=>'','interest_rate'=>'','finaltotal'=>'');
                $data['period']=config('constants.payModePartitionShortCut')[$pm_id][$assessment_period];
                if($key==0){
                    $periodCoverd =$yr.', '.$data['period'].' - '.$periodCoverd;
                }
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
                    $paymentSchduleHtml .=$this->genereatePaymentSchedule($final);
                }
            }
            if($isDataAvailable>0){
                $html .=$this->generateTaxOrderHtml($yr,'Year'); 
                $html .=$feesHtml;
            }
            if($cnt==1){
                $periodCoverd = $yr.', '.$data['period'];
            }
            $cnt++;
        }

        
        $finalData['feeName'] = "BUSINESS/PERMIT";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 

        $finalData['feeName'] = "GRAND TOTAL";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 
        
        $arrBussDtls = $this->_treasurerAssessment->getBusinessDetails($bus_id);
        $address=$this->_Barangay->findDetails($arrBussDtls->busn_office_main_barangay_id);
        $arrPayment= $this->_treasurerAssessment->getPaidPaymentDate($bus_id);

        $data = array();
        $data['paymentSchduleHtml']=$paymentSchduleHtml;
        $data['activityDetails']=$this->getActivityDetails($bus_id,$app_code,$retire_id);
        $ownar_name=$arrBussDtls->rpo_first_name.' '.$arrBussDtls->rpo_middle_name.' '.$arrBussDtls->rpo_custom_last_name;
        if(!empty($arrBussDtls->suffix)){
            $ownar_name .=", ".$arrBussDtls->suffix;
        }   

        $data['ownar_name']=$ownar_name;
        $data['busns_id_no']=$arrBussDtls->busns_id_no;
        $data['busn_name']=$arrBussDtls->busn_name.' / '.$arrBussDtls->brgy_name;
        $data['payment_mode']=$arrBussDtls->pm_desc;
        
        $data['app_type']=config('constants.arrBusinessApplicationType')[(int)$app_code];
        $data['address']=$address;
        $data['period_coverd']=$periodCoverd;
        $data['total_employee']=$arrBussDtls->busn_employee_total_no;
        $data['mobile']=$arrBussDtls->p_mobile_no;
        
        $data['last_amount_paid']='';
        if(isset($arrPayment)){
            $data['last_amount_paid']=number_format($arrPayment->total_amount,2);
        }
        $top_no='';
        if(count($arrTopNo)>0){
            $top_no = implode(",",$arrTopNo);
        }
        $data['top_trans_no']=$top_no;
        if($app_code==3){
            $data['total_employee'] = $this->_treasurerAssessment->getTotalEmployee($retire_id);
        }
        $assessDate = $this->_treasurerAssessment->getAssessDate($data['top_trans_no']);
        $assessmentDate = (!empty($assessDate))?$assessDate:$arrBussDtls->created_at;
        $data['date_assessed']=date("d M Y",strtotime($assessmentDate));

        // ************* Start Display Content Details ************************
        if($displayType=="pdf"){
            $this->generatePdfFile($bus_id,$html,$data,$displayType);
        }
        // ************* End Display Content Details ************************
    }

    public function generateHtmlOrPdf($bus_id,$year,$app_code=0,$displayType,$retire_id=0,$checkedPeriod=array()){
        $paymentYear = $this->getPaymentYear($bus_id,'',$app_code);
        if($paymentYear>0){
            $year =$paymentYear;
        }
        //$year=2021; //For Testing
        $html="";
        $finalData=array('feeName'=>'','subtotal'=>0,'surcharge'=>0,'interest'=>0,'finaltotal'=>0);
        $arrPerFixed = array("0"=>"","1"=>"%","2"=>"F.A.");
        $cnt=1;
        $periodCoverd="";
        $finalAssessIds="";

        $startYear = date("Y");
        if($displayType=='delinquencyEmail'){
            $startYear= date("Y")-1; //Delinquency always create previous year
        }
        $paymentSchduleHtml = "";
        for($i=$startYear; $year<=$i;$i--){
            $yr = $i;
            $arrFinal = $this->_treasurerAssessment->getFinalAssessementDetails($bus_id,$yr,$app_code,$checkedPeriod);
            if(count($arrFinal)>0){
                $subtotal=0;$surcharge=0;$interest=0;$finaltotal=0;
                $isDataAvailable=0;
                $feesHtml='';
                foreach($arrFinal AS $key=>$final){
                    $finalAssessIds .=",".$final->id;
                    $pm_id = $final->payment_mode;
                    $assessment_period = $final->assessment_period;
                    $arrAssDtls = $this->_treasurerAssessment->getTaxAssessementDetails($bus_id,$yr,$pm_id,$assessment_period,$app_code);
                    $data= array('period'=>'','perticular'=>'','subtotal'=>'','surcharge'=>'','surcharge_rate'=>'','interest'=>'','interest_rate'=>'','finaltotal'=>'');
                    $data['period']=config('constants.payModePartitionShortCut')[$pm_id][$assessment_period];
                    if($i==$year && $key==0){
                        $periodCoverd =$year.', '.$data['period'].' - '.$periodCoverd;
                    }
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
                        $paymentSchduleHtml .=$this->genereatePaymentSchedule($final);
                    }
                }
                if($isDataAvailable>0){
                    $html .=$this->generateTaxOrderHtml($yr,'Year'); 
                    $html .=$feesHtml;
                }
                if($cnt==1){
                    $periodCoverd = $yr.', '.$data['period'];
                }
                $cnt++;
            }
        }
        if($cnt==1 && $displayType!='delinquencyEmail' && $displayType!='assessmentEmail'){
            echo "<span style='color:red;'>Not Found Tax Order Of Payment.</span>";exit;
        }
        if(isset($_POST['chk_hidden_final_total'])){ // Checked for Re-Assess
            $hidden_final_total = 0;
            foreach ($checkedPeriod as $pa_key => $pa_val) {
                $hidden_final_total += isset($_POST['chk_hidden_final_total_'.$pa_val])?$_POST['chk_hidden_final_total_'.$pa_val]:0;
            }
            $hTotal = number_format($hidden_final_total);
            $fTotal = number_format($finalData['finaltotal']);

            if($hTotal!= $fTotal && $hidden_final_total>0){
                echo "###MISSMATCHING###";exit;
            }
        }
        
        $finalData['feeName'] = "BUSINESS/PERMIT";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 

        $finalData['feeName'] = "GRAND TOTAL";
        $html .=$this->generateTaxOrderHtml($finalData,'Total'); 
        
        $arrBussDtls = $this->_treasurerAssessment->getBusinessDetails($bus_id);
        $address=$this->_Barangay->findDetails($arrBussDtls->busn_office_main_barangay_id);
        $arrPayment= $this->_treasurerAssessment->getPaidPaymentDate($bus_id);

        $data = array();
        $data['paymentSchduleHtml']=$paymentSchduleHtml;
        $data['activityDetails']=$this->getActivityDetails($bus_id,$app_code,$retire_id);
        $ownar_name=$arrBussDtls->rpo_first_name.' '.$arrBussDtls->rpo_middle_name.' '.$arrBussDtls->rpo_custom_last_name;
        if(!empty($arrBussDtls->suffix)){
            $ownar_name .=", ".$arrBussDtls->suffix;
        }   

        $data['ownar_name']=$ownar_name;
        $data['busns_id_no']=$arrBussDtls->busns_id_no;
        $data['busn_name']=$arrBussDtls->busn_name.' / '.$arrBussDtls->brgy_name;
        $data['payment_mode']=$arrBussDtls->pm_desc;
        
        $data['app_type']=config('constants.arrBusinessApplicationType')[(int)$app_code];
        $data['address']=$address;
        $data['period_coverd']=$periodCoverd;
        $data['total_employee']=$arrBussDtls->busn_employee_total_no;
        $data['mobile']=$arrBussDtls->p_mobile_no;
        
        $data['last_amount_paid']='';
        if(isset($arrPayment)){
            $data['last_amount_paid']=number_format($arrPayment->total_amount,2);
        }
        $data['top_trans_no']='';
        if(!empty($html) && $displayType!='delinquencyEmail'){
            $period=0;
            if(count($checkedPeriod)>0){
                $period = end($checkedPeriod);
            }
            $data['top_trans_no'] = $this->getAddUpdateTopTransaction($bus_id,$app_code,$finalData['finaltotal'],$finalAssessIds,$period);
        }
        if($app_code==3){
            $data['total_employee'] = $this->_treasurerAssessment->getTotalEmployee($retire_id);
        }
        $assessDate = $this->_treasurerAssessment->getAssessDate($data['top_trans_no']);
        $assessmentDate = (!empty($assessDate))?$assessDate:$arrBussDtls->created_at;
        $data['date_assessed']=date("d M Y",strtotime($assessmentDate));

        // ************* Start Display Content Details ************************
        if($displayType=='html'){
            $Finalhtml = view('Bplo.TreasurerAssessment.taxOrderPayment',compact('html','data','displayType'));
            $this->generatePdfFile($bus_id,$html,$data,'pdf','1');
            echo $data['top_trans_no'].'#####'.$Finalhtml;exit;
        }elseif($displayType=="pdf"){
            $this->generatePdfFile($bus_id,$html,$data,$displayType);
            
        }elseif($displayType=='delinquencyEmail'){
            $data['isShowBtn']=1;
            $data['username']=$arrBussDtls->rpo_custom_last_name;
            $Finalhtml = view('mails.taxOrderPaymentEmail',compact('html','data','displayType'));
            return $Finalhtml;
        }elseif($displayType=='assessmentEmail'){
            $data['isShowBtn']=0;
            $data['username']=$arrBussDtls->rpo_custom_last_name;
            $Finalhtml = view('mails.taxOrderPaymentEmail',compact('html','data','displayType'));
            return $Finalhtml;
        }
        echo $Finalhtml; exit;
        // ************* End Display Content Details ************************
    }
    public function genereatePaymentSchedule($data){
        $arrpm = config('constants.payModePartition')[$data->payment_mode];
        $status ='<span style="color:red;">Pending</span>';
        if($data->payment_status==1){
            $status ='<span style="color:green;">Paid</span>';
        }
        //<td style="text-align:center;">'.$status.'</td>
        $html ='<tr class="perticular-dtls" style="text-align:center;">
            <td style="text-align:center;">'.$arrpm[$data->assessment_period].'</td>
            <td style="text-align:center;">'.$data->assess_due_date.'</td>
            
            <td style="text-align:center;">'.number_format($data->sub_amount,2).'</td>
            <td style="text-align:center;">'.number_format($data->total_surcharge_interest_fee,2).'</td>
            <td style="text-align:center;">'.number_format($data->total_amount,2).'</td>
        </tr>';
        return $html;
    }
    public function generatePdfFile($bus_id,$html,$data,$displayType,$isFileSaved=0){
        $Finalhtml = view('Bplo.TreasurerAssessment.taxOrderPayment',compact('html','data','displayType'));
        $mpdf = new \Mpdf\Mpdf(['setAutoBottomMargin' => 'stretch','autoMarginPadding' => '10']);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $qrImage = asset('uploads/bplo_qrcode') . "/qrcode.png";

        $footer = "<table name='footer' width=\"1000\" style='font-size:8px;'>
            <tr>
            
                <td>
                    <p>1. <span>Please pay the amount due accordingly through online or at the City Treasurers Office, Brgy. Singalat. 1st Floor Building. City Hall of Palayan. Palayan City. N.E.</span></p>
                    <p>2. <span>Late Payment will be subjected to 25% surcharges and a monthly interest rate of 2% of the amount due to be paid at the same time and in the same manner as tax due.</span></p>
                    <p>3. <span>For due dates that fall on Saturdays, Sundays, and Holidays, payment should be made on the last working day prior to the due date.</span></p> 
                </td>
            </tr>
         </table>";
        $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($Finalhtml);
        $orderfilename = "orderOfPayment-".$bus_id.".pdf";
        if(!$isFileSaved){
            $mpdf->Output($orderfilename, "I");
        }else{
            $destinationPath =  public_path().'/uploads/billing/bplo/';
            if(!File::exists($destinationPath)) { 
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $filename = $data['top_trans_no'].'.pdf';
            $mpdf->Output($destinationPath.$filename,'F'); 
            @chmod($destinationPath.$filename, 0777);
            $columns['attachment'] = $filename;
            DB::table('cto_top_transactions')->where('transaction_no',$data['top_trans_no'])->update($columns);
        }
        
    }
    public function getAddUpdateTopTransaction($bus_id,$app_code,$finaltotal,$finalAssessIds,$period=0){
        $bussDtls = $this->_treasurerAssessment->getBussDetails($bus_id);
        if(empty($bussDtls->pm_id)){
            $bussDtls->pm_id=1;
        }
        $assesment_period=$period;
        if($period==0){
            $assesment_period = $this->getPaymentPeriod($bussDtls->pm_id,date("Y-m-d"));
        }
        $arrTB=array();
        $arrTrans = array();
        $arrTB['top_year']=date("Y");
        $arrTB['top_month']=date("m");
        $arrTB['final_assessment_ids']=trim($finalAssessIds,',');
        $arrTB['top_transaction_type_id'] = $arrTrans['top_transaction_type_id'] = 1 ;
        $arrTB['busn_id']=$bus_id;
        $arrTB['app_code']=$app_code;
        $arrTB['pm_id']=$bussDtls->pm_id;
        $arrTB['pap_id']=$assesment_period;
        $arrTB['top_is_posted']=1;
        $arrTB['updated_by'] = $arrTrans['updated_by'] = \Auth::user()->id;
        $arrTB['updated_at'] = $arrTrans['updated_at'] = date('Y-m-d H:i:s');

        $arrTrans['tfoc_is_applicable']=1;
        $arrTrans['amount']=$finaltotal;

        $flag=0;
        $prevTrans = $this->_treasurerAssessment->checkTopPaidTransaction($bus_id,$app_code,date("Y"));
        if(empty($prevTrans)){
            $flag=1;
        }elseif($prevTrans->is_paid){
            $flag=1;
        }
        if($flag==1){
            $arrTB['created_by'] = $arrTrans['created_by'] = \Auth::user()->id;
            $arrTB['created_at'] = $arrTrans['created_at'] = date('Y-m-d H:i:s');

            $top_id = $this->_treasurerAssessment->addTopBplo($arrTB);
            $arrTrans['transaction_ref_no']=$top_id;

            $trans_id = $this->_treasurerAssessment->addTopTransactions($arrTrans);
            $transaction_no = str_pad($trans_id, 6, '0', STR_PAD_LEFT);
            $this->_treasurerAssessment->updateTopTransactions($trans_id,array("transaction_no"=>$transaction_no));
        }else{
            $this->_treasurerAssessment->updateTopBplo($prevTrans->top_bplo_id,$arrTB);
            $this->_treasurerAssessment->updateTopTransactions($prevTrans->top_trans_id,$arrTrans);
            $transaction_no = $prevTrans->transaction_no;
        }
        $arr=array();
        $arr['top_transaction_no']=$transaction_no;
        $finalAssessIds =explode(",", trim($finalAssessIds,','));
        $this->_treasurerAssessment->updateFinalAssessment($finalAssessIds,$arr);
        return $transaction_no;
    }

    function getPaymentPeriod($pm_id,$date){
        $ass_period=1;
        $month=date("n",strtotime($date));
        if($pm_id==2){
            $ass_period = ceil($month / 6);
        }elseif($pm_id==3){
            $ass_period = ceil($month / 3);
        }
        return $ass_period;
    }
    public function sendEmail(Request $request){
        $bus_id = $request->input('id');
        $year = $request->input('year');
        $retire_id = $request->input('retire_id');
        $app_code = $request->input('app_code');
        $transaction_no = $request->input('transaction_no');
        $app_name = (!isset($app_code))?'':config('constants.arrBusinessApplicationType')[(int)$app_code];
        $arrDtls = $this->_treasurerAssessment->getBusinessDetails($bus_id);
        if(!empty($transaction_no) && $request->session()->get('IS_SYNC_TO_TAXPAYER')){
            DB::table('bplo_bill_summary')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->update(array('is_synced'=>0));
            try {
                $remortServer = DB::connection('remort_server');
                $remortServer->table('bplo_bill_summary')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->update(array('is_final_assessment'=>1));
                DB::table('bplo_bill_summary')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->update(array('is_synced'=>1));
            }catch (\Throwable $error) {
            }
        }
        
            
        if(isset($arrDtls)){
            if(!empty($arrDtls->p_email_address)){
                $data=array();
                $description = 'This is the assessment details, Please pay as soon as possible.';
                $html = $this->generateHtmlOrPdf($bus_id,$year,$app_code,'assessmentEmail',$retire_id);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$arrDtls->rpo_first_name;
                $data['to_email']=$arrDtls->p_email_address;
                //$data['to_email']='tushalburungale11@gmail.com';
                $data['subject']=$app_name.' Business Assessment Details.';

                Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                }); 
            }
        }
    }
    
    public function restoreDeleteAssessmentFee(Request $request){
        $type = $request->input('type');
        $bus_id = $request->input('id');
        $tfoc_id = $request->input('tfoc_id');
        $year = $request->input('year');
        $app_code = $request->input('app_code');
        $subclass_id = $request->input('subclass_id');
        $pm_id = (int)$request->input('pm_id');
        $pm_id = (!$pm_id)?1:$pm_id;
        $assesment_period = $request->input('assesment_period');
        $arrDelData['tfoc_id']=$tfoc_id;
        $arrDelData['busn_id']=$bus_id;
        $arrDelData['year']=$year;
        $arrDelData['app_code']=$app_code;
        $arrDelData['pm_id']=$pm_id;
        $arrDelData['subclass_id']=$subclass_id;
        
        foreach($assesment_period AS $period){
            $arrDelData['pap_id']=$period;
            $isDeleted = $this->_assessmentCalculationCommon->checkDeletedFee($arrDelData);
            if(!isset($isDeleted)){
                if($type=='delete'){
                    $postData = $arrDelData;
                    $postData['month'] = date("m");
                    $postData['updated_by'] = \Auth::user()->id;
                    $postData['updated_at'] = date('Y-m-d H:i:s');
                    $postData['created_by'] =  \Auth::user()->id;
                    $postData['created_at'] =  date('Y-m-d H:i:s');
                    $this->_assessmentCalculationCommon->addDeletedFee($postData);
                }
            }else{
                if($type=='restore'){
                    $this->_assessmentCalculationCommon->deleteDeletedAssesmentFee($arrDelData);
                }
            }
        }
        echo json_encode(array("status"=>true,'message'=>'Deleted successfully'));
    }
    public function storRemoteBploBillReceipt(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $bus_id = $request->input('id');
            $app_code = $request->input('app_code');
            $transaction_no = $request->input('transaction_no');
            $checkedPeriod = $request->input('checkedPeriod');
            $arrTran = $this->_treasurerAssessment->getBillDetails($transaction_no,$bus_id);
            if(isset($arrTran)){
                $this->_assessmentCalculationCommon->arrDueDates = $this->_assessmentCalculationCommon->getDueDatesDetails($app_code);
                $period = $arrTran->pap_id;
                if(!empty($checkedPeriod)){
                    $period = end($checkedPeriod);
                }
                $dueDate=$this->_assessmentCalculationCommon->getPaymentDueDate($app_code,$arrTran->pm_id,$period,date("Y")); 
                $arrData['busn_id'] = $bus_id;
                $arrData['client_id'] = $arrTran->client_id;
                $arrData['bill_year'] = date("Y");
                $arrData['bill_month'] = date("m");
                $arrData['bill_due_date'] = $dueDate;
                $arrData['app_code'] = $app_code;
                $arrData['pm_id'] = $arrTran->pm_id;
                $arrData['pap_id'] = $period;
                $arrData['total_amount'] = $arrTran->amount;
                $arrData['transaction_no'] = $transaction_no;
                $arrData['attachement'] = $arrTran->attachment;
                $arrData['updated_by'] = \Auth::user()->id;
                $arrData['updated_at'] = date('Y-m-d H:i:s');
                $arrData['is_synced'] = 0;
                
                //This is for Main Server
                $arrBill = DB::table('bplo_bill_summary')->select('id')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->first();
                if(isset($arrBill)){
                    DB::table('bplo_bill_summary')->where('id',$arrBill->id)->update($arrData);
                }else{
                    $arrData['created_by'] =  \Auth::user()->id;
                    $arrData['created_at'] =  date('Y-m-d H:i:s');
                    DB::table('bplo_bill_summary')->insert($arrData);
                }

                // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/bplo/'.$arrTran->attachment;
                $fileContents = file_get_contents($destinationPath);
                $remotePath = 'public/uploads/billing/bplo/'.$arrTran->attachment;
                Storage::disk('remote')->put($remotePath, $fileContents);
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('bplo_bill_summary')->select('id')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->first();

                try {
                    if(isset($arrBill)){
                        $remortServer->table('bplo_bill_summary')->where('id',$arrBill->id)->update($arrData);
                    }else{
                        $arrData['created_by'] =  \Auth::user()->id;
                        $arrData['created_at'] =  date('Y-m-d H:i:s');
                        $remortServer->table('bplo_bill_summary')->insert($arrData);
                    }
                    DB::table('bplo_bill_summary')->where('busn_id',$bus_id)->where('transaction_no',$transaction_no)->update(array('is_synced'=>1));
                    unlink($destinationPath);
                }catch (\Throwable $error) {
                    return $error;
                }
                
                return "Done";
            }
        }
    }
}