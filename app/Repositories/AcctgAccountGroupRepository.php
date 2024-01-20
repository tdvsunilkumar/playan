<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountGroupRepositoryInterface;
use App\Models\AcctgAccountGroup;

class AcctgAccountGroupRepository implements AcctgAccountGroupRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgAccountGroup::all();
    }

    public function find($id) 
    {
        return AcctgAccountGroup::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountGroup::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountGroup::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return AcctgAccountGroup::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountGroup::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountGroup::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}