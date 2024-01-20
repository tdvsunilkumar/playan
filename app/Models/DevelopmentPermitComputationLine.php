<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevelopmentPermitComputationLine extends Model
{
    use HasFactory;
    protected $guarded;
    protected $table = 'cpdo_development_permit_computation_lines';
}
