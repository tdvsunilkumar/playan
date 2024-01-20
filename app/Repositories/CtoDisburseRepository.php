<?php

namespace App\Repositories;

use App\Interfaces\CtoDisburseInterface;
use App\Models\CtoDisburse;
use App\Models\CtoDisburseDetail;
use App\Models\AcctgVoucher;
use App\Models\AcctgDepartment;
use App\Models\CboAllotmentObligation;
use App\Models\CboAllotmentBreakdown;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountPayable;
use App\Models\GsoUnitOfMeasurement;
use App\Models\User;
use App\Models\UserAccessApprovalApprover;
use App\Models\CboPayee;
use DB;

class CtoDisburseRepository implements CtoDisburseInterface 
{
    public function find($id) 
    {
        return CtoDisburse::with(['payee'])->findOrFail($id);
    }

    public function create(array $details) 
    {
        return CtoDisburse::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CtoDisburse::whereId($id)->update($newDetails);
    }

    public function check_if_exist($disbursementID, $obrID)
    {
        return CtoDisburseDetail::where(['disburse_id' => $disbursementID, 'obligation_id' => $obrID])->get();
    }

    public function find_line($id) 
    {
        return CtoDisburseDetail::findOrFail($id);
    }

    public function create_line(array $details) 
    {
        return CtoDisburseDetail::create($details);
    }

