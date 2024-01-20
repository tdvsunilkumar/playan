<?php

namespace App\Models\CPDO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CpdoService extends Model
{
	 public function getServices(){
    	return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')->where('tfoc_status',1)->where('tfoc_is_applicable',5)->get();
    }
    public function getAppType(){
        return DB::table('eng_application_type')->select('id','eat_module_desc')->get();
    }
    public function getEngModules(){
        return DB::table('eng_modules')->select('id','em_module_desc')->where('em_is_active',1)->get();
    }

    public function getTransactiontype(){
        return DB::table('cto_top_transaction_type')->select('id','ttt_desc')->where('tfoc_is_applicable',5)->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_description')->where('is_active',1)->where('req_dept_cpdo',1)->get();
    }
    public function updateData($id,$columns){
        return DB::table('cpdo_services')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cpdo_services')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getRequirementforedit($serviceid){
         return DB::table('cpdo_service_requirements')->select('id','req_id','orderno','csr_is_required')->where('cs_id','=',$serviceid)->get();
    }
    public function checkRequirementExists($serviceid,$reqid){
         return DB::table('cpdo_service_requirements')->select('id')->where('cs_id','=',$serviceid)->where('req_id','=',$reqid)->get();
    }
    public function addRequirementData($postdata){
        DB::table('cpdo_service_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('cpdo_services')->where('id',$id)->update($columns);
    }

     public function deleteRequirements($id,$ids){
      return DB::table('cpdo_service_requirements')->where('cs_id',$id)->whereNotIn('req_id',$ids)->delete();
    }

     public function getRequirementforview($serviceid){
         return DB::table('cpdo_service_requirements as sr')->leftjoin('requirements as rq','sr.req_id', '=', 'rq.id')->select('req_description')->where('cs_id','=',$serviceid)->get();
    } 

    public function updateRequirementData($id,$columns){
        return DB::table('cpdo_service_requirements')->where('id',$id)->update($columns);
    }
     public function GetServicename($id){
      return DB::table('cto_tfocs AS ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('aas.description as chartofaccount','tfoc_amount')->where('ctot.id',$id)->get();
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
      0 =>"cs.id",
      1 =>"asl.description",
      3 =>"cs_is_active"	
    );

    $sql = DB::table('cpdo_services AS cs')
   		  ->join('cto_tfocs AS afc', 'afc.id', '=', 'cs.tfoc_id') 
        ->join('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id') 
          ->select('cs.id','afc.tfoc_short_name','asl.description','cs.cm_id','cs_is_active');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(asl.description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(afc.tfoc_short_name)'),'like',"%".strtolower($q)."%");
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
