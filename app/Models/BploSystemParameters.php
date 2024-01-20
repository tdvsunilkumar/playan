<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use DB;
class BploSystemParameters extends Model
{
    public function updateData($id,$columns){
        return DB::table('bplo_system_parameters')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bplo_system_parameters')->insert($postdata);
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
          0 =>"bsp_local_code",  
          1 =>"bsp_local_name",
          2 =>"bsp_address",
          3 =>"bsp_telephone_no",
          4 =>"bsp_fax_no",
          5 =>"bsp_governor_mayor",
          6 =>"bsp_administrator_name"
          
           
        );
         $sql = DB::table('bplo_system_parameters AS bgf')->select('id','bsp_local_code','bsp_local_name','bsp_address','bsp_telephone_no','bsp_fax_no','bsp_governor_mayor','bsp_administrator_name');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bsp_local_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bsp_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bsp_governor_mayor)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bsp_administrator_name)'),'like',"%".strtolower($q)."%");
                    
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
