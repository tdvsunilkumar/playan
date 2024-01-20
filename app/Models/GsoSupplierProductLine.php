<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoSupplierProductLine extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_suppliers_product_lines';
    
    public $timestamps = false;

    public function product_line()
    {
        return $this->belongsTo('App\Models\GsoProductLine', 'product_line_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\GsoSupplier', 'supplier_id', 'id');
    }
}
