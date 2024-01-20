<?php

namespace App\Repositories;

use App\Interfaces\GsoIssuanceInterface;
use App\Models\GsoIssuance;
use App\Models\GsoIssuanceDetail;
use App\Models\GsoItem;
use App\Models\User;
use App\Models\GsoPurchaseOrder;
use App\Models\GsoPurchaseOrderPosting;
use App\Models\GsoPurchaseOrderPostingLine;
use App\Models\BacRfqLine;
use App\Models\HrEmployee;
use App\Models\GsoItemHistory;
use App\Models\GsoPropertyAccountability;
use App\Models\GsoPropertyType;
use App\Models\BacRfqSupplierCanvass;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoDepartmentalRequestTrackingStatus;
use DB;

class GsoIssuanceRepository implements GsoIssuanceInterface 
{
    public function find($id) 
    {
        return GsoIssuance::with(['requestor.department.designation', 'issuer'])->findOrFail($id);
    }

    public function create(array $details) 
    {
        return GsoIssuance::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoIssuance::whereId($id)->update($newDetails);
    }

    public function update_line($lineID, array $newDetails) 
    {
        return GsoIssuanceDetail::whereId($lineID)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_issuances.id',
            1 => 'gso_issuances.control_no',
            2 => 'requestor.fullname',
            3 => 'acctg_departments.code',
            4 => 'issuer.fullname',
            5 => 'gso_issuances.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_issuances.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoIssuance::select([
            'gso_issuances.*',
            'gso_issuances.id as identity',
            'gso_issuances.control_no as identityNo',
            'gso_issuances.status as identityStatus',
            'gso_issuances.created_at as identityCreated',
            'gso_issuances.updated_at as identityUpdated',
            'gso_issuances.total_amount as identityTotal'
        ])
        ->leftJoin('hr_employees as requestor', function($join)
        {
            $join->on('requestor.id', '=', 'gso_issuances.requested_by');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'requestor.acctg_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'requestor.acctg_department_division_id');
        })
        ->leftJoin('hr_employees as issuer', function($join)
        {
            $join->on('issuer.id', '=', 'gso_issuances.issued_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuances.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('requestor.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('issuer.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_issuances.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_issuances.id',
            1 => 'gso_issuances.control_no',
            2 => 'requestor.fullname',
            3 => 'acctg_departments.code',
            4 => 'issuer.fullname',
            5 => 'gso_issuances.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_issuances.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoIssuance::select([
            '*',
            'gso_issuances.id as identity',
            'gso_issuances.control_no as identityNo',
            'gso_issuances.status as identityStatus',
            'gso_issuances.created_at as identityCreated',
            'gso_issuances.updated_at as identityUpdated',
            'gso_issuances.total_amount as identityTotal',
            'gso_issuances.approved_by as identityApprovedBy',
            'gso_issuances.disapproved_by as identityDispprovedBy',
        ])
        ->leftJoin('hr_employees as requestor', function($join)
        {
            $join->on('requestor.id', '=', 'gso_issuances.requested_by');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'requestor.acctg_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'requestor.acctg_department_division_id');
        })
        ->leftJoin('hr_employees as issuer', function($join)
        {
            $join->on('issuer.id', '=', 'gso_issuances.issued_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuances.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('requestor.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('issuer.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_issuances.status', '!=', 'draft')
        ->where(['gso_issuances.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function item_listItems($request, $id)
    {
        $columns = array( 
            1 => 'gso_issuances_categories.code',
            2 => 'gso_issuances_types.code',
            3 => 'gso_items.code'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.code' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoIssuanceDetail::select([
            '*', 
            'gso_issuances_details.id as identity',
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            'gso_issuances_details.quantity as itemQuantity',
            'gso_issuances_details.amount as itemCost'
            // DB::raw('SUM(gso_departmental_requests_items.quantity) as itemQuantity'),
            // DB::raw('SUM(gso_departmental_requests_items.amount) as itemTotal')            
        ])
        ->leftJoin('gso_issuances_types', function($join)
        {
            $join->on('gso_issuances_types.id', '=', 'gso_issuances_details.issuance_type_id');
        })
        ->leftJoin('gso_issuances_categories', function($join)
        {
            $join->on('gso_issuances_categories.id', '=', 'gso_issuances_details.category_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_issuances_details.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_issuances_details.uom_id');
        })
        ->leftJoin('gso_issuances', function($join)
        {
            $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
        })
        ->where([
            'gso_issuances.id' => $id,
            'gso_issuances_details.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuances_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuances_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allUsers()
    {
        return User::allUsers();
    }

    public function generate_control_no()
    {
        $count       = GsoIssuance::count();
        $controlNo   = '';

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

    public function view_available_items($issuanceID, $inventory, $poNo)
    {   
        $arr = explode(',', $poNo);
        $types = ['1', '2'];
        if ($inventory > 0) {
            $res = GsoItem::select([
                '*',
                'id as itemId',
                'code as itemCode',
                'name as itemName',
                'description as itemDescription',
                'remarks as itemRemarks',
                'quantity_inventory as itemQuantity',
                DB::raw('CONCAT("") as poNo'),
                'weighted_cost as itemCost'
            ])
            ->whereIn('item_type_id', $types)
            ->where(['is_active' => 1])
            ->where('quantity_inventory', '>', 0)
            ->get();
        } else {
            $res = GsoPurchaseOrderPostingLine::select([
                '*',
                'gso_items.id as itemId',
                'gso_items.code as itemCode',
                'gso_items.name as itemName',
                'gso_items.description as itemDescription',
                'gso_items.remarks as itemRemarks',
                'gso_items.weighted_cost as itemCost',
                DB::raw('SUM(gso_purchase_orders_posting_lines.quantity) as itemQuantity'),
                'gso_purchase_orders.purchase_order_no as poNo',
                'gso_purchase_orders.id as poId',
                'gso_purchase_orders.rfq_id as rfq'
            ])
            ->leftJoin('gso_purchase_orders_posting', function($join)
            {
                $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
            })
            ->leftJoin('gso_purchase_orders', function($join)
            {
                $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
            })
            ->leftJoin('gso_items', function($join)
            {
                $join->on('gso_items.id', '=', 'gso_purchase_orders_posting_lines.item_id');
            })
            ->leftJoin('gso_unit_of_measurements', function($join)
            {
                $join->on('gso_unit_of_measurements.id', '=', 'gso_purchase_orders_posting_lines.uom_id');
            })
            ->whereIn('gso_purchase_orders.id', $arr)
            ->where([
                'gso_purchase_orders_posting_lines.is_active' => 1
            ])
            ->groupBy(['gso_purchase_orders_posting_lines.item_id', 'gso_purchase_orders.id'])
            ->get();
        }

        return $res;
    }

    public function check_quantity_withdrawn($issuanceID, $itemID, $inventory, $poNo = 0)
    {   
        if ($poNo > 0) {
            $res = GsoIssuanceDetail::where([
                'gso_issuances_details.issuance_id' => $issuanceID,
                'gso_issuances_details.item_id' => $itemID,
                'gso_issuances_details.category_id' => ($inventory > 0) ? 2 : 1,
                'gso_issuances.purchase_order_id' => $poNo,
                'gso_issuances_details.is_active' => 1
            ])
            ->leftJoin('gso_issuances', function($join)
            {
                $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
            })
            ->get();
        } else {
            $res = GsoIssuanceDetail::where([
                'gso_issuances_details.issuance_id' => $issuanceID,
                'gso_issuances_details.item_id' => $itemID,
                'gso_issuances_details.category_id' => ($inventory > 0) ? 2 : 1,
                'gso_issuances_details.is_active' => 1
            ])
            ->leftJoin('gso_issuances', function($join)
            {
                $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
            })
            ->get();
        }

        if ($res->count() > 0) {
            return $res->first()->quantity;
        }
        return 0;
    }

    public function check_all_quantity_withdrawn($issuanceID, $itemID, $inventory, $poNo = 0)
    {   
        if ($poNo > 0) {
            $res = GsoIssuanceDetail::
            select([
                DB::raw('SUM(gso_issuances_details.quantity) as quantity'),
            ])
            ->leftJoin('gso_issuances', function($join)
            {
                $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
            })
            ->where('gso_issuances_details.issuance_id', '!=', $issuanceID)
            ->where([
                'gso_issuances.purchase_order_id' => $poNo,
                'gso_issuances_details.item_id' => $itemID,
                'gso_issuances_details.category_id' => ($inventory > 0) ? 2 : 1,
                'gso_issuances_details.is_active' => 1
            ])
            ->get();
        } else {
            $res = GsoIssuanceDetail::
            select([
                DB::raw('SUM(gso_issuances_details.quantity) as quantity'),
            ])
            ->leftJoin('gso_issuances', function($join)
            {
                $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
            })
            ->where('gso_issuances_details.issuance_id', '!=', $issuanceID)
            ->where([
                'gso_issuances_details.item_id' => $itemID,
                'gso_issuances_details.category_id' => ($inventory > 0) ? 2 : 1,
                'gso_issuances_details.is_active' => 1
            ])
            ->get();
        }

        if ($res->count() > 0) {
            return $res->first()->quantity;
        }
        return 0;
    }

    public function get_pr_no_via_rfq($rfq)
    {
        $result = BacRfqLine::select([
            'gso_purchase_requests.purchase_request_no as prNo'
        ])
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->where([
            'bac_rfqs_lines.rfq_id' => $rfq,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->get();

        $arr = array();
        foreach ($result as $res) {
            $arr[] = $res->prNo;
        }

        if (count($arr) > 1) {
            return implode(', ', $arr);
        } else {
            return implode('', $arr);
        }
    }

    public function allPrPo()
    {
        $res = GsoPurchaseOrderPostingLine::select([
            'bac_rfqs.id as rfq', 
            'gso_purchase_orders.id as poID', 
            'gso_purchase_orders.purchase_order_no as poNo'
        ])
        ->leftJoin('gso_purchase_orders_posting','gso_purchase_orders_posting.id','=','gso_purchase_orders_posting_lines.posting_id')
        ->leftJoin('gso_purchase_orders','gso_purchase_orders.id','=','gso_purchase_orders_posting.purchase_order_id')
        ->leftJoin('bac_rfqs','bac_rfqs.id','=','gso_purchase_orders.rfq_id')
        ->where('gso_purchase_orders_posting_lines.quantity','!=',DB::raw('gso_purchase_orders_posting_lines.quantity_withdrawn'))
        ->groupBy('gso_purchase_orders.id')
        ->get();

        $pr_po = array();
        foreach ($res as $po) {
            $pr_po[] = array(
                $po->poID => $po->poNo . ' (' . $this->get_pr_no_via_rfq($po->rfq) . ')'
            );
        }

        $pr_pos = array();
        foreach($pr_po as $po) {
            foreach($po as $key => $val) {
                $pr_pos[$key] = $val;
            }
        }

        return $pr_pos;
    }

    public function post($request, $issuanceID, $inventory, $timestamp, $user)
    {   
        $category = ($inventory > 0) ? 2 : 1;
        foreach ($request->issuances as $issue) {
            $item = GsoItem::find($issue['id']);
            if ($item->item_type_id == 1) {
                $type = 1;
            } else {
                if (floatval($issue['amt']) > floatval(18000)) {
                    $type = 3;
                } else {
                    $type = 2;
                }
            }
            
            $res = GsoIssuanceDetail::where([
                'issuance_id' => $issuanceID,
                'category_id' => $category,
                'item_id' => $issue['id'],
                'uom_id' => $issue['uom'] 
            ])
            ->get();

            if (floatval($issue['qty']) > 0) {
                if ($res->count() > 0) {
                    $issuance = $res->first();
                    GsoIssuanceDetail::whereId($issuance->id)->update([
                        'category_id' => $category,
                        'quantity' => $issue['qty'],
                        'amount' => $issue['amt'],
                        'updated_at' => $timestamp,
                        'updated_by' => $user,
                        'is_active' => 1
                    ]);
                } else {
                    GsoIssuanceDetail::create([
                        'issuance_id' => $issuanceID,
                        'category_id' => $category,
                        'issuance_type_id' => $type,
                        'item_id' => $issue['id'],
                        'uom_id' => $issue['uom'],
                        'quantity' => $issue['qty'],
                        'amount' => $issue['amt'],
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            } else {
                if ($res->count() > 0) {
                    $issuance = $res->first();
                    GsoIssuanceDetail::whereId($issuance->id)->update([
                        'category_id' => $category,
                        'quantity' => 0,
                        'amount' => 0,
                        'updated_at' => $timestamp,
                        'updated_by' => $user,
                        'is_active' => 0
                    ]);
                }
            }
        }

        GsoIssuance::whereId($issuanceID)->update([
            'total_amount' => $this->getTotalAmount($issuanceID),
            'updated_at' => $timestamp,
            'updated_by' => $user
        ]);

        return true;
    }

    public function getTotalAmount($issuanceID)
    {
        $res = GsoIssuanceDetail::select([
            DB::raw('(SUM(quantity) * SUM(amount)) as totalAmt')
        ])
        ->where([
            'issuance_id' => $issuanceID,
            'is_active' => 1
        ])
        ->groupBy(['item_id', 'category_id'])
        ->get();

        $totalAmt = 0;
        foreach ($res as $result) {
            $totalAmt += floatval($result->totalAmt);
        }

        return $totalAmt;
    }

    public function validate_issuance($issuanceID)
    {
        $res = GsoIssuanceDetail::select([
            'gso_issuances_details.category_id as itemCategory',
            DB::raw('SUM(gso_issuances_details.quantity) as itemQuantity'),
            'gso_items.quantity_inventory as inventory',
            'gso_items.quantity_reserved as reserved'
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_issuances_details.item_id');
        })
        ->where([
            'gso_issuances_details.issuance_id' => $issuanceID,
            'gso_issuances_details.is_active' => 1
        ])
        ->groupBy(['gso_issuances_details.item_id', 'gso_issuances_details.category_id'])
        ->get();

        $validated = 1;
        foreach ($res as $result) {
            if ($result->itemCategory == 1) {
                if (floatval($result->reserved) < floatval($result->itemQuantity)) {
                    $validated = 0;
                } 
            } else {
                if (floatval($result->inventory) < floatval($result->itemQuantity)) {
                    $validated = 0;
                } 
            }
        }
        return $validated;
    }

    public function getItemCost($rfqID, $itemID) 
    {   
        $res = BacRfqSupplierCanvass::select('unit_cost')
        ->leftJoin('bac_rfqs_suppliers', function($join)
        {
            $join->on('bac_rfqs_suppliers.supplier_id', '=', 'bac_rfqs_suppliers_canvass.supplier_id');
        })
        ->where([
            'bac_rfqs_suppliers.rfq_id' => $rfqID,
            'bac_rfqs_suppliers_canvass.item_id' => $itemID,
            'bac_rfqs_suppliers.is_active' => 1,
            'bac_rfqs_suppliers.is_selected' => 1
        ])
        ->get();

        if ($res->count() > 0) {
            return $res->first()->unit_cost;
        }
        return floatval(0);
    }

    public function credit_inventory($issuanceID, $timestamp, $user)
    {   
        $res = GsoIssuanceDetail::select([
            'gso_issuances_details.category_id as itemCategory',
            'gso_issuances_details.issuance_type_id as itemType',
            DB::raw('SUM(gso_issuances_details.quantity) as itemQuantity'),
            'gso_items.quantity_inventory as inventory',
            'gso_items.quantity_reserved as reserved',
            'gso_issuances_details.item_id as itemId',
            'gso_issuances.control_no as identityControl',
            'gso_issuances.issued_by as identityIssuer',
            'gso_issuances.received_by as identityReceiver',
            'gso_issuances.received_date as identityReceiverDate',
            'gso_issuances.remarks as identityRemarks',
            'gso_purchase_orders.rfq_id as identityRFQ',
            'gso_purchase_orders.id as poID'
        ])
        ->leftJoin('gso_issuances', function($join)
        {
            $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_issuances.purchase_order_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_issuances_details.item_id');
        })
        ->where([
            'gso_issuances_details.issuance_id' => $issuanceID,
            'gso_issuances_details.is_active' => 1
        ])
        ->groupBy(['gso_issuances_details.item_id', 'gso_issuances_details.category_id'])
        ->get();

        $validated = 1;
        foreach ($res as $result) {
            $withdraw = GsoItem::find($result->itemId);
            $basedQty = ($result->itemCategory == 1) ? $withdraw->quantity_reserved : $withdraw->quantity_inventory;
            $postedQty = $result->itemQuantity;
            $balancedQty = floatval($basedQty) - floatval($postedQty);

            if ($result->itemCategory == 1) {
                $withdraw->quantity_reserved = floatval($withdraw->quantity_reserved) - floatval($result->itemQuantity);
                $withdraw->updated_at = $timestamp;
                $withdraw->updated_by = $user;
                $withdraw->update();
            } else {
                $withdraw->quantity_inventory = floatval($withdraw->quantity_inventory) - floatval($result->itemQuantity);
                $withdraw->updated_at = $timestamp;
                $withdraw->updated_by = $user;
                $withdraw->update();                
            }
            
            $history = GsoItemHistory::create([
                'item_id' => $result->itemId,
                'trans_type' => 'Issuance',
                'trans_class' => NULL,
                'trans_datetime' => $timestamp,
                'remarks' => $result->identityControl,
                'trans_by' => $result->identityIssuer,
                'rcv_by' => $result->identityReceiver,
                'based_from' => ($result->itemCategory == 1) ? 'Reserved' : 'Inventory',
                'based_qty' => $basedQty,
                'posted_qty' => $postedQty,
                'balanced_qty' => $balancedQty,
                'created_at' => $timestamp,
                'created_by' => $user,
            ]);

            if ($result->itemType > 1) {
                $item = GsoItem::find($result->itemId);
                $accountability = GsoPropertyAccountability::create([
                    'property_type_id' => 1,
                    'property_no' => $this->generate_propertyNo(),
                    'issuance_id' => $issuanceID,
                    'issued_by' => $result->identityIssuer,
                    'received_by' => $result->identityReceiver,
                    'received_date' => $result->identityReceiverDate,
                    'gl_account_id' => $item->gl_account_id,
                    'item_type_id' => $result->itemType,
                    'item_id' => $item->id,
                    'uom_id' => $item->uom_id,
                    'quantity' => $postedQty,
                    'unit_cost' => $this->getItemCost($result->identityRFQ, $item->id),
                    'estimated_life_span' => $item->life_span,
                    'remarks' => $result->identityRemarks,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }

            /**
             * update issuance withdrawn
             * in gso_purchase_orders_posting_lines
             */
            if ($result->poID > 0) {
                $this->post_quantity_withdrawn($result->poID, $result->itemId, $postedQty);
            }
        }

        return true;
    }

    public function update_request($poID)
    {
        $res1 = GsoPurchaseOrderPostingLine::select([
            'gso_purchase_orders_posting_lines.id as identity',
            'gso_purchase_orders_posting_lines.quantity as identityQuantity',
            'gso_purchase_orders_posting_lines.quantity_withdrawn as identityWithdrawn'
        ])        
        ->leftJoin('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where([
            'gso_purchase_orders.id' => $poID,
            'gso_purchase_orders_posting_lines.is_active' => 1
        ])
        ->get();

        $counter = 0;
        if ($res1->count() > 0) {
            foreach ($res1 as $r1) {
                if (floatval($r1->identityQuantity) != floatval($r1->identityWithdrawn)) {
                    $counter++;
                }
            }
        }

        $res2 = BacRfqLine::select([
            '*',
            'gso_departmental_requests.id as identity'
        ])
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->where([
            'gso_purchase_orders.id' => $poID,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->get();

        $timestamp = date('Y-m-d H:i:s');
        if ($res2->count() > 0) {
            foreach ($res2 as $r2) {
                GsoDepartmentalRequisition::whereId($r2->identity)->update([
                    'status' => ($counter > 0) ? 'partial' : 'completed',
                    'updated_at' => $timestamp,
                    'updated_by' => \Auth::user()->id
                ]);
                GsoDepartmentalRequestItem::where([
                    'departmental_request_id' => $r2->identity, 
                    'is_active' => 1
                ])->update([
                    'status' => ($counter > 0) ? 'partial' : 'completed',
                    'updated_at' => $timestamp,
                    'updated_by' => \Auth::user()->id
                ]);
                $this->track_dept_request($r2->identity);
            }
        }

        return true;
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

    public function post_quantity_withdrawn($poID, $itemID, $quantity)
    {
        $res = GsoPurchaseOrderPostingLine::select([
            'gso_purchase_orders_posting_lines.id as identity',
            'gso_purchase_orders_posting_lines.quantity as identityQuantity',
            'gso_purchase_orders_posting_lines.quantity_withdrawn as identityWithdrawn'
        ])        
        ->leftJoin('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where('gso_purchase_orders_posting_lines.quantity','!=',DB::raw('gso_purchase_orders_posting_lines.quantity_withdrawn'))
        ->where([
            'gso_purchase_orders_posting_lines.item_id' => $itemID,
            'gso_purchase_orders.id' => $poID,
            'gso_purchase_orders_posting_lines.is_active' => 1
        ])
        ->get();

        if ($res->count() > 0) {
            foreach ($res as $r) {
                $withdrawn = (floatval($r->identityWithdrawn) > 0) ? floatval($r->identityWithdrawn) : 0;
                if ($quantity > 0) {
                    $forPosting = floatval($r->identityQuantity) - floatval($withdrawn);
                    if (floatval($forPosting) > floatval($quantity)) { 
                        $posted = floatval($withdrawn) + floatval($quantity);
                        GsoPurchaseOrderPostingLine::whereId($r->identity)->update(['quantity_withdrawn' => $posted]);
                        $quantity -= floatval($quantity);
                    } else {
                        $posted = floatval($withdrawn) + floatval($forPosting);
                        GsoPurchaseOrderPostingLine::whereId($r->identity)->update(['quantity_withdrawn' => $posted]);
                        $quantity -= floatval($forPosting);
                    }
                }
            }
        } 

        $this->update_request($poID);
        return true;
    }
    
    public function generate_propertyNo()
    {
        $year        = date('Y'); 
        $count       = GsoPropertyAccountability::whereYear('created_at', '=', $year)->count();
        $propertyNo  = '';
        $propertyNo .= $year . '-';

        if($count < 9) {
            $propertyNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $propertyNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $propertyNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $propertyNo .= '0' . ($count + 1);
        } else {
            $propertyNo .= ($count + 1);
        }
        return $propertyNo;
    }

    public function posted_items_via_control_no($controlNo, $type)
    {
        $res = GsoIssuanceDetail::select([
            '*',
            'gso_issuances_details.category_id as itemCategory',
            DB::raw('SUM(gso_issuances_details.quantity) as itemQuantity'),
            'gso_issuances_details.amount as itemCost'
        ])
        ->leftJoin('gso_issuances', function($join)
        {
            $join->on('gso_issuances.id', '=', 'gso_issuances_details.issuance_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_issuances_details.item_id');
        })
        ->where([
            'gso_issuances.control_no' => $controlNo,
            'gso_issuances_details.issuance_type_id' => $type,
            'gso_issuances.status' => 'issued',
            'gso_issuances_details.is_active' => 1
        ])
        ->groupBy(['gso_issuances_details.item_id', 'gso_issuances_details.category_id'])
        ->get();

        return $res;
    }

    public function find_issuance($controlNo)
    {
        $res = GsoIssuance::select(['*'])
        ->where([
            'gso_issuances.control_no' => $controlNo,
            'gso_issuances.is_active' => 1
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

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function validate_par($issuanceID)
    {
        $res = GsoIssuanceDetail::select(['gso_issuances_types.is_property_asset as propertyAsset', 'gso_issuances_details.quantity as issueQty'])
        ->leftJoin('gso_issuances_types', function($join)
        {
            $join->on('gso_issuances_types.id', '=', 'gso_issuances_details.issuance_type_id');
        })
        ->where([
            'gso_issuances_details.issuance_id' => $issuanceID,
            // 'gso_issuances_types.is_property_asset' => 1,
            'gso_issuances_details.is_active' => 1
        ])
        // ->where('gso_issuances_details.quantity', '>', 1)
        ->get();

        $validated = 0;
        if ($res->count() > 0) {
            foreach ($res as $r) {
                if ((floatval($r->propertyAsset) > 0) && (floatval($r->issueQty) > 1)) {
                    $validated = 1;
                    break; break;
                }
            }
        }

        return $validated;
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

}