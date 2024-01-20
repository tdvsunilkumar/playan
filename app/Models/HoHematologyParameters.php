<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoHematologyParameters extends Model
{
        public function updateData($id,$columns){
            return DB::table('ho_hematology_parameters')->where('id',$id)->update($columns);
        }
        public function addData($postdata){
            return DB::table('ho_hematology_parameters')->insert($postdata);

        }
        public function updateActiveInactive($id,$columns){
          return DB::table('ho_hematology_parameters')->where('id',$id)->update($columns);
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
                    1 =>"chp_parameter",
                    2 =>"hp_is_active"
                  );
                  $sql = DB::table('ho_hematology_parameters AS hp')
                        ->select('hp.id','hp.chp_parameter','hp.hp_is_active');
                  if(!empty($q) && isset($q)){
                      $sql->where(function ($sql) use($q) {
                          $sql->where(DB::raw('LOWER(hp.chp_parameter)'),'like',"%".strtolower($q)."%")
                              ->orWhere(DB::raw('LOWER(hp.hp_is_active)'),'like',"%".strtolower($q)."%");
                            
                    });
                  }
    
                  /*  #######  Set Order By  ###### */
                  if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                  else
                    $sql->orderBy('hp.id','ASC');
    
                  /*  #######  Get count without limit  ###### */
                  $data_cnt=$sql->count();
                  /*  #######  Set Offset & Limit  ###### */
                  $sql->offset((int)$params['start'])->limit((int)$params['length']);
                  $data=$sql->get();
                  return array("data_cnt"=>$data_cnt,"data"=>$data);
                }
          
    }
    

