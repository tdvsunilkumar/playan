<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessLocation extends Model
{
       public function updateActiveInactive($id,$columns){
        return DB::table('bplo_business_locations')->where('id',$id)->update($columns);
       }  
       public function updateData($id,$columns){
           return DB::table('bplo_business_locations')->where('id',$id)->update($columns);
       }
       public function addData($postdata){
           DB::table('bplo_business_locations')->insert($postdata);
           return DB::getPdo()->lastInsertId();
       }
       
       public function getEditDetails($id){
           return DB::table('bplo_business_locations')->where('id',$id)->first();
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
             1 =>"busloc_desc",
             2 =>"busloc_status",   
           );
           $sql = DB::table('bplo_business_locations')
                 ->select('*');
           if(!empty($q) && isset($q)){
               $sql->where(function ($sql) use($q) {
                   $sql->where(DB::raw('LOWER(busloc_desc)'),'like',"%".strtolower($q)."%");
               });
           }
           /*  #######  Set Order By  ###### */
           if(isset($params['order'][0]['column']))
             $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
           else
             $sql->orderBy('id','ASC');
   
           /*  #######  Get count without limit  ###### */
           $data_cnt=$sql->count();
           /*  #######  Set Offset & Limit  ###### */
           $sql->offset((int)$params['start'])->limit((int)$params['length']);
           $data=$sql->get();
           return array("data_cnt"=>$data_cnt,"data"=>$data);
       }
       public function allBusinessLocations($vars = '')
       {
           $bplo_business_locs = self::where('busloc_status', 1)->orderBy('id', 'asc')->get();
   
           $designs = array();
           if (!empty($vars)) {
               $designs[] = array('' => 'select a '.$vars);
           } else {
               $designs[] = array('' => 'select a Business Activity');
           }
           foreach ($bplo_business_locs as $bplo_business_loc) {
               $designs[] = array(
                   $bplo_business_loc->id => $bplo_business_loc->busloc_desc
               );
           }
   
           $bplo_business_locs = array();
           foreach($designs as $design) {
               foreach($design as $key => $val) {
                   $bplo_business_locs[$key] = $val;
               }
           }
   
           return $bplo_business_locs;
       }
       
}
