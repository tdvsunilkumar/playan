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
use App\Interfaces\GsoPurchaseOrderInterface;
use App\Interfaces\BacRequestForQuotationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPurchaseOrderController extends Controller
{   
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private $carbon;
    private $slugs;

    public function __construct(BacRequestForQuotationInterface $bacRequestForQuotationRepository, GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/purchase-order';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $rfqs = ['' => 'select a control no'];
        $supplier = ['' => 'select a supplier'];
        $po_types = $this->gsoPurchaseOrderRepository->allPoTypes();
        $modes = $this->gsoPurchaseOrderRepository->allProcurementModes();
        $payment_terms = $this->gsoPurchaseOrderRepository->allPaymentTerms();
        $delivery_terms = $this->gsoPurchaseOrderRepository->allDeliveryTerms();
        return view('for-approvals.purchase-order.index')->with(compact('rfqs', 'supplier', 'po_types', 'modes', 'delivery_terms', 'payment_terms'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }

        $result = $this->gsoPurchaseOrderRepository->approval_listItems($request, Auth::user()->id);
        $res = $result->data->map(function($purchase) use ($actions, $statusClass) {
            $project_name = ($purchase->rfq->project_name !== NULL) ? wordwrap($purchase->rfq->project_name, 25, "\n") : '';
            $supplier = $purchase->supplier ? wordwrap($purchase->supplier->business_name, 25, "\n") : '';
            return [
                'id' => $purchase->identity,
                'rfq' => $purchase->rfq->id,
                'po_no' => $purchase->purchase_order_no,
                'supplier' => '<div class="showLess">' . $supplier . '</div>',
                'project_name' => '<div class="showLess">' . $project_name . '</div>',
                'po_type' => $purchase->po_type ? $purchase->po_type->name : '',
                'total_amount' => $this->money_format($purchase->identityTotal),
                'approved_by' => $this->fetchApprovedBy($purchase->identityApprovedBy),
                'modified' => ($purchase->identityUpdated !== NULL) ? date('d-M-Y', strtotime($purchase->identityUpdated)).'<br/>'. date('h:i A', strtotime($purchase->identityUpdated)) : date('d-M-Y', strtotime($purchase->identityCreated)).'<br/>'. date('h:i A', strtotime($purchase->identityCreated)),
                'status' => $statusClass[$purchase->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$purchase->identityStatus]->bg. ' p-2">' . $statusClass[$purchase->identityStatus]->status . '</span>' ,
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

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPurchaseOrderRepository->find($id)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoPurchaseOrderRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        // $approvers = explode(',',$this->gsoPurchaseOrderRepository->find($id)->approved_by);
        // if (in_array(Auth::user()->id, $approvers)) {
        //     return true;
        // }
        // return false;
        return false;
    }

    public function approve(Request $request, $id)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->gsoPurchaseOrderRepository->find($id)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->gsoPurchaseOrderRepository->find($id)->approved_by);
            }
            $approvers[] = Auth::user()->id;
            $details = array(
                'purchase_order_no' => (count($approvers) > 1) ? $this->gsoPurchaseOrderRepository->generate_po_no() : NULL,
                'status' => (count($approvers) > 1) ? 'completed' : 'for approval',
                'approved_at' => $this->carbon::now(),
                'approved_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers)
            );
            $this->gsoPurchaseOrderRepository->update($id, $details);
            if (count($approvers) > 1) {
                $details2 = array(
                    'status' => 'purchased',
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->gsoPurchaseOrderRepository->update_request($id, $details2);
                $this->gsoPurchaseOrderRepository->update_srp($id);
            }
            return response()->json([
                'approves' => count($approvers),
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $id)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $this->carbon::now(),
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoPurchaseOrderRepository->update($id, $details);
            $details2 = array(
                'status' => 'cancelled',
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoPurchaseOrderRepository->update_request($id, $details2);
            $details3 = array(
                'disapproved_from' => 'Purchase Order',
                'disapproved_at' => $this->carbon::now(),
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->gsoPurchaseOrderRepository->disapprove_request($id, $details3);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function pr_lists(Request $request, $id)
    {   
        $result = $this->gsoPurchaseOrderRepository->pr_listItems($request, $id);
        $res = $result->data->map(function($purchase) {
            return [
                'pr_no' => $purchase->pr_no,
                'agency' => $purchase->agency,
                'alob_no' => $purchase->alob_no
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
        $result = $this->gsoPurchaseOrderRepository->item_listItems($request, $id);
        $res = $result->data->map(function($poItem, $iteration = 0) {
            if (strlen($poItem->pr_remarks) > 0) { 
                $description = wordwrap($poItem->item->code .' - ' . $poItem->item->name . ' (' . $poItem->pr_remarks . ')', 25, "\n");
            } else if (strlen($poItem->itemRemarks) > 0) {
                $description = wordwrap($poItem->item->code .' - ' . $poItem->item->name . ' (' . $poItem->itemRemarks . ')', 25, "\n");
            } else { 
                $description = wordwrap($poItem->item->code .' - ' . $poItem->item->name, 25, "\n"); 
            } 
            $unitCost = $this->gsoPurchaseOrderRepository->getItemCost($poItem->rfq, $poItem->itemId);
            return [
                'no' => $iteration = $iteration + 1,
                'id' => $poItem->itemId,
                'code' => $poItem->itemCode,
                'description' => '<div class="showLess">' . $description . '</div>',
                'quantity' => $poItem->itemQuantity,
                'uom' => $poItem->uom->code,
                'unit_cost' => $this->money_format($unitCost),
                'total_cost' => $this->money_format(floatval(floatval($poItem->itemQuantity) * floatval($unitCost)))
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function reload_available_control_no(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoPurchaseOrderRepository->all_available_rfq($id)
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $po = $this->gsoPurchaseOrderRepository->find($id);
        return response()->json([
            'rfq' => $this->gsoPurchaseOrderRepository->all_available_rfq($id),
            'data' => (object) [
                'purchase_order_no' => $po->purchase_order_no,
                'rfq_id' => $po->rfq_id,
                'purchase_order_type_id' => $po->purchase_order_type_id,
                'procurement_mode_id' => $po->procurement_mode_id,
                'payment_term_id' => $po->payment_term_id,
                'delivery_term_id' => $po->delivery_term_id,
                'purchase_order_date' => $po->purchase_order_date,
                'committed_date' => $po->committed_date,
                'delivery_place' => $po->delivery_place,
                'remarks' => $po->remarks,
                'funding_by' => $po->funding_by,
                'approval_by' => $po->approval_by,
                'supplier' => $po->supplier ? ucwords($po->supplier->business_name). ' - ' . ucwords($po->supplier->branch_name) : '',
                'address' => $po->supplier ? $po->supplier->address : '',
                'project_name' => $po->rfq ? $po->rfq->project_name : '',
            ]
        ]);
    }
}
