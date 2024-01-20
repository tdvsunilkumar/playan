<?php

namespace App\Interfaces;

interface CboPayeeInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($perPage, $startFrom, $keywords, $sortBy, $orderBy);

    public function listCount($keywords);

    public function validate($paye_type, $id);

    public function allAccountGroups();

    public function allEmpData();

    public function allSupplier();

    public function findAcctGrp($account);
    
    public function findMajorAcctGrp($major);

    public function reload_major_account($account);
    
    public function allBarangays();
}