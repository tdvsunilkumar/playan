<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BfpApplicationForm; 
use App\Models\HrEmployee; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use File;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;
class BfpApplicationFormController extends Controller
{
    
    public $data = [];
    public $postdata = [];

    public $arrprofile = array(""=>"Select Owner");
    public $yeararr = array(""=>"Select Year");
    public $arrBarangay = array(""=>"Please Select");
    public $accountnos = array(""=>"Select Account Number");
    public $nofbusscode = array(""=>"Select Code");
    public $arrOcupancy = array(""=>"Please Select");
    public $citizen = array(""=>"Please Select");
    public $employee = array(""=>"Please Select");
    public $arrApplicationType = array();
    Public $arrPurpose = array();
    Public $arrCategory = array();
    private $slugs;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness(); 
        $this->_bfpapplicationform = new BfpApplicationForm();
        $this->_hrEmployee= new HrEmployee();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->dataApplication = array('id'=>'','ba_code'=>'','profile_id'=>'','brgy_code'=>'','bff_year'=>'','bff_date'=>'','bff_application_type'=>'','bff_application_no'=>'','bff_representative_code'=>'','subclass_code'=>'','bot_code'=>'','bot_occupancy_type'=>'','ba_building_total_area_occupied'=>'','bff_no_of_storey'=>'','bff_email_addrress'=>'','bff_telephone_no'=>'','bff_mobile_no'=>'','bff_req_occupancy_fsic'=>'1','bff_req_new_business'=>'','bff_req_renew_business'=>'','bff_verified_by'=>'','bff_veridifed_date'=>'','bff_cro_date'=>'','bff_cro_in'=>'','bff_cro_out'=>'','bff_fca_date'=>'','bff_fca_in'=>'','bff_fca_out'=>'','bff_fcca_date'=>'','bff_fcca_in'=>'','bff_fcca_out'=>'','bff_cfses1_date'=>'','bff_cfses1_in'=>'','bff_cfses1_out'=>'','bff_fsi_date'=>'','bff_fsi_in'=>'','bff_fsi_out'=>'','bff_cfses2_date'=>'','bff_cfses2_in'=>'','bff_cfses2_out'=>'','bff_cfm_mfm_date'=>'','bff_cfm_mfm_in'=>'','bff_cfm_mfm_out'=>'','bff_status'=>'1','bff_remarks'=>'','busn_id'=>'','bff_representative_id'=>'','busn_bldg_total_floor_area'=>'','busn_bldg_area'=>'','bot_id'=>'','bend_id'=>'','client_id'=>'','ba_business_account_no'=>'','barangay_id'=>'','bff_category'=>'','purpase'=>'','bff_verified_status'=>'','bff_certified_by'=>'','bff_certified_status'=>'','bff_certified_date'=>'','bff_certified_position'=>'');
         $this->slugs = 'fire-protection/endorsement';

