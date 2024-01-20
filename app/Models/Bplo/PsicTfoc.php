<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PsicTfoc extends Model
{
    public function updateActiveInactive($id,$columns){
        return DB::table('psic_tfocs')->where('id',$id)->update($columns);
    } 
    public function updateData($id,$columns){
        return DB::table('psic_tfocs')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('psic_tfocs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('psic_tfocs')->where('id',$id)->first();
    }
    public function getType(){
        return DB::table('pbloapplicationtypes')->select('id','app_type')->where('is_active',1)->orderBy('app_type', 'ASC')->get()->toArray();
    }
    public function getChargesTypes(){
        return DB::table('cto_charge_types')->select('id','ctype_desc')->where('ctype_is_active',1)->orderBy('ctype_desc', 'ASC')->get()->toArray();
    }
    public function getTFOCTypeCharges($tfoc_id=0){
        return DB::table('cto_tfocs AS ct')
            ->join('cto_charge_types as cct', 'ct.ctype_id', '=', 'cct.id')
            ->select('cct.id','cct.ctype_desc')->where('ct.id',(int)$tfoc_id)->orderBy('cct.ctype_desc', 'ASC')->get()->toArray();
    }
    public function getBasis($app_code=0){
        /*return DB::table('cto_tfoc_computation_bases AS cb')
            ->join('cto_charge_descriptions AS ccd', function($join){
                $join->on(\DB::raw("FIND_IN_SET(ccd.id, cb.basis_ids)"),">",\DB::raw("'0'"));
            })
            ->select('ccd.id','ccd.charge_desc')->where('ccd.is_active',1)->where('cb.tfoc_id',(int)$tfoc_id)->orderBy('ccd.charge_desc', 'ASC')->get()->toArray();*/
        $sql=DB::table('cto_tfoc_basis')->select('id','basis_name')->where('basis_ref_table','!=',"''")->where('basis_ref_field','!=',"''")->where('basis_status',1)->where('basis_status',1)->orderBy('basis_name', 'ASC');
        if($app_code==3){
            $sql->where('basis_is_retire',1);
        }
        return $sql->get()->toArray();
    }
    public function getCharges($field=''){
        $sql = DB::table('cto_charge_descriptions')->select('id','charge_desc')->where('is_active',1);
        if(!empty($field)){
            $sql->where($field,1);
        }
        return $sql->orderBy('charge_desc', 'ASC')->get()->toArray();
    }

    public function getTypeComputation(){
        return DB::table('cto_computation_types')->select('id','cctype_desc')->where('cctype_is_active',1)->orderBy('cctype_desc', 'ASC')->get()->toArray();
    }
    public function getTypeComputationList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cto_computation_types')
            ->select('id','cctype_desc')->where('cctype_is_active',1);;
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(cctype_desc)'),'like',"%".strtolower($search)."%");
          }
        });
      
      $sql->orderBy('cctype_desc','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getChargesList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cto_charge_descriptions')
            ->select('id','charge_desc')->where('is_active',1);;
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('id',$search);
          }else{
            $sql->where(DB::raw('LOWER(charge_desc)'),'like',"%".strtolower($search)."%");
          }
        });
      
      $sql->orderBy('charge_desc','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getTFOCDtlsList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('cto_tfocs AS ct')
            ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
            ->join('acctg_account_general_ledgers as aagl', 'sl.gl_account_id', '=', 'aagl.id')
            ->select('ct.id','aagl.code','aagl.description as gldescription','sl.prefix','sl.description')->where('tfoc_status',1)->where('tfoc_usage_business_permit',1)->where('tfoc_is_applicable',1);
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('ct.id',$search);
          }else{
            $sql->where(DB::raw('LOWER(aagl.code)'),'like',"%".strtolower($search)."%")
                 ->orWhere(DB::raw("CONCAT('[', aagl.code, ' - ', aagl.description, ']=>[', sl.prefix, ' - ', sl.description, ']')"), 'like', "%" . $search . "%");
          }
        });
      
      $sql->orderBy('sl.description','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getTFOCDtls(){
        return DB::table('cto_tfocs AS ct')
            ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
            ->join('acctg_account_general_ledgers as aagl', 'sl.gl_account_id', '=', 'aagl.id')
            ->select('ct.id','aagl.code','aagl.description as gldescription','sl.prefix','sl.description')->where('tfoc_status',1)->where('tfoc_usage_business_permit',1)->where('tfoc_is_applicable',1)->orderBy('sl.description', 'ASC')->get()->toArray();
    }
    public function getSubsidiaryList($id){
        return DB::table('cto_tfocs AS ct')
            ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
            ->select('sl.id','sl.description','ct.gl_account_id')->where('tfoc_status',1)->where('ct.id',(int)$id)->orderBy('sl.description', 'ASC')->get()->toArray();
    }

    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $sid=(int)$request->input('sid'); 
        $type=(int)$request->input('type'); 

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"sl.description",
          2 =>"app_type",
          3 =>"ctype_desc",
          4 =>"ptfoc_is_active"
           
        );

        $sql = DB::table('psic_tfocs AS tf')
            ->Join('cto_tfocs AS ct', 'tf.tfoc_id', '=', 'ct.id')
            ->Leftjoin('acctg_account_subsidiary_ledgers AS sl', 'tf.ptfoc_sl_id', '=', 'sl.id')
            ->Leftjoin('pbloapplicationtypes AS at', 'tf.app_code', '=', 'at.id')
            ->Leftjoin('cto_charge_types AS cct', 'tf.ctype_id', '=', 'cct.id')
            ->select('tf.id','sl.description','tf.ptfoc_is_active','at.app_type','cct.ctype_desc','tfoc_is_applicable');

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(sl.description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(at.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cct.ctype_desc)'),'like',"%".strtolower($q)."%"); ;
            });
        }
        $sql->where('ptfoc_access_type',$type);
        if($type==1){
            $sql->where('section_id',$sid);
        }else{
            $sql->where('subclass_id',$sid);
        }
        
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
