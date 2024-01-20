<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use App\Models\HrEmployee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Traits\ModelUpdateCreate;

class HrWorkSchedule extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $appends = array('employee_name');

    public function employee() 
    { 
        return $this->belongsTo(HrEmployee::class, 'hr_employeesid', 'id'); 
    }
    public function getEmployeeNameAttribute(){
        return $this->employee->fullname;
    }
    public function time_schedule() 
    { 
        return $this->hasOne(HrDefaultSchedule::class, 'id', 'hrds_id'); 
    }

    public function getTimecardAttribute() 
    { 
        return HrTimecard::where([
            ['hrtc_date',$this->hrds_date],
            ['hrtc_employeesid',$this->hr_employeesid],
        ])->first(); 
    }
    function getIfHasTimecardAttribute(){
        $timecard = HrTimecard::where([
            ['hrtc_date',$this->hrds_date],
            ['hrtc_employeesid',$this->hr_employeesid],
        ])
        ->whereNotNull(['hrtc_time_in','hrtc_time_out'])
        ->first();
        return $timecard;
    }
    public function getLeaveAttribute() 
    { 
        return HrLeave::where([
            ['hr_employeesid',$this->hr_employeesid],
            ['hrla_approved_by','!=',0],
            ['hrla_reviewed_by','!=',0],
            ['hrla_noted_by','!=',0],
            ['hrla_status',6],
        ])
        ->whereRaw("'".$this->hrds_date."' BETWEEN hrl_start_date AND hrl_end_date")
        ->first(); 
    }
    public function updateWorkSched($data,$updateby = null) {
        $data = (object)$data;
        if (!isset($data->end_date)) {
            $data->end_date = Carbon::parse($data->start_date)->endOfYear()->toDateString();
            # code...
        }
        // dd($data->end_date);
        $ranges = CarbonPeriod::create($data->start_date,'1 day',$data->end_date);
        foreach ($ranges as $range) {
            $holiday = HrHolidays::where('hrh_date',$range->toDateString())->first();
            if ($range->isWeekday() && !($holiday)) {
                $update = [
                    'hrds_id' => $data->hrds_id,
                ];
                if ($updateby) {
                    $update = [
                        'hrds_id' => $data->hrds_id,
                        'updated_by' => $updateby,
                    ];
                }
                self::updateOrCreate(
                    [
                        'hr_employeesid' => $data->hr_employeesid,
                        'hrds_date' => $range->toDateString(),
                        'year' => $range->year,
                        'month' => $range->month,
                    ],
                    $update
                    );
                $timecard = HrTimecard::where([
                    'hrtc_employeesid' => $data->hr_employeesid,
                    'hrtc_date' => $range->toDateString()
                ])->first();
                self::updateTimecardAtWorksched($timecard, $data->hrds_id);
            }
            
        }
    }
    public function updateTimecardAtWorksched($timecard, $sched_id){
        if ($timecard) {
            $schedule = HrDefaultSchedule::find($sched_id);
            $timecard->update([
                'hrds_id' => $sched_id,
                'hrtc_work_sched_in'=> $schedule->hrds_start_time,
                'hrtc_work_sched_out'=> $schedule->hrds_end_time,
            ]);
            $timecard->getAndUpdateWorkHours();
        }
    }
    public function updateData($id,$columns){
        return DB::table('hr_work_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_work_schedules')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_work_schedules')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getDefaultschedule(){
         return DB::table('hr_default_schedules')->select('id','hrds_start_time','hrds_end_time')->get();
    }
    public function checkisexistdata($year,$employeeid){
        return DB::table('hr_work_schedules')->select('id')->where('year',$year)->where('hr_employeesid',$employeeid)->get();
    }
    public function getWorkScheduleByEmpId($user_id, $range){
        $work_schedules= self::where('hr_employeesid',$user_id)
                        ->whereBetween('hrds_date',[$range['start'],$range['end']])
                        ->select('hr_work_schedules.*')->get();
        $sched_eve=[];
            foreach($work_schedules as $i=>$data){
                // dd($data);
                $hr_default_schedules=$data->time_schedule;
                $start_time = Carbon::createFromFormat('H:i:s', $hr_default_schedules->hrds_start_time)->format('h:i A');
                $end_time = Carbon::createFromFormat('H:i:s', $hr_default_schedules->hrds_end_time)->format('h:i A');
                $title=$start_time." - ".$end_time;
                $date=$data->hrds_date;
                $sched_eve[$i] = [
                    'id' => 1,
                    'title' => ' '.$title,
                    'start' => $date,
                    'end' => $date,
                    'backgroundColor' => '#26580F'
                ];
            }
        return  $sched_eve;               
    }
    
    public function getTimecardByEmpId($user_id, $range){
        $work_schedules= self::where('hr_employeesid',$user_id)
                        ->whereBetween('hrds_date',[$range['start'],$range['end']])
                        ->select('hr_work_schedules.*')->get();
        $sched_eve=[];
            // dd($work_schedules);
            foreach($work_schedules as $i=>$data){
                $logs = HrTimecard::where([
                    ['hrtc_employeesid',$user_id],
                    ['hrtc_date',$data->hrds_date],
                    ])
                ->first();
                $date=$data->hrds_date;
                $leave = HrLeave::where([
                    ['hr_employeesid',$user_id],
                    ['hrla_reviewed_by','!=',0],
                    ['hrla_approved_by','!=',0],
                    ['hrla_noted_by','!=',0],
                    ['hrla_status',6],
                ])
                ->whereRaw("'".$data->hrds_date."' BETWEEN hrl_start_date AND hrl_end_date")->first();
                
                if ($logs) {
                    $start_time = $logs->hrtc_time_in ? Carbon::createFromFormat('H:i:s', $logs->hrtc_time_in)->format('h:i A') : '';
                    $end_time = $logs->hrtc_time_out ? Carbon::createFromFormat('H:i:s', $logs->hrtc_time_out)->format('h:i A') : '';
                    $title=$start_time." - ".$end_time;
                    $sched_eve[$i+1] = [
                        'id' => 2,
                        'title' => $title,
                        'start' => $date,
                        'end' => $date,
                    ];
                    if ($start_time && $end_time) {
                        $sched_eve[$i+1]['backgroundColor'] = '#DD571C';
                    } else {
                        $sched_eve[$i+1]['backgroundColor'] = '#01FFFF';
                    }
                    $official_work = HrOfficialWork::where([
                        'hr_employeesid' => $user_id,
                        'hrow_work_date'=>$date,
                        'hrow_status' => 6,
                    ])->first();
                    if ($official_work) {
                        $sched_eve[$i+1]['backgroundColor'] = '#7C4700';
                        
                    }
                } else {
                    if ($leave) {

                    } else {
                        $sched_eve[$i+1] = [
                            'id' => 2,
                            'title' => 'No Logs',
                            'start' => $date,
                            'end' => $date,
                            'backgroundColor' => '#b80f0a'
                        ];
                    }
                    
                }
            }

            $today = HrBiometricsRecord::where([
                ['hrbr_emp_id',$user_id],
                ['hrbr_date',Carbon::now()->toDateString()]
                ])->first();
                if ($today) {
                    $sched_eve[0] = [
                        'id' => 2,
                        'title' => $today->hrbr_time,
                        'start' => $today->hrbr_date,
                        'end' => $today->hrbr_date,
                        'backgroundColor' => '#DD571C'
                    ];
                }
        return  $sched_eve;               
    }
    public function getLeavesByEmpId($user_id, $range){
        $work_schedules= self::where('hr_employeesid',$user_id)
                        ->whereBetween('hrds_date',[$range['start'],$range['end']])
                        ->select('hr_work_schedules.*')->get();
        $sched_eve=[];
        foreach($work_schedules as $i=>$data){
            $date=$data->hrds_date;
            $leave = HrLeave::where([
                ['hr_employeesid',$user_id],
                ['hrla_reviewed_by','!=',0],
                ['hrla_approved_by','!=',0],
                ['hrla_noted_by','!=',0],
                ['hrla_status',6],
            ])
            ->whereRaw("'".$data->hrds_date."' BETWEEN hrl_start_date AND hrl_end_date")->first();
            if ($leave) {
                $sched_eve[$i+1] = [
                    'id' => 2,
                    'title' => 'Leave for '.$leave->leave_type->hrla_description,
                    'start' => $date,
                    'end' => $date,
                    'backgroundColor' => '#8080FF'
                ];
            }
        }
        return  $sched_eve;               
    }
    public function validate_approver($department, $sequence, $type, $slugs, $user)
    {   
        $query = '';
        if ($sequence == 1) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.primary_approvers)';
        } else if ($sequence == 2) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.secondary_approvers)';
        } else if ($sequence == 3) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.tertiary_approvers)';
        } else {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.quaternary_approvers)';
        }

        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('*')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->whereRaw($query)
            ->where(['menu_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->count();
        } else {
            $res = UserAccessApprovalApprover::select('*')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
            })
            ->whereRaw($query)
            ->where(['menu_sub_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department ])
            ->count();
        }

        return $res;
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $select_date=$request->input('select_date');
        $department=$request->input('department');
        if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
        }

        $columns = array( 
        0 =>"id",
        1 =>"he.fullname",
        2 =>"hrds_date",
        4 =>"is_active",
        );

        $sql = HrAppointment::leftjoin('hr_employees as he','hr_appointment.hr_emp_id','=','he.id')
                ->leftjoin('hr_work_schedules as hws','hws.hr_employeesid','=','he.id')->leftjoin('hr_default_schedules as hds','hws.hrds_id','=','hds.id')
                ->select('he.fullname','hws.year','hws.month','hrds_date','hds.hrds_start_time','hds.hrds_end_time','hws.id as id');

        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
                });
            }
            if(!empty($select_date) && isset($select_date)){
                $sql = $sql->where(function ($sql) use($select_date) {
                    $sql->where('hrds_date',$select_date);
                });
            } else {
                $now = Carbon::now();
                $sql = $sql->where(function ($sql) use($now) {
                    $sql->where('hrds_date',$now->toDateString());
                });
            }
            if ($department) {
                $sql = $sql->where('hra_department_id',$department);
            }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql = $sql->orderBy('hws.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

    

}
