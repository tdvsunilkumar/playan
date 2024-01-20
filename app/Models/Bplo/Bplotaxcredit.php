<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Bplotaxcredit extends Model
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
   		  ->leftjoin('users as u','cc.created_by','=','u.id')
          ->select('cc.or_no','u.name as cashier','cc.tax_credit_amount','cc.cashier_or_date','cc.total_amount','cc.tax_credit_gl_id','cc.tax_credit_sl_id')->where('cc.id',$id)->first();
    }

    public function getdetailsutilized($id){
      return DB::table('cto_cashier AS cc')
        ->leftjoin('users as u','cc.created_by','=','u.id')
          ->select('cc.or_no','u.name as cashier','cc.tax_credit_amount','cc.cashier_or_date','cc.total_amount','cc.tax_credit_gl_id','cc.tax_credit_sl_id')->where('cc.previous_cashier_id',$id)->first();
    }

    public function GetBussinessids(){
      return DB::table('bplo_business')->select('id','busns_id_no','busn_name')->where('busns_id_no','<>',NULL)->get();
    }

    public function getDetailsrows($id){
       return DB::table('cto_cashier_details AS cc')
          ->select('cc.tfc_amount','cc.sl_id','cc.agl_account_id','cc.surcharge_fee','cc.interest_fee')->where('cc.cashier_id',$id)->get();
    }

    public function getDetailofEngDefault($id){
         return DB::table('cto_cashier_details_eng_occupancy')
          ->select('fees_description','tfc_amount')->where('cashier_id',$id)->get();
    }

    public function getAccountGeneralLeaderbyid($id,$glid){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')->where('aagl.is_active',1)->where('aasl.is_parent',0)->where('aasl.is_hidden',0)->where('aasl.is_active',1)->where('aasl.id',$id)->where('aasl.gl_account_id',$glid)->first();
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate = $request->input('todate');
    $businessid = $request->input('businessid');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>"bb.busns_id_no",
      2 =>"c.full_name",
      3 =>"bb.busn_name",
      4 =>"cc.or_no",	
      5 =>"cc.total_amount",
      6=>'cc.cashier_or_date',
      7=>'cc.tax_credit_amount',
      9=>'cc.tax_credit_is_useup',
     
    );

    $sql = DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')->leftjoin('users as u','cc.created_by','=','u.id')->leftjoin('bplo_business as bb','cc.busn_id','=','bb.id')->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')
          ->select('bb.busns_id_no','cc.id','cc.previous_cashier_id','cc.cashier_or_date','bb.busn_name','cc.tfoc_is_applicable','cc.or_no','cc.total_amount','cc.created_at','cc.tax_credit_is_useup','cc.tax_credit_amount','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','cc.tax_credit_gl_id','cc.tax_credit_sl_id');
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
    if($businessid){
      $sql->where('bb.id','=',$businessid); 
    }
     $sql->where('tfoc_is_applicable','=','1'); 
     $sql->where('tax_credit_amount','>','0');    
     $sql->where('cc.status',1); 
       
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cc.total_amount)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cc.cashier_or_date)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cc.tax_credit_amount)'),'like',"%".strtolower($q)."%");
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
}
