<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class EngBldgOccupancySubType extends Model
{
  public $table = 'eng_bldg_occupancy_sub_types';
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_bldg_occupancy_sub_types')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eng_bldg_occupancy_sub_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('eng_bldg_occupancy_sub_types')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getRptClass(){
        return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->get();
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
          1 =>"class.ebot_description", 
          2 =>"sub.ebost_description", 
          3 =>"sub.ebost_is_active",
          
         );
         $sql = DB::table('eng_bldg_occupancy_sub_types AS sub')
               ->join('eng_bldg_occupancy_types AS class', 'class.id', '=', 'sub.ebost_id')
               ->select('sub.id','class.ebot_description','sub.ebost_description','sub.ebost_is_active');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(class.ebot_description)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(sub.ebost_description)'),'like',"%".strtolower($q)."%");
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('sub.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}


