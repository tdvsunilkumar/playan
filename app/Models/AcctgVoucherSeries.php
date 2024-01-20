<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgVoucherSeries extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_vouchers_series';
    
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'voucher_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }
}
