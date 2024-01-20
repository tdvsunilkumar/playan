<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegCauseOfDeath extends Model
{
      public $table = 'reg_cause_of_deaths';
  public function updateActiveInactive($id,$columns){
    return DB::table('reg_cause_of_deaths')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('reg_cause_of_deaths')->where('id',$id)->update($columns);
    }
    public function updateDefaultall($data){
      return DB::table('reg_cause_of_deaths')->update($data);
    }
    public function addData($postdata){
        return DB::table('reg_cause_of_deaths')->insert($postdata);
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
          1 =>"cause_of_death",  
          2 =>"remarks",  
          3 =>"status"
         );
        
         $sql = DB::table('reg_cause_of_deaths')->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cause_of_deaths)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(remarks)'),'like',"%".strtolower($q)."%");
                   
                    
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

