<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BfpApplicationForm extends Model
{
    
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('bfp_application_forms')->where('id',$id)->update($columns);
    }
    public function addData($data){
        DB::table('bfp_application_forms')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function findDataById($id){
        return DB::table('bfp_application_forms')->where('id',$id)->first();
    }
    public function updateDataInspection($id,$dataArr){
        return DB::table('bfp_inspection_orders')->where('bff_id',$id)->update($dataArr);
    }
    public function addRequirment($postdata){
         return DB::table('bfp_application_requirements')->insert($postdata);
        // return DB::getPdo()->lastInsertId();
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
    
    public function addDataInspection($postdata){
        return DB::table('bfp_inspection_orders')->insert($postdata);
    }
    public function getApplicationEdit($busn_id,$bend_id,$year){
        return DB::table('bfp_application_forms')->select('*')->where('bend_id',(int)$bend_id)->where('busn_id',(int)$busn_id)->where('bff_year',(int)$year)->first();
    }
    public function updateAssesmentDetail($id,$columns){
         return DB::table('cto_application_assessments')->where('id',$id)->update($columns);
    }
    public function getBploBusinessId($busn_id){
        return DB::table('bplo_business')->select('id')->where('id',$busn_id)->get();
    }
    
    public function getHrEmplyeesAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('hr_employees AS h')
             ->select('h.id','h.firstname','h.middlename','h.lastname','h.fullname','h.suffix','h.title');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('h.is_active',1);
            $sql->Where('h.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(h.fullname)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('h.fullname','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getEmployee($user_id){
         return DB::table('hr_employees')->where('user_id','!=',$user_id)->select('id','firstname','middlename','lastname','suffix','fullname','title')->get();
         //return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','fullname','title')->get();
    }
    public function getEmployeeUser($user_id){
         return DB::table('hr_employees')->where('user_id','=',$user_id)->select('id','firstname','middlename','lastname','suffix','fullname','title')->get();
    }
    public function getRefreshEmployee(){
         return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','title')->orderBy('id','DESC')->get();
    }

    public function getCitizen(){
         return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_fire_safety',1)->where('is_active',1)->get();
    }
    public function getRefreshCitizen(){
         return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_fire_safety',1)->where('is_active',1)->orderBy('id','DESC')->get();
    }
    public function getRepresentative($id){
        return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_fire_safety',1)->where('is_active',1)->where('id',(int)$id)->first();
    }
    public function getClientDetails($id){
        return DB::table('bplo_business AS bus')
               ->join('clients AS c', 'c.id', '=', 'bus.client_id')
               ->select('c.id','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix')->where('bus.id',(int)$id)->get();
    }
    public function employeeData($id){
         return DB::table('hr_employees AS e')
              ->join('hr_designations AS d', 'd.id', '=', 'e.hr_designation_id')
               ->select('e.id','d.description')->where('e.id','=',$id)->first();
    }
    
    public function getApplicationDetails($busn_id,$bbendo_id,$year=''){
        return DB::table('bfp_application_forms')->select('bff_document')->where('bend_id',$bbendo_id)->where('bff_year',(int)$year)->where('busn_id',$busn_id)->first();
    }
    public function updateApplication($busn_id,$bbendo_id,$columns,$year=''){
        return DB::table('bfp_application_forms')->where('bend_id',$bbendo_id)->where('busn_id',$busn_id)->where('bff_year',(int)$year)->update($columns);
    }
    public function getaccountnumbers(){
         return DB::table('bplo_business')->select('id','busn_registration_no')->where('is_active',1)->get();
    }
    public function getaccountnumbersdata($id){
         return DB::table('bplo_business')->select('id','busn_registration_no')->where('is_active',1)->where('id','=',$id)->get();
    }
    public function getaccountnumbersedit($id){
         return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('id',(int)$id)->where('is_active',1)->get();
    }
    public function getBussinessDetals($id){
        return DB::table('bfp_application_forms')->select('*')->where('id',(int)$id)->get();
    }
    public function getnatureofBussinessCodes(){
         return DB::table('psic_subclasses')->select('id','subclass_code')->where('is_active',1)->get();
    }
    public function getbussinesscalsification(){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->get();
    }
    public function getOcupancy(){
        return DB::table('bfp_occupancy_types')->select('id','bot_occupancy_type')->where('is_active',1)->get();
    }
    public function getOcupancyId($id){
         return DB::table('bfp_occupancy_types')->select('id','bot_occupancy_type')->where('is_active',1)->where('id',(int)$id)->get();
    }
    public function getocuppancyDetails($id){
        return DB::table('bfp_occupancy_types')->select('id','bot_occupancy_type')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function bfpgetBussinessData($id){
        return DB::table('psic_subclasses')->select('id','subclass_description')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getTasktypesData($id){
        return DB::table('tax_types')->select('id','tax_type_description')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getClasificationDesc($id){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('id',(int)$id)->first();
    }
    public function getActivityDesc($id){
        return DB::table('bplo_business_activities')->select('id','bba_desc')->where('id',(int)$id)->first();
    }
    
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
    }

    public function getApplicationType(){
        return DB::table('bfp_application_type')->select('id','btype_name','btype_description')->where('btype_status',1)->get();
    }
    public function getPurposeDetails($id){
        return DB::table('bfp_application_purpose')->select('id','bap_desc')->where('btype_id',(int)$id)->where('bap_status',1)->first();
    }
    public function getBarangyaDetails(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
    
    public function getPurpose(){
        return DB::table('bfp_application_purpose')->select('id','bap_desc')->where('bap_status',1)->get();
    }
    public function getCategoryDetails($id){
        return DB::table('bplo_business')->select('id','app_code')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getCategoty(){
        return DB::table('bfp_application_category')->select('id','bac_desc')->where('bac_status',1)->get();
    }

    public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description')->where('psic.busn_id',(int)$id)->get();
    }
    public function getCategoryRequirment($busn_id,$bend_id,$id,$year){
        $result= DB::table('bfp_requirements AS bfp')
            ->join('requirements AS r', 'r.id', '=', 'bfp.req_id')
            ->select('r.id','r.req_code_abbreviation','r.req_description',
                DB::raw("(SELECT bfr_document_file FROM bfp_application_requirements AS ar 
                  WHERE ar.req_id = r.id AND busn_id=".(int)$busn_id." AND bend_id=".(int)$bend_id."  AND category_type=".(int)$id." AND YEAR(created_at)=".(int)$year."
                ) as bfr_document_file"),
                DB::raw("(SELECT req_id FROM bfp_application_requirements AS ar 
                  WHERE ar.req_id = r.id AND busn_id=".(int)$busn_id." AND bend_id=".(int)$bend_id." AND category_type=".(int)$id." AND YEAR(created_at)=".(int)$year."
                ) as req_id")
            )->where('bfp.bac_id',$id)->where('bac_status',1);
        return $result->get();
    }
    public function getCategoryRequirmentPrint($busn_id,$bend_id,$id){
       $result= DB::table('bfp_requirements AS bfp')
            ->join('requirements AS r', 'r.id', '=', 'bfp.req_id')
            ->select('r.id','r.req_code_abbreviation','r.req_description',
                DB::raw("(SELECT bfr_document_file FROM bfp_application_requirements AS ar 
                  WHERE ar.req_id = r.id AND busn_id=".(int)$busn_id." AND bend_id=".(int)$bend_id."
                ) as bfr_document_file"),
                DB::raw("(SELECT req_id FROM bfp_application_requirements AS ar 
                  WHERE ar.req_id = r.id AND busn_id=".(int)$busn_id." AND bend_id=".(int)$bend_id."
                ) as req_id")
            )->where('bfp.bac_id',$id)->where('bac_status',1);
       
        return $result->get();
        
    }
    public function checkRequirdmentRequietExit($columns){
        return DB::table('bfp_application_requirements')->select('id')->where('req_id',$columns['req_id'])->where('bff_id',$columns['bff_id'])->get()->toArray();
    }
    
    public function updateRequiredmentRelationData($id,$columns){
        return DB::table('bfp_application_requirements')->where('id',$id)->update($columns);
    }
    public function getrequirementRelation($busn_id,$bend_id,$category,$year){
        return DB::table('bfp_application_requirements AS ar')
          ->join('requirements AS r', 'r.id', '=', 'ar.req_id')
          ->select('r.id','req_id','bfr_document_file','r.req_description')->where('ar.busn_id',$busn_id)->where('ar.bend_id',$bend_id)->whereYear('ar.created_at',$year)->where('ar.category_type',$category)->get()->toArray();

    }
    public function addbfpassessment($data){
        DB::table('bfp_application_assessments')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function addbfpassessmentsfees(){
        DB::table('bfp_application_assessment_fees')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function getClassifications($tax_type_id){
        $sql = DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1);
        if($tax_type_id>0){
            $sql->where('tax_type_id', '=', $tax_type_id);
        }
        return $sql->get();
    }
    public function getActivitybyClass($class_id){
        $sql = DB::table('bplo_business_activities')->select('id','bba_code','bba_desc')->where('is_active',1);
        if($class_id>0){
            $sql->where('business_classification_id', '=', $class_id);
        }
        return $sql->get();
    }
    public function getEngneeringFee(){
        $sql =  DB::table('bplo_business_engg_fees AS bbef')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bbef.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bbef.tax_type_id')
              ->select('bbef.id','bbef.fee_code AS code','tc.tax_class_desc','tax_type_short_name AS description','bbef.is_active','bof_default_amount AS amount');
          return $sql->get();     
    }
     public function getEngneeringFeebyid($id){
        $sql =  DB::table('bplo_business_engg_fees AS bbef')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bbef.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bbef.tax_type_id')
              ->select('bbef.id','bbef.fee_code AS code','tc.tax_class_desc','top','tax_type_short_name AS description','bbef.is_active','bof_default_amount AS amount')
              ->where('bbef.id', '=', $id);
          return $sql->get();     
    }

    public function geFeemasters(){
           return DB::table('bfp_fees_masters')->get();
    }

    public function getPermitfees($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion){
        $sql = DB::table('bplo_business_permitfees')->select('id','bpf_code AS code','bpt_permit_fee_amount AS amount','bpt_revenue_code AS description','bpt_permit_fee_amount','bpt_capital_asset_minimum','bpt_capital_asset_maximum')->where('is_active',1);
        if($taxtypeid>0){
            $sql->where('tax_type_id', '=', $taxtypeid);
        }if($classificationid>0){
            $sql->where('bbc_classification_code', '=', $classificationid);
        }
        if($activityid>0){
            $sql->where('bba_code', '=', $activityid);
        }
        if($noofworker>0){
            $sql->where('bpt_workers_no_minimum','<=', $noofworker);
            $sql->where('bpt_workers_no_maximum','>=', $noofworker);
        }
        return $sql->offset(0)->limit(1)->get();
    }
     public function getPermitfees2($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion){
        $sql = DB::table('bplo_business_permitfees')->select('id','bpf_code AS code','bpt_permit_fee_amount AS amount','bpt_revenue_code AS description','bpt_permit_fee_amount','bpt_capital_asset_minimum','bpt_capital_asset_maximum')->where('is_active',1);
        if($taxtypeid>0){
            $sql->where('tax_type_id', '=', $taxtypeid);
        }if($classificationid>0){
            $sql->where('bbc_classification_code', '=', $classificationid);
        }
        if($activityid>0){
            $sql->where('bba_code', '=', $activityid);
        }
        if($capitaliztion>0){
            $sql->where('bpt_capital_asset_minimum','<=', $capitaliztion);
            $sql->where('bpt_capital_asset_maximum','>=', $capitaliztion);
        }
        return $sql->offset(0)->limit(1)->get();
    }
    public function getGarbageDrodown($taxtypeid,$classificationid,$activityid,$areaused){
        $sql = DB::table('bplo_business_garbage_fees as bgf')->join('bplo_business_activities AS ba', 'ba.id', '=', 'bgf.bba_code')->select('bgf.id','bgf_code AS code','bba_desc AS description','bgf_fee_amount  AS amount')->where('bgf.is_active',1);
        if($taxtypeid>0){
            $sql->where('bgf.tax_type_id', '=', $taxtypeid);
        }if($classificationid>0){
            $sql->where('bgf.bbc_classification_code', '=', $classificationid);
        }
        if($activityid>0){
            $sql->where('bgf.bba_code', '=', $activityid);
        }
        if($areaused>0){
            $sql->where('bgf.bgf_area_minimum','<=', $areaused);
            $sql->where('bgf.bgf_area_maximum','>=', $areaused);
        }
        return $sql->get();
    }
    
    public function getSanitaryDrodown($taxtypeid,$classificationid,$activityid,$areaused){
        $sql = DB::table('bplo_business_sanitaryfees')->select('id','bsf_code AS code','bsf_revenue_code AS description','bsf_fee_amount AS amount','bsf_area_minimum','bsf_area_maximum')->where('is_active',1);
        if($taxtypeid>0){
            $sql->where('tax_type_id', '=', $taxtypeid);
        }if($classificationid>0){
            $sql->where('bbc_classification_code', '=', $classificationid);
        }
        if($activityid>0){
            $sql->where('bba_code', '=', $activityid);
        }
        if($areaused>0){
            $sql->where('bsf_area_minimum','<=', $areaused);
            $sql->where('bsf_area_maximum','>=', $areaused);
        }
        return $sql->offset(0)->limit(1)->get();
    }
    public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name','tax_class_type_code')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        return $sql->get();
    }



    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $isapproved=$request->input('approve');
        $startdate =$request->input('crdate');
        $year = $request->input('year');

        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }

        $columns = array( 
            0 =>"pas.bff_application_no",
            1 =>"ba_business_account_no",
            2 =>"pa.ba_business_name",
            3 =>"ba_address_house_lot_no",
            4 =>"bot_occupancy_type",
            5 =>"bff_no_of_storey"     
        );

        $sql = DB::table('bfp_application_forms AS pas')
            ->join('bplo_applications AS pa', 'pa.id', '=', 'pas.ba_business_account_no')
            ->leftjoin('profiles AS p', 'p.id', '=', 'pas.profile_id')
            ->join('bfp_occupancy_types AS o', 'o.id', '=', 'pas.bot_code')
            ->select('pas.id','pas.bff_status','o.bot_occupancy_type','pas.bff_no_of_storey','pas.bff_application_no','pa.ba_business_account_no','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba_address_subdivision','p_complete_name_v1','p_family_name','ba_date_started');

        $sql->where('pa.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(pas.ba_business_account_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pa.ba_business_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ba_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p_complete_name_v1)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ba_address_street_name)'),'like',"%".strtolower($q)."%");
                    
            });
        }
        if(isset($isapproved)){
            $sql->where('pa.is_approved', '=', trim($isapproved));
        } 
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('ba_date_started','=',trim($startdate));  
        }
        if(!empty($year) && isset($year)){
            $sql->whereYear('ba_date_started','=',trim($year));  
        }
            
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('pas.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
     }
     public function getprogiledata($id){  
        return DB::table('profiles')->join('bplo_applications as pa', 'pa.profile_id', '=', 'profiles.id')->select('pa.id','ba_business_account_no','pa.app_type_id','ba_building_property_index_number','ba_taxable_owned_truck_wheeler_10above','ba_taxable_owned_truck_wheeler_6above','ba_taxable_owned_truck_wheeler_4above','ba_registration_ctc_no',DB::raw('DATE(ba_registration_ctc_issued_date) AS ba_registration_ctc_issued_date'),'ba_registration_ctc_place_of_issuance',DB::raw('DATE(pa.ba_date_started) AS applicationdate'),'ba_registration_ctc_amount_paid','ba_locational_clearance_no',DB::raw('DATE(ba_locational_clearance_date_issued) AS ba_locational_clearance_date_issued'),'ba_bureau_domestic_trade_no',DB::raw('DATE(ba_bureau_domestic_trade_date_issued) AS ba_bureau_domestic_trade_date_issued'),'ba_sec_registration_no','ba_sec_registration_date_issued','ba_dti_no','ba_dti_date_issued','ba_building_property_index_number','profiles.id as profile_id','p_first_name','ba_cover_year','p_middle_name','p_family_name','profiles.ba_code','p_code','p_telephone_no','p_mobile_no','p_email_address','p_fax_no','p_tin_no','p_address_street_name','p_address_subdivision','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba_building_total_area_occupied','ba_building_is_owned','barangay_id','pa.brgy_name')->where('pa.id','=',$id)->get();
    }

    
    public function clientData($id){  
        return DB::table('bplo_business AS bs')
               ->join('clients AS c', 'c.id', '=', 'bs.client_id')
               ->Leftjoin('barangays AS b', 'b.id', '=', 'c.p_barangay_id_no')
               ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
               ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
               ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->Leftjoin('bplo_business_endorsement AS bent', 'bent.busn_id', '=', 'bs.id')
               ->Leftjoin('rpt_properties AS rp', 'rp.id', '=', 'bs.rp_code')
               ->select('bs.id','rp.rp_building_no_of_storey','c.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','c.p_mobile_no','c.is_active','c.p_telephone_no','c.p_email_address','bs.busn_bldg_area','bs.busn_bldg_total_floor_area','bs.busns_id_no','bs.busn_name','bent.id AS bendId','c.id AS client_id','b.id AS barangay_id')->where('bs.id','=',$id)->get();

    }
    
    public function getChequeDetails($id){
        return DB::table('bfp_application_forms AS baf')
            ->leftjoin('clients AS c', 'c.id', '=', 'baf.client_id')
            ->leftjoin('clients AS ct', 'ct.id', '=', 'baf.bff_representative_id')
            ->leftjoin('bfp_occupancy_types AS o', 'o.id', '=', 'baf.bot_occupancy_type')
            ->leftjoin('hr_employees AS hr', 'hr.id', '=', 'baf.bff_verified_by')
            ->leftjoin('hr_employees AS cr', 'cr.id', '=', 'baf.bff_certified_by')
            ->leftjoin('bplo_business AS bs', 'bs.id', '=', 'baf.busn_id')
            ->leftjoin('hr_designations AS d', 'd.id', '=', 'cr.hr_designation_id')
            ->leftjoin('bplo_business_endorsement AS bent', 'bent.id', '=', 'baf.bend_id')
            ->Leftjoin('barangays AS b', 'b.id', '=', 'baf.barangay_id')
            ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
            ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
            ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
            ->select('baf.id','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','baf.bff_application_no','baf.bff_date','baf.bff_certified_position','baf.bff_year','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix','ct.rpo_first_name AS ctrpo_first_name','ct.rpo_middle_name AS ctrpo_middle_name','ct.rpo_custom_last_name AS ctrpo_custom_last_name','ct.suffix AS ctsuffix','o.bot_occupancy_type','c.p_telephone_no','c.p_email_address','baf.bff_no_of_storey','baf.busn_bldg_total_floor_area','hr.firstname','hr.middlename','hr.lastname','hr.title','hr.suffix AS hrsuffix','cr.firstname AS first','cr.title AS crtitle','cr.middlename AS middle','cr.lastname AS last','cr.suffix AS suff','baf.bff_veridifed_date','baf.bff_cro_date','baf.bff_cro_in','baf.bff_cro_out','baf.bff_fca_date','baf.bff_fca_in','baf.bff_fca_out','baf.bff_fcca_date','baf.bff_fcca_in','baf.bff_fcca_out','baf.bff_cfses1_date','baf.bff_cfses1_in','baf.bff_cfses1_out','baf.bff_fsi_date','baf.bff_fsi_in','baf.bff_fsi_out','baf.bff_cfses2_date','baf.bff_cfses2_in','baf.bff_cfses2_out','baf.bff_cfm_mfm_date','baf.bff_cfm_mfm_in','baf.bff_cfm_mfm_out','bs.busn_name','baf.busn_id','baf.bend_id','baf.bff_certified_date','d.description','baf.bff_req_new_business','baf.bff_req_renew_business','hr.user_id AS verified_user_id','cr.user_id AS certified_user_id')
            ->where('baf.id','=',$id)->get();
    }
    
    public function getProgiledataForedit($id){  
        return DB::table('profiles')->leftjoin('bplo_applications as pa', 'pa.profile_id', '=', 'profiles.id')->join('bfp_application_forms AS pas','pas.ba_business_account_no', '=', 'pa.id')->select('pas.id','pas.ba_business_account_no','pa.app_type_id','profiles.id as profile_id','p_first_name','ba_cover_year','p_middle_name','p_family_name','p_telephone_no','p_mobile_no','p_email_address','p_fax_no','p_tin_no','p_address_street_name','p_address_subdivision','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','pas.subclass_code','pas.bff_no_of_storey','pas.bff_representative_code','pas.bff_cro_date','bff_cro_in','bff_cro_out','pas.bff_fca_date','pas.bff_fca_in','pas.bff_fca_out','pas.bff_fcca_date','pas.bff_fcca_in','pas.bff_fcca_out','pas.bff_cfses1_date','pas.bff_cfses1_in','pas.bff_cfses1_out','pas.bff_application_type','pas.bff_application_no','pas.bff_fsi_date','pas.bff_fsi_in','pas.bff_fsi_out','pas.bff_cfses2_date','pas.bff_cfses2_in','pas.bff_cfses2_out','pas.bff_cfm_mfm_date','pas.bff_cfm_mfm_in','pas.bff_cfm_mfm_out','pas.bff_verified_by','pas.bff_veridifed_date','pas.ba_building_total_area_occupied','pas.p_code','pas.bot_code','pas.bot_occupancy_type','pas.bff_req_occupancy_fsic','pas.bff_req1','pas.bff_req2','pas.bff_req3','pas.bff_req4','pas.bff_req5','pas.ba_code','pas.bff_req_new_business','pas.bff_req6','pas.bff_req7','pas.bff_req8','pas.bff_req9','pas.bff_req_renew_business','pas.bff_req10','pas.bff_req11','pas.bff_req12','pas.bff_req13','pas.bff_req1_file','pas.bff_req2_file','pas.bff_req3_file','pas.bff_req4_file','pas.bff_req5_file','pas.bff_req6_file','pas.bff_req7_file','pas.bff_req8_file','pas.bff_req9_file','pas.bff_req10_file','pas.bff_req11_file','pas.bff_req12_file','pas.bff_req13_file','ba_building_is_owned','barangay_id','pa.brgy_name')->where('pas.id','=',$id)->first();
    }

    public function getUserBusinessDetails($id){
        return DB::table('bfp_application_forms AS baf')
            ->leftjoin('bplo_assessments AS ba', 'baf.profile_id', '=', 'ba.profile_id')
            ->leftjoin('bplo_applications AS pa', 'ba.application_id', '=', 'pa.id')
            ->leftjoin('treasurer_cashier_pos AS tcp', 'ba.id', '=', 'tcp.bas_id')
           ->leftjoin('profiles AS p', 'p.id', '=', 'baf.profile_id')
            ->select('baf.id','tcp.totaltax_due','tcp.subtotal','pa.id as baid','ba.application_id','ba.profile_id','ba.tax_class_id','ba.tax_type_id','pa.barangay_id as brgycode','pa.brgy_name as barangayname','baf.bff_application_type','baf.bff_application_no','baf.bff_year','tcp.checkamount_paid','tcp.cashamount_paid','tcp.order_number','ba.ba_business_account_no','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba.ba_building_total_area_occupied','ba.no_of_personnel','ba_date_started','tcp.ba_cover_year','engneering_feeid','p.p_complete_name_v1','p_first_name','p_middle_name','p_family_name')
            ->where('baf.id','=',$id)->get();
    }


    

    public function getAssesAccountNumbers(){
        return DB::table('bplo_assessments AS pas')
            ->join('bplo_applications AS pa', 'pas.application_id', '=', 'pa.id')
            ->select('pas.application_id','pa.ba_business_account_no')->get();
    }
    public function getApplicationAssessments($id){
        return DB::table('cto_application_assessments')
            ->select('permit_amount','garbage_amount','sanitary_amount')
            ->where('bplo_assessment_id','=',$id)->get();
    }
    public function getAllFeeMaster(){
        return DB::table('allfeemaster')
            ->select('id','fee_name')
            ->where('is_active',1)->get()->toArray();
    }
}
