<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboBudget extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_budgets';
    
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }
    
    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function allBudgetYear()
    {
        $budget_years = self::where(['is_active' => 1])->distinct()->get(['budget_year']);

        $buds = array();
        $buds[] = array('' => 'select a budget year');
        foreach ($budget_years as $budget_year) {
            $buds[] = array(
                $budget_year->budget_year => $budget_year->budget_year
            );
        }

        $budget_years = array();
        foreach($buds as $bud) {
            foreach($bud as $key => $val) {
                $budget_years[$key] = $val;
            }
        }

        return $budget_years;
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
