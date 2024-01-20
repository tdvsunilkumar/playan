<?php

namespace App\Repositories;

use App\Interfaces\CtoCollectionInterface;
use App\Models\CtoCashier;
use App\Models\CtoCashierDetail;
use App\Models\CtoCollection;
use App\Models\CtoCollectionDetail;
use App\Models\CtoDenominationBill;
use App\Models\CtoPaymentOrRegister;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgFundCode;
use App\Models\User;
use App\Models\UserAccessApprovalApprover;
use App\Models\AcctgVoucherSeries;
use App\Models\AcctgVoucher;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgAccountDisbursement;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountDeduction;
use App\Models\CboPayee;
use App\Models\CtoCashierIncome;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountSubsidiaryLedger;
use DB;

class CtoCollectionRepository implements CtoCollectionInterface 
{
    public function find($id) 
    {
        return CtoCollection::findOrFail($id);
    }

    public function validate($trans_date, $officer, $fund_code, $id = '')
    {   
        if ($id !== '') {
            return CtoCollection::where([
                    'trans_date' => date('Y-m-d', strtotime($trans_date)), 
                    'officer_id' => $officer,
                    'fund_code_id' => $fund_code
                ])
                ->where('id', '!=', $id)->count();
        } 
        return CtoCollection::where([
                'trans_date' => date('Y-m-d', strtotime($trans_date)), 
                'officer_id' => $officer,
                'fund_code_id' => $fund_code
            ])
            ->count();
    }

    public function get($id) 
    {
        return CtoCollection::whereId($id)->get();
    }
    
    public function create(array $details) 
    {
        return CtoCollection::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return CtoCollection::whereId($id)->update($newDetails);
    }

    public function create_details(array $details) 
    {
        return CtoCollectionDetail::create($details);
    }

