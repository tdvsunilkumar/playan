<?php

namespace App\Repositories;

use App\Interfaces\EconCemeteryInterface;
use App\Models\EcoCemeteryApplication;
use App\Models\EcoCemeteryApplicationPayment;
use App\Models\SocialWelfare\Citizen;
use App\Models\EcoDataCemetery;
use App\Models\EcoService;
use App\Models\CemeteryStyle;
use App\Models\CemeteriesListDetails;
use App\Models\CtoTopTransaction;
use App\Models\CtoCashierDetail;
use App\Models\CtoReceivable;
use App\Models\User;

class EconCemeteryRepository implements EconCemeteryInterface 
{
    public function find($id) 
    {
        return EcoCemeteryApplication::findOrFail($id);
    }
    
    public function create(array $details) 
    {
        return EcoCemeteryApplication::create($details);
    }

    public function update($id, array $newDetails) 
    {
        if (isset($newDetails['terms'])) {
            $timestamp = $newDetails['updated_at'];
            $cemetery = EcoCemeteryApplication::find($id);
            $amount = floatval(floatval($cemetery->total_amount) - floatval($cemetery->downpayment)) / floatval($newDetails['terms']);
            $i = 1;
            while ($i <= $newDetails['terms']) {
                $receivable_details = array(
                    'category' => 'cemetery',
                    'application_id' => $id,
                    'taxpayer_id' => $cemetery->requestor->id,
                    'taxpayer_name' => $cemetery->requestor->cit_fullname,
                    'pcs_id' => 10,
                    'top_transaction_type_id' => $cemetery->top_transaction_type_id,
                    'fund_code_id' => $cemetery->tfoc->fund_id,
                    'gl_account_id' => $cemetery->gl_account_id,
                    'sl_account_id' => $cemetery->sl_account_id,
                    'description' => $cemetery->service->tfoc_name,
                    'top_no' => $cemetery->transaction->transaction_no,
                    'due_date' => date('Y-m-d', strtotime($timestamp. ' + '.$i.' months')),
                    'amount_due' => $amount,
                    'remaining_amount' => $amount,
                    'created_at' => $timestamp,
                    'created_by' => $newDetails['updated_by']
                );
                CtoReceivable::create($receivable_details); 
                $i++;
            }
        }
        return EcoCemeteryApplication::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'eco_cemetery_application.transaction_no',
            1 => 'cto_top_transactions.transaction_no',
            2 => 'citizens.cit_fullname',
            3 => 'eco_cemetery_application.full_address',
            4 => 'eco_cemetery_application.total_amount',   
            5 => 'eco_cemetery_application.remaining_amount',
            6 => 'eco_cemetery_application.or_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'eco_cemetery_application.transaction_no' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = EcoCemeteryApplication::select([
            'eco_cemetery_application.*'
        ])
        ->leftJoin('citizens', function($join)
        {
            $join->on('citizens.id', '=', 'eco_cemetery_application.requestor_id');
        })
        ->leftJoin('cto_top_transactions', function($join)
        {
            $join->on('cto_top_transactions.id', '=', 'eco_cemetery_application.top_transaction_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('eco_cemetery_application.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.transaction_date', 'like', '%' . $keywords . '%')
                ->orWhere('citizens.cit_fullname', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.full_address', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_top_transactions.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.or_no', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy('eco_cemetery_application.created_at', $order)
        ->orderBy($column, $order);
        if ($status != 'all') {
            $res = $res->where('eco_cemetery_application.status', '=', $status);
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allCitizens()
    {
        return (new Citizen)->allCitizens();
    }

    public function allCemeteryLocations()
    {
        return (new EcoDataCemetery)->allCemeteryLocations();
    }

    public function allCemeteryNames()
    {
        return (new EcoDataCemetery)->allCemeteryNames();
    }

    public function allServices($type = 0)
    {
        return (new EcoService)->allServices($type);
    }

    public function find_column($column, $id)
    {
        return Citizen::whereId($id)->first()->$column;
    }

    public function allCemeteryStyles()
    {
        return (new CemeteryStyle)->allCemeteryStyles();
    }

    public function reload_cemetery_lot($appID, $location = '', $cemetery = '', $style = '')
    {   
        $res = CemeteriesListDetails::select([
            'eco_cemeteries_list_details.*'
        ])
        ->leftJoin('eco_cemeteries_lists', function($join)
        {
            $join->on('eco_cemeteries_lists.id', '=', 'eco_cemeteries_list_details.ecl_id');
        })
        // ->whereNotIn('eco_cemeteries_list_details.id',
        //     (new EcoCemeteryApplication)
        //     ->select('cemetery_lot_id')
        //     ->where('id', '!=', $appID)
        //     ->where([
        //         'is_active' => 1
        //     ])
        //     ->where('status', '!=', 'cancelled')
        //     ->get()
        // )
        ->where('eco_cemeteries_list_details.status', 1);
        if (!empty($location)) {
            $res = $res->where('eco_cemeteries_lists.brgy_id', '=', $location);
        }
        if (!empty($cemetery)) {
            $res = $res->where('eco_cemeteries_lists.ec_id', '=', $cemetery);
        }
        if (!empty($style)) {
            $res = $res->where('eco_cemeteries_lists.ecs_id', '=', $style);
        }
        $res = $res->get();

        return $res;
    }

    public function reload_cemetery_name($location)
    {
        return EcoDataCemetery::where(['brgy_id' => $location, 'status' => 1])->get();
    }

    public function find_services($service)
    {
        return EcoService::findOrFail($service);
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = EcoCemeteryApplication::whereYear('created_at', '=', $year)->count();
        $controlNo  = 'CEM-';

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

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'eco_cemetery_application.transaction_no',
            1 => 'cto_top_transactions.transaction_no',
            2 => 'citizens.cit_fullname',
            3 => 'eco_cemetery_application.full_address',
            4 => 'eco_cemetery_application.total_amount',   
            5 => 'eco_cemetery_application.remaining_amount',
            6 => 'eco_cemetery_application.or_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = EcoCemeteryApplication::select([
            'eco_cemetery_application.*'
        ])
        ->leftJoin('citizens', function($join)
        {
            $join->on('citizens.id', '=', 'eco_cemetery_application.requestor_id');
        })
        ->leftJoin('cto_top_transactions', function($join)
        {
            $join->on('cto_top_transactions.id', '=', 'eco_cemetery_application.top_transaction_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('eco_cemetery_application.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.transaction_date', 'like', '%' . $keywords . '%')
                ->orWhere('citizens.cit_fullname', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.full_address', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('eco_cemetery_application.or_no', 'like', '%' . $keywords . '%');
            }
        })
        ->where('eco_cemetery_application.status', '!=', 'draft')
        ->orderBy('eco_cemetery_application.created_at', $order)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function create_transactions(array $details) 
    {
        return CtoTopTransaction::create($details);
    }

    public function update_transactions($id, array $newDetails) 
    {
        return CtoTopTransaction::whereId($id)->update($newDetails);
    }

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }

    public function create_payments(array $details) 
    {
        return EcoCemeteryApplicationPayment::create($details);
    }
    
    public function find_application_via_column($trans)
    {
        $res = EcoCemeteryApplication::select('*',
            'eco_cemeteries_style.eco_cemetery_style as cemetery_style',
            'eco_cemeteries_list_details.ecl_block as block',
            'eco_cemeteries_list_details.ecl_lot as lot',
            'eco_cemeteries_lists.ecl_street as street',
            'eco_data_cemeteries.cem_name as cemetery',
            'eco_services.tfoc_name',
            'eco_services.amount',
        )
        ->leftJoin('eco_cemeteries_style', function($join)
        {
            $join->on('eco_cemeteries_style.id', '=', 'eco_cemetery_application.cemetery_style_id');
        })
        ->leftJoin('eco_cemeteries_list_details', function($join)
        {
            $join->on('eco_cemeteries_list_details.id', '=', 'eco_cemetery_application.cemetery_lot_id');
        })
        ->leftJoin('eco_data_cemeteries', function($join)
        {
            $join->on('eco_data_cemeteries.id', '=', 'eco_cemetery_application.cemetery_id');
        })
        ->leftJoin('eco_cemeteries_lists', function($join)
        {
            $join->on('eco_cemeteries_lists.ec_id', '=', 'eco_data_cemeteries.id');
        })
        ->leftJoin('eco_services', function($join)
        {
            $join->on('eco_services.id', '=', 'eco_cemetery_application.service_id');
        })
        ->where([
            'transaction_no' => $trans,
        ])
        ->first();
        
        return $res;
    }

    public function payment_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'cto_cashier_details.id',
            1 => 'cto_cashier_details.created_at',
            2 => 'cto_cashier_details.or_no',
            3 => 'cto_cashier_details.cem_total_amount',
            4 => 'cto_cashier_details.cem_paid_amount',   
            5 => 'cto_cashier_details.cem_remaining_balance'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'eco_cemetery_application_payments.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = CtoCashierDetail::select([
            'cto_cashier_details.*'
        ])
        ->leftJoin('eco_cemetery_application', function($join)
        {
            $join->on('eco_cemetery_application.id', '=', 'cto_cashier_details.cemetery_application_id');
        })
        ->leftJoin('citizens', function($join)
        {
            $join->on('citizens.id', '=', 'eco_cemetery_application.requestor_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_cashier_details.or_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_details.created_at', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_details.cem_total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_details.cem_paid_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_details.cem_remaining_balance', 'like', '%' . $keywords . '%');
            }
        })
        ->where('eco_cemetery_application.id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}