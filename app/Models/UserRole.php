<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuGroup;
use App\Models\MenuModule;
use App\Models\MenuSubModule;
use App\Models\UserRoleGroup;
use App\Models\UserRoleModule;
use App\Models\UserRoleSubModule;

class UserRole extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'users_role';
    
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function load_user_menus($user)
    {
        $menus = [];
        $userRes = self::where(['user_id' => $user, 'is_active' => 1])->get();

        if ($userRes->count() > 0) {
            $userRes = $userRes->first(); $role = $userRes->role_id;
            $groups = MenuGroup::
            with([
                'modules' =>  function($q) use ($user, $role) { 
                    $q->select(['*'])
                    ->with([
                        'sub_modules' =>  function($q2) use ($user, $role) { 
                            $q2->select(['*'])
                            ->whereIn('id', 
                                UserRoleSubModule::select('menu_sub_module_id')
                                ->where([
                                    'user_id' => $user, 
                                    'role_id' => $role, 
                                    'is_active' => 1
                                ])
                                ->get()
                            )
                            ->where('is_active', 1)
                            ->orderBy('order', 'asc')
                            ->get();
                        },
                    ])
                    ->whereIn('id', 
                        UserRoleModule::select('menu_module_id')
                        ->where([
                            'user_id' => $user, 
                            'role_id' => $role, 
                            'is_active' => 1
                        ])
                        ->get()
                    )
                    ->where('is_active', 1)
                    ->orderBy('order', 'asc')
                    ->get();
                },
            ])
            ->whereIn('id', 
                UserRoleGroup::select('menu_group_id')
                ->where([
                    'user_id' => $user, 
                    'role_id' => $role, 
                    'is_active' => 1
                ])
                ->get()
            )
            ->where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();

            foreach($groups as $group) {
                $menus['groups'][] = (object) array(
                    'id' => $group->id,
                    'code' => $group->code,
                    'name' => $group->name,
                    'description' => $group->description,
                    'icon' => $group->icon,
                    'slug' => $group->slug
                );

                if ($group->modules) {
                    foreach($group->modules as $module) {
                        $menus['modules'][$group->id][] = (object) array(
                            'id' => $module->id,
                            'code' => $module->code,
                            'name' => $module->name,
                            'description' => $module->description,
                            'icon' => $module->icon,
                            'slug' => $module->slug
                        );

                        if ($module->sub_modules) {
                            foreach($module->sub_modules as $sub_module) {
                                $menus['sub_modules'][$module->id][] = (object) array(
                                    'id' => $sub_module->id,
                                    'code' => $sub_module->code,
                                    'name' => $sub_module->name,
                                    'description' => $sub_module->description,
                                    'icon' => $sub_module->icon,
                                    'slug' => $sub_module->slug
                                );
                            }
                        }
                    }
                }
            }
        }

        return $menus;
    }
}
