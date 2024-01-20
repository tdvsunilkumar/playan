<?php

namespace App\Interfaces;

interface BacRequestForQuotationInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function listItems($request);

    public function approval_listItems($request);

    public function allFundCodes();

    public function allExpendableWarranties();

    public function allNonExpendableWarranties();

    public function allPriceValidities();

    public function pr_listItems($request, $id);

    public function supplier_listItems($request, $id);

    public function item_listItems($request, $id);

    public function view_available_suppliers($rfqID);

    public function create_supplier(array $details);

    public function update_supplier($id, array $newDetails);  

    public function update_supplier_canvass($rfqID, $supplierID, array $newDetails);

    public function view_available_purchase_requests($rfqID, $fundCode);

    public function find_supplier($rfqID, $supplierID);  
    
    public function create_pr(array $details);

    public function update_pr($id, array $newDetails);  

    public function find_pr($rfqID, $prID);  

    public function generate_control_no();

    public function fetch_items($rfqID);

    public function find_canvass($rfqID, $supplierID, $itemID);

    public function create_canvass(array $details);

    public function update_canvass($id, array $newDetails);  

    public function computeTotalAmount($rfqID, $supplierID);

    public function computeTotalBudget($rfqID);

    public function validate_supplier($rfqID);

    public function get_agencies($rfqID);

    public function update_request($id, array $newDetails);  

    public function generateQuotationNo();

    public function fetchApprovedBy($approvers);
    
    public function kenneth($params);

    public function find_rfq_via_control_no($controlNo);

    public function updateRequest($rfqID, array $newDetails);
    
    public function updateLines($rfqID, array $newDetails);

    public function disapprove_request($rfqID, array $details);

    public function find_rfq_via_column($column, $data);

    public function find_rfq_lines_via_column($column, $data);

    public function find_rfq_suppliers_via_column($column, $data, $supplierID = '');

    public function numberTowords(float $amount);

    public function getTotalCanvass($rfqID, $supplierID);
    
    public function getQuotationNo($rfqID);

    public function allPurchaseTypes();
}