<?php

namespace App\Interfaces;

interface AcctgAccountDisbursementInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    
    
    public function approvals_listItems($request);
    
    public function approve($id, array $details);

    public function disapprove($id, array $details);

    public function approve_all($request, array $details);

    public function disapprove_all($request, array $details);
    
    public function fetchApprovedBy($approvers);
}