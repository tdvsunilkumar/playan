<?php

namespace App\Models\Bplo;
use App\Models\RptPropertyOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TreasurerAssessment extends Model
{
    public $table = '';

    public function getTransactionNumber($bus_id,$app_code=0,$year=0){
        return DB::table('cto_bplo_final_assessment_details')
            ->select('top_transaction_no')
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->where('top_transaction_no','>',0)
            ->where('payment_status',0)
            ->where('assess_year',(int)$year)->orderBy('id','DESC')->pluck('top_transaction_no')->first();
    }

    public function checkAllTopPaidTransaction($bus_id,$app_code=0,$year=0){
        $sql= DB::table('cto_bplo_final_assessment_details')
            ->select('top_transaction_no','payment_status','id','total_amount')
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->where('top_transaction_no','>',0);
        if($year>0){
            $sql->where('assess_year',(int)$year);
        }
        return $sql->orderBy('top_transaction_no','DESC')->limit(4)->get()->toArray();
    }

    public function checkTopPaidTransaction($bus_id,$app_code=0,$year,$pm_id=0,$period=0){
        $sql = DB::table('cto_top_bplo AS ctb')
            ->Join('cto_top_transactions AS ctt', 'ctt.transaction_ref_no', '=', 'ctb.id')
            ->select('ctt.id AS top_trans_id','ctb.id AS top_bplo_id','is_paid','transaction_no')
            ->where('ctt.top_transaction_type_id',1)
            ->where('ctt.tfoc_is_applicable',1)
            ->where('top_year',(int)$year)
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code);
        if($pm_id>0){
            $sql->where('pm_id',(int)$pm_id);
        }
        if($period>0){
            $sql->where('pap_id',(int)$period);
        }
        return $sql->orderBy('ctb.id','DESC')->first();
    }
    public function getTopTransactionFinalAsess($bus_id,$app_code=0,$year,$pm_id=0,$period=0){
         return DB::table('cto_bplo_final_assessment_details')
            ->select('top_transaction_no')
            ->where('assess_year',(int)$year)
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->where('payment_mode',(int)$pm_id)
            ->where('assessment_period',(int)$period)->first();
    }
    public function getPaymentMode(){ 
        return DB::table('cto_payment_mode')->select('id','pm_desc')->where('pm_status',1)->orderBy('pm_desc', 'ASC')->get()->toArray();
    }
    public function getSubsidiaryLedgerName($id){
        return DB::table('acctg_account_subsidiary_ledgers')->where('id',(int)$id)->pluck('description')->first();
    }
    public function getPendingAssessedYear($bus_id){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','total_amount','payment_mode','app_code','assess_due_date')->where('payment_status',0)->where('busn_id',(int)$bus_id)->orderBy('assess_year','ASC')->first();
    }
    public function getPaidPaymentDate($bus_id){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','total_amount','payment_mode','app_code','assess_due_date')->where('payment_status',1)->where('busn_id',(int)$bus_id)->orderBy('assess_year','DESC')->first();
    }
    public function getPreviousYearDetails($bus_id,$year){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','payment_mode','app_code')->where('assess_year', '=',(int)$year)->where('busn_id',(int)$bus_id)->orderBy('id','DESC')->first();
    }
    public function getFinalAssessementDetails($bus_id,$year,$app_code=0,$checkedPeriod=array()){
        $sql = DB::table('cto_bplo_final_assessment_details')->where('payment_status','!=',1)->where('assess_year', '=',(int)$year)->where('busn_id',(int)$bus_id)->where('app_code',(int)$app_code);
        if(count($checkedPeriod)>0){
            $sql->whereIn('assessment_period',$checkedPeriod);
        }
        return $sql->orderBy('id','ASC')->get()->toArray();
    }
    public function getTaxAssessementDetails($bus_id,$year,$pm_id,$assessment_period,$app_code=0){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.*','sl.description')
            ->where('assess_year', '=',(int)$year)
            ->where('assessment_period',(int)$assessment_period)
            ->where('payment_mode',(int)$pm_id)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$bus_id)->orderBy('id','ASC')->get()->toArray();
    }

    
    public function checkPaidRecord($bus_id,$pm_id,$assess_period,$year,$app_code=0){
        return DB::table('cto_bplo_final_assessment_details')->select('payment_date')->where('payment_status',1)
            ->where('busn_id',(int)$bus_id)
            ->where('assessment_period',(int)$assess_period)
            ->where('app_code',(int)$app_code)
            ->where('assess_year',(int)$year)
            ->exists();
    }
    public function getBusinessDetails($id){
        return DB::table('bplo_business AS bb')
            ->Leftjoin('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->leftJoin('barangays AS bgy', 'bgy.id', '=', 'bb.busn_office_main_barangay_id')
            ->select('bb.pm_id','busn_employee_total_no','bb.created_at','p_mobile_no','busn_office_main_barangay_id','is_final_assessment','bb.id','busn_tax_year','busn_name','busns_id_no','pm_desc','app_code','busn_app_status','busn_app_method','bb.created_at','suffix','cl.full_name','rpo_first_name','rpo_middle_name','p_email_address','rpo_custom_last_name','brgy_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"))
            ->where('bb.id',(int)$id)->first();
    }
    public function getActivityDetails($id,$app_code,$retire_id=0){
        if($app_code==3){ // Retire business
            return DB::table('bplo_business_retirement_psic AS brp')
                ->Join('psic_subclasses AS ps', 'brp.subclass_id', '=', 'ps.id')
                ->select('brp.busnret_capital_investment AS busp_capital_investment','brp.busnret_essential AS busp_essential','brp.busnret_non_essential AS busp_non_essential','subclass_description')
                ->where('busnret_id',(int)$retire_id)->get()->toArray();
        }else{
            return DB::table('bplo_business_psic AS bbp')
                ->Join('psic_subclasses AS ps', 'bbp.subclass_id', '=', 'ps.id')
                ->select('busp_capital_investment','busp_essential','busp_non_essential','subclass_description')
                ->where('busn_id',(int)$id)->get()->toArray();
        }
    }
    public function getYearDetails(){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year')->groupBy('assess_year')->orderBy('assess_year','DESC')->get()->toArray(); 
    }
    public function getTotalEmployee($retire_id){
        return DB::table('bplo_business_retirement')->where('id',(int)$retire_id)->pluck('retire_employee_total_no')->first();
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          0 =>"id",
          1 =>"busns_id_no",
          2 =>"full_name",
          3 =>"busn_name",
          4 =>"app_code",
          5 =>"bb.created_at",
          6 =>"busn_app_status",
          7 =>"pm_desc"
        );
        
        $sql = DB::table('bplo_business AS bb')
        ->Leftjoin('clients AS cl', 'bb.client_id', '=', 'cl.id')
        ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
        ->select('bb.id','busn_name','busns_id_no','pm_desc','bb.app_code','busn_app_status','busn_app_method','cl.full_name','suffix','bb.created_at','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'bb.busn_tax_year')
        ->where('busn_app_status','>=',3);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                    ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.created_at)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pm_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
						  if ($q === 'New' || $q === 'new') {
							  $sql->where('bb.app_code', '=', 1); 
						  } elseif ($q === 'renew' || $q === 'Renew') {
							  $sql->where('bb.app_code', '=', 2); 
						  }elseif ($q === 'Retire' || $q === 'retire') {
							  $sql->where('bb.app_code', '=', 3); 
						  }
					})
					->orWhere(function ($sql) use ($q) {
						  if ($q === 'Not Completed' || $q === 'Not Completed') {
							  $sql->where('busn_app_status', '=', 0); 
						  } elseif ($q === 'For Verification' || $q === 'For Verification') {
							  $sql->where('busn_app_status', '=', 1); 
						  }elseif ($q === 'For Endorsement' || $q === 'For Endorsement') {
							  $sql->where('busn_app_status', '=', 2); 
						  }elseif ($q === 'For Assessment' || $q === 'For Assessment') {
							  $sql->where('busn_app_status', '=', 3); 
						  }elseif ($q === 'For Payment' || $q === 'For Payment') {
							  $sql->where('busn_app_status', '=', 4); 
						  }elseif ($q === 'For Issuance' || $q === 'For Issuance') {
							  $sql->where('busn_app_status', '=', 5); 
						  }elseif ($q === 'License Issued' || $q === 'License Issued') {
							  $sql->where('busn_app_status', '=', 6); 
						  }elseif ($q === 'Declined' || $q === 'Declined') {
							  $sql->where('busn_app_status', '=', 7); 
						  }elseif ($q === 'Cancelled Permit' || $q === 'Cancelled Permit') {
							  $sql->where('busn_app_status', '=', 8); 
						  }
					})
					; 
            });
        }
        if(!empty($year)){
            $sql->whereExists(function ($query)use($year) {
               $query->select("id")
                    ->from('cto_bplo_final_assessment_details AS fa')
                    ->whereRaw('assess_year ='.(int)$year)
                    ->where('fa.busn_id','=',DB::raw('bb.id'));
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getDueDatesDetails($type){
        return DB::table('cto_payment_due_dates')->select('*')->where('app_type_id',(int)$type)->first();
    }
    public function getBussDetails($id){
        return DB::table('bplo_business')->select('*')->where('id',(int)$id)->first();
    }

    public function updateBusinessData($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }
    public function updateBusnRetirement($busn_id,$columns){
        return DB::table('bplo_business_retirement')->where('busn_id',$busn_id)->update($columns);
    }
    public function getReAssessModeDtls(){
        return DB::table('cto_bplo_re_assessment_payment_mode')->orderBy('id', 'DESC')->pluck('pmode_policy')->first();
    }
    public function addTopBplo($columns){
        DB::table('cto_top_bplo')->insert($columns);
        return DB::getPdo()->lastInsertId();
    }
    public function updateTopBplo($id,$columns){
        return DB::table('cto_top_bplo')->where('id',$id)->update($columns);
    }
    public function addTopTransactions($columns){
        DB::table('cto_top_transactions')->insert($columns);
        return DB::getPdo()->lastInsertId();
    }
    public function updateTopTransactions($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function updateFinalAssessment($ids,$columns){
        return DB::table('cto_bplo_final_assessment_details')->whereIn('id',$ids)->update($columns);
    }
    public function getBillDetails($transaction_no,$bus_id){
        return DB::table('cto_top_transactions AS tt')
        ->Leftjoin('cto_top_bplo AS tb', 'tb.id', '=', 'tt.transaction_ref_no')
        ->Leftjoin('bplo_business AS bb', 'bb.id', '=', 'tb.busn_id')
        ->select('tb.pm_id','tb.pap_id','tt.amount','tt.attachment','bb.client_id')
        ->where('transaction_no',$transaction_no)->orderBy('tt.id', 'DESC')->first();
    }
    public function getAssessDate($transaction_no){
        return DB::table('cto_top_transactions AS tt')
        ->where('transaction_no',$transaction_no)->orderBy('tt.id', 'DESC')->pluck('created_at')->first();
    }

    
}
