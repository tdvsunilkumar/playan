<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymnetOrRegister extends Model
{
    use HasFactory;

    public function updateActiveInactive($id,$columns){
	    return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
	  }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('cto_payment_or_registers')->insert($postdata);
    }
    public function getRecordforedit($id){
      return DB::table('cto_payment_or_registers')->where('id',$id)->first();
    }
    public function getoRtypes(){
       return DB::table('cto_payment_or_types')->select('*')->get();
    }
    public function editBuildingRoofing($id){
        return DB::table('cto_payment_or_registers')->where('id',$id)->first();
    }
    public function getShortname($id){
      return DB::table('cto_payment_or_types')->select('or_short_name')->where('id',$id)->first();
    }
    public function checkidExist($id){
      return DB::table('cto_payment_or_assignments')->select('id')->where('cpor_id',$id)->get();
    }

    public function getEditDetails($id){
      return DB::table('cto_payment_or_registers')->where('id',$id)->first();
    }

    public function checktoExist($ora_from,$ora_to,$cpot_id,$id){

      $sql = DB::table('cto_payment_or_registers')
        ->where('cpot_id','=',$cpot_id)
        ->where(function ($query) use ($ora_from, $ora_to) {
          $query->where(function ($query) use ($ora_from, $ora_to) {
              $query->where('ora_from', '<=', $ora_from)
                  ->where('ora_to', '>=', $ora_from);
          })->orWhere(function ($query) use ($ora_from, $ora_to) {
              $query->where('ora_from', '<=', $ora_to)
                  ->where('ora_to', '>=', $ora_to);
          })->orWhere(function ($query) use ($ora_from, $ora_to) {
              $query->where('ora_from', '>=', $ora_from)
                  ->where('ora_to', '<=', $ora_to);
          });
      });
      if($id > 0) {
        $data = $sql->where('id','<>', $id)->count();
      } else {
        $data = $sql->count();
      }
      return $data;

      // $sql =  DB::table('cto_payment_or_registers')->select('ora_from','ora_to')->where('cpot_id','=',$cpot_id);
      // if($id > 0){
      //  $data = $sql->where('id','<>',$id)->get();
      // }else{
      //  $data = $sql->get();
      // }
      // return $data;
    }

    public function checkFromExist($ora_from,$ora_to,$cpot_id,$id){
      return DB::table('cto_payment_or_registers')->where('ora_from','>=',$ora_from)->where('cpot_id','=',$cpot_id)->where('id','<>',$id)->get();
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $status=$request->input('status');
        $type =$request->input('type');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"cot.ortype_name",
          2 =>"cpor_status"
        );

        $sql = DB::table('cto_payment_or_registers as cor')->leftjoin('cto_payment_or_types as cot','cot.id','=','cor.cpot_id')->leftjoin('hr_employees as he','he.user_id','=','cor.created_by') 
              ->select('he.fullname','cot.ortype_name','cot.or_short_name','cor.id','cor.cpor_series','cor.cpor_status','cor.ora_from','cor.ora_to','cor.or_count','cor.coa_no','cor.ora_document','cor.created_at');
        if ($type == '') {
            } else {
                   $sql->where('cot.ortype_name', '=',$type);
        }    
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cot.ortype_name)'),'like',"%".strtolower($q)."%"); 
            });
        }
        if ($status == '3') {
            } else {
                   $sql->where('cor.cpor_status', '=', (int)$status);
        }    
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cot.ortype_name)'),'like',"%".strtolower($q)."%"); 
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cor.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
