<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseOrderPostingLine extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_purchase_orders_posting_lines';
    
    public $timestamps = false;

    public function posting()
    {
        return $this->belongsTo('App\Models\GsoPurchaseOrderPosting', 'posting_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }
}
