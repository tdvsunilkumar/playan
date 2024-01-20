<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoIcd10Code extends Model
{

    public function updateData($id,$columns){
        return DB::table('ho_icd10_codes')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ho_icd10_codes')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_icd10_codes')->where('id',$id)->update($columns);
    }
    public function getIcdGroupId(){
      return DB::table('ho_icd10_groups')->select('id','icd10_group_name')->where('icd_is_active',1)->get();
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
                1 =>"icd10_code",
                2 =>"icd10_group_id",
                3 =>"icd10_case_rate",
                4 => "icd10_pro_fee",
                5 => "icd10_institution_fee",
                6 => "icd_is_active"
              );
              $sql = DB::table('ho_icd10_codes AS icdc')
                    ->join('ho_icd10_groups AS icdg', 'icdg.id', '=', 'icdc.icd10_group_id')
                    ->select('icdc.*','icdg.icd10_group_name');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(icdc.icd10_code)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(icdc.icd10_group_id)'),'like',"%".strtolower($q)."%");
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('icdc.id','ASC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
      
}
