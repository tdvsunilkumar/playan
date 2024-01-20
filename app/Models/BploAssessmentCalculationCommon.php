<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class BploAssessmentCalculationCommon extends Model
{
    public $data=[];
    public $arrFinalAssessDtls=[];
    public $assIdDetails=['finalAssIds'=>[],'assDtlsIds'=>[],'assIds'=>[]];
    public $arrsubIntFee=[];
    public $arrDueDates=[];
    public $paymentModePeriodDetails=[];
    public $requestType;
    public $assesEndDate;
    public function __construct() {
        $this->arrsubIntFee = $this->getSubchargeInterest();
        $this->assesEndDate=date("Y-m-d");
    }
    public function updatestatusmaster($tablename,$ids,$wherecolumn,$columns){
		return DB::table($tablename)->whereIn($wherecolumn,$ids)->update($columns);
	}

	public function addSystemActivityLog($postdata){
        DB::table('system_activity_log')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateLog($postdata){
        $postdata['created_by'] = !empty(\Auth::user())?\Auth::user()->id:1;
        $postdata['created_at'] = date('Y-m-d H:i:s');
        $postdata['updated_at'] = date('Y-m-d H:i:s');
        DB::table('system_activity_log')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getTotalMonth($busn_id,$assess_due_date){
        $arrPayment = DB::table('cto_bplo_final_assessment_details')->select('payment_date')->where('payment_status',1)->where('busn_id',(int)$busn_id)->orderBy('assess_year','DESC')->first();
        $assYear = date("Y",strtotime($assess_due_date));
        $paidYear=0;
        if(isset($arrPayment)){
            $paidYear = isset($arrPayment->payment_date)?date("Y",strtotime($arrPayment->payment_date)):'';
        }
        
        $arrTotalYear = \Carbon\CarbonPeriod::create($assYear.'-01-01', '1 year', date("Y").'-01-01')->toArray();
        $totalYear = 0;
        if(date('Y')!=$assYear && $assYear!=$paidYear){
            foreach ($arrTotalYear as $date) {
                if($date->format('Y')!=date('Y') && $paidYear!=$date->format('Y')){
                    $totalYear++;
                }
            }
        }
        $totalMonth = $totalYear*12;
        //echo $totalMonth;exit;
        return $totalMonth;
    }
    public function getTfocInterestSurchargeFee($amt,$val,$assess_due_date){
        $arr = $this->arrsubIntFee;
        $totalMonth = $this->getTotalMonth($val->busn_id,$assess_due_date);

        if($totalMonth>$arr->tis_interest_max_month){
            $totalMonth = $arr->tis_interest_max_month;
        }
        $arrDtls = array();
        $arrDtls['surcharge_sl_id']=0;
        $arrDtls['surcharge_fee']= 0;
        $arrDtls['interest_sl_id']=0;
        $arrDtls['interest_fee']=0;

        $arrDtls['surcharge_rate']=0;
        $arrDtls['surcharge_rate_type']= 0;
        $arrDtls['interest_rate']=0;
        $arrDtls['interest_rate_type']=0;

        if($arr->tis_surcharge_amount>0 && $val->tfoc_surcharge_fee==1){
            $arrDtls['surcharge_fee']= $arr->tis_surcharge_amount;
            $arrDtls['surcharge_sl_id']=$val->tfoc_surcharge_sl_id;
            $arrDtls['surcharge_rate']=$arr->tis_surcharge_amount;
            $arrDtls['surcharge_rate_type']= $arr->tis_surcharge_rate_type;
            if($arr->tis_surcharge_rate_type==1){
                $finalAmount = ($amt*$arr->tis_surcharge_amount)/100;
                $arrDtls['surcharge_fee']= $finalAmount;
            }
        }
        if($val->tfoc_interest_fee==1){
            $totalInterest = $arr->tis_interest_amount*$totalMonth;
            if($arr->tis_interest_amount>0){
                $arrDtls['interest_fee']= $arr->tis_interest_amount;
                $arrDtls['interest_sl_id']=$val->tfoc_interest_sl_id;
                $arrDtls['interest_rate']=$arr->tis_interest_amount;
                $arrDtls['interest_rate_type']=$arr->tis_interest_rate_type;

                if($arr->tis_interest_rate_type==1){
                    $finalAmount = ($amt*$totalInterest)/100;
                    $arrDtls['interest_fee']= $finalAmount;
                }
                //Note - Interest Fees = Fees + Sucharge And If rate type is percentage
                if($arr->tis_interest_formula==2  && $arr->tis_interest_rate_type==1){
                    $finalAmount = (($amt+$arrDtls['surcharge_fee'])*$totalInterest)/100;
                    $arrDtls['interest_fee'] = $finalAmount;
                }
            }
        }
        return $arrDtls;
    }
    public function calculateComputewiseFee($data){
        $arrJson = json_decode($data->ptfoc_json,true);
        if(isset($arrJson)){
            if($data->cctype_id==3){ // Measure And Pax Calculation
                $index = array_search($data->buspx_charge_id, array_column($arrJson, 'charge_id'));
                if($index>=0){
                    $jsonDtsl = $arrJson[$index];
                    if($jsonDtsl['measure_amount']>0){
                        $totalFee = $jsonDtsl['measure_amount'];
                        if($jsonDtsl['is_per_unit']==1){
                            $totalFee = $jsonDtsl['measure_amount']*$data->buspx_no_units;
                        }
                        return $totalFee;
                    }
                }
            }else{
                $arrBasisFieldsTablesDtls= $this->getBasisFieldsTablesDtls($data->ptfoc_basis_id);
                if(isset($arrBasisFieldsTablesDtls)){
                    $field = $arrBasisFieldsTablesDtls->basis_ref_field;
                    $totalNumber = $data->$field;
                    if($data->cctype_id==2){ // Formula Calculation
                        if(!empty($arrJson['formula'])){
                            $formula = strtolower($arrJson['formula']);
                            $final = str_replace("x",$totalNumber , $formula);
                            $totalFee = eval('return '.$final.';');
                            if($arrJson['is_higher']==1 && $arrJson['higher_amount']>$totalFee){
                                $totalFee = $arrJson['higher_amount'];
                            }
                            return $totalFee;
                        }
                    }elseif($data->cctype_id==6){ // Range Calculation)
                        $arrRange = array_filter($arrJson, function($item)use($totalNumber) {
                            if($totalNumber>=$item['lower_amount'] && $totalNumber<=$item['upper_amount']){
                                return $item;
                            }
                        });
                        if(count($arrRange)>0){
                            $arrRange = array_values($arrRange);
                            if(isset($arrRange)){
                                $arrRange = $arrRange[0];
                            }else{
                                $arrRange=array();
                            }
                            
                            if($arrRange['is_formula']==1){
                                $formula = strtolower($arrRange['formula']);
                                $final = str_replace("x",$totalNumber , $formula);
                                //echo "<div class='hide' hiddendFormalForCheck>".$formula."</div>";
                                $totalFee = eval('return '.$final.';');
                            }else{
                                $totalFee = $arrRange['range_amount'];
                            }
                            if($arrRange['is_higher']==1 && $arrRange['higher_amount']>$totalFee){
                                $totalFee = $arrRange['higher_amount'];
                            }
                            return $totalFee;
                        }
                    }
                }
            }
        }
        return 0;
        
    }
    public function finalAssesmentDtls($busn_id,$val,$app_code,$pm_id,$year){
        $paidDetails = $this->checkPaidDetails($busn_id,$year,$val->busn_psic_id,$val->subclass_id,$val->tfoc_id,$app_code);
        if(isset($paidDetails)){
            $dtls = (array)$paidDetails;
            $dtls['tfoc_divided_fee'] = $val->tfoc_divided_fee;
            $this->data[]= $dtls;
        }else{
            if($app_code==1){
                $pm_id=1; // If application type New then payment mode Annually bydefault
            }
            $arrPm = array("1"=>"1","2"=>"2","3"=>"4");
            $arrDtls = array();
            $arrReturnData = array();
            if($val->cctype_id==0){
                $arrDtls['tfoc_amount']=$val->tfoc_amount;
            }
            elseif($val->cctype_id==1){
                $arrDtls['tfoc_amount']=$val->ptfoc_constant_amount;
            }else{
                if(isset($val->ptfoc_json)){
                    $arrDtls['tfoc_amount']=$this->calculateComputewiseFee($val);
                }
            }
            if($arrDtls['tfoc_amount']>0){
                $arrDtls['assess_year']=$year;
                $arrDtls['assess_month']=date("m");
                $arrDtls['busn_id']=$busn_id;
                $arrDtls['app_code']=$app_code;
                $arrDtls['busn_psic_id']=$val->busn_psic_id;
                $arrDtls['subclass_id']=$val->subclass_id;
                $arrDtls['tfoc_id']=$val->tfoc_id;
                $arrDtls['agl_account_id']=$val->gl_account_id;
                $arrDtls['sl_id']=$val->sl_id;

                //$arrDtls['agl_account_id']=$val->ptfoc_gl_id;
                //$arrDtls['sl_id']=$val->ptfoc_sl_id;
                $arrDtls['assess_is_surcharge']=$val->tfoc_surcharge_fee;
                $arrDtls['assess_is_interest']=$val->tfoc_interest_fee;
                $arrDtls['payment_mode'] = $pm_id;
                $arrDtls['tfoc_tmp_amount']=$arrDtls['tfoc_amount'];
                $arrDtls['surcharge_fee']=0;
                $arrDtls['interest_fee']=0;
                if($app_code==2 || $app_code==3){ // Only for Renew Or Retire
                    $assess_due_date=$this->getPaymentDueDate($app_code,$pm_id,1,$year); 
                    $isValidDate = $this->checkExpireDueDate($assess_due_date); 
                    if(!$isValidDate){
                        $arrChargesDtls = $this->getTfocInterestSurchargeFee($arrDtls['tfoc_amount'],$val,$assess_due_date);
                        
                        $arrDtls['surcharge_sl_id']=$arrChargesDtls['surcharge_sl_id'];
                        $arrDtls['surcharge_fee']=$arrChargesDtls['surcharge_fee'];
                        $arrDtls['interest_sl_id']=$arrChargesDtls['interest_sl_id'];
                        $arrDtls['interest_fee']=$arrChargesDtls['interest_fee'];

                        $arrDtls['surcharge_rate']=$arrChargesDtls['surcharge_rate'];
                        $arrDtls['surcharge_rate_type']=$arrChargesDtls['surcharge_rate_type'];
                        $arrDtls['interest_rate']=$arrChargesDtls['interest_rate'];
                        $arrDtls['interest_rate_type']=$arrChargesDtls['interest_rate_type'];
                       
                    }
                }
                $arrDtls['updated_by']=!empty(\Auth::user())?\Auth::user()->id:1;
                $arrDtls['updated_at']= date('Y-m-d H:i:s');

                $arrReturnData = $arrDtls;
                $arrReturnData['tfoc_divided_fee'] = $val->tfoc_divided_fee;
                if($this->requestType=='saveData'){
                    $arrDelData['tfoc_id']=$arrDtls['tfoc_id'];
                    $arrDelData['busn_id']=$busn_id;
                    $arrDelData['year']=$year;
                    $arrDelData['app_code']=$app_code;
                    $arrDelData['pm_id']=$pm_id;
                    $arrDelData['subclass_id']=$arrDtls['subclass_id'];
                    $isDeleted = $this->checkDeletedFee($arrDelData);
                    if(!isset($isDeleted)){
                        $assesment_id = $this->addAssesment($arrDtls);
                        $flag=1;
                        if($assesment_id>0){
                            for($i=0; $arrPm[$pm_id]>$i; $i++){
                                if($i>0 && (int)$val->tfoc_divided_fee==0){
                                    $flag=0;
                                }
                                $dividedBy=$arrPm[$pm_id];
                                if((int)$val->tfoc_divided_fee==0){
                                    $dividedBy=1;
                                }
                                if($flag){
                                    $assess_due_date=$this->getPaymentDueDate($app_code,$pm_id,($i+1),$year);
                                    $interest_fee=0;
                                    $surcharge_fee=0;

                                    $surcharge_rate=0;
                                    $interest_rate=0;

                                    $tfoc_amount = $arrDtls['tfoc_amount']/$dividedBy;
                                    if($app_code==2 || $app_code==3){ // Only for Renew Or Retire
                                        $isValidDate = $this->checkExpireDueDate($assess_due_date);
                                        if(!$isValidDate){
                                            if($val->tfoc_interest_fee==1){
                                                $interest_fee = $arrDtls['interest_fee']/$dividedBy;
                                                $interest_rate=$arrDtls['interest_rate'];
                                            }
                                            if($val->tfoc_surcharge_fee==1){
                                                $surcharge_fee = $arrDtls['surcharge_fee']/$dividedBy;
                                                $surcharge_rate=$arrDtls['surcharge_rate'];
                                            }
                                        }
                                    }
                                    $assessment_period = ($i+1);
                                    $arrassDtls=array();
                                    $arrassDtls = $arrDtls;
                                    $arrassDtls['assessment_period'] = $assessment_period;
                                    $arrassDtls['assess_due_date']=$assess_due_date;
                                    $arrassDtls['tfoc_amount'] = $arrassDtls['tfoc_tmp_amount'] = $tfoc_amount;
                                    $arrassDtls['interest_fee'] =$interest_fee;
                                    $arrassDtls['surcharge_fee'] =$surcharge_fee;

                                    $arrassDtls['surcharge_rate']=$surcharge_rate;
                                    $arrassDtls['interest_rate']=$interest_rate;


                                    $arrassDtls['cb_assesment_id'] =$assesment_id;
                                    $assess_details_id = $this->addAssesmentDetails($arrassDtls);
                                    
                                    $this->paymentModePeriodDetails[$pm_id][$assessment_period]['assement_ids'][$assesment_id]=$assesment_id;
                                    $this->paymentModePeriodDetails[$pm_id][$assessment_period]['assement_details_ids'][$assess_details_id]=$assess_details_id; 
                                }
                            }
                        }
                        $this->data[]= $arrReturnData;
                    }
                }else{
                    $this->data[]= $arrReturnData;
                }
                
            }
        }
    }

    public function checkExpireDueDate($assess_due_date){
        $date1 = Carbon::createFromFormat('Y-m-d', $assess_due_date);
        $date2 = Carbon::createFromFormat('Y-m-d',$this->assesEndDate);
        $result = $date1->gt($date2);
        return $result;
    }
    public function getPaymentDueDate($app_code,$pm_id,$i,$year){
        $payment_due_date=$year."-".date("m-d");
        $DayMonth='';
        if($app_code==1){
            return $payment_due_date;
        }else{
            if($i==1 && !empty($this->arrDueDates->due_1st_payment)){  //First Payment for all mode
                $DayMonth = $this->arrDueDates->due_1st_payment;
            }elseif($pm_id==2 && $i==2 && !empty($this->arrDueDates->due_semi_annual_2nd_sem)){  //Semi - Annually
                $DayMonth = $this->arrDueDates->due_semi_annual_2nd_sem;
            }elseif($pm_id==3){ // Quarterly
                if($i==2 && !empty($this->arrDueDates->due_quarterly_2nd)){
                    $DayMonth = $this->arrDueDates->due_quarterly_2nd;
                }elseif($i==3 && !empty($this->arrDueDates->due_quarterly_3rd)){
                    $DayMonth = $this->arrDueDates->due_quarterly_3rd;
                }elseif($i==4 && !empty($this->arrDueDates->due_quarterly_4th)){
                    $DayMonth = $this->arrDueDates->due_quarterly_4th;
                }
            }
        }
        if(!empty($DayMonth)){
            $arr = explode("/",$DayMonth);
            $payment_due_date=$year.'-'.$arr[0].'-'.$arr[1];
        }
        return $payment_due_date;
    }
    public function calculateAssessmentDetails($busn_id,$app_code='1',$pm_id=1,$requestType='',$year="",$end_dept_id=0,$retire_id=0){
        //Note - If $end_dept_id >0 means this is the local or national government fee departmentwise
        $this->assIdDetails=['finalAssIds'=>[],'assDtlsIds'=>[],'assIds'=>[]];
        $this->paymentModePeriodDetails=[];
        $this->requestType = $requestType;
        $this->arrDueDates = $this->getDueDatesDetails($app_code);
        if($retire_id>0){
            $endDate = $this->getRetirementCloseDate($retire_id);
            if(isset($endDate)){
                $this->assesEndDate = $endDate;
            }
        }
        $this->CalculateAllFees($busn_id,$app_code,$pm_id,$year,$end_dept_id,$retire_id);
        // Store Final Assessment Details with payment mode
        if($this->requestType=='saveData' && count($this->data)>0){
            // Delete not exist data
            if($end_dept_id==0){
                $this->deleteNotExistData($busn_id,$year,$app_code);
            }
            $this->saveFinalAssessmentDetails($busn_id,$app_code,$pm_id,$year,$end_dept_id);
        }
    }
    public function saveFinalAssessmentDetails($busn_id,$app_code,$pm_id,$year,$end_dept_id){
        if(count((array)$this->arrDueDates)==0){
            $this->arrDueDates = $this->getDueDatesDetails($app_code);
        }
        //$dividedBy=($pm_id==3)?4:$pm_id;
        $arrpm = config('constants.payModePartition')[$pm_id];
        foreach($arrpm as $key=>$val){
            $assess_due_date=$this->getPaymentDueDate($app_code,$pm_id,$key,$year);
            $arrfinalDetails = $this->getAmountSurcharchInterestFees($key,$pm_id,$busn_id,$app_code,$year,$end_dept_id);
            $arrDelequency = $arrfinalDetails;
            $arrfinalDetails['assess_year']=$year;
            $arrfinalDetails['assess_due_date']=$assess_due_date;
            $arrfinalDetails['busn_id']=$busn_id;
            $arrfinalDetails['payment_mode']=$pm_id;
            $arrfinalDetails['app_code']=$app_code;
            $arrfinalDetails['assessment_period']=$key;
            $arrfinalDetails['updated_by'] = !empty(\Auth::user())?\Auth::user()->id:1;
            $arrfinalDetails['updated_at'] = date('Y-m-d H:i:s');
            $arrDelequency['final_assess_id']=$this->addFinalAssesmentDetails($arrfinalDetails);
            $this->arrFinalAssessDtls[]=$arrDelequency;
        }
    }
    public function getAmountSurcharchInterestFees($assesment_period,$pm_id,$busn_id,$app_code,$year,$end_dept_id){
        $amount = 0;
        $interetAmt = 0;
        $surchargeAmt = 0;
        $intSurAmt = 0;
        $totalAmount = 0;

        $aassement_ids=array();
        $assement_details_ids=array();
        $arrAssDtls = DB::table('cto_bplo_assessment_details')->select('id','cb_assesment_id','tfoc_amount','interest_fee','surcharge_fee')->where('assess_year',(int)$year)->where('busn_id',(int)$busn_id)->where('assessment_period',(int)$assesment_period)->where('app_code',(int)$app_code)->where('payment_mode',(int)$pm_id)->get()->toArray();

        if(count($arrAssDtls)>0){
            foreach ($arrAssDtls as $a_key => $a_val){
                $amount +=$a_val->tfoc_amount;
                $interetAmt +=$a_val->interest_fee;
                $surchargeAmt +=$a_val->surcharge_fee;
                $aassement_ids[$a_val->cb_assesment_id]=$a_val->cb_assesment_id;
                $assement_details_ids[$a_val->id]=$a_val->id;
            }
        }
        $intSurAmt = $surchargeAmt+$interetAmt;
        $totalAmount = $amount+$intSurAmt;
        $arr=array();
        $arr['sub_amount']=$amount;
        $arr['surcharge_fee']=$surchargeAmt;
        $arr['interest_fee']=$interetAmt;
        $arr['total_surcharge_interest_fee']=$intSurAmt;
        $arr['total_amount']=$totalAmount;
        $arr['assesment_ids'] = implode(",",$aassement_ids);
        $arr['assessment_details_ids']= implode(",",$assement_details_ids);
        return $arr;
    }

    public function calculateAllFees($busn_id,$app_code,$pm_id,$year,$end_dept_id=0,$retire_id=0){
        if($end_dept_id==0){
            $arrbusPisic = $this->getTFOCCharges($busn_id,$app_code,$retire_id);
            if(isset($arrbusPisic)){
                $arrTmp = array();
                foreach ($arrbusPisic as $key => $val) {
                    $isExecute =1;
                    if(isset($arrTmp[$val->tfoc_id]) && $val->tfoc_iterated_fee==0){
                        $isExecute=0;
                    }
                    if($isExecute){
                        $this->finalAssesmentDtls($busn_id,$val,$app_code,$pm_id,$year);
                    }
                    $arrTmp[$val->tfoc_id] = $val->tfoc_id;
                }
            } 
            //Calculate Measure And Pax Details Only for New and Renew
            if($app_code<=2){
                $arrMeasure = $this->getMeasurePaxCharges($busn_id,$app_code);
                if(isset($arrMeasure)){
                    foreach ($arrMeasure as $key => $val) {
                        $this->finalAssesmentDtls($busn_id,$val,$app_code,$pm_id,$year);
                    }
                }
            }
        }
        //Calculate Local & National Fee Only for New and Renew
        if($app_code<=2){
            $arrLocal = $this->getLocalGovFees($busn_id,$app_code,$year,$end_dept_id);
            if(count($arrLocal)>0){
                $arrEmpty=(object)array('busn_psic_id'=>'0','subclass_id'=>'0','busp_no_units'=>'0','busp_capital_investment'=>'0','busp_essential'=>'0','busp_non_essential'=>'0','busp_total_gross'=>'0','cctype_id'=>'0','ptfoc_basis_id'=>'0','ptfoc_gl_id'=>'0','ptfoc_sl_id'=>'0','ptfoc_constant_amount'=>'0','ptfoc_json'=>'','busn_employee_total_no'=>'0','busn_bldg_area'=>'0','pm_id'=>'0');
                foreach($arrLocal AS $l_key=>$l_val){
                    $arrMerge = (object)array_merge((array)$l_val,(array)$arrEmpty);
                    $this->finalAssesmentDtls($busn_id,$arrMerge,$app_code,$pm_id,$year);
                }
            }
        }
    }
    public function updateDelinquencyDetails($bus_id=0,$app_code=0,$year=0,$retire_id=0){
        if(count($this->arrFinalAssessDtls)>0){
            $amount = 0;
            $interetAmt = 0;
            $surchargeAmt = 0;
            $totalAmount = 0;
            $aassement_ids=array();
            foreach($this->arrFinalAssessDtls AS $key=>$val){
                $amount +=$val['sub_amount'];
                $interetAmt +=$val['interest_fee'];
                $surchargeAmt +=$val['surcharge_fee'];
                $aassement_ids[$val['final_assess_id']]=$val['final_assess_id'];
            }
            $intSurAmt = $surchargeAmt+$interetAmt;
            $totalAmount = $amount+$intSurAmt;

            $arr=array();
            $arr['sub_amount']=$amount;
            $arr['retire_id']=$retire_id;
            $arr['surcharge_fee']=$surchargeAmt;
            $arr['interest_fee']=$interetAmt;
            $arr['total_amount']=$totalAmount;
            $arr['final_assessment_ids'] = implode(",",$aassement_ids);
            $arr['busn_id']=$bus_id;
            $arr['app_code']=$app_code;
            $arr['year']=$year;
            $arr['updated_by']=!empty(\Auth::user())?\Auth::user()->id:1;
            $arr['updated_at']= date('Y-m-d H:i:s');
            $this->updateDelinquency($arr);
        }
    }
    public function updateDelinquency($postdata){
        $isExist = DB::table('bplo_business_delinquents')->select('id')
            ->where('busn_id',(int)$postdata['busn_id'])
            ->where('app_code',(int)$postdata['app_code'])->first();
        if(isset($isExist)){
            DB::table('bplo_business_delinquents')->where('id',(int)$isExist->id)->update($postdata);
        }else{
            $postdata['created_by']=!empty(\Auth::user())?\Auth::user()->id:1;
            $postdata['created_at']= date('Y-m-d H:i:s');
            DB::table('bplo_business_delinquents')->insert($postdata);
        }
    }
    public function deleteDelinquency($bus_id=0,$app_code=0,$year=0){
        $sql = DB::table('bplo_business_delinquents')->where('busn_id',(int)$bus_id);
        if($app_code==3){
            $sql->whereIn('app_code',[1,2,3]);
        }elseif($app_code==2 || $app_code==1){
            $sql->whereIn('app_code',[1,2]);
        }
        $sql->delete();
    }
    public function getRetirementCloseDate($retire_id){
        return DB::table('bplo_business_retirement')->where('id',(int)$retire_id)->pluck('retire_date_closed')->first();
    }
    public function checkPaidFinalAssessmentDetails($busn_id,$year,$app_code=0){
        $sql = DB::table('cto_bplo_final_assessment_details')
            ->where('assess_year',(int)$year)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$busn_id);
            
        $sql->whereExists(function ($query)use($year,$busn_id,$app_code) {
               $query->select("id")
                    ->from('cto_bplo_final_assessment_details')
                    ->whereRaw('assess_year ='.(int)$year)
                    ->where('busn_id',(int)$busn_id)
                    ->where('app_code',(int)$app_code)
                    ->where('payment_status',1);
        });
        return $sql->get()->toArray();
    }

    public function checkPaidAssessmentDetails($busn_id,$year,$pm_id,$assesment_period,$app_code=0){
        $sql = DB::table('cto_bplo_assessment_details')
            ->where('assess_year',(int)$year)
            ->where('busn_id',(int)$busn_id)
            ->where('app_code',(int)$app_code)
            ->where('payment_mode',(int)$pm_id)
            ->where('assessment_period',(int)$assesment_period);
            
        $sql->whereExists(function ($query)use($year,$busn_id,$app_code) {
               $query->select("id")
                    ->from('cto_bplo_final_assessment_details')
                    ->whereRaw('assess_year ='.(int)$year)
                    ->where('busn_id',(int)$busn_id)
                    ->where('app_code',(int)$app_code)
                    ->where('payment_status',1);
        });
        return $sql->get()->toArray();
    }

    public function checkPaidDetails($busn_id,$year,$busn_psic_id,$subclass_id,$tfoc_id,$app_code=0){
        $sql = DB::table('cto_bplo_assessment')
            ->where('assess_year',(int)$year)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$busn_id)
            ->where('busn_psic_id',(int)$busn_psic_id)
            ->where('subclass_id',(int)$subclass_id)
            ->where('tfoc_id',(int)$tfoc_id);

        $sql->whereExists(function ($query)use($year,$busn_id,$app_code) {
               $query->select("id")
                    ->from('cto_bplo_final_assessment_details')
                    ->whereRaw('assess_year ='.(int)$year)
                    ->where('app_code',(int)$app_code)
                    ->where('busn_id',(int)$busn_id)
                    ->where('payment_status',1);
        });
        return $sql->first();
    }
    public function deleteNotExistData($busn_id,$year,$app_code=0){
        if(count($this->assIdDetails['assIds'])>0){
            DB::table('cto_bplo_assessment')->whereNotIn('id',$this->assIdDetails['assIds'])
            ->where('assess_year',(int)$year)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$busn_id)->delete();
        }
        if(count($this->assIdDetails['assDtlsIds'])>0){
            DB::table('cto_bplo_assessment_details')->whereNotIn('id',$this->assIdDetails['assDtlsIds'])
            ->where('assess_year',(int)$year)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$busn_id)->delete();
        }
        if(count($this->assIdDetails['finalAssIds'])>0){
            DB::table('cto_bplo_final_assessment_details')->whereNotIn('id',$this->assIdDetails['finalAssIds'])
            ->where('assess_year',(int)$year)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$busn_id)->delete();
        }
    }

    public function getDueDatesDetails($type){
        $type=($type==3)?2:$type; //If type retire then still get renew due dates
        return DB::table('cto_payment_due_dates')->select('*')->where('app_type_id',(int)$type)->first();
    }
    public function getLocalGovFees($busn_id,$app_code,$year,$end_dept_id){ 
        $sql= DB::table('bplo_business_endorsement AS bbe')
            ->join('cto_tfocs AS ct', 'ct.id', '=', 'bbe.tfoc_id')
            ->select('bbe.tfoc_id','bbe.tfoc_amount','tfoc_interest_fee','tfoc_surcharge_fee','tfoc_surcharge_sl_id','tfoc_interest_sl_id','tfoc_divided_fee','tfoc_iterated_fee','bbe.busn_id','sl_id','gl_account_id')
            ->where('busn_id',(int)$busn_id)
            ->where('bend_year',(int)$year)
            ->whereIn('bend_status',[1,2])
            ->where('app_type_id','=',$app_code);
        if($end_dept_id>0){
            $sql->where('endorsing_dept_id','=',(int)$end_dept_id);
        }
        return $sql->get()->toArray();
    }
    public function checkOptionalFee($tfoc_id){
        return DB::table('cto_tfocs')->where('id','=',(int)$tfoc_id)->pluck('tfoc_is_optional_fee')->first();
    }
    public function isFinalAssessment($busn_id,$app_code){
        if($app_code==3){
            if(isset($_REQUEST['retire_id'])){
                return DB::table('bplo_business_retirement')->where('id','=',(int)$_REQUEST['retire_id'])->pluck('retire_is_final_assessment')->first();
            }
        }else{
            return DB::table('bplo_business')->where('id','=',(int)$busn_id)->pluck('is_final_assessment')->first();
        }
    }
    public function checkDeletedFee($conditions){
        return DB::table('bplo_deleted_assessed_fees')->select('id')->where($conditions)->first();
    }
    public function addDeletedFee($postdata){
        return DB::table('bplo_deleted_assessed_fees')->insert($postdata);
    }
    public function deleteDeletedAssesmentFee($conditions){
        return DB::table('bplo_deleted_assessed_fees')->where($conditions)->delete();
    }
    public function getTFOCCharges($busn_id,$app_code,$retire_id=0){ 
        if($app_code==3){
            return DB::table('bplo_business_psic AS bbp')
                ->join('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                ->join('bplo_business_retirement AS br', 'br.busn_id', '=', 'bb.id')
                ->join('bplo_business_retirement_psic AS brp', 'brp.busnret_id', '=', 'br.id')
                ->join('psic_tfocs AS pt', 'pt.subclass_id', '=', 'brp.subclass_id')
                ->join('cto_tfocs AS ct', 'ct.id', '=', 'pt.tfoc_id')
                ->select('bbp.id AS busn_psic_id','br.busn_id','brp.subclass_id','brp.busnret_no_units AS busp_no_units','brp.busnret_capital_investment AS busp_capital_investment','brp.busnret_essential AS busp_essential','brp.busnret_non_essential AS busp_non_essential','brp.busnret_total_gross AS busp_total_gross','cctype_id','ptfoc_basis_id','ptfoc_gl_id','ptfoc_sl_id','ptfoc_constant_amount','ptfoc_json','gl_account_id','sl_id','tfoc_interest_fee','tfoc_surcharge_fee','tfoc_surcharge_sl_id','tfoc_interest_sl_id','pt.tfoc_id','busn_employee_total_no','retire_bldg_area AS busn_bldg_area','bb.pm_id','tfoc_divided_fee','tfoc_iterated_fee','brp.busnret_capital_investment','brp.busnret_essential','brp.busnret_non_essential','brp.busnret_total_gross','br.retire_employee_total_no','br.retire_bldg_area')
                ->where('br.busn_id',(int)$busn_id)
                ->where('br.id',(int)$retire_id)
                ->whereNotIn('cctype_id',[3,4,5])
                ->where('ptfoc_is_active','=','1')
                ->where('tfoc_is_applicable','=','1')
                ->where('pt.app_code','=',$app_code)
                ->orderBy('bbp.id','ASC')
                ->get()->toArray();
        }else{
            return DB::table('bplo_business_psic AS bbp')
                ->join('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                ->join('psic_tfocs AS pt', 'pt.subclass_id', '=', 'bbp.subclass_id')
                ->join('cto_tfocs AS ct', 'ct.id', '=', 'pt.tfoc_id')
                ->select('bbp.id AS busn_psic_id','busn_id','bbp.subclass_id','bbp.busp_no_units','bbp.busp_capital_investment','bbp.busp_essential','bbp.busp_non_essential','bbp.busp_total_gross','cctype_id','ptfoc_basis_id','ptfoc_gl_id','ptfoc_sl_id','ptfoc_constant_amount','ptfoc_json','gl_account_id','sl_id','tfoc_interest_fee','tfoc_surcharge_fee','tfoc_surcharge_sl_id','tfoc_interest_sl_id','pt.tfoc_id','busn_employee_total_no','busn_bldg_area','bb.pm_id','tfoc_divided_fee','tfoc_iterated_fee')
                ->where('busn_id',(int)$busn_id)
                ->whereNotIn('cctype_id',[3,4,5])
                ->where('ptfoc_is_active','=','1')
                ->where('tfoc_is_applicable','=','1')
                ->where('pt.app_code','=',$app_code)
                ->orderBy('bbp.id','ASC')
                ->get()->toArray();
        }
    }
    public function getMeasurePaxCharges($busn_id,$app_code){ 
        return DB::table('bplo_business_measure_pax AS bmp')
            ->join('bplo_business AS bb', 'bb.id', '=', 'bmp.busn_id')
            ->join('psic_tfocs AS pt', function ($join) {
                $join->on('pt.subclass_id', '=', 'bmp.subclass_id');
                $join->on('bmp.tfoc_id', '=', 'pt.tfoc_id');
            })
            ->join('cto_tfocs AS ct', 'ct.id', '=', 'bmp.tfoc_id')
            ->select('bmp.id AS busn_psic_id','busn_id','bmp.subclass_id','bmp.buspx_no_units','bmp.buspx_charge_id','bmp.buspx_capacity','cctype_id','ptfoc_basis_id','ptfoc_gl_id','ptfoc_sl_id','ptfoc_constant_amount','ptfoc_json','gl_account_id','sl_id','tfoc_interest_fee','tfoc_surcharge_fee','tfoc_surcharge_sl_id','tfoc_interest_sl_id','bmp.tfoc_id','busn_employee_total_no','busn_bldg_area','bb.pm_id','tfoc_divided_fee','tfoc_iterated_fee')
            ->where('busn_id',(int)$busn_id)
            ->where('cctype_id','=','3')
            ->where('ptfoc_is_active','=','1')
            ->where('pt.app_code','=',$app_code)
            ->get()->toArray();
    }
    public function getBasisFieldsTablesDtls($basis_id=0){
        return DB::table('cto_tfoc_basis')->select('basis_ref_table','basis_ref_field')->where('basis_status',1)->where('id',(int)$basis_id)->first();
    }
    public function getSubchargeInterest(){
        return DB::table('cto_tax_interest_surcharges')->first();
    }
    public function addAssesment($postdata){
        $isExist = DB::table('cto_bplo_assessment')->select('id')->where('assess_year',(int)$postdata['assess_year'])
            ->where('busn_id',(int)$postdata['busn_id'])
            ->where('busn_psic_id',(int)$postdata['busn_psic_id'])
            ->where('subclass_id',(int)$postdata['subclass_id'])
            ->where('app_code',(int)$postdata['app_code'])
            ->where('tfoc_id',(int)$postdata['tfoc_id'])->first();
        if(isset($isExist)){
            DB::table('cto_bplo_assessment')->where('id',(int)$isExist->id)->update($postdata);
            $lastId = $isExist->id;
        }else{
            $postdata['created_by']=!empty(\Auth::user())?\Auth::user()->id:1;
            $postdata['created_at']= date('Y-m-d H:i:s');
            DB::table('cto_bplo_assessment')->insert($postdata);
            $lastId = DB::getPdo()->lastInsertId();
        }
        $this->assIdDetails['assIds'][] = $lastId;
       return $lastId;
    }
    public function addAssesmentDetails($postdata){
        $isExist = DB::table('cto_bplo_assessment_details')->select('id')->where('assess_year',(int)$postdata['assess_year'])
            ->where('busn_id',(int)$postdata['busn_id'])
            ->where('busn_psic_id',(int)$postdata['busn_psic_id'])
            ->where('subclass_id',(int)$postdata['subclass_id'])
            ->where('assessment_period',(int)$postdata['assessment_period'])
            ->where('cb_assesment_id',(int)$postdata['cb_assesment_id'])
            ->where('app_code',(int)$postdata['app_code'])
            ->where('tfoc_id',(int)$postdata['tfoc_id'])->first();
        if(isset($isExist)){
            DB::table('cto_bplo_assessment_details')->where('id',(int)$isExist->id)->update($postdata);
            $lastId = $isExist->id;
        }else{
            $postdata['created_by']=!empty(\Auth::user())?\Auth::user()->id:1;
            $postdata['created_at']= date('Y-m-d H:i:s');
            DB::table('cto_bplo_assessment_details')->insert($postdata);
            $lastId = DB::getPdo()->lastInsertId();
        }
        $this->assIdDetails['assDtlsIds'][] = $lastId;
        return $lastId;
    }
    public function addFinalAssesmentDetails($postdata){
        $isExist = DB::table('cto_bplo_final_assessment_details')->select('id')
            ->where('assess_year',(int)$postdata['assess_year'])
            ->where('busn_id',(int)$postdata['busn_id'])
            ->where('app_code',(int)$postdata['app_code'])
            ->where('assessment_period',(int)$postdata['assessment_period'])->first();

        if(isset($isExist)){
            DB::table('cto_bplo_final_assessment_details')->where('id',(int)$isExist->id)->update($postdata);
            $lastId = $isExist->id;
        }else{
            $postdata['created_by']=!empty(\Auth::user())?\Auth::user()->id:1;
            $postdata['created_at']= date('Y-m-d H:i:s');
            DB::table('cto_bplo_final_assessment_details')->insert($postdata);
            $lastId = DB::getPdo()->lastInsertId();
        }
        $this->assIdDetails['finalAssIds'][] = $lastId;
        return $lastId;
    }
    public function getPerticularOutstandingDetails($reqData){
        $sql = DB::table('cto_bplo_final_assessment_details')->select(DB::raw("GROUP_CONCAT(assessment_details_ids) AS assessment_details_ids"))->where('payment_status',0)->where('assess_year',(int)$reqData['year'])->whereIn('app_code',[1,2])->where('busn_id',(int)$reqData['busn_id']);
        if($reqData['pm_id']>0){
            $sql->where('payment_mode',$reqData['pm_id']);
        }
        if($reqData['assesment_period']>0){
            $sql->where('assessment_period','<=',$reqData['assesment_period']);
        }
        return $sql->first();
    }
    public function getScheduleOutstandingDetails($reqData){
        //->where('app_code',(int)$reqData['app_code'])
        $sql = DB::table('cto_bplo_final_assessment_details')->where('payment_status',0)->where('assess_year',(int)$reqData['year'])->whereIn('app_code',[1,2])->where('busn_id',(int)$reqData['busn_id']);
        if($reqData['pm_id']>0){
            $sql->where('payment_mode',$reqData['pm_id']);
        }
        if($reqData['assesment_period']>0){
            $sql->where('assessment_period','<=',$reqData['assesment_period']);
        }
        return $sql->get()->toArray();
    }
    public function getFinalOutstandingDetails($assessment_details_ids,$busn_id){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.assess_year',DB::raw('SUM(ass.tfoc_amount) AS tfoc_amount'),DB::raw('SUM(surcharge_fee) AS surcharge_fee') ,DB::raw('SUM(interest_fee) AS interest_fee'),'sl.description','payment_mode','assessment_period','surcharge_sl_id','interest_sl_id','subclass_id','tfoc_id','agl_account_id','interest_rate','surcharge_rate','interest_rate_type','surcharge_rate_type')
            ->whereIn('ass.id',$assessment_details_ids)
            ->where('ass.busn_id',(int)$busn_id)
            ->groupBy('ass.assess_year','ass.cb_assesment_id')
            ->orderBy('ass.id','ASC')->get()->toArray();
    }

}
