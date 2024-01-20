<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class RptPropertyBuilding extends Model
{
    use HasFactory;
     protected $fillable = [
        'rp_property_code',
        'pk_id',
        'rvy_revision_year_id',
        'rvy_revision_code',
        'brgy_code_id',
        'brgy_no',
        'rp_td_no',
        'rp_suffix',
        'rp_tax_declaration_no',
        'loc_local_code',
        'dist_code',
        'rp_section_no',
        'rp_pin_no',
        'rp_pin_suffix',
        'rp_oct_tct_cloa_no',
        'rp_cadastral_lot_no',
        'rpo_code',
        'rp_administrator_code',
        'loc_group_brgy_no',
        'rp_location_number_n_street',
        'rp_bound_north',
        'rp_bound_south',
        'rp_bound_east',
        'rp_bound_west',
        'uc_code',
        'rp_app_taxability',
        'rp_app_effective_year',
        'rp_app_effective_quarter',
        'rp_app_posting_date',
        'rp_app_memoranda',
        'rp_app_extension_section',
        'rp_app_assessor_lot_no',
        'rp_code_lref',
        'rp_td_no_lref',
        'rp_suffix_lref',
        'rp_section_no_lref',
        'rp_pin_no_lref',
        'rp_pin_suffix_lref',
        'rpo_code_lref',
        'rp_oct_tct_cloa_no_lref',
        'rp_cadastral_lot_no_lref',
        'rp_total_land_area',
        'rp_code_bref',
        'rp_section_no_bref',
        'rp_pin_no_bref',
        'rbf_building_roof_desc1',
        'rbf_building_roof_desc2',
        'rbf_building_roof_desc3',
        'rbf_building_floor_desc1',
        'rbf_building_floor_desc2',
        'rbf_building_floor_desc3',
        'rbf_building_wall_desc1',
        'rbf_building_wall_desc2',
        'rbf_building_wall_desc3',
        'bk_building_kind_code',
        'pc_class_code',
        'rp_bulding_permit_no',
        'rp_building_name',
        'rp_building_cct_no',
        'rp_building_unit_no',
        'rp_building_age',
        'rp_building_no_of_storey',
        'rp_constructed_month',
        'rp_constructed_year',
        'rp_occupied_month',
        'rp_occupied_year',
        'rp_building_completed_year',
        'rp_building_completed_percent',
        'rp_building_gf_area',
        'rp_building_total_area',
        'rp_depreciation_rate',
        'rp_accum_depreciation',
        'rpb_accum_deprec_market_value',
        'al_assessment_level',
        'rpb_assessed_value',
        'rp_modified_by',
        'rp_registered_by',
        'rp_is_active'
    ];

    public function updateData($id,$columns){
        return DB::table('rpt_properties')->where('id',$id)->update($columns);
    }

    public function addData($postdata){
    {
            DB::table('rpt_properties')->insert($postdata);
    }
        return DB::getPdo()->lastInsertId();
    }
     public function getRevisionYears($value=''){
        return DB::table('rpt_revision_year')->get()->toArray();
     }
 	   public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_bplo',1)->get();
     }
     public function getLocalityCodes($value=''){
       return DB::table('rpt_locality')->select('id','loc_local_code','loc_local_name','loc_address')->where('is_active',1)->get();
     }
     public function getDistrictCodes($value=''){
       return DB::table('rpt_district')->select('id','loc_local_code','dist_code','dist_name')->where('is_active',1)->get();
     }
     public function getUpdateCodes(){
        return DB::table('rpt_update_codes')->get();
     }
     public function getPropClasses(){
        return DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_no','pc_class_description','pc_unit_value_option','pc_taxability_option')->get();
     }
     public function getPropKindCodes($value=''){
        return DB::table('rpt_property_kinds')->get()->toArray();
     }
      public function getStrippingCodes($value=''){
        return DB::table('rpt_land_strippings')->where('rls_is_active',1)->get()->toArray();
     }
     public function getPlantTreeCodes($value=''){
        return DB::table('rpt_plant_tress')->get()->toArray();
     }

     public function getBuildingTypes($value=''){
        return DB::table('rpt_building_types')->get()->toArray();
     } 

     public function getPropertyActualUse($value=''){
        return DB::table('rpt_property_actual_uses')->select('id','pau_actual_use_code','pau_actual_use_desc')->get()->toArray();
     } 
     public function getPropertyBuildingroof($value=''){
        return DB::table('rpt_building_roofings')->select('id','rbr_building_roof_desc')->where('is_active','1')->get()->toArray();
     } 
     public function getPropertyBuildingrfloor($value=''){
        return DB::table('rpt_building_floorings')->select('id','rbf_building_flooring_desc')->where('rbf_is_active','1')->get()->toArray();
     } 
     public function getPropertyBuildingwall($value=''){
        return DB::table('rpt_building_wallings')->select('id','rbw_building_walling_desc')->where('rbw_is_active','1')->get()->toArray();
     } 

     public function getHrEmplyees($value=''){
        return DB::table('hr_employees')->get()->toArray();
     }
     public function getKindIdByCode(){
        $sql = DB::table('rpt_property_kinds')->select('id','pk_description')->get()->toArray();
        if($sql != null){
            return $sql;
        }else{
            return "";
        }
    }
    public function updateBuildingFloorvalueDetails($id,$columns){
            return DB::table('rpt_property_bldg_floor_val')->where('id',$id)->update($columns);
    }

    public function addBuildingFloorvalueDetail($postdata){
            return DB::table('rpt_property_bldg_floor_val')->insert($postdata);
    }
    
    public function getUpdateCodeById($value=''){
        $sql = DB::table('rpt_update_codes')->where('id',$value)->get()->first();
        if($sql != null){
            return $sql->uc_code;
        }else{
            return "";
        }
    }

     public function getLandUnitValue($request){
            $sql = DB::table('rpt_building_unit_values AS ut')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.*')
               ->where('ut.bk_building_kind_code',$request->bt_building_type_code)
               ->where('ut.bt_building_type_code',$request->actualUseCodeId)
               ->where('year.id',$request->revisionYearId)
               ->where('is_approve',1)
               ->first();
               if($sql == null){
                return false;
               }else{
                return $sql;
               }

                                       
    }

    public function getprofiles(){
        return DB::table('clients')->get();
    }

    public function getMachineryAppraisalDetals($id = ''){
        return DB::table('rpt_property_appraisals as ap')
                   ->join('rpt_property_classes AS class', 'ap.pc_class_code', '=', 'class.id')
                   ->join('rpt_property_subclassifications AS sub', 'ap.ps_subclass_code', '=', 'sub.id')
                   ->join('rpt_property_actual_uses AS au', 'ap.pau_actual_use_code', '=', 'au.id')
                   ->select('ap.*','class.pc_class_description','sub.ps_subclass_desc','au.pau_actual_use_desc')
                   ->where('ap.rp_code',(int)$id)
                   ->get();
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
          0 => "id",
          1 => "pk_description",  
          2 => "uc_description",
          3 => "rvy_revision_year",
          4 => "brgy_code",
          5 => "rpo_first_name",
          6 => "rp_cadastral_lot_no",
          7 => "rp_location_number_n_street",

         );
          $sql = DB::table('rpt_properties AS bgf')
                 ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'bgf.pk_id')
                 ->join('rpt_revision_year AS ry', 'ry.id', '=', 'bgf.rvy_revision_year_id')
                 ->join('barangays AS bgy', 'bgy.id', '=', 'bgf.brgy_code_id')
                 ->join('clients AS pr', 'pr.id', '=', 'bgf.rpo_code')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'bgf.uc_code')
                 ->selectRaw('bgf.id,bgf.rp_property_code,bgf.rp_location_number_n_street,bgf.rvy_revision_code,bgf.rp_td_no,bgf.rp_suffix,bgf.pk_is_active,bgf.rp_tax_declaration_no,bgf.loc_local_code,bgf.dist_code,bgf.rp_section_no,bgf.rp_pin_no,bgf.rp_pin_suffix,bgf.rp_oct_tct_cloa_no,bgf.rp_cadastral_lot_no,bgf.rpo_code,bgf.rp_administrator_code,pk.pk_code,pk.pk_description,ry.rvy_revision_year,ry.rvy_revision_code,bgy.brgy_code,bgy.brgy_name,pr.rpo_first_name,pr. rpo_custom_last_name,pr.rpo_address_street_name,pr.rpo_address_subdivision,uc.uc_code,uc.uc_description, CONCAT(bgy.brgy_code, bgy.brgy_name) as barangay')->where('pk_id','1');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(pk.pk_description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(uc.uc_description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ry.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pr.rpo_first_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pr.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pr.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pr.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgf.rp_cadastral_lot_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgf.rp_location_number_n_street)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($q)."%"); 
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
