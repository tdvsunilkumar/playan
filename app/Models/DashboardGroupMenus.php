<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardGroupMenus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'dashboard_group_menus';
    
    public $timestamps = false;
}
