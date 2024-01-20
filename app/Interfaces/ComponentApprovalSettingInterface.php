<?php

namespace App\Interfaces;

interface ComponentApprovalSettingInterface 
{    
    public function find($id);

    public function listItems($request);

    public function validate($module, $sub_module, $id);

    public function allModuleMenus();

    public function allUsers();

    public function allDepartmentx();

    public function store($request, $timestamp, $user);

    public function modify($id, $request, $timestamp, $user);

    public function findLines($sequence, $settingID);

    public function reload_sub_module($module);
}