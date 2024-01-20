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
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\CboBudgetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GsoDepartmentalRequisitionController extends Controller
{
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoPurchaseRequestInterface $gsoPurchaseRequestRepository;
    private CboBudgetInterface $cboBudgetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoPurchaseRequestInterface $gsoPurchaseRequestRepository, 
        CboBudgetInterface $cboBudgetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseRequestRepository = $gsoPurchaseRequestRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->cboBudgetRepository = $cboBudgetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/departmental-requisitions';
    }    

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $departments = $this->gsoDepartmentalRequisitionRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoDepartmentalRequisitionRepository->allEmployees();
        $designations = $this->gsoDepartmentalRequisitionRepository->allDesignations();
        $request_types = $this->gsoDepartmentalRequisitionRepository->allRequestTypes();
        // $purchase_types = $this->gsoDepartmentalRequisitionRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $fund_codes = $this->gsoDepartmentalRequisitionRepository->allFundCodes();
        $categories = $this->cboBudgetRepository->allBudgetCategories();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        return view('general-services.departmental-requisitions.index')->with(compact('can_create', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'fund_codes', 'items', 'measurements', 'categories'));
    }

    public function lists(Request $request)
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
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg',
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn view-track-btn bg-secondary btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-stats-up text-white"></i></a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->gsoDepartmentalRequisitionRepository->listItems($request, Auth::user()->id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions, $actions2, $canUpdate, $canRead) {
            $department = wordwrap($requisition->department->code . ' - ' . $requisition->department->name . ' [' . $requisition->division->code . ']', 25, "\n");
            $remarks = wordwrap($requisition->prRemarks, 25, "\n");
            $funds = $requisition->fund ? wordwrap($requisition->fund->code.' - '.$requisition->fund->description, 25, "\n") : '';      
            if ($canUpdate > 0 && $requisition->status == 'draft') {
                $actions .= '<a href="javascript:;" class="action-btn send-btn bg-print btn m-1 btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>';
            }
            if ($canRead > 0 && $requisition->status != 'draft') {
                $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="download this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
            }
            return [
                'id' => $requisition->prId,
                'obr_no' => $requisition->obligation ? $requisition->obligation->budget_control_no : '',
                'control' => $requisition->control_no,
                'control_no' => '<strong class="text-primary">'.$requisition->control_no.'</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'request_type' => $requisition->req_type->description,
                // 'purchase_type' => $requisition->pur_type->description,
                'funds' => '<div class="showLess" title="' . ($requisition->fund ? $requisition->fund->code.' - '.$requisition->fund->description : '') . '">' . $funds . '</div>',
                'requestor' => '<strong>'.$requisition->employee->fullname.'</strong>',
                'total' => $this->money_format($requisition->total_amount),
                'remarks' => '<div class="showLess">' . $remarks. '</div>',
                'modified' => ($requisition->prUpdatedAt !== NULL) ? 
                '<strong>'.$requisition->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->prUpdatedAt)) : 
                '<strong>'.$requisition->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($requisition->prCreatedAt)),
                'status' => $requisition->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->status]. ' p-2">' . $requisition->status . '</span>' ,
                'actions' => ($requisition->status == 'cancelled') ? $actions2 : $actions
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoDepartmentalRequisitionRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions) {
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
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$requisition->status]. ' p-2">' . $requisition->status . '</span>' ,
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
            'data' => $this->gsoDepartmentalRequisitionRepository->find($id),
            'alob' => $this->cboBudgetAllocationRepository->findAlobViaPr($id)->map(function($alob) {
                return (object) [
                    'allob_requested_date' => $alob->requisition->requested_date,
                    'control_no' => $alob->budget_control_no,
                    'budget_no' => ($alob->budget_no == NULL) ? '' : $alob->fund_code->code . '-' . date('Y', strtotime($alob->approved_at)) . '-' . date('m', strtotime($alob->approved_at)) . '-' . $alob->budget_no,
                    'allob_department_id' => $alob->department_id,                    
                    'allob_division_id' => $alob->division_id,
                    'budget_year' => $alob->budget_year,
                    'payee_id' => $alob->payee_id,
                    'fund_code_id' => $alob->fund_code_id,
                    'address' => $alob->address,
                    'particulars' => $alob->particulars
                ];
            }),
            'pr' => $this->gsoPurchaseRequestRepository->find_via_pr($id)->map(function($pr) {
                return (object) [
                    'id' => $pr->id,
                    'departmental_request_id' => $pr->departmental_request_id,
                    'purchase_request_no' => $pr->purchase_request_no,
                    'prepared_date' => date('d-M-Y', strtotime($pr->prepared_date)),
                    'prepared_by' => $pr->prepared_by,
                    'pr_remarks' => $pr->remarks,
                    'approved_date' => $pr->approved_at ? date('d-M-Y', strtotime($pr->approved_at)) : ''
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $this->is_permitted($this->slugs, 'create');     
        $timestamp = $this->carbon::now();
        $control_no = $this->gsoDepartmentalRequisitionRepository->generate_control_no($request->department_id, $request->requested_date);
        $details = array(
            'department_id' => $request->get('department_id'),
            'division_id' => $request->get('division_id'),
            'employee_id' => $request->get('employee_id'),
            'designation_id' => $request->get('designation_id'),
            'budget_category_id' => $request->get('budget_category_id'),
            'request_type_id' => $request->get('request_type_id'),
            'fund_code_id' => $request->get('fund_code_id'),
            'control_no' => $control_no,
            'requested_date' => date('Y-m-d', strtotime($request->requested_date)),
            'remarks' => urldecode($request->get('remarks')),
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        $requisition = $this->gsoDepartmentalRequisitionRepository->create($details);
        if ($request->item_id > 0) {
            $this->storeItem($request, $requisition->id, $timestamp);
            $details['total_amount'] = $this->computeTotalAmount($requisition->id);
            $this->gsoDepartmentalRequisitionRepository->update($requisition->id, $details);
        }
        $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisition->id);
        return response()->json(
            [
                'data' => $requisition,
                'totalAmt' => ($details['total_amount'] > 0) ? $details['total_amount'] : 0,
                'title' => 'Well done!',
                'text' => 'The request item has been successfully added.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        $details = array(
            'department_id' => $request->get('department_id'),
            'division_id' => $request->get('division_id'),
            'employee_id' => $request->get('employee_id'),
            'designation_id' => $request->get('designation_id'),
            'budget_category_id' => $request->get('budget_category_id'),
            'request_type_id' => $request->get('request_type_id'),
            'fund_code_id' => $request->get('fund_code_id'),
            'requested_date' => date('Y-m-d', strtotime($request->requested_date)),
            'remarks' => urldecode($request->get('remarks')),
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        if ($request->item_id > 0) {
            $this->storeItem($request, $id, $timestamp);
            $details['total_amount'] = $this->computeTotalAmount($id);
        }
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->update($id, $details),
            'totalAmt' => ($details['total_amount'] > 0) ? $details['total_amount'] : 0,
            'title' => 'Well done!',
            'text' => 'The request item has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function storeItem($request, $requisitionID, $timestamp)
    {
        $this->is_permitted($this->slugs, 'create'); 
        $itemDetail = $this->gsoDepartmentalRequisitionRepository->findItem($request->item_id);
        $totalPrice = floatval(floatval($request->request_unit_price) * floatval($request->quantity_requested));
        $details = array(
            'departmental_request_id' => $requisitionID,
            'gl_account_id' => $itemDetail->gl_account_id,
            'item_id' => $request->item_id,
            'uom_id' => $request->get('uom_id'),
            'remarks' => $request->item_remarks,
            'quantity_requested' => $request->quantity_requested,
            'request_unit_price' => $request->request_unit_price,
            'request_total_price' => $totalPrice,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        return $this->gsoDepartmentalRequisitionRepository->createItem($details);
    }

    public function updateLine(Request $request, $id)
    {   
        $this->is_permitted($this->slugs, 'update');
        $lineDetail = $this->gsoDepartmentalRequisitionRepository->findLine($id);
        $itemDetail = $this->gsoDepartmentalRequisitionRepository->findItem($request->item_id);
        $totalPrice = floatval(floatval($request->request_unit_price) * floatval($request->quantity_requested));
        $details = array(
            'gl_account_id' => $itemDetail->gl_account_id,
            'item_id' => $request->item_id,
            'uom_id' => $request->get('uom_id'),
            'remarks' => $request->item_remarks,
            'quantity_requested' => $request->quantity_requested,
            'request_unit_price' => $request->request_unit_price,
            'request_total_price' => $totalPrice,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $this->gsoDepartmentalRequisitionRepository->updateLine($id, $details);
        $total = array(
            'total_amount' => $this->computeTotalAmount($lineDetail->departmental_request_id)
        );      
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->update($lineDetail->departmental_request_id, $total),
            'totalAmt' => ($total['total_amount'] > 0) ? $total['total_amount'] : 0,
            'title' => 'Well done!',
            'text' => 'The request item has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function findLine(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->findLine($id)
        ]);
    }

    public function removeLine(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'delete');
        $requisition = $this->gsoDepartmentalRequisitionRepository->findLine($id)->departmental_request_id;
        $this->gsoDepartmentalRequisitionRepository->removeLine($id);
        $details['total_amount'] = $this->computeTotalAmount($requisition);
        $this->gsoDepartmentalRequisitionRepository->update($requisition, $details);
        return response()->json(
            [
                'totalAmt' => ($details['total_amount'] > 0) ? $details['total_amount'] : 0,
                'title' => 'Well done!',
                'text' => 'The item line has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function reload_itemx(Request $request, $fund_code, $department = '', $division = '', $requestDate = '', $category = '') 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_itemx($fund_code, $department, $division, $requestDate, $category)
        ]);
    }

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_items($purchase_type)
        ]);
    }

    public function reload_items2(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_items($purchase_type)
        ]);
    }

    public function reload_uom(Request $request, $item) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_uom($item)
        ]);
    }

    public function reload_unit_cost(Request $request, $item) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'unit_cost' => $this->gsoDepartmentalRequisitionRepository->reload_unit_cost($item)
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'employees' => $this->gsoDepartmentalRequisitionRepository->reload_employees($department),
            'divisions' => $this->gsoDepartmentalRequisitionRepository->reload_divisions($department)
        ]);
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_designation($employee)
        ]);
    }

    public function computeTotalAmount($requisitionID)
    {
        return $this->gsoDepartmentalRequisitionRepository->computeTotalAmount($requisitionID);
    }

    public function fetch_status(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoDepartmentalRequisitionRepository->find($requisitionID)->status
        ]);
    }

    public function track_request(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->track_request($requisitionID)
        ]);
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoDepartmentalRequisitionRepository->fetch_remarks($id)->disapproved_remarks
        ]);
    }

    public function send(Request $request, $status, $requisitionID)
    {   
        $res = $this->gsoDepartmentalRequisitionRepository->find($requisitionID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            // if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            //     $details = array(
            //         'status' => 'requested',
            //         'sent_at' => $timestamp,
            //         'sent_by' => Auth::user()->id,
            //         'approved_at' => $timestamp,
            //         'approved_by' => Auth::user()->id
            //     );
            //     $allotmentDetail = array(
            //         'obligation_type_id' => 1,
            //         'budget_control_no' => $this->cboBudgetAllocationRepository->generateBudgetControlNo(date('Y')),
            //         'departmental_request_id' => $requisitionID,
            //         'department_id' => $res->department_id,
            //         'division_id' => $res->division_id,
            //         'fund_code_id' => $res->fund_code_id,
            //         'employee_id' => $res->employee_id,
            //         'designation_id' => $res->designation_id,
            //         'with_pr' => 1,
            //         'budget_year' => date('Y'),
            //         'created_at' => $timestamp
            //     );
            //     $allotment = $this->cboBudgetAllocationRepository->create($allotmentDetail);
            //     $allotmentRequestDetail = array(
            //         'allotment_id' => $allotment->id,
            //         'status' => 'completed',                    
            //         'sent_at' => $timestamp,
            //         'sent_by' => Auth::user()->id,
            //     );
            //     $allotmentRequest = $this->cboBudgetAllocationRepository->create_request($allotmentRequestDetail);
            // } else {
                $details = array(
                    'status' => str_replace('-', ' ', $status),
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, url('/send-departmental-request/'.$requisitionID));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_HEADER, 0);
                // curl_exec($ch);
                // curl_close($ch);
            // }
            $data = $this->gsoDepartmentalRequisitionRepository->update($requisitionID, $details);
            $lines = $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $details);
            return response()->json([
                'data' => $data,
                'lines' => $lines,
                'tracking' => $this->gsoDepartmentalRequisitionRepository->track_dept_request($requisitionID),
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'requisition' => $requisitionID,
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
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

    public function validate_item_request(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'validate' => $this->gsoDepartmentalRequisitionRepository->validate_item_request($request->get('line'), $request->get('fund'), date('Y', strtotime($request->get('year'))), $request->get('division'), $request->get('category'), $request->get('item'), $request->get('quantity')),
            'title' => 'Well done!',
            'text' => 'The item has been successfully validated.',
            'type' => 'success',
            'class' => 'btn-brand'
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
        $payee = $res->requestor ? $res->requestor->fullname : '';
        $address = $res->requestor ? $res->requestor->current_address : '';
        PDF::SetTitle($controlNo);
        PDF::AddPage('P', 'LETTER');
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
                        <td width="470.25" colspan="4" align="left" style="border-bottom: 0.7px solid black; border-right: 0.7px solid black; font-size: 11px"> ' . $res->department->shortname . ' </td>
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
        PDF::setCellHeightRatio(4);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(30, 60, $res->department->code.''.$res->division->code, 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(80, 60, $res->alobParticulars, 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 60, $res->fundcode, 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 60, $res->code, 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(35.87, 60, ($res->alobAmount ? $this->money_format($res->alobAmount, 1) : $this->money_format($res->totalAmount, 1) ), 1, 'R', 0, 0, '', '', true);
        PDF::setCellHeightRatio(1);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(110, 5, '', 1, 'C', 0, 0, '', '', true);
        PDF::MultiCell(85.87, 5, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln();
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
                <thead>
                    <tr>
                        <td width="311.8" colspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<strong>A Certified</strong><div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="center" style="border-right: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div></td>
                    </tr>
                    <tr>
                        <td width="84.8" colspan="1" align="left" style="border-left: 0.7px solid black;"><div style="font-size:1pt">&nbsp;</div>&nbsp;<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="227" colspan="1" align="left" style="border-right: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>&nbsp;Charges to appropriation/allotment neccessary,<div style="font-size:1pt">&nbsp;</div></td>
                        <td width="243.25" colspan="3" align="center" style="border-right: 0.7px solid black; font-size: 8px"><div style="font-size:4pt">&nbsp;</div>Existence of available appropriation</td>
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
        PDF::SetXY(22, 130);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::SetXY(22, 135);
        PDF::Cell(4, 4, '', 1, 0, 'C', 0, '', 0);
        PDF::ln();
        PDF::ln(4);
        $tbl = '<table id="obligation-request-print-table" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 10px;">
        <thead>
            <tr>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Signature<div style="font-size:3pt">&nbsp;</div></td>
                <td width="241.8" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;</td>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Signature<div style="font-size:3pt">&nbsp;</div></td>
                <td width="173.25" colspan="1" rowspan="2" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div>&nbsp;</td>
            </tr>
            <tr>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Printed<div style="font-size:2pt">&nbsp;</div></td>
                <td width="70" colspan="1" align="left" style="border-left: 0.7px solid black; font-size: 9px"><div style="font-size:2pt">&nbsp;</div>&nbsp;Printed<div style="font-size:2pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Name<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:2pt">&nbsp;</div><strong>' . ($res->approve_by ? $res->approve_by->fullname : '') . '</strong><div style="font-size:2pt">&nbsp;</div></td>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Name<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:2pt">&nbsp;</div><strong>' . ($res->fund_by ? $res->fund_by->fullname : '') . '</strong><div style="font-size:2pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:6pt">&nbsp;</div>&nbsp;Position<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 11px"><div style="font-size:1pt">&nbsp;</div>&nbsp;' . ($res->approve_designation ? strtoupper($res->approve_designation->description) : '') . '<div style="font-size:1pt">&nbsp;</div></td>
                <td width="70" colspan="1" rowspan="2" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:6pt">&nbsp;</div>&nbsp;Position<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 11px"><div style="font-size:1pt">&nbsp;</div>&nbsp;' . ($res->fund_designation ? strtoupper($res->fund_designation->description) : '') . '<div style="font-size:1pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="241.8" align="center" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 8px;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Head, Unit/authorized Representative<div style="font-size:3pt">&nbsp;</div></td>
                <td width="173.25" align="center" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 8px;"><div style="font-size:1pt">&nbsp;</div>&nbsp;Head, Unit/authorized Representative<div style="font-size:3pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Date<div style="font-size:1pt">&nbsp;</div></td>
                <td width="241.8" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div></td>
                <td width="70" align="left" style="border-left: 0.7px solid black; border-bottom:1px solid black; font-size: 9px"><div style="font-size:1pt">&nbsp;</div>&nbsp;Date<div style="font-size:1pt">&nbsp;</div></td>
                <td width="173.25" align="left" style="border-right: 0.7px solid black; border-left: 0.7px solid black; border-bottom:1px solid black"><div style="font-size:1pt">&nbsp;</div></td>
            </tr>
        </thead>
        </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
        PDF::ln();

        PDF::Output('obligation_request.pdf');
    }
}