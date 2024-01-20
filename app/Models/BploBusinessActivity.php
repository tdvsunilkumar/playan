<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessActivity extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('bplo_business_activities')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('bplo_business_activities')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bplo_business_activities')->insert($postdata);
    }
    public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getclasscode($id){
      return DB::table('tax_classes')->select('tax_class_code')->where('id',(int)$id)->first();
    }
    
    public function gettypecode($id){
      return DB::table('tax_types')->select('type_code')->where('id',(int)$id)->first();
    }
    public function getClassificationsCode($id){
      return DB::table('bplo_business_classifications')->select('bbc_classification_code')->where('id',(int)$id)->first();
    }
    public function getClassifications($tax_class_id,$tax_type_id){
        $sql = DB::table('bplo_business_classifications')->select('id','bbc_classification_code','bbc_classification_desc')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        if($tax_type_id>0){
            $sql->where('tax_type_id', '=', $tax_type_id);
        }
        return $sql->get();
    }
    public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name','tax_type_description')->where('is_active',1);
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
          0 =>"taxclass_taxtype_classification_code",
          1 =>"bba_desc",  
          2 =>"bba_per_day",
          3 =>"bba_code",
          4 =>"tax_class_desc",
          5 =>"tax_class_code",
          6 =>"tax_type_short_name",
          7 =>"tax_type_description",
          8 =>"bbc_classification_desc",
          9 =>"bba.is_active"     
        );


        $sql = DB::table('bplo_business_activities AS bba')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bba.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bba.tax_type_id')
              ->join('bplo_business_classifications AS bbc', 'bbc.id', '=', 'bba.business_classification_id')
              ->select('bba.id','tc.tax_class_code','tc.tax_class_desc','type_code','tax_type_short_name','tax_type_description','bba.taxclass_taxtype_classification_code','bba_code','bba_desc','bba.is_active','bba_per_day','bbc_classification_desc','bbc_classification_code');

        //$sql->where('bba.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bba_per_day)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbc_classification_desc)'),'like',"%".strtolower($q)."%")
                    // ->orWhere(DB::raw('LOWER(taxclass_taxtype_classification_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bba_desc)'),'like',"%".strtolower($q)."%");
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bba.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
