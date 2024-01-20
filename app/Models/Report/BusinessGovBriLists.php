<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class BusinessGovBriLists extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getTypeofOwnership(){
      return DB::table('bplo_business_type')->select('id','btype_desc')->get();
    }
    public function getdetails($id){
    		return DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->select('cc.or_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cc.tcm_id','cc.tax_credit_gl_id','cc.tax_credit_sl_id','cc.tax_credit_amount')->where('cc.id',$id)->first();
    }
    public function getCharges($field=''){
        $sql = DB::table('cto_charge_descriptions')->select('id','charge_desc')->where('is_active',1);
        if(!empty($field)){
            $sql->where($field,1);
        }
        return $sql->orderBy('charge_desc', 'ASC')->get()->toArray();
    }

    public function GetSubclassesArray(){
      return DB::table('psic_subclasses')->select('id','subclass_description')->get();
    }

     public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description','psic.busp_capital_investment','psic.busp_total_gross')->where('psic.busn_id',(int)$id)->get()->toArray();
    }

     public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        Session::put('birlidtfromdate',$from_date); Session::put('birlisttodate',$to_date); Session::put('birlistsearch',$q);

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"bb.busns_id_no",
          2 =>"bbpi.bpi_permit_no",  
          3 =>"bb.busn_name",
          4 =>"cl.full_name",
          5 =>"bb.busn_tin_no",
		  6 =>"b.brgy_name",
		  9 =>"bb.app_code",
          10 =>"bb.created_at",
         );

        $sql = DB::table('bplo_business_history AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
			   ->Leftjoin('barangays AS b', 'b.id', '=', 'bb.busn_office_main_barangay_id')
			  ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
			  ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
			  ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->select('bb.busn_name','bb.busn_tin_no','cl.full_name','bb.busn_office_main_barangay_id','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','bb.busn_id','bb.app_code','bb.busns_id_no','bb.created_at','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
		    if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bb.application_date','<=',$to_date);
            }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                {
                    $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_tin_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.created_at)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
						  if ($q === 'New' || $q === 'new') {
							  $sql->where('bb.app_code', '=', 1); 
						  }elseif ($q === 'Renew' || $q === 'renew') {
							  $sql->where('bb.app_code', '=', 2);
						  }elseif ($q === 'Retire' || $q === 'retire') {
							  $sql->where('bb.app_code', '=', 3);
						  }
					})
                   ;
                    
                }
                
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('bb.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        // $sql->groupBy('bbpi.bpi_year','bbpi.busn_id');
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  public function getDataExport(){
      $from_date = Session::get('birlidtfromdate');  $to_date = Session::get('birlisttodate');  $q = Session::get('birlistsearch'); 
      $sql = DB::table('bplo_business_history AS bb')
         ->join('clients AS cl','cl.id','=','bb.client_id')
         ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
         ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
               ->select('bb.busn_name','bb.busn_tin_no','bb.busn_office_main_barangay_id','bb.busn_id','bb.app_code','bb.busns_id_no','bb.created_at','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),
         'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
        if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bb.application_date','<=',$to_date);
            }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                {
                   $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_tin_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.created_at)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
						  if ($q === 'New' || $q === 'new') {
							  $sql->where('bb.app_code', '=', 1); 
						  }elseif ($q === 'Renew' || $q === 'renew') {
							  $sql->where('bb.app_code', '=', 2);
						  }elseif ($q === 'Retire' || $q === 'retire') {
							  $sql->where('bb.app_code', '=', 3);
						  }
					})
                   ;
                    
                }
                
            });
        }
        $data=$sql->get();
        return $data;
  }
}
