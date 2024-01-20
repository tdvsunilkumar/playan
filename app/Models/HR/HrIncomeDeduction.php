<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrIncomeDeduction extends Model
{
    public $table = 'hr_income_and_deduction';
    public function updateData($hriad_ref_no,$emp_id,$columns){
      $sql=DB::table('hr_income_and_deduction')->where('hriad_ref_no',$hriad_ref_no)->where('emp_id',$emp_id)->first();
      if(!empty($sql)){
        return DB::table('hr_income_and_deduction')->where('hriad_ref_no',$hriad_ref_no)->where('emp_id',$emp_id)->update($columns);
      }
      else{
        return DB::table('hr_income_and_deduction')->insert($columns);
      }
    }
    public function deleteByUnselect($hriad_ref_no,$selData){
     
      $d=DB::table('hr_income_and_deduction')
      ->where('hriad_ref_no',$hriad_ref_no)
      ->whereNotIn('emp_id',$selData)
      ->delete();
    }
  
    public function addData($postdata){
        return DB::table('hr_income_and_deduction')->insert($postdata);
    }
    public function getEditDetails($id){
          return DB::table('hr_income_and_deduction')->where('id',$id)->first();
      }
    public function getSelEmpByRef($ref){
        // return  DB::table('hr_income_and_deduction as hid')
        // ->leftjoin('hr_income_deduction_type AS hidt', 'hidt.id', '=', 'hid.hridt_id')
        // ->leftjoin('hr_loan_cycle AS hlc', 'hlc.id', '=', 'hid.hrlc_id')
        // ->leftjoin('hr_employees AS he', 'he.id', '=', 'hid.emp_id')
        // ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
        // ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
        // ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
        // ->where('hid.hriad_ref_no',$ref)
        // ->select('hid.*','he.fullname as emp_name','hidt.hridt_description as app_type','hlc.hrlc_month as cycle','ad.name as dept_name','add.name as div_name','hd.description as designation')
        // ->get();
        $items = DB::table('hr_income_and_deduction')->select('emp_id', 'hriad_effectivity_date', 'hriad_balance','hriad_deduct')->where('hriad_ref_no',$ref)->get();
        return $items;
    }  
    public function getEmpByRefID($ref,$emp_id){
      // return  DB::table('hr_income_and_deduction as hid')
      // ->leftjoin('hr_income_deduction_type AS hidt', 'hidt.id', '=', 'hid.hridt_id')
      // ->leftjoin('hr_loan_cycle AS hlc', 'hlc.id', '=', 'hid.hrlc_id')
      // ->leftjoin('hr_employees AS he', 'he.id', '=', 'hid.emp_id')
      // ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
      // ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
      // ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
      // ->where('hid.hriad_ref_no',$ref)
      // ->select('hid.*','he.fullname as emp_name','hidt.hridt_description as app_type','hlc.hrlc_month as cycle','ad.name as dept_name','add.name as div_name','hd.description as designation')
      // ->get();
      $items = DB::table('hr_income_and_deduction')->select('emp_id', 'hriad_effectivity_date', 'hriad_balance','hriad_deduct')->where(['hriad_ref_no'=>$ref,'emp_id'=>$emp_id])->first();
      return $items;
  }  
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_income_and_deduction')->where('id',$id)->update($columns);
    } 
    public function getAppType(){
      return DB::table('hr_income_deduction_type')->orderBy('hridt_description')->get();
    }  
    public function getCyle(){
      return DB::table('hr_loan_cycle')->where('hrlc_status',1)->orderBy('hrlc_month')->get();
    }  
    public function getCycleById($id){
      $hr_loan_cycle= DB::table('hr_loan_cycle')->where('id',$id)->first();
      return ($hr_loan_cycle) ? $hr_loan_cycle->hrlc_month : 1;
    }  
    public function getSelEmpList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $originalArray = $request->input('selectedValues');
    // dd($originalArray);
    if(!empty($originalArray)){
      $empIds = array_map(function ($item) {
            return $item['emp_id'];
        }, $originalArray);
    }


    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"he.fullname",
      2 =>"ad.name",
      3 =>"hd.description",
    );

    $sql = DB::table('hr_employees AS he')
            ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
            ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
            ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
            ->select('he.*','ad.name as dept_name','add.name as div_name','hd.description as designation');
    if(!empty($empIds) && isset($empIds)){
      $sql->whereIn('he.id',$empIds);
    }
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
        ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
        ->orWhere(DB::raw('LOWER(hd.description)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('he.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    // $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  public function getEmpList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $selectedValues=$request->input('selectedValues');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"he.fullname",
      2 =>"ad.name",
      3 =>"hd.description",
    );

    $sql = DB::table('hr_employees AS he')
            ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
            ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
            ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
            ->select('he.*','ad.name as dept_name','add.name as div_name','hd.description as designation');
    if(!empty($selectedValues) && isset($selectedValues)){
        $sql->whereNotIn('he.id',$selectedValues);  
      }
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
        ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
        ->orWhere(DB::raw('LOWER(hd.description)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    // if(isset($params['order'][0]['column']))
    //   $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    // else
    //   $sql->orderBy('he.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
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
      1 =>"hrlc_month",
      2 =>"hrlc_status",
    );

    $sql = DB::table('hr_income_and_deduction as hid')
            ->leftjoin('hr_income_deduction_type AS hidt', 'hidt.id', '=', 'hid.hridt_id')
            ->leftjoin('hr_loan_cycle AS hlc', 'hlc.id', '=', 'hid.hrlc_id')
            ->leftjoin('hr_employees AS he', 'he.id', '=', 'hid.emp_id')
            ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
            ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
            ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
            ->select('hid.*','he.fullname as emp_name','hidt.hridt_description as app_type','hlc.hrlc_month as cycle','ad.name as dept_name','add.name as div_name','hd.description as designation')
            ->groupBy('hid.hriad_ref_no');
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hid.hriad_ref_no)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('hid.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
  
  public function getType($id,$column){
    $type = HrIncomeDeductionType::find($id);
    return $type ? $type[$column] : null ;
  }
}
