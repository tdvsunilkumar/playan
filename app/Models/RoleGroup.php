<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'role_groups';
    
    public $timestamps = false;
}
