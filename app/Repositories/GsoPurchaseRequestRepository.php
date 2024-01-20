<?php

namespace App\Repositories;

use App\Interfaces\GsoPurchaseRequestInterface;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseRequest;
use App\Models\GsoPurchaseRequestLine;
use App\Models\GsoPurchaseRequestType;
use App\Models\GsoPurchaseType;
use App\Models\GsoItem;
use App\Models\GsoUnitOfMeasurement;
use App\Models\CboAllotmentObligation;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\User;
use App\Models\GsoDepartmentalRequestDisapproval;
use App\Models\CboAllotmentObligationRequest;
use App\Models\UserAccessApprovalApprover;
use DB;

class GsoPurchaseRequestRepository implements GsoPurchaseRequestInterface 
{
    public function getAll() 
    {
        return GsoPurchaseRequest::all();
    }

    public function find($id) 
    {
        return GsoPurchaseRequest::findOrFail($id);
    }

    public function find_via_pr($requisitionID)
    {
        return GsoPurchaseRequest::with(['allotment'])->where('departmental_request_id', $requisitionID)->get();
    }

    public function find_pr_via_alob($allotmentID)
    {
        return GsoPurchaseRequest::where('allotment_id', $allotmentID)->get();
    }

    public function create($request, $allotmentID, $user, $timestamp) 
    {   
        $alob = CboAllotmentObligation::find($allotmentID);
        if ($alob->departmental_request_id > 0) {
            $departmental = GsoDepartmentalRequisition::find($alob->departmental_request_id);
            $updateItems = GsoDepartmentalRequestItem::where('departmental_request_id', $alob->departmental_request_id)->update(['quantity_pr' => DB::raw('quantity_requested')]);
            $details = array(
                'departmental_request_id' => $alob->departmental_request_id,
                'allotment_id' => $allotmentID,
                'prepared_date' =>  date('Y-m-d', strtotime($timestamp)),
                'prepared_by' => (new User)->find($user)->name,
                'remarks' => $departmental->remarks,
                'created_at' => $timestamp,
                'created_by' => $user
            );        
            return GsoPurchaseRequest::create($details);
        } else {
            $details = array(
                'departmental_request_id' => NULL,
                'allotment_id' => $allotmentID,
                'prepared_date' =>  date('Y-m-d', strtotime($timestamp)),
                'prepared_by' => (new User)->find($user)->name,
                'created_at' => $timestamp,
                'created_by' => $user
            );        
            return GsoPurchaseRequest::create($details);
        }
    }

    public function update($requisitionID, $newDetails, $allotmentID = 0) 
    {   
        if ($allotmentID > 0) {
            return GsoPurchaseRequest::where('allotment_id', $allotmentID)->update($newDetails);
        } else {
            return GsoPurchaseRequest::where('departmental_request_id', $requisitionID)->update($newDetails);
        }
    }

    public function update_alob($allotmentID, array $newDetails)
    {
        return CboAllotmentObligation::whereId($allotmentID)->update($newDetails);
    }

    public function printdata($prNo) 
    { 
        $res = GsoDepartmentalRequestItem::select('*')
            ->leftJoin('gso_departmental_requests', function($join)
            {
                $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
            })
            ->leftJoin('gso_purchase_requests', function($join)
            {
                $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
            })
        
            ->where('gso_purchase_requests.purchase_request_no', $prNo);
            // ->where('gso_departmental_request_items.is_active', 1);
            $res   = $res->get();
        return $res;
  
    }

