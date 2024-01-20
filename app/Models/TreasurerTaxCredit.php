<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TreasurerTaxCredit extends Model
{
    public $table = 'cto_tax_credit_management';
    
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_tax_credit_management')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_tax_credit_management')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_tax_credit_management')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_tax_credit_management')->where('id',$id)->first();
    }

    public function getPropertyKind(){
        return DB::table('rpt_property_kinds')->select('id','pk_description')->where('pk_is_active',1)->get();
    }
	
	 public function getPhysician(){
        return DB::table('cto_tax_credit_management')->select('*')->get();
    }
	public function FundCodes(){
        return DB::table('acctg_fund_codes')->select('*')->get();
    }
	public function Chargetypes(){
        return DB::table('cto_charge_types')->select('*')->get();
    }
	public function PaymentCashierSystem(){
        return DB::table('cto_payment_cashier_system')->select('*')->get();
    }
	
	public function Generalledgers(){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')
		->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')
		->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')
		->where('aagl.is_active',1)
		->where('aasl.is_parent',0)
		->where('aasl.is_hidden',0)
		->where('aasl.is_active',1)
		->get();
    }
	public function getAccountDesc($id)
   {
	return DB::table('acctg_account_subsidiary_ledgers')->select('id','gl_account_id','prefix','description')->where('is_active',1)->where('id','=',$id)->get();
   }
	
	public function getCitizens(){
        return DB::table('citizens')->select('*')->get();
    }
	
	public function getCitizensname($id){
      return DB::table('citizens as ci')
	  ->where('ci.id',$id)
	   ->join('barangays AS bar', 'bar.id', '=', 'ci.brgy_id')
	  ->select('ci.cit_age As cit_age','ci.cit_gender As cit_gender','bar.brgy_name As brgy_name')
	  ->first();
    }
    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"id",
		  1 =>"fund_id",
		  2 =>"ctype_id", 
	      3 =>"tcm_gl_id",
	      4 =>"tcm_sl_id",
		  5 =>"pcs_id", 
	      6 =>"tcm_remarks",
	      7 =>"tcm_status"
           
        );

        $sql = DB::table('cto_tax_credit_management As ctm')
			  ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctm.fund_id')
			  ->join('cto_charge_types AS cth', 'cth.id', '=', 'ctm.ctype_id')
			  ->join('cto_payment_cashier_system AS ccs', 'ccs.id', '=', 'ctm.pcs_id')
              ->select('ctm.*','afc.description','cth.ctype_desc','ccs.pcs_name');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tcm_remarks)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pcs_id)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(afc.description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cth.ctype_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ccs.pcs_name)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
