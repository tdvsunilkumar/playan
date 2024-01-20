<?php

namespace App\Repositories;

use App\Interfaces\ReportAcctgLedgerInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgFundCode;
use App\Models\Client;
use App\Models\GsoSupplier;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgGeneralJournal;
use App\Models\AcctgGeneralJournalEntry;
use App\Models\RptLocality;
use App\Models\AcctgTrialBalance;
use App\Models\AcctgSLAccountReport;
use App\Models\AcctgGLAccountReport;
Use App\Models\User;
use DB;

class ReportAcctgLedgerRepository implements ReportAcctgLedgerInterface 
{
    public function find($id) 
    {
        return AcctgAccountGeneralLedger::findOrFail($id);
    }

    public function allFundCodes()
    {
       return (new AcctgFundCode)->allFundCodes();
    }
    
    public function reload($type)
    {
        if ($type == 'general-ledger') {
            $res = AcctgAccountGeneralLedger::where(['is_active' => 1])->get();
            return $res;
        } else {
            $res = AcctgAccountSubsidiaryLedger::where(['is_active' => 1])->get();
            return $res;
        }
    }

    public function reload_category_name($category) 
    {
        if ($category == 'Clients') {
            $res = Client::where(['is_active' => 1])->get();
            $res = $res->map(function($category){
                return (object) [
                    'id' => $category->id,
                    'fullname' => ucwords($category->rpo_first_name).' '.ucwords($category->rpo_middle_name).' '.ucwords($category->rpo_custom_last_name)
                ];
            });
        } else {
            $res = GsoSupplier::where(['is_active' => 1])->get();
            $res = $res->map(function($category){
                return (object) [
                    'id' => $category->id,
                    'fullname' => $category->branch_name ? ucwords($category->business_name).' ('.ucwords($category->branch_name).')' : ucwords($category->business_name)
                ];
            });
        }
        return $res;
    }

    public function get_general_journals()
    {
        $sql2 = AcctgGeneralJournalEntry::select([
            'acctg_general_journals.general_journal_no as jevNo',
            DB::raw('SUM(acctg_general_journals_entries.credit_amount) as payables'),
            DB::raw('SUM(0) as ewt'),
            DB::raw('SUM(0) as evat'),
            'cbo_payee.paye_name as payee',
            'acctg_general_journals.particulars as particulars',
            'acctg_general_journals.transaction_date as postedDate',
            DB::raw('SUM(acctg_general_journals_entries.debit_amount) as totalAmt'),
            DB::raw('CONCAT("General Journal") as type'),
            'acctg_account_general_ledgers.normal_balance as norms',
            'acctg_account_general_ledgers.id as glID',
            'acctg_account_general_ledgers.code as glCode',
            'acctg_account_general_ledgers.description as glDesc',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_general_journals_entries.gl_account_id');
        })
        ->leftJoin('acctg_general_journals', function($join)
        {
            $join->on('acctg_general_journals.id', '=', 'acctg_general_journals_entries.general_journal_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_general_journals.payee_id');
        })
        ->where([
            'acctg_general_journals.status' => 'completed',
            'acctg_general_journals_entries.is_active' => 1
        ])
        ->where('acctg_general_journals.fund_code_id', '=', $request->get('fund_code_id'))
        ->whereBetween('acctg_general_journals.transaction_date', [$request->get('date_from'), $request->get('date_to')]);
        if (!empty($request->name)) {
            $sql2 = $sql2->where('cbo_payee.hr_employee_id', $request->name);
        }  
        if($request->code > 0) {
            $sql2 = $sql2->where('acctg_account_general_ledgers.id', '=', $request->code);
        }
        $sql2 = $sql2->groupBy(['type', 'jevNo', 'glID']);
    }

