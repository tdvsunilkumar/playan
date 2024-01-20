<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\Employee;
use App\Models\Mail\UserCreate;
use App\Models\User;
use App\Models\UserCompany;
use Auth;
use File;
use App\Models\Utility;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;
use Spatie\Permission\Models\Role;
use Storage;
//use Intervention\Image\Facades\Image;
use Image;
class UserController extends Controller
{

    public function index()
    {
        $user = \Auth::user();
        
            $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();

            return view('user.index')->with('users', $users);
        

    }

    public function create()
    {

        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name','!=','client')->get()->pluck('name', 'id');
        
            return view('user.create', compact('roles', 'customFields'));
        
    }

    public function store(Request $request)
    {
        
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:users',
                                   'password' => 'required|min:6',
                                   'role' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $objUser    = \Auth::user();
            $role_r                = Role::findById($request->role);
            $psw                   = $request->password;
            $request['password']   = Hash::make($request->password);
            $request['type']       = $role_r->name;
            $request['lang']       = !empty($default_language) ? $default_language->value : 'en';
            $request['created_by'] = \Auth::user()->creatorId();
            $user = User::create($request->all());
            $user->assignRole($role_r);

            if($request['type'] != 'client')
                \App\Models\Utility::employeeDetails($user->id,\Auth::user()->creatorId());

            //Send Email

            $user->password = $psw;
            $user->type     = $role_r->name;

            $userArr = [
                'email' => $user->email,
                'password' =>  $user->password,
            ];
            $resp = Utility::sendEmailTemplate('create_user', [$user->id => $user->email], $userArr);
            return redirect()->route('users.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

        

    }

    public function edit($id)
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name','!=','client')->get()->pluck('name', 'id');
        
            $user              = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields      = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('user.edit', compact('user', 'roles', 'customFields'));
        

    }


