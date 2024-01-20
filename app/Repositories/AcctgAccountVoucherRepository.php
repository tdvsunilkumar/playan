<?php

namespace App\Repositories;

use App\Interfaces\AcctgAccountVoucherInterface;
use App\Models\AcctgFundCode;
use App\Models\AcctgBank;
use App\Models\AcctgVoucher;
use App\Models\AcctgAccountPayable;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgPaymentType;
use App\Models\GsoUnitOfMeasurement;
use App\Models\AcctgExpandedVatableTax;
use App\Models\AcctgExpandedWithholdingTax;
use App\Models\AcctgAccountDisbursement;
use App\Models\GsoPurchaseOrder;
use App\Models\CboPayee;
use App\Models\AcctgVoucherSeries;
use App\Models\CboAllotmentObligation;
use App\Models\BacRfqLine;
use App\Models\AcctgAccountIncome;
use App\Models\AcctgAccountDeduction;
use App\Models\User;
use App\Models\UserRoleSubModule;
use App\Models\AcctgVoucherDocument;
use App\Models\AcctgRptReceivableCY;
use App\Models\AcctgTrialBalance;
use App\Models\AcctgSLAccountReport;
use App\Models\AcctgGLAccountReport;
use DB;

class AcctgAccountVoucherRepository implements AcctgAccountVoucherInterface 
{
    public function find($id) 
    {
        return AcctgVoucher::findOrFail($id);
    }
    
    public function validate($transNo, $transType, $id = '')
    {   
        if ($id !== '') {
            return AcctgVoucher::where(['trans_no' => $transNo, 'trans_type' => $transType])->where('id', '!=', $id)->count();
        } 
        return AcctgVoucher::where(['trans_no' => $transNo, 'trans_type' => $transType])->count();
    }

    public function create(array $details) 
    {   
        return AcctgVoucher::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return AcctgVoucher::whereId($id)->update($newDetails);
    }

