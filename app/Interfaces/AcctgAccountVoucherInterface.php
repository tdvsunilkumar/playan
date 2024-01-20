<?php

namespace App\Interfaces;

interface AcctgAccountVoucherInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function remove_payables($id, array $newDetails); 
    
    public function remove_collections($id, array $newDetails);   

    public function listItems($request, $type = 1);

    public function payables_listItems($request, $voucherID);

    public function deductions_listItems($request, $voucherID);

    public function collections_listItems($request, $voucherID);

    public function approvals_payables_listItems($request);

    public function payments_listItems($request, $voucherID);

    public function approvals_payments_listItems($request);

    public function allGLAccounts();

    public function allSLAccounts();

    public function allBankSLAccounts();

    public function allPaymentSLAccounts();

    public function allPaymentType();

    public function allUOMs();

    public function allEVAT();

    public function allEWT();

    public function allPayees();

    public function generateVoucherNo($fund_code, $id, $user, $timestamp);

    public function view_available_payables($voucherID, $fund_code, $payee, $replenish);

    public function view_available_incomes($voucherID, $fund_code, $payee);

    public function add_payables($request, $voucherID);

    public function add_incomes($request, $voucherID);

    public function update_vouchers($voucherID);

    public function update_income_vouchers($voucherID);

    public function find_sl_bank($slID);

    public function add_payments($request, $voucherID, $user, $timestamp);

    public function update_payments($request, $paymentID, $user, $timestamp);

    public function update_paymentx($request, $paymentID, $user, $timestamp);

    public function find_payments($paymentID);

    public function remove_payments($id, array $newDetails);    

    public function get_payables($voucherNo, $posted = 1);

    public function get_incomes($voucherNo, $deduction, $posted = 1);

    public function get_centre_payables($voucherNo);
    
    public function get_centre_ewt_payables($voucherNo, $centre);

    public function find_voucher($voucher);

    public function allFundCodes();

    public function find_payable_gl();

    public function find_treasury_gl();

    public function find_due_to_bir_gl();

    public function get_sum_deposits($voucher, $posted = 1);

    public function get_sum_payables($voucher);

    public function get_due_ewt_payables($voucher, $posted = 1);

    public function get_due_evat_payables($voucher, $posted = 1);

    public function get_gl_payments($voucher, $type, $reference = '', $posted = 1, $collection = 0);

    public function get_sl_payments($voucher, $gl_account, $reference = '', $posted = 1);

    public function remove_all_payables($request, array $details);

    public function remove_all_collections($request, array $details);

    public function send_all_payables($request, array $details);

    public function send_all_collections($request, array $details);

    public function send_all_deductions($request, array $details);

    public function remove_all_payments($request, array $details);

    public function send_all_payments($request, array $details);

    public function numberTowords(float $amount);

    public function updateSeries($details);

    public function get_obligation_no($voucher);

    public function get_obligation_no_via_disbursement($voucher, $reference = '');

    public function get_checque_no($voucher);

    public function get_invoice_no($voucher);

    public function validate_voucher($voucher);

    public function get_payable_lines($voucher, $reference = '', $posted = 1);

    public function fetch_voucher_print($id, $type);

    public function update_voucher_date($id, $type, $date, $preparedBy, $approvedBy, $timestamp);

    public function get_voucher_approvers();

    public function approval_docListItems($request, $user, $type);

    public function fetch_document_status($id, $type);

    public function fetch_document_remarks($id, $type);

    public function fetch_document($id, $type);

    public function find_document($id);

    public function update_document($id, array $newDetails);    

    public function fetchApprovedBy($approvers);

    public function get_current_tax($voucher, $posted = 1);

    public function get_sl_incomes($voucher, $deduction, $gl_account_id, $posted = 1);

    public function get_deposited($voucher);

    // public function get_gl_rpt_current_tax($fund, $sl);

    public function get_rpt_current_tax($fund, $sl);

    public function get_gl_rpt_current_tax($fund, $gl, $sl);
}