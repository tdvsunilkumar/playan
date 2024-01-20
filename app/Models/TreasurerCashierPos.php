<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class TreasurerCashierPos extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('treasurer_cashier_pos')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('treasurer_cashier_pos')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updatePaymentdetialData($id,$columns){
        return DB::table('licence_payment_detail')->where('id',$id)->update($columns);
    }
    public function addPaymentdetialData($postdata){
        DB::table('licence_payment_detail')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getPaymentdetialData($id){
       return DB::table('licence_payment_detail')->select('*')->where('tcp_id',$id)->orderBy('id','ASC')->get()->toArray();
    }

    public function getaccountnumbers(){
    	 return DB::table('bplo_assessments')->select('id','ba_business_account_no')->where('is_active',1)->get();
    }
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('crdate');
    $year = $request->input('year');

    if(!isset($params['start']) && !isset($params['length'])){
        $params['start']="0";
        $params['length']="10";
    }

    $columns = array( 
        0 =>"ba_business_account_no",
        1 =>"ba.ba_business_name",
        2 =>"tcp.order_number",
        3 =>"tcp.totalamt_due",
        4 =>"pa.is_active"     
    );

    $sql = DB::table('treasurer_cashier_pos AS tcp')
        ->join('bplo_assessments AS pa', 'pa.id', '=', 'tcp.bas_id')
        ->join('bplo_applications AS ba', 'ba.id', '=', 'pa.application_id')
        ->select('tcp.id','pa.ba_business_account_no','ba.ba_business_name','pa.is_active','order_number','totalamt_due');

    //$sql->where('pa.created_by', '=', \Auth::user()->creatorId());
    if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(ba_business_account_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pa.ba_business_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(order_number)'),'like',"%".strtolower($q)."%");
        });
    } 
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
        $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
        $sql->orderBy('pa.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getApplicationAssessments($id){
        return DB::table('cto_application_assessments')
            ->select('permit_amount','garbage_amount','sanitary_amount','mayrol_permit_code','sanitary_code','garbage_code')
            ->where('bplo_assessment_id','=',$id)->get();
    }
    public function getmasterpenaltyrates(){
        return DB::table('bplo_assess_penalty_rates')->select('*')->where('id','=','1')->get()->first();
    }
    public function getORnumber(){
        return DB::table('setup_pop_receipts')->select('serial_no_from')->where('id','=','1')->get()->first();
    }
    public function getAllFeeMaster(){
        return DB::table('allfeemaster')
            ->select('id','fee_name')
            ->where('is_active',1)->get();
    }
    public function getAssesmentData($id){
     return DB::table('bplo_assessments AS pas')
            ->join('bplo_applications AS pa', 'pas.application_id', '=', 'pa.id')
            ->join('profiles AS p', 'p.id', '=', 'pas.profile_id')
            ->select('pas.id','pas.application_id','pas.ba_business_account_no','p_first_name','p_middle_name','p_family_name','pa.ba_business_name','ba_date_started','pa.ba_cover_year')
            ->where('pas.id','=',$id)->get();
    }
     public function getPermitLicencedata($id){
        return DB::table('treasurer_cashier_pos')->select('order_number','totalamt_due','surcharge','interest','bas_id')->where('id','=',$id)->get()->first();
         
    }
}
