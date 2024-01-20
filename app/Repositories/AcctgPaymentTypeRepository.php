<?php

namespace App\Repositories;

use App\Interfaces\AcctgPaymentTypeInterface;
use App\Models\AcctgPaymentType;

class AcctgPaymentTypeRepository implements AcctgPaymentTypeInterface 
{
    public function find($id) 
    {
        return AcctgPaymentType::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgPaymentType::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgPaymentType::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return AcctgPaymentType::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgPaymentType::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgPaymentType::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}