    public function printsingledata($prNo) 
    { 
        $res = GsoDepartmentalRequestItem::select('*','acctg_departments.name as dept_name')
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('gso_departmental_requests.department_id', '=', 'acctg_departments.id');
        })
            ->where('gso_purchase_requests.purchase_request_no', $prNo);
        
            // ->where('gso_departmental_request_items.is_active', 1);
            $result = $res->first();
        
    return $result;

    }

    public function update_pr_via_alob($request, $allotmentID, $user, $timestamp)
    {   
        $details = array(
            'remarks' => urldecode($request->get('remarks')),
            'updated_at' => $timestamp,
            'updated_by' => $user
        );        
        return GsoPurchaseRequest::where('allotment_id', $allotmentID)->update($details);
    }

    public function listItems($request)
    {             
        $columns = array( 
            0 => 'cbo_allotment_obligations.id',
            1 => 'cbo_allotment_obligations.alobs_control_no',
            2 => 'gso_purchase_requests.purchase_request_no',
            3 => 'cbo_obligation_types.name',
            4 => 'acctg_departments.code',
            5 => 'hr_employees.fullname'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['completed'];

        $res = CboAllotmentObligationRequest::select([
            'cbo_allotment_obligations_requests.*',
            'cbo_allotment_obligations.departmental_request_id as departmental_id',
            'cbo_allotment_obligations.id as obligId',
            'cbo_allotment_obligations.department_id as obligDepartment',
            'cbo_allotment_obligations.created_at as obligCreatedAt',
            'cbo_allotment_obligations.updated_at as obligUpdatedAt',
            'cbo_allotment_obligations.status as obligStatus',
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_obligations_requests.allotment_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'cbo_allotment_obligations.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'cbo_allotment_obligations.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cbo_allotment_obligations.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'cbo_allotment_obligations.division_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->leftJoin('cbo_obligation_types', function($join)
        {
            $join->on('cbo_obligation_types.id', '=', 'cbo_allotment_obligations.obligation_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cbo_allotment_obligations.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests.purchase_request_no', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_obligations.alobs_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cbo_allotment_obligations.with_pr', 1)
        ->where('cbo_allotment_obligations.status', 'completed')
        ->where('cbo_allotment_obligations_requests.status', 'completed')
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsOLD($request)
    {             
        $columns = array( 
            0 => 'gso_departmental_requests.id',
            1 => 'gso_departmental_requests.control_no',
            2 => 'gso_purchase_requests.purchase_request_no',
            3 => 'gso_purchase_request_types.description',
            4 => 'acctg_departments.code',
            5 => 'hr_employees.fullname',   
            6 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['draft', 'for approval', 'requested', 'for alob approval'];

        $res = GsoDepartmentalRequisition::select([
            '*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_departmental_requests.status as prStatus',
            'gso_purchase_requests.purchase_request_no as prNo'
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
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
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
        ->whereNotIn('gso_departmental_requests.status', $status)
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

    public function listItemLines2($request, $allotmentID)
    {             
        $columns = array( 
            0 => 'gso_purchase_requests_lines.id',
            1 => 'gso_items.code',
            2 => 'gso_unit_of_measurements.code',
            8 => 'gso_unit_of_measurements.code',
            9 => 'gso_unit_of_measurements.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_requests_lines.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPurchaseRequestLine::select([
            'gso_purchase_requests_lines.*'
        ])
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'gso_purchase_requests_lines.purchase_request_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_purchase_requests_lines.uom_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_requests_lines.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests_lines.quantity_pr', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests_lines.request_unit_price', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests_lines.request_total_price', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests_lines.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_requests_lines.item_description', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_purchase_requests.allotment_id' => $allotmentID, 'gso_purchase_requests_lines.is_active' => 1])
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
            $query .= 'gso_departmental_requests.status NOT IN ("draft") AND (';
            foreach ($res as $r) {
                if ($r->first > 0) {
                    if ($q <= 0) {
                        $query .= '(gso_departmental_requests.approved_counter >= 1';
                    } else {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 1';
                    }
                    $query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 2';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 2';
                    }
                    $query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 3';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 3';
                    }
                    $query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $query .= ' OR (gso_departmental_requests.approved_counter >= 4';
                    } else {
                        $query .= '(gso_departmental_requests.approved_counter >= 4';
                    }
                    $query .= ' AND cbo_allotment_obligations.department_id = '.$r->department_id.')';
                    $q++;
                }            
                $iteration++;
            }
            $query .= ')';
        } else {
            $query .= 'gso_departmental_requests.status NOT IN ("draft")';
        }

        $columns = array( 
            0 => 'gso_departmental_requests.id',
            1 => 'gso_departmental_requests.control_no',
            2 => 'gso_purchase_requests.purchase_request_no',
            3 => 'cbo_obligation_types.name',
            4 => 'acctg_departments.code',
            5 => 'hr_employees.fullname',   
            6 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPurchaseRequest::select([
            '*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_purchase_requests.status as prStatus',
            'gso_purchase_requests.purchase_request_no as prNo',
            'gso_purchase_requests.approved_by as prApprovedBy',
            'gso_purchase_requests.approved_at as prApprovedAt',
            'gso_purchase_requests.approved_counter as prSequence',
            'gso_purchase_requests.disapproved_by as prDisapprovedBy',
            'gso_purchase_requests.disapproved_at as prDisapprovedAt',
        ])
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'gso_purchase_requests.allotment_id');
        })
        ->leftJoin('cbo_obligation_types', function($join)
        {
            $join->on('cbo_obligation_types.id', '=', 'cbo_allotment_obligations.obligation_type_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'cbo_allotment_obligations.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'cbo_allotment_obligations.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cbo_allotment_obligations.department_id');
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
        ->where(function($queue) use ($keywords) {
            if (!empty($keywords)) {
                $queue->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
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

    public function fetchPurchaseRequestNo()
    {   
        $count  = GsoPurchaseRequest::where('approved_by', '>', 0)->count();
        $series = '';

        if($count < 9) {
            $series .= '00000' . ($count + 1);
        } else if($count < 99) {
            $series .= '0000' . ($count + 1);
        } else if($count < 999) {
            $series .= '000' . ($count + 1);
        } else if($count < 9999) {
            $series .= '00' . ($count + 1);
        } else if($count < 99999) {
            $series .= '0' . ($count + 1);
        } else {
            $series .= ($count + 1);
        }
        return $series;
    }

    public function updateRequest($id, array $newDetails) 
    {
        return GsoDepartmentalRequisition::whereId($id)->update($newDetails);
    }

    public function update_item_line($requestItemID, $column, $data, $user, $timestamp)
    {
        $details = array(
            $column => $data,
            'updated_at' => $timestamp,
            'updated_by' => $user
        );

        return GsoDepartmentalRequestItem::whereId($requestItemID)->update($details);
    }

    public function validate_pr($allotmentID)
    {
        $res = GsoPurchaseRequest::where(['allotment_id' => $allotmentID])->get();
        if (!($res->count() > 0)) {
            return 1;
        } 
        if(!(strlen($res->first()->remarks)) > 0) {
            return 2;
        }
        return 0;
    }

    public function disapprove_request(array $details) 
    {   
        return GsoDepartmentalRequestDisapproval::create($details);
    }

    public function find_via_column($column, $value)
    {
        return GsoPurchaseRequest::select(['*'])->where($column, $value)->get();
    }

    public function item_list_via_pr_num($prNum)
    {
        $res = GsoDepartmentalRequestItem::select(['*'])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->where([
            'gso_purchase_requests.purchase_request_no' => $prNum,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->get();

        return $res;
    }

    public function numberTowords(float $amount)
    {   
        $number = floatval($amount);
        $no = floor($number);
        $fraction = $number - $no;
        $hundred = null;
        $digits_1 = strlen($no); //to find lenght of the number
        $i = 0;
        // Numbers can stored in array format
        $str = array();

        $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');

        $digits = array('', 'Hundred', 'Thousand', 'Million', 'Billion');
        //Extract last digit of number and print corresponding number in words till num becomes 0
        while ($i < $digits_1)
        {
        $divider = ($i == 2) ? 10 : 100;
        //Round numbers down to the nearest integer
        $number =floor($no % $divider);
        $no = floor($no / $divider);
        $i +=($divider == 10) ? 1 : 2;

        if ($number)
        {
        $plural = (($counter = count($str)) && $number > 9) ? '' : null;
        $hundred = ($counter == 1 && $str[0]) ? '' : null;
        $str [] = ($number < 21) ? $words[$number] . " " .
        $digits[$counter] .
        $plural . " " .
        $hundred: $words[floor($number / 10) * 10]. " " .
        $words[$number % 10] . " ".
        $digits[$counter] . $plural . " " .
        $hundred;
        }
        else $str[] = null;
        }

        $str = array_reverse($str);
        $result = implode('', $str); //Join array elements with a string
        if (($fraction) > 0) {
            return trim($result).' and '. (number_format($fraction,2) * 100) .'/100'.' pesos';
        }
        return $result.'pesos';
    }

    public function allUOMs()
    {
        return (new GsoUnitOfMeasurement)->allUOMs();
    }

    public function create_pr_line(array $details) 
    {
        return GsoPurchaseRequestLine::create($details);
    }

    public function modify_pr_line($id, array $newDetails) 
    {
        return GsoPurchaseRequestLine::whereId($id)->update($newDetails);
    }

    public function find_pr_line($id) 
    {
        return GsoPurchaseRequestLine::whereId($id)->get();
    }

    public function fetch_amount($allotmentID)
    {
        $res = GsoPurchaseRequestLine::
        leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'gso_purchase_requests_lines.purchase_request_id');
        })
        ->where(['gso_purchase_requests.allotment_id' => $allotmentID, 'gso_purchase_requests_lines.is_active' => 1])
        ->sum('gso_purchase_requests_lines.request_total_price');

        return $res;
    }

    public function updatePrLines($allotmentID, array $newDetails)
    {
        return GsoPurchaseRequestLine::leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'gso_purchase_requests_lines.purchase_request_id');
        })
        ->where([
            'gso_purchase_requests.allotment_id' => $allotmentID,
            'gso_purchase_requests_lines.is_active' => 1
        ])
        ->update([          
            'gso_purchase_requests_lines.status' => $newDetails['status'],      
            'gso_purchase_requests_lines.updated_at' => $newDetails['updated_at'],     
            'gso_purchase_requests_lines.updated_by' => $newDetails['updated_by']
        ]);
    }
}