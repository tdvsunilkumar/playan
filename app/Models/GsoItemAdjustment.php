<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemAdjustment extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_item_adjustments';
    
    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\GsoItemAdjustmentType', 'adjustment_type_id', 'id');
    }

    public function requestor()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'sent_by', 'user_id');
    }
}
