<?php

namespace App\Repositories;

use App\Interfaces\GsoPreRepairInspectionInterface;
use App\Models\GsoPreRepairInspectionRequest;
use App\Models\GsoPreRepairInspectionItem;
use App\Models\GsoPropertyAccountability;
use App\Models\GsoPreRepairInspectionHistory;
use App\Models\HrEmployee;
use App\Models\UserAccessApprovalApprover;
use App\Models\User;
use App\Models\GsoItem;
use App\Models\GsoUnitOfMeasurement;
use DB;

class GsoPreRepairInspectionRepository implements GsoPreRepairInspectionInterface 
{
    public function find($id) 
    {
        return GsoPreRepairInspectionRequest::findOrFail($id);
    }

    public function findItem($id) 
    {
        return GsoPreRepairInspectionItem::findOrFail($id);
    }

    public function get($id) 
    {
        return GsoPreRepairInspectionRequest::whereId($id)->get();
    }

    public function getItem($id) 
    {
        return GsoPreRepairInspectionItem::whereId($id)->get();
    }

    public function create(array $details) 
    {
        return GsoPreRepairInspectionRequest::create($details);
    }

    public function createItem(array $details) 
    {
        return GsoPreRepairInspectionItem::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoPreRepairInspectionRequest::whereId($id)->update($newDetails);
    }