    public function update_details($id, array $newDetails) 
    {
        return CtoCollectionDetail::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_fund_codes.code',
            1 => 'cto_collections.trans_no',
            2 => 'cto_collections.trans_date',
            3 => 'users.name',
            4 => 'cto_collections.total_amount',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_collections.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoCollection::select([
            'cto_collections.*'
        ])
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cto_collections.fund_code_id');
        })
        ->leftJoin('users', function($join)
        {
            $join->on('users.id', '=', 'cto_collections.officer_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_collections.trans_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.trans_date', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('users.name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
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
                        $query .= '((cto_collections.approved_counter >= 1';
                    } else {
                        $query .= ' OR (cto_collections.approved_counter >= 1';
                    }
                    $query .= ' AND hr_employees.acctg_department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->second > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_collections.approved_counter >= 2';
                    } else {
                        $query .= '((cto_collections.approved_counter >= 2';
                    }
                    $query .= ' AND hr_employees.acctg_department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->third > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_collections.approved_counter >= 3';
                    } else {
                        $query .= '((cto_collections.approved_counter >= 3';
                    }
                    $query .= ' AND hr_employees.acctg_department_id = '.$r->department_id.')';
                    $q++;
                }
                if ($r->fourth > 0) {
                    if ($q > 0) {
                        $query .= ' OR (cto_collections.approved_counter >= 4';
                    } else {
                        $query .= '((cto_collections.approved_counter >= 4';
                    }
                    $query .= ' AND hr_employees.acctg_department_id = '.$r->department_id.')';
                    $q++;
                }           
                $iteration++;
            }
        }

        $columns = array( 
            0 => 'cto_collections.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_collections.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = CtoCollection::select([
            'cto_collections.*'
        ])
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('acctg_fund_codes.id', '=', 'cto_collections.fund_code_id');
        })
        ->leftJoin('users', function($join)
        {
            $join->on('users.id', '=', 'cto_collections.officer_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.user_id', '=', 'users.id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_collections.trans_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.trans_date', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('users.name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_collections.status', '!=', 'draft')
        ->orderBy($column, $order);
        $count = $res->count();
        if ($limit != -1) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }
        
        return (object) array('count' => $count, 'data' => $res);
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

    public function fetchApprovedBy($approvers)
    {
        $results = User::whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->name);
        }

        return implode(', ', $arr);
    }

    public function transaction_listItems($request, $id)
    {
        $columns = array( 
            0 => 'cto_cashier_income.id',
            1 => 'cto_cashier_income.or_no',
            2 => 'cto_cashier_income.taxpayer_name',
            3 => 'cto_cashier_income.form_code',
            4 => 'cto_cashier_income.amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_cashier_income.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $officer   = $request->get('officer');
        $transdate = $request->get('transaction_date');
        $fund_code = $request->get('fund');
        $collectionx = CtoCollection::find($id);
            
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
            'cto_cashier_income.id as identity',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_cashier_income.cashier_or_date', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.or_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.taxpayer_name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.form_code', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.amount', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        if($id > 0 && $collectionx->data_collections != NULL) {
            $res->whereIn('cto_cashier_income.id', explode(',', $collectionx->data_collections));
        } else {
            $res->where('cto_cashier_income.is_collected', 0);
            if ($fund_code) {
                $res->where('cto_cashier_income.fund_id', '=', $fund_code);
            }
            if ($transdate) {
                $res->where('cto_cashier_income.cashier_or_date', '<=', $transdate);
            }
            if($officer) {
                $res->where('cto_cashier_income.created_by', $officer);
            } else {
                $res->where('cto_cashier_income.created_by', 'xXx');
            }
        }
        $arr = array();
        $collections = $res->get();
        if (!empty($collections)) {
            foreach($collections as $collection) {
                $arr[] = $collection->identity;
            }
        }
        if ($limit != -1) {
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $count = $res->count();
            $res   = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res, 'collections' => $arr);
    }

    public function old_transaction_listItems($request, $id)
    {
        $columns = array( 
            0 => 'cto_cashier.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_cashier.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $officer   = $request->get('officer');
        $transdate = $request->get('transaction_date');
        $collectionx = CtoCollection::find($id);
            
        $res = CtoCashier::select([
            'cto_cashier.*',
            'cto_cashier.id as identity',
            DB::raw('CONCAT(cto_payment_or_registers.cpor_series) as formcode'),
            DB::raw('CONCAT(acctg_account_subsidiary_ledgers.code," - ", acctg_account_subsidiary_ledgers.description) as sl_account'),
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier.or_register_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier.tax_credit_sl_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_collections.trans_no', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.trans_date', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_fund_codes.description', 'like', '%' . $keywords . '%')
                ->orWhere('users.name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_collections.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_cashier.status', 1)
        ->orderBy($column, $order);
        if($id > 0 && $collectionx->data_collections != NULL) {
            $res->whereIn('cto_cashier.id', explode(',', $collectionx->data_collections));
        } else {
            $res->where('cto_cashier.is_collected', 0);
            if ($transdate) {
                $res->where('cto_cashier.cashier_or_date', '<=', $transdate);
            }
            if($officer) {
                $res->where('cto_cashier.created_by', $officer);
            } else {
                $res->where('cto_cashier.created_by', 'xXx');
            }
        }
        $arr = array();
        $collections = $res->get();
        if (!empty($collections)) {
            foreach($collections as $collection) {
                $arr[] = $collection->identity;
            }
        }
        if ($limit != -1) {
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $count = $res->count();
            $res   = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res, 'collections' => $arr);
    }

    public function receipt_listItems($request, $id)
    {
        $columns = array( 
            0 => 'cto_cashier_income.form_code',
            1 => 'cto_payment_cashier_system.pcs_name',
            2 => 'cto_cashier_income.or_from',
            3 => 'cto_cashier_income.or_to',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_cashier_income.form_code' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $officer   = $request->get('officer');
        $transdate = $request->get('transaction_date');
        $fund_code = $request->get('fund');
        $collectionx = CtoCollection::find($id);
            
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
            'cto_payment_cashier_system.pcs_name as or_dept',    
            DB::raw('SUM(CASE WHEN cto_cashier_income.is_discount = 1 THEN cto_cashier_income.amount ELSE 0 END) as total_discount'),   
            DB::raw('SUM(CASE WHEN cto_cashier_income.is_discount = 0 THEN cto_cashier_income.amount ELSE 0 END) as total_amount'), 
        ])
        ->leftJoin('cto_payment_cashier_system', function($join)
        {
            $join->on('cto_payment_cashier_system.id', '=', 'cto_cashier_income.tfoc_is_applicable');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_cashier_income.form_code', 'like', '%' . $keywords . '%')
                ->orWhere('cto_payment_cashier_system.pcs_name', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.or_from', 'like', '%' . $keywords . '%')
                ->orWhere('cto_cashier_income.or_to', 'like', '%' . $keywords . '%');
            }
        })
        ->groupBy(['cto_cashier_income.or_register_id'])
        ->orderBy($column, $order);
        if($id > 0 && $collectionx->data_collections != NULL) {
            $res->whereIn('cto_cashier_income.id', explode(',', $collectionx->data_collections));
        } else {
            $res->where('cto_cashier_income.is_collected', 0);
            if ($fund_code) {
                $res->where('cto_cashier_income.fund_id', '=', $fund_code);
            }
            if ($transdate) {
                $res->where('cto_cashier_income.cashier_or_date', '<=', $transdate);
            }
            if($officer) {
                $res->where('cto_cashier_income.created_by', $officer);
            } else {
                $res->where('cto_cashier_income.created_by', 'xXx');
            }
        }
        $total = 0; $discount = 0;
        $totals = $res->get();
        foreach($totals as $totalx) {
            $total += floatval($totalx->total_amount);
            $discount += floatval($totalx->total_discount);
        }
        if ($limit != -1) {
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $count = $res->count();
            $res   = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res, 'total' => floatval($total), 'discount' => floatval($discount));
    }

    public function old_receipt_listItems($request, $id)
    {
        $columns = array( 
            0 => 'cto_payment_or_registers.cpor_series',
            2 => 'cto_payment_or_registers.ora_from',
            3 => 'cto_payment_or_registers.ora_to'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'cto_payment_or_registers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $officer   = $request->get('officer');
        $transdate = $request->get('transaction_date');
        $collectionx = CtoCollection::find($id);
            
        $res = CtoPaymentOrRegister::select([
            'cto_payment_or_registers.*',
            // 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 
            // 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous
            DB::raw('(CASE 
            WHEN cto_payment_or_types.or_is_applicable = 1 THEN "Business Permit" 
            WHEN cto_payment_or_types.or_is_applicable = 2 THEN "Real Property"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Engineering"
            WHEN cto_payment_or_types.or_is_applicable = 4 THEN "Occupancy"
            WHEN cto_payment_or_types.or_is_applicable = 5 THEN "Planning & Development"
            WHEN cto_payment_or_types.or_is_applicable = 6 THEN "Health & Safety"
            WHEN cto_payment_or_types.or_is_applicable = 7 THEN "Community Tax"
            WHEN cto_payment_or_types.or_is_applicable = 8 THEN "Burial Permit"
            WHEN cto_payment_or_types.or_is_applicable = 9 THEN "Miscellaneous"
            ELSE "Econ And Investments" END) AS or_dept'),
            DB::raw('SUM(cto_cashier.total_amount) as total_amount'),
        ])
        ->leftJoin('cto_cashier', function($join)
        {
            $join->on('cto_cashier.or_register_id', '=', 'cto_payment_or_registers.id');
        })
        ->leftJoin('cto_payment_or_types', function($join)
        {
            $join->on('cto_payment_or_types.id', '=', 'cto_payment_or_registers.cpot_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_payment_or_registers.cpor_series', 'like', '%' . $keywords . '%')
                ->orWhere('cto_payment_or_registers.ora_from', 'like', '%' . $keywords . '%')
                ->orWhere('cto_payment_or_registers.ora_to', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_cashier.status', 1)
        ->groupBy(['cto_payment_or_registers.cpor_series'])
        ->orderBy($column, $order);
        if($id > 0 && $collectionx->data_collections != NULL) {
            $res->whereIn('cto_cashier.id', explode(',', $collectionx->data_collections));
        } else {
            $res->where('cto_cashier.is_collected', 0);
            if ($transdate) {
                $res->where('cto_cashier.cashier_or_date', '<=', $transdate);
            }
            if($officer) {
                $res->where('cto_cashier.created_by', $officer);
            } else {
                $res->where('cto_cashier.created_by', 'xXx');
            }
        }
        $total = 0;
        $totals = $res->get();
        foreach($totals as $totalx) {
            $total += floatval($totalx->total_amount);
        }
        if ($limit != -1) {
            $count = $res->count();
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $count = $res->count();
            $res   = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res, 'total' => $total);
    }
    
    public function get_or_receipts($collection)
    {
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
            'cto_payment_cashier_system.pcs_name as or_dept',    
            DB::raw('SUM(CASE WHEN cto_cashier_income.is_discount = 1 THEN cto_cashier_income.amount ELSE 0 END) as total_discount'),   
            DB::raw('SUM(CASE WHEN cto_cashier_income.is_discount = 0 THEN cto_cashier_income.amount ELSE 0 END) as total_amount'), 
        ])
        ->leftJoin('cto_payment_cashier_system', function($join)
        {
            $join->on('cto_payment_cashier_system.id', '=', 'cto_cashier_income.tfoc_is_applicable');
        })
        ->groupBy(['cto_cashier_income.or_register_id'])
        ->whereIn('cto_cashier_income.id', explode(',', $collection->data_collections))
        ->get();

        return $res;
    }

    public function old_get_or_receipts($collection)
    {
        $res = CtoPaymentOrRegister::select([
            'cto_payment_or_registers.*',
            // 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 
            // 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous
            DB::raw('(CASE 
            WHEN cto_payment_or_types.or_is_applicable = 1 THEN "Business Permit" 
            WHEN cto_payment_or_types.or_is_applicable = 2 THEN "Real Property"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Engineering"
            WHEN cto_payment_or_types.or_is_applicable = 4 THEN "Occupancy"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Planning & Development"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Health & Safety"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Community Tax"
            WHEN cto_payment_or_types.or_is_applicable = 3 THEN "Burial Permit"
            ELSE "Miscellaneous" END) AS or_dept'),
            DB::raw('SUM(cto_cashier.total_amount) as total_amount'),
        ])
        ->leftJoin('cto_cashier', function($join)
        {
            $join->on('cto_cashier.or_register_id', '=', 'cto_payment_or_registers.id');
        })
        ->leftJoin('cto_payment_or_types', function($join)
        {
            $join->on('cto_payment_or_types.id', '=', 'cto_payment_or_registers.cpot_id');
        })
        ->where('cto_cashier.status', 1)
        ->groupBy(['cto_payment_or_registers.cpor_series'])
        ->whereIn('cto_cashier.id', explode(',', $collection->data_collections))
        ->get();

        return $res;
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allCtoOfficers()
    {
        return (new User)->allCtoOfficers();
    }

    public function get_denominations($id)
    {   
        if ($id > 0) {
            $res = CtoCollectionDetail::where(['collection_id' => $id, 'is_active' => 1])->get();
            return $res = $res->map(function($item) {
                return (object) [
                    'id' => $item->denomination->id,
                    'name' => $item->denomination->name,
                    'multiplier' => floatval($item->denomination->code),
                    'counter' => $item->counter ? $item->counter : '',
                    'amount' => $item->counter ? floatval($item->counter) * floatval($item->denomination->code) : ''
                ];
            });
        } else {
            $res = CtoDenominationBill::where(['is_active' => 1])->get();
            return $res = $res->map(function($denomination) {
                return (object) [
                    'id' => $denomination->id,
                    'name' => $denomination->name,
                    'multiplier' => floatval($denomination->code),
                    'counter' => '',
                    'amount' => ''
                ];
            });
        }
    }

    public function get_denomination_value($collectionID, $value)
    {   
        $res = CtoCollectionDetail::select(['cto_collections_details.*'])
        ->leftJoin('cto_denomination_bills', function($join)
        {
            $join->on('cto_denomination_bills.id', '=', 'cto_collections_details.denomination_id');
        })
        ->where([
            'cto_denomination_bills.description' => $value, 
            'cto_collections_details.collection_id' => $collectionID, 
            'cto_collections_details.is_active' => 1
        ])->get();
        if ($res->count() > 0) {
            $res = $res->map(function($item) {
                return (object) [
                    'id' => $item->denomination->id,
                    'name' => $item->denomination->name,
                    'multiplier' => floatval($item->denomination->code),
                    'counter' => $item->counter ? $item->counter : '',
                    'amount' => $item->counter ? floatval($item->counter) * floatval($item->denomination->code) : ''
                ];
            });
        } else {
            $res = CtoDenominationBill::select(['cto_denomination_bills.*'])
            ->where([
                'cto_denomination_bills.description' => $value, 
            ])->get();
            $res = $res->map(function($item) {
                return (object) [
                    'id' => $item->id,
                    'name' => $item->name,
                    'multiplier' => floatval($item->code),
                    'counter' => '',
                    'amount' => ''
                ];
            });
        }

        return $res->first();
    }

    public function generate()
    {
        $year     = date('Y'); 
        $count    = CtoCollection::whereYear('created_at', '=', $year)->count();
        $transNo  = '';
        $transNo .= $year . '-';

        if($count < 9) {
            $transNo .= '000' . ($count + 1);
        } else if($count < 99) {
            $transNo .= '00' . ($count + 1);
        } else if($count < 999) {
            $transNo .= '0' . ($count + 1);
        } else {
            $transNo .= ($count + 1);
        }
        return $transNo;
    }

    public function update_collections($collectionDetails, $collections, $id)
    {
        if ($id > 0) {
            if (!($collectionDetails['is_collected'] > 0)) {
                CtoCollection::whereId($id)
                ->update([
                    'officer_id' => NULL,
                    'data_collections' => NULL, 
                    'total_amount' => 0
                ]);
            } 
        } 
        if ($collections != NULL ) {
            CtoCashier::whereIn('id', explode(',', $collections))
            ->update([
                'is_collected' => $collectionDetails['is_collected']
            ]);
            return CtoCashierIncome::whereIn('id', explode(',', $collections))
            ->update([
                'is_collected' => $collectionDetails['is_collected']
            ]);
        }
    }

    public function check_if_details_exist($id, $billId) 
    {
        return CtoCollectionDetail::where(['collection_id' => $id, 'denomination_id' => $billId])->get();
    }

    public function generate_voucher($collection, $timestamp, $user)
    {   
        $res = AcctgVoucherSeries::where(['voucher_id' => NULL, 'fund_code_id' => $collection->fund_code_id])->get();
        if ($res->count() > 0) {
            $series = $res->first()->series;
        } else {
            $fund_codes = AcctgFundCode::find($collection->fund_code_id);
            $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

            $count = AcctgVoucher::whereYear('created_at', '=',  $year)
            ->where(['fund_code_id' => $collection->fund_code_id])
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
        }
        $payeeRes = CboPayee::where('hr_employee_id', '=', $collection->employee->id)->get();
        $payee = ($payeeRes->count() > 0) ? $payeeRes->first()->id : NULL;
        $voucher = AcctgVoucher::create([ 
            'payee_id' => $payee,
            'fund_code_id' => $collection->fund_code_id,
            'voucher_no' => $series,
            'is_payables' => 0,
            'remarks' => 'COLLECTIONS FROM TRANSACTION ('.$collection->trans_no.') DATED '.strtoupper(date("d-M-Y", strtotime($collection->trans_date))).'.',
            'created_at' => $timestamp,
            'created_by' => $user
        ]);

        $res = AcctgVoucherSeries::where(['voucher_id' => $voucher->id]);
        if ($res->count() > 0) {
            $res = AcctgVoucherSeries::find($res->first()->id);
            $res->fund_code_id = $collection->fund_code_id;
            $res->series = $series;
            $res->updated_at = $timestamp;
            $res->updated_by = $user;
            $res->update();
        } else {
            $res1 = AcctgVoucherSeries::where(['voucher_id' => NULL, 'fund_code_id' => $collection->fund_code_id, 'series' => $series])->get();
            if ($res1->count() > 0) {
                $res1 = AcctgVoucherSeries::find($res1->first()->id);
                $res1->voucher_id = $voucher->id;
                $res1->updated_at = $timestamp;
                $res1->updated_by = $user;
                $res1->update();
            } else {
                AcctgVoucherSeries::create([
                    'fund_code_id' => $collection->fund_code_id,
                    'voucher_id' => $voucher->id,
                    'series' => $series,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        }

        $trans = CtoCashierIncome::select([
            'cto_cashier_income.*',
            'cto_cashier_income.id as identity',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->whereIn('cto_cashier_income.id', explode(',', $collection->data_collections))
        ->get();

        $lot = GsoUnitOfMeasurement::select(['id'])->where(['is_lot' => 1, 'is_active' => 1])->get();
        if ($lot->count() > 0) {
            $lot = $lot->first()->id;
        } else {
            $lot = NULL;
        }
        $incomeAmt = 0; $deductionAmt = 0;
        if ($trans->count() > 0) {
            foreach ($trans as $tran) {    
                $particulars = ucwords(strtolower($tran->taxpayer_name.' '.($tran->cashier ? $tran->cashier->cashier_particulars : '')));
                if ($tran->is_discount > 0) {
                    $deduction = AcctgAccountDeduction::create([
                        'voucher_id' => $voucher->id,
                        'payee_id' => $payee,
                        'fund_code_id' => $tran->fund_id,
                        'gl_account_id' => $tran->gl_account_id,
                        'sl_account_id' => $tran->sl_account_id,
                        'trans_no' => $tran->or_no,
                        'trans_type' => 'Collections',
                        'trans_id' => $tran->id,
                        'responsibility_center' => '',
                        'remarks' => $tran->form_code,
                        'items' => $particulars,
                        'quantity' => 1,
                        'uom_id' => $lot,
                        'amount' => $tran->amount,
                        'total_amount' => $tran->amount,
                        'due_date' => $tran->cashier_or_date,
                        'vat_type' => 'Non-Vatable',
                        'ewt_id' => NULL,
                        'ewt_amount' => NULL,
                        'evat_id' => NULL,
                        'evat_amount' => NULL,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                    $deductionAmt += floatval($tran->amount);
                }  else {
                    $incomes = AcctgAccountIncome::create([
                        'voucher_id' => $voucher->id,
                        'payee_id' => $payee,
                        'fund_code_id' => $tran->fund_id,
                        'gl_account_id' => $tran->gl_account_id,
                        'sl_account_id' => $tran->sl_account_id,
                        'trans_no' => $tran->or_no,
                        'trans_type' => 'Collections',
                        'trans_id' => $tran->id,
                        'responsibility_center' => '',
                        'remarks' => $tran->form_code,
                        'items' => $particulars,
                        'quantity' => 1,
                        'uom_id' => $lot,
                        'amount' => $tran->amount,
                        'total_amount' => $tran->amount,
                        'due_date' => $tran->cashier_or_date,
                        'vat_type' => 'Non-Vatable',
                        'ewt_id' => NULL,
                        'ewt_amount' => NULL,
                        'evat_id' => NULL,
                        'evat_amount' => NULL,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                    $incomeAmt += floatval($tran->amount);
                }
            }
        }

        AcctgVoucher::whereId($voucher->id)->update([ 
            'total_payables' => $incomeAmt,
            'total_disbursement' => $collection->total_amount,
            'total_deductions' => $deductionAmt
        ]);

        $treasuryGL = AcctgAccountGeneralLedger::where(['is_treasury' => 1])->get();
        if ($treasuryGL->count() > 0) {
            $treasuryGL = $treasuryGL->first()->id;
            $treasurySL = AcctgAccountSubsidiaryLedger::where(['gl_account_id' => $treasuryGL, 'is_treasury' => 1])->get();
            if ($treasurySL->count() > 0) {
                $treasurySL = $treasurySL->first()->id;
            } else {
                $treasurySL = NULL;
            }
        } else {
            $treasuryGL = NULL;
            $treasurySL = NULL;
        }
        AcctgAccountDisbursement::create([
            'voucher_id' => $voucher->id,
            'gl_account_id' => $treasuryGL,
            'sl_account_id' => $treasurySL,
            'payment_type_id' => 1,
            'disburse_type_id' => 4,
            'payment_date' => date('Y-m-d', strtotime($timestamp)),
            'amount' => $collection->total_amount,
            'status' => 'draft',
            'reference_no' => $collection->trans_no
        ]);
    }

    public function get_details($transNo)
    {
        $collection = CtoCollection::where('trans_no', '=', $transNo)->first();
        $res = CtoCashierIncome::with(['barangay'])->select([
            'cto_cashier_income.*',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->where('cto_cashier_income.is_collected', 1)
        ->whereIn('cto_cashier_income.id', explode(',', $collection->data_collections))
        ->groupBy(['cto_cashier_income.or_no']);
        return $res->get();
    }

    public function get_breakdown_details($or_no)
    {
        $res = CtoCashierIncome::select([
            'cto_cashier_income.*',
        ])
        ->leftJoin('cto_payment_or_registers', function($join)
        {
            $join->on('cto_payment_or_registers.id', '=', 'cto_cashier_income.or_register_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_cashier_income.gl_account_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'cto_cashier_income.sl_account_id');
        })
        ->where('cto_cashier_income.is_collected', 1)
        ->where('cto_cashier_income.or_no', $or_no)
        ->get();
        return $res;
    }

    public function money_format($money)
    {
        return floor(($money*100))/100;
    }

    public function find_collection_via_column($transNo)
    {
        $res = CtoCollection::select('*')
        ->where([
            'trans_no' => $transNo,
        ])
        ->first();
        
        return $res;
    }

    public function find_collection_detail_via_column($collection_id)
    {
        $res = CtoCollectionDetail::select('*')
        ->leftJoin('cto_denomination_bills', function($join)
        {
            $join->on('cto_denomination_bills.id', '=', 'cto_collections_details.denomination_id');
        })
        ->where([
            'collection_id' => $collection_id,
        ])
        ->first();
        
        return $res;
    }
    
    public function get_deposits($transNo)
    {
        $res = AcctgAccountDisbursement::select('*')
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where('acctg_vouchers.remarks', 'like', '%' . $transNo . '%')
        ->get();

        return $res;
    }

    public function get_deposits_detail($transNo, $type)
    {   
        if ($type == 'check') {
            $res = AcctgAccountDisbursement::select('*')
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->where('acctg_vouchers.remarks', 'like', '%' . $transNo . '%')
            ->where('acctg_disbursements.cheque_date', '!=', NULL)
            ->sum('acctg_disbursements.amount');
        } else {
            $res = AcctgAccountDisbursement::select('*')
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->where('acctg_vouchers.remarks', 'like', '%' . $transNo . '%')
            ->where('acctg_disbursements.cheque_date', '=', NULL)
            ->sum('acctg_disbursements.amount');
        }

        return $res;
    }

    public function numberTowords(float $amount)
    {   
        $number = floatval($amount);
        $no = floor($number);
        $fraction = $number - $no;
        $hundred = null;
        $digits_1 = strlen($no); //to find lenght of the number
        $i = 0;
        // Numbers can stored in array format
        $str = array();

        $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');

        $digits = array('', 'Hundred', 'Thousand', 'Million', 'Billion');
        //Extract last digit of number and print corresponding number in words till num becomes 0
        while ($i < $digits_1)
        {
        $divider = ($i == 2) ? 10 : 100;
        //Round numbers down to the nearest integer
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i +=($divider == 10) ? 1 : 2;

        if ($number)
        {
        $plural = (($counter = count($str)) && $number > 9) ? '' : null;
        $hundred = ($counter == 1 && $str[0]) ? '' : null;
        $str [] = ($number < 21) ? $words[$number] . " " .
        $digits[$counter] .
        $plural . " " .
        $hundred: $words[floor($number / 10) * 10]. " " .
        $words[$number % 10] . " ".
        $digits[$counter] . $plural . " " .
        $hundred;
        }
        else $str[] = null;
        }

        $str = array_reverse($str);
        $result = implode('', $str); //Join array elements with a string
        if (($fraction) > 0) {
            return ' and '. (number_format($fraction,2) * 100) .'/100'.' pesos';
        }
        return 'pesos';
    }
}