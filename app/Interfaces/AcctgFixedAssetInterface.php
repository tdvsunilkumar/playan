<?php

namespace App\Interfaces;

interface AcctgFixedAssetInterface 
{    
    public function find($id);
    
    public function get($id);

    public function find_history($id);
    
    public function get_history($id);

    public function create(array $details);

    public function create_history(array $details);

    public function update($id, array $newDetails);    
    
    public function update_history($id, array $newDetails);    

    public function listItems($request);

    public function validate($code, $id);

    public function allProperties();

    public function allGLAccounts();

    public function allDepreciations();

    public function allEmployees();

    public function allCategories();

    public function reload_items_via_gl($gl_account, $field);

    public function generate();

    public function depreciate($timestamp);
}