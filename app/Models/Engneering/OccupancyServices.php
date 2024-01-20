<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class OccupancyServices extends Model
{
     public function getServices(){
    	return DB::table('cto_tfocs as ctot')
      ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
      ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
      ->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')
      ->where('tfoc_status',1)->where('tfoc_is_applicable',4)->get();
    }
    public function getAppType(){
        return DB::table('eng_application_type')->select('id','eat_module_desc')->get();
    }
    public function getEngModules(){
        return DB::table('eng_modules')->select('id','em_module_desc')->where('em_is_active',1)->get();
    }
    public function getTransactiontype(){
        return DB::table('cto_top_transaction_type')->select('id','ttt_desc')->where('tfoc_is_applicable',4)->get();
    }
    public function updateData($id,$columns){
        return DB::table('occupancy_services')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('occupancy_services')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('occupancy_services')->where('id',$id)->update($columns);
    } 
    
     public function getRequirements(){
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('is_active',1)->where('req_dept_eng',1)->get();
    }
    public function addRequirementData($postdata){
        DB::table('eng_occ_services_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateRequirementData($id,$columns){
        return DB::table('eng_occ_services_requirements')->where('id',$id)->update($columns);
    }
    public function deleteRequirements($id,$ids){
      return DB::table('eng_occ_services_requirements')->where('es_id',$id)->whereNotIn('req_id',$ids)->delete();
    }

    public function checkRequirementExists($serviceid,$reqid){
         return DB::table('eng_occ_services_requirements')->select('id')->where('es_id','=',$serviceid)->where('req_id','=',$reqid)->get();
    }

    public function getRequirementforedit($serviceid){
         return DB::table('eng_occ_services_requirements')->select('id','req_id','orderno','esr_is_required')->where('es_id','=',$serviceid)->get();
    }

    public function getRequirementforview($serviceid){
         return DB::table('eng_occ_services_requirements as sr')->leftjoin('requirements as rq','sr.req_id', '=', 'rq.id')->select('req_description')->where('es_id','=',$serviceid)->get();
    }

    public function GetServicename($id){
      return DB::table('cto_tfocs AS ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('aas.description as chartofaccount')->where('ctot.id',$id)->get();
    }

    public function getAccountDesc($id)
	  {
		return DB::table('acctg_account_subsidiary_ledgers')->select('id','code','description')->where('is_active',1)->where('gl_account_id','=',$id)->get();
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
      0 =>"es.id",
      1 =>"asl.description",
      2 =>"eba.eat_module_desc",
      3 =>"em.em_module_desc",
      4 =>"em.em_module_desc",
      5 =>"es.es_is_active",	
    );

    $sql = DB::table('occupancy_services AS es')
   		  ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'es.tfoc_id') 
        ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id') 
           ->leftjoin('eng_application_type AS eba', 'eba.id', '=', 'es.eat_id')
          ->leftjoin('eng_modules AS em', 'em.id', '=', 'es.emf_id')
          ->select('es.id','afc.tfoc_short_name','asl.description','eat_module_desc','em.em_module_desc','es_is_active');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(eba.eat_module_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(em.em_module_desc)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(asl.description)'),'like',"%".strtolower($q)."%");
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
