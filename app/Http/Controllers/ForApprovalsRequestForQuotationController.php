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
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsRequestForQuotationController extends Controller
{   
    private BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository, 
        BacRequestForQuotationInterface $bacRequestForQuotationRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->bacAbstractOfCanvassRepository = $bacAbstractOfCanvassRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/request-for-quotation';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $warranty = $this->bacRequestForQuotationRepository->allExpendableWarranties();
        $non_warranty = $this->bacRequestForQuotationRepository->allNonExpendableWarranties();
        $price_validity = $this->bacRequestForQuotationRepository->allPriceValidities();
        return view('for-approvals.bac.request-for-quotation.index')->with(compact('warranty', 'non_warranty', 'price_validity'));
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
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->bacRequestForQuotationRepository->approval_listItems($request);
        $res = $result->data->map(function($rfq) use ($actions, $actions2, $statusClass) {
            $project_name = ($rfq->project_name !== NULL) ? wordwrap($rfq->project_name, 25, "\n") : '';
            $agencies = wordwrap($this->get_agencies($rfq->id), 25, "\n");
            $remarks = ($rfq->remarks !== NULL) ? wordwrap($rfq->remarks, 25, "\n") : '';
            if ($rfq->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($rfq->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($rfq->disapproved_at));
            } else if($rfq->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($rfq->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($rfq->approved_at));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $rfq->id,
                'control_no' => $rfq->control_no,
                'control_no_label' => '<strong class="text-primary">'.$rfq->control_no.'</strong>',
                'project_name' => '<div class="showLess">' . $project_name . '</div>',
                'agencies' => '<div class="showLess">' . $agencies . '</div>',
                'remarks' => '<div class="showLess">' . $remarks . '</div>',
                'modified' => ($rfq->updated_at !== NULL) ? date('d-M-Y', strtotime($rfq->updated_at)).'<br/>'. date('h:i A', strtotime($rfq->updated_at)) : date('d-M-Y', strtotime($rfq->created_at)).'<br/>'. date('h:i A', strtotime($rfq->created_at)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$rfq->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$rfq->status]->bg. ' p-2">' . $statusClass[$rfq->status]->status . '</span>',
                'actions' => ($rfq->status == 'cancelled') ? $actions2 : $actions
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
            'status' => $this->bacRequestForQuotationRepository->find($id)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->bacRequestForQuotationRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->bacRequestForQuotationRepository->find($id)->approved_by);
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
                'status' => 'quoted',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacRequestForQuotationRepository->update_request($rfqID, $details);
            $details2 = array(
                'status' => 'completed',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            $this->bacRequestForQuotationRepository->update($rfqID, $details2);
            $details3 = array(
                'rfq_id' => $rfqID,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->create($details3);
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
            $this->bacRequestForQuotationRepository->update($rfqID, $details);
            $details2 = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacRequestForQuotationRepository->update_request($rfqID, $details2);
            // $this->bacRequestForQuotationRepository->updateRequest($rfqID, $details2);
            $lineDetails = array(
                'status' => 'cancelled',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->bacRequestForQuotationRepository->updateLines($rfqID, $lineDetails);
            $details3 = array(
                'disapproved_from' => 'Request For Quotation',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->bacRequestForQuotationRepository->disapprove_request($rfqID, $details3);
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
            'remarks' => $this->bacRequestForQuotationRepository->find($rfq_ID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->find($id),
            'abstract' => $this->bacAbstractOfCanvassRepository->find_abstract($id),
            'agencies' => $this->get_agencies($id)
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
