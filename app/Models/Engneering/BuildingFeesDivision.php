<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BuildingFeesDivision extends Model
{	
	public $table = 'eng_building_permit_fees_division';
    public function updateData($id,$columns){
        return DB::table('eng_building_permit_fees_division')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eng_building_permit_fees_division')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eng_building_permit_fees_division')->where('id',$id)->update($columns);
    } 
	public function feescategory(){
         return DB::table('eng_building_permit_fees_category')->select('id','ebpfc_description')->where('ebpfc_status',1)->get();
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
      1 =>"ebpfd_group",
	  2 =>"ebpfd_description",
	  3 =>"ebpfc.ebpfc_description",
	  4 =>"ebpfd_status",
    );

    $sql = DB::table('eng_building_permit_fees_division As ebpfd' )
		  ->leftjoin('eng_building_permit_fees_category AS ebpfc', 'ebpfc.id', '=', 'ebpfd.ebpfc_id')
          ->select('ebpfd.*','ebpfc.ebpfc_description');
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(ebpfd_group)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfd_description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfc.ebpfc_description)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ebpfd_status)'),'like',"%".strtolower($q)."%")
				;
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
