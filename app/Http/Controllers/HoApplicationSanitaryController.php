<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\HoApplicationSanitary;
use App\Models\Barangay;
use App\Models\HoAppHealthCert;
use App\Models\HrEmployee;
use App\Models\BploBusiness;
use App\Models\BfpApplicationForm;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class HoApplicationSanitaryController extends Controller
{   
     public $data = [];
    public $postdata = [];
    public $yeararr = array(""=>"Select Year");
    public $arrYears = array(""=>"Select Year");
    public $arrbfpapplication = array(""=>"Select App");
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $busn_name = array(""=>"Please Select");
    public $employee = array(""=>"Please Select");
    private $slugs;
    public $busn_end_status = ['0'=>"Not Started",'1'=>"In Progress",'2'=>"Completed",'3'=>"Decline"];
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
     public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
		$this->_hoappsanitary = new HoApplicationSanitary();
        $this->_hoapphealthcert = new HoAppHealthCert();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->_barangay = new Barangay(); 
        $this->_hrEmployee = new HrEmployee(); 
        $this->_BploBusiness = new BploBusiness(); 
        $this->slugs = 'healthy-and-safety/app-sanitary';
        $this->_bfpapplicationform = new BfpApplicationForm();
        $this->sanitaryData = array('id'=>'','bend_id'=>'','busn_id'=>'','has_app_year'=>'','has_app_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>'','has_expired_date'=>'','has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
        $this->reldata = array('id'=>'','req_id'=>'','req_id_abbreviation'=>'','hasr_is_complete'=>'','hasr_completed_date'=>'','hasr_remarks'=>'');
        
        $arrYrs = $this->_hoappsanitary->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->has_app_year] =$val->has_app_year;
        }
        
        foreach($this->_hoappsanitary->getbploApplications() as $val) {
            $this->arrbfpapplication[$val->id]=$val->ba_business_account_no;
        } 
        foreach ($this->_hoappsanitary->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_hoappsanitary->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
        foreach ($this->_hoapphealthcert->getBusiness() as $val) {
            $this->busn_name[$val->id]=$val->busn_name."-[".$val->bend_year."-".$this->busn_end_status[$val->bend_status]."]";
        }
       
    }
    public function index(Request $request)
    {   
            $this->is_permitted($this->slugs, 'read');
            $arrYears = $this->arrYears;
            return view('hoappsanitary.index',compact('arrYears'));
    }
    
    public function getList(Request $request){
        $data=$this->_hoappsanitary->getList($request);
        $hr_emp=$this->_hrEmployee->empIdByUserId(\Auth::user()->creatorId());

        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
			
            $busn_address=(!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
            $addressnew = wordwrap($busn_address, 40, "<br />\n");
			
		
            $arr[$i]['srno']=$j;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['busn_address']="<div class='showLess'>".str_replace(',', ', ', $addressnew)."</div>";
            $arr[$i]['tax_payer_name']=$row->full_name;
            $arr[$i]['has_app_year']=$row->has_app_year;
            $arr[$i]['has_app_no']=$row->has_transaction_no;
            $arr[$i]['has_type_of_establishment']=$row->has_type_of_establishment;
            $arr[$i]['has_issuance_date']=date("M d, Y",strtotime($row->has_issuance_date));
            $arr[$i]['has_expired_date']=date("M d, Y",strtotime($row->has_expired_date));
            $arr[$i]['has_permit_no']=$row->has_permit_no;
            $rem_btn =($row->has_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm remove ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center restore ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $print_btn = ($row->has_approver_status == 1 && $row->has_recommending_approver_status == 1) ? '<div class="action-btn bg-info ms-2">
            
                     <a title="Print App Sanitary"  data-title="Print App Sanitary" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/healthy-and-safety/app-sanitary/healthsanitaryprint?id='.(int)$row->id).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
            </div>' : "";
            $arr[$i]['has_status']=($row->has_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['has_remarks']=$row->has_remarks;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/healthy-and-safety/app-sanitary/store?id='.$row->id).'&end_id='.$row->end_id.'&busn_id='.$row->busn_id.'"  data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit Sanitary Permit"  data-title="Edit Sanitary Permit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>'.$print_btn.$rem_btn;
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

    public function store(Request $request){
        $user_id= \Auth::user()->id;
        $auth=$this->_hrEmployee->empIdByUserId($user_id);
        $busn_id=$request->input('busn_id');
        $end_id =  $request->input('end_id');
        $row = $this->_hoapphealthcert->getBusnComAddress($end_id,$busn_id);
        $busn_plan=$this->_BploBusiness->reload_busn_plan($busn_id);
        $bplo_business=$this->_hoapphealthcert->getBusn($busn_id);
        $busn_name=$bplo_business->busn_name;
        $year=date('Y');
        $Sanitarytotal_employees ="0";
        $total_employeeHealthCard="";
        $arrDocumentDetailsHtml='';
        $has_issuance_date=Carbon::now()->format('Y-m-d');
        $has_expired_date=Carbon::now()->endOfYear()->toDateString();
        $sanitary_data=$this->_hoappsanitary->getDataByBusnId($busn_id);
        if($sanitary_data != NUll)
        {
            $sanitary_id=$sanitary_data->id;
        }
        else{
            $sanitary_id=$request->input('id');
        }
        foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
            $this->employee[$val->id]=$val->fullname;
        }
        foreach ($this->_bfpapplicationform->getEmployeeUser($user_id) as $val) {
            $this->employee[$val->id]=$val->fullname;
        }
        
        $employee= $this->employee;
        if(empty($sanitary_id) && $request->input('submit')=="")
        {
            $hr_emp=$this->_hrEmployee->empIdByUserId(\Auth::user()->id);
            $has_recommending_approver_position=$this->_hoapphealthcert->getPosition($hr_emp->id);
            $latest = HoApplicationSanitary::orderBy('id','DESC')->first();
            $has_approver=NULL;
            $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
            $owner=$row->full_name;
            // $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
            $complete_address= (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '');
            $data = array('id'=>'','bend_id'=>$end_id,'busn_id'=>$busn_id,'has_app_year'=>$year,'has_app_no'=>'','has_transaction_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>$has_issuance_date,'has_expired_date'=>$has_expired_date,'has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
        }else{
            $complete_address = "";
            $owner= "";
            $data = array('id'=>'','bend_id'=>$end_id,'busn_id'=>$busn_id,'has_app_year'=>$year,'has_app_no'=>'','has_transaction_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>$has_issuance_date,'has_expired_date'=>$has_expired_date,'has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
        }
        $data = (object)$data;
        $reldata = array();
        $bfpapplications =$this->arrbfpapplication;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;
        
       
        $countries = array('Select Country'); $requirements = array(); $healthcertreq = array();
        
        foreach ($this->_hoappsanitary->getRequirements() as $val) {
            $requirements[$val->id]=$val->req_description;
        } 
        foreach ($this->_hoappsanitary->getCountries() as $val) {
            $countries[$val->id]=$val->country_name;
        } 
		
        if($sanitary_id>0 && $request->input('submit')==""){
            $data = HoApplicationSanitary::find($sanitary_id);
            $total_employee ="";
            $total_employee = $this->_hoappsanitary->total_employees($busn_id,$data->has_app_year);
            if ($total_employee !== null) {
               $Sanitarytotal_employees = $total_employee->busn_employee_no_male + $total_employee->busn_employee_no_female;
            }
            $total_employeeHealthCard = $this->_hoappsanitary->totalHealthCard($busn_id,$data->has_app_year);
            // echo $total_employeeHealthCard;exit;
            if(isset($data)){
                    if($data->has_recommending_approver == $auth->id || $data->has_approver == $auth->id)
                    {
                        $user_id=0;
                        foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                            $this->employee[$val->id]=$val->fullname;
                        }
                        
                    }
                $reldata = $this->_hoappsanitary->getappSanitaryReqData($sanitary_id);
                $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
                $owner=$row->full_name;
                // $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
                $complete_address= (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
            }
            $arrdocDtls = $this->genSanitaryDocList($sanitary_id);
            if(isset($arrdocDtls)){
                $arrDocumentDetailsHtml = $arrdocDtls;
            }
        }
		if(!empty($data->has_document)){
		$arrdocDtls = $this->generateDocumentList($data->has_document,$data->id);
			if(isset($arrdocDtls)){
				$data->arrDocumentDetailsHtml = $arrdocDtls;
			}
		}
		
        if($request->input('submit')!=""){
            foreach((array)$this->sanitaryData as $key=>$val){
                $this->sanitaryData[$key] = $request->input($key);
            }
            $this->sanitaryData['busn_id'] = $busn_id;
            $currentYear = $this->sanitaryData['has_app_year'];
            $sanitary_data = HoApplicationSanitary::where('id',$sanitary_id)->first();
            $apv_send=1;
            if($sanitary_id>0){
                $ext_data=$this->_hoappsanitary->findDataById($sanitary_id);
                $this->sanitaryData['updated_by']=\Auth::user()->id;
                $this->sanitaryData['updated_at'] = date('Y-m-d H:i:s');
                $this->sanitaryData['has_status'] = $sanitary_data->has_status;
                if($this->sanitaryData['has_recommending_approver'] == NULL)
                {
                    $this->sanitaryData['has_recommending_approver']=$sanitary_data->has_recommending_approver; 
                }
                if($this->sanitaryData['has_approver'] == NULL)
                {
                    $this->sanitaryData['has_approver']=$sanitary_data->has_approver; 
                }        
                if($auth->id == $sanitary_data->has_recommending_approver || $auth->id == $sanitary_data->has_approver){
                   
                    if($request->has('has_approver_status'))
                    {
                            if($sanitary_data->has_permit_no == NULL)
                            {
                                $newValue = sprintf('%06d', $sanitary_data->has_app_no);
                                $this->sanitaryData['has_permit_no']=$newValue;
                                $this->sanitaryData['has_approved_date']=date('Y-m-d H:i:s');
                            }      
                            $this->sanitaryData['has_approver_status']=1;
                    }
                    else{
                        $this->sanitaryData['has_approver_status']=$sanitary_data->has_approver_status;
                    }
                    if($request->has('has_recommending_approver_status'))
                    {
                        $this->sanitaryData['has_recommending_approver_status']=1; 
                    }else{
                        $this->sanitaryData['has_recommending_approver_status']=$sanitary_data->has_recommending_approver_status; 
                    }
                }else{
                    $this->sanitaryData['has_recommending_approver_status']=$sanitary_data->has_recommending_approver_status;
                    $this->sanitaryData['has_approver_status']=$sanitary_data->has_approver_status;
                }
                $this->_hoappsanitary->updateData($sanitary_id,$this->sanitaryData);
                $success_msg = 'Sanitary Permit updated successfully.';
                $lastinsertid = $sanitary_id;
                if($ext_data->has_approver_status == 1){
                    $apv_send=0;
                }
            }else{
                $lastRecord = HoApplicationSanitary::where('has_app_year',$currentYear)->orderBy('id','DESC')->first();
                if (empty($lastRecord)) {
                    $lastNumber = 0;
                }else{
                    $lastNumber =$lastRecord->has_app_no;
                }
                $newNumber = $lastNumber + 1;
                $hahc_app_no = $newNumber;
                $newValue = sprintf('%04d-%06d', $currentYear, $newNumber);
                $this->sanitaryData['has_app_no']=$hahc_app_no;
                $this->sanitaryData['has_transaction_no']=$newValue;
                $this->sanitaryData['has_status']=1;
                $this->sanitaryData['created_by']=\Auth::user()->id;
                $this->sanitaryData['created_at'] = date('Y-m-d H:i:s');
                $this->is_permitted($this->slugs, 'create');
                $lastinsertid = $this->_hoappsanitary->addData($this->sanitaryData);
                $success_msg = 'Sanitary Permit added successfully.';
            }
            if($this->sanitaryData['has_approver_status'] == 1 && $apv_send == 1){
                $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                $smsTemplate=SmsTemplate::where('id',19)->where('is_active',1)->first();
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
            // return redirect()->route('hoappsanitary.index')->with('success', __($success_msg));
            return redirect()->to('healthy-and-safety/app-sanitary')->with('success', __($success_msg));
        }
        return view('hoappsanitary.create',compact('data','busn_id','busn_plan','auth','end_id','busn_name','owner','employee','complete_address','countries','requirements','healthcertreq','reldata','arrDocumentDetailsHtml','Sanitarytotal_employees','total_employeeHealthCard'));
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
    public function genSanitaryDocList($sanitary_id){
        $html = "";
        if(isset($sanitary_id)){
            $arr = $this->_hoappsanitary->getappSanitaryReqData($sanitary_id);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val->req_description."</td>
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn'  href='".asset('uploads/sanitaryReqDoc').'/'.$val->hasr_document."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteSanitaryReq ti-trash text-white text-white' sid='".$val->id."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function removeSanitary(Request $request,$id)
    {
        $HoApplicationSanitary = HoApplicationSanitary::where('id',$id)->first();
        if($HoApplicationSanitary->has_status ==1)
        {
            $status=0;
        }
        else{
            $status=1;
        }
        $HoApplicationSanitary->has_status=$status;
        $HoApplicationSanitary->save();
        return response()->json($HoApplicationSanitary);
    }
    

    public function getEstablisSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Perform the logic to fetch suggestions based on $query
        // Example:
        $suggestions = HoApplicationSanitary::where('has_type_of_establishment', 'LIKE', '%' . $query . '%')->distinct('has_type_of_establishment')->pluck('has_type_of_establishment');

        return response()->json($suggestions);
    }
    public function deleteSanitaryReq(Request $request,$id){
        $this->_hoappsanitary->deleteSanitaryReq($id);
        
    }
    
    public function getPosition(Request $request){
    	$id= $request->input('id');
        $data = $this->_hoapphealthcert->getPosition($id);
        $details=[
            'position' => $data
            ];
        echo json_encode($details);
    }
    
    
    public function getBusnComAddress(Request $request){
    	$end_id= $request->input('id');
        $bplo_ends=bplo_business_endorsement::where('id',$end_id)->first();
        $row = $this->_hoapphealthcert->getBusnComAddress($end_id,$bplo_ends->busn_id);
        $brgy_det=$this->_barangay->findDetails($row->busn_office_main_barangay_id);
        $owner=$row->full_name;
        // $complete_address=(!empty($row->busn_office_main_building_no) ? $row->busn_office_main_building_no . ',' : '') . (!empty($row->busn_office_main_building_name) ? $row->busn_office_main_building_name . ',' : '') . (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
        $complete_address= (!empty($row->busn_office_main_add_block_no) ? $row->busn_office_main_add_block_no . ',' : ''). (!empty($row->busn_office_main_add_lot_no) ? $row->busn_office_main_add_lot_no . ',' : ''). (!empty($row->busn_office_main_add_street_name) ? $row->busn_office_main_add_street_name . ',' : ''). (!empty($row->busn_office_main_add_subdivision) ? $row->busn_office_main_add_subdivision . ',' : '') . (!empty($brgy_det) ? $brgy_det : '');
        $details=[
                    'complete_address' => $complete_address,
                    'owner' => $owner
        ];
        echo json_encode($details);
    }

     public function formValidation(Request $request){
            $validator = \Validator::make(
                $request->all(), [
                    'bend_id'=>'required',
                    'has_app_year'=>'required',
                    'has_type_of_establishment'=>'required', 
                    'has_issuance_date'=>'required', 
                    'has_expired_date'=>'required',
                    'has_recommending_approver'=>'required',
                    'has_recommending_approver_position'=>'required',
                    'has_approver'=>'required',
                    'has_approver_position'=>'required',
                    'end_requirement_id'=>'required',
                ],[
                    'bend_id.required' => 'Business name is Required',
                    'has_app_year.required' => 'Year is Required',
                    'has_type_of_establishment.required' => 'Establishment is Required',
                    'has_issuance_date.required' => 'Issuance Date is Required',
                    'has_expired_date.required' => 'Expired Date is Required',
                    'has_recommending_approver.required' => 'Recommending Approver is Required',
                    'has_recommending_approver_position.required' => 'Recommending Approver Position is Required',
                    'has_approver.required' => 'Approver Name is Required',
                    'has_approver_position.required' => 'Approver Position is Required',
                    'end_requirement_id.required' => 'Requirement is Required',
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
     public function getpbloAppdetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_hoappsanitary->getBploApplictaions($id);
        echo json_encode($data);
    }


    public function hoapphealthsanitaryprint(Request $request){
		
            $id = $request->input('id');
			
            $data = $this->_hoappsanitary->getAppSanitary($id);
            $owner=$data->full_name;
            //echo "<pre>"; print_r($data); exit;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/SanitaryPermit-CHO2.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{OWNERNAME}}',$owner, $html);
            $html = str_replace('{{BUSSINESS}}',$data->busn_name, $html);
            $html = str_replace('{{ESTABLISHMENT}}',$data->has_type_of_establishment, $html);
            $html = str_replace('{{PERMITNO}}',$data->has_permit_no, $html);
            $brgy_det=$this->_barangay->findDetails($data->busn_office_main_barangay_id);
            $address=(!empty($data->busn_office_main_add_block_no) ? $data->busn_office_main_add_block_no . ', ' : ''). (!empty($data->busn_office_main_add_lot_no) ? $data->busn_office_main_add_lot_no . ', ' : ''). (!empty($data->busn_office_main_add_street_name) ? $data->busn_office_main_add_street_name . ', ' : ''). (!empty($data->busn_office_main_add_subdivision) ? $data->busn_office_main_add_subdivision . ', ' : '') . (!empty($brgy_det) ? $brgy_det : '');
            $html = str_replace('{{ADDRESS}}',str_replace(',', ', ', $address), $html);
            $issueddate = date(" F d, Y",strtotime($data->has_issuance_date));
            $expireddate = date(" F d, Y",strtotime($data->has_expired_date));
            $html = str_replace('{{DATEISSUED}}',$issueddate, $html);
            $html = str_replace('{{DATEEXPIRED}}',$expireddate, $html);
            $html = str_replace('{{SIGN1}}',$sign, $html);
            $html = str_replace('{{SIGN2}}',$sign, $html);
            $html = str_replace('{{BGIMAGE}}',$bgimage, $html);

            $html = str_replace('{{r_apv_name}}',$data->r_apv_name, $html);
            $html = str_replace('{{apv_name}}',$data->apv_name, $html);
            $html = str_replace('{{has_recommending_approver_position}}',$data->has_recommending_approver_position, $html);
            $html = str_replace('{{has_approver_position}}',$data->has_approver_position, $html);
            $mpdf->WriteHTML($html);
        
        $filename = $data->id."-Sanitary Permit to operate.pdf";
        $arrSign= $this->_commonmodel->isSignApply('health_safety_endorsement_bplo_sanitary_permit_recommend_approval');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_endorsement_bplo_sanitary_permit_approved_by');
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
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->r_apv_user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

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

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->apv_user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

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
    

    public function hoapphealthsanitaryprint1(Request $request,$id){
    

        //$mpdf = new \Mpdf\Mpdf(['format' => 'Legal']);
        
        
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $mpdf->AddPage('L');
        // $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp']);
        // $mpdf->shrink_tables_to_fit = 0;
        
        //$mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
        // $mpdf-> autoPageBreak = false; 
        
        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $mpdf = new \Mpdf\Mpdf(['fontdata' => $fontData + [ // lowercase letters only in font key
                'frutiger' => [
                    'R' => 'Frutiger-Normal.ttf',
                    'I' => 'FrutigerObl-Normal.ttf',
                ]
            ]]);
        
        //$mpdf->AddPage('P','','','','',10,10,4,4,10,10);
        
        $html = file_get_contents(resource_path('views/layouts/templates/SanitaryPermit-CHO.html'));
        // buildingpermit1.html Vari
        // $html = str_replace('{{MUNCIPALITY}}','1234567', $html);
        // $html = str_replace('{{appno}}','1234567', $html);
        // $html = str_replace('{{permitno}}','1234567', $html);
        // $html = str_replace('{{dateofapp}}','1234567', $html);
        // $html = str_replace('{{dateissued}}','1234567', $html);
        // $html = str_replace('{{acctno}}','1234567', $html);
        // $html = str_replace('{{formogowner}}','1234567', $html);
        // $html = str_replace('{{ecoactivity}}','1234567', $html);
        // $html = str_replace('{{location}}','1234567', $html);
        // $html = str_replace('{{noofunits}}','1234567', $html);
        // $html = str_replace('{{bldofficialname}}','1234567', $html);
        // $html = str_replace('{{date}}','1234567', $html);
        
        
        $mpdf->WriteHTML($html);
        $mpdf->Output();
}
	
	public function uploadDocument(Request $request){
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoApplicationSanitary::find($healthCertId);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->has_document,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/sanitaryReqDoc/';
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
                    $arrJson = json_decode($arrEndrosment->has_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['has_document'] = json_encode($finalJsone);
                $this->_hoappsanitary->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['has_document'],$healthCertId);
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
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn'  href='".asset('uploads/sanitaryReqDoc').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoApplicationSanitary::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->has_document,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/sanitaryReqDoc/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['has_document'] = json_encode($arrJson);
                    $this->_hoappsanitary->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
