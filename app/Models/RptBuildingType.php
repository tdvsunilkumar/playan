<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class RptBuildingType extends Model
{

    protected $table = 'rpt_building_types';

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_building_types')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('rpt_building_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){

        return DB::table('rpt_building_types')->insert($postdata);
    }
    public function getBuildingType(){
       return DB::table('rpt_building_types')->select('*')->get();
    }
    public function editBuildingType($id){
        return DB::table('rpt_building_types')->where('id',$id)->first();
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
          1 =>"bt_building_type_code",
          2 =>"bt_building_type_desc",  
          3 =>"bt_is_active",
          // 4 =>"active|inactive"

         );
         $sql = DB::table('rpt_building_types AS bgf')->select('*');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bt_building_type_desc)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(bt_building_type_code)'),'like',"%".strtolower($q)."%");
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
