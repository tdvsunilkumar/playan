<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgSLAccountReport extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_sl_accounts_reports';
    
    public $timestamps = false;

    public function payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'payee_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'voucher_id', 'id');
    }
}
