<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessEnggFee extends Model
{

  public function updateActiveInactive($id,$columns){
    return DB::table('bplo_business_engg_fees')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('bplo_business_engg_fees')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
         DB::table('bplo_business_engg_fees')->insert($postdata);
       return DB::getPdo()->lastInsertId();
    }
    public function getTaxClasses(){
        return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
    }
    public function getTaxTyeps($tax_class_id=0){
        $sql = DB::table('tax_types')->select('id','type_code','tax_type_short_name')->where('is_active',1);
        if($tax_class_id>0){
            $sql->where('tax_class_id', '=', $tax_class_id);
        }
        return $sql->get();
    }

    public function getclasscode($id){
      return DB::table('tax_classes')->select('tax_class_code')->where('id',(int)$id)->first();
    }
    public function gettypecode($id){
      return DB::table('tax_types')->select('type_code')->where('id',(int)$id)->first();
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
          2 =>"bof_default_amount",
          // 3 =>"bbef.is_active"     
        );

        $sql = DB::table('bplo_business_engg_fees AS bbef')
              ->join('tax_classes AS tc', 'tc.id', '=', 'bbef.tax_class_id')
              ->join('tax_types AS tt', 'tt.id', '=', 'bbef.tax_type_id')
              ->select('bbef.id','tc.tax_class_desc','tax_type_short_name','bbef.is_active','bof_default_amount');

        //$sql->where('bbef.created_by', '=', \Auth::user()->creatorId());

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_short_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bof_default_amount)'),'like',"%".strtolower($q)."%");
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bbef.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
