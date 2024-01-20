<?php

namespace App\Repositories;

use App\Interfaces\CboObligationTypeInterface;
use App\Models\CboObligationType;
use App\Models\AcctgFundCode;
use App\Models\AcctgAccountGeneralLedger;

class CboObligationTypeRepository implements CboObligationTypeInterface 
{
    public function find($id) 
    {
        return CboObligationType::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return CboObligationType::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return CboObligationType::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return CboObligationType::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CboObligationType::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'cbo_obligation_types.id',
            1 => 'acctg_fund_codes.code',
            2 => 'acctg_account_general_ledgers.code',
            3 => 'cbo_obligation_types.code',
            4 => 'cbo_obligation_types.name',   
            5 => 'cbo_obligation_types.description',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cbo_obligation_types.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CboObligationType::select([
            '*',
            'cbo_obligation_types.id as identity',
            'cbo_obligation_types.created_at as identityCreatedAt',
            'cbo_obligation_types.updated_at as identityUpdatedAt',
            'cbo_obligation_types.code as identityCode',
            'cbo_obligation_types.name as identityName',
            'cbo_obligation_types.description as identityDescription',
            'cbo_obligation_types.is_active as identityStatus',
        ])
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cbo_obligation_types.fund_code_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_obligation_types.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cbo_obligation_types.id', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_obligation_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }
}