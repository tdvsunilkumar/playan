<?php

namespace App\Repositories;

use App\Interfaces\GsoObligationRequestInterface;
use App\Models\GsoDepartmentalRequisition;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseRequestType;
use App\Models\GsoPurchaseType;
use App\Models\GsoItem;
use App\Models\GsoUnitOfMeasurement;
use App\Models\CboAllotmentBreakdown;
use App\Models\CboAllotmentObligation;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;
use App\Models\CboAllotmentObligationRequest;
use App\Models\CboObligationType;
use App\Models\AcctgFundCode;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\CboObligationPayroll;
use App\Models\PayrollBreakdown;
use App\Models\HR\Payroll;
use DB;

class GsoObligationRequestRepository implements GsoObligationRequestInterface 
{
    public function getAll() 
    {
        return GsoDepartmentalRequisition::all();
    }

    public function find($id) 
    {
        return GsoDepartmentalRequisition::findOrFail($id);
    }
    
    public function validate($code, $id = '')
    {   
        if ($id !== '') {
            return GsoDepartmentalRequisition::where(['code' => $code])->where('id', '!=', $id)->count();
        } 
        return GsoDepartmentalRequisition::where(['code' => $code])->count();
    }

    public function create(array $details) 
    {
        return GsoDepartmentalRequisition::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return GsoDepartmentalRequisition::whereId($id)->update($newDetails);
    }

    public function validate_funds($type)
    {
        $res = AcctgFundCode::whereIn('id',
            CboObligationType::select('fund_code_id')->whereRaw('LOWER(cbo_obligation_types.name) LIKE ? ',['%'.trim(strtolower(str_replace('-', ' ',$type))).'%'])->get()
        )
        ->where(['is_active' => 1])
        ->get();

        if ($res->count() > 0) {
            return $res;
        } else {
            return AcctgFundCode::where(['is_active' => 1])->get();
        }
    }

    public function validate_gl_accounts($type)
    {
        $res = AcctgAccountGeneralLedger::whereIn('id',
            CboObligationType::select('gl_account_id')->whereRaw('LOWER(cbo_obligation_types.name) LIKE ? ',['%'.trim(strtolower(str_replace('-', ' ',$type))).'%'])->get()
        )
        ->where(['is_active' => 1])
        ->get();

        return $res;
    }

    public function find_obr_type($type)
    {
        return CboObligationType::whereRaw('LOWER(cbo_obligation_types.name) LIKE ? ',['%'.trim(strtolower(str_replace('-', ' ',$type))).'%'])->first();
    }

