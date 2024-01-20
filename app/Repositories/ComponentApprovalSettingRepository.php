<?php

namespace App\Repositories;

use App\Interfaces\ComponentApprovalSettingInterface;
use App\Models\UserAccessApprovalSetting;
use App\Models\UserAccessApprovalApprover;
use App\Models\MenuModule;
use App\Models\MenuSubModule;
use App\Models\AcctgDepartment;
use App\Models\User;

class ComponentApprovalSettingRepository implements ComponentApprovalSettingInterface 
{
    public function find($id) 
    {
        return UserAccessApprovalSetting::findOrFail($id);
    }
    
    public function validate($module, $sub_module, $id = '')
    {   
        if ($id !== '') {
            return UserAccessApprovalSetting::where(['module_id' => $module, 'sub_module_id' => $sub_module])->where('id', '!=', $id)->count();
        } 
        return UserAccessApprovalSetting::where(['module_id' => $module, 'sub_module_id' => $sub_module])->count();
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'user_access_approval_settings.id',
            1 => 'menu_groups.name',
            2 => 'menu_modules.name',
            3 => 'menu_sub_modules.name',
            4 => 'user_access_approval_settings.levels',
            5 => 'user_access_approval_settings.remarks'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $cols[]    = (!isset($request->get('order')['0']['column'])) ? 'user_access_approval_settings.id' : $columns[$request->get('order')['0']['column']];
        $order[]     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        if (isset($request->get('order')['1']['column'])) { 
            $cols[] =  $columns[$request->get('order')['1']['column']];
            $order[] = $request->get('order')['1']['dir'];
        }     

        $res = UserAccessApprovalSetting::select([
            'user_access_approval_settings.*',
            'user_access_approval_settings.id as identity',
            'user_access_approval_settings.remarks as identityRemarks',
            'user_access_approval_settings.created_at as identityCreatedAt',
            'user_access_approval_settings.updated_at as identityUpdatedAt',
            'user_access_approval_settings.is_active as identityStatus'
        ])
        ->leftJoin('menu_sub_modules', function($join)
        {
            $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
        })
        ->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
        })
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('user_access_approval_settings.id', 'like', '%' . $keywords . '%')
                ->orWhere('menu_groups.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('user_access_approval_settings.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('user_access_approval_settings.levels', 'like', '%' . $keywords . '%');
            }
        });
        $index = 0;
        foreach ($cols as $col) {
            $res->orderBy($col, $order[$index]);
            $index++;
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allModuleMenus()
    {
        return (new MenuModule)->allModuleMenus();
    }

    public function allUsers()
    {
        return (new User)->allUsersMultiple();
    }

    public function allDepartmentx()
    {
        return (new AcctgDepartment)->where(['is_active' => 1])->orderBy('name', 'asc')->get();
    }

    public function store($request, $timestamp, $user)
    {   
        $setting = UserAccessApprovalSetting::create([
            'module_id' => $request->module_id,
            'sub_module_id' => $request->sub_module_id,
            'levels' => $request->levels,
            'remarks' => $request->remarks,
            'created_at' => $timestamp, 
            'created_by' => $user
        ]);

        $arr = array(); $exclude = ['module_id', 'levels', '_token'];
        foreach ($_POST as $name => $value) {
            if (!in_array($name, $exclude)) {
                $fields = explode('_', $name);
                if ($fields[0] == 1) {
                    $primary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $setting->id,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'primary_approvers' => $primary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $setting->id,
                            'department_id' => $fields[1],
                            'primary_approvers' => $primary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 2) {
                    $secondary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $setting->id,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'secondary_approvers' => $secondary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $setting->id,
                            'department_id' => $fields[1],
                            'secondary_approvers' => $secondary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 3) {
                    $tertiary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $setting->id,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'tertiary_approvers' => $tertiary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $setting->id,
                            'department_id' => $fields[1],
                            'tertiary_approvers' => $tertiary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 4) {
                    $quaternary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $setting->id,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'quaternary_approvers' => $quaternary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $setting->id,
                            'department_id' => $fields[1],
                            'quaternary_approvers' => $quaternary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
            }
        }
        
        return $setting;
    }

    public function modify($settingID, $request, $timestamp, $user)
    {   
        UserAccessApprovalSetting::whereId($settingID)->update([
            'module_id' => $request->module_id,
            'sub_module_id' => $request->sub_module_id,
            'levels' => $request->levels,
            'remarks' => $request->remarks,
            'updated_at' => $timestamp, 
            'updated_by' => $user
        ]);

        $updates = UserAccessApprovalApprover::where(['setting_id' => $settingID])->update([
            'primary_approvers' => NULL,
            'secondary_approvers' => NULL,
            'tertiary_approvers' => NULL,
            'quaternary_approvers' => NULL,
        ]);

        $arr = array(); $exclude = ['module_id', 'levels', '_token'];
        foreach ($_POST as $name => $value) {
            if (!in_array($name, $exclude)) {
                $fields = explode('_', $name);
                if ($fields[0] == 1) {
                    $primary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $settingID,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'primary_approvers' => $primary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $settingID,
                            'department_id' => $fields[1],
                            'primary_approvers' => $primary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 2) {
                    $secondary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $settingID,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'secondary_approvers' => $secondary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $settingID,
                            'department_id' => $fields[1],
                            'secondary_approvers' => $secondary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 3) {
                    $tertiary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $settingID,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'tertiary_approvers' => $tertiary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $settingID,
                            'department_id' => $fields[1],
                            'tertiary_approvers' => $tertiary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
                if ($fields[0] == 4) {
                    $quaternary = (count($value) > 1) ? implode(',', $value) : implode('', $value);
                    $res = UserAccessApprovalApprover::where([
                        'setting_id' => $settingID,
                        'department_id' => $fields[1]
                    ]);
                    if ($res->count() > 0) {
                        UserAccessApprovalApprover::whereId($res->first()->id)->update([
                            'quaternary_approvers' => $quaternary,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        UserAccessApprovalApprover::create([
                            'setting_id' => $settingID,
                            'department_id' => $fields[1],
                            'quaternary_approvers' => $quaternary,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
            }
        }
        
        return UserAccessApprovalSetting::find($settingID);
    }

    public function findLines($sequence, $settingID)
    {
        if ($sequence == 1) {
            $result = UserAccessApprovalApprover::select(['*'])
            ->where([
                'setting_id' => $settingID,
                'is_active' => 1
            ])
            ->where('primary_approvers', '!=', NULL)
            ->get();

            $arr = array();
            if ($result->count() > 0) {
                foreach ($result as $res) {
                    $arr[] = (object) array(
                        '1_department_'.$res->department_id => explode(',', $res->primary_approvers)
                    );
                }
            }
            return $arr;
        } else if ($sequence == 2) {
            $result = UserAccessApprovalApprover::select(['*'])
            ->where([
                'setting_id' => $settingID,
                'is_active' => 1
            ])
            ->where('secondary_approvers', '!=', NULL)
            ->get();

            $arr = array();
            if ($result->count() > 0) {
                foreach ($result as $res) {
                    $arr[] = (object) array(
                        '2_department_'.$res->department_id => explode(',', $res->secondary_approvers)
                    );
                }
            }
            return $arr;
        } else if ($sequence == 3) {
            $result = UserAccessApprovalApprover::select(['*'])
            ->where([
                'setting_id' => $settingID,
                'is_active' => 1
            ])
            ->where('tertiary_approvers', '!=', NULL)
            ->get();

            $arr = array();
            if ($result->count() > 0) {
                foreach ($result as $res) {
                    $arr[] = (object) array(
                        '3_department_'.$res->department_id => explode(',', $res->tertiary_approvers)
                    );
                }
            }
            return $arr;
        } else {
            $result = UserAccessApprovalApprover::select(['*'])
            ->where([
                'setting_id' => $settingID,
                'is_active' => 1
            ])
            ->where('quaternary_approvers', '!=', NULL)
            ->get();

            $arr = array();
            if ($result->count() > 0) {
                foreach ($result as $res) {
                    $arr[] = (object) array(
                        '4_department_'.$res->department_id => explode(',', $res->quaternary_approvers)
                    );
                }
            }
            return $arr;
        }
    }

    public function reload_sub_module($module)
    {
        return (new MenuSubModule)->reload_sub_module($module);
    }
}