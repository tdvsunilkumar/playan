<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ReportColumnHeader extends Model
{
    public function getServices($pcsid){
    	return DB::table('cto_tfocs as ctot')->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','aal.code','aal.description as gldescription','aas.prefix','aas.description')->where('tfoc_status',1)->where('tfoc_is_applicable',$pcsid)->get();
    }
    public function getdepartments(){
        return DB::table('cto_payment_cashier_system')->select('id','pcs_name')->get();
    }

    public function updateData($id,$columns){
        return DB::table('report_column_headers')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('report_column_headers')->insert($postdata);
        return DB::getPdo()->lastInsertId();
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
      2 =>"rch.rep_header_name",
      3 =>"aal.code",
      4 =>"rch.description",
      5 =>"rch.remark",
      6 =>"he.fullname"	
    );

    $sql = DB::table('report_column_headers AS rch')->leftjoin('hr_employees as he','he.user_id','=','rch.created_by')->join('cto_payment_cashier_system AS cpcs', 'cpcs.id', '=', 'rch.pcs_id')
   		  ->leftjoin('cto_tfocs AS afc', 'afc.id', '=', 'rch.tfoc_id')
   		  ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'afc.gl_account_id') 
          ->leftjoin('acctg_account_subsidiary_ledgers AS asl', 'asl.id', '=', 'afc.sl_id') 
          ->select('rch.id','cpcs.pcs_name','afc.tfoc_short_name','aal.code','aal.description as gldescription','asl.description','rch.rep_header_name','rch.description','rch.remark','he.fullname');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(asl.description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(rch.rep_header_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(rch.rep_header_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(afc.tfoc_short_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(aal.code)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(rch.description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(rch.remark)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(he.fullname)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('rch.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
