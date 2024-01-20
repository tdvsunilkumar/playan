<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

// relation
use App\Models\SocialWelfare\Citizen;

class SerologyModel extends Model
{
    public $table = 'ho_serology';
    
    public function patient() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
    
    public function h_officer() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'health_officer'); 
    }
    public function m_tech() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'med_tech'); 
    }
    
    public function physician() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'hp_code'); 
    }
    public function details() 
    { 
        return $this->hasMany(SerologyDetails::class, 'ser_id', 'id'); 
    }
    
    public function allService() 
    { 
        return HealthSafetySetupDataService::where([['ho_service_form',2],['ho_is_active',1]])->get(); 
    }
	public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
    public function updateActiveInactive($id,$columns){
     return DB::table('ho_serology')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('ho_serology')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_serology')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('ho_serology')->where('id',$id)->first();
    }
	
	public function getPhysician(){
        return DB::table('hr_employees')->select('*')->get();
    }

    public function getFields(){
        return HealthSafetySetupDataService::where([['ho_service_form',2],['ho_is_active',1]])->get();
    }
	
    public function checkAvail($id){
        $check = HoLabReqFees::where([['lab_req_id',$this->lab_req_id],['service_id',$id]])->first();
        return ($check) ? true : false;
    }

    public function checkMethod($service,$method){
        $check = SerologyDetails::join('ho_serology_method','ho_serology_method.id','ho_serology_details.sm_id')
                    ->select('ser_m_method','ho_serology_details.ser_id')
                    ->where([
                        ['ser_m_method',$method],
                        ['ho_service_id',$service],
                        ['ho_serology_details.ser_id',$this->id]
                    ])->first();
        return ($check) ? true : false;
    }

    public function dataField($id, $field){
        $data = SerologyDetails::where([['ho_service_id',$id],['ser_id',$this->id]])->select($field.' as data')->first();
        // dd($data);
        return ($data)?$data->data:'';
    }

	public function getCitizens(){
        return DB::table('citizens As cit')
		->join('ho_lab_requests AS hlr', 'hlr.cit_id', '=', 'cit.id')
		->select('cit.*','hlr.lab_req_no','hlr.lab_control_no')
		->get();
    }
	
	public function getScreeningTest(){
        return DB::table('ho_serology_method')->select('*')->get();
    }

    public function getScreeningTestList($id,$disabled = false){
        $method = DB::table('ho_serology_method')->select('*')->where('ser_id',$id);
        $list = [""=>""];
        if ($disabled && $method->count() != 0) {
            $list = ["false"=>"Please Select"];
        }
        $method = $method->get();
        foreach ($method as $key => $value) {
            $list[$value->id] = $value->ser_m_method;
        }
        return $list;
    }
    
	public function getTestlistlab($id){
        return DB::table('ho_lab_fees As hlf')
		->join('ho_services AS hose', 'hose.id','hlf.service_id')
		->where('hlf.cit_id',$id)
		->select('hlf.*','hose.id as service_id','hose.ho_service_name As service_name')
		->get();
    }
	public function getlabResult($id){
        return DB::table('ho_serology_details As hsd')
		->join('ho_services AS hose', 'hose.id','hsd.ho_service_id')
		->where('hsd.ser_id',$id)
		->select('hsd.*','hose.id as service_id','hose.ho_service_name As service_name')
		->get();
    }
	
	
	public function getCitizensname($id){
      return DB::table('citizens As cit')
	  ->where('cit.id',$id)
	  ->join('ho_lab_requests AS hlr', 'hlr.cit_id', '=', 'cit.id')
	  ->select('cit.cit_age','cit.cit_gender','hlr.lab_req_no','hlr.lab_control_no')
	  ->first();
    }
	public function getDesignation($employee_id){
        try {
            return DB::table('hr_employees')
            ->join('hr_designations', 'hr_designations.id', 'hr_employees.hr_designation_id')
            ->where('hr_employees.id', $employee_id)
            ->select('hr_designations.description', 'hr_employees.identification_no AS licence_no')
                ->first();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
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
		  1 =>"ser_lab_num",
		  2 =>"cit_fullname",
		  3 =>"cit_age",
		  4 =>"cit_gender",
		  5 =>"brgy_name",
		  6 =>"ser_or_num",
		  8 =>"ser_date",
		  9 =>"ser_is_posted",
		  10 =>"ser_is_active"
        );

        $sql = self::
              join('citizens AS cit', 'cit.id', '=', 'ho_serology.cit_id')
              ->leftjoin('barangays AS bar', 'bar.id', '=', 'cit.brgy_id')
              ->select('ho_serology.*','cit.cit_fullname','cit.cit_age','cit.cit_first_name','cit.cit_middle_name','bar.brgy_name','cit.cit_last_name','cit.cit_gender','cit.cit_full_address');
        
		if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ho_serology.ser_lab_num)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_fullname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cit.cit_age)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cit.cit_gender)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bar.brgy_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_serology.ser_or_num)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_serology.ser_date)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_serology.ser_is_posted)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_serology.ser_is_active)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ho_serology.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
	  
	public function updateusersavedataData($id,$columns){
        return DB::table('user_last_save_data')->where('id',$id)->update($columns);
    }
    public function addusersaveData($data){
        DB::table('user_last_save_data')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function CheckFormdataExist($formid,$userid){
        return DB::table('user_last_save_data')->where('form_id',$formid)->where('user_id',$userid)->get();
    }
}
