<?php

namespace App\Interfaces;

interface EconCemeteryInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function approval_listItems($requests);

    public function payment_listItems($requests, $id);

    public function allCitizens();

    public function allCemeteryLocations();

    public function allCemeteryNames();

    public function allServices($type);

    public function find_column($column, $id);

    public function allCemeteryStyles();

    public function reload_cemetery_lot($id, $location, $cemetery, $style);

    public function reload_cemetery_name($location);

    public function find_services($service);

    public function generate();

    public function create_transactions(array $details);

    public function update_transactions($id, array $newDetails);

    public function fetchApprovedBy($approvers);

    public function create_payments(array $details);
}