<?php

namespace App\Interfaces;

interface GsoInventoryInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function history_listItems($request, $id);

    public function allAdjustmentTypes();

    public function create_request(array $details);

    public function generateNo();

    public function fetchApprovedBy($approvers);

    public function update_request($adjustmentID, array $newDetails);   

    public function disapprove_request($adjustmentID, array $newDetails);

    public function find_adjustment($id);
}