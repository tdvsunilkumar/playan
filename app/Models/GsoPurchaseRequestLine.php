<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseRequestLine extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_purchase_requests_lines';
    
    public $timestamps = false;

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }
}
