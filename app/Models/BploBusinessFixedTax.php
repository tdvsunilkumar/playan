<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessFixedTax extends Model
{
    
      public function updateData($id,$columns){
        return DB::table('bplo_business_fixed_taxes')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bplo_business_fixed_taxes')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }



     public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getbussinesscalsification2(){
        return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->get();
    }
    public function getTaxDetails($id){
        return DB::table('bplo_business_classifications as bgf')
               ->join('tax_classes AS tc', 'tc.id', '=', 'bgf.tax_class_id')
               ->join('tax_types AS tt', 'tt.id', '=', 'bgf.tax_type_id')
               ->select('bgf.id','tc.tax_class_code','tc.tax_class_desc','tt.type_code','tt.tax_type_description','bgf.bbc_classification_code','bgf.bbc_classification_desc','bgf.tax_class_id','bgf.tax_type_id')->where('bgf.id',(int)$id)->where('bgf.is_active',1)->first();
    }
    public function getbussinessactivitybyid($id){
        return DB::table('bplo_business_activities')->select('id','taxclass_taxtype_classification_code','bba_code','bba_desc')->where('is_active',1)->where('business_classification_id','=',$id)->get();
    }
    
    // public function getbussinessbyTaxtype($id){
    //     return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->where('tax_type_id','=',$id)->get();
    // }
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
    // public function getTaxClasses(){
    //     return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    // }
    // public function getcodebyid(){
    //     return DB::table('bplo_business_activities')->select('id','taxclass_taxtype_classification_code','bba_code','bba_desc')->where('is_active',1)->get();
    // }
    // public function getbussinesscalsification(){
    //     return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->get();
    // }
  
    //  public function getbussinessactivitybyid($id){
    //     return DB::table('bplo_business_activities')->select('id','taxclass_taxtype_classification_code','bba_code','bba_desc')->where('is_active',1)->where('tax_type_id','=',$id)->get();
    // }

    public function getbbaDetails($id){
        return DB::table('bplo_business_activities')->select('id','taxclass_taxtype_classification_code','bba_code','bba_desc','business_classification_id')->where('id',(int)$id)->where('is_active',1)->first();
    }
    
    // public function distroy($id){
    //     DB::table('bplo_business_fixed_taxes')->where('id', $id)->delete();
    // }
    
    // public function getbussinessbyTaxtype($id){
    //     return DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1)->where('tax_type_id','=',$id)->get();
    // }
    // public function getTaxTyeps($tax_class_id=0){
    //     $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name','tax_type_description')->where('is_active',1);
    //     if($tax_class_id>0){
    //         $sql->where('tax_type_id', '=', $tax_class_id);
    //     }
    //     return $sql->get();
    // }
  


  
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"tax_class_code",  
          1 =>"type_code",
          2 =>"bbc_classification_code",
          3 =>"bba_code",
          4 =>"bft_additional_tax",
          5 =>"tax_class_desc",
          6 =>"tax_type_description",  
          7 =>"bbc_classification_desc",
          8 =>"bft_tax_amount",
          9 =>"bft_item_count",
          10 =>"bft_additional_tax",
          11 =>"bba_desc",
          12 =>"bft_taxation_procedure"
           
        );
        

        $sql = DB::table('bplo_business_fixed_taxes AS bgf')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bgf.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bgf.tax_type_id')
              ->join('bplo_business_classifications AS bbc', 'bbc.id', '=', 'bgf.bbc_classification_code')
              ->join('bplo_business_activities AS bba', 'bba.id', '=', 'bgf.bba_code')
              ->select('bgf.id','tc.tax_class_code','tc.tax_class_desc','tt.type_code','tt.tax_type_description','bbc.bbc_classification_code','bba.bba_code','bba.bba_desc','bbc.bbc_classification_desc','bft_tax_amount','bft_item_count','bft_additional_tax','bft_taxation_procedure','bft_taxation_schedule');

        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bbc_classification_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bft_tax_amount)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bft_item_count)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bft_additional_tax)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bft_taxation_procedure)'),'like',"%".strtolower($q)."%");
                  
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
