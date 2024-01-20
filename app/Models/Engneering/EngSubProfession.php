<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngSubProfession extends Model
{
     public function updateActiveInactive($id,$columns){
     return DB::table('eng_sub_professions')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eng_sub_professions')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('eng_sub_professions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
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
          1 =>"esp_sub_type",
          2 =>"esp_is_active"
           
        );

        $sql = DB::table('eng_sub_professions')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(esp_sub_type)'),'like',"%".strtolower($q)."%")
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
	  
	 public function allAppType(){
	
      $wsat_type = DB::table('eng_sub_professions')
	  ->orderBy('id')
	  ->select('id','esp_sub_type')->get();
      return $wsat_type;
	}
}
