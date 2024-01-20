<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_item_categories';
    
    public $timestamps = false;

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function allItemCategories($vars = '')
    {
        $categories = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $cats = array();
        if (!empty($vars)) {
            $cats[] = array('' => 'select a '.$vars);
        } else {
            $cats[] = array('' => 'select a category');
        }
        foreach ($categories as $category) {
            $cats[] = array(
                $category->id => $category->code . ' - ' . $category->description
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
