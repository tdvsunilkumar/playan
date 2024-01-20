<?php

namespace App\Interfaces;

interface GsoPPMPInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function update_division_status($id, array $newDetails);

    public function approvals_listItems($request, $type, $slugs, $user);

    public function listItems($request, $user);

    public function app_listItems($request);

    public function validate($fund_code, $department, $budget, $category, $id);

    public function allDepartmentsWithRestriction($user);

    public function allFundCodes();

    public function allItems();

    public function allItemsViaGL($gl_account, $field);

    public function allDivisions();

    public function allBudgetCategories();

    public function fetch_item_details($id);

    public function generate_control_no($year);

    public function getAllItems();

    public function update_lines($ppmpID, $request, $timestamp, $user);

    public function update_lines2($ppmpID, $request, $timestamp, $user);

    public function find_lines($ppmpID, $gl_account, $division = '');

    public function find_budgets($ppmpID);

    public function get_budgets($ppmpID);

    public function fetch_division_status($ppmpID, $division);

    public function lock_division($ppmpID, $division, $timestamp, $user);

    public function check_if_division_locked($ppmpID, $division, $budget = 0);

    public function validate_if_budget_exist($fund_code, $department, $budget, $category);

    public function remove_lines($id, array $newDetails);

    public function validate_approver($id, $sequence, $type, $slugs, $user);
    
    public function find_levels($slugs, $type);

    public function fetchApprovedBy($approvers);

    public function disapprove($id, array $details);

    public function validate_division_status($ppmpID);

    public function copy($copyID, $ppmpID, $timestamp, $user);

    public function validate_item_request($month, $item, $fund_code, $department, $division, $year);

    public function validate_item_details($ppmpID, $division, $itemID);

    public function validate_item_removal($id);

    public function fetch_budget_lists($fund, $department, $budget_category, $budget_year);

    public function create_budget_plan(array $details);

    public function update_budget_plan($id, array $newDetails);   

    public function check_if_budget_plan($ppmpID, $gl_account, $division, $category);

    public function find_budget_plan($ppmpID, $gl_account, $division, $category);
}