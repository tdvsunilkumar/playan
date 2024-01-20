<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
class HoReportingRange extends Model
{

    // public function updateData($id,$columns){
    //     return DB::table('ho_reporting_ranges')->where('id',$id)->update($columns);
    // }
    // public function addData($postdata){
    //     return DB::table('ho_reporting_ranges')->insert($postdata);
    // }
    // public function updateActiveInactive($id,$columns){
    //   return DB::table('ho_reporting_ranges')->where('id',$id)->update($columns);
    // }
    public function getRange(){
      return config('constants.reportingDateRanges');
    }
    
    public function getRangeDetails($id){
      return config('constants.reportingDateRanges')[$id];
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $range = $request->input('range');
        $request->session()->put('landSelectedRange',$range);

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
      $columns = array( 
                0 =>"", 
                1 =>"diag_name",
                2 =>"icd10_details",
                2 =>"totalM",
                2 =>"totalF",
                2 =>"grandtotal"
              );
              $sql = HoDiagnosis::select(
                      'ho_diagnoses.*',
                      DB::raw("sum(IF (ho_medical_record_diagnoses.cit_gender = 0,1,0)) AS 'totalM'"),
                      DB::raw("sum(IF (ho_medical_record_diagnoses.cit_gender = 1,1,0)) AS 'totalF'"),
          
                      DB::raw("count(ho_medical_record_diagnoses.id) AS 'grandtotal'")
                    );
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(diag_name)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(icd10_details)'),'like',"%".strtolower($q)."%");
                });
              }
                  // sort date
              if(!empty($range) && isset($range)){
                  $year = Carbon::now()->year;
                  $range = self::getRangeDetails($range);
                  if (is_array($range['data'])) {
                    $startMonth = $range['data'][0];
                    $endMonth = $range['data'][1];
                  } else {
                    $startMonth = $range['data'];
                    $endMonth = $range['data'];
                  }
                  $start = Carbon::parse($startMonth.'/1/'.$year)->startofmonth()->toDateTimeString();
                  $end = Carbon::parse($endMonth.'/1/'.$year)->endofmonth()->toDateTimeString();
              } else {
                $start = Carbon::now()->startofmonth()->toDateTimeString();
                $end = Carbon::now()->endofmonth()->toDateTimeString();
              }
                $sql = $sql->leftJoin(DB::raw("(select * from ho_medical_record_diagnoses where created_at BETWEEN '".$start."' AND '".$end."') as ho_medical_record_diagnoses "),
                'ho_medical_record_diagnoses.disease_id','ho_diagnoses.id');//i give up lol
              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql=$sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql=$sql->orderBy('ho_diagnoses.diag_name','ASC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->distinct('ho_diagnoses.id')->count('ho_diagnoses.id');
              /*  #######  Set Offset & Limit  ###### */
              $sql=$sql->groupBy('ho_diagnoses.id')->offset((int)$params['start'])->limit((int)$params['length']);
              

              $data=$sql->groupBy('ho_diagnoses.id')->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
        }
