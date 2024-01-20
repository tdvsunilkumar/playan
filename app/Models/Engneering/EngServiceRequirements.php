<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EngServiceRequirements extends Model
{
     public function getServicefees(){
    	return DB::table('cto_tfocs')->select('id','tfoc_short_name')->where('tfoc_status',1)->get();
    }
    public function getServices(){
        return DB::table('eng_services')->join('eng_bldg_aptypes AS eba', 'eba.id', '=', 'eng_services.eat_id')->select('eng_services.id','eba.eba_description')->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_description')->where('is_active',1)->get();
    }
    public function updateData($id,$columns){
        return DB::table('eng_service_requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eng_service_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_service_requirements')->where('id',$id)->update($columns);
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
      0 =>"esr.id",
      1 =>"esr.tfoc_id",
      2 =>"es.eat_id",
      3 =>"rq.req_description",
      4 =>"es.esr_is_required",
      5 =>"esr_is_active"		
    );

    $sql = DB::table('eng_service_requirements AS esr')
   		  ->join('cto_tfocs AS afc', 'afc.id', '=', 'esr.tfoc_id') 
          ->join('eng_services AS es', 'esr.es_id', '=', 'es.eat_id')
          ->join('eng_bldg_aptypes AS eba', 'eba.id', '=', 'es.eat_id')
          ->join('requirements AS rq', 'rq.id', '=', 'esr.req_id')
          ->select('esr.id','afc.tfoc_short_name','eba.eba_description as service','rq.req_description','esr_is_required','esr_is_active');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(rq.req_description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(ctot.tfoc_short_name)'),'like',"%".strtolower($q)."%");
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
