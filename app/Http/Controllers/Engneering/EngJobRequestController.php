<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngJobRequest;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use File;
use DB;
use \Mpdf\Mpdf as PDF;
use Session;
use Carbon\Carbon;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;

class EngJobRequestController extends Controller
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
     private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
     private $carbon;
     public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_engjobrequest= new EngJobRequest(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->datafirst = array('id'=>'','ejr_jobrequest_no'=>'','Applicationtype'=>'','client_id'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_mobile_no'=>'','tfoc_id'=>'','es_id'=>'','location_brgy_id'=>'');  
        $this->data = array('id'=>'','ejr_jobrequest_no'=>'','client_id'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_mobile_no'=>'','tfoc_id'=>'','es_id'=>'','application_no'=>'','Applicationtype'=>'','ejr_project_name'=>'','ebfd_floor_area'=>'','ejr_firstfloorarea'=>'','ejr_secondfloorarea'=>'','ejr_lotarea'=>'','ejr_perimeter'=>'','ejr_projectcost'=>'','ejr_date_paid'=>'','ejr_total_net_amount'=>'','ejr_surcharge_fee'=>'','ejr_totalfees'=>'','location_brgy_id'=>'','zoning_cert_id'=>'','ejr_or_no'=>'','ordate'=>''); 

        $this->OrderPaymentdata = array('ejr_project_name'=>'','ebfd_floor_area'=>'','ejr_firstfloorarea'=>'','ejr_secondfloorarea'=>'','ejr_lotarea'=>'','ejr_perimeter'=>'','ejr_projectcost'=>'','ejr_totalfees'=>'');
        $this->slugs = 'engjobrequest'; 
        $this->appdata = array('id'=>'','ebpa_mun_no'=>'','ebpa_application_no'=>'','ebpa_permit_no'=>'','eba_id'=>'','ebpa_application_date'=>'','ebpa_issued_date'=>'','ebpa_owner_last_name'=>'','ebpa_owner_first_name'=>'','ebpa_owner_mid_name'=>'','ebpa_owner_suffix_name'=>'','ebpa_tax_acct_no'=>'','ebpa_form_of_own'=>'','ebpa_economic_act'=>'','ebpa_address_house_lot_no'=>'','ebpa_address_street_name'=>'','ebpa_address_subdivision'=>'','brgy_code'=>'','ebpa_location'=>'','ebs_id'=>'','ebpa_scope_remarks'=>'','no_of_units'=>'','ebot_id'=>'','ebost_id'=>'','ebpa_occ_other_remarks'=>'','ebpa_bldg_official_name'=>'');

        $this->engfeesdata = array('ebfd_bldg_est_cost' => '','ebfd_elec_est_cost'=>'','ebfd_plum_est_cost'=>'','ebfd_mech_est_cost'=>'','ebfd_other_est_cost'=>'','ebfd_total_est_cost'=>'','ebfd_equip_cost_1'=>'','ebfd_equip_cost_2'=>'','ebfd_equip_cost_3'=>'','ebfd_no_of_storey'=>'','ebfd_construction_date'=>'','ebfd_completion_date'=>'','ebfd_mats_const'=>'','ebfd_sign_category'=>'','ebfd_floor_area'=>'','ebfd_incharge_prc_reg_no'=>'','ebfd_sign_address_house_lot_no'=>'','ebfd_sign_address_street_name'=>'','ebfd_sign_address_subdivision'=>'','ebfd_sign_ptr_no'=>'','ebfd_incharge_ptr_no'=>'','ebfd_incharge_ptr_date_issued'=>'','ebfd_incharge_ptr_place_issued'=>'','ebfd_incharge_tan'=>'','ebfd_applicant_consultant_id'=>'','ebfd_applicant_date_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_consent_comtaxcert'=>'','ebfd_applicant_comtaxcert'=>'','ebpa_address_house_lotno'=>'','ebfd_applicant_date_issued'=>'','ebfd_applicant_place_issued'=>'','ebfd_consent_tctoct_no'=>'','ebfd_sign_prc_reg_no'=>'','ebfd_incharge_category'=>'','ebfd_incharge_consultant_id'=>'','ebfd_sign_consultant_id'=>'','ebfd_consent_id'=>'','ebfd_consent_comtaxcert'=>'','ebfd_incharge_address_house_lot_no'=>'');
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
    public function getEngOwnersAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engjobrequest->getEngOwnersAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

     public function getbildOfficialAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engjobrequest->getbildOfficialAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getPermitnoAjax(Request $request){
        $search = $request->input('search');
        $clientid = $request->input('cleintid');
        $arrRes = $this->_engjobrequest->getpermitnoAjax($search,$clientid);
      
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            if($val->ebpa_permit_no > 0){
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->ebpa_permit_no;
           }
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getExteranlsAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engjobrequest->getExteranlsAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function index(Request $request)
    {
           $this->is_permitted($this->slugs, 'read');
           $barangay=array(""=>"Please select"); 
           $services = $this->arrGetservices;
           $getmincipalityid = $this->_engjobrequest->getEngmunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_engjobrequest->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
            $methods = array(''=>'Select Method','1' => 'Online','0' => 'Walkin');
            $to_date=date('Y-m-d');
            $from_date=date('Y-m-d',strtotime('-1 month'));
                return view('Engneering.engjobrequest.index',compact('barangay','to_date','from_date','services','methods'));
    }

    public function getBarngayList(Request $request){
       $search = $request->input('search');
       $getmincipalityid = $this->_engjobrequest->getEngmunciapality(); $munid ="";
       if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;
        }
        $arrRes = $this->_engjobrequest->getBarangayforajax($search,$munid);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            //$arr['data'][$key]['text']=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
   }


    public function getsuboccupancytype(Request $request){
        $id= $request->input('occupancyid');  $subid =$request->input('subid');
        $subtype = $this->_engjobrequest->subtypeoccupancy($id);
        $html='<select name="ebost_id" required  class="form-control suboccupancydrop">';
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

    public function saveorderofpayment(Request $request){
         $id= $request->input('appid');
         $clientdata = $this->_engjobrequest->getclientidbyid($id);
         $tfocid= $request->input('tfocid');
         $amount= $request->input('amount');
         $serviceid =$request->input('serviceid'); 
         $data =array();
         if($serviceid =='1'){ $data['top_transaction_type_id'] = 6; } if($serviceid =='2'){ $data['top_transaction_type_id'] = 7; }
         if($serviceid =='3'){ $data['top_transaction_type_id'] = 13; } if($serviceid =='4'){ $data['top_transaction_type_id'] = 9; }
         if($serviceid =='5'){ $data['top_transaction_type_id'] = 15; } if($serviceid =='6'){ $data['top_transaction_type_id'] = 8; }
         if($serviceid =='8'){ $data['top_transaction_type_id'] = 11; } if($serviceid =='7'){ $data['top_transaction_type_id'] = 12; }
         if($serviceid =='9'){ $data['top_transaction_type_id'] = 16; }
         if($serviceid =='10'){ $data['top_transaction_type_id'] = 14; } if($serviceid =='11'){ $data['top_transaction_type_id'] = 17; } if($serviceid =='13'){ $data['top_transaction_type_id'] = 18; }
         $data['tfoc_is_applicable'] = '3';
         $data['transaction_ref_no'] = $id;
         $data['tfoc_id'] = $tfocid;
         $amount = str_replace(",", "", $amount);
         $data['amount'] = $amount;
         //echo $id; echo $data['top_transaction_type_id'];exit;
         $filename = $this->PrintorderFile($id,$serviceid);
         $checkidexist = $this->_engjobrequest->checkTransactionexist($id,$data['top_transaction_type_id']);
         if(count($checkidexist)> 0){
            $appuptdata = array('amount'=>$amount,'attachment'=>$filename);
            $this->_engjobrequest->TransactionupdateData($checkidexist[0]->id,$appuptdata);
            $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$checkidexist[0]->transaction_no,'transid'=>$checkidexist[0]->id];
            $appuptdata = array('top_transaction_type_id'=>$data['top_transaction_type_id'],'ejr_opd_created_by'=>\Auth::user()->id);
                $this->_engjobrequest->updateData($id,$appuptdata);
         }else{
            $data['created_at'] = date('Y-m-d H:i:s');
            $lastinsert =$this->_engjobrequest->TransactionaddData($data);
                $transactionno = str_pad($lastinsert, 6, '0', STR_PAD_LEFT);
                $updatedata = array('transaction_no'=>$transactionno,'attachment'=>$filename);
                $this->_engjobrequest->TransactionupdateData($lastinsert,$updatedata);
                
                $appuptdata = array('top_transaction_type_id'=>$data['top_transaction_type_id'],'ejr_or_no'=>$transactionno,'ejr_opd_created_by'=>\Auth::user()->id);
                $this->_engjobrequest->updateData($id,$appuptdata);
                $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$transactionno,'transid'=>$lastinsert];
         }
        $updateremotedata = array();
        $updateremotedata['cashieramount'] = $amount;
        $this->_engjobrequest->updateremotedata($request->input('appid'),$updateremotedata); 
         echo json_encode($array);
    }
    
       public function storeEngbillSummary(Request $request){
       //if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
          $id = $request->input('appid'); 
          $transactionno =  $request->input('transactionno');
          $arrTran = $this->_engjobrequest->getBillDetails($transactionno,$id);
          //print_r($arrTran); exit;
          if(isset($arrTran)){ 
             $billsaummary = array();
             $billsaummary['jobrequest_id'] = $id; 
             $billsaummary['client_id'] = $arrTran->client_id;
             $billsaummary['bill_year'] = date('Y');
             $billsaummary['bill_month'] = date('m');
             $billsaummary['total_amount'] = $arrTran->amount;
             $billsaummary['pm_id'] = 1;
             $billsaummary['attachement'] = $arrTran->attachment;
             $billsaummary['transaction_no'] = $arrTran->transaction_no;

          //This is for Main Server
            $arrBill = DB::table('engineering_bill_summary')->select('id')->where('jobrequest_id',$id)->where('transaction_no',$arrTran->transaction_no)->first();
            if(isset($arrBill)){
                DB::table('engineering_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
            }else{
                 $billsaummary['created_by'] = \Auth::user()->id;
                 $billsaummary['created_at'] = date('Y-m-d H:i:s');
                $this->_engjobrequest->insertbillsummary($billsaummary);
            }

            // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/engineering/'.$arrTran->attachment;
                $fileContents = file_get_contents($destinationPath);
                $remotePath = 'public/uploads/billing/engineering/'.$arrTran->attachment;
                Storage::disk('remote')->put($remotePath, $fileContents);
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('engineering_bill_summary')->select('id')->where('jobrequest_id',$id)->where('transaction_no',$arrTran->transaction_no)->first();

                try {
                    if(isset($arrBill)){
                        $remortServer->table('engineering_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
                    }else{
                        $billsaummary['created_by'] =  \Auth::user()->id;
                        $billsaummary['created_at'] =  date('Y-m-d H:i:s');
                       $this->_engjobrequest->insertbillsummaryremote($billsaummary);
                    }
                    DB::table('engineering_bill_summary')->where('jobrequest_id',$id)->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
                    unlink($destinationPath);
                }catch (\Throwable $error) {
                    return $error;
                }  
                echo "Done";
            } 
        //}

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
            if($row->es_id != 7){
                if($row->es_id == 1|| $row->es_id == 3 || $row->es_id == 4 || $row->es_id == 8 || $row->es_id == 10){
                    $status .='<div class="action-btn bg-info ms-2">
                        <a href="'.url('/engjobrequest/print-permit/'.$row->id).'" title="Print Job Request"  data-title="Print Job Request" target="_blank" class="mx-3 btn btn-sm print text-white digital-sign-btn">
                            <i class="ti-printer text-white"></i>
                        </a></div>';
                }else{
                    if($row->is_approve ==1){
                   $status .='<div class="action-btn bg-info ms-2">
                        <a href="'.url('/engjobrequest/printpermit?id='.$row->id).'&serviceid='.$row->es_id.'" title="Print Job Request"  data-title="Print Job Request" target="_blank" class="mx-3 btn btn-sm print text-white digital-sign-btn">
                            <i class="ti-printer text-white"></i>
                        </a></div>'; 
                    }
                }
             
              }
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['jobreqno']=$row->ejr_jobrequest_no;
            $barngayName = "";
            $barngayAddress = $this->_commonmodel->getBarangayname($row->location_brgy_id); 
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['locbarangay']= $barngayName;
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

            
            $arr[$i]['amount']=number_format($row->ejr_totalfees,2);
            $arr[$i]['ornumber']=$orno;
            $arr[$i]['ordate']=$ordate;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['is_online']=($row->is_online == 0)? 'Walkin':'Online';                 
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

    public function getbuildingpermitdetails(Request $request){
        $id= $request->input('permitno');
        $data = $this->_engjobrequest->getPermitnoDetails($id);
        if(!empty($data)){
        $data->location =""; $data->address ="";
        if(!empty($data->rpo_address_house_lot_no)){
            $data->address = $data->rpo_address_house_lot_no.", ";
        }
        if(!empty($data->rpo_address_street_name)){
            $data->address .= $data->rpo_address_street_name.", ";
        }
        if(!empty($data->rpo_address_subdivision)){
            $data->address .= $data->rpo_address_subdivision.", ";
        }
         foreach ($this->_commonmodel->getBarangaybyid($data->p_barangay_id_no)['data'] as $val){
                    $data->address.=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
        $locationAddress = $this->_commonmodel->getBarangayname($data->location_brgy_id);  
        if(!empty($locationAddress)){
               $data->location = $locationAddress->brgy_name;
            }  
        } 
        echo json_encode($data); 
    }

    public function getApplicationType(Request $request){
           $id= $request->input('es_id');
           $data = $this->_engjobrequest->getapptypeUsingtfocid($id);
           if(count($data)>0){ echo $data[0]->eat_module_desc."#".$data[0]->tfoc_id."#".$data[0]->is_flcno_required; }
        
    }

    public function getZoninginfo(Request $request){
        $certid = $request->input('certid');
        $data = $this->_engjobrequest->getzoningdetails($certid);
         echo json_encode($data);
    }

    public function getbarngaybyfalcno(Request $request){
        $certno =$request->input('certid'); 
        $data = $this->_engjobrequest->getbarangaybyfalcno($certno);
        $htmloption = "";
          foreach ($data as $key => $value) {
            $htmloption ='<option value="'.$value->id.'">'.$value->brgy_name.'</option>';
          }
      echo $htmloption;
    }

    public function getFalcnobycleint(Request $request){
        $clientid = $request->input('cleintid');
         $isrefrence =$request->input('isrefrence'); 
            $getConslutants = $this->_engjobrequest->getFalcnobycleint($clientid,$isrefrence);
          $htmloption ='<option value="">Select Select</option>';
          foreach ($getConslutants as $key => $value) {
            $htmloption .='<option value="'.$value->id.'">'.$value->cc_falc_no.'</option>';
          }
      echo $htmloption;
    }

    public function getFalcnobyAjax(Request $request){
        $search = $request->input('search');
         $clientid = $request->input('cleintid');
         $isrefrence =$request->input('isrefrence'); 
        $arrRes = $this->_engjobrequest->getFalcnobyAjax($search,$clientid,$isrefrence);
      
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->cc_falc_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getApplicant(Request $request){
        $applid= $request->input('id');
        $data = $this->_engjobrequest->getTaxcertificatedetails($applid);
		$datanew['address'] = (!empty($data->rpo_address_house_lot_no) ? $data->rpo_address_house_lot_no. ', ' : '') . (!empty($data->rpo_address_street_name) ? $data->rpo_address_street_name. ', ' : '') . (!empty($data->rpo_address_subdivision) ? $data->rpo_address_subdivision . '' : '');
        if(!empty($data->p_barangay_id_no)){
         foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            if($datanew['address'] !=""){ $datanew['address'] .= ', ';}
                 $datanew['address'] .=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
        }
        $datanew['or_no'] =""; $datanew['created_at']="";$datanew['cashier_batch_no']="";$datanew['ctc_place_of_issuance']="";
        if(!empty($data->or_no)){$datanew['or_no'] = $data->or_no;}
		if(!empty($data->created_at)){$datanew['created_at'] = $data->created_at;}
		if(!empty($data->cashier_batch_no)){$datanew['cashier_batch_no'] = $data->cashier_batch_no;}
		if(!empty($data->ctc_place_of_issuance)){$datanew['ctc_place_of_issuance'] = $data->ctc_place_of_issuance;}
        $datanew = (object)$datanew;
        echo json_encode($datanew);
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

            $datanew['address'] = (!empty($data->c_house_lot_no) ? $data->c_house_lot_no. ', ' : '') . (!empty($data->c_street_name) ? $data->c_street_name. ', ' : '') . (!empty($data->c_subdivision) ? $data->c_subdivision . '' : '');
            foreach ($this->_commonmodel->getBarangay($data->barangay_id)['data'] as $val) {
                if($datanew['address'] !=""){ $datanew['address'] .= ', ';}
                 $datanew['address'] .=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
            $datanew['prcno'] = $data->emp_prc_no;
            $datanew['ptrno'] = $data->emp_ptr_no;
            $datanew['tinno'] = $data->tin_no;
            $datanew['issueddate'] = $data->emp_issue_date;
            $datanew['issuedplace'] = $data->emp_issue_at;
            $datanew['validity'] = $data->emp_prc_validity;
        }else{
           $data = $this->_engjobrequest->getExternalDetails($signid);
            $datanew['address'] = (!empty($data->house_lot_no) ? $data->house_lot_no. ', ' : '') . (!empty($data->street_name) ? $data->street_name. ', ' : '') . (!empty($data->subdivision) ? $data->subdivision . '' : '');
            foreach ($this->_commonmodel->getBarangay($data->brgy_code)['data'] as $val) {
                if($datanew['address'] !=""){ $datanew['address'] .= ', ';}
                 $datanew['address'] .=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                
            }
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
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;padding-right: 35px;">
                     <div class="form-group">
                        <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_requiremets" style="padding: 5px 8px;"><i class="ti-trash"></i></button>
                    </div></div></div>';
               $reqhtml .= '</div>';
           }
           echo $reqhtml; exit;
    }

    public function getConsultants(Request $request){
            $id =$request->input('signcatid'); 
            if($id =='1'){
				$htmloption ='<option value="">Select Employee</option>';
				$getConslutants = $this->_engjobrequest->gethremployess();
			}else{ 
				$htmloption ='<option value="">Select Consultant</option>';
				$getConslutants = $this->_engjobrequest->getExteranls(); 
			}
            
		  
		  foreach ($getConslutants as $key => $value) {
			$htmloption .='<option value="'.$value->id.'">'.$value->fullname.'</option>';
		  }
      echo $htmloption;
    }

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
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
        $view = view('Engneering.engjobrequest.ajax.addservice',compact('arrGetservices'))->render();

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
         $view = view('Engneering.engjobrequest.ajax.electricalrevision',compact('electicfeedata','miscellaneousfees','jobserviceid'))->render();
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
         $view = view('Engneering.engjobrequest.ajax.buildingrevision',compact('buildingfeedata','buildingdivision','jobserviceid','floorarea'))->render();
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
         $totalamount = 0;   $request_id = $request->input('request_id');
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
            $totalamount =  $floorarea * $getsetdata->ebpfs4_fees;
         }
         if($geset->ebpfd_feessetid =='3'){
            $getsetdata = $this->_engjobrequest->getdataset3($floorarea);
            $dataset = $this->_engjobrequest->getdataset3data($getsetdata->ebpfs3_id);
            $floorarearun = $floorarea;
            //print_r($dataset); exit;
            foreach ($dataset as $key => $value) {
                if($floorarearun >= $value->ebpfs3_range_to){
                    $diff = abs($value->ebpfs3_range_to - $value->ebpfs3_range_from);
                    $amount = $diff * $value->ebpfs3_fees;
                }else{
                    $diff = abs($floorarearun - $value->ebpfs3_range_from);
                    
                     $amount = $diff * $value->ebpfs3_fees;
                }
               $totalamount = $totalamount + $amount; 
            }
         }
         if($geset->ebpfd_feessetid =='4'){
            $getsetdata = $this->_engjobrequest->getdataset4($floorarea);
            $dataset = $this->_engjobrequest->getdataset4data($getsetdata->ebpfs4_id);
            $floorarearun = $floorarea;
            //print_r($dataset); exit;
            foreach ($dataset as $key => $value) {
                if($floorarearun >= $value->ebpfs4_range_to){
                    $diff = abs($value->ebpfs4_range_to - $value->ebpfs4_range_from);
                    $amount = $diff * $value->ebpfs4_fees;
                }else{
                    $diff = abs($floorarearun - $value->ebpfs4_range_from);
                    
                     $amount = $diff * $value->ebpfs4_fees;
                }
               $totalamount = $totalamount + $amount; 
            }
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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
            $signdropdown = $this->hremployees;
            $inchargedropdown = $this->hremployees;
            $arrbuildingScope = $this->arrbuildingScope;
            $jobservice = [];
            $sessionId = '';
            $JobRequest = [];
            $appdata->ebpa_application_date = date('Y-m-d');
            $getConslutants = $this->_engjobrequest->getExteranls();
            $consultant =array();
            foreach ($getConslutants as $key => $value) {
                 $consultant[$value->id] = $value->fullname;
            }
            $clientid =$this->_engjobrequest->getClientidJobrequest($request->input('request_id'));

            $pcode = $clientid[0]->client_id;
            $clientdetls = $this->_engjobrequest->getclientname($pcode);
            if($request->has('request_id') && $request->request_id != ''){
               $appdata = $this->_engjobrequest->getEditDetailsbldApp($request->input('request_id'));
                foreach ($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                    $appdata->ebpa_location=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                    $appdata->municipality = $val->mun_desc;
                }
               //echo "<pre>"; print_r($appdata); exit;
               if(empty($appdata->ebpa_owner_first_name)){ 
                $appdata->ebpa_owner_first_name = $clientdetls->rpo_first_name;
                $appdata->ebpa_owner_mid_name = $clientdetls->rpo_middle_name;
                $appdata->ebpa_owner_last_name = $clientdetls->rpo_custom_last_name;
                $appdata->ebpa_owner_suffix_name = $clientdetls->suffix;
               }
               if($appdata->ebpa_application_date=='0000-00-00'){
                $appdata->ebpa_application_date = date('Y-m-d');
               }
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
       
        $view = view('Engneering.engjobrequest.ajax.buildingpermit',compact('arrGetservices','appdata','arrTypeofOccupancy','engfeesdata','arrApptype','EngAssessdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','signdropdown','inchargedropdown','pcode','arrbuildingScope','buildofficial'))->render();

        echo $view;
    }

    public function showsanitarypermitform(Request $request){
            $sanitaryappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','espa_year'=>'','espa_series_no'=>'','espa_application_no'=>'','ebpa_permit_no'=>'','espa_application_date'=>'','espa_issued_date'=>'','p_code'=>'','taxacctno'=>'','formofowner'=>'','maineconomy'=>'','espa_location'=>'','ebs_id'=>'','ebsa_scope_remarks'=>'','ebot_id'=>'','otheroccupancy'=>'','espa_water_closet_qty'=>'','espa_water_closet_type'=>'','espa_floor_drain_qty'=>'','espa_floor_drain_type'=>'','espa_lavatories_qty'=>'','espa_lavatories_type'=>'','espa_kitchen_sink_qty'=>'','espa_kitchen_sink_type'=>'','espa_faucet_qty'=>'','espa_faucet_type'=>'','espa_shower_head_qty'=>'','espa_shower_head_type'=>'','espa_water_meter_qty'=>'','espa_water_meter_type'=>'','espa_grease_trap_qty'=>'','espa_grease_trap_type'=>'','espa_bath_tubs_qty'=>'','espa_bath_tubs_type'=>'','espa_slop_sink_qty'=>'','espa_slop_sink_type'=>'','espa_urinal_qty'=>'','espa_urinal_type'=>'','espa_airconditioning_unit_qty'=>'','espa_airconditioning_unit_type'=>'','espa_water_tank_qty'=>'','espa_water_tank_type'=>'','espa_bidette_qty'=>'','espa_bidettet_type'=>'','espa_laundry_trays_qty'=>'','espa_laundry_trays_type'=>'','espa_dental_cuspidor_qty'=>'','espa_dental_cuspidor_type'=>'','espa_gas_heater_qty'=>'','espa_gas_heater_type'=>'','espa_electric_heater_qty'=>'','espa_electric_heater_type'=>'','espa_water_boiler_qty'=>'','espa_water_boiler_type'=>'','espa_drinking_fountain_qty'=>'','espa_drinking_fountain_type'=>'','espa_bar_sink_qty'=>'','espa_bar_sink_type'=>'','espa_soda_fountain_qty'=>'','espa_soda_fountain_type'=>'','espa_laboratory_qty'=>'','espa_laboratory_type'=>'','espa_sterilizer_qty'=>'','espa_sterilizer_type'=>'','espa_swimmingpool_qty'=>'','espa_swimmingpool_type'=>'','espa_others_qty'=>'','espa_others_type'=>'','espa_others_category'=>'','ewst_id'=>'','edst_id'=>'','espa_no_of_storey'=>'','espa_floor_area'=>'','espa_installation_date'=>'','espa_installation_cost'=>'','espa_completion_date'=>'','espa_preparedby'=>'','espa_amount_due'=>'','espa_assessed_by'=>'','espa_or_no'=>'','espa_date_paid'=>'','espa_sign_category'=>'','espa_sign_consultant_id'=>'','espa_incharge_category'=>'','espa_incharge_consultant_id'=>'','espa_applicant_category'=>'','espa_applicant_consultant_id'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','lotno'=>'','blkno'=>'','totno'=>'','taxdcno'=>'','Street'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','espa_building_official'=>'');
            $sanitaryappdata = (object)$sanitaryappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
               $sanitaryappdata->locbarangay = ""; $sanitaryappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $sanitaryappdata->locbarangay = $val->brgy_name;
                            $sanitaryappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $sanitaryappdata->espa_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.sanitarypermit',compact('sanitaryappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','waterSupplyarray','disposalarray','pcode','signdropdown','inchargedropdown','arrscopeofwork','arrTypeofOccupancy','arrPermitno','buildofficial'))->render();

        echo $view;
    }

     public function showelectricpermitform(Request $request){
            $electricappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','eea_application_date'=>'','eea_issued_date'=>'','taxacctno'=>'','formofowner'=>'','kindbussiness'=>'','p_code'=>'','ebs_id'=>'','ebot_id'=>'','eeet_id'=>'','eea_date_of_construction'=>'','eea_estimated_cost'=>'','eea_date_of_completion'=>'','eea_prepared_by'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_amount_due'=>'','eea_assessed_by'=>'','eea_or_no'=>'','eea_date_paid'=>'','eea_building_official'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','signaddress'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','signprcregno'=>'','inchargenaddress'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','inchargeprcregno'=>'','ownertaxdcno'=>'','owneraddress'=>'','ownerstreet'=>'','ownersubdivision'=>'','ownermuncipality'=>'','ownertelephoneno'=>'','lotno'=>'','taxdecno'=>'','totno'=>'','blkno'=>'','streetname'=>'','subdivision'=>'','ownerespa_location'=>'');
            $electricappdata = (object)$electricappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
                $electricappdata->eea_location = ""; 
                 foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $electricappdata->eea_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.electricpermit',compact('electricappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','electicequipmentarray','pcode','signdropdown','inchargedropdown','arrscopeofwork','arrPermitno','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
               $civilappdata->locbarangay = ""; $civilappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $civilappdata->locbarangay = $val->brgy_name;
                            $civilappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $civilappdata->eca_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.civilpermit',compact('civilappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','buildofficial'))->render();
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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
               $electronicsappdata->locbarangay = ""; $electronicsappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $electronicsappdata->locbarangay = $val->brgy_name;
                            $electronicsappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $electronicsappdata->eeta_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.electronicspermit',compact('electronicsappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','electronicequipmentarray','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
               $mechanicalappdata->locbarangay = ""; $mechanicalappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $mechanicalappdata->locbarangay = $val->brgy_name;
                            $mechanicalappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $mechanicalappdata->ema_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.mechanicalpermit',compact('mechanicalappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','instllationtypearray','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','buildofficial'))->render();

        echo $view;
    }

    public function showexcavationpermitform(Request $request){
         $excavationappdata = array('id'=>'','ejr_id'=>'','mum_no'=>'','eega_year'=>'','eega_series_no'=>'','eega_application_no'=>'','ebpa_permit_no'=>'','p_code'=>'','eega_form_of_own'=>'','eega_tax_acct_no'=>'','eega_economic_act'=>'','eega_location'=>'','lotno'=>'','blkno'=>'','totno'=>'','tdno'=>'','Street'=>'','ebs_id'=>'','ebot_id'=>'','eegt_id'=>'','eega_sign_category'=>'','eega_sign_consultant_id'=>'','eega_incharge_category'=>'','eega_incharge_consultant_id'=>'','eega_applicant_category'=>'','eega_applicant_consultant_id'=>'','eega_owner_id'=>'','signaddress'=>'','signprcno'=>'','signvalidity'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','inchargenaddress'=>'','inchargeprcregno'=>'','inchargevalidity'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','applicantaddress'=>'','applicant_comtaxcert'=>'','applicant_date_issued'=>'','applicant_place_issued'=>'','owneraddress'=>'','ctcoctno'=>'','owner_date_issued'=>'','ownerplaceissued'=>'','eega_building_official'=>'');
            $excavationappdata = (object)$excavationappdata;
            $arrgetBrgyCode = $this->arrgetBrgyCode;
            $GetMuncipalities =$this->GetMuncipalities;
            $arrlotOwner =$this->arrlotOwner;
            $hremployees =$this->hremployees;
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
               $excavationappdata->locbarangay = ""; $excavationappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $excavationappdata->locbarangay = $val->brgy_name;
                            $excavationappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $excavationappdata->eega_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.excavationpermit',compact('excavationappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','excavationgroundtypearray','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
                $architecturalappdata->locbarangay = ""; $architecturalappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $architecturalappdata->locbarangay = $val->brgy_name;
                            $architecturalappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $architecturalappdata->eea_location = $val->mun_desc;
                    } 
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
                          if(!empty($usersaved)){
                          $architecturalappdata->eea_building_official = $usersaved->eea_building_official;
                          }
                         } 
                       }
            }
            if($request->has('jobrequest_id') && $request->jobrequest_id != 0){
                //$propertyCode = $this->_rptproperty->getSinglePropertyDetails($request->property_id);
            }
       
        $view = view('Engneering.engjobrequest.ajax.architecturalpermit',compact('architecturalappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrTypeofOccupancy','pcode','signdropdown','inchargedropdown','arrbuildingScope','arrPermitno','architecturefeaturetypearray','confirmancefirearray','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
                $fencingappdata->locbarangay = ""; $fencingappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $fencingappdata->locbarangay = $val->brgy_name;
                            $fencingappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $fencingappdata->ebpa_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.fencingpermit',compact('fencingappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','pcode','signdropdown','inchargedropdown','arrPermitno','arrtypeofFencing','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
                $signappdata->locbarangay = ""; $signappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                            $signappdata->locbarangay = $val->brgy_name;
                            $signappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                            $signappdata->ebpa_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.signpermit',compact('signappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','arrTypeofOccupancy','arrsigndisplaytype','arrsignInstllationtype','pcode','signdropdown','inchargedropdown','arrPermitno','buildofficial'))->render();

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
            $buildofficial = array(""=>"Please Select");
            foreach ($this->_engjobrequest->getbuidingofficial() as $val){
             $buildofficial[$val->id]=$val->fullname;
            } 
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
            foreach ($this->_engjobrequest->GetBuildingpermitsdemolition($pcode) as $val) {
             $arrPermitno[$val->id]=$val->ebpa_permit_no;
            }
            if($request->has('request_id') && $request->request_id != ''){
               $demolitionnappdata = $this->_engjobrequest->getEditDetailsDemolitionApp($request->input('request_id'));
               $demolitionnappdata->locbarangay = ""; $demolitionnappdata->locmunicipality ="";
                foreach($this->_commonmodel->getBarangaybyid($clientid[0]->location_brgy_id)['data'] as $val){
                        $demolitionnappdata->locbarangay = $val->brgy_name;
                        $demolitionnappdata->locmunicipality = $val->mun_desc;
                    }
                   foreach($this->_commonmodel->getBarangaybyid($clientid[0]->brgy_code)['data'] as $val){
                        $demolitionnappdata->eda_location = $val->mun_desc;
                    } 
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
       
        $view = view('Engneering.engjobrequest.ajax.demolitionpermit',compact('demolitionnappdata','arrgetBrgyCode','GetMuncipalities','arrlotOwner','hremployees','arrbuildingScope','arrTypeofOccupancy','arrsigndisplaytype','arrsignInstllationtype','pcode','signdropdown','inchargedropdown','arrPermitno','buildofficial'))->render();

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

    public function UpdatePermitIssued(Request $request){
        $releaseupdate =array('ejr_permit_released_by'=>\Auth::user()->id,'ejr_is_permit_released'=>'1','ejr_permit_released_date_time'=>date('Y-m-d H:i:s'));
        $this->_engjobrequest->updateData($request->input('id'),$releaseupdate);
        if($request->input('serviceid') =='1'){
             $smsTemplate=SmsTemplate::where('id',25)->where('is_active',1)->first();
         }else if($request->input('serviceid') =='2'){
           $smsTemplate=SmsTemplate::where('id',27)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='3'){
           $smsTemplate=SmsTemplate::where('id',37)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='4'){
           $smsTemplate=SmsTemplate::where('id',31)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='5'){
           $smsTemplate=SmsTemplate::where('id',41)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='6'){
           $smsTemplate=SmsTemplate::where('id',29)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='7'){
           $smsTemplate=SmsTemplate::where('id',35)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='8'){
           $smsTemplate=SmsTemplate::where('id',33)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='9'){
           $smsTemplate=SmsTemplate::where('id',43)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='10'){
           $smsTemplate=SmsTemplate::where('id',39)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='11'){
           $smsTemplate=SmsTemplate::where('id',45)->where('is_active',1)->first();
        } else if($request->input('serviceid') =='13'){
           $smsTemplate=SmsTemplate::where('id',47)->where('is_active',1)->first();
        }
        $arrData = $this->_engjobrequest->getsmsinfobyid($request->input('id'));
        if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
            {
                $receipient=$arrData->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                $msg = str_replace('<JOB_REQUEST_NO>', $arrData->ejr_jobrequest_no,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
            }  
        $array =array();
        $array['status'] = "success";
        $array['permitid'] = "";
        echo json_encode($array);
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
            //$this->_engjobrequest->updatePermitAppData($id,$updatearray);
             $smsTemplate=SmsTemplate::where('id',24)->where('is_active',1)->first();
         }
        else if($request->input('serviceid') =='2'){
            $smsTemplate=SmsTemplate::where('id',26)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='3'){
           $smsTemplate=SmsTemplate::where('id',36)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='4'){
             $smsTemplate=SmsTemplate::where('id',30)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='5'){
           $smsTemplate=SmsTemplate::where('id',40)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='6'){
           $smsTemplate=SmsTemplate::where('id',28)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='7'){
           $smsTemplate=SmsTemplate::where('id',34)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='8'){
           $smsTemplate=SmsTemplate::where('id',32)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='9'){
           $smsTemplate=SmsTemplate::where('id',42)->where('is_active',1)->first(); 
        }else if($request->input('serviceid') =='10'){
           $smsTemplate=SmsTemplate::where('id',38)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='11'){
           $smsTemplate=SmsTemplate::where('id',44)->where('is_active',1)->first();
        }else if($request->input('serviceid') =='13'){
           $smsTemplate=SmsTemplate::where('id',46)->where('is_active',1)->first();
        }

        $jobreqarray =array('ejr_opd_approved_by'=>\Auth::user()->id,'is_approve'=>'1');
        $this->_engjobrequest->updateData($request->input('ejrid'),$jobreqarray);
        $gettopdata = $this->_engjobrequest->checkTransactionexistbyid($request->input('ejrid'));
        if(count($gettopdata)>0){
            $updateremotedata = array();
            $updateremotedata['topno'] = $gettopdata[0]->transaction_no;
          $this->_engjobrequest->updateremotedata($request->input('ejrid'),$updateremotedata); 
        }
        
        
        $arrData = $this->_engjobrequest->getsmsinfobyid($request->input('ejrid'));
        if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
            {
                $receipient=$arrData->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                $msg = str_replace('<JOB_REQUEST_NO>', $arrData->ejr_jobrequest_no,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
               $this->send($msg, $receipient);
            }
        $array =array();
        $array['status'] = "success";
        $array['permitid'] = $appPermitNo;
        echo json_encode($array);
    }

    public function send($message, $receipient)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $setting = $this->componentSMSNotificationRepository->fetch_setting();
            $details = array(
                'message_type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => $this->carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
                //$this->sendSms($receipient, $message);
                $this->componentSMSNotificationRepository->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;  $issurcharge = 0; $istaxpayersref = 0;
        $arrOwners = $this->arrOwners;
        $arrRequirements = array();
        $arrlocgetBrgyCode = array();
        $arrfolcno = array();   
        $requirements =$this->arrRequirements;
        $arrGetservices =$this->arrGetservices;
        $applicationid ="";  $appreqid = "";
        $data->is_approve =""; $extrafeearr = array();
        $defaultFeesarr = $this->_engjobrequest->GetDefaultfees();
        $getextrafees = $this->_engjobrequest->getextrafees();
        foreach ($getextrafees as $key => $value) {
              $extrafeearr[$value->description."#".$value->tfoc_id] = $value->description;
          }
        foreach ($this->_engjobrequest->getSercviceRequirementsall() as $val) {
             $requirements[$val->id]=$val->req_code_abbreviation."-".$val->req_description;
         }
         foreach ($this->_engjobrequest->getfalcnumbers() as $val) {
             $arrfolcno[$val->id]=$val->cc_falc_no;
         }
        //print_r($defaultFeesarr); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngJobRequest::find($request->input('id'));
            $servicedata = $this->_engjobrequest->getservicebyid($data->es_id);
            $istaxpayersref = $servicedata->is_flcno_required;
            if($data->brgy_code>0){
                foreach ($this->_commonmodel->getBarangay($data->brgy_code)['data'] as $val) {
                    $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                }
            }
            foreach ($this->_commonmodel->getBarangay($data->location_brgy_id)['data'] as $val) {
                // $arrlocgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                $arrlocgetBrgyCode[$val->id]=$val->brgy_name;
            }
            if($data->es_id =='1'){
                $data->class  = "buildingpermit";
                $appid = $this->_engjobrequest->Getbidappid($request->input('id')); 
                $applicationid = $appid->id;
                
            }else if($data->es_id =='2'){ 
                $data->class = "demolitionpermit";
                $appid = $this->_engjobrequest->getEditDetailsDemolitionApp($request->input('id')); 
                $applicationid = $appid->id;
            }else if($data->es_id =='3'){ 
                $data->class = "sanitarypermit";
                $appid = $this->_engjobrequest->getEditDetailsSanitaryApp($request->input('id')); 
                $applicationid = $appid->id;
            }else if($data->es_id =='4'){ 
                $data->class = "fencingpermit";
                $appid = $this->_engjobrequest->getEditDetailsFencingApp($request->input('id')); 
                $applicationid = $appid->id;
            }else if($data->es_id =='5'){ 
                $data->class = "excavationpermit";
                 $appid = $this->_engjobrequest->getEditDetailsExcavationApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='6'){ 
                $data->class = "electicpermit";
                $appid = $this->_engjobrequest->getEditDetailsElecticApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='8'){ 
                $data->class = "signpermit";
                $appid = $this->_engjobrequest->getEditDetailsSignApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='9'){ 
                $data->class = "electronicpermit";
                $appid = $this->_engjobrequest->getEditDetailsElectronicsApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='10'){ 
                $data->class = "mechanicalpermit";
                $appid = $this->_engjobrequest->getEditDetailsMechanicalApp($request->input('id')); 
                if(!empty($appid)){ $applicationid = $appid->id; }
            }
            else if($data->es_id =='11'){ 
                $data->class = "civilpermit";
                $appid = $this->_engjobrequest->getEditDetailsCivilApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            else if($data->es_id =='13'){ 
                $data->class = "architecturalpermit";
                $appid = $this->_engjobrequest->getEditDetailsArchitecturalApp($request->input('id')); 
                $applicationid = $appid->id;
            }
            $arrRequirements = $this->_engjobrequest->getJobRequirementsData($request->input('id'));
            $defaultFeesarr = $this->_engjobrequest->GetReqiestfees($request->input('id')); 

            $getsurchargesl = $this->_engjobrequest->getCasheringIds($data->tfoc_id);
            $issurcharge = $getsurchargesl->tfoc_surcharge_sl_id;
             //echo "<pre>"; print_r($arrRequirements);  exit;
        }
        $userroleid = ""; 
        $user = \Auth::user();
       
        $getroleofuserdata = $this->_engjobrequest->getUserrole(\Auth::user()->id);
        if(count($getroleofuserdata) > 0){
          $userroleid = $getroleofuserdata[0]->id; 
        }
        
        //echo "<pre>"; print_r($getroleofuserdata); exit;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $appreqid = $request->input('id');
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
                     $destinationPath =  public_path().'/uploads/engineering/requirements';
                        if(!File::exists($destinationPath)){ 
                            File::makeDirectory($destinationPath, 0755, true, true);
                        }
                     $filename =  'requirement'.date('Ymdhis');  
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
                     $filearray['fe_path'] = 'engineering/requirements';
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
             Session::put('REMOTE_SYNC_APPFORMIDENGINEERING',$appreqid); 
            return redirect()->route('engjobrequest.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engjobrequest.create',compact('data','arrgetBrgyCode','arrOwners','arrRequirements','arrGetservices','applicationid','defaultFeesarr','extrafeearr','requirements','userroleid','issurcharge','arrlocgetBrgyCode','arrfolcno','istaxpayersref'));
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

                if($this->datafirst['es_id'] =='7'){
                    $appid = $lastinsertid;
                    $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);

                    $updateDatanew= array('application_no'=>$appsereviceNo);
                    $this->_engjobrequest->updateData($lastinsertid,$updateDatanew);
                    $class="waterpermit";
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

                $appid2 =$appsereviceNo;  $surchatgeenable="0";
                $defaultFeesarr = $this->_engjobrequest->GetDefaultfees();
                $cashdata = $this->_engjobrequest->getCasheringIds($this->datafirst['tfoc_id']);
                
                if($cashdata->tfoc_surcharge_sl_id > 0){
                    $surchatgeenable= '1';
                }
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

                $array = ["status"=>"success","lastinsertid" => $lastinsertid,"appid2"=>$appsereviceNo,"jobreqno"=>$jobreqNo,"class"=>$class,"appid"=>$appid,"surchatgeenable"=>$surchatgeenable];

            }
            
         echo json_encode($array);
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'rpo_address_house_lot_no'=>'required',
                'rpo_address_street_name'=>'required',
                'location_brgy_id'=>'required',
                'brgy_code'=>'required',
                'es_id'=>'required',
            ]
            ,
            [
                'client_id.required|unique' => 'Client Has Added Service Allready',
                'rpo_address_house_lot_no.required'=>'Required',
                'location_brgy_id.required'=>'Required',
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
                'ebpa_application_no'=>'required',
                'eba_id'=>'required',
                'ebpa_location' =>'required',
                'ebpa_application_date'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name'=>'required',
                'ebpa_owner_mid_name'=>'required',
				'ebpa_economic_act'=>'required',
                'ebpa_bldg_official_name'=>'required',
                'ebot_id' =>'required',
                'ebs_id' =>'required',
            ]
            ,
            [
                'ebpa_mun_no.required' => 'Required Field',
                'ebpa_application_no.required'=>'Required',
                'eba_id.required'=>'Required',
                'ebpa_application_date.required'=>'Required',
                'ebpa_owner_last_name.required'=>'Required',
                'ebpa_owner_first_name.required'=>'Required',
                'ebpa_owner_mid_name.required'=>'Required',
				'ebpa_economic_act.required' =>'Required',
                'ebpa_bldg_official_name.required'=>'Required',
                'ebpa_location.required' =>'Required',
                'ebot_id.required' =>'Required',
                'ebs_id.required' =>'Required',
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
                'mum_no'=>'required',
                'espa_application_no'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name' =>'required',
                'ebpa_owner_mid_name'=>'required',
                'ewst_id'=>'required',
                'edst_id'=>'required',
                'espa_no_of_storey'=>'required|numeric|gt:0',
                'espa_floor_area'=>'required|numeric|gt:0',
                'espa_installation_cost'=>'required|numeric|gt:0',
                'espa_location'=>'required',
                'espa_installation_date'=>'required',
                'espa_completion_date'=>'required',
                'espa_building_official'=>'required',
                'espa_preparedby'=>'required',
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
                'espa_no_of_storey.required'=>'Required',
                'espa_floor_area.required'=>'Required',
                'espa_installation_cost.required'=>'Required',
                'espa_location.required'=>'Required',
                'espa_installation_date.required'=>'required',
                'espa_completion_date.required'=>'Required',
                'espa_building_official.required'=>'Required',
                'espa_preparedby.required'=>'Required',
               
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
                'eea_application_no'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name' =>'required',
                'ebpa_owner_mid_name'=>'required',
				'kindbussiness'=>'required',
				'eea_location'=>'required',
                'ebs_id'=>'required',
				'eea_date_of_construction'=>'required',
                'eea_estimated_cost' =>'required |numeric|gt:0', 
                'eea_date_of_completion'=>'required',
                'eeet_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eea_application_no.required'=>'Required Field',
                'ebpa_owner_last_name.required'=>'Required Field',
                'ebpa_owner_first_name.required'=>'Required Field',
                'ebpa_owner_mid_name.required'=>'Required Field',
				'kindbussiness.required'=>'Required Field',
				'eea_location.required'=>'Required Field',
                'ebs_id.required'=>'Required Field',
				'eea_date_of_construction.required'=>'Required Field',
                'eea_estimated_cost.required' =>'Required Field', 
                'eea_date_of_completion.required'=>'Required Field',
				'eeet_id.required'=>'required',
               
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
                'eeta_application_no'=>'required',
                'eeta_location'=>'required',
                'ebs_id'=>'required',
                'eest_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eeta_application_no.required'=>'Required',
                'eeta_location.required'=>'Required',
                'ebs_id.required'=>'Required',
                'eest_id.required'=>'Required',
               
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
                'ema_application_no'=>'required',
                'ema_location'=>'required',
                'ebs_id'=>'required',
                'eiot_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'ema_application_no.required'=>'Required',
                'ema_location.required'=>'Required',
                'ebs_id.required'=>'Required',
                'eiot_id.required'=>'Required',
               
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
                'eega_application_no'=>'required',
                'eega_location'=>'required',
                'ebs_id'=>'required',
                'eegt_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eega_application_no.required'=>'Required',
                'eega_location.required'=>'Required',
                'ebs_id.required'=>'Required',
                'eegt_id.required'=>'Required',
               
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
                'eca_application_no'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name' =>'required',
                'ebpa_owner_mid_name'=>'required',
                'ebs_id'=>'required',
                'ebot_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eea_application_no.required'=>'Required',
                'ebpa_owner_last_name.required'=>'Required',
                'ebpa_owner_first_name.required'=>'Required',
                'ebpa_owner_mid_name.required'=>'Required',
                'ebs_id.required'=>'Required',
                'ebot_id.required'=>'Required',
               
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
                'eea_application_no'=>'required',
                'eea_location'=>'required',
                'ebs_id'=>'required',
            ]
            ,
            [
                'mum_no.required' => 'Required Field',
                'eaa_application_no.required'=>'Required',
                'eea_location.required'=>'Required',
                'ebs_id.required'=>'Required',
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
                'esa_application_no'=>'required',
                'ebpa_location'=>'required',
                'ebs_id'=>'required',
                'esdt_id'=>'required',
                'esit_id'=>'required',
                'length'=>'required|numeric|gt:0',
                'width'=>'required|numeric|gt:0',
                'alllengthwidth'=>'required|numeric|gt:0',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
                'esa_application_no.required'=>'Required',
                'ebpa_location.required'=>'Required',
                'ebs_id.required'=>'Required',
                'esdt_id.required'=>'Required',
                'esit_id.required'=>'Required',
                'length.required'=>'Required',
                'width.required'=>'Required',
                'alllengthwidth.required'=>'Required',
               
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
                'eda_application_no'=>'required',
				'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name' =>'required',
                'ebpa_owner_mid_name'=>'required',
                'eda_tax_acct_no' =>'required',
                'eda_form_of_own' =>'required',
                'eda_location'=>'required',
                'ebs_id'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
                'eda_application_no.required'=>'Required',
				'ebpa_owner_last_name.required'=>'Required',
                'ebpa_owner_first_name.required'=>'Required',
                'ebpa_owner_mid_name.required'=>'Required',
                'eda_location.required'=>'Required',
                'eda_tax_acct_no.required' =>'Required',
                'eda_form_of_own.required' =>'Required',
                'ebs_id.required'=>'Required',
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
                'efa_application_no'=>'required',
                'ebpa_location'=>'required',
                'ebs_id'=>'required',
                // 'eft_id'=>'required',
            ]
            ,
            [
                'mun_no.required' => 'Required Field',
                'efa_application_no.required'=>'Required',
                'ebpa_location.required'=>'Required',
                'ebs_id.required'=>'Required',
                // 'eft_id.required'=>'Required',
               
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
                'espa_amount_due'=>'required|numeric|gt:0',
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
                'espa_amount_due.required'=>'Required',
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
                    $updatearray = array('ebaf_bldg_amount'=>$buildingfeedata['ebpf_total_fees'],'ebaf_total_amount'=>$request->input('totalssessdfeeamt'));
                    $buldappid = $this->_engjobrequest->getbuildingappid($request->input('jobrequestid'));
                //$jobarray = array('ejr_totalfees'=>$buildingfeedata['ebpf_total_fees']);
                $this->_engjobrequest->updateAssessmentbldgFees($buldappid->id,$updatearray);
              
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
                'eea_application_no.required'=>'Required Field',
                'eea_building_official.required'=>'Required Field',
               
            ]
          );
            $arr=array('status'=>'validation_error');
            if($validator->fails()){
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                return response()->json($arr); exit;
            }


            $electricappdata = array('id'=>'','mum_no'=>'','eea_year'=>'','eea_series_no'=>'','eea_application_no'=>'','ebpa_permit_no'=>'','eea_application_date'=>'','eea_issued_date'=>'','taxacctno'=>'','formofowner'=>'','kindbussiness'=>'','p_code'=>'','ebs_id'=>'','ebot_id'=>'','eeet_id'=>'','eea_date_of_construction'=>'','eea_estimated_cost'=>'','eea_date_of_completion'=>'','eea_prepared_by'=>'','eea_sign_category'=>'','eea_sign_consultant_id'=>'','eea_incharge_category'=>'','eea_incharge_consultant_id'=>'','eea_applicant_category'=>'','eea_applicant_consultant_id'=>'','eea_owner_id'=>'','eea_amount_due'=>'','eea_assessed_by'=>'','eea_or_no'=>'','eea_date_paid'=>'','eea_building_official'=>'','rescertno'=>'','dateissued'=>'','placeissued'=>'','signaddress'=>'','signptrno'=>'','signdateissued'=>'','signplaceissued'=>'','signtin'=>'','signprcregno'=>'','inchargenaddress'=>'','inchargeptrno'=>'','inchargedateissued'=>'','inchargeplaceissued'=>'','inchargetin'=>'','inchargeprcregno'=>'','ownertaxdcno'=>'','owneraddress'=>'','ownerstreet'=>'','ownersubdivision'=>'','ownermuncipality'=>'','ownertelephoneno'=>'','lotno'=>'','taxdecno'=>'','totno'=>'','blkno'=>'','streetname'=>'','subdivision'=>'','ownerespa_location'=>'');

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
            $idsoeiot_id = implode(',',$request->input('eiot_id'));
            $mechanicalappdata['eiot_id'] = $idsoeiot_id;
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
            if(!empty($request->input('eeft_id'))){
              $idsofeeetid = implode(',',$request->input('eeft_id'));
              $architecturalappdata['eeft_id'] = $idsofeeetid;  
            }
            
            if(!empty($request->input('ectfc_id'))){
            $idsofectfc_id = implode(',',$request->input('ectfc_id'));
            $architecturalappdata['ectfc_id'] = $idsofectfc_id;
            }
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
                'measureheight'=>'required|numeric|gt:0',
                'measurelength'=>'required|numeric|gt:0',
            ]
            ,
            [
                'efa_building_official.required'=>'Required',
                'measureheight.required'=>'Required',
                'measurelength.required'=>'Required',
               
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
            ],
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

     public function Printorderfile($id,$serviceid){
        $data = EngJobRequest::find($id);
         $mpdf  = new PDF( [
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => '3',
                'margin_top' => '20',
                'margin_bottom' => '20',
                'margin_footer' => '2',
            ]);     
            //$mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/orderofpayment.html'));
            $topTransactionno = "";

             if($data->top_transaction_type_id > 0){
               $gettopdata = $this->_engjobrequest->checkTransactionexist($data->id,$data->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $topTransactionno = $gettopdata[0]->transaction_no;
               }
            }  
           switch($serviceid){
              case 1:
                     $appdata = $this->_engjobrequest->orderpaymentbuild($id);
                     //echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                     // echo"<pre>"; print_r($arrTypeofOccupancy); exit;
                     $html = str_replace('{{dateapplied}}',$appdata->ebpa_application_date, $html);
                     $html = str_replace('{{dateprepared}}',$appdata->ebpa_application_date, $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->ebfd_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->ebfd_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(isset($data->fullname))
                        $datanew['fullname'] = $data->fullname;
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->ebfd_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(isset($data->fullname))
                        $datanew['fullname'] = $data->fullname;
                    }
                    $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;

                   case 2:
                     $appdata = $this->_engjobrequest->orderpaymentdemolition($id);
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                     // echo"<pre>"; print_r($arrTypeofOccupancy); exit;
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eda_location, $html);
                      if($appdata->eda_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eda_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eda_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                     case 3:
                     $appdata = $this->_engjobrequest->orderpaymentsanitary($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->espa_location, $html);
                      if($appdata->espa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->espa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->espa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                    case 4:
                     $appdata = $this->_engjobrequest->orderpaymentfencing($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->efa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->efa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->efa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                     case 5:
                     $appdata = $this->_engjobrequest->orderpaymentexcavation($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eega_location, $html);
                      if($appdata->eega_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                    case 6:
                     $appdata = $this->_engjobrequest->orderpaymentelectric($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eega_location, $html);
                      if($appdata->eega_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                     case 7:
                     $appdata = $this->_engjobrequest->orderpaymentelectric($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                   
                    break;  


                     case 8:
                     $appdata = $this->_engjobrequest->orderpaymentsign($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->esa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->esa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->esa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    
                    break; 

                     case 9:
                     $appdata = $this->_engjobrequest->orderpaymentelectronic($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eeta_location, $html);
                      if($appdata->eeta_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eeta_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eeta_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;

                     case 10:
                     $appdata = $this->_engjobrequest->orderpaymentmechanical($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ema_location, $html);
                      if($appdata->ema_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->ema_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->ema_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                    case 11:
                     $appdata = $this->_engjobrequest->orderpaymentcivil($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eca_location, $html);
                      if($appdata->eca_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eca_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eca_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                    case 13:
                     $appdata = $this->_engjobrequest->orderpaymentarchitect($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eea_location, $html);
                      if($appdata->eea_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eea_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;                                                                 

              }
           
             $dynamicfeehtml= "";
             $defaultFeesarr = $this->_engjobrequest->GetReqiestfees($id);  $i= 1;
             foreach ($defaultFeesarr as $key => $value) {
                $dynamicfeehtml .='<tr>
                           <td  style="text-align:left;border:0px solid black;">
                          <p style="font-size: 15px;font-weight: 400;"> '.$i.'.'.$value->fees_description.' :<span class="textborder"> '.$value->tax_amount.' </span></p></td>
                        </tr>';
                        $i++;
             }
             $html = str_replace('{{dynamictr}}',$dynamicfeehtml, $html); 
             $html = str_replace('{{toptransno}}',$topTransactionno, $html); 
             $html = str_replace('{{contactno}}',$appdata->p_mobile_no, $html);
             $html = str_replace('{{project}}',$appdata->ejr_project_name, $html);
             $html = str_replace('{{firstfloor}}',$appdata->ejr_firstfloorarea, $html);
             $html = str_replace('{{secondfloor}}',$appdata->ejr_secondfloorarea, $html);
             $html = str_replace('{{dimention}}',$appdata->ebfd_floor_area, $html);
             $html = str_replace('{{totalarea}}',$appdata->ebfd_floor_area, $html);
             $html = str_replace('{{lotarea}}',$appdata->ejr_lotarea, $html);
             $html = str_replace('{{premietr}}',$appdata->ejr_perimeter, $html);
             $html = str_replace('{{projectcost}}',$appdata->ejr_projectcost, $html);
             $html = str_replace('{{nettotal}}',$appdata->ejr_total_net_amount, $html);
             $html = str_replace('{{ornumberr}}',$appdata->cashier_id, $html);
             $html = str_replace('{{surchargefee}}',$appdata->ejr_surcharge_fee, $html);
             if($appdata->ejr_date_paid !='0000-00-00 00:00:00'){
                $paiddate = date('Y/m/d',strtotime($appdata->ejr_date_paid));
             }else{  $paiddate ="";}
             $html = str_replace('{{paiddate}}', $paiddate, $html);
             $html = str_replace('{{totalfee}}',$appdata->ejr_totalfees, $html);
             $html = str_replace('{{nameprepared}}','', $html);
             $html = str_replace('{{approvedby}}','', $html);
             $html = str_replace('{{approvedby}}','', $html); 
             $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
             $html = str_replace('{{applicationno}}',$appdata->application_no, $html);
             $html = str_replace('{{nameofowner}}',$appdata->rpo_custom_last_name." ".$appdata->rpo_first_name." ".$appdata->rpo_middle_name, $html);
             $filename="";
            //$html = $html;
            //echo $html; exit;
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{LOGO2}}',$logo2, $html);
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $permitfilename = $id.$filename."orderofpayment.pdf";
            $folder =  public_path().'/uploads/billing/engineering/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/billing/engineering/" . $permitfilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            return $permitfilename;
    }

    public function Printorder(Request $request){
		
         $id = $request->input('id');   $serviceid = $request->input('serviceid'); 
         return 'engjobrequest/print-order-of-payment/'.$id;
         $data = EngJobRequest::find($request->input('id'));
         $mpdf  = new PDF( [
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => '3',
                'margin_top' => '20',
                'margin_bottom' => '20',
                'margin_footer' => '2',
            ]);     
            //$mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/orderofpayment.html'));
            $topTransactionno = "";

             if($data->top_transaction_type_id > 0){
               $gettopdata = $this->_engjobrequest->checkTransactionexist($data->id,$data->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $topTransactionno = $gettopdata[0]->transaction_no;
               }
            }  
           switch($serviceid){
              case 1:
                     $appdata = $this->_engjobrequest->orderpaymentbuild($id);
                     //echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                     // echo"<pre>"; print_r($arrTypeofOccupancy); exit;
                     $html = str_replace('{{dateapplied}}',$appdata->ebpa_application_date, $html);
                     $html = str_replace('{{dateprepared}}',$appdata->ebpa_application_date, $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->ebfd_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->ebfd_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(isset($data->fullname))
                        $datanew['fullname'] = $data->fullname;
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->ebfd_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(isset($data->fullname))
                        $datanew['fullname'] = $data->fullname;
                    }
                    $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;

                   case 2:
                     $appdata = $this->_engjobrequest->orderpaymentdemolition($id);
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                     // echo"<pre>"; print_r($arrTypeofOccupancy); exit;
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eda_location, $html);
                      if($appdata->eda_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eda_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eda_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                     case 3:
                     $appdata = $this->_engjobrequest->orderpaymentsanitary($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->espa_location, $html);
                      if($appdata->espa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->espa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->espa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                    case 4:
                     $appdata = $this->_engjobrequest->orderpaymentfencing($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->efa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->efa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->efa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                     case 5:
                     $appdata = $this->_engjobrequest->orderpaymentexcavation($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eega_location, $html);
                      if($appdata->eega_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                    case 6:
                     $appdata = $this->_engjobrequest->orderpaymentelectric($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eega_location, $html);
                      if($appdata->eega_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eega_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;  

                     case 8:
                     $appdata = $this->_engjobrequest->orderpaymentsign($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $arrTypeofOccupancy[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
                      if($appdata->esa_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->esa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->esa_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    
                    break; 

                     case 9:
                     $appdata = $this->_engjobrequest->orderpaymentelectronic($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eeta_location, $html);
                      if($appdata->eeta_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eeta_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eeta_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;

                     case 10:
                     $appdata = $this->_engjobrequest->orderpaymentmechanical($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->ema_location, $html);
                      if($appdata->ema_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->ema_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->ema_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                    case 11:
                     $appdata = $this->_engjobrequest->orderpaymentcivil($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eca_location, $html);
                      if($appdata->eca_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eca_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eca_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break; 

                    case 13:
                     $appdata = $this->_engjobrequest->orderpaymentarchitect($id);
                      // echo"<pre>"; print_r($appdata); exit;
                     $arrTypeofOccupancy = $this->arrTypeofOccupancy;
                     //echo $appdata->ebot_id;  print_r($arrTypeofOccupancy); exit;
                     $electronicequipmentarray = array(); 
                        foreach ($this->_engjobrequest->GetEquipmentsSystemType() as $val) {
                         $electronicequipmentarray[$val->id]=$val->eest_description;
                        } 
                     if($appdata->ebot_id > 0){ $typeofoccupancy = $electronicequipmentarray[$appdata->ebot_id];}
                     else{ $typeofoccupancy =""; }
                     // echo $arrTypeofOccupancy[1];
                    
                     $html = str_replace('{{dateapplied}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{dateprepared}}',date('Y/m/d',strtotime($appdata->created_at)), $html);
                     $html = str_replace('{{location}}',$appdata->eea_location, $html);
                      if($appdata->eea_sign_category =='1'){
                        $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }else{
                        $data = $this->_engjobrequest->getExternalDetails($appdata->eea_sign_consultant_id);
                        $datanew = array('fullname'=>'');
                        if(!empty($data)){ $datanew['fullname'] = $data->fullname; }
                        $html = str_replace('{{signenginner}}',$datanew['fullname'], $html);
                    }
                    $html = str_replace('{{useofoccupancy}}',$typeofoccupancy, $html);
                    break;                                                                 

              }
           
             $dynamicfeehtml= "";
             $defaultFeesarr = $this->_engjobrequest->GetReqiestfees($request->input('id'));  $i= 1;
             foreach ($defaultFeesarr as $key => $value) {
                $dynamicfeehtml .='<tr>
                           <td  style="text-align:left;border:0px solid black;">
                          <p style="font-size: 15px;font-weight: 400;"> '.$i.'.'.$value->fees_description.' :<span class="textborder"> '.$value->tax_amount.' </span></p></td>
                        </tr>';
                        $i++;
             }
             $html = str_replace('{{dynamictr}}',$dynamicfeehtml, $html); 
             $html = str_replace('{{toptransno}}',$topTransactionno, $html); 
             $html = str_replace('{{contactno}}',$appdata->p_mobile_no, $html);
             $html = str_replace('{{project}}',$appdata->ejr_project_name, $html);
             $html = str_replace('{{firstfloor}}',$appdata->ejr_firstfloorarea, $html);
             $html = str_replace('{{secondfloor}}',$appdata->ejr_secondfloorarea, $html);
             $html = str_replace('{{dimention}}',$appdata->ebfd_floor_area, $html);
             $html = str_replace('{{totalarea}}',$appdata->ebfd_floor_area, $html);
             $html = str_replace('{{lotarea}}',$appdata->ejr_lotarea, $html);
             $html = str_replace('{{premietr}}',$appdata->ejr_perimeter, $html);
             $html = str_replace('{{projectcost}}',$appdata->ejr_projectcost, $html);
             $html = str_replace('{{nettotal}}',$appdata->ejr_total_net_amount, $html);
             $html = str_replace('{{ornumberr}}',$appdata->cashier_id, $html);
             $html = str_replace('{{surchargefee}}',$appdata->ejr_surcharge_fee, $html);
             if($appdata->ejr_date_paid !='0000-00-00 00:00:00'){
                $paiddate = date('Y/m/d',strtotime($appdata->ejr_date_paid));
             }else{  $paiddate ="";}
             $html = str_replace('{{paiddate}}', $paiddate, $html);
             $html = str_replace('{{totalfee}}',$appdata->ejr_totalfees, $html);
             $html = str_replace('{{nameprepared}}','', $html);
             $html = str_replace('{{approvedby}}','', $html);
             $html = str_replace('{{approvedby}}','', $html); 
             $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
             $html = str_replace('{{applicationno}}',$appdata->application_no, $html);
             $html = str_replace('{{nameofowner}}',$appdata->rpo_custom_last_name." ".$appdata->rpo_first_name." ".$appdata->rpo_middle_name, $html);
             $filename="";
            //$html = $html;
            //echo $html; exit;
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{LOGO2}}',$logo2, $html);
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $permitfilename = $id.$filename."orderofpayment.pdf";
            $folder =  public_path().'/uploads/jobrequestgpermit/orderpayment/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/jobrequestgpermit/orderpayment/" . $permitfilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/jobrequestgpermit/orderpayment/' . $permitfilename);
        
    }

    public function printpermit(Request $request){
		
      $id = $request->input('id');
	  $serviceid = $request->input('serviceid');  //exit;

        //$data = $this->_engoccupancyapp->getoccupancydetail($id);
        //echo $data->ebpa_id;
        //print_r($data); exit;
        // Create the mPDF document
        $mpdf  = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
        //$mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = false;
        $mpdf->showImageErrors = false;
        $mpdf->text_input_as_HTML = true;

        /*$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['fontdata' => $fontData + [ // lowercase letters only in font key
            'frutiger' => [
                'R' => 'Frutiger-Normal.ttf',
                'I' => 'FrutigerObl-Normal.ttf',
            ]
        ]]);*/

        $unchecked = url('/assets/images/unchecked-checkbox.jpg');
        $checked = url('/assets/images/checked-checkbox.jpeg');

        switch($serviceid){
            case 1:
                break;
                case 2:
                 $html = file_get_contents(resource_path('views/layouts/templates/demolitionpermitdesign.html'));
                 $appdata = $this->_engjobrequest->getEditDetailsDemolitionforprint($id);
                 //echo "<pre>"; print_r($appdata); exit;
                 $datarelated = $this->_engjobrequest->find($id);
                 $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
                 $html = str_replace('{{appno}}',$appdata->eda_application_no, $html);
                 $html = str_replace('{{loclotno}}',$appdata->loclotno, $html);
                 $html = str_replace('{{locblkno}}',$appdata->locblkno, $html);
                 $html = str_replace('{{loctotno}}',$appdata->loctotno, $html);
                 $html = str_replace('{{loctdno}}',$appdata->loctdno, $html);
                 $html = str_replace('{{locstreet}}',$appdata->locstreet, $html);
                 $permitno ="&nbsp;";
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                 $html = str_replace('{{permitno}}',$permitno, $html);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $html = str_replace('{{acctno}}',$appdata->eda_tax_acct_no, $html);
                 $html = str_replace('{{formogowner}}',$appdata->eda_form_of_own, $html);
                 $html = str_replace('{{maineconomy}}',$appdata->eda_economic_act, $html);
                 $html = str_replace('{{location}}',$appdata->eda_location, $html);
                 $html = str_replace('{{telephoneno}}',$appdata->p_telephone_no, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{houseno}}',$appdata->rpo_address_house_lot_no, $html);
                 $html = str_replace('{{tinno}}','', $html);
                 $html = str_replace('{{barangay}}',$datarelated->brgy->brgy_name, $html);
                 
                 $htmldynaapp = "";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeseldemolition(); 
                 $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                    foreach ($scopeofbuilding as $key => $value) {
                      if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        } 
                        if($value->id == $appdata->ebs_id){
                        $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                        }else{
                        $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                        }
                        if(in_array($i,$dynaarray1)){ 
                           $htmldynaapp .='</tr>'; 
                           }
                           $i++;
                     }
                   $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

                if($appdata->eda_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eda_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
                    $datanew['prcno'] = $data->emp_prc_no;
                    $datanew['ptrno'] = $data->emp_ptr_no;
                    $datanew['tinno'] = $data->tin_no;
                    $datanew['issueddate'] = $data->emp_issue_date;
                    $datanew['issuedplace'] = $data->emp_issue_at;
                    $datanew['validity'] = $data->emp_prc_validity;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eda_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $appdata->inchargeaddress;
                    $datanew['prcno'] = $appdata->inchargeprcno;
                    $datanew['ptrno'] = $appdata->inchargeptrno;
                    $datanew['tinno'] = $appdata->inchargeaddress;
                    $datanew['issueddate'] = $appdata->inchargedateissued;
                    $datanew['issuedplace'] = $appdata->inchargeplaceissued; 
                    $datanew['validity'] = $appdata->inchargevalidity; 
                   }
                }
                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargeaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}', $appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargevalidity}}', $appdata->inchargevalidity, $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin , $html);

                $dataapplicant = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eda_applicant_consultant_id);
                if(!empty($dataapplicant)){ 
                $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
                $html = str_replace('{{applicantname}}', $fullname , $html);
                $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
                }else{  
                    $html = str_replace('{{applicantname}}', '' , $html);
                    $html = str_replace('{{dateapp}}', '' , $html);
                }
                $html = str_replace('{{applicantaddress}}', $appdata->applicantaddress , $html);
                $html = str_replace('{{appctcno}}', $appdata->applicantctcno, $html);
                $html = str_replace('{{appdateissued}}', $appdata->applicantdateissued , $html);
                $html = str_replace('{{placeissued}}', $appdata->applicantplaceissued , $html);
                $dataowner = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eda_owner_id);
                
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{lotname}}', $fullname , $html);
                $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                $html = str_replace('{{lottaxcert}}', $appdata->ownerctcno , $html);
                $html = str_replace('{{lotaddress}}', $appdata->owneraddress , $html);
                $html = str_replace('{{lotdateissued}}', $appdata->ownerdateissued , $html);
                $html = str_replace('{{lotplaceissued}}', $appdata->ownerplaceissued , $html);

                $html = str_replace('{{applicantnew}}', $appdata->applicantnew , $html);
                $html = str_replace('{{ctcnonew}}', $appdata->ctcnonew , $html);
                $html = str_replace('{{dateissuednew}}', $appdata->dateissuednew , $html);
                $html = str_replace('{{placeissuednew}}', $appdata->placeissuednew , $html);

                $html = str_replace('{{liancenedapplicant}}', $appdata->liancenedapplicant , $html);
                $html = str_replace('{{liancenedctcno}}', $appdata->liancenedctcno , $html);
                $html = str_replace('{{lianceneddateissued}}', $appdata->lianceneddateissued , $html);
                $html = str_replace('{{liancenedplaceissued}}', $appdata->liancenedplaceissued , $html);
               
                $buildingname ="";
                if(!empty($appdata->eda_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eda_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                //print_r($gethremployename); exit;
                $html = str_replace('{{bldofficialname}}', $buildingname , $html);
                // echo $html; exit;
                $filename="";
                $logo = url('/assets/images/logo.png');
                $logo2 = url('/assets/images/logo2.jpg');  
                $bgimage = url('/assets/images/clearancebackground.jpg');
                $html = str_replace('{{LOGO}}',$logo, $html);
                $html = str_replace('{{LOGO2}}',$logo2, $html);
                $mpdf->WriteHTML($html);
                // $mpdf->AddPage();
                // $mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id."demolition.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
               
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eda_building_official);
				
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '207,149,385,84,2';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
					
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,80,240,50);
                        }
                    }
                }

                $mpdf->Output($filename,"I");

                break;

               //  case 3:
               //   $html = file_get_contents(resource_path('views/layouts/templates/sanitarypermit1.html'));
               //   $appdata = $this->_engjobrequest->getEditDetailsSanitaryforprint($id);
               //   $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
               //   $html = str_replace('{{appno}}',$appdata->espa_application_no, $html);

               //   $fixturetype =array('0'=>'','1'=>'New Fixtures','2'=>'Existing Fixtures');
               //   //echo "<pre>"; print_r($appdata); exit;
               //   //$html = str_replace('{{permitno}}',$appdata->ebpa_permit_no, $html);
               //   $permitno="";
               //   $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
               //   if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
               //   $html = str_replace('{{permitno}}',$permitno, $html);
               //   $html = str_replace('{{dateofapp}}',$appdata->espa_application_date, $html);
               //   $html = str_replace('{{dateissued}}',$appdata->espa_issued_date, $html);
               //   $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
               //   $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
               //   $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html); 
               //   $html = str_replace('{{preffix}}',$appdata->suffix, $html);
               //   $html = str_replace('{{address}}',$appdata->rpo_address_house_lot_no.", ".$appdata->rpo_address_street_name.", ".$appdata->rpo_address_subdivision, $html);

               //   $html = str_replace('{{waterqty}}',$appdata->espa_water_closet_qty, $html);
               //   $html = str_replace('{{watertype}}',$fixturetype[$appdata->espa_water_closet_type], $html);
               //   $html = str_replace('{{bidetteqty}}',$appdata->espa_bidette_qty, $html);
               //   $html = str_replace('{{bidetetype}}',$fixturetype[$appdata->espa_bidettet_type], $html);

               //   $html = str_replace('{{floorqty}}',$appdata->espa_floor_drain_qty, $html);
               //   $html = str_replace('{{floortype}}',$fixturetype[$appdata->espa_floor_drain_type], $html);
               //   $html = str_replace('{{laundryqty}}',$appdata->espa_laundry_trays_qty, $html);
               //   $html = str_replace('{{laundrytype}}',$fixturetype[$appdata->espa_laundry_trays_type], $html);

               //   $html = str_replace('{{Lavourtonesqty}}',$appdata->espa_lavatories_qty, $html);
               //   $html = str_replace('{{Lavourtonestype}}',$fixturetype[$appdata->espa_lavatories_type], $html);
               //   $html = str_replace('{{dentalqty}}',$appdata->espa_dental_cuspidor_qty, $html);
               //   $html = str_replace('{{dentaltype}}',$fixturetype[$appdata->espa_dental_cuspidor_type], $html);

               //   $html = str_replace('{{ktchenqty}}',$appdata->espa_kitchen_sink_qty, $html);
               //   $html = str_replace('{{ktchentype}}',$fixturetype[$appdata->espa_kitchen_sink_type], $html);
               //   $html = str_replace('{{gasqty}}',$appdata->espa_gas_heater_qty, $html);
               //   $html = str_replace('{{gastype}}',$fixturetype[$appdata->espa_gas_heater_type], $html);

               //   $html = str_replace('{{faucetqty}}',$appdata->espa_faucet_qty, $html);
               //   $html = str_replace('{{faucettype}}',$fixturetype[$appdata->espa_faucet_type], $html);
               //   $html = str_replace('{{electricqty}}',$appdata->espa_electric_heater_qty, $html);
               //   $html = str_replace('{{electrictype}}',$fixturetype[$appdata->espa_electric_heater_type], $html);

               //   $html = str_replace('{{showerqty}}',$appdata->espa_shower_head_qty, $html);
               //   $html = str_replace('{{showertype}}',$fixturetype[$appdata->espa_shower_head_type], $html);
               //   $html = str_replace('{{waterboilerqty}}',$appdata->espa_water_boiler_qty, $html);
               //   $html = str_replace('{{waterboilertype}}',$fixturetype[$appdata->espa_water_boiler_type], $html);

               //   $html = str_replace('{{watermeterqty}}',$appdata->espa_water_meter_qty, $html);
               //   $html = str_replace('{{watermetertype}}',$fixturetype[$appdata->espa_water_meter_type], $html);
               //   $html = str_replace('{{drinkingqty}}',$appdata->espa_drinking_fountain_qty, $html);
               //   $html = str_replace('{{drinkingtype}}',$fixturetype[$appdata->espa_drinking_fountain_type], $html);

               //   $html = str_replace('{{greaseqty}}',$appdata->espa_grease_trap_qty, $html);
               //   $html = str_replace('{{greasetype}}',$fixturetype[$appdata->espa_grease_trap_type], $html);
               //   $html = str_replace('{{barqty}}',$appdata->espa_bar_sink_qty, $html);
               //   $html = str_replace('{{bartype}}',$fixturetype[$appdata->espa_bar_sink_type], $html);

               //   $html = str_replace('{{bathtubsqty}}',$appdata->espa_bath_tubs_qty, $html);
               //   $html = str_replace('{{bathtubstype}}',$fixturetype[$appdata->espa_bath_tubs_type], $html);
               //   $html = str_replace('{{sodaqty}}',$appdata->espa_laboratory_qty, $html);
               //   $html = str_replace('{{sodatype}}',$fixturetype[$appdata->espa_laboratory_type], $html);

               //   $html = str_replace('{{slopqty}}',$appdata->espa_slop_sink_qty, $html);
               //   $html = str_replace('{{sloptype}}',$fixturetype[$appdata->espa_slop_sink_type], $html);
               //   $html = str_replace('{{laboutaryqty}}',$appdata->espa_soda_fountain_qty, $html);
               //   $html = str_replace('{{laboutarytype}}',$fixturetype[$appdata->espa_soda_fountain_type], $html);

               //   $html = str_replace('{{urinalqty}}',$appdata->espa_urinal_qty, $html);
               //   $html = str_replace('{{urinaltype}}',$fixturetype[$appdata->espa_urinal_type], $html);
               //   $html = str_replace('{{sterilizeqty}}',$appdata->espa_sterilizer_qty, $html);
               //   $html = str_replace('{{sterilizetype}}',$fixturetype[$appdata->espa_sterilizer_type], $html);

               //   $html = str_replace('{{airqty}}',$appdata->espa_airconditioning_unit_qty, $html);
               //   $html = str_replace('{{airtype}}',$fixturetype[$appdata->espa_airconditioning_unit_type], $html);
               //   $html = str_replace('{{swimmingqty}}',$appdata->espa_swimmingpool_qty, $html);
               //   $html = str_replace('{{swimmingtype}}',$fixturetype[$appdata->espa_swimmingpool_type], $html);

               //   $html = str_replace('{{watertankqty}}',$appdata->espa_water_meter_qty, $html);
               //   $html = str_replace('{{watertanktype}}',$fixturetype[$appdata->espa_water_meter_type], $html);
               //   $html = str_replace('{{othrtqty}}',$appdata->espa_others_qty, $html);
               //   $html = str_replace('{{othrttype}}',$fixturetype[$appdata->espa_others_type], $html);

               //   $html = str_replace('{{acctno}}',$appdata->taxacctno, $html);
               //   $html = str_replace('{{formogowner}}',$appdata->formofowner, $html);
               //   $html = str_replace('{{maineconomy}}',$appdata->maineconomy, $html);
               //   $html = str_replace('{{location}}',$appdata->espa_location, $html);
               //   $html = str_replace('{{bldofficialname}}',$appdata->espa_location, $html);
               //   $html = str_replace('{{date}}',$appdata->espa_application_date, $html);
               //   $html = str_replace('{{totalarea}}',$appdata->espa_floor_area, $html);
               //   $html = str_replace('{{dateofinstall}}',$appdata->espa_installation_date, $html);
               //   $html = str_replace('{{totalcostinstall}}',$appdata->espa_installation_cost, $html);
               //   $html = str_replace('{{dateofcompletion}}',$appdata->espa_completion_date, $html);
               //   $html = str_replace('{{prepareby}}',$appdata->espa_assessed_by, $html);
               //   $htmldynaapp ="";
               //   $scopeofbuilding = $this->_engjobrequest->GetBuildingScopessanitary();   
               //      foreach ($scopeofbuilding as $key => $value) {
               //      if($value->id == $appdata->ebs_id){
               //          $htmldynaapp .=' <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value->ebs_description.'</p>';
               //        }else{
               //          $htmldynaapp .='<p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value->ebs_description.'</p>';
               //          }
               //       }
               //     $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

               //    $htmldynaapp ="";
               //    $watersupply = $this->_engjobrequest->GetTypeofWaterSupply();   
               //      foreach ($watersupply as $key => $value) {
               //      if($value->id == $appdata->ewst_id){
               //          $htmldynaapp .=' <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value->ewst_description.'</p>';
               //        }else{
               //          $htmldynaapp .='<p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value->ewst_description.'</p>';
               //          }
               //       }
               //     $html = str_replace('{{watersupply}}',$htmldynaapp, $html);

               //    $htmldynaapp ="";
               //    $systemdisposal = $this->_engjobrequest->GetTypeofDisposalSystem();   
               //      foreach ($systemdisposal as $key => $value) {
               //      if($value->id == $appdata->edst_id){
               //          $htmldynaapp .=' <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value->edst_description.'</p>';
               //        }else{
               //          $htmldynaapp .='<p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value->edst_description.'</p>';
               //          }
               //       }
               //     $html = str_replace('{{systemofdisposal}}',$htmldynaapp, $html);  

               //  $buildingname ="";
               //  if(!empty($appdata->espa_building_official)){
               //      $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->espa_building_official);
               //      $buildingname =$gethremployename->fullname; 
               //  }
               //  //print_r($gethremployename); exit;
               //  $html = str_replace('{{bldofficialname}}', $buildingname , $html);
               //  //echo "<pre>"; print_r($appdata); exit;
               //   $html1 = file_get_contents(resource_path('views/layouts/templates/sanitarypermit2.html'));
               //  // //$html = $html;
               //  $html1 = str_replace('{{dueamount}}',$appdata->espa_amount_due, $html1);
               //  $html1 = str_replace('{{assessedby}}',$appdata->espa_assessed_by, $html1);
               //  $html1 = str_replace('{{ornumber}}',$appdata->espa_or_no, $html1);
               //  $html1 = str_replace('{{paiddate}}',$appdata->espa_date_paid, $html1);
               //  if($appdata->espa_sign_category =='1'){
               //      $data = $this->_engjobrequest->getEmployeeDetails($appdata->espa_sign_consultant_id);
               //      $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
               //      if(isset($data)){
               //      $datanew['fullname'] = $data->fullname;
               //      $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
               //      $datanew['prcno'] = $data->emp_prc_no;
               //      $datanew['ptrno'] = $data->emp_ptr_no;
               //      $datanew['tinno'] = $data->tin_no;
               //      $datanew['issueddate'] = $data->emp_issue_date;
               //      $datanew['issuedplace'] = $data->emp_issue_at;
               //      $datanew['validity'] = $data->emp_prc_validity;
               //      }
               //  }else{
               //      $data = $this->_engjobrequest->getExternalDetails($appdata->espa_sign_consultant_id);
               //      $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
               //      if(isset($data)){
               //      $datanew['fullname'] = $data->fullname;
               //      $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
               //      $datanew['prcno'] = $data->prc_no;
               //      $datanew['ptrno'] = $data->ptr_no;
               //      $datanew['tinno'] = $data->tin_no;
               //      $datanew['issueddate'] = $data->ptr_date_issued;
               //      $datanew['issuedplace'] = ""; 
               //      $datanew['validity'] = $data->prc_validity; 
               //     }
               //  }
               //  $html1 = str_replace('{{signfullname}}',$datanew['fullname'], $html1);
               //  $html1 = str_replace('{{signaddress}}',$datanew['address'], $html1);
               //  $html1 = str_replace('{{signptr}}', $datanew['ptrno'], $html1);
               //  $html1 = str_replace('{{signprcno}}',$datanew['prcno'], $html1);
               //  $html1 = str_replace('{{signdateissued}}', $datanew['issueddate'], $html1);
               //  $html1 = str_replace('{{signplaceissued}}', $datanew['issuedplace'] , $html1);
               //  $html1 = str_replace('{{signsign}}', '', $html1);
               //  $html1 = str_replace('{{signtin}}', $datanew['tinno'] , $html1);

               //  if($appdata->espa_incharge_category =='1'){
               //      $data = $this->_engjobrequest->getEmployeeDetails($appdata->espa_incharge_consultant_id);
               //      $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
               //      if(isset($data)){
               //      $datanew['fullname'] = $data->fullname;
               //      $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
               //      $datanew['prcno'] = $data->emp_prc_no;
               //      $datanew['ptrno'] = $data->emp_ptr_no;
               //      $datanew['tinno'] = $data->tin_no;
               //      $datanew['issueddate'] = $data->emp_issue_date;
               //      $datanew['issuedplace'] = $data->emp_issue_at;
               //      $datanew['validity'] = $data->emp_prc_validity;
               //      }
               //  }else{
               //      $data = $this->_engjobrequest->getExternalDetails($appdata->espa_incharge_consultant_id);
               //      $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
               //      if(isset($data)){
               //      $datanew['fullname'] = $data->fullname;
               //      $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
               //      $datanew['prcno'] = $data->prc_no;
               //      $datanew['ptrno'] = $data->ptr_no;
               //      $datanew['tinno'] = $data->tin_no;
               //      $datanew['issueddate'] = $data->ptr_date_issued;
               //      $datanew['issuedplace'] = ""; 
               //      $datanew['validity'] = $data->prc_validity; 
               //     }
               //  }
               //  $html1 = str_replace('{{inchargefullname}}',$datanew['fullname'], $html1);
               //  $html1 = str_replace('{{inchargeaddress}}',$datanew['address'], $html1);
               //  $html1 = str_replace('{{inchargeprcno}}',$datanew['prcno'], $html1);
               //  $html1 = str_replace('{{inchargeptrno}}',$datanew['prcno'], $html1);
               //  $html1 = str_replace('{{inchargedateissued}}', $datanew['issueddate'], $html1);
               //  $html1 = str_replace('{{inchargeplaceissued}}', $datanew['issuedplace'] , $html1);
               //  $html1 = str_replace('{{inchargesign}}', '', $html1);
               //  $html1 = str_replace('{{inchargetin}}', $datanew['tinno'] , $html1);

               // // $html1 = str_replace('{{inchargetin}}', $appdata->espa_applicant_consultant_id, $html1);
               //  $html1 = str_replace('{{regcertno}}', $appdata->rescertno , $html1);
               //  $html1 = str_replace('{{dateiisued}}', $appdata->dateissued , $html1);
               //  $html1 = str_replace('{{placeissued}}', $appdata->placeissued , $html1);

               //   $dataapplicant = $this->_engjobrequest->getTaxcertificatedetailsforprint($appdata->espa_applicant_consultant_id);
               //  if(!empty($dataapplicant)){ 
               //  $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
               //  $html1 = str_replace('{{applicant}}', $fullname , $html1);
               //  }else{  
               //      $html1 = str_replace('{{applicant}}', '' , $html1);
               //  }
               //  $html1 = str_replace('{{MUNCIPALITY}}', '' , $html1);
               //  //$html = $html;
               //  //echo $html;  echo $html1; exit;
               //  $filename=""; 
               //  $logo = url('/assets/images/logo.png');
               //  $logo2 = url('/assets/images/logo2.jpg');  
               //  $bgimage = url('/assets/images/clearancebackground.jpg');
               //  $html = str_replace('{{LOGO}}',$logo, $html);
               //  $html = str_replace('{{LOGO2}}',$logo2, $html);
               //  $mpdf->WriteHTML($html);
               //  $mpdf->AddPage();
               //  $mpdf->WriteHTML($html1);
               // //$filename = str_replace(' ','', $applicantname);
               //  $permitfilename =$id.$filename."sanitarypermit.pdf";
                return redirect()->route('eng-permit-print',['id'=>$id]);
                break;

                case 4:
            //      $html = file_get_contents(resource_path('views/layouts/templates/fencingpermit1.html'));
            //      $appdata = $this->_engjobrequest->getEditDetailsSFencingforprint($id);
            //      //echo "<pre>"; print_r($appdata); exit;
            //      $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
            //      $html = str_replace('{{appno}}',$appdata->efa_application_no, $html);
            //      //$html = str_replace('{{permitno}}',$appdata->ebpa_permit_no, $html);
            //      $permitno ="";
            //      $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
            //      if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
            //      $html = str_replace('{{permitno}}',$permitno, $html);
            //      // $html = str_replace('{{dateissued}}',$appdata->espa_issued_date, $html);
            //      $html = str_replace('{{formogowner}}',$appdata->efa_form_of_own, $html);
            //      // $html = str_replace('{{maineconomy}}',$appdata->maineconomy, $html);
            //      $html = str_replace('{{location}}',$appdata->ebpa_location, $html);
            //      $htmldynaapp ="";
            //      $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeselfetching();   
            //         foreach ($scopeofbuilding as $key => $value) {
            //         if($value->id == $appdata->ebs_id){
            //             $htmldynaapp .=' <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value->ebs_description.'</p>';
            //           }else{
            //             $htmldynaapp .='<p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value->ebs_description.'</p>';
            //             }
            //          }
            //      $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

            //       if($appdata->efa_sign_category =='1'){
            //         $data = $this->_engjobrequest->getEmployeeDetails($appdata->efa_sign_consultant_id);
            //         $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
            //         if(isset($data)){
            //         $datanew['fullname'] = $data->fullname;
            //         $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
            //         $datanew['prcno'] = $data->emp_prc_no;
            //         $datanew['ptrno'] = $data->emp_ptr_no;
            //         $datanew['tinno'] = $data->tin_no;
            //         $datanew['issueddate'] = $data->emp_issue_date;
            //         $datanew['issuedplace'] = $data->emp_issue_at;
            //         $datanew['validity'] = $data->emp_prc_validity;
            //        }
            //     }else{
            //         $data = $this->_engjobrequest->getExternalDetails($appdata->efa_sign_consultant_id);
            //         $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
            //         if(isset($data)){
            //         $datanew['fullname'] = $data->fullname;
            //         $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
            //         $datanew['prcno'] = $data->prc_no;
            //         $datanew['ptrno'] = $data->ptr_no;
            //         $datanew['tinno'] = $data->tin_no;
            //         $datanew['issueddate'] = $data->ptr_date_issued;
            //         $datanew['issuedplace'] = ""; 
            //         $datanew['validity'] = $data->prc_validity; 
            //        }
            //     }
            //     $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
            //     $html = str_replace('{{signaddress}}',$datanew['address'], $html);
            //     $html = str_replace('{{signptr}}', $datanew['ptrno'], $html);
            //     $html = str_replace('{{signprcno}}',$datanew['prcno'], $html);
            //     $html = str_replace('{{signdateissued}}', $datanew['issueddate'], $html);
            //     $html = str_replace('{{signplaceissued}}', $datanew['issuedplace'] , $html);
            //     $html = str_replace('{{signsign}}', '', $html);
            //     $html = str_replace('{{signvalidity}}', $datanew['validity'] , $html);
            //     $html = str_replace('{{signtin}}', $datanew['tinno'] , $html);

            //     if($appdata->efa_inspector_category =='1'){
            //         $data = $this->_engjobrequest->getEmployeeDetails($appdata->efa_inspector_consultant_id);
            //         $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
            //         if(isset($data)){
            //         $datanew['fullname'] = $data->fullname;
            //         $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
            //         $datanew['prcno'] = $data->emp_prc_no;
            //         $datanew['ptrno'] = $data->emp_ptr_no;
            //         $datanew['tinno'] = $data->tin_no;
            //         $datanew['issueddate'] = $data->emp_issue_date;
            //         $datanew['issuedplace'] = $data->emp_issue_at;
            //         $datanew['validity'] = $data->emp_prc_validity;
            //         }
            //     }else{
            //         $data = $this->_engjobrequest->getExternalDetails($appdata->efa_inspector_consultant_id);
            //         $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
            //         if(isset($data)){
            //         $datanew['fullname'] = $data->fullname;
            //         $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
            //         $datanew['prcno'] = $data->prc_no;
            //         $datanew['ptrno'] = $data->ptr_no;
            //         $datanew['tinno'] = $data->tin_no;
            //         $datanew['issueddate'] = $data->ptr_date_issued;
            //         $datanew['issuedplace'] = ""; 
            //         $datanew['validity'] = $data->prc_validity; 
            //         }
            //     }
            //     $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
            //     $html = str_replace('{{inchargeaddress}}',$datanew['address'], $html);
            //     $html = str_replace('{{inchargeprcno}}',$datanew['prcno'], $html);
            //     $html = str_replace('{{inchargeptrno}}',$datanew['prcno'], $html);
            //     $html = str_replace('{{inchargedateissued}}', $datanew['issueddate'], $html);
            //     $html = str_replace('{{inchargeplaceissued}}', $datanew['issuedplace'] , $html);
            //     $html = str_replace('{{inchargesign}}', '', $html);
            //     $html = str_replace('{{inchargevalidity}}', $datanew['validity'] , $html);
            //     $html = str_replace('{{inchargetin}}', $datanew['tinno'] , $html);
            //     $dataapplicant = $this->_engjobrequest->getTaxcertificatedetailsforprint($appdata->efa_applicant_consultant_id);
            //     if(!empty($dataapplicant)){ 
            //     $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
            //     $html = str_replace('{{applicantname}}', $fullname , $html);
            //     $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
            //     $html = str_replace('{{applicantaddress}}', $dataapplicant->rpo_address_house_lot_no.",".$dataapplicant->rpo_address_street_name , $html);
            //     $html = str_replace('{{appctcno}}', $dataapplicant->or_no , $html);
            //     $html = str_replace('{{appdateissued}}', $dataapplicant->created_at , $html);
            //     $html = str_replace('{{placeissued}}', $dataapplicant->ctc_place_of_issuance , $html);
            //     }else{  
            //         $html = str_replace('{{applicantname}}', '' , $html);
            //         $html = str_replace('{{dateapp}}', '' , $html);
            //         $html = str_replace('{{applicantaddress}}', '' , $html);
            //         $html = str_replace('{{appctcno}}', '' , $html);
            //         $html = str_replace('{{appdateissued}}', '' , $html);
            //         $html = str_replace('{{placeissued}}', '' , $html); 
            //     }
            //     $dataowner = $this->_engjobrequest->getTaxcertificatedetailsforprint($appdata->efa_owner_id);
            //     if(!empty($dataowner)){ 
            //     $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
            //     $html = str_replace('{{lotname}}', $fullname , $html);
            //     $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
            //     $html = str_replace('{{lottaxcert}}', $dataowner->or_no , $html);
            //     $html = str_replace('{{lotaddress}}', $dataowner->rpo_address_house_lot_no.",".$dataapplicant->rpo_address_street_name , $html);
            //     $html = str_replace('{{lotdateissued}}', $dataowner->created_at , $html);
            //     $html = str_replace('{{lotplaceissued}}', $dataowner->ctc_place_of_issuance , $html);
            //     }else{  
            //         $html = str_replace('{{lotname}}', '' , $html);
            //         $html = str_replace('{{lotdate}}', '' , $html);
            //         $html = str_replace('{{lottaxcert}}', '' , $html);
            //         $html = str_replace('{{lotaddress}}', '' , $html); 
            //         $html = str_replace('{{lotdateissued}}', '' , $html);
            //         $html = str_replace('{{lotplaceissued}}', '' , $html); 
            //     }
                
            //     $html1 = file_get_contents(resource_path('views/layouts/templates/fencingpermit2.html'));
            //     $html1 = str_replace('{{linegradeamt}}',$appdata->efa_linegrade_amount, $html1);
            //     $html1 = str_replace('{{lineassess}}',$appdata->efa_linegrade_processed_by, $html1);
            //     $html1 = str_replace('{{lineornumber}}',$appdata->efa_linegrade_or_no, $html1);
            //     $html1 = str_replace('{{linepaiddate}}',$appdata->efa_linegrade_date_paid, $html1);
            //     $html1 = str_replace('{{fencingamt}}',$appdata->efa_fencing_amount, $html1);
            //     $html1 = str_replace('{{fencingassess}}',$appdata->efa_fencing_processed_by, $html1);
            //     $html1 = str_replace('{{fencingornum}}',$appdata->efa_fencing_or_no, $html1);
            //     $html1 = str_replace('{{fencingpaiddate}}',$appdata->efa_fencing_date_paid, $html1);
            //     $html1 = str_replace('{{electamt}}',$appdata->efa_electrical_amount, $html1);
            //     $html1 = str_replace('{{electassess}}',$appdata->efa_electrical_processed_by, $html1);
            //     $html1 = str_replace('{{electornum}}',$appdata->efa_electrical_or_no, $html1);
            //     $html1 = str_replace('{{electpaiddate}}',$appdata->efa_electrical_date_paid, $html1);
            //     $html1 = str_replace('{{otheramt}}',$appdata->efa_others_amount, $html1);
            //     $html1 = str_replace('{{otherassess}}',$appdata->efa_others_processed_by, $html1);
            //     $html1 = str_replace('{{otehtorno}}',$appdata->efa_others_or_no, $html1);
            //     $html1 = str_replace('{{otherpaiddate}}',$appdata->efa_others_date_paid, $html1);
            //     $html1 = str_replace('{{totalamt}}',$appdata->efa_total_amount, $html1);
            //     $html1 = str_replace('{{bldofficialname}}',$appdata->efa_building_official, $html1);
            //     $html1 = str_replace('{{date}}','', $html1);
            //     $buildingname ="";
            //      if(!empty($appdata->efa_building_official)){
            //         $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->efa_building_official);
            //         $buildingname =$gethremployename->fullname; 
            //     }
            //     $html1 = str_replace('{{bldofficialname}}', $buildingname , $html1);

              
            //     //$html = $html;
            //     //echo $html; exit;
            //     $filename ="";
            //     $logo = url('/assets/images/logo.png');
            //     $logo2 = url('/assets/images/logo2.jpg');  
            //     $bgimage = url('/assets/images/clearancebackground.jpg');
            //     $html = str_replace('{{LOGO}}',$logo, $html);
            //     $html = str_replace('{{LOGO2}}',$logo2, $html);
            //     $mpdf->WriteHTML($html);
            //     $mpdf->AddPage();
            //     $mpdf->WriteHTML($html1);
            //    //$filename = str_replace(' ','', $applicantname);
            //     $permitfilename =$id.$filename."fencingpermit.pdf";
                return redirect()->route('eng-permit-print',['id'=>$id]);
                break;

                case 5:
                $html = file_get_contents(resource_path('views/layouts/templates/excavationdesign.html'));
                $appdata = $this->_engjobrequest->getEditDetailsExcavationforprint($id);
                //echo "<pre>"; print_r($appdata); exit;
                $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);

                $applicationnodyna ="";
                 foreach (str_split($appdata->eega_application_no) as $value) {
                        $applicationnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                    }
                 $html = str_replace('{{appno}}',$applicationnodyna, $html);   
                 $permitno ="";
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                  $permitnodyna ="";
                  if(!empty($permitno)){
                     foreach (str_split($permitno) as $value) {
                        $permitnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                     }
                    }else{
                        $permitnodyna .='<td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>';
                    }

                 $html = str_replace('{{permitno}}',$permitnodyna, $html);
                 $baragaydata = $this->_engjobrequest->getBarangaybyid($appdata->p_barangay_id_no);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $html = str_replace('{{formogowner}}',$appdata->eega_form_of_own, $html);
                 $html = str_replace('{{location}}',$appdata->eega_location, $html);
                 $html = str_replace('{{textb}}',$appdata->eega_location, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{houseno}}',$appdata->rpo_address_house_lot_no, $html);
                 $html = str_replace('{{telephoneno}}',$appdata->p_telephone_no, $html);
                 $html = str_replace('{{acctno}}','', $html);
                 $html = str_replace('{{lotno}}',$appdata->lotno, $html);
                 $html = str_replace('{{blkno}}',$appdata->blkno, $html);
                 $html = str_replace('{{tctno}}',$appdata->totno, $html);
                 $html = str_replace('{{taxdecno}}',$appdata->tdno, $html);
                 $html = str_replace('{{street}}',$appdata->Street, $html);
                 $html = str_replace('{{barangay}}',$baragaydata->brgy_name, $html);
                 $htmldynaapp ="";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeselexcavation();  
                  $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19'); $i=0; 
                    foreach ($scopeofbuilding as $key => $value) {
                     if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        }   
                        if($value->id == $appdata->ebs_id){
                            $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                          }else{
                            $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                            }
                        if(in_array($i,$dynaarray1)){
                           $htmldynaapp .='</tr>';  
                        }  
                        $i++;     
                     }
                $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

                 $useofoccupancyhtml ="";  $occupancycharacter ="";
                   $useofoccupancy = $this->_engjobrequest->GetTypeofOccupancyforelexacavation(); 
                   if(count($useofoccupancy)> 0){
                    $i= 1;
                    foreach ($useofoccupancy as $key => $val) { 
                        if($i%2 !=0){
                           $useofoccupancyhtml .='<tr>';  
                        }
                        
                        if($val->id == $appdata->ebot_id){  $occupancycharacter = $val->ebot_description;
                            $useofoccupancyhtml .='<td width="50%" style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                          }else{
                            $useofoccupancyhtml .='<td width="50%" style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                            }
                           if($i%2 ==0){ 
                           $useofoccupancyhtml .='</tr>'; 
                           }
                           $i++;
                     } 
                    }else{
                         $useofoccupancyhtml .='<tr>
                          <td style="border:none; padding:2px;">
                              <img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">
                          </td>
                      </tr>';
                    }

                    $html = str_replace('{{occupancyhtml}}',$useofoccupancyhtml, $html);

                    $html = str_replace('{{characterofoccupancy}}',$occupancycharacter, $html); 

                  $excavationgroundarray = $this->_engjobrequest->GetExcavationGroundType();
                  $excavationhtml = ""; $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                  $idsofeeet_id = explode(',',$appdata->eegt_id);
                  foreach ($excavationgroundarray as $keyeq => $valeq) {
                    if(in_array($i,$dynaarray)){
                           $excavationhtml .='<tr>';  
                        } 
                    if(in_array($valeq->id,$idsofeeet_id)){
                        $excavationhtml .='<td   style="border:none; padding:2px;">
                      <img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$valeq->eegt_description.'</td>'; 
                    }else{
                       $excavationhtml .='<td   style="border:none; padding:2px;">
                       <img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$valeq->eegt_description.'</td>'; 
                   }
                       if(in_array($i,$dynaarray1)){
                           $excavationhtml .='</tr>';  
                        } 
                        $i++;
                  }
                  
                 
                  if($appdata->eega_sign_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
                    $datanew['prcno'] = $data->emp_prc_no;
                    $datanew['ptrno'] = $data->emp_ptr_no;
                    $datanew['tinno'] = $data->tin_no;
                    $datanew['issueddate'] = $data->emp_issue_date;
                    $datanew['issuedplace'] = $data->emp_issue_at;
                    $datanew['validity'] = $data->emp_prc_validity;
                   }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eega_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
                    $datanew['prcno'] = $data->prc_no;
                    $datanew['ptrno'] = $data->ptr_no;
                    $datanew['tinno'] = $data->tin_no;
                    $datanew['issueddate'] = $data->ptr_date_issued;
                    $datanew['issuedplace'] = ""; 
                    $datanew['validity'] = $data->prc_validity; 
                    }
                }
                $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{signaddress}}',$appdata->signaddress, $html);
                $html = str_replace('{{signptr}}', $appdata->signptrno, $html);
                $html = str_replace('{{signprcno}}',$appdata->signprcno, $html);
                $html = str_replace('{{signdateissued}}', $appdata->signdateissued, $html);
                $html = str_replace('{{signplaceissued}}', $appdata->signplaceissued , $html);
                $html = str_replace('{{signsign}}', '', $html);
                $html = str_replace('{{signvalidity}}', $appdata->signvalidity , $html);
                $html = str_replace('{{signtin}}', $appdata->signtin, $html);

                if($appdata->eega_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eega_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $data->c_house_lot_no." ".$data->c_street_name." ".$data->c_subdivision;
                    $datanew['prcno'] = $data->emp_prc_no;
                    $datanew['ptrno'] = $data->emp_ptr_no;
                    $datanew['tinno'] = $data->tin_no;
                    $datanew['issueddate'] = $data->emp_issue_date;
                    $datanew['issuedplace'] = $data->emp_issue_at;
                    $datanew['validity'] = $data->emp_prc_validity;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eega_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    $datanew['address'] = $data->house_lot_no." ".$data->street_name." ".$data->subdivision;
                    $datanew['prcno'] = $data->prc_no;
                    $datanew['ptrno'] = $data->ptr_no;
                    $datanew['tinno'] = $data->tin_no;
                    $datanew['issueddate'] = $data->ptr_date_issued;
                    $datanew['issuedplace'] = ""; 
                    $datanew['validity'] = $data->prc_validity; 
                    }
                   
                }
                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargenaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcregno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}', $appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargesign}}', '', $html);
                $html = str_replace('{{inchargevalidity}}', $appdata->inchargevalidity , $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin , $html);


                $dataapplicant = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eega_applicant_consultant_id);
                if(!empty($dataapplicant)){ 
                $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
                $html = str_replace('{{applicantname}}', $fullname , $html);
                $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
                }else{  
                    $html = str_replace('{{applicantname}}', '' , $html);
                    $html = str_replace('{{dateapp}}', '' , $html);
                }
                $dataowner = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eega_owner_id);
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{lotname}}', $fullname , $html);
                $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
               
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                $html = str_replace('{{applicantaddress}}',$appdata->applicantaddress  , $html);
                $html = str_replace('{{appctcno}}', $appdata->applicant_comtaxcert, $html);
                $html = str_replace('{{appdateissued}}',$appdata->applicant_date_issued , $html);
                $html = str_replace('{{placeissued}}', $appdata->applicant_place_issued , $html);

                 $html = str_replace('{{lottaxcert}}', $appdata->ctcoctno , $html);
                $html = str_replace('{{lotaddress}}', $appdata->owneraddress , $html);
                $html = str_replace('{{lotdateissued}}', $appdata->owner_date_issued , $html);
                $html = str_replace('{{lotplaceissued}}', $appdata->ownerplaceissued , $html);
                
                $html1 = file_get_contents(resource_path('views/layouts/templates/excavationpermit2.html'));
                $html1 = str_replace('{{excavationgroundhtml}}',$excavationhtml, $html1);  
                // $html1 = str_replace('{{permitno}}',$permitno, $html1);
                //$html1 = str_replace('{{bldofficialname}}',$appdata->eega_building_official, $html1);
                $buildingname ="";
                 if(!empty($appdata->eega_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eega_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                $html1 = str_replace('{{bldofficialname}}', $buildingname , $html1);
                $html1 = str_replace('{{date}}','', $html1);
                //$html = $html;
                //echo $html; echo $html1; exit;
                $filename ="";
                $logo = url('/assets/images/logo.png');
                $logo2 = url('/assets/images/logo2.jpg');  
                $bgimage = url('/assets/images/clearancebackground.jpg');
                $html = str_replace('{{LOGO}}',$logo, $html);
                $html = str_replace('{{LOGO2}}',$logo2, $html);
                $mpdf->WriteHTML($html);
                $mpdf->AddPage();
                $mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id."excavationpermit.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
               
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eega_building_official);
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '207,149,385,84,2';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,90,235,50);
                        }
                    }
                }

                $mpdf->Output($filename,"I");
                break;

                case 6:
                $html = file_get_contents(resource_path('views/layouts/templates/electicalpermitdesign.html'));
                $appdata = $this->_engjobrequest->getEditDetailsElectricalforprint($id);
                //echo "<pre>"; print_r($appdata);  exit;
                 $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
                 $html = str_replace('{{appno}}',$appdata->eea_application_no, $html);
                 $permitno ="";
                 $baragaydata = $this->_engjobrequest->getBarangaybyid($appdata->p_barangay_id_no);
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                 $html = str_replace('{{permitno}}',$permitno, $html);
                 $html = str_replace('{{dateofapp}}',$appdata->eea_application_date, $html);
                 $html = str_replace('{{dateissued}}',$appdata->eea_issued_date, $html);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $html = str_replace('{{dateofstart}}',$appdata->eea_date_of_construction, $html);
                 $html = str_replace('{{costofinstall}}',$appdata->eea_estimated_cost, $html);
                 $html = str_replace('{{completiondate}}',$appdata->eea_date_of_completion, $html);
                 $html = str_replace('{{telephone}}',$appdata->taxacctno, $html);
                 $html = str_replace('{{taxaccountno}}',$appdata->p_telephone_no, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{lotno}}',$appdata->rpo_address_house_lot_no, $html);
                 //$html = str_replace('{{prepareby}}',$appdata->eea_prepared_by, $html);
                 $html = str_replace('{{Barangay}}',$baragaydata->brgy_name, $html);

                 $html = str_replace('{{constrdate}}',$appdata->eea_date_of_construction, $html);
                 $html = str_replace('{{exceptcomplete}}',$appdata->eea_date_of_completion, $html);
                 $prepareby ="";
                if(!empty($appdata->eea_prepared_by)){
                    $preparebyname = $this->_engjobrequest->getEmployeeDetails($appdata->eea_prepared_by);
                    $prepareby =$preparebyname->fullname; 
                }
                 $html = str_replace('{{prepareby}}',$prepareby, $html);
                 $html = str_replace('{{costofconstruct}}',$appdata->eea_estimated_cost, $html);
                 $htmldynaapp ="";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeselectric();  
                 $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19'); $i=0; 
                    foreach ($scopeofbuilding as $key => $value) {
                     if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        }   
                        if($value->id == $appdata->ebs_id){
                            $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                          }else{
                            $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                            }
                        if(in_array($i,$dynaarray1)){
                           $htmldynaapp .='</tr>';  
                        }  
                        $i++;     
                     }
                $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

                $useofoccupancyhtml ="";
                $useofoccupancy = $this->_engjobrequest->GetTypeofOccupancyforelectric(); 
                $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                foreach ($useofoccupancy as $key => $val) { 
                    if(in_array($i,$dynaarray)){
                           $useofoccupancyhtml .='<tr>';  
                        } 
                    if($val->id == $appdata->ebot_id){
                        $useofoccupancyhtml .='<td   style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                      }else{
                        $useofoccupancyhtml .='<td   style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                        }
                       if(in_array($i,$dynaarray1)){ 
                           $useofoccupancyhtml .='</tr>'; 
                           }
                       $i++;
                 } 
                  $html = str_replace('{{occupancyhtml}}',$useofoccupancyhtml, $html);
                  $electicequipmentarray = $this->_engjobrequest->GetElecticEquipments();
                  $equipmenthtml = ""; $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                  $idsofeeet_id = explode(',',$appdata->eeet_id);
                  foreach ($electicequipmentarray as $keyeq => $valeq) {
                    if(in_array($i,$dynaarray)){
                           $equipmenthtml .='<tr>';  
                        } 
                    if(in_array($valeq->id,$idsofeeet_id)){
                        $equipmenthtml .='<td   style="border:none; padding:2px;">
                      <img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$valeq->eeet_description.'</td>'; 
                    }else{
                       $equipmenthtml .='<td   style="border:none; padding:2px;">
                       <img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$valeq->eeet_description.'</td>'; 
                   }
                       if(in_array($i,$dynaarray1)){
                           $equipmenthtml .='</tr>';  
                        } 
                        $i++;
                  }
                  $html = str_replace('{{equipmentinstalled}}',$equipmenthtml, $html);
                  if($appdata->eea_sign_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eea_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    } 
                }
                $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{signaddress}}',$appdata->signaddress, $html);
                $html = str_replace('{{signptr}}', $appdata->signptrno, $html);
                $html = str_replace('{{signprcno}}',$appdata->signprcregno, $html);
                $html = str_replace('{{signdateissued}}', $appdata->signdateissued, $html);
                $html = str_replace('{{signplaceissued}}', $appdata->signplaceissued , $html);
                $html = str_replace('{{signsign}}', '', $html);
                $html = str_replace('{{signtin}}',$appdata->signtin , $html);

                if($appdata->eea_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eea_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }

                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargenaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcregno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}',$appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargesign}}', '', $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin, $html);
                //echo "<pre>"; print_r($appdata); exit;
                //$html1 = file_get_contents(resource_path('views/layouts/templates/electricalpermit2.html'));
                $applicantadata = array('or_no'=>'','created_at'=>'','ctc_place_of_issuance'=>'');
                $dataapplicant = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eea_applicant_consultant_id);
                if(isset($dataapplicant)){
                    $dataapplicant = $dataapplicant;
                }else{
                   $dataapplicant = (object)$applicantadata; 
                }
                $html = str_replace('{{tanno}}', '' , $html);
                $html = str_replace('{{certno}}', $appdata->rescertno , $html);
                $html = str_replace('{{dateissued}}', $appdata->dateissued , $html);
                $html = str_replace('{{placeissued}}', $appdata->placeissued , $html);

                $dataowner = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eea_owner_id);
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{applastname}}', $dataowner->rpo_custom_last_name , $html);
                $html = str_replace('{{appfirstname}}', $dataowner->rpo_first_name  , $html);
                $html = str_replace('{{appmiddlename}}',$dataowner->rpo_middle_name , $html);
                $html = str_replace('{{taxaccountno}}', $appdata->ownertaxdcno , $html);
               
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                $html = str_replace('{{appaddress}}', $appdata->owneraddress , $html);
                $html = str_replace('{{applotno}}', $appdata->ownersubdivision, $html);
                $html = str_replace('{{appstreet}}',$appdata->ownerstreet , $html);
                $html = str_replace('{{apptelephone}}', $appdata->ownertelephoneno , $html);
                $html = str_replace('{{ownermuncipality}}', $appdata->ownermuncipality , $html);
                $html = str_replace('{{streetname}}', $appdata->streetname , $html);
                $html = str_replace('{{inslocation}}', $appdata->ownerespa_location , $html);

                //print_r($dataapplicant); exit;
                $buildingname ="";
                if(!empty($appdata->eea_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eea_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                //print_r($gethremployename); exit;
                $html = str_replace('{{buidingofficial}}', $buildingname , $html);
                $html = str_replace('{{datepermit}}', $appdata->eea_issued_date , $html);
                $html = str_replace('{{amountdue}}', $appdata->eea_amount_due , $html);
                $html = str_replace('{{assessdby}}', $appdata->eea_assessed_by , $html);
                $html = str_replace('{{ornumber}}', $appdata->eea_or_no , $html);
                $html = str_replace('{{paiddate}}', $appdata->eea_date_paid , $html);


                //$html = $html;
                //echo $html; exit;
                $filename ="";
                $logo = url('/assets/images/logo.png');
                $logo2 = url('/assets/images/logo2.jpg');  
                $bgimage = url('/assets/images/clearancebackground.jpg');
                $html = str_replace('{{LOGO}}',$logo, $html);
                $html = str_replace('{{LOGO2}}',$logo2, $html);
                $mpdf->WriteHTML($html);
                // $mpdf->AddPage();
                // $mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id.$filename."electricpermit.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eea_building_official);
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '386,412,531,361,2';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,130,150,50);
                        }
                    }
                }
                $mpdf->Output($filename,"I");
                break;

                case 8:
                return redirect()->route('eng-permit-print',['id'=>$id]);
                break;

                case 9:
                $html = file_get_contents(resource_path('views/layouts/templates/electronicpermitdesign.html'));
                $appdata = $this->_engjobrequest->getEditDetailsElectronicsforprint($id);
                // echo "<pre>"; print_r($appdata); 
                 $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
                 $html = str_replace('{{appno}}',$appdata->eeta_application_no, $html);
                 $permitno ="&nbsp;";
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                 $html = str_replace('{{permitno}}',$permitno, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{houseno}}',$appdata->rpo_address_house_lot_no, $html);
                 $html = str_replace('{{formogowner}}',$appdata->eeta_form_of_own, $html);
                 $html = str_replace('{{telephone}}',$appdata->p_telephone_no, $html);
                 $html = str_replace('{{location}}',$appdata->eeta_location, $html);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $baragaydata = $this->_engjobrequest->getBarangaybyid($appdata->p_barangay_id_no);
                 $html = str_replace('{{telephoneno}}',$appdata->p_telephone_no, $html);
                 $html = str_replace('{{acctno}}','', $html);
                 $html = str_replace('{{lotno}}',$appdata->lotno, $html);
                 $html = str_replace('{{blkno}}',$appdata->blkno, $html);
                 $html = str_replace('{{tctno}}',$appdata->totno, $html);
                 $html = str_replace('{{taxdecno}}',$appdata->taxdecno, $html);
                 $html = str_replace('{{street}}',$appdata->Street, $html);
                 $html = str_replace('{{barangay}}',$baragaydata->brgy_name, $html);
                $htmldynaapp ="";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeselectronic();  
                  $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19'); $i=0; 
                    foreach ($scopeofbuilding as $key => $value) {
                     if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        }   
                        if($value->id == $appdata->ebs_id){
                            $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                          }else{
                            $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                            }
                        if(in_array($i,$dynaarray1)){
                           $htmldynaapp .='</tr>';  
                        }  
                        $i++;     
                     }
                   $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);

                 $useofoccupancyhtml =""; $occupancycharacter="";
                 $useofoccupancy = $this->_engjobrequest->GetTypeofOccupancyforelectronic(); 
                 $i= 0;
                 foreach ($useofoccupancy as $key => $val) { 
                    if($i%2 !=0){
                       $useofoccupancyhtml .='<tr>';  
                    }
                    
                    if($val->id == $appdata->ebot_id){  $occupancycharacter = $val->ebot_description;
                        $useofoccupancyhtml .='<td   style="text-align:left;border:0px solid black; padding-right:30px;"> <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$val->ebot_description.'</p></td>';
                      }else{
                        $useofoccupancyhtml .='<td   style="text-align:left;border:0px solid black; padding-right:30px;"><p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$val->ebot_description.'</p></td>';
                        }
                       if($i%2 ==0){ 
                       $useofoccupancyhtml .='</tr>'; 
                       }
                       $i++;
                   } 
                 $html = str_replace('{{occupancyhtml}}',$useofoccupancyhtml, $html);
                 $html = str_replace('{{characterofoccupancy}}',$occupancycharacter, $html); 

                 $equipmenthtml ="";
                   $electronicequipmentarray = $this->_engjobrequest->GetEquipmentsSystemType(); 
                    $i= 0;  $idsofeeetid = explode(',',$appdata->eest_id);
                    $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');
                    foreach ($electronicequipmentarray as $key => $val) { 
                        if(in_array($i,$dynaarray)){
                           $equipmenthtml .='<tr>';  
                        }
                        
                        if(in_array( $val->id,$idsofeeetid)){
                            $equipmenthtml .='<td   style="border:none; padding:2px;"> <img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->eest_description.'</td>';
                          }else{
                            $equipmenthtml .='<td   style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->eest_description.'</td>';
                            }

                           if(in_array($i,$dynaarray1)){ 
                           $equipmenthtml .='</tr>'; 
                           }
                           $i++;
                          
                    } 
                    $html = str_replace('{{equipmentinstalled}}',$equipmenthtml, $html);

                 
                  if($appdata->eeta_sign_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eeta_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                   }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eeta_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                   }
                }
                $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{signaddress}}',$appdata->signaddress, $html);
                $html = str_replace('{{signptr}}', $appdata->signptrno, $html);
                $html = str_replace('{{signprcno}}',$appdata->signprcno, $html);
                $html = str_replace('{{signdateissued}}', $appdata->signdateissued, $html);
                $html = str_replace('{{signplaceissued}}',$appdata->signplaceissued , $html);
                $html = str_replace('{{signsign}}', '', $html);
                $html = str_replace('{{signvalidity}}', $appdata->signvalidity , $html);
                $html = str_replace('{{signtin}}', $appdata->signptrno , $html);

                if($appdata->eeta_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eeta_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                   }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eeta_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }

                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargenaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcregno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}', $appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargesign}}', '', $html);
                $html = str_replace('{{inchargevalidity}}', $appdata->inchargevalidity , $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin , $html);

                $dataapplicant = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eeta_applicant_consultant_id);
                if(!empty($dataapplicant)){ 
                $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
                $html = str_replace('{{applicantname}}', $fullname , $html);
                $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
                }else{  
                    $html = str_replace('{{applicantname}}', '' , $html);
                    $html = str_replace('{{dateapp}}', '' , $html);
                }
                $html = str_replace('{{applicantaddress}}', $appdata->applicantaddress, $html);
                $html = str_replace('{{appctcno}}', $appdata->applicant_comtaxcert , $html);
                $html = str_replace('{{appdateissued}}', $appdata->applicant_date_issued , $html);
                $html = str_replace('{{placeissued}}', $appdata->applicant_place_issued , $html);
                $dataowner = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eeta_owner_id);
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{lotname}}', $fullname , $html);
                $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                $html = str_replace('{{lottaxcert}}', $appdata->owner_comtaxcert , $html);
                $html = str_replace('{{lotaddress}}', $appdata->owneraddress, $html);
                $html = str_replace('{{lotdateissued}}', $appdata->owner_date_issued , $html);
                $html = str_replace('{{lotplaceissued}}', $appdata->ownerplaceissued , $html);
                //echo "<pre>"; print_r($appdata); exit;
               // $html1 = file_get_contents(resource_path('views/layouts/templates/electronicpermit2.html'));

                $buildingname ="";
                if(!empty($appdata->eeta_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eeta_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                $html = str_replace('{{bldofficialname}}',$buildingname, $html);
                //$html = $html;
                //echo $html; exit;
                $filename ="";
                $logo = url('/assets/images/logo.png');
                $logo2 = url('/assets/images/logo2.jpg');  
                $bgimage = url('/assets/images/clearancebackground.jpg');
                $html = str_replace('{{LOGO}}',$logo, $html);
                $html = str_replace('{{LOGO2}}',$logo2, $html);
                $mpdf->WriteHTML($html);
                //$mpdf->AddPage();
                //$mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id."electronicpermit.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eeta_building_official);
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '338,319,493,261,2';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,120,180,50);
                        }
                    }
                }
                $mpdf->Output($filename,"I");
                break;

                case 10:
                break;

                case 11:
                $html = file_get_contents(resource_path('views/layouts/templates/civilpermitdesign.html'));
                $appdata = $this->_engjobrequest->getEditDetailsCivilforprint($id);
                //echo "<pre>"; print_r($appdata); exit;
                 $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
                
                 $applicationnodyna ="";
                 foreach (str_split($appdata->eca_application_no) as $value) {
                        $applicationnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                    }
                 $html = str_replace('{{appno}}',$applicationnodyna, $html);   
                 $permitno ="";
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                  $permitnodyna ="";
                  if(!empty($permitno)){
                     foreach (str_split($permitno) as $value) {
                        $permitnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                     }
                    }else{
                        $permitnodyna .='<td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>';
                    }

                 $html = str_replace('{{permitno}}',$permitnodyna, $html); 
                 $html = str_replace('{{formogowner}}',$appdata->eca_form_of_own, $html);
                 //$html = str_replace('{{MUNCIPALITY}}',$appdata->maineconomy, $html);
                 $html = str_replace('{{location}}',$appdata->eca_location, $html);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{houseno}}',$appdata->rpo_address_house_lot_no, $html);
                 $html = str_replace('{{telephone}}',$appdata->p_telephone_no, $html);
                 $baragaydata = $this->_engjobrequest->getBarangaybyid($appdata->p_barangay_id_no);
                 $html = str_replace('{{acctno}}',$appdata->eca_tax_acct_no, $html);
                 $html = str_replace('{{lotno}}',$appdata->lotno, $html);
                 $html = str_replace('{{blkno}}',$appdata->blkno, $html);
                 $html = str_replace('{{tctno}}',$appdata->totno, $html);
                 $html = str_replace('{{taxdecno}}',$appdata->taxdecno, $html);
                 $html = str_replace('{{streetnew}}',$appdata->Street, $html);
                 $html = str_replace('{{barangay}}',$baragaydata->brgy_name, $html);
                 $html = str_replace('{{maineconomy}}',$appdata->eca_economic_act, $html);
                 
                 $htmldynaapp ="";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopecivil(); 
                 $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                    foreach ($scopeofbuilding as $key => $value) {
                      if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        } 
                        if($value->id == $appdata->ebs_id){
                        $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                        }else{
                        $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                        }
                        if(in_array($i,$dynaarray1)){ 
                           $htmldynaapp .='</tr>'; 
                           }
                           $i++;
                     }
                   $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);
                   $useofoccupancyhtml ="";
                   $useofoccupancy = $this->_engjobrequest->GetTypeofOccupancyforcivil(); 
                   if(count($useofoccupancy)> 0){
                    $i= 1;
                    foreach ($useofoccupancy as $key => $val) { 
                        if($i%2 !=0){
                           $useofoccupancyhtml .='<tr>';  
                        }
                        
                        if($val->id == $appdata->ebot_id){
                            $useofoccupancyhtml .='<td width="50%" style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                          }else{
                            $useofoccupancyhtml .='<td width="50%" style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ebot_description.'</td>';
                            }
                           if($i%2 ==0){ 
                           $useofoccupancyhtml .='</tr>'; 
                           }
                           $i++;
                     } 
                    }else{
                         $useofoccupancyhtml .='<tr>
                          <td style="border:none; padding:2px;">
                              <img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">
                          </td>
                      </tr>';
                    }

                    $html = str_replace('{{occupancyhtml}}',$useofoccupancyhtml, $html);

                  if($appdata->eca_sign_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eca_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eca_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }
                $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{signaddress}}',$appdata->signaddress, $html);
                $html = str_replace('{{signptr}}', $appdata->signptrno, $html);
                $html = str_replace('{{signprcno}}',$appdata->signprcno, $html);
                $html = str_replace('{{signdateissued}}', $appdata->signdateissued, $html);
                $html = str_replace('{{signplaceissued}}',$appdata->signplaceissued , $html);
                $html = str_replace('{{signsign}}', '', $html);
                $html = str_replace('{{signvalidity}}', $appdata->signvalidity , $html);
                $html = str_replace('{{signtin}}', $appdata->signptrno , $html);

                if($appdata->eca_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eca_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eca_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                   
                }
                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargenaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcregno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}', $appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargesign}}', '', $html);
                $html = str_replace('{{inchargevalidity}}', $appdata->inchargevalidity , $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin , $html);

                 $dataapplicant = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eca_applicant_consultant_id);
                if(!empty($dataapplicant)){ 
                $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
                $html = str_replace('{{applicantname}}', $fullname , $html);
                $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
                }else{  
                    $html = str_replace('{{applicantname}}', '' , $html);
                    $html = str_replace('{{dateapp}}', '' , $html);
                }
                $html = str_replace('{{applicantaddress}}', $appdata->applicantaddress, $html);
                $html = str_replace('{{appctcno}}', $appdata->applicant_comtaxcert , $html);
                $html = str_replace('{{appdateissued}}', $appdata->applicant_date_issued , $html);
                $html = str_replace('{{placeissued}}', $appdata->applicant_place_issued , $html);
                $dataowner = $this->_engjobrequest->GetOwnerDetailsforprint($appdata->eca_owner_id);
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{lotname}}', $fullname , $html);
                $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                
                $html = str_replace('{{lottaxcert}}', $appdata->ownerctcno , $html);
                $html = str_replace('{{lotaddress}}', $appdata->owneraddress, $html);
                $html = str_replace('{{lotdateissued}}', $appdata->owner_date_issued , $html);
                $html = str_replace('{{lotplaceissued}}', $appdata->ownerplaceissued , $html);
                //$html1 = file_get_contents(resource_path('views/layouts/templates/civilpermit2.html'));
                //$html1 = str_replace('{{bldofficialname}}',$appdata->eca_building_official, $html1);
                $buildingname ="";
                if(!empty($appdata->eca_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eca_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                $html = str_replace('{{bldofficialname}}',$buildingname, $html);
                //$html = $html;
                //echo $html;  exit;
                $filename ="";
               
                $mpdf->WriteHTML($html);
                // $mpdf->AddPage();
                // $mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id."civilpermit.pdf";
                //$filename =$id.$filename."electronicpermit.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eca_building_official);
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '208,191,412,99,2';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,80,230,50);
                        }
                    }
                }
                $mpdf->Output($filename,"I");
                break;

                case 13:
                $html = file_get_contents(resource_path('views/layouts/templates/architecturalpermit1.html'));
                $appdata = $this->_engjobrequest->getEditDetailsArchitectureforprint($id);
                //echo "<pre>"; print_r($appdata); exit;
                $html = str_replace('{{MUNCIPALITY}}',$appdata->mun_desc, $html);
                 
                 $applicationnodyna ="";
                 foreach (str_split($appdata->eea_application_no) as $value) {
                        $applicationnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                    }
                 $html = str_replace('{{Applicationno}}',$applicationnodyna, $html);   
                 $permitno ="";
                 $permitnodata = $this->_engjobrequest->getpermitno($appdata->ebpa_permit_no);
                 if(!empty( $permitnodata)){ $permitno = $permitnodata->ebpa_permit_no;}
                  $permitnodyna ="";
                  if(!empty($permitno)){
                     foreach (str_split($permitno) as $value) {
                        $permitnodyna .='<td style="width:18px; height: 18px;">'.$value.'</td>';
                     }
                    }else{
                        $permitnodyna .='<td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>
                        <td style="width:18px; height: 18px;"></td>';
                    }

                 $html = str_replace('{{permitno}}',$permitnodyna, $html); 
                 $html = str_replace('{{footprint}}',$appdata->eaa_footprint, $html);
                 $html = str_replace('{{imperviousarea}}',$appdata->eaa_impervious_area, $html);
                 $html = str_replace('{{unpavedarea}}',$appdata->eaa_unpaved_area, $html);
                 $html = str_replace('{{otherper}}',$appdata->eaa_others_percentage, $html);
                 $html = str_replace('{{formogowner}}',$appdata->eea_form_of_own, $html);
                 $html = str_replace('{{location}}',$appdata->eea_location, $html);
                 $html = str_replace('{{lastname}}',$appdata->rpo_custom_last_name, $html);
                 $html = str_replace('{{firstname}}',$appdata->rpo_first_name, $html);
                 $html = str_replace('{{middlename}}',$appdata->rpo_middle_name, $html);
                 $html = str_replace('{{street}}',$appdata->rpo_address_street_name, $html);
                 $html = str_replace('{{subdivision}}',$appdata->rpo_address_subdivision, $html);
                 $html = str_replace('{{houseno}}',$appdata->rpo_address_house_lot_no, $html);
                 $html = str_replace('{{telephone}}',$appdata->p_telephone_no, $html);
                 $baragaydata = $this->_engjobrequest->getBarangaybyid($appdata->p_barangay_id_no);
                 $html = str_replace('{{acctno}}',$appdata->eea_tax_acct_no, $html);
                 $html = str_replace('{{lotno}}',$appdata->lotno, $html);
                 $html = str_replace('{{blkno}}',$appdata->blkno, $html);
                 $html = str_replace('{{tctno}}',$appdata->totno, $html);
                 $html = str_replace('{{taxdecno}}',$appdata->taxdecno, $html);
                 $html = str_replace('{{streetnew}}',$appdata->Street, $html);
                 $html = str_replace('{{barangay}}',$baragaydata->brgy_name, $html);
                 $html = str_replace('{{maineconomy}}',$appdata->eea_economic_act, $html);
                 $htmldynaapp ="";
                 $scopeofbuilding = $this->_engjobrequest->GetBuildingScopeselarchitecture(); 
                 $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');  $i=0;
                    foreach ($scopeofbuilding as $key => $value) {
                      if(in_array($i,$dynaarray)){
                           $htmldynaapp .='<tr>';  
                        }  
                    if($value->id == $appdata->ebs_id){
                        $htmldynaapp .=' <td style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                      }else{
                        $htmldynaapp .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$value->ebs_description.'</td>';
                        }
                        if(in_array($i,$dynaarray1)){ 
                           $htmldynaapp .='</tr>'; 
                           }
                           $i++;
                     }
                   $html = str_replace('{{scopeofwork}}',$htmldynaapp, $html);
                   $electricfeaturehtml ="";
                   $electricalfeaturetype = $this->_engjobrequest->GetElecticArchitectureFeatureType(); 
                    $i= 0;  $idsofeeetid = explode(',',$appdata->eeft_id);
                    $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');
                    foreach ($electricalfeaturetype as $key => $val) { 
                        if(in_array($i,$dynaarray)){
                           $electricfeaturehtml .='<tr>';  
                        }
                        
                        if(in_array( $val->id,$idsofeeetid)){
                            $electricfeaturehtml .='<td   style="border:none; padding:2px;"> <img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->eaft_description.'</td>';
                          }else{
                            $electricfeaturehtml .='<td   style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->eaft_description.'</td>';
                            }

                           if(in_array($i,$dynaarray1)){ 
                           $electricfeaturehtml .='</tr>'; 
                           }
                           $i++;
                          
                     } 
                    $html = str_replace('{{electricfeature}}',$electricfeaturehtml, $html);
                    $confirmancefirehtml ="";
                    $confirmancefire = $this->_engjobrequest->GetConfirmnaceFireType(); 
                    $i= 1;  $idsofectfc_id = explode(',',$appdata->ectfc_id);
                    $dynaarray = array('0','4','8','12','16');  $dynaarray1 = array('3','7','11','15','19');
                    foreach ($confirmancefire as $key => $val) { 
                        if($i%2 !=0){
                           $confirmancefirehtml .='<tr>';  
                        }
                        
                        if(in_array( $val->id,$idsofectfc_id)){
                            $confirmancefirehtml .='<td   style="border:none; padding:2px;"><img src="'.$checked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ectfc_description.'</p></td>';
                          }else{
                            $confirmancefirehtml .='<td style="border:none; padding:2px;"><img src="'.$unchecked.'" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">'.$val->ectfc_description.'</td>';
                            }

                           if($i%2 ==0){
                           $confirmancefirehtml .='</tr>'; 
                           }
                           $i++;
                          
                     } 
                    $html = str_replace('{{electricfeature}}',$electricfeaturehtml, $html);

                    $html = str_replace('{{comformancefire}}',$confirmancefirehtml, $html);
                  if($appdata->eea_sign_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eea_sign_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }
                $html = str_replace('{{signfullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{signaddress}}',$appdata->signaddress, $html);
                $html = str_replace('{{signptr}}', $appdata->signptrno, $html);
                $html = str_replace('{{signprcno}}',$appdata->signprcno, $html);
                $html = str_replace('{{signdateissued}}', $appdata->signdateissued, $html);
                $html = str_replace('{{signplaceissued}}',$appdata->signplaceissued , $html);
                $html = str_replace('{{signsign}}', '', $html);
                $html = str_replace('{{signvalidity}}', $appdata->signvalidity , $html);
                $html = str_replace('{{signtin}}', $appdata->signptrno , $html);

                if($appdata->eea_incharge_category =='1'){
                    $data = $this->_engjobrequest->getEmployeeDetails($appdata->eea_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                     if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }else{
                    $data = $this->_engjobrequest->getExternalDetails($appdata->eea_incharge_consultant_id);
                    $datanew = array('fullname'=>'','address'=>'','prcno'=>'','ptrno'=>'','tinno'=>'','issueddate'=>'','issuedplace'=>'','validity'=>'');
                    if(isset($data)){
                    $datanew['fullname'] = $data->fullname;
                    }
                }
                $html = str_replace('{{inchargefullname}}',$datanew['fullname'], $html);
                $html = str_replace('{{inchargeaddress}}',$appdata->inchargenaddress, $html);
                $html = str_replace('{{inchargeprcno}}',$appdata->inchargeprcregno, $html);
                $html = str_replace('{{inchargeptrno}}',$appdata->inchargeptrno, $html);
                $html = str_replace('{{inchargedateissued}}', $appdata->inchargedateissued, $html);
                $html = str_replace('{{inchargeplaceissued}}', $appdata->inchargeplaceissued , $html);
                $html = str_replace('{{inchargesign}}', '', $html);
                $html = str_replace('{{inchargevalidity}}', $appdata->inchargevalidity , $html);
                $html = str_replace('{{inchargetin}}', $appdata->inchargetin , $html);

                 $dataapplicant = $this->_engjobrequest->getTaxcertificatedetailsforprint($appdata->eea_applicant_consultant_id);
                if(!empty($dataapplicant)){ 
                $fullname = $dataapplicant->rpo_first_name." ".$dataapplicant->rpo_middle_name." ".$dataapplicant->rpo_custom_last_name;
                $html = str_replace('{{applicantname}}', $fullname , $html);
                $html = str_replace('{{dateapp}}', $dataapplicant->created_at , $html);
                }else{  
                    $html = str_replace('{{applicantname}}', '' , $html);
                    $html = str_replace('{{dateapp}}', '' , $html);
                }
                $html = str_replace('{{applicantaddress}}', $appdata->applicantaddress, $html);
                $html = str_replace('{{appctcno}}', $appdata->applicant_comtaxcert , $html);
                $html = str_replace('{{appdateissued}}', $appdata->applicant_date_issued , $html);
                $html = str_replace('{{placeissued}}', $appdata->applicant_place_issued , $html);

                $dataowner = $this->_engjobrequest->getTaxcertificatedetailsforprint($appdata->eea_owner_id);
                if(!empty($dataowner)){ 
                $fullname = $dataowner->rpo_first_name." ".$dataowner->rpo_middle_name." ".$dataowner->rpo_custom_last_name;
                $html = str_replace('{{lotname}}', $fullname , $html);
                $html = str_replace('{{lotdate}}', $dataowner->created_at , $html);
                }else{  
                    $html = str_replace('{{lotname}}', '' , $html);
                    $html = str_replace('{{lotdate}}', '' , $html);
                }
                $html = str_replace('{{lottaxcert}}', $appdata->ownerctcno , $html);
                $html = str_replace('{{lotaddress}}', $appdata->owneraddress, $html);
                $html = str_replace('{{lotdateissued}}', $appdata->owner_date_issued , $html);
                $html = str_replace('{{lotplaceissued}}', $appdata->ownerplaceissued , $html);
                $html1 = file_get_contents(resource_path('views/layouts/templates/architecturalpermit2.html'));
                //$html1 = str_replace('{{bldofficialname}}',$appdata->eea_building_official, $html1);
                $buildingname ="";
                if(!empty($appdata->eea_building_official)){
                    $gethremployename = $this->_engjobrequest->getEmployeeDetails($appdata->eea_building_official);
                    $buildingname =$gethremployename->fullname; 
                }
                $html = str_replace('{{bldofficialname}}',$buildingname, $html);
                //$html = $html;
                //echo $html;  exit;
                $filename ="";
                $logo = url('/assets/images/logo.png');
                $logo2 = url('/assets/images/logo2.jpg');  
                $bgimage = url('/assets/images/clearancebackground.jpg');
                //$html = file_get_contents(resource_path('views/layouts/templates/architecturalpermitdesign1.html'));
                $mpdf->WriteHTML($html);
                // $mpdf->AddPage();
                // $mpdf->WriteHTML($html1);
               //$filename = str_replace(' ','', $applicantname);
                $filename =$id."architecturalpermit.pdf";
                //$filename =$id.$filename."electronicpermit.pdf";
                //$mpdf->Output($filename, "I");
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                // PDF::Output($filename,'I'); exit;
                $isSignVeified = 1;
                $officialid = $this->_commonmodel->getuseridbyempid($appdata->eea_building_official);
                $signType = $this->_commonmodel->getSettingData('sign_settings');
                if(!$signType || !$isSignVeified){
                    $mpdf->Output($folder.$filename);
                }else{
                    $signature ="";
                    if(!empty($officialid->user_id)){
                    $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                    }
                    $path =  public_path().'/uploads/e-signature/'.$signature;
                    if($isSignVeified==1 && $signType==2){
                        $arrData['signerXyPage'] = '232,533,381,495,3';
                        if(!empty($signature) && File::exists($path)){
                            // Apply Digital Signature
                            $mpdf->Output($folder.$filename,'F');
                            $arrData['signaturePath'] = $signature;
                            $arrData['filename'] = $filename;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                    if($isSignVeified==1 && $signType==1){
                        // Apply E-Signature
                        if(!empty($signature) && File::exists($path)){
                            $mpdf->Image($path,85,105,40);
                        }
                    }
                }
                $mpdf->Output($filename,"I");
                break;
        }
        //$applicantname = $filename."certificateofoccupancy.pdf";
        // $folder =  public_path().'/uploads/jobrequestgpermit/';
        // if(!File::exists($folder)) { 
        //     File::makeDirectory($folder, 0755, true, true);
        // }
        // $filename =  $permitfilename;
        // $mpdf->Output($filename, "I");
            
    }
}
