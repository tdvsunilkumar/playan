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
use Carbon\Carbon;
use Carbon\CarbonInterface;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\HelSafRegistration;
class MedicalRecordCardController extends Controller
{
    public $data = [];
    private $slugs;

    public $gend = ['0'=>"Male",'1'=>"Female"]; 
    public function __construct(){
        $this->_horecordcard = new HoRecordCard();
        $this->_homedicalrecord = new Ho_Medical_Record();
        $this->_hotreatment = new Ho_Treatment();
        $this->_commonmodel = new CommonModelmaster();
        $this->_hodiagnosis = new Ho_Medical_Record_Diagnosis();
        $this->slugs = 'medical-record';
        
        $this->data = array(
            'medical_rec_id'=>'',
            'hp_code'=>'',
            'cit_id'=>'',
            'cit_age'=>'',
            'cit_gender'=>'',
            'cit_age_days'=>'',
            'rec_card_id'=>'',
            'med_rec_nurse_note'=>'',
            'med_rec_date'=>Carbon::now(),
        );
    
        
    
    }
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		 return view('medicalrecord.index');
        
    }

    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('medical_rec_id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');    
            $data->med_rec_nurse_note =    'Height: 
Weight:
BP:
Temperature:
Covid Vaccine:';
			// do not move or make tab on this please
        }
        if($request->input('medical_rec_id')>0 && $request->isMethod('get')!=""){   
            $data = Ho_Medical_Record::where('medical_rec_id',$request->input('medical_rec_id'))->first();
            // dd($data->recordCard);
            $recordcard = $data->recordCard;
            $data->diagnosis = $recordcard->diagnosis;
            $data->treatment = $recordcard->treatment;
        }
        if($request->isMethod('post')!=""){
            // dd($request->input());
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('medical_rec_id')>0){
				$data = Ho_Medical_Record::where('medical_rec_id',$request->input('medical_rec_id'))->first();
				if(!empty($data->doc_json)){
					$this->data['doc_json'] = $data->doc_json;
				}
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
                $this->_homedicalrecord->updateMedicalData($request->input('medical_rec_id'),$this->data);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['med_rec_status'] = 1;
                $request->medical_rec_id =  $this->_homedicalrecord->addMedicalData($this->data);
                // dd($request->id );
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
                HelSafRegistration::register($this->data['cit_id'],date('Y-m-d'),'is_opd');
            }

            $logDetails['module_id'] =$request->medical_rec_id;
            $this->_commonmodel->updateLog($logDetails);

            $data = $request->input();
            $data['medical_rec_id'] = $logDetails['module_id'];
            $this->_homedicalrecord->addRelation($data);
            $data['med_rec_date'] = Carbon::parse($data['med_rec_date'])->toDayDateTimeString();
			// $data['diagnosis'] = $this->_homedicalrecord->where('medical_rec_id',$data['id'])->first()->diagnosis;
			
			return redirect()->back();
            return json_encode(
                [
                    'ESTATUS'=>0,
                    'msg'=>$success_msg,
                    'data' => $data
                ]
            );
			
        }
		    if (($request->input('record_id'))) {
				$recordcard = HoRecordCard::find($request->input('record_id'));
			}
            $data->rec_card_id = $recordcard->id;
            $data->rec_card_num = $recordcard->rec_card_num;
            $data->cit_id = $recordcard->cit_id;
            $data->cit_age = $recordcard->age;
            $data->cit_age_days = Carbon::parse($recordcard->cit_date_of_birth)->diffInDays();
            $data->patient = $recordcard->patient;
            $data->cit_gender = $recordcard->cit_gender;
			
		if(!empty($data->doc_json)){
         $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->ho_medical_records);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
			
        return view('medicalrecord.create',compact('data'));
    }

    public function diagnosisActive(Request $request, $id){
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_homedicalrecord->updateDiagnosisActiveInactive($id,$data);
        return $is_activeinactive;
    }

    public function treatmentisActive(Request $request, $id){
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('treat_is_active' => $is_activeinactive);
        $this->_homedicalrecord->updateTreatmentActiveInactive($id,$data);
        return $is_activeinactive;
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'hp_code'=>'required',
                
            ],[
                'hp_code.required'=>'Health Officer is required',
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

    public function getList(Request $request,$id = null){
        $data=$this->_homedicalrecord->getList($request,$id);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;      
        foreach ($data['data'] as $row){
            $rowId = $row->medical_rec_id;
            $j=$j+1;
            $status =($row->med_rec_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$rowId.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$rowId.'></a>';
            $bday = Carbon::parse($row->cit_date_of_birth);
            $register_date = Carbon::parse($row->cit_date_of_birth)->addDays($row->cit_age_days);
            $age_of_register = $register_date->diffForHumans($bday,['syntax' => CarbonInterface::DIFF_ABSOLUTE]);
            $arr[$i]['no']=$rowId;
            $arr[$i]['rec_card_num']=$row->recordCard->rec_card_num;
            $arr[$i]['name']=isset($row->recordCard->patient)?$row->recordCard->patient->cit_fullname:'';   
            $arr[$i]['barangay'] = isset($row->recordCard->patient->brgy) ? $row->recordCard->patient->brgy->brgy_name : '';
            $arr[$i]['age'] = $age_of_register . ' old' ;    
            $arr[$i]['sex'] = ($row->cit_gender == 0) ? 'Male' : 'Female';

            $diagnose = '<div class="showLess">';
            foreach ($row->diagnosis as $value) {
                // dd($value->diagnose->diag_name);
                $diagnose .= $value->diag_name.'<br>';
            }
            $diagnose .= '</div>';
            $treatment = '<div class="showLess">';
            foreach ($row->treatment as $value) {
                $treatment .= $value->treat_medication.'/'.$value->treat_management.'<br>';
            }
            $treatment .= '</div>';
            $arr[$i]['nurse_notes']= '<div class="showLess">'.wordwrap($row->med_rec_nurse_note, 30, "<br />\n",true).'</div>';
            $arr[$i]['action']='
            <div class="action-btn bg-warning ms-2">
                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/medical-record/store?medical_rec_id='.$rowId).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Medical Record">
                    <i class="ti-pencil text-white"></i>
                </a>
                    
                <!--<div class="action-btn bg-info ms-2">
                    <button class="mx-3 btn btn-sm btn_open_labreq_modal align-items- " data-url="'.url('/laboratory-request/store?medical_rec_id='.$rowId).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Add Lab Request">
                        <i class="ti-file text-white"></i>
                    </button>
                </div>-->
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
                
            if ($id) {
                $arr[$i]['nurse_notes']= wordwrap($row->med_rec_nurse_note, 30, "<br />\n",true);
                $treatment='';
                $diagnose='';
                foreach ($row->treatment as $value) {
                    $treatment .= $value->treat_medication.'/'.$value->treat_management.'<br>';
                }
                foreach ($row->diagnosis as $value) {
                    $diagnose .= wordwrap($value->diag_name, 30, "<br />\n",true).'<br>';
                }
                $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <button type="button" data-url="'.route('medical.store',['medical_rec_id'=>$rowId]).'" class="btn btn-sm btn-warning btn_open_labreq_modal" data-title="Medical Record">
                        <i class="ti-pencil text-white"></i>
                    </button>
                </div>
                <!--'.$status.'-->
                    </div>
                </div>' ;
                    
            }

            
            $arr[$i]['diagnosis']=$diagnose;
            $arr[$i]['treatment']=$treatment;
            $arr[$i]['attending_health_officer']=($row->officer) ? $row->officer->fullname : '';
            $arr[$i]['date_created']=Carbon::parse($row->med_rec_date)->format('M d, Y g:i A');
            $arr[$i]['status']=($row->med_rec_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
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
    
    public function recordActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('med_rec_status' => $is_activeinactive);
        $this->_homedicalrecord->updateActiveInactive($id,$data);

        echo json_encode('done');
    }

    public function selectDiagnosis(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Diagnosis = $this->_homedicalrecord->selectDiagnosis($q);
        foreach ($Diagnosis['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->diag_name;
        }
        $data['data_cnt']=$Diagnosis['data_cnt'];
        echo json_encode($data);
    }
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = Ho_Medical_Record::where('medical_rec_id', $healthCertId)->first();
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/medicalrecordcard/';
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
                $this->_homedicalrecord->updateMedicalData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/medicalrecordcard').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = Ho_Medical_Record::where('medical_rec_id', $healthCertid)->first();
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/medicalrecordcard/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_homedicalrecord->updateMedicalData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}