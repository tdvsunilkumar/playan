<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HR\Payroll;

class CboObligationPayroll extends Model
{
    use HasFactory;
    public $table = 'cbo_obligation_payroll';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function payroll() 
    { 
        return $this->hasOne('App\Models\HR\Payroll', 'hrpr_payroll_no', 'payroll_no'); 
    }
    public function payrolls() 
    { 
        return $this->hasMany('App\Models\HR\Payroll', 'hrpr_payroll_no', 'payroll_no'); 
    }
    public function payroll_breakdown() 
    { 
        return $this->hasMany('App\Models\PayrollBreakdown', 'payroll_no', 'payroll_no'); 
    }
    public function cutoff() 
    { 
        return $this->hasOne('App\Models\HR\CuttoffPeriod', 'id', 'cutoff_id'); 
    }
    public function emp_type() 
    { 
        return $this->hasOne('App\Models\HR\HrAppointmentStatus', 'id', 'employee_type'); 
    }
}
