<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoTfoc extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_tfocs';
    
    public $timestamps = false;
}
