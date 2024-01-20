<?php

namespace App\Repositories;

use App\Interfaces\ComponentMenuModuleInterface;
use App\Models\MenuModule;
use App\Models\MenuGroup;

class ComponentMenuModuleRepository implements ComponentMenuModuleInterface 
{
    public function find($id) 
    {
        return MenuModule::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return MenuModule::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return MenuModule::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return MenuModule::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return MenuModule::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'menu_modules.order',
            1 => 'menu_groups.name',
            2 => 'menu_modules.code',
            3 => 'menu_modules.name',
            4 => 'menu_modules.description',
            5 => 'menu_modules.icon',   
            6 => 'menu_modules.slug'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'menu_modules.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = MenuModule::select([
            'menu_modules.*',
            'menu_modules.id as moduleId',
            'menu_modules.code as moduleCode',
            'menu_modules.name as moduleName',
            'menu_modules.description as moduleDesc',
            'menu_modules.icon as moduleIcon',
            'menu_modules.slug as moduleSlug',
            'menu_modules.order as moduleOrder',
            'menu_modules.is_active as moduleStatus',
            'menu_modules.created_at as moduleCreatedAt',
            'menu_modules.updated_at as moduleUpdatedAt'
        ])
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('menu_modules.id', 'like', '%' . $keywords . '%')
                ->orWhere('menu_groups.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.code', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.description', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.icon', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.slug', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.order', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function count() 
    {
        return MenuModule::count();
    }

    public function findBy($column, $data)
    {
        return MenuModule::where($column, $data)->first();
    }

    public function allGroupMenus()
    {
        return (new MenuGroup)->allGroupMenus();
    }

    public function update_order($request)
    {       
        $i = 1;
        foreach($request->orders as $id) {
            $this->update($id, ['order' => $i]);
            $i++;
        } 
        return true;
    }
}