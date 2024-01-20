<?php
namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CashierBusinessPermit extends Model
{
    public function getTransactions($id){

        $sql = DB::table('cto_top_transactions as ctt')->join('cto_top_bplo as ctb','ctb.id', '=', 'ctt.transaction_ref_no')->join('bplo_business as bb','bb.id', '=', 'ctb.busn_id')->select('ctt.id','ctt.transaction_no')->where('tfoc_is_applicable',1)->where('is_final_assessment',1);
        if($id<=0){
            $sql->where('is_paid','0');
        }
        return $sql->get();
    }
    public function checkExistCreditAmout($bus_id,$previous_cashier_id,$current_cashier_id){
        $sql =  DB::table('cto_cashier')
            ->select('id','tax_credit_amount','or_no','cashier_or_date');
        if($previous_cashier_id>0){
            $sql->where('id',$previous_cashier_id);
        }else{
            $sql->where('tax_credit_is_useup',0);
            $sql->where('id','!=',$current_cashier_id);
            $sql->where('status',1);
        }

        if(!$previous_cashier_id && $current_cashier_id>0){}else{
            $sql->where('busn_id',(int)$bus_id)
                ->where('tax_credit_amount','>',0)
                ->where('tcm_id','>',0)
                ->orderby('id','DESC');

            return $sql->first();
        }
    }
    public function updateORInLocationClearance($busn_id=0,$cashierDetailsId=0,$data){
        $columns=array();
        $year = date("Y");
        $columns['cashierd_id']=$cashierDetailsId;
        $columns['cashier_id']=$data['cashier_id'];
        $columns['or_no']=$data['or_no'];
        $columns['or_date']=date("Y-m-d");
        $columns['or_amount']=$data['tfc_amount'];
        return DB::table('pdo_bplo_endosements')->where('pend_year',$year)->where('busn_id',$busn_id)->update($columns);
    }
    public function getEndDeptDetails(){
        return DB::table('bplo_endorsing_dept')->select('tfoc_id')->where('id',2)->first();
    }
    public function checkOrUsedOrNot($or_no){
       return DB::table('cto_cashier')->select('or_no')->where('ortype_id',2)->where('or_no',$or_no)->orderby('id','DESC')->exists();
    }

    public function updateOrRegisterData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }
    public function updateOrAssignmentData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }
    public function getappdatataxpayer($id){
        return DB::table('clients')->select('p_mobile_no','full_name')->where('id',$id)->first();
    }
    public function Getorregisterid($id,$ornumber){
       return DB::table('cto_payment_or_assignments')->select('cpor_id as id','ora_to')->where('ortype_id',$id)->where('ora_is_completed',0)->where('ora_from','<=',$ornumber)->orderby('id', 'ASC')->first();
    }


    public function checkORAssignedORNot(){
        return DB::table('cto_payment_or_assignments AS pa')
            ->join('cto_payment_or_type_details AS potd','pa.ortype_id', '=', 'potd.ortype_id')
            ->where('pa.ortype_id',2)->where('potd.pcs_id',1)->where('ora_is_active',1)->orderby('pa.id','ASC')->where('ora_is_completed','0')->exists();
    }
    public function getGetOrrange($id){
        return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_active',1)->orderby('id','ASC')->where('ora_is_completed','0')->first();
    }
    public function getLatestOrNumber($ortype_id){
        $createdBy = \Auth::user()->id;
       return DB::table('cto_cashier')->select('or_no')->where('tfoc_is_applicable',1)->where('ortype_id',$ortype_id)->where('created_by',$createdBy)->orderby('id','DESC')->first();
    }
    public function checkCreditFacilityExist(){
        return DB::table('cto_tax_credit_management')->select('id','tcm_gl_id','tcm_sl_id')->where('pcs_id',1)->where('tcm_status',1)->orderby('id','DESC')->first();
    }
    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function updateOrUsed($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->orderby('id','ASC')->where('ora_is_active',1)->limit(1)->update($columns);
    }
    public function getTopTransAssessmentIds($id){
        return DB::table('cto_top_transactions as ctt')->join('cto_top_bplo as ctb','ctb.id', '=', 'ctt.transaction_ref_no')->select('ctb.final_assessment_ids')->where('ctt.id','=',(int)$id)->first();
    }
    public function getFundCode(){
         return DB::table('acctg_fund_codes')->select('id','code')->get()->toArray();
    }
    public function getBankList(){
        return DB::table('cto_payment_banks')->select('id','bank_code')->get()->toArray();
    }
    public function getTfocDtls($id){
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id','fund_id')->where('id',$id)->first();
    }
    public function getTfocDtlsFromSLID($id){
        return DB::table('cto_tfocs')->select('gl_account_id')->where('sl_id',$id)->first();
    }
    public function getBusinessDetails($id){
        return DB::table('bplo_business')->select('busn_office_barangay_id','client_id','pm_id')->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function updateIssuanceData($id,$columns){
        return DB::table('bplo_business_retirement_issuance')->where('id',$id)->update($columns);
    }
    public function addRetirementIssuance($data){
        DB::table('bplo_business_retirement_issuance')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function getRetirementDetails($busn_id,$year){
        return   DB::table('bplo_business_retirement as bbr')
          ->Join('bplo_business as bb','bbr.busn_id','=','bb.id')
		  ->Join('clients AS c', 'c.id', '=', 'bb.client_id')
          ->select('bbr.id','bb.busn_name','bbr.retire_date_closed','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','bb.busn_office_main_barangay_id','bb.pm_id','bb.client_id')->where('bbr.busn_id',$busn_id)->where('retire_year',(int)$year)->first();
    }
    public function updateTopTransaction($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function updateFinalAssessment($ids,$columns){
        return DB::table('cto_bplo_final_assessment_details')->whereIn('id',$ids)->update($columns);
    }
    public function updateFinalAssessmentByTransNo($trans_no,$columns){
        return DB::table('cto_bplo_final_assessment_details')->where('top_transaction_no',$trans_no)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function checkRecordIsExist($tfocid,$Cashierid){
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    } 
    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }
    public function addCashierDetailsData($postdata){
        DB::table('cto_cashier_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateCashierPaymentData($id,$columns){
        return DB::table('cto_cashier_other_payments')->where('id',$id)->update($columns);
    }
    public function updateBusinessStatus($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }
    public function addCashierPaymentData($postdata){
        DB::table('cto_cashier_other_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getSubClassDtls($id){
        return DB::table('psic_subclasses')->select('section_id','division_id','group_id','class_id')->where('id',$id)->first();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->where('ocr_is_active','1')->orderby('ocr_reason', 'ASC')->get();
    } 
    public function getChequeTypes(){
      return DB::table('check_type_masters')->select('id','ctm_description')->where('is_active','1')->orderby('ctm_description', 'ASC')->get();
    } 
    public function getEditDetails($id){
        return DB::table('cto_cashier')->where('id',$id)->first();
    }
    public function getCasheirDetails($id){
        return DB::table('cto_cashier_details')->select('cashier_year','tfoc_id','tfc_amount','ctc_taxable_amount')->where('cashier_id',$id)->orderby('id', 'ASC')->get();
    }
    public function getPaymentModeDetails($id,$p_type){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)
        ->where('payment_terms',(int)$p_type)
        ->orderby('id', 'ASC')->get();
    }
    public function deleteOtherPaymentMode($p_type,$Cashierid){
        DB::table('cto_cashier_other_payments')
        ->where('payment_terms','!=',(int)$p_type)
        ->where('cashier_id',(int)$Cashierid)->delete();
    }
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $status=$request->input('status');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 =>"busns_id_no",
          2 =>"full_name",
          3 =>"busn_name",
          4 =>"transaction_no",
          5 =>"or_no",
          6 =>"total_amount",
          7 =>"total_paid_amount",
          8 =>"tax_credit_amount",
          9 =>"payment_terms",
          10 =>"cc.status",
          11 =>"cc.created_at"     
        );
        $sql = DB::table('cto_cashier AS cc')
            ->join('cto_top_transactions AS tp', 'tp.id', '=', 'cc.top_transaction_id') 
            ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->join('bplo_business AS bb', 'bb.id', '=', 'cc.busn_id') 
            ->select('cc.created_by','cc.payment_terms','total_amount','tax_credit_amount','tp.transaction_no','cc.id','cc.total_paid_amount','cc.or_no','cc.status','cc.created_at','suffix','c.full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'busn_name','busns_id_no'); 
            if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
            }
        $sql->where('cc.tfoc_is_applicable','=','1');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%") 
                ->orWhere(function ($sql) use ($q) {
                          if ($q === 'Cash' || $q === 'cash') {
                              $sql->where('cc.payment_terms', '=', 1);
                          } elseif ($q === 'Bank' || $q === 'bank') {
                              $sql->where('cc.payment_terms', '=', 2);
                          }elseif ($q === 'Cheque' || $q === 'cheque') {
                              $sql->where('cc.payment_terms', '=', 3);
                          }elseif ($q === 'Credit Card' || $q === 'credit card') {
                              $sql->where('cc.payment_terms', '=', 4); 
                          }elseif ($q === 'Online Payment' || $q === 'online payment' || $q === 'online' || $q === 'Online') {
                              $sql->where('cc.payment_terms', '=', 5); 
                          }else {
                              $sql->where('cc.payment_terms', '=', '');
                          }
                    })  
                ->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(total_paid_amount)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($startdate) && isset($startdate)){
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cc.created_at','<=',trim($enddate));  
        }
        // if(!empty($status) && isset($status)){
        //     $sql->where('cc.status', '=',$status);       
        // }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cc.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getTopTransactionDtls($id){
        return DB::table('cto_top_transactions as ctt')
            ->join('cto_top_bplo as ctb','ctb.id', '=', 'ctt.transaction_ref_no')
            ->join('bplo_business AS bb', 'bb.id', '=', 'ctb.busn_id') 
            ->join('clients AS c', 'c.id', '=', 'bb.client_id') 
            ->select('ctt.id','ctt.transaction_no','busn_id','final_assessment_ids','suffix','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'busn_name','busn_office_main_barangay_id','ctb.app_code','ctb.pm_id','ctb.pap_id','bb.client_id','top_year')
            ->where('ctt.id','=',(int)$id)  
            ->first();
    }
    public function getAssessmentDetails($bus_id,$final_assessment_ids){
        return DB::table('cto_bplo_final_assessment_details')
            ->select(DB::raw("GROUP_CONCAT(assessment_details_ids) AS assessment_details_ids"))
            ->whereIn('id',$final_assessment_ids)
            ->first()->assessment_details_ids;
    }
    public function getFinalAssessmentDetails($bus_id,$assessment_details_ids){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.assess_year',DB::raw('SUM(ass.tfoc_amount) AS tfoc_amount'),DB::raw('SUM(surcharge_fee) AS surcharge_fee') ,DB::raw('SUM(interest_fee) AS interest_fee'),'sl.description','payment_mode','assessment_period','surcharge_sl_id','interest_sl_id','subclass_id','tfoc_id','agl_account_id')
            ->whereIn('ass.id',$assessment_details_ids)
            ->where('ass.busn_id',(int)$bus_id)
            ->groupBy('ass.assess_year','ass.cb_assesment_id')
            ->orderBy('ass.id','ASC')->get()->toArray();
    }

    public function getCertificateDetails($id){
        return DB::table('cto_cashier AS cc')
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->select('cc.*','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.p_tin_no','c.icr_no','c.weight','c.birth_place','c.country','c.gender','c.dateofbirth','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('cc.id',$id)->first();
    }
    public function GetReqiestfees($id)
    {
        return DB::table('cto_cashier_details AS cc')->join('acctg_account_subsidiary_ledgers AS acctg', 'acctg.id', '=', 'cc.sl_id')->select('cc.*','cc.tfc_amount as tax_amount','acctg.description as fees_description')->where('cc.cashier_id',$id)->get();
    }
    public function GetPaymentcheckdetails($id){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','3')->orderby('id', 'ASC')->get();
    }
    public function GetPaymentbankdetails($id){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','2')->orderby('id', 'ASC')->get();
    }
}
