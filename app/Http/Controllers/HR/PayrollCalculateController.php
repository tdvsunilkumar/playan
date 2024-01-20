<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\HrAppointment;
use App\Models\HR\Payroll;
use App\Models\PayrollBreakdown;
use App\Models\HR\HrBiometricsRecord;
use App\Models\HR\HrMissedLog;
use App\Models\HR\HrOfficialWork;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
class PayrollCalculateController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;
    public $department = array(""=>"Please Select");
    public $employee_appointment_status = array(""=>"Please Select");
    private $carbon;
    public function __construct( Carbon $carbon){
        $this->carbon = $carbon;
		$this->_hrAppointment= new HrAppointment(); 
		$this->_Payroll= new Payroll(); 
        foreach ($this->_hrAppointment->getDepartment() as $val) {
            $this->department[$val->id]=$val->name;
        } 
        foreach ($this->_hrAppointment->getAptStatus() as $val) {
            $this->employee_appointment_status[$val->id]=$val->hras_description;
        } 
        $this->data = array(
            'id'=>'',
            'hrcp_id'=>'',
            'hrpr_payroll_no'=>$this->_Payroll->next_number,
            'hrpr_appointment_type'=>'',
            'hrpr_employees_id'=>'',
            'hrpr_department_id'=>'',
            'hrpr_division_id'=>'',
            'hrpr_monthly_rate'=>'',
            'hrpr_aut'=>'',
            'hrpr_reg_ot'=>'',
            'hrpr_rd_ot'=>'',
            'hrpr_holiday_ot'=>'',
            'hrpr_total_salary'=>'',
            'hrpr_earnings'=>'',
            'hrpr_deductions'=>'',
            'hrpr_net_salary'=>'',
            'hrpr_is_processed'=>'',
            'hrpr_processed_date'=>'',
        );  
        $this->slugs = 'hr-payroll-calculate';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('HR.PayrollCalculate.index');
    }
    public function employee(Request $request)
    {   
        $this->is_permitted('my-payroll', 'read');
        return view('HR.PayrollCalculate.employee');
    }
    public function view(Request $request, $id)
    {   
        // $this->is_permitted('my-payroll', 'read');
        $data = $this->_Payroll->find($id);
        $deductions = (array)json_decode($data->hrpr_deduction);
        $deductions_total = array_sum(array_column($deductions,'hriad_deduct'));
        $other_deduct = $data->hrpr_deductions - $deductions_total;
        return view('HR.PayrollCalculate.view', compact('data','other_deduct'));
    }
    public function viewByGL($payroll_no,$gl_id)
    {
        $data = PayrollBreakdown::where([
            'payroll_no' => $payroll_no,
            'gl_id' => $gl_id
        ])->get();
        return view('HR.PayrollCalculate.viewGL', compact('data','payroll_no'));
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_Payroll->find($request->input('id'));
        }
        if($request->isMethod('post')!=""){
                $salary_breakdown = [];
                $cutoff = '';
                $department = '';
                $employees = Payroll::getEmployee('', $request->hrpr_division_id, $request->hrpr_appointment_type,$request->hrcp_id)['data'];
                if ($request->id) {
                    $data = $this->_Payroll->find($request->id);
                    $employees = $data->processed_emp;
                }
                foreach ($employees as $key => $employee) {
                    if ($request->id) {
                        $employee = $employee->appointment;
                    }
                    $timekeep = Payroll::getEmployeePayroll($employee->hr_emp_id,$request->hrcp_id);
                    if ($timekeep) {
                        $monthly_rate = $timekeep->hrpr_monthly_rate;
                        $monthly_rate = (float)str_replace(',','',$monthly_rate);
                        $aut_compute = str_replace(',','',$timekeep->hrpr_aut_compute);
                        $hrpr_total_salary = $monthly_rate + $timekeep->hrpr_reg_ot + $timekeep->hrpr_rd_ot + $timekeep->hrpr_holiday_ot - $aut_compute;
                        $hrpr_net_salary = $hrpr_total_salary + $timekeep->hrpr_earnings - $timekeep->hrpr_deductions;
                        $payroll = Payroll::updateOrCreate(
                            [
                                'hrcp_id' => $request->hrcp_id,
                                'hrpr_payroll_no' => $request->hrpr_payroll_no,
                                'hrpr_employees_id' => $employee->hr_emp_id,
                                'hrpr_department_id' => $request->hrpr_department_id,
                                'hrpr_division_id' => $request->hrpr_division_id,
                            ],
                            [
                                'hrpr_appointment_type' => $request->hrpr_appointment_type,
                                'hrpr_monthly_rate' => $monthly_rate,
                                'hrpr_aut' => $aut_compute,
                                'hrpr_reg_ot' => $timekeep->hrpr_reg_ot,
                                'hrpr_rd_ot' => $timekeep->hrpr_rd_ot,
                                'hrpr_holiday_ot' => $timekeep->hrpr_holiday_ot,
                                'hrpr_total_salary' => $hrpr_total_salary,
                                'hrpr_earnings' => $timekeep->hrpr_earnings,
                                'hrpr_deductions' => $timekeep->hrpr_deductions,
                                'ewt_id' => isset($timekeep->ewt_id) ? $timekeep->ewt_id : null,
                                'hrpr_net_salary' => $hrpr_net_salary,
                                'hrpr_monthly_tax' => isset($timekeep->deduction['tax']) ? $timekeep->deduction['tax']['hriad_deduct'] : 0,
                                'hrpr_income' => ($timekeep->income) ? $timekeep->income->toJSON():'',
                                'hrpr_deduction' => $timekeep->deduction->toJSON(),
                                'hrpr_ots' => isset($timekeep->ot) ? $timekeep->ot->toJSON() : '',
                                'hrpr_gov_share' => isset($timekeep->gov_share) ? collect($timekeep->gov_share)->toJSON() : ''
                            ]
                        );
                        $cutoff = $payroll->cutoff;
                        if ($request->btn == 1) {      
                            $emp_breakdown = [];  
                            foreach ($timekeep->income as $value) {
                                // dd($value);
                                $salary_breakdown['income'.$value->hridt_id]['gl_id'] = $value->gl_id;
                                $salary_breakdown['income'.$value->hridt_id]['sl_id'] = $value->sl_id;
                                $emp_breakdown['income'.$value->hridt_id]['sl_id'] = $value->sl_id_debit;
                                $emp_breakdown['income'.$value->hridt_id]['gl_id'] = $value->gl_id_debit;
                                $emp_breakdown['income'.$value->hridt_id]['amt'] = $value->hriad_deduct;
                                if (isset($salary_breakdown['income'.$value->hridt_id]['amt'])) {
                                    $salary_breakdown['income'.$value->hridt_id]['amt'] += $value->hriad_deduct;
                                }else {
                                    $salary_breakdown['income'.$value->hridt_id]['amt'] = $value->hriad_deduct;
                                }
                            }
                            foreach ($timekeep->deduction as $key => $value) {
                                $salary_breakdown['deduction'.$key]['sl_id'] = $value['sl_id'];
                                $salary_breakdown['deduction'.$key]['gl_id'] = $value['gl_id'];
                                $emp_breakdown['deduction'.$key]['sl_id'] = $value['sl_id_debit'];
                                $emp_breakdown['deduction'.$key]['gl_id'] = $value['gl_id_debit'];
                                $emp_breakdown['deduction'.$key]['amt'] = $value['hriad_deduct'];
                                if (isset($salary_breakdown['deduction'.$key]['amt'])) {
                                    $salary_breakdown['deduction'.$key]['amt'] += $value['hriad_deduct'];
                                }else {
                                    $salary_breakdown['deduction'.$key]['amt'] = $value['hriad_deduct'];
                                }
                            }
                            foreach ($timekeep->gov_share as $key => $value) {
                                $salary_breakdown['gov_share'.$key]['sl_id'] = $value['sl_id'];
                                $salary_breakdown['gov_share'.$key]['gl_id'] = $value['gl_id'];
                                $emp_breakdown['gov_share'.$key]['sl_id'] = $value['sl_id_debit'];
                                $emp_breakdown['gov_share'.$key]['gl_id'] = $value['gl_id_debit'];
                                $emp_breakdown['gov_share'.$key]['amt'] = $value['hriad_deduct'];
                                if (isset($salary_breakdown['gov_share'.$key]['amt'])) {
                                    $salary_breakdown['gov_share'.$key]['amt'] += $value['hriad_deduct'];
                                }else {
                                    $salary_breakdown['gov_share'.$key]['amt'] = $value['hriad_deduct'];
                                }
                            }
                            // $salary_breakdown['salary']['gl_id'] = $employee->appoint_status->gl->id;
                            $salary_breakdown['salary']['sl_id'] = $employee->appoint_status->sl->id;
                            $salary_breakdown['salary']['gl_id'] = $employee->appoint_status->gl->id;
                            $emp_breakdown['salary']['sl_id'] = $employee->appoint_status->sl->id;
                            $emp_breakdown['salary']['gl_id'] = $employee->appoint_status->gl->id;
                            $emp_breakdown['salary']['amt'] = $hrpr_total_salary;
                            if (isset( $salary_breakdown['salary']['amt'])) {
                                $salary_breakdown['salary']['amt'] += $hrpr_total_salary;
                            }else {
                                $salary_breakdown['salary']['amt'] = $hrpr_total_salary;
                            }
                                foreach ($emp_breakdown as $value) {
                                Payroll::sendBreakdown($payroll, $value);
                            }
                            Payroll::updatePayments([
                                'payroll' => $payroll,
                                'employee' => $employee,
                                'timekeep' => $timekeep,
                            ]);
                        }
                    }
                }
                
            if ($request->btn == 1) {
                Payroll::find($request->input('id'))->sendOBR(
                    $salary_breakdown,
                    $cutoff,
                );
                $this->_Payroll->where('hrpr_payroll_no',$request->hrpr_payroll_no)->update([
                    'hrpr_is_processed' => 1,
                    'hrpr_processed_date' => Carbon::today()->toDateString(),
                ]);
            }
            return redirect()->back();
        }
        $department =$this->department;
        $employee_appointment_status= $this->employee_appointment_status;
        return view('HR.PayrollCalculate.create',compact('data','department','employee_appointment_status'));
    }

    public function selectEmployees(Request $request, $division, $type){
        $q = $request->input('search');
        $cutoff_id = $request->input('cutoff_id');
        $data = [];
        $employees = Payroll::getEmployee($q, $division, $type,$cutoff_id);
        foreach ($employees['data'] as $key => $value) {
            // dd($value);
            $data['data'][$key]['id']=$value->hr_emp_id;
            $data['data'][$key]['text']=$value->fullname;
        }
        $data['data_cnt']=$employees['data_cnt'];
        echo json_encode($data);
    }
    public function getPayroll(Request $request){
        //show employee payroll computation
            $data = Payroll::getEmployeePayroll($request->emp_id,$request->cutoff_id,$request->id);
            return json_encode($data);
    }

    public function selectCutoff(Request $request){
        $q = $request->input('search');
        $data = [];
        $Citizen = Payroll::getCutoff($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->hrcp_description .': '.$value->hrcp_date_from .' - '.$value->hrcp_date_to;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }
    
    public function updateTimecards(){
        $logins = collect();
        $missedin = collect(
            HrMissedLog::where([
                ['is_copied',0],
                ['hrlog_id',1]
            ])
            ->whereNotNull(['hml_approved_by','hml_reviewed_by','hml_noted_by'])
            ->whereNotIn('hml_approved_by',[0])
            ->whereNotIn('hml_reviewed_by',[0])
            ->whereNotIn('hml_noted_by',[0])
            ->where('hml_status',6)
            ->get(['id','hml_work_date as log_date','hml_actual_time as log_time','hr_emp_id as emp_id','hml_noted_by as hrtc_updated_by','hrlog_id',DB::raw('1 AS log')])
        );

        $records = collect(
            HrBiometricsRecord::where('is_copied',0)
            ->get(['id','hrbr_date as log_date','hrbr_time as log_time','hrbr_emp_id as emp_id','hrbr_emp_id as hrtc_updated_by',DB::raw('0 AS log')])
        );
        $missedout = collect(
            HrMissedLog::where([
                ['is_copied',0],
                ['hrlog_id',2]
            ])
            ->whereNotNull(['hml_approved_by','hml_reviewed_by','hml_noted_by'])
            ->whereNotIn('hml_approved_by',[0])
            ->whereNotIn('hml_reviewed_by',[0])
            ->whereNotIn('hml_noted_by',[0])
            ->where('hml_status',6)
            ->get(['id','hml_work_date as log_date','hml_actual_time as log_time','hr_emp_id as emp_id','hml_noted_by as hrtc_updated_by','hrlog_id',DB::raw('1 AS log')])
        );
        $logs = $missedin->merge($records);
        $logs = $logs->merge($missedout);
        foreach ($logs as $key => $value) {
            
            $appointment = HrAppointment::where('hr_emp_id',$value->emp_id)->first();
            if ($appointment === null) {
                $appointment = [];
            }
            $log_type = null;
            if (isset($value->hrlog_id)) {
                $log_type = $value->hrlog_id;
            }
            $timecard = $this->_Payroll->updateTimecard([
                'hrtc_employeesidno' => isset($appointment->hra_employee_no)? $appointment->hra_employee_no : '',
                'hrtc_employeesid' => $value->emp_id,
                'hrtc_department_id' => isset($appointment->hra_department_id)? $appointment->hra_department_id : '',
                'hrtc_division_id' => isset($appointment->hra_division_id)? $appointment->hra_division_id : '',
                'hrtc_date' => $value->log_date,
                'hrtc_time_in' => $value->log_time,
                'updated_by' => $value->hrtc_updated_by,
            ],$log_type);
            if ($timecard) {
                if ($value->log === 1) {
                    HrMissedLog::where('id',$value->id)->update(['is_copied'=>1]);
                } else {
                    HrBiometricsRecord::where('id',$value->id)->update(['is_copied'=>1]);
                }
            }
            
        }
        $official = HrOfficialWork::where([['is_copied',0]])
        ->whereNotNull(['hrow_approved_by','hrow_reviewed_by','hrow_noted_by'])
        ->whereNotIn('hrow_approved_by',[0])
        ->whereNotIn('hrow_reviewed_by',[0])
        ->whereNotIn('hrow_noted_by',[0])
        ->where('hrow_status',6)
        ->get();
        foreach ($official as $key => $value) {
            $appointment = HrAppointment::where('hr_emp_id',$value->hr_employeesid)->first();
            if ($appointment === null) {
                $appointment = [];
            }
            $timecard = $this->_Payroll->updateTimecard([
                'hrtc_employeesidno' => isset($appointment->hra_employee_no)? $appointment->hra_employee_no : '',
                'hrtc_employeesid' => $value->hr_employeesid,
                'hrtc_department_id' => isset($appointment->hra_department_id)? $appointment->hra_department_id : '',
                'hrtc_division_id' => isset($appointment->hra_division_id)? $appointment->hra_division_id : '',
                'hrtc_date' => $value->hrow_work_date,
                'hrtc_time_in' => $value->hrow_time_in,
                'hrtc_time_out' => $value->hrow_time_out,
                'updated_by' => $value->hrow_noted_by,
            ]);
            
            if ($timecard) {
                HrOfficialWork::where('id',$value->id)->update(['is_copied'=>1]);
            }

        }
        return true;
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'hrcp_id'=>'required',
                'hrpr_department_id'=>'required',
                // 'hrpr_appointment_type'=>'required',
                // 'hrpr_division_id'=>'required',
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

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Payroll->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        // dd($data['data']);
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->pay_id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ref_no']=$row->hrpr_payroll_no;
            $arr[$i]['payroll_period']=$row->hrcp_description;
            $arr[$i]['department']=$row->dept_name;
            $arr[$i]['division']=$row->div_name;
            $arr[$i]['appointment_type']=$row->app_status;
            $arr[$i]['process']=($row->hrpr_is_processed==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Processed</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-payroll-calculate/store?id='.$row->pay_id).'" data-ajax-popup="true"  data-size="xxl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Payroll">
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

    public function getEmployeeList(Request $request){
        $this->is_permitted('my-timecard', 'read');
        $data=$this->_Payroll->getList($request, 'user');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        // dd($data['data']);
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->pay_id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ref_no']=$row->hrpr_payroll_no;
            $arr[$i]['payroll_period']=$row->hrcp_description;
            $arr[$i]['department']=$row->dept_name;
            $arr[$i]['division']=$row->div_name;
            $arr[$i]['appointment_type']=$row->app_status;
            $arr[$i]['process']=($row->hrpr_is_processed==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Processed</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/my-payroll/view?id='.$row->pay_id).'" data-ajax-popup="true"  data-size="xxl" data-bs-toggle="tooltip" title="Edit"  data-title="View Payroll">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    
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
    public function payrollView(Request $request){
        $this->is_permitted('my-timecard', 'read');     

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_Payroll->find($request->input('id'));
        }
        $department =$this->department;
        $employee_appointment_status= $this->employee_appointment_status;
        return view('HR.PayrollCalculate.employeeView',compact('data','department','employee_appointment_status'));
    }
    public function timecard(Request $request) 
    {
        $job = $this->findJobs($this->carbon::now(), 'timecard');
        if ($job->count() > 0) {
            $job = $job->first();
            return response()
            ->json([
                'status' => true,
                'data' => $this->updateTimecards()
            ]);
        }
        return response()
        ->json([
            'success' => false
        ]);
    }
    public function printGeneral(Request $request,$payroll) {
        $data=$this->_Payroll->find($payroll);
        // settings
        $height = 5;

        PDF::setHeaderCallback(function($pdf) use ($data, $height){
            $department = $data->appointment->employee->department->name;
            $division = $data->appointment->employee->division->name;
            $appointment_stat = $data->appointmentStatus->hras_description;
            $pdf->SetY(5);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->writeHTML('<p style="text-align:center">City of Palayan</p>',true, false, false, false, 'center');
            $pdf->writeHTML('<p style="text-align:center">'.$department.', '.$division.' ('.$appointment_stat.')</p>',true, false, false, false, 'center');
            $pdf->writeHTML('<h3 style="text-align:center">GENERAL PAYROLL</h3>',true, false, false, false, 'center');

            $pdf->ln(3);
            $pdf->writeHTML('<p>For the period of '.$data->cutoff->hrcp_description.'</p>',true, false, false, false, 'center');
            $pdf->ln(3);
            $pdf->setCellPadding(0.4, 0.01, 0.4, 0.01);

            $pdf->SetFont('Helvetica', '', 7);
            $pdf->MultiCell(7, $height * 4, "No.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(25, $height * 4, "Name", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(15.5, $height * 4, "Designation", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(15.5, $height * 4, "Monthly Basic", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(15.5, $height * 4, "ACA /PERA", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(15.5, $height * 4, "Gross Pay", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->SetFont('Helvetica', 'B', 7);
            $pdf->MultiCell(217, $height, "    D E D U C T I O N S    ", 1, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->SetFont('Helvetica', '', 7);
            $pdf->MultiCell(15.5, $height * 4, "Others", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(0, $height * 4, "Monthly NET PAY", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            // $pdf->MultiCell(15.5, $height * 4, "NET PAY (1-15.5)", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            // $pdf->MultiCell(7, $height * 4, "No.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            // $pdf->MultiCell(0, $height * 4, "SIGNATURE", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 4, 'M');
            $pdf->MultiCell(0, $height, "", 0, 'J', 0, 1, '', '', true, 0, false, true, $height, 'M');

            $pdf->MultiCell(94, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height * 3, "ECC G.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height * 2, "GSIS P.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "Pagibig P.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "Philhealth P.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "GSIS Conso/", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "GSIS EDUC Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 3, "GSIS Emrgncy Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height * 3, "Cash Advance  E-Card", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height * 3, "W/holding Tax", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height * 3, "Policy Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height * 2, "Pagibig Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "GSIS Add'l Prem.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 2, "COOP Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            $pdf->MultiCell(15.5, $height * 3, "Total Deduc", 1, 'C', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(0, $height * 2, "", 0, 'C', 0, 1, '', '', true, 0, false, true, $height * 2, 'M');

            $pdf->MultiCell(94, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height * 3, 'M');
            $pdf->MultiCell(15.5, $height, "G.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "G.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "G.S.", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "Multipurpose", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "CPL", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "Calamity", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "Loan", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(15.5, $height, "LWOP", 1, 'C', 0, 0, '', '', true, 0, false, true, $height, 'M');
            $pdf->MultiCell(0, $height , "", 0, 'J', 0, 1, '', '', true, 0, false, true, $height , 'M');
        });

        PDF::SetTitle('General Payroll For '.$data->hrpr_payroll_no.'');    
        PDF::SetMargins(5, 10, 5,true);    
        PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        PDF::AddPage('L', 'LEGAL');
        PDF::SetHeaderMargin(10);
        PDF::setCellPadding(1, 0, 1, 0);

        PDF::SetY(49.5);
        PDF::ln();
        $sr_no = 0;
        PDF::SetFont('Helvetica', '', 7);

        //grand total
        $deductions_total = [];

        $monthly_rate_total = 0;
        $income_total = 0;
        $gross_total = 0;
        $total_deduct_total = 0;
        $monthly_netpay_total = 0;
        $netpay_total = 0;

        foreach ($data->processed_emp as $value) {
            // employees
            $sr_no += 1;
            $deductions = [];
            $other_deduct = $value->hrpr_deductions;
            foreach(config('constants.hrDeductions') as $column => $type){
                $amount = $value->getJSONData($type,$column,'hriad_deduct');
                $deductions[$column] = $amount;

                if (isset($deductions_total[$column])) {
                    $deductions_total[$column] += $amount;
                } else {
                    $deductions_total[$column] = $amount;
                }
                
                if ($type === 'hrpr_deduction') {
                    $other_deduct -= (float)$amount;
                }
            }
            if (isset($deductions_total['other_deduct'])) {
                $deductions_total['other_deduct'] += $other_deduct;
            } else {
                $deductions_total['other_deduct'] = $other_deduct;
            }
            

            $monthly_rate_total += $value->hrpr_total_salary;
            $income_total += $value->hrpr_earnings;
            $gross_total += $value->hrpr_total_salary;
            $total_deduct_total += $value->hrpr_deductions;
            $monthly_netpay_total += $value->hrpr_net_salary;
            $netpay_total += $value->hrpr_net_salary / 2;

            PDF::MultiCell(7, $height * 2, $sr_no, 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(25, $height * 2, $value->appointment->employee->fullname, 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(15.5, $height * 2, $value->appointment->employee->designation->description, 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(15.5, $height * 2, currency_format($value->hrpr_total_salary), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(15.5, $height * 2, currency_format($value->hrpr_earnings), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(15.5, $height * 2, currency_format($value->hrpr_total_salary + $value->hrpr_earnings), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(15.5, $height, '', 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_contribution'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['pag_ibig'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['philhealth'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_conso'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_educ'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_emergency'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['cash_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['tax'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['policy_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['pagibig_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_add_prem'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['coop_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height* 2, currency_format($value->hrpr_deductions), 1, 'R', 0, 0, '', '', true, 0, false, true, $height* 2, 'M');
            PDF::MultiCell(15.5, $height * 2, currency_format($other_deduct), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(0, $height * 2, currency_format($value->hrpr_net_salary), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            // PDF::MultiCell(15.5, $height * 2, currency_format($value->hrpr_net_salary / 2), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            // PDF::MultiCell(7, $height * 2, $sr_no, 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            // PDF::MultiCell(0, $height * 2, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
            PDF::MultiCell(0, $height , "", 1, 'R', 0, 1, '', '', true, 0, false, true, $height, 'M');

            PDF::MultiCell(94, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['ecc'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['pagibig_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['philhealth_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_multipurpose'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['computer_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['pagibig_calamity'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['gsis_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, currency_format($deductions['coop_lwop'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
            PDF::MultiCell(15.5, $height, "", 0, 'R', 0, 1, '', '', true, 0, false, true, $height, 'M');
            // end employee
        }

        // Grand Total
        PDF::MultiCell(7, $height * 2, '', 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(25, $height * 2, 'Grand Total', 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(15.5, $height * 2, '', 1, 'L', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(15.5, $height * 2, currency_format($monthly_rate_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(15.5, $height * 2, currency_format($income_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(15.5, $height * 2, currency_format($gross_total + $income_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_contribution'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['pag_ibig'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['philhealth'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_conso'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_educ'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_emergency'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['cash_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['tax'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['policy_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['pagibig_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_add_prem'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['coop_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height* 2, currency_format($total_deduct_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height* 2, 'M');
        PDF::MultiCell(15.5, $height * 2, currency_format($deductions_total['other_deduct'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(0, $height * 2, currency_format($monthly_netpay_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        // PDF::MultiCell(15.5, $height * 2, currency_format($netpay_total), 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        // PDF::MultiCell(7, $height * 2, $sr_no, 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        // PDF::MultiCell(0, $height * 2, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height * 2, 'M');
        PDF::MultiCell(0, $height , "", 1, 'R', 0, 1, '', '', true, 0, false, true, $height, 'M');

        PDF::MultiCell(94, $height, "", 0, 'J', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['ecc'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['pagibig_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['philhealth_gs'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_multipurpose'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['computer_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, "", 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['pagibig_calamity'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['gsis_loan'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, currency_format($deductions_total['coop_lwop'] ), 1, 'R', 0, 0, '', '', true, 0, false, true, $height, 'M');
        PDF::MultiCell(15.5, $height, "", 0, 'R', 0, 1, '', '', true, 0, false, true, $height, 'M');
        // Grand Total end

        //approvals
        
        PDF::lastPage();
        PDF::Output('general_payroll'.$data->hrpr_payroll_no.'.pdf');
        
    }
}
