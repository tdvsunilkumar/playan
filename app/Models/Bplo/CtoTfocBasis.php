<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoTfocBasis extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_tfoc_basis')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_tfoc_basis')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_tfoc_basis')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_tfoc_basis')->where('id',$id)->first();
    }




    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          0 =>"id",
          1 =>"basis_name",
          2 =>"basis_ref_table",
          3 =>"basis_ref_field",
          4 =>"basis_is_retire",
          5 =>"basis_status",   
        );
        $sql = DB::table('cto_tfoc_basis')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(basis_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(basis_ref_table)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(basis_ref_field)'),'like',"%".strtolower($q)."%"); ; 
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
