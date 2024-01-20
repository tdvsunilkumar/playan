<?php

namespace App\Repositories;

use App\Interfaces\GsoPurchaseOrderInterface;
use App\Models\BacRfq;
use App\Models\BacResolution;
use App\Models\BacRfqLine;
use App\Models\BacProcurementMode;
use App\Models\BacRfqSupplierCanvass;
use App\Models\GsoItem;
use App\Models\GsoPurchaseRequest;
use App\Models\GsoSupplier;
use App\Models\GsoDeliveryTerm;
use App\Models\GsoPaymentTerm;
use App\Models\GsoPurchaseOrderType;
use App\Models\GsoPurchaseOrder;
use App\Models\GsoPurchaseOrderPosting;
use App\Models\GsoPurchaseOrderPostingLine;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoDepartmentalRequestDisapproval;
use App\Models\GsoDepartmentalRequestTrackingStatus;
use App\Models\BacRfqSupplier;
use App\Models\CboBudgetBreakdown;
use App\Models\User;
use App\Models\HrEmployee;
use App\Models\AcctgAccountPayable;
use App\Models\GsoItemWeighted;
use App\Models\RptLocality;
use App\Models\GsoItemHistory;
use App\Models\CboPayee;
use DB;

class GsoPurchaseOrderRepository implements GsoPurchaseOrderInterface 
{
    public function find($id) 
    {
        return GsoPurchaseOrder::with(['supplier', 'rfq'])->findOrFail($id);
    }

    public function find_po($id) 
    {
        return GsoPurchaseOrder::with(['supplier', 'rfq'])->where('id', $id)->get();
    }

    public function find_inspect($poID)
    {
        return GsoPurchaseOrderPosting::where('purchase_order_id', $poID)->first();
    }

    public function create(array $details) 
    {
        return GsoPurchaseOrder::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoPurchaseOrder::whereId($id)->update($newDetails);
    }

