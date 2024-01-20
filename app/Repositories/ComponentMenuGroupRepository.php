<?php

namespace App\Repositories;

use App\Interfaces\ComponentMenuGroupInterface;
use App\Models\MenuGroup;
use App\Models\MenuPermission;

class ComponentMenuGroupRepository implements ComponentMenuGroupInterface 
{
    public function find($id) 
    {
        return MenuGroup::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return MenuGroup::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return MenuGroup::where(['code' => $code])->count();
    }

    public function validate_slug($slug, $group = '', $module = '', $sub_module = '')
    {   
        if ($sub_module !== '') {
            $res = MenuPermission::where([
                'slug' => $slug,
                'group_id' => $group,
                'module_id' => $module,
                'sub_module_id' => $sub_module
            ])->get();
            if ($res->count() > 0) {
                return MenuPermission::where(['slug' => $slug])->where('id', '!=', $res->first()->id)->count();
            }
        } else if ($module !== '') {
            $res = MenuPermission::where([
                'slug' => $slug,
                'group_id' => $group,
                'module_id' => $module,
                'sub_module_id' => NULL
            ])->get();
            if ($res->count() > 0) {
                return MenuPermission::where(['slug' => $slug])->where('id', '!=', $res->first()->id)->count();
            }
        } else {
            $res = MenuPermission::where([
                'slug' => $slug,
                'group_id' => $group,
                'module_id' => NULL,
                'sub_module_id' => NULL
            ])->get();
            if ($res->count() > 0) {
                return MenuPermission::where(['slug' => $slug])->where('id', '!=', $res->first()->id)->count();
            }
        }
        return MenuPermission::where(['slug' => $slug])->count();
    }

    public function find_slugID($group = '', $module = '', $sub_module = '')
    {   
        if ($sub_module !== '') {
            $res = MenuPermission::where([
                'group_id' => $group,
                'module_id' => $module,
                'sub_module_id' => $sub_module
            ])->get();
            if ($res->count() > 0) {
                return $res->first()->id;
            }
        } else if ($module !== '') {
            $res = MenuPermission::where([
                'group_id' => $group,
                'module_id' => $module,
                'sub_module_id' => NULL
            ])->get();
            if ($res->count() > 0) {
                return $res->first()->id;
            }
        } else {
            $res = MenuPermission::where([
                'group_id' => $group,
                'module_id' => NULL,
                'sub_module_id' => NULL
            ])->get();
            if ($res->count() > 0) {
                return $res->first()->id;
            }
        }
        return false;
    }

    public function create(array $details) 
    {
        return MenuGroup::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return MenuGroup::whereId($id)->update($newDetails);
    }

    public function createSlug(array $details) 
    {
        return MenuPermission::create($details);
    }

    public function updateSlug($id, array $newDetails) 
    {
        return MenuPermission::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'order',
            1 => 'code',
            2 => 'name',
            3 => 'description',
            4 => 'icon',   
            5 => 'slug',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'order' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = MenuGroup::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhere('icon', 'like', '%' . $keywords . '%')
                ->orWhere('slug', 'like', '%' . $keywords . '%')
                ->orWhere('order', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function count() 
    {
        return MenuGroup::count();
    }

    public function findBy($column, $data)
    {
        return MenuGroup::where($column, $data)->first();
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

    public function updateAllSlugs($group, $module, array $groupSlug)
    {
        return MenuPermission::where(['group_id' => $group, 'module_id' => $module])->update($groupSlug);
    }
}