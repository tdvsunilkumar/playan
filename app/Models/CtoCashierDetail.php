<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoCashierDetail extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_cashier_details';
    
    public $timestamps = false;

    public function cashier()
    {
        return $this->belongsTo('App\Models\CtoCashier', 'cashier_id', 'id');
    }
}
