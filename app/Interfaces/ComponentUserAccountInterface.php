<?php

namespace App\Interfaces;

interface ComponentUserAccountInterface 
{    
    public function find($id);

    public function create(array $details, $request, $timestamp, $user);

    public function update($id, array $newDetails, $request, $timestamp, $user);   

    public function modify($id, array $newDetails);      

    public function listItems($request, $role, $user);

    public function validate($data, $column, $id);

    public function count();
    
    public function allRoles($role);

    public function allEmployees();

    public function updateDash($id, $request, $timestamp, $user);   
}