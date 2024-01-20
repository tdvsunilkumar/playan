<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploApplicationController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrBarangay = array(""=>"Please Select");
    public $arrprofile = array(""=>"Select Owner ");
    public $arrSubclasses = array(""=>"Please Select");
    public $yeararr = array(""=>"Select Year ");
    
    public function __construct(){
		$this->_bploApplication = new BploApplication();
        $this->_commonmodel = new CommonModelmaster();  

        $this->data = array('id'=>'','profile_id'=>'','ba_telephone_no'=>'','ba_fax_no'=>'','business_phone'=>'','business_tin'=>'','business_email'=>'','ba_business_name'=>'','ba_address_house_lot_no'=>'','ba_address_street_name'=>'','barangay_id'=>'','brgy_name'=>'','ba_building_is_owned'=>'','ba_building_total_area_occupied'=>'','ba_building_permit_no'=>'','ba_building_permit_issued_date'=>'','ba_building_certificate_occupancy_number'=>'','ba_building_info_date_updated'=>'','ba_building_assessed_value'=>'','ba_building_property_index_number'=>'','ba_type_id'=>'','ba_plate_is_issued'=>'','ba_plate_big_small'=>'','ba_no_of_personnel'=>'','ba_office_type'=>'','ba_registration_ctc_no'=>'','ba_registration_ctc_issued_date'=>'','ba_registration_ctc_place_of_issuance'=>'','ba_registration_ctc_amount_paid'=>'','ba_registration_sss_number'=>'','ba_registration_sss_date_issued'=>'','ba_locational_clearance_no'=>'','ba_locational_clearance_date_issued'=>'','ba_bureau_domestic_trade_no'=>'','ba_bureau_domestic_trade_date_issued'=>'','ba_sec_registration_no'=>'','ba_sec_registration_date_issued'=>'','ba_dti_no'=>'','ba_dti_date_issued'=>'','ba_taxable_owned_truck_wheeler_10above'=>'0','ba_taxable_owned_truck_wheeler_6above'=>'0','ba_taxable_owned_truck_wheeler_4above'=>'0','ba_date_started'=>date("Y-m-d"),'is_approved'=>'','ba_cover_year'=>date("Y"),'ba_business_account_no'=>'','ba_city_name'=>'','app_type_id'=>'');

        $this->data = array('id'=>'','profile_id'=>'','ba_telephone_no'=>'','ba_fax_no'=>'','business_phone'=>'','business_tin'=>'','business_email'=>'','ba_business_name'=>'','ba_address_house_lot_no'=>'','ba_address_street_name'=>'','barangay_id'=>'','brgy_name'=>'','ba_building_is_owned'=>'','ba_building_total_area_occupied'=>'','ba_building_permit_no'=>'','ba_building_permit_issued_date'=>'','ba_building_certificate_occupancy_number'=>'','ba_building_info_date_updated'=>'','ba_building_assessed_value'=>'','ba_building_property_index_number'=>'','ba_type_id'=>'','ba_plate_is_issued'=>'','ba_plate_big_small'=>'','ba_no_of_personnel'=>'','ba_office_type'=>'','ba_registration_ctc_no'=>'','ba_registration_ctc_issued_date'=>'','ba_registration_ctc_place_of_issuance'=>'','ba_registration_ctc_amount_paid'=>'','ba_registration_sss_number'=>'','ba_registration_sss_date_issued'=>'','ba_locational_clearance_no'=>'','ba_locational_clearance_date_issued'=>'','ba_bureau_domestic_trade_no'=>'','ba_bureau_domestic_trade_date_issued'=>'','ba_sec_registration_no'=>'','ba_sec_registration_date_issued'=>'','ba_dti_no'=>'','ba_dti_date_issued'=>'','ba_taxable_owned_truck_wheeler_10above'=>'0','ba_taxable_owned_truck_wheeler_6above'=>'0','ba_taxable_owned_truck_wheeler_4above'=>'0','ba_date_started'=>date("Y-m-d"),'is_approved'=>'','ba_cover_year'=>date("Y"),'ba_business_account_no'=>'','ba_city_name'=>'','app_type_id'=>'');

        
        foreach ($this->_bploApplication->getBarangay() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.' - '.$val->brgy_name;
        } 
        foreach ($this->_bploApplication->getSubClasses() as $val) {
            $this->arrSubclasses[$val->id]=$val->subclass_description;
        } 
        
    }
    public function index(Request $request)
    {   
        $getbarangays = $this->arrBarangay; $yeararr= $this->yeararr;
        $year ='2020';
        for($i=0;$i<=10;$i++){
            $yeararr[$year] =$year; 
            $year = $year +1;
        }
         return view('bploapplication.index',compact('getbarangays','yeararr'));
        
    }
    public function getList(Request $request){
        $data=$this->_bploApplication->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['ba_business_name']=$row->ba_business_name;
            $arr[$i]['business_address']=$row->ba_address_house_lot_no.','.$row->ba_address_street_name;
            $arr[$i]['p_complete_name_v1']=$row->p_complete_name_v1;
            $arr[$i]['created_at']=date("M d, Y",strtotime($row->ba_date_started));
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploapplication/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div><div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Payment"  data-title="Print Payment" class="mx-3 btn btn-sm print align-items-center" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a>
                 </div>';

            if($row->app_type_id=='5'){
                $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" title="Renewed" disabled style="cursor:not-allowed;">
                        <i class="ti ti-lock-off text-white"></i>
                    </a>
                </div>';
            } else{
               $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploapplication/store?id='.$row->id).'&reneval=1" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Renew"  data-title="Renew">
                        <i class="ti-reload text-white"></i>
                    </a>
                </div>'; 
            }

            
			$i++;
		}
        
		$totalRecords=$data['data_cnt'];
		$json_data = array(
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $arr   // total data array
		);
		echo json_encode($json_data);
    }
    
    public function getBploRequirement(){
        $arrBusiness = array();
        // $i=0;
        // foreach ($this->_bploApplication->getBploRequirement() as $val) {
        //     $arrBusiness[$i]['bplo_requirement_id']=$val->id;
        //     $arrBusiness[$i]['bplo_req_description']=$val->req_description;
        //     $arrBusiness[$i]['bplo_code_abbreviation']=$val->req_code_abbreviation;
        //     $arrBusiness[$i]['bplo_app_type']=$val->apptype_id;
        //     $arrBusiness[$i]['bar_is_complied']='';
        //     $arrBusiness[$i]['bar_date_sumitted']='';
        //     $arrBusiness[$i]['bar_remarks']='';
        //     $i++;
        // }
        return $arrBusiness;
    }

    public function getPrevBploRequirement($id,$arrBusiness){
        $getAppReqDtls = $this->_bploApplication->getAppReqDtls($id);
        if(count($getAppReqDtls)>0){
            foreach($getAppReqDtls as $key => $val){
                $idx = 1;
                if($idx>=0){
                    $arrBusiness[$key]['id']=$getAppReqDtls[$key]->id;
                    $arrBusiness[$key]['bplo_req_description']=$getAppReqDtls[$key]->bplo_req_description;
                    $arrBusiness[$key]['bplo_code_abbreviation']=$getAppReqDtls[$key]->bplo_requirement_id;
                    $arrBusiness[$key]['bplo_app_type']=$getAppReqDtls[$key]->bplo_app_type;
                    $arrBusiness[$key]['bar_is_complied']=$getAppReqDtls[$key]->bar_is_complied;
                    $arrBusiness[$key]['bar_date_sumitted']=$getAppReqDtls[$key]->bar_date_sumitted;
                    $arrBusiness[$key]['bar_remarks']=$getAppReqDtls[$key]->bar_remarks;
                }
            }
        }
        //echo "<pre>"; print_r($arrBusiness); exit;
        return $arrBusiness;
    }

    public function getNatureDetails($arrJson=''){
        $arrNature= array();
        if(empty($arrJson)){
            $arrNature[0]['psic_subclass_id']='';
            $arrNature[0]['taxable_item_name']='';
            $arrNature[0]['taxable_item_qty']='';
            $arrNature[0]['capital_investment']='';
            $arrNature[0]['date_started']=date("Y-m-d");;
        }else{
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $arrNature[$key]['psic_subclass_id']=$val['psic_subclass_id'];
                $arrNature[$key]['taxable_item_name']=$val['taxable_item_name'];
                $arrNature[$key]['taxable_item_qty']=$val['taxable_item_qty'];
                $arrNature[$key]['capital_investment']=$val['capital_investment'];
                $arrNature[$key]['date_started']=$val['date_started'];
            }
        }
        return $arrNature;
    }

    

    public function store(Request $request){
        $data = (object)$this->data;  $apptypeid = "1"; $arrrequirement=array(); $tradearray =array();
        $arrBarangay = $this->arrBarangay;
        $arrSubclasses = $this->arrSubclasses;
        foreach ($this->_bploApplication->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->p_first_name.' '.$val->p_middle_name.' '.$val->p_family_name;
        } 
        
        $profile = $this->arrprofile;
        $arrBusiness = $this->getBploRequirement();
        $arrNature = $this->getNatureDetails();
        //echo "<pre>"; print_r($arrrequirement); exit;
        $isreneval = $request->input('reneval');
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploApplication::find($request->input('id'));
            $apptypeid = $data->app_type_id;
            $arrBusiness = $this->getPrevBploRequirement($data->id,$arrBusiness);
            $checkapplicantexist = $this->_bploApplication->getApplicantexists($data->profile_id,$data->ba_business_name);
            if(count($checkapplicantexist) > 0){
              //$data->app_type_id = '2'; 
              //$apptypeid ='2'; 
            }
            $arrNature = $this->getNatureDetails($data->nature_of_bussiness_json);

            $tradearray[$data->ba_business_name] =$data->ba_business_name;
            // Converted datetime to date
            $arrDates = array('ba_building_info_date_updated','ba_registration_ctc_issued_date','ba_locational_clearance_date_issued','ba_registration_sss_date_issued','ba_bureau_domestic_trade_date_issued');
            foreach($arrDates as $val){
                if(!empty($data->$val) && $data->$val!='0000-00-00 00:00:00'){
                    $data->$val = date("Y-m-d",strtotime($data->$val));
                }
            }
            
            }
            foreach ($this->_bploApplication->requirementcode($apptypeid) as $val) {
                $arrrequirement[$val->id]=$val->req_code_abbreviation."-".$val->req_description;
            }

            
            //echo "<pre>"; print_r($arrrequirement); exit; 
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $isreneval = $request->input('isreneval'); 
             $psic_subclass_id = $request->input('psic_subclass_id');
            
            if ($psic_subclass_id != array_unique($psic_subclass_id)) {
                // return redirect()->back()->with('error', 'Nature Of Bussiness Should not be dublicate.'); exit;
            } 
           
            if($isreneval =='1'){
                $renewalarray = array();
                $renewalarray['application_id'] =$request->input('id');
                $renewalarray['renewal_date'] =date('Y-m-d');
                $renewalarray['renewal_year'] =date('Y');
                $renewalarray['created_by'] =\Auth::user()->creatorId();
                $renewalarray['created_at'] = date('Y-m-d H:i:s');
                $this->_bploApplication->addAppRenewalData($renewalarray);
                $this->data['app_type_id']=5;
                $assessUptData = array('app_type'=>'2'); 
                $this->_bploApplication->updateAssesmentData($request->input('id'),$assessUptData);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploApplication->updateData($request->input('id'),$this->data);
                $success_msg = 'Application updated successfully.';
            }else{
                $this->data['is_active']=1;
                $this->data['app_type_id']=1;
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $request->id = $this->_bploApplication->addData($this->data);

                $accNo = str_pad($request->id, 6, '0', STR_PAD_LEFT);
                $arrupd = array();
                $arrupd['ba_business_account_no']=date("y").'-'.$accNo;
                $this->_bploApplication->updateData($request->id,$arrupd);
                $success_msg = 'Application added successfully.';
            }
            if($request->id>0){
                $this->addApplicationRequirementDetails($request);
                $this->addNatureOfBusiness($request);
            }
            return redirect()->route('bploapplication.index')->with('success', __($success_msg));
    	}
        $arrCity= array();
        foreach ($this->_bploApplication->getMunicipality() as $val) {
            $arrCity[$val->id]=$val->mun_no.' - '.$val->mun_desc;
        } 

        return view('bploapplication.create',compact('data','arrBarangay','arrNature','arrBusiness','profile','arrSubclasses','isreneval','arrCity','arrrequirement','apptypeid','tradearray'));

        
	}

    public function checkApptype(Request $request){
         $pid = $request->input('id');  $trade = $request->input('trade'); $apptypeid ='1'; 
        $checkapplicantexist = $this->_bploApplication->getApplicantexists($pid,$trade);
            if(count($checkapplicantexist) > 0){
              $apptypeid ='2'; 
            }
           $data['type'] = $apptypeid;  $htmloption ="<option value=''>Please Select</option>";
           foreach ($this->_bploApplication->requirementcode($apptypeid) as $val) {
                $htmloption .='<option value="'.$val->id.'">'.$val->req_description.'</option>';
            }
           $data['reqoption'] = $htmloption;  
         echo json_encode($data);    
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

    public function addApplicationRequirementDetails($request){
        //$this->_bploApplication->deleteApplicationRequirement($request->id);
        $bplo_code_abbreviation = $request->input('bplo_code_abbreviation');
        foreach ($bplo_code_abbreviation as $key => $value) {
            $arr = array();
            $arr['bplo_application_id']=$request->id;
            //echo "<pre>"; print_r($_POST); exit;
            $bploreqdata = $this->_bploApplication->getRequirementRow($request->input('bplo_code_abbreviation')[$key]);
            if(!empty($bploreqdata) > 0){
              $bplo_requirement_id = $bploreqdata->id; $bplo_code_abbreviation = $bploreqdata->req_code_abbreviation; 
              $bplo_req_description = $bploreqdata->req_description;
            }else{ $bplo_requirement_id = ''; $bplo_code_abbreviation =''; $bplo_req_description=''; }
            $arr['bplo_requirement_id']=$bplo_requirement_id;
            $arr['bplo_code_abbreviation']=$bplo_code_abbreviation;
            $arr['bplo_req_description']=$bplo_req_description;
            $arr['bplo_app_type']=($request->input('app_type_id')) ? $request->input('app_type_id'):'1';
            $arr['bar_is_complied']=(int)$request->input($key.'_bar_is_complied');
            $arr['bar_date_sumitted']=$request->input('bar_date_sumitted')[$key];
            $arr['bar_remarks']=$request->input('bar_remarks')[$key];
            $arr['created_by'] = \Auth::user()->creatorId();
            $arr['updated_by'] = \Auth::user()->creatorId();
            if(!empty($request->input('appreqrelid')[$key])){
                   $this->_bploApplication->updateApplicationRequirement($request->input('appreqrelid')[$key],$arr);
            }else{
               $this->_bploApplication->addApplicationRequirement($arr); 
            }
            
        }
    }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'class_code'=>'required|unique:psic_classes,class_code,'.$request->input('id'),
                // 'section_id'=>'required',
                // 'division_id'=>'required', 
                // 'group_id'=>'required', 
                // 'class_description'=>'required'
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function destroy($id)
    {
        $BploApplication = BploApplication::find($id);
        if($BploApplication->generated_by == \Auth::user()->creatorId()){
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

    public function deleteBploRequirement(Request $request){
        $id = $request->input("id");
        echo $result = $this->_bploApplication->deleteRequirementsBplo($id);
        echo "Success";
    }

}
