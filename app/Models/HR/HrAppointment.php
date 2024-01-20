<?php

namespace App\Models\HR;
use App\Models\HrEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrAppointment extends Model
{
    public $table = 'hr_appointment';
    protected $guarded = ['id'];
    // relate
    public function employee() 
    { 
        return $this->belongsTo(HrEmployee::class, 'hr_emp_id', 'id'); 
    }
    public function appoint_status() 
    { 
        return $this->belongsTo(HrAppointmentStatus::class, 'hras_id', 'id'); 
    }
    public function income_deduction() 
    {  
        return $this->hasMany(HrIncomeDeduction::class, 'emp_id', 'hr_emp_id'); 
    }
    public function work_schedule() 
    { 
        return $this->hasMany(HrWorkSchedule::class, 'hr_employeesid', 'hr_emp_id'); 
    }
    public function timecards() 
    { 
        return $this->hasMany(HrTimecard::class, 'hrtc_employeesid', 'hr_emp_id'); 
    }
    public function timekeep() 
    { 
        return $this->hasMany(HrTimekeeping::class, 'hrtk_emp_id', 'hr_emp_id'); 
    }
    public function leaves() 
    { 
        return $this->hasMany(HrLeave::class, 'hr_employeesid', 'hr_emp_id'); 
    }
    public function loans() 
    { 
      return $this->hasMany(HrLoanLedger::class, 'hrll_employeesid', 'hr_emp_id'); 
    }
    public function loan_app() 
    { 
      return $this->hasMany(HrLoanApplication::class, 'hrla_employeesid', 'hr_emp_id'); 
    }
    public function getLoanBreakdownAttribute() 
    { 
        return HrLoanLedger::join('hr_loan_applications as hrla', 'hrla.id', 'hr_loan_ledger.hrla_id')->where([
          'hrll_employeesid'=>$this->hr_emp_id,
        ]); 
    }
    public function overtimes() 
    { 
        return $this->hasMany(HrOverTimeModels::class, 'hr_employeesid', 'hr_emp_id'); 
    }
    public function changesched() 
    { 
        return $this->hasMany(ChangeofSchedule::class, 'hr_employeesid', 'hr_emp_id'); 
    }
    public function time_keep_hours($cutoff = null)
    {
      $hours = 0;
      $aut = 0;
      $aut_cost = 0;
      $leaves = 0;
      $aut_dates = [];
      $timekeep = $this->timekeep->where('hrcp_id',$cutoff)->first();
      // if ($timekeep) {
      //   return (object)[
      //     'process_date' => $timekeep->hrtk_date,
      //     'hours' => round($timekeep->hrtk_total_hours,2),
      //     'aut' => round($timekeep->hrtk_total_aut,2),
      //     'aut_cost' => round($timekeep->hrtk_aut_cost,2),
      //     'leaves' => $timekeep->hrtk_total_leave,
      //   ];
      // }

      if ($cutoff) {
        $cutoff = CuttoffPeriod::find($cutoff);
        $now = Carbon::parse($cutoff->hrcp_date_from);
      } else {
        $now = Carbon::today();
        $cutoff = CuttoffPeriod::whereRaw("'".$now->toDateString()."' BETWEEN hrcp_date_from AND hrcp_date_to")->first();
        $cutoff->hrcp_date_to = $now->toDateString();
      }
        $workdays = $this->work_schedule->whereBetween('hrds_date',[$cutoff->hrcp_date_from,$cutoff->hrcp_date_to]);
        foreach ($workdays as $working_day) {
          // dd($working_day->if_has_timecard);
          // $holiday = HrHolidays::where('hrh_date',$working_day->toDateString())->first();
            if ($working_day->if_has_timecard) {
              $timecard = $working_day->timecard;
              $per_min_cost = $this->hra_hourly_rate / 60;
              $late_cost = $per_min_cost * $timecard->hrtc_late;
              $undertime_cost = $this->hra_hourly_rate * $timecard->hrtc_undertime;
              $late = $timecard->hrtc_late / 60;
              $aut_sum = (float)$timecard->hrtc_undertime + $late;
              $aut += $aut_sum;
              $aut_cost += $late_cost + $undertime_cost;
              $hours += $timecard->hrtc_hours_work;
              if ($aut_sum) {
                $aut_dates[] = [
                  'date' => $working_day->hrds_date,
                  'hours' => $aut_sum
                ];
              }
            } else {
              if ($working_day->leave) {
                $leave_hrs = $working_day->leave->leave_hours;
                if ($working_day->leave->hrlt_id === 1) {
                  $aut_cost += (float)$this->hra_hourly_rate * $leave_hrs;
                }
                $leaves += $leave_hrs;
              } else {
                $aut += 8;
                $aut_dates[] = [
                  'date' => $working_day->hrds_date,
                  'hours' => 8
                ];
                $aut_cost += (float)$this->hra_hourly_rate * 8;
              }
            }
        }
      

      return (object)[
        'leaves' => $leaves,
        'aut_list' => $aut_dates,
        'hours' => round($hours,2),
        'aut' => round($aut,2),
        'aut_cost' => round($aut_cost,2),
        'process_date' => '',
      ];
    }

    public function time_keep_overtime($cutoff = null)
    {
      $ot_count = 0;
      $timekeep = $this->timekeep->where('hrcp_id',$cutoff)->first();
      if ($timekeep) {
        return round($timekeep->hrtk_total_overtime,2);
      }
      if ($cutoff) {
        $cutoff = CuttoffPeriod::find($cutoff);
        $now = Carbon::parse($cutoff->hrcp_date_from);
      } else {
        $now = Carbon::today();
        $cutoff = CuttoffPeriod::whereRaw("'".$now->toDateString()."' BETWEEN hrcp_date_from AND hrcp_date_to")->first();
      }
      $overtimes = $this->overtimes->where('hrot_is_process',0)->where('hrot_work_date','<=',$cutoff->hrcp_date_to)->where('hro_approved_by','!=',0)->where('hro_reviewed_by','!=',0)->where('hro_noted_by','!=',0)->where('hro_status',6);
        //
      foreach ($overtimes as $overtime) {
        $ot_count += $overtime->hrot_considered_hours;
      }
      return $ot_count;
    }

    public function time_keep_leave($cutoff = null)
    {
      $leave_count = 0;
      $timekeep = $this->timekeep->where('hrcp_id',$cutoff)->first();
      if ($timekeep) {
        return round($timekeep->hrtk_total_leave,2);
      }
      if ($cutoff) {
        $cutoff = CuttoffPeriod::find($cutoff);
        $now = Carbon::parse($cutoff->hrcp_date_from);
      } else {
        $now = Carbon::today();
        $cutoff = CuttoffPeriod::whereRaw("'".$now->toDateString()."' BETWEEN hrcp_date_from AND hrcp_date_to")->first();
      }

      $leaves = $this->leaves->whereBetween('hrl_start_date',[$cutoff->hrcp_date_from,$cutoff->hrcp_date_to])->where('hrla_approved_by','!=',0)->where('hrla_reviewed_by','!=',0)->where('hrla_noted_by','!=',0)->where('hrla_status',6);
        //
      foreach ($leaves as $leave) {
        if($leave->hrla_id != 1){
          $leave_count += 4;
        } else {
          $start = Carbon::parse($this->hrl_start_date);
          $end = Carbon::parse($this->hrl_end_date);
          $leave_used = $start->diffInDays($end) + 1;
          $leave_count += $leave_used * 8;
        }
      }
      return $leave_count;
    }

    public function remaining_leave($code)
    {
      $remaining = DB::table('hr_leave_earning_adjustment_detail as leaves')
      ->join('hr_leave_adjustments as hrla', 'hrla.id', 'leaves.hrlea_id')
      ->join('hr_leavetypes as hrlt', 'hrlt.id', 'leaves.hrlt_id')
      ->where([
          ['hrlead_balance', '>', 0],
          ['hrlt.hrlt_leave_code', $code],
          ['hr_employeesid', $this->hr_emp_id],
      ])
      ->first();
      return $remaining ? $remaining->hrlead_balance : 0;
    }

    public function updateData($id,$columns){
        return DB::table('hr_appointment')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_appointment')->insert($postdata);
        $hr_appointment=DB::table('hr_appointment')->orderBy('id','DESC')->first();
        return $hr_appointment->id;
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_appointment')->where('id',$id)->update($columns);
    } 
    public function find($id){
        return DB::table('hr_appointment')->where('id',$id)->first();
      } 
    public function findLatest(){
        return DB::table('hr_appointment')->orderBy('id','DESC')->first();
      } 
    public function getUserdapartment($id){
        $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    } 
    public function getDepartment(){
        return DB::table('acctg_departments')->orderBy('name')->get();
      }  
    public function getEmpStatus(){
        return DB::table('hr_employee_statuses')->where('is_active',1)->orderBy('hres_description')->get();
      }
    public function getAptStatus(){
        return DB::table('hr_appointment_status')->orderBy('hras_description')->get();
      }
    public function getPaymentTerm(){
        return DB::table('hr_payment_term')->orderBy('hr_payment_term')->get();
      }  
    public function getOccuLev(){
        return DB::table('hr_occupation_levels')->where('is_active',1)->orderBy('hrol_description')->get();
      }
    public function getSalaryGrade(){
        return DB::table('hr_salary_grades')->orderBy('hrsg_salary_grade')->get();
      } 
    public function getSalaryGradeStep(){
        return DB::table('hr_salary_grade_steps')->orderBy('hrsgs_description')->get();
      }   
      
    public function getDivByDept($id){
        return DB::table('acctg_departments_divisions')->where('acctg_department_id',$id)->orderBy('name')->get();
      }
    public function getEmpByDiv($id){
        return DB::table('hr_employees')->where('acctg_department_division_id',$id)->orderBy('fullname')->get();
      }    
    public function getEmpdetById($id){
      $employee = DB::table('hr_employees')
      ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
      ->where('hr_employees.id', $id)
      ->select('hr_designations.description as designation', 'hr_employees.*')
      ->first();

        return $employee;
      } 
    public function getSalaryDet($grade_id){
        $sal_grade = DB::table('hr_salary_grades')
        ->where('id', $grade_id)
        ->select('hr_salary_grades.*')
        ->first();
        return $sal_grade;
    }     
          
    public function fetch_destination($id){
      $data= DB::table('hr_employees')
      ->where('hr_employees.user_id',$id)
      ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
      ->select('hr_designations.description')->first();
      return $data;
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
    public function getList($request)
      {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $department=$request->input('department');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          1 =>"he.fullname",
          2 =>"ad.name",
          3 =>"add.name",
          4 =>"hd.description",
          5 =>"ha.hra_monthly_rate",
          6 =>"ha.is_active"	
        );

        $sql = DB::table('hr_appointment AS ha')
              ->leftjoin('hr_employees AS he', 'he.id', '=', 'ha.hr_emp_id')
              ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
              ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
              ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
              ->leftjoin('hr_salary_grades AS hsg', 'hsg.id', '=', 'ha.hrsg_id')
              ->leftjoin('hr_salary_grade_steps AS hsgs', 'hsgs.id', '=', 'ha.hrsgs_id')
              ->select('ha.*','he.fullname as emp_name','hsg.hrsg_salary_grade','hsgs.hrsgs_description','ha.hra_employee_no as emp_id','ad.name as dept_name','add.name as div_name','hd.description as designation');
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
          $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(add.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(hd.description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ha.hra_monthly_rate)'),'like',"%".strtolower($q)."%");
          });
        }
        if ($department) {
            $sql->where('hra_department_id',$department);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
          $sql->orderBy('id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      public function getEmployee($search="",$div = null)
      {
          $page=1;
          if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
          }
          $length = 20;
          $offset = ($page - 1) * $length;
          $sql = self::join('hr_employees', 'hr_appointment.hr_emp_id','hr_employees.id')->where([['hr_employees.is_active',1],['hr_appointment.is_active',1]]);
          // $sql = HrEmployee::where('is_active',1);
          if(!empty($search)){
              $sql->where(function ($sql) use($search) {
                      if(is_numeric($search)){
                          $sql->Where('id',$search);
                      }else{
                          $sql->where(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($search)."%");
                      }
              });
          }
          if ($div) {
            $sql->Where('hr_employees.acctg_department_division_id',$div);
            # code...
          }
          $sql->orderBy('hr_employees.fullname','ASC');
          $data_cnt=$sql->count();
          $sql->offset((int)$offset)->limit((int)$length);
          
          $data=$sql->get();
          return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
   
}
