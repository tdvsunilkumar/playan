<?php

namespace App\Interfaces;

interface CboBudgetInterface 
{
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);  

    public function listItems($request, $year);

    public function approval_listItems($request);

    public function line_listItems($request, $id);

    public function allGLAccounts();

    public function validate_budget($budgetID);

    public function validate_breakdown($gl_account, $category, $budgetID, $id);

    public function create_breakdown(array $details);

    public function find_breakdown($id);

    public function update_breakdown($id, array $newDetails);  

    public function getTotalAmount($budgetID);

    public function fetchApprovedBy($approvers);

    public function year_lists();

    public function copy($lists, $year, $timestamp, $user);

    public function allBudgetCategories();

    public function insertAlignment(array $details);

    public function update_breakdowns($budgetID, array $newDetails);  
}