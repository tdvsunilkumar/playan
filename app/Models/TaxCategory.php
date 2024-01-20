<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class TaxCategory extends Model
{
    
    public function updateData($id,$columns){
        return DB::table('tax_categories')->where('id',$id)->update($columns);
    }
    
    public function addData($postdata){
		return DB::table('tax_categories')->insert($postdata);
	}
	public function gettaxCategory(){
        return DB::table('tax_categories')->select('*')->get();
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
      0 =>"tax_category_code",
      1 =>"tax_category_desc",
      2 =>"completedesc",
      3 =>"is_active"
    );

    $sql = DB::table('tax_categories')
          ->select('id','tax_category_code','tax_category_desc','is_active');

    //$sql->where('pc.generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_category_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_category_desc)'),'like',"%".strtolower($q)."%");
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
}
