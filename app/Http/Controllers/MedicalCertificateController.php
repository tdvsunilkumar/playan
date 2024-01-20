<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoIssuance;
use App\Models\HoMedicalCertificate;
use App\Models\CommonModelmaster;
use Auth;
use DB;
use File;
use PDF;
use Carbon\Carbon;
use Carbon\CarbonInterface;
class MedicalCertificateController extends Controller
{
    private $slugs;
	public $_HoIssuance;
    public $getbarangay = array(""=>"Please Select");
    public function __construct(){
        $this->_HoIssuance = new HoIssuance(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_HoMedicalCertificate = new HoMedicalCertificate(); 
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'or_date'=>'',
            'or_amount'=>'',
            'cashierd_id'=>'',
            'cashier_id'=>'',
            'med_cert_date'=>'',
            'med_cert_type'=>'',
            'incedent_nature'=>'',
            'incedent_place'=>'',
            'incedent_datetime'=>'',
            'med_cert_findings'=>'',
            'med_officer_id'=>'',
            'med_cert_is_free'=>'',
            'med_officer_approved_status'=>'',
            'med_officer_position'=>0,
            'or_no'=>'',
            'doc_json'=>''
        );
        $this->slugs = 'medical-certificate';
    }
 
    public function index(){
        try{
            $this->is_permitted($this->slugs, 'read');
            return view('medicalCertificate.index');
        }catch(Exception $e){
            return ($e->getMessage());
        }
    }

