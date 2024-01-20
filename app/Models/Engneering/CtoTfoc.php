<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoTfoc extends Model
{
    protected $table = 'cto_tfocs';

     public function getFundCodes(){
    	return DB::table('acctg_fund_codes')->select('id','code','description')->where('is_active',1)->get();
    }
    public function getChargesType(){
        return DB::table('cto_charge_types')->select('id','ctype_desc')->where('ctype_is_active',1)->get();
    }
    public function getTypeofchargesAjax($search=""){
         $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cto_charge_types')->select('id','ctype_desc');
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(ctype_desc)'),'like',"%".strtolower($search)."%");;
          }
        });
      }
      $sql->orderBy('cto_charge_types.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getChargesbyid($id){
       return DB::table('cto_charge_types')->select('id','is_essential')->where('id',$id)->first();
    }
    public function GetDepartmrntsArray(){
      return DB::table('cto_payment_cashier_system')->select('id','pcs_name')->get();
    }
    public function getAccountGeneralLeader(){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')->where('aagl.is_active',1)->where('aasl.is_parent',0)->where('aasl.is_hidden',0)->where('aasl.is_active',1)->get();
    }

     public function getAccountGeneralLeaderedit($id){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')->where('aagl.is_active',1)->where('aasl.is_parent',0)->where('aasl.is_hidden',0)->where('aasl.is_active',1)->where('aasl.id',$id)->get();
    }

    public function getChartofaccountAjax($search=""){
       $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('acctg_account_subsidiary_ledgers as aasl')->leftjoin('acctg_account_general_ledgers as aagl','aasl.gl_account_id', '=', 'aagl.id')->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')->where('aagl.is_active',1)->where('aasl.is_parent',0)->where('aasl.is_hidden',0)->where('aasl.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('aasl.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(aagl.description)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(aagl.code)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(aasl.prefix)'),'like',"%".strtolower($search)."%")
            ->orWhere(DB::raw('LOWER(aasl.description)'),'like',"%".strtolower($search)."%");
          }
        });
      }
      $sql->orderBy('aasl.id','DESC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function updateData($id,$columns){
        return DB::table('cto_tfocs')->where('id',$id)->update($columns);
    }
    public function getSubsidiaryLeader(){
        return DB::table('acctg_account_subsidiary_ledgers')->select('id','code','description')->where('is_active',1)->get();
    }
    public function getOccupancytype(){
        return DB::table('eng_bldg_occupancy_types')->select('id','ebot_description')->where('ebot_is_active',1)->get();
    }
     public function getOccupancySubtype(){
        return DB::table('eng_bldg_occupancy_sub_types')->select('id','ebost_description')->where('ebost_is_active',1)->get();
    }
    public function addData($postdata){
        DB::table('cto_tfocs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('cto_tfocs')->where('id',$id)->update($columns);
    } 

    public function GetGlid($id){
     return DB::table('acctg_account_subsidiary_ledgers')->select('gl_account_id')->where('is_active',1)->where('id','=',$id)->get()->first();
    }

    public function addDataOthertaxes($postdata){
        DB::table('cto_tfoc_other_taxes')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateDataOthertaxes($id,$columns){
        return DB::table('cto_tfoc_other_taxes')->where('id',$id)->update($columns);
    }

    public function deleteOthertaxesrow($id){
        return DB::table('cto_tfoc_other_taxes')->where('id',$id)->delete();
    }

    public function GetOthercharges($tfocid){
      return  DB::table('cto_tfoc_other_taxes')->where('tfoc_id',$tfocid)->get()->toArray();
    }

    public function getAccountDesc($id)
	  {
		return DB::table('acctg_account_subsidiary_ledgers')->select('id','gl_account_id','prefix','description')->where('is_active',1)->where('id','=',$id)->get();
	  } 
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $department = $request->input('department');
    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cct.id",
      1 =>"afc.description",
      2 =>"cct.ctype_desc",
      3 =>"aal.description",
      4 =>"accdesc",	
      5 =>"tfoc_amount",
      6 =>'applicable',
      7 =>"tfoc_status"
    );

    $sql = DB::table('cto_tfocs AS ctot')
   		  ->join('acctg_fund_codes AS afc', 'afc.id', '=', 'ctot.fund_id') 
          ->leftjoin('cto_charge_types AS cct', 'cct.id', '=', 'ctot.ctype_id')
          ->leftjoin('acctg_account_general_ledgers AS aal', 'aal.id', '=', 'ctot.gl_account_id')
          ->leftjoin('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
          ->select('ctot.id','afc.description as fundcode','cct.ctype_desc as chargetype','aal.description as chartofaccount','aal.code','aas.code','aas.description as accdesc','ctot.tfoc_short_name','tfoc_amount','tfoc_is_applicable','tfoc_usage_business_permit','tfoc_usage_engineering','tfoc_status','tfoc_usage_real_property','is_business_tax_non_essential','is_business_tax_essential',
            DB::raw("CASE 
        WHEN ctot.tfoc_is_applicable = 1 THEN 'Business Permit' 
        WHEN ctot.tfoc_is_applicable = 2 THEN 'Real Property'
        WHEN ctot.tfoc_is_applicable = 3 THEN 'Engineering'
        WHEN ctot.tfoc_is_applicable = 4 THEN 'Occupancy'
        WHEN ctot.tfoc_is_applicable = 5 THEN 'Planning and Development'
        WHEN ctot.tfoc_is_applicable = 6 THEN 'Health & Safety'
        WHEN ctot.tfoc_is_applicable = 7 THEN 'Community Tax'
        WHEN ctot.tfoc_is_applicable = 8 THEN 'Burial Permit'
        WHEN ctot.tfoc_is_applicable = 9 THEN 'Miscellaneous'
        END as applicable"));
          
     if($department > 0){
       $sql->where('tfoc_is_applicable','=',$department);     
     }        
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()-ctotreatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(afc.description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cct.ctype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(aal.description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(aas.description)'),'like',"%".strtolower($q)."%")
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
