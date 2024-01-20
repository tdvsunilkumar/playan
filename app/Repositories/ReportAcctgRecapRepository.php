<?php

namespace App\Repositories;

use App\Interfaces\ReportAcctgRecapInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\AcctgDebitMemo;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountDeduction;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\RptLocality;
use App\Models\AcctgAccountGeneralLedger;
use DB;

class ReportAcctgRecapRepository implements ReportAcctgRecapInterface 
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
    public function get_account_tax()
    {
        $res = AcctgAccountGeneralLedger::where(['is_due_to_bir' => 1, 'is_active' => 1])->get();
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

    public function get_ap_details($voucher, $request)
    {
        $res = AcctgAccountPayable::select([
            'acctg_payables.*',
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
        $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();

        return $res = $res->map(function($res) {
            return (object) [
                'id' => $res->id,
                'account_code' => $res->gl_account->code,
                'account_desc' => $res->gl_account->description,
                'amount' => $res->totalAmt,
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
        if ($request->category == 'ap-recap') {
            $res = AcctgAccountPayable::select([
                'acctg_payables.*',
                DB::raw('SUM(acctg_payables.total_amount) as debit'),
                DB::raw('SUM(acctg_payables.total_amount) as credit'),
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
            })
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
            $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();

            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'amount' => $res->totalAmt,
                    'account_code' => $res->gl_account->code,
                    'account_desc' => $res->gl_account->description,
                    'debit' => $res->debit,
                    'credit' => $res->credit,
                ];
            });
        } else if ($request->category == 'check-recap') { 
            $payable_gl = $this->get_account_payable();
            $tax_gl = $this->get_account_tax();
            $payable = AcctgAccountPayable::select([
                'acctg_payables.id as acctg_id',
                'acctg_payables.voucher_id',
                DB::raw($payable_gl->id.' as gl_account_id'),
                DB::raw('SUM(paid_amount) as paid_amount'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw("'debit' as type"),
                DB::raw("'payable' as from_tbl"),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->whereIn('acctg_vouchers.id',
                AcctgAccountDisbursement::select(['acctg_disbursements.voucher_id'])
                ->where([
                    'acctg_disbursements.payment_type_id' => 2,
                    'acctg_disbursements.is_active' => 1,
                    'acctg_disbursements.status' => 'posted'
                ])
                ->get()
            )
            ->where('voucher_id', '!=', NULL)
            ->where([
               'acctg_payables.is_active' => 1,
               'acctg_payables.status' => 'posted'
            ]);

            $taxes1 = AcctgAccountPayable::select([
                'acctg_payables.id as acctg_id',
                'acctg_payables.voucher_id',
                DB::raw($tax_gl->id.' as gl_account_id'),
                DB::raw('TRUNCATE(SUM(ewt_amount),2) as paid_amount'),
                DB::raw('SUM(ewt_amount ) as total_amount'),
                DB::raw("'credit' as type"),
                DB::raw("'payable' as from_tbl"),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->whereIn('acctg_vouchers.id',
                AcctgAccountDisbursement::select(['acctg_disbursements.voucher_id'])
                ->where([
                    'acctg_disbursements.payment_type_id' => 2,
                    'acctg_disbursements.is_active' => 1,
                    'acctg_disbursements.status' => 'posted'
                ])
                ->get()
            )
            ->groupBy('acctg_payables.ewt_id')
            ->where('voucher_id', '!=', NULL)
            ->where([
               'acctg_payables.is_active' => 1,
               'acctg_payables.status' => 'posted'
            ]);
            // dd($taxes1->get());
            $taxes2 = AcctgAccountPayable::select([
                'acctg_payables.id as acctg_id',
                'acctg_payables.voucher_id',
                DB::raw($tax_gl->id.' as gl_account_id'),
                DB::raw('TRUNCATE(SUM(evat_amount ),2) as paid_amount'),
                DB::raw('SUM(evat_amount ) as total_amount'),
                DB::raw("'credit' as type"),
                DB::raw("'payable' as from_tbl"),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->whereIn('acctg_vouchers.id',
                AcctgAccountDisbursement::select(['acctg_disbursements.voucher_id'])
                ->where([
                    'acctg_disbursements.payment_type_id' => 2,
                    'acctg_disbursements.is_active' => 1,
                    'acctg_disbursements.status' => 'posted'
                ])
                ->get()
            )
            ->groupBy('acctg_payables.evat_id')
            ->where('voucher_id', '!=', NULL)
            ->where([
               'acctg_payables.is_active' => 1,
               'acctg_payables.status' => 'posted'
            ]);
            $disbursed = AcctgAccountDisbursement::select([
                'acctg_disbursements.id as acctg_id',
                'acctg_disbursements.voucher_id',
                'gl_account_id',
                DB::raw('SUM(amount) as paid_amount'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw("'credit' as type"),
                DB::raw("'disburse' as from_tbl"),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->where('voucher_id', '!=', NULL)
            ->where([
               'acctg_disbursements.payment_type_id' => 2,
               'acctg_disbursements.is_active' => 1,
               'acctg_disbursements.status' => 'posted'
            ]);
            $payable = AcctgAccountPayable::select([
                'acctg_payables.id as acctg_id',
                'acctg_payables.voucher_id',
                DB::raw($payable_gl->id.' as gl_account_id'),
                DB::raw('SUM(paid_amount) as paid_amount'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw("'debit' as type"),
                DB::raw("'payable' as from_tbl"),
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->whereIn('acctg_vouchers.id',
                AcctgAccountDisbursement::select(['acctg_disbursements.voucher_id'])
                ->where([
                    'acctg_disbursements.payment_type_id' => 2,
                    'acctg_disbursements.is_active' => 1,
                    'acctg_disbursements.status' => 'posted'
                ])
                ->get()
            )
            ->where('voucher_id', '!=', NULL)
            ->where([
               'acctg_payables.is_active' => 1,
               'acctg_payables.status' => 'posted'
            ]);

            $deductions = AcctgAccountDeduction::select([
                'acctg_deductions.id as acctg_id',
                'acctg_deductions.voucher_id',
                'acctg_deductions.gl_account_id',
                DB::raw('SUM(total_amount) as paid_amount'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw("'debit' as type"),
                DB::raw("'deduction' as from_tbl"),
            ])
            ->groupBy('acctg_deductions.gl_account_id')
            ->join('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
            })
            ->join(DB::raw('(Select `acctg_disburse_types`.`code` as `disburse_code`, `acctg_disbursements`.`voucher_id`, `acctg_disbursements`.`amount`, `acctg_disbursements`.`payment_type_id` from `acctg_disbursements` inner join `acctg_disburse_types` on `acctg_disburse_types`.`id` = `acctg_disbursements`.`disburse_type_id`) as disburse'), function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'disburse.voucher_id')
                    ->on('acctg_deductions.trans_type', '=', 'disburse.disburse_code');
            })
            ->where('acctg_deductions.voucher_id', '!=', NULL)
            ->where([
               'acctg_vouchers.is_payables' => 2,
               'acctg_deductions.is_active' => 1,
               'acctg_deductions.status' => 'posted'
            ]);


            if (!empty($request->date_from) && !empty($request->date_to)) {
                $payable = $payable->whereBetween('posted_at', [$request->date_from, $request->date_to]);
                $deductions = $deductions->whereBetween('posted_at', [$request->date_from, $request->date_to]);
                $taxes2 = $taxes2->whereBetween('posted_at', [$request->date_from, $request->date_to]);
                $taxes1 = $taxes1->whereBetween('posted_at', [$request->date_from, $request->date_to]);
                $disbursed = $disbursed->whereBetween('posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $payable = $payable->where('acctg_vouchers.fund_code_id', '=', $request->fund);
                $disbursed = $disbursed->where('acctg_vouchers.fund_code_id', '=', $request->fund);
                $taxes1 = $taxes1->where('acctg_vouchers.fund_code_id', '=', $request->fund);
                $taxes2 = $taxes2->where('acctg_vouchers.fund_code_id', '=', $request->fund);
                $deductions = $deductions->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = DB::table($disbursed->union($payable)->union($deductions)->union($taxes1)->union($taxes2), 'tbl')
            ->select(
                'tbl.*',
                DB::raw('SUM(tbl.paid_amount) as total_amt'),
                'acctg_account_general_ledgers.description as gl_desc',
                'acctg_account_general_ledgers.code as gl_code',
                'acctg_account_general_ledgers.normal_balance',
            );
            $res->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id', '=', 'tbl.gl_account_id')->groupby('tbl.gl_account_id');
            
            $res = $res->get();
            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'amount' => $res->total_amt,
                    'account_code' => $res->gl_code,
                    'account_desc' => $res->gl_desc,
                    'account_id' => $res->gl_account_id,
                    'debit' => $res->type === 'debit' ? $res->total_amt : 0,
                    'credit' => $res->type === 'credit' ? $res->total_amt : 0,
                ];
            });
        } else if ($request->category == 'cash-recap') {
            $res = AcctgAccountPayable::select([
                'acctg_payables.*',
                DB::raw('SUM(acctg_payables.paid_amount) as credit'),
                DB::raw('SUM(acctg_payables.total_amount) as debit'),
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->whereIn('acctg_vouchers.id',
                AcctgAccountDisbursement::select(['acctg_disbursements.voucher_id'])
                ->where([
                    'acctg_disbursements.payment_type_id' => 1,
                    'acctg_disbursements.is_active' => 1,
                    'acctg_disbursements.status' => 'posted'
                ])
                ->get()
            )
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
            $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();
            
            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'amount' => $res->totalAmt,
                    'account_code' => $res->gl_account->code,
                    'account_desc' => $res->gl_account->description,
                    'debit' => $res->debit,
                    'credit' => $res->credit,
                ];
            });
        } else if ($request->category == 'cash-receipt-recap') {
            $res = AcctgAccountIncome::select([
                'acctg_incomes.*',
                DB::raw('SUM(acctg_incomes.deposited_amount) as debit'),
                DB::raw('SUM(acctg_incomes.total_amount) as credit'),
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_incomes.gl_account_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_vouchers.is_payables', '=', 0)
            ->where('acctg_incomes.voucher_id', '!=', NULL)
            ->where('acctg_incomes.is_active', '=', 1)
            ->where('acctg_incomes.status', '=', 'posted');
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('acctg_incomes.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();

            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'account_code' => $res->gl_account->code,
                    'account_desc' => $res->gl_account->description,
                    'debit' => $res->debit,
                    'credit' => $res->credit,
                ];
            });
        } else {
//             $res = DB::table(DB::raw("(SELECT * FROM acctg_debit_memos UNION SELECT * FROM acctg_deductions) as memo"))
//             ->select([
//                 '*',
//                 DB::raw('SUM(paid_amount) as debit'),
//                 DB::raw('SUM(total_amount) as credit'),
//             ])
//             ->leftJoin('acctg_account_general_ledgers', function($join)
//             {
//                 $join->on('acctg_account_general_ledgers.id', '=', 'memo.gl_account_id');
//             })
//             ->leftJoin('acctg_vouchers', function($join)
//             {
//                 $join->on('acctg_vouchers.id', '=', 'memo.voucher_id');
//             })
//             ->leftJoin('cbo_payee', function($join)
//             {
//                 $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
//             })
//             ->get();
// dd($res);    
            $res = DB::table(DB::raw("(SELECT * FROM acctg_debit_memos UNION SELECT * FROM acctg_deductions) as memo"))
            ->select([
                '*',
                'acctg_account_general_ledgers.code',
                'acctg_account_general_ledgers.description',
                'acctg_account_general_ledgers.normal_balance',
                DB::raw('SUM(paid_amount) as debit'),
                DB::raw('SUM(total_amount) as credit'),
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'memo.gl_account_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'memo.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
            })
            ->where('acctg_vouchers.is_payables', '=', 2)
            ->where('memo.voucher_id', '!=', NULL)
            ->where('memo.is_active', '=', 1)
            ->where('memo.status', '=', 'posted');
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $res = $res->whereBetween('memo.posted_at', [$request->date_from, $request->date_to]);
            }
            if (!empty($request->fund)) {
                $res = $res->where('acctg_vouchers.fund_code_id', '=', $request->fund);
            }
            $res = $res->groupBy(['acctg_account_general_ledgers.id'])->get();
            return $res = $res->map(function($res) use ($request) {
                return (object) [
                    'account_code' => $res->code,
                    'account_desc' => $res->description,
                    'type' => $res->normal_balance,
                    'debit' => $res->debit,
                    'credit' => $res->credit,
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
}