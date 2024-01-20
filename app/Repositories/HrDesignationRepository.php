<?php

namespace App\Repositories;

use App\Interfaces\HrDesignationRepositoryInterface;
use App\Models\HrDesignation;

class HrDesignationRepository implements HrDesignationRepositoryInterface 
{
    public function getAll() 
    {
        return HrDesignation::all();
    }

    public function find($id) 
    {
        return HrDesignation::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return HrDesignation::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return HrDesignation::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return HrDesignation::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return HrDesignation::whereId($id)->update($newDetails);
    }

    public function listItems($startFrom, $limit, $keywords, $sortBy, $orderBy)
    {   
        $column = ($sortBy == '') ? 'id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;

        return HrDesignation::select('*')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('code', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order)
        ->skip($startFrom)->take($limit)
        ->get();
    }

    public function listCount($keywords)
    {
        return HrDesignation::select('*')
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('code', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%');
            }
        })
        ->count();
    }
}