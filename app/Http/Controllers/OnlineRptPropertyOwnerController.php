<?php

namespace App\Http\Controllers;
use App\Models\OnlineRptPropertyOwner;
use App\Models\OnlineBploBusiness;
use App\Models\BploBusiness;
use App\Models\Barangay;
use App\Models\CommonModelmaster;
use App\Models\RptProperty;
use App\Models\RptPropertyHistory;
use App\Models\RevisionYear;
use App\Models\ProfileMunicipality;
use App\Models\RptLocality;
use App\Models\Engneering\EngJobRequest;
use App\Models\Cpdo\CpdoDevelopmentPermit;
use App\Models\Engneering\EngOccupancyApp;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use File;
use Carbon\Carbon;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;
use DB;

class OnlineRptPropertyOwnerController extends Controller
{
    
    public $data = [];
    public $postdata = [];
    public $arrgetBrgyCode = array(""=>"Please Select");
    public $client = array(""=>"Please Select");
	public $arrgetClients = array(""=>"Please Select");
    public $arrgetCountries = array(""=>"Select");
    private $slugs;
    private $slugs2;
    public $activeRevisionYear = [];
    public $activeMuncipalityCode = [];
    public $approve_status = [
        '0' => 'Pending',
        '1' => 'Accepted',
        '2' => 'Declined',
    ];
    public function __construct(){
        $this->_engjobrequest= new EngJobRequest(); 
        $this->_barangay= new Barangay(); 
        $this->_cpdodevelopmentapp= new CpdoDevelopmentPermit(); 
        $this->_engoccupancyapp= new EngOccupancyApp(); 
        $this->_rptpropertyowner = new OnlineRptPropertyOwner();
        $this->_commonmodel = new CommonModelmaster();
        $this->_OnlineBploBusiness = new OnlineBploBusiness(); 
        $this->_BploBusiness = new BploBusiness(); 
        $this->_propertyHistory = new RptPropertyHistory;
        $this->_revisionyear = new RevisionYear;
        $this->_muncipality = new ProfileMunicipality;
        $this->data = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'');

        foreach ($this->_rptpropertyowner->getCountries() as $val) {
            $this->arrgetCountries[$val->id]=$val->nationality;
        }
        foreach ($this->_OnlineBploBusiness->getClientName() as $val) {
            $this->client[$val->id]=$val->full_name;
        }

