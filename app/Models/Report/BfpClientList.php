<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class BfpClientList extends Model
{
    
    public function getBploDocuments($id,$year=''){
        return DB::table('bplo_business_psic_req AS brq')
        ->Leftjoin('requirements AS rq', 'rq.id', '=', 'brq.req_code')
        ->select('brq.id','attachment','req_code','req_description')->where('busn_id',(int)$id)->where('busreq_year',(int)$year)->get()->toArray();
    }
    public function getYearDetails(){
        return DB::table('bplo_business_endorsement')->select('bend_year')->groupBy('bend_year')->orderBy('bend_year','DESC')->get()->toArray(); 
    }
    public function occupancyData(){
        return DB::table('bfp_occupancy_types')->select('id','bot_occupancy_type','is_active')->get();
    }
   
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');
		$barangayid =$request->input('barangayid');
        $year=$request->input('year');
        $brgy=$request->input('brgy');
        $status=$request->input('status');
		$application_status=$request->input('application_status');
        Session::put('startdate',$startdate); Session::put('enddate',$enddate); Session::put('yearList',$year); Session::put('statusList',$status);Session::put('searchList',$q);Session::put('application_status',$application_status);Session::put('barangayid',$barangayid);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"busns_id_no",
          2 =>"created_at",
          3 =>"bio_inspection_no",
          4 =>"busn_name",
          5 =>"rpo_first_name",
          6 =>"rpo_custom_last_name",
          7 =>"bfpcert_no",
          8 =>"inspection_date",
		  9 =>"app_type",
		  10 =>"bfpcert_date_issue",
          11 =>"bfpcert_date_expired",
          12 =>"brgy_name",
          13 =>"p_mobile_no",
          14 =>"bot_occupancy_type",
          15 =>"fullname",
          16 =>"bfpas_total_amount",
          17 =>"bfpas_payment_or_no",
		  18 =>"bfpas_date_paid",
		  19 =>"bfpas_remarks",
		  20 =>"is_printed",
        );
        $sql = DB::table('bfp_certificates AS cert')
        ->Join('bplo_business_history AS hist', 'hist.busn_id', '=', 'cert.busn_id')
        ->Join('bplo_business_endorsement AS end', 'end.id', '=', 'cert.bend_id')
        ->Join('clients AS cl', 'cl.id', '=', 'hist.client_id')
        ->Join('bplo_application_type AS app', 'app.id', '=', 'hist.app_code')
        ->Join('barangays AS bars', 'bars.id', '=', 'hist.busn_office_barangay_id')
        ->Join('bfp_application_forms AS form', 'form.busn_id', '=', 'end.busn_id')
        ->Join('bfp_occupancy_types AS occu', 'occu.id', '=', 'form.bot_id')
        ->Leftjoin('hr_employees AS hr', 'hr.id', '=', 'cert.inspection_officer_id')
        ->Join('bfp_application_assessments AS ass', 'ass.bff_id', '=', 'form.id')
        ->select('hist.busns_id_no AS BINBAN','cert.created_at','cert.bio_inspection_no','hist.busn_name','cl.rpo_first_name','cl.rpo_custom_last_name','cert.bfpcert_no','cert.inspection_date','app.app_type','cert.bfpcert_date_issue','cert.bfpcert_date_expired','bars.brgy_name','cl.p_mobile_no','occu.bot_occupancy_type','hr.fullname','ass.bfpas_total_amount','ass.bfpas_payment_or_no','ass.bfpas_date_paid','ass.bfpas_remarks','cert.is_printed','cert.bfpcert_year');
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cert.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cert.created_at','<=',trim($enddate));  
        }
		
		if(!empty($barangayid) && isset($barangayid)){
            $sql->where('hist.busn_office_barangay_id','=',$barangayid);  
        }
		
        if(!empty($year)){
            $sql->where('bfpcert_year',(int)$year);
        }
		if(!empty($application_status)){
            $sql->where('occu.id','=',$application_status);
        }
        if(!empty($brgy)){
            $sql->where('brgy.id','=',$brgy);
        }
        if(!empty($status)){
            $sql->where('hist.app_code',(int)$status);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('cert.id','DESC');
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
    public function getDataExport(){
	  $startdate = Session::get('startdate'); 
	  $enddate = Session::get('enddate');$year = Session::get('yearList');
	  $status = Session::get('statusList'); 
	  $q = Session::get('searchList');
	  $barangayid=Session::get('barangayid');
	  $application_status = Session::get('application_status');
      $sql = DB::table('bfp_certificates AS cert')
        ->Join('bplo_business_history AS hist', 'hist.busn_id', '=', 'cert.busn_id')
        ->Join('bplo_business_endorsement AS end', 'end.id', '=', 'cert.bend_id')
        ->Join('clients AS cl', 'cl.id', '=', 'hist.client_id')
        ->Join('bplo_application_type AS app', 'app.id', '=', 'hist.app_code')
        ->Join('barangays AS bars', 'bars.id', '=', 'hist.busn_office_barangay_id')
        ->Join('bfp_application_forms AS form', 'form.busn_id', '=', 'end.busn_id')
        ->Join('bfp_occupancy_types AS occu', 'occu.id', '=', 'form.bot_id')
        ->Leftjoin('hr_employees AS hr', 'hr.id', '=', 'cert.inspection_officer_id')
        ->Join('bfp_application_assessments AS ass', 'ass.bff_id', '=', 'form.id')
        ->select('hist.busns_id_no AS BINBAN','cert.created_at','cert.bio_inspection_no','hist.busn_name','cl.rpo_first_name','cl.rpo_custom_last_name','cert.bfpcert_no','cert.inspection_date','app.app_type','cert.bfpcert_date_issue','cert.bfpcert_date_expired','bars.brgy_name','cl.p_mobile_no','occu.bot_occupancy_type','hr.fullname','ass.bfpas_total_amount','ass.bfpas_payment_or_no','ass.bfpas_date_paid','ass.bfpas_remarks','cert.is_printed','cert.bfpcert_year');
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cert.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cert.created_at','<=',trim($enddate));  
        }
		
		if(!empty($barangayid) && isset($barangayid)){
            $sql->where('hist.busn_office_barangay_id','=',$barangayid);  
        }
		
        if(!empty($year)){
            $sql->where('bfpcert_year',(int)$year);
        }
		if(!empty($application_status)){
            $sql->where('occu.id','=',$application_status);
        }
        if(!empty($brgy)){
            $sql->where('brgy.id','=',$brgy);
        }
        if(!empty($status)){
            $sql->where('hist.app_code',(int)$status);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('cert.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $data=$sql->get();
        return $data;
  }
}
