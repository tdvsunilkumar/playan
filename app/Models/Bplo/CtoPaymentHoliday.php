<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymentHoliday extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_holidays')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_holidays')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_payment_holidays')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_payment_holidays')->where('id',$id)->first();
    }
    public function getHolidayType(){
        return DB::table('cto_payment_holiday_types')->select('id','htype_desc')->where('htype_is_active',1)->orderBy('htype_desc', 'ASC')->get()->toArray();
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
          1 =>"htype_desc",
          2 =>"hol_desc",
          3 =>"hol_start_date",
          4 =>"hol_end_date",
          5 =>"hol_is_active"
           
        );

        $sql = DB::table('cto_payment_holidays AS h')
              ->join('cto_payment_holiday_types AS ht', 'h.htype_id', '=', 'ht.id')
              ->select('h.*','ht.htype_desc');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(hol_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(htype_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(hol_start_date)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(hol_end_date)'),'like',"%".strtolower($q)."%"); 
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
