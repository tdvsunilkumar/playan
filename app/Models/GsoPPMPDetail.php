<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPPMPDetail extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_project_procurement_management_plans_details';
    
    public $timestamps = false;

    public function ppmp()
    {
        return $this->belongsTo('App\Models\GsoPPMP', 'ppmp_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }
}
