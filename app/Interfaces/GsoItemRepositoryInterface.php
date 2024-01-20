<?php

namespace App\Interfaces;

interface GsoItemRepositoryInterface 
{
    public function getAll();

    public function getAllConversion($itemID);
    
    public function find($id);

    public function showData($id);

    public function create(array $details);

    public function update($id, array $newDetails);    

    public function listItems($request);

    public function listItemsConversion($request, $id);

    public function health_listItems($requests);
    
    public function newlistItems($request);

    public function validate($code, $id);

    public function allGLAccounts();

    public function allItemCategories();

    public function allItemTypes();

    public function allPurchaseTypes();

    public function allUOMs();

    public function listItemsUpload($request, $itemId);

    public function formatSizeUnits($size);

    public function delete($id);

    public function fetch_gl_via_item_category($item_category);

    public function generate_item_code($item_category);

    public function validate_item($id);

    public function validate_conversion($itemID, $baseUOM, $conversionUOM, $conversionID);

    public function create_conversion(array $details);

    public function update_conversion($id, array $newDetails);      
    
    public function find_conversion($id);

    public function allMedicalCategories();

    public function preload_uom($itemType);
}