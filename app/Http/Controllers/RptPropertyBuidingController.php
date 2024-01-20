<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptPropertyBuilding;
use App\Models\RptProperty;
use App\Models\Barangay;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\ProfileMunicipality;
use App\Models\RptPropertyApproval;
use App\Models\RevisionYear;
use App\Models\RptPropertyHistory;
use App\Models\RptPropertyStatus;
use App\Models\RptPropertyAnnotation;
use App\Models\RptPropertySworn;
use App\Models\RptBuildingFloorValue;
use App\Models\RptPropertyBuildingFloorAdItem;
use App\Models\RptPropertyActualUse;
use DB;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Import;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RptPropertyBuidingController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrUpdateCodesDirectCancel = [];
    public $arrRevisionYears = array(""=>"Select Revision Year");
    public $arrBarangay = array(""=>"Select Barangay");
    public $arrLocCodes = array(""=>"Select Locality");
    public $taxDeclarations = array();
    public $arrDistNumbers = array(""=>"Select Locality");
    public $arrUpdateCodes = [];
    public $arrPropKindCodes = array(""=>"Select Property Kind");
    public $arrStripingCodes = array(""=>"Select Stripping Code");
    public $arrprofile = [];
    public $arrSubclasses = array(""=>"Please Select");
    public $arrbuildingroof = array(""=>"Please Select");
    public $arrbuildingfloor = array(""=>"Please Select");
    public $arrbuildingfwall = array(""=>"Please Select");
    public $rpt_building_types = array(""=>"Please Select");
    public $rpt_building_actualuse = array(""=>"Please Select");
    public $arrPlantTreesCode = ["" => 'Select Plant/Tree Code'];
    public $buildingPermits = ["" => 'Select Building Permit'];
    public $arrBuildingKinds = [];
    public $arremployees = [];
    public $activeMuncipalityCode = "";
    public $activeRevisionYear = "";
    public $yeararr = [];
    public $propertyKind = 'B';
    public $taxDeclarationss = [];
    public $bulkUploadBATds;
    public $bulkUploadBAUnitValues;

    private $slugs;
    public function __construct(){
	        $this->_bploApplication = new BploApplication();
	        $this->_commonmodel = new CommonModelmaster();  
	        $this->_rptpropertybuilding = new RptProperty();
	        $this->_barangay    = new Barangay;
	        $this->_muncipality = new ProfileMunicipality;
	        $this->_revisionyear = new RevisionYear;
	        $this->_propertyHistory = new RptPropertyHistory;
	        $this->data = [
	            "id" => 0,
	            "rp_property_code"=> "",
	            "rvy_revision_year_id" => "",
	            "rvy_revision_year" => "",
	            "rvy_revision_code" => "",
	            "pk_id" => "",
	            "loc_local_code_name" => "",
	            "rp_tax_declaration_no" => "",
	            "brgy_code_id" => "",
	            "rp_td_no" => "",
	            "rp_suffix" => "",
	            "loc_local_code" => "",
	            "dist_code" => "",
	            "dist_code_name" => "",
	            "brgy_code_and_desc" => "",
	            "rp_section_no" => "",
	            "rp_pin_no" => "",
                "land_owner" =>"",
                "land_location" => "",
	            "rp_pin_suffix" => "",
	            "rp_oct_tct_cloa_no" => "",
	            "rpo_code" => "",
                "rp_administrator_code" => "",
	            "loc_group_brgy_no" => "",
	            "rp_location_number_n_street" => "",
	            "uc_code" => "",
	            "update_code" => "",
	            "rp_cadastral_lot_no" => "",
	            "property_owner_address" => "",
	            "rp_total_land_area" => "",
	            "rp_administrator_code_address" => "",
	            "bk_building_kind_code" => "",
	            "pc_class_code" => "",
	            "rp_constructed_month" => "",
	            "rp_bulding_permit_no" => "",
                "is_manual_permit" => 0,
                "permit_id" => "",
                "rp_td_no_lref" => "",
                "rp_code_lref" => "",
                "rp_suffix_lref" =>"",
                "rp_depreciation_rate" => "",
                "rp_oct_tct_cloa_no_lref" => "",
                "rpo_code_lref" =>"",
                "rp_cadastral_lot_no_lref" => "",
	            "rp_building_age" => "",
	            "rp_building_no_of_storey" => "",
	            "rp_occupied_month" => "",
	            "rp_building_name" => "",
	            "rp_building_completed_year" => "",
	            "rp_building_completed_percent" => "",
	            "rp_building_cct_no" => "",
	            "rp_building_gf_area" => "",
	            "rp_building_total_area" => "",
	            "rp_building_unit_no" =>"",
                "rbf_building_roof_desc1" => '',
                'rbf_building_roof_desc2' => '',
                'rbf_building_roof_desc3' => '',
                'rbf_building_floor_desc1' => '',
                'rbf_building_floor_desc2' => '',
                'rbf_building_floor_desc3' => '',
                'rbf_building_wall_desc1'  => '',
                'rbf_building_wall_desc2'  => '',
                'rbf_building_wall_desc3'  => '',
                "created_against" => "",
                "rp_accum_depreciation" => "",
                'rpb_accum_deprec_market_value' => "",
                'al_assessment_level' => '',
                'rpb_assessed_value' =>''

	        ];

	        $this->approvedata =array('id'=>'','rp_app_taxability'=>'','rp_app_posting_date'=>'','rp_modified_by'=>'','rp_app_effective_year'=>'','rp_modified_by'=>'','uc_code'=>'','rp_app_memoranda'=>'','pk_is_active'=>'','rp_app_memoranda'=>'');
	        foreach ($this->_bploApplication->getBarangay() as $val) {
	            $this->arrBarangay[$val->id]=$val->brgy_code.' - '.$val->brgy_name;
	        } 

	        foreach ($this->_bploApplication->getSubClasses() as $val) {
	            $this->arrSubclasses[$val->id]=$val->subclass_description;
	        } 
	        foreach ($this->_rptpropertybuilding->getRevisionYears() as $val) {
	            $this->arrRevisionYears[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
	        }foreach ($this->_rptpropertybuilding->getLocalityCodes() as $val) {
	            $this->arrLocCodes[$val->id]=$val->loc_local_code.'-'.$val->loc_local_name;
	        }foreach ($this->_rptpropertybuilding->getDistrictCodes() as $val) {
	            $this->arrDistNumbers[$val->id]=$val->dist_code;
	        }foreach ($this->_rptpropertybuilding->getUpdateCodes('B') as $val) {
	            $this->arrUpdateCodes[$val->id]=$val->uc_code.'-'.$val->uc_description;
	        }foreach ($this->_rptpropertybuilding->getPropClasses() as $val) {
	            $this->arrPropClasses[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
	        }foreach ($this->_rptpropertybuilding->getPropKindCodes() as $val) {
	            $this->arrPropKindCodes[$val->id]=$val->pk_description;
	        }foreach ($this->_rptpropertybuilding->getStrippingCodes() as $val) {
	            $this->arrStripingCodes[$val->id]=$val->rls_description;
	        }foreach ($this->_rptpropertybuilding->getPlantTreeCodes() as $val) {
	            $this->arrPlantTreesCode[$val->id]=$val->pt_ptrees_description;
	        }foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
	            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
	        }foreach ($this->_rptpropertybuilding->getUpdateCodesForCancellation('B') as $val) {
            $this->arrUpdateCodesDirectCancel[$val->id]=$val->uc_code.'-'.$val->uc_description;
        }foreach ($this->_rptpropertybuilding->getHrEmplyees() as $val) {
              $this->arremployees[$val->id]=$val->fullname;
	        }
	        
            foreach ($this->_rptpropertybuilding->getPropertyBuildingroof() as $val) {
                $this->arrbuildingroof[$val->id]=$val->rbr_building_roof_desc;
            }
            foreach ($this->_rptpropertybuilding->getPropertyBuildingrfloor() as $val) {
                $this->arrbuildingfloor[$val->id]=$val->rbf_building_flooring_desc;
            }
            foreach ($this->_rptpropertybuilding->getPropertyBuildingwall() as $val) {
                $this->arrbuildingfwall[$val->id]=$val->rbw_building_walling_desc;
            }
            foreach ($this->_rptpropertybuilding->getPropertyBuildingKinds() as $val) {
                $this->arrBuildingKinds[$val->id]=$val->building_kind_standard_name;
            }
            foreach ($this->_rptpropertybuilding->getBuildAdditionalItems() as $val) {
                //dd($val);
                $this->arrAddItems[$val->bei_extra_item_code]=$val->standard_item_name;
            }
            $dataForLAbulkUpload = $this->_rptpropertybuilding->getDataForBuildAppraisalBulkUpload();
            $this->bulkUploadBATds = $dataForLAbulkUpload['tds'];
            $this->bulkUploadBAUnitValues = $dataForLAbulkUpload['floorUnitValues'];

	        $this->activeMuncipalityCode = $this->_muncipality->getActiveMuncipalityCode();
	        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
		    $this->slugs = 'rptbuilding';   
	}

    public function getBuildPermitRemoteSelect(Request $request){
        $data = $this->_rptpropertybuilding->getbuilPermits($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }

    public function createPinSuffix(Request $request){
        $id       = $request->id;
        $propId   = $request->propId;
        $proCount = $this->_rptpropertybuilding->getBuildingpropertycount($id,$propId); 
        $suffixpincount = $proCount + 1;
        $response = [
            'status' => 'success',
            'data'   => ['suffix' => 'B'.$suffixpincount]
        ];
        return response()->json($response);
    }
    public function index(Request $request)
    {   

        $this->is_permitted($this->slugs, 'read');

        $request->session()->forget('buildingstructuredata');
        $request->session()->forget('approvalFormDataBuilding');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('floorValuesBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->session()->forget('propertyAnnotationForBuilding');
        $updateCodes = $this->makeSelectListOfUpdateCodes();
        $revisionYears = $this->arrRevisionYears;
        $arrBarangay = $this->arrBarangay;
         $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        return view('rptbuilding.index',compact('arrBarangay','updateCodes','revisionYears','activeRevisionYear'));
    }

    public function autoFillMainForm(Request $request){
        $sessionData = collect(session()->get('floorValuesBuilding'));
        $id = $request->input('id');
        $dataToSend = [
            'disableKindOfBuilding' => false,
            'stucTypes'             => '',
            'storeys'               => '',
            'totalArea'             => '',
            'areaOfGround'          => ''  
        ];
        if($id > 0){
            $landApprasals = RptBuildingFloorValue::where('rp_code',$id)->get();
        }else{
            $landApprasals = $sessionData;
        }
        //dd($landApprasals);
        $stucTypes = [];
        foreach ($landApprasals as $key => $value) {
            $buildType = DB::table('rpt_building_types')->select('bt_building_type_code')->where('id',$value->bt_building_type_code)->first();
            if($buildType != null){
                $stucTypes[] = $buildType->bt_building_type_code;
            }
            
        }
        if($landApprasals->count() > 0){
            $dataToSend['disableKindOfBuilding'] = true; 
            $dataToSend['storeys'] = $landApprasals->count();
            $dataToSend['totalArea'] = $landApprasals->sum('rpbfv_floor_area');
            $dataToSend['areaOfGround'] = $landApprasals->where('rpbfv_floor_no',1)->sum('rpbfv_floor_area');
            $dataToSend['stucTypes'] = implode("; ",array_unique($stucTypes));
        }
        return response()->json($dataToSend);
    }

     public function getTaxDeclaresionNODetails(Request $request){
        $id= $request->input('id');
        $brgy_code_id= $request->input('brgy_code_id');
        $rvy_revision_year_id= $request->input('rvy_revision_year_id');
       $getgroups = $this->_rptpropertybuilding->getTaxDeclaresionNODetails($id,$brgy_code_id,$rvy_revision_year_id);
       $htmloption ="<option value=''>Please Select Tax Declaration No.</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    public function getTaxDeclaresionNODetailsAll(Request $request){
       $brgy_code_id= $request->input('brgy_code_id');
        $rvy_revision_year_id= $request->input('rvy_revision_year_id');
       $getgroups = $this->_rptpropertybuilding->getTaxDeclaresionNODetailsAll($brgy_code_id,$rvy_revision_year_id);
       $htmloption ="<option value=''>Please Select Tax Declaration No.</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    public function taxDeclarationsId(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertybuilding->gettaxDetails($id);
        echo json_encode($data);
    }
    public function makeSelectListOfUpdateCodes($value = ''){
        $html = '<select class="form-control selected_update_code" required="required" name="selected_update_code"><option value="">Select Update Code</option>';
        $restrictCodes = [config('constants.update_codes_land.DC'),config('constants.update_codes_land.GR')];
        foreach ($this->_rptpropertybuilding->getBuildingUpdateCodes() as $val) {
            if(!in_array($val->id,$restrictCodes)){
            $html .= '<option value="'.$val->id.'" data-code="'.$val->uc_code.'">'.$val->uc_code.'-'.$val->uc_description.'</option>';
        }
        }
         $html .= '</select>';
         return $html;
    }

    public function getList(Request $request){
        //$this->_rptpropertybuilding->syncAssedMarketValueToMainTable(372);
        $request->session()->forget('buildingstructuredata');
        $request->session()->forget('approvalFormDataBuilding');
        $request->session()->forget('propertyAnnotationForBuilding');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('floorValuesBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->request->add(['property_kind' => $this->propertyKind]);
        $data=$this->_rptpropertybuilding->getBuildingList($request);
        //dd($data);
        $arr=array();
        $i="0";  
        $count = $request->start+1;  
        foreach ($data['data'] as $row){ 
        //dd(Helper::money_format($row->rpb_assessed_value));   
           $arr[$i]['no']=$count;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $taxpayer_name = wordwrap($row->taxpayer_name, 30, "<br />\n");
            $arr[$i]['taxpayer_name']="<div class='showLess'>".$taxpayer_name."</div>";
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $cctUnitNo = wordwrap($row->cct_unit_no, 30, "<br />\n");
            $arr[$i]['cct_unit_no']="<span class='showLess2'>".$cctUnitNo."</span>";
            $arr[$i]['market_value']=Helper::money_format($row->rpb_accum_deprec_market_value);
            $arr[$i]['assessed_value']=Helper::money_format($row->rpb_assessed_value);
            $uc_code = $row->updatecode->uc_code.'-'.$row->updatecode->uc_description;
            $arr[$i]['uc_code']="<span class='showLess2'>".$uc_code."</span>";
            $arr[$i]['effectivity']=$row->rp_app_effective_year;
            $reg_emp_name = wordwrap($row->reg_emp_name, 20, "<br />\n");
            $arr[$i]['reg_emp_name']="<div class='showLess'>".$reg_emp_name."</div>";
            $arr[$i]['created_date']=date("d M, Y",strtotime($row->created_at));
            $arr[$i]['pk_is_active'] = ($row->pk_is_active == 1) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':(($row->rp_app_cancel_is_direct == 1)?'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Direct Cancelled</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
                if($row->pk_is_active == 1){
                $arr[$i]['action']  = '<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="edit" data-propertyid="'.$row->id.'" data-size="xxll"  title="Edit"  data-title="Real Property - L">

                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Real Property - L">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Real Property - L">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="updatecode" data-tax="'.$row->rp_tax_declaration_no.'"  data-propertyid="'.$row->id.'" data-count="'.$count.'"  data-size="xxll"  title="Update Code"  data-title="Real Property - L">

                            <i class="ti-clipboard text-white"></i>
                        </a>
                    </div>';
                    }
                    else{
                         $arr[$i]['action']  = '<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="edit" data-propertyid="'.$row->id.'" data-size="xxll"  title="Edit"  data-title="Real Property - L">

                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Real Property - L">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Real Property - L">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                   ';
                    }
            $i++;
            $count++;
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
         $select .= '<option value="">Select Action</option><option class="fa" value="'.$id.'" data-actionname="edit" data-propertyid="'.$id.'">&#xf044 &nbsp;Edit</option><option value="'.$id.'" class="fa" data-actionname="print" data-propertyid="'.$id.'">&#xf02f &nbsp;Print</option><option value="'.$id.'" class="fa" data-actionname="printfaas" data-propertyid="'.$id.'">&#xf02f &nbsp;Print FAAS</option><option value="'.$id.'" data-actionname="updatecode" class="fa" data-propertyid="'.$id.'">&#xf0c9 &nbsp;Update Code</option>';
        $select .= '</select></div>';
        return $select;
    }

    

     public function formValidation(Request $request){
        $rules = [
                'rvy_revision_year_id'=>'required',
                'brgy_code_id'=>'required',
                'rp_suffix'=>'max:5', 
                'loc_local_code_name'=>'required',
                'dist_code'=>'required',
                'rp_section_no'=>'required|max:2', 
                'rp_pin_no'=>'required|numeric|digits_between:1,10', 
                'rp_pin_suffix'=>'required|max:4', 
                'profile_id' => 'required',
                'property_owner_address' => 'required',
                //'rp_location_number_n_street' => 'required',
                //'rp_code_lref' => 'required',
                //'rp_td_no_lref' => 'required',
                //'rpo_code_lref' => 'required',
                //'rp_cadastral_lot_no_lref' => 'required',
                //'rp_total_land_area'  => 'required',
                'bk_building_kind_code' => 'required',
                'pc_class_code'         => 'required',
                //'rp_bulding_permit_no' => 'required',
                'rp_building_age' => 'required',
                'rp_building_no_of_storey' =>'required|numeric|min:1',
                'rp_constructed_month' => 'required',
                'rp_occupied_month' =>'required',
                //'rp_building_name'=>'required',
                'rp_building_completed_year'=>'required|digits:4|integer|min:1900|max:'.(date('Y')),
                'rp_building_completed_percent' =>'required|numeric|min:1|max:100',
                //'rp_building_cct_no'=> 'required',
                'rp_building_gf_area' => 'required',
                'rp_building_total_area' => 'required',
                //'rp_building_unit_no' =>'required',
            ];
            if($request->has('uc_code') && $request->uc_code == config('constants.update_codes_land.DUP')){
             $rules['rp_section_no'] = 'required|max:2';
             $rules['rp_pin_no'] = 'required|numeric|digits_between:1,10';
             $rules['rp_pin_suffix'] = 'required|max:4';
        }
        /* Rules For Previous Owner Detail Submission */
        if($request->has('rp_app_cancel_by_td_id')){
             $rules['rp_app_cancel_by_td_id'] = 'required';
        }  
        if($request->has('rp_app_taxability')){
             $rules['rp_app_taxability'] = 'required';
        }
        if($request->has('rp_app_effective_year')){
             $rules['rp_app_effective_year'] = 'required';
        }
        if($request->has('rp_app_effective_quarter')){
             $rules['rp_app_effective_quarter'] = 'required';
        }
        if($request->has('rp_app_approved_by')){
             $rules['rp_app_approved_by'] = 'required';
        }
        if($request->has('rp_app_posting_date')){
             $rules['rp_app_posting_date'] = 'required';
        }
        /*if($request->has('rpb_assessed_value')){
            $request->request->add([
                'rpb_assessed_value' => (double)str_replace( ',', '', $request->rp_assessed_value),
            ]);
             $rules['rpb_assessed_value'] = 'gt:0';
        }*/
        /* Rules For Previous Owner Detail Submission */
        $validator = \Validator::make(
            $request->all(), $rules
                ,
            [
                'rvy_revision_year_id.required' => 'Required Field',
                'brgy_code_id.required' => 'Required Field',
                'rp_td_no.required' => 'Required Field',
                'rp_pin_suffix.required' => 'Required Field',
                'rp_pin_suffix.max' => 'Only 4 Digits',
                'rp_suffix.max' => 'Only 5 Digits',
                'loc_local_code_name.required' => 'Required Field',
                'dist_code.required' => 'Required Field',
                'brgy_code.required' => 'Required Field',
                'rp_section_no.required' => 'Required Field',
                'rp_section_no.max' => 'Only 2 Digits',
                'rp_pin_no.required' => 'Required Field',
                'rp_pin_no.digits_between' => 'Invalid Value',
                'rp_pin_no.numeric' => 'Numeric only',
                'rp_total_land_area'=>'Required Field',
                'profile_id.required' => 'Required Field',
                'property_owner_address.required' => 'Required Field',
                'rp_location_number_n_street.required' => 'Required Field',
                'rp_td_no_lref.required' => 'Required Field',
                // 'rpo_code_lref.required' => 'Required Field', 
                'rp_cadastral_lot_no_lref.required' => 'Required Field',
                'rp_total_land_area.required'  => 'Required Field',
                'uc_code.required' => 'Required Field',
                'bk_building_kind_code.required' => 'Required Field',
                'pc_class_code.required' => 'Required Field',
                'rp_bulding_permit_no.required' => 'Required Field',
                'rp_building_age.required' => 'Required Field',
                'rp_building_no_of_storey.required' =>'Required Field',
                'rp_building_no_of_storey.min' =>'Invalid Value',
                'rp_building_no_of_storey.max' =>'Invalid Value',
                'rp_constructed_month.required' => 'Required Field',
                'rp_occupied_month.required' =>'Required Field',
                'rp_building_name.required'=>'Required Field',
                'rp_building_completed_year.required'=>'Required Field',
                'rp_building_completed_year.min'=>'Invalid Value',
                'rp_building_completed_year.max'=>'Invalid Value',
                'rp_building_completed_year.digits'=>'Invalid Value',
                'rp_building_completed_percent.required' =>'Required Field',
                'rp_building_completed_percent.min' =>'Invalid Value',
                'rp_building_completed_percent.max' =>'Invalid Value',
                'rp_building_cct_no.required'=> 'Required Field',
	            'rp_building_gf_area.required' => 'Required Field',
	            'rp_building_total_area.required' => 'Required Field',
	            'rp_building_unit_no.required' =>'Required Field',
            ]
        );
         $validator->after(function ($validator) {
            $data = $validator->getData();
            //dd($data);
            if($data['uc_code'] == config('constants.update_codes_land.DUP')){
                $oldPropertyData = RptProperty::find($data['old_property_id']);
                if($oldPropertyData != null){
                    //dd($oldPropertyData);
                    if($oldPropertyData->rpo_code == $data['rpo_code'] || $data['uc_code'] == config('constants.update_codes_land.TR')){
                        $validator->errors()->add('profile_id', 'Should be different from previous property!');
                    }
                }
            }
            /* Check RP Pin SUffix Existance */
            $pinSuffixExis = DB::table('rpt_properties')
                                 ->where('rp_code_lref',$data['rp_code_lref'])
                                 ->where('rp_pin_suffix',$data['rp_pin_suffix'])
                                 ->where('pk_id',config('constants.rptKinds.B'))
                                 ->where('pk_is_active',1);
            if(isset($data['rp_property_code']) && $data['rp_property_code'] != ''){
                $pinSuffixExis->where('rp_property_code','!=',$data['rp_property_code'])
                              ->where('id','!=',$data['id']);
            }                      
            $count = $pinSuffixExis->count();
            if($count > 0){
                $validator->errors()->add('rp_pin_suffix', 'Already Exists');
            }
            /* Check RP Pin SUffix Existance */
            
            //dd($propWithSaneSection);
    });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function setData($propertyId = '', $updateCode = ''){
        if(empty($this->activeRevisionYear) || empty($this->activeMuncipalityCode)){
            return [];
        }
        //$updateCodeDetails = $this->_rptpropertybuilding->getUpdateCodeById($updateCode);
        $updateCodeDetails = (in_array($updateCode,array_values(config('constants.update_codes_land'))))?array_flip(config('constants.update_codes_land'))[$updateCode]:'';
        $this->data['update_code'] = $updateCodeDetails;
        $this->data['uc_code'] = $updateCode;
        if($updateCodeDetails == ''){
            return [];
        }
        $selectedPropertyDetails = $this->_rptpropertybuilding->with([
                'revisionYearDetails',
                'propertyOwner',
                'buildingReffernceLand'
            ])->where('id',$propertyId)->first();
        switch($updateCodeDetails){
            case 'TR':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                 $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
               /* $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;*/
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';
                $rfs = $selectedPropertyDetails->rp_code_lref;       

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;

                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                 $arrSworn = DB::table('rpt_property_sworns')->where('rp_code',$propertyId)->first();
                if($arrSworn != null){
                    $arr = (array)$arrSworn;
                    $arr['id']=0;
                    session()->put('propertySwornStatementBuilding', $arr);
                }

                $arrAnno = DB::table('rpt_property_statuses')->where('rp_code',$propertyId)->first();
                if($arrAnno != null){
                    $arr = (array)$arrAnno;
                    $arr['id']=0;
                    session()->put('propertyStatusForBuilding', $arr);
                }

                $arrPropAnn = DB::table('rpt_property_annotations')->where('rp_code',$propertyId)->get()->toArray();
                if($arrPropAnn != null){
                    $arr = $arrPropAnn;
                    foreach($arr as &$val){
                        $val->id=0;
                    }
                    session()->put('propertyAnnotationForBuilding', $arr);
                }
                $arrApprove = [
                    'rp_app_appraised_by' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_by,
                    //'rp_app_appraised_date' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_date,
                    'rp_app_appraised_date' => date("Y-m-d"),
                    'rp_app_appraised_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_is_signed,
                    'rp_app_taxability' => $selectedPropertyDetails->rp_app_taxability,
                    'rp_app_recommend_by' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_by,
                    //'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,
                    'rp_app_recommend_date' => date("Y-m-d"),
                    'rp_app_recommend_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_is_signed,
                    'rp_app_effective_year' => date("Y")+1,
                    'rp_app_effective_quarter' => $selectedPropertyDetails->rp_app_effective_quarter,
                    'rp_app_approved_by' => $selectedPropertyDetails->propertyApproval->rp_app_approved_by,
                    //'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
                    'rp_app_approved_date' => date("Y-m-d"),
                    'rp_app_approved_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_approved_is_signed,
                    'cancelled_by_id'    => $selectedPropertyDetails->id,
                    'pk_is_active'    => 1,
                    //'rp_app_posting_date' => $selectedPropertyDetails->rp_app_posting_date,
                    'rp_app_posting_date' => date("Y-m-d"),
                    'rp_app_memoranda' => '',
                    'rp_app_extension_section' => $selectedPropertyDetails->rp_app_extension_section,
                    'rp_app_assessor_lot_no' => $selectedPropertyDetails->rp_app_assessor_lot_no,
                ];
                session()->put('approvalFormDataBuilding', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'CS':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                 $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
               /* $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;*/
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';
                $rfs = $selectedPropertyDetails->rp_code_lref;       

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;

                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                 $arrSworn = DB::table('rpt_property_sworns')->where('rp_code',$propertyId)->first();
                if($arrSworn != null){
                    $arr = (array)$arrSworn;
                    $arr['id']=0;
                    session()->put('propertySwornStatementBuilding', $arr);
                }

                $arrAnno = DB::table('rpt_property_statuses')->where('rp_code',$propertyId)->first();
                if($arrAnno != null){
                    $arr = (array)$arrAnno;
                    $arr['id']=0;
                    session()->put('propertyStatusForBuilding', $arr);
                }

                $arrPropAnn = DB::table('rpt_property_annotations')->where('rp_code',$propertyId)->get()->toArray();
                if($arrPropAnn != null){
                    $arr = $arrPropAnn;
                    foreach($arr as &$val){
                        $val->id=0;
                    }
                    session()->put('propertyAnnotationForBuilding', $arr);
                }
                $arrApprove = [
                    'rp_app_appraised_by' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_by,
                    //'rp_app_appraised_date' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_date,
                    'rp_app_appraised_date' => date("Y-m-d"),
                    'rp_app_appraised_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_is_signed,
                    'rp_app_taxability' => $selectedPropertyDetails->rp_app_taxability,
                    'rp_app_recommend_by' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_by,
                    //'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,
                    'rp_app_recommend_date' => date("Y-m-d"),
                    'rp_app_recommend_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_is_signed,
                    'rp_app_effective_year' => date("Y")+1,
                    'rp_app_effective_quarter' => $selectedPropertyDetails->rp_app_effective_quarter,
                    'rp_app_approved_by' => $selectedPropertyDetails->propertyApproval->rp_app_approved_by,
                    //'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
                    'rp_app_approved_date' => date("Y-m-d"),
                    'rp_app_approved_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_approved_is_signed,
                    'cancelled_by_id'    => $selectedPropertyDetails->id,
                    'pk_is_active'    => 1,
                    //'rp_app_posting_date' => $selectedPropertyDetails->rp_app_posting_date,
                    'rp_app_posting_date' => date("Y-m-d"),
                    'rp_app_memoranda' => '',
                    'rp_app_extension_section' => $selectedPropertyDetails->rp_app_extension_section,
                    'rp_app_assessor_lot_no' => $selectedPropertyDetails->rp_app_assessor_lot_no,
                ];
                session()->put('approvalFormDataBuilding', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'SSD':
            
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';       

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;

                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                 $arrSworn = DB::table('rpt_property_sworns')->where('rp_code',$propertyId)->first();
                if($arrSworn != null){
                    $arr = (array)$arrSworn;
                    $arr['id']=0;
                    session()->put('propertySwornStatementBuilding', $arr);
                }

                $arrAnno = DB::table('rpt_property_statuses')->where('rp_code',$propertyId)->first();
                if($arrAnno != null){
                    $arr = (array)$arrAnno;
                    $arr['id']=0;
                    session()->put('propertyStatusForBuilding', $arr);
                }

                $arrPropAnn = DB::table('rpt_property_annotations')->where('rp_code',$propertyId)->get()->toArray();
                if($arrPropAnn != null){
                    $arr = $arrPropAnn;
                    foreach($arr as &$val){
                        $val->id=0;
                    }
                    session()->put('propertyAnnotationForBuilding', $arr);
                }
                $arrApprove = [
                    'rp_app_appraised_by' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_by,
                   // 'rp_app_appraised_date' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_date,
                    'rp_app_appraised_date' => date("Y-m-d"),
                    'rp_app_appraised_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_is_signed,
                    'rp_app_taxability' => $selectedPropertyDetails->rp_app_taxability,
                    'rp_app_recommend_by' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_by,
                    //'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,
                    'rp_app_recommend_date' => date("Y-m-d"),
                    'rp_app_recommend_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_is_signed,
                    'rp_app_effective_year' => date("Y")+1,
                    'rp_app_effective_quarter' => $selectedPropertyDetails->rp_app_effective_quarter,
                    'rp_app_approved_by' => $selectedPropertyDetails->propertyApproval->rp_app_approved_by,
                    //'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
                    'rp_app_approved_date' => date("Y-m-d"),
                    'rp_app_approved_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_approved_is_signed,
                    'cancelled_by_id'    => $selectedPropertyDetails->id,
                    'pk_is_active'    => 1,
                    //'rp_app_posting_date' => $selectedPropertyDetails->rp_app_posting_date,
                    'rp_app_posting_date' => date("Y-m-d"),
                    'rp_app_memoranda' => '',
                    'rp_app_extension_section' => $selectedPropertyDetails->rp_app_extension_section,
                    'rp_app_assessor_lot_no' => $selectedPropertyDetails->rp_app_assessor_lot_no,
                ];
                session()->put('approvalFormDataBuilding', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'RC':
            
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';
                       

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;

                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                $data->property_owner_details              = $selectedPropertyDetails->property_owner_details;
                 $arrSworn = DB::table('rpt_property_sworns')->where('rp_code',$propertyId)->first();
                if($arrSworn != null){
                    $arr = (array)$arrSworn;
                    $arr['id']=0;
                    session()->put('propertySwornStatementBuilding', $arr);
                }

                $arrAnno = DB::table('rpt_property_statuses')->where('rp_code',$propertyId)->first();
                if($arrAnno != null){
                    $arr = (array)$arrAnno;
                    $arr['id']=0;
                    session()->put('propertyStatusForBuilding', $arr);
                }

                $arrPropAnn = DB::table('rpt_property_annotations')->where('rp_code',$propertyId)->get()->toArray();
                if($arrPropAnn != null){
                    $arr = $arrPropAnn;
                    foreach($arr as &$val){
                        $val->id=0;
                    }
                    session()->put('propertyAnnotationForBuilding', $arr);
                }
                $arrApprove = [
                    'rp_app_appraised_by' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_by,
                    //'rp_app_appraised_date' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_date,
                    'rp_app_appraised_date' => date("Y-m-d"),
                    'rp_app_appraised_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_is_signed,
                    'rp_app_taxability' => $selectedPropertyDetails->rp_app_taxability,
                    'rp_app_recommend_by' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_by,
                    //'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,
                    'rp_app_recommend_date' => date("Y-m-d"),
                    'rp_app_recommend_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_is_signed,
                    'rp_app_effective_year' => date("Y")+1,
                    'rp_app_effective_quarter' => $selectedPropertyDetails->rp_app_effective_quarter,
                    'rp_app_approved_by' => $selectedPropertyDetails->propertyApproval->rp_app_approved_by,
                    //'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
                    'rp_app_approved_date' => date("Y-m-d"),
                    'rp_app_approved_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_approved_is_signed,
                    'cancelled_by_id'    => $selectedPropertyDetails->id,
                    'pk_is_active'    => 1,
                    //'rp_app_posting_date' => $selectedPropertyDetails->rp_app_posting_date,
                    'rp_app_posting_date' => date("Y-m-d"),
                    'rp_app_memoranda' => '',
                    'rp_app_extension_section' => $selectedPropertyDetails->rp_app_extension_section,
                    'rp_app_assessor_lot_no' => $selectedPropertyDetails->rp_app_assessor_lot_no,
                ];
                session()->put('approvalFormDataBuilding', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'PC':
            
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';
                $rfs = $selectedPropertyDetails->rp_code_lref;
                foreach ($this->_rptpropertybuilding->gettaxDetailsId($rfs) as $val) {
                    $this->taxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }
                foreach ($this->_rptpropertybuilding->getTaxDeclaresionNODetails($selectedPropertyDetails->rpo_code,$selectedPropertyDetails->brgy_code_id,$selectedPropertyDetails->rvy_revision_year_id) as $val) {
                    $this->taxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }       

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;

                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                $data->property_owner_details              = $selectedPropertyDetails->property_owner_details;
                 $arrSworn = DB::table('rpt_property_sworns')->where('rp_code',$propertyId)->first();
                if($arrSworn != null){
                    $arr = (array)$arrSworn;
                    $arr['id']=0;
                    session()->put('propertySwornStatementBuilding', $arr);
                }

                $arrAnno = DB::table('rpt_property_statuses')->where('rp_code',$propertyId)->first();
                if($arrAnno != null){
                    $arr = (array)$arrAnno;
                    $arr['id']=0;
                    session()->put('propertyStatusForBuilding', $arr);
                }

                $arrPropAnn = DB::table('rpt_property_annotations')->where('rp_code',$propertyId)->get()->toArray();
                if($arrPropAnn != null){
                    $arr = $arrPropAnn;
                    foreach($arr as &$val){
                        $val->id=0;
                    }
                    session()->put('propertyAnnotationForBuilding', $arr);
                }
                $arrApprove = [
                    'rp_app_appraised_by' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_by,
                    //'rp_app_appraised_date' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_date,
                    'rp_app_appraised_date' => date("Y-m-d"),
                    'rp_app_appraised_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_appraised_is_signed,
                    'rp_app_taxability' => $selectedPropertyDetails->rp_app_taxability,
                    'rp_app_recommend_by' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_by,
                    //'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,
                    'rp_app_recommend_date' => date("Y-m-d"),
                    'rp_app_recommend_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_is_signed,
                    'rp_app_effective_year' => date("Y")+1,
                    'rp_app_effective_quarter' => $selectedPropertyDetails->rp_app_effective_quarter,
                    'rp_app_approved_by' => $selectedPropertyDetails->propertyApproval->rp_app_approved_by,
                    //'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
                    'rp_app_approved_date' => date("Y-m-d"),
                    'rp_app_approved_is_signed' => $selectedPropertyDetails->propertyApproval->rp_app_approved_is_signed,
                    'cancelled_by_id'    => $selectedPropertyDetails->id,
                    'pk_is_active'    => 1,
                    //'rp_app_posting_date' => $selectedPropertyDetails->rp_app_posting_date,
                    'rp_app_posting_date' => date("Y-m-d"),
                    'rp_app_memoranda' => '',
                    'rp_app_extension_section' => $selectedPropertyDetails->rp_app_extension_section,
                    'rp_app_assessor_lot_no' => $selectedPropertyDetails->rp_app_assessor_lot_no,
                ];
                session()->put('approvalFormDataBuilding', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'DUP':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                //dd($selectedPropertyDetails->buildingReffernceLand);
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
               
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;
                $this->data['created_against'] = $selectedPropertyDetails->id;
                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'DC':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $this->activeRevisionYear->id;
                $this->data['rvy_revision_year']    = $this->activeRevisionYear->rvy_revision_year;
                $this->data['rvy_revision_code']    = $this->activeRevisionYear->rvy_revision_code;
                $this->data['rp_property_code']    = $selectedPropertyDetails->rp_property_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_td_no_lref'] = $selectedPropertyDetails->rp_td_no_lref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_suffix_lref'] = $selectedPropertyDetails->rp_suffix_lref;
                $this->data['rp_oct_tct_cloa_no_lref'] = $selectedPropertyDetails->rp_oct_tct_cloa_no_lref;
                $this->data['rpo_code_lref'] = $selectedPropertyDetails->rpo_code_lref;
                $this->data['land_owner'] = (isset($selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->buildingReffernceLand->propertyOwner->standard_name:'';
                $this->data['land_location'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street))?$selectedPropertyDetails->buildingReffernceLand->rp_location_number_n_street:'';
                $this->data['rp_cadastral_lot_no_lref'] = (isset($selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no))?$selectedPropertyDetails->buildingReffernceLand->rp_cadastral_lot_no:'';
                $this->data['rp_total_land_area'] = ($selectedPropertyDetails->buildingReffernceLand != null)?$selectedPropertyDetails->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):'';
                      

                $this->data['bk_building_kind_code'] = $selectedPropertyDetails->bk_building_kind_code;
                $this->data['pc_class_code'] = $selectedPropertyDetails->pc_class_code;
                $this->data['rp_bulding_permit_no'] = $selectedPropertyDetails->rp_bulding_permit_no;
                $this->data['permit_id'] = $selectedPropertyDetails->permit_id;
                $this->data['is_manual_permit'] = $selectedPropertyDetails->is_manual_permit;
                $this->data['rp_building_age'] = $selectedPropertyDetails->rp_building_age;
                $this->data['rp_building_no_of_storey'] = $selectedPropertyDetails->rp_building_no_of_storey;
                $this->data['rp_constructed_month'] = $selectedPropertyDetails->rp_constructed_month;
                $this->data['rp_constructed_year'] = $selectedPropertyDetails->rp_constructed_year;
                $this->data['rp_occupied_month'] = $selectedPropertyDetails->rp_occupied_month;
                $this->data['rp_occupied_year'] = $selectedPropertyDetails->rp_occupied_year;
                $this->data['rp_building_name'] = $selectedPropertyDetails->rp_building_name;
                $this->data['rp_building_completed_year'] = $selectedPropertyDetails->rp_building_completed_year;
                $this->data['rp_building_completed_percent'] = $selectedPropertyDetails->rp_building_completed_percent;
                $this->data['rp_building_cct_no'] = $selectedPropertyDetails->rp_building_cct_no;
                $this->data['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                $this->data['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;
                $this->data['rp_building_unit_no'] = $selectedPropertyDetails->rp_building_unit_no;
                $this->data['rp_depreciation_rate'] = $selectedPropertyDetails->rp_depreciation_rate;
                $this->data['rp_accum_depreciation'] = $selectedPropertyDetails->rp_accum_depreciation;
                $this->data['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                $this->data['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                $this->data['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;
                $this->data['rp_app_taxability'] = $selectedPropertyDetails->rp_app_taxability;
                $buildingstructuredata = [
                'id'                       => null,
                'rbf_building_roof_desc1'  => $selectedPropertyDetails->rbf_building_roof_desc1,
                'rbf_building_roof_desc2' => $selectedPropertyDetails->rbf_building_roof_desc2,
                'rbf_building_roof_desc3' => $selectedPropertyDetails->rbf_building_roof_desc3,
                'rbf_building_floor_desc1' => $selectedPropertyDetails->rbf_building_floor_desc1,
                'rbf_building_floor_desc2' => $selectedPropertyDetails->rbf_building_floor_desc2,
                'rbf_building_floor_desc3' => $selectedPropertyDetails->rbf_building_floor_desc3,
                'rbf_building_wall_desc1' => $selectedPropertyDetails->rbf_building_wall_desc1,
                'rbf_building_wall_desc2' => $selectedPropertyDetails->rbf_building_wall_desc2,
                'rbf_building_wall_desc3' =>$selectedPropertyDetails->rbf_building_wall_desc3
                
            ];
            session()->forget('buildingstructuredata');
            session()->put('buildingstructuredata', $buildingstructuredata);
            session()->put('buildingSelectedBrgy',$selectedPropertyDetails->brgy_code_id); 
                $data = (object)$this->data;
                $data->buildingReffernceLand = $selectedPropertyDetails->buildingReffernceLand;
                $data->property_admin_details = $selectedPropertyDetails->property_admin_details;
                $data->property_owner_details = $selectedPropertyDetails->property_owner_details;
            }else{
                $this->data['rvy_revision_year_id'] = $this->activeRevisionYear->id;
                $this->data['rvy_revision_year'] = $this->activeRevisionYear->rvy_revision_year;
                $this->data['rvy_revision_code'] = $this->activeRevisionYear->rvy_revision_code;
                $data = (object)$this->data;
            }
            
            break;


            default:
            $this->data['rvy_revision_year_id'] = $this->activeRevisionYear->id;
            $this->data['rvy_revision_year'] = $this->activeRevisionYear->rvy_revision_year;
            $this->data['rvy_revision_code'] = $this->activeRevisionYear->rvy_revision_code;
            $data = (object)$this->data;
        }

        return $data;
    }
  
    public function anootationSpeicalPropertystatus(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $propertyStatus = [];
        foreach ($this->_rptpropertybuilding->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        } 
        $appraisers  = $this->arremployees;
        $profile = $this->arrprofile;
        if($propertyId != 0){
            $propertyData = RptPropertyStatus::where('rp_code',$propertyId)->first();
            
            if($propertyData != null){
                $propertyStatus = $propertyData;
                //dd($propertyStatus);
            }
        }else{
            $propertyStatus = (object)$request->session()->get('propertyStatusForBuilding');
        }
        //dd($propertyStatus);
        return view('rptbuilding.ajax.annotationpropertystatus',compact('profile','appraisers','propertyStatus','propertyId'));
    }

    public function swornStatment(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $landPropId = ($request->has('landprpid'))?$request->landprpid:0;
        $landPropDetails = [];
        $propertyStatus = [];
        $propDetails  = [];
        foreach ($this->_rptpropertybuilding->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        } 
        if($landPropId != 0){
            $propDetails = RptProperty::with('landAppraisals')->where('id',$landPropId)->first();
        }
        foreach ($this->_rptpropertybuilding->getctoCashier() as $val) {
            $this->arrOrnumber[$val->id]=$val->or_no;
        } 
        foreach ($this->_rptpropertybuilding->getEmployee() as $val) {
            if($val->suffix){
              $this->hremployee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
            }
            else{
                $this->hremployee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }
        $employee = $this->hremployee;
        $orData = $this->arrOrnumber;
        $appraisers  = $this->arremployees;
        $profile = $this->arrprofile;
        if($propertyId != 0){
            $propertyData = RptPropertySworn::where('rp_code',$propertyId)->first();
            
            if($propertyData != null){
                $propertyStatus = $propertyData;
                //dd($propertyStatus);
            }
        }else{
            $propertyStatus = (object)$request->session()->get('propertySwornStatementBuilding');
        }
        //dd($propertyStatus);
        return view('rptbuilding.ajax.swornstatement',compact('profile','orData','employee','appraisers','propertyStatus','propertyId','propDetails'));
    }

    public function loadPropertyAnnotations(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $propertyAnnotations = [];
        if($propertyId != 0){
            $propertyAnnotations = RptPropertyAnnotation::where('rp_code',$propertyId)->get();
        }else{
            $propertyAnnotations = (object)$request->session()->get('propertyAnnotationForBuilding');
        }
        //dd($propertyAnnotations);
        return view('rptbuilding.ajax.annotations',compact('propertyAnnotations'));
    }

    public function loadAssessementSummary(Request $request){
        $sessionData = collect($request->session()->get('floorValuesBuilding'));
        //dd($sessionData);
        $id = $request->input('id');
        $dep = $request->input('depRate');
        $depDetails = [];
        if($id == 0){
            $landApprasals = $sessionData;
        }else{
            $landApprasals = RptBuildingFloorValue::where('rp_code',$id)->get();
        }
        $newAssesmentSummary    = [];
        if(!$landApprasals->isEmpty()){
        $response = $this->updateAssValueBasedDepRate($request->id,$request->class,$request->brgy,$request->revisionYear,$request->depRate);
        $depDetails = (object)$response;
            $actualUseDetails = RptPropertyActualUse::find((isset($landApprasals[0]))?$landApprasals[0]->pau_actual_use_code:0);
            $rawSummary = [
                'property_kind'                   => 'Building',
                'actualUse'                       => (isset($actualUseDetails->pau_actual_use_desc))?$actualUseDetails->pau_actual_use_desc:'',
                'actualUseId'                     => (isset($actualUseDetails->id))?$actualUseDetails->id:''
            ];
            if($id > 0){
                $propDetails = RptProperty::find($id);
                $rawSummary['adjustedDepreciatedMarketValue'] = $propDetails->rpb_accum_deprec_market_value;
                $rawSummary['AssesseMentLevel'] = $propDetails->al_assessment_level;
                $rawSummary['assessedValue'] = $propDetails->rpb_assessed_value;
            }else{
                $rawSummary['adjustedDepreciatedMarketValue'] = $response['rpb_accum_deprec_market_value'];
                $rawSummary['AssesseMentLevel'] = $response['al_assessment_level'];
                $rawSummary['assessedValue'] = $response['rpb_assessed_value'];
            }
            $newAssesmentSummary[] = $rawSummary;
        }
        $view = view('rptbuilding.ajax.assessementsummary',compact('newAssesmentSummary','depDetails'))->render();
        echo $view;
    }

    public function updateAssValueBasedDepRate($id, $class, $brgy, $revisionYear, $depRate){
        $sessionData = collect(session()->get('floorValuesBuilding'));
        if($id == 0){
            $landApprasals = $sessionData;
        }else{
            $landApprasals = RptBuildingFloorValue::where('rp_code',$id)->get();
        }
        $totalMarketValue = $landApprasals->sum('rpbfv_total_floor_market_value');
        $assLevel = 0;
        $request = new Request;
        $request->replace([
                'propertyKind' => config('constants.rptKinds.B'),
                'propertyClass' => $class,
                'propertyActualUseCode' => (isset($landApprasals[0]))?$landApprasals[0]->pau_actual_use_code:0,
                'propertyRevisionYear' => $revisionYear,
                'totalMarketValue'     => $totalMarketValue,
                'barangay'             => $brgy  

            ]);
        $response = $this->_rptpropertybuilding->getAssessementLevel($request);
        if($response != false){
            if(!$response->assessementRelations->isEmpty()){
                $ass = $response->assessementRelations;
                $assLevel = $ass[0]->assessment_level;
            }
           }
        $depValue         = ($depRate*$totalMarketValue)/100;
        $accumulatedValue = $totalMarketValue-$depValue; 
        $assessedValue    = ($accumulatedValue*$assLevel)/100;
        $dataToUpdate = [
            'rp_depreciation_rate' => ($depRate > 0)?$depRate:0,
            'rp_accum_depreciation' => $depValue,
            'rpb_accum_deprec_market_value' => $accumulatedValue,
            'al_assessment_level' => $assLevel,
            'rpb_assessed_value'  => $assessedValue
        ];
        foreach ($landApprasals as $key => $floorValue) {
            $totalMarketValueFloor = $floorValue->rpbfv_total_floor_market_value;
            $depValueFloor         = ($depRate*$totalMarketValueFloor)/100;
            $accumulatedValueFloor = $totalMarketValueFloor-$depValueFloor; 
            $assessedValueFloor    = ($accumulatedValueFloor*$assLevel)/100;
            if($id > 0){
                $dataTOUpdateInFloor = [
                    'al_assessment_level' => $assLevel,
                    'rpb_assessed_value'  => $assessedValueFloor
                ];
                $this->_rptpropertybuilding->updateFloorValueDetail($floorValue->id,$dataTOUpdateInFloor);
            }else{
                $floorValue->al_assessment_level = $assLevel;
                $floorValue->rpb_assessed_value  =  $assessedValueFloor;
                session()->put('floorValuesBuilding.'.$key,$floorValue);
            }
        }
        if($id > 0){
            $this->_rptpropertybuilding->updateData($id,$dataToUpdate);
        }
        return $dataToUpdate;
    }

    public function Approve(Request $request){
        $annotation = "";
         $arrUpdateCodes = $this->arrUpdateCodes;
         $ucCode      = $request->updatecode;
         $directCancelUcCOdes = $this->arrUpdateCodesDirectCancel;
         $approvedata = [];
         $arrRevisionYears = array();
         $arrUpdateCodes = $this->arrUpdateCodes;
         $history = [];
         $id = ($request->has('id'))?$request->id:'';
         $propertyDetails = [];
         foreach ($this->_rptpropertybuilding->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->rpo_first_name.' '.$val->rpo_custom_last_name;
        } 
        
        $profile = $this->arrprofile;
         if($request->has('id') && $request->id != '0'){
            $approvelFormData = $this->_rptpropertybuilding->getApprovalFormDetails($request->id);
            //dd($request->id);
            $propertyDetails  = RptProperty::find($approvelFormData->rp_code);
            //dd($propertyDetails);
            if($approvelFormData != null){
                $history          = $this->_propertyHistory->with([
                'activeProp.revisionYearDetails',
                'cancelProp.revisionYearDetails',
                'activeProp.barangay',
                'cancelProp.barangay',
                'cancelProp.propertyOwner',
                'cancelProp.floorValues.actualUses'
            ])->where('rp_property_code',$approvelFormData->rp_property_code)->get();
            }else{
                $history = [];
            }
         $annotationData   = DB::table('rpt_property_annotations')->select(DB::raw('GROUP_CONCAT(rpa_annotation_desc SEPARATOR"; ") as annotation'))->where('rp_code',$approvelFormData->rp_code)->first();
         $annotation = (isset($annotationData->annotation))?'"'.$annotationData->annotation.'"':''; 
            
         }else{
             $approvelFormData = $request->session()->get('approvalFormDataBuilding');
             $annotationData   = (session()->has('propertyAnnotationForBuilding'))?session()->get('propertyAnnotationForBuilding'):'';
            if(!empty($annotationData)){
                $annoCollection = collect($annotationData)->pluck('rpa_annotation_desc')->toArray();
                $annotation     = '"'.implode("; ",$annoCollection).'"';
                
            }
         }
         //dd($history);
         $appraisers  = $this->arremployees;
		 //print_r($appraisers);die;
		 
         $allTds     = $this->_rptpropertybuilding->getApprovalFormTds('B',$request->id);
         //dd($approvelFormData);
        return view('rptbuilding.approval',compact('arrRevisionYears','approvedata','appraisers','arrUpdateCodes','approvelFormData','ucCode','profile','arrUpdateCodes','allTds','history','id','propertyDetails','directCancelUcCOdes','annotation'));
    }
    public function deleteFloorValue(Request $request){
            $id = $request->input('id');
            if($request->has('sessionId') && $request->sessionId != ''){
               $request->session()->forget('floorValuesBuilding.'.$request->sessionId);
               $savedLandApprSessionData = $request->session()->get('floorValuesBuilding');
               $seesionCount = collect($savedLandApprSessionData)->count();
                foreach ($savedLandApprSessionData as $key=>$sessionSingle) {
                    $savedLandApprSessionData[$key]->rpbfv_total_floor = $seesionCount;
                }
                $request->session()->put('floorValuesBuilding', $savedLandApprSessionData);
               return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
            }else{
                $rptPlantTreeAppraisal = RptBuildingFloorValue::find($id);
            if($rptPlantTreeAppraisal != null){
                if($this->_rptpropertybuilding->checkToVerifyPsw($rptPlantTreeAppraisal->rp_code)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswBuilding');
                try {
                    $rptPlantTreeAppraisal->delete();
                    $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($rptPlantTreeAppraisal->rp_code);
                    
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            }
    }

    public function deleteAnnotaion(Request $request){
       
            $id = $request->input('id');
            if($request->has('sessionId') && $request->sessionId != ''){
                //dd($request->session()->get('landAppraisals'));
               $request->session()->forget('propertyAnnotationForBuilding.'.$request->sessionId);
               return response()->json(['status' => __('success'), 'msg' => 'Property Annotation delete successfully!']);
            }else{
                $rptPlantTreeAppraisal = RptPropertyAnnotation::find($id);
            if($rptPlantTreeAppraisal != null){
                try {
                    $rptPlantTreeAppraisal->delete();
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            }
    }

    public function fllorvalue (Request $request){
        $view = view('rptbuilding.ajax.floorvalue.index')->render();
        echo $view;
    }

    public function fllorValueDepreciation(Request $request){
        $sessionData = collect($request->session()->get('floorValuesBuilding'));
        $id   = ($request->has('id'))?$request->id:'';
        $actualUse = ($request->has('actualUse'))?$request->actualUse:'';
        $depreCiation = ($request->has('depreciation'))?$request->depreciation:'';
        $floorValues = collect([]);
        if($id == 0){
            $floorValues = $sessionData;
        }else{
            $floorValues = RptBuildingFloorValue::with('additionalItems')->where('rp_code',$id)->where('pau_actual_use_code',$actualUse)->get();
            
        }
        $view = view('rptbuilding.ajax.floorvalue.floorvaluedepreciation',compact('floorValues','depreCiation'))->render();
        echo $view;
    }

    public function getFloorValues(Request $request){
        $id = ($request->has('id'))?$request->id:0;
        $floorValues = [];
        if($id != 0){
            $floorValues = RptBuildingFloorValue::where('rp_code',$id)->get();
        }else{
            $sessionData = collect($request->session()->get('floorValuesBuilding'));
            if(!$sessionData->isEmpty()){
                $floorValues = $sessionData;
            }
        }
        $view = view('rptbuilding.ajax.floorvalue.listing',compact('floorValues'))->render();
        echo $view;
    }

    public function showFloorValueForm(Request $request){
        $arrPropertyTypes = [''=>'Select Struc. Type'];
        $landAppraisal = (object)[];
        $arrActualUsesCodes =  [''=>'Select Actual Use'];
        $sessionId = '';
        $propertyCode = [];
        $addiItems    = $this->arrAddItems;
        $floorCount   = collect($request->session()->get('floorValuesBuilding'))->count()+1;
        $landAppraisal->rpbfv_floor_no = $floorCount;
        $landAppraisal->rpbfv_total_floor = $floorCount;
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptBuildingFloorValue::with('additionalItems')->where('id',$request->id)->first();
            $propertyCode = RptProperty::find($landAppraisal->rp_code);
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisal = $request->session()->get('floorValuesBuilding.'.$request->sessionId);
            $sessionId = $request->sessionId;
        }
        if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == null){
            $propertyCode = RptProperty::with('floorValues')->where('id',$request->property_id)->first();
            $floorCount   = $propertyCode->floorValues->count()+1;
            $landAppraisal->rpbfv_floor_no = $floorCount;
            $landAppraisal->rpbfv_total_floor = $floorCount;
        }
        foreach ($this->_rptpropertybuilding->getPropertyActualUse($request->classId) as $val) {
                $arrActualUsesCodes[$val->id]=$val->pau_actual_use_code.' - '.$val->pau_actual_use_desc;
            }
        foreach ($this->_rptpropertybuilding->getBuildingTypes($request->buildingkind, $this->activeRevisionYear) as $val) {
                $arrPropertyTypes[$val->id]=$val->bt_building_type_code.' - '.$val->bt_building_type_desc;
            }    
        $view = view('rptbuilding.ajax.floorvalue.addfloorvalues',compact('arrPropertyTypes','landAppraisal','arrActualUsesCodes','sessionId','propertyCode','addiItems'))->render();

        echo $view;
    }

    public function setDataForUpdateCodeFunctionality($request){
        
        $inputData = [
            'oldpropertyid' => $request->oldpropertyid,
            'updateCode'    => $request->updateCode,
            'propertykind'  => $request->propertykind
        ];
        $request->session()->forget('floorValuesBuilding');
        $selectedProperty = $this->_rptpropertybuilding->with([
            'floorValues.additionalItems',
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner'
        ])->where('id',$request->oldpropertyid)
        ->where('pk_is_active',1)->get()->first();

        if($selectedProperty == null){
            return [
            'status'=>'error',
            'msg'=>'Cannot do Transfer of Ownership for cancelled Tax Declaration!'
        ];
        }
        $buildingstructuredata = [
                'id'                       => null,
                'rbf_building_roof_desc1'  => $selectedProperty->rbf_building_roof_desc1,
                'rbf_building_roof_desc2' => $selectedProperty->rbf_building_roof_desc2,
                'rbf_building_roof_desc3' => $selectedProperty->rbf_building_roof_desc3,
                'rbf_building_floor_desc1' => $selectedProperty->rbf_building_floor_desc1,
                'rbf_building_floor_desc2' => $selectedProperty->rbf_building_floor_desc2,
                'rbf_building_floor_desc3' => $selectedProperty->rbf_building_floor_desc3,
                'rbf_building_wall_desc1' => $selectedProperty->rbf_building_wall_desc1,
                'rbf_building_wall_desc2' => $selectedProperty->rbf_building_wall_desc2,
                'rbf_building_wall_desc3' =>$selectedProperty->rbf_building_wall_desc3
                
            ];
            $request->session()->forget('buildingstructuredata');
            $request->session()->put('buildingstructuredata', $buildingstructuredata);    
        foreach ($selectedProperty->floorValues as $key=>$value) {
            //dd($value);
            $plantsTreeApprasals = $value->additionalItems;
            $plantTreeAppForLand = [];
            foreach ($plantsTreeApprasals as $plantTree) {
                $plantsTreeApp = [
                'id'                => null,
                'rp_code'           => null,
                'rpbfv_code'          => null,
                'bei_extra_item_code' => $plantTree->bei_extra_item_code,
                'bei_extra_item_desc' => $plantTree->bei_extra_item_desc,
                'rpbfai_total_area' => $plantTree->rpbfai_total_area,
                'rpbfai_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $plantTreeAppForLand[] = (object)$plantsTreeApp;
            }
            $dataToSave = [
                'id'                => null,
                'rp_code'           => null,
                'rp_property_code'     => $selectedProperty->rp_property_code,
                'rpbfv_floor_no' => $value->rpbfv_floor_no,
                'rpbfv_total_floor' => $value->rpbfv_total_floor,
                'bt_building_type_code' => $value->bt_building_type_code,
                'pau_actual_use_code' => $value->pau_actual_use_code,
                'rpbfv_floor_unit_value' => $value->rpbfv_floor_unit_value,
                'rpbfv_floor_area' => $value->rpbfv_floor_area,
                'rpbfv_floor_base_market_value'   => $value->rpbfv_floor_base_market_value,
                'rpbfv_floor_additional_value'   => $value->rpbfv_floor_additional_value,
                'rpbfv_floor_adjustment_value'   => $value->rpbfv_floor_adjustment_value,
                'rpbfv_total_floor_market_value'    => $value->rpbfv_total_floor_market_value,
                'al_assessment_level'      => $value->al_assessment_level,
                'rpb_assessed_value' =>$value->rpb_assessed_value,
                'bt_building_type_code_desc' => $value->bt_building_type_code_desc,
                'pau_actual_use_code_desc'   => $value->pau_actual_use_code_desc,
                'rpbfv_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'additionalItems'=> $plantTreeAppForLand
            ];
            $savedLandApprSessionData = $request->session()->get('floorValuesBuilding');
            $savedLandApprSessionData[] = (object)$dataToSave;
            $request->session()->put('floorValuesBuilding', $savedLandApprSessionData);
            if($request->updateCode == config('constants.update_codes_land.DUP')){
                //dd($selectedProperty->propertyApproval);
                $dataToSaveInApprovalForm = [
            'rp_app_appraised_by' => $selectedProperty->propertyApproval->rp_app_appraised_by,
            //'rp_app_appraised_date' => $selectedProperty->propertyApproval->rp_app_appraised_date,
            'rp_app_appraised_date' => date("Y-m-d"),
            'rp_app_appraised_is_signed' => $selectedProperty->propertyApproval->rp_app_appraised_is_signed,
            'rp_app_taxability' => $selectedProperty->rp_app_taxability,
            'rp_app_recommend_by' => $selectedProperty->propertyApproval->rp_app_recommend_by,
            //'rp_app_recommend_date' => $selectedProperty->propertyApproval->rp_app_recommend_date,
            'rp_app_recommend_date' => date("Y-m-d"),
            'rp_app_recommend_is_signed' => $selectedProperty->propertyApproval->rp_app_recommend_is_signed,
            'rp_app_effective_year' => $selectedProperty->rp_app_effective_year,
            'rp_app_effective_quarter' => $selectedProperty->rp_app_effective_quarter,
            'rp_app_approved_by' => $selectedProperty->propertyApproval->rp_app_approved_by,
           // 'rp_app_approved_date' => $selectedProperty->propertyApproval->rp_app_approved_date,
            'rp_app_approved_date' => date("Y-m-d"),
            'rp_app_approved_is_signed' => $selectedProperty->propertyApproval->rp_app_approved_is_signed,
            //'rp_app_posting_date' => date("Y-m-d"),
            'rp_app_posting_date' => date("Y-m-d"),
            'rp_app_memoranda' => $selectedProperty->rp_app_memoranda,
            'rp_app_extension_section' => $selectedProperty->rp_app_extension_section,
            'pk_is_active' => $selectedProperty->pk_is_active,
            'rp_app_assessor_lot_no' => $selectedProperty->rp_app_assessor_lot_no,
            'rp_app_cancel_by' => \Auth::user()->creatorId()
        ];
        $request->session()->forget('approvalFormDataBuilding');
        $request->session()->put('approvalFormDataBuilding', (object)$dataToSaveInApprovalForm);

            }

        }
        return [
            'status'=>'success',
            'data'=>$inputData
        ];
    } 

    public function dpFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptbuilding.ajax.dp.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dpFunctionlaitySbubmit (Request $request){
         //dd($request->oldpropertyid);
        $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
                'remarks'    => 'required',
                'approvalformid' => 'required',
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required',
                'approvalformid.required' => 'Approval has null data'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $dataToUpdate = [
            'pk_is_active' => 0,
            'rp_modified_by' => \Auth::User()->creatorId(),
            'updated_at'     => date("Y-m-d H:i:s")
        ];
        $dataToUpdateInApprovalForm = [
            'rp_app_cancel_remarks' => $request->remarks,
            'rp_app_cancel_type' => config('constants.update_codes_land.RE'),
            'rp_app_cancel_is_direct' => 1,
            'rp_app_cancel_date' =>date("Y-m-d")

        ];
        if($request->input('updateCode') == config('constants.update_codes_land.DP')){
            $dataToUpdateInApprovalForm['rp_app_cancel_type'] = config('constants.update_codes_land.DP');
        }
        if($request->input('updateCode') == config('constants.update_codes_land.RF')){
            $dataToUpdateInApprovalForm['rp_app_cancel_type'] = config('constants.update_codes_land.RF');
        }
        if($request->input('updateCode') == config('constants.update_codes_land.DE')){
            $dataToUpdateInApprovalForm['rp_app_cancel_type'] = config('constants.update_codes_land.DE');
        }
        if($request->input('updateCode') == config('constants.update_codes_land.DT')){
            $dataToUpdateInApprovalForm['rp_app_cancel_type'] = config('constants.update_codes_land.DT');
        }
        //dd( $dataToUpdateInApprovalForm);
        $propertyDetails = RptProperty::find($request->oldpropertyid);
        try {
            if($propertyDetails->pk_is_active == 0){
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'This action can not be performed on cancelled property!'

                ]);
            }
            $this->_rptpropertybuilding->updateApprovalForm($request->approvalformid,$dataToUpdateInApprovalForm);
            $this->_rptpropertybuilding->updateData($request->oldpropertyid,$dataToUpdate);
            $this->_rptpropertybuilding->updateAccountReceiaveableDetails($request->oldpropertyid, true);
            if($request->updateCode == config('constants.update_codes_land.DP')){
                $msg = 'Dispute successfully raised for tax declaration #'.$propertyDetails->rp_tax_declaration_no;
            }if($request->updateCode == config('constants.update_codes_land.RE')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' successfully removed!';
            }if($request->updateCode == config('constants.update_codes_land.RF')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' move to Raized By Fire successfully';
            }if($request->updateCode == config('constants.update_codes_land.DE')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' move to Demolished successfully';
            }if($request->updateCode == config('constants.update_codes_land.DT')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' move to Destruction successfully';
            }
            $response = [
                'status' => 'success',
                'msg'    => $msg
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'msg'    => $e->getMessage()
            ];
        }
        
        if($request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1){
            return $response;
        }else{
            return response()->json($response);
        }
        
    }

    public function conditionsForUpdateCodes($oldPropId = '',$updateCode = ''){
        $response = [
            'status' => true,
            'msg'    => '' 
          ];
        $propertyDetails = RptProperty::find($oldPropId);
        if($propertyDetails->uc_code == config('constants.update_codes_land.DUP') && $updateCode != 'CS'){
                    $response['status'] = false; 
                    $response['msg'] = 'You can not use Duplicate Copy, Please use original TD!' ;
                }
        if(isset($propertyDetails->pk_is_active) && $propertyDetails->pk_is_active == 0){
                $response['status'] = false; 
                $response['msg'] = 'Oops, This is cancelled property!' ;
             }
        /* Check Last Payment Year */
        if($updateCode != 'CS' && $propertyDetails->rp_app_effective_year < date("Y")+1){
            $lastPaidTaxYear = $this->_rptpropertybuilding->checkLastPaidTax($oldPropId);
             if($lastPaidTaxYear->lastPaymentYear == null || $lastPaidTaxYear->lastPaymentYear < date("Y")){
                $response['status'] = false; 
                $response['msg'] = 'Please clear your previous dues, then try again!' ;
             }

        }
        /* Check Last Payment Year */

        switch ($updateCode) {
            case 'DUP':
            /* Check Last Payment Year */
        if(isset($propertyDetails->uc_code) && $propertyDetails->uc_code == config('constants.update_codes_land.DUP')){
            $response['status'] = false; 
            $response['msg'] = 'You can not make Duplcate Copy of already Duplicated record!' ;
        }
        $checkForExistDupCopy = DB::table('rpt_properties')
                                ->where('created_against',$oldPropId)
                                ->where('uc_code',config('constants.update_codes_land.DUP'))
                                ->where('pk_is_active',1)
                                ->first();
        if($checkForExistDupCopy != null){
            $response['status'] = false; 
            $response['msg'] = 'Duplicate copy already exists!' ;
        }
                break;
                case 'CS':
            $sessionData = session()->get('buildTaxDeclarationForConsolidation');
            if($sessionData == null || empty($sessionData) || count($sessionData) < 2){
                $response['status'] = false; 
                $response['msg'] = 'At least two tax declarations needed for consolidation!' ;
            }
            $tdErrors = [];
            $lastPaymentYear = [];
            foreach ($sessionData as $key => $value) {
                $propDetails = RptProperty::find($value);
                 if($propDetails->rp_app_effective_year < date("Y")+1){
                    $lastPaidTaxYear = $this->_rptpropertybuilding->checkLastPaidTax($value);
                    if($lastPaidTaxYear->lastPaymentYear == null || $lastPaidTaxYear->lastPaymentYear < date("Y")){
                        $lastPaymentYear[] = $lastPaidTaxYear;
                    }
                 }
                if($propDetails->uc_code == config('constants.update_codes_land.DUP')){
                    $checkForExistDupCopy = DB::table('rpt_properties')
                                ->where('id',$propDetails->created_against)
                                ->where('pk_is_active',1)
                                ->first();
                }
                if(isset($checkForExistDupCopy) && $checkForExistDupCopy != null){
                    $tdErrors[] = $propDetails->rp_tax_declaration_no;
                }     
            }
            if(!empty($tdErrors)){
                $response['status'] = false; 
                $response['msg'] = 'TD #'.implode(", ",$tdErrors).' already have active duplicate copies, Please cancel one to go agead!' ;
            }if(!empty($lastPaymentYear)){
                $response['status'] = false; 
                $response['msg'] = 'Please clear your previous dues, then try again!' ;
            }
            break;

            case 'SD':
            $subdividedTds = $this->_rptpropertybuilding->where('created_against',$oldPropId)->get();
            if($subdividedTds->isEmpty() || $subdividedTds->count() < 2){
                $response['status'] = false; 
                $response['msg'] = 'At least two tax declarations needed for Subdivision!' ;
            }
            break;
            
            default:
             
                break;
        }
        return $response;
    }

    public function trFunctionlaitySbubmit (Request $request){
        //dd($request->oldpropertyid);
        $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['msg'] = 'Something is missing, we can not move forward, Please try again or contact admin!';
            return response()->json($arr);
        }
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'TR');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
        
    }

    public function ssdFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptbuilding.ajax.ssd.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function ssdFunctionlaitySbubmit (Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['msg'] = 'Something is missing, we can not move forward, Please try again or contact admin!';
            return response()->json($arr);
        }
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'SSD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
        
    }

    public function rcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptbuilding.ajax.rc.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function rcFunctionlaitySbubmit (Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['msg'] = 'Something is missing, we can not move forward, Please try again or contact admin!';
            return response()->json($arr);
        }
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'SSD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
        
    }

    public function pcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptbuilding.ajax.pc.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function pcFunctionlaitySbubmit (Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['msg'] = 'Something is missing, we can not move forward, Please try again or contact admin!';
            return response()->json($arr);
        }
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'SSD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
        
    }

    public function csFunctionlaity(Request $request){
        $request->session()->forget('buildTaxDeclarationForConsolidation');
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        $savedtaxDecSessionData = $request->session()->get('buildTaxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->selectedproperty, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->selectedproperty;
        }
        
        $request->session()->put('buildTaxDeclarationForConsolidation', $savedtaxDecSessionData);
        //$allTds     = $this->_rptpropertybuilding->getApprovalFormTds('B');
        $view = view('rptbuilding.ajax.cs.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function loadTaxDeclToConsolidate(){
        $sessionData = session()->get('buildTaxDeclarationForConsolidation');
        //dd($sessionData);
        $taxDeclarations = [];
        if($sessionData != null && !empty($sessionData)){
            $taxDeclarations = RptProperty::with([
                'landAppraisals.rptProperty.propertyOwner',
                'landAppraisals.class'
            ])->whereIn('id',array_unique($sessionData))
            ->where('pk_is_active',1)
            ->where('is_deleted',0)
            ->get();
            //dd($taxDeclarations);  rp_td_no
        }
        $view = view('rptbuilding.ajax.cs.listing',compact('taxDeclarations'))->render();
        echo $view;
    }

    public function addTaxDeclarationInList(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'id'=>'required',
                'propertykind' => 'required',
                'revisionYear' => 'required',
                'barabgy'      => 'required'
            ],
            [
                'id.required' => 'Required Field',
                'propertykind.required' => 'Required Field',
                'revisionYear.required' => 'Required Field',
                'barabgy.required'      => 'Required Field'
            ]
        );
         $validator->after(function ($validator) {
            $data = $validator->getData();
            //dd($data);
                $oldPropertyData = RptProperty::where('id',$data['id'])->where('rvy_revision_year_id',$data['revisionYear'])
                                                ->where('brgy_code_id',$data['barabgy'])
                                                ->where('pk_id',$data['propertykind'])
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                /*->where('rp_registered_by',\Auth::user()->creatorId())*/
                                                ->first();
                if($oldPropertyData == null){
                    $validator->errors()->add('selectedPropertyId', 'No Td found!');
                }
                $savedtaxDecSessionData   = session()->get('buildTaxDeclarationForConsolidation');
                if(in_array($data['id'], $savedtaxDecSessionData)){
                    $validator->errors()->add('selectedPropertyId', 'This T.D. No. already applied.');
                }
                
                $sessionPropObj           = RptProperty::with('floorValues')->whereIn('id',$savedtaxDecSessionData);
                $actualUsestoCheck        = (isset($oldPropertyData->floorValues[0]->pau_actual_use_code))?$oldPropertyData->floorValues[0]->pau_actual_use_code:'';
                $allFloorValues           = DB::table('rpt_building_floor_values')->whereIn('rp_code',$savedtaxDecSessionData)->pluck('pau_actual_use_code')->toArray();
                $sessionPropDataClass     = $sessionPropObj->pluck('pc_class_code')->toArray();
                $sessionPropDataLandRef   = $sessionPropObj->pluck('rp_code_lref')->toArray();
                $sessionPropDataBuildKind = $sessionPropObj->pluck('bk_building_kind_code')->toArray();
                if(!empty($sessionPropDataClass) && !in_array((isset($oldPropertyData->pc_class_code))?$oldPropertyData->pc_class_code:'',$sessionPropDataClass)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should be of same class!");
                }if(!empty($sessionPropDataLandRef) && !in_array((isset($oldPropertyData->rp_code_lref))?$oldPropertyData->rp_code_lref:'',$sessionPropDataLandRef)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should have same land reference!");
                }if(!empty($sessionPropDataBuildKind) && !in_array((isset($oldPropertyData->bk_building_kind_code))?$oldPropertyData->bk_building_kind_code:'',$sessionPropDataBuildKind)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should have same building kind!");
                }if(!empty($allFloorValues) && !in_array($actualUsestoCheck,$allFloorValues)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should have same Actual Usage!");
                }
    });
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        /* Save Data in session for future use */
        $savedtaxDecSessionData = $request->session()->get('buildTaxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->id, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->id;
        }
        
        $request->session()->put('buildTaxDeclarationForConsolidation', $savedtaxDecSessionData);
        /* Save Data in session for future use */
        return response()->json([
            'status' => 'success',
            'data'   => []
        ]);

    }

    public function csDeleteTaxDeclaration(Request $request){
        $sessionData = session()->get('buildTaxDeclarationForConsolidation');
        if($sessionData != null && !empty($sessionData)){
            $sessionFlipData = array_flip($sessionData);
            $sessionKey      = $sessionFlipData[$request->selectedTaxDeclarationid];
            session()->forget('buildTaxDeclarationForConsolidation.'.$sessionKey);
        }
       return response()->json([
            'status' => 'success',
            'data'   => []
        ]);
    }

    public function csFunctionlaitySbubmit (Request $request){
       $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'CS');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }
       $sessionData = session()->get('buildTaxDeclarationForConsolidation');
       $landAppraisalForNewTaxDeclaration = [];
       $taxDeclarationDetails = RptProperty::with(['floorValues'])->whereIn('id',$sessionData)->get();
       $allLandAppraisals     = [];
       $onlyClassSubCActua    = [];
       foreach ($taxDeclarationDetails as $appraisal) {
           $allLandAppraisals[] = $appraisal->floorValues->toArray();
       }
       
       $allLandAppraisalsCol = collect($allLandAppraisals)->collapse();
       $buildingstructuredata = [
                'id'                       => null,
                'rbf_building_roof_desc1'  => (isset($taxDeclarationDetails[0]->rbf_building_roof_desc1))?$taxDeclarationDetails[0]->rbf_building_roof_desc1:'',
                'rbf_building_roof_desc2' => (isset($taxDeclarationDetails[0]->rbf_building_roof_desc2))?$taxDeclarationDetails[0]->rbf_building_roof_desc2:'',
                'rbf_building_roof_desc3' => (isset($taxDeclarationDetails[0]->rbf_building_roof_desc3))?$taxDeclarationDetails[0]->rbf_building_roof_desc3:'',
                'rbf_building_floor_desc1' => (isset($taxDeclarationDetails[0]->rbf_building_floor_desc1))?$taxDeclarationDetails[0]->rbf_building_floor_desc1:'',
                'rbf_building_floor_desc2' => (isset($taxDeclarationDetails[0]->rbf_building_floor_desc2))?$taxDeclarationDetails[0]->rbf_building_floor_desc2:'',
                'rbf_building_floor_desc3' => (isset($taxDeclarationDetails[0]->rbf_building_floor_desc3))?$taxDeclarationDetails[0]->rbf_building_floor_desc3:'',
                'rbf_building_wall_desc1' => (isset($taxDeclarationDetails[0]->rbf_building_wall_desc1))?$taxDeclarationDetails[0]->rbf_building_wall_desc1:'',
                'rbf_building_wall_desc2' => (isset($taxDeclarationDetails[0]->rbf_building_wall_desc2))?$taxDeclarationDetails[0]->rbf_building_wall_desc2:'',
                'rbf_building_wall_desc3' =>(isset($taxDeclarationDetails[0]->rbf_building_wall_desc3))?$taxDeclarationDetails[0]->rbf_building_wall_desc3:''
                
            ];
            $request->session()->forget('buildingstructuredata');
            $request->session()->put('buildingstructuredata', $buildingstructuredata);  
       
       $request->session()->forget('floorValuesBuilding');
       $i = 1;
       foreach ($allLandAppraisalsCol as $key => $value) {
           $rptProprtyDetails = RptProperty::find($value['rp_code']);
           $tempLandApp = $value;
           //dd($tempLandApp);
           $tempLandApp['rpbfv_floor_no'] = $i;
           $tempLandApp['rp_property_code'] = (isset($allLandAppraisalsCol[0]['rp_property_code']))?$allLandAppraisalsCol[0]['rp_property_code']:'';
           $tempLandApp['rpbfv_total_floor'] = count($allLandAppraisalsCol);
           $tempLandApp['bt_building_type_code_desc'] = (isset($value['building_type']['bt_building_type_desc']))?$value['building_type']['bt_building_type_desc']:'';
           $tempLandApp['pau_actual_use_code_desc'] = (isset($value['actual_uses']['pau_actual_use_desc']))?$value['actual_uses']['pau_actual_use_desc']:'';
         unset($tempLandApp['building_type']);
         unset($tempLandApp['actual_uses']);
         unset($tempLandApp['created_at']);
         unset($tempLandApp['updated_at']);
         $tempLandApp['rpbfv_registered_by'] = \Auth::user()->id;
         $tempLandApp['id']                   = null;
         $tempLandApp['rp_code']              = null;
         $savedLandApprSessionData = $request->session()->get('floorValuesBuilding');
         $savedLandApprSessionData[] = (object)$tempLandApp;
         $request->session()->put('floorValuesBuilding',$savedLandApprSessionData);
        $i++;
       }
      /* dd($taxDeclarationDetails[0]->id);
       dd($request->session()->get('floorValuesBuilding'));*/
       $inputData = [
            'oldpropertyid' => (isset($taxDeclarationDetails[0]->id))?$taxDeclarationDetails[0]->id:$request->oldpropertyid,
            'updateCode'    => $request->updateCode,
            'propertykind'  => $request->propertykind
        ];
       return response()->json([
        'status' => 'success',
        'data'   => $inputData
       ]);
       
    }

    public function sdFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
         //dd($selectedProperty->landAppraisals[0]->appr);
        $view = view('rptbuilding.ajax.sd.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function sdLoadFloorValues(Request $request){
        $assignedFloors   = [];
        $oldProperty      = $request->oldProperty;
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->where('id',$request->oldProperty)->first();
        $newSubDivProp    = $this->_rptpropertybuilding->where('created_against',$oldProperty)->get();
        foreach ($newSubDivProp as $key => $value) {
            if($value->created_against_appraisal != ''){
            $assignedFloorsData = json_decode($value->created_against_appraisal);
            $assignedFloors[]   = $assignedFloorsData;
        }
        }
        $assignedFloors = collect($assignedFloors)->collapse()->toArray();
        $view = view('rptbuilding.ajax.sd.ajax.prevpropfloorvalues',compact('selectedProperty','updateCode','assignedFloors'))->render();
        echo $view;
    }

     public function sdgetListing(Request $request){
        $oldProperty           = ($request->has('oldProperty'))?$request->oldProperty:'';
        $this->_rptpropertybuilding->setTable('rpt_properties');
        //$this->_propertyappraisal->setTable('rpt_property_appraisals');
        $propOwners = [];
        foreach ($this->_rptpropertybuilding->getprofiles() as $val) {
            $propOwners[$val->id]=$val->standard_name;
        }
        $selectedProperty = $this->_rptpropertybuilding->where('created_against',$oldProperty);
        $propIds = $selectedProperty->pluck('id')->toArray();
        /*$landAppraisals = DB::table('rpt_property_appraisals')
            ->join('rpt_property_classes AS class', 'rpt_property_appraisals.pc_class_code', '=', 'class.id')
            ->select('rpt_property_appraisals.*','class.pc_class_code')
            ->whereIn('rp_code',array_values($propIds))->get();*/
           // dd($landAppraisals);
        $selectedProperty =  $selectedProperty->get();
        if($oldProperty != ''){
            if($selectedProperty != null){
                $taxDeclarations = view('rptbuilding.ajax.sd.ajax.subdividedtaxdeclaration',compact('selectedProperty','propOwners'))->render();
                //$appraisals      = view('rptproperty.ajax.sd.ajax.subdividedappraisals',compact('landAppraisals'))->render();
                $reponse = [
                'status' => 'success',
                'view1'    => $taxDeclarations,
                //'view2'    => $appraisals

            ];
            }else{
                $reponse = [
                'status' => 'error',
                'msg'    => 'Something went wrong with data'

            ];
            }
        }else{
            $reponse = [
                'status' => 'error',
                'msg'    => 'Something went wrong with data'

            ];
        }
        
        return response()->json($reponse);
    }

    public function sdUpdateTaxDeclaration(Request $request){
        $tmpTaxDecla = ($request->has('propertyid'))?$request->propertyid:'';
        $selectedOwner = ($request->has('declaredOwner'))?$request->declaredOwner:'';
        $id     = ($request->has('id'))?$request->id:'';
        $landArea     = ($request->has('landArea'))?$request->landArea:'';
        $actionFor     = ($request->has('update'))?$request->update:'';
        $response = [
            'status' => 'error',
            'msg'    => 'Something went wrong!'
        ];
        if($tmpTaxDecla != '' && $selectedOwner != '' && $actionFor != ''){
            if($actionFor == 'taxDecla'){
                $dataToSave = [
                'rpo_code' => $selectedOwner
            ];
            $this->_rptpropertybuilding->updateData($tmpTaxDecla,$dataToSave,'SD');
            $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }

        }
        if($actionFor != '' && $id != '' && $landArea != ''){

                $landAppraisalDetails = DB::table('rpt_property_appraisals')->where('id',$id)->first();
                $calCulatedDta = $this->calculateMarketValueAdjustedMarketVale($landArea,$landAppraisalDetails);
                //dd($calCulatedDta);
                
                $dataToUpdate = [
                    'rpa_total_land_area' => $landArea,
                    'rpa_base_market_value' => $calCulatedDta['baseMarketValue'],
                    'rpa_adjustment_value'  => $calCulatedDta['adjuValue'],
                    'rpa_adjusted_market_value' => $calCulatedDta['adjMarketValue']
                ];
                $this->_rptpropertybuilding->updateLandAppraisalDetail($id,$dataToUpdate,'SD');
                $this->_rptpropertybuilding->calculateLAPpraisalAndUpdate($id);
                $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }
        return response()->json($response);
    }

    public function sdDeleteTaxDeclaration(Request $request){
            $id = $request->input('selectedTaxDeclarationid');
            //dd($id);
            $tempTaxDec = $this->_rptpropertybuilding->where('id',$id);
            if($tempTaxDec != null){
                DB::beginTransaction();
                try {
                    $tempTaxDec->delete();
                    DB::table('rpt_building_floor_values')->where('rp_code',$id)->delete();
                    DB::table('rpt_property_approvals')->where('rp_code',$id)->delete();
                    DB::commit();
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            
    }

    public function sdFunctionlaitySecondStep(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with([
            'landAppraisals'=>function($query) use($request){
                $query->where('id',$request->selectedLandApp);
            },
            'landAppraisals.class',
            'landAppraisals.subClass',
            'landAppraisals.actualUses',
            'plantTreeAppraisals',
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner'
        ])->where('id',$request->oldProperty)->get()->first();
        $selectedLandAppraisal = $request->selectedLandApp;
        $view = view('rptbuilding.ajax.sd.step2',compact('selectedProperty','updateCode','selectedLandAppraisal'))->render();
        echo $view;
    }

    public function loadNewTdFloorValues(Request $request){
        $selectedProperty = $this->_rptpropertybuilding->where('id',$request->id)->get()->first();
        $view = view('rptbuilding.ajax.sd.ajax.subdividedappraisals',compact('selectedProperty'))->render();
        echo $view;
    }

    public function sdFunctionlaitySbubmit(Request $request){
        $response              = ['status'=>'success','msg'=>'Data Inserted'];
        $oldProperty           = ($request->has('oldProperty'))?$request->oldProperty:'';
        $selectedLandAppraisal = ($request->has('selectedLandAppraisal'))?$request->selectedLandAppraisal:'';
        $updatecode            = ($request->has('updatecode'))?$request->updatecode:'';
        $action                = ($request->has('action'))?$request->action:'';

        /* Add new Temp Tax Declaration Starts Here */
        if($action == 'addNewTempTaxDeclaration'){ 
            $alreadyCreatedTdsObj = DB::table('rpt_properties')->where('created_against',$oldProperty);
            $lastSuffix           = $alreadyCreatedTdsObj->orderBy('id','DESC')->first();
            $alreadyCreatedTds    = $alreadyCreatedTdsObj->count();
            //$lastSuffix = $alreadyCreatedTds;
            if(isset($lastSuffix->rp_pin_suffix)){
                $lastSuffix = (int)str_replace('B','',$lastSuffix->rp_pin_suffix)+1;
            }
            if($oldProperty != '' && !empty($selectedLandAppraisal) != '' && $updatecode != ''){
            $selectedProperty = $this->_rptpropertybuilding->with([
            'floorValues'=>function($query) use($selectedLandAppraisal){
                $query->whereIn('id',$selectedLandAppraisal);
            },
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner',
            'propertyKindDetails'
        ])->where('id',$oldProperty)
            ->where('pk_is_active',1)
            ->get()
            ->first();
        $dataTosave = [
                'created_against' => $oldProperty,
                'created_against_appraisal' => json_encode($selectedLandAppraisal),
                'rp_app_memoranda' => '',
                'rp_app_extension_section' => $selectedProperty->rp_app_extension_section,
                'rp_app_assessor_lot_no' => $selectedProperty->rp_app_assessor_lot_no,
                'rp_app_taxability' => $selectedProperty->rp_app_taxability,
                'rp_app_effective_year' => $selectedProperty->rp_app_effective_year,
                'pk_is_active' => $selectedProperty->pk_is_active,
                'rp_registered_by' => \Auth::user()->id,
                'rp_modified_by' => \Auth::user()->id,
                'is_deleted' =>1,
                'rp_app_posting_date'=>date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
        if($selectedProperty != null){
                try {
                    $dataTosave['rp_administrator_code'] = $selectedProperty->rp_administrator_code;
                    $dataTosave['pk_id'] = $selectedProperty->pk_id;
                    $dataTosave['rp_section_no'] = $selectedProperty->rp_section_no;
                    $dataTosave['rp_pin_no'] = $selectedProperty->rp_pin_no;
                    $dataTosave['rp_pin_suffix'] = ($alreadyCreatedTds == 0)?$selectedProperty->rp_pin_suffix:'B'.$lastSuffix;
                    $dataTosave['rp_suffix'] = $selectedProperty->rp_suffix;
                    $dataTosave['rvy_revision_year_id'] = $selectedProperty->rvy_revision_year_id;
                    $dataTosave['rvy_revision_code'] =$selectedProperty->rvy_revision_code;
                    $dataTosave['rp_property_code'] = $selectedProperty->rp_property_code;
                    $dataTosave['brgy_code_id'] = $selectedProperty->brgy_code_id;
                    $dataTosave['loc_local_code'] = $selectedProperty->loc_local_code;
                    $dataTosave['dist_code'] = $selectedProperty->dist_code;
                    $dataTosave['loc_group_brgy_no'] = $selectedProperty->loc_group_brgy_no;
                    $dataTosave['rp_location_number_n_street'] = $selectedProperty->rp_location_number_n_street;
                    $dataTosave['uc_code'] = $updatecode;
                    $dataTosave['loc_group_brgy_no'] = $selectedProperty->brgy_name;
                    $dataTosave['rp_td_no_lref'] = $selectedProperty->rp_td_no_lref;
                    $dataTosave['rp_code_lref'] = $selectedProperty->rp_code_lref;
                    $dataTosave['rp_suffix_lref'] = $selectedProperty->rp_suffix_lref;
                    $dataTosave['rp_oct_tct_cloa_no_lref'] = $selectedProperty->rp_oct_tct_cloa_no_lref;
                    $dataTosave['rpo_code_lref'] = $selectedProperty->rpo_code_lref;
                    $dataTosave['rp_cadastral_lot_no_lref'] = (isset($selectedProperty->buildingReffernceLand->rp_cadastral_lot_no))?$selectedProperty->buildingReffernceLand->rp_cadastral_lot_no:'';
                    $dataTosave['rp_total_land_area'] = ($selectedProperty->buildingReffernceLand != null)?$selectedProperty->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):''; 
                    $dataTosave['bk_building_kind_code'] = $selectedProperty->bk_building_kind_code;
                    $dataTosave['pc_class_code'] = $selectedProperty->pc_class_code;
                    $dataTosave['rp_bulding_permit_no'] = $selectedProperty->rp_bulding_permit_no;
                    $dataTosave['permit_id'] = $selectedProperty->permit_id;
                    $dataTosave['is_manual_permit'] = $selectedProperty->is_manual_permit;
                    $dataTosave['rp_building_age'] = $selectedProperty->rp_building_age;
                    $dataTosave['rp_building_no_of_storey'] = $selectedProperty->floorValues->count();
                    $dataTosave['rp_constructed_month'] = $selectedProperty->rp_constructed_month;
                    $dataTosave['rp_constructed_year'] = $selectedProperty->rp_constructed_year;
                    $dataTosave['rp_occupied_month'] = $selectedProperty->rp_occupied_month;
                    $dataTosave['rp_occupied_year'] = $selectedProperty->rp_occupied_year;
                    $dataTosave['rp_building_name'] = $selectedProperty->rp_building_name;
                    $dataTosave['rp_building_completed_year'] = $selectedProperty->rp_building_completed_year;
                    $dataTosave['rp_building_completed_percent'] = $selectedProperty->rp_building_completed_percent;
                    $dataTosave['rp_building_cct_no'] = $selectedProperty->rp_building_cct_no;
                    /*$dataTosave['rp_building_gf_area'] = $selectedPropertyDetails->rp_building_gf_area;
                    $dataTosave['rp_building_total_area'] = $selectedPropertyDetails->rp_building_total_area;*/
                    $dataTosave['rp_building_unit_no'] = $selectedProperty->rp_building_unit_no;
                    $dataTosave['rp_depreciation_rate'] = 0;
                    $dataTosave['rp_accum_depreciation'] = 0;
                    /*$dataTosave['rpb_accum_deprec_market_value'] = $selectedPropertyDetails->rpb_accum_deprec_market_value;
                    $dataTosave['al_assessment_level'] = $selectedPropertyDetails->al_assessment_level;
                    $dataTosave['rpb_assessed_value'] = $selectedPropertyDetails->rpb_assessed_value;*/
                    
                $savedId = $this->_rptpropertybuilding->addData($dataTosave,$updatecode);
                //dd($selectedProperty->propertyKindDetails);
                $dataToSaveInApprovalForm = [
                    'rp_code' => $savedId,
                    'rp_property_code' => $selectedProperty->rp_property_code,
                    'pk_code' => $selectedProperty->propertyKindDetails->pk_code,
                    'rp_app_cancel_by' => \Auth::user()->creatorId(),
                    'rp_app_appraised_by' => $selectedProperty->propertyApproval->rp_app_appraised_by,
                    'rp_app_recommend_by' => $selectedProperty->propertyApproval->rp_app_recommend_by,
                    'rp_app_approved_by' => $selectedProperty->propertyApproval->rp_app_appraised_by,
                    'rp_app_appraised_date'=>date('Y-m-d'),
                    'rp_app_recommend_date'=>date('Y-m-d'),
                    'rp_app_approved_date'=>date('Y-m-d'),
                ];
                $this->_rptpropertybuilding->addApprovalForm($dataToSaveInApprovalForm);
                $this->_rptpropertybuilding->generateTaxDeclarationAndPropertyCode($savedId);
                $this->_rptpropertybuilding->generateTaxDeclarationAform($savedId);
                $savedProperty = $this->_rptpropertybuilding->find($savedId);
                $selectedLandAppraisal = $selectedProperty->landAppraisals;
                $i = 1;
                foreach($selectedProperty->floorValues as $build){
                    $tempLandApp = $build->toArray();
                    unset($tempLandApp['id']);
                    unset($tempLandApp['building_type']);
                    unset($tempLandApp['actual_uses']);
                    unset($tempLandApp['bt_building_type_code_desc']);
                    unset($tempLandApp['pau_actual_use_code_desc']);
                    $tempLandApp['rpbfv_floor_no'] = $i;
                    $tempLandApp['rpbfv_total_floor'] = $selectedProperty->floorValues->count();
                    $tempLandApp['rp_property_code'] = $savedProperty->rp_property_code;
                    $tempLandApp['rp_code'] = $savedId;
                    $tempLandApp['rpbfv_registered_by'] = \Auth::user()->id;
                    $tempLandApp['created_at'] = date("Y-m-d H:i:s");
                    $tempLandApp['updated_at'] = date("Y-m-d H:i:s");
                    $lastFloorId = $this->_rptpropertybuilding->addFloorValueDetail($tempLandApp);
                    $this->updateAssValueBasedDepRate($savedProperty->id,$savedProperty->pc_class_code,$savedProperty->brgy_code_id,$savedProperty->rvy_revision_year_id,0);
                    $i++;
                }
                DB::commit();
                    $response['status'] = 'success';
                    $response['msg']    = 'saved successfully';
                } catch (\Exception $e) {
                    DB::rollback();
                    dd($e);
                    $response['status'] = 'error';
                    $response['msg']    = $e->getMessage();
                }
        }else{
            $response['status'] = 'error';
            $response['msg']    = 'Data is missing';
        }
        }else{
            $response['status'] = 'error';
            $response['msg']    = 'Data is missing';
        }
    
       


        return response()->json($response);
    }
     /* Add new Temp Tax Declaration ends Here */

    /* Temp Tax Declaration Subdivision Starts Here */
    if($action == 'taxDeclarationDivisionFinsish'){
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'SD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }
        
        $response = ['status'=>'error','msg'=>''];
        $oldPropertyId     = ($request->has('oldpropertyid'))?$request->oldpropertyid:''; 
        $oldPropertyDetails = $this->_rptpropertybuilding->with([
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner',
            'propertyKindDetails'
        ])->where('id',$oldPropertyId)
          ->where('pk_is_active',1)
          ->get()
          ->first();

        $newtaxsd = count($request->input('newCreatedTaxDeclarationForSd')); 
        $newCreatedTaxDeclarationListForSubMission = [];
        $allLandAppraisalsForOldProperty = $this->_rptpropertybuilding->with([
            'landAppraisals.class',
        ])->where('id',$oldPropertyId)->get()->first();
        $allAppraisals = $allLandAppraisalsForOldProperty->floorValues;
        $oldPropTotalLandArae = $oldPropertyDetails->floorValues->sum('rpbfv_floor_area');
        $oldPropTotalCount    = $oldPropertyDetails->floorValues->count();
        $newCreatedProperties = ($request->has('newCreatedTaxDeclarationForSd') && !empty($request->newCreatedTaxDeclarationForSd))?$request->newCreatedTaxDeclarationForSd:[];
        //dd($newCreatedProperties);
        $newTaxDeclarationDetails = DB::table('rpt_properties')
                                      ->select(DB::raw('SUM(rpt_building_floor_values.rpbfv_floor_area) as newTdsToTalArea'),DB::raw('COUNT(rpt_building_floor_values.id) as totalFloorCount'))

                                      ->join('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rpt_properties.id')
                                      ->whereIn('rpt_properties.id',$newCreatedProperties)->first();
        $newTaxDeclarationTotalLandArea = 0;
        $dataToValidate = $this->data;
        unset($dataToValidate['id']);
        unset($dataToValidate['rp_property_code']);
        unset($dataToValidate['rvy_revision_year']);
        unset($dataToValidate['loc_local_code_name']);
        unset($dataToValidate['rp_tax_declaration_no']);
        unset($dataToValidate['rp_td_no']);
        unset($dataToValidate['dist_code_name']);
        unset($dataToValidate['brgy_code_and_desc']);
        unset($dataToValidate['update_code']);
        unset($dataToValidate['rp_administrator_code_address']);
        unset($dataToValidate['property_owner_address']);
        unset($dataToValidate['rp_suffix']);
        unset($dataToValidate['rp_pin_suffix']);
        $validationError = false;
        $validationErrorFor = [];
        $missingLandAppraisal = false;
        $missingLandAppraisalFor = [];
        $missingApproval = false;
        $missingApprovalFor = [];
        $landAreaNotEqual = false;
        $floorCount = false;
        $landAreaNotEqualfor = [];
        $emptyTaxDeclaration = false;
        $emptyTaxDeclarationFor = [];
        $finalSubmission = false;
        $finalSubmissionFor = [];
        $cancelByTdNoField = [];
        $cancelationType = 0;
        foreach ($allAppraisals as $appraisal) {

            $tacDeclarationsAgainstAppraisal = DB::table('rpt_properties')
            ->whereJsonContains('created_against_appraisal',''.$appraisal->id.'')
            ->where('is_deleted',1)
            ->where('rp_registered_by',\Auth::user()->id);
            //dd($tacDeclarationsAgainstAppraisal->count());
            if($tacDeclarationsAgainstAppraisal->count() == 0){
                $emptyTaxDeclaration = true;
                $emptyTaxDeclarationFor[] = $appraisal->rpbfv_floor_no;
            }
            $landTotalArea = $appraisal->rpbfv_floor_area;
            $newPropertiedTotalLandArea = 0;
            foreach ($tacDeclarationsAgainstAppraisal->get() as $key => $prop) {

            foreach ($prop as $column => $value) {
                //ndd($column);
                if(in_array($column, array_keys($dataToValidate)) && $value == ''){
                    $new[] = $column;
                    $validationError = true;
                    $validationErrorFor[] = '#'.$prop->rp_tax_declaration_no;
                }
            }
            $newCreatedTaxDeclarationListForSubMissionp[] = $prop->id;
            $landAppraisal = DB::table('rpt_building_floor_values')->where('rp_code',$prop->id);
            $landAppraisalList = $landAppraisal->get();
            $approvalForm = DB::table('rpt_property_approvals')->where('rp_code',$prop->id)->get()->first();

            if($landAppraisalList->isEmpty()){
                $missingLandAppraisal = true;
                $missingLandAppraisalFor[] = '#'.$prop->rp_tax_declaration_no;
            }

            if($approvalForm->rp_app_appraised_by == '' || $approvalForm->rp_app_recommend_by == '' || $approvalForm->rp_app_approved_by == ''){
                $missingApproval = true;
                $missingApprovalFor[] = '#'.$prop->rp_tax_declaration_no;
            }
            $singlePropLandArea = 0;
            $newPropertiedTotalLandArea += $singlePropLandArea;
            /* Final Submission of Subdivision */
            if(!$missingApproval && !$missingLandAppraisal && !$landAreaNotEqual  && !$emptyTaxDeclaration){
                $finalSubmission = true;
                $finalSubmissionFor[] = '#'.$prop->rp_tax_declaration_no;
                $cancelByTdNoField[]  = $prop->id;
                /* Update New Property */
                $cancelationType = $prop->uc_code;
                /* Update New Property */

            }
            /* Final Submission of Subdivision */
        }
        
        }
        if($oldPropTotalLandArae != $newTaxDeclarationDetails->newTdsToTalArea){
            $landAreaNotEqual = true;
            $landAreaNotEqualfor[] = '';
        }if($oldPropTotalCount != $newTaxDeclarationDetails->totalFloorCount){
            $floorCount = true;
        }
        if($emptyTaxDeclaration){
            $response['msg'] = 'No New Tax declaration created against #'.implode(', ',array_unique($emptyTaxDeclarationFor)). ' Property, Please create and try again';
            return response()->json($response);
        }
        if($landAreaNotEqual){
            $response['msg'] = "New Tax declarations total floor area is not equal to old tax declaration's floor area, Please check all ned TD and try again";
            return response()->json($response);
        }if($floorCount){
            $response['msg'] = "New Tax declarations total floor values is not equal to old tax declaration's total floor values, Please check all ned TD and try again";
            return response()->json($response);
        }
        /*if($validationError){
            $response['msg'] = 'Important data is missing from new tax declarations '.implode(', ',array_unique($validationErrorFor)).', Please fill all required fields then try again';
            return response()->json($response);
        }*/
        if($missingLandAppraisal){
            $response['msg'] = 'Floor Values is missing for tax declaration '.implode(', ',array_unique($missingLandAppraisalFor));
            return response()->json($response);
        }
        if($missingApproval){
            $response['msg'] = 'Approval is missing for tax declaration '.implode(', ',array_unique($missingApprovalFor));
            return response()->json($response);
        }
        if($finalSubmission){
            $newCreatedTaxDeclarationListForSubMissionpData = DB::table('rpt_properties')->whereIn('id',$newCreatedTaxDeclarationListForSubMissionp)->get();
            
            foreach ($newCreatedTaxDeclarationListForSubMissionpData as $key => $prop) {
                $dataToUpdateInNewProperty = [
                    'is_deleted' => 0,
                    'created_against' => NULL,
                    'created_against_appraisal' => NULL
                ];
                
                $this->_rptpropertybuilding->updateData($prop->id,$dataToUpdateInNewProperty);
               /* if($key == 0){
                    $this->_rptpropertybuilding->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id,'SD');
                }else{
                    $this->_rptpropertybuilding->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id);

                }*/
                $dataToAddInHistory = [
                    'pk_code' => $oldPropertyDetails->propertyKindDetails->pk_code,
                    'rp_property_code' => $oldPropertyDetails->rp_property_code,
                    'rp_code_active' => $prop->id,
                    'rp_code_cancelled' => $oldPropertyDetails->id,
                    'ph_registered_by' => \Auth::user()->creatorId(),
                    'ph_registered_date' => date('Y-m-d H:i:s'),
                ];
                //dd($dataToAddInHistory);
                $this->_rptpropertybuilding->addPropertyHistory($dataToAddInHistory);
            }
            $dataToUpdateInOldProperty = [
                'pk_is_active' => 0,
                'rp_modified_by' => \Auth::User()->creatorId(),
                'updated_at'     => date('Y-m-d H:i:s')
            ];
            $this->_rptpropertybuilding->updateData($oldPropertyDetails->id,$dataToUpdateInOldProperty);
            $dataToUpdateInOldPropertyApproval = [
                'rp_app_cancel_by' => \Auth::User()->creatorId(),
                'rp_app_cancel_type' => $cancelationType,
                'rp_app_cancel_date'     => date('Y-m-d H:i:s'),
                'rp_app_cancel_by_td_id' => implode(',',$cancelByTdNoField)
            ];
            $this->_rptpropertybuilding->updateApprovalForm($oldPropertyDetails->propertyApproval->id,$dataToUpdateInOldPropertyApproval);
            $previousChain = [];
            foreach ($newCreatedTaxDeclarationListForSubMissionpData as $key => $prop) {
                $this->_rptpropertybuilding->updatePinDeclarationNumber($prop->id);
                $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($prop->id);
                if($key == 0){
                    $previoChainQU = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$oldPropertyId)->first();
                    $previousChain = json_decode($previoChainQU->rp_code_chain);
                    $this->_rptpropertybuilding->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id);
                }else{
                    $this->_rptpropertybuilding->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id,'SD',[],$previousChain);
                }
            }
            $response['msg'] = 'Subdivision Completed, New tax declaration created '.implode(', ',array_unique($finalSubmissionFor)). ' against #'.$oldPropertyDetails->rp_tax_declaration_no;
            $response['status'] = 'success';
            return response()->json($response);
        }
        
    }
        /* Temp Tax Declaration Subdivision Ends Here */
    }

    public function trFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptbuilding.ajax.tr.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dupFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertybuilding->with(['floorValues','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptbuilding.ajax.dup.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dupFunctionlaitySbubmit (Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'oldpropertyid'=>'required',
                'updateCode'=>'required',
                'propertykind'=>'required', 
            ],
            [
                'oldpropertyid.required'=>'Required',
                'updateCode.required'=>'Required',
                'propertykind.required'=>'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['msg'] = 'Something is missing, we can not move forward, Please try again or contact admin!';
            return response()->json($arr);
        }
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'DUP');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
        
    }

    public function storeFloorValue(Request $request){
       // dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'rpbfv_floor_no'=>'required',
                'rpbfv_total_floor'=>'required',
                'bt_building_type_code'=>'required', 
                'pau_actual_use_code'=>'required', 
                'rpbfv_floor_area'=>'required',
                'rpbfv_floor_unit_value'=>'required|numeric|min:1', 
                'al_assessment_level'=>'required|numeric',
                'rpbfv_floor_base_market_value'=>'required',
                /*'rpbfv_floor_additional_value'=>'required',
                'rpbfv_floor_adjustment_value'=>'required',*/
                'rpbfv_total_floor_market_value'=>'required'
            ],
            [
                'rpbfv_floor_no.required'=>'Required Field',
                'rpbfv_total_floor.required'=>'Required Field',
                'bt_building_type_code.required'=>'Required Field', 
                'pau_actual_use_code.required'=>'Required Field', 
                'rpbfv_floor_area.required'=>'Required Field',
                'rpbfv_floor_unit_value.required'=>'Not Approved Yet', 
                'rpbfv_floor_unit_value.min'=>'Not Approved Yet',
                'al_assessment_level.required'=>'Not Approved Yet',
                'rpbfv_floor_base_market_value.required'=>'Required Field',
                'rpbfv_floor_additional_value.required'=>'Required Field',
                'rpbfv_floor_adjustment_value.required'=>'Required Field',
                'rpbfv_total_floor_market_value.required'=>'Required Field',
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
            if($data['property_id'] > 0){
                $landApprasals = RptBuildingFloorValue::where('rp_code',$data['property_id'])->get();
            }else{
                $sessionData = collect(session()->get('floorValuesBuilding'));
                $landApprasals = $sessionData;
            }
            
            if(!$landApprasals->isEmpty()){
                $validation = $landApprasals->where('pau_actual_use_code',$data['pau_actual_use_code']);
                //dd($validation);
                if($validation->isEmpty() && $landApprasals->count() > 0){
                    $validator->errors()->add('pau_actual_use_code', 'Should be same to other floor values Actual Use');
                }
            }
        });
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        
        $dataToSave = [
                'id'                => $request->id,
                'rp_code'           => $request->property_id,
                'rpbfv_floor_no' => $request->rpbfv_floor_no,
                'rpbfv_total_floor' => $request->rpbfv_total_floor,
                'bt_building_type_code' => $request->bt_building_type_code,
                'bt_building_type_code_desc' => $request->bt_building_type_code_desc,
                'pau_actual_use_code' => $request->pau_actual_use_code,
                'pau_actual_use_code_desc' => $request->pau_actual_use_code_desc,
                'rpbfv_floor_area' => $request->rpbfv_floor_area,
                'rpbfv_floor_unit_value' => $request->rpbfv_floor_unit_value,
                'rpbfv_floor_base_market_value' => $request->rpbfv_floor_base_market_value,
                'rpbfv_floor_additional_value' =>$request->rpbfv_floor_additional_value,
                'rpbfv_floor_adjustment_value' =>$request->rpbfv_floor_adjustment_value,
                'rpbfv_total_floor_market_value' => $request->rpbfv_total_floor_market_value,
                'al_assessment_level' => $request->al_assessment_level,
                'rpb_assessed_value' => $request->rpb_assessed_value,
                'rpbfv_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
           //dd($dataToSave);
           if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            $rptPropertyDetails = RptProperty::find($request->property_id);
               /* if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }*/
            if($this->_rptpropertybuilding->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswBuilding');    
            $dataToSave['rpbfv_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            unset($dataToSave['bt_building_type_code_desc']);
            unset($dataToSave['pau_actual_use_code_desc']);
            $addItems = $this->extractAdditionalItems($request);
            $this->_rptpropertybuilding->updateFloorValueDetail($request->id,$dataToSave);
            $this->_rptpropertybuilding->updateAccountReceiaveableDetails($request->property_id);
            $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($request->property_id);
            foreach ($addItems as $key => $value) {
                 $addItems[$key]['created_at'] = date('Y-m-d H:i:s');
                 $addItems[$key]['rpbfai_registered_by'] = \Auth::user()->creatorId();
            }
            RptPropertyBuildingFloorAdItem::where('rpbfv_code',$request->id)->delete();
            RptPropertyBuildingFloorAdItem::insert($addItems);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id == null){
            $rptPropertyDetails = RptProperty::find($request->property_id);
                /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }*/
            if($this->_rptpropertybuilding->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswBuilding');      
            $savedProperty = $this->_rptpropertybuilding->getSinglePropertyDetails($request->property_id);
            $dataToSave['rp_property_code'] = $savedProperty->rp_property_code;
            unset($dataToSave['bt_building_type_code_desc']);
            unset($dataToSave['pau_actual_use_code_desc']);
            unset($dataToSave['id']);
            $addItems = $this->extractAdditionalItems($request);
            
            $lastInsertedId = $this->_rptpropertybuilding->addFloorValueDetail($dataToSave);
            $this->_rptpropertybuilding->updateAccountReceiaveableDetails($request->property_id);
            $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($request->property_id);
            foreach ($addItems as $key => $value) {
                 $addItems[$key]['rpbfv_code'] = $lastInsertedId;
                 $addItems[$key]['created_at'] = date('Y-m-d H:i:s');
                 $addItems[$key]['rpbfai_registered_by'] = \Auth::user()->creatorId();
            }
            RptPropertyBuildingFloorAdItem::insert($addItems);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $dataToSave['id']   = null;
                $dataToSave['rp_code']   = null;
                $dataToSave['dataSource']   = 'session';
                $dataToSave['additionalItems'] = $this->extractAdditionalItems($request);
                //dd($dataToSave);
                $savedLandApprSessionData = $request->session()->get('floorValuesBuilding');
               /* $dataToSave['rpbfv_total_floor'] = collect($savedLandApprSessionData)->count()+1;*/
                $savedLandApprSessionData[] = (object)$dataToSave;
                $seesionCount = collect($savedLandApprSessionData)->count();
                foreach ($savedLandApprSessionData as $key=>$sessionSingle) {
                    $savedLandApprSessionData[$key]->rpbfv_total_floor = $seesionCount;
                }
                if($request->has('session_id') && $request->session_id != ''){
                    $getPlantsTreesAdjustmentFactor = $request->session()->get('floorValuesBuilding.'.$request->session_id);
                    //dd($getPlantsTreesAdjustmentFactor);
                    $dataToSave['additionalItems'] = $this->extractAdditionalItems($request);
                    //dd($dataToSave);
                    $request->session()->put('floorValuesBuilding.'.$request->session_id, (object)$dataToSave);
                }else{
                    $request->session()->put('floorValuesBuilding', $savedLandApprSessionData);
                }
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
           


    }

    public function storeSwornStatment(Request $request){
        $validator = \Validator::make(
        $request->all(), [
            'land_market_value' => 'required'
            ],
            [
                'land_market_value.required' => 'Required Field'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        
        $dataToSave = [
            'rps_improvement_value' => ($request->rps_improvement_value != '')?$request->rps_improvement_value:0,
            'rps_person_taking_oath_id' => $request->rps_person_taking_oath_id,
            'rps_person_taking_oath_custom' => $request->rps_person_taking_oath_custom,
            'cashier_id' => $request->cashier_id,
            'cashierd_id' =>$request->cashierd_id,
            'rps_date' => $request->rps_date,
            'rps_ctc_no' =>$request->rps_ctc_no,
            'rps_ctc_issued_date' =>$request->rps_ctc_issued_date,
            'rps_ctc_issued_place' =>$request->rps_ctc_issued_place,
            'rps_administer_official1_type' =>$request->rps_administer_official1_type,
            'rps_administer_official1_id' =>$request->rps_administer_official1_id,
            'rps_administer_official_title1' =>$request->rps_administer_official_title1,
            'rps_administer_official2_type' =>$request->rps_administer_official2_type,
            'rps_administer_official2_id' =>$request->rps_administer_official2_id,
            'rps_administer_official_title2' =>$request->rps_administer_official_title2,
         ];
        if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id != null){
            
            $dataToSave['rps_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptpropertybuilding->updatePropertySwornData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == null){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rps_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertybuilding->addPropertySwornData($dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $request->session()->put('propertySwornStatementBuilding', $dataToSave);
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
    }

    public function storeAnootationSpeicalPropertystatus(Request $request){
        if($request->has('action') && $request->action == 'main_form'){
            $validator = \Validator::make(
            $request->all(), [
                
                'rpss_mortgage_amount' => 'required_if:rpss_is_mortgaged,1',
                'rpss_mortgage_to_code' => 'required_if:rpss_is_mortgaged,1',
                'rpss_mortgage_cancelled' => 'required_if:rpss_is_mortgaged,1',
            ],
            [
                
                'rpss_mortgage_amount.required_if' => 'Required',
                'rpss_mortgage_to_code.required_if' => 'Required',
                'rpss_mortgage_cancelled.required_if' => 'Required',
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        
        $dataToSave = [
            'rpss_mciaa_property' => ($request->has('rpss_mciaa_property'))?$request->rpss_mciaa_property:0,
            'rpss_peza_property' => ($request->has('rpss_peza_property'))?$request->rpss_peza_property:0,
            'rpss_beneficial_use' => ($request->has('rpss_beneficial_use'))?$request->rpss_beneficial_use:0,
            'rpss_beneficial_user_code' => $request->rpss_beneficial_user_code,
            'rpss_beneficial_user_name' =>$request->rpss_beneficial_user_name,
            'rpss_is_mortgaged' =>($request->has('rpss_is_mortgaged'))?$request->rpss_is_mortgaged:0,
            'rpss_is_mortgaged_date' =>$request->rpss_is_mortgaged_date,
            'rpss_is_levy' =>($request->has('rpss_is_levy'))?$request->rpss_is_levy:0,
            'rpss_is_auction' =>($request->has('rpss_is_auction'))?$request->rpss_is_auction:0,
            'rpss_is_protest' =>($request->has('rpss_is_protest'))?$request->rpss_is_protest:0,
            'rpss_is_protest_date' =>$request->rpss_is_protest_date,
            'rpss_mortgage_amount' =>$request->rpss_mortgage_amount,
            'rpss_mortgage_to_code' =>$request->rpss_mortgage_to_code,
            'rpss_mortgage_cancelled' =>$request->rpss_mortgage_cancelled,
            'rpss_mortgage_exec_date' =>$request->rpss_mortgage_exec_date,
            'rpss_mortgage_exec_by'=>$request->rpss_mortgage_exec_by,
            'rpss_mortgage_certified_before'=>$request->rpss_mortgage_certified_before,
            'rpss_mortgage_notary_public_date' =>$request->rpss_mortgage_notary_public_date
            
         ];

         if($request->has('rpss_mciaa_property') && $request->rpss_mciaa_property != 0){
            $dataToSave['rpss_mciaa_property'] = 1;
            $dataToSave['rpss_peza_property'] = 0;
            $dataToSave['rpss_beneficial_use'] = 0;
         }
         if($request->has('rpss_peza_property') && $request->rpss_peza_property != 0){
            $dataToSave['rpss_mciaa_property'] = 0;
            $dataToSave['rpss_peza_property'] = 1;
            $dataToSave['rpss_beneficial_use'] = 0;
         }
         if($request->has('rpss_beneficial_use') && $request->rpss_beneficial_use != 0){
            $dataToSave['rpss_mciaa_property'] = 0;
            $dataToSave['rpss_peza_property'] = 0;
            $dataToSave['rpss_beneficial_use'] = 1;
         }

         if($request->has('rpss_is_mortgaged') && $request->rpss_is_mortgaged != 0){
            $dataToSave['rpss_is_mortgaged'] = 1;
            $dataToSave['rpss_is_levy'] = 0;
            $dataToSave['rpss_is_auction'] = 0;
            $dataToSave['rpss_is_protest'] = 0;
         }
         if($request->has('rpss_is_levy') && $request->rpss_is_levy != 0){
           $dataToSave['rpss_is_mortgaged'] = 0;
            $dataToSave['rpss_is_levy'] = 1;
            $dataToSave['rpss_is_auction'] = 0;
            $dataToSave['rpss_is_protest'] = 0;
         }
         if($request->has('rpss_is_auction') && $request->rpss_is_auction != 0){
            $dataToSave['rpss_is_mortgaged'] = 0;
            $dataToSave['rpss_is_levy'] = 0;
            $dataToSave['rpss_is_auction'] = 1;
            $dataToSave['rpss_is_protest'] = 0;
         }
         if($request->has('rpss_is_protest') && $request->rpss_is_protest != 0){
            $dataToSave['rpss_is_mortgaged'] = 0;
            $dataToSave['rpss_is_levy'] = 0;
            $dataToSave['rpss_is_auction'] = 0;
            $dataToSave['rpss_is_protest'] = 1;
         }

        if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            //dd($dataToSave);
            $dataToSave['rpss_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptpropertybuilding->updatePropertyStatusData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == ''){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rpss_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertybuilding->addPropertyStatusData($dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $request->session()->put('propertyStatusForBuilding', $dataToSave);
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
        }
        if($request->has('action') && $request->action == 'annotation'){
            $validator = \Validator::make(
            $request->all(), [
                
                'rpa_annotation_date_time' => 'required',
                'rpa_annotation_by_code'   => 'required',
                'rpa_annotation_desc'      => 'required',
            ],
            [
                
                'rpa_annotation_date_time.required' => 'Required',
                'rpa_annotation_by_code.required' => 'Required',
                'rpa_annotation_desc.required' => 'Required',
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        
        $dataToSave = [

            'rpa_annotation_date_time' => $request->rpa_annotation_date_time,
            'rpa_annotation_by_code' =>$request->rpa_annotation_by_code,
            'rpa_annotation_desc' =>$request->rpa_annotation_desc,
         ];

        if($request->has('property_id') && $request->property_id > 0){
            $propertyDetails = RptProperty::with('propertyKindDetails')->where('id',$request->property_id)->first();
            $dataToSave['rp_code'] = $request->property_id;
            $dataToSave['pk_code'] = $propertyDetails->propertyKindDetails->pk_code;
            $dataToSave['rpa_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertybuilding->addAnnotationData($dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $getPlantsTreesAdjustmentFactor = $request->session()->get('propertyAnnotationForBuilding');

                $savedLandApprSessionData = $request->session()->get('propertyAnnotationForBuilding');
                $dataToSave['id'] = null;
                $dataToSave['rp_code'] = null;
                $savedLandApprSessionData[] = (object)$dataToSave;
                $request->session()->put('propertyAnnotationForBuilding', $savedLandApprSessionData);
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }

        }

    }

    public function storeApprove(Request $request){
        //dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'rp_app_appraised_by'=>'required',
                'rp_app_appraised_date'=>'required',
                'rp_app_taxability'=>'required', 
                'rp_app_recommend_by'=>'required',
                'rp_app_recommend_date'=>'required',
                'rp_app_effective_year'=>'required',
                //'rp_app_effective_quarter'=>'required', 
                'rp_app_approved_by'=>'required', 
                'rp_app_approved_date'=>'required',
                'uc_code' => 'required',
                'rp_app_posting_date' => 'required',
                'rp_app_memoranda' => 'required',
                /*'rp_app_extension_section' => 'required',
                'rp_app_assessor_lot_no'   => 'required',*/
                'pk_is_active'   => 'required',
                'rp_app_cancel_type' => 'required_if:rp_app_cancel_is_direct,1',
                /*'rp_app_cancel_by_td_id' => 'required_if:rp_app_cancel_is_direct,1',*/
                'rp_app_cancel_remarks' => 'required_if:rp_app_cancel_is_direct,1',
                'rp_app_cancel_date' => 'required_if:rp_app_cancel_is_direct,1',
            ],
            [
                'rp_app_posting_date.required' => 'Required',
                'rp_app_appraised_by.required'=>'Required',
                'rp_app_appraised_date.required'=>'Required',
                'rp_app_taxability.required'=>'Required', 
                'rp_app_recommend_by.required'=>'Required',
                'rp_app_recommend_date.required'=>'Required',
                'rp_app_effective_year.required'=>'Required',
                'rp_app_effective_quarter.required'=>'Required', 
                'rp_app_approved_by.required'=>'Required', 
                'rp_app_approved_date.required'=>'Required',
                'uc_code.required' => 'Required',
                'rp_app_memoranda.required' => 'Required',
                'rp_app_extension_section.required' => 'Required',
                'rp_app_extension_section.required' => 'Required',
                'rp_app_assessor_lot_no.required'   => 'Required',
                'pk_is_active.required'   => 'Required',
                'rp_app_cancel_type.required_if' => 'Required',
                'rp_app_cancel_by_td_id.required_if' => 'Required',
                'rp_app_cancel_remarks.required_if' => 'Required',
                'rp_app_cancel_date.required_if' => 'Required'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['data'] = $messages;
            return response()->json($arr);
        }
        
        $dataToSave = [
            'rp_app_appraised_by' => $request->rp_app_appraised_by,
            'rp_app_appraised_date' => $request->rp_app_appraised_date,
            'rp_app_appraised_is_signed' => ($request->has('rp_app_appraised_is_signed'))?$request->rp_app_appraised_is_signed:0,
            'rp_app_taxability' => $request->rp_app_taxability,
            'rp_app_recommend_by' => $request->rp_app_recommend_by,
            'rp_app_recommend_date' => $request->rp_app_recommend_date,
            'rp_app_recommend_is_signed' => ($request->has('rp_app_recommend_is_signed'))?$request->rp_app_recommend_is_signed:0,
            'rp_app_effective_year' => $request->rp_app_effective_year,
            'rp_app_effective_quarter' => ($request->has('rp_app_effective_quarter'))?$request->rp_app_effective_quarter:1,
            'rp_app_approved_by' => $request->rp_app_approved_by,
            'rp_app_approved_date' => $request->rp_app_approved_date,
            'rp_app_approved_is_signed' => ($request->has('rp_app_approved_is_signed'))?$request->rp_app_approved_is_signed:0,
            'rp_app_posting_date' => $request->rp_app_posting_date,
            'rp_app_memoranda' => $request->rp_app_memoranda,
            'rp_app_extension_section' => $request->rp_app_extension_section,
            'pk_is_active' => $request->pk_is_active,
            'rp_app_cancel_is_direct' => ($request->has('rp_app_cancel_is_direct'))?$request->rp_app_cancel_is_direct:0,
            'rp_app_cancel_by' => $request->rp_app_cancel_by,
            'rp_app_cancel_type' => ($request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1)?$request->rp_app_cancel_type:'',
            'rp_app_cancel_date' => ($request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1)?$request->rp_app_cancel_date:'',
            'rp_app_cancel_remarks' => ($request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1)?$request->rp_app_cancel_remarks:'',
            'uc_code' => $request->uc_code,
            'rp_app_assessor_lot_no' => $request->rp_app_assessor_lot_no,
        ];
        if($request->has('cancelled_by_id') && $request->cancelled_by_id != ''){
            $dataToSave['rp_app_cancel_by_td_id'] = $request->cancelled_by_id;
        }else{
            $dataToSave['rp_app_cancel_by_td_id'] = ($request->has('rp_app_cancel_is_direct'))?$request->rp_app_cancel_by_td_id:'';
        }
        if($request->has('id') && $request->id != null){
            $approvelFormDetails = RptPropertyApproval::find($request->id);
            $rptPropertyDetails = RptProperty::find($approvelFormDetails->rp_code);
                if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0 && $approvelFormDetails->rp_app_cancel_is_direct == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }
            $dataToUpdateInParent = [
                'rp_app_effective_year'   => $request->rp_app_effective_year,
                'rp_app_effective_quarter' => $request->rp_app_effective_quarter,
                'rp_app_posting_date' => $request->rp_app_posting_date,
                'rp_app_memoranda' => $request->rp_app_memoranda,
                'rp_app_extension_section' => $request->rp_app_extension_section,
                'pk_is_active' => $request->pk_is_active,
                'rp_app_assessor_lot_no' => $request->rp_app_assessor_lot_no,
                'rp_app_taxability' => $request->rp_app_taxability,
            ];
            //dd($dataToUpdateInParent);
            $this->_rptpropertybuilding->updateData($approvelFormDetails->rp_code,$dataToUpdateInParent);
            unset($dataToSave['rp_app_effective_year']);
            unset($dataToSave['rp_app_effective_quarter']);
            unset($dataToSave['rp_app_posting_date']);
            unset($dataToSave['rp_app_memoranda']);
            unset($dataToSave['rp_app_extension_section']);
            unset($dataToSave['pk_is_active']);
            unset($dataToSave['uc_code']);
            unset($dataToSave['rp_app_assessor_lot_no']);
            unset($dataToSave['rp_app_taxability']);
            $this->_rptpropertybuilding->updateApprovalForm($request->id,$dataToSave);
            if($approvelFormDetails->rp_app_appraised_by != 0 && $approvelFormDetails->rp_app_recommend_by != 0 && $approvelFormDetails->rp_app_approved_by != 0){
                $this->updatePropertyHistory($dataToSave['rp_app_cancel_by_td_id'],RptProperty::with(['updatecode'])->find($approvelFormDetails->rp_code));
            }
            if($request->pk_is_active == 0 && $request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1){
                $request->request->add(
                    [
                        'oldpropertyid'  => $approvelFormDetails->rp_code,
                        'updateCode'     => $request->rp_app_cancel_type,
                        'propertykind'   => 1,
                        'remarks'        => $request->rp_app_cancel_remarks,
                        'approvalformid' => $request->id
                    ]
                );
                $this->dpFunctionlaitySbubmit($request);
                $this->_rptpropertybuilding->updateAccountReceiaveableDetails($approvelFormDetails->rp_code, true);

            }
            if($request->pk_is_active == 1 && $approvelFormDetails != null){
                $this->_rptpropertybuilding->disableDirectCancellation($approvelFormDetails->rp_code);


            }
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else{
            //dd($dataToSave);
             $request->session()->put('approvalFormDataBuilding', (object)$dataToSave);
             $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }
        return response()->json($response);  
    }


    public function updateApprovalForm($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('approvalFormDataBuilding');
        if(!empty($sesionData) && $rptProperty != null){
            $dataToSave = (array)$sesionData;
            if(isset($dataToSave['rp_app_cancel_by_td_id']) && $dataToSave['rp_app_cancel_by_td_id'] != ''){
                $this->updatePropertyHistory($dataToSave['rp_app_cancel_by_td_id'], $rptProperty);
            }if(isset($dataToSave['cancelled_by_id']) && $dataToSave['cancelled_by_id'] != ''){
                $this->updatePropertyHistory($dataToSave['cancelled_by_id'], $rptProperty);
            }
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['rp_property_code'] = $rptProperty->rp_property_code;
            $dataToSave['created_at'] =  date('Y-m-d H:i:s');
            $dataToSave['updated_at'] =  date('Y-m-d H:i:s');
            unset($dataToSave['rp_app_cancel_by_td_id']);
            unset($dataToSave['cancelled_by_id']);
            unset($dataToSave['rp_app_effective_year']);
            unset($dataToSave['rp_app_effective_quarter']);
            unset($dataToSave['rp_app_posting_date']);
            unset($dataToSave['rp_app_memoranda']);
            unset($dataToSave['rp_app_extension_section']);
            unset($dataToSave['pk_is_active']);
            unset($dataToSave['uc_code']);
            unset($dataToSave['rp_app_assessor_lot_no']);
            unset($dataToSave['rp_app_taxability']);
            $this->_rptpropertybuilding->addApprovalForm($dataToSave);
            session()->forget('approvalFormDataBuilding');
        }
    }

    public function updateApprovalFormForPreOwner($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('approvalFormDataBuilding');
        if(!empty($sesionData) && $rptProperty != null){
            $dataToSave = $holdData = (array)$sesionData;
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['rp_property_code'] = $rptProperty->rp_property_code;
            $dataToSave['created_at'] =  date('Y-m-d H:i:s');
            $dataToSave['updated_at'] =  date('Y-m-d H:i:s');
            unset($dataToSave['rp_app_cancel_by_td_id']);
            unset($dataToSave['cancelled_by_id']);
            unset($dataToSave['rp_app_effective_year']);
            unset($dataToSave['rp_app_effective_quarter']);
            unset($dataToSave['rp_app_posting_date']);
            unset($dataToSave['rp_app_memoranda']);
            unset($dataToSave['rp_app_extension_section']);
            unset($dataToSave['pk_is_active']);
            unset($dataToSave['uc_code']);
            unset($dataToSave['rp_app_assessor_lot_no']);
            unset($dataToSave['rp_app_taxability']);
            unset($dataToSave['rp_app_taxability']);
            //dd($_rptpropertybuilding);
            $this->_rptpropertybuilding->addApprovalForm($dataToSave);
            session()->forget('approvalFormDataBuilding');
            if(isset($holdData['rp_app_cancel_by_td_id']) && $holdData['rp_app_cancel_by_td_id'] != ''){
                $this->updatePropertyHistory($id,RptProperty::find($holdData['rp_app_cancel_by_td_id']),true);
            }
        }
    }

    public function updateSwornStatement($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('propertySwornStatementBuilding');
        if(!empty($sesionData) && $rptProperty != null){
            $dataToSave = (array)$sesionData;
            
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['created_at'] =  date('Y-m-d H:i:s');
            $dataToSave['updated_at'] =  date('Y-m-d H:i:s');
            $dataToSave['rps_registered_by'] =  \Auth::user()->creatorId();
            //dd($dataToSave);
            $this->_rptpropertybuilding->addPropertySwornData($dataToSave);
            session()->forget('propertySwornStatementBuilding');
        }
    }

    public function updateFloorValues($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        $sesionData  = session()->get('floorValuesBuilding');
        
        if(!empty($sesionData) && $rptProperty != null){
            foreach ($sesionData as $key => $value) {
            $dataToSave = (array)$value;
            $dataToSave['rp_code'] =          $rptProperty->id;
            $dataToSave['rp_property_code'] = $rptProperty->rp_property_code;
            $addItems  = (!empty($dataToSave['additionalItems']))?$dataToSave['additionalItems']:[];
            unset($dataToSave['id']);
            unset($dataToSave['bt_building_type_code_desc']);
            unset($dataToSave['pau_actual_use_code_desc']);
            unset($dataToSave['dataSource']);
            unset($dataToSave['additionalItems']);
            
            $lastInsrtedId = $this->_rptpropertybuilding->addFloorValueDetail($dataToSave);
            $annoNations   = session()->get('propertyAnnotationForBuilding');
            //dd($annoNations);
            if(!empty($addItems)){
                foreach ($addItems as $item) {
                $dataToSaveInAnootation = (array)$item;
                $dataToSaveInAnootation['rp_code'] = $rptProperty->id;
                $dataToSaveInAnootation['rp_property_code'] = $rptProperty->rp_property_code;
                $dataToSaveInAnootation['rpbfv_code'] = $lastInsrtedId;
                $dataToSaveInAnootation['created_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['updated_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['rpbfai_registered_by'] =  \Auth::user()->creatorId();
                //dd($dataToSaveInAnootation);
                $this->_rptpropertybuilding->addAdditionalItemsData($dataToSaveInAnootation);
            }
                

            }
        }
        }
        session()->forget('propertyStatusForBuilding');
    }

    public function updatePropertyStatus($id=''){
       $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
    
        $sesionData  = session()->get('propertyStatusForBuilding');
        //dd($sesionData);
        if(!empty($sesionData) && $rptProperty != null){
            $dataToSave = (array)$sesionData;
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['created_at'] =  date('Y-m-d H:i:s');
            $dataToSave['updated_at'] =  date('Y-m-d H:i:s');
            $dataToSave['rpss_registered_by'] =  \Auth::user()->creatorId();
            $lastInsrtedId = $this->_rptpropertybuilding->addPropertyStatusData($dataToSave);
            $annoNations   = session()->get('propertyAnnotationForBuilding');
            //dd($annoNations);
            if(!empty($annoNations)){
                foreach ($annoNations as $ano) {
                $dataToSaveInAnootation = (array)$ano;
                $dataToSaveInAnootation['rp_code'] = $rptProperty->id;
                $dataToSaveInAnootation['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
                $dataToSaveInAnootation['created_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['updated_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['rpa_registered_by'] =  \Auth::user()->creatorId();
                $this->_rptpropertybuilding->addAnnotationData($dataToSaveInAnootation);
            }
                

            }
            
            session()->forget('propertyStatusForBuilding');
            session()->forget('propertyAnnotationForBuilding');
        }
    }

    public function addfloorvaluedescription(Request $request){
        $arrPropertyTypes = $this->rpt_building_types;
        $arrLandStrippingCodes = $this->arrStripingCodes;
        $rpt_building_actualuse =  $this->rpt_building_actualuse;
        $landAppraisal = [];
        $landUnitMeaure = config('constants.lav_unit_measure');
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptPropertyAppraisal::find($request->id);
            $arrSubClassesListColl = $this->_rptpropertybuilding->getSubClassesList($landAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_code','id')->toArray();
            //dd($arrSubClassesList);
            $arrActualUsesCodeColl = $this->_rptpropertybuilding->getActualUsesList($landAppraisal->pc_class_code);
            $arrActualUsesCodes = $arrActualUsesCodeColl->pluck('pau_actual_use_code','id')->toArray();
            $rptProprtyDetails = RptProperty::find($landAppraisal->rp_code);
            //dd($rptProprtyDetails);
            $request->request->add([
                'propertyKind' => $rptProprtyDetails->pk_id,
                'propertyClass' => $landAppraisal->pc_class_code,
                'propertyActualUseCode' => $landAppraisal->pau_actual_use_code,
                'propertyRevisionYear' => $rptProprtyDetails->rvy_revision_year_id

            ]);
            $arrassessementLevel = $this->_rptpropertybuilding->getAssessementLevel($request);
            
            $landAppraisal->al_minimum_unit_value = (isset($arrassessementLevel->al_minimum_unit_value))?$arrassessementLevel->al_minimum_unit_value:'0.00';
            $landAppraisal->al_maximum_unit_value = (isset($arrassessementLevel->al_maximum_unit_value))?$arrassessementLevel->al_maximum_unit_value:'0.00';
            $landAppraisal->al_assessment_level_hidden = (isset($arrassessementLevel->al_assessment_level))?$arrassessementLevel->al_assessment_level:'0.00';
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisal = $request->session()->get('buildingFloorvalue.'.$request->sessionId);
            $sessionId = $request->sessionId;
            $arrSubClassesListColl = $this->_rptpropertybuilding->getSubClassesList($landAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_desc','id')->toArray();

            $arrActualUsesCodeColl = $this->_rptpropertybuilding->getActualUsesList($landAppraisal->pc_class_code);
            $arrActualUsesCodes = $arrActualUsesCodeColl->pluck('pau_actual_use_desc','id')->toArray();
        }
        if($request->has('property_id') && $request->property_id != 0){
            $propertyCode = $this->_rptpropertybuilding->getSinglePropertyDetails($request->property_id);
        }
        $view = view('rptbuilding.ajax.addbuildingfloorvalue',compact('arrPropertyTypes','arrLandStrippingCodes','landAppraisal','rpt_building_actualuse'))->render();

        echo $view;
    }

    public function getAllBuildingStructure(Request $request){
        //dd($this->data);
        $arrbuildingroof = $this->arrbuildingroof;
        $arrbuildingfloor = $this->arrbuildingfloor;
        $arrbuildingfwall =  $this->arrbuildingfwall;
        $propertyCode = [];
        
               //dd($this->data);
        if($request->has('property_id') && $request->property_id != 0){
            $propertyCode = $this->_rptpropertybuilding->find($request->property_id);
            //dd($propertyCode);
        }else{
            $buildingstructureSessionData = $request->session()->get('buildingstructuredata');
            //dd($buildingstructureSessionData);
            if(!empty($buildingstructureSessionData)){
                unset($buildingstructureSessionData['id']);
                foreach($buildingstructureSessionData as $key=>$val){
                    $this->data[$key] = $val;
                  }
                $propertyCode = (object)$this->data;
            }
        
        }
        //dd($propertyCode);
        $view = view('rptbuilding.ajax.buildingstructure',compact('arrbuildingroof','arrbuildingfloor','arrbuildingfwall','propertyCode'))->render();

        echo $view;


    }

    public function searchLand(Request $request){
        $rptPropObj = new RptPropertyController;
        $landPropertyKind = $rptPropObj->propertyKind;
        $landPropertyKindId = $rptPropObj->_rptproperty->getKindIdByCode($landPropertyKind);
        $propertyDetails = [];
        $validator = \Validator::make(
            $request->all(), [
                'rp_td_no_lref'=>'required',
                'brgy_code_id' => 'required'
            ],
            [
                'rp_td_no_lref.required'=>'Required Field',
                'brgy_code_id.required'=>'Required Field',
            ]
        );
        $validator->after(function ($validator) use($landPropertyKindId, &$propertyDetails) {
            $data = $validator->getData();
                $oldPropertyData = RptProperty::with(['landAppraisals','propertyOwner'])->where('rp_td_no',$data['rp_td_no_lref'])
                                                ->where('brgy_code_id',$data['brgy_code_id'])
                                                ->where('pk_id',$landPropertyKindId)
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                ->first();
                $propertyDetails = $oldPropertyData;                                
                if($oldPropertyData == null){
                    $validator->errors()->add('rp_td_no_lref', 'No Td found!');
                }
              
            
    });
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $dataToReturn = [
            'rp_code_lref' => $propertyDetails->rp_property_code,
            'rp_td_no_lref'=> $propertyDetails->id,
            'rp_suffix_lref' => $propertyDetails->rp_suffix,
            // 'rpo_code_lref' => $propertyDetails->rpo_code,
            'rp_oct_tct_cloa_no_lref'=>$propertyDetails->rp_oct_tct_cloa_no,
            // 'rp_cadastral_lot_no_lref'=>$propertyDetails->rp_cadastral_lot_no,
            // 'rp_total_land_area' =>$propertyDetails->total_land_area,
            // 'land_owner' => (isset($propertyDetails->propertyOwner))?$propertyDetails->propertyOwner->standard_name:'',
            // 'land_location' => $propertyDetails->rp_location_number_n_street
        ];
        //dd($dataToReturn);
        return response()->json(['status' => 'success','data'=>$dataToReturn]);
    }

    public function storeBuildingfloorval(Request $request){
        //dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'rpbfv_floor_no'=>'required',
                'rpbfv_floor_area'=>'required',
                'rpbfv_floor_additional_value'=>'required', 
                'bt_building_type_code'=>'required', 
                'rpbfv_floor_adjustment_value'=>'required',
                'pau_actual_use_code'=>'required', 
                'rpbfv_floor_unit_value'=>'required',
                'rpbfv_total_floor_market_value' => 'required'
            ],
            [
                'rpbfv_floor_no.required'=>'Required Field',
                'rpbfv_floor_area.required'=>'Required Field',
                'rpbfv_floor_additional_value.required'=>'Required Field', 
                'bt_building_type_code.required'=>'Required Field',
                'pau_actual_use_code.required'=>'Required Field',
                'rpbfv_floor_unit_value.required' => 'Not Approved Yet',
                'rpbfv_floor_adjustment_value.required' => 'Required Field',
                'rpbfv_total_floor_market_value.required' => 'Required Field'
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $dataToSave = [
                'id'                => $request->id,
                'rp_code'           => $request->property_id,
                'rp_property_code' => $request->property_id,
                'rpbfv_floor_no' => $request->rpbfv_floor_no,
                'rpbfv_total_floor' => $request->rpbfv_total_floor,
                'bt_building_type_code' => $request->bt_building_type_code,
                'pau_actual_use_code' => $request->pau_actual_use_code,
                'rpbfv_floor_unit_value' => $request->rpbfv_floor_unit_value,
                'rpbfv_floor_area' => $request->rpbfv_floor_area,
                'rpbfv_floor_base_market_value' =>$request->rpbfv_floor_base_market_value,
                'rpbfv_floor_additional_value' =>$request->rpbfv_floor_additional_value,
                'rpbfv_floor_adjustment_value' => $request->rpbfv_floor_adjustment_value,
                'rpbfv_total_floor_market_value' =>$request->rpbfv_total_floor_market_value,
                'created_by' => \Auth::user()->creatorId(),
                'updated_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
           //dd($dataToSave);
           if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            $dataToSave['rpa_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptpropertybuilding->updateBuildingFloorvalueDetails($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id == null){
            // $savedProperty = $this->_rptpropertybuilding->getSinglePropertyDetails($request->property_id);
            // unset($dataToSave['pc_class_description']);
            // unset($dataToSave['ps_subclass_desc']);
            // unset($dataToSave['pau_actual_use_desc']);
            // unset($dataToSave['land_stripping_id']);
            // unset($dataToSave['al_minimum_unit_value']);
            // unset($dataToSave['al_maximum_unit_value']);
            // unset($dataToSave['al_assessment_level_hidden']);
            $dataToSave['rp_code']           = $savedProperty->rp_code;
            // $dataToSave['rp_property_code']  = $savedProperty->rp_property_code;
            // $dataToSave['rvy_revision_year'] = $savedProperty->rvy_revision_year;
            // $dataToSave['rvy_revision_code'] = $savedProperty->rvy_revision_code;
            //dd($dataToSave);
            $this->_rptpropertybuilding->addBuildingFloorvalueDetail($dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $dataToSave['id']   = null;
                $dataToSave['dataSource']   = 'session';
                $dataToSave['plantsTreeApraisals'] = [];
                $savedLandApprSessionData = $request->session()->get('buildingfloorvaluedata');
                $savedLandApprSessionData[] = (object)$dataToSave;
                if($request->has('session_id') && $request->session_id != ''){
                    $getPlantsTreesAdjustmentFactor = $request->session()->get('buildingfloorvaluedata.'.$request->session_id);
                    $request->session()->put('buildingfloorvaluedata.'.$request->session_id, (object)$dataToSave);
                }else{
                    $request->session()->put('buildingfloorvaluedata', $savedLandApprSessionData);
                }
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
    }

    public function storebuildingstructure(Request $request){
    	//echo "here"; exit;
    	 $validator = \Validator::make(
            $request->all(), [
                'rbf_building_roof_desc1'=>'nullable|different:rbf_building_roof_desc2,rbf_building_roof_desc3',
                'rbf_building_roof_desc2'=>'nullable|different:rbf_building_roof_desc1,rbf_building_roof_desc3',
                'rbf_building_roof_desc3'=>'nullable|different:rbf_building_roof_desc2,rbf_building_roof_desc1', 
                'rbf_building_floor_desc1'=>'nullable|different:rbf_building_floor_desc2,rbf_building_floor_desc3', 
                'rbf_building_floor_desc2'=>'nullable|different:rbf_building_floor_desc1,rbf_building_floor_desc3',
                'rbf_building_floor_desc3'=>'nullable|different:rbf_building_floor_desc1,rbf_building_floor_desc2', 
                'rbf_building_wall_desc1'=>'nullable|different:rbf_building_wall_desc2,rbf_building_wall_desc3',
                'rbf_building_wall_desc2' => 'nullable|different:rbf_building_wall_desc1,rbf_building_wall_desc3',
                'rbf_building_wall_desc3' => 'nullable|different:rbf_building_wall_desc1,rbf_building_wall_desc2',
            ],
            [
                'rbf_building_roof_desc1.required'=>'Required Field',
                'rbf_building_roof_desc2.required'=>'Required Field',
                'rbf_building_roof_desc3.required'=>'Required Field', 
                'rbf_building_floor_desc1.required'=>'Required Field',
                'rbf_building_floor_desc2.required'=>'Required Field',
                'rbf_building_floor_desc3.required' => 'Not Approved Yet',
                'rbf_building_wall_desc1.required' => 'Required Field',
                'rbf_building_wall_desc2.required' => 'Required Field',
                'rbf_building_wall_desc3.required' => 'Required Field',

                'rbf_building_roof_desc1.different'=>'Must be different from other Roofs',
                'rbf_building_roof_desc2.different'=>'Must be different from other Roofs',
                'rbf_building_roof_desc3.different'=>'Must be different from other Roofs', 
                'rbf_building_floor_desc1.different'=>'Must be different from other Floors',
                'rbf_building_floor_desc2.different'=>'Must be different from other Floors',
                'rbf_building_floor_desc3.different' => 'Must be different from other Floors',
                'rbf_building_wall_desc1.different' => 'Must be different from other Walls',
                'rbf_building_wall_desc2.different' => 'Must be different from other Walls',
                'rbf_building_wall_desc3.different' => 'Must be different from other Walls',
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $dataToSave = [
                'id'                       => $request->id,
                'rbf_building_roof_desc1'  => $request->rbf_building_roof_desc1,
                'rbf_building_roof_desc2' => $request->rbf_building_roof_desc2,
                'rbf_building_roof_desc3' => $request->rbf_building_roof_desc3,
                'rbf_building_floor_desc1' => $request->rbf_building_floor_desc1,
                'rbf_building_floor_desc2' => $request->rbf_building_floor_desc2,
                'rbf_building_floor_desc3' => $request->rbf_building_floor_desc3,
                'rbf_building_wall_desc1' => $request->rbf_building_wall_desc1,
                'rbf_building_wall_desc2' => $request->rbf_building_wall_desc2,
                'rbf_building_wall_desc3' =>$request->rbf_building_wall_desc3
                
            ];
         if($request->has('property_id') && $request->property_id > 0){
            if($this->_rptpropertybuilding->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswBuilding');
            unset($dataToSave['id']);
            $dataToSave['rp_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertybuilding->updateData($request->property_id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $dataToSave['id']   = null;
                $request->session()->put('buildingstructuredata', $dataToSave);
                $response = ['status' => 'success','msg' => 'Data Updated successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
    }

      public function getBuildingUnitValue(Request $request){
        
        $id = $request->input('id');
        $data = $this->_rptpropertybuilding->getBuildingUnitValue($request);
        $response = ['status'=>'error','data'=>[]];
        if($data != null){
            $response['status'] = 'success';
            $response['data'] = $data;
        }
        echo json_encode($response);
    }


    public function getMachineryAppraisal(Request $request){
        $sessionData = collect($request->session()->get('landAppraisals'));
        $id = $request->input('id');
        $machineryApprasals = $this->_rptpropertybuilding->getMachineryAppraisalDetals($id);
        if($id == 0){
            $landApprasals = $sessionData;
        }
        //dd($landApprasals);
        $view = view('rptbuilding.ajax.machineryappraisallisting',compact('machineryApprasals'))->render();
        echo $view;
    }
    public function getAssessmentSummaryListing(Request $request){
        
        $id        = $request->input('id');
        $sessionid = $request->input('id');
        if($id != ''){
            $assessmentsummary = $this->_rptpropertybuilding->getPalntsTreesAppraisalDetails($id);
        }else{
            $sessionData = collect($request->session()->get('assessmentsummary'));
            $assessmentsummary = [];
        }
        $view = view('rptproperty.ajax.plantstreesadjustmentfactor',compact('assessmentsummary'))->render();
        echo $view;
    }

    public function extractAdditionalItems($request=''){
        $addItems = ($request->has('bei_extra_item_code'))?$request->bei_extra_item_code:[];
        $addItesmsArray = [];
        if(!empty($addItems)){
                    $loop = count($request->bei_extra_item_code);
                     $activitydetail = array();
                  
                    for($i=0; $i<$loop;$i++){
                        if($request->bei_extra_item_code[$i] != null){
                            $activitydetail['rp_code'] = $request->rp_code[$i];
                            $activitydetail['rpbfv_code'] = $request->rpbfv_code[$i];
                            $activitydetail['bei_extra_item_code'] = $request->bei_extra_item_code[$i];
                            $activitydetail['bei_extra_item_desc'] =$request->bei_extra_item_desc[$i];
                            $activitydetail['rpbfai_total_area'] = $request->rpbfai_total_area[$i];
                            $addItesmsArray[] = $activitydetail;
                        }
                        
                    }
                 }
                 
                 return $addItesmsArray;
    }

    public function generateTaxDeclarationAndPropertyCode($id = ''){
        $data = RptProperty::with([
                'barangay',
                'updatecode',
                'locality'
            ])->where('id',$id)->first();
        $rpTdNo = $data->id;
        $updateCode = $data->updatecode->uc_code;
        $brgyCode = $data->barangay->brgy_code;
        $taxDeclarationNumber = $brgyCode.$rpTdNo;
        $locality = $data->locality->loc_local_code;
        $distCode = $data->dist_code;
        $propertyCode = $brgyCode.$locality.$distCode.$rpTdNo;
        $dataToUpdate = [
            'rp_property_code' => $propertyCode,
            'rp_td_no' => $rpTdNo,
            'rp_tax_declaration_no' => $taxDeclarationNumber
        ];
        if($updateCode != 'DC'){
            unset($dataToUpdate['rp_property_code']);
        }
        $this->_rptpropertybuilding->updateData($data->id,$dataToUpdate);
    }

    public function getAllProfiles(){
        return $this->_rptpropertybuilding->getprofiles();
    }

    public function store(Request $request){
        $buildPermits = $this->buildingPermits;
        $arrLocationdocs = array();
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC'); 
        $propertyKind = $this->_rptpropertybuilding->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        if($request->getMethod() == "GET" && $updateCode == config('constants.update_codes_land.DC')){
                   session()->forget('buildingstructuredata');
                   session()->forget('approvalFormDataBuilding');
                   session()->forget('propertyAnnotationForBuilding');
                   session()->forget('propertySwornStatementBuilding');
                   session()->forget('floorValuesBuilding');
                   session()->forget('propertyStatusForBuilding');
                }
        $data = $this->setData($oldpropertyid,$updateCode);
        if($request->getMethod() == "GET" && empty($data) && !$request->has('id')){
            if($request->ajax()){
                return response()->json(['status'=>'error','msg'=>'RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!']);
            }else{
                return redirect()->route('rptproperty.index')->with('error', __('RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!'));
            }
            
        }
        $arrBarangay = $this->arrBarangay;
        $arrSubclasses = $this->arrSubclasses;
        $arrRevisionYears = $this->arrRevisionYears;
        $arrLocalityCodes = $this->arrLocCodes;
        $arrDistNumbers = $this->arrDistNumbers;
        $arrUpdateCodes = $this->arrUpdateCodes;
        $arrPropertyClasses = $this->arrPropClasses;
        $arrPropKindCodes = $this->arrPropKindCodes;
        $arrLandStrippingCodes = $this->arrStripingCodes;
        $activeMuncipalityCode = $this->activeMuncipalityCode;
        $buildingKinds         = $this->arrBuildingKinds;
        //$taxDeclarations         = $this->taxDeclarationss;
        //dd($this->taxDeclarationss);
        
        $landAraisalDetails = [];
        $activeBarangay = [];
        foreach ($this->_rptpropertybuilding->getprofiles($request->input('id')) as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        }
        
        $profile = $this->arrprofile;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin',
                'buildingReffernceLand'
            ])->where('id',$request->input('id'))->first();
            //dd($data->buildingReffernceLand);
            $arrLocationdocs = $this->_rptpropertybuilding->getPropertydocbyid($data->rp_property_code);
            $data->land_owner = (isset($data->buildingReffernceLand->propertyOwner->standard_name))?$data->buildingReffernceLand->propertyOwner->standard_name:'';
            $data->land_location = (isset($data->buildingReffernceLand->rp_location_number_n_street))?$data->buildingReffernceLand->rp_location_number_n_street:'';;
            //dd($data);
            $data->property_owner_address = $data->propertyOwner->address();
            $data->rp_administrator_code_address = ($data->propertyAdmin != null)?$data->propertyAdmin->address():'';
            $updateCodeDetails = $this->_rptpropertybuilding->getUpdateCodeById($data->uc_code);
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revisionYearDetails->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            $rfs=$data->rp_code_lref;
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
            }
            }
        if($request->isMethod('post')){
           //dd($request->all());
            $sesionData  = session()->get('approvalFormDataBuilding');
            $buildingStucturalSessionData = (object)$request->session()->get('buildingstructuredata');
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
               }
                $this->data['rp_modified_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                unset($this->data['loc_local_code_name']);
                unset($this->data['dist_code_name']);
                unset($this->data['brgy_code_and_desc']);
                unset($this->data['rvy_revision_year']);
                unset($this->data['update_code']);
                unset($this->data['property_owner_address']);
                unset($this->data['rp_administrator_code_address']);
                unset($this->data['land_owner']);
                unset($this->data['land_location']);
                $rpOccupiedMonthArray = explode('-',$request->rp_occupied_month);
                $rpConstructMonthArray = explode('-',$request->rp_constructed_month);
                //dd(collect($buildingStucturalSessionData)->isEmpty());
                $this->data['rp_constructed_month'] = $rpConstructMonthArray[1];
                $this->data['rp_occupied_month']    = $rpOccupiedMonthArray[1];
                $this->data['rp_constructed_year']  = $rpConstructMonthArray[0];
                $this->data['rp_occupied_year']     = $rpOccupiedMonthArray[0];
                 if($request->has('is_manual_permit')){
                   $this->data['permit_id'] = '';
                }else{
                    if($request->permit_id > 0){
                        $permitDetails = DB::table('eng_bldg_permit_apps')->select('ebpa_permit_no')->where('id',$request->permit_id)->first();
                        $this->data['is_manual_permit'] = 0;
                        $this->data['rp_bulding_permit_no'] = $permitDetails->ebpa_permit_no;
                    }else{
                        $this->data['is_manual_permit'] = 0;
                    }
                    
                }
               if($request->input('id')>0){
                unset($this->data['rbf_building_roof_desc1']);
                unset($this->data['rbf_building_roof_desc2']);
                unset($this->data['rbf_building_roof_desc3']);
                unset($this->data['rbf_building_floor_desc1']);
                unset($this->data['rbf_building_floor_desc2']);
                unset($this->data['rbf_building_floor_desc3']);
                unset($this->data['rbf_building_wall_desc1']);
                unset($this->data['rbf_building_wall_desc2']);
                unset($this->data['rbf_building_wall_desc3']);

                $dataToSave = $this->data;
                $savedProperty = $this->_rptpropertybuilding->getSinglePropertyDetails($request->input('id'));
                $approvalFormData = DB::table('rpt_property_approvals')->where('rp_code',$request->input('id'))->select('rp_app_cancel_is_direct')->first();
                /*if($savedProperty->pk_is_active == 0 && $approvalFormData->rp_app_cancel_is_direct == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }*/
                if($this->_rptpropertybuilding->checkToVerifyPsw($request->input('id'))){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswBuilding');
                DB::beginTransaction();
                try {
                    $this->_rptpropertybuilding->updateData($request->input('id'),$dataToSave);
                    $lastinsertid = $request->input('id');
                    $this->_rptpropertybuilding->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($lastinsertid);
                    $this->_rptpropertybuilding->updatePinDeclarationNumber($lastinsertid);
                    DB::commit();
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                    $status = 'success';
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    $success_msg = $e->getMessage();
                    $status = 'error';
                }
                }else{
                if(collect($buildingStucturalSessionData)->isEmpty()){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Building Structural details!']);
                }
                
                if(collect($sesionData)->isEmpty()|| $sesionData->rp_app_memoranda == ''){
                    return response()->json(['status'=>'error','msg'=>'Please Complete The Approval Form details, then try again!']);
                }
                
                $this->data['rbf_building_roof_desc1']= (isset($buildingStucturalSessionData->rbf_building_roof_desc1))?$buildingStucturalSessionData->rbf_building_roof_desc1:'';
                $this->data['rbf_building_roof_desc2']= (isset($buildingStucturalSessionData->rbf_building_roof_desc2))?$buildingStucturalSessionData->rbf_building_roof_desc2:'';
                $this->data['rbf_building_roof_desc3']= (isset($buildingStucturalSessionData->rbf_building_roof_desc3))?$buildingStucturalSessionData->rbf_building_roof_desc3:'';
                $this->data['rbf_building_floor_desc1']= (isset($buildingStucturalSessionData->rbf_building_floor_desc1))?$buildingStucturalSessionData->rbf_building_floor_desc1:'';
                $this->data['rbf_building_floor_desc2']= (isset($buildingStucturalSessionData->rbf_building_floor_desc2))?$buildingStucturalSessionData->rbf_building_floor_desc2:'';
                $this->data['rbf_building_floor_desc3']= (isset($buildingStucturalSessionData->rbf_building_floor_desc3))?$buildingStucturalSessionData->rbf_building_floor_desc3:'';
                $this->data['rbf_building_wall_desc1']= (isset($buildingStucturalSessionData->rbf_building_wall_desc1))?$buildingStucturalSessionData->rbf_building_wall_desc1:'';
                $this->data['rbf_building_wall_desc2']= (isset($buildingStucturalSessionData->rbf_building_wall_desc2))?$buildingStucturalSessionData->rbf_building_wall_desc2:'';
                $this->data['rbf_building_wall_desc3']= (isset($buildingStucturalSessionData->rbf_building_wall_desc3))?$buildingStucturalSessionData->rbf_building_wall_desc3:'';
                $this->data['rp_app_effective_year']= (isset($sesionData->rp_app_effective_year))?$sesionData->rp_app_effective_year:'';
                $this->data['rp_app_effective_quarter']= (isset($sesionData->rp_app_effective_quarter))?$sesionData->rp_app_effective_quarter:'';
                $this->data['rp_app_posting_date']= (isset($sesionData->rp_app_posting_date))?$sesionData->rp_app_posting_date:'';
                $this->data['rp_app_memoranda']= (isset($sesionData->rp_app_memoranda))?$sesionData->rp_app_memoranda:'';
                $this->data['rp_app_extension_section']= (isset($sesionData->rp_app_extension_section))?$sesionData->rp_app_extension_section:'';
                $this->data['pk_is_active']= (isset($sesionData->pk_is_active))?$sesionData->pk_is_active:'';
                //$this->data['pk_id']= $this->data['bk_building_kind_code'];
                $this->data['rp_app_assessor_lot_no']= (isset($sesionData->rp_app_assessor_lot_no))?$sesionData->rp_app_assessor_lot_no:'';
                $this->data['rp_app_taxability']= (isset($sesionData->rp_app_taxability))?$sesionData->rp_app_taxability:'';
                $this->data['rp_registered_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                //dd($this->data);
                $selectedUpDateCode = $request->uc_code;
                $dataToSave = $this->data;
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptpropertybuilding->addData($dataToSave);
                    $lastinsertid = $request->id;
                    $this->_rptpropertybuilding->generateTaxDeclarationAndPropertyCode($lastinsertid);
                    $this->updateSwornStatement($lastinsertid);
                    $this->updateApprovalForm($lastinsertid);
                    $this->updatePropertyStatus($lastinsertid);
                    $this->updateFloorValues($lastinsertid);
                    $this->_rptpropertybuilding->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertybuilding->updateAccountReceiaveableDetails($lastinsertid);
                    $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($lastinsertid);

                    DB::commit();
                    $newGeneratedPropertyDetails = RptProperty::find($lastinsertid);
                    $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    $oldPropertyData = RptProperty::find($request->old_property_id);
                    if($selectedUpDateCode == config('constants.update_codes_land.TR') && $oldPropertyData != null){
                        $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against Transfer of ownership of #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.DUP') && $oldPropertyData != null){
                        $success_msg = 'Duplicate copy generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.SSD') && $oldPropertyData != null){
                        $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Superseded generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.RC') && $oldPropertyData != null){
                        $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Reclassification completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.PC') && $oldPropertyData != null){
                        $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Physical changes completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.CS') && $oldPropertyData != null){
                        $success_msg = 'Consolidation completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    }else{
                        $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertid);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    }
                    $status = 'success';
                } catch (\Exception $e) {
                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                    dd($e);
                }
            }
            $totalMarketValue = 0;
            if($request->ajax()){
                return response()->json(['status'=>$status,'msg'=>$success_msg]);
            }else{
                return redirect()->route('rptbuilding.index')->with('success', __($success_msg));
            }
        }
        
        return view('rptbuilding.create',compact('arrRevisionYears','data','arrBarangay','profile','arrSubclasses','arrLocalityCodes','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','arrLandStrippingCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','propertyKind','updateCode','oldpropertyid','buildingKinds','arrLocationdocs'));
    }

    public function loadPreviousOwner(Request $request){
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC'); 
        $propertyKind = $this->_rptpropertybuilding->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        if($request->getMethod() == "GET"){
                   session()->forget('buildingstructuredata');
                   session()->forget('approvalFormDataBuilding');
                   session()->forget('floorValuesBuilding');
                   session()->forget('propertyStatusForBuilding');
                }
        $data = $this->setData($oldpropertyid,$updateCode);
        if($request->getMethod() == "GET" && empty($data) && !$request->has('id')){
            if($request->ajax()){
                return response()->json(['status'=>'error','msg'=>'RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!']);
            }else{
                return redirect()->route('rptproperty.index')->with('error', __('RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!'));
            }
            
        }
         
        $arrBarangay = $this->arrBarangay;
        $arrSubclasses = $this->arrSubclasses;
        $arrRevisionYears = $this->arrRevisionYears;
        $arrDistNumbers = $this->arrDistNumbers;
        $arrUpdateCodes = $this->arrUpdateCodes;
        $arrPropertyClasses = $this->arrPropClasses;
        $arrPropKindCodes = $this->arrPropKindCodes;
        $activeMuncipalityCode = $this->activeMuncipalityCode;
        $buildingKinds         = $this->arrBuildingKinds;
        $approvelFormData  = [];
        
        $landAraisalDetails = [];
        $activeBarangay = [];
        foreach ($this->_rptpropertybuilding->getprofiles($request->input('id')) as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        }
        
        $profile = $this->arrprofile;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin',
                'buildingReffernceLand'
            ])->where('id',$request->input('id'))->first();
            $approvelFormData   = $data->propertyApproval;
            $arrLocationdocs = $this->_rptpropertybuilding->getPropertydocbyid($data->rp_property_code);
            $data->land_owner = (isset($data->buildingReffernceLand->propertyOwner->standard_name))?$data->buildingReffernceLand->propertyOwner->standard_name:'';
            $data->land_location = (isset($data->buildingReffernceLand->rp_location_number_n_street))?$data->buildingReffernceLand->rp_location_number_n_street:'';;
            //dd($data);
            $data->property_owner_address = $data->propertyOwner->address();
            $data->rp_administrator_code_address = ($data->propertyAdmin != null)?$data->propertyAdmin->address():'';
            $updateCodeDetails = $this->_rptpropertybuilding->getUpdateCodeById($data->uc_code);
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revisionYearDetails->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
                //$data->rp_location_number_n_street = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
            }
            }
        if($request->isMethod('post')){
            $approvalFormData = [
                'rp_app_cancel_by_td_id' => ($request->has('rp_app_cancel_by_td_id'))?$request->rp_app_cancel_by_td_id:'',
                'rp_app_taxability' => ($request->has('rp_app_taxability'))?$request->rp_app_taxability:'',
                'rp_app_effective_year' => ($request->has('rp_app_effective_year'))?$request->rp_app_effective_year:'',
                'rp_app_effective_quarter' => ($request->has('rp_app_effective_quarter'))?$request->rp_app_effective_quarter:'',
                'rp_app_approved_by' => ($request->has('rp_app_approved_by'))?$request->rp_app_approved_by:'',
                'rp_app_approved_date' => ($request->has('rp_app_posting_date'))?$request->rp_app_posting_date:'',
                'rp_app_posting_date' => ($request->has('rp_app_posting_date'))?$request->rp_app_posting_date:'',
                'pk_is_active' => 9,
                'rp_app_cancel_by' => \Auth::user()->id,
                'rp_app_cancel_type' => config('constants.update_codes_land.DC'),
                'rp_app_cancel_date' => date("Y-m-d")
            ];
            session()->put('approvalFormDataBuilding', (object)$approvalFormData);
            $sesionData  = session()->get('approvalFormDataBuilding');
            $buildingStucturalSessionData = (object)$request->session()->get('buildingstructuredata');
            $buildingFloorValueSessionData = (object)$request->session()->get('floorValuesBuilding');
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
               }
                $this->data['rp_app_effective_year'] = $request->rp_app_effective_year;
                $this->data['rp_app_taxability'] = $request->rp_app_taxability;
                $this->data['rp_app_effective_quarter'] = $request->rp_app_effective_quarter;
                $this->data['rp_app_posting_date'] = $request->rp_app_posting_date;
                $this->data['rp_modified_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                unset($this->data['loc_local_code_name']);
                unset($this->data['dist_code_name']);
                unset($this->data['brgy_code_and_desc']);
                unset($this->data['rvy_revision_year']);
                unset($this->data['update_code']);
                unset($this->data['property_owner_address']);
                unset($this->data['rp_administrator_code_address']);
                unset($this->data['land_owner']);
                unset($this->data['land_location']);
                $rpOccupiedMonthArray = explode('-',$request->rp_occupied_month);
                $rpConstructMonthArray = explode('-',$request->rp_constructed_month);
                //dd(collect($buildingStucturalSessionData)->isEmpty());
                $this->data['rp_constructed_month'] = $rpConstructMonthArray[1];
                $this->data['rp_occupied_month']    = $rpOccupiedMonthArray[1];
                $this->data['rp_constructed_year']  = $rpConstructMonthArray[0];
                $this->data['rp_occupied_year']     = $rpOccupiedMonthArray[0];
                if($request->has('is_manual_permit')){
                   $this->data['permit_id'] = '';
                }else{
                    if($request->permit_id > 0){
                        $permitDetails = DB::table('eng_bldg_permit_apps')->select('ebpa_permit_no')->where('id',$request->permit_id)->first();
                        $this->data['is_manual_permit'] = 0;
                        $this->data['rp_bulding_permit_no'] = $permitDetails->ebpa_permit_no;
                    }else{
                        $this->data['is_manual_permit'] = 0;
                    }
                }
               if($request->input('id')>0){
                unset($this->data['rbf_building_roof_desc1']);
                unset($this->data['rbf_building_roof_desc2']);
                unset($this->data['rbf_building_roof_desc3']);
                unset($this->data['rbf_building_floor_desc1']);
                unset($this->data['rbf_building_floor_desc2']);
                unset($this->data['rbf_building_floor_desc3']);
                unset($this->data['rbf_building_wall_desc1']);
                unset($this->data['rbf_building_wall_desc2']);
                unset($this->data['rbf_building_wall_desc3']);
                $dataToSave = $this->data;
                $savedProperty = $this->_rptpropertybuilding->getSinglePropertyDetails($request->input('id'));
                if($savedProperty->pk_is_active == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }
                DB::beginTransaction();
                try {
                    $this->_rptpropertybuilding->updateData($request->input('id'),$dataToSave);
                    $lastinsertid = $request->input('id');
                    if($request->has('rp_app_approved_by')){
                        $approvalFormId = DB::table('rpt_property_approvals')->select('id')->where('rp_code',$lastinsertid)->first();
                        $dataToUpdateAppForm = [
                            'rp_app_approved_by' => $request->rp_app_approved_by,
                            'rp_app_approved_date' => $request->rp_app_posting_date
                        ];
                        $this->_rptpropertybuilding->updateApprovalForm($approvalFormId->id,$dataToUpdateAppForm);
                    }
                    $lastinsertid = $request->input('id');
                    $this->_rptpropertybuilding->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $status = 'success';
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                } catch (\Exception $e) {
                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                }
               
                }else{
                if(collect($buildingFloorValueSessionData)->isEmpty()){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Building Floor Value details!']);
                }
                
                $this->data['rbf_building_roof_desc1']= (isset($buildingStucturalSessionData->rbf_building_roof_desc1))?$buildingStucturalSessionData->rbf_building_roof_desc1:'';
                $this->data['rbf_building_roof_desc2']= (isset($buildingStucturalSessionData->rbf_building_roof_desc2))?$buildingStucturalSessionData->rbf_building_roof_desc2:'';
                $this->data['rbf_building_roof_desc3']= (isset($buildingStucturalSessionData->rbf_building_roof_desc3))?$buildingStucturalSessionData->rbf_building_roof_desc3:'';
                $this->data['rbf_building_floor_desc1']= (isset($buildingStucturalSessionData->rbf_building_floor_desc1))?$buildingStucturalSessionData->rbf_building_floor_desc1:'';
                $this->data['rbf_building_floor_desc2']= (isset($buildingStucturalSessionData->rbf_building_floor_desc2))?$buildingStucturalSessionData->rbf_building_floor_desc2:'';
                $this->data['rbf_building_floor_desc3']= (isset($buildingStucturalSessionData->rbf_building_floor_desc3))?$buildingStucturalSessionData->rbf_building_floor_desc3:'';
                $this->data['rbf_building_wall_desc1']= (isset($buildingStucturalSessionData->rbf_building_wall_desc1))?$buildingStucturalSessionData->rbf_building_wall_desc1:'';
                $this->data['rbf_building_wall_desc2']= (isset($buildingStucturalSessionData->rbf_building_wall_desc2))?$buildingStucturalSessionData->rbf_building_wall_desc2:'';
                $this->data['rbf_building_wall_desc3']= (isset($buildingStucturalSessionData->rbf_building_wall_desc3))?$buildingStucturalSessionData->rbf_building_wall_desc3:'';
                $this->data['rp_app_effective_year']= (isset($sesionData->rp_app_effective_year))?$sesionData->rp_app_effective_year:'';
                $this->data['rp_app_effective_quarter']= (isset($sesionData->rp_app_effective_quarter))?$sesionData->rp_app_effective_quarter:'';
                $this->data['rp_app_posting_date']= (isset($sesionData->rp_app_posting_date))?$sesionData->rp_app_posting_date:'';
                $this->data['rp_app_memoranda']= (isset($sesionData->rp_app_memoranda))?$sesionData->rp_app_memoranda:'';
                $this->data['rp_app_extension_section']= (isset($sesionData->rp_app_extension_section))?$sesionData->rp_app_extension_section:'';
                $this->data['pk_is_active']= (isset($sesionData->pk_is_active))?$sesionData->pk_is_active:'';
                $this->data['rp_app_assessor_lot_no']= (isset($sesionData->rp_app_assessor_lot_no))?$sesionData->rp_app_assessor_lot_no:'';
                $this->data['rp_app_taxability']= (isset($sesionData->rp_app_taxability))?$sesionData->rp_app_taxability:'';
                $this->data['rp_registered_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                //dd($this->data);
                $selectedUpDateCode = $request->uc_code;
                $dataToSave = $this->data;
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptpropertybuilding->addData($dataToSave);
                    $lastinsertid = $request->id;
                    $this->_rptpropertybuilding->generateTaxDeclarationAndPropertyCode($lastinsertid,true);
                    $lastinsertid = $request->id;
                    $this->updateApprovalFormForPreOwner($lastinsertid);
                    $this->updateFloorValues($lastinsertid);
                    $this->_rptpropertybuilding->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertybuilding->updateChain($lastinsertid);
                    $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $newGeneratedPropertyDetails = RptProperty::find($lastinsertid);
                    $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    $status = 'success';
                } catch (\Exception $e) {
                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                    dd($e);
                }
            }
            //dd($savedProperty);
            $totalMarketValue = 0;
            if($request->ajax()){
                return response()->json(['status'=>$status,'msg'=>$success_msg]);
            }else{
                return redirect()->route('rptbuilding.index')->with('success', __($success_msg));
            }
        }
        $allTds     = $this->_rptpropertybuilding->getPreviousOwnerTds((isset($request->oldpropertyid))?$request->oldpropertyid:$request->id); 
        $appraisers  = $this->arremployees;
         foreach ($this->_rptpropertybuilding->getEmployee() as $e_key => $e_value) {
            $appraisers[$e_value->id] = $e_value->fullname;
         }
        return view('rptbuilding.ajax.addpreviousowner',compact('arrRevisionYears','data','arrBarangay','profile','arrSubclasses','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','propertyKind','updateCode','oldpropertyid','buildingKinds','allTds','appraisers','approvelFormData'));
    }

    public function updatePropertyHistory($olderProp = '', $newProp = '',$flag = false){
        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('buildTaxDeclarationForConsolidation');
            $olderPropIds = $sessionDataOfConsoldation;
        }else{
            $olderPropIds = (array)$olderProp;
        }
        $olderPropties = RptProperty::with(['propertyKindDetails','propertyApproval'])->whereIn('id',$olderPropIds)->get();
        
        if($olderProp != null && $newProp->uc_code != config('constants.update_codes_land.SD') && $newProp->uc_code != config('constants.update_codes_land.DUP')){
            foreach ($olderPropties as $olderProp) {
                //dd($olderProp);
            $dataToSaveInHistory = [
            'pk_code' =>$olderProp->propertyKindDetails->pk_code,
            'rp_property_code' =>(isset($olderPropties[0]->rp_property_code))?$olderPropties[0]->rp_property_code:$olderProp->rp_property_code,
            'rp_code_active' => $newProp->id,
            'rp_code_cancelled' => $olderProp->id,
            'ph_registered_by' => \Auth::user()->creatorId(),
            'ph_registered_date' => date('Y-m-d H:i:s'),
            'ph_modified_by' => \Auth::user()->creatorId(),
            'ph_modified_date' =>  date('Y-m-d H:i:s')
        ];
        $this->_rptpropertybuilding->addPropertyHistory($dataToSaveInHistory);
        //dd($newProp);
        $prevCalcelByTd = $olderProp->propertyApproval->rp_app_cancel_by_td_id;
        $newCancelByTd = $newProp->id;
        if($prevCalcelByTd != null){
            $newCancelByTd .= $prevCalcelByTd.','.$newProp->id;
        }
        $dataToUpdateInOldProp = [
            'rp_app_cancel_by_td_id' => $newCancelByTd,
            'rp_app_cancel_by' => \Auth::user()->creatorId(),
            'rp_app_cancel_type' => $newProp->uc_code,
            'rp_app_cancel_date' => date('Y-m-d H:i:s'),
            'rp_app_cancel_remarks' => ''
        ];
        $this->_rptpropertybuilding->updateApprovalForm($olderProp->propertyApproval->id,$dataToUpdateInOldProp);
        /* Update Older Property Data */
        $this->_rptpropertybuilding->updateData($olderProp->id,[
            'pk_is_active' => ($flag)?9:0,
            'rp_property_code_new' => $newProp->rp_property_code,
            'rp_modified_by' => \Auth::user()->creatorId(),
        ]);
        /* Update Older Property Data */
        /* Update New Property Data */
        $this->_rptpropertybuilding->updateData($newProp->id,[
            'rp_property_code_new' => $newProp->rp_property_code,
        ]);
        /* Update New Property Data */
        }
        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('buildTaxDeclarationForConsolidation');
            $olderPropIds = $sessionDataOfConsoldation;
            $this->_rptpropertybuilding->addDataInAccountReceivable($newProp->id,(isset($olderPropties[0]->id))?$olderPropties[0]->id:0,'CS',$olderPropIds);
        }
    }
    }
    public function generateRpTDNo($value=''){
        $tableIdid      = DB::select("SHOW TABLE STATUS LIKE 'rpt_properties'");
        $nextTableIdid  = $tableIdid[0]->Auto_increment;
        $rpTdNo         = $nextTableIdid;
        /* Generate Property Code */
         /*switch (strlen($taxDeclNuumber)) {
            case '1':
                $propertyCode = '0000'.$taxDeclNuumber;
                break;
            case '2':
                $propertyCode = '000'.$taxDeclNuumber;
                break;
            case '3':
                $propertyCode = '00'.$taxDeclNuumber;
                break;  
            case '4':
                $propertyCode = '0'.$taxDeclNuumber;
                break;      
            
            default:
                $propertyCode = $taxDeclNuumber;
                break;
         }*/

        /* Generate Property Code */
        return $rpTdNo;
    }

    public function bulkUpload(Request $request){
        $this->is_permitted($this->slugs, 'upload');
        $arrType   = array("1"=>"Building Tax Declaration","2"=>"Building Floor Values");
        return view('rptbuilding.bulkUpload',compact('arrType'));
    }

    public function downloadbuildTDTemplate(Request $request){
        $status = ($request->has('status'))?$request->status:1;
        $this->setData('',config('constants.update_codes_land.DC'));
        $arrHeading = $this->_rptpropertybuilding->setValueToCommonColumns($this->data,1,$status);
        if(empty($arrHeading)){
            echo "Barangay, District or Locality Is missing";exit;
        }
        $arrHeading = $this->_rptpropertybuilding->insertNewArrayItem($arrHeading,'uc_code','pk_is_active');
        $arrHeading['pk_is_active'] = $status;
        $clients=DB::table('clients')->select(DB::raw('CONCAT("[",id,"]","=>","[",full_name,"]") as client_name'))->where('is_active',1)->get()->toArray();
       
        $arrEmployees = [];
        $arrRoofs = [];
        $arrFloors = [];
        $arrWalls = [];
        $arrClients = [];
        $arrLandReferences = [];
        $arrClasses = [];
        $preOwnerRef = [];
        $buildingKindCodes = [];
        foreach ($clients as $val) {
              $arrClients[]=$val->client_name;
        }
        foreach ($this->_rptpropertybuilding->getHrEmplyees() as $val) {
              $arrEmployees[]='['.$val->id.']'.'=>'.'['.$val->fullname.']';
        }
        foreach ($this->_rptpropertybuilding->getPropertyBuildingroof() as $val) {
                $arrRoofs[]='['.$val->id.']=>['.$val->rbr_building_roof_desc.']';
            }
        foreach ($this->_rptpropertybuilding->getPropertyBuildingrfloor() as $val) {
                $arrFloors[]='['.$val->id.']=>['.$val->rbf_building_flooring_desc.']';
        }
        foreach ($this->_rptpropertybuilding->getPropertyBuildingwall() as $val) {
                $arrWalls[]='['.$val->id.']=>['.$val->rbw_building_walling_desc.']';
        }
        foreach ($this->_rptpropertybuilding->getLandReferencesForMachine(session()->get('buildingSelectedBrgy'),$this->activeRevisionYear->id) as $val) {
                $arrLandReferences[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        }
        foreach ($this->_rptpropertybuilding->getPropertyClassesForExcelUpload() as $val) {
                $arrClasses[]='['.$val->id.']=>['.$val->pc_class_description.']';
            }
        foreach ($this->_rptpropertybuilding->getpreviousOwnerRefrences(session()->get('buildingSelectedBrgy'),$this->activeRevisionYear->id,1) as $val) {
                $preOwnerRef[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        } 
        foreach ($this->_rptpropertybuilding->getPropertyBuildKindsForExcelUpload() as $val) {
                $buildingKindCodes[]='['.$val->id.']=>['.$val->bk_building_kind_desc.']';
            }   
        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $allRecordCount = [count($arrClients),count($arrEmployees),count($arrRoofs),count($arrFloors),count($arrWalls),count($arrLandReferences),count($preOwnerRef),count($buildingKindCodes)];
        //dd($allRecordCount);
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        $contForEmployee = 0;
        for($i = 0; $i<max($allRecordCount); $i++){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                if($h_key == 'client_name'){
                    
                        $data[] = (isset($arrClients[$contForEmployee]))?$arrClients[$contForEmployee]:'';
                    
                }else if($h_key == 'employee_name'){
                    
                        $data[] = (isset($arrEmployees[$contForEmployee]))?$arrEmployees[$contForEmployee]:'';
                    
                }else if($h_key == 'roof_description'){
                    
                        $data[] = (isset($arrRoofs[$contForEmployee]))?$arrRoofs[$contForEmployee]:'';
                    
                }else if($h_key == 'floor_description'){
                   
                        $data[] = isset($arrFloors[$contForEmployee])?$arrFloors[$contForEmployee]:'';
                    
                }else if($h_key == 'wall_description'){
                   
                        $data[] = (isset($arrWalls[$contForEmployee]))?$arrWalls[$contForEmployee]:'';
                    
                }else if($h_key == 'land_reference'){

                        $data[] = (isset($arrLandReferences[$contForEmployee]))?$arrLandReferences[$contForEmployee]:'';

                }else if($h_key == 'class'){

                        $data[] = (isset($arrClasses[$contForEmployee]))?$arrClasses[$contForEmployee]:'';
                        
                }else if($h_key == 'previous_owner_reference_tds'){

                        $data[] = (isset($preOwnerRef[$contForEmployee]))?$preOwnerRef[$contForEmployee]:'';
                        
                }else if($h_key == 'kinds'){

                        $data[] = (isset($buildingKindCodes[$contForEmployee]))?$buildingKindCodes[$contForEmployee]:'';
                        
                }else{
                    $data[]=$h_val;
                }
            }
            $arrFields[$cnt] = $data;
            $contForEmployee++;
            $cnt++;
        }
        //dd($arrFields);
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection, WithEvents, WithMultipleSheets {
            protected $data;
            protected $rptpropObj;
            protected $rptpropMoObj;
            protected $mainData;
            public function __construct($data){
                $this->data = $data;
                $this->rptpropObj = new RptPropertyBuidingController;
                $this->rptpropMoObj = new RptProperty;
                $this->mainData = array_combine($this->data[0], $this->data[1]);
            }
            public function collection(){
                return $this->data;
            }
            public function sheets(): array{
                return [$this];
            }
            public function registerEvents(): array{
                return [
                AfterSheet::class => function(AfterSheet $event) {
                    // get layout counts (add 1 to rows for heading row)
                    $row_count = $this->data->count();
                    $column_count = count($this->data[0]);
                    /* For Client */
                    // set dropdown column
                    $drop_column = 'L';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AQ$2:$AQ$'.$row_count);
                    $validationForClient = $validation;
                    /* For Client */

                    /* For Administrator */
                    // set dropdown column
                    $drop_column_admin = 'M';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_admin."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AQ$2:$AQ$'.$row_count);
                    $validationForAdmin = $validation;
                    /* For Administrator */

                    /* For Building Kind COde */
                    // set dropdown column
                    $drop_column_kind = 'R';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_kind."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AX$2:$AX$'.count($this->rptpropMoObj->getPropertyBuildKindsForExcelUpload()));
                    $validationForKind = $validation;
                    /* For Building Kind COde */

                    /* For Pc Class COde */
                    // set dropdown column
                    $drop_column_class = 'S';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_class."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AW$2:$AW$'.count($this->rptpropObj->arrPropClasses));
                    $validationForClass = $validation;
                    /* For Pc Class COde */

                    /* For rp_constructed_month */
                    // set dropdown column
                    $drop_column_rp_constructed_month = 'U';
                    $months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rp_constructed_month."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1(sprintf('"%s"',implode(',',$months)));
                    $validationForconstructed_month = $validation;
                    /* For rp_constructed_month */

                     /* For rp_constructed_Year */
                    // set dropdown column
                    $drop_column_rp_constructed_year = 'T';
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rp_constructed_year."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setOperator(DataValidation::OPERATOR_BETWEEN );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be between 1500 and '.date("Y"));
                   /* $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Only numeric value allowed');*/
                    $validation->setFormula1('1500');
                    $validation->setFormula2(date("Y"));
                    $validationForconstructed_year = $validation;
                    /* For rp_constructed_Year */

                     /* For Land Reference */
                    // set dropdown column
                    $drop_column_landref = 'W';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_landref."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AV$2:$AV$'.count($this->rptpropMoObj->getLandReferencesForMachine($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'])));
                    $validationForLandRef = $validation;
                    /* For Land Reference */

                    /* For rp_occupied_month */
                    // set dropdown column
                    $drop_column_rp_occupied_month = 'Y';
                    $months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rp_occupied_month."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1(sprintf('"%s"',implode(',',$months)));
                    $validationForrp_occupied_month = $validation;
                    /* For rp_occupied_month */

                     /* For rp_occupied_year */
                    // set dropdown column
                    $drop_column_rp_occupied_year = 'X';
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rp_occupied_year."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setOperator(DataValidation::OPERATOR_BETWEEN );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be between 1500 and '.date("Y"));
                   /* $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Only numeric value allowed');*/
                    $validation->setFormula1('1500');
                    $validation->setFormula2(date("Y"));
                    $validationForoccupied_year = $validation;
                    /* For rp_occupied_year */


                    /* For rp_building_completed_year */
                    // set dropdown column
                    $drop_column_rp_building_completed_year = 'AA';
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rp_building_completed_year."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setOperator(DataValidation::OPERATOR_BETWEEN );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be between 1500 and '.date("Y"));
                   /* $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Only numeric value allowed');*/
                    $validation->setFormula1('1500');
                    $validation->setFormula2(date("Y"));
                    $validationForrp_building_completed_year = $validation;
                    /* For rp_building_completed_year */

                    /* For Roof Description A */
                    // set dropdown column
                    $drop_column_roofA = 'AD';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_roofA."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AS$2:$AS$'.count($this->rptpropObj->arrbuildingroof));
                    $validationForRoofA = $validation;
                    /* For Roof Description A */

                    /* For Roof Description B */
                    // set dropdown column
                    $drop_column_roofB = 'AE';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_roofB."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AS$2:$AS$'.count($this->rptpropObj->arrbuildingroof));
                    $validationForRoofB = $validation;
                    /* For Roof Description B */

                    /* For Roof Description C */
                    // set dropdown column
                    $drop_column_roofC = 'AF';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_roofC."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AS$2:$AS$'.count($this->rptpropObj->arrbuildingroof));
                    $validationForRoofC = $validation;
                    /* For Roof Description C */

                    /* For Floor Description A */
                    // set dropdown column
                    $drop_column_floorA = 'AG';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_floorA."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AT$2:$AT$'.count($this->rptpropObj->arrbuildingfloor));
                    $validationForFloorA = $validation;
                    /* For Floor Description A */

                    /* For Floor Description B */
                    // set dropdown column
                    $drop_column_floorB = 'AH';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_floorB."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AT$2:$AT$'.count($this->rptpropObj->arrbuildingfloor));
                    $validationForFloorB = $validation;
                    /* For Floor Description B */

                    /* For Floor Description C */
                    // set dropdown column
                    $drop_column_floorC = 'AI';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_floorC."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AT$2:$AT$'.count($this->rptpropObj->arrbuildingfloor));
                    $validationForFloorC = $validation;
                    /* For Floor Description C */

                    /* For Wall Description A */
                    // set dropdown column
                    $drop_column_wallA = 'AJ';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_wallA."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AU$2:$AU$'.count($this->rptpropObj->arrbuildingfwall));
                    $validationForWallA = $validation;
                    /* For Floor Description A */

                    /* For Wall Description B */
                    // set dropdown column
                    $drop_column_wallB = 'AK';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_wallB."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AU$2:$AU$'.count($this->rptpropObj->arrbuildingfwall));
                    $validationForWallB = $validation;
                    /* For Floor Description B */

                    /* For Wall Description C */
                    // set dropdown column
                    $drop_column_wallC = 'AL';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_wallC."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AU$2:$AU$'.count($this->rptpropObj->arrbuildingfwall));
                    $validationForWallC = $validation;
                    /* For Floor Description C */

                    /* For AppriasedBy */
                    // set dropdown column
                    $drop_column_ap_by = 'AM';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_ap_by."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AR$2:$AR$'.count($this->rptpropObj->arremployees));
                    $validationForAppBy = $validation;
                    /* For AppriasedBy */

                    /* For Recommended */
                    // set dropdown column
                    $drop_column_rec_by = 'AN';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rec_by."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AR$2:$AR$'.count($this->rptpropObj->arremployees));
                    $validationForRecBy = $validation;
                    /* For Recommended */

                    /* For Approved By */
                    // set dropdown column
                    $drop_column_app_by = 'AO';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_app_by."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AR$2:$AR$'.count($this->rptpropObj->arremployees));
                    $validationForAppBy = $validation;
                    /* For Approved By */

                    /* For Previous Owner Reference */
                    // set dropdown column
                    $drop_column_pre_owner = 'AP';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_pre_owner."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$AY$2:$AY$'.count($this->rptpropMoObj->getpreviousOwnerRefrences($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'],1)));
                    $validationForPreOwner = $validation;
                    /* For Previous Owner Reference */

                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validationForClient);
                        $event->sheet->getCell("{$drop_column_admin}{$i}")->setDataValidation(clone $validationForAdmin);

                        $event->sheet->getCell("{$drop_column_kind}{$i}")->setDataValidation(clone $validationForKind);
                        $event->sheet->getCell("{$drop_column_class}{$i}")->setDataValidation(clone $validationForClass);
                        $event->sheet->getCell("{$drop_column_rp_constructed_month}{$i}")->setDataValidation(clone $validationForconstructed_month);
                        $event->sheet->getCell("{$drop_column_rp_constructed_year}{$i}")->setDataValidation(clone $validationForconstructed_year);

                        $event->sheet->getCell("{$drop_column_rp_occupied_month}{$i}")->setDataValidation(clone $validationForrp_occupied_month);
                        $event->sheet->getCell("{$drop_column_rp_occupied_year}{$i}")->setDataValidation(clone $validationForoccupied_year);
                        $event->sheet->getCell("{$drop_column_rp_building_completed_year}{$i}")->setDataValidation(clone $validationForrp_building_completed_year);

                        $event->sheet->getCell("{$drop_column_roofA}{$i}")->setDataValidation(clone $validationForRoofA);
                        $event->sheet->getCell("{$drop_column_roofB}{$i}")->setDataValidation(clone $validationForRoofB);
                        $event->sheet->getCell("{$drop_column_roofC}{$i}")->setDataValidation(clone $validationForRoofC);
                        $event->sheet->getCell("{$drop_column_floorA}{$i}")->setDataValidation(clone $validationForFloorA);
                        $event->sheet->getCell("{$drop_column_floorB}{$i}")->setDataValidation(clone $validationForFloorB);
                        $event->sheet->getCell("{$drop_column_floorC}{$i}")->setDataValidation(clone $validationForFloorC);
                        $event->sheet->getCell("{$drop_column_wallA}{$i}")->setDataValidation(clone $validationForWallA);
                        $event->sheet->getCell("{$drop_column_wallB}{$i}")->setDataValidation(clone $validationForWallB);
                        $event->sheet->getCell("{$drop_column_wallC}{$i}")->setDataValidation(clone $validationForWallC);

                        $event->sheet->getCell("{$drop_column_landref}{$i}")->setDataValidation(clone $validationForLandRef);

                        $event->sheet->getCell("{$drop_column_ap_by}{$i}")->setDataValidation(clone $validationForAppBy);
                        $event->sheet->getCell("{$drop_column_rec_by}{$i}")->setDataValidation(clone $validationForRecBy);
                        $event->sheet->getCell("{$drop_column_app_by}{$i}")->setDataValidation(clone $validationForAppBy);

                        $event->sheet->getCell("{$drop_column_pre_owner}{$i}")->setDataValidation(clone $validationForPreOwner);
                    }

                    // set columns to autosize
                    for ($i = 1; $i <= $column_count; $i++) {
                        $column = Coordinate::stringFromColumnIndex($i);
                        $event->sheet->getColumnDimension($column)->setAutoSize(true);
                    }
                },
            ];
    }
        };
        // Export the data to Excel
        return Excel::download($exportClass, 'building-tax-declaration.xlsx');
    }

    public function downloadBuildAppraisalTemplate(){
        // Define the data to export
        $arrHeading = array('rp_code'=>'','pc_class_code'=>'','pc_building_kind'=>'','rpbfv_floor_no' => '','rpbfv_floor_area'=>'','rpbfv_floor_unit_value' => '', 'pau_actual_use_code'=>'','rpbfv_floor_additional_value'=>'', 'rpbfv_floor_adjustment_value'=>'', 'rp_tax_declaration_no' => '','class'=>'','kind'=>'', 'class_subclse_actualuses'=>'','actualuses' => '');

         
        $arrBusn = [];
        $arrClass = [];
        $arrKind = [];
        foreach ($this->bulkUploadBATds as $key => $value) {
            $arrBusn[] = $value->rp_tax_declaration_no;
            $arrClass[] = $value->pc_class_description;
            $arrKind[] = $value->bk_building_kind_desc;
        }
        //dd($arrKind);
        $landUnitValue = $this->bulkUploadBAUnitValues;
        
        $arrHeadData=array();
        $actualUses = [];
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        foreach($this->_rptpropertybuilding->getPropertyActualUseForExcelUpload() AS $h_key => $h_val){

            $actualUses[] = '['.$h_val->id.']=>['.$h_val->pc_class_description.']=>['.$h_val->pau_actual_use_desc.']';
        }
        $allArrayCount = [count($arrBusn),count($landUnitValue),count($actualUses)];
       // dd($actualUses);
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        for($i=0; $i<max($allArrayCount); $i++){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                
                if($h_key == 'rp_tax_declaration_no'){
                    $data[] = (isset($arrBusn[$i]))?$arrBusn[$i]:'';
                }else if($h_key == 'class_subclse_actualuses'){
                    $data[] = (isset($landUnitValue[$i]->class_subclse_actualuses))?$landUnitValue[$i]->class_subclse_actualuses:'';
                }else if($h_key == 'actualuses'){
                    $data[] = (isset($actualUses[$i]))?$actualUses[$i]:'';
                }else if($h_key == 'class'){
                    $data[] = (isset($arrClass[$i]))?$arrClass[$i]:'';
                }else if($h_key == 'kind'){
                    $data[] = (isset($arrKind[$i]))?$arrKind[$i]:'';
                }else{
                    $data[]=$h_val;
                }
            }
            $arrFields[$cnt] = $data;
            $cnt++;
        }
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection,WithEvents {
            protected $data;
            protected $controllerObj;
            protected $rptpropMoObj;
            public function __construct($data){
                $this->data = $data;
                $this->controllerObj = new RptPropertyBuidingController;
                $this->rptpropMoObj = new RptProperty;
            }
            public function collection(){
                return $this->data;
            }
            public function registerEvents(): array{
                return [
                AfterSheet::class => function(AfterSheet $event) {
                    // get layout counts (add 1 to rows for heading row)
                    $row_count = $this->data->count();
                    $column_count = count($this->data[0]);
                    /* For Tax Declaration */
                    // set dropdown column
                    $drop_column = 'A';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$J$2:$J$'.count($this->controllerObj->bulkUploadBATds));
                    $validationForTds = $validation;
                    /* For Tax Declaration */

                    /* For Unit Value */
                    // set dropdown column
                    $drop_column_uv = 'F';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_uv."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$M$2:$M$'.count($this->controllerObj->bulkUploadBAUnitValues));
                     $validationForUnitValue = $validation;
                    /* For Unit Value */

                     /* For Total Land Area */
                    // set dropdown column
                    $drop_column_la = 'E';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_la."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value Allowed');
                    $validationForArea = $validation;
                    /* For Unit Value */

                     /* For Additional Value */
                    // set dropdown column
                    $drop_column_av = 'H';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_av."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Numeric Value Allowed');
                    $validationForAV = $validation;
                    /* For Additional Value */

                     /* For Adjustment  Value */
                    // set dropdown column
                    $drop_column_aj = 'I';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_aj."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Numeric Value Allowed');
                    $validationForAJ = $validation;
                    /* For Adjustment Value */

                    /* For Total Land Area */
                    // set dropdown column
                    $drop_column_floorno = 'D';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_floorno."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Integer Value Allowed');
                    $validationForFloorNo = $validation;
                    /* For Unit Value */

                    /* For Actual Uses */
                    // set dropdown column
                    $drop_column_actual_uses = 'G';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_actual_uses."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$N$2:$N$'.count($this->rptpropMoObj->getPropertyActualUseForExcelUpload()));
                    $validationForActualUses = $validation;
                    /* For Actual Uses */

                
                   
                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validationForTds);
                        $event->sheet->getCell("{$drop_column_uv}{$i}")->setDataValidation(clone $validationForUnitValue);
                        $event->sheet->getCell("{$drop_column_la}{$i}")->setDataValidation(clone $validationForArea);
                        $event->sheet->getCell("{$drop_column_floorno}{$i}")->setDataValidation(clone $validationForFloorNo);

                        $event->sheet->getCell("{$drop_column_av}{$i}")->setDataValidation(clone $validationForAV);
                        $event->sheet->getCell("{$drop_column_aj}{$i}")->setDataValidation(clone $validationForAJ);

                        $event->sheet->getCell("{$drop_column_actual_uses}{$i}")->setDataValidation(clone $validationForActualUses);
                        
                    }
                    for ($i = 2; $i <= $row_count; $i++) {
                        $event->sheet->getCell("B{$i}")->setValue('=LOOKUP(A'.$i.',K2:K'.count($this->controllerObj->bulkUploadBATds).')');
                        $event->sheet->getCell("C{$i}")->setValue('=LOOKUP(A'.$i.',L2:L'.count($this->controllerObj->bulkUploadBATds).')');
                    }

                    // set columns to autosize
                    for ($i = 1; $i <= $column_count; $i++) {
                        $column = Coordinate::stringFromColumnIndex($i);
                        $event->sheet->getColumnDimension($column)->setAutoSize(true);
                    }
                },
            ];
    }
        };
        // Export the data to Excel
        return Excel::download($exportClass, 'building_floor_values.xlsx');
    }

    public function uploadBulkBuildData(Request $request){
        $upload_type =  $request->input('upload_type');
        if($upload_type==1){
            return $this->uploadBuildingTaxDeclaration($request);
        }else if($upload_type==2){
            return $this->uploadBuildingAppraisal($request);
        }
    }

    public function uploadBuildingTaxDeclaration($request){
     $upload_type =  $request->input('upload_type');

        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptpropertybuilding->fliterData($excelData[$i],$excelData[0]);
                    $response = $this->_rptpropertybuilding->checkRequiredFields($newDataToSave);
                   //dd($response);
                    if($response['status']){
                        $approvalData = $this->_rptpropertybuilding->createDataForApproval($newDataToSave);
                        $dataToSave = $response['data'];
                        unset($dataToSave['rp_app_appraised_by']);
                        unset($dataToSave['rp_app_recommend_by']);
                        unset($dataToSave['rp_app_approved_by']);
                        unset($dataToSave['rvy_revision_year']);
                        if(isset($dataToSave['previous_owner_reference'])){
                            unset($dataToSave['previous_owner_reference']);
                        }
                       
                        try {
                            $lastinsertedId = $this->_rptpropertybuilding->addData($dataToSave);
                            
                            if($dataToSave['pk_is_active'] == 1){
                               $this->_rptpropertybuilding->generateTaxDeclarationAndPropertyCode($lastinsertedId);
                             }
                             if($dataToSave['pk_is_active'] == 9){
                               $this->_rptpropertybuilding->generateTaxDeclarationAndPropertyCode($lastinsertedId,true);
                             }
                             $property = DB::table('rpt_properties')->where('id',$lastinsertedId)->first();
                            $approvalData['rp_code'] = $lastinsertedId;
                            $approvalData['rp_property_code'] = $property->rp_property_code;
                            $this->_rptpropertybuilding->addApprovalForm($approvalData);

                            if($property->pk_is_active == 1){
                                $this->_rptpropertybuilding->addDataInAccountReceivable($lastinsertedId);
                            }if($property->pk_is_active == 9){
                                $this->updatePropertyHistory($lastinsertedId,RptProperty::find($dataToSave['created_against']),true);
                                $this->_rptpropertybuilding->updateChain($lastinsertedId);
                            }
                            
                            $noOfRecordsExecuted++;
                        } catch (\Exception $e) {
                            dd ($e);exit;
                        }
                    }
                    
                }
                $response = ['status' => true, 'message' => $noOfRecordsExecuted.' Records Uploaded successfully.'];
                return json_encode($response);
        }
    }

    public function uploadBuildingAppraisal($request){
        $upload_type =  $request->input('upload_type');

        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptpropertybuilding->fliterApraisalData($excelData[$i],$excelData[0]);
                    $response = $this->_rptpropertybuilding->checkAppraisalRequiredFields($newDataToSave);
                    if($response['status']){
                        $dataToSave = $response['data'];
                        try {
                            unset($dataToSave['pk_code']);
                            unset($dataToSave['bk_building_kind_code']);
                            $lastinsertedId = $this->_rptpropertybuilding->addFloorValueDetail($dataToSave);
                            $totalFloors = DB::table('rpt_building_floor_values')->where('rp_code',$dataToSave['rp_code'])->count();
                            DB::table('rpt_building_floor_values')->where('rp_code',$dataToSave['rp_code'])->update(['rpbfv_total_floor'=>$totalFloors]);
                            $freshRequestObj = new Request;
                            $freshRequestObj->merge(['id' => $dataToSave['rp_code']]);
                            $buildOtherData = $this->autofillmainform($freshRequestObj);
                            $buildOtherData = json_decode($buildOtherData);
                            $dataToUpdateInMainTable = [
                                'rp_building_no_of_storey' => (isset($buildOtherData['storeys']))?$buildOtherData['storeys']:'',
                                'rp_building_gf_area' => (isset($buildOtherData['areaOfGround']))?$buildOtherData['areaOfGround']:'',
                                'rp_building_total_area' => (isset($buildOtherData['totalArea']))?$buildOtherData['totalArea']:''
                            ];
                            $this->_rptpropertybuilding->updateData($dataToSave['rp_code'],$dataToUpdateInMainTable);
                            $property = DB::table('rpt_properties')->where('id',$dataToSave['rp_code'])->first();
                            $this->updateAssValueBasedDepRate($dataToSave['rp_code'],$property->pc_class_code,$property->brgy_code_id,$property->rvy_revision_year_id,0);
                            $this->_rptpropertybuilding->generateTaxDeclarationAform($property->id);
                            $this->_rptpropertybuilding->updateAccountReceiaveableDetails($property->id);
                            $this->_rptpropertybuilding->syncAssedMarketValueToMainTable($property->id);
                            $noOfRecordsExecuted++;
                        } catch (\Exception $e) {
                            dd ($e);exit;
                        }
                    }
                    
                }
                $response = ['status' => true, 'message' => $noOfRecordsExecuted.' Records Uploaded successfully.'];
                return json_encode($response);
        }
    }
}
