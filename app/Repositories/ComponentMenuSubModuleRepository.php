<?php

namespace App\Repositories;

use App\Interfaces\ComponentMenuSubModuleInterface;
use App\Models\MenuSubModule;
use App\Models\MenuModule;

class ComponentMenuSubModuleRepository implements ComponentMenuSubModuleInterface 
{
    public function find($id) 
    {
        return MenuSubModule::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return MenuSubModule::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return MenuSubModule::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return MenuSubModule::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return MenuSubModule::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'menu_sub_modules.order',
            1 => 'menu_modules.name',
            2 => 'menu_sub_modules.code',
            3 => 'menu_sub_modules.name',
            4 => 'menu_sub_modules.description',
            5 => 'menu_sub_modules.icon',   
            6 => 'menu_sub_modules.slug'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'menu_sub_modules.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = MenuSubModule::select([
            'menu_sub_modules.*',
            'menu_sub_modules.id as subModuleId',
            'menu_sub_modules.code as subModuleCode',
            'menu_sub_modules.name as subModuleName',
            'menu_sub_modules.description as subModuleDesc',
            'menu_sub_modules.icon as subModuleIcon',
            'menu_sub_modules.slug as subModuleSlug',
            'menu_sub_modules.order as subModuleOrder',
            'menu_sub_modules.is_active as subModuleStatus',
            'menu_sub_modules.created_at as subModuleCreatedAt',
            'menu_sub_modules.updated_at as subModuleUpdatedAt'
        ])
        ->with(['module.group'])
        ->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'menu_sub_modules.menu_module_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('menu_sub_modules.id', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.code', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.description', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.icon', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.slug', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.order', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function count() 
    {
        return MenuSubModule::count();
    }

    public function findBy($column, $data)
    {
        return MenuSubModule::where($column, $data)->first();
    }

    public function allModuleMenus()
    {
        return (new MenuModule)->allModuleMenus();
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