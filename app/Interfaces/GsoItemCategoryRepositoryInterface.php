<?php

namespace App\Interfaces;

interface GsoItemCategoryRepositoryInterface 
{
    public function getAll();
    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $id);

    public function allGLAccounts();

    public function validate_items($id);
}