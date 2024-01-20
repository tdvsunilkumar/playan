<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;

class CpdoDevelopmentPermit extends Model
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
        return DB::table('cpdo_development_permits')->where('id',$id)->update($columns);
    }
    public function findDataById($id){
      return DB::table('cpdo_development_permits as cdp')->join('clients AS c', 'c.id', '=', 'cdp.client_id')->select('cdp.*','c.full_name','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name')->where('cdp.id',$id)->first();
  }
    public function addData($postdata){
        DB::table('cpdo_development_permits')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function GetOrderdata($id){
        return DB::table('cto_top_transactions as ctt')->leftjoin('cpdo_development_permits AS caf', 'caf.id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('ctt.id','ctt.transaction_ref_no','ctt.transaction_no','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.cdp_phone_no','caf.cdp_total_amount','caf.penaltyamount','ctt.created_at')->where('ctt.id',$id)->first();
    }
    public function GetInspectiondata($id){
        return DB::table('cpdo_development_inspection_reports as cir')
		->leftjoin('cpdo_development_permits AS caf', 'caf.id', '=', 'cir.caf_id')
		->leftjoin('cto_top_transactions AS ctt', 'cir.caf_id', '=', 'ctt.transaction_ref_no')
		->join('clients AS c', 'c.id', '=', 'caf.client_id')
		->select('cir.*','ctt.transaction_no','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.nameofproject','caf.cdp_phone_no','caf.cdp_total_amount','caf.cdp_address','ctt.created_at','caf.id as cafid','caf.top_transaction_type_id')->where('cir.id',$id)->first();
    }

    public function getCertificateData($id){
       return DB::table('cpdo_development_certificate as cc')->leftjoin('cpdo_development_permits AS caf', 'caf.id', '=', 'cc.caf_id')->leftjoin('cto_top_transactions AS ctt', 'cc.caf_id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('cc.*','ctt.transaction_no','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.cdp_phone_no','caf.cdp_control_no','caf.cdp_address','caf.cdp_total_amount','ctt.created_at','caf.id as cafid','caf.top_transaction_type_id')->where('cc.id',$id)->first();
    }

    public function getProfileDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_telephone_no','id as clientid','p_mobile_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no','p_email_address')->where('id',(int)$id)->first();
    }

    public function Getclientbyid($id){
      return DB::table('clients')->select('c.full_name','rpo_first_name','rpo_custom_last_name','rpo_middle_name')->where('id',$id)->first();
    }
    
    public function getEditDetails($id){
       return DB::table('cpdo_development_inspection_reports')->select('cir_upload_documents_json')->where('caf_id',$id)->first();
    }

    public function GetapplicationRecord($id){
         return DB::table('cpdo_development_permits as caf')->leftjoin('cto_top_transactions as ctt','caf.id', '=', 'ctt.transaction_ref_no')->select('caf.*','ctt.transaction_no')->where('caf.id',$id)->first();
    }
    public function Getpenaltypercen($id){
        return DB::table('cpdo_penalties')->select('id','name','percentage')->where('id',$id)->first();
    }
    public function GetPenaltiesmaster(){
       return DB::table('cpdo_penalties')->select('id','name','percentage')->get()->toArray();
    }
    public function CertificateupdateData($id,$columns){
        return DB::table('cpdo_development_certificate')->where('id',$id)->update($columns);
    }
    public function findCertificateDataById($id){
        return DB::table('cpdo_development_certificate as cdc')->leftjoin('cpdo_development_permits AS caf', 'caf.id', '=', 'cdc.caf_id')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('cdc.*','c.full_name','c.p_mobile_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.cdp_phone_no','caf.totalamount','caf.cdp_total_amount','caf.penaltyamount')->where('cdc.id',$id)->first();
    }
    
    public function CertificateaddData($postdata){
        DB::table('cpdo_development_certificate')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function CertificateupdatebyCafid($id,$columns){
        return DB::table('cpdo_development_certificate')->where('caf_id',$id)->update($columns);
    }
    public function InspectionupdateData($id,$columns){
        return DB::table('cpdo_development_inspection_reports')->where('id',$id)->update($columns);
    }
    public function InspectionaddData($postdata){
        DB::table('cpdo_development_inspection_reports')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function GetGeoLocationbyid($id){
       return DB::table('cpdo_inspection_geotagging')->where('cir_id',$id)->get()->toArray();
    }
    public function AddGeoLocationData($postdata){
        DB::table('cpdo_inspection_geotagging')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateGeoLocationData($id,$columns){
        return DB::table('cpdo_inspection_geotagging')->where('id',$id)->update($columns);
    }
    public function TransactionupdateData($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function checkTransactionexist($id){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id','44')->get();
    }
    public function getORandORdate($id){
      return DB::table('cto_cashier')->select('or_no','total_amount',DB::raw('DATE(created_at) AS created_at'))->where('top_transaction_id',$id)->get()->toArray();
    }
    public function TransactionaddData($postdata){
        DB::table('cto_top_transactions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function isexistinspection($id){
         return DB::table('cpdo_development_inspection_reports')->where('caf_id',$id)->get();
    }
    public function getapproveluserid($id){
      return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first();
    }
    public function isexistcertificate($id){
      return DB::table('cpdo_development_certificate')->where('caf_id',$id)->get();
    }
    public function getcertificateforedit($id){
      return DB::table('cpdo_development_certificate')->where('caf_id',$id)->first();
    }
    public function getinspectionorderforedit($id){
        return DB::table('cpdo_development_inspection_reports')->where('caf_id',$id)->first();
    }

    public function gethremployess(){ 
      return DB::table('hr_employees')->select('id','fullname','suffix')->where('is_active',1)->get();
    } 

    public function getpositionbyid($id){
      return DB::table('hr_employees as he')->leftjoin('hr_designations as hd','he.hr_designation_id','=','hd.id')->select('he.id','hd.description','he.fullname')->where('he.id',$id)->first();
    }

    public function getclientidbyid($id){
        return DB::table('cpdo_development_permits')->select('client_id')->where('id',$id)->first();
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

    public function getDataForEdit($id){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_development_permits')->where('id',$id)->first();
    }

    public function getBillDetails($transaction_no,$id){
        return DB::table('cto_top_transactions AS tt')
        ->select('tt.amount','tt.attachment','tt.transaction_no')
        ->where('tt.id',$transaction_no)->orderBy('tt.id', 'DESC')->first();
    }

    public function getRequirements(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_cpdo',1)->get();
    }
     public function getRequirementsAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('requirements')
        ->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_cpdo',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(req_code_abbreviation)'),'like',"%".strtolower($search)."%");
            $sql->where(DB::raw('LOWER(req_description)'),'like',"%".strtolower($search)."%");
            $sql->orWhere(DB::raw("CONCAT(req_code_abbreviation,' - ', COALESCE(req_description))"), 'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('requirements.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }

    public function getCpdomunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','6')->limit(1)->first();
    }

    public function appRequirementupdateData($id,$columns){
        return DB::table('cpdo_development_permit_requirements')->where('id',$id)->update($columns);
    }
    public function checkappRequiremenexist($id,$reqid){
        return DB::table('cpdo_development_permit_requirements')->where('cdp_id',$id)->where('req_id',$reqid)->get();
    }
    public function appRequirementaddData($postdata){
        DB::table('cpdo_development_permit_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    } 
    public function AddappFilesData($postdata){
        DB::table('cpdo_development_permit_req_files')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkRequirementfileexist($id){
       return DB::table('cpdo_development_permit_req_files')->where('cdpr_id',$id)->get()->toArray();
    }

    public function UpdateappFilesData($id,$columns){
        return DB::table('cpdo_development_permit_req_files')->where('id',$id)->update($columns);
    }

    public function updateinspectionData($id,$columns){
       return DB::table('cpdo_development_inspection_reports')->where('caf_id',$id)->update($columns);
    }

    public function AddPaymentLineData($postdata){
        DB::table('cpdo_development_permit_payment_lines_create')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updatepaymentlineData($id,$columns){
       return DB::table('cpdo_development_permit_payment_lines_create')->where('id',$id)->update($columns);
    }

    public function checkpaymentlineExist($id,$appid){
       return DB::table('cpdo_development_permit_payment_lines_create')->where('cdppl_plineid',$appid)->where('cdp_id',$id)->get();
    }

    public function getPaymentlinebyappid($id){
       return DB::table('cpdo_development_permit_payment_lines_create')->where('cdp_id',$id)->get();
    }


    public function getAppRequirementsData($id){
           return DB::table('cpdo_development_permit_requirements AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_development_permit_req_files AS cf', 'cf.cdpr_id', '=', 'cpr.id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cdprl_name','cf.cdprl_path')->where('cpr.cdp_id',$id)->groupby('cpr.id')->get()->toArray();
    }
    public function getAppRequirementsDataonline($id){
      $remortServer = DB::connection('remort_server');
           return $remortServer->table('cpdo_development_permit_requirements AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_development_permit_req_files AS cf', 'cf.cdpr_id', '=', 'cpr.frgn_cdpr_id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cdprl_name','cf.cdprl_path')->where('cpr.cdp_id',$id)->groupby('cpr.id')->get()->toArray();
    }
    public function getRequirementsbyid($id){
         return DB::table('cpdo_development_permit_requirements AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_development_permit_req_files AS cf', 'cf.cdpr_id', '=', 'cpr.id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cdprl_name','cf.cdprl_path')->where('cpr.id',$id)->groupby('cpr.id')->get()->toArray();
    }
    public function deleteRequirementsbyid($id){
       return DB::table('cpdo_development_permit_requirements')->where('id',$id)->delete();
    }

    public function deleteimagerowbyid($id){
      return DB::table('cpdo_development_permit_req_files')->where('id',$id)->delete();
    }

    public function getSercviceRequirements($id){
             return DB::table('cpdo_service_requirements AS csr')
          ->join('requirements AS re', 'csr.req_id', '=', 're.id')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('csr.tfoc_id',$id)->orderby('orderno','ASC')->Groupby('re.id')->get();; 
    } 

    public function GetcpdolatestApp(){
       return DB::table('cpdo_development_permits')->orderby('id','ASC')->first();
    }

    public function getServiceTypearray($type){
        return DB::table('cpdo_module')->where('cm_type',$type)->orderby('id','ASC')->get();
    }

    public function getServiceTypearraydefault(){
        return DB::table('cpdo_module')->where('cm_type',2)->orderby('id','ASC')->get();
    }

    public function getCasheringIds($id){
       return DB::table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id')->where('id',$id)->first();
    }

    public function getcleranceid($id){
       return DB::table('cpdo_development_permit_computation')->select('id')->where('cm_id',$id)->orderby('id','ASC')->first();
    }

    public function paymentlines($id){
       return DB::table('cpdo_development_permit_computation_lines as cdcl')->leftjoin('cpdo_imperial_system as cis','cis.id','=','cdcl.cis_id')->select('cdcl.cdpcl_description','cdcl.cdpcl_amount','cdcl.id','cis.cis_code')->where('cdpc_id',$id)->orderby('cdcl.id','ASC')->get();
    }

    public function getclerancelinedata($id,$billamount){ 
        return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over','>=',$billamount)->where('czcc_id',$id)->first();
    }
    public function hetoverbyAmount($id,$billamount){
      return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over_by_amount','=','1')->where('czcc_id',$id)->first();
    }


    public function getList($request){
          $params = $columns = $totalRecords = $data = array();
          $params = $_REQUEST;
          $q=$request->input('q');
          $client_id=$request->input('client_id');
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
            1 =>"cdf.cdp_control_no",
            2 =>"c.full_name",
			      3 =>"cdf.nameofproject",
            4 =>"cdf.cdp_address" 
			//5 =>"cdf.caf_type_project",		
          );

          $sql = DB::table('cpdo_development_permits AS cdf')
                ->join('clients AS c', 'c.id', '=', 'cdf.client_id') 
                ->leftjoin('cpdo_development_inspection_reports AS cir', 'cdf.id', '=', 'cir.caf_id')
                ->select('cir.id as cirid',DB::raw('DATE(cdf.created_at) AS date'),'cdf.id','cdf.is_online','cdf.client_id','cdf.nameofproject','cdf.locationofproject','cdf.project_barangay_id','cdf.cdp_control_no','cdf.top_transaction_type_id','cdf.cdp_total_amount','cdf.cdp_address','c.full_address','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cdf.csd_id','cdf.penaltyamount','cdf.cs_id','cdf.is_active');


          //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cdf.cdp_address)'),'like',"%".strtolower($q)."%")
				        ->orWhere(DB::raw('LOWER(c.full_address)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cdf.nameofproject)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%");
                  });
              }
              if(!empty($client_id) && isset($client_id)){
                  $sql->where('cdf.client_id',$client_id);
              }
              if(!empty($barangay)){
                  $sql->where('cdf.locationofproject',$barangay);
              }
              if(!empty($fromdate) && isset($fromdate)){
                  $sql->whereDate('cdf.created_at','>=',trim($fromdate));  
              }
              if(!empty($todate) && isset($todate)){
                  $sql->whereDate('cdf.created_at','<=',trim($todate));  
              }
              if(!empty($status)){
                  $sql->where('cdf.csd_id',$status);
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
          $remortServer = DB::connection('remort_server');
          $params = $columns = $totalRecords = $data = array();
          $params = $_REQUEST;
          $q=$request->input('q');
          $from_date=(!empty($request->input('from_date')))?Carbon::parse($request->input('from_date'))->format('Y-m-d'):'';
          $to_date=(!empty($request->input('to_date')))?Carbon::parse($request->input('to_date'))->format('Y-m-d'):'';
          $brgy=$request->input('brgy');
          $flt_status=$request->input('flt_status');

          if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
          }

          $columns = array( 
            0 =>"es.id",
            1 =>"cdf.cdp_control_no",
            2 =>"c.full_name",
            3 =>"cdf.caf_type_project",
            4 =>"cdf.cdp_address"    
          );

          $sql = $remortServer->table('cpdo_development_permits AS cdf')
                ->join('clients AS c', 'c.client_frgn_id', '=', 'cdf.client_id') 
                ->leftjoin('cpdo_development_inspection_reports AS cir', 'cdf.id', '=', 'cir.caf_id')
                ->select('cir.id as cirid','cdf.is_approved','cdf.client_id','cdf.nameofproject','cdf.locationofproject','cdf.project_barangay_id','cdf.id','cdf.cdp_control_no','cdf.top_transaction_type_id','cdf.cdp_total_amount','cdf.cdp_address','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cdf.csd_id','cdf.cs_id','cdf.is_active','cdf.created_at');
         
          $sql->where('cdf.is_approved','!=',1);

          if(!empty($from_date) && isset($from_date)){
            $sql->whereDate('cdf.created_at','>=',$from_date);
            }
            if(!empty($to_date) && isset($to_date)){
                            $sql->whereDate('cdf.created_at','<=',$to_date);
                    }
            if(!empty($brgy) && isset($brgy)){
                    $sql->where('cdf.project_barangay_id',$brgy);
            } 
            if($flt_status != 4 && isset($flt_status) ){
                    $sql->where('cdf.is_approved',$flt_status);
            } 
              
              if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q,$remortServer) {
                    $sql->where($remortServer->raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(cdf.cdp_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(cdf.nameofproject)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%");
                });
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

    public function findOnlineApp($id){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_development_permits AS cdf')
      ->join('clients AS c', 'c.client_frgn_id', '=', 'cdf.client_id') 
      ->select('cdf.*','c.full_name')
      ->where('cdf.id',$id)->first();
    }

    public function updateremotedevdata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('cpdo_development_permits')->where('frgn_cdp_id',$id)->update($columns);
    }

    public function approve($request,$id)
    {
        $remortServer = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $rowToUpdate = $remortServer->table('cpdo_development_permits')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_cdp_id=$rowAttributes['frgn_cdp_id'];
            unset($rowAttributes['id']);
            unset($rowAttributes['frgn_cdp_id']);
            unset($rowAttributes['is_approved']);
            unset($rowAttributes['req_file']);
            $rowAttributes['is_synced'] = 1;
            // dd($rowAttributes);
            DB::table('cpdo_development_permits')->insert($rowAttributes);
            $last_insert_id=DB::getPdo()->lastInsertId();
            $controlno = str_pad($last_insert_id, 6, '0', STR_PAD_LEFT); 
            DB::table('cpdo_development_permits')->where('id',$last_insert_id)->update(['cdp_control_no' => $controlno]);
             $bplo_cafrequirment = $remortServer->table('cpdo_development_permit_requirements')->where('cdp_id',$frgn_cdp_id)->get();

            foreach($bplo_cafrequirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $frgn_devlop_reqid=$reqRowAttributes['frgn_cdpr_id']; 
                unset($reqRowAttributes['id']);
                unset($reqRowAttributes['frgn_cdpr_id']);
                unset($reqRowAttributes['is_synced']);
                $reqRowAttributes['cdp_id'] = $last_insert_id;
                DB::table('cpdo_development_permit_requirements')->insert($reqRowAttributes);
                $l_cpdo_req_id=DB::getPdo()->lastInsertId(); 
                $remortServer->table('cpdo_development_permit_requirements')->where('id',$item->id)->update(['cdp_id' => $last_insert_id,'frgn_cdpr_id' => $l_cpdo_req_id]);
                $cpdo_file = $remortServer->table('cpdo_development_permit_req_files')->where('cdpr_id',$frgn_devlop_reqid)->get();
                foreach($cpdo_file as $itemfile)
                {
                    $cpdofileRowAttributes = get_object_vars($itemfile);
                    unset($cpdofileRowAttributes['id']);
                    $remotePath = 'public/uploads/cpdo_development/requirement/' . $itemfile->cdprl_name;

                    // Retrieve the file contents from the remote server
                    $fileContents = Storage::disk('remote')->get($remotePath);
                    if ($fileContents !== false) {
                        // Define the local path where you want to save the file
                        $localPath = public_path() . '/uploads/cpdo_development/requirement/'.$itemfile->cdprl_name;

                        // Use file_put_contents to save the retrieved file contents locally
                        if (file_put_contents($localPath, $fileContents) !== false) {
                            // File was successfully transferred from remote server to local path
                        } else {
                            // Handle the error if the local file couldn't be saved
                        }
                    } else {
                        // Handle the error if the file couldn't be retrieved from the remote server
                    }    

                    $cpdofileRowAttributes['cdpr_id'] = $l_cpdo_req_id;
                    DB::table('cpdo_development_permit_req_files')->insert($cpdofileRowAttributes);
                    $remortServer->table('cpdo_development_permit_req_files')->where('id',$itemfile->id)->update(['cdpr_id' => $l_cpdo_req_id]);
                }
            }
            $remortServer->table('cpdo_development_permits')->where('id',$id)->update(['is_approved' => 1,'frgn_cdp_id' => $last_insert_id,'cdp_control_no'=>$controlno]);
            DB::commit();
            return $last_insert_id;
            
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 
    
    public function decline($request,$id)
    {
        $remortServer = DB::connection('remort_server');
        try {
            DB::beginTransaction();
             $remortServer->table('cpdo_development_permits')->where('id',$id)->update(['is_approved' => 2]);
            DB::commit();
            return $id;
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 
    public function getClientDetails($id){
      return DB::table('clients')->where('id',$id)->first();
    } 
    
}
