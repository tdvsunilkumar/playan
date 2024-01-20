<?php

namespace App\Http\Controllers\Auth;

use App\Models\Customer;
use App\Models\Vender;
use App\Models\Utility;
use App\Models\CommonModelmaster;
use App\Models\IpExclusion;
use App\Models\IpRegistration;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;
use App\User;
use Session;
use Illuminate\Validation\ValidationException;
use App\Models\SmsPrefix;

class AuthenticatedSessionController extends Controller
{
   
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */


    public function __construct()
    {
        // if(!file_exists(storage_path() . "/installed"))
        // {
        //     header('location:install');
        //     die;
        // }
        // $this->middleware('guest')->except('logout');
        $this->IpExclusion= new IpExclusion(); 
        $this->IpRegistration = new IpRegistration();
        $this->_commonmodel = new CommonModelmaster();
    }

    public function create()
    {
        // return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */


    // protected function authenticated(Request $request)
    //    {


    //             $user = Auth::user();
    //        if($user->delete_status == 0)
    //        {
    //            auth()->logout();
    //        }

    //        if($user->is_active == 0)
    //        {
    //            auth()->logout();
    //        }
    //    }
    public function num_format($msisdn)
    {
        $pattern = array('/i/i','/l/i','/o/i','/[^\d]/','/^(\+63|63)/');
        $replace = array(1,1,0,'','0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        return $msisdn;
    }    
    
    public function getPrefix($number)
    {
        $msisdn = $this->num_format($number);
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

    public function store(LoginRequest $request)
    {
        $public_ip = $_SERVER['REMOTE_ADDR'];
        //for ip validation
        $check_ip_security=$this->IpRegistration->getIpSettingStatus();
        $check_is_super_admin=$this->IpRegistration->check_is_super_admin($request->input('email'));
        $userdata = $this->_commonmodel->getUserDetails($request->input('email'));
        if($check_ip_security->value == 1 && $check_is_super_admin == null)
        {
            $ip_reg_check=$this->IpRegistration->checkIpReg($public_ip);
            if($ip_reg_check == null){
                $user_exclusion_check=$this->IpExclusion->checkUserExclusion($request->input('email'));
                if($user_exclusion_check == null){
                    if(!empty($userdata)){
                        $userdata = $this->_commonmodel->getUserDetails($request->input('email'));
                        $public_ip = $_SERVER['REMOTE_ADDR'];
                        $logDetails['module_id'] ="";
                        $logDetails['details'] = $request->input('password');
                        $logDetails['dept_id'] =$userdata->deptid;
                        $logDetails['dept_name'] =$userdata->deptname;
                        $logDetails['ip_address'] =$public_ip;
                        $logDetails['full_name'] =$userdata->username;
                        $logDetails['email_address'] =$request->input('email');
                        $logDetails['user_id'] = $userdata->userid;
                        $logDetails['attempt'] =1;
                        $logDetails['created_by'] = "";
                        $platform = \Agent::platform();
                        //$public_ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
                        
                        $logDetails['log_content'] = "User attempt to Log-in [".$platform."] device at ".date('m/d/Y h:i a')." via un-registered IP Address. IP Address : ".$public_ip; 
                        $this->_commonmodel->updateLoglogin($logDetails);
                        }
                    throw ValidationException::withMessages([
                        'email' => __('Your IP Address is not allowed to access the system.
                        Please inform the Administrator.'),
                    ]);
                }
            }
        }
           
        //ReCpatcha
        if(env('RECAPTCHA_MODULE') == 'yes')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else{
            $validation = [];
        }
        $password = $request->password; 
        $this->validate($request, $validation);

        $authresult = $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        //echo "<pre>"; print_r($user); exit;
        if($user->delete_status == 0)
        {
            auth()->logout();
        }

        if($user->is_active == 0)
        {
            auth()->logout();
        }
        // Update Last Login Time
        $user->update(
            [
                'last_login_at' => Carbon::now()->toDateTimeString(),
            ]
        );
        $arr['name']=$user->name;
        $arr['email']=$user->email;
        $public_ip = $_SERVER['REMOTE_ADDR'];
        $logDetails['module_id'] ="";
        $logDetails['details'] ="";
        $logDetails['dept_id'] =$userdata->deptid;
        $logDetails['dept_name'] =$userdata->deptname;
        $logDetails['ip_address'] =$public_ip;
        $logDetails['full_name'] =$userdata->username;
        $logDetails['email_address'] =$request->email;
        $logDetails['attempt'] =0;
        $logDetails['user_id'] = Auth::user()->id;
        $logDetails['created_by'] = Auth::user()->id;
        $platform = \Agent::platform();
        //$public_ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
      
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Successfully Log-in to [".$platform."] device at ".date('m/d/Y h:i a')." IP Address : ".$public_ip; 
        $this->_commonmodel->updateLoglogin($logDetails);
        //$this->sendLoginNotification($arr);
        // Get Setting Data
        $this->settingData();
        /**
         * SEND SMS NOTIF
         */
        $receipient = $user->hr_employee->mobile_no;
        $receipient = (strlen($receipient) > 10) ?  $receipient : ltrim($receipient, $receipient[0]);
        $validate = $this->getPrefix($receipient);
        if ($user->user_role->id <> 1) {
            if ($validate != 'auto' && strlen($receipient) == 11) {
                // dd($receipient .' - '. $validate);
                $this->sendSMS($user, $agent = new Agent());
            }
        }

        if($user->type =='company' || $user->type =='super admin' || $user->type =='client')
        {   
            // return redirect()->route('dashboard');
            //$this->_commonmodel = new CommonModelmaster();
            //$this->_commonmodel->sendEmailThourghAjax('sendLoginNotification',$arr);
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        else
        {
            return redirect()->intended(RouteServiceProvider::HOME);
            // return redirect()->intended(RouteServiceProvider::EMPHOME);
        }
    }
    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function settingData() {
        $arr = DB::table('settings')->select('value')->where('name', 'IS_SYNC_TO_TAXPAYER')->first();
        if(isset($arr)){
            Session::put('IS_SYNC_TO_TAXPAYER', $arr->value);
        }
    }

    public function destroy(Request $request)
    {
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        // return redirect('/');

        Session::flush();
        Auth::logout();
        return redirect('login');
    }


    public function showCustomerLoginForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function customerLogin(Request $request)
    {

        $this->validate(
            $request, [
                        'email' => 'required|email',
                        'password' => 'required|min:6',
                    ]
        );

        if(\Auth::guard('customer')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ], $request->get('remember')
        ))
        {
            if(\Auth::guard('customer')->user()->is_active == 0)
            {
                \Auth::guard('customer')->logout();
            }
            $user = \Auth::guard('customer')->user();
            $user->update(
                [
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                ]
            );

            return redirect()->route('customer.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showVenderLoginForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }

    public function venderLogin(Request $request)
    {
        $this->validate(
            $request, [
                        'email' => 'required|email',
                        'password' => 'required|min:6',
                    ]
        );
        if(\Auth::guard('vender')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ], $request->get('remember')
        ))
        {
            if(\Auth::guard('vender')->user()->is_active == 0)
            {
                \Auth::guard('vender')->logout();
            }
            $user = \Auth::guard('vender')->user();
            $user->update(
                [
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                ]
            );

            return redirect()->route('vender.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm($lang = '')
    {

        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        $settings = Utility::settings();

        return view('auth.login', compact('lang','settings'));
    }

    public function showLinkRequestForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }


        \App::setLocale($lang);

        return view('auth.forgot-password', compact('lang'));
    }

    public function showCustomerLoginLang($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function showVenderLoginLang($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }

    //    ---------------------------------Customer ----------------------------------_
    public function showCustomerLinkRequestForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.passwords.customerEmail', compact('lang'));
    }

    public function postCustomerEmail(Request $request)
    {

        $request->validate(
            [
                'email' => 'required|email|exists:customers',
            ]
        );

        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        Mail::send(
            'auth.customerVerify', ['token' => $token], function ($message) use ($request){
            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        }
        );

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {

        $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
        $lang             = !empty($default_language) ? $default_language->value : 'en';

        \App::setLocale($lang);

        return view('auth.passwords.reset')->with(
            [
                'token' => $token,
                'email' => $request->email,
                'lang' => $lang,
            ]
        );
    }

    public function getCustomerPassword($token)
    {

        return view('auth.passwords.customerReset', ['token' => $token]);
    }

    public function updateCustomerPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:customers',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if(!$updatePassword)
        {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Customer::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed.');

    }

    //    ----------------------------Vendor----------------------------------------------------
    public function showVendorLinkRequestForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.passwords.vendorEmail', compact('lang'));
    }

    public function postVendorEmail(Request $request)
    {

        $request->validate(
            [
                'email' => 'required|email|exists:venders',
            ]
        );

        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        Mail::send(
            'auth.vendorVerify', ['token' => $token], function ($message) use ($request){
            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        }
        );

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function getVendorPassword($token)
    {

        return view('auth.passwords.vendorReset', ['token' => $token]);
    }

    public function updateVendorPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:venders',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if(!$updatePassword)
        {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Vender::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed.');

    }
   

    public function sendLoginNotification($params){
        $platform = \Agent::platform();
        //$public_ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
        $public_ip = $_SERVER['REMOTE_ADDR'];
        $name = $params['name'];
        $email = $params['email'];
        $html = view('mails.loginNotification');

        $html = str_replace("{USER_EMAIL}",$email, $html);
        $html = str_replace("{USERNAME}",$name, $html);
        $html = str_replace("{PUBLIC_IP}",$public_ip, $html);
        $html = str_replace("{OS_NAME}",$platform, $html);
        $html = str_replace("{DATETIME}",date("d M, Y h:i a"), $html);
        $data['message'] = $html;
        $data['to_name']=$name;
        $data['to_email']=$email;
        //$data['to_email']='tushalburungale11@gmail.com';
        $data['subject']='Palayan City Hall: System Login Notice';
        Mail::send([], ['data' =>$data], function ($m) use ($data) {
            $m->to($data['to_email'], $data['to_name']);
            $m->subject($data['subject']);
            $m->setBody($data['message'], 'text/html');
        }); 
        
    }
    
}
