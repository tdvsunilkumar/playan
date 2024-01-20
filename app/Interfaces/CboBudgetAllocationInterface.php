<?php

namespace App\Interfaces;

interface CboBudgetAllocationInterface 
{
    public function getAll();
    
    public function find($id);

    public function find_alob($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function update_request($id, array $newDetails);   

    public function updateRequest($id, array $newDetails);

    public function listItems($request);

    public function approval_listItems($request, $type, $slugs, $user);

    public function listItemLines($request, $id);
    
    public function validate($code, $id);

    public function allDepartments();

    public function allDesignations();

    public function allEmployees();

    public function allRequestTypes();

    public function allPurchaseTypes();

    public function reload_items($purchase_type);

    public function reload_uom($item);

    public function reload_employees($department);

    public function reload_divisions($dpartment);

    public function reload_designation($employee);

    public function generate_control_no($department);

    public function createItem(array $details);

    public function updateLine($id, array $newDetails);  

    public function findItem($itemId);

    public function computeTotalAmount($requisitionId);

    public function findLine($id);

    public function removeLine($id);

    public function updateLines($id, array $newDetails); 

    public function findAlobViaPr($id);

    public function get_alob($id);

    public function allob_divisions();

    public function allFundCodes();

    public function allPayees();

    public function fetch_payee_details($id, $column);

    public function view_alob_lines($id, $department, $divisionm, $year, $fund, $category);

    public function view_alob_lines2($id, $department, $divisionm, $year, $fund, $category);

    public function allBudgetYear();
    
    public function listAlobLines($request, $id);

    public function listAlobLines2($request, $id);

    public function update_row($requisitionID, $breakdown, $gl_account, $allocated = 0, $timestamp, $user);

    public function update_row2($allotmentID, $breakdown, $gl_account, $allocated = 0, $timestamp, $user);

    public function computeBreakdownTotalAmount($allotmentID);

    public function updateBreakdownLine($id, array $newDetails); 

    public function findBreakdownLine($id);     
    
    public function updateAllotment($id, array $newDetails); 

    public function fetchBudgetSeriesNo($id);

    public function create_request(array $details);

    public function generateBudgetControlNo($year);

    public function fetchApprovedBy($approvers);

    public function fetch_remarks($id);

    public function find_obligation($id);

    public function fetch_designation($employee);

    public function fetchAlobNo($id);

    public function validate_approver($id, $sequence, $type, $slugs, $user);

    public function find_levels($slugs, $type);
}