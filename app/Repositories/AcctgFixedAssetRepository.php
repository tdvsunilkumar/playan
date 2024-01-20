<?php

namespace App\Repositories;

use App\Interfaces\AcctgFixedAssetInterface;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\GsoPropertyAccountability;
use App\Models\GsoPropertyAccountabilityHistory;
use App\Models\GsoPropertyCategory;
use App\Models\GsoPropertyType;
use App\Models\GsoItem;
use App\Models\GsoDepreciationType;
use App\Models\HrEmployee;

class AcctgFixedAssetRepository implements AcctgFixedAssetInterface 
{
    public function find($id) 
    {
        return GsoPropertyAccountability::findOrFail($id);
    }

    public function get($id) 
    {
        return GsoPropertyAccountability::whereId($id)->get();
    }

    public function find_history($id) 
    {
        return GsoPropertyAccountabilityHistory::findOrFail($id);
    }

    public function get_history($id) 
    {
        return GsoPropertyAccountabilityHistory::where('property_id', $id)->get();
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoPropertyAccountability::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoPropertyAccountability::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return GsoPropertyAccountability::create($details);
    }

    public function create_history(array $details) 
    {
        return GsoPropertyAccountabilityHistory::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoPropertyAccountability::whereId($id)->update($newDetails);
    }

    public function update_history($id, array $newDetails) 
    {
        return GsoPropertyAccountabilityHistory::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'gso_property_accountabilities.id',
            1 => 'gso_property_accountabilities.fixed_asset_no',
            2 => 'gso_property_accountabilities.property_no',
            3 => 'gso_property_categories.name',
            4 => 'gso_property_types.name',
            5 => 'acctg_account_general_ledgers.code',
            6 => 'gso_items.code',
            7 => 'gso_property_accountabilities.unit_cost',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_property_accountabilities.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPropertyAccountability::select([
            'gso_property_accountabilities.*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_property_accountabilities.unit_cost', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.property_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_categories.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%');
            }
        })
        ->leftJoin('gso_property_categories', function($join)
        {
            $join->on('gso_property_categories.id', '=', 'gso_property_accountabilities.property_category_id');
        })
        ->leftJoin('gso_property_types', function($join)
        {
            $join->on('gso_property_types.id', '=', 'gso_property_accountabilities.property_type_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'gso_property_accountabilities.gl_account_id');
        })
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_property_accountabilities.item_id');
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function history_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'gso_property_accountabilities_history.id',
            1 => 'gso_property_accountabilities_history.acquired_date',
            2 => 'acquiree.fullname',
            3 => 'issuer.fullname',
            4 => 'gso_property_accountabilities_history.returned_date',   
            5 => 'returnee.fullname',
            6 => 'receiver.fullname'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_property_accountabilities_history.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoPropertyAccountabilityHistory::select([
            'gso_property_accountabilities_history.*'
        ])
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'gso_property_accountabilities_history.property_id');
        })
        ->leftJoin('hr_employees as acquiree', function($join)
        {
            $join->on('acquiree.id', '=', 'gso_property_accountabilities_history.acquired_by');
        })
        ->leftJoin('hr_employees as issuer', function($join)
        {
            $join->on('issuer.id', '=', 'gso_property_accountabilities_history.issued_by');
        })
        ->leftJoin('hr_employees as returnee', function($join)
        {
            $join->on('returnee.id', '=', 'gso_property_accountabilities_history.returned_by');
        })
        ->leftJoin('hr_employees as receiver', function($join)
        {
            $join->on('receiver.id', '=', 'gso_property_accountabilities_history.received_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acquiree.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('issuer.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('returnee.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('receiver.fullname', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_property_accountabilities_history.property_id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allProperties()
    {
       return (new GsoPropertyType)->allProperties();
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }

    public function allDepreciations()
    {
        return (new GsoDepreciationType)->allDepreciations();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function allCategories()
    {
        return (new GsoPropertyCategory)->allCategories();
    }

    public function reload_items_via_gl($gl_account, $field)
    {
        return (new GsoItem)->allItemsViaGL($gl_account, $field);
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = GsoPropertyAccountability::where('fixed_asset_no', '!=', NULL)->whereYear('created_at', '=', $year)->count();
        $controlNo  = 'FA'.substr($year, -2).'-';

        if($count < 9) {
            $controlNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $controlNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $controlNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $controlNo .= '0' . ($count + 1);
        } else {
            $controlNo .= ($count + 1);
        }
        return $controlNo;
    }

    public function depreciate($timestamp)
    {
        $res = GsoPropertyAccountability::whereDay('effectivity_date', '=', date('d', strtotime($timestamp)))
        ->where([
            'is_locked' => 1,
            'is_active' => 1,
            'is_depreciative' => 1,
            'status' => 'acquired'
        ])
        ->get();
        if ($res->count() > 0) {
            foreach ($res as $r) {
                $date1 = date('Y-m-d', strtotime($timestamp));
                $date2 = date('Y-m-d', strtotime($r->effectivity_date));
                $diff = abs(strtotime($date2)-strtotime($date1));
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                if ($r->estimated_life_span >= $months) {
                    $totalAmt = floatval($months) * floatval($r->monthly_depreciation); 
                    $details = array(
                        'total_depreciation' => $totalAmt,
                        'updated_at' => $timestamp
                    );
                    GsoPropertyAccountability::whereId($r->id)->update($details);
                }                
            }
        }
        return true;
    }
}