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
use App\Interfaces\BacRequestForQuotationInterface;
use App\Interfaces\BacAbstractOfCanvassInterface;
use App\Interfaces\BacResolutionInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsAbstractOfCanvassController extends Controller
{   
    private BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private BacResolutionInterface $bacResolutionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        BacResolutionInterface $bacResolutionRepository, 
        BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository, 
        BacRequestForQuotationInterface $bacRequestForQuotationRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->bacAbstractOfCanvassRepository = $bacAbstractOfCanvassRepository;
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->bacResolutionRepository = $bacResolutionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/request-for-quotation';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $warranty = $this->bacRequestForQuotationRepository->allExpendableWarranties();
        $non_warranty = $this->bacRequestForQuotationRepository->allNonExpendableWarranties();
        $price_validity = $this->bacRequestForQuotationRepository->allPriceValidities();
        return view('for-approvals.bac.abstract-of-canvass.index')->with(compact('warranty', 'non_warranty', 'price_validity'));
    }

    public function lists(Request $request)
    {           
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="read this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->bacAbstractOfCanvassRepository->approval_listItems($request);
        $res = $result->data->map(function($canvass) use ($actions, $actions2, $statusClass) {
            $project_name = ($canvass->rfq->project_name !== NULL) ? wordwrap($canvass->rfq->project_name, 25, "\n") : '';
            $agencies = wordwrap($this->get_agencies($canvass->rfq->id), 25, "\n");
            if ($canvass->identityDispprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($canvass->identityDispprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($canvass->identityDisapprovedAt));
            } else if($canvass->identityApprovedBy !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($canvass->identityApprovedBy).'</strong><br/>'.date('d-M-Y H:i a', strtotime($canvass->identityApprovedAt));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $canvass->identity,
                'rfq' => $canvass->rfq->id,
                'control_no' => $canvass->rfq->control_no,
                'control_no_label' => '<strong class="text-primary">'.$canvass->rfq->control_no.'</strong>',
                'project_name' => '<div class="showLess">' . $project_name . '</div>',
                'agencies' => '<div class="showLess">' . $agencies . '</div>',
                'modified' => ($canvass->identityUpdated !== NULL) ? date('d-M-Y', strtotime($canvass->identityUpdated)).'<br/>'. date('h:i A', strtotime($canvass->identityUpdated)) : date('d-M-Y', strtotime($canvass->identityCreated)).'<br/>'. date('h:i A', strtotime($canvass->identityCreated)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$canvass->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$canvass->identityStatus]->bg. ' p-2">' . $statusClass[$canvass->identityStatus]->status . '</span>',
                'actions' => ($canvass->identityStatus == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function committee_lists(Request $request, $id)
    {   
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn btn-danger btn m-1 btn-sm align-items-center" title="Print"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->bacAbstractOfCanvassRepository->committee_listItems($request, $id);
        $res = $result->data->map(function($committee) use ($actions) {
            $fullname = ($committee->identityName !== NULL) ? wordwrap($committee->identityName, 25, "\n") : '';
            $department = ($committee->departments !== NULL) ? wordwrap($committee->departments, 25, "\n") : '';
            $division = ($committee->divisions !== NULL) ? wordwrap($committee->divisions, 25, "\n") : '';
            $designation = ($committee->designations !== NULL) ? wordwrap($committee->designations, 25, "\n") : '';
            return [
                'id' => $committee->identity,
                'code' => $committee->identityName,
                'name' => '<div class="showLess">' . $fullname . '</div>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'division' => '<div class="showLess">' . $division . '</div>',
                'designation' => '<div class="showLess">' . $designation . '</div>',
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
    
    public function get_agencies($rfqID)
    {
        return $this->bacRequestForQuotationRepository->get_agencies($rfqID);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->bacAbstractOfCanvassRepository->find_abstract($id)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->bacAbstractOfCanvassRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->bacAbstractOfCanvassRepository->find_abstract($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $rfqID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'estimated',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->update_request($rfqID, $details);
            $details2 = array(
                'status' => 'completed',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->update($rfqID, $details2);
            $details3 = array(
                'rfq_id' => $rfqID,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $this->bacResolutionRepository->create($details3);
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $rfqID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->bacAbstractOfCanvassRepository->update($rfqID, $details);
            $details2 = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->update_request($rfqID, $details2);
            // $this->bacAbstractOfCanvassRepository->updateRequest($rfqID, $details2);
            $lineDetails = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->updateLines($rfqID, $lineDetails);
            $details3 = array(
                'disapproved_from' => 'Abstract Of Canvass',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->bacAbstractOfCanvassRepository->disapprove_request($rfqID, $details3);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_remarks(Request $request, $rfq_ID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->bacAbstractOfCanvassRepository->find($rfq_ID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->find($id),
            'suppliers' => $this->bacResolutionRepository->fetch_suppliers($id)->map(function($sup) use ($id) {
                return (object) [
                    'supplier_id' => $sup->supplier->id,
                    'supplier' => $sup->supplier->business_name . ' ['. $sup->supplier->branch_name . ']',
                    'canvass' => $sup->total_canvass,
                    'is_selected' => $sup->is_selected,
                    'items' => $this->bacResolutionRepository->fetch_canvass($id, $sup->supplier->id)->map(function($item) {
                        return (object) [
                            'name' => $item->item->code . ' - ' . $item->item->name,
                            'quantity' => $item->identityQuantity,
                            'uom' => $item->item->uom->code,
                            'model' => $item->identityModel,
                            'unit_cost' => $item->identityUnitCost,
                            'total_cost' => $item->identityTotalCost
                        ];
                    })
                ];
            }),
            'abstract' => $this->bacAbstractOfCanvassRepository->find_abstract($id),
        ]);
    }

    public function reload_items(Request $request, $purchase_type) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoDepartmentalRequisitionRepository->reload_items($purchase_type)
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

    public function pr_lists(Request $request, $id)
    {   
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->bacRequestForQuotationRepository->pr_listItems($request, $id);
        $res = $result->data->map(function($rfqLine) use ($actions) {
            $department = wordwrap($rfqLine->purchase_request->requisition->department->code . ' - ' . $rfqLine->purchase_request->requisition->department->name . ' [' . $rfqLine->purchase_request->requisition->division->code . ']', 25, "\n");
            return [
                'id' => $rfqLine->identity,
                'pr_no' => '<strong class="text-primary">'.$rfqLine->purchase_request->purchase_request_no.'</strong>',
                'department' => '<div class="showLess">' . $department . '</div>',
                'rfq_no' => $rfqLine->rfq_no,
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

    public function supplier_lists(Request $request, $id)
    {   
        $statusClass = [
            'draft' => 'draft-bg',
            'pending' => 'for-approval-bg',
            'for approval' => 'for-approval-bg',
            'completed' => 'completed-bg'
        ]; 
        $actions = '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
        $result = $this->bacRequestForQuotationRepository->supplier_listItems($request, $id);
        $res = $result->data->map(function($rfqLineSupplier) use ($actions, $statusClass) {
            $supplier = wordwrap($rfqLineSupplier->supplier->business_name . ' - [' . $rfqLineSupplier->supplier->branch_name . ']', 25, "\n");
            return [
                'id' => $rfqLineSupplier->identity,
                'supplier_id' => $rfqLineSupplier->supplier->id,
                'supplier' => '<div class="showLess">' . $supplier . '</div>',
                'branch' => $rfqLineSupplier->supplier->branch_name,
                'contact_no' => $rfqLineSupplier->mobile_no,
                'total_canvass' => $this->money_format($rfqLineSupplier->total_canvass),
                'status' => $rfqLineSupplier->identityStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$rfqLineSupplier->identityStatus]. ' p-2">' .  $rfqLineSupplier->identityStatus . '</span>',
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
        $result = $this->bacRequestForQuotationRepository->item_listItems($request, $id);
        $res = $result->data->map(function($rfqItem) {
            if (strlen($rfqItem->pr_remarks) > 0) { 
                $description = wordwrap($rfqItem->item->code .' - ' . $rfqItem->item->name . ' (' . $rfqItem->pr_remarks . ')', 25, "\n");
            } else if (strlen($rfqItem->itemRemarks) > 0) {
                $description = wordwrap($rfqItem->item->code .' - ' . $rfqItem->item->name . ' (' . $rfqItem->itemRemarks . ')', 25, "\n");
            } else { 
                $description = wordwrap($rfqItem->item->code .' - ' . $rfqItem->item->name, 25, "\n"); 
            } 
            return [
                'id' => $rfqItem->itemId,
                'code' => $rfqItem->itemCode,
                'description' => '<div class="showLess">' . $description . '</div>',
                'quantity' => $rfqItem->itemQuantity,
                'uom' => $rfqItem->uom->code
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function edit_supplier(Request $request, $rfqID)
    {   
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->fetch_items($rfqID)
            ->map(function($item) use ($request, $rfqID) {
                if (strlen($item->pr_remarks) > 0) { 
                    $description = wordwrap($item->item->code .' - ' . $item->item->name . ' (' . $item->pr_remarks . ')', 25, "\n");
                } else if (strlen($item->itemRemarks) > 0) {
                    $description = wordwrap($item->item->code .' - ' . $item->item->name . ' (' . $item->itemRemarks . ')', 25, "\n");
                } else { 
                    $description = wordwrap($item->item->code .' - ' . $item->item->name, 25, "\n"); 
                } 
                $res = $this->bacRequestForQuotationRepository->find_canvass($rfqID, $request->get('supplier'), $item->itemId);
                return [
                    'item_id' => $item->itemId,
                    'item_code' => $item->itemCode,
                    'item_description' => '<div class="showLess">' . $description . '</div>',
                    'item_quantity' => $item->itemQuantity,
                    'item_uom' => $item->uom->code,
                    'brand' => ($res->count() > 0) ? ($res->first()->description) ? $res->first()->description : '' : '',
                    'unit_cost' => ($res->count() > 0) ? ($res->first()->unit_cost) ? $res->first()->unit_cost : '' : '',
                    'total_cost' => ($res->count() > 0) ? ($res->first()->total_cost) ? $res->first()->total_cost : '' : '',
                    'remarks' => ($res->count() > 0) ? ($res->first()->remarks) ? $res->first()->remarks : '' : '',
                ];
            }),
            'canvass' => $this->bacRequestForQuotationRepository->find_supplier($rfqID, $request->get('supplier')),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
