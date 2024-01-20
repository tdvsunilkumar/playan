<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use App\Models\Barangay;

class EngJobRequest extends Model
{
  public $table = 'eng_job_requests';

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
        
      default://
        return $this->hasOne(EngJobReqBuilding::class, 'ejr_id', 'id');
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
    
    public function getServicefees(){
    	return DB::table('cto_tfocs')->select('id','tfoc_short_name')->where('tfoc_status',1)->get();
    }
    public function getOwners(){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',1)->where('is_active',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function getRptOwners(){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',1)->where('is_active',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function getEngOwnersAjax($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',1)->where('is_active',1)->where('rpo_first_name','<>',NULL);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(full_name)'),'like',"%".strtolower($search)."%");;
          }
        });
      }
      $sql->orderBy('clients.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getbildOfficialAjax($search=""){
       $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('eng_engineeringstaffs as ees')->leftjoin('hr_employees as hr','ees.ees_employee_id','=','hr.id')->select('hr.id','hr.fullname')->where('ees.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($search)."%");;
          }
        });
      }
      $sql->orderBy('ees.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getextrafees(){
        return DB::table('eng_services AS es')
        ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'es.tfoc_id') 
        ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id')->select('asl.description','es.tfoc_id')->where('es.is_main_service','=','0')->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_description')->where('is_active',1)->get();
    }
    public function updateData($id,$columns){
        return DB::table('eng_job_requests')->where('id',$id)->update($columns);
    }
    public function getClientidJobrequest($id){
        return DB::table('eng_job_requests')->select('client_id','location_brgy_id','brgy_code')->where('id',$id)->get();
    }
    public function addData($postdata){
        DB::table('eng_job_requests')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getsmsinfobyid($id){
         return DB::table('eng_job_requests as ejr')->leftjoin('clients as c','c.id','=','ejr.client_id')->select('c.p_mobile_no','c.full_name','ejr.ejr_jobrequest_no')->where('ejr.id',$id)->first();
    }

    public function deleteFeedetailsrow($id){
        return DB::table('eng_job_request_fees_details')->where('id',$id)->delete();
    }
    public function GetDefaultfees(){
        return DB::table('eng_job_request_default_fees')->select('fees_description')->where('status',1)->orderby('id','ASC')->get();
    }

    public function GetReqiestfees($id){
        return DB::table('eng_job_request_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('ejr_id',$id)->orderby('id','ASC')->get();
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

    public function checkTransactionexistbyid($id){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('tfoc_is_applicable','3')->get();
    }
    
    public function updateremotedata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('eng_job_requests')->where('frgn_ejr_id',$id)->update($columns);
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

    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }

    public function getEngmunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','7')->limit(1)->first();
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

    public function getbuidingofficial(){
      //return DB::table('hr_employees as hr')->leftjoin('users_role as ur','hr.user_id','=','ur.user_id')->select('hr.id','hr.fullname')->where('ur.role_id','=',18)->where('hr.is_active',1)->get();
      return DB::table('eng_engineeringstaffs as ees')->leftjoin('hr_employees as hr','ees.ees_employee_id','=','hr.id')->select('hr.id','hr.fullname')->where('ees.is_active',1)->get();
    }
    public function getEmployeeDetails($id){
       return DB::table('hr_employees')->select('fullname','emp_prc_no','emp_ptr_no','emp_issue_date','emp_issue_at','emp_prc_validity','tin_no','c_house_lot_no','c_street_name','c_subdivision','c_brgy_code','barangay_id','c_region')->where('id',$id)->get()->first();
    }

    public function getExternalDetails($id){
       return DB::table('consultants')->select('fullname','prc_no','ptr_no','ptr_date_issued','prc_validity','tin_no','house_lot_no','street_name','subdivision','brgy_code','country')->where('id',$id)->get()->first();
    }

    public function getExteranls(){ 
      return DB::table('consultants')->select('id','fullname')->get();
    } 
    public function getExteranlsAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('consultants')->select('id','fullname');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($search)."%");;
          }
        });
      }
      $sql->orderBy('consultants.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getEditDetailsbldApp($id){
         return DB::table('eng_bldg_permit_apps')->select('*',DB::raw('DATE(ebpa_application_date) AS ebpa_application_date'),DB::raw('DATE(ebpa_issued_date) AS ebpa_issued_date'))->where('ejr_id',$id)->get()->first();
    }

    public function getclientname($id){
      return DB::table('clients')->where('id',$id)->select('rpo_first_name','rpo_middle_name','rpo_custom_last_name','suffix')->first();
    }

    public function getEditDetailsbldAppforprint($id){
         return DB::table('eng_bldg_permit_apps as ebp')->leftjoin('profile_municipalities AS mun', 'ebp.ebpa_mun_no', '=', 'mun.id')->select('mun.mun_desc','ebp.*',DB::raw('DATE(ebpa_application_date) AS ebpa_application_date'),DB::raw('DATE(ebpa_issued_date) AS ebpa_issued_date'))->where('ejr_id',$id)->get()->first();
    }

    public function Getbidappid($id){
      return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->where('ejr_id',$id)->get()->first();
    }

    public function getEmployeesDetails($id){
       return DB::table('hr_employees')->select('*')->where('id',$id)->get()->first();
    }
    public function getExteranlsDetails($id){
       return DB::table('consultants')->select('*')->where('id',$id)->get()->first();
    }
    
    public function getEditDetailsSanitaryApp($id){
         return DB::table('eng_sanitary_plumbing_apps as esp')->leftjoin('clients AS c', 'esp.p_code', '=', 'c.id')->select('esp.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('esp.ejr_id',$id)->get()->first();
    }
    public function getpermitno($id){
        return DB::table('eng_bldg_permit_apps')->select('ebpa_permit_no')->where('id',$id)->get()->first();
    }
    public function getEditDetailsSanitaryforprint($id){
         return DB::table('eng_sanitary_plumbing_apps as esp')->leftjoin('profile_municipalities AS mun', 'esp.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'esp.p_code', '=', 'c.id')->select('mun.mun_desc','esp.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision',DB::raw('DATE(espa_application_date) AS espa_application_date'),DB::raw('DATE(espa_issued_date) AS espa_issued_date'))->where('ejr_id',$id)->get()->first();
    }

     public function getEditDetailsDemolitionforprint($id){
         return DB::table('eng_demolition_app as esp')->leftjoin('profile_municipalities AS mun', 'esp.mun_no', '=', 'mun.id')->leftjoin('clients AS c', 'esp.p_code', '=', 'c.id')->select('mun.mun_desc','esp.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_telephone_no','c.p_barangay_id_no')->where('ejr_id',$id)->get()->first();
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
          return DB::table('eng_electrical_app as eea')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('eea.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eea.ejr_id',$id)->get()->first();
    }

    public function getEditDetailsElectricalforprint($id){
         return DB::table('eng_electrical_app as eea')->leftjoin('profile_municipalities AS mun', 'eea.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('mun.mun_desc','eea.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_barangay_id_no','c.p_telephone_no','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.rpo_address_street_name',DB::raw('DATE(eea_application_date) AS eea_application_date'),DB::raw('DATE(eea_issued_date) AS eea_issued_date'))->where('ejr_id',$id)->get()->first();
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
          return DB::table('eng_civil_app as eca')->leftjoin('clients AS c', 'eca.p_code', '=', 'c.id')->select('eca.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eca.ejr_id',$id)->get()->first();
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
          return DB::table('eng_fencing_app as efa')->leftjoin('clients AS c', 'efa.p_code', '=', 'c.id')->select('efa.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('efa.ejr_id',$id)->get()->first();
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
          return DB::table('eng_sign_app as esa')->leftjoin('clients AS c', 'esa.p_code', '=', 'c.id')->select('esa.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('esa.ejr_id',$id)->get()->first();
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
         return DB::table('eng_demolition_app as eda')->leftjoin('clients AS c', 'eda.p_code', '=', 'c.id')->select('eda.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eda.ejr_id',$id)->get()->first();
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
          return DB::table('eng_architectural_app as era')->leftjoin('clients AS c', 'era.p_code', '=', 'c.id')->select('era.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('era.ejr_id',$id)->get()->first();
    }
    public function getEditDetailsArchitectureforprint($id){
         return DB::table('eng_architectural_app as eaa')->leftjoin('profile_municipalities AS mun', 'eaa.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'eaa.p_code', '=', 'c.id')->select('mun.mun_desc','eaa.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_barangay_id_no','c.p_telephone_no','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.rpo_address_street_name')->where('ejr_id',$id)->get()->first();
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
         return DB::table('eng_electronics_app as eea')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('eea.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('eea.ejr_id',$id)->get()->first();
    }

    public function getEditDetailsElectronicsforprint($id){
         return DB::table('eng_electronics_app as eea')->leftjoin('profile_municipalities AS mun', 'eea.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'eea.p_code', '=', 'c.id')->select('mun.mun_desc','eea.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_barangay_id_no','c.p_telephone_no','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.rpo_address_street_name')->where('ejr_id',$id)->get()->first();
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
          return DB::table('eng_mechanical_app as ema')->leftjoin('clients AS c', 'ema.p_code', '=', 'c.id')->select('ema.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('ema.ejr_id',$id)->get()->first();
    }

    public function getEditDetailsMechanicalforprint($id){
         return DB::table('eng_mechanical_app as ema')->leftjoin('profile_municipalities AS mun', 'ema.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'ema.p_code', '=', 'c.id')->select('mun.mun_desc','ema.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_telephone_no')->where('ejr_id',$id)->get()->first();
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
          return DB::table('eng_excavation_ground_app as exga')->leftjoin('clients AS c', 'exga.p_code', '=', 'c.id')->select('exga.*','c.rpo_first_name','c.rpo_middle_name','c.suffix','c.rpo_custom_last_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision')->where('exga.ejr_id',$id)->get()->first();
    }
    public function getBarangaybyid($id){
      return DB::table('barangays')->select('brgy_name')->where('id',$id)->get()->first();
    }
    public function getEditDetailsExcavationforprint($id){
         return DB::table('eng_excavation_ground_app as exg')->leftjoin('profile_municipalities AS mun', 'exg.mum_no', '=', 'mun.id')->leftjoin('clients AS c', 'exg.p_code', '=', 'c.id')->select('mun.mun_desc','exg.*','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.brgy_code','c.p_barangay_id_no','c.p_telephone_no','c.rpo_address_house_lot_no','c.rpo_address_subdivision','c.rpo_address_street_name')->where('ejr_id',$id)->get()->first();
    }
    public function GetBuildingpermits($id){
      return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->where('p_code',$id)->where('ebpa_permit_no','<>','')->get();
    }
    public function GetBuildingpermitsdemolition($id){
      return DB::table('eng_bldg_permit_apps as a')->join('eng_job_requests as ejr','ejr.id','=','a.ejr_id')->select('a.id','a.ebpa_permit_no')->where('a.p_code',$id)->where('ejr.is_active',1)->where('a.ebpa_permit_no','<>','')->orderby('a.ebpa_permit_no','ASC')->get();
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
       return DB::table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id')->where('id',$id)->first();
    }

    public function getJobRequirementsData($id){
           return DB::table('eng_job_request_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('eng_files AS fe', 'fe.ejrr_id', '=', 'esr.id')
          ->select('fe.id as feid','esr.id','esr.req_id','re.req_code_abbreviation','re.req_description','fe.fe_name','fe.fe_path')->where('esr.ejr_id',$id)->groupby('esr.id')->orderby('esr.id','ASC')->get()->toArray();
    } 
    public function getRequirementsbyid($id){
          return DB::table('eng_job_request_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('eng_files AS fe', 'fe.ejrr_id', '=', 'esr.id')
          ->select('fe.id as feid','esr.id','esr.req_id','re.req_code_abbreviation','re.req_description','fe.fe_name','fe.fe_path')->where('esr.id',$id)->groupby('esr.id')->orderby('esr.id','ASC')->get()->toArray();
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

    public function getbuildingappid($id){
      return DB::table('eng_bldg_permit_apps')->select('id')->where('ejr_id',$id)->first();
    }

    public function getTaxcertificatedetails($id){
             return DB::table('cto_cashier as cc')->leftjoin('clients as c','c.id','=','cc.client_citizen_id')->select('c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','cc.cashier_batch_no','cc.or_no','cc.cashier_batch_no','c.p_barangay_id_no','cc.ctc_place_of_issuance',DB::raw('DATE(cc.created_at) AS created_at'))->where('cc.payee_type','1')->where('cc.client_citizen_id',$id)->orderby('cc.id','DESC')->first();
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

     public function getservicebyid($id){
        return DB::table('eng_services')->where('id',$id)->first();
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
        return DB::table('eng_building_permit_fees_set1')->select('ebpfs1_fees','ebpfs1_id')->where('ebpfs1_range_from','<=',$area)->where('ebpfs1_range_to','>=',$area)->first();
    }
    
    public function getdataset2($area){
        return DB::table('eng_building_permit_fees_set2')->select('ebpfs2_fees','ebpfs2_id')->where('ebpfs2_range_from','<=',$area)->where('ebpfs2_range_to','>=',$area)->first();
    }

    public function getdataset3($area){
        return DB::table('eng_building_permit_fees_set3')->select('ebpfs3_fees','ebpfs3_id')->where('ebpfs3_range_from','<=',$area)->where('ebpfs3_range_to','>=',$area)->first();
    }
    public function getdataset3data($id){
        return DB::table('eng_building_permit_fees_set3')->select('*')->where('ebpfs3_id','<=',$id)->get();
    }
    public function getdataset4($area){
        return DB::table('eng_building_permit_fees_set4')->select('ebpfs4_fees','ebpfs4_id')->where('ebpfs4_range_from','<=',$area)->where('ebpfs4_range_to','>=',$area)->first();
    }

    public function getdataset4data($id){
        return DB::table('eng_building_permit_fees_set4')->select('*')->where('ebpfs4_id','<=',$id)->get();
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
      return DB::table('eng_bldg_scopes')->select('id','ebs_description')->where('ebs_is_building',1)->where('ebs_is_active',1)->get()->toArray();
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
        return DB::table('eng_sign_installation_types')->select('id','esit_description')->where('esit_is_active',1)->get()->toArray();
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
    public function getPermitnoDetails($id){
      return DB::table('eng_bldg_permit_apps as a')->join('eng_job_requests as ejr','a.ejr_id','=','ejr.id')->leftjoin('clients as c','ejr.client_id','=','c.id')->select('c.full_name','ejr.location_brgy_id','ejr.rpo_address_house_lot_no','ejr.rpo_address_street_name','ejr.rpo_address_subdivision','c.p_barangay_id_no','ejr.ejr_jobrequest_no')->where('a.id',$id)->first();
    }
    public function wgetCpdomunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','7')->limit(1)->first();
    }

    public function getclientidbyid($id){
        return DB::table('eng_job_requests')->select('client_id')->where('id',$id)->first();
    }
    public function subtypeoccupancy($id){
      return DB::table('eng_bldg_occupancy_sub_types')
              ->select('id','ebost_description')->where('ebost_id',(int)$id)->get();
    }
    public function getapptypeUsingtfocid($id){
        return DB::table('eng_services AS ees')
           ->join('eng_application_type AS eat', 'eat.id', '=', 'ees.eat_id')
          ->select('eat.eat_module_desc','ees.tfoc_id as tfoc_id','is_flcno_required')->where('ees.id',$id)->get();;
    }
    public function getzoningdetails($id){
         return DB::table('cpdo_certificate AS cc')
           ->leftjoin('cpdo_application_forms AS caf', 'cc.caf_id', '=', 'caf.id')
           ->leftjoin('barangays as b','caf.caf_brgy_id','=','b.id')
           ->leftjoin('clients as c','caf.client_id','=','c.id')
           ->select('cc.cc_date','cc.cc_name_project','b.brgy_name','c.full_name')->where('cc.id',$id)->first();;
    }

    public function insertbillsummary($postdata){
      DB::table('engineering_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function insertbillsummaryremote($postdata){
      $remortServer = DB::connection('remort_server');
      $remortServer->table('engineering_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

     public function getBillDetails($transaction_no,$id){
        return DB::table('cto_top_transactions AS tt')
        ->leftjoin('eng_job_requests as ejr','ejr.id','=','tt.transaction_ref_no')
        ->select('tt.amount','tt.attachment','ejr.client_id','tt.transaction_no')
        ->where('tt.id',$transaction_no)->orderBy('tt.id', 'DESC')->first();
    }

    public function getBarangay(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
     public function getSercviceRequirementsall(){
             return DB::table('eng_service_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('req_dept_eng',1)->Groupby('re.id')->get();; 
    }

    public function getSercviceRequirements($id){
             return DB::table('eng_service_requirements AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('esr.tfoc_id',$id)->Groupby('re.id')->orderby('orderno','ASC')->get();; 
    }

    public function GetMuncipalities(){
      return DB::table('profile_municipalities')->select('id','mun_desc')->get()->toArray();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_job_requests')->where('id',$id)->update($columns);
    }

    public function getfalcnumbers(){
       return DB::table('cpdo_certificate as a')->join('cpdo_application_forms as b','a.caf_id','=','b.id')->select('a.id','a.cc_falc_no')
            ->distinct()
            ->get();
    }

    public function getFalcnobycleint($cleintid,$isrefrence){
         $sql = DB::table('cpdo_certificate as a')->join('cpdo_application_forms as b','a.caf_id','=','b.id')->select('a.id','a.cc_falc_no')
            ->distinct();
            if($isrefrence > 0){
              $sql->where('b.client_id',$cleintid); 
            }
            return $data = $sql->get();
    }

    public function getFalcnobyAjax($search="",$cleintid,$isrefrence){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cpdo_certificate as a')->join('cpdo_application_forms as b','a.caf_id','=','b.id')
       ->select('a.id','a.cc_falc_no')
            ->distinct();
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if($isrefrence > 0){
           $sql->where('b.client_id',$cleintid); 
          }
            $sql->where(DB::raw('LOWER(a.cc_falc_no)'),'like',"%".strtolower($search)."%");
         
        });
      }
      $sql->orderBy('a.id','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

     public function getpermitnoAjax($search="",$cleintid){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('eng_bldg_permit_apps as a')->join('eng_job_requests as ejr','ejr.id','=','a.ejr_id')
       ->select('a.id','a.ebpa_permit_no');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
            $sql->where(DB::raw('LOWER(a.ebpa_permit_no)'),'like',"%".strtolower($search)."%");
         
        });
      }
      $sql->where('a.p_code',$cleintid)->where('ejr.is_active',1)->where('a.ebpa_permit_no','<>',''); 
      $sql->orderBy('a.ebpa_permit_no','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    
    public function getbarangaybyfalcno($certno){
       $sql = DB::table('cpdo_certificate as a')->join('cpdo_application_forms as b','a.caf_id','=','b.id') ->leftjoin('barangays as c','b.caf_brgy_id','=','c.id')->select('c.id','c.brgy_name');
           $sql->where('b.is_active',1)->where('cc_approved','>=','1')->where('cc_approval_status','1')->where('a.id',$certno);
            return $data = $sql->get();
    }

    public function getBarangayforajax($search="",$munid){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('barangays AS bgf')
        ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
        ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
        ->select('bgf.id','brgy_name','mun_desc','prov_desc','reg_region')->where('bgf.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('bgf.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      if(!empty($munid)){
        $sql->Where('bgf.mun_no',$munid);
      }
      $sql->orderBy('brgy_name','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
   
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $client_id=$request->input('client_id');
    $barangay=$request->input('barangay');
    $service=$request->input('service');
    $method = $request->input('method');
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

    $sql = DB::table('eng_job_requests AS ejr')
          ->join('clients AS cl', 'ejr.client_id', '=', 'cl.id')
          ->join('eng_services AS es', 'ejr.es_id', '=', 'es.id')
          ->join('eng_application_type AS eat', 'eat.id', '=', 'es.eat_id')
          ->select('ejr.id','ejr.es_id','ejr.is_online','ejr.is_approve','ejr.location_brgy_id','ejr.top_transaction_type_id','ejr.ejr_jobrequest_no','ejr.client_id','eat.eat_module_desc','cl.full_name','cl.rpo_custom_last_name','cl.rpo_first_name','ejr.application_no','cl.rpo_middle_name','ejr.ejr_totalfees','ejr.is_active',DB::raw('DATE(ejr.created_at) AS created_at'));

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
    if(!empty($client_id) && isset($client_id)){
        $sql->where('ejr.client_id',$client_id);
    }
    if(!empty($barangay)){
        $sql->where('ejr.location_brgy_id',$barangay);
    }
    if(!empty($fromdate) && isset($fromdate)){
        $sql->whereDate('ejr.created_at','>=',trim($fromdate));  
    }
    if(!empty($todate) && isset($todate)){
        $sql->whereDate('ejr.created_at','<=',trim($todate));  
    }
    if(!empty($service) && isset($service)){
        $sql->where('ejr.es_id',$service);  
    }
    if(isset($method)){
        $sql->where('ejr.is_online',$method);  
    }
    if(!empty($status) && isset($status)){
      $sql->where('ejr.is_active',$status);
    }
    else{if($status != ''){
      $sql->where('ejr.is_active',$status);
    }}

		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('ejr.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  
}
