<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngEquipmentSystemType extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('eng_equipment_system_type')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eng_equipment_system_type')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('eng_equipment_system_type')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
	public function getEditDetails($id){
        return DB::table('eng_equipment_system_type')->where('id',$id)->first();
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
          1 =>"eest_description",
          2 =>"eest_is_active"
           
        );

        $sql = DB::table('eng_equipment_system_type')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(eest_description)'),'like',"%".strtolower($q)."%")
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
