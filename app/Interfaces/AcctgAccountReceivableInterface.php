<?php

namespace App\Interfaces;

interface AcctgAccountReceivableInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);
}