    public function updateItem($id, array $newDetails) 
    {
        return GsoPreRepairInspectionItem::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_pre_repair_inspection_requests.id',
            1 => 'gso_pre_repair_inspection_requests.repair_no',
            2 => 'gso_property_accountabilities.fixed_asset_no',
            3 => 'hr_employees.fullname',
            4 => 'gso_pre_repair_inspection_requests.requested_date',
            5 => 'gso_pre_repair_inspection_requests.issues'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPreRepairInspectionRequest::select([
            'gso_pre_repair_inspection_requests.*'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_pre_repair_inspection_requests.requested_by');
        })
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'gso_pre_repair_inspection_requests.property_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_pre_repair_inspection_requests.repair_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.issues', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function inpsection_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_pre_repair_inspection_requests.id',
            1 => 'gso_pre_repair_inspection_requests.repair_no',
            2 => 'gso_property_accountabilities.fixed_asset_no',
            3 => 'hr_employees.fullname',
            4 => 'gso_pre_repair_inspection_requests.requested_date',
            5 => 'gso_pre_repair_inspection_requests.issues'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['draft', 'for approval'];

        $res = GsoPreRepairInspectionRequest::select([
            'gso_pre_repair_inspection_requests.*'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_pre_repair_inspection_requests.requested_by');
        })
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'gso_pre_repair_inspection_requests.property_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_pre_repair_inspection_requests.repair_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.issues', 'like', '%' . $keywords . '%');
            }
        })
        ->whereNotIn('gso_pre_repair_inspection_requests.status', $status)
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
                    $query .= '(';
                    $query .= 'gso_pre_repair_inspection_requests.approved_counter >= 1';
                } else {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 1';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status != "draft")';
                $q++;
            }
            if ($r->second > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 2';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 2';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status != "draft")';
                $q++;
            }
            if ($r->third > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 3';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 3';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status != "draft")';
                $q++;
            }
            if ($r->fourth > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 4';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 4';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status != "draft")';
                $q++;
            }           
            $iteration++;
        }

        $columns = array( 
            0 => 'gso_pre_repair_inspection_requests.id',
            1 => 'gso_pre_repair_inspection_requests.repair_no',
            2 => 'gso_property_accountabilities.fixed_asset_no',
            3 => 'hr_employees.fullname',
            4 => 'gso_pre_repair_inspection_requests.requested_date',
            5 => 'gso_pre_repair_inspection_requests.issues'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPreRepairInspectionRequest::select([
            'gso_pre_repair_inspection_requests.*'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_pre_repair_inspection_requests.requested_by');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_employees.acctg_department_id');
        })
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'gso_pre_repair_inspection_requests.property_id');
        })
        ->whereRaw('('.$query.')')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_pre_repair_inspection_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.repair_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.issues', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $res   = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approvals2_listItems($request, $type, $slugs, $user)
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

        $query = ''; $q = 0; $iteration = 0; $restrictions = "'".implode( "','", ['draft', 'for approval', 'requested'] )."'"; 
        foreach ($res as $r) {
            if ($r->first > 0) {
                if ($q <= 0) {
                    $query .= '(';
                    $query .= 'gso_pre_repair_inspection_requests.approved_counter >= 1';
                } else {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 1';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status NOT IN('.$restrictions.'))';
                $q++;
            }
            if ($r->second > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 2';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 2';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status NOT IN('.$restrictions.'))';
                $q++;
            }
            if ($r->third > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 3';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 3';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status NOT IN('.$restrictions.'))';
                $q++;
            }
            if ($r->fourth > 0) {
                if ($q > 0) {
                    $query .= ' OR (gso_pre_repair_inspection_requests.approved_counter >= 4';
                } else {
                    $query .= '(gso_pre_repair_inspection_requests.approved_counter >= 4';
                }
                $query .= ' AND acctg_departments.id = '.$r->department_id.' AND gso_pre_repair_inspection_requests.status NOT IN('.$restrictions.'))';
                $q++;
            }           
            $iteration++;
        }

        $columns = array( 
            0 => 'gso_pre_repair_inspection_requests.id',
            1 => 'gso_pre_repair_inspection_requests.repair_no',
            2 => 'gso_property_accountabilities.fixed_asset_no',
            3 => 'hr_employees.fullname',
            4 => 'gso_pre_repair_inspection_requests.requested_date',
            5 => 'gso_pre_repair_inspection_requests.issues'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPreRepairInspectionRequest::select([
            'gso_pre_repair_inspection_requests.*'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_pre_repair_inspection_requests.requested_by');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_employees.acctg_department_id');
        })
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'gso_pre_repair_inspection_requests.property_id');
        })
        ->whereRaw('('.$query.')')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_pre_repair_inspection_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.repair_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_requests.issues', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $res   = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function history_listItems($request, $id, $fixedAsset = 0)
    {   
        $columns = array( 
            0 => 'gso_pre_repair_inspection_history.id',
            1 => 'gso_pre_repair_inspection_history.requested_date',
            2 => 'gso_pre_repair_inspection_history.concerns',
            3 => 'gso_pre_repair_inspection_history.remarks',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_history.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPreRepairInspectionHistory::select([
            'gso_pre_repair_inspection_history.*'
        ])
        ->leftJoin('gso_pre_repair_inspection_requests', function($join)
        {
            $join->on('gso_pre_repair_inspection_requests.id', '=', 'gso_pre_repair_inspection_history.repair_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_pre_repair_inspection_history.requested_date', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_history.completion_date', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_history.concerns', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_history.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_pre_repair_inspection_history.property_id', '=', $fixedAsset)
        ->where('gso_pre_repair_inspection_history.repair_id', '!=', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function item_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'gso_pre_repair_inspection_items.id',
            1 => 'gso_items.code',
            2 => 'gso_pre_repair_inspection_items.remarks',
            3 => 'gso_pre_repair_inspection_items.quantity',
            4 => 'gso_unit_of_measurements.code',
            5 => 'gso_pre_repair_inspection_items.amount',
            6 => 'gso_pre_repair_inspection_items.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_pre_repair_inspection_history.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPreRepairInspectionItem::select([
            'gso_pre_repair_inspection_items.*'
        ])
        ->leftJoin('gso_pre_repair_inspection_requests', function($join)
        {
            $join->on('gso_pre_repair_inspection_requests.id', '=', 'gso_pre_repair_inspection_items.repair_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_pre_repair_inspection_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_pre_repair_inspection_items.uom_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.codex', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_items.quantity', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_items.amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_items.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_pre_repair_inspection_items.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_pre_repair_inspection_items.repair_id' => $id, 'gso_pre_repair_inspection_items.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allFixedAssets()
    {
        return (new GsoPropertyAccountability)->allFixedAssets();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = GsoPreRepairInspectionRequest::where('repair_no', '!=', NULL)->whereYear('created_at', '=', $year)->count();
        $controlNo  = 'REP'.substr($year, -2).'-';

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

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }

    public function allItems()
    {
        return (new GsoItem)->allItems();
    }

    public function allUOMs()
    {
        return (new GsoUnitOfMeasurement)->allUOMs();
    }

    public function getItems($itemID)
    {
        return (new GsoItem)->whereId($itemID)->get();
    }

    public function validate($repairID)
    {
        return GsoPreRepairInspectionItem::where(['repair_id' => $repairID, 'is_active' => 1])->get();
    }
}