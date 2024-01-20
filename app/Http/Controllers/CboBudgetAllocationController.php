<?php

namespace App\Http\Controllers;
use App\Models\GsoDepartmentalRequisition;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Interfaces\CboBudgetInterface;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class CboBudgetAllocationController extends Controller
{
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CboBudgetAllocationInterface $cboBudgetAllocationRepository,
        HrEmployeeRepositoryInterface $hrEmployeeRepository,
        CboBudgetInterface $cboBudgetRepository,
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'finance/budget-allocations';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->cboBudgetAllocationRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->cboBudgetAllocationRepository->allEmployees();
        $designations = $this->cboBudgetAllocationRepository->allDesignations();
        $request_types = $this->cboBudgetAllocationRepository->allRequestTypes();
        $purchase_types = $this->cboBudgetAllocationRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        $funding_by = $this->cboBudgetAllocationRepository->allEmployees();
        $approval_by = $this->cboBudgetAllocationRepository->allEmployees();
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        return view('finance.budget-allocations.index')->with(compact('funding_by', 'approval_by', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements', 'categories'));
    }

    public function lists(Request $request)
    {      
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'pending' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="View"><i class="ti-pencil text-white"></i></a>';
        }
        $canDownload = $this->is_permitted($this->slugs, 'download', 1);
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1);
        $result = $this->cboBudgetAllocationRepository->listItems($request);
        $res = $result->data->map(function($requisition) use ($actions, $statusClass, $canDownload, $canUpdate) {
            $department = ($requisition->obligation->department_id !== NULL) ? wordwrap($requisition->obligation->department->code . ' - ' . $requisition->obligation->department->name . ' [' . (($requisition->obligation->division) ? $requisition->obligation->division->code : '') . ']', 25, "\n") : '';
            $controlNo  = $requisition->obligation->requisition ? $requisition->obligation->requisition->control_no : '';
            $reqType    = $requisition->obligation->requisition ? $requisition->obligation->requisition->req_type->description : '';
            $requestor  = $requisition->obligation->requisition ? $requisition->obligation->requisition->employee->fullname : '';
            $remarks    = wordwrap($requisition->obligation->prRemarks, 25, "\n");
            $particulars = wordwrap($requisition->particulars, 25, "\n");
            if ($requisition->obligation->type->id == 1) {
                $type = $requisition->obligation->type ? $requisition->obligation->type->name .'<br/>{ '.$reqType.' }' : ''; 
            } else {
                $type = $requisition->obligation->type ? $requisition->obligation->type->name : '';
            }
            $requisition->obligation->type ? $requisition->obligation->type->name : ''; 
            if ($canDownload > 0 && $requisition->obligStatus == 'completed') {
                // $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="Print"><i class="ti-printer text-white"></i></a>';
            } else {
                if ($canUpdate > 0) {
                    $actions .= '<a href="javascript:;" class="action-btn send-btn bg-print btn m-1 btn-sm align-items-center" title="Send"><i class="ti-arrow-right text-white"></i></a>';
                }
            }
            return [
                'id' => $requisition->obligId,
                'departmental_request' => $requisition->departmental_request_id,
                'type_id' => $requisition->obligation->obligation_type_id,
                'type' => ($requisition->obligation->type->id == 1) ?
                    '<div class="showLess" title="'. ($requisition->obligation->type ? $requisition->obligation->type->name.' { '.$reqType.' }' : '') .'">' . $type . '</div>'
                    :
                    '<div class="showLess" title="'. ($requisition->obligation->type ? $requisition->obligation->type->name : '') .'">' . $type . '</div>',
                'payee' => ($requisition->obligation->payee_id !== NULL) ? $requisition->obligation->payee->paye_name : '',
                'particulars' => '<div class="showLess">' . $particulars . '</div>',
                'control' => $requisition->obligation->budget_control_no,
                'budget_control' => '<strong class="text-primary">'.$requisition->obligation->budget_control_no.'</strong>',
                'control_no' => '<strong>' . $controlNo . '</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'request_type' => $reqType,
                'requestor' => '<strong>' . $requestor . '</strong>',
                'total_pr' => ($requisition->obligation->departmental_request_id > 0 ) ? $requisition->obligation->requisition->total_amount : 0,
                'total_alob' => $requisition->obligation->total_amount,
                'total' => $this->money_format($requisition->obligation->total_amount),
                'modified' => ($requisition->obligUpdatedAt !== NULL) ? 
                '<strong>'.$requisition->obligation->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->obligUpdatedAt)) : 
                '<strong>'.$requisition->obligation->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->obligCreatedAt)),
                'status' => $statusClass[$requisition->obligStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->obligStatus]->bg. ' p-2">' . $statusClass[$requisition->obligStatus]->status . '</span>',
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function item_lists(Request $request, $id)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg',
            'for pr approval' => 'for-approval-bg',
            'prepared' => 'prepared-bg',
            'quoted' => 'quoted-bg',
            'for rfq approval' => 'for-approval-bg',
            'estimated' => 'estimated-bg',
            'for abstract approval' => 'for-approval-bg',
            'awarded' => 'awarded-bg',
            'for resolution approval' => 'for-approval-bg',
            'for po approval' => 'for-approval-bg',
            'purchased' => 'purchased-bg',
            'partial' => 'purchased-bg',
            'posted' => 'completed-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg'
        ];
        $result = $this->cboBudgetAllocationRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass) {
            $unitPrice  = (floatval($requisition->purchase_unit_price) > 0) ? $requisition->purchase_unit_price : $requisition->request_unit_price;
            $totalPrice = (floatval($requisition->purchase_total_price) > 0) ? $requisition->purchase_total_price : $requisition->request_total_price;
            $items = wordwrap($requisition->item->code.' - ' .$requisition->item->name, 25, "\n");
            return [
                'id' => $requisition->itemId,
                'item' => $requisition->item->code.' - ' .$requisition->item->name,
                'item_details' => '<div class="showLess">' . $items. '</div>',
                'uom' => $requisition->uom->code,
                'req_quantity' => $requisition->quantity_requested,    
                'pr_quantity' => ($requisition->quantity_pr > 0) ? $requisition->quantity_pr : '',    
                'po_quantity' => ($requisition->quantity_po > 0) ? $requisition->quantity_po : '',    
                'posted_quantity' => ($requisition->quantity_posted > 0) ? $requisition->quantity_posted : '',  
                'unit_price' => $this->money_format($unitPrice), 
                'total_price' => '<strong>' . $this->money_format($totalPrice) . '</strong>',   
                'status' => $requisition->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->status]. ' p-2">' . $requisition->status . '</span>' ,
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function alob_lists(Request $request, $id)
    {       
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->cboBudgetAllocationRepository->listAlobLines($request, $id);
        $res = $result->data->map(function($alob) use ($actions) {
            $glDesc = wordwrap($alob->glDesc, 25, "\n");
            return [
                'id' => $alob->alobId,
                'gl_code' => $alob->glCode,
                'gl_desc' => '<div class="showLess">' . $glDesc. '</div>',   
                'budget_id' => $alob->budgetId,  
                'total' => $this->money_format($alob->budgetTotal), 
                'amount' => '<strong>' . $this->money_format($alob->alobAmt) . '</strong>', 
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function alob_lists2(Request $request, $id)
    {       
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->cboBudgetAllocationRepository->listAlobLines2($request, $id);
        $res = $result->data->map(function($alob) use ($actions) {
            $glDesc = wordwrap($alob->glDesc, 25, "\n");
            return [
                'id' => $alob->alobId,
                'gl_code' => $alob->glCode,
                'gl_desc' => '<div class="showLess">' . $glDesc. '</div>',   
                'budget_id' => $alob->budgetId,  
                'total' => $this->money_format($alob->budgetTotal), 
                'amount' => '<strong>' . $this->money_format($alob->alobAmt) . '</strong>', 
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->find($id),
            'alob' => $this->cboBudgetAllocationRepository->findAlobViaPr($id)->map(function($alob) {
                return (object) [
                    'allob_requested_date' => $alob->requisition->requested_date,
                    'control_no' => $alob->requisition->control_no,
                    'budget_no' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id' => $alob->department_id,                    
                    'allob_division_id' => $alob->division_id,
                    'budget_year' => $alob->budget_year,
                    'payee_id' => $alob->payee_id,
                    'fund_code_id' => $alob->fund_code_id,
                    'budget_category_id' => $alob->budget_category_id,
                    'address' => $alob->address,
                    'particulars' => $alob->particulars,
                    'funding_byz' => $alob->funding_by,
                    'approval_byz' => $alob->approval_by
                ];
            })
        ]);
    }

    public function findLine(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->findLine($id)
        ]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->reload_items($purchase_type)
        ]);
    }

    public function reload_uom(Request $request, $item) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->reload_uom($item)
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'employees' => $this->cboBudgetAllocationRepository->reload_employees($department),
            'divisions' => $this->cboBudgetAllocationRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->reload_designation($employee)
        ]);
    }

    public function computeTotalAmount($requisitionID)
    {
        return $this->cboBudgetAllocationRepository->computeTotalAmount($requisitionID);
    }

    public function fetch_status(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetAllocationRepository->find($requisitionID)->status
        ]);
    }

    public function print(Request $request, $controlNo)
    {
        $this->is_permitted($this->slugs, 'download');
        PDF::SetTitle('Hello World');
        PDF::AddPage('P', 'LETTER');
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
                <thead>
                    <tr>
                        <td colspan="5" align="center" style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black"><div style="font-size:1pt">&nbsp;</div>Republic of the Philippines</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="border-right: 1px solid black; border-left: 1px solid black"><div style="font-size:5pt">&nbsp;</div>Province of Nueva Ecija<div style="font-size:5pt">&nbsp;</div></td>
                    </tr>
                    <tr>    
                        <td style="border-bottom: 1px solid black; border-left: 1px solid black"></td>
                        <td colspan="3" align="center" style="border-bottom: 1px solid black;">CITY OF PALAYAN<div style="font-size:1pt">&nbsp;</div></td>
                        <td align="left" style="border-bottom: 1px solid black; border-right: 1px solid black">No:</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="font-size: 12px; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black"><div style="font-size:2pt">&nbsp;</div>Obligation Request<div style="font-size:2pt">&nbsp;</div></td>
                    </tr>
                    <tr>    
                        <td width="85" colspan="1" style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; font-size: 11px"> Payee </td>
                        <td width="470.5" colspan="4" align="left" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 11px"> </td>
                    </tr>
                    <tr>    
                        <td width="85" style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; font-size: 11px"> Office </td>
                        <td width="470.5" colspan="4" align="left" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 11px"> CMO </td>
                    </tr>
                    <tr>    
                        <td width="85" style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; font-size: 11px"> Address</td>
                        <td width="470.5" colspan="4" align="left" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 11px"> </td>
                    </tr>
                </thead>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::SetFont('Helvetica', '', 9);

        PDF::MultiCell(30, 9, 'Responsibilty Center', 1, 'C', 0, 0, '', '', true, 'C', 'C');
        PDF::MultiCell(80, 9, 'Particulars', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 9, 'F.O.P', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 9, 'Amount Code', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(36, 9, 'Amount', 1, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(30, 60, '', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(80, 60, 'Particulars', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 60, 'F.O.P', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 60, 'Amount Code', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(36, 60, 'Amount', 1, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(110, 5, '', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(86, 5, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln();
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
                <thead>
                    <tr>
                        <td width="311.8" colspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong><div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.7" colspan="3" align="center" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Charges to appropriation/allotment neccessary,<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.7" colspan="3" align="center" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>Existence of available appropriation</td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Lawful and under my direct supervision<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.7" colspan="3" align="center" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black; border-bottom: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;Supporting documents valid proper and legal<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.7" colspan="3" align="center" style="border-right: 0.7px solid black; border-bottom: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                </thead>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::ln();
        PDF::SetXY(22, 130);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::SetXY(22, 135);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::ln();
        PDF::ln();
        PDF::ln(2);
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
        <thead>
            <tr>
                <td width="70" colspan="1" align="center" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>Signature<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong><div style="font-size:1pt">&nbsp;</div></td>
                <td width="70" colspan="1" align="center" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>Signature<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.7" colspan="1" rowspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong><div style="font-size:1pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" colspan="1" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>Printed<div style="font-size:1pt">&nbsp;</div></td>
                <td width="70" colspan="1" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>Printed<div style="font-size:1pt">&nbsp;</div></td>
            </tr>
        </thead>
        </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::ln();

        PDF::Output('obligation_request.pdf');
    }

    public function update(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        $allottment = $this->cboBudgetAllocationRepository->find_alob($allotmentID);
        if ($allottment->obligation_type_id == 1) {
            $details = array(
                'address' => $request->get('address'),
                'particulars' => $request->get('particulars'),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        } else {
            $details = array(
                'fund_code_id' => $request->get('fund_code_id'),
                'address' => $request->get('address'),
                'particulars' => $request->get('particulars'),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
        }
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details),
            'title' => 'Well done!',
            'text' => 'The alob has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_payee_details(Request $request, $id, $column)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->fetch_payee_details($id, $column),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully fetched.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function view_alob_lines(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->view_alob_lines($requisitionID, $request->get('department'), $request->get('division'), $request->get('year'), $request->get('fund'), $request->get('category')),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function view_alob_lines2(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->view_alob_lines2($allotmentID, $request->get('department'), $request->get('division'), $request->get('year'), $request->get('fund'), $request->get('category')),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_row(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'update');
        $res = $this->cboBudgetAllocationRepository->update_row($requisitionID, $request->get('breakdown'), $request->get('gl_account'), $request->get('allocated'), $this->carbon::now(), Auth::user()->id);
        return response()->json([
            'remaining' => $res->remaining,
            'amount' => $res->amount, 
            'title' => 'Well done!',
            'text' => 'The row has been successfully updated.',
            'type' => $res->type,
            'class' => 'btn-brand'
        ]);
    }

    public function update_row2(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'update');
        $res = $this->cboBudgetAllocationRepository->update_row2($allotmentID, $request->get('breakdown'), $request->get('gl_account'), $request->get('allocated'), $this->carbon::now(), Auth::user()->id);
        return response()->json([
            'remaining' => $res->remaining,
            'amount' => $res->amount, 
            'title' => 'Well done!',
            'text' => 'The row has been successfully updated.',
            'type' => $res->type,
            'class' => 'btn-brand'
        ]);
    }

    public function computeBreakdownTotalAmount($allotmentID)
    {
        return $this->cboBudgetAllocationRepository->computeBreakdownTotalAmount($allotmentID);
    }

    public function remove_line(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'delete');
        $this->cboBudgetAllocationRepository->updateBreakdownLine($id, ['amount' => 0, 'is_active' => 0]); 
        $allotmentID = $this->cboBudgetAllocationRepository->findBreakdownLine($id)->allotment_id;
        $details['total_amount'] = $this->computeBreakdownTotalAmount($allotmentID);
        $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details);
        return response()->json(
            [
                'totalAmt' => ($details['total_amount'] > 0) ? $details['total_amount'] : 0,
                'title' => 'Well done!',
                'text' => 'The alob line has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function fetch_allotment_via_pr(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read');
        $column = $request->get('column');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->findAlobViaPr($requisitionID)->first()->$column,
            'title' => 'Well done!',
            'text' => 'The allotment has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_allotment_via_pr2(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read');
        $column = $request->get('column');
        return response()->json([
            'data' => $this->cboBudgetAllocationRepository->get_alob($allotmentID)->first()->$column,
            'title' => 'Well done!',
            'text' => 'The allotment has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $allotmentID)
    {   
        if ($status == 'for-alob-approval') {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => str_replace('-', ' ', $status),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if ($request->get('departmental') > 0) {
                $this->cboBudgetAllocationRepository->updateRequest($request->get('departmental'), $details);
                $this->gsoDepartmentalRequisitionRepository->track_dept_request($request->get('departmental'));
            }
            $details2 = array(
                'status' => 'for approval',
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details2),
                'budget_no' => '',
                // 'budget_no' => ($this->is_permitted($this->slugs, 'approve', 1) > 0) ? $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID) : '',
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'type' => 'danger',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function approve(Request $request, $allotmentID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'allocated',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            if ($request->get('departmental') > 0) {
                $this->cboBudgetAllocationRepository->updateRequest($request->get('departmental'), $details);
                $this->gsoDepartmentalRequisitionRepository->track_dept_request($request->get('departmental'));
            }
            $details2 = array(
                'status' => 'completed',
                'budget_no' => $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo(),
                'alobs_control_no' => $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID, $timestamp),
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            return response()->json([
                'data' => $this->cboBudgetAllocationRepository->updateAllotment($allotmentID, $details2),
                'budget_no' => ($this->is_permitted($this->slugs, 'approve', 1) > 0) ? $this->cboBudgetAllocationRepository->fetchBudgetSeriesNo($allotmentID) : '',
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_alob_status(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetAllocationRepository->find_alob($allotmentID)->status
        ]);
    }

    public function find_obligation(Request $request, $allotmentID): JsonResponse 
    {
        return response()->json([
            'alob' => $this->cboBudgetAllocationRepository->get_alob($allotmentID)->map(function($alob) {
                return (object) [
                    'allob_requested_date2' => $alob->date_requested,
                    'control_no2' => $alob->budget_control_no,
                    'budget_no2' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id2' => $alob->department_id,                    
                    'allob_division_id2' => $alob->division_id,
                    'budget_year2' => $alob->budget_year,
                    'payee_id2' => $alob->payee_id,
                    'fund_code_id2' => $alob->fund_code_id,
                    'address2' => $alob->address,
                    'particulars2' => $alob->particulars,
                    'funding_byx' => $alob->funding_by,
                    'approval_byx' => $alob->approval_by,
                    'with_pr2' => $alob->with_pr,
                    'employee_id2' => $alob->employee_id,
                    'designation_id2' => $alob->designation_id,
                    'budget_category_id2' => $alob->budget_category_id,
                ];
            })
        ]);
    }

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
        ]);
    }
}
