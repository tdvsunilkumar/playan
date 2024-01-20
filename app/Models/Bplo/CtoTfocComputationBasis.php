<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoTfocComputationBasis extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_tfoc_computation_bases')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_tfoc_computation_bases')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_tfoc_computation_bases')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_tfoc_computation_bases')->where('id',$id)->first();
    }
    public function getTFOCDtls(){
        return DB::table('cto_tfocs AS ct')
            ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
            ->select('ct.id','sl.description')->where('tfoc_status',1)->orderBy('sl.description', 'ASC')->get()->toArray();
    }
    public function getTFOCBasis(){
        return DB::table('cto_tfoc_basis')->select('id','basis_name')->where('basis_ref_table','!=',"''")->where('basis_ref_field','!=',"''")->where('basis_status',1)->where('basis_status',1)->orderBy('basis_name', 'ASC')->get()->toArray();
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
          1 =>"description",
          2 =>"basis_name",
          3 =>"tcb_is_active"
        );

        $sql = DB::table('cto_tfoc_computation_bases AS cb')
            ->join('cto_tfocs AS ct', 'cb.tfoc_id', '=', 'ct.id')
            ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
             // ->leftJoin('cto_tfoc_basis AS ctb', 'ctb.id', '=', 'cb.basis_ids')
            //  ->select('cb.id','sl.description','cb.tcb_is_active','ctb.basis_name');

            ->leftJoin('cto_tfoc_basis AS ctb', function($join){
                $join->on(DB::raw("FIND_IN_SET(ctb.id, cb.basis_ids)"),">",DB::raw("'0'"));
            })
            ->select('cb.id','sl.description','cb.tcb_is_active',DB::raw("GROUP_CONCAT(ctb.basis_name SEPARATOR ', ') as basis_name"))->groupBy('ctb.id');

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(sl.description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctb.basis_name)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cb.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
