<?php

namespace App\Repositories;

use App\Interfaces\BacResolutionInterface;
use App\Models\BacAbstract;
use App\Models\BacResolution;
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
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\User;
use App\Models\GsoDepartmentalRequestDisapproval;
use DB;

class BacResolutionRepository implements BacResolutionInterface 
{
    public function find($id) 
    {
        return BacResolution::findOrFail($id);
    }

    public function create(array $details) 
    {
        return BacResolution::create($details);
    }

    public function update($rfqID, array $newDetails) 
    {
        return BacResolution::where('rfq_id', $rfqID)->update($newDetails);
    }

    public function find_resolution($rfqID) 
    {
        return BacResolution::where('rfq_id', $rfqID)->first();
    }

    public function update_award($rfqID, $supplierID, array $newDetails) 
    {
        return BacRfqSupplier::where(['rfq_id' => $rfqID, 'supplier_id' => $supplierID])->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'bac_resolution.id',
            1 => 'bac_rfqs.control_no',
            2 => 'bac_rfqs.project_name',
            3 => 'bac_rfqs.requesting_agency'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'bac_resolution.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacResolution::select([
            '*',
            'bac_resolution.id as identity',
            'bac_resolution.status as identityStatus',
            'bac_resolution.created_at as identityCreated',
            'bac_resolution.updated_at as identityUpdated'
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_resolution.rfq_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('bac_resolution.id', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.requesting_agency', 'like', '%' . $keywords . '%')
                ->orWhere('bac_resolution.status', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['bac_resolution.is_active' => 1])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'bac_resolution.id',
            1 => 'bac_rfqs.control_no',
            2 => 'bac_rfqs.project_name',
            3 => 'bac_rfqs.requesting_agency'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'bac_resolution.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacResolution::select([
            '*',
            'bac_resolution.id as identity',
            'bac_resolution.status as identityStatus',
            'bac_resolution.created_at as identityCreated',
            'bac_resolution.updated_at as identityUpdated',
            'bac_resolution.approved_at as identityApprovedAt',
            'bac_resolution.approved_by as identityApprovedBy',
            'bac_resolution.disapproved_at as identityDisapprovedAt',
            'bac_resolution.disapproved_by as identityDispprovedBy',
        ])
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_resolution.rfq_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('bac_resolution.id', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.project_name', 'like', '%' . $keywords . '%')
                ->orWhere('bac_rfqs.requesting_agency', 'like', '%' . $keywords . '%')
                ->orWhere('bac_resolution.status', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['bac_resolution.is_active' => 1])
        ->where('bac_resolution.status', '!=', 'draft')
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

    public function committee_listItems($request, $id)
    {
        $columns = array( 
            0 => 'hr_employees.id',
            1 => 'acctg_departments.code',
            2 => 'acctg_departments_divisions.code',
            3 => 'hr_designations.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'hr_employees.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = HrEmployee::select([
            '*',
            'hr_employees.id as identity',
            'hr_employees.fullname as identityName',
            'hr_designations.description as designations',
            'acctg_departments.name as departments',
            'acctg_departments_divisions.name as divisions',
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_employees.acctg_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'hr_employees.acctg_department_division_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'hr_employees.hr_designation_id');
        })
        ->where([
            'hr_employees.is_active' => 1
        ])
        ->whereIn('hr_employees.id', explode(',', BacResolution::select('committees')->where('rfq_id', $id)->first()->committees))
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('hr_designations.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function view_available_committees($rfqID)
    {
        $res = HrEmployee::select([
            '*',
            'hr_employees.id as identity',
            'hr_employees.fullname as identityName',
            'hr_designations.description as designations',
            'acctg_departments.name as departments',
            'acctg_departments_divisions.name as divisions',
        ])
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_employees.acctg_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'hr_employees.acctg_department_division_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'hr_employees.hr_designation_id');
        })
        ->where([
            'hr_employees.is_active' => 1
        ])
        ->whereRaw('LOWER(hr_designations.description) LIKE ? ',[trim(strtolower('bac-member')).'%'])
        ->whereNotIn('hr_employees.id', explode(',', BacResolution::select('committees')->where('rfq_id', $rfqID)->first()->committees))
        ->orderBy('hr_employees.id', 'ASC')
        ->get();

        return $res;
    }

    public function fetch_suppliers($rfqID)
    {
        $res = BacRfqSupplier::select([
            '*',

        ])
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers.supplier_id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.rfq_id');
        })
        ->where([
            'bac_rfqs.id' => $rfqID,
            'bac_rfqs_suppliers.is_active' => 1
        ])
        ->get();

        return $res;
    }

