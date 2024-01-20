<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class Country extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('countries')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('countries')->where('id',$id)->update($columns);
    }
    public function updateDefaultall($data){
      return DB::table('countries')->update($data);
    }
    public function addData($postdata){
        return DB::table('countries')->insert($postdata);
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
          1 =>"country_name",  
          2 =>"nationality",  
          3 =>"is_active",
          4 =>"is_default"
         );
        
         $sql = DB::table('countries AS bgf')->select('id','country_name','nationality','is_active','is_default');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(country_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(nationality)'),'like',"%".strtolower($q)."%");
                   
                    
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
