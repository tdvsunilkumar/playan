<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Policy extends Model
{
    protected $guarded = ['id'];
    public $table = 'hr_system_policy';
    public $timestamps = false;
}
