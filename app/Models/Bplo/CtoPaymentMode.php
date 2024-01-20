<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoPaymentMode extends Model
{

  protected $guarded = ['id'];

  public $table = 'cto_payment_mode';
  
  public $timestamps = false;

    public function updateActiveInactive($id,$columns){
        return DB::table('cto_payment_mode')->where('id',$id)->update($columns);
       }  
       public function updateData($id,$columns){
           return DB::table('cto_payment_mode')->where('id',$id)->update($columns);
       }
       public function addData($postdata){
           DB::table('cto_payment_mode')->insert($postdata);
           return DB::getPdo()->lastInsertId();
       }
       
       public function getEditDetails($id){
           return DB::table('cto_payment_mode')->where('id',$id)->first();
       }

       public function allPayMode($vars = ''){
            $pay_modes = self::where('pm_status',1)->orderBy('id')->get();
            $brgys = array();
            if (!empty($vars)) {
                $brgys[] = array('' => 'select a '.$vars);
            } else {
                $brgys[] = array('' => 'Please select...');
            }
            foreach ($pay_modes as $pay_mode) {
                $brgys[] = array(
                    $pay_mode->id => $pay_mode->pm_desc
                );
            }
      
            $pay_modes = array();
            foreach($brgys as $brgy) {
                foreach($brgy as $key => $val) {
                    $pay_modes[$key] = $val;
                }
            }
      
            return $pay_modes;
       }
   

   
       public function getList($request)
       {
           $params = $columns = $totalRecords = $data = array();
           $params = $_REQUEST;
           $q=$request->input('q');
           if(!isset($params['start']) && !isset($params['length'])){
             $params['start']="0";
             $params['length']="10";
           }
           $columns = array( 
             1 =>"pm_desc",
             2 =>"pm_no",
             3 =>"pm_status",   
           );
           $sql = DB::table('cto_payment_mode')
                 ->select('*');
           if(!empty($q) && isset($q)){
               $sql->where(function ($sql) use($q) {
                   $sql->where(DB::raw('LOWER(pm_desc)'),'like',"%".strtolower($q)."%")
                       ->orWhere(DB::raw('LOWER(pm_no)'),'like',"%".strtolower($q)."%");
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
