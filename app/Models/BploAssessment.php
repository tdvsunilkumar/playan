<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BploAssessment extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('bplo_assessments')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bplo_assessments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function addAssesmentDetail($postdata){
    	DB::table('cto_application_assessments')->insert($postdata);
    }
    public function updateAssesmentDetail($id,$columns){
    	 return DB::table('cto_application_assessments')->where('id',$id)->update($columns);
    }
    public function getaccountnumbers(){
    	 return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }
     public function getaccountnumbersedit($id){
    	 return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('id',(int)$id)->where('is_active',1)->get();
    }
    public function getnatureofBussinessCodes(){
    	 return DB::table('psic_subclasses')->select('id','subclass_code')->where('is_active',1)->get();
    }
    public function getbussinesscalsification(){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->get();
    }
    public function getBussinessData($id){
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
    public function getBussinessDetals($id){
        return DB::table('cto_application_assessments')->select('*')->where('bplo_assessment_id',(int)$id)->get();
    }
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('is_active',1)->get();
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
            0 =>"ba_business_account_no",
            1 =>"pa.ba_business_name",
            2 =>"ba_address_house_lot_no",
            3 =>"pas.app_type",
            4 =>"pas.is_active"     
        );

        $sql = DB::table('bplo_assessments AS pas')
            ->join('bplo_applications AS pa', 'pa.id', '=', 'pas.application_id')
            ->join('profiles AS p', 'p.id', '=', 'pas.profile_id')
            ->join('pbloapplicationtypes AS pt', 'pt.id', '=', 'pas.app_type')
            ->select('pas.id','pt.app_type','pas.ba_business_account_no','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba_address_subdivision','pas.is_active','p_complete_name_v1','ba_date_started');

        //$sql->where('pa.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(pas.ba_business_account_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pa.ba_business_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ba_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p_complete_name_v1)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ba_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ba_address_subdivision)'),'like',"%".strtolower($q)."%");
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
        return DB::table('profiles')
                   ->join('bplo_applications as pa', 'pa.profile_id', '=', 'profiles.id')
                   ->select('pa.id','ba_business_account_no','pa.app_type_id','ba_building_property_index_number','ba_taxable_owned_truck_wheeler_10above','ba_taxable_owned_truck_wheeler_6above','ba_taxable_owned_truck_wheeler_4above','ba_registration_ctc_no',DB::raw('DATE(ba_registration_ctc_issued_date) AS ba_registration_ctc_issued_date'),'ba_registration_ctc_place_of_issuance',DB::raw('DATE(pa.ba_date_started) AS applicationdate'),'ba_registration_ctc_amount_paid','ba_locational_clearance_no',DB::raw('DATE(ba_locational_clearance_date_issued) AS ba_locational_clearance_date_issued'),'ba_bureau_domestic_trade_no',DB::raw('DATE(ba_bureau_domestic_trade_date_issued) AS ba_bureau_domestic_trade_date_issued'),'ba_sec_registration_no','ba_sec_registration_date_issued','ba_dti_no','ba_dti_date_issued','ba_building_property_index_number','profiles.id as profile_id','p_first_name','ba_cover_year','p_middle_name','p_family_name','p_telephone_no','p_fax_no','p_tin_no','p_address_street_name','p_address_subdivision','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba_building_total_area_occupied','ba_building_is_owned','barangay_id','pa.brgy_name')->where('pa.id','=',$id)->get();
    }

    public function getProgiledataForedit($id){  
        return DB::table('profiles')->join('bplo_applications as pa', 'pa.profile_id', '=', 'profiles.id')->join('bplo_assessments AS pas','pas.application_id', '=', 'pa.id')->select('pas.id','pas.application_id','pas.lessor','pas.lessoraddress','pas.administrator','pas.rentalstart','pas.presentrate','pas.ba_business_account_no','pa.app_type_id','pas.ba_taxable_owned_truck_wheeler_10above','pas.ba_taxable_owned_truck_wheeler_6above','pas.ba_taxable_owned_truck_wheeler_4above','big','small','no_of_personnel','ba_building_property_index_number','ba_registration_ctc_no',DB::raw('DATE(ba_registration_ctc_issued_date) AS ba_registration_ctc_issued_date'),DB::raw('DATE(pa.ba_date_started) AS applicationdate'),'ba_registration_ctc_place_of_issuance','ba_registration_ctc_amount_paid','ba_locational_clearance_no',DB::raw('DATE(ba_locational_clearance_date_issued) AS ba_locational_clearance_date_issued'),'ba_bureau_domestic_trade_no',DB::raw('DATE(ba_bureau_domestic_trade_date_issued) AS ba_bureau_domestic_trade_date_issued'),'ba_sec_registration_no','ba_sec_registration_date_issued','ba_dti_no','ba_dti_date_issued','ba_building_property_index_number','profiles.id as profile_id','p_first_name','ba_cover_year','p_middle_name','p_family_name','p_telephone_no','p_fax_no','p_tin_no','p_address_street_name','p_address_subdivision','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','pas.ba_building_total_area_occupied','ba_building_is_owned','barangay_id','pa.brgy_name','engneeringfee_description','engneering_amount','engneering_code','engneering_feeid')->where('pas.id','=',$id)->first();
    }

    public function getUserBusinessDetails($id){
        return DB::table('bplo_assessments AS pas')
            ->join('bplo_applications AS pa', 'pas.application_id', '=', 'pa.id')
            ->join('profiles AS p', 'p.id', '=', 'pas.profile_id')
            ->select('pas.id','pas.application_id','pas.ba_business_account_no','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','pa.ba_building_total_area_occupied','pas.no_of_personnel','p_complete_name_v1','ba_date_started','pa.ba_cover_year','engneering_feeid')
            ->where('pas.id','=',$id)->get();
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
            ->where('is_active',1)->get();
    }
}
