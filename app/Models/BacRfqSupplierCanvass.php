<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacRfqSupplierCanvass extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_rfqs_suppliers_canvass';
    
    public $timestamps = false;

    public function rfq()
    {
        return $this->belongsTo('App\Models\BacRfq', 'rfq_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\GsoSupplier', 'supplier_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }
}
