<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\SocialWelfare\Citizen;

class GramStainingTest extends Model
{
    public $table = 'ho_gram_stainings';
    
    public function patient() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
    public function h_officer() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'health_officer_id'); 
    }
    public function m_tech() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'med_tech_id'); 
    }
    public function getFields($id){
        return HealthSafetySetupDataService::select('ho_service_name','id')->where([['id',$id],['ho_is_active',1]])->get();
    }
	public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
    public function physician() 
    { 
        return $this->hasOne(HrEmployee::class, 'id', 'hp_code'); 
    }
    public function getPatientAttribute() 
    { 
        return $this->lab_request->patient; 
    }
    public function service() 
    { 
        return $this->hasOne(HealthSafetySetupDataService::class, 'id', 'bs_type'); 
    }
    public function lab_request() 
    { 
        return $this->hasOne(HoLabRequest::class, 'lab_control_no', 'lab_control_no'); 
    }
    public function updateActiveInactive($id,$columns){
        return DB::table('ho_gram_stainings')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('ho_gram_stainings')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_gram_stainings')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('ho_gram_stainings')->where('id',$id)->first();
    }

	 public function getPhysician(){
        return DB::table('hr_employees')->select('*')->get();
    }
	
	public function getCitizens(){
        return DB::table('citizens As cit')
		->join('ho_lab_requests AS hlr', 'hlr.cit_id', '=', 'cit.id')
		->select('cit.*','hlr.lab_req_no','hlr.lab_control_no')
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
            ->select('hr_designations.descrigsion', 'hr_employees.identification_no AS licence_no')
                ->first();
        } catch (\Excegsion $e) {
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
		      1 =>"lab_req_id",
		      2 =>"cit_fullname", 
			  3 =>"cit_age",
		      4 =>"cit_gender",
		      5 =>"brgy_name",
		      6 =>"gs_or_num",
		      7 =>"gs_date",
			  9 =>"gs_result",
		      10 =>"is_posted",
		      11 =>"is_active",
           
        );

        $sql = self::
              join('citizens AS cit', 'cit.id', '=', 'ho_gram_stainings.cit_id')
              ->join('barangays AS brgy', 'brgy.id', '=', 'cit.brgy_id')
              ->select('ho_gram_stainings.*','cit.cit_fullname','cit.cit_age','cit.cit_first_name','cit.cit_middle_name','cit.cit_gender','brgy.brgy_name','cit.cit_last_name');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ho_gram_stainings.gs_lab_num)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_fullname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_age)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_gender)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(brgy.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_gram_stainings.gs_or_num)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_gram_stainings.gs_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_gram_stainings.gs_result)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_gram_stainings.gs_is_posted)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_gram_stainings.gs_is_active)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ho_gram_stainings.id','DESC');

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