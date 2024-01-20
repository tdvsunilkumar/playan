<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessPermitfee extends Model
    { 
    public function updateActiveInactive($id,$columns){
        return DB::table('bplo_business_permitfees')->where('id',$id)->update($columns);
    }
    public function updateData($id,$columns){
        return DB::table('bplo_business_permitfees')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bplo_business_permitfees')->insert($postdata);
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
    public function getActivitybbaCode($id){
        return DB::table('bplo_business_activities')->select('id','bba_code')->where('is_active',1)->where('id','=',$id)->first();
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
          0 =>"bpt_recno",
          1 =>"tax_class_desc",  
          2 =>"tax_type_short_name",
          3 =>"bbc_classification_desc",
          4 =>"bpt_permit_fee_amount",
          5 =>"bpt_fee_option",
          6 =>"bpt_tax_schedule",
          7 =>"bpt_revenue_code",
          8 =>"bpt_remarks",
        //   9 =>"bpf.is_active"     
        );

        $sql = DB::table('bplo_business_permitfees AS bpf')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bpf.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bpf.tax_type_id')
               ->join('bplo_business_classifications AS bbc', 'bbc.id', '=', 'bpf.bbc_classification_code')
              ->select('bpf.id','bpt_recno','tc.tax_class_desc','tax_type_short_name','bbc_classification_desc','bpt_permit_fee_amount','bpt_fee_option','bpt_tax_schedule','bpt_revenue_code','bpt_remarks','bba_code','bpf.is_active');

        //$sql->where('bpf.created_by', '=', \Auth::user()->creatorId());
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
          $sql->orderBy('bpf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getPermitCategory($classificationid,$activityid){
      return DB::table('permit_fee_category')->select('*')->where('bussiness_classifiaction_id', '=', $classificationid)->where('bussiness_activities_id', '=', $activityid)->get();
    }
     public function getPermitArea($classificationid,$activityid){
      return DB::table('permit_fee_areamaster')->select('*')->where('bussiness_classifiaction_id', '=', $classificationid)->where('bussiness_activities_id', '=', $activityid)->get();
    }
}
