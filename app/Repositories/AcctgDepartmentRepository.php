<?php

namespace App\Repositories;

use App\Interfaces\AcctgDepartmentRepositoryInterface;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\AcctgDepartmentFunction;
use App\Models\HrEmployee;
use App\Models\HrDesignation;

class AcctgDepartmentRepository implements AcctgDepartmentRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgDepartment::all();
    }

    public function find($id) 
    {
        return AcctgDepartment::findOrFail($id);
    }

    public function create(array $details) 
    {
        return AcctgDepartment::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgDepartment::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_departments.id',
            1 => 'acctg_departments.code',
            2 => 'acctg_departments.financial_code',
            3 => 'acctg_departments.name',
            4 => 'hr_employees.fullname',
            5 => 'hr_designations.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgDepartment::select([
            '*', 
            'acctg_departments.id as depId', 
            'acctg_departments.code as depCode', 
            'acctg_departments.name as depName',
            'acctg_departments.is_active as depStatus',
            'acctg_departments.created_at as depCreatedAt',
            'acctg_departments.updated_at as depUpdatedAt',
            'hr_designations.description as desigName'
        ])
        ->with([
            'functions' =>  function($q) { 
                $q->select([
                    'acctg_departments_functions.id', 'acctg_departments_functions.code', 'acctg_departments_functions.name']);
            },
            'employee' =>  function($q) { 
                $q->select([
                    'hr_employees.id', 'hr_employees.firstname', 'hr_employees.middlename', 'hr_employees.lastname', 'hr_employees.fullname']);
            },
            'designation' =>  function($q) { 
                $q->select([
                    'hr_designations.id', 'hr_designations.code', 'hr_designations.description']);
            }
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'acctg_departments.hr_employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'acctg_departments.hr_designation_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_departments.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.financial_code', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('hr_designations.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function line_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'name',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgDepartmentDivision::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'acctg_department_id' => $id
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function findLineItems($departmentId)
    {
        return AcctgDepartmentDivision::select(['*'])
        ->where('acctg_department_id', $departmentId)
        ->get();
    }

    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgDepartment::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgDepartment::where(['code' => $code])->count();
    }

    public function validateDivision($departmentId, $code, $id = '') 
    {   
        if ($id !== '') {
            return AcctgDepartmentDivision::where(['acctg_department_id' => $departmentId, 'code' => $code])->where('id', '!=', $id)->count();
        }
        return AcctgDepartmentDivision::where(['acctg_department_id' => $departmentId, 'code' => $code])->count();
    }

    public function createLineItem(array $details) 
    {
        return AcctgDepartmentDivision::create($details);
    }

    public function updateLineItem($id, array $newDetails) 
    {
        return AcctgDepartmentDivision::whereId($id)->update($newDetails);
    }

    public function findLineItem($id) 
    {
        return AcctgDepartmentDivision::findOrFail($id);
    }
    
    public function allDepartmentFunctions()
    {
    	return (new AcctgDepartmentFunction)->allDepartmentFunctions();
    }

    public function allEmployees($vars)
    {
        return (new HrEmployee)->allEmployees($vars);
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function fetch_designation($employee)
    {
        return HrEmployee::find($employee)->hr_designation_id;
    }
}