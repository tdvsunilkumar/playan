<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngEngineeringstaff extends Model
{
     public function updateData($id,$columns){
        return DB::table('eng_engineeringstaffs')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_engineeringstaffs')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_engineeringstaffs')->where('id',$id)->update($columns);
    }

    public function getdepartmentid($id){
    		return DB::table('hr_employees')->select('id','acctg_department_id')->where('id',$id)->first();
    }
    public function getHrEmployeeCode(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','fullname')->get();
    }
    public function getEmployeeDetails($id){
        $data= DB::table('hr_employees')
		->where('hr_employees.id',$id)
        ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
		->select('hr_designations.description')->first();
		return $data;
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
          0 =>"eng.id", 
          2 =>"p.fullname",
          3 =>"ad.name",
          4 =>"eng.ra_appraiser_position",
          5 =>"p.fullname",
          6 =>"ra_is_active"
              );
              $sql = DB::table('eng_engineeringstaffs AS eng')
                    ->join('hr_employees AS p', 'p.id', '=', 'eng.ees_employee_id')
					->Leftjoin('acctg_departments AS ad', 'ad.id', '=', 'p.acctg_department_id')
					->Leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'p.acctg_department_division_id')
					->Leftjoin('users AS user', 'user.id', '=', 'eng.created_by')
                    ->select('eng.*','p.firstname','p.middlename','p.lastname','p.suffix','p.fullname','ad.name AS departments_name','add.name AS division_name','user.name');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(p.fullname)'),'like',"%".strtolower($q)."%")
						  ->orWhere(DB::raw('LOWER(p.firstname)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(p.middlename)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(p.lastname)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(p.suffix)'),'like',"%".strtolower($q)."%")
						  ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
						  ->orWhere(DB::raw('LOWER(add.name)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(eng.ees_position)'),'like',"%".strtolower($q)."%")
						  ->orWhere(DB::raw('LOWER(eng.is_active)'),'like',"%".strtolower($q)."%")
						  ->orWhere(DB::raw('LOWER(user.name)'),'like',"%".strtolower($q)."%")
						  ;   
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('eng.is_active','DESC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
