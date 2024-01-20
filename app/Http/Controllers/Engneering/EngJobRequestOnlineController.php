<?php

namespace App\Http\Controllers\Engneering;

use App\Http\Controllers\Controller;
use App\Models\Engneering\EngJobRequestOnline;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use \Mpdf\Mpdf as PDF;
use Carbon\Carbon;

class EngJobRequestOnlineController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrOwners = array(""=>"Please Select");
     public $arrlotOwner = array(""=>"Please Select");
     public $arrGetservices = array(""=>"Please Select");
     public $arrRequirements = array();
     public $arrApptype =  array(""=>"Please Select");
     public $GetMuncipalities = array(""=>"Please Select");
     public $arrTypeofOccupancy = array();
     public $arrbuildingScope = array(""=>"Please Select");
     public $arrsigndisplaytype = array();
     public $arrsignInstllationtype = array();
     public $hremployees = array(""=>"Please Select");
     public $arrPermitno = array(" "=>"Please Select");
     public function __construct(){
		$this->_engjobrequest= new EngJobRequestOnline(); 
        $this->_commonmodel = new CommonModelmaster();
        
        $this->datafirst = array('id'=>'','ejr_jobrequest_no'=>'','Applicationtype'=>'','client_id'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_mobile_no'=>'','tfoc_id'=>'','es_id'=>'');  
        $this->data = array('id'=>'','ejr_jobrequest_no'=>'','client_id'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_mobile_no'=>'','tfoc_id'=>'','es_id'=>'','application_no'=>'','Applicationtype'=>'','ejr_project_name'=>'','ebfd_floor_area'=>'','ejr_firstfloorarea'=>'','ejr_secondfloorarea'=>'','ejr_lotarea'=>'','ejr_perimeter'=>'','ejr_projectcost'=>'','ejr_date_paid'=>'','ejr_total_net_amount'=>'','ejr_surcharge_fee'=>'','ejr_totalfees'=>''); 

        $this->OrderPaymentdata = array('ejr_project_name'=>'','ebfd_floor_area'=>'','ejr_firstfloorarea'=>'','ejr_secondfloorarea'=>'','ejr_lotarea'=>'','ejr_perimeter'=>'','ejr_projectcost'=>'','ejr_totalfees'=>'');
        $this->slugs = 'engjobrequestonline'; 
        $this->appdata = array('id'=>'','ebpa_mun_no'=>'','ebpa_application_no'=>'','ebpa_permit_no'=>'','eba_id'=>'','ebpa_application_date'=>'','ebpa_issued_date'=>'','ebpa_owner_last_name'=>'','ebpa_owner_first_name'=>'','ebpa_owner_mid_name'=>'','ebpa_owner_suffix_name'=>'','ebpa_tax_acct_no'=>'','ebpa_form_of_own'=>'','ebpa_economic_act'=>'','ebpa_address_house_lot_no'=>'','ebpa_address_street_name'=>'','ebpa_address_subdivision'=>'','brgy_code'=>'','ebpa_location'=>'','ebs_id'=>'','ebpa_scope_remarks'=>'','no_of_units'=>'','ebot_id'=>'','ebost_id'=>'','ebpa_occ_other_remarks'=>'','ebpa_bldg_official_name'=>'');

        $this->engfeesdata = array('ebfd_bldg_est_cost' => '','ebfd_elec_est_cost'=>'','ebfd_plum_est_cost'=>'','ebfd_mech_est_cost'=>'','ebfd_other_est_cost'=>'','ebfd_total_est_cost'=>'','ebfd_equip_cost_1'=>'','ebfd_equip_cost_2'=>'','ebfd_equip_cost_3'=>'','ebfd_no_of_storey'=>'','ebfd_construction_date'=>'','ebfd_mats_const'=>'','ebfd_sign_category'=>'','ebfd_floor_area'=>'','ebfd_incharge_prc_reg_no'=>'','ebfd_sign_address_house_lot_no'=>'','ebfd_sign_address_street_name'=>'','ebfd_sign_address_subdivision'=>'','ebfd_sign_ptr_no'=>'','ebfd_incharge_ptr_no'=>'','ebfd_incharge_ptr_date_issued'=>'','ebfd_incharge_ptr_place_issued'=>'','ebfd_incharge_tan'=>'','ebfd_applicant_consultant_id'=>'','ebfd_applicant_date_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_consent_comtaxcert'=>'','ebfd_applicant_comtaxcert'=>'','ebpa_address_house_lotno'=>'','ebfd_applicant_date_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_consent_tctoct_no'=>'','ebfd_sign_prc_reg_no'=>'','ebfd_incharge_category'=>'','ebfd_incharge_consultant_id'=>'','ebfd_sign_consultant_id'=>'','ebfd_consent_id'=>'','ebfd_consent_comtaxcert'=>'','ebfd_incharge_address_house_lot_no'=>'');
        $this->EngAssessdata = array('ebaf_zoning_amount'=>'','ebaf_zoning_assessed_by'=>'','ebaf_zoning_or_no'=>'','ebaf_zoning_date_paid'=>'','ebaf_linegrade_amount'=>'','ebaf_linegrade_assessed_by'=>'','ebaf_linegrade_or_no'=>'','ebaf_linegrade_date_paid'=>'','ebaf_bldg_amount'=>'','ebaf_bldg_assessed_by'=>'','ebaf_bldg_or_no'=>'','ebaf_bldg_date_paid'=>'','ebaf_plum_amount'=>'','ebaf_plum_assessed_by'=>'','ebaf_plum_or_no'=>'','ebaf_plum_date_paid'=>'','ebaf_elec_amount'=>'','ebaf_elec_assessed_by'=>'','ebaf_elec_or_no'=>'','ebaf_elec_date_paid'=>'','ebaf_mech_amount'=>'','ebaf_mech_assessed_by'=>'','ebaf_mech_or_no'=>'','ebaf_mech_date_paid'=>'','ebaf_others_amount'=>'','ebaf_others_assessed_by'=>'','ebaf_others_or_no'=>'','ebaf_others_date_paid'=>'','ebaf_total_amount'=>'','ebaf_total_assessed_by'=>'','ebaf_total_or_no'=>'','ebaf_total_date_paid'=>'');

        
        
        foreach ($this->_engjobrequest->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->full_name;
         }
        foreach ($this->_engjobrequest->getRptOwners() as $val) {
             $this->arrlotOwner[$val->id]=$val->full_name;
         }
        foreach ($this->_engjobrequest->GetBuildingScopes() as $val) {
              $this->arrbuildingScope[$val->id]=$val->ebs_description;
        } 
        foreach ($this->_engjobrequest->GetSignDisplayTypes() as $val) {
               $this->arrsigndisplaytype[$val->id]=$val->esdt_description;
        }
        foreach ($this->_engjobrequest->GetSignInstallationTypes() as $val) {
               $this->arrsignInstllationtype[$val->id]=$val->esit_description;
        }
        foreach ($this->_engjobrequest->getServices() as $val) {
             $this->arrGetservices[$val->id]=$val->accdesc;
         } 
         foreach ($this->_engjobrequest->GetTypeofOccupancy() as $val) {
             $this->arrTypeofOccupancy[$val->id]=$val->ebot_description;
         } 
         foreach ($this->_engjobrequest->getAppType() as $val) {
             $this->arrApptype[$val->id]=$val->eba_description;
         } 
         foreach ($this->_engjobrequest->GetMuncipalities() as $val){
             $this->GetMuncipalities[$val->id]=$val->mun_desc;
         } 
         foreach ($this->_engjobrequest->gethremployess() as $val){
             $this->hremployees[$val->id]=$val->fullname;
         } 
    }
    
    public function index(Request $request)
    {  
		$barangay=array(""=>"Please select");
        $getmincipalityid = $this->_engjobrequest->getEngmunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_engjobrequest->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
            $to_date=Carbon::now()->format('Y-m-d');
            $from_date=Carbon::now()->format('Y-m-d');
           $this->is_permitted($this->slugs, 'read');
           return view('EngneeringOnline.engjobrequest.index',compact('barangay','to_date','from_date'));
    }

    public function getsuboccupancytype(Request $request){
        $id= $request->input('occupancyid');  $subid =$request->input('subid');
        $subtype = $this->_engjobrequest->subtypeoccupancy($id);
        $html='<select name="ebost_id" required  class="suboccupancydrop">';
        foreach ($subtype as $key => $value) {
            if($value->id == $subid){
                $html .='<option selected="selected" value='.$value->id.'>'.$value->ebost_description.'</option>';
            }else{
                $html .='<option value='.$value->id.'>'.$value->ebost_description.'</option>'; 
            }
        }
         $html .='</select><span class="validate-err" id="err_ebost_id"></span>';
         echo $html;
    }

    public function Declineapplication(Request $request){
    	$id=$request->input('appid');
    	$updatearray = array("is_approved"=>"2");
    	$this->_engjobrequest->updateData($id,$updatearray);
    }

    public function approve(Request $request)
    {  $id=$request->input('appid');
        $data=$this->_engjobrequest->approve($id);
        return response()->json([
            'data' =>$data
        ]);
    }

     public function syncreqtoremote(Request $request){
        $id=$request->input('id');
        $data=$this->_engjobrequest->syncreqtoremote($id);
        return response()->json([
            'data' =>$data
        ]);
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engjobrequest->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['jobreqno']="ONLINE".$row->ejr_jobrequest_no;
            $arr[$i]['ownername']=$row->full_name;
            $barngayName = "";
            $barngayAddress = $this->_commonmodel->getBarangayname($row->location_brgy_id); 
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['barangay']=$barngayName;
            $arr[$i]['services']=$row->eat_module_desc;
            $arr[$i]['generated']=$row->created_at;
            $appsereviceNo = date('Y').'-'.str_pad($row->id, 4, '0', STR_PAD_LEFT);
            $arr[$i]['appno']=$appsereviceNo;
            $arrPermitno = array(); $permitnumber ="";
            $startCarbon = Carbon::parse($row->created_at);
            $endCarbon = date('Y-m-d');
            $diff = $startCarbon->diff($endCarbon);
            if ($diff->days == 0) {
                $duration = "";
            } 
            elseif($diff->days == 1) {
                $duration = $diff->days . " Day";
            } else {
                $duration = $diff->days . " Days";
            }
            $arr[$i]['duration']=$duration; 
            $permitnumber = "";
            $arr[$i]['permtno']=$permitnumber;
            $arr[$i]['topno']="";  $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_engjobrequest->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_engjobrequest->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                  $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }

            
            $arr[$i]['amount']=number_format($row->ejr_totalfees,2);
           
            if($row->is_approved == '0'){
            	$status = '<span class="btn btn-info" style="padding: 0.1rem 0.5rem !important;">Pending</span>';
            }
            if($row->is_approved == '2'){
            	$status = '<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Declined</span>';
            }
            if($row->is_approved == '1'){
            	$status = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approved</span>';
            }
            $arr[$i]['is_active']=$status;
            $arr[$i]['date']=date('Y-m-d',strtotime($row->created_at));
            $arr[$i]['method']='Online';                 
            $arr[$i]['action']='
                <div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engjobrequestonline/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Engineering Job Request: Online Application">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>';
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
    public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_engjobrequest->getProfileDetails($id);
        foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }

    public function getRptClientDetails(Request $request){
        $id= $request->input('clientid');
        $data = $this->_engjobrequest->getOwnerClientDetails($id);
        echo json_encode($data); 
    }

    public function getApplicationType(Request $request){
           $id= $request->input('es_id');
           $data = $this->_engjobrequest->getapptypeUsingtfocid($id);
           if(count($data)>0){ echo $data[0]->eat_module_desc."#".$data[0]->tfoc_id;}
        
    }

    public function getApplicant(Request $request){
        $applid= $request->input('id');
        $data = $this->_engjobrequest->getTaxcertificatedetails($applid);
        echo json_encode($data);
    }

    public function getapplicantdetails($id){
         $data = $this->_engjobrequest->getTaxcertificatedetails($id);
         return $data;
    }

    public function getSignDetails(Request $request){
        $signid= $request->input('signcatid');
        $signcategory= $request->input('categoryid');  $datanew = array();
        if($signcategory =='1'){
            $data = $this->_engjobrequest->getEmployeeDetails($signid);

            $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
            $datanew['prcno'] = $data->emp_prc_no;
            $datanew['ptrno'] = $data->emp_ptr_no;
            $datanew['tinno'] = $data->tin_no;
            $datanew['issueddate'] = $data->emp_issue_date;
            $datanew['issuedplace'] = $data->emp_issue_at;
            $datanew['validity'] = $data->emp_prc_validity;
        }else{
           $data = $this->_engjobrequest->getExternalDetails($signid);
            $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
            $datanew['prcno'] = $data->prc_no;
            $datanew['ptrno'] = $data->ptr_no;
            $datanew['tinno'] = $data->tin_no;
            $datanew['issueddate'] = $data->ptr_date_issued;
            $datanew['issuedplace'] = ""; 
            $datanew['validity'] = $data->prc_validity;
        }
        $datanew = (object)$datanew;
        echo json_encode($datanew);
    }

    public function getRequirements(Request $request){
           $tfocid= $request->input('tfocid');
           $requirements = $this->_engjobrequest->getSercviceRequirements($tfocid);
           $reqhtml = "";
           foreach ($requirements as $key => $value) {
               $reqhtml .= '<div class="removerequirementsdata row pt10">';
               $reqhtml .= '<div class="col-lg-5 col-md-5 col-sm-5">
                      <div class="form-group"><div class="form-icon-user">
                        '.$value->req_description.'<input type="hidden" name="reqid[]" value="'.$value->id.'">'.'</div></div></div>';
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                        <div class="form-icon-user">
                    </div></div></div>';
               $reqhtml .= '<div class="col-lg-3 col-md-3 col-sm-3">
                     <div class="form-group">
                        <div class="form-icon-user"><input class="form-control" name="reqfile[]" type="file" value="">
                    </div></div></div>';
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                        <div class="form-icon-user"><button type="button" class="btn btn-primary btn_cancel_requiremets"><i class="ti-trash"></i></button>
                    </div></div></div>';
               $reqhtml .= '</div>';
           }
           echo $reqhtml; exit;
    }

    public function getConsultants(Request $request){
            $id =$request->input('signcatid'); 
            if($id =='1'){ $getConslutants = $this->_engjobrequest->gethremployess(); }
             else{ $getConslutants = $this->_engjobrequest->getExteranls(); }
            
              $htmloption ='<option value="">Select Consultant</option>';
              foreach ($getConslutants as $key => $value) {
                $htmloption .='<option value="'.$value->id.'">'.$value->fullname.'</option>';
              }
      echo $htmloption;
    }

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('esr_is_active' => $is_activeinactive);
        $this->_engjobrequest->updateActiveInactive($id,$data);
      }

    public function deleteFeedetails(Request $request){
        $id =$request->input('id');
        $this->_engjobrequest->deleteFeedetailsrow($id);
    }  

     public function showserviceform(Request $request){
        $arrGetservices =$this->arrGetservices;
        $jobservice = [];
        $sessionId = '';
        $JobRequest = [];
        if($request->has('id') && $request->id != ''){
            $landAppraisal = RptPropertyAppraisal::find($request->id);
            //dd($rptProprtyDetails);
            $request->request->add([
                'propertyKind' => $rptProprtyDetails->pk_id,
                'propertyClass' => $landAppraisal->pc_class_code,
                'propertyActualUseCode' => $landAppraisal->pau_actual_use_code,
                'propertyRevisionYear' => $rptProprtyDetails->rvy_revision_year_id

            ]);
        }
        if($request->has('sessionId') && $request->sessionId != ''){
            $landAppraisal = $request->session()->get('landAppraisals.'.$request->sessionId);
            $sessionId = $request->sessionId;
        }
        if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
            //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
        }
        $view = view('EngneeringOnlineOnline.engjobrequest.ajax.addservice',compact('arrGetservices'))->render();

        echo $view;
    }

    public function showelectricrevisionform(Request $request){
        $electicfeedata = array('eef_total_load_kva'=>'','eef_total_load_total_fees'=>'','eef_total_ups'=>'','eef_total_ups_total_fees'=>'','eef_pole_location_qty'=>'','eef_pole_location_total_fees'=>'','eef_guying_attachment_qty'=>'','eef_guying_attachment_fees'=>'','eefm_id'=>'','eef_electric_meter_fees'=>'','eef_wiring_permit_fees'=>'','eef_miscellaneous_tota_fees'=>'','eef_total_fees'=>'');
        $electicfeedata = (object)$electicfeedata;
        $jobserviceid =$request->input('request_id');
        $electricfeeexist = $this->_engjobrequest->geteditdataElectricfee($jobserviceid);
          if(count($electricfeeexist) > 0){
            $electicfeedata = $this->_engjobrequest->geteditdataElectricfeeshow($jobserviceid);
          }
        
        $miscellaneousfees = array();
        foreach ($this->_engjobrequest->getElectricalmiscellaneous() as $key => $value) {
            $miscellaneousfees[$value->id] = $value->eefm_description;
        }
        $data =array();
         $view = view('EngneeringOnlineOnline.engjobrequest.ajax.electricalrevision',compact('electicfeedata','miscellaneousfees','jobserviceid'))->render();
        echo $view;
    }

     public function showbuildingrevisionform(Request $request){
        $buildingfeedata = array('ebpfd_id'=>'','ebpf_total_sqm'=>'','ebpf_total_fees'=>'');
        $buildingfeedata = (object)$buildingfeedata;
        $jobserviceid = $request->input('request_id');
        $floorarea = $request->input('floorarea');
        $buildingfeeexist = $this->_engjobrequest->geteditdataBuildingfee($jobserviceid);
          if(count($buildingfeeexist) > 0){
            $buildingfeedata = $this->_engjobrequest->geteditdataBuildingfeeshow($jobserviceid);
          }
        
        $buildingdivision = array();
        foreach ($this->_engjobrequest->getengbuildingDivision() as $key => $value) {
            $buildingdivision[$value->id] = $value->ebpfd_description;
        }
        $data =array();
         $view = view('EngneeringOnlineOnline.engjobrequest.ajax.buildingrevision',compact('buildingfeedata','buildingdivision','jobserviceid','floorarea'))->render();
        echo $view;
    }

    public function checkloadrange(Request $request){
         $load = $request->input('load');
         $getrangedata = $this->_engjobrequest->getloaddatarange($load);
         $difference = $load - $getrangedata->eefl_kva_range_from;
         $extraamout= $difference * $getrangedata->eef_in_excess_fees;
         $totalkavaloadamount = $getrangedata->eefl_fees + $extraamout;
         echo $totalkavaloadamount;
    }

    public function calculatesanitaryfee(Request $request){
        $fixturefees = $this->_engjobrequest->getFixturetypefees();
         $total = "0";  $finaltotal = 0;
        foreach ($fixturefees as $key => $value) {
            $totalamount ="0";
            if($value->id=='1'){
                 $totalamount =  $request->input('espa_water_closet_qty') * $value->eft_fees;
            }
            if($value->id=='2'){
                 $totalamount =  $request->input('espa_floor_drain_qty') * $value->eft_fees;
            }
            if($value->id=='3'){
                 $totalamount =  $request->input('espa_lavatories_qty') * $value->eft_fees;
            }
            if($value->id=='4'){
                 $totalamount =  $request->input('espa_kitchen_sink_qty') * $value->eft_fees;
            }
            if($value->id=='5'){
                 $totalamount =  $request->input('espa_faucet_qty') * $value->eft_fees;
            }
            if($value->id=='6'){
                 $totalamount =  $request->input('espa_shower_head_qty') * $value->eft_fees;
            }
            if($value->id=='7'){
                 $totalamount =  $request->input('espa_water_meter_qty') * $value->eft_fees;
            }
            if($value->id=='8'){
                 $totalamount =  $request->input('espa_grease_trap_qty') * $value->eft_fees;
            }
            if($value->id=='9'){
                 $totalamount =  $request->input('espa_bath_tubs_qty') * $value->eft_fees;
            }
            if($value->id=='10'){
                 $totalamount =  $request->input('espa_slop_sink_qty') * $value->eft_fees;
            }
            if($value->id=='11'){
                 $totalamount =  $request->input('espa_urinal_qty') * $value->eft_fees;
            }
            if($value->id=='12'){
                 $totalamount =  $request->input('espa_airconditioning_unit_qty') * $value->eft_fees;
            }
            if($value->id=='13'){
                 $totalamount =  $request->input('espa_water_tank_qty') * $value->eft_fees;
            }
            if($value->id=='14'){
                 $totalamount =  $request->input('espa_bidette_qty') * $value->eft_fees;
            }
            if($value->id=='15'){
                 $totalamount =  $request->input('espa_laundry_trays_qty') * $value->eft_fees;
            }
            if($value->id=='16'){
                 $totalamount =  $request->input('espa_dental_cuspidor_qty') * $value->eft_fees;
            }
            if($value->id=='17'){
                 $totalamount =  $request->input('espa_gas_heater_qty') * $value->eft_fees;
            }
            if($value->id=='18'){
                 $totalamount =  $request->input('espa_electric_heater_qty') * $value->eft_fees;
            }
            if($value->id=='19'){
                 $totalamount =  $request->input('espa_water_boiler_qty') * $value->eft_fees;
            }
            if($value->id=='20'){
                 $totalamount =  $request->input('espa_drinking_fountain_qty') * $value->eft_fees;
            }
            if($value->id=='21'){
                 $totalamount =  $request->input('espa_bar_sink_qty') * $value->eft_fees;
            }
            if($value->id=='22'){
                 $totalamount =  $request->input('espa_soda_fountain_qty') * $value->eft_fees;
            }
            if($value->id=='23'){
                 $totalamount =  $request->input('espa_laboratory_qty') * $value->eft_fees;
            }
            if($value->id=='24'){
                 $totalamount =  $request->input('espa_sterilizer_qty') * $value->eft_fees;
            }
            if($value->id=='25'){
                 $totalamount =  $request->input('espa_swimmingpool_qty') * $value->eft_fees;
            }
            if($value->id=='26'){
                 $totalamount =  $request->input('espa_others_qty') * $value->eft_fees;
            }
            
            $finaltotal = $finaltotal + $totalamount;
        }
        
        echo $finaltotal;

    }

    public function calculatebuildingfee(Request $request){
         $id = $request->input('id');
         $totalamount = "";   $request_id = $request->input('request_id');
         $getflooearea = $this->_engjobrequest->getFloorarea($request_id);
         //$floorarea = $getflooearea->ebfd_floor_area;
         $floorarea = $request->input('floorarea');
         $geset = $this->_engjobrequest->getdivisionsetid($id);
         if($geset->ebpfd_feessetid =='1'){
            $getsetdata = $this->_engjobrequest->getdataset1($floorarea);
            $totalamount =  $floorarea * $getsetdata->ebpfs1_fees;
         }
         if($geset->ebpfd_feessetid =='2'){
            $getsetdata = $this->_engjobrequest->getdataset2($floorarea);
            $totalamount =  $floorarea * $getsetdata->ebpfs2_fees;
         }
         if($geset->ebpfd_feessetid =='3'){
            $getsetdata = $this->_engjobrequest->getdataset3($floorarea);
            $totalamount =  $floorarea * $getsetdata->ebpfs3_fees;
         }
         if($geset->ebpfd_feessetid =='4'){
            $getsetdata = $this->_engjobrequest->getdataset4($floorarea);
            $totalamount =  $floorarea * $getsetdata->ebpfs4_fees;
         }
         echo $totalamount;
    }

    public function checkupsrange(Request $request){
         $load = $request->input('upsval');
         $getrangedata = $this->_engjobrequest->getupsdatarange($load);
         $difference = $load - $getrangedata->eefu_kva_range_from;
         $extraamout= $difference * $getrangedata->eefu_in_excess_fees;
         $totalkavaloadamount = $getrangedata->eefu_fees + $extraamout;
         echo $totalkavaloadamount;
    }

    public function getpoleamount(Request $request){
         $qty = $request->input('qty');
         $id = $request->input('id');
         $getrangedata = $this->_engjobrequest->getpoleattachcost($qty,$id);
         $totalkavaloadamount = $qty * $getrangedata->eefpa_amount;;
         echo $totalkavaloadamount;
    }

    public function getmiscellaneousamount(Request $request){
         $id = $request->input('id');
         $getrangedata = $this->_engjobrequest->getmiscellanousrow($id);
         $totalamount = $getrangedata->eefpa_electic_meter_amount + $getrangedata->eefpa_wiring_permit_amount;
         echo $getrangedata->eefpa_electic_meter_amount."#".$getrangedata->eefpa_wiring_permit_amount."#".$totalamount;
    }

    public function showbuildingappfrom(Request $request){

            $this->EngAssessdata['ebaf_zoning_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_linegrade_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_bldg_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_plum_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_elec_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_mech_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_others_assessed_by']  =\Auth::user()->name;
            $this->EngAssessdata['ebaf_total_assessed_by']  =\Auth::user()->name;
            $appdata = (object)$this->appdata;
            $engfeesdata = (object)$this->engfeesdata;
            $arrGetservices =$this->arrGetservices;
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforbuilding() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 
            $arrApptype =$this->arrApptype;
            $EngAssessdata =(object)$this->EngAssessdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $arrbuildingScope = $this->arrbuildingScope;
            $jobservice = [];
            $sessionId = '';
            $JobRequest = [];
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            if($request->has('request_id') && $request->request_id != ''){
               $appdata = $this->_engjobrequest->getEditDetailsbldApp($request->input('request_id'));
               //echo "<pre>"; print_r($appdata); exit;
               if($appdata->ebpa_bldg_official_name ==""){
                $getdatausersave = $this->_engjobrequest->CheckFormdataExist('10',\Auth::user()->id);
                 if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $appdata->ebpa_bldg_official_name = $usersaved->ebpa_bldg_official_name;
                 } 
               }
                $databildfee = $this->_engjobrequest->getEditDetailsbldgFees($appdata->id);
                //echo "<pre>"; print_r($databildfee); exit;
                if(count($databildfee)){
                    $engfeesdata = $this->_engjobrequest->getDetailsbldgFeesedit($appdata->id);
                     if($engfeesdata->ebfd_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($engfeesdata->ebfd_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                }
                $getAssessdata = $this->_engjobrequest->getEditAssessmentFees($appdata->id);
                if(count($getAssessdata)){
                    $EngAssessdata = $this->_engjobrequest->getAssessmentFeesedit($appdata->id);
                }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.buildingpermit',compact('arrGetservices','appdata','arrTypeofOccupancy','engfeesdata','arrApptype','EngAssessdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','signdropdown','inchargedropdown','pcode','arrbuildingScope'))->render();

        echo $view;
    }

    public function showsanitarypermitform(Request $request){
            $sanitaryappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','espa_year'=>'','espa_series_no'=>'','espa_application_no'=>'','ebpa_permit_no'=>'','espa_application_date'=>'','espa_issued_date'=>'','p_code'=>'','taxacctno'=>'','formofowner'=>'','maineconomy'=>'','espa_location'=>'','ebs_id'=>'','ebsa_scope_remarks'=>'','ebot_id'=>'','otheroccupancy'=>'','espa_water_closet_qty'=>'','espa_water_closet_type'=>'','espa_floor_drain_qty'=>'','espa_floor_drain_type'=>'','espa_lavatories_qty'=>'','espa_lavatories_type'=>'','espa_kitchen_sink_qty'=>'','espa_kitchen_sink_type'=>'','espa_faucet_qty'=>'','espa_faucet_type'=>'','espa_shower_head_qty'=>'','espa_shower_head_type'=>'','espa_water_meter_qty'=>'','espa_water_meter_type'=>'','espa_grease_trap_qty'=>'','espa_grease_trap_type'=>'','espa_bath_tubs_qty'=>'','espa_bath_tubs_type'=>'','espa_slop_sink_qty'=>'','espa_slop_sink_type'=>'','espa_urinal_qty'=>'','espa_urinal_type'=>'','espa_airconditioning_unit_qty'=>'','espa_airconditioning_unit_type'=>'','espa_water_tank_qty'=>'','espa_water_tank_type'=>'','espa_bidette_qty'=>'','espa_bidettet_type'=>'','espa_laundry_trays_qty'=>'','espa_laundry_trays_type'=>'','espa_dental_cuspidor_qty'=>'','espa_dental_cuspidor_type'=>'','espa_gas_heater_qty'=>'','espa_gas_heater_type'=>'','espa_electric_heater_qty'=>'','espa_electric_heater_type'=>'','espa_water_boiler_qty'=>'','espa_water_boiler_type'=>'','espa_drinking_fountain_qty'=>'','espa_drinking_fountain_type'=>'','espa_bar_sink_qty'=>'','espa_bar_sink_type'=>'','espa_soda_fountain_qty'=>'','espa_soda_fountain_type'=>'','espa_laboratory_qty'=>'','espa_laboratory_type'=>'','espa_sterilizer_qty'=>'','espa_sterilizer_type'=>'','espa_swimmingpool_qty'=>'','espa_swimmingpool_type'=>'','espa_others_qty'=>'','espa_others_type'=>'','espa_others_category'=>'','ewst_id'=>'','edst_id'=>'','espa_no_of_storey'=>'','espa_floor_area'=>'','espa_installation_date'=>'','espa_installation_cost'=>'','espa_completion_date'=>'','espa_preparedby'=>'','espa_amount_due'=>'','espa_assessed_by'=>'','espa_or_no'=>'','espa_date_paid'=>'','espa_sign_category'=>'','espa_sign_consultant_id'=>'','espa_incharge_category'=>'','espa_incharge_consultant_id'=>'','espa_applicant_category'=>'','espa_applicant_consultant_id'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdcno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','espa_building_official'=>'');
            $sanitaryappdata = (object)$sanitaryappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $waterSupplyarray = array();  $disposalarray = array();
            $getConslutants = $this->_engjobrequest->getExteranls();
            
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            foreach ($this->_engjobrequest->GetTypeofWaterSupply() as $val) {
             $waterSupplyarray[$val->id]=$val->ewst_description;
            } 
            foreach ($this->_engjobrequest->GetTypeofDisposalSystem() as $val) {
             $disposalarray[$val->id]=$val->edst_description;
            } 
             $arrscopeofwork = array();
            foreach ($this->_engjobrequest->GetBuildingScopessanitary() as $val) {
             $arrscopeofwork[$val->id]=$val->ebs_description;
            } 
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforsanitary() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 

            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $sanitaryappdata = $this->_engjobrequest->getEditDetailsSanitaryApp($request->input('request_id'));
                    if($sanitaryappdata->espa_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($sanitaryappdata->espa_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                    if($sanitaryappdata->espa_building_official ==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('17',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $sanitaryappdata->espa_building_official = $usersaved->espa_building_official;
                         } 
                       }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.sanitarypermit',compact('sanitaryappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','waterSupplyarray','disposalarray','pcode','signdropdown','inchargedropdown','arrscopeofwork','arrTypeofOccupancy','arrPermitno'))->render();

        echo $view;
    }

     public function showelectricpermitform(Request $request){
            $electricappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','eea_application_date'=>'','eea_issued_date'=>'','taxacctno'=>'','formofowner'=>'','kindbussiness'=>'','p_code'=>'','ebs_id'=>'','ebot_id'=>'','eeet_id'=>'','eea_date_of_construction'=>'','eea_estimated_cost'=>'','eea_date_of_completion'=>'','eea_prepared_by'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_amount_due'=>'','eea_assessed_by'=>'','eea_or_no'=>'','eea_date_paid'=>'','eea_building_official'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','signaddress'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','signprcregno'=>'','inchargenaddress'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','inchargeprcregno'=>'','ownertaxdcno'=>'','owneraddress'=>'','ownerstreet'=>'','ownersubdivision'=>'','ownermuncipality'=>'','ownertelephoneno'=>'','lotno'=>'','streetname'=>'','subdivision'=>'','ownerespa_location'=>'');
            $electricappdata = (object)$electricappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $electicequipmentarray = array(); 
            foreach ($this->_engjobrequest->GetElecticEquipments() as $val) {
             $electicequipmentarray[$val->id]=$val->eeet_description;
            } 
            $arrscopeofwork = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselectric() as $val) {
             $arrscopeofwork[$val->id]=$val->ebs_description;
            } 
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforelectric() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $electricappdata = $this->_engjobrequest->getEditDetailsElecticApp($request->input('request_id'));
                    if($electricappdata->eea_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($electricappdata->eea_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                     if($electricappdata->eea_building_official ==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('12',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $electricappdata->eea_building_official = $usersaved->eea_building_official;
                         } 
                       }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.electricpermit',compact('electricappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','electicequipmentarray','pcode','signdropdown','inchargedropdown','arrscopeofwork','arrPermitno'))->render();

        echo $view;
    }
    public function showcivilpermitform(Request $request){
            $civilappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eca_year'=>'','eca_series_no'=>'','eca_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eca_form_of_own'=>'','ebs_id'=>'','ebot_id'=>'','ecst_id'=>'','eca_sign_category'=>'','eca_sign_consultant_id'=>'','eca_incharge_category'=>'','eca_location'=>'','eca_incharge_consultant_id'=>'','eca_applicant_category'=>'','eca_applicant_consultant_id'=>'','eca_owner_id'=>'','eca_building_official'=>'','eca_tax_acct_no'=>'','eca_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');
            $civilappdata = (object)$civilappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopecivil() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforcivil() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $sessionId = '';
            $JobRequest = [];
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $civilappdata = $this->_engjobrequest->getEditDetailsCivilApp($request->input('request_id'));
                    if($civilappdata->eca_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($civilappdata->eca_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                     if($civilappdata->eca_building_official==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('21',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $civilappdata->eca_building_official = $usersaved->eca_building_official;
                         } 
                       }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.civilpermit',compact('civilappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno'))->render();
        echo $view;
    }

     public function showelectronicspermitform(Request $request){
            $electronicsappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eeta_year'=>'','eeta_series_no'=>'','eeta_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eeta_form_of_own'=>'','eeta_location'=>'','ebs_id'=>'','ebot_id'=>'','eest_id'=>'','eeta_sign_category'=>'','eeta_sign_consultant_id'=>'','eeta_incharge_category'=>'','eeta_incharge_consultant_id'=>'','eeta_applicant_category'=>'','eeta_applicant_consultant_id'=>'','eeta_owner_id'=>'','eeta_building_official'=>'','eeta_tax_acct_no'=>'','eeta_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');
            $electronicsappdata = (object)$electronicsappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $electronicequipmentarray = array(); 
            foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
             $electronicequipmentarray[$val->id]=$val->eest_description;
            }
             $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselectronic() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            }  
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $electronicsappdata = $this->_engjobrequest->getEditDetailsElectronicsApp($request->input('request_id'));
                    if($electronicsappdata->eeta_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($electronicsappdata->eeta_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                   if($electronicsappdata->eeta_building_official==""){
                    $getdatausersave = $this->_engjobrequest->CheckFormdataExist('20',\Auth::user()->id);
                     if(count($getdatausersave)>0){
                      $usersaved = json_decode($getdatausersave[0]->is_data);
                      $electronicsappdata->eeta_building_official = $usersaved->eeta_building_official;
                     } 
                   }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.electronicspermit',compact('electronicsappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','electronicequipmentarray','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno'))->render();

        echo $view;
    }

    public function showmechanicalpermitform(Request $request){
            $mechanicalappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','ema_year'=>'','ema_series_no'=>'','ema_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','ema_form_of_own'=>'','ema_location'=>'','ebs_id'=>'','ebot_id'=>'','eiot_id'=>'','ema_sign_category'=>'','ema_sign_consultant_id'=>'','ema_incharge_category'=>'','ema_incharge_consultant_id'=>'','ema_applicant_category'=>'','ema_applicant_consultant_id'=>'','ema_owner_id'=>'','ema_building_official'=>'','ema_tax_acct_no'=>'','ema_economic_act'=>'','lotno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','blkno'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');
            $mechanicalappdata = (object)$mechanicalappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $instllationtypearray = array(); 
            foreach ($this->_engjobrequest->GetInstallationOperationType() as $val) {
             $instllationtypearray[$val->id]=$val->eiot_description;
            } 
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselmechanical() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $sessionId = '';
            $JobRequest = [];
            
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }

            if($request->has('request_id') && $request->request_id != ''){
               $mechanicalappdata = $this->_engjobrequest->getEditDetailsMechanicalApp($request->input('request_id'));
                    if($mechanicalappdata->ema_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($mechanicalappdata->ema_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                   if($mechanicalappdata->ema_building_official ==""){
                     $getdatausersave = $this->_engjobrequest->CheckFormdataExist('18',\Auth::user()->id);
                      if(count($getdatausersave)>0){
                      $usersaved = json_decode($getdatausersave[0]->is_data);
                      $mechanicalappdata->ema_building_official = $usersaved->ema_building_official;
                     } 
                   }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.mechanicalpermit',compact('mechanicalappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','instllationtypearray','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno'))->render();

        echo $view;
    }

    public function showexcavationpermitform(Request $request){
         $excavationappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eega_year'=>'','eega_series_no'=>'','eega_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eega_form_of_own'=>'','eega_tax_acct_no'=>'','eega_economic_act'=>'','eega_location'=>'','lotno'=>'','blkno'=>'','totno'=>'','tdno'=>'','Street'=>'','ebs_id'=>'','ebot_id'=>'','eegt_id'=>'','eega_sign_category'=>'','eega_sign_consultant_id'=>'','eega_incharge_category'=>'','eega_incharge_consultant_id'=>'','eega_applicant_category'=>'','eega_applicant_consultant_id'=>'','eega_owner_id'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ctcoctno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','eega_building_official'=>'');
            $excavationappdata = (object)$excavationappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselexcavation() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforelexacavation() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 

            $excavationgroundtypearray = array(); 
            foreach ($this->_engjobrequest->GetExcavationGroundType() as $val) {
             $excavationgroundtypearray[$val->id]=$val->eegt_description;
            } 

            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $excavationappdata = $this->_engjobrequest->getEditDetailsExcavationApp($request->input('request_id'));
               if($excavationappdata->eega_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($excavationappdata->eega_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                    if($excavationappdata->eega_building_official ==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('19',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $excavationappdata->eega_building_official = $usersaved->eega_building_official;
                        } 
                    }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.excavationpermit',compact('excavationappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','excavationgroundtypearray'))->render();

        echo $view;
    }

    public function showarchitecturalpermitform(Request $request){
         $architecturalappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eea_form_of_own'=>'','eea_location'=>'','ebs_id'=>'','ebot_id'=>'','eaft_id'=>'','eaa_footprint'=>'','eaa_impervious_area'=>'','eaa_unpaved_area'=>'','eaa_others_percentage'=>'','ectfc_id'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_building_official'=>'','eea_tax_acct_no'=>'','eea_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');
            $architecturalappdata = (object)$architecturalappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            //$arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselarchitecture() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $arrTypeofOccupancy = array();
            foreach ($this->_engjobrequest->GetTypeofOccupancyforelarchitecture() as $val) {
             $arrTypeofOccupancy[$val->id]=$val->ebot_description;
            } 

            $architecturefeaturetypearray = array(); 
            foreach ($this->_engjobrequest->GetElecticArchitectureFeatureType() as $val) {
             $architecturefeaturetypearray[$val->id]=$val->eaft_description;
            } 
            $confirmancefirearray = array(); 
            foreach ($this->_engjobrequest->GetConfirmnaceFireType() as $val) {
             $confirmancefirearray[$val->id]=$val->ectfc_description;
            } 
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $architecturalappdata = $this->_engjobrequest->getEditDetailsArchitecturalApp($request->input('request_id'));
                if($architecturalappdata->eea_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($architecturalappdata->eea_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                    if($architecturalappdata->eea_building_official==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('22',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $appdata->eea_building_official = $usersaved->eea_building_official;
                         } 
                       }
            }
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.architecturalpermit',compact('architecturalappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','architecturefeaturetypearray','confirmancefirearray'))->render();

        echo $view;
    }

    public function showfencingpermitform(Request $request){
         $fencingappdata = array('id'=>'','ejr_id'=>'','mun_no'=>'','efa_year'=>'','measurelength'=>'','measureheight'=>'','typeoffencing'=>'','efa_series_no'=>'','efa_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','efa_form_of_own'=>'','ebpa_location'=>'','ebs_id'=>'','eft_id'=>'','efa_sign_category'=>'','efa_sign_consultant_id'=>'','efa_inspector_category'=>'','efa_inspector_consultant_id'=>'','efa_applicant_category'=>'','efa_applicant_consultant_id'=>'','efa_owner_id'=>'','efa_linegrade_amount'=>'','efa_linegrade_processed_by'=>'','efa_linegrade_or_no'=>'','efa_linegrade_date_paid'=>'','efa_fencing_amount'=>'','efa_fencing_processed_by'=>'','efa_fencing_or_no'=>'','efa_fencing_date_paid'=>'','efa_electrical_amount'=>'','efa_electrical_processed_by'=>'','efa_electrical_or_no'=>'','efa_electrical_date_paid'=>'','efa_others_amount'=>'','efa_others_processed_by'=>'','efa_others_or_no'=>'','efa_others_date_paid'=>'','efa_total_amount'=>'','efa_total_processed_by'=>'','efa_total_or_no'=>'','efa_total_date_paid'=>'','taxacctno'=>'','maineconomy'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'',''=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'',''=>'','owner_date_issued'=>'','ownerplaceissued'=>'','applicantnamenew'=>'','applicantaddressnew'=>'','ctcnonew'=>'','dateissuednew'=>'','placeissuednew'=>'','liancnedapplicant'=>'','liancnedaddress'=>'','liancnedctcno'=>'','liancneddateissued'=>'','liancnedplaceissued'=>'','efa_building_official'=>'');
            $fencingappdata = (object)$fencingappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            //$arrbuildingScope = $this->arrbuildingScope;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselfetching() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $arrtypeofFencing = array();
            foreach ($this->_engjobrequest->GetTypeofFencing() as $val) {
               $arrtypeofFencing[$val->id]=$val->eft_description;
            }
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val){
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $fencingappdata = $this->_engjobrequest->getEditDetailsFencingApp($request->input('request_id'));
                if($fencingappdata->efa_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($fencingappdata->efa_inspector_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                     if($fencingappdata->efa_building_official ==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('13',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $fencingappdata->efa_building_official = $usersaved->efa_building_official;
                         } 
                    }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.fencingpermit',compact('fencingappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','pcode','signdropdown','inchargedropdown','arrPermitno','arrtypeofFencing'))->render();

        echo $view;
    }

    public function showsignpermitform(Request $request){
        $signappdata = array('id'=>'','ejr_id'=>'','mun_no'=>'','esa_year'=>'','esa_series_no'=>'','esa_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','taxaccno'=>'','esa_form_of_own'=>'','ebpa_location'=>'','ebs_id'=>'','ebot_id'=>'','esdt_id'=>'','esit_id'=>'','esa_sign_category'=>'','esa_sign_consultant_id'=>'','esa_incharge_category'=>'','esa_incharge_consultant_id'=>'','esa_applicant_category'=>'','esa_applicant_consultant_id'=>'','esa_owner_id'=>'','esa_building_official'=>'','esa_economic_act'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','length'=>'','width'=>'','alllengthwidth'=>'');
            $signappdata = (object)$signappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            //$arrbuildingScope = $this->arrbuildingScope;
            $arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrsigndisplaytype = $this->arrsigndisplaytype;
            $arrsignInstllationtype = $this->arrsignInstllationtype;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeselsign() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $signappdata = $this->_engjobrequest->getEditDetailsSignApp($request->input('request_id'));
               if($signappdata->esa_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($signappdata->esa_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                    if($signappdata->esa_building_official ==""){
                        $getdatausersave = $this->_engjobrequest->CheckFormdataExist('15',\Auth::user()->id);
                         if(count($getdatausersave)>0){
                          $usersaved = json_decode($getdatausersave[0]->is_data);
                          $signappdata->esa_building_official = $usersaved->esa_building_official;
                      } 
                  }
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.signpermit',compact('signappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','arrTypeofOccupancy','arrsigndisplaytype','arrsignInstllationtype','pcode','signdropdown','inchargedropdown','arrPermitno'))->render();

        echo $view;
    }

    public function showdemolitionpermitform(Request $request){
        $demolitionnappdata = array('id'=>'','mun_no'=>'','eda_year'=>'','eda_series_no'=>'','eda_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eda_economic_act'=>'','eda_tax_acct_no'=>'','loclotno'=>'','locblkno'=>'','loctotno'=>'','loctdno'=>'','locstreet'=>'','eda_form_of_own'=>'','eda_location'=>'','ebs_id'=>'','ebot_id'=>'','eda_sign_category'=>'','eda_sign_consultant_id'=>'','inchargeaddress'=>'','inchargetin'=>'','inchargeprcno'=>'','inchargetin'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','eda_incharge_category'=>'','eda_incharge_consultant_id'=>'','eda_applicant_category'=>'','eda_applicant_consultant_id'=>'','applicantaddress'=>'','applicantctcno'=>'','applicantdateissued'=>'','applicantplaceissued'=>'','eda_owner_id'=>'','owneraddress'=>'','ownerctcno'=>'','ownerdateissued'=>'','ownerplaceissued'=>'','applicantnew'=>'','applicantaddressnew'=>'','ctcnonew'=>'','dateissuednew'=>'','placeissuednew'=>'','liancenedapplicant'=>'','liancenedctcno'=>'','lianceneddateissued'=>'','liancenedplaceissued'=>'','liancenedaddress'=>'','eda_building_official'=>'','ordateissued'=>'','orplaceissued'=>'');
            $demolitionnappdata = (object)$demolitionnappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            //$arrbuildingScope = $this->arrbuildingScope;
            $arrTypeofOccupancy = $this->arrTypeofOccupancy;
            $arrsigndisplaytype = $this->arrsigndisplaytype;
            $arrsignInstllationtype = $this->arrsignInstllationtype;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $arrbuildingScope = array();
            foreach ($this->_engjobrequest->GetBuildingScopeseldemolition() as $val) {
             $arrbuildingScope[$val->id]=$val->ebs_description;
            } 
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));
            $pcode = $clientid[0]->client_id;
            $sessionId = '';
            $JobRequest = [];
            $arrPermitno = array("Please Select");
            foreach ($this->_engjobrequest->GetBuildingpermits($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $demolitionnappdata = $this->_engjobrequest->getEditDetailsDemolitionApp($request->input('request_id'));
                   if($demolitionnappdata->eda_sign_category =='2'){
                     $signdropdown = $consultant;
                     }
                     if($demolitionnappdata->eda_incharge_category =='2'){
                        $inchargedropdown = $consultant;
                    }
                  if($demolitionnappdata->eda_building_official ==""){
                    $getdatausersave = $this->_engjobrequest->CheckFormdataExist('11',\Auth::user()->id);
                     if(count($getdatausersave)>0){
                      $usersaved = json_decode($getdatausersave[0]->is_data);
                      $demolitionnappdata->eda_building_official = $usersaved->eda_building_official;
                     } 
                   }   
            }
            
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('EngneeringOnline.engjobrequest.ajax.demolitionpermit',compact('demolitionnappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','arrTypeofOccupancy','arrsigndisplaytype','arrsignInstllationtype','pcode','signdropdown','inchargedropdown','arrPermitno'))->render();

        echo $view;
    }


     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $eid = $request->input('eid');
        $arrRequirements = $this->_engjobrequest->getRequirementsbyid($rid);
        if(count($arrRequirements) > 0){
            if($arrRequirements[0]->fe_name){
                $path =  public_path().'/uploads/'.$arrRequirements[0]->fe_path."/".$arrRequirements[0]->fe_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_engjobrequest->deleteRequirementsbyid($rid);
                if(!empty($eid)){
                   $this->_engjobrequest->deleteimagerowbyid($eid); 
                }
                echo "deleted";
            }
        }
    }

    public function MakeapprovePermit(Request $request){
        $getseries = $this->_engjobrequest->getlatestseries();
        //echo "<pre>"; print_r($getseries); exit;  
        $prmitsrno = $getseries->permitnoseries;
        $permitsrno= $prmitsrno + 1; 
        $id = $request->input('id');
        $appPermitNo = date('Y').'-'.date('m').'-'.str_pad($permitsrno, 4, '0', STR_PAD_LEFT);
        if($request->input('serviceid') =='1'){
            $updatearray = array('ebpa_permit_no'=>$appPermitNo);
            $this->_engjobrequest->updatePermitAppData($id,$updatearray);
         }
            //else if($request->input('serviceid') =='2'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateDemolitionAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='3'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateSanitaryAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='4'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateFencingAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='5'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateExcavationAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='6'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateElecticAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='8'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateSignAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='9'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateElectronicsAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='10'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateMechanicalAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='11'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateCivilAppData($id,$updatearray);
        // }
        // else if($request->input('serviceid') =='14'){
        //     $updatearray = array('ebpa_permit_no'=>$appPermitNo);
        //     $this->_engjobrequest->updateArchitecturalAppData($id,$updatearray);
        // }

        $jobreqarray =array('ejr_opd_approved_by'=>\Auth::user()->id,'is_approve'=>'1','permitnoseries'=>$permitsrno);
        $this->_engjobrequest->updateData($request->input('ejrid'),$jobreqarray);
        $array =array();
        $array['status'] = "success";
        $array['permitid'] = $appPermitNo;
        echo json_encode($array);
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrOwners = $this->arrOwners;
        $arrRequirements = array();
        $requirements =$this->arrRequirements;
        $arrGetservices =$this->arrGetservices;
        $applicationid =""; $locationofapp ="";
        $data->is_approve =""; $extrafeearr = array();
        $defaultFeesarr = $this->_engjobrequest->GetDefaultfees();
        $getextrafees = $this->_engjobrequest->getextrafees();
        foreach ($getextrafees as $key => $value) {
              $extrafeearr[$value->description."#".$value->tfoc_id] = $value->description;
          }
        foreach ($this->_engjobrequest->getSercviceRequirementsall() as $val) {
             $requirements[$val->id]=$val->req_code_abbreviation."-".$val->req_description;
         }
        //print_r($defaultFeesarr); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engjobrequest->getDataJobrequestedit($request->input('id'));
            //echo "<pre>"; print_r($data); exit;
            if($data->brgy_code>0){
                foreach ($this->_engjobrequest->getBarangayedit($data->brgy_code)['data'] as $val) {
                    $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                }
            }
            foreach ($this->_commonmodel->getBarangay($data->location_brgy_id)['data'] as $val) {
                $locationofapp=$val->brgy_name;
            }
            if($data->es_id =='1'){
                $data->class  = "buildingpermit";
                $appid = $this->_engjobrequest->Getbidappid($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }else if($data->es_id =='2'){ 
                $data->class = "demolitionpermit";
                $appid = $this->_engjobrequest->getEditDetailsDemolitionApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }else if($data->es_id =='3'){ 
                $data->class = "sanitarypermit";
                $appid = $this->_engjobrequest->getEditDetailsSanitaryApp($data->frgn_ejr_id); 
                //dd($appid);
                $applicationid = $appid->id; 
            }else if($data->es_id =='4'){ 
                $data->class = "fencingpermit";
                $appid = $this->_engjobrequest->getEditDetailsFencingApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }else if($data->es_id =='5'){ 
                $data->class = "excavationpermit";
                 $appid = $this->_engjobrequest->getEditDetailsExcavationApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='6'){ 
                $data->class = "electicpermit";
                $appid = $this->_engjobrequest->getEditDetailsElecticApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='8'){ 
                $data->class = "signpermit";
                $appid = $this->_engjobrequest->getEditDetailsSignApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='9'){ 
                $data->class = "electronicpermit";
                $appid = $this->_engjobrequest->getEditDetailsElectronicsApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='10'){ 
                $data->class = "mechanicalpermit";
                $appid = $this->_engjobrequest->getEditDetailsMechanicalApp($data->frgn_ejr_id); 
                if(!empty($appid)){ $applicationid = $appid->id; }
                
            }
            else if($data->es_id =='11'){ 
                $data->class = "civilpermit";
                $appid = $this->_engjobrequest->getEditDetailsCivilApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='13'){ 
                $data->class = "architecturalpermit";
                $appid = $this->_engjobrequest->getEditDetailsArchitecturalApp($data->frgn_ejr_id); 
                $applicationid = $appid->id;
            }
            $arrRequirements = $this->_engjobrequest->getJobRequirementsData($data->frgn_ejr_id);
            $defaultFeesarr = array(); 
             //echo "<pre>"; print_r($arrRequirements);  exit;
        }
        $userroleid = "";
        $getroleofuserdata = $this->_engjobrequest->getUserrole(\Auth::user()->id);
        if(count($getroleofuserdata) > 0){
           $userroleid = $getroleofuserdata[0]->id; 
        }
        //echo "<pre>"; print_r($arrOwners); print_r($data); exit;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $cashdata = $this->_engjobrequest->getCasheringIds($this->data['tfoc_id']);
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['ejr_opd_modified_by']=\Auth::user()->id;
            $this->data['agl_account_id'] = $cashdata->gl_account_id;
            $this->data['sl_id'] = $cashdata->sl_id;
            $this->data['surcharge_gl_id'] = $cashdata->tfoc_surcharge_gl_id;
            $this->data['surcharge_sl_id'] = $cashdata->tfoc_surcharge_sl_id;
            if($request->input('id')>0){
                unset($this->data['ejr_jobrequest_no']);
                unset($this->data['Applicationtype']); 
                unset($this->data['application_no']); 
                unset($this->data['es_id']);
                unset($this->data['tfoc_id']);
                $this->data['ejr_projectcost'] = str_replace(",", "", $this->data['ejr_projectcost']);
                $this->data['ejr_total_net_amount'] = str_replace(",", "", $this->data['ejr_total_net_amount']);
                $this->data['ejr_surcharge_fee'] = str_replace(",", "", $this->data['ejr_surcharge_fee']);
                $this->data['ejr_totalfees'] = str_replace(",", "", $this->data['ejr_totalfees']);
                $this->_engjobrequest->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Job Request updated successfully.';
               if(!empty($_POST['feesdesc'])){ 
                foreach ($_POST['feesdesc'] as $key => $value) {
                    $jobfeesdetails =array();
                     $jobfeesdetails['ejr_id'] =$request->input('id');
                     if($_POST['istfocid'][$key] == 0){
                       $jobfeesdetails['tfoc_id'] = $_POST['tfoc_id'];  
                       $jobfeesdetails['is_default'] = '0';
                       $jobfeesdetails['fees_description'] = $value;
                     }else{
                        $jobfeesdetails['is_default'] = '1';
                        $feedata = explode('#',$value);
                        if(count($feedata) > 1){
                           $jobfeesdetails['fees_description'] = $feedata[0];
                           $jobfeesdetails['tfoc_id'] = $feedata[1];   
                        }else{
                            $jobfeesdetails['fees_description'] = $feedata[0];
                        }
                     }
                     $jobfeesdetails['agl_account_id'] = $cashdata->gl_account_id;
                     $jobfeesdetails['sl_id'] = $cashdata->sl_id;
                     //$jobfeesdetails['tax_amount'] = $_POST['amountfee'][$key];
                     $jobfeesdetails['tax_amount'] = str_replace(",", "", $_POST['amountfee'][$key]);
                     $checkexist = $this->_engjobrequest->checkJobrequestFeesDetail($jobfeesdetails['fees_description'],$request->input('id'));
                     if(count($checkexist) > 0){
                            $this->_engjobrequest->updateJobrequestFeesDetailData($checkexist[0]->id,$jobfeesdetails);
                     }else{
                        $jobfeesdetails['created_by']=\Auth::user()->id;
                        $jobfeesdetails['created_at'] = date('Y-m-d H:i:s');
                        $jobfeesdetails['updated_by']=\Auth::user()->id;
                        $jobfeesdetails['updated_at'] = date('Y-m-d H:i:s');
                        $this->_engjobrequest->addJobrequestFeesDetailData($jobfeesdetails);
                     }
                   }
                 }
                //echo "<pre>"; print_r($_FILES); 
                if(!empty($_POST['reqid']) > 0){
                foreach ($_POST['reqid'] as $key => $value) {
                    $jobreqarr = array();
                    $jobreqarr['ejr_id'] = $request->input('id');
                    $jobreqarr['tfoc_id'] = $_POST['tfoc_id'];
                    $jobreqarr['es_id'] = $_POST['es_id'];
                    $jobreqarr['req_id'] = $_POST['reqid'][$key];
                    $jobreqarr['created_by']=\Auth::user()->id;
                    $jobreqarr['created_at'] = date('Y-m-d H:i:s');
                    $checkexistreq = $this->_engjobrequest->checkJobRequirementsexist($request->input('id'),$_POST['reqid'][$key]);
                    if(count($checkexistreq) > 0){
                        $lastinsertid = $checkexistreq[0]->id; 
                    }else{ $lastinsertid = $this->_engjobrequest->addJobRequirementsData($jobreqarr); }
                      
                     if(isset($request->file('reqfile')[$key])){  
                        if($image =$request->file('reqfile')[$key]) {  
                         $reqid= $_POST['reqid'][$key]; 
                     $destinationPath =  public_path().'/uploads/engineering/'.$_POST['ebpa_application_no'];
                        if(!File::exists($destinationPath)){ 
                            File::makeDirectory($destinationPath, 0755, true, true);
                        }
                     $filename =  $_POST['ebpa_application_no'].'requirement'.$lastinsertid;  
                     $filename = str_replace(" ", "", $filename);   
                     $requirementpdf = $filename. "." . $image->extension();
                     $extension =$image->extension();
                     $image->move($destinationPath, $requirementpdf);
                     
                    // print_r($image); exit;
                     $filearray = array();
                     $filearray['ejrr_id'] = $lastinsertid;
                     $filearray['ejr_id'] =  $request->input('id');
                     $filearray['fe_name'] = $requirementpdf;
                     $filearray['fe_type'] = $extension;
                    // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                     $filearray['fe_path'] = 'engineering';
                     $filearray['created_by']=\Auth::user()->id;
                     $filearray['created_at'] = date('Y-m-d H:i:s');
                     $checkimageexits = $this->_engjobrequest->checkRequirementfileexist($reqid);
                     if(!empty($_POST['feid'][$key])){ 
                        $this->_engjobrequest->UpdateengFilesData($checkimageexits[0]->id,$filearray);
                     }else{ $this->_engjobrequest->AddengFilesData($filearray); }
                     
                     // echo $profileImage;
                     }
                   } 
                }
             }
            }
            return redirect()->route('engjobrequest.index')->with('success', __($success_msg));
    	}
        return view('EngneeringOnline.engjobrequest.create',compact('data','arrgetBrgyCode','arrOwners','arrRequirements','arrGetservices','applicationid','defaultFeesarr','extrafeearr','requirements','userroleid','locationofapp'));
	}

    public function savejobreuest(Request $request){
        foreach((array)$this->datafirst as $key=>$val){
                $this->datafirst[$key] = $request->input($key);
            }
            if($request->input('id')>0){
                 foreach((array)$this->data as $key=>$val){
                   $this->data[$key] = $request->input($key);
                } 
                unset($this->data['ejr_jobrequest_no']);
                unset($this->data['Applicationtype']); 
                unset($this->data['application_no']); 
                unset($this->data['es_id']);
                unset($this->data['tfoc_id']);
                $this->data['updated_by']=\Auth::user()->id;
                $this->_engjobrequest->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $jobreqNo = $request->input('ejr_jobrequest_no');
                $appsereviceNo = $request->input('ebpa_application_no');
                $appid ="";
               
                 $array = ["status"=>"update","message" => ""];
            }else{
                $appsereviceNo ="";
                $this->datafirst['created_by']=\Auth::user()->id;
                $this->datafirst['created_at'] = date('Y-m-d H:i:s');
                $this->datafirst['is_active'] = 1;
                 unset($this->datafirst['ebpa_application_no']);
                $lastinsertid = $this->_engjobrequest->addData($this->datafirst);
                $jobreqNo = date('Y').'-'.str_pad($lastinsertid, 6, '0', STR_PAD_LEFT);
                $updateData= array('ejr_jobrequest_no'=>$jobreqNo);
                $this->_engjobrequest->updateData($lastinsertid,$updateData);

                if($this->datafirst['es_id'] =='1'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addPermitAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('ebpa_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updatePermitAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="buildingpermit";
                }
                if($this->datafirst['es_id'] =='2'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addDemolitionAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eda_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateDemolitionAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="demolitionpermit";
                }

                if($this->datafirst['es_id'] =='3'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addSanitaryAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('espa_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateSanitaryAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="sanitarypermit";
                }

                if($this->datafirst['es_id'] =='4'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addFencingAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('efa_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateFencingAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="fencingpermit";
                }


                if($this->datafirst['es_id'] =='5'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addExcavationAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eega_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateExcavationAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="excavationpermit";
                }


                if($this->datafirst['es_id'] =='6'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addElecticAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eea_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateElecticAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="electicpermit";
                }

                 if($this->datafirst['es_id'] =='8'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addSignAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('esa_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateSignAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="signpermit";
                }

                 if($this->datafirst['es_id'] =='9'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addElectronicsAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eeta_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateElectronicsAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="electronicpermit";
                }

                if($this->datafirst['es_id'] =='10'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addMechanicalAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('ema_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateMechanicalAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="mechanicalpermit";
                 }

                 if($this->datafirst['es_id'] =='11'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addCivilAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eca_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateCivilAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="civilpermit";
                }
                 if($this->datafirst['es_id'] =='13'){
                    $permitappdata = array();
                    $permitappdata['ejr_id'] =$lastinsertid;
                    $permitappdata['p_code'] =$this->datafirst['client_id'];
                    $appid = $this->_engjobrequest->addArcghitecturalAppData($permitappdata);
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                    $updateData2= array('eea_application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateArchitecturalAppData($appid,$updateData2);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="architecturalpermit";
                }

                $appid2 =$appsereviceNo; 
                $defaultFeesarr = $this->_engjobrequest->GetDefaultfees();
                $cashdata = $this->_engjobrequest->getCasheringIds($this->datafirst['tfoc_id']);
                  foreach ($defaultFeesarr as $key => $value) {
                    $jobfeesdetails =array();
                     $jobfeesdetails['ejr_id'] =$lastinsertid;
                     $jobfeesdetails['tfoc_id'] = $_POST['tfoc_id'];  
                     $jobfeesdetails['agl_account_id'] = $cashdata->gl_account_id;
                     $jobfeesdetails['sl_id'] = $cashdata->sl_id;
                     $jobfeesdetails['fees_description'] = $value->fees_description;
                     $jobfeesdetails['tax_amount'] = "";
                        $jobfeesdetails['created_by']=\Auth::user()->id;
                        $jobfeesdetails['created_at'] = date('Y-m-d H:i:s');
                        $jobfeesdetails['updated_by']=\Auth::user()->id;
                        $jobfeesdetails['updated_at'] = date('Y-m-d H:i:s');
                      $this->_engjobrequest->addJobrequestFeesDetailData($jobfeesdetails);
               }
                if(!empty($_POST['reqid']) > 0){
                foreach ($_POST['reqid'] as $key => $value) {
                    $jobreqarr = array();
                    $jobreqarr['ejr_id'] = $lastinsertid;
                    $jobreqarr['tfoc_id'] = $_POST['tfoc_id'];
                    $jobreqarr['es_id'] = $_POST['es_id'];
                    $jobreqarr['req_id'] = $_POST['reqid'][$key];
                    $jobreqarr['created_by']=\Auth::user()->id;
                    $jobreqarr['created_at'] = date('Y-m-d H:i:s');
                    $checkexistreq = $this->_engjobrequest->checkJobRequirementsexist($lastinsertid,$_POST['reqid'][$key]);
                    if(count($checkexistreq) > 0){
                        
                    }else{  $this->_engjobrequest->addJobRequirementsData($jobreqarr); }
                   }
                }

                $array = ["status"=>"success","lastinsertid" => $lastinsertid,"appid2"=>$appsereviceNo,"jobreqno"=>$jobreqNo,"class"=>$class,"appid"=>$appid];

            }
            
         echo json_encode($array);
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'rpo_address_house_lot_no'=>'required',
                'rpo_address_street_name'=>'required',
                'rpo_address_subdivision'=>'required',
                'brgy_code'=>'required',
                'es_id'=>'required',
            ]
            ,
            [
                'client_id.required|unique' => 'Client Has Added Service Allready',
                'rpo_address_house_lot_no.required'=>'Required',
                'rpo_address_subdivision.required'=>'Required',
                'brgy_code.required'=>'Required',
                'es_id.required'=>'Required',
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
        //'client_id'=>'required|unique:eng_job_requests,client_id,'.$request->input('id').',id,es_id,'.$request->input('es_id')
    }

    public function PermitValidationBuilding(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'ebpa_mun_no'=>'required',
            ]
            ,
            [
                'ebpa_mun_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

    public function permitvalidationSanitary(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required'
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

     public function permitvalidationElectric(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

    public function permitvalidationElectronic(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
               
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

    public function permitvalidationMechanical(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
               
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

     public function permitvalidationExcavation(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
                // 'eegt_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

     public function permitvalidationCivil(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
               
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }


    public function permitvalidationArchitectural(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

    public function permitvalidationSign(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mun_no'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }

       public function permitvalidationDemolition(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'mun_no'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }


    public function permitvalidationFencing(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'mun_no'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
            ]
        );
        $arr=array('status'=>'success');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['status'] = 'validation_error';
        }
        echo json_encode($arr);exit;
    }



    public function SaveBuildingPermit(Request $request){
             foreach((array)$this->appdata as $key=>$val){
                $this->appdata[$key] = $request->input($key);
            }
            foreach((array)$this->engfeesdata as $key=>$val){
                $this->engfeesdata[$key] = $request->input($key);
            }

            foreach((array)$this->EngAssessdata as $key=>$val){
                $this->EngAssessdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                unset($this->appdata['id']);
                $this->_engjobrequest->updatePermitAppData($request->input('application_id'),$this->appdata);

                $data = $this->_engjobrequest->getEditDetailsbldgFees($request->input('application_id'));
                if(count($data)){
                    unset($this->appdata['id']);
                    $this->_engjobrequest->updatePermitbldgFees($request->input('application_id'),$this->engfeesdata);
                }else{
                    unset($this->appdata['id']);
                   $this->engfeesdata['ebpa_id'] =  $request->input('application_id');
                   //echo "<pre>"; print_r($this->engfeesdata); exit;
                   $this->_engjobrequest->addDataBldgFeeData($this->engfeesdata);
                    $user_savedata = array();
                    $user_savedata['ebpa_bldg_official_name'] = $request->input('ebpa_bldg_official_name');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 10;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('10',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }

                $dataassess = $this->_engjobrequest->getEditAssessmentFees($request->input('application_id'));
                if(count($dataassess)){
                    $this->_engjobrequest->updateAssessmentbldgFees($request->input('application_id'),$this->EngAssessdata);
                }else{
                    $this->EngAssessdata['ebpa_id'] =  $request->input('application_id');
                   $this->_engjobrequest->addAssessmentAppData($this->EngAssessdata);
                }
                
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);
    }


    public function storesanitarypermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
                'espa_application_no'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name' =>'required',
                'ebpa_owner_mid_name'=>'required',
                'ewst_id'=>'required',
                'edst_id'=>'required',
                'espa_location'=>'required',
                'espa_no_of_storey'=>'required',
                'espa_floor_area'=>'required',
                'espa_installation_date'=>'required',
                'espa_installation_cost'=>'required',
                'espa_completion_date'=>'required',
                'espa_assessed_by'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'espa_application_no.required'=>'Required',
                'ebpa_owner_last_name.required'=>'Required',
                'ebpa_owner_first_name.required'=>'Required',
                'ebpa_owner_mid_name.required'=>'Required',
                'ewst_id.required'=>'Required',
                'edst_id.required'=>'Required',
                'espa_location.required'=>'Required',
                'espa_no_of_storey.required'=>'Required',
                'espa_floor_area.required'=>'Required',
                'espa_installation_date.required'=>'Required',
                'espa_installation_cost.required'=>'Required',
                'espa_completion_date.required'=>'Required',
                'espa_assessed_by.required'=>'Required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


              $sanitaryappdata = array('id'=>'','mum_no'=>'','espa_application_no'=>'','ebpa_permit_no'=>'','espa_application_date'=>'','espa_issued_date'=>'','p_code'=>'','taxacctno'=>'','formofowner'=>'','maineconomy'=>'','espa_location'=>'','ebs_id'=>'','ebsa_scope_remarks'=>'','ebot_id'=>'','otheroccupancy'=>'','espa_water_closet_qty'=>'','espa_water_closet_type'=>'','espa_floor_drain_qty'=>'','espa_floor_drain_type'=>'','espa_lavatories_qty'=>'','espa_lavatories_type'=>'','espa_kitchen_sink_qty'=>'','espa_kitchen_sink_type'=>'','espa_faucet_qty'=>'','espa_faucet_type'=>'','espa_shower_head_qty'=>'','espa_shower_head_type'=>'','espa_water_meter_qty'=>'','espa_water_meter_type'=>'','espa_grease_trap_qty'=>'','espa_grease_trap_type'=>'','espa_bath_tubs_qty'=>'','espa_bath_tubs_type'=>'','espa_slop_sink_qty'=>'','espa_slop_sink_type'=>'','espa_urinal_qty'=>'','espa_urinal_type'=>'','espa_airconditioning_unit_qty'=>'','espa_airconditioning_unit_type'=>'','espa_water_tank_qty'=>'','espa_water_tank_type'=>'','espa_bidette_qty'=>'','espa_bidettet_type'=>'','espa_laundry_trays_qty'=>'','espa_laundry_trays_type'=>'','espa_dental_cuspidor_qty'=>'','espa_dental_cuspidor_type'=>'','espa_gas_heater_qty'=>'','espa_gas_heater_type'=>'','espa_electric_heater_qty'=>'','espa_electric_heater_type'=>'','espa_water_boiler_qty'=>'','espa_water_boiler_type'=>'','espa_drinking_fountain_qty'=>'','espa_drinking_fountain_type'=>'','espa_bar_sink_qty'=>'','espa_bar_sink_type'=>'','espa_soda_fountain_qty'=>'','espa_soda_fountain_type'=>'','espa_laboratory_qty'=>'','espa_laboratory_type'=>'','espa_sterilizer_qty'=>'','espa_sterilizer_type'=>'','espa_swimmingpool_qty'=>'','espa_swimmingpool_type'=>'','espa_others_qty'=>'','espa_others_type'=>'','espa_others_category'=>'','ewst_id'=>'','edst_id'=>'','espa_no_of_storey'=>'','espa_floor_area'=>'','espa_installation_date'=>'','espa_installation_cost'=>'','espa_preparedby'=>'','espa_completion_date'=>'','espa_amount_due'=>'','espa_assessed_by'=>'','espa_or_no'=>'','espa_date_paid'=>'','espa_sign_category'=>'','espa_sign_consultant_id'=>'','espa_incharge_category'=>'','espa_incharge_consultant_id'=>'','espa_applicant_category'=>'','espa_applicant_consultant_id'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdcno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','espa_building_official'=>'');

            foreach((array)$sanitaryappdata as $key=>$val){
                $sanitaryappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                $sanitaryappdatacheck = $this->_engjobrequest->getEditDetailsSanitaryApp($request->input('jobrequest_id'));
                unset($sanitaryappdata['id']);
                $this->_engjobrequest->updateSanitaryAppData($request->input('application_id'),$sanitaryappdata);

                 if(empty($sanitaryappdatacheck->espa_building_official)){
                    $user_savedata = array();
                    $user_savedata['espa_building_official'] = $request->input('espa_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 17;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('17',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function saveelectricalcalculation(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'eef_total_load_kva'=>'required',
                'eef_total_ups'=>'required',
                'eef_pole_location_qty'=>'required',
                'eef_guying_attachment_qty' =>'required',
                'eefm_id'=>'required',
            ]
            ,
            [
                'eef_total_load_kva.required' => 'Required Field',
                'eef_total_ups.required'=>'Required',
                'eef_pole_location_qty.required'=>'Required',
                'eef_guying_attachment_qty.required'=>'Required',
                'eefm_id.required'=>'Required',
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


              $electicfeedata = array('eef_total_load_kva'=>'','eef_total_load_total_fees'=>'','eef_total_ups'=>'','eef_total_ups_total_fees'=>'','eef_pole_location_qty'=>'','eef_pole_location_total_fees'=>'','eef_guying_attachment_qty'=>'','eef_guying_attachment_fees'=>'','eefm_id'=>'','eef_electric_meter_fees'=>'','eef_wiring_permit_fees'=>'','eef_miscellaneous_tota_fees'=>'','eef_total_fees'=>'');

            foreach((array)$electicfeedata as $key=>$val){
                $electicfeedata[$key] = $request->input($key);
            }
            if($request->input('jobrequestid')>0){
                $electricfeeexist = $this->_engjobrequest->geteditdataElectricfee($request->input('jobrequestid'));
                    if(count($electricfeeexist) >0){
                        $this->_engjobrequest->UpdateElecticFeesData($request->input('jobrequestid'),$electicfeedata);
                    }else{
                        $electicfeedata['eef_jobrequestid']=$request->input('jobrequestid');
                        $electicfeedata['created_by'] = \Auth::user()->id;
                        $electicfeedata['created_at'] = date('Y-m-d H:i:s');
                        $electicfeedata['updated_at'] = date('Y-m-d H:i:s'); 
                        $this->_engjobrequest->AddElecticfessData($electicfeedata);
                    }

                $jobarray = array('ejr_totalfees'=>$electicfeedata['eef_total_fees']);
                //$this->_engjobrequest->updateData($request->input('jobrequestid'),$jobarray);
              
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);
    }

    public function savebuildingculation(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'ebpfd_id'=>'required',
                'ebpf_total_sqm'=>'required',
                'ebpf_total_fees'=>'required',
            ]
            ,
            [
                'ebpfd_id.required' => 'Required Field',
                'ebpf_total_sqm.required'=>'Required',
                'ebpf_total_fees.required'=>'Required',
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


              $buildingfeedata = array('ebpfd_id'=>'','ebpf_total_sqm'=>'','ebpf_total_fees'=>'');

            foreach((array)$buildingfeedata as $key=>$val){
                $buildingfeedata[$key] = $request->input($key);
            }
            if($request->input('jobrequestid')>0){
                $buildingfeeexist = $this->_engjobrequest->geteditdataBuildingfee($request->input('jobrequestid'));
                    if(count($buildingfeeexist) >0){
                        $this->_engjobrequest->UpdateBuildingFeesData($request->input('jobrequestid'),$buildingfeedata);
                    }else{
                        $buildingfeedata['ejr_id']=$request->input('jobrequestid');
                        $buildingfeedata['created_by'] = \Auth::user()->id;
                        $buildingfeedata['created_at'] = date('Y-m-d H:i:s');
                        $buildingfeedata['updated_at'] = date('Y-m-d H:i:s'); 
                        $this->_engjobrequest->AddBuildingfessData($buildingfeedata);
                    }

                $jobarray = array('ejr_totalfees'=>$buildingfeedata['ebpf_total_fees']);
                //$this->_engjobrequest->updateData($request->input('jobrequestid'),$jobarray);
              
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);
    }

    public function storeelectricpermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
                'eea_application_no'=>'required',
                'eea_building_official'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eea_application_no.required'=>'Required',
                'eea_building_official.required'=>'Required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


            $electricappdata = array('id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','eea_application_date'=>'','eea_issued_date'=>'','taxacctno'=>'','formofowner'=>'','kindbussiness'=>'','p_code'=>'','ebs_id'=>'','ebot_id'=>'','eeet_id'=>'','eea_date_of_construction'=>'','eea_estimated_cost'=>'','eea_date_of_completion'=>'','eea_prepared_by'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_amount_due'=>'','eea_assessed_by'=>'','eea_or_no'=>'','eea_date_paid'=>'','eea_building_official'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','signaddress'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','signprcregno'=>'','inchargenaddress'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','inchargeprcregno'=>'','ownertaxdcno'=>'','owneraddress'=>'','ownerstreet'=>'','ownersubdivision'=>'','ownermuncipality'=>'','ownertelephoneno'=>'','lotno'=>'','streetname'=>'','subdivision'=>'','ownerespa_location'=>'');

          foreach((array)$electricappdata as $key=>$val){
                $electricappdata[$key] = $request->input($key);
            }
            $idsofeeetid = implode(',',$request->input('eeet_id'));
            $electricappdata['eeet_id'] = $idsofeeetid;
            $electricappdatacheck = $this->_engjobrequest->getEditDetailsElecticApp($request->input('jobrequest_id'));
            //echo  $idsofeeetid; exit;
            if($request->input('application_id')>0){
                unset($electricappdata['id']);
                $this->_engjobrequest->updateElecticAppData($request->input('application_id'),$electricappdata);

                if(empty($electricappdatacheck->eea_building_official)){
                    $user_savedata = array();
                    $user_savedata['eea_building_official'] = $request->input('eea_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 12;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('12',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storeelectrronicpermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'eeta_building_official'=>'required',
            ]
            ,
            [
                'eeta_building_official'=>'required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


            $electronicsappdata = array('id'=>'','mum_no'=>'','eeta_year'=>'','eeta_series_no'=>'','eeta_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eeta_form_of_own'=>'','eeta_location'=>'','ebs_id'=>'','ebot_id'=>'','eest_id'=>'','eeta_sign_category'=>'','eeta_sign_consultant_id'=>'','eeta_incharge_category'=>'','eeta_incharge_consultant_id'=>'','eeta_applicant_category'=>'','eeta_applicant_consultant_id'=>'','eeta_owner_id'=>'','eeta_building_official'=>'','eeta_tax_acct_no'=>'','eeta_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');

          foreach((array)$electronicsappdata as $key=>$val){
                $electronicsappdata[$key] = $request->input($key);
            }
            $idsofeeetid = implode(',',$request->input('eest_id'));
            $electronicsappdata['eest_id'] = $idsofeeetid;
            if($request->input('application_id')>0){
                $electronicsappdatacheck = $this->_engjobrequest->getEditDetailsElectronicsApp($request->input('jobrequest_id'));
                unset($electronicsappdata['id']);
                $this->_engjobrequest->updateElectronicsAppData($request->input('application_id'),$electronicsappdata);

                 if(empty($electronicsappdatacheck->eeta_building_official)){
                    $user_savedata = array();
                    $user_savedata['eeta_building_official'] = $request->input('eeta_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 20;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('20',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storemechanicalpermit(Request $request){
          $validator = \Validator::make(
            $request->all(), [
                'ema_building_official'=>'required',
            ]
            ,
            [
                'ema_building_official'=>'required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


             $mechanicalappdata = array('id'=>'','mum_no'=>'','ema_year'=>'','ema_series_no'=>'','ema_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','ema_form_of_own'=>'','ema_location'=>'','ebs_id'=>'','ebot_id'=>'','eiot_id'=>'','ema_sign_category'=>'','ema_sign_consultant_id'=>'','ema_incharge_category'=>'','ema_incharge_consultant_id'=>'','ema_applicant_category'=>'','ema_applicant_consultant_id'=>'','ema_owner_id'=>'','ema_building_official'=>'','ema_tax_acct_no'=>'','ema_economic_act'=>'','lotno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','blkno'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');

          foreach((array)$mechanicalappdata as $key=>$val){
                $mechanicalappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                $mechanicalappdatacheck = $this->_engjobrequest->getEditDetailsMechanicalApp($request->input('jobrequest_id'));
                unset($mechanicalappdata['id']);
                $this->_engjobrequest->updateMechanicalAppData($request->input('application_id'),$mechanicalappdata);

                 if(empty($mechanicalappdatacheck->ema_building_official)){
                    $user_savedata = array();
                    $user_savedata['ema_building_official'] = $request->input('ema_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 18;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('18',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storeexcavationpermit(Request $request){
          $validator = \Validator::make(
            $request->all(), [
                'eega_building_official'=>'required',
            ]
            ,
            [
                'eega_building_official'=>'required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }

           $excavationappdata = array('id'=>'','mum_no'=>'','eega_year'=>'','eega_series_no'=>'','eega_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eega_tax_acct_no'=>'','eega_economic_act'=>'','eega_form_of_own'=>'','eega_location'=>'','ebs_id'=>'','ebot_id'=>'','eegt_id'=>'','eega_sign_category'=>'','eega_sign_consultant_id'=>'','eega_incharge_category'=>'','lotno'=>'','blkno'=>'','totno'=>'','tdno'=>'','Street'=>'','eega_incharge_consultant_id'=>'','eega_applicant_category'=>'','eega_applicant_consultant_id'=>'','eega_owner_id'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ctcoctno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','eega_building_official'=>'');

          foreach((array)$excavationappdata as $key=>$val){
                $excavationappdata[$key] = $request->input($key);
            }
            $idsofeeetid = implode(',',$request->input('eegt_id'));
            $excavationappdata['eegt_id'] = $idsofeeetid;
            if($request->input('application_id')>0){
                $excavationappdatacheck = $this->_engjobrequest->getEditDetailsExcavationApp($request->input('jobrequest_id'));
                unset($excavationappdata['id']);
                $this->_engjobrequest->updateExcavationAppData($request->input('application_id'),$excavationappdata);

                 if(empty($excavationappdatacheck->eega_building_official)){
                    $user_savedata = array();
                    $user_savedata['eega_building_official'] = $request->input('eega_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 19;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('19',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storearchitecturalpermit(Request $request){
         $validator = \Validator::make(
            $request->all(), [
                'eea_building_official'=>'required',],
            [ 'eea_building_official'=>'required',]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


           $architecturalappdata = array('id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eea_form_of_own'=>'','eea_location'=>'','ebs_id'=>'','ebot_id'=>'','eeft_id'=>'','eaa_footprint'=>'','eaa_impervious_area'=>'','eaa_unpaved_area'=>'','eaa_others_percentage'=>'','ectfc_id'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_building_official'=>'','eea_tax_acct_no'=>'','eea_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');

          foreach((array)$architecturalappdata as $key=>$val){
                $architecturalappdata[$key] = $request->input($key);
            }
            $idsofeeetid = implode(',',$request->input('eeft_id'));
            $architecturalappdata['eeft_id'] = $idsofeeetid;

            $idsofectfc_id = implode(',',$request->input('ectfc_id'));
            $architecturalappdata['ectfc_id'] = $idsofeeetid;
            if($request->input('application_id')>0){
                $architecturalappdatacheck = $this->_engjobrequest->getEditDetailsArchitecturalApp($request->input('jobrequest_id'));
                unset($architecturalappdata['id']);
                $this->_engjobrequest->updateArchitecturalAppData($request->input('application_id'),$architecturalappdata);

                 if(empty($architecturalappdatacheck->eea_building_official)){
                    $user_savedata = array();
                    $user_savedata['eea_building_official'] = $request->input('eea_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 22;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('22',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);
    }

    public function storefencingpermit(Request $request){
          $validator = \Validator::make(
            $request->all(), [
                'efa_building_official'=>'required',
            ]
            ,
            [
                'efa_building_official.required'=>'required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }

           $fencingappdata = array('id'=>'','mun_no'=>'','measurelength'=>'','measureheight'=>'','typeoffencing'=>'','efa_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','efa_form_of_own'=>'','ebpa_location'=>'','ebs_id'=>'','eft_id'=>'','efa_sign_category'=>'','efa_sign_consultant_id'=>'','efa_inspector_category'=>'','efa_inspector_consultant_id'=>'','efa_applicant_category'=>'','efa_applicant_consultant_id'=>'','efa_owner_id'=>'','efa_linegrade_amount'=>'','efa_linegrade_processed_by'=>'','efa_linegrade_or_no'=>'','efa_linegrade_date_paid'=>'','efa_fencing_amount'=>'','efa_fencing_processed_by'=>'','efa_fencing_or_no'=>'','efa_fencing_date_paid'=>'','efa_electrical_amount'=>'','efa_electrical_processed_by'=>'','efa_electrical_or_no'=>'','efa_electrical_date_paid'=>'','efa_others_amount'=>'','efa_others_processed_by'=>'','efa_others_or_no'=>'','efa_others_date_paid'=>'','efa_total_amount'=>'','efa_total_processed_by'=>'','efa_total_or_no'=>'','efa_total_date_paid'=>'','taxacctno'=>'','maineconomy'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','applicantnamenew'=>'','applicantaddressnew'=>'','ctcnonew'=>'','dateissuednew'=>'','placeissuednew'=>'','liancnedapplicant'=>'','liancnedaddress'=>'','liancnedctcno'=>'','liancneddateissued'=>'','liancnedplaceissued'=>'','efa_building_official'=>'');

          foreach((array)$fencingappdata as $key=>$val){
                $fencingappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                unset($fencingappdata['id']);
                 $fencingappdatacheck = $this->_engjobrequest->getEditDetailsFencingApp($request->input('jobrequest_id'));
                $this->_engjobrequest->updateFencingAppData($request->input('application_id'),$fencingappdata);

                 if(empty($fencingappdatacheck->efa_building_official)){
                    $user_savedata = array();
                    $user_savedata['efa_building_official'] = $request->input('efa_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 13;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('13',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);
    }


    public function storecivilpermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'mum_no'=>'required',
                'eca_application_no'=>'required',
                'eca_building_official'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eca_application_no.required'=>'Required',
                'eca_building_official.required'=>'Required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


           $civilappdata = array('id'=>'','mum_no'=>'','eca_year'=>'','eca_series_no'=>'','eca_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eca_form_of_own'=>'','ebs_id'=>'','ebot_id'=>'','eca_location'=>'','ecst_id'=>'','eca_sign_category'=>'','eca_sign_consultant_id'=>'','eca_incharge_category'=>'','eca_incharge_consultant_id'=>'','eca_applicant_category'=>'','eca_applicant_consultant_id'=>'','eca_owner_id'=>'','eca_building_official'=>'','eca_tax_acct_no'=>'','eca_economic_act'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ownerctcno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'');

          foreach((array)$civilappdata as $key=>$val){
                $civilappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                unset($civilappdata['id']);
                 $civilappdatacheck = $this->_engjobrequest->getEditDetailsCivilApp($request->input('jobrequest_id'));
                $this->_engjobrequest->updateCivilAppData($request->input('application_id'),$civilappdata);

                if(empty($civilappdatacheck->eca_building_official)){
                    $user_savedata = array();
                    $user_savedata['eca_building_official'] = $request->input('eca_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 21;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('21',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storesigngpermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'mun_no'=>'required',
                'esa_application_no'=>'required',
                'esa_building_official'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
                'esa_application_no.required'=>'Required',
                'esa_building_official.required'=>'Required',
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


           $signappdata = array('id'=>'','mun_no'=>'','esa_year'=>'','esa_series_no'=>'','esa_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','taxaccno'=>'','esa_form_of_own'=>'','ebpa_location'=>'','ebs_id'=>'','ebot_id'=>'','esdt_id'=>'','esit_id'=>'','esa_sign_category'=>'','esa_sign_consultant_id'=>'','esa_incharge_category'=>'','esa_incharge_consultant_id'=>'','esa_applicant_category'=>'','esa_applicant_consultant_id'=>'','esa_owner_id'=>'','esa_building_official'=>'','esa_economic_act'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','owner_comtaxcert'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdecno'=>'','Street'=>'','length'=>'','width'=>'','alllengthwidth'=>'');

          foreach((array)$signappdata as $key=>$val){
                $signappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                unset($signappdata['id']);
                $signappdatacheck = $this->_engjobrequest->getEditDetailsSignApp($request->input('jobrequest_id'));
                $this->_engjobrequest->updateSignAppData($request->input('application_id'),$signappdata);

                if(empty($signappdatacheck->esa_building_official)){
                    $user_savedata = array();
                    $user_savedata['esa_building_official'] = $request->input('esa_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 15;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('15',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

    public function storedemolitionpermit(Request $request){

          $validator = \Validator::make(
            $request->all(), [
                'mun_no'=>'required',
                'eda_application_no'=>'required',
                'eda_building_official'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
                'eda_application_no.required'=>'Required',
                'eda_building_official.required'=>'Required',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


           $demolitionnappdata = array('id'=>'','mun_no'=>'','eda_year'=>'','eda_series_no'=>'','eda_application_no'=>'','ebpa_permit_no'=>'','eda_economic_act'=>'','p_code'=>'','eda_tax_acct_no'=>'','loclotno'=>'','locblkno'=>'','loctotno'=>'','loctdno'=>'','locstreet'=>'','eda_form_of_own'=>'','eda_location'=>'','ebs_id'=>'','ebot_id'=>'','eda_sign_category'=>'','eda_sign_consultant_id'=>'','inchargeaddress'=>'','inchargeprcno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','eda_incharge_category'=>'','eda_incharge_consultant_id'=>'','eda_applicant_category'=>'','eda_applicant_consultant_id'=>'','applicantaddress'=>'','applicantctcno'=>'','applicantdateissued'=>'','applicantplaceissued'=>'','eda_owner_id'=>'','owneraddress'=>'','ownerctcno'=>'','ownerdateissued'=>'','ownerplaceissued'=>'','applicantnew'=>'','applicantaddressnew'=>'','ctcnonew'=>'','dateissuednew'=>'','placeissuednew'=>'','liancenedapplicant'=>'','liancenedctcno'=>'','lianceneddateissued'=>'','liancenedplaceissued'=>'','liancenedaddress'=>'','eda_building_official'=>'','ordateissued'=>'','orplaceissued'=>'');

          foreach((array)$demolitionnappdata as $key=>$val){
                $demolitionnappdata[$key] = $request->input($key);
            }
            if($request->input('application_id')>0){
                $demolitionnappdatacheck = $this->_engjobrequest->getEditDetailsDemolitionApp($request->input('jobrequest_id'));
                unset($demolitionnappdata['id']);
                $demolitionnappdata['created_at']=date('Y-m-d H:i:s');
                $demolitionnappdata['updated_at']=date('Y-m-d H:i:s');
                $this->_engjobrequest->updateDemolitionAppData($request->input('application_id'),$demolitionnappdata);
                if(empty($demolitionnappdatacheck->eda_building_official)){
                    $user_savedata = array();
                    $user_savedata['eda_building_official'] = $request->input('eda_building_official');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 11;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_engjobrequest->CheckFormdataExist('11',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_engjobrequest->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_engjobrequest->addusersaveData($userlastdata);
                    }
                }
            }
            $array = ["status"=>"success","message" =>"Data Saved Successfully."];
         echo json_encode($array);

    }

}
