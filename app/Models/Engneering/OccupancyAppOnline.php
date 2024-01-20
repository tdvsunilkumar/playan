<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use File;
use Illuminate\Support\Facades\Storage;

class OccupancyAppOnline extends Model
{
    public $table = 'eng_occupancy_apps';
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
      return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('occupancy_services as oc','ctot.id','=','oc.tfoc_id')->leftjoin('cto_top_transaction_type as cttt','cttt.id','=','oc.top_transaction_type_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')->where('tfoc_status',1)->where('ctot.tfoc_is_applicable','4')->get();
    }
    public function getRptOwners(){
        return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_rpt',1)->where('rpo_first_name','<>',NULL)->get();
    }
    public function GetOccupancyAppData($id){
        return DB::table('eng_occupancy_apps as app')->leftjoin('clients as c','c.id','=','app.client_id')->select('app.*','c.p_mobile_no','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name')->where('app.id',$id)->first();
    }
    public function updateData($id,$columns){
        return $this->remortServer->table('eng_occupancy_apps')->where('id',$id)->update($columns);
    }

    public function getdataforedit($id){
    	return $this->remortServer->table('eng_occupancy_apps')->where('id',$id)->first();
    }
    public function getBarangaybymunno($munid){
         return DB::table('barangays')
          ->select('id','brgy_name')->where('mun_no',$munid)->get(); 
    }
    public function getOccumunciapality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','8')->limit(1)->first();
    }
    
    public function addData($postdata){
        DB::table('eng_occupancy_apps')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getbuidingpermitdata($id){
        return DB::table('eng_bldg_permit_apps as ebpa')->leftjoin('clients as c','c.id','=','ebpa.p_code')->leftjoin('eng_bldg_fees_details as ebfd','ebpa.id','=','ebfd.ebpa_id')->select('c.id','c.p_mobile_no','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_barangay_id_no','ebfd.ebfd_no_of_storey','ebfd.ebfd_floor_area','ebpa.ebot_id')->where('ebpa.ebpa_permit_no',$id)->first();
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
    public function GetBuildingpermits(){
      return DB::table('eng_bldg_permit_apps')->select('id','ebpa_permit_no')->get();
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
       return DB::table('cto_tfocs')->select('gl_account_id','sl_id','tfoc_surcharge_sl_id','tfoc_surcharge_gl_id')->where('id',$id)->first();
    }
    
    public function deleteFeedetailsrow($id){
        return DB::table('eng_occupancy_fees_details')->where('id',$id)->delete();
    }

    public function GetDefaultfees(){
        return DB::table('eng_occupancy_default_fees')->select('fees_description','id as tfoc_id')->orderby('id','ASC')->get();
    }
    public function GetRequestfees($id){
        return $this->remortServer->table('eng_occupancy_fees_details')->select('id','fees_description','tax_amount','tfoc_id','is_default')->where('eoa_id',$id)->orderby('id','ASC')->get();
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
             return $this->remortServer->table('requirements AS re')
          ->select('re.id','re.req_code_abbreviation','re.req_description')->where('req_dept_eng',1)->Groupby('re.id')->get();; 
    }

    public function getextrafees(){
        return DB::table('occupancy_services AS es')
        ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'es.tfoc_id') 
        ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id')->select('asl.description','afc.id')->where('es.is_main_service','=','0')->get();
    }

    public function getJobRequirementsdefault($tfocid){
           return $this->remortServer->table('eng_occ_services_requirements AS eosr')
          ->join('requirements AS re', 'eosr.req_id', '=', 're.id')
          ->select('eosr.req_id','re.req_code_abbreviation','re.req_description')->where('eosr.tfoc_id',$tfocid)->groupby('eosr.id')->orderby('eosr.orderno','ASC')->get()->toArray();
    }

    public function getJobRequirementsData($id){
           return $this->remortServer->table('occupancy_requirement AS esr')
          ->join('requirements AS re', 'esr.req_id', '=', 're.id')
          ->leftjoin('files_occupancy AS fo', 'esr.frgn_or_id', '=', 'fo.eoar_id')
          ->select('fo.*','esr.req_id','re.req_code_abbreviation','re.req_description')->where('esr.eoa_id',$id)->groupby('esr.id')->get()->toArray();
    } 

    public function getBarangay($id){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return $this->remortServer->table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->where('bgf.id',$id)->get();
    }

    public function approve($id)
    {
        $remortServernew = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $rowToUpdate = $remortServernew->table('eng_occupancy_apps')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_eoa_id=$rowAttributes['frgn_eoa_id'];
            unset($rowAttributes['id']);
            unset($rowAttributes['frgn_eoa_id']);
            $rowAttributes['is_approved']=1;
            $rowAttributes['is_synced']=1;
            DB::table('eng_occupancy_apps')->insert($rowAttributes);
            $l_eoaapplication_id=DB::getPdo()->lastInsertId(); 
            $controlno = str_pad($l_eoaapplication_id, 4, '0', STR_PAD_LEFT); 
            DB::table('eng_occupancy_apps')->where('id',$l_eoaapplication_id)->update(['eoa_application_no' => $controlno]);
            $occupancy_requirment = $remortServernew->table('occupancy_requirement')->where('eoa_id',$frgn_eoa_id)->get();

            $defaultFeesarr = $this->GetDefaultfees();
             foreach ($defaultFeesarr as $key => $value) {
                     $occufeesdetails =array();
                     $occufeesdetails['eoa_id'] =$l_eoaapplication_id;
                     $occufeesdetails['tfoc_id'] = $rowAttributes['tfoc_id'];  
                     $occufeesdetails['agl_account_id'] = $rowAttributes['agl_account_id'];
                     $occufeesdetails['sl_id'] = $rowAttributes['sl_id'];
                     $occufeesdetails['fees_description'] = $value->fees_description;
                     $occufeesdetails['tax_amount'] = "";
                        $occufeesdetails['created_by']=\Auth::user()->id;
                        $occufeesdetails['created_at'] = date('Y-m-d H:i:s');
                        $occufeesdetails['updated_by']=\Auth::user()->id;
                        $occufeesdetails['updated_at'] = date('Y-m-d H:i:s');
                      $this->addoccupancyFeesDetailData($occufeesdetails);
               }

            foreach($occupancy_requirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $frgn_occupancy_reqid=$reqRowAttributes['frgn_or_id']; 
                unset($reqRowAttributes['id']);
                unset($reqRowAttributes['frgn_or_id']);
                $reqRowAttributes['eoa_id'] = $l_eoaapplication_id;
                DB::table('occupancy_requirement')->insert($reqRowAttributes);
                $l_occupancy_req_id=DB::getPdo()->lastInsertId(); 
                $remortServernew->table('occupancy_requirement')->where('id',$item->id)->update(['eoa_id' => $l_eoaapplication_id,'frgn_or_id' => $l_occupancy_req_id]);
                $occupancy_file = $remortServernew->table('files_occupancy')->where('eoar_id',$frgn_occupancy_reqid)->get();
                foreach($occupancy_file as $itemfiles)
                {
                    $occupancyfileRowAttributes = get_object_vars($itemfiles);
                    unset($occupancyfileRowAttributes['id']);
                    unset($occupancyfileRowAttributes['is_synced']);
                    $occupancyfileRowAttributes['eoar_id'] = $l_occupancy_req_id;
                    $occupancyfileRowAttributes['eoa_id'] = $l_eoaapplication_id;
                    $remotePath = 'public/uploads/engineering/occupancy/' . $itemfiles->fe_name;

                    // Retrieve the file contents from the remote server
                    $fileContents = Storage::disk('remote')->get($remotePath);
                    if ($fileContents !== false) {
                        // Define the local path where you want to save the file
                        $localPath = public_path() . '/uploads/engineering/occupancy/'.$itemfiles->fe_name;

                        // Use file_put_contents to save the retrieved file contents locally
                        if (file_put_contents($localPath, $fileContents) !== false) {
                            // File was successfully transferred from remote server to local path
                        } else {
                            // Handle the error if the local file couldn't be saved
                        }
                    } else {
                        // Handle the error if the file couldn't be retrieved from the remote server
                    }    
                    DB::table('files_occupancy')->insert($occupancyfileRowAttributes);
                    $remortServernew->table('files_occupancy')->where('id',$item->id)->update(['eoar_id' => $l_occupancy_req_id]);
                }
            }

            $remortServernew->table('eng_occupancy_apps')->where('id',$id)->update(['eoa_application_no' => $controlno,'frgn_eoa_id' => $l_eoaapplication_id,'is_approved' => 1]);
            DB::commit();
            return $l_eoaapplication_id;
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
            $rowToUpdate = DB::table('eng_occupancy_apps')->where('id',$id)->first();
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_eoa_id=$rowAttributes['frgn_eoa_id'];
            $currentapp_id=$rowAttributes['id'];
            unset($rowAttributes['id']);
            $checkappinremote = $remortServernew->table('eng_occupancy_apps')->where('frgn_eoa_id',$currentapp_id)->first();
            if(!empty($checkappinremote)){
                unset($rowAttributes['frgn_eoa_id']);
                unset($rowAttributes['is_approved']);
                unset($rowAttributes['is_synced']);
                $remortServernew->table('eng_occupancy_apps')->where('frgn_caf_id',$currentapp_id)->update($rowAttributes);  
            }else{
                $rowAttributes['frgn_eoa_id'] = $currentapp_id;
                $rowAttributes['is_approved'] = '1';
                $remortServernew->table('eng_occupancy_apps')->insert($rowAttributes); 
            }

            $occupancy_requirment = DB::table('occupancy_requirement')->where('eoa_id',$id)->get();

            foreach($occupancy_requirment as $item)
            {
                $reqRowAttributes = get_object_vars($item);
                $currentreq_id=$reqRowAttributes['id']; 
                unset($reqRowAttributes['id']);
                $reqRowAttributes['frgn_or_id'] = $item->id;
                $reqRowAttributes['eoa_id'] = $currentapp_id;
                $checkreqinremote = $remortServernew->table('occupancy_requirement')->where('eoa_id',$currentapp_id)->where('req_id',$item->req_id)->first();
                if(!empty($checkreqinremote)){
                    // $remortServernew->table('cpdo_application_forms')->where('frgn_caf_id',$currentapp_id)->update($reqRowAttributes); 
                }else{
                   $remortServernew->table('occupancy_requirement')->insert($reqRowAttributes);  
                }
                 DB::table('occupancy_requirement')->where('id',$item->id)->update(['is_synced' => 1]);

                $occupancy_file = DB::table('files_occupancy')->where('eoar_id',$item->id)->get();
                foreach($occupancy_file as $itemfile)
                {
                    $occupancyfileRowAttributes = get_object_vars($itemfile);
                    unset($occupancyfileRowAttributes['id']);
                    $occupancyfileRowAttributes['eoar_id'] = $currentreq_id;
                    $occupancyfileRowAttributes['eoa_id'] = $currentapp_id;
                    $checkfileinremote = $remortServernew->table('files_occupancy')->where('eoar_id',$currentreq_id)->first();
                    if(!empty($checkfileinremote)){
                            $updatearray = array();
                            $updatearray['fe_name'] = $itemfile->fe_name;
                            $updatearray['fe_path'] = $itemfile->fe_path;
                           unset($occupancyfileRowAttributes['eoar_id']);
                            $remortServernew->table('files_occupancy')->where('eoar_id',$currentreq_id)->update($updatearray); 
                    }else{
                         $destinationPath =  public_path().'/uploads/engineering/occupancy/'.$itemfile->fe_name;
                            $fileContents = file_get_contents($destinationPath);
                            
                            $remotePath = 'public/uploads/engineering/occupancy/'.$itemfile->fe_name;
                            $error = Storage::disk('remote')->put($remotePath, $fileContents);
                          $remortServernew->table('files_occupancy')->insert($occupancyfileRowAttributes);  
                    }  
                }
            }

            DB::table('eng_occupancy_apps')->where('id',$id)->update(['is_synced' => 1]);
            DB::commit();
            return $l_eoaapplication_id;
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
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
      0 =>"eoc.id",
      1 =>"ebpa_id",
      2 =>"eoc.client_id",
      3 =>"eoc.eoa_application_type",
      4 =>"eoc.is_active",
     	
    );

    $sql = $this->remortServer->table('eng_occupancy_apps AS eoc')
          ->join('clients AS cl', 'eoc.client_id', '=', 'cl.client_frgn_id')
          ->select('eoc.id','eoc.created_at','eoc.ebpa_id','eoc.client_id','eoc.location_brgy_id','eoc.is_approved','eoc.eoa_application_type','cl.rpo_custom_last_name','cl.rpo_first_name','cl.rpo_middle_name','eoc.is_active','eoc.eoa_application_no','eoc.eoa_total_fees','eoc.top_transaction_type_id')->where('eoc.is_approved','<>',1);

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(eoc.eoa_application_type)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.ebpa_id)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.client_id)'),'like',"%".strtolower($q)."%")
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
         if(!empty($barangay)){
            $sql->where('cl.p_barangay_id_no',$barangay);
        }
        if(!empty($fromdate) && isset($fromdate)){
            $sql->whereDate('eoc.created_at','>=',trim($fromdate));  
        }
        if(!empty($todate) && isset($todate)){
            $sql->whereDate('eoc.created_at','<=',trim($todate));  
        }
        if(!empty($status) && isset($status)){
        $sql->where('eoc.is_approved',$status);
        }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('eoc.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
   
}
