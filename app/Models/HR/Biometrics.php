<?php
// uses ZKBio device 

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Rats\Zkteco\Lib\ZKTeco;
// use App\Traits\ModelUpdateCreate;
use Carbon\Carbon;
use Auth;

class Biometrics extends Model
{
    // use ModelUpdateCreate;
    public $table = 'biometrics';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array( 
            1 =>"bio_ip",
            2 =>"bio_proxy",   
            3 =>"bio_department",   
            4 =>"bio_code",   
            5 =>"bio_model",   
        );
        $sql = $this;
        if(!empty($q) && isset($q)){
            $sql = $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(bio_ip)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(bio_proxy)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(bio_department)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(bio_code)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(bio_model)'),'like',"%".strtolower($q)."%");
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

    public function addRecord($data){
        $emp = HrAppointment::where('hra_employee_no',$data['hrtc_emp_id_no'])->first();
        $data['hrbr_emp_id'] = $emp ? $emp->hr_emp_id : 0;
        $data['hrbr_department_id'] = $emp ? $emp->hra_department_id : 0;
        $data['hrbr_division_id'] = $emp ? $emp->hra_division_id : 0;
        $data['created_by'] = Auth::id();
        $data['created_at'] = Carbon::now();

        $data = HrBiometricsRecord::firstOrCreate(
            [
                'hrtc_emp_id_no' => $data['hrtc_emp_id_no'],
                'hrbr_date' => $data['hrbr_date'],
                'hrbr_time' => $data['hrbr_time'],
            ],
            $data
        );
        return $data;
    }

    // can remove
    public function start($ip = null)
    {
        if ($ip == null) {
            $ip = $this->bio_ip;
        }
        $zk = new ZKTeco($ip,4370);
        $zk->connect();
        $zk->disableDevice();
        return $zk;
    }
    
    /**
     * Add user to biometrics
     *
     * parameters
     * 
     * {
     * 'uid' => int(),
     * 'userid' => int|string ( max length = 9),
     * 'name' => string,
     * 'password' => int(),
     * 'ip' => ip,
     * 'cardno' => int() (max length = 10, Default 0),
     * 'role' => int() (default is 1 for normal user| 14 for super user),
     * }
     *
     **/
    public function addUser($request)
    {
        $role = isset($request['role']) ? $request['role'] : 1;
        $cardno = isset($request['cardno']) ? $request['cardno'] : 0;
        $ip = isset($request['ip']) ? $request['ip'] : 0;
        $zk = Self::start($ip);
        $zk->setUser(
            $request['uid'],
            $request['userid'],
            $request['name'],
            $request['password'],
            $role,
            $cardno);
        return $request['uid'];
    }

    /**
     * Remove user to biometrics
     *
     * parameters
     * 
     * $id = uid 
     *
     **/
    public function removeUser($ip = null)
    {
        $zk = Self::start($ip);
        return $zk->removeUser($id);
    }

    public function listUser($ip = null)
    {
        $zk = Self::start($ip);
        return $zk->getUser();
    }

    public function getAttendance()
    {
        $zk = Self::start();
        $attendance = $zk->getAttendance();
            dd($attendance);
        foreach ($attendance as $key => $value) {
            $timestamp = Carbon::parse($value['timestamp']);
            $emp = HrAppointment::where('hra_employee_no',$value['id'])->first();
            if ($emp) {
            dd($emp);
            # code...
            }
            // HrBiometricsRecord::addData(
            //     [
            //         'hrbr_emp_id' => hr_emp_id,
            //         'hrtc_emp_id_no' => $value['id'],
            //         'hrbr_department_id' => hra_department_id,
            //         'hrbr_division_id' => hra_division_id,
            //         'hrbr_date' => $timestamp->toDateString(),
            //         'hrbr_time' => $timestamp->toTimeString(),
            //     ]
            // );
        }
        
        return $attendance;
    }

    public function testBiometric($ip)
    {
        $zk = Self::start($ip);
        $zk->testVoice();
        $model = $zk->version(); 
        return [
            'model'=>$model
        ];
    }


}


