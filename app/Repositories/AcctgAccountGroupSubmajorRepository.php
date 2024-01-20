<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountGroupSubmajorRepositoryInterface;
use App\Models\AcctgAccountGroup;
use App\Models\AcctgAccountGroupMajor;
use App\Models\AcctgAccountGroupSubmajor;

class AcctgAccountGroupSubmajorRepository implements AcctgAccountGroupSubmajorRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgAccountGroupSubmajor::all();
    }

    public function find($id) 
    {
        return AcctgAccountGroupSubmajor::findOrFail($id);
    }
    
    public function validate($code, $acctGroup, $majorAcctGroup, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountGroupSubmajor::where(['code' => $code, 'acctg_account_group_id' => $acctGroup, 'acctg_account_group_major_id' => $majorAcctGroup])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountGroupSubmajor::where(['code' => $code, 'acctg_account_group_id' => $acctGroup, 'acctg_account_group_major_id' => $majorAcctGroup])->count();
    }

    public function create(array $details) 
    {
        return AcctgAccountGroupSubmajor::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountGroupSubmajor::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_account_groups_submajors.id',
            1 => 'acctg_account_groups.code',
            2 => 'acctg_account_groups_majors.code',
            3 => 'acctg_account_groups_submajors.code',
            4 => 'acctg_account_groups_submajors.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_groups_submajors.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountGroupSubmajor::select([
            '*', 
            'acctg_account_groups_submajors.id as agsId', 
            'acctg_account_groups_submajors.code as agsCode', 
            'acctg_account_groups_submajors.description as agsDesc',
            'acctg_account_groups_submajors.prefix as agsPrefix',
            'acctg_account_groups_submajors.is_active as agsStatus',
            'acctg_account_groups_submajors.created_at as agsCreatedAt',
            'acctg_account_groups_submajors.updated_at as agsUpdatedAt'
        ])
        ->with([
            'account_group' =>  function($q) { 
                $q->select([
                    'acctg_account_groups.id', 'acctg_account_groups.code', 'acctg_account_groups.description']);
            },
            'major_account_group' =>  function($q) { 
                $q->select([
                    'acctg_account_groups_majors.id', 'acctg_account_groups_majors.code', 'acctg_account_groups_majors.description', 'acctg_account_groups_majors.prefix']);
            }
        ])
        ->leftJoin('acctg_account_groups', function($join)
        {
            $join->on('acctg_account_groups.id', '=', 'acctg_account_groups_submajors.acctg_account_group_id');
        })
        ->leftJoin('acctg_account_groups_majors', function($join)
        {
            $join->on('acctg_account_groups_majors.id', '=', 'acctg_account_groups_submajors.acctg_account_group_major_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_groups_submajors.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_majors.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_majors.prefix', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allAccountGroups()
    {
        return (new AcctgAccountGroup)->allAccountGroups();
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
}