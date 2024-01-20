<?php

namespace App\Repositories;

use App\Interfaces\GsoIssuanceApproverInterface;
use App\Models\GsoIssuance;
use App\Models\GsoIssuanceDetails;
use App\Models\AcctgDepartment;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\AcctgDepartmentDivision;
use App\Models\GsoItem;

class GsoIssuanceApproverRepository implements GsoIssuanceApproverInterface 
{
    public function find($id) 
    {
        $data= GsoIssuance::findOrFail($id);
        $data['department_id']=$data->department->name;
        $data['department_id']=$data->department->name;
        $data['division_id']=$data->division->name;
        $data['issue_requestor']=$data->issue_req_by->fullname;
        $data['issue_requestor_position']=$data->issue_req_position->description;
        return $data;
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
            0 => 'gso_issuance.issue_control_no',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $keywords  = $request->get('query');

        $res = GsoIssuance::select([
                '*', 
                'gso_issuance.id as gi_id', 
                'gso_issuance.issue_control_no as control_no', 
                'gso_issuance.issue_date as issuance_date', 
            ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_issuance.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_date', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column);
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
    public function newlistItems($request,$issu_id)
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
        $keywords  = $request->get('query');

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
                ->orWhere('gso_issuance_details.item_agl_code', 'like', '%' . $keywords . '%')                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.item_desc', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance_details.unit_code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_control_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_issuance.issue_date', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
    
    
}