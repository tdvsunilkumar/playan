<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Support\Facades\Storage;

class EngJobRequestOnline extends Model
{
    public $table = 'eng_job_requests';
    protected $connection = 'remort_server';
    public $remortServer ="";

    public function __construct() 
    {
        date_default_timezone_set('Asia/Manila');
       $this->remortServer = DB::connection('remort_server');
    } 

    // for prints
  public function applicant() //the applicant
  { 
      return $this->hasOne(EngClients::class, 'id', 'client_id'); 
  }
  public function brgy() 
  {
      return $this->hasOne(Barangay::class, 'id', 'brgy_code');
  }
  public function permit() // for sign, building permit
  {
      return $this->hasOne(EngBldgPermitApp::class, 'ejr_id', 'id');
  }
  public function details() 
  { 
    switch ($this->es_id) {
      case 1:
        return $this->hasOne(EngJobReqBuilding::class, 'ejr_id', 'id'); //check
      case 2:
        return $this->hasOne(EngJobReqDemolition::class, 'ejr_id', 'id'); //check
      case 3:
        return $this->hasOne(EngJobReqSanitary::class, 'ejr_id', 'id');
      case 4:
        return $this->hasOne(EngJobReqFence::class, 'ejr_id', 'id');
      case 5:
        return $this->hasOne(EngJobReqExcavation::class, 'ejr_id', 'id');
      case 6:
        return $this->hasOne(EngJobReqElectrical::class, 'ejr_id', 'id');
      // case 7://
      //   return $this->hasOne(EngJobReqBuilding::class, 'ejr_id', 'id');
      case 8:
        return $this->hasOne(EngJobReqSign::class, 'ejr_id', 'id');
      case 9:
        return $this->hasOne(EngJobReqElectronics::class, 'ejr_id', 'id');
      case 10:
        return $this->hasOne(EngJobReqMechanical::class, 'ejr_id', 'id');
      case 11://
        return $this->hasOne(EngJobReqCivil::class, 'ejr_id', 'id');
      // case 12://
      //   return $this->hasOne(EngJobReqBuilding::class, 'ejr_id', 'id');
      case 13://
        return $this->hasOne(EngJobReqArchitectural::class, 'ejr_id', 'id');
    }
  }
  public function approver()
  {
      return $this->hasOne(User::class, 'id', 'ejr_opd_approved_by');
  }
  public function getCurrentAddressAttribute()
  {
    $brgy = ($this->brgy_code)?', '.Barangay::findDetails($this->brgy_code):'';
      return $this->rpo_address_house_lot_no.', '.$this->rpo_address_street_name.', '.$this->rpo_address_subdivision.$brgy;
  }
  // end for prints

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

    public function getDataJobrequestedit($id){
    	return $this->remortServer->table('eng_job_requests')->where('id',$id)->first();
    }
    
