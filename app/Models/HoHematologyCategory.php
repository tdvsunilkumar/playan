<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoHematologyCategory extends Model
{

    public $table = 'ho_hematology_categories';
    public function updateData($id,$columns){
        return DB::table('ho_hematology_categories')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ho_hematology_categories')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_hematology_categories')->where('id',$id)->update($columns);
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
                1 =>"chg_category",
                2 =>"cc_is_active"
              );
              $sql = DB::table('ho_hematology_categories AS hc')
                    ->select('hc.id','hc.chg_category','hc.cc_is_active');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(hc.chg_category)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(rpt.cc_is_active)'),'like',"%".strtolower($q)."%");
                          
                        
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('hc.id','ASC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
      
}
