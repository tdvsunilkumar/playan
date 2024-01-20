<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

// relation
use App\Models\SocialWelfare\Citizen;

class FecalysisModel extends Model
{
    public $table = 'ho_fecalysis';
    
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
    public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
    public function updateActiveInactive($id,$columns){
        return DB::table('ho_fecalysis')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('ho_fecalysis')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_fecalysis')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('ho_fecalysis')->where('id',$id)->first();
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
		  1 =>"fec_lab_num",
		  2 =>"cit_fullname",
		  3 =>"cit_age",
		  4 =>"cit_gender",		  
		  5 =>"brgy_name",
		  6 =>"fec_or_num",
		  7 =>"fec_date",
		  8 =>"fec_is_posted",
		  9 =>"fec_is_active",    
        );

        $sql = self::
               join('citizens AS cit', 'cit.id', '=', 'ho_fecalysis.cit_id')
               ->join('barangays AS brgy', 'brgy.id', '=', 'cit.brgy_id')
              ->select('ho_fecalysis.*','cit.cit_age','cit.cit_fullname','cit.cit_middle_name','cit.cit_gender','brgy.brgy_name','cit.cit_last_name');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ho_fecalysis.fec_lab_num)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_fullname)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_age)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit.cit_gender)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(brgy.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_fecalysis.fec_or_num)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ho_fecalysis.fec_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_fecalysis.fec_is_posted)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ho_fecalysis.fec_is_active)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('created_at','DESC');

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
