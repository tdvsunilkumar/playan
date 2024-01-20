<?php

namespace App\Repositories;

use App\Interfaces\AcctgExpandedWithholdingTaxesInterface;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgAccountGeneralLedger;

class AcctgExpandedWithholdingTaxesRepository implements AcctgExpandedWithholdingTaxesInterface 
{
    public function find($id) 
    {
        return AcctgExpandedWithholdingTax::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return AcctgExpandedWithholdingTax::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return AcctgExpandedWithholdingTax::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return AcctgExpandedWithholdingTax::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgExpandedWithholdingTax::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'description',
            4 => 'percentage', 
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgExpandedWithholdingTax::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhere('percentage', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }
}