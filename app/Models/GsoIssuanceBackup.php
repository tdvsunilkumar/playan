<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoIssuanceBackup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_issuance';
    
    public $timestamps = false;
    public function acctg_departments()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'dept_code', 'id');
    }
    public function acctg_departments_divisions()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'ddiv_code', 'id');
    }
    public function issue_req_by()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'issue_requestor', 'id');
    }
    public function issue_req_position()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'issue_requestor_position', 'id');
    }
    public function approver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'issue_approver', 'id');
    }
    public function approver_position()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'issue_approver_position', 'id');
    }

}
