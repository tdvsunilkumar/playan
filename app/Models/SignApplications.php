<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SignApplications extends Model
{
    public function updateData($id,$columns){
        return DB::table('sign_applications')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('sign_applications')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('sign_applications')->where('sign_applications.id',$id)
               ->leftjoin('menu_sub_modules','menu_sub_modules.id','=','sign_applications.menu_sub_id') 
               ->select('sign_applications.*','menu_sub_modules.name AS menu_sub_modules_name')
               ->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('sign_applications')->where('id',$id)->update($columns);
    } 
    public function getIpSettingStatus(){
        return DB::table('settings')->where('name',"sign_settings")->first();
    }
    public function checkIpReg($user_ip){
        return DB::table('sign_applications')->where('ip_address',$user_ip)->where('status',1)->first();
    }
    public function check_is_super_admin($email){
        return DB::table('users')
               ->leftjoin('users_role','users_role.user_id','=','users.id') 
               ->where('users.email',$email)
               ->where('users_role.role_id',1)->first();
    }
    public function allModuleMenus()
    {
        return (new MenuModule)->allModuleMenus();
    }
    public function get_sub_module($menu_module_id)
    {
        return (new MenuSubModule)->reload_sub_module($menu_module_id);
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
    
    public function allVeriable()
    {
        return DB::table('sign_variables')->orderBy('var_name')->get();
    }
    public function allSections()
    {
        return DB::table('sign_section')->orderBy('section_name')->get();
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
        $menu_description=$request->input('menu_description');

        if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
        }

        $columns = array( 
        0 => "id",
        2 => "menu_sub_modules.name",
        3 => "sign_applications.section_name",
        4 => "sign_applications.var_name",
        6 => "hr_employees.fullname",
        7 => "sign_applications.created_at",
        8 => "sign_applications.status",
        );

        $sql = DB::table('sign_applications')
                ->leftjoin('menu_modules','menu_modules.id','=','sign_applications.menu_module_id') 
                ->leftjoin('menu_groups','menu_groups.id','=','sign_applications.menu_group_id') 
                ->leftjoin('menu_sub_modules','menu_sub_modules.id','=','sign_applications.menu_sub_id') 
                ->leftjoin('sign_variables','sign_variables.id','=','sign_applications.var_id') 
                ->leftjoin('hr_employees','hr_employees.user_id','=','sign_applications.created_by') 
                ->select('sign_applications.*','sign_variables.var_name','menu_sub_modules.name AS menu_sub_modules_name','menu_modules.name AS menu_modules_name','menu_groups.name AS menu_groups_name','hr_employees.fullname');
        if($menu_description != null){
            $sql->where('sign_applications.menu_module_id',$menu_description);
        }
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(sign_applications.var_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(menu_sub_modules.name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(sign_applications.section_name)'),'like',"%".strtolower($q)."%")
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
