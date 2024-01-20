<?php

namespace App\Repositories;

use App\Interfaces\GsoItemCategoryRepositoryInterface;
use App\Models\GsoItemCategory;
use App\Models\GsoItem;
use App\Models\AcctgAccountGeneralLedger;

class GsoItemCategoryRepository implements GsoItemCategoryRepositoryInterface 
{
    public function getAll() 
    {
        return GsoItemCategory::all();
    }

    public function find($id) 
    {
        return GsoItemCategory::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoItemCategory::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoItemCategory::where(['code' => $code])->count();
    }

    public function validate_items($id)
    {
        return GsoItem::where(['item_category_id' => $id, 'is_active' => 1])->count();
    }

    public function create(array $details) 
    {
        return GsoItemCategory::create($details);
    }

    public function update($id, array $newDetails) 
    {   
        $items = GsoItem::where(['item_category_id' => $id])->update([
            'gl_account_id' => $newDetails['gl_account_id']
        ]);
        return GsoItemCategory::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_item_categories.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'gso_item_categories.code',
            3 => 'gso_item_categories.description',
            4 => 'gso_item_categories.remarks'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_item_categories.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItemCategory::select([
            '*',
            'gso_item_categories.id as itcId',
            'gso_item_categories.code as itcCode',
            'gso_item_categories.description as itcDescription',
            'gso_item_categories.remarks as itcRemarks',
            'gso_item_categories.created_at as itcCreated_at',
            'gso_item_categories.updated_at as itcUpdated_at',
            'gso_item_categories.is_active as itcStatus',
        ])
        ->with([
            'gl_account' =>  function($q) { 
                $q->select([
                    'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
            }
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_item_categories.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_item_categories.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.remarks', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }
}