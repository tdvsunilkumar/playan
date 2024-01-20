<?php

namespace App\Interfaces;

interface AcctgFundCodeRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);
    
    public function validate($code, $id);
}