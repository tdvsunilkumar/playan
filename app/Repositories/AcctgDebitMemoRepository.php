<?php

namespace App\Repositories;

use App\Interfaces\AcctgDebitMemoInterface;

use App\Models\AcctgDebitMemo;
use App\Models\AcctgVoucher;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountDeduction;
use App\Models\AcctgDisburseType;
use App\Models\AcctgTrialBalance;

use Carbon\Carbon;
use Auth;
use DB;

class AcctgDebitMemoRepository implements AcctgDebitMemoInterface
{
    public function listItems($request, $type = 2)
    {   
        $columns = array( 
            0 => 'acctg_vouchers.voucher_no',
            1 => 'cbo_payee.paye_name',
            2 => 'acctg_vouchers.remarks',
            3 => 'acctg_vouchers.total_payables',
            4 => 'acctg_vouchers.total_ewt',
            5 => 'acctg_vouchers.total_evat',
            6 => 'acctg_vouchers.total_disbursement'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_vouchers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgVoucher::select([
            'acctg_vouchers.*',
            'acctg_vouchers.id as identity',
            'acctg_vouchers.status as identityStatus',
            'acctg_vouchers.total_payables as identityPayablesAmount',
            'acctg_vouchers.total_ewt as identityEWTAmount',
            'acctg_vouchers.total_evat as identityEVATAmount',
            'acctg_vouchers.total_disbursement as identityDisbursementAmount',
            'acctg_vouchers.total_deductions as identityDeduction',
            'acctg_vouchers.created_at as identityCreatedAt',
            'acctg_vouchers.created_by as identityCreatedBy',
            'acctg_vouchers.updated_at as identityUpdatedAt',
            'acctg_vouchers.updated_by as identityUpdatedBy',
        ])
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')  
                // ->orWhere('acctg_vouchers.remarks', 'like', '%' . $keywords . '%')    
                ->orWhere('cbo_payee.paye_name', 'like', '%' . $keywords . '%')   
                ->orWhere('acctg_vouchers.total_payables', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_ewt', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_evat', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_disbursement', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res = $res->where('acctg_vouchers.status', $status);
        }
        $res = $res->where('acctg_vouchers.is_payables', $type)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();
        return (object) array('count' => $count, 'data' => $res);
    }
    public function deductions_listItems($request, $voucherID)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_deductions.vat_type',
            3 => 'acctg_expanded_withholding_taxes.code',
            4 => 'acctg_expanded_vatable_taxes.code',
            5 => 'acctg_deductions.items',
            6 => 'acctg_deductions.quantity',
            7 => 'gso_unit_of_measurements.code',
            8 => 'acctg_deductions.amount',
            9 => 'acctg_deductions.total_amount',
            11 => 'acctg_deductions.due_date',
            12 => 'acctg_deductions.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_deductions.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountDeduction::select([
            'acctg_deductions.*',
            'acctg_deductions.id as identity',
            'acctg_deductions.status as identityStatus',
            // DB::raw('sum(acctg_deductions.amount) as identityAmount'),
            // 'acctg_deductions.total_amount as identityTotal'
            DB::raw('SUM(acctg_deductions.total_amount) as identityTotal'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_deductions.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_deductions.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_deductions.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_deductions.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_deductions.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_deductions.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_deductions.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_deductions.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_deductions.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_deductions.voucher_id', $voucherID);
        if ($status != 'all') {
            $res->where('acctg_deductions.status', $status);
        }
        $res->groupBy('acctg_deductions.gl_account_id');
        $res->orderBy($column, $order);
        $count = $res->get()->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }
        // dd($res);
        return (object) array('count' => $count, 'data' => $res);
    }
    
