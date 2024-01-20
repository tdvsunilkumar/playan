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

class RptPropertyController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrRevisionYears = array(""=>"Select Revision Year");
    public $arrBarangay = array(""=>"Select Barangay");
    public $arrLocCodes = array(""=>"Select Locality");
    public $arrDistNumbers = array(""=>"Select Locality");
    public $arrUpdateCodes = [];
    public $arrUpdateCodesDirectCancel = [];
    public $arrPropKindCodes = array(""=>"Select Property Kind");
    public $arrStripingCodes = array(""=>"Select Stripping Code");
    public $arrprofile = [];
    public $arrSubclasses = array(""=>"Please Select");
    public $arrPropClasses = array(""=>"Please Select");
    public $arrPlantTreesCode = ["" => 'Select Plant/Tree Code'];
    public $arremployees = [];
    public $activeMuncipalityCode = "";
    public $activeRevisionYear = "";
    public $yeararr = [];
    public $propertyKind                   = 'L';
    public $slugs;
    public $bulkUploadLATds;
    public $bulkUploadLAUnitValues;
    
    public function __construct(){
        $this->_bploApplication = new BploApplication();
        $this->_commonmodel = new CommonModelmaster();  
        $this->_rptproperty = new RptProperty();
        $this->_barangay    = new Barangay;
        $this->_muncipality = new ProfileMunicipality;
        $this->_revisionyear = new RevisionYear;
        $this->_propertyHistory = new RptPropertyHistory;
        $this->_propertyappraisal = new RptPropertyAppraisal;
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
            "rp_pin_suffix" => "",
            "rp_oct_tct_cloa_no" => "",
            /*"property_owner_address" => "",*/
            "loc_group_brgy_no" => "",
            "rp_location_number_n_street" => "",
            "uc_code" => "",
            "update_code" => "",
            "rp_cadastral_lot_no" => "",
            "property_owner_address" => "",
            "rpo_code" => "",
            /*"adminstrator" => "",*/
            "rp_administrator_code_address" => "",
            "rp_administrator_code" => "",
            "rp_bound_north" => "",
            "rp_bound_east" => "",
            "rp_bound_south" => "",
            "rp_bound_west" => "",
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
		
        foreach ($this->_rptproperty->getRevisionYears() as $val) {
            $this->arrRevisionYears[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
		foreach ($this->_rptproperty->getLocalityCodes() as $val) {
            $this->arrLocCodes[$val->id]=$val->loc_local_code.'-'.$val->loc_local_name;
        }
		foreach ($this->_rptproperty->getDistrictCodes() as $val) {
            $this->arrDistNumbers[$val->id]=$val->dist_code;
        }
		foreach ($this->_rptproperty->getUpdateCodes('L') as $val) {
            $this->arrUpdateCodes[$val->id]=$val->uc_code.'-'.$val->uc_description;
        }
		foreach ($this->_rptproperty->getUpdateCodesForCancellation('L') as $val) {
            $this->arrUpdateCodesDirectCancel[$val->id]=$val->uc_code.'-'.$val->uc_description;
        }
		foreach ($this->_rptproperty->getPropClasses() as $val) {
            $this->arrPropClasses[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
        }
		foreach ($this->_rptproperty->getPropKindCodes() as $val) {
            $this->arrPropKindCodes[$val->id]=$val->pk_description;
        }
		foreach ($this->_rptproperty->getStrippingCodes() as $val) {
            $this->arrStripingCodes[$val->id]=$val->rls_description;
        }
		foreach ($this->_rptproperty->getPlantTreeCodes() as $val) {
            $this->arrPlantTreesCode[$val->id]=$val->pt_ptrees_code.'-'.$val->pt_ptrees_description;
        }
		foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
		
		foreach ($this->_rptproperty->getHrEmplyees() as $val) {
              $this->arremployees[$val->id]=$val->fullname;
        }

        $dataForLAbulkUpload = $this->_rptproperty->getDataForLandAppraisalBulkUpload();
        $this->bulkUploadLATds = $dataForLAbulkUpload['tds'];
        $this->bulkUploadLAUnitValues = $dataForLAbulkUpload['landUnitValues'];
        $this->activeMuncipalityCode = $this->_muncipality->getActiveMuncipalityCode();
        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
        $this->slugs = '/real-property/property-data/property';
    }
    
    public function index(Request $request)
    {   
		
        $read1 = $this->is_permitted($this->slugs, 'read', 1);
        if (!($read1 > 0)){
            return abort(401);
        }
        //dd(session()->get('plantTreeAppraisals'));
        $request->session()->forget('approvalFormData');
        $request->session()->forget('plantTreeAppraisals');
        $request->session()->forget('landAppraisals');
        $request->session()->forget('taxDeclarationForConsolidation');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->session()->forget('propertyAnnotationForBuilding');
        $revisionYears = $this->arrRevisionYears;
        $updateCodes = $this->makeSelectListOfUpdateCodes();
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $arrBarangay = $this->arrBarangay;
        
        return view('rptproperty.index',compact('revisionYears','updateCodes','activeRevisionYear','arrBarangay'));
       
    }

    public function makeSelectListOfUpdateCodes($value = ''){
        $html = '<select class="form-control selected_update_code" required="required" name="selected_update_code"><option value="">Select Update Code</option>';
        $restrictCodes = [config('constants.update_codes_land.DC'),config('constants.update_codes_land.GR')];
        foreach ($this->_rptproperty->getUpdateCodes('L') as $val) {
            if(!in_array($val->id,$restrictCodes)){
                $html .= '<option value="'.$val->id.'" data-code="'.$val->uc_code.'">'.$val->uc_code.'-'.$val->uc_description.'</option>';
            }
        }
         $html .= '</select>';
         return $html;
    }
    
    public function getList(Request $request){
        $request->session()->forget('approvalFormData');
        $request->session()->forget('plantTreeAppraisals');
        $request->session()->forget('landAppraisals');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->session()->forget('propertyAnnotationForBuilding');
        $request->request->add(['property_kind' => $this->propertyKind]);
        $data=$this->_rptproperty->getList($request);
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $arr[$i]['no']=$count;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $taxpayer_name = wordwrap($row->taxpayer_name, 30, "<br />\n");
            $arr[$i]['taxpayer_name']="<div class='showLess'>".$taxpayer_name."</div>";
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['effectivity']=$row->rp_app_effective_year;
            $arr[$i]['created_date']=date("d M, Y",strtotime($row->created_at));
             $reg_emp_name = wordwrap($row->reg_emp_name, 20, "<br />\n");
            $arr[$i]['reg_emp_name']="<div class='showLess'>".$reg_emp_name."</div>";
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $rp_cadastral_lot_no = wordwrap($row->rp_cadastral_lot_no, 20, "<br />\n");
            $arr[$i]['rp_cadastral_lot_no']="<div class='showLess'>".$rp_cadastral_lot_no."</div>";
            $arr[$i]['market_value']=Helper::money_format($row->market_value);
            $arr[$i]['assessed_value']=Helper::money_format($row->assessed_value);
            $uc_code = $row->updatecode->uc_code.'-'.$row->updatecode->uc_description;
            // $uc_code = wordwrap($row->updatecode->uc_code.'-'.$row->updatecode->uc_description, 2, "<br />\n");
            $arr[$i]['uc_code']="<span class='showLess2'>".$uc_code."</span>";
            $arr[$i]['pk_is_active'] = ($row->pk_is_active == 1) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':(($row->rp_app_cancel_is_direct == 1)?'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Direct Cancelled</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
                 if($row->pk_is_active == 1){
                 $arr[$i]['action']  = '<div class="action-btn bg-warning ms-2">
                        <a href="javascript:;" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="edit" data-propertyid="'.$row->id.'" data-size="xxll"  title="Edit" data-title="Real Property - Land | Plant & Trees">

                            <i class="ti-pencil text-white"></i>
                        </a>   
                    </div>
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Print FAAS">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="updatecode" data-tax="'.$row->rp_tax_declaration_no.'"  data-propertyid="'.$row->id.'" data-count="'.$count.'"  data-size="xxll"  title="Update Code"  data-title="Update Code">

                            <i class="ti-clipboard text-white"></i>
                        </a>
                    </div>';
                    }
                    
                    else{
                         $arr[$i]['action']  = '<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="edit" data-propertyid="'.$row->id.'" data-size="xxll"  title="Edit" data-title="Real Property - Land | Plant & Trees">

                            <i class="ti-pencil text-white"></i>
                        </a>   
                    </div>
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Print FAAS">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>';
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
         $select .= '<option value="">Select Action</option><option class="fa" value="'.$id.'" data-actionname="edit" data-propertyid="'.$id.'">&#xf044 &nbsp;&nbsp;Edit</option><option value="'.$id.'" class="fa" data-actionname="print" data-propertyid="'.$id.'">&#xf02f &nbsp;&nbsp;Print</option><option value="'.$id.'" class="fa" data-actionname="printfaas" data-propertyid="'.$id.'">&#xf02f &nbsp;&nbsp;Print FAAS</option><option value="'.$id.'" class="fa" data-actionname="bill" data-propertyid="'.$id.'">&#xf0d6 &nbsp;&nbsp;Billing</option><option value="'.$id.'" data-actionname="updatecode" class="fa" data-propertyid="'.$id.'">&#xf0c9 &nbsp;&nbsp;Update Code</option>';
        $select .= '</select></div>';
        return $select;
    }

    public function updatePropertyHistory($olderProp = '', $newProp = '', $flag = false){
        //dd('called');
        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('taxDeclarationForConsolidation');
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
            'rp_property_code' =>$newProp->rp_property_code,
            'rp_code_active' => $newProp->id,
            'rp_code_cancelled' => $olderProp->id,
            'ph_registered_by' => \Auth::user()->id,
            'ph_registered_date' => date('Y-m-d H:i:s'),
            'ph_modified_by' => \Auth::user()->id,
            'ph_modified_date' =>  date('Y-m-d H:i:s')
        ];
        $this->_rptproperty->addPropertyHistory($dataToSaveInHistory);
        //dd($olderPropties->propertyApproval);
        $prevCalcelByTd = (isset($olderProp->propertyApproval->rp_app_cancel_by_td_id))?$olderProp->propertyApproval->rp_app_cancel_by_td_id:'';
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
        $this->_rptproperty->updateApprovalForm($olderProp->propertyApproval->id,$dataToUpdateInOldProp);
        /* Update Older Property Data */
        $this->_rptproperty->updateData($olderProp->id,[
            'pk_is_active' => ($flag)?9:0,
            'rp_property_code_new' => $newProp->rp_property_code,
            'rp_modified_by' => \Auth::user()->creatorId(),
        ]);
        /* Update Older Property Data */
        /* Update New Property Data */
        $this->_rptproperty->updateData($newProp->id,[
            'rp_property_code_new' => $newProp->rp_property_code,
        ]);
        /* Update New Property Data */

        }

        if($newProp->uc_code == config('constants.update_codes_land.CS')){
            $sessionDataOfConsoldation = session()->get('taxDeclarationForConsolidation');
            $olderPropIds = $sessionDataOfConsoldation;
            $this->_rptproperty->addDataInAccountReceivable($newProp->id,(isset($olderPropties[0]->id))?$olderPropties[0]->id:0,'CS',$olderPropIds);
        }

    }
    }

    public function anootationSpeicalPropertystatus(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $propertyStatus = [];
        foreach ($this->_rptproperty->getprofiles() as $val) {
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
        return view('rptproperty.ajax.annotationpropertystatus',compact('profile','appraisers','propertyStatus','propertyId'));
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
            $this->_rptproperty->updatePropertyStatusData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == ''){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rpss_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptproperty->addPropertyStatusData($dataToSave);
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

        if($request->has('property_id') && $request->property_id != 0){
            $propertyDetails = RptProperty::with('propertyKindDetails')->where('id',$request->property_id)->first();
            $dataToSave['rp_code'] = $request->property_id;
            $dataToSave['pk_code'] = $propertyDetails->propertyKindDetails->pk_code;
            $dataToSave['rpa_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptproperty->addAnnotationData($dataToSave);
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
    public function swornStatment(Request $request){
        $propertyId = ($request->has('id'))?$request->id:0;
        $landPropId = ($request->has('landprpid'))?$request->landprpid:0;
        $landPropDetails = [];
        $propertyStatus = [];
        $propDetails  = [];
        // $getctoCashier=$this->_rptproperty->getctoCashier();
        // print_r($getctoCashier);exit;
        foreach ($this->_rptproperty->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        }
        foreach ($this->_rptproperty->getctoCashier() as $val) {
            $this->arrOrnumber[$val->id]=$val->or_no;
        }  
        foreach ($this->_rptproperty->getEmployee() as $val) {
            if($val->suffix){
              $this->hremployee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
            }
            else{
                $this->hremployee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }
        if($propertyId != 0){
            $propDetails = RptProperty::with('landAppraisals')->where('id',$propertyId)->first();
            $area        = $propDetails->landAppraisals->sum('rpa_adjusted_market_value');
        }else{
            $sessionData = collect($request->session()->get('landAppraisals'));
            $area        = $sessionData->sum('rpa_adjusted_market_value');

        }

        $appraisers  = $this->arremployees;
        $orData = $this->arrOrnumber;
        $profile = $this->arrprofile;
        $employee = $this->hremployee;
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
        return view('rptproperty.ajax.swornstatement',compact('profile','orData','appraisers','propertyStatus','propertyId','propDetails','area','employee'));
    }
    public function getClientRptProperty(Request $request){
       $getgroups = $this->_rptproperty->getclients();
       $htmloption ="";
       $htmloption .='<option>Please Select Person</option>';
      foreach ($getgroups as $key => $value) {
        if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
        
      }
      echo $htmloption;
    }
    public function getOrNumberDetails(Request $request){
       $id=$request->input('id');
       $getgroups = $this->_rptproperty->getctoCashierDetails($id);
       $htmloption ="";
       $htmloption .='<option>Please Select OR No.</option>';
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->or_no.'</option>';
        }
        echo $htmloption;
    }
    public function getEmpDetails(Request $request){
       $id=$request->input('id');
       $getgroups = $this->_rptproperty->getRefreshEmployee($id);
       $htmloption ="";
       $htmloption .='<option>Please Select Employee</option>';
      foreach ($getgroups as $key => $value) {
        if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->title.' '.$value->firstname.' '.$value->middlename.' '.$value->lastname.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->title.' '.$value->firstname.'  '.$value->middlename.' '.$value->lastname.'</option>';
        }
        }
        echo $htmloption;
    }
    public function getIssuanceDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptproperty->getctoCashierIsseueanceDetails($id);
        echo json_encode($data);
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
            $this->_rptproperty->updatePropertySwornData($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else if($request->has('property_id') && $request->property_id != 0 && $request->has('id') && $request->id == null){
            $dataToSave['rp_code']           = $request->property_id;
            $dataToSave['rps_registered_by'] = \Auth::user()->creatorId();
            $dataToSave['created_at'] = date('Y-m-d H:i:s');
            //dd($dataToSave);
            $this->_rptproperty->addPropertySwornData($dataToSave);
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
        return view('rptproperty.ajax.annotations',compact('propertyAnnotations'));
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
            $this->_rptproperty->addApprovalForm($dataToSave);
            session()->forget('approvalFormData');
            if(isset($holdData['rp_app_cancel_by_td_id']) && $holdData['rp_app_cancel_by_td_id'] != ''){
                $this->updatePropertyHistory($id,RptProperty::find($holdData['rp_app_cancel_by_td_id']),true);
            }
        }
    }

    public function updateApprovalForm($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval'])->where('id',$id)->first();
        $sesionData  = session()->get('approvalFormData');
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
            unset($dataToSave['rp_app_taxability']);
            //dd($dataToSave);
            $this->_rptproperty->addApprovalForm($dataToSave);
            session()->forget('approvalFormData');
        }
    }

    public function updateLandAppraisal($id = ''){
        $savedProperty = $this->_rptproperty->getSinglePropertyDetails($id);
        $sesionData  = session()->get('landAppraisals');
        if(!empty($sesionData)){
            foreach ($sesionData as $landAppRaisal) {
                //dd($landAppRaisal);
            $dataToSave = (array)$landAppRaisal;
            unset($dataToSave['id']);
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            unset($dataToSave['pau_actual_use_desc']);
            unset($dataToSave['land_stripping_id']);
            unset($dataToSave['dataSource']);
            unset($dataToSave['al_minimum_unit_value']);
            unset($dataToSave['al_maximum_unit_value']);
            unset($dataToSave['al_assessment_level_hidden']);
            unset($dataToSave['plantsTreeApraisals']);
            $dataToSave['rp_property_code']  = $savedProperty->rp_property_code;
            $dataToSave['pk_code']           = $savedProperty->pk_code;
            $dataToSave['rvy_revision_year'] = $savedProperty->rvy_revision_year;
            $dataToSave['rvy_revision_code'] = $savedProperty->rvy_revision_code;
            $dataToSave['rp_code'] = $savedProperty->id;
            $landAppraisalId = $this->_rptproperty->addLandAppraisalDetail($dataToSave);
            //if(isset($landAppRaisal->plantsTreeApraisals))
            $this->updatePlantTreeAppraisal($id, $landAppRaisal->plantsTreeApraisals, $landAppraisalId);
        }
        }
        session()->forget('landAppraisals');
    }

    public function updatePlantTreeAppraisal($id = '', $dataFromLandAppra = [], $landAppraisalId = ''){
        $rptProperty = RptProperty::where('id',$id)->first();
        $sesionData  = $dataFromLandAppra;
        if(!empty($sesionData)){
            foreach ($sesionData as $landAppRaisal) {
            $dataToSave = (array)$landAppRaisal;
            unset($dataToSave['id']);
            unset($dataToSave['pt_ptrees_description']);
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            unset($dataToSave['dataSource']);
            $dataToSave['rp_property_code']  = $rptProperty->rp_property_code;
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['rpa_code'] = $landAppraisalId;
            $dataToSave['rpta_registered_by'] = $rptProperty->rp_registered_by;
            $this->_rptproperty->addPlantsTreesFactors($dataToSave);
        }
        }
        session()->forget('plantTreeAppraisals');
        
    }

    public function conditionsForUpdateCodes($oldPropId = '',$updateCode = ''){
        $response = [
            'status' => true,
            'msg'    => '' 
          ];
        $propertyDetails = RptProperty::find($oldPropId);
        //dd($propertyDetails);
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
                $lastPaidTaxYear = $this->_rptproperty->checkLastPaidTax($oldPropId);
             if($lastPaidTaxYear->lastPaymentYear == null || $lastPaidTaxYear->lastPaymentYear < date("Y")){
                $response['status'] = false; 
                $response['msg'] = 'Please clear your previous dues, then try again!' ;
             }
             }
             /* Check Last Payment Year */

        switch ($updateCode) {
            case 'CS':
            $sessionData = session()->get('taxDeclarationForConsolidation');
            if($sessionData == null || empty($sessionData) || count($sessionData) < 2){
                $response['status'] = false; 
                $response['msg'] = 'At least two tax declarations needed for consolidation!' ;
            }
            $tdErrors = [];
            $lastPaymentYear = [];
            foreach ($sessionData as $key => $value) {
                $propDetails = RptProperty::find($value);
                 if($propDetails->rp_app_effective_year < date("Y")+1){
                    $lastPaidTaxYear = $this->_rptproperty->checkLastPaidTax($value);
                    if($lastPaidTaxYear->lastPaymentYear == null || $lastPaidTaxYear->lastPaymentYear < date("Y")){
                        $lastPaymentYear[] = $lastPaidTaxYear;
                    }
                 }
                if($propDetails->uc_code == config('constants.update_codes_land.DUP')){
                    $checkForExistDupCopy = DB::table('rpt_properties')
                                ->where('id',$propDetails->created_against)
                                ->where('pk_is_active',1)
                                ->first();
                }/*else{
                    $checkForExistDupCopy = DB::table('rpt_properties')
                                ->where('created_against',$value)
                                ->where('uc_code',config('constants.update_codes_land.DUP'))
                                ->where('pk_is_active',1)
                                ->first();
                }*/
                //dd($checkForExistDupCopy);
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
            
            default:
             
                break;
        }
        return $response;
    }

    public function trFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();

        $view = view('rptproperty.ajax.tr.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function dpFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptproperty.ajax.dp.index',compact('selectedProperty','updateCode'))->render();
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
            'pk_is_active' => 0,
            'rp_modified_by' => \Auth::User()->id,
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
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'DP');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        } 
       
        $propertyDetails = RptProperty::find($request->oldpropertyid);
        $this->_rptproperty->updateAccountReceiaveableDetails($request->oldpropertyid, true);
        try {
            $this->_rptproperty->updateApprovalForm($request->approvalformid,$dataToUpdateInApprovalForm);
            $this->_rptproperty->updateData($request->oldpropertyid,$dataToUpdate);
            if($request->updateCode == config('constants.update_codes_land.DP')){
                $msg = 'Dispute successfully raised for tax declaration #'.$propertyDetails->rp_tax_declaration_no;
            }if($request->updateCode == config('constants.update_codes_land.RE')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' successfully removed!';
            }if($request->updateCode == config('constants.update_codes_land.RF')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' successfully Raized By Fire!';
            }if($request->updateCode == config('constants.update_codes_land.DE')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' successfully Demolished!';
            }if($request->updateCode == config('constants.update_codes_land.DT')){
                $msg = 'Tax declaration #'.$propertyDetails->rp_tax_declaration_no.' successfully Destructed!';
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

    public function dupFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       //dd($selectedProperty->propertyApproval->rp_app_cancel_remarks);
        $view = view('rptproperty.ajax.dup.index',compact('selectedProperty','updateCode'))->render();
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
                                                ->where('rp_registered_by',\Auth::user()->id)
                                                ->first();
                if($oldPropertyData == null){
                    $validator->errors()->add('selectedPropertyId', 'No Td found!');
                }
                $savedtaxDecSessionData = session()->get('taxDeclarationForConsolidation');
                if(in_array($data['id'], $savedtaxDecSessionData)){
                    $validator->errors()->add('selectedPropertyId', 'This T.D. No. already applied.');
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
        $savedtaxDecSessionData = $request->session()->get('taxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->id, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->id;
        }
        //dd($savedtaxDecSessionData);
        
        $request->session()->put('taxDeclarationForConsolidation', $savedtaxDecSessionData);
        /* Save Data in session for future use */
        return response()->json([
            'status' => 'success',
            'data'   => []
        ]);

    }

     public function csDeleteTaxDeclaration(Request $request){
        $sessionData = session()->get('taxDeclarationForConsolidation');
        if($sessionData != null && !empty($sessionData)){
            $sessionFlipData = array_flip($sessionData);
            $sessionKey      = $sessionFlipData[$request->selectedTaxDeclarationid];
            session()->forget('taxDeclarationForConsolidation.'.$sessionKey);
        }
       return response()->json([
            'status' => 'success',
            'data'   => []
        ]);
    }

    public function loadTaxDeclToConsolidate(){
        $sessionData = session()->get('taxDeclarationForConsolidation');
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
        }
        //dd($taxDeclarations);
        $view = view('rptproperty.ajax.cs.listing',compact('taxDeclarations'))->render();
        echo $view;
    }

    public function pcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptproperty.ajax.pc.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    } 

    public function csFunctionlaity(Request $request){
        $request->session()->forget('taxDeclarationForConsolidation');
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        $savedtaxDecSessionData = $request->session()->get('taxDeclarationForConsolidation');
        if($savedtaxDecSessionData == null || !in_array($request->selectedproperty, $savedtaxDecSessionData)){
            $savedtaxDecSessionData[] = $request->selectedproperty;
        }
        
        $request->session()->put('taxDeclarationForConsolidation', $savedtaxDecSessionData);
        //$allTds     = $this->_rptproperty->getApprovalFormTds("L");
        $view = view('rptproperty.ajax.cs.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    } 

    public function csFunctionlaitySbubmit (Request $request){
       $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'CS');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }
        $sessionData = session()->get('taxDeclarationForConsolidation');
       $landAppraisalForNewTaxDeclaration = [];
       $taxDeclarationDetails = RptProperty::with(['landAppraisals.plantTreeAppraisals'])->whereIn('id',$sessionData)->get();
       $allLandAppraisals     = [];
       $onlyClassSubCActua    = [];
       foreach ($taxDeclarationDetails as $appraisal) {
           $allLandAppraisals[] = $appraisal->landAppraisals->toArray();
       }
       $allLandAppraisalsCol = collect($allLandAppraisals)->collapse();
       /*$groupedData          = $allLandAppraisalsCol->groupBy('lav_unit_value');*/
       $uniqueData          = $allLandAppraisalsCol->unique(function (array $item) {
    return $item['pc_class_code'].$item['ps_subclass_code'].$item['pau_actual_use_code'];
       });
       //dd($uniqueData);
       $request->session()->forget('landAppraisals');
       foreach ($uniqueData as $key => $value) {
           $rptProprtyDetails = RptProperty::find($value['rp_code']);
           $searchedData = $allLandAppraisalsCol->where('pc_class_code',$value['pc_class_code'])
                                                ->where('ps_subclass_code',$value['ps_subclass_code'])
                                                ->where('pau_actual_use_code',$value['pau_actual_use_code']);

           $tempLandApp = $value;
           $tempLandApp['rpa_total_land_area'] = $searchedData->sum('rpa_total_land_area');
           $tempLandApp['al_assessment_level'] = 0.00;
           //$tempLandApp['rpa_base_market_value'] = $tempLandApp['rpa_total_land_area']*$value['lav_unit_value'];
           //dd($tempLandApp);
           /* Find Assessement Level for new */
           $request->request->add([
                'propertyKind' => $rptProprtyDetails->pk_id,
                'propertyClass' => $value['pc_class_code'],
                'propertyActualUseCode' => $value['pau_actual_use_code'],
                'propertyRevisionYear' => $rptProprtyDetails->rvy_revision_year_id,
                'barangay'             => $rptProprtyDetails->brgy_code_id,
                'totalMarketValue'     => $tempLandApp['rpa_total_land_area']*$value['lav_unit_value']
            ]);
            $arrassessementLevel = $this->_rptproperty->getAssessementLevel($request);
            if($arrassessementLevel != false){
            if(!$arrassessementLevel->assessementRelations->isEmpty()){
                $ass = $arrassessementLevel->assessementRelations;
                $tempLandApp['al_assessment_level'] = $ass[0]->assessment_level;
            }
           }
           /* Find Assessement Level for new */
           $tempLandApp['rpa_adjustment_factor_a'] = 0;
           $tempLandApp['rpa_adjustment_factor_b'] = 0;
           $tempLandApp['rpa_adjustment_factor_c'] = 0;
           $tempLandApp['rpa_adjusted_plant_tree_value'] = 0;
           $tempLandApp['rpa_adjusted_total_planttree_market_value'] = 0;
           $tempLandApp['plantsTreeApraisals'] = [];
           if(isset($value['plant_tree_appraisals']) && !empty($value['plant_tree_appraisals'])){
            $plantTreeAdjustMentFactor = $value['plant_tree_appraisals'];
            //dd($value);
            foreach ($plantTreeAdjustMentFactor as $key => $plantTree) {
                $plantTreeAdjustMentFactor[$key]['id'] = null;
                $plantTreeAdjustMentFactor[$key]['rp_code'] = null;
                $plantTreeAdjustMentFactor[$key]['rp_property_code'] = $value['rp_property_code'];
                unset($plantTreeAdjustMentFactor[$key]['created_at']);
                unset($plantTreeAdjustMentFactor[$key]['updated_at']);
                $plantTreeAdjustMentFactor[$key]['rpta_registered_by'] = \Auth::User()->creatorId();
            }
            //$tempLandApp['plantsTreeApraisals'] = (object)$plantTreeAdjustMentFactor;
         }
         unset($tempLandApp['plant_tree_appraisals']);
         unset($tempLandApp['class']);
         unset($tempLandApp['sub_class']);
         unset($tempLandApp['actual_uses']);
         unset($tempLandApp['can_have_rpt_property']);
         unset($tempLandApp['remaining_area']);
         unset($tempLandApp['created_at']);
         unset($tempLandApp['updated_at']);
         $tempLandApp['rpa_registered_by'] = \Auth::user()->creatorId();
         $calculatedData = $this->calculateMarketValueAdjustedMarketVale($tempLandApp['rpa_total_land_area'],(object)$tempLandApp);
         //dd($calculatedData);
           $tempLandApp['rpa_base_market_value'] = $calculatedData['baseMarketValue'];
           $tempLandApp['rpa_adjusted_market_value'] = $calculatedData['baseMarketValue'];;
           $tempLandApp['rpa_assessed_value'] = $calculatedData['adjMarketValue']*$tempLandApp['al_assessment_level']/100;
           $tempLandApp['rpa_adjustment_percent'] = 0;
           $tempLandApp['rpa_adjustment_value'] =   0;
           $tempLandApp['id']                   = null;
           $tempLandApp['rp_code']              = null;
         $savedLandApprSessionData = $request->session()->get('landAppraisals');
         //dd($tempLandApp);
         $savedLandApprSessionData[] = (object)$tempLandApp;
         $request->session()->put('landAppraisals',$savedLandApprSessionData);

       }
       //dd($request->session()->get('landAppraisals'));
       $inputData = [
            'oldpropertyid' => $request->oldpropertyid,
            'updateCode'    => $request->updateCode,
            'propertykind'  => $request->propertykind
        ];
       return response()->json([
        'status' => 'success',
        'data'   => $inputData
       ]);
       
    }
     

    public function rcFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptproperty.ajax.rc.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    }

    public function getTotalArea($propId = ''){
        $propData = DB::table('rpt_property_appraisals')
                        ->select(
                            DB::raw('
                                CASE 
                                WHEN (COUNT(DISTINCT lav_unit_measure)) = 2 THEN SUM((CASE WHEN lav_unit_measure = 1 THEN rpa_total_land_area/10000 ELSE rpa_total_land_area END))
                                ELSE SUM(rpa_total_land_area) END as area
                            '),
                            DB::raw('
                                CASE 
                                WHEN (COUNT(DISTINCT lav_unit_measure)) = 2 THEN 2
                                ELSE lav_unit_measure END as mesure
                            ')
                        )
                        ->where('rp_code',$propId)
                        ->first();
        return $propData;
    }

    public function sdFunctionlaitySbubmit(Request $request){
        $response              = ['status'=>'success','msg'=>'Data Inserted'];
        $oldProperty           = ($request->has('oldProperty'))?$request->oldProperty:'';
        $selectedLandAppraisal = ($request->has('selectedLandAppraisal'))?$request->selectedLandAppraisal:'';
        $updatecode            = ($request->has('updatecode'))?$request->updatecode:'';
        $action                = ($request->has('action'))?$request->action:'';
        /* Add new Temp Tax Declaration Starts Here */
        if($action == 'addNewTempTaxDeclaration'){ 
            
            if($oldProperty != ''  && $updatecode != ''){
            $selectedProperty = $this->_rptproperty->with([
            'landAppraisals',
            'landAppraisals.plantTreeAppraisals',
            'landAppraisals.class',
            'landAppraisals.subClass',
            'landAppraisals.actualUses',
            'plantTreeAppraisals',
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner',
            'propertyKindDetails'
        ])->where('id',$oldProperty)
            ->where('pk_is_active',1)
            ->get()
            ->first();
            $areaAndMeasurement = $this->getTotalArea($oldProperty);
           
        $totalLandAreaForAppraisal = (isset($areaAndMeasurement->area))?$areaAndMeasurement->area:$selectedProperty->landAppraisals->sum('rpa_total_land_area');

        $tacDeclarationsAgainstAppraisal =  DB::table('rpt_properties')
            ->join('rpt_property_appraisals as pa','pa.rp_code','=','rpt_properties.id')
            ->select(DB::raw('SUM(COALESCE(pa.rpa_total_land_area,0)) as rpa_total_land_area'),
        DB::raw('
                                CASE 
                                WHEN (COUNT(DISTINCT pa.lav_unit_measure)) = 2 THEN SUM((CASE WHEN pa.lav_unit_measure = 1 THEN pa.rpa_total_land_area/10000 ELSE pa.rpa_total_land_area END))
                                ELSE SUM(pa.rpa_total_land_area) END as rpa_total_land_area
                            ')
    )
            //->where('created_against_appraisal',$selectedLandAppraisal)
            ->where('created_against',$oldProperty)
            ->where('is_deleted',1)->first();
        $tacDeclarationsAgainstAppraisalArea = ($tacDeclarationsAgainstAppraisal->rpa_total_land_area != null)?$tacDeclarationsAgainstAppraisal->rpa_total_land_area:0;
        $reqminingAraea = $totalLandAreaForAppraisal-$tacDeclarationsAgainstAppraisalArea;
        if($reqminingAraea == 0){
            return response()->json([
                    'status' => 'error',
                    'msg'    => 'All area ocupied, in order to create new TD please divide area accordingly!'

                ]);
        }
        //dd($reqminingAraea);
        /*$result = $this->conditionsForUpdateCodes($request->oldProperty,'SD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }*/
        if($selectedProperty != null){
            $dataTosave = [
                'created_against' => $oldProperty,
                //'created_against_appraisal' => $selectedLandAppraisal,
                'rp_property_code' => $selectedProperty->rp_property_code,
                'rpo_code' => $selectedProperty->rpo_code,
                'pk_id' => $selectedProperty->pk_id,
                'rvy_revision_year_id' => $selectedProperty->rvy_revision_year_id,
                'rvy_revision_code' => $selectedProperty->rvy_revision_code,
                'brgy_code_id' => $selectedProperty->brgy_code_id,
                'loc_local_code' => $selectedProperty->loc_local_code,
                'dist_code' => $selectedProperty->dist_code,
                'rp_section_no' => $selectedProperty->rp_section_no,
                'rp_pin_no' => $selectedProperty->rp_pin_no,
                'rp_pin_suffix' => $selectedProperty->rp_pin_suffix,
                'rp_oct_tct_cloa_no' => $selectedProperty->rp_oct_tct_cloa_no,
                'rp_cadastral_lot_no' => $selectedProperty->rp_cadastral_lot_no,
                'rp_administrator_code' => $selectedProperty->rpo_code,
                'rp_location_number_n_street' => $selectedProperty->rp_location_number_n_street,
                'rp_bound_north' => $selectedProperty->rp_bound_north,
                'rp_bound_south' => $selectedProperty->rp_bound_south,
                'rp_bound_east' => $selectedProperty->rp_bound_east,
                'rp_bound_west' => $selectedProperty->rp_bound_west,
                'uc_code' => $updatecode,
                'rp_app_memoranda' => '',
                'rp_app_extension_section' => $selectedProperty->rp_app_extension_section,
                'rp_app_assessor_lot_no' => $selectedProperty->rp_app_assessor_lot_no,
                'rp_app_taxability' => $selectedProperty->rp_app_taxability,
                'rp_app_effective_year' => date('Y', strtotime('+1 year')),
                'rp_app_effective_quarter' => $selectedProperty->rp_app_effective_quarter,
                'pk_is_active' => $selectedProperty->pk_is_active,
                'rp_registered_by' => \Auth::user()->id,
                'is_deleted' =>1,
                'rp_app_posting_date'=>date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $savedId = $this->_rptproperty->addData($dataTosave,$updatecode);
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
            $this->_rptproperty->addApprovalForm($dataToSaveInApprovalForm);
            $this->_rptproperty->generateTaxDeclarationAndPropertyCode($savedId);
            $this->_rptproperty->generateTaxDeclarationAform($savedId);
            $this->_rptproperty->syncAssedMarketValueToMainTable($savedId);
            $savedProperty = $this->_rptproperty->find($savedId);
            $selectedLandAppraisal = $selectedProperty->landAppraisals;
            if(isset($selectedLandAppraisal[0])){
                $land = $selectedLandAppraisal[0];
                $landAppraisalData = [
                    'rp_code' => $savedId,
                    'rp_property_code' => $savedProperty->rp_property_code,
                    'pk_code' => $land->pk_code,
                    'rvy_revision_year' => $land->rvy_revision_year,
                    'rvy_revision_code' => $land->rvy_revision_code,
                    'pc_class_code' => $land->pc_class_code,
                    'ps_subclass_code' => $land->ps_subclass_code,
                    'pau_actual_use_code' => $land->pau_actual_use_code,
                    'lav_unit_value' => $land->lav_unit_value,
                    'rpa_total_land_area' => $reqminingAraea,
                    'lav_unit_measure' => (isset($areaAndMeasurement->mesure))?$areaAndMeasurement->mesure:$land->lav_unit_measure,
                    'rls_code' => $land->rls_code,
                    'rls_percent' => $land->rls_percent,
                    'lav_strip_unit_value' => $land->lav_strip_unit_value,
                    'rpa_base_market_value' => $land->rpa_base_market_value,
                    'rpa_adjusted_market_value' => $land->rpa_adjusted_market_value,
                    'al_assessment_level' => $land->al_assessment_level,
                    'rpa_assessed_value' => $land->rpa_assessed_value,
                    'rpa_taxable' => $land->rpa_taxable,
                    'rpa_adjustment_factor_a' => 0,//$land->rpa_adjustment_factor_a,
                    'rpa_adjustment_factor_b' => 0,//$land->rpa_adjustment_factor_b,
                    'rpa_adjustment_factor_c' => 0,//$land->rpa_adjustment_factor_c,
                    'rpa_adjustment_percent' => 100,//$land->rpa_adjustment_percent,
                    'rpa_adjustment_value' => $land->rpa_adjustment_value,
                    'rpa_registered_by' => $land->rpa_adjustment_factor_c,
                    'created_at' => date('Y-m-d H:i:s')
           ]; 
                $savedPropAppId = $this->_rptproperty->addLandAppraisalDetail($landAppraisalData,$updatecode);
                $this->_rptproperty->calculateLAPpraisalAndUpdate($savedPropAppId);
                $planttreeAdjustmentFactors = $land->plantTreeAppraisals;
                foreach ($planttreeAdjustmentFactors as $key => $value) {
                    $dataPlantsTrees = [
                    'rp_code' => $savedProperty->id,
                    'rpa_code'=> $savedPropAppId,
                    'rp_property_code'=> $savedProperty->rp_property_code,
                    'rp_planttree_code'=> $value->rp_planttree_code,
                    'rvy_revision_year'=> $value->rvy_revision_year,
                    'rvy_revision_code'=> $value->rvy_revision_code,
                    'pc_class_code'=> $value->pc_class_code,
                    'ps_subclass_code'=> $value->ps_subclass_code,
                    'rpta_total_area_planted'=> $value->rpta_total_area_planted,
                    'rpta_non_fruit_bearing'=> $value->rpta_non_fruit_bearing,
                    'rpta_fruit_bearing_productive'=> $value->rpta_fruit_bearing_productive,
                    'rpta_fruit_bearing_non_productive'=> $value->rpta_fruit_bearing_non_productive,
                    'rpta_date_planted'=> $value->rpta_date_planted,
                    'rpta_market_value'=> $value->rpta_market_value,
                    'rpta_taxable'=> $value->rpta_taxable,
                    'rpta_registered_by'=> $value->rp_planttree_code,
                    'created_at'=> date('Y-m-d H:i:s'),
                ];
                //$this->_rptproperty->addPlantsTreesFactors($dataPlantsTrees,$updatecode);
                }
                
            }
            $this->_rptproperty->setTable('rpt_properties');
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
        
        $response = ['status'=>'error','msg'=>''];
        $oldPropertyId     = ($request->has('oldpropertyid'))?$request->oldpropertyid:''; 
        $oldPropAppraisal  = ($request->has('selectedlandappraisal'))?$request->selectedlandappraisal:'';
        $oldPropertyDetails = $this->_rptproperty->with([
            'landAppraisals',
            'landAppraisals.plantTreeAppraisals',
            'landAppraisals.class',
            'landAppraisals.subClass',
            'landAppraisals.actualUses',
            'plantTreeAppraisals',
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
        if($newtaxsd <= 1){
             return response()->json([
            'status'=>'error',
            'msg'=>'You shoud add two tax declaration record for Sub Division!'
          ]);
        }
        if($oldPropertyDetails == null){
            return response()->json([
            'status'=>'error',
            'msg'=>'Cannot do Subdivision for cancelled Tax Declaration!'
        ]);
        }
        $newCreatedTaxDeclarationListForSubMission = [];
        $allLandAppraisalsForOldProperty = $this->_rptproperty->with([
            'landAppraisals.class',
        ])->where('id',$oldPropertyId)->get()->first();
        $allAppraisals = $allLandAppraisalsForOldProperty->landAppraisals;
        $areaAndMeasurement = $this->getTotalArea($oldPropertyId);
        $oldPropTotalLandArae = (isset($areaAndMeasurement->area))?$areaAndMeasurement->area:$oldPropertyDetails->landAppraisals->sum('rpa_total_land_area');

        $newCreatedProperties = ($request->has('newCreatedTaxDeclarationForSd') && !empty($request->newCreatedTaxDeclarationForSd))?$request->newCreatedTaxDeclarationForSd:[];
        //dd($newCreatedProperties);
        $newTaxDeclarationDetails = DB::table('rpt_properties')->whereIn('id',$newCreatedProperties)->get();
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
        unset($dataToValidate['rp_location_number_n_street']);
        unset($dataToValidate['loc_group_brgy_no']);
        unset($dataToValidate['rp_bound_north']);
        unset($dataToValidate['rp_bound_south']);
        unset($dataToValidate['rp_bound_east']);
        unset($dataToValidate['rp_bound_west']);
        $validationError = false;
        $validationErrorFor = [];
        $missingLandAppraisal = false;
        $missingLandAppraisalFor = [];
        $missingApproval = false;
        $missingApprovalFor = [];
        $landAreaNotEqual = false;
        $landAreaNotEqualfor = [];
        $emptyTaxDeclaration = false;
        $emptyTaxDeclarationFor = [];
        $finalSubmission = false;
        $finalSubmissionFor = [];
        $cancelByTdNoField = [];
        $cancelationType = 0;
        foreach ([$oldPropertyDetails] as $appraisal) {
            $tacDeclarationsAgainstAppraisal = DB::table('rpt_properties')
            ->where('created_against',$appraisal->id)
            ->where('is_deleted',1)
            ->where('rp_registered_by',\Auth::user()->id);

            //dd($tacDeclarationsAgainstAppraisal->count());
            if($tacDeclarationsAgainstAppraisal->count() == 0){
                $emptyTaxDeclaration = true;
                $emptyTaxDeclarationFor[] = $appraisal->rp_tax_declaration_no;
            }
            $landTotalArea = (isset($areaAndMeasurement->area))?$areaAndMeasurement->area:0;
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
            $landAppraisal = DB::table('rpt_property_appraisals')->where('rp_code',$prop->id);
            $landAppraisalList = $landAppraisal->get();
            
            $approvalForm = DB::table('rpt_property_approvals')->where('rp_code',$prop->id)->get()->first();
           // dd($approvalForm);
            if($landAppraisalList->isEmpty()){
                $missingLandAppraisal = true;
                $missingLandAppraisalFor[] = '#'.$prop->rp_tax_declaration_no;
            }
//dd($approvalForm);
            if($approvalForm->rp_app_appraised_by == '' || $approvalForm->rp_app_recommend_by == '' || $approvalForm->rp_app_approved_by == '' || $prop->rp_app_memoranda == ''){
                $missingApproval = true;
                $missingApprovalFor[] = '#'.$prop->rp_tax_declaration_no;
            }
            $singlePropLandArea = $landAppraisal->sum('rpa_total_land_area');
            $newPropertiedTotalLandArea += $singlePropLandArea;
            /* Final Submission of Subdivision */
            if(!$missingApproval && !$missingLandAppraisal && !$validationError && !$landAreaNotEqual && !$emptyTaxDeclaration){
                $finalSubmission = true;
                $finalSubmissionFor[] = '#'.$prop->rp_tax_declaration_no;
                $cancelByTdNoField[]  = $prop->id;
                /* Update New Property */
                $cancelationType = $prop->uc_code;
                /* Update New Property */

            }
            /* Final Submission of Subdivision */
        }
        if($landTotalArea != $newPropertiedTotalLandArea){
            $landAreaNotEqual = true;
            $landAreaNotEqualfor[] = $appraisal->rp_tax_declaration_no;
        }
        }
       // dd($new);
        if($emptyTaxDeclaration){
            $response['msg'] = 'No New Tax declaration created against #'.implode(', ',array_unique($emptyTaxDeclarationFor)). ' Property, Please create and try again';
            return response()->json($response);
        }
        if($landAreaNotEqual){
            $response['msg'] = "New Tax declarations total property area is not equal to old tax declaration's ".implode(', ',array_unique($landAreaNotEqualfor))." property area, Please change property area and try again";
            return response()->json($response);
        }
        if($validationError){
            $response['msg'] = 'Important data is missing from new tax declarations '.implode(', ',array_unique($validationErrorFor)).', Please fill all required fields then try again';
            return response()->json($response);
        }
        if($missingLandAppraisal){
            $response['msg'] = 'Land appraisal is missing for tax declaration '.implode(', ',array_unique($missingLandAppraisalFor));
            return response()->json($response);
        }
        if($missingApproval){
            $response['msg'] = 'Approval is missing for tax declaration '.implode(', ',array_unique($missingApprovalFor));
            return response()->json($response);
        }
     dd($finalSubmission);
        if($finalSubmission){
            $newCreatedTaxDeclarationListForSubMissionpData = DB::table('rpt_properties')->whereIn('id',$newCreatedTaxDeclarationListForSubMissionp)->get();
            
            foreach ($newCreatedTaxDeclarationListForSubMissionpData as $key => $prop) {
                $dataToUpdateInNewProperty = [
                    'is_deleted' => 0,
                    'created_against' => NULL,
                    'created_against_appraisal' => NULL
                ];
                
                $this->_rptproperty->updateData($prop->id,$dataToUpdateInNewProperty);
                $dataToAddInHistory = [
                    'pk_code' => $oldPropertyDetails->propertyKindDetails->pk_code,
                    'rp_property_code' => $oldPropertyDetails->rp_property_code,
                    'rp_code_active' => $prop->id,
                    'rp_code_cancelled' => $oldPropertyDetails->id,
                    'ph_registered_by' => \Auth::user()->creatorId(),
                    'ph_registered_date' => date('Y-m-d H:i:s'),
                ];
                //dd($dataToAddInHistory);
                $this->_rptproperty->addPropertyHistory($dataToAddInHistory);
            }
            $dataToUpdateInOldProperty = [
                'pk_is_active' => 0,
                'rp_modified_by' => \Auth::User()->creatorId(),
                'updated_at'     => date('Y-m-d H:i:s')
            ];
            $this->_rptproperty->updateData($oldPropertyDetails->id,$dataToUpdateInOldProperty);
            $dataToUpdateInOldPropertyApproval = [
                'rp_app_cancel_by' => \Auth::User()->creatorId(),
                'rp_app_cancel_type' => $cancelationType,
                'rp_app_cancel_date'     => date('Y-m-d H:i:s'),
                'rp_app_cancel_by_td_id' => implode(',',$cancelByTdNoField)
            ];
            $this->_rptproperty->updateApprovalForm($oldPropertyDetails->propertyApproval->id,$dataToUpdateInOldPropertyApproval);
            $previousChain = [];
            foreach ($newCreatedTaxDeclarationListForSubMissionpData as $key => $prop) {
                $this->_rptproperty->updatePinDeclarationNumber($prop->id);
                if($key == 0){
                    $previoChainQU = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$oldPropertyId)->first();
                    $previousChain = json_decode($previoChainQU->rp_code_chain);
                    $this->_rptproperty->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id);
                }else{
                    $this->_rptproperty->addDataInAccountReceivable($prop->id,$oldPropertyDetails->id,'SD',[],$previousChain);

                }
                $this->_rptproperty->syncAssedMarketValueToMainTable($prop->id);
            }
            $response['msg'] = 'Subdivision Completed, New tax declaration created '.implode(', ',array_unique($finalSubmissionFor)). ' against #'.$oldPropertyDetails->rp_tax_declaration_no;
            $response['status'] = 'success';
            return response()->json($response);
        }
        
    }
        /* Temp Tax Declaration Subdivision Ends Here */
    }

    public function calculateMarketValueAdjustedMarketVale($landArea = '', $landPPraisal = []){
                $landArae    = $landArea;
                $landAppraisalDetails = $landPPraisal;
                $measureUnit = $landAppraisalDetails->lav_unit_measure;
                $baseMarketValue = $landArae*$landAppraisalDetails->lav_unit_value;
                $factorA = ($landAppraisalDetails->rpa_adjustment_factor_a != '')?$landAppraisalDetails->rpa_adjustment_factor_a:0;
                $factorB = ($landAppraisalDetails->rpa_adjustment_factor_b != '')?$landAppraisalDetails->rpa_adjustment_factor_b:0;
                $factorC = ($landAppraisalDetails->rpa_adjustment_factor_c != '')?$landAppraisalDetails->rpa_adjustment_factor_c:0;
                $totalFacorValue = $factorA+$factorB+$factorC;
                $adjuValue       = $baseMarketValue*$totalFacorValue/100;
                $adjMarketValue  = $baseMarketValue-$adjuValue;

                return [
                    'totalFacorValue' => $totalFacorValue,
                    'adjuValue'       => $adjuValue,
                    'adjMarketValue'  => $adjMarketValue,
                    'baseMarketValue' => $baseMarketValue
                ];
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
            $this->_rptproperty->updateData($tmpTaxDecla,$dataToSave,'SD');
            $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }

        }
        if($actionFor != '' && $id != '' && $landArea != ''){
                
                $landAppraisalDetails = DB::table('rpt_property_appraisals')->where('id',$id)->first();
                $calCulatedDta = $this->calculateMarketValueAdjustedMarketVale($landArea,$landAppraisalDetails);
                
                
                $dataToUpdate = [
                    'rpa_total_land_area' => $landArea,
                    'rpa_base_market_value' => $calCulatedDta['baseMarketValue'],
                    'rpa_adjustment_value'  => $calCulatedDta['adjuValue'],
                    'rpa_adjusted_market_value' => $calCulatedDta['adjMarketValue']
                ];
                $this->_rptproperty->updateLandAppraisalDetail($id,$dataToUpdate,'SD');
                $this->_rptproperty->calculateLAPpraisalAndUpdate($id);
                $response = [
            'status' => 'success',
            'msg'    => 'Inserted'
        ];
            }
        return response()->json($response);
    }

   

    public function sdDeleteTaxDeclaration(Request $request){
        
            $id = $request->input('selectedTaxDeclarationid');
            $this->_rptproperty->setTable('rpt_properties');
            $tempTaxDec = Rptproperty::find($id);
            //dd($tempTaxDec);
            if($tempTaxDec != null){
                try {
                   DB::table('rpt_properties')->where('id',$id)->delete();
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            
    }

    public function sdgetListing(Request $request){
        $selectedLandAppraisal = ($request->has('selectedLandAppraisal'))?$request->selectedLandAppraisal:'';
        $oldProperty           = ($request->has('oldProperty'))?$request->oldProperty:'';
        $this->_rptproperty->setTable('rpt_properties');
        $this->_propertyappraisal->setTable('rpt_property_appraisals');
        $propOwners = [];
        foreach ($this->_rptproperty->getprofiles() as $val) {
            $propOwners[$val->id]=$val->standard_name;
        }
        $selectedProperty = $this->_rptproperty/*->with([
            'landAppraisals'=>function($query) use($selectedLandAppraisal){
                $query->where('id',$selectedLandAppraisal);
            },
            'landAppraisals.plantTreeAppraisals',
            'landAppraisals.class',
            'landAppraisals.subClass',
            'landAppraisals.actualUses',
            'plantTreeAppraisals',
            'propertyApproval',
            'revisionYearDetails',
            'barangay',
            'propertyOwner'
        ])*/->where('created_against',$oldProperty)
            ->where('is_deleted',1);

            //->where('created_against_appraisal',$selectedLandAppraisal);
        $propIds = $selectedProperty->pluck('id')->toArray();
        //dd($propIds);
        $lastId = end($propIds);
        $landAppraisals = DB::table('rpt_property_appraisals')
            ->join('rpt_property_classes AS class', 'rpt_property_appraisals.pc_class_code', '=', 'class.id')
            ->select('rpt_property_appraisals.*','class.pc_class_code')
            ->where('rp_code',$oldProperty)->get();
         //dd($landAppraisals);
        $selectedProperty =  $selectedProperty->get();
            if($selectedProperty != null){
                
                $taxDeclarations = view('rptproperty.ajax.sd.ajax.subdividedtaxdeclaration',compact('selectedProperty','propOwners','lastId'))->render();
                $appraisals      = view('rptproperty.ajax.sd.ajax.subdividedappraisals',compact('landAppraisals'))->render();
                $reponse = [
                'status' => 'success',
                'view1'    => $taxDeclarations,
                'view2'    => $appraisals

            ];
            }else{
                $reponse = [
                'status' => 'error',
                'msg'    => 'Something went wrong with data'

            ];
            }
        
        
        return response()->json($reponse);
    }

    public function sdFunctionlaity(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
         //dd($selectedProperty->landAppraisals);
        $view = view('rptproperty.ajax.sd.index',compact('selectedProperty','updateCode'))->render();
        echo $view;
    } 

    public function sdFunctionlaitySecondStep(Request $request){
        $updateCode       = $request->updatecode;
        $selectedProperty = $this->_rptproperty->with([
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
        /* Add Land Apraisal data to session */
        /*$request->session()->forget('landAppraisals');
        foreach ($selectedProperty->landAppraisals as $key=>$value) {
            $plantsTreeApprasals = $this->_rptproperty->getPalntsTreesAppraisalDetails($value->id);
            $plantTreeAppForLand = [];
            foreach ($plantsTreeApprasals as $plantTree) {
                $plantsTreeApp = [
                'id'                => null,
                'rp_code'           => null,
                'rpa_code'          => null,
                'rp_planttree_code' => $plantTree->rp_planttree_code,
                'rvy_revision_year' => $plantTree->rvy_revision_year,
                'rvy_revision_code' => $plantTree->rvy_revision_code,
                'pc_class_code' => $plantTree->pc_class_code,
                'ps_subclass_code' => $plantTree->ps_subclass_code,
                'rpta_total_area_planted' => $plantTree->rpta_total_area_planted,
                'rpta_non_fruit_bearing' => $plantTree->rpta_non_fruit_bearing,
                'rpta_fruit_bearing_productive' =>$plantTree->rpta_fruit_bearing_productive,
                'rpta_date_planted' =>$plantTree->rpta_date_planted,
                'rpta_unit_value' => $plantTree->rpta_unit_value,
                'rpta_market_value' =>$plantTree->rpta_market_value,
                'rpta_taxable' => ($plantTree->rpta_taxable == null)?0:1,
                'rpta_registered_by' => \Auth::user()->creatorId(),
                'pt_ptrees_description' => $plantTree->pt_ptrees_description,
                'pc_class_description' => $plantTree->pc_class_description,
                'ps_subclass_desc'   => $plantTree->ps_subclass_desc,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $plantTreeAppForLand[] = (object)$plantsTreeApp;
            }
            $dataToSave = [
                'id'                => null,
                'rp_code'           => null,
                'pc_class_code'     => $value->pc_class_code,
                'pc_class_description' => $value->class->pc_class_description,
                'ps_subclass_code' => $value->ps_subclass_code,
                'ps_subclass_desc' => $value->subClass->ps_subclass_desc,
                'pau_actual_use_code' => $value->pau_actual_use_code,
                'pau_actual_use_desc' => $value->actualUses->pau_actual_use_desc,
                'land_stripping_id' => '',
                'rpa_adjustment_factor_a'   => $value->rpa_adjustment_factor_a,
                'rpa_adjustment_factor_b'   => $value->rpa_adjustment_factor_b,
                'rpa_adjustment_factor_c'   => $value->rpa_adjustment_factor_c,
                'rpa_adjustment_percent'    => $value->rpa_adjustment_percent,
                'rpa_adjustment_value'      => $value->rpa_adjustment_value,
                'rls_code' =>$value->rls_code,
                'lav_strip_unit_value' =>$value->lav_strip_unit_value,
                'rls_percent' => $value->rls_percent,
                'rpa_total_land_area' =>$value->rpa_total_land_area,
                'lav_unit_measure' => $value->lav_unit_measure,
                'lav_unit_value' => $value->lav_unit_value,
                'rpa_base_market_value' => $value->rpa_base_market_value,
                'al_assessment_level'   => $value->al_assessment_level,
                'rpa_adjusted_market_value'   => $value->rpa_adjusted_market_value,
                'rpa_assessed_value'   => $value->rpa_assessed_value,
                'rpa_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'plantsTreeApraisals'=> $plantTreeAppForLand
            ];
            $savedLandApprSessionData = $request->session()->get('landAppraisals');
            $savedLandApprSessionData[] = (object)$dataToSave;
            $request->session()->put('landAppraisals', $savedLandApprSessionData);
        }*/
        /* Add Land Apraisal data to session */
        $view = view('rptproperty.ajax.sd.step2',compact('selectedProperty','updateCode','selectedLandAppraisal'))->render();
        echo $view;
    }

    public function setDataForUpdateCodeFunctionality($request){
        
        $inputData = [
            'oldpropertyid' => $request->oldpropertyid,
            'updateCode'    => $request->updateCode,
            'propertykind'  => $request->propertykind
        ];
        $request->session()->forget('landAppraisals');
        $selectedProperty = $this->_rptproperty->with([
            'landAppraisals.class',
            'landAppraisals.subClass',
            'landAppraisals.actualUses',
            'plantTreeAppraisals',
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
        foreach ($selectedProperty->landAppraisals as $key=>$value) {
            $plantsTreeApprasals = $this->_rptproperty->getPalntsTreesAppraisalDetails($value->id);
            $plantTreeAppForLand = [];
            foreach ($plantsTreeApprasals as $plantTree) {
                $plantsTreeApp = [
                'id'                => null,
                'rp_code'           => null,
                'rpa_code'          => null,
                'rp_planttree_code' => $plantTree->rp_planttree_code,
                'rvy_revision_year' => $plantTree->rvy_revision_year,
                'rvy_revision_code' => $plantTree->rvy_revision_code,
                'pc_class_code' => $plantTree->pc_class_code,
                'ps_subclass_code' => $plantTree->ps_subclass_code,
                'rpta_total_area_planted' => $plantTree->rpta_total_area_planted,
                'rpta_non_fruit_bearing' => $plantTree->rpta_non_fruit_bearing,
                'rpta_fruit_bearing_productive' =>$plantTree->rpta_fruit_bearing_productive,
                'rpta_date_planted' =>$plantTree->rpta_date_planted,
                'rpta_unit_value' => $plantTree->rpta_unit_value,
                'rpta_market_value' =>$plantTree->rpta_market_value,
                'rpta_taxable' => ($plantTree->rpta_taxable == null)?0:1,
                'rpta_registered_by' => \Auth::user()->creatorId(),
                'pt_ptrees_description' => $plantTree->pt_ptrees_description,
                'pc_class_description' => $plantTree->pc_class_description,
                'ps_subclass_desc'   => $plantTree->ps_subclass_desc,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $plantTreeAppForLand[] = (object)$plantsTreeApp;
            }
            $dataToSave = [
                'id'                => null,
                'rp_code'           => null,
                'pc_class_code'     => $value->pc_class_code,
                'pc_class_description' => $value->class->pc_class_description,
                'ps_subclass_code' => $value->ps_subclass_code,
                'ps_subclass_desc' => $value->subClass->ps_subclass_desc,
                'pau_actual_use_code' => $value->pau_actual_use_code,
                'pau_actual_use_desc' => $value->actualUses->pau_actual_use_desc,
                'land_stripping_id' => '',
                'rpa_adjustment_factor_a'   => $value->rpa_adjustment_factor_a,
                'rpa_adjustment_factor_b'   => $value->rpa_adjustment_factor_b,
                'rpa_adjustment_factor_c'   => $value->rpa_adjustment_factor_c,
                'rpa_adjustment_percent'    => $value->rpa_adjustment_percent,
                'rpa_adjustment_value'      => $value->rpa_adjustment_value,
                'rls_code' =>$value->rls_code,
                'lav_strip_unit_value' =>$value->lav_strip_unit_value,
                'rls_percent' => $value->rls_percent,
                'rpa_total_land_area' =>$value->rpa_total_land_area,
                'lav_unit_measure' => $value->lav_unit_measure,
                'lav_unit_value' => $value->lav_unit_value,
                'rpa_base_market_value' => $value->rpa_base_market_value,
                'al_assessment_level'   => $value->al_assessment_level,
                'rpa_adjusted_market_value'   => $value->rpa_adjusted_market_value,
                'rpa_assessed_value'   => $value->rpa_assessed_value,
                'rpa_registered_by' => \Auth::user()->creatorId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'plantsTreeApraisals'=> $plantTreeAppForLand,
                'rpa_adjusted_plant_tree_value'=>$value->rpa_adjusted_plant_tree_value,
                'rpa_adjusted_total_planttree_market_value'=>$value->rpa_adjusted_total_planttree_market_value
            ];
            $savedLandApprSessionData = $request->session()->get('landAppraisals');
            $savedLandApprSessionData[] = (object)$dataToSave;
            $request->session()->put('landAppraisals', $savedLandApprSessionData);
            if($request->updateCode == config('constants.update_codes_land.DUP')){
                //dd($selectedProperty->propertyApproval);
                $dataToSaveInApprovalForm = [
            'rp_app_appraised_by' => $selectedProperty->propertyApproval->rp_app_appraised_by,
            //'rp_app_appraised_date' => $selectedProperty->propertyApproval->rp_app_appraised_date,
            'rp_app_appraised_date' =>date("d-m-Y"),
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
            'rp_app_posting_date' => date("Y-m-d"),
            'rp_app_memoranda' => $selectedProperty->rp_app_memoranda,
            'rp_app_extension_section' => $selectedProperty->rp_app_extension_section,
            'pk_is_active' => $selectedProperty->pk_is_active,
            'rp_app_assessor_lot_no' => $selectedProperty->rp_app_assessor_lot_no,
            'rp_app_cancel_by' => \Auth::user()->creatorId()
        ];
        $request->session()->forget('approvalFormData');
        $request->session()->put('approvalFormData', (object)$dataToSaveInApprovalForm);

            }
        if($request->updateCode == config('constants.update_codes_land.PC') || $request->updateCode == config('constants.update_codes_land.RC') || $request->updateCode == config('constants.update_codes_land.SSD')){
                $dataToSaveInApprovalForm = [
            'rp_app_appraised_by' => $selectedProperty->propertyApproval->rp_app_appraised_by,
           // 'rp_app_appraised_date' => $selectedProperty->propertyApproval->rp_app_appraised_date,
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
            'rp_app_posting_date' => date("Y-m-d"),
            'rp_app_memoranda' => '',
            'rp_app_extension_section' => $selectedProperty->rp_app_extension_section,
            'cancelled_by_id'    => $selectedProperty->id,
            'pk_is_active' => 1,
            'rp_app_assessor_lot_no' => $selectedProperty->rp_app_assessor_lot_no,
            'rp_app_cancel_by' => \Auth::user()->creatorId()
        ];
        $request->session()->forget('approvalFormData');
        $request->session()->put('approvalFormData', (object)$dataToSaveInApprovalForm);

            }

        }
        return [
            'status'=>'success',
            'data'=>$inputData
        ];
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

        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'RC');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }

        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
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
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'PC');
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
        $selectedProperty = $this->_rptproperty->with(['landAppraisals.class','plantTreeAppraisals','propertyApproval','revisionYearDetails','barangay','propertyOwner'])->where('id',$request->selectedproperty)->get()->first();
        
       
        $view = view('rptproperty.ajax.ssd.index',compact('selectedProperty','updateCode'))->render();
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
        $result = $this->conditionsForUpdateCodes($request->oldpropertyid,'SDD');
        if(!$result['status']){
            return response()->json([
                    'status' => 'error',
                    'msg'    => $result['msg']

                ]);
        }
        $response = $this->setDataForUpdateCodeFunctionality($request);

        return response()->json($response);
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

    public function setData($propertyId = '', $updateCode = ''){
        if(empty($this->activeRevisionYear) || empty($this->activeMuncipalityCode)){
            return [];
        }
        //$updateCodeDetails = $this->_rptproperty->getUpdateCodeById($updateCode);
        $updateCodeDetails = (in_array($updateCode,array_values(config('constants.update_codes_land'))))?array_flip(config('constants.update_codes_land'))[$updateCode]:'';
        $this->data['update_code'] = $updateCodeDetails;
        $this->data['uc_code'] = $updateCode;
        if($updateCodeDetails == ''){
            return [];
        }
        $selectedPropertyDetails = $this->_rptproperty->with('revisionYearDetails','propertyApproval')->where('id',$propertyId)->first();
        if($selectedPropertyDetails != null){
            $activeBarangay = $this->_barangay->getActiveBarangayCode($selectedPropertyDetails->brgy_code_id);
        }
        
        switch($updateCodeDetails){
            case 'TR':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;

                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_pin_suffix'] = $selectedPropertyDetails->rp_pin_suffix;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rp_suffix'] = $selectedPropertyDetails->rp_suffix;
                $data = (object)$this->data;

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
                    /*'rp_app_recommend_date' => $selectedPropertyDetails->propertyApproval->rp_app_recommend_date,*/
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
                session()->put('approvalFormData', (object)$arrApprove);
            }else{
                $data = (object)$this->data;
            }
            break;

            case 'PC':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
                $data = (object)$this->data;
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
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'SSD':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
                $data = (object)$this->data;
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

            case 'RC':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
                $data = (object)$this->data;
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
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'CS':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                //$this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;

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
                session()->put('approvalFormData', (object)$arrApprove);
                $data = (object)$this->data;
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'SD':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
                $data = (object)$this->data;
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'DUP':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $selectedPropertyDetails->rvy_revision_year_id;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
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
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['created_against'] = $selectedPropertyDetails->id;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
                $data = (object)$this->data;
            }else{
                $data = (object)$this->data;
            }
            
            break;

            case 'DC':
            if($selectedPropertyDetails != null){
                $this->data['rvy_revision_year_id'] = $this->activeRevisionYear->id;
                $this->data['rvy_revision_year']    = $this->activeRevisionYear->rvy_revision_year;
                $this->data['rvy_revision_code']    = $this->activeRevisionYear->rvy_revision_code;
                
                $this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;

                $this->data['rp_property_code']    = $selectedPropertyDetails->rp_property_code;
                $this->data['rp_cadastral_lot_no'] = $selectedPropertyDetails->rp_cadastral_lot_no;
                $this->data['rpo_code'] = $selectedPropertyDetails->rpo_code;
                $this->data['rp_section_no'] = $selectedPropertyDetails->rp_section_no;
                $this->data['rp_pin_no'] = $selectedPropertyDetails->rp_pin_no;
                $this->data['rp_administrator_code'] = $selectedPropertyDetails->rp_administrator_code;
                $this->data['rp_oct_tct_cloa_no'] = $selectedPropertyDetails->rp_oct_tct_cloa_no;
                $this->data['rp_bound_north'] = $selectedPropertyDetails->rp_bound_north;
                $this->data['rp_bound_east'] = $selectedPropertyDetails->rp_bound_east;
                $this->data['rp_bound_south'] = $selectedPropertyDetails->rp_bound_south;
                $this->data['rp_bound_west'] = $selectedPropertyDetails->rp_bound_west;
                $this->data['rp_location_number_n_street'] = $selectedPropertyDetails->rp_location_number_n_street;
                $this->data['rp_app_taxability'] = $selectedPropertyDetails->rp_app_taxability;
                /*$this->data['brgy_code_id'] = $selectedPropertyDetails->brgy_code_id;
                $this->data['loc_local_code'] = $selectedPropertyDetails->loc_local_code;
                $this->data['dist_code'] = $selectedPropertyDetails->dist_code;
                $this->data['loc_group_brgy_no'] = $selectedPropertyDetails->loc_group_brgy_no;
                $this->data['rp_tax_declaration_no'] = $activeBarangay->brgy_code.$this->data['rp_td_no'];
                $this->data['uc_code'] = $updateCode;
                $this->data['brgy_code_and_desc'] = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $this->data['loc_local_code_name'] = $activeBarangay->loc_local_code.'-'.$activeBarangay->loc_local_name;
                $this->data['dist_code_name'] = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $this->data['loc_group_brgy_no'] = $activeBarangay->brgy_name;
                $this->data['rp_location_number_n_street'] = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;*/
                session()->put('landSelectedBrgy',$selectedPropertyDetails->brgy_code_id);
                $data = (object)$this->data;
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

    public function getAllProfiles(){
        return $this->_rptproperty->getprofiles();
    }
    public function deleteAttachment(Request $request){
        $id = $request->input('id');
        $arredit = $this->_rptproperty->getPropertydocbyidtodelete($id);
        if($arredit){
             $path =  public_path().'/uploads/rpt/location/'.$arredit->doc_link;
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                }
        $arr = $this->_rptproperty->deletePropertydocbyid($id);
        echo "deleted";
    }
    public function deletelocationlink(Request $request){
        $id = $request->input('id');
        $arr = $this->_rptproperty->deletePropertydocbyid($id);
        echo "deleted";
    }
    public function generateDocumentListnew($rp_property_code){
        $html = "";
        $arr = $this->_rptproperty->getPropertydocbyid($rp_property_code);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$val->doc_link."</span></td>
                        <td><a class='btn' href='".asset('uploads/rpt/location').'/'.$val->doc_link."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm btn_delete_documents ti-trash text-white text-white' id='".$val->id."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        return $html;
    }

     public function uploadDocument(Request $request){
        $property_code =  $request->input('property_code');
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if($image = $request->file('file')) {
            $destinationPath =  public_path().'/uploads/rpt/location/';
            if(!File::exists($destinationPath)) { 
                File::makeDirectory($destinationPath, 0755, true, true);
            }
             $filename = "attachment_".time().'.'.$image->extension();
             $image->move($destinationPath, $filename);
             $arrData = array();
             $arrData['filename'] = $filename;
             $finalJsone[] = $arrData;
             $locationarray = array();
             $locationarray['rp_property_code'] = $request->input('property_code');
             $locationarray['type'] = "1";
             $locationarray['doc_link'] = $filename;
             $locationarray['created_by']=\Auth::user()->id;
             $locationarray['created_at'] = date('Y-m-d H:i:s');
              $this->_rptproperty->AddPropertydoclinkData($locationarray);
            $arrDocumentList = $this->generateDocumentListnew($property_code);
        }
        
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }

    public function getLocationsbypropid(Request $request){
         $html ='<input type="hidden" name="property_code" value="'.$request->input('property_code').'">';
            $arrLocations = $this->_rptproperty->getPropertydoclinkbyid($request->input('property_code'));
            $i=1;
            $newI = 0;
            foreach ($arrLocations as $key => $val) {
                $html.='<div class="removedocumentsdata row pt10">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                  <div class="form-group"><div class="form-icon-user">
                                    <p class="serialnoclass" style="text-align:center;">'.$i.'</p>
                                    <input id="fileid" name="geoid[]" type="hidden" value="'.$val->id.'">
                                    </div>
                                  </div>
                                 </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-group">
                                       <input id="linkdesc" class="form-control linkdesc" name="linkdesc[]" type="text" value="'.$val->doc_link.'">
                                       <span class="validate-err linkdesc" id="linkdesc'.$newI.'"></span>
                                   </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                       <input id="remark" class="form-control" name="remark[]" type="text" value="'.$val->Remark.'">
                                   </div>
                                </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">
                                        <a class="btn btn-primary" href="'.$val->doc_link.'" target="_blank"><i class="ti-world"></i></a>
                                         <button type="button" class="btn btn-danger btn_cancel_locations" value="'.$val->id.'"><i class="ti-trash"></i></button>
                                       </div>
                                 </div>
                                </div>';  $i++;$newI++;
            }
            
                                                                                           

            $arr =array('status'=>'success','msg'=>'data saved successfully','dynadata'=>$html);

            echo json_encode($arr);
    }

    public function savegeolocationdata(Request $request){
        $newValidation = [
             'property_code' => 'required',
             "linkdesc"  => 'required',
             "linkdesc.*"  => "required|url",
        ];
        $validator = \Validator::make(
            $request->all(),  $newValidation,
            [
            'property_code.required' => 'Required Field',
            'linkdesc.*.required' => 'Required Field',
            'linkdesc.*.url' => 'Invalid URL',
        ]
        );
        
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
            echo json_encode($arr);exit;
        }
        //dd($request->all());
        if(isset($_POST['linkdesc'])){
            //dd($request->all());
             foreach ($_POST['linkdesc'] as $key => $value){ 
                        // print_r($image); exit;
                         $locationarray = array();
                         $locationarray['rp_property_code'] = $request->input('property_code');
                         $locationarray['type'] = "2";
                         $locationarray['doc_link'] = $_POST['linkdesc'][$key];
                         $locationarray['Remark'] = $_POST['remark'][$key];
                         $locationarray['created_by']=\Auth::user()->id;
                         $locationarray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['geoid'][$key])){
                            $this->_rptproperty->UpdatePropertydoclinkData($_POST['geoid'][$key],$locationarray);
                         }else{ if(!empty($_POST['linkdesc'][$key])){
                            $this->_rptproperty->AddPropertydoclinkData($locationarray);
                             }
                        }
                     
                    }
            }                                                                            

            $arr =array('status'=>'success','msg'=>'data saved successfully');

            echo json_encode($arr);
    }
    

    public function store(Request $request){
		
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC');
        //dd($updateCode);
        $propertyKind = $this->_rptproperty->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        if($request->getMethod() == "GET" && $updateCode == config('constants.update_codes_land.DC')){
        $request->session()->forget('approvalFormData');
        $request->session()->forget('plantTreeAppraisals');
        $request->session()->forget('landAppraisals');
        $request->session()->forget('propertySwornStatementBuilding');
        $request->session()->forget('propertyStatusForBuilding');
        $request->session()->forget('propertyAnnotationForBuilding');
        }
        $data = $this->setData($oldpropertyid,$updateCode);
        if($request->getMethod() == "GET" && empty($data) && !$request->has('id')){
            if($request->ajax()){
                return response()->json(['status'=>'error','msg'=>'RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!']);
            }else{
                return redirect()->route('rptproperty.index')->with('error', __('RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!'));
            }
        }
        
        $arrLocationdocs =array();
        $arrLocations = array();
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
        //dd($this->_rptproperty->getprofiles());
        foreach ($this->_rptproperty->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        } 
         
        $profile = $this->arrprofile;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $updateCodeDetails = $this->_rptproperty->getUpdateCodeById($data->uc_code);
                $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin'
            ])->where('id',$request->input('id'))->first();

            $arrLocations = $this->_rptproperty->getPropertydoclinkbyid($data->rp_property_code);
            $arrLocationdocs = $this->_rptproperty->getPropertydocbyid($data->rp_property_code);    
            //dd($data);
            $data->property_owner_address = $data->property_owner_details->standard_address;
            //dd($data);
            $data->rp_administrator_code_address = ($data->property_admin != null)?$data->property_admin_details->standard_address:'';
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revision_year_code->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            $taxDeclNuumber = $data->rp_td_no;
            $landAraisalDetails = $this->getLandAppraisalDetals($request->input('id'));
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                //dd($data->brgy_code_id);
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                //dd($activeBarangay);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->mun_desc;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
                //$data->rp_location_number_n_street = $activeBarangay->brgy_name.', '.$activeBarangay->mun_desc;
            }
            }
        if($request->isMethod('post')){
            //dd($request->all());
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
               if($request->input('id')>0){
                $dataToSave = $this->data;
                $access1 = $this->is_permitted($this->slugs, 'update', 1);
                if (!($access1 > 0)){
                    return abort(401);
                }
                 $savedProperty    = $this->_rptproperty->getSinglePropertyDetails($request->input('id'));
                 $approvalFormData = DB::table('rpt_property_approvals')->where('rp_code',$request->input('id'))->select('rp_app_cancel_is_direct')->first();
                /*if($savedProperty->pk_is_active == 0 && $approvalFormData->rp_app_cancel_is_direct == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }*/
                if($this->_rptproperty->checkToVerifyPsw($request->input('id'))){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
                DB::beginTransaction();
                try {
                    $this->_rptproperty->updateData($request->input('id'),$dataToSave);
                    $this->_rptproperty->updatePinDeclarationNumber($request->input('id'));
                    $lastinsertid = $request->input('id');
                    $this->_rptproperty->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptproperty->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                    $status = 'success';         
                } catch (\Exception $e) {
                    DB::rollback();
                    $success_msg = $e->getMessage();
                    $status = 'error'; 
                }
                
                }else{
                $landAppSesionData  = session()->get('landAppraisals');
                if(empty($landAppSesionData)){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Land Appraisal details!']);
                }
                $sesionData  = session()->get('approvalFormData');
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
                if($request->uc_code =='11'){
                 $this->data['created_against'] = $request->old_property_id;   
                }
                $dataToSave = $this->data;
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptproperty->addData($dataToSave);
                    $lastinsertid = $request->id;
                    //$lastinsertid = 97;
                    //dd($lastinsertid);
                    $savedProperty = $this->_rptproperty->getSinglePropertyDetails($lastinsertid);
                    $this->_rptproperty->generateTaxDeclarationAndPropertyCode($lastinsertid);
                    $this->updatePlantTreeAppraisal($lastinsertid);
                    $this->updateApprovalForm($lastinsertid);
                    $this->updateLandAppraisal($lastinsertid);
                    $this->updateSwornStatement($lastinsertid);
                    $this->updatePropertyStatus($lastinsertid);
                    $this->_rptproperty->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptproperty->syncAssedMarketValueToMainTable($lastinsertid);
                    DB::commit();
                    $newGeneratedPropertyDetails = RptProperty::find($lastinsertid);
                    $oldPropertyData = RptProperty::find($request->old_property_id);
                    if($selectedUpDateCode == config('constants.update_codes_land.TR') && $oldPropertyData != null){
                        $this->_rptproperty->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against Transfer of ownership of #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.PC') && $oldPropertyData != null){
                        $this->_rptproperty->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against Physical Changes of #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.RC') && $oldPropertyData != null){
                        $this->_rptproperty->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against Reclassification of #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.DUP') && $oldPropertyData != null){
                        $success_msg = 'Duplicate copy generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.SSD') && $oldPropertyData != null){
                        $this->_rptproperty->addDataInAccountReceivable($lastinsertid,$oldPropertyData->id);
                        $success_msg = 'Superseded done new Tax Declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no.' against #'.$oldPropertyData->rp_tax_declaration_no;
                    }else if($selectedUpDateCode == config('constants.update_codes_land.CS') && $oldPropertyData != null){
                        $success_msg = 'Consolidation completed with new No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    }else{
                        $this->_rptproperty->addDataInAccountReceivable($lastinsertid,0);
                        $success_msg = 'New tax declaration generated with No. #'.$newGeneratedPropertyDetails->rp_tax_declaration_no;
                    }
                    $status = 'success';
                } catch (\Exception $e) {
                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                }
            }
            //dd($savedProperty);
            $totalMarketValue = 0;
            if($request->ajax()){
                return response()->json(['status'=>$status,'msg'=>$success_msg]);
            }else{
                return redirect()->route('rptproperty.index')->with('success', __($success_msg));
            }
        } 
        return view('rptproperty.create',compact('arrRevisionYears','data','arrBarangay','profile','arrSubclasses','arrLocalityCodes','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','arrLandStrippingCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','activeBarangay','propertyKind','updateCode','oldpropertyid','arrLocations','arrLocationdocs'));
    }

    public function loadPreviousOwner(Request $request){
        $propertyKind = ($request->has('propertykind') && $request->propertykind != '')?$request->propertykind:$this->propertyKind;
        $updateCode = ($request->has('updatecode'))?$request->updatecode:config('constants.update_codes_land.DC');
        $propertyKind = $this->_rptproperty->getKindIdByCode($propertyKind);
        $oldpropertyid = ($request->has('oldpropertyid') && $request->oldpropertyid != '')?$request->oldpropertyid:'';
        $data = $this->setData($oldpropertyid,$updateCode);
        if($request->getMethod() == "GET" && empty($data) && !$request->has('id')){
            session()->forget('landAppraisals');
            if($request->ajax()){
                return response()->json(['status'=>'error','msg'=>'RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!']);
            }else{
                return redirect()->route('rptproperty.index')->with('error', __('RPT Active Revision Year or Muncipality is Missing, Please set before go ahead!'));
            }
        }
        if($request->getMethod() == "GET"){
            session()->forget('landAppraisals');
            session()->forget('approvalFormData');
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
        $approvelFormData = [];
        //dd($this->_rptproperty->getprofiles());
        foreach ($this->_rptproperty->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->standard_name;
        } 
         
        $profile = $this->arrprofile;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $updateCodeDetails = $this->_rptproperty->getUpdateCodeById($data->uc_code);
                $data = RptProperty::with([
                'revisionYearDetails',
                'propertyOwner',
                'propertyAdmin'
            ])->where('id',$request->input('id'))->first();   
            //dd($data);
            $data->property_owner_address = $data->property_owner_details->standard_address;
            $data->rp_administrator_code_address = ($data->property_admin != null)?$data->property_admin_details->standard_address:'';
            $data->update_code = $updateCodeDetails;
            $data->rvy_revision_year = $data->revision_year_code->rvy_revision_year;
            $propertyKind = $data->pk_id;
            $updateCode   = $data->uc_code;
            $taxDeclNuumber = $data->rp_td_no;
            $landAraisalDetails = $this->getLandAppraisalDetals($request->input('id'));
            $approvelFormData   = $data->propertyApproval;
            //dd($approvelFormData);
            if(isset($data->brgy_code_id) && $data->brgy_code_id != ''){
                $activeBarangay = $this->_barangay->getActiveBarangayCode($data->brgy_code_id);
                $data->brgy_code_and_desc = $activeBarangay->brgy_code.'-'.$activeBarangay->brgy_name;
                $data->loc_local_code_name = $activeBarangay->mun_desc;
                $data->dist_code_name = $activeBarangay->dist_code.'-'.$activeBarangay->dist_name;
                $data->loc_group_brgy_no = $activeBarangay->brgy_name;
            }
            }
        if($request->isMethod('post')){
                    if($request->has('rp_assessed_value')){
                    $request->request->add([
                        'rp_assessed_value' => (double)str_replace( ',', '', $request->rp_assessed_value),
                    ]);
                }
                //dd($request->all());
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
               if($request->input('id')>0){
                $dataToSave = $this->data;
                $access1 = $this->is_permitted($this->slugs, 'update', 1);
                if (!($access1 > 0)){
                    return abort(401);
                }
                 $savedProperty = $this->_rptproperty->getSinglePropertyDetails($request->input('id'));
                if($savedProperty->pk_is_active == 0){
                    return response()->json(['status'=>'error','msg'=>'Cancelled property cannot be updated!']);
                }
                DB::beginTransaction();
                try {
                    $this->_rptproperty->updateData($request->input('id'),$dataToSave);
                    $this->_rptproperty->syncAssedMarketValueToMainTable($request->input('id'));
                    $lastinsertid = $request->input('id');
                    if($request->has('rp_app_approved_by')){
                        $approvalFormId = DB::table('rpt_property_approvals')->select('id')->where('rp_code',$lastinsertid)->first();
                        $dataToUpdateAppForm = [
                            'rp_app_approved_by' => $request->rp_app_approved_by,
                            'rp_app_approved_date' => $request->rp_app_posting_date
                        ];
                        $this->_rptproperty->updateApprovalForm($approvalFormId->id,$dataToUpdateAppForm);
                    }
                    $this->_rptproperty->generateTaxDeclarationAform($lastinsertid);
                    DB::commit();
                    $status = 'success';
                    $success_msg = 'Tax declaration #'.$savedProperty->rp_tax_declaration_no.' has been updated successfully.';
                } catch (\Exception $e) {

                    DB::rollback();
                    $status = 'error';
                    $success_msg = $e->getMessage();
                }
                }else{
                $landAppSesionData  = session()->get('landAppraisals');
                if(empty($landAppSesionData)){
                    return response()->json(['status'=>'error','msg'=>'Please provide the Land Appraisal details!']);
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
                
                DB::beginTransaction();
                try {
                    $request->id = $this->_rptproperty->addData($dataToSave);
                    $lastinsertid = $request->id;
                    $savedProperty = $this->_rptproperty->getSinglePropertyDetails($lastinsertid);
                    $this->_rptproperty->generateTaxDeclarationAndPropertyCode($lastinsertid, true);
                    $this->updateApprovalFormForPreOwner($lastinsertid);
                    $this->updateLandAppraisal($lastinsertid);
                    $this->_rptproperty->generateTaxDeclarationAform($lastinsertid);
                    $this->_rptproperty->updateChain($lastinsertid);
                    $this->_rptproperty->syncAssedMarketValueToMainTable($lastinsertid);
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
                return redirect()->route('rptproperty.index')->with('success', __($success_msg));
            }
        }
        $allTds     = $this->_rptproperty->getPreviousOwnerTds((isset($request->oldpropertyid))?$request->oldpropertyid:$request->id); 
        $appraisers  = $this->arremployees;
        return view('rptproperty.ajax.addpreviousowner',compact('arrRevisionYears','data','arrBarangay','profile','arrSubclasses','arrLocalityCodes','arrDistNumbers','arrUpdateCodes','arrPropertyClasses','arrPropKindCodes','arrLandStrippingCodes','activeMuncipalityCode','landAraisalDetails','landAraisalDetails','activeBarangay','propertyKind','updateCode','oldpropertyid','allTds','appraisers','approvelFormData'));
    }

    public function getPreviousOwnertdDetails(Request $request){
        $prop = $request->id;
        $details = RptProperty::where('id',$prop)->first();
        //dd($details->machineAppraisals);
        if((isset($details->propertyKindDetails->pk_code)) && $details->propertyKindDetails->pk_code == 'B'){
            $view  = view('rptbuilding.ajax.previousownerappraisal',compact('details'))->render();
        }else if((isset($details->propertyKindDetails->pk_code)) && $details->propertyKindDetails->pk_code == 'M'){
            $view  = view('rptmachinery.ajax.previousownerappraisal',compact('details'))->render();
        }else{
            $view  = view('rptproperty.ajax.previousownerappraisal',compact('details'))->render();
        }
        
        $qrters = ['1'=>'1st','2'=>'2nd','3'=>'3rd','4'=>'4th'];
        $data = [
            'index_no' => (isset($details->rp_pin_declaration_no))?$details->rp_pin_declaration_no:'',
            'tax_payer_name' => (isset($details->propertyOwner->standard_name))?$details->propertyOwner->standard_name:'',
            'address' => (isset($details->propertyOwner->standard_address))?$details->propertyOwner->standard_address:'',
            'taxability' => (isset($details->rp_app_taxability))?(($details->rp_app_taxability == 1)?'Taxable':'Exempt'):'',
            'effectivity' => (isset($details->rp_app_effective_year))?$details->rp_app_effective_year:'',
            'quarter' => (isset($details->rp_app_effective_quarter))?((in_array($details->rp_app_effective_quarter,$qrters)?$qrters[$details->rp_app_effective_quarter]:$qrters[1])):'',
            'approvedby' => (isset($details->propertyApproval->approveBy->standard_name))?$details->propertyApproval->approveBy->standard_name:'',
            'date' => (isset($details->rp_app_posting_date))?date("m/d/Y",strtotime($details->rp_app_posting_date)):'',
            'view' => $view
        ];
        return response()->json($data);
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
            $lastInsrtedId = $this->_rptproperty->addPropertyStatusData($dataToSave);
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
                $this->_rptproperty->addAnnotationData($dataToSaveInAnootation);
            }
                

            }
            
            session()->forget('propertyStatusForBuilding');
            session()->forget('propertyAnnotationForBuilding');
        }
    }

    public function updateSwornStatement($id=''){
        $rptProperty = RptProperty::with(['propertyKindDetails','propertyApproval','landAppraisals'])->where('id',$id)->first();
        //dd($rptProperty);
        $sesionData  = session()->get('propertySwornStatementBuilding');
        if(!empty($sesionData) && $rptProperty != null){
            $dataToSave = (array)$sesionData;
            //$dataToSave['land_market_value'] = $rptProperty->landAppraisals->sum('rpa_adjusted_market_value');
            $dataToSave['rp_code'] = $rptProperty->id;
            $dataToSave['pk_code'] = $rptProperty->propertyKindDetails->pk_code;
            $dataToSave['created_at'] =  date('Y-m-d H:i:s');
            $dataToSave['updated_at'] =  date('Y-m-d H:i:s');
            $dataToSave['rps_registered_by'] =  \Auth::user()->id;
            //dd($dataToSave);
            $this->_rptproperty->addPropertySwornData($dataToSave);
            session()->forget('propertySwornStatementBuilding');
        }
    }

    public function getMuncipalityCodes(Request $request){
        $id = $request->input('id');
        $data = $this->_rptproperty->getMuncipLityCodeDetails($id);
        echo json_encode($data);
    }

    public function getBarangyCodeDetails(Request $request){
        $id = $request->input('id');
        $data = $this->_barangay->getActiveBarangayCode($id);
        echo json_encode($data);
    }

    public function getClassDetails(Request $request){
        $id = $request->input('id');
        $data = $this->_rptproperty->getClassDetails($id);
        echo json_encode($data);
    }

    public function getPlantTreeUnitValue(Request $request){
       // dd($request->all());
        $id = $request->input('id');
        $data = $this->_rptproperty->getPlantTreeUnitValue($request);
        $response = ['status'=>'error','data'=>[]];
        if($data != false){
            $response['status'] = 'success';
            $response['data'] = [
                'ptuv_unit_value'=>$data->ptuv_unit_value,
            ];
        }
        echo json_encode($response);
    }

    public function getAssessementLevel(Request $request){
        $data = $this->_rptproperty->getAssessementLevel($request);
        $response = [
            'status'=>'success',
            'data'=>[
                'al_assessment_level'=>0,
                'al_minimum_unit_value' => 0,
                'al_maximum_unit_value' => 0,
            ]];
        if($data != false){
            if(!$data->assessementRelations->isEmpty()){
                $ass = $data->assessementRelations;
                $response['data'] = [
                'al_assessment_level'=>$ass[0]->assessment_level,
                'al_minimum_unit_value' => $ass[0]->minimum_unit_value,
                'al_maximum_unit_value' => $ass[0]->maximum_unit_value,
            ];
            }
        }
        //dd($data);
        echo json_encode($response);
    }

    public function getLandUnitValue(Request $request){
       // dd($request->all());
        $id = $request->input('id');
        $data = $this->_rptproperty->getLandUnitValue($request);
        $response = ['status'=>'error','data'=>[]];
        if($data != false){
            $response['status'] = 'success';
            $response['data'] = [
                'lav_strip_unit_value'=>$data->lav_strip_unit_value,
                'lav_unit_measure' => $data->lav_unit_measure,
                'lav_unit_measure_name' => config('constants.lav_unit_measure.'.$data->lav_unit_measure),
                'lav_unit_value' => $data->lav_unit_value,
                'rls_code' => $data->rls_code,
                'rls_percent' => $data->rls_percent,
            ];
        }
        echo json_encode($response);
    }

    public function getRevisionYearDetails(Request $request){
        $id = $request->input('pid');
        $data = $this->_rptproperty->getRevisionYearDetails($id);
        echo json_encode($data);
    }
    

	public function getLandStrippingDetails(Request $request){
		$id = $request->input('id');
		$data = $this->_rptproperty->getLandStrippingDetails($id);
		echo json_encode($data);
	}

    public function getprofileData(Request $request){
        $id = $request->input('pid');
        $data = $this->_rptproperty->getprofileData($id);
        echo json_encode($data);
    }

    public function getPropertyOwners ($value=''){
        $html = '<option value="">Select Property Owner</option>';
        foreach ($this->_rptproperty->getprofiles() as $val) {
            $html .= '<option value="'.$val->id.'">'.$val->rpo_custom_last_name.', '.$val->rpo_address_house_lot_no.', '.$val->rpo_address_street_name.', '.$val->rpo_address_subdivision.'</option>';
        }
        echo $html;
    }
    
    public function getSubClasses(Request $request){

        $id = $request->input('id');
        $data = $this->_rptproperty->getSubClassesList($id);
        $html = '<option value="">Select Sub Class</option>';
        foreach ($data as $subClass) {
            $html .= '<option value="'.$subClass->id.'">'.$subClass->ps_subclass_code.'</option>';
        }
        echo $html;
    }

    public function getActualUses(Request $request){
        $id = $request->input('id');
        $data = $this->_rptproperty->getActualUsesList($id);
        $html = '<option value="">Select Actual Use Code</option>';
        foreach ($data as $subClass) {
            $html .= '<option value="'.$subClass->id.'">'.$subClass->pau_actual_use_code.'</option>';
        }
        echo $html;
    }


    public function getDistrictCodes(Request $request){
        $id = $request->input('id');
        $data = $this->_rptproperty->getDistrictCodesBasedOnLocality($id);
        $html = '<option value="">District Code</option>';
        foreach ($data as $subClass) {
            $html .= '<option value="'.$subClass->dist_code.'">'.$subClass->dist_code.'</option>';
        }
        echo $html;
        
    }

    public function getLandAppraisalDetals($id){
        $arrbDetails = array();
        $i=0;
        $landAppraisals = $this->_rptproperty->getLandAppraisalDetals($id);
        //dd($landAppraisals);
        foreach ($landAppraisals as $val) {
            $arrbDetails[$i]['id']=$val->id;
            $arrbDetails[$i]['rp_code']=$val->rp_code;
            $arrbDetails[$i]['pk_code']=$val->pk_code;
            $arrbDetails[$i]['rvy_revision_year']=$val->rvy_revision_year;
            $arrbDetails[$i]['rvy_revision_code']=$val->rvy_revision_code;
            $arrbDetails[$i]['pc_class_code']=$val->pc_class_code;
            $arrbDetails[$i]['pc_class_description']=$val->pc_class_description;
            $arrbDetails[$i]['ps_subclass_code']=$val->ps_subclass_code;
             $arrbDetails[$i]['ps_subclass_desc']=$val->ps_subclass_desc;
            $arrbDetails[$i]['pau_actual_use_code']=$val->pau_actual_use_code;
            $arrbDetails[$i]['pau_actual_use_desc']=$val->pau_actual_use_desc;
            $arrbDetails[$i]['lav_unit_value']=$val->lav_unit_value;
            $arrbDetails[$i]['rpa_total_land_area']=$val->rpa_total_land_area;
            $arrbDetails[$i]['lav_unit_measure']=$val->lav_unit_measure; 
            $arrbDetails[$i]['rls_code']=$val->rls_code;
            $arrbDetails[$i]['rls_percent']=$val->rls_percent;
            $arrbDetails[$i]['lav_strip_unit_value']=$val->lav_strip_unit_value;
            $arrbDetails[$i]['rpa_base_market_value']=$val->rpa_base_market_value;
            $arrbDetails[$i]['rpa_adjusted_market_value']=$val->rpa_adjusted_market_value;
            $arrbDetails[$i]['al_assessment_level']=$val->al_assessment_level;
            $arrbDetails[$i]['rpa_assessed_value']=$val->rpa_assessed_value;
            $arrbDetails[$i]['rpa_taxable']=$val->rpa_taxable;
            $arrbDetails[$i]['rpa_adjustment_factor_a']=$val->rpa_adjustment_factor_a;
            $arrbDetails[$i]['rpa_adjustment_factor_b']=$val->rpa_adjustment_factor_b;
            $arrbDetails[$i]['rpa_adjustment_factor_c']=$val->rpa_adjustment_factor_c;
            $arrbDetails[$i]['rpa_adjustment_percent']=$val->rpa_adjustment_percent;
            $arrbDetails[$i]['rpa_adjustment_value']=$val->rpa_adjustment_value;
            $subClasses = $this->_rptproperty->getSubClassesList($val->pc_class_code);
            $actualUses = $this->_rptproperty->getActualUsesList($val->pc_class_code);
            $arrbDetails[$i]['sub_classes']=$subClasses->pluck('ps_subclass_desc','id')->toArray();
            $arrbDetails[$i]['actual_uses']=$actualUses->pluck('pau_actual_use_desc','id')->toArray();
            $i++;
        }
        //dd($arrbDetails);
        return $arrbDetails;    
    }
    public function addNatureOfBusiness($request){
        $psic_subclass_id = $request->input('psic_subclass_id');
        $arr = array();
        $i=0;
        foreach ($psic_subclass_id as $key => $value) {
            if(!empty($request->input('psic_subclass_id')[$key])){
                $arr[$i]['psic_subclass_id']=$request->input('psic_subclass_id')[$key];
                $arr[$i]['taxable_item_name']=$request->input('taxable_item_name')[$key];
                $arr[$i]['taxable_item_qty']=$request->input('taxable_item_qty')[$key];
                $arr[$i]['capital_investment']=$request->input('capital_investment')[$key];
                $arr[$i]['date_started']=$request->input('date_started')[$key];
                $i++;
            }
        }
        if(count($arr)>0){
            $json = json_encode($arr);
            $arrData=array("nature_of_bussiness_json"=>$json);
            $this->_bploApplication->updateData($request->id,$arrData);
        }
    }

    public function formValidation(Request $request){
        
        $rules = [
                'rvy_revision_year_id'=>'required',
                'brgy_code_id'=>'required',
                'rp_suffix'=>'max:5', 
                'loc_local_code'=>'required',
                'dist_code'=>'required',
                'rp_section_no'=>'required|max:2|', 
                'rp_pin_no'=>'required|numeric|digits_between:1,10', 
                'rp_pin_suffix'=>'max:4', 
                'rp_oct_tct_cloa_no' => 'required',
                'profile_id' => 'required',
                //'loc_group_brgy_no' => 'required',
               // 'rp_location_number_n_street' => 'required',
                'uc_code' => 'required',
                'rp_cadastral_lot_no' => 'required',
                'property_owner_address' => 'required',
                /*'rp_bound_north' => 'required',
                'rp_bound_east' => 'required',
                'rp_bound_south' => 'required',
                'rp_bound_west' => 'required'*/
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
             $rules['rp_pin_suffix'] = 'max:4';
        }
        $propWithSaneSection = RptProperty::where('rp_section_no',$request->rp_section_no)->get();
            if($propWithSaneSection->count() > 1){
                $rules['rp_section_no'] = 'required|max:2';
                $rules['rp_pin_no'] = 'required|numeric|digits_between:1,10';
               $rules['rp_pin_suffix'] = 'max:4';
            }
            if($request->has('uc_code') && $request->uc_code == config('constants.update_codes_land.SD')){
             $rules['rp_pin_no'] = ['required','numeric','digits_between:1,10',Rule::unique('rpt_properties')->where(function($query)use($request){
                $query->where('pk_is_active',1)
                      ->whereNotIn('id', [$request->id,$request->old_property_id])
                      ->where('rp_property_code', $request->rp_property_code)
                      ->where('uc_code',config('constants.update_codes_land.SD'))
                      ->get();
                      
             })];
        }
        $postpin = $request->has('rp_pin_no');    
        $validator = \Validator::make(
            $request->all(), $rules,
            [
                'rvy_revision_year_id.required' => 'Required Field',
                'brgy_code_id.required' => 'Required Field',
                'rp_td_no.required' => 'Required Field',
                'rp_pin_suffix.required' => 'Required Field',
                'rp_pin_suffix.max' => 'Only 4 Digits',
                'rp_pin_suffix.unique' => 'Already in use',
                'rp_suffix.max' => 'Only 5 Digits',
                'loc_local_code.required' => 'Required Field',
                'dist_code.required' => 'Required Field',
                'brgy_code.required' => 'Required Field',
                'rp_section_no.required' => 'Required Field',
                'rp_section_no.max' => 'Only 2 Digits',
                //'rp_section_no.unique' => 'Already in use',
                'rp_pin_no.required' => 'Required Field',
                'rp_pin_no.digits_between' => 'Invalid Value',
                'rp_pin_no.numeric' => 'Numeric only',
                //'rp_pin_no.unique' => 'Already in use',
                'rp_oct_tct_cloa_no.required' => 'Required Field',
                'profile_id.required' => 'Required Field',
                'loc_group_brgy_no.required' => 'Required Field',
                'rp_location_number_n_street.required' => 'Required Field',
                'uc_code.required' => 'Required Field',
                'rp_cadastral_lot_no.required' => 'Required Field',
                'property_owner_address.required' => 'Required Field',
                'rp_administrator_code.required' =>'Required Field',
                'rp_bound_north.required' => 'Required Field',
                'rp_bound_east.required' => 'Required Field',
                'rp_bound_south.required' => 'Required Field',
                'rp_bound_west.required' => 'Required Field',
                'rp_app_effective_year.required' => 'Required Field',
                'rp_app_approved_by.required' => 'Required Field',
                'rp_app_approved_by.required' => 'Required Field',
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
           // dd($data);
            if($data['uc_code'] == config('constants.update_codes_land.DUP')){
                $oldPropertyData = RptProperty::find($data['old_property_id']);
                if($oldPropertyData != null){
                    //dd($oldPropertyData);
                    if($oldPropertyData->rpo_code == $data['rpo_code']){
                        $validator->errors()->add('profile_id', 'Should be different from previous property!');
                    }
                }
            }
            if($data['uc_code'] == config('constants.update_codes_land.SD')){
                $pinPropertyData = $this->_rptproperty->getpropertiesbyPin($data['rp_pin_no'],$data['id']);

                if(count($pinPropertyData) >= 2 ){
                $validator->errors()->add('rp_pin_no', 'Should be different from previous Sub divided property!');
                }
            }
        });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }else{
            $isDuplicate = RptProperty::where('loc_local_code',$request->loc_local_code)
                ->where('dist_code',$request->dist_code)
                ->where('brgy_code_id',$request->brgy_code_id)
                ->where('rp_section_no',$request->rp_section_no)
                ->where('rp_pin_no',$request->rp_pin_no)
                ->where('rp_pin_suffix',$request->rp_pin_suffix)
                ->where('rp_property_code','!=',$request->rp_property_code)
                ->count();
            if($isDuplicate>0){
                $arr['field_name'] = 'duplicatesFields';
                $arr['error'] = "Already in use[Locality, District, Barangay, Section No., PIN No., PIN Suffix], Please try another.";
                $arr['ESTATUS'] = 1;
            }elseif($request->update_code=='TR' && $request->old_property_id>0){
                $isDuplicate = RptProperty::where('id',$request->old_property_id)
                    ->where('rpo_code',$request->profile_id)->count();
                if($isDuplicate>0){
                    $arr['field_name'] = 'profile_id';
                    $arr['error'] = "Already in use";
                    $arr['ESTATUS'] = 1;
                }    
            }
        }
        echo json_encode($arr);exit;
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
            $dataToSave['rp_app_cancel_by_td_id'] = ($request->has('rp_app_cancel_is_direct'))?'':'';
        }
        //dd($dataToSave);
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
            $this->_rptproperty->updateData($approvelFormDetails->rp_code,$dataToUpdateInParent);
            unset($dataToSave['rp_app_effective_year']);
            unset($dataToSave['rp_app_effective_quarter']);
            unset($dataToSave['rp_app_posting_date']);
            unset($dataToSave['rp_app_memoranda']);
            unset($dataToSave['rp_app_extension_section']);
            unset($dataToSave['pk_is_active']);
            unset($dataToSave['uc_code']);
            unset($dataToSave['rp_app_assessor_lot_no']);
            unset($dataToSave['rp_app_taxability']);
            //dd($dataToSave);
            $this->_rptproperty->updateApprovalForm($request->id,$dataToSave);
            if($approvelFormDetails->rp_app_appraised_by != 0 && $approvelFormDetails->rp_app_recommend_by != 0 && $approvelFormDetails->rp_app_approved_by != 0){
                $newPropDetails = RptProperty::with('updatecode')->where('id',$approvelFormDetails->rp_code)->first();
                //dd($newPropDetails);
                $this->updatePropertyHistory($dataToSave['rp_app_cancel_by_td_id'],$newPropDetails);
            }

            if($request->pk_is_active == 0 && $request->has('rp_app_cancel_is_direct') && $request->rp_app_cancel_is_direct == 1){
                //dd($request->all());
                $request->request->add(
                    [
                        'oldpropertyid'  => $approvelFormDetails->rp_code,
                        'updateCode'     => $request->rp_app_cancel_type,
                        'propertykind'   => 2,
                        'remarks'        => $request->rp_app_cancel_remarks,
                        'approvalformid' => $request->id
                    ]
                );

                $this->dpFunctionlaitySbubmit($request);
                 $this->_rptproperty->updateAccountReceiaveableDetails($approvelFormDetails->rp_code, true);
            }
            if($request->pk_is_active == 1 && $approvelFormDetails != null){
                $this->_rptproperty->disableDirectCancellation($approvelFormDetails->rp_code);


            }
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else{
            //dd($dataToSave);
             $request->session()->put('approvalFormData', (object)$dataToSave);
             $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }
        return response()->json($response);  
    }

    public function storelandAppraisalFactors(Request $request){

        if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            $rptPropertyDetails = RptProperty::find($request->property_id);
            $apprasalDetails = RptPropertyAppraisal::find($request->id);
            if($this->_rptproperty->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password','previousValues' => ['factor_a' => $apprasalDetails->rpa_adjustment_factor_a,'factor_b' => $apprasalDetails->rpa_adjustment_factor_b,'factor_c' => $apprasalDetails->rpa_adjustment_factor_c]]);
                }
                session()->forget('verifyPswLand');
                /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                    return response()->json([
            'status'=>'error',
            'msg'=>'You can not update cancelled Tax Declaration!'
        ]);
                }*/
            $dataToUpdate = [
                'rpa_adjustment_factor_a'   => $request->rpa_adjustment_factor_a,
                'rpa_adjustment_factor_b'   => $request->rpa_adjustment_factor_b,
                'rpa_adjustment_factor_c'   => $request->rpa_adjustment_factor_c,
                'rpa_adjustment_percent'    => str_replace('%', '', $request->rpa_adjustment_percent),
                'rpa_adjustment_value'      => str_replace(array( '(', ')' ), '', $request->rpa_adjustment_value),
                'rpa_adjusted_market_value' => $request->rpa_adjusted_market_value,
                'rpa_modified_by'           => \Auth::user()->id,
                'updated_at'                => date('Y-m-d H:i:s')
            ];
            //dd($dataToUpdate);
            $this->_rptproperty->updateLandAppraisalDetail($request->id,$dataToUpdate);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }else{
                if($request->has('session_id') && $request->session_id != ''){
                    //dd($request->all());
                    $savedSessionData = $request->session()->get('landAppraisals.'.$request->session_id);
                    $savedSessionData->rpa_adjustment_factor_a = $request->rpa_adjustment_factor_a;
                    $savedSessionData->rpa_adjustment_factor_b = $request->rpa_adjustment_factor_b;
                    $savedSessionData->rpa_adjustment_factor_c = $request->rpa_adjustment_factor_c;
                    $savedSessionData->rpa_adjustment_percent = str_replace('%', '', $request->rpa_adjustment_percent);
                    $savedSessionData->rpa_adjustment_value = str_replace(array( '(', ')' ), '', $request->rpa_adjustment_value);
                    $savedSessionData->rpa_adjusted_market_value = $request->rpa_adjusted_market_value;
                    $request->session()->put('landAppraisals.'.$request->session_id,$savedSessionData);
                }
                $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
           }
           $this->calculateAdjustMarketTreesValue($request->id,$request->session_id);
        return response()->json($response);  
    }

    public function storePlantsTreesAdjustmentFactor(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'rp_planttree_code'=>'required',
                'plants_tree_ps_subclass_code'=>'required',
                'plants_tree_pc_class_code'=>'required', 
                'rpta_total_area_planted'=>'required',
                /*'rpta_non_fruit_bearing'=>'required',*/
                'rpta_fruit_bearing_productive'=>'required',
                'rpta_date_planted'=>'required', 
                'rpta_unit_value'=>'required', 
                'rpta_market_value'=>'required',
                'plant_tree_revision_year_code' => 'required'
            ],
            [
                'rp_planttree_code.required'=>'Required Field',
                'plants_tree_ps_subclass_code.required'=>'Required Field',
                'plants_tree_pc_class_code.required'=>'Required Field', 
                'ps_subclass_code.required'=>'Required Field', 
                'rpta_total_area_planted.required'=>'Required Field',
                'rpta_non_fruit_bearing.required'=>'Required Field',
                'rpta_fruit_bearing_productive.required'=>'Required Field',
                'rpta_date_planted.required'=>'Required Field', 
                'rpta_unit_value.required'=>'Required Field', 
                'rpta_market_value.required'=>'Required Field',
                'plant_tree_revision_year_code.required' => 'Please select revision year from main form'
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
            'rpa_code'           => $request->land_appraisal_id,
            'rp_planttree_code' => $request->rp_planttree_code,
            'rvy_revision_year' => $request->plant_tree_revision_year,
            'rvy_revision_code' => $request->plant_tree_revision_year_code,
            'pc_class_code' => $request->plants_tree_pc_class_code,
            'ps_subclass_code' => $request->plants_tree_ps_subclass_code,
            'rpta_total_area_planted' => $request->rpta_total_area_planted,
            'rpta_non_fruit_bearing' => $request->rpta_non_fruit_bearing,
            'rpta_fruit_bearing_productive' =>$request->rpta_fruit_bearing_productive,
            'rpta_date_planted' =>$request->rpta_date_planted,
            'rpta_unit_value' => $request->rpta_unit_value,
            'rpta_market_value' =>$request->rpta_market_value,
            'rpta_taxable' => ($request->rpta_taxable == null)?0:1,
            'rpta_registered_by' => \Auth::user()->id,
            'pt_ptrees_description' => $request->pt_ptrees_description,
            'pc_class_description' => $request->pc_class_description,
            'ps_subclass_desc'   => $request->ps_subclass_desc,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            if($this->_rptproperty->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
            $rptPropertyDetails = RptProperty::find($request->property_id);
            /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                return response()->json([
                    'status'=>'error',
                    'msg'=>'You can not update cancelled Tax Declaration!'
                ]);
            }*/
            unset($dataToSave['pt_ptrees_description']);
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            $dataToSave['rpta_modified_by'] = \Auth::user()->id;
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptproperty->updatePlantsTreesFactors($request->id,$dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id == null){
            if($this->_rptproperty->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
            $rptPropertyDetails = RptProperty::find($request->property_id);
            /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                return response()->json([
                    'status'=>'error',
                    'msg'=>'You can not update cancelled Tax Declaration!'
                ]);
            }*/
            unset($dataToSave['pt_ptrees_description']);
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            //dd($dataToSave);
            $this->_rptproperty->addPlantsTreesFactors($dataToSave);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else{

            $plantTreeAppraisal = $request->session()->get('landAppraisals.'.$request->land_appraisal_session_id);
           // dd($plantTreeAppraisal);
            $dataToSave['id']   = null;
            $dataToSave['dataSource']   = 'session';
            $savesPlantsTreeSessionData = (isset($plantTreeAppraisal->plantsTreeApraisals))?$plantTreeAppraisal->plantsTreeApraisals:[];
            $savesPlantsTreeSessionData[] = (object)$dataToSave;
            if($request->has('session_id') && $request->session_id != ''){
                $plantTreeAppraisal->plantsTreeApraisals[$request->session_id] = (object)$dataToSave;
                //dd($plantTreeAppraisal);
                $request->session()->put('landAppraisals.'.$request->land_appraisal_session_id, $plantTreeAppraisal);
            }else{
                $plantTreeAppraisal->plantsTreeApraisals = $savesPlantsTreeSessionData;
                //dd($plantTreeAppraisal);
                $request->session()->put('landAppraisals.'.$request->land_appraisal_session_id, $plantTreeAppraisal);
            }
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }
        $this->calculateAdjustMarketTreesValue($dataToSave['rpa_code'],$request->land_appraisal_session_id);
        //$this->_rptproperty->addPlantsTreesFactors($dataToSave);
        return response()->json($response);  
    }

    public function destroy($id)
    {
        
            $BploApplication = BploApplication::find($id);
            if($BploApplication->generated_by == \Auth::user()->id){
                $BploApplication->delete();
                return redirect()->route('bploapplication.index')->with('success', __('PSIC class successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }

    public function getprofiles(Request $request){
         $id= $request->input('pid');  $apptype = '1';
         $data = $this->_bploApplication->getprogiledata($id);
         echo json_encode($data);
    }

    public function getTradedropdown(Request $request){
        $id= $request->input('pid'); 
        $data = $this->_bploApplication->getappidsdata($id); $htmloption ="";
        if(!empty($data[0]->applicantids)){ 
         if (strpos($data[0]->applicantids, ',') !== false)
            {
                $ids =explode(',',$data[0]->applicantids); $getrequirements="";
                $htmloption ="<option value=''>Please Select</option>";
                foreach ($ids as $key => $value) {
                     $gettradename  = $this->_bploApplication->getTradename($value);
                     $htmloption .='<option value="'.$gettradename[0]->tradename.'">'.$gettradename[0]->tradename.'</option>';
                }
            }
            else
            {
                $gettradename  = $this->_bploApplication->getTradename($data[0]->applicantids);
                $htmloption ="<option value=''>Please Select</option>";
                $htmloption .='<option value="'.$gettradename[0]->tradename.'">'.$gettradename[0]->tradename.'</option>';
            }
        }
        echo $htmloption;
    }

    public function getBarangyaDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bploApplication->getBarangyaDetails($id);
        echo json_encode($data);
    }
    public function grosssaleReceipt(Request $request){
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
             $mpdf->debug = true;
             $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/grossalereceipt.html'));
            $logo = url('/assets/images/logo.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $mpdf->WriteHTML($html);
            $applicantname = "grosssaleReceipt.pdf";
            $folder =  public_path().'/uploads/grosssalereceipt/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/grosssalereceipt/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/grosssalereceipt/' . $applicantname);
    }

    public function getRequirementsofNature(Request $request){
           $id= $request->input('id');  $prereq = $request->input('prereq');  
           if(!empty($prereq)){
                if (strpos($prereq, ',') !== false)
                    {
                        $prereq =explode(',',$prereq); 
                        
                    }
                    else
                    {
                        $prereq = array('0'=>$prereq);
                    }
          
           }
           else{ $prereq = array(); }
           if (strpos($id, ',') !== false)
            {
                $ids =explode(',',$id); $getrequirements="";
                
                $getrequirements = $this->_bploApplication->getRequirementsNaturearray($ids,$prereq);
            }
            else
            {
                $getrequirements  = $this->_bploApplication->getRequirementsNature($id,$prereq);
            }
          
           $html = "";
           foreach ($getrequirements as $key => $value) {
               $html .="<div class='row removerequirementdata pt10'>
                                        <div class='col-lg-4 col-md-4 col-sm-4'>
                                            <div class='form-group'>
                                            <input id='bplo_requirement_id' name='bplo_requirement_id[]' type='hidden' value='".$value->id."'>
                                            <input id='bplo_app_type' name='bplo_app_type[]' type='hidden' value='".$value->apptype_id."'>
                                                <div class='form-icon-user'>
                                                     <select class='form-control codeabbrevation' id='bplo_code_abbreviation' required='required' name='bplo_code_abbreviation[]' fdprocessedid='qugyzs'><option value='".$value->id."'>".$value->req_code_abbreviation."-".$value->req_description."</option></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-lg-2 col-md-2 col-sm-2'>
                                            <div class='d-flex radio-check'><br>
                                                <div class='form-check form-check-inline form-group'>
                                                    <input id='Completed0' class='form-check-input bariscompleted code' name='0_bar_is_complied' type='checkbox' value='1'>
                                                    <label for='Completed0' class='form-label'>Completed</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class='col-lg-2 col-md-2 col-sm-2'>
                                            <div class='form-group'>
                                                <div class='form-icon-user'>
                                                    <input class='form-control' required='required' name='bar_date_sumitted[]' type='date' value=''>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='col-lg-3 col-md-3 col-sm-3'>
                                            <div class='form-group'>
                                                <div class='form-icon-user'>
                                                    <input class='form-control' required='required' name='bar_remarks[]' type='text' value='' fdprocessedid='3ww4c'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-lg-1 col-md-1 col-sm-1'>
                                         <input type='button' name='btn_cancel' class='btn btn-success btn_cancel_requirement' required='required' cid='' value='Delete' style='padding: 0.4rem 1rem !important;'>
                                        </div>
                                    </div>";
           }
           echo $html;
    }


    public function deletePlantTreeAppraisal(Request $request){
        
            $id = $request->input('id');
            if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisalData = session()->get('landAppraisals.'.$request->land_appraisal_id);
            if(isset($landAppraisalData->plantsTreeApraisals) && !empty($landAppraisalData->plantsTreeApraisals)){
                unset($landAppraisalData->plantsTreeApraisals[$request->sessionId]);
                $this->calculateAdjustMarketTreesValue(0,$request->land_appraisal_id);
                return response()->json(['status' => __('success'), 'msg' => 'Plants/Trees Appraisal delete successfully!']);
            }else{
                return response()->json(['status' => __('error'), 'msg' => 'Plants/Trees Appraisal could not be deleted!']);

            }   
            }else{
                $rptPlantTreeAppraisal = RptPlantTreesAppraisal::find($id);
                if($rptPlantTreeAppraisal != null){
                    if($this->_rptproperty->checkToVerifyPsw($rptPlantTreeAppraisal->rp_code)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
                    if($rptPlantTreeAppraisal->rpta_registered_by == \Auth::user()->id){
                    try {
                        $rpa_code = $rptPlantTreeAppraisal->rpa_code;
                        $rptPlantTreeAppraisal->delete();
                        $this->calculateAdjustMarketTreesValue($rpa_code);
                        return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', __($e->getMessage()));
                    }
                }else{
                    return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);
                }

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
        }
           
            
    }

    public function deleteLandAppraisal(Request $request){
        
            $id = $request->input('id');
            if($request->has('sessionId') && $request->sessionId != ''){
                //dd($request->session()->get('landAppraisals'));
               $request->session()->forget('landAppraisals.'.$request->sessionId);
               return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
            }else{
                $rptPlantTreeAppraisal = RptPropertyAppraisal::find($id);
                if($this->_rptproperty->checkToVerifyPsw($rptPlantTreeAppraisal->rp_code)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
            if($rptPlantTreeAppraisal != null){
                try {
                    DB::table('rpt_property_appraisals')->where('id',$id)->delete();
                    $this->_rptproperty->updateAccountReceiaveableDetails($rptPlantTreeAppraisal->rp_code);
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            }
    }

    public function deletePreviousOwnerTd(Request $request){
            $id = $request->input('id');
            $rptProperty = RptProperty::find($id);
            if($rptProperty != null && $rptProperty->pk_is_active == 9){
                try {
                    RptProperty::where('id',$id)->delete();
                    RptPropertyHistory::where('id',$request->historyid)->delete();
                    return response()->json(['status' => __('success'), 'msg' => 'Property deleted successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }

            }else{
                return response()->json(['status' => __('error'), 'msg' => 'You are not authorised to delete this record']);

            }
            
    }

    public function landAppraisalFactorsform(Request $request){
        $sessionId = '';
        $landAppraisal = [];
        $propertyCode = [];
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptPropertyAppraisal::find($request->id);
        }
        if($request->has('sessionid') && $request->sessionid != ''){
            $landAppraisal = $request->session()->get('landAppraisals.'.$request->sessionid);
            $sessionId = $request->sessionid;
        }
        if($request->has('property_id') && $request->property_id != 0){
            $propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
        }
        //dd($landAppraisal);
        $view = view('rptproperty.ajax.landappraisaladjustmentfactor',compact('sessionId','landAppraisal','propertyCode'))->render();
        echo $view;
    }

    public function showLandAppraisalForm(Request $request){
        $arrPropertyClasses = $this->arrPropClasses;
        $arrLandStrippingCodes = $this->arrStripingCodes;
        $landAppraisal = [];
        $arrSubClassesList = [];
        $arrActualUsesCodes = [];
        $sessionId = '';
        $propertyCode = [];
        $landUnitMeaure = config('constants.lav_unit_measure');
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptPropertyAppraisal::find($request->id);
            $arrSubClassesListColl = $this->_rptproperty->getSubClassesList($landAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_code','id')->toArray();
            //dd($arrSubClassesList);
            $arrActualUsesCodeColl = $this->_rptproperty->getActualUsesList($landAppraisal->pc_class_code);
            $arrActualUsesCodes = $arrActualUsesCodeColl->pluck('pau_actual_use_code','id')->toArray();
            $rptProprtyDetails = RptProperty::find($landAppraisal->rp_code);
            //dd($rptProprtyDetails);
            $request->request->add([
                'propertyKind' => $rptProprtyDetails->pk_id,
                'propertyClass' => $landAppraisal->pc_class_code,
                'propertyActualUseCode' => $landAppraisal->pau_actual_use_code,
                'propertyRevisionYear' => $rptProprtyDetails->rvy_revision_year_id

            ]);
            $arrassessementLevel = $this->_rptproperty->getAssessementLevel($request);
            
            $landAppraisal->al_minimum_unit_value = (isset($arrassessementLevel->al_minimum_unit_value))?$arrassessementLevel->al_minimum_unit_value:'0.00';
            $landAppraisal->al_maximum_unit_value = (isset($arrassessementLevel->al_maximum_unit_value))?$arrassessementLevel->al_maximum_unit_value:'0.00';
            $landAppraisal->al_assessment_level_hidden = (isset($arrassessementLevel->al_assessment_level))?$arrassessementLevel->al_assessment_level:'0.00';
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisal = $request->session()->get('landAppraisals.'.$request->sessionId);
            $sessionId = $request->sessionId;
            $arrSubClassesListColl = $this->_rptproperty->getSubClassesList($landAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_desc','id')->toArray();

            $arrActualUsesCodeColl = $this->_rptproperty->getActualUsesList($landAppraisal->pc_class_code);
            $arrActualUsesCodes = $arrActualUsesCodeColl->pluck('pau_actual_use_desc','id')->toArray();
        }
        if($request->has('property_id') && $request->property_id != 0){
            $propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
        }
        $view = view('rptproperty.ajax.addlandappraisal',compact('arrPropertyClasses','arrLandStrippingCodes','landAppraisal','arrSubClassesList','arrActualUsesCodes','sessionId','propertyCode','landUnitMeaure'))->render();

        echo $view;
    }

    public function storeLandAppraisal(Request $request){
        if($request->has('from') && $request->from == 'previousowner'){
            $rules = [
                'pc_class_code'=>'required',
                'pau_actual_use_code'=>'required', 
                'rpa_assessed_value' => 'required'
            ];
        }else{
            $rules = [
                'pc_class_code'=>'required',
                'ps_subclass_code'=>'required',
                'pau_actual_use_code'=>'required', 
                'lav_unit_value'=>'required', 
                'rpa_total_land_area'=>'required',
                'lav_unit_measure'=>'required', 

                'rpa_base_market_value'=>'required',
                'al_assessment_level' => 'required|numeric',
                'rpa_adjusted_market_value' => 'required',
                'rpa_assessed_value' => 'required'
            ];
        }
        $validator = \Validator::make(
            $request->all(),$rules,
            [
                'pc_class_code.required'=>'Required Field',
                'ps_subclass_code.required'=>'Required Field',
                'pau_actual_use_code.required'=>'Required Field', 
                'rpa_total_land_area.required'=>'Required Field',
                'lav_unit_measure.required'=>'Required Field', 
                'lav_unit_value.required'=>'Not Approved Yet', 
                'rpa_base_market_value.required'=>'Required Field',
                'al_assessment_level.required' => 'Not Approved Yet',
                'al_assessment_level.numeric' => 'Invalid value',
                'al_assessment_level.min' => 'The assessment level must be greater than 0.00',
                'rpa_adjusted_market_value.required' => 'Required Field',
                'rpa_assessed_value.required' => 'Required Field',
                'rpa_assessed_value.gt' => 'Should be grater than 0'
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
            'pc_class_code' => $request->pc_class_code,
            'pc_class_description' => $request->pc_class_description,
            'ps_subclass_code' => $request->ps_subclass_code,
            'ps_subclass_desc' => $request->ps_subclass_desc,
            'pau_actual_use_code' => $request->pau_actual_use_code,
            'pau_actual_use_desc' => $request->pau_actual_use_desc,
            'land_stripping_id' => $request->land_stripping_id,
            'rls_code' =>$request->rls_code,
            'lav_strip_unit_value' =>$request->lav_strip_unit_value,
            'rls_percent' => $request->rls_percent,
            'rpa_total_land_area' =>$request->rpa_total_land_area,
            'lav_unit_measure' => ($request->lav_unit_measure != '')?array_flip(config('constants.lav_unit_measure'))[$request->lav_unit_measure]:'',
            'lav_unit_value' => $request->lav_unit_value,
            'rpa_base_market_value' => $request->rpa_base_market_value,
            'al_assessment_level'   => $request->al_assessment_level,
            'al_minimum_unit_value'   => $request->al_minimum_unit_value,
            'al_maximum_unit_value'   => $request->al_maximum_unit_value,
            'al_assessment_level_hidden'   => $request->al_assessment_level_hidden,
            'rpa_adjusted_market_value'   => $request->rpa_adjusted_market_value,
            'rpa_assessed_value'   => $request->rpa_assessed_value,
            'rpa_registered_by' => \Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        //dd($dataToSave);
        if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id != null){
            if($this->_rptproperty->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
                session()->forget('verifyPswLand');
            $rptPropertyDetails = RptProperty::find($request->property_id);
            /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                return response()->json([
                    'status'=>'error',
                    'msg'=>'You can not update cancelled Tax Declaration!'
                ]);
            }*/
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            unset($dataToSave['pau_actual_use_desc']);
            unset($dataToSave['land_stripping_id']);
            unset($dataToSave['al_minimum_unit_value']);
            unset($dataToSave['al_maximum_unit_value']);
            unset($dataToSave['al_assessment_level_hidden']);
            $dataToSave['rpa_modified_by'] = \Auth::user()->id;
            $dataToSave['updated_at'] = date('Y-m-d H:i:s');
            $this->_rptproperty->updateLandAppraisalDetail($request->id,$dataToSave);
            $this->_rptproperty->generateTaxDeclarationAform($request->property_id);
            $this->_rptproperty->updateAccountReceiaveableDetails($request->property_id);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else if($request->has('property_id') && $request->property_id != null && $request->has('id') && $request->id == null){
            if($this->_rptproperty->checkToVerifyPsw($request->property_id)){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
            session()->forget('verifyPswLand');    
            $rptPropertyDetails = RptProperty::find($request->property_id);
            /*if($rptPropertyDetails != null && $rptPropertyDetails->pk_is_active == 0){
                return response()->json([
                    'status'=>'error',
                    'msg'=>'You can not update cancelled Tax Declaration!'
                ]);
            }*/
            $savedProperty = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            unset($dataToSave['pc_class_description']);
            unset($dataToSave['ps_subclass_desc']);
            unset($dataToSave['pau_actual_use_desc']);
            unset($dataToSave['land_stripping_id']);
            unset($dataToSave['al_minimum_unit_value']);
            unset($dataToSave['al_maximum_unit_value']);
            unset($dataToSave['al_assessment_level_hidden']);
            $dataToSave['pk_code']           = $savedProperty->pk_code;
            $dataToSave['rp_property_code']  = $savedProperty->rp_property_code;
            $dataToSave['rvy_revision_year'] = $savedProperty->rvy_revision_year;
            $dataToSave['rvy_revision_code'] = $savedProperty->rvy_revision_code;
            //dd($dataToSave);
            $this->_rptproperty->addLandAppraisalDetail($dataToSave);
            $this->_rptproperty->generateTaxDeclarationAform($request->property_id);
            $this->_rptproperty->updateAccountReceiaveableDetails($request->property_id);
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }else{
            $dataToSave['id']   = null;
            $dataToSave['dataSource']   = 'session';
            $dataToSave['plantsTreeApraisals'] = [];
            $savedLandApprSessionData = $request->session()->get('landAppraisals');

            $dataToSave['rpa_adjusted_plant_tree_value']=0.00;
            $dataToSave['rpa_adjusted_total_planttree_market_value']=0.00;

            $savedLandApprSessionData[] = (object)$dataToSave;
            if($request->has('session_id') && $request->session_id != ''){
                $getPlantsTreesAdjustmentFactor = $request->session()->get('landAppraisals.'.$request->session_id);
                $dataToSave['plantsTreeApraisals'] = $getPlantsTreesAdjustmentFactor->plantsTreeApraisals;
                $dataToSave['rpa_adjusted_plant_tree_value'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjusted_plant_tree_value))?$getPlantsTreesAdjustmentFactor->rpa_adjusted_plant_tree_value:0;
                $dataToSave['rpa_adjusted_total_planttree_market_value'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjusted_total_planttree_market_value))?$getPlantsTreesAdjustmentFactor->rpa_adjusted_total_planttree_market_value:0;
                $dataToSave['rpa_adjustment_factor_a'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_a))?$getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_a:0;
                $dataToSave['rpa_adjustment_factor_b'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_b))?$getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_b:0;
                $dataToSave['rpa_adjustment_factor_c'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_c))?$getPlantsTreesAdjustmentFactor->rpa_adjustment_factor_c:0;
                $dataToSave['rpa_adjustment_percent'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjustment_percent))?$getPlantsTreesAdjustmentFactor->rpa_adjustment_percent:100;
                $dataToSave['rpa_adjustment_value'] = (isset($getPlantsTreesAdjustmentFactor->rpa_adjustment_value))?$getPlantsTreesAdjustmentFactor->rpa_adjustment_value:100;
                $request->session()->put('landAppraisals.'.$request->session_id, (object)$dataToSave);
            }else{
                $request->session()->put('landAppraisals', $savedLandApprSessionData);
            }
            $response = ['status' => 'success','msg' => 'Data Inserted successfully!'];
        }
        $this->calculateAdjustMarketTreesValue($request->id,$request->session_id);
        if($request->ajax()){
            return response()->json($response);
        }else{
            return $response;
        }
    }


    public function storePlantsAdjustmentFactor(Request $request){
        $arrPropertyClasses = $this->arrPropClasses;
        $arrPlantTreeCode   = $this->arrPlantTreesCode;
        $arrSubClassesList  = [];
        $propertyCode       = [];
        $sessionId = '';
        $landAppraisalSessionId = $request->landAppSessionId;
        $ladnAppraisalId      = $request->landAppraisalId;
        $rptPlantTreeAppraisal = (object)[];
        if($request->has('id') && $request->id != ''){
            $rptPlantTreeAppraisal = RptPlantTreesAppraisal::find($request->id);
            //dd($rptPlantTreeAppraisal);
            $arrSubClassesListColl = $this->_rptproperty->getSubClassesList($rptPlantTreeAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_desc','id')->toArray();
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $rptPlantTreeAppraisal = $request->session()->get('landAppraisals.'.$request->landAppSessionId);
            $rptPlantTreeAppraisal = $rptPlantTreeAppraisal->plantsTreeApraisals[$request->sessionId];
            //dd($rptPlantTreeAppraisal);
            $sessionId = $request->sessionId;

            $arrSubClassesListColl = $this->_rptproperty->getSubClassesList($rptPlantTreeAppraisal->pc_class_code);
            $arrSubClassesList = $arrSubClassesListColl->pluck('ps_subclass_desc','id')->toArray();
        }
        if($request->has('property_id') && $request->property_id != 0){
            $propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
        }
        $view = view('rptproperty.ajax.addplantstreesadjustmentfactor',compact('arrPropertyClasses','arrPlantTreeCode','rptPlantTreeAppraisal','arrSubClassesList','propertyCode','sessionId','landAppraisalSessionId','ladnAppraisalId'))->render();

        echo $view;
    }

    public function Approve(Request $request){
         $annotation = "";
         $arrUpdateCodes = $this->arrUpdateCodes;
         $ucCode      = $request->updatecode;
         $directCancelUcCOdes = $this->arrUpdateCodesDirectCancel;
         $approvedata = [];
         $arrRevisionYears = array(); $Oldtaxdeclarationno =""; $duptaxdeclaration ="";
         $arrUpdateCodes = $this->arrUpdateCodes;
         //dd($arrUpdateCodes);
         $history = [];
         $id = ($request->has('id'))?$request->id:'';
         $propertyDetails = [];
         foreach ($this->_rptproperty->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->rpo_first_name.' '.$val->rpo_custom_last_name;
         }
        $profile = $this->arrprofile;
        if($request->has('id') && $request->id != '0'){
            $approvelFormData = $this->_rptproperty->getApprovalFormDetails($request->id);
            $propDetails = $this->_rptproperty->find($approvelFormData->rp_code);
            $history = [];
            //dd($approvelFormData);
            if(isset($approvelFormData)){
                $propertyDetails  = RptProperty::find($approvelFormData->rp_code);
                if($approvelFormData != null){
                    //->orderBy('rp_tax_declaration_no', 'DESC')-
                    $history          = $this->_propertyHistory->with([
                        'activeProp.revisionYearDetails',
                        'cancelProp.revisionYearDetails',
                        'activeProp.barangay',
                        'cancelProp.barangay',
                        'cancelProp.propertyOwner',
                        'cancelProp.landAppraisals.actualUses'
                    ])
                    ->orderBy('id','DESC');
                    if($propDetails->rp_property_code_new > 0){
                        $history->where('rp_property_code',$propDetails->rp_property_code_new);
                   }else{
                        $history->where('rp_property_code',$propDetails->rp_property_code);
                   }
                   $history = $history->get();
                    if($request->updatecode ==config('constants.update_codes_land.DUP')){
                        $oldPropertyData = RptProperty::find($propertyDetails->created_against);
                        if(isset($oldPropertyData)){
                            $Oldtaxdeclarationno = $oldPropertyData->rp_tax_declaration_no;  
                        }
                     
                   }
                   if($request->updatecode ==config('constants.update_codes_land.DC')){
                        $duptaxdeclarationdata = $this->_rptproperty->getTaxDeclarationnumofnewtd($propertyDetails->rp_property_code);
                        if(isset($duptaxdeclarationdata)){
                            $duptaxdeclaration = $duptaxdeclarationdata->rp_tax_declaration_no;
                        }
                   }
                }
            }
            $annotationData   = DB::table('rpt_property_annotations')->select(DB::raw('GROUP_CONCAT(rpa_annotation_desc SEPARATOR"; ") as annotation'))->where('rp_code',$approvelFormData->rp_code)->first();
            $annotation = (isset($annotationData->annotation))?'"'.$annotationData->annotation.'"':'';
            
        }else{
            $approvelFormData = $request->session()->get('approvalFormData');
            $annotationData   = (session()->has('propertyAnnotationForBuilding'))?session()->get('propertyAnnotationForBuilding'):'';
            if(!empty($annotationData)){
                $annoCollection = collect($annotationData)->pluck('rpa_annotation_desc')->toArray();
                $annotation     = '"'.implode("; ",$annoCollection).'"';
                
            }
        }
        //dd($directCancelUcCOdes);
       
        $appraisers  = $this->arremployees;
		
        $allTds     = $this->_rptproperty->getApprovalFormTds('L',$request->id);

        return view('rptproperty.approval',compact('arrRevisionYears','approvedata','appraisers','arrUpdateCodes','approvelFormData','ucCode','profile','arrUpdateCodes','allTds','history','id','propertyDetails','Oldtaxdeclarationno','duptaxdeclaration','directCancelUcCOdes','annotation'));
    }

    public function getPlantsTreesAdjustmentFactor(Request $request){
        $id        = $request->input('id');
        $sessionid = $request->input('id');
        if($id != ''){
            $plantsTreeApprasals = $this->_rptproperty->getPalntsTreesAppraisalDetails($id);
        }else{
            $landTreeAppraisal = $request->session()->get('landAppraisals.'.$request->sessionId);
            $plantsTreeApprasals = $landTreeAppraisal->plantsTreeApraisals;
            //dd($plantsTreeApprasals);
        }

        $view = view('rptproperty.ajax.plantstreesadjustmentfactor',compact('plantsTreeApprasals'))->render();
        echo $view;
    }
    public function getLandAppraisal(Request $request){
        $sessionData = collect($request->session()->get('landAppraisals'));
        $id = $request->input('id');
        $landApprasals = $this->_rptproperty->getLandAppraisalDetals($id);
        if($id == 0){
            $landApprasals = $sessionData;
        }
        $view = view('rptproperty.ajax.landappraisallisting',compact('landApprasals'))->render();
        echo $view;
    }

    public function loadAssessementSummary(Request $request){
        $sessionData = collect($request->session()->get('landAppraisals'));
        $id = $request->input('id');
        $propDetails = DB::table('rpt_properties')->select('rp_assessed_value')->where('id',$id)->first();
        $landApprasals = RptPropertyAppraisal::where('rp_code',$id)
                              ->addSelect([
                                'plantsTreeTotal' => RptPlantTreesAppraisal::select(DB::raw("SUM(rpta_market_value) AS plantsTreeTotal"))
                                       ->whereColumn('rpa_code', 'rpt_property_appraisals.id')
                              ])
                              ->get();

        if($id == 0){
            $landApprasals = $sessionData;
        }

        //dd($landApprasals[0]->pau_actual_use_desc);
        $view = view('rptproperty.ajax.assessementsummary',compact('landApprasals','propDetails'))->render();
        echo $view;
    }

    public function relatedBuildingsummary(Request $request){
        $id = $request->input('id');
        $data=$this->_rptproperty->getRelatedBuiMachineList($request);
        //dd($data);
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $arr[$i]['no']=$count;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $taxpayer_name = wordwrap($row->customername, 30, "<br />\n");
            $arr[$i]['taxpayer_name']="<div class='showLess'>".$taxpayer_name."</div>";
            $arr[$i]['kind']=$row->pk_description;
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $arr[$i]['market_value']=Helper::money_format($row->marketValue);
            $arr[$i]['assessment_level']=Helper::decimal_format($row->assessementLevel).' %';
            $arr[$i]['assessed_value']=Helper::money_format($row->assessedValue);;
            $arr[$i]['pk_is_active'] = ($row->pk_is_active == 1 ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>'); 
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

    public function PrintCertificate(Request $request){
           $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/certoflandholdingprint.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            // $html = str_replace('{{OWNERNAME}}',$data->p_complete_name_v1, $html);
            // $html = str_replace('{{BUSSINESS}}',$data->ba_business_name, $html);
            // $html = str_replace('{{BARANGAYNAME}}',$data->brgy_name, $html);
            // $issueddate = date("d, F Y",strtotime($data->ebac_issuance_date));
            // $html = str_replace('{{DATE}}',$issueddate, $html);
            // $html = str_replace('{{SIGN}}',$sign, $html);
            // $html = str_replace('{{BGIMAGE}}',$bgimage, $html);
            $mpdf->WriteHTML($html);
            $filename = 'samplecustomer';
            //$filename = str_replace(' ','', $data->p_complete_name_v1);
            //$applicantname = date('ymdhis').$filename."certoflandholdingprint.pdf";
            $applicantname = $filename."certoflandholdingprint.pdf";
            $folder =  public_path().'/uploads/certoflandholdingprint/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/certoflandholdingprint/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/certoflandholdingprint/' . $applicantname);
    }
     public function PrintnolandCertificate(Request $request){
           $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/certofnolandholdingprint.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            // $html = str_replace('{{OWNERNAME}}',$data->p_complete_name_v1, $html);
            // $html = str_replace('{{BUSSINESS}}',$data->ba_business_name, $html);
            // $html = str_replace('{{BARANGAYNAME}}',$data->brgy_name, $html);
            // $issueddate = date("d, F Y",strtotime($data->ebac_issuance_date));
            // $html = str_replace('{{DATE}}',$issueddate, $html);
            // $html = str_replace('{{SIGN}}',$sign, $html);
            // $html = str_replace('{{BGIMAGE}}',$bgimage, $html);
            $mpdf->WriteHTML($html);
            $filename = 'samplecustomer';
            //$filename = str_replace(' ','', $data->p_complete_name_v1);
            //$applicantname = date('ymdhis').$filename."certoflandholdingprint.pdf";
            $applicantname = $filename."certoflandholdingprint.pdf";
            $folder =  public_path().'/uploads/certofnolandholdingprint/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/certoflandholdingprint/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/certofnolandholdingprint/' . $applicantname);
    }

    public function getTdsForAjaxSelectList(Request $request){
        $data = $this->_rptproperty->getTdsForAjaxSelectList($request);
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

    public function getBrgyForAjaxSelectList(Request $request){
        $data = $this->_muncipality->getBrgyForAjaxSelectList($request);
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

    public function bulkUpload(Request $request){
        $this->is_permitted($this->slugs, 'upload');
        $arrType   = array("1"=>"Land Tax Declaration","2"=>"Land Appraisals");
        return view('rptproperty.bulkUpload',compact('arrType'));
    }

    public function downloadlandTDTemplate(Request $request){
        $status = ($request->has('status'))?$request->status:1;
        $this->setData('',config('constants.update_codes_land.DC'));
        $arrHeading = $this->_rptproperty->setValueToCommonColumns($this->data,2,$status);
        if(empty($arrHeading)){
            echo "Barangay, District or Locality Is missing";exit;
        }
        $arrHeading = $this->_rptproperty->insertNewArrayItem($arrHeading,'uc_code','pk_is_active');
        $arrHeading['pk_is_active'] = $status;

        $arrClients=DB::table('clients')->select(DB::raw('CONCAT("[",id,"]","=>","[",full_name,"]") as client_name'))->where('is_active',1)->get()->toArray();
        $arrEmployees = [];
        $preOwnerRef = [];
        foreach ($this->_rptproperty->getHrEmplyees() as $val) {
              $arrEmployees[]='['.$val->id.']'.'=>'.'['.$val->fullname.']';
        }
        foreach ($this->_rptproperty->getpreviousOwnerRefrences(session()->get('landSelectedBrgy'),$this->activeRevisionYear->id,2) as $val) {
                $preOwnerRef[]='['.$val->id.']=>['.$val->rp_tax_declaration_no.']';
        }
        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        $contForEmployee = 0;
        foreach($arrClients As $key => $val){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                $val = (array)$val;
                if(array_key_exists($h_key,$val)){

                    $data[] = $val[$h_key];

                }else if($h_key == 'employee_name'){
    
                        $data[] = (isset($arrEmployees[$contForEmployee]))?$arrEmployees[$contForEmployee]:'';
                    
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
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection, WithEvents, WithMultipleSheets {
            protected $data;
            protected $rptpropObj;
            protected $rptpropMoObj;
            protected $mainData;
            public function __construct($data){
                $this->data = $data;
                $this->rptpropObj = new RptPropertyController;
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
                    $drop_column = 'R';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column."2")->getDataValidation();
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
                    $validation->setFormula1('=$AB$2:$AB$'.$row_count);
                    $valForClient = $validation;
                    /* For Client */

                    /* For Admin */
                    // set dropdown column
                    $drop_column_admin = 'S';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_admin."2")->getDataValidation();
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
                    $validation->setFormula1('=$AB$2:$AB$'.$row_count);
                    $valForAdmin = $validation;
                    /* For Admin */

                    /* For AppriasedBy */
                    // set dropdown column
                    $drop_column_ap_by = 'X';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_ap_by."2")->getDataValidation();
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
                    $validation->setFormula1('=$AC$2:$AC$'.count($this->rptpropObj->arremployees));
                    $valForAprBy = $validation;
                    /* For AppriasedBy */

                    /* For Recommended */
                    // set dropdown column
                    $drop_column_rec_by = 'Y';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_rec_by."2")->getDataValidation();
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
                    $validation->setFormula1('=$AC$2:$AC$'.count($this->rptpropObj->arremployees));
                    $valForRecBy = $validation;
                    /* For Recommended */

                    /* For Approved By */
                    // set dropdown column
                    $drop_column_app_by = 'Z';
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
                    $validation->setFormula1('=$AC$2:$AC$'.count($this->rptpropObj->arremployees));
                    $valForAprovedBy = $validation;
                    /* For Approved By */

                    /* For Previous Owner Reference */
                    // set dropdown column
                    $drop_column_pre_owner = 'AA';
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
                    $validation->setFormula1('=$AD$2:$AD$'.count($this->rptpropMoObj->getLandReferencesForMachine($this->mainData['brgy_code_id'],$this->mainData['rvy_revision_year_id'],3)));
                    $validationForPreOwner = $validation;
                    /* For Previous Owner Reference */

                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $valForClient);
                        $event->sheet->getCell("{$drop_column_admin}{$i}")->setDataValidation(clone $valForAdmin);
                        $event->sheet->getCell("{$drop_column_ap_by}{$i}")->setDataValidation(clone $valForAprBy);
                        $event->sheet->getCell("{$drop_column_rec_by}{$i}")->setDataValidation(clone $valForRecBy);
                        $event->sheet->getCell("{$drop_column_app_by}{$i}")->setDataValidation(clone $valForAprovedBy);
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
        return Excel::download($exportClass, 'land-tax-declaration.xlsx');
    }

    public function downloadLandAppraisalTemplate(){
        // Define the data to export
        $arrHeading = array('rp_code'=>'','pk_code'=>'L','lav_unit_value' => '','rpa_total_land_area'=>'', 'rpa_adjustment_factor_a'=>'', 'rpa_adjustment_factor_b'=>'', 'rpa_adjustment_factor_c'=>'', 'client_name' => '', 'rp_tax_declaration_no' => '', 'class_subclse_actualuses'=>'', 'unitvalue' => '');

         
        $arrBusn = $this->bulkUploadLATds;
        $landUnitValue = $this->bulkUploadLAUnitValues;
        $allArrayCount = [count($arrBusn),count($landUnitValue)];
        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        for($i=0; $i<max($allArrayCount); $i++){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                
                if($h_key == 'client_name'){
                    $data[] = (isset($arrBusn[$i]->client_name))?$arrBusn[$i]->client_name:'';
                }else if($h_key == 'rp_tax_declaration_no'){
                    $data[] = (isset($arrBusn[$i]->rp_tax_declaration_no))?$arrBusn[$i]->rp_tax_declaration_no:'';
                }else if($h_key == 'class_subclse_actualuses'){
                    $data[] = (isset($landUnitValue[$i]->class_subclse_actualuses))?$landUnitValue[$i]->class_subclse_actualuses:'';
                }else if($h_key == 'unitvalue'){
                    $data[] = (isset($landUnitValue[$i]->unitvalue))?$landUnitValue[$i]->unitvalue:'';
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
                $this->controllerObj = new RptPropertyController;
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
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1('=$I$2:$I$'.count($this->controllerObj->bulkUploadLATds));
                    /* For Tax Declaration */

                    /* For Unit Value */
                    // set dropdown column
                    $drop_column_uv = 'C';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_uv."2")->getDataValidation();
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
                    $validation->setFormula1('=$J$2:$J$'.count($this->controllerObj->bulkUploadLAUnitValues));
                    /* For Unit Value */

                     /* For Total Land Area */
                    // set dropdown column
                    $drop_column_la = 'D';
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
                    
                    /* For Unit Value */

                    /* For Total Land Area */
                    // set dropdown column
                    $drop_column_af1 = 'E';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_af1."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setOperator(DataValidation::OPERATOR_BETWEEN );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value Allowed');
                    
                    /* For Unit Value */

                    /* For Total Land Area */
                    // set dropdown column
                    $drop_column_af2 = 'F';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_af2."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value Allowed');
                    
                    /* For Unit Value */

                    /* For Total Land Area */
                    // set dropdown column
                    $drop_column_af3 = 'G';
                    //dd($options);
                    // set dropdown list for first data row
                    $validation = $event->sheet->getCell($drop_column_af3."2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_DECIMAL );
                    $validation->setErrorStyle(DataValidation::STYLE_STOP );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Only Decimal Value Allowed');
                    
                    /* For Unit Value */

                     $drop_column_total_percent = 'H';
                   
                    // clone validation to remaining rows
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                        $event->sheet->getCell("{$drop_column_uv}{$i}")->setDataValidation(clone $validation);
                        $event->sheet->getCell("{$drop_column_la}{$i}")->setDataValidation(clone $validation);
                        $event->sheet->getCell("{$drop_column_af1}{$i}")->setDataValidation(clone $validation);
                        $event->sheet->getCell("{$drop_column_af2}{$i}")->setDataValidation(clone $validation);
                        $event->sheet->getCell("{$drop_column_af3}{$i}")->setDataValidation(clone $validation);
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

    public function uploadBulkLandData(Request $request){
        $upload_type =  $request->input('upload_type');
        if($upload_type==1){
            return $this->uploadLandTaxDeclaration($request);
        }else if($upload_type==2){
            return $this->uploadLandAppraisals($request);
        }else if($upload_type==3){
            return $this->uploadMeasurePax($request);
        }
    }

    public function uploadLandAppraisals($request){
        $upload_type =  $request->input('upload_type');
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptproperty->fliterApraisalData($excelData[$i],$excelData[0]);
                    $response = $this->_rptproperty->checkAppraisalRequiredFields($newDataToSave);
                    //dd($response);
                    if($response['status']){
                        $dataToSave = $response['data'];
                        //dd($dataToSave);
                        try {
                            $lastinsertedId = $this->_rptproperty->addLandAppraisalDetail($dataToSave);
                           // $lastinsertedId = 67;
                            $property = DB::table('rpt_properties')->where('id',$dataToSave['rp_code'])->first();
                            $this->_rptproperty->calculateLAPpraisalAndUpdate($lastinsertedId,true);
                            $this->_rptproperty->generateTaxDeclarationAform($property->id);
                            $this->_rptproperty->updateAccountReceiaveableDetails($property->id);
                            $this->_rptproperty->syncAssedMarketValueToMainTable($property->id);
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

    public function uploadLandTaxDeclaration($request){
        $upload_type =  $request->input('upload_type');
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = $this->data;
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            $noOfRecordsExecuted = 0;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $newDataToSave = $this->_rptproperty->fliterData($excelData[$i],$excelData[0]);
                    $response = $this->_rptproperty->checkRequiredFields($newDataToSave);
                    $approvalData = $this->_rptproperty->createDataForApproval($newDataToSave);
                   
                    if($response['status']){
                        $dataToSave = $response['data'];
                        unset($dataToSave['rp_app_appraised_by']);
                        unset($dataToSave['rp_app_recommend_by']);
                        unset($dataToSave['rp_app_approved_by']);
                        unset($dataToSave['rvy_revision_year']);
                        if(isset($dataToSave['previous_owner_reference'])){
                            unset($dataToSave['previous_owner_reference']);
                        }
                        try {
                           // dd($dataToSave);
                            $lastinsertedId = $this->_rptproperty->addData($dataToSave);
                            $property = DB::table('rpt_properties')->where('id',$lastinsertedId)->first();
                            if($property->pk_is_active == 1){
                               $this->_rptproperty->generateTaxDeclarationAndPropertyCode($lastinsertedId);
                             }
                             if($property->pk_is_active == 9){
                               $this->_rptproperty->generateTaxDeclarationAndPropertyCode($lastinsertedId,true);
                             }
                            $approvalData['rp_code'] = $lastinsertedId;
                            $approvalData['rp_property_code'] = $property->rp_property_code;
                            $this->_rptproperty->addApprovalForm($approvalData);
                            if($property->pk_is_active == 1){
                                $this->_rptproperty->addDataInAccountReceivable($lastinsertedId);
                            }if($property->pk_is_active == 9){
                                $this->updatePropertyHistory($lastinsertedId,RptProperty::find($dataToSave['created_against']),true);
                                $this->_rptproperty->updateChain($lastinsertedId);
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

    public function calculateAdjustMarketTreesValue($rpa_code,$session_id=0){
        if($rpa_code>0 && empty($session_id)){
            $arr = DB::table('rpt_property_appraisals AS rpa')->where('rpa.id',$rpa_code)
                ->select(
                    'rpa_adjusted_market_value',
                    'al_assessment_level',
                    'rp_code',
                    'rpa_base_market_value',
                    DB::raw('COALESCE(rpa_adjustment_percent,0) as rpa_adjustment_percent'),
                    DB::raw('COALESCE(rpa_adjustment_value,0) as rpa_adjustment_value')
                )
                ->addSelect([
                    'plantsTreeTotal' => RptPlantTreesAppraisal::select(DB::raw("SUM(rpta_market_value) AS plantsTreeTotal"))
                   ->whereColumn('rpa_code', 'rpa.id')
                   ->where('rpa_code',$rpa_code)
                ])->first();

            if(isset($arr)){
                //dd($arr);
                $data=array();
                //$total = $arr->rpa_adjusted_market_value + $arr->plantsTreeTotal;
                $plantTreeValue = ($arr->plantsTreeTotal > 0)?$arr->plantsTreeTotal:0;
                $factorAdjustedValue = (isset($arr->rpa_adjustment_value))?$arr->rpa_adjustment_value:0;
                if($arr->rpa_adjustment_percent < 100){
                    $factorAdjustedValue = -1*$factorAdjustedValue;
                }
                $total = $arr->rpa_base_market_value + $plantTreeValue + $factorAdjustedValue;
                $finalTotal = ($total*$arr->al_assessment_level)/100;
                $data['rpa_adjusted_plant_tree_value']=$arr->plantsTreeTotal;
                $data['rpa_adjusted_total_planttree_market_value']=$total;
                $data['rpa_adjusted_market_value'] = $total;
                $data['rpa_assessed_value']=$finalTotal;
                $this->_rptproperty->updateLandAppraisalDetail($rpa_code,$data);
                $this->_rptproperty->syncAssedMarketValueToMainTable($arr->rp_code);
            }
        }else{
            $savedSessionData = session()->get('landAppraisals');
            if($session_id!=''){
                $savedSessionData = session()->get('landAppraisals.'.$session_id);
                $plantTrees       = $savedSessionData->plantsTreeApraisals;
                //dd($savedSessionData);
                if(!empty($plantTrees)){
                    $plantsTreeTotal = (collect($plantTrees)->sum('rpta_market_value'));
                    if($plantsTreeTotal>0){
                        $adjustedValue = (isset($savedSessionData->rpa_adjustment_value) && $savedSessionData->rpa_adjustment_value > 0)?$savedSessionData->rpa_adjustment_value:0;
                        if(isset($savedSessionData->rpa_adjustment_percent) && $savedSessionData->rpa_adjustment_percent < 100){
                            $adjustedValue = -1*$adjustedValue;
                        }
                        $total = $savedSessionData->rpa_base_market_value + $plantsTreeTotal + $adjustedValue;
                        $savedSessionData->rpa_adjusted_plant_tree_value=$plantsTreeTotal;
                       
                    }else{
                         $adjustedValue = (isset($savedSessionData->rpa_adjustment_value) && $savedSessionData->rpa_adjustment_value > 0)?$savedSessionData->rpa_adjustment_value:0;
                         if(isset($savedSessionData->rpa_adjustment_percent) && $savedSessionData->rpa_adjustment_percent < 100){
                            $adjustedValue = -1*$adjustedValue;
                        }
                        $total = $savedSessionData->rpa_base_market_value + $adjustedValue;
                        $savedSessionData->rpa_adjusted_plant_tree_value=0;
                    }
                }else{
                    $adjustedValue = (isset($savedSessionData->rpa_adjustment_value) && $savedSessionData->rpa_adjustment_value > 0)?$savedSessionData->rpa_adjustment_value:0;
                    if(isset($savedSessionData->rpa_adjustment_percent) && $savedSessionData->rpa_adjustment_percent < 100){
                            $adjustedValue = -1*$adjustedValue;
                        }
                    $total = $savedSessionData->rpa_base_market_value + $adjustedValue;
                    $savedSessionData->rpa_adjusted_plant_tree_value=0;
                }
                //dd($savedSessionData);
                $finalTotal = ($total*$savedSessionData->al_assessment_level)/100;
                $savedSessionData->rpa_adjusted_total_planttree_market_value=$total;
                $savedSessionData->rpa_assessed_value=$finalTotal;
                $savedSessionData->rpa_adjusted_market_value=$total;
                session()->put('landAppraisals.'.$session_id,(object)$savedSessionData);
                
                $savedSessionData = session()->get('landAppraisals.'.$session_id);
            }
        }
    }
}
