<?php

namespace App\Interfaces;

interface EconRentalInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);
    
    public function allCitizens();

    public function allReceptionLocations();

    public function allServices($type);

    public function find_column($column, $id);

    public function reload_reception_class($id, $location, $reception);

    public function reload_reception_name($location);

    public function fetch_multiplier_amount($location, $reception, $reception_class);

    public function find_services($service);

    public function generate();

    public function create_transactions(array $details);

    public function update_transactions($id, array $newDetails);

    public function fetchApprovedBy($approvers);

    public function get_calendar();

    public function allDiscounts();

    public function fetch_discount($id);
}