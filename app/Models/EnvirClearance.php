<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnvirClearance extends Model
{
    //use HasFactory;
	public function scopeActive($query)
    {
        $query->where('ebac_status', 1);
    }
    public function updateData($id,$columns){
        return DB::table('enro_bplo_app_clearances')->where('id',$id)->update($columns);
    }
    public function findDataById($id){
        return DB::table('enro_bplo_app_clearances')->where('id',$id)->first();
    }
    
    
    public function addData($postdata){
        return DB::table('enro_bplo_app_clearances')->insert($postdata);
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
    
    public function getHRemployees(){
        return DB::table('hr_employees')
        ->select('id','firstname','middlename','lastname','fullname','suffix','title')
        ->where('is_active',1)->get();
    }
	public function getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year=''){
        return DB::table('enro_bplo_app_clearances')
		->select('ebac_document')
		->where('bend_id',$bbendo_id)
		->where('ebac_app_year',(int)$year)
		->where('busn_id',$busn_id)
		->first();
    }
	public function updateBusinessEndorsement($busn_id,$bbendo_id,$columns,$year=''){
		
        return DB::table('enro_bplo_app_clearances')->where('bend_id',$bbendo_id)->where('busn_id',$busn_id)->where('ebac_app_year',(int)$year)->update($columns);
    }
	public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
	public function getYearDetails(){
        return DB::table('enro_bplo_app_clearances')->select('ebac_app_year')->groupBy('ebac_app_year')
        ->orderBy('ebac_app_year','DESC')
        ->get()->toArray(); 
    }
    public function getbploBusiness($id){
        return DB::table('bplo_business')
        ->where('bplo_business.id',$id)
        ->join('clients', 'clients.id', '=', 'bplo_business.client_id')
        ->select('bplo_business.*','clients.full_name','clients.rpo_first_name','clients.rpo_middle_name','clients.rpo_custom_last_name','suffix')->first();
    }
	
    public function getPrintDetails($id){
          return DB::table('enro_bplo_app_clearances As io')
	 	 ->where('io.id',$id)
	 	 ->join('bplo_business As bp', 'bp.id', '=', 'io.busn_id')
	 	 ->select('io.*','bp.busn_name','bp.busn_office_main_barangay_id','bp.client_id')->first();
     }  
	public function OwnerName($id){
        return DB::table('clients')
        ->where('id',$id)
		->select('full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','suffix')
        ->first();
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
            0 =>"id",
            1 =>"ebac_app_year",
            2 =>"busn_name",
            3 =>"b.brgy_name",
            4 =>"clients.full_name",
            5 =>"ebpli.ebac_date",
            6 =>"ebpli.ebac_issuance_date",
            7 =>"hre.fullname",
			8 =>"ebpli.ebac_status"
           );
         $sql = DB::table('enro_bplo_app_clearances As ebpli')
		      ->join('bplo_business as bb','bb.id','=','ebpli.busn_id')
			  ->join('clients', 'clients.id', '=','bb.client_id')
			  ->join('hr_employees as hre','hre.id','=','ebpli.ebac_approved_by')
			  ->Leftjoin('barangays AS b', 'b.id', '=', 'bb.busn_office_main_barangay_id')
			  ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
		      ->select('ebpli.*','bb.busn_name','bb.busns_id_no','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','hre.fullname','clients.full_name','clients.rpo_first_name','clients.rpo_middle_name','clients.rpo_custom_last_name','clients.suffix');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ebpli.ebac_app_year)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(clients.full_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(clients.suffix)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(clients.rpo_first_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(clients.rpo_middle_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(clients.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpli.ebac_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpli.ebac_issuance_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(hre.fullname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpli.ebac_status)'),'like',"%".strtolower($q)."%")
				 ;
            });
        }
		if(!empty($year) && isset($year)){
            $sql->where('ebpli.ebac_app_year','=',$year);  
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ebpli.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
	  
	public function getPosition($id){
        $data= DB::table('hr_employees')
		->where('hr_employees.id',$id)
        ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
		->select('hr_designations.description')->first();
		return $data;
    } 
	  
	public function updateActiveInactive($id,$columns){
     return DB::table('enro_bplo_app_clearances')->where('id',$id)->update($columns);
    } 
}
