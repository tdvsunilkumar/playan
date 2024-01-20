<?php

namespace App\Interfaces;

interface ComponentMenuGroupInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $id);

    public function count();

    public function findBy($column, $data);

    public function update_order($request);

    public function validate_slug($slug, $group, $module, $sub_module);

    public function find_slugID($group, $module, $sub_module);

    public function createSlug(array $details);

    public function updateSlug($id, array $newDetails);    

    public function updateAllSlugs($group, $module, array $groupSlug);
}