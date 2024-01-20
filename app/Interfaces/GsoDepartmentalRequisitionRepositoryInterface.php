<?php

namespace App\Interfaces;

interface GsoDepartmentalRequisitionRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request, $user = '');

    public function approval_listItems($request, $type, $slugs, $user);

    public function approval_listItems2($request);

    public function listItemLines($request, $id);
    
    public function validate($code, $id);

    public function allDepartments();

    public function allDepartmentsWithRestriction($user);

    public function allDesignations();

    public function allEmployees();

    public function allRequestTypes();

    public function allPurchaseTypes();

    public function reload_itemx($fund_code, $department, $division, $requestDate, $category);

    public function reload_items($purchase_type);

    public function reload_uom($item);

    public function reload_employees($department);

    public function reload_divisions($dpartment);

    public function reload_designation($employee);

    public function generate_control_no($department, $requested_date);

    public function createItem(array $details);

    public function updateLine($id, array $newDetails);  

    public function findItem($itemId);

    public function computeTotalAmount($requisitionId);

    public function findLine($id);

    public function removeLine($id);

    public function updateLines($id, array $newDetails); 

    public function getAllItems($id);

    public function fetchApprovedBy($approvers);

    public function disapprove_request(array $details2);

    public function fetch_remarks($id);

    public function reload_unit_cost($item);

    public function fetch($id);

    public function get_departmental_request_approvers($id);

    public function allFundCodes();

    public function find_levels($slugs, $type);

    public function validate_approver($department, $sequence, $type, $slugs, $user);

    public function validate_item_request($line, $fund, $year, $division, $category, $item, $quantity);

    public function track_dept_request($requisitionId);

    public function track_request($requisitionId);
}