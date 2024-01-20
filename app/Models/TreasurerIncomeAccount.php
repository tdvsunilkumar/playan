<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TreasurerIncomeAccount extends Model
{
    public function updateData($id,$columns){
        return DB::table('treasurer_income_accounts')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('treasurer_income_accounts')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getAccGenLedCode(){
        return DB::table('acctg_account_general_ledgers')->select('id','code','description')->get();
    }
    public function getFunCode(){
        return DB::table('acctg_fund_codes')->select('id','code','description')->get();
    }
    public function getDepCode(){
        return DB::table('acctg_departments')->select('id','code','name')->get();
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
          0 => "id",	
          1 =>"code",  
          2 =>"tia_account_code",
          3 =>"tia_account_description",
          4 =>"tia_tax_cy",
          5 =>"tia_tax_py",
          6 =>"tia_discount_code",
          7 =>"tia_penalty_cy",
          8 =>"tia_penalty_py",
          9 =>"tia_tax_credit",

        );
        
         // $sql = DB::table('treasurer_income_accounts AS tia')
         //      ->join('acctg_departments AS ac', 'ac.id', '=', 'tia.agl_code')
         //      ->join('acctg_fund_codes AS af', 'af.id', '=', 'tia.fund_code')
         //      ->join('acctg_account_general_ledgers AS agl', 'agl.id', '=', 'tia.loc_budget_officer_id')
         //      ->select('id','tia_fund_code','tia_account_code','tia_account_description','tia_initial_amount');

        $sql = DB::table('treasurer_income_accounts AS tia')
              ->join('acctg_fund_codes AS af', 'af.id', '=', 'tia.fund_code')
              ->select('tia.id','af.code','tia_account_code','tia_account_description','tia_initial_amount','tia_tax_cy','tia_tax_py','tia_discount_code','tia_penalty_cy','tia_penalty_py','tia_tax_credit');

        //$sql->where('created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tia_account_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_account_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(af.code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_tax_cy)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_tax_py)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_discount_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_penalty_cy)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_penalty_py)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tia_tax_credit)'),'like',"%".strtolower($q)."%");
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('tia.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