    public function posting_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_purchase_orders.id',
            1 => 'gso_purchase_orders.purchase_order_no',
            2 => 'gso_purchase_order_types.name',
            3 => 'gso_suppliers.business_name',
            4 => 'bac_rfqs.project_name',
            5 => 'gso_purchase_orders.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_orders.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPurchaseOrder::select([
            'gso_purchase_orders.*',
            'gso_purchase_orders.id as identity',
            'gso_purchase_orders.posting_status as identityStatus',
            'gso_purchase_orders.created_at as identityCreated',
            'gso_purchase_orders.updated_at as identityUpdated',
            'gso_purchase_orders.total_amount as identityTotal'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'gso_purchase_orders.rfq_id');
        })
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'gso_purchase_orders.supplier_id');
        })
        ->leftJoin('gso_purchase_order_types', function($join)
        {
            $join->on('gso_purchase_order_types.id', '=', 'gso_purchase_orders.purchase_order_type_id');
        })
        ->leftJoin('gso_payment_terms', function($join)
        {
            $join->on('gso_payment_terms.id', '=', 'gso_purchase_orders.payment_term_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_orders.purchase_order_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_order_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_payment_terms.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_purchase_orders.status' => 'completed', 'gso_purchase_orders.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_purchase_orders.id',
            1 => 'gso_purchase_orders.purchase_order_no',
            2 => 'bac_rfqs.control_no',
            3 => 'gso_purchase_order_types.name',
            4 => 'gso_suppliers.business_name',
            5 => 'bac_rfqs.project_name',
            6 => 'gso_purchase_orders.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_orders.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPurchaseOrder::select([
            'gso_purchase_orders.*',
            'gso_purchase_orders.id as identity',
            'gso_purchase_orders.status as identityStatus',
            'gso_purchase_orders.created_at as identityCreated',
            'gso_purchase_orders.updated_at as identityUpdated',
            'gso_purchase_orders.total_amount as identityTotal'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'gso_purchase_orders.rfq_id');
        })
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'gso_purchase_orders.supplier_id');
        })
        ->leftJoin('gso_purchase_order_types', function($join)
        {
            $join->on('gso_purchase_order_types.id', '=', 'gso_purchase_orders.purchase_order_type_id');
        })
        ->leftJoin('gso_payment_terms', function($join)
        {
            $join->on('gso_payment_terms.id', '=', 'gso_purchase_orders.payment_term_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_orders.purchase_order_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_order_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_payment_terms.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_purchase_orders.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function pr_listItems($request, $id)
    {
        $columns = array( 
            0 => 'gso_purchase_requests.purchase_request_no',
            1 => 'acctg_departments.code',
            2 => 'bac_rfqs_lines.rfq_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_requests.purchase_request_no' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfqLine::select([
            'gso_purchase_requests.purchase_request_no as pr_no',
            DB::raw("CONCAT(acctg_departments.code,' - ',acctg_departments.name, ' [' ,acctg_departments_divisions.code, ']') as agency"),
            'cbo_allotment_obligations.alobs_control_no as alob_no'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->where([
            'gso_purchase_orders.id' => $id,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_requests.purchase_request_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_allotment_obligations.budget_no', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function item_listItems($request, $id)
    {
        $columns = array( 
            1 => 'gso_items.code',
            2 => 'gso_items.name'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.code' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'bac_rfqs.id as rfq', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_po) as itemQuantity'),
            DB::raw('SUM(gso_departmental_requests_items.quantity_posted) as itemPosted')            
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->where([
            'gso_purchase_orders.id' => $id,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%');
            }
        })
        ->groupBy('gso_items.id')
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function item_list_via_po_num($poNum)
    {
        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'bac_rfqs.id as rfq', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_po) as itemQuantity'),
            DB::raw('SUM(gso_departmental_requests_items.quantity_posted) as itemPosted')            
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
        ->orderBy('gso_items.code', 'asc')
        ->get();

        return $res;
    }

    public function posted_items_via_po_num($poNum, $sequence = '')
    {
        $res = GsoPurchaseOrderPostingLine::select([
            '*',
            DB::raw('SUM(gso_purchase_orders_posting_lines.quantity) as itemQuantity'),
        ]) 
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_purchase_orders_posting_lines.item_id');
        })      
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_purchase_orders_posting_lines.uom_id');
        })
        ->leftJoin('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        }) 
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'gso_purchase_orders_posting.is_active' => 1
        ]);
        if ($sequence != '') {
            $res->where('gso_purchase_orders_posting.sequence_no', $sequence);
        }
        return $res->groupBy('gso_items.id')->get();
    }

    public function posted_listItems($request, $id)
    {
        $columns = array( 
            0 => 'gso_purchase_orders_posting.sequence_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_orders_posting.sequence_no' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPurchaseOrderPosting::with(['purchased', 'inspector', 'receiver'])
        ->select([
            '*'         
        ])
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->leftJoin('hr_employees as usez', function($join)
        {
            $join->on('usez.id', '=', 'gso_purchase_orders_posting.inspected_by');
        })
        ->leftJoin('hr_employees as usex', function($join)
        {
            $join->on('usex.id', '=', 'gso_purchase_orders_posting.received_by');
        })
        ->where([
            'gso_purchase_orders.id' => $id,
            'gso_purchase_orders_posting.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_orders_posting.sequence_no', 'like', '%' . $keywords . '%')
                ->orWhere('usez.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('usex.fullname', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function getItemCost($rfqID, $itemID) 
    {   
        $res = BacRfqSupplierCanvass::select('unit_cost')
        ->leftJoin('bac_rfqs_suppliers', function($join)
        {
            $join->on('bac_rfqs_suppliers.rfq_id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
        })
        ->whereRaw('bac_rfqs_suppliers_canvass.supplier_id = bac_rfqs_suppliers.supplier_id 
            AND bac_rfqs_suppliers.rfq_id = '.$rfqID.' 
            and bac_rfqs_suppliers_canvass.item_id = '.$itemID.' 
            and bac_rfqs_suppliers.is_selected = 1')
        ->get();

        if ($res->count() > 0) {
            return $res->first()->unit_cost;
        }
        return floatval(0);
    }

    public function all_available_rfq($id)
    {   
        $res = BacResolution::select('bac_rfqs.id', 'bac_rfqs.control_no')
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_resolution.rfq_id');
        })
        ->where('bac_resolution.status','=','completed')
        ->where(function ($query) use ($id) {
            $query->whereNotIn('bac_resolution.rfq_id',(function ($query) use ($id) {
                    $query->from('gso_purchase_orders')
                        ->select('rfq_id')
                        ->whereNotNull('rfq_id')
                        ->where('id','!=', $id);
                })
            );
        })
        ->get();


        // $res = BacResolution::select('bac_rfqs.id', 'bac_rfqs.control_no')
        // ->leftJoin('bac_rfqs', function($join)
        // {
        //     $join->on('bac_rfqs.id', '=', 'bac_resolution.rfq_id');
        // })
        // ->where('bac_resolution.status','=','completed')
        // ->where(function ($query) use ($id) {
        //     $query->whereIn('rfq_id',(function ($query) use ($id) {
        //         $query->from('gso_purchase_orders')
        //             ->select('rfq_id')
        //             ->where('id', '=', $id);
        //         })
        //     );
        //     // $query->where('is_attached', '=', 0)
        //     //     ->orWhereIn('rfq_id',(function ($query) use ($id) {
        //     //         $query->from('gso_purchase_orders')
        //     //             ->select('rfq_id')
        //     //             ->where('id', '=', $id);
        //     //     })
        //     // );
        // })
        // ->get();

        return $res;
    }

    public function allPoTypes()
    {
        return (new GsoPurchaseOrderType)->allPoTypes();
    }

    public function allProcurementModes()
    {
        return (new BacProcurementMode)->allProcurementModes();
    }

    public function allPaymentTerms()
    {
        return (new GsoPaymentTerm)->allPaymentTerms();
    }

    public function allDeliveryTerms()
    {
        return (new GsoDeliveryTerm)->allDeliveryTerms();
    }

    public function getSupplier($rfqID)
    {   
        if ($rfqID > 0) {
            $res = BacRfqSupplier::where([
                'rfq_id' => $rfqID,
                'is_selected' => 1
            ])
            ->first();

            return $res->supplier_id;
        }
        return NULL;
    }

    public function getLocalAddress()
    {
        $res = RptLocality::where(['loc_local_name' => 'Palayan City', 'department' => 4])->get();
        return ($res->count() > 0) ? $res->first()->loc_address : '';
    }

    public function generate_po_no()
    {
        $year        = date('Y'); 
        $month       = date('m'); 
        $monthPrefix = (strlen($month) > 1) ? $month : '0' . $month;
        $count       = GsoPurchaseOrder::where('purchase_order_no', '!=', NULL)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
        $poNum       = '';
        $poNum      .= substr($year, -2) . '' . $monthPrefix . '-';

        if($count < 9) {
            $poNum .= '0000' . ($count + 1);
        } else if($count < 99) {
            $poNum .= '000' . ($count + 1);
        } else if($count < 999) {
            $poNum .= '00' . ($count + 1);
        } else if($count < 9999) {
            $poNum .= '0' . ($count + 1);
        } else {
            $poNum .= ($count + 1);
        }
        return $poNum;
    }

    public function updatePoQuantity($rfqID) 
    {   
        if ($rfqID > 0) {
            $updateItems = GsoDepartmentalRequestItem::whereIn('departmental_request_id', 
                GsoPurchaseRequest::select('departmental_request_id')
                ->whereIn('id',
                    BacRfqLine::select('purchase_request_id')
                    ->where([
                        'rfq_id' => $rfqID,
                        'is_active' => 1
                    ])
                    ->get()
                )
                ->get()            
            )->update(['quantity_po' => DB::raw('quantity_pr')]);
        }
        return true;
    }

    public function update_request($poID, array $newDetails)
    {
        $res = BacRfqLine::select([
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

        if ($res->count() > 0) {
            foreach ($res as $result) {
                GsoDepartmentalRequisition::whereId($result->identity)->update($newDetails);
                GsoDepartmentalRequestItem::where(['departmental_request_id' => $result->identity, 'is_active' => 1])->update($newDetails);
                $this->track_dept_request($result->identity);
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

    public function approval_listItems($request, $user = '')
    {   
        $columns = array( 
            0 => 'gso_purchase_orders.id',
            1 => 'gso_purchase_orders.purchase_order_no',
            2 => 'gso_purchase_order_types.name',
            3 => 'gso_suppliers.business_name',
            4 => 'bac_rfqs.project_name',
            5 => 'gso_purchase_orders.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_orders.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $empID     = HrEmployee::select('id')->where(['user_id' => $user])->first()->id;

        $res = GsoPurchaseOrder::select([
            '*',
            'gso_purchase_orders.id as identity',
            'gso_purchase_orders.status as identityStatus',
            'gso_purchase_orders.created_at as identityCreated',
            'gso_purchase_orders.updated_at as identityUpdated',
            'gso_purchase_orders.total_amount as identityTotal',
            'gso_purchase_orders.approved_by as identityApprovedBy'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'gso_purchase_orders.rfq_id');
        })
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'gso_purchase_orders.supplier_id');
        })
        ->leftJoin('gso_purchase_order_types', function($join)
        {
            $join->on('gso_purchase_order_types.id', '=', 'gso_purchase_orders.purchase_order_type_id');
        })
        ->leftJoin('gso_payment_terms', function($join)
        {
            $join->on('gso_payment_terms.id', '=', 'gso_purchase_orders.payment_term_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_orders.purchase_order_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_order_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_payment_terms.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_purchase_orders.status', '!=', 'draft')
        ->where(['gso_purchase_orders.is_active' => 1]);
        if ($user != '') {
            $res->where('gso_purchase_orders.funding_by', $empID)
            ->orWhere('gso_purchase_orders.approval_by', $empID);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
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

    public function computeTotalAmount($requisitionId)
    {
        return DB::select(DB::raw('SELECT SUM((CASE WHEN purchase_total_price > 0 THEN purchase_total_price ELSE request_total_price END)) as totalAmt FROM  gso_departmental_requests_items WHERE is_active = 1 AND departmental_request_id='. $requisitionId .''))[0]->totalAmt;
    }

    public function update_srp($poID)
    {
        $res = BacRfqLine::select([
            '*',
            'gso_departmental_requests.id as identity',
            'gso_purchase_orders.rfq_id as identityRFQ'
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

        if ($res->count() > 0) {
            foreach ($res as $result) {                
                $res2 = GsoDepartmentalRequestItem::where(['departmental_request_id' => $result->identity, 'is_active' => 1])->get();
                foreach ($res2 as $result2) {
                    $unit_cost = $this->getItemCost($result->identityRFQ, $result2->item_id);
                    $total_cost = floatval(floatval($result2->quantity_po) * floatval($unit_cost));
                    $details = array(
                        'purchase_unit_price' => $unit_cost,
                        'purchase_total_price' => $total_cost
                    );
                    GsoDepartmentalRequestItem::whereId($result2->id)->update($details);
                }                   
                $details2 = array(
                    'total_amount' => $this->computeTotalAmount($result->identity)
                );
                GsoDepartmentalRequisition::whereId($result->identity)->update($details2);
            }
        }
    }

    public function disapprove_request($poID, array $details) 
    {   
        $res = BacRfqLine::select([
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
        ->groupBy('gso_departmental_requests.id')
        ->get();

        if ($res->count() > 0) {
            foreach ($res as $result) {
                $details['departmental_request_id'] = $result->identity;
                GsoDepartmentalRequestDisapproval::create($details);
            }
        }

        return true;
    }

    public function view_available_posting($poID)
    {
        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'bac_rfqs.id as rfq', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_po) as itemQuantity'),
            DB::raw('SUM(gso_departmental_requests_items.quantity_posted) as itemPosted')            
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->where('gso_departmental_requests_items.status', '!=', 'posted')
        ->where([
            'gso_purchase_orders.id' => $poID,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
        ->get();

        return $res;
    }

    public function findItemDescription($poID, $itemID)
    {
        $res = GsoDepartmentalRequestItem::select([
            '*'     
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->where([
            'gso_departmental_requests_items.item_id' => $itemID,
            'gso_purchase_orders.id' => $poID,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
        ->first();

        if ($res->pr_remarks != NULL) {
            $description = $res->item->code .' - ' . $res->item->name . ' (' . $res->pr_remarks . ')';
        } else if ($res->itemRemarks != NULL) {
            $description = $res->item->code .' - ' . $res->item->name . ' (' . $res->itemRemarks . ')';
        } else { 
            $description = $res->item->code .' - ' . $res->item->name; 
        } 

        return $description;
    }

    public function generate_sequence_no($poID)
    {   
        // $res = GsoPurchaseOrder::find($poID);
        // $count = GsoPurchaseOrderPosting::where('purchase_order_id', $poID)->count();
        // $count += 1;
        // if ($count > 9) {
        //     return $count;
        // }
        // return '0' . $count;
        $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');
        $count = GsoPurchaseOrderPosting::whereYear('created_at', '=',  $year)->count();
        $series = substr($year, -2).''.$month.'-';

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
        return $series;
    }

    public function get_responsibility_center($rfqID, $itemID)
    {
        $res = GsoDepartmentalRequestItem::select(['acctg_departments.code as depCode', 'acctg_departments_divisions.code as divCode'])
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->where([
            'gso_departmental_requests_items.item_id' => $itemID,
            'bac_rfqs_lines.rfq_id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1,
        ])
        ->get();
        
        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $r) {
                $center = $r->depCode . '' . $r->divCode;
                if (!in_array($center, $arr)) {
                    $arr[] = $center;
                }
            }
            if (count($arr) > 1) {
                return implode(', ', $arr);
            } else {
                return implode('', $arr);
            }
        }
        return $arr = '';
    }
    
    public function get_fund_code($rfqID)
    {
        return BacRfq::find($rfqID)->fund_code_id;
    }

    function truncate_number($number, $decimals = 0)
    {
        $negation = ($number < 0) ? (-1) : 1;
        $coefficient = 10 ** $decimals;
        return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
    }

    public function create_posting($request, $poID, $timestamp, $user)
    {   
        $purchased = GsoPurchaseOrder::find($poID);
        $items = array(); 
        foreach ($request->posted as $post) {
            $item = GsoItem::find($post['id']);
            $items[] = $post['qty'].''.$post['uom'].' '.$item->code .' - ' . $item->name;

            /** 
             * put amount used in budget
             */
            $amount = floatval($this->getItemCost($purchased->rfq_id, $post['id']));
            $budgets = CboBudgetBreakdown::select(['cbo_budget_breakdowns.*'])
            ->leftJoin('cbo_allotment_breakdowns', function($join)
            {
                $join->on('cbo_allotment_breakdowns.budget_breakdown_id', '=', 'cbo_budget_breakdowns.id');
            })
            ->leftJoin('cbo_allotment_obligations', function($join)
            {
                $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
            })
            ->leftJoin('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.allotment_id', '=', 'cbo_allotment_obligations.id');
            })
            ->leftJoin('bac_rfqs_lines', function($join)
            {
                $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
            })
            ->leftJoin('gso_purchase_orders', function($join)
            {
                $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs_lines.rfq_id');
            })
            ->where([
                'cbo_budget_breakdowns.gl_account_id' => $item->gl_account_id,
                'gso_purchase_orders.id' => $poID,
                'cbo_budget_breakdowns.is_active' => 1
            ])
            ->get();

            if ($budgets->count() > 0) {
                $budgets = CboBudgetBreakdown::find($budgets->first()->id);
                $budgets['amount_used'] = floatval($budgets['amount_used']) + floatval(floatval($amount) * floatval($post['qty']));
                $budgets->update();
            }
        }

        $sequence = $this->generate_sequence_no($poID);
        $details = array(
            'purchase_order_id' => $poID,
            'sequence_no' => $sequence,
            'reference_no' => urldecode($request->get('reference_no')),
            'reference_date' => date('Y-m-d', strtotime($request->get('reference_date'))),
            'inspected_by' => $request->get('inspected_by'),
            'inspected_by_designation' => $request->get('inspected_by') ? $this->fetch_designation($request->get('inspected_by')) : NULL,
            'inspected_date' => date('Y-m-d', strtotime($request->get('inspected_date'))),
            'received_by' => $request->get('received_by'),
            'received_by_designation' => $request->get('received_by') ? $this->fetch_designation($request->get('received_by')) : NULL,
            'received_date' => date('Y-m-d', strtotime($request->get('received_date'))),
            'remarks' => urldecode($request->get('remarks')),
            'posted_items' => implode(', ', $items),
            'is_inventory_posting' => $request->get('inventory'),
            'created_at' => $timestamp,
            'created_by' => $user
        );
        $posting = GsoPurchaseOrderPosting::create($details);
        $due_date = date_add($timestamp, date_interval_create_from_date_string("".$purchased->payment_term->code." days"));
        foreach ($request->posted as $post) {
            $details2 = array(
                'posting_id' => $posting->id,
                'item_id' => $post['id'],
                'uom_id' => $post['uom_id'],
                'quantity' => $post['qty'],
                'created_at' => $timestamp,
                'created_by' => $user
            );
            $posting_line = GsoPurchaseOrderPostingLine::create($details2);

            /** 
             * put data on item history
             */
            if ($request->get('inventory') > 0) {
                $item = GsoItem::find($post['id']);
                $basedQty = $item->quantity_inventory;
                $postedQty = $post['qty'];
                $balancedQty = floatval($basedQty) + floatval($postedQty);
                $item->quantity_inventory = $balancedQty;
                $item->update();
            } else {
                $item = GsoItem::find($post['id']);
                $basedQty = $item->quantity_reserved;
                $postedQty = $post['qty'];
                $balancedQty = floatval($basedQty) + floatval($postedQty);
                $item->quantity_reserved = $balancedQty;
                $item->update();
            }
            $transBy = User::find($user)->hr_employee->id;
            $history = GsoItemHistory::create([
                'item_id' => $post['id'],
                'trans_type' => 'Posting',
                'trans_class' => NULL,
                'trans_datetime' => $timestamp,
                'remarks' => $sequence,
                'trans_by' => $transBy,
                'rcv_by' => $request->get('received_by'),
                'based_from' => ($request->get('inventory') > 0) ? 'Inventory' : 'Reserved',
                'based_qty' => $basedQty,
                'posted_qty' => $postedQty,
                'balanced_qty' => $balancedQty,
                'created_at' => $timestamp,
                'created_by' => $user,
            ]);            

            $item = GsoItem::find($post['id']);
            $amount = floatval($this->getItemCost($purchased->rfq_id, $post['id']));
            /** 
             * this is weighted computation
             * store weighted and latest cost
             */
            $frequency = floatval(GsoItemWeighted::where(['item_id' => $item->id])->count()) + floatval(1);
            $sumAmt    = floatval(GsoItemWeighted::where(['item_id' => $item->id])->sum('latest_cost')) + floatval($amount);
            $weighted_cost = floatval($sumAmt) / floatval($frequency); 
            $weightedDetails = array(
                'posting_line_id' => $posting_line->id,
                'item_id' => $item->id,
                'weighted_cost' => $this->truncate_number($weighted_cost, 5),
                'weighted_cost_date' => date('Y-m-d'),
                'latest_cost' => $amount,
                'latest_cost_date' => date('Y-m-d'),  
                'created_at' => $timestamp,
                'created_by' => $user
            );
            GsoItemWeighted::create($weightedDetails);
            $itemDetails = array(
                'weighted_cost' => $this->truncate_number($weighted_cost, 5),
                'latest_cost' => $amount,
                'latest_cost_date' => date('Y-m-d')
            );
            GsoItem::whereId($item->id)->update($itemDetails);

            $result = GsoDepartmentalRequestItem::select([
                DB::raw("CONCAT(acctg_departments.code,'',acctg_departments_divisions.code) as centre"),
                'gso_departmental_requests_items.quantity_po as quantity_po',
                'gso_departmental_requests_items.quantity_posted as quantity_posted',
                'gso_departmental_requests_items.id as identity'
            ])
            ->leftJoin('gso_items', function($join)
            {
                $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
            })
            ->leftJoin('gso_departmental_requests', function($join)
            {
                $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
            })
            ->leftJoin('acctg_departments', function($join)
            {
                $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
            })
            ->leftJoin('acctg_departments_divisions', function($join)
            {
                $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
            })
            ->leftJoin('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
            })
            ->leftJoin('bac_rfqs_lines', function($join)
            {
                $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
            })
            ->leftJoin('bac_rfqs', function($join)
            {
                $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
            })
            ->leftJoin('gso_purchase_orders', function($join)
            {
                $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
            })
            ->where('gso_departmental_requests_items.status', '!=', 'posted')
            ->where([
                'gso_items.id' => $post['id'],
                'gso_purchase_orders.id' => $poID,
                'bac_rfqs_lines.is_active' => 1,
                'gso_departmental_requests_items.is_active' => 1
            ])
            ->get();            

            $quantity = $post['qty'];
            foreach ($result as $res) {  
                if ($quantity > 0) {
                    $forPosting = floatval($res->quantity_po) - floatval($res->quantity_posted);
                    if (floatval($forPosting) > floatval($quantity)) { 
                        $posted = floatval($res->quantity_posted) + floatval($quantity);
                        if (floatval($posted) == floatval($res->quantity_po)) {
                            GsoDepartmentalRequestItem::whereId($res->identity)->update(['quantity_posted' => $posted, 'status' => 'posted']);
                        } else {
                            GsoDepartmentalRequestItem::whereId($res->identity)->update(['quantity_posted' => $posted, 'status' => 'partial']);
                        }

                        /** account payables start **/                        
                        $totalAmt = floatval($amount) * floatval($quantity);
                        if ($purchased->supplier->vat_type == 'Vatable') {
                            $ewtAmt = $purchased->supplier->ewt ? floatval(floatval(floatval($totalAmt) / floatval(1.12)) * floatval($purchased->supplier->ewt->percentage)) : NULL;
                            $evatAmt = $purchased->supplier->evat ? floatval(floatval(floatval($totalAmt) / floatval(1.12)) * floatval($purchased->supplier->evat->percentage)) : NULL;
                        } else {
                            $ewtAmt = $purchased->supplier->ewt ? floatval(floatval($totalAmt) * floatval($purchased->supplier->ewt->percentage)) : NULL;
                            $evatAmt = $purchased->supplier->evat ? floatval(floatval($totalAmt) * floatval($purchased->supplier->evat->percentage)) : NULL;
                        }
                        
                        $payee = $purchased->supplier->payee_id;
                        $payableDetails = array(
                            'payee_id' => $payee,
                            'fund_code_id' => $this->get_fund_code($purchased->rfq_id),
                            'gl_account_id' => $item->gl_account_id,
                            'sl_account_id' => NULL,
                            'trans_no' => $purchased->purchase_order_no,
                            'trans_type' => 'Purchase Order',
                            'trans_id' => $posting_line->id,
                            'responsibility_center' => $res->centre,
                            'remarks' => $request->get('reference_no'),
                            'items' => $this->findItemDescription($poID, $post['id']),
                            'quantity' => $quantity,
                            'uom_id' => $post['uom_id'],
                            'amount' => $amount,
                            'total_amount' => $totalAmt,
                            'due_date' => date('Y-m-d', strtotime($due_date)),
                            'vat_type' => $purchased->supplier->vat_type,
                            'ewt_id' => $purchased->supplier->ewt_id,
                            'ewt_amount' => $this->truncate_number($ewtAmt, 5),
                            'evat_id' => $purchased->supplier->evat_id,
                            'evat_amount' => $this->truncate_number($evatAmt, 5),
                            'created_at' => $timestamp,
                            'created_by' => $user
                        );
                        $payables = AcctgAccountPayable::create($payableDetails);
                        /** account payables end **/
                        
                        $quantity -= floatval($quantity);
                    } else {
                        $posted = floatval($res->quantity_posted) + floatval($forPosting);
                        GsoDepartmentalRequestItem::whereId($res->identity)->update(['quantity_posted' => $posted, 'status' => 'posted']);

                        /** account payables start **/
                        $totalAmt = floatval($amount) * floatval($forPosting);
                        if ($purchased->supplier->vat_type == 'Vatable') {
                            $ewtAmt = $purchased->supplier->ewt ? floatval(floatval(floatval($totalAmt) / floatval(1.12)) * floatval($purchased->supplier->ewt->percentage)) : NULL;
                            $evatAmt = $purchased->supplier->evat ? floatval(floatval(floatval($totalAmt) / floatval(1.12)) * floatval($purchased->supplier->evat->percentage)) : NULL;
                        } else {
                            $ewtAmt = $purchased->supplier->ewt ? floatval(floatval($totalAmt) * floatval($purchased->supplier->ewt->percentage)) : NULL;
                            $evatAmt = $purchased->supplier->evat ? floatval(floatval($totalAmt) * floatval($purchased->supplier->evat->percentage)) : NULL;
                        }
                        
                        $payee = $purchased->supplier->payee_id;
                        $payableDetails = array(
                            'payee_id' => $payee,
                            'fund_code_id' => $this->get_fund_code($purchased->rfq_id),
                            'gl_account_id' => $item->gl_account_id,
                            'sl_account_id' => NULL,
                            'trans_no' => $purchased->purchase_order_no,
                            'trans_type' => 'Purchase Order',
                            'trans_id' => $posting_line->id,
                            'responsibility_center' => $res->centre,
                            'remarks' => $request->get('reference_no'),
                            'items' => $this->findItemDescription($poID, $post['id']),
                            'quantity' => $forPosting,
                            'uom_id' => $post['uom_id'],
                            'amount' => $amount,
                            'total_amount' => $totalAmt,
                            'due_date' => date('Y-m-d', strtotime($due_date)),
                            'vat_type' => $purchased->supplier->vat_type,
                            'ewt_id' => $purchased->supplier->ewt_id,
                            'ewt_amount' => $this->truncate_number($ewtAmt, 5),
                            'evat_id' => $purchased->supplier->evat_id,
                            'evat_amount' => $this->truncate_number($evatAmt, 5),
                            'created_at' => $timestamp,
                            'created_by' => $user
                        );
                        $payables = AcctgAccountPayable::create($payableDetails);
                        /** account payables end **/

                        $quantity -= floatval($forPosting);
                    }
                }
            }
        }

        $result2 = GsoDepartmentalRequestItem::select([
            'gso_departmental_requests_items.status as identityStatus',
            'gso_departmental_requests_items.id as identity'
        ])
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
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->where([
            'gso_purchase_orders.id' => $poID,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->get();

        $count = 0;
        foreach ($result2 as $res2) {
            if ($res2->identityStatus == 'posted') {
                $count++;
            }
        }
        if($result2->count() == $count) {
            GsoPurchaseOrderPosting::whereId($posting->id)->update(['status' => 'completed']);
            GsoPurchaseOrder::whereId($poID)->update(['posting_status' => 'completed']);
        } 

        return true;
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function fetch_designation($employee)
    {
        return HrEmployee::find($employee)->hr_designation_id;
    }

    public function allUsers()
    {
        return (new User)->allUsers();
    }

    public function find_via_column($column, $value)
    {
        return GsoPurchaseOrder::select(['*'])->where($column, $value)->get();
    }

    public function find_posting($poNum, $sequence = '')
    {
        $res = GsoPurchaseOrderPosting::select([
            '*',        
            'gso_purchase_orders_posting.status as identityStatus',
            'gso_purchase_orders.posting_status as parentStatus',
        ])
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum
        ]);       
        if ($sequence != '') {
            $res->where('gso_purchase_orders_posting.sequence_no', $sequence);
        }
        return $res->get();
    }

    public function find_posting_lines($poNum, $sequence)
    {
        return GsoPurchaseOrderPostingLine::select(['*'])
        ->leftJoin('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'gso_purchase_orders_posting.sequence_no' => $sequence,
            'gso_purchase_orders_posting_lines.is_active' => 1
        ])->get();
    }

    public function getAlobs($poNum)
    {
        $res = BacRfqLine::select([
            '*',
            'cbo_allotment_obligations.alobs_control_no as alobNo'
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
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('gso_departmental_requests.id')
        ->get();

        $arr = array();
        foreach ($res as $r) {
            $arr[] = $r->alobNo;
        }

        return implode(',', $arr);
    }   

    public function getAlobsAmount($poNum)
    {
        $res = BacRfqLine::select([
            '*',
            'cbo_allotment_obligations.total_amount as alobAmount'
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
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('gso_departmental_requests.id')
        ->get();

        $totalAmt = 0;
        foreach ($res as $r) {
            if (floatval($r->alobAmount) > 0) {
                $totalAmt += floatval($r->alobAmount);
            }
        }

        return $totalAmt;
    }   

    public function getPrNos($poNum)
    {
        $res = BacRfqLine::select([
            '*',
            'gso_purchase_requests.purchase_request_no as prNo'
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
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('gso_departmental_requests.id')
        ->get();

        $arr = array();
        foreach ($res as $r) {
            $arr[] = $r->prNo;
        }

        return implode(',', $arr);
    }   

    public function getPoDepartments($poNum)
    {
        $res = BacRfqLine::select([
            '*',
            'acctg_departments.shortname as department'
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
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('acctg_departments.id')
        ->get();

        $arr = array();
        foreach ($res as $r) {
            $arr[] = $r->department;
        }

        return implode(', ', $arr);
    }

    public function getPoDivisions($poNum)
    {
        $res = BacRfqLine::select([
            '*',
            'acctg_departments.code as department',
            'acctg_departments_divisions.code as division'
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
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.acctg_department_id', '=', 'acctg_departments.id');
        })
        ->where([
            'gso_purchase_orders.purchase_order_no' => $poNum,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('acctg_departments.id')
        ->get();

        $arr = array();
        foreach ($res as $r) {
            if (!in_array($r->department.''.$r->division, $arr)) {
                $arr[] = $r->department.''.$r->division;
            }
        }

        return implode(', ', $arr);
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
}