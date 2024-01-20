<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrHolidays extends Model
{
    public $table = 'hr_holidays';

    public function holiday_type() 
    { 
        return $this->hasOne(HrHolidayType::class, 'id', 'hrht_id'); 
    }
    public function updateData($id,$columns){
      // dd($columns['hrh_date']);
      $this->removeSchedules($columns['hrh_date']);
        return DB::table('hr_holidays')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
      $this->removeSchedules($postdata['hrh_date']);
      DB::table('hr_holidays')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function removeSchedules($date){
      HrWorkSchedule::where('hrds_date',$date)->delete();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_holidays')->where('id',$id)->update($columns);
    } 
    public function getHolidayType(){
      return DB::table('hr_holiday_types')->where('is_active',1)->orderBy('hrht_description')->get();
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
      0 =>"id",
      1 =>"hr_holidays.hrh_date",
      3 =>"hr_holidays.hrh_description",
      5 =>"is_active",
    );

    $sql = DB::table('hr_holidays')
          ->leftjoin('hr_holiday_types', 'hr_holiday_types.id', '=', 'hr_holidays.hrht_id')
          ->select('hr_holidays.*','hr_holiday_types.hrht_description as holiday_type');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hr_holidays.hrh_description)'),'like',"%".strtolower($q)."%")
            ->orWhere(DB::raw('LOWER(hr_holiday_types.hrht_description)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('hr_holidays.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
