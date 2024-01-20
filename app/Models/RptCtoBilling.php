<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\Helper;

class RptCtoBilling extends Model
{
    use HasFactory;

    protected $appends = ['period_covered','total_due'];

    public function getBasicRates($class=''){
    	return DB::table('rpt_cto_taxrates')->where('pc_class_code',$class)->where('is_active',1)->first();
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class,'brgy_code');
    }

    public function addTopTransactions($columns){
        DB::table('cto_top_transactions')->insert($columns);
        return DB::getPdo()->lastInsertId();
    }

    public function updateTopTransactions($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }

    public function getPropertyClasscode(){
       return DB::table('rpt_property_classes')->select('id','pc_class_code')->get();
    }

    public function deleteBillingPenaltyrow($id){
        return DB::table('rpt_cto_billing_details_penalties')->where('cb_code',$id)->delete();
    }

    public function deleteBillingDiscountrow($id){
        return DB::table('rpt_cto_billing_details_discounts')->where('cb_code',$id)->delete();
    }

    public function deleteBillingDetailsrow($id){
        return DB::table('rpt_cto_billing_details')->where('cb_code',$id)->delete();
    }

    public function deleteBillingsrow($id){
       return DB::table('rpt_cto_billings')->where('id',$id)->delete();
    }

    public function getcountofcontrolno($cb_control_no){
      return DB::table('rpt_cto_billings')->where('cb_control_no',$cb_control_no)->get();
    }

    public function getcontrolno($id){
        return DB::table('rpt_cto_billings')->select('cb_control_no','transaction_id')->where('id',$id)->first();
    } 

    public function deleteTopTransactionrow($transid){
      return DB::table('cto_top_transactions')->where('id',$transid)->where('tfoc_is_applicable','2')->delete();
    }

    public function getPeriodCoveredAttribute()
    {
        $firstRecord = $this->billingDetails->first();
        $lastRecord  = $this->billingDetails->last();
        //dd($firstRecord);
        if($firstRecord != null && $firstRecord->sd_mode == '14'){
            return '1st Qtr, '.$this->cb_covered_from_year.' to 4th Qtr, '.$this->cb_covered_to_year;
        }else{
            if($firstRecord != null){
                return Helper::billing_quarters()[$firstRecord->sd_mode].', '.$this->cb_covered_from_year.' to '.Helper::billing_quarters()[$lastRecord->sd_mode].', '.$this->cb_covered_to_year;
            }else{
                return '';
            }
            
        }
    }

    public function getBillingDetails($id=''){
        return DB::table('rpt_cto_billing_details as cbd')
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
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','cbd.cbd_covered_year','cbd.sd_mode',
                                DB::raw('(COALESCE(cbd.basic_amount,0)+COALESCE(cbdp.basic_penalty_amount,0)-COALESCE(cbdd.basic_discount_amount,0)) as basicAmount'),
                                DB::raw('(COALESCE(cbd.sef_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)-COALESCE(cbdd.sef_discount_amount,0)) as sefAmount'),
                                DB::raw('(COALESCE(cbd.sh_amount,0)+COALESCE(cbdp.sh_penalty_amount,0)-COALESCE(cbdd.sh_discount_amount,0)) as shAmount'),

                                DB::raw('COALESCE(cbd.basic_amount,0) as basicAmountOnly'),
                                DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basicPenaltyOnly'),
                                DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basicDiscOnly'),

                                DB::raw('COALESCE(cbd.sef_amount,0) as sefAmountOnly'),
                                DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sefPenaltyOnly'),
                                DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sefDiscOnly'),

                                DB::raw('COALESCE(cbd.sh_amount,0) as shAmountOnly'),
                                DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as shPenaltyOnly'),
                                DB::raw('COALESCE(cbdd.sh_discount_amount,0) as shDiscOnly'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDueNew'),
                               )
                             ->where('cbd.cb_code',$id)
                             ->get();
    }

    public function getBillingDetailsByControlNo($cno='')
    {
        //dd($cno);
       return $this->from('rpt_cto_billings as cb')
                             ->join('rpt_cto_billing_details as cbd','cbd.cb_code','=','cb.id')
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
                             ->join('rpt_properties as rp','rp.id','=','cb.rp_code')
                             ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                             ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                             
                             ->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no','rp.rp_pin_declaration_no','pk.pk_code','cb.cb_is_paid','cb.id','cb.rp_code','cbd.cbd_covered_year','c.full_name','rp.rp_lot_cct_unit_desc',
                                DB::raw('MAX(cbd.cbd_covered_year) as endYear'),
                                DB::raw('MIN(cbd.cbd_covered_year) as startYear'),
                                DB::raw('MAX(cbd.sd_mode) as endSdmode'),
                                DB::raw('MIN(cbd.sd_mode) as startSdmode'),
                                DB::raw('(SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbdp.basic_penalty_amount,0))-SUM(COALESCE(cbdd.basic_discount_amount,0))) as basicAmount'),
                                DB::raw('(SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))-SUM(COALESCE(cbdd.sef_discount_amount,0))) as sefAmount'),
                                DB::raw('(SUM(COALESCE(cbd.sh_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0))-SUM(COALESCE(cbdd.sh_discount_amount,0))) as shAmount'),

                                DB::raw('SUM(COALESCE(cbd.basic_amount,0)) as basicAmountOnly'),
                                DB::raw('SUM(COALESCE(cbdp.basic_penalty_amount,0)) as basicPenaltyOnly'),
                                DB::raw('SUM(COALESCE(cbdd.basic_discount_amount,0)) as basicDiscOnly'),

                                DB::raw('SUM(COALESCE(cbd.sef_amount,0)) as sefAmountOnly'),
                                DB::raw('SUM(COALESCE(cbdp.sef_penalty_amount,0)) as sefPenaltyOnly'),
                                DB::raw('SUM(COALESCE(cbdd.sef_discount_amount,0)) as sefDiscOnly'),

                                DB::raw('SUM(COALESCE(cbd.sh_amount,0)) as shAmountOnly'),
                                DB::raw('SUM(COALESCE(cbdp.sh_penalty_amount,0)) as shPenaltyOnly'),
                                DB::raw('SUM(COALESCE(cbdd.sh_discount_amount,0)) as shDiscOnly'),

                                DB::raw('((SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbd.sh_amount,0)))+(SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0)))-(SUM(COALESCE(cbdd.basic_discount_amount,0))+SUM(COALESCE(cbdd.sef_discount_amount,0))+SUM(COALESCE(cbdd.sh_discount_amount,0)))) as totalDueNew'),
                               )
                             ->where('cb.cb_control_no',$cno)
                             ->groupBy('cb.id')
                             ->get();
    }

    public function getLastPaymentDetails($id=''){
        $data = DB::table('cto_cashier_real_properties as ccrp')
                        ->select('cc.or_no','cc.cashier_or_date')
                        ->join('cto_cashier as cc',function($j){
                            $j->on('cc.id','=','ccrp.cashier_id')->where('cc.status',1)->where('cc.tfoc_is_applicable',2);
                        })
                        ->where('ccrp.tfoc_is_applicable',2)
                        ->where('rp_code',$id)
                        ->orderBy('ccrp.id','DESC')
                        ->first();
        return $data;                
    }
    public function getTotalDueAttribute()
    {
        $allBillingIds = DB::table('rpt_cto_billings')->where('cb_control_no',$this->cb_control_no)->pluck('id');
        
            $totalBasicDue = DB::table('rpt_cto_billing_details')->select(DB::raw('COALESCE(SUM(rpt_cto_billing_details.basic_total_due),0) as totalBasicDue'))->whereIn('cb_code',$allBillingIds->toArray())->first();
            $totalPenaltyDue = DB::table('rpt_cto_billing_details_penalties')->select(DB::raw('COALESCE(SUM(rpt_cto_billing_details_penalties.penalty_total_due),0) as totalPenaltyDue'))->whereIn('cb_code',$allBillingIds->toArray())->first();
            $totalDisccount = DB::table('rpt_cto_billing_details_discounts')->select(DB::raw('COALESCE(SUM(rpt_cto_billing_details_discounts.dicount_total_due),0) as totalDiscountDue'))->whereIn('cb_code',$allBillingIds->toArray())->first();
            $data = [
            'totalBasicDue'   => $totalBasicDue->totalBasicDue,
            'totalPenaltyDue' => $totalPenaltyDue->totalPenaltyDue,
            'totalDiscount'   => $totalDisccount->totalDiscountDue,
            'netPayABleAmount'=> $totalBasicDue->totalBasicDue+$totalPenaltyDue->totalPenaltyDue-$totalDisccount->totalDiscountDue
        ];
        return $data;
        
    }

    public function addData($postdata, $updateCode = ''){
        DB::table('rpt_cto_billings')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function rptProperty(){
        return $this->belongsTo(RptProperty::class,'rp_code');
    }

    public function billTo(){
        return $this->belongsTo(RptPropertyOwner::class,'rpo_code');
    }

    public function addBillingDetailsData($postdata, $updateCode = ''){
        DB::table('rpt_cto_billing_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateBillingDetailsData($postdata, $updateCode = ''){
        DB::table('rpt_cto_billing_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

     public function addBillingDetailsDiscountData($postdata, $updateCode = ''){
        DB::table('rpt_cto_billing_details_discounts')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function addBillingDetailsPenaltyData($postdata, $updateCode = ''){
        DB::table('rpt_cto_billing_details_penalties')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function relatedBillings($value=''){
        return $this->hasMany(self::class,'cb_control_no');
    }

    public function billingDetails(){
        return $this->hasMany(RptCtoBillingDetail::class,'cb_code');
    }

    public function billingPenaltyDetails(){
        return $this->belongsTo(RptCtoBillingDetailsPenalty::class,'cbd_covered_year','cbd_covered_year');
    }

    public function billingDiscountDetails(){
        return $this->belongsTo(RptCtoBillingDetailsDiscount::class,'cbd_covered_year','cbd_covered_year');
    }


    public function updateData($id,$columns, $updateCode = ''){
        return DB::table('rpt_cto_billings')->where('id',$id)->update($columns);
        
    }

    public function getPaymentScheduledData($yaer='',$mode = ''){
    	return DB::table('rpt_cto_payment_schedules')->where('rcpsched_year',$yaer)->where('sd_mode',$mode)->where('is_active',1)->first();
    }

    public function getPenalityRateData($value=''){
        return DB::table('rpt_cto_penalty_schedules')->where('cps_from_year','<=',$value)->where('cps_to_year','>=',$value)->first();
    }

    public function getActivetdinbarangay($id){
        return DB::table('rpt_properties')
                        ->select('id','rp_td_no','rp_tax_declaration_no')
                        ->where('pk_is_active',1)
                        ->where('is_deleted',0)
                        ->where('brgy_code_id','=',$id)
                        ->where('rp_app_taxability',1)
						->orderBy('created_at', 'DESC')
                        ->get();
    }

    public function getActivetdinbarangayForMultiple(){
        return DB::table('rpt_properties')
                        ->select('id','rp_td_no','rp_tax_declaration_no')
                        ->where('rp_app_taxability',1)
                        ->where('pk_is_active',1)
                        ->where('is_deleted',0)
						->orderBy('created_at', 'DESC')
                        ->get();
    }

    public function getMultipleList($request=''){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year = $request->input('year');
        $status = $request->input('status');
        $barangy = $request->input('barangay');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');
        $request->session()->put('multipleBillingSelectedBrgy',$barangy);
        $request->session()->put('multipleBillingSelectedRevsionYear',$year);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $totalDue = '((SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbd.sh_amount,0)))+(SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0)))-(SUM(COALESCE(cbdd.basic_discount_amount,0))+SUM(COALESCE(cbdd.sef_discount_amount,0))+SUM(COALESCE(cbdd.sh_discount_amount,0))))';
        $columns = array( 
          0 => 'rpt_cto_billings.cb_control_no',
          1 => "rpt_cto_billings.cb_control_no",
          2 => "clients.full_name",  
		  3 => "barangays.brgy_name",
          4 => "rpt_cto_billings.cb_billing_date",
          //4 => DB::raw("SUM(child.basic_total_due)"),
		  5 => "rpt_cto_billings.transaction_no",
		  6 => DB::raw($totalDue),
		  7 =>"cb_is_paid"
         );
                $sql = $this->select('rpt_cto_billings.*','clients.full_name',
                    DB::raw($totalDue.' as totalDueNew')) 
                  ->with([
                    'rptProperty.barangay','relatedBillings'
                ])
                  ->join('barangays','barangays.id','=','rpt_cto_billings.brgy_code')
                  ->join('clients','clients.id','=','rpt_cto_billings.rpo_code')
                  ->join('rpt_cto_billing_details as cbd','cbd.cb_code','=','rpt_cto_billings.id')
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
                  ->where('rpt_cto_billings.cb_billing_mode',1)
                  ->groupBy('rpt_cto_billings.cb_control_no');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(clients.full_name)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(rpt_cto_billings.transaction_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere('rpt_cto_billings.cb_control_no','like',$q)
                        //->orWhere(DB::raw('LOWER(rpt_cto_billings.cb_billing_date)'),'like',"%".strtolower($q)."%")
                        //->having(DB::raw("SUM(child.basic_total_due)"),$q)
						;
   
           });
        }
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rpt_cto_billings.rvy_revision_year',$year);

            });
        }if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('rpt_cto_billings.brgy_code',$barangy);

            });
        }

        if(!empty($startdate) && isset($startdate)){
            $sql->whereDate('rpt_cto_billings.cb_billing_date','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $sql->whereDate('rpt_cto_billings.cb_billing_date','<=',trim($enddate));  
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      }else{
          $sql->orderBy('rpt_cto_billings.cb_control_no','DESC');
      }

        /*  #######  Get count without limit  ###### */
    
        $data_cnt=$sql->get()->count();
		
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
		/* echo '<pre>';
		print_r($data);die; */
        //dd($data->toArray());
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year = $request->input('year');
        $barangy = $request->input('barangay');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');
        $request->session()->put('billingSelectedBrgy',$barangy);
        $request->session()->put('billingSelectedRevsionYear',$year);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 => "rpt_cto_billings.cb_control_no",
          2 => "rpt_cto_billings.rp_td_no",
          3 => "clients.full_name",
          4 => "barangays.brgy_name",
          5 => 'rpt_cto_billings.pk_code',
          6 => "rpt_cto_billings.cb_billing_date",
          7 => "rpt_cto_billings.cb_covered_from_year",
          8 => "rpt_cto_billings.cb_assessed_value",
          9 => "rpt_cto_billings.transaction_no",
          10 => "rpt_cto_billings.cb_or_no",
          11 => "amount_due",
          12 => "rpt_cto_billings.cb_is_paid",
		 
         );
           $sql = $this->select('rpt_cto_billings.*','clients.full_name')
                 ->addSelect(
                    [
                        'amount_due' => RptCtoBillingDetail::select(DB::raw("SUM(basic_total_due) AS amount_due"))
                                       ->whereColumn('cb_code', 'rpt_cto_billings.id'),  
                        'penalty_due' => RptCtoBillingDetailsPenalty::select(DB::raw("SUM(penalty_total_due) AS penalty_due"))
                                       ->whereColumn('cb_code', 'rpt_cto_billings.id'),
                        'discount' => RptCtoBillingDetailsDiscount::select(DB::raw("SUM(dicount_total_due) AS discount"))
                                       ->whereColumn('cb_code', 'rpt_cto_billings.id'),              
                ]
            ) 
                 ->with([
                    'rptProperty.barangay',
                ])
                 ->join('clients','clients.id','=','rpt_cto_billings.rpo_code')
                 ->join('barangays','barangays.id','=','rpt_cto_billings.brgy_code')
                 ->where('cb_billing_mode',0)
                 ->where('rpt_cto_billings.cb_is_paid','<>',2);
                 // ->where('pk_id',$propertyKindId); 
        if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				if(is_numeric($q)){
                    $sql->havingRaw('amount_due = '.$q);
                }else{
					$sql->Where(DB::raw('LOWER(rp_td_no)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(clients.full_name)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(rpt_cto_billings.cb_billing_date)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(rpt_cto_billings.transaction_no)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(rpt_cto_billings.cb_or_no)'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(rpt_cto_billings.cb_assessed_value)'),'like',"%".strtolower($q)."%");
                   }
			});
        }
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rpt_cto_billings.rvy_revision_year',$year);
            });
        }
		if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('rpt_cto_billings.brgy_code',$barangy);

            });
        }
        if(!empty($startdate) && isset($startdate)){
            $sql->whereDate('rpt_cto_billings.cb_billing_date','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $sql->whereDate('rpt_cto_billings.cb_billing_date','<=',trim($enddate));  
        }
		
		
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      }else{
          $sql->orderBy('rpt_cto_billings.id','DESC');
      }
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

          function checkExceptionForCurrentYear($rpPropertyCode){

        $response = ['status' => false,'penaltyRate' => 0,'pendingModes' => []];
        $lastPayment     = DB::table('rpt_cto_billing_details')
                                   ->where('rp_property_code',$rpPropertyCode)
                                   ->orderBy('id','DESC')
                                   ->first();
        if($lastPayment != null && in_array($lastPayment->sd_mode, [11,22,33])){
            if(date("Y") > $lastPayment->cbd_covered_year){
                 $allModes = Helper::billing_quarters();
                 $fromIndex = array_search($lastPayment->sd_mode,array_keys($allModes));
                 $sdModes = array_slice(array_keys($allModes),$fromIndex+1);
                 $getPenalityRateDate = DB::table('rpt_cto_penalty_tables')
                                        ->where('cpt_effective_year',$lastPayment->cbd_covered_year)
                                        ->where('cpt_current_year',date("Y"))
                                        ->first();

                    if($getPenalityRateDate != null){
                        $penalityRate        = $getPenalityRateDate->cpt_month_12;
                        $response['status'] = true;
                        $response['penaltyRate'] = $penalityRate;
                        $response['lastYear'] = $lastPayment->cbd_covered_year;
                    }

            }
        }   
        return $response;                     
    }

    public function getGrAndPreviousOwnerData($propCode = '',$rpCode = ''){
        $propDetails = RptProperty::find($rpCode);
        $chainObj    = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$rpCode)->first();
        $chain       = (isset($chainObj->rp_code_chain))?json_decode($chainObj->rp_code_chain):[];
        $previousOwners    = DB::table('rpt_properties as rp')
                          ->select('rp.rp_app_effective_year','rp.id','rp.rp_property_code','rp.rpo_code','rp.pk_is_active','rp.created_against','rp.rp_assessed_value')
                          //->where('rp.rp_property_code',$propCode)
                          //->whereIn('rp.pk_is_active',[9,1])
                          ->whereIn('rp.id',$chain);
                          if(isset($propDetails->uc_code) && $propDetails->uc_code == 18){
                            //$previousOwners=$previousOwners->where('created_against',$rpCode);
                          }
                            $previousOwners=$previousOwners->get();
                            //dd($previousOwners);
        /*$generalRevisionTds = DB::table('rpt_properties as rp')
                           ->select('rp.rp_app_effective_year','rp.id','rp.rp_property_code','rp.rpo_code','rp.pk_is_active','rp.created_against','rp.rp_assessed_value')
                           ->join('rpt_property_approvals as rpa',function($j)use($propCode){
                            $j->on('rpa.rp_property_code','=','rp.rp_property_code')->where('rpa.rp_property_code',$propCode);
                          })
                           ->where('rp.rp_property_code',$propCode)
                           ->where('rpa.rp_app_cancel_type',config('constants.update_codes_land.GR'))
                           ->get($previousOwners);*/
        //$newData = $previousOwners->merge($generalRevisionTds);  
        $newData = $previousOwners; 
        return $newData->unique()->sortBy('rp_app_effective_year');
                        
    }

    public function getRevenueCodeDetails($taxTrevId='',$taxRevenueYear,$pkCode = 0){
        $query =  DB::table('rpt_cto_tax_revenues as rev')
                          ->leftJoin('cto_tfocs as basic','basic.id','=','rev.basic_tfoc_id')
                          ->leftJoin('cto_tfocs as sef','sef.id','=','rev.sef_tfoc_id')
                          ->leftJoin('cto_tfocs as sht','sht.id','=','rev.sh_tfoc_id')   

                          ->leftJoin('cto_tfocs as basicdisc','basicdisc.id','=','rev.basic_discount_tfoc_id')
                          ->leftJoin('cto_tfocs as sefdisc','sefdisc.id','=','rev.sef_discount_tfoc_id')
                          ->leftJoin('cto_tfocs as shtdisc','shtdisc.id','=','rev.sh_discount_tfoc_id') 

                          ->leftJoin('cto_tfocs as basicpen','basicpen.id','=','rev.basic_penalty_tfoc_id')
                          ->leftJoin('cto_tfocs as sefpen','sefpen.id','=','rev.sef_penalty_tfoc_id')
                          ->leftJoin('cto_tfocs as shtpen','shtpen.id','=','rev.sh_penalty_tfoc_id')
                          
                          ->select('basic.gl_account_id as basic_gl_id','basic.sl_id as basic_sl_id','sef.gl_account_id as sef_gl_id','sef.sl_id as sef_sl_id','sht.gl_account_id as sh_gl_id','sht.sl_id as sh_sl_id','basicdisc.gl_account_id as basic_d_gl_id','basicdisc.sl_id as basic_d_sl_id','sefdisc.gl_account_id as sef_d_gl_id','sefdisc.sl_id as sef_d_sl_id','shtdisc.gl_account_id as sh_d_gl_id','shtdisc.sl_id as sh_d_sl_id','basicpen.gl_account_id as basic_p_gl_id','basicpen.sl_id as basic_p_sl_id','sefpen.gl_account_id as sef_p_gl_id','sefpen.sl_id as sef_p_sl_id','shtpen.gl_account_id as sh_p_gl_id','shtpen.sl_id as sh_p_sl_id','rev.basic_tfoc_id','rev.sef_tfoc_id','rev.sh_tfoc_id','rev.basic_discount_tfoc_id','rev.sef_discount_tfoc_id','rev.sh_discount_tfoc_id','rev.basic_penalty_tfoc_id','rev.sef_penalty_tfoc_id','rev.sh_penalty_tfoc_id','rev.id')
                          ->where('rev.trev_id',$taxTrevId)
                          ->where('rev.tax_what_year',$taxRevenueYear)
                          ->where('rev.pk_code',$pkCode)
                          ->first();
                          return $query;
    }
}
