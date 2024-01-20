<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Traits\ModelUpdateCreate;
use App\Models\HrEmployee;
use App\Models\CboAllotmentObligationRequest;
use App\Models\CboAllotmentObligation;
use App\Models\CboAllotmentBreakdown;
use App\Models\CboObligationPayroll;
use App\Models\PayrollBreakdown;
use App\Models\AcctgAccountGeneralLedger;
use Auth;
class Payroll extends Model
{
    use ModelUpdateCreate;
    public $table = 'hr_payroll';
    protected $guarded = ['id'];
    public $timestamps = false;

    // relation
    public function appointment() 
    { 
        return $this->hasOne(HrAppointment::class, 'hr_emp_id', 'hrpr_employees_id'); 
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'hrpr_employees_id', 'id');
    }
    public function appointmentStatus() 
    { 
        return $this->hasOne(HrAppointmentStatus::class, 'id', 'hrpr_appointment_type'); 
    }

    public function cutoff() 
    { 
        return $this->hasOne(CuttoffPeriod::class, 'id', 'hrcp_id'); 
    }

    // Attributes
    public function breakdown_amount($emp_id, $gl, $sl) 
    { 
        $payroll_no =$this->hrpr_payroll_no;
        $breakdown = PayrollBreakdown::where([
            'payroll_no' => $payroll_no,
            'emp_id' => $emp_id,
            'gl_id' => $gl,
        ]); 
        if ($sl) {
            $breakdown->where('sl_id', $sl);
        }
        return $breakdown->first()->amount;
    }
    public function getPayrollDescAttribute()
    {
        $cutoff = $this->cutoff;
    
        return $cutoff ? $cutoff->hrcp_description .': '.$cutoff->hrcp_date_from .' - '.$cutoff->hrcp_date_to :'';
    }

    public function getNextNumberAttribute()
    {
        $lastNum = $this->whereYear('created_at',date('Y'))->orderBy('id','desc')->first();
        if ($lastNum) {
            $lastNum = explode('-',$lastNum->hrpr_payroll_no)[1];
        } else {
            $lastNum = 0;
        }
        $lastNum = sprintf('%06d',$lastNum+1);
        return date('Y') .'-'.$lastNum;
    }

    public function getProcessedEmpAttribute()
    {
        if ($this->hrpr_is_processed) {
            $emps = self::where(['hrpr_payroll_no'=>$this->hrpr_payroll_no])->get();
        } else {
            $emps = HrTimekeeping::leftJoin('hr_payroll','hr_payroll.hrpr_employees_id', 'hr_timekeeping.hrtk_emp_id')
                ->where([
                    ['hrtk_department_id',$this->hrpr_department_id],
                    ['hrtk_division_id',$this->hrpr_division_id],
                    ['hr_timekeeping.hrcp_id',$this->hrcp_id],
                    ['hr_payroll.hrpr_payroll_no',$this->hrpr_payroll_no],
                ])
                ->orWhereNull('hr_payroll.hrcp_id')->get();

        }
        return $emps;
    }

    public function getJSONData($column,$key,$data)
    {
        if ($this[$column]) {
            $payroll = json_decode($this[$column]);
            return isset($payroll->$key) ? $payroll->$key->$data : '0.00';
        }
        return '0.00';
    }
    
    public function getEmployee($search="",$division=null,$type=null,$cutoff=null)
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = HrTimekeeping::join('hr_appointment','hr_appointment.hr_emp_id', 'hr_timekeeping.hrtk_emp_id')
        ->leftJoin('hr_employees','hr_employees.id', 'hr_timekeeping.hrtk_emp_id')
        ->leftJoin('hr_payroll','hr_payroll.hrpr_employees_id', 'hr_timekeeping.hrtk_emp_id')
        ->where('hr_appointment.is_active',1)
        ->where('hr_timekeeping.hrtk_is_processed',0)
        ->where('hr_timekeeping.hrcp_id',$cutoff);
        if(!empty($search)){
            $sql = $sql->where(function ($sql) use($search) {
                    if(is_numeric($search)){
                        $sql = $sql->Where('id',$search);
                    }else{
                        $sql = $sql->where(DB::raw('LOWER(hr_employees.fullname)'),'like',"%".strtolower($search)."%");
                    }
            });
        }
        if(!empty($cutoff)){
            $sql = $sql->where(function ($sql) use($cutoff) {
                $sql = $sql->where('hr_payroll.hrcp_id','!=',$cutoff);
                $sql = $sql->orWhereNull('hr_payroll.hrcp_id');
            });
        }
