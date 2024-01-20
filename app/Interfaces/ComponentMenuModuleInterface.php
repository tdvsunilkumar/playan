<?php

namespace App\Interfaces;

interface ComponentMenuModuleInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $id);

    public function count();

    public function findBy($column, $data);

    public function allGroupMenus();

    public function update_order($request);
}