<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptBuildingRoofing extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_building_roofings')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('rpt_building_roofings')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('rpt_building_roofings')->insert($postdata);
	}
    public function getBuildingRoofing(){
       return DB::table('rpt_building_roofings')->select('*')->get();
    }
    public function editBuildingRoofing($id){
        return DB::table('rpt_building_roofings')->where('id',$id)->first();
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
          1 =>"rbr_building_roof_desc",
          2 =>"is_active"
           
        );

        $sql = DB::table('rpt_building_roofings')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(rbr_building_roof_desc)'),'like',"%".strtolower($q)."%")
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
