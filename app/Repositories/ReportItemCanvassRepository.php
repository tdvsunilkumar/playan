<?php

namespace App\Repositories;

use App\Interfaces\ReportItemCanvassInterface;
use App\Models\BacRfqSupplierCanvass;

class ReportItemCanvassRepository implements ReportItemCanvassInterface 
{
    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'order' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfqSupplierCanvass::select([
            'bac_rfqs_suppliers_canvass.*'
        ])
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers_canvass.supplier_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'bac_rfqs_suppliers_canvass.item_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('bac_rfqs_suppliers_canvass.id', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.description', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.quantity', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.unit_cost', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers_canvass.total_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.branch_name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}