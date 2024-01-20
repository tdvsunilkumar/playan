<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymentOrType extends Model
{
    public $table = 'cto_payment_or_types';
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_or_types')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_or_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_payment_or_types')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function PaymentCashierSystem(){
        return DB::table('cto_payment_cashier_system')->select('*')->get();
    }
    public function getEditDetails($id){
        return DB::table('cto_payment_or_types')->where('id',$id)->first();
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
          1 =>"ortype_name",
          2 =>"ora_remarks",
		  3 =>"or_is_applicable",
          4 =>"ora_is_active"
           
        );

        $sql = DB::table('cto_payment_or_types')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ortype_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ora_remarks)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(ccs.pcs_name)'),'like',"%".strtolower($q)."%");; 
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
