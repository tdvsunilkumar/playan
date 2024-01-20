<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmployeeDepartmentalAccess extends Model
{
    protected $guarded = ['id'];

    public $table = 'hr_employees_departmental_access';
    
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'employee_id', 'id');
    }
}
