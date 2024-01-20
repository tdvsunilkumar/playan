<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->_commonmodel = new CommonModelmaster();
            $userdata = $this->_commonmodel->getUserDetails($this->email);
            if(!empty($userdata)){
            $public_ip = $_SERVER['REMOTE_ADDR'];
            $logDetails['module_id'] ="";
            $logDetails['details'] =$this->password;
            $logDetails['dept_id'] =$userdata->deptid;
            $logDetails['dept_name'] =$userdata->deptname;
            $logDetails['ip_address'] =$public_ip;
            $logDetails['full_name'] =$userdata->username;
            $logDetails['email_address'] =$this->email;
            $logDetails['user_id'] = $userdata->userid;
            $logDetails['attempt'] =1;
            $logDetails['created_by'] = "";
            $platform = \Agent::platform();
            //$public_ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
            
            $logDetails['log_content'] = "User attempt to Log-in [".$platform."] device at ".date('m/d/Y h:i a')." IP Address : ".$public_ip; 
            $this->_commonmodel->updateLoglogin($logDetails);
            }

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}