    public function collections_listItems($request, $voucherID)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => DB::raw('SUM(acctg_debit_memos.total_amount)'),
            3 => 'acctg_debit_memos.responsibility_center',
            4 => 'acctg_debit_memos.status',
            // 5 => 'acctg_payables.items',
            // 6 => 'acctg_payables.quantity',
            // 7 => 'gso_unit_of_measurements.code',
            // 8 => 'acctg_payables.amount',
            // 9 => 'acctg_payables.total_amount',
            // 11 => 'acctg_payables.due_date',
            // 12 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_payables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgDebitMemo::select([
            'acctg_debit_memos.*',
            'acctg_debit_memos.id as identity',
            'acctg_debit_memos.status as identityStatus',
            'acctg_debit_memos.amount as identityAmount',
            // 'acctg_debit_memos.total_amount as identityTotal'
            DB::raw('SUM(acctg_debit_memos.total_amount) as identityTotal'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_debit_memos.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_debit_memos.gl_account_id');
        })
        
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_debit_memos.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_debit_memos.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_debit_memos.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_debit_memos.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_debit_memos.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_debit_memos.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_debit_memos.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_debit_memos.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_debit_memos.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_debit_memos.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_debit_memos.voucher_id', $voucherID);
        if ($status != 'all') {
            $res->where('acctg_debit_memos.status', $status);
        }
        $res->groupBy('acctg_debit_memos.gl_account_id');
        $res->orderBy($column, $order);
        $count = $res->get()->count();
        if ($limit > 0) {
            $res = $res->offset($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function  listEmployees($request, $id)
    {             
        $columns = array( 
            0 => 'hr_payroll.id',
            1 => 'hr_employees.fullname',
            2 => 'gso_unit_of_measurements.code',
            8 => 'gso_unit_of_measurements.code',
            9 => 'gso_unit_of_measurements.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'hr_employees.fullname' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgDebitMemo::select([
            '*',
            'hr_payroll.id as payId',
            'hr_employees.fullname as employeeName',
            'acctg_departments_divisions.name as divisionName',
            'acctg_departments.name as departmentName',
        ])
        ->join('hr_payroll', function($join)
        {
            $join->on('hr_payroll.id', '=', 'acctg_debit_memos.trans_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'hr_payroll.hrpr_employees_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_payroll.hrpr_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'hr_payroll.hrpr_division_id');
        })
        
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_debit_memos.trans_type', 'salary')
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();
        return (object) array('count' => $count, 'data' => $res);
    }
    public function send_all_collections($request, array $details)
    {
        AcctgDebitMemo::whereIn('gl_account_id', $request->payables)->where('voucher_id',$request->voucher_id)->update($details);
        return true;
    }
    public function send_all_deductions($request, array $details)
    {
        // $details['paid_amount'] = DB::raw('acctg_deductions.amount');
        AcctgAccountDeduction::whereIn('gl_account_id', $request->deductions)->where('voucher_id',$request->voucher_id)->update($details);
        return true;
    }

    public function update_vouchers($voucherID)
    {
        $voucher = AcctgVoucher::find($voucherID);
            $res = AcctgDebitMemo::select([
                '*',
                DB::raw('SUM(ewt_amount) as ewtAmt'),
                DB::raw('SUM(evat_amount) as evatAmt'),
                DB::raw('SUM(total_amount) as totalAmt')
            ])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->groupBy('voucher_id')
            ->get();

        $res2 = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        $res3 = AcctgAccountDeduction::select([
            '*',
            DB::raw('SUM(total_amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        AcctgVoucher::whereId($voucherID)->update([
            'total_payables' => ($res->count() > 0) ? $res->first()->totalAmt : 0,
            'total_ewt' => ($res->count() > 0) ? $res->first()->ewtAmt : 0,
            'total_evat' => ($res->count() > 0) ? $res->first()->evatAmt : 0,
            'total_disbursement' => ($res2->count() > 0) ? $res2->first()->totalAmt : 0,
            'total_deductions' => ($res3->count() > 0) ? $res3->first()->totalAmt : 0
        ]);

        $totalAmt = AcctgAccountDisbursement::select(['acctg_disbursements.*'])
        ->where(['voucher_id' => $voucherID, 'status' =>  'posted', 'is_active' => 1])
        ->sum('amount'); 
        
            $res4 = AcctgDebitMemo::select(['acctg_debit_memos.*'])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->get();
        
        $totalAmt += ($res->count() > 0) ? floatval($res->first()->ewtAmt) : floatval(0);
        $totalAmt += ($res->count() > 0) ? floatval($res->first()->evatAmt) : floatval(0);
        if (!($voucher->is_payable > 0)) {
            $totalAmt += ($res3->count() > 0) ? floatval($res3->first()->totalAmt) : floatval(0);
        }
        while($totalAmt > 0) {
            if ($res4->count() > 0) {
                    foreach ($res4 as $r) {
                        $payables = floatval($r->total_amount);
                        if (floatval($totalAmt) >= floatval($payables)) {
                            AcctgDebitMemo::whereId($r->id)->update(['paid_amount' => $payables]);
                            $totalAmt -= floatval($payables);
                        } else {
                            AcctgDebitMemo::whereId($r->id)->update(['paid_amount' => $totalAmt]);
                            $totalAmt -= floatval($totalAmt);
                        }
                    }
                    break; 
            } 
            break;
        }   

        return true;
    }

    public function get_payroll_data($id) {
        $salary = AcctgDebitMemo::get_total($id, 'salary');
        $pagibig = AcctgDebitMemo::get_total($id, 'pagibig');
        $gsis = AcctgDebitMemo::get_total($id, 'gsis');
        $philhealth = AcctgDebitMemo::get_total($id, 'philhealth');
        $due_to_bir = AcctgDebitMemo::get_total($id, 'due_to_bir');

        $pagibig_pay = AcctgDebitMemo::get_sum($id, 'pagibig');
        $gsis_pay = AcctgDebitMemo::get_sum($id, 'gsis');
        $philhealth_pay = AcctgDebitMemo::get_sum($id, 'philhealth');
        $due_to_bir_pay = AcctgDebitMemo::get_sum($id, 'due_to_bir');

        $deduct = AcctgAccountDeduction::where('voucher_id',$id)->sum('total_amount');
        $gross = 0;
        $payrolls = AcctgAccountDeduction::where('voucher_id', $id)->groupBy('trans_id')->get();
        foreach ($payrolls as $payroll) {
            if ($payroll->payroll) {
                $gross += floatval($payroll->payroll->hrpr_total_salary) + floatval($payroll->payroll->hrpr_earnings);
            }
        }
        return [
            'salaries' => $salary,
            'pagibig' => $pagibig,
            'philhealth' => $philhealth,
            'bir' => $due_to_bir,
            'gsis' => $gsis,

            'pagibig_pay' => $pagibig_pay,
            'philhealth_pay' => $philhealth_pay,
            'bir_pay' => $due_to_bir_pay,
            'gsis_pay' => $gsis_pay,

            'total_deductions' => $pagibig + $gsis + $philhealth + $due_to_bir,
            'gross_salary' => $gross,
        ];
    }
    public function allDisburseType()
    {
        return ['' => 'Please Select'] + AcctgDisburseType::where('is_payroll',1)->get()->mapWithKeys(function ($penalty) {
            $percent = $penalty->id;
            $array[$percent] = $penalty->name;
            return $array;
        })->toArray();
    }

    public function get_debit($voucher, $posted = 1)
    {
        $res = AcctgDebitMemo::select([
            '*',
            'acctg_debit_memos.responsibility_center as centre',
            DB::raw('SUM(acctg_debit_memos.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_debit_memos.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_debit_memos.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_debit_memos.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_debit_memos.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where('acctg_debit_memos.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_debit_memos.gl_account_id', 'acctg_debit_memos.responsibility_center'])
        ->get();
        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_id' => $line->gl_account_id,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }
    public function get_debit_sub($voucher, $gl_id, $posted = 1)
    {
        $res = AcctgDebitMemo::select([
            '*',
            'acctg_debit_memos.responsibility_center as centre',
            DB::raw('SUM(acctg_debit_memos.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_debit_memos.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_debit_memos.total_amount) as totalAmt')
        ])
        ->with(['sl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_debit_memos.voucher_id');
        })
        ->where([
            'acctg_vouchers.voucher_no' => $voucher, 
            'acctg_debit_memos.gl_account_id' => $gl_id, 
            'acctg_debit_memos.is_active' => 1
        ])
        ->whereNotNull('acctg_debit_memos.sl_account_id');
        if ($posted > 0) {
            $res = $res->where('acctg_debit_memos.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_debit_memos.sl_account_id', 'acctg_debit_memos.responsibility_center'])
        ->get();
        // dd($res);
        $res = $res->map(function($line) {
            if ($line->sl_account) {
                return (object) [
                    'centre' => $line->centre,
                    'sl_id' => $line->sl_account_id,
                    'sl_account' => $line->sl_account->description,
                    'sl_code' => $line->sl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            }
        });
        return $res;
    }

    public function get_credit($voucher, $posted = 1)
    {
        $res = AcctgAccountDeduction::select([
            '*',
            'acctg_deductions.responsibility_center as centre',
            DB::raw('SUM(acctg_deductions.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_deductions.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_deductions.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_deductions.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where('acctg_deductions.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_deductions.gl_account_id', 'acctg_deductions.responsibility_center'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_id' => $line->gl_account_id,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }
    public function get_credit_sub($voucher, $gl_id, $posted = 1)
    {
        $res = AcctgAccountDeduction::select([
            '*',
            'acctg_deductions.responsibility_center as centre',
            DB::raw('SUM(acctg_deductions.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_deductions.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_deductions.total_amount) as totalAmt')
        ])
        ->with(['sl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->where([
            'acctg_vouchers.voucher_no' => $voucher, 
            'acctg_deductions.gl_account_id' => $gl_id, 
            'acctg_deductions.is_active' => 1
        ])
        ->whereNotNull('acctg_deductions.sl_account_id');
        if ($posted > 0) {
            $res = $res->where('acctg_deductions.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_deductions.sl_account_id', 'acctg_deductions.responsibility_center'])
        ->get();
        // dd($res);
        $res = $res->map(function($line) {
            if ($line->sl_account) {
                return (object) [
                    'centre' => $line->centre,
                    'sl_id' => $line->sl_account_id,
                    'sl_account' => $line->sl_account->description,
                    'sl_code' => $line->sl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            }
        });

        return $res;
    }

    public function get_to_pay($voucher, $type = null, $posted = 1)
    {
        $res = AcctgAccountDeduction::select([
            '*',
            'acctg_deductions.responsibility_center as centre',
            DB::raw('SUM(acctg_deductions.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_deductions.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_deductions.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_deductions.gl_account_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_deductions.is_active' => 1]);
        if($type) {
            $res->where(['acctg_account_general_ledgers.is_'.$type => 1]);
        }
        if ($posted > 0) {
            $res = $res->where('acctg_deductions.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_deductions.gl_account_id', 'acctg_deductions.responsibility_center'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_id' => $line->gl_account_id,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }

    public function get_gl_payments($voucher, $type, $reference = '', $disburse_type = null, $posted = 1, $collection = 0)
    {       
        $res = AcctgAccountDisbursement::select([
            'acctg_account_general_ledgers.id as id',
            'acctg_account_general_ledgers.description as description',
            'acctg_account_general_ledgers.code as code',
            'disburse_type_id',
            'acctg_disbursements.id as disburse_id',
            'acctg_disbursements.gl_account_id as gl_id',
            'acctg_vouchers.voucher_no',
            DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
        ])
        ->leftJoin('acctg_payment_types', function($join)
        {
            $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        });
        $res->where([
            'acctg_vouchers.voucher_no' => $voucher, 
            'acctg_disbursements.is_active' => 1,
        ]);
        if (isset($reference)) {
            $res->where([
                'acctg_disbursements.reference_no' => $reference, 
            ]);
        }
        if($disburse_type) {
            $disburse_type = DB::table('acctg_disburse_types')->where('code', $disburse_type)->first('id');
            $res->where(['acctg_disbursements.disburse_type_id' => $disburse_type->id]);
        }
        if (!($collection > 0)) {
            $res->where(DB::raw('LOWER(acctg_payment_types.name)'), 'like', '%' . strtolower($type) . '%');
        }
        if ($posted > 0) {
            $res->where(function ($query) {
                $query->where('acctg_disbursements.status', '=', 'posted');
                $query->orWhere('acctg_disbursements.status', '=', 'deposited');
            });
        }
        $res = $res->groupBy(['acctg_disbursements.gl_account_id'])->get();
        $res = $res->map(function($line) {
            return (object) [
                'id' => $line->id,
                'code' => $line->code,
                'description' => $line->description,
                'gl_id' => $line->gl_id,
                'totalPayment' => $line->totalPayment
            ];
        });

        return $res;
    }

    public function get_sl_payments($voucher, $gl_account, $reference = '', $disburse_type = null, $posted = 1)
    {
        $res = AcctgAccountDisbursement::select([
            'acctg_account_subsidiary_ledgers.description as description',
            'acctg_account_subsidiary_ledgers.code as code',
            'disburse_type_id',
            'acctg_disbursements.reference_no',
            'acctg_disbursements.status',
            DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        });

        if (isset($reference)) {
            $res->where([
                'acctg_disbursements.reference_no' => $reference,
                'acctg_vouchers.voucher_no' => $voucher, 
                'acctg_account_general_ledgers.id' => $gl_account, 
                'acctg_disbursements.is_active' => 1
            ]);
        } else {
            $res->where([
                'acctg_vouchers.voucher_no' => $voucher, 
                'acctg_account_general_ledgers.id' => $gl_account, 
                'acctg_disbursements.is_active' => 1
            ]);
        }

        if($disburse_type) {
            $disburse_type = DB::table('acctg_disburse_types')->where('code', $disburse_type)->first('id');
            $res->where(['acctg_disbursements.disburse_type_id' => $disburse_type->id]);
        }

        if ($posted > 0) {
            $res->where(function ($query) {
                $query->where('acctg_disbursements.status', '=', 'posted');
                $query->orWhere('acctg_disbursements.status', '=', 'deposited');
            });
        }
        $res = $res->groupBy(['acctg_disbursements.sl_account_id'])
        ->get();
// dd($res);
        $res = $res->map(function($line) {
            return (object) [
                'code' => $line->code,
                'description' => $line->description,
                'totalPayment' => $line->totalPayment
            ];
        });

        return $res;
    }

    public function approve_deduction($id, array $newDetails) 
    {
        $res1 = AcctgAccountDeduction::find($id);
        $res1 = AcctgAccountDeduction::where('gl_account_id',$res1->gl_account_id);
        $res1->update($newDetails);
        foreach ($res1->get() as $value) {
            if ($res1->status == 'posted') {
                $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountDeduction)->getTable(), 'entity_id' => $id])->get();
                if ($res2->count() > 0) {
                    AcctgTrialBalance::whereId($res2->first()->id)->update([
                        'voucher_id' => $res1->voucher_id,
                        'payee_id' => $res1->voucher->payee_id,
                        'fund_code_id' => $res1->voucher->fund_code_id,
                        'gl_account_id' => $res1->gl_account_id,
                        'sl_account_id' => $res1->sl_account_id,
                        'debit' => $res1->amount,
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
                        'debit' => $res1->amount,
                        'posted_at' => $res1->posted_at,
                        'posted_by' => $res1->posted_by,
                        'entity' => (new AcctgAccountDeduction)->getTable(),
                        'entity_id' => $id
                    ]);
                }
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
                $totalAmt += floatval($res1->amount);
            }
        }
        
    }
}