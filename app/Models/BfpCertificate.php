<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;



class BfpCertificate extends Model
{
     public function updateData($id,$columns){
        return DB::table('bfp_certificates')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bfp_certificates')->insert($postdata);
    }
    public function findDataById($id){
        return DB::table('bfp_certificates')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('bfp_certificates')->where('id',$id)->update($columns);
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
    public function getBfpApplications(){
    	 return DB::table('bfp_application_forms')->select('id','bff_application_no')->where('bff_status',1)->get();
    }
    public function getYearDetails(){
        return DB::table('bplo_business_endorsement')->select('bend_year')->groupBy('bend_year')->orderBy('bend_year','DESC')->get()->toArray(); 
    }
    public function getApplicationDetails($busn_id,$bbendo_id,$year=''){
        return DB::table('bfp_certificates')->select('bfpcert_document')->where('endorsing_dept_id',$bbendo_id)->where('bfpcert_year',(int)$year)->where('busn_id',$busn_id)->first();
    }
    public function updateApplication($busn_id,$bbendo_id,$columns,$year=''){
        return DB::table('bfp_certificates')->where('endorsing_dept_id',$bbendo_id)->where('busn_id',$busn_id)->where('bfpcert_year',(int)$year)->update($columns);
    }
    public function getApplicationEdit($busn_id,$bend_id,$year){
         return DB::table('bfp_certificates')->select('*')->where('endorsing_dept_id',(int)$bend_id)->where('busn_id',(int)$busn_id)->where('bfpcert_year',(int)$year)->first();
    }
    
    public function getBploBusinessId(){
        $result=array();
        $sql=DB::table('bplo_business AS busn')
        ->join('bplo_business_endorsement AS bend', 'bend.busn_id', '=', 'busn.id')
        ->select('busn.id','busn.busn_name')->where('busn.is_active',1);
         $sql->whereExists(function ($query) {
               $query->select("busn_id")
                  ->from('bplo_business_endorsement')
                  ->whereRaw('busn_id = busn.id');
        });
        $result=$sql->get()->toArray();
        return $result;
        
       
    }
    public function selectHRemployees($id){
    return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->value('user_id');
    }
    public function getClient(){
         return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->get();
    }
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','firstname','middlename','lastname','suffix','title')->get();
    }
    public function employeeData($id){
         return DB::table('hr_employees AS e')
              ->join('hr_designations AS d', 'd.id', '=', 'e.hr_designation_id')
               ->select('e.id','d.description')->where('e.id','=',$id)->first();
    }
    public function clientData($id,$bend_id,$year){  
        return DB::table('bplo_business AS bs')
               ->join('clients AS c', 'c.id', '=', 'bs.client_id')
               ->Leftjoin('barangays AS b', 'b.id', '=', 'c.p_barangay_id_no')
               ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
               ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
               ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->Leftjoin('bfp_application_assessments AS ass', 'ass.busn_id', '=', 'bs.id')
               ->Leftjoin('bfp_application_forms AS app', 'app.busn_id', '=', 'bs.id')
               ->Leftjoin('bfp_inspection_orders AS ins', 'ins.bff_id', '=', 'app.id')
               ->Leftjoin('bplo_business_endorsement AS bent', 'bent.busn_id', '=', 'bs.id')
               ->select('bs.id','c.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_mobile_no','c.is_active','c.p_telephone_no','c.p_email_address','bs.busn_bldg_area','bs.busn_bldg_total_floor_area','bs.busns_id_no','bent.id AS bendId','c.id AS client_id','b.id AS barangay_id','app.id AS bff_id','ass.id AS bfpas_id','ass.bfpas_total_amount','ass.bfpas_payment_or_no','ass.bfpas_date_paid','ins.id AS bio_id','bs.busn_name')->where('bs.id','=',$id)->where('bent.endorsing_dept_id','=',$bend_id)->where('bent.bend_year','=',$year)->get();

    }

    public function getPrintDetails($id){
        return DB::table('bfp_certificates AS bsc')
               ->Leftjoin('clients AS c', 'c.id', '=', 'bsc.client_id')
               ->Leftjoin('hr_employees AS rhr', 'rhr.id', '=', 'bsc.bfpcert_approved_recommending')
               ->Leftjoin('hr_employees AS ahr', 'ahr.id', '=', 'bsc.bfpcert_approved')
               ->Leftjoin('hr_designations AS rdhr', 'rdhr.id', '=', 'rhr.hr_designation_id')
               ->Leftjoin('hr_designations AS adhr', 'adhr.id', '=', 'ahr.hr_designation_id')
               ->Leftjoin('barangays AS b', 'b.id', '=', 'bsc.bgy_id')
               ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
               ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
               ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->Leftjoin('bfp_application_forms AS app', 'app.id', '=', 'bsc.bff_id')
               ->Leftjoin('bplo_business AS bs', 'bs.id', '=', 'bsc.busn_id')
               ->Leftjoin('bplo_business_endorsement AS bent', 'bent.id', '=', 'bsc.bend_id')
               ->select('bs.id','c.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_mobile_no','c.p_telephone_no','c.p_email_address','bs.busn_bldg_area','bs.busn_bldg_total_floor_area','bs.busns_id_no','bs.busn_name','bent.id AS bendId','c.id AS client_id','b.id AS barangay_id','app.id AS bff_id','app.id AS bff_id','bsc.bfpcert_year','bsc.bfpcert_date_issue','bsc.bfpcert_date_expired','rhr.firstname AS rfirst','rhr.middlename AS rmiddle','rhr.lastname AS rlast','rhr.suffix AS rsuf','ahr.firstname AS afirst','ahr.middlename AS amiddle','ahr.lastname AS alast','ahr.suffix AS asuf','bsc.bfpcert_no','bsc.bfpcert_approved_date','rdhr.description AS rdescription','adhr.description AS adescription','bsc.orno','bsc.oramount','bsc.ordate','bsc.bfpcert_approved_recommending','bsc.bfpcert_approved','bsc.bfpcert_approved_recommending_position','bsc.bfpcert_approved_position')->where('bsc.id','=',$id)->get();
    }
    

