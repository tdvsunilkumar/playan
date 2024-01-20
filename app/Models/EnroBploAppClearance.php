<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class EnroBploAppClearance extends Model
{
    public function updateData($id,$columns){
        return DB::table('enro_bplo_app_clearances')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('enro_bplo_app_clearances')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getBfpApplications(){
    	 return DB::table('bfp_application_forms')->select('id','bff_application_no')->where('bff_status',1)->get();
    }
    public function getAppClearance($id){
      return DB::table('enro_bplo_app_clearances as ec')->join('profiles AS p', 'p.id', '=', 'ec.p_code')->select('ec.id','p.p_complete_name_v1','ebac_issuance_date','p.ba_business_name','p.brgy_name')->where('ec.id',(int)$id)->first();
    }
    public function getAppClearancerow($id){
        return DB::table('enro_bplo_app_clearances')->where('id',$id)->first();
    }
    
    public function getbploApplications(){
       return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }

    public function addenroInspectionReportData($postdata){
        return DB::table('enro_bplo_inspection_report')->insert($postdata);
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
          0 =>"p_complete_name_v1",  
          1 =>"ba_business_account_no",
          2 =>"ebac_app_code",
          3 =>"bio_inspection_code",
          4 =>"ebac_app_year",
          5 =>"ebac_app_no",
          6 =>"ebac_transaction_no",
          7 =>"ebac_environmental_fee",  
          8 =>"ebac_is_paid", 
          9 =>"ebac_issuance_date"
         );
         $sql = DB::table('enro_bplo_app_clearances AS ec')
            ->join('bplo_applications AS ba', 'ba.id', '=', 'ec.ba_code')
            ->join('profiles AS p', 'p.id', '=', 'ec.p_code')
            ->select('ec.id','p.p_complete_name_v1','isreport','ba.ba_business_account_no','ebac_app_code','ebac_app_year','ebac_app_no','ebac_transaction_no','ebac_environmental_fee','ebac_is_paid','ebac_issuance_date','ebac_remarks');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(p_complete_name_v1)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ec.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
