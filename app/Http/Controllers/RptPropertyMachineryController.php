<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptPropertyMachinery;
use App\Models\Barangay;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\ProfileMunicipality;
use App\Models\RptPropertyApproval;
use App\Models\RevisionYear;
use App\Models\RptPropertyHistory;
use App\Models\RptProperty;
use App\Models\RptPropertyMachineAppraisal;
use App\Models\RptPropertyStatus;
use App\Models\RptPropertyAnnotation;
use App\Models\RptPropertySworn;
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

class RptPropertyMachineryController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrRevisionYears = array(""=>"Select Revision Year");
    public $arrUpdateCodesDirectCancel = [];
    public $arrBarangay = array(""=>"Select Barangay");
    public $arrLocCodes = array(""=>"Select Locality");
    public $arrDistNumbers = array(""=>"Select Locality");
    public $arrUpdateCodes = [];
    public $arrPropKindCodes = array(""=>"Select Property Kind");
    public $arrStripingCodes = array(""=>"Select Stripping Code");
    public $arrprofile = [];
    public $arrSubclasses = array(""=>"Please Select");
    public $arrPropClasses = [];
    public $arrPlantTreesCode = ["" => 'Select Plant/Tree Code'];
    public $arremployees = [];
    public $activeMuncipalityCode = "";
    public $activeRevisionYear = "";
    public $yeararr = [];
    public $propertyKind                   = 'M';
    public $landTaxDeclarationss = [];
    public $buildTaxDeclarationss = [];
    public $buildDetails          = [];
    public $landDetails           = [];
    public $previousOwnerRefrences = [];
    private $slugs;
    public $bulkUploadMATds = [];
    public function __construct(){
	        $this->_bploApplication = new BploApplication();
	        $this->_commonmodel = new CommonModelmaster();  
	        $this->_rptpropertymachinery = new RptProperty();
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
                "land_owner" => "",
	            "rp_pin_no" => "",
	            "rp_pin_suffix" => "",
	            "rp_section_no_bref" => "",
                "rp_pin_no_bref" => "",
                "rp_pin_suffix_bref"=>"",
                "rp_code_bref" => "",
                "rp_code_lref" => "",
                "rp_section_no_lref"=>"",
                "rp_pin_no_lref" => "",
                "building_owner" => "",
                "rp_code_lref" => "",
                "rp_pin_suffix_lref" => "",
                "rpo_code_lref" => "", 
	            "loc_group_brgy_no" => "",
	            "rp_location_number_n_street" => "",
	            "uc_code" => "",
	            "update_code" => "",
	            "property_owner_address" => "",
	            "rpo_code" => "",
	            /*"adminstrator" => "",*/
	            "rp_administrator_code_address" => "",
	            "rp_administrator_code" => "",
                "created_against" => "",
                "rp_assessed_value" => ""
	          
	        ];

	        $this->approvedata =array('id'=>'','rp_app_taxability'=>'','rp_app_posting_date'=>'','rp_modified_by'=>'','rp_app_effective_year'=>'','rp_modified_by'=>'','uc_code'=>'','rp_app_memoranda'=>'','pk_is_active'=>'','rp_app_memoranda'=>'');
	        foreach ($this->_bploApplication->getBarangay() as $val) {
	            $this->arrBarangay[$val->id]=$val->brgy_code.' - '.$val->brgy_name;
	        } 


	        foreach ($this->_bploApplication->getSubClasses() as $val) {
	            $this->arrSubclasses[$val->id]=$val->subclass_description;
	        } 
	        foreach ($this->_rptpropertymachinery->getRevisionYears() as $val) {
	            $this->arrRevisionYears[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
	        }foreach ($this->_rptpropertymachinery->getUpdateCodesForCancellation('M') as $val) {
            $this->arrUpdateCodesDirectCancel[$val->id]=$val->uc_code.'-'.$val->uc_description;
            }foreach ($this->_rptpropertymachinery->getLocalityCodes() as $val) {
	            $this->arrLocCodes[$val->id]=$val->loc_local_code.'-'.$val->loc_local_name;
	        }foreach ($this->_rptpropertymachinery->getDistrictCodes() as $val) {
	            $this->arrDistNumbers[$val->id]=$val->dist_code;
	        }foreach ($this->_rptpropertymachinery->getUpdateCodes('L') as $val) {
	            $this->arrUpdateCodes[$val->id]=$val->uc_code.'-'.$val->uc_description;
	        }foreach ($this->_rptpropertymachinery->getPropClasses() as $val) {
	            $this->arrPropClasses[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
	        }foreach ($this->_rptpropertymachinery->getPropKindCodes() as $val) {
	            $this->arrPropKindCodes[$val->id]=$val->pk_description;
	        }foreach ($this->_rptpropertymachinery->getStrippingCodes() as $val) {
	            $this->arrStripingCodes[$val->id]=$val->rls_description;
	        }foreach ($this->_rptpropertymachinery->getPlantTreeCodes() as $val) {
	            $this->arrPlantTreesCode[$val->id]=$val->pt_ptrees_description;
	        }foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
	            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
	        }foreach ($this->_rptpropertymachinery->getHrEmplyees() as $val) {
                $this->arremployees[$val->id]=$val->fullname;
	        }


            $this->bulkUploadMATds = $this->_rptpropertymachinery->getDataForMachineAppraisalBulkUpload();

	        $this->activeMuncipalityCode = $this->_muncipality->getActiveMuncipalityCode();
	        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
		    $this->slugs = 'real-property/property-data/machinery';    
	}

    public function createPinSuffix(Request $request){
        $id       = $request->id;
        $propId   = $request->propId;
        $proCount = $this->_rptpropertymachinery->getMachinerypropertycount($id,$propId); 
        $suffixpincount = $proCount + 1;
        $response = [
            'status' => 'success',
            'data'   => ['suffix' => 'M'.$suffixpincount]
        ];
        return response()->json($response);
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $request->session()->forget('machinePropertyAppraisals');
        $request->session()->forget('approvalFormDataMachine');
        $request->session()->forget('propertyAnnotationForBuilding');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $revisionYears = $this->arrRevisionYears;
        $updateCodes = $this->makeSelectListOfUpdateCodes();
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $arrBarangay = $this->arrBarangay;
        return view('rptmachinery.index',compact('updateCodes','revisionYears','activeRevisionYear','arrBarangay'));
    }
    public function makeSelectListOfUpdateCodes($value = ''){
        $html = '<select class="form-control selected_update_code" required="required" name="selected_update_code"><option value="">Select Update Code</option>';
        $restrictCodes = [config('constants.update_codes_land.DC'),config('constants.update_codes_land.GR')];
        foreach ($this->_rptpropertymachinery->getMachineryUpdateCodes() as $val) {
            if(!in_array($val->id,$restrictCodes)){
            $html .= '<option value="'.$val->id.'" data-code="'.$val->uc_code.'">'.$val->uc_code.'-'.$val->uc_description.'</option>';
        }
        }
         $html .= '</select>';
         return $html;
    }



    public function getList(Request $request){
        $request->session()->forget('machinePropertyAppraisals');
        $request->session()->forget('approvalFormDataMachine');
        $request->session()->forget('propertyAnnotationForBuilding');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->request->add(['property_kind' => $this->propertyKind]);
        $data=$this->_rptpropertymachinery->getMachineList($request);
        //dd($data);
        $arr=array();
        $i="0";   
        $count = $request->start+1; 
        foreach ($data['data'] as $row){    
            $arr[$i]['no']=$count;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $arr[$i]['taxpayer_name']=$row->taxpayer_name;
            $arr[$i]['brgy']=$row->brgy_name;
            $description = wordwrap($row->description, 20, "<br />\n");
            $arr[$i]['desc']="<div class='showLess'>".$description."</div>";
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $arr[$i]['rp_cadastral_lot_no']=$row->rp_cadastral_lot_no;
            $arr[$i]['market_value']=Helper::money_format($row->machine_market_value);
            $arr[$i]['assessed_value']=Helper::money_format($row->machine_assessed_value);
            $uc_code = $row->updatecode->uc_code.'-'.$row->updatecode->uc_description;
            $arr[$i]['uc_code']="<span class='showLess2'>".$uc_code."</span>";
            $arr[$i]['effectivity']=$row->rp_app_effective_year;
            $reg_emp_name = wordwrap($row->reg_emp_name, 20, "<br />\n");
            $arr[$i]['reg_emp_name']="<div class='showLess'>".$reg_emp_name."</div>";
            $arr[$i]['created_date']=date("d M, Y",strtotime($row->created_at));
            $arr[$i]['pk_is_active'] = ($row->pk_is_active == 1) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':(($row->rp_app_cancel_is_direct == 1)?'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Direct Cancelled</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
            //$arr[$i]['action']  = $this->updateCodeSelectList($row->id);
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

    public function calculateAssesementLeval($request,$landApprasals){
        $propertyKind = $this->_rptpropertymachinery->getKindIdByCode($this->propertyKind);
        $classDetails = $this->_rptpropertymachinery->getClassDetails($request->propertyClass);
        $defaultActualUseId = 0;

        if(!empty($classDetails)){
            $defaultActualUse = DB::table('rpt_property_actual_uses')
                                    ->where('pc_class_code',$request->propertyClass)
                                    ->where('pau_is_active',1)
                                    ->where('pau_actual_use_desc',$classDetails->pc_class_description)
                                    ->first();

            if($defaultActualUse != null){
                $defaultActualUseId = $defaultActualUse->id;   
            }                        
        }
        $request->request->add(['propertyActualUseCode' => $defaultActualUseId,'propertyKind'=>$propertyKind]);

        if(!$landApprasals->isEmpty()){
            $request->merge(
                    [
                    'propertyActualUseCode' => $defaultActualUseId,
                    'propertyKind'=>$propertyKind,
                    'totalMarketValue'=>$landApprasals->sum('rpma_market_value')
                ]
                );
            //dd($request->all());
            $asseseLevalData = $this->_rptpropertymachinery->getAssessementLevel($request);
            //dd($asseseLevalData);
            $assesLevel      = (isset($asseseLevalData->assessementRelations) && !$asseseLevalData->assessementRelations->isEmpty())?$asseseLevalData->assessementRelations[0]->assessment_level:0;
            //dd($assesLevel);
            
            foreach ($landApprasals as $key => $land) {
                //dd($land);
                if($land->id != null){
                    //dd($land);
                    $assessedValue   = ($land->rpma_market_value*$assesLevel)/100;
                    $dataToUpdateInAppraisal = [
                        'pc_class_code' => $request->propertyClass,
                        'pau_actual_use_code' => $request->propertyActualUseCode,
                        'al_assessment_level' => $assesLevel,
                        'rpm_assessed_value' => $assessedValue
                    ];
                    $this->_rptpropertymachinery->updateMachineAppraisalDetail($land->id,$dataToUpdateInAppraisal);
                }else{
                    $assessedValue   = ($land->rpma_market_value*$assesLevel)/100;
                    $land->pc_class_code = $request->propertyClass;
                    $land->pau_actual_use_code = $request->propertyActualUseCode;
                    $land->al_assessment_level = $assesLevel;
                    $land->rpm_assessed_value = $assessedValue;
                    $request->session()->put('machinePropertyAppraisals.'.$key,$land);
                }
        }
        if($asseseLevalData == false){
                return false;
            }else{
                return true;
            }

        }
        
    }

    public function loadAssessementSummary(Request $request){
        
        $sessionData = collect($request->session()->get('machinePropertyAppraisals'));
        $result = true;
        $id = $request->input('id');
        $classCode = $request->input('pc_class_code');
        if($id == 0){
             $result = $this->calculateAssesementLeval($request,$sessionData);
             $landApprasals = collect($request->session()->get('machinePropertyAppraisals'));
        }else{
            $landApprasals = RptPropertyMachineAppraisal::where('rp_code',$id)->get();
            $result = $this->calculateAssesementLeval($request,$landApprasals);
            $landApprasals = RptPropertyMachineAppraisal::where('rp_code',$id)->get();
            $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($id);
        }
        //dd($landApprasals);
        //$assessementsummaryData = $landApprasals->groupBy('pau_actual_use_code');
        $newAssesmentSummary    = [];
        if (!$landApprasals->isEmpty()) {
            $actualUseDetails = $this->_rptpropertymachinery->getClassDetails($request->propertyClass);
            $newAssesmentSummary = [
                'property_kind'                   => 'Machine',
                'actualUse'                       => (isset($actualUseDetails->pc_class_description))?$actualUseDetails->pc_class_description:'',
                'marketValue'                     => $landApprasals->sum('rpma_market_value'),
                'AssesseMentLevel'                => $landApprasals[0]->al_assessment_level,
                'assessedValue'                   => $landApprasals->sum('rpm_assessed_value'),
            ];
           // $newAssesmentSummary[] = $rawSummary;
        }
        $newAssesmentSummary = (object)$newAssesmentSummary;
        $view = view('rptmachinery.ajax.assessementsummary',compact('newAssesmentSummary'))->render();
        return response()->json([
                'status' => 'success',
                'view'    => $view,
                'assessementLevel' => $result
            ]);
        //echo $view;
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
            $dataToSave['rpss_modified_by'] = \Auth::user()->id;
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptpropertymachinery->updatePropertyStatusData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == ''){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rpss_registered_by'] = \Auth::user()->id;
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertymachinery->addPropertyStatusData($dataToSave);
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
            $dataToSave['rpa_registered_by'] = \Auth::user()->id;
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertymachinery->addAnnotationData($dataToSave);
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

    public function searchLandOrBuilding(Request $request){
        
       $properyKind = $request->propertyKind;
       $landPropertyKindId = $this->_rptpropertymachinery->getKindIdByCode($properyKind); 
        $validationArray = ['brgy_code_id' => 'required'];
        if($properyKind == "B"){
            $validationArray['rp_td_no_bref'] = 'required';
        }else{
            $validationArray['rp_td_no_lref'] = 'required';
        }
        //dd($landPropertyKindId);
        $propertyDetails = [];
        $validator = \Validator::make(
            $request->all(), $validationArray,
            [
                'rp_td_no_bref.required' => 'Required Field', 
                'rp_td_no_lref.required'=>'Required Field',
                'brgy_code_id.required'=>'Required Field',
            ]
        );
        $validator->after(function ($validator) use($landPropertyKindId, &$propertyDetails,$properyKind) {
            $data = $validator->getData();
                $oldPropertyData = RptProperty::with(['landAppraisals','propertyOwner'])->where('id',$data['rp_td_no_lref'])
                                                ->where('brgy_code_id',$data['brgy_code_id'])
                                                ->where('pk_id',$landPropertyKindId)
                                                ->where('is_deleted',0)
                                                ->first();
                $propertyDetails = $oldPropertyData;  
                //dd($oldPropertyData);                              
                if($oldPropertyData == null){
                    if($properyKind == "L"){
                        $validator->errors()->add('rp_td_no_lref', 'No Td found!');
                    }else{
                         $validator->errors()->add('rp_td_no_bref', 'No Td found!');
                    }
                    
                }
              
            
    });
        $arr=array('status'=>'validation_error','data'=>['propertyKind'=>$request->propertyKind]);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $dataToReturn = [
            'rp_section_no_lref' => $propertyDetails->rp_section_no,
            'rp_pin_no_lref'=> $propertyDetails->rp_pin_no,
            'rp_pin_declaration_no'=> $propertyDetails->rp_pin_declaration_no,
            'rp_pin_suffix_lref' => $propertyDetails->rp_pin_suffix,
            'rpo_code_lref' => $propertyDetails->rpo_code,
            'land_owner' => (isset($propertyDetails->propertyOwner))?$propertyDetails->propertyOwner->standard_name:'',
            'rp_section_no_bref' => $propertyDetails->rp_section_no,
            'rp_pin_no_bref'=> $propertyDetails->rp_pin_no,
            'rp_pin_suffix_bref' => $propertyDetails->rp_pin_suffix,
            'building_owner' => (isset($propertyDetails->propertyOwner))?$propertyDetails->propertyOwner->standard_name:'',
            'propertyKind' => $properyKind,
            'rp_code' => $propertyDetails->id,
            'rp_code_lref' => $propertyDetails->rp_code_lref,
            'buildRefLandTdNo' => (isset($propertyDetails->buildingReffernceLand->rp_tax_declaration_no))?$propertyDetails->buildingReffernceLand->rp_tax_declaration_no:''
        ];
        //dd($dataToReturn);
        return response()->json(['status' => 'success','data'=>$dataToReturn]);
    }

    public function updateCodeSelectList($id = ''){
        $select = '<div class="font-awesome"><select class="form-control updatecodefunctionality fa" name="updatecodefunctionality" style="width:100px;">';
         $select .= '<option value="">Select Action</option><option class="fa" value="'.$id.'" data-actionname="edit" data-propertyid="'.$id.'">&#xf044 &nbsp;Edit</option><option value="'.$id.'" class="fa" data-actionname="print" data-propertyid="'.$id.'">&#xf02f &nbsp;Print</option><option value="'.$id.'" class="fa" data-actionname="printfaas" data-propertyid="'.$id.'">&#xf02f &nbsp;Print FAAS</option><option value="'.$id.'" data-actionname="updatecode" class="fa" data-propertyid="'.$id.'">&#xf0c9 &nbsp;Update Code</option>';
        $select .= '</select></div>';
        return $select;
    }

    public function setData($propertyId = '', $updateCode = ''){
        if(empty($this->activeRevisionYear) || empty($this->activeMuncipalityCode)){
            return [];
        }
        //$updateCodeDetails = $this->_rptpropertymachinery->getUpdateCodeById($updateCode);
        $updateCodeDetails = (in_array($updateCode,array_values(config('constants.update_codes_land'))))?array_flip(config('constants.update_codes_land'))[$updateCode]:'';
        $this->data['update_code'] = $updateCodeDetails;
        $this->data['uc_code'] = $updateCode;
        if($updateCodeDetails == ''){
            return [];
        }
        $selectedPropertyDetails = $this->_rptpropertymachinery->with([
                'revisionYearDetails',
                'machineReffernceLand',
                'machineReffernceBuild'
            ])->where('id',$propertyId)->first();
        switch($updateCodeDetails){
            case 'TR':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
               /* $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;*/
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                /*$rfs=$selectedPropertyDetails->rp_code_bref;
                foreach ($this->_rptpropertymachinery->gettaxDetailsId($rfs) as $val) {
                    $this->buildTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }
                foreach ($this->_rptpropertymachinery->getBuildTaxDeclaresionNODetails($selectedPropertyDetails->rpo_code,$selectedPropertyDetails->brgy_code_id,$selectedPropertyDetails->rvy_revision_year_id) as $val) {
                    $this->buildTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }

                $rfsLand=$selectedPropertyDetails->rp_code_lref;
                foreach ($this->_rptpropertymachinery->gettaxDetailsId($rfsLand) as $val) {
                    $this->landTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }
                foreach ($this->_rptpropertymachinery->getTaxDeclaresionNODetails($selectedPropertyDetails->rpo_code,$selectedPropertyDetails->brgy_code_id,$selectedPropertyDetails->rvy_revision_year_id) as $val) {
                    $this->landTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
                }
                $this->buildDetails = DB::table('rpt_properties')
                                      ->select('rp_pin_declaration_no')
                                      ->where('id',$selectedPropertyDetails->rp_code_bref)
                                      ->first();
                $this->landDetails = DB::table('rpt_properties')
                                      ->select('rp_pin_declaration_no')
                                      ->where('id',$selectedPropertyDetails->rp_code_lref)
                                      ->first();*/
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';


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
                   // 'rp_app_approved_date' => $selectedPropertyDetails->propertyApproval->rp_app_approved_date,
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
                session()->put('approvalFormDataMachine', (object)$arrApprove);
                $data = (object)$this->data;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
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
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';

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
                session()->put('approvalFormDataMachine', (object)$arrApprove);
                $data = (object)$this->data;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'CS':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';

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
                session()->put('approvalFormDataMachine', (object)$arrApprove);
                $data = (object)$this->data;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
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
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';

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
                session()->put('approvalFormDataMachine', (object)$arrApprove);
                $data = (object)$this->data;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
                $data->property_owner_details              = $selectedPropertyDetails->property_owner_details;
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
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';

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
                session()->put('approvalFormDataMachine', (object)$arrApprove);
                $data = (object)$this->data;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
                $data->property_owner_details              = $selectedPropertyDetails->property_owner_details;
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'DUP':
             $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rvy_revision_year'] = $selectedPropertyDetails->revisionYearDetails->rvy_revision_year;
                $this->data['rvy_revision_code'] =$selectedPropertyDetails->rvy_revision_code;
                $this->data['rp_property_code'] = $selectedPropertyDetails->rp_property_code;
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';
                $this->data['created_against'] = $selectedPropertyDetails->id;
                $data = (object)$this->data;

                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';

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
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_code_bref'] = $selectedPropertyDetails->rp_code_bref;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->brgy_name;
                
                $this->data['rp_section_no_bref'] = $selectedPropertyDetails->rp_section_no_bref;
                $this->data['rp_pin_no_bref'] = $selectedPropertyDetails->rp_pin_no_bref;
                $this->data['rp_pin_suffix_bref'] = $selectedPropertyDetails->rp_pin_suffix_bref;
                $this->data['rp_code_lref'] = $selectedPropertyDetails->rp_code_lref;
                $this->data['rp_section_no_lref'] = $selectedPropertyDetails->rp_section_no_lref;
                $this->data['rp_pin_no_lref'] = $selectedPropertyDetails->rp_pin_no_lref;
                $this->data['rp_pin_suffix_lref'] = $selectedPropertyDetails->rp_pin_suffix_lref;
                $this->data['building_owner'] = (isset($selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceBuild->propertyOwner->standard_name:'';
                $this->data['land_owner'] = (isset($selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name))?$selectedPropertyDetails->machineReffernceLand->propertyOwner->standard_name:'';
                $this->data['rpo_code_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rpo_code))?$selectedPropertyDetails->machineReffernceLand->rpo_code:'';
                $this->data['rp_td_no_lref'] = (isset($selectedPropertyDetails->machineReffernceLand->rp_td_no))?$selectedPropertyDetails->machineReffernceLand->rp_td_no:'';
                $this->data['rp_td_no_bref'] = (isset($selectedPropertyDetails->machineReffernceBuild->rp_td_no))?$selectedPropertyDetails->machineReffernceBuild->rp_td_no:'';
                
                $this->data['rp_app_taxability'] = $selectedPropertyDetails->rp_app_taxability;
                session()->put('machineSelectedBrgy',$selectedPropertyDetails->brgy_code_id);
                $data = (object)$this->data;
                $data->property_admin_details = $selectedPropertyDetails->property_admin_details;
                $data->property_owner_details = $selectedPropertyDetails->property_owner_details;
                $data->machineReffernceLand = ($selectedPropertyDetails->machineReffernceLand != null)?$selectedPropertyDetails->machineReffernceLand:'';
                $data->machineReffernceBuild = ($selectedPropertyDetails->machineReffernceBuild != null)?$selectedPropertyDetails->machineReffernceBuild:'';
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

    public function getMachineryAppraisal(Request $request){
        $classes = $this->arrPropClasses;
        $id = ($request->has('id'))?$request->id:0;
        $machineAppraisals = [];
        if($id != 0){
            $machineAppraisals = RptPropertyMachineAppraisal::where('rp_code',$id)->get();
        }else{
            $sessionData = collect($request->session()->get('machinePropertyAppraisals'));
            if(!$sessionData->isEmpty()){
                $machineAppraisals = $sessionData;
            }
        }
        
        $view = view('rptmachinery.ajax.machineryappraisallisting',compact('machineAppraisals','classes'))->render();
        $view2 = view('rptmachinery.ajax.machineryappraisallistingdesc',compact('machineAppraisals'))->render();
        return response()->json([
            'status' => 'success',
            'view'   => $view,
            'view2'  => $view2
        ]);
        //echo $view;
    }
    public function getAssessmentSummaryListing(Request $request){
        
        $id        = $request->input('id');
        $sessionid = $request->input('id');
        if($id != ''){
            $assessmentsummary = $this->_rptpropertymachinery->getPalntsTreesAppraisalDetails($id);
        }else{
            $sessionData = collect($request->session()->get('assessmentsummary'));
            $assessmentsummary = [];
        }
        $view = view('rptproperty.ajax.plantstreesadjustmentfactor',compact('assessmentsummary'))->render();
        echo $view;
    }

    public function storeMachineAppraisal(Request $request){
        //dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'rpma_description'=>'required',
                'rpma_appr_no_units'=>'required',
                'rpma_acquisition_cost'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'], 
                /*'rpma_freight_cost'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'], 
                'rpma_insurance_cost'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],
                'rpma_installation_cost'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'], 
                'rpma_other_cost'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],*/
                'rpma_base_market_value'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],
                /*'rpma_depreciation_rate'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],*/
                /*'rpma_depreciation'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],*/
                'rpma_market_value'=>['required','numeric','regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'],
            ],
            [
                'rpma_description.required'=>'Required Field',
                'rpma_appr_no_units.required'=>'Required Field',
                'rpma_acquisition_cost.required'=>'Required Field', 
                'rpma_acquisition_cost.regex'=>'Invalid Value', 
                'rpma_acquisition_cost.numeric'=>'Invalid Value', 
                'rpma_freight_cost.required'=>'Required Field', 
                'rpma_freight_cost.regex'=>'Invalid Value', 
                'rpma_freight_cost.numeric'=>'Invalid Value',
                'rpma_insurance_cost.required'=>'Required Field',
                'rpma_insurance_cost.regex'=>'Invalid Value', 
                'rpma_insurance_cost.numeric'=>'Invalid Value',
                'rpma_installation_cost.required'=>'Required Field', 
                'rpma_installation_cost.regex'=>'Invalid Value', 
                'rpma_installation_cost.numeric'=>'Invalid Value',
                'rpma_other_cost.required'=>'Required Field',
                'rpma_other_cost.regex'=>'Invalid Value', 
                'rpma_other_cost.numeric'=>'Invalid Value',
                'rpma_base_market_value.required'=>'Required Field',
                'rpma_base_market_value.regex'=>'Invalid Value', 
                'rpma_base_market_value.numeric'=>'Invalid Value',
                'rpma_depreciation_rate.required'=>'Required Field',
                'rpma_depreciation_rate.regex'=>'Invalid Value', 
                'rpma_depreciation_rate.numeric'=>'Invalid Value',
                'rpma_depreciation.required'=>'Required Field',
                'rpma_depreciation.regex'=>'Invalid Value', 
                'rpma_depreciation.numeric'=>'Invalid Value',
                'rpma_market_value.required'=>'Required Field',
                'rpma_market_value.regex'=>'Invalid Value', 
                'rpma_market_value.numeric'=>'Invalid Value',
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
                'rpma_description' => $request->rpma_description,
                'rpma_brand_model' => $request->rpma_brand_model,
                'rpma_capacity_hp' => $request->rpma_capacity_hp,
                'rpma_date_acquired' => $request->rpma_date_acquired,
                'rpma_condition' => $request->rpma_condition,
                'rpma_estimated_life' => $request->rpma_estimated_life,
                'rpma_remaining_life' => $request->rpma_remaining_life,
                'rpma_date_installed' => $request->rpma_date_installed,
                'rpma_date_operated' => $request->rpma_date_operated,
                'rpma_remarks' =>$request->rpma_remarks,
                'rpma_appr_no_units' =>$request->rpma_appr_no_units,
                'rpma_acquisition_cost' => $request->rpma_acquisition_cost,
                'rpma_freight_cost' => ($request->rpma_freight_cost > 0)?$request->rpma_freight_cost:0,
                'rpma_insurance_cost' => ($request->rpma_insurance_cost > 0)?$request->rpma_insurance_cost:0,
                'rpma_installation_cost' =>($request->rpma_installation_cost > 0)?$request->rpma_installation_cost:0,
                'rpma_other_cost' =>($request->rpma_other_cost > 0)?$request->rpma_other_cost:0,
                'rpma_base_market_value' => $request->rpma_base_market_value,
                'rpma_depreciation_rate' => ($request->rpma_depreciation_rate > 0)?$request->rpma_depreciation_rate:0,
                'rpma_depreciation' => ($request->rpma_depreciation > 0)?$request->rpma_depreciation:0,
                'rpma_market_value' => $request->rpma_market_value,
                'pc_class_code' => $request->pc_class_code,
                'pau_actual_use_code' => $request->pau_actual_use_code,
                'al_assessment_level' => $request->al_assessment_level,
                'rpm_assessed_value' => $request->rpm_assessed_value,
                'rpma_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
           //dd($dataToSave);
           if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            $rptPropertyDetails = RptProperty::find($request->property_id);
                /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }*/
                if($this->_rptpropertymachinery->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswMachine');
            $dataToSave['rpma_modified_by'] = \Auth::user()->creatorId();
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertymachinery->updateMachineAppraisalDetail($request->id,$dataToSave);
            $this->_rptpropertymachinery->generateTaxDeclarationAform($request->property_id);
            $this->_rptpropertymachinery->updateAccountReceiaveableDetails($request->property_id);
            $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($request->property_id);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id == null){
            $rptPropertyDetails = RptProperty::find($request->property_id);
                /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }*/
                if($this->_rptpropertymachinery->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswMachine');
            $savedProperty = RptProperty::with(['propertyKindDetails','propertyApproval','revisionYearDetails'])->where('id',$request->property_id)->first();
            $dataToSave['rp_code'] = $savedProperty->id;
            $dataToSave['rp_property_code'] = $savedProperty->rp_property_code;
            $dataToSave['pk_code'] = $savedProperty->propertyKindDetails->pk_code;
            $dataToSave['rvy_revision_year'] = $savedProperty->revisionYearDetails->rvy_revision_year;
            $dataToSave['rvy_revision_code'] = $savedProperty->rvy_revision_code;
            unset($dataToSave['id']);
            //dd($dataToSave);
            $lastInsertedId = $this->_rptpropertymachinery->addMachineAppraisalDetail($dataToSave);
            $this->_rptpropertymachinery->generateTaxDeclarationAform($savedProperty->id);
            $this->_rptpropertymachinery->updateAccountReceiaveableDetails($savedProperty->id);
            $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($savedProperty->id);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                $dataToSave['id']   = null;
                $dataToSave['rp_code']   = null;
                $dataToSave['dataSource']   = 'session';
                $savedLandApprSessionData = $request->session()->get('machinePropertyAppraisals');
                $savedLandApprSessionData[] = (object)$dataToSave;
                if($request->has('session_id') && $request->session_id != ''){
                    $getPlantsTreesAdjustmentFactor = $request->session()->get('machinePropertyAppraisals.'.$request->session_id);
                    $request->session()->put('machinePropertyAppraisals.'.$request->session_id, (object)$dataToSave);
                }else{
                    $request->session()->put('machinePropertyAppraisals', $savedLandApprSessionData);
                }
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           if($request->ajax()){
            return response()->json($response);
           }else{
            return $response;
           }
           


    }

    public function deleteMachineAppraisal(Request $request){
        // if(!\Auth::user()->can('delete tax declaration')){
        //         return response()->json(['error' => __('Permission denied.')]);
        //     }
            $id = $request->input('id');
            if($request->has('sessionId') && $request->sessionId != ''){
                //dd($request->session()->get('landAppraisals'));
               $request->session()->forget('machinePropertyAppraisals.'.$request->sessionId);
               return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
            }else{
                $rptPlantTreeAppraisal = RptPropertyMachineAppraisal::find($id);
                if($this->_rptpropertymachinery->checkToVerifyPsw($rptPlantTreeAppraisal->rp_code)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswMachine');
            if($rptPlantTreeAppraisal != null){
                try {
                    $rptPlantTreeAppraisal->delete();
                    $this->_rptpropertymachinery->generateTaxDeclarationAform($rptPlantTreeAppraisal->rp_code);
                    $this->_rptpropertymachinery->updateAccountReceiaveableDetails($rptPlantTreeAppraisal->rp_code);
                    $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($rptPlantTreeAppraisal->rp_code);
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            }
    }

    public function showMachineAppraisalForm(Request $request){
        //$arrPropertyTypes = $this->rpt_building_types;
        $landAppraisal = (object)[];
        //$arrActualUsesCodes =  $this->rpt_building_actualuse;
        $sessionId = '';
        $propertyCode = [];
        //$addiItems    = $this->arrAddItems;
        $floorCount   = collect($request->session()->get('floorValuesBuilding'))->count()+1;
        $landAppraisal->rpbfv_floor_no = $floorCount;
        $landAppraisal->rpbfv_total_floor = $floorCount;
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptPropertyMachineAppraisal::where('id',$request->id)->first();
            $propertyCode = RptProperty::find($landAppraisal->rp_code);
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisal = $request->session()->get('machinePropertyAppraisals.'.$request->sessionId);
            $sessionId = $request->sessionId;
        }
        if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == null){
            $propertyCode = RptProperty::with('floorValues')->where('id',$request->property_id)->first();
            
        }
        //dd($landAppraisal);
        $view = view('rptmachinery.ajax.machineappraisal.addmachineappraisal',compact('landAppraisal','sessionId','propertyCode'))->render();

        echo $view;
    
    }

    public function formValidation(Request $request){
        //dd($request->all());

        $rules = [
                'rvy_revision_year_id'=>'required',
                'brgy_code_id'=>'required',
                'rp_suffix'=>'max:5', 
                'loc_local_code_name'=>'required',
                'dist_code'=>'required',
                'rp_section_no'=>'required|max:2', 
                'rp_pin_no'=>'required|numeric|digits_between:1,10', 
                'rp_pin_suffix'=>'required|max:4|', 
                'profile_id' => 'required',
                'property_owner_address' => 'required',
                /*'rp_code_bref' => 'required',
                'building_owner' => 'required',*/
                //'rp_code_lref'   => 'required',
                //'rpo_code_lref' => 'required',
            ]; 
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
            if($request->has('rp_assessed_value')){
                $request->request->add([
                    'rp_assessed_value' => (double)str_replace( ',', '', $request->rp_assessed_value),
                ]);
                 $rules['rp_assessed_value'] = 'gt:0';
            }
            /* Rules For Previous Owner Detail Submission */
            if($request->has('uc_code') && $request->uc_code == config('constants.update_codes_land.DUP')){
            
             $rules['rp_section_no'] = 'required|max:2';
             $rules['rp_pin_no'] = 'required|numeric|digits_between:1,10';
             $rules['rp_pin_suffix'] = 'required|max:4';
        }
        /*$propWithSaneSection = RptProperty::where('rp_section_no',$request->rp_section_no)->get();
            if($propWithSaneSection->count() > 1){
                $rules['rp_section_no'] = 'required|max:5';
                $rules['rp_pin_no'] = 'required|numeric|digits_between:1,10';
               $rules['rp_pin_suffix'] = 'required|max:4';
            }*/
        $validator = \Validator::make(
            $request->all(), $rules,
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
                'rp_pin_no.numeric' => 'Numeric only',
                'rp_pin_no.digits_between' => 'Invalid Value',
                'rp_total_land_area'=>'Required Field',
                'profile_id.required' => 'Required Field',
                'property_owner_address.required' => 'Required Field',
                'rp_location_number_n_street.required' => 'Required Field',
                'rp_td_no_lref.required' => 'Required Field',

                'rp_code_bref.required' => 'Required Field',
                'building_owner.required' => 'Required Field',
                'rpo_code_lref.required'  => 'Required Field',
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
                'rp_code_lref.required' => 'Required Field'
            ]
        );
        $validator->after(function ($validator)use($request) {

            $data = $validator->getData();
            //dd($data);
            if(isset($data['id']) && $data['id'] != 0){
                $machineryApp = RptProperty::where('id',$data['id'])->whereHas('machineAppraisals',function($query){
                    $query->where('al_assessment_level','!=','');
                })->count();
            }else{
                $sessionData = collect(session()->get('machinePropertyAppraisals'));
                $machineryApp  = $sessionData->where('al_assessment_level','!=','')->count();
            }
            if(isset($machineryApp) && $machineryApp == 0){
                    $validator->errors()->add('asse_summary_pc_class_code', 'Assessment Level should not be empty');
                }
            if($data['uc_code'] == config('constants.update_codes_land.DUP') || $data['uc_code'] == config('constants.update_codes_land.TR')){
                $oldPropertyData = RptProperty::find($data['old_property_id']);
                if($oldPropertyData != null){
                    //dd($oldPropertyData);
                    if($oldPropertyData->rpo_code == $data['rpo_code']){
                        $validator->errors()->add('profile_id', 'Should be different from previous property!');
                    }
                }
            }
            /* Check RP Pin SUffix Existance */
            $pinSuffixExis = DB::table('rpt_properties')
                                 ->where('rp_code_lref',$data['rp_code_lref'])
                                 ->where('rp_pin_suffix',$data['rp_pin_suffix'])
                                 ->where('pk_id',config('constants.rptKinds.M'));
            if(isset($data['rp_property_code']) && $data['rp_property_code'] != ''){
                $pinSuffixExis->where('rp_property_code','!=',$data['rp_property_code']);
            }                       
            $count = $pinSuffixExis->count();
            if($count > 0){
                $validator->errors()->add('rp_pin_suffix', 'Already Exists');
            }
            /* Check RP Pin SUffix Existance */
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

    public function Approve(Request $request){
        $annotation = "";
         $arrUpdateCodes = $this->arrUpdateCodes;
         $directCancelUcCOdes = $this->arrUpdateCodesDirectCancel;
         $ucCode      = $request->updatecode;
         $approvedata = [];
         $arrRevisionYears = array();
         $arrUpdateCodes = $this->arrUpdateCodes;
         $history = [];
         $id = ($request->has('id'))?$request->id:'';
         $propertyDetails = [];
         foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->rpo_first_name.' '.$val->rpo_custom_last_name;
        } 
        
        $profile = $this->arrprofile;
         if($request->has('id') && $request->id != '0'){
            $approvelFormData = $this->_rptpropertymachinery->getApprovalFormDetails($request->id);
            //dd($approvelFormData);
            $propertyDetails  = RptProperty::find($approvelFormData->rp_code);
            //dd($propertyDetails);
            if($approvelFormData != null){
                $history          = $this->_propertyHistory->with([
                'activeProp.revisionYearDetails',
                'cancelProp.revisionYearDetails',
                'activeProp.barangay',
                'cancelProp.barangay',
                'cancelProp.propertyOwner',
                'cancelProp.machineAppraisals.actualUses'
            ])->where('rp_property_code',$approvelFormData->rp_property_code)->get();
            }else{
                $history = [];
            }
            $annotationData   = DB::table('rpt_property_annotations')->select(DB::raw('GROUP_CONCAT(rpa_annotation_desc SEPARATOR"; ") as annotation'))->where('rp_code',$approvelFormData->rp_code)->first();
            $annotation = (isset($annotationData->annotation))?'"'.$annotationData->annotation.'"':''; 
            
            
         }else{
             $approvelFormData = $request->session()->get('approvalFormDataMachine');
             $annotationData   = (session()->has('propertyAnnotationForBuilding'))?session()->get('propertyAnnotationForBuilding'):'';
            if(!empty($annotationData)){
                $annoCollection = collect($annotationData)->pluck('rpa_annotation_desc')->toArray();
                $annotation     = '"'.implode("; ",$annoCollection).'"';
                
            }
         }
         //dd($history->toArray() );
         $appraisers  = $this->arremployees;
        /*  foreach ($this->_rptpropertymachinery->getEmployee() as $e_key => $e_value) {
            $appraisers[$e_value->id] = $e_value->fullname;
         } */
         // $allTds     = $this->_rptpropertymachinery->pluck('id','id')->toArray();
         $allTds     = $this->_rptpropertymachinery->getApprovalFormTds('M',$request->id);
         //dd($allTds);
        return view('rptmachinery.approval',compact('arrRevisionYears','approvedata','appraisers','arrUpdateCodes','approvelFormData','ucCode','profile','arrUpdateCodes','allTds','history','id','propertyDetails','directCancelUcCOdes','annotation'));
    }

    public function anootationSpeicalPropertystatus(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $propertyStatus = [];
        foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        }
		foreach ($this->_rptpropertymachinery->getEmployee() as $e_key => $e_value) {
            $appraisers[$e_value->id] = $e_value->fullname;
         }
        //$appraisers  = $this->arremployees;
		
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
        return view('rptmachinery.ajax.annotationpropertystatus',compact('profile','appraisers','propertyStatus','propertyId'));
    }

    public function storeApprove(Request $request){
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
            $this->_rptpropertymachinery->updateData($approvelFormDetails->rp_code,$dataToUpdateInParent);
            unset($dataToSave['rp_app_effective_year']);
            unset($dataToSave['rp_app_effective_quarter']);
            unset($dataToSave['rp_app_posting_date']);
            unset($dataToSave['rp_app_memoranda']);
            unset($dataToSave['rp_app_extension_section']);
            unset($dataToSave['pk_is_active']);
            unset($dataToSave['uc_code']);
            unset($dataToSave['rp_app_assessor_lot_no']);
            unset($dataToSave['rp_app_taxability']);
            $this->_rptpropertymachinery->updateApprovalForm($request->id,$dataToSave);
            if($approvelFormDetails->rp_app_appraised_by != 0 && $approvelFormDetails->rp_app_recommend_by != 0 && $approvelFormDetails->rp_app_approved_by != 0 && $dataToSave['rp_app_cancel_by_td_id'] != ""){
                $this->updatePropertyHistory($dataToSave['rp_app_cancel_by_td_id'],RptProperty::with(['updatecode'])->find($approvelFormDetails->rp_code));
            }
            if($request->pk_is_active == 0 && $request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1){
                $request->request->add(
                    [
                        'oldpropertyid'  => $approvelFormDetails->rp_code,
                        'updateCode'     => $request->rp_app_cancel_type,
                        'propertykind'   => 3,
                        'remarks'        => $request->rp_app_cancel_remarks,
                        'approvalformid' => $request->id
                    ]
                );
                $this->dpFunctionlaitySbubmit($request);
                $this->_rptpropertymachinery->updateAccountReceiaveableDetails($approvelFormDetails->rp_code, true);
            }
            if($request->pk_is_active == 1 && $approvelFormDetails != null){
                $this->_rptpropertymachinery->disableDirectCancellation($approvelFormDetails->rp_code);


            }
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else{
            //dd($dataToSave);
             $request->session()->put('approvalFormDataMachine', (object)$dataToSave);
             $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }
        return response()->json($response);  
    }

    public function updatePropertyHistory($olderProp = '', $newProp = '',$flag = false){
        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('machineTaxDeclarationForConsolidation');
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
        $this->_rptpropertymachinery->addPropertyHistory($dataToSaveInHistory);
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
        $this->_rptpropertymachinery->updateApprovalForm($olderProp->propertyApproval->id,$dataToUpdateInOldProp);
        /* Update Older Property Data */
        $this->_rptpropertymachinery->updateData($olderProp->id,[
            'pk_is_active' => ($flag)?9:0,
            'rp_property_code_new' => $newProp->rp_property_code,
            'rp_modified_by' => \Auth::user()->id,
        ]);
        /* Update Older Property Data */
         /* Update New Property Data */
        $this->_rptpropertymachinery->updateData($newProp->id,[
            'rp_property_code_new' => $newProp->rp_property_code,
        ]);
        /* Update New Property Data */

        }

        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('machineTaxDeclarationForConsolidation');
            $olderPropIds = $sessionDataOfConsoldation;
            //dd((isset($olderPropties[0]->id))?$olderPropties[0]->id:0);
            $this->_rptpropertymachinery->addDataInAccountReceivable($newProp->id,(isset($olderPropties[0]->id))?$olderPropties[0]->id:0,'CS',$olderPropIds);
        }
    }
    }

    public function updateApprovalFormForPreOwner($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('approvalFormData');
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
            //dd($dataToSave);
            $this->_rptpropertymachinery->addApprovalForm($dataToSave);
            session()->forget('approvalFormData');
            if(isset($holdData['rp_app_cancel_by_td_id']) && $holdData['rp_app_cancel_by_td_id'] != ''){
                $this->updatePropertyHistory($id,RptProperty::find($holdData['rp_app_cancel_by_td_id']),true);
            }
        }
    }

    public function updateApprovalForm($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('approvalFormDataMachine');
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
            $this->_rptpropertymachinery->addApprovalForm($dataToSave);
            session()->forget('approvalFormDataMachine');
        }
    }

    public function setDataForUpdateCodeFunctionality($request){
        
        $inputData = [
            'oldpropertyid' => $request->oldpropertyid,
            'updateCode'    => $request->updateCode,
            'propertykind'  => $request->propertykind
        ];
        $request->session()->forget('machinePropertyAppraisals');
        $selectedProperty = $this->_rptpropertymachinery->with([
            'machineAppraisals',
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
        foreach ($selectedProperty->machineAppraisals as $key=>$value) {
            $dataToSave = [
                'id'                => null,
                'rp_code'           => null,
                'rp_property_code'  => $selectedProperty->rp_property_code,
                'pk_code'           => $value->pk_code,
                'rvy_revision_year' => $value->rvy_revision_year,
                'rvy_revision_code' => $value->rvy_revision_code,
                'rpma_description' => $value->rpma_description,
                'rpma_brand_model' => $value->rpma_brand_model,
                'rpma_capacity_hp' => $value->rpma_capacity_hp,
                'rpma_date_acquired' => $value->rpma_date_acquired,
                'rpma_condition' => $value->rpma_condition,
                'rpma_estimated_life' => $value->rpma_estimated_life,
                'rpma_remaining_life' => $value->rpma_remaining_life,
                'rpma_date_installed' => $value->rpma_date_installed,
                'rpma_date_operated' => $value->rpma_date_operated,
                'rpma_remarks' =>$value->rpma_remarks,
                'rpma_appr_no_units' =>$value->rpma_appr_no_units,
                'rpma_acquisition_cost' => $value->rpma_acquisition_cost,
                'rpma_freight_cost' => $value->rpma_freight_cost,
                'rpma_insurance_cost' => $value->rpma_insurance_cost,
                'rpma_installation_cost' =>$value->rpma_installation_cost,
                'rpma_other_cost' =>$value->rpma_other_cost,
                'rpma_base_market_value' => $value->rpma_base_market_value,
                'rpma_depreciation_rate' => $value->rpma_depreciation_rate,
                'rpma_depreciation' => $value->rpma_depreciation,
                'rpma_market_value' => $value->rpma_market_value,
                'pc_class_code' => $value->pc_class_code,
                'pau_actual_use_code' => $value->pau_actual_use_code,
                'al_assessment_level' => $value->al_assessment_level,
                'rpm_assessed_value' => $value->rpm_assessed_value,
                'rpma_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $savedLandApprSessionData[] = (object)$dataToSave;
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
            //'rp_app_approved_date' => $selectedProperty->propertyApproval->rp_app_approved_date,
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
        session()->forget('approvalFormDataMachine');
        session()->put('approvalFormDataMachine', (object)$dataToSaveInApprovalForm);

            }
        }
        //dd($savedLandApprSessionData);
        session()->forget('machinePropertyAppraisals');
        session()->put('machinePropertyAppraisals', $savedLandApprSessionData);
       // dd(session()->get('machinePropertyAppraisals'));
        return [
            'status'=>'success',
            'data'=>$inputData
        ];
    } 

    public function dpFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptmachinery.ajax.dp.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dpFunctionlaitySbubmit (Request $request){
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
            //'uc_code' => $request->updateCode,
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
        $propertyDetails = RptProperty::find($request->oldpropertyid);
        try {
            if($propertyDetails->pk_is_active == 0){
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'This action can not be performed on cancelled property!'

                ]);
            }
            $this->_rptpropertymachinery->updateApprovalForm($request->approvalformid,$dataToUpdateInApprovalForm);
            $this->_rptpropertymachinery->updateData($request->oldpropertyid,$dataToUpdate);
            $this->_rptpropertymachinery->updateAccountReceiaveableDetails($request->oldpropertyid, true);
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
        /* Check Last Payment Year */
             if(isset($propertyDetails->pk_is_active) && $propertyDetails->pk_is_active == 0){
                $response['status'] = false; 
                $response['msg'] = 'Oops, This is cancelled property!' ;
             }
            if($updateCode != 'CS' && $propertyDetails->rp_app_effective_year < date("Y")+1){
            $lastPaidTaxYear = $this->_rptpropertymachinery->checkLastPaidTax($oldPropId);
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
            $sessionData = session()->get('machineTaxDeclarationForConsolidation');
            if($sessionData == null || empty($sessionData) || count($sessionData) < 2){
                $response['status'] = false; 
                $response['msg'] = 'At least two tax declarations needed for consolidation!' ;
            }
            $tdErrors = [];
            $lastPaymentYear = [];
            foreach ($sessionData as $key => $value) {
                $propDetails = RptProperty::find($value);
                 if($propDetails->rp_app_effective_year < date("Y")+1){
                    $lastPaidTaxYear = $this->_rptpropertymachinery->checkLastPaidTax($value);
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
            $subdividedTds = $this->_rptpropertymachinery->where('created_against',$oldPropId)->get();
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

    public function trFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptmachinery.ajax.tr.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function csFunctionlaity(Request $request){
        $request->session()->forget('machineTaxDeclarationForConsolidation');
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['floorValues','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        $savedtaxDecSessionData = $request->session()->get('machineTaxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->selectedproperty, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->selectedproperty;
        }
        
        $request->session()->put('machineTaxDeclarationForConsolidation', $savedtaxDecSessionData);
        //$allTds     = $this->_rptpropertymachinery->getApprovalFormTds('M');
        $view = view('rptmachinery.ajax.cs.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function loadTaxDeclToConsolidate(){
        $sessionData = session()->get('machineTaxDeclarationForConsolidation');
        //dd($sessionData);
        $taxDeclarations = [];
        if($sessionData != null && !empty($sessionData)){
            $taxDeclarations = RptProperty::whereIn('id',array_unique($sessionData))
            ->where('pk_is_active',1)
            ->where('is_deleted',0)
            ->get();
            //dd($taxDeclarations);  rp_td_no
        }
        $view = view('rptmachinery.ajax.cs.listing',compact('taxDeclarations'))->render();
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
                $savedtaxDecSessionData   = session()->get('machineTaxDeclarationForConsolidation');
                if(in_array($data['id'], $savedtaxDecSessionData)){
                    $validator->errors()->add('selectedPropertyId', 'This T.D. No. already applied.');
                }
                $sessionPropObj           = RptProperty::with('floorValues')->whereIn('id',$savedtaxDecSessionData);
                $classtoCheck             = (isset($oldPropertyData->machineAppraisals[0]->pc_class_code))?$oldPropertyData->machineAppraisals[0]->pc_class_code:'';
                $allmachineAppr           = DB::table('rpt_property_machine_appraisals')->whereIn('rp_code',$savedtaxDecSessionData)->pluck('pc_class_code')->toArray();
                $sessionPropDataLandRef   = $sessionPropObj->pluck('rp_code_lref')->toArray();
                if(!empty($sessionPropDataLandRef) && !in_array((isset($oldPropertyData->rp_code_lref))?$oldPropertyData->rp_code_lref:'',$sessionPropDataLandRef)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should have same land reference!");
                }if(!empty($allmachineAppr) && !in_array($classtoCheck,$allmachineAppr)){
                    $validator->errors()->add('id', "Td's who needs to consolidate should have same Class!");
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
        $savedtaxDecSessionData = $request->session()->get('machineTaxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->id, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->id;
        }
        
        $request->session()->put('machineTaxDeclarationForConsolidation', $savedtaxDecSessionData);
        /* Save Data in session for future use */
        return response()->json([
            'status' => 'success',
            'data'   => []
        ]);

    }

    public function csDeleteTaxDeclaration(Request $request){
        $sessionData = session()->get('machineTaxDeclarationForConsolidation');
        if($sessionData != null && !empty($sessionData)){
            $sessionFlipData = array_flip($sessionData);
            $sessionKey      = $sessionFlipData[$request->selectedTaxDeclarationid];
            session()->forget('machineTaxDeclarationForConsolidation.'.$sessionKey);
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
       $sessionData = session()->get('machineTaxDeclarationForConsolidation');
       $landAppraisalForNewTaxDeclaration = [];
       $taxDeclarationDetails = RptProperty::with(['machineAppraisals'])->whereIn('id',$sessionData)->get();
       $allLandAppraisals     = [];
       $onlyClassSubCActua    = [];
       foreach ($taxDeclarationDetails as $appraisal) {
           $allLandAppraisals[] = $appraisal->machineAppraisals->toArray();
       }
       
       $allLandAppraisalsCol = collect($allLandAppraisals)->collapse();
       
       $request->session()->forget('machinePropertyAppraisals');
       $i = 1;
       foreach ($allLandAppraisalsCol as $key => $value) {
           $rptProprtyDetails = RptProperty::find($value['rp_code']);
           $tempLandApp = $value;
           
           $tempLandApp['rp_property_code'] = (isset($allLandAppraisalsCol[0]['rp_property_code']))?$allLandAppraisalsCol[0]['rp_property_code']:'';
         unset($tempLandApp['class']);
         unset($tempLandApp['pc_class_description']);
         unset($tempLandApp['subdivision_used_units']);
         unset($tempLandApp['created_at']);
         unset($tempLandApp['updated_at']);
         $tempLandApp['rpma_registered_by'] = \Auth::user()->id;
         $tempLandApp['id']                   = null;
         $tempLandApp['rp_code']              = null;
         $savedLandApprSessionData = $request->session()->get('machinePropertyAppraisals');
         $savedLandApprSessionData[] = (object)$tempLandApp;
         $request->session()->put('machinePropertyAppraisals',$savedLandApprSessionData);
        $i++;
       }
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
        $selectedProperty = $this->_rptpropertymachinery->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
         //dd($selectedProperty->landAppraisals[0]->appr);
        $view = view('rptmachinery.ajax.sd.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function sdLoadMachineAppraisals(Request $request){
        $assignedFloors   = [];
        $oldProperty      = $request->oldProperty;
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery
                                  ->where('id',$request->oldProperty)
                                  ->first();
        $newSubDivProp    = $this->_rptpropertymachinery->where('created_against',$oldProperty)->get();
        foreach ($newSubDivProp as $key => $value) {
            if($value->created_against_appraisal != ''){
            $assignedFloorsData = json_decode($value->created_against_appraisal);
            $assignedFloors[]   = $assignedFloorsData;
        }
        }
        $assignedFloors = collect($assignedFloors)->collapse()->toArray();
        $view = view('rptmachinery.ajax.sd.ajax.prevpropfloorvalues',compact('selectedProperty','updateCode','assignedFloors'))->render();
        echo $view;
    }

     public function sdgetListing(Request $request){
        $oldProperty           = ($request->has('oldProperty'))?$request->oldProperty:'';
        $this->_rptpropertymachinery->setTable('rpt_properties');
        //$this->_propertyappraisal->setTable('rpt_property_appraisals');
        $propOwners = [];
        foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            $propOwners[$val->id]=$val->standard_name;
        }
        $selectedProperty = $this->_rptpropertymachinery->where('created_against',$oldProperty);
        $propIds = $selectedProperty->pluck('id')->toArray();
        /*$landAppraisals = DB::table('rpt_property_appraisals')
            ->join('rpt_property_classes AS class', 'rpt_property_appraisals.pc_class_code', '=', 'class.id')
            ->select('rpt_property_appraisals.*','class.pc_class_code')
            ->whereIn('rp_code',array_values($propIds))->get();*/
           // dd($landAppraisals);
        $selectedProperty =  $selectedProperty->get();
        if($oldProperty != ''){
            if($selectedProperty != null){
                $taxDeclarations = view('rptmachinery.ajax.sd.ajax.subdividedtaxdeclaration',compact('selectedProperty','propOwners'))->render();
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
            $this->_rptpropertymachinery->updateData($tmpTaxDecla,$dataToSave,'SD');
            $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }

        }
        if($actionFor != '' && $id != '' && $landArea != ''){
                $parentMachineAppraisal = $request->parentid;
                $totalparentApprasalUnits    = DB::table('rpt_property_machine_appraisals')->where('id',$parentMachineAppraisal)->first();
                $usedAppraisalUnits          = DB::table('rpt_properties')->select(DB::raw('SUM(rpt_property_machine_appraisals.rpma_appr_no_units) as totalUsedUnits'))
                                                   ->join('rpt_property_machine_appraisals',function($j)use($parentMachineAppraisal,$id){
                                                    $j->on('rpt_property_machine_appraisals.rp_code','=','rpt_properties.id')->where('rpt_property_machine_appraisals.created_against',$parentMachineAppraisal)->where('rpt_property_machine_appraisals.id','!=',$id);
                                                   })
                                                   ->where('rpt_properties.created_against',(isset($totalparentApprasalUnits->rp_code))?$totalparentApprasalUnits->rp_code:'')->first();
                $usedAppraisalUnits = (isset($usedAppraisalUnits->totalUsedUnits) && $usedAppraisalUnits->totalUsedUnits != null)?$usedAppraisalUnits->totalUsedUnits:0;
                if(isset($totalparentApprasalUnits->rpma_appr_no_units) && $landArea+$usedAppraisalUnits > $totalparentApprasalUnits->rpma_appr_no_units){
                                $response = [
                        'status' => 'error',
                        'msg'    => 'No of units should not be greater than available no of Units!'
                    ];
                                return response()->json($response);
                }
                /*if(isset($totalparentApprasalUnits->rpma_appr_no_units) && $usedAppraisalUnits->totalUsedUnits != $totalparentApprasalUnits->rpma_appr_no_units){
                    $response = [
                        'status' => 'error',
                        'msg'    => 'No of units should be same than available no of Units!'
                    ];
                                return response()->json($response);
                    
                }*/
                $landAppraisalDetails = DB::table('rpt_property_machine_appraisals')->where('id',$id)->first();
                $calCulatedDta = $this->calculateMarketValueAdjustedMarketVale($landArea,$landAppraisalDetails,$request); 
                $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }
        return response()->json($response);
    }

    public function calculateMarketValueAdjustedMarketVale($units = '',$landAppraisalDetails,$request){
        if(!empty($landAppraisalDetails) && $units != ''){
        $previousAppraisalDetails = RptPropertyMachineAppraisal::find($landAppraisalDetails->created_against);
        $savedProperty = RptProperty::find($landAppraisalDetails->rp_code);
        $availableNumberOfUnits = $previousAppraisalDetails->rpma_appr_no_units;
        $acquisitionCost        = $previousAppraisalDetails->rpma_acquisition_cost;
        $newCostDiv             = ($acquisitionCost/$availableNumberOfUnits)*$units;
        $dataToUpdate = [
            'rpma_appr_no_units'    => $units,
            'rpma_acquisition_cost' => $newCostDiv,
            'rpma_base_market_value' => $newCostDiv,
            'rpma_market_value'   => $newCostDiv,
            'al_assessment_level' => 0,
            'rpm_assessed_value'  => 0
        ];
        $this->_rptpropertymachinery->updateMachineAppraisalDetail($landAppraisalDetails->id,$dataToUpdate);
        $request->request->add(
                    [
                    'id' => $savedProperty->id,
                    'propertyClass'=>(isset($landAppraisalDetails->pc_class_code))?$landAppraisalDetails->pc_class_code:'',
                    'propertyRevisionYear'=>$savedProperty->rvy_revision_year_id,
                    'barangay' => $savedProperty->brgy_code_id
                ]
                );
        //dd($request->all());
        $this->loadAssessementSummary($request);
    }
    }

    public function sdDeleteTaxDeclaration(Request $request){
            $id = $request->input('selectedTaxDeclarationid');
            //dd($id);
            $tempTaxDec = $this->_rptpropertymachinery->where('id',$id);
            if($tempTaxDec != null){
                DB::beginTransaction();
                try {
                    $tempTaxDec->delete();
                    DB::table('rpt_property_machine_appraisals')->where('rp_code',$id)->delete();
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
        $selectedProperty = $this->_rptpropertymachinery->with([
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
        $view = view('rptmachinery.ajax.sd.step2',compact('selectedProperty','updateCode','selectedLandAppraisal'))->render();
        echo $view;
    }

    public function loadNewTdMachineAppra(Request $request){
        $selectedProperty = $this->_rptpropertymachinery->where('id',$request->id)->get()->first();
        $view = view('rptmachinery.ajax.sd.ajax.subdividedappraisals',compact('selectedProperty'))->render();
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
                $lastSuffix = (int)str_replace('M','',$lastSuffix->rp_pin_suffix)+1;
            }
            if($oldProperty != '' && !empty($selectedLandAppraisal) != '' && $updatecode != ''){
            $selectedProperty = $this->_rptpropertymachinery->with([
            'machineAppraisals'=>function($query) use($selectedLandAppraisal){
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
                    $dataTosave['rp_pin_suffix'] = ($alreadyCreatedTds == 0)?$selectedProperty->rp_pin_suffix:'M'.$lastSuffix;
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
                    $dataTosave['rp_code_bref'] = $selectedProperty->rp_code_bref;
                    $dataTosave['rp_section_no_bref'] = $selectedProperty->rp_section_no_bref;
                    $dataTosave['rp_pin_no_bref'] = $selectedProperty->rp_pin_no_bref;
                    $dataTosave['rp_pin_suffix_bref'] = $selectedProperty->rp_pin_suffix_bref;
                    $dataTosave['rp_td_no_lref'] = $selectedProperty->rp_td_no_lref;
                    $dataTosave['rp_code_lref'] = $selectedProperty->rp_code_lref;
                    $dataTosave['rp_section_no_lref'] = $selectedProperty->rp_section_no_lref;
                    $dataTosave['rp_pin_no_lref'] = $selectedProperty->rp_pin_no_lref;
                    $dataTosave['rp_pin_suffix_lref'] = $selectedProperty->rp_pin_suffix_lref;
                    $dataTosave['rp_suffix_lref'] = $selectedProperty->rp_suffix_lref;
                    $dataTosave['rp_oct_tct_cloa_no_lref'] = $selectedProperty->rp_oct_tct_cloa_no_lref;
                    $dataTosave['rpo_code_lref'] = $selectedProperty->rpo_code_lref;
                    $dataTosave['rp_cadastral_lot_no_lref'] = (isset($selectedProperty->buildingReffernceLand->rp_cadastral_lot_no))?$selectedProperty->buildingReffernceLand->rp_cadastral_lot_no:'';
                    $dataTosave['rp_total_land_area'] = ($selectedProperty->buildingReffernceLand != null)?$selectedProperty->buildingReffernceLand->landAppraisals->sum('rpa_total_land_area'):''; 
                $savedId = $this->_rptpropertymachinery->addData($dataTosave,$updatecode);
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
                $this->_rptpropertymachinery->addApprovalForm($dataToSaveInApprovalForm);
                $this->_rptpropertymachinery->generateTaxDeclarationAndPropertyCode($savedId);
                $this->_rptpropertymachinery->generateTaxDeclarationAform($savedId);
                $savedProperty = $this->_rptpropertymachinery->find($savedId);
                $selectedLandAppraisal = $selectedProperty->machineAppraisals;
                $i = 1;
                foreach($selectedProperty->machineAppraisals as $build){
                    $tempLandApp = $build->toArray();
                    //dd($tempLandApp);
                    unset($tempLandApp['id']);
                    unset($tempLandApp['class']);
                    unset($tempLandApp['pc_class_description']);
                    unset($tempLandApp['subdivision_used_units']);
                    $tempLandApp['rp_property_code'] = $savedProperty->rp_property_code;
                    $tempLandApp['created_against'] = $build->id;
                    $remainingUnits  = $build->rpma_appr_no_units-$build->subdivision_used_units;
                    $acquisitionCost = ($build->rpma_acquisition_cost/$build->rpma_appr_no_units)*$remainingUnits;
                    $tempLandApp['rpma_appr_no_units']   = $remainingUnits;
                    $tempLandApp['rpma_acquisition_cost'] = $acquisitionCost;
                    $tempLandApp['rpma_freight_cost'] = 0;
                    $tempLandApp['rpma_insurance_cost'] = 0;
                    $tempLandApp['rpma_installation_cost'] = 0;
                    $tempLandApp['rpma_other_cost'] = 0;
                    $tempLandApp['rpma_depreciation_rate'] = 0;
                    $tempLandApp['rpma_depreciation'] = 0;
                    $tempLandApp['rpma_base_market_value'] = $acquisitionCost;
                    $tempLandApp['rpma_market_value'] = $acquisitionCost;

                    $tempLandApp['rp_code'] = $savedId;
                    $tempLandApp['rpma_registered_by'] = \Auth::user()->id;
                    $tempLandApp['created_at'] = date("Y-m-d H:i:s");
                    $tempLandApp['updated_at'] = date("Y-m-d H:i:s");
                    $lastFloorId = $this->_rptpropertymachinery->addMachineAppraisalDetail($tempLandApp);
                    
                    $i++;
                }
                $allAppraisals = RptPropertyMachineAppraisal::where('rp_code',$savedProperty->id)->get();
                $request->request->add(
                    [
                    'id' => $savedProperty->id,
                    'propertyClass'=>(isset($allAppraisals[0]->pc_class_code))?$allAppraisals[0]->pc_class_code:'',
                    'propertyRevisionYear'=>$savedProperty->rvy_revision_year_id,
                    'barangay' => $savedProperty->brgy_code_id
                ]
                );
                $this->loadAssessementSummary($request);
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
        $oldPropertyDetails = $this->_rptpropertymachinery->with([
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
        //dd($request->input('newCreatedTaxDeclarationForSd'));
        $newCreatedTaxDeclarationListForSubMission = [];
        $allLandAppraisalsForOldProperty = $this->_rptpropertymachinery->with([
            'landAppraisals.class',
        ])->where('id',$oldPropertyId)->get()->first();
        $allAppraisals = $allLandAppraisalsForOldProperty->machineAppraisals;
        $newCreatedProperties = ($request->has('newCreatedTaxDeclarationForSd') && !empty($request->newCreatedTaxDeclarationForSd))?$request->newCreatedTaxDeclarationForSd:[];
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
                $emptyTaxDeclarationFor[] = $appraisal->rpma_description;
            }
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
            $landAppraisal = DB::table('rpt_property_machine_appraisals')->where('rp_code',$prop->id);
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
        if($appraisal->rpma_appr_no_units != $appraisal->subdivision_used_units){
            $landAreaNotEqual = true;
            $landAreaNotEqualfor[] = '';
        }
        
        }
        
        if($emptyTaxDeclaration){
            $response['msg'] = 'No New Tax declaration created against #'.implode(', ',array_unique($emptyTaxDeclarationFor)). ' Property, Please create and try again';
            return response()->json($response);
        }
        if($landAreaNotEqual){
            $response['msg'] = "New Tax declarations total no of units not equal to old tax declaration's units, Please check all ned TD and try again";
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
                
                $this->_rptpropertymachinery->updateData($prop->id,$dataToUpdateInNewProperty);
                $dataToAddInHistory = [
                    'pk_code' => $oldPropertyDetails->propertyKindDetails->pk_code,
                    'rp_property_code' => $oldPropertyDetails->rp_property_code,
                    'rp_code_active' => $prop->id,
                    'rp_code_cancelled' => $oldPropertyDetails->id,
                    'ph_registered_by' => \Auth::user()->id,
                    'ph_registered_date' => date('Y-m-d H:i:s'),
                ];
                //dd($dataToAddInHistory);
                $this->_rptpropertymachinery->addPropertyHistory($dataToAddInHistory);
            }
            $dataToUpdateInOldProperty = [
                'pk_is_active' => 0,
                'rp_modified_by' => \Auth::User()->creatorId(),
                'updated_at'     => date('Y-m-d H:i:s')
            ];
            $this->_rptpropertymachinery->updateData($oldPropertyDetails->id,$dataToUpdateInOldProperty);
            $dataToUpdateInOldPropertyApproval = [
                'rp_app_cancel_by' => \Auth::User()->creatorId(),
                'rp_app_cancel_type' => $cancelationType,
                'rp_app_cancel_date'     => date('Y-m-d H:i:s'),
                'rp_app_cancel_by_td_id' => implode(',',$cancelByTdNoField)
            ];
            $this->_rptpropertymachinery->updateApprovalForm($oldPropertyDetails->propertyApproval->id,$dataToUpdateInOldPropertyApproval);
            $previousChain = [];
            foreach ($newCreatedTaxDeclarationListForSubMissionpData as $key => $prop) {
                $this->_rptpropertymachinery->updatePinDeclarationNumber($prop->id);
                $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($prop->id);
                if($key == 0){
                    $previoChainQU = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$oldPropertyId)->first();
                    $previousChain = json_decode($previoChainQU->rp_code_chain);
                    $this->_rptpropertymachinery->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id);
                }else{
                    $this->_rptpropertymachinery->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id,'SD',[],$previousChain);
                }
            }
            $response['msg'] = 'Subdivision Completed, New tax declaration created '.implode(', ',array_unique($finalSubmissionFor)). ' against #'.$oldPropertyDetails->rp_tax_declaration_no;
            $response['status'] = 'success';
            return response()->json($response);
        }
        
    }
        /* Temp Tax Declaration Subdivision Ends Here */
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
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptmachinery.ajax.ssd.index',compact('selectedProperty','updateCode'))->render();
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

    public function rcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptmachinery.ajax.rc.index',compact('selectedProperty','updateCode'))->render();
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

    public function pcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptmachinery.ajax.pc.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dupFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptpropertymachinery->with(['machineAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptmachinery.ajax.dup.index',compact('selectedProperty','updateCode'))->render();
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

    public function getAllProfile(){
        return $this->_rptpropertymachinery->getprofiles();
    }
    public function getTaxDeclaresionNOBuildingDetails(Request $request){
        $id= $request->input('id');
        $brgy_code_id= $request->input('brgy_code_id');
        $rvy_revision_year_id= $request->input('rvy_revision_year_id');
       $getgroups = $this->_rptpropertymachinery->getTaxDeclaresionNOBuildingDetails($id,$brgy_code_id,$rvy_revision_year_id);
       $htmloption ="<option value=''>Select Building TD. No</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    public function getTaxDeclaresionNODetailsLandAll(Request $request){
        
       $getgroups = $this->_rptpropertymachinery->getTaxdeclarationland();
       $htmloption ="<option value=''>Select Land TD. No</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    public function getTaxDeclaresionNOLandDetails(Request $request){
        $id= $request->input('id');
        $brgy_code_id= $request->input('brgy_code_id');
        $rvy_revision_year_id= $request->input('rvy_revision_year_id');
       $getgroups = $this->_rptpropertymachinery->getTaxDeclaresionNODetails($id,$brgy_code_id,$rvy_revision_year_id);
       $htmloption ="<option value=''>Select Land TD. No</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    
    public function getTaxDeclaresionNODetailsBuildingAll(Request $request){
        
       $getgroups = $this->_rptpropertymachinery->getTaxdeclarationbuilding();
       $htmloption ="<option value=''>Select Building TD. No</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }

    public function getAdmistrativeDetails(Request $request){
       $clientid= $request->input('clientid');
       $getgroups = $this->_rptpropertymachinery->getClientDetails($clientid);
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
         if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
      }
      $getgroups = $this->_rptpropertymachinery->getClientAll();
     
      foreach ($getgroups as $key => $value) {
         if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
      }
      echo $htmloption;
    }
    public function store(Request $request){
		
        $arrLocationdocs = array();
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC'); 
        $propertyKind = $this->_rptpropertymachinery->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        if($request->getMethod() == "GET" && $updateCode == config('constants.update_codes_land.DC')){
                session()->forget('machinePropertyAppraisals');
                session()->forget('approvalFormDataMachine');
                session()->forget('propertyAnnotationForBuilding');
                session()->forget('propertySwornStatementBuilding');
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
        $landAraisalDetails = [];
        $activeBarangay = [];
        $buildTaxDeclarations         = $this->buildTaxDeclarationss;
        $landTaxDeclarations         = $this->landTaxDeclarationss;
        $buildRef = $this->buildDetails;
        $landRef  =  $this->landDetails;
        foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            if($val->suffix){
                $this->arrprofile[$val->id]=$val->rpo_first_name.' '. $val->rpo_middle_name.'  '.$val->rpo_custom_last_name.', '.$val->suffix;
            }
            else{
                $this->arrprofile[$val->id]=$val->rpo_first_name.' '. $val->rpo_middle_name.'  '.$val->rpo_custom_last_name;
            }
            
        }
        
        $profile = $this->arrprofile;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin',
                'machineReffernceLand',
                'machineReffernceBuild'
            ])->where('id',$request->input('id'))->first();
           // dd($data);
            $arrLocationdocs = $this->_rptpropertymachinery->getPropertydocbyid($data->rp_property_code);
            $data->land_owner = (isset($data->machineReffernceLand->propertyOwner->standard_name))?$data->machineReffernceLand->propertyOwner->standard_name:'';
            $data->building_owner = (isset($data->machineReffernceBuild->propertyOwner->standard_name))?$data->machineReffernceBuild->propertyOwner->standard_name:'';
            $data->rp_td_no_bref = (isset($data->machineReffernceBuild->rp_td_no))?$data->machineReffernceBuild->rp_td_no:'';
            $data->rp_td_no_lref = (isset($data->machineReffernceLand->rp_td_no))?$data->machineReffernceLand->rp_td_no:'';
            $buildRef = DB::table('rpt_properties')->select('rp_pin_declaration_no')->where('id',$data->rp_code_bref)->first();
            $landRef = DB::table('rpt_properties')->select('rp_pin_declaration_no')->where('id',$data->rp_code_lref)->first();
            /*$rfs=$data->rp_code_bref;
            foreach ($this->_rptpropertymachinery->gettaxDetailsId($rfs) as $val) {
                $this->buildTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
            }
            foreach ($this->_rptpropertymachinery->getBuildTaxDeclaresionNODetails($data->rpo_code,$data->brgy_code_id,$data->rvy_revision_year_id) as $val) {
                $this->buildTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
            }
            $buildTaxDeclarations= $this->buildTaxDeclarationss;

            $rfsLand=$data->rp_code_lref;
            foreach ($this->_rptpropertymachinery->gettaxDetailsId($rfsLand) as $val) {
                $this->landTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
            }
            foreach ($this->_rptpropertymachinery->getTaxDeclaresionNODetails('',$data->brgy_code_id,$data->rvy_revision_year_id) as $val) {
                $this->landTaxDeclarationss[$val->id]=$val->rp_tax_declaration_no;
            }
            $landTaxDeclarations= $this->landTaxDeclarationss;*/
            $data->property_owner_address = $data->propertyOwner->address();
            $data->rp_administrator_code_address = ($data->propertyAdmin != null)?$data->propertyAdmin->address():'';
            $updateCodeDetails = $this->_rptpropertymachinery->getUpdateCodeById($data->uc_code);
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revisionYearDetails->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            $taxDeclNuumber = $data->rp_td_no;
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
				$data->loc_local_name = $activeBarangay->loc_local_name;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
                //$data->rp_location_number_n_street = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
            }
           
            }
        if($request->isMethod('post')){
            if($request->has('rp_assessed_value')){
                    $request->request->add([
                        'rp_assessed_value' => (double)str_replace( ',', '', $request->rp_assessed_value),
                    ]);
                }
            $sesionData  = session()->get('approvalFormData');
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
                unset($this->data['building_owner']);
            if($request->input('id')>0){
                $dataToSave = $this->data;
                $savedProperty = $this->_rptpropertymachinery->getSinglePropertyDetails($request->input('id'));
                $approvalFormData = DB::table('rpt_property_approvals')->where('rp_code',$request->input('id'))->select('rp_app_cancel_is_direct')->first();
                /*if($savedProperty->pk_is_active == 0 && $approvalFormData->rp_app_cancel_is_direct == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }*/
                if($this->_rptpropertymachinery->checkToVerifyPsw($request->input('id'))){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswMachine');
                DB::beginTransaction();
                try {
                    $this->_rptpropertymachinery->updateData($request->input('id'),$dataToSave);
                    $lastinsertid = $request->input('id');
                    $this->_rptpropertymachinery->updatePinDeclarationNumber($request->input('id'));
                    $this->_rptpropertymachinery->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($lastinsertid);
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                    $status = 'success';
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $success_msg = $e->getMessage();
                    $status = 'error';
                }
                
            }else{
                $landAppSesionData  = session()->get('machinePropertyAppraisals');
                if(empty($landAppSesionData)){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Machine Appraisal details!']);
                }
                $sesionData  = session()->get('approvalFormDataMachine');
                if(empty($sesionData) || $sesionData->rp_app_memoranda == ''){
                    return response()->json(['status'=>'error','msg'=>'Please Complete The Approval Form details, then try again!']);
                }
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
                $selectedUpDateCode = $request->uc_code;
                $dataToSave = $this->data;
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptpropertymachinery->addData($dataToSave);
                    $lastinsertid = $request->id;
                    $this->_rptpropertymachinery->generateTaxDeclarationAndPropertyCode($lastinsertid);
                    $this->updateApprovalForm($lastinsertid);
                    $this->updateMachineAppraisal($lastinsertid);
                    $this->updatePropertyStatus($lastinsertid);
                    $this->updateSwornStatement($lastinsertid);
                    $this->_rptpropertymachinery->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $newGeneratedPropertyDetails = RptProperty::find($lastinsertid);
                    $oldPropertyData = RptProperty::find($request->old_property_id);
                    if($selectedUpDateCode == config('constants.update_codes_land.TR') && $oldPropertyData != null){
                        $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against Transfer of ownership of #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.DUP') && $oldPropertyData != null){
                        $success_msg = 'Duplicate copy generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.SSD') && $oldPropertyData != null){
                        $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Superseded generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.RC') && $oldPropertyData != null){
                        $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Reclassification completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.PC') && $oldPropertyData != null){
                        $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Physical changes completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.CS') && $oldPropertyData != null){
                        $success_msg = 'Consolidation completed with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    }else{
                        $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertid);
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
            //dd($savedProperty);
            $totalMarketValue = 0;
            if($request->ajax()){
                return response()->json(['status'=>$status,'msg'=>$success_msg]);
            }else{
                return redirect()->route('rptmachinery.index')->with('success', __($success_msg));
            }
        } 
        return view('rptmachinery.create',compact('arrRevisionYears','data','arrBarangay','arrSubclasses','arrLocalityCodes','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','arrLandStrippingCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','activeBarangay','propertyKind','updateCode','oldpropertyid','arrLocationdocs','buildRef','landRef'));
    }

    public function loadPreviousOwner(Request $request){
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC'); 
        $propertyKind = $this->_rptpropertymachinery->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        $data = $this->setData($oldpropertyid,$updateCode);
        $landtdnoarray = array(""=>"Select Land TD. No");
        foreach ($this->_rptpropertymachinery->getTaxdeclarationland() as $key => $value) {
            if($value->rp_td_no != ''){
                $landtdnoarray[$value->rp_td_no] = $value->rp_tax_declaration_no;
            }
        }
        $buildingtdnoarray = array(""=>"Select Building TD. No");
        foreach ($this->_rptpropertymachinery->getTaxdeclarationbuilding() as $key => $value) {
            $buildingtdnoarray[$value->rp_td_no] = $value->rp_tax_declaration_no;
        }
        if($request->getMethod() == "GET" && empty($data) && !$request->has('id')){
            if($request->ajax()){
                return response()->json(['status'=>'error','msg'=>'RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!']);
            }else{
                return redirect()->route('rptproperty.index')->with('error', __('RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!'));
            }
            
        }
        if($request->getMethod() == "GET"){
            session()->forget('machinePropertyAppraisals');
            session()->forget('approvalFormDataMachine');
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
        $landAraisalDetails = [];
        $activeBarangay = [];
        $buildRef = $this->buildDetails;
        $landRef  =  $this->landDetails;
        $approvelFormData = [];
        foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            if($val->suffix){
                $this->arrprofile[$val->id]=$val->rpo_first_name.' '. $val->rpo_middle_name.'  '.$val->rpo_custom_last_name.', '.$val->suffix;
            }
            else{
                $this->arrprofile[$val->id]=$val->rpo_first_name.' '. $val->rpo_middle_name.'  '.$val->rpo_custom_last_name;
            }
            
        }
        
        $profile = $this->arrprofile;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin',
                'machineReffernceLand',
                'machineReffernceBuild'
            ])->where('id',$request->input('id'))->first();
            $approvelFormData   = $data->propertyApproval;
            //dd($approvelFormData);
            $data->land_owner = (isset($data->machineReffernceLand->propertyOwner->standard_name))?$data->machineReffernceLand->propertyOwner->standard_name:'';
            $data->building_owner = (isset($data->machineReffernceBuild->propertyOwner->standard_name))?$data->machineReffernceBuild->propertyOwner->standard_name:'';
            $data->rp_td_no_bref = (isset($data->machineReffernceBuild->rp_td_no))?$data->machineReffernceBuild->rp_td_no:'';
            $data->rp_td_no_lref = (isset($data->machineReffernceLand->rp_td_no))?$data->machineReffernceLand->rp_td_no:'';
            $buildRef = DB::table('rpt_properties')->select('rp_pin_declaration_no')->where('id',$data->rp_code_bref)->first();
            $landRef = DB::table('rpt_properties')->select('rp_pin_declaration_no')->where('id',$data->rp_code_lref)->first();
            

            $data->property_owner_address = $data->propertyOwner->address();
            $data->rp_administrator_code_address = ($data->propertyAdmin != null)?$data->propertyAdmin->address():'';
            $updateCodeDetails = $this->_rptpropertymachinery->getUpdateCodeById($data->uc_code);
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revisionYearDetails->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            $taxDeclNuumber = $data->rp_td_no;
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
                $data->loc_local_name = $activeBarangay->loc_local_name;
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
            //dd($approvalFormData);
            session()->put('approvalFormData', (object)$approvalFormData);
            $sesionData  = session()->get('approvalFormData');
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
                unset($this->data['building_owner']);
            if($request->input('id')>0){
                $dataToSave = $this->data;
                $savedProperty = $this->_rptpropertymachinery->getSinglePropertyDetails($request->input('id'));
                if($savedProperty->pk_is_active == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }
                DB::beginTransaction();
                try {
                    $this->_rptpropertymachinery->updateData($request->input('id'),$dataToSave);
                    $lastinsertid = $request->input('id');
                    if($request->has('rp_app_approved_by')){
                        $approvalFormId = DB::table('rpt_property_approvals')->select('id')->where('rp_code',$lastinsertid)->first();
                        $dataToUpdateAppForm = [
                            'rp_app_approved_by' => $request->rp_app_approved_by,
                            'rp_app_approved_date' => $request->rp_app_posting_date
                        ];
                        $this->_rptpropertymachinery->updateApprovalForm($approvalFormId->id,$dataToUpdateAppForm);
                    }
                    $this->_rptpropertymachinery->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $status = 'success';
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                } catch (\Exception $e) {
                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                }
                
            }else{
                $landAppSesionData  = session()->get('machinePropertyAppraisals');
                if(empty($landAppSesionData)){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Machine Appraisal details!']);
                }
                
                $this->data['rp_app_effective_year']= (isset($sesionData->rp_app_effective_year))?$sesionData->rp_app_effective_year:'';
                $this->data['rp_app_effective_quarter']= (isset($sesionData->rp_app_effective_quarter))?$sesionData->rp_app_effective_quarter:'';
                $this->data['rp_app_posting_date']= (isset($sesionData->rp_app_posting_date))?$sesionData->rp_app_posting_date:'';
                $this->data['pk_is_active']= (isset($sesionData->pk_is_active))?$sesionData->pk_is_active:'';
                $this->data['rp_app_taxability']= (isset($sesionData->rp_app_taxability))?$sesionData->rp_app_taxability:'';
                $this->data['rp_registered_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $selectedUpDateCode = $request->uc_code;
                $dataToSave = $this->data;
               // dd($dataToSave);
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptpropertymachinery->addData($dataToSave);
                    $lastinsertid = $request->id;
                    $this->_rptpropertymachinery->generateTaxDeclarationAndPropertyCode($lastinsertid, true);
                    $this->updateApprovalFormForPreOwner($lastinsertid);
                    $this->updateMachineAppraisal($lastinsertid);
                    $this->_rptpropertymachinery->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptpropertymachinery->updateChain($lastinsertid);
                    $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $status = 'success';
                    $newGeneratedPropertyDetails = RptProperty::find($lastinsertid);
                    $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
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
                return redirect()->route('rptmachinery.index')->with('success', __($success_msg));
            }
        } 
        $allTds     = $this->_rptpropertymachinery->getPreviousOwnerTds((isset($request->oldpropertyid))?$request->oldpropertyid:$request->id); 
        $appraisers  = $this->arremployees;
         foreach ($this->_rptpropertymachinery->getEmployee() as $e_key => $e_value) {
            $appraisers[$e_value->id] = $e_value->fullname;
         }
        return view('rptmachinery.ajax.addpreviousowner',compact('arrRevisionYears','data','arrBarangay','profile','arrSubclasses','arrLocalityCodes','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','arrLandStrippingCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','activeBarangay','propertyKind','updateCode','oldpropertyid','landtdnoarray','buildingtdnoarray','buildRef','landRef','allTds','appraisers','approvelFormData'));
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
            $this->_rptpropertymachinery->addPropertySwornData($dataToSave);
            session()->forget('propertySwornStatementBuilding');
        }
    }

    public function swornStatment(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $landPropId = ($request->has('landprpid'))?$request->landprpid:0;
        $landPropDetails = [];
        $propertyStatus = [];
        $propDetails  = [];
        foreach ($this->_rptpropertymachinery->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        } 
        if($landPropId != 0){
            $propDetails = RptProperty::with('landAppraisals')->where('id',$landPropId)->first();
        }
        foreach ($this->_rptpropertymachinery->getctoCashier() as $val) {
            $this->arrOrnumber[$val->id]=$val->or_no;
        } 
        foreach ($this->_rptpropertymachinery->getEmployee() as $val) {
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
        return view('rptmachinery.ajax.swornstatement',compact('profile','orData','employee','appraisers','propertyStatus','propertyId','propDetails'));
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
            $this->_rptpropertymachinery->updatePropertySwornData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == null){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rps_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptpropertymachinery->addPropertySwornData($dataToSave);
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
            $dataToSave['rpss_registered_by'] =  \Auth::user()->id;
            $lastInsrtedId = $this->_rptpropertymachinery->addPropertyStatusData($dataToSave);
            $annoNations   = session()->get('propertyAnnotationForBuilding');
            //dd($annoNations);
            if(!empty($annoNations)){
                foreach ($annoNations as $ano) {
                $dataToSaveInAnootation = (array)$ano;
                $dataToSaveInAnootation['rp_code'] = $rptProperty->id;
                $dataToSaveInAnootation['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
                $dataToSaveInAnootation['created_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['updated_at'] =  date('Y-m-d H:i:s');
                $dataToSaveInAnootation['rpa_registered_by'] =  \Auth::user()->id;
                $this->_rptpropertymachinery->addAnnotationData($dataToSaveInAnootation);
            }
                

            }
            
            session()->forget('propertyStatusForBuilding');
            session()->forget('propertyAnnotationForBuilding');
        }
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
        $this->_rptpropertymachinery->updateData($data->id,$dataToUpdate);
    }

    public function updateMachineAppraisal($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval','revisionYearDetails'])->where('id',$id)->first();
        $sesionData  = session()->get('machinePropertyAppraisals');
        //dd($rptProperty);
        if(!empty($sesionData) && $rptProperty != null){
            foreach ($sesionData as $key => $value) {
            $dataToSave = (array)$value;
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['rp_property_code'] = $rptProperty->rp_property_code;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['rvy_revision_year'] = $rptProperty->revisionYearDetails->rvy_revision_year;
            $dataToSave['rvy_revision_code'] = $rptProperty->rvy_revision_code;
            unset($dataToSave['id']);
            unset($dataToSave['bt_building_type_code_desc']);
            unset($dataToSave['pau_actual_use_code_desc']);
            unset($dataToSave['dataSource']);
            unset($dataToSave['additionalItems']);
            //dd($dataToSave);
            $lastInsrtedId = $this->_rptpropertymachinery->addMachineAppraisalDetail($dataToSave);
        }
        }
        session()->forget('machinePropertyAppraisals');
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
        $arrType   = array("1"=>"Machine Tax Declaration","2"=>"Machine Previous Owner Tax Declaration","3"=>"Machine Appraisals");
        return view('rptmachinery.bulkUpload',compact('arrType'));
    }

    public function downloadmachineTDTemplate(Request $request){
        $status = ($request->has('status'))?$request->status:1;
        $this->setData('',config('constants.update_codes_land.DC'));
        $arrHeading = $this->_rptpropertymachinery->setValueToCommonColumns($this->data,3,$status);
        if(empty($arrHeading)){
            echo "Barangay, District or Locality Is missing";exit;
        }
        
        $arrHeading = $this->_rptpropertymachinery->insertNewArrayItem($arrHeading,'uc_code','pk_is_active');
        $arrHeading = $this->_rptpropertymachinery->insertNewArrayItem($arrHeading,'rpo_code','pc_class_code');
        //dd($arrHeading);
        $arrHeading['pk_is_active'] = $status;
        //dd($arrHeading);
        $clients=DB::table('clients')->select(DB::raw('CONCAT("[",id,"]","=>","[",full_name,"]") as client_name'))->where('is_active',1)->get()->toArray();
       //dd($arrHeading);
        $arrEmployees = [];
        $arrClients = [];
        $arrLandReferences = [];
        $arrBuildReferences = [];
        $previousOwnerRefrences = [];
        $arrClasses = [];
        $preOwnerRef = [];
        foreach ($clients as $val) {
              $arrClients[]=$val->client_name;
        }
        foreach ($this->_rptpropertymachinery->getHrEmplyees() as $val) {
              $arrEmployees[]='['.$val->id.']'.'=>'.'['.$val->fullname.']';
        }
        foreach ($this->_rptpropertymachinery->getPropClasses() as $val) {
                $arrClasses[]='['.$val->id.']=>['.$val->pc_class_description.']';
        }
        foreach ($this->_rptpropertymachinery->getBuildReferencesForMachine(session()->get('machineSelectedBrgy'),$this->activeRevisionYear->id) as $val) {
                $arrBuildReferences[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        }
        foreach ($this->_rptpropertymachinery->getLandReferencesForMachine(session()->get('machineSelectedBrgy'),$this->activeRevisionYear->id) as $val) {
                $arrLandReferences[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        }
        foreach ($this->_rptpropertymachinery->getpreviousOwnerRefrences(session()->get('machineSelectedBrgy'),$this->activeRevisionYear->id,3) as $val) {
                $preOwnerRef[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        }    
            
        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $allRecordCount = [count($arrClients),count($arrEmployees),count($arrBuildReferences),count($arrLandReferences),count($arrClasses)];
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
                    
                }else if($h_key == 'building_reference'){
                    
                        $data[] = (isset($arrBuildReferences[$contForEmployee]))?$arrBuildReferences[$contForEmployee]:'';
                    
                }else if($h_key == 'land_reference'){

                        $data[] = (isset($arrLandReferences[$contForEmployee]))?$arrLandReferences[$contForEmployee]:'';

                }else if($h_key == 'classes'){

                        $data[] = (isset($arrClasses[$contForEmployee]))?$arrClasses[$contForEmployee]:'';
                        
                }else if($h_key == 'previous_owner_reference_tds'){

                        $data[] = (isset($preOwnerRef[$contForEmployee]))?$preOwnerRef[$contForEmployee]:'';
                        
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
                $this->rptpropObj = new RptPropertyMachineryController;
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
                    $drop_column = 'S';
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
                    $validation->setFormula1('=$Y$2:$Y$'.$row_count);
                    $validationForClient = $validation;
                    /* For Client */

                    /* For Administrator */
                    // set dropdown column
                    $drop_column_admin = 'T';
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
                    $validation->setFormula1('=$Y$2:$Y$'.$row_count);
                    $validationForAdmin = $validation;
                    /* For Administrator */

                    /* For Pc Class COde */
                    // set dropdown column
                    $drop_column_class = 'R';
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
                    $validation->setFormula1('=$AC$2:$AC$'.count($this->rptpropObj->arrPropClasses));
                    $validationForClass = $validation;
                    /* For Pc Class COde */

                    /* For Land Reference */
                    // set dropdown column
                    $drop_column_lref = 'M';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_lref."2")->getDataValidation();
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
                    $validation->setFormula1('=$AA$2:$AA$'.count($this->rptpropMoObj->getBuildReferencesForMachine($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'])));
                    $validationForLref = $validation;
                    /* For Land Reference */

                      /* For Land Reference */
                    // set dropdown column
                    $drop_column_bref = 'L';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_bref."2")->getDataValidation();
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
                    $validation->setFormula1('=$AB$2:$AB$'.count($this->rptpropMoObj->getLandReferencesForMachine($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'])));
                    $validationForBref = $validation;
                    /* For Land Reference */

                     

                    /* For AppriasedBy */
                    // set dropdown column
                    $drop_column_ap_by = 'U';
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
                    $validation->setFormula1('=$Z$2:$Z$'.count($this->rptpropObj->arremployees));
                    $validationForAppBy = $validation;
                    /* For AppriasedBy */

                    /* For Recommended */
                    // set dropdown column
                    $drop_column_rec_by = 'V';
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
                    $validation->setFormula1('=$Z$2:$Z$'.count($this->rptpropObj->arremployees));
                    $validationForRecBy = $validation;
                    /* For Recommended */

                    /* For Approved By */
                    // set dropdown column
                    $drop_column_app_by = 'W';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_app_by."2")->getDataValidation();
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
                    $validation->setFormula1('=$Z$2:$Z$'.count($this->rptpropObj->arremployees));
                    $validationForAppBy = $validation;
                    /* For Approved By */

                    /* For Previous Owner Reference */
                    // set dropdown column
                    $drop_column_pre_owner = 'X';
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
                    $validation->setFormula1('=$AD$2:$AD$'.count($this->rptpropMoObj->getpreviousOwnerRefrences($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'],3)));
                    $validationForPreOwner = $validation;
                    /* For Previous Owner Reference */


                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validationForClient);
                        $event->sheet->getCell("{$drop_column_admin}{$i}")->setDataValidation(clone $validationForAdmin);

                        $event->sheet->getCell("{$drop_column_class}{$i}")->setDataValidation(clone $validationForClass);
                        $event->sheet->getCell("{$drop_column_lref}{$i}")->setDataValidation(clone $validationForLref);
                        $event->sheet->getCell("{$drop_column_bref}{$i}")->setDataValidation(clone $validationForBref);

                        $event->sheet->getCell("{$drop_column_pre_owner}{$i}")->setDataValidation(clone $validationForPreOwner);

                        $event->sheet->getCell("{$drop_column_ap_by}{$i}")->setDataValidation(clone $validationForAppBy);
                        $event->sheet->getCell("{$drop_column_rec_by}{$i}")->setDataValidation(clone $validationForRecBy);
                        $event->sheet->getCell("{$drop_column_app_by}{$i}")->setDataValidation(clone $validationForAppBy);
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
        return Excel::download($exportClass, 'machine-tax-declaration.xlsx');
    }

    public function uploadBulkMachineData(Request $request){
        $upload_type =  $request->input('upload_type');
        if($upload_type==1){
            return $this->uploadMachineTaxDeclaration($request);
        }else if($upload_type==2){
            return $this->uploadMachineTaxDeclaration($request);
        }else if($upload_type==3){
            return $this->uploadMachineAppraisal($request);
        }
    }



    public function uploadMachineTaxDeclaration($request){
        $upload_type =  $request->input('upload_type');
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptpropertymachinery->fliterData($excelData[$i],$excelData[0]);
                    $response = $this->_rptpropertymachinery->checkRequiredFields($newDataToSave);
                    //dd($response);
                    $approvalData = $this->_rptpropertymachinery->createDataForApproval($newDataToSave);
                    if($response['status']){
                        $dataToSave = $response['data'];
                        unset($dataToSave['rp_app_appraised_by']);
                        unset($dataToSave['rp_app_recommend_by']);
                        unset($dataToSave['rp_app_approved_by']);
                        unset($dataToSave['rvy_revision_year']);
                        if(isset($dataToSave['previous_owner_reference'])){
                            unset($dataToSave['previous_owner_reference']);
                        }
                       // dd($dataToSave);
                        try {
                           // dd($dataToSave);
                            $lastinsertedId = $this->_rptpropertymachinery->addData($dataToSave);
                            $property = DB::table('rpt_properties')->where('id',$lastinsertedId)->first();
                            if($property->pk_is_active == 1){
                               $this->_rptpropertymachinery->generateTaxDeclarationAndPropertyCode($lastinsertedId);
                             }
                             if($property->pk_is_active == 9){
                               $this->_rptpropertymachinery->generateTaxDeclarationAndPropertyCode($lastinsertedId,true);
                             }
                            
                            $approvalData['rp_code'] = $lastinsertedId;
                            $approvalData['rp_property_code'] = $property->rp_property_code;
                            $this->_rptpropertymachinery->addApprovalForm($approvalData);

                            if($property->pk_is_active == 1){
                                $this->_rptpropertymachinery->addDataInAccountReceivable($lastinsertedId);
                            }if($property->pk_is_active == 9){
                                $this->updatePropertyHistory($lastinsertedId,RptProperty::find($dataToSave['created_against']),true);
                                $this->_rptpropertymachinery->updateChain($lastinsertedId);
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

    public function uploadMachineAppraisal($request){
        $upload_type =  $request->input('upload_type');
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptpropertymachinery->fliterApraisalData($excelData[$i],$excelData[0]);

                    $response = $this->_rptpropertymachinery->checkAppraisalRequiredFields($newDataToSave);
                    
                    if($response['status']){
                        $dataToSave = $response['data'];
                        try {
                            $lastinsertedId = $this->_rptpropertymachinery->addMachineAppraisalDetail($dataToSave);
                           // $lastinsertedId = 67;
                            $property = DB::table('rpt_properties')->where('id',$dataToSave['rp_code'])->first();
                            //dd($property);
                            $freshRequestObj = new Request;
                            $freshRequestObj->merge(['id' => $lastinsertedId,'propertyClass' => $dataToSave['pc_class_code'],'propertyRevisionYear' => $property->rvy_revision_year_id,'barangay' => $property->brgy_code_id]);
                            $landApprasals = RptPropertyMachineAppraisal::where('rp_code',$property->id)->get();
                            $this->calculateAssesementLeval($freshRequestObj,$landApprasals);
                            $this->_rptpropertymachinery->generateTaxDeclarationAform($property->id);
                            $this->_rptpropertymachinery->updateAccountReceiaveableDetails($property->id);
                            $this->_rptpropertymachinery->syncAssedMarketValueToMainTable($property->id);
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

    

    public function downloadMachineAppraisalTemplate(){
        // Define the data to export
        $arrHeading = array('rp_code'=>'','pk_code'=>'M','rpma_description' => '','rpma_brand_model'=>'', 'rpma_capacity_hp'=>'', 'rpma_date_acquired'=>'', 'rpma_condition'=>'', 'rpma_estimated_life' => '', 'rpma_remaining_life' => '', 'rpma_date_installed'=>'', 'rpma_date_operated' => '','rpma_remarks' => '','rpma_appr_no_units' => '','rpma_acquisition_cost' => '', 'rpma_freight_cost' => '','rpma_insurance_cost' => '','rpma_installation_cost' => '','rpma_other_cost' => '','rp_tax_declaration_no' => '');

         
        $arrBusn = $this->bulkUploadMATds;
        $allArrayCount = [count($arrBusn)];
        //dd($arrBusn);
        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        for($i=0; $i<max($allArrayCount); $i++){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                
                if($h_key == 'rp_tax_declaration_no'){
                    $data[] = (isset($arrBusn[$i]->rp_tax_declaration_no))?$arrBusn[$i]->rp_tax_declaration_no:'';
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
            public function __construct($data){
                $this->data = $data;
                $this->controllerObj = new RptPropertyMachineryController;
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
                    $validation->setFormula1('=$S$2:$S$'.count($this->controllerObj->bulkUploadMATds));
                    $validationForTD = $validation;
                    /* For Tax Declaration */

                    /* For rpma_capacity_hp */
                    // set dropdown column
                    $drop_column_rpma_capacity_hp = 'E';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_capacity_hp."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be integer');
                    $validationForCapacity = $validation;
                    /* For rpma_capacity_hp */

                    /* For rpma_capacity_hp */
                    // set dropdown column
                    $drop_column_rpma_date_acquired = 'F';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_date_acquired."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DATE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be in mm/dd/yyyy format');
                    $validationForDateAcquired = $validation;
                    /* For rpma_capacity_hp */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_estimated_life = 'H';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_estimated_life."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Integer Value allowed');
                    $validationForEstimatedLife = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_remaining_life = 'I';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_remaining_life."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Integer Value allowed');
                    $validationForRemainingLife = $validation;
                    /* For ma_estimated_life */

                    /* For rpma_date_installed */
                    // set dropdown column
                    $drop_column_rpma_date_installed = 'J';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_date_installed."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DATE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be in mm/dd/yyyy format');
                    $validationForDateInstalled = $validation;
                    /* For rpma_date_installed */

                    /* For rpma_date_installed */
                    // set dropdown column
                    $drop_column_rpma_date_operated = 'K';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_date_operated."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DATE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value should be in mm/dd/yyyy format');
                    $validationForDateOperated = $validation;
                    /* For rpma_date_installed */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_no_units = 'M';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_no_units."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_WHOLE );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Integer Value allowed');
                    $validationForUnits = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_acquisition_cost = 'N';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_acquisition_cost."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value allowed');
                    $validationForAcquCost = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_freist_cost = 'O';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_freist_cost."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value allowed');
                    $validationForFreistCost = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_insurance_cost = 'P';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_insurance_cost."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value allowed');
                    $validationForInsuCost = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_installation_cost = 'Q';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_installation_cost."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value allowed');
                    $validationForInstallCost = $validation;
                    /* For ma_estimated_life */

                    /* For ma_estimated_life */
                    // set dropdown column
                    $drop_column_rpma_other_cost = 'R';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rpma_other_cost."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value allowed');
                    $validationForOtherCost = $validation;
                    /* For ma_estimated_life */

                    
                   
                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validationForTD);
                        $event->sheet->getCell("{$drop_column_rpma_capacity_hp}{$i}")->setDataValidation(clone $validationForCapacity);

                        $event->sheet->getCell("{$drop_column_rpma_date_acquired}{$i}")->setDataValidation(clone $validationForDateAcquired);
                        $event->sheet->getCell("{$drop_column_rpma_estimated_life}{$i}")->setDataValidation(clone $validationForEstimatedLife);
                        $event->sheet->getCell("{$drop_column_rpma_remaining_life}{$i}")->setDataValidation(clone $validationForRemainingLife);
                        $event->sheet->getCell("{$drop_column_rpma_date_installed}{$i}")->setDataValidation(clone $validationForDateInstalled);
                        $event->sheet->getCell("{$drop_column_rpma_date_operated}{$i}")->setDataValidation(clone $validationForDateOperated);
                        $event->sheet->getCell("{$drop_column_rpma_no_units}{$i}")->setDataValidation(clone $validationForUnits);
                        $event->sheet->getCell("{$drop_column_rpma_acquisition_cost}{$i}")->setDataValidation(clone $validationForAcquCost);
                        $event->sheet->getCell("{$drop_column_rpma_freist_cost}{$i}")->setDataValidation(clone $validationForFreistCost);
                        $event->sheet->getCell("{$drop_column_rpma_insurance_cost}{$i}")->setDataValidation(clone $validationForInsuCost);
                        $event->sheet->getCell("{$drop_column_rpma_installation_cost}{$i}")->setDataValidation(clone $validationForInstallCost);
                        $event->sheet->getCell("{$drop_column_rpma_other_cost}{$i}")->setDataValidation(clone $validationForOtherCost);
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
        return Excel::download($exportClass, 'land_appraisal.xlsx');
    }

}
