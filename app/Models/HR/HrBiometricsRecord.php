<?php

namespace App\Models\HR;
use App\Models\HrEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccessApprovalApprover;
use DB;

class HrBiometricsRecord extends Model
{
    public $table = 'hr_biometrics_record';
    public $timestamps = false;
    protected $guarded = ['id'];
    
    public function updateData($id,$columns){
        return DB::table('hr_biometrics_record')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_biometrics_record')->insert($postdata);
        $hr_biometrics_record=DB::table('hr_biometrics_record')->orderBy('id','DESC')->first();
        return $hr_biometrics_record->id;
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_biometrics_record')->where('id',$id)->update($columns);
    } 
    public function find($id){
        return DB::table('hr_biometrics_record')->where('id',$id)->first();
      } 
    public function findLatest(){
        return DB::table('hr_biometrics_record')->orderBy('id','DESC')->first();
      } 
    public function getUserdapartment($id){
        $data= DB::table('hr_employees')->where('hr_employees.user_id',$id)->select('acctg_department_id')->first();
        return $data;
    } 
    public function getDepartment(){
        return DB::table('acctg_departments')->orderBy('name')->get();
      }  
    public function getDivByDept($id){
        return DB::table('acctg_departments_divisions')->where('acctg_department_id',$id)->orderBy('name')->get();
      }

    public function getList($request)
      {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $hrbr_department_id=$request->input('hrbr_department_id');
        $hrbr_division_id=$request->input('hrbr_division_id');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          1 =>"he.identification_no",
          2 =>"he.fullname",
          3 =>"ad.name",
          4 =>"add.name",
          5 =>"hbr.hrbr_date",
          6 =>"hbr.hrbr_time",	
        );

        $sql = DB::table('hr_biometrics_record AS hbr')
              ->leftjoin('hr_employees AS he', 'he.id', '=', 'hbr.hrbr_emp_id')
              ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
              ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
              ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
              ->select('hbr.*','he.fullname as emp_name','hbr.hrtc_emp_id_no as user_id_no','ad.name as dept_name','add.name as div_name','hd.description as designation');
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
          $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ad.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(add.name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('he.identification_no'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('hbr.hrtc_emp_id_no'),'like',"%".strtolower($q)."%");
          });
        }
        if(!empty($hrbr_department_id) && isset($hrbr_department_id)){
            $sql->where('hrbr_department_id',$hrbr_department_id);
          }
          if(!empty($hrbr_division_id) && isset($hrbr_division_id)){
            $sql->where('hrbr_division_id',$hrbr_division_id);
          }
          if(!empty($from_date) && isset($from_date)){
            $sql->whereDate('hrbr_date','>=',$from_date);
          }
          if(!empty($to_date) && isset($to_date)){
            $sql->whereDate('hrbr_date','<=',$to_date);
          }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
          $sql->orderBy('id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
   
}
