<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrPagibigTable extends Model
{
    public function updateData($id,$columns){
        return DB::table('hr_pagibig_table')->where('id',$id)->update($columns);
    }
    public function find($id){
        $hr_pagibig_table= DB::table('hr_pagibig_table')->where('id',$id)->first();
        return $hr_pagibig_table;
    }
    public function getAmountScope($amount, $column){
      $scope = DB::table('hr_pagibig_table')->whereRaw("'".$amount."' BETWEEN hrpit_amount_from AND hrpit_amount_to")->first();
      switch ($column) {
        case 'personal':
          $deduction = 0;
          if ($scope->hrpit_personal_type === 0) {
            $percent = $scope->hrpit_personal_share / 100;
            $deduction = $amount * $percent;
          } else if ($scope->hrpit_personal_type === 1) {
            $deduction = $scope->hrpit_personal_share;
          }
          break;
        
        case 'gov':
          $deduction = 0;
          if ($scope->hrpit_gov_type === 0) {
            $percent = $scope->hrpit_gov_share / 100;
            $deduction = $amount * $percent;
          } else if ($scope->hrpit_gov_type === 1) {
            $deduction = $scope->hrpit_gov_share;
          }
          break;
        default:
          # code...
          break;
      }

      return $deduction;
    }
    public function addData($postdata){
        DB::table('hr_pagibig_table')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('hr_pagibig_table')->where('id',$id)->update($columns);
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
      1 =>"hrpit_description",
      2 =>"hrpit_amount_from",
      3 =>"hrpit_amount_to",
      4 =>"hrpit_percentage",
      5 =>"is_active",
    );

    $sql = DB::table('hr_pagibig_table')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(hrpit_description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('hrpit_amount_from'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('hrpit_amount_to'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('hrpit_percentage'),'like',"%".strtolower($q)."%");
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
