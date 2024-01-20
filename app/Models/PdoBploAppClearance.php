<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PdoBploAppClearance extends Model
{
     public function updateData($id,$columns){
        return DB::table('pdo_bplo_app_clearances')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('pdo_bplo_app_clearances')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getbploApplications(){
       return DB::table('bplo_applications')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }

    public function getBploApplictaions($id){
        return DB::table('bplo_applications')->select('id','ba_business_account_no','profile_id','barangay_id')->where('id','=',$id)->first();
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
          2 =>"pbac_app_code",
          3 =>"pbac_app_year",
          4 =>"pbac_app_no",
          5 =>"pbac_transaction_no",
          6 =>"pbac_zoning_clearance_fee",  
          7 =>"pbac_is_paid", 
          8 =>"pbac_issuance_date",
          9 =>"pbac_officer_position",
          10 =>"pbac_approver_position",
          11 =>"pbac_remarks"
         );
         $sql = DB::table('pdo_bplo_app_clearances AS pc')
            ->join('bplo_applications AS ba', 'ba.id', '=', 'pc.ba_code')
            ->join('profiles AS p', 'p.id', '=', 'pc.p_code')
            ->select('pc.id','p.p_complete_name_v1','ba.ba_business_account_no','pbac_app_code','pbac_app_year','pbac_app_no','pbac_transaction_no','pbac_zoning_clearance_fee','pbac_is_paid','pbac_issuance_date','pbac_officer_position','pbac_approver_position','pbac_remarks');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(p_complete_name_v1)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('pc.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
