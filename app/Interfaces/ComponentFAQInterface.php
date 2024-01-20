<?php

namespace App\Interfaces;

interface ComponentFAQInterface 
{    
    public function find($id);

    public function create(array $details);

    public function update($id, array $newDetails);   
    
    public function find_detail_via_column($id, $column, $value);

    public function create_details(array $details);

    public function update_details($id, array $newDetails);  

    public function listItems($request);

    public function validate($code, $id);

    public function drop_details($id, array $newDetails);

    public function find_details($id);

    public function lists($keywords, $group);

    public function allGroupMenus();

    public function update_order($request);
}