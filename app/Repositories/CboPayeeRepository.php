<?php

namespace App\Repositories;

use App\Interfaces\CboPayeeInterface;
use App\Models\AcctgAccountGroup;
use App\Models\AcctgAccountGroupMajor;
use App\Models\AcctgAccountGroupSubmajor;
use App\Models\CboPayee;
use App\Models\HrEmployee;
use App\Models\GsoSupplierContactPerson;
use App\Models\Barangay;



class CboPayeeRepository implements CboPayeeInterface 
{
    public function getAll() 
    {
        return CboPayee::all();
    }

    public function find($id) 
    {
        return CboPayee::findOrFail($id);
    }
    
    public function validate($paye_type, $id)
    {   
        if ($paye_type == 2) {
            return CboPayee::where('scp_id', $id)->count();
        } 
        return CboPayee::where('hr_employee_id', $id)->count();
    }

    public function create(array $details) 
    {
        return CboPayee::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CboPayee::whereId($id)->update($newDetails);
    }

    public function listItems($startFrom, $limit, $keywords, $sortBy, $orderBy)
    {   
        $column = ($sortBy == '') ? 'cbo_payee.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;

        return CboPayee::select([
            '*', 
            'cbo_payee.id as payeeId', 
            'cbo_payee.paye_name as payeeName', 
            'cbo_payee.paye_type as payeeType',
            'cbo_payee.paye_status as status',
            'cbo_payee.created_at as payeeCreatedAt',
            'cbo_payee.updated_at as payeeUpdatedAt'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_groups_submajors.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.paye_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.paye_type', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order)
        ->skip($startFrom)->take($limit)
        ->get();
    }

    public function listCount($keywords)
    {
        return CboPayee::select([
            '*', 
            'cbo_payee.id as payeeId', 
            'cbo_payee.paye_name as payeeName', 
            'cbo_payee.paye_type as payeeType',
            'cbo_payee.paye_status as status',
            'cbo_payee.created_at as payeeCreatedAt',
            'cbo_payee.updated_at as payeeUpdatedAt'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_groups_submajors.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.paye_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.paye_type', 'like', '%' . $keywords . '%');
            }
        })
        ->count();
    }

    public function allAccountGroups()
    {
        return (new AcctgAccountGroup)->allAccountGroups();
    }
    public function allEmpData()
    {
        return (new HrEmployee)->allEmployees();
    }
    public function allSupplier()
    {
        return (new GsoSupplierContactPerson)->allSupplier();
    }
    public function findAcctGrp($account)
    {
        return AcctgAccountGroup::find($account);
    }
    
    public function findMajorAcctGrp($major)
    {
        return AcctgAccountGroupMajor::find($major);
    }

    public function reload_major_account($account)
    {
        return (new AcctgAccountGroupMajor)->reload_major_account($account);
    }
    public function allBarangays()
    {
        return (new Barangay)->allBarangays();
    }
    public function brgyDetails($id)
    {
        return (new Barangay)->findDetails($id);
    }
    
}