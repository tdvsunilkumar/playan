<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barangay;
use DB;

class Receptions extends Model
{ 
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_data_receptions';
    
    public $timestamps = false;
    
    public function updateActiveInactive($id,$columns){
     return DB::table('eco_data_receptions')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_data_receptions')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_data_receptions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_data_receptions')->where('id',$id)->first();
    }
	/* public function getBarangay(){
        return DB::table('barangays AS b')
		 ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
         ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
         ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
        ->select('b.id','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region')
        ->get();
    } */
	
	public function getMuncipality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','5')->first();
    }
    public function getBarangay($munid){
         return DB::table('barangays')
         ->select('id','brgy_code','brgy_name')->where('is_active',1)->where('mun_no',$munid)->get();
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
          1 =>"reception_name",
          2 =>"status"
           
        );

        $sql = DB::table('eco_data_receptions')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(reception_name)'),'like',"%".strtolower($q)."%")
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

    public function allCemeteryStyles()
    {
        $styles = self::where('status', 1)->orderBy('id', 'asc')->get();
    
        $styz = array();
        $styz[] = array('' => 'select a receptions');
        foreach ($styles as $style) {
            $styz[] = array(
                $style->id => $style->receptions_name
            );
        }

        $styles = array();
        foreach($styz as $sty) {
            foreach($sty as $key => $val) {
                $styles[$key] = $val;
            }
        }

        return $styles;
    }
}
