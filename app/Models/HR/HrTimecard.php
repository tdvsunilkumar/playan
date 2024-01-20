<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class HrTimecard extends Model
{
  protected $guarded = ['id'];
  public function schedule() 
    { 
        return $this->hasOne(HrDefaultSchedule::class, 'id', 'hrds_id'); 
    }
    public function holiday() 
    { 
        return $this->hasOne(HrHolidayType::class, 'id', 'hrht_id'); 
    }
    public function getHolidayNameAttribute() 
    { 
        return ($this->holiday)? $this->holiday->hrht_description: ''; 
    }
    public function updateData($id,$columns){
        return DB::table('hr_timecards')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_timecards')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_timecards')->where('id',$id)->update($columns);
    } 
    public function getDepartments(){
    	 return DB::table('acctg_departments')->select('id','name as shortname')->get();
    }
     public function getDivisions(){
    	 return DB::table('acctg_departments_divisions')->select('id','name')->get();
    }
    public function getAndUpdateWorkHours(){
      $timecard = $this;
      $aut = [];
      $actual_in = Carbon::parse($timecard->hrtc_time_in);
      $actual_out = Carbon::parse($timecard->hrtc_time_out);
      
      if ($timecard->schedule) {
          $all_lates = 0;
          if ($timecard->hrtc_time_in) {
              $schedule_in = Carbon::parse($timecard->schedule->hrds_start_time);
              if ($schedule_in->lessThan($actual_in)) {
                  $aut['hrtc_late'] = $schedule_in->diffInMinutes($actual_in);
                  $all_lates += $aut['hrtc_late'] / 60;
              } else {
                $aut['hrtc_late'] = 0;
              }
          }
          if ($timecard->hrtc_time_out) {
              $schedule_out = Carbon::parse($timecard->schedule->hrds_end_time);
              $actual = ($actual_out->minute >= 30)? 0.5 : 0;
              $sched_min = ($schedule_out->minute >= 30)? 0.5 : 0;
              $plus_late = ($schedule_out->minute < 59)? 0 : 1;
              $actual_min = $actual - $sched_min;
              $late_out = $schedule_out->diffInHours($actual_out);
              // if ($timecard->id == 26) {
              //   # code...
              //   dd($actual_min);
              // }
              if ($actual_out->lessThan($schedule_out)) { 
                  $aut['hrtc_undertime'] = $late_out + $actual_min + $plus_late ;
                  $all_lates += $aut['hrtc_undertime'];
              } else {
                  $aut['hrtc_undertime'] = 0;
                  $aut['hrtc_ot'] = $late_out + $actual_min + $plus_late;
              }
          }
          $aut['hrtc_hours_work'] = round(8 - $all_lates,2);
      } else {
          $aut['hrtc_hours_work'] = $actual_in->diffInMinutes($actual_out);
      }
      $timecard->update($aut);
    }
    public function getList($request, $id){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate =$request->input('todate');
    $department =$request->input('department');
    $division =$request->input('division');
    $hr_employeesid=$request->input('hr_employeesid');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"id",
      1 =>"hrtc_employeesidno",
      2 =>"he.fullname",
      3 =>"ad.shortname",
      4 =>"add.name",
      5 =>"hrtc_date",
    );

   $sql = self::join('hr_employees as he','hr_timecards.hrtc_employeesid','=','he.id')->leftjoin('acctg_departments as ad','hr_timecards.hrtc_department_id','=','ad.id')->leftjoin('acctg_departments_divisions as add','hr_timecards.hrtc_division_id','=','add.id')
          ->select('add.name as division','ad.shortname as department','he.fullname','hrtc_employeesidno','hr_timecards.*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql = $sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ad.shortname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(add.name)'),'like',"%".strtolower($q)."%");
			});
		}
		if(!empty($department) && isset($department)){
      $sql = $sql->where('hr_timecards.hrtc_department_id',trim($department));  
    }
      if(!empty($division) && isset($division)){
        $sql = $sql->where('hr_timecards.hrtc_division_id',trim($division));  
    }
    if(!empty($hr_employeesid) && isset($hr_employeesid)){
        $sql = $sql->where('hr_timecards.hrtc_employeesid',$hr_employeesid);  
    }
    if(!empty($startdate) && isset($startdate)){
        $startdate = date('Y-m-d',strtotime($startdate)); 
        $sql = $sql->whereDate('hr_timecards.hrtc_date','>=',trim($startdate));  
    }
    if(!empty($enddate) && isset($enddate)){
        $enddate = date('Y-m-d',strtotime($enddate)); 
        $sql = $sql->whereDate('hr_timecards.hrtc_date','<=',trim($enddate));  
    }
    if ($id) {
      $sql = $sql->where('hr_timecards.hrtc_employeesid',$id);  
    }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql = $sql->orderBy('hr_timecards.hrtc_date','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
  public function disapproveTimecard($data){
    // dd($data['hrtc_employeesid']);
    return self::where([
      'hrtc_employeesid' => $data['hrtc_employeesid'],
      'hrtc_date' => $data['hrtc_date'],
    ])->update(
      $data
    );
  }

}
