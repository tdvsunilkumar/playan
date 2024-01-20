<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DelinquencyOutstanding extends Model
{
    public $table = '';
    public function getEditDetails($id){
        return DB::table('bplo_business AS bb')
            ->Join('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->select('bb.id','pm_id','cpm.pm_desc','bb.application_date','busn_office_main_barangay_id','bb.id AS busn_id','busn_tax_year','busn_name','busns_id_no','app_code','full_name','p_email_address')
            ->where('bb.id',(int)$id)->first();
    }
    public function getBarangayList(){
        return DB::table('barangays AS b')
            ->Join('rpt_locality AS l', 'l.mun_no', '=', 'b.mun_no')
            ->select('b.brgy_name','b.id')
            ->where('department',2)->orderBy('brgy_name', 'ASC')->get()->toArray();
    }
    public function getList($request){
        $params = $columns=array();
        $params = $_REQUEST;
        $search =$request->input('q');
        $barngay_id=(int)$request->input('barngay_id');
        $busn_id=(int)$request->input('busn_id');
        $client_id=(int)$request->input('client_id');
        if(!isset($params['start']) && !isset($params['length'])){
          $start="0";
          $length="10";
        }else{
            $start = $params['start'];
            $length = $params['length'];
        }
        $columns = array( 
            1 =>"busns_id_no",
            2 =>"full_name",
            3 =>"p_email_address",
            4 =>"busn_name",
            5 =>"brgy_name",

            6 =>"or_no",
            7 =>"total_paid_amount",
            8 =>"cashier_or_date",

            9 =>"out_sub_amount",
            10 =>"out_surcharge_fee",
            11 =>"out_interest_fee",
            12 =>"out_total_amount",

            13 =>"del_sub_amount",
            14 =>"del_surcharge_fee",
            15 =>"del_interest_fee",
            16 =>"del_total_amount",

            17 =>"total_del_out_smt"

        );

        $orderBy ='id DESC';
        if(isset($params['order'][0]['column'])){
            $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
        }
        $out="p_total_count";
        $arr = DB::select("CALL BPLO_DLINQUENCY_OUTSTANDING_PAYMENTS($busn_id,$client_id,$barngay_id,$start,$length,'$search','$orderBy',@$out)");
        $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
        return array("data_cnt"=>$data_cnt,"data"=>$arr);
    }
}
