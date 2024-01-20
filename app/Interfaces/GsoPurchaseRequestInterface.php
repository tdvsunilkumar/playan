<?php

namespace App\Interfaces;

interface GsoPurchaseRequestInterface 
{
    public function find($id);

    public function create($request, $id, $user, $timestamp);

    public function update_pr_via_alob($request, $requisitionID, $user, $timestamp);    

    public function update($id, array $newDetails, $allotmentID = 0);   

    public function updateRequest($id, array $newDetails);

    public function listItems($request);

    public function listItemLines2($request, $id);

    public function find_via_pr($requisitionID);

    public function find_pr_via_alob($allotmentID);

    public function fetchPurchaseRequestNo();

    public function update_item_line($requestItemID, $column, $data, $user, $timestamp);

    public function printdata($prNo);

    public function validate_pr($allotmentID);

    public function disapprove_request(array $details);

    public function find_via_column($column, $value);

    public function item_list_via_pr_num($prNum);

    public function numberTowords(float $amount);

    public function update_alob($id, array $newDetails);

    public function allUOMs();

    public function create_pr_line(array $details);

    public function modify_pr_line($id, array $newDetails);

    public function find_pr_line($id);

    public function fetch_amount($allotmentID);

    public function updatePrLines($id, array $newDetails);

    public function approval_listItems($request, $type, $slugs, $user);
    
    public function validate_approver($id, $sequence, $type, $slugs, $user);

    public function find_levels($slugs, $type);
}