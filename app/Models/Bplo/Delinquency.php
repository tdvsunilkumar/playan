<?php

namespace App\Models\Bplo;
use App\Models\RptPropertyOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Delinquency extends Model
{
    public $table = '';
    public function getEditDetails($id){
        return DB::table('bplo_business_delinquents AS bdl')
            ->Join('bplo_business AS bb', 'bb.id', '=', 'bdl.busn_id')
            ->Join('clients AS cl', 'bb.client_id', '=', 'cl.id')
             ->Leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->select('bdl.id','pm_id','cpm.pm_desc','bb.application_date','busn_office_main_barangay_id','busn_id','busn_tax_year','busn_name','busns_id_no','bdl.app_code','suffix','full_name','rpo_first_name','rpo_middle_name','rpo_custom_last_name','bdl.year AS year','bdl.retire_id','p_email_address',DB::raw("(SELECT cashier_or_date FROM cto_cashier AS csh WHERE csh.busn_id=bb.id ORDER BY csh.id DESC LIMIT 1) AS last_paid_date"))
            ->where('bdl.id',(int)$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('bplo_business_delinquents')->where('id',$id)->update($columns);
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
            1 =>"busns_id_no",
            2 =>"full_name",
            3 =>"p_email_address",
            4 =>"busn_name",
            5 =>"bd.app_code",
            6 =>"sub_amount",
            7 =>"surcharge_fee",
            8 =>"interest_fee",
            9 =>"total_amount",
            10 =>"is_approved"
         
        );
        $sql = DB::table('bplo_business_delinquents AS bd')
            ->join('bplo_business AS bb', 'bb.id', '=', 'bd.busn_id') 
            ->join('clients AS c', 'c.id', '=', 'bb.client_id') 
            ->select('bd.*',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'full_name','suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name','busn_name','busns_id_no','p_email_address','is_approved','acknowledged_date',DB::raw("(SELECT cashier_or_date FROM cto_cashier AS csh WHERE csh.busn_id=bb.id ORDER BY csh.id DESC LIMIT 1) AS last_paid_date"))
            ->whereIn('bd.app_code',[1,2]);
            
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%") ->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%");
            });
        }
        
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bd.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
