<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class CpdoCashering extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }

    public function updateOrRegisterData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }
    
    public function updateOrAssignmentData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }

    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getfundcode(){
    	 return DB::table('acctg_fund_codes')->select('id','code')->get();
    }
    public function getBankarray(){
    	 return DB::table('cto_payment_banks')->select('id','bank_code')->get();
    }
    public function getEngOwners(){
        return DB::table('clients as c')
		->select('c.id','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('is_engg',1)->get();
    }
    public function Geteditrecord($id){
    	return DB::table('cto_cashier')->where('id',$id)->first();
    }
    public function getCitizens(){
    	return DB::table('citizens')->select('id','cit_last_name','cit_first_name','cit_middle_name')->get();
    }
    public function getTransactions(){
         return DB::table('cto_top_transactions as ctt')->select('ctt.id','ctt.transaction_no')->whereIn('ctt.top_transaction_type_id',[19,44])->where('is_paid',0)->get();
    }

    public function getTransactionsedit(){
         return DB::table('cto_top_transactions as ctt')->join('cpdo_application_forms as caf','caf.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->where('ctt.top_transaction_type_id','19')->get();
    }
    public function getTransactionsfordevelop(){
         return DB::table('cto_top_transactions as ctt')->join('cpdo_development_permits as cad','cad.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->whereIn('ctt.top_transaction_type_id',[19,44])->where('is_paid',0)->get();
    }
    public function getTransactionsfordevelopedit(){
         return DB::table('cto_top_transactions as ctt')->join('cpdo_development_permits as cad','cad.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->where('ctt.top_transaction_type_id','44')->get();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->get();
    }
    public function getCashierno(){
      return DB::table('cto_cashier')->select('cashier_issue_no','cashier_year')->orderby('id','DESC')->first();
    } 
    public function Gettaxfees(){
    	 return DB::table('cto_tfocs AS ctot')
   		  ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctot.fund_id') 
          ->leftjoin('cto_charge_types AS cct', 'cct.id', '=', 'ctot.ctype_id')
          ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
          ->select('ctot.id','aas.description as accdesc')->where('tfoc_is_applicable','5')->get();
    }

    public function getToptransactiontypeid($id){
      return DB::table('cto_top_transactions')->select('top_transaction_type_id')->where('id',$id)->first();
    }

    public function getZoningappfee($id){
        return DB::table('cpdo_application_forms')->select('penaltyamount','tfoc_id')->where('id',$id)->first();
    }

    public function getDevelopmentappfee($id){
      return DB::table('cpdo_development_permits')->select('penaltyamount','tfoc_id')->where('id',$id)->first();
    }

    public function Gettransactionnobyid($id){
      //echo "here"; exit;
       return DB::table('cpdo_application_forms as caf')->leftjoin('cto_top_transactions as ctt','caf.id', '=', 'ctt.transaction_ref_no')->leftjoin('clients as c','caf.client_id','=','c.id')->select('ctt.transaction_no','caf.penaltyamount','ctt.transaction_ref_no as appid','ctt.id as topid','caf.caf_total_amount','caf.tfoc_id',DB::raw('(caf.caf_total_amount + caf.penaltyamount) as caf_amount'),'caf.caf_control_no','caf.caf_date','caf.cm_id','caf.client_id')->where('ctt.id',$id)->get()->first();
    }

    public function getzoningappdata($id){
      return DB::table('cpdo_application_forms as app')->leftjoin('clients as c','c.id','=','app.client_id')->select('c.p_mobile_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('app.id',$id)->first();
    }

     public function getdevelopappdata($id){
      return DB::table('cpdo_development_permits as app')->leftjoin('clients as c','c.id','=','app.client_id')->select('c.p_mobile_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('app.id',$id)->first();
    }

    public function Gettransactionnobydevelop($id){
      //echo "here"; exit;
       return DB::table('cpdo_development_permits as cdp')->leftjoin('cto_top_transactions as ctt','cdp.id', '=', 'ctt.transaction_ref_no')->leftjoin('clients as c','cdp.client_id','=','c.id')->select('ctt.transaction_no','ctt.transaction_ref_no as appid','ctt.id as topid','cdp.penaltyamount','cdp.cdp_total_amount as caf_total_amount','cdp.tfoc_id',DB::raw('DATE(cdp.created_at) AS caf_date'),DB::raw('(cdp.cdp_total_amount + cdp.penaltyamount) as caf_amount'),'cdp.cdp_control_no as caf_control_no','cdp.client_id')->where('ctt.id',$id)->get()->first();
    }

    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    }

    public function getpositionbyid($id){
      return DB::table('hr_employees as he')->leftjoin('hr_designations as hd','he.hr_designation_id','=','hd.id')->select('he.id','hd.description','he.fullname')->where('he.id',$id)->first();
    }

    public function GetFeeamount($id){
    		return DB::table('cto_tfocs')->select('tfoc_amount')->where('id',$id)->first();
    }
    public function addCashierDetailsData($postdata){
        DB::table('cto_cashier_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

     public function deleteRequirements($id,$ids){
      return DB::table('cto_cashier_details')->where('cs_id',$id)->whereNotIn('req_id',$ids)->delete();
    }

     public function checkrecordisexist($tfocid,$Cashierid){
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('isotehrtaxes','0')->where('cashier_id',$Cashierid)->get();
    } 

    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }

    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }

    public function Getorregisterid($id,$ornumber){
       return DB::table('cto_payment_or_assignments')->select('id as assignid','cpor_id as id','ora_to','or_count')->where('ortype_id',$id)->where('ora_is_completed',0)->where('ora_from','<=',$ornumber)->orderby('id', 'ASC')->first();
    }

    public function GetFeedetails($id){
    	  return DB::table('cto_cashier_details')->select('cashier_year','tfoc_id','tfc_amount','ctc_taxable_amount')->where('cashier_id',$id)->where('isotehrtaxes','0')->orderby('id', 'ASC')->get();
    }
    public function GetPaymentcheckdetails($id){
    	  return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','3')->orderby('id', 'ASC')->get();
    }
    public function GetPaymentbankdetails($id){
    	  return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','2')->orderby('id', 'ASC')->get();
    }
    public function getCasheringIds($id){
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_gl_id','tfoc_surcharge_sl_id','fund_id')->where('id',$id)->first();
    }

    public function getGetOrrange($id){
    	return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_completed','0')->first();
    }

    public function UpdateOrused($id,$columns){
    	  return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->update($columns);
    }

    public function updateremotedata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_application_forms')->where('frgn_caf_id',$id)->update($columns);
    }

    public function updatelocaldata($id,$columns){
      return DB::table('cpdo_application_forms')->where('id',$id)->update($columns);
    }

    public function updateremotedevdata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_development_permits')->where('frgn_cdp_id',$id)->update($columns);
    }

    public function updateremotepaymentData($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('payment_history')->where('transaction_no',$id)->update($columns);
    }

    public function updatelocaldevdata($id,$columns){
      return DB::table('cpdo_development_permits')->where('id',$id)->update($columns);
    }

    public function getappidbytoptransaction($id){
         return DB::table('cto_top_transactions')->select('transaction_ref_no')->where('id',$id)->first();
    }

    public function GetcpdolatestOrNumber(){
       return DB::table('cto_cashier')->select('or_no')->orderby('id','DESC')->first();
    }

    public function getBusinessDetails($id){
       return DB::table('bplo_business')->select('busn_name','id')->where('client_id',$id)->get();
    }

    public function GetApplcationId($id){
       return DB::table('cto_top_transactions')->select('transaction_ref_no','top_transaction_type_id')->where('id',$id)->first();
    }

    public function getCertificateDetails($id){
         return DB::table('cto_cashier AS cc')
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
          ->select('cc.*','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.p_tin_no','c.icr_no','c.height','c.weight','c.birth_place','c.country','c.gender','c.dateofbirth','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('cc.id',$id)->first();
    }

    public function getDataUpdateFullname(){
       return DB::table('cto_cashier')->select('id','payee_type','client_citizen_id')->where('taxpayers_name','=',NULL)->get()->toArray();
    }

    public function addCashierPaymentData($postdata){
        DB::table('cto_cashier_other_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateTopTransaction($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }

     public function checkrecordisPaymentexist($tfocid,$Cashierid){
         return DB::table('cto_cashier_other_payments')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    }

    public function getPaymentOrSetup($ortype_id){
      return DB::table('cto_payment_or_setups')->where('user_id', Auth::user()->id)->where('ortype_id',$ortype_id)->first();
    }

    public function updateCashierPaymentData($id,$columns){
        return DB::table('cto_cashier_other_payments')->where('id',$id)->update($columns);
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
      0 =>"cc.id",
      1 =>"caf.caf_control_no",
      2 =>"c.full_name",
      3 =>"c.full_address",
      4 =>"cc.or_no",	
      5 =>"cc.total_amount",
      6 =>"cc.payment_terms",
      7=>'cc.status',
      8=>'cc.created_at'
    );

     $sql = DB::table('cto_cashier AS cc')
            ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
            ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id') 
            ->leftjoin('hr_employees as he','he.user_id','=','cc.created_by')
            ->select('cc.*','he.fullname','cc.total_paid_amount','cc.cashier_or_date','cc.or_no','cc.status','cc.created_at','c.full_name','top.transaction_no as top_no','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.full_address');
        $sql->where('cc.tfoc_is_applicable','=','5');
        if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
            }
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
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
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%");
			});
		}
	
 		if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cc.created_at','<=',trim($enddate));  
        }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('cc.created_at','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
