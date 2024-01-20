<?php

namespace App\Repositories;

use App\Interfaces\ComponentUserAccountInterface;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use App\Models\HrEmployee;
use App\Models\UserRole;
use App\Models\UserRoleGroup;
use App\Models\UserRoleModule;
use App\Models\UserRoleSubModule;
use App\Models\DashUserMenuPermissions;

class ComponentUserAccountRepository implements ComponentUserAccountInterface 
{
    public function find($id) 
    {
        return User::select(['*'])->with(['user_role.role',  'hr_employee'])->findOrFail($id);
    }
    
    public function validate($data, $column, $id = '')
    {   
        if ($id !== '') {
            return User::where([$column => $data])->where('id', '!=', $id)->count();
        } 
        return User::where([$column => $data])->count();
    }

    public function create(array $details, $request, $timestamp, $user) 
    {
        $users = User::create($details);

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
                    $role_group = UserRoleGroup::create([
                        'user_id' => $users->id,
                        'role_id' => $request->role_id,
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
                    $role_module = UserRoleModule::create([
                        'user_id' => $users->id,
                        'role_id' => $request->role_id,
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
                    $role_sub_module = UserRoleSubModule::create([
                        'user_id' => $users->id,
                        'role_id' => $request->role_id,
                        'menu_sub_module_id' => $sub_module,
                        'permissions' => implode(',', $privileges),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        $employee = HrEmployee::where('id', $request->employee_id)->update([
            'user_id' => $users->id,
            'updated_at' => $timestamp,
            'updated_by' => $user
        ]);
        $userRole = UserRole::create([
            'user_id' => $users->id,
            'role_id' => $request->role_id,
            'created_at' => $timestamp,
            'created_by' => $user
        ]);
        
        return $users;
    }

    public function update($id, array $newDetails, $request, $timestamp, $user) 
    {   
        if ($request) {
            UserRoleGroup::where('user_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
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
                        $res = UserRoleGroup::where([
                            'user_id' => $id,
                            'role_id' => $request->role_id,
                            'menu_group_id' => $group,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_group = UserRoleGroup::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_group = UserRoleGroup::create([
                                'user_id' => $id,
                                'role_id' => $request->role_id,
                                'menu_group_id' => $group,
                                'permissions' => implode(',', $privileges),
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }
                    }
                }
            }

            UserRoleModule::where('user_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
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
                        $res = UserRoleModule::where([
                            'user_id' => $id,
                            'role_id' => $request->role_id,
                            'menu_module_id' => $module,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_module = UserRoleModule::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_module = UserRoleModule::create([
                                'user_id' => $id,
                                'role_id' => $request->role_id,
                                'menu_module_id' => $module,
                                'permissions' => implode(',', $privileges),
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }
                    }
                }
            }

            UserRoleSubModule::where('user_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
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
                        $res = UserRoleSubModule::where([
                            'user_id' => $id,
                            'role_id' => $request->role_id,
                            'menu_sub_module_id' => $sub_module,
                        ])->get();

                        if ($res->count() > 0) {
                            $role_sub_module = UserRoleSubModule::where([
                                'id' => $res->first()->id
                            ])->update([
                                'permissions' => implode(',', $privileges),
                                'updated_at' => $timestamp,
                                'updated_by' => $user,
                                'is_active' => 1
                            ]);
                        } else {
                            $role_sub_module = UserRoleSubModule::create([
                                'user_id' => $id,
                                'role_id' => $request->role_id,
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
        $employee = HrEmployee::where('id', $request->employee_id)->update([
            'user_id' => $id,
            'updated_at' => $timestamp,
            'updated_by' => $user
        ]);
        $userRes = UserRole::where(['user_id' => $id])->get();
        if ($userRes->count() > 0) {
            $userRole = UserRole::where(['id' => $userRes->first()->id])
            ->update([
                'role_id' => $request->role_id,
                'updated_at' => $timestamp,
                'updated_by' => $user
            ]);
        } else {
            $userRole = UserRole::create([
                'user_id' => $id,
                'role_id' => $request->role_id,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }
        return User::whereId($id)->update($newDetails);
    }

    public function updateDash($id, $request, $timestamp, $user) 
    {   
        if ($request) {
            // UserRoleGroup::where('user_id', $id)->update(['is_active' => 0, 'updated_at' => $timestamp, 'updated_by' => $user]);
            $groups = $request->input('group');
            if (!empty($groups)) {
                foreach ($groups ?? [] as $group) {
                    if ($group !== NULL) {
                        if (isset($request->input('group_permission')[$group])) {
                            $permissions = $request->input('group_permission')[$group];
                            $privileges = array();
                            foreach ($permissions as $permission) {
                                if ($permission !== NULL) {
                                    $privileges[] = $permission;
                                }
                            }
                            $res = DashUserMenuPermissions::where([
                                'user_id' => $id,
                                'menu_group_id' => $group,
                            ])->get();

                            if ($res->count() > 0) {
                                $role_group = DashUserMenuPermissions::where([
                                    'id' => $res->first()->id
                                ])->update([
                                    'menu_permissions' => implode(',', $privileges),
                                    'updated_at' => $timestamp,
                                    'updated_by' => $user,
                                    'is_active' => 1
                                ]);
                            } else {
                                $role_group = DashUserMenuPermissions::create([
                                    'user_id' => $id,
                                    'menu_group_id' => $group,
                                    'menu_permissions' => implode(',', $privileges),
                                    'created_at' => $timestamp,
                                    'created_by' => $user
                                ]);
                            }
                        }else{
                            $role_group = DashUserMenuPermissions::create([
                                'user_id' => $id,
                                'menu_group_id' => $group,
                                'menu_permissions' => "",
                                'created_at' => $timestamp,
                                'created_by' => $user
                            ]);
                        }    
                    }
                }
            }
        }
        
        // return User::whereId($id)->update($newDetails);
    }

    public function modify($id, array $newDetails)
    {
        return User::whereId($id)->update($newDetails);
    }

    public function listItems($request, $role, $user)
    {   
        $columns = array( 
            0 => 'users.id',
            1 => 'users.name',
            2 => 'users.email',
            3 => 'role.name'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'users.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = User::select([
            'users.*',
            'users.id as userID',
            'users.name as userName',
            'users.email as userEmail',
            'role.name as userRole',
            'users.created_at as userCreatedAt',
            'users.created_at as userUpdatedAt',
            'users.is_active as userStatus'
        ])
        ->leftJoin('users_role', function($join)
        {
            $join->on('users_role.user_id', '=', 'users.id');
        })
        ->leftJoin('role', function($join)
        {
            $join->on('role.id', '=', 'users_role.role_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('users.id', 'like', '%' . $keywords . '%')
                ->orWhere('users.name', 'like', '%' . $keywords . '%')
                ->orWhere('users.email', 'like', '%' . $keywords . '%')
                ->orWhere('role.name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        if ($role <> 1) {
            $res = $res->where('role.id', '!=', 1);
            $res = $res->where('users.id', '!=', $user);
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allRoles($role = 0)
    {
        return (new Role)->allRoles($role);
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function count() 
    {
        return User::count();
    }
}