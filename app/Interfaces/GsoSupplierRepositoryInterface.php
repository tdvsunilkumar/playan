<?php

namespace App\Interfaces;

interface GsoSupplierRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details, $product_lines, $timestamp, $user);

    public function update($id, array $newDetails, $product_lines, $timestamp, $user);
    
    public function toggleUpdate($id, array $newDetails);

    public function listItems($request);
    
    public function contact_listItems($request, $id);

    public function upload_listItems($request, $id);

    public function validate($code, $id);
    
    public function getProductLine($supplier);

    public function allBarangays();

    public function lastId();

    public function allProductLines();

    public function formatSizeUnits($size);

    public function delete($id);

    public function findLines($supplierId);

    public function findContacts($supplierId);
    
    public function validateContactPerson($supplierId, $contact_person, $id);

    public function createContactPerson(array $details);

    public function updateContactPerson($id, array $newDetails); 

    public function findContactPerson($id);

    public function generate_code();

    public function allEWT();

    // public function allEVAT();
}