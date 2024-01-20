<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class RptCtoPenaltySchedule extends Model
{

    public function updateData($id,$columns){
        return DB::table('rpt_cto_penalty_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){

        return DB::table('rpt_cto_penalty_schedules')->insert($postdata);
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
          0 =>"cps_prevailing_law",
          1 =>"cps_from_year",  
          2 =>"cps_to_year",
          3 =>"cps_penalty_rate",
          4 =>"cps_penalty_limitation",
          5 =>"cps_maximum_penalty"
         );
         $sql = DB::table('rpt_cto_penalty_schedules AS bgf')->select('*');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cps_from_year)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(cps_to_year)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(cps_penalty_rate)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(cps_maximum_penalty)'),'like',"%".strtolower($q)."%");
                    
                   
                    
            });
        }

        
      
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else

          $sql->orderBy('bgf.id','ASC');

          $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}

