<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoReceptionListDetail extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_receptions_lists_details';
    
    public $timestamps = false;
}
