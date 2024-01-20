<?php

namespace App\Interfaces;

interface GsoPreRepairInspectionInterface 
{    
    public function find($id);

    public function findItem($id);

    public function getItem($id);

    public function create(array $details);

    public function createItem(array $details);

    public function update($id, array $newDetails);    

    public function updateItem($id, array $newDetails);    

    public function listItems($request);

    public function inpsection_listItems($request);

    public function approvals_listItems($request, $type, $slugs, $user);

    public function approvals2_listItems($request, $type, $slugs, $user);

    public function allFixedAssets();

    public function history_listItems($request, $id, $fixedAsset = 0);

    public function item_listItems($request, $id);

    public function allEmployees();

    public function generate();

    public function find_levels($slugs, $type);

    public function validate_approver($department, $sequence, $type, $slugs, $user);

    public function fetchApprovedBy($approvers);

    public function allItems();

    public function allUOMs();

    public function getItems($item);

    public function validate($repairID);
}