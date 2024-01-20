<?php

namespace App\Repositories;

use App\Interfaces\ComponentPermissionInterface;
use App\Models\Permission;

class ComponentPermissionRepository implements ComponentPermissionInterface 
{
    public function find($id) 
    {
        return Permission::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return Permission::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return Permission::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return Permission::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return Permission::whereId($id)->update($newDetails);
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
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('query');

        $res = Permission::select(['*'])
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

    public function count() 
    {
        return Permission::count();
    }
}