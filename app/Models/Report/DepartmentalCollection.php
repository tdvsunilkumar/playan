<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DepartmentalCollection extends Model
{
	  public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getdetails($id){
    		return DB::table('cto_cashier AS cc')
   		  ->leftjoin('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->select('cc.or_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cc.tcm_id','cc.tax_credit_gl_id','cc.tax_credit_sl_id','cc.tax_credit_amount')->where('cc.id',$id)->first();
    }

    public function GetDepartmrntsArray(){
      return DB::table('cto_payment_cashier_system')->select('id','pcs_name')->get();
    }

    public function getDetailsrows($id){
       return DB::table('cto_cashier_details AS cc')
          ->select('cc.tfc_amount','cc.sl_id','cc.agl_account_id','cc.surcharge_fee','cc.interest_fee')->where('cc.cashier_id',$id)->get();
    }

    public function commonQueryForRptDetails($id = ''){
        return      DB::table('cto_cashier as cc')
                  ->join('cto_cashier_details as ccd','ccd.cashier_id','=','cc.id')
                  ->join('rpt_cto_billing_details as cbd','ccd.cbd_code','=','cbd.id')
                  ->join('rpt_properties as rp','rp.id','=','cbd.rp_code')
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
                             ->where('cc.tfoc_is_applicable',2)
                             ->where('cc.id',$id)
                             ->where('cc.status',1);
    }

    public function getTaxCreditTdWise($id = ''){
        $data = DB::table('cto_cashier as cc')
                    ->select('ccrp.rp_tax_declaration_no','acsl.description','acsl.prefix','aagl.code','aagl.description as agDesc',DB::raw('SUM(COALESCE(ccrp.tax_credit_amount,0)) as tax_credit_amount'))
                    ->join('cto_cashier_real_properties as ccrp','ccrp.cashier_id','=','cc.id')
                    ->leftJoin('acctg_account_subsidiary_ledgers as acsl','acsl.id','=','ccrp.tax_credit_sl_id')
                    ->leftJoin('acctg_account_general_ledgers as aagl', 'ccrp.tax_credit_gl_id', '=', 'aagl.id')
                    ->where('cc.id',$id)
                    ->where('cc.tfoc_is_applicable',2)
                    ->where('cc.status',1)
                    ->where('ccrp.tax_credit_is_useup',0)
                    ->groupBy('rp_code')
                    ->get();
                    return $data;
    }

    public function getYearlyWiseData($id = ''){
                            $sql = $this->commonQueryForRptDetails($id)->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no',
                                DB::raw('SUM(COALESCE(cbd.basic_amount,0)) as basicAmount'),
                                DB::raw('SUM(COALESCE(cbdd.basic_discount_amount,0)) as basicDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.basic_penalty_amount,0)) as basicPenalty'),

                                DB::raw('SUM(COALESCE(cbd.sef_amount,0)) as sefAmount'),
                                DB::raw('SUM(COALESCE(cbdd.sef_discount_amount,0)) as sefDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.sef_penalty_amount,0)) as sefPenalty'),

                                DB::raw('SUM(COALESCE(cbd.sh_amount,0)) as shAmount'),
                                DB::raw('SUM(COALESCE(cbdd.sh_discount_amount,0)) as shDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.sh_penalty_amount,0)) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                  
                  ->groupBy('cbd.cbd_covered_year')
                  ->get();
                  return $sql;
    }

    public function getTdWiseData($id = ''){
        $sql = $this->commonQueryForRptDetails($id)->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no',
                                DB::raw('SUM(COALESCE(cbd.basic_amount,0)) as basicAmount'),
                                DB::raw('SUM(COALESCE(cbdd.basic_discount_amount,0)) as basicDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.basic_penalty_amount,0)) as basicPenalty'),

                                DB::raw('SUM(COALESCE(cbd.sef_amount,0)) as sefAmount'),
                                DB::raw('SUM(COALESCE(cbdd.sef_discount_amount,0)) as sefDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.sef_penalty_amount,0)) as sefPenalty'),

                                DB::raw('SUM(COALESCE(cbd.sh_amount,0)) as shAmount'),
                                DB::raw('SUM(COALESCE(cbdd.sh_discount_amount,0)) as shDiscount'),
                                DB::raw('SUM(COALESCE(cbdp.sh_penalty_amount,0)) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                  
                  ->groupBy('cbd.rp_code')
                  ->get();
                  return $sql;
    }

    public function getDetailofEngDefault($id){
         return DB::table('cto_cashier_details_eng_occupancy')
          ->select('fees_description','tfc_amount')->where('cashier_id',$id)->get();
    }

    public function Gettdnoofrpt($id){
       return DB::table('cto_cashier_real_properties')
                  ->select('rp_tax_declaration_no',DB::raw("GROUP_CONCAT(DISTINCT rp_tax_declaration_no SEPARATOR '; ') as rp_tax_declaration_no"))
                  ->where('cashier_id',$id)
                  ->groupBy('cashier_id')
                  ->first();
    }

    public function getAccountGeneralLeaderbyid($id,$glid){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')
              ->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')
              ->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')
              ->where('aagl.is_active',1)
              ->where('aasl.is_parent',0)
              ->where('aasl.is_hidden',0)
              ->where('aasl.is_active',1)
              ->where('aasl.id',$id)
              ->where('aasl.gl_account_id',$glid)
              ->first();
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
	
    $enddate = $request->input('todate');
    $department = $request->input('department');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>"c.full_name",
      2 =>"bb.busn_name",
      3 =>"cc.cashier_particulars",
	    4 =>"cc.net_tax_due_amount",
      5 =>"ort.ortype_name",
	    6 =>"ctt.transaction_no",
	    7 =>"cc.or_no",
	    8 =>"cc.created_at",	  
      9 =>"cc.total_amount",
      11=>'cc.status',
	    12=>'u.name',
    );

    $sql = DB::table('cto_cashier AS cc')
   		  ->leftjoin('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
		  ->leftjoin('users as u','cc.created_by','=','u.id')
		  ->leftjoin('bplo_business as bb','cc.busn_id','=','bb.id')
		  ->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')
		  ->leftjoin('cto_top_transactions as ctt','ctt.id','=','cc.top_transaction_id')
          ->select('ort.ortype_name','cc.id','cc.net_tax_due_amount','ctt.transaction_no','cc.payee_type','cc.client_citizen_id','cc.tfoc_is_applicable','bb.busn_name','u.name as cashier','cc.or_no','cc.total_amount','cc.total_paid_amount','cc.created_at','cc.status','cc.cashier_particulars','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision');
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
     if($department > 0){
     	 $sql->where('cc.tfoc_is_applicable','=',$department);     
     }   
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q){
				$sql->where(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.net_tax_due_amount)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ort.ortype_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ctt.transaction_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.created_at)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.total_amount)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(u.name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.status)'),'like',"%".strtolower($q)."%");
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
      $sql->orderBy('cc.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
	
	public function getListexport($request){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
		$q=$request->input('q');
		$startdate =$request->input('fromdate');
		$enddate = $request->input('todate');
		$department = $request->input('department');

		$sql = DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')->leftjoin('users as u','cc.created_by','=','u.id')->leftjoin('bplo_business as bb','cc.busn_id','=','bb.id')->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')->leftjoin('cto_top_transactions as ctt','ctt.id','=','cc.top_transaction_id')
          ->select('ort.ortype_name','cc.id','ctt.transaction_no','cc.payee_type','cc.client_citizen_id','cc.tfoc_is_applicable','bb.busn_name','u.name as cashier','cc.or_no','cc.total_amount','cc.created_at','cc.status','cc.cashier_particulars','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision');
		//$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		 if($department > 0){
			 $sql->where('tfoc_is_applicable','=',$department);     
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
		
	   
		  $sql->orderBy('cc.id','ASC');

		/*  #######  Get count without limit  ###### */
		$data_cnt=$sql->count();
		$data=$sql->get();
		return array("data_cnt"=>$data_cnt,"data"=>$data);
	 }
}
