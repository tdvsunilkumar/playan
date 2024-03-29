<?php

namespace App\Models\SocialWelfare;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TypeDisability extends Model
{
    public $table = 'welfare_pwd_type_of_disability';
    
    public function updateActiveInactive($id,$columns){
     return DB::table('welfare_pwd_type_of_disability')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('welfare_pwd_type_of_disability')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('welfare_pwd_type_of_disability')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('welfare_pwd_type_of_disability')->where('id',$id)->first();
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
          1 =>"wptod_description",
          2 =>"wptod_is_active"
           
        );

        $sql = DB::table('welfare_pwd_type_of_disability')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(wptod_description)'),'like',"%".strtolower($q)."%")
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
