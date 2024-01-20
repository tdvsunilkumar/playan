<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmpHobbies extends Model
{
    public $table = 'hr_emp_hobbies';
    protected $guarded = ['id'];
    use HasFactory;
}
