<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPreRepairInspectionItem extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_pre_repair_inspection_items';
    
    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo('App\Models\GsoPreRepairInspectionRequest', 'repair_id', 'id');
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