    public function listItems($request, $type = 1)
    {   
        $columns = array( 
            0 => 'acctg_vouchers.voucher_no',
            1 => 'cbo_payee.paye_name',
            2 => 'acctg_vouchers.remarks',
            3 => 'acctg_vouchers.total_payables',
            4 => 'acctg_vouchers.total_ewt',
            5 => 'acctg_vouchers.total_evat',
            6 => 'acctg_vouchers.total_disbursement'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_vouchers.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgVoucher::select([
            'acctg_vouchers.*',
            'acctg_vouchers.id as identity',
            'acctg_vouchers.status as identityStatus',
            'acctg_vouchers.total_payables as identityPayablesAmount',
            'acctg_vouchers.total_ewt as identityEWTAmount',
            'acctg_vouchers.total_evat as identityEVATAmount',
            'acctg_vouchers.total_disbursement as identityDisbursementAmount',
            'acctg_vouchers.total_deductions as identityDeduction',
            'acctg_vouchers.created_at as identityCreatedAt',
            'acctg_vouchers.created_by as identityCreatedBy',
            'acctg_vouchers.updated_at as identityUpdatedAt',
            'acctg_vouchers.updated_by as identityUpdatedBy',
        ])
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')  
                // ->orWhere('acctg_vouchers.remarks', 'like', '%' . $keywords . '%')    
                ->orWhere('cbo_payee.paye_name', 'like', '%' . $keywords . '%')   
                ->orWhere('acctg_vouchers.total_payables', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_ewt', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_evat', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.total_disbursement', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res = $res->where('acctg_vouchers.status', $status);
        }
        $res = $res->where('acctg_vouchers.is_payables', $type)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function payables_listItems($request, $voucherID)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_payables.vat_type',
            3 => 'acctg_expanded_withholding_taxes.code',
            4 => 'acctg_expanded_vatable_taxes.code',
            5 => 'acctg_payables.items',
            6 => 'acctg_payables.quantity',
            7 => 'gso_unit_of_measurements.code',
            8 => 'acctg_payables.amount',
            9 => 'acctg_payables.total_amount',
            11 => 'acctg_payables.due_date',
            12 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_payables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountPayable::select([
            'acctg_payables.*',
            'acctg_payables.id as identity',
            'acctg_payables.status as identityStatus',
            'acctg_payables.amount as identityAmount',
            'acctg_payables.total_amount as identityTotal'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_payables.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_payables.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_payables.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_payables.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_payables.voucher_id', $voucherID);
        if ($status != 'all') {
            $res->where('acctg_payables.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function deductions_listItems($request, $voucherID)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_deductions.vat_type',
            3 => 'acctg_expanded_withholding_taxes.code',
            4 => 'acctg_expanded_vatable_taxes.code',
            5 => 'acctg_deductions.items',
            6 => 'acctg_deductions.quantity',
            7 => 'gso_unit_of_measurements.code',
            8 => 'acctg_deductions.amount',
            9 => 'acctg_deductions.total_amount',
            11 => 'acctg_deductions.due_date',
            12 => 'acctg_deductions.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_deductions.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountDeduction::select([
            'acctg_deductions.*',
            'acctg_deductions.id as identity',
            'acctg_deductions.status as identityStatus',
            'acctg_deductions.amount as identityAmount',
            'acctg_deductions.total_amount as identityTotal'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_deductions.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_deductions.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_deductions.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_deductions.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_deductions.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_deductions.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_deductions.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_deductions.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_deductions.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_deductions.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_deductions.voucher_id', $voucherID);
        if ($status != 'all') {
            $res->where('acctg_deductions.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function collections_listItems($request, $voucherID)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_payables.vat_type',
            3 => 'acctg_expanded_withholding_taxes.code',
            4 => 'acctg_expanded_vatable_taxes.code',
            5 => 'acctg_payables.items',
            6 => 'acctg_payables.quantity',
            7 => 'gso_unit_of_measurements.code',
            8 => 'acctg_payables.amount',
            9 => 'acctg_payables.total_amount',
            11 => 'acctg_payables.due_date',
            12 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_payables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountIncome::select([
            'acctg_incomes.*',
            'acctg_incomes.id as identity',
            'acctg_incomes.status as identityStatus',
            'acctg_incomes.amount as identityAmount',
            'acctg_incomes.total_amount as identityTotal'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_incomes.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_incomes.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_incomes.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_incomes.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_incomes.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_incomes.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_incomes.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_incomes.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_incomes.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_incomes.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_incomes.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_incomes.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_incomes.voucher_id', $voucherID);
        if ($status != 'all') {
            $res->where('acctg_incomes.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approvals_payables_listItems($request)
    {
        $columns = array( 
            // 0 => 'acctg_vouchers.voucher_no',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_payables.vat_type',
            3 => 'acctg_expanded_withholding_taxes.code',
            4 => 'acctg_expanded_vatable_taxes.code',
            5 => 'acctg_payables.items',
            6 => 'acctg_payables.quantity',
            7 => 'gso_unit_of_measurements.code',
            11 => 'acctg_payables.due_date',
            12 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_payables.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountPayable::select([
            'acctg_payables.*',
            'acctg_payables.id as identity',
            'acctg_payables.status as identityStatus',
            'acctg_payables.amount as identityAmount',
            'acctg_payables.total_amount as identityTotal'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_payables.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_payables.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_payables.ewt_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_payables.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.trans_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.trans_type', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.vat_type', 'like', '%' . $keywords . '%')    
                ->orWhere('acctg_expanded_withholding_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_withholding_taxes.percentage', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_expanded_vatable_taxes.percentage', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.items', 'like', '%' . $keywords . '%')            
                ->orWhere('acctg_payables.quantity', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')         
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payables.total_amount', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res = $res->where('acctg_payables.status', $status);
        } else {
            $res = $res->where('acctg_payables.status', '!=', 'draft');
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function payments_listItems($request, $voucherID)
    {
        $columns = array( 
            1 => 'acctg_disbursements.id',
            4 => 'acctg_disbursements.cheque_no',
            5 => 'acctg_disbursements.bank_name',
            6 => 'acctg_disbursements.bank_account_no',
            8 => 'acctg_disbursements.amount',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_disbursements.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountDisbursement::with(['gl_account', 'sl_account', 'type', 'voucher'])->select([
            'acctg_disbursements.*',
            'acctg_disbursements.id as identity',
            'acctg_disbursements.status as identityStatus',
            'acctg_disbursements.amount as identityAmount'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_payment_types', function($join)
        {
            $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_disbursements.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payment_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.cheque_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['acctg_disbursements.voucher_id' => $voucherID, 'acctg_disbursements.is_active' => 1]);
        if ($status != 'all') {
            $res->where('acctg_disbursements.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        if ($limit > 0) {
            $res = $res->skip($start)->take($limit)->get();
        } else {
            $res = $res->get();
        }

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approvals_payments_listItems($request)
    {
        $columns = array( 
            1 => 'acctg_disbursements.id',
            // 1 => 'acctg_account_general_ledgers.code',
            // 2 => 'acctg_payables.vat_type',
            // 3 => 'acctg_expanded_withholding_taxes.code',
            // 4 => 'acctg_expanded_vatable_taxes.code',
            // 5 => 'acctg_payables.items',
            // 6 => 'acctg_payables.quantity',
            // 7 => 'gso_unit_of_measurements.code',
            // 11 => 'acctg_payables.due_date',
            // 12 => 'acctg_payables.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_disbursements.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgAccountDisbursement::select([
            '*',
            'acctg_disbursements.id as identity',
            'acctg_disbursements.status as identityStatus',
            'acctg_disbursements.amount as identityAmount'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
        })
        ->leftJoin('acctg_payment_types', function($join)
        {
            $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_disbursements.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_payment_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_subsidiary_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.bank_account_name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.cheque_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_disbursements.total_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_disbursements.status', '!=', 'draft')
        ->where('acctg_disbursements.is_active', 1);
        if ($status != 'all') {
            $res->where('acctg_disbursements.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }

    public function allSLAccounts()
    {
        return (new AcctgAccountSubsidiaryLedger)->allSLAccountsGroup();
    }

    public function allBankSLAccounts()
    {
        return (new AcctgAccountSubsidiaryLedger)->allBankSLAccounts();
    }

    public function allPaymentSLAccounts()
    {
        return (new AcctgAccountSubsidiaryLedger)->allPaymentSLAccounts();
    }

    public function allPaymentType()
    {
        return (new AcctgPaymentType)->allPaymentType();
    }

    public function allUOMs()
    {
        return (new GsoUnitOfMeasurement)->allUOMs();
    }

    public function allEVAT()
    {
        return (new AcctgExpandedVatableTax)->allEVAT();
    }

    public function allEWT()
    {
        return (new AcctgExpandedWithholdingTax)->allEWT();
    }

    public function allPayees()
    {
        return (new CboPayee)->allPayees();
    }

    public function updateSeries($details)
    {
        $res = AcctgVoucherSeries::where(['voucher_id' => $details['voucher_id']]);
        if ($res->count() > 0) {
            $res = AcctgVoucherSeries::find($res->first()->id);
            $res->fund_code_id = $details['fund_code_id'];
            $res->series = $details['series'];
            $res->updated_at = $details['created_at'];
            $res->updated_by = $details['created_by'];
            $res->update();
        } else {
            $res1 = AcctgVoucherSeries::where(['voucher_id' => NULL, 'fund_code_id' => $details['fund_code_id'], 'series' => $details['series']])->get();
            if ($res1->count() > 0) {
                $res1 = AcctgVoucherSeries::find($res1->first()->id);
                $res1->voucher_id = $details['voucher_id'];
                $res1->updated_at = $details['created_at'];
                $res1->updated_by = $details['created_by'];
                $res1->update();
            } else {
                AcctgVoucherSeries::create($details);
            }
        }

        return true;
    }

    public function generateVoucherNo($fund, $voucherID = 0, $user, $timestamp)
    {
        if ($voucherID > 0) {
            $voucher = AcctgVoucher::find($voucherID);
            if ($voucher->fund_code_id == $fund) {
                return $voucher->voucher_no;
            } else {
                $res0 = AcctgVoucherSeries::where(['voucher_id' => $voucherID]);
                if ($res0->count() > 0) {
                    $res0 = AcctgVoucherSeries::find($res0->first()->id);
                    $res0->voucher_id = NULL;
                    $res0->updated_at = $timestamp;
                    $res0->updated_by = $user;
                    $res0->update();
                } else {
                    AcctgVoucherSeries::create([
                        'voucher_id' => NULL,
                        'fund_code_id' => $voucher->fund_code_id,
                        'series' => $voucher->voucher_no,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }

                $res1 = AcctgVoucherSeries::where(['voucher_id' => NULL, 'fund_code_id' => $fund])->get();
                if ($res1->count() > 0) {
                    $res1 = AcctgVoucherSeries::find($res1->first()->id);
                    $res1->voucher_id = $voucherID;
                    $series = $res1->series;
                    $res1->update();
                    return $series;
                } else {
                    $fund_codes = AcctgFundCode::find($fund);
                    $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

                    $count = AcctgVoucher::whereYear('created_at', '=',  $year)
                    ->where(['fund_code_id' => $fund])
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
                    return $series;
                }
            }
        } else {
            $res1 = AcctgVoucherSeries::where(['voucher_id' => NULL, 'fund_code_id' => $fund])->get();
            if ($res1->count() > 0) {
                return $res1->first()->series;
            } else {
                $fund_codes = AcctgFundCode::find($fund);
                $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

                $count = AcctgVoucher::whereYear('created_at', '=',  $year)
                ->where(['fund_code_id' => $fund])
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
                return $series;
            }
        }
    }

    public function view_available_payables($voucherID, $fund_code, $payee = 0, $replenish = 0)
    {   
        if ($replenish > 0) {
            $res = AcctgAccountPayable::select('acctg_payables.*', 'acctg_payables.id as identity', 'acctg_payables.status as identityStatus', 'acctg_payables.amount as identityAmount', 'acctg_payables.total_amount as identityTotal')
            ->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id','=','acctg_payables.gl_account_id')
            ->leftJoin('gso_unit_of_measurements','gso_unit_of_measurements.id','=','acctg_payables.uom_id')
            ->leftJoin('acctg_expanded_vatable_taxes','acctg_expanded_vatable_taxes.id','=','acctg_payables.evat_id')
            ->leftJoin('acctg_expanded_withholding_taxes','acctg_expanded_withholding_taxes.id','=','acctg_payables.ewt_id')
            ->where('acctg_payables.fund_code_id', '=', $fund_code)
            ->whereNull('acctg_payables.voucher_id')
            ->get();
        } else {
            if ($payee > 0) {
                $res = AcctgAccountPayable::select('acctg_payables.*', 'acctg_payables.id as identity', 'acctg_payables.status as identityStatus', 'acctg_payables.amount as identityAmount', 'acctg_payables.total_amount as identityTotal')
                ->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id','=','acctg_payables.gl_account_id')
                ->leftJoin('gso_unit_of_measurements','gso_unit_of_measurements.id','=','acctg_payables.uom_id')
                ->leftJoin('acctg_expanded_vatable_taxes','acctg_expanded_vatable_taxes.id','=','acctg_payables.evat_id')
                ->leftJoin('acctg_expanded_withholding_taxes','acctg_expanded_withholding_taxes.id','=','acctg_payables.ewt_id')
                ->where('acctg_payables.payee_id', '=', $payee)
                ->where('acctg_payables.fund_code_id', '=', $fund_code)
                ->whereNull('acctg_payables.voucher_id')
                ->get();
            } else {
                $res = AcctgAccountPayable::select('acctg_payables.*', 'acctg_payables.id as identity', 'acctg_payables.status as identityStatus', 'acctg_payables.amount as identityAmount', 'acctg_payables.total_amount as identityTotal')
                ->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id','=','acctg_payables.gl_account_id')
                ->leftJoin('gso_unit_of_measurements','gso_unit_of_measurements.id','=','acctg_payables.uom_id')
                ->leftJoin('acctg_expanded_vatable_taxes','acctg_expanded_vatable_taxes.id','=','acctg_payables.evat_id')
                ->leftJoin('acctg_expanded_withholding_taxes','acctg_expanded_withholding_taxes.id','=','acctg_payables.ewt_id')
                ->where('acctg_payables.fund_code_id', '=', $fund_code)
                ->whereNull('acctg_payables.voucher_id')
                ->get();
            }
        }

        return $res;
    }

    public function view_available_incomes($voucherID, $fund_code, $payee = 0)
    {
        if ($payee > 0) {
            $res = AcctgAccountIncome::select('acctg_incomes.*', 'acctg_incomes.id as identity', 'acctg_incomes.status as identityStatus', 'acctg_incomes.amount as identityAmount', 'acctg_incomes.total_amount as identityTotal')
            ->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id','=','acctg_incomes.gl_account_id')
            ->leftJoin('gso_unit_of_measurements','gso_unit_of_measurements.id','=','acctg_incomes.uom_id')
            ->leftJoin('acctg_expanded_vatable_taxes','acctg_expanded_vatable_taxes.id','=','acctg_incomes.evat_id')
            ->leftJoin('acctg_expanded_withholding_taxes','acctg_expanded_withholding_taxes.id','=','acctg_incomes.ewt_id')
            ->where('acctg_incomes.payee_id', '=', $payee)
            ->where('acctg_incomes.fund_code_id', '=', $fund_code)
            ->whereNull('acctg_incomes.voucher_id')
            ->get();
        } else {
            $res = AcctgAccountIncome::select('acctg_incomes.*', 'acctg_incomes.id as identity', 'acctg_incomes.status as identityStatus', 'acctg_incomes.amount as identityAmount', 'acctg_incomes.total_amount as identityTotal')
            ->leftJoin('acctg_account_general_ledgers','acctg_account_general_ledgers.id','=','acctg_incomes.gl_account_id')
            ->leftJoin('gso_unit_of_measurements','gso_unit_of_measurements.id','=','acctg_incomes.uom_id')
            ->leftJoin('acctg_expanded_vatable_taxes','acctg_expanded_vatable_taxes.id','=','acctg_incomes.evat_id')
            ->leftJoin('acctg_expanded_withholding_taxes','acctg_expanded_withholding_taxes.id','=','acctg_incomes.ewt_id')
            ->where('acctg_incomes.fund_code_id', '=', $fund_code)
            ->whereNull('acctg_incomes.voucher_id')
            ->get();
        }

        return $res;
    }

    public function add_payables($request, $voucherID)
    {
        foreach ($request->payables as $payable) {
            AcctgAccountPayable::whereId($payable)->update(['voucher_id' => $voucherID, 'is_active' => 1]);
        }

        $res = AcctgAccountPayable::select([
            '*',
            DB::raw('SUM(ewt_amount) as ewtAmt'),
            DB::raw('SUM(evat_amount) as evatAmt'),
            DB::raw('SUM(total_amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        if ($res->count() > 0) {
            $res = $res->first();
            AcctgVoucher::whereId($voucherID)->update([
                'total_payables' => $res->totalAmt,
                'total_ewt' => $res->ewtAmt,
                'total_evat' => $res->evatAmt
            ]);
        }

        return true;
    }

    public function add_incomes($request, $voucherID)
    {
        foreach ($request->payables as $payable) {
            AcctgAccountIncome::whereId($payable)->update(['voucher_id' => $voucherID, 'is_active' => 1]);
        }

        $res = AcctgAccountIncome::select([
            '*',
            DB::raw('SUM(ewt_amount) as ewtAmt'),
            DB::raw('SUM(evat_amount) as evatAmt'),
            DB::raw('SUM(total_amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        if ($res->count() > 0) {
            $res = $res->first();
            AcctgVoucher::whereId($voucherID)->update([
                'total_payables' => $res->totalAmt,
                'total_ewt' => $res->ewtAmt,
                'total_evat' => $res->evatAmt
            ]);
        }

        return true;
    }

    public function update_vouchers($voucherID)
    {
        $voucher = AcctgVoucher::find($voucherID);
        if ($voucher->is_payables == 1) {
            $res = AcctgAccountPayable::select([
                '*',
                DB::raw('SUM(ewt_amount) as ewtAmt'),
                DB::raw('SUM(evat_amount) as evatAmt'),
                DB::raw('SUM(total_amount) as totalAmt')
            ])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->groupBy('voucher_id')
            ->get();
        } else if ($voucher->is_payables == 0) {
            $res = AcctgAccountIncome::select([
                '*',
                DB::raw('SUM(ewt_amount) as ewtAmt'),
                DB::raw('SUM(evat_amount) as evatAmt'),
                DB::raw('SUM(total_amount) as totalAmt')
            ])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->groupBy('voucher_id')
            ->get();
        }

        $res2 = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        $res3 = AcctgAccountDeduction::select([
            '*',
            DB::raw('SUM(total_amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        AcctgVoucher::whereId($voucherID)->update([
            'total_payables' => ($res->count() > 0) ? $res->first()->totalAmt : 0,
            'total_ewt' => ($res->count() > 0) ? $res->first()->ewtAmt : 0,
            'total_evat' => ($res->count() > 0) ? $res->first()->evatAmt : 0,
            'total_disbursement' => ($res2->count() > 0) ? $res2->first()->totalAmt : 0,
            'total_deductions' => ($res3->count() > 0) ? $res3->first()->totalAmt : 0
        ]);

        $totalAmt = AcctgAccountDisbursement::select(['acctg_disbursements.*'])
        ->where(['voucher_id' => $voucherID, 'status' =>  'posted', 'is_active' => 1])
        ->sum('amount'); 
        
        if ($voucher->is_payables == 1) {
            $res4 = AcctgAccountPayable::select(['acctg_payables.*'])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->get();
        } else if ($voucher->is_payables == 0) {
            $res4 = AcctgAccountIncome::select(['acctg_incomes.*'])
            ->where(['voucher_id' => $voucherID, 'is_active' => 1])
            ->get();
        }
        
        $totalAmt += ($res->count() > 0) ? floatval($res->first()->ewtAmt) : floatval(0);
        $totalAmt += ($res->count() > 0) ? floatval($res->first()->evatAmt) : floatval(0);
        if ($voucher->is_payables == 0) {
            $totalAmt += ($res3->count() > 0) ? floatval($res3->first()->totalAmt) : floatval(0);
        }

        while($totalAmt > 0) {
            if ($res4->count() > 0) {
                if ($voucher->is_payables == 1) {
                    foreach ($res4 as $r) {
                        $payables = floatval($r->total_amount);
                        if (floatval($totalAmt) >= floatval($payables)) {
                            AcctgAccountPayable::whereId($r->id)->update(['paid_amount' => $payables]);
                            $totalAmt -= floatval($payables);
                        } else {
                            AcctgAccountPayable::whereId($r->id)->update(['paid_amount' => $totalAmt]);
                            $totalAmt -= floatval($totalAmt);
                        }
                    }
                } else if ($voucher->is_payables == 0) {
                    foreach ($res4 as $r) {
                        $payables = floatval($r->total_amount);
                        if (floatval($totalAmt) >= floatval($payables)) {
                            AcctgAccountIncome::whereId($r->id)->update(['deposited_amount' => $payables]);
                            $totalAmt -= floatval($payables);
                        } else {
                            AcctgAccountIncome::whereId($r->id)->update(['deposited_amount' => $totalAmt]);
                            $totalAmt -= floatval($totalAmt);
                        }
                    }
                }
                break; break;
            } 
        }   

        return true;
    }

    public function update_income_vouchers($voucherID)
    {
        $res = AcctgAccountIncome::select([
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
            'total_ewt' => 0,
            'total_evat' => 0,
            'total_disbursement' => ($res2->count() > 0) ? $res2->first()->totalAmt : 0
        ]);

        return true;
    }
    
    public function remove_payables($id, array $newDetails) 
    {
        return AcctgAccountPayable::whereId($id)->update($newDetails);
    }

    public function remove_all_payables($request, $details)
    {
        AcctgAccountPayable::whereIn('id', $request->payables)->update($details);
        return true;
    }

    public function remove_collections($id, array $newDetails) 
    {
        return AcctgAccountIncome::whereId($id)->update($newDetails);
    }

    public function remove_all_collections($request, $details)
    {
        AcctgAccountIncome::whereIn('id', $request->payables)->update($details);
        return true;
    }

    public function get_account_payable()
    {
        $res = AcctgAccountGeneralLedger::where(['is_payable' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first()->id;
        }
        return 0;
    }

    public function get_cash_in_local()
    {   
        $res = AcctgAccountGeneralLedger::where(['is_treasury' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first()->id;
        }
        return 0;
    }

    public function get_due_to_bir()
    {   
        $res = AcctgAccountGeneralLedger::where(['is_due_to_bir' => 1, 'is_active' => 1])->get();
        if ($res->count() > 0) {
            return $res->first()->id;
        }
        return 0;
    }

    public function sl_account_reports($id, $row, $entity, $is_payable = true, $user, $timestamp, $is_disbursement = false)
    {   
        if (!$is_disbursement) {
            if ($is_payable) {
                $sql1 = AcctgSLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'debit_amount' => NULL,
                    'tax_amount' => NULL
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgSLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgSLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }

                if ($row->ewt_id > 0 || $row->evat_id > 0) {
                    $vat_gl = ($row->ewt_id > 0) ? $row->ewt->gl_account->id : $row->evat->gl_account->id;
                    $sql2 = AcctgSLAccountReport::where([
                        'entity' => $entity, 
                        'entity_id' => $id,
                        'debit_amount' => NULL,
                        'credit_amount' => NULL
                    ])->get();
                    if ($sql2->count() > 0) {
                        AcctgSLAccountReport::whereId($sql2->first()->id)->update([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $vat_gl,
                            'tax_amount' => floatval($row->ewt_amount) + floatval($row->evat_amount),
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        AcctgSLAccountReport::create([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $vat_gl,
                            'tax_amount' => floatval($row->ewt_amount) + floatval($row->evat_amount),
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'entity' => $entity,
                            'entity_id' => $id,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
            } else {
                $sql1 = AcctgSLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'debit_amount' => NULL,
                    'tax_amount' => NULL
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgSLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgSLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        } else {
            $sql1 = AcctgSLAccountReport::where([
                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                'entity_id' => $id,
                'credit_amount' => NULL,
                'tax_amount' => NULL
            ])->get();
            if ($sql1->count() > 0) {
                AcctgSLAccountReport::whereId($sql1->first()->id)->update([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgSLAccountReport::create([
                    'voucher_id' => $row->voucher_id,
                    'payee_id' => $row->voucher->payee_id,
                    'fund_id' => $row->voucher->fund_code_id,
                    'gl_account_id' => $row->gl_account_id,
                    'debit_amount' => $row->amount,
                    'posted_at' => $row->posted_at,
                    'posted_by' => $row->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                    'entity_id' => $id,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        }

        return true;
    }

    public function gl_account_reports($id, $row, $entity, $is_payable = true, $user, $timestamp, $is_deduction = false, $is_disbursement = false)
    {   
        $res = AcctgAccountGeneralLedger::where('is_payable', '=', 1)->get();
        $payableGL = ($res->count() > 0) ? $res->first()->id : 0;
        $res2 = AcctgAccountGeneralLedger::where('is_cash_in_bank', '=', 1)->get();
        $cashInBankGL = ($res2->count() > 0) ? $res2->first()->id : 0;
        $res3 = AcctgAccountGeneralLedger::where('is_treasury', '=', 1)->get();
        $cashInLocalGL = ($res3->count() > 0) ? $res3->first()->id : 0;

        if (!$is_disbursement) {
            if ($is_payable) {
                $sql1 = AcctgGLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'credit_amount' => NULL,
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'debit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgGLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'debit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }

                // payables
                $sql2 = AcctgGLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'debit_amount' => NULL,
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $payableGL,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgGLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->payee_id,
                        'fund_id' => $row->fund_code_id,
                        'gl_account_id' => $payableGL,
                        'credit_amount' => $row->total_amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }

                if ($row->ewt_id > 0 || $row->evat_id > 0) {
                    $vat_gl = ($row->ewt_id > 0) ? $row->ewt->gl_account->id : $row->evat->gl_account->id;
                    $sql2 = AcctgGLAccountReport::where([
                        'gl_account_id' => $vat_gl,
                        'entity' => $entity, 
                        'entity_id' => $id,
                        'debit_amount' => NULL,
                    ])->get();
                    if ($sql2->count() > 0) {
                        AcctgGLAccountReport::whereId($sql2->first()->id)->update([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $vat_gl,
                            'credit_amount' => floatval($row->ewt_amount) + floatval($row->evat_amount),
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        AcctgGLAccountReport::create([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $vat_gl,
                            'credit_amount' => floatval($row->ewt_amount) + floatval($row->evat_amount),
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'entity' => $entity,
                            'entity_id' => $id,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
            } else {
                if (!$is_deduction) {
                    $sql1 = AcctgGLAccountReport::where([
                        'entity' => $entity, 
                        'entity_id' => $id,
                        'debit_amount' => NULL
                    ])->get();
                    if ($sql1->count() > 0) {
                        AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $row->gl_account_id,
                            'credit_amount' => $row->total_amount,
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        AcctgGLAccountReport::create([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $row->gl_account_id,
                            'credit_amount' => $row->total_amount,
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'entity' => $entity,
                            'entity_id' => $id,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                } else {
                    $sql1 = AcctgGLAccountReport::where([
                        'entity' => $entity, 
                        'entity_id' => $id,
                        'credit_amount' => NULL
                    ])->get();
                    if ($sql1->count() > 0) {
                        AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $row->gl_account_id,
                            'debit_amount' => $row->total_amount,
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'updated_at' => $timestamp,
                            'updated_by' => $user
                        ]);
                    } else {
                        AcctgGLAccountReport::create([
                            'voucher_id' => $row->voucher_id,
                            'payee_id' => $row->payee_id,
                            'fund_id' => $row->fund_code_id,
                            'gl_account_id' => $row->gl_account_id,
                            'debit_amount' => $row->total_amount,
                            'posted_at' => $row->posted_at,
                            'posted_by' => $row->posted_by,
                            'entity' => $entity,
                            'entity_id' => $id,
                            'created_at' => $timestamp,
                            'created_by' => $user
                        ]);
                    }
                }
            }
        } else {
            if ($is_payable) {
                $sql1 = AcctgGLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'debit_amount' => NULL,
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->voucher->payee_id,
                        'fund_id' => $row->voucher->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgGLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->voucher->payee_id,
                        'fund_id' => $row->voucher->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'credit_amount' => $row->amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            } else {
                $sql1 = AcctgGLAccountReport::where([
                    'entity' => $entity, 
                    'entity_id' => $id,
                    'credit_amount' => NULL,
                ])->get();
                if ($sql1->count() > 0) {
                    AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->voucher->payee_id,
                        'fund_id' => $row->voucher->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'debit_amount' => $row->amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'updated_at' => $timestamp,
                        'updated_by' => $user
                    ]);
                } else {
                    AcctgGLAccountReport::create([
                        'voucher_id' => $row->voucher_id,
                        'payee_id' => $row->voucher->payee_id,
                        'fund_id' => $row->voucher->fund_code_id,
                        'gl_account_id' => $row->gl_account_id,
                        'debit_amount' => $row->amount,
                        'posted_at' => $row->posted_at,
                        'posted_by' => $row->posted_by,
                        'entity' => $entity,
                        'entity_id' => $id,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }

        return true;
    }

    public function get_current_taxes($voucherID, $sl_account, $posted = 1)
    {
        $res = AcctgAccountIncome::select([
            'acctg_incomes.gl_account_id as gl_account_id',
            'acctg_incomes.sl_account_id as sl_account_id',
            'acctg_vouchers.fund_code_id as fund_id'
            // DB::raw('SUM(acctg_incomes.total_amount) as total_amount'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_incomes.sl_account_id');
        })
        ->where([
            'acctg_account_subsidiary_ledgers.id' => $sl_account,
            'acctg_account_subsidiary_ledgers.is_rpt_tax_cy' => 1,
            'acctg_incomes.is_active' => 1,
            'acctg_vouchers.id' => $voucherID
        ]);
        if ($posted > 0) {
            $res = $res->where('acctg_incomes.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_incomes.sl_account_id'])
        ->orderBy('acctg_incomes.sl_account_id', 'ASC')->get();

        $arr = array(); $arrs = array();
        if ($res->count() > 0) {
            $gl_codes = array();
            foreach ($res as $r) {
                $taxes = AcctgRptReceivableCY::where(['fund_code_id' => $r->fund_id,  'income_sl' => $r->sl_account_id, 'is_active' => 1])->get();
                if ($taxes->count() > 0) {
                    foreach ($taxes as $tax) {
                        if (!in_array($tax->gl_account->code, $gl_codes)) {
                            $arr[] = [
                                'gl_account_id' => $tax->gl_account->id,
                                'gl_account_code' => $tax->gl_account->code,
                                'gl_account_desc' => $tax->gl_account->description,
                            ];
                            $gl_codes[] = $tax->gl_account->code;
                        }
                    }
                }
            }

            foreach ($res as $r) {
                $taxes = AcctgRptReceivableCY::where(['fund_code_id' => $r->fund_id,  'income_sl' => $r->sl_account_id, 'is_active' => 1])->get();  
                if ($taxes->count() > 0) {
                    foreach ($taxes as $tax) {
                        $res2 = AcctgAccountIncome::select([
                        ])
                        ->leftJoin('acctg_vouchers', function($join)
                        {
                            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
                        })
                        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
                        {
                            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_incomes.sl_account_id');
                        })
                        ->where([
                            'acctg_account_subsidiary_ledgers.id' => $r->sl_account_id,
                            'acctg_account_subsidiary_ledgers.is_rpt_tax_cy' => 1,
                            'acctg_incomes.is_active' => 1,
                            'acctg_vouchers.id' => $voucherID
                        ]);
                        if ($posted > 0) {
                            $res2 = $res2->where('acctg_incomes.status', '=', 'posted');
                        }
                        $res2 = $res2->sum('acctg_incomes.total_amount');        
                        $key = array_search($tax->gl_account->code, array_column($arr, 'gl_account_code'));     
                        $sample = [
                            'sl_account_code' => $tax->sl_account->code,
                            'sl_account_desc' => $tax->sl_account->description,
                            'total' => $res2,
                            'is_debit' => $tax->is_debit
                        ];  
                        array_push($arr[$key], ['childrens' => $sample]);
                    }
                }
            }
        }
        return $arr;
    }

    public function trial_balance_reports($id, $row, $entity)
    {
    }

    public function send_all_payables($request, array $details)
    {   
        $accounts_payable = $this->get_account_payable();
        AcctgAccountPayable::whereIn('id', $request->payables)->update($details);
        if (!empty($request->payables)) {
            $totalAmt = 0; $voucher = []; $arr = [];
            foreach ($request->payables as $payable) {
                $res1 = AcctgAccountPayable::find($payable);
                if ($res1->status == 'posted') {
                    $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountPayable)->getTable(), 'entity_id' => $payable])->get();
                    if ($res2->count() > 0) {
                        AcctgTrialBalance::whereId($res2->first()->id)->update([
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->payee_id,
                            'fund_code_id' => $res1->fund_code_id,
                            'gl_account_id' => $res1->gl_account_id,
                            'debit' => $res1->total_amount,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by
                        ]);
                    } else {
                        AcctgTrialBalance::create([
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->payee_id,
                            'fund_code_id' => $res1->fund_code_id,
                            'gl_account_id' => $res1->gl_account_id,
                            'debit' => $res1->total_amount,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountPayable)->getTable(),
                            'entity_id' => $payable
                        ]);
                    }

                    // process GL account reports
                    $this->gl_account_reports($payable, $res1, (new AcctgAccountPayable)->getTable(), true, $details['approved_by'], $details['approved_at']);

                    // process SL account reports
                    $this->sl_account_reports($payable, $res1, (new AcctgAccountPayable)->getTable(), true, $details['approved_by'], $details['approved_at']);

                    if (empty($voucher)) {
                        $voucher = (object) [
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->payee_id,
                            'fund_code_id' => $res1->fund_code_id,
                            'voucher_id' => $res1->voucher_id,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountPayable)->getTable()
                        ];
                    }
                    $arr[] = $payable;
                    $totalAmt += floatval($res1->total_amount);
                }
            }
            if ($totalAmt > 0) {
                AcctgTrialBalance::create([
                    'voucher_id' => $voucher->voucher_id,
                    'payee_id' => $voucher->payee_id,
                    'fund_code_id' => $voucher->fund_code_id,
                    'gl_account_id' => $accounts_payable,
                    'credit' => $totalAmt,
                    'entity' => $voucher->entity,
                    'entity_id' => implode(',', $arr),
                    'posted_at' => $voucher->posted_at,
                    'posted_by' => $voucher->posted_by,
                ]);
            }
        }
        return true;
    }

    public function send_all_collections($request, array $details)
    {   
        $cash_in_local = $this->get_cash_in_local();
        AcctgAccountIncome::whereIn('id', $request->payables)->update($details);
        if (!empty($request->payables)) {
            $totalAmt = 0; $voucher = []; $arr = [];
            foreach ($request->payables as $payable) {
                $res1 = AcctgAccountIncome::find($payable);
                if ($res1->status == 'posted') {
                    // $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountIncome)->getTable(), 'entity_id' => $payable])->get();
                    // if ($res2->count() > 0) {
                    //     AcctgTrialBalance::whereId($res2->first()->id)->update([
                    //         'voucher_id' => $res1->voucher_id,
                    //         'payee_id' => $res1->payee_id,
                    //         'fund_code_id' => $res1->fund_code_id,
                    //         'gl_account_id' => $res1->gl_account_id,
                    //         'sl_account_id' => $res1->sl_account_id,
                    //         'credit' => $res1->total_amount,
                    //         'posted_at' => $res1->posted_at,
                    //         'posted_by' => $res1->posted_by
                    //     ]);
                    // } else {
                    //     AcctgTrialBalance::create([
                    //         'voucher_id' => $res1->voucher_id,
                    //         'payee_id' => $res1->payee_id,
                    //         'fund_code_id' => $res1->fund_code_id,
                    //         'gl_account_id' => $res1->gl_account_id,
                    //         'sl_account_id' => $res1->sl_account_id,
                    //         'credit' => $res1->total_amount,
                    //         'posted_at' => $res1->posted_at,
                    //         'posted_by' => $res1->posted_by,
                    //         'entity' => (new AcctgAccountIncome)->getTable(),
                    //         'entity_id' => $payable
                    //     ]);
                    // }

                    // process GL account reports
                    $this->gl_account_reports($payable, $res1, (new AcctgAccountIncome)->getTable(), false, $details['approved_by'], $details['approved_at']);

                    // process SL account reports
                    $this->sl_account_reports($payable, $res1, (new AcctgAccountIncome)->getTable(), false, $details['approved_by'], $details['approved_at']);
                        
                    $currentTax = $this->get_current_taxes($res1->voucher_id, $res1->sl_account_id);
                    if (count($currentTax) > 0) {
                        foreach ($currentTax as $current) {
                            $totalAmt = 0; $is_debit = 0;
                            foreach ($current as $key => $value) {
                                if (is_int($key)) {
                                    if ($value['childrens']['is_debit'] > 0) {
                                        $is_debit = 1;
                                    }
                                    $totalAmt += floatval($value['childrens']['total']);
                                }
                            }
                            if ($is_debit > 0) {
                                AcctgGLAccountReport::create([
                                    'voucher_id' => $res1->voucher_id,
                                    'payee_id' => $res1->payee_id,
                                    'fund_id' => $res1->fund_code_id,
                                    'gl_account_id' => $current['gl_account_id'],
                                    'debit_amount' => $totalAmt,
                                    'posted_at' => $res1->posted_at,
                                    'posted_by' => $res1->posted_by,
                                    'entity' => (new AcctgAccountIncome)->getTable(),
                                    'entity_id' => $payable,
                                    'created_at' => $details['approved_at'],
                                    'created_by' => $details['approved_by']
                                ]);
                            } else {
                                AcctgGLAccountReport::create([
                                    'voucher_id' => $res1->voucher_id,
                                    'payee_id' => $res1->payee_id,
                                    'fund_id' => $res1->fund_code_id,
                                    'gl_account_id' => $current['gl_account_id'],
                                    'credit_amount' => $totalAmt,
                                    'posted_at' => $res1->posted_at,
                                    'posted_by' => $res1->posted_by,
                                    'entity' => (new AcctgAccountIncome)->getTable(),
                                    'entity_id' => $payable,
                                    'created_at' => $details['approved_at'],
                                    'created_by' => $details['approved_by']
                                ]);
                            }
                        }
                    }

                    if (empty($voucher)) {
                        $voucher = (object) [
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->payee_id,
                            'fund_code_id' => $res1->fund_code_id,
                            'voucher_id' => $res1->voucher_id,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountIncome)->getTable()
                        ];
                    }
                    $arr[] = $payable;
                    $totalAmt += floatval($res1->total_amount);
                }
            }
            if ($totalAmt > 0) {
                // AcctgTrialBalance::create([
                //     'voucher_id' => $voucher->voucher_id,
                //     'payee_id' => $voucher->payee_id,
                //     'fund_code_id' => $voucher->fund_code_id,
                //     'gl_account_id' => $cash_in_local,
                //     'debit' => $totalAmt,
                //     'entity' => $voucher->entity,
                //     'entity_id' => implode(',', $arr),
                //     'posted_at' => $voucher->posted_at,
                //     'posted_by' => $voucher->posted_by,
                // ]);
            }
        }
        return true;
    }

    public function send_all_deductions($request, array $details)
    {
        AcctgAccountDeduction::whereIn('id', $request->deductions)->update($details);
        if (!empty($request->deductions)) {
            $totalAmt = 0; $voucher = []; $arr = [];
            foreach ($request->deductions as $deduction) {
                $res1 = AcctgAccountDeduction::find($deduction);
                if ($res1->status == 'posted') {
                    $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountDeduction)->getTable(), 'entity_id' => $deduction])->get();
                    if ($res2->count() > 0) {
                        AcctgTrialBalance::whereId($res2->first()->id)->update([
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->voucher->payee_id,
                            'fund_code_id' => $res1->voucher->fund_code_id,
                            'gl_account_id' => $res1->gl_account_id,
                            'sl_account_id' => $res1->sl_account_id,
                            'debit' => $res1->amount,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by
                        ]);
                    } else {
                        AcctgTrialBalance::create([
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->voucher->payee_id,
                            'fund_code_id' => $res1->voucher->fund_code_id,
                            'gl_account_id' => $res1->gl_account_id,
                            'sl_account_id' => $res1->sl_account_id,
                            'debit' => $res1->amount,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountDeduction)->getTable(),
                            'entity_id' => $deduction
                        ]);
                    }
                    
                    // process GL account reports
                    $this->gl_account_reports($deduction, $res1, (new AcctgAccountDeduction)->getTable(), false, $details['approved_by'], $details['approved_at'], true);

                    if (empty($voucher)) {
                        $voucher = (object) [
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->voucher->payee_id,
                            'fund_code_id' => $res1->voucher->fund_code_id,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountDeduction)->getTable()
                        ];
                    }
                    $arr[] = $deduction;
                    $totalAmt += floatval($res1->amount);
                }
            }
        }

        return true;
    }

    public function send_all_payments($request, array $details)
    {
        AcctgAccountDisbursement::whereIn('id', $request->payments)->update($details);
        $disburse = AcctgAccountDisbursement::find($request->payments[0]);
        $journal = AcctgVoucher::find($disburse->voucher_id);
        if (!empty($request->payments)) {
            $totalAmt = 0; $voucher = []; $arr = [];
            foreach ($request->payments as $payment) {
                $res1 = AcctgAccountDisbursement::find($payment);
                if ($res1->status == 'posted') {
                    $res2 = AcctgTrialBalance::where(['entity' => (new AcctgAccountDisbursement)->getTable(), 'entity_id' => $payment])->get();
                    if ($res2->count() > 0) {
                        if ($journal->is_payables == 1) {
                            AcctgTrialBalance::whereId($res2->first()->id)->update([
                                'voucher_id' => $res1->voucher_id,
                                'payee_id' => $res1->voucher->payee_id,
                                'fund_code_id' => $res1->voucher->fund_code_id,
                                'gl_account_id' => $res1->gl_account_id,
                                'sl_account_id' => $res1->sl_account_id,
                                'credit' => $res1->amount,
                                'posted_at' => $res1->posted_at,
                                'posted_by' => $res1->posted_by
                            ]);
                        } else {
                            AcctgTrialBalance::whereId($res2->first()->id)->update([
                                'voucher_id' => $res1->voucher_id,
                                'payee_id' => $res1->voucher->payee_id,
                                'fund_code_id' => $res1->voucher->fund_code_id,
                                'gl_account_id' => $res1->gl_account_id,
                                'sl_account_id' => $res1->sl_account_id,
                                'debit' => $res1->amount,
                                'posted_at' => $res1->posted_at,
                                'posted_by' => $res1->posted_by
                            ]);
                        }
                    } else {
                        if ($journal->is_payables == 1) {
                            AcctgTrialBalance::create([
                                'voucher_id' => $res1->voucher_id,
                                'payee_id' => $res1->voucher->payee_id,
                                'fund_code_id' => $res1->voucher->fund_code_id,
                                'gl_account_id' => $res1->gl_account_id,
                                'sl_account_id' => $res1->sl_account_id,
                                'credit' => $res1->amount,
                                'posted_at' => $res1->posted_at,
                                'posted_by' => $res1->posted_by,
                                'entity' => (new AcctgAccountDisbursement)->getTable(),
                                'entity_id' => $payment
                            ]);
                        } else {
                            AcctgTrialBalance::create([
                                'voucher_id' => $res1->voucher_id,
                                'payee_id' => $res1->voucher->payee_id,
                                'fund_code_id' => $res1->voucher->fund_code_id,
                                'gl_account_id' => $res1->gl_account_id,
                                'sl_account_id' => $res1->sl_account_id,
                                'debit' => $res1->amount,
                                'posted_at' => $res1->posted_at,
                                'posted_by' => $res1->posted_by,
                                'entity' => (new AcctgAccountDisbursement)->getTable(),
                                'entity_id' => $payment
                            ]);
                        }
                    }

                    $is_payable = ($journal->is_payables == 1 || $journal->is_payables == 2) ? true : false;
                    $this->gl_account_reports($payment, $res1, (new AcctgAccountDisbursement)->getTable(), $is_payable, $details['approved_by'], $details['approved_at'], false, true);
                    $this->sl_account_reports($payment, $res1, (new AcctgAccountDisbursement)->getTable(), $is_payable, $details['approved_by'], $details['approved_at'], true);

                    if (empty($voucher)) {
                        $voucher = (object) [
                            'voucher_id' => $res1->voucher_id,
                            'payee_id' => $res1->voucher->payee_id,
                            'fund_code_id' => $res1->voucher->fund_code_id,
                            'posted_at' => $res1->posted_at,
                            'posted_by' => $res1->posted_by,
                            'entity' => (new AcctgAccountDisbursement)->getTable()
                        ];
                    }
                    $arr[] = $payment;
                    $totalAmt += floatval($res1->amount);
                }
            }
            if ($totalAmt > 0) {
                if ($journal->is_payables == 1) {
                    $ap_details = $this->get_payables_details($voucher->voucher_id);
                    if (!empty($ap_details)) {
                        foreach ($ap_details as $detail) {                        
                            $res2 = AcctgTrialBalance::where([
                                'voucher_id' => $voucher->voucher_id, 
                                'gl_account_id' => $detail->gl_account_id, 
                                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                                'entity_id' => NULL
                            ])->get();
                            if ($res2->count() > 0) {
                                AcctgTrialBalance::whereId($res2->first()->id)->update([
                                    'voucher_id' => $voucher->voucher_id,
                                    'payee_id' => $voucher->payee_id,
                                    'fund_code_id' => $voucher->fund_code_id,
                                    'gl_account_id' => $detail->gl_account_id,
                                    'sl_account_id' => $detail->sl_account_id,
                                    'debit' => $detail->debit,
                                    'credit' => $detail->credit,
                                    'posted_at' => $voucher->posted_at,
                                    'posted_by' => $voucher->posted_by
                                ]);
                            } else {
                                AcctgTrialBalance::create([
                                    'voucher_id' => $voucher->voucher_id,
                                    'payee_id' => $voucher->payee_id,
                                    'fund_code_id' => $voucher->fund_code_id,
                                    'gl_account_id' => $detail->gl_account_id,
                                    'sl_account_id' => $detail->sl_account_id,
                                    'debit' => $detail->debit,
                                    'credit' => $detail->credit,
                                    'posted_at' => $voucher->posted_at,
                                    'posted_by' => $voucher->posted_by,
                                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                                    'entity_id' => NULL
                                ]);
                            }
                        }
                    }
                } else {
                    // $collections = $this->get_collections_details($voucher->voucher_id);
                    // if (!empty($collections)) {
                    //     foreach ($collections as $detail) {                        
                    //         $res2 = AcctgTrialBalance::where([
                    //             'voucher_id' => $voucher->voucher_id, 
                    //             'gl_account_id' => $detail->gl_account_id, 
                    //             'entity' => (new AcctgAccountDisbursement)->getTable(), 
                    //             'entity_id' => NULL
                    //         ])->get();
                    //         if ($res2->count() > 0) {
                    //             AcctgTrialBalance::whereId($res2->first()->id)->update([
                    //                 'voucher_id' => $voucher->voucher_id,
                    //                 'payee_id' => $voucher->payee_id,
                    //                 'fund_code_id' => $voucher->fund_code_id,
                    //                 'gl_account_id' => $detail->gl_account_id,
                    //                 'sl_account_id' => $detail->sl_account_id,
                    //                 'debit' => $detail->debit,
                    //                 'credit' => $detail->credit,
                    //                 'posted_at' => $voucher->posted_at,
                    //                 'posted_by' => $voucher->posted_by
                    //             ]);
                    //         } else {
                    //             AcctgTrialBalance::create([
                    //                 'voucher_id' => $voucher->voucher_id,
                    //                 'payee_id' => $voucher->payee_id,
                    //                 'fund_code_id' => $voucher->fund_code_id,
                    //                 'gl_account_id' => $detail->gl_account_id,
                    //                 'sl_account_id' => $detail->sl_account_id,
                    //                 'debit' => $detail->debit,
                    //                 'credit' => $detail->credit,
                    //                 'posted_at' => $voucher->posted_at,
                    //                 'posted_by' => $voucher->posted_by,
                    //                 'entity' => (new AcctgAccountDisbursement)->getTable(),
                    //                 'entity_id' => NULL
                    //             ]);
                    //         }
                    //     }
                    // }
                }
            }   
        }
        return true;
    }

    public function remove_all_payments($request, $details)
    {
        AcctgAccountDisbursement::whereIn('id', $request->payments)->update($details);
        return true;
    }

    public function get_payables_details($voucher)
    {
        $res = AcctgAccountPayable::select([
            'acctg_payables.*',
            DB::raw('SUM(acctg_payables.paid_amount) as totalAmt2'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt'),
            DB::raw('SUM(IF(acctg_payables.ewt_id="1",ewt_amount,0)) as ewtAmt_1'),
            DB::raw('SUM(IF(acctg_payables.ewt_id="2",ewt_amount,0)) as ewtAmt_2'),
            DB::raw('SUM(IF(acctg_payables.evat_id="1",evat_amount,0)) as evatAmt_3'),
            DB::raw('SUM(IF(acctg_payables.evat_id="2",evat_amount,0)) as evatAmt_5'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
        })
        ->where('acctg_payables.voucher_id', '=', $voucher)
        ->where([
           'acctg_payables.is_active' => 1,
           'acctg_payables.status' => 'posted'
        ]);
        $res = $res->groupBy(['acctg_vouchers.id'])->get();

        $arr = array();
        if ($res->count() > 0) {
            $res = $res->first();
            if ($res->ewtAmt_1 > 0 || $res->ewtAmt_2 > 0 || $res->evatAmt_3 > 0 || $res->evatAmt_5 > 0) {
                $amount = floatval($res->ewtAmt_1) + floatval($res->ewtAmt_2) + floatval($res->evatAmt_3) + floatval($res->evatAmt_5);
                $glAccount = ($res->ewt_id != NULL) ? $res->ewt->gl_account : $res->evat->gl_account;
                $arr[] = (object) [
                    'gl_account_id' => $glAccount->id,
                    'sl_account_id' => NULL,
                    'debit' => 0,
                    'credit' => $amount
                ];
            } 
            if ($res->totalAmt > 0) {
                $arr[] = (object) [
                    'gl_account_id' => $this->get_account_payable(),
                    'sl_account_id' => NULL,
                    'debit' => $res->totalAmt,
                    'credit' => 0
                ];
            }
        }
        return $arr;
    }

    public function get_collections_details($voucher)
    {
        $res = AcctgAccountIncome::select([
            'acctg_incomes.*',
            DB::raw('SUM(acctg_incomes.paid_amount) as totalAmt2'),
            DB::raw('SUM(acctg_incomes.total_amount) as totalAmt'),
            DB::raw('SUM(IF(acctg_incomes.ewt_id="1",ewt_amount,0)) as ewtAmt_1'),
            DB::raw('SUM(IF(acctg_incomes.ewt_id="2",ewt_amount,0)) as ewtAmt_2'),
            DB::raw('SUM(IF(acctg_incomes.evat_id="1",evat_amount,0)) as evatAmt_3'),
            DB::raw('SUM(IF(acctg_incomes.evat_id="2",evat_amount,0)) as evatAmt_5'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
        })
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_vouchers.payee_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_incomes.gl_account_id');
        })
        ->where('acctg_incomes.voucher_id', '=', $voucher)
        ->where([
           'acctg_incomes.is_active' => 1,
           'acctg_incomes.status' => 'posted'
        ]);
        $res = $res->groupBy(['acctg_vouchers.id'])->get();

        $arr = array();
        if ($res->count() > 0) {
            if ($res->totalAmt > 0) {
                $arr[] = (object) [
                    'gl_account_id' => $this->get_account_payable(),
                    'sl_account_id' => NULL,
                    'debit' => $res->totalAmt,
                    'credit' => 0
                ];
            }
        }
        return $arr;
    }

    public function find_sl_bank($slID) 
    {
        return AcctgAccountSubsidiaryLedger::with(['bank'])->find($slID);
    }

    public function add_payments($request, $voucherID, $user, $timestamp)
    {   
        $data = [
            'voucher_id' => $voucherID,
            'gl_account_id' => AcctgAccountSubsidiaryLedger::find($request->sl_account_id)->gl_account_id,
            'sl_account_id' => $request->sl_account_id,
            'payment_type_id' => $request->payment_type_id,
            'payment_date' => date('Y-m-d', strtotime($request->payment_date)),
            'amount' => $request->amount,
            'bank_name' => $request->get('bank_name') ? $request->get('bank_name') : NULL,
            'bank_account_no' => $request->get('bank_account_no') ? $request->get('bank_account_no') : NULL,
            'bank_account_name' => $request->get('bank_account_name') ? $request->get('bank_account_name') : NULL,
            'cheque_no' => $request->get('cheque_no') ? urldecode($request->get('cheque_no')) : NULL,
            'cheque_date' => $request->get('cheque_date') ? $request->get('cheque_date') : NULL,
            'reference_no' => urldecode($request->get('reference_no')),
            'attachment' => $request->get('attachment'),
            'disburse_no' => $request->disburse_no,
            'created_at' => $timestamp,
            'created_by' => $user
        ];
        if ($request->disburse_type_id) {
            $data['disburse_type_id'] = $request->disburse_type_id;
        }
        $payment = AcctgAccountDisbursement::create($data);

        $res = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $voucherID, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        if ($res->count() > 0) {
            $res = $res->first();
            AcctgVoucher::whereId($voucherID)->update([
                'total_disbursement' => $res->totalAmt,
            ]);
        }

        return true;
    }

    public function update_paymentx($request, $paymentID, $user, $timestamp)
    {   
        $payRes = AcctgAccountDisbursement::find($paymentID);

        /**
         * process Cash in Local Treasury
         */
        if ($payRes->status == 'posted') {
            $res2 = AcctgTrialBalance::where([
                'voucher_id' => $payRes->voucher_id,
                'gl_account_id' => $payRes->gl_account_id, 
                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                'entity_id' => $paymentID
            ])->get();
            if ($res2->count() > 0) {
                AcctgTrialBalance::whereId($res2->first()->id)->update([
                    'voucher_id' => $payRes->voucher_id,
                    'payee_id' => $payRes->voucher->payee_id,
                    'fund_code_id' => $payRes->voucher->fund_code_id,
                    'gl_account_id' => $payRes->gl_account_id,
                    'sl_account_id' => $payRes->sl_account_id,
                    'credit' => $payRes->amount,
                    'posted_at' => $payRes->posted_at,
                    'posted_by' => $payRes->posted_by
                ]);
            } else {
                AcctgTrialBalance::create([
                    'voucher_id' => $payRes->voucher_id,
                    'payee_id' => $payRes->voucher->payee_id,
                    'fund_code_id' => $payRes->voucher->fund_code_id,
                    'gl_account_id' => $payRes->gl_account_id,
                    'sl_account_id' => $payRes->sl_account_id,
                    'credit' => $payRes->amount,
                    'posted_at' => $payRes->posted_at,
                    'posted_by' => $payRes->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                    'entity_id' => $paymentID
                ]);
            }
        }

        $payment = AcctgAccountDisbursement::whereId($paymentID)->update([
            'gl_account_id' => AcctgAccountSubsidiaryLedger::find($request->sl_account_id)->gl_account_id,
            'sl_account_id' => $request->sl_account_id,
            'payment_type_id' => 5,
            'bank_name' => $request->get('bank_name') ? $request->get('bank_name') : NULL,
            'bank_account_no' => $request->get('bank_account_no') ? $request->get('bank_account_no') : NULL,
            'bank_account_name' => $request->get('bank_account_name') ? $request->get('bank_account_name') : NULL,
            'attachment' => $request->get('attachment'),
            'status' => 'deposited',
            'updated_at' => $timestamp,
            'updated_by' => $user
        ]);

        /**
         * process Cash in Bank
         */
        $payRes2 = AcctgAccountDisbursement::find($paymentID);
        if ($payRes2->status == 'deposited') {
            $res2 = AcctgTrialBalance::where([
                'voucher_id' => $payRes2->voucher_id,
                'gl_account_id' => $payRes2->gl_account_id, 
                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                'entity_id' => $paymentID
            ])->get();
            if ($res2->count() > 0) {
                AcctgTrialBalance::whereId($res2->first()->id)->update([
                    'voucher_id' => $payRes2->voucher_id,
                    'payee_id' => $payRes2->voucher->payee_id,
                    'fund_code_id' => $payRes2->voucher->fund_code_id,
                    'gl_account_id' => $payRes2->gl_account_id,
                    'sl_account_id' => $payRes2->sl_account_id,
                    'credit' => $payRes2->amount,
                    'posted_at' => $payRes2->posted_at,
                    'posted_by' => $payRes2->posted_by
                ]);
            } else {
                AcctgTrialBalance::create([
                    'voucher_id' => $payRes2->voucher_id,
                    'payee_id' => $payRes2->voucher->payee_id,
                    'fund_code_id' => $payRes2->voucher->fund_code_id,
                    'gl_account_id' => $payRes2->gl_account_id,
                    'sl_account_id' => $payRes2->sl_account_id,
                    'credit' => $payRes2->amount,
                    'posted_at' => $payRes2->posted_at,
                    'posted_by' => $payRes2->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                    'entity_id' => $paymentID
                ]);
            }

            // START GL REPORTS
            $sql1 = AcctgGLAccountReport::where([
                'gl_account_id' => $payRes->gl_account_id,
                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                'entity_id' => $paymentID,
                'credit_amount' => NULL,
            ])->get();
            if ($sql1->count() > 0) {
                AcctgGLAccountReport::whereId($sql1->first()->id)->update([
                    'voucher_id' => $payRes->voucher_id,
                    'payee_id' => $payRes->voucher->payee_id,
                    'fund_id' => $payRes->voucher->fund_code_id,
                    'gl_account_id' => $payRes->gl_account_id,
                    'credit_amount' => $payRes->amount,
                    'posted_at' => $payRes->posted_at,
                    'posted_by' => $payRes->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $payRes->voucher_id,
                    'payee_id' => $payRes->voucher->payee_id,
                    'fund_id' => $payRes->voucher->fund_code_id,
                    'gl_account_id' => $payRes->gl_account_id,
                    'credit_amount' => $payRes->amount,
                    'posted_at' => $payRes->posted_at,
                    'posted_by' => $payRes->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                    'entity_id' => $paymentID,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
            $sql2 = AcctgGLAccountReport::where([
                'gl_account_id' => $payRes2->gl_account_id,
                'entity' => (new AcctgAccountDisbursement)->getTable(), 
                'entity_id' => $paymentID,
                'credit_amount' => NULL,
            ])->get();
            if ($sql2->count() > 0) {
                AcctgGLAccountReport::whereId($sql2->first()->id)->update([
                    'voucher_id' => $payRes2->voucher_id,
                    'payee_id' => $payRes2->voucher->payee_id,
                    'fund_id' => $payRes2->voucher->fund_code_id,
                    'gl_account_id' => $payRes2->gl_account_id,
                    'debit_amount' => $payRes2->amount,
                    'posted_at' => $payRes2->posted_at,
                    'posted_by' => $payRes2->posted_by,
                    'updated_at' => $timestamp,
                    'updated_by' => $user
                ]);
            } else {
                AcctgGLAccountReport::create([
                    'voucher_id' => $payRes2->voucher_id,
                    'payee_id' => $payRes2->voucher->payee_id,
                    'fund_id' => $payRes2->voucher->fund_code_id,
                    'gl_account_id' => $payRes2->gl_account_id,
                    'debit_amount' => $payRes2->amount,
                    'posted_at' => $payRes2->posted_at,
                    'posted_by' => $payRes2->posted_by,
                    'entity' => (new AcctgAccountDisbursement)->getTable(),
                    'entity_id' => $paymentID,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
            // END GL REPORTS
        }

        $res = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $payRes->voucher_id, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        if ($res->count() > 0) {
            $res = $res->first();
            AcctgVoucher::whereId($payRes->voucher_id)->update([
                'total_disbursement' => $res->totalAmt,
            ]);
        }

        return $payRes->voucher_id;
    }

    public function update_payments($request, $paymentID, $user, $timestamp)
    {   
        $payRes = AcctgAccountDisbursement::find($paymentID);
        $payment = AcctgAccountDisbursement::whereId($paymentID)->update([
            'voucher_id' => $payRes->voucher_id,
            'gl_account_id' => AcctgAccountSubsidiaryLedger::find($request->sl_account_id)->gl_account_id,
            'sl_account_id' => $request->sl_account_id,
            'payment_type_id' => $request->payment_type_id,
            'payment_date' => date('Y-m-d', strtotime($request->payment_date)),
            'amount' => $request->amount,
            'bank_name' => $request->get('bank_name') ? $request->get('bank_name') : NULL,
            'bank_account_no' => $request->get('bank_account_no') ? $request->get('bank_account_no') : NULL,
            'bank_account_name' => $request->get('bank_account_name') ? $request->get('bank_account_name') : NULL,
            'cheque_no' => $request->get('cheque_no') ? $request->get('cheque_no') : NULL,
            'cheque_date' => $request->get('cheque_date') ? $request->get('cheque_date') : NULL,
            'reference_no' => $request->reference_no,
            'attachment' => $request->get('attachment'),
            'disburse_no' => $request->disburse_no,
            'updated_at' => $timestamp,
            'updated_by' => $user
        ]);

        $res = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(amount) as totalAmt')
        ])
        ->where(['voucher_id' => $payRes->voucher_id, 'is_active' => 1])
        ->groupBy('voucher_id')
        ->get();

        if ($res->count() > 0) {
            $res = $res->first();
            AcctgVoucher::whereId($payRes->voucher_id)->update([
                'total_disbursement' => $res->totalAmt,
            ]);
        }

        return $payRes->voucher_id;
    }

    public function find_payments($paymentID) 
    {
        return AcctgAccountDisbursement::find($paymentID);
    }

    public function remove_payments($paymentID, array $newDetails) 
    {
        return AcctgAccountDisbursement::whereId($paymentID)->update($newDetails);
    }

    public function reCenter($centers)
    {
        $arrays = explode(',', $centers);
        $details = array();
        foreach ($arrays as $arr) {  
            if (!in_array($arr, $details)) {
                $details[] = trim($arr);
            }
        }

        return $details;
    }

    public function redefine($centers)
    {
        $arrays = explode(',', $centers);
        $iteration = 0; $details = '';
        foreach ($arrays as $arr) {            
            if ($iteration == 1) {
                $details .= '...';
                break; break;
            }
            $details .= $arr;
            $iteration++;
        }

        return $details;
    }

    public function find_voucher($voucher)
    {
        return AcctgVoucher::select('acctg_vouchers.*')->where(['acctg_vouchers.voucher_no' => $voucher])->get();
    }

    public function get_obligation_no($voucher)
    {   
        $res = CboAllotmentObligation::select(['alobs_control_no'])
        ->whereIn('departmental_request_id',                     
            BacRfqLine::select('gso_purchase_requests.departmental_request_id')
            ->leftJoin('gso_purchase_requests', function($join)
            {
                $join->on('gso_purchase_requests.id', '=', 'bac_rfqs_lines.purchase_request_id');
            })
            ->leftJoin('bac_rfqs', function($join)
            {
                $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
            })
            ->leftJoin('gso_purchase_orders', function($join)
            {
                $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
            })
            ->whereIn(
                'gso_purchase_orders.id',
                AcctgAccountPayable::select('gso_purchase_orders.id')
                ->leftJoin('acctg_vouchers', function($join)
                {
                    $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
                })
                ->leftJoin('gso_purchase_orders_posting_lines', function($join)
                {
                    $join->on('gso_purchase_orders_posting_lines.id', '=', 'acctg_payables.trans_id');
                })
                ->leftJoin('gso_purchase_orders_posting', function($join)
                {
                    $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
                })
                ->leftJoin('gso_purchase_orders', function($join)
                {
                    $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
                })
                ->where('acctg_vouchers.voucher_no', $voucher)
                ->get()
            )
        )
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) 
            {   
                if (!in_array($r->alobs_control_no, $arr)) {
                    $arr[] = $r->alobs_control_no;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function get_obligation_no_via_disbursement($voucher, $reference = '')
    {
        $res = CboAllotmentObligation::select(['cbo_allotment_obligations.*'])
        ->leftJoin('cto_disburse_details', function($join)
        {
            $join->on('cto_disburse_details.obligation_id', '=', 'cbo_allotment_obligations.id');
        })
        ->leftJoin('cto_disburse', function($join)
        {
            $join->on('cto_disburse.id', '=', 'cto_disburse_details.disburse_id');
        })
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'cto_disburse.voucher_id');
        })
        ->where([
            'cto_disburse_details.is_active' => 1,
            'acctg_vouchers.voucher_no' => $voucher,
            'cto_disburse.control_no' => $reference
        ])
        ->get();
        
        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) 
            {   
                if (!in_array($r->alobs_control_no, $arr)) {
                    $arr[] = $r->alobs_control_no;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }
    
    public function get_checque_no($voucher)
    {
        $res = AcctgAccountDisbursement::select([
            'acctg_disbursements.cheque_no as cheque'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where([
            'acctg_vouchers.voucher_no' => $voucher,
            'acctg_disbursements.is_active' => 1
        ])
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) 
            {   
                if (!in_array($r->cheque, $arr)) {
                    $arr[] = $r->cheque;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function get_invoice_no($voucher)
    {
        $res = AcctgAccountPayable::select('gso_purchase_orders_posting.reference_no')
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('gso_purchase_orders_posting_lines', function($join)
        {
            $join->on('gso_purchase_orders_posting_lines.id', '=', 'acctg_payables.trans_id');
        })
        ->leftJoin('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->leftJoin('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1])
        ->groupBy(['gso_purchase_orders_posting.id', 'gso_purchase_orders_posting.reference_no'])
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) 
            {   
                if (!in_array($r->reference_no, $arr)) {
                    $arr[] = $r->reference_no;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function get_payables($voucher, $posted = 1)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_payables.responsibility_center as centre',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where('acctg_payables.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_payables.gl_account_id', 'acctg_payables.responsibility_center'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }

    public function get_incomes($voucher, $deduction = 0, $posted = 1)
    {
        if (!($deduction > 0)) {
            $res = AcctgAccountIncome::select([
                'acctg_incomes.*',
                'acctg_incomes.responsibility_center as centre',
                DB::raw('SUM(acctg_incomes.ewt_amount) as ewtAmt'),
                DB::raw('SUM(acctg_incomes.evat_amount) as evatAmt'),
                DB::raw('SUM(acctg_incomes.total_amount) as totalAmt')
            ])
            ->with(['gl_account'])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
            })
            ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_incomes.is_active' => 1])
            ->whereNotNull('acctg_incomes.gl_account_id')
            ->where('acctg_incomes.gl_account_id', '!=', 0);
            if ($posted > 0) {
                $res = $res->where('acctg_incomes.status', '=', 'posted');
            }
            $res = $res->groupBy(['acctg_incomes.gl_account_id', 'acctg_incomes.responsibility_center'])
            ->get();

            $res = $res->map(function($line) {
                return (object) [
                    'centre' => $line->centre,
                    'gl_id' => $line->gl_account_id,
                    'gl_account' => $line->gl_account->description,
                    'gl_code' => $line->gl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            });
        } else {
            $res = AcctgAccountDeduction::select([
                'acctg_deductions.*',
                'acctg_deductions.responsibility_center as centre',
                DB::raw('SUM(acctg_deductions.ewt_amount) as ewtAmt'),
                DB::raw('SUM(acctg_deductions.evat_amount) as evatAmt'),
                DB::raw('SUM(acctg_deductions.total_amount) as totalAmt')
            ])
            ->with(['gl_account'])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
            })
            ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_deductions.is_active' => 1])
            ->whereNotNull('acctg_deductions.gl_account_id')
            ->where('acctg_deductions.gl_account_id', '!=', 0);
            if ($posted > 0) {
                $res = $res->where('acctg_deductions.status', '=', 'posted');
            }
            $res = $res->groupBy(['acctg_deductions.gl_account_id', 'acctg_deductions.responsibility_center'])
            ->get();

            $res = $res->map(function($line) {
                return (object) [
                    'centre' => $line->centre,
                    'gl_id' => $line->gl_account_id,
                    'gl_account' => $line->gl_account->description,
                    'gl_code' => $line->gl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            });
        }

        return $res;
    }

    public function get_sl_incomes($voucher, $deduction = 0, $gl_account, $posted = 1)
    {
        if (!($deduction > 0)) {
            $res = AcctgAccountIncome::select([
                'acctg_incomes.*',
                'acctg_incomes.responsibility_center as centre',
                DB::raw('SUM(acctg_incomes.ewt_amount) as ewtAmt'),
                DB::raw('SUM(acctg_incomes.evat_amount) as evatAmt'),
                DB::raw('SUM(acctg_incomes.total_amount) as totalAmt')
            ])
            ->with(['gl_account'])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
            })
            ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_incomes.gl_account_id' => $gl_account, 'acctg_incomes.is_active' => 1])
            ->whereNotNull('acctg_incomes.gl_account_id')
            ->where('acctg_incomes.gl_account_id', '!=', 0);
            if ($posted > 0) {
                $res = $res->where('acctg_incomes.status', '=', 'posted');
            }
            $res = $res->groupBy(['acctg_incomes.sl_account_id', 'acctg_incomes.responsibility_center'])
            ->get();

            $res = $res->map(function($line) {
                return (object) [
                    'centre' => $line->centre,
                    'sl_account' => $line->sl_account->description,
                    'sl_code' => $line->sl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            });
        } else {
            $res = AcctgAccountDeduction::select([
                'acctg_deductions.*',
                'acctg_deductions.responsibility_center as centre',
                DB::raw('SUM(acctg_deductions.ewt_amount) as ewtAmt'),
                DB::raw('SUM(acctg_deductions.evat_amount) as evatAmt'),
                DB::raw('SUM(acctg_deductions.total_amount) as totalAmt')
            ])
            ->with(['gl_account'])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_deductions.voucher_id');
            })
            ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_deductions.gl_account_id' => $gl_account, 'acctg_deductions.is_active' => 1])
            ->whereNotNull('acctg_deductions.gl_account_id')
            ->where('acctg_deductions.gl_account_id', '!=', 0);
            if ($posted > 0) {
                $res = $res->where('acctg_deductions.status', '=', 'posted');
            }
            $res = $res->groupBy(['acctg_deductions.sl_account_id', 'acctg_deductions.responsibility_center'])
            ->get();

            $res = $res->map(function($line) {
                return (object) [
                    'centre' => $line->centre,
                    'sl_account' => $line->sl_account->description,
                    'sl_code' => $line->sl_account->code,
                    'totalAmt' => $line->totalAmt
                ];
            });
        }

        return $res;
    }

    public function get_deposited($voucher)
    {
        $res = AcctgAccountDisbursement::select([
            'acctg_disbursements.*'
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_disbursements.is_active' => 1])
        ->where('acctg_disbursements.status', '=', 'deposited')
        ->get();

        return $res;
    }

    public function get_sum_deposits($voucher, $posted = 1)
    {
        $res = AcctgAccountDisbursement::select([
            '*',
            DB::raw('SUM(acctg_disbursements.amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_disbursements.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where(function($q) {
                $q->where('acctg_disbursements.status', '=', 'posted')
                ->orWhere('acctg_disbursements.status', '=', 'deposited');
            });
        }
        // ->groupBy(['acctg_disbursements.gl_account_id'])
        $res = $res->get();

        $res = $res->map(function($line) {
            return (object) [
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res->first();
    }

    public function get_centre_payables($voucher)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_payables.responsibility_center as centre',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1])
        ->groupBy(['acctg_payables.responsibility_center'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }

    public function get_centre_ewt_payables($voucher, $centre)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_payables.responsibility_center as centre',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt')
        ])
        ->with(['gl_account'])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.responsibility_center' => $centre,'acctg_payables.is_active' => 1])
        ->groupBy(['acctg_payables.gl_account_id', 'acctg_payables.responsibility_center'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'centre' => $line->centre,
                'gl_account' => $line->gl_account->description,
                'gl_code' => $line->gl_account->code,
                'totalEwt' => $line->ewtAmt,
                'totalEvat' => $line->evatAmt
            ];
        });

        return $res;
    }

    public function get_centre($voucher)
    {

        $res = AcctgAccountPayable::select([
            '*',
            'acctg_payables.responsibility_center as centre',
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1])
        ->groupBy(['acctg_payables.responsibility_center'])
        ->get();

        $arr = array();
        if ($res->count() > 0) {
            foreach ($res as $r) {
                if(!in_array($r->centre, $arr)) {
                    $arr[] = $r->centre;
                }
            }
        }

        return (count($arr) > 1) ? implode(', ', $arr) : implode('', $arr);
    }

    public function get_sum_payables($voucher)
    {
        $res = AcctgAccountPayable::select([
            '*',
            // 'acctg_payables.responsibility_center as centre',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt')
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1])
        ->get();

        $res = $res->map(function($line) use ($voucher) {
            return (object) [
                'centres' => $this->get_centre($voucher),
                'totalEwt' => $line->ewtAmt,
                'totalEvat' => $line->evatAmt,
                'totalAmt' => $line->totalAmt
            ];
        });

        return $res;
    }

    public function find_payable_gl()
    {
        return $res = AcctgAccountGeneralLedger::where(['is_payable' => 1])->first();
    }

    public function find_treasury_gl()
    {
        return $res = AcctgAccountGeneralLedger::where(['is_treasury' => 1])->first();
    }

    public function find_due_to_bir_gl()
    {
        return $res = AcctgAccountGeneralLedger::where(['is_due_to_bir' => 1])->first();
    }

    public function get_due_ewt_payables($voucher, $posted = 1)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_expanded_withholding_taxes.description as description',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_payables.ewt_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where('acctg_payables.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_payables.ewt_id'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'description' => $line->description,
                'totalEwt' => $line->ewtAmt
            ];
        });

        return $res;
    }

    public function get_due_evat_payables($voucher, $posted = 1)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_expanded_vatable_taxes.description as description',
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt')
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_payables.evat_id');
        })
        ->where(['acctg_vouchers.voucher_no' => $voucher, 'acctg_payables.is_active' => 1]);
        if ($posted > 0) {
            $res = $res->where('acctg_payables.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_payables.evat_id'])
        ->get();

        $res = $res->map(function($line) {
            return (object) [
                'description' => $line->description,
                'totalEvat' => $line->evatAmt
            ];
        });

        return $res;
    }

    public function get_gl_payments($voucher, $type, $reference = '', $posted = 1, $collection = 0)
    {       
        // dd($voucher);
        if (isset($reference)) {
            $res = AcctgAccountDisbursement::select([
                'acctg_account_general_ledgers.id as id',
                'acctg_account_general_ledgers.description as description',
                'acctg_account_general_ledgers.code as code',
                DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
            ])
            ->leftJoin('acctg_payment_types', function($join)
            {
                $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
            })
            ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
            {
                $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
            })
            ->where([
                'acctg_disbursements.reference_no' => $reference, 
                'acctg_vouchers.voucher_no' => $voucher, 
                'acctg_disbursements.is_active' => 1,
            ]);
            if (!($collection > 0)) {
                $res = $res->where(DB::raw('LOWER(acctg_payment_types.name)'), 'like', '%' . strtolower($type) . '%');
            }
            if ($posted > 0) {
                $res = $res->where(function($q) {
                    $q->where('acctg_disbursements.status', '=', 'posted')
                    ->orWhere('acctg_disbursements.status', '=', 'deposited');
                });
            }
            $res = $res->groupBy(['acctg_disbursements.gl_account_id'])
            ->get();
        } else {
            $res = AcctgAccountDisbursement::select([
                'acctg_account_general_ledgers.id as id',
                'acctg_account_general_ledgers.description as description',
                'acctg_account_general_ledgers.code as code',
                DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
            ])
            ->leftJoin('acctg_payment_types', function($join)
            {
                $join->on('acctg_payment_types.id', '=', 'acctg_disbursements.payment_type_id');
            })
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
            })
            ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
            {
                $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
            })
            ->where([
                'acctg_vouchers.voucher_no' => $voucher,
                'acctg_disbursements.is_active' => 1
            ]);
            if (!($collection > 0)) {
                $res = $res->where('acctg_vouchers.is_payables', 1)->where(DB::raw('LOWER(acctg_payment_types.name)'), 'like', '%' . strtolower($type) . '%');
            }
            if ($posted > 0) {
                $res = $res->where(function($q) {
                    $q->where('acctg_disbursements.status', '=', 'posted')
                    ->orWhere('acctg_disbursements.status', '=', 'deposited');
                });
            }
            $res = $res->groupBy(['acctg_disbursements.gl_account_id'])
            ->get();
        }
        
        $res = $res->map(function($line) {
            return (object) [
                'id' => $line->id,
                'code' => $line->code,
                'description' => $line->description,
                'totalPayment' => $line->totalPayment
            ];
        });

        return $res;
    }

    public function get_sl_payments($voucher, $gl_account, $reference = '', $posted = 1)
    {
        if (isset($reference)) {
            $res = AcctgAccountDisbursement::select([
                'acctg_account_subsidiary_ledgers.description as description',
                'acctg_account_subsidiary_ledgers.code as code',
                DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
            })
            ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
            {
                $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
            })
            ->where([
                'acctg_disbursements.reference_no' => $reference,
                'acctg_vouchers.voucher_no' => $voucher, 
                'acctg_account_general_ledgers.id' => $gl_account, 
                'acctg_disbursements.is_active' => 1
            ]);
            if ($posted > 0) {
                $res = $res->where(function ($query) {
                    $query->where('acctg_disbursements.status', '=', 'posted')
                        ->orWhere('acctg_disbursements.status', '=', 'deposited');
                });
            }
            $res = $res->groupBy(['acctg_disbursements.sl_account_id'])
            ->get();
        } else {
            $res = AcctgAccountDisbursement::select([
                'acctg_vouchers.id as voucher',
                'acctg_disbursements.id as id',
                'acctg_account_subsidiary_ledgers.description as description',
                'acctg_account_subsidiary_ledgers.code as code',
                DB::raw('SUM(acctg_disbursements.amount) as totalPayment')
            ])
            ->leftJoin('acctg_vouchers', function($join)
            {
                $join->on('acctg_vouchers.id', '=', 'acctg_disbursements.voucher_id');
            })
            ->leftJoin('acctg_account_general_ledgers', function($join)
            {
                $join->on('acctg_account_general_ledgers.id', '=', 'acctg_disbursements.gl_account_id');
            })
            ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
            {
                $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_disbursements.sl_account_id');
            })
            ->where([
                'acctg_vouchers.voucher_no' => $voucher, 
                'acctg_account_general_ledgers.id' => $gl_account, 
                'acctg_disbursements.is_active' => 1
            ]);
            if ($posted > 0) {
                $res = $res->where(function ($query) {
                    $query->where('acctg_disbursements.status', '=', 'posted')
                        ->orWhere('acctg_disbursements.status', '=', 'deposited');
                });
            }
            $res = $res->groupBy(['acctg_disbursements.sl_account_id'])
            ->get();
        }

        $res = $res->map(function($line) {
            return (object) [
                'code' => $line->code,
                'description' => $line->description,
                'totalPayment' => $line->totalPayment
            ];
        });

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
        $number =floor($no % $divider);
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
            return trim($result).' and '. (number_format($fraction,2) * 100) .'/100'.' pesos';
        }
        return $result.'pesos';
    }

    public function validate_voucher($voucher)
    {
        $res = AcctgVoucher::find($voucher);  
        if ($res->is_payables == 1) {   
            $payables = ($res->total_payables != NULL) ? floatval($res->total_payables) - floatval(floatval(floor(($res->total_ewt*100))/100) + floatval(floor(($res->total_evat*100))/100)) : 0;
            $payments = ($res->total_disbursement != NULL) ? floatval($res->total_disbursement) : 0;

            if (floatval($payables) <= 0 || floatval($payments) <= 0) {
                return 2;
            } else if (floatval(trim($payables)) > floatval(trim($payments))) {
                return 1;
            } else { 
                return 0;
            }
        }
    }

    public function get_payable_lines($voucher, $reference = '', $posted = 1)
    {
        $res = AcctgAccountPayable::select([
            '*',
            'acctg_payables.responsibility_center as centre',
            'acctg_account_general_ledgers.description as gl_name',
            'acctg_account_general_ledgers.code as gl_code',
            DB::raw('SUM(acctg_payables.ewt_amount) as ewtAmt'),
            DB::raw('SUM(acctg_payables.evat_amount) as evatAmt'),
            DB::raw('SUM(acctg_payables.total_amount) as totalAmt')
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_payables.voucher_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_payables.gl_account_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'acctg_payables.uom_id');
        })
        ->leftJoin('acctg_expanded_vatable_taxes', function($join)
        {
            $join->on('acctg_expanded_vatable_taxes.id', '=', 'acctg_payables.evat_id');
        })
        ->leftJoin('acctg_expanded_withholding_taxes', function($join)
        {
            $join->on('acctg_expanded_withholding_taxes.id', '=', 'acctg_payables.ewt_id');
        })
        ->where([
            'acctg_vouchers.voucher_no' => $voucher,
            // 'acctg_payables.trans_no' => $reference,
            'acctg_payables.is_active' => 1
        ]);
        if ($posted > 0) {
            $res = $res->where('acctg_payables.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_payables.gl_account_id', 'acctg_payables.responsibility_center'])
        ->get();
        
        return $res;
    }

    public function fetch_voucher_print($id, $type)
    {
        $res = AcctgVoucher::find($id);
        $data = '';
        switch ($type) {
            case 'collection':
                $data =  $res->collection_voucher_date ? $res->collection_voucher_date : '';
                break;
            case 'payables':
                $data =  $res->payables_voucher_date ? $res->payables_voucher_date : '';
                break;
            case 'cash':
                $data =  $res->cash_voucher_date ? $res->cash_voucher_date : '';
                break;
            case 'cheque':
                $data =  $res->cheque_voucher_date ? $res->cheque_voucher_date : '';
                break;
            case 'others':
                $data =  $res->others_voucher_date ? $res->others_voucher_date : '';
                break;
            default:
                $data = '';
        }
        return $data;
    }

    public function update_voucher_date($id, $type, $date, $preparedBy, $approvedBy, $timestamp)
    {
        $res = AcctgVoucher::find($id);
        switch ($type) {
            case 'collection':
                $res->collection_voucher_date = date('Y-m-d', strtotime($date));    
                $res->collection_voucher_prepared = $preparedBy;
                $res->collection_voucher_approver = $approvedBy;            
                break;
            case 'payables':
                $res->payables_voucher_date = date('Y-m-d', strtotime($date));
                $res->payables_voucher_prepared = $preparedBy;
                $res->payables_voucher_approver = $approvedBy;      
                break;
            case 'cash':
                $res->cash_voucher_date = date('Y-m-d', strtotime($date));
                $res->cash_voucher_prepared = $preparedBy;
                $res->cash_voucher_approver = $approvedBy;      
                break;
            case 'cheque':
                $res->cheque_voucher_date = date('Y-m-d', strtotime($date));
                $res->cheque_voucher_prepared = $preparedBy;
                $res->cheque_voucher_approver = $approvedBy;      
                break;                
            default:
                $res->others_voucher_date = date('Y-m-d', strtotime($date));
                $res->others_voucher_prepared = $preparedBy;
                $res->others_voucher_approver = $approvedBy;   
        }
        $res->update();

        $document = AcctgVoucherDocument::where('voucher_id', '=', $id)->where('document', '=', $type)->get();
        if ($document->count() > 0) {
            $documents = $document->last();
            if ($documents->status == 'for approval') {
                AcctgVoucherDocument::whereId($documents->id)->update([
                    'document' => $type,
                    'prepared_by' => $preparedBy,
                    'approval_by' => $approvedBy,
                    'updated_at' => $timestamp,
                    'updated_by' => $preparedBy,
                ]);
            } else {
                AcctgVoucherDocument::create([
                    'voucher_id' => $id,
                    'voucher_no' => $res->voucher_no,
                    'instance' => (floatval($document->count()) + 1),
                    'document' => $type,
                    'prepared_by' => $preparedBy,
                    'approval_by' => $approvedBy,
                    'sent_at' => $timestamp,
                    'sent_by' => $preparedBy,
                    'created_at' => $timestamp,
                    'created_by' => $preparedBy,
                ]);
            }
        } else {
            AcctgVoucherDocument::create([
                'voucher_id' => $id,
                'voucher_no' => $res->voucher_no,
                'instance' => (floatval($document->count()) + 1),
                'document' => $type,
                'prepared_by' => $preparedBy,
                'approval_by' => $approvedBy,
                'sent_at' => $timestamp,
                'sent_by' => $preparedBy,
                'created_at' => $timestamp,
                'created_by' => $preparedBy,
            ]);
        }

        return true;
    }

    public function get_voucher_approvers()
    {
        $users = User::whereIn('id',
            UserRoleSubModule::select(['users_role_sub_modules.user_id'])
            ->leftJoin('menu_sub_modules', function($join)
            {
                $join->on('menu_sub_modules.id', '=', 'users_role_sub_modules.menu_sub_module_id');
            })
            ->leftJoin('menu_modules', function($join)
            {
                $join->on('menu_modules.id', '=', 'menu_sub_modules.menu_module_id');
            })
            ->leftJoin('menu_groups', function($join)
            {
                $join->on('menu_groups.id', '=', 'menu_modules.menu_group_id');
            })
            ->where([
                'menu_groups.code' => 'accounting',
                'menu_modules.code' => 'accounting-journal-entries',
                'users_role_sub_modules.is_active' => 1
            ])
            ->where('users_role_sub_modules.permissions', 'like', '%approve%')  
            ->where(function($q) {
                $q->where('menu_sub_modules.code', 'like', '%accounting-journal-entries-payables%')  
                ->orWhere('menu_sub_modules.code', 'like', '%accounting-journal-entries-incomes%');
            })
            ->get()
        )->where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $userx = array();
        $userx[] = array('' => 'select a user');
        foreach ($users as $user) {
            $userx[] = array(
                $user->id => $user->name
            );
        }

        $users = array();
        foreach($userx as $usex) {
            foreach($usex as $key => $val) {
                $users[$key] = $val;
            }
        }

        return $users;
    }
    
    public function approval_docListItems($request, $user, $type = 0)
    {   
        $columns = array( 
            0 => 'acctg_vouchers_documents.id',
            1 => 'acctg_vouchers.voucher_no',
            2 => 'acctg_vouchers_documents.document',
            3 => 'emp1.fullname',
            4 => 'emp1.fullname'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_vouchers_documents.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgVoucherDocument::select([
            'acctg_vouchers_documents.*'
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_vouchers_documents.voucher_id');
        })
        ->leftJoin('hr_employees as emp1', function($join)
        {
            $join->on('emp1.user_id', '=', 'acctg_vouchers_documents.prepared_by');
        })
        ->leftJoin('hr_employees as emp2', function($join)
        {
            $join->on('emp2.user_id', '=', 'acctg_vouchers_documents.approval_by');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_vouchers.voucher_no', 'like', '%' . $keywords . '%')   
                ->orWhere('acctg_vouchers_documents.document', 'like', '%' . $keywords . '%')   
                ->orWhere('emp1.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('emp2.fullname', 'like', '%' . $keywords . '%');
            }
        })
        ->where('acctg_vouchers.is_payables', $type)
        ->where('acctg_vouchers_documents.approval_by', '=', $user);
        if ($status != 'all') {
            $res = $res->where('acctg_vouchers_documents.status', $status);
        }
        $res   = $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function fetch_document_status($id, $type)
    {   
        $status = [
            'for approval' => 1,
            'approved' => 2,
            'disapproved' => 3
        ];
        $res = AcctgVoucherDocument::where('voucher_id', '=', $id)->where('document', '=', $type)->get();    
        if ($res->count() > 0) {
            return intval($status[$res->last()->status]);
        } else {
            return intval(0);
        }
    }

    public function fetch_document_remarks($id, $type)
    {   
        $res = AcctgVoucherDocument::where('voucher_id', '=', $id)->where('document', '=', $type)->get();    
        if ($res->count() > 0) {
            return $res->last()->disapproved_remarks;
        } else {
            return '';
        }
    }

    public function fetch_document($id, $type)
    {   
        $res = AcctgVoucherDocument::where('voucher_id', '=', $id)->where('document', '=', $type)->get();  
        return $res->last();
    }

    public function find_document($id)
    {   
        return AcctgVoucherDocument::findOrFail($id);
    }

    public function update_document($id, array $newDetails) 
    {
        return AcctgVoucherDocument::whereId($id)->update($newDetails);
    }

    public function fetchApprovedBy($approvers)
    {
        $results = User::with(['hr_employee'])->whereIn('id', explode(',',$approvers))->get();
        $arr = array();
        foreach ($results as $res) {
            $arr[] = ucwords($res->hr_employee->fullname);
        }

        return implode(', ', $arr);
    }

    public function get_current_tax($voucher, $posted = 1)
    {
        $res = AcctgAccountIncome::select([
            'acctg_incomes.gl_account_id as gl_account_id',
            'acctg_incomes.sl_account_id as sl_account_id',
            'acctg_vouchers.fund_code_id as fund_id'
            // DB::raw('SUM(acctg_incomes.total_amount) as total_amount'),
        ])
        ->leftJoin('acctg_vouchers', function($join)
        {
            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
        })
        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
        {
            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_incomes.sl_account_id');
        })
        ->where([
            'acctg_account_subsidiary_ledgers.is_rpt_tax_cy' => 1,
            'acctg_incomes.is_active' => 1,
            'acctg_vouchers.voucher_no' => $voucher
        ]);
        if ($posted > 0) {
            $res = $res->where('acctg_incomes.status', '=', 'posted');
        }
        $res = $res->groupBy(['acctg_incomes.sl_account_id'])
        ->orderBy('acctg_incomes.sl_account_id', 'ASC')->get();

        $arr = array(); $arrs = array();
        if ($res->count() > 0) {
            $gl_codes = array();
            foreach ($res as $r) {
                $taxes = AcctgRptReceivableCY::where(['fund_code_id' => $r->fund_id,  'income_sl' => $r->sl_account_id, 'is_active' => 1])->get();
                if ($taxes->count() > 0) {
                    foreach ($taxes as $tax) {
                        if (!in_array($tax->gl_account->code, $gl_codes)) {
                            $arr[] = [
                                'gl_account_code' => $tax->gl_account->code,
                                'gl_account_desc' => $tax->gl_account->description,
                            ];
                            $gl_codes[] = $tax->gl_account->code;
                        }
                    }
                }
            }

            foreach ($res as $r) {
                $taxes = AcctgRptReceivableCY::where(['fund_code_id' => $r->fund_id,  'income_sl' => $r->sl_account_id, 'is_active' => 1])->get();                
                
                if ($taxes->count() > 0) {
                    foreach ($taxes as $tax) {
                        $res2 = AcctgAccountIncome::select([
                        ])
                        ->leftJoin('acctg_vouchers', function($join)
                        {
                            $join->on('acctg_vouchers.id', '=', 'acctg_incomes.voucher_id');
                        })
                        ->leftJoin('acctg_account_subsidiary_ledgers', function($join)
                        {
                            $join->on('acctg_account_subsidiary_ledgers.id', '=', 'acctg_incomes.sl_account_id');
                        })
                        ->where([
                            'acctg_account_subsidiary_ledgers.id' => $r->sl_account_id,
                            'acctg_account_subsidiary_ledgers.is_rpt_tax_cy' => 1,
                            'acctg_incomes.is_active' => 1,
                            'acctg_vouchers.voucher_no' => $voucher
                        ]);
                        if ($posted > 0) {
                            $res2 = $res2->where('acctg_incomes.status', '=', 'posted');
                        }
                        $res2 = $res2->sum('acctg_incomes.total_amount');        
                        $key = array_search($tax->gl_account->code, array_column($arr, 'gl_account_code'));     
                        $sample = [
                            'sl_account_code' => $tax->sl_account->code,
                            'sl_account_desc' => $tax->sl_account->description,
                            'total' => $res2,
                            'is_debit' => $tax->is_debit
                        ];  
                        array_push($arr[$key], ['childrens' => $sample]);
                        // $arr[$key]['childrens'] = $sample;
                        // dd($arr[$key]['childrens']);
                        // array_merge($arr[$key], $sample);
                        // array_push($arr[$key]['childrens'], $sample);   

                        // // $arr[$key]['childrens'][] = $sample;

                        // $arrs[] = $sample;
                    }
                }
            }
        }
        return $arr;
    }

    public function get_gl_rpt_current_tax($fund, $gl, $sl)
    {
        $res = AcctgRptReceivableCY::where(['fund_code_id' => $fund, 'gl_account_id' => $gl,  'income_sl' => $sl, 'is_active' => 1])->get();
        return $res;
    }

    public function get_rpt_current_tax($fund, $sl)
    {
        $res = AcctgRptReceivableCY::where(['fund_code_id' => $fund, 'income_sl' => $sl, 'is_active' => 1])->get();
        return $res;
    }
}