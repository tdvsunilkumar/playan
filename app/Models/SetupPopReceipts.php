<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class SetupPopReceipts extends Model
{
 
     public function updatePrint($id,$columns){
        return DB::table('setup_pop_receipts')->where('id',$id)->update($columns);
    }  
 
    public function updateData($id,$columns){
        return DB::table('setup_pop_receipts')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('setup_pop_receipts')->insert($postdata);
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
          1 =>"stp_type",  
          2 =>"stp_accountable_form",
          3 =>"serial_no_from",
          4 =>"serial_no_to",
          5 =>"stp_qty",
          6 =>"stp_value",
          7 =>"stp_print",
          8 =>"is_active"
         );
         $sql = DB::table('setup_pop_receipts AS bgf')->select('id','stp_type','stp_accountable_form','serial_no_from','serial_no_to','stp_qty','stp_value','stp_print','is_active');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
       $sql->where('bgf.created_by', '=', \Auth::user()->creatorId());

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(stp_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(stp_accountable_form)'),'like',"%".strtolower($q)."%");
                    
                   
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
