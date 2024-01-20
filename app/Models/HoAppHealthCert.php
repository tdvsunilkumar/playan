<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barangay;
use DB;

class HoAppHealthCert extends Model
{
    public $table = 'ho_app_health_certs';
    
    public function updateData($id,$columns){
        return DB::table('ho_app_health_certs')->where('id',$id)->update($columns);
    }
    public function getYearDetails(){
        return DB::table('ho_app_health_certs')->select('hahc_app_year')->groupBy('hahc_app_year')
        ->orderBy('hahc_app_year','DESC')
        ->get()->toArray(); 
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_app_health_certs')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('ho_app_health_certs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateusersavedataData($id,$columns){
        return DB::table('user_last_save_data')->where('id',$id)->update($columns);
    }
    public function addusersaveData($data){
        DB::table('user_last_save_data')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function CheckFormdataExist($formid,$userid){
        return DB::table('user_last_save_data')->where('form_id',$formid)->where('user_id',$userid)->get();
    }
    public function getbploApplications(){
       return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }
    public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getBarangay(){
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pp.prov_desc','pr.reg_region','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_display_for_rpt','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }    
    public function getCountries(){
        return DB::table('countries')->select('id','country_name')->where('is_active',1)->get();
    }
    public function getCitizen(){
        return DB::table('citizens')->where('cit_is_active',1)->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name')->get();
    }
    public function getCitizenAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('citizens AS c')
             ->select('c.id','c.cit_fullname');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('c.cit_is_active',1);
            $sql->Where('c.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(c.cit_fullname)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('c.cit_fullname','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getBusinessAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('bplo_business')
        ->Leftjoin('bplo_business_endorsement', 'bplo_business_endorsement.busn_id', '=', 'bplo_business.id')
        ->select('bplo_business_endorsement.id','bplo_business_endorsement.busn_id','bplo_business.busn_name','bend_year','bend_status')->where('bplo_business_endorsement.endorsing_dept_id',3)->where('busn_app_status','>=',2);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->where('bplo_business_endorsement.endorsing_dept_id',3);
            $sql->where('busn_app_status','>=',2);
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($search)."%");
            $sql->where(DB::raw('LOWER(bend_year)'),'like',"%".strtolower($search)."%");
            $sql->orWhere(DB::raw("CONCAT(busn_name, '-[', COALESCE(bend_year, ''), '-', COALESCE(bend_status))"), 'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('bplo_business.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getBusiness(){
        return DB::table('bplo_business')
        ->Leftjoin('bplo_business_endorsement', 'bplo_business_endorsement.busn_id', '=', 'bplo_business.id')
        ->where('bplo_business_endorsement.endorsing_dept_id',3)
        ->where('busn_app_status','>=',2)
        ->select('bplo_business_endorsement.id','bplo_business_endorsement.busn_id','bplo_business.busn_name','bend_year','bend_status')->orderby('bplo_business.id','DESC')->get();
    }
    public function getBusiness22(){
        DB::table('bplo_business AS bb')
        ->Leftjoin('clients AS cl', 'bb.client_id', '=', 'cl.id')
        ->Leftjoin('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
        ->where('bb.busn_app_status','>=',2)
        ->where('bbe.endorsing_dept_id',3)
        ->select('bbe.id','busn_id','bb.busn_name','bend_year','bend_status')
        ->get();
    }
    public function getBusnComAddress($end_id,$busn_id){
        return DB::table('bplo_business_endorsement')
        ->leftjoin('bplo_business as bb', 'bb.id', '=', 'bplo_business_endorsement.busn_id')
        ->leftjoin('clients as cc', 'cc.id', '=', 'bb.client_id')
        ->where('bplo_business_endorsement.id',$end_id)
        ->where('bplo_business_endorsement.busn_id',$busn_id)
        ->select('bplo_business_endorsement.id','cc.full_name','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','busn_id','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision','bb.busn_office_main_barangay_id')->first();
    }
    
    public function getEmployee(){
        return DB::table('hr_employees')->select('id','fullname')->get();
    }
    public function getPosition($id){
        $data= HrEmployee::where('id',$id)->first();
        return $data->designation->description;
    }
     public function getBusnId($id){
        return DB::table('bplo_business_endorsement')
        ->join('bplo_business', 'bplo_business.id', '=', 'bplo_business_endorsement.busn_id')
        ->select('bplo_business_endorsement.id','busn_id','bplo_business.busn_name','bend_year','bend_status')
        ->where('bplo_business_endorsement.id',$id)
        ->first();
    }
    public function getBusn($id){
        return DB::table('bplo_business')
        ->where('id',$id)
        ->first();
    }
    
    
    // public function getRequirements(){
    //     return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_health_office',1)->where('is_active',1)->get();
    // }
    public function getRequirements(){
        return DB::table('ho_services')->select('id','ho_service_name')->where('ho_is_active',1)->get();
    }
    public function updateHealthcertiReqData($id,$columns){
        return DB::table('ho_app_health_cert_req')->where('id',$id)->update($columns);
    }
    public function addHealthcertiReqlData($postdata){
        DB::table('ho_app_health_cert_req')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getHealthcertiReqData($id){
        return DB::table('ho_app_health_cert_req')->where('hahc_id',$id)->get();
    }
    public function getCitizenDetails($id){
        return  DB::table('citizens')
                ->leftjoin('countries', 'countries.id', '=', 'citizens.country_id')
                ->select('citizens.*','countries.nationality')
                ->where('citizens.id',$id)->first();
    }
    
 	  public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        return $sql->get();
    }
    public function deleteCertificateReq($id){
        return DB::table('ho_app_health_cert_req')->where('id', $id)->delete();
    }

    public function getgetHealthcertiReqDatabyCat($id,$catid){
        return DB::table('ho_app_health_cert_req')
                ->join('ho_services', 'ho_services.id', '=', 'ho_app_health_cert_req.req_id')
                ->select('ho_app_health_cert_req.id','ho_app_health_cert_req.hahcr_exam_date','ho_app_health_cert_req.hahcr_exam_result','ho_services.ho_service_name as ho_service_name')->where('ho_app_health_cert_req.hahc_id',$id)->where('ho_app_health_cert_req.hahcr_category',$catid)->get();
    }

    public function getHoappHealthcerti($id){
      return DB::table('ho_app_health_certs')
             ->join('citizens', 'citizens.id', '=', 'ho_app_health_certs.citizen_id')
             ->leftjoin('countries as count', 'count.id', '=', 'citizens.country_id')
             ->leftjoin('hr_employees as r_apv', 'r_apv.id', '=', 'ho_app_health_certs.hahc_recommending_approver')
             ->leftjoin('hr_employees as apv', 'apv.id', '=', 'ho_app_health_certs.hahc_approver')
             ->select('ho_app_health_certs.id','count.nationality','hahc_issuance_date','hahc_expired_date','hahc_place_of_work','hahc_registration_no','cit_last_name','cit_fullname','cit_first_name','employee_occupation','cit_middle_name','cit_suffix_name','cit_age','cit_gender','cit_date_of_birth','r_apv.fullname as r_apv_name','r_apv.user_id as r_apv_user_id','apv.fullname as apv_name','apv.user_id as apv_user_id','hahc_approver_position','hahc_recommending_approver_position')
             ->where('ho_app_health_certs.id',$id)->first();
    }
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        
        
        $columns = array( 
            0 =>"hc.id",  //
            1 =>"hahc_app_year",//
            2 =>"citizens.cit_fullname",
            3 =>"cit_gender",
            4 =>"hahc_issuance_date",
            5 =>"hahc_expired_date",
            6 =>"created_at",  
            7 =>"busn_name", // 
            8 =>"hahc_registration_no",//
            9 =>"is_active"
           );
         $sql = DB::table('ho_app_health_certs AS hc')
                ->leftjoin('citizens', 'citizens.id', '=', 'hc.citizen_id')
                ->leftjoin('bplo_business', 'bplo_business.id', '=', 'hc.busn_id')
                ->select('hc.id','citizens.cit_fullname','hahc_app_code','hc.created_at','hahc_app_year','hahc_app_no','hahc_transaction_no','hahc_registration_no','hahc_issuance_date','hahc_expired_date','hahc_status','hahc_remarks','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_date_of_birth','cit_gender','bplo_business.busn_name','hahc_recommending_approver','hahc_approver','hahc_approver_status','hahc_recommending_approver_status','hc.hahc_status as is_active');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(hc.id)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(hahc_app_year)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(citizens.cit_fullname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cit_first_name, ' ',cit_middle_name,' ',cit_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cit_gender)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bplo_business.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(hahc_registration_no)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($year) && isset($year)){
            $sql->where('hahc_app_year',$year);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $orderByColumnIndex = $params['order'][0]['column'];
            $orderByColumn = $columns[$orderByColumnIndex];
            if ($orderByColumnIndex == 2) { // Assuming the column index for the name field is 4
                $sql->orderBy(DB::raw("CONCAT(citizens.cit_first_name, ' ', citizens.cit_middle_name, ' ', citizens.cit_last_name)"), $params['order'][0]['dir']);
            }
            else {
                $sql->orderBy($orderByColumn, $params['order'][0]['dir']);
            }
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('hc.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }   
    
}
