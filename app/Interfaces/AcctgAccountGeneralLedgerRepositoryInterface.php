<?php

namespace App\Interfaces;

interface AcctgAccountGeneralLedgerRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function find_sl($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function sub_listItems($request, $id);

    public function validate($code, $group, $major, $id);

    public function allAccountGroups();

    public function allBanks();

    public function allFundCodes();

    public function findAcctGrp($account);
    
    public function findMajorAcctGrp($major);

    public function findSubMajorAcctGrp($submajor);

    public function reload_major_account($account);
    
    public function reload_submajor_account($account, $major);

    public function reload_parent($gl, $sl);

    public function current_listItems($request, $id);

    public function allGLSLAccounts();
}