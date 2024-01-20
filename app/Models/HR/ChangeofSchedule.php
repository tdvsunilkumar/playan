<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use Illuminate\Support\Facades\Auth;
use DB;

class ChangeofSchedule extends Model
{
    public $table = 'hr_changeof_schedules';

    public function updateData($id,$columns){
        return DB::table('hr_changeof_schedules')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
       DB::table('hr_changeof_schedules')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_changeof_schedules')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getScheduleDetails($id,$year,$month){
        return DB::table('hr_work_schedules')->select('monthdate_json')->where('hr_employeesid',$id)->where('year',$year)->where('month',$month)->first();
    }
     public function updateWorkData($id,$year,$month,$columns){
        return DB::table('hr_work_schedules')->where('hr_employeesid',$id)->where('year',$year)->where('month',$month)->update($columns);
    }
    public function getUserdapartment($id){
         $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    }
    public function getDefaultschedule(){
         return DB::table('hr_default_schedules')->select('id','hrds_start_time','hrds_end_time')->get();
    }
    public function checkisexistdata($year,$employeeid){
        return DB::table('hr_default_schedules')->select('id')->where('year',$year)->where('hr_employeesid',$employeeid)->get();
    }
    public function fetch_destination($id){
        $data= DB::table('hr_employees')
        ->where('hr_employees.user_id',$id)
        ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
        ->select('hr_designations.description')->first();
        return $data;
    } 
    public function Get_hrfullname($id){
         $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('fullname')->first();
        return $data;
    }
    public function getRecordforEdit($id){
         return DB::table('hr_changeof_schedules')->select('*')->where('id',$id)->first();
    }
    public function GetDocumentfiles($id){
       return DB::table('files_hr_cos')->where('hrcos_id',$id)->get()->toArray();
    }
    public function GetDocumentfilebyid($id){
       return DB::table('files_hr_cos')->where('id',$id)->get()->toArray();
    }
    public function deleteimagerowbyid($id){
      return DB::table('files_hr_cos')->where('id',$id)->delete();
    }
    public function AddDocumentFilesData($postdata){
        DB::table('files_hr_cos')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateDocumentFilesData($id,$columns){
        return DB::table('files_hr_cos')->where('id',$id)->update($columns);
    }
    public function getApplicationNumber(){
        return DB::table('hr_changeof_schedules')->select('id')->whereYear('created_at',date("Y"))->orderby('id','DESC')->first();
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

        $sql = DB::table('hr_changeof_schedules as hcs')->leftjoin('hr_employees as he','hcs.hr_employeesid','=','he.id')->leftjoin('hr_default_schedules as hds','hcs.hrcos_new_schedule','=','hds.id')
              ->select('he.fullname','hcs.hrcos_start_date','hcs.hrcos_end_date','hds.hrds_start_time','hds.hrds_end_time','hcs.id as id','reason','status','hcs.approved_by','hcs.reviewd_by','hcs.noted_by','applicationno');
        if($hr_employeesid > 0) {
         $sql->where('hcs.hr_employeesid', '=', $hr_employeesid);   
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
          $sql->orderBy('hcs.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
    public function getListApprove($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        $userdata = ChangeofSchedule::getUserdapartment(Auth::user()->id);
        $userdata->acctg_department_id;
        $approver = ChangeofSchedule::getFirstapproverid('hr-change-schedule',$userdata->acctg_department_id);
        //echo $approver->primary_approvers; exit;

        $departdata = ChangeofSchedule::getUserdapartment(Auth::user()->id);
        $departmentid = $departdata->acctg_department_id;

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

        $sql = DB::table('hr_changeof_schedules as hcs')->leftjoin('hr_employees as he','hcs.hr_employeesid','=','he.id')->leftjoin('hr_default_schedules as hds','hcs.hrcos_new_schedule','=','hds.id')
              ->select('he.fullname','hcs.hrcos_start_date','hcs.hrcos_end_date','hds.hrds_start_time','hds.hrds_end_time','hcs.id as id','reason','status','hcs.approved_by','hcs.reviewd_by','hcs.noted_by','applicationno');
                $sql->where('hcs.status', '>', 2); 
        // if(isset($approver)){ 
        //      if(Auth::user()->id == $approver->primary_approvers && Auth::user()->id != $approver->secondary_approvers){
        //         // $sql->where('he.acctg_department_id',$departmentid); 
        //         $sql->where('hcs.status', '>=', 2); 
        //      }
        //      if(Auth::user()->id == $approver->secondary_approvers || Auth::user()->id == $approver->tertiary_approvers){
        //         $sql->where('hcs.status', '>=', 2); 
        //      }
        //  }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
            });
        }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('hcs.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    
    public function time_schedule() 
    { 
        return $this->hasOne(HrDefaultSchedule::class, 'id', 'hrcos_new_schedule'); 
    }
}
