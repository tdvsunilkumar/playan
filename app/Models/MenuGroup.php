<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'menu_groups';
    
    public $timestamps = false;

    public function modules()
    {
        return $this->hasMany('App\Models\MenuModule', 'menu_group_id', 'id')->where('is_active', 1)->orderBy('order', 'asc');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function allGroupMenus($vars = '')
    {
        $groups = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $grps = array();
        if (!empty($vars)) {
            $grps[] = array('' => 'select a '.$vars);
        } else {
            $grps[] = array('' => 'select a group');
        }
        foreach ($groups as $group) {
            $grps[] = array(
                $group->id => $group->name
            );
        }

        $groups = array();
        foreach($grps as $grp) {
            foreach($grp as $key => $val) {
                $groups[$key] = $val;
            }
        }

        return $groups;
    }
}
