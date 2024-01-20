<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class HrEmpCivilService extends Model
{
    public $table = 'hr_emp_civil_service_eligibility';
    protected $guarded = ['id'];
    public $timestamps = false;
}
