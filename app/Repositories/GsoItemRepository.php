<?php

namespace App\Repositories;

use App\Interfaces\GsoItemRepositoryInterface;
use App\Models\GsoItem;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoItemType;
use App\Models\GsoItemCategory;
use App\Models\GsoPurchaseType;
use App\Models\GsoUnitOfMeasurement;
use App\Models\FileUpload;
use App\Models\GsoItemHistory;
use App\Models\GsoIssuance;
use App\Models\GsoIssuanceDetails;
use App\Models\AcctgDepartment;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\AcctgDepartmentDivision;
use App\Models\GsoPPMPDetail;
use App\Models\GsoItemConversion;
use App\Models\HoMedicalItemCategory;

class GsoItemRepository implements GsoItemRepositoryInterface 
{
    public function getAll() 
    {
        return GsoItem::all();
    }

    public function getAllConversion($itemID) 
    {
        return GsoItemConversion::where(['item_id' => $itemID])->get();
    }

    public function find($id) 
    {
        return GsoItem::with(['category'])->findOrFail($id);
    }

    public function find_conversion($id)
    {
        return GsoItemConversion::findOrFail($id);
    }

    public function showData($id) 
    {
        $gsoItem=GsoItem::findOrFail($id);
        $data=[
                'id' => $gsoItem->id,
                'item_code' => $gsoItem->code,
                'item_category_id' => $gsoItem->category->code."-".$gsoItem->category->description,
                'gl_account_id' => $gsoItem->gl_account->code."-".$gsoItem->gl_account->description,
                'name' => $gsoItem->name,
                'description' => $gsoItem->description,
                'remarks' => $gsoItem->remarks,
                'quantity_inventory' => $gsoItem->quantity_inventory,
                'uom_id' => $gsoItem->uom->code,
                'quantity_reserved' => $gsoItem->quantity_reserved,
                'latest_cost' => $gsoItem->latest_cost,
                'total_cost' => $gsoItem->latest_cost,
                'filter_type' => 1,
              ];
              return $data;
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoItem::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoItem::where(['code' => $code])->count();
    }

    public function validate_conversion($itemID, $baseUOM, $conversionUOM, $conversionID = '')
    {
        if ($conversionID !== '') {
            return GsoItemConversion::where(['item_id' => $itemID, 'based_uom' => $baseUOM, 'conversion_uom' => $conversionUOM])->where('id', '!=', $conversionID)->count();
        } 
        return GsoItemConversion::where(['item_id' => $itemID, 'based_uom' => $baseUOM, 'conversion_uom' => $conversionUOM])->count();
    }   

    public function create(array $details) 
    {
        return GsoItem::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoItem::whereId($id)->update($newDetails);
    }

    public function create_conversion(array $details) 
    {
        return GsoItemConversion::create($details);
    }

