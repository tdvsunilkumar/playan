<?php

namespace App\Repositories;

use App\Interfaces\ReportAcctgJournalInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountDeduction;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgDebitMemo;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\RptLocality;
use App\Models\AcctgAccountGeneralLedger;
use DB;

class ReportAcctgJournalRepository implements ReportAcctgJournalInterface 
{
    public function find($id) 
    {
        return AcctgAccountPayable::findOrFail($id);
    }

    public function allFundCodes()
    {
       return (new AcctgFundCode)->allFundCodes();
    }

    public function get_petty_cash()
    {
        $res = AcctgAccountGeneralLedger::where(['is_petty_cash' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function get_account_payable()
    {
        $res = AcctgAccountGeneralLedger::where(['is_payable' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function get_advanced_payment()
    {
        $res = AcctgAccountGeneralLedger::where(['is_cash_advanced' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function get_cash_in_bank()
    {   
        $res = AcctgAccountGeneralLedger::where(['is_cash_in_bank' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function get_ap_details($voucher, $request, $row, $jev = 0)
    {
        if ($row->voucher->is_payables === 2) {
            $res = AcctgAccountDeduction::select([
                'acctg_deductions.*',
                DB::raw('SUM(acctg_deductions.amount) as totalAmt2'),
                DB::raw('SUM(acctg_deductions.total_amount) as totalAmt'),
                DB::raw('SUM(IF(acctg_deductions.ewt_id="1",ewt_amount,0)) as ewtAmt_1'),
                DB::raw('SUM(IF(acctg_deductions.ewt_id="2",ewt_amount,0)) as ewtAmt_2'),
                DB::raw('SUM(IF(acctg_deductions.evat_id="1",evat_amount,0)) as evatAmt_3'),
                DB::raw('SUM(IF(acctg_deductions.evat_id="2",evat_amount,0)) as evatAmt_5'),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_deductions.gl_account_id');
            })
            ->where('acctg_deductions.voucher_id', '=', $voucher)
            ->where([
            'acctg_deductions.is_active' => 1,
            'acctg_deductions.status' => 'posted'
            ]);
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_deductions.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            if ($jev > 0) {
                $res = $res->groupBy(['acctg_vouchers.id','acctg_deductions.gl_account_id'])->get();
            } else {
                $res = $res->groupBy(['acctg_deductions.gl_account_id'])->get();
            }
            // dd($res);
        } else {
            $res = AcctgAccountPayable::select([
                'acctg_payables.*',
                DB::raw('SUM(acctg_payables.paid_amount) as totalAmt2'),
                DB::raw('SUM(acctg_payables.total_amount) as totalAmt'),
                DB::raw('SUM(IF(acctg_payables.ewt_id="1",ewt_amount,0)) as ewtAmt_1'),
                DB::raw('SUM(IF(acctg_payables.ewt_id="2",ewt_amount,0)) as ewtAmt_2'),
                DB::raw('SUM(IF(acctg_payables.evat_id="1",evat_amount,0)) as evatAmt_3'),
                DB::raw('SUM(IF(acctg_payables.evat_id="2",evat_amount,0)) as evatAmt_5'),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
            })
            ->where('acctg_payables.voucher_id', '=', $voucher)
            ->where([
            'acctg_payables.is_active' => 1,
            'acctg_payables.status' => 'posted'
            ]);
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_payables.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            if ($jev > 0) {
                $res = $res->groupBy(['acctg_vouchers.id'])->get();
            } else {
                $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();
            }
        }
        return $res = $res->map(function($res) {
            return (object) [
                'id' => $res->id,
                'account_code' => $res->gl_account->code,
                'account_desc' => $res->gl_account->description,
                'amount' => $res->totalAmt,
                'amount_paid' => $res->totalAmt2,
                'ewt_1' => $res->ewtAmt_1,
                'ewt_2' => $res->ewtAmt_2,
                'evat_3' => $res->evatAmt_3,
                'evat_5' => $res->evatAmt_5
            ];
        });
    }

    public function get_income_details($voucher, $request)
    {   
        $sql = AcctgAccountDeduction::select([
            'acctg_deductions.*',
            DB::raw('SUM(0) as total_collected'),
            DB::raw('SUM(acctg_deductions.total_amount) as total_deposited'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_deductions.gl_account_id');
        })
        ->where('acctg_deductions.voucher_id', '=', $voucher)
        ->where([
           'acctg_deductions.is_active' => 1,
           'acctg_deductions.status' => 'posted'
        ]);
        if (!empty($request->date_from) && !empty($request->date_to)) {
            $sql = $sql->whereBetween('acctg_deductions.posted_at', [$request->date_from, $request->date_to]);
        }
        if (!empty($request->fund)) {
            $sql = $sql->where('acctg_vouchers.fund_code_id', '=', $request->fund);
        }
        $sql = $sql->groupBy(['acctg_account_general_ledgers.id']);

        $res = AcctgAccountIncome::select([
            'acctg_incomes.*',
            DB::raw('SUM(acctg_incomes.total_amount) as total_collected'),
            DB::raw('SUM(acctg_incomes.deposited_amount) as total_deposited'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_incomes.gl_account_id');
        })
        ->where('acctg_incomes.voucher_id', '=', $voucher)
        ->where([
           'acctg_incomes.is_active' => 1,
           'acctg_incomes.status' => 'posted'
        ]);
        if (!empty($request->date_from) && !empty($request->date_to)) {
            $res = $res->whereBetween('acctg_incomes.posted_at', [$request->date_from, $request->date_to]);
        }
        if (!empty($request->fund)) {
            $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
        }
        $res = $res->groupBy(['acctg_account_general_ledgers.id'])->union($sql)->get();

        return $res = $res->map(function($res) {
            return (object) [
                'id' => $res->id,
                'account_code' => $res->gl_account->code,
                'account_desc' => $res->gl_account->description,
                'amount_collected' => $res->total_collected,
                'amount_deposited' => $res->total_deposited,
            ];
        });
    }

    public function get($request)
    {
        if ($request->category == 'ap-journal') {
            $res = AcctgAccountPayable::select([
                'acctg_payables.*'
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_vouchers.is_payables', '=', 1)
            ->where('acctg_payables.voucher_id', '!=', NULL)
            ->where([
               'acctg_payables.is_active' => 1,
               'acctg_payables.status' => 'posted'
            ]);
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_payables.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_vouchers.id'])->get();

            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'date' => date('d-M-Y', strtotime($res->updated_at)),
                    'jev_no' => $res->voucher->voucher_no,
                    'payee' => $res->voucher->payee ? ucwords($res->voucher->payee->paye_name) : '',
                    'particulars' => $res->voucher->remarks,
                    'payables' => $this->get_ap_details($res->voucher->id, $request, $res)
                ];
            });
        } else if ($request->category == 'check-journal') { 
            $res = AcctgAccountDisbursement::select([
                'acctg_disbursements.*'
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_disbursements.payment_type_id', '=', 2)
            // ->where('acctg_vouchers.is_payables', '=', 1)
            ->where('acctg_disbursements.voucher_id', '!=', NULL)
            ->where([
                'acctg_disbursements.is_active' => 1,
                'acctg_disbursements.status' => 'posted'
            ]);
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_disbursements.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_vouchers.id'])->get();
            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'date' => date('d-M-Y', strtotime($res->updated_at)),
                    'jev_no' => $res->voucher->voucher_no,
                    'payee' => $res->voucher->payee ? ucwords($res->voucher->payee->paye_name) : '',
                    'particulars' => $res->voucher->remarks,
                    'type' => $res->voucher->is_payables,
                    'payables' => $this->get_ap_details($res->voucher->id, $request, $res, 1)
                ];
            });
        } else if ($request->category == 'cash-journal') {
            $res = AcctgAccountDisbursement::select([
                'acctg_disbursements.*'
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_disbursements.payment_type_id', '=', 1)
            ->where('acctg_vouchers.is_payables', '=', 1)
            ->where('acctg_disbursements.voucher_id', '!=', NULL)
            ->where([
                'acctg_disbursements.is_active' => 1,
                'acctg_disbursements.status' => 'posted'
            ]);
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_disbursements.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_vouchers.id'])->get();

            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'date' => date('d-M-Y', strtotime($res->updated_at)),
                    'jev_no' => $res->voucher->voucher_no,
                    'payee' => $res->voucher->payee ? ucwords($res->voucher->payee->paye_name) : '',
                    'particulars' => $res->voucher->remarks,
                    'payables' => $this->get_ap_details($res->voucher->id, $request, $res)
                ];
            });
        } else if ($request->category == 'cash-receipt-journal') {
            $res = AcctgAccountDisbursement::select([
                'acctg_disbursements.*'
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_vouchers.is_payables', '=', 0)
            ->where('acctg_disbursements.voucher_id', '!=', NULL)
            ->where('acctg_disbursements.is_active', '=', 1)
            ->where(function ($query) {
                $query->where('acctg_disbursements.status', '=', 'posted')
                    ->orWhere('acctg_disbursements.status', '=', 'deposited');
            });
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_disbursements.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_vouchers.id'])->get();

            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'date' => date('d-M-Y', strtotime($res->updated_at)),
                    'jev_no' => $res->voucher->voucher_no,
                    'payee' => $res->voucher->payee ? ucwords($res->voucher->payee->paye_name) : '',
                    'particulars' => $res->voucher->remarks,
                    'incomes' => $this->get_income_details($res->voucher->id, $request)
                ];
            });
        } else if ($request->category == 'debit-memo-journal') {
            $res = AcctgDebitMemo::select([
                'acctg_debit_memos.*'
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_debit_memos.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_vouchers.is_payables', '=', 2)
            ->where('acctg_debit_memos.voucher_id', '!=', NULL)
            ->where('acctg_debit_memos.is_active', '=', 1)
            ->where(function ($query) {
                $query->where('acctg_debit_memos.status', '=', 'posted')
                      ->orWhere('acctg_debit_memos.status', '=', 'deposited');
            });
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_debit_memos.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_vouchers.id'])->get();
            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'date' => date('d-M-Y', strtotime($res->updated_at)),
                    'jev_no' => $res->voucher->voucher_no,
                    'payee' => $res->voucher->payee ? ucwords($res->voucher->payee->paye_name) : '',
                    'particulars' => $res->voucher->remarks,
                    'debit_memo' => $res->voucher->alobs_debit_memo()->where('status','posted'),
                    'credit' => $res->voucher->deductions()
                        ->where('status','posted')
                        ->join('acctg_account_general_ledgers as gl', 'gl.id', 'acctg_deductions.gl_account_id')->groupby('gl_account_id')
                        ->select(
                            DB::raw('SUM(amount) as amt'),
                            'gl.code as code'
                        )
                        ->get(),
                    'debit' => $res->voucher->debit_memos()
                        ->where('status','posted')
                        ->join('acctg_account_general_ledgers as gl', 'gl.id', 'acctg_debit_memos.gl_account_id')
                        ->groupby('gl_account_id')
                        ->select(
                            DB::raw('SUM(amount) as amt'),
                            'gl.code as code'
                        )
                        ->get(),
                ];
            });
        }
    }

    public function get_check_disbursement($voucherNo, $slID)
    {
        $res = AcctgAccountDisbursement::leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where('acctg_disbursements.payment_type_id', '=', 2)
        ->where('acctg_vouchers.is_payables', '=', 1)
        ->where('acctg_disbursements.voucher_id', '!=', NULL)
        ->where([
            'acctg_disbursements.is_active' => 1,
            'acctg_disbursements.status' => 'posted'
        ])
        ->where('acctg_vouchers.voucher_no', '=', $voucherNo)
        ->where('acctg_account_subsidiary_ledgers.id', '=', $slID)
        ->sum('acctg_disbursements.amount');

        return $res;
    }

    public function get_debit_memo($voucherNo, $slID)
    {
        $res = AcctgAccountDisbursement::leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where('acctg_disbursements.payment_type_id', '=', 2)
        ->where('acctg_vouchers.is_payables', '=', 2)
        ->where('acctg_disbursements.voucher_id', '!=', NULL)
        ->where([
            'acctg_disbursements.is_active' => 1,
            'acctg_disbursements.status' => 'posted'
        ])
        ->where('acctg_vouchers.voucher_no', '=', $voucherNo)
        ->where('acctg_account_subsidiary_ledgers.id', '=', $slID)
        ->sum('acctg_disbursements.amount');
        return $res;
    }

    public function get_cash_disbursement($voucherNo, $glID)
    {
        $res = AcctgAccountDisbursement::leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where('acctg_disbursements.payment_type_id', '=', 1)
        ->where('acctg_vouchers.is_payables', '=', 1)
        ->where('acctg_disbursements.voucher_id', '!=', NULL)
        ->where([
            'acctg_disbursements.is_active' => 1,
            'acctg_disbursements.status' => 'posted'
        ])
        ->where('acctg_vouchers.voucher_no', '=', $voucherNo)
        ->where('acctg_account_general_ledgers.id', '=', $glID)
        ->sum('acctg_disbursements.amount');

        return $res;
    }

    public function get_debit_memos($request)
    {   
        $cash_in_bank = $this->get_cash_in_bank();
        $res = AcctgAccountDisbursement::select([
            'acctg_disbursements.*'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->where('acctg_account_general_ledgers.id', '=', $cash_in_bank->id)
        ->where('acctg_vouchers.is_payables', '=', 2)
        ->where('acctg_disbursements.voucher_id', '!=', NULL)
        ->where([
            'acctg_disbursements.is_active' => 1,
            'acctg_disbursements.status' => 'posted'
        ]);
        if (!empty($request->date_from) && !empty($request->date_to)) {
            $res = $res->whereBetween('acctg_disbursements.posted_at', [$request->date_from, $request->date_to]);
        }
        if (!empty($request->fund)) {
            $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
        }
        $res = $res->groupBy(['acctg_account_subsidiary_ledgers.id'])->get();
        return $res;
    }

    public function get_disbursements($request)
    {   
        $cash_in_bank = $this->get_cash_in_bank();
        $res = AcctgAccountDisbursement::select([
            'acctg_disbursements.*'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->where('acctg_account_general_ledgers.id', '=', $cash_in_bank->id)
        ->where('acctg_disbursements.payment_type_id', '=', 2)
        // ->where('acctg_vouchers.is_payables', '=', 1)
        ->where('acctg_disbursements.voucher_id', '!=', NULL)
        ->where([
            'acctg_disbursements.is_active' => 1,
            'acctg_disbursements.status' => 'posted'
        ]);
        if (!empty($request->date_from) && !empty($request->date_to)) {
            $res = $res->whereBetween('acctg_disbursements.posted_at', [$request->date_from, $request->date_to]);
        }
        if (!empty($request->fund)) {
            $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
        }
        $res = $res->groupBy(['acctg_account_subsidiary_ledgers.id'])->get();
        return $res;
    }

    public function get_prepared_by()
    {
        $res = User::find(Auth::user()->id);
        $user = array();
        if ($res) {
            $user = (object) [
                'fullname' => $res->hr_employee->fullname,
                'designation' => $res->hr_employee->designation->description
            ];
        }
        return $user;
    }

    public function get_certified_by()
    {
        $res = RptLocality::find(5);
        $user = array();
        if ($res) {
            $user = (object) [
                'fullname' => $res->budget_officer->fullname,
                'designation' => $res->budget_officer->designation->description
            ];
        }
        return $user;
    }

    public function get_check_disbursement_debit_memo($id, $slID){
        $acct = AcctgAccountDeduction::find($id);
        $disburse = AcctgAccountDisbursement::join('acctg_disburse_types', 'acctg_disburse_types.id', 'acctg_disbursements.disburse_type_id')
        ->where([
            'acctg_disbursements.voucher_id' => $acct->voucher_id,
            'acctg_disburse_types.code' => $acct->trans_type,
            'acctg_disbursements.sl_account_id' => $slID,
        ])->sum('amount');
        return $disburse;
    }
}