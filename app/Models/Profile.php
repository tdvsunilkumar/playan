<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Profile extends Model
{
    public function updateData($id,$columns){
        return DB::table('profiles')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('profiles')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getBarangay(){
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
        // return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
     public function getBploApplications($id){
        return DB::table('bplo_applications')->where('profile_id',$id)->get()->toArray();
    }

    
    public function getProfileusers(){
        return DB::table('profiles AS bgf')
              ->join('barangays AS b', 'b.id', '=', 'bgf.brgy_code')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','b.brgy_code','b.brgy_name','p_code','p_first_name','p_middle_name','p_family_name','p_address_house_lot_no','p_address_street_name','p_address_subdivision','p_mobile_no','p_email_address','p_job_position','p_gender','p_date_of_birth','ba_business_name')->get();
        // return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
    public function getProfileusersdata(){
        return DB::table('profiles AS bgf')
              ->join('barangays AS b', 'b.id', '=', 'bgf.brgy_code')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','b.brgy_code','b.brgy_name','p_code','p_first_name','p_middle_name','p_family_name','p_address_street_name','p_address_subdivision','p_mobile_no','p_email_address','p_job_position','p_gender','p_date_of_birth','ba_business_name')->get();
        // return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
    public function getCountry(){
        return DB::table('countries')->select('id','country_name')->where('is_active',1)->get();
    }
}
