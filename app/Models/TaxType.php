<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class TaxType extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('tax_types')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('tax_types')->insert($postdata);
	}
    public function getCategory(){
		return DB::table('tax_categories')->select('id','tax_category_code','tax_category_desc')->where('is_active',1)->get();
	}
    public function getTaxClasses(){
		return DB::table('tax_classes')->select('id','tax_class_code','tax_class_desc')->where('is_active',1)->get();
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
      0 =>"tax_class_code",
      1 =>"tax_class_desc",
      2 =>"tax_type_description",
      3 =>"tax_type_short_name",
      4 =>"tia_account_code",  
      5 =>"tt.is_active"     
    );

    $sql = DB::table('tax_types AS tt')
           ->join('tax_categories AS tc', 'tc.id', '=', 'tt.tax_category_id')
           ->join('tax_classes AS tcls', 'tt.tax_class_id', '=', 'tcls.id')
           ->select('tt.id','tax_class_code','tax_class_desc','type_code','tax_class_type_code','tax_type_description','column_no','tax_type_short_name','tia_account_code','tt.is_active','tax_category_code');

    //$sql->where('pc.generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_type_short_name)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(type_code)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('tt.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
