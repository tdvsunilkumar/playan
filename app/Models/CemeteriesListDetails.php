<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CemeteriesListDetails extends Model
{ 
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_cemeteries_list_details';
    
    public $timestamps = false;

    public function updateActiveInactive($id,$columns){
     return DB::table('eco_cemeteries_list_details')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_cemeteries_list_details')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_cemeteries_list_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_cemeteries_list_details')->where('id',$id)->first();
    }

    public function getList($request){
		
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
		$ecl_id=$request->input('ecl_id');
		
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
		  5 =>"ecld.ecl_block",
          6 =>"ecld.ecl_lot",
		  7 =>"ecld.status",
           
        );

        $sql = DB::table('eco_cemeteries_list_details As ecld')
				 ->join('eco_cemeteries_lists AS ecl', 'ecl.id', '=', 'ecld.ecl_id')
				  ->join('eco_data_cemeteries AS edc', 'edc.id', '=', 'ecl.ec_id')
				 ->join('barangays AS b', 'b.id', '=', 'ecl.brgy_id')
				 ->join('eco_cemeteries_style AS ecs', 'ecs.id', '=', 'ecl.ecs_id')
				 ->select('ecld.*','b.brgy_name','edc.cem_name','ecl.ecl_street','ecs.eco_cemetery_style');
		if(!empty($ecl_id) && isset($ecl_id)){
                    $sql->where('ecld.ecl_id','=',$ecl_id);
            }		 
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(edc.cem_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecs.eco_cemetery_style)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecl.ecl_street)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecld.ecl_block)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecld.ecl_lot)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(ecld.status)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
		
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ecld.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
