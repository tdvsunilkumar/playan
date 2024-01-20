<?php

namespace App\Interfaces;

interface AcctgAccountGroupMajorRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $group, $id);

    public function allAccountGroups();
}