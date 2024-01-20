<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoDepartmentalRequisition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_departmental_requests';
    
    public $timestamps = false;

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }

    public function fund()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function req_type()
    {
        return $this->belongsTo('App\Models\GsoPurchaseRequestType', 'request_type_id', 'id');
    }

    public function pur_type()
    {
        return $this->belongsTo('App\Models\GsoPurchaseType', 'purchase_type_id', 'id');
    }
    
    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'employee_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'designation_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function obligation()
    {   
        return $this->hasOne('App\Models\CboAllotmentObligation', 'departmental_request_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
