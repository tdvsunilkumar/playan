<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Auth;

class LeaveAdjustment extends Model
{
    public $table = 'hr_leave_adjustments';

    public function updateData($id,$columns){
        return DB::table('hr_leave_adjustments')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('hr_leave_adjustments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateadjustdetailData($id,$columns){
        return DB::table('hr_leave_adjustment_detail')->where('id',$id)->update($columns);
    }
    public function adddjustdetailData($postdata){
         DB::table('hr_leave_adjustment_detail')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkdetailexist($id,$typeid){
    		return DB::table('hr_leave_adjustment_detail')->select('id')->where('hrlt_id',$typeid)->where('hrlad_approved_by',0)->where('hrlead_id',$id)->first();
    }
    public function updateearnadjustdetailData($id,$columns){
        return DB::table('hr_leave_earning_adjustment_detail')->where('id',$id)->update($columns);
    }
    public function adddearnjustdetailData($postdata){
         DB::table('hr_leave_earning_adjustment_detail')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkdetailexistearn($id,$typeid){
    		return DB::table('hr_leave_earning_adjustment_detail')->select('id')->where('hrlt_id',$typeid)->where('hrlea_id',$id)->get();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_leave_adjustments')->where('id',$id)->update($columns);
    } 
    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
    public function getLeaveParameters(){
    	return DB::table('hr_leave_parameter')->select('id','hrlp_description')->get();
    }
    public function getLeaveType(){
    	return DB::table('hr_leavetypes')->select('id','hrlt_leave_type','hrlt_leave_code')->get();
    }
    public function getParameterdetails($id){
    	return DB::table('hr_leave_parameter_detail')->select('id','hrlt_id','hrlpc_days')->where('hrlp_id',$id)->where('hrlpc_is_active',1)->get();
    }
    public function getRecordforEdit($id){
    	return DB::table('hr_leave_adjustments')->where('id',$id)->first();
    }

    public function getUserrole($id){
           return DB::table('users_role as ur')->leftjoin('role AS r','r.id','=','ur.role_id')->select('r.id','r.name')->where('user_id',$id)->get();
    }
    
    public function getAdjustmentDetails($id){
    		return DB::table('hr_leave_earning_adjustment_detail AS hlea')
        ->leftjoin(DB::raw('(select * from hr_leave_adjustment_detail where hrlad_approved_by = 0 AND hrlad_status = 1) as hlad'), 'hlea.id', '=', 'hlad.hrlead_id') 
        ->select('hlad.hrlad_adjustment','hlea.hrlt_id','hlea.hrlpc_days','hrlead_used','hrlead_balance','hlea.id','hlad.id as hlad_id','hrlad_status')->where('hlea.hrlea_id','=',$id)->orderby('hlea.id','ASC')->get();
    }

    public function getAccuralBy($id,$type){
      return DB::table('hr_leave_earning_adjustment_detail AS hlea')
        ->leftjoin('hr_leave_adjustments as hrla', 'hrla.id', '=', 'hlea.hrlea_id') 
        ->leftjoin('hr_leave_parameter_detail as hrlpd', function($join)
                    {
                        $join->on('hrlpd.hrlp_id', '=', 'hrla.hrlp_id');
                        $join->on('hrlpd.hrlt_id', '=', 'hlea.hrlt_id');
                    }) 
        ->select('hlea.hrlt_id','hlea.hrlpc_days','hrlead_used','hrlead_balance','hlea.id as hlea_id','hrlea_id as hrla_id','hrat_id','hrlpc_credits','hrla.hrlp_id as hrla_id')
        ->where([
          ['hlea.hrlea_id',$id],
          ['hrat_id',$type]
          ])
        ->orderby('hlea.id','ASC')
        ->get();
    }

    public function getRemaining($leave_type,$employee_id){
      $now = Carbon::now();
      $get_leave = DB::table('hr_leave_earning_adjustment_detail AS hlead')
      ->leftjoin('hr_leave_adjustments AS hla', 'hlead.hrlea_id', '=', 'hla.id')
      ->where('hla.hr_employeesid',$employee_id)
      ->where('hlead.hrlt_id',$leave_type)
      ->where('hla.hrlea_date_effective','<=',$now->toDateString())
      ->first();
      return ($get_leave) ? $get_leave->hrlead_balance : 0;
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
        2 =>"hrds_date",
        4 =>"is_active",
      );

      $sql = DB::table('hr_leave_adjustments as hla')->join('hr_employees as he','hla.hr_employeesid','=','he.id')
            ->select('he.fullname','hla.*');

      //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
      if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
          $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
        });
      }
      /*  #######  Set Order By  ###### */
      if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      else
        $sql->orderBy('hla.id','ASC');
      /*  #######  Get count without limit  ###### */
      /*  #######  Set Offset & Limit  ###### */
      $data_cnt=$sql->count();
      $sql->offset((int)$params['start'])->limit((int)$params['length']);
      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
   }

   public function getLeaveList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      // 0 =>"hrlad.id",
      0 =>"hrlt.hrlt_leave_type",
      1 =>"hrlad.hrlpc_days",
      2 =>"hrlad.hrlead_used",
      3 =>"hrlad.hrlead_balance",
    );

    $sql = DB::table('hr_leave_earning_adjustment_detail as hrlad')
            ->join('hr_leave_adjustments as hrla','hrlad.hrlea_id','=','hrla.id')
            ->join('hr_leavetypes as hrlt','hrlad.hrlt_id','=','hrlt.id')
          ->where('hrla.hr_employeesid',Auth::user()->hr_employee->id)
          ->select('hrlt.hrlt_leave_type as leave_type','hrlad.*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
    if(!empty($q) && isset($q)){
      $sql->where(function ($sql) use($q) {
        $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%");
      });
    }
    /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('hrlad.id','ASC');
    /*  #######  Get count without limit  ###### */
    /*  #######  Set Offset & Limit  ###### */
    $data_cnt=$sql->count();
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
   
}
