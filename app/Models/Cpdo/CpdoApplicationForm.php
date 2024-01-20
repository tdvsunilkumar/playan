<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CpdoApplicationForm extends Model
{
	 public function getOwners(){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_engg',1)->where('rpo_first_name','<>',NULL)->get();
    }
     public function getServices(){
    	return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('cpdo_services as cs','ctot.id','=','cs.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','cs.top_transaction_type_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','cttt.ttt_desc as description')->where('tfoc_status',1)->get();
    }

    public function getServicesbyid($id){
      return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('cpdo_services as cs','ctot.id','=','cs.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','cs.top_transaction_type_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','cttt.ttt_desc as description')->where('ctot.id',$id)->where('tfoc_status',1)->get();
    }
    public function updateData($id,$columns){
        return DB::table('cpdo_application_forms')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cpdo_application_forms')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function GetOrderdata($id){
        return DB::table('cto_top_transactions as ctt')->leftjoin('cpdo_application_forms AS caf', 'caf.id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('ctt.transaction_ref_no','ctt.is_printed','ctt.id','ctt.transaction_no','c.full_name','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.client_telephone','caf.caf_amount','caf.caf_total_amount','caf.penaltyamount','ctt.created_at')->where('ctt.id',$id)->first();
    }
    public function GetInspectiondata($id){
        return DB::table('cpdo_inspection_reports as cir')->leftjoin('cpdo_application_forms AS caf', 'caf.id', '=', 'cir.caf_id')->leftjoin('cto_top_transactions AS ctt', 'cir.caf_id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('cir.*','c.full_name','ctt.transaction_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.client_telephone','caf.caf_amount','caf.caf_type_project','caf.caf_brgy_id','ctt.created_at','caf.id as cafid','caf.top_transaction_type_id')->where('cir.id',$id)->first();
    }

    public function getCertificateData($id){
       return DB::table('cpdo_certificate as cc')->leftjoin('cpdo_application_forms AS caf', 'caf.id', '=', 'cc.caf_id')->leftjoin('cto_top_transactions AS ctt', 'cc.caf_id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('cc.*','ctt.transaction_no','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.client_telephone','caf.caf_control_no','caf.caf_type_project','caf.caf_brgy_id','caf.caf_amount','ctt.created_at','caf.id as cafid','caf.top_transaction_type_id')->where('cc.id',$id)->first();
    }

    public function getProfileDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_mobile_no as p_telephone_no','id as clientid','rpo_address_house_lot_no','p_email_address','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no')->where('id',(int)$id)->first();
    }

    public function Getclientbyid($id){
      return DB::table('clients')->select('full_name','rpo_first_name','rpo_custom_last_name','rpo_middle_name')->where('id',$id)->first();
    }

    public function GetapplicationRecord($id){
         return DB::table('cpdo_application_forms as caf')->leftjoin('cto_top_transactions as ctt','caf.id', '=', 'ctt.transaction_ref_no')->select('caf.*','ctt.transaction_no')->where('caf.id',$id)->first();
    }
    public function CertificateupdateData($id,$columns){
        return DB::table('cpdo_certificate')->where('id',$id)->update($columns);
    }

    public function CertificateupdatebyCafid($id,$columns){
        return DB::table('cpdo_certificate')->where('caf_id',$id)->update($columns);
    }
    public function CertificateaddData($postdata){
        DB::table('cpdo_certificate')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function findCertificateDataById($id){
        return DB::table('cpdo_certificate as cc')->leftjoin('cpdo_application_forms AS caf', 'caf.id', '=', 'cc.caf_id')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('cc.*','c.full_name','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.client_telephone','caf.caf_amount','caf.caf_total_amount','caf.penaltyamount')->where('cc.id',$id)->first();
    }
    
    public function InspectionupdateData($id,$columns){
        return DB::table('cpdo_inspection_reports')->where('id',$id)->update($columns);
    }
    public function InspectionaddData($postdata){
        DB::table('cpdo_inspection_reports')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function GetGeoLocationbyid($id){
       return DB::table('cpdo_inspection_geotagging')->where('cir_id',$id)->get()->toArray();
    }
    public function GetPenaltiesmaster(){
       return DB::table('cpdo_penalties')->select('id','name','percentage')->get()->toArray();
    }
    public function Getpenaltypercen($id){
        return DB::table('cpdo_penalties')->select('id','name','percentage')->where('id',$id)->first();
    }
    public function AddGeoLocationData($postdata){
        DB::table('cpdo_inspection_geotagging')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateGeoLocationData($id,$columns){
        return DB::table('cpdo_inspection_geotagging')->where('id',$id)->update($columns);
    }
    public function checkGeoLocationexist($id,$link){
        return DB::table('cpdo_inspection_geotagging')->where('cir_id',$id)->where('cig_location_description',$link)->get();
    }
    public function TransactionupdateData($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function updateremotedata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_application_forms')->where('frgn_caf_id',$id)->update($columns);
    }
    public function checkTransactionexist($id){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id','19')->get();
    }
    public function getORandORdate($id){
      return DB::table('cto_cashier')->select('or_no','total_amount',DB::raw('DATE(created_at) AS created_at'))->where('top_transaction_id',$id)->get()->toArray();
    }
    public function TransactionaddData($postdata){
        DB::table('cto_top_transactions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
       return DB::table('cpdo_inspection_reports')->select('cir_upload_documents_json')->where('caf_id',$id)->first();
    }
    public function isexistinspection($id){
    	 return DB::table('cpdo_inspection_reports')->where('caf_id',$id)->get();
    }
    public function getapproveluserid($id){
      return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first();
    }
    public function isexistcertificate($id){
      return DB::table('cpdo_certificate')->where('caf_id',$id)->get();
    }
    public function getcertificateforedit($id){
      return DB::table('cpdo_certificate')->where('caf_id',$id)->first();
    }
    public function getinspectionorderforedit($id){
    	return DB::table('cpdo_inspection_reports')->where('caf_id',$id)->first();
    }
    public function getclientidbyid($id){
        return DB::table('cpdo_application_forms')->select('client_id')->where('id',$id)->first();
    }
    public function insertbillsummary($postdata){
      DB::table('planning_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function insertbillsummaryremote($postdata){
      $remortServer = DB::connection('remort_server');
      $remortServer->table('planning_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function gethremployess(){ 
      return DB::table('hr_employees')->select('id','fullname','suffix')->where('is_active',1)->get();
    } 

    public function getBillDetails($transaction_no,$id){
        return DB::table('cto_top_transactions AS tt')
        ->select('tt.amount','tt.attachment','tt.transaction_no')
        ->where('tt.id',$transaction_no)->orderBy('tt.id', 'DESC')->first();
    }

    public function getpositionbyid($id){
      return DB::table('hr_employees as he')->leftjoin('hr_designations as hd','he.hr_designation_id','=','hd.id')->select('he.id','hd.description','he.fullname')->where('he.id',$id)->first();
    }

    public function getRequirements(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_cpdo',1)->get();
    }

    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }

    public function appRequirementupdateData($id,$columns){
        return DB::table('cpdo_application_requirement')->where('id',$id)->update($columns);
    }
    public function checkappRequiremenexist($id,$reqid){
        return DB::table('cpdo_application_requirement')->where('caf_id',$id)->where('req_id',$reqid)->get();
    }
    public function appRequirementaddData($postdata){
        DB::table('cpdo_application_requirement')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    } 

    public function AddappFilesData($postdata){
        DB::table('cpdo_file')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkRequirementfileexist($id){
       return DB::table('cpdo_file')->where('car_id',$id)->get()->toArray();
    }

    public function UpdateappFilesData($id,$columns){
        return DB::table('cpdo_file')->where('id',$id)->update($columns);
    }

    public function updateinspectionData($id,$columns){
       return DB::table('cpdo_inspection_reports')->where('caf_id',$id)->update($columns);
    }

    public function getAppRequirementsData($id){
           return DB::table('cpdo_application_requirement AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_file AS cf', 'cf.car_id', '=', 'cpr.id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cf_name','cf.cf_path')->where('cpr.caf_id',$id)->groupby('cpr.id')->get()->toArray();
    }
    public function getRequirementsbyid($id){
         return DB::table('cpdo_application_requirement AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_file AS cf', 'cf.car_id', '=', 'cpr.id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cf_name','cf.cf_path')->where('cpr.id',$id)->groupby('cpr.id')->get()->toArray();
    }
    public function deleteRequirementsbyid($id){
       return DB::table('cpdo_application_requirement')->where('id',$id)->delete();
    }

    public function deleteimagerowbyid($id){
      return DB::table('cpdo_file')->where('id',$id)->delete();
    }

    public function getSercviceRequirements($id){
             return DB::table('cpdo_service_requirements AS csr')
          ->join('requirements AS re', 'csr.req_id', '=', 're.id')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('csr.tfoc_id',$id)->orderby('orderno','ASC')->Groupby('re.id')->get();; 
    } 

    public function GetcpdolatestApp(){
       return DB::table('cpdo_application_forms')->orderby('id','DESC')->first();
    }

    public function getServiceTypearray($type){
        return DB::table('cpdo_module')->where('cm_type',$type)->orderby('id','ASC')->get();
    }

    public function getServiceTypearraydefault(){
        return DB::table('cpdo_module')->where('cm_type','1')->orderby('id','ASC')->get();
    }

    public function getCasheringIds($id){
       return DB::table('cto_tfocs')->select('gl_account_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id','sl_id')->where('id',$id)->first();
    }

    public function getcleranceid($id){
       return DB::table('cpdo_zoning_computation_clearance')->select('id')->where('cm_id',$id)->orderby('id','ASC')->first();
    }

    public function getclerancelinedata($id,$billamount){ 
        return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over','>=',$billamount)->where('czccl_over_by_amount','=','0')->where('czcc_id',$id)->first();
    }
    public function hetoverbyAmount($id,$billamount){
      return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over_by_amount','=','1')->where('czcc_id',$id)->first();
    }

    public function getCpdomunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','6')->limit(1)->first();
    }

     public function getClientsNameAjax($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('clients')
             ->where('is_engg',1)
             ->where('is_active',1)
             ->select('full_name','id');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(full_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('full_name','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getBarangay($search="",$munid){
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
        ->select('bgf.id','brgy_name')->where('bgf.is_active',1);
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

    public function getEmployees($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('hr_employees')->select('id','fullname')
       ->where('is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
            $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(firstname)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(middlename)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(lastname)'),'like',"%".strtolower($search)."%");
          
        });
      }
     
      $sql->orderBy('fullname','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
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
      0 =>"es.id",
      1 =>"caf.caf_control_no",
      2 =>"c.rpo_first_name",
      3 =>"caf.caf_type_project",
      4 =>"caf.caf_brgy_id"	
    );

    $sql = DB::table('cpdo_application_forms AS caf')
   		  ->join('clients AS c', 'c.id', '=', 'caf.client_id') 
        ->leftjoin('cpdo_inspection_reports AS cir', 'caf.id', '=', 'cir.caf_id')
        ->select('cir.id as cirid','caf.id','caf_date','caf.caf_control_no','caf.is_online','caf.caf_type_project','caf.top_transaction_type_id','caf.caf_brgy_id','c.suffix','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.csd_id','caf.cs_id','caf.cna_id','caf.caf_amount','caf.caf_total_amount','caf.penaltyamount','caf.is_active');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(c.suffix)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(caf.caf_brgy_id)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(caf.caf_type_project)'),'like',"%".strtolower($q)."%");
			});
		}
       if(!empty($barangay)){
            $sql->where('caf.caf_brgy_id',$barangay);
        }
        if(!empty($fromdate) && isset($fromdate)){
            $sql->whereDate('caf_date','>=',trim($fromdate));  
        }
        if(!empty($todate) && isset($todate)){
            $sql->whereDate('caf_date','<=',trim($todate));  
        }
        if(!empty($status)){
            $sql->where('caf.csd_id',$status);
        }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  public function getListonline($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"es.id",
      1 =>"caf.caf_control_no",
      2 =>"c.full_name",
      3 =>"caf.caf_type_project",
      4 =>"caf.caf_brgy_id"  
    );

    $sql = DB::table('cpdo_application_forms AS caf')
        ->join('clients AS c', 'c.id', '=', 'caf.client_id') 
        ->leftjoin('cpdo_inspection_reports AS cir', 'caf.id', '=', 'cir.caf_id')
          ->select('cir.id as cirid','caf.id','caf.caf_control_no','caf.caf_type_project','caf.top_transaction_type_id','caf.caf_brgy_id','c.suffix','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.csd_id','caf.cs_id','caf.caf_amount','caf.caf_total_amount','caf.is_active')->where('is_online',1);

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
    if(!empty($q) && isset($q)){
      $sql->where(function ($sql) use($q) {
        $sql->where(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
		 ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
		  ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
		   ->orWhere(DB::raw('LOWER(c.suffix)'),'like',"%".strtolower($q)."%")
		  ->orWhere(DB::raw('LOWER(caf.caf_brgy_id)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(caf.caf_type_project)'),'like',"%".strtolower($q)."%");
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
}
