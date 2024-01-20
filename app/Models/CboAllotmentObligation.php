<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboAllotmentObligation extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_allotment_obligations';
    
    public $timestamps = false;

    public function requisition()
    {
        return $this->belongsTo('App\Models\GsoDepartmentalRequisition', 'departmental_request_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }

    public function payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'payee_id', 'id');
    }

    public function obligation()
    {   
        return $this->hasOne('App\Models\CboAllotmentObligationRequest', 'allotment_id', 'id');
    }

    public function pur_request()
    {   
        return $this->hasOne('App\Models\GsoPurchaseRequest', 'allotment_id', 'id');
    }

    public function fund_by()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'funding_by', 'id');
    }

    public function fund_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'funding_designation', 'id');
    }

    public function approve_by()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'approval_by', 'id');
    }

    public function approve_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'approval_designation', 'id');
    }

    public function type()
    {   
        return $this->belongsTo('App\Models\CboObligationType', 'obligation_type_id', 'id');
    }

    public function requestor()
    {   
        return $this->belongsTo('App\Models\HrEmployee', 'employee_id', 'id');
    }

    public function allotments()
    {   
        return $this->hasMany('App\Models\CboAllotmentBreakdown', 'allotment_id', 'id');
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
