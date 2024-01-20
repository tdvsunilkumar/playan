<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class CpdoPenalties extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cpdo_penalties')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cpdo_penalties')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cpdo_penalties')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getHRemployees(){
        return DB::table('hr_employees')
        ->select('user_id','fullname')
        ->get();
    }
    public function getEditDetails($id){
        return DB::table('cpdo_penalties')->where('id',$id)->first();
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
          1 =>"cpen.code",
          2 =>"cpen.name",
		  3 =>"cpen.description",
		  4 =>"cpen.percentage",
		  5 =>"hre.fullname",
		  6 =>"cpen.is_active"
           
        );

        $sql = DB::table('cpdo_penalties As cpen')
			   ->join('hr_employees as hre','hre.user_id','=','cpen.created_by')
              ->select('cpen.*',);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cpen.code)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cpen.name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cpen.description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cpen.percentage)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(hre.fullname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cpen.is_active)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cpen.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
