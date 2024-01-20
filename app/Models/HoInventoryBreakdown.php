<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoInventoryBreakdown extends Model
{
    use HasFactory;
    protected $guarded;
    protected $table = "ho_inventory_breakdowns";
}
