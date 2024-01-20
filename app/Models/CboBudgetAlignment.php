<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboBudgetAlignment extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_budget_alignments';
    
    public $timestamps = false;

    public function breakdown()
    {
        return $this->belongsTo('App\Models\CboBudgetBreakdown', 'budget_breakdown_id', 'id');
    }
}
