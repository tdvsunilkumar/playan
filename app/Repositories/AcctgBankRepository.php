<?php

namespace App\Repositories;

use App\Interfaces\AcctgBankInterface;
use App\Models\AcctgBank;

class AcctgBankRepository implements AcctgBankInterface 
{
    public function find($id) 
    {
        return AcctgBank::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgBank::where(['bank_account_no' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgBank::where(['bank_account_no' => $code])->count();
    }

    public function create(array $details) 
    {
        return AcctgBank::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgBank::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'bank_name',
            2 => 'bank_account_no',
            3 => 'bank_account_name'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgBank::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('bank_name', 'like', '%' . $keywords . '%')
                ->orWhere('bank_account_no', 'like', '%' . $keywords . '%')
                ->orWhere('bank_account_name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}