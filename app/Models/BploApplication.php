<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BploApplication extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('bplo_applications')->where('id',$id)->update($columns);
    }

    
    public function addData($postdata){
        DB::table('bplo_applications')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getApplicantexists($profileid,$bussinessname){
       return DB::table('bplo_applications')->where('profile_id', $profileid)->where('ba_business_name', $bussinessname)->get()->toArray(); 
    }
    public function addAppRenewalData($postdata){
        DB::table('bplo_application_renewal')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateAssesmentData($id,$columns){
        return DB::table('bplo_assessments')->where('application_id',$id)->update($columns);
    }
    public function addApplicationRequirement($postdata){
        return DB::table('bplo_application_requirements')->insert($postdata);
    }
    public function updateApplicationRequirement($id,$columns){
        return DB::table('bplo_application_requirements')->where('id',$id)->update($columns);
    }
    public function deleteApplicationRequirement($id){
        DB::table('bplo_application_requirements')->where('bplo_application_id', $id)->delete();
    }
    public function getAppReqDtls($id){
        return DB::table('bplo_application_requirements')->where('bplo_application_id', $id)->get()->toArray();
    }
    public function getSubClasses(){
        return DB::table('psic_subclasses')->select('id','subclass_description','subclass_code')->where('is_active',1)->get();
    }
    public function getMunicipality(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('mun_display_for_bplo',1)->get();
    }

    
    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_bplo',1)->get();
    }
    public function getBarangyaDetails($id){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getBploRequirement(){
        return DB::table('bplo_requirements')->select('id','req_code_abbreviation','req_description','apptype_id')->where('is_active',1)->where('apptype_id',1)->get();
    }
    public function getprofiles(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name','p_complete_name_v1')->get();
    }
    public function getprogiledata($id){
        return DB::table('profiles AS p')
                ->join('barangays AS bgf', 'bgf.id', '=', 'p.brgy_code')
                ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
                ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
                ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
                ->select('p.id','p.p_first_name','p.p_middle_name','p.p_family_name','p.p_telephone_no','p.p_fax_no','p.p_tin_no','p.p_address_house_lot_no','p.p_address_street_name','p.p_address_subdivision','pm.mun_desc','pp.prov_desc','pr.reg_region','bgf.brgy_name')->where('p.id','=',$id)->get();
    }

    public function getappidsdata($id){
        return DB::table('profiles')->select('applicantids')->where('id','=',$id)->get();
    }
    
    public function getTradename($id){
             return DB::table('allaplicants')->select('tradename')->where('id','=',$id)->get();
    }
    public function requirementcode($type)
    {
        //return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
         return DB::table('requirements as rq')->leftjoin('bplo_requirements AS br', 'rq.id', '=', 'br.req_id')->leftjoin('bplo_requirement_relations AS brr', 'br.id', '=', 'brr.bplo_requirement_id')->select('rq.id','rq.req_code_abbreviation','rq.req_description')->where('rq.req_dept_bplo','1')->where('br.apptype_id',$type)->get();
    }
    public function getRequirementRow($id){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo',1)->where('id',$id)->first();
    }
    public function getRequirementsNature($id,$prereq){
        return DB::table('bplo_requirement_relations as brel')->join('bplo_requirements AS br', 'br.id', '=', 'brel.bplo_requirement_id')->join('requirements AS rq', 'rq.id', '=', 'brel.requirement_id')->select('brel.id','rq.req_code_abbreviation','br.apptype_id','rq.req_description')->where('brel.subclass_id',$id)->whereNotIn('rq.id',$prereq)->groupBy('rq.id')->get();
    }

    public function deleteRequirementsBplo($id){
        return DB::table('bplo_application_requirements')->where('id',$id)->delete();
    }

    public function getRequirementsNaturearray($ids,$prereq){
        return DB::table('bplo_requirement_relations as brel')->join('bplo_requirements AS br', 'br.id', '=', 'brel.bplo_requirement_id')->join('requirements AS rq', 'rq.id', '=', 'brel.requirement_id')->select('brel.id','rq.req_code_abbreviation','br.apptype_id','rq.req_description')->whereIn('brel.subclass_id',$ids)->whereNotIn('rq.id',$prereq)->groupBy('rq.id')->get();
    }
    
   
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $isapproved=$request->input('approve');
    $startdate =$request->input('crdate');
    $barangay =$request->input('barangay');
    $year = $request->input('year');

    if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
    }

    $columns = array( 
        0 =>"ba_business_account_no",
        1 =>"pa.ba_business_name",
        2 =>"ba_address_house_lot_no",
        3 =>"pa.created_at",
        4 =>"pa.is_active"     
    );

    $sql = DB::table('bplo_applications AS pa')
        ->join('profiles AS p', 'p.id', '=', 'pa.profile_id')
        ->select('pa.id','pa.app_type_id','ba_business_account_no','pa.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba_address_subdivision','pa.created_at','pa.is_active','p_complete_name_v1','ba_date_started');

    //$sql->where('pa.created_by', '=', \Auth::user()->creatorId());
    if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(ba_business_account_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pa.ba_business_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ba_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ba_address_street_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ba_address_subdivision)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(class_description)'),'like',"%".strtolower($q)."%");
        });
    }
    if(isset($isapproved)){
        $sql->where('is_approved', '=', trim($isapproved));
    } 
    if(!empty($barangay) && isset($barangay)){
        $sql->where('barangay_id', '=', trim($barangay));
    }
    if(!empty($startdate) && isset($startdate)){
        $sdate = explode('-', $startdate);
        $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
        $startdate = date('Y-m-d',strtotime($startdate)); 
        $sql->whereDate('ba_date_started','>=',trim($startdate));  
        //$sql->where(DB::raw('spi_created_date'::date >=$startdate));
    }
         
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
        $sql->orderBy('pa.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
