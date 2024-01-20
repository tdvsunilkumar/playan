<?php

namespace App\Interfaces;

interface GsoPurchaseOrderInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function update_request($id, array $newDetails);  

    public function disapprove_request($id, array $details); 

    public function listItems($request);

    public function posting_listItems($requests);

    public function approval_listItems($request, $user = '');

    public function all_available_rfq($id);

    public function allPoTypes();

    public function allProcurementModes();

    public function allPaymentTerms();

    public function allDeliveryTerms();

    public function pr_listItems($request, $id);

    public function getSupplier($rfqID);

    public function generate_po_no();

    public function updatePoQuantity($rfqID);

    public function getItemCost($rfqID, $itemID);

    public function fetchApprovedBy($approvers);

    public function update_srp($poID);

    public function view_available_posting($poID);

    public function find_inspect($poID);

    public function create_posting($request, $poID, $timestamp, $user);

    public function allUsers();

    public function find_via_column($column, $value);

    public function item_list_via_po_num($poNum);

    public function getAlobs($poNum);

    public function getAlobsAmount($poNum);

    public function getPrNos($poNum);

    public function allEmployees();

    public function fetch_designation($employee);

    public function find_posting($poNum, $sequence = '');

    public function posted_items_via_po_num($poNum, $sequence = '');

    public function getPoDepartments($poNum);

    public function getPoDivisions($poNum);

    public function numberTowords(float $amount);

    public function find_po($poID);

    public function getLocalAddress();
}