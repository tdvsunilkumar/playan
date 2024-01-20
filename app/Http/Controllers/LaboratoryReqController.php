<?php

namespace App\Http\Controllers;

use App\Models\HoLabRequest;
// use App\Models\add_laboratory_request;

use App\Models\CommonModelmaster;
use App\Models\HoAppHealthCert;
use App\Models\BploBusiness;
use App\Models\Barangay;
use App\Models\BploBusinessPsic;
use App\Models\HrEmployee;
use App\Models\HoLabReqFees;
use App\Models\HealthSafetySetupDataService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use DB;
use Carbon\CarbonPeriod;
// use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use App\Models\HelSafRegistration;
use PDF;

use App\Models\PregnancyTestModel;
use App\Models\HematologyModel;
use App\Models\FecalysisModel;
use App\Models\BloodSugarTest;
use App\Models\GramStainingTest;
use App\Models\SocialWelfare\Citizen;

class LaboratoryReqController extends Controller
{
    public $data = [];
    public $citizen = array(""=>"Select Citizen");
    public $getEmployee  = array(""=>"Please Select");
    private $slugs;
    public $gend = ['0'=>"Male",'1'=>"Female"]; 

    public function __construct(){
        $this->_holabrequest = new HoLabRequest();
        // $this->_addlabrequest = new add_laboratory_request();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'hp_code'=>'',
            'req_phys'=>'',
            'lab_req_year'=>Carbon::now()->year(),
            'lab_reg_date'=>Carbon::now(),
            'lab_req_diagnosis'=>'',
            'trans_id'=>'',
            'payor_id'=>'',
            'lab_req_or'=>'',
            'or_date'=>'',
            'lab_is_free'=>'',
            'lab_req_amount'=>'',
            'top_transaction_no'=>''
        );
        $this->slugs = 'laboratory-request';
        foreach ($this->_holabrequest->getEmployee() as $val) {
            $this->getEmployee[$val->id] = $val->fullname;
        }
    
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('laboratoryreq.index');
    }
    
    public function getList(Request $request){
        $data=$this->_holabrequest->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;      
        foreach ($data['data'] as $row){  
            $j=$j+1;
            
            $status =($row->is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn btn-sm btn-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
        			
			$arr[$i]['no'] = ($row->is_active == 1) ? '<div>'.$j.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$j.'</div>';
            $arr[$i]['control_no']=($row->is_active == 1) ? '<div>'.$row->lab_control_no.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->lab_control_no.'</div>';
            $arr[$i]['name']=($row->is_active == 1) ? '<div>'.$row->cit_fullname.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->cit_fullname.'</div>';
            $arr[$i]['barangay']=($row->is_active == 1) ? '<div>'.$row->brgy_name.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->brgy_name.'</div>';
            $arr[$i]['age']=($row->is_active == 1) ? '<div>'.$row->patient->age_human.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->patient->age_human.'</div>';
            $arr[$i]['gender']=($row->is_active == 1) ? '<div>'.($row->cit_gender==1?'Female':'Male').'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.($row->cit_gender==1?'Female':'Male').'</div>';
            $lab_req_diagnosis =($row->is_active == 1) ? '<div>'. wordwrap($row->lab_req_diagnosis, 40, "<br />\n").'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'. wordwrap($row->lab_req_diagnosis, 40, "<br />\n").'</div>';
            $arr[$i]['lab_req_diagnosis']=($row->is_active == 1) ? '<div>'."<div class='showLess'>".$lab_req_diagnosis."</div>".'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'."<div class='showLess'>".$lab_req_diagnosis."</div>".'</div>';
            if($row->lab_is_free == 1){
                $arr[$i]['top_no'] = ($row->is_active == 1) ? '<div>'.'Free'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'Free'.'</div>';
            }else{
            $arr[$i]['top_no']= ($row->is_active == 1) ? '<div>'.$row->top_transaction_no.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->top_transaction_no.'</div>';
            }
            $arr[$i]['or_no']= ($row->is_active == 1) ? '<div>'.$row->lab_req_or.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->lab_req_or.'</div>';
            $amount = ($row->is_active == 1) ? '<div>'.number_format($row->lab_req_amount, 2).'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.number_format($row->lab_req_amount, 2).'</div>';
            if($row->lab_req_amount == 0){
                $arr[$i]['amount']= ($row->is_active == 1) ? '<div>'.'Free'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'Free'.'</div>';
            }else{
                $arr[$i]['amount']=($row->is_active == 1) ? '<div>'.$amount.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$amount.'</div>';
            }
            if($row->lab_req_or == null && $row->or_date == null){
                $arr[$i]['payment_status']= ($row->is_active == 1) ? '<div>'.' '.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.' '.'</div>';
            }else if($row->lab_req_or != null && $row->status == 1){
                $arr[$i]['payment_status']= ($row->is_active == 1) ? '<div class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">'.'PAID'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'PAID'.'</div>';
            }else if($row->lab_req_or != null && $row->status == 0){
                $arr[$i]['payment_status']= ($row->is_active == 1) ? '<div span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">'.'OR CANCELLED'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'OR CANCELLED'.'</div>';
            }
            $arr[$i]['date_created']=($row->is_active == 1) ? '<div>'. Carbon::parse($row->lab_reg_date)->format('M d, Y g:i A').'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.Carbon::parse($row->lab_reg_date)->format('M d, Y g:i A').'</div>';
            $arr[$i]['save_status']=($row->lab_is_posted==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
             
            $arr[$i]['action']='
                <div class="action-btn btn-sm btn-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/laboratory-request/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Laboratory">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    <div class="action-btn btn-sm btn-info ms-2">
                    <a href="laboratory-request/print/'.$row->id.'" class="mx-3 btn btn-sm  align-items-center" target="_blank" data-size="lg" data-bs-toggle="tooltip" title="Print" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>
                    </div>
                    '.$status.'
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

    public function laboratoryActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('is_active' => $is_activeinactive);
            $this->_holabrequest->updateActiveInactive($id,$data);

            // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Lab Request ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }

    public function getListSpecific(Request $request,$cit_id){
		// try {
			$this->is_permitted($this->slugs, 'read');
			$data = $this->_holabrequest->getIssueByPatient($request,$cit_id);
			// return $data;
			$arr=array();
			$i="0";
			$sr_no=(int)$request->input('start')-1;
			$sr_no=$sr_no>0? $sr_no+1:0;
			foreach ($data['data'] as $row){
                switch ($row->desc->ho_service_form) {
                    case 1:
                        $url = route('hematology.store',['lab_id'=>$row->lab_req_id,'service_id'=>$row->desc->id]);
                        $title = 'Hematology: '.$row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 2:
                        $url = route('serology.store',['lab_id'=>$row->lab_req_id,'service_id'=>$row->service_id]);
                        $title = 'Serology: '.$row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 3:
                        $url = route('urinalysis.store',['lab_id'=>$row->lab_req_id]);
                        $title = $row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 4:
                        $url = route('fecalysis.store',['lab_id'=>$row->lab_req_id]);
                        $title = $row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 5:
                        $url = route('pregnancy-test.store',['lab_id'=>$row->lab_req_id]);
                        $title = $row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 6:
                        $url = route('blood-sugar-test.store',['lab_id'=>$row->lab_req_id,'service_id'=>$row->service_id]);
                        $title = $row->desc->ho_service_name;
                        $disable = '';
                        break;
                    case 7:
                        $url = route('gram-staining-test.store',['lab_id'=>$row->lab_req_id]);
                        $title = $row->desc->ho_service_name;
                        $disable = '';
                        break;
                    default:
                        $url = '#';
                        $title = '';
                        $disable = 'disabled';
                        break;
                }
				$sr_no=$sr_no+1;
				$actions = '';

				if ($this->is_permitted($this->slugs, 'update', 1) > 0) {   
					$actions .= '<div class="action-btn bg-warning ms-2">
						<a href="#" class="mx-3 btn btn-sm  align-items-center btn_open_issue_modal" 
							data-url="'.$url.'" 
							data-size="xxll" data-bs-toggle="tooltip" title="Mangage Laboratory"  
							data-title="'.$title.'" 
                            '.$disable.'>
							<i class="ti-pencil text-white"></i>
						</a>
					</div>'; 
				}
				$arr[$i]['srno']= $sr_no;
				$arr[$i]['service_name']= $row->ho_service_name;
				$arr[$i]['or_no']= ($row->hlf_is_free == 1) ? '<div>'.'Free'.'</div>':'<div>'.$row->lab_req_or.'</div>';
                $arr[$i]['date']= Carbon::parse($row->lab_reg_date)->format('M d, Y');
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
		// } catch (\Exception $e) {
		// 	return ($e->getMessage());
		// }
	}

    public function store(Request $request){
        $data = (object)$this->data;
        $data->slug = $this->slugs;
        $services = array();
        $servicesfees = array();
        $getEmployee = $this->getEmployee;
        $req_phys = "";

        foreach ($this->_holabrequest->getServices() as $val) {
            $services[$val->id]=$val->ho_service_name;
        } 
        
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $incre_no_req_id=$this->generatelabreqidNumber(" ");
        $current_years=date('Y');
        $data->lab_control_no= $current_years." - ".$incre_no_req_id;
		
		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoLabRequest::find($request->input('id'));
            $id = $data->hp_code;
            $req_phys = $this->_holabrequest->getEmployeeFirst($id);
        }
        if($request->isMethod('post')!=""){
            // dd($request->input());
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['lab_req_amount'] = str_replace(',', '',$request->input('lab_req_amount'));
            $this->data['lab_is_free']= ($request->input('lab_req_amount')== 0.00)?'1':'0';
			
            if($request->input('id')>0){
                // dd($this->data);
                $id = $request->input('id');
                if ($request->input('button') === 'submit') {
                    $this->data['lab_is_posted'] = 1;
                    $transaction_no = $this->_holabrequest->transaction($id);
                    if ($transaction_no) {
                        $this->data['top_transaction_no'] = $transaction_no['TOP'];
                        $this->data['trans_id'] = $transaction_no['TOP_id'];
                    }
                    // dd($transaction_no);
                }
                $this->_holabrequest->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
            }else{

                $this->data['lab_req_no']= $incre_no_req_id;
                $this->data['lab_control_no']= $current_years." - ".$incre_no_req_id;
                $this->data['lab_req_year']= $current_years;
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $request->id = $this->_holabrequest->addData($this->data);
                $id = $request->id;

                if ($request->input('button') === 'submit'){
                    $this->data['lab_is_posted'] = 1;
                    $this->data['id'] = $id;
                    $transaction_no = $this->_holabrequest->transaction($id);
                    if($transaction_no){
                        $this->data['top_transaction_no'] = $transaction_no['TOP'];
                        $this->data['trans_id'] = $transaction_no['TOP_id'];
                    }
                    $this->_holabrequest->updateData($id,$this->data);
                }
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
            }
            $data = $request->input();
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            $data['id'] = $logDetails['module_id'];
            $this->_holabrequest->addFees($data);
            HelSafRegistration::register($this->data['cit_id'],date('Y-m-d'),'is_lab');

            return json_encode(
                [
                    'ESTATUS'=>0,
                    'msg'=>$success_msg,
                    'data' => $data
                ]
            );
        }
        if (($request->input('cit_id'))) {
            $data->cit_id = (int)$request->input('cit_id');
            $data->patient = Citizen::find($request->input('cit_id'));
            $data->record_card = 1;
        }
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
        return view('laboratoryreq.create',compact('data','getEmployee','servicesfees','services','req_phys'));
        
    }
    public function generatehemalabnoNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_lab_requests')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->fam_ref_id;
            } else {
              $last_booking='0000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                   $last_booking='0000';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 2, 0, STR_PAD_LEFT);
        return $appliction_no;
    }

    public function getReqPhysDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_holabrequest->getEmployeeFirst($id);
        $req_phys = $data->fullname;
        
        $details=[
                    'req_phys' => $req_phys,
        ];
        echo json_encode($details);
    }

    public function generatelabreqidNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_lab_requests')->orderBy('id','desc');
        $current_years=date('Y');
        $last_book_yr = ($last_bookingq->first())?$last_bookingq->first()->lab_control_no:'';
        $last_book_yr = explode(' - ',$last_book_yr)[0];
        if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->lab_req_no;
            } else {
              $last_booking='0000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                   $last_booking='0000';
            }
            if ($last_book_yr != $current_years) {
                $last_booking='0000';
            }
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 4, 0, STR_PAD_LEFT);
        return $appliction_no;
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'lab_reg_date'=>'required',
                'cit_id'=>'required',
                'req_phys'=>'required',
                'fees'=>'required',
                'fees.*.service_id'=>'required',
            ],
            [   'lab_reg_date.required' => 'Date Is Required',
                'cit_id.required' => 'Patient Name Is Required',
                'req_phys.required' => 'Requesting Physician Is Required',
                'fees.required' => 'Atleast one Fees and Charges is required',
                'fees.*.service_id.required' => 'This field is required'
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = str_replace('.','-',$messages->keys()[0]);
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
    public function submit(Request $request, $id)
    {
        $transaction_no = $this->_holabrequest->transaction($id);
        if ($transaction_no) {
            $this->_holabrequest->updateData(
                $id,
                [
                    'lab_is_posted'=>1,
                    'top_transaction_no'=>$transaction_no['TOP'],
                    'trans_id'=>$transaction_no['TOP_id']
                ]
            );
        }
        $this->_holabrequest->updateData(
            $id,
            [
                'lab_is_posted'=>1,
            ]
        );

        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }

    public function removeFee($id) 
    {
        $this->_holabrequest->removeFee($id);
        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }

    public function print(Request $request, $id) // lab request print
    {
        $this->_HealthSafetySetupDataService = new HealthSafetySetupDataService();
        $services = $this->_HealthSafetySetupDataService->getLabServices();
        // dd($res);
        $data = HoLabRequest::find($id);
        // dd($data);
        PDF::SetTitle('Laboratory Request('.$id.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        
        $border = 0;
        $cell_height = 5;
        
        $doh_logo = public_path('assets/images/department_logos/DOH.png');
        $palayan_logo = public_path('assets/images/palayan-logo-gso.png');

        PDF::Image( $doh_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG');
        PDF::Image( $palayan_logo, $x = 135, $y = 10, $w = 22, $h = 0, $type = 'PNG');
        $watermark_img2 = public_path('/assets/images/cho-lab.png');
        // echo('<img src="'.$watermark_img2.'" alt="">');
        // dd('s');
        PDF::SetAlpha(0.1);
        PDF::Image( $watermark_img2, $x = 55, $y = 40, $w = '', $h = 100);
        PDF::SetAlpha(1);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0,4,'CITY HEALTH OFFICE',0,0,'C');
        PDF::ln(7);

        PDF::Cell(0,4,'LABORATORY REQUEST',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'TAX ORDER OF PAYMENT',0,0,'C');
        PDF::ln(10);

        
        PDF::ln(5);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Name:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(45, $cell_height, $data->patient->cit_last_name , $border, 0, 'C');
        PDF::Cell(45, $cell_height, $data->patient->cit_first_name, $border, 0, 'C');
        PDF::Cell(45, $cell_height, $data->patient->cit_middle_name, $border, 0, 'C');
        PDF::Cell(13, $cell_height, $data->patient->cit_suffix_name, $border, 0, 'C');
        PDF::ln();

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, '', $border, 0, 'C');
        PDF::Cell(45, $cell_height, 'Surname', 'T', 0, 'C');
        PDF::Cell(45, $cell_height, 'First Name', 'T', 0, 'C');
        PDF::Cell(45, $cell_height, 'Middle Name', 'T', 0, 'C');
        PDF::Cell(13, $cell_height, 'Suffix', 'T', 0, 'C');
        PDF::ln();

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Address:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, $cell_height, $data->patient->cit_full_address, 'B', 0, 'L');
        PDF::ln();

        $sex = ($data->patient->cit_gender == 0) ? 'Male' : 'Female';
        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, $cell_height, 'Sex:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(15, $cell_height, ($sex == '') ? '' : $sex , 'B', 0, 'C');
        PDF::Cell(10, $cell_height, '', $border, 0, 'L');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, $cell_height, 'Age:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(25, $cell_height, $data->patient->age_human, 'B', 0, 'C');
        PDF::Cell(5, $cell_height, '', $border, 0, 'L');

        $dob = date_create($data->patient->cit_date_of_birth);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(20, $cell_height, 'Date of Birth:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(33.5, $cell_height, date_format($dob,"F d, Y"), 'B', 0, 'C');
        // PDF::Cell(10, $cell_height, '', $border, 0, 'L');

        $lab_reg_date = date_create($data->lab_reg_date);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(28, $cell_height, 'Date:', $border, 0, 'R');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(33.5, $cell_height, date_format($lab_reg_date,"F d, Y") , 'B', 0, 'C');
        PDF::ln();

        $diagnosis = ($data->lab_req_diagnosis == '') ? '' : $data->lab_req_diagnosis;
        PDF::SetFont('helvetica','',9);
        PDF::Cell(20, $cell_height, 'Diagnosis:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, $cell_height, $diagnosis, 'B', 0, 'L');
        PDF::ln(10);
 
        $no = 1;
        $table = '<table>
                    <tr>
                        <td style="text-align:center"><b>No.</b></td>
                        <td><b>Fees & Charges</b></td>
                        <td><b>Service Name</b></td>
                        <td><b>Amount</b></td>
                    </tr>
                    <tr><td></td></tr>';
        foreach ($data->fees as $service) {
            $pay = ($service->hlf_fee != '0.00')? number_format($service->hlf_fee,2) : 'Free';
            $table .= ' <tr>
                            <td style="text-align:center">'.$no.'</td>
                            <td>'.$service->desc->ho_service_name.'</td>
                            <td>'.$service->desc->service_name.'</td>
                            <td>'.$pay.'</td>
                        </tr>';
                        $no++;
        }
        $total_pay = ($data->lab_req_amount != '0.00')? number_format($data->lab_req_amount,2) : 'Free';
        $table .= ' 
        <tr><td></td></tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>Total</b></td>
            <td>'.$total_pay.'</td>
        </tr>';
        $table .= '</table>';
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, $cell_height, $table, $border, 'L', 0, 1, '', '', true, '', true);

        PDF::ln(5);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(20, $cell_height, 'Others:', $border, 0, 'L');
        PDF::Cell(0, $cell_height, '', 'B', 0, 'L');
        PDF::ln();
        PDF::Cell(0, $cell_height, '', 'B', 0, 'L');
        PDF::ln(20);

        PDF::Cell(110, $cell_height, '', 0, 0, 'L');
        PDF::Cell(0, $cell_height, 'DR. SHEILA VISTORIA B. FLORES, MD', 0, 0, 'C');
        
        PDF::ln();
        PDF::Cell(110, $cell_height, '', 0, 0, 'L');
        PDF::Cell(0, $cell_height, 'Requesting Physician', 'T', 0, 'C');

        
        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode($data->top_transaction_no, 'QRCODE,H', 180, 40, 15, 15, $style, 'N');
        PDF::SetFont('helvetica','',7);
        PDF::ln(1);
        if ($data->top_transaction_no) {
            PDF::MultiCell(0, $cell_height, 'TOP No:'. $data->top_transaction_no, $border, 'L', 0, 0, 178, '', true, 0, true);
        }


        PDF::Output('laboratory_request'.$id.'.pdf');
    }

    public function labHeader($service, $data)
    {
        // dd($data);
        // new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        PDF::SetTitle($service.'('.$data['id'].')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        
        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG','', 'T', false, 300, '', false, false, 0, false, false, false);
        
        $watermark_img2 = public_path('/assets/images/cho-lab.png');
        // echo('<img src="'.$watermark_img2.'" alt="">');
        // dd('s');
        PDF::SetAlpha(0.1);
        PDF::Image( $watermark_img2, $x = 55, $y = 40, $w = '', $h = 100);
        PDF::SetAlpha(1);

        PDF::SetFont('helvetica','',9);
        PDF::ln();
        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln(7);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0,4,'CITY HEALTH OFFICE',0,0,'C');
        PDF::ln();

        PDF::Cell(0,4,'CLINICAL LABORATORY',0,0,'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, 4, 'Lab. No :', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(80, 4, $data['lab_no'] , 'B', 0, 'C');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, 4, 'Age:', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(20, 4, $data['age'] , 'B', 0, 'C');
        
        $sex = ($data['sex'] == 0 )? 'M' : 'F';
        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, 4, 'Sex:', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(10, 4, $sex, 'B', 0, 'C');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, 4, 'Date:', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, 4, Carbon::parse($data['date'])->format('F j, Y') , 'B', 0, 'C');
        PDF::ln();

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, 4, 'Name :', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(80, 4, $data['name'] , 'B', 0, 'C');
        
        PDF::SetFont('helvetica','',9);
        PDF::Cell(34, 4, 'Requesting Physician :', '0', 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, 4, $data['physician'] , 'B', 0, 'C');
        PDF::ln(10);
    }

    public function labFooter($data)
    {
        PDF::SetFont('helvetica','',8);
        PDF::Cell(20, 4, 'REMARKS:', '', 0, 'C');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(0, 4, $data['remarks'], 'B', 0, 'L');
        PDF::ln(20);


        PDF::Cell(70, 4, strtoupper($data['Medical Technologist']), 'B', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');        
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(20, 4, '', '', 0, 'C');
        PDF::Cell(90, 4, strtoupper($data['City Health Officer']), '', 0, 'C');
        PDF::ln();
        
        PDF::SetFont('helvetica','',8);
        PDF::Cell(70, 4, $data['Medical Technologist Position'], 'T', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C'); 
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::Cell(20, 4, '', '', 0, 'C');
        PDF::Cell(70, 4, $data['City Health Officer Position'], 'T', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');
    }
    public function hemaPrint(Request $request, $id)
    {
        $data = HematologyModel::find($id);
		
        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        // dd($lab_request);
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";

        $header = [
            'id' => $data->id,
            'lab_no' => $data->hema_lab_num,
            'age' =>  $lab_request->patient->age_human,
            'sex' => $lab_request->patient->cit_gender,
            'date' => $data->hema_date,
            'name' => strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name,
            'physician' => $lab_request->req_phys,
        ];
        $this->labHeader('Hematology', $header);

        $border = 1;
        $cell_height = 5;

        PDF::Cell(0,4,'HEMATOLOGY',0,0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(20, $cell_height, 'PARA', $border, 0, 'C');
        PDF::Cell(36, $cell_height, 'RESULT', $border, 0, 'C');
        PDF::Cell(36, $cell_height, 'REF. RANGE-'.strtoupper($data->category), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');
        
        PDF::Cell(20, $cell_height, 'PARA', $border, 0, 'C');
        PDF::Cell(36, $cell_height, 'RESULT', $border, 0, 'C');
        PDF::Cell(36, $cell_height, 'REF. RANGE-'.strtoupper($data->category), $border, 0, 'C');
        PDF::ln();

        
        PDF::Cell(20, $cell_height, 'Hgb', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_hgb, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'g / L', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('Hgb'), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'WBC', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_wbc, 'B', 0, 'C');
        PDF::MultiCell(18, $cell_height, "x 10<sup>9</sup> / L", 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(36, $cell_height, $data->get_range('WBC'), $border, 0, 'C');
        PDF::ln();
        
        PDF::Cell(20, $cell_height, 'RBC', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_rbc, 'B', 0, 'C');
        PDF::MultiCell(18, $cell_height, "x 10<sup>12</sup> / L", 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(36, $cell_height, $data->get_range('RBC'), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'Lymph %', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_lymph_pct, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, '%', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('Lymph %'), $border, 0, 'C');
        PDF::ln();

        PDF::Cell(20, $cell_height, 'Hct', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_hct, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, '%', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('Hct'), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'Mid %', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_mid_pct, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, '%', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('Mid %'), $border, 0, 'C');
        PDF::ln();

        PDF::Cell(20, $cell_height, 'MCV', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_mcv, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'fL', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('MCV'), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');
        
        PDF::Cell(20, $cell_height, 'Gran %', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_gran_pct, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, '%', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('Gran %'), $border, 0, 'C');
        PDF::ln();

        PDF::Cell(20, $cell_height, 'MCH', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_mch, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'pg', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('MCH'), $border, 0, 'C');

        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'PLT', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_plt, 'B', 0, 'C');
        PDF::MultiCell(18, $cell_height, "x 10<sup>9</sup> / L", 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(36, $cell_height, $data->get_range('PLT'), $border, 0, 'C');
        PDF::ln();

        PDF::Cell(20, $cell_height, 'MCHC', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_mchc, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'g / L', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('MCHC'), $border, 0, 'C');

        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'MPV', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_mpv, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'fL', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('MPV'), $border, 0, 'C');
        PDF::ln();

        PDF::Cell(20, $cell_height, 'RDW - CV', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_rdw_cv, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, '%', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('RDW-CV'), $border, 0, 'C');

        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, '', $border, 0, 'L');
        PDF::Cell(18, $cell_height, '', 'B', 0, 'C');
        PDF::MultiCell(18, $cell_height, "", 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(36, $cell_height, '', $border, 0, 'C');
        
        PDF::ln();

        PDF::Cell(20, $cell_height, 'RDW - SD', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_rdw_sd, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'fL', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('RDW-SD'), $border, 0, 'C');

        // PDF::Cell(20, $cell_height, 'Gran #', $border, 0, 'C');
        // PDF::Cell(18, $cell_height, $data->hema_gran_num, $border, 0, 'C');
        // PDF::MultiCell(18, $cell_height, "x 10<sup>9</sup> / L", $border, 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(36, $cell_height, $data->get_range('Gran #'), $border, 0, 'C');
        
        PDF::Cell(6, $cell_height, '', '', 0, 'C');

        PDF::Cell(20, $cell_height, 'BLOODTYPE', $border, 0, 'L');
        PDF::Cell(18, $cell_height, $data->hema_blood_type, 'B', 0, 'C');
        PDF::Cell(18, $cell_height, 'N/A', 'B', 0, 'L');
        PDF::Cell(36, $cell_height, $data->get_range('BLOOD TYPE'), $border, 0, 'C');

        // PDF::ln();

        
        
        // PDF::Cell(6, $cell_height, '', '', 0, 'C');

        
        // PDF::ln();

        
        
        // PDF::Cell(6, $cell_height, '', '', 0, 'C');

        

        
        
        // PDF::Cell(6, $cell_height, '', '', 0, 'C');

        

        // PDF::Cell(20, $cell_height, 'PDW', $border, 0, 'C');
        // PDF::Cell(18, $cell_height, $data->hema_pdw, $border, 0, 'C');
        // PDF::Cell(18, $cell_height, '', $border, 0, 'C');
        // PDF::Cell(36, $cell_height, $data->get_range('PDW'), $border, 0, 'C');
        // PDF::ln();

        

        // PDF::Cell(20, $cell_height, 'PCT', $border, 0, 'C');
        // PDF::Cell(18, $cell_height, $data->hema_pct, $border, 0, 'C');
        // PDF::Cell(18, $cell_height, '%', $border, 0, 'C');
        // PDF::Cell(36, $cell_height, $data->get_range('PCT'), $border, 0, 'C');
        // PDF::ln();

        
        // PDF::Cell(20, $cell_height, 'Lymph #', $border, 0, 'C');
        // PDF::Cell(18, $cell_height, $data->hema_lymph_num, $border, 0, 'C');
        // PDF::MultiCell(18, $cell_height, "x 10<sup>9</sup> / L", $border, 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(36, $cell_height, $data->get_range('Lymph #'), $border, 0, 'C');
        
        // PDF::Cell(6, $cell_height, '', '', 0, 'C');

        
        PDF::ln(10);

        $footer = [
            'remarks' => $data->hema_remarks,
            'City Health Officer' => ($data->h_officer) ? $data->h_officer->fullname : " ",
            'City Health Officer Position' => ($data->health_officer_position) ? $data->health_officer_position : " ",
            'Medical Technologist' => ($data->m_tech) ? $data->m_tech->fullname : " ",
            'Medical Technologist Position' => ($data->med_tech_position) ? $data->med_tech_position : " ",
        ];
        $this->labFooter($footer);
        // PDF::Output('laboratory_request'.$id.'.pdf');
        $filename ='laboratoryrequest'.$id.'.pdf';
        // $Cityhealthoffice = ($data->h_officer) ? $data->h_officer->fullname : " ";
        // $medicaltechnologiest = ($data->m_tech) ? $data->m_tech->fullname : " ";

        $arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_hematology_approved_health_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_hematology_approved_medical_technologist');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }

        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->h_officer->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->m_tech->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
          
        if($isSignVeified==1 && $signType==2 && $data->officer_is_approved==1){
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

        if($isSignCertified==1 && $signType==2 && $data->esign_is_approved ==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignCertified==1 && $signType==1 && $data->esign_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }

    public function pregnancyPrint(Request $request, $id)
    {

        $data = PregnancyTestModel::find($id);
        $date1 = $data->pt_expiry;
        $newDate5 = Carbon::createFromFormat('Y-m-d', $date1)->format('m/d/Y');

        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";

        $header = [
            'id' => $data->id,
            'lab_no' => $data->pt_lab_num,
            'age' =>  $lab_request->patient->age_human,
            'sex' => $lab_request->patient->cit_gender,
            'date' => $data->pt_date,
            'name' => strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name,
            'physician' => $lab_request->req_phys,
        ];
        $this->labHeader('Pregnancy Test', $header);

        $border = 1;
        $cell_height = 5;
        
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(0,4,'MISCELLANEOUS',0,0,'C');
        PDF::ln(10);
        
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(40,4,'TEST',$border,0,'C');
        PDF::Cell(30,4,'SPECIMEN',$border,0,'C');
        PDF::Cell(40,4,'BRAND/LOT#',$border,0,'C');
        PDF::Cell(40,4,'EXPIRY',$border,0,'C');
        PDF::Cell(40,4,'RESULT',$border,0,'C');
        PDF::ln();

        PDF::MultiCell(40, 20, 'PREGNANCY TEST', $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $data->pt_specimen, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(40, 20, $data->pt_brand_lot, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(40, 20, $newDate5 , $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        $result = ($data->pt_result == 1) ? 'POSITIVE' : 'NEGATIVE';
        PDF::MultiCell(40, 20, $result, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::ln(25);

        $footer = [
            'remarks' => $data->pt_remarks,
            'City Health Officer' => ($data->h_officer) ? $data->h_officer->fullname : " ",
            'City Health Officer Position' => ($data->health_officer_position) ? $data->health_officer_position : " ",
            'Medical Technologist' => ($data->m_tech) ? $data->m_tech->fullname : " ",
            'Medical Technologist Position' => ($data->med_tech_position) ? $data->med_tech_position : " ",
        ];
        $this->labFooter($footer);

          $filename ='PregnancyTest'.$id.'.pdf';
        $Cityhealthoffice = ($data->h_officer) ? $data->h_officer->fullname : " ";
        $medicaltechnologiest = ($data->m_tech) ? $data->m_tech->fullname : " ";

        $arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_pregnancy_test_approved_health_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_pregnancy_test_approved_medical_technologist');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }

        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->h_officer->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->m_tech->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
          
        if($isSignVeified==1 && $signType==2 && $data->officer_is_approved==1){
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

        if($isSignCertified==1 && $signType==2 && $data->esign_is_approved==1 ){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignCertified==1 && $signType==1 && $data->esign_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
        // PDF::Output('laboratory_request'.$id.'.pdf');
    }

    public function bloodSugarPrint(Request $request, $id)
    {

        $data = BloodSugarTest::find($id);
        $date1 = $data->bs_expiry;
        $newDate5 = Carbon::createFromFormat('Y-m-d', $date1)->format('m/d/Y');

        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";

        $header = [
            'id' => $data->id,
            'lab_no' => $data->bs_lab_num,
            'age' =>  $lab_request->patient->age_human,
            'sex' => $lab_request->patient->cit_gender,
            'date' => $data->bs_date,
            'name' => strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name,
            'physician' => $lab_request->req_phys,
        ];
        $this->labHeader('Blood Sugar Test', $header);

        $border = 1;
        $cell_height = 5;
        
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(0,4,'Blood Sugar Test',0,0,'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(40,4,'TEST',$border,0,'C');
        PDF::Cell(30,4,'SPECIMEN',$border,0,'C');
        PDF::Cell(30,4,'BRAND/LOT#',$border,0,'C');
        PDF::Cell(30,4,'EXPIRY',$border,0,'C');
        PDF::Cell(30,4,'NORMAL VALUE',$border,0,'C');
        PDF::Cell(30,4,'RESULT',$border,0,'C');
        PDF::ln();
        $bs_type = ($data->lab_request->ho_service_name) ? $data->lab_request->ho_service_name : "";
        $whole_blood = ($data->bs_specimen)? $data->bs_specimen : "";
        $brand = $data->bs_lot_no;
        $expiry_date = $data->bs_expiry;
        $normal_value = $data->bs_normal_value;
        $result = $data->bs_result;

        PDF::MultiCell(40, 20, $bs_type, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $whole_blood, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $brand, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $expiry_date , $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $normal_value, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, $result, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::ln(25);
        
        $footer = [
            'remarks' => $data->bs_remarks,
            'City Health Officer' => ($data->h_officer) ? $data->h_officer->fullname : " ",
            'City Health Officer Position' => ($data->health_officer_position) ? $data->health_officer_position : " ",
            'Medical Technologist' => ($data->m_tech) ? $data->m_tech->fullname : " ",
            'Medical Technologist Position' => ($data->med_tech_position) ? $data->med_tech_position : " ",
        ];
        $this->labFooter($footer);
        // PDF::Output('laboratory_request'.$id.'.pdf');

          $filename ='bloodSugar'.$id.'.pdf';
        $Cityhealthoffice = ($data->h_officer) ? $data->h_officer->fullname : " ";
        $medicaltechnologiest = ($data->m_tech) ? $data->m_tech->fullname : " ";

        $arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_bloodsugar_test_approved_health_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_bloodsugar_test_approved_medical_technologist');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }

        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->h_officer->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->m_tech->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
          
        if($isSignVeified==1 && $signType==2 && $data->officer_is_approved==1){
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

        if($isSignCertified==1 && $signType==2 && $data->esign_is_approved==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignCertified==1 && $signType==1 && $data->esign_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }

    public function gramStainingPrint(Request $request, $id)
    {
        $data = GramStainingTest::find($id);
        $date1 = $data->gs_date;
        $newDate5 = Carbon::createFromFormat('Y-m-d', $date1)->format('m/d/Y');

        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";

        $header = [
            'id' => $data->id,
            'lab_no' => $data->gs_lab_num,
            'age' =>  $lab_request->patient->age_human,
            'sex' => $lab_request->patient->cit_gender,
            'date' => $data->gs_date,
            'name' => strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name,
            'physician' => $lab_request->req_phys,
        ];
        $this->labHeader('Gram Staining Test', $header);

        $border = 1;
        $cell_height = 5;
        
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(0,4,'GRAM STAIN',0,0,'C');
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(40,4,'ORGANISM',$border,0,'C');
        PDF::Cell(40,4,'SPECIMEN',$border,0,'C');
        PDF::Cell(110,4,'RESULTS',$border,0,'C');
        PDF::ln();

        $organism = $data->gs_organism;
        $specimen = $data->gs_specimen;
        $result = $data->gs_result;
        $findings = $data->gs_findings;
        $recommendation = $data->gs_recommendation;

        PDF::MultiCell(40, 20, $organism, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(40, 20, $specimen, $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(80, 20, nl2br($result), $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::MultiCell(30, 20, nl2br($findings) , $border, 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');
        PDF::ln(25);
        
        PDF::SetFont('helvetica','',8);
        PDF::Cell(25, 4, 'Recommendation:', '', 0, 'C');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(0, 4, $recommendation, 'B', 0, 'L');
        PDF::ln(20);

        // $footer = [
        //     'remarks' => $data->bs_remarks,
        //     'City Health Officer' => ($data->h_officer) ? $data->h_officer->fullname : " ",
        //     'City Health Officer Position' => ($data->health_officer_position) ? $data->health_officer_position : " ",
        //     'Medical Technologist' => ($data->m_tech) ? $data->m_tech->fullname : " ",
        //     'Medical Technologist Position' => ($data->med_tech_position) ? $data->med_tech_position : " ",
        // ];
        // $this->labFooter($footer);
        
        PDF::Cell(70, 4, strtoupper(($data->m_tech) ? $data->m_tech->fullname : " "), 'B', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');        
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(20, 4, '', '', 0, 'C');
        PDF::Cell(90, 4, strtoupper(($data->h_officer) ? $data->h_officer->fullname : " "), '', 0, 'C');
        PDF::ln();
        
        PDF::SetFont('helvetica','',8);
        PDF::Cell(70, 4, ($data->med_tech_position) ? $data->med_tech_position : " ", 'T', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C'); 
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');
        PDF::Cell(20, 4, '', '', 0, 'C');
        PDF::Cell(70, 4, ($data->health_officer_position) ? $data->health_officer_position : " ", 'T', 0, 'C');
        PDF::Cell(10, 4, '', '', 0, 'C');
        // PDF::Output('laboratory_request'.$id.'.pdf');

          $filename ='GramStainingTest'.$id.'.pdf';
        $Cityhealthoffice = ($data->h_officer) ? $data->h_officer->fullname : " ";
        $medicaltechnologiest = ($data->m_tech) ? $data->m_tech->fullname : " ";

        $arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_gram_staining_test_approved_health_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_gram_staining_test_approved_medical_technologist');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }

        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->h_officer->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->m_tech->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
          
        if($isSignVeified==1 && $signType==2 && $data->officer_is_approved==1){
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

        if($isSignCertified==1 && $signType==2 && $data->esign_is_approved==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignCertified==1 && $signType==1 && $data->esign_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }

	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoLabRequest::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/laboratory/';
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
                $this->_holabrequest->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/laboratory').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoLabRequest::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/laboratory/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_holabrequest->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
    
}
