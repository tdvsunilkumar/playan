<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoMedicalItemCategory extends Model
{
    protected $guarded = ['id'];

    public $table = 'ho_medical_item_categories';
    
    public $timestamps = false;

    public function allMedicalCategories($vars = '')
    {
        $categories = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $cats = array();
        if (!empty($vars)) {
            $cats[] = array('' => 'select a '.$vars);
        } else {
            $cats[] = array('' => 'select a fund code');
        }
        foreach ($categories as $category) {
            $cats[] = array(
                $category->id => $category->code . ' - ' . $category->name
            );
        }

        $categories = array();
        foreach($cats as $cat) {
            foreach($cat as $key => $val) {
                $categories[$key] = $val;
            }
        }

        return $categories;
    }
}
