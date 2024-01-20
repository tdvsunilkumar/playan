<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class SmsTemplate extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_templates';
    
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo('App\Models\SmsType', 'type_id', 'id');
    }

    public function action()
    {
        return $this->belongsTo('App\Models\SmsAction', 'action_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\MenuGroup', 'group_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo('App\Models\MenuModule', 'module_id', 'id');
    }

    public function sub_module()
    {
        return $this->belongsTo('App\Models\MenuSubModule', 'sub_module_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    //search by slug
    public function searchBySlug($slug)
    {
        $group = MenuGroup::select([
            'id',
            DB::raw('0 as type'),
            'slug',
            DB::raw('id as group_id'),
            DB::raw('null as module_id'),
            DB::raw('null as sub_module_id'),
            'is_active'
        ]);
        $module = MenuModule::select([
            'id',
            DB::raw('1 as type'),
            'slug',
            DB::raw('menu_group_id as group_id'),
            DB::raw('id as module_id'),
            DB::raw('null as sub_module_id'),
            'is_active'
        ]);
        $sub_module = MenuSubModule::select([
            'menu_sub_modules.id',
            DB::raw('2 as type'),
            'menu_sub_modules.slug',
            DB::raw('menu_modules.menu_group_id as group_id'),
            DB::raw('menu_sub_modules.menu_module_id as module_id'),
            DB::raw('menu_sub_modules.id as sub_module_id'),
            'menu_sub_modules.is_active'
        ])->join('menu_modules','menu_modules.id','menu_sub_modules.menu_module_id');
        $menu = DB::table(
            $group->union($module)->union($sub_module)
        )->where([['slug',$slug],['is_active',1]])
        ->first();
        
        $template = self::where([
            ['is_active',1],
            ['group_id',$menu->group_id],
            ['module_id',$menu->module_id],
            ['sub_module_id',$menu->sub_module_id],
        ]);
        return $template;
        
    }
}
