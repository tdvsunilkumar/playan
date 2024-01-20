<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrLoanApplication;
use App\Models\HR\HrLoanLedger;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
class HrLoanApplicationController extends Controller
{
     public $data = [];
     public $postdata = [];
    
     public function __construct(){
		$this->_hrloanapplication= new HrLoanApplication(); 
		$this->_HrLoanLedger= new HrLoanLedger(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrla_employeesid'=>'','hrla_loan_date'=>'','hrla_department_id'=>'','hrla_division_id'=>'','hrla_application_no'=>'','hrla_loan_status'=>'','hrla_loan_description'=>'','hrla_id'=>'','hrla_loan_amount'=>'','hrla_interest_percentage'=>'','hrla_interest_amount'=>'','hrlc_id'=>'','hrla_amount_disbursed'=>'','hrla_installment_amount'=>'','hrla_effectivity_date'=>'','hrla_requested_by'=>'','hrla_requested_date'=>'','hrla_approved_by'=>'','hrla_approved_date'=>'');  
        $this->slugs = 'formula';
		$this->employee = array(""=>"");
		$this->department = array(""=>"Please Select");
		$this->division = array(""=>"Please Select");
		$this->hrloantype = array(""=>"Please Select");
		$this->hrloancycle = array(""=>"Please Select");
		$this->hrloanstatus = array("1"=>"Active","0"=>"InActive");
		foreach ($this->_hrloanapplication->getEmployee() as $val) {
                $this->employee[$val->id]=$val->fullname;
        }
		foreach ($this->_hrloanapplication->getDepartment() as $val) {
                $this->department[$val->id]=$val->name;
        }
		foreach ($this->_hrloanapplication->getDivision() as $val) {
                $this->division[$val->id]=$val->name;
        }
		foreach ($this->_hrloanapplication->getHrLoanType() as $val) {
                $this->hrloantype[$val->id]=$val->hrlt_description;
        }
		foreach ($this->_hrloanapplication->getHrLoanCycle() as $val) {
                $this->hrloancycle[$val->hrlc_month]=$val->hrlc_month;
        }
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('HR.loanapplication.index');
    }


    public function getList(Request $request){
		$arrEmployee = $this->employee;
		$arrHrloantype = $this->hrloantype;
		$arrhrloanstatus = $this->hrloanstatus;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrloanapplication->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->hrla_loan_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrla_employeesid']=$arrEmployee[$row->hrla_employeesid];
			$arr[$i]['hrla_application_no']=$row->hrla_application_no;
			$arr[$i]['hrla_id']=$arrHrloantype[$row->hrla_id];
			$arr[$i]['hrla_loan_description']=$row->hrla_loan_description;
			$arr[$i]['hrla_loan_status']=$arrhrloanstatus[$row->hrla_loan_status];
			$arr[$i]['hrla_loan']=$row->hrla_loan_status;
            //$arr[$i]['hrla_loan_status']=($row->hrla_loan_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/loan-application/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Loan Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    </div>';
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
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('hrla_loan_status' => $is_activeinactive);
        $this->_hrloanapplication->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        $arrEmployee = $this->employee;
		$arrDepartment = $this->department;
		$arrDivision = $this->division;
		$arrHrloantype = $this->hrloantype;
		$arrHrloanCycle = $this->hrloancycle;
		$arrhrloanstatus = $this->hrloanstatus;
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
		$arrPaymentDetails = array();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrLoanApplication::find($request->input('id'));
			$arrPaymentDetails = $this->_HrLoanLedger->GetPaymentdetails($request->id);

        }
        //echo "<pre>"; print_r($data); exit;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrloanapplication->updateData($request->input('id'),$this->data);
                if ($request->submit_type === '1') {
                    $cycle = $request->input('hrlc_id') +1;
                    $i=0;
                    while($i < $cycle){
                        // dd($request->input('hrll_installment_amount')[$i]);
                        $z=($i+1)*30;
                        $loan_type = HrLoanApplication::find($request->id)->loan_type;
                        $sl_id = $loan_type ? $loan_type->sl_id : 0;
                        $gl_id = $loan_type ? $loan_type->gl_id : 0;
                        HrLoanLedger::create(
                            [
                                'hrll_employeesid' => $request->input('hrla_employeesid'),
                                'hrll_department_id' => $request->input('hrla_department_id'),
                                'hrll_division_id' => $request->input('hrla_division_id'),
                                'hrla_id' => $request->id,
                                'sl_id' => $sl_id,
                                'gl_id' => $gl_id,
                                'hrll_cycle' => $request->input('hrlc_id'),
                                'hrll_balance' => $request->input('hrll_balance')[$i],
                                'hrll_payment_date' => $request->input('hrll_payment_date')[$i],
                                'hrll_installment_amount' => $request->input('hrll_installment_amount')[$i],
                            ]
                        );
                        
                        $i++;
                    }
                    $this->data['hrla_approved_by']=\Auth::user()->hr_employee->id;
                    $this->data['hrla_approved_date'] = date('Y-m-d H:i:s');
                }
                $success_msg = 'Loan Application dated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Loan Application '".$this->data['hrla_loan_description']."'"; 
            }else{
                $curr_years = date('Y');
                $this->data['hrla_application_no']=$this->generateApplictionNumberyears($curr_years."-");
                
                $this->data['hrla_requested_by']=\Auth::user()->id;
                $this->data['hrla_requested_date'] = date('Y-m-d H:i:s');
                $this->data['hrla_approved_date'] = date('Y-m-d H:i:s');
                
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hrla_loan_status'] = 1;
                $request->id = $this->_hrloanapplication->addData($this->data);

                $success_msg = 'Loan Application added successfully.';
                
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Loan Application '".$this->data['hrla_loan_description']."'"; 
            }
                    
                    
		
