<?php

namespace App\Repositories;

use App\Interfaces\GsoUnitOfMeasurementRepositoryInterface;
use App\Models\GsoUnitOfMeasurement;

class GsoUnitOfMeasurementRepository implements GsoUnitOfMeasurementRepositoryInterface 
{
    public function getAll() 
    {
        return GsoUnitOfMeasurement::all();
    }

    public function find($id) 
    {
        return GsoUnitOfMeasurement::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoUnitOfMeasurement::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoUnitOfMeasurement::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return GsoUnitOfMeasurement::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoUnitOfMeasurement::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'description',
            3 => 'remarks'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoUnitOfMeasurement::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhere('remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}