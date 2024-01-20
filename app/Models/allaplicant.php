<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class allaplicant extends Model
{
    use HasFactory;

    protected $guard_name = 'web';

    protected $fillable = [
        'isnew',
        'modeofpayment',
        'applicationdate',
        'tinno',
        'registartionno',
        'registrationdate',
        'typeofbussiness',
        'amendmentfrom',
        'amendmentto',
        'enjoyingtax',
        'fname',
        'mname',
        'lname',
        'bussinessname',
        'tradename',
        'bussinessaddress',
        'billing_postalcode',
        'billing_email',
        'billing_telephone',
        'billing_mobile',
        'owner_postalcode',
        'owner_email',
        'owner_telephone',
        'owner_mobile',
        'bussinessarea',
        'noofempestablish',
        'noofempewithlgu',
        'lessor_fullname',
        'lessor_address',
        'lessor_mobile',
        'lessor_email',
        'lineofbussiness',
        'noofunits',
        'capitalization',
        'essential',
        'non_essential',
        'is_approve',
    ];



    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"fname",
      1 =>"bussinessname",
      2 =>"tradename",
      3 =>"isnew",
      4 =>"modeofpayment", 
      5 =>"monthlyrental",
      6 =>"bussinessaddress"     
    );


    $sql = DB::table('allaplicants AS ap')
          ->join('pbloapplicationtypes AS pt', 'ap.isnew', '=', 'pt.id')
           ->join('profiles AS p', 'ap.profile_id', '=', 'p.id')
          ->select('ap.id','p.p_complete_name_v2','fname','mname','lname','bussinessname','tradename','app_type','is_approve','modeofpayment','monthlyrental','bussinessaddress','owneraddress');

    //$sql->where('ap.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(fname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(lname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tradename)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(bussinessname)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function updateapplicantData($ids,$columns)
	{
		return DB::table('allaplicants')->whereIn('id',$ids)->update($columns);
	}
    public function updateData($id,$columns){
        return DB::table('allaplicants')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('allaplicants')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function apptypes()
    {
        return DB::table('pbloapplicationtypes')->select('id','app_type')->where('is_active','1')->get();
    }
    public function getBarangayapp(){
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
        // return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
    public function getprofiles(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name','p_complete_name_v1')->get();
    }
     public function bussinesstypes()
    {
        return DB::table('typeof_bussinesses')->select('id','bussiness_type')->where('is_active','1')->get();
    }
    public function addProfileData($postdata){
        return DB::table('profiles')->insert($postdata);
    }
    public function checkprofile($email,$phone){
        return DB::table('profiles')->select('*')->where('p_email_address',$email)->where('p_telephone_no',$phone)->get()->toArray();
    }
    public function updateProfileData($id,$columns){
        return DB::table('profiles')->where('id',$id)->update($columns);
    }
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_bplo',1)->get();
    }
    public function getprofileProvcodeId(){
        return DB::table('profile_municipalities')->select('id','mun_desc','mun_no')->where('mun_display_for_bplo',1)->where('is_active',1)->get();
    }
    public function getSubClasses(){
        return DB::table('psic_subclasses')->select('id','subclass_description','subclass_code')->where('is_active',1)->get();
    }
    

    public function addApplicationActivity($postdata){
        return DB::table('applicant_activity')->insert($postdata);
    }
    public function updateApplicationActivity($id,$columns){
        return DB::table('applicant_activity')->where('id',$id)->update($columns);
    }
    public function getActivitydetialData($id){
       return DB::table('applicant_activity')->select('*')->where('applicantid',$id)->orderBy('id','ASC')->get()->toArray();
    }
    public function getProfiledata($id){
         return DB::table('profiles AS bgf')
              ->join('barangays AS b', 'b.id', '=', 'bgf.brgy_code')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pm.mun_zip_code','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_description','pr.reg_no','b.brgy_code','b.brgy_name','p_code','p_first_name','p_middle_name','p_family_name','p_address_house_lot_no','p_address_street_name','p_address_subdivision','p_mobile_no','p_telephone_no','p_email_address','p_job_position','p_gender','p_date_of_birth','ba_business_name')->where('bgf.id','=',$id)->get();
    }

}