            $logDetails['module_id'] = $request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrloanapplication.index')->with('success', __($success_msg));
    	}
        return view('HR.loanapplication.create',compact('data','arrEmployee','arrDepartment','arrDivision','arrHrloantype','arrHrloanCycle','arrhrloanstatus','arrPaymentDetails'));
	}
    public function generateApplictionNumberyears($company_code) {
        $prefix = $company_code;
		$curr_years = date('Y');
        $last_bookingq=DB::table('hr_loan_applications')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->hrla_application_no;
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
        $appliction_no = $prefix . str_pad($counter, 5, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
	public function getDesignation($employee_id){
    	try{
			$designation = $this->_hrloanapplication->getDesignation($employee_id);
			return response()->json(['status' => 200, 'data' => $designation]);
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                //'hrla_loan_description'=>'required|unique:hr_loan_applications,hrla_loan_description,'.(int)$request->input('id')
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
	public function loneledegerhtml(Request $request){
		$months = $request->input('months');
		$total_amout = $request->input('total_amout');
		$installment_amount = $request->input('installment_amount');
		$interest_per_month = $request->input('interest_per_month');
		$effectivity_date = $request->input('effectivity_date');
		$html ="";
		$html .='<div class="row removecheckdata" style="padding: 5px 0px;">
					 <div class="col-lg-1 col-md-1 col-sm-1">
						0
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1">
						<input class="form-control" readonly="readonly" id="hrll_balance" name="hrll_balance[]" type="text" value="'.$total_amout.'">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_payment_date" name="hrll_payment_date[]" type=" " value="">
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_installment_amount" name="hrll_installment_amount[]" type="number" value="'.$installment_amount.'">
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_paid_amount" name="hrll_paid_amount[]" type="number" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_paid_date" name="hrll_paid_date[]" type="date" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_payroll_ref_no" name="hrll_payroll_ref_no[]" type="text" value="">
					</div>
				</div>';
		$i=1;
		while($i <= $months){
            $installment = $installment_amount - $interest_per_month;
			$balance = $total_amout - $installment * $i;
            $pay_date = Carbon::parse($effectivity_date)->addMonths($i - 1)->endOfMonth();
            $payment_date = $pay_date->toDateString();
			 $html .='<div class="row removecheckdata" style="padding: 5px 0px;">
					 <div class="col-lg-1 col-md-1 col-sm-1">
						'.$i.'
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1">
						<input class="form-control" readonly="readonly" id="hrll_balance" name="hrll_balance[]" type="text" value="'.$balance.'">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_payment_date" name="hrll_payment_date[]" type="date" value="'.$payment_date.'">
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_installment_amount" name="hrll_installment_amount[]" type="number" value="'.$installment_amount.'">
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_paid_amount" name="hrll_paid_amount[]" type="number" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_paid_date" name="hrll_paid_date[]" type="date" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input class="form-control" readonly="readonly" id="hrll_payroll_ref_no" name="hrll_payroll_ref_no[]" type="text" value="">
					</div>
				</div>';
		 $i++;
        }
        echo $html; 
	}
	
}
