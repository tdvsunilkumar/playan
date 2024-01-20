<?php

namespace App\Interfaces;

interface GsoIssuanceInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function listItems($request);

    public function approval_listItems($request);
    
    public function item_listItems($request, $id);

    public function allUsers();

    public function generate_control_no();

    public function view_available_items($issuanceID, $inventory, $poNo);

    public function check_quantity_withdrawn($issuanceID, $itemID, $inventory, $poNo = 0);

    public function check_all_quantity_withdrawn($issuanceID, $itemID, $inventory, $poNo = 0);

    public function allPrPo();

    public function post($request, $issuanceID, $inventory, $timestamp, $user);

    public function getTotalAmount($issuanceID);

    public function validate_issuance($issuanceID);

    public function credit_inventory($issuanceID, $timestamp, $user);

    public function update_line($lineID, array $details);

    public function posted_items_via_control_no($controlNo, $type);

    public function find_issuance($controlNo);

    public function numberTowords(float $amount);

    public function allEmployees();

    public function validate_par($issuanceID);

    public function fetchApprovedBy($approvers);
}
