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
use File;

class GsoPurchaseOrderController extends Controller
{   
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        BacRequestForQuotationInterface $bacRequestForQuotationRepository, 
        GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, 
        Carbon $carbon
    ) {
        $this->_commonmodel = new CommonModelmaster();
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/purchase-orders';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $this->gsoPurchaseOrderRepository->all_available_rfq(3);
        $rfqs = ['' => 'select a control no'];
        $supplier = ['' => 'select a supplier'];
        $po_types = $this->gsoPurchaseOrderRepository->allPoTypes();
        $modes = $this->gsoPurchaseOrderRepository->allProcurementModes();
        $payment_terms = $this->gsoPurchaseOrderRepository->allPaymentTerms();
        $delivery_terms = $this->gsoPurchaseOrderRepository->allDeliveryTerms();
        $funding_by = $this->gsoPurchaseOrderRepository->allEmployees();
        $approval_by = $this->gsoPurchaseOrderRepository->allEmployees();
        $localAddress = $this->gsoPurchaseOrderRepository->getLocalAddress();
        return view('general-services.purchase-order.index')->with(compact('localAddress', 'funding_by', 'approval_by', 'rfqs', 'supplier', 'po_types', 'modes', 'delivery_terms', 'payment_terms'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => 'draft-bg',
            'pending' => 'for-approval-bg',
            'for approval' => 'for-approval-bg',
            'completed' => 'completed-bg',
            'cancelled' => 'cancelled-bg',
        ]; 
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'download', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="download this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="download this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->gsoPurchaseOrderRepository->listItems($request);
        $res = $result->data->map(function($purchase) use ($actions, $actions2, $statusClass) {
            $project_name =  $purchase->rfq ? ($purchase->rfq->project_name !== NULL) ? wordwrap($purchase->rfq->project_name, 25, "\n") : '' : '';
            return [
                'id' => $purchase->identity,
                'rfq' =>  $purchase->rfq ? $purchase->rfq->id : 0,
                'rfq_label' =>  $purchase->rfq ? $purchase->rfq->control_no : '',
                'po_no' => $purchase->purchase_order_no,
                'po_label' => '<strong class="text-primary">'.$purchase->purchase_order_no.'</strong>',
                'supplier' => $purchase->supplier ? '<div class="showLess">' . $purchase->supplier->business_name . '</div>' : '',
                'project_name' => '<div class="showLess">' . $project_name . '</div>',
                'po_type' => $purchase->po_type ? $purchase->po_type->name : '',
                'total_amount' => $this->money_format($purchase->identityTotal),
                'modified' => ($purchase->identityUpdated !== NULL) ? 
                '<strong>'.$purchase->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->identityUpdated)) : 
                '<strong>'.$purchase->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($purchase->identityCreated)),
                'status' => $purchase->identityStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$purchase->identityStatus]. ' p-2">' .  $purchase->identityStatus . '</span>',
                'actions' => ($purchase->identityStatus == 'draft') ? $actions : $actions2
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
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

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPurchaseOrderRepository->find($id)->status
        ]);
    }

    public function update(Request $request, $poID)
    {
        $supplierID = $this->gsoPurchaseOrderRepository->getSupplier($request->rfq_id);
        $updateQty  =$this->gsoPurchaseOrderRepository->updatePoQuantity($request->rfq_id);
        if ($poID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            // $this->gsoPurchaseOrderRepository->generate_po_no()
            $details = array(
                'purchase_order_type_id' => $request->purchase_order_type_id,
                'rfq_id' => $request->rfq_id,
                'supplier_id' => $supplierID,
                'payment_term_id' => $request->payment_term_id,
                'delivery_term_id' => $request->delivery_term_id,
                'procurement_mode_id' => $request->procurement_mode_id,
                'purchase_order_no' => NULL,
                'purchase_order_date' => $request->purchase_order_date ? date('Y-m-d', strtotime($request->purchase_order_date)) : NULL,
                'committed_date' => $request->committed_date ? date('Y-m-d', strtotime($request->committed_date)) : NULL,
                'delivery_place' => $request->delivery_place,
                'remarks' => $request->remarks,
                'funding_by' => $request->funding_by,
                'funding_designation' => $request->funding_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->funding_by) : NULL,
                'approval_by' => $request->approval_by,
                'approval_designation' => $request->approval_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->approval_by) : NULL,
                'total_amount' => $this->computeTotalAmount($request->rfq_id, $supplierID),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $po = $this->gsoPurchaseOrderRepository->create($details);
            $poID = $po->id;
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'purchase_order_type_id' => $request->purchase_order_type_id,
                'rfq_id' => $request->rfq_id,
                'supplier_id' => $supplierID,
                'payment_term_id' => $request->payment_term_id,
                'delivery_term_id' => $request->delivery_term_id,
                'procurement_mode_id' => $request->procurement_mode_id,
                'purchase_order_date' => $request->purchase_order_date ? date('Y-m-d', strtotime($request->purchase_order_date)) : NULL,
                'committed_date' => $request->committed_date ? date('Y-m-d', strtotime($request->committed_date)) : NULL,
                'delivery_place' => $request->delivery_place,
                'remarks' => $request->remarks,
                'funding_by' => $request->funding_by,
                'funding_designation' => $request->funding_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->funding_by) : NULL,
                'approval_by' => $request->approval_by,
                'approval_designation' => $request->approval_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->approval_by) : NULL,
                'total_amount' => $this->computeTotalAmount($request->rfq_id, $supplierID),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoPurchaseOrderRepository->update($poID, $details);
        }
        return response()->json([
            'data' => $this->gsoPurchaseOrderRepository->find($poID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function computeTotalBudget($rfqID)
    {
        return $this->bacRequestForQuotationRepository->computeTotalBudget($rfqID);
    }

    public function computeTotalAmount($rfqID, $supplierID)
    {   
        if ($rfqID > 0 && $supplierID > 0) {
            return $this->bacRequestForQuotationRepository->computeTotalAmount($rfqID, $supplierID);
        } 
        return floatval(0);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
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

    public function send(Request $request, $status, $poID)
    {   
        if ($status == 'for-po-approval') {
            $timestamp = $this->carbon::now();
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
            return response()->json([
                'data' => $this->gsoPurchaseOrderRepository->update($poID, $details2),
                'data2' => $this->gsoPurchaseOrderRepository->update_request($poID, $details),
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

    public function dateDiff($date1, $date2)
    {
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        return $days.' days';
    }


    public function print(Request  $request, $poNum)
    {
        $this->is_permitted($this->slugs, 'download');
        $res = $this->gsoPurchaseOrderRepository->find_via_column('purchase_order_no', $poNum);

        if (!($res->count() > 0)) {
            return abort(404);
        }
        $alobNo  = $this->gsoPurchaseOrderRepository->getAlobs($poNum);
        $alobAmt = $this->gsoPurchaseOrderRepository->getAlobsAmount($poNum);
        $prNo    = $this->gsoPurchaseOrderRepository->getPrNos($poNum);
        $res = $res->first();
        PDF::SetTitle('Purchase Order ('.$poNum.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(195.85, 5, 'LGU Form No. 6', 0, 'L', 0, 0, '', '', true);
        PDF::ln(4);
        PDF::MultiCell(195.85, 5, '(Revised 2002)', 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(170.85, 5, 'Control No.:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(22, 5, $res->rfq->control_no, 'B', 'C', 0, 0, '', '', true);
        PDF::ln(4.5);
        PDF::SetFont('Helvetica', 'B', 20);
        PDF::MultiCell(195.85, 5, 'PURCHASE ORDER', 0, 'C', 0, 0, '', '', true);
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
        PDF::MultiCell(18, 5, 'Supplier: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, $res->supplier->business_name, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'P.O. No: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, $res->purchase_order_no, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(18, 5, 'Address: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, $res->supplier->address, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(12, 5, 'Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(61, 5, date('d-M-Y', strtotime($res->purchase_order_date)), 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(18, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(41, 5, 'Mode of Procurement: ', 0, 'L', 0, 0, '', '', true);
        $procurement = strlen($res->procurement->description) > 13 ? substr($res->procurement->description,0,13)."..." : $res->procurement->description;
        PDF::MultiCell(32, 5, $procurement, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(18, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'P.R. No.: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, $prNo, 0, 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 47); 
        PDF::MultiCell(120, 20, '', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(75.85, 20, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(24, 10, 'Gentlemen: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(171.85, 10, chr(10).'Please furnish this office the following subject to the terms and conditions contained herein:', 0, 'L', 0, 1, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 67); 
        PDF::MultiCell(195.85, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(195.85, 1, '', 'LR', 'L', 0, 0, '', '', true);
        PDF::ln(4); 
        PDF::MultiCell(34, 5, 'Place of Delivery: ', 0, 'L', 0, 0, '', '', true);
        $deliveryPlace = strlen($res->delivery_place) > 38 ? substr($res->delivery_place,0,38)."..." : $res->delivery_place;
        PDF::MultiCell(73, 5, $deliveryPlace, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(28, 5, 'Delivery Term: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(54, 5,  $res->delivery_term->name, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(34, 5, 'Date of Delivery :', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(73, 5, $this->dateDiff($res->purchase_order_date, $res->committed_date), 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, 'Payment Term: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(52, 5, $res->payment_term->name, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 79); 
        PDF::MultiCell(109, 14, '', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(3, 14, '', 'LR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(83.85, 14, '', 1, 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 90); 
        PDF::MultiCell(195.85, 1, '', 'LBR', 'L', 0, 0, '', '', true);

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
        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(18, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(78, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::ln(1.5); 
        $itemList = $this->gsoPurchaseOrderRepository->item_list_via_po_num($poNum);
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
            $unitCost = $this->gsoPurchaseOrderRepository->getItemCost($item->rfq, $item->itemId);
            $totalCost = floatval($item->itemQuantity) * floatval($unitCost);
            $totalAmt += floatval($totalCost);
            PDF::MultiCell(18, 5, $iteration, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(18, 5, $item->uom->code, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(20, 5, $item->itemQuantity, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(78, 5, $description, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, number_format(floor(($unitCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, number_format(floor(($totalCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);
            PDF::ln(8.5); 
        }
        PDF::ln(); 
        PDF::SetFont('Helvetica', '', 11);
        PDF::setCellHeightRatio(1.25);
        PDF::SetXY(10, 100); 
        PDF::MultiCell(18, 140, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(18, 140, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 140, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(78, 140, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 140, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 140, '', 'LR', 'C', 0, 0, '', '', true);

        $lineStyle = array('width' => 0.35, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        PDF::ln(); 
        PDF::MultiCell(164.925, 5, '(Total Amount in Words) '.trim(ucfirst(strtolower($this->gsoPurchaseOrderRepository->numberTowords($totalAmt)))),  'TL', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(30.925, 5, 'P'.number_format(floor(($totalAmt*100))/100, 2), 'TR', 'R', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::ln(5); 
        PDF::SetFont('Helvetica', '', 11.5);
        PDF::MultiCell(195.85, 1, '     In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent for every day of delay shall be imposed.', 0, 'L', 0, 0, '', '', true);

        // if ($res->approve_by) {
        //     if (file_exists('uploads/e-signature/'.$res->approve_by->identification_no.'_'.urlencode($res->approve_by->fullname).'.png')) {
        //         PDF::Image(url('./uploads/e-signature/'.$res->approve_by->identification_no.'_'.urlencode($res->approve_by->fullname).'.png'), 132, 268, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
        //     }
        // }

        PDF::ln(); 
        PDF::ln(5); 
        PDF::SetFont('Helvetica', 'B', 11.5);
        PDF::MultiCell(25, 1, ' Conforme:', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(105, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(35, 1, 'Very truly yours,', 0, 'C', 0, 0, '', '', true);
        
        PDF::ln(); 
        PDF::ln(10); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, strtoupper($res->approve_by->fullname), 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, $res->supplier->business_name, 'T', 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, ucwords($res->approve_designation->description), '0', 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::ln(10); 
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, '(Date)', 'T', 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 245); 
        PDF::MultiCell(195.85, 60, '', 1, 'C', 0, 0, '', '', true);
        PDF::ln(); 

        // if ($res->fund_by) {
        //     if (file_exists('uploads/e-signature/'.$res->fund_by->identification_no.'_'.urlencode($res->fund_by->fullname).'.png')) {
        //         PDF::Image(url('./uploads/e-signature/'.$res->fund_by->identification_no.'_'.urlencode($res->fund_by->fullname).'.png'), 34, 308, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
        //     }
        // }

        PDF::ln(3); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::MultiCell(50, 1, '  Funds Available: ', 0, 'L', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 1, 'ALOBS No.:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 1, $alobNo, 'B', 'C', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::ln(2); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::SetXY(10, 319.36095766667); 
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, strtoupper($res->fund_by->fullname), '0', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, ucwords($res->fund_designation->description), '0', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 1, 'Amount:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 1, 'P'.number_format(floor(($alobAmt*100))/100, 2), 'B', 'C', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::ln(5); 
        PDF::SetFont('Helvetica', 'B', 10.5);
        PDF::SetXY(10, 338.55320666667); 
        PDF::MultiCell(10, 1, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 1, "(Date)", 'T', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(10, 300); 
        PDF::MultiCell(195.85, 45, '', 'LBR', 'C', 0, 0, '', '', true);

        $y = PDF::GetY();
        PDF::Line(117.8, $y - 15.25, 196, $y - 15.25, $lineStyle);
        PDF::Line(19.8, $y + 24, 98, $y + 24, $lineStyle);

        // PDF::Output('purchase_order_'.$poNum.'.pdf');
        $filename = 'purchase_order_'.$poNum.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('gso_purchase_order_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('gso_purchase_order_funding_by');
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
                if($isSignCertified==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
        $apporveId=user_mayor()->user_id;
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
    }
    public function get_local_address()
    {
        return $this->gsoPurchaseOrderRepository->getLocalAddress();
    }
}
