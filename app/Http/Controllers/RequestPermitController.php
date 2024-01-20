<?php

namespace App\Http\Controllers;
use App\Models\RequestPermit;
use App\Models\CommonModelmaster;
use App\Models\SocialWelfare\Citizen;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use DB;
use Carbon\CarbonPeriod;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use PDF;
use App\Models\HelSafRegistration;
use App\Models\HealthSafetySetupDataService;
class RequestPermitController extends Controller
{
    
    public $data = [];
    public $getcitizens = array(""=>"Select Citizen");
    public $getEmployee  = array(""=>"Please Select");
    private $slugs;
    public $gend = ['0'=>"Male",'1'=>"Female"]; 

    public function __construct(){
        $this->_requestpermit = new RequestPermit();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'requestor_id'=>'',
            'brgy_id'=>'',
            'payor_id'=>'',
            'request_date'=>Carbon::now(),
            'control_no'=>'',
            'request_amount'=>'',
            'top_transaction_no'=>'',
            'trans_id'=>'',
            'cashierd_id'=>'',
            'cashier_id'=>0,
            'or_no'=>'',
            'or_date'=>'',
			'or_amount'=>'',
			'is_free'=>'',
			
        );
        $this->slugs = 'civil-registrar/request-permit';
        foreach ($this->_requestpermit->getEmployee() as $val) {
            $this->getEmployee[$val->id] = $val->fullname;
        }
		foreach ($this->_requestpermit->getCitizen() as $val) {
             $this->getcitizens[$val->id]=$val->cit_fullname;
        }
    
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('requestpermit.index');
    }
    
    public function getList(Request $request){
        $data=$this->_requestpermit->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;      
        foreach ($data['data'] as $row){  
            $j=$j+1;
            $status =($row->status == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn btn-sm btn-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            
			$arr[$i]['no'] = $j;
			$arr[$i]['control_no']=$row->control_no;
			$arr[$i]['requestor']=$row->cit_fullname;
			$arr[$i]['barangay']=$row->brgy_name;
			if($row->is_free == 1){
                $arr[$i]['top_no'] = ($row->status == 1) ? '<div>'.''.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'Free'.'</div>';
            }else{
            $arr[$i]['top_no']= ($row->status == 1) ? '<div>'.$row->top_transaction_no.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->top_transaction_no.'</div>';
            }
			$arr[$i]['or_no']= ($row->status == 1) ? '<div>'.$row->or_no.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$row->or_no.'</div>';
			$amount = ($row->status == 1) ? '<div>'.number_format($row->request_amount, 2).'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.number_format($row->request_amount, 2).'</div>';
			if($row->request_amount == 0){
                $arr[$i]['request_amount']= ($row->status == 1) ? '<div>'.'Free'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'Free'.'</div>';
            }else{
                $arr[$i]['request_amount']=($row->status == 1) ? '<div>'.$amount.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.$amount.'</div>';
            }
			if($row->or_no == null && $row->or_date == null){
                $arr[$i]['payment_status']= ($row->status == 1) ? '<div>'.' '.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.' '.'</div>';
            }else if($row->or_no != null && $row->status == 1){
                $arr[$i]['payment_status']= ($row->status == 1) ? '<div class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">'.'PAID'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'PAID'.'</div>';
            }else if($row->or_no != null && $row->status == 0){
                $arr[$i]['payment_status']= ($row->status == 1) ? '<div span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">'.'OR CANCELLED'.'</div>':'<div style="text-decoration: line-through; color: red; text-decoration-color: red;">'.'OR CANCELLED'.'</div>';
            }
			$arr[$i]['request_date']=Carbon::parse($row->request_date)->format('M d, Y');
            $arr[$i]['save_status']=($row->is_posted==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
            $arr[$i]['is_status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
             
            $arr[$i]['action']='
                <div class="action-btn btn-sm btn-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/OccupationalPermit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Request Permit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    <div class="action-btn btn-sm btn-info ms-2">
                    <a href="'.route('requestpermit.print',['id'=>$row->id]).'" class="mx-3 btn btn-sm  align-items-center" target="_blank" data-size="lg" data-bs-toggle="tooltip" title="Print" >
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
            "recordsTotal"    => intval($totalRecords),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function laboratoryActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('status' => $is_activeinactive);
            $this->_requestpermit->updateActiveInactive($id,$data);

            // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Lab Request ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }

    public function getListSpecific(Request $request,$cit_id){
		// try {
			$this->is_permitted($this->slugs, 'read');
			$data = $this->_requestpermit->getIssueByPatient($request,$cit_id);
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
		$getcitizens = $this->getcitizens;
        foreach ($this->_requestpermit->getServices() as $val) {
            $services[$val->id]=$val->ho_service_name;
        } 
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $curr_years = date('Y');
		$appali_no=$this->generateApplictionNumber($curr_years."-");
        $data->control_no= $appali_no;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RequestPermit::find($request->input('id'));
        }
        if($request->isMethod('post')!=""){
            // dd($request->input());
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['request_amount'] = str_replace(',', '',$request->input('request_amount'));
            if($request->input('request_amount')== 0){
				$this->data['is_free'] = 1;
			}
            if($request->input('id')>0){
                $id = $request->input('id');
                if ($request->input('button') === 'submit') {
                    $this->data['is_posted'] = 1;
                    $transaction_no = $this->_requestpermit->transaction($id);
                    if ($transaction_no){
                        $this->data['top_transaction_no'] = $transaction_no['TOP'];
                        $this->data['trans_id'] = $transaction_no['TOP_id'];
                    }
                    //dd($transaction_no);
                }
                $this->_requestpermit->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
				 //dd($this->data);
            }else{
                $this->data['control_no']= $appali_no;
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['status'] = 1;
                $request->id = $this->_requestpermit->addData($this->data);
				$id = $request->id;
				if($request->input('button') === 'submit'){
                    $this->data['is_posted'] = 1;
                    $this->data['id'] = $id;
					$transaction_no = $this->_requestpermit->transaction($id);
                    if($transaction_no){
                        $this->data['top_transaction_no'] = $transaction_no['TOP'];
                        $this->data['trans_id'] = $transaction_no['TOP_id'];
                    }
                    $this->_requestpermit->updateData($id,$this->data);
                }
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
            }
            $data = $request->input();
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            $data['id'] = $logDetails['module_id'];
            $this->_requestpermit->addFees($data);
            HelSafRegistration::register($this->data['requestor_id'],date('Y-m-d'),'is_lab');

            return json_encode(
                [
                    'ESTATUS'=>0,
                    'msg'=>$success_msg,
                    'data' => $data
                ]
            );
        }
        if (($request->input('requestor_id'))) {
            $data->requestor_id = (int)$request->input('requestor_id');
            $data->patient = Citizen::find($request->input('requestor_id'));
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
       
        return view('requestpermit.create',compact('data','getEmployee','servicesfees','services','getcitizens'));
        
    }

    public function generateApplictionNumber($company_code) {
        $prefix = $company_code;
		$curr_years = date('Y');
        $last_bookingq=DB::table('ho_request_permit')
        ->where('control_no', 'like', '%' . $curr_years . '%')
        ->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->control_no;
            } else {
              $last_booking=$curr_years."-";
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                $last_booking=$curr_years."-";
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 6, 0, STR_PAD_LEFT);
        return $appliction_no;
    }

    public function formValidation(Request $request){

        $validator = \Validator::make(
            $request->all(), [
                'request_date'=>'required',
                'requestor_id'=>'required',
                //'is_free'=>'required',
                'fees.*.service_id'=>'distinct'
            ],
            [  
				'request_date.required'=> 'Date Is Required',
                'requestor_id.required'=> 'Requestor Information Required',
                //'is_free.required' => 'Atleast one Fees and Charges is required',
                'fees.*.service_id.distinct'=> 'Must be Unique'
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
        $transaction_no = $this->_requestpermit->transaction($id);
        if ($transaction_no) {
            $this->_requestpermit->updateData(
                $id,
                [
                    'is_posted'=>1,
                    'top_transaction_no'=>$transaction_no['TOP'],
                    'trans_id'=>$transaction_no['TOP_id']
                ]
            );
        }
        $this->_requestpermit->updateData(
            $id,
            [
                'is_posted'=>1,
            ]
        );

        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }
    public function removeFee($id) 
    {
        $this->_requestpermit->removeFee($id);
        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }
    public function print(Request $request, $id)
    {

        $this->_HealthSafetySetupDataService = new HealthSafetySetupDataService();
        $services = $this->_HealthSafetySetupDataService->getLabServices();
        // dd($res);
        $data = RequestPermit::find($id);

        PDF::SetTitle('Request Permit('.$id.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        
        $border = 0;
        $cell_height = 5;
        
        $doh_logo = public_path('assets/images/department_logos/DOH.png');
        $palayan_logo = public_path('assets/images/issuanceLogo.png');

        // PDF::Image( $doh_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG');
        PDF::Image( $palayan_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG');
        
        PDF::SetFont('helvetica','',9);
        PDF::Cell(0,4,'Republic of the Philippines',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'Province of Nueva Ecija',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'City of Palayan',0,0,'C');
        PDF::ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0,4,'CIVIL REGISTRAR OFFICE',0,0,'C');
        // PDF::ln(7);

        // PDF::Cell(0,4,'LABORATORY REQUEST',0,0,'C');
        PDF::ln();
        PDF::Cell(0,4,'TAX ORDER OF PAYMENT',0,0,'C');
        PDF::ln(10);

        
        PDF::ln(5);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Name:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(45, $cell_height, $data->requestor->cit_last_name , $border, 0, 'C');
        PDF::Cell(45, $cell_height, $data->requestor->cit_first_name, $border, 0, 'C');
        PDF::Cell(45, $cell_height, $data->requestor->cit_middle_name, $border, 0, 'C');
        PDF::Cell(13, $cell_height, $data->requestor->cit_suffix_name, $border, 0, 'C');
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
        PDF::Cell(0, $cell_height, $data->requestor->cit_full_address, 'B', 0, 'L');
        PDF::ln(7);

        // $sex = ($data->requestor->cit_gender == 0) ? 'Male' : 'Female';
        // PDF::SetFont('helvetica','',9);
        // PDF::Cell(10, $cell_height, 'Sex:', $border, 0, 'L');
        // PDF::SetFont('helvetica','B',9);
        // PDF::Cell(15, $cell_height, ($sex == '') ? '' : $sex , 'B', 0, 'C');
        // PDF::Cell(10, $cell_height, '', $border, 0, 'L');

        // PDF::SetFont('helvetica','',9);
        // PDF::Cell(10, $cell_height, 'Age:', $border, 0, 'L');
        // PDF::SetFont('helvetica','B',9);
        // PDF::Cell(25, $cell_height, $data->requestor->age_human, 'B', 0, 'C');
        // PDF::Cell(5, $cell_height, '', $border, 0, 'L');

        // $dob = date_create($data->requestor->cit_date_of_birth);
        // PDF::SetFont('helvetica','',9);
        // PDF::Cell(20, $cell_height, 'Date of Birth:', $border, 0, 'L');
        // PDF::SetFont('helvetica','B',9);
        // PDF::Cell(33.5, $cell_height, date_format($dob,"F d, Y"), 'B', 0, 'C');
        // PDF::Cell(10, $cell_height, '', $border, 0, 'L');

        $request_date = date_create($data->request_date);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(10, $cell_height, 'Date:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(33.5, $cell_height, date_format($request_date,"F d, Y") , 'B', 0, 'C');
        PDF::ln();

        // $diagnosis = ($data->lab_req_diagnosis == '') ? '' : $data->lab_req_diagnosis;
        // PDF::SetFont('helvetica','',9);
        // PDF::Cell(20, $cell_height, 'Diagnosis:', $border, 0, 'L');
        // PDF::SetFont('helvetica','B',9);
        // PDF::Cell(0, $cell_height, $diagnosis, 'B', 0, 'L');
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
            $pay = ($service->permit_fee != '0.00')? number_format($service->permit_fee,2) : 'Free';
            $table .= ' <tr>
                            <td style="text-align:center">'.$no.'</td>
                            <td>'.$service->desc->ho_service_name.'</td>
                            <td>'.$service->desc->service_name.'</td>
                            <td>'.$pay.'</td>
                        </tr>';
                        $no++;
        }
        $total_pay = ($data->request_amount != '0.00')? number_format($data->request_amount,2) : 'Free';
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
        // PDF::SetFont('helvetica','B',9);
        // PDF::Cell(20, $cell_height, 'Others:', $border, 0, 'L');
        // PDF::Cell(0, $cell_height, '', 'B', 0, 'L');
        // PDF::ln();
        // PDF::Cell(0, $cell_height, '', 'B', 0, 'L');
        // PDF::ln(20);

        // PDF::Cell(110, $cell_height, '', 0, 0, 'L');
        // PDF::Cell(0, $cell_height, 'DR. SHEILA VISTORIA B. FLORES, MD', 0, 0, 'C');
        
        // PDF::ln();
        // PDF::Cell(110, $cell_height, '', 0, 0, 'L');
        // PDF::Cell(0, $cell_height, 'Requesting Physician', 'T', 0, 'C');

        
        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode($data->top_transaction_no, 'QRCODE,H', 180, 35, 15, 15, $style, 'N');
        PDF::SetFont('helvetica','',7);
        PDF::ln(1);
        if ($data->top_transaction_no) {
            PDF::MultiCell(0, $cell_height, 'TOP No:'. $data->top_transaction_no, $border, 'L', 0, 0, 178, '', true, 0, true);
        }


        PDF::Output('laboratory_request'.$id.'.pdf');
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = RequestPermit::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/requestpermit/';
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
                $this->_requestpermit->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/requestpermit').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = RequestPermit::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/requestpermit/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_requestpermit->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
