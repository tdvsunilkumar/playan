<?php

namespace App\Interfaces;

interface ReportAcctgTrialBalanceInterface 
{    
    public function find($id);

    public function allFundCodes();

    public function reload($type);

    public function get($request);
    
    public function get_prepared_by();

    public function get_certified_by();

    public function allGLAccounts();
}