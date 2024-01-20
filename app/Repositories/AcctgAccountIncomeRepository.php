<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountIncomeInterface;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountDeduction;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgExpandedVatableTax;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\AcctgTrialBalance;
use App\Models\AcctgSLAccountReport;
use App\Models\AcctgGLAccountReport;

class AcctgAccountIncomeRepository implements AcctgAccountIncomeInterface 
{
    public function find($id) 
    {
        return AcctgAccountIncome::findOrFail($id);
    }

    public function find_deduction($id) 
    {
        return AcctgAccountDeduction::findOrFail($id);
    }
    
    public function validate($transNo, $transType, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountIncome::where(['trans_no' => $transNo, 'trans_type' => $transType])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountIncome::where(['trans_no' => $transNo, 'trans_type' => $transType])->count();
    }

    public function create(array $details) 
    {   
        $ewt = AcctgExpandedWithholdingTax::find($details['ewt_id']);
        $evat = AcctgExpandedVatableTax::find($details['evat_id']);
        $details['total_amount'] = floatval($details['amount']) * floatval($details['quantity']);
        if ($details['vat_type'] == 'Vatable') {
            $ewtAmt = $ewt ? floatval(floatval(floatval($details['total_amount']) / floatval(1.12)) * floatval($ewt->percentage)) : NULL;
            $evatAmt = $evat ? floatval(floatval(floatval($details['total_amount']) / floatval(1.12)) * floatval($evat->percentage)) : NULL;
        } else {
            $ewtAmt = $ewt ? floatval(floatval($details['total_amount']) * floatval($ewt->percentage)) : NULL;
            $evatAmt = $evat ? floatval(floatval($details['total_amount']) * floatval($evat->percentage)) : NULL;
        }
        $newDetails['ewt_amount'] = str_replace(',','', number_format($ewtAmt, 5));
        $newDetails['evat_amount'] = str_replace(',','', number_format($evatAmt, 5));
        return AcctgAccountIncome::create($details);
    }

    public function update($id, array $newDetails) 
    {
        $ewt = AcctgExpandedWithholdingTax::find($newDetails['ewt_id']);
        $evat = AcctgExpandedVatableTax::find($newDetails['evat_id']);
        $newDetails['total_amount'] = floatval($newDetails['amount']) * floatval($newDetails['quantity']);
        if ($newDetails['vat_type'] == 'Vatable') {
            $ewtAmt = $ewt ? floatval(floatval(floatval($newDetails['total_amount']) / floatval(1.12)) * floatval($ewt->percentage)) : NULL;
            $evatAmt = $evat ? floatval(floatval(floatval($newDetails['total_amount']) / floatval(1.12)) * floatval($evat->percentage)) : NULL;
        } else {
            $ewtAmt = $ewt ? floatval(floatval($newDetails['total_amount']) * floatval($ewt->percentage)) : NULL;
            $evatAmt = $evat ? floatval(floatval($newDetails['total_amount']) * floatval($evat->percentage)) : NULL;
        }
        $newDetails['ewt_amount'] = str_replace(',','', number_format($ewtAmt, 5));
        $newDetails['evat_amount'] = str_replace(',','', number_format($evatAmt, 5));
        return AcctgAccountIncome::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_vouchers.voucher_no',
            2 => 'acctg_payables.vat_type',
            3 => 'acctg_account_general_ledgers.code',
            4 => 'acctg_payables.items',
            11 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_payables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountIncome::select([
            '*',
            'acctg_payables.id as identity',
            'acctg_payables.status as identityStatus',
            'acctg_payables.amount as identityAmount',
            'acctg_payables.total_amount as identityTotal'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_payables.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_payables.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_payables.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_payables.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.trans_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.trans_type', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res->where('acctg_payables.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }

    public function allUOMs()
    {
        return (new GsoUnitOfMeasurement)->allUOMs();
    }

    public function allEVAT()
    {
        return (new AcctgExpandedVatableTax)->allEVAT();
    }

    public function allEWT()
    {
        return (new AcctgExpandedWithholdingTax)->allEWT();
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function approve_deduction($id, array $newDetails) 
    {
        AcctgAccountDeduction::whereId($id)->update($newDetails);
        $res1 = AcctgAccountDeduction::find($id);
        if ($res1->status == 'posted') {
            $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountDeduction)->getTable(), 'entity_id' => $id])->get();
            if ($res2->count() > 0) {
                AcctgTrialBalance::whereId($res2->first()->id)->update([
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->voucher->payee_id,
                    'fund_code_id' => $res1->voucher->fund_code_id,
                    'gl_account_id' => $res1->gl_account_id,
                    'sl_account_id' => $res1->sl_account_id,
                    'debit' => $res1->total_amount,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by
                ]);
            } else {
                AcctgTrialBalance::create([
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->voucher->payee_id,
                    'fund_code_id' => $res1->voucher->fund_code_id,
                    'gl_account_id' => $res1->gl_account_id,
                    'sl_account_id' => $res1->sl_account_id,
                    'debit' => $res1->total_amount,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by,
                    'entity' => (new AcctgAccountDeduction)->getTable(),
                    'entity_id' => $id
                ]);
            }

            $this->gl_account_reports($id, $res1, (new AcctgAccountDeduction)->getTable(), $newDetails['approved_by'], $newDetails['approved_at'], true);

            if (empty($voucher)) {
                $voucher = (object) [
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->voucher->payee_id,
                    'fund_code_id' => $res1->voucher->fund_code_id,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by,
                    'entity' => (new AcctgAccountDeduction)->getTable()
                ];
            }
            $arr[] = $id;
            $totalAmt += floatval($res1->total_amount);
        }

        return true;
    }

