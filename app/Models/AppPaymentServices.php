<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class AppPaymentServices extends Model
{
    public $table = 'cto_forms_miscellaneous_payments';
    
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_forms_miscellaneous_payments')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_forms_miscellaneous_payments')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_forms_miscellaneous_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_forms_miscellaneous_payments')->where('id',$id)->first();
    }
	
	public function Ctotfocs(){
        return DB::table('cto_tfocs As ctot')
		->join('acctg_account_general_ledgers as aagl', 'ctot.gl_account_id','aagl.id')
		->join('acctg_account_subsidiary_ledgers as aasl', 'ctot.sl_id','aasl.id')
		->select('ctot.id As id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')
		->where('aagl.is_active',1)
		->where('aasl.is_parent',0)
		->where('aasl.is_hidden',0)
		->where('aasl.is_active',1)
		->get();
    }
	
	public function getAccountDesc($id){
		return DB::table('cto_tfocs')->select('tfoc_is_applicable')->where('id','=',$id)->first()->tfoc_is_applicable;
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
		  1 =>"fpayment_app_name",
		  2 =>"fpayment_module_name", 
	      3 =>"tfoc_id",
	      4 =>"fpayment_remarks",

        );

        $sql = DB::table('cto_forms_miscellaneous_payments As cfmp')
              ->select('cfmp.*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(fpayment_remarks)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(fpayment_app_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(fpayment_module_name)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('fpayment_app_name','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
