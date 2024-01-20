<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ElectricalFeesLoad extends Model
{	
	public $table = 'eng_electrical_fees_load';
    public function updateData($id,$columns){
        return DB::table('eng_electrical_fees_load')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_electrical_fees_load')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_electrical_fees_load')->where('id',$id)->update($columns);
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
      1 =>"eefl_kva_range_from",
      2 =>"eefl_kva_range_to",
      3 =>"eefl_fees",
	  4 =>"eef_in_excess_fees",
	  5 =>"eef_status",
    );

    $sql = DB::table('eng_electrical_fees_load')
          ->select('*');
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(eefl_kva_range_from)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(eefl_kva_range_to)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(eefl_fees)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(eef_in_excess_fees)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(eef_status)'),'like',"%".strtolower($q)."%")
				;
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
