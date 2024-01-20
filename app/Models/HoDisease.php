<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoDisease extends Model
{
        public function updateData($id,$columns){
            return DB::table('ho_diseases')->where('id',$id)->update($columns);
        }
        public function addData($postdata){
            return DB::table('ho_diseases')->insert($postdata);

        }
        public function updateActiveInactive($id,$columns){
          return DB::table('ho_diseases')->where('id',$id)->update($columns);
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
                    1 =>"disease_name",
                    2 =>"hd_is_active"
                  );
                  $sql = DB::table('ho_diseases AS hd')
                        ->select('hd.id','hd.disease_name','hd.hd_is_active');
                  if(!empty($q) && isset($q)){
                      $sql->where(function ($sql) use($q) {
                          $sql->where(DB::raw('LOWER(hd.disease_name)'),'like',"%".strtolower($q)."%")
                              ->orWhere(DB::raw('LOWER(hd.hd_is_active)'),'like',"%".strtolower($q)."%");
                            
                    });
                  }
    
                  /*  #######  Set Order By  ###### */
                  if(isset($params['order'][0]['column']))
                    $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                  else
                    $sql->orderBy('hd.id','ASC');
    
                  /*  #######  Get count without limit  ###### */
                  $data_cnt=$sql->count();
                  /*  #######  Set Offset & Limit  ###### */
                  $sql->offset((int)$params['start'])->limit((int)$params['length']);
                  $data=$sql->get();
                  return array("data_cnt"=>$data_cnt,"data"=>$data);
                }
          
    }
    

