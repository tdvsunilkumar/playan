<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountReceivableInterface;
use App\Models\CtoReceivable;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgExpandedVatableTax;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgFundCode;
use App\Models\User;
use DB;

class AcctgAccountReceivableRepository implements AcctgAccountReceivableInterface 
{
    public function find($id) 
    {
        return CtoReceivable::findOrFail($id);
    }

    public function create(array $details) 
    {
        return CtoReceivable::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CtoReceivable::whereId($id)->update($newDetails);
    }
    
    public function listItems($request)
    {   
        $columns = array( 
            0 => 'cto_receivables.id',
            1 => 'acctg_fund_codes.code',
            2 => 'acctg_account_general_ledgers.code',
            3 => 'cto_receivables.description',
            4 => 'cto_receivables.amount_due',
            5 => 'cto_receivables.amount_pay',
            6 => 'cto_receivables.remaining_amount',
            7 => 'cto_receivables.due_date',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_receivables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $result = CtoReceivable::select([
            'cto_receivables.*',
            DB::raw('SUM(cto_receivables.amount_due) as totalAmtDue'),
            DB::raw('SUM(cto_receivables.amount_pay) as totalAmtPay'),
            DB::raw('SUM(cto_receivables.remaining_amount) as totalAmtBal')
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_receivables.gl_account_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cto_receivables.fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_receivables.amount_due', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.description', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.amount_pay', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.due_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_receivables.is_active', '=', 1);
        if ($status != 'all') {
            $result->where('cto_receivables.is_paid', $status);
        }
        $result = $result->get();
        

        $res = CtoReceivable::select([
            'cto_receivables.*',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_receivables.gl_account_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cto_receivables.fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_receivables.amount_due', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.description', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.amount_pay', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.due_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_receivables.is_active', '=', 1)
        ->orderBy($column, $order);
        if ($status != 'all') {
            $res->where('cto_receivables.is_paid', $status);
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        $totalAmtDue = $result->first()->totalAmtDue;
        $totalAmtPay = $result->first()->totalAmtPay;
        $totalAmtBal = $result->first()->totalAmtBal;

        return (object) array('count' => $count, 'data' => $res, 'total_due' => $totalAmtDue, 'total_pay' => $totalAmtPay, 'total_balance' => $totalAmtBal);
    }
}