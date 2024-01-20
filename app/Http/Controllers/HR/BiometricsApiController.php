<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\Biometrics;
use App\Models\HR\HrBiometricsRecord;
use App\Models\HR\HrAppointment;
use Hash;
class BiometricsApiController extends Controller
{
    public function __construct(){
        $this->api_password = 'zt3coB1oMetr1c';
    }
    /**
     * Password checker
     *
     * Lisener and server hash key is same 
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function passwordCheck($password)
    {
        if (!Hash::check($this->api_password,$password)) {
            return json_encode([
                'status' => 400,
                'type' => 'auth',
                'msg' => 'Password Invalid!'
            ]);
        }
        return [];
    }

    public function biometrics()
    {
        $biometrics = Biometrics::where('bio_is_copied',0);
        $display = $biometrics->get()->toJSON();
        // $biometrics->update(['bio_is_copied'=>1]);
        return $display;
    }

    public function confirmBiometric(Request $request)
    {
        $check = $this->passwordCheck($request->password);
            if ( $check) {
                return $check;
            }
        $biometrics = Biometrics::find($request->id);
        $display = $biometrics->toJSON();
        $biometrics->update(['bio_is_copied'=>1]);
        return $display;
    }

    /**
     * Recieve Attendance from lisener
     *
     * {
     * user_id = employee id in biometrics
     * date = date in biometrics
     * time = time in biometrics
     * }
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function recieveAttendance(Request $request)
    {
        try {
            $check = $this->passwordCheck($request->password);
            if ( $check) {
                return $check;
            }
            $data = [
                'hr_emp_id' => 0,
                'hra_department_id' => 0,
                'hra_division_id' => 0,
            ];
            $emp = HrAppointment::where('hra_employee_no',$request->user_id)->first();
            if ($emp) {
                $data = [
                    'hr_emp_id' => $emp->hr_emp_id,
                    'hra_department_id' => $emp->hra_department_id,
                    'hra_division_id' => $emp->hra_division_id,
                ];
            }
            HrBiometricsRecord::addData(
                [
                    'hrbr_emp_id' => $data['hr_emp_id'],
                    'hrtc_emp_id_no' => $request->user_id,
                    'hrbr_department_id' => $data['hra_department_id'],
                    'hrbr_division_id' => $data['hra_division_id'],
                    'hrbr_date' => $request->date,
                    'hrbr_time' => $request->time,
                ]
            );
            return json_encode([
                'status' => 200,
                'type' => 'success',
                'msg' => 'Attendance Sent!'
            ]);
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 500,
                'type' => 'error',
                'msg' => $th
            ]);
        }
        
    }
}
