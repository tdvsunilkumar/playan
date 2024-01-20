<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoReplenishDetail extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_replenish_details';
    
    public $timestamps = false;

    public function replenish()
    {
        return $this->belongsTo('App\Models\CtoReplenish', 'replenish_id', 'id');
    }

    public function disburse()
    {
        return $this->belongsTo('App\Models\CtoDisburse', 'disburse_id', 'id');
    }
}
