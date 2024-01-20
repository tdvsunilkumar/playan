<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngPermitFeesSet1 extends Model
{	
	  public $table = 'eng_building_permit_fees_set1';
    public function updateData($id,$columns){
        return DB::table('eng_building_permit_fees_set1')->where('ebpfs1_id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_building_permit_fees_set1')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_building_permit_fees_set1')->where('ebpfs1_id',$id)->update($columns);
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
      0 =>"ebpfs1_id",
      1 =>"ebpfs1_range_from",
      2 =>"ebpfs1_range_to",
      3 =>"ebpfs1_fees",
	  4 =>"eef_status",
    );

    $sql = DB::table('eng_building_permit_fees_set1')
          ->select('*');
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ebpfs1_range_from)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfs1_range_to)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfs1_fees)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(eef_status)'),'like',"%".strtolower($q)."%")
				;
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('ebpfs1_id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
