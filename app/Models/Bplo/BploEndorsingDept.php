<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploEndorsingDept extends Model
{
		public function updateActiveInactive($id,$columns){
            return DB::table('bplo_endorsing_dept')->where('id',$id)->update($columns);
        }  
        public function updateData($id,$columns){
           return DB::table('bplo_endorsing_dept')->where('id',$id)->update($columns);
        }
        public function addData($postdata){
           DB::table('bplo_endorsing_dept')->insert($postdata);
           return DB::getPdo()->lastInsertId();
        }
        public function getSection($id)
        {
            return DB::table('psic_sections')->select('id','section_code','section_description')->where('id',(int)$id)->get()->toArray();
        }
        public function requirementcode()
        {
            return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
        }
        public function apptypes()
        {
            return DB::table('bplo_application_type')->select('id','app_type')->get();
        }
        public function getCtoTfoc(){
            return DB::table('cto_tfocs AS ct')
                ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
                ->join('acctg_account_general_ledgers as aagl', 'sl.gl_account_id', '=', 'aagl.id')
                ->select('ct.id','aagl.code','aagl.description as gldescription','sl.prefix','sl.description')->where('tfoc_status',1)->where('tfoc_usage_business_permit',1)->orderBy('sl.description', 'ASC')->get()->toArray();
        }

        public function getCtoTfocById($id){
          return DB::table('cto_tfocs AS ct')
              ->join('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
              ->join('acctg_account_general_ledgers as aagl', 'sl.gl_account_id', '=', 'aagl.id')
              ->select('ct.id','aagl.code','aagl.description as gldescription','sl.prefix','sl.description')->where('ct.id',$id)->where('tfoc_status',1)->where('tfoc_usage_business_permit',1)->orderBy('sl.description', 'ASC')->first();
       }
       

       public function getEditDetails($id){
           return DB::table('bplo_endorsing_dept')->where('id',$id)->first();
       }
   
		public function getactivelist(){
           return DB::table('bplo_endorsing_dept')->select('*')->where('edept_status',1)->get();
		}
        public function getBploEndorsingDept($id){
            return DB::table('bplo_endorsing_dept')->select('requirement_json')->where('id',$id)->first();
        }

       public function getList($request)
       {
           $params = $columns = $totalRecords = $data = array();
           $params = $_REQUEST;
           $q=$request->input('q');
           if(!isset($params['start']) && !isset($params['length'])){
             $params['start']="0";
             $params['length']="10";
           }
           $columns = array( 
             1 =>"edept_name",
             2 =>"description",
             3 =>"edept_status",   
           );
           $sql = DB::table('bplo_endorsing_dept')
                  ->leftJoin('cto_tfocs AS ct', 'bplo_endorsing_dept.tfoc_id', '=', 'ct.id')
                  ->leftJoin('acctg_account_subsidiary_ledgers AS sl', 'ct.sl_id', '=', 'sl.id')
                  ->leftJoin('acctg_account_general_ledgers as aagl', 'sl.gl_account_id', '=', 'aagl.id')
                  ->select('bplo_endorsing_dept.*','aagl.code','aagl.description as gldescription','sl.prefix','sl.description');
           if(!empty($q) && isset($q)){
               $sql->where(function ($sql) use($q) {
                   $sql->where(DB::raw('LOWER(bplo_endorsing_dept.edept_name)'),'like',"%".strtolower($q)."%")
                       ->orWhere(DB::raw('LOWER(aagl.code)'),'like',"%".strtolower($q)."%")
                       ->orWhere(DB::raw('LOWER(aagl.description)'),'like',"%".strtolower($q)."%")
                       ->orWhere(DB::raw('LOWER(aagl.prefix)'),'like',"%".strtolower($q)."%")
                       ->orWhere(DB::raw('LOWER(sl.description)'),'like',"%".strtolower($q)."%");

               });
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
