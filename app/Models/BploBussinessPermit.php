<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BploBussinessPermit extends Model
{

    public function updateData($id,$columns){
        return DB::table('bplo_business_permit_issuance')->where('id',$id)->update($columns);
    }
    public function updateBploBusinessData($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }
    public function updateDataAttachment($id,$columns){
        return DB::table('bplo_business_permit_issuance')->where('id',$id)->update($columns);
    }
    public function addData($data){
        DB::table('bplo_business_permit_issuance')->insert($data);
         return DB::getPdo()->lastInsertId();
    }
    public function getbploApplications(){
       return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }
    public function getbploBusinessAppCode($busn_id,$year){
       return DB::table('bplo_business')->select('app_code')->where('id',$busn_id)->where('busn_tax_year',$year)->value('app_code');
    }
    public function getPreviousIssueNumber(){
        return DB::table('bplo_business_permit_issuance')->select('bpi_no')->where('bpi_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function getLocality(){
        return DB::table('rpt_locality')->select('loc_local_code')->where('department',2)->value('loc_local_code');
    }
    public function getBusinessType(){
        return DB::table('bplo_business_type')->select('id','btype_desc')->get()->toArray();
    }
    public function getYearDetails(){
        return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
    }
    public function getPermitIsseuDetails($id){
        return DB::table('bplo_business_permit_issuance')->select('id','business_plate_no','bpi_remarks','bpi_issued_status','bpi_permit_no')->where('id',(int)$id)->first();
    }
    public function getEndorsmentDetails($id,$year=''){
        return DB::table('bplo_endorsing_dept AS ed')
               ->join('bplo_business_endorsement AS e', 'e.endorsing_dept_id', '=', 'ed.id')
               ->select('ed.id','e.id AS end_id','edept_name','busn_id','bend_year','bend_status')->where('e.busn_id',(int)$id)->where('e.bend_year',(int)$year)->get()->toArray();
    }
    public function getEndorsmentFireProtection($id,$year=''){
        return DB::table('bplo_endorsing_dept AS ed')
               ->join('bplo_business_endorsement AS e', 'e.endorsing_dept_id', '=', 'ed.id')
               ->select('ed.id AS bendId','e.id AS end_id','edept_name','busn_id','bend_year','bend_status')->where('e.busn_id',(int)$id)->where('e.bend_year',(int)$year)->where('ed.id',1)->get()->toArray();
    }
    public function getEndorsmentPlaning($id,$year=''){
        return DB::table('bplo_endorsing_dept AS ed')
               ->join('bplo_business_endorsement AS e', 'e.endorsing_dept_id', '=', 'ed.id')
               ->select('ed.id','e.id AS end_id','edept_name','busn_id','bend_year','bend_status')->where('e.busn_id',(int)$id)->where('e.bend_year',(int)$year)->where('ed.id',2)->get()->toArray();
    }
    public function getEndorsmentHealth($id,$year=''){
        return DB::table('bplo_endorsing_dept AS ed')
               ->join('bplo_business_endorsement AS e', 'e.endorsing_dept_id', '=', 'ed.id')
               ->select('ed.id','e.id AS end_id','edept_name','busn_id','bend_year','bend_status')->where('e.busn_id',(int)$id)->where('e.bend_year',(int)$year)->where('ed.id',3)->get()->toArray();
    }
    public function getEnv($id,$year=''){
        return DB::table('bplo_endorsing_dept AS ed')
               ->join('bplo_business_endorsement AS e', 'e.endorsing_dept_id', '=', 'ed.id')
               ->select('ed.id','e.id AS end_id','edept_name','busn_id','bend_year','bend_status')->where('e.busn_id',(int)$id)->where('e.bend_year',(int)$year)->where('ed.id',4)->get()->toArray();
    }
    public function getFireProtection($id,$bend_id,$year=''){
        return DB::table('bplo_business_endorsement')->select('documetary_req_json')->where('busn_id',(int)$id)->where('endorsing_dept_id',(int)$bend_id)->where('bend_year',(int)$year)->value('documetary_req_json');
    }
    public function getApplicationDetails($id,$bend_id,$year=''){
        return DB::table('bfp_application_forms')->select('bff_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('bff_year',(int)$year)->value('bff_document');
    }
    public function getInspectionOrderDetails($id,$bend_id,$year=''){
        return DB::table('bfp_inspection_orders')->select('bio_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('bio_year',(int)$year)->value('bio_document');
    }
    public function getCertificateDetails($id,$bend_id,$year=''){
        return DB::table('bfp_certificates')->select('bfpcert_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('bfpcert_year',(int)$year)->value('bfpcert_document');
    }
    public function getAssessmentDetails($id,$bend_id,$year=''){
        return DB::table('bfp_application_assessments')->select('bfpas_document_json')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('bfpas_ops_year',(int)$year)->value('bfpas_document_json');
    }
    public function getPlaningDetails($id,$bend_id,$year=''){
        return DB::table('pdo_bplo_endosements')->select('pend_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('pend_year',(int)$year)->value('pend_document');
    }
    public function getHealthDetails($id,$bend_id,$year=''){
        return DB::table('ho_app_health_certs')->select('hahc_document_json')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('hahc_app_year',(int)$year)->get()->toArray();
    }
    public function getSanitaryDetails($id,$bend_id,$year=''){
        return DB::table('ho_application_sanitaries AS s')
               ->join('ho_application_sanitary_req AS sr', 'sr.has_id', '=', 's.id')
               ->select('hasr_document')->where('s.busn_id',(int)$id)->where('s.bend_id',(int)$bend_id)->where('s.has_app_year',(int)$year)->get()->toArray();
    }
    public function getEnvReport($id,$bend_id,$year=''){
        return DB::table('enro_bplo_inspection_report')->select('ebir_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('ebir_year',(int)$year)->value('ebir_document');
    }
    public function getEnvClearance($id,$bend_id,$year=''){
        return DB::table('enro_bplo_app_clearances')->select('ebac_document')->where('busn_id',(int)$id)->where('bend_id',(int)$bend_id)->where('ebac_app_year',(int)$year)->value('ebac_document');
    }
    
    public function employeeData($user_id){
         return DB::table('hr_employees AS e')
              ->join('hr_designations AS d', 'd.id', '=', 'e.hr_designation_id')
               ->select('e.id','d.description')->where('e.user_id','=',$user_id)->first();
    }

    public function getPermitIsseuPrint($id){
        return DB::table('bplo_business_permit_issuance')->select('id AS issuance_id','bpi_permit_no','bpi_date_expired','bpi_upload_signed_permit','busn_id','bpi_year','bpi_remarks','bpi_issued_date','app_type_id')->where('id',(int)$id)->first();
    }
    public function checkPermitIssueExit($columns){
        return DB::table('bplo_business_permit_issuance')->select('id')->where('busn_id',$columns['busn_id'])->where('app_type_id',$columns['app_type_id'])->where('bpi_year',$columns['bpi_year'])->get()->toArray();
    }
    public function checkPermitIssueDetails($busn_id,$year=''){
        return DB::table('bplo_business_permit_issuance')->select('inspection_report_attachment')->where('endorsing_dept_id',$bbendo_id)->where('bend_year',(int)$year)->where('busn_id',$busn_id)->first();
    }
    public function getBploApplictaiondetails($id){
        return DB::table('bplo_applications')->select('id','ba_cover_year','ba_date_started')->where('id','=',$id)->first();
    }
    public function reload_busn_plan($busn_id)
        {
            $items = DB::table('bplo_business_psic AS bbp')
                    ->join('psic_subclasses AS psc', 'psc.id', '=', 'bbp.subclass_id')
                    ->join('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                    ->join('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                    ->join('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                    ->join('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                    ->join('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                    ->select('bbp.id as ID','bbp.*','subclass_code','subclass_description')
                    ->where('bbp.busn_id',$busn_id)
                    ->orderBy('id', 'asc')
                    ->first();
    
            return $items;
    }
    public function addenroInspectionReportData($postdata){
        return DB::table('enro_bplo_inspection_report')->insert($postdata);
    }
    public function getFinalAssessementDetails($bus_id,$app_code,$year){
        return DB::table('cto_bplo_final_assessment_details')->where('assess_year', '=',(int)$year)->where('app_code', '=',(int)$app_code)->where('busn_id',(int)$bus_id)->orderBy('id','ASC')->get()->toArray();
    }
    public function checkTopPaidTransaction($bus_id,$app_code,$year){
        return DB::table('cto_top_bplo AS ctb')
            ->Join('cto_top_transactions AS ctt', 'ctt.transaction_ref_no', '=', 'ctb.id')
            ->select('ctt.id AS top_trans_id','ctb.id AS top_bplo_id','is_paid','transaction_no','ctt.created_at')
            ->where('ctt.top_transaction_type_id',1)
            ->where('ctt.tfoc_is_applicable',1)
            ->where('top_year',(int)$year)
            ->where('busn_id',(int)$bus_id)
            ->where('app_code',(int)$app_code)
            ->orderBy('ctt.id','DESC')->first();
    }
    public function getPaidPaymentDate($bus_id){
        return DB::table('cto_bplo_final_assessment_details')->select('assess_year','total_amount','payment_mode','app_code','assess_due_date')->where('payment_status',1)->where('busn_id',(int)$bus_id)->orderBy('id','DESC')->first();
    }
    public function getTaxAssessementDetails($bus_id,$app_code,$year,$pm_id,$assessment_period){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.*','sl.description')
            ->where('assess_year', '=',(int)$year)
            ->where('assessment_period',(int)$assessment_period)
            ->where('payment_mode',(int)$pm_id)
            ->where('app_code',(int)$app_code)
            ->where('busn_id',(int)$bus_id)->orderBy('id','ASC')->get()->toArray();
    }
    public function getBusinessDetails($busn_id){
        return DB::table('bplo_business AS bb')
            ->Leftjoin('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->select('bb.pm_id','bb.busn_tax_year','busn_employee_total_no','bb.created_at','p_mobile_no','busn_office_main_barangay_id','is_final_assessment','bb.id','busn_tax_year','busn_name','busns_id_no','pm_desc','app_code','busn_app_status','busn_app_method','bb.created_at',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"))
            ->where('bb.id',(int)$busn_id)->first();
    }
    public function getBussinessPermitdata($id){
      return DB::table('bplo_bussiness_permits as bp')->join('bplo_applications AS ba', 'ba.id', '=', 'bp.ba_code')->join('profiles AS p', 'p.id', '=', 'ba.profile_id')->select('bp.id','bp.bbp_permit_no','bp.bbp_record_no','bbp_approved_date','bbp_date_expired','ba.ba_business_name','ba_address_house_lot_no','ba_address_street_name','ba.brgy_name','p_complete_name_v1')->where('bp.id',(int)$id)->first();
    }
    public function getNatureofbusiness($subclassid){
       return DB::table('psic_subclasses')->select('subclass_description')->where('id','=',$subclassid)->first();
    }
    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $bbendo_id=$request->input('bbendo_id');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 =>"busn_tax_year",
          2 =>"busns_id_no",
          3 =>"cl.full_name",
          4 =>"busn_name",
          5 =>"app_type_id",
          6 =>"bb.created_at",
          7 =>"busn_app_status",
          8 =>"pm_desc",
          9 =>"busn_plate_number"
        );

        $statuses = [5, 6,8];
        $sql = DB::table('bplo_business AS bb')
        ->Join('bplo_business_permit_issuance AS bi', 'bi.busn_id', '=', 'bb.id')
        ->Leftjoin('clients AS cl', 'cl.id', '=', 'bb.client_id')
        ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
         ->select('bi.id AS issuance_id','bb.id','bb.busn_name','busn_plate_number','busn_tax_year','busns_id_no','pm_desc','app_code','app_type_id','busn_app_status','busn_app_method','cl.suffix','bb.created_at',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'cl.full_name');
        //->whereIn('busn_app_status', $statuses);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")   
                    ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(function ($sql) use ($q) {
                          if ($q === 'New' || $q === 'new') {
                              $sql->where('app_type_id', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'Renew' || $q === 'renew') {
                              $sql->where('app_type_id', '=', 2); // Condition for Exempt (option 2)
                          }elseif ($q === 'Retire' || $q === 'retire') {
                              $sql->where('app_type_id', '=', 3); // Condition for Exempt (option 2)
                          }
                    })
                    ->orWhere(function ($sql) use ($q) {
                          if ($q === 'Not Completed') {
                              $sql->where('busn_app_status', '=', 0); // Condition for Taxable (option 1)
                          } elseif ($q === 'For Verification') {
                              $sql->where('busn_app_status', '=', 1); // Condition for Exempt (option 2)
                          }elseif ($q === 'For Endorsement') {
                              $sql->where('busn_app_status', '=', 2);

                          
                          }elseif ($q === 'For Assessment' || $q === 'retire') {
                              $sql->where('busn_app_status', '=', 3);
                              
                          
                          }elseif ($q === 'For Payment') {
                              $sql->where('busn_app_status', '=', 4);
                              
                          
                          }elseif ($q === 'For Issuance') {
                              $sql->where('busn_app_status', '=', 5);
                              
                          
                          }elseif ($q === 'License Issued') {
                              $sql->where('busn_app_status', '=', 6);
                              
                          
                          }elseif ($q === 'Declined') {
                              $sql->where('busn_app_status', '=', 7);
                              
                          
                          }elseif ($q === 'Cancelled Permit') {
                              $sql->where('busn_app_status', '=', 8);
                              
                          }
                    })
                    
                    ->orWhere(DB::raw('DATE_FORMAT(bb.created_at, "%d-%b-%Y %H:%i:%s")'), 'LIKE', "%" . strtolower($q) . "%")
                    ->orWhere(DB::raw('LOWER(busn_plate_number)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pm_desc)'),'like',"%".strtolower($q)."%"); 
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
}
