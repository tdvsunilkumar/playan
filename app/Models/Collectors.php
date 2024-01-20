<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class Collectors extends Model
{
    public function updateActiveInactive($id,$columns){
      return DB::table('collectors')->where('id',$id)->update($columns);
    }  
     public function updateData($id,$columns){
        return DB::table('collectors')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('collectors')->insert($postdata);
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
          1 =>"col_code",  
          2 =>"col_initial",
          3 =>"col_initial2",
          4 =>"col_name",
          5 =>"col_desc",
          6 =>"col_type",
          // 6 =>"is_active"
         );
         $sql = DB::table('collectors AS bgf')->select('id','col_code','col_initial','col_initial2','col_name','col_desc','col_type','is_active');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(col_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(col_name)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(col_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(col_type)'),'like',"%".strtolower($q)."%");
                   
                    
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
