<?php

namespace App\Repositories;

use App\Interfaces\HrInterface;

use App\Models\UserAccessApprovalApprover;
use App\Models\CboAllotmentObligation;
use App\Models\CboObligationPayroll;
use App\Models\AcctgVoucherSeries;
use App\Models\AcctgFundCode;
use App\Models\AcctgVoucher;
use App\Models\AcctgDebitMemo;
use App\Models\AcctgAccountDeduction;
use App\Models\AcctgAccountGeneralLedger;

use Carbon\Carbon;
use DB;
use Auth;

class HrRepository implements HrInterface
{
    public function __construct(){
        $this->first_sequence = 2; //0 => Draft, '1' => 'Cancelled', '2' => 'Disapproved',
    }

    function approveButton($user, $applicant_dept, $slugs, $sequence) {
        $query = '';
        $sequence = $sequence - $this->first_sequence;
        if ($sequence == 1) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.primary_approvers)';
        } else if ($sequence == 2) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.secondary_approvers)';
        } else if ($sequence == 3) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.tertiary_approvers)';
        } else {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.quaternary_approvers)';
        }

        $res = UserAccessApprovalApprover::select('*')
        ->leftJoin('user_access_approval_settings', function($join)
        {
            $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
        })
        ->leftJoin('menu_sub_modules', function($join)
        {
            $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
        })
        ->whereRaw($query)
        ->where([
            'menu_sub_modules.slug' => $slugs, 
            'user_access_approval_approvers.department_id' => $applicant_dept 
            ])
        ->first();
        $status = $sequence + $this->first_sequence;
        $level = $res ? ($res->levels) : 6;
            
            if ($res && $sequence >= $level) {
                if ($sequence > $res->levels) {
                    $status = 6;
                }
                $sequence = 3;
            }
            if ($res === null) {
                $sequence = null;
            }
            return [
                'sequence' => $sequence,
                'status' => $status,
            ];
    }

    function sendToDebitMemo($allotmentID, $user) {
        $obr = CboAllotmentObligation::find($allotmentID);
        $payroll = CboObligationPayroll::where('allotment_id',$allotmentID)->first();
        $voucher = $this->generate_voucher($obr, $user->id, $payroll);
        foreach ($obr->allotments as $value) {
            $this->addDebitDeductions(
                $voucher, 
                $obr, 
                $value->id,
                $payroll->payroll->employee->department->code,
                $payroll->payroll->employee->division->code,
                $payroll->payroll_no,
                'Allotment',
                'allotment',
                $value->amount,
                $value->gl_account_id,
            ); 
        }
        foreach ($payroll->payrolls as $value) {
            $due_to_employees_gl = AcctgDebitMemo::due_to_employees_gl()->id;

            //net salary
            $this->addDebitDeductions(
                $voucher, 
                $obr, 
                $value->id,
                $value->appointment->employee->department->code,
                $value->appointment->employee->division->code,
                $value->hrpr_payroll_no,
                'Salary',
                'salary',
                $value->hrpr_net_salary,
                $due_to_employees_gl,
            ); 
            foreach (json_decode($value->hrpr_income) as $income) {
                //total salary
                $this->addDebitDeductions(
                    $voucher, 
                    $obr, 
                    $value->id,
                    $value->appointment->employee->department->code,
                    $value->appointment->employee->division->code,
                    $value->hrpr_payroll_no,
                    $income->hriad_description,
                    'income',
                    $income->hriad_deduct,
                    $income->gl_id,
                    $income->sl_id,
                ); 
            }
            foreach (json_decode($value->hrpr_deduction) as $deduct) {
                if (stristr($deduct->hriad_description,'tax')) {
                    $this->addDebitDeductions(
                        $voucher, 
                        $obr, 
                        $value->id,
                        $value->appointment->employee->department->code,
                        $value->appointment->employee->division->code,
                        $value->hrpr_payroll_no,
                        $deduct->hriad_description,
                        'due_to_bir',
                        $deduct->hriad_deduct,
                        $deduct->gl_id,
                        $deduct->sl_id,
                        $value->ewt_id,
                        $deduct->hriad_deduct,
                    ); 
                } else {
                    $this->addDebitDeductions(
                        $voucher, 
                        $obr, 
                        $value->id,
                        $value->appointment->employee->department->code,
                        $value->appointment->employee->division->code,
                        $value->hrpr_payroll_no,
                        $deduct->hriad_description,
                        'deduction',
                        $deduct->hriad_deduct,
                        $deduct->gl_id,
                        $deduct->sl_id,
                    ); 
                }
                
            }
            foreach (json_decode($value->hrpr_gov_share) as $share) {
                $this->addDebitDeductions(
                    $voucher, 
                    $obr, 
                    $value->id,
                    $value->appointment->employee->department->code,
                    $value->appointment->employee->division->code,
                    $value->hrpr_payroll_no,
                    $share->hriad_description,
                    'gov_share',
                    $share->hriad_deduct,
                    $share->gl_id,
                    $share->sl_id,
                ); 
                
            }
        }

        AcctgDebitMemoRepository::update_vouchers($voucher->id);
        
    }

    function addDebitDeductions($voucher, $obr, $trans_id, $dept_code, $divi_code, $payroll_no, $item, $type, $amount, $gl_id, $sl_id = null, $ewt_id = null, $ewt_amount = null){
        $code = $dept_code.$divi_code;
        $timestamp = Carbon::now();
        $user = Auth::user();
        $gl_data = AcctgAccountGeneralLedger::find($gl_id);
        if ($gl_data) {
            switch (true) {
                case stristr($gl_data->description,'pag-ibig'):
                    $type = 'pag-ibig';
                break;
                case stristr($gl_data->description,'philhealth'):
                    $type = 'philhealth';
                break;
                case stristr($gl_data->description,'gsis'):
                    $type = 'gsis';
                break;
            }
            if ($gl_data->normal_balance === 'Credit') {
                AcctgAccountDeduction::create([
                    'voucher_id' => $voucher->id,
                    'payee_id' => $obr->payee_id,
                    'fund_code_id' => $voucher->fund_code_id,
                    'gl_account_id' => $gl_id,
                    'sl_account_id' => $sl_id,
                    'trans_no' => $payroll_no,
                    'trans_type' => $type,
                    'trans_id' => $trans_id,
                    'responsibility_center' => $code,
                    'items' => $item,
                    'amount' => $amount,
                    'total_amount' => $amount,
                    'ewt_id' => $ewt_id,
                    'ewt_amount' => $ewt_amount,
                    'status' => 'draft',
                    'created_at' => $timestamp,
                    'created_by' => $user->id
                ]);
            } else {
                AcctgDebitMemo::create([
                    'voucher_id' => $voucher->id,
                    'payee_id' => $obr->payee_id,
                    'fund_code_id' => $voucher->fund_code_id,
                    'gl_account_id' => $gl_id,
                    'sl_account_id' => $sl_id,
                    'trans_no' => $payroll_no,
                    'trans_type' => $type,
                    'trans_id' => $trans_id,
                    'responsibility_center' => $code,
                    'items' => $item,
                    'amount' => $amount,
                    'total_amount' => $amount,
                    'ewt_id' => $ewt_id,
                    'ewt_amount' => $ewt_amount,
                    'status' => 'draft',
                    'created_at' => $timestamp,
                    'created_by' => $user->id
                ]);
            }
        }
        
        
        
        
    }

    function generate_voucher($obr, $user, $payroll){
        $timestamp = \Carbon\Carbon::now();
        $emp_type =explode(' ', $payroll->emp_type->gl->description);
        $remarks = "PAYROLL FOR ".\Carbon\Carbon::parse($payroll->cutoff->hrcp_date_to)->format('F j, Y')." (".array_pop($emp_type).")";
        $payee = $obr->payee_id;
        $voucher = AcctgVoucher::where([
            'is_payables'=>2,
            'remarks'=>$remarks,
            'status'=>'draft',
        ])->first();
        if (!$voucher) {
            $fund_codes = AcctgFundCode::find($obr->fund_code_id);
            $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

            $count = AcctgVoucher::whereYear('created_at', '=',  $year)
            ->where(['fund_code_id' => $obr->fund_code_id])
            ->count();
            $series = $fund_codes->code. '-' . $year . '-' . $month . '-';

              if($count < 9) {
                $series .= '000' . ($count + 1);
            } else if($count < 99) {
                $series .= '00' . ($count + 1);
            } else if($count < 999) {
                $series .= '0' . ($count + 1);
            } else {
                $series .= ($count + 1);
            }
            $voucher = AcctgVoucher::create([ 
                'payee_id' => $payee,
                'fund_code_id' => $obr->fund_code_id,
                'voucher_no' => $series,
                'is_payables' => 2,
                'remarks' => $remarks,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
            AcctgVoucherSeries::create([
                'fund_code_id' => $obr->fund_code_id,
                'voucher_id' => $voucher->id,
                'series' => $series,
                'created_at' => $timestamp,
                'created_by' => $user
            ]);
        }


        return $voucher;
    }

    
}