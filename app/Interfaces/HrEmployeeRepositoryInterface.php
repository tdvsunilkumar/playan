<?php

namespace App\Interfaces;

interface HrEmployeeRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details, $access, $timestamp, $user);

    public function update($id, array $newDetails, $access, $timestamp, $user);
    
    public function listItems($request);

    public function validate($identification_no, $id);

    public function allDepartments();

    public function allDepartmentsMultiple();

    public function allDesignations();

    public function allBarangays();

    public function reload_division($department);

    public function findAccess($id);

    public function listItemsUpload($request, $id);
    
    public function formatSizeUnits($bytes);

    public function delete($id);

    public function remove_restore($id, array $newDetails);
}