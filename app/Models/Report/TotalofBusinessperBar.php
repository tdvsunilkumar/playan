<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Session;
class TotalofBusinessperBar extends Model
{
  public $table = 'bplo_business_permit_issuance';

  public function getYearDetails(){
	
      return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
  }
 
  public function getList($request){
    $params = $columns=array();
    $params = $_REQUEST;
    $search =$request->input('q');
    $year = $request->input('year');
    if(!isset($params['start']) && !isset($params['length'])){
      $start="0";
      $length="10";
    }else{
        $start = $params['start'];
        $length = $params['length'];
    }
    $columns = array( 
        1 =>"brgy_name",
        2 =>"new_application",
        3 =>"renewal_application",
        4 =>"renewal_application"
    );

    $orderBy ='brgy_name ASC';
    if(isset($params['order'][0]['column'])){
      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
    }
    $out="p_total_count";
    $sql="CALL REPORT_BPLO_PER_BRGY('$year','$start',$length,'$search','$orderBy',@$out)";

    $arr = DB::select($sql);
    Session::put('REPORT_PER_BRGY',$sql);
    $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
    return array("data_cnt"=>$data_cnt,"data"=>$arr);
  }
  /* public function getDataPerBarngay(){
    $sql = Session::get('REPORT_PER_BRGY');
    $arr = DB::select($sql);
     return $arr;
  }*/
  
   public function getDataPerBarngay($request){
    $params = $columns=array();
    $params = $_REQUEST;
    $search =$request->input('q');
    $year = $request->input('year');
    $start="0";
    $length=$request->input('length');
    
    $columns = array( 
        1 =>"brgy_name",
        2 =>"new_application",
        3 =>"renewal_application",
        4 =>"renewal_application"
    );

    $orderBy ='brgy_name ASC';
    if(isset($params['order'][0]['column'])){
      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
    }
    $out="p_total_count";
    $sql="CALL REPORT_BPLO_PER_BRGY('$year','$start',$length,'$search','$orderBy',@$out)";

    Session::put('REPORT_PER_BRGY',$sql);
    $arr = DB::select($sql);
    return $arr;
  }
}
