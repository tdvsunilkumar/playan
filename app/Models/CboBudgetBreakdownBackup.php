<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CboBudgetBreakdownBackup extends Model
{
          
      // public function updateData($id,$columns){
      //   return DB::table('cbo_budget_breakdowns')->where('id',$id)->update($columns);
      // }
     
      
      public function checkAssRequietExit($id){
        return DB::table('cbo_budget_breakdowns')->select('id')->where('id',(int)$id)->get()->toArray();
    }
    public function UpdateAssRequietExit($id){
        return DB::table('cbo_budget_breakdowns')->select('*')->where('bud_id',$id)->get()->toArray();
    }
    public function addAssRelationData($postdata){
        return DB::table('cbo_budget_breakdowns')->insert($postdata);
       // return DB::getPdo()->lastInsertId();
   }
   public function updateAssRelationData($id,$columns){
    return DB::table('cbo_budget_breakdowns')->where('id',$id)->update($columns);
}
      
      public function getAssRequiet($id){
        return DB::table('cbo_budget_breakdowns AS cbb')
        ->select('*','cbb.id AS relationId')->where('bud_id',$id)->get()->toArray();
        // return DB::table('bplo_requirement_relations AS bgf')->select('*')->where('bplo_requirement_id',$id)->get();
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
          2 =>"dept_id", 
          3 =>"ddiv_id", 
          4 =>"fc_code",  
          5 =>"bud_year", 
          6 =>"bud_budget_quarter",
          7 =>"bud_budget_annual",
          8 =>"bud_budget_total",
          9 =>"bud_is_locked",
          10 =>"bud_approved_by",
          11 =>"bud_approved_date",
          12 =>"bud_disapproved_by",
          13 =>"bud_disapproved_date",
          // 8 =>"buv_is_active"
         );
         $sql = DB::table('cbo_budget_breakdowns AS cbb')
               ->leftjoin('acctg_account_general_ledgers AS aagl', 'aagl.id', '=', 'cbb.agl_id')
               ->select('cbb.id','aagl.code as agl_code','aagl.description as agl_description','cbb.bud_year','cbb.bud_budget_quarter',
               'cbb.bud_budget_annual','cbb.bud_budget_total','cbb.bud_is_locked','cbb.budget_status','cbb.is_active');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ad.dept_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(add.ddiv_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(afc.fc_code)'),'like',"%".strtolower($q)."%");
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cbb.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
