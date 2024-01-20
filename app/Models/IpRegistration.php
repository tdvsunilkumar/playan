<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class IpRegistration extends Model
{
    public function updateData($id,$columns){
        return DB::table('ip_registration')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('ip_registration')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('ip_registration')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ip_registration')->where('id',$id)->update($columns);
    } 
    public function getIpSettingStatus(){
        return DB::table('settings')->where('name',"ip_settings")->first();
    }
    public function checkIpReg($user_ip){
        return DB::table('ip_registration')->where('ip_address',$user_ip)->where('status',1)->first();
    }
    public function check_is_super_admin($email){
        return DB::table('users')
               ->leftjoin('users_role','users_role.user_id','=','users.id') 
               ->where('users.email',$email)
               ->where('users_role.role_id',1)->first();
    }
    public function updateIpSettings($request){
        $ip_settings= DB::table('settings')->where('name',"ip_settings")->first();
        if($ip_settings){
            DB::table('settings')->where('name','ip_settings')->update(['value'=>$request->isActive,
            'updated_at'=>date('Y-m-d H:i:s')]);
        }else{
            $data = array(
                        'name' => "ip_settings",
                        'value'=> $request->isActive,
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
        0 =>"id",
        1 =>"ip_address",
        2 =>"local_name",
        4 => "hr_employees.fullname",
        5 => "created_at",
        6 => "updated_at",
        7 => "status",
        );

        $sql = DB::table('ip_registration')
                ->leftjoin('hr_employees','hr_employees.user_id','=','ip_registration.created_by') 
                ->select('ip_registration.*','hr_employees.fullname');

        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(ip_address)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(local_name)'),'like',"%".strtolower($q)."%");
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
