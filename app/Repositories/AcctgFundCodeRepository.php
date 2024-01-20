<?php

namespace App\Repositories;

use App\Interfaces\AcctgFundCodeRepositoryInterface;
use App\Models\AcctgFundCode;

class AcctgFundCodeRepository implements AcctgFundCodeRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgFundCode::all();
    }

    public function find($id) 
    {
        return AcctgFundCode::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgFundCode::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgFundCode::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return AcctgFundCode::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgFundCode::whereId($id)->update($newDetails);
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

        $res = AcctgFundCode::select(['*'])
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