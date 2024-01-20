<?php

namespace App\Repositories;

use App\Interfaces\CtoReplenishInterface;
use App\Models\CtoReplenish;
use App\Models\CtoReplenishDetail;
use App\Models\CtoDisburse;
use App\Models\CtoDisburseDetail;
use App\Models\AcctgVoucher;
use App\Models\CboAllotmentObligation;
use App\Models\CboAllotmentObligationRequest;
use App\Models\CboAllotmentBreakdown;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgFundCode;
use App\Models\GsoUnitOfMeasurement;
use App\Models\UserAccessApprovalApprover;
use App\Models\HrEmployee;
use App\Models\User;
use App\Models\RptLocality;
use DB;

class CtoReplenishRepository implements CtoReplenishInterface 
{
    public function find($id) 
    {
        return CtoReplenish::findOrFail($id);
    }

    public function create(array $details) 
    {
        return CtoReplenish::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CtoReplenish::whereId($id)->update($newDetails);
    }

    public function check_if_exist($replenishID, $disburseID)
    {
        return CtoReplenishDetail::where(['replenish_id' => $replenishID, 'disburse_id' => $disburseID])->get();
    }

    public function find_line($id) 
    {
        return CtoReplenishDetail::findOrFail($id);
    }

    public function create_line(array $details) 
    {
        return CtoReplenishDetail::create($details);
    }

    public function update_line($id, array $newDetails) 
    {
        return CtoReplenishDetail::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'cto_replenish.id',
            1 => 'cto_replenish.control_no',
            2 => 'cto_replenish.particulars',
            3 => 'cto_replenish.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_replenish.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoReplenish::select([
            'cto_replenish.*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_replenish.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_replenish.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_replenish.particulars', 'like', '%' . $keywords . '%');
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

