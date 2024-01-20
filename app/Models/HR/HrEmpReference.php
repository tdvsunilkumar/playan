<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmpReference extends Model
{
    public $table = 'hr_emp_reference';
    protected $guarded = ['id'];
    use HasFactory;
}
