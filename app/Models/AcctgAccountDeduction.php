<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountDeduction extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_deductions';
    
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

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function ewt()
    {
        return $this->belongsTo('App\Models\AcctgExpandedWithholdingTax', 'ewt_id', 'id');
    }

    public function evat()
    {
        return $this->belongsTo('App\Models\AcctgExpandedVatableTax', 'evat_id', 'id');
    }

    //for debit memo sorry
    public function payroll()
    {
        return $this->belongsTo('App\Models\HR\Payroll', 'trans_id', 'id');
    }
}
