<?php

namespace App\Interfaces;

interface AcctgDepartmentRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function line_listItems($request, $id);

    public function allDepartmentFunctions();

    public function allEmployees($vars);

    public function allDesignations();

    public function findLineItems($department);
    
    public function validate($code, $id);
    
    public function validateDivision($departmentId, $code, $id);

    public function createLineItem(array $details);

    public function updateLineItem($id, array $newDetails); 

    public function findLineItem($id);

    public function fetch_designation($employee);
}