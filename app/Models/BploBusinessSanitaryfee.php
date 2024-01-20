<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessSanitaryfee extends Model
{

    public function updateActiveInactive($id,$columns){
      return DB::table('bplo_business_sanitaryfees')->where('id',$id)->update($columns);
    }  
     public function updateData($id,$columns){
        return DB::table('bplo_business_sanitaryfees')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('bplo_business_sanitaryfees')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getbussinesscalsification(){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->get();
    }
    public function getbussinessactivitybyid($id){
        return DB::table('bplo_business_activities')->select('id','taxclass_taxtype_classification_code','bba_code','bba_desc')->where('is_active',1)->where('business_classification_id','=',$id)->get();
    }
    public function getbussinessbyTaxtype($id){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->where('tax_type_id','=',$id)->get();
    }
    public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        return $sql->get();
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
          0 =>"tax_class_desc",  
          1 =>"tax_type_short_name",
          2 =>"bbc_classification_desc",
          3 =>"bba_code",
          4 =>"bsf_fee_option",
          5 =>"bsf_fee_amount",
          6 =>"bsf_revenue_code",
          7 =>"bsf_remarks",
          // 8 =>"bsf.is_active"     
        );

        $sql = DB::table('bplo_business_sanitaryfees AS bsf')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bsf.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bsf.tax_type_id')
               ->join('bplo_business_classifications AS bbc', 'bbc.id', '=', 'bsf.bbc_classification_code')
              ->select('bsf.id','tc.tax_class_desc','tax_type_short_name','bbc_classification_desc','bsf_fee_option','bsf_fee_amount','bsf_tax_schedule','bsf_revenue_code','bsf_remarks','bba_code','bsf.is_active');
        //$sql->where('bsf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_short_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbc_classification_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bba_code)'),'like',"%".strtolower($q)."%");
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bsf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function getSanitaryCategory($classificationid,$activityid){
        return DB::table('sanitary_fee_category')->select('*')->where('bussiness_classifiaction_id', '=', $classificationid)->where('bussiness_activities_id', '=', $activityid)->get();
      }
       public function getSanitaryArea($classificationid,$activityid){
        return DB::table('sanitary_fee_areamaster')->select('*')->where('bussiness_classifiaction_id', '=', $classificationid)->where('bussiness_activities_id', '=', $activityid)->get();
      }
}
