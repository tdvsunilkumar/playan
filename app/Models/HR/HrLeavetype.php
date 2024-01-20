<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrLeavetype extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_leavetypes')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_leavetypes')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_leavetypes')->where('id',$id)->update($columns);
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
      1 =>"hrlt_leave_code",
      2 =>"hrlt_leave_type",
      3 =>"is_active",
    );

    $sql = DB::table('hr_leavetypes')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrlt_leave_code)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(hrlt_leave_type)'),'like',"%".strtolower($q)."%");
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