    public function get($request)
    {   
        if ($request->ledger_type == 'subsidiary-ledger') {
            $res = AcctgSLAccountReport::select([
                'acctg_sl_accounts_reports.*',
                'acctg_sl_accounts_reports.id as identity',
                'acctg_sl_accounts_reports.posted_at as postedDate',
                DB::raw('SUM(acctg_sl_accounts_reports.debit_amount) as totalDebit'),
                DB::raw('SUM(acctg_sl_accounts_reports.credit_amount) as totalCredit'),
                DB::raw('SUM(acctg_sl_accounts_reports.tax_amount) as totalTax'),
                'acctg_vouchers.remarks as particulars',
                'acctg_vouchers.voucher_no as jevNo',
                'cbo_payee.paye_name as payee',
                'acctg_account_general_ledgers.normal_balance as norms',
                'acctg_account_general_ledgers.id as glID',
                'acctg_account_general_ledgers.code as glCode',
                'acctg_account_general_ledgers.description as glDesc',
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_sl_accounts_reports.gl_account_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_sl_accounts_reports.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_sl_accounts_reports.payee_id');
            })
            ->where([
                'acctg_sl_accounts_reports.is_active' => 1
            ])
            ->where('acctg_sl_accounts_reports.fund_id', '=', $request->get('fund_code_id'))
            ->whereBetween('acctg_sl_accounts_reports.posted_at', [$request->get('date_from').' 00:00:00', $request->get('date_to'). ' 23:59:59']);
            if (!empty($request->name)) {
                if ($request->category == 'Clients') {
                    $res = $res->where('cbo_payee.hr_employee_id', $request->name);
                } else {
                    $res = $res->where('cbo_payee.scp_id', $request->name);
                }
            }       
            if($request->code > 0) {
                $res = $res->where('acctg_account_subsidiary_ledgers.id', '=', $request->code);
            }
            $res = $res->groupBy(['jevNo'])->orderBy('jevNo', 'asc')->orderBy('identity', 'asc')->get();
        } else {
            $res = AcctgGLAccountReport::select([
                'acctg_gl_accounts_reports.*',
                'acctg_gl_accounts_reports.id as identity',
                'acctg_gl_accounts_reports.posted_at as postedDate',
                DB::raw('SUM(acctg_gl_accounts_reports.debit_amount) as totalDebit'),
                DB::raw('SUM(acctg_gl_accounts_reports.credit_amount) as totalCredit'),
                'acctg_vouchers.remarks as particulars',
                'acctg_vouchers.voucher_no as jevNo',
                'cbo_payee.paye_name as payee',
                'acctg_account_general_ledgers.normal_balance as norms',
                'acctg_account_general_ledgers.id as glID',
                'acctg_account_general_ledgers.code as glCode',
                'acctg_account_general_ledgers.description as glDesc',
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_gl_accounts_reports.gl_account_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_gl_accounts_reports.voucher_id');
            })
            ->leftJoin('cbo_payee', function($join)
            {
                $join->on('cbo_payee.id', '=', 'acctg_gl_accounts_reports.payee_id');
            })
            ->where([
                'acctg_gl_accounts_reports.is_active' => 1
            ])
            ->where('acctg_gl_accounts_reports.fund_id', '=', $request->get('fund_code_id'))
            ->whereBetween('acctg_gl_accounts_reports.posted_at', [$request->get('date_from').' 00:00:00', $request->get('date_to'). ' 23:59:59']);
            if (!empty($request->name)) {
                if ($request->category == 'Clients') {
                    $res = $res->where('cbo_payee.hr_employee_id', $request->name);
                } else {
                    $res = $res->where('cbo_payee.scp_id', $request->name);
                }
            }       
            if($request->code > 0) {
                $res = $res->where('acctg_account_general_ledgers.id', '=', $request->code);
            }
            $res = $res->groupBy(['jevNo', 'glID'])->orderBy('jevNo', 'asc')->orderBy('identity', 'asc')->get();
        }

        if (!empty($res)) {
            if ($request->ledger_type == 'subsidiary-ledger') {
                return $res = $res->map(function($rows) {
                    $debit = $rows->totalDebit ? floatval($rows->totalDebit) + ($rows->totalTax ? floatval($rows->totalTax) : 0) : 0;
                    $credit = $rows->totalCredit ? $rows->totalCredit : 0;
                    $balance = floatval($debit) - floatval($credit);
                    return (object) [
                        'jev_no' => $rows->jevNo,
                        'type' => '',
                        'account_code' => $rows->glCode,
                        'account_desc' => $rows->glDesc,
                        'posted' => date('d-M-Y', strtotime($rows->postedDate)),
                        'payee' => $rows->payee ? ucwords($rows->payee) : '',
                        'particulars' => $rows->particulars,
                        'norms' => $rows->norms,
                        'debit' => $debit,
                        'credit' => $credit,
                        'balance' => $balance,
                    ];
                });
            } else {
                return $res = $res->map(function($rows) {
                    $debit = $rows->totalDebit ? $rows->totalDebit : 0;
                    $credit = $rows->totalCredit ? $rows->totalCredit : 0;
                    $balance = floatval($debit) - floatval($credit);
                    return (object) [
                        'jev_no' => $rows->jevNo,
                        'type' => $rows->type,
                        'account_code' => $rows->glCode,
                        'account_desc' => $rows->glDesc,
                        'posted' => date('d-M-Y', strtotime($rows->postedDate)),
                        'payee' => $rows->payee ? ucwords($rows->payee) : '',
                        'particulars' => $rows->particulars,
                        'norms' => $rows->norms,
                        'debit' => $debit,
                        'credit' => $credit,
                        'balance' => $balance,
                    ];
                });
            }
        } else {
            return [];
        }
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
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