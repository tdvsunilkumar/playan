<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class BploAssessTaxRateEffectivit extends Model
{
    public function updateActiveInactive($id,$columns){
      return DB::table('bplo_assess_tax_rate_effectivits')->where('id',$id)->update($columns);
    }  
     public function updateData($id,$columns){
        return DB::table('bplo_assess_tax_rate_effectivits')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bplo_assess_tax_rate_effectivits')->insert($postdata);
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
          1 =>"tre_effectivity_year",
          2 =>"tre_quarter",
          3 =>"tre_ordinance_number",
          4 =>"is_active",
          5 =>"tre_remarks"
         );
         $sql = DB::table('bplo_assess_tax_rate_effectivits AS bgf')->select('id','tre_code','tre_effectivity_year','tre_quarter','tre_ordinance_number','is_active','tre_remarks');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tre_effectivity_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(id)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tre_quarter)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tre_ordinance_number)'),'like',"%".strtolower($q)."%");
                   
                    
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
