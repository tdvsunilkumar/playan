<?php

namespace App\Interfaces;

interface CtoCollectionInterface 
{    
    public function find($id);

    public function get($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function create_details(array $details);

    public function update_details($id, array $newDetails);    

    public function listItems($request);

    public function transaction_listItems($request, $id);

    public function receipt_listItems($request, $id);

    public function allFundCodes();

    public function allCtoOfficers();

    public function get_denominations($id);

    public function validate($transdate, $officer, $fund_code, $id);

    public function generate();

    public function update_collections($collectionDetails, $collections, $id);
    
    public function check_if_details_exist($id, $billId);

    public function approvals_listItems($request, $type, $slugs, $user);

    public function validate_approver($department, $sequence, $type, $slugs, $user);

    public function find_levels($slugs, $type);

    public function fetchApprovedBy($approvers);

    public function generate_voucher($collection, $timestamp, $user);

    public function get_or_receipts($collection);

    public function get_denomination_value($collectionID, $value);

    public function get_deposits($transNo);

    public function get_deposits_detail($transNo, $type);

    public function numberTowords(float $amount);

    public function get_details($transNo);

    public function get_breakdown_details($or_no);
}