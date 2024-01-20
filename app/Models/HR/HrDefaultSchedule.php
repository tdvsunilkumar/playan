<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrDefaultSchedule extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_default_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_default_schedules')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_default_schedules')->where('id',$id)->update($columns);
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
      1 =>"hrds_range",
      2 =>"is_active"
    );

    $sql = DB::table('hr_default_schedules')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrla_description)'),'like',"%".strtolower($q)."%");
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
