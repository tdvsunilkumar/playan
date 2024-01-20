<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPPMPStatus extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_project_procurement_management_plans_status';
    
    public $timestamps = false;

    public function pmpp()
    {
        return $this->belongsTo('App\Models\GsoPPMPDetail', 'ppmp_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }
}
