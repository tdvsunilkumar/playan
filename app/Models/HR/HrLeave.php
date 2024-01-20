<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
class HrLeave extends Model
{
    public $table = 'hr_leaves';

    public function employee() 
    { 
        return $this->belongsTo('App\Models\HrEmployee', 'hr_employeesid', 'id'); 
    }
    public function appointment() 
    { 
        return $this->belongsTo('App\Models\HR\HrAppointment', 'hr_employeesid', 'hr_emp_id'); 
    }
    public function leave_type() 
    { 
        return $this->belongsTo(HrLeaveApplication::class, 'hrla_id', 'id'); 
    }
    public function hlt() 
    { 
        return $this->belongsTo(HrLeavetype::class, 'hrlt_id', 'id'); 
    }
    public function getLeaveCodeAttribute() 
    { 
        return $this->hlt ? $this->hlt->hrlt_leave_code : null; 
    }
    public function updateData($id,$columns){
        return DB::table('hr_leaves')->where('id',$id)->update($columns);
    }
    public function addData($postdata){

        DB::table('hr_leaves')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_leaves')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getLeavetypes(){
         return DB::table('hr_leave_earning_adjustment_detail as leaves')
                        ->join('hr_leavetypes as leave_type', 'leave_type.id', 'leaves.hrlt_id')
                        ->join('hr_leave_adjustments as emp_leave', 'emp_leave.id', 'leaves.hrlea_id')
                        ->where('emp_leave.hr_employeesid',Auth::user()->hr_employee->id)
                        ->select('leave_type.id','hrlt_leave_type')
                        ->get();
    }
    public function getApplicationtypes(){
         return DB::table('hr_leave_applications')
         ->where('is_active',1)
         ->select('id','hrla_description')
         ->get();
    }
    public function checkisexistdata($year,$employeeid){
        return DB::table('hr_default_schedules')->select('id')->where('year',$year)->where('hr_employeesid',$employeeid)->get();
    }
    public function getApplicationNumber(){
        $number=1;
        $arrPrev = DB::table('hr_leaves')->select('id')->whereYear('created_at',date("Y"))->orderby('id','DESC')->first();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->id+1;
        }
        $number = date('Y')."-".str_pad($number, 5, '0', STR_PAD_LEFT);
        return $number;
    } 
    public function fetch_destination($id){
        $data= DB::table('hr_employees')
        ->where('hr_employees.user_id',$id)
        ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
        ->select('hr_designations.description')->first();
        return $data;
    }
    public function getUserdapartment($id){
         $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    } 
    public function Get_hrfullname($id){
         $data= DB::table('hr_employees')
        ->where('hr_employees.user_id',$id)
        ->select('fullname')->first();
        return $data;
    }
    public function getRecordforEdit($id){
         return DB::table('hr_leaves')->select('*')->where('id',$id)->first();
    }
    public function GetDocumentfiles($id){
       return DB::table('files_hr_leaves')->where('hrl_id',$id)->get()->toArray();
    }
    public function GetDocumentfilebyid($id){
       return DB::table('files_hr_leaves')->where('id',$id)->get()->toArray();
    }
    public function deleteimagerowbyid($id){
      return DB::table('files_hr_leaves')->where('id',$id)->delete();
    }
    public function AddDocumentFilesData($postdata){
        DB::table('files_hr_leaves')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateDocumentFilesData($id,$columns){
        return DB::table('files_hr_leaves')->where('id',$id)->update($columns);
    }

    public function useLeaves($operation = 'add'){
        $type = $this->hrlt_id;
        $emp_id = $this->hr_employeesid;
        $leave_adjust = DB::table('hr_leave_adjustments as hla')
                            ->join('hr_leave_earning_adjustment_detail AS hlea', 'hla.id','=','hlea.hrlea_id')
                            ->where('hlea.hrlt_id',$type)
                            ->where('hla.hr_employeesid',$emp_id)
                            ->first();

        $leave_used = $this->dayswithpay;
        // if ($this->hrla_id === 1) {
        //     $start = Carbon::parse($this->hrl_start_date);
        //     $end = Carbon::parse($this->hrl_end_date);
        //     $leave_used = $start->diffInDays($end) + 1;
        // }
        // dd($operation);
        if ($leave_adjust) {
            if ($operation === 'add') {
                $used = $leave_adjust->hrlead_used + $leave_used;
                $balance = $leave_adjust->hrlead_balance - $leave_used;
                DB::table('hr_leave_earning_adjustment_detail AS hlea')->where('id',$leave_adjust->id)->update([
                    'hrlead_used'=>$used,
                    'hrlead_balance'=>$balance,
                ]);
            } else {
                $used = $leave_adjust->hrlead_used - $leave_used;
                $balance = $leave_adjust->hrlead_balance + $leave_used;
                DB::table('hr_leave_earning_adjustment_detail AS hlea')->where('id',$leave_adjust->id)->update([
                    'hrlead_used'=>$used,
                    'hrlead_balance'=>$balance,
                ]);
            }
        }


        return 'done';
    }

    public function getFirstapproverid($slugs,$department){
          $res = UserAccessApprovalApprover::select('user_access_approval_approvers.primary_approvers','user_access_approval_approvers.secondary_approvers','user_access_approval_approvers.tertiary_approvers')
                ->leftJoin('user_access_approval_settings', function($join)
                {
                    $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
                })
                ->leftJoin('menu_sub_modules', function($join)
                {
                    $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
                })
                ->where(['menu_sub_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department ])
                ->first();
              return $res;
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
            if ($sequence == 1) {
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
            }else{
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
                ->count();  
            }
        }
        return $res;
    }
    public function find_levels($slugs, $type)
    {   
        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->where(['menu_modules.slug' => $slugs])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->get();
        } else {
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
            })
            ->where(['menu_sub_modules.slug' => $slugs])
            ->get();
        }

        if ($res->count() > 0) {
            return intval($res->first()->levels);
        } else {
            return 'System Error';
        }
    }

    public function getList($request,$hr_employeesid){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

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

    $sql = DB::table('hr_leaves as hl')->join('hr_employees as he','hl.hr_employeesid','=','he.id')->leftjoin('hr_leavetypes as hlt','hl.hrlt_id','=','hlt.id')
          ->select('he.fullname','hl.hrl_start_date','hl.hrl_end_date','hrlt_leave_type','hl.id as id','days','dayswithpay','hrla_reason','hrla_status','hrla_approved_by','hrla_reviewed_by','hrla_noted_by','applicationno');

    $sql->where('hl.hr_employeesid', '=', $hr_employeesid);
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('hl.id','DESC');
    /*  #######  Get count without limit  ###### */
    /*  #######  Set Offset & Limit  ###### */
    $data_cnt=$sql->count();
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);

   }
   public function getListApprover($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        $userdata = HrLeave::getUserdapartment(Auth::user()->id);
        $departmentid =$userdata->acctg_department_id;
        $approver = HrLeave::getFirstapproverid('hr-leaves',$userdata->acctg_department_id);
        //echo $approver->primary_approvers; exit;

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

        $sql = DB::table('hr_leaves as hl')->join('hr_employees as he','hl.hr_employeesid','=','he.id')->leftjoin('hr_leavetypes as hlt','hl.hrlt_id','=','hlt.id')
                ->select('he.fullname','hl.hrl_start_date','hl.hrl_end_date','hrlt_leave_type','hl.id as id','dayswithpay','hrla_reason','hrla_status','hrla_approved_by','hrla_reviewed_by','hrla_noted_by','applicationno');

        $sql->where('hl.hrla_status', '>', 2);
        if(isset($approver)){  
            if(Auth::user()->id == $approver->primary_approvers && Auth::user()->id != $approver->secondary_approvers){
                // $sql->where('he.acctg_department_id',$departmentid); 
            }
            if(Auth::user()->id == $approver->secondary_approvers || Auth::user()->id == $approver->tertiary_approvers){
                $sql->where('hl.hrla_status', '>=', 2); 
            }
            }
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
                });
            }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('hl.id','DESC');
        /*  #######  Get count without limit  ###### */
        /*  #######  Set Offset & Limit  ###### */
        $data_cnt=$sql->count();
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function checker($group, $value_type, $value, $if_true = true, $if_false = false){
        $selected_type = $this->leave_code; 
        if ($selected_type === $group) {
            if ($value_type) {
                $selected_value = $this[$value_type]; 
                if ($selected_value === $value) {
                    return $if_true;
                }
            } else {
                return $if_true;
            }
        }
        return $if_false;
    }

    
}
