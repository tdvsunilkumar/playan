<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Session;
class ListofBusinessperBar extends Model
{
  public $table = 'bplo_business_permit_issuance';

  public function getYearDetails(){
	
      return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
  }
 
  public function getList($request){
    $params = $columns=array();
    $params = $_REQUEST;  
    $search =$request->input('q');
    $brayid = $request->input('brgayid');
    $from_date=($request->input('from_date'))? $request->input('from_date') : '';
    $to_date=($request->input('to_date'))? $request->input('to_date') : '';
         Session::put('blistfromdate',$from_date); Session::put('blisttodate',$to_date); Session::put('blistsearch',$search);
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
    $sql="CALL REPORT_BPLOLIST_PER_BRGY('$from_date','$to_date','$brayid','$start',$length,'$search','$orderBy',@$out)";
    $arr = DB::select($sql);
    Session::put('REPORT_BPLOLIST_PER_BRGY',$sql);
    $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
    return array("data_cnt"=>$data_cnt,"data"=>$arr);
  }
  public function getDataPerBarngay(){
    Session::forget('blistfromdate'); Session::forget('blisttodate'); Session::forget('blistsearch');
    $sql = Session::get('REPORT_BPLOLIST_PER_BRGY');
    $arr = DB::select($sql);
    return $arr;
  }
}
