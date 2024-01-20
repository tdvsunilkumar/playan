<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class PayrollBreakdown extends Model
{
    use ModelUpdateCreate;
    public $table = 'hr_payroll_breakdown';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'emp_id', 'id');
    }
    public function appointment() 
    { 
        return $this->hasOne('App\Models\HR\HrAppointment', 'hr_emp_id', 'emp_id'); 
    }
    public function sl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountSubsidiaryLedger', 'sl_id', 'id');
    }
}
