<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoCashierIncome extends Model
{
    protected $guarded = ['id'];

    public $table = 'cto_cashier_income';
    
    public $timestamps = false;

    public function cashier()
    {
        return $this->belongsTo('App\Models\CtoCashier', 'cashier_id', 'id');
    }

    public function sl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountSubsidiaryLedger', 'sl_account_id', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay', 'barangay_id', 'id');
    }
}
