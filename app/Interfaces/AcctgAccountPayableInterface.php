<?php

namespace App\Interfaces;

interface AcctgAccountPayableInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($transNo, $transType, $id);

    public function allGLAccounts();

    public function allUOMs();

    public function allEVAT();

    public function allEWT();

    public function allFundCodes();
    
    public function approve($id, array $details);

    public function disapprove($id, array $details);

    public function approve_all($request, array $details);

    public function disapprove_all($request, array $details);
    
    public function fetchApprovedBy($approvers);
}