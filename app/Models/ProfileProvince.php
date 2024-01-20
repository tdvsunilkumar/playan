<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProfileProvince extends Model
{

  public $table = 'profile_provinces';
 
  public function updateActiveInactive($id,$columns){
    return DB::table('profile_provinces')->where('id',$id)->update($columns);
  }  
     public function updateData($id,$columns){
        return DB::table('profile_provinces')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('profile_provinces')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function ProfileProvinceData($id){
        return DB::table('profile_regions')->select('id','reg_region','reg_description')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getProfileProvince(){
         return DB::table('profile_regions')->select('id','reg_region','reg_description')->where('is_active',1)->get();
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
          1 =>"reg_region",
          2 =>"prov_no",
          3 =>"prov_desc",
          4 =>"is_active"
          
          
         );
         $sql = DB::table('profile_provinces AS bgf')
         ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
         ->select('bgf.id','prov_code','pr.reg_region','pr.reg_description','prov_no','prov_desc','bgf.is_active');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(prov_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(prov_no)'),'like',"%".strtolower($q)."%");
                   
                    
            });
        }


        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
