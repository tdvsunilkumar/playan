<?php

namespace App\Http\Controllers;

use App\Models\HoRecordCard;
use App\Models\HoGuardian;
use App\Models\Ho_Medical_Record;
use App\Models\Ho_Treatment;
use App\Models\Ho_Medical_Record_Diagnosis;
use App\Models\CommonModelmaster;
use App\Models\HoAppHealthCert;
use App\Models\BploBusiness;
use App\Models\Barangay;
use App\Models\BploBusinessPsic;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Carbon\CarbonPeriod;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use App\Models\HelSafRegistration;
class MenHelRecordCardController extends Controller
{
     public $data = [];
     private $slugs;
   
     public $gend = ['0'=>"Male",'1'=>"Female"]; 
     public function __construct(){
        $this->_horecordcard = new HoRecordCard();
        $this->_addguardian = new HoGuardian();
        $this->_homedicalrecord = new Ho_Medical_Record();
        $this->_hotreatment = new Ho_Treatment();
        $this->_hodiagnosis = new Ho_Medical_Record_Diagnosis();
        $this->_commonmodel = new CommonModelmaster();
		$this->data = array('id'=>'','cit_id'=>'','rec_card_num'=>'','rec_card_status'=>'');
        $this->slugs = 'health-and-safety/mental-health/record-card';
    }
    public function index(Request $request)
    {
        
        $this->is_permitted($this->slugs, 'read');
        return view('recordcard.index');
    }
    public function indexMedical(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('medicalrecord.index');
    }
    
