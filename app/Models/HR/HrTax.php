<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrTax extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_tax_table')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_tax_table')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('hr_tax_table')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_tax_table')->where('id',$id)->update($columns);
    } 
    public function getEwt(){
      return DB::table('acctg_expanded_withholding_taxes')->orderBy('name')->get();
    }  
    public function getAmountScope($amount){
        return DB::table('hr_tax_table')->whereRaw("'".$amount."' BETWEEN hrtt_amount_from AND hrtt_amount_to")->first();
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
      1 =>"hrtt_description",
      2 =>"hrtt_status",
	  3 =>"hrtt_amount_from",
	  4 =>"hrtt_amount_to",
	  5 =>"hrtt_percentage",
    );

    $sql = DB::table('hr_tax_table')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrtt_description)'),'like',"%".strtolower($q)."%");
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
