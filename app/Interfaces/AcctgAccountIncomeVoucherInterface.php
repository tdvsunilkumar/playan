<?php

namespace App\Interfaces;

interface AcctgAccountIncomeVoucherInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function remove_payables($id, array $newDetails);   

    public function listItems($request);

    public function payables_listItems($request, $voucherID);

    public function approvals_payables_listItems($request);

    public function payments_listItems($request, $voucherID);

    public function approvals_payments_listItems($request);

    public function allGLAccounts();

    public function allSLAccounts();

    public function allPaymentType();

    public function allUOMs();

    public function allEVAT();

    public function allEWT();

    public function allPayees();

    public function generateVoucherNo($fund_code, $id, $user, $timestamp);

    public function view_available_payables($voucherID, $fund_code, $payee);

    public function add_payables($request, $voucherID);

    public function update_vouchers($voucherID);

    public function find_sl_bank($slID);

    public function add_payments($request, $voucherID, $user, $timestamp);

    public function update_payments($request, $paymentID, $user, $timestamp);

    public function find_payments($paymentID);

    public function remove_payments($id, array $newDetails);    

    public function get_payables($voucherNo);

    public function get_centre_payables($voucherNo);
    
    public function get_centre_ewt_payables($voucherNo, $centre);

    public function find_voucher($voucher);

    public function allFundCodes();

    public function find_payable_gl();

    public function find_due_to_bir_gl();

    public function get_sum_payables($voucher);

    public function get_due_ewt_payables($voucher);

    public function get_due_evat_payables($voucher);

    public function get_gl_payments($voucher, $type, $reference = '');

    public function get_sl_payments($voucher, $gl_account, $reference = '');

    public function remove_all_payables($request, array $details);

    public function send_all_payables($request, array $details);

    public function remove_all_payments($request, array $details);

    public function send_all_payments($request, array $details);

    public function numberTowords(float $amount);

    public function updateSeries($details);

    public function get_obligation_no($voucher);

    public function get_obligation_no_via_disbursement($voucher, $reference = '');

    public function get_checque_no($voucher);

    public function get_invoice_no($voucher);

    public function validate_voucher($voucher);

    public function get_payable_lines($voucher, $reference = '');
}