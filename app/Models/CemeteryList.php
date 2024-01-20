<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CemeteryList extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('eco_cemeteries_lists')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_cemeteries_lists')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_cemeteries_lists')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_cemeteries_lists')->where('id',$id)->first();
    }
	
	public function CemeteryStyle(){
        return DB::table('eco_cemeteries_style')
        ->select('id','eco_cemetery_style')
        ->where('ecs_status',1)->get();
    }
	public function getBarangay(){
        return DB::table('eco_data_cemeteries AS eco')
		 ->join('barangays AS b', 'b.id', '=', 'eco.brgy_id')
        ->select('eco.id','eco.brgy_id','eco.cem_name','b.brgy_name')
        ->get();
    }
	public function getbrgyid($id){
        $data= DB::table('eco_data_cemeteries')
		->where('id',$id)
		->select('brgy_id')->first();
		return $data;
    } 
	public function lotnolist($ecl_id){
        $data= DB::table('eco_cemeteries_list_details')
		->where('ecl_id',$ecl_id)
		->select('ecl_lot')->get();
		return $data;
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
          1 =>"b.brgy_name",
          2 =>"edc.cem_name",
		  3 =>"ecs.eco_cemetery_style",
		  4 =>"ecl.ecl_street",
          5 =>"ecl.ecl_block",
		  6 =>"ecl.ecl_lot_no_from",
		  7 =>"ecl.ecl_lot_no_to",
		  8 =>"ecl.ecl_slot",
		  10=>"ecl.status"
        );

        $sql = DB::table('eco_cemeteries_lists As ecl')
			  ->join('barangays AS b', 'b.id', '=', 'ecl.brgy_id')
			  ->join('eco_data_cemeteries AS edc', 'edc.id', '=', 'ecl.ec_id')
			  ->join('eco_cemeteries_style AS ecs', 'ecs.id', '=', 'ecl.ecs_id')
              ->select('ecl.*','b.brgy_name','edc.cem_name','ecs.eco_cemetery_style');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(edc.cem_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecs.eco_cemetery_style)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_street)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_block)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_lot_no_from)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_lot_no_to)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_slot)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
