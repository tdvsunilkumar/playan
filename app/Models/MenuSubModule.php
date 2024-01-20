<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSubModule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'menu_sub_modules';
    
    public $timestamps = false;

    public function module()
    {
        return $this->belongsTo('App\Models\MenuModule', 'menu_module_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function allSubModuleMenus($vars = '')
    {
        $sub_modules = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $sub_mods = array();
        if (!empty($vars)) {
            $sub_mods[] = array('' => 'select a '.$vars);
        } else {
            $sub_mods[] = array('' => 'select a sub module');
        }
        foreach ($sub_modules as $sub_module) {
            $sub_mods[] = array(
                $sub_module->id => $sub_module->name
            );
        }

        $sub_modules = array();
        foreach($sub_mods as $sub_mod) {
            foreach($sub_mod as $key => $val) {
                $sub_modules[$key] = $val;
            }
        }

        return $sub_modules;
    }

    public function reload_sub_module($module)
    {
        $sub_modules = self::where(['menu_module_id' => $module, 'is_active' => 1])
        ->orderBy('id', 'asc')
        ->get();

        return $sub_modules;
    }
}