    public function listItems($request, $user = '')
    {  
        $columns = array( 
            0 => 'cbo_allotment_obligations.budget_control_no',
            1 => 'gso_departmental_requests.control_no',
            2 => 'acctg_departments.code',
            3 => 'hr_employees.fullname',  
            4 => 'cbo_allotment_obligations.particulars',  
            5 => 'cbo_allotment_obligations.particulars',  
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        
        $res = CboAllotmentObligationRequest::select([
            '*',
            'cbo_allotment_obligations.id as obligId',
            'cbo_allotment_obligations.department_id as obligDepartment',
            'cbo_allotment_obligations.created_at as obligCreatedAt',
            'cbo_allotment_obligations.updated_at as obligUpdatedAt',
            'cbo_allotment_obligations_requests.status as obligStatus',
            'cbo_allotment_obligations.created_by as created_by'
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_obligations_requests.allotment_id');
        })
        ->leftJoin('cbo_obligation_types', function($join)
        {
            $join->on('cbo_obligation_types.id', '=', 'cbo_allotment_obligations.obligation_type_id');
        })
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'cbo_allotment_obligations.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'cbo_allotment_obligations.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->whereRaw('LOWER(cbo_obligation_types.name) LIKE ? ',['%'.trim(strtolower(str_replace('-', ' ',$request->get('type')))).'%'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        });
        if ($user != '') {
            $ras = HrEmployee::where('user_id', $user)->first();
            if ($ras->is_dept_restricted > 0) {
                $res->whereIn('cbo_allotment_obligations.department_id',
                    HrEmployee::select('acctg_department_id')
                    ->where([
                        'user_id' => $user
                    ])
                    ->get()
                );
            } else {
                $res->whereIn('cbo_allotment_obligations.department_id', 
                    HrEmployeeDepartmentalAccess::select('hr_employees_departmental_access.department_id')
                    ->leftJoin('hr_employees', function($join)
                    {
                        $join->on('hr_employees.id', '=', 'hr_employees_departmental_access.employee_id');
                    })
                    ->where([
                        'hr_employees_departmental_access.is_active' => 1,
                        'hr_employees.user_id' => $user
                    ])
                    ->get()
                );
            }
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function approval_listItems($request, $user = '')
    {  
        $columns = array( 
            0 => 'cbo_allotment_obligations.budget_control_no',
            1 => 'gso_departmental_requests.control_no',
            // 3 => 'gso_purchase_request_types.description',
            2 => 'acctg_departments.code',
            3 => 'hr_employees.fullname',   
            4 => 'gso_departmental_requests.remarks',
            5 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        
        $res = CboAllotmentObligationRequest::select([
            '*',
            'cbo_allotment_obligations.id as obligId',
            'cbo_allotment_obligations.department_id as obligDepartment',
            'cbo_allotment_obligations.created_at as obligCreatedAt',
            'cbo_allotment_obligations.updated_at as obligUpdatedAt',
            'cbo_allotment_obligations_requests.status as obligStatus',
            'cbo_allotment_obligations_requests.approved_at as obligApprovedAt',
            'cbo_allotment_obligations_requests.approved_by as obligApprovedBy',
            'cbo_allotment_obligations_requests.disapproved_at as obligDisapprovedAt',
            'cbo_allotment_obligations_requests.disapproved_by as obligDisapprovedBy'
        ])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_obligations_requests.allotment_id');
        })
        
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cbo_allotment_obligations_requests.status', '!=', 'pending')
        ->where('cbo_allotment_obligations_requests.status', '!=', 'draft')
        ->where('cbo_allotment_obligations.departmental_request_id', 0);
        if ($user != '') {
            $ras = HrEmployee::where('user_id', $user)->first();
            if ($ras->is_dept_restricted > 0) {
                $res->whereIn('cbo_allotment_obligations.department_id',
                    HrEmployee::select('acctg_department_id')
                    ->where([
                        'user_id' => $user
                    ])
                    ->get()
                );
            } else {
                $res->whereIn('cbo_allotment_obligations.department_id', 
                    HrEmployeeDepartmentalAccess::select('hr_employees_departmental_access.department_id')
                    ->leftJoin('hr_employees', function($join)
                    {
                        $join->on('hr_employees.id', '=', 'hr_employees_departmental_access.employee_id');
                    })
                    ->where([
                        'hr_employees_departmental_access.is_active' => 1,
                        'hr_employees.user_id' => $user
                    ])
                    ->get()
                );
            }
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemsx($request)
    {             
        $columns = array( 
            0 => 'gso_departmental_requests.id',
            2 => 'gso_departmental_requests.control_no',
            3 => 'gso_purchase_request_types.description',
            4 => 'acctg_departments.code',
            5 => 'hr_employees.fullname',   
            6 => 'gso_departmental_requests.remarks',
            7 => 'gso_departmental_requests.total_amount'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = ['draft', 'for-approval'];

        $res = GsoDepartmentalRequisition::select([
            '*',
            'gso_departmental_requests.id as prId',
            'gso_departmental_requests.remarks as prRemarks',
            'gso_departmental_requests.created_at as prCreatedAt',
            'gso_departmental_requests.updated_at as prUpdatedAt',
            'gso_departmental_requests.is_active as prStatus'
        ])
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
        })
        ->leftJoin('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->leftJoin('gso_purchase_types', function($join)
        {
            $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.control_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_request_types.description', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.fullname', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests.remarks', 'like', '%' . $keywords . '%');
            }
        })
        ->whereNotIn('status', $status)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function listItemLines($request, $id)
    {             
        $columns = array( 
            0 => 'gso_departmental_requests_items.id',
            1 => 'gso_items.code',
            2 => 'gso_unit_of_measurements.code',
            8 => 'gso_unit_of_measurements.code',
            9 => 'gso_unit_of_measurements.code',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'gso_departmental_requests_items.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = GsoDepartmentalRequestItem::select([
            '*',
            'gso_departmental_requests_items.id as itemId',
            'gso_departmental_requests_items.remarks as itemRemarks',
            'gso_departmental_requests_items.created_at as itemCreatedAt',
            'gso_departmental_requests_items.updated_at as itemUpdatedAt',
            'gso_departmental_requests_items.is_active as itemStatus'
        ])
        ->leftJoin('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->leftJoin('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests_items.id', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_requested', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_pr', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_po', 'like', '%' . $keywords . '%')
                ->orWhere('gso_departmental_requests_items.quantity_posted', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%');
            }
        })
        ->where('gso_departmental_requests_items.departmental_request_id', $id)
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function  payrollComputationList($request, $id)
    {             
        $columns = array( 
            0 => 'hr_payroll_breakdown.id',
            1 => 'acctg_account_general_ledgers.code',
            2 => 'acctg_account_general_ledgers.description',
            3 => 'hr_payroll_breakdown.amount',
            4 => 'hr_payroll_breakdown.id',
        );
        $start     = $request->get('start');
        $limit     = (int)$request->get('length') > 0 ? $request->get('length') : null;
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_account_general_ledgers.code' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $payroll = CboObligationPayroll::where('allotment_id',$id)->first();

        $res = PayrollBreakdown::select([
            'description', 
            'code', 
            'payroll_no',
            DB::raw('sum(amount) as amount'), 
            'hr_payroll_breakdown.gl_id as payId'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'hr_payroll_breakdown.gl_id');
        })
        ->where('payroll_no', $payroll->payroll_no)
        ->groupby('gl_id')
        ->orderBy($column, $order);
        $count = $res->get()->count();
        if ($limit) {
            $res   = $res->skip($start)->take($limit)->get();
        } else {
            $res   = $res->get();
        }
        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function allDepartmentsWithRestriction($user)
    {
        return (new AcctgDepartment)->allDepartmentsWithRestriction($user);
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function allEmployees()
    {
        return (new HrEmployee)->allEmployees();
    }

    public function allCboEmployees()
    {
        return (new HrEmployee)->allCboEmployees();
    }

    public function allRequestTypes()
    {
        return (new GsoPurchaseRequestType)->allRequestTypes();
    }

    public function allPurchaseTypes()
    {
        return (new GsoPurchaseType)->allPurchaseTypes();
    }

    public function reload_items($purchase_type)
    {
        return (new GsoItem)->reload_items($purchase_type);
    }

    public function reload_uom($item)
    {
        return (new GsoUnitOfMeasurement)->find((new GsoItem)->find($item)->uom_id);
    }

    public function reload_employees($department)
    {
        return (new HrEmployee)->reload_cbo_employees($department);
    }

    public function reload_divisions($department)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department);
    }