// dd($sql->get());
        if ($division && $type) {
            $sql = $sql->where('hra_division_id',$division)->where('hras_id',$type);
        }
        $sql = $sql->orderBy('hr_employees.fullname','ASC');
        $data_cnt=$sql->count();
        $sql = $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    

    public function getCutoff($search="")
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = DB::table('hr_cutoff_period')
                ->select('*');
        if(!empty($search)){
            $sql = $sql->where(function ($sql) use($search) {
                    if(is_numeric($search)){
                        $sql->Where('id',$search);
                    }else{
                        $sql->where('hrcp_date_from','like',"%".$search."%")
                            ->orWhere('hrcp_date_to','like',"%".$search."%")
                            ->orWhere(DB::raw('LOWER(hrcp_description)'),'like',"%".strtolower($search)."%");
                    }
            });
        }
        $sql->orderBy('hrcp_date_from','DESC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function updateTimecard($data, $is_log_in = null)
    {
        // note: comment try catch to get errors
        // try {
            $data = $data;
            $work = HrWorkSchedule::where([
                ['hr_employeesid',$data['hrtc_employeesid']],
                ['hrds_date',$data['hrtc_date']],
                ])->first();
            $data['hrds_id'] = 0;
            if ($work) {
                $schedule = HrDefaultSchedule::find($work->hrds_id);
                $data['hrds_id'] = $work->hrds_id;
                $data['hrtc_work_sched_in'] = $schedule->hrds_start_time;
                $data['hrtc_work_sched_out'] = $schedule->hrds_end_time;
            }

            $holiday = HrHolidays::where('hrh_date',$data['hrtc_date'])->first();
            $data['hrht_id'] = 0;
            $data['hrtc_multiplier'] = 0;
            if ($holiday) {
                $data['hrht_id'] = $holiday->hrht_id;
                $data['hrtc_multiplier'] = $holiday->holiday_type->hrht_multipier;
            }
            $timecard = HrTimecard::where([
                'hrtc_employeesidno' => $data['hrtc_employeesidno'],
                'hrtc_employeesid' => $data['hrtc_employeesid'],
                'hrtc_department_id' => $data['hrtc_department_id'],
                'hrtc_division_id' => $data['hrtc_division_id'],
                'hrtc_date' => $data['hrtc_date'],
            ])->first();
            if ($is_log_in) {
                //if miss out
                if ($is_log_in === 1) {
                    $insert = [
                            'hrht_id' => $data['hrht_id'],
                            'hrds_id' => $data['hrds_id'],
                            'hrtc_multiplier' => $data['hrtc_multiplier'],
                            'hrtc_time_in' => $data['hrtc_time_in'],
                            'updated_by' => $data['updated_by'],
                        ];
                    if ($timecard && $timecard->hrtc_time_in) {
                        $insert['hrtc_time_out'] = $timecard->hrtc_time_in;
                    } 
                    // Miss In
                } else {
                    $insert = [
                        'hrht_id' => $data['hrht_id'],
                        'hrds_id' => $data['hrds_id'],
                        'hrtc_multiplier' => $data['hrtc_multiplier'],
                        'hrtc_time_out' => $data['hrtc_time_in'],
                        'updated_by' => $data['updated_by'],
                    ];
                    // $timecard->update(['hrtc_time_out' => $data['hrtc_time_in']]);
                }
            } else {
                // biometrics
                // if ($timecard) {
                if (!isset($data['hrtc_time_out']) && !isset($timecard['hrtc_time_out']) && $timecard) {
                    $timecard->update(['hrtc_time_out' => $data['hrtc_time_in']]);
                    $insert = null;
                } elseif (isset($timecard['hrtc_time_out']) && isset($timecard['hrtc_time_in']) && $timecard) {
                    $time_in = Carbon::parse($timecard['hrtc_time_in']);
                    $time_out = Carbon::parse($timecard['hrtc_time_out']);
                    $time_data = Carbon::parse($data['hrtc_time_in']);
                    if ($time_in->greaterThan($time_data)) {
                        $timecard->update(['hrtc_time_in' => $data['hrtc_time_in']]);
                    } elseif ($time_out->lessThan($time_data)){
                        $timecard->update(['hrtc_time_out' => $data['hrtc_time_in']]);
                    }
                    $insert = null;
                }else {
                    $insert = $data;
                }
            }

            if ($insert) {
                $timecard = HrTimecard::updateOrCreate(
                    [
                        'hrtc_employeesidno' => $data['hrtc_employeesidno'],
                        'hrtc_employeesid' => $data['hrtc_employeesid'],
                        'hrtc_department_id' => $data['hrtc_department_id'],
                        'hrtc_division_id' => $data['hrtc_division_id'],
                        'hrtc_date' => $data['hrtc_date'],
                    ],
                    $insert);
            }
            if ($timecard->hrtc_time_in && $timecard->hrtc_time_out) {
                $timecard->getAndUpdateWorkHours();
                // dd($timecard);
            }
            
            return $timecard;
        // } catch (\Throwable $th) {
        //     return false;
        // }
    }

    public function getEmployeePayroll($emp_id, $cutoff_id,$payroll_id=null){
        $appointment = HrAppointment::where('hr_emp_id',$emp_id)->first();
        $cutoff = CuttoffPeriod::find($cutoff_id);
        $payroll = self::find($payroll_id);
        if ($payroll && $payroll->hrpr_is_processed == 1) {
            $processed = 1;
        } else {
            $processed = 0;
        }
        // dd($payroll);
        $timekeep = $appointment->timekeep->where('hrcp_id',$cutoff_id)->where('hrtk_is_processed',$processed)->first();
        $today = Carbon::parse($cutoff->hrcp_date_to);
        if ($timekeep) {
            $double_rest_pay = $appointment->overtimes->where('hrot_is_double_holiday',1)->where('hrot_is_rest_day',1)->where('hrot_is_process',$processed)->where('hro_status',6);
            $double_pay = $appointment->overtimes->where('hrot_is_double_holiday',1)->where('hrot_is_rest_day',0)->where('hrot_is_process',$processed)->where('hro_status',6);
            $regular_rest_pay = $appointment->overtimes->where('hrot_is_regular_holiday',1)->where('hrot_is_rest_day',1)->where('hrot_is_process',$processed)->where('hro_status',6);
            $special_rest_pay = $appointment->overtimes->where('hrot_is_special_holiday',1)->where('hrot_is_rest_day',1)->where('hrot_is_process',$processed)->where('hro_status',6);
            $regular_pay = $appointment->overtimes->where('hrot_is_regular_holiday',1)->where('hrot_is_rest_day',0)->where('hrot_is_process',$processed)->where('hro_status',6);
            $special_pay = $appointment->overtimes->where('hrot_is_special_holiday',1)->where('hrot_is_rest_day',0)->where('hrot_is_process',$processed)->where('hro_status',6);
            $rest_pay = $appointment->overtimes->where('hrot_is_special_holiday',0)->where('hrot_is_regular_holiday',0)->where('hrot_is_double_holiday',0)->where('hrot_is_rest_day',1)->where('hrot_is_process',$processed)->where('hro_status',6);
            $regular_pay = $appointment->overtimes->where('hrot_is_special_holiday',0)->where('hrot_is_regular_holiday',0)->where('hrot_is_double_holiday',0)->where('hrot_is_rest_day',0)->where('hrot_is_process',$processed)->where('hro_status',6);
            $data = [
                'timekeep_id' => $timekeep->id,
                'hr_designation' => $appointment->employee->designation->description,
                'employee_name' => $appointment->employee->fullname,
                'hrpr_monthly_rate' => number_format($appointment->hra_monthly_rate,2),
                'income' =>$appointment->income_deduction->where('hriad_effectivity_date','<=',$today->toDateString())->where('hridt_type',1),
                'deduction' =>$appointment->income_deduction->where('hriad_effectivity_date','<=',$today->toDateString())->where('hridt_type',0)->where('hriad_balance','!=',0),
                'hrpr_aut' => $timekeep->hrtk_total_aut,
                'hrpr_reg_ot' => $double_rest_pay->sum('hrot_ot_cost'),
                'hrpr_rd_ot' => $double_rest_pay->sum('hrot_ot_cost'),
                'hrpr_holiday_ot' => $double_rest_pay->sum('hrot_ot_cost'),
                'hrpr_earnings' => $appointment->income_deduction->where('hridt_id',1)->sum('hriad_amount'),
                'hrpr_aut_compute' => number_format($timekeep->hrtk_total_aut * $appointment->hra_hourly_rate,2),
                'date_now'=>$today
            ];

            // Overtime display
            if ($double_rest_pay->isNotEmpty()) {
                $data['ot']['double_rest_pay'] = [
                    'hours' => $double_rest_pay->sum('hrot_considered_hours'),
                    'earn' => $double_rest_pay->sum('hrot_ot_cost'),
                    'name' => 'Double Holiday + Rest Day',
                ];
            }
            if ($double_pay->isNotEmpty()) {
                $data['ot']['double_pay'] = [
                    'hours' => $double_pay->sum('hrot_considered_hours'),
                    'earn' => $double_pay->sum('hrot_ot_cost'),
                    'name' => 'Double Holiday',
                ];
            }
            if ($regular_rest_pay->isNotEmpty()) {
                $data['ot']['regular_rest_pay'] = [
                    'hours' => $regular_rest_pay->sum('hrot_considered_hours'),
                    'earn' => $regular_rest_pay->sum('hrot_ot_cost'),
                    'name' => 'Regular Holiday + Rest Day',
                ];
            }
            if ($special_rest_pay->isNotEmpty()) {
                $data['ot']['special_rest_pay'] = [
                    'hours' => $special_rest_pay->sum('hrot_considered_hours'),
                    'earn' => $special_rest_pay->sum('hrot_ot_cost'),
                    'name' => 'Special Holiday + Rest Day',
                ];
            }
            if ($regular_pay->isNotEmpty()) {
                $data['ot']['regular_pay'] = [
                    'hours' => $regular_pay->sum('hrot_considered_hours'),
                    'earn' => $regular_pay->sum('hrot_ot_cost'),
                    'name' => 'Regular Holiday',
                ];
            }
            if ($special_pay->isNotEmpty()) {
                $data['ot']['special_pay'] = [
                    'hours' => $special_pay->sum('hrot_considered_hours'),
                    'earn' => $special_pay->sum('hrot_ot_cost'),
                    'name' => 'Special Holiday',
                ];
            }
            if ($rest_pay->isNotEmpty()) {
                $data['ot']['rest_pay'] = [
                    'hours' => $rest_pay->sum('hrot_considered_hours'),
                    'earn' => $rest_pay->sum('hrot_ot_cost'),
                    'name' => 'Rest Day',
                ];
            }
            if ($regular_pay->isNotEmpty()) {
                $data['ot']['regular_pay'] = [
                    'hours' => $regular_pay->sum('hrot_considered_hours'),
                    'earn' => $regular_pay->sum('hrot_ot_cost'),
                    'name' => 'OT',
                ];
            }

            //Gov fees
            $fees = $appointment->income_deduction->where('hriad_effectivity_date','<=',$today->toDateString())->where('hridt_type',3);
            foreach ($fees as $fee) {
                // dd($fee);
                $code = str_replace(' ','_',strtolower($fee->hriad_description));
                $data['deduction'][$code] = [
                    'hriad_deduct' => $fee->hriad_deduct,
                    'hriad_description' => $fee->hriad_description,
                    'gl_id' => $fee->gl_id,
                    'sl_id' => $fee->sl_id,
                    'gl_id_debit' => $fee->gl_id_debit,
                    'sl_id_debit' => $fee->sl_id_debit,
                    'id' => $fee->id,
                ];
                if ($code === 'tax') {
                    $data['ewt_id'] = HrTax::getAmountScope($appointment->hra_monthly_rate) ? HrTax::getAmountScope($appointment->hra_monthly_rate)->ewt_id : 0;
                }
            }
            // Get Loans
            $loans = $appointment->loan_breakdown->where([
                'hrll_payment_date' => $today->endOfMonth()->toDateString(),
                'hrla_loan_status'=> 1
            ])->get();

            $sum_loans = 0;
            foreach ($loans as $value) {
                $code = $value->loan_app->loan_type->hrlt_code;
                $data['deduction'][$code] = [
                    'hriad_deduct' => $value->hrll_installment_amount,
                    'sl_id' => $value->sl_id,
                    'gl_id' => $value->gl_id,
                    'sl_id_debit' => $value->sl_id_debit,
                    'gl_id_debit' => $value->gl_id_debit,
                    'hriad_description' => 'Loan: '.$value->hrla_loan_description,
                ];

                $sum_loans += $value->hrll_installment_amount;
            }
            $data['hrpr_deductions'] = array_sum(array_column($data['deduction']->toArray(), 'hriad_deduct'));

            // GOVERNMENT Share COMPUTATION
            $fees = $appointment->income_deduction->where('hriad_effectivity_date','<=',$today->toDateString())->where('hridt_type',2);
            foreach ($fees as $fee) {
                $code = str_replace(' ','_',strtolower($fee->hriad_description));
                $data['gov_share'][$code] = [
                    'hriad_deduct' => $fee->hriad_deduct,
                    'hriad_description' => $fee->hriad_description,
                    'gl_id' => $fee->gl_id,
                    'sl_id' => $fee->sl_id,
                    'gl_id_debit' => $fee->gl_id_debit,
                    'sl_id_debit' => $fee->sl_id_debit,
                    'id' => $fee->id,
                ];
            }
            return (object)$data;
        }
        return null;
    }

    public function getList($request, $type = null){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
    
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
    
        $columns = array( 
          0 =>"id",
          1 =>"hrlc_month",
          2 =>"hrlc_status",
        );
    
        $sql = DB::table('hr_payroll as hrp')
                ->leftjoin('hr_cutoff_period AS hrcp', 'hrcp.id', '=', 'hrp.hrcp_id')
                ->leftjoin('hr_employees AS he', 'he.id', '=', 'hrp.hrpr_employees_id')
                ->leftjoin('hr_appointment AS ha', 'he.id', '=', 'ha.hr_emp_id')
                ->leftjoin('hr_appointment_status AS has', 'has.id', '=', 'ha.hras_id')
                ->leftjoin('acctg_departments AS ad', 'ad.id', '=', 'he.acctg_department_id')
                ->leftjoin('acctg_departments_divisions AS add', 'add.id', '=', 'he.acctg_department_division_id')
                ->leftjoin('hr_designations AS hd', 'hd.id', '=', 'he.hr_designation_id')
                ->select('hrp.*','he.fullname as emp_name','hrcp.hrcp_description','ad.name as dept_name','add.name as div_name','hd.description as designation','has.hras_description as app_status','hrp.id as pay_id')
                ->groupBy('hrp.hrpr_payroll_no');
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(hrp.hrpr_payroll_no)'),'like',"%".strtolower($q)."%");
                });
            }
            // for my-timecard
        if ($type === 'user') {
            $sql->where('hrpr_employees_id',Auth::user()->hr_employee->id)->where('hrpr_is_processed',1);
            
        }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('hrp.hrpr_payroll_no','DESC');
        
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    function updatePayments($data) {
        $data = (object)$data;
        $now = $data->timekeep->date_now;
        $ovetimes = HrOverTimeModels::where([
            ['hrot_is_process',0],
            ['hrwc_id',2],
            ['hrot_work_date','<=',$now->toDateString()],
            ['hro_approved_by','!=',0],
            ['hro_reviewed_by','!=',0],
            ['hro_noted_by','!=',0],
            ['hro_status',6],
            ['hr_employeesid',$data->employee->hr_emp_id]
        ])->update(['hrot_is_process'=>1]);
        $loans = $data->employee->loans->where('hrll_payment_date',$now->endOfMonth()->toDateString());
        foreach ($loans as $value) {
            $loan_ledger = HrLoanLedger::find($value->id);
            $loan = HrLoanApplication::find($loan_ledger->hrla_id);
            $interest = $loan->hrla_interest_amount/$loan->hrlc_id;
            $paid_amount = $loan_ledger->hrll_installment_amount - $interest;

            $loan_ledger->hrll_paid_amount = $loan_ledger->hrll_installment_amount;
            $loan_ledger->hrll_paid_date = $now->toDateString();
            $loan_ledger->hrll_payroll_ref_no = $data->payroll->hrpr_payroll_no;
            $loan_ledger->save();

            $loan_balance = $loan->hrla_balance - $paid_amount;
            $loan->hrla_balance = $loan_balance;
            if ($loan_balance === 0) {
                $loan->hrla_loan_status = 3;
            }
            $loan->save();

        }

        $income_deduction = $data->employee->income_deduction->where('hriad_effectivity_date','<=',$now->toDateString())->where('hriad_balance','!=',0);
        foreach ($income_deduction as $value) {
            $in_deduct = HrIncomeDeduction::find($value->id);
            $in_deduct->hriad_balance = $in_deduct->hriad_balance - $in_deduct->hriad_deduct;
            if ($in_deduct->hriad_balance < 0) {
                $in_deduct->hriad_date_completed = Carbon::now()->toDateString();
            }
            $in_deduct->save();
        }

        $timekeeper = HrTimekeeping::find($data->timekeep->timekeep_id);
        if ($timekeeper) {
            $timekeeper->hrtk_is_processed = 1;
            $timekeeper->save();
        }

    }
    
    // send to obr
    public function getObrIdAttribute()//obr_id
    {
        $code = 'SAL';
        $obr = DB::table('cbo_obligation_types')->select('*')->where('code', $code)->first();
        return $obr ? $obr : []; 
        
    }
    function getFundCodeAttribute()//fund_code
    {
        // $code = 101;
        $fund = DB::table('acctg_fund_codes')->select('*')->where([
            // 'code' => $code,
            'is_payroll' => 1,
        ])->first();
        return $fund ? $fund : []; 
        
    }
    function getMainDivisionAttribute()//main_division
    {
        $code = 'A';
        $dept = 32;//accounting
        $division = DB::table('acctg_departments_divisions')->select('*')->where([
            'code' => $code,
            'acctg_department_id' => $dept,
        ])->first();
        return $division ? $division : []; 
        
    }

    public function generateBudgetControlNo($year)
    {   
        $count  = CboAllotmentObligation::where('budget_year', $year)->count();
        $controlNo = $year.'-';
        if($count < 9) {
            $controlNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $controlNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $controlNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $controlNo .= '0' . ($count + 1);
        } else {
            $controlNo .= ($count + 1);
        }
        return $controlNo;
    }
    
    public function sendBreakdown($payroll, $data ) {
        $now = Carbon::now();
        $timestamp = $now->toDateString();
        if ($data['gl_id']) {
            $payrollBrkdwn = PayrollBreakdown::updateOrCreate([
                'payroll_no' => $payroll->hrpr_payroll_no,
                'hrcp_no' => $payroll->hrcp_id,
                'emp_id' => $payroll->hrpr_employees_id,
                'gl_id' => $data['gl_id'],
                'sl_id' => $data['sl_id'],
                'amount' => $data['amt'],
                'created_at' => $timestamp,
                'created_by' => auth()->user()->id
            ]);
        }
    }
    public function sendOBR($breakdown, $cutoff ) {
        //per payroll no(group)
        $now = Carbon::now();
        $timestamp = $now->toDateString();
        $obr = $this->getObrIdAttribute();
        $various = various_user();
        $user = Auth::user();
        $deparment = $this->appointment->employee->department->name;

        $breakdown_sum = array_sum(array_column($breakdown,'amt'));
        $allotment = CboAllotmentObligation::create([
            'obligation_type_id' => $obr->id,
            'budget_control_no' => $this->generateBudgetControlNo(date('Y')),
            'department_id' => $this->main_division->acctg_department_id,
            'division_id' => $this->main_division->id,
            'fund_code_id' => $this->fund_code->id,
            'employee_id' => $user->hr_employee->id,
            'designation_id' => $user->hr_designation_id,
            'with_pr' => 0,
            'particulars' => $deparment.' Payroll Period '.$cutoff->hrcp_description,
            'total_amount' => 0,
            'address' => '',
            'payee_id' => $various->cbo_payee->id,
            'budget_year' => date('Y'),
            'created_by' => $user->id,
            'created_at' => $timestamp,
        ]);
        $request = CboAllotmentObligationRequest::create([
            'allotment_id' => $allotment->id,
            'status' => 'draft',
            'sent_at' => $timestamp,
            'sent_by' =>$user->id,
            'created_by' => $user->id,
            'created_at' => $timestamp,
        ]);
        // foreach ($breakdown as $key => $value) {
        //     // dd($value['gl_id']);
        //     // $alllotBrkdwn = CboAllotmentBreakdown::create([
        //     //     'allotment_id' => $allotment->id,
        //     //     // 'budget_breakdown_id' => $breakdown,
        //     //     'gl_account_id' => $value['gl_id'],
        //     //     'amount' => $value['amt'],
        //     //     'created_at' => $timestamp,
        //     //     'created_by' => $user->id
        //     // ]);
        //     $payrollBrkdwn = PayrollBreakdown::create([
        //         'payroll_no' => $this->hrpr_payroll_no,
        //         'hrcp_no' => $cutoff->id,
        //         'gl_id' => $value['gl_id'],
        //         'sl_id' => $value['sl_id'],
        //         'amount' => $value['amt'],
        //         'created_at' => $timestamp,
        //         'created_by' => $user->id
        //     ]);
        // }
        
        $obrPayroll = CboObligationPayroll::create([
            'allotment_id' => $allotment->id,
            'payroll_no' => $this->hrpr_payroll_no,
            'created_at' => $timestamp,
            'created_by' => $user->id,
            'cutoff_id' => $cutoff->id,
            'employee_type' => $this->hrpr_appointment_type,
            'is_active' => 1

        ]);
        // dd($user);
    }
}
