<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngCashering extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function updateDatajobrequest($id,$columns){
        return DB::table('eng_job_requests')->where('id',$id)->update($columns);
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
        return DB::table('clients as c')->join('eng_job_requests as ejr','c.id','=','ejr.client_id')->select('c.id','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('c.rpo_first_name','<>',NULL)->get();
    }
    public function updateTopTransaction($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    
    public function updateremotepaymentData($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('payment_history')->where('transaction_no',$id)->update($columns);
    }

    public function Geteditrecord($id){
    	return DB::table('cto_cashier')->where('id',$id)->first();
    }
    public function getlatestseries(){
        return DB::table('eng_job_requests')->select('permitnoseries')->orderby('permitnoseries','DESC')->get()->first();
    }

    public function updatePermitAppData($id,$columns){
        return DB::table('eng_bldg_permit_apps')->where('ejr_id',$id)->update($columns);
    }

    public function getapplicationtype($id){
           return DB::table('eng_job_requests')->select('es_id')->where('id',$id)->first();
    }

    public function getCitizens(){
    	return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name')->get();
    }
    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function getTransactions($id){
    	$ids =array(6,7,8,9,11,12,13,14,15,16,17,18);
         $sql= DB::table('cto_top_transactions as ctt')->join('eng_job_requests as ejr','ejr.id', '=', 'ctt.transaction_ref_no')->select('ctt.id','ctt.transaction_no')->where('is_approve',1)->whereIn('ctt.top_transaction_type_id',$ids);
         if($id<=0){
            $sql->where('is_paid','0');
        }
        return $sql->get();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->get();
    }
    public function Gettaxfees(){
    	 return DB::table('cto_tfocs AS ctot')
   		  ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctot.fund_id') 
          ->leftjoin('cto_charge_types AS cct', 'cct.id', '=', 'ctot.ctype_id')
          ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
          ->select('ctot.id','aas.description as accdesc')->where('tfoc_is_applicable','3')->get();
    }
    public function Gettransactionnobyid($id){
      //echo "here"; exit;
       return DB::table('eng_job_requests as ejr')
	   ->leftjoin('cto_top_transactions as ctt','ejr.id', '=', 'ctt.transaction_ref_no')
	   ->leftjoin('eng_services AS ees','ees.id','=','ejr.es_id')
     ->leftjoin('eng_application_type AS eat', 'eat.id', '=', 'ees.eat_id')
	   ->leftjoin('clients as c','ejr.client_id','=','c.id')
	   ->select('ejr.id as ejrid','ctt.transaction_no','ctt.id as topid','ejr.tfoc_id','ejr.id','ejr.ejr_total_net_amount','ejr.ejr_totalfees','ejr.ejr_surcharge_fee','ejr.ejr_jobrequest_no',DB::raw('DATE(ejr.created_at) AS created_at'),'eat.eat_module_desc','ejr.client_id')->where('ctt.id',$id)->get()->first();
    }
    public function getengappdata($id){
      return DB::table('eng_job_requests as app')->leftjoin('clients as c','c.id','=','app.client_id')->select('c.p_mobile_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('app.id',$id)->first();
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
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->where('isotehrtaxes','=','0')->get();
    }

    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }

    public function GetOthercharges($tfocid){
      return  DB::table('cto_tfoc_other_taxes')->where('tfoc_id',$tfocid)->get()->toArray();
    } 

    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }

    public function updateremotedata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('eng_job_requests')->where('frgn_ejr_id',$id)->update($columns);
    }

    public function updatelocaldata($id,$columns){
      return DB::table('eng_job_requests')->where('id',$id)->update($columns);
    }

    public function getappidbytoptransaction($id){
         return DB::table('cto_top_transactions')->select('transaction_ref_no')->where('id',$id)->first();
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
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id','fund_id')->where('id',$id)->first();
    }
    public function getCasheringsurchargeIds($id){
        return DB::table('cto_tfocs')->select('tfoc_surcharge_gl_id','tfoc_surcharge_sl_id','fund_id')->where('id',$id)->first();
    }
    public function getGetOrrange($id){
    	  return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_completed','0')->first();
    }

    public function UpdateOrused($id,$columns){
    	  return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->update($columns);
    }

    public function getCashierno(){
      return DB::table('cto_cashier')->select('cashier_issue_no','cashier_year')->orderby('id','DESC')->first();
    } 

    public function GetcpdolatestOrNumber(){
       return DB::table('cto_cashier')->select('or_no')->orderby('id','DESC')->first();
    }

    public function GetReqiestfees($id){
        return DB::table('eng_job_request_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('ejr_id',$id)->orderby('id','ASC')->get();
    }
    public function Getsurchargefee($id){
         return DB::table('eng_job_requests')->select('ejr_surcharge_fee','created_at')->where('id',$id)->first();
    }
    public function GetReqiestfeesaddon($id){
        return DB::table('eng_job_request_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('ejr_id',$id)->where('is_default','1')->orderby('id','ASC')->get();
    }

    public function getBusinessDetails($id){
       return DB::table('bplo_business')->select('busn_name','id')->where('client_id',$id)->get();
    }

    public function getCertificateDetails($id){
         return DB::table('cto_cashier AS cc')
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
          ->select('cc.total_amount','cc.or_no','cc.cashier_year','cc.created_at','cc.created_by','cc.payment_terms','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.p_tin_no','c.icr_no','cc.top_transaction_id','c.weight','c.birth_place','c.country','c.gender','c.dateofbirth','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('cc.id',$id)->first();
    }
    
    public function GetJobrequestId($id){
       return DB::table('cto_top_transactions')->select('transaction_ref_no')->where('id',$id)->first();
    }
    public function addCashierPaymentData($postdata){
        DB::table('cto_cashier_other_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function addeng_occupancy_detailsData($postdata){
        DB::table('cto_cashier_details_eng_occupancy')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateeng_occupancy_detailsData($id,$columns){
        return DB::table('cto_cashier_details_eng_occupancy')->where('id',$id)->update($columns);
    }
    public function checkeng_occupancy_details($feedesc,$cashier_id){
         return DB::table('cto_cashier_details_eng_occupancy')->select('*')->where('cashier_id',$cashier_id)->where('fees_description',$feedesc)->get();
    }

    public function checkrecordisPaymentexist($tfocid,$Cashierid){
         return DB::table('cto_cashier_other_payments')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    } 

    public function Getorregisterid($id,$ornumber){
       return DB::table('cto_payment_or_assignments')->select('id as assignid','cpor_id as id','ora_to','or_count')->where('ortype_id',$id)->where('ora_is_completed',0)->where('ora_from','<=',$ornumber)->orderby('id', 'ASC')->first();
    }

    public function updateCashierPaymentData($id,$columns){
        return DB::table('cto_cashier_other_payments')->where('id',$id)->update($columns);
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate =$request->input('todate');
    $status=$request->input('status');
    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"es.id",
      1 =>"cc.cashier_year",
      2 =>"c.full_name",
      3 =>"c.full_address",
      4 =>"cc.or_no",
      5 =>"cc.total_amount",
      6 =>"cc.payment_terms",
      7=>'cc.status',
      8=>'cc.created_at',
	  9=>'he.fullname'	  
    );

     $sql = DB::table('cto_cashier AS cc')
            ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
            ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id') 
            ->leftjoin('hr_employees as he','he.user_id','=','cc.created_by')
            ->select('cc.*','he.fullname','cc.total_paid_amount','cc.cashier_or_date','cc.or_no','cc.status','cc.created_at','cc.client_citizen_id','c.full_name','top.transaction_no as top_no','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.full_address');
        $sql->where('cc.tfoc_is_applicable','=','3');   
        if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
        } 
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(cc.cashier_year)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
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
				->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cc.total_amount)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cc.status)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cc.created_at)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
				;
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
