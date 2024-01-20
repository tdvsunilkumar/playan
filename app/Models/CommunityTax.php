<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CommunityTax extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function updateOrRegisterData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }
    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }
    public function updateOrAssignmentData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }
    public function getRptOwners(){
        return DB::table('clients')->select('id','full_name')->get();
    }
    public function getCashierno(){
      return DB::table('cto_cashier')->select('cashier_issue_no','cashier_year')->orderby('id','DESC')->first();
    }
    public function Geteditrecord($id){
    	return DB::table('cto_cashier')->where('id',$id)->first();
    }
    public function getCitizens(){
    	return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name')->get();
    }
    public function getCountries(){
         return DB::table('countries')->select('id','nationality')->get();
    } 
    public function Gettaxfees(){
    	 return DB::table('cto_tfocs AS ctot')
   		  ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctot.fund_id') 
          ->leftjoin('cto_charge_types AS cct', 'cct.id', '=', 'ctot.ctype_id')
          ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
          ->select('ctot.id','aas.description as accdesc')->where('tfoc_is_applicable','7')->get();
    }
    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->get();
    }
    public function getProfileDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_telephone_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_tin_no','dateofbirth','gender','icr_no','height','weight','birth_place','occupation','civil_status','country')->where('id',(int)$id)->first();
    }
    public function getCitizenDetails($id){
      return DB::table('citizens')
              ->select('cit_telephone_no','cit_house_lot_no','cit_street_name','cit_subdivision','cit_tin_no','cit_date_of_birth','cit_gender','icr_no','cit_height','cit_weight','birth_place','occupation','civil_status','country_id')->where('id',(int)$id)->first();
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
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    } 
    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }
    public function GetFeedetails($id){
    	  return DB::table('cto_cashier_details as cto')
                ->join('cto_tfocs as tfoc', 'tfoc.id', 'cto.tfoc_id')
                ->join('acctg_account_subsidiary_ledgers as sl', 'sl.id', 'tfoc.sl_id')
                ->select('cto.cashier_year','cto.tfoc_id','cto.tfc_amount','cto.ctc_taxable_amount', 'sl.description as fees_description', 'cto.tfc_amount as tax_amount')
                ->where('cashier_id',$id)
                ->orderby('cto.id', 'ASC')
                ->get();
    }
    public function getCasheringIds($id){
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id','fund_id')->where('id',$id)->first();
    }

    public function getGetOrrange($id){
    	  return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_completed','0')->first();
    }

    public function UpdateOrused($id,$columns){
    	  return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->update($columns);
    }

    public function GetcpdolatestOrNumber(){
       return DB::table('cto_cashier')->select('or_no')->orderby('id','DESC')->first();
    }

    public function getBusinessDetails($id){
       return DB::table('bplo_business')->select('busn_name','id')->where('client_id',$id)->get();
    }

    public function getTaxpayers($id=0){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_active',1)->orWhere('id',$id)->get()->toArray();
    }
    public function getappdatataxpayer($id){
        return DB::table('clients')->select('p_mobile_no','full_name')->where('id',$id)->first();
    }
    public function getappdatacitizen($id){
          return DB::table('citizens')->select('cit_mobile_no as p_mobile_no','cit_fullname as full_name')->where('id',$id)->first();
    }
    public function getCitizensforedit($id=0){
        return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name')->where('cit_is_active',1)->orWhere('id',$id)->get()->toArray();
    }
    public function getCertificateDetails($id){
         return DB::table('cto_cashier AS cc')
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
          ->select('cc.*','c.rpo_first_name','c.rpo_custom_last_name','c.p_tin_no','c.icr_no','c.height','c.weight','c.birth_place','c.country','c.gender','c.dateofbirth','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('cc.id',$id)->first();
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
    0 =>"es.id",
    1 =>"cc.cashier_year",
    2 =>"c.full_name",
    3 =>"c.full_address",
    3 =>"c.rpo_address_street_name",
	  3 =>"c.rpo_address_subdivision",
	  4 =>"cc.or_no",
	  5 =>"cc.total_paid_amount",
      6 =>"cc.payment_terms",
	  7 =>"cc.status",
	  8 =>"cc.created_at",
	  9 =>"he.fullname",
    );

    $sql = DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
		  ->leftjoin('hr_employees as he','he.user_id','=','cc.created_by') 
          ->select('cc.*','he.fullname','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.full_address');

    $sql->where('cc.tfoc_is_applicable', '=','7');
    if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
        }
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(cc.cashier_year)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
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
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_address)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.total_paid_amount)'),'like',"%".strtolower($q)."%")
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