    public function update(Request $request, $id)
    {
       
            if(\Auth::user()->type == 'company')
            {
                $user = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:120',
                                       'email' => 'required|email|unique:users,email,' . $id,
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $role = Role::findById($request->role);
                $input = $request->all();
                $input['type'] = $role->name;

                $user->fill($input)->save();
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success', 'User successfully updated.'
                );
            }
            else
            {
                $user = User::findOrFail($id);
                $this->validate(
                    $request, [
                                'name' => 'required|max:120',
                                'email' => 'required|email|unique:users,email,' . $id,
                                'role' => 'required',
                            ]
                );

                $role          = Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();
                Utility::employeeDetailsUpdate($user->id,\Auth::user()->creatorId());
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success', 'User successfully updated.'
                );
            }
        
    }


    public function destroy($id)
    {
        
            $user = User::find($id);
            if($user)
            {
                if(\Auth::user()->type == 'company')
                {
                    if($user->delete_status == 0)
                    {
                        $user->delete_status = 1;
                    }
                    else
                    {
                        $user->delete_status = 0;
                    }
                    $user->save();
                }
                if(\Auth::user()->type == 'company')
                {
                    $employee = Employee::where(['user_id' => $user->id])->delete();
                    if($employee){
                        $delete_user = User::where(['id' => $user->id])->delete();
                        if($delete_user){
                            return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
                        }else{
                            return redirect()->back()->with('error', __('Something is wrong.'));
                        }
                    }else{
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }

                return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        
    }

    public function profile()
    {
        $userDetail              = \Auth::user();
		
        $userDetail->customField = CustomField::getData($userDetail, 'user');
        $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $users                   = User::find(\Auth::user()->id);
        $eSignature              = url('uploads/e-signature/'.$users->hr_employee->identification_no.'_'.urlencode($users->hr_employee->fullname).'.png');
        $eSignatureExist         = file_exists('uploads/e-signature/'.$users->hr_employee->identification_no.'_'.urlencode($users->hr_employee->fullname).'.png');
        return view('user.profile', compact('userDetail', 'customFields', 'eSignature', 'eSignatureExist'));
    }

    public function editprofile(Request $request)
    {
        // return $request->all();
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );
        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
        }

        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
		$user['is_activate_digitalsignature'] = $request['is_activate_digitalsignature'];
        if(isset($request['is_active_e_sign'])){
            $user['is_active_e_sign'] = 1;
        }else{
            $user['is_active_e_sign'] = 0; 
        }
        $user->save();
        CustomField::saveData($user, $request->customField);

        if(!empty($request->e_sign)) {
            $uploaddir = 'e-signature';
            Storage::disk('uploads')->makeDirectory($uploaddir);
            $allowed = array('png');
            $filename = $_FILES['e_sign']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                echo 'error';
            } else {
                $files = $user->hr_employee->identification_no . "_" . urlencode($user->hr_employee->fullname) . '.png';    
                $guessExtension = $request->file('e_sign')->guessExtension();
                $file = $request->file('e_sign')->storeAs($uploaddir, $files,'uploads');
                DB::table('users')->where('id',$user->id)->update(array('e_signature'=>$files));
            }
        } else {
            if($request->signature != null){
                $uploaddir = 'e-signature';
                Storage::disk('uploads')->makeDirectory($uploaddir);
                $image_parts = explode(";base64,", $request->post('signature'));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];        
                $image_base64 = base64_decode($image_parts[1]);
                //$newfile =  "resize_" . urlencode($user->hr_employee->fullname) . '.' . $image_type; 
				$file = $user->hr_employee->identification_no . "_" . urlencode($user->hr_employee->fullname) . '.' . $image_type;				
				Storage::put($uploaddir . '/' . $file, $image_base64);
				//$originalImagePath =   public_path().'/uploads/e-signature/'.$newfile;
				//$resizedImagePath = public_path().'/uploads/e-signature/'.$file;
				//$image = Image::make($originalImagePath);
				// $image->fit(180,70, function ($constraint) {
				// 	//$constraint->upsize();
				// 	$constraint->aspectRatio();
				// });
				// $image->save($resizedImagePath,100);
                DB::table('users')->where('id',$user->id)->update(array('e_signature'=>$file));
            }  
        }
        return redirect()->route('profile')->with(
            'success', 'Profile successfully updated.'
        );
    }
	
    public function updateSignature(Request $request)
    {          
        // $img = imagecreatefrompng(url('uploads/e-signature/'.$file));
        // $remove = imagecolorallocate($img, 255, 255, 255);
        // imagecolortransparent($img, $remove);
        // imagepng($img, 'uploads/e-signature/'.$file);
    
        // // file_put_contents($file, $image_base64);

        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);

        if($request->signature != null){
            $uploaddir = 'e-signature';
            Storage::disk('uploads')->makeDirectory($uploaddir);
            $image_parts = explode(";base64,", $request->post('signature'));
            $image_type_aux = explode("image/", $image_parts[0]);        
            $image_type = $image_type_aux[1];        
            $image_base64 = base64_decode($image_parts[1]); 
			
            $file = $user->hr_employee->identification_no . "_" . $user->hr_employee->fullname . '.' . $image_type;        
            // file_put_contents($file, $image_base64);
            Storage::put($uploaddir . '/' . $file, $image_base64);
            DB::table('users')->where('id',$user->id)->update(array('e_signature'=>$file));
        }        

        $uploaddir = 'e-signature';
        Storage::disk('uploads')->makeDirectory($uploaddir);
        $image_parts = explode(";base64,", $request->post('signature'));
        $image_type_aux = explode("image/", $image_parts[0]);        
        $image_type = $image_type_aux[1];      


        $image_base64 = base64_decode($image_parts[1]);    
        $filename = $user->hr_employee->identification_no . "_" . urlencode($user->hr_employee->fullname) . '.' . $image_type;    
        Storage::put($uploaddir . '/' . $filename, $image_base64);   
        // $this->resize_image($image_base64['tmp_name'], 400, 200, $uploaddir, $filename);

        // $file = public_path().'/uploads/e-signature/'.$filename;
        // if(File::exists($file)) {
        //     $this->resize_image($file, 400, 200, $uploaddir, $filename);
        // }


        // $path = public_path('uploads/e-signature/'.$filename);
        // $file = File::files($path);
        // dd($file);
        // sleep(1);
        // $this->resize_image($file, 400, 200, $uploaddir, $filename);
        // while($file = readdir($handle)){
        //     $this->resize_image($file, 400, 200, $uploaddir, $filename);
        // }

        // Storage::put($uploaddir . '/' . $filename, $image_base64); 

        return redirect()->route('dashboard')->with(
            'success', 'Profile successfully updated.'
        );
    }

    function resize_image($file, $w, $h, $directory, $filename) {
        $original_img = imagecreatefrompng($file);
        $original_width = imagesx($original_img);
        $original_height = imagesy($original_img);
        $new_img = imagecreatetruecolor($w, $h);
        imagecopyresampled($new_img, $original_img, 0,0,0,0, $w, $h, $original_width, $original_height);
        header('Content-type: image/png'); 
        // return Storage::put($directory . '/' . urlencode($filename), $new_img); 
        // Storage::put( public_path().'/uploads/e-signature/'.$filename, $new_img);   
        return imagepng($new_img, public_path().'/uploads/e-signature/'.$filename, 0);
    }

    public function updatePassword(Request $request)
    {

        if(Auth::Check())
        {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['old_password'], $current_password))
            {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }
    // User To do module
    public function todo_store(Request $request)
    {
        $request->validate(
            ['title' => 'required|max:120']
        );

        $post            = $request->all();
        $post['user_id'] = Auth::user()->id;
        $todo            = UserToDo::create($post);


        $todo->updateUrl = route(
            'todo.update', [
                             $todo->id,
                         ]
        );
        $todo->deleteUrl = route(
            'todo.destroy', [
                              $todo->id,
                          ]
        );

        return $todo->toJson();
    }

    public function todo_update($todo_id)
    {
        $user_todo = UserToDo::find($todo_id);
        if($user_todo->is_complete == 0)
        {
            $user_todo->is_complete = 1;
        }
        else
        {
            $user_todo->is_complete = 0;
        }
        $user_todo->save();
        return $user_todo->toJson();
    }

    public function todo_destroy($id)
    {
        $todo = UserToDo::find($id);
        $todo->delete();

        return true;
    }

    // change mode 'dark or light'
    public function changeMode()
    {
        $usr = Auth::user();
        if($usr->mode == 'light')
        {
            $usr->mode      = 'dark';
            $usr->dark_mode = 1;
        }
        else
        {
            $usr->mode      = 'light';
            $usr->dark_mode = 0;
        }
        $usr->save();

        return redirect()->back();
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);

        $plans = Plan::get();

        return view('user.plan', compact('user', 'plans'));
    }
    public function activePlan($user_id, $plan_id)
    {

        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if($assignPlan['is_success'] == true && !empty($plan))
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => isset(\Auth::user()->planPrice()['currency']) ? \Auth::user()->planPrice()['currency'] : '',
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        }
        else
        {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }

    }

    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('user.reset', compact('user'));

    }

    public function userPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'password' => 'required|confirmed|same:password_confirmation',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
                             'password' => Hash::make($request->password),
                         ])->save();

        return redirect()->route('users.index')->with(
            'success', 'User Password successfully updated.'
        );


    }

}
