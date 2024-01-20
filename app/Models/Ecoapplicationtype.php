<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Ecoapplicationtype extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('eco_receptions_lists')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_receptions_lists')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_receptions_lists')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_receptions_lists')->where('id',$id)->first();
    }
	public function getBarangay(){
        return DB::table('eco_data_receptions AS edr')
		  ->join('barangays AS b', 'b.id', '=', 'edr.brgy_id')
		 /* ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
         ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
         ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no') 
        ->select('b.id','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region')*/
		->select('b.id','b.brgy_name','edr.reception_name',)
        ->get();
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
          1 =>"est_service_type",
          2 =>"est_status"
           
        );

        $sql = DB::table('eco_receptions_lists')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(est_service_type)'),'like',"%".strtolower($q)."%")
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
