<?php

namespace App\Interfaces;

interface ReportAcctgJournalInterface 
{    
    public function find($id);

    public function get($request);

    public function allFundCodes();

    public function get_prepared_by();

    public function get_certified_by();

    public function get_account_payable();

    public function get_advanced_payment();

    public function get_petty_cash();

    public function get_cash_in_bank();

    public function get_disbursements($request);

    public function get_debit_memos($request);

    public function get_check_disbursement($voucherNo, $slID);

    public function get_cash_disbursement($voucherNo, $glID);

    public function get_debit_memo($voucherNo, $slID);
}