<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessClassification extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('bplo_business_classifications')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('bplo_business_classifications')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bplo_business_classifications')->insert($postdata);
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
          0 =>"bbc_classification_code",
          1 =>"bbc_classification_desc",  
          2 =>"tax_class_desc",
          3 =>"tax_type_description",
          4 =>"tax_type_short_name",
          // 5 =>"bbc.is_active"     
        );

        $sql = DB::table('bplo_business_classifications AS bbc')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bbc.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bbc.tax_type_id')
              ->select('bbc.id','tc.tax_class_desc','tc.tax_class_code','tt.type_code','tax_type_short_name','tax_type_description','bbc_classification_code','bbc_classification_desc','bbc.is_active');
        //$sql->where('bbc.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_short_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbc_classification_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbc_classification_desc)'),'like',"%".strtolower($q)."%");
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bbc.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
