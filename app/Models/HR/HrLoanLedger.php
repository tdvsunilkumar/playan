<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\ModelUpdateCreate;

class HrLoanLedger extends Model
{
    use ModelUpdateCreate;
    public $table = 'hr_loan_ledger';
    public $timestamps = false;
    protected $guarded = ['id'];
    public function updateData($id,$columns){
        return DB::table('hr_loan_ledger')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_loan_ledger')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('hr_loan_ledger')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_loan_ledger')->where('id',$id)->update($columns);
    }
	public function GetPaymentdetails($id){
    	  return DB::table('hr_loan_ledger')->select('*')->where('hrla_id',$id)->orderby('id', 'ASC')->get();
    }
    public function loan_app() 
    { 
        return $this->belongsTo(HrLoanApplication::class, 'hrla_id', 'id'); 
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
      1 =>"hrll_employeesid",
      2 =>"hrll_department_id",
	  3 =>"hrll_division_id",
	  4 =>"hrla_id",
	  5 =>"hrll_deduction_status",
    );

    $sql = DB::table('hr_loan_ledger')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrll_balance)'),'like',"%".strtolower($q)."%");
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
}
