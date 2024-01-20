<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\CboBudgetBreakdown;
use Illuminate\Http\Request;

class ReportBudgetExpenseController extends Controller
{
    private $carbon;
    private $slugs;
    public function __construct(
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->carbon = $carbon;
        $this->slugs = 'reports/finance/budget-expense';
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        // $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $export_as = ['' => 'Select export type', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.finance.budget-expense.index')->with(compact('export_as', 'orders'));
    }
    public function export_to_pdf(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $months = [];
        foreach (CarbonPeriod::create($request->date_from, '1 month', $request->date_to) as $month) {
            $months[$month->format('m-Y')] = $month->format('M <\\b\\r> Y');
        }

        $rows = CboBudgetBreakdown::join('cbo_budgets', 'cbo_budgets.id', 'cbo_budget_breakdowns.budget_id')
        ->join('acctg_departments', 'acctg_departments.id', 'cbo_budgets.department_id')
        ->join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', 'cbo_budget_breakdowns.gl_account_id')
        ->where('cbo_budgets.status','locked')
        ->select('cbo_budget_breakdowns.annual_budget', 'cbo_budget_breakdowns.final_budget','acctg_departments.code','acctg_departments.name','acctg_account_general_ledgers.description as gl_name','cbo_budget_breakdowns.alignment','amount_used','alignment','final_budget')
        ->get();
        // dd($rows);  
        return view('reports.finance.budget-expense.pageview')->with(compact('rows','months'));
    }
}
