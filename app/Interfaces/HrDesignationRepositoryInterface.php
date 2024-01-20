<?php

namespace App\Interfaces;

interface HrDesignationRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($perPage, $startFrom, $keywords, $sortBy, $orderBy);

    public function listCount($keywords);

    public function validate($code, $id);
}