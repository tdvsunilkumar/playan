<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use DB;

class HrOverTimeModels extends Model
{
    public $table = 'hr_overtime';

    public function updateData($id,$columns){
        return DB::table('hr_overtime')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_overtime')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_overtime')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getUserdapartment($id){
         $data= DB::table('hr_employees')->where('hr_employees.id',$id)->first();
        return $data;
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
         return DB::table('hr_overtime')->join('hr_employees', 'hr_employees.id','hr_overtime.hr_employeesid')->select('hr_overtime.*','hr_employees.acctg_department_id as department_id')->where('hr_overtime.id',$id)->first();
    }
	public function GetDocumentfiles($id){
       return DB::table('files_hr_official_work')->where('hrow_id',$id)->get()->toArray();
    }
    public function GetDocumentfilebyid($id){
       return DB::table('files_hr_official_work')->where('id',$id)->get()->toArray();
    }
    public function deleteimagerowbyid($id){
      return DB::table('files_hr_official_work')->where('id',$id)->delete();
    }
    public function AddDocumentFilesData($postdata){
        DB::table('files_hr_official_work')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateDocumentFilesData($id,$columns){
        return DB::table('files_hr_official_work')->where('id',$id)->update($columns);
    }
    public function getApplicationNumber(){
        return DB::table('hr_overtime')->select('id')->whereYear('created_at',date("Y"))->orderby('id','DESC')->first();
    }

    public function addOffsetHoursData($postdata){
        return DB::table('hr_offset_hours')->insert($postdata);
    }

    public function chkBalanceHour($id){
       return DB::table('hr_offset_hours')->select('hroh_balance_offset_hours','hroh_total_offset_hours')->where('hr_employeesid',$id)->get()->toArray();
    }

    public function updateOffserHourData($id,$columns){
        return DB::table('hr_offset_hours')->where('hr_employeesid',$id)->update($columns);
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
      1 =>"he.fullname",
      2 =>"hrot_work_date",
      4 =>"hro_status",
    );

    $sql = DB::table('hr_overtime as hot')
		  ->leftjoin('hr_employees as he','hot.hr_employeesid','=','he.id')
          ->select('he.fullname','hrot_application_no','hrot_work_date','hot.hrwc_id','hot.hrot_start_time','hot.hrot_end_time','hot.id as id','hro_reason','hro_status','hot.hro_approved_by','hot.hro_reviewed_by','hot.hro_noted_by','hot.hro_disapproved_by','hrot_application_no as applicationno');


			if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%");
                });
		    }
            if ($request->input('type') === 'approval') {
                $sql->where('hro_status','>',2);
            } else {
                $hr_emp_id=\Auth::user()->hr_employee->id;
                $sql->where('hr_employeesid',$hr_emp_id);
            }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('hot.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
