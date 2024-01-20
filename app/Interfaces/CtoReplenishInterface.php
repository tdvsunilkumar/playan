<?php

namespace App\Interfaces;

interface CtoReplenishInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function approvals_listItems($request, $type, $slugs, $user);

    public function allPettyVouchers();

    public function line_listItems($request, $id);

    public function view_available_disbursements($replenishID);

    public function check_if_exist($replenishID, $obrID);

    public function find_line($id);

    public function create_line(array $details);

    public function update_line($id, array $newDetails);    

    public function computeTotalAmount($replenishID);

    public function generate();

    public function replenish($replenishID, $timestamp, $user, $newDetails, $auto = 0);

    public function fetchApprovedBy($approvers);
}