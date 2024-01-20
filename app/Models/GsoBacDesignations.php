<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class GsoBacDesignations extends Model
{
    public function updateData($id,$columns){
        return DB::table('gso_bac_designations')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('gso_bac_designations')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('gso_bac_designations')->where('id',$id)
               ->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('gso_bac_designations')->where('id',$id)->update($columns);
    } 
    public function getIpSettingStatus(){
        return DB::table('settings')->where('name',"sign_settings")->first();
    }
    public function checkIpReg($user_ip){
        return DB::table('gso_bac_designations')->where('ip_address',$user_ip)->where('status',1)->first();
    }
    public function check_is_super_admin($email){
        return DB::table('users')
               ->leftjoin('users_role','users_role.user_id','=','users.id') 
               ->where('users.email',$email)
               ->where('users_role.role_id',1)->first();
    }
    public function allHrEmployee()
    {
        return (new HrEmployee)->allEmployees();
    }
    public function get_emp_dept($emp_id)
    {
        $hr_employee= (new HrEmployee)->empDataById($emp_id);
        return $hr_employee->department->name." => ". $hr_employee->division->name;
    }
    public function sub_module($menu_module_id)
    {
        return DB::table('menu_sub_modules')->where('menu_module_id',$menu_module_id)->get();
    }
    
    public function getMenuGroupId($menu_module_id)
    {
        $menuModule = MenuModule::where('id', $menu_module_id)->first();
        return $menuModule->menu_group_id;
    }
    public function getVariableName($var_id)
    {
        $sign_variable = DB::table('sign_variables')->where('id', $var_id)->first();
        return $sign_variable->var_name;
    }
    public function getSectionName($section_id)
    {
        $sign_section = DB::table('sign_section')->where('id', $section_id)->first();
        return $sign_section->section_name;
    }
    
    
    
    public function updateSigningSettings($request){
        $ip_settings= DB::table('settings')->where('name',"sign_settings")->first();
        if($ip_settings){
            DB::table('settings')->where('name','sign_settings')->update(['value'=>$request->radioVal,
            'updated_at'=>date('Y-m-d H:i:s')]);
        }else{
            $data = array(
                        'name' => "sign_settings",
                        'value'=> $request->radioVal,
                        'created_by'=>\Auth::user()->id,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ); 
            DB::table('settings')->insert($data);
        }
        return true;
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
        0 => "gso_bac_designations.id",
        1 => "hr_employees.fullname",
        2 => "acctg_departments.name",
        3 => "gso_bac_designations.app_id",
        4 => "gso_bac_designations.position",
        5 => "gso_bac_designations.updated_at",
        6 => "gso_bac_designations.is_active",
        );

        $sql = DB::table('gso_bac_designations')
                ->leftjoin('hr_employees','hr_employees.id','=','gso_bac_designations.employee_id') 
                ->leftjoin('acctg_departments','acctg_departments.id','=','hr_employees.acctg_department_id') 
                ->leftjoin('acctg_departments_divisions','acctg_departments_divisions.id','=','hr_employees.acctg_department_division_id') 
                ->select('gso_bac_designations.*','hr_employees.fullname','acctg_departments.name AS department_name','acctg_departments_divisions.name AS div_name');

            if(!empty($q) && isset($q)){
                $que = null;
                if($q == "Abstract Of Canvass"){
                    $que = 1; 
                }if($q == "Resolution"){
                    $que = 1; 
                }
                $sql->where(function ($sql) use($q,$que) {
                    if(isset($que))
                    {
                        $sql->where(DB::raw('gso_bac_designations.app_id'),$que); 
                    }
                    else{
                        $sql->where(DB::raw('LOWER(gso_bac_designations.position)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(acctg_departments.name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($q)."%");
                    }
                });
            }    
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
        $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}

