<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPPMP extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_project_procurement_management_plans';
    
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function fund()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
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
