<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPPMPBudget extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_project_procurement_management_plans_budgets';
    
    public $timestamps = false;

    public function ppmp()
    {
        return $this->belongsTo('App\Models\GsoPPMPBudget', 'ppmp_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\CboBudgetCategory', 'budget_category_id', 'id');
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
