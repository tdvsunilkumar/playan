<?php

namespace App\Interfaces;

interface CboObligationTypeInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $id);

    public function allFundCodes();

    public function allGLAccounts();
}