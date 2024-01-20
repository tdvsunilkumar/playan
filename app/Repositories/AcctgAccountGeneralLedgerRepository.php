<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountGeneralLedgerRepositoryInterface;
use App\Models\AcctgAccountGroup;
use App\Models\AcctgAccountGroupMajor;
use App\Models\AcctgAccountGroupSubmajor;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgFundCode;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgBank;
use App\Models\AcctgRptReceivableCY;

class AcctgAccountGeneralLedgerRepository implements AcctgAccountGeneralLedgerRepositoryInterface 
{
    public function getAll() 
    {
        return AcctgAccountGeneralLedger::all();
    }

    public function find($id) 
    {
        return AcctgAccountGeneralLedger::with(['subsidiaries'])->findOrFail($id);
    }
    
    public function find_sl($id)
    {
        return AcctgAccountSubsidiaryLedger::findOrFail($id);
    }

    public function validate($code, $acctGroup, $majorAcctGroup, $id = '')
    {   
        if ($id !== '') {
            return AcctgAccountGeneralLedger::where(['code' => $code, 'acctg_account_group_id' => $acctGroup, 'acctg_account_group_major_id' => $majorAcctGroup])->where('id', '!=', $id)->count();
        } 
        return AcctgAccountGeneralLedger::where(['code' => $code, 'acctg_account_group_id' => $acctGroup, 'acctg_account_group_major_id' => $majorAcctGroup])->count();
    }

    public function create(array $details) 
    {
        return AcctgAccountGeneralLedger::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgAccountGeneralLedger::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {
        $columns = array( 
            0 => 'acctg_account_general_ledgers.id',
            1 => 'acctg_account_groups.code',
            2 => 'acctg_account_groups_majors.prefix',
            3 => 'acctg_account_groups_submajors.prefix',
            4 => 'acctg_account_general_ledgers.code',
            5 => 'acctg_account_general_ledgers.description',
            6 => 'acctg_account_general_ledgers.normal_balance'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_general_ledgers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountGeneralLedger::select([
            '*', 
            'acctg_account_general_ledgers.id as aglId', 
            'acctg_account_general_ledgers.code as aglCode', 
            'acctg_account_general_ledgers.description as aglDesc',
            'acctg_account_general_ledgers.is_active as aglStatus',
            'acctg_account_general_ledgers.created_at as aglCreatedAt',
            'acctg_account_general_ledgers.updated_at as aglUpdatedAt'
        ])
        ->leftJoin('acctg_account_groups', function($join)
        {
            $join->on('acctg_account_groups.id', '=', 'acctg_account_general_ledgers.acctg_account_group_id');
        })
        ->leftJoin('acctg_account_groups_majors', function($join)
        {
            $join->on('acctg_account_groups_majors.id', '=', 'acctg_account_general_ledgers.acctg_account_group_major_id');
        })
        ->leftJoin('acctg_account_groups_submajors', function($join)
        {
            $join->on('acctg_account_groups_submajors.id', '=', 'acctg_account_general_ledgers.acctg_account_group_submajor_id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'acctg_account_general_ledgers.acctg_fund_code_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_majors.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_majors.prefix', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups_submajors.prefix', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.normal_balance', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_groups.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function sub_listItems($request, $id)
    {
        $columns = array( 
            0 => 'acctg_account_subsidiary_ledgers.prefix',
            1 => 'acctg_account_subsidiary_ledgers.code',
            2 => 'acctg_account_subsidiary_ledgers.description'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_subsidiary_ledgers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgAccountSubsidiaryLedger::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_subsidiary_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.prefix', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_account_subsidiary_ledgers.gl_account_id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function current_listItems($request, $id)
    {
        $columns = array( 
            0 => 'acctg_rpt_receivable_cy.id',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_rpt_receivable_cy.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = AcctgRptReceivableCY::select([
            'acctg_rpt_receivable_cy.*'
        ])
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'acctg_rpt_receivable_cy.fund_code_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_rpt_receivable_cy.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_rpt_receivable_cy.sl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_rpt_receivable_cy.income_sl', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allGLSLAccounts()
    {
        return (new AcctgAccountSubsidiaryLedger)->allGLSLAccounts();
    }

    public function allAccountGroups()
    {
        return (new AcctgAccountGroup)->allAccountGroups();
    }

    public function allBanks()
    {
        return (new AcctgBank)->allBanks();
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function findAcctGrp($account)
    {
        return AcctgAccountGroup::find($account);
    }
    
    public function findMajorAcctGrp($major)
    {
        return AcctgAccountGroupMajor::find($major);
    }

    public function findSubMajorAcctGrp($submajor)
    {
        return AcctgAccountGroupSubmajor::find($submajor);
    }

    public function reload_major_account($account)
    {
        return (new AcctgAccountGroupMajor)->reload_major_account($account);
    }

    public function reload_submajor_account($account, $major)
    {
        return (new AcctgAccountGroupSubmajor)->reload_submajor_account($account, $major);
    }

    public function reload_parent($gl, $sl)
    {
        return (new AcctgAccountSubsidiaryLedger)->reload_parent($gl, $sl);
    }
}