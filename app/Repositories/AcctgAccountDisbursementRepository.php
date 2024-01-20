<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountDisbursementInterface;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgExpandedVatableTax;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgSLAccountReport;
use App\Models\AcctgGLAccountReport;

class AcctgAccountDisbursementRepository implements AcctgAccountDisbursementInterface 
{
    public function find($id) 
    {
        return AcctgAccountDisbursement::findOrFail($id);
    }
    
    public function validate($transNo, $transType, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountDisbursement::where(['trans_no' => $transNo, 'trans_type' => $transType])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountDisbursement::where(['trans_no' => $transNo, 'trans_type' => $transType])->count();
    }

    public function create(array $details) 
    {   
        return AcctgAccountDisbursement::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountDisbursement::whereId($id)->update($newDetails);
    }

    public function approvals_listItems($request)
    {
        $columns = array( 
            1 => 'acctg_vouchers.voucher_no',
            2 => 'acctg_account_subsidiary_ledgers.code',
            3 => 'acctg_payment_types.name',
            4 => 'acctg_disbursements.cheque_no',
            5 => 'acctg_disbursements.bank_name',
            6 => 'acctg_disbursements.amount',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_disbursements.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountDisbursement::select([
            'acctg_disbursements.*',
            'acctg_disbursements.id as identity',
            'acctg_disbursements.status as identityStatus',
            'acctg_disbursements.amount as identityAmount'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_payment_types', function($join)
        {
            $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_disbursements.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payment_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.cheque_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_disbursements.is_active', 1);
        if ($status != 'all') {
            $res = $res->where('acctg_disbursements.status', $status);
        } else {
            $res = $res->where('acctg_disbursements.status', '!=', 'draft');
        }
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->orderBy($column, $order);
            $res = $res->skip($start)->take($limit)->get(); 
        } else {
            $res = $res->orderBy($column, $order);
            $res = $res->get(); 
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function get_payables_details($voucher)
    {
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
        $res = $res->groupBy(['acctg_vouchers.id'])->get();

        $arr = array();
        if ($res->count() > 0) {
            $res = $res->first();
            if ($res->ewtAmt_1 > 0 || $res->ewtAmt_2 > 0 || $res->evatAmt_3 > 0 || $res->evatAmt_5 > 0) {
                $amount = floatval($res->ewtAmt_1) + floatval($res->ewtAmt_2) + floatval($res->evatAmt_3) + floatval($res->evatAmt_5);
                $glAccount = ($res->ewt_id != NULL) ? $res->ewt->gl_account : $res->evat->gl_account;
                $arr[] = (object) [
                    'gl_account_id' => $glAccount->id,
                    'sl_account_id' => NULL,
                    'debit' => 0,
                    'credit' => $amount
                ];
            } 
            if ($res->totalAmt > 0) {
                $arr[] = (object) [
                    'gl_account_id' => $this->get_account_payable(),
                    'sl_account_id' => NULL,
                    'debit' => $res->totalAmt,
                    'credit' => 0
                ];
            }
        }
        return $arr;
    }

    public function get_collections_details($voucher)
    {
        $res = AcctgAccountIncome::select([
            'acctg_incomes.*',
            DB::raw('SUM(acctg_incomes.paid_amount) as totalAmt2'),
            DB::raw('SUM(acctg_incomes.total_amount) as totalAmt'),
            DB::raw('SUM(IF(acctg_incomes.ewt_id="1",ewt_amount,0)) as ewtAmt_1'),
            DB::raw('SUM(IF(acctg_incomes.ewt_id="2",ewt_amount,0)) as ewtAmt_2'),
            DB::raw('SUM(IF(acctg_incomes.evat_id="1",evat_amount,0)) as evatAmt_3'),
            DB::raw('SUM(IF(acctg_incomes.evat_id="2",evat_amount,0)) as evatAmt_5'),
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
        $res = $res->groupBy(['acctg_vouchers.id'])->get();

        $arr = array();
        if ($res->count() > 0) {
            if ($res->totalAmt > 0) {
                $arr[] = (object) [
                    'gl_account_id' => $this->get_account_payable(),
                    'sl_account_id' => NULL,
                    'debit' => $res->totalAmt,
                    'credit' => 0
                ];
            }
        }
        return $arr;
    }

    public function sl_account_reports($id, $row, $entity, $is_payable = true, $user, $timestamp)
    {   
        $sql = AcctgSLAccountReport::where([
            'entity' => $entity, 
            'entity_id' => $id,
            'credit_amount' => NULL,
            'tax_amount' => NULL
        ])->get();
        if ($sql->count() > 0) {
            AcctgSLAccountReport::whereId($sql->first()->id)->update([
                'voucher_id' => $row->voucher_id,
                'payee_id' => $row->voucher->payee_id,
                'fund_id' => $row->voucher->fund_code_id,
                'gl_account_id' => $row->gl_account_id,
                'debit_amount' => $row->amount,
                'posted_at' => $row->posted_at,
                'posted_by' => $row->posted_by,
                'updated_at' => $timestamp,
                'updated_by' => $user
            ]);
        } else {
            AcctgSLAccountReport::create([
                'voucher_id' => $row->voucher_id,
                'payee_id' => $row->voucher->payee_id,
                'fund_id' => $row->voucher->fund_code_id,
                'gl_account_id' => $row->gl_account_id,
                'debit_amount' => $row->amount,
                'posted_at' => $row->posted_at,
                'posted_by' => $row->posted_by,
                'entity' => $entity,
                'entity_id' => $id,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }

        return true;
    }

    public function gl_account_reports($id, $row, $entity, $is_payable = true, $user, $timestamp)
    {
        if ($is_payable) {
            $sql = AcctgGLAccountReport::where([
                'entity' => $entity, 
                'entity_id' => $id,
                'debit_amount' => NULL,
            ])->get();
            if ($sql->count() > 0) {
                AcctgGLAccountReport::whereId($sql->first()->id)->update([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'credit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'credit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'entity' => $entity,
                    'entity_id' => $id,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        } else {
            $sql = AcctgGLAccountReport::where([
                'entity' => $entity, 
                'entity_id' => $id,
                'credit_amount' => NULL,
            ])->get();
            if ($sql->count() > 0) {
                AcctgGLAccountReport::whereId($sql->first()->id)->update([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'entity' => $entity,
                    'entity_id' => $id,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        }
        
        return true;
    }

    public function approve($id, array $newDetails) 
    {
        AcctgAccountDisbursement::whereId($id)->update($newDetails);
        $disburse = AcctgAccountDisbursement::find($id);
        $journal = AcctgVoucher::find($disburse->voucher_id);
        $totalAmt = 0; $voucher = []; $arr = [];
        if ($disburse->status == 'posted') {
            $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountDisbursement)->getTable(), 'entity_id' => $id])->get();
            if ($res2->count() > 0) {
                if ($journal->is_payables == 1) {
                    AcctgTrialBalance::whereId($res2->first()->id)->update([
                        'voucher_id' => $disburse->voucher_id,
                        'payee_id' => $disburse->voucher->payee_id,
                        'fund_code_id' => $disburse->voucher->fund_code_id,
                        'gl_account_id' => $disburse->gl_account_id,
                        'sl_account_id' => $disburse->sl_account_id,
                        'credit' => $disburse->amount,
                        'posted_at' => $disburse->posted_at,
                        'posted_by' => $disburse->posted_by
                    ]);
                } else {
                    AcctgTrialBalance::whereId($res2->first()->id)->update([
                        'voucher_id' => $disburse->voucher_id,
                        'payee_id' => $disburse->voucher->payee_id,
                        'fund_code_id' => $disburse->voucher->fund_code_id,
                        'gl_account_id' => $disburse->gl_account_id,
                        'sl_account_id' => $disburse->sl_account_id,
                        'debit' => $disburse->amount,
                        'posted_at' => $disburse->posted_at,
                        'posted_by' => $disburse->posted_by
                    ]);
                }
            } else {
                if ($journal->is_payables == 1) {
                    AcctgTrialBalance::create([
                        'voucher_id' => $disburse->voucher_id,
                        'payee_id' => $disburse->voucher->payee_id,
                        'fund_code_id' => $disburse->voucher->fund_code_id,
                        'gl_account_id' => $disburse->gl_account_id,
                        'sl_account_id' => $disburse->sl_account_id,
                        'credit' => $disburse->amount,
                        'posted_at' => $disburse->posted_at,
                        'posted_by' => $disburse->posted_by,
                        'entity' => (new AcctgAccountDisbursement)->getTable(),
                        'entity_id' => $id
                    ]);
                } else {
                    AcctgTrialBalance::create([
                        'voucher_id' => $disburse->voucher_id,
                        'payee_id' => $disburse->voucher->payee_id,
                        'fund_code_id' => $disburse->voucher->fund_code_id,
                        'gl_account_id' => $disburse->gl_account_id,
                        'sl_account_id' => $disburse->sl_account_id,
                        'debit' => $disburse->amount,
                        'posted_at' => $disburse->posted_at,
                        'posted_by' => $disburse->posted_by,
                        'entity' => (new AcctgAccountDisbursement)->getTable(),
                        'entity_id' => $id
                    ]);
                }
            }

            $is_payable = ($journal->is_payables == 1) ? true : false;
            $this->gl_account_reports($id, $disburse, (new AcctgAccountDisbursement)->getTable(), $is_payable, $newDetails['approved_by'], $newDetails['approved_at']);
            $this->sl_account_reports($id, $disburse, (new AcctgAccountDisbursement)->getTable(), $is_payable, $newDetails['approved_by'], $newDetails['approved_at']);

            if (empty($voucher)) {
                $voucher = (object) [
                    'voucher_id' => $disburse->voucher_id,
                    'payee_id' => $disburse->voucher->payee_id,
                    'fund_code_id' => $disburse->voucher->fund_code_id,
                    'posted_at' => $disburse->posted_at,
                    'posted_by' => $disburse->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable()
                ];
            }
            $arr[] = $id;
            $totalAmt += floatval($disburse->amount);
        }
        if ($totalAmt > 0) {
            if ($journal->is_payables == 1) {
                $ap_details = $this->get_payables_details($voucher->voucher_id);
                if (!empty($ap_details)) {
                    foreach ($ap_details as $detail) {                        
                        $res2 = AcctgTrialBalance::where([
                            'voucher_id' => $voucher->voucher_id, 
                            'gl_account_id' => $detail->gl_account_id, 
                            'entity' => (new AcctgAccountDisbursement)->getTable(), 
                            'entity_id' => NULL
                        ])->get();
                        if ($res2->count() > 0) {
                            AcctgTrialBalance::whereId($res2->first()->id)->update([
                                'voucher_id' => $voucher->voucher_id,
                                'payee_id' => $voucher->payee_id,
                                'fund_code_id' => $voucher->fund_code_id,
                                'gl_account_id' => $detail->gl_account_id,
                                'sl_account_id' => $detail->sl_account_id,
                                'debit' => $detail->debit,
                                'credit' => $detail->credit,
                                'posted_at' => $voucher->posted_at,
                                'posted_by' => $voucher->posted_by
                            ]);
                        } else {
                            AcctgTrialBalance::create([
                                'voucher_id' => $voucher->voucher_id,
                                'payee_id' => $voucher->payee_id,
                                'fund_code_id' => $voucher->fund_code_id,
                                'gl_account_id' => $detail->gl_account_id,
                                'sl_account_id' => $detail->sl_account_id,
                                'debit' => $detail->debit,
                                'credit' => $detail->credit,
                                'posted_at' => $voucher->posted_at,
                                'posted_by' => $voucher->posted_by,
                                'entity' => (new AcctgAccountDisbursement)->getTable(),
                                'entity_id' => NULL
                            ]);
                        }
                    }
                }
            } else {

            }
        }  
        return true;
    }

    public function disapprove($id, array $newDetails) 
    {
        return AcctgAccountDisbursement::whereId($id)->update($newDetails);
    }

    public function approve_all($request, array $newDetails) 
    {
        return AcctgAccountDisbursement::whereIn('id', $request->payments)->update($newDetails);
    }

    public function disapprove_all($request, array $newDetails) 
    {
        return AcctgAccountDisbursement::whereIn('id', $request->payments)->update($newDetails);
    }

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }
}