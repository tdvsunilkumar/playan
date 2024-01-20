<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BfpInspectionOrder extends Model
{
    //use HasFactory;
	public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }
    public function updateData($id,$columns){
        return DB::table('bfp_inspection_orders')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bfp_inspection_orders')->insert($postdata);
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
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }
    public function getRegions(){
        return DB::table('profile_regions')->select('id','reg_description','reg_region')->where('is_active',1)->get();
    }
    public function getClient(){
        return DB::table('clients')->select('id','rpo_first_name','rpo_middle_name','rpo_custom_last_name','suffix')->where('is_bplo',1)->get();
    }
    public function getHRemployees(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','title','fullname')->where('is_active',1)->get();
    }
	public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
	
    public function getBusiness(){
        return DB::table('bplo_business')->select('id','busn_name')->where('is_active',1)->get();
    }
	
    public function getProvinces(){
        return DB::table('profile_provinces')->select('id','prov_code','prov_desc')->where('is_active',1)->get();
    }
    public function getBfpApplications(){
         return DB::table('bfp_application_forms')->select('id','bff_application_no')->where('bff_status',1)->get();
    }
	public function getBfpAppformid($id){
        return DB::table('bfp_application_forms')->select('id')->where('busn_id','=',$id)->first();
    }
    public function getBfpAppform($id){
        return DB::table('bfp_application_forms')->select('id','ba_business_account_no','p_code','ba_code')->where('id','=',$id)->first();
    }
    public function getBendId($id){
        return DB::table('bplo_business_endorsement')->select('id','busn_id')->where('busn_id','=',$id)->first();
    }
	public function getYearDetails(){
        return DB::table('bfp_inspection_orders')->select('bio_year')->groupBy('bio_year')->orderBy('bio_year','DESC')->get()->toArray(); 
    }
    public function getbploBusiness($id){
        return DB::table('bplo_business')
		->where('bplo_business.id',$id)
		->join('clients AS cli', 'cli.id', '=', 'bplo_business.client_id')
		->select('bplo_business.*','cli.rpo_first_name','cli.rpo_middle_name','cli.rpo_custom_last_name','cli.suffix',DB::raw("CASE 
            WHEN cli.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,'')))
            WHEN cli.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,'')))
            WHEN cli.suffix IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,'')))
            WHEN cli.rpo_first_name IS NULL AND cli.rpo_middle_name IS NULL AND cli.suffix IS NULL THEN COALESCE(cli.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,''))) END as ownar
            "))->first();
    }
    
    
     public function getPrintDetails($id){
         return DB::table('bfp_inspection_orders As io')
		 ->where('io.id',$id)
		 ->join('bplo_business As bp', 'bp.id', '=', 'io.busn_id')
		 ->join('clients As cli', 'cli.id', '=', 'io.client_id')
         ->join('barangays AS b', 'b.id', '=', 'io.brgy_id')
         ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
         ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
         ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
		 ->join('hr_employees AS rhr', 'rhr.id', '=', 'io.bio_recommending_approval')
		 ->select('io.*','bp.*','cli.rpo_first_name','cli.rpo_middle_name','cli.rpo_custom_last_name','cli.suffix','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region')->get();
    } 
	
    
	/* public function getPrintDetails($id){
        return DB::table('bfp_inspection_orders AS io')
            ->join('bfp_application_forms AS baf', 'baf.id', '=', 'io.bff_code')
            ->join('barangays AS b', 'b.id', '=', 'io.brgy_id')
            ->join('clients AS c', 'c.id', '=', 'io.client_id')
            ->join('hr_employees AS rhr', 'rhr.id', '=', 'io.bio_recommending_approval')
            ->join('hr_employees AS ahr', 'rhr.id', '=', 'io.bio_approved')
            ->select('io.id','baf.bff_application_no','c.rpo_first_name','c.rpo_middle_name','c.rpo_custom_last_name','c.suffix','b.brgy_name','io.bio_inspection_no','io.bio_inspection_purpose','io.bio_inspection_duration','bio_remarks','bio_approved_date','rhr.firstname AS rfirst','rhr.middlename AS rmiddle','rhr.lastname AS rlast','rhr.suffix AS rsuffix','ahr.firstname AS afirst','ahr.middlename AS amiddle','ahr.lastname AS alast','ahr.suffix AS asuffix')->where('io.id','=',$id)->get();
    } */

    public function getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year=''){
        return DB::table('bfp_inspection_orders')
		->select('bio_document')
		->where('bend_id',$bbendo_id)
		->where('bio_year',(int)$year)
		->where('busn_id',$busn_id)
		->first();
    }
	public function updateBusinessEndorsement($busn_id,$bbendo_id,$columns,$year=''){
		
        return DB::table('bfp_inspection_orders')
		->where('bend_id',$bbendo_id)
		->where('busn_id',$busn_id)
		->where('bio_year',(int)$year)
		->update($columns);
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        
        $columns = array( 
            0 =>"id",
            1 =>"bio_year",
            2 =>"busn_name",
            3 =>"ownar_name",
            4 =>"brgy_name",
            5 =>"bio_inspection_no",
            6 =>"fullname",  
            7 =>"is_active"
           );
         $sql = DB::table('bfp_inspection_orders AS ins')
			->join('hr_employees AS hre', 'hre.id', '=', 'ins.bio_approved')
			->join('clients AS cli', 'cli.id', '=', 'ins.client_id')
            ->join('bplo_business As bp', 'bp.id', '=', 'ins.busn_id')
            ->Leftjoin('barangays AS b', 'b.id', '=', 'ins.brgy_id')
            ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
            ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
            ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
            ->select('ins.id','ins.*','bp.busn_name','cli.rpo_first_name','cli.rpo_middle_name','cli.rpo_custom_last_name','cli.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region',
            DB::raw("CASE 
            WHEN cli.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,'')))
            WHEN cli.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,'')))
            WHEN cli.suffix IS NULL THEN TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,'')))
            WHEN cli.rpo_first_name IS NULL AND cli.rpo_middle_name IS NULL AND cli.suffix IS NULL THEN COALESCE(cli.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(cli.rpo_first_name,''),' ',COALESCE(cli.rpo_middle_name,''),' ',COALESCE(cli.rpo_custom_last_name,''),', ',COALESCE(cli.suffix,''))) END as ownar_name
            "));
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ins.id)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(ins.bio_year)'),'like',"%".strtolower($q)."%")
                 ->orWhere(DB::raw('LOWER(bp.busn_name)'),'like',"%".strtolower($q)."%")
                 ->orWhere(DB::raw("CONCAT(cli.rpo_first_name, ' ', COALESCE(cli.rpo_middle_name, ''), ' ', COALESCE(cli.rpo_custom_last_name), ', ', cli.suffix)"), 'LIKE', "%{$q}%") 
                 ->orWhere(DB::raw('LOWER(cli.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                 ->orWhere(DB::raw('LOWER(cli.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(bio_inspection_no)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(bio_date)'),'like',"%".strtolower($q)."%")
				  ->orWhere(DB::raw('LOWER(bio_recommending_approval)'),'like',"%".strtolower($q)."%")
                  ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                  ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                  ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                  ->orWhere(DB::raw('LOWER(hre.fullname)'),'like',"%".strtolower($q)."%")
					; 
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ins.id','DESC');

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
     return DB::table('bfp_inspection_orders')->where('id',$id)->update($columns);
    } 
}
