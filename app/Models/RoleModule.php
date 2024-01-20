<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'role_modules';
    
    public $timestamps = false;
}
