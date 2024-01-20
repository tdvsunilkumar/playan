<?php

namespace App\Interfaces;

interface GsoObligationRequestInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request, $user = '');

    public function approval_listItems($request, $user = '');

    public function listItemLines($request, $id);
    
    public function validate($code, $id);

    public function allDepartments();

    public function allDepartmentsWithRestriction($user);

    public function allDesignations();

    public function allEmployees();

    public function allCboEmployees();

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

    public function findAlobViaControlNo($controlNo);

    public function update_alob($id, array $newDetails);    

    public function validate_funds($type);

    public function validate_gl_accounts($type);

    public function find_gl_code($controlNo);
}