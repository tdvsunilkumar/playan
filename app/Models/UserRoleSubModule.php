<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleSubModule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'users_role_sub_modules';
    
    public $timestamps = false;
}
