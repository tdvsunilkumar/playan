<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class BfpOccupancyType extends Model
{
    public function updateActiveInactive($id,$columns){
      return DB::table('bfp_occupancy_types')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('bfp_occupancy_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bfp_occupancy_types')->insert($postdata);
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
          1 =>"bot_occupancy_type",  
          2 =>"is_active"
         );
         $sql = DB::table('bfp_occupancy_types AS bgf')->select('id','bot_occupancy_type','is_active');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bot_occupancy_type)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      
}
