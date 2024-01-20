<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoReceptionList extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_receptions_lists';
    
    public $timestamps = false;
}
