<?php

namespace App\Models\Bplo;
use App\Models\RptPropertyOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class OutstandingPayment extends Model
{
    public $table = '';
    public function getEditDetails($id){
        return DB::table('bplo_business AS bb')
            ->Join('clients AS cl', 'bb.client_id', '=', 'cl.id')
            ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->select('pm_id','cpm.pm_desc','bb.application_date','busn_office_main_barangay_id','bb.id AS busn_id','busn_tax_year','busn_name','busns_id_no','app_code','suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name','p_email_address')
            ->where('bb.id',(int)$id)->first();
    }
    public function getBarangayList(){
        return DB::table('barangays AS b')
            ->Join('rpt_locality AS l', 'l.mun_no', '=', 'b.mun_no')
            ->select('b.brgy_name','b.id')
            ->where('department',2)->orderBy('brgy_name', 'ASC')->get()->toArray();
    }
    public function updateData($id,$columns){
        return DB::table('bplo_business_delinquents')->where('id',$id)->update($columns);
    }
    public function getPaymentMode(){ 
        return DB::table('cto_payment_mode')->select('id','pm_desc')->where('pm_status',1)->orderBy('pm_desc', 'ASC')->get()->toArray();
    }
    public function getFinalAssessementDetails($bus_id,$year,$app_code=0,$pm_id=0,$period=0){
        $sql = DB::table('cto_bplo_final_assessment_details')->where('payment_status',0)->where('assess_year',(int)$year)->whereIn('app_code',[1,2])->where('busn_id',(int)$bus_id);
        if($pm_id>0){
            $sql->where('payment_mode',$pm_id);
        }
        if($period>0){
            $sql->where('assessment_period','<=',$period);
        }
        return $sql->get()->toArray();
    }
    public function getTaxAssessementDetails($bus_id,$year,$pm_id,$assessment_period,$app_code=0){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.*','sl.description')
            ->where('assess_year', '=',(int)$year)
            ->where('assessment_period',(int)$assessment_period)
            ->where('payment_mode',(int)$pm_id)
            ->whereIn('app_code',[1,2])
            ->where('busn_id',(int)$bus_id)->orderBy('id','ASC')->get()->toArray();
    }
    public function checkExistEmailDlts($busn_id,$pm_id,$app_code){
        $year = date("Y");
        return DB::table('bplo_business_outstanding_email_response')->select('id')
            ->where('busn_id',(int)$busn_id)
            ->where('app_code',(int)$app_code)
            ->where('year',(int)$year)
            ->where('pm_id',(int)$pm_id)->first();
    }
    public function upateEmailResponse($id,$columns){
        return DB::table('bplo_business_outstanding_email_response')->where('id',$id)->update($columns);
    }
    public function addEmailResponse($postdata){
        DB::table('bplo_business_outstanding_email_response')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function checkEmailResponse($busn_id,$app_code){
        $year = date("Y");
        return DB::table('bplo_business_outstanding_email_response')->select('acknowledged_date','is_read')
            ->where('busn_id',(int)$busn_id)
            ->where('app_code',(int)$app_code)
            ->where('year',(int)$year)->first();
    }
    public function getList($request){
        $params = $columns=array();
        $params = $_REQUEST;
        $search =$request->input('q');
        $pm_id =$request->input('pm_id');
        $period =$request->input('period');
        $barngay_id=(int)$request->input('barngay_id');
        $year = date('Y');
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
            4 =>"brgy_name",
            5 =>"busn_name",
            6 =>"bb.app_code",
            7 =>"sub_amount",
            8 =>"surcharge_fee",
            9 =>"interest_fee",
            10 =>"total_amount"
        );

        $orderBy ='id DESC';
        if(isset($params['order'][0]['column'])){
            $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
        }
        $out="p_total_count";
        $arr = DB::select("CALL BPLO_OUTSTANDING_PAYMENTS('$year','$pm_id','$period','$barngay_id','$start',$length,'$search','$orderBy',@$out)");
        $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
        return array("data_cnt"=>$data_cnt,"data"=>$arr);
    }
}
