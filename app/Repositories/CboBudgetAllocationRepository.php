<?php

namespace App\Repositories;

use App\Interfaces\CboBudgetAllocationInterface;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\AcctgFundCode;
use App\Models\CboAllotmentObligation;
use App\Models\CboAllotmentBreakdown;
use App\Models\CboBudget;
use App\Models\CboBudgetBreakdown;
use App\Models\CboPayee;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoItem;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseRequestType;
use App\Models\GsoPurchaseType;
use App\Models\GsoUnitOfMeasurement;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\CboAllotmentObligationRequest;
use App\Models\GsoDepartmentalRequestDisapproval;
use App\Models\CboObligationType;
use App\Models\User;
use App\Models\UserAccessApprovalApprover;
use App\Models\RptLocality;
use DB;

class CboBudgetAllocationRepository implements CboBudgetAllocationInterface 
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
        return CboAllotmentObligation::create($details);
    }

    public function create_request(array $details)
    {
        return CboAllotmentObligationRequest::create($details);
    }

    public function update_request($id, array $newDetails)
    {   
        $locality = RptLocality::select('*')->where(['loc_local_name' => 'Palayan City', 'department' => 4])->get();
        if ($locality->count() > 0) {
            $locality = $locality->first();
            $newDetails['budget_officer_id'] = $locality->loc_budget_officer_id;
            $newDetails['budget_officer_designation'] = $locality->loc_budget_officer_position;
            $newDetails['treasurer_id'] = $locality->loc_treasurer_id;
            $newDetails['treasurer_designation'] = $locality->loc_treasurer_position;
            $newDetails['mayor_id'] = $locality->loc_mayor_id;
            $newDetails['mayor_designation'] = 'City Mayor';
        } 
        return CboAllotmentObligationRequest::where('allotment_id', $id)->update($newDetails);
    }

    public function updateRequest($id, array $newDetails) 
    {   
        GsoDepartmentalRequestItem::where(['departmental_request_id' => $id, 'is_active' => 1])->update($newDetails);
        return GsoDepartmentalRequisition::whereId($id)->update($newDetails);
    }

    public function update($id, array $newDetails) 
    {
        return CboAllotmentObligation::where('departmental_request_id', $id)->update($newDetails);
    }

    public function listItems($request)
    {  
        $columns = array( 
            0 => 'cbo_allotment_obligations.budget_control_no',
            1 => 'cbo_obligation_types.name',
            2 => 'gso_departmental_requests.control_no',
            3 => 'acctg_departments.code',
            4 => 'cbo_allotment_obligations.particulars',
            5 => 'cbo_allotment_obligations.total_amount',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['completed'];

        $res = CboAllotmentObligationRequest::select([
            '*',
            'cbo_allotment_obligations.id as obligId',
            'cbo_allotment_obligations.department_id as obligDepartment',
            'cbo_allotment_obligations.created_at as obligCreatedAt',
            'cbo_allotment_obligations.updated_at as obligUpdatedAt',
            'cbo_allotment_obligations.status as obligStatus'
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_obligations_requests.allotment_id');
        })
        ->leftJoin('cbo_obligation_types', function($join)
        {
            $join->on('cbo_obligation_types.id', '=', 'cbo_allotment_obligations.obligation_type_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
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
            $join->on('acctg_departments.id', '=', 'cbo_allotment_obligations.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'cbo_allotment_obligations.division_id');
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
                $q->where('cbo_allotment_obligations.budget_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_obligations.particulars', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_obligations.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->whereIn('cbo_allotment_obligations_requests.status', $status)
        ->orderBy($column, $order);
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
            $query .= 'cbo_allotment_obligations.status IN ("for approval", "completed", "cancelled")';
            $in_query = '';
            foreach ($res as $r) {
                if ($r->first > 0) {
                    if ($q <= 0) {
                        $in_query .= '(cbo_allotment_obligations.approved_counter >= 1';
                    } else {
                        $in_query .= ' OR (cbo_allotment_obligations.approved_counter >= 1';
                    }
                    $in_query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $in_query .= ' OR (cbo_allotment_obligations.approved_counter >= 2';
                    } else {
                        $in_query .= '(cbo_allotment_obligations.approved_counter >= 2';
                    }
                    $in_query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $in_query .= ' OR (cbo_allotment_obligations.approved_counter >= 3';
                    } else {
                        $in_query .= '(cbo_allotment_obligations.approved_counter >= 3';
                    }
                    $in_query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $in_query .= ' OR (cbo_allotment_obligations.approved_counter >= 4';
                    } else {
                        $in_query .= '(cbo_allotment_obligations.approved_counter >= 4';
                    }
                    $in_query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }            
                $iteration++;
            }
            if ($in_query) {
                $query = $query.' AND (' . $in_query. ')';
            }
        } else {
            $query .= 'cbo_allotment_obligations.status IN ("for approval", "completed", "cancelled")';
        }
        $columns = array( 
            0 => 'cbo_allotment_obligations.budget_control_no',
            1 => 'gso_departmental_requests.control_no',
            2 => 'acctg_departments.code',
            3 => 'cbo_payee.paye_name',
            4 => 'cbo_allotment_obligations.particulars',
            5 => 'cbo_allotment_obligations.total_amount',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['for approval', 'completed', 'cancelled'];
        // $empID     = HrEmployee::select('id')->where(['user_id' => $user])->first()->id;

        $res = CboAllotmentObligation::select([
            'cbo_allotment_obligations.*',
            'cbo_allotment_obligations.id as obligId',
            'cbo_allotment_obligations.department_id as obligDepartment',
            'cbo_allotment_obligations.created_at as obligCreatedAt',
            'cbo_allotment_obligations.updated_at as obligUpdatedAt',
            'cbo_allotment_obligations.status as obligStatus',
            'cbo_allotment_obligations.approved_at as obligApprovedAt',
            'cbo_allotment_obligations.approved_by as obligApprovedBy',
            'cbo_allotment_obligations.disapproved_at as obligDisapprovedAt',
            'cbo_allotment_obligations.disapproved_by as obligDisapprovedBy',
            'cbo_allotment_obligations.total_amount as allotmentTotal'
        ])

        ->leftJoin('cbo_obligation_types', function($join)
        {
            $join->on('cbo_obligation_types.id', '=', 'cbo_allotment_obligations.obligation_type_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'cbo_allotment_obligations.payee_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
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
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_payee.paye_name', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_obligations.particulars', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->whereRaw($query)
        ->whereIn('cbo_allotment_obligations.id', 
            (new CboAllotmentObligationRequest)->select('allotment_id')
            ->where('status', 'completed')
            ->get()
        );
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsx($request)
    {             
        $columns = array( 
            0 => 'gso_departmental_requests.control_no',
            1 => 'gso_purchase_request_types.description',

            3 => 'gso_departmental_requests.id',
            4 => 'gso_departmental_requests.id',
            // 0 => 'gso_departmental_requests.id',
            // 2 => 'gso_departmental_requests.control_no',
            // 3 => 'gso_purchase_request_types.description',
            // 4 => 'acctg_departments.code',
            // 5 => 'hr_employees.fullname',   
            // 6 => 'gso_departmental_requests.remarks',
            // 7 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['draft', 'for-approval'];

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
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->whereNotIn('status', $status)
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
            2 => 'gso_unit_of_measurements.code',
            8 => 'gso_unit_of_measurements.code',
            9 => 'gso_unit_of_measurements.code',
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
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
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
        ->where('gso_departmental_requests.id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
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

    public function reload_uom($item)
    {
        return (new GsoUnitOfMeasurement)->find((new GsoItem)->find($item)->uom_id);
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

    public function generate_control_no($department)
    {   
        $date       = date('Y-m-d'); 
        $year       = date('Y'); 
        $month      = date('m');
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

    public function generateBudgetControlNo($year)
    {   
        $count  = CboAllotmentObligation::where('budget_year', $year)->count();
        $controlNo = $year.'-';
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

    public function findAlobViaPr($id) 
    {
        return CboAllotmentObligation::where('departmental_request_id', $id)->get();
    }

    public function find_obligation($id)
    {
        return CboAllotmentObligationRequest::where('allotment_id', $id)->first();
    }

    public function find_alob($id) 
    {
        return CboAllotmentObligation::findOrFail($id);
    }

    public function get_alob($id) 
    {
        return CboAllotmentObligation::whereId($id)->get();
    }

    public function allob_divisions()
    {
        return (new AcctgDepartmentDivision)->allDivisions();
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allPayees()
    {
        return (new CboPayee)->allPayees();
    }

    public function fetch_payee_details($id, $column)
    {
        return (new CboPayee)->find($id)->$column;
    }

    public function allBudgetYear()
    {
        return (new CboBudget)->allBudgetYear();
    }

    public function view_alob_lines($id, $department, $division, $year, $fund, $category)
    {
        $res = CboBudgetBreakdown::select([
            'cbo_budget_breakdowns.id as id',
            'acctg_account_general_ledgers.id as glAcctId',
            'acctg_account_general_ledgers.code as glAcctCode',
            'acctg_account_general_ledgers.description as glAcctDesc',
            'cbo_budget_breakdowns.annual_budget as totalAmt',
            'cbo_budget_breakdowns.amount_used as totalUsed'
        ])
        ->leftJoin('cbo_budgets', function($join)
        {
            $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_budget_breakdowns.gl_account_id');
        })
        ->whereIn('cbo_budget_breakdowns.gl_account_id',
            GsoDepartmentalRequestItem::select(['gso_items.gl_account_id'])
            ->leftJoin('gso_items', function($join)
            {
                $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
            })
            ->where([
                'gso_departmental_requests_items.departmental_request_id' => $id,
                'gso_departmental_requests_items.is_active' => 1
            ])
            ->get()
        )
        ->where([
            'cbo_budgets.department_id' => $department,
            // 'cbo_budgets.division_id' => $division,
            'cbo_budgets.fund_code_id' => $fund,
            'cbo_budgets.budget_year' => $year,
            'cbo_budgets.is_locked' => 1,
            'cbo_budget_breakdowns.budget_category_id' => $category,
            'cbo_budget_breakdowns.is_active' => 1
        ])
        ->get();

        return $res = $res->map(function($budget) use ($id) {
            $totalAllotment = CboAllotmentBreakdown::where(['budget_breakdown_id' => $budget->id, 'is_active' => 1])->sum('amount');
            $amtUsed = CboAllotmentBreakdown::
            leftJoin('cbo_allotment_obligations', function($join)
            {
                $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
            })
            ->where([
                'cbo_allotment_obligations.departmental_request_id' => $id, 
                'cbo_allotment_breakdowns.budget_breakdown_id' => $budget->id, 
                'cbo_allotment_breakdowns.is_active' => 1
            ])
            ->sum('cbo_allotment_breakdowns.amount');

            $total1 = floatval($budget->totalAmt) - floatval($totalAllotment);
            $total2 = floatval($totalAllotment) - floatval($budget->totalUsed);
            return (object) [
                'id' => $budget->id,
                'gl_id' => $budget->glAcctId,
                'gl_code' => $budget->glAcctCode,
                'gl_desc' => $budget->glAcctDesc,
                'total' => floatval($budget->totalAmt),
                'remaining' => floatval($total1) + floatval($total2),
                'amount' => floatval($amtUsed)
            ];
        });
    }

    public function view_alob_lines2($id, $department, $division, $year, $fund, $category)
    {   
        $res = CboBudgetBreakdown::select([
            'cbo_budget_breakdowns.id as id',
            'acctg_account_general_ledgers.id as glAcctId',
            'acctg_account_general_ledgers.code as glAcctCode',
            'acctg_account_general_ledgers.description as glAcctDesc',
            'cbo_budget_breakdowns.annual_budget as totalAmt',
            'cbo_budget_breakdowns.amount_used as totalUsed'
        ])
        ->leftJoin('cbo_budgets', function($join)
        {
            $join->on('cbo_budgets.id', '=', 'cbo_budget_breakdowns.budget_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_budget_breakdowns.gl_account_id');
        })
        ->where([
            'cbo_budgets.department_id' => $department,
            // 'cbo_budgets.division_id' => $division,
            'cbo_budgets.fund_code_id' => $fund,
            'cbo_budgets.budget_year' => $year,
            'cbo_budgets.is_locked' => 1,
            'cbo_budget_breakdowns.budget_category_id' => $category,
            'cbo_budget_breakdowns.is_active' => 1
        ]);
        $obr = CboAllotmentObligation::find($id);
        if ($obr->obligation_type_id === 4) {
            $res->where('acctg_account_general_ledgers.is_payroll',1);
        }
        $res = $res->get();

        return $res = $res->map(function($budget) use ($id) {
            $totalAllotment = CboAllotmentBreakdown::where(['budget_breakdown_id' => $budget->id, 'is_active' => 1])->sum('amount');
            $amtUsed = CboAllotmentBreakdown::
            leftJoin('cbo_allotment_obligations', function($join)
            {
                $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
            })
            ->where([
                'cbo_allotment_obligations.id' => $id, 
                'cbo_allotment_breakdowns.budget_breakdown_id' => $budget->id, 
                'cbo_allotment_breakdowns.is_active' => 1
            ])
            ->sum('cbo_allotment_breakdowns.amount');

            $total1 = floatval($budget->totalAmt) - floatval($totalAllotment);
            $total2 = floatval($totalAllotment) - floatval($budget->totalUsed);
            return (object) [
                'id' => $budget->id,
                'gl_id' => $budget->glAcctId,
                'gl_code' => $budget->glAcctCode,
                'gl_desc' => $budget->glAcctDesc,
                'total' => floatval($budget->totalAmt),
                'remaining' => floatval($total1) - floatval($total2),
                'amount' => floatval($amtUsed)
            ];
        });
    }

    public function listAlobLines($request, $id)
    {             
        $columns = array( 
            0 => 'cbo_allotment_breakdowns.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_account_general_ledgers.description',
            3 => 'cbo_allotment_breakdowns.amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cbo_allotment_breakdowns.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CboAllotmentBreakdown::select([
            '*',
            'cbo_allotment_breakdowns.id as alobId',
            'cbo_allotment_breakdowns.budget_breakdown_id as budgetId',
            'cbo_allotment_breakdowns.amount as alobAmt',
            'cbo_budget_breakdowns.annual_budget as budgetTotal',
            'cbo_allotment_breakdowns.created_at as alobCreatedAt',
            'cbo_allotment_breakdowns.updated_at as alobUpdatedAt',
            'cbo_allotment_breakdowns.is_active as alobStatus',
            'acctg_account_general_ledgers.code as glCode',
            'acctg_account_general_ledgers.description as glDesc',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_allotment_breakdowns.gl_account_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
        })
        ->leftJoin('cbo_budget_breakdowns', function($join)
        {
            $join->on('cbo_budget_breakdowns.id', '=', 'cbo_allotment_breakdowns.budget_breakdown_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_breakdowns.annual_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_breakdowns.amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'cbo_allotment_obligations.departmental_request_id' => $id,
            'cbo_allotment_breakdowns.is_active' => 1
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listAlobLines2($request, $id)
    {             
        $columns = array( 
            0 => 'cbo_allotment_breakdowns.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_account_general_ledgers.description',
            3 => 'cbo_allotment_breakdowns.amount'
        );
        $start     = $request->get('start');
        $limit     = (int)$request->get('length') > 0 ? $request->get('length') : null;
        // $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cbo_allotment_breakdowns.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CboAllotmentBreakdown::select([
            '*',
            'cbo_allotment_breakdowns.id as alobId',
            'cbo_allotment_breakdowns.budget_breakdown_id as budgetId',
            'cbo_allotment_breakdowns.amount as alobAmt',
            'cbo_budget_breakdowns.annual_budget as budgetTotal',
            'cbo_allotment_breakdowns.created_at as alobCreatedAt',
            'cbo_allotment_breakdowns.updated_at as alobUpdatedAt',
            'cbo_allotment_breakdowns.is_active as alobStatus',
            'acctg_account_general_ledgers.code as glCode',
            'acctg_account_general_ledgers.description as glDesc',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_allotment_breakdowns.gl_account_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
        })
        ->leftJoin('cbo_budget_breakdowns', function($join)
        {
            $join->on('cbo_budget_breakdowns.id', '=', 'cbo_allotment_breakdowns.budget_breakdown_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_budget_breakdowns.annual_budget', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_breakdowns.amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'cbo_allotment_obligations.id' => $id,
            'cbo_allotment_breakdowns.is_active' => 1
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit) {
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $res   = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function validate_budget_breakdown($breakdown, $allocated, $allotmentID)
    {   
        $totalAmt = CboBudgetBreakdown::find($breakdown)->annual_budget;
        $totalUse = CboAllotmentBreakdown::where([
            'budget_breakdown_id' => $breakdown,
            'is_active' => 1
        ])
        ->where('allotment_id', '!=', $allotmentID)
        ->sum('amount');

        $totalAmt -= floatval($totalUse);
        if (floatval($totalAmt) >= floatval($allocated)) {
            return true;
        }
        return false;
    }

    public function update_row($requisitionID, $breakdown, $gl_account, $allocated, $timestamp, $user)
    {
        $allotmentID = CboAllotmentObligation::where('departmental_request_id', $requisitionID)->first()->id;
        $res = CboAllotmentBreakdown::where([
            'allotment_id' => $allotmentID,
            'budget_breakdown_id' => $breakdown,
            'gl_account_id' => $gl_account
        ])
        ->get();
            
        $check = $this->validate_budget_breakdown($breakdown, $allocated, $allotmentID);
        $status = ($allocated > 0 && $check > 0) ? 1 : 0;
        if ($res->count() > 0) {
            $res = $res->first();
            $alllotBrkdwn = CboAllotmentBreakdown::where('id', $res->id)->update([
                'amount' => ($check > 0) ? $allocated : 0,
                'updated_at' => $timestamp,
                'updated_by' => $user,
                'is_active' => $status
            ]);
        } else {
            $alllotBrkdwn = CboAllotmentBreakdown::create([
                'allotment_id' => $allotmentID,
                'budget_breakdown_id' => $breakdown,
                'gl_account_id' => $gl_account,
                'amount' => ($check > 0) ? $allocated : 0,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }
        
        $data['total_amount'] = $this->computeBreakdownTotalAmount($allotmentID);
        $data['updated_by']   = $user;
        $data['updated_at']   = $timestamp;
        $this->updateAllotment($allotmentID, $data);

        $totalAmount = CboBudgetBreakdown::where('id', $breakdown)->first()->annual_budget;
        if ($check > 0) {
            $totalAmtUsed = CboAllotmentBreakdown::where(['budget_breakdown_id' => $breakdown, 'is_active' => 1])->sum('amount');
        } else { 
            $totalAmtUsed = CboAllotmentBreakdown::where('allotment_id', '!=', $allotmentID)->where(['budget_breakdown_id' => $breakdown, 'is_active' => 1])->sum('amount');
        }
        return (object) [
            'remaining' => floatval($totalAmount) - floatval($totalAmtUsed),
            'amount' => (floatval($allocated) > 0 && $check > 0) ? $allocated : '',
            'type' => ($check > 0) ? 'success' : 'failed'
        ];
    }

    public function update_row2($allotmentID, $breakdown, $gl_account, $allocated, $timestamp, $user)
    {
        $res = CboAllotmentBreakdown::where([
            'allotment_id' => $allotmentID,
            'budget_breakdown_id' => $breakdown,
            'gl_account_id' => $gl_account
        ])
        ->get();
            
        $check = $this->validate_budget_breakdown($breakdown, $allocated, $allotmentID);
        $status = ($allocated > 0 && $check > 0) ? 1 : 0;
        if ($res->count() > 0) {
            $res = $res->first();
            $alllotBrkdwn = CboAllotmentBreakdown::where('id', $res->id)->update([
                'amount' => ($check > 0) ? $allocated : 0,
                'updated_at' => $timestamp,
                'updated_by' => $user,
                'is_active' => $status
            ]);
        } else {
            $alllotBrkdwn = CboAllotmentBreakdown::create([
                'allotment_id' => $allotmentID,
                'budget_breakdown_id' => $breakdown,
                'gl_account_id' => $gl_account,
                'amount' => ($check > 0) ? $allocated : 0,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }
        
        $data['total_amount'] = $this->computeBreakdownTotalAmount($allotmentID);
        $data['updated_by']   = $user;
        $data['updated_at']   = $timestamp;
        $this->updateAllotment($allotmentID, $data);

        $totalAmount = CboBudgetBreakdown::where('id', $breakdown)->first()->annual_budget;
        if ($check > 0) {
            $totalAmtUsed = CboAllotmentBreakdown::where(['budget_breakdown_id' => $breakdown, 'is_active' => 1])->sum('amount');
        } else { 
            $totalAmtUsed = CboAllotmentBreakdown::where('allotment_id', '!=', $allotmentID)->where(['budget_breakdown_id' => $breakdown, 'is_active' => 1])->sum('amount');
        }
        return (object) [
            'remaining' => floatval($totalAmount) - floatval($totalAmtUsed),
            'amount' => (floatval($allocated) > 0 && $check > 0) ? $allocated : '',
            'type' => ($check > 0) ? 'success' : 'failed'
        ];
    }

    public function computeBreakdownTotalAmount($allotmentID)
    {
        return CboAllotmentBreakdown::where(['allotment_id' => $allotmentID, 'is_active' => 1])->sum('amount');
    }

    public function updateBreakdownLine($id, array $newDetails)
    {
        return CboAllotmentBreakdown::whereId($id)->update($newDetails);
    }

    public function findBreakdownLine($id) 
    {
        return CboAllotmentBreakdown::findOrFail($id);
    }

    public function updateAllotment($id, array $newDetails) 
    {
        return CboAllotmentObligation::whereId($id)->update($newDetails);
    }

    public function fetchAlobNo($id)
    {   
        $res = CboAllotmentObligation::find($id);
        $series = '';
        $count  = CboAllotmentObligation::where('status', 'completed')->count();
        if($count < 9) {
            $series .= '0000' . ($count + 1);
        } else if($count < 99) {
            $series .= '000' . ($count + 1);
        } else if($count < 999) {
            $series .= '00' . ($count + 1);
        } else if($count < 9999) {
            $series .= '0' . ($count + 1);
        }  else {
            $series .= ($count + 1);
        }
        $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($res->approved_at)) . '-' . date('m', strtotime($res->approved_at)) . '-' . $series;
        $res->alobs_control_no = $alobNo;
        $res->update();
        return $alobNo;
    }

    public function fetchBudgetSeriesNo($id = '', $approvedAt = '')
    {   
        if ($id != '') {
            $res = CboAllotmentObligation::find($id);
            if ($approvedAt !== '') {
                if ($res->budget_no == NULL) {
                    $series = '';
                    $count  = CboAllotmentObligation::where('approved_by', '!=', NULL)->count();

                    if($count < 9) {
                        $series .= '0000' . ($count + 1);
                    } else if($count < 99) {
                        $series .= '000' . ($count + 1);
                    } else if($count < 999) {
                        $series .= '00' . ($count + 1);
                    } else if($count < 9999) {
                        $series .= '0' . ($count + 1);
                    } else {
                        $series .= ($count + 1);
                    }
                    $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($approvedAt)) . '-' . date('m', strtotime($approvedAt)) . '-' . $series;
                    $res->alobs_control_no = $alobNo;
                    $res->budget_no = $series;
                    $res->update();
                    return $alobNo;
                } else {
                    $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($approvedAt)) . '-' . date('m', strtotime($approvedAt)) . '-' . $res->budget_no;
                    $res->alobs_control_no = $alobNo;
                    $res->update();
                    return $alobNo;
                }
            }
            $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($res->approved_at)) . '-' . date('m', strtotime($res->approved_at)) . '-' . $res->budget_no;
            $res->alobs_control_no = $alobNo;
            $res->update();
            return $alobNo;
        } else {
            $series = '';
            $count  = CboAllotmentObligation::where('approved_by', '>', 0)->count();

            if($count < 9) {
                $series .= '0000' . ($count + 1);
            } else if($count < 99) {
                $series .= '000' . ($count + 1);
            } else if($count < 999) {
                $series .= '00' . ($count + 1);
            } else if($count < 9999) {
                $series .= '0' . ($count + 1);
            }  else {
                $series .= ($count + 1);
            }
            return $series;
        }
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

    public function fetch_remarks($id)
    {
        return GsoDepartmentalRequestDisapproval::where('departmental_request_id', $id)->first();
    }

    public function fetch_designation($employee)
    {
        return HrEmployee::find($employee)->hr_designation_id;
    }
}