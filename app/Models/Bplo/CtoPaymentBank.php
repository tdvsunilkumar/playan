<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymentBank extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_banks')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_banks')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_payment_banks')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_payment_banks')->where('id',$id)->first();
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
          1 =>"bank_code",
          2 =>"bank_branch_code",
          3 =>"bank_desc",
          4 =>"bank_address",
          5 =>"bank_is_active"
           
        );

        $sql = DB::table('cto_payment_banks')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bank_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bank_branch_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bank_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bank_address)'),'like',"%".strtolower($q)."%"); 
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
