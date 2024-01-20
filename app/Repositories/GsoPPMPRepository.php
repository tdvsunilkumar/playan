<?php

namespace App\Repositories;

use App\Interfaces\GsoPPMPInterface;
use App\Models\GsoPPMP;
use App\Models\GsoPPMPStatus;
use App\Models\GsoPPMPDetail;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\CboBudget;
use App\Models\CboBudgetBreakdown;
use App\Models\GsoItem;
use App\Models\UserAccessApprovalSetting;
use App\Models\UserAccessApprovalApprover;
use App\Models\HrEmployeeDepartmentalAccess;
use App\Models\HrEmployee;
use App\Models\User;
use App\Models\AcctgFundCode;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\CboBudgetCategory;
use App\Models\GsoPPMPBudget;
use DB;

class GsoPPMPRepository implements GsoPPMPInterface 
{
    public function find($id) 
    {
        return GsoPPMP::findOrFail($id);
    }
    
    public function validate($fund_code, $department, $budget, $category, $id = '')
    {   
        if ($id !== '') {
            return GsoPPMP::where(['fund_code_id' => $fund_code, 'department_id' => $department, 'budget_year' => $budget, 'budget_category_id' => $category])->where('id', '!=', $id)->count();
        } 
        return GsoPPMP::where('status', '!=', 'cancelled')->where(['fund_code_id' => $fund_code, 'department_id' => $department, 'budget_year' => $budget, 'budget_category_id' => $category])->count();
    }

    public function create(array $details) 
    {
        return GsoPPMP::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoPPMP::whereId($id)->update($newDetails);
    }

    public function update_division_status($id, array $newDetails) 
    {
        return GsoPPMPStatus::where('ppmp_id', $id)->update($newDetails);
    }

    public function listItems($request, $user = '')
    {   
        $columns = array( 
            0 => 'gso_project_procurement_management_plans.id',
            1 => 'gso_project_procurement_management_plans.control_no',
            2 => 'gso_project_procurement_management_plans.budget_year',
            3 => 'acctg_departments.code',
            4 => 'acctg_fund_codes.code',
            5 => 'gso_project_procurement_management_plans.remarks',
            6 => 'gso_project_procurement_management_plans.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_project_procurement_management_plans.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = urldecode($request->get('status'));
        $year      = urldecode($request->get('year'));

        $res = GsoPPMP::select([
            'gso_project_procurement_management_plans.*',
            'gso_project_procurement_management_plans.id as identity',
            'gso_project_procurement_management_plans.remarks as identityRemarks',
            'gso_project_procurement_management_plans.created_at as identityCreatedAt',
            'gso_project_procurement_management_plans.updated_at as identityUpdatedAt',
            'gso_project_procurement_management_plans.status as identityStatus'
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_project_procurement_management_plans.department_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'gso_project_procurement_management_plans.fund_code_id');
        })
        ->leftJoin('cbo_budget_categories', function($join)
        {
            $join->on('cbo_budget_categories.id', '=', 'gso_project_procurement_management_plans.budget_category_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_project_procurement_management_plans.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.budget_year', 'like', '%' . $keywords . '%')
                // ->orWhere('gso_project_procurement_management_plans.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.status', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res = $res->where('gso_project_procurement_management_plans.status', $status);
        }
        if ($year != 'all') {
            $res = $res->where('gso_project_procurement_management_plans.budget_year', $year);
        }
        if ($user != '') {
            $ras = HrEmployee::where('user_id', $user)->first();
            if ($ras->is_dept_restricted > 0) {
                $res->whereIn('gso_project_procurement_management_plans.department_id',
                    HrEmployee::select('acctg_department_id')
                    ->where([
                        'user_id' => $user
                    ])
                    ->get()
                );
            } else {
                $res->whereIn('gso_project_procurement_management_plans.department_id', 
                    HrEmployeeDepartmentalAccess::select('hr_employees_departmental_access.department_id')
                    ->leftJoin('hr_employees', function($join)
                    {
                        $join->on('hr_employees.id', '=', 'hr_employees_departmental_access.employee_id');
                    })
                    ->where([
                        'hr_employees_departmental_access.is_active' => 1,
                        'hr_employees.user_id' => $user
                    ])
                    ->get()
                );
            }
        }
        $res   = $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
    
    public function app_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_project_procurement_management_plans.id',
            1 => 'gso_project_procurement_management_plans.control_no',
            2 => 'gso_project_procurement_management_plans.budget_year',
            3 => 'acctg_departments.code',
            4 => 'acctg_fund_codes.code',
            5 => 'gso_project_procurement_management_plans.remarks',
            6 => 'gso_project_procurement_management_plans.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_project_procurement_management_plans.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = urldecode($request->get('status'));
        $year      = urldecode($request->get('year'));

        $res = GsoPPMP::select([
            'gso_project_procurement_management_plans.*',
            'gso_project_procurement_management_plans.id as identity',
            'gso_project_procurement_management_plans.remarks as identityRemarks',
            'gso_project_procurement_management_plans.created_at as identityCreatedAt',
            'gso_project_procurement_management_plans.updated_at as identityUpdatedAt',
            'gso_project_procurement_management_plans.budget_status as identityStatus'
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_project_procurement_management_plans.department_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'gso_project_procurement_management_plans.fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_project_procurement_management_plans.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.budget_year', 'like', '%' . $keywords . '%')
                // ->orWhere('gso_project_procurement_management_plans.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.status', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_project_procurement_management_plans.status', 'locked');
        if ($status != 'all') {
            $res = $res->where('gso_project_procurement_management_plans.budget_status', $status);
        }
        if ($year != 'all') {
            $res = $res->where('gso_project_procurement_management_plans.budget_year', $year);
        }
        $res = $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function find_levels($slugs, $type)
    {   
        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->where(['menu_modules.slug' => $slugs])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->get();
        } else {
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
            })
            ->where(['menu_sub_modules.slug' => $slugs])
            ->get();
        }

        if ($res->count() > 0) {
            return intval($res->first()->levels);
        } else {
            return 'System Error';
        }
    }

