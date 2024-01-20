<?php

namespace App\Models\HR;
use App\Models\HrEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use DB;

class HrMissedLog extends Model
{
    public $table = 'hr_missed_log';
    public function updateData($id,$columns){
        return DB::table('hr_missed_log')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_missed_log')->insert($postdata);
        $hr_missed_log=DB::table('hr_missed_log')->orderBy('id','DESC')->first();
        return $hr_missed_log->id;
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_missed_log')->where('id',$id)->update($columns);
    } 
    public function find($id){
        return DB::table('hr_missed_log')->where('id',$id)->first();
      } 
      public function getRecordforEdit($id){
        return DB::table('hr_missed_log')->join('hr_employees', 'hr_employees.id','hr_missed_log.hr_emp_id')->select('hr_missed_log.*','hr_employees.acctg_department_id as department_id')->where('hr_missed_log.id',$id)->first();
   }
    public function GetDocumentfiles($id){
        return DB::table('files_hr_missed_log')->where('hml_id',$id)->get()->toArray();
     }  
     public function GetDocumentfilebyid($id){
        return DB::table('files_hr_missed_log')->where('id',$id)->get()->toArray();
     }
     public function deleteimagerowbyid($id){
       return DB::table('files_hr_missed_log')->where('id',$id)->delete();
     }
    public function findLatest(){
        return DB::table('hr_missed_log')->orderBy('id','DESC')->first();
      } 
    public function getLogType(){
        return DB::table('hr_log')->orderBy('id')->get();
      }  
    public function AddDocumentFilesData($postdata){
        DB::table('files_hr_missed_log')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdateDocumentFilesData($id,$columns){
        return DB::table('files_hr_missed_log')->where('id',$id)->update($columns);
    } 
    public function getUserdapartment($id){
          $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    } 
    public function fetch_destination($id){
      $data= DB::table('hr_employees')
      ->where('hr_employees.user_id',$id)
      ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
      ->select('hr_designations.description')->first();
      return $data;
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
        $hr_emp_id=HrEmployee::hrEmpIdByUserId(\Auth::user()->id);

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          1 =>"he.fullname",
          2 =>"hml.created_at",
          3 =>"hml.hml_work_date",
          4 =>"hml.hml_actual_time",
          5 =>"hl.hrlog_description",	
          7 =>"hml.hml_reason",	
          8 =>"he_apv.fullname",	
          9 =>"he_rev.fullname",	
          10 =>"he_not.fullname",	
        );

        $sql = DB::table('hr_missed_log AS hml')
              ->join('hr_employees AS he', 'he.id', '=', 'hml.hr_emp_id')
              ->join('hr_log AS hl', 'hl.id', '=', 'hml.hrlog_id')
              ->leftjoin('hr_employees AS he_apv', 'he_apv.user_id', '=', 'hml.hml_approved_by')
              ->leftjoin('hr_employees AS he_rev', 'he_rev.user_id', '=', 'hml.hml_reviewed_by')
              ->leftjoin('hr_employees AS he_not', 'he_not.user_id', '=', 'hml.hml_noted_by')
              ->select('hml.*','he.fullname as emp_name','hl.hrlog_description','he_apv.fullname as apv_by','he_rev.fullname as review_by','he_not.fullname as noted_by','hml_application_no as applicationno');
        // dd($request->input('apv'));
        if($request->input('apv')==1){
          $sql->where('hml_status',"!=",0);
        }
        else{
          $sql->where('hr_emp_id',$hr_emp_id);
        }
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
          $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hl.hrlog_description)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(he_apv.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(he_rev.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(he_not.fullname)'),'like',"%".strtolower($q)."%");
          });
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
    public function getListApv($request)
      {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $hr_emp_id=HrEmployee::hrEmpIdByUserId(\Auth::user()->id);
        $approver = $this->getFirstapproverid('hr-missed-logs',$this->getUserdapartment(\Auth::user()->id)->acctg_department_id);
        $departmentid = $this->getUserdapartment(\Auth::user()->id)->acctg_department_id;
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          1 =>"he.fullname",
          2 =>"hml.created_at",
          3 =>"hml.hml_work_date",
          4 =>"hml.hml_actual_time",
          5 =>"hl.hrlog_description",	
          7 =>"hml.hml_reason",	
          8 =>"he_apv.fullname",	
          9 =>"he_rev.fullname",	
          10 =>"he_not.fullname",	
        );
        $departmentid = $this->getUserdapartment(\Auth::user()->id)->acctg_department_id;
        $sql = DB::table('hr_missed_log AS hml')
        ->join('hr_employees AS he', 'he.id', '=', 'hml.hr_emp_id')
        ->join('hr_log AS hl', 'hl.id', '=', 'hml.hrlog_id')
        ->leftjoin('hr_employees AS he_apv', 'he_apv.user_id', '=', 'hml.hml_approved_by')
        ->leftjoin('hr_employees AS he_rev', 'he_rev.user_id', '=', 'hml.hml_reviewed_by')
        ->leftjoin('hr_employees AS he_not', 'he_not.user_id', '=', 'hml.hml_noted_by')
        // ->leftjoin('user_access_approval_approvers AS uaaa', 'uaaa.department_id', '=', 'he.acctg_department_id')
        ->where('hml.hml_status', '>', 2)
        // ->where(function ($query) use ($userID, $userDepartmentID) {
        //     $query->where(function ($query) use ($userID, $userDepartmentID) {
        //         $query->where('uaaa.primary_approvers', $userID)
        //             ->where('uaaa.department_id', $userDepartmentID);
        //     })
        //     ->orWhere(function ($query) use ($userID) {
        //         $query->whereIn('uaaa.secondary_approvers', [$userID, $userID, $userID])
        //             ->where('hml.hml_status', '!=', 1);
        //     });
        // })
        ->select('hml.*', 'he.fullname as emp_name', 'hl.hrlog_description', 'he_apv.fullname as apv_by', 'he_rev.fullname as review_by', 'he_not.fullname as noted_by','hml_application_no as applicationno');
        // if(Auth::user()->id == $approver->primary_approvers){
        //     // $sql->where('he.acctg_department_id',$departmentid); 
        // }
        // else{
        //     $sql->where('hml.hml_status', '>=', 3);
        // }
            // $sql->where('hml.hml_status', '>=', 2);
            //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
          $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hl.hrlog_description)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(he_apv.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(he_rev.fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(he_not.fullname)'),'like',"%".strtolower($q)."%");
          });
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
    public function disapprove($request)
      {
        return HrTimecard::disapproveTimecard(
          $request
        );
      }
}
