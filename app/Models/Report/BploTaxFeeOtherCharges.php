<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class BploTaxFeeOtherCharges extends Model
{
	 public function getList($request){
    	$params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $search=$request->input('q');  $tfocid ="";
        $from_date=($request->input('from_date'))? $request->input('from_date') : '';
        $to_date=($request->input('to_date'))? $request->input('to_date') : '';
         Session::put('tfocfromdate',$from_date); Session::put('tfoctodate',$to_date); Session::put('tfocsearch',$search);

       if(!isset($params['start']) && !isset($params['length'])){
	      $start="0";
	      $length="10";
	    }else{
	        $start = $params['start'];
	        $length = $params['length'];
	    }
        
        $columns = array( 
          1 =>"bb.busn_name",
          2 =>"bb.rpo_first_name",  
          3 =>"bb.busn_name",
          4 =>"cl.rpo_first_name",
          5 =>"bb.busn_tin_no",
          9 =>"bb.created_at",
         );
    	   $orderBy ='ct.id ASC';
		    if(isset($params['order'][0]['column'])){
		      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
		    }
		    
		    $out="p_total_count";
		    $sql="CALL REPORT_BPLO_TAX_FEE_OTHER_CHARGES('$from_date','$to_date','$start','$length','$search','$orderBy',@$out)";

		    $arr = DB::select($sql);
		    Session::put('REPORT_BPLO_TAX_FEE_OTHER_CHARGES',$sql);
		    $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
		    return array("data_cnt"=>$data_cnt,"data"=>$arr);
    }

    public function getDataExport(){
       $from_date = Session::get('tfocfromdate');   $to_date = Session::get('tfoctodate');  $search = Session::get('tfocsearch'); 
            $orderBy ='ct.id ASC';
		    $start = ""; $length= null;
		    $out="p_total_count";
		    $sql="CALL REPORT_BPLO_TAX_FEE_OTHER_CHARGES('$from_date','$to_date','$start','$length','$search','$orderBy',@$out)";
		    
		    $data = DB::select($sql);
        return $data;
  }
}
