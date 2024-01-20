<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BploAssessPaymentSchedule extends Model
{
    public function updateData($id,$columns){
    return DB::table('bplo_assess_payment_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('bplo_assess_payment_schedules')->insert($postdata);
		return DB::getPdo()->lastInsertId();
	}
	public function getmodes(){
		return DB::table('payments_schedulesmodes')->select('mode','psched_description','psched_short_desc')->get();
	}


  
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"psched_year",
      2 =>"psched_mode_no",
      3 =>"psched_description",
      4 =>"psched_short_desc",
      5 =>"psched_date_start",	
      6 =>"psched_date_end",
      7 =>"psched_penalty_due_date",
      8 =>"psched_discount_due_date",     
    );

    $sql = DB::table('bplo_assess_payment_schedules')
          ->select('id','psched_year','psched_mode_no','psched_description','psched_short_desc','psched_date_start','psched_date_end','psched_penalty_due_date','psched_discount_due_date');

    //$sql->where('created_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(psched_year)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(psched_mode_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(psched_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(psched_short_desc)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
