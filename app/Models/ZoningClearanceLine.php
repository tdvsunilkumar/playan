<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoningClearanceLine extends Model
{
    use HasFactory;
    protected $table = "cpdo_zoning_computation_clearance_lines";
    protected $guarded;
}

