<?php

namespace App\Repositories;

use App\Interfaces\EconRentalInterface;
use App\Models\EcoRentalApplication;
use App\Models\Citizen;
use App\Models\EcoService;
use App\Models\EcoReceptionList;
use App\Models\EcoReceptionListDetail;
use App\Models\CtoTopTransaction;
use App\Models\User;
use App\Models\EcoDataReception;
use App\Models\EcoRentalDiscount;

class EconRentalRepository implements EconRentalInterface 
{
    public function find($id) 
    {
        return EcoRentalApplication::findOrFail($id);
    }
    
    public function create(array $details) 
    {
        return EcoRentalApplication::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return EcoRentalApplication::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'eco_rental_application.transaction_no',
            1 => 'cto_top_transactions.transaction_no',
            2 => 'citizens.cit_fullname',
            3 => 'eco_rental_application.full_address',
            4 => 'eco_rental_application.total_amount',  
            5 => 'eco_rental_application.or_no' 
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'eco_rental_application.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = EcoRentalApplication::select([
            'eco_rental_application.*'
        ])
        ->leftJoin('citizens', function($join)
        {
            $join->on('citizens.id', '=', 'eco_rental_application.requestor_id');
        })
        ->leftJoin('cto_top_transactions', function($join)
        {
            $join->on('cto_top_transactions.id', '=', 'eco_rental_application.top_transaction_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('eco_rental_application.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.transaction_date', 'like', '%' . $keywords . '%')
                ->orWhere('citizens.cit_fullname', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.full_address', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_top_transactions.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.or_no', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy('eco_rental_application.created_at', $order)
        ->orderBy($column, $order);
        if ($status != 'all') {
            $res = $res->where('eco_rental_application.status', $status);
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allCitizens()
    {
        return (new Citizen)->allCitizens();
    }

    public function allReceptionLocations()
    {
        return (new EcoDataReception)->allReceptionLocations();
    }

    public function allServices($type = 0)
    {
        return (new EcoService)->allServices($type);
    }

    public function reload_reception_name($location)
    {
        return EcoDataReception::where(['brgy_id' => $location, 'status' => 1])->get();
    }

    public function reload_reception_class($appID, $location = '', $reception = '')
    {   
        $res = EcoReceptionListDetail::select([
            'eco_receptions_lists_details.*'
        ])
        ->leftJoin('eco_receptions_lists', function($join)
        {
            $join->on('eco_receptions_lists.id', '=', 'eco_receptions_lists_details.est_id');
        })
        // ->whereNotIn('eco_receptions_lists_details.id',
        //     (new EcoRentalApplication)
        //     ->select('reception_class_id')
        //     ->where('id', '!=', $appID)
        //     ->where([
        //         'is_active' => 1
        //     ])
        //     ->where('status', '!=', 'cancelled')
        //     ->get()
        // )
        ->where('eco_receptions_lists_details.eatd_status', 1);
        if (!empty($location)) {
            $res = $res->where('eco_receptions_lists.barangay_id', '=', $location);
        }
        if (!empty($reception)) {
            $res = $res->where('eco_receptions_lists.est_id', '=', $reception);
        }
        $res = $res->get();

        return $res;
    }

    public function fetch_multiplier_amount($location, $reception, $reception_class)
    {
        $res = EcoReceptionListDetail::select([
            'eco_receptions_lists_details.*'
        ])
        ->leftJoin('eco_receptions_lists', function($join)
        {
            $join->on('eco_receptions_lists.id', '=', 'eco_receptions_lists_details.est_id');
        })
        ->where('eco_receptions_lists_details.eatd_status', 1);
        if (!empty($location)) {
            $res = $res->where('eco_receptions_lists.barangay_id', '=', $location);
        }
        if (!empty($reception)) {
            $res = $res->where('eco_receptions_lists.est_id', '=', $reception);
        }
        if (!empty($reception_class)) {
            $res = $res->where('eco_receptions_lists_details.eatd_process_type', '=', $reception_class);
        }
        $res = $res->get();
        return $res;
    }

    public function find_services($service)
    {
        return EcoService::findOrFail($service);
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = EcoRentalApplication::whereYear('created_at', '=', $year)->count();
        $controlNo  = 'RENT-';

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

    public function find_column($column, $id)
    {
        return Citizen::whereId($id)->first()->$column;
    }

    public function approval_listItems($request)
    {   
        $columns = array( 
            0 => 'eco_rental_application.transaction_no',
            1 => 'cto_top_transactions.transaction_no',
            2 => 'citizens.cit_fullname',
            3 => 'eco_rental_application.full_address',
            4 => 'eco_rental_application.total_amount',  
            5 => 'eco_rental_application.or_no' 
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = EcoRentalApplication::select([
            'eco_rental_application.*'
        ])
        ->leftJoin('citizens', function($join)
        {
            $join->on('citizens.id', '=', 'eco_rental_application.requestor_id');
        })
        ->leftJoin('cto_top_transactions', function($join)
        {
            $join->on('cto_top_transactions.id', '=', 'eco_rental_application.top_transaction_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('eco_rental_application.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.transaction_date', 'like', '%' . $keywords . '%')
                ->orWhere('citizens.cit_fullname', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.full_address', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_top_transactions.transaction_no', 'like', '%' . $keywords . '%')
                ->orWhere('eco_rental_application.or_no', 'like', '%' . $keywords . '%');
            }
        })
        ->where('eco_rental_application.status', '!=', 'draft')
        ->orderBy('eco_rental_application.created_at', $order)
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

    public function get_calendar()
    {
        return EcoRentalApplication::where(['is_active' => 1])->whereNotNull('approved_by')->get();
    }

    public function find_application_via_column($trans)
    {
        $res = EcoRentalApplication::with(['transaction'])->select('eco_rental_application.*','eco_services.tfoc_name as service_name')
        ->leftJoin('eco_services', function($join)
        {
            $join->on('eco_services.id', '=', 'eco_rental_application.service_id');
        })
        ->where([
            'transaction_no' => $trans,
        ])
        ->first();
        
        return $res;
    }
    
    public function allDiscounts()
    {
        return (new EcoRentalDiscount)->allDiscounts();
    }

    public function fetch_discount($id)
    {
        return EcoRentalDiscount::findOrFail($id);
    }
}