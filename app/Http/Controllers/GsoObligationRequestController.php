<?php

namespace App\Http\Controllers;
use App\Models\GsoDepartmentalRequisition;
use App\Models\CommonModelmaster;
use App\Models\CboPayee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoObligationRequestInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use File;

class GsoObligationRequestController extends Controller
{   
    private GsoObligationRequestInterface $gsoObligationRequestRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private HrEmployeeRepositoryInterface $hrEmployeeRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoObligationRequestInterface $gsoObligationRequestRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        HrEmployeeRepositoryInterface $hrEmployeeRepository,
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->_commonmodel = new CommonModelmaster();
        $this->gsoObligationRequestRepository = $gsoObligationRequestRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->hrEmployeeRepository = $hrEmployeeRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'finance/obligation-requests';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->gsoObligationRequestRepository->allDepartmentsWithRestriction(Auth::user()->id);
        // $departments = $this->gsoObligationRequestRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoObligationRequestRepository->allCboEmployees();
        $designations = $this->gsoObligationRequestRepository->allDesignations();
        $request_types = $this->gsoObligationRequestRepository->allRequestTypes();
        $purchase_types = $this->gsoObligationRequestRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        $permissions = explode(',', $this->load_privileges($this->slugs));
        return view('finance.obligation-requests.index')->with(compact('permissions', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements', 'categories'));
    }

    public function lists(Request $request)
    {      
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg'
        ]; 
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $canDownload = $this->is_permitted($this->slugs, 'download', 1);
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1);
        $result = $this->gsoObligationRequestRepository->listItems($request, Auth::user()->id);
        $res = $result->data->map(function($requisition) use ($actions, $statusClass, $canDownload, $canUpdate) {
            // if ($requisition->obligation) {
                $department = ($requisition->obligation->department_id > 0) ? wordwrap($requisition->obligation->department->code . ' - ' . $requisition->obligation->department->name . ' [' . (($requisition->obligation->division) ? $requisition->obligation->division->code : '') . ']', 25, "\n") : '';
            // } else {
            //     $department = '';
            // }
            $controlNo  = $requisition->obligation->requisition ? $requisition->obligation->requisition->control_no : '';
            $reqType    = $requisition->obligation->requisition ? $requisition->obligation->requisition->req_type->description : '';
            $requestor  = $requisition->obligation->requestor ?  wordwrap($requisition->obligation->requestor->fullname, 25, "\n")  : '';
            $remarks    = wordwrap($requisition->obligation->prRemarks, 25, "\n");
            $particulars = $requisition->particulars ? wordwrap($requisition->particulars, 25, "\n") : '';
            if ($canDownload > 0 && $requisition->obligStatus == 'completed') {
                $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
                if($requisition->obligation->type->id != 1) {
                    $actions .= '<a href="javascript:;" class="action-btn print2-btn prepared-bg btn m-1 btn-sm align-items-center" title="dibursement voucher" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-paste text-white"></i></a>';
                }
            } else {
                if ($canUpdate > 0) {
                    $actions .= '<a href="javascript:;" class="action-btn send-btn bg-print btn m-1 btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>';
                }
            }
            return [
                'id' => $requisition->obligId,
                'alob_no' => $requisition->obligation->alobs_control_no ? $requisition->obligation->alobs_control_no : 0,
                'departmental_request' => $requisition->departmental_request_id,
                'payee' => ($requisition->obligation->payee_id !== NULL)? $requisition->obligation->payee->paye_name : '',
                // 'particulars' => '<div class="showLess">' . $particulars . '</div>',
                'control' => $requisition->obligation->budget_control_no,
                'budget_control' => '<strong class="text-primary">'.$requisition->obligation->budget_control_no.'</strong>',
                'control_no' => '<strong>' . $controlNo . '</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'particulars' => '<div class="showLess" title="'.( $requisition->particulars ? $requisition->particulars : '' ).'">' . $particulars . '</div>',
                'request_type' => $reqType,
                'requestor' => '<div class="showLess" title="'.($requisition->obligation->requestor ? $requisition->obligation->requestor->fullname : '').'">' . $requestor . '</div>',
                'total' => ($requisition->departmental_request_id > 0) ?  $this->money_format($requisition->obligation->requisition->total_amount) : $this->money_format($requisition->obligation->total_amount),
                'modified' => ($requisition->obligUpdatedAt !== NULL) ? 
                '<strong>'.$requisition->obligation->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->obligUpdatedAt)) : 
                '<strong>'.$requisition->obligation->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->obligCreatedAt)),
                'status' => $requisition->obligStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->obligStatus]. ' p-2">' .  $requisition->obligStatus . '</span>',
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
            'partial' => 'partial-bg',
            'posted' => 'completed-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg'
        ];
        $result = $this->gsoObligationRequestRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass) {
            $unitPrice  = (floatval($requisition->purchase_unit_price) > 0) ? $requisition->purchase_unit_price : $requisition->request_unit_price;
            $totalPrice = (floatval($requisition->purchase_total_price) > 0) ? $requisition->purchase_total_price : $requisition->request_total_price;
            $items = wordwrap($requisition->item->code.' - ' .$requisition->item->name, 25, "<br />\n");
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
                'total' => $this->money_format($requisition->totalAmount),
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

    public function payrollComputationList(Request $request, $id)
    {      
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg'
        ]; 
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" data-url="{{view_link}}" class="action-btn second-modal-show bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-eye text-white"></i></a>';
        }
        $result = $this->gsoObligationRequestRepository->payrollComputationList($request, $id);
        $res = $result->data->map(function($requisition) use ($actions, $statusClass) {
            $view_link = route('hr.payroll.calculate.view.gl',
            [
                'gl_id'=>$requisition->payId,
                'payroll_no'=>$requisition->payroll_no,
            ]);  
            return [
                'id' => $requisition->payId,
                'code' => wordwrap($requisition->code, 25, "\n"),
                'description' => wordwrap($requisition->description, 25, "\n"),
                'amount' => '<strong class="text-primary">'.'₱'.currency_format($requisition->amount).'</strong>',
                'actions' => str_replace('{{view_link}}', $view_link, $actions )
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
        // $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoObligationRequestRepository->find($id),
            'alob' => $this->gsoObligationRequestRepository->findAlobViaPr($id)->map(function($alob) {
                return (object) [
                    'allob_requested_date' => $alob->date_requested,
                    'control_no' => $alob->budget_control_no,
                    'budget_no' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id' => $alob->department_id,                    
                    'allob_division_id' => $alob->division_id,
                    'budget_year' => $alob->budget_year,
                    'payee_id' => $alob->payee_id,
                    'fund_code_id' => $alob->fund_code_id,
                    'address' => $alob->address,
                    'particulars' => $alob->particulars,
                    'with_pr' => $alob->with_pr,
                    'budget_category_id' => $alob->budget_category_id,
                    'budget_category_id2' => $alob->budget_category_id
                ];
            })
        ]);
    }

    public function validate_gl_funds(Request $request): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'funds' => $this->gsoObligationRequestRepository->validate_funds($request->get('type')),
            'gl_accounts' => $this->gsoObligationRequestRepository->validate_gl_accounts($request->get('type'))
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
                    'with_pr2' => $alob->with_pr,
                    'employee_id2' => $alob->employee_id,
                    'designation_id2' => $alob->designation_id,
                    'budget_category_id2' => $alob->budget_category_id
                ];
            })
        ]);
    }

    public function findLine(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoObligationRequestRepository->findLine($id)
        ]);
    }

    public function money_format($money, $is_pdf = 0)
    {   
        if ($is_pdf > 0) {
            return 'P' . number_format(floor(($money*100))/100, 2);
        }
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoObligationRequestRepository->reload_items($purchase_type)
        ]);
    }

    public function reload_uom(Request $request, $item) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoObligationRequestRepository->reload_uom($item)
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'employees' => $this->gsoObligationRequestRepository->reload_employees($department),
            'divisions' => $this->gsoObligationRequestRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoObligationRequestRepository->reload_designation($employee)
        ]);
    }

    public function computeTotalAmount($requisitionID)
    {
        return $this->gsoObligationRequestRepository->computeTotalAmount($requisitionID);
    }

    public function fetch_status(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoObligationRequestRepository->find($requisitionID)->status
        ]);
    }

    public function print(Request $request, $controlNo)
    {
        $this->is_permitted($this->slugs, 'download');
        $res = $this->gsoObligationRequestRepository->findAlobViaControlNo($controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        if ($res->prStatus == 'draft' || $res->prStatus == 'for approval') { 
            return abort(404);
        }
        $budgetNo = ($res->budget_no == NULL) ? '' : $res->alobs_control_no;
        // $budgetNo = ($res->budget_no == NULL) ? '' : $res->fund_code->code . '-' . date('Y', strtotime($res->alobApprovedAt)) . '-' . date('m', strtotime($res->alobApprovedAt)) . '-' . $res->budget_no;
        // $payee = ($res->payee_id == NULL) ? '' : ucwords($res->payee->paye_name); 
        $office = $res->department->shortname;
        if ($res->obligation_type_id == 1) {
            $payee = '';
            $address = '';
        } else if ($res->obligation_type_id == 4) {
            $payee = 'ACT';
            $address = '';
            $office = '';
        } else {
            $payee = $res->requestor ? $res->requestor->fullname : '';
            $address = $res->requestor ? $res->requestor->current_address : '';
        }

        $glCodes = $this->gsoObligationRequestRepository->find_gl_code($controlNo);
        PDF::SetTitle($controlNo);
        PDF::AddPage('P', 'LETTER');
        
        // if ($res->approve_by) {
        //     if (file_exists('uploads/e-signature/'.$res->approve_by->identification_no.'_'.urlencode($res->approve_by->fullname).'.png')) {
        //         PDF::Image(url('./uploads/e-signature/'.$res->approve_by->identification_no.'_'.urlencode($res->approve_by->fullname).'.png'), 52.5, 144, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
        //     }
        // }
        // if ($res->fund_by) {        
        //     if (file_exists('uploads/e-signature/'.$res->fund_by->identification_no.'_'.urlencode($res->fund_by->fullname).'.png')) {
        //         PDF::Image(url('./uploads/e-signature/'.$res->fund_by->identification_no.'_'.urlencode($res->fund_by->fullname).'.png'), 150.5, 144, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
        //     }
        // }
        
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
                <thead>
                    <tr>
                        <td colspan="5" align="center" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div>Republic of the Philippines</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black"><div style="font-size:5pt">&nbsp;</div>Province of Nueva Ecija<div style="font-size:5pt">&nbsp;</div></td>
                    </tr>
                    <tr>    
                        <td style="border-bottom: 0.7.px solid black; border-left: 0.7px solid black"></td>
                        <td colspan="3" width="330" align="center" style="border-bottom: 0.7px solid black;">CITY OF PALAYAN<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="114.25" align="left" style="border-bottom: 0.7px solid black; border-right: 0.7px solid black">No: ' . $res->alobNo . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="font-size: 12px; border-bottom: 0.7.px solid black; border-right: 0.7.px solid black; border-left: 0.7.px solid black"><div style="font-size:2pt">&nbsp;</div>Obligation Request<div style="font-size:2pt">&nbsp;</div></td>
                    </tr>
                    <tr>    
                        <td width="85" colspan="1" style="border-bottom: 0.7px solid black; border-left: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> Payee </td>
                        <td width="470.25" colspan="4" align="left" style="border-bottom: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> ' . $payee . ' </td>
                    </tr>
                    <tr>    
                        <td width="85" style="border-bottom: 0.7px solid black; border-left: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> Office </td>
                        <td width="470.25" colspan="4" align="left" style="border-bottom: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> ' . $office . ' </td>
                    </tr>
                    <tr>    
                        <td width="85" style="border-bottom: 0.7px solid black; border-left: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> Address</td>
                        <td width="470.25" colspan="4" align="left" style="border-bottom: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> ' . trim($address) . ' </td>
                    </tr>
                </thead>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::SetFont('Helvetica', '', 9);


        PDF::MultiCell(30, 9, 'Responsibilty Center', 1, 'C', 0, 0, '', '', true, 'C', 'C');
        PDF::setCellHeightRatio(2.5);
        PDF::MultiCell(80, 9, 'Particulars', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 9, 'F.O.P', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 9, 'Amount Code', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(35.87, 9, 'Amount', 1, 'C', 0, 0, '', '', true);
        PDF::setCellHeightRatio(1.5);
        PDF::ln();
        // PDF::SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => array(255, 255, 255)));
        PDF::SetFont('Helvetica', '', 10);
        if ($res->obligation_type_id === 4) {
            PDF::setCellHeightRatio(2);
            PDF::MultiCell(30, 15, $res->department->code.''.$res->division->code, 'LTR', 'C', 0, 0, '', '', true, 0, true);
            PDF::MultiCell(80, 15, "<b>".$res->alobParticulars."</b>", 'LTR', 'L', 0, 0, '', '', true, 0, true);
            PDF::MultiCell(20, 15, $res->fundcode, 'LTR', 'C', 0, 0, '', '', true, 0, true);
            PDF::MultiCell(30, 15, '', 'LTR', 'C', 0, 0, '', '', true, 0, true);
            PDF::MultiCell(35.87, 15, $this->money_format($res->alobAmount, 1), 'LTR', 'R', 0, 0, '', '', true, 0, true);
            PDF::ln();
            $allotment_height = 50/$res->allotments->count();
            foreach ($res->allotments as $allotments) {
                PDF::MultiCell(30, $allotment_height, '', 'LR', 'C', 0, 0, '', '', true);
                PDF::MultiCell(80, $allotment_height, $allotments->gl_account->description, 'LR', 'L', 0, 0, '', '', true);
                PDF::MultiCell(20, $allotment_height, $res->fundcode, 'LR', 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, $allotment_height, $allotments->gl_account->code, 'LR', 'C', 0, 0, '', '', true);
                PDF::MultiCell(35.87, $allotment_height, $this->money_format($allotments->amount, 1), 'LR', 'R', 0, 0, '', '', true);
                PDF::setCellHeightRatio(1);
                PDF::ln();
            }
        } else {
            PDF::setCellHeightRatio(4);
            PDF::MultiCell(30, 65, $res->department->code.''.$res->division->code, 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(80, 65, $res->alobParticulars, 1, 'L', 0, 0, '', '', true);
            PDF::MultiCell(20, 65, $res->fundcode, 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 65, $glCodes, 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(35.87, 65, $this->money_format($res->alobAmount, 1), 1, 'R', 0, 0, '', '', true);
            PDF::setCellHeightRatio(1);
            PDF::ln();
        }
        
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(110, 5, '', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(85.87, 5, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln();
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
                <thead>
                    <tr>
                        <td width="311.8" colspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong><div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="left" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong></td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>&nbsp;Charges to appropriation/allotment neccessary,<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="center" style="border-right: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>existence of available appropriation</td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>&nbsp;Lawful and under my direct supervision<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="center" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black; border-bottom: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>&nbsp;Supporting documents valid proper and legal<div style="font-size:3pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="center" style="border-right: 0.7px solid black; border-bottom: 0.7px solid black"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                </thead>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::ln();
        PDF::SetXY(22, 140);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::SetXY(130, 135);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::SetXY(22, 135);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::ln();
        PDF::ln(4);
        $y = PDF::GetY();
        PDF::SetFont('Helvetica', '', 10);
        PDF::SetXY(10, $y + .60);
        PDF::ln();

        $approvalTime = explode(',', $res->approved_datetime);
        // dd($approvalTime);
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px; ">
        <thead>
            <tr>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Signature<div style="font-size:3pt">&nbsp;</div></td>
                <td width="241.8" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;</td>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Signature<div style="font-size:3pt">&nbsp;</div></td>
                <td width="173.25" colspan="1" rowspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;</td>
            </tr>
            <tr>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Printed<div style="font-size:2pt">&nbsp;</div></td>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Printed<div style="font-size:2pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Name<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; "><div style="font-size:2pt">&nbsp;</div><strong>' . ($res->fund_by ? $res->fund_by->fullname : '') . '</strong><div style="font-size:2pt">&nbsp;</div></td>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Name<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black;"><div style="font-size:2pt">&nbsp;</div><strong>' . ($res->approve_by ? $res->approve_by->fullname : '') . '</strong><div style="font-size:2pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:6pt">&nbsp;</div>&nbsp;Position<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; font-size: 11px"><div style="font-size:1pt">&nbsp;</div>&nbsp;' . ($res->fund_designation ? strtoupper($res->fund_designation->description) : '') . '<div style="font-size:1pt">&nbsp;</div></td>
                <td width="70" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:6pt">&nbsp;</div>&nbsp;Position<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 11px"><div style="font-size:1pt">&nbsp;</div>&nbsp;' . ($res->approve_designation ? strtoupper($res->approve_designation->description) : '') . '<div style="font-size:1pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; font-size: 8px;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Head, Unit/authorized Representative<div style="font-size:3pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Head, Unit/authorized Representative<div style="font-size:3pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Date<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 8px"><div style="font-size:1pt">&nbsp;</div>'. ($approvalTime[0] ? date('d-M-Y h:i A', strtotime($approvalTime[0])) : '') .'</td>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Date<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 8px"><div style="font-size:1pt">&nbsp;</div>'. ($approvalTime[0] ? date('d-M-Y h:i A', strtotime($approvalTime[0])) : '') .'</td>
            </tr>
        </thead>
        </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::ln();

        $lineStyle = array('width' => 0.35, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        PDF::Line(120, 152.5, 34.8, 152.5, $lineStyle);
        PDF::Line(120, 157.5, 34.8, 157.5, $lineStyle);
        PDF::Line(120, 162, 34.8, 162, $lineStyle);
        PDF::Line(120, 166.3, 34.8, 166.3, $lineStyle);

        PDF::Line(144.75, 152.5, 206, 152.5, $lineStyle);
        PDF::Line(144.75, 157.5, 206, 157.5, $lineStyle);
        PDF::Line(144.75, 162, 206, 162, $lineStyle);
        PDF::Line(144.75, 166.3, 206, 166.3, $lineStyle);
        // PDF::Image(url('./uploads/e-signature/00000_Hon.+Viandrei+Nicole+Joson+Cuevas.png'), 50, 50, 100, '', '', '', '', false, 300, '', false, false);
        // PDF::Image(url('./uploads/e-signature/00000_Hon.+Viandrei+Nicole+Joson+Cuevas.png'), 50, 50, 100, '', '', '', '', false, 300, '', false, false);
        // PDF::SetAlpha(0.1);

        // $mask = PDF::Image(url('./uploads/e-signature/00000_Hon.+Viandrei+Nicole+Joson+Cuevas.png'), 50, 140, 100, '', '', '', '', false, 300, '', true);
        // PDF::Image(url('./uploads/e-signature/00000_Hon.+Viandrei+Nicole+Joson+Cuevas.png'), 50, 140, 100, '', '', 'http://www.tcpdf.org', '', false, 300, '', false, $mask);

        // PDF::Image(url('./uploads/e-signature/123_Atty Olga M. Berdon.png'), 150, 50, 35, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, false, false);
       // $inspectedId = HrEmployee::where('id', Auth::user()->hr_employee->id)->first();
        $filename = $res->id."-obligation_request.pdf";
        $arrSign= $this->_commonmodel->isSignApply('finance_budget_allocation_approve_funded_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('finance_budget_allocation_approve_approved_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
           PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($res->approve_by->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature($res->fund_by->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
               PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
               PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
       PDF::Output($filename,"I");
       //PDF::Output('cemetery_application.pdf');
       // PDF::Output('obligation_request.pdf');
    }

    public function money_format2($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function print_disbursement(Request $request, $controlNo)
    {
        $res = $this->gsoObligationRequestRepository->findAlobViaControlNo($controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();

        PDF::SetTitle('Disbursement Voucher ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(195.85, 5, 'Republic of the Philippines', 'TLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(195.85, 5, 'Province of Nueva Ecija', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 11);
        PDF::MultiCell(195.85, 5, 'CITY OF PALAYAN', 'BLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(195.85, 5, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(70.425, 5, '', 'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, 'DISBURSEMENT VOUCHER', 'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(70.425, 5, '', 'R', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'BLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(25.85, 20, 'Mode Of Payment', 'BLR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Check', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Cash', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Others', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(19, 20, '', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(25.85, 13, 'Payee:', 'BLR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(50, 13, $res->requestor->fullname, 'BLR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(22, 13, 'TIN/Emp No:', 'B', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(34, 13, '' , 'BR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(24, 5, 'Obligation No:', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(40, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::ln();
        PDF::MultiCell(85.85, 8, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(46, 8, ($res->requestor->tin_no != NULL) ? $res->requestor->tin_no : '' , 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::setCellHeightRatio(1.15);
        PDF::MultiCell(54, 8, $res->alobNo, 'BR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='T');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(25.85, 25.25, 'Address:', 'LRB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(50, 25.25, chr(10) . trim($res->requestor->current_address), 'BLR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='T');
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(22, 5, 'Po No:', 0, 'L', 0, 0, '', '', true);

        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(34, 5, '', 'R', 'L', 0, 0, '', '', true);

        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(64, 5, 'Responsibility Center:', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(75.85, 8, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);   
        PDF::MultiCell(46, 8, '' , 'BR', 'L', 0, 0, '', '', true);   


        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);        
        PDF::MultiCell(54, 8, $res->department->code.''.$res->division->code, 'BR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(75.85, 4.5, '', 0, 'L', 0, 0, '', '', true); 
        PDF::MultiCell(56, 4.25, 'Office/Unit/Project:', 'R', 'L', 0, 0, '', '', true);
        PDF::MultiCell(64, 4.25, 'Fund Code:', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(75.85, 4.25, '', '', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(46, 8, $res->department->shortname, 'BR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(54, 8, $res->fund_code->code.' - '.$res->fund_code->description, 'BR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(97.925, 5, 'EXPLANATION', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, 'AMOUNT', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $y = PDF::getY();
        PDF::SetFont('Helvetica', '', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(97.925, 80, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 80, '', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::setXY(15, $y);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(87.925, 80, '        '.$res->alobParticulars, 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
        PDF::setXY(112.925, $y);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(87.925, 80, 'Php' . $this->money_format2($res->alobAmount), 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(10.4625, 5, '', 'L', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 5, 'Certified', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '',  0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 5, 'Certified', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10.4625, 12, '', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 12, '    Allotment Obligated for the purpose indicated above. Supporting documents complete.', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 12, '', 'RB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10.4625, 12, '', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 12, '    Funds Available', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 12, '', 'RB', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);

        PDF::ln();
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true,);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->budget_officer ? strtoupper($res->obligation->budget_officer->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->treasurer ? strtoupper($res->obligation->treasurer->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
       
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->budget_officer_designation ? ucwords($res->obligation->budget_officer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->treasurer_designation ? ucwords($res->obligation->treasurer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 7.5, 'APPROVED PAYMENT', 'LB', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');
        PDF::MultiCell(97.925, 7.5, 'RECEIVED PAYMENT', 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');

        PDF::ln();
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);

        PDF::MultiCell(20, 5, 'Check No', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, 'Date', 'LR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(97.925, 10, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::ln();

        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true,);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->mayor ? strtoupper($res->obligation->mayor->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, strtoupper($res->requestor->fullname), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->treasurer_designation ? ucwords($res->obligation->treasurer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'Payee', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetXY(67, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(117, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(169, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::Output('disbursement_voucher_'.$controlNo.'.pdf');
    }

    public function fetch_alob_status(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->cboBudgetAllocationRepository->find_alob($allotmentID)->obligation->status
        ]);
    }

    public function update(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        $timestamp = $this->carbon::now();
        $year = ($request->get('budget_year') !== null) ? $request->get('budget_year') : date('Y');
        $type = ($request->get('type') !== null) ? $this->gsoObligationRequestRepository->find_obr_type($request->get('type')) : NULL;
        if ($request->get('department_id') !== null) {
            if ($allotmentID == 0) {
                $allotmentDetail = array(
                    'date_requested' => date('Y-m-d'),
                    'budget_control_no' => $this->cboBudgetAllocationRepository->generateBudgetControlNo($year),
                    'budget_year' => $year,
                    'created_at' => $timestamp,
                    'created_by' => Auth::user()->id
                );
                $allotment = $this->cboBudgetAllocationRepository->create($allotmentDetail);
                $allotmentID = $allotment->id;
                $allotmentRequestDetail = array(
                    'allotment_id' => $allotmentID
                );
                $allotmentRequest = $this->cboBudgetAllocationRepository->create_request($allotmentRequestDetail);
            }
            if ($request->get('employee_id') > 0) {
                $payee = CboPayee::select('id')->where(['hr_employee_id' => $request->get('employee_id'), 'paye_type' => 1])->get();
                if ($payee->count() > 0) {
                    $payeeID = $payee->first()->id;
                } else {
                    $payeeID = NULL;
                }
            } else {
                $payeeID = NULL;
            }
            $details = array(
                'obligation_type_id' => $type->id,
                'department_id' => $request->get('department_id'),
                'division_id' => $request->get('division_id'),
                'fund_code_id' => $request->get('fund_code_id'),
                'address' => urldecode($request->get('address')),
                'particulars' => urldecode($request->get('particulars')),
                'employee_id' => $request->get('employee_id'),
                'designation_id' => $request->get('designation_id'),
                'budget_category_id' => $request->get('budget_category_id'),
                'payee_id' => $payeeID,
                'with_pr' => $request->get('with_pr'),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoObligationRequestRepository->update_alob($allotmentID, $details);
            return response()->json([
                'data' => $this->cboBudgetAllocationRepository->find_alob($allotmentID),
                'title' => 'Well done!',
                'text' => 'The alob has been successfully updated.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
        return response()->json([
            'data' => '',
            'title' => 'Well done!',
            'text' => 'The alob has been successfully updated.',
            'type' => 'success',
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
        if ($status == 'for-approval') {
            $timestamp = $this->carbon::now();
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'completed',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'status' => str_replace('-', ' ', $status),
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id
                );
            }
            return response()->json([
                'data' => $this->cboBudgetAllocationRepository->update_request($allotmentID, $details),
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

    public function reload_division(Request $request, $department)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->hrEmployeeRepository->reload_division($department)
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
}
