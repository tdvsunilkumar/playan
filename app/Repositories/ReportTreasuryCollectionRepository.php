<?php

namespace App\Repositories;

use App\Interfaces\ReportTreasuryCollectionInterface;
use App\Models\CtoCashierIncome;
use App\Models\GsoPropertyType;
use App\Models\AcctgFundCode;
use App\Models\Client;
use App\Models\GsoSupplier;
use App\Models\AcctgAccountDisbursement;
use DB;

class ReportTreasuryCollectionRepository implements ReportTreasuryCollectionInterface 
{
    public function find($id) 
    {
        return CtoCashierIncome::findOrFail($id);
    }

    public function allFundCodes()
    {
       return (new AcctgFundCode)->allFundCodes();
    }

    public function get_details($fund, $officer = '', $dateFrom = '', $dateTo = '')
    {
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->where('cto_cashier_income.is_collected', 1);
        if ($fund) {
            $res = $res->where('cto_cashier_income.fund_id', '=', $fund);
        }
        if ($dateFrom && $dateTo) {
            $res = $res->whereBetween('cto_cashier_income.cashier_or_date', [$dateFrom, $dateTo]);
        }
        if($officer) {
            $res = $res->where('cto_cashier_income.created_by', $officer);
        } 
        $res = $res->groupBy(['cto_cashier_income.or_no']);
        return $res->get();
    }

    public function get_breakdown_details($or_no)
    {
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->where('cto_cashier_income.is_collected', 1)
        ->where('cto_cashier_income.or_no', $or_no)
        ->get();
        return $res;
    }
    
    public function reload($type)
    {
        if ($type == 'general-ledger') {
            $res = GsoPropertyAccountability::where(['is_active' => 1])->get();
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
        if ($request->category == 'summary') {
            $res = GsoPropertyType::select([
                'gso_property_types.*',
            ])
            ->leftJoin('gso_property_accountabilities', function($join)
            {
                $join->on('gso_property_accountabilities.property_type_id', '=', 'gso_property_types.id');
            })
            ->groupBy(['gso_property_types.id'])
            ->get();
            

            return $res = $res->map(function($rows) {
                return (object) [
                    'id' => $rows->id,
                    'type' => $rows->name,
                ];
            });
        } else {
            $lifespan = [
                '60' => '5 years',
                '120' => '10 years',
                '180' => '15 years',
                '240' => '20 years',
                '300' => '25 years',
                '360' => '30 years'
            ];
            $status = [
                'acquired' => 'Active',
                'active' => 'Active',
                'disposed' => 'Disposed',
                'sold' => 'Sold'
            ];
            $res = GsoPropertyAccountability::select([
                'gso_property_accountabilities.*'
            ])
            ->leftJoin('gso_property_types', function($join)
            {
                $join->on('gso_property_types.id', '=', 'gso_property_accountabilities.property_type_id');
            })
            ->where([
                'gso_property_accountabilities.is_locked' => 1,
                'gso_property_accountabilities.is_active' => 1
            ])
            ->groupBy(['gso_property_accountabilities.id'])
            ->get();
            return $res = $res->map(function($rows) use ($lifespan, $status) {
                return (object) [
                    'id' => $rows->id,
                    'asset_no' => $rows->fixed_asset_no,
                    'type' => $rows->type->name,
                    'date_acquired' => date('d-M-Y', strtotime($rows->received_date)),
                    'life_span' => ($rows->estimated_life_span > 0) ? $lifespan[$rows->estimated_life_span] : '',
                    'date_ended' => date('d-M-Y', strtotime($rows->received_date .' +'. $rows->estimated_life_span .' Months')),
                    'unit_cost' => $rows->unit_cost,
                    'salvage_value' => $rows->salvage_value ? $rows->salvage_value.'%' : '',
                    'depreciation_cost' => $rows->total_depreciation,
                    'book_value' => floatval(floatval($rows->unit_cost) - floatval($rows->total_depreciation)),
                    'status' => $status[$rows->status]
                ];
            });
        }
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function get_acquisition_cost($type, $fund, $status, $from, $to)
    {
        $total = GsoPropertyAccountability::where([
            'property_type_id' => $type,
            'fund_code_id' => $fund,
            'is_locked' => 1,
            'is_active' => 1
        ])
        ->whereBetween('received_date', [$from, $to]);
        if(!empty($status)) {
            $total = $total->where('status', '=', $status);
        }
        $total = $total->sum('unit_cost');

        return ($total > 0) ? $total : 0;
    }

    public function get_depreciation_cost($type, $fund, $status, $from, $to)
    {
        $total = GsoPropertyAccountability::where([
            'property_type_id' => $type,
            'fund_code_id' => $fund,
            'is_locked' => 1,
            'is_active' => 1
        ])
        ->whereBetween('received_date', [$from, $to]);
        if(!empty($status)) {
            $total = $total->where('status', '=', $status);
        }
        $total = $total->sum('total_depreciation');

        return ($total > 0) ? $total : 0;
    }
}