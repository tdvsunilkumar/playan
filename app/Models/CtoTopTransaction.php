<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoTopTransaction extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_top_transactions';
    
    public $timestamps = false;
}
