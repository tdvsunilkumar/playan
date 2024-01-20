<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountGroupMajorRepositoryInterface;
use App\Models\AcctgAccountGroupMajor;
use App\Models\AcctgAccountGroup;

class AcctgAccountGroupMajorRepository implements AcctgAccountGroupMajorRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgAccountGroupMajor::all();
    }

    public function find($id) 
    {
        return AcctgAccountGroupMajor::findOrFail($id);
    }
    
    public function validate($code, $acctGroup, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountGroupMajor::where(['code' => $code, 'acctg_account_group_id' => $acctGroup])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountGroupMajor::where(['code' => $code, 'acctg_account_group_id' => $acctGroup])->count();
    }

    public function create(array $details) 
    {
        return AcctgAccountGroupMajor::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountGroupMajor::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_account_groups_majors.id',
            1 => 'acctg_account_groups.code',
            2 => 'acctg_account_groups_majors.code',
            3 => 'acctg_account_groups_majors.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_groups_majors.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountGroupMajor::select([
            '*', 
            'acctg_account_groups_majors.id as agmId', 
            'acctg_account_groups_majors.code as agmCode', 
            'acctg_account_groups_majors.description as agmDesc',
            'acctg_account_groups_majors.prefix as agmPrefix',
            'acctg_account_groups_majors.is_active as agmStatus',
            'acctg_account_groups_majors.created_at as agmCreatedAt',
            'acctg_account_groups_majors.updated_at as agmUpdatedAt'
        ])
        ->leftJoin('acctg_account_groups', function($join)
        {
            $join->on('acctg_account_groups.id', '=', 'acctg_account_groups_majors.acctg_account_group_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_groups_majors.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_majors.code', 'like', '%' . $keywords . '%')
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
}