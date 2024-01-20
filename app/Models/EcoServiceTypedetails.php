<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EcoServiceTypedetails extends Model
{
	protected $table = 'eco_receptions_lists_details'; 
    public function updateActiveInactive($id,$columns){
     return DB::table('eco_receptions_lists_details')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_receptions_lists_details')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_receptions_lists_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_receptions_lists_details')->where('est_id',$id)->first();
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
          1 =>"est_id",
          2 =>"eat_additional_info",
		  3 =>"eatd_discount",
		  4 =>"eatd_process_type",
		  5 =>"eatd_amount_type",
		  6 =>"eatd_status",
           
        );

        $sql = DB::table('eco_receptions_lists_details')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(eat_additional_info)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
