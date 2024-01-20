<?php

namespace App\Interfaces;

interface GsoWasteMaterialInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function get_po_reference_no($poID, $itemID);
}