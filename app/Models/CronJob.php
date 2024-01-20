<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class CronJob extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cron_job';
    
    public $timestamps = false;

    public function updateActiveInactive($id,$columns){
        return DB::table('cron_job')->where('id',$id)->update($columns);
       }  
       public function updateData($id,$columns){
           return DB::table('cron_job')->where('id',$id)->update($columns);
       }
       public function addData($postdata){
           DB::table('cron_job')->insert($postdata);
           return DB::getPdo()->lastInsertId();
       }
       
       public function getEditDetails($id){
           return DB::table('cron_job')->where('id',$id)->first();
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
             1 =>"department",
             2 =>"url",
             3 =>"description",
             4 =>"remarks",
             5 =>"schedule_type",
             6 =>"schedule_value",
             7 =>"status"
           );
   
           $sql = DB::table('cron_job')
                 ->select('cron_job.*');
           if(!empty($q) && isset($q)){
               $sql->where(function ($sql) use($q) {
                   $sql->where(DB::raw('LOWER(department)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(url)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(remarks)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(schedule_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(schedule_value)'),'like',"%".strtolower($q)."%"); 
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