    public function reload_designation($employee)
    {
        return (new HrDesignation)->find((new HrEmployee)->find($employee)->hr_designation_id);
    }

    public function generate_control_no($department)
    {   
        $date       = date('Y-m-d'); 
        $year       = date('Y'); 
        $month      = date('m');
        $count      = GsoDepartmentalRequisition::where('department_id', $department)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
        $controlNo  = AcctgDepartment::find($department)->shortname.'-';
        $controlNo .= substr($date, -2);
        $controlNo .= $month;

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

    public function createItem(array $details) 
    {
        return GsoDepartmentalRequestItem::create($details);
    }

    public function updateLine($id, array $newDetails) 
    {
        return GsoDepartmentalRequestItem::whereId($id)->update($newDetails);
    }

    public function findItem($itemId)
    {
        return GsoItem::find($itemId);
    }

    public function computeTotalAmount($requisitionId)
    {
        return \DB::select(\DB::raw('SELECT SUM((CASE WHEN purchase_total_price > 0 THEN purchase_total_price ELSE request_total_price END)) as totalAmt FROM  gso_departmental_requests_items WHERE is_active = 1 AND departmental_request_id='. $requisitionId .''))[0]->totalAmt;
    }

    public function findLine($id) 
    {
        return GsoDepartmentalRequestItem::findOrFail($id);
    }
    
    public function removeLine($id) 
    {
        GsoDepartmentalRequestItem::destroy($id);
    }

    public function updateLines($id, array $newDetails) 
    {
        return GsoDepartmentalRequestItem::where('departmental_request_id', $id)->update($newDetails);
    }

    public function findAlobViaPr($id)
    {
        return CboAllotmentObligation::where('departmental_request_id', $id)->get();
    }

    public function find_gl_code($controlNo)
    {
        $res = CboAllotmentBreakdown::select(['cbo_allotment_breakdowns.*'])
        ->leftJoin('cbo_allotment_obligations', function($join)
        {
            $join->on('cbo_allotment_obligations.id', '=', 'cbo_allotment_breakdowns.allotment_id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cbo_allotment_breakdowns.gl_account_id');
        })
        ->where(['cbo_allotment_obligations.is_active' => 1, 'cbo_allotment_obligations.budget_control_no' => $controlNo])
        ->groupBy(['acctg_account_general_ledgers.id'])
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r)
            {
                if (!in_array($r->gl_account->code, $arr)) {
                    $arr[] = $r->gl_account->code;
                }
            }
        }

