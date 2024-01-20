<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use Illuminate\Support\Facades\Auth;
use DB;

class HrOfficialWork extends Model
{
     public function updateData($id,$columns){
        return DB::table('hr_official_works')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_official_works')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_official_works')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getUserdapartment($id){
         $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    }
    public function getWorkType(){
        return DB::table('hr_work_type')->where('is_active',1)->orderBy('hrwt_description')->get();
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
         return DB::table('hr_official_works')->select('*')->where('id',$id)->first();
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
        return DB::table('hr_official_works')->select('id')->whereYear('created_at',date("Y"))->orderby('id','DESC')->first();
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
      2 =>"applicationno",
      3 =>"hrow_work_date",
      4 =>"how.hrow_time_in",
      5 =>"how.hrow_time_out",
      6 =>"hwt.hrwt_description",
      7 =>"hrow_reason",
      8 =>"hrow_status"
    );

    $sql = DB::table('hr_official_works as how')
          ->leftjoin('hr_employees as he','how.hr_employeesid','=','he.id')
          ->leftjoin('hr_work_type as hwt','how.hrwt_id','=','hwt.id')
          ->select('he.fullname','applicationno','hrow_work_date','hwt.hrwt_description','how.hrwt_id','how.hrow_time_in','how.hrow_time_out','how.id as id','hrow_reason','hrow_status','how.hrow_approved_by','how.hrow_reviewed_by','how.hrow_noted_by','applicationno');

    $sql->where('how.hr_employeesid', '=', $hr_employeesid);
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('how.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

    public function getListApproval($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
     
    $userdata = HrOfficialWork::getUserdapartment(Auth::user()->id);
    $departmentid =$userdata->acctg_department_id;
    $approver = HrOfficialWork::getFirstapproverid('hr-official-work',$userdata->acctg_department_id);

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
        0 =>"id",
        1 =>"he.fullname",
        2 =>"applicationno",
        3 =>"hrow_work_date",
        4 =>"how.hrow_time_in",
        5 =>"how.hrow_time_out",
        6 =>"hwt.hrwt_description",
        7 =>"hrow_reason",
        8 =>"hrow_status"
    );

    $sql = DB::table('hr_official_works as how')
           ->leftjoin('hr_employees as he','how.hr_employeesid','=','he.id')
           ->leftjoin('hr_work_type as hwt','how.hrwt_id','=','hwt.id')
           ->select('he.fullname','applicationno','hrow_work_date','hwt.hrwt_description','how.hrwt_id','how.hrow_time_in','how.hrow_time_out','how.id as id','hrow_reason','hrow_status','how.hrow_approved_by','how.hrow_reviewed_by','how.hrow_noted_by','applicationno');

    $sql->where('how.hrow_status','>', 2);
    if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
        });
    }
      if(isset($approver)){  
          if(Auth::user()->id == $approver->primary_approvers && Auth::user()->id != $approver->secondary_approvers){
                // $sql->where('he.acctg_department_id',$departmentid); 
          }
          if(Auth::user()->id == $approver->secondary_approvers || Auth::user()->id == $approver->tertiary_approvers){
                $sql->where('how.hrow_status', '>=', 2); 
          }
      }   
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('how.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function disapprove($request)
      {
        return HrTimecard::disapproveTimecard(
          $request
        );
      }
}