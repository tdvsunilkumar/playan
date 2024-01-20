<?php

namespace App\Repositories;

use App\Interfaces\ReportAcctgTrialBalanceInterface;
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
use App\Models\User;
use App\Models\AcctgGLAccountReport;
use DB;

class ReportAcctgTrialBalanceRepository implements ReportAcctgTrialBalanceInterface 
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

    public function get($request)
    {
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
        $res = $res->groupBy(['glID'])->orderBy('glID', 'asc')->orderBy('identity', 'asc')->get();

        if (!empty($res)) {
            return $res = $res->map(function($rows) {
                $debit = $rows->totalDebit ? $rows->totalDebit : 0;
                $credit = $rows->totalCredit ? $rows->totalCredit : 0;
                if ($rows->norms == 'Debit') {
                    $balance = floatval($debit) - floatval($credit);
                } else {
                    $balance = floatval($credit) - floatval($debit);
                }
                return (object) [
                    'code' => $rows->glCode,
                    'title' => $rows->glDesc,
                    'debit' => ($rows->norms == 'Debit') ? $balance : '',
                    'credit' => ($rows->norms == 'Debit') ? '' : $balance
                ];
            });
        } else {
            return [];
        }
    }
    
    public function gets($request)
    {   
        // $sql = AcctgTrialBalance::select([
        //     'acctg_trial_balance.id as identity',
        //     'acctg_general_journals.general_journal_no as jevNo',
        //     'cbo_payee.paye_name as payee',
        //     'acctg_general_journals.particulars as particulars',
        //     'acctg_trial_balance.posted_at as postedDate',
        //     DB::raw('SUM(acctg_trial_balance.debit) as totalDebit'),
        //     DB::raw('SUM(acctg_trial_balance.credit) as totalCredit'),
        //     DB::raw('(CASE 
        //                 WHEN entity = "acctg_payables" THEN "Payables"
        //                 WHEN entity = "acctg_incomes" THEN "Incomes"
        //                 WHEN entity = "acctg_general_journals_entries" THEN "General Journals"
        //                 WHEN entity = "acctg_disbursements" THEN "Disbursement"
        //                 WHEN entity = "acctg_deductions" THEN "Deductions"
        //                 ELSE "Debit Memo"
        //             END) as type'),
        //     'acctg_account_general_ledgers.normal_balance as norms',
        //     'acctg_account_general_ledgers.id as glID',
        //     'acctg_account_general_ledgers.code as glCode',
        //     'acctg_account_general_ledgers.description as glDesc',
        // ])
        // ->leftJoin('acctg_account_general_ledgers', function($join)
        // {
        //     $join->on('acctg_account_general_ledgers.id', '=', 'acctg_trial_balance.gl_account_id');
        // })
        // ->leftJoin('acctg_general_journals', function($join)
        // {
        //     $join->on('acctg_general_journals.id', '=', 'acctg_trial_balance.voucher_id');
        // })
        // ->leftJoin('cbo_payee', function($join)
        // {
        //     $join->on('cbo_payee.id', '=', 'acctg_trial_balance.payee_id');
        // })
        // ->where('acctg_trial_balance.entity', '=', 'acctg_general_journals_entries')
        // ->where([
        //     'acctg_trial_balance.is_active' => 1
        // ])
        // ->where('acctg_trial_balance.fund_code_id', '=', $request->get('fund_code_id'))
        // ->whereBetween('acctg_trial_balance.posted_at', [$request->get('date_from').' 00:00:00', $request->get('date_to'). ' 23:59:59']);
        // if (!empty($request->name)) {
        //     if ($request->category == 'Clients') {
        //         $sql = $sql->where('cbo_payee.hr_employee_id', $request->name);
        //     } else {
        //         $sql = $sql->where('cbo_payee.scp_id', $request->name);
        //     }
        // }       
        // if($request->code > 0) {
        //     $sql = $sql->where('acctg_account_general_ledgers.id', '=', $request->code);
        // }
        // $sql = $sql->groupBy(['glID']);

        $res = AcctgTrialBalance::select([
            'acctg_trial_balance.id as identity',
            // 'acctg_vouchers.voucher_no as jevNo',
            // 'cbo_payee.paye_name as payee',
            // 'acctg_vouchers.remarks as particulars',
            'acctg_trial_balance.posted_at as postedDate',
            DB::raw('SUM(acctg_trial_balance.debit) as totalDebit'),
            DB::raw('SUM(acctg_trial_balance.credit) as totalCredit'),
            // DB::raw('(CASE 
            //             WHEN entity = "acctg_payables" THEN "Payables"
            //             WHEN entity = "acctg_incomes" THEN "Incomes"
            //             WHEN entity = "acctg_general_journals_entries" THEN "General Journals"
            //             WHEN entity = "acctg_disbursements" THEN "Disbursement"
            //             WHEN entity = "acctg_deductions" THEN "Deductions"
            //             ELSE "Debit Memo"
            //         END) as type'),
            'acctg_account_general_ledgers.normal_balance as norms',
            'acctg_account_general_ledgers.id as glID',
            'acctg_account_general_ledgers.code as glCode',
            'acctg_account_general_ledgers.description as glDesc',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_trial_balance.gl_account_id');
        })
        // ->leftJoin('acctg_vouchers', function($join)
        // {
        //     $join->on('acctg_vouchers.id', '=', 'acctg_trial_balance.voucher_id');
        // })
        // ->leftJoin('cbo_payee', function($join)
        // {
        //     $join->on('cbo_payee.id', '=', 'acctg_trial_balance.payee_id');
        // })
        ->where('acctg_trial_balance.entity', '!=', 'acctg_general_journals_entries')
        ->where([
            'acctg_trial_balance.is_active' => 1
        ])
        ->where('acctg_trial_balance.fund_code_id', '=', $request->get('fund_code_id'))
        ->whereBetween('acctg_trial_balance.posted_at', [$request->get('date_from').' 00:00:00', $request->get('date_to'). ' 23:59:59']);
        // if (!empty($request->name)) {
        //     if ($request->category == 'Clients') {
        //         $res = $res->where('cbo_payee.hr_employee_id', $request->name);
        //     } else {
        //         $res = $res->where('cbo_payee.scp_id', $request->name);
        //     }
        // }       
        if($request->code > 0) {
            $res = $res->where('acctg_account_general_ledgers.id', '=', $request->code);
        }
        // $res = $res->groupBy(['glID']);
        $res = $res->groupBy(['glID'])->orderBy('glCode', 'asc')->get();

        if (!empty($res)) {
            return $res = $res->map(function($rows) {
                $debit = $rows->totalDebit ? $rows->totalDebit : 0;
                $credit = $rows->totalCredit ? $rows->totalCredit : 0;
                if ($rows->norms == 'Debit') {
                    $balance = $debit - $credit;
                } else {
                    $balance = $credit - $debit;
                }
                if ($balance < 0) {
                    if($rows->norms == 'Debit') {
                        $credit = $debit;
                        $debit = $balance;
                    } else {
                        $debit = $credit;
                        $credit = $balance;
                    }
                } else if ($balance == 0) {
                    $debit = 0;
                    $credit = 0;
                }
                return (object) [
                    'code' => $rows->glCode,
                    'title' => $rows->glDesc,
                    'debit' => $this->money_format($debit),
                    'credit' => $this->money_format($credit)
                ];
            });
        } else {
            return [];
        }
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
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