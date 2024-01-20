<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoHematologyRange extends Model
{

    public function updateData($id,$columns){
        return DB::table('ho_hematology_ranges')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ho_hematology_ranges')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_hematology_ranges')->where('id',$id)->update($columns);
    }
    public function getchcId(){
      return DB::table('ho_hematology_categories')->select('id','chg_category')->where('cc_is_active',1)->get();
    }
    public function getchpId(){
      return DB::table('ho_hematology_parameters')->select('id','chp_parameter')->where('hp_is_active',1)->get();
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
                0 =>"", 
                1 =>"chp_id",
                2 =>"chc_id",
                3 =>"chr_range",
                4 =>"hr_is_active"
              );
              $sql = DB::table('ho_hematology_ranges AS hr')
                    ->join('ho_hematology_categories AS hc', 'hc.id', '=', 'hr.chc_id')
                    ->join('ho_hematology_parameters AS hp', 'hp.id', '=', 'hr.chp_id')
                    ->select('hr.id','hc.chg_category','hp.chp_parameter','hr.chr_range','hr.hr_is_active');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(hr.chp_id)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(rpt.chc_id)'),'like',"%".strtolower($q)."%");
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('hr.id','ASC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
      
}
