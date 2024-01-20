<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngBldgPermitApp extends Model
{
    public $table = 'eng_bldg_permit_apps';
    public function bldgOccupancyType()
    {
      return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebs_id');
    }
    public function getOccupancyTypeAttribute()
    {
      return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description:'';
    }

    // use HasFactory;
    public function getMuncipalities(){
    	return DB::table('profile_municipalities')->select('id','mun_desc')->where('is_active',1)->get();
    }
    
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
    public function getBuildAppType(){
        return DB::table('eng_bldg_aptypes')->select('id','eba_description')->where('eba_is_active',1)->get();
    }
    public function updateData($id,$columns){
        return DB::table('eng_bldg_permit_apps')->where('id',$id)->update($columns);
    }
    public function getBuildScopes(){
        return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->get();
    }
    public function getOccupancytype(){
        return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->get();
    }
     public function getOccupancySubtype(){
        return DB::table('eng_bldg_occupancy_sub_types')->select('id','ebost_description')->where('ebost_is_active',1)->get();
    }
    public function addData($postdata){
        DB::table('eng_bldg_permit_apps')->insert($postdata);
        return DB::getPdo()->lastInsertId();
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
      0 =>"mun_desc",
      1 =>"ebpa_application_no",
      2 =>"ebpa_permit_no",
      3 =>"ebpa_application_date",
      4 =>"ebpa_owner_last_name",	
      5 =>"ebpa_issued_date"
    );

    $sql = DB::table('eng_bldg_permit_apps AS psc')
          ->join('profile_municipalities AS mun', 'mun.id', '=', 'psc.ebpa_mun_no')
          ->join('barangays AS b', 'psc.brgy_code', '=', 'b.id')
          ->select('psc.id','mun_desc','brgy_name','ebpa_application_no','ebpa_permit_no','ebpa_application_date','ebpa_issued_date','ebpa_owner_last_name','ebpa_owner_first_name','ebpa_owner_mid_name','ebpa_owner_suffix_name','ebpa_address_house_lot_no','ebpa_address_street_name','ebpa_address_subdivision');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ebpa_application_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ebpa_owner_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ebpa_owner_mid_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ebpa_owner_last_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(ebpa_address_street_name)'),'like',"%".strtolower($q)."%");
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
