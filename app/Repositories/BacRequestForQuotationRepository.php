<?php

namespace App\Repositories;

use App\Interfaces\BacRequestForQuotationInterface;
use App\Models\AcctgFundCode;
use App\Models\BacRfq;
use App\Models\BacRfqLine;
use App\Models\BacRfqSupplier;
use App\Models\BacRfqSupplierCanvass;
use App\Models\BacExpendableWarranty;
use App\Models\BacNonExpendableWarranty;
use App\Models\BacPriceValidity;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoSupplier;
use App\Models\GsoPurchaseRequest;
use App\Models\GsoPurchaseType;
use App\Models\GsoDepartmentalRequestDisapproval;
use App\Models\GsoDepartmentalRequestTrackingStatus;
use App\Models\User;
use DB;

class BacRequestForQuotationRepository implements BacRequestForQuotationInterface 
{
    public function find($id) 
    {
        return BacRfq::findOrFail($id);
    }

    public function create(array $details) 
    {
        return BacRfq::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return BacRfq::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'bac_rfqs.id',
            1 => 'bac_rfqs.control_no',
            2 => 'acctg_fund_codes.code',
            3 => 'gso_purchase_types.description',
            4 => 'bac_rfqs.project_name',
            5 => 'bac_rfqs.requesting_agency',
            6 => 'bac_rfqs.remarks'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'bac_rfqs.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfq::select([
            'bac_rfqs.*',
        ])
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'bac_rfqs.fund_code_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'bac_rfqs.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('bac_rfqs.id', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.requesting_agency', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.status', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'control_no',
            2 => 'project_name',
            3 => 'requesting_agency',
            4 => 'remarks'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfq::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('control_no', 'like', '%' . $keywords . '%')
                ->orWhere('project_name', 'like', '%' . $keywords . '%')
                ->orWhere('requesting_agency', 'like', '%' . $keywords . '%')
                ->orWhere('remarks', 'like', '%' . $keywords . '%')
                ->orWhere('status', 'like', '%' . $keywords . '%');
            }
        })
        ->where('status', '!=', 'draft')
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
            2 => 'bac_rfqs.control_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_purchase_requests.purchase_request_no' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfqLine::select([
            '*',
            'bac_rfqs_lines.id as identity'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
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
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->where([
            'bac_rfqs.id' => $id,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_purchase_requests.purchase_request_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_lines.rfq_no', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function supplier_listItems($request, $id)
    {
        $columns = array( 
            0 => 'gso_suppliers.business_name',
            1 => 'bac_rfqs_suppliers.contact_number',
            2 => 'bac_rfqs_suppliers.status'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_suppliers.business_name' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacRfqSupplier::select(['*', 
        'bac_rfqs_suppliers.id as identity',
        'bac_rfqs_suppliers.status as identityStatus',
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.rfq_id');
        })
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers.supplier_id');
        })
        ->where([
            'bac_rfqs.id' => $id,
            'bac_rfqs_suppliers.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers.contact_number', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs_suppliers.status', 'like', '%' . $keywords . '%');
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
            0 => 'gso_items.id',
            1 => 'gso_items.code',
            2 => 'gso_items.name',
            3 => 'gso_departmental_requests_items.quantity_pr',
            4 => 'gso_unit_of_measurements.code'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_pr) as itemQuantity')
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
        ->where([
            'bac_rfqs.id' => $id,
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

    public function fetch_items($rfqID)
    {
        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_pr) as itemQuantity')
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
        ->where([
            'bac_rfqs.id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
        ->get();

        return $res;
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allExpendableWarranties()
    {
        return (new BacExpendableWarranty)->allExpendableWarranties();
    }

    public function allNonExpendableWarranties()
    {
        return (new BacNonExpendableWarranty)->allNonExpendableWarranties();
    }

    public function allPriceValidities()
    {
        return (new BacPriceValidity)->allPriceValidities();
    }

    public function view_available_suppliers($rfqID)
    {
        $res = GsoSupplier::select(['*'])
        ->whereNotIn('id',
            (new BacRfqSupplier)
            ->select('supplier_id')
            ->where([
                'rfq_id' => $rfqID,
                'is_active' => 1
            ])
            ->get()
        )
        ->where('is_active', 1)
        ->get();

        return $res;
    }

    public function find_supplier($rfqID, $supplierID) 
    {
        return BacRfqSupplier::where(['rfq_id' => $rfqID, 'supplier_id' => $supplierID])->get();
    }

    public function create_supplier(array $details) 
    {
        return BacRfqSupplier::create($details);
    }

    public function update_supplier($id, array $newDetails) 
    {
        return BacRfqSupplier::whereId($id)->update($newDetails);
    }

    public function update_supplier_canvass($rfqID, $supplierID, array $newDetails) 
    {
        return BacRfqSupplier::where(['rfq_id' => $rfqID, 'supplier_id' => $supplierID])->update($newDetails);
    }

    public function view_available_purchase_requests($rfqID, $fundCode)
    {
        $res = GsoPurchaseRequest::select([
            '*',
            'gso_purchase_requests.id as identity'
        ])
        ->whereNotIn('gso_purchase_requests.id',
            (new BacRfqLine)
            ->select('purchase_request_id')
            ->where([
                'is_active' => 1
            ])
            ->get()
        )
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->where('gso_purchase_requests.departmental_request_id', '>', 0)
        ->where(['cbo_allotment_obligations.fund_code_id' => $fundCode, 'gso_purchase_requests.is_active' => 1])
        ->get();

        return $res;
    }

    public function find_pr($rfqID, $prID) 
    {
        return BacRfqLine::where(['rfq_id' => $rfqID, 'purchase_request_id' => $prID])->get();
    }

    public function create_pr(array $details) 
    {
        return BacRfqLine::create($details);
    }

    public function update_pr($id, array $newDetails) 
    {
        return BacRfqLine::whereId($id)->update($newDetails);
    }

    public function generate_control_no()
    {   
        $year       = date('Y'); 
        $count      = BacRfq::whereYear('created_at', '=', $year)->count();
        $controlNo  = '';
        $controlNo .= $year . '-';

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

    public function find_canvass($rfqID, $supplierID, $itemID)
    {
        $res = BacRfqSupplierCanvass::select('*')
        ->where([
            'rfq_id' => $rfqID,
            'supplier_id' => $supplierID,
            'item_id' => $itemID
        ])
        ->get();
        
        return $res;
    }    

    public function create_canvass(array $details) 
    {
        return BacRfqSupplierCanvass::create($details);
    }

    public function update_canvass($id, array $newDetails) 
    {
        return BacRfqSupplierCanvass::whereId($id)->update($newDetails);
    }

    public function getQuotationNo($rfqID)
    {
        $res = BacRfqLine::where(['rfq_id' => $rfqID, 'is_active' => 1])->get();

        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $r) {
                if(!in_array($r->rfq_no, $arr)) {
                    $arr[] = $r->rfq_no;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function getTotalCanvass($rfqID, $supplierID)
    {
        $total_canvass = BacRfqSupplierCanvass::where([
            'rfq_id' => $rfqID,
            'supplier_id' => $supplierID,
            'is_active' => 1
        ])->sum('total_cost');

        return $total_canvass ? $total_canvass : 0;
    }

    public function computeTotalAmount($rfqID, $supplierID)
    {
        $total_canvass = BacRfqSupplierCanvass::where([
            'rfq_id' => $rfqID,
            'supplier_id' => $supplierID,
            'is_active' => 1
        ])->sum('total_cost');

        BacRfqSupplier::where(['rfq_id' => $rfqID, 'supplier_id' => $supplierID])->update(['total_canvass' => $total_canvass]);

        return $total_canvass;
    }

    public function computeTotalBudget($rfqID)
    {
        $total_canvass = BacRfqLine::where([
            'bac_rfqs_lines.rfq_id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.departmental_request_id', '=', 'gso_purchase_requests.departmental_request_id');
        })
        ->sum('cbo_allotment_obligations.total_amount');

        BacRfq::whereId($rfqID)->update(['total_budget' => $total_canvass]);

        return $total_canvass;
    }

    public function validate_supplier($rfqID)
    {
        $res1 = BacRfqSupplier::where(['rfq_id' => $rfqID, 'is_active' => 1])->get();
        if ($res1->count() < 3) {
            return true;
        }
        $res2 = BacRfqSupplier::where(['rfq_id' => $rfqID, 'status' => 'completed', 'is_active' => 1])->get();
        if ($res1->count() != $res2->count()) {
            return true;
        }
        return false;
    }

    public function get_agencies($rfqID)
    {
        $res = BacRfqLine::select([
            '*',
            'acctg_departments.shortname as departmental'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
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
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->where([
            'bac_rfqs.id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->get();

        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $result) {
                if (!in_array($result->departmental, $arr)) {
                    $arr[] = $result->departmental;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function printsingledata($rfqNo) 
    { 
        $res = BacRfqLine::select('*')
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_purchase_requests.departmental_request_id');
        })

            ->where('bac_rfqs.control_no', $rfqNo);
            // ->where('gso_departmental_request_items.is_active', 1);
            $result = $res->first();
    return $result;

    }

    public function printdata($rfqNo) 
    { 
        $res = BacRfqSupplierCanvass::select('*')->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
        })
            ->where('bac_rfqs.control_no', $rfqNo);
            // ->where('gso_departmental_request_items.is_active', 1);
            $result = $res->get();
    return $result;

    }

    public function update_request($rfqID, array $newDetails)
    {
        $res = BacRfqLine::select([
            '*',
            'gso_departmental_requests.id as identity'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
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
            'bac_rfqs.id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->get();

        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $result) {
                GsoDepartmentalRequestItem::where(['departmental_request_id' => $result->identity, 'is_active' => 1])->update($newDetails);
                GsoDepartmentalRequisition::whereId($result->identity)->update($newDetails);
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

    public function generateQuotationNo()
    {   
        $year = date('Y');
        $month = date('m');
        $quotationNo = substr($year, -2).''.substr($month, -2).'-'; 
        $count = BacRfqLine::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

        if($count < 9) {
            $quotationNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $quotationNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $quotationNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $quotationNo .= '0' . ($count + 1);
        } else {
            $quotationNo .= ($count + 1);
        }
        return $quotationNo;
    }

    //method function
    public function kenneth($params)
    {
        return 'this is kenneth: '.$params;
    }
    
    public function find_rfq_via_control_no($controlNo)
    {
        return $res = BacRfqSupplier::select(['*','bac_expendable_warranties.name'])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.id');
        })
        ->leftJoin('bac_expendable_warranties', function($join)
        {
            $join->on('bac_expendable_warranties.id', '=', 'bac_rfqs.warranty_exp_id');
        })
        ->where('bac_rfqs.control_no', $controlNo)
        ->first();

        // return $res = BacRfq::select(['*'])
        // ->leftJoin('bac_rfqs', function($join)
        // {
        //     $join->on('bac_rfqs.rfq_id', '=', 'bac_rfqs_suppliers.rfq_id');
        // })
        // ->where('bac_rfqs.control_no', $controlNo)
        // ->get();
    }

    public function get_supplier($controlNo)
    {
        return $supplier_res = BacRfqSupplier::select([
            '*', 
            'bac_rfqs_lines.rfq_no', 
            'bac_rfqs.id as rfq_ID', 
            'hr_employees.fullname'])
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers.supplier_id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.rfq_id');
        })
        ->leftJoin('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.rfq_id', '=', 'bac_rfqs.id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 
            'bac_rfqs.secretariat_id');
        })
        ->where('bac_rfqs.control_no', $controlNo)
        ->get();
    }

    public function item_list($controlNo)
    {
        return $res = GsoDepartmentalRequestItem::select([
            '*', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_pr) as itemQuantity')
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
        ->where([
            'bac_rfqs.control_no' => $controlNo,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
    ->get();
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
    
    public function updateRequest($rfqID, array $newDetails) 
    {   
        return GsoDepartmentalRequisition::whereIn('gso_departmental_requests.id',
            BacRfqLine::select('gso_purchase_requests.departmental_request_id')
            ->join('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
            })
            ->where([
                'bac_rfqs_lines.rfq_id' => $rfqID,
                'bac_rfqs_lines.is_active' => 1
            ])
            ->groupBy('gso_purchase_requests.departmental_request_id')
            ->get()            
        )->update($newDetails);
    }

    public function updateLines($rfqID, array $newDetails) 
    {   
        return GsoDepartmentalRequestItem::whereIn('gso_departmental_requests_items.departmental_request_id',
            BacRfqLine::select('gso_purchase_requests.departmental_request_id')
            ->join('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
            })
            ->where([
                'bac_rfqs_lines.rfq_id' => $rfqID,
                'bac_rfqs_lines.is_active' => 1
            ])
            ->groupBy('gso_purchase_requests.departmental_request_id')
            ->get()            
        )->update($newDetails);
    }
    
    public function disapprove_request($rfqID, array $details) 
    {   
        $result = BacRfqLine::select('gso_purchase_requests.departmental_request_id')
        ->join('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->where([
            'bac_rfqs_lines.rfq_id' => $rfqID,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->groupBy('gso_purchase_requests.departmental_request_id')
        ->get();      

        foreach ($result as $res) {
            $details['departmental_request_id'] = $res->departmental_request_id;
            GsoDepartmentalRequestDisapproval::create($details);
        }
        return  $result;
    }

    public function getNoteDates($controlNo)
    {
        return $note_dates = BacRfq::select([
            '*',
            'bac_price_validities.name as priceValidityCode',
            'bac_expendable_warranties.name as warrantyExpCode',
            'bac_non_expendable_warranties.name as nonWarrantyExpCode',
            
        ])
        ->rightJoin('bac_price_validities', function($join)
        {
            $join->on('bac_rfqs.price_validaty_id', '=', 'bac_price_validities.id');
        })
        ->rightJoin('bac_expendable_warranties', function($join)
        {
            $join->on('bac_rfqs.warranty_exp_id', '=', 'bac_expendable_warranties.id');
        })
        ->rightJoin('bac_non_expendable_warranties', function($join)
        {
            $join->on('bac_rfqs.warranty_non_exp_id', '=', 'bac_non_expendable_warranties.id');
        })
        
        ->where('bac_rfqs.control_no', $controlNo)
        ->get();
    }

    // public function get_agencies_via_control_no($controlNo)
    // {
    //     return $res_agencies = GsoDepartmentalRequisition::select([
    //         '*'
    //     ])
    //     ->rightJoin('acctg_departments', function($join)
    //     {
    //         $join->on('acctg_departments.id', '=', 'gso_departmental_requests.division_id');
    //     })
    //     ->leftJoin('gso_purchase_requests', function($join)
    //     {
    //         $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
    //     })
    //     ->leftJoin('bac_rfqs_lines', function($join)
    //     {
    //         $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
    //     })
    //     ->rightJoin('bac_rfqs', function($join)
    //     {
    //         $join->on('bac_rfqs_lines.rfq_id', '=', 'bac_rfqs.id');
    //     })
    //     // ->where('bac_rfqs.control_no', $controlNo)
    //     ->get();
    // }

    public function get_agencies_via_control_no($controlNo)
    {
        $res = BacRfqLine::select([
            '*',
            'acctg_departments.shortname as departmental'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
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
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->where([
            'bac_rfqs.control_no' => $controlNo,
            'bac_rfqs_lines.is_active' => 1
        ])
        ->get();

        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $result) {
                if (!in_array($result->departmental, $arr)) {
                    $arr[] = $result->departmental;
                }
            }
        }

        return implode(',', $arr);
    }

    public function find_rfq_via_column($column, $data) 
    {
        return BacRfq::select(['*', 'bac_rfqs.id as identity'])
        ->where($column, $data)->get();
    }

    public function find_rfq_suppliers_via_column($column, $data, $supplierID = '')
    {
        $res = BacRfqSupplier::select(['*', 
        'bac_rfqs_suppliers.id as identity',
        'bac_rfqs_suppliers.status as identityStatus',
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.rfq_id');
        })
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers.supplier_id');
        })
        ->where([
            $column => $data,
            'bac_rfqs_suppliers.is_active' => 1
        ]);
        if ($supplierID != '') {
            $res->where('gso_suppliers.id', $supplierID);
        }
        return $res->get();
    }

    public function find_rfq_lines_via_column($column, $data)
    {
        $res = GsoDepartmentalRequestItem::select([
            '*', 
            'gso_items.id as itemId',
            'gso_items.code as itemCode',
            'gso_items.name as itemName',
            DB::raw('SUM(gso_departmental_requests_items.quantity_pr) as itemQuantity')
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
        ->where([
            $column => $data,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->groupBy('gso_items.id')
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

    public function allPurchaseTypes()
    {
        return (new GsoPurchaseType)->allPurchaseTypes();
    }
}