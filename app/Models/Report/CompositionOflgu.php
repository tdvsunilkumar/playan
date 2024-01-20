<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CompositionOflgu extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getdetails($id){
    		return DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->select('cc.or_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cc.tcm_id','cc.tax_credit_gl_id','cc.tax_credit_sl_id','cc.tax_credit_amount')->where('cc.id',$id)->first();
    }
    public function getCharges($field=''){
        $sql = DB::table('cto_charge_descriptions')->select('id','charge_desc')->where('is_active',1);
        if(!empty($field)){
            $sql->where($field,1);
        }
        return $sql->orderBy('charge_desc', 'ASC')->get()->toArray();
    }

    public function GetSubclassesArray(){
      return DB::table('psic_subclasses')->select('id','subclass_description')->get();
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate = $request->input('todate');
    $subclass_id = $request->input('subclass');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>"aas.description",
      2 =>"cct.ctype_desc",
      3 =>"bat.app_type",
      4 =>"pt.ptfoc_effectivity_date"	
     
     
    );

    $sql = DB::table('psic_tfocs AS pt')
   		  ->join('cto_tfocs AS ctc', 'ctc.id', '=', 'pt.tfoc_id')
		  ->leftjoin('cto_charge_types as cct','cct.id','=','pt.ctype_id')
		  ->leftjoin('bplo_application_type as bat','bat.id','=','pt.app_code')
		  ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctc.sl_id')->leftjoin('cto_computation_types as ct','ct.id','=','pt.cctype_id')
          ->select('bat.app_type','pt.id','cct.ctype_desc','pt.ptfoc_effectivity_date','pt.ptfoc_json','pt.cctype_id','ct.cctype_desc','aas.description as accdesc','pt.ptfoc_constant_amount')->where('pt.ptfoc_is_active',1);
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
     if($subclass_id > 0){
     	 $sql->where('subclass_id','=',$subclass_id);     
     }else{
     	$sql->where('subclass_id','<','0');  
     }   
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(cct.ctype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%");
			});
	}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('pt.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
