<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\UserRoleGroup;
use App\Models\UserRoleModule;
use App\Models\UserRoleSubModule;
use App\Models\MenuPermission;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\SmsMessage;
use App\Models\SmsOutbox;
use App\Models\SmsPrefix;
use App\Models\SmsSetting;
use App\Models\SmsServerSetting;
use App\Models\CronJobs;
use Jenssegers\Agent\Agent;
use DB;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function is_permitted($slugs, $permission, $view = 0, $type = 0)
    {   
        $privileges = explode(',', strtolower($this->load_privileges($slugs)));

        if (!in_array($permission, $privileges)) {
            if ($view > 0) {
                return false; 
            } else { 
                if ($type == 0) {
                    return abort(401);
                }
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        return true;
    }

    public function is_dash_permitted($slugs)
    {   
        $dashboard_group_menus = DB::table('dashboard_group_menus')->where('slug',$slugs)->where('is_active',1)->first();

        if (!empty($dashboard_group_menus)) {
            $dashboard_user_menu_permissions = DB::table('dashboard_user_menu_permissions')->whereRaw("FIND_IN_SET(?, menu_permissions)", [$dashboard_group_menus->id])->where('menu_group_id',$dashboard_group_menus->menu_group_id)->where('user_id',\Auth::user()->id)->where('is_active',1)->count();
            if ($dashboard_user_menu_permissions > 0) {
                return true; 
            } else { 
                return false;
            }
        }
        return false;
    }

    public function load_privileges($slugs)
    {
        $url = trim($_SERVER['REQUEST_URI']);
        $url = str_replace('/dev/','/',$url);
        $url = str_replace('/uat/','/',$url);
        $subUrl = trim($url,'/');
        $privileges = '';  
        if (Auth::check()) {
            $res = (new UserRole)->where('user_id', Auth::user()->id)->first();
            $permission = MenuPermission::with(['group', 'module', 'sub_module'])->where(['slug' => $subUrl, 'is_active' => 1])->first();
            if(!isset($permission)){
                $permission = MenuPermission::with(['group', 'module', 'sub_module'])->where(['slug' => $slugs, 'is_active' => 1])->first();
            }
           
            if ($permission->module_id !== null && $permission->sub_module_id !== null) {
                $privileges = UserRoleSubModule::select('users_role_sub_modules.permissions')
                ->leftJoin('menu_sub_modules', function($join)
                {
                    $join->on('menu_sub_modules.id', '=', 'users_role_sub_modules.menu_sub_module_id');
                })
                ->leftJoin('menu_modules', function($join)
                {
                    $join->on('menu_modules.id', '=', 'menu_sub_modules.menu_module_id');
                })
                ->leftJoin('menu_groups', function($join)
                {
                    $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
                })
                ->where([
                    'menu_groups.id' => $permission->group_id,
                    'menu_modules.id' => $permission->module_id,
                    'menu_sub_modules.id' => $permission->sub_module_id,
                    'users_role_sub_modules.role_id' => $res->role_id,
                    'users_role_sub_modules.user_id' => $res->user_id,
                    'users_role_sub_modules.is_active' => 1
                ])
                ->get();
                if ($privileges->count() > 0) {
                    $privileges = $privileges->first()->permissions;
                }    
            } else if ($permission->module_id !== null) {
                $privileges = UserRoleModule::select('users_role_modules.permissions')
                ->leftJoin('menu_modules', function($join)
                {
                    $join->on('menu_modules.id', '=', 'users_role_modules.menu_module_id');
                })
                ->leftJoin('menu_groups', function($join)
                {
                    $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
                })
                ->where([
                    'menu_groups.id' => $permission->group_id,
                    'menu_modules.id' => $permission->module_id,
                    'users_role_modules.role_id' => $res->role_id,
                    'users_role_modules.user_id' => $res->user_id,
                    'users_role_modules.is_active' => 1
                ])
                ->get();

                if ($privileges->count() > 0) {
                    $privileges = $privileges->first()->permissions;
                }
            } else {
                $privileges = UserRoleGroup::select('users_role_groups.permissions')
                ->leftJoin('menu_groups', function($join)
                {
                    $join->on('menu_groups.id', '=', 'users_role_groups.menu_group_id');
                })
                ->where([
                    'menu_groups.id' => $permission->group_id,
                    'users_role_groups.role_id' => $res->role_id,
                    'users_role_groups.user_id' => $res->user_id,
                    'users_role_groups.is_active' => 1
                ])
                ->get();

                if ($privileges->count() > 0) {
                    $privileges = $privileges->first()->permissions;
                }    
            }
        }
        return $privileges;
    }

    public function insertLogs($details)
    {   
        $agent = new Agent();
        $device = $agent->isDesktop() ? ucwords('Desktop '.$agent->platform()) : $agent->device();
        $user = User::find($details['created_by']);
        $details['user_id'] = $details['created_by'];
        $details['full_name'] = $user->hr_employee->fullname;
        $details['email_address'] = $user->email;
        $details['dept_id'] = $user->hr_employee->department->id;
        $details['dept_name'] = $user->hr_employee->department->name;
        $details['logs'] = $this->templates('transactions', $user, $agent, $details['logs']);  
        $details['ip_address'] = $_SERVER['REMOTE_ADDR'];      
        return AuditLog::create($details);
    }

    public function insertJobs($details)
    {
        return CronJobs::create($details);
    }

    public function findJobs($timestamp, $description = '')
    {
        $res1 = CronJobs::where([
            'is_active' => 1, 
            'is_repeated' => 1
        ]);
        if (!empty($description)) {
            $res1 = $res1->where('description', '=', $description);
        }
        $res = CronJobs::where([
            'is_active' => 1
        ])
        ->where('timestamp','LIKE','%'.date('Y-m-d H:i', strtotime($timestamp)).'%');
        if (!empty($description)) {
            $res = $res->where('description', '=', $description);
        }
        $res = $res->union($res1);
        $res = $res->get();
        return $res;
    }

    private static function SMS_SETTINGS($isOTP = 0) 
    {   
        if (!($isOTP > 0)) {
            $setting = SmsServerSetting::find(1);
            return SmsSetting::select(['sms_settings.*'])->where(['sms_settings.type_id' => 2, 'sms_settings.shortcode_mask' => $setting->masking_id])->first();
        }
    }

    public function templates($type, $user, $agent, $logs = '')
    {
        $device = $agent->isDesktop() ? ucwords('Desktop '.$agent->platform()) : $agent->device();
       
        $templates = [
            "notif" => "[Palayan City Hall]\\n\\nHi ".$user->hr_employee->fullname.",\\nWe notice a new sign-in to your account on ".$device." device under IP: ".$_SERVER['REMOTE_ADDR'].". If this was you, please ignore. Otherwise, kindly ask assistance in admin office.",
            "transactions" => "User '" . $user->hr_employee->fullname . "' ". str_replace(".", "", $logs)." on ".$device." under IP: ".$_SERVER['REMOTE_ADDR']."."
        ];

        return $templates[$type];
    }

    public function sendSMS($user, $agent)
    {
        //$settings = self::SMS_SETTINGS()->mask->code;
        $settings = self::SMS_SETTINGS();
        $user = User::find($user->id);
        $details = array(
            'action_id' => 1,
            'type_id' => $settings->type_id,
            'masking_code' => $settings->mask->code,
            'messages' => $this->templates('notif', $user, $agent),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user->id
        );
        $message = SmsMessage::create($details);
        $this->sendTo($user->hr_employee->mobile_no, $message);
    }

    public function sendTo($receipient, $message, $outboxID = 0)
    {   
        $network = [
            1 => 'globe',
            2 => 'smart',
            3 => 'sun',
            4 => 'dito'
        ];
        $receipient = (strlen($receipient) > 10) ?  $receipient : ltrim($receipient, $receipient[0]);

        $validate = $this->getPrefixes($receipient);
        if ($validate != 'auto') {
            if (!($outboxID > 0)) {
                $outbox = SmsOutbox::create([
                    'message_id' => $message->id,
                    'msisdn' => $receipient,
                    'created_at' => $message->created_at,
                    'created_by' => $message->created_by
                ]);
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::SMS_SETTINGS()->payload_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{ 
                    \"app_key\": \"".self::SMS_SETTINGS()->app_key."\", 
                    \"app_secret\": \"".self::SMS_SETTINGS()->app_secret."\", 
                    \"shortcode_mask\": \"".self::SMS_SETTINGS()->mask->code."\", 
                    \"dcs\": \"".self::SMS_SETTINGS()->dcs."\", 
                    \"msisdn\": \"".$receipient."\", 
                    \"content\": \"".$message->messages."\" 
                }",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {                
                $response = json_decode($response);
                $outboxID = ($outboxID > 0) ? $outboxID : $outbox->id;
                $outbox = SmsOutbox::find($outboxID);
                if(!$outbox) {
                    throw new NotFoundHttpException();
                }
                if(!empty($response->telco_id)){
                    $outbox->smsc = $network[$response->telco_id];           
                    $outbox->transid = $response->transid; 
                    $outbox->timestamp = $response->timestamp; 
                    $outbox->msgcount = $response->msgcount; 
                    $outbox->telco_id = $response->telco_id;
                    $outbox->messageId = $response->messageId;
                    $outbox->update();
                }
            }
        }

        return true;
    }

    public function getPrefixes($number)
    {
        $msisdn = $this->num_formats($number);
        $prefix = str_split($msisdn, 4);

        $result = SmsPrefix::select('network')->where('access', 'like', '%' . $prefix[0] . '%')->get();
        $network = 'auto';

        if ($result->count() > 0) {
            foreach ($result as $res) {
                $network = $res->network;
            }
        }

        return $network;
    }

    public function num_formats($msisdn)
    {
        $pattern = array('/i/i','/l/i','/o/i','/[^\d]/','/^(\+63|63)/');
        $replace = array(1,1,0,'','0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        return $msisdn;
    }

    public function verifyPasswordToUpdate(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'verify_psw' => 'required',
                
            ],[
                'verify_psw.required' => 'Required Field'
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $user = DB::table('users')->where('id',\Auth::user()->id)->first();
            if (!password_verify($data['verify_psw'], $user->password)) {
                $validator->errors()->add('verify_psw', 'Password you entered is incorrect!');
             }
        });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
            return response()->json($arr);
        }

        if($request->has('verify_psw_for') && $request->verify_psw_for == 'cahering'){
             session()->put('casheringVerifyPsw',true);
        }else{
            $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$request->verify_psw_id)->first();
            if($propDetails->pk_id == 2){
                session()->put('verifyPswLand',true);
            }if($propDetails->pk_id == 1){
                session()->put('verifyPswBuilding',true);
            }if($propDetails->pk_id == 3){
                session()->put('verifyPswMachine',true);
            }
        }
        
        return response()->json(['status'=>'success','msg' => 'Password Verified successfully!']);
    }

    public function verifyPasswordCashering(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'verify_psw' => 'required',
                
            ],[
                'verify_psw.required' => 'Required Field'
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $user = DB::table('users')->where('id',\Auth::user()->id)->first();
            if (!password_verify($data['verify_psw'], $user->password)) {
                $validator->errors()->add('verify_psw', 'Password you entered is incorrect!');
             }
        });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
            return response()->json($arr);
        }

        if($request->has('verify_psw_for') && $request->verify_psw_for == 'cahering'){
             session()->put('casheringVerifyPsw',true);
        }else{
            $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$request->verify_psw_id)->first();
            if($propDetails->pk_id == 2){
                session()->put('verifyPswLand',true);
            }if($propDetails->pk_id == 1){
                session()->put('verifyPswBuilding',true);
            }if($propDetails->pk_id == 3){
                session()->put('verifyPswMachine',true);
            }
        }
        
        return response()->json(['status'=>'success','msg' => 'Password Verified successfully!']);
    }
}
