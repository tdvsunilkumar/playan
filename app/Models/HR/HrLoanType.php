<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgAccountGeneralLedger;

class HrLoanType extends Model
{
    public function updateData($id,$columns){
      $db = DB::table('hr_loan_ledger')
      ->join('hr_loan_applications','hr_loan_applications.id','hr_loan_ledger.hrla_id')
      ->where('hr_loan_applications.hrla_id',$id)
      ->update([
        'gl_id' => $columns['gl_id'],
        'sl_id' => $columns['sl_id'],
      ]);
        return DB::table('hr_loan_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_loan_types')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      
      return DB::table('hr_loan_types')->where('id',$id)->update($columns);
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
      1 =>"hrlt_description",
      2 =>"is_active",
      3 =>"is_active",
    );

    $sql = DB::table('hr_loan_types')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrlt_description)'),'like',"%".strtolower($q)."%");
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

  //relation
  public function sl()
  {
    return $this->hasOne(AcctgAccountSubsidiaryLedger::class, 'id', 'sl_id'); 

  }
  public function gl()
  {
    return $this->hasOne(AcctgAccountGeneralLedger::class, 'id', 'gl_id'); 

  }
}
