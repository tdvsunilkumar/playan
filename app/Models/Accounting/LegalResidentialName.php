<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LegalResidentialName extends Model
{
  public $table = 'eco_residential_name';
  
    public function updateData($id,$columns){
        return DB::table('eco_residential_name')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eco_residential_name')->insert($postdata);
    }
    public function findById($id){
        return DB::table('eco_residential_name')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eco_residential_name')->where('id',$id)->update($columns);
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
      1 =>"Brgy.brgy_name",
      2 =>"ett.type_of_transaction",
      3 =>"lrn.is_active",
    );

    $sql = DB::table('eco_residential_name as lrn')
           ->leftJoin('eco_type_of_transaction AS ett', 'ett.id', '=', 'lrn.residential_name')
           ->leftJoin('barangays AS Brgy', 'Brgy.id', '=', 'lrn.barangay_id')
           ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'Brgy.reg_no')
           ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'Brgy.prov_no')
           ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'Brgy.mun_no')
           ->select('lrn.*','Brgy.brgy_name', 'pr.reg_region', 'pp.prov_desc', 'pm.mun_desc', 'ett.type_of_transaction');
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ett.type_of_transaction)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(Brgy.brgy_name, ', ',pm.mun_desc,', ',pp.prov_desc,', ', pr.reg_region)"), 'LIKE', "%".strtolower($q)."%");
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