    public function update_line($id, array $newDetails) 
    {
        return CtoDisburseDetail::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'cto_disburse.id',
            1 => 'cto_disburse.control_no',
            2 => 'acctg_vouchers.voucher_no',
            3 => 'cbo_payee.paye_name',
            4 => 'acctg_departments.name',
            5 => 'cto_disburse.particulars',
            6 => 'cto_disburse.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_disburse.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoDisburse::select([
            'cto_disburse.*'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'cto_disburse.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'cto_disburse.payee_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cto_disburse.department_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_disburse.id', 'like', '%' . $keywords . '%')
                ->orWhere('cto_disburse.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_payee.paye_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_disburse.particulars', 'like', '%' . $keywords . '%')
                ->orWhere('cto_disburse.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allPettyVouchers()
    {
        return (new AcctgVoucher)->allPettyVouchers();
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function line_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'cto_disburse_details.id',
            1 => 'cbo_allotment_obligations.alobs_control_no',
            2 => 'cbo_allotment_obligations.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_disburse_details.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoDisburseDetail::select([
            'cto_disburse_details.*'
        ])
        ->leftJoin('cto_disburse', function($join)
        {
            $join->on('cto_disburse.id', '=', 'cto_disburse_details.disburse_id');
        })
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cto_disburse_details.obligation_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_disburse.alobs_control_no', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'cto_disburse.id' => $id,
            'cto_disburse_details.is_active' => 1
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function view_available_obligation_requests($disbursementID, $department)
    {
        $res = CboAllotmentObligation::select([
            '*'
        ])
        ->where('department_id', '=', $department)
        ->where('departmental_request_id', '=', 0)
        ->where('is_attached', '=', 0)
        ->where('status', '=', 'completed')
        ->whereIn('obligation_type_id', [3,8])
        ->whereRaw('
            pr_status = CASE WHEN with_pr > 0 THEN "completed" WHEN with_pr <= 0 THEN "draft" END       
        ')
        ->get();

        return $res;
    }

    public function computeTotalAmount($disbursementID)
    {
        $totalAmt = CtoDisburseDetail::
        leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cto_disburse_details.obligation_id');
        })
        ->where([
            'cto_disburse_details.disburse_id' => $disbursementID, 
            'cto_disburse_details.is_active' => 1
        ])
        ->sum('cbo_allotment_obligations.total_amount');
        CtoDisburse::whereId($disbursementID)->update(['total_amount' => $totalAmt]);

        return floatval($totalAmt);
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = CtoDisburse::whereYear('created_at', '=', $year)->count();
        $controlNo  = 'PETTYCASH-';
        $controlNo .= substr($year, -2);

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

    public function reimburse($allotmentID, $timestamp, $user)
    {
        $glRes = AcctgAccountGeneralLedger::where(['is_petty_cash' => 1])->get();
        if ($glRes->count() > 0) {
            $gl_account = $glRes->first()->id;
        } else {
            $gl_account = 0;
        }

        $uomRes = GsoUnitOfMeasurement::where(['is_lot' => 1])->get();
        if ($uomRes->count() > 0) {
            $uom = $uomRes->first()->id;
        } else {
            $uom = 0;
        }

        $res = CboAllotmentObligation::select([
            'cbo_allotment_obligations.*'
        ])
        ->where([
            'cbo_allotment_obligations.id' => $allotmentID, 
            'cbo_allotment_obligations.is_active' => 1
        ])
        ->get();

        $payee = User::find($user)->hr_employee->payee_id;
        if ($res->count() > 0) {
            foreach ($res as $r) {
                foreach ($r->allotments as $allotment) {
                    AcctgAccountPayable::create([
                        'voucher_id' => NULL,
                        'payee_id' => $payee,
                        'fund_code_id' => $r->fund_code_id,
                        'gl_account_id' => $allotment->gl_account_id,
                        'sl_account_id' => NULL,
                        'trans_no' => $r->budget_control_no,
                        'trans_type' => $r->type->name,
                        'trans_id' => $r->id,
                        'responsibility_center' => $r->department->code.''.$r->division->code,
                        'remarks' => $r->particulars,
                        'items' => $r->particulars,
                        'quantity' => 1,
                        'uom_id' => $uom,
                        'amount' => $allotment->amount,
                        'total_amount' => $allotment->amount,
                        'due_date' => date('Y-m-d', strtotime($timestamp)),
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }
        return true;
    }

    public function disburse($disbursementID, $timestamp, $user)
    {
        $glRes = AcctgAccountGeneralLedger::where(['is_petty_cash' => 1])->get();
        if ($glRes->count() > 0) {
            $gl_account = $glRes->first()->id;
        } else {
            $gl_account = 0;
        }

        $uomRes = GsoUnitOfMeasurement::where(['is_lot' => 1])->get();
        if ($uomRes->count() > 0) {
            $uom = $uomRes->first()->id;
        } else {
            $uom = 0;
        }

        $disburse = CtoDisburse::find($disbursementID);
        $res = CtoDisburseDetail::with(['obligation'])->select([
            '*', 
            'cbo_allotment_obligations.id as identity',
            'cbo_allotment_obligations.total_amount as totalAmt'
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cto_disburse_details.obligation_id');
        })
        ->where([
            'cto_disburse_details.disburse_id' => $disbursementID, 
            'cto_disburse_details.is_active' => 1
        ])
        ->get();

        if ($res->count() > 0) {
            foreach ($res as $r) {
                foreach ($r->obligation->allotments as $allotment) {
                    AcctgAccountPayable::create([
                        'payee_id' => $disburse->payee_id,
                        'voucher_id' => $disburse->voucher_id,
                        'fund_code_id' => $r->fund_code_id,
                        'gl_account_id' => $allotment->gl_account_id,
                        'sl_account_id' => NULL,
                        'trans_no' => $disburse->control_no,
                        'trans_type' => $r->obligation->type->name,
                        'trans_id' => $r->identity,
                        'responsibility_center' => $r->obligation->department->code.''.$r->obligation->division->code,
                        'remarks' => $disburse->particulars,
                        'items' => $r->obligation->particulars,
                        'quantity' => 1,
                        'uom_id' => $uom,
                        'amount' => $allotment->amount,
                        'total_amount' => $allotment->amount,
                        'paid_amount' => $allotment->paid_amount,
                        'due_date' => date('Y-m-d', strtotime($timestamp)),
                        'created_at' => $timestamp,
                        'created_by' => $disburse->created_by
                    ]);
                }

                AcctgAccountDisbursement::create([
                    'disburse_no' => $disburse->disburse_no,
                    'voucher_id' => $disburse->voucher_id,
                    'gl_account_id' => $gl_account,
                    'payment_type_id' => 1,
                    'disburse_type_id' => 2,
                    'payment_date' => date('Y-m-d', strtotime($timestamp)),
                    'amount' => $r->totalAmt,
                    'status' => 'posted',
                    'sent_at' => $timestamp,
                    'sent_by' => $user,
                    'approved_at' => $timestamp,
                    'approved_by' => $user,
                    'posted_at' => $timestamp,
                    'posted_by' => $user,
                    'reference_no' => $disburse->control_no,
                    'created_at' => $timestamp,
                    'created_by' => $disburse->created_by
                ]);
            }
        }
        $this->update_vouchers($disburse->voucher_id);
        return true;
    }

    public function update_vouchers($voucherID)
    {
        $res = AcctgAccountPayable::select([
            '*',
            DB::raw('SUM(ewt_amount) as ewtAmt'),
            DB::raw('SUM(evat_amount) as evatAmt'),
            DB::raw('SUM(total_amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        $res2 = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        AcctgVoucher::whereId($voucherID)->update([
            'total_payables' => ($res->count() > 0) ? $res->first()->totalAmt : 0,
            'total_ewt' => ($res->count() > 0) ? $res->first()->ewtAmt : 0,
            'total_evat' => ($res->count() > 0) ? $res->first()->evatAmt : 0,
            'total_disbursement' => ($res2->count() > 0) ? $res2->first()->totalAmt : 0
        ]);

        return true;
    }

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }

    public function find_levels($slugs, $type)
    {   
        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->where(['menu_modules.slug' => $slugs])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->get();
        } else {
            $res = UserAccessApprovalApprover::select('user_access_approval_settings.levels')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'user_access_approval_settings.sub_module_id');
            })
            ->where(['menu_sub_modules.slug' => $slugs])
            ->get();
        }

        if ($res->count() > 0) {
            return intval($res->first()->levels);
        } else {
            return 'System Error';
        }
    }

    public function validate_approver($department, $sequence, $type, $slugs, $user)
    {   
        $query = '';
        if ($sequence == 1) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.primary_approvers)';
        } else if ($sequence == 2) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.secondary_approvers)';
        } else if ($sequence == 3) {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.tertiary_approvers)';
        } else {
            $query .= 'FIND_IN_SET('.$user.',user_access_approval_approvers.quaternary_approvers)';
        }

        if ($type == 'modules') { 
            $res = UserAccessApprovalApprover::select('*')
            ->leftJoin('user_access_approval_settings', function($join)
            {
                $join->on('user_access_approval_settings.id', '=', 'user_access_approval_approvers.setting_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'user_access_approval_settings.module_id');
            })
            ->whereRaw($query)
            ->where(['menu_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department])
            ->where('user_access_approval_settings.sub_module_id', NULL)
            ->count();
        } else {
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
            ->where(['menu_sub_modules.slug' => $slugs, 'user_access_approval_approvers.department_id' => $department ])
            ->count();
        }

        return $res;
    }

    public function approvals_listItems($request, $type, $slugs, $user)
    {   
        if ($type == 'modules') { 
            $res = DB::select( DB::raw("
            SELECT app.department_id, 
            CASE 
                WHEN (FIND_IN_SET($user,app.primary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'first',
            CASE 
                WHEN (FIND_IN_SET($user,app.secondary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'second',
            CASE 
                WHEN (FIND_IN_SET($user,app.tertiary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'third',
            CASE 
                WHEN (FIND_IN_SET($user,app.quaternary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'fourth'
            FROM
                `user_access_approval_approvers` as app
            LEFT JOIN user_access_approval_settings ON user_access_approval_settings.id = app.setting_id
            LEFT JOIN menu_modules ON menu_modules.id = user_access_approval_settings.module_id   
            WHERE menu_modules.slug = '$slugs' AND  user_access_approval_settings.sub_module_id IS NULL
            ") );
        } else {
            $res = DB::select( DB::raw("
            SELECT app.department_id, 
            CASE 
                WHEN (FIND_IN_SET($user,app.primary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'first',
            CASE 
                WHEN (FIND_IN_SET($user,app.secondary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'second',
            CASE 
                WHEN (FIND_IN_SET($user,app.tertiary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'third',
            CASE 
                WHEN (FIND_IN_SET($user,app.quaternary_approvers)>0) THEN '1'
                ELSE '0'
            END as 'fourth'
            FROM
                `user_access_approval_approvers` as app
            LEFT JOIN user_access_approval_settings ON user_access_approval_settings.id = app.setting_id
            LEFT JOIN menu_sub_modules ON menu_sub_modules.id = user_access_approval_settings.sub_module_id   
            WHERE menu_sub_modules.slug = '$slugs'
            ") );
        }

        $query = ''; $q = 0; $iteration = 0;
        if (!empty($res)) {
            foreach ($res as $r) {
                if ($r->first > 0) {
                    if ($q <= 0) {
                        $query .= '((cto_disburse.approved_counter >= 1';
                    } else {
                        $query .= ' OR (cto_disburse.approved_counter >= 1';
                    }
                    $query .= ' AND cto_disburse.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_disburse.approved_counter >= 2';
                    } else {
                        $query .= '((cto_disburse.approved_counter >= 2';
                    }
                    $query .= ' AND cto_disburse.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_disburse.approved_counter >= 3';
                    } else {
                        $query .= '((cto_disburse.approved_counter >= 3';
                    }
                    $query .= ' AND cto_disburse.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_disburse.approved_counter >= 4';
                    } else {
                        $query .= '((cto_disburse.approved_counter >= 4';
                    }
                    $query .= ' AND cto_disburse.department_id = '.$r->department_id.')';
                    $q++;
                }           
                $iteration++;
            }
            $query .= ' AND cto_disburse.status != "draft")';
        } else {
            $query .= 'cto_disburse.status != "draft"';
        }

        $columns = array( 
            0 => 'cto_disburse.id',
            1 => 'cto_disburse.control_no',
            2 => 'acctg_vouchers.voucher_no',
            3 => 'cbo_payee.paye_name',
            4 => 'acctg_departments.name',
            5 => 'cto_disburse.particulars',
            6 => 'cto_disburse.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_disburse.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoDisburse::select([
            'cto_disburse.*'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'cto_disburse.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'cto_disburse.payee_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'cto_disburse.department_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_disburse.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('cbo_payee.paye_name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_disburse.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_disburse.particulars', 'like', '%' . $keywords . '%');
            }
        })
        ->whereRaw($query)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function find_via_column($column, $data)
    {
        return CtoDisburse::where($column, $data)->first();
    }

    public function get_details($disbursementID)
    {
        return CtoDisburseDetail::where(['disburse_id' => $disbursementID, 'is_active' => 1])->get();
    }

    public function get_obligation_details($obligationID)
    {
        $res = CboAllotmentBreakdown::select(['cbo_allotment_breakdowns.*'])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
        })
        ->leftJoin('cto_disburse_details', function($join)
        {
            $join->on('cto_disburse_details.obligation_id', '=', 'cbo_allotment_obligations.id');
        })
        ->where([
            'cbo_allotment_breakdowns.is_active' => 1,
            'cbo_allotment_obligations.id' => $obligationID
        ])
        ->get();
        
        return $res;
    }
}