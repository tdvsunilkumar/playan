<?php

namespace App\Interfaces;

interface BacAbstractOfCanvassInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);   

    public function listItems($request);

    public function approval_listItems($request);

    public function pr_listItems($request, $id);

    public function supplier_listItems($request, $id);

    public function item_listItems($request, $id);

    public function find_abstract($rfqID);

    public function committee_listItems($request, $id);

    public function view_available_committees($rfqID);

    public function update_request($rfqID, array $details);

    public function fetchApprovedBy($approvers);

    public function updateRequest($rfqID, array $newDetails);
    
    public function updateLines($rfqID, array $newDetails);

    public function disapprove_request($rfqID, array $details);
    
    public function get_rfq_details($controlNo);

    public function find_abstract_via_column($column, $data);
}