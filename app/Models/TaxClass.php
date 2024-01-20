<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class TaxClass extends Model
{
    public function updateData($id,$columns){
        return DB::table('tax_classes')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('tax_classes')->insert($postdata);
	}
    
	public function getTaxclass()
    {
        return DB::table('tax_classes')->select('*')->get();
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
      2 =>"completedesc",
      3 =>"is_active"
    );

    $sql = DB::table('tax_classes')
          ->select('id','tax_class_code','tax_class_desc','is_active');

    //$sql->where('pc.generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(tax_class_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(tax_class_desc)'),'like',"%".strtolower($q)."%");
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
     public function updateActiveInactive($id,$columns){
      return DB::table('tax_classes')->where('id',$id)->update($columns);
    }  
}
