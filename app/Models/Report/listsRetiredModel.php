<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class listsRetiredModel extends Model
{
    public function getList($request){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
		$q=$request->input('q');
		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		
		if(!isset($params['start']) && !isset($params['length'])){
		  $params['start']="0";
		  $params['length']="10";
		}
		
		$columns = array( 
		  1 =>"bb.busn_name",
		  2 =>"bb.busn_office_main_building_no",	
		  3 =>"bbr.retire_application_type",
		  4 =>"bbr.busn_id",
		  5 =>"bb.busns_id_no",
		  6 =>"retire_reason_remarks",
		  7 =>"bbr.retire_date_closed",
		  8 =>"bbr.retire_date_start",
		  9 =>"retire_date_closed",
		  10 =>"c.full_name",
		  11 =>"c.p_mobile_no",
		);

		$sql = DB::table('bplo_business_retirement AS bbr')
			  ->join('bplo_business_retirement_issuance AS bbri', 'bbri.retire_id', '=', 'bbr.id')
			  ->leftjoin('bplo_business as bb','bb.id','=','bbr.busn_id')
			  ->leftjoin('clients AS c', 'c.id', '=', 'bb.client_id')
			  ->select('bbr.*','bb.busn_name','bb.busns_id_no','c.full_name','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision','bb.busn_office_main_barangay_id');   
				//$sql->where('bbr.prev_app_code','=','3');
		if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bbr.retire_date_closed','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bbr.retire_date_closed','<=',$to_date);
        }
		 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_building_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_reason_remarks)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_closed)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_start)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.busn_id)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
                          if ($q === 'per line of business' || $q === 'Per Line of Business') {
                              $sql->where('bbr.retire_application_type', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'entire business' || $q === 'Entire Business') {
                              $sql->where('bbr.retire_application_type', '=', 2); // Condition for Exempt (option 2)
                          }
                    })
					
					;
			});
		}
			
	    /*  #######  Set Order By  ###### */
		if(isset($params['order'][0]['column']))
		  $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
		else
		  $sql->orderBy('bbr.id','ASC');

		/*  #######  Get count without limit  ###### */
		$data_cnt=$sql->count();
		/*  #######  Set Offset & Limit  ###### */
		$sql->offset((int)$params['start'])->limit((int)$params['length']);
		$data=$sql->get();
		return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

	public function getBusinessPSIC($id){
        return DB::table('bplo_business_retirement_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description')->where('psic.busn_id',(int)$id)->get()->toArray();
    }
	
    public function getListexport($request){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
		$q=$request->input('q');
		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		$sql = DB::table('bplo_business_retirement AS bbr')
			  ->join('bplo_business_retirement_issuance AS bbri', 'bbri.retire_id', '=', 'bbr.id')
			  ->leftjoin('bplo_business as bb','bb.id','=','bbr.busn_id')
			  ->leftjoin('clients AS c', 'c.id', '=', 'bb.client_id')
			  ->select('bbr.*','bb.busn_name','bb.busns_id_no','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision','bb.busn_office_main_barangay_id');  
		if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bbr.retire_date_closed','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bbr.retire_date_closed','<=',$to_date);
        }
	   
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_building_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_building_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_add_block_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_reason_remarks)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_closed)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_start)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
                          if ($q === 'per line of business' || $q === 'Per Line of Business') {
                              $sql->where('bbr.retire_application_type', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'entire business' || $q === 'Entire Business') {
                              $sql->where('bbr.retire_application_type', '=', 2); // Condition for Exempt (option 2)
                          }
                    })
					
					;
			});
		}

		/*  #######  Get count without limit  ###### */
		$data_cnt=$sql->count();
		$data=$sql->get();
		return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
}
