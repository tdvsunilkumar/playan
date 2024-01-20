<?php

namespace App\Repositories;

use App\Interfaces\ReportAcctgFixedAssetInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\GsoPropertyAccountability;
use App\Models\GsoPropertyType;
use App\Models\AcctgFundCode;
use App\Models\Client;
use App\Models\GsoSupplier;
use App\Models\AcctgAccountDisbursement;
use App\Models\RptLocality;
Use App\Models\User;
use DB;

class ReportAcctgFixedAssetRepository implements ReportAcctgFixedAssetInterface 
{
    public function find($id) 
    {
        return GsoPropertyAccountability::findOrFail($id);
    }

    public function allFundCodes()
    {
       return (new AcctgFundCode)->allFundCodes();
    }

    public function allProperties()
    {   
        return (new GsoPropertyType)->allProperties();
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
                'gso_property_accountabilities.is_depreciative' => 1,
                'gso_property_accountabilities.is_locked' => 1,
                'gso_property_accountabilities.is_active' => 1
            ])
            ->groupBy(['gso_property_accountabilities.id'])
            ->get();
            return $res = $res->map(function($rows) use ($lifespan, $status) {
                return (object) [
                    'id' => $rows->id,
                    'asset_no' => $rows->fixed_asset_no,
                    'mode' => $rows->mode ?  $rows->mode->name : '',
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