<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrLoanApplication extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_loan_applications')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
      DB::table('hr_loan_applications')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
	public function getEditDetails($id){
        return DB::table('hr_loan_applications')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_loan_applications')->where('id',$id)->update($columns);
    }
	
	public function getEmployee(){
       return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }
	public function getDepartment(){
       return DB::table('acctg_departments')->select('id','name')->get();
    }
	public function getDivision(){
       return DB::table('acctg_departments_divisions')->select('id','name')->get();
    }
	public function getHrLoanType(){
       return DB::table('hr_loan_types')->select('id','hrlt_description')->get();
    }
	public function getHrLoanCycle(){
       return DB::table('hr_loan_cycle')->select('id','hrlc_month')->get();
    }
	public function getDesignation($employee_id){
        try {
            return DB::table('hr_employees')
            ->where('hr_employees.id', $employee_id)
            ->select('acctg_department_id', 'acctg_department_division_id')
                ->first();
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
    public function loan_type() 
    { 
        return $this->hasOne(HrLoanType::class, 'id', 'hrla_id'); 
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
      1 =>"hrla_employeesid",
      2 =>"hrla_department_id",
	  3 =>"hrla_division_id",
	  4 =>"hrla_application_no",
	  5 =>"hrla_loan_status",
	  6 =>"hrla_loan_description",
	  7 =>"hrla_id",
    );

    $sql = DB::table('hr_loan_applications')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrla_loan_description)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  public function getSL($id){
    return HrLoanType::find($id)->sl_id;
  }
}
