<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SerologyMethod extends Model
{
    protected $table = 'ho_serology_method';
    public function updateActiveInactive($id,$columns){
     return DB::table('ho_serology_method')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('ho_serology_method')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_serology_method')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
        return DB::table('ho_serology_method')->where('id',$id)->first();
    }
    public function getSerologyType(){
          return DB::table('ho_services')->where('ho_service_form',2)->select('id','ho_service_name')->get();
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
          1 =>"ser_id",
          2 =>"ser_m_method",
          3 =>"ser_m_remarks",
          4 =>"ser_is_active",
        );

        $sql = DB::table('ho_serology_method')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(df_desc)'),'like',"%".strtolower($q)."%")
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
