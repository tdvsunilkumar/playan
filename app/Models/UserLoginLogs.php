<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserLoginLogs extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('audit_logs')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('audit_logs')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('audit_logs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('audit_logs')->where('id',$id)->first();
    }
	
	 public function getDepartments(){
        return DB::table('acctg_departments')->select('id','name')->orderBy('name','ASC')->get();
    }
	
    public function getList($request, $role){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
		$from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
		$department=$request->input('department');
		$log_type=$request->input('log_type');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"id",
          1 =>"full_name",
		  2 =>"email_address",
          3 =>"dept_name",
		  4 =>"logs",
		  5 =>"log_type"
           
        );

        $sql = DB::table('audit_logs As al')
            ->leftJoin('users_role', function($join)
            {
                $join->on('users_role.user_id', '=', 'al.user_id');
            })
            ->leftJoin('role', function($join)
            {
                $join->on('role.id', '=', 'users_role.role_id');
            })
              ->select('al.*',);
		if(!empty($from_date) && isset($from_date)){
             $sql->whereDate('al.created_at','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
             $sql->whereDate('al.created_at','<=',$to_date);
        }
		if(!empty($department) && isset($department)){
            $sql->where('al.dept_id','=',$department);
        }
		    if(!empty($log_type) && isset($log_type)){
            $sql->where('al.log_type','=',$log_type);
        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q){
                $sql->where(DB::raw('LOWER(al.full_name)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(al.dept_name)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(al.logs)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw('LOWER(al.email_address)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        if ($role <> 1) {
            $sql = $sql->where('role.id', '!=', 1);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('al.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