    public function fetch_canvass($rfqID, $supplierID)
    {
        $res = BacRfqSupplierCanvass::select([
            '*',
            'bac_rfqs_suppliers_canvass.quantity as identityQuantity',
            'bac_rfqs_suppliers_canvass.description as identityModel',
            'bac_rfqs_suppliers_canvass.unit_cost as identityUnitCost',
            'bac_rfqs_suppliers_canvass.total_cost as identityTotalCost',
        ])
        ->leftJoin('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers_canvass.supplier_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'bac_rfqs_suppliers_canvass.item_id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
        })
        ->where([
            'bac_rfqs.id' => $rfqID,
            'bac_rfqs_suppliers_canvass.supplier_id' => $supplierID,
            'bac_rfqs_suppliers_canvass.is_active' => 1
        ])
        ->get();

        return $res;
    }

    public function printdata($control_no) 
    { 
        $res = BacRfqSupplierCanvass::select('*','bac_rfqs.project_name')
            ->leftJoin('bac_rfqs', function($join)
            {
                $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
            })
            ->leftJoin('gso_suppliers', function($join)
            {
                $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
            })
            ->leftJoin('bac_rfqs_suppliers', function($join)
            {
                $join->on('bac_rfqs_suppliers.rfq_id', '=', 'bac_rfqs.id');
            })
            ->where([
                'bac_rfqs.control_no' => $control_no,
                'bac_rfqs_suppliers.is_selected' => 1
            ])
            // ->where('bac_rfqs.control_no', $control_no)
            ->get();

        return $res;
  }
    public function printsingledata($rfqNo) 
        { 
            $res = BacAbstract::select('*')
                ->leftJoin('bac_rfqs', function($join)
                {
                    $join->on('bac_rfqs.id', '=', 'bac_abstract.rfq_id');
                })   
                ->leftJoin('bac_rfqs_suppliers', function($join)
                {
                    $join->on('bac_rfqs_suppliers.rfq_id', '=', 'bac_rfqs.id');
                })        
                ->where([
                    'bac_rfqs.control_no' => $control_no,
                    'bac_rfqs_suppliers.is_selected' => 1
                ])
            
                ->first();
                
            dd($res);
        return $res;
    
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

        if ($res->count() > 0) {
            foreach ($res as $result) {
                GsoDepartmentalRequisition::whereId($result->identity)->update($newDetails);
                GsoDepartmentalRequestItem::where(['departmental_request_id' => $result->identity, 'is_active' => 1])->update($newDetails);
            }
        }

        return true;
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
    public function get_resolution_details($control_no)
    {
        $res = BacRfqSupplier::select('*',
                                    'bac_rfqs.project_name',
                                    'bac_rfqs.requesting_agency',
                                    'bac_rfqs.total_budget',
                                    'gso_suppliers.business_name', 
                                    'gso_purchase_requests.purchase_request_no', 
                                    'bac_resolution.created_at',
                                    'bac_resolution.committees',
                                    'hr_employees.fullname as BacSecretariat',
                                    'users.name as Mayor')
            ->leftJoin('bac_rfqs', function($join)
            {
                $join->on('bac_rfqs.id', '=', 'bac_rfqs_suppliers.rfq_id');
            })
            ->leftJoin('bac_rfqs_lines', function($join)
            {
                $join->on('bac_rfqs_lines.rfq_id', '=', 'bac_rfqs.id');
            })
            ->leftJoin('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
            })
            ->leftJoin('gso_suppliers', function($join)
            {
                $join->on('gso_suppliers.id', '=', 'bac_rfqs_suppliers.supplier_id');
            })
            ->leftJoin('bac_resolution', function($join)
            {
                $join->on('bac_resolution.rfq_id', '=', 'bac_rfqs.id');
            })
            ->leftJoin('hr_employees', function($join)
            {
                $join->on('hr_employees.id', '=', 
                'bac_resolution.secretariat_id');
            })
            ->leftJoin('users', function($join)
            {
                $join->on('users.id', '=', 
                'bac_resolution.approved_by_designation');
            })
            ->where([
                'bac_rfqs.control_no' => $control_no,
                'bac_rfqs_suppliers.is_selected' => 1
            ])
            ->first();
        return $res;
    }

    public function get_hr_employee($control_no, $committees)
    {  
        
        
        $x = HrEmployee::select(['*','hr_designations.description as position'])
            ->whereIn('hr_employees.id', $committees)
            ->leftJoin('hr_designations', function($join)
            {
                $join->on('hr_designations.id', '=', 'hr_employees.hr_designation_id');
                
            })
            ->get();
            return $x;
        
    }

    public function get_all_pr($controlNo)
    {
        $pr_res = BacRfqLine::select(['*','gso_purchase_requests.purchase_request_no'])
        ->leftJoin('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
        })
        ->leftJoin('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->where([
            'bac_rfqs.control_no' => $controlNo
        ])
        ->get();
        return $pr_res;
    }

    


}