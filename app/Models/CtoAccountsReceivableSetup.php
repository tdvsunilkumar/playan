<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoAccountsReceivableSetup extends Model
{
    use HasFactory;

    public function updateData($id,$columns){
        return DB::table('cto_accounts_receivable_setups')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('cto_accounts_receivable_setups')->insert($postdata);
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $pk_id=$request->input('pk_id');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $category = "CASE WHEN acrs.ars_category = 1 THEN 'Basic Tax' WHEN acrs.ars_category = 2 THEN 'Special Education Tax' ELSE 'Socialize Housing Tax' END";
        $glSlDesc = "CONCAT('[',aagl.code,'-',aagl.description,']','=>','[',aasl.prefix,'-',aasl.description,']')";
        $columns = array( 
          0 => "id", 
          1 => "pk.pk_description",
          2 => DB::raw($category),  
          3 => "fc.description",
          4 => DB::raw($glSlDesc),
          5 => "acrs.ars_remarks",
          6 => "emp.fullname",
          7 => "acrs.updated_at"
         );
         $sql = DB::table('cto_accounts_receivable_setups AS acrs')
                      ->join('rpt_property_kinds as pk','pk.id','=','acrs.pk_id')
                      ->join('acctg_fund_codes as fc','fc.id','=','acrs.ars_fund_id')
                      ->join('acctg_account_subsidiary_ledgers  AS aasl','aasl.id','=','acrs.sl_id')
                      ->join('acctg_account_general_ledgers as aagl','aagl.id','=','acrs.gl_id')
                      ->join('hr_employees as emp','emp.user_id','=','acrs.updated_by')
                      ->select('pk.pk_description',DB::raw($category.' as ars_category'),'fc.description as fund_id','aagl.code as gl_code','aagl.description as gl_description','aasl.prefix as sl_prefix','aasl.description as sl_description',DB::raw($glSlDesc.' as gl_sl_id'),'acrs.ars_remarks','emp.fullname','acrs.updated_at','acrs.id');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q,$category,$glSlDesc) {
                $sql->where(DB::raw('LOWER(pk.pk_description)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw($category),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(fc.description)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw($glSlDesc),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(fc.description)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(aagl.code)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(aagl.description)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(aasl.prefix)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(aasl.description)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(acrs.ars_remarks)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(emp.fullname)'),'like',"%".strtolower($q)."%");
            });
        }

        if(!empty($pk_id) && isset($pk_id)){
            $sql->where(function ($sql) use($pk_id) {
                $sql->where('acrs.pk_id',$pk_id);
            });
        }
      
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else

          $sql->orderBy('acrs.id','ASC');

          $sql->orderBy('acrs.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

    public function getAccountGeneralLeader(){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')
        ->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')
        ->select('aasl.id as sl_id','aagl.id as gl_id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')
        ->where('aagl.is_active',1)
        ->where('aasl.is_parent',0)
        ->where('aasl.is_hidden',0)
        ->where('aasl.is_active',1)
        ->get();
    }

    public function getFundCodes(){
        return DB::table('acctg_fund_codes')->select('id','code','description')->where('is_active',1)->get();
    }
}
