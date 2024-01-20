<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MonthlyCollection extends Model
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
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->select('cc.or_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cc.tcm_id','cc.tax_credit_gl_id','cc.tax_credit_sl_id','cc.tax_credit_amount')->where('cc.id',$id)->first();
    }

    public function GetDepartmrntsArray(){
      return DB::table('cto_payment_cashier_system')->select('id','pcs_name')->get();
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
    $month = $request->input('month');
    if(isset($month)){
    	$montharr = explode('/',$month);
    	$month = $montharr[1];
    }

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>"cc.created_at",
      2 =>"cc.or_no",
      3 =>"bb.busns_id_no",
      4 =>"c.full_name",	
      5 =>"bb.busn_name",
      6 =>'cc.total_amount'
     
    );

    $sql = DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')->leftjoin('bplo_business as bb','cc.busn_id','=','bb.id')->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')
          ->select('ort.ortype_name','cc.id','cc.tfoc_is_applicable','bb.busn_name','cc.or_no','cc.total_amount','cc.created_at','bb.busns_id_no','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('status','1');
     $sql->where('tfoc_is_applicable','=','1');    
     if($month > 0){
     $sql->whereMonth('cc.created_at', $month); 
     }   
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.created_at)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.total_amount)'),'like',"%".strtolower($q)."%")
					;
			});
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
    $month = $request->input('month');
    if(isset($month)){
      $montharr = explode('/',$month);
      $month = $montharr[1];
    }

    $sql = DB::table('cto_cashier AS cc')
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')->leftjoin('bplo_business as bb','cc.busn_id','=','bb.id')->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')
          ->select('ort.ortype_name','cc.id','cc.tfoc_is_applicable','bb.busn_name','cc.or_no','cc.total_amount','cc.created_at','bb.busns_id_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('status','1');
     $sql->where('tfoc_is_applicable','=','1');    
     if($month > 0){
     $sql->whereMonth('cc.created_at', $month); 
     }  
    
   
      $sql->orderBy('cc.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
}
