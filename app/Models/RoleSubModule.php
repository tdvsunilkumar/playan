<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSubModule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'role_sub_modules';
    
    public $timestamps = false;
}
