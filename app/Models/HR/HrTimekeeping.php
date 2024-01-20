<?php

namespace App\Models\HR;
use App\Models\HrEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use App\Traits\ModelUpdateCreate;
use DB;
use Carbon\Carbon;

class HrTimekeeping extends Model
{
    use ModelUpdateCreate;
    public $table = 'hr_timekeeping';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function appointment() 
    { 
        return $this->hasOne(HrAppointment::class, 'hr_emp_id', 'hrtk_emp_id'); 
    }
    public function updateData($id,$columns){
        return DB::table('hr_timekeeping')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_timekeeping')->insert($postdata);
        $hr_timekeeping=DB::table('hr_timekeeping')->orderBy('id','DESC')->first();
        return $hr_timekeeping->id;
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_timekeeping')->where('id',$id)->update($columns);
    } 
    // public function find($id){
    //     return DB::table('hr_timekeeping')->where('id',$id)->first();
    //   } 
    public function currentCutoff($now){
      $cut_off_period = CuttoffPeriod::whereRaw("'".$now."' BETWEEN hrcp_date_from AND hrcp_date_to")->first();
      return $cut_off_period? $cut_off_period : (object)[];
    } 
    

    public function findLatest(){
        return DB::table('hr_timekeeping')->orderBy('id','DESC')->first();
      } 
    public function getUserdapartment($id){
        $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    } 
    public function getDepartment(){
        return DB::table('acctg_departments')->orderBy('name')->get();
      }  
    public function getDivByDept($id){
        return DB::table('acctg_departments_divisions')->where('acctg_department_id',$id)->orderBy('name')->get();
      }
    public function getEmpByDiv($id){
        return DB::table('hr_employees')->where('acctg_department_division_id',$id)->orderBy('fullname')->get();
    }
    public function getCutoffPeriod(){
        return DB::table('hr_cutoff_period')->where('hrcp_status',1)->orderBy('hrcp_date_from','DESC')->get();
    }
    public function getList($request)
      {
        // dd($request->all());
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $hrtk_department_id=$request->input('hrtk_department_id');
        $hrtk_division_id=$request->input('hrtk_division_id');
        $hrtk_emp_id=$request->input('hrtk_emp_id');
        $cut_off_period=$request->input('cut_off_period');
        $hrtk_is_processed=$request->input('hrtk_is_processed');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          1 =>"he.identification_no",
          2 =>"he.fullname",
          3 =>"ad.name",
          4 =>"add.name",
          5 =>"hbr.hrbr_date",
          6 =>"hbr.hrbr_time",	
        );

        $sql = HrAppointment::leftjoin('hr_employees AS he' , 'he.id', '=','hr_appointment.hr_emp_id')
              ->leftjoin(DB::raw('(
              SELECT * from hr_timekeeping WHERE hrcp_id = '.$cut_off_period.'
              ) as `ht`'), 'he.id', '=', 'ht.hrtk_emp_id')
              ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
              ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
              ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id');
              
        if(!empty($q) && isset($q)){
          $sql = $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(add.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('he.identification_no'),'like',"%".strtolower($q)."%");
          });
        }
        if(!empty($hrtk_department_id) && isset($hrtk_department_id)){
          $sql = $sql->where('hra_department_id',$hrtk_department_id);
        }
        if(!empty($hrtk_division_id) && isset($hrtk_division_id)){
          $sql = $sql->where('hra_division_id',$hrtk_division_id);
        }
        if(!empty($hrtk_emp_id) && isset($hrtk_emp_id)){
          $sql = $sql->where('hr_emp_id',$hrtk_emp_id);
        }
          if(!empty($hrtk_is_processed) && isset($hrtk_is_processed)){
            $sql = $sql->where(function ($sql) use($cut_off_period) {
              $sql->whereNotNull("ht.id")
                  ->Where('ht.hrcp_id',$cut_off_period);
            });
          } else {
            $sql = $sql->whereNull('ht.id');
          }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
          $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
          $sql = $sql->orderBy('hr_appointment.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql = $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
   
      public function leaveAutoDeduct($cutoff){
        $cutoff = CuttoffPeriod::find($cutoff);
        $date = Carbon::today()->toDateString();
        $user = Auth::user()->id;
        $leaves_list = DB::table('hr_leave_earning_adjustment_detail as leaves')
                    ->join('hr_leave_adjustments as hrla', 'hrla.id', 'leaves.hrlea_id')
                    ->groupby('hrla.hr_employeesid')
                    ->where('hrlead_balance', '>', 0)
                    ->select(
                        'hr_employeesid',
                        DB::raw('sum(hrlead_balance) as bal'),
                    )
                    ->get();
        foreach ($leaves_list as $leave) {
            $appoint = HrAppointment::where('hr_emp_id',$leave->hr_employeesid)->first();
            // if ($leave->hr_employeesid === 114) {
                $leaves = $appoint->time_keep_hours($cutoff->id);
                if ($leaves->aut) {
                    foreach ($leaves->aut_list as $aut) {
                        $remaining = DB::table('hr_leave_earning_adjustment_detail as leaves')
                        ->join('hr_leave_adjustments as hrla', 'hrla.id', 'leaves.hrlea_id')
                        ->join('hr_leavetypes as hrlt', 'hrlt.id', 'leaves.hrlt_id')
                        ->where([
                            ['hrlead_balance', '>', 0],
                            ['hr_employeesid', $leave->hr_employeesid],
                        ])
                        ->whereIn('hrlt_leave_code', ['MFL'])
                        ->first();
                        $leave_days = $aut['hours']/8;
                        $hrla_id = $aut['hours'] === 8 ? 1 : 0;
                        $app_no = HrLeave::getApplicationNumber();
                        if ($remaining) {
                          $save_leave = HrLeave::addData([
                              'hr_employeesid'=>$leave->hr_employeesid,
                              'applicationno'=>$app_no,
                              'hrl_start_date'=>$aut['date'],
                              'hrl_end_date'=>$aut['date'],
                              'hrlt_id'=>$remaining->hrlt_id,
                              'hrla_id'=>$hrla_id,
                              'leave_hours'=>$aut['hours'],
                              'hrla_reason'=>'Auto Generated on'.Carbon::parse($date)->format('F j, Y'),
                              'days'=>$leave_days,
                              'dayswithpay'=>$leave_days,
                              'remainingdays'=>$remaining->hrlead_balance,
                              'hrla_status'=>6,
                              'hrla_approved_by'=>$user,
                              'hrla_approved_at'=>Carbon::now(),
                              'hrla_reviewed_by'=>$user,
                              'hrla_reviewed_at'=>Carbon::now(),
                              'hrla_noted_by'=>$user,
                              'hrla_noted_at'=>Carbon::now(),
                              'created_by'=>$user,
                              'created_at'=>Carbon::now(),
                          ]);
                          // dd($save_leave);
                          HrLeave::find($save_leave)->useLeaves();
                        }
                        
                    }
                }
            // }
        }
    }
}