    public function validate_approver($department, $sequence, $type, $slugs, $user)
    {   
        $query = '';
        if ($sequence == 1) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.primary_approvers)';
        } else if ($sequence == 2) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.secondary_approvers)';
        } else if ($sequence == 3) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.tertiary_approvers)';
        } else {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.quaternary_approvers)';
        }

        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('*')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->whereRaw($query)
            ->where(['menu_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->count();
        } else {
            $res = UserAccessApprovalApprover::select('*')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
            })
            ->whereRaw($query)
            ->where(['menu_sub_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department ])
            ->count();
        }

        return $res;
    }

    public function approvals_listItems($request, $type, $slugs, $user)
    {   
        if ($type == 'modules') { 
            $res = DB::select( DB::raw("
            SELECT app.department_id, 
            CASE 
                WHEN (FIND_IN_SET($user,app.primary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'first',
            CASE 
                WHEN (FIND_IN_SET($user,app.secondary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'second',
            CASE 
                WHEN (FIND_IN_SET($user,app.tertiary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'third',
            CASE 
                WHEN (FIND_IN_SET($user,app.quaternary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'fourth'
            FROM
                `user_access_approval_approvers` as app
            LEFT JOIN user_access_approval_settings ON user_access_approval_settings.id = app.setting_id
            LEFT JOIN menu_modules ON menu_modules.id = user_access_approval_settings.module_id   
            WHERE menu_modules.slug = '$slugs' AND  user_access_approval_settings.sub_module_id IS NULL
            ") );
        } else {
            $res = DB::select( DB::raw("
            SELECT app.department_id, 
            CASE 
                WHEN (FIND_IN_SET($user,app.primary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'first',
            CASE 
                WHEN (FIND_IN_SET($user,app.secondary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'second',
            CASE 
                WHEN (FIND_IN_SET($user,app.tertiary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'third',
            CASE 
                WHEN (FIND_IN_SET($user,app.quaternary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'fourth'
            FROM
                `user_access_approval_approvers` as app
            LEFT JOIN user_access_approval_settings ON user_access_approval_settings.id = app.setting_id
            LEFT JOIN menu_sub_modules ON menu_sub_modules.id = user_access_approval_settings.sub_module_id   
            WHERE menu_sub_modules.slug = '$slugs'
            ") );
        }

        $query = ''; $q = 0; $iteration = 0;
        foreach ($res as $r) {
            if ($r->first > 0) {
                if ($q <= 0) {
                    $query .= '((';
                    $query .= 'gso_project_procurement_management_plans.approved_counter >= 1';
                } else {
                    $query .= ' OR (gso_project_procurement_management_plans.approved_counter >= 1';
                }
                $query .= ' AND gso_project_procurement_management_plans.department_id = '.$r->department_id.')';
                $q++;
            }
            if ($r->second > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_project_procurement_management_plans.approved_counter >= 2';
                } else {
                    $query .= '((gso_project_procurement_management_plans.approved_counter >= 2';
                }
                $query .= ' AND gso_project_procurement_management_plans.department_id = '.$r->department_id.')';
                $q++;
            }
            if ($r->third > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_project_procurement_management_plans.approved_counter >= 3';
                } else {
                    $query .= '((gso_project_procurement_management_plans.approved_counter >= 3';
                }
                $query .= ' AND gso_project_procurement_management_plans.department_id = '.$r->department_id.')';
                $q++;
            }
            if ($r->fourth > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_project_procurement_management_plans.approved_counter >= 4';
                } else {
                    $query .= '((gso_project_procurement_management_plans.approved_counter >= 4';
                }
                $query .= ' AND gso_project_procurement_management_plans.department_id = '.$r->department_id.')';
                $q++;
            }           
            $iteration++;
        }
        if ($q > 0) {
            $query .= ' AND gso_project_procurement_management_plans.status != "draft")';
        }
        // dd($query);

        $columns = array( 
            0 => 'gso_project_procurement_management_plans.id',
            1 => 'gso_project_procurement_management_plans.control_no',
            2 => 'gso_project_procurement_management_plans.budget_year',
            3 => 'acctg_departments.code',
            4 => 'acctg_fund_codes.code',
            5 => 'gso_project_procurement_management_plans.remarks',
            6 => 'gso_project_procurement_management_plans.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_project_procurement_management_plans.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPPMP::select([
            '*',
            'gso_project_procurement_management_plans.id as identity',
            'gso_project_procurement_management_plans.remarks as identityRemarks',
            'gso_project_procurement_management_plans.created_at as identityCreatedAt',
            'gso_project_procurement_management_plans.updated_at as identityUpdatedAt',
            'gso_project_procurement_management_plans.status as identityStatus'
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_project_procurement_management_plans.department_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'gso_project_procurement_management_plans.fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_project_procurement_management_plans.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.budget_year', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_project_procurement_management_plans.status', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_project_procurement_management_plans.status', '!=', 'draft');
        $res = $res->orderBy($column, $order);
        if ($q > 0) {
            $res   = $res->whereRaw($query);
            $count = $res->count();
        } else {
            $res   = $res->whereRaw('gso_project_procurement_management_plans.id < 0');
            $count = $res->count();
        }  
        if ($limit != -1) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartmentsWithRestriction($user)
    {
        return (new AcctgDepartment)->allDepartmentsWithRestriction($user);
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }
    
    public function allDivisions()
    {
        return (new AcctgDepartmentDivision)->allDivisions();
    }

    public function allItems()
    {
        return (new GsoItem)->allItems();
    }

    public function allItemsViaGL($gl_account, $field = 0)
    {
        return (new GsoItem)->allItemsViaGL($gl_account, $field);
    }

    public function validate_item_details($ppmpID, $division, $itemID)
    {
        return $res = GsoPPMPDetail::where([
            'ppmp_id' => $ppmpID,
            'division_id' => $division,
            'item_id' => $itemID,
            'is_active' => 1
        ])
        ->count();
    }

    public function validate_item_removal($id)
    {
        $ppmp_details = GsoPPMPDetail::select([
            'gso_project_procurement_management_plans_details.*'
        ])
        ->leftJoin('gso_project_procurement_management_plans', function($join)
        {
            $join->on('gso_project_procurement_management_plans.id', '=', 'gso_project_procurement_management_plans_details.ppmp_id');
        })
        ->where([
            'gso_project_procurement_management_plans_details.id' => $id
        ])
        ->first();

        $result = GsoDepartmentalRequestItem::
        leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->where([
            'gso_departmental_requests_items.is_active' => 1,
            'gso_departmental_requests_items.item_id' => $ppmp_details->item_id,
            'gso_departmental_requests.fund_code_id' => $ppmp_details->ppmp->fund_code_id,
            'gso_departmental_requests.department_id' => $ppmp_details->ppmp->department_id,
            'gso_departmental_requests.division_id' => $ppmp_details->division_id,
        ])
        ->whereYear('gso_departmental_requests.requested_date', '=', $ppmp_details->ppmp->budget_year)
        ->count();

        if ($result > 0) {
            return false;
        }
        return true;
    }

    public function fetch_item_details($itemID)
    {
        return (new GsoItem)->select(['gso_items.*'])->with(['gl_account', 'uom'])->find($itemID);
    }   

    public function generate_control_no($year)
    {
        // $year        = date('Y'); 
        $count       = GsoPPMP::whereYear('budget_year', '=', $year)->count();
        $controlNo   = '';
        $controlNo  .= $year . '-';

        if($count < 9) {
            $controlNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $controlNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $controlNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $controlNo .= '0' . ($count + 1);
        } else {
            $controlNo .= ($count + 1);
        }
        return $controlNo;
    }

    public function getAllItems()
    {
        return GsoItem::where(['is_active' => 1])->get();
    }

    public function update_lines($ppmpID, $request, $timestamp, $user)
    {
        $lineID = $request->get('id');
        if ($request->get('item') > 0) {
            if ($lineID > 0) {
                if ($request->get('division') == 'ALL') {
                    GsoPPMPDetail::whereId($lineID)
                    ->update([
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'quantity' => $request->get('quantity'),
                        'amount' => str_replace(',', '', $request->get('amount')),
                        'total_amount' => str_replace(',', '', $request->get('total')),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                    $res = GsoPPMPDetail::find($lineID);
                } else {
                    GsoPPMPDetail::whereId($lineID)
                    ->update([
                        'division_id' => $request->get('division'),
                        'gl_account_id' => $request->get('gl_account'),
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'quantity' => $request->get('quantity'),
                        'amount' => str_replace(',', '', $request->get('amount')),
                        'total_amount' => str_replace(',', '', $request->get('total')),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                    $res = GsoPPMPDetail::find($lineID);
                }
            } else {
                $res = GsoPPMPDetail::create([
                    'ppmp_id' => $ppmpID,
                    'division_id' => $request->get('division'),
                    'gl_account_id' => $request->get('gl_account'),
                    'item_id' => $request->get('item'),
                    'uom_id' => $request->get('uom'),
                    'quantity' => $request->get('quantity'),
                    'amount' => str_replace(',', '', $request->get('amount')),
                    'total_amount' => str_replace(',', '', $request->get('total')),
                    'budget_quantity' => $request->get('quantity'),
                    'budget_amount' => str_replace(',', '', $request->get('amount')),
                    'budget_total_amount' => str_replace(',', '', $request->get('total')),
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        } else {
            GsoPPMPDetail::whereId($lineID)
            ->update([
                'is_active' => 0,
                'updated_at' => $timestamp,
                'updated_by' => $user
            ]);
            $res = GsoPPMPDetail::find($lineID);
        }        

        $res2 = GsoPPMPDetail::select([
            DB::raw('SUM(total_amount) as totalAmt'),
        ])
        ->where(['ppmp_id' => $ppmpID, 'is_active' => 1])
        ->groupBy('ppmp_id')
        ->first();
        GsoPPMP::whereId($ppmpID)->update(['total_amount' => $res2->totalAmt, 'budget_total_amount' => $res2->totalAmt]);

        return $res;
    }

    public function update_lines2($ppmpID, $request, $timestamp, $user)
    {
        $lineID = $request->get('id');
        if ($request->get('item') > 0) {
            if ($lineID > 0) {
                if ($request->get('division') == 'ALL') {
                    GsoPPMPDetail::whereId($lineID)
                    ->update([
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                    $res = GsoPPMPDetail::find($lineID);
                } else {
                    GsoPPMPDetail::whereId($lineID)
                    ->update([
                        'division_id' => $request->get('division'),
                        'gl_account_id' => $request->get('gl_account'),
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                    $res = GsoPPMPDetail::find($lineID);
                }
            } else {
                if ($request->get('cbo') > 0) {
                    $res = GsoPPMPDetail::create([
                        'ppmp_id' => $ppmpID,
                        'division_id' => $request->get('division'),
                        'gl_account_id' => $request->get('gl_account'),
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'is_departmental' => 0,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                } else {
                    $res = GsoPPMPDetail::create([
                        'ppmp_id' => $ppmpID,
                        'division_id' => $request->get('division'),
                        'gl_account_id' => $request->get('gl_account'),
                        'item_id' => $request->get('item'),
                        'uom_id' => $request->get('uom'),
                        'budget_quantity' => $request->get('quantity'),
                        'budget_amount' => str_replace(',', '', $request->get('amount')),
                        'budget_total_amount' => str_replace(',', '', $request->get('total')),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        } else {
            GsoPPMPDetail::whereId($lineID)
            ->update([
                'is_active' => 0,
                'updated_at' => $timestamp,
                'updated_by' => $user
            ]);
            $res = GsoPPMPDetail::find($lineID);
        }        

        $res2 = GsoPPMPDetail::select([
            DB::raw('SUM(budget_total_amount) as totalAmt'),
        ])
        ->where(['ppmp_id' => $ppmpID, 'is_active' => 1])
        ->groupBy('ppmp_id')
        ->first();
        GsoPPMP::whereId($ppmpID)->update(['budget_total_amount' => $res2->totalAmt]);

        return $res;
    }

    public function find_lines($ppmpID, $gl_account, $division = '')
    {   
        if ($division != '') {
            return GsoPPMPDetail::where(['ppmp_id' => $ppmpID, 'gl_account_id' => $gl_account, 'division_id' => $division, 'is_active' => 1])->get();
        } else {
            return GsoPPMPDetail::where(['ppmp_id' => $ppmpID, 'gl_account_id' => $gl_account, 'is_active' => 1])->get();
        }
    }

    public function find_budgets($ppmpID)
    {   
        $res = GsoPPMP::find($ppmpID);
        $res2 = CboBudgetBreakdown::select('*')
        ->leftJoin('cbo_budgets', function($join)
        {
            $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
        })
        ->where([
            'cbo_budgets.budget_year' => $res->budget_year,
            'cbo_budgets.department_id' => $res->department_id,
            'cbo_budgets.fund_code_id' => $res->fund_code_id,
            'cbo_budgets.status' => 'locked',
            'cbo_budget_breakdowns.budget_category_id' => $res->budget_category_id,
            'cbo_budget_breakdowns.is_ppmp' => 1,
            'cbo_budget_breakdowns.is_active' => 1 
        ])
        ->orderBy('cbo_budget_breakdowns.gl_account_id', 'asc')
        ->get();

        return $res2;
    }

    public function get_budgets($ppmpID)
    {   
        $res = GsoPPMP::find($ppmpID);
        $gl_accounts = AcctgAccountGeneralLedger::whereIn('id',
            CboBudgetBreakdown::select(['cbo_budget_breakdowns.gl_account_id'])
            ->leftJoin('cbo_budgets', function($join)
            {
                $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
            })
            ->where([
                'cbo_budgets.budget_year' => $res->budget_year,
                'cbo_budgets.department_id' => $res->department_id,
                'cbo_budgets.fund_code_id' => $res->fund_code_id,
                'cbo_budgets.status' => 'locked',
                'cbo_budget_breakdowns.budget_category_id' => $res->budget_category_id,
                'cbo_budget_breakdowns.is_ppmp' => 1,
                'cbo_budget_breakdowns.is_active' => 1 
            ])
            ->get()
        )->where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $gls = array();
        if (!empty($vars)) {
            $gls[] = array('' => 'select a '.$vars);
        } else {
            $gls[] = array('' => 'select a gl account');
        }
        foreach ($gl_accounts as $gl_account) {
            $gls[] = array(
                $gl_account->code => $gl_account->code . ' - ' . $gl_account->description
            );
        }

        $gl_accounts = array();
        foreach($gls as $gl) {
            foreach($gl as $key => $val) {
                $gl_accounts[$key] = $val;
            }
        }

        return $gl_accounts;
    }

    public function fetch_division_status($ppmpID, $division)
    {
        $res = GsoPPMPStatus::where(['ppmp_id' => $ppmpID, 'division_id' => $division, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first()->status;
        }
        return 'draft';
    }

    public function lock_division($ppmpID, $division, $timestamp, $user)
    {
        $res = GsoPPMPStatus::where(['ppmp_id' => $ppmpID, 'division_id' => $division])->get();
        if ($res->count() > 0) {
            $res = GsoPPMPStatus::find($res->first()->id);
            $res->division_id = $division;
            $res->updated_at = $timestamp;
            $res->updated_by = $user;
            $res->status = 'locked';
            $res->is_active = 1;
            $res->update();
        } else {
            GsoPPMPStatus::create([
                'ppmp_id' => $ppmpID,
                'division_id' => $division,
                'status' => 'locked',
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }

        return true;
    }

    public function check_if_division_locked($ppmpID, $division, $budget = 0)
    {   
        if ($budget > 0) {
            return GsoPPMPStatus::where(['ppmp_id' => $ppmpID, 'division_id' => $division, 'budget_status' => 'locked'])->count();
        }
        return GsoPPMPStatus::where(['ppmp_id' => $ppmpID, 'division_id' => $division, 'status' => 'locked'])->count();
    }

    public function validate_if_budget_exist($fund_code, $department, $budget_year, $category)
    {   
        return CboBudgetBreakdown::leftJoin('cbo_budgets', function($join)
        {
            $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
        })
        ->where([
            'cbo_budgets.fund_code_id' => $fund_code, 
            'cbo_budgets.department_id' => $department, 
            'cbo_budgets.budget_year' => $budget_year, 
            'cbo_budget_breakdowns.budget_category_id' => $category, 
            'cbo_budget_breakdowns.is_active' => 1
        ])
        ->count();
        // return CboBudget::where(['fund_code_id' => $fund_code, 'department_id' => $department, 'budget_year' => $budget, 'budget_category_id' => $category, 'is_active' => 1])->count();
    }

    public function remove_lines($id, array $newDetails) 
    {   
        $ppmp_detail = GsoPPMPDetail::whereId($id)->update($newDetails);
        $detail = GsoPPMPDetail::find($id);
        $res2 = GsoPPMPDetail::select([
            DB::raw('SUM(total_amount) as totalAmt'),
        ])
        ->where(['ppmp_id' => $detail->ppmp_id, 'is_active' => 1])
        ->groupBy('ppmp_id')
        ->first();
        GsoPPMP::whereId($detail->ppmp_id)->update(['total_amount' => $res2->totalAmt, 'budget_total_amount' => $res2->totalAmt]);
        return $ppmp_detail;
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

    public function disapprove($id, array $newDetails) 
    {
        return GsoPPMP::whereId($id)->update($newDetails);
    }

    public function validate_division_status($ppmpID)
    {   
        $result = GsoPPMP::select(['*'])->where('id', $ppmpID)->get();

        $status = true;
        foreach ($result as $res) {
            foreach ($res->department->divisions as $div) {
                $locked = $this->check_if_division_locked($ppmpID, $div->id);
                if (!($locked > 0)) {
                    $status = false;
                    break; break; break;
                }
            }
        }

        return $status;
    }

    public function copy($copyID, $ppmpID, $timestamp, $user)
    {
        $details = GsoPPMPDetail::where(['ppmp_id' => $copyID, 'is_active' => 1])->get();
        foreach($details as $i => $detail)
        {   
            $ppmp_detail[$i] = new GsoPPMPDetail();
            $ppmp_detail[$i]->ppmp_id = $ppmpID;
            $ppmp_detail[$i]->division_id = $detail->division_id;
            $ppmp_detail[$i]->gl_account_id = $detail->gl_account_id;
            $ppmp_detail[$i]->item_id = $detail->item_id;
            $ppmp_detail[$i]->uom_id = $detail->uom_id;
            $ppmp_detail[$i]->quantity = $detail->quantity;
            $ppmp_detail[$i]->amount = $detail->amount;
            $ppmp_detail[$i]->total_amount = $detail->total_amount;
            $ppmp_detail[$i]->created_at = $timestamp;
            $ppmp_detail[$i]->created_by = $user;
            $ppmp_detail[$i]->save();
        }

        $res2 = GsoPPMPDetail::select([
            DB::raw('SUM(total_amount) as totalAmt'),
        ])
        ->where(['ppmp_id' => $ppmpID, 'is_active' => 1])
        ->groupBy('ppmp_id')
        ->first();
        GsoPPMP::whereId($ppmpID)->update(['total_amount' => $res2->totalAmt]);
    }

    public function validate_item_request($month, $item, $fund_code, $department, $division = '', $year)
    {
        if ($division != '') {
            $res = GsoDepartmentalRequestItem::
            leftJoin('gso_departmental_requests', function($join)
            {
                $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
            })
            ->where([
                'gso_departmental_requests_items.item_id' => $item,
                'gso_departmental_requests.fund_code_id' => $fund_code,
                'gso_departmental_requests.department_id' => $department,
                'gso_departmental_requests.division_id' => $division
            ])
            ->where('gso_departmental_requests.request_type_id', '!=', 3)
            ->whereYear('gso_departmental_requests.requested_date', '=', $year)
            ->whereMonth('gso_departmental_requests.requested_date', '=', $month)
            ->groupBy('gso_departmental_requests_items.item_id')
            ->sum(DB::raw('(CASE 
                WHEN gso_departmental_requests_items.quantity_po > 0
                THEN gso_departmental_requests_items.quantity_po 
                WHEN gso_departmental_requests_items.quantity_pr > 0
                THEN gso_departmental_requests_items.quantity_pr 
                ELSE gso_departmental_requests_items.quantity_requested
            END)'));
        } else {
            $res = GsoDepartmentalRequestItem::
            leftJoin('gso_departmental_requests', function($join)
            {
                $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
            })
            ->where([
                'gso_departmental_requests_items.item_id' => $item,
                'gso_departmental_requests.fund_code_id' => $fund_code,
                'gso_departmental_requests.department_id' => $department
            ])
            ->where('gso_departmental_requests.request_type_id', '!=', 3)
            ->whereYear('gso_departmental_requests.requested_date', '=', $year)
            ->whereMonth('gso_departmental_requests.requested_date', '=', $month)
            // ->sum('gso_departmental_requests_items.quantity_po');
            ->groupBy('gso_departmental_requests_items.item_id')
            ->sum(DB::raw('(CASE 
                WHEN gso_departmental_requests_items.quantity_po > 0
                THEN gso_departmental_requests_items.quantity_po 
                WHEN gso_departmental_requests_items.quantity_pr > 0
                THEN gso_departmental_requests_items.quantity_pr 
                ELSE gso_departmental_requests_items.quantity_requested
            END)'));
        }

        return $res;
    }

    public function allBudgetCategories()
    {
        return (new CboBudgetCategory)->allBudgetCategories();
    }

    public function fetch_budget_lists($fund, $department, $budget_category, $budget_year)
    {   
        $res1 = CboBudgetBreakdown::with([
            'gl_account'
        ])
        ->select([
            'cbo_budget_breakdowns.*'
        ])
        ->leftJoin('cbo_budgets', function($join)
        {
            $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
        })
        ->where([
            'cbo_budgets.fund_code_id' => $fund,
            'cbo_budgets.department_id' => $department,
            'cbo_budgets.budget_year' => $budget_year,
            'cbo_budgets.status' => 'locked',
            'cbo_budget_breakdowns.budget_category_id' => $budget_category,
            'cbo_budget_breakdowns.is_ppmp' => 1,
            'cbo_budget_breakdowns.is_active' => 1
        ])
        ->get();

        $res2 = AcctgDepartmentDivision::with([
            'department'
        ])
        ->where([
            'acctg_department_id' => $department,
            'is_active' => 1
        ])
        ->get();

        return $arr = (object) array(
            'budgets' => $res1,
            'divisions' => $res2
        );
    }

    public function check_if_budget_plan($ppmpID, $gl_account, $division, $category)
    {
        return $res = GsoPPMPBudget::where([
            'ppmp_id' => $ppmpID,
            'gl_account_id' => $gl_account,
            'division_id' => $division,
            'budget_category_id' => $category
        ])
        ->get();
    }

    public function create_budget_plan(array $details) 
    {
        return GsoPPMPBudget::create($details);
    }

    public function update_budget_plan($id, array $newDetails) 
    {
        return GsoPPMPBudget::whereId($id)->update($newDetails);
    }

    public function find_budget_plan($ppmpID, $gl_account, $division, $category)
    {
        $res = GsoPPMPBudget::where([
            'ppmp_id' => $ppmpID,
            'gl_account_id' => $gl_account,
            'division_id' => $division,
            'budget_category_id' => $category
        ])
        ->get();

        if ($res->count() > 0) {
            return $res->first();
        }
        return $res;
    }
}