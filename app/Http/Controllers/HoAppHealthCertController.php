<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\HoAppHealthCert;
use App\Models\BploBusiness;
use App\Models\Barangay;
use App\Models\BploBusinessPsic;
use App\Models\HrEmployee;
use App\Models\BfpApplicationForm;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\CarbonPeriod;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
class HoAppHealthCertController extends Controller
{
    public $data = [];
    public $postdata = [];
	public $arrYears = array(""=>"Select Year");
    public $barangay = array(""=>"Select Barangay");
    public $citizen = array(""=>"Select Citizen");
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $busn_name = array(""=>"Please Select");
    public $employee = array(""=>"Please Select");
    public $busn_end_status = ['0'=>"Not Started",'1'=>"In Progress",'2'=>"Completed",'3'=>"Decline"];
    public $gend = ['0'=>"Male",'1'=>"Female"];    
    private $slugs;
    
    
    public function __construct(){
		$this->_hoapphealthcert = new HoAppHealthCert();
        $this->_BploBusinessPsic = new BploBusinessPsic(); 
        $this->_BploBusiness = new BploBusiness(); 
        $this->_commonmodel = new CommonModelmaster(); 
        $this->_hrEmployee = new HrEmployee(); 
        $this->_bfpapplicationform = new BfpApplicationForm();
        $this->slugs = 'healthy-and-safety/health-certificate';
        $this->data = array('id'=>'','brgy_id'=>'','busn_id'=>'','hahc_approver_status'=>'','employee_occupation'=>'','bend_id'=>'','hahc_app_code'=>'','hahc_app_year'=>'','hahc_app_no'=>'','hahc_transaction_no'=>'','hahc_registration_no'=>'','hahc_issuance_date'=>'','hahc_expired_date'=>'','citizen_id'=>'','hahc_place_of_work'=>'','hahc_status'=>'','hahc_remarks'=>'','created_by'=>'','hahc_recommending_approver'=>"",'hahc_recommending_approver_position'=>'','hahc_approver'=>'','hahc_approver_position'=>'');
        $arrYrs = $this->_hoapphealthcert->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->hahc_app_year] =$val->hahc_app_year;
        }
        foreach ($this->_hoapphealthcert->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_hoapphealthcert->getBarangay() as $val) {
            $this->barangay[$val->id]=$val->brgy_name.",".$val->mun_desc.",".$val->prov_desc.",".$val->reg_region;
        }
        foreach ($this->_hoapphealthcert->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
        foreach ($this->_hoapphealthcert->getCitizen() as $row) {
            $this->citizen[$row->id]=$row->cit_fullname;
        }
        foreach ($this->_hoapphealthcert->getBusiness() as $val) {
            $this->busn_name[$val->id]=$val->busn_name."-[".$val->bend_year."-".$this->busn_end_status[$val->bend_status]."]";
        }
       
    }
    public function getCitizenAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_hoapphealthcert->getCitizenAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->cit_fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    
    public function getBusinessAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_hoapphealthcert->getBusinessAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->busn_name."-[".$val->bend_year."-".$this->busn_end_status[$val->bend_status]."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    
    public function index(Request $request)
    {   
            $this->is_permitted($this->slugs, 'read');
            $arrYears = $this->arrYears;
            return view('hoapphealthcert.index',compact('arrYears'));
    }
    public function getList(Request $request){
        $data=$this->_hoapphealthcert->getList($request);
        $hr_emp=$this->_hrEmployee->empIdByUserId(\Auth::user()->id);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
			$actions = '';
			
            $now = Carbon::now();
            $age = $now->diffInYears(Carbon::parse($row->cit_date_of_birth));
            $isActivestatus =($row->hahc_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm remove ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center restore ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['srno']=$j;    
            
            $arr[$i]['citizen_name']= (!empty($row->cit_first_name) ? $row->cit_first_name . ' ' : '') . (!empty($row->cit_middle_name) ? $row->cit_middle_name . ' ' : '') . (!empty($row->cit_last_name) ? $row->cit_last_name : ''). (!empty($row->cit_suffix_name) ? ', '.$row->cit_suffix_name  : '');
            $arr[$i]['hahc_app_year']=$row->hahc_app_year;
            $arr[$i]['gend_age']=$this->gend[$row->cit_gender]."[".$age."]";

            $arr[$i]['hahc_app_no']=$row->hahc_app_no;
            $arr[$i]['hahc_transaction_no']=$row->hahc_transaction_no;
            $arr[$i]['hahc_registration_no']=$row->hahc_registration_no;
            $arr[$i]['hahc_issuance_date']=date("M d, Y",strtotime($row->hahc_issuance_date));
            $arr[$i]['hahc_expired_date']=date("M d, Y",strtotime($row->hahc_expired_date));
            $arr[$i]['applied_date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['busn_name']=$row->busn_name;
            if($row->hahc_status == 1){$status = "Yes"; } else{ $status = "No"; }
            $arr[$i]['hahc_status']=$status;
            $arr[$i]['hahc_remarks']=$row->hahc_remarks;
            $arr[$i]['is_active']=($row->hahc_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
			
				$actions .= '<div class="action-btn bg-warning ms-2">
                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/healthy-and-safety/health-certificate/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Health Certificate">
                                    <i class="ti-pencil text-white"></i>
                                </a>
                            </div>';
                if($row->hahc_approver_status == 1 && $row->hahc_recommending_approver_status == 1){
                        $actions .= '<div class="action-btn bg-info ms-2">
                                    <a title="Print Health Certificate"  data-title="Print Health Certificate" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/healthy-and-safety/health-certificate/hoapphealthcertPrint?id='.(int)$row->id).'" >
                                        <i class="ti-printer text-white"></i>
                                    </a>
                             </div>';
                    }
                    $actions .= '<div class="action-btn bg-info ms-2">
                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/healthy-and-safety/health-certificate/upload?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Upload" data-title="Health Certificate Document Management">
                                        <i class="ti-cloud-up text-white"></i>
                                    </a>
                                </div> '.$isActivestatus.'
                                </div>';
                      	    
            $arr[$i]['action']=$actions;
                
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
    public function ActiveInactive(Request $request,$id){
        $HoAppHealthCert = HoAppHealthCert::where('id',$id)->first();
        if($HoAppHealthCert->hahc_status ==1)
        {
            $status=0;
        }
        else{
            $status=1;
        }
        $HoAppHealthCert->hahc_status=$status;
        $HoAppHealthCert->save();
        return response()->json($HoAppHealthCert);
    }
    public function getCitizenDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_hoapphealthcert->getCitizenDetails($id);
        //$brgy=Barangay::findDetails($data->brgy_id);
        $now = Carbon::now();
        $age = $now->diffInYears(Carbon::parse($data->cit_date_of_birth));
        $house_no=(!empty($data->cit_house_lot_no)) ? $data->cit_house_lot_no.',' : "";
        $cit_street_name=(!empty($data->cit_street_name)) ? $data->cit_street_name.',' : "";
        $cit_subdivision=(!empty($data->cit_subdivision)) ? $data->cit_subdivision.',' : "";
        //$brgy_data=(!empty($brgy)) ? $brgy : "";
        $gender=$this->gend[$data->cit_gender];
        $complete_address= $this->_commonmodel->getTaxPayerAddress($id);
		//$house_no.$cit_street_name.$cit_subdivision.$brgy_data;
        $details=[
                    'age' =>$age,
                    'complete_address' =>$complete_address,
                    'gender' =>$gender,
                    'nationality'=>$data->nationality
        ];
        echo json_encode($details);
    }
	
    public function getPosition(Request $request){
    	$id= $request->input('id');
        $data = $this->_hoapphealthcert->getPosition($id);
        $details=[
            'position' => $data
            ];
        echo json_encode($details);
    }


     public function store(Request $request){
            $user_id= \Auth::user()->id;
            $auth=$this->_hrEmployee->empIdByUserId($user_id);
            $year=date('Y');
            $hahc_issuance_date=Carbon::now()->format('Y-m-d');
            $hahc_expired_date=Carbon::now()->endOfYear()->toDateString();
            if(empty($request->input('id')) && $request->input('submit')=="")
            {
                $data = array('id'=>'','brgy_id'=>'','busn_id'=>'','hahc_approver_status'=>'','employee_occupation'=>'','bend_id'=>'','hahc_app_code'=>'','hahc_app_year'=>$year,'hahc_app_no'=>'','hahc_transaction_no'=>'','hahc_registration_no'=>'','hahc_issuance_date'=>$hahc_issuance_date,'hahc_expired_date'=>$hahc_expired_date,'citizen_id'=>'','hahc_place_of_work'=>'','hahc_status'=>'','hahc_remarks'=>'','created_by'=>'','hahc_recommending_approver'=>'','hahc_recommending_approver_status'=>'','hahc_recommending_approver_position'=>'','hahc_approver'=>'','hahc_approver_position'=>'');

            }
            else{
                $data = array('id'=>'','brgy_id'=>'','busn_id'=>'','hahc_approver_status'=>'','employee_occupation'=>'','bend_id'=>'','hahc_app_code'=>'','hahc_app_year'=> '','hahc_app_year'=>'','hahc_app_no'=>'','hahc_transaction_no'=>'','hahc_registration_no'=>'','hahc_issuance_date'=>'','hahc_expired_date'=>'','citizen_id'=>'','hahc_place_of_work'=>'','hahc_status'=>'','hahc_remarks'=>'','created_by'=>'','hahc_recommending_approver'=>"",'hahc_recommending_approver_status'=>"",'hahc_recommending_approver_position'=>'','hahc_approver'=>'','hahc_approver_position'=>'');
            }
	        $data = (object)$data;
	        $barangay =$this->barangay;
            $citizen=  $this->citizen;
            $nationality = "";
            $age = "";
            $gender = "";
            $complete_address = "";
	        $arrTaxClasses = $this->arrTaxClasses;
	        $arrTaxTypes = $this->arrTaxTypes;
            $busn_name = $this->busn_name;
	        $countries = array('Select Country'); 
            $requirements = array(); 
            $healthcertreq = array();
            foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                $this->employee[$val->id]=$val->fullname;
            }
            foreach ($this->_bfpapplicationform->getEmployeeUser($user_id) as $val) {
                $this->employee[$val->id]=$val->fullname;
            }
            
            $employee= $this->employee;
	        
	        foreach ($this->_hoapphealthcert->getRequirements() as $val) {
	            $requirements[$val->id]=$val->ho_service_name;
	        } 
	        foreach ($this->_hoapphealthcert->getCountries() as $val) {
	            $countries[$val->id]=$val->country_name;
	        }
            $getdatausersave = $this->_hoapphealthcert->CheckFormdataExist('7',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->hahc_recommending_approver = $usersaved->hahc_recommending_approver;
                  $data->hahc_recommending_approver_position = $usersaved->hahc_recommending_approver_position;
                  $data->hahc_approver = $usersaved->hahc_approver;
                  $data->hahc_approver_position = $usersaved->hahc_approver_position;
                  $data->hahc_place_of_work = $usersaved->hahc_place_of_work;
               }  
	        if($request->input('id')>0 && $request->input('submit')==""){
	            $data = HoAppHealthCert::find($request->input('id'));
                if($data->hahc_recommending_approver == $auth->id || $data->hahc_approver == $auth->id)
                {
                    $user_id=0;
                    foreach ($this->_bfpapplicationform->getEmployee($user_id) as $val) {
                        $this->employee[$val->id]=$val->fullname;
                    }
                    $employee= $this->employee;
                }
	            $healthcertreq = $this->_hoapphealthcert->getHealthcertiReqData($request->input('id'));
                $citizenDetails = $this->_hoapphealthcert->getCitizenDetails($data->citizen_id);
				//$brgy=Barangay::findDetails($citizenDetails->brgy_id);
                $now = Carbon::now();
                $age = $now->diffInYears(Carbon::parse($citizenDetails->cit_date_of_birth));
                $house_no=(!empty($citizenDetails->cit_house_lot_no)) ? $citizenDetails->cit_house_lot_no.',' : "";
                $cit_street_name=(!empty($citizenDetails->cit_street_name)) ? $citizenDetails->cit_street_name.',' : "";
                $cit_subdivision=(!empty($citizenDetails->cit_subdivision)) ? $citizenDetails->cit_subdivision.',' : "";
                //$brgy_data=(!empty($brgy)) ? $brgy : "";
                $gender= $this->gend[$citizenDetails->cit_gender];
                $nationality= $citizenDetails->nationality;
                $complete_address=$this->_commonmodel->getTaxPayerAddress($data->citizen_id);
				//$house_no.$cit_street_name.$cit_subdivision.$brgy_data;

	            //echo "<pre>"; print_r($healthcertreq); exit;         
	        }
			if(!empty($data->hahc_document_json)){
            $arrdocDtls = $this->generateDocumentList($data->hahc_document_json,$data->id);
				if(isset($arrdocDtls)){
					$data->arrDocumentDetailsHtml = $arrdocDtls;
				}
			}
            if($request->input('submit')!=""){
	            foreach((array)$this->data as $key=>$val){
	                $this->data[$key] = $request->input($key);
	            }
                if(!empty($this->data['bend_id']))
                {
                    $busn_end_data=$this->_hoapphealthcert->getBusnId($this->data['bend_id']);
                    $this->data['busn_id'] = $busn_end_data->busn_id;
                }
                $citizen_data=$this->_hoapphealthcert->getCitizenDetails($this->data['citizen_id']);
                $this->data['brgy_id'] = $citizen_data->brgy_id;
	            if($request->input('id')>0){
                    $this->is_permitted($this->slugs, 'update');
                    $ret = HoAppHealthCert::find($request->input('id'));
                    $this->data['updated_by']=\Auth::user()->id;
                    $this->data['updated_at'] = date('Y-m-d H:i:s');
                    $this->data['hahc_status'] = $ret->hahc_status;
                    if($this->data['hahc_recommending_approver'] == NULL)
                    {
                        $this->data['hahc_recommending_approver']=$ret->hahc_recommending_approver; 
                        $this->data['hahc_recommending_approver_position']=$ret->hahc_recommending_approver_position; 
                    }
                    if($this->data['hahc_approver'] == NULL)
                    {
                        $this->data['hahc_approver']=$ret->hahc_approver; 
                        $this->data['hahc_approver_position']=$ret->hahc_approver_position; 
                    } 
                    if($auth->id == $ret->hahc_recommending_approver || $auth->id == $ret->hahc_approver){

                        if($request->has('hahc_approver_status'))
                        {
                            $currentYear = $ret->hahc_app_year;
                            $this->data['hahc_approver_status']=1;
                            if($ret->hahc_registration_no == NULL)
                            {
                                $newNumber = sprintf('%06d', $ret->hahc_app_no);
                                $newValue =  $currentYear."-".$newNumber;
                                $this->data['hahc_registration_no']=$newValue;
                            }
                        }else{
                            $this->data['hahc_approver_status']=$ret->hahc_approver_status;
                        }
                        if($request->has('hahc_recommending_approver_status')){
                            $this->data['hahc_recommending_approver_status']=1;
                        }
                        else{
                            $this->data['hahc_recommending_approver_status']=$ret->hahc_recommending_approver_status;
                        }
                    }
                    else{
                        $this->data['hahc_recommending_approver_status']=$ret->hahc_recommending_approver_status;
                        $this->data['hahc_approver_status']=$ret->hahc_approver_status;
                    }
                    $this->_hoapphealthcert->updateData($request->input('id'),$this->data);
	                $success_msg = 'Health Certificate updated successfully.';
	                $lastinsertid = $request->input('id');
	            }else{
                    $this->is_permitted($this->slugs, 'create');
                    $currentYear = $this->data['hahc_app_year'];
                    $lastRecord = HoAppHealthCert::where('hahc_app_year',$currentYear)->orderBy('id','DESC')->first();
                    if(empty($lastRecord))
                    {
                        $lastNumber = 0;
                    }else{
                        $lastNumber = $lastRecord->hahc_app_no;
                    }
                    $newNumber = $lastNumber + 1;
                    $prefix = 'CHO';
                    $newValue = sprintf('%s-%04d-%06d', $prefix, $currentYear, $newNumber);
                    $this->data['hahc_app_code']=$prefix;
                    $this->data['hahc_app_no']=$newNumber;
                    $this->data['hahc_transaction_no']=$newValue;
                    $this->data['hahc_status']=1;
	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
	                $lastinsertid = $this->_hoapphealthcert->addData($this->data);
	                $success_msg = 'Health Certificate added successfully.';
                    $user_savedata = array();
                    $user_savedata['hahc_recommending_approver'] = $request->input('hahc_recommending_approver');
                    $user_savedata['hahc_recommending_approver_position'] = $request->input('hahc_recommending_approver_position');
                    $user_savedata['hahc_approver'] = $request->input('hahc_approver');
                    $user_savedata['hahc_place_of_work'] = $request->input('hahc_place_of_work');
                    $user_savedata['hahc_approver_position'] = $request->input('hahc_approver_position');
                    $userlastdata = array();
                    $userlastdata['form_id'] = 7;
                    $userlastdata['user_id'] = \Auth::user()->id;
                    $userlastdata['is_data'] = json_encode($user_savedata);
                    $userlastdata['created_at'] = date('Y-m-d H:i:s');
                    $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                    $checkisexist = $this->_hoapphealthcert->CheckFormdataExist('7',\Auth::user()->id);
                    if(count($checkisexist) >0){
                        $this->_hoapphealthcert->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                    }else{
                        $this->_hoapphealthcert->addusersaveData($userlastdata);
                    }
	            }
	            if(!empty($_POST['req_id']))
                {
                    $loop = count($_POST['req_id']); 
                    $healthcertreq = array();
                        for($i=0; $i < $loop;$i++){
                            $healthcertreq['hahc_id'] = $lastinsertid;
                        
                            if(!empty($_POST['req_id'][$i])){
                                $reqdata = explode('_',$_POST['req_id'][$i]);
                                $healthcertreq['req_id'] = $reqdata[0];
                            }
                            $healthcertreq['hahcr_category'] = $_POST['hahcr_category'][$i];
                            $healthcertreq['hahcr_exam_date'] = $_POST['hahcr_exam_date'][$i];
                            $healthcertreq['hahcr_exam_result'] = $_POST['hahcr_exam_result'][$i];
                            $healthcertreq['hahcr_remarks'] = $_POST['hahcr_remarks'][$i];
                            if(!empty($_POST['healthreqid'][$i])){
                                $this->_hoapphealthcert->updateHealthcertiReqData($_POST['healthreqid'][$i],$healthcertreq);
                            }else{
                                $this->_hoapphealthcert->addHealthcertiReqlData($healthcertreq);
                            }
                        }
	            }
	            // return redirect()->route('hoapphealthcert.index')->with('success', __($success_msg));
                return redirect()->to('healthy-and-safety/health-certificate')->with('success', __($success_msg));
	        }
	        return view('hoapphealthcert.create',compact('data','auth','citizen','employee','nationality','age','gender','complete_address','busn_name','barangay','arrTaxClasses','arrTaxTypes','countries','requirements','healthcertreq','healthcertreq'));
        
    }
    public function getOccuSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Perform the logic to fetch suggestions based on $query
        // Example:
        $suggestions = HoAppHealthCert::where('employee_occupation', 'LIKE', '%' . $query . '%')
        ->distinct('employee_occupation')
        ->pluck('employee_occupation');

        return response()->json($suggestions);
    }
    public function getWorkAddressSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Perform the logic to fetch suggestions based on $query
        // Example:
        $suggestions = HoAppHealthCert::where('hahc_place_of_work', 'LIKE', '%' . $query . '%')->distinct('hahc_place_of_work')->pluck('hahc_place_of_work');

        return response()->json($suggestions);
    }

    public function upload(Request $request){
        $id=$request->input('id'); 
        $data = HoAppHealthCert::find($id);
        $arrdocDtls = $this->generateDocumentList($data->hahc_document_json,$data->id);
        if(isset($arrdocDtls)){
            $arrDocumentDetailsHtml = $arrdocDtls;
        }     
        return view('hoapphealthcert.upload_doc',compact('id','arrDocumentDetailsHtml'));
    
    }
    public function uploadDocument(Request $request){
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoAppHealthCert::find($healthCertId);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';

        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->hahc_document_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/health_certificate/';
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
                    $arrJson = json_decode($arrEndrosment->hahc_document_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['hahc_document_json'] = json_encode($finalJsone);
                $this->_hoapphealthcert->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['hahc_document_json'],$healthCertId);
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
                                <a class='btn' href='".asset('uploads/health_certificate').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoAppHealthCert::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->hahc_document_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/health_certificate/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['hahc_document_json'] = json_encode($arrJson);
                    $this->_hoapphealthcert->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
    

    
    public function deleteCertificateReq(Request $request,$id){
        $this->_hoapphealthcert->deleteCertificateReq($id);
        
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'hahc_app_year'=>'required', 
                'employee_occupation'=>'required', 
                'hahc_issuance_date'=>'required',
                'hahc_expired_date'=>'required',
                'citizen_id'=>'required',
                'hahc_place_of_work'=>'required',
                'hahc_recommending_approver'=>'required',
                'hahc_recommending_approver_position'=>'required',
                'hahc_approver'=>'required',
                'hahc_approver_position'=>'required',
            ],[
                'hahc_app_year.required' => 'Year is Required',
                'employee_occupation.required' => 'Occupation is Required',
                'hahc_issuance_date.required' => 'Issuance date is Required',
                'citizen_id.required' => 'Citizens Name is Required',
                'hahc_place_of_work.required' => 'Work Place is Required',
                'hahc_recommending_approver.required' => 'Approver is Required',
                'hahc_recommending_approver_position.required' => 'Approver Position is Required',
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
    public function hoapphealthcertPrint(Request $request){
        $id=$request->input('id');
        $table1 =""; $table2 =""; $table3="";
        $data = $this->_hoapphealthcert->getHoappHealthcerti($id);
        $owner=(!empty($data->cit_first_name) ? $data->cit_first_name . ' ' : '') . (!empty($data->cit_middle_name) ? $data->cit_middle_name . ' ' : '') . (!empty($data->cit_last_name) ? $data->cit_last_name : '') . (!empty($data->cit_suffix_name) ? ', '.$data->cit_suffix_name : '');
        $birthDateCarbon = Carbon::parse($data->cit_date_of_birth);
        $currentDate = Carbon::now();
        $age = $birthDateCarbon->diffInYears($currentDate);
        $table1data = $this->_hoapphealthcert->getgetHealthcertiReqDatabyCat($id,'0');
        //echo "<pre>"; print_r($table1data); exit;
        if(count($table1data) == 1){
                $t1r1c1=($table1data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table1data[0]->hahcr_exam_date)->format('d-M-Y') : "";
                $t1r1c2=$table1data[0]->ho_service_name;
                $t1r1c3=$table1data[0]->hahcr_exam_result;
                $t1r2c1="";
                $t1r2c2="";
                $t1r2c3="";
        }
        elseif(count($table1data) >= 2)
        {
                $t1r1c1=($table1data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table1data[0]->hahcr_exam_date)->format('d-M-Y') : "";
                $t1r1c2=$table1data[0]->ho_service_name;
                $t1r1c3=$table1data[0]->hahcr_exam_result;
                $t1r2c1=($table1data[1]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table1data[1]->hahcr_exam_date)->format('d-M-Y') : "";
                $t1r2c2=$table1data[1]->ho_service_name;
                $t1r2c3=$table1data[1]->hahcr_exam_result;
        }
        else{
            $t1r1c1="";
            $t1r1c2="";
            $t1r1c3="";
            $t1r2c1="";
            $t1r2c2="";
            $t1r2c3="";
        }
        

        $table2data = $this->_hoapphealthcert->getgetHealthcertiReqDatabyCat($id,'1');
        //echo "<pre>"; print_r($table1data); exit;
        if(count($table2data) == 1){
                $t2r1c1=($table2data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table2data[0]->hahcr_exam_date)->format('d-M-Y') : "";
                $t2r1c2=$table2data[0]->ho_service_name;
                $t2r1c3=$table2data[0]->hahcr_exam_result;
                $t2r2c1="";
                $t2r2c2="";
                $t2r2c3="";
        }
        elseif(count($table2data) >= 2)
        {
            $t2r1c1=($table2data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table2data[0]->hahcr_exam_date)->format('d-M-Y') : "";
            $t2r1c2=$table2data[0]->ho_service_name;
            $t2r1c3=$table2data[0]->hahcr_exam_result;
            $t2r2c1=($table2data[1]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table2data[1]->hahcr_exam_date)->format('d-M-Y') : "";
            $t2r2c2=$table2data[1]->ho_service_name;
            $t2r2c3=$table2data[1]->hahcr_exam_result;
        }
        else{
            $t2r1c1="";
            $t2r1c2="";
            $t2r1c3="";
            $t2r2c1="";
            $t2r2c2="";
            $t2r2c3="";
        }
        $table3data = $this->_hoapphealthcert->getgetHealthcertiReqDatabyCat($id,'2');
        //echo "<pre>"; print_r($table1data); exit;
        if(count($table3data) == 1){
            $t3r1c1=($table3data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table3data[0]->hahcr_exam_date)->format('d-M-Y') : "";
            $t3r1c2=$table3data[0]->ho_service_name;
            $t3r1c3=$table3data[0]->hahcr_exam_result;
            $t3r2c1="";
            $t3r2c2="";
            $t3r2c3="";
        } 
        elseif(count($table3data) >= 2)
        {
            $t3r1c1=($table3data[0]->hahcr_exam_date != '0000-00-00') ? Carbon::parse($table3data[0]->hahcr_exam_date)->format('d-M-Y') : "";
            $t3r1c2=$table3data[0]->ho_service_name;
            $t3r1c3=$table3data[0]->hahcr_exam_result;
            $t3r2c1=($table3data[1]->hahcr_exam_date != "0000-00-00") ? Carbon::parse($table3data[1]->hahcr_exam_date)->format('d-M-Y') : "";
            $t3r2c2=$table3data[1]->ho_service_name;
            $t3r2c3=$table3data[1]->hahcr_exam_result;
        } else{
            $t3r1c1="";
            $t3r1c2="";
            $t3r1c3="";
            $t3r2c1="";
            $t3r2c2="";
            $t3r2c3="";
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $filename="";
        $html = file_get_contents(resource_path('views/layouts/templates/HealthCertificatePerfect.html'));
        $logo = url('/assets/images/logo.png');
        $sign = url('/assets/images/signeture2.png');  
        $bgimage = url('/assets/images/clearancebackground.jpg');
        $html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{OWNERNAME}}',$owner, $html);
        $data->employee_occupation == NULL ? $occupation="N/A" :  $occupation=$data->employee_occupation;
        $html = str_replace('{{occupation}}',$occupation, $html);
        $html = str_replace('{{age}}',$age, $html);
        $html = str_replace('{{BUSSINESS}}',"sdcsdcd", $html);
        $gender=$this->gend[$data->cit_gender];
        $html = str_replace('{{GENDER}}',$gender, $html);
        $data->nationality == NULL ? $nationality="N/A" :  $nationality=$data->nationality;
        $html = str_replace('{{nationality}}',$nationality, $html);
        $html = str_replace('{{WORKPLACE}}',$data->hahc_place_of_work, $html);
        $html = str_replace('{{REGISTRATION}}',$data->hahc_registration_no, $html);
        $issueddate = date("F d, Y",strtotime($data->hahc_issuance_date));
        $expirydate = date("F d, Y",strtotime($data->hahc_expired_date));
        $html = str_replace('{{DATE}}',$issueddate, $html);
        $html = str_replace('{{EXPIRE}}',$expirydate, $html);
        $html = str_replace('{{SIGN}}',$sign, $html);
        $html = str_replace('{{r_apv_name}}',$data->r_apv_name, $html);
        $html = str_replace('{{apv_name}}',$data->apv_name, $html);
        $html = str_replace('{{hahc_recommending_approver_position}}',$data->hahc_recommending_approver_position, $html);
        $html = str_replace('{{hahc_approver_position}}',$data->hahc_approver_position, $html);

        $html = str_replace('{{t1r1c1}}',$t1r1c1, $html);
        $html = str_replace('{{t1r1c2}}',$t1r1c2, $html);
        $html = str_replace('{{t1r1c3}}',$t1r1c3, $html);
        $html = str_replace('{{t1r2c1}}',$t1r2c1, $html);
        $html = str_replace('{{t1r2c2}}',$t1r2c2, $html);
        $html = str_replace('{{t1r2c3}}',$t1r2c3, $html);

        $html = str_replace('{{t2r1c1}}',$t2r1c1, $html);
        $html = str_replace('{{t2r1c2}}',$t2r1c2, $html);
        $html = str_replace('{{t2r1c3}}',$t2r1c3, $html);
        $html = str_replace('{{t2r2c1}}',$t2r2c1, $html);
        $html = str_replace('{{t2r2c2}}',$t2r2c2, $html);
        $html = str_replace('{{t2r2c3}}',$t2r2c3, $html);

        $html = str_replace('{{t3r1c1}}',$t3r1c1, $html);
        $html = str_replace('{{t3r1c2}}',$t3r1c2, $html);
        $html = str_replace('{{t3r1c3}}',$t3r1c3, $html);
        $html = str_replace('{{t3r2c1}}',$t3r2c1, $html);
        $html = str_replace('{{t3r2c2}}',$t3r2c2, $html);
        $html = str_replace('{{t3r2c3}}',$t3r2c3, $html);
        $mpdf->WriteHTML($html);
        
        $filename = $data->id."-Health Certificate.pdf";
                $arrSign= $this->_commonmodel->isSignApply('health_safety_endorsement_bplo_health_cert_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_endorsement_bplo_health_cert_approved_by');
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

}
