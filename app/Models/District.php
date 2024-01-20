<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class District extends Model
{
    public function updateData($id,$columns){
        return DB::table('rpt_district')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('rpt_district')->insert($postdata);
	}
    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_district')->where('id',$id)->update($columns);
    }
    public function getDistrict(){
        // return DB::table('barangays')->select('*')->get();
       return DB::table('rpt_district AS dist')
              ->join('rpt_locality AS loc', 'loc.loc_local_code', '=', 'dist.loc_local_code')
              ->select('dist.id','dist.loc_local_code','dist.dist_code','dist.dist_name','dist.is_active','loc.loc_local_name')->get();
    }
    public function editDistrict($id){
        return DB::table('rpt_district')->where('id',$id)->first();
     }

    public function getMunId(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('is_active',1)->where('mun_display_for_rpt',1)->get();
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
          0 =>"mun_no",  
          1 =>"mun_desc",  
          2 =>"dist_code",
          3 =>"dist_name",
          4 =>"is_active"
           
        );
        $sql = DB::table('rpt_district AS dist')
        ->join('profile_municipalities AS loc', 'loc.id', '=', 'dist.loc_local_code')
        ->select('dist.id','dist.loc_local_code','dist.dist_code','dist.dist_name','dist.is_active','loc.mun_no','loc.mun_desc');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(loc.mun_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(loc.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(dist.dist_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(dist.dist_name)'),'like',"%".strtolower($q)."%");
                  
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('dist.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

}
