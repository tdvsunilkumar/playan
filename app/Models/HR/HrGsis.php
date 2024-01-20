<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrGsis extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_gsis_table')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('hr_gsis_table')->insert($postdata);
    }
	public function getEditDetails($id){
        return DB::table('hr_gsis_table')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_gsis_table')->where('id',$id)->update($columns);
    } 
    public function getAmountScope($amount, $column){
        $scope = DB::table('hr_gsis_table')->whereRaw("'".$amount."' BETWEEN hrgt_amount_from AND hrgt_amount_to")->first();
        switch ($column) {
          case 'personal':
            $deduction = 0;
            if ($scope->hrgt_personal_type === 0) {
              $percent = $scope->hrgt_personal_share / 100;
              $deduction = $amount * $percent;
            } else if ($scope->hrgt_personal_type === 1) {
              $deduction = $scope->hrgt_personal_share;
            }
            break;
          
          case 'gov':
            $deduction = 0;
            if ($scope->hrgt_gov_type === 0) {
              $percent = $scope->hrgt_gov_share / 100;
              $deduction = $amount * $percent;
            } else if ($scope->hrgt_gov_type === 1) {
              $deduction = $scope->hrgt_gov_share;
            }
            break;
          default:
            # code...
            break;
        }
  
        return $deduction;
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
      1 =>"hrgt_description",
      2 =>"hrgt_status",
	  3 =>"hrgt_amount_from",
	  4 =>"hrgt_amount_to",
	  5 =>"hrgt_percentage",
    );

    $sql = DB::table('hr_gsis_table')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrgt_description)'),'like',"%".strtolower($q)."%");
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
