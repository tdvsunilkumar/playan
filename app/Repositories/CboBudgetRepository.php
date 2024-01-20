<?php

namespace App\Repositories;

use App\Interfaces\CboBudgetInterface;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\AcctgFundCode;
use App\Models\CboBudget;
use App\Models\CboBudgetBreakdown;
use App\Models\User;
use App\Models\CboBudgetCategory;
use App\Models\CboBudgetAlignment;
use DB;

class CboBudgetRepository implements CboBudgetInterface 
{
    public function find($id) 
    {
        return CboBudget::findOrFail($id);
    }

    public function create(array $details) 
    {
        return CboBudget::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CboBudget::whereId($id)->update($newDetails);
    }
    
    public function listItems($request, $year = 'all')
    {  
        $columns = array( 
            0 => 'cbo_budgets.id',
            1 => 'cbo_budgets.budget_year',
            2 => 'acctg_departments.code',
            // 3 => 'acctg_departments_divisions.code',
            3 => 'acctg_fund_codes.code',
            4 => 'cbo_budgets.total_budget'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CboBudget::select([
            'cbo_budgets.*',
            'cbo_budgets.id as identity',
            'cbo_budgets.created_at as identityCreatedAt',
            'cbo_budgets.updated_at as identityUpdatedAt',
            'cbo_budgets.status as identityStatus',
            DB::raw('(SELECT SUM(cb.amount_used) FROM cbo_budget_breakdowns as cb WHERE cb.budget_id = cbo_budgets.id AND cb.is_active = 1) as total_used')
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cbo_budgets.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'cbo_budgets.division_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cbo_budgets.fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cbo_budgets.id', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.budget_year', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.total_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        if ($year != 'all') {
            $res = $res->where('cbo_budgets.budget_year', '=', $year);
        }
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems($request)
    {
        $columns = array( 
            0 => 'cbo_budgets.id',
            1 => 'cbo_budgets.budget_year',
            2 => 'acctg_departments.code',
            3 => 'acctg_departments_divisions.code',
            4 => 'acctg_fund_codes.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CboBudget::select([
            'cbo_budgets.*',
            'cbo_budgets.id as identity',
            'cbo_budgets.created_at as identityCreatedAt',
            'cbo_budgets.updated_at as identityUpdatedAt',
            'cbo_budgets.status as identityStatus'
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cbo_budgets.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'cbo_budgets.division_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cbo_budgets.fund_code_id');
        })
        ->where('cbo_budgets.status', '!=', 'draft')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cbo_budgets.id', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.budget_year', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.total_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budgets.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function line_listItems($request, $id)
    {  
        $columns = array( 
            0 => 'cbo_budget_breakdowns.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'cbo_budget_breakdowns.quarterly_budget',
            3 => 'cbo_budget_breakdowns.annual_budget',
            4 => 'cbo_budget_breakdowns.amount_used'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $category  = $request->get('category');

        $res = CboBudgetBreakdown::select([
            '*',
            'cbo_budget_breakdowns.id as identity',
            'cbo_budget_breakdowns.created_at as identityCreatedAt',
            'cbo_budget_breakdowns.updated_at as identityUpdatedAt',
            'cbo_budget_breakdowns.is_active as identityStatus'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_budget_breakdowns.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cbo_budget_breakdowns.quarterly_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_breakdowns.annual_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_breakdowns.amount_used', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'cbo_budget_breakdowns.budget_id' => $id
        ])
        ->orderBy($column, $order);
        if ($category != 'all') {
            $res = $res->where('cbo_budget_breakdowns.budget_category_id', '=', $category);
        }
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function reload_division_budget($department, $year)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department);
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }

    public function validate_breakdown($gl_account, $category, $budgetID, $id = '')
    {   
        if ($id !== '') {
            return CboBudgetBreakdown::where(['gl_account_id' => $gl_account, 'budget_category_id' => $category])->where('budget_id', $budgetID)->where('id', '!=', $id)->count();
        } 
        return CboBudgetBreakdown::where(['gl_account_id' => $gl_account, 'budget_category_id' => $category])->where('budget_id', $budgetID)->count();
    }

    public function find_breakdown($id) 
    {
        return CboBudgetBreakdown::findOrFail($id);
    }

    public function create_breakdown(array $details) 
    {
        return CboBudgetBreakdown::create($details);
    }

    public function update_breakdown($id, array $newDetails) 
    {
        return CboBudgetBreakdown::whereId($id)->update($newDetails);
    }

    public function update_breakdowns($budgetID, array $newDetails) 
    {
        return CboBudgetBreakdown::where('budget_id', '=', $budgetID)->update($newDetails);
    }

    public function getTotalAmount($budgetID)
    {
        $res = CboBudgetBreakdown::select([
            DB::raw('SUM(CASE WHEN 
            final_budget IS NOT NULL THEN 
                final_budget 
            ELSE annual_budget END) as totalAmt')
        ])
        ->where([
            'budget_id' => $budgetID,
            'is_active' => 1
        ])
        ->groupBy(['budget_id'])
        ->get();

        $totalAmt = 0;
        foreach ($res as $result) {
            $totalAmt += floatval($result->totalAmt);
        }

        return $totalAmt;
    }

    public function validate_budget($budgetID)
    {
        $res = CboBudget::find($budgetID);

        $validate = 0;
        $result = CboBudget::where([
            'budget_year' => $res->budget_year,
            'department_id' => $res->department_id,
            'division_id' => $res->division_id,
            'fund_code_id' => $res->fund_code_id
        ])
        ->where('id', '!=', $budgetID)
        ->get();

        if ($result->count() > 0) {
            $validate = 1;
        }

        return $validate;
    }

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }

    public function year_lists()
    {
        return CboBudget::where(['is_active' => 1])->whereNotNull('budget_year')->distinct()->get(['budget_year']);
    }

    public function category_lists()
    {
        return CboBudgetCategory::where(['is_active' => 1])->get();
    }

    public function copy($lists, $year, $timestamp, $user)
    {
        foreach($lists as $i => $list)
        {   
            $budget = CboBudget::find($list);
            if ($budget->budget_year != $year) 
            {
                $res = CboBudget::where([
                    'department_id' => $budget->department_id,
                    'fund_code_id' => $budget->fund_code_id,
                    'budget_year' => $year
                ])
                ->get();
                if (!($res->count() > 0)) {
                    $budgetDetail = CboBudget::create([
                        'department_id' => $budget->department_id,
                        'fund_code_id' => $budget->fund_code_id,
                        'budget_year' => $year,
                        'remarks' => $budget->remarks,
                        'total_budget' => $budget->total_budget,
                        'status' => 'draft',
                        'is_locked' => 0,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);

                    $breakdowns = CboBudgetBreakdown::where(['budget_id' => $budget->id, 'is_active' => 1])->get();
                    if ($breakdowns->count() > 0) {
                        foreach ($breakdowns as $breakdown) {
                            $res2 = CboBudgetBreakdown::where([
                                'gl_account_id' => $breakdown->gl_account_id,
                                'budget_category_id' => $breakdown->budget_category_id,
                                'budget_id' => $budgetDetail->id
                            ])
                            ->get();
                            if (!($res2->count() > 0)) {
                                $breakdownDetail = CboBudgetBreakdown::create([
                                    'budget_id' => $budgetDetail->id,
                                    'gl_account_id' => $breakdown->gl_account_id,
                                    'budget_category_id' => $breakdown->budget_category_id,
                                    'quarterly_budget' => $breakdown->quarterly_budget,
                                    'is_ppmp' => $breakdown->is_ppmp,
                                    'annual_budget' => $breakdown->annual_budget,
                                    'amount_used' => 0,
                                    'created_at' => $timestamp,
                                    'created_by' => $user
                                ]);
                            }
                        }
                    }

                    $res3 = CboBudgetBreakdown::select([
                        '*',
                        DB::raw('SUM(annual_budget) as totalBudget')
                    ])
                    ->where(['budget_id' => $budgetDetail->id, 'is_active' => 1])
                    ->groupBy('budget_id')
                    ->get();
            
                    if ($res3->count() > 0) {
                        $res3 = $res3->first();
                        CboBudget::whereId($budgetDetail->id)->update([
                            'total_budget' => $res3->totalBudget
                        ]);
                    }
                }
            }
        }

        return true;
    }

    public function allBudgetCategories()
    {
        return (new CboBudgetCategory)->allBudgetCategories();
    }

    public function insertAlignment(array $details) 
    {
        return CboBudgetAlignment::create($details);
    }
}