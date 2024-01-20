<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacResolution extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_resolution';
    
    public $timestamps = false;

    public function rfq()
    {
        return $this->belongsTo('App\Models\BacRfq', 'rfq_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
