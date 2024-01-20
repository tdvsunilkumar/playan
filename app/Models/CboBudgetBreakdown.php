<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboBudgetBreakdown extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_budget_breakdowns';
    
    public $timestamps = false;

    public function budget()
    {
        return $this->belongsTo('App\Models\CboBudget', 'budget_id', 'id');
    }
    
    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\CboBudgetCategory', 'budget_category_id', 'id');
    }
}
