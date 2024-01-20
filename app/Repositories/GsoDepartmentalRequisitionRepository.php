<?php

namespace App\Repositories;

use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseRequestType;
use App\Models\GsoPurchaseType;
use App\Models\GsoItem;
use App\Models\GsoPPMPDetail;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;
use App\Models\User;
use App\Models\GsoDepartmentalRequestDisapproval;
use App\Models\UserRoleModule;
use App\Models\AcctgFundCode;
use App\Models\UserAccessApprovalApprover;
use App\Models\GsoDepartmentalRequestTrackingStatus;
use DB;

class GsoDepartmentalRequisitionRepository implements GsoDepartmentalRequisitionRepositoryInterface 
{
    public function getAll() 
    {
        return GsoDepartmentalRequisition::all();
    }

    public function find($id) 
    {
        return GsoDepartmentalRequisition::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoDepartmentalRequisition::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoDepartmentalRequisition::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return GsoDepartmentalRequisition::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoDepartmentalRequisition::whereId($id)->update($newDetails);
    }

    public function listItems($request, $user = '')
    {             
        $columns = array( 
            0 => 'gso_departmental_requests.id',
            1 => 'gso_departmental_requests.control_no',
            2 => 'gso_purchase_request_types.description',
            3 => 'acctg_departments.code',
            4 => 'hr_employees.fullname',   
            // 5 => 'gso_departmental_requests.remarks',
            5 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');
        
        $res = GsoDepartmentalRequisition::select([
            'gso_departmental_requests.*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_departmental_requests.is_active as prStatus'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.status', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%');
                // ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        });
        if ($user != '') {
            $ras = HrEmployee::where('user_id', $user)->first();
            if ($ras->is_dept_restricted > 0) {
                $res->whereIn('gso_departmental_requests.department_id',
                    HrEmployee::select('acctg_department_id')
                    ->where([
                        'user_id' => $user
                    ])
                    ->get()
                );
            } else {
                $res->whereIn('gso_departmental_requests.department_id', 
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
        if ($status != 'all') {
            $res->where('gso_departmental_requests.status', $status);
        }
        $res->orderBy($column, $order);
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

    public function approval_listItems($request, $type, $slugs, $user)
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
            LEFT JOIN user_access_approval_settings 
                ON user_access_approval_settings.id = app.setting_id
            LEFT JOIN menu_modules 
                ON menu_modules.id = user_access_approval_settings.module_id   
            WHERE menu_modules.slug LIKE '%".$slugs."%' AND user_access_approval_settings.sub_module_id IS NULL
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
        if (!empty($res)) {
            $query .= 'gso_departmental_requests.status != "draft" AND (';
            foreach ($res as $r) {
                if ($r->first > 0) {
                    if ($q <= 0) {
                        $query .= '(gso_departmental_requests.approved_counter >= 1';
                    } else {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 1';
                    }
                    $query .= ' AND gso_departmental_requests.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 2';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 2';
                    }
                    $query .= ' AND gso_departmental_requests.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 3';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 3';
                    }
                    $query .= ' AND gso_departmental_requests.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 4';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 4';
                    }
                    $query .= ' AND gso_departmental_requests.department_id = '.$r->department_id.')';
                    $q++;
                }            
                $iteration++;
            }
            $query .= ')';
        } else {
            $query .= 'gso_departmental_requests.status != "draft"';
        }

        $columns = array( 
            0 => 'gso_departmental_requests.control_no',
            1 => 'gso_purchase_request_types.description',
            2 => 'acctg_departments.code',
            3 => 'hr_employees.fullname',   
            4 => 'gso_departmental_requests.remarks',
            5 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.control_no' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequisition::select([
            '*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_departmental_requests.is_active as prStatus'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->where('gso_departmental_requests.status', '!=', 'draft')
        ->where(function($queue) use ($keywords) {
            if (!empty($keywords)) {
                $queue->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.status', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);

        if ($q > 0) {
            $res   = $res->whereRaw($query);
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $res   = $res->whereRaw('gso_departmental_requests.id < 0');
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        }  

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems2($request)
    {
        $columns = array( 
            0 => 'gso_departmental_requests.id',
            2 => 'gso_departmental_requests.control_no',
            3 => 'gso_purchase_request_types.description',
            4 => 'acctg_departments.code',
            5 => 'hr_employees.fullname',   
            6 => 'gso_departmental_requests.remarks',
            7 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequisition::select([
            '*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_departmental_requests.is_active as prStatus'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->where('gso_departmental_requests.status', '!=', 'draft')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.status', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemLines($request, $id)
    {             
        $columns = array( 
            0 => 'gso_departmental_requests_items.id',
            1 => 'gso_items.code',
            2 => 'gso_unit_of_measurements.code'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequestItem::select([
            '*',
            'gso_departmental_requests_items.id as itemId',
            'gso_departmental_requests_items.remarks as itemRemarks',
            'gso_departmental_requests_items.created_at as itemCreatedAt',
            'gso_departmental_requests_items.updated_at as itemUpdatedAt',
            'gso_departmental_requests_items.is_active as itemStatus'
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_requested', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_pr', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_po', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_posted', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_departmental_requests_items.departmental_request_id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function allDepartmentsWithRestriction($user)
    {
        return (new AcctgDepartment)->allDepartmentsWithRestriction($user);
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function allRequestTypes()
    {
        return (new GsoPurchaseRequestType)->allRequestTypes();
    }

    public function allPurchaseTypes()
    {
        return (new GsoPurchaseType)->allPurchaseTypes();
    }

    public function reload_items($purchase_type)
    {
        return (new GsoItem)->reload_items($purchase_type);
    }

    public function reload_itemx($fund_code, $department, $division, $requestDate, $category)
    {   
        $year = date('Y', strtotime($requestDate));
        $res = GsoItem::whereIn('id',
            GsoPPMPDetail::select(['gso_project_procurement_management_plans_details.item_id'])
            ->leftJoin('gso_project_procurement_management_plans', function($join)
            {
                $join->on('gso_project_procurement_management_plans.id', '=', 'gso_project_procurement_management_plans_details.ppmp_id');
            })
            ->where([
                'gso_project_procurement_management_plans.status' => 'locked',
                'gso_project_procurement_management_plans.budget_status' => 'locked',
                'gso_project_procurement_management_plans.fund_code_id' => $fund_code,
                'gso_project_procurement_management_plans.department_id' => $department,
                'gso_project_procurement_management_plans.budget_year' => $year,
                'gso_project_procurement_management_plans.budget_category_id' => $category,
                'gso_project_procurement_management_plans_details.division_id' => $division,
                'gso_project_procurement_management_plans_details.is_active' => 1
            ])
            ->get()
        )
        ->where(['is_active' => 1])
        ->get();

        return $res;
    }

    public function reload_uom($item)
    {
        return (new GsoUnitOfMeasurement)->find((new GsoItem)->find($item)->uom_id);
    }

    public function reload_unit_cost($item)
    {
        return (new GsoItem)->find($item)->weighted_cost;
    }

    public function reload_employees($department)
    {
        return (new HrEmployee)->reload_employees($department);
    }

    public function reload_divisions($department)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department);
    }

    public function reload_designation($employee)
    {
        return (new HrDesignation)->find((new HrEmployee)->find($employee)->hr_designation_id);
    }

    public function generate_control_no($department, $requested_date)
    {   
        $date       = date('Y-m-d', strtotime($requested_date)); 
        $year       = date('Y', strtotime($requested_date)); 
        $month      = date('m', strtotime($requested_date));
        $count      = GsoDepartmentalRequisition::where('department_id', $department)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
        $controlNo  = AcctgDepartment::find($department)->shortname.'-';
        $controlNo .= substr($date, -2);
        $controlNo .= $month;

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

    public function createItem(array $details) 
    {
        return GsoDepartmentalRequestItem::create($details);
    }

    public function updateLine($id, array $newDetails) 
    {
        return GsoDepartmentalRequestItem::whereId($id)->update($newDetails);
    }

    public function findItem($itemId)
    {
        return GsoItem::find($itemId);
    }

    public function computeTotalAmount($requisitionId)
    {
        return \DB::select(\DB::raw('SELECT SUM((CASE WHEN purchase_total_price > 0 THEN purchase_total_price ELSE request_total_price END)) as totalAmt FROM  gso_departmental_requests_items WHERE is_active = 1 AND departmental_request_id='. $requisitionId .''))[0]->totalAmt;
    }

    public function findLine($id) 
    {
        return GsoDepartmentalRequestItem::findOrFail($id);
    }
    
    public function removeLine($id) 
    {
        GsoDepartmentalRequestItem::destroy($id);
    }

    public function updateLines($id, array $newDetails) 
    {
        return GsoDepartmentalRequestItem::where('departmental_request_id', $id)->update($newDetails);
    }

    public function getAllItems($id) 
    {
        return GsoDepartmentalRequestItem::where(['departmental_request_id' => $id, 'is_active' => 1])->get();
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

    public function disapprove_request(array $details) 
    {   
        return  GsoDepartmentalRequestDisapproval::create($details);
    }

    public function fetch_remarks($id)
    {
        return GsoDepartmentalRequestDisapproval::where('departmental_request_id', $id)->first();
    }

    public function fetch($id)
    {
        return GsoDepartmentalRequisition::whereId($id)->get();
    }

    public function get_departmental_request_approvers($id)
    {
        $req = GsoDepartmentalRequisition::find($id);

        $res = UserRoleModule::select(['users_role_modules.user_id', 'users.email', 'hr_employees.nickname', 'hr_employees.is_dept_restricted', 'hr_employees.acctg_department_id'])
        ->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'users_role_modules.menu_module_id');
        })
        ->leftJoin('users', function($join)
        {
            $join->on('users.id', '=', 'users_role_modules.user_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.user_id', '=', 'users.id');
        })
        ->where([
            'users_role_modules.is_active' => 1,
            'menu_modules.slug' => 'for-approvals/departmental-requisition',
        ])
        ->where('users_role_modules.permissions', 'LIKE', '%approve%')
        ->get();

        $arr = array();
        if ($res->count() > 0){
            foreach ($res as $r) {
                if ($r->is_dept_restricted > 0) {
                    if($r->acctg_department_id == $req->department_id) {
                        // if (!in_array($r->user_id, $arr)) {
                            $arr[] = (object) array(
                                'id' => $r->user_id,
                                'nickname' => $r->nickname,
                                'email' => $r->email
                            );
                        // }
                    } 
                } else {
                    $count = HrEmployeeDepartmentalAccess::select('*')
                    ->leftJoin('hr_employees', function($join)
                    {
                        $join->on('hr_employees.id', '=', 'hr_employees_departmental_access.employee_id');
                    })
                    ->where([
                        'hr_employees_departmental_access.is_active' => 1,
                        'hr_employees.user_id' => $r->user_id,
                        'hr_employees_departmental_access.department_id' => $req->department_id
                    ])
                    ->count();

                    if ($count > 0) {
                        // if (!in_array($r->user_id, $arr)) {
                            $arr[] = (object) array(
                                'id' => $r->user_id,
                                'nickname' => $r->nickname,
                                'email' => $r->email
                            );
                        // }
                    }
                }
            }
        }
        
        return $arr;
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function validate_item_request($line, $fund, $year, $division, $category, $item, $quantity)
    {   
        $taken = 0;
        $res1 = GsoDepartmentalRequestItem::select([
            DB::raw('SUM(CASE 
                WHEN gso_departmental_requests_items.quantity_po > 0
                THEN gso_departmental_requests_items.quantity_po 
                WHEN gso_departmental_requests_items.quantity_pr > 0
                THEN gso_departmental_requests_items.quantity_pr 
                ELSE gso_departmental_requests_items.quantity_requested
            END) as taken')
        ])
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->whereYear('gso_departmental_requests.requested_date', '=', $year)
        ->where([
            'gso_departmental_requests.fund_code_id' => $fund, 
            'gso_departmental_requests.division_id' => $division,
            'gso_departmental_requests.budget_category_id' => $category,
            'gso_departmental_requests_items.is_active' => 1,
            'gso_departmental_requests_items.item_id' => $item
        ])
        ->groupBy(['gso_departmental_requests_items.item_id', 'gso_departmental_requests.division_id']);
        if ($line > 0) {
            $res1 = $res1->where('gso_departmental_requests_items.id', '!=', $line);
        }
        $res1 = $res1->get();
        if ($res1->count() > 0) {
            $res1 = $res1->first();
            $taken = $res1->taken ? $res1->taken : 0;
        }
        $taken += floatval($quantity);

        $qty = 0;
        $res2 = GsoPPMPDetail::select([
            DB::raw('SUM(gso_project_procurement_management_plans_details.budget_quantity) as qty')
        ])
        ->leftJoin('gso_project_procurement_management_plans', function($join)
        {
            $join->on('gso_project_procurement_management_plans.id', '=', 'gso_project_procurement_management_plans_details.ppmp_id');
        })
        ->where([
            'gso_project_procurement_management_plans.budget_year' => $year, 
            'gso_project_procurement_management_plans.fund_code_id' => $fund, 
            'gso_project_procurement_management_plans.budget_category_id' => $category, 
            'gso_project_procurement_management_plans_details.is_active' => 1,
            'gso_project_procurement_management_plans_details.division_id' => $division,
            'gso_project_procurement_management_plans_details.item_id' => $item
        ])
        ->groupBy([
            'gso_project_procurement_management_plans_details.item_id', 
            'gso_project_procurement_management_plans_details.division_id'
        ])
        ->get();
        if ($res2->count() > 0) {
            $res2 = $res2->first();
            $qty = $res2->qty ? $res2->qty : 0;
        }

        if (floatval($qty) >= floatval($taken)) {
            return true;
        }

        return false;
    }

    public function track_dept_request($requisitionId)
    {
        $requisition = GsoDepartmentalRequisition::find($requisitionId);
        $res = GsoDepartmentalRequestTrackingStatus::where('departmental_request_id', '=', $requisitionId)->get();
        if ($res->count() > 0) {
            $res = $res->first();
            $statuses = explode(',', $res->status); $dates = explode(',', $res->dates);
            if (!in_array($requisition->status, $statuses)) {
                $statuses[] = $requisition->status;
                $dates[] = $requisition->updated_at;
            }
            $tracking = GsoDepartmentalRequestTrackingStatus::whereId($res->id)->update([
                'status' => implode(',', $statuses),
                'dates' => implode(',', $dates)
            ]);
        } else {
            $tracking = GsoDepartmentalRequestTrackingStatus::create([
                'departmental_request_id' => $requisitionId,
                'status' => $requisition->status,
                'dates' => $requisition->created_at
            ]);
        }
        return $tracking;
    }

    public function track_request($requisitionId)
    {
        $res = GsoDepartmentalRequestTrackingStatus::where('departmental_request_id', '=', $requisitionId)->get();
        $statuses = array(); $dates = array(); $arr = array();
        if ($res->count() > 0) {
            $res = $res->first();
            $statuses = explode(',', $res->status);
            $dates = explode(',', $res->dates);
        }
        $iteration = 0;
        foreach ($statuses as $stat) {
            $arr[] = (object) array(
                'status' => $stat,
                'date' => date('d-M-Y H:i A', strtotime($dates[$iteration]))
            );
            $iteration++;
        }

        return $arr;
    }
}