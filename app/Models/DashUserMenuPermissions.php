<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashUserMenuPermissions extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'dashboard_user_menu_permissions';
    
    public $timestamps = false;
}
