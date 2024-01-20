<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountDisbursement extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_disbursements';
    
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'voucher_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function sl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountSubsidiaryLedger', 'sl_account_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\AcctgPaymentType', 'payment_type_id', 'id');
    }

    public function disburse()
    {
        return $this->belongsTo('App\Models\AcctgDisburseType', 'disburse_type_id', 'id');
    }
}
