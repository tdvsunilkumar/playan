<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class EngBldgAssessmentFees extends Model
{
  public $table = 'eng_bldg_assessment_fees';
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_bldg_assessment_fees')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eng_bldg_assessment_fees')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_bldg_assessment_fees')->insert($postdata);
    }
    public function getPermit(){
        return DB::table('eng_bldg_permit_apps')->select('id','ebpa_owner_last_name','ebpa_owner_first_name','ebpa_owner_mid_name','ebpa_owner_suffix_name')->get();
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
          1 =>"ebpa_owner_first_name", 
          2 =>"ebaf_total_assessed_by",
          3 =>"ebaf_total_or_no",
          4 =>"ebaf_total_amount",
          5 =>"ebaf_total_date_paid"
          // 6 =>"ps_is_active"
         );
         $sql = DB::table('eng_bldg_assessment_fees AS sub')
               ->join('eng_bldg_permit_apps AS class', 'class.id', '=', 'sub.ebpa_id')
               ->select('sub.id','class.ebpa_owner_last_name','class.ebpa_owner_first_name','class.ebpa_owner_mid_name','class.ebpa_owner_suffix_name','sub.ebaf_total_amount','sub.ebaf_total_assessed_by','sub.ebaf_total_or_no','sub.ebaf_total_date_paid','sub.ebaf_is_active');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(class.ebpa_owner_last_name)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(class.ebpa_owner_first_name)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(class.ebpa_owner_mid_name)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(class.ebpa_owner_suffix_name)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(sub.ebaf_total_amount)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(sub.ebaf_total_assessed_by)'),'like',"%".strtolower($q)."%")
                   ->orWhere(DB::raw('LOWER(sub.ebaf_total_or_no)'),'like',"%".strtolower($q)."%");
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('sub.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}


