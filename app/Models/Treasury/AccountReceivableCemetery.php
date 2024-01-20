<?php

namespace App\Models\Treasury;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class AccountReceivableCemetery extends Model
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

    public function GetCemeteryName(){
    	return DB::table('eco_data_cemeteries')->select('id','cem_name')->get();
    }

    public function Getlocationarray(){
    	$munid = "4";
    	$locality = DB::table('rpt_locality')
         ->select('id','mun_no')->where('department',5)->offset(0,1)->first();
         if(!empty($locality)){
         	$munid = $locality->mun_no;
         }
    	return DB::table('barangays')
         ->select('id','brgy_code','brgy_name')->where('is_active',1)->where('mun_no',$munid)->get();
    }

    public function getDetailsrows($id){
       return DB::table('cto_cashier_details AS cc')
          ->select('cc.tfc_amount','cc.sl_id','cc.agl_account_id','cc.surcharge_fee','cc.interest_fee')->where('cc.cashier_id',$id)->get();
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

    public function getpaymentList($request,$id){
        $params = $columns = $totalRecords = $data = array();
		    $params = $_REQUEST;
		    $q=$request->input('q');
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

		    $sql = DB::table('cto_cashier_details AS cc')
		          ->leftjoin('eco_cemetery_application as eca','eca.id','=','cc.cemetery_application_id')
		          ->select('cc.id as id','cc.created_at','cc.or_no','cc.cem_total_amount','cc.cem_paid_amount','cc.cem_remaining_balance','cc.cem_status')->where('cc.cemetery_application_id','=',$id);  ;
		    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		    
			 if(!empty($q) && isset($q)){
					$sql->where(function ($sql) use($q){
						$sql->where(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
							->orWhere(DB::raw('LOWER(cc.cem_total_amount)'),'like',"%".strtolower($q)."%")
							->orWhere(DB::raw('LOWER(cc.cem_paid_amount)'),'like',"%".strtolower($q)."%");
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

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate = $request->input('todate');
    $cemetery = $request->input('cemetery');
    $location = $request->input('location');

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
   		  ->leftjoin('citizens AS c', 'c.id', '=', 'cc.client_citizen_id')
		  ->leftjoin('eco_cemetery_application as eca','eca.or_no','=','cc.or_no')
		  ->leftjoin('barangays as b','b.id','=','eca.location_id')
          ->select('eca.id as id','eca.transaction_no','b.brgy_name','c.cit_fullname','eca.location_id','eca.full_address','eca.total_amount','eca.remaining_amount','cc.top_transaction_id','cc.or_no','cc.total_paid_amount','eca.status')->where('eca.remaining_amount','>',0);
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
     if($cemetery > 0){
     	 $sql->where('eca.cemetery_id','=',$cemetery);     
     }  
     if($location > 0){
     	 $sql->where('eca.location_id','=',$location);     
     }    
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q){
				$sql->where(DB::raw('LOWER(c.cit_fullname)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.top_transaction_id)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(eca.status)'),'like',"%".strtolower($q)."%");
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
	
	public function getListexport(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
		  $sql = DB::table('cto_cashier AS cc')
   		  ->leftjoin('citizens AS c', 'c.id', '=', 'cc.client_citizen_id')
		  ->leftjoin('eco_cemetery_application as eca','eca.or_no','=','cc.or_no')
		  ->leftjoin('barangays as b','b.id','=','eca.location_id')
          ->select('eca.id as id','eca.transaction_no','b.brgy_name','c.cit_fullname','eca.location_id','eca.full_address','eca.total_amount','eca.remaining_amount','cc.top_transaction_id','cc.or_no','cc.total_paid_amount','eca.status')->where('eca.remaining_amount','>',0);
		      $sql->orderBy('cc.id','ASC');

		    /*  #######  Get count without limit  ###### */
		     $data_cnt=$sql->count();
		    /*  #######  Set Offset & Limit  ###### */
		     $data=$sql->get();
		     return $data;
	 }
}