    public function GetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_HoMedicalCertificate->getList($request);
        $arr=array();
        $i="0";
        $sr_no=(int)$request->input('start')-1;
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {   
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" 
                        class="mx-3 btn btn-sm  align-items-center" 
                        data-url="'.url('/medical-certificate/store').'?id='. $row->id .'" 
                        data-ajax-popup="true" 
                        data-size="xxl" 
                        data-bs-toggle="tooltip" 
                        title="Edit"  
                        data-title="Mangage Medical Certificate">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
                $actions.= '<div class="action-btn bg-info ms-2">
                    <a href="medical-record/print/'.$row->id.'" class="mx-3 btn btn-sm  align-items-center" target="_blank" data-size="lg" data-bs-toggle="tooltip" title="Print" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';   
            }
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['cit_id']= $row->citizen_name;
            $arr[$i]['cit_age']= $row->med_cert_cit_age; 
            $arr[$i]['address']= $row->brgy_name .', '. $row->mun_desc;
            $arr[$i]['med_officer_id']= $row->officer_name;
            $arr[$i]['med_cert_date']= Carbon::parse($row->med_cert_date)->format('M d, Y');
            $arr[$i]['or_no']= $row->med_cert_is_free ? 'Free' : $row->or_no;
            $arr[$i]['amount']= $row->or_amount == 0.00 ? 'Free' : $row->or_amount;
            $arr[$i]['approval_status']= ($row->med_officer_approved_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approved</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['is_active']= ($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
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
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('is_active' => $is_activeinactive);
            $this->_HoMedicalCertificate->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function store(request $request){
        try {
            // $citizen_id = '1';
            // Getting the Patient Details
            $citizens = (object)[
                'cit_id'=>null,
                'cit_fullname'=>null,
                'age_human'=>null,
                'cit_full_address'=>null,
                'mun_desc'=>null,
            ];
            $select_or_nos = ['' => 'Select OR No'];
            $cert_type =config('constants.med_cert_type');
            
            $getbarangay = $this->getbarangay;
            // If its in add
            $barangays = $this->_commonmodel->getBarangay();
            foreach ($barangays['data'] as $val) {
                $getbarangay[$val->id]=$val->brgy_name.", ".$val->mun_desc;
            }

            if(isset($request->citizen_id)){
                $citizen_id = $request->citizen_id;//for or number
                $citizens = $this->_HoMedicalCertificate->getCitizenAddress($citizen_id);
            }
            // $citizens = $this->_HoMedicalCertificate->getCitizenAddress($citizen_id);
            
            // Getting the health officerd
            $employees = $this->_HoIssuance->getEmployeeByHealth();
            $select_health_officer = ['' => 'Select Health Officer'];
            foreach ($employees as $key => $value) {
                $select_health_officer[$value->id] = $value->fullname;
            };

            $medical_report = array();
            $selected = [
                "medical_officer" => null,
                "userid" => null,
                "approved_status" => null,
                "licence_number" => null,
                "position" => null,
                "or_no" => null,
                "or_date" => null,
                "amount" => null,
                "is_free" => null,
                "cashierd_id" => null,
                "cashier_id" => null,
                "id" => null,
                "med_cert_date" => date('Y-m-d'),
                "med_cert_type" => null,
                "incedent_nature" => null,
                "incedent_place" => null,
                "med_officer_approved_status" => null,
                "med_cert_findings" => null,
                "med_cert_cit_age" => null,
                "incedent_datetime" => date('Y-m-d'),
            ];

            // Getting the officer last time inserted

            $lastReport = $this->_HoMedicalCertificate->getLastReport(); 
            if($lastReport != ''){
                $is_data = json_decode($lastReport->is_data);
                $selected['medical_officer'] = $is_data->officer_id;
                $selected['position'] = $is_data->position;
                $designation = $this->_HoIssuance->getDesignation($selected['medical_officer']);
                $selected['licence_number'] = $designation->licence_no;
            }

            // This Section Is For edit
            if($request->input('id') > 0 && $request->input('submit') == ""){ 
                $medical_report = $this->_HoMedicalCertificate->getSingleReport($request->input('id'));

                foreach ($this->_commonmodel->getBarangay($medical_report->incedent_place)['data'] as $val) {
                    $getbarangay[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
                }

                $user_id = $this->_HoMedicalCertificate->getSingleUser($request->input('id'));
                $selected['userid'] = $user_id;
                $selected['medical_officer'] = $medical_report->med_officer_id;
                $selected['approved_status'] = $medical_report->med_officer_approved_status;
                $designation = $this->_HoIssuance->getDesignation($selected['medical_officer']);
                $selected['licence_number'] = $designation->licence_no;
                $selected['position'] = $medical_report->med_officer_position;
                $selected['or_no'] = $medical_report->or_no;
                $selected['or_date'] = $medical_report->or_date;
                $selected['amount'] = $medical_report->or_amount;
                $selected['is_free'] = $medical_report->med_cert_is_free;
				if(!empty($medical_report->doc_json)){
                    $selected['doc_json'] = $medical_report->doc_json;
                }
                $selected['cashierd_id'] = $medical_report->cashierd_id;
                $selected['cashier_id'] = $medical_report->cashier_id;
                $selected['id'] = $medical_report->id;
                $selected['med_cert_date'] = $medical_report->med_cert_date;
                $selected['med_cert_type'] = $medical_report->med_cert_type;
                $selected['incedent_nature'] = $medical_report->incedent_nature;
                $selected['incedent_datetime'] = $medical_report->incedent_datetime;
                $selected['incedent_place'] = $medical_report->incedent_place;
                $selected['med_cert_findings'] = $medical_report->med_cert_findings;
                $selected['med_cert_cit_age'] = $medical_report->med_cert_cit_age;
                $citizens = $this->_HoMedicalCertificate->getCitizenAddress($medical_report->cit_id);
                $citizen_id = $medical_report->cit_id;//for or number
            }
            if (isset($citizen_id)){
                // Getting the or no
                $or_nos = $this->_HoMedicalCertificate->getOrNumbers($citizen_id);
                foreach ($or_nos->get() as $key => $value) {
                    $select_or_nos[$value->or_no] = $value->or_no;
                };
            }
            // This Section Is For Add And Update
            if($request->isMethod('post')!=""){
                if($request->input('id') > 0){
					$medical_report = $this->_HoMedicalCertificate->getSingleReport($request->input('id'));
				}
                $data = (object)$this->data;
                foreach((array)$this->data as $key=>$val){
                    $this->data[$key] = $request->input($key);
                }
                // dd($this->data);
                $this->data['created_by'] = Auth::user()->id;
                $this->data['updated_by'] = Auth::user()->id;
                
                if(isset($this->data['med_cert_is_free'])){
                    $this->data['med_cert_is_free'] = 1;
                }else{
                    $this->data['med_cert_is_free'] = 0;
                }
                if(isset($this->data['med_officer_approved_status'])){
                    $this->data['med_officer_approved_status'] = 1;
                }else{
                    $this->data['med_officer_approved_status'] = 0;
                }
				
                if(!isset($this->data['or_no'])){
                    $this->data['or_date'] = '';
                    $this->data['or_amount'] = '';
                    $this->data['or_no'] = '';
                }
				if(!empty($medical_report->doc_json)){
                    $this->data['doc_json'] = $medical_report->doc_json;
                }
                unset($this->data['submit']);
                $message = "";
                
                if($request->input('id')>0){
                    $this->_HoMedicalCertificate->updateData($request->input('id'), $this->data);
                    $message = "Updated.";
                }else{
                    // dd($request->cit_age);
                    $this->data['med_cert_cit_age'] = $request->cit_age;
                    $this->data['is_active'] = 1;
                    $this->_HoMedicalCertificate->addData($this->data);
                    $message = "Added.";
                }
                return redirect()->route('medical-certificate')->with('success', __('Medical Certificate Successfully ' .$message));
            }
            if(!empty($medical_report->doc_json)){
			$arrdocDtls = $this->generateDocumentList($medical_report->doc_json,$medical_report->id);
				if(isset($arrdocDtls)){
					$selected['arrDocumentDetailsHtml'] = $arrdocDtls;
				}
			}else{
				$selected['arrDocumentDetailsHtml'] ="";
			}
            // dd($selected);
            // return $selected;
            return view('medicalCertificate.create', compact('select_health_officer','getbarangay','cert_type','citizens', 'select_or_nos', 'selected'));
        } catch (\Exception $e) {
            return redirect()->route('medical-certificate')->with('success', __('Medical Certificate Successfully ' .$message));
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                'cit_id'=>'required',
                'cit_age'=>'required',
                'med_officer_id'=>'required',
                'med_cert_date' => 'required',
                'med_officer_position'  => 'required',
                // 'or_date' => 'required',
                // 'med_officer_approved_status' => 'prohibits:or_no,med_cert_is_free',
                'or_no' => 'required_if:med_officer_approved_status,null|required_if:med_cert_is_free,null',
                'med_cert_is_free' => 'required_if:med_officer_approved_status,null|required_if:or_no,null',
                
			],[
				'cit_id.required' => 'Patient Name Is Required',
				'cit_age.required' => 'Patient Age Is Required',
				'med_officer_id.required' => 'Officer Name Is Required',
				// 'or_date.required' => 'OR Date Is Required',
				// 'or_amount.required' => 'OR Amount Is Required',
				
				'med_cert_date.required' => 'Certificate Date Is Required',
                'med_officer_position.required' => 'Position Is Required',
                'or_no.required' => 'OR Number Is Required',
				'med_cert_is_free.required_if' => 'OR No is required if not Free',
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

    public function getORNoDetails($or_no){
        try {
            $or_details = $this->_HoMedicalCertificate->getOrNumberDetails($or_no);
            return response()->json(['status' => 200, 'data' => $or_details]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }
	
	public function uploadDocument(Request $request){
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoMedicalCertificate::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/medicalcertificate/';
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
				//print_r($data['doc_json']);die;
                $this->_HoMedicalCertificate->updateMedicalData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/medicalcertificate').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoMedicalCertificate::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/medicalcertificate/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_HoMedicalCertificate->updateMedicalData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function getOr(Request $request, $citizen_id){
        $data = [];
        $or_nos = $this->_HoMedicalCertificate->getOrNumbers($citizen_id);
                foreach ($or_nos->get() as $key => $value) {
            $data['data'][$key]['id']=$value->or_no;
            $data['data'][$key]['text']=$value->or_no;
                };
        $data['data_cnt']=$or_nos->count();
        echo json_encode($data);
    }

    public function medCertHeader($data)
    {
        // 1 physical 2 mental 3 medico legal
        // dd($data->officer->identification_no);
        $border = 0;
        // 195.88 max width
        PDF::SetTitle("Med Cert");    
        PDF::SetMargins(10, 20, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        
        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 15, $y = 20, $w = 22, $h = 0, $type = 'PNG');
        
        $watermark_img2 = public_path('/assets/images/issuanceLogo.png');
        // echo('<img src="'.$watermark_img2.'" alt="">');
        // dd('s');
        PDF::SetAlpha(0.1);
        PDF::Image( $watermark_img2, $x = 40, $y = 70, $w = '', $h = 130);
        PDF::SetAlpha(1);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::MultiCell(0, 0, "<h3>CITY OF PALAYAN</h3>", '', 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln(15);
        PDF::SetLineStyle(array('width' =>.7));
        PDF::MultiCell(0, 0, "<h1>CITY HEALTH OFFICE</h1>", 'B', 'C', 0, 1  , '', '', true, 0, true);
        PDF::ln(10);    

        PDF::SetLineStyle(array('width' =>.1));

        $new_date = Carbon::parse($data->med_cert_date)->format('F d, Y');
        $officer = strtoupper($data->officer->fullname);
        $position = strtoupper($data->med_officer_position);
        $license = $data->officer->identification_no;
        
        PDF::MultiCell(150, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(36, 0, "<b>".$new_date."</b>", $border, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(150, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(36, 0, "Date", "T", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "FROM THE DESK OF:", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::MultiCell(0, 0, "<b>".$officer."</b>", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".$position."</b>", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>LICENSE No.: ".$license."</b>", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "<h1><u>MEDICAL CERTIFICATE</u></h1>", $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "<b>TO WHOM IT MAY CONCERN:</b>", $border, 'L', 0, 1, '', '', true, 0, true);
        
        
    }

    public function medCertFooter($data)
    {
        $border = 0;

        $officer = strtoupper($data->officer->fullname);
        $position = strtoupper($data->med_officer_position);
        $license = $data->officer->identification_no;

        PDF::MultiCell(120, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(70, "", $officer, $border, 1, 'C');
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(120, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, "<b>".$position."</b>", "T", 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(120, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, "LICENSE No.: ".$license, $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, "*NOT VALID WITHOUT DRY SEAL", $border, 'L', 0, 1, '', '', true, 0, true);
    }

    public function medCertPrint(Request $request, $id)
    {
        $data = HoMedicalCertificate::find($id);
        $this->medCertHeader($data);
        // dd($data->patient->cit_fullname);
        $border = 0;
        $cell_height = 5;

        $med_cert_type = $data->med_cert_type; // use this when table is updated
        // dd($med_cert_type);
        // $med_cert_type = 3; // comment this when table is updated
        $patient = strtoupper($data->patient->cit_fullname);
        $patient_brgy = strtoupper($data->patient->brgy->brgy_name);
        $patient_mun = strtoupper($data->patient->brgy->municipality->mun_desc);
        $new_date = strtoupper(Carbon::parse($data->med_cert_date)->format('F d, Y'));
        $age = $data->med_cert_cit_age;
        
        // for medico legal
        $noi = strtoupper(($data->incedent_nature) ? $data->incedent_nature: "");
        $toi = ($data->incedent_datetime) ? Carbon::parse($data->incedent_datetime)->format('H:i A') : "";
        $poi_brgy = ($data->incident_brgy) ? strtoupper($data->incident_brgy->brgy_name) : "";
        $poi_mun = ($data->incident_brgy) ? strtoupper($data->incident_brgy->municipality->mun_desc) : "";
        // dd($data->incident_brgy->municipality);
        $doi = ($data->incedent_datetime) ? Carbon::parse($data->incedent_datetime)->format('F d, Y') : "";
        // dd($doi);
        
        switch ($med_cert_type) {
            case 1: //physical
                PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This is to certify that I have seen and examined Mr./Mrs./Ms. <b><u>".$patient."</u></b>, <b><u>".$age."</u></b> from <b><u>BRGY. ".$patient_brgy.", ".$patient_mun." CITY</u></b> this <b><u>".$new_date."</u></b> because ______________________________.", $border, 'J', 0, 1, '', '', true, 0, true);
                PDF::ln(10);

                PDF::MultiCell(20, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>PERTINENT P.E. FINDINGS:</b>", $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::ln();
                PDF::MultiCell(60, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, nl2br($data->med_cert_findings), $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(20);

                PDF::MultiCell(20, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "This medical certificate was issued upon the request of the patient for any purpose it may serve except medical-legal.", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(10);

                break;
            case 3: //mental
                PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This is to certify that I have seen and examined Mr./Mrs./Ms. <b><u>".$patient."</u></b>, <b><u>".$age."</u></b> from <b><u>BRGY. ".$patient_brgy.", ".$patient_mun." CITY</u></b> today for psychiatric evaluation.", $border, 'J', 0, 1, '', '', true, 0, true);
                PDF::ln(10);

                // PDF::MultiCell(20, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>IMPRESSION:</b>", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(5);
                PDF::MultiCell(0, 0, '<p style="text-indent: 70px">'.nl2br($data->med_cert_findings).'</p>', $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::ln(20);

                PDF::MultiCell(0, 0, "<b>RECOMMENDATION:</b>", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(10);

                PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This medical certificate was issue for Medical Assistance/ PWD ID.", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(15);

                break;
            case 2: //medicol legal
                PDF::MultiCell(0, 0, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This is to certify that I have seen and examined Mr./Mrs./Ms. <b><u>".$patient."</u></b>, <b><u>".$age."</u></b> from <b><u>BRGY. ".$patient_brgy.", ".$patient_mun." CITY</u></b> last <b><u>".$new_date."</u></b> BECAUSE ______________________________", $border, 'J', 0, 1, '', '', true, 0, true);
                PDF::ln(5);

                PDF::MultiCell(12, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>PERTINENT P.E. FINDINGS:</b>", $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::MultiCell(12, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>NOI: ".$noi."</b>", $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::MultiCell(12, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>TOI: ".$toi."</b>", $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::MultiCell(12, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>POI: ".$poi_brgy.", ".$poi_mun." CITY</b>", $border, 'L', 0, 1, '', '', true, 0, true);

                PDF::MultiCell(12, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "<b>DOI: ".$doi."</b>", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(5);

                PDF::MultiCell(20, 0, "", $border, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(0, 0, "This medical certificate was issued upon the request of the patient for reference purpose .", $border, 'L', 0, 1, '', '', true, 0, true);
                PDF::ln(10);
                break;
            default:
                # code...
                break;
        }

        
        $this->medCertFooter($data);
        
        PDF::Output('Medical_Certificate.pdf');
    }
}
 