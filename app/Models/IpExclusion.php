<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class IpExclusion extends Model
{
    public function updateData($id,$columns){
        return DB::table('ip_exclusion')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ip_exclusion')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('ip_exclusion')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ip_exclusion')->where('id',$id)->update($columns);
    } 
    public function getHrEmployee(){
        return DB::table('users')->where('is_active',1)->orderBy('name')->get();
    }
    public function checkUserExclusion($user_email){
        return DB::table('ip_exclusion')
                   ->leftjoin('users','users.id','=','ip_exclusion.employee_id') 
                   ->where('users.email',$user_email)
                   ->first();
    }
    public function getEmpDetails($id){
        return DB::table('users')
                ->leftjoin('hr_employees','hr_employees.user_id','=','users.id') 
                ->leftjoin('acctg_departments','acctg_departments.id','=','hr_employees.acctg_department_id') 
                ->leftjoin('hr_designations','hr_designations.id','=','hr_employees.hr_designation_id') 
                ->select('users.email as emp_email','acctg_departments.name as emp_dept_name','hr_designations.description as emp_position')
                ->where('users.id',$id)->first();
      }   
    public function deleteById($id){
        return DB::table('ip_exclusion')->delete($id);
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
        1 =>"users.name",
        2 =>"users.email",
        3 => "hr_designations.description",
        5 => "acctg_departments.name",
        6 => "hr_employees.fullname",
        7 => "updated_at",
        8 => "status",
        );

        $sql = DB::table('ip_exclusion')
                ->leftjoin('hr_employees','hr_employees.user_id','=','ip_exclusion.created_by') 
                ->leftjoin('users','users.id','=','ip_exclusion.employee_id') 
                ->leftjoin('hr_employees as Emp','Emp.user_id','=','users.id') 
                ->leftjoin('acctg_departments','acctg_departments.id','=','Emp.acctg_department_id') 
                ->leftjoin('hr_designations','hr_designations.id','=','Emp.hr_designation_id') 
                ->select('ip_exclusion.*','hr_employees.fullname','users.name as emp_name','users.email as emp_email','acctg_departments.name as emp_dept_name','hr_designations.description as emp_position');

        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(users.name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(users.email)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hr_designations.description)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(acctg_departments.name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%");
                });
            }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
        $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
        }
    }
