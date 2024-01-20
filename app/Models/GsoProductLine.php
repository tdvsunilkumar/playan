<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoProductLine extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_product_lines';
    
    public $timestamps = false;

    public function allProductLines($vars = '')
    {
        $product_lines = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $prods = array();
        foreach ($product_lines as $product_line) {
            $prods[] = array(
                $product_line->id => $product_line->description
            );
        }

        $product_lines = array();
        foreach($prods as $prod) {
            foreach($prod as $key => $val) {
                $product_lines[$key] = $val;
            }
        }

        return $product_lines;
    }
}
