<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoPaymentOrRegister extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_payment_or_registers';
    
    public $timestamps = false;
}
