<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponser;
    public $App_KEY = '';
    public function __construct(){
        $this->App_KEY = \Config::get('app.key');
    }
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(),[
                'email'=>'required|email',
                'password'=>'required',
            ],
            [
                'email.required' => 'Please enter email.',
                'email.email' => 'Enter valid email.',
                'password.required' => 'Please enter password.',
            ]
        );

        if ($validator->fails()) {
            $arr=(array)$validator->errors();
            foreach ($arr as $key => $value) {
                if(!empty($value['email'][0])){
                    return $this->sendError($value['email'][0]); 
                }
                if(!empty($value['password'][0])){
                    return $this->sendError($value['password'][0]);
                }
            }
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  auth()->user()->createToken($this->App_KEY)->plainTextToken;
            $success['user'] =  auth()->user();            
            // return $this->sendResponse($success, 'Login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.');
        } 
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Logout successfully.');
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return json_encode($response);
    }
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];
        return json_encode($response);
    }
}
