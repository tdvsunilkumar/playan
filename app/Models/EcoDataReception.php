<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barangay;
use DB;

class EcoDataReception extends Model
{
    public $table = 'eco_data_receptions';

    public function allReceptionLocations($vars = '')
    { 
        $barangays = Barangay::select([
        'barangays.id as brgyID',
        'barangays.brgy_name as brgyName',
        'barangays.brgy_office as brgyOffice', 
        'profile_municipalities.mun_desc as municipal',
        'profile_provinces.prov_desc as province',
        'profile_regions.reg_region as region'
        ])
        ->join('profile_regions', function($join)
        {
            $join->on('profile_regions.id', '=', 'barangays.reg_no');
        })
        ->join('profile_municipalities', function($join)
        {
            $join->on('profile_municipalities.id', '=', 'barangays.mun_no');
        })
        ->join('profile_provinces', function($join)
        {
            $join->on('profile_provinces.id', '=', 'barangays.prov_no');
        })
        ->whereIn('barangays.id', 
            self::select('brgy_id')->where('status', 1)->get()
        )
        ->where('barangays.is_active', 1)
        ->orderBy('barangays.brgy_name', 'asc')->get();

        $brgys = array();
        if (!empty($vars)) {
            $brgys[] = array('' => 'select a '.$vars);
        } else {
            $brgys[] = array('' => 'select a barangay...');
        }
        foreach ($barangays as $barangay) {
            $brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
            $brgys[] = array(
                $barangay->brgyID => ucwords(strtolower($brgy)) 
                // . ', ' . $barangay->municipal . ', ' . $barangay->province . ', ' . $barangay->region
            );
        }

        $barangays = array();
        foreach($brgys as $brgy) {
            foreach($brgy as $key => $val) {
                $barangays[$key] = $val;
            }
        }

        return $barangays;
    }
}
