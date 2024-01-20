<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptBuildingUnitValue extends Model
{

  protected $table = 'rpt_building_unit_values';

  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_building_unit_values')->where('id',$id)->update($columns);
  }  
 
    public function updateData($id,$columns){
        return DB::table('rpt_building_unit_values')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_building_unit_values')->insert($postdata);
    }

    
    public function getKind(){
        return DB::table('rpt_building_kinds')->select('id','bk_building_kind_code','bk_building_kind_desc')->where('bk_is_active',1)->get();
    }
    public function getBulidingType(){
        return DB::table('rpt_building_types')->select('id','bt_building_type_code','bt_building_type_desc')->where('bt_is_active',1)->get();
    }

    public function kindAjaxRequest($request){
      $term=$request->input('term');
        $query = DB::table('rpt_building_kinds')->select('id','bk_building_kind_code','bk_building_kind_desc',DB::raw('CONCAT(bk_building_kind_code,"-",bk_building_kind_desc) as text'))->where('bk_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(bk_building_kind_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(bk_building_kind_desc)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }

    public function typeAjaxRequest($request){
      $term=$request->input('term');
        $query = DB::table('rpt_building_types')->select('id','bt_building_type_code','bt_building_type_desc',DB::raw('CONCAT(bt_building_type_code,"-",bt_building_type_desc) as text'))->where('bt_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(bt_building_type_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(bt_building_type_desc)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    
     public function getLocal(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('mun_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getBrgy(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getBrgyId(){
      $municipaliti=DB::table('profile_municipalities')->select('id')->where('mun_display_for_rpt',1)->where('is_active',1)->first();
      if(!empty($municipaliti))
      {
        $rpt_locality=DB::table('rpt_locality')->select('loc_group_default_barangay_id')->where('mun_no',$municipaliti->id)->first();
        if($rpt_locality->loc_group_default_barangay_id != null)
        {
          $brgy_id=$rpt_locality->loc_group_default_barangay_id;
        }
        else{
          $brgy_id=null;
        }
      }
      else{
        $brgy_id=null;
      }

      return $brgy_id;
    }

    public function getRevision(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->get();
    }

    public function getRevisionActive(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active', 1)->get();
    }

    public function getOneRevisionActiveDefault(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active', 1)->where('is_default_value', 1)->first();
    }

    public function getRevisionDefult(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active',1)->where('is_default_value',1)->get();
    }
    
    public function getRevisionyears(){
        return DB::table('rpt_plant_tress_unit_values AS ut')->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')->select('year.id','year.rvy_revision_year','year.rvy_revision_code')->groupBy('ut.rvy_revision_year')->get();
    }
 
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $revisionyear = $request->input('revisionyear');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"k.bk_building_kind_code", 
          2 =>"bt_building_type_code", 
          3 =>"rvy_revision_year",  
          4 =>"buv_minimum_unit_value", 
          5 =>"buv_maximum_unit_value",
          6 =>"is_approve",
          7 =>"buv_is_active",
          // 8 =>"buv_is_active"
         );
         if(!empty($revisionyear)){
            $sql = DB::table('rpt_building_unit_values AS ut')
               ->join('rpt_building_types AS bt', 'bt.id', '=', 'ut.bt_building_type_code')
               ->join('rpt_building_kinds AS k', 'k.id', '=', 'ut.bk_building_kind_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','bt.bt_building_type_code','bt.bt_building_type_desc','k.bk_building_kind_code','k.bk_building_kind_desc','year.rvy_revision_year','year.rvy_revision_code','ut.buv_minimum_unit_value','ut.buv_maximum_unit_value','ut.buv_is_active','ut.is_approve')->where('ut.rvy_revision_year',$revisionyear);
         } else{
            $sql = DB::table('rpt_building_unit_values AS ut')
               ->join('rpt_building_types AS bt', 'bt.id', '=', 'ut.bt_building_type_code')
               ->join('rpt_building_kinds AS k', 'k.id', '=', 'ut.bk_building_kind_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','bt.bt_building_type_code','bt.bt_building_type_desc','k.bk_building_kind_code','k.bk_building_kind_desc','year.rvy_revision_year','year.rvy_revision_code','ut.buv_minimum_unit_value','ut.buv_maximum_unit_value','ut.buv_is_active','ut.is_approve')->where('year.is_active',1)->where('year.is_default_value',1);
        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bt.bt_building_type_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bt.bt_building_type_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(k.bk_building_kind_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(k.bk_building_kind_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ut.buv_minimum_unit_value)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ut.buv_maximum_unit_value)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ut.id','ASC');
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}



