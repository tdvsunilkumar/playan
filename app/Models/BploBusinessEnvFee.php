<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BploBusinessEnvFee extends Model
{
    public function updateActiveInactive($id,$columns){
        return DB::table('bplo_business_env_fees')->where('id',$id)->update($columns);
      }  
    public function updateData($id,$columns){
        return DB::table('bplo_business_env_fees')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('bplo_business_env_fees')->insert($postdata);
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
          4 =>"bbef_fee_option",
          5 =>"bbef_fee_amount",
          6 =>"bbef_tax_schedule",
          7 =>"bbef_revenue_code",
          8 =>"bbef_remarks",
        //   9 =>"bgf.is_active"     
        );

        $sql = DB::table('bplo_business_env_fees AS bgf')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bgf.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bgf.tax_type_id')
               ->join('bplo_business_classifications AS bbc', 'bbc.id', '=', 'bgf.bbc_classification_code')
              ->select('bgf.id','tc.tax_class_desc','tax_type_short_name','bbc_classification_desc','bbef_fee_option','bbef_fee_amount','bbef_tax_schedule','bbef_revenue_code','bbef_remarks','bba_code','bgf.is_active');

        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
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
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
