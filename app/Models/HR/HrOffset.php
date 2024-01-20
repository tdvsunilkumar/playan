<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use Illuminate\Support\Facades\Auth;
use DB;

class HrOffset extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_offsets')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_offsets')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_offsets')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getLeavetypes(){
         return DB::table('hr_leavetypes')->select('id','hrlt_leave_type')->get();
    }
    public function getApplicationtypes(){
         return DB::table('hr_leave_applications')->where('is_active',1)->select('id','hrla_description')->get();
    }
    public function checkisexistdata($year,$employeeid){
        return DB::table('hr_default_schedules')->select('id')->where('year',$year)->where('hr_employeesid',$employeeid)->get();
    }
    public function getApplicationNumber(){
        return DB::table('hr_offsets')->select('id')->whereYear('created_at',date("Y"))->orderby('id','DESC')->first();
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
    public function chkBalanceHour($id){
       return DB::table('hr_offset_hours')->select('hroh_balance_offset_hours','hroh_used_offset_hours','id')->where('hr_employeesid',$id)->get()->toArray();
    }

    public function updateOffserHourData($id,$columns){
        return DB::table('hr_offset_hours')->where('hr_employeesid',$id)->update($columns);
    }

    public function Get_hrfullname($id){
         $data= DB::table('hr_employees')
        ->where('hr_employees.user_id',$id)
        ->select('fullname')->first();
        return $data;
    }
    public function getRecordforEdit($id){
         return DB::table('hr_offsets')->select('*')->where('id',$id)->first();
    }
    public function GetDocumentfiles($id){
       return DB::table('files_hr_offset')->where('hro_id',$id)->get()->toArray();
    }
    public function GetDocumentfilebyid($id){
       return DB::table('files_hr_offset')->where('id',$id)->get()->toArray();
    }
    public function deleteimagerowbyid($id){
      return DB::table('files_hr_offset')->where('id',$id)->delete();
    }
    public function AddDocumentFilesData($postdata){
        DB::table('files_hr_offset')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateDocumentFilesData($id,$columns){
        return DB::table('files_hr_offset')->where('id',$id)->update($columns);
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
            if ($sequence == 1){
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

    $sql = DB::table('hr_offsets as ho')->join('hr_employees as he','ho.hr_employeesid','=','he.id')->leftjoin('hr_leave_applications as hla','ho.hro_id','=','hla.id')
          ->select('he.fullname','hrla_description','ho.hro_work_date','ho.id as id','hro_reason','hro_status','hro_approved_by','hro_reviewed_by','hro_noted_by','applicationno');

    $sql->where('ho.hr_employeesid', '=', $hr_employeesid);
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('ho.id','DESC');
    /*  #######  Get count without limit  ###### */
    /*  #######  Set Offset & Limit  ###### */
    $data_cnt=$sql->count();
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);

   }
   public function getListApproval($request){
      $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $userdata = HrOffset::getUserdapartment(Auth::user()->id);
    $departmentid =$userdata->acctg_department_id;
    $approver = HrOffset::getFirstapproverid('hr-leaves',$userdata->acctg_department_id);
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

    $sql = DB::table('hr_offsets as ho')->join('hr_employees as he','ho.hr_employeesid','=','he.id')->leftjoin('hr_leave_applications as hla','ho.hro_id','=','hla.id')
          ->select('he.fullname','hrla_description','ho.hro_work_date','ho.id as id','hro_reason','hro_status','hro_approved_by','hro_reviewed_by','hro_noted_by','applicationno');
          $sql->where('ho.hro_status', '>', 2); 

    // if(isset($approver)){  
    //  if(Auth::user()->id == $approver->primary_approvers && Auth::user()->id != $approver->secondary_approvers){
    //         // $sql->where('he.acctg_department_id',$departmentid); 
    //   }
    // //   if(Auth::user()->id == $approver->secondary_approvers || Auth::user()->id == $approver->tertiary_approvers){
    //         $sql->where('ho.hro_status', '>', 2); 
    // //   }
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
      $sql->orderBy('ho.id','DESC');
    /*  #######  Get count without limit  ###### */
    /*  #######  Set Offset & Limit  ###### */
    $data_cnt=$sql->count();
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
   }
}