    public function disapprove_deduction($id, array $newDetails) 
    {
        return AcctgAccountDeduction::whereId($id)->update($newDetails);
    }

    public function get_account_payable()
    {
        $res = AcctgAccountGeneralLedger::where(['is_payable' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function get_cash_in_local()
    {   
        $res = AcctgAccountGeneralLedger::where(['is_treasury' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function sl_account_reports($id, $row, $entity, $user, $timestamp)
    {
        $sql = AcctgSLAccountReport::where([
            'entity' => (new AcctgAccountIncome)->getTable(), 
            'entity_id' => $id,
            'debit_amount' => NULL,
            'tax_amount' => NULL
        ])->get();
        if ($sql->count() > 0) {
            AcctgSLAccountReport::whereId($sql->first()->id)->update([
                'voucher_id' => $row->voucher_id,
                'payee_id' => $row->payee_id,
                'fund_id' => $row->fund_code_id,
                'gl_account_id' => $row->gl_account_id,
                'credit_amount' => $row->total_amount,
                'posted_at' => $row->posted_at,
                'posted_by' => $row->posted_by,
                'updated_at' => $timestamp,
                'updated_by' => $user
            ]);
        } else {
            AcctgSLAccountReport::create([
                'voucher_id' => $row->voucher_id,
                'payee_id' => $row->payee_id,
                'fund_id' => $row->fund_code_id,
                'gl_account_id' => $row->gl_account_id,
                'credit_amount' => $row->total_amount,
                'posted_at' => $row->posted_at,
                'posted_by' => $row->posted_by,
                'entity' => (new AcctgAccountIncome)->getTable(),
                'entity_id' => $id,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }

        return true;
    }

    public function gl_account_reports($id, $row, $entity, $user, $timestamp, $is_deduction = false)
    {   
        if (!$is_deduction) {
            $sql1 = AcctgGLAccountReport::where([
                'entity' => $entity, 
                'entity_id' => $id,
                'debit_amount' => NULL
            ])->get();
            if ($sql1->count() > 0) {
                AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->payee_id,
                    'fund_id' => $row->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'credit_amount' => $row->total_amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->payee_id,
                    'fund_id' => $row->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'credit_amount' => $row->total_amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'entity' => $entity,
                    'entity_id' => $id,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        } else {
            $sql1 = AcctgGLAccountReport::where([
                'entity' => $entity, 
                'entity_id' => $id,
                'credit_amount' => NULL
            ])->get();
            if ($sql1->count() > 0) {
                AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->payee_id,
                    'fund_id' => $row->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->total_amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->payee_id,
                    'fund_id' => $row->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->total_amount,
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
        $cash_in_local = $this->get_cash_in_local();
        AcctgAccountIncome::whereId($id)->update($newDetails);
        $totalAmt = 0; $voucher = []; $arr = [];
        $res1 = AcctgAccountIncome::find($id);
        if ($res1->status == 'posted') {
            $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountIncome)->getTable(), 'entity_id' => $id])->get();
            if ($res2->count() > 0) {
                AcctgTrialBalance::whereId($res2->first()->id)->update([
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->payee_id,
                    'fund_code_id' => $res1->fund_code_id,
                    'gl_account_id' => $res1->gl_account_id,
                    'sl_account_id' => $res1->sl_account_id,
                    'credit' => $res1->total_amount,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by
                ]);
            } else {
                AcctgTrialBalance::create([
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->payee_id,
                    'fund_code_id' => $res1->fund_code_id,
                    'gl_account_id' => $res1->gl_account_id,
                    'sl_account_id' => $res1->sl_account_id,
                    'credit' => $res1->total_amount,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by,
                    'entity' => (new AcctgAccountIncome)->getTable(),
                    'entity_id' => $id
                ]);
            }
            if (empty($voucher)) {
                $voucher = (object) [
                    'voucher_id' => $res1->voucher_id,
                    'payee_id' => $res1->payee_id,
                    'fund_code_id' => $res1->fund_code_id,
                    'voucher_id' => $res1->voucher_id,
                    'posted_at' => $res1->posted_at,
                    'posted_by' => $res1->posted_by,
                    'entity' => (new AcctgAccountIncome)->getTable()
                ];
            }

            $this->gl_account_reports($id, $res1, (new AcctgAccountIncome)->getTable(), $newDetails['approved_by'], $newDetails['approved_at']);
            $this->sl_account_reports($id, $res1, (new AcctgAccountIncome)->getTable(), $newDetails['approved_by'], $newDetails['approved_at']);

            $arr[] = $id;
            $totalAmt += floatval($res1->total_amount);
        }
        if ($totalAmt > 0) {
            // AcctgTrialBalance::create([
            //     'voucher_id' => $voucher->voucher_id,
            //     'payee_id' => $voucher->payee_id,
            //     'fund_code_id' => $voucher->fund_code_id,
            //     'gl_account_id' => $cash_in_local->id,
            //     'debit' => $totalAmt,
            //     'entity' => $voucher->entity,
            //     'entity_id' => implode(',', $arr),
            //     'posted_at' => $voucher->posted_at,
            //     'posted_by' => $voucher->posted_by,
            // ]);
        }
        return true;
    }

    public function disapprove($id, array $newDetails) 
    {
        return AcctgAccountIncome::whereId($id)->update($newDetails);
    }

    public function approve_all($request, array $newDetails) 
    {
        return AcctgAccountIncome::whereIn('id', $request->payables)->update($newDetails);
    }

    public function disapprove_all($request, array $newDetails) 
    {
        return AcctgAccountIncome::whereIn('id', $request->payables)->update($newDetails);
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