    public function InspectionOrder(){
         return DB::table('bfp_inspection_orders')->select('id','ba_business_account_no')->get();
    }


    
    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }

        $columns = array( 
            0 =>"pas.bff_application_no",
            1 =>"bfpcert_year",
            2 =>"busn_name",
            3 =>"c.full_name",
            4 =>"c.full_address",
            5 =>"bfpcert_date_issue",
            6 =>"bfpcert_date_expired",
            7 =>"orno",
            8 =>"ordate",
            9 =>"bfpcert_date_issue",
            10 =>"oramount",
            11 =>"is_active",
            12 =>"bfpcert_date_expired"      
        );

        $sql = DB::table('bfp_certificates AS bsc')
               ->Leftjoin('clients AS c', 'c.id', '=', 'bsc.client_id')
               ->Leftjoin('barangays AS b', 'b.id', '=', 'bsc.bgy_id')
               ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
               ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
               ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->Leftjoin('bfp_application_assessments AS ass', 'ass.id', '=', 'bsc.bfpas_id')
               ->Leftjoin('bfp_application_forms AS app', 'app.id', '=', 'bsc.bff_id')
               ->Leftjoin('bplo_business AS bs', 'bs.id', '=', 'bsc.busn_id')
               ->Leftjoin('bplo_business_endorsement AS bent', 'bent.id', '=', 'bsc.bend_id')
               ->select('bsc.id','c.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','c.full_address','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_mobile_no','c.p_telephone_no','c.p_email_address','bs.busn_bldg_area','bs.busn_bldg_total_floor_area','bs.busns_id_no','bs.busn_name','bent.id AS bendId','c.id AS client_id','b.id AS barangay_id','ass.id AS bfpas_id','app.id AS bff_id','app.id AS bff_id','bsc.bfpcert_year','bsc.bfpcert_date_issue','bsc.bfpcert_date_expired','bsc.bend_id','bsc.busn_id','bsc.endorsing_dept_id','bsc.orno','bsc.oramount','bsc.ordate','bsc.is_active','bsc.recommending_status','bsc.approved_status',DB::raw("CASE 
        WHEN rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,'')))
        WHEN rpo_first_name IS NULL AND rpo_middle_name IS NULL AND suffix IS NULL THEN COALESCE(rpo_custom_last_name,'')
        ELSE TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''))) END as ownar_name"));
            
           if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.full_address)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw("CONCAT(c.rpo_first_name, ' ', COALESCE(c.rpo_middle_name, ''), ' ',c.rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(c.rpo_first_name, ' ', COALESCE(c.rpo_middle_name, ''), ' ', COALESCE(c.rpo_custom_last_name), ', ', c.suffix)"), 'LIKE', "%{$q}%")
                     ->orWhere(DB::raw("CONCAT(b.brgy_name,',',pm.mun_desc)"), 'LIKE', "%{$q}%")    
                    ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(function ($sql) use ($q) {
                          if ($q === 'New' || $q === 'new') {
                              $sql->where('app_code', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'Renew' || $q === 'renew') {
                              $sql->where('app_code', '=', 2); // Condition for Exempt (option 2)
                          }elseif ($q === 'Retire' || $q === 'retire') {
                              $sql->where('app_code', '=', 3); // Condition for Exempt (option 2)
                          }else {
                              $sql->where('app_code', '=', ''); // Condition to return no results for other search terms
                          }
                    })
                   ->orWhere(DB::raw('DATE_FORMAT(bfpcert_date_issue, "%b %d, %Y")'), 'LIKE', "%" . $q . "%")

                    ->orWhere(DB::raw('DATE_FORMAT(bfpcert_date_expired, "%b %d, %Y")'), 'LIKE', "%" . $q . "%")
                    ->orWhere(DB::raw('LOWER(orno)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ordate)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(oramount)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bsc.bfpcert_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bs.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bs.busns_id_no)'),'like',"%".strtolower($q)."%");
                    
            });
        }
       
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('bsc.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
