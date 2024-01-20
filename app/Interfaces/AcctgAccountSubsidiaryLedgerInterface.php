<?php

namespace App\Interfaces;

interface AcctgAccountSubsidiaryLedgerInterface 
{
    public function getAll();
    
    public function find($id);

    public function find_current($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function validate($code, $gl_account, $id);

    public function validate_current($incomeSL, $fund, $glID, $slID, $is_debit, $current);

    public function create_current(array $details);

    public function update_current($id, array $newDetails);   
}