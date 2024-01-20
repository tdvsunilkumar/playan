<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EcoHousingPenalty;
use DB;

class LegaltypeTransaction extends Model
{
    public $table = 'eco_type_of_transaction';
    public function updateData($id,$columns){
        return DB::table('eco_type_of_transaction')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eco_type_of_transaction')->insert($postdata);
    }
    public function findById($id){
        return DB::table('eco_type_of_transaction')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eco_type_of_transaction')->where('id',$id)->update($columns);
    } 
    public function getTransType(){
      return DB::table('eco_type_of_transaction')->where('is_active',1)->orderBy('type_of_transaction')->get();
    } 
    public function getPenalties(){
      $penalty = EcoHousingPenalty::where('is_active',1)->get()->mapWithKeys(function ($penalty, $key) {
                    $percent = $penalty->id;
                    $array[$percent] = $penalty->name;
                    return $array;
                })->toArray();
      $penalty = array_merge([null=>'Please Select'],$penalty);
      return $penalty;
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
      1 =>"type_of_transaction",
      2 =>"is_active",
    );

    $sql = DB::table('eco_type_of_transaction')
          ->select('*');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(type_of_transaction)'),'like',"%".strtolower($q)."%");
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
