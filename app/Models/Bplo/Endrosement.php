<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class Endrosement extends Model
{
    
    public function getBploDocuments($id,$year=''){
        return DB::table('bplo_business_psic_req AS brq')
        ->Leftjoin('requirements AS rq', 'rq.id', '=', 'brq.req_code')
        ->select('brq.id','attachment','req_code','req_description')->where('busn_id',(int)$id)->where('busreq_year',(int)$year)->get()->toArray();
    }
    public function getYearDetails(){
        return DB::table('bplo_business_endorsement')->select('bend_year')->groupBy('bend_year')->orderBy('bend_year','DESC')->get()->toArray(); 
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

    public function getPeviouscompletedDept($id){
        return DB::table('bplo_business')->select('busn_dept_completed','busn_dept_involved')->where('id',(int)$id)->first();
    }
    public function getEndorsingDept($id){
        return DB::table('bplo_endorsing_dept')->select('tfoc_id','fees','force_mark_complete')->where('id',(int)$id)->first();
    }
    public function getDueDatesDetails($app_code){
        return DB::table('cto_payment_due_dates')->select('*')->where('app_type_id',(int)$app_code)->first();
    }
    
    public function getBusinessEndorsementDetails($busn_id,$bbendo_id,$year=''){
        return DB::table('bplo_business_endorsement')->select('documetary_req_json')->where('endorsing_dept_id',$bbendo_id)->where('bend_year',(int)$year)->where('busn_id',$busn_id)->first();
    }
    
    public function getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year=''){
        return DB::table('bplo_business_endorsement')->select('inspection_report_attachment')->where('endorsing_dept_id',$bbendo_id)->where('bend_year',(int)$year)->where('busn_id',$busn_id)->first();
    }
	
    public function checkApplicationExist($id,$bbendo_id,$year=''){
        $sql= DB::table('bfp_application_forms AS baf')
              ->select('baf.id AS bff_id')->where('baf.busn_id',$id)->where('baf.bend_id',(int)$bbendo_id)->where('baf.bff_year',(int)$year);
        
        return $sql->value('bff_id');
    }
    public function checkLocalityExist(){
        $sql= DB::table('rpt_locality')
              ->select('id AS loc_id')->where('department',2)->where('bfp_inspection_order',1);
          return $sql->value('loc_id');
    }
    public function checkInspectionsExist($id,$bbendo_id,$year=''){
        $sql= DB::table('bfp_inspection_orders')
              ->select('id')->where('busn_id',$id)->where('bend_id',(int)$bbendo_id)->where('bio_year',(int)$year);
        
        return $sql->value('id');
    }
    public function checkAssessmentsExist($id,$bbendo_id,$year=''){
        $sql= DB::table('bfp_application_assessments')
              ->select('id')->where('busn_id',$id)->where('bend_id',(int)$bbendo_id)->where('bfpas_ops_year',(int)$year);
        
        return $sql->value('id');
    }
    public function checkLocalGovermentFeeExist($busn_id,$year,$tfoc_id,$amount=0){
        return DB::table('cto_bplo_assessment')
            ->where('assess_year',(int)$year)
            ->where('busn_id',(int)$busn_id)
            ->where('busn_psic_id',0)
            ->where('subclass_id',0)
            ->where('tfoc_amount',$amount)
            ->where('tfoc_id',(int)$tfoc_id)->Exists();
    }
    public function deleteLocalNationalAssessmentFees($busn_id,$year,$tfoc_id){
        DB::table('cto_bplo_assessment')
        ->where('assess_year',(int)$year)
        ->where('busn_id',(int)$busn_id)
        ->where('busn_psic_id',0)
        ->where('subclass_id',0)
        ->where('tfoc_id',(int)$tfoc_id)->delete();

        DB::table('cto_bplo_assessment_details')
        ->where('assess_year',(int)$year)
        ->where('busn_id',(int)$busn_id)
        ->where('busn_psic_id',0)
        ->where('subclass_id',0)
        ->where('tfoc_id',(int)$tfoc_id)->delete();
        return true;
    }
    public function getPermitIsseuDate($id,$year=''){
        return DB::table('bplo_business_permit_issuance')->select('bpi_issued_date')->where('busn_id',(int)$id)->where('bpi_year',(int)$year)->value('bpi_issued_date');
    }
    
    public function getBploBusinessStatus($id,$bbendo_id,$year=''){
        return DB::table('bplo_business AS bb')
            ->join('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
            ->select('busn_app_status')->where('bbe.busn_id',$id)->where('bbe.endorsing_dept_id',(int)$bbendo_id)->where('bbe.bend_year',(int)$year)->value('busn_app_status');
    }
    public function getEditDetails($id,$bbendo_id,$year=''){
        return DB::table('bplo_business AS bb')
            ->join('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
            ->LeftJoin('clients as cc', 'cc.id', '=', 'bb.client_id')
            ->select('bbe.endorsing_dept_id AS bbendo_id','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','bb.id','busns_id_no','busn_name','tfoc_amount AS enddept_fee','documetary_req_json','inspection_report_attachment','force_mark_complete','bend_status','bbe.id AS bplo_documents','bbe.id AS document_details','bend_year','app_type_id','payment_mode')->where('bbe.busn_id',$id)->where('bbe.endorsing_dept_id',(int)$bbendo_id)->where('bbe.bend_year',(int)$year)->first();
    }
    public function getHealthCert(){
        $currentYear = date('Y');
        return DB::table('ho_app_health_certs AS hc')
        ->leftjoin('citizens', 'citizens.id', '=', 'hc.citizen_id')
        ->leftjoin('bplo_business', 'bplo_business.id', '=', 'hc.busn_id')
        ->select('hc.id','hahc_app_code','hc.created_at','hahc_app_year','hahc_app_no','hahc_transaction_no','hahc_registration_no','hahc_issuance_date','hahc_expired_date','hahc_status','hahc_remarks','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_date_of_birth','cit_gender','bplo_business.busn_name','hahc_recommending_approver','hahc_approver','hahc_approver_status')
        ->where('hc.hahc_app_year',$currentYear)
        ->where('hc.hahc_status',1)
        ->where('hc.busn_id',NULL)
        ->get();
    }
    public function getHealthCertLists($id,$bbendo_id){
        $data= DB::table('ho_app_health_certs AS hc')
                ->leftjoin('citizens', 'citizens.id', '=', 'hc.citizen_id')
                ->leftjoin('bplo_business', 'bplo_business.id', '=', 'hc.busn_id')
                ->select('hc.id','hahc_app_code','hc.created_at','hahc_app_year','hahc_app_no','hahc_transaction_no','hahc_registration_no','hahc_issuance_date','hahc_expired_date','hahc_status','hahc_remarks','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_date_of_birth','cit_gender','bplo_business.busn_name','hahc_recommending_approver','hahc_recommending_approver_status','hahc_approver','hahc_approver_status')
                ->where('hc.busn_id',$id)
                ->where('hc.bend_id',$bbendo_id)
                ->get();
        return array("data_cnt"=>$data->count(),"data"=>$data);
    }
    
    public function updateBusinessEndorsement($busn_id,$bbendo_id,$columns,$year=''){
        return DB::table('bplo_business_endorsement')->where('endorsing_dept_id',$bbendo_id)->where('busn_id',$busn_id)->where('bend_year',(int)$year)->update($columns);
    }
    public function updateBusinessEndorsementInspection($bbendo_id,$columns){
        return DB::table('bplo_business_endorsement')->where('busn_id',$bbendo_id)->update($columns);
    }
    public function updateData($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }
    
    public function getAssesmentDetails($buss_id,$year='',$app_code=0){
        return DB::table('cto_bplo_assessment AS cba')
            ->Leftjoin('acctg_account_subsidiary_ledgers AS sl', 'cba.sl_id', '=', 'sl.id')
            ->Leftjoin('psic_subclasses AS ps', 'cba.subclass_id', '=', 'ps.id')
            ->select('cba.id','cba.tfoc_id','sl.description as fee_name','tfoc_tmp_amount','surcharge_fee','interest_fee','assess_is_surcharge','assess_is_interest','tfoc_amount','subclass_description','subclass_id','busn_psic_id')
            ->where('busn_id',(int)$buss_id)
            ->where('assess_year',(int)$year)
            ->where('cba.app_code',(int)$app_code)
            //DB::raw("SUM(tfoc_amount) as tfoc_amount")
            //->groupBy('sl_id')
            ->get()->toArray();
    }
    
    public function requirementCode(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
    }

    public function newRequirementCode(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
    }
    public function getDataExport(){
        // Session::put('bbendo_idList',$bbendo_id); Session::put('yearList',$year); Session::put('statusList',$status);Session::put('searchList',$q);
      $bbendo_id = Session::get('bbendo_idList');
	  $startdate = Session::get('startdate'); 
	  $enddate = Session::get('enddate');$year = Session::get('yearList');
	  $status = Session::get('statusList'); 
	  $q = Session::get('searchList');
	  $application_status = Session::get('application_status');
      $sql = DB::table('bplo_business AS bb')
        ->Join('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
        ->leftjoin('bfp_certificates AS bcc', 'bb.id', '=', 'bcc.busn_id')
        ->leftjoin('clients AS cl', 'cl.id', '=', 'bb.client_id')
        // ->Leftjoin('bfp_application_forms AS bfp', 'bfp.busn_id', '=', 'bb.id')
        ->select('bb.id','bb.client_id','bbe.id as end_id','bcc.bfpcert_no','bcc.orno','bcc.oramount','bcc.ordate','bcc.bfpcert_date_issue','bcc.bfpcert_date_expired','bbe.bend_completed_date','bbe.created_at as start_date','bbe.endorsing_dept_id','busn_name','busns_id_no','app_code','busn_app_status','busn_app_method','suffix','bb.created_at','bbe.force_mark_complete','bend_status','bend_year','full_name','cl.p_mobile_no','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CASE 
        WHEN rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,'')))
        WHEN rpo_first_name IS NULL AND rpo_middle_name IS NULL AND suffix IS NULL THEN COALESCE(rpo_custom_last_name,'')
        ELSE TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,''))) END as ownar_name"))
        
        ->where('busn_app_status','>=',2)->where('bbe.endorsing_dept_id',(int)$bbendo_id);
       if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('bb.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('bb.created_at','<=',trim($enddate));  
        }
        if(!empty($year)){
            $sql->where('bend_year',(int)$year);
        }
        if(!empty($status)){
            $sql->where('bend_status',(int)$status);
        }
        if(!empty($application_status)){
            $sql->where('bb.busn_app_status','=',$application_status);
        }
        if(!empty($q) && isset($q)){
            $que = 100;
            $app_type = 100;
            $bend_status = 100;
            $bend_status = array_search($q,config('constants.arrBusEndorsementStatus'));
           
           
            if ($bend_status !== false) {
               
            } else {
                // Value is not found in the array
                $bend_status = 100;
                // Handle the case where the value is not found
                // ...
            }
            // dd($bend_status);
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                case 'new':
                    $app_type = 1;
                    break;
                case 'renew':
                    $app_type = 2;
                    break; 
                case 'retire':
                    $app_type = 3;
                    break;           
                default:
                    $que = 100;
                    $app_type = 100;
                    break;
            }
          
            $sql->where(function ($sql) use($q,$que,$app_type,$bend_status) {
                
                if(isset($que) && $que != 100)
                { 
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }elseif(isset($app_type) && $app_type != 100 ){
                    $sql->where(DB::raw('app_code'),$app_type);     
                }elseif(isset($bend_status) && $bend_status != 100){
                   
                    $sql->where(DB::raw('bend_status'),$bend_status);     
                }
              else{
                if (preg_match('/^(\d+)\s+(Days?)$/', $q, $matches)) {
                   $duration = (int)$matches[1];
                   $sql->where(function ($sql) use ($duration) {
                        $sql->orWhere(function ($subQuery) use ($duration) {
                            // Check if the calculated duration matches the search query
                           $subQuery->whereRaw("DATEDIFF(bbe.bend_completed_date, bbe.created_at) =?", [$duration]);
                        });
                    
                    });
                } else {   
                $sql->where(function ($sql) use ($q) {
                
                $sql->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(app_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bb.created_at)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_app_method)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bend_status)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bend_status)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bbe.created_at)'),'like',"%".strtolower($q)."%")
                ;
               });
              } 
              }   

            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('id','DESC');
        }
        $data=$sql->get();
        return $data;
  }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');
		$barangayid =$request->input('barangayid');
        $bbendo_id=$request->input('bbendo_id');
        $year=$request->input('year');
        $brgy=$request->input('brgy');
        $status=$request->input('status');
		$application_status=$request->input('application_status');
        Session::put('bbendo_idList',$bbendo_id);Session::put('startdate',$startdate); Session::put('enddate',$enddate); Session::put('yearList',$year); Session::put('statusList',$status);Session::put('searchList',$q);Session::put('application_status',$application_status);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"busns_id_no",
          2 =>"full_name",
          3 =>"busn_name",
          4 =>"app_code",
          5 =>"bb.created_at",
          6 =>"busn_app_method",
          7 =>"busn_app_status",
          8 =>"bend_status",
		  9 =>"bbe.created_at"
        );
        $sql = DB::table('bplo_business AS bb')
        ->Join('bplo_business_endorsement AS bbe', 'bb.id', '=', 'bbe.busn_id')
        ->leftjoin('clients AS cl', 'cl.id', '=', 'bb.client_id')
        ->Leftjoin('barangays AS bars', 'bars.id', '=', 'bb.busn_office_barangay_id')
        ->select('bb.id','bars.brgy_name','bbe.id as end_id','bbe.bend_completed_date','bbe.created_at as start_date','endorsing_dept_id','busn_name','busns_id_no','app_code','busn_app_status','busn_app_method','suffix','bb.created_at','bbe.force_mark_complete','bend_status','bend_year','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name',DB::raw("CASE 
        WHEN rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
        WHEN suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,'')))
        WHEN rpo_first_name IS NULL AND rpo_middle_name IS NULL AND suffix IS NULL THEN COALESCE(rpo_custom_last_name,'')
        ELSE TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,''))) END as ownar_name"))
        
        ->where('busn_app_status','>=',2)->where('endorsing_dept_id',(int)$bbendo_id);
        /*$sql->whereExists(function ($query) {
               $query->select("bed.id")
                  ->from('bplo_endorsing_dept AS bed')
                  ->where('bed.id',1)
                  ->where('edept_status',1);
        });*/
        //->where('bend_assessment_type',2);
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('bb.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('bb.created_at','<=',trim($enddate));  
        }
		
		if(!empty($barangayid) && isset($barangayid)){
            $sql->where('bb.busn_office_barangay_id','=',$barangayid);  
        }
		
        if(!empty($year)){
            $sql->where('bend_year',(int)$year);
        }
		if(!empty($application_status)){
            $sql->where('bb.busn_app_status','=',$application_status);
        }
        if(!empty($brgy)){
            $sql->where('brgy.id','=',$brgy);
        }
        if(!empty($status)){
            $sql->where('bend_status',(int)$status);
        }
        if(!empty($q) && isset($q)){
            $que = 100;
            $app_type = 100;
            $bend_status = 100;
            $bend_status = array_search($q,config('constants.arrBusEndorsementStatus'));
           
           
            if ($bend_status !== false) {
               
            } else {
                // Value is not found in the array
                $bend_status = 100;
                // Handle the case where the value is not found
                // ...
            }
            // dd($bend_status);
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                case 'new':
                    $app_type = 1;
                    break;
                case 'renew':
                    $app_type = 2;
                    break; 
                case 'retire':
                    $app_type = 3;
                    break;           
                default:
                    $que = 100;
                    $app_type = 100;
                    break;
            }
          
            $sql->where(function ($sql) use($q,$que,$app_type,$bend_status) {
                
                if(isset($que) && $que != 100)
                { 
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }elseif(isset($app_type) && $app_type != 100 ){
                    $sql->where(DB::raw('app_code'),$app_type);     
                }elseif(isset($bend_status) && $bend_status != 100){
                   
                    $sql->where(DB::raw('bend_status'),$bend_status);     
                }
              else{
                if (preg_match('/^(\d+)\s+(Days?)$/', $q, $matches)) {
                   $duration = (int)$matches[1];
                   $sql->where(function ($sql) use ($duration) {
                        $sql->orWhere(function ($subQuery) use ($duration) {
                            // Check if the calculated duration matches the search query
                           $subQuery->whereRaw("DATEDIFF(bbe.bend_completed_date, bbe.created_at) =?", [$duration]);
                        });
                    
                    });
                } else {   
                $sql->where(function ($sql) use ($q) {
                
                $sql->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(app_code)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.created_at)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(busn_app_method)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(busn_app_status)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bend_status)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bend_status)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bbe.created_at)'),'like',"%".strtolower($q)."%")
				;
               });
              } 
              }   

            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);

    }
	
	public function getBarangayAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('barangays As a')
		->join('rpt_locality AS b', 'b.mun_no', '=', 'a.mun_no')
        ->select('a.id','a.brgy_name')
		->where('b.department','=',2);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('b.department','=',2);
            $sql->Where('a.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(a.brgy_name)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('a.brgy_name','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
