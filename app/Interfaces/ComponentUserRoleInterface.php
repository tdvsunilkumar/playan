<?php

namespace App\Interfaces;

interface ComponentUserRoleInterface 
{    
    public function find($id);

    public function create(array $details, $request, $timestamp, $user);

    public function update($id, array $newDetails, $request, $timestamp, $user);    

    public function listItems($request, $role);

    public function validate($code, $id);

    public function count();

    public function load_menus($role, $user);

    public function load_menus_dash($role, $user);

    public function load_available_permissions();

    public function load_available_permissions_dash();
}