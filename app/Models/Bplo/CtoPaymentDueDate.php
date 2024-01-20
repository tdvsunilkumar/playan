<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymentDueDate extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_due_dates')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_due_dates')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_payment_due_dates')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getApplicationType(){
        return DB::table('pbloapplicationtypes')->select('id','app_type')->where('is_active',1)->where('app_type','!=',"Retire")->orderBy('app_type', 'ASC')->get()->toArray();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_payment_due_dates')->where('id',$id)->first();
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
          1 =>"app_type",
          2 =>"due_1st_payment",
          3 =>"due_semi_annual_2nd_sem",
          4 =>"due_quarterly_2nd",
          5 =>"due_quarterly_3rd",
          6 =>"due_quarterly_4th",
          7 =>"due_is_active"
           
        );

        $sql = DB::table('cto_payment_due_dates AS dd')
               ->join('pbloapplicationtypes AS at', 'dd.app_type_id', '=', 'at.id')
               ->select('dd.*','at.app_type');

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_1st_payment)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_semi_annual_2nd_sem)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_quarterly_2nd)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_quarterly_3rd)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_quarterly_4th)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(due_quarterly_2nd)'),'like',"%".strtolower($q)."%"); 
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

