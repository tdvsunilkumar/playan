<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ScheduleDescription extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('schedule_descriptions')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('schedule_descriptions')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('schedule_descriptions')->insert($postdata);
	}
    public function getScheduleDescription(){
       return DB::table('schedule_descriptions')->select('*')->get();
    }

    
    public function editScheduleDescription($id){
        return DB::table('schedule_descriptions')->where('id',$id)->first();
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
          1 =>"sd_mode",
          2 =>"sd_description",
          3 =>"sd_description_short",
          4 =>"is_active"
        );
        

        $sql = DB::table('schedule_descriptions')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                    $sql->orWhere(DB::raw('LOWER(schedule_descriptions.sd_mode)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(schedule_descriptions.sd_description)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(schedule_descriptions.sd_description_short)'),'like',"%".strtolower($q)."%");
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
