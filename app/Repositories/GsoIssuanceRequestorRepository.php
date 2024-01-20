<?php

namespace App\Repositories;

use App\Interfaces\GsoIssuanceRequestorInterface;
use App\Models\GsoIssuance;
use App\Models\GsoIssuanceDetails;
use App\Models\AcctgDepartment;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\AcctgDepartmentDivision;
use App\Models\GsoItem;

class GsoIssuanceRequestorRepository implements GsoIssuanceRequestorInterface 
{
    public function find($id) 
    {
        $gso_issuance= GsoIssuance::findOrFail($id);
        $gso_issuance['requestor']=$gso_issuance->issue_req_by->fullname;
        $gso_issuance['requestor_position']=$gso_issuance->issue_req_position->description;
        $gso_issuance['approver']=$gso_issuance->approver->fullname;
        $gso_issuance['approver_position']=$gso_issuance->approver_position->description;
        $gso_issuance['departments']=$gso_issuance->acctg_departments->name;
        $gso_issuance['division']=$gso_issuance->acctg_departments_divisions->name;
        return $gso_issuance;
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoIssuance::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoIssuance::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        GsoIssuance::create($details);
        $gsoInss=GsoIssuance::orderBy('id','DESC')->first();
        return $gsoInss;
    }
    public function createIssuanceDetails(array $details) 
    {
        GsoIssuanceDetails::create($details);
    }
    

    public function update($id, array $newDetails) 
    {
        return GsoIssuance::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_issuance.id',
            1 => 'gso_issuance.issue_control_no',
            2 => 'acctg_departments.name',
            3 => 'acctg_departments_divisions.name'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoIssuance::select([
                '*', 
                'gso_issuance.issue_control_no as control_no', 
                'gso_issuance.id as issuance_id', 
                'gso_issuance.issue_date as issuance_date', 
                'acctg_departments.name as deptName', 
                'acctg_departments_divisions.name as divName', 
            ])
            ->with([
                'acctg_departments' =>  function($q) { 
                    $q->select([
                        'acctg_departments.id', 'acctg_departments.name']);
                }
            ])
            ->with([
                'acctg_departments_divisions' =>  function($q) { 
                    $q->select([
                        'acctg_departments_divisions.id', 'acctg_departments_divisions.name']);
                }
            ])
            ->leftJoin('acctg_departments', function($join)
            {
                $join->on('acctg_departments.id', '=', 'gso_issuance.dept_code');
            })
            ->leftJoin('acctg_departments_divisions', function($join)
            {
                $join->on('acctg_departments_divisions.id', '=', 'gso_issuance.ddiv_code');
            })
        
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuance.issue_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_date', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function issuanceItemDetails($request)
    {   
        $columns = array( 
            0 => 'gso_issuance_details.id',
            1 => 'gso_issuance_details.item_code',
            2 => 'gso_issuance_details.item_name',
            3 => 'gso_issuance_details.item_desc',
            4 => 'gso_issuance_details.unit_code',   
            5 => 'gso_issuance.issue_control_no',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];


        $res = GsoIssuanceDetails::select([
                '*', 
                'gso_issuance.issue_control_no as control_no', 
                'gso_issuance.issue_date as issuance_date', 
            ])
            ->with([
                'gso_issuance' =>  function($q) { 
                    $q->select([
                        'gso_issuance.id', 'gso_issuance.issue_control_no']);
                }
            ])
            ->leftJoin('gso_issuance', function($join)
            {
                $join->on('gso_issuance.id', '=', 'gso_issuance_details.issue_id');
            })
        
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuance_details.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_agl_code', 'like', '%' . $keywords . '%')                
                ->orWhere('gso_issuance_details.item_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_desc', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.unit_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_issuance_details.issue_id',$request->get('issuance_id'))
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function newlistItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'gso_items.code',
            2 => 'gso_items.name',
            3 => 'gso_items.description',
            4 => 'gso_items.weighted_cost',   
            5 => 'gso_items.latest_cost', 
            6 => 'gso_items.quantity_inventory',
            7 => 'acctg_account_general_ledgers.code',
            8 => 'acctg_account_general_ledgers.description',
            9 => 'gso_item_types.code',
            10 => 'gso_item_types.description',
            11 => 'gso_item_categories.code',
            12 => 'gso_item_categories.description',
            13 => 'gso_unit_of_measurements.code',
            14 => 'gso_purchase_types.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
                '*', 
                'gso_items.id as itemId', 
                'gso_items.code as itemCode', 
                'gso_items.name as itemName',
                'gso_items.description as itemDesc',
                'gso_items.is_active as itemStatus',
                'gso_items.created_at as itemCreatedAt',
                'gso_items.updated_at as itemUpdatedAt',
                'gso_items.quantity_inventory as itemInventory',
                'gso_items.weighted_cost as itemWeightedCost',
                'gso_items.latest_cost as itemLatestCost',
                'gso_items.latest_cost_date as itemLatestCostDate',
                'gso_item_categories.code as catCode',
                'gso_unit_of_measurements.code as itemUOM'
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
    }

    public function count() 
    {
        return GsoIssuance::count();
    }

    public function findBy($column, $data)
    {
        return GsoIssuance::where($column, $data)->first();
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
    public function findGsoItem($item_id)
    {
        return GsoItem::findOrFail($item_id);
    }
    public function lastIssueItemDet()
    {
        $data=GsoIssuanceDetails::orderBy('id','DESC')->first();
        return $data;
    }
    
    
}