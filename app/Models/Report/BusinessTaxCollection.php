<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class BusinessTaxCollection extends Model
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
        Session::put('startdate',$startdate); Session::put('enddate',$enddate);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"cashier_or_date",
          2 =>"or_no",
          3 =>"taxpayers_name",
          4 =>"busns_id_no",
          5 =>"busn_name",
          6 =>"net_tax_due_amount",
          7 =>"tax_credit_amount",
        );
        $sql = DB::table('cto_cashier AS a')
        ->Join('bplo_business  AS b', 'b.id', '=', 'a.busn_id')
        ->select('a.cashier_or_date','a.or_no','a.taxpayers_name','b.busns_id_no','b.busn_name','a.net_tax_due_amount','a.tax_credit_amount')
        ->where('a.tfoc_is_applicable',1)
        ->where('a.ocr_id',0);
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('a.cashier_or_date','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('a.cashier_or_date','<=',trim($enddate));  
        }
		
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('a.id','DESC');
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
	  
      $sql = DB::table('cto_cashier AS a')
        ->Join('bplo_business  AS b', 'b.id', '=', 'a.busn_id')
        ->select('a.cashier_or_date','a.or_no','a.taxpayers_name','b.busns_id_no','b.busn_name','a.net_tax_due_amount','a.tax_credit_amount')
        ->where('a.tfoc_is_applicable',1)
        ->where('a.ocr_id',0);
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('a.cashier_or_date','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('a.cashier_or_date','<=',trim($enddate));  
        }
        
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
            $sql->orderBy('a.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $data=$sql->get();
        return $data;
  }
}
