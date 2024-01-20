<?php

namespace App\Repositories;

use App\Interfaces\ComponentFAQInterface;
use App\Models\Faq;
use App\Models\FaqDetail;
use App\Models\MenuGroup;

class ComponentFAQRepository implements ComponentFAQInterface 
{   
    public function lists($keywords = '', $group = '')
    {
        $res = Faq::where(['is_active' => 1]);
        if ($group != '') {
            $res = $res->where('group_id', '=', $group);
        }
        if (!empty($keywords)) {
            $res = $res->where(function($q) use ($keywords) {
                if (!empty($keywords)) {
                    $q->where('title', 'like', '%' . $keywords . '%')
                    ->orWhere('description', 'like', '%' . $keywords . '%');
                }
            });
        }
        $res = $res->get();
        return $res;
    }

    public function find($id) 
    {
        return Faq::findOrFail($id);
    }
    
    public function validate($title, $id = '')
    {   
        if ($id !== '') {
            return Faq::where(['title' => $title])->where('id', '!=', $id)->count();
        } 
        return Faq::where(['title' => $title])->count();
    }

    public function create(array $details) 
    {
        return Faq::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return Faq::whereId($id)->update($newDetails);
    }

    public function find_detail_via_column($id, $column, $value) 
    {
        return FaqDetail::where('faq_id', '=', $id)->where($column, '=', $value)->get();
    }

    public function create_details(array $details) 
    {
        return FaqDetail::create($details);
    }

    public function update_details($id, array $newDetails) 
    {
        return FaqDetail::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id', 
            1 => 'title',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = Faq::select(['faqs.*'])
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'faqs.group_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('faqs.id', 'like', '%' . $keywords . '%')
                ->orWhere('faqs.title', 'like', '%' . $keywords . '%')
                ->orWhere('menu_groups.name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function find_details($id)
    {
        return FaqDetail::where(['faq_id' => $id, 'is_active' => 1])->orderBy('orders', 'asc')->get();
    } 

    public function drop_details($id, array $newDetails)
    {
        return FaqDetail::where('faq_id', $id)->update($newDetails);
    }

    public function allGroupMenus()
    {
        return (new MenuGroup)->allGroupMenus();
    }

    public function update_order($request)
    {       
        $i = 1;
        foreach($request->orders as $id) {
            $this->update_details($id, ['orders' => $i]);
            $i++;
        } 
        return true;
    }
}