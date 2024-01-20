<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgRptReceivableCY extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_rpt_receivable_cy';
    
    public $timestamps = false;

    public function fund()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function sl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountSubsidiaryLedger', 'sl_account_id', 'id');
    }
}
