<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgAccountGeneralLedger;

class HrIncomeDeductionType extends Model
{
    public $table = 'hr_income_deduction_type';
    public function updateData($id,$columns){
      
      DB::table('hr_income_and_deduction')->where('hridt_id',$id)->update([
        'gl_id' => $columns['gl_id'],
        'sl_id' => $columns['sl_id'],
        
        'gl_id_debit' => $columns['gl_id_debit'],
        'sl_id_debit' => $columns['sl_id_debit'],
      ]);
        return DB::table('hr_income_deduction_type')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_income_deduction_type')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('hr_income_deduction_type')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_income_deduction_type')->where('id',$id)->update($columns);
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
      1 =>"hridt_description",
      2 =>"is_active",
    );

    $sql = $this->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hridt_description)'),'like',"%".strtolower($q)."%");
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

  public function sl()
  {
    return $this->hasOne(AcctgAccountSubsidiaryLedger::class, 'id', 'sl_id'); 
  }
  public function gl()
  {
    return $this->hasOne(AcctgAccountGeneralLedger::class, 'id', 'gl_id'); 
  }
  public function sl_debit()
  {
    return $this->hasOne(AcctgAccountSubsidiaryLedger::class, 'id', 'sl_id_debit'); 
  }
  public function gl_debit()
  {
    return $this->hasOne(AcctgAccountGeneralLedger::class, 'id', 'gl_id_debit'); 
  }

}
