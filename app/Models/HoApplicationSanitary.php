<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoApplicationSanitary extends Model
{
    public $table = 'ho_application_sanitaries';

    public function updateData($id,$columns){
        return DB::table('ho_application_sanitaries')->where('id',$id)->update($columns);
    }
    public function findDataById($id){
        return DB::table('ho_application_sanitaries')->where('id',$id)->first();
    }
    public function getYearDetails(){
        return DB::table('ho_application_sanitaries')->select('has_app_year')->groupBy('has_app_year')
        ->orderBy('has_app_year','DESC')
        ->get()->toArray(); 
    }
    public function addData($postdata){
         DB::table('ho_application_sanitaries')->insert($postdata);
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
    public function total_employees($busin_id,$year){
        return DB::table('bplo_business_history')->select('id','busn_employee_no_female','busn_employee_no_male')->where('busn_id',$busin_id)->where('busn_tax_year',$year)->where('app_code', '<>', 3)->first();
    }
    public function totalHealthCard($busin_id, $year) {
        return DB::table('ho_app_health_certs')
            ->where('busn_id', $busin_id)
            ->where('hahc_app_year', $year)
            ->count();
    }
    public function getbploApplications(){
       return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }
    public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getDataByBusnId($busn_id){
        return DB::table('ho_application_sanitaries')->where('busn_id',$busn_id)->first();
    }
    public function getCountries(){
        return DB::table('countries')->select('id','country_name')->where('is_active',1)->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_health_office',1)->where('is_active',1)->get();
    }
    public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        return $sql->get();
    }
    public function getappSanitaryReqData($id){
        return DB::table('ho_application_sanitary_req')
                ->join('requirements', 'requirements.id', '=', 'ho_application_sanitary_req.req_id')
                ->select('ho_application_sanitary_req.*','requirements.req_description')
                ->where('ho_application_sanitary_req.has_id',$id)->get();
    }
    public function getBploApplictaions($id){
        return DB::table('bplo_applications')->select('id','ba_business_account_no','profile_id','barangay_id')->where('id','=',$id)->first();
    }
    public function updateAppsanitaryReqData($id,$columns){
        return DB::table('ho_application_sanitary_req')->where('id',$id)->update($columns);
    }
    public function addAppsanitaryReqlData($postdata){
        DB::table('ho_application_sanitary_req')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEmployee(){
        return DB::table('hr_employees')->select('id','fullname')->get();
    }
     public function getAppSanitary($id){

      return DB::table('ho_application_sanitaries AS has')
      ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'has.busn_id')
      ->leftjoin('clients as cc', 'cc.id', '=', 'bb.client_id')
      ->leftjoin('countries as count', 'count.id', '=', 'cc.country')
      ->leftjoin('hr_employees as r_apv', 'r_apv.id', '=', 'has.has_recommending_approver')
      ->leftjoin('hr_employees as apv', 'apv.id', '=', 'has.has_approver')
      ->select('has.id','cc.rpo_custom_last_name','count.nationality','cc.full_name','cc.rpo_first_name','cc.rpo_middle_name','bb.busn_name','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision','bb.busn_office_main_barangay_id','has_app_year','has_app_no','has_transaction_no','has_type_of_establishment','has_issuance_date','has_expired_date','has_permit_no','has_approver','has_approver_status','has_status','has_remarks','r_apv.fullname as r_apv_name','r_apv.user_id as r_apv_user_id','apv.fullname as apv_name','apv.user_id as apv_user_id','has_approver_position','has_recommending_approver_position')
      ->where('has.id',(int)$id)->first();
    }
    public function deleteSanitaryReq($id){
        return DB::table('ho_application_sanitary_req')->where('id', $id)->delete();
    }
    public function findSanitaryReq($id){
        return DB::table('ho_application_sanitary_req')->where('id', $id)->first();
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
          0 =>"has.id",  
          1 =>"has_app_year",
          2 =>"bb.busn_name",
          3 =>"bb.busn_office_main_building_no",
          4 =>"cc.full_name",
          5 =>"has_type_of_establishment",
          6 =>"has_issuance_date",  
          7 =>"has_expired_date", 
          8 =>"has_transaction_no",
          9 =>"has_permit_no",
          10 =>"has_status",
         );
         $sql = DB::table('ho_application_sanitaries AS has')
            ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'has.busn_id')
            ->leftjoin('clients AS cc', 'cc.id', '=', 'bb.client_id')
            ->select('has.id','has.busn_id as busn_id','has.bend_id as end_id','bb.busn_name','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision','bb.busn_office_main_barangay_id','has_app_year','has_app_no','has_transaction_no','has_type_of_establishment','has_issuance_date','has_expired_date','has_permit_no','has_approver','has_approver_status','has_recommending_approver_status','has_status','has_remarks','cc.suffix','cc.full_name','cc.rpo_first_name','cc.rpo_middle_name','cc.rpo_custom_last_name');
        if(!empty($year) && isset($year)){
                $sql->where('has_app_year',$year);
        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                if(strtolower($q) == "active") { $q=1; } elseif(strtolower($q) == "inactive") { $q=0; } 
                $sql->where(DB::raw('LOWER(has.id)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(has_app_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.suffix)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(has_type_of_establishment)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(has_app_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(has_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(has_status)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if (isset($params['order'][0]['column'])) {
            $orderByColumnIndex = $params['order'][0]['column'];
            $orderByColumn = $columns[$orderByColumnIndex];
            if ($orderByColumnIndex == 4) { // Assuming the column index for the name field is 4
                $sql->orderBy(DB::raw("CONCAT(cc.rpo_first_name, ' ', cc.rpo_middle_name, ' ', cc.rpo_custom_last_name)"), $params['order'][0]['dir']);
            }
            else {
                $sql->orderBy($orderByColumn, $params['order'][0]['dir']);
            }
        } else {
            $sql->orderBy('has.id', 'DESC');
        }
          

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