        return implode(', ', $arr);
    }

    public function findAlobViaControlNo($controNo)
    {   
        // 
       return CboAllotmentObligation::with(['requestor', 'obligation'])->select([
            'cbo_allotment_obligations.*',
            'cbo_allotment_obligations.id',
            'cbo_allotment_obligations.with_pr',
            'cbo_allotment_obligations.department_id',
            'cbo_allotment_obligations.division_id',
            'cbo_allotment_obligations.payee_id',
            'cbo_allotment_obligations.fund_code_id',
            'gso_departmental_requests.status as prStatus',
            'cbo_allotment_obligations.particulars as alobParticulars',
            'cbo_allotment_obligations.address as alobAddress',
            'cbo_allotment_obligations.total_amount as alobAmount',
            'gso_departmental_requests.total_amount as totalAmount',
            'cbo_allotment_obligations.approved_at as alobApprovedAt',
            'acctg_fund_codes.code as fundcode',
            // 'acctg_account_general_ledgers.*',
            'cbo_allotment_obligations.alobs_control_no as alobNo',
            // 'acctg_account_general_ledgers.code as glCode',
            // DB::raw("(SELECT agl.code FROM 
            // cbo_allotment_breakdowns as cb 
            // LEFT JOIN acctg_account_general_ledgers as agl 
            // ON agl.id = cb.gl_account_id 
            // WHERE cb.allotment_id = cbo_allotment_obligations.id AND cb.is_active = 1) 
            // as glCode
            // "),
        ])
        ->leftJoin('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'cbo_allotment_obligations.departmental_request_id');
        })
        ->leftJoin('cbo_allotment_breakdowns', function($join)
        {
            $join->on('cbo_allotment_breakdowns.allotment_id', '=', 'cbo_allotment_obligations.id');
        })
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('cbo_allotment_breakdowns.gl_account_id', '=', 'acctg_account_general_ledgers.id');
        })
        ->leftJoin('acctg_fund_codes', function($join)
        {
            $join->on('cbo_allotment_obligations.fund_code_id', '=', 'acctg_fund_codes.id');
        })
        ->where('cbo_allotment_obligations.budget_control_no', $controNo,)
        ->get();
    }

    public function update_alob($id, array $newDetails) 
    {
        return CboAllotmentObligation::whereId($id)->update($newDetails);
    }

    public function find_alob_via_control_no($controlNo)
    {
        return CboAllotmentObligation::where(['budget_control_no' => $controlNo])->first();
    }
}