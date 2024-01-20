<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngPermitFeesSet2 extends Model
{
    public $table = 'eng_building_permit_fees_set2';
    public function updateData($id,$columns){
        return DB::table('eng_building_permit_fees_set2')->where('ebpfs2_id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_building_permit_fees_set2')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_building_permit_fees_set2')->where('ebpfs2_id',$id)->update($columns);
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
      0 =>"ebpfs2_id",
      1 =>"ebpfs2_range_from",
      2 =>"ebpfs2_range_to",
      3 =>"ebpfs2_fees",
	  4 =>"ebpfs2_status",
    );

    $sql = DB::table('eng_building_permit_fees_set2')
          ->select('*');
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ebpfs2_range_from)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfs2_range_to)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfs2_fees)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfs2_status)'),'like',"%".strtolower($q)."%")
				;
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('ebpfs2_id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
