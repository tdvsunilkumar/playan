<?php

namespace App\Repositories;

use App\Interfaces\GsoInventoryInterface;
use App\Models\GsoItem;
use App\Models\GsoItemHistory;
use App\Models\GsoItemAdjustment;
use App\Models\GsoItemAdjustmentType;
use App\Models\HrEmployee;
use App\Models\User;

class GsoInventoryRepository implements GsoInventoryInterface 
{
    public function find($id) 
    {
        return GsoItem::findOrFail($id);
    }
    
    public function create(array $details) 
    {
        return GsoItem::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoItem::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'gso_item_types.code',
            3 => 'gso_item_categories.code',
            4 => 'gso_items.code',   
            5 => 'gso_items.name',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
            '*',
            'gso_items.id as identity',
            'gso_items.code as identityCode',
            'gso_items.name as identityName',
            'gso_items.description as identityDesc',
            'gso_items.created_at as identityCreatedAt',
            'gso_items.updated_at as identityUpdatedAt',                 
            'gso_items.weighted_cost as identityWeightedCost',
            'gso_items.latest_cost as identityLatestCost',
            'gso_items.latest_cost_date as identityLatestCostDate',       
            'gso_items.is_active as identityStatus',
        ])
        ->with([
            'gl_account' =>  function($q) { 
                $q->select([
                    'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
            },
            'type' =>  function($q) { 
                $q->select([
                    'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
            },
            'category' =>  function($q) { 
                $q->select([
                    'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
            },
            'uom' =>  function($q) { 
                $q->select([
                    'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
            },
            'pur_type' =>  function($q) { 
                $q->select([
                    'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
            }
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
        })
        ->leftJoin('gso_item_types', function($join)
        {
            $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
        })
        ->leftJoin('gso_item_categories', function($join)
        {
            $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);

        $column = ($sortBy == '') ? 'gso_items.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;
    }

    public function history_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'gso_item_history.trans_type',
            1 => 'gso_item_history.trans_datetime',
            2 => 'issuer.fullname',
            3 => 'receiver.fullname',
            4 => 'gso_item_history.based_from'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_item_history.trans_datetime' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItemHistory::select([
            '*',
            'gso_item_history.id as identity',
        ])
        ->leftJoin('hr_employees as issuer', function($join)
        {
            $join->on('issuer.id', '=', 'gso_item_history.trans_by');
        })
        ->leftJoin('hr_employees as receiver', function($join)
        {
            $join->on('receiver.id', '=', 'gso_item_history.rcv_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_item_history.trans_type', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.based_from', 'like', '%' . $keywords . '%')
                ->orWhere('issuer.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('receiver.fullname', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_item_history.item_id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);

        $column = ($sortBy == '') ? 'gso_items.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;
    }

    public function allAdjustmentTypes()
    {
        return (new GsoItemAdjustmentType)->allAdjustmentTypes();
    }

    public function generateNo()
    {   
        $code  = 'ADJ-';
        $count = GsoItemAdjustment::count();

        if($count < 9) {
            $code .= '0000' . ($count + 1);
        } else if($count < 99) {
            $code .= '000' . ($count + 1);
        } else if($count < 999) {
            $code .= '00' . ($count + 1);
        } else if($count < 9999) {
            $code .= '0' . ($count + 1);
        } else {
            $code .= ($count + 1);
        }
        return $code;
    }

    public function create_request(array $details)
    {   
        $res = GsoItemAdjustment::create($details);
        if (isset($details['approved_by'])) {
            $user = User::find($details['created_by']);
            $item = GsoItem::find($details['item_id']);
            $basedQty = $item->quantity_inventory;
            if ($details['adjustment_type_id'] == 1) {
                $balancedQty = floatval($basedQty) + floatval($details['quantity']);
            } else {
                $balancedQty = floatval($basedQty) - floatval($details['quantity']);
            }
            $item->quantity_inventory = $balancedQty;
            $item->updated_at = $details['created_at'];
            $item->updated_by = $details['created_by'];
            $item->update();
            
            $history = GsoItemHistory::create([
                'item_id' => $details['item_id'],
                'trans_type' => 'Adjustment',
                'trans_class' => ($details['adjustment_type_id'] == 1) ? 'Additional Inventory' : 'Deduction Inventory',
                'trans_datetime' => $details['created_at'],
                'remarks' => $res->control_no,
                'trans_by' => $user->hr_employee->id,
                'rcv_by' => NULL,
                'based_from' => 'Inventory',
                'based_qty' => $basedQty,
                'posted_qty' => $details['quantity'],
                'balanced_qty' => $balancedQty,
                'created_at' => $details['created_at'],
                'created_by' => $details['created_by'],
            ]);
        }
        return $res;
    }

    public function update_request($adjustmentID, array $details)
    {
        if (isset($details['approved_by'])) {
            $res = GsoItemAdjustment::find($adjustmentID);
            $user = User::find($res->created_by);
            $item = GsoItem::find($res->item_id);
            $basedQty = $item->quantity_inventory;
            if ($res->adjustment_type_id == 1) {
                $balancedQty = floatval($basedQty) + floatval($res->quantity);
            } else {
                $balancedQty = floatval($basedQty) - floatval($res->quantity);
            }
            $item->quantity_inventory = $balancedQty;
            $item->updated_at = $details['approved_at'];
            $item->updated_by = $details['approved_by'];
            $item->update();
            
            $history = GsoItemHistory::create([
                'item_id' => $res->item_id,
                'trans_type' => 'Adjustment',
                'trans_class' => ($res->adjustment_type_id == 1) ? 'Additional Inventory' : 'Deduction Inventory',
                'trans_datetime' => $details['approved_at'],
                'remarks' => $res->control_no,
                'trans_by' => $user->hr_employee->id,
                'rcv_by' => NULL,
                'based_from' => 'Inventory',
                'based_qty' => $basedQty,
                'posted_qty' => $res->quantity,
                'balanced_qty' => $balancedQty,
                'created_at' => $details['approved_at'],
                'created_by' => $details['approved_by'],
            ]);

            GsoItemAdjustment::whereId($adjustmentID)->update($details);
        }
        return true;
    }

    public function disapprove_request($adjustmentID, array $details)
    {
        return GsoItemAdjustment::whereId($adjustmentID)->update($details);
    }

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_item_adjustments.id',
            1 => 'gso_item_adjustments.control_no',
            2 => 'requestor.fullname',
            3 => 'acctg_departments.code',
            4 => 'gso_items.code'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_item_adjustments.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItemAdjustment::select([
            '*',
            'gso_item_adjustments.id as identity',
            'gso_item_adjustments.control_no as identityNo',
            'gso_item_adjustments.status as identityStatus',
            'gso_item_adjustments.sent_at as identitySent',
            'gso_item_adjustments.disapproved_by as identityDisapprovedBy',
            'gso_item_adjustments.disapproved_at as identityDisapprovedBy',
            'gso_item_adjustments.approved_by as identityApprovedBy',
            'gso_item_adjustments.approved_at as identityApprovedAt',
            'gso_item_adjustments.updated_at as identityUpdatedAt',
            'gso_item_adjustments.created_at as identityCreatedAt'
        ])
        ->leftJoin('gso_item_adjustments_types', function($join)
        {
            $join->on('gso_item_adjustments_types.id', '=', 'gso_item_adjustments.adjustment_type_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_item_adjustments.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
        })
        ->leftJoin('hr_employees as requestor', function($join)
        {
            $join->on('requestor.user_id', '=', 'gso_item_adjustments.sent_by');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'requestor.acctg_department_id');
        })
        ->leftJoin('hr_employees as approver', function($join)
        {
            $join->on('approver.user_id', '=', 'gso_item_adjustments.approved_by');
        })
        ->leftJoin('hr_employees as disapprover', function($join)
        {
            $join->on('disapprover.user_id', '=', 'gso_item_adjustments.disapproved_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_item_adjustments_types.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_adjustments_types.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_adjustments_types.quantity', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('approver.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('disapprover.fullname', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);

        $column = ($sortBy == '') ? 'gso_items.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;
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

    public function find_adjustment($adjustmentID)
    {
        return GsoItemAdjustment::find($adjustmentID);
    }
}