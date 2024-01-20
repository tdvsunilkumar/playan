<?php

namespace App\Interfaces;

interface CtoDisburseInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function allPettyVouchers();

    public function line_listItems($request, $id);

    public function view_available_obligation_requests($disbursementID, $department);

    public function check_if_exist($disbursementID, $obrID);

    public function find_line($id);

    public function create_line(array $details);

    public function update_line($id, array $newDetails);    

    public function computeTotalAmount($disbursementID);

    public function generate();

    public function disburse($disbursementID, $timestamp, $user);

    public function fetchApprovedBy($approvers);

    public function allDepartments();

    public function find_via_column($column, $data);

    public function get_details($disbursementID);

    public function get_obligation_details($obligationID);

    public function approvals_listItems($request, $type, $slugs, $user);

    public function validate_approver($department, $sequence, $type, $slugs, $user);

    public function find_levels($slugs, $type);

    public function reimburse($allotmentID, $timestamp, $user);
}