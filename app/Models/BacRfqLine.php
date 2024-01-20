<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacRfqLine extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_rfqs_lines';
    
    public $timestamps = false;

    public function rfq()
    {
        return $this->belongsTo('App\Models\BacRfq', 'rfq_id', 'id');
    }

    public function purchase_request()
    {
        return $this->belongsTo('App\Models\GsoPurchaseRequest', 'purchase_request_id', 'id');
    }
}
