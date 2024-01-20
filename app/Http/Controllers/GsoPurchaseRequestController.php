<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\GsoObligationRequestInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use File;
class GsoPurchaseRequestController extends Controller
{   
    private GsoPurchaseRequestInterface $gsoPurchaseRequestRepository;
    private GsoObligationRequestInterface $gsoObligationRequestRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPurchaseRequestInterface $gsoPurchaseRequestRepository, 
        GsoObligationRequestInterface $gsoObligationRequestRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->_commonmodel = new CommonModelmaster(); 
        $this->gsoPurchaseRequestRepository = $gsoPurchaseRequestRepository;
        $this->gsoObligationRequestRepository = $gsoObligationRequestRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/purchase-requests';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->gsoObligationRequestRepository->allDepartments();
        $divisions = ['' => 'select a division'];
        $employees = $this->gsoObligationRequestRepository->allEmployees();
        $designations = $this->gsoObligationRequestRepository->allDesignations();
        $request_types = $this->gsoObligationRequestRepository->allRequestTypes();
        $purchase_types = $this->gsoObligationRequestRepository->allPurchaseTypes();
        $allob_divisions = $this->cboBudgetAllocationRepository->allob_divisions();
        $fund_codes = $this->cboBudgetAllocationRepository->allFundCodes();
        $payees = $this->cboBudgetAllocationRepository->allPayees();
        $years  = $this->cboBudgetAllocationRepository->allBudgetYear();
        $unit_of_measurements = $this->gsoPurchaseRequestRepository->allUOMs();
        $items = ['' => 'select an item'];
        $measurements = ['' => 'select a uom'];
        return view('general-services.purchase-requests.index')->with(compact('unit_of_measurements', 'years', 'payees', 'fund_codes', 'allob_divisions', 'departments', 'divisions', 'employees', 'designations', 'request_types', 'purchase_types', 'items', 'measurements'));
    }

    public function lists(Request $request)
    {       
        // $restriction = ['draft', 'for approval', 'requested', 'allocated'];
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn send-btn bg-print btn m-1 btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>';
        }
        $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="#" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center digital-sign-btn" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->gsoPurchaseRequestRepository->listItems($request);
        $res = $result->data->map(function($purchase) use ($actions, $actions2, $statusClass) {
            $department = $purchase->obligation->department ? wordwrap($purchase->obligation->department->code . ' - ' . $purchase->obligation->department->name . ' [' . $purchase->obligation->division->code . ']', 25, "\n") : '';
            // $status     = ($requisition->prStatus == 'allocated' || $requisition->prStatus == 'for pr approval') ? ($requisition->prStatus == 'allocated') ? 'pending' : 'for approval' : 'completed';
            // $statusBg   = ($requisition->prStatus == 'allocated' || $requisition->prStatus == 'for pr approval') ? 'for-approval-bg' : 'completed-bg';
            // $remarks    = wordwrap($requisition->prRemarks, 25, "\n");
            $requestor = $purchase->obligation->requestor ? wordwrap($purchase->obligation->requestor->fullname, 25, "\n") : '';
            // dd($purchase->obligation->pur_request.' '.$purchase->obligation->id);
            if ($purchase->obligation->pur_request) {
                $modified = $purchase->obligation->pur_request->updated_at !== NULL ? '<strong>'.$purchase->obligation->pur_request->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->obligation->pur_request->updated_at)) : '<strong>'.$purchase->obligation->pur_request->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->obligation->pur_request->created_at));
            } else {
                $modified = $purchase->obligation->updated_at !== NULL ? '<strong>'.$purchase->obligation->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->obligation->updated_at)) : '<strong>'.$purchase->obligation->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->obligation->created_at));
            }
            return [
                'id' => $purchase->obligation->id,
                'departmental' => $purchase->obligation->departmental_request_id,
                'pr_no' => $purchase->obligation->pur_request ? $purchase->obligation->pur_request->purchase_request_no : '',
                'pr_no_label' => '<strong class="text-primary">'.($purchase->obligation->pur_request ? $purchase->obligation->pur_request->purchase_request_no : '').'</strong>',
                'control_no' => $purchase->obligation->alobs_control_no,
                'control_no_label' => '<strong>'. $purchase->obligation->alobs_control_no .'</strong>',
                'department' => '<div class="showLess" title="'.($purchase->obligation->department ? $purchase->obligation->department->code . ' - ' . $purchase->obligation->department->name . ' [' . $purchase->obligation->division->code . ']' : '').'">' . $department . '</div>',
                'request_type' => $purchase->obligation->type ? $purchase->obligation->type->name : '',
                'requestor' => '<div class="showLess" title="'.($purchase->obligation->requestor ? $purchase->obligation->requestor->fullname : '').'">' . $requestor . '</div>',
                'total' => ($purchase->departmental_id > 0) ? $this->money_format($purchase->obligation->requisition->total_amount) : $this->money_format($purchase->obligation->total_amount),
                'modified' => $modified,
                'status' => $statusClass[$purchase->obligation->pr_status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$purchase->obligation->pr_status]->bg. ' p-2">' . $statusClass[$purchase->obligation->pr_status]->status . '</span>' ,
                'actions' => ($statusClass[$purchase->obligation->pr_status]->status !== 'draft') ? $actions2 : $actions
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoDepartmentalRequisitionRepository->listItemLines($request, $id);
        $res = $result->data->map(function($requisition) use ($statusClass, $actions) {
            $unitPrice  = (floatval($requisition->purchase_unit_price) > 0) ? $requisition->purchase_unit_price : $requisition->request_unit_price;
            $totalPrice = (floatval($requisition->purchase_total_price) > 0) ? $requisition->purchase_total_price : $requisition->request_total_price;            
            if (strlen($requisition->pr_remarks) > 0) { 
                $items = wordwrap($requisition->item->code .' - ' . $requisition->item->name . ' (' . $requisition->pr_remarks . ')', 25, "\n");
            } else if (strlen($requisition->itemRemarks) > 0) {
                $items = wordwrap($requisition->item->code .' - ' . $requisition->item->name . ' (' . $requisition->itemRemarks . ')', 25, "\n");
            } else { 
                $items = wordwrap($requisition->item->code .' - ' . $requisition->item->name, 25, "\n"); 
            }            
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

    public function item_lists2(Request $request, $id)
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoPurchaseRequestRepository->listItemLines2($request, $id);
        $res = $result->data->map(function($prLine) use ($statusClass, $actions) {
            $unitPrice  = $prLine->request_unit_price;
            $totalPrice = $prLine->request_total_price;            
            if (strlen($prLine->remarks) > 0) { 
                $items = wordwrap($prLine->item_description. ' (' . $prLine->remarks . ')', 25, "\n");
            } else { 
                $items = wordwrap($prLine->item_description, 25, "\n"); 
            }            
            return [
                'id' => $prLine->id,
                'item_details' => '<div class="showLess">' . $items. '</div>',
                'uom' => $prLine->uom->code,
                'pr_quantity' => ($prLine->quantity_pr > 0) ? $prLine->quantity_pr : '',   
                'unit_price' => $this->money_format($unitPrice), 
                'total_price' => '<strong>' . $this->money_format($totalPrice) . '</strong>',   
                'status' => $prLine->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$prLine->status]. ' p-2">' . $prLine->status . '</span>' ,
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
                    'budget_no' => $alob->alobs_control_no,
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

    public function find_obligation(Request $request, $allotmentID): JsonResponse 
    {  
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'alob' => $this->cboBudgetAllocationRepository->get_alob($allotmentID)->map(function($alob) {
                return (object) [
                    'allob_requested_date2' => $alob->date_requested,
                    'control_no2' => $alob->budget_control_no,
                    'budget_no2' => $alob->alobs_control_no,
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
                ];
            }),
            'pr' => $this->gsoPurchaseRequestRepository->find_pr_via_alob($allotmentID)->map(function($pr) {
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


    public function alob_lists(Request $request, $allotmentID)
    {       
        $actions = '';
        // if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        // }
        $result = $this->cboBudgetAllocationRepository->listAlobLines2($request, $allotmentID);
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

    public function generate(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'create');
        return response()->json([
            // 'data' => $this->gsoPurchaseRequestRepository->create($request, $requisitionID,  Auth::user()->id, $this->carbon::now()),
            'data' => $this->gsoPurchaseRequestRepository->create($request, $allotmentID,  Auth::user()->id, $this->carbon::now()),
            'type' => 'success'
        ]);
    }

    public function update_pr_via_alob(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        return response()->json([
            'data' => $this->gsoPurchaseRequestRepository->update_pr_via_alob($request, $allotmentID, Auth::user()->id, $this->carbon::now()),
            'title' => 'Well done!',
            'text' => 'The purchase request has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_item_line(Request $request, $requestItemID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        return response()->json([
            'data' => $this->gsoPurchaseRequestRepository->update_item_line($requestItemID, $request->get('column'), $request->get('data'), Auth::user()->id, $this->carbon::now()),
            'title' => 'Well done!',
            'text' => 'The purchase request has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_pr_status_via_alob(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPurchaseRequestRepository->find_pr_via_alob($allotmentID)->first()->status,
            'title' => 'Well done!',
            'text' => 'The purchase request has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_status(Request $request, $requisitionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPurchaseRequestRepository->find_via_pr($requisitionID)->first()->status
        ]);
    }

    public function send(Request $request, $status, $allotmentID)
    {   
        if ($status == 'for-pr-approval') {
            $timestamp = $this->carbon::now();
            $alobs = $this->cboBudgetAllocationRepository->find_alob($allotmentID);
            if ($alobs->departmental_request_id > 0) {
                if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                    $details = array(
                        'status' => 'prepared',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details2 = array(
                        'purchase_request_no' => $this->gsoPurchaseRequestRepository->fetchPurchaseRequestNo(),
                        'status' => 'completed',
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id,
                        'approved_at' => $timestamp,
                        'approved_by' => Auth::user()->id
                    );
                    $lineDetails = array(
                        'status' => 'prepared',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details4 = array(
                        'pr_status' => 'completed',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                } else {
                    $details = array(
                        'status' => str_replace('-', ' ', $status),
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details2 = array(
                        'status' => 'for approval',
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id
                    );
                    $lineDetails = array(
                        'status' => 'for approval',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details4 = array(
                        'pr_status' => 'for approval',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                }
                $data = $this->gsoPurchaseRequestRepository->updateRequest($alobs->departmental_request_id, $details);
                return response()->json([
                    'data' => $data,
                    'datas' => $this->gsoPurchaseRequestRepository->update($alobs->departmental_request_id, $details2),
                    'tracking' => $this->gsoDepartmentalRequisitionRepository->track_dept_request($alobs->departmental_request_id),
                    'lines' => $this->is_permitted($this->slugs, 'approve', 1) ? $this->gsoDepartmentalRequisitionRepository->updateLines($alobs->departmental_request_id, $lineDetails) : '',
                    'info' => $this->gsoPurchaseRequestRepository->find_via_pr($alobs->departmental_request_id)->map(function($pr) {
                        return (object) [
                            'id' => $pr->id,
                            'departmental_request_id' => $pr->departmental_request_id,
                            'purchase_request_no' => $pr->purchase_request_no,
                            'prepared_date' => $pr->prepared_date ? date('d-M-Y', strtotime($pr->prepared_date)) : '',
                            'prepared_by' => $pr->prepared_by,
                            'pr_remarks' => $pr->remarks,
                            'approved_date' => $pr->approved_at ? date('d-M-Y', strtotime($pr->approved_at)) : ''
                        ];
                    }),
                    'data4' => $this->gsoPurchaseRequestRepository->update_alob($allotmentID, $details4),
                    'text' => 'The request has been successfully sent.',
                    'type' => 'success',
                    'status' => 'success'
                ]);
            } else {
                if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                    $details2 = array(
                        'purchase_request_no' => $this->gsoPurchaseRequestRepository->fetchPurchaseRequestNo(),
                        'status' => 'completed',
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id,
                        'approved_at' => $timestamp,
                        'approved_by' => Auth::user()->id
                    );
                    $lineDetails = array(
                        'status' => 'prepared',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details4 = array(
                        'pr_status' => 'completed',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                } else {
                    $details2 = array(
                        'status' => 'for approval',
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id
                    );
                    $lineDetails = array(
                        'status' => 'for approval',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                    $details4 = array(
                        'pr_status' => 'for approval',
                        'updated_at' => $timestamp,
                        'updated_by' => Auth::user()->id
                    );
                }
                return response()->json([
                    'datas' => $this->gsoPurchaseRequestRepository->update($alobs->departmental_request_id, $details2, $allotmentID),
                    'lines' => $this->is_permitted($this->slugs, 'approve', 1) ? $this->gsoPurchaseRequestRepository->updatePrLines($allotmentID, $lineDetails) : '',
                    'info' => $this->gsoPurchaseRequestRepository->find_pr_via_alob($allotmentID)->map(function($pr) {
                        return (object) [
                            'id' => $pr->id,
                            'departmental_request_id' => $pr->departmental_request_id,
                            'purchase_request_no' => $pr->purchase_request_no,
                            'prepared_date' => $pr->prepared_date ? date('d-M-Y', strtotime($pr->prepared_date)) : '',
                            'prepared_by' => $pr->prepared_by,
                            'pr_remarks' => $pr->remarks,
                            'approved_date' => $pr->approved_at ? date('d-M-Y', strtotime($pr->approved_at)) : ''
                        ];
                    }),
                    'data4' => $this->gsoPurchaseRequestRepository->update_alob($allotmentID, $details4),
                    'text' => 'The request has been successfully sent.',
                    'type' => 'success',
                    'status' => 'success'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'type' => 'danger',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function money_format($money, $is_pdf = 0)
    {   
        if ($is_pdf > 0) {
            return 'P' . number_format(floor(($money*100))/100, 2);
        }
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function view_item_lines(Request $request, $requisitionID)
    {
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->getAllItems($requisitionID)->map(function($item) {
                return (object) [
                    'id' => $item->id,
                    'item_id' => $item->item->id,
                    'item_code' => $item->item->code,
                    'item_desc' => (strlen($item->remarks) > 0) ? $item->item->name . ' (' . $item->remarks. ')' : $item->item->name,
                    'req_qty' => $item->quantity_requested,
                    'pr_qty' => $item->quantity_pr,
                    'pr_remarks' => ($item->pr_remarks !== null) ? $item->pr_remarks : ''
                ];
            }),
            'text' => 'The request has been successfully fetch.',
            'type' => 'success',
            'status' => 'success'
        ]);
        
    }

    public function print(Request $request, $prNum)
    {
        $this->is_permitted($this->slugs, 'download');
        $res = $this->gsoPurchaseRequestRepository->find_via_column('purchase_request_no', $prNum);

        if (!($res->count() > 0)) {
            return abort(404);
        }
        // $alobNo  = $this->gsoPurchaseOrderRepository->getAlobs($poNum);
        // $alobAmt = $this->gsoPurchaseOrderRepository->getAlobsAmount($poNum);
        // $prNo    = $this->gsoPurchaseOrderRepository->getPrNos($poNum);
        $res = $res->first();

        PDF::SetTitle('Purchase Request ('.$prNum.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(195.85, 5, 'LGU Form No. 5', 0, 'L', 0, 0, '', '', true);
        PDF::ln(4);
        PDF::MultiCell(195.85, 5, '(Revised 2002)', 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(163.85, 5, 'Control No.:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(31, 5, $res->requisition->control_no, 'B', 'C', 0, 0, '', '', true);
        PDF::ln(4.5);
        PDF::SetFont('Helvetica', 'B', 20);
        PDF::MultiCell(195.85, 5, 'PURCHASE REQUEST', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::ln(2);
        PDF::SetFont('Helvetica', 'B', 16);
        PDF::MultiCell(40, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(115.85, 5, 'LGU - PALAYAN CITY', 'B', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 12);
        PDF::MultiCell(195.85, 5, 'Agency Name', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetXY(10, 10); 
        PDF::MultiCell(195.85, 37, '', 'LTBR', 'C', 0, 0, '', '', true);
        PDF::ln();

        PDF::SetFont('Helvetica', '', 11);
        PDF::MultiCell(24, 5, 'Department: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(92, 5, $res->requisition->department->code . ' - ' . $res->requisition->department->name, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'P.R. No: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, $res->purchase_request_no, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(38, 5, 'Responsibility Code: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(78, 5, $res->requisition->division->code, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(12, 5, 'Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(61, 5, date('d-M-Y', strtotime($res->prepared_date)), 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(18, 5, 'Purpose:', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, $res->remarks, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(24, 5, 'ALOBS No.: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(49, 5, $res->allotment->alobs_control_no, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(18, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(12, 5, 'Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(61, 5, date('d-M-Y', strtotime($res->allotment->approved_at)), 0, 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 47); 
        PDF::MultiCell(120, 20, '', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(75.85, 20, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln(); 

        PDF::SetFont('Helvetica', 'B', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(18, 5, 'Item No.', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'Unit', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Quantity', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(78, 5, 'Description', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, 'Unit Cost', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, 'Amount', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', '', 10);
        PDF::ln(2); 
        $itemList = $this->gsoPurchaseRequestRepository->item_list_via_pr_num($prNum);
        $iteration = 0; $totalAmt = 0;
        foreach ($itemList as $item) {
            $iteration++;
            if (strlen($item->pr_remarks) > 0) { 
                $description = $item->item->code .' - ' . $item->item->name . ' (' . $item->pr_remarks . ')';
            } else if (strlen($item->itemRemarks) > 0) {
                $description = $item->item->code .' - ' . $item->item->name . ' (' . $item->itemRemarks . ')';
            } else { 
                $description = $item->item->code .' - ' . $item->item->name; 
            } 
            $unitCost = $item->request_unit_price;
            $totalCost = floatval($item->quantity_pr) * floatval($unitCost);
            $totalAmt += floatval($totalCost);
            $y = PDF::GetY();
            PDF::MultiCell(18, 5, $iteration, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(18, 5, $item->uom->code, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(20, 5, $item->quantity_pr, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(78, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, number_format(floor(($unitCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, number_format(floor(($totalCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);
            PDF::SetXY(66, $y);
            PDF::MultiCell(78, 5, $description, 0, 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::ln(1.5);
        }
        PDF::ln(); 
        PDF::SetFont('Helvetica', '', 11);
        PDF::setCellHeightRatio(1.25);
        PDF::SetXY(10, 72); 
        PDF::MultiCell(18, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(18, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(78, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(164.925, 5, '(Total Amount in Words) '.trim(ucfirst(strtolower($this->gsoPurchaseRequestRepository->numberTowords($totalAmt)))),  'TBL', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(30.925, 5, 'P'.number_format(floor(($totalAmt*100))/100, 2), 'TBR', 'R', 0, 0, '', '', true);
        PDF::ln();
        $y = PDF::GetY();
        PDF::MultiCell(97.925, 45, '', 'LBR', 'R', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 45, '', 'LBR', 'R', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetXY(10, $y);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 5, 'Requested By:',  '0', 'L', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, 'Approved By:',  '0', 'L', 0, 0, '', '', true);

        PDF::ln();
        PDF::MultiCell(97.925, 7.55, '',  '0', 'L', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, '',  '0', 'L', 0, 0, '', '', true);

        
        $requestor = strtoupper($res->requisition ? $res->requisition->employee->fullname : '');
        $approver = strtoupper($res->approver ? $res->approver->hr_employee->fullname : '');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(17.5, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(62.925, 5, $requestor,  '0', 'C', 0, 0, '', '', true);
        PDF::MultiCell(17.5, 5, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(17.5, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(62.925, 5, $approver,  '0', 'C', 0, 0, '', '', true);
        PDF::MultiCell(17.5, 5, '',  'R', 'C', 0, 0, '', '', true);

        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', '', 10);

        $lineStyle = array('width' => 0.35, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        if (strlen($requestor) > 25) {
            $y = PDF::GetY();
            PDF::SetXY(107.925, $y + 5);
            PDF::MultiCell(97.925, 5, ucwords(($res->requisition ? $res->requisition->designation->description : '')),  '0', 'C', 0, 0, '', '', true);
            PDF::Line(27.4, $y + 4, 90.5, $y + 4, $lineStyle);
        } else {
            $y = PDF::GetY();  
            PDF::MultiCell(97.925, 5, ucwords(($res->requisition ? $res->requisition->designation->description : '')),  '0', 'C', 0, 0, '', '', true);
            PDF::Line(27.4, $y, 90.5, $y, $lineStyle);
        }

        if (strlen($approver) > 25) {
            $y = PDF::GetY();            
            PDF::SetXY(107.925, $y + 5);
            PDF::MultiCell(97.925, 5, ucwords(($res->approver ? $res->approver->hr_employee->designation->description : '')),  '0', 'C', 0, 0, '', '', true);
            PDF::Line(188.5, $y + 4, 125.25, $y + 4, $lineStyle);
        } else {
            $y = PDF::GetY();
            PDF::MultiCell(97.925, 5, ucwords(($res->approver ? $res->approver->hr_employee->designation->description : '')),  '0', 'C', 0, 0, '', '', true);
            PDF::Line(188.5, $y, 125.25, $y, $lineStyle);
        }        
        

        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, '',  'LR', 'C', 0, 0, '', '', true);

        PDF::ln();
        PDF::MultiCell(37.5, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(22.925, 5, ($res->requisition ? date('d-M-Y', strtotime($res->requisition->requested_date)) : ''),  'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(37.5, 5, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(37.5, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(22.925, 5, ($res->approved_at ? date('d-M-Y', strtotime($res->approved_at)) : ''),  'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(37.5, 5, '',  'R', 'C', 0, 0, '', '', true);

        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 5, 'Date',  '0', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, 'Date',  '0', 'C', 0, 0, '', '', true);

        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 5, '',  '0', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, '',  '0', 'C', 0, 0, '', '', true);

       
        /*if ($res->approver) {
            if (file_exists('uploads/e-signature/'.$res->approver->hr_employee->identification_no.'_'.urlencode($res->approver->hr_employee->fullname).'.png')) {
                PDF::Image(url('./uploads/e-signature/'.$res->approver->hr_employee->identification_no.'_'.urlencode($res->approver->hr_employee->fullname).'.png'), 132, 247, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
            }
        }
        if ($res->requisition) {
            if (file_exists('uploads/e-signature/'.$res->requisition->employee->identification_no.'_'.urlencode($res->requisition->employee->fullname).'.png')) {
                PDF::Image(url('./uploads/e-signature/'.$res->requisition->employee->identification_no.'_'.urlencode($res->requisition->employee->fullname).'.png'), 34, 247, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
            }
        }*/
        $filename ='purchase_request_'.$prNum.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('gso_purchase_request_requested_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('gso_purchase_request_approved_by');
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

        $varifiedSignature = $this->_commonmodel->getuserSignature($res->requisition->employee->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature($res->approver->hr_employee->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
        if($isSignCertified==1 && $signType==2){
            $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
            $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
            $arrData['isDisplayPdf'] = 1;
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }


        // Apply E-sign Here
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

    }

    public function approve(Request $request, $requisitionID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'prepared',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );

            $details2 = array(
                'purchase_request_no' => $this->gsoPurchaseRequestRepository->fetchPurchaseRequestNo(),
                'sent_at' => $timestamp,
                'sent_by' => Auth::user()->id,
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );

            $lineDetails = array(
                'status' => 'prepared',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );

            return response()->json([
                'data' => $this->gsoPurchaseRequestRepository->updateRequest($requisitionID, $details),
                'datas' => $this->gsoPurchaseRequestRepository->update($requisitionID, $details2),
                'lines' => $this->is_permitted($this->slugs, 'approve', 1) ? $this->gsoDepartmentalRequisitionRepository->updateLines($requisitionID, $lineDetails) : '',
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function validate_pr(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'create');
        return response()->json([
            'status' => $this->gsoPurchaseRequestRepository->validate_pr($allotmentID),
            'type' => 'success'
        ]);
    }

    public function find_pr_line(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'create');
        return response()->json([
            'data' => $this->gsoPurchaseRequestRepository->find_pr_line($id)
            ->map(function($prLine) { 
                return (object) array(
                    'item_description' => $prLine->item_description,
                    'uom_id' => $prLine->uom_id,
                    'remarks' => $prLine->remarks,
                    'quantity' => $prLine->quantity_pr,
                    'unit_cost' => $prLine->request_unit_price,
                    'total_cost' => $prLine->request_total_price,
                    'status' => $prLine->status
                );
            }),
            'type' => 'success'
        ]);
    }

    public function fetch_pr_line_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'create');
        return response()->json([
            'status' => $this->gsoPurchaseRequestRepository->find_pr_line($id)->first()->status
        ]);
    }

    public function add_pr_line(Request $request, $allotmentID): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');        
        $purchase = $this->gsoPurchaseRequestRepository->find_pr_via_alob($allotmentID)->first();
        $details = array(
            'purchase_request_id' => $purchase->id,
            'item_description' => $request->item_description,
            'uom_id' => $request->uom_id,
            'remarks' => urldecode($request->get('remarks')),
            'quantity_pr' => $request->quantity,
            'request_unit_price' => $request->unit_cost,
            'request_total_price' => floatval($request->quantity) * floatval($request->unit_cost),
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoPurchaseRequestRepository->create_pr_line($details),
                'title' => 'Well done!',
                'text' => 'The item has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function modify_pr_line(Request $request, $id): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'update');
        $details = array(
            'item_description' => $request->item_description,
            'uom_id' => $request->uom_id,
            'remarks' => urldecode($request->get('remarks')),
            'quantity_pr' => $request->quantity,
            'request_unit_price' => $request->unit_cost,
            'request_total_price' => floatval($request->quantity) * floatval($request->unit_cost),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoPurchaseRequestRepository->modify_pr_line($id, $details),
                'title' => 'Well done!',
                'text' => 'The item has been successfully updated.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function remove_pr_line(Request $request, $id): JsonResponse 
    {
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'is_active' => 0,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->gsoPurchaseRequestRepository->modify_pr_line($id, $details),
                'title' => 'Well done!',
                'text' => 'The item has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function fetch_pr_amount(Request $request, $allotmentID)
    {
        $this->is_permitted($this->slugs, 'create');
        $alobs = $this->cboBudgetAllocationRepository->find_alob($allotmentID);
        if ($alobs->departmental_request_id > 0) {
            return response()->json([
                'departmental' => 1,
                'item_amount' => $this->gsoDepartmentalRequisitionRepository->find($alobs->departmental_request_id)->total_amount,
                'budget_amount' => $alobs->total_amount
            ]);
        } else {
            return response()->json([
                'departmental' => 0,
                'item_amount' => $this->gsoPurchaseRequestRepository->fetch_amount($allotmentID),
                'budget_amount' => $alobs->total_amount
            ]);
        }
    }
}
