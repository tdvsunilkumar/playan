<?php

namespace App\Repositories;

use App\Interfaces\ComponentUserRoleInterface;
use App\Models\Role;
use App\Models\Permission;
use App\Models\MenuGroup;
use App\Models\MenuModule;
use App\Models\MenuSubModule;
use App\Models\RoleGroup;
use App\Models\RoleModule;
use App\Models\RoleSubModule;
use App\Models\UserRole;
use App\Models\UserRoleGroup;
use App\Models\UserRoleModule;
use App\Models\UserRoleSubModule;
use App\Models\DashboardGroupMenus;
use App\Models\DashUserMenuPermissions;
use DB;

class ComponentUserRoleRepository implements ComponentUserRoleInterface 
{
    public function find($id) 
    {
        return Role::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return Role::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return Role::where(['code' => $code])->count();
    }

    public function create(array $details, $request, $timestamp, $user) 
    {
        $role = Role::create($details);

        $groups = $request->input('group');
        if (!empty($groups)) {
            foreach ($groups as $group) {
                if ($group !== NULL) {
                    $permissions = $request->input('group_permission')[$group];
                    $privileges = array();
                    foreach ($permissions as $permission) {
                        if ($permission !== NULL) {
                            $privileges[] = $permission;
                        }
                    }
                    $role_group = RoleGroup::create([
                        'role_id' => $role->id,
                        'menu_group_id' => $group,
                        'permissions' => implode(',', $privileges),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        $modules = $request->input('module');
        if (!empty($modules)) {
            foreach ($modules as $module) {
                if ($module !== NULL) {
                    $permissions = $request->input('module_permission')[$module];
                    $privileges = array();
                    foreach ($permissions as $permission) {
                        if ($permission !== NULL) {
                            $privileges[] = $permission;
                        }
                    }
                    $role_module = RoleModule::create([
                        'role_id' => $role->id,
                        'menu_module_id' => $module,
                        'permissions' => implode(',', $privileges),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        $sub_modules = $request->input('sub_module');
        if (!empty($sub_modules)) {
            foreach ($sub_modules as $sub_module) {
                if ($sub_module !== NULL) {
                    $permissions = $request->input('sub_module_permission')[$sub_module];
                    $privileges = array();
                    foreach ($permissions as $permission) {
                        if ($permission !== NULL) {
                            $privileges[] = $permission;
                        }
                    }
                    $role_sub_module = RoleSubModule::create([
                        'role_id' => $role->id,
                        'menu_sub_module_id' => $sub_module,
                        'permissions' => implode(',', $privileges),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        return $role;
    }

    public function update($id, array $newDetails, $request, $timestamp, $user) 
    {   
        if ($request) {
            RoleGroup::where('role_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
            $groups = $request->input('group');
            if (!empty($groups)) {
                foreach ($groups ?? [] as $group) {
                    if ($group !== NULL) {
                        $permissions = $request->input('group_permission')[$group];
                        $privileges = array();
                        foreach ($permissions as $permission) {
                            if ($permission !== NULL) {
                                $privileges[] = $permission;
                            }
                        }
                        $res = RoleGroup::where([
                            'role_id' => $id,
                            'menu_group_id' => $group,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_group = RoleGroup::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_group = RoleGroup::create([
                                'role_id' => $id,
                                'menu_group_id' => $group,
                                'permissions' => implode(',', $privileges),
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }
                    }
                }
            }

            RoleModule::where('role_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
            $modules = $request->input('module');
            if (!empty($modules)) {
                foreach ($modules ?? [] as $module) {
                    if ($module !== NULL) {
                        $permissions = $request->input('module_permission')[$module];
                        $privileges = array();
                        foreach ($permissions as $permission) {
                            if ($permission !== NULL) {
                                $privileges[] = $permission;
                            }
                        }
                        $res = RoleModule::where([
                            'role_id' => $id,
                            'menu_module_id' => $module,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_module = RoleModule::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_module = RoleModule::create([
                                'role_id' => $id,
                                'menu_module_id' => $module,
                                'permissions' => implode(',', $privileges),
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }
                    }
                }
            }

            RoleSubModule::where('role_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
            $sub_modules = $request->input('sub_module');
            if (!empty($sub_modules)) {
                foreach ($sub_modules ?? [] as $sub_module) {
                    if ($sub_module !== NULL) {
                        $permissions = $request->input('sub_module_permission')[$sub_module];
                        $privileges = array();
                        foreach ($permissions as $permission) {
                            if ($permission !== NULL) {
                                $privileges[] = $permission;
                            }
                        }
                        $res = RoleSubModule::where([
                            'role_id' => $id,
                            'menu_sub_module_id' => $sub_module,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_sub_module = RoleSubModule::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_sub_module = RoleSubModule::create([
                                'role_id' => $id,
                                'menu_sub_module_id' => $sub_module,
                                'permissions' => implode(',', $privileges),
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }
                    }
                }
            }
        }
        return Role::whereId($id)->update($newDetails);
    }

    public function listItems($request, $role)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('query');

        $res = Role::select(['*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        if ($role <> 1) {
            $res = $res->where('id', '!=' , 1);
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function count() 
    {
        return Role::count();
    }

    public function find_role_group_permission($role, $group, $user)
    {   
        $permission = '';
        $exist = UserRoleGroup::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            $res = UserRoleGroup::where(['user_id' => $user, 'role_id' => $role, 'menu_group_id' => $group, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        } else {
            $res = RoleGroup::where(['role_id' => $role, 'menu_group_id' => $group, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        }
        return $permission;
    }

    public function find_role_group_selected($role, $group, $user)
    {
        $exist = UserRoleGroup::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            return UserRoleGroup::where(['user_id' => $user, 'role_id' => $role, 'menu_group_id' => $group, 'is_active' => 1])->count();
        } else {
            return RoleGroup::where(['role_id' => $role, 'menu_group_id' => $group, 'is_active' => 1])->count();
        }
    }

    public function find_role_group_permission_dash($role, $group, $user)
    {   
        return DashboardGroupMenus::where('menu_group_id',$group)->where('is_active', 1)->get();
    }

    public function find_role_group_selected_dash($role, $group, $user)
    {
        
        $data= DashUserMenuPermissions::where(['user_id' => $user, 'menu_group_id' => $group, 'is_active' => 1])->first();
        if(!empty($data)){
            return $data->menu_permissions;
        }
        return "";
        
    }

    public function find_role_module_permission($role, $module, $user)
    {   
        $permission = '';
        $exist = UserRoleModule::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            $res = UserRoleModule::where(['user_id' => $user, 'role_id' => $role, 'menu_module_id' => $module, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        } else {
            $res = RoleModule::where(['role_id' => $role, 'menu_module_id' => $module, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        }
        return $permission;
    }

    public function find_role_module_selected($role, $module, $user)
    {   
        $exist = UserRoleModule::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            return UserRoleModule::where(['user_id' => $user, 'role_id' => $role, 'menu_module_id' => $module, 'is_active' => 1])->count();
        } else {
            return RoleModule::where(['role_id' => $role, 'menu_module_id' => $module, 'is_active' => 1])->count();
        }
    }

    public function find_role_sub_module_permission($role, $sub_module, $user)
    {   
        $permission = '';
        $exist = UserRoleSubModule::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            $res = UserRoleSubModule::where(['user_id' => $user, 'role_id' => $role, 'menu_sub_module_id' => $sub_module, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        } else {
            $res = RoleSubModule::where(['role_id' => $role, 'menu_sub_module_id' => $sub_module, 'is_active' => 1])->get();
            if ($res->count() > 0) {
                $permission = $res->first()->permissions;
            }
        }
        return $permission;
    }

    public function find_role_sub_module_selected($role, $sub_module, $user)
    {   
        $exist = UserRoleSubModule::where(['user_id' => $user, 'role_id' => $role, 'is_active' => 1])->get(); 
        if ($exist->count() > 0) {
            return UserRoleSubModule::where(['user_id' => $user, 'role_id' => $role, 'menu_sub_module_id' => $sub_module, 'is_active' => 1])->count();
        } else {
            return RoleSubModule::where(['role_id' => $role, 'menu_sub_module_id' => $sub_module, 'is_active' => 1])->count();
        }
    }

    public function load_menus($role, $user)
    {
        $res = MenuGroup::select('*')->with(['modules.sub_modules'])->where(['is_active' => 1])->orderBy('order', 'asc')->get();

        $menus = array(); $iteration = 0;
        foreach($res as $group) {
            /* ADD GROUPS */
            $menus['group'][] = array(
                'id' => $group->id,
                'code' => $group->code,
                'name' => $group->name,
                'description' => $group->description,
                'icon' => $group->icon,
                'slug' => $group->slug,
                'permissions' => $this->find_role_group_permission($role, $group->id, $user),
                'is_selected' => $this->find_role_group_selected($role, $group->id, $user)
            );

             /* START MODULES */
            if ($group->modules) {
                foreach($group->modules as $module) {
                    /* ADD MODULES */
                    $menus['modules'][$group->id][] = array(
                        'id' => $module->id,
                        'code' => $module->code,
                        'name' => $module->name,
                        'description' => $module->description,
                        'icon' => $module->icon,
                        'slug' => $module->slug,
                        'permissions' => $this->find_role_module_permission($role, $module->id, $user),
                        'is_selected' => $this->find_role_module_selected($role, $module->id, $user)
                    );

                     /* START SUB MODULES */
                    if ($module->sub_modules) {
                        foreach($module->sub_modules as $sub_module) {
                            /* ADD SUB MODULES */
                            $menus['sub_modules'][$module->id][] = array(
                                'id' => $sub_module->id,
                                'code' => $sub_module->code,
                                'name' => $sub_module->name,
                                'description' => $sub_module->description,
                                'icon' => $sub_module->icon,
                                'slug' => $sub_module->slug,
                                'permissions' => $this->find_role_sub_module_permission($role, $sub_module->id, $user),
                                'is_selected' => $this->find_role_sub_module_selected($role, $sub_module->id, $user)
                            );
                        }
                    }
                    /* END SUB MODULES */
                }
            }
            /* END MODULES */
        } 

        return $menus;
    }

    public function load_menus_dash($role, $user)
    {
        $res = MenuGroup::select('*')->with(['modules.sub_modules'])->where(['is_active' => 1,'is_dashboard' => 1])->orderBy('order', 'asc')->get();

        $menus = array(); $iteration = 0;
        foreach($res as $group) {
            /* ADD GROUPS */
            $menus['group'][] = array(
                'id' => $group->id,
                'code' => $group->code,
                'name' => $group->name,
                'description' => $group->description,
                'icon' => $group->icon,
                'slug' => $group->slug,
                'permissions' => $this->find_role_group_permission_dash($role, $group->id, $user),
                'is_per_selected' => $this->find_role_group_selected_dash($role, $group->id, $user),
                'is_selected' => $this->find_role_group_selected($role, $group->id, $user)
            );
        } 

        return $menus;
    }
    

    public function load_available_permissions()
    {
        return Permission::where('is_active', 1)->get();
    }
    public function load_available_permissions_dash()
    {
        return DashboardGroupMenus::where('menu_group_id',2)->where('is_active', 1)->get();
    }
    
}