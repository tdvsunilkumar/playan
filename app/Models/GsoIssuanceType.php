<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoIssuanceType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_issuances_types';
    
    public $timestamps = false;
}
