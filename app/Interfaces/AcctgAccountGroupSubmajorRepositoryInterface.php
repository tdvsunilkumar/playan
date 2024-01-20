<?php

namespace App\Interfaces;

interface AcctgAccountGroupSubmajorRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $group, $major, $id);

    public function allAccountGroups();

    public function findAcctGrp($account);
    
    public function findMajorAcctGrp($major);

    public function reload_major_account($account);
}