        foreach ($this->_rptpropertyowner->getClientslist() as $val) {
           if($val->suffix){
            $this->arrgetClients[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name.", ".$val->suffix;
          }else{
              $this->arrgetClients[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name;
            }
          }
          $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
          $this->activeMuncipalityCode = $this->_muncipality->getActiveMuncipalityCode();
          $this->slugs = 'real-property-taxpayers-file';
          $this->slugs2 = 'real-property/property-owners';
    }

    public function index(Request $request)
    {
        $this->is_permitted($this->slugs2, 'read');
        $isopen=$request->input('isopenAddform');
        return view('onlineRptpropertyowner.index',compact('isopen'));
    }
    
    public function getList(Request $request){
        $data = $this->_rptpropertyowner->getList($request);
        $arr = array();
        $i = "0";
        $j = (int)$request->input('start') - 1;
        $j = $j > 0 ? $j + 1 : 0;
    
        foreach ($data['data'] as $row) {
            $j = $j + 1;
            $arr[$i]['no'] = $j;
            $status = $this->approve_status[$row->is_approved];
    
            $Address = '';
            if ($row->rpo_address_house_lot_no) {
                $Address .= $row->rpo_address_house_lot_no . ', ';
            }
    
            if ($row->rpo_address_street_name) {
                $Address .= $row->rpo_address_street_name . ', ';
            }
    
            if ($row->rpo_address_subdivision) {
                $Address .= $row->rpo_address_subdivision . ', ';
            }
    
            $Address .= $row->brgy_name . ', ' . $row->mun_desc . ', ' . $row->prov_desc . ', ' . $row->reg_region;
    
            $Address = rtrim($Address, ', ');
    
            if (empty($Address)) {
                $Address = $row->brgy_name . ', ' . $row->mun_desc . ', ' . $row->prov_desc . ', ' . $row->reg_region;
            }
            $Address=utf8_encode($Address);
            $arr[$i]['rpt_owner'] = utf8_encode($row->full_name);
            $addressnew = wordwrap($Address, 40);

            $arr[$i]['rpo_address_house_lot_no'] = "<div class='showLess'>" . $addressnew . "</div";
            $arr[$i]['p_mobile_no'] = $row->p_mobile_no;
            $arr[$i]['is_active'] = '';
            $arr[$i]['status'] = $status;
            $arr[$i]['email'] = $row->p_email_address;
            $arr[$i]['gender'] = ($row->gender == 0 ? 'Female' : 'Male');
            $startCarbon = Carbon::parse($row->created_at);
            $endCarbon = date('Y-m-d');
            $diff = $startCarbon->diff($endCarbon);
            if ($diff->days == 0) {
                $duration = '';
            } elseif ($diff->days == 1) {
                $duration = $diff->days . " Day";
            } else {
                $duration = $diff->days . " Days";
            }
            $arr[$i]['duration'] = $duration;
            $arr[$i]['status'] = $status;
            $arr[$i]['action'] = '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center view" data-url="' . url('/taxpayer-online-registration/store?id=' . $row->id) . '" data-ajax-popup="true"  data-size="xxl" data-bs-toggle="tooltip" title="View Details"  data-title="Manage Online Registration">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
            </div';
    
            $i++;
        }
    
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        //dd($json_data);
        $json_encoded = json_encode($json_data);
       // dd($json_encoded);
        if ($json_encoded === false) {
            // Check for JSON encoding errors
            $json_last_error = json_last_error();
            $json_last_error_msg = json_last_error_msg();
            echo "JSON encoding failed with error code $json_last_error: $json_last_error_msg";
        } else {
            echo $json_encoded;
        }
    }

    public function getBploBusinessList(Request $request){
        $data=$this->_BploBusiness->getListByClientId($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
           
            $serial_no = $sr_no;
            $owner = $row->client_id != NULL ? $row->rpo_first_name  ." ". $row->rpo_middle_name ." ". $row->rpo_custom_last_name : "";
            $application_date = Carbon::parse($row->application_date)->format('d-M-Y');
            $last_pay_date = !empty($row->last_pay_date) ? Carbon::parse($row->last_pay_date)->format('d-M-Y') : "";
            $barangay=$row->office_brgy_name != NULL ? $row->office_brgy_name . ', ' . $row->office_mun_desc . ', ' . $row->office_prov_desc . ', ' . $row->office_reg_region : "";
            $barangay_new = wordwrap($barangay, 40, "<br />\n");
           
 
            
             // dd($duration);
            if($row->app_type=='Retire'){
                 $serial_no='<strike style="color:red;">'.$serial_no.' </strike>';
                 $row->busns_id_no = '<strike style="color:red;">'.$row->busns_id_no.' </strike>';
                 $owner = '<strike style="color:red;">'.$owner.' </strike>';
                 $row->busn_name = '<strike style="color:red;">'.$row->busn_name.' </strike>';
                 $row->app_type = '<strike style="color:red;">'.$row->app_type.' </strike>';
                 $application_date = '<strike style="color:red;">'.$application_date.' </strike>';
                 $last_pay_date = '<strike style="color:red;">'.$last_pay_date.' </strike>';
                 $row->office_brgy_name = '<strike style="color:red;">'.$row->office_brgy_name.' </strike>';
                 $barangay_new = '<strike style="color:red;">'.$barangay_new.' </strike>';
                 $row->busn_app_method= '<strike style="color:red;">'.$row->busn_app_method.' </strike>';
            }
            $arr[$i]['srno']=$serial_no;
            $arr[$i]['busn_id_no']=$row->busns_id_no;
            $arr[$i]['owner']=$owner;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['app_type']=$row->app_type;
            $arr[$i]['app_date']= $application_date;
            $arr[$i]['last_pay_date']= $last_pay_date;
            $arr[$i]['app_method']=$row->busn_app_method;
            $arr[$i]['barangay']=$row->office_brgy_name;
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval($totalRecords),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function engjobrequestList(Request $request){
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
            if($row->es_id != 7){
                if($row->es_id == 1|| $row->es_id == 3 || $row->es_id == 4 || $row->es_id == 8 || $row->es_id == 10){
                    $status .='<div class="action-btn bg-info ms-2">
                        <a href="'.url('/engjobrequest/print-permit/'.$row->id).'" title="Print Job Request"  data-title="Print Job Request" target="_blank" class="mx-3 btn btn-sm print text-white">
                            <i class="ti-printer text-white"></i>
                        </a></div>';
                }else{
                   $status .='<div class="action-btn bg-info ms-2">
                        <a href="'.url('/engjobrequest/printpermit?id='.$row->id).'&serviceid='.$row->es_id.'" title="Print Job Request"  data-title="Print Job Request" target="_blank" class="mx-3 btn btn-sm print text-white">
                            <i class="ti-printer text-white"></i>
                        </a></div>'; 
                }
             
              }
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['jobreqno']=$row->ejr_jobrequest_no;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $arr[$i]['services']=$row->eat_module_desc;
            $arr[$i]['generated']=$row->created_at;
            $arr[$i]['appno']=$row->application_no;
            $arrPermitno = array(); $permitnumber ="";
            foreach ($this->_engjobrequest->GetBuildingpermitsall() as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            //print_r($arrPermitno); exit;
            if($row->es_id =='1'){
                $appid = $this->_engjobrequest->Getbidappid($row->id); 
                if(isset($appid)){
                    $permitnumber = $appid->ebpa_permit_no;
                }
                
            }else if($row->es_id =='2'){ 
                $appid = $this->_engjobrequest->getEditDetailsDemolitionApp($row->id); 
                if(!empty($appid)){
                    if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                  } 
                }
                
            }else if($row->es_id =='3'){ 
                $appid = $this->_engjobrequest->getEditDetailsSanitaryApp($row->id);
                if(!empty($appid)){ 
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }else if($row->es_id =='4'){ 
                $appid = $this->_engjobrequest->getEditDetailsFencingApp($row->id); 
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }else if($row->es_id =='5'){ 
                 $appid = $this->_engjobrequest->getEditDetailsExcavationApp($row->id); 
                 if(!empty($appid)){
                 if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }
            else if($row->es_id =='6'){ 
                $appid = $this->_engjobrequest->getEditDetailsElecticApp($row->id); 
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }
            else if($row->es_id=='8'){ 
                $appid = $this->_engjobrequest->getEditDetailsSignApp($row->id); 
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }
            else if($row->es_id =='9'){ 
                $appid = $this->_engjobrequest->getEditDetailsElectronicsApp($row->id); 
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }
            else if($row->es_id =='10'){ 
                $appid = $this->_engjobrequest->getEditDetailsMechanicalApp($row->id);
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                }  }
            }
            else if($row->es_id =='11'){ 
                $appid = $this->_engjobrequest->getEditDetailsCivilApp($row->id); 
                if(!empty($appid)){
                if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                } }
            }
            else if($row->es_id =='13'){ 
                $appid = $this->_engjobrequest->getEditDetailsArchitecturalApp($row->id);
                if(!empty($appid)){
               if($appid->ebpa_permit_no > 0){
                   $permitnumber = $arrPermitno[$appid->ebpa_permit_no]; 
                }  }
            }

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

            
            $arr[$i]['amount']=$row->ejr_totalfees;
            $arr[$i]['ornumber']=$orno;
            $arr[$i]['ordate']=$ordate;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engjobrequest/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Job Request">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status;
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
    public function engoccupancyappList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engoccupancyapp->getList($request);
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
            $arr[$i]['ebpa_id']=$row->ebpa_id;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $arr[$i]['eoa_application_type']="Occupancy Service";
            $arr[$i]['appno']=$row->eoa_application_no;
             $arr[$i]['topno'] ="";  $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_engoccupancyapp->checkTransexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_engoccupancyapp->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                  $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $arr[$i]['amount']=$row->eoa_total_fees;
            $arr[$i]['ornumber']=$orno;
            $arr[$i]['ordate']=$ordate;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engoccupancyapp/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Manage Occupancy Permit Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    <div class="action-btn bg-info ms-2">
                        <a href="'.route('engoccupancyapp.certificateoccupancyprint',['id'=>$row->id]).'" title="Print Occupancy" target="_blank" data-title="Print Occupancy" class="mx-3 btn btn-sm print align-items-center" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a></div>' 
              ;
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

    public function cpdodevelopmentappList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdodevelopmentapp->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
           $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['caf_control_no']=$row->cdp_control_no;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $arr[$i]['projectname']="";
            $arr[$i]['address']=$row->cdp_address;  
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            $arr[$i]['status']=config('constants.arrCpdoStatusDetails')[$row->csd_id]; 
            $arr[$i]['topno']=""; 
            $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                 $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $arr[$i]['amount']=$row->cdp_total_amount;              
            $arr[$i]['orno']=$orno; 
            $arr[$i]['ordate']=$ordate; 
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Planning &  Devt Application">
                        <i class="ti-pencil text-white"></i>
                    </a></div>';
                    if($row->csd_id >='2'){
                    $arr[$i]['action'] .='<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/inspectionreport?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Inspection Order"  data-title="Manage Planning &  Devt Inspection Order">
                            <i class="ti-eye text-white"></i>
                        </a></div>';
                    }
                    if(!empty($row->cirid) && $row->csd_id >='4'){
                        $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/certification?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Certificate"  data-title="Manage Planning &  Devt Certificate">
                            <i class="fa fa-certificate text-white"></i>
                        </a></div>';
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
    public function realPropertyList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_OnlineBploBusiness->getRptPropertyList($request);
        //dd($data);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;   
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $arr[$i]['taxpayer_name']=$row->full_name;
            $arr[$i]['barangay']=$row->brgy_name;
            $arr[$i]['pin']=$row->rp_pin_declaration_no;  
            $cctUnitNo = wordwrap($row->cctUnitNo, 30, "<br />\n");
            $arr[$i]['lot']="<div class='showLess'>".$cctUnitNo."</div>";
            $arr[$i]['class']=$row->propertyClass; 
            $arr[$i]['assessedValue']=$row->assessedValue;
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
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_rpt' => $is_activeinactive);
        $this->_rptpropertyowner->updateActiveInactive($id,$data);
    }
    
    public function getProfileDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertyowner->getProfileDetails($id);
		print_r($data);die;
        foreach ($this->_rptpropertyowner->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }
	 public function getClientsDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_rptpropertyowner->getClientsDetails($id);
        foreach ($this->_rptpropertyowner->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }
    
    public function store(Request $request){
        //dd($request->isMethod('post'));
        $data = (object)$this->data;

        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $client=$this->client;
        // If its in add
        $barangays = $this->_rptpropertyowner->getBarangay();
        foreach ($barangays['data'] as $val) {
            $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }
        $arrgetCountries =$this->arrgetCountries;
		$arrgetClients = $this->arrgetClients;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_rptpropertyowner->find($request->input('id'));
            $ref_client=DB::table('clients')->where('p_mobile_no',$data->p_mobile_no)
                                ->orWhere(DB::raw('LOWER(full_name)'), strtolower($data->full_name))
                                ->first();
            if(!empty($ref_client)) 
            {
                $ref_client_id = $ref_client->id;
            }else{
                $ref_client_id = 0;
            }                   
            foreach ($this->_rptpropertyowner->getBarangay($data->p_barangay_id_no)['data'] as $val) {
                $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
            
        }
	
        return view('onlineRptpropertyowner.create',compact('data','arrgetBrgyCode','arrgetCountries','arrgetClients','client','ref_client_id'));
    }

    

    public function approve(Request $request,$id){
        /*if($request->search != null){
            $validator = Validator::make($request->all(), [
                'p_email_address_ext' => 'required|email|unique:clients,p_email_address,' . $request->search,
                'p_mobile_no_ext' => 'required|unique:clients,p_mobile_no,' . $request->search,
            ], [
                'p_email_address_ext.unique' => 'Email id already exist.',
                'p_mobile_no_ext.unique' => 'Mobile no already exist.'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'p_email_address' => 'required|email|unique:clients,p_email_address',
                'p_mobile_no_ext' => 'required|unique:clients,p_mobile_no',
            ], [
                'p_email_address.unique' => 'Email id already exist.',
                'p_mobile_no_ext.unique' => 'Mobile no already exist.'
            ]);
        }*/
        if($request->search != null){
            $validator = Validator::make($request->all(), [
                'p_email_address_ext' => 'required|email',
                'p_mobile_no' => 'required',
            ], [
                'p_email_address.required' => 'Please enter email.',
                'p_email_address.email' => 'Please enter valid email.',
                'p_mobile_no.required' => 'Please enter Mobile no.'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'p_email_address' => 'required|email',
                'p_mobile_no' => 'required',
            ], [
                'p_email_address.required' => 'Please enter email.',
                'p_email_address.email' => 'Please enter valid email.',
                'p_mobile_no.required' => 'Please enter Mobile no.'
            ]);
        }

        
        
        if ($validator->fails()) {
            $arr=(array)$validator->errors();
            foreach ($arr as $key => $value) {
                if(!empty($value['p_email_address_ext'][0])){
                    return response()->json([
                        'msg' =>$value['p_email_address_ext'][0],
                        'success' => false
                    ]);
                }
                if(!empty($value['p_mobile_no_ext'][0])){
                    return response()->json([
                        'msg' =>$value['p_mobile_no_ext'][0],
                        'success' => false
                    ]);
                }
            }
        }
        $apv_res=$this->_rptpropertyowner->approve($request,$id);
        if($apv_res['success'] == true){
            return response()->json([
                'data' =>$apv_res['data'],
                'success' => true
            ]);
        }
        return response()->json([
            'msg' =>$apv_res['msg'], 
            'success' => false
        ]);
        
    }

    public function decline(Request $request,$id){
        if($request->remarks == null){
            return response()->json([
                'msg' =>"Remarks is required for decline",
                'success' => false
            ]);
        }
        $data=$this->_rptpropertyowner->decline($request,$id);
        return response()->json([
            'data' =>$data,
            'success' => true
        ]);
    }
    
    public function getClientDetails(Request $request,$id){
        
        $data=$this->_rptpropertyowner->getClientDetails($id);
        $barangayDesc = $this->_barangay->findDetails($data->p_barangay_id_no);
        $data->p_barangay_desc = $barangayDesc;
        return response()->json([
            'data' =>$data,
        ]);
    }
    
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptPropertyOwner = RptPropertyOwner::find($id);
            if($RptPropertyOwner->created_by == \Auth::user()->creatorId()){
                $RptPropertyOwner->delete();
            }
    }

    /* Real Property Tax Owner File */

    public function taxpayersindex(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        return view('realpropertytaxpayerfile.index');
         
    }

    public function rptopGetList(Request $request){
        $data=$this->_rptpropertyowner->getRptopList($request);
        //dd($data);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')+1; 
        foreach ($data['data'] as $row){
            $arr[$i]['no']= $j;
            if($row->rpo_address_house_lot_no){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.",".$row->reg_region; 
              }
              if($row->rpo_address_street_name){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.",".$row->mun_desc.",".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region; 
              }
              if($row->rpo_address_subdivision){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.",".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region; 
              }
            $arr[$i]['rpt_owner'] = $row->customername;
            $addressnew = wordwrap($Address, 40, "<br />\n");
            $arr[$i]['rpo_address_house_lot_no']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center addNewPropertyOwner" data-url="'.url('/taxpayer-online-registration/store?id='.$row->id).'" title="Edit"  data-title="Manage Property Owner">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showTaxDeclarationsDetails" data-url="'.url('/taxpayer-online-registration/show?id='.$row->id).'" title="RPTO"  data-title="Manage Property Owner">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>';
           
           
            $i++;
            $j++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function show(Request $request){
        //dd($this->activeRevisionYear);
        $propOwner = $request->id;
        $rptProperties = RptProperty::with(
            ['propertyKindDetails'=>function($query){
                                              $query->select(['id','pk_code','pk_description']);
                                         },
                                      
                                     ])
                                      ->select(['id','rp_tax_declaration_no','rp_pin_declaration_no','rp_property_code','rpo_code','rvy_revision_year_id','brgy_code_id','pk_id','rp_section_no','rp_pin_no','rp_td_no','pc_class_code'])
                                      ->where('rpo_code',$propOwner)
                                      ->where('is_deleted',0);
        $rptProperties = $rptProperties->get();
        $activeRevisionYear = $this->activeRevisionYear;
        $path = $request->path();
        if($path == 'rptpropertyowner/show'){
            return view('realpropertytaxpayerfile.show',compact('rptProperties','propOwner','activeRevisionYear'));
        }else{
            return $rptProperties;
        }
        
    }

    public function rptopLoadHistory(Request $request){
        $id = $request->id;
        $history = $this->_propertyHistory->with([
                'activeProp.revisionYearDetails',
                'cancelProp.revisionYearDetails',
                'activeProp.barangay',
                'cancelProp.barangay',
                'cancelProp.propertyOwner',
                'cancelProp.landAppraisals.actualUses'
            ])->where('rp_property_code',$id)->get();
        //dd($history);
        return view('realpropertytaxpayerfile.history',compact('history'));
    }



    public function printBill(Request $request){
        $this->_rptpropertyowner->eligibleToGenearteCoNumb($request);
        $rptProperties = $this->show($request);
        $controlNumber = DB::table('rpt_properties_assessment_notices')->where('rpo_code',$request->id)->orderBy('id','desc')->first();
        //dd($controlNumber);
        $propOwnerDetails = RptPropertyOwner::find($request->id);
        $activeRevisionYear = $this->activeRevisionYear;
        $RptLocality = RptLocality::where('mun_no',(isset($this->activeMuncipalityCode->id))?$this->activeMuncipalityCode->id:0)->where('department',1)->first();
        //dd($RptLocality);
        $data = [
                    'propOwnerDetails' => $propOwnerDetails, 
                    'rptProperties' => $rptProperties,
                    'controlNumber' => $controlNumber,
                    'activeRevisionYear' => $activeRevisionYear,
                    'RptLocality' => $RptLocality
                ];
                //dd($propOwnerDetails);
        $documentFileName = "assessment-list.pdf";
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);   
        $document->shrink_tables_to_fit = 0;  
        $document->AddPage('L','','','','',10,10,4,4,10,10);
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('realpropertytaxpayerfile.billofassessement', $data)->render();

        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
         
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = RptPropertyOwner::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/taxpayer-online-registration/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['doc_id'] = count($arrJson)+1;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->doc_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['doc_json'] = json_encode($finalJsone);
                $this->_rptpropertyowner->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['doc_json'],$healthCertId);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn ms-2'>
                                <a class='btn' href='".asset('uploads/taxpayer-online-registration').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function deleteAttachment(Request $request){
        $healthCertid = $request->input('healthCertid');
        $doc_id = $request->input('doc_id');
        $arrEndrosment = RptPropertyOwner::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/taxpayer-online-registration/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_rptpropertyowner->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
   
}
