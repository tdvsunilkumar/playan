<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class CpdoApplicationOnline extends Model
{
	public $remortServer ="";
	public function __construct() 
    {
        date_default_timezone_set('Asia/Manila');
       $this->remortServer = DB::connection('remort_server');
    } 
     public function getOwners(){
        return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_engg',1)->where('rpo_first_name','<>',NULL)->get();
    }
     public function getServices(){
    	return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('cpdo_services as cs','ctot.id','=','cs.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','cs.top_transaction_type_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','cttt.ttt_desc as description')->where('tfoc_status',1)->get();
    }

    public function getServicesbyid($id){
      return $this->remortServer->table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('cpdo_services as cs','ctot.id','=','cs.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','cs.top_transaction_type_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','cttt.ttt_desc as description')->where('ctot.id',$id)->where('tfoc_status',1)->get();
    }
    public function updateData($id,$columns){
        return $this->remortServer->table('cpdo_application_forms')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cpdo_application_forms')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getDataForEdit($id){
    	return $this->remortServer->table('cpdo_application_forms')->where('id',$id)->first();
    }
    public function GetOrderdata($id){
        return DB::table('cto_top_transactions as ctt')->leftjoin('cpdo_application_forms AS caf', 'caf.id', '=', 'ctt.transaction_ref_no')->join('clients AS c', 'c.id', '=', 'caf.client_id')->select('ctt.id','ctt.transaction_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','caf.client_telephone','caf.caf_amount','ctt.created_at')->where('ctt.id',$id)->first();
    }

    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }

    public function getCpdomunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','6')->limit(1)->first();
    }

    public function getProfileDetails($id){
      //echo "here"; exit;
        return DB::table('clients')
              ->select('p_telephone_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no')->where('id',(int)$id)->first();
    }

    public function Getclientbyid($id){
      return DB::table('clients')->select('rpo_first_name','rpo_custom_last_name','rpo_middle_name')->where('id',$id)->first();
    }

    public function GetapplicationRecord($id){
         return DB::table('cpdo_application_forms as caf')->leftjoin('cto_top_transactions as ctt','caf.id', '=', 'ctt.transaction_ref_no')->select('caf.*','ctt.transaction_no')->where('caf.id',$id)->first();
    }
    public function CertificateupdateData($id,$columns){
        return DB::table('cpdo_certificate')->where('id',$id)->update($columns);
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
        return $this->remortServer->table('cto_top_transactions')->where('transaction_ref_no',$id)->where('top_transaction_type_id','19')->get();
    }
    public function getORandORdate($id){
      return DB::table('cto_cashier')->select('or_no','total_amount',DB::raw('DATE(created_at) AS created_at'))->where('top_transaction_id',$id)->get()->toArray();
    }
    public function TransactionaddData($postdata){
        DB::table('cto_top_transactions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
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

    public function gethremployess(){ 
      return DB::table('hr_employees')->select('id','fullname','suffix')->where('is_active',1)->get();
    } 

    public function getpositionbyid($id){
      return DB::table('hr_employees as he')->leftjoin('hr_designations as hd','he.hr_designation_id','=','hd.id')->select('he.id','hd.description','he.fullname')->where('he.id',$id)->first();
    }

    public function getRequirements(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_cpdo',1)->get();
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
           return $this->remortServer->table('cpdo_application_requirement AS cpr')
          ->join('requirements AS re', 'cpr.req_id', '=', 're.id')
          ->leftjoin('cpdo_file AS cf', 'cf.car_id', '=', 'cpr.frgn_car_id')
          ->select('cf.id as cfid','cpr.id','cpr.req_id','re.req_code_abbreviation','re.req_description','cf.cf_name','cf.cf_path')->where('cpr.caf_id',$id)->groupby('cpr.id')->get()->toArray();
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
       return $this->remortServer->table('cpdo_application_forms')->orderby('id','DESC')->first();
    }

    public function getServiceTypearray($type){
        return DB::table('cpdo_module')->where('cm_type',$type)->orderby('id','ASC')->get();
    }

    public function getServiceTypearraydefault(){
        return $this->remortServer->table('cpdo_module')->where('cm_type','1')->orderby('id','ASC')->get();
    }

    public function getCasheringIds($id){
       return $this->remortServer->table('cto_tfocs')->select('gl_account_id','sl_id')->where('id',$id)->first();
    }

    public function getcleranceid($id){
       return $this->remortServer->table('cpdo_zoning_computation_clearance')->select('id')->where('cm_id',$id)->orderby('id','ASC')->first();
    }

    public function getclerancelinedata($id,$billamount){ 
        return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over','>=',$billamount)->where('czccl_over_by_amount','=','0')->where('czcc_id',$id)->first();
    }
    public function hetoverbyAmount($id,$billamount){
      return DB::table('cpdo_zoning_computation_clearance_lines')->select('czccl_amount','czccl_over_by_amount','czccl_below')->where('czccl_below','<=',$billamount)->where('czccl_over_by_amount','=','1')->where('czcc_id',$id)->first();
    }

    public function approve($id)
    {
        $remortServernew = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $rowToUpdate = $remortServernew->table('cpdo_application_forms')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_caf_id=$rowAttributes['frgn_caf_id'];
            unset($rowAttributes['id']);
            unset($rowAttributes['frgn_caf_id']);
             $rowAttributes['is_approved']=1;
             $rowAttributes['is_synced']=1;
            DB::table('cpdo_application_forms')->insert($rowAttributes);
            $l_cafapplication_id=DB::getPdo()->lastInsertId();
            $controlno = str_pad($l_cafapplication_id, 6, '0', STR_PAD_LEFT); 
            DB::table('cpdo_application_forms')->where('id',$l_cafapplication_id)->update(['caf_control_no' => $controlno]);
            $bplo_cafrequirment = $remortServernew->table('cpdo_application_requirement')->where('caf_id',$frgn_caf_id)->get();

            foreach($bplo_cafrequirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $frgn_cpdo_reqid=$reqRowAttributes['frgn_car_id']; 
                unset($reqRowAttributes['id']);
                unset($reqRowAttributes['frgn_car_id']);
                unset($reqRowAttributes['is_synced']);
                $reqRowAttributes['caf_id'] = $l_cafapplication_id;
                DB::table('cpdo_application_requirement')->insert($reqRowAttributes);
                $l_cpdo_req_id=DB::getPdo()->lastInsertId(); 
                $remortServernew->table('cpdo_application_requirement')->where('id',$item->id)->update(['caf_id' => $l_cafapplication_id,'frgn_car_id' => $l_cpdo_req_id]);
                $cpdo_file = $remortServernew->table('cpdo_file')->where('car_id',$frgn_cpdo_reqid)->get();
                foreach($cpdo_file as $item)
                {
                    $cpdofileRowAttributes = get_object_vars($item);
                    unset($cpdofileRowAttributes['id']);
                    unset($cpdofileRowAttributes['is_synced']);

                    $remotePath = 'public/uploads/cpdo/requirement/' . $item->cf_name;

                    // Retrieve the file contents from the remote server
                    $fileContents = Storage::disk('remote')->get($remotePath);
                    if ($fileContents !== false) {
                        // Define the local path where you want to save the file
                        $localPath = public_path() . '/uploads/cpdo/requirement/'.$item->cf_name;

                        // Use file_put_contents to save the retrieved file contents locally
                        if (file_put_contents($localPath, $fileContents) !== false) {
                            // File was successfully transferred from remote server to local path
                        } else {
                            // Handle the error if the local file couldn't be saved
                        }
                    } else {
                        // Handle the error if the file couldn't be retrieved from the remote server
                    }    

                    $cpdofileRowAttributes['car_id'] = $l_cpdo_req_id;
                    DB::table('cpdo_file')->insert($cpdofileRowAttributes);
                    $remortServernew->table('cpdo_file')->where('id',$item->id)->update(['car_id' => $l_cpdo_req_id]);
                }
            }

            $remortServernew->table('cpdo_application_forms')->where('id',$id)->update(['caf_control_no' => $controlno,'frgn_caf_id' => $l_cafapplication_id,'is_approved' => 1]);
            DB::commit();
            return $l_cafapplication_id;
        } catch (\Exception $e) {
            //echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 

    public function syncapptoremote($id){
         $remortServernew = DB::connection('remort_server');
        try {
            $remortServernew->beginTransaction();
            $rowToUpdate = DB::table('cpdo_application_forms')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $currentapp_id=$rowAttributes['id'];
            unset($rowAttributes['id']);
            $checkappinremote = $remortServernew->table('cpdo_application_forms')->where('frgn_caf_id',$currentapp_id)->first();
            if(!empty($checkappinremote)){
                unset($rowAttributes['frgn_caf_id']);
                $remortServernew->table('cpdo_application_forms')->where('frgn_caf_id',$currentapp_id)->update($rowAttributes); 
            }else{
                $rowAttributes['frgn_caf_id'] = $currentapp_id;
                $rowAttributes['is_approved'] = '1';
                $remortServernew->table('cpdo_application_forms')->insert($rowAttributes);  
            }

            // $controlno = str_pad($l_cafapplication_id, 6, '0', STR_PAD_LEFT); 
            $cpdo_cafrequirment = DB::table('cpdo_application_requirement')->where('caf_id',$id)->get();

            foreach($cpdo_cafrequirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $currentreq_id=$reqRowAttributes['id']; 
                unset($reqRowAttributes['id']);
                $reqRowAttributes['frgn_car_id'] = $item->id;
                $reqRowAttributes['caf_id'] = $currentapp_id;
                $checkreqinremote = $remortServernew->table('cpdo_application_requirement')->where('caf_id',$currentapp_id)->where('req_id',$item->req_id)->first();
                if(!empty($checkreqinremote)){
                    // $remortServernew->table('cpdo_application_forms')->where('frgn_caf_id',$currentapp_id)->update($reqRowAttributes); 
                }else{
                   $remortServernew->table('cpdo_application_requirement')->insert($reqRowAttributes);  
                }
                DB::table('cpdo_application_requirement')->where('id',$item->id)->update(['is_synced' => 1]);
                $cpdo_files = DB::table('cpdo_file')->where('car_id',$item->id)->get();
                foreach($cpdo_files as $itemfile)
                {
                    $cpdofileRowAttributes = get_object_vars($itemfile);
                    unset($cpdofileRowAttributes['id']);
                     //print_r($cpdofileRowAttributes); exit;
                    $checkfileinremote = $remortServernew->table('cpdo_file')->where('car_id',$currentreq_id)->first();
                    if(!empty($checkfileinremote)){
                            $updatearray = array();
                            $filename = "'".$itemfile->cf_name."'";
                            $updatearray['cf_path'] = $itemfile->cf_path;
                           unset($cpdofileRowAttributes['car_id']);
                            $remortServernew->table('cpdo_file')->where('car_id',$currentreq_id)->update(["cf_name" =>$filename]); 
                    }else{
                         $destinationPath =  public_path().'/uploads/cpdo/requirement/'.$itemfile->cf_name;
                            $fileContents = file_get_contents($destinationPath);
                            
                            $remotePath = 'public/uploads/cpdo/requirement/'.$itemfile->cf_name;
                            $error = Storage::disk('remote')->put($remotePath, $fileContents);
                          $remortServernew->table('cpdo_file')->insert($cpdofileRowAttributes);  
                    }
                }
            }
            DB::table('cpdo_application_forms')->where('id',$id)->update(['is_synced' =>1]);
            $remortServernew->commit();
            return $currentapp_id;
        } catch (\Exception $e) {
           echo json_encode($e->getMessage());
            // Rollback the transaction if an exception occurs
            $remortServernew->rollback();
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
      0 =>"es.id",
      1 =>"caf.caf_control_no",
      2 =>"c.rpo_first_name",
      3 =>"caf.caf_type_project",
      4 =>"caf.caf_complete_address"	
    );

    $sql = $this->remortServer->table('cpdo_application_forms AS caf')
   		  ->join('clients AS c', 'c.client_frgn_id', '=', 'caf.client_id') 
        ->leftjoin('cpdo_inspection_reports AS cir', 'caf.id', '=', 'cir.caf_id')
        ->select('cir.id as cirid','caf.id','caf.caf_date','caf.caf_control_no','caf.caf_type_project','caf.top_transaction_type_id','caf.caf_complete_address','caf.caf_brgy_id','c.rpo_first_name','caf.is_approved','c.rpo_custom_last_name','c.rpo_middle_name','caf.csd_id','caf.cs_id','caf.caf_amount','caf.caf_total_amount','caf.is_active')->where('caf.is_approved','<>',1);

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(caf.caf_complete_address)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(caf.caf_type_project)'),'like',"%".strtolower($q)."%");
			});
		}
        if(!empty($barangay)){
            $sql->where('c.p_barangay_id_no',$barangay);
        }
        if(!empty($fromdate) && isset($fromdate)){
            $sql->whereDate('caf_date','>=',trim($fromdate));  
        }
        if(!empty($todate) && isset($todate)){
            $sql->whereDate('caf_date','<=',trim($todate));  
        }
		
		if(!empty($status) && isset($status)){
			$sql->where('caf.is_approved',$status);
		}else{
			$sql->where('caf.is_approved',0);	
		}
		/*  #######  Set Order By  ###### */
      $sql->orderBy('id','DESC');
    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
