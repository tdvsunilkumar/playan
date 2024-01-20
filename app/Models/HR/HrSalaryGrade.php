<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrSalaryGrade extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_salary_grades')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_salary_grades')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_salary_grades')->where('id',$id)->update($columns);
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
      0 =>"es.id",
      1 =>"asl.description",
      2 =>"eba.eat_module_desc",
      3 =>"em.em_module_desc",
      4 =>"em.em_module_desc",
      5 =>"es.es_is_active",	
    );

    $sql = DB::table('hr_salary_grades')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrsg_salary_grade)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(hrsg_step_1)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(hrsg_step_2)'),'like',"%".strtolower($q)."%");
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