    public function getList(Request $request){
        $data=$this->_horecordcard->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;      
        foreach ($data['data'] as $row){
            // dd($row);
            $j=$j+1;
            $status =($row->rec_card_status == 1) ? '
            <div class="action-btn btn-sm btn-danger ms-2">
                <a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' title="Remove"></a>' 
                : 
            '<div class="action-btn bg-info ms-2">
                <a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->record_id.' title="Restore"></a>';
            $arr[$i]['no']=$j;
            $arr[$i]['rec_card_num']=$row->rec_card_num;
            $arr[$i]['name']=$row->cit_fullname;   
            $arr[$i]['barangay']=$row->brgy_name;
            $arr[$i]['age']=$row->patient->age_human;
            $arr[$i]['sex']=($row->cit_gender==1?'Female':'Male');
            $arr[$i]['philhealth_member']=(($row->cit_philhealth_no)?'Yes':'No');
            $arr[$i]['status']=($row->rec_card_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" 
                        data-url="'.url('/recordcard/store?id='.$row->record_id).'" 
                        data-ajax-popup="true"  
                        data-size="lg" 
                        data-bs-toggle="tooltip" 
                        title="Manage Record Card"  
                        data-title="Manage Record Card">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <button class="mx-3 btn btn-sm btn_open_labreq_modal align-items- " 
                        data-url="'.url('/medical-record/store?record_id='.$row->record_id).'" 
                        data-ajax-popup="true"  
                        data-size="lg" 
                        data-bs-toggle="tooltip" 
                        title="Add Medical Record" 
                        data-title="Add Medical Record">
                        <i class="ti-file text-white"></i>
                    </button>
                </div>
                <div class="action-btn bg-info ms-2">
                    <button class="mx-3 btn btn-sm btn_open_labreq_modal align-items- " 
                        data-url="'.url('/laboratory-request/store?cit_id='.$row->cit_id).'" 
                        data-ajax-popup="true"  
                        data-size="lg" 
                        data-bs-toggle="tooltip" 
                        title="Add Lab Request" 
                        data-title="Add Lab Request">
                        <i class="ti-clipboard text-white"></i>
                    </button>
                </div>
                    <div class="action-btn bg-info ms-2">
                        <a href="#" 
                            class="mx-3 btn btn-sm  align-items-center" 
                            data-url="'.url('/medical-certificate/store?citizen_id='.$row->cit_id).'" 
                            data-ajax-popup="true"  
                            data-size="xxl" 
                            data-bs-toggle="tooltip" 
                            title="Add Medical Certificate"  
                            data-title="Manage Medical Certificate">
                            <i class="ti-receipt text-white"></i>
                        </a>
                    </div>
                    '.$status.'
                    </div>
                </div>' ;
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

    public function getCitizenDetailsRecord(Request $request){
    	$id= $request->input('id');
        $data = $this->_horecordcard->getCitizenDetails($id);
        $brgy=Barangay::findDetails($data->brgy_id);
        $now = Carbon::now();
        $age = $now->diffInYears(Carbon::parse($data->cit_date_of_birth));
        $house_no=(!empty($data->cit_house_lot_no)) ? $data->cit_house_lot_no.',' : "";
        $cit_philhealth_no_data=(!empty($data->cit_philhealth_no)) ? $data->cit_philhealth_no.',' : "";
        $cit_occupation_data=(!empty($data->occupation)) ? $data->occupation.',' : "";
        $cit_nationality_data=(!empty($data->nationality)) ? $data->nationality.',' : "";
        $cit_street_name=(!empty($data->cit_street_name)) ? $data->cit_street_name.',' : "";
        $cit_subdivision=(!empty($data->cit_subdivision)) ? $data->cit_subdivision.',' : "";
        $brgy_data=(!empty($brgy)) ? $brgy : "";
        $gender=$this->gend[$data->cit_gender];
        $complete_address=$house_no.$cit_street_name.$cit_subdivision.$brgy_data;
        $details=[
                    'age' =>  $age,
                    'complete_address' => $complete_address,
                    'gender' => $gender,
                    'philhealth_no' => $cit_philhealth_no_data,
                    'occupation' => $cit_occupation_data,
                    'nationality' => $cit_nationality_data
        ];
        echo json_encode($details);
    }

    public function getGuardianDetailsRecord(Request $request){
    	$id= $request->input('id');
        $data = $this->_horecordcard->getGuardianDetails($id);
        
        $brgy=Barangay::findDetails($data->brgy_id);
        $now = Carbon::now();
        $house_no=(!empty($data->cit_house_lot_no)) ? $data->cit_house_lot_no.',' : "";
        $cit_mobile_no_data=(!empty($data->cit_mobile_no)) ? $data->cit_mobile_no.',' : "";
        $cit_status=(!empty($data->cit_is_active==1)) ? "yes" : "No";
        $cit_street_name=(!empty($data->cit_street_name)) ? $data->cit_street_name.',' : "";
        $cit_subdivision=(!empty($data->cit_subdivision)) ? $data->cit_subdivision.',' : "";
        $brgy_data=(!empty($brgy)) ? $brgy : "";
        $complete_address=$house_no.$cit_street_name.$cit_subdivision.$brgy_data;
        $guardiandetails=[
                    'get_complete_address' => $complete_address,
                    'mobile_no' => $cit_mobile_no_data,
                    'status' => $cit_status
        ];
        echo json_encode($guardiandetails);
    }

    public function defaultUpdateCode(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_print');
        $data=array('is_default' => $is_activeinactive);
        $dataupt = array('is_default' => '0');
        $this->_horecordcard->updateDefaultall($dataupt);
        $this->_horecordcard->updateData($id,$data);
    }

    public function activate(Request $request){
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('rec_card_status' => $is_activeinactive);
        $this->_horecordcard->updateActiveInactive($request->id,$data);
    }

    public function guardianActivate(Request $request, $id){
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('guardian_status' => $is_activeinactive);
        $this->_horecordcard->updateGuardianActiveInactive($id,$data);
        return $is_activeinactive;
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $diagnosisdata=array();
        $empdata=array();
        $status = "";
        $citizensdata = array();
        $citizenDetails = array();
        $medicaldata = array();
        $services = array();
        $medical_issuances = array();


        if($request->input('id')>0 && $request->input('submit')==""){

            foreach ($this->_homedicalrecord->diagnosisData() as $val) {
                $diagnosisdata[$val->id]= $val->diag_name;
            }
            foreach ($this->_homedicalrecord->empData() as $val) {
                $empdata[$val->fullname]= $val->fullname;
            }

            $data = HoRecordCard::find($request->input('id'));
            $citizenDetails = $this->_horecordcard->getCitizenDetails($data->cit_id);
            // $guardianDetails = $this->_horecordcard->getGuardianDetails($data->cit_id);
            $citizensdata = $this->_addguardian->getAddMoreDataServices($request->input('id'));
            $medicaldata = $data->medicalrecord;
            $medical_issuances = $this->_homedicalrecord->getIssuances($data->cit_id);
            // $date= $dob;

            
        }
    
        if($request->isMethod('post')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['rec_card_status']);
                $this->_horecordcard->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Record Card Updated Successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rec_card_status'] = 1;
                $lastinsertid = $this->_horecordcard->addData($this->data);
                HelSafRegistration::register($this->data['cit_id'],date('Y-m-d'),'is_opd');
                
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added ";
                $success_msg = 'Record Card Added Successfully.';
            }
            $data = $request->input();
            $logDetails['module_id'] =$lastinsertid;
            $this->_commonmodel->updateLog($logDetails);
            $data['id'] = $logDetails['module_id'];
            $this->_horecordcard->addGuardian($data);
            // dd($request->input());
            return redirect()->route('recordcard.index')->with('success', __($success_msg));
        }
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
        return view('recordcard.create',compact('data','medicaldata','empdata','citizenDetails','diagnosisdata','services', 'medical_issuances','citizensdata'));
        
    }

    public function deleteRecordCard(Request $request,$id){
        $this->_addguardian->deleteRecordCard($id);  
    }

    public function deleteMedicalRecordCard(Request $request,$id){
        $this->_homedicalrecord->deleteMedicalRecordCard($id);  
    }

    public function deleteTreatment(Request $request,$id){
        $this->_hotreatment->deleteTreatment($id);  
    }

    public function deleteDiagnosis(Request $request,$id){
        $this->_hodiagnosis->deleteDiagnosis($id); 
    }
    
    public function formValidation(Request $request){
        $rule = [
            'rec_card_num'=>'required', 
            'cit_id'      =>'required',
        ];
        if (!$request->id) {
            $rule = array_merge($rule, 
            [
                'rec_card_num'=>'required|unique:ho_record_cards,rec_card_num',
                'cit_id'      =>'required|unique:ho_record_cards,cit_id',
            ]
        );
        }
        // if (!$request->id){
        $validator = \Validator::make(
            
            $request->all(), 
           
            $rule,[
                'rec_card_num.required' => 'Record Card Number is Required',
                'cit_id.required' => 'Patient Name is Required',
                'cit_id.unique' => 'The Name already exist',
                'rec_card_num.unique' => 'The Record No. has existing record ',
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
        
    // }
    }
    
    public function getCitizens(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Citizen = $this->_horecordcard->getCitizen($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->cit_id;
            $data['data'][$key]['text']=$value->cit_fullname;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoRecordCard::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/horecordcard/';
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
                $this->_horecordcard->updateData($healthCertId,$data);
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
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn' href='".asset('uploads/horecordcard').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoRecordCard::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/horecordcard/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_horecordcard->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
