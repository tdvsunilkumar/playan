<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;

class EngOccupancyApp extends Model
{
    public $table = 'eng_occupancy_apps';

    public function getOwners(){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_engg',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function getServices(){
      return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->leftjoin('occupancy_services as oc','ctot.id','=','oc.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','oc.top_transaction_type_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')->where('tfoc_status',1)->where('ctot.tfoc_is_applicable','4')->get();
    }
    public function getRptOwners(){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_rpt',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function GetOccupancyAppData($id){
        return DB::table('eng_occupancy_apps as app')->leftjoin('clients as c','c.id','=','app.client_id')->select('app.*','c.p_mobile_no','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('app.id',$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('eng_occupancy_apps')->where('id',$id)->update($columns);
    }
    public function getclientidbyid($id){
        return DB::table('eng_occupancy_apps')->select('client_id')->where('id',$id)->first();
    }
    public function getBillDetails($transaction_no,$id){
        return DB::table('cto_top_transactions AS tt')
        ->leftjoin('eng_occupancy_apps as eoa','eoa.id','=','tt.transaction_ref_no')
        ->select('tt.amount','tt.attachment','eoa.client_id','tt.transaction_no')
        ->where('tt.id',$transaction_no)->orderBy('tt.id', 'DESC')->first();
    }
    public function addData($postdata){
        DB::table('eng_occupancy_apps')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getbuidingpermitdata($id){
        return DB::table('eng_bldg_permit_apps as ebpa')
        ->leftjoin('clients as c','c.id','=','ebpa.p_code')
        ->leftjoin('eng_bldg_fees_details as ebfd','ebpa.id','=','ebfd.ebpa_id')
        ->leftjoin('eng_job_requests as ejr','ejr.id','=','ebpa.ejr_id')
        ->select('ebpa.ebpa_issued_date','ejr.ejr_project_name','ejr.orno as ejr_or','ejr.ordate as ejr_ordate','ejr.location_brgy_id','c.id','c.p_mobile_no','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_barangay_id_no','ebfd.ebfd_no_of_storey','ebfd.ebfd_floor_area','ejr.ebfd_floor_area as dimension','ebpa.no_of_units','ebfd.ebfd_construction_date','ebfd.ebfd_construction_date','ebfd.ebfd_completion_date','ejr.ejr_firstfloorarea','ejr.ejr_secondfloorarea','ejr.ejr_lotarea','ejr.ejr_perimeter','ejr.ejr_projectcost')
        // ->select('c.id','c.p_mobile_no','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_barangay_id_no','ebfd.ebfd_no_of_storey','ebfd.ebfd_floor_area','ebpa.ebot_id')
        ->where('ebpa.ebpa_permit_no',$id)->first();
    }
    public function TransactionupdateData($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function checkTransexist($id,$tttypeid){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id',$tttypeid)->get();
    }
    public function TransactionaddData($postdata){
        DB::table('cto_top_transactions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function insertbillsummary($postdata){
      DB::table('occupancy_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function insertbillsummaryremote($postdata){
      $remortServer = DB::connection('remort_server');
      $remortServer->table('occupancy_bill_summary')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateremotedata($id,$columns){
      $remortServer = DB::connection('remort_server');
      return $remortServer->table('eng_occupancy_apps')->where('frgn_eoa_id',$id)->update($columns);
    }

    public function checkOccupancyRequirementsexist($id,$reqid){
        return DB::table('occupancy_requirement')->where('eoa_id',$id)->where('req_id',$reqid)->get()->toArray();
    }

    public function GetTypeofOccupancy(){
      return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->get();
    }
    
    public function addOccupancyRequirementsData($postdata){
        DB::table('occupancy_requirement')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    } 
    public function GetRequirements(){
    	return DB::table('eng_application_type')->select('id','eat_module_desc')->get();
    }
    public function getPermitno($permitno){
        return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->where('ebpa_permit_no',$permitno)->get();
    }
    public function GetBuildingpermits(){
      return DB::table('eng_bldg_permit_apps as a')->join('eng_job_requests as ejr','ejr.id','=','a.ejr_id')->select('a.id','a.ebpa_permit_no')->where('a.ebpa_permit_no','<>','')->get();
    }
    public function GetBuildingpermitsAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('eng_bldg_permit_apps as a')->join('eng_job_requests as ejr','ejr.id','=','a.ejr_id')->select('a.id','a.ebpa_permit_no')->where('a.ebpa_permit_no','<>','')->where('a.ebpa_permit_no','<>',NULL)->where('ejr.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('a.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(a.ebpa_permit_no)'),'like',"%".strtolower($search)."%");;
          }
        });
      }
      $sql->orderBy('a.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function checkRequirementfileexist($id){
       return DB::table('files_occupancy')->where('eoar_id',$id)->get()->toArray();
    }
    public function AddengFilesData($postdata){
        DB::table('files_occupancy')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateengFilesData($id,$columns){
        return DB::table('files_occupancy')->where('id',$id)->update($columns);
    }
    public function getCasheringIds($id){
       return DB::table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id','fund_id')->where('id',$id)->first();
    }
    
    public function deleteFeedetailsrow($id){
        return DB::table('eng_occupancy_fees_details')->where('id',$id)->delete();
    }

    public function GetDefaultfees(){
        return DB::table('eng_occupancy_default_fees')->select('fees_description','id as tfoc_id')->where('status',1)->orderby('id','ASC')->get();
    }
    public function GetRequestfees($id){
        return DB::table('eng_occupancy_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('eoa_id',$id)->orderby('id','ASC')->get();
    }
    public function getUserrole($id){
           return DB::table('users_role as ur')->leftjoin('role AS r','r.id','=','ur.role_id')->select('r.id','r.name')->where('user_id',$id)->get();
    }
    
    public function addoccupancyFeesDetailData($postdata){
        DB::table('eng_occupancy_fees_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateoccupancyFeesDetailData($id,$columns){
        return DB::table('eng_occupancy_fees_details')->where('id',$id)->update($columns);
    }
    public function checkoccupancyFeesDetail($appid,$feedesc){
         return DB::table('eng_occupancy_fees_details')->select('*')->where('fees_description',$feedesc)->where('eoa_id',$appid)->get();
    }
    
    public function getSercviceRequirements(){
             return DB::table('requirements AS re')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('req_dept_eng',1)->Groupby('re.id')->get();; 
    }
    public function getSercviceRequirementsAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('requirements')
        ->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_eng',1);
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
    public function getextrafees(){
        return DB::table('occupancy_services AS es')
        ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'es.tfoc_id') 
        ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id')->select('asl.description','afc.id')->where('es.is_main_service','=','0')->get();
    }

    public function getJobRequirementsdefault($tfocid){
           return DB::table('eng_occ_services_requirements AS eosr')
          ->join('requirements AS re', 'eosr.req_id', '=', 're.id')
          ->select('eosr.req_id','re.req_code_abbreviation','re.req_description')->where('eosr.tfoc_id',$tfocid)->groupby('eosr.id')->orderby('eosr.orderno','ASC')->get()->toArray();
    }

    public function getJobRequirementsData($id){
           return DB::table('occupancy_requirement AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('files_occupancy AS fo', 'esr.id', '=', 'fo.eoar_id')
          ->select('fo.*','esr.id as rid','esr.req_id','re.req_code_abbreviation','re.req_description')->where('esr.eoa_id',$id)->groupby('esr.id')->get()->toArray();
    }

    public function getRequirementsbyid($id){
          return DB::table('occupancy_requirement AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('files_occupancy AS fo', 'esr.id', '=', 'fo.eoar_id')
          ->select('fo.*','esr.id as rid','esr.req_id','re.req_code_abbreviation','re.req_description')->where('esr.id',$id)->groupby('esr.id')->get()->toArray();
    }
    public function deleteRequirementsbyid($id){
       return DB::table('occupancy_requirement')->where('id',$id)->delete();
    } 
    public function deleteimagerowbyid($id){
      return DB::table('files_occupancy')->where('id',$id)->delete();
    }

    public function getBarangay($id){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->where('bgf.id',$id)->get();
    }

    public function getoccupancydetail($id){
    	 return DB::table('eng_occupancy_apps AS eoc')
          ->join('clients AS cl', 'eoc.client_id', '=', 'cl.id')
          ->select('eoc.id as id','eoc.*','eoc.client_id','eoc.eoa_application_type','cl.rpo_custom_last_name','cl.rpo_first_name','cl.rpo_middle_name','cl.p_telephone_no','eoc.is_active','eoc.ebpa_id','eoc.dateissued','eoc.rpo_address_house_lot_no','eoc.rpo_address_street_name','eoc.rpo_address_subdivision','eoc.nameofproject','eoc.ebpa_location','eoc.ebfd_no_of_storey','eoc.no_of_units','eoc.ebfd_floor_area','eoc.eoa_date_of_completion','eoc.eoa_application_type')->where('eoc.id',$id)->get()->first();
          //   ->select('eoc.id','eoc.ebpa_id','eoc.client_id','eoc.eoa_application_type','cl.rpo_custom_last_name','cl.rpo_first_name','cl.rpo_middle_name','cl.p_telephone_no','eoc.is_active','eoc.ebpa_id','eoc.dateissued','eoc.rpo_address_house_lot_no','eoc.rpo_address_street_name','eoc.rpo_address_subdivision','eoc.nameofproject','eoc.ebpa_location','eoc.ebfd_no_of_storey','eoc.no_of_units','eoc.ebfd_floor_area','eoc.eoa_date_of_completion','eoc.eoa_application_type')->where('eoc.id',$id)->get()->first();
    }
    public function checkTransactionexist($id,$tttypeid){
        return DB::table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id',$tttypeid)->get();
    }
    public function getORandORdate($id){
      return DB::table('cto_cashier')->select('or_no',DB::raw('DATE(created_at) AS created_at'))->where('top_transaction_id',$id)->get()->toArray();
    }

     public function getOccumunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','8')->limit(1)->first();
    }
    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
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
    $method = $request->input('method');
    $fromdate=$request->input('fromdate');
    $todate=$request->input('todate');
    $status=$request->input('status');
    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"eoc.id",
      1 =>"ebpa_id",
      2 =>"eoc.client_id",
      3 =>"eoc.eoa_application_type",
      4 =>"eoc.is_active",
     	
    );

    $sql = DB::table('eng_occupancy_apps AS eoc')
          ->join('clients AS cl', 'eoc.client_id', '=', 'cl.id')
          ->select('eoc.id','eoc.ebpa_id','eoc.client_id','eoc.is_online','eoc.is_approve','eoc.location_brgy_id','eoc.eoa_application_type','cl.full_name','cl.rpo_custom_last_name','cl.rpo_first_name','cl.rpo_middle_name','eoc.is_active','eoc.eoa_application_no','eoc.eoa_total_fees','eoc.top_transaction_type_id',DB::raw('DATE(eoc.created_at) AS created_at'));

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(eoc.eoa_application_type)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.ebpa_id)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.client_id)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%") 
				->orWhere(DB::raw('LOWER(cl.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.is_active)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.eoa_application_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.eoa_total_fees)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.top_transaction_type_id)'),'like',"%".strtolower($q)."%")
                ;
			});
		}
      if(!empty($client_id) && isset($client_id)){
          $sql->where('eoc.client_id',$client_id);
      }
      if(!empty($barangay)){
        $sql->where('eoc.location_brgy_id',$barangay);
      }
      if(!empty($fromdate) && isset($fromdate)){
          $sql->whereDate('eoc.created_at','>=',trim($fromdate));  
      }
      if(!empty($todate) && isset($todate)){
          $sql->whereDate('eoc.created_at','<=',trim($todate));  
      }
      if(isset($method)){
          $sql->where('eoc.is_online',$method);  
      }
      if($status != ''){
        $sql->where('eoc.is_active',$status);
      }
     
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('eoc.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

    // eloquent style

    public function approver()
    {
        return $this->hasOne(User::class, 'id', 'eoa_opd_approved_by');
    }
    public function preparer()
    {
        return $this->hasOne(User::class, 'id', 'eoa_opd_created_by');
    }
    // client / applicant of job request
    // $data->details->client = data
    public function client() 
    {
        return $this->hasOne(EngClients::class, 'id', 'client_id');
    }

    // for transaction
    public function getTableIdAttribute()//get transaction id $this->table_id
    {
        // return DB::table('cto_top_transaction_type')->where('ttt_table_reference',$this->table)->first()->id; //it suppose to be like this
        return 10;
    }
    public function getTransactionAttribute()
    {
        return $this->checkTransactionexist($this->id,$this->table_id)[0]->transaction_no;
    }
}
