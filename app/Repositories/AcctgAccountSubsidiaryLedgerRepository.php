<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountSubsidiaryLedgerInterface;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgRptReceivableCY;

class AcctgAccountSubsidiaryLedgerRepository implements AcctgAccountSubsidiaryLedgerInterface 
{
    public function getAll() 
    {
        return AcctgAccountSubsidiaryLedger::all();
    }

    public function find($id) 
    {
        return AcctgAccountSubsidiaryLedger::findOrFail($id);
    }
    
    public function find_current($id) 
    {
        return AcctgRptReceivableCY::findOrFail($id);
    }

    public function validate_current($incomeSL, $fund, $glID, $slID, $is_debit, $id = '')
    {
        if ($id !== '') {
            return AcctgRptReceivableCY::where(['fund_code_id' => $fund, 'income_sl' => $incomeSL, 'gl_account_id' => $glID, 'sl_account_id' => $slID, 'is_debit' => $is_debit])->where('id', '!=', $id)->count();
        } 
        return AcctgRptReceivableCY::where(['fund_code_id' => $fund, 'income_sl' => $incomeSL, 'gl_account_id' => $glID, 'sl_account_id' => $slID, 'is_debit' => $is_debit])->count();
    }

    public function create_current(array $details) 
    {
        return AcctgRptReceivableCY::create($details);
    }

    public function update_current($id, array $newDetails) 
    {
        return AcctgRptReceivableCY::whereId($id)->update($newDetails);
    }

    public function validate($code, $gl_id, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountSubsidiaryLedger::where(['code' => $code, 'gl_account_id' => $gl_id])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountSubsidiaryLedger::where(['code' => $code, 'gl_account_id' => $gl_id])->count();
    }

    public function create(array $details) 
    {
        return AcctgAccountSubsidiaryLedger::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountSubsidiaryLedger::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {
        $columns = array( 
            0 => 'acctg_account_subsidiary_ledgers.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_account_subsidiary_ledgers.prefix',
            3 => 'acctg_account_subsidiary_ledgers.code',
            4 => 'acctg_account_subsidiary_ledgers.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $cols[]    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_general_ledgers.id' : $columns[$request->get('order')['0']['column']];
        $order[]   = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];

        if (isset($request->get('order')['1']['column'])) { 
            $cols[] =  $columns[$request->get('order')['1']['column']];
            $order[] = $request->get('order')['1']['dir'];
        }     

        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountSubsidiaryLedger::select([
            '*',
            'acctg_account_subsidiary_ledgers.id as subId',
            'acctg_account_subsidiary_ledgers.prefix as subPrefix',
            'acctg_account_subsidiary_ledgers.code as subCode',
            'acctg_account_subsidiary_ledgers.description as subDesc',
            'acctg_account_subsidiary_ledgers.created_at as subCreatedAt',
            'acctg_account_subsidiary_ledgers.updated_at as subUpdatedAt',
            'acctg_account_subsidiary_ledgers.is_active as subStatus'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_account_subsidiary_ledgers.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_subsidiary_ledgers.prefix', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%');
            }
        });
        // $res->orderBy('acctg_account_general_ledgers.code', 'asc');
        // $res->orderBy('acctg_account_subsidiary_ledgers.prefix', 'asc');

        $index = 0;
        foreach ($cols as $col) {
            $res->orderBy($col, $order[$index]);
            $index++;
        }

        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}