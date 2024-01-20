<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcctgDebitMemo extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $appends = array('alobs');

    public function due_to_employees_gl()
    {
        //add to policy
        return AcctgAccountGeneralLedger::where('code',20101020)->first();
    }

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

    public function ewt()
    {
        return $this->belongsTo('App\Models\AcctgExpandedWithholdingTax', 'ewt_id', 'id');
    }
    public function allotment()
    {
        return $this->belongsTo('App\Models\CboAllotmentBreakdown', 'trans_id', 'id');
    }
    public function payrolls()
    {
        return $this->hasMany('App\Models\HR\Payroll', 'hrpr_payroll_no', 'trans_no');
    }
    public function getAlobsAttribute()
    {
        return isset($this->allotment->obligation) ? $this->allotment->obligation->alobs_control_no : '';
    }

    public function get_total($id, $type)
    {
        $debitmemo = self::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_debit_memos.gl_account_id')
        ->where([
            'acctg_debit_memos.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Debit',
        ])->sum('total_amount');

        $deductions = AcctgAccountDeduction::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_deductions.gl_account_id')
        ->where([
            'acctg_deductions.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Debit',
        ])->sum('total_amount');

        $debitmemo_credit = self::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_debit_memos.gl_account_id')
        ->where([
            'acctg_debit_memos.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Credit',
        ])->sum('total_amount');
        $deductions_credit = AcctgAccountDeduction::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_deductions.gl_account_id')
        ->where([
            'acctg_deductions.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Credit',
        ])->sum('total_amount');
        return ($debitmemo_credit + $deductions_credit) - ($debitmemo + $deductions);
    }
    public function get_sum($id, $type)
    {
        $debitmemo_credit = self::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_debit_memos.gl_account_id')
        ->where([
            'acctg_debit_memos.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Credit',
        ])->sum('total_amount');
        $deductions_credit = AcctgAccountDeduction::join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'acctg_deductions.gl_account_id')
        ->where([
            'acctg_deductions.voucher_id' => $id,
            'acctg_account_general_ledgers.is_'.$type => 1,
            'acctg_account_general_ledgers.normal_balance' => 'Credit',
        ])->sum('total_amount');
        return ($debitmemo_credit + $deductions_credit) ;
    }
}