    public function update_conversion($id, array $newDetails) 
    {
        return GsoItemConversion::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'gso_item_types.description',
            3 => 'gso_item_categories.code',
            4 => 'gso_items.code',   
            5 => 'gso_items.name',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
            '*',
            'gso_items.id as identity',
            'gso_items.code as identityCode',
            'gso_items.name as identityName',
            'gso_items.description as identityDesc',
            'gso_items.created_at as identityCreatedAt',
            'gso_items.updated_at as identityUpdatedAt',                 
            'gso_items.weighted_cost as identityWeightedCost',
            'gso_items.latest_cost as identityLatestCost',
            'gso_items.latest_cost_date as identityLatestCostDate',       
            'gso_items.is_active as identityStatus',
        ])
        ->with([
            'gl_account' =>  function($q) { 
                $q->select([
                    'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
            },
            'type' =>  function($q) { 
                $q->select([
                    'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
            },
            'category' =>  function($q) { 
                $q->select([
                    'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
            },
            'uom' =>  function($q) { 
                $q->select([
                    'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
            },
            'pur_type' =>  function($q) { 
                $q->select([
                    'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
            }
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
        })
        ->leftJoin('gso_item_types', function($join)
        {
            $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
        })
        ->leftJoin('gso_item_categories', function($join)
        {
            $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);

        $column = ($sortBy == '') ? 'gso_items.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;
    }

    public function health_listItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'gso_item_types.description',
            3 => 'gso_item_categories.code',
            4 => 'gso_items.code',   
            5 => 'gso_items.name',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
            '*',
            'gso_items.id as identity',
            'gso_items.code as identityCode',
            'gso_items.name as identityName',
            'gso_items.description as identityDesc',
            'gso_items.created_at as identityCreatedAt',
            'gso_items.updated_at as identityUpdatedAt',                 
            'gso_items.weighted_cost as identityWeightedCost',
            'gso_items.latest_cost as identityLatestCost',
            'gso_items.latest_cost_date as identityLatestCostDate',       
            'gso_items.is_active as identityStatus',
        ])
        ->with([
            'gl_account' =>  function($q) { 
                $q->select([
                    'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
            },
            'type' =>  function($q) { 
                $q->select([
                    'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
            },
            'category' =>  function($q) { 
                $q->select([
                    'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
            },
            'uom' =>  function($q) { 
                $q->select([
                    'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
            },
            'pur_type' =>  function($q) { 
                $q->select([
                    'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
            }
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
        })
        ->leftJoin('gso_item_types', function($join)
        {
            $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
        })
        ->leftJoin('gso_item_categories', function($join)
        {
            $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
        })
        ->where('gso_item_categories.is_health_safety', 1)
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);

        $column = ($sortBy == '') ? 'gso_items.id' : $sortBy;
        $order  = ($orderBy == '') ? 'asc' : $orderBy;
    }

    public function newlistItems($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'gso_items.code',
            2 => 'gso_items.name',
            3 => 'gso_items.description',
            4 => 'gso_items.weighted_cost',   
            5 => 'gso_items.latest_cost', 
            6 => 'gso_items.quantity_inventory',
            7 => 'acctg_account_general_ledgers.code',
            8 => 'acctg_account_general_ledgers.description',
            9 => 'gso_item_types.code',
            10 => 'gso_item_types.description',
            11 => 'gso_item_categories.code',
            12 => 'gso_item_categories.description',
            13 => 'gso_unit_of_measurements.code',
            14 => 'gso_purchase_types.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];


        $res = GsoItem::select([
                '*', 
                'gso_items.id as itemId', 
                'gso_items.code as itemCode', 
                'gso_items.name as itemName',
                'gso_items.description as itemDesc',
                'gso_items.is_active as itemStatus',
                'gso_items.created_at as itemCreatedAt',
                'gso_items.updated_at as itemUpdatedAt',
                'gso_items.quantity_inventory as itemInventory',
                'gso_items.weighted_cost as itemWeightedCost',
                'gso_items.latest_cost as itemLatestCost',
                'gso_items.latest_cost_date as itemLatestCostDate',
                'gso_item_categories.description as catCode',
                'gso_unit_of_measurements.code as itemUOM'
            ])
            ->with([
                'gl_account' =>  function($q) { 
                    $q->select([
                        'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
                },
                'type' =>  function($q) { 
                    $q->select([
                        'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
                },
                'category' =>  function($q) { 
                    $q->select([
                        'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
                },
                'uom' =>  function($q) { 
                    $q->select([
                        'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
                },
                'pur_type' =>  function($q) { 
                    $q->select([
                        'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
                }
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
            })
            ->leftJoin('gso_item_types', function($join)
            {
                $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
            })
            ->leftJoin('gso_item_categories', function($join)
            {
                $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
            })
            ->leftJoin('gso_unit_of_measurements', function($join)
            {
                $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
            })
            ->leftJoin('gso_purchase_types', function($join)
            {
                $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
            })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function issue_checked_item($request)
    {   
        $columns = array( 
            0 => 'gso_items.id',
            1 => 'gso_items.code',
            2 => 'gso_items.name',
            3 => 'gso_items.description',
            4 => 'gso_items.weighted_cost',   
            5 => 'gso_items.latest_cost', 
            6 => 'gso_items.quantity_inventory',
            7 => 'acctg_account_general_ledgers.code',
            8 => 'acctg_account_general_ledgers.description',
            9 => 'gso_item_types.code',
            10 => 'gso_item_types.description',
            11 => 'gso_item_categories.code',
            12 => 'gso_item_categories.description',
            13 => 'gso_unit_of_measurements.code',
            14 => 'gso_purchase_types.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItem::select([
                '*', 
                'gso_items.id as itemId', 
                'gso_items.code as itemCode', 
                'gso_items.name as itemName',
                'gso_items.description as itemDesc',
                'gso_items.is_active as itemStatus',
                'gso_items.created_at as itemCreatedAt',
                'gso_items.updated_at as itemUpdatedAt',
                'gso_items.quantity_inventory as itemInventory',
                'gso_items.weighted_cost as itemWeightedCost',
                'gso_items.latest_cost as itemLatestCost',
                'gso_items.latest_cost_date as itemLatestCostDate',
                'gso_items.life_span as estLifeSpan',
                'gso_item_types.code as itemType',
                'acctg_account_general_ledgers.code as accCode',
                'gso_item_categories.description as catCode',
                'gso_unit_of_measurements.code as itemUOM'
            ])
            ->with([
                'gl_account' =>  function($q) { 
                    $q->select([
                        'acctg_account_general_ledgers.id', 'acctg_account_general_ledgers.code', 'acctg_account_general_ledgers.description']);
                },
                'type' =>  function($q) { 
                    $q->select([
                        'gso_item_types.id', 'gso_item_types.code', 'gso_item_types.description', 'gso_item_types.remarks']);
                },
                'category' =>  function($q) { 
                    $q->select([
                        'gso_item_categories.id', 'gso_item_categories.code', 'gso_item_categories.description', 'gso_item_categories.remarks']);
                },
                'uom' =>  function($q) { 
                    $q->select([
                        'gso_unit_of_measurements.id', 'gso_unit_of_measurements.code', 'gso_unit_of_measurements.description', 'gso_unit_of_measurements.remarks']);
                },
                'pur_type' =>  function($q) { 
                    $q->select([
                        'gso_purchase_types.id', 'gso_purchase_types.code', 'gso_purchase_types.description', 'gso_purchase_types.remarks']);
                }
            ])
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'gso_items.gl_account_id');
            })
            ->leftJoin('gso_item_types', function($join)
            {
                $join->on('gso_item_types.id', '=', 'gso_items.item_type_id');
            })
            ->leftJoin('gso_item_categories', function($join)
            {
                $join->on('gso_item_categories.id', '=', 'gso_items.item_category_id');
            })
            ->leftJoin('gso_unit_of_measurements', function($join)
            {
                $join->on('gso_unit_of_measurements.id', '=', 'gso_items.uom_id');
            })
            ->leftJoin('gso_purchase_types', function($join)
            {
                $join->on('gso_purchase_types.id', '=', 'gso_items.purchase_type_id');
            })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.weighted_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.latest_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.quantity_inventory', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_categories.description', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_types.code', 'like', '%' . $keywords . '%');
            }
        })
        ->whereIn('gso_items.id',$request->get('checkedValues'))
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }

    public function allItemCategories()
    {
        return (new GsoItemCategory)->allItemCategories();
    }

    public function allItemTypes()
    {
        return (new GsoItemType)->allItemTypes();
    }

    public function allPurchaseTypes()
    {
        return (new GsoPurchaseType)->allPurchaseTypes();
    }

    public function allUOMs()
    {
        return (new GsoUnitOfMeasurement)->allUOMs();
    }

    public function listItemsUpload($request, $itemId)
    {   
        $columns = array( 
            0 => 'name',
            1 => 'type',
            2 => 'size'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'name' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = FileUpload::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('type', 'like', '%' . $keywords . '%')
                ->orWhere('size', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['category' => 'items', 'category_id' => $itemId])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsConversion($request, $itemId)
    {   
        $columns = array( 
            0 => 'gso_items_conversions.id',
            1 => 'gso_items_conversions.based_quantity',
            2 => 'based.code',
            3 => 'gso_items_conversions.conversion_quantity',
            4 => 'conversion.code'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_items_conversions.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoItemConversion::select([
            'gso_items_conversions.*'
        ])
        ->leftJoin('gso_unit_of_measurements as based', function($join)
        {
            $join->on('based.id', '=', 'gso_items_conversions.based_uom');
        })
        ->leftJoin('gso_unit_of_measurements as conversion', function($join)
        {
            $join->on('conversion.id', '=', 'gso_items_conversions.conversion_uom');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_items_conversions.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items_conversions.based_quantity', 'like', '%' . $keywords . '%')
                ->orWhere('based.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items_conversions.conversion_quantity', 'like', '%' . $keywords . '%')
                ->orWhere('conversion.code', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['gso_items_conversions.item_id' => $itemId])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
    
    public function delete($id)
    {
        FileUpload::destroy($id);
    }

    public function fetch_gl_via_item_category($item_category)
    {
        return GsoItemCategory::find($item_category)->gl_account_id;
    }

    public function generate_item_code($item_category)
    {
        $code  = GsoItemCategory::find($item_category)->code . '-';
        $count = GsoItem::where('item_category_id', $item_category)->count();

        if($count < 9) {
            $code .= '0000' . ($count + 1);
        } else if($count < 99) {
            $code .= '000' . ($count + 1);
        } else if($count < 999) {
            $code .= '00' . ($count + 1);
        } else if($count < 9999) {
            $code .= '0' . ($count + 1);
        } else {
            $code .= ($count + 1);
        }
        return $code;
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function itemHistoryList($request,$item_id,$filter_type)
    {   
        if($filter_type == 1){ $mon="-1 months";}
        if($filter_type == 2){ $mon="-3 months";}
        if($filter_type == 3){ $mon="-6 months";}
        if($filter_type == 4){ $mon="-12 months";}
        $columns = array( 
            0 => 'gso_item_history.id',
            1 => 'gso_item_history.trans_type',
            2 => 'gso_item_history.item_id',
            3 => 'gso_item_history.trans_date',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $currentDate = date('Y-m-d');
        if($filter_type == 4)
        {
            $startDate = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        }
        else{
            $startDate = date('Y-m-d', strtotime($mon));
        }
        $res = GsoItemHistory::select([
                '*',
                'gso_item_history.trans_type as trans_type', 
                'gso_item_history.trans_date as trans_date', 
                'trans_by.fullname as trans_by',
                'rcv_by.fullname as rcv_by', 
                'gso_item_history.based_qty as based_qty', 
                'gso_item_history.posted_qty as posted_qty',
                'gso_item_history.balance_qty as balance_qty',
                'gso_item_history.reserved_qty as reserved_qty'
            ])
            ->leftJoin('hr_employees as trans_by', function($join)
            {
                $join->on('trans_by.id', '=', 'gso_item_history.trans_by');
            })
            ->leftJoin('hr_employees as rcv_by', function($join)
            {
                $join->on('rcv_by.id', '=', 'gso_item_history.rcv_by');
            })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_item_history.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.based_qty', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.posted_qty', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.reserved_qty', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.balance_qty', 'like', '%' . $keywords . '%')
                ->orWhere('trans_by.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('rcv_by.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.trans_type', 'like', '%' . $keywords . '%')
                ->orWhere('gso_item_history.trans_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_item_history.item_id', $item_id)
        ->whereBetween('gso_item_history.trans_date', [$startDate, $currentDate]);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }


    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function reload_employees($department)
    {
        return (new HrEmployee)->reload_employees($department);
    }

    public function reload_divisions($department)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department);
    }

    public function reload_designation($employee)
    {
        return (new HrDesignation)->find((new HrEmployee)->find($employee)->hr_designation_id);
    }
    public function findGsoItem($item_id)
    {
        return GsoItem::findOrFail($item_id);
    }
    public function lastIssueItemDet()
    {
        $data=GsoIssuanceDetails::orderBy('id','DESC')->first();
        return $data;
    }
    public function createIssuanceDetails(array $details) 
    {
        GsoIssuanceDetails::create($details);
    }
    public function createIssuance(array $details) 
    {
        GsoIssuance::create($details);
        $gsoInss=GsoIssuance::orderBy('id','DESC')->first();
        return $gsoInss;
    }

    public function validate_category($item_category)
    {
        $res = GsoItemCategory::find($item_category);

        if ($res->is_health_safety > 0) { 
            return 0;
        } 
        
        return 1;
    }

    public function validate_item($itemID)
    {
        return GsoPPMPDetail::where(['item_id' => $itemID, 'is_active' => 1])->count();
    }

    public function allMedicalCategories()
    {
        return (new HoMedicalItemCategory)->allMedicalCategories();
    }

    public function preload_uom($itemType)
    {
        $res = GsoItemType::find($itemType);
        if ($res->code == 'SPA') {
            $res2 =  GsoUnitOfMeasurement::where(['is_lot' => 1])->get();
            if ($res2->count() > 0) {
                return $res2->first()->id;
            }
        }
        return '';
    }
}