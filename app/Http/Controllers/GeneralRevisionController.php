<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptProperty;
use App\Models\Barangay;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\ProfileMunicipality;
use App\Models\RptPropertyApproval;
use App\Models\RevisionYear;
use App\Models\RptPropertyHistory;
use App\Models\RptPropertySworn;
use App\Models\RptPropertyStatus;
use App\Models\RptPropertyAnnotation;
use App\Models\RptLandUnitValue;
use App\Models\RptPlantTressUnitValue;
use App\Models\RptBuildingUnitValue;
use App\Models\RptAssessmentLevel;
use App\Models\RptBuildingFloorValue;
use App\Models\RptPropertyMachineAppraisal;
use App\Models\CashierRealProperty;
use App\Helpers\Helper;
use App\Http\Controllers\RptPropertyController;
use DB;

class GeneralRevisionController extends Controller
{

	public $activeRevisionYear = "";
	public $arrBarangay  = [];
    public $arrPropKinds = [];
    public $activeRevisionYearDetails = null;
    public $oldRevisionYearDetails    = null;
    private $slugs;

	public function __construct(){
        $this->_commonmodel = new CommonModelmaster();  
        $this->_rptproperty = new RptProperty();
        $this->_barangay    = new Barangay;
        $this->_muncipality = new ProfileMunicipality;
        $this->_revisionyear = new RevisionYear;
        $this->_propertyHistory = new RptPropertyHistory;
        $this->_propertyappraisal = new RptPropertyAppraisal;
        $this->_landunitvalue = new RptLandUnitValue;
        $this->_buildingunitvalue = new RptBuildingUnitValue;
        $this->_assessementlevel = new RptAssessmentLevel;
        $this->_plantstreesunitvalue = new RptPlantTressUnitValue;
        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();

        foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }foreach ($this->_rptproperty->getPropertyKinds() as $val) {
            $this->arrPropKinds[$val->id]=$val->pk_code.'-'.$val->pk_description;
        }
        $this->slugs="real-property/property-data/generalrevision";
        $this->activeRevisionYearDetails = $this->_revisionyear->find(($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'');
        $lastLandProperty                = $this->_rptproperty->whereHas('landAppraisals')->whereIn('pk_is_active',[1,2])->latest()->first();
        $this->oldRevisionYearDetails    = $this->_revisionyear->find(($lastLandProperty != null)?$lastLandProperty->rvy_revision_year_id:'');
    }

    public function index(Request $request)
    {   
	
        $this->is_permitted($this->slugs, 'read');
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $activeRevisionYearDetails = $this->activeRevisionYearDetails;
        $oldRevisionYearDetails    = $this->oldRevisionYearDetails;
        
       // dd($activeRevisionYear);
        $arrBarangay = $this->arrBarangay;
        $kinds       = $this->arrPropKinds;
        return view('generalrevision.index',compact('activeRevisionYearDetails','arrBarangay','kinds','oldRevisionYearDetails'));
    }

