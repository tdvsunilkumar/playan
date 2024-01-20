<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgVoucher extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_vouchers';
    
    public $timestamps = false;

    public function payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'payee_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function allPettyVouchers()
    {
        $vouchers = self::where(['is_active' => 1, 'is_replenish' => 1])->orderBy('id', 'asc')->get();
    
        $vouchs = array();
        $vouchs[] = array('' => 'select a voucher');
        foreach ($vouchers as $voucher) {
            $vouchs[] = array(
                $voucher->id => $voucher->voucher_no
            );
        }

        $vouchers = array();
        foreach($vouchs as $vouch) {
            foreach($vouch as $key => $val) {
                $vouchers[$key] = $val;
            }
        }

        return $vouchers;
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function payables_prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'payables_voucher_prepared', 'user_id');
    }

    public function payables_approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'payables_voucher_approver', 'user_id');
    }

    public function collections_prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'collection_voucher_prepared', 'user_id');
    }

    public function collections_approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'collection_voucher_approver', 'user_id');
    }

    public function cash_prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'cash_voucher_prepared', 'user_id');
    }

    public function cash_approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'cash_voucher_approver', 'user_id');
    }

    public function cheque_prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'cheque_voucher_prepared', 'user_id');
    }

    public function cheque_approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'cheque_voucher_approver', 'user_id');
    }

    public function others_prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'others_voucher_prepared', 'user_id');
    }

    public function others_approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'others_voucher_approver', 'user_id');
    }

    //for debit memo
    public function debit_memos()
    {
        return $this->hasMany('App\Models\AcctgDebitMemo', 'voucher_id', 'id');
    }
    public function disbursement()
    {
        return $this->hasMany('App\Models\AcctgAccountDisbursement', 'voucher_id', 'id');
    }
    public function deductions()
    {
        return $this->hasMany('App\Models\AcctgAccountDeduction', 'voucher_id', 'id');
    }
    public function alobs_debit_memo(){
        $allotment = $this->debit_memos->where('items', 'Allotment');
        return $allotment;
    }
}
