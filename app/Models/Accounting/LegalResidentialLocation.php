<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LegalResidentialLocation extends Model
{
    public $table = 'eco_residential_location';
    
    public function updateData($id,$columns){
        return DB::table('eco_residential_location')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('eco_residential_location')->insert($postdata);
    }
    public function addLocDetailsData($postdata){
      return DB::table('eco_residential_location_details')->insert($postdata);
  }
    public function findById($id){
        return DB::table('eco_residential_location')->where('id',$id)->first();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('eco_residential_location')->where('id',$id)->update($columns);
    } 
    public function residentialName(){
        return DB::table('eco_residential_name')
              ->leftJoin('barangays', 'barangays.id', '=', 'eco_residential_name.barangay_id')
              ->leftJoin('eco_type_of_transaction', 'eco_type_of_transaction.id', '=', 'eco_residential_name.residential_name')
              ->select('eco_type_of_transaction.type_of_transaction AS name','barangays.brgy_name', 'eco_residential_name.id')
              ->where('eco_residential_name.is_active',1)->get();
      } 
    public function lotnolist($ecl_id){
        $data= DB::table('eco_residential_location_details')
        ->where('residential_location_id',$ecl_id)
        ->select('lot_number')->get();
        return $data;
    }  
    public function verifyUnique($request){
      $residential_id=$request->input('residential_id');
      $phase=$request->input('phase');
      $street=$request->input('street');
      $block=$request->input('block');
      $lot_from=$request->input('lot_from');
      $lot_to=$request->input('lot_to');
      return DB::table('eco_residential_location')->where('residential_id',$residential_id)->where('phase',$phase)->where('street',$street)->where('block',$block)->where('lot_from',$lot_from)->where('lot_to',$lot_to)->count();
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
          0 =>"lrl.id",
          1 =>"Brgy.brgy_name",
          2 =>"lrn.residential_name",
		      3 =>"lrl.phase",
		      4 =>"lrl.street",
          5 =>"lrl.block",
		      6=>"lrl.is_active"
        );
        $sql = DB::table('eco_residential_location As lrl')
                ->leftJoin('eco_residential_name AS lrn', 'lrn.id', '=', 'lrl.residential_id')
                ->leftJoin('eco_type_of_transaction AS ett', 'ett.id', '=', 'lrn.residential_name')
                ->leftJoin('barangays AS Brgy', 'Brgy.id', '=', 'lrn.barangay_id')
                ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'Brgy.reg_no')
                ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'Brgy.prov_no')
                ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'Brgy.mun_no')
                ->select('lrl.*','ett.type_of_transaction AS residential_name','Brgy.brgy_name', 'pr.reg_region', 'pp.prov_desc', 'pm.mun_desc');
                
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(lrn.residential_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw("CONCAT(Brgy.brgy_name, ', ',pm.mun_desc,', ',pp.prov_desc,', ', pr.reg_region)"), 'LIKE', "%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(lrl.phase)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(lrl.street)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWERlrl.block)'),'like',"%".strtolower($q)."%"); 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('lrl.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
