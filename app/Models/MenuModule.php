<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'menu_modules';
    
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo('App\Models\MenuGroup', 'menu_group_id', 'id');
    }

    public function sub_modules()
    {
        return $this->hasMany('App\Models\MenuSubModule', 'menu_module_id', 'id')->where('is_active', 1)->orderBy('order', 'asc');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function allModuleMenus($vars = '')
    {
        $modules = self::select('menu_modules.*')->with(['group'])->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
        })
        ->where('menu_modules.is_active', 1)->orderBy('menu_groups.name', 'asc')->get();
    
        $mods = array();
        if (!empty($vars)) {
            $mods[] = array('' => 'select a '.$vars);
        } else {
            $mods[] = array('' => 'select a module');
        }
        foreach ($modules as $module) {
            $mods[] = array(
                $module->id => $module->group->name .' => '. $module->name
            );
        }

        $modules = array();
        foreach($mods as $mod) {
            foreach($mod as $key => $val) {
                $modules[$key] = $val;
            }
        }

        return $modules;
    }

    public function allForApprovalModules($vars = '')
    {
        $modules = self::select([
            'menu_modules.*'
        ])
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
        })
        ->where('menu_groups.slug', 'LIKE', '%for-approvals%')
        ->where([
            'menu_modules.is_active' => 1
        ])
        ->orderBy('menu_modules.name', 'asc')->get();
    
        $mods = array();
        if (!empty($vars)) {
            $mods[] = array('' => 'select a '.$vars);
        } else {
            $mods[] = array('' => 'select a module');
        }
        foreach ($modules as $module) {
            $mods[] = array(
                $module->id => $module->name
            );
        }

        $modules = array();
        foreach($mods as $mod) {
            foreach($mod as $key => $val) {
                $modules[$key] = $val;
            }
        }

        return $modules;
    }
}