    public function getServicefees(){
    	return DB::table('cto_tfocs')->select('id','tfoc_short_name')->where('tfoc_status',1)->get();
    }
    public function getOwners(){
        return DB::table('clients')->select('id','rpo_custom_last_name','full_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',1)->where('is_active',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function getRptOwners(){
        return DB::table('clients')->select('id','rpo_custom_last_name','full_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',1)->where('is_active',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function getextrafees(){
        return $this->remortServer->table('eng_services AS es')
        ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'es.tfoc_id') 
        ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id')->select('asl.description','es.tfoc_id')->where('es.is_main_service','=','0')->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_description')->where('is_active',1)->get();
    }
    public function updateData($id,$columns){
        return $this->remortServer->table('eng_job_requests')->where('frgn_ejr_id',$id)->update($columns);
    }
    public function getClientidJobrequest($id){
        return $this->remortServer->table('eng_job_requests')->select('client_id')->where('frgn_ejr_id',$id)->get();
    }
    public function addData($postdata){
        DB::table('eng_job_requests')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }
    
    public function getEngmunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','7')->limit(1)->first();
    }

    public function deleteFeedetailsrow($id){
        return DB::table('eng_job_request_fees_details')->where('id',$id)->delete();
    }
    public function GetDefaultfees(){
        return $this->remortServer->table('eng_job_request_default_fees')->select('fees_description')->orderby('id','ASC')->get();
    }

    public function GetReqiestfees($id){
        return $this->remortServer->table('eng_job_request_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('ejr_id',$id)->orderby('id','ASC')->get();
    }

    public function getUserrole($id){
           return DB::table('users_role as ur')->leftjoin('role AS r','r.id','=','ur.role_id')->select('r.id','r.name')->where('user_id',$id)->get();
    }

    public function TransactionupdateData($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function checkTransactionexist($id,$tttypeid){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id',$tttypeid)->get();
    }
    public function getORandORdate($id){
      return DB::table('cto_cashier')->select('or_no',DB::raw('DATE(created_at) AS created_at'))->where('top_transaction_id',$id)->get()->toArray();
    }

    public function TransactionaddData($postdata){
        DB::table('cto_top_transactions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function addJobrequestFeesDetailData($postdata){
        DB::table('eng_job_request_fees_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateJobrequestFeesDetailData($id,$columns){
        return DB::table('eng_job_request_fees_details')->where('id',$id)->update($columns);
    }
    public function checkJobrequestFeesDetail($feedesc,$ejrid){
         return DB::table('eng_job_request_fees_details')->select('*')->where('ejr_id',$ejrid)->where('fees_description',$feedesc)->get();
    }

    public function orderpaymentbuild($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_bldg_permit_apps AS ebp', 'ebp.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'ebp.ebpa_mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->leftjoin('eng_bldg_fees_details AS ebfd', 'ebfd.ebpa_id', '=', 'ebp.id')->select('mun.mun_desc','ebp.ebpa_location','ebp.ebot_id','ebfd.ebfd_sign_category','ebfd.ebfd_sign_consultant_id','ebp.ebot_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name',DB::raw('DATE(ebpa_application_date) AS ebpa_application_date'),DB::raw('DATE(ebpa_issued_date) AS ebpa_issued_date'))->where('ejr.id',$id)->get()->first();  
    }

    public function orderpaymentdemolition($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_demolition_app AS eda', 'eda.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'eda.mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','eda.eda_location','eda.ebot_id','eda.eda_sign_category','eda.eda_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first();  
    }

    public function orderpaymentsanitary($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_sanitary_plumbing_apps AS esa', 'esa.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'esa.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','esa.espa_location','esa.ebot_id','esa.espa_sign_category','esa.espa_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first();  
    }

    public function orderpaymentfencing($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_fencing_app AS efa', 'efa.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'efa.mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','efa.ebpa_location','efa.ebot_id','efa.efa_sign_category','efa.efa_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first();  
    }

    public function orderpaymentexcavation($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_excavation_ground_app AS exga', 'exga.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'exga.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','exga.eega_location','exga.ebot_id','exga.eega_sign_category','exga.eega_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first(); 
    }

    public function orderpaymentelectric($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_excavation_ground_app AS exga', 'exga.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'exga.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','exga.eega_location','exga.ebot_id','exga.eega_sign_category','exga.eega_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first();  
    }

     public function orderpaymentsign($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_sign_app AS esa', 'esa.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'esa.mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','esa.ebpa_location','esa.ebot_id','esa.esa_sign_category','esa.esa_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first();  
    }

     public function orderpaymentelectronic($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_electronics_app AS eea', 'eea.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'eea.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','eea.eeta_location','eea.ebot_id','eea.eeta_sign_category','eea.eeta_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first(); 
    }

     public function orderpaymentmechanical($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_mechanical_app AS ema', 'ema.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'ema.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','ema.ema_location','ema.ebot_id','ema.ema_sign_category','ema.ema_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first(); 
    }

     public function orderpaymentcivil($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_civil_app AS eca', 'eca.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'eca.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','eca.eca_location','eca.ebot_id','eca.eca_sign_category','eca.eca_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first(); 
    }

     public function orderpaymentarchitect($id){
       return DB::table('eng_job_requests as ejr')->leftjoin('eng_architectural_app AS eaa', 'eaa.ejr_id', '=', 'ejr.id')->leftjoin('profile_municipalities AS mun', 'eaa.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ejr.client_id', '=', 'c.id')->select('mun.mun_desc','eaa.eea_location','eaa.ebot_id','eaa.eea_sign_category','eaa.eea_sign_consultant_id','ejr.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name')->where('ejr.id',$id)->get()->first(); 
    }
    
    public function gethremployess(){ 
      return DB::table('hr_employees')->select('id','fullname')->where('is_active',1)->get();
    } 

    public function getEmployeeDetails($id){
       return DB::table('hr_employees')->select('fullname','emp_prc_no','emp_ptr_no','emp_issue_date','emp_issue_at','emp_prc_validity','tin_no','c_house_lot_no','c_street_name','c_subdivision','c_brgy_code','c_region')->where('id',$id)->get()->first();
    }

    public function getExternalDetails($id){
       return DB::table('consultants')->select('fullname','prc_no','ptr_no','ptr_date_issued','prc_validity','tin_no','house_lot_no','street_name','subdivision','brgy_code','country')->where('id',$id)->get()->first();
    }

    public function getExteranls(){ 
      return DB::table('consultants')->select('id','fullname')->get();
    } 
    public function getEditDetailsbldApp($id){
         return $this->remortServer->table('eng_bldg_permit_apps')->select('*',DB::raw('DATE(ebpa_application_date) AS ebpa_application_date'),DB::raw('DATE(ebpa_issued_date) AS ebpa_issued_date'))->where('ejr_id',$id)->get()->first();
    }

    public function getEditDetailsbldAppforprint($id){
         return DB::table('eng_bldg_permit_apps as ebp')->leftjoin('profile_municipalities AS mun', 'ebp.ebpa_mun_no', '=', 'mun.id')->select('mun.mun_desc','ebp.*',DB::raw('DATE(ebpa_application_date) AS ebpa_application_date'),DB::raw('DATE(ebpa_issued_date) AS ebpa_issued_date'))->where('ejr_id',$id)->get()->first();
    }

    public function Getbidappid($id){
		
      return $this->remortServer->table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->where('ejr_id',$id)->get()->first();
    }

    public function getEmployeesDetails($id){
       return DB::table('hr_employees')->select('*')->where('id',$id)->get()->first();
    }
    public function getExteranlsDetails($id){
       return DB::table('consultants')->select('*')->where('id',$id)->get()->first();
    }
    
    public function getEditDetailsSanitaryApp($id){
         return $this->remortServer->table('eng_sanitary_plumbing_apps as esp')
         ->leftjoin('clients AS c', 'esp.p_code', '=', 'c.id')
         ->select('esp.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')
         ->where('esp.ejr_id',$id)->first();
    }
    public function getpermitno($id){
        return DB::table('eng_bldg_permit_apps')->select('ebpa_permit_no')->where('id',$id)->get()->first();
    }
    
    public function getDetailsbldgFeesedit($id){
         return DB::table('eng_bldg_fees_details')->select('*')->where('ebpa_id',$id)->get()->first();
    }

    public function getFixturetypefees(){
       return DB::table('eng_fixture_types')->select('id','eft_fees')->where('eft_is_active',1)->orderby('id','ASC')->get();
    }

    public function getFloorarea($ejr_id){
         return DB::table('eng_bldg_permit_apps as eba')->join('eng_bldg_fees_details as ebd','eba.id','=','ebd.ebpa_id')->select('ebd.ebfd_floor_area')->where('eba.ejr_id',$ejr_id)->first();
    }

    public function getAssessmentFeesedit($id){
         return DB::table('eng_bldg_assessment_fees')->select('*')->where('ebpa_id',$id)->get()->first();
    }
    public function addDataBldgFeeData($postdata){
        DB::table('eng_bldg_fees_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getlatestseries(){
        return DB::table('eng_job_requests')->select('permitnoseries')->orderby('permitnoseries','DESC')->get()->first();
    }

    public function getAppType(){
        return DB::table('eng_bldg_aptypes')->select('id','eba_description')->get();
    }
    public function updatePermitAppData($id,$columns){
        return DB::table('eng_bldg_permit_apps')->where('id',$id)->update($columns);
    }
    public function addPermitAppData($postdata){
        DB::table('eng_bldg_permit_apps')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function addSanitaryAppData($postdata){
        DB::table('eng_sanitary_plumbing_apps')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateSanitaryAppData($id,$columns){
        return DB::table('eng_sanitary_plumbing_apps')->where('id',$id)->update($columns);
    }

    public function getEditDetailsElecticApp($id){
         //return DB::table('eng_electrical_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_electrical_app as eea')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('eea.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eea.ejr_id',$id)->get()->first();
    }

    public function addElecticAppData($postdata){
        DB::table('eng_electrical_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateElecticAppData($id,$columns){
        return DB::table('eng_electrical_app')->where('id',$id)->update($columns);
    }

    public function getEditDetailsCivilApp($id){
         //return DB::table('eng_civil_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_civil_app as eca')->leftjoin('clients AS c', 'eca.p_code', '=', 'c.id')->select('eca.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eca.ejr_id',$id)->get()->first();
    }
    public function getEditDetailsCivilforprint($id){
         return DB::table('eng_civil_app as eca')->leftjoin('profile_municipalities AS mun', 'eca.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'eca.p_code', '=', 'c.id')->select('mun.mun_desc','eca.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_barangay_id_no','c.p_telephone_no','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.rpo_address_street_name')->where('ejr_id',$id)->get()->first();
    }

    public function addCivilAppData($postdata){
        DB::table('eng_civil_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateCivilAppData($id,$columns){
        return DB::table('eng_civil_app')->where('id',$id)->update($columns);
    }
    public function getEditDetailsFencingApp($id){
         //return DB::table('eng_fencing_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_fencing_app as efa')->leftjoin('clients AS c', 'efa.p_code', '=', 'c.id')->select('efa.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('efa.ejr_id',$id)->get()->first();
    }

    public function getEditDetailsSFencingforprint($id){
         return DB::table('eng_fencing_app as efa')->leftjoin('profile_municipalities AS mun', 'efa.mun_no', '=', 'mun.id')->select('mun.mun_desc','efa.*')->where('ejr_id',$id)->get()->first();
    }

    public function addFencingAppData($postdata){
        DB::table('eng_fencing_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateFencingAppData($id,$columns){
        return DB::table('eng_fencing_app')->where('id',$id)->update($columns);
    }

    public function getEditDetailsSignApp($id){
         //return DB::table('eng_sign_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_sign_app as esa')->leftjoin('clients AS c', 'esa.p_code', '=', 'c.id')->select('esa.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('esa.ejr_id',$id)->get()->first();
    }

    public function getEditDetailsSignforprint($id){
         return DB::table('eng_sign_app as esa')->leftjoin('profile_municipalities AS mun', 'esa.mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'esa.p_code', '=', 'c.id')->select('mun.mun_desc','esa.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_telephone_no')->where('ejr_id',$id)->get()->first();
    }

    public function addSignAppData($postdata){
        DB::table('eng_sign_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateSignAppData($id,$columns){
        return DB::table('eng_sign_app')->where('id',$id)->update($columns);
    }

    // public function getEditDetailsDemolitionApp($id){
    //      return DB::table('eng_demolition_app')->select('*')->where('ejr_id',$id)->get()->first();
    // }

     public function getEditDetailsDemolitionApp($id){
         return $this->remortServer->table('eng_demolition_app as eda')->leftjoin('clients AS c', 'eda.p_code', '=', 'c.id')->select('eda.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eda.ejr_id',$id)->get()->first();
    }

    public function addDemolitionAppData($postdata){
        DB::table('eng_demolition_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateDemolitionAppData($id,$columns){
        return DB::table('eng_demolition_app')->where('id',$id)->update($columns);
    }
    public function getEditDetailsArchitecturalApp($id){
         //return DB::table('eng_architectural_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_architectural_app as era')->leftjoin('clients AS c', 'era.p_code', '=', 'c.id')->select('era.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('era.ejr_id',$id)->get()->first();
    }
   
    public function addArcghitecturalAppData($postdata){
        DB::table('eng_architectural_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateArchitecturalAppData($id,$columns){
        return DB::table('eng_architectural_app')->where('id',$id)->update($columns);
    }
    public function getEditDetailsElectronicsApp($id){
         //return DB::table('eng_electronics_app')->select('*')->where('ejr_id',$id)->get()->first();
         return $this->remortServer->table('eng_electronics_app as eea')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('eea.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eea.ejr_id',$id)->get()->first();
    }

    public function addElectronicsAppData($postdata){
        DB::table('eng_electronics_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateElectronicsAppData($id,$columns){
        return DB::table('eng_electronics_app')->where('id',$id)->update($columns);
    }

    public function getEditDetailsMechanicalApp($id){
         // return DB::table('eng_mechanical_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_mechanical_app as ema')->leftjoin('clients AS c', 'ema.p_code', '=', 'c.id')->select('ema.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('ema.ejr_id',$id)->get()->first();
    }
  
    public function addMechanicalAppData($postdata){
        DB::table('eng_mechanical_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateMechanicalAppData($id,$columns){
        return DB::table('eng_mechanical_app')->where('id',$id)->update($columns);
    }
    public function getEditDetailsExcavationApp($id){
        // return DB::table('eng_excavation_ground_app')->select('*')->where('ejr_id',$id)->get()->first();
          return $this->remortServer->table('eng_excavation_ground_app as exga')->leftjoin('clients AS c', 'exga.p_code', '=', 'c.id')->select('exga.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('exga.ejr_id',$id)->get()->first();
    }
    public function getBarangaybyid($id){
      return DB::table('barangays')->select('brgy_name')->where('id',$id)->get()->first();
    }
   
    public function GetBuildingpermits($id){
      return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->where('p_code',$id)->where('ebpa_permit_no','<>','')->get();
    }
     public function GetBuildingpermitsall(){
      return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->get();
    }
    public function addExcavationAppData($postdata){
        DB::table('eng_excavation_ground_app')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateExcavationAppData($id,$columns){
        return DB::table('eng_excavation_ground_app')->where('id',$id)->update($columns);
    }
    public function addJobRequirementsData($postdata){
        DB::table('eng_job_request_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    } 
    public function AddengFilesData($postdata){
        DB::table('eng_files')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkRequirementfileexist($id){
       return DB::table('eng_files')->where('ejrr_id',$id)->get()->toArray();
    }

    public function UpdateengFilesData($id,$columns){
        return DB::table('eng_files')->where('id',$id)->update($columns);
    }
    public function getCasheringIds($id){
       return $this->remortServer->table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id')->where('id',$id)->first();
    }

    public function getJobRequirementsData($id){
           return $this->remortServer->table('eng_job_request_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('eng_files AS fe', 'fe.ejrr_id', '=', 'esr.frgn_ejrr_id')
          ->select('fe.id as feid','esr.id','esr.req_id','re.req_code_abbreviation','re.req_description','fe.fe_name','fe.fe_path')->where('esr.ejr_id',$id)->groupby('esr.id')->orderby('esr.id','ASC')->get()->toArray();
    } 
   
    public function deleteRequirementsbyid($id){
       return DB::table('eng_job_request_requirements')->where('id',$id)->delete();
    }
    public function deleteimagerowbyid($id){
      return DB::table('eng_files')->where('id',$id)->delete();
    }
    public function checkJobRequirementsexist($id,$reqid){
        return DB::table('eng_job_request_requirements')->where('ejr_id',$id)->where('req_id',$reqid)->get()->toArray();
    }
    public function getEditDetailsbldgFees($id){
        return DB::table('eng_bldg_fees_details')->where('ebpa_id',$id)->get()->toArray();
    }
    public function updatePermitbldgFees($id,$columns){
        return DB::table('eng_bldg_fees_details')->where('ebpa_id',$id)->update($columns);
    }

    public function getEditAssessmentFees($id){
        return DB::table('eng_bldg_assessment_fees')->where('ebpa_id',$id)->get()->toArray();
    }
    public function updateAssessmentbldgFees($id,$columns){
        return DB::table('eng_bldg_assessment_fees')->where('ebpa_id',$id)->update($columns);
    }

    public function getTaxcertificatedetails($id){
             return DB::table('cto_cashier as cc')->leftjoin('clients as c','c.id','=','cc.client_citizen_id')->select('c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','cc.cashier_batch_no','cc.or_no','cc.cashier_batch_no','cc.ctc_place_of_issuance',DB::raw('DATE(cc.created_at) AS created_at'))->where('cc.payee_type','1')->where('cc.client_citizen_id',$id)->orderby('cc.id','DESC')->first();
    }

    public function getTaxcertificatedetailsforprint($id){
             return DB::table('cto_cashier as cc')->leftjoin('clients as c','c.id','=','cc.client_citizen_id')->select('c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','cc.cashier_batch_no','cc.or_no','cc.cashier_batch_no','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','cc.ctc_place_of_issuance',DB::raw('DATE(cc.created_at) AS created_at'))->where('cc.payee_type','1')->where('cc.client_citizen_id',$id)->orderby('cc.id','DESC')->first();
    }

    public function GetOwnerDetailsforprint($id){
       return DB::table('clients as c')->leftjoin('cto_cashier as cc','c.id','=','cc.client_citizen_id')->select('c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','cc.ctc_place_of_issuance',DB::raw('DATE(cc.created_at) AS created_at'))->where('c.id',$id)->orderby('c.id','DESC')->first();
    }

    public function addAssessmentAppData($postdata){
        DB::table('eng_bldg_assessment_fees')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getElectricalmiscellaneous(){
      return DB::table('eng_electrical_feess_miscellaneos')->select('id','eefm_description','eefpa_electic_meter_amount','eefpa_wiring_permit_amount')->where('is_active',1)->get()->toArray();
    }

    public function getloaddatarange($load){
        return DB::table('eng_electrical_fees_load')->select('eefl_kva_range_from','eefl_fees','eef_in_excess_fees')->where('eefl_kva_range_from','<=',$load)->where('eefl_kva_range_to','>=',$load)->first();
    }

    public function getengbuildingDivision(){
      return DB::table('eng_building_permit_fees_division')->select('ebpfd_description','id')->where('ebpfd_status','=',1)->get();
    }

    public function getpoleattachcost($qty,$id){
        return DB::table('eng_electrical_feess_pole_attachments')->select('eefpa_amount')->where('id','=',$id)->first();
    }
    public function getmiscellanousrow($id){
        return DB::table('eng_electrical_feess_miscellaneos')->select('eefpa_electic_meter_amount','eefpa_wiring_permit_amount')->where('id','=',$id)->first();
    }

    public function getupsdatarange($load){
        return DB::table('eng_electrical_fees_ups')->select('eefu_kva_range_from','eefu_fees','eefu_in_excess_fees')->where('eefu_kva_range_from','<=',$load)->where('eefu_kva_range_to','>=',$load)->first();
    }

    public function getdataset1($area){
        return DB::table('eng_building_permit_fees_set1')->select('ebpfs1_fees')->where('ebpfs1_range_from','<=',$area)->where('ebpfs1_range_to','>=',$area)->first();
    }
    public function getdataset2($area){
        return DB::table('eng_building_permit_fees_set2')->select('ebpfs2_fees')->where('ebpfs2_range_from','<=',$area)->where('ebpfs2_range_to','>=',$area)->first();
    }

    public function getdataset3($area){
        return DB::table('eng_building_permit_fees_set3')->select('ebpfs3_fees')->where('ebpfs3_range_from','<=',$area)->where('ebpfs3_range_to','>=',$area)->first();
    }
    public function getdataset4($area){
        return DB::table('eng_building_permit_fees_set4')->select('ebpfs4_fees')->where('ebpfs4_range_from','<=',$area)->where('ebpfs4_range_to','>=',$area)->first();
    }

    public function geteditdataElectricfee($jobrequestid){
      return DB::table('eng_electrical_fees')->select('id')->where('eef_jobrequestid','=',$jobrequestid)->get();
    }

    public function geteditdataElectricfeeshow($jobrequestid){
      return DB::table('eng_electrical_fees')->select('*')->where('eef_jobrequestid','=',$jobrequestid)->first();
    }

    public function geteditdataBuildingfee($jobrequestid){
      return DB::table('eng_building_permit_fees')->select('id')->where('ejr_id','=',$jobrequestid)->get();
    }

    public function geteditdataBuildingfeeshow($jobrequestid){
      return DB::table('eng_building_permit_fees')->select('*')->where('ejr_id','=',$jobrequestid)->first();
    }
    

    public function AddElecticfessData($postdata){
        DB::table('eng_electrical_fees')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateElecticFeesData($id,$columns){
        return DB::table('eng_electrical_fees')->where('eef_jobrequestid',$id)->update($columns);
    }

    public function AddBuildingfessData($postdata){
        DB::table('eng_building_permit_fees')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateBuildingFeesData($id,$columns){
        return DB::table('eng_building_permit_fees')->where('ejr_id',$id)->update($columns);
    }
    public function getdivisionsetid($id){
      return DB::table('eng_building_permit_fees_division')->select('ebpfd_feessetid')->where('id',$id)->first();
    }

    public function GetTypeofOccupancy(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_building',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforbuilding(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_building',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforsanitary(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_sanitary',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforelectric(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_electrical',1)->get()->toArray();
    }
     public function GetTypeofOccupancyforelectronic(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_electronics',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforelexacavation(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_excavation_and_ground',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforelarchitecture(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_architectural_permit',1)->get()->toArray();
    }
    public function GetTypeofOccupancyforcivil(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->where('ebot_is_civil_structural_permit',1)->get()->toArray();
    }
    public function GetSubTypeofoccupancy($id){
             return DB::table('eng_bldg_occupancy_sub_types')->select('id','ebost_description')->where('ebost_id',$id)->where('ebost_is_active',1)->get()->toArray();
    }
    public function GetBuildingScopes(){
      return $this->remortServer->table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_building',1)->where('ebs_is_active',1)->get()->toArray();
    }
    public function GetBuildingScopessanitary(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_sanitary',1)->get()->toArray();
    }
    public function GetBuildingScopeselectric(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_electrical',1)->get()->toArray();
    }
    public function GetBuildingScopeselexcavation(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_excavation_and_ground',1)->get()->toArray();
    }
    public function GetBuildingScopeselarchitecture(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_architectural_permit',1)->get()->toArray();
    }
    public function GetBuildingScopeselfetching(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_fencing',1)->get()->toArray();
    }
    public function GetBuildingScopeselsign(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_sign',1)->get()->toArray();
    }
    public function GetBuildingScopeseldemolition(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_demolition',1)->get()->toArray();
    }
    public function GetBuildingScopecivil(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_civil_structural_permit',1)->get()->toArray();
    }
    public function GetBuildingScopeselectronic(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_electronics',1)->get()->toArray();
    }
    public function GetBuildingScopeselmechanical(){
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_active',1)->where('ebs_is_mechanical',1)->get()->toArray();
    }

    public function GetTypeofWaterSupply(){
      return DB::table('eng_water_supply_types')->select('id','ewst_description')->where('ewst_is_active',1)->get()->toArray();
    }

    public function GetEquipmentsSystemType(){
      return DB::table('eng_equipment_system_type')->select('id','eest_description')->where('eest_is_active',1)->get()->toArray();
    }

    public function GetSignDisplayTypes(){
        return DB::table('eng_sign_display_types')->select('id','esdt_description')->where('esdt_is_active',1)->get()->toArray();
    }

    public function GetTypeofFencing(){
        return DB::table('eng_fecing_types')->select('id','eft_description')->where('eft_is_active',1)->get()->toArray();
    }

     public function GetSignInstallationTypes(){
        return $this->remortServer->table('eng_sign_installation_types')->select('id','esit_description')->where('esit_is_active',1)->get()->toArray();
    }

    public function GetInstallationOperationType(){
       return DB::table('eng_installation_operation_types')->select('id','eiot_description')->where('eiot_is_active',1)->get()->toArray();
    }

    public function GetElecticEquipments(){
      return DB::table('eng_electrical_equipment_types')->select('id','eeet_description')->where('eeet_is_active',1)->get()->toArray();
    }
    public function GetElecticArchitectureFeatureType(){
      return DB::table('eng_architectural_features_types')->select('id','eaft_description')->where('eaft_is_active',1)->get()->toArray();
    }

    public function GetExcavationGroundType(){
      return DB::table('eng_excavation_ground_types')->select('id','eegt_description')->where('eegt_is_active',1)->get()->toArray();
    }

    public function GetConfirmnaceFireType(){
      return DB::table('eng_conformance_to_fire_code')->select('id','ectfc_description')->where('ectfc_is_active',1)->get()->toArray();
    }
    public function GetTypeofDisposalSystem(){
      return DB::table('eng_disposal_system_types')->select('id','edst_description')->where('edst_is_active',1)->get()->toArray();
    }
    public function getServices(){
       return DB::table('eng_services AS es')
          ->join('eng_application_type AS eat', 'eat.id', '=', 'es.eat_id')
          ->select('es.id','eat.eat_module_desc as accdesc')->get();;
    }
    public function getProfileDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_mobile_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no')->where('id',(int)$id)->first();
    }
    public function getOwnerClientDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_mobile_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no','suffix')->where('id',(int)$id)->first();
    }
    public function subtypeoccupancy($id){
      return DB::table('eng_bldg_occupancy_sub_types')
              ->select('id','ebost_description')->where('ebost_id',(int)$id)->get();
    }
    public function getapptypeUsingtfocid($id){
        return DB::table('eng_services AS ees')
           ->join('eng_application_type AS eat', 'eat.id', '=', 'ees.eat_id')
          ->select('eat.eat_module_desc','ees.tfoc_id as tfoc_id')->where('ees.id',$id)->get();;
    }
    public function getBarangay(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
    public function getBarangayedit($id){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
       $data = DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.id',$id)->where('bgf.is_active',1)->get();
               return array("data"=>$data);
    }
    public function getSercviceRequirementsall(){
             return $this->remortServer->table('eng_service_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('req_dept_eng',1)->Groupby('re.id')->get();; 
    }
   
    public function GetMuncipalities(){
      return DB::table('profile_municipalities')->select('id','mun_desc')->get()->toArray();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_job_requests')->where('id',$id)->update($columns);
    }

    public function approve($id)
    {
        $remortServernew = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $rowToUpdate = $remortServernew->table('eng_job_requests')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_ejr_id=$rowAttributes['frgn_ejr_id'];
            unset($rowAttributes['id']);
            unset($rowAttributes['frgn_ejr_id']);
            $rowAttributes['is_approved']=1;
            $rowAttributes['is_synced']=1;
            DB::table('eng_job_requests')->insert($rowAttributes);
            $l_ejrapplication_id=DB::getPdo()->lastInsertId(); 
            $ejrjobrequestno = date('Y').str_pad($l_ejrapplication_id, 6, '0', STR_PAD_LEFT); 
            $appsereviceNo = 0;
               $defaultFeesarr = $this->GetDefaultfees();
                //$cashdata = $this->getCasheringIds($rowAttributes['tfoc_id']);
                  foreach ($defaultFeesarr as $key => $value) {
                    $jobfeesdetails =array();
                     $jobfeesdetails['ejr_id'] =$l_ejrapplication_id;
                     $jobfeesdetails['tfoc_id'] = $rowAttributes['tfoc_id'];  
                     $jobfeesdetails['agl_account_id'] = $rowAttributes['agl_account_id'];
                     $jobfeesdetails['sl_id'] = $rowAttributes['sl_id'];
                     $jobfeesdetails['fees_description'] = $value->fees_description;
                     $jobfeesdetails['tax_amount'] = "";
                     $jobfeesdetails['created_by']=\Auth::user()->id;
                     $jobfeesdetails['created_at'] = date('Y-m-d H:i:s');
                     $jobfeesdetails['updated_by']=\Auth::user()->id;
                     $jobfeesdetails['updated_at'] = date('Y-m-d H:i:s');
                      $this->addJobrequestFeesDetailData($jobfeesdetails);
               }
           
            if($rowAttributes['es_id'] =='1'){
                $bldrowToUpdate = $remortServernew->table('eng_bldg_permit_apps')->where('ejr_id',$frgn_ejr_id)->first();
                $bldrowAttributes = get_object_vars($bldrowToUpdate);
                $bldpermitid =  $bldrowAttributes['id'];
                unset($bldrowAttributes['id']);
                unset($bldrowAttributes['frgn_ebpa_id']);
                $bldrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_bldg_permit_apps')->insert($bldrowAttributes);
                $l_bldpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_bldpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_bldg_permit_apps')->where('id',$l_bldpermit_id)->update(['ebpa_application_no' => $appsereviceNo]);    
                $remortServernew->table('eng_bldg_permit_apps')->where('id',$bldpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_ebpa_id' => $l_bldpermit_id]);
                
            }else if($rowAttributes['es_id'] =='2'){ 
                $demolitionrowToUpdate = $remortServernew->table('eng_demolition_app')->where('ejr_id',$frgn_ejr_id)->first();
                $demorowAttributes = get_object_vars($demolitionrowToUpdate);
                $demopermitid =  $demorowAttributes['id'];
                unset($demorowAttributes['id']);
                unset($demorowAttributes['frgn_ejr_id']);
                $demorowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_demolition_app')->insert($demorowAttributes);
                $l_demopermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_demopermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_demolition_app')->where('id',$l_demopermit_id)->update(['eda_application_no' => $appsereviceNo]); 
                $remortServernew->table('eng_demolition_app')->where('id',$demopermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_ejr_id' => $l_demopermit_id]);
            }else if($rowAttributes['es_id'] =='3'){ 
                //$data->class = "sanitarypermit";
                $sanitaryrowToUpdate = $remortServernew->table('eng_sanitary_plumbing_apps')->where('ejr_id',$frgn_ejr_id)->first();
                $sanirowAttributes = get_object_vars($sanitaryrowToUpdate);
                $sanipermitid =  $sanirowAttributes['id'];
                unset($sanirowAttributes['id']);
                unset($sanirowAttributes['frgn_espa_id']);
                $sanirowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_sanitary_plumbing_apps')->insert($sanirowAttributes);
                $l_sanipermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_sanipermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_sanitary_plumbing_apps')->where('id',$l_sanipermit_id)->update(['espa_application_no' => $appsereviceNo]); 
                $remortServernew->table('eng_sanitary_plumbing_apps')->where('id',$sanipermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_espa_id' => $l_sanipermit_id]);
            }else if($rowAttributes['es_id'] =='4'){ 
                //$data->class = "fencingpermit";
                $fecingrowToUpdate = $remortServernew->table('eng_fencing_app')->where('ejr_id',$frgn_ejr_id)->first();
                $fecirowAttributes = get_object_vars($fecingrowToUpdate);
                $fecipermitid =  $fecirowAttributes['id'];
                unset($fecirowAttributes['id']);
                unset($fecirowAttributes['frgn_efa_id']);
                $fecirowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_fencing_app')->insert($fecirowAttributes);
                $l_fecipermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_fecipermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_fencing_app')->where('id',$l_fecipermit_id)->update(['efa_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_fencing_app')->where('id',$fecipermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_efa_id' => $l_fecipermit_id]);
            }else if($rowAttributes['es_id'] =='5'){ 
                //$data->class = "excavationpermit";
                $excavrowToUpdate = $remortServernew->table('eng_excavation_ground_app')->where('ejr_id',$frgn_ejr_id)->first();
                $excarowAttributes = get_object_vars($excavrowToUpdate);
                $excavpermitid =  $excarowAttributes['id'];
                unset($excarowAttributes['id']);
                unset($excarowAttributes['frgn_eega_id']);
                $excarowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_excavation_ground_app')->insert($excarowAttributes);
                $l_excavpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_excavpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_excavation_ground_app')->where('id',$l_excavpermit_id)->update(['eega_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_excavation_ground_app')->where('id',$excavpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_eega_id' => $l_excavpermit_id]);
            }
            else if($rowAttributes['es_id'] =='6'){ 
                //$data->class = "electicpermit";
                $electricrowToUpdate = $remortServernew->table('eng_electrical_app')->where('ejr_id',$frgn_ejr_id)->first();
                $electricrowAttributes = get_object_vars($electricrowToUpdate);
                $electricpermitid =  $electricrowAttributes['id'];
                unset($electricrowAttributes['id']);
                unset($electricrowAttributes['frgn_eea_id']);
                $electricrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_electrical_app')->insert($electricrowAttributes);
                $l_electricpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_electricpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_electrical_app')->where('id',$l_electricpermit_id)->update(['eea_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_electrical_app')->where('id',$electricpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_eea_id' => $l_electricpermit_id]);
            }
            else if($rowAttributes['es_id'] =='8'){ 
                //$data->class = "signpermit";
                $signrowToUpdate = $remortServernew->table('eng_sign_app')->where('ejr_id',$frgn_ejr_id)->first();
                $signrowAttributes = get_object_vars($signrowToUpdate);
                $signpermitid =  $signrowAttributes['id'];
                unset($signrowAttributes['id']);
                unset($signrowAttributes['frgn_esa_id']);
                $signrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_sign_app')->insert($signrowAttributes);
                $l_signpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_signpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_sign_app')->where('id',$l_signpermit_id)->update(['esa_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_sign_app')->where('id',$signpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_esa_id' => $l_signpermit_id]);
            }
            else if($rowAttributes['es_id'] =='9'){ 
                //$data->class = "electronicpermit";
                $elerowToUpdate = $remortServernew->table('eng_electronics_app')->where('ejr_id',$frgn_ejr_id)->first();
                $elerowAttributes = get_object_vars($elerowToUpdate);
                $elepermitid =  $elerowAttributes['id'];
                unset($elerowAttributes['id']);
                unset($elerowAttributes['frgn_eea_id']);
                $elerowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_electronics_app')->insert($elerowAttributes);
                $l_elepermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_elepermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_electronics_app')->where('id',$l_elepermit_id)->update(['eeta_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_electronics_app')->where('id',$elepermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_eea_id' => $l_elepermit_id]);
            }
            else if($rowAttributes['es_id']=='10'){ 
                //$data->class = "mechanicalpermit";
               $mechrowToUpdate = $remortServernew->table('eng_mechanical_app')->where('ejr_id',$frgn_ejr_id)->first();
                $mechrowAttributes = get_object_vars($mechrowToUpdate);
                $mechpermitid =  $mechrowAttributes['id'];
                unset($mechrowAttributes['id']);
                unset($mechrowAttributes['frgn_ema_id']);
                $mechrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_mechanical_app')->insert($mechrowAttributes);
                $l_mechpermit_id=DB::getPdo()->lastInsertId();
                $appsereviceNo = date('Y').'-'.str_pad($l_mechpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_mechanical_app')->where('id',$l_mechpermit_id)->update(['ema_application_no' => $appsereviceNo]); 
                $remortServernew->table('eng_mechanical_app')->where('id',$mechpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_ema_id' => $l_mechpermit_id]);
                
            }
            else if($rowAttributes['es_id']=='11'){ 
                //$data->class = "civilpermit";
                $civilrowToUpdate = $remortServernew->table('eng_civil_app')->where('ejr_id',$frgn_ejr_id)->first();
                $civilrowAttributes = get_object_vars($civilrowToUpdate);
                $civilpermitid =  $civilrowAttributes['id'];
                unset($civilrowAttributes['id']);
                unset($civilrowAttributes['frgn_eca_id']);
                $civilrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_civil_app')->insert($civilrowAttributes);
                $l_civilpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_civilpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_civil_app')->where('id',$l_civilpermit_id)->update(['eca_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_civil_app')->where('id',$civilpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_eca_id' => $l_civilpermit_id]);
            }
            else if($rowAttributes['es_id']=='13'){ 
                $data->class = "architecturalpermit";
                $artechrowToUpdate = $remortServernew->table('eng_architectural_app')->where('ejr_id',$frgn_ejr_id)->first();
                $artechrowAttributes = get_object_vars($artechrowToUpdate);
                $artechpermitid =  $artechrowAttributes['id'];
                unset($artechrowAttributes['id']);
                unset($artechrowAttributes['frgn_eaa_id']);
                $artechrowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_architectural_app')->insert($artechrowAttributes);
                $l_artechpermit_id=DB::getPdo()->lastInsertId(); 
                $appsereviceNo = date('Y').'-'.str_pad($l_artechpermit_id, 4, '0', STR_PAD_LEFT);
                DB::table('eng_architectural_app')->where('id',$l_artechpermit_id)->update(['eea_application_no' => $appsereviceNo]);
                $remortServernew->table('eng_architectural_app')->where('id',$artechpermitid)->update(['ejr_id' => $l_ejrapplication_id,'frgn_eaa_id' => $l_artechpermit_id]);
            }

             DB::table('eng_job_requests')->where('id',$l_ejrapplication_id)->update(['ejr_jobrequest_no' => $ejrjobrequestno,'application_no'=>$appsereviceNo]);

             $eng_ejrrequirment = $remortServernew->table('eng_job_request_requirements')->where('ejr_id',$frgn_ejr_id)->get();

            foreach($eng_ejrrequirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $frgn_eng_reqid=$reqRowAttributes['frgn_ejrr_id']; 
                unset($reqRowAttributes['id']);
                unset($reqRowAttributes['frgn_ejrr_id']);
                $reqRowAttributes['ejr_id'] = $l_ejrapplication_id;
                DB::table('eng_job_request_requirements')->insert($reqRowAttributes);
                $l_eng_req_id=DB::getPdo()->lastInsertId(); 
                $remortServernew->table('eng_job_request_requirements')->where('id',$item->id)->update(['ejr_id' => $l_ejrapplication_id,'frgn_ejrr_id' => $l_eng_req_id]);
                $eng_file = $remortServernew->table('eng_files')->where('ejrr_id',$frgn_eng_reqid)->get();

                foreach($eng_file as $item)
                {
                    $engfileRowAttributes = get_object_vars($item);
                    unset($engfileRowAttributes['id']);
                    unset($engfileRowAttributes['is_synced']);
                    $remotePath = 'public/uploads/engineering/requirements/' . $item->fe_name;

                    //Retrieve the file contents from the remote server
                    $fileContents = Storage::disk('remote')->get($remotePath);
                    if ($fileContents !== false) {
                        // Define the local path where you want to save the file
                        $localPath = public_path() . '/uploads/engineering/requirements/'.$item->fe_name;

                        // Use file_put_contents to save the retrieved file contents locally
                        if (file_put_contents($localPath, $fileContents) !== false) {
                            // File was successfully transferred from remote server to local path
                        } else {
                            // Handle the error if the local file couldn't be saved
                        }
                    } else {
                        // Handle the error if the file couldn't be retrieved from the remote server
                    } 
                    $engfileRowAttributes['ejrr_id'] = $l_eng_req_id;
                    $engfileRowAttributes['ejr_id'] = $l_ejrapplication_id;
                    DB::table('eng_files')->insert($engfileRowAttributes);
                    $remortServernew->table('eng_files')->where('id',$item->id)->update(['ejrr_id' => $l_eng_req_id,'ejr_id'=>$l_ejrapplication_id]);
                }
            }

            $remortServernew->table('eng_job_requests')->where('id',$id)->update(['ejr_jobrequest_no' => $ejrjobrequestno,'application_no'=>$appsereviceNo,'frgn_ejr_id' => $l_ejrapplication_id,'is_approved' => 1]);
            DB::commit();
            return $l_ejrapplication_id;
        } catch (\Exception $e) {
          echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback(); 
            // Handle the exception
        }    
    }  


    public function syncapptoremote($id)
    {
        $remortServernew = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $rowToUpdate = DB::table('eng_job_requests')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $currentapp_id=$rowAttributes['id'];
            unset($rowAttributes['id']);
            $checkappinremote = $remortServernew->table('eng_job_requests')->where('frgn_ejr_id',$currentapp_id)->first();
            if(!empty($checkappinremote)){
                  unset($rowAttributes['frgn_ejr_id']);
                  unset($rowAttributes['is_approved']);
                  unset($rowAttributes['is_synced']); 
                 $remortServernew->table('eng_job_requests')->where('frgn_ejr_id',$currentapp_id)->update($rowAttributes); 
            }else{
                $rowAttributes['frgn_ejr_id'] = $currentapp_id;
                $rowAttributes['is_approved'] = '1';
                $remortServernew->table('eng_job_requests')->insert($rowAttributes);  
            }
            if($rowAttributes['es_id'] =='1'){
                $bldrowToUpdate = DB::table('eng_bldg_permit_apps')->where('ejr_id',$currentapp_id)->first();
                $bldrowAttributes = get_object_vars($bldrowToUpdate);
                $bldpermitid =  $bldrowAttributes['id'];
                unset($bldrowAttributes['id']);
               $checkappinremote = $remortServernew->table('eng_bldg_permit_apps')->where('frgn_ebpa_id',$bldpermitid)->first();
              if(!empty($checkappinremote)){
                 unset($bldrowAttributes['frgn_ebpa_id']);
                 $remortServernew->table('eng_bldg_permit_apps')->where('frgn_ebpa_id',$bldpermitid)->update($bldrowAttributes);
              }else{
                  $bldrowAttributes['frgn_ebpa_id'] = $bldpermitid;
                  $remortServernew->table('eng_bldg_permit_apps')->insert($bldrowAttributes);  
              }
                
            }else if($rowAttributes['es_id'] =='2'){ 
                $demolitionrowToUpdate = DB::table('eng_demolition_app')->where('ejr_id',$currentapp_id)->first();
                $demorowAttributes = get_object_vars($demolitionrowToUpdate);
                $demopermitid =  $demorowAttributes['id'];
                unset($demorowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_demolition_app')->where('frgn_ejr_id',$demopermitid)->first();
                if(!empty($checkappinremote)){
                  unset($demorowAttributes['frgn_ejr_id']);
                  $remortServernew->table('eng_demolition_app')->where('frgn_ejr_id', $demopermitid)->update($demorowAttributes);
                }else{
                  $demorowAttributes['frgn_ejr_id'] = $demopermitid;
                  $remortServernew->table('eng_demolition_app')->insert($demorowAttributes);  
                }
            }else if($rowAttributes['es_id'] =='3'){ 
                //$data->class = "sanitarypermit";
                $sanitaryrowToUpdate = DB::table('eng_sanitary_plumbing_apps')->where('ejr_id',$currentapp_id)->first();
                $sanirowAttributes = get_object_vars($sanitaryrowToUpdate);
                $sanipermitid =  $sanirowAttributes['id'];
                unset($sanirowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_sanitary_plumbing_apps')->where('frgn_espa_id',$sanipermitid)->first();
                if(!empty($checkappinremote)){
                  unset($sanirowAttributes['frgn_espa_id']);
                 $remortServernew->table('eng_sanitary_plumbing_apps')->where('frgn_espa_id', $sanipermitid)->update($sanirowAttributes);
                }else{
                  $sanirowAttributes['frgn_espa_id'] = $sanipermitid;
                  $remortServernew->table('eng_sanitary_plumbing_apps')->insert($sanirowAttributes);  
                }
            }else if($rowAttributes['es_id'] =='4'){ 
                //$data->class = "fencingpermit";
                $fecingrowToUpdate = DB::table('eng_fencing_app')->where('ejr_id',$currentapp_id)->first();
                $fecirowAttributes = get_object_vars($fecingrowToUpdate);
                $fecipermitid =  $fecirowAttributes['id'];
                unset($fecirowAttributes['id']);
                //$fecirowAttributes['ejr_id'] = $l_ejrapplication_id;
                $checkappinremote = $remortServernew->table('eng_fencing_app')->where('frgn_efa_id',$fecipermitid)->first();
                if(!empty($checkappinremote)){
                  unset($fecirowAttributes['frgn_efa_id']);
                 $remortServernew->table('eng_fencing_app')->where('frgn_efa_id', $fecipermitid)->update($fecirowAttributes);
                }else{
                  $fecirowAttributes['frgn_efa_id'] = $fecipermitid;
                  $remortServernew->table('eng_fencing_app')->insert($fecirowAttributes);  
                }
            }else if($rowAttributes['es_id'] =='5'){ 
                //$data->class = "excavationpermit";
                $excavrowToUpdate = DB::table('eng_excavation_ground_app')->where('ejr_id',$currentapp_id)->first();
                $excarowAttributes = get_object_vars($excavrowToUpdate);
                $excavpermitid =  $excarowAttributes['id'];
                unset($excarowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_excavation_ground_app')->where('frgn_eega_id',$excavpermitid)->first();
                if(!empty($checkappinremote)){
                  unset($excarowAttributes['frgn_eega_id']);
                 $remortServernew->table('eng_excavation_ground_app')->where('frgn_eega_id', $excavpermitid)->update($excarowAttributes);
                }else{
                  $excarowAttributes['frgn_eega_id'] = $excavpermitid;
                  $remortServernew->table('eng_excavation_ground_app')->insert($excarowAttributes);  
                }
            }
            else if($rowAttributes['es_id'] =='6'){ 
                $electricrowToUpdate = DB::table('eng_electrical_app')->where('ejr_id',$currentapp_id)->first();
                $electricrowAttributes = get_object_vars($electricrowToUpdate);
                $electricpermitid =  $electricrowAttributes['id'];
                unset($electricrowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_electrical_app')->where('frgn_eea_id',$electricpermitid)->first();
                if(!empty($checkappinremote)){
                  unset($electricrowAttributes['frgn_eea_id']);
                 $remortServernew->table('eng_electrical_app')->where('frgn_eea_id', $electricpermitid)->update($electricrowAttributes);
                }else{
                  $electricrowAttributes['frgn_eea_id'] = $electricpermitid;
                  $remortServernew->table('eng_electrical_app')->insert($electricrowAttributes);  
                }
            }
            else if($rowAttributes['es_id'] =='8'){ 
                //$data->class = "signpermit";
                $signrowToUpdate = DB::table('eng_sign_app')->where('ejr_id',$currentapp_id)->first();
                $signrowAttributes = get_object_vars($signrowToUpdate);
                $signpermitid =  $signrowAttributes['id'];
                unset($signrowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_sign_app')->where('frgn_esa_id',$signpermitid)->first();
                if(!empty($checkappinremote)){
                  unset($signrowAttributes['frgn_esa_id']);
                 $remortServernew->table('eng_sign_app')->where('frgn_esa_id', $signpermitid)->update($signrowAttributes);
                }else{
                  $signrowAttributes['frgn_esa_id'] = $signpermitid;
                  $remortServernew->table('eng_sign_app')->insert($signrowAttributes);  
                }
            }
            else if($rowAttributes['es_id'] =='9'){ 
                //$data->class = "electronicpermit";
                $elerowToUpdate = DB::table('eng_electronics_app')->where('ejr_id',$currentapp_id)->first();
                $elerowAttributes = get_object_vars($elerowToUpdate);
                $elepermitid =  $elerowAttributes['id'];
                unset($elerowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_electronics_app')->where('frgn_eea_id',$elepermitid)->first();
                if(!empty($checkappinremote)){
                   unset($elerowAttributes['frgn_eea_id']);
                 $remortServernew->table('eng_electronics_app')->where('frgn_eea_id', $elepermitid)->update($elerowAttributes);
                }else{
                  $elerowAttributes['frgn_eea_id'] = $elepermitid;
                  $remortServernew->table('eng_electronics_app')->insert($elerowAttributes);  
                }
            }
            else if($rowAttributes['es_id']=='10'){ 
                //$data->class = "mechanicalpermit";
                $mechrowToUpdate = $remortServernew->table('eng_mechanical_app')->where('ejr_id',$currentapp_id)->first();
                $mechrowAttributes = get_object_vars($mechrowToUpdate);
                $mechpermitid =  $mechrowAttributes['id'];
                unset($mechrowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_mechanical_app')->where('frgn_ema_id',$mechpermitid)->first();
                if(!empty($checkappinremote)){
                   unset($mechrowAttributes['frgn_ema_id']);
                 $remortServernew->table('eng_mechanical_app')->where('frgn_ema_id', $mechpermitid)->update($mechrowAttributes);
                }else{
                  $mechrowAttributes['frgn_ema_id'] = $mechpermitid;
                  $remortServernew->table('eng_mechanical_app')->insert($mechrowAttributes);  
                }
            }
            else if($rowAttributes['es_id']=='11'){ 
                //$data->class = "civilpermit";
                $civilrowToUpdate = $remortServernew->table('eng_civil_app')->where('ejr_id',$currentapp_id)->first();
                $civilrowAttributes = get_object_vars($civilrowToUpdate);
                $civilpermitid =  $civilrowAttributes['id'];
                unset($civilrowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_civil_app')->where('frgn_eca_id',$civilpermitid)->first();
                if(!empty($checkappinremote)){
                   unset($civilrowAttributes['frgn_eca_id']);
                 $remortServernew->table('eng_civil_app')->where('frgn_eca_id', $civilpermitid)->update($civilrowAttributes);
                }else{
                  $civilrowAttributes['frgn_eca_id'] = $civilpermitid;
                  $remortServernew->table('eng_civil_app')->insert($civilrowAttributes);  
                }
            }
            else if($rowAttributes['es_id']=='13'){ 
                $data->class = "architecturalpermit";
                $artechrowToUpdate = $remortServernew->table('eng_architectural_app')->where('ejr_id',$currentapp_id)->first();
                $artechrowAttributes = get_object_vars($artechrowToUpdate);
                $artechpermitid =  $artechrowAttributes['id'];
                unset($artechrowAttributes['id']);
                $checkappinremote = $remortServernew->table('eng_architectural_app')->where('frgn_eaa_id',$artechpermitid)->first();
                if(!empty($checkappinremote)){
                  unset($artechrowAttributes['frgn_eaa_id']);
                 $remortServernew->table('eng_architectural_app')->where('frgn_eaa_id', $artechpermitid)->update($artechrowAttributes);
                }else{
                  $artechrowAttributes['frgn_eaa_id'] = $artechpermitid;
                  $remortServernew->table('eng_architectural_app')->insert($artechrowAttributes);  
                }
            }

            $eng_ejrrequirment = DB::table('eng_job_request_requirements')->where('ejr_id',$id)->get();

            foreach($eng_ejrrequirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $currentreq_id=$reqRowAttributes['id'];
                unset($reqRowAttributes['id']);
                $reqRowAttributes['ejr_id'] = $currentapp_id;
                $reqRowAttributes['frgn_ejrr_id'] = $currentreq_id;
                $checkreqinremote = $remortServernew->table('eng_job_request_requirements')->where('ejr_id',$currentapp_id)->where('req_id',$item->req_id)->first();
                if(!empty($checkreqinremote)){
                    // $remortServernew->table('cpdo_application_forms')->where('frgn_caf_id',$currentapp_id)->update($reqRowAttributes); 
                }else{
                   $remortServernew->table('eng_job_request_requirements')->insert($reqRowAttributes);  
                }
                DB::table('eng_job_request_requirements')->where('id',$item->id)->update(['is_synced' => 1]);
               
                $eng_file = DB::table('eng_files')->where('ejrr_id',$currentreq_id)->get();
                foreach($eng_file as $item)
                {
                    $engfileRowAttributes = get_object_vars($item);
                    unset($engfileRowAttributes['id']);
                   
                    $checkfileinremote = $remortServernew->table('eng_files')->where('ejrr_id',$currentreq_id)->first();
                    if(!empty($checkfileinremote)){
                            $updatearray = array();
                            $updatearray['fe_name'] = $item->fe_name;
                            $updatearray['fe_path'] = $item->fe_path;
                           unset($engfileRowAttributes['car_id']);
                            $remortServernew->table('eng_files')->where('ejrr_id',$currentreq_id)->update($updatearray); 
                    }else{
                         $destinationPath =  public_path().'/uploads/engineering/requirements/'.$itemfile->fe_name;
                            $fileContents = file_get_contents($destinationPath);
                            
                            $remotePath = 'public/uploads/engineering/requirements/'.$itemfile->fe_name;
                            $error = Storage::disk('remote')->put($remotePath, $fileContents);
                          $remortServernew->table('eng_files')->insert($engfileRowAttributes);  
                    }
                }
              }

          DB::table('eng_job_requests')->where('id',$id)->update(['is_synced' =>1]);
          DB::commit();
            return $currentapp_id;
        } catch (\Exception $e) {
          //echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback(); 
            // Handle the exception
        }    
    } 

    public function syncreqtoremote($id){
       $remortServernew = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $engineering_requirment = DB::table('eng_service_requirements')->where('es_id',$id)->get();
            foreach($engineering_requirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                unset($reqRowAttributes['id']);
                 $checkreqinremote = $remortServernew->table('eng_service_requirements')->where('es_id',$id)->where('req_id',$item->req_id)->first();
                if(!empty($checkreqinremote)){
                     $remortServernew->table('eng_service_requirements')->where('id',$checkreqinremote->id)->update($reqRowAttributes); 
                }else{
                   $remortServernew->table('eng_service_requirements')->insert($reqRowAttributes);  
                }
               
            }
            DB::commit();
            return $id;
        } catch (\Exception $e) {
             echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 
   
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $barangay=$request->input('barangay');
    $fromdate=$request->input('fromdate');
    $todate=$request->input('todate');
    $status=$request->input('status');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"ejr.id",
      1 =>"ejr_jobrequest_no",
      2 =>"cl.full_name",
      3 =>"eat.eat_module_desc",
      4 =>"ejr.created_at",
      5 =>"ejr.application_no",
      9 =>"ejr.is_active"
    );

    $sql = $this->remortServer->table('eng_job_requests AS ejr')
          ->join('clients AS cl', 'ejr.client_id', '=', 'cl.client_frgn_id')
          ->join('eng_services AS es', 'ejr.es_id', '=', 'es.id')
          ->join('eng_application_type AS eat', 'eat.id', '=', 'es.eat_id')
          ->select('ejr.id','ejr.es_id','ejr.top_transaction_type_id','ejr.ejr_jobrequest_no','ejr.location_brgy_id','ejr.client_id','eat.eat_module_desc','cl.full_name','cl.rpo_custom_last_name','cl.rpo_first_name','ejr.application_no','cl.rpo_middle_name','ejr.ejr_totalfees','ejr.is_active','ejr.is_approved',DB::raw('DATE(ejr.created_at) AS created_at'))->where('ejr.is_approved','<>',1);

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ejr.ejr_jobrequest_no)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
		   ->orWhere(DB::raw('LOWER(cl.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(cl.rpo_middle_name)'),'like',"%".strtolower($q)."%")
           ->orWhere(DB::raw('LOWER(eat.eat_module_desc)'),'like',"%".strtolower($q)."%");
			});
		}
     if(!empty($barangay)){
          $sql->where('cl.p_barangay_id_no',$barangay);
      }
      if(!empty($fromdate) && isset($fromdate)){
          $sql->whereDate('ejr.created_at','>=',trim($fromdate));  
      }
      if(!empty($todate) && isset($todate)){
          $sql->whereDate('ejr.created_at','<=',trim($todate));  
      }
  		if(!empty($status) && isset($status)){
  			$sql->where('ejr.is_approved',$status);
  		}else{
  			$sql->where('ejr.is_approved',0);
  		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('ejr.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
