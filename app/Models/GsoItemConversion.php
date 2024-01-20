<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemConversion extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_items_conversions';
    
    public $timestamps = false;

    public function based()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'based_uom', 'id');
    }

    public function conversion()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'conversion_uom', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }
}
