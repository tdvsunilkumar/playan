<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class CtoPaymentOrAssignment extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_payment_or_assignments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function getRegisteredata($id){
        return DB::table('cto_payment_or_assignments')->select('cpor_id')->where('ortype_id',$id)->get()->toArray();
    }

    public function getOrregister($id,$ids){
        return DB::table('cto_payment_or_registers')->select('*')->where('cpot_id',$id)->where('cpor_status',1)->whereNotIn('id',$ids)->get();
    }
    public function getOrregisterbyid($id){
        return DB::table('cto_payment_or_registers')->select('*')->where('id',$id)->first();
    }
    public function getOrregisterdefualt(){
        return DB::table('cto_payment_or_registers')->select('*')->get();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->first();
    }
    public function checkOropenExist($id,$userid,$orid){
       $sql = DB::table('cto_payment_or_assignments')->select('id')->where('ortype_id',$id)->where('created_by',$userid)->where('ora_is_completed',0);
       if($id>0){
            $sql->where('id','<>',$orid);
        }
       return $sql->get()->toArray();
    }
    public function checkRangeExist($from,$to,$type_id,$id){
        $subsql = "";
        if($id>0){
            $subsql = " AND id!=".(int)$id;
        }
        return DB::select("SELECT count(*) AS total_cnt FROM cto_payment_or_assignments WHERE (".(int)$from." BETWEEN ora_from AND ora_to OR ".(int)$to." BETWEEN ora_from AND ora_to OR ora_from IN ( SELECT ora_from FROM cto_payment_or_assignments WHERE ora_from BETWEEN ".(int)$from." AND ".(int)$to.")) AND ortype_id=".(int)$type_id. $subsql);
    }
    public function getORType(){
        return DB::table('cto_payment_or_types')->select('id','ortype_name')->where('ora_status',1)->orderBy('ortype_name', 'ASC')->get()->toArray();
    }

    public function deleteFromAssignment($id){
      return DB::table('cto_payment_or_assignments')->where('id',$id)->delete();
    }

    public function checkidExist($id){
      return DB::table('cto_cashier')->select('id')->where('or_assignment_id',$id)->get();
    }
    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $status=$request->input('status');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $accountref = $request->input('accountref');

        $columns = array( 
		      1 =>"ort.ortype_name",
          2 =>"cpr.coa_no",
          3 =>"a.ora_from",
          4 =>"a.ora_to",
          5 =>"a.or_count",
          6 =>"a.ora_is_completed",
          7 =>"a.ora_completed_date",
          8 =>"a.ora_date_returned",
		      9 =>"a.ora_remarks",
          10 =>"a.ora_is_active"
           
        );

        $sql = DB::table('cto_payment_or_assignments AS a')
              ->join('cto_payment_or_registers as cpr','cpr.id','=','a.cpor_id')->join('hr_employees as he','he.user_id','=','a.created_by')->join('cto_payment_or_types AS ort', 'a.ortype_id', '=', 'ort.id') 
              ->select('a.*','ort.ortype_name','ort.or_short_name','cpr.coa_no','he.fullname');

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(ort.ortype_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cpr.coa_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(a.ora_from)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(a.ora_to)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(a.or_count)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(a.ora_is_completed)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(a.ora_completed_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(a.ora_date_returned)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(a.ora_remarks)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(a.ora_is_active)'),'like',"%".strtolower($q)."%")
					; 
            });
        }
        $sql->where('ora_is_completed',(int)$status);
        if(!empty($accountref)){ 
          $sql->where('a.created_by',\Auth::user()->id);
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('a.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
