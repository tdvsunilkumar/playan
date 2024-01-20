<?php

namespace App\Interfaces;

interface AcctgGeneralJournalInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function line_listItems($request, $journalID);

    public function allFundCodes();

    public function allPayees();

    public function allDivisions();

    public function reload_fixed_asset($journalID);

    public function generateVoucherNo($fund_code, $id, $user, $timestamp);

    public function updateSeries($details);

    public function allGLAccounts();

    public function validate_entry($gl_account, $id);

    public function getTotalDebitAmount($id);

    public function getTotalCreditAmount($id);
}