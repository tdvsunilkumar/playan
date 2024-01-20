<?php

namespace App\Models\Bplo;
use App\Models\RptPropertyOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RetireAssessment extends Model
{
    public $table = '';
    public function checkTopPaidTransaction($bus_id,$app_code=0,$year){
        return DB::table('cto_top_bplo AS ctb')
            ->Join('cto_top_transactions AS ctt', 'ctt.transaction_ref_no', '=', 'ctb.id')
            ->select('ctt.id AS top_trans_id','ctb.id AS top_bplo_id','is_paid','transaction_no')
            ->where('ctt.top_transaction_type_id',1)
            ->where('ctt.tfoc_is_applicable',1)
            ->where('top_year',(int)$year)
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->orderBy('ctb.id','DESC')->first();
    }
    public function getYearDetails(){
        return DB::table('bplo_business_retirement')->select('retire_year')->groupBy('retire_year')->orderBy('retire_year','DESC')->get()->toArray(); 
    }
    public function getPaymentMode(){ 
        return DB::table('cto_payment_mode')->select('id','pm_desc')->where('pm_status',1)->orderBy('pm_desc', 'ASC')->get()->toArray();
    }
    public function getSubsidiaryLedgerName($id){
        return DB::table('acctg_account_subsidiary_ledgers')->where('id',(int)$id)->pluck('description')->first();
    }
    public function getPaidPaymentDate($bus_id){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','total_amount','payment_mode','app_code','assess_due_date')->where('payment_status',1)->where('busn_id',(int)$bus_id)->orderBy('id','DESC')->first();
    }
    public function getPreviousYearDetails($bus_id,$year){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','payment_mode','app_code')->where('assess_year', '=',(int)$year)->where('busn_id',(int)$bus_id)->orderBy('id','DESC')->first();
    }
    public function getFinalAssessementDetails($bus_id,$year,$app_code=0){
        return DB::table('cto_bplo_final_assessment_details')->where('payment_status','!=',1)->where('assess_year', '=',(int)$year)->where('busn_id',(int)$bus_id)->where('app_code',(int)$app_code)->orderBy('id','ASC')->get()->toArray();
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
    public function checkAllTopPaidTransaction($bus_id,$app_code=0,$year){
        return DB::table('cto_bplo_final_assessment_details')
            ->select('top_transaction_no','payment_status','id')
            ->where('assess_year',(int)$year)
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->where('top_transaction_no','>',0)->orderBy('top_transaction_no','DESC')->limit(4)->get()->toArray();
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
            ->select('bb.pm_id','busn_employee_total_no','bb.created_at','p_mobile_no','busn_office_main_barangay_id','is_final_assessment','bb.id','busn_tax_year','busn_name','busns_id_no','pm_desc','app_code','busn_app_status','busn_app_method','bb.created_at','suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"))
            ->where('bb.id',(int)$id)->first();
    }
    public function getActivityDetails($id){
        return DB::table('bplo_business_psic AS bbp')
            ->Join('psic_subclasses AS ps', 'bbp.subclass_id', '=', 'ps.id')
            ->select('busp_capital_investment','busp_essential','busp_non_essential','subclass_description')
            ->where('busn_id',(int)$id)->get()->toArray();
    }

    public function getEditDetails($id){
        return DB::table('bplo_business_retirement AS br')
            ->Join('bplo_business AS bb', 'bb.id', '=', 'br.busn_id')
            ->Join('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->select('retire_is_final_assessment','bb.pm_id','busn_employee_total_no','bb.created_at AS busn_created_date','br.id','busn_name','retire_date_start','retire_date_closed','busns_id_no','br.busn_id','suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"))
            ->where('br.id',(int)$id)->first();

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
          1 =>"busns_id_no",
          2 =>"full_name",
          3 =>"busn_name",
          4 =>"retire_application_type",
          5 =>"retire_date_start",
          6 =>"retire_date_closed",  
        );
        $sql = DB::table('bplo_business_retirement AS br')
            ->join('bplo_business AS bb', 'bb.id', '=', 'br.busn_id') 
            ->join('clients AS c', 'c.id', '=', 'bb.client_id') 
            ->select('br.*',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'suffix','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','busn_name','busns_id_no');
        $sql->where("retire_status",">=",1);
            
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
              ;
            });
        }
        if(!empty($q) && isset($q)){
            $date = $q;
            $newretiredate =  date('Y-m-d',strtotime($date));  
            $sql->orWhere(DB::raw('LOWER(retire_date_start)'),'like',"%".strtolower($newretiredate)."%");
        }
        if(!empty($q) && isset($q)){
            $date = $q;
            $newclosedate =  date('Y-m-d',strtotime($date));  
            $sql->orWhere(DB::raw('LOWER(retire_date_closed)'),'like',"%".strtolower($newclosedate)."%");
        }
        if(!empty($q) && isset($q)){
            $appltype = (($q == 'Entire Business')? 2 : 1);
            $sql->orWhere(DB::raw('LOWER(retire_application_type)'),'like',"%".strtolower($appltype)."%");
        }
        if(!empty($year)){
            $sql->where("retire_year",(int)$year);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('br.id','DESC');

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
}