        foreach ($this->_bfpapplicationform->getBarangay() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code;
        } 
        foreach ($this->_bfpapplicationform->getOcupancy() as $val) {
            $this->arrOcupancy[$val->id]=$val->bot_occupancy_type;
        } 
        foreach ($this->_bfpapplicationform->getCitizen() as $val) {
            if($val->suffix){
              $this->citizen[$val->id]=$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name.', '.$val->suffix;
            }
            else{
                $this->citizen[$val->id]=$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name;
            }
        }
        

    }
    public function getocuppancyDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getocuppancyDetails($id);
        echo json_encode($data);
    }
    public function getBotIdDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getocuppancyDetails($id);
        echo json_encode($data);
    }
    public function getRepresentative(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getRepresentative($id);
        echo json_encode($data);
    }
    public function getClientDetails(Request $request){
       $id= $request->input('id');
       $getgroups = $this->_bfpapplicationform->getClientDetails($id);
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
         if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
      }

      echo $htmloption;
      $getgroups = $this->_bfpapplicationform->getCitizen();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
         if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
      }
      
      echo $htmloption;
    }
    public function getCertified(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->employeeData($id);
        echo json_encode($data);
    }
    public function getPurposeDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getPurposeDetails($id);
        echo json_encode($data);
    }
    public function getCategoryDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getCategoryDetails($id);
        echo json_encode($data);
    }
    public function index(Request $request)
    {   
        $yeararr= $this->yeararr;
        $year ='2020';
        for($i=0;$i<=10;$i++){
            $yeararr[$year] =$year; 
            $year = $year +1;
        }
        
            return view('bfpapplicationform.index',compact('yeararr'));
        
    }
    public function getList(Request $request){
        $data=$this->_bfpapplicationform->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['bff_application_no']=$row->bff_application_no;    
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['ba_business_name']=$row->ba_business_name;
            $arr[$i]['business_address']=$row->ba_address_house_lot_no.','.$row->ba_address_street_name;
            $arr[$i]['p_complete_name_v1']=$row->p_complete_name_v1;
            $arr[$i]['bot_occupancy_type']=$row->bot_occupancy_type;
            $arr[$i]['bff_no_of_storey']=$row->bff_no_of_storey;
            $arr[$i]['created_at']=date("M d, Y",strtotime($row->ba_date_started));
            $arr[$i]['status']=($row->bff_status==1?'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Close</span>':'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Open</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpapplicationform/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update BFP Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpapplicationform/asses?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Assess Now"  data-title="Assess Now">
                    <i class="ti ti-currency-dollar text-white"></i>
                    </a>
                </div><div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Bfp Assessment"  data-title="Print Bfp Assessment" class="mx-3 btn print btn-sm  align-items-center" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a>
                 </div>
                 <div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Bfp Cheque"  data-title="Print Bfp Cheque" class="mx-3 btn printCheque btn-sm" id="'.$row->id.'">
                            <i class="ti ti-printer text-white"></i>
                        </a>
                    </div>
                 ';
                 
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
   
   
    public function assessNow(Request $request){
        $id = $request->input('id'); $arrFees =array(); $arrAccountNum =array(); $arrtaxFees =array(); $data= array();
        $data = $this->_bfpapplicationform->getUserBusinessDetails($id);
        if(count($data)>0){
            $data = $data[0];
        }
        $feemasterarray = array();
        $feemaster  = $this->_bfpapplicationform->getAllFeeMaster();
        foreach ($feemaster as $key => $value) {
            $feemaster[$value->id] = $value->fee_name;
        }
        // echo "<pre>"; print_r($feemasterarray); exit;
        if($request->input('id') > 0 && $request->input('submit')!=""){
           $getdata = $this->_bfpapplicationform->getUserBusinessDetails($request->input('id'));
           $bfpassessmentdata = array();
           $bfpassessmentdata['bff_id']=$request->input('id');
           $bfpassessmentdata['ba_code']=$getdata[0]->baid;
           $bfpassessmentdata['ba_business_account_no']=$getdata[0]->ba_business_account_no;
           $bfpassessmentdata['p_code']=$getdata[0]->profile_id;
           $bfpassessmentdata['brgy_code']=$getdata[0]->brgycode;
           $bfpassessmentdata['bff_application_type']=$getdata[0]->bff_application_type;
           $bfpassessmentdata['bff_application_no']=$getdata[0]->bff_application_no;;
           $bfpassessmentdata['bfpas_ops_code']='RO30419';
           $bfpassessmentdata['bfpas_ops_year']=$getdata[0]->bff_year;
           $bfpassessmentdata['bfpas_ops_no']='123456';
           $bfpassessmentdata['bfpas_total_amount']=$getdata[0]->subtotal;
           $bfpassessmentdata['bfpas_total_amount_paid']=$getdata[0]->checkamount_paid + $getdata[0]->cashamount_paid;
           $bfpassessmentdata['bfpas_is_fully_paid']='1';
           $bfpassessmentdata['bfpas_payment_or_no']=$getdata[0]->order_number;
           $bfpassessmentdata['bfpas_remarks']='';
           $this->_bfpapplicationform->addbfpassessment($bfpassessmentdata);
           //echo "<pre>"; print_r($getdata); exit;
        }else{
            $totalfirefees = 0;
            if($totalfirefees < 500){ $totalfirefees ='500'; }
        }
        return view('bfpapplicationform.assessnew',compact('arrFees','arrAccountNum','arrtaxFees','data','totalfirefees','feemasterarray'));
    }
    public function getRefreshCitizen(Request $request){
       $getgroups = $this->_bfpapplicationform->getRefreshCitizen();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
        if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.', '.$value->suffix.'</option>';
        }else{
            $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
        }
        
      }
      echo $htmloption;
    }  
    public function getRefreshEmployee(Request $request){
       $getgroups = $this->_bfpapplicationform->getRefreshEmployee();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
         if($value->suffix){
            $htmloption .='<option value="'.$value->id.'">'.$value->title.' '.$value->firstname.' '.$value->middlename.' '.$value->lastname.', '.$value->suffix.'</option>';
        }else{
           $htmloption .='<option value="'.$value->id.'">'.$value->title.' '.$value->firstname.'  '.$value->middlename.' '.$value->lastname.'</option>';
        }
      }
      echo $htmloption;
    }
    public function getClientsBfpAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_bfpapplicationform->getHrEmplyeesAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    
    public function store(Request $request){
        $this->is_permitted($this->slugs, 'update');
        $user_id= \Auth::user()->id;
        $auth=$this->_hrEmployee->empIdByUserId($user_id);
        $busn_id =  $request->input('busn_id');
        $bend_id =  $request->input('bend_id');
        $year =  $request->input('year');
        $categoryNew = 1;
        $categoryReNew = 2;
        $dataPSIC = $this->_bfpapplicationform->getBusinessPSIC($busn_id);
        $dataCategoryRequirment = $this->_bfpapplicationform->getCategoryRequirment($busn_id,$bend_id,$categoryNew,$year);
        // echo "<pre>";print_r($dataCategoryRequirment);exit;
        $dataCategoryRequirmentRenew = $this->_bfpapplicationform->getCategoryRequirment($busn_id,$bend_id,$categoryReNew,$year);
        // print_r($dataCategoryRequirment);exit;
        $dataApplication = (object)$this->dataApplication;
        $statusBff_verified_status="0";
        $statusBff_verified_status= $request->input('bff_verified_status');
        $statusBff_certified_status="0";
        $statusBff_certified_status= $request->input('bff_certified_status');

        $arrBarangay = $this->arrBarangay;
        $arrOcupancy = $this->arrOcupancy;
        $citizen = $this->citizen;
        
        foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
            if($val->suffix){
              $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
            }
            else{
                $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }
        foreach ($this->_bfpapplicationform->getEmployeeUser($user_id) as $val) {
            if($val->suffix){
              $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
            }
            else{
                $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }
        $employee= $this->employee;
        $arrbDetails=array();
        $arrNature=array();
        $arrNatureNew=array();
        $arrdocDtls=array();
        if($request->input('busn_id')>0 && $request->input('submit')==""){
            // $dataApplication = BfpApplicationForm::find($request->input('busn_id'));
            $dataApplicationBusnDetails =$this->_bfpapplicationform->getApplicationEdit($busn_id,$bend_id,$year);

            if($dataApplicationBusnDetails){
               $arrdocDtls = $this->generateDocumentListInspection($dataApplicationBusnDetails->bff_document,$dataApplicationBusnDetails->bend_id);
            // echo "<pre>"; print_r($arrdocDtls); exit;
            }else{
                $arrdocDtls="";
            }
            
                 // echo "<pre>"; print_r($arrdocDtls); exit;
           
            $arrNature = $this->_bfpapplicationform->getrequirementRelation($busn_id,$bend_id,$categoryReNew,$year);
            // echo "<pre>";print_r($arrNature);exit;
            $arrNatureNew = $this->_bfpapplicationform->getrequirementRelation($busn_id,$bend_id,$categoryNew,$year);
            // echo "<pre>"; print_r($arrNature); exit;
         
            if(isset($dataApplicationBusnDetails)){
                $dataApplication=$dataApplicationBusnDetails;
                if($dataApplicationBusnDetails->bff_certified_by == $auth->id || $dataApplicationBusnDetails->bff_verified_by == $auth->id)
                {
                    $user_id=0;
                    foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                        if($val->suffix){
                          $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.','.$val->suffix;
                        }
                        else{
                            $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
                        }
                    }
                    $employee= $this->employee;
                }
            }else{
               $getdatausersave = $this->_bfpapplicationform->CheckFormdataExist('1',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $dataApplication->bff_verified_by = $usersaved->bff_verified_by;
                  $dataApplication->bff_certified_by = $usersaved->bff_certified_by;
                  $dataApplication->bff_certified_position = $usersaved->bff_certified_position;
               }
            }
        
        }    
                foreach ($this->_bfpapplicationform->getaccountnumbers() as $val) {
                  $this->accountnos[$val->busn_registration_no]=$val->busn_registration_no;
           
        }
        foreach ($this->_bfpapplicationform->getApplicationType() as $val) {
            $this->arrApplicationType[$val->id]=$val->btype_name;
        }
        foreach ($this->_bfpapplicationform->getPurpose() as $val) {
            $this->arrPurpose[$val->id]=$val->bap_desc;
        }
        foreach ($this->_bfpapplicationform->getCategoty() as $val) {
            $this->arrCategory[$val->id]=$val->bac_desc;
        }
        foreach ($this->_bfpapplicationform->getnatureofBussinessCodes() as $val) {
            $this->nofbusscode[$val->id]=$val->subclass_code;
        } 
        foreach ($this->_bfpapplicationform->getTaxTyeps() as $val) {
            $this->taxtypes[$val->id]=$val->tax_class_type_code;
        } 
        $arrCategory = $this->arrCategory;
        $arrPurpose = $this->arrPurpose;
        $arrApplicationType = $this->arrApplicationType;
        $accountnos = $this->accountnos;
        $nofbusscode = $this->nofbusscode;
        
        foreach((array)$this->dataApplication as $key=>$val){
            $this->dataApplication[$key] = $request->input($key);
        }
        $this->dataApplication['updated_by']=\Auth::user()->id;
        $this->dataApplication['updated_at'] = date('Y-m-d H:i:s');
        $verify_send=1;
        $certified_send=1;
        if($request->input('submit')!=""){
            if($request->input('id')>0){
                $ext_app_data=$this->_bfpapplicationform->findDataById($request->input('id'));
                if($statusBff_verified_status==1){
                  $this->dataApplication['bff_veridifed_date'] = date('Y-m-d H:i:s');
                }
                if($statusBff_certified_status==1){
                  $this->dataApplication['bff_certified_date'] = date('Y-m-d H:i:s');
                }
                // print_r($this->dataApplication['bff_veridifed_date']);exit;
                //echo "<pre>"; print_r($request->input()); exit;
                $dataArr['bff_id']=$request->input('id');
                $dataArr['busn_id']=$busn_id;
                $dataArr['bend_id']=$request->input('bend_id');
                $dataArr['client_id']=$request->input('client_id');
                $dataArr['brgy_id']=$request->input('barangay_id');
                $this->_bfpapplicationform->updateDataInspection($request->input('id'),$dataArr);
               $this->_bfpapplicationform->updateData($request->input('id'),$this->dataApplication);
               $lastId=$request->input('id');
               $success_msg = 'BFP application form updated successfully.';
               if($ext_app_data->bff_verified_status == 1)
               {
                $verify_send=0;
               }
               if($ext_app_data->bff_certified_status == 1)
               {
                $certified_send=0;
               }

            }else{
                $user_savedata = array();
                $user_savedata['bff_verified_by'] = $request->input('bff_verified_by');
                $user_savedata['bff_certified_by'] = $request->input('bff_certified_by');
                $user_savedata['bff_certified_position'] = $request->input('bff_certified_position');
                $userlastdata = array();
                $userlastdata['form_id'] = 1;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_bfpapplicationform->CheckFormdataExist('1',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_bfpapplicationform->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_bfpapplicationform->addusersaveData($userlastdata);
                }
                $this->dataApplication['created_by']=\Auth::user()->id;
                $this->dataApplication['created_at'] = date('Y-m-d H:i:s');
                $this->dataApplication['busn_id'] =$busn_id;
                $this->dataApplication['bff_status']=1;

                $lastId=$this->_bfpapplicationform->addData($this->dataApplication);


                // $dataArr['bff_code']=$lastId;
                // $dataArr['busn_id']=$busn_id;
                // $dataArr['bend_id']=$request->input('bend_id');
                // $dataArr['client_id']=$request->input('client_id');
                // $dataArr['brgy_id']=$request->input('barangay_id');
                // $this->_bfpapplicationform->addDataInspection($dataArr);
                if($request->input('bff_application_no')){
                     $arr['bff_application_no']=$request->input('bff_application_no');
                }else{
                    $accNo = str_pad($lastId, 6, '0', STR_PAD_LEFT);
                    $arr['bff_application_no']="09".$accNo;
                }
                
              
                $this->_bfpapplicationform->updateData($lastId,$arr);

                $success_msg = 'BFP application form added successfully.';
            }
            if($lastId>0){
               $this->addRequirment($request,$lastId);
            }

            if($request->input('bff_verified_by') == $auth->id && $request->input('bff_verified_status') == 1 && $verify_send == 1)
                {
                    $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                    $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',5)->where('type_id',1)->where('is_active',1)->first();
                    if(!empty($smsTemplate))
                    {
                        $receipient=$arrBuss->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                        $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                        $this->send($msg, $receipient);
                    }
                }
            if($request->input('bff_certified_by') == $auth->id && $request->input('bff_certified_status') == 1 && $certified_send == 1)
                {
                    $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                    $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',6)->where('type_id',1)->where('is_active',1)->first();
                    if(!empty($smsTemplate))
                    {
                        $receipient=$arrBuss->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                        $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                        $this->send($msg, $receipient);
                    }
                }

           

            return redirect('fire-protection/endorsement')->with('success', __($success_msg));
        }
        return view('bfpapplicationform.create',compact('dataApplication','auth','accountnos','arrBarangay','arrOcupancy','nofbusscode','arrbDetails','citizen','dataPSIC','arrApplicationType','arrPurpose','arrCategory','dataCategoryRequirment','dataCategoryRequirmentRenew','arrNature','arrNatureNew','employee','arrdocDtls'));
        
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
    public function uploadAttachment(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('year');
        $bbendo_id =  $request->input('bbendo_id');
        $arrEndrosment = $this->_bfpapplicationform->getApplicationDetails($busn_id,$bbendo_id,$year);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/document_requirement/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['business_id'] = $busn_id;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->bff_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['bff_document'] = json_encode($finalJsone);
                $this->_bfpapplicationform->updateApplication($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['bff_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $busn_id = $request->input('id');
        $year = $request->input('year');
        $bbendo_id = $request->input('bbendo_id');
        $arrEndrosment = $this->_bfpapplicationform->getApplicationDetails($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->bff_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bff_document'] = json_encode($arrJson);
                    $this->_bfpapplicationform->updateApplication($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }


    public function generateDocumentListInspection($arrJson,$bbendo_id){
        $html = "";
        
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td><a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                   <td>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' rid='".$val['filename']."' bbendo_id='".$bbendo_id."'></a>
                        </div>
                    </td>
                </tr>";
            }
        }
        return $html;
    }
    public function addRequirment($request,$lastId){
         $req_id = $request->input('req_id');
         $image = $request->file('bfr_document_file');
    if (!empty($req_id) && !empty($image)) {
        $arr = array();
        $i = 0;

        foreach ($req_id as $key => $value) {
            if (isset($req_id[$key])) {
                $arr[$i]['bff_id'] = $lastId;
                $arr[$i]['client_id'] = $request->input('client_id');
                $arr[$i]['bend_id'] = $request->input('bend_id');
                $arr[$i]['req_id'] = $req_id[$key];
                $arr[$i]['busn_id'] = $request->input('busn_id');
                if ($request->input('bff_req_new_business')) {
                    $arr[$i]['category_type'] = $request->input('bff_req_new_business');
                } else {
                    $arr[$i]['category_type'] = $request->input('bff_req_renew_business');
                }
                if (isset($request->file('bfr_document_file')[$key])) {
                    $uploadedFile = $image[$key];
                    $destinationPath = 'public/uploads/bfp_applicaton/';
                    $bfr_document_file2 = $uploadedFile->getClientOriginalExtension();
                    $bfr_document_file = "Renew_" . $lastId . $req_id[$key] . $bfr_document_file2;
                    $uploadedFile->move($destinationPath, $bfr_document_file);
                    $arr[$i]['bfr_document_file'] = $bfr_document_file;
                }
                $arr[$i]['updated_by'] = \Auth::user()->id;
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');

                $check = $this->_bfpapplicationform->checkRequirdmentRequietExit($arr[$i]);

                if (count($check) > 0) {
                    $this->_bfpapplicationform->updateRequiredmentRelationData($check[0]->id, $arr[$i]);
                } else {
                    $arr[$i]['created_by'] = \Auth::user()->id;
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_bfpapplicationform->addRequirment($arr[$i]);
                }
                $i++;
            }
        }
    }
    
 }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ba_business_account_no'=>'required|unique:bfp_application_forms,ba_business_account_no,'.$request->input('id'),
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
        if($BploApplication->generated_by == \Auth::user()->id){
            $BploApplication->delete();
            return redirect()->route('bfpapplicationform.index')->with('success', __('PSIC class successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function getBusinessNoId(Request $request){
        $id = $request->input('id');
        $arrActivity = $this->_bfpapplicationform->getaccountnumbersdata($id);
        $htmloption ="";
        foreach ($arrActivity as $key => $value) {
            
            $htmloption .='<option value="'.$value->id.'">'.$value->busn_registration_no.'</option>';
        }
        echo $htmloption;
    }
    public function getprofileClient(Request $request){
         $id= $request->input('pid');
         $data = $this->_bfpapplicationform->clientData($id);
         echo json_encode($data);
    }
    // public function getprofilesasses2(Request $request){
    //      $id= $request->input('pid');
    //      $data = $this->_bfpapplicationform->getprogiledata($id);
    //      echo json_encode($data);
    // }
  
    public function bfpgetBussinessData(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->bfpgetBussinessData($id);
        echo json_encode($data);
    }
    public function bfpgetClasificationDesc(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getClasificationDesc($id);
        echo json_encode($data);
    }
    public function getActivityDesc(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getActivityDesc($id);
        echo json_encode($data);
    }
    public function getClassificationByType(Request $request){
        $tax_type_id = $request->input('tax_type_id');
        $preclassificationid = $request->input('pre_classification_id');
        $arrClassification = $this->_bfpapplicationform->getClassifications($tax_type_id);
        $htmloption ="<option value=''>Please Select</option>"; $selected ="";
        foreach ($arrClassification as $key => $value) {
            if($value->id == $preclassificationid){ $selected ="selected"; }
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->bbc_classification_code.'</option>';
        }
        echo $htmloption;
    }
    public function getActivitybyClass(Request $request){
        $class_id = $request->input('class_id');
        $pre_activityid = $request->input('pre_activityid');
        $arrActivity = $this->_bfpapplicationform->getActivitybyClass($class_id);
        $htmloption ="<option value=''>Please Select</option>";  $selected ="";
        foreach ($arrActivity as $key => $value) {
            if($value->id == $pre_activityid){ $selected ="selected"; }
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->bba_code.'</option>';
        }
        echo $htmloption;
    }
     public function getAllFeeDetails(Request $request){
        $taxtypeid = $request->input('tasktypeid');
        $classificationid = $request->input('classificationid');
        $activityid = $request->input('activityid');
        $areaused = $request->input('areaused');
        $noofworker = $request->input('noofworker');
        $capitaliztion = $request->input('capitaliztion');
        $arrPermits = $this->_bfpapplicationform->getPermitfees($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion);
        foreach($arrPermits as &$val){
            $val->description=$val->id.' - '.$val->description.' - '.$val->bpt_permit_fee_amount;
        }
        $arrPermits1 = $this->_bfpapplicationform->getPermitfees2($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion);
        foreach($arrPermits1 as &$val){
            $val->description=$val->id.' - '.$val->description.' - '.$val->bpt_permit_fee_amount;
        }

        $arrGarbage = $this->_bfpapplicationform->getGarbageDrodown($taxtypeid,$classificationid,$activityid,$areaused);
        foreach($arrGarbage as &$val){
            $val->description=$val->id.' - '.$val->description;
        }
        $arrSanitary = $this->_bfpapplicationform->getSanitaryDrodown($taxtypeid,$classificationid,$activityid,$areaused);
        foreach($arrSanitary as &$val){
            $val->description=$val->id.' - '.$val->description.' amount - '.$val->amount;
        }
        if((count($arrPermits) > 0 ) && (count($arrPermits1) > 0)){
            if($arrPermits[0]->bpt_permit_fee_amount > $arrPermits1[0]->bpt_permit_fee_amount){
                $arrPermits = $arrPermits;
            } else{ $arrPermits = $arrPermits1;}
        }
        //echo $arrPermits[0]->bpt_permit_fee_amount; echo $arrPermits1[0]->bpt_permit_fee_amount; exit;
        $arrJson = array();
        $arrJson['ESTATUS']=1;
        if(count($arrPermits)>0 || count($arrGarbage)>0 || count($arrSanitary)>0){
            $arrJson['ESTATUS']=0;
            $arrJson['arrPermits']=$arrPermits;
            $arrJson['arrGarbage']=$arrGarbage;
            $arrJson['arrSanitary']=$arrSanitary;
         }
        echo json_encode($arrJson);
    }

    public function bfpgetEngneeringFeeDetails(){
        $arrEngnneringfee = $this->_bfpapplicationform->getEngneeringFee();
         foreach($arrEngnneringfee as &$val){
            $val->description=$val->id.' - '.$val->description.' amount - '.$val->amount;
            $val->amount = number_format($val->amount,2);
        }
        $arrJson = array();
        $arrJson['ESTATUS']=1;
        if(count($arrEngnneringfee)>0 ){
            $arrJson['ESTATUS']=0;
            $arrJson['arrEngnneringfee']=$arrEngnneringfee;
         }
        echo json_encode($arrJson);
       
    }

     public function BfpAssessmentPrint(Request $request){
            $id= $request->input('id');
            $data = $this->_bfpapplicationform->getUserBusinessDetails($id);
            if(count($data)>0){
                $data = $data[0];
            }

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $p_complete_name_v1="";
            $p_complete_name_v1=$data->p_complete_name_v1;
            $html = file_get_contents(resource_path('views/layouts/templates/bfpassessment.html'));
            $logo = url('/assets/images/logo.png');
            $unchecked = url('/assets/images/unchecked-checkbox.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{UNCHKED}}',$unchecked, $html);
            $html = str_replace('{{NAME}}',$p_complete_name_v1, $html);
            $location = $data->ba_address_house_lot_no.$data->ba_address_street_name;
            $html = str_replace('{{LOCATION}}',$location, $html);
            $fullname = $data->p_first_name.$data->p_middle_name.$data->p_family_name;
            $html = str_replace('{{OWNERNAME}}',$fullname, $html);
            $html = str_replace('{{TOTALAMT}}',$data->totaltax_due, $html);
            $totalfirefees = $data->totaltax_due * 0.15;
            if($totalfirefees < 500){ $totalfirefees ='500'; }
            $html = str_replace('{{FIREFEES}}',$totalfirefees, $html);
            $mpdf->WriteHTML($html);
            $applicantname = "bfpassessment.pdf";
            $folder =  public_path().'/uploads/bfpassessment/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0777, true, true);
            }
            $filename = public_path() . "/uploads/bfpassessment/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/bfpassessment/' . $applicantname);
    }
    public function BfpChequePrint(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpapplicationform->getChequeDetails($id);
        if(count($data)>0){
            $data = $data[0];

        }
        $bff_application_no="";

        $bff_date="";
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [205, 330.2]]);
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        $mpdf->SetDisplayMode(90);
        $mpdf->AddPage('p','','','','',12,10,30,2,10,10);
        $filename="";
        $html = file_get_contents(resource_path('views/layouts/templates/bureaucheque.html'));
        $logo = url('/assets/images/logo.png');
        $fsicwatermark = url('/assets/images/applicationPdf/fsicwatermark.png');
        $FSIC_Logo = url('/assets/images/applicationPdf/FSIC_Logo.png');
        $telephoneicon = url('/assets/images/applicationPdf/telephoneicon.png');
        $browsericon = url('/assets/images/applicationPdf/browsericon.png');
        $telephoneicon = url('/assets/images/applicationPdf/telephoneicon.png');
        $check = url('/assets/images/checked-checkbox.jpeg');
        $uncheck = url('/assets/images/checkbox-unchecked.jpg');
        if($data->suffix){
          $owner = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name.', '.$data->suffix;
        }else{
            $owner = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
        }
        if($data->ctsuffix){
            $representative = $data->ctrpo_first_name.' '.$data->ctrpo_middle_name.' '.$data->ctrpo_custom_last_name.', '.$data->ctsuffix;
        }else{
             $representative = $data->ctrpo_first_name.' '.$data->ctrpo_middle_name.' '.$data->ctrpo_custom_last_name;
        }
        if($data->hrsuffix){
            $bff_verified_by =$data->title.' '.$data->firstname.' '.$data->middlename.' '.$data->lastname.', '.$data->hrsuffix;
        }else{
             $bff_verified_by = $data->title.' '.$data->firstname.' '.$data->middlename.' '.$data->lastname;
        }
        if($data->suff){
            $bff_certified_by =$data->crtitle.' '.$data->first.' '.$data->middle.' '.$data->last.', '.$data->suff;
        }else{
             $bff_certified_by = $data->crtitle.' '.$data->first.' '.$data->middle.' '.$data->last;
        }
        $address=$data->brgy_name.', '.$data->mun_desc.', '.$data->prov_desc.', '.$data->reg_region;
        $bff_req_new_business=$data->bff_req_new_business;
        $bff_req_renew_business=$data->bff_req_renew_business;
        $busn_name=$data->busn_name;
        $bot_occupancy_type=$data->bot_occupancy_type;
        $busn_bldg_total_floor_area=$data->busn_bldg_total_floor_area;
        $p_telephone_no=$data->p_telephone_no;
        $p_email_address=$data->p_email_address;
        $bff_no_of_storey=$data->bff_no_of_storey;
        $bff_veridifed_date2=$data->bff_veridifed_date;
        $bff_veridifed_date = Carbon::createFromFormat('Y-m-d H:i:s', $bff_veridifed_date2)->format('Y-m-d h:i a');
        
        $bff_certified_date=$data->bff_certified_date;
        $description=$data->description;
        $bff_cro_date=$data->bff_cro_date;
        $bff_cro_in=$data->bff_cro_in;
        $bff_cro_out=$data->bff_cro_out;
        $bff_fca_date=$data->bff_fca_date;
        $bff_fca_in=$data->bff_fca_in;
        $bff_fca_out=$data->bff_fca_out;
        $bff_fcca_date=$data->bff_fcca_date;
        $bff_fcca_in=$data->bff_fcca_in;
        $bff_fcca_out=$data->bff_fcca_out;
        $bff_cfses1_date=$data->bff_cfses1_date;
        $bff_cfses1_in=$data->bff_cfses1_in;
        $bff_cfses1_out=$data->bff_cfses1_out;
        $bff_fsi_date=$data->bff_fsi_date;
        $bff_fsi_in=$data->bff_fsi_in;
        $bff_fsi_out=$data->bff_fsi_out;
        $bff_cfses2_date=$data->bff_cfses2_date;
        $bff_cfses2_in=$data->bff_cfses2_in;
        $bff_cfses2_out=$data->bff_cfses2_out;
        $bff_cfm_mfm_date=$data->bff_cfm_mfm_date;
        $bff_cfm_mfm_in=$data->bff_cfm_mfm_in;
        $bff_cfm_mfm_out=$data->bff_cfm_mfm_out;
        $bff_cfm_mfm_out=$data->bff_cfm_mfm_out;
        $bff_certified_position=$data->bff_certified_position;
        $categoryNew = 1;
        $categoryReNew = 2;
        $bend_id=$data->bend_id;
        $busn_id=$data->busn_id;
        $year=$data->bff_year;
        $resultNew = $this->_bfpapplicationform->getCategoryRequirment($busn_id,$bend_id,$categoryNew,$year);
        $dataCategoryRequirmentnew='';
        
           foreach($resultNew as $row)
                {
                    if($row->req_id){
                         $dataCategoryRequirmentnew .=
                           
                          '<li style="text-indent:-14px; margin-left:0px;"> [ &#10003;] '. $row->req_description . '</li>';
                        }

                        else{
                             $dataCategoryRequirmentnew .=
                           
                          '<li style="text-indent:-14px; margin-left:0px;"> [ ] '. $row->req_description . '</li>';
                        }
                        }
                  
          
        $resultReNew = $this->_bfpapplicationform->getCategoryRequirment($busn_id,$bend_id,$categoryReNew,$year);
        $dataCategoryRequirmentRenew='';
        
           foreach($resultReNew as $row)
                {
                    if($row->req_id){
                         $dataCategoryRequirmentRenew .=
                          '<li style="text-indent:-14px; margin-left:0px;"> [ &#10003;] '. $row->req_description . '</li>';
                    }

                    else{
                             $dataCategoryRequirmentRenew .=
                           
                           '<li style="text-indent:-14px; margin-left:0px;"> [ ] '. $row->req_description . '</li>';
                    }
                }
                
         $html = str_replace('{{LOGO}}',$logo, $html);
        // $html = str_replace('{{SIGNETURE}}',$signeture, $html);
        $html = str_replace('{{bff_certified_position}}',$bff_certified_position, $html);
        $html = str_replace('{{fsicwatermark}}',$fsicwatermark, $html);
        $html = str_replace('{{FSIC_Logo}}',$FSIC_Logo, $html);
        $html = str_replace('{{telephoneicon}}',$telephoneicon, $html);
        $html = str_replace('{{browsericon}}',$browsericon, $html);
        $html = str_replace('{{check}}',$check, $html);
        $html = str_replace('{{uncheck}}',$uncheck, $html);
        $html = str_replace('{{address}}',$address, $html);
        $bff_application_no = $data->bff_application_no;
        if($bff_req_new_business == 1){
           $bff_req_new_businessdata ='<img src="' . url('/assets/images/checked-checkbox.jpeg') . '" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">';
        }else{
           $bff_req_new_businessdata ='<img src="' . url('/assets/images/checkbox-unchecked.jpg') . '" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">';
        }
        if($bff_req_renew_business == 2){
           $bff_req_renew_businessdata ='<img src="' . url('/assets/images/checked-checkbox.jpeg') . '" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">';
        }else{
           $bff_req_renew_businessdata ='<img src="' . url('/assets/images/checkbox-unchecked.jpg') . '" style="height:15px; width:15px; padding-right:5px; vertical-align: middle;">';
        }
        $html = str_replace('{{bff_req_new_businessdata}}',$bff_req_new_businessdata, $html);
        $html = str_replace('{{bff_req_renew_businessdata}}',$bff_req_renew_businessdata, $html);  
            
        $digits = str_split($bff_application_no); // Split the number into an array of digits

        $numberApplication = ''; // Initialize an empty string to store the HTML

        foreach ($digits as $digit) {
            $numberApplication .= '<td style="width:20px; height: 28px;">' . $digit . '</td>';
        }
        // dd($numberApplication);exit;
        $bff_date2 = $data->bff_date;
        $bff_date= date('M j,Y', strtotime($bff_date2));
        $html = str_replace('{{application_no}}',$numberApplication, $html);
        $html = str_replace('{{application_date}}',$bff_date, $html);
        $html = str_replace('{{busn_name}}',$busn_name, $html);
        $html = str_replace('{{dataCategoryRequirmentnew}}',$dataCategoryRequirmentnew, $html);
        $html = str_replace('{{dataCategoryRequirmentRenew}}',$dataCategoryRequirmentRenew, $html);
        $html = str_replace('{{owner}}',$owner, $html);
        $html = str_replace('{{representative}}',$representative, $html);
        $html = str_replace('{{bot_occupancy_type}}',$bot_occupancy_type, $html);
        $html = str_replace('{{busn_bldg_total_floor_area}}',$busn_bldg_total_floor_area, $html);
        $html = str_replace('{{p_telephone_no}}',$p_telephone_no, $html);
        $html = str_replace('{{p_email_address}}',$p_email_address, $html);
        $html = str_replace('{{bff_no_of_storey}}',$bff_no_of_storey, $html);
        $html = str_replace('{{bff_verified_by}}',$bff_verified_by, $html);
        $html = str_replace('{{bff_veridifed_date}}',$bff_veridifed_date, $html);
        $html = str_replace('{{bff_certified_by}}',$bff_certified_by, $html);
        $html = str_replace('{{bff_certified_date}}',$bff_certified_date, $html);
        $html = str_replace('{{description}}',$description, $html);
        $html = str_replace('{{bff_cro_date}}',$bff_cro_date, $html);
        $html = str_replace('{{bff_cro_in}}',$bff_cro_in, $html);
        $html = str_replace('{{bff_cro_out}}',$bff_cro_out, $html);
        $html = str_replace('{{bff_fca_date}}',$bff_fca_date, $html);
        $html = str_replace('{{bff_fca_in}}',$bff_fca_in, $html);
        $html = str_replace('{{bff_fca_out}}',$bff_fca_out, $html);
        $html = str_replace('{{bff_fcca_date}}',$bff_fcca_date, $html);
        $html = str_replace('{{bff_fcca_in}}',$bff_fcca_in, $html);
        $html = str_replace('{{bff_fcca_out}}',$bff_fcca_out, $html);
        $html = str_replace('{{bff_cfses1_date}}',$bff_cfses1_date, $html);
        $html = str_replace('{{bff_cfses1_in}}',$bff_cfses1_in, $html);
        $html = str_replace('{{bff_cfses1_out}}',$bff_cfses1_out, $html);
        $html = str_replace('{{bff_fsi_date}}',$bff_fsi_date, $html);
        $html = str_replace('{{bff_fsi_in}}',$bff_fsi_in, $html);
        $html = str_replace('{{bff_fsi_out}}',$bff_fsi_out, $html);
        $html = str_replace('{{bff_cfses2_date}}',$bff_cfses2_date, $html);
        $html = str_replace('{{bff_cfses2_in}}',$bff_cfses2_in, $html);
        $html = str_replace('{{bff_cfses2_out}}',$bff_cfses2_out, $html);
        $html = str_replace('{{bff_cfm_mfm_date}}',$bff_cfm_mfm_date, $html);
        $html = str_replace('{{bff_cfm_mfm_in}}',$bff_cfm_mfm_in, $html);
        $html = str_replace('{{bff_cfm_mfm_out}}',$bff_cfm_mfm_out, $html);
        $mpdf->WriteHTML($html);
        $filename ='application-'.$id.'.pdf';


        $arrSign= $this->_commonmodel->isSignApply('fire_protection_endorsement_application_form_verified_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('fire_protection_endorsement_application_form_certified_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            $mpdf->Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->verified_user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->certified_user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
        
        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
                
            }
        }

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");

    }
    
}
