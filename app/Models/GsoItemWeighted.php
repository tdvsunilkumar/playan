<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemWeighted extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_items_weighted';
    
    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }
}
