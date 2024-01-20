<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LocationClearance extends Model
{
    public function updateData($id,$columns){
        return DB::table('location_clearances')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('location_clearances')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getCpdoOwners(){
        return DB::table('clients as c')->join('cpdo_application_forms as caf','c.id','=','caf.client_id')->select('c.id','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix')->where('c.rpo_first_name','<>',NULL)->where('c.is_engg','=','1')->get();
    }
    
    public function GetServicename($id){
      return DB::table('cto_tfocs AS ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('aas.description as chartofaccount')->where('ctot.id',$id)->get();
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
      1 =>"c.rpo_custom_last_name",
      2 =>"lc.completeaddress",
      3 =>"is_active"	
    );

    $sql = DB::table('location_clearances AS lc')
   		  ->join('clients AS c', 'c.id', '=', 'lc.client_id') 
          ->select('lc.id','lc.completeaddress','lc.is_active','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(lc.completeaddress)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%");
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
