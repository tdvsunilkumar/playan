<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class BusinessGovDtiLists extends Model
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
        Session::put('dtilidtfromdate',$from_date); Session::put('dtilisttodate',$to_date); Session::put('dtilistsearch',$q);

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"bb.busn_name",  
          2 =>"bbt.btype_desc",
          3 =>"bb.busn_registration_no",  
          4 =>"bbpi.bpi_issued_date",
          5 =>"bbpi.bpi_permit_no",
          6 =>"bat.app_type",
          7 =>"bb.application_date",
          8 =>"cl.full_name",
		  9 =>"b.brgy_name",
		  14 =>"ornumber",
          15 =>"cl.p_mobile_no",
          16 =>"cl.p_email_address",
         );

        $sql = DB::table('bplo_business_history AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
			   ->Leftjoin('barangays AS b', 'b.id', '=', 'bb.busn_office_main_barangay_id')
			   ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
			   ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
			   ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->select('bb.*','bbt.btype_desc','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','cl.full_name','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',DB::raw("(select or_no from cto_cashier where cto_cashier.busn_id = bb.busn_id AND cto_cashier.cashier_year = bb.busn_tax_year ORDER BY cto_cashier.id DESC limit 1) as ornumber"),
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date',);
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
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_issued_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.application_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ornumber)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
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
      $from_date = Session::get('dtilidtfromdate');  $to_date = Session::get('dtilisttodate');  $q = Session::get('dtilistsearch'); 
      $sql = DB::table('bplo_business_history AS bb')
         ->join('clients AS cl','cl.id','=','bb.client_id')
         ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
         ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
         ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
		 ->Leftjoin('barangays AS b', 'b.id', '=', 'bb.busn_office_main_barangay_id')
	   ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
	   ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
	   ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->select('bb.*','bbt.btype_desc','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',DB::raw("(select or_no from cto_cashier where cto_cashier.busn_id = bb.busn_id AND cto_cashier.cashier_year = bb.busn_tax_year ORDER BY cto_cashier.id DESC limit 1) as ornumber"),
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
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_issued_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.application_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ornumber)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%");
                    
                }
                
            });
        }
        $data=$sql->get();
        return $data;
  }
}
