<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LegalResidentialLocDetails extends Model
{
    public $table = 'eco_residential_location_details';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function updateData($id,$columns){
        return DB::table('eco_residential_location_details')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eco_residential_location_details')->insert($postdata);
    }
    public function findById($id){
        return DB::table('eco_residential_location_details')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eco_residential_location_details')->where('id',$id)->update($columns);
    } 
    public function residentialName(){
        return DB::table('eco_residential_name')->where('is_active',1)->get();
      } 
    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $residential_location_id=$request->input('residential_location_id');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          0 =>"lrld.id",
          1 =>"lrl.block",
          2 =>"lrld.lot_number",
		  3=>"lrld.lot_status"
        );
        $sql = DB::table('eco_residential_location_details As lrld')
                ->leftJoin('eco_residential_location AS lrl', 'lrl.id', '=', 'lrld.residential_location_id')
                ->leftJoin('eco_residential_name AS lrn', 'lrn.id', '=', 'lrld.residential_id')
                ->leftJoin('barangays AS Brgy', 'Brgy.id', '=', 'lrn.barangay_id')
                ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'Brgy.reg_no')
                ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'Brgy.prov_no')
                ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'Brgy.mun_no')
                ->select('lrld.*','lrl.block','lrn.residential_name','Brgy.brgy_name', 'pr.reg_region', 'pp.prov_desc', 'pm.mun_desc');
        if($residential_location_id != null){
            $sql->where('lrld.residential_location_id',$residential_location_id);
        }         
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(lrl.block)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(lrld.lot_number)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('lrld.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
