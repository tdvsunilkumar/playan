<?php

namespace App\Repositories;

use App\Interfaces\BacProcurementModeInterface;
use App\Models\BacProcurementMode;

class BacProcurementModeRepository implements BacProcurementModeInterface 
{
    public function find($id) 
    {
        return BacProcurementMode::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return BacProcurementMode::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return BacProcurementMode::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return BacProcurementMode::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return BacProcurementMode::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'description',
            3 => 'minimum_amount',
            4 => 'maximum_amount',   
            5 => 'remarks',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = BacProcurementMode::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhere('minimum_amount', 'like', '%' . $keywords . '%')
                ->orWhere('maximum_amount', 'like', '%' . $keywords . '%')
                ->orWhere('remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}