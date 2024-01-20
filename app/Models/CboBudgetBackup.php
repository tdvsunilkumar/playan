<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class CboBudgetBackup extends Model
{
      public function addData($postdata){
        // $budgetQuarter = collect($postdata['bud_budget_quarter']);
        // $budgetQuarterTotal = $budgetQuarter->sum();
        // //dd($budgetQuarterTotal);
        // $budgetTotal = collect($postdata['bud_budget_total']);
        // $budgetAllTotal = $budgetTotal->sum();
        // $budgetAnnual  = collect($postdata['bud_budget_annual']);
        // $budgetAnnualTotal = $budgetAnnual->sum();
        // $postdata['bud_budget_quarter'] = $budgetQuarterTotal;
        // $postdata['bud_budget_annual'] = $budgetAnnualTotal;
        // $postdata['bud_budget_total'] = $budgetAllTotal;
        // $postdata['budget_status'] = 0;

        // unset($postdata['agl_id']);
           DB::table('cbo_budgets')->insert($postdata);
         
           return DB::getPdo()->lastInsertId();
      
      }
      public function updateData($id,$columns){
        return DB::table('cbo_budgets')->where('id',$id)->update($columns);
    }
      public function getAgl(){
        return DB::table('acctg_account_general_ledgers')->select('id','code','description')->get();
      }
      public function getDept(){
          return DB::table('acctg_departments')->select('id','code','name')->where('is_active',1)->get();
      }
      public function getDDiv(){
          return DB::table('acctg_departments_divisions')->select('id','code','name')->where('is_active',1)->get();
      }
      public function getdataagl($id){
        return DB::table('acctg_account_general_ledgers')
                  ->select('id','code','description')->where('id',(int)$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('cbo_budgets')->where('id',$id)->update($columns);
    } 
      public function getFund(){
          return DB::table('acctg_fund_codes')->select('id','code','description')->where('is_active',1)->get();
      }
      public function getdivclass($id){
        return DB::table('acctg_departments_divisions')->select('id','code','name')->where('is_active',1)->where('acctg_department_id','=',$id)->get();
    } 
    public function updateApprove($id,$columns){
        return DB::table('cbo_budgets')->where('id',$id)->update($columns);
      } 
      public function updateSubmitDraft($id,$columns){
        return DB::table('cbo_budgets')->where('id',$id)->update($columns);
      } 
      public function updateUnlock($id,$columns){
        return DB::table('cbo_budgets')->where('id',$id)->update($columns);
      } 
      
    //   public function getEmp(){
    //     return DB::table('hr_employees')->select('id','firstname','lastname')->where('is_active',1)->get();
    // }
    
    public function getAssRelation($id){
        return DB::table('cbo_budget_breakdowns')->where('bud_id',$id)->get()->toArray();
    }
    
    

    public function getHrEmployeeCode(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix')->get();
    }
    public function getEmployeeDetails($id){
        return DB::table('hr_employees AS hre')
              ->join('hr_designations AS hrd', 'hrd.id', '=', 'hre.hr_designation_id')
              ->select('hre.id','hrd.code','hrd.description')->where('hre.id',(int)$id)->first();
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
          $sql = DB::table('cbo_budgets AS cb')
                ->join('acctg_departments AS ad', 'ad.id', '=', 'cb.dept_id')
                ->join('acctg_departments_divisions AS add', 'add.id', '=', 'cb.ddiv_id')
                ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'cb.fc_code')
                // ->leftjoin('acctg_account_general_ledgers AS aagl', 'aagl.id', '=', 'cb.agl_id')
                ->select('cb.id','ad.code as dept_code','ad.name as dept_name','add.code','add.name','afc.code as fc_code','cb.bud_year','cb.bud_budget_quarter',
                'cb.bud_budget_annual','cb.bud_budget_total','cb.bud_is_locked','cb.budget_status','cb.bud_is_approve','cb.is_active');
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
          $sql->orderBy('cb.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function allBudgetYear()
    {
        $budget_years = self::where(['is_active' => 1])->distinct()->get(['bud_year']);

        $buds = array();
        $buds[] = array('' => 'select a budget year');
        foreach ($budget_years as $budget_year) {
            $buds[] = array(
                $budget_year->bud_year => $budget_year->bud_year
            );
        }

        $budget_years = array();
        foreach($buds as $bud) {
            foreach($bud as $key => $val) {
                $budget_years[$key] = $val;
            }
        }

        return $budget_years;
    } 
}