    public function showPlantsTreesUnitValueScheduleView(Request $request){
        $activeRevisionYearDetails = $this->activeRevisionYearDetails;
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        /* Copy land unit values from previous year to new year */
        $sql = DB::table('rpt_plant_tress_unit_values')
                                    ->where('loc_group_brgy_no',$brngy)
                                    ->where('rvy_revision_year',$oldRevisionYear);
        if($barangayDetails != null){
            $sql->where('mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $plantTreesUnitValues = $sql->get();
        //dd($plantTreesUnitValues);
        foreach ($plantTreesUnitValues as $landUnit) {
            $dataToSave = [
                'mun_no' => $landUnit->mun_no,
                'loc_group_brgy_no' => $landUnit->loc_group_brgy_no,
                'pt_ptrees_code'    => $landUnit->pt_ptrees_code,
                'pc_class_code' => $landUnit->pc_class_code,
                'ps_subclass_code' => $landUnit->ps_subclass_code,
                'rvy_revision_year' => $revisionYear,
                'ptuv_unit_value' => $revisionYear,
                'ptuv_is_active'  => 1,
                'is_approve' => 0,
                'created_by' =>\Auth::user()->creatorId(),
                'created_at' =>date("Y-m-d H:i:s"),
                'updated_at' =>date("Y-m-d H:i:s")
            ];
            /* Check record exist or not */
            $existance = DB::table('rpt_plant_tress_unit_values')
                                    ->where('mun_no',$landUnit->mun_no)
                                    ->where('loc_group_brgy_no',$landUnit->loc_group_brgy_no)
                                    ->where('rvy_revision_year',$revisionYear)
                                    ->where('pc_class_code',$landUnit->pc_class_code)
                                    ->where('ps_subclass_code',$landUnit->ps_subclass_code)
                                    ->first();
             if($existance == null){
                //dd($dataToSave);
                 $this->_plantstreesunitvalue->addData($dataToSave);
             }                       
            /* Check record exist or not */
        }                          
        /* Copy land unit values from previous year to new year */
        return view('generalrevision.ajax.plantstreesunitvalue.index',compact('activeRevisionYearDetails','barangayDetails'));
    }

    public function showBuildingUnitValueScheduleView(Request $request){
        $activeRevisionYearDetails = $this->activeRevisionYearDetails;
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        /* Copy land unit values from previous year to new year */
        $sql = DB::table('rpt_building_unit_values')
                                    ->where('loc_group_brgy_no',$brngy)
                                    ->where('rvy_revision_year',$oldRevisionYear);
        if($barangayDetails != null){
            $sql->where('mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $buildingUnitValues = $sql->get();
        foreach ($buildingUnitValues as $landUnit) {
            $dataToSave = [
                'mun_no' => $landUnit->mun_no,
                'loc_group_brgy_no' => $landUnit->loc_group_brgy_no,
                'bk_building_kind_code'    => $landUnit->bk_building_kind_code,
                'bt_building_type_code' => $landUnit->bt_building_type_code,
                'buv_minimum_unit_value' => $landUnit->buv_minimum_unit_value,
                'buv_maximum_unit_value' => $landUnit->buv_maximum_unit_value,
                'rvy_revision_year' => $revisionYear,
                'buv_revision_year'  => $landUnit->buv_revision_year,
                'buv_is_active' =>1,
                'is_approve' =>0,
                'created_by' =>\Auth::user()->creatorId(),
                'created_at' =>date("Y-m-d H:i:s"),
                'updated_at' =>date("Y-m-d H:i:s")
            ];
            /* Check record exist or not */
            $existance = DB::table('rpt_building_unit_values')
                                    ->where('mun_no',$landUnit->mun_no)
                                    ->where('loc_group_brgy_no',$landUnit->loc_group_brgy_no)
                                    ->where('rvy_revision_year',$revisionYear)
                                    ->where('bk_building_kind_code',$landUnit->bk_building_kind_code)
                                    ->where('bt_building_type_code',$landUnit->bt_building_type_code)
                                    ->first();
             if($existance == null){
                //dd($dataToSave);
                 $this->_buildingunitvalue->addData($dataToSave);
             }                       
            /* Check record exist or not */
        }                          
        /* Copy land unit values from previous year to new year */
        return view('generalrevision.ajax.buildingunitvlaue.index',compact('activeRevisionYearDetails','barangayDetails'));
    }

    public function showAssessmentScheduleView(Request $request){
        $activeRevisionYearDetails = $this->activeRevisionYearDetails;
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        /* Copy land unit values from previous year to new year */
        $sql = DB::table('rpt_assessment_levels')
                                    ->where('loc_group_brgy_no',$brngy)
                                    ->where('pk_code',$kind)
                                    ->where('rvy_revision_year',$oldRevisionYear);
        if($barangayDetails != null){
            $sql->where('mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $buildingUnitValues = $sql->get();
        foreach ($buildingUnitValues as $landUnit) {
            $dataToSave = [
                'mun_no' => $landUnit->mun_no,
                'loc_group_brgy_no' => $landUnit->loc_group_brgy_no,
                'ps_subclass_code'    => $landUnit->ps_subclass_code,
                'pk_code' => $landUnit->pk_code,
                'pc_class_code' => $landUnit->pc_class_code,
                'pau_actual_use_code' => $landUnit->pau_actual_use_code,
                'rvy_revision_year' => $revisionYear,
                'is_active' =>1,
                'is_approve' =>0,
                'created_by' =>\Auth::user()->creatorId(),
                'created_at' =>date("Y-m-d H:i:s"),
                'updated_at' =>date("Y-m-d H:i:s")
            ];
            /* Check record exist or not */
            $relationData = DB::table('rpt_assessment_levels_relations')->where('assessment_id',$landUnit->id)->get();
            $existance = DB::table('rpt_assessment_levels')
                                    ->where('mun_no',$landUnit->mun_no)
                                    ->where('loc_group_brgy_no',$landUnit->loc_group_brgy_no)
                                    ->where('rvy_revision_year',$revisionYear)
                                    ->where('pk_code',$landUnit->pk_code)
                                    ->where('pc_class_code',$landUnit->pc_class_code)
                                    ->where('ps_subclass_code',$landUnit->ps_subclass_code)
                                    ->where('pau_actual_use_code',$landUnit->pau_actual_use_code)
                                    ->first();
             if($existance == null){
                 $lastInsertedId = $this->_assessementlevel->addData($dataToSave);
                 /* Save Related Data */
                 $relationData = DB::table('rpt_assessment_levels_relations')->where('assessment_id',$landUnit->id)->get();
                 foreach ($relationData as $relatedData) {
                     $dataToSaveInRelatedModel = [
                        'assessment_id'      => $lastInsertedId,
                        'minimum_unit_value' => $relatedData->minimum_unit_value,
                        'maximum_unit_value' => $relatedData->maximum_unit_value,
                        'assessment_level'   => $relatedData->assessment_level,
                        're_is_active'       => '1',
                        'created_by'         => \Auth::user()->creatorId(),
                        'created_at'         => date("Y-m-d H:i:s"),
                        'updated_at'         => date("Y-m-d H:i:s")
                     ];
                     $this->_assessementlevel->addAssRelationData($dataToSaveInRelatedModel);
                 }
                 /* Save Related Data */
             }                       
            /* Check record exist or not */
        }                          
        /* Copy land unit values from previous year to new year */
        return view('generalrevision.ajax.assessementlevel.index',compact('activeRevisionYearDetails','barangayDetails'));
    }

    public function showLandUnitValueScheduleView(Request $request){
    	$activeRevisionYearDetails = $this->activeRevisionYearDetails;
    	$brngy = $request->brngyCode;
    	$kind  = $request->propertyKind;
    	$oldRevisionYear = $request->fromRevisionYear;
    	$revisionYear    = $request->toRevisionYear;
    	$barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
    	/* Copy land unit values from previous year to new year */
    	$sql = DB::table('rpt_land_unit_values')
    	                            ->where('loc_group_brgy_no',$brngy)
    	                            ->where('rvy_revision_year',$oldRevisionYear);
    	if($barangayDetails != null){
    		$sql->where('loc_local_code',$barangayDetails->loc_local_code_id);
    	}                            
    	$landUnitValues = $sql->get();
    	foreach ($landUnitValues as $landUnit) {
    		$dataToSave = [
    			'loc_local_code' => $landUnit->loc_local_code,
    			'loc_group_brgy_no' => $landUnit->loc_group_brgy_no,
    			'pc_class_code'    => $landUnit->pc_class_code,
    			'ps_subclass_code' => $landUnit->ps_subclass_code,
    			'pau_actual_use_code' => $landUnit->pau_actual_use_code,
    			'lav_location_name' => $landUnit->lav_location_name,
    			'rvy_revision_year' => $revisionYear,
    			'lav_unit_value'  => $landUnit->lav_unit_value,
    			'lav_unit_measure' => $landUnit->lav_unit_measure,
    			'rls_code' =>$landUnit->rls_code,
    			'rls_percent' =>$landUnit->rls_percent,
    			'rls_description' =>$landUnit->rls_description,
    			'lav_strip_unit_value' =>$landUnit->lav_strip_unit_value,
    			'lav_strip_is_active' =>1,
    			'is_approve' =>0,
    			'created_by' =>\Auth::user()->creatorId(),
    			'created_at' =>date("Y-m-d H:i:s"),
    			'updated_at' =>date("Y-m-d H:i:s")
    		];
    		/* Check record exist or not */
    		$existance = DB::table('rpt_land_unit_values')
    		                        ->where('loc_local_code',$landUnit->loc_local_code)
    	                            ->where('loc_group_brgy_no',$landUnit->loc_group_brgy_no)
    	                            ->where('rvy_revision_year',$revisionYear)
    	                            ->where('pc_class_code',$landUnit->pc_class_code)
    	                            ->where('ps_subclass_code',$landUnit->ps_subclass_code)
    	                            ->where('pau_actual_use_code',$landUnit->pau_actual_use_code)
    	                            ->first();
    	     if($existance == null){
    	     	//dd($dataToSave);
                 $this->_landunitvalue->addData($dataToSave);
    	     }                       
    		/* Check record exist or not */
    	}                          
    	/* Copy land unit values from previous year to new year */
        //dd($barangayDetails);
    	return view('generalrevision.ajax.landunitvlaue.index',compact('activeRevisionYearDetails','barangayDetails'));
    }

    public function landUnitValueListing(Request $request){
    	$brngy = $request->brngyCode;
    	$kind  = $request->propertyKind;
    	$oldRevisionYear = $request->fromRevisionYear;
    	$revisionYear    = $request->toRevisionYear;
    	$barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
    	$sql = DB::table('rpt_land_unit_values AS ut')
    	       ->join('profile_municipalities AS loc', 'loc.id', '=', 'ut.loc_local_code')
               ->join('barangays AS b', 'b.id', '=', 'ut.loc_group_brgy_no')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ut.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ut.ps_subclass_code')
               ->join('rpt_property_actual_uses AS act', 'act.id', '=', 'ut.pau_actual_use_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','loc.mun_no','loc.mun_desc','b.brgy_code','b.brgy_name','act.pau_actual_use_code','act.pau_actual_use_desc','year.rvy_revision_year','year.rvy_revision_code','ut.lav_unit_value','ut.lav_unit_measure','ut.lav_strip_is_active','ut.is_approve')
    	       ->where('ut.loc_group_brgy_no',$brngy)
    	       ->where('ut.rvy_revision_year',$revisionYear)
    	       ->whereIn('ut.lav_strip_is_active',[0,1]);
    	if($barangayDetails != null){
    		$sql->where('ut.loc_local_code',$barangayDetails->loc_local_code_id);
    	}                            
    	$landUnitValues = $sql->get();
    	//dd($barangayDetails);
    	return view('generalrevision.ajax.landunitvlaue.landunitvaluelisting',compact('landUnitValues'));
    }

    public function plantTreesUnitValueListing(Request $request){
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        $sql = DB::table('rpt_plant_tress_unit_values AS ptuv')
               ->join('rpt_plant_tress AS rpt', 'rpt.id', '=', 'ptuv.pt_ptrees_code')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ptuv.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ptuv.ps_subclass_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ptuv.rvy_revision_year')
               ->select('ptuv.id','rpt.pt_ptrees_code','rpt.pt_ptrees_description','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','year.rvy_revision_year','year.rvy_revision_code','ptuv.ptuv_unit_value','ptuv.ptuv_is_active','ptuv.is_approve')
               ->where('ptuv.loc_group_brgy_no',$brngy)
               ->where('ptuv.rvy_revision_year',$revisionYear)
               ->where('ptuv.ptuv_is_active',1);
        if($barangayDetails != null){
            $sql->where('ptuv.mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $landUnitValues = $sql->get();
        //dd($landUnitValues);
        return view('generalrevision.ajax.plantstreesunitvalue.plantstreesunitvaluelisting',compact('landUnitValues'));
    }

    public function buildingunitvaluelisting(Request $request){
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        $sql = DB::table('rpt_building_unit_values AS ut')
               ->join('rpt_building_types AS bt', 'bt.id', '=', 'ut.bt_building_type_code')
               ->join('rpt_building_kinds AS k', 'k.id', '=', 'ut.bk_building_kind_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','bt.bt_building_type_code','bt.bt_building_type_desc','k.bk_building_kind_code','k.bk_building_kind_desc','year.rvy_revision_year','year.rvy_revision_code','ut.buv_minimum_unit_value','ut.buv_maximum_unit_value','ut.buv_is_active','ut.is_approve')
               ->where('ut.loc_group_brgy_no',$brngy)
               ->where('ut.rvy_revision_year',$revisionYear)
               ->where('ut.buv_is_active',1);
        if($barangayDetails != null){
            $sql->where('ut.mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $landUnitValues = $sql->get();
        //dd($landUnitValues);
        return view('generalrevision.ajax.buildingunitvlaue.buildingunitvaluelisting',compact('landUnitValues'));
    }

    public function assessementLevelListing(Request $request){
        $brngy = $request->brngyCode;
        $kind  = $request->propertyKind;
        $oldRevisionYear = $request->fromRevisionYear;
        $revisionYear    = $request->toRevisionYear;
        $barangayDetails = $this->_barangay->getActiveBarangayCode($brngy);
        $sql = DB::table('rpt_assessment_levels AS ral')
               ->join('rpt_property_kinds AS rpk', 'rpk.id', '=', 'ral.pk_code')
               ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'ral.pc_class_code')
               ->leftjoin('rpt_property_actual_uses AS rpau', 'rpau.id', '=', 'ral.pau_actual_use_code')
               ->join('rpt_revision_year AS rry', 'rry.id', '=', 'ral.rvy_revision_year')              
               ->select('ral.id','ral.is_approve','rpk.pk_code','rpk.pk_description','rpc.pc_class_code','rpc.pc_class_description','rpau.pau_actual_use_code','rpau.pau_actual_use_desc','rry.rvy_revision_year','rry.rvy_revision_code','ral.al_minimum_unit_value','ral.al_maximum_unit_value','ral.al_assessment_level','ral.is_active')
               ->where('ral.loc_group_brgy_no',$brngy)
               ->where('ral.rvy_revision_year',$revisionYear)
               ->where('ral.pk_code',$kind)
               ->whereIn('ral.is_active',[0,1]);
        if($barangayDetails != null){
            $sql->where('ral.mun_no',$barangayDetails->loc_local_code_id);
        }                            
        $landUnitValues = $sql->get();
        //dd($landUnitValues);
        return view('generalrevision.ajax.assessementlevel.assessementlevellisting',compact('landUnitValues'));
    }

    public function loadRelatedTds (Request $request){
    	$revisionYear = $request->revisionYear;
    	$brgyCode = $request->brngyCode;
    	$kind = $request->propertyKind;
        $kindDetails = DB::table('rpt_property_kinds')->where('id',$kind)->first();
    	$rptPropertiesBuild = RptProperty::with(
    		['propertyKindDetails'=>function($query){
    		                                  $query->select(['id','pk_code']);
    	                                 },
    	                              
    	                             ])
    	                              ->select(['id','rpo_code','rvy_revision_year_id','brgy_code_id','pk_id','rp_section_no','rp_pin_no','rp_td_no','rp_tax_declaration_no','rp_pin_declaration_no'])
    	                              ->where('pk_id',$kind)
    	                              ->where('rvy_revision_year_id',$revisionYear)
    	                              ->where('brgy_code_id',$brgyCode)
    	                              ->where('pk_is_active',1);
    	if($kindDetails != null && $kindDetails->pk_code == 'L'){
            $rptPropertiesBuild->addSelect(
                    [
                        'market_value_new' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_adjusted_market_value) AS market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]);
        }if($kindDetails != null && $kindDetails->pk_code == 'B'){
            $rptPropertiesBuild->addSelect(
                    [
                        'pin'=>DB::raw("CONCAT(rp_section_no,'-',rp_pin_no) AS pin"),
                        'market_value_new' => RptBuildingFloorValue::select(DB::raw("SUM(rpbfv_total_floor_market_value) AS market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptBuildingFloorValue::select(DB::raw("SUM(rpb_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]
            );
        }if($kindDetails != null && $kindDetails->pk_code == 'M'){
            $rptPropertiesBuild->addSelect(
                    [
                        'pin'=>DB::raw("CONCAT(rp_section_no,'-',rp_pin_no) AS pin"),
                        'market_value_new' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpma_market_value) AS market_value_new"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpm_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]);
        }
    	$rptProperties = $rptPropertiesBuild->get();
        //dd($rptProperties);
    	return view('generalrevision.ajax.oldtds',compact('rptProperties'));                              
    }
    public function loadDraftedTaxDeclarations (Request $request){
        $revisionYear = $request->revisionYear;
        $brgyCode = $request->brngyCode;
        $kind = $request->propertyKind;
        $kindDetails = DB::table('rpt_property_kinds')->where('id',$kind)->first();
        $rptPropertiesBuild = RptProperty::with(
            ['propertyKindDetails'=>function($query){
                                              $query->select(['id','pk_code']);
                                         },
                                      
                                     ])
                                      ->select(['id','rpo_code','rvy_revision_year_id','brgy_code_id','pk_id','rp_section_no','rp_pin_no','rp_td_no','rp_pin_declaration_no','rp_tax_declaration_no'])
                                      ->where('pk_id',$kind)
                                      ->where('rvy_revision_year_id',$revisionYear)
                                      ->where('brgy_code_id',$brgyCode)
                                      ->where('pk_is_active',2);
        if($kindDetails != null && $kindDetails->pk_code == 'L'){
            $rptPropertiesBuild->addSelect(
                    [
                        'market_value_new' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_adjusted_market_value) AS market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]);
        }if($kindDetails != null && $kindDetails->pk_code == 'B'){
            $rptPropertiesBuild->addSelect(
                    [
                        'pin'=>DB::raw("CONCAT(rp_section_no,'-',rp_pin_no) AS pin"),
                        'market_value_new' => RptBuildingFloorValue::select(DB::raw("SUM(rpbfv_total_floor_market_value) AS market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptBuildingFloorValue::select(DB::raw("SUM(rpb_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]
            );
        }if($kindDetails != null && $kindDetails->pk_code == 'M'){
            $rptPropertiesBuild->addSelect(
                    [
                        'pin'=>DB::raw("CONCAT(rp_section_no,'-',rp_pin_no) AS pin"),
                        'market_value_new' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpma_market_value) AS market_value_new"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value_new' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpm_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]);
        }
        $rptProperties = $rptPropertiesBuild->get();
        //dd($rptProperties);
        return view('generalrevision.ajax.newtds',compact('rptProperties'));                              
    }

    public function checkForCreditOrCollection($propObj = ''){
        $cashierModelObj = new CashierRealProperty;
        $checkAdvancePayment = DB::table('cto_cashier_details as ccd')
                                   ->select(
                                    DB::Raw('COALESCE(ccd.basic_amount,0) as basic_amount'),
                                    DB::Raw('COALESCE(ccd.basic_discount_amount,0) as basic_discount_amount'),
                                    DB::Raw('COALESCE(ccd.basic_penalty_amount,0) as basic_penalty_amount'),
                                    DB::Raw('COALESCE(ccd.sef_amount,0) as sef_amount'),
                                    DB::Raw('COALESCE(ccd.sef_discount_amount,0) as sef_discount_amount'),
                                    DB::Raw('COALESCE(ccd.sef_penalty_amount,0) as sef_penalty_amount'),
                                    DB::Raw('COALESCE(ccd.sh_amount,0) as sh_amount'),
                                    DB::Raw('COALESCE(ccd.sh_discount_amount,0) as sh_discount_amount'),
                                    DB::Raw('COALESCE(ccd.sh_penalty_amount,0) as sh_penalty_amount'),
                                    'rcbd.id',
                                    'rcbd.rp_code',
                                    'rcbd.rp_property_code',
                                    'rcbd.cbd_covered_year',
                                    'rcbd.sd_mode'
                                )
                                   ->where('ccd.tfoc_is_applicable',2)
                                   ->join('cto_cashier',function($j){
                                            $j->on('cto_cashier.id','=','ccd.cashier_id')
                                            ->where('cto_cashier.tfoc_is_applicable',2)
                                            ->where('cto_cashier.status',1);
                                        })
                                   ->join('rpt_cto_billing_details as rcbd',function($j)use($propObj){
                                       $j->on('rcbd.id','=','ccd.cbd_code')
                                         ->where('rcbd.rp_property_code',$propObj->rp_property_code)
                                         ->where('rcbd.cbd_covered_year',date("Y"));
                                   })
                                   ->groupBy('rcbd.id')
                                   ->get();
                                   //dd($checkAdvancePayment);
        if(!$checkAdvancePayment->isEmpty()){
            $totalPaidTax = 0;
            $totalNewTax  = 0;

            $basicAMount = 0;
            $basicPenalty = 0;
            $basicDiscount = 0;
            $sefAmount     = 0;
            $sefPenalty    = 0;
            $sefDiscount   = 0;
            $shAmount      = 0;
            $shPenalty     = 0;
            $shDiscount    = 0;
            foreach ($checkAdvancePayment as $key => $value) {
                $totalPaidTaxForMode = ($value->basic_amount+$value->basic_penalty_amount-$value->basic_discount_amount)+($value->sef_amount+$value->sef_penalty_amount-$value->sef_discount_amount)+($value->sh_amount+$value->sh_penalty_amount-$value->sh_discount_amount);
                $totalPaidTax += $totalPaidTaxForMode;
                $totalNewTaxS = $propObj->calculateTotalTaxDue($propObj->id,date("Y"),$value->sd_mode);
                $totalNewTax += $totalNewTaxS;
                $newTaxDetails = $propObj->calculatePenaltyFee($propObj->id,date("Y"),$value->sd_mode);

                $basicAMount += $newTaxDetails['basicAmount'];
                $basicPenalty += $newTaxDetails['basicPenalty'];
                $basicDiscount += $newTaxDetails['basicDisc'];
                $sefAmount     += $newTaxDetails['basicSefAmount'];
                $sefPenalty    += $newTaxDetails['sefPenalty'];
                $sefDiscount   += $newTaxDetails['sefDisc'];
                $shAmount      += $newTaxDetails['basicShAMount'];
                $shPenalty     += $newTaxDetails['shPenalty'];
                $shDiscount    += $newTaxDetails['shDisc'];
            }
      
            $lastCasheringObj = DB::table('cto_cashier_real_properties')
                                        ->select('cto_cashier_real_properties.id')
                                        ->where('cto_cashier_real_properties.rp_property_code',$propObj->rp_property_code) 
                                        ->join('cto_cashier',function($j){
                                            $j->on('cto_cashier.id','=','cto_cashier_real_properties.cashier_id')
                                            ->where('cto_cashier.tfoc_is_applicable',2)
                                            ->where('cto_cashier.status',1);
                                        })->orderBy('cto_cashier_real_properties.id','DESC')
                                        ->first();
            if($totalPaidTax > $totalNewTax){ //Tax Credit perform here
                $arrExist        = $cashierModelObj->checkCreditFacilityExist();
                $arr             = [];
                if(isset($arrExist)){
                      $arr['additional_credit_amount'] = ($totalPaidTax-$totalNewTax);
                      $arr['tcm_id'] = $arrExist->id;
                      $arr['tax_credit_gl_id'] = $arrExist->tcm_gl_id;
                      $arr['tax_credit_sl_id'] = $arrExist->tcm_sl_id;
                 }
                
                if(isset($lastCasheringObj->id)){
                    $cashierModelObj->updateCashierDetailsRealPropertyData($lastCasheringObj->id,$arr);
                }                      

            }if($totalPaidTax < $totalNewTax){ //Short Collection perform here
                $dataToUpdateInCash = [
                    'is_short_collection'   => 1,
                    'sc_covered_year'       => date("Y"),
                    'basic_amount'          => $basicAMount,
                    'basic_discount_amount' => $basicDiscount,
                    'basic_penalty_amount'  => $basicPenalty,
                    'sef_amount'            => $sefAmount,
                    'sef_discount_amount'   => $sefDiscount,
                    'sef_penalty_amount'    => $sefPenalty,
                    'sh_amount'             => $shAmount,
                    'sh_discount_amount'    => $shDiscount,
                    'sh_penalty_amount'     => $shPenalty
                ];
                $cashierModelObj->updateCashierDetailsRealPropertyData($lastCasheringObj->id,$dataToUpdateInCash);
                $dataToUpdateInReceivable = [
                    'cbd_is_paid'   => 2,
                    'short_collection_amount' => ($totalNewTax-$totalPaidTax)/4,
                ];
                DB::table('cto_accounts_receivable_details')
                        ->where('rp_property_code',$propObj->rp_property_code)
                        ->where('ar_covered_year',date("Y"))
                        ->update($dataToUpdateInReceivable);
            }
            
        }
    }

    public function reviseOrRollback (Request $request){
        $action = ($request->has('actionForSelectedTds'))?$request->actionForSelectedTds:'';
        $ids    = ($request->has('id'))?$request->id:'';
        $allProperties = RptProperty::with(['propertyKindDetails','revisionYearDetails'])->whereIn('id',$ids)->get();
        if($action == 'rollback'){
            foreach ($allProperties as $prop) {
               $oldProp = RptProperty::where('id',$prop->created_against)->first();
               $oldProp->pk_is_active = 1;
               //dd($oldProp);
               $oldProp->save();
               $prop->delete();
            }
            $msg = "All selected properties's temporary revision has been canceleed!";
        }else{
            foreach ($allProperties as $prop) {
                $conObj = new RptPropertyController;
                $oldProp = RptProperty::find($prop->created_against);
                $prop->pk_is_active = 1;
                $prop->save();
                $conObj->_rptproperty->addDataInAccountReceivable($prop->id,$oldProp->id);
                $this->_rptproperty->syncAssedMarketValueToMainTable($prop->id);
                /* Check for Credit or Short Collection */
                $propCode = $prop->rp_property_code;
                $this->checkForCreditOrCollection($prop);
                /* Check for Credit or Short Collection */
                $conObj->updatePropertyHistory($oldProp->id,RptProperty::find($prop->id));
            }
            $msg = "All selected properties's final revision completed successfully!";
        }
        return response()->json([
            'status' => 'success',
            'msg'   => $msg,
            'action' => $action
        ]);
    }

    public function getList(Request $request){
        $kind = DB::table('rpt_property_kinds')->where('id',$request->property_kind)->first();
        if($kind->pk_code == 'L' ){
            $data=$this->_rptproperty->getList($request);
        }if($kind->pk_code == 'B' ){
            $data=$this->_rptproperty->getBuildingList($request);
        }if($kind->pk_code == 'M' ){
            $data=$this->_rptproperty->getMachineList($request);
        }
        // return $data;
        // return $kind->pk_code;
        //dd($request->all());
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
			$sr_no=$sr_no+1;
            $arr[$i]['checkbox'] = '<input type="checkbox" name="id[]" class="selectpropertyforfinalrevision" value="'.$row->id.'" />';
            $arr[$i]['no']=$sr_no;
            $arr[$i]['td_no']=$row->td_no;
            $arr[$i]['taxpayer_name']=$row->taxpayer_name;
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            if($kind->pk_code == 'L'){
                $marketValue = Helper::money_format($row->market_value);
                $assessedVal = Helper::money_format($row->assessed_value);
            }if($kind->pk_code == 'B'){
                $marketValue = Helper::money_format($row->rpb_accum_deprec_market_value);
                $assessedVal = Helper::money_format($row->rpb_assessed_value);
            }if($kind->pk_code == 'M'){
                $marketValue = Helper::money_format($row->machine_market_value);
                $assessedVal = Helper::money_format($row->machine_assessed_value);
            }
            $arr[$i]['market_value']=$marketValue;
            $arr[$i]['assessed_value']=$assessedVal;
            if($row->pk_is_active == 1){
                $active = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>';
            }if($row->pk_is_active == 2){
                $active = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Drafted</span>';
            }if($row->pk_is_active == 0){
                $active = '<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancel</span>';
            }
            $arr[$i]['pk_is_active'] = $active;
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center edirRevisedTD" data-url="'.url('/rptproperty/store?id='.$row->id).'"  title="Edit"  data-title="Update Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            //$arr[$i]['action']  = $this->updateCodeSelectList($row->id);
            $i++;
            
        }
        
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

     public function updateCodeSelectList($id = ''){
        $select = '<div class="font-awesome"><select class="form-control updatecodefunctionality fa" name="updatecodefunctionality" style="width:100px;">';
         $select .= '<option value="">Select Action</option><option class="fa" value="'.$id.'" data-actionname="edit" data-propertyid="'.$id.'">&#xf044 &nbsp;Edit</option><option value="'.$id.'" class="fa" data-actionname="print" data-propertyid="'.$id.'">&#xf02f &nbsp;Print</option>';
        $select .= '</select></div>';
        return $select;
    }

    public function store($value=''){
        $activeRevisionYearDetails = $this->activeRevisionYearDetails;
        $oldRevisionYearDetails    = $this->oldRevisionYearDetails;
        $kinds       = $this->arrPropKinds;
    	$arrBarangay = $this->arrBarangay;
    	return view('generalrevision.store',compact('arrBarangay','activeRevisionYearDetails','oldRevisionYearDetails','kinds'));
    }

    public function storeData (Request $request){
        $proprtiesNeedGenRev = [];
        $case             = [];
        $fromRevisionYear = $request->from_rvy_revision_year_id;
        $toRevisionYear   = $request->to_rvy_revision_year_id;
        $ids           = ($request->has('id'))?$request->id:[];
        $allProperties = RptProperty::with(['propertyKindDetails','revisionYearDetails'])->whereIn('id',$ids)->get();
        foreach ($allProperties as $prop) {
			
            //dd($prop->bk_building_kind_code);
            if($prop->propertyKindDetails->pk_code == "L"){
                /* Compare land unit value */
                $compareLandUnitValues   = DB::table('rpt_property_appraisals')
                ->join('rpt_properties as rp','rp.id','=','rpt_property_appraisals.rp_code')
                ->join('rpt_land_unit_values',function($query){
                $query->on('rpt_land_unit_values.rvy_revision_year','=','rp.rvy_revision_year_id');
                $query->on('rpt_land_unit_values.loc_group_brgy_no','=','rp.brgy_code_id');
                $query->on('rpt_land_unit_values.pc_class_code','=','rpt_property_appraisals.pc_class_code');
                $query->on('rpt_land_unit_values.ps_subclass_code','=','rpt_property_appraisals.ps_subclass_code');
                $query->on('rpt_land_unit_values.pau_actual_use_code','=','rpt_property_appraisals.pau_actual_use_code');
                $query->on('rpt_land_unit_values.lav_unit_value','=','rpt_property_appraisals.lav_unit_value');
            })->where('rp_code',$prop->id)->count();
                /* Compare land unit value */
                /* Compare plants trees unit value */
                if(!$prop->plantTreeAppraisals->isEmpty()){
                $comparePlantsTreesUnitValues   = DB::table('rpt_plant_trees_appraisals')->join('rpt_plant_tress_unit_values',function($query){
                $query->on('rpt_plant_tress_unit_values.pt_ptrees_code','=','rpt_plant_trees_appraisals.rp_planttree_code');
                $query->on('rpt_plant_tress_unit_values.pc_class_code','=','rpt_plant_trees_appraisals.pc_class_code');
                $query->on('rpt_plant_tress_unit_values.ps_subclass_code','=','rpt_plant_trees_appraisals.ps_subclass_code');
                $query->on('rpt_plant_tress_unit_values.ptuv_unit_value','=','rpt_plant_trees_appraisals.rpta_unit_value');
            })->where('rp_code',$prop->id)->count();
            }
                /* Compare plants trees unit value */

                /* Compare assessment levels for land */
                $compareAssesmentLevel = DB::table('rpt_property_appraisals')->select('rpt_assessment_levels_relations.assessment_level as maintable_assessment','rpt_property_appraisals.al_assessment_level as childtable_ass','rpt_property_appraisals.rpa_base_market_value as marketvalue')
                ->join('rpt_assessment_levels',function($query)use($prop){
                $query->on('rpt_assessment_levels.pau_actual_use_code','=','rpt_property_appraisals.pau_actual_use_code');
                $query->on('rpt_assessment_levels.pc_class_code','=','rpt_property_appraisals.pc_class_code');
                $query->where('rpt_assessment_levels.pk_code',$prop->pk_id);
            })
                ->join('rpt_assessment_levels_relations',function($query){
                    $query->on('rpt_assessment_levels_relations.assessment_id','=','rpt_assessment_levels.id');
                    $query->where('rpt_assessment_levels_relations.minimum_unit_value','<=',DB::raw('rpt_property_appraisals.rpa_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.maximum_unit_value','>=',DB::raw('rpt_property_appraisals.rpa_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.assessment_level','=',DB::raw('rpt_property_appraisals.al_assessment_level'));
                })
                ->where('rpt_property_appraisals.rp_code',$prop->id)->count();
                /* Compare assessment levels for land */
            }
            if($prop->propertyKindDetails->pk_code == "B"){
                /* Compare building Unit value */
                $compareBuildingUnitValues   = DB::table('rpt_building_floor_values')->join('rpt_building_unit_values',function($query)use($prop){
                $query->on('rpt_building_unit_values.bt_building_type_code','=','rpt_building_floor_values.bt_building_type_code');
                $query->on('rpt_building_unit_values.buv_minimum_unit_value','=','rpt_building_floor_values.rpbfv_floor_unit_value');
                $query->where('bk_building_kind_code',$prop->bk_building_kind_code);
            })->where('rp_code',$prop->id)->count();
                /* Compare building Unit value */

                /* Compare Assessement level for Building */
                $compareAssesmentLevel = DB::table('rpt_building_floor_values')->select('rpt_assessment_levels_relations.assessment_level as maintable_assessment','rpt_building_floor_values.al_assessment_level as childtable_ass','rpt_building_floor_values.rpbfv_floor_base_market_value as floorTotalValue')
                ->join('rpt_assessment_levels',function($query)use($prop){
                $query->on('rpt_assessment_levels.pau_actual_use_code','=','rpt_building_floor_values.pau_actual_use_code');
                $query->where('rpt_assessment_levels.pk_code',$prop->pk_id);
                $query->where('rpt_assessment_levels.pc_class_code',$prop->pc_class_code);
            })
                ->join('rpt_assessment_levels_relations',function($query){
                    $query->on('rpt_assessment_levels_relations.assessment_id','=','rpt_assessment_levels.id');
                    $query->where('rpt_assessment_levels_relations.minimum_unit_value','<=',DB::raw('rpt_building_floor_values.rpbfv_floor_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.maximum_unit_value','>=',DB::raw('rpt_building_floor_values.rpbfv_floor_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.assessment_level','=',DB::raw('rpt_building_floor_values.al_assessment_level'));
                })
                ->where('rp_code',$prop->id)->count();
                /* Compare Assessement level for Building */
            }if($prop->propertyKindDetails->pk_code == "M"){

                /* Compare Assessement level for Machinery */
                $compareAssesmentLevel = DB::table('rpt_property_machine_appraisals')->select('rpt_assessment_levels_relations.assessment_level as maintable_assessment','rpt_property_machine_appraisals.al_assessment_level as childtable_ass','rpt_property_machine_appraisals.rpma_base_market_value as floorTotalValue')
                ->join('rpt_assessment_levels',function($query)use($prop){
                $query->on('rpt_assessment_levels.pc_class_code','=','rpt_property_machine_appraisals.pc_class_code');
                $query->where('rpt_assessment_levels.pk_code',$prop->pk_id);
            })
                ->join('rpt_assessment_levels_relations',function($query){
                    $query->on('rpt_assessment_levels_relations.assessment_id','=','rpt_assessment_levels.id');
                    $query->where('rpt_assessment_levels_relations.minimum_unit_value','<=',DB::raw('rpt_property_machine_appraisals.rpma_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.maximum_unit_value','>=',DB::raw('rpt_property_machine_appraisals.rpma_base_market_value'));
                    $query->where('rpt_assessment_levels_relations.assessment_level','=',DB::raw('rpt_property_machine_appraisals.al_assessment_level'));
                })
                ->where('rp_code',$prop->id)->count();
                /* Compare Assessement level for Machinery */
            }
            //dd($comparePlantsTreesUnitValues);
            if(isset($compareLandUnitValues) && $compareLandUnitValues == 0){
                $case[] = 2; // There is changes in land unit value.
            }
            if(isset($comparePlantsTreesUnitValues) && $comparePlantsTreesUnitValues == 0){
                $case[] = 3; // There is changes in plants trees unit value.
            }
            if(isset($compareBuildingUnitValues) && $compareBuildingUnitValues == 0){
                $case[] = 4; // There is changes in building unit value.
            }
            if(isset($compareAssesmentLevel) && $compareAssesmentLevel == 0){
                $case[] = 5; // There is changes in assessment level.
            }
            if($fromRevisionYear != $toRevisionYear){
                $case[] = 1; //There is changes in revision year.
            }
//dd($case);
            if(!empty($case)){
                $this->replicateData($prop, $fromRevisionYear,$toRevisionYear, $case);
                $proprtiesNeedGenRev[] = '#'.$prop->rp_tax_declaration_no;
            }
            //dd($case);
        }
		if(!isset($prop)){
			return response()->json([
                    'status' => 'error',
                    'msg'    => 'Please select at least one T.D. No. for General Revision.'
                ]);
		}
        if(empty($proprtiesNeedGenRev)){
                $proprtiesNeedGenRev[] = '#'.$prop->rp_tax_declaration_no;
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'No changes found for General Revision!'
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'msg'    => 'General Revision completed'
                ]);
            }
        
    }

    public function replicateData($prop, $oldRevisionYearId,$newRevisionYearId, $cases){
        //dd($cases);
           $rptPropertyCont = new RptPropertyController;
           $oldRevYearDetails = $this->_revisionyear->find($oldRevisionYearId);
           $newRevYearDetails = $this->_revisionyear->find($newRevisionYearId);
           if(in_array(1, $cases) && $prop->propertyKindDetails->pk_code){
              $revisionYear = $newRevYearDetails;
           }else{
              $revisionYear = $oldRevYearDetails;
           }
           //dd($revisionYear);
           $newModel = $prop->replicate(['rvy_revision_year_id','rvy_revision_code','rp_td_no','rp_tax_declaration_no','uc_code','created_against','rp_app_effective_year','pr_tax_arp_no','rp_pin_declaration_no']);
           $newModel->rvy_revision_year_id = $revisionYear->id;
           $newModel->rvy_revision_code = $revisionYear->rvy_revision_code;
           $newModel->uc_code           = config('constants.update_codes_land.GR');
           $newModel->pk_is_active      = 2;
           $newModel->created_against   = $prop->id;
           $newModel->rp_app_effective_year   = date("Y")+1;
           $newModel->rp_registered_by  = \Auth::user()->creatorId();
           $newModel->created_at        = date("Y-m-d H:i:s");
           $newModel->updated_at        = date("Y-m-d H:i:s");
           $newModel->push();
           //dd($newModel);
           $rptPropertyCont->_rptproperty->generateTaxDeclarationAndPropertyCode($newModel->id);
           /* Replicate of approval */
             $approvalData = $prop->propertyApproval->toArray();
             unset($approvalData['id']);
             $approvalData['rp_code']    = $newModel->id;
             $approvalData['created_at'] = date("Y-m-d H:i:s");
             $approvalData['updated_at'] = date("Y-m-d H:i:s");
             $newModel->propertyApproval()->create($approvalData);
           /* Replicate of approval */

           /* Replicate of swornstatement */
           if($prop->swornStatement != null){
             $swornData = $prop->swornStatement->toArray();
             unset($swornData['id']);
             $swornData['rp_code']    = $newModel->id;
             $swornData['created_at'] = date("Y-m-d H:i:s");
             $swornData['updated_at'] = date("Y-m-d H:i:s");
             $newModel->swornStatement()->create($swornData);
           }
           /* Replicate of swornstatement */

           /* Replicate of anootationstatuses data */
           if($prop->propertyStatus != null){
             $statusData = $prop->propertyStatus->toArray();
             unset($statusData['id']);
             $statusData['rp_code']    = $newModel->id;
             $statusData['rpss_registered_by']    = \Auth::user()->creatorId();
             $statusData['created_at'] = date("Y-m-d H:i:s");
             $statusData['updated_at'] = date("Y-m-d H:i:s");
             $proStatusModel = $newModel->propertyStatus()->create($statusData);
           }
           if(!$prop->propertyAnnotations->isEmpty()){
             $annoData = $prop->propertyAnnotations->toArray();
             foreach ($annoData as $anno) {
                unset($anno['id']);
                $anno['rp_code'] = $newModel->id;
                $anno['rpa_registered_by']    = \Auth::user()->creatorId();
                $anno['created_at'] = date("Y-m-d H:i:s");
                $anno['updated_at'] = date("Y-m-d H:i:s");
                 $newModel->propertyAnnotations()->create($anno);
             }
           }
           /* Replicate of anootationstatuses data */

           /* Replicate of appraisal data of land, building and machinery */
           if($prop->propertyKindDetails != null && $prop->propertyKindDetails->pk_code == 'L'){
            if(!$prop->landAppraisals->isEmpty()){
                //dd($prop->landAppraisals);
                $landAppraisals = $prop->landAppraisals;
                foreach ($landAppraisals as $landApp) {
                    $landAppModel = $landApp;
                    $landApp      = $landApp->toArray();
                    /* Get Land Unit value and assessement level and calculete */
                    $computeData = $this->landCalculation($prop,$landApp,$revisionYear);
                    /* Get Land Unit value and assessement level and calculete */
                unset($landApp['id']);
                $landApp['rp_code'] = $newModel->id;
                $landApp['rvy_revision_year'] = $revisionYear->rvy_revision_year;
                $landApp['rvy_revision_code'] = $revisionYear->rvy_revision_code;
                if($computeData['adjMarketValue'] != 0){
                    $landApp['rpa_adjusted_market_value'] = $computeData['adjMarketValue'];
                }
                if($computeData['baseMarketValue'] != 0){
                    $landApp['rpa_base_market_value'] = $computeData['baseMarketValue'];
                }
                if($computeData['assessement'] != 0){
                    $landApp['al_assessment_level'] = $computeData['assessement'];
                }
                if($computeData['landUnitValue'] != 0){
                    $landApp['lav_unit_value'] = $computeData['landUnitValue'];
                }
                if($computeData['assessedValue'] != 0){
                    $landApp['rpa_assessed_value'] = $computeData['assessedValue'];
                }
                if($computeData['adjuValue'] != 0){
                    $landApp['rpa_adjustment_value'] = $computeData['adjuValue'];
                }
                $landApp['rpa_registered_by']    = \Auth::user()->creatorId();
                $landApp['created_at'] = date("Y-m-d H:i:s");
                $landApp['updated_at'] = date("Y-m-d H:i:s");
                $newLandAppModel = $newModel->landAppraisals()->create($landApp);
                //dd($landAppModel);
                $plantsTreesAppraisal = $landAppModel->plantTreeAppraisals;
                if(!$plantsTreesAppraisal->isEmpty()){
                foreach ($plantsTreesAppraisal->toArray() as $plantTreeAppraisal) {
                    /* Get Plants Tree Unit value and assessement level and calculete */
                    $computeDataPlantstRees = $this->plantTreeCalculation($prop,$plantTreeAppraisal,$revisionYear);
                      //dd($computeDataPlantstRees);
                    /* Get Plants Tree Unit value and assessement level and calculete */
                unset($plantTreeAppraisal['id']);
                $plantTreeAppraisal['rp_code'] = $newModel->id;
                $plantTreeAppraisal['rp_property_code'] = $prop->rp_property_code;
                $plantTreeAppraisal['rpa_code'] = $newLandAppModel->id;
                $plantTreeAppraisal['rvy_revision_year'] = $revisionYear->rvy_revision_year;
                $plantTreeAppraisal['rvy_revision_code'] = $revisionYear->id;
                if($computeDataPlantstRees['marketValue'] != 0){
                    $plantTreeAppraisal['rpta_market_value'] = $computeDataPlantstRees['marketValue'];
                }
                if($computeDataPlantstRees['plantTreeUnitValue'] != 0){
                    $plantTreeAppraisal['rpta_unit_value'] = $computeDataPlantstRees['plantTreeUnitValue'];
                }
                $plantTreeAppraisal['rpta_registered_by']    = \Auth::user()->creatorId();
                $plantTreeAppraisal['created_at'] = date("Y-m-d H:i:s");
                $plantTreeAppraisal['updated_at'] = date("Y-m-d H:i:s");
                $newLandAppModel->plantTreeAppraisals()->create($plantTreeAppraisal);
            }
             }
             /* Update Assessed Value after tree computation */
             $rptPropertyCont->_rptproperty->calculateLAPpraisalAndUpdate($newLandAppModel->id,'');
            }
           }
       }if($prop->propertyKindDetails != null && $prop->propertyKindDetails->pk_code == 'B'){
                if(!$prop->floorValues->isEmpty()){
                $floorValues = $prop->floorValues->toArray();
                foreach ($floorValues as $floorValue) {
                    /* Get Plants Tree Unit value and assessement level and calculete */
                    $computeDataBuilding = $this->buildingCalculation($prop,$floorValue,$revisionYear);
                      //dd($computeDataBuilding);
                    /* Get Plants Tree Unit value and assessement level and calculete */
                unset($floorValue['id']);
                $floorValue['rp_code'] = $newModel->id;
                $floorValue['rp_property_code'] = $prop->rp_property_code;
                if($computeDataBuilding['buildingUnitValue'] != 0){
                    $floorValue['rpbfv_floor_unit_value'] = $computeDataBuilding['buildingUnitValue'];
                }
                if($computeDataBuilding['baseMarketValue']){
                    $floorValue['rpbfv_floor_base_market_value'] = $computeDataBuilding['baseMarketValue'];
                }
                if($computeDataBuilding['marketValue'] != 0){
                    $floorValue['rpbfv_total_floor_market_value'] = $computeDataBuilding['marketValue'];
                }
                if($computeDataBuilding['assLevel']){
                    $floorValue['al_assessment_level'] = $computeDataBuilding['assLevel'];
                }
                if($computeDataBuilding['assessedValue'] != 0){
                    $floorValue['rpb_assessed_value'] = $computeDataBuilding['assessedValue'];
                }
                $floorValue['rpbfv_registered_by']    = \Auth::user()->creatorId();
                $floorValue['created_at'] = date("Y-m-d H:i:s");
                $floorValue['updated_at'] = date("Y-m-d H:i:s");
                $newFloorValueModel = $newModel->floorValues()->create($floorValue);
                $floorValueAddItems = $prop->additionalItems;
                if(!$floorValueAddItems->isEmpty()){
                foreach ($floorValueAddItems->toArray() as $addItem) {
                unset($addItem['id']);
                $addItem['rp_code'] = $newModel->id;
                $addItem['rp_property_code'] = $prop->rp_property_code;
                $addItem['rpbfv_code'] = $newFloorValueModel->id;
                $addItem['rpbfai_registered_by']    = \Auth::user()->creatorId();
                $addItem['created_at'] = date("Y-m-d H:i:s");
                $addItem['updated_at'] = date("Y-m-d H:i:s");
                $newFloorValueModel->additionalItems()->create($addItem);
            }
             }
            }
           }
            
           }if($prop->propertyKindDetails != null && $prop->propertyKindDetails->pk_code == 'M'){
                if(!$prop->machineAppraisals->isEmpty()){
                $machineAppraisals = $prop->machineAppraisals->toArray();
                foreach ($machineAppraisals as $machine) {
                    /* Get Machinery Assessement level */
                    $computeDataMachine = $this->machineCalculation($prop,$machine,$revisionYear,$machineAppraisals);
                      //dd($computeDataMachine);
                    /* Get Machinery Assessement level */
                unset($machine['id']);
                $machine['rp_code'] = $newModel->id;
                $machine['rp_property_code'] = $prop->rp_property_code;
                $machine['rvy_revision_year'] = $revisionYear->rvy_revision_year;
                $machine['rvy_revision_code'] = $revisionYear->rvy_revision_code;
                if($computeDataMachine['al_assessment_level'] != 0){
                    $machine['al_assessment_level'] = $computeDataMachine['al_assessment_level'];
                }
                if($computeDataMachine['rpm_assessed_value'] != 0){
                    $machine['rpm_assessed_value'] =  $computeDataMachine['rpm_assessed_value'];
                }
                $machine['rpma_registered_by']    = \Auth::user()->creatorId();
                $machine['created_at'] = date("Y-m-d H:i:s");
                $machine['updated_at'] = date("Y-m-d H:i:s");
                $newModel->machineAppraisals()->create($machine);
            }
           }
            
           }
           /* Replicate of appraisal data of land, building and machinery */
           $newModel->save();
           $prop->pk_is_active = 3; // Temp canceled
           $prop->save();
    }

    public function machineCalculation($prop = [], $appraisal = [], $revisionYear = [],$machineAppraisals = []){
        $appCollection = collect($machineAppraisals);
        $appraisal = (object)$appraisal;
        $totalMarketValue = $appCollection->sum('rpma_market_value');
        $request = new \Illuminate\Http\Request();
        $request->replace(
                    [
                    'propertyKind'=>$prop->pk_id,
                    'totalMarketValue'=>$totalMarketValue,
                    'propertyRevisionYear' => $revisionYear->id,
                    'barangay' => $prop->brgy_code_id,
                    'propertyClass' =>$appraisal->pc_class_code
                ]
                );
            $asseseLevalData = $this->_rptproperty->getAssessementLevel($request);
            //dd($asseseLevalData);
            $assesLevel      = (isset($asseseLevalData->assessementRelations) && !$asseseLevalData->assessementRelations->isEmpty())?$asseseLevalData->assessementRelations[0]->assessment_level:0;
            //dd($assesLevel);
            $assessedValue   = ($totalMarketValue*$assesLevel)/100;
            return [
                'al_assessment_level' => $assesLevel,
                'rpm_assessed_value' => $assessedValue
            ];
    }

    public function buildingCalculation($prop = [], $appraisal = [], $revisionYear = []){
        //dd($appraisal);
       $appraisal = (object)$appraisal;
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'buildingKing' => $prop->bk_building_kind_code,
            'baranGy' => $prop->brgy_code_id,
            'revisionYearId' => $revisionYear->id,
            'bt_building_type_code' => $appraisal->bt_building_type_code,
        ]);
        $getLandUnitValue = $this->_rptproperty->getBuildingUnitValue($request);
        
        $landUnitValue    = (isset($getLandUnitValue->buv_minimum_unit_value))?$getLandUnitValue->buv_minimum_unit_value:0;
        $landArae         = $appraisal->rpbfv_floor_area;
        $baseMarketValue  = $landArae*$landUnitValue;
        $floorMarketValue = $baseMarketValue+$appraisal->rpbfv_floor_additional_value+$appraisal->rpbfv_floor_adjustment_value;
        $request->replace([
            'propertyKind' => $prop->pk_id,
            'totalMarketValue' => $baseMarketValue,
            'propertyRevisionYear' => $revisionYear->id,
            'barangay' => $prop->brgy_code_id,
            'propertyClass' => $prop->pc_class_code,
            'propertyActualUseCode' => $appraisal->pau_actual_use_code,
        ]);        
        $getAsseLevel     = $this->_rptproperty->getAssessementLevel($request);
        $assLevel         = isset($getAsseLevel->assessementRelations[0]->assessment_level)?$getAsseLevel->assessementRelations[0]->assessment_level:0;
        $assessedValeu    = $assLevel*$baseMarketValue/100;
        $marketValue      = $baseMarketValue-$assessedValeu;
        
        //dd($baseMarketValue);
        
        $dataToReturn = [
                    'buildingUnitValue' => $landUnitValue,
                    'baseMarketValue'   => $baseMarketValue,
                    'marketValue'       => $floorMarketValue,
                    'assessedValue'     => $assessedValeu,
                    'assLevel'          => $assLevel
                ];
        return $dataToReturn;
    }

    public function plantTreeCalculation($prop = [], $appraisal = [], $revisionYear = []){
        //dd($appraisal);
       $appraisal = (object)$appraisal;
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'platTreeId' => $appraisal->rp_planttree_code,
            'barangy' => $prop->brgy_code_id,
            'revisionYearId' => $revisionYear->id,
            'classId' => $appraisal->pc_class_code,
            'subClassId' => $appraisal->ps_subclass_code,
        ]);
        $getLandUnitValue = $this->_rptproperty->getPlantTreeUnitValue($request);
        //dd($getLandUnitValue);
        $landUnitValue    = (isset($getLandUnitValue->ptuv_unit_value))?$getLandUnitValue->ptuv_unit_value:0;
        $landArae         = $appraisal->rpta_total_area_planted;
        $baseMarketValue  = $landArae*$landUnitValue;
        //dd($baseMarketValue);
        
        $dataToReturn = [
                    'plantTreeUnitValue' => $landUnitValue,
                    'marketValue'        => $baseMarketValue,
                ];
        return $dataToReturn;
    }

    public function landCalculation($prop = [], $appraisal = [], $revisionYear = []){
        //dd($appraisal);
        $appraisal = (object)$appraisal;
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'localityId' => $prop->loc_local_code,
            'barangayId' => $prop->brgy_code_id,
            'revisionYearId' => $revisionYear->id,
            'classId' => $appraisal->pc_class_code,
            'subCkassId' => $appraisal->ps_subclass_code,
            'actualUseCodeId' => $appraisal->pau_actual_use_code,
        ]);
        $getLandUnitValue = $this->_rptproperty->getLandUnitValue($request);
        $landUnitValue    = (isset($getLandUnitValue->lav_unit_value))?$getLandUnitValue->lav_unit_value:0;
        $landArae         = $appraisal->rpa_total_land_area;
        $measureUnit      = (isset($getLandUnitValue->lav_unit_measure))?$getLandUnitValue->lav_unit_measure:1;
        $baseMarketValue  = $landArae*$landUnitValue;
        //dd($baseMarketValue);
        
        $request->replace([
            'propertyKind' => $prop->pk_id,
            'totalMarketValue' => $baseMarketValue,
            'propertyRevisionYear' => $revisionYear->id,
            'barangay' => $prop->brgy_code_id,
            'propertyClass' => $appraisal->pc_class_code,
            'propertyActualUseCode' => $appraisal->pau_actual_use_code,
        ]);        
        $getAsseLevel     = $this->_rptproperty->getAssessementLevel($request);
        $assLevel         = isset($getAsseLevel->assessementRelations[0]->assessment_level)?$getAsseLevel->assessementRelations[0]->assessment_level:0;
        $assessedValeu    = $assLevel*$baseMarketValue/100;
        $marketValue      = $baseMarketValue-$assessedValeu;
       // dd($baseMarketValue);
        $factorA = ($appraisal->rpa_adjustment_factor_a != '')?$appraisal->rpa_adjustment_factor_a:0;
        $factorB = ($appraisal->rpa_adjustment_factor_b != '')?$appraisal->rpa_adjustment_factor_b:0;
        $factorC = ($appraisal->rpa_adjustment_factor_c != '')?$appraisal->rpa_adjustment_factor_c:0;
        $totalFacorValue = $factorA+$factorB+$factorC;
        $adjuValue       = ($totalFacorValue/100)*$baseMarketValue;
        $adjMarketValue  = $baseMarketValue+$adjuValue;
        $dataToReturn = [
                    'totalFacorValue' => $totalFacorValue,
                    'adjuValue'       => $adjuValue,
                    'adjMarketValue'  => $adjMarketValue,
                    'assessedValue'   => $assessedValeu,
                    'baseMarketValue' => $baseMarketValue,
                    'marketValue'     => $marketValue,
                    'landUnitValue'   => $landUnitValue,
                    'assessement'     => $assLevel
                ];
        return $dataToReturn;
    }
}
