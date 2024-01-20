<?php

namespace App\Repositories;

use App\Interfaces\GsoWasteMaterialInterface;
use App\Models\CtoReceivable;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgExpandedVatableTax;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseOrderPostingLine;
use DB;

class GsoWasteMaterialRepository implements GsoWasteMaterialInterface 
{
    public function find($id) 
    {
        return CtoReceivable::findOrFail($id);
    }

    public function create(array $details) 
    {
        return CtoReceivable::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CtoReceivable::whereId($id)->update($newDetails);
    }
    
    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_departmental_requests_items.id',
            1 => 'gso_departmental_requests_items.quantity_po',
            2 => 'gso_unit_of_measurements.code',
            3 => 'gso_items.code',
            4 => 'gso_suppliers.business_name',
            5 => 'gso_purchase_orders.purchase_order_no',
            6 => 'gso_suppliers.business_name',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequestItem::select([
            'gso_departmental_requests_items.*',
            'gso_purchase_orders.id as poID',
            'bac_rfqs.id as rfqID',
            'gso_suppliers.business_name as supplier',
            'gso_purchase_orders.purchase_order_no as po_no',
            'gso_purchase_orders.purchase_order_date as po_date'
        ])
        ->join('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->join('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->join('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->join('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->join('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->join('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->join('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->join('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->join('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'gso_purchase_orders.supplier_id');
        })
        ->where([
            'gso_purchase_request_types.id' => 3,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests_items.quantity_po', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.purchase_order_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.purchase_order_date', 'like', '%' . $keywords . '%');
            }
        })
        ->groupBy(['gso_items.id','gso_purchase_orders.id'])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
    
    public function get_po_reference_no($poID, $itemID)
    {
        $res = GsoPurchaseOrderPostingLine::select(['reference_no'])
        ->join('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->join('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where([
            'gso_purchase_orders_posting_lines.is_active' => 1,
            'gso_purchase_orders_posting_lines.item_id' => $itemID,
            'gso_purchase_orders.id' => $poID
        ])
        ->groupBy(['gso_purchase_orders_posting.reference_no'])
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) {
                $arr[] = $r->reference_no;
            }
        }

        return (count($arr) > 0) ? implode(',', $arr) : '';
    }
}