<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptCtoPaymentSchedule extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_cto_payment_schedules')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('rpt_cto_payment_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('rpt_cto_payment_schedules')->insert($postdata);
	}
    public function getCtoPaymentSchedule(){
       return DB::table('rpt_cto_payment_schedules')->select('*')->get();
    }
    public function editCtoPaymentSchedule($id){
        return DB::table('rpt_cto_payment_schedules')->where('id',$id)->first();
    }

    public function getSdCode(){
        return DB::table('schedule_descriptions')->select('id','sd_mode','sd_description','sd_description_short')->where('is_active',1)->get();
    }
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year = $request->input('year');
        $request->session()->put('paymentScheduleYear',$year);
        //dd(session()->get('paymentScheduleYear'));
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"rcpsched_year",  
          2 =>"sd_mode",
          3 =>"rcpsched_date_start",
          4 =>"rcpsched_date_end",
          5 =>"rcpsched_penalty_due_date",  
          6 =>"rcpsched_discount_due_date",
          7 =>"rcpsched_discount_rate",
          8 =>"is_active",
                     
        );

        $sql = DB::table('rpt_cto_payment_schedules AS rcps')
              ->join('schedule_descriptions AS sd', 'sd.id', '=', 'rcps.sd_mode')
              ->select('rcps.id','rcps.rcpsched_year','rcps.rcpsched_date_start','rcps.rcpsched_date_end','rcps.rcpsched_penalty_due_date',
              'rcps.rcpsched_discount_due_date','rcps.rcpsched_discount_rate','sd.sd_mode','sd.sd_description',
              'sd.sd_description_short','rcps.is_active');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw('LOWER(rcps.sd_mode)'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('LOWER(sd.sd_description)'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('LOWER(sd.sd_description_short)'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('DATE_FORMAT(rcps.rcpsched_date_start,"%d/%m/%Y")'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('DATE_FORMAT(rcps.rcpsched_date_end,"%d/%m/%Y")'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('DATE_FORMAT(rcps.rcpsched_penalty_due_date,"%d/%m/%Y")'),'like',"%".strtolower($q)."%");
                $sql->orWhere(DB::raw('DATE_FORMAT(rcps.rcpsched_discount_due_date,"%d/%m/%Y")'),'like',"%".strtolower($q)."%");
                $sql->orWhere('rcps.rcpsched_discount_rate',$q);
                                
            });
        }if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rcps.rcpsched_year',$year);

            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rcps.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
