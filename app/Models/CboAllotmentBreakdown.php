<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboAllotmentBreakdown extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_allotment_breakdowns';
    
    public $timestamps = false;

    public function obligation()
    {
        return $this->belongsTo('App\Models\CboAllotmentObligation', 'allotment_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }
}