    public function line_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'cto_replenish_details.id',
            1 => 'cto_disburse.control_no',
            2 => 'cto_disburse.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_replenish_details.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoReplenishDetail::select([
            'cto_replenish_details.*'
        ])
        ->leftJoin('cto_replenish', function($join)
        {
            $join->on('cto_replenish.id', '=', 'cto_replenish_details.replenish_id');
        })
        ->leftJoin('cto_disburse', function($join)
        {
            $join->on('cto_disburse.id', '=', 'cto_replenish_details.disburse_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_disburse.control_no', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'cto_replenish.id' => $id,
            'cto_replenish_details.is_active' => 1
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function view_available_disbursements($replenishID)
    {
        $res = CtoDisburse::select([
            '*'
        ])
        ->where('status', 'completed')
        ->where('is_replenished', '=', 0)
        ->get();

        return $res;
    }

    public function computeTotalAmount($replenishID)
    {
        $totalAmt = CtoReplenishDetail::
        leftJoin('cto_disburse', function($join)
        {
            $join->on('cto_disburse.id', '=', 'cto_replenish_details.disburse_id');
        })
        ->where([
            'cto_replenish_details.replenish_id' => $replenishID, 
            'cto_replenish_details.is_active' => 1
        ])
        ->sum('cto_disburse.total_amount');
        CtoReplenish::whereId($replenishID)->update(['total_amount' => $totalAmt]);

        return floatval($totalAmt);
    }

    public function generate()
    {
        $year       = date('Y'); 
        $count      = CtoReplenish::whereYear('created_at', '=', $year)->count();
        $controlNo  = 'REPLENISH-';
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

    public function fetchBudgetSeriesNo($id = '', $approvedAt = '')
    {   
        if ($id != '') {
            $res = CboAllotmentObligation::find($id);
            if ($approvedAt !== '') {
                if ($res->budget_no == NULL) {
                    $series = '';
                    $count  = CboAllotmentObligation::where('approved_by', '!=', NULL)->count();

                    if($count < 9) {
                        $series .= '0000' . ($count + 1);
                    } else if($count < 99) {
                        $series .= '000' . ($count + 1);
                    } else if($count < 999) {
                        $series .= '00' . ($count + 1);
                    } else if($count < 9999) {
                        $series .= '0' . ($count + 1);
                    } else {
                        $series .= ($count + 1);
                    }
                    $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($approvedAt)) . '-' . date('m', strtotime($approvedAt)) . '-' . $series;
                    $res->alobs_control_no = $alobNo;
                    $res->budget_no = $series;
                    $res->update();
                    return $alobNo;
                } else {
                    $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($approvedAt)) . '-' . date('m', strtotime($approvedAt)) . '-' . $res->budget_no;
                    $res->alobs_control_no = $alobNo;
                    $res->update();
                    return $alobNo;
                }
            }
            $alobNo = $res->fund_code->code . '-' . date('Y', strtotime($res->approved_at)) . '-' . date('m', strtotime($res->approved_at)) . '-' . $res->budget_no;
            $res->alobs_control_no = $alobNo;
            $res->update();
            return $alobNo;
        } else {
            $series = '';
            $count  = CboAllotmentObligation::where('approved_by', '>', 0)->count();

            if($count < 9) {
                $series .= '0000' . ($count + 1);
            } else if($count < 99) {
                $series .= '000' . ($count + 1);
            } else if($count < 999) {
                $series .= '00' . ($count + 1);
            } else if($count < 9999) {
                $series .= '0' . ($count + 1);
            }  else {
                $series .= ($count + 1);
            }
            return $series;
        }
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
    
    public function replenish($replenishID, $timestamp, $user, $details2, $auto = 0)
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
        
        $disburse = CtoDisburse::whereIn('id',
            CtoReplenishDetail::select('disburse_id')
            ->where([
                'replenish_id' => $replenishID,
                'is_active' => 1
            ])      
        )->update($details2);

        $replenish = CtoReplenish::find($replenishID);
        $res = CboAllotmentBreakdown::select([
            '*', 
            'cbo_allotment_obligations.id as identity',
            DB::raw('SUM(cbo_allotment_breakdowns.amount) as totalAmt'),
            'cto_disburse.voucher_id as voucher',
            'cbo_allotment_obligations.fund_code_id as fund',
            'cbo_allotment_breakdowns.gl_account_id as gl_account'
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
        })
        ->leftJoin('cto_disburse_details', function($join)
        {
            $join->on('cto_disburse_details.obligation_id', '=', 'cbo_allotment_obligations.id');
        })
        ->leftJoin('cto_disburse', function($join)
        {
            $join->on('cto_disburse.id', '=', 'cto_disburse_details.disburse_id');
        })
        ->leftJoin('cto_replenish_details', function($join)
        {
            $join->on('cto_replenish_details.disburse_id', '=', 'cto_disburse.id');
        })
        ->where([
            'cto_replenish_details.replenish_id' => $replenishID, 
            'cto_replenish_details.is_active' => 1,
        ])
        ->groupBy([
            'cbo_allotment_obligations.department_id', 
            'cbo_allotment_obligations.division_id', 
            'cto_disburse.voucher_id'
        ])
        ->get();

        $fundID = 0; $voucher = 0;
        if ($res->count() > 0) {
            foreach ($res as $r) {
                // AcctgAccountPayable::create([
                //     'voucher_id' => $r->voucher,
                //     'fund_code_id' => $r->fund,
                //     'gl_account_id' => $r->gl_account,
                //     'sl_account_id' => NULL,
                //     'trans_no' => $replenish->control_no,
                //     'trans_type' => $r->obligation->type->name,
                //     'trans_id' => $replenishID,
                //     'vat_type' => 'Non-Vatable',
                //     'responsibility_center' => $r->obligation->department->code.''.$r->obligation->division->code,
                //     'remarks' => $replenish->particulars,
                //     'items' => $r->obligation->particulars,
                //     'quantity' => 1,
                //     'uom_id' => $uom,
                //     'amount' => $r->totalAmt,
                //     'total_amount' => $r->totalAmt,
                //     'due_date' => date('Y-m-d', strtotime($timestamp)),
                //     'created_at' => $timestamp,
                //     'created_by' => $user
                // ]);
                $fundID = $r->fund;
                $voucher = $r->voucher;
            }
        }
        AcctgAccountDisbursement::create([
            'voucher_id' => $voucher,
            'gl_account_id' => NULL,
            'payment_type_id' => NULL,
            'disburse_type_id' => 3,
            'payment_date' => date('Y-m-d', strtotime($timestamp)),
            'amount' => $replenish->total_amount,
            'status' => 'draft',
            'sent_at' => $timestamp,
            'sent_by' => $user,
            'approved_at' => $timestamp,
            'approved_by' => $user,
            'reference_no' => $replenish->control_no
        ]);
        $this->update_vouchers($voucher);
        $this->generateOBR($fundID, $replenish, $timestamp, $user, $auto);

        return true;
    }

    public function generateOBR($fundID, $replenish, $timestamp, $user, $auto)
    {
        $fund = AcctgFundCode::find($fundID);
        $series = $this->fetchBudgetSeriesNo();
        $budgetYear = date('Y', strtotime($timestamp));
        $budgetControlNo =  $this->generateBudgetControlNo($budgetYear);
        $alobNo = $fund->code . '-' . date('Y', strtotime($timestamp)) . '-' . date('m', strtotime($timestamp)) . '-' . $series;
        $users = User::find($replenish->created_by);   
        $allotment = CboAllotmentObligation::create([
            'obligation_type_id' => 6,
            'department_id' => $users->hr_employee->acctg_department_id,
            'division_id' => $users->hr_employee->acctg_department_division_id,
            'employee_id' => $users->hr_employee->id,
            'designation_id' => $users->hr_employee->hr_designation_id,
            'fund_code_id' => $fundID,
            'date_requested' => date('Y-m-d', strtotime($timestamp)),
            'budget_year' => $budgetYear,
            'budget_control_no' => $budgetControlNo,
            'budget_no' => $series,
            'alobs_control_no' => $alobNo,
            'total_amount' => $replenish->total_amount,
            'address' => $users->hr_employee->current_address,
            'particulars' => $replenish->particulars,
            'status' => 'completed',
            'sent_at' => $timestamp,
            'sent_by' => $user,
            'created_at' => $timestamp,
            'created_by' => $user,
            'approved_at' => $timestamp,
            'approved_by' => ($auto > 0) ? $user : $replenish->approved_by
        ]);
        $locality = RptLocality::select('*')->where(['loc_local_name' => 'Palayan City', 'department' => 4])->get();
        if ($locality->count() > 0) {
            $locality = $locality->first();
            $budget_officer = $locality->loc_budget_officer_id;
            $budget_officer_designation = $locality->loc_budget_officer_position;
            $treasurer = $locality->loc_treasurer_id;
            $treasurer_designation = $locality->loc_treasurer_position;
            $mayor = $locality->loc_mayor_id;
            $mayor_designation = 'City Mayor';
        } else {
            $budget_officer = NULL;
            $budget_officer_designation = NULL;
            $treasurer = NULL;
            $treasurer_designation = NULL;
            $mayor = NULL;
            $mayor_designation = NULL;
        }
        CboAllotmentObligationRequest::create([
            'allotment_id' => $allotment->id,
            'status' => 'completed',
            'budget_officer_id' => $budget_officer,
            'budget_officer_designation' => $budget_officer_designation,
            'treasurer_id' => $treasurer,
            'treasurer_designation' => $treasurer_designation,
            'mayor_id' => $mayor,
            'mayor_designation' => $mayor_designation,
            'created_at' => $timestamp,
            'created_by' => $user,
            'approved_at' => $timestamp,
            'approved_by' => ($auto > 0) ? $user : $replenish->approved_by
        ]);

        $replenish = CtoReplenish::find($replenish->id);
        $replenish->allotment_id = $allotment->id;
        $replenish->update();

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
                        $query .= '((cto_replenish.approved_counter >= 1';
                    } else {
                        $query .= ' OR (cto_replenish.approved_counter >= 1';
                    }
                    $query .= ' AND cto_replenish.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_replenish.approved_counter >= 2';
                    } else {
                        $query .= '((cto_replenish.approved_counter >= 2';
                    }
                    $query .= ' AND cto_replenish.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_replenish.approved_counter >= 3';
                    } else {
                        $query .= '((cto_replenish.approved_counter >= 3';
                    }
                    $query .= ' AND cto_replenish.department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_replenish.approved_counter >= 4';
                    } else {
                        $query .= '((cto_replenish.approved_counter >= 4';
                    }
                    $query .= ' AND cto_replenish.department_id = '.$r->department_id.')';
                    $q++;
                }           
                $iteration++;
            }
            if ($query) {
                $query .= ' AND cto_replenish.status != "draft")';
            } else {
                $query .= 'cto_replenish.status != "draft"';
            }
        } else {
            $query .= 'cto_replenish.status != "draft"';
        }

        $columns = array( 
            0 => 'cto_replenish.id',
            1 => 'cto_replenish.control_no'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_replenish.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoReplenish::select([
            'cto_replenish.*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_replenish.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_replenish.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_replenish.particulars', 'like', '%' . $keywords . '%');
            }
        })
        ->whereRaw($query)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }
}