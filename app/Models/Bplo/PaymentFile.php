<?php

namespace App\Models\Bplo;
use App\Models\RptPropertyOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentFile extends Model
{
    public $table = '';
    public function getEditDetails($id){
        return DB::table('bplo_business AS bb')
            ->Join('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->Leftjoin('barangays AS bg', 'bb.busn_office_barangay_id', '=', 'bg.id')
            ->select('pm_id','cpm.pm_desc','bb.application_date','busn_office_main_barangay_id','bb.id AS busn_id','busn_tax_year','busn_name','busns_id_no','app_code','suffix','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','p_email_address','brgy_name')
            ->where('bb.id',(int)$id)->first();
    }
    public function getBarangayList(){
        return DB::table('barangays AS b')
            ->Join('rpt_locality AS l', 'l.mun_no', '=', 'b.mun_no')
            ->select('b.brgy_name','b.id')
            ->where('department',2)->orderBy('brgy_name', 'ASC')->get()->toArray();
    }
    public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description')->where('psic.busn_id',(int)$id)->get()->toArray();
    }
    public function getAllTransactionDetials($busn_id){
        return DB::table('cto_cashier AS cc')
             ->Leftjoin('bplo_business_history AS bbh', function ($join) {
                $join->on('cc.busn_id', '=', 'bbh.busn_id');
                $join->on('bbh.busn_tax_year', '=', 'cc.cashier_year');
            })
            ->Leftjoin('clients AS c', 'bbh.client_id', '=', 'c.id')
            ->Leftjoin('barangays AS bg', 'bbh.busn_office_barangay_id', '=', 'bg.id')
            ->select('cc.id AS cashier_id','suffix','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','p_email_address','busn_name','busns_id_no','cc.app_code','brgy_name','cc.or_no','cc.cashier_or_date','cc.pm_id','cc.pap_id','cc.tax_credit_amount', 'cc.total_amount','cc.created_by','cc.top_transaction_id')->where('cc.busn_id',(int)$busn_id)->orderBy('cc.id', 'DESC')->get()->toArray();
    } 
    public function getUserName($id){
        return DB::table('users')->where('id',(int)$id)->pluck('name')->first();
    }
    public function getAssessedBy($id){
        return DB::table('cto_top_transactions AS tt')->join('users AS usr', 'usr.id', '=', 'tt.created_by')
        ->where('tt.id',(int)$id)->pluck('usr.name')->first();
    }
    public function getList($request){
        $params = $columns=array();
        $params = $_REQUEST;
        $search =$request->input('q');
        $busn_id =(int)$request->input('busn_id');
        $status =(int)$request->input('status');
        $barngay_id=(int)$request->input('barngay_id');
        if(!isset($params['start']) && !isset($params['length'])){
          $start="0";
          $length="10";
        }else{
            $start = $params['start'];
            $length = $params['length'];
        }
        $columns = array( 
            0 =>"id",
            1 =>"busns_id_no",
            2 =>"full_name",
            3 =>"busn_name",
            4 =>"brgy_name",
            5 =>"p_email_address",
            6 =>"bb.app_code",
            7 =>"or_no",
            8 =>"total_amount",
            9 =>"cashier_or_date"
        );

        $orderBy ='bb.id DESC';
        if(isset($params['order'][0]['column'])){
            $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
        }
        $out="p_total_count";
        $arr = DB::select("CALL BPLO_PAYMENT_FILE('$busn_id','$barngay_id','$status','$start',$length,'$search','$orderBy',@$out)");
        $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
        return array("data_cnt"=>$data_cnt,"data"=>$arr);
    }
}
