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

class GsoInspectionAcceptanceController extends Controller
{   
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private $carbon;
    private $slugs;

    public function __construct(BacRequestForQuotationInterface $bacRequestForQuotationRepository, GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, Carbon $carbon) 
    {
        $this->_commonmodel = new CommonModelmaster();
        date_default_timezone_set('Asia/Manila');
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/inspection-and-acceptance';
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
        $users = $this->gsoPurchaseOrderRepository->allEmployees();
        return view('general-services.inspection-and-acceptance.index')->with(compact('users', 'rfqs', 'supplier', 'po_types', 'modes', 'delivery_terms', 'payment_terms'));
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
        $result = $this->gsoPurchaseOrderRepository->posting_listItems($request);
        $res = $result->data->map(function($purchase) use ($actions, $actions2, $statusClass) {
            $project_name = ($purchase->rfq->project_name !== NULL) ? wordwrap($purchase->rfq->project_name, 25, "\n") : '';
            return [
                'id' => $purchase->identity,
                'rfq' => $purchase->rfq->id,
                'po_no' => $purchase->purchase_order_no,
                'po_no_label' => '<strong class="text-primary">'.$purchase->purchase_order_no.'</strong>',
                'supplier' => $purchase->supplier ? $purchase->supplier->business_name : '',
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

    public function posting_lists(Request $request, $id)
    {   
        $result = $this->gsoPurchaseOrderRepository->posted_listItems($request, $id);
        $res = $result->data->map(function($posting) {
            $items = wordwrap($posting->posted_items, 25, "\n");
            return [
                'iar_no' => $posting->sequence_no,
                'sequence_no' => '<strong class="text-primary">'.$posting->sequence_no.'</strong>',
                'reference' => '<strong>'.$posting->reference_no.'</strong><br/>'.date('d-M-Y h:i A', strtotime($posting->reference_date)),
                'inspected' => '<strong>'.ucwords($posting->inspector->fullname).'</strong><br/>'.date('d-M-Y h:i A', strtotime($posting->inspected_date)),
                'received' => '<strong>'.ucwords($posting->receiver->fullname).'</strong><br/>'.date('d-M-Y h:i A', strtotime($posting->received_date)),          
                'remarks' => $posting->remarks,
                'posted_items' => '<div class="showLess">' . $items . '</div>',
                'actions' => '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="Print"><i class="ti-printer text-white"></i></a>'
                
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
                'posted' => $poItem->itemPosted,
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
            'status' => $this->gsoPurchaseOrderRepository->find($id)->posting_status
        ]);
    }

    public function update(Request $request, $poID)
    {
        $supplierID = $this->gsoPurchaseOrderRepository->getSupplier($request->rfq_id);
        $updateQty  =$this->gsoPurchaseOrderRepository->updatePoQuantity($request->rfq_id);
        if ($poID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'purchase_order_type_id' => $request->purchase_order_type_id,
                'rfq_id' => $request->rfq_id,
                'supplier_id' => $supplierID,
                'payment_term_id' => $request->payment_term_id,
                'delivery_term_id' => $request->delivery_term_id,
                'procurement_mode_id' => $request->procurement_mode_id,
                'purchase_order_no' => $this->gsoPurchaseOrderRepository->generate_po_no(),
                'purchase_order_date' => $request->purchase_order_date ? date('Y-m-d', strtotime($request->purchase_order_date)) : NULL,
                'committed_date' => $request->committed_date ? date('Y-m-d', strtotime($request->committed_date)) : NULL,
                'delivery_place' => $request->delivery_place,
                'remarks' => $request->remarks,
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

    public function money_format2($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'rfq' => $this->gsoPurchaseOrderRepository->all_available_rfq($id),
            'data' => $this->gsoPurchaseOrderRepository->find_po($id)->map(function($po) {
                return [
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
                    'supplier' => ucwords($po->supplier->business_name). ' - ' . ucwords($po->supplier->branch_name),
                    'address' => $po->supplier->address,
                    'project_name' => $po->rfq->project_name,
                ];
            })
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

    public function view_available_posting(Request $request, $poID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoPurchaseOrderRepository->view_available_posting($poID)
            ->map(function($poItem, $iteration = 0) {
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
                    'posted' => $poItem->itemPosted,
                    'quantity' => $poItem->itemQuantity,
                    'available' => floatval(floatval($poItem->itemQuantity) - floatval($poItem->itemPosted)),
                    'uom_id' => $poItem->uom->id,
                    'uom' => $poItem->uom->code
                ];
            }),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function posting(Request $request, $poID)
    {
        $this->is_permitted($this->slugs, 'read');
        $this->gsoPurchaseOrderRepository->create_posting($request, $poID, $this->carbon::now(), Auth::user()->id);        
        return response()->json([
            'data' => $request->posted,
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }


    public function print(Request $request, $poNum)
    {   
        $res = $this->gsoPurchaseOrderRepository->find_posting($poNum, $request->get('sequence'));
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $departments = $this->gsoPurchaseOrderRepository->getPoDepartments($poNum);
        $res = $res->first();

        PDF::SetTitle('Inspection & Acceptance ('.$poNum.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(195.85, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::ln(4);
        PDF::MultiCell(195.85, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::ln(4.5);
        PDF::SetFont('Helvetica', 'B', 20);
        PDF::MultiCell(195.85, 5, 'INSPECTION AND ACCEPTANCE', 0, 'C', 0, 0, '', '', true);
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
        PDF::MultiCell(98, 5, $res->purchased->supplier->business_name, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'IAR No.: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, $request->get('sequence'), 'B', 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::MultiCell(18, 5, 'PO No.: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(40, 5, $poNum, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(18, 5, 'PO Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(40, 5, date('d-M-Y', strtotime($res->purchased->purchase_order_date)), 'B', 'L', 0, 0, '', '', true);


        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(22, 5, 'IAR Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(51, 5, date('d-M-Y', strtotime($res->created_at)), 'B', 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::MultiCell(36, 5, 'Requesting Office: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(80, 5, $departments, 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(25, 5, 'Invoice No.: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(48, 5, $res->reference_no, 'B', 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::MultiCell(18, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(98, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(27, 5, 'Invoice Date: ', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(46, 5, date('d-M-Y', strtotime($res->reference_date)), 0, 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::SetXY(10, 47); 
        PDF::MultiCell(120, 20, '', 1, 'L', 0, 0, '', '', true);
        PDF::MultiCell(75.85, 20, '', 1, 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(18, 5, 'Item No.', 'LBR', 'C', 0, 0, '', '', true);
        // PDF::MultiCell(20, 5, 'Quantity', 'LBR', 'C', 0, 0, '', '', true);
        // PDF::MultiCell(18, 5, 'Unit', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(116, 5, 'Description', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, 'Quantity', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, 'Unit', 'LBR', 'C', 0, 0, '', '', true);


        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(18, 5, '', 0, 'C', 0, 0, '', '', true);
        // PDF::MultiCell(18, 5, '', 0, 'C', 0, 0, '', '', true);
        // PDF::MultiCell(20, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(116, 5, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::ln(1.5); 
        $itemList = $this->gsoPurchaseOrderRepository->posted_items_via_po_num($poNum, $request->get('sequence'));
        $iteration = 0; $totalAmt = 0;
        foreach ($itemList as $item) {
            $iteration++;
            $description = $item->item->code .' - ' . $item->item->name;
            $unitCost = $this->gsoPurchaseOrderRepository->getItemCost($item->posting->purchased->rfq_id, $item->item->id);
            $totalCost = floatval($item->itemQuantity) * floatval($unitCost);
            $totalAmt += floatval($totalCost);
            PDF::MultiCell(18, 5, $iteration, 0, 'C', 0, 0, '', '', true);
            // PDF::MultiCell(20, 5, $item->itemQuantity, 0, 'C', 0, 0, '', '', true);
            // PDF::MultiCell(18, 5, $item->uom->code, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(116, 5, $description, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, $item->itemQuantity, 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 5, $item->uom->code, 0, 'C', 0, 0, '', '', true);           
            PDF::ln(8.5);
        }
        
        PDF::ln(); 
        PDF::SetFont('Helvetica', '', 11);
        PDF::setCellHeightRatio(1.25);
        PDF::SetXY(10, 72); 
        PDF::MultiCell(18, 168, '', 'LR', 'C', 0, 0, '', '', true);
        // PDF::MultiCell(20, 168, '', 'LR', 'C', 0, 0, '', '', true);
        // PDF::MultiCell(18, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(116, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 168, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.925, 168, '', 'LR', 'C', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::MultiCell(164.925, 5, '(Total Amount in Words) '.trim(ucfirst(strtolower($this->gsoPurchaseOrderRepository->numberTowords($totalAmt)))),  'TL', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(30.925, 5, 'P'.number_format(floor(($totalAmt*100))/100, 2), 'TR', 'R', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::SetFont('Helvetica', '', 11);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(97.925, 4, 'INSPECTION',  'LTR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 4, 'ACCEPTANCE',  'TR', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(30.9625, 5, 'Date Inspected: ',  'L', 'L', 0, 0, '', '', true);
        PDF::MultiCell(36, 5, date('d-M-Y', strtotime($res->inspected_date)), 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.9625, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.9625, 5, 'Date Received: ',  'L', 'L', 0, 0, '', '', true);
        PDF::MultiCell(36, 5, date('d-M-Y', strtotime($res->received_date)), 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30.9625, 5, '', 'R', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(97.925, 25, '',  'LR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 25, '',  'R', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(15, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(67.925, 5, strtoupper($res->inspector->fullname),  'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(15, 5, '',  'R', 'C', 0, 0, '', '', true);
        PDF::MultiCell(15, 5, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(67.925, 5, strtoupper($res->receiver->fullname),  'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(15, 5, '',  'R', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetFont('Helvetica', '', 11);
        PDF::MultiCell(20, 5, '',  'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(57.925, 5, 'Inspection Officer',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, '',  'R', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(57.925, 5, 'Acceptance / Buyer V',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, '',  'R', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::MultiCell(97.925, 5, '',  'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, '',  'BR', 'C', 0, 0, '', '', true);
        PDF::ln(); 

        PDF::SetXY(25, 263.5); 
        PDF::MultiCell(6, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::ln(); 
        PDF::SetXY(31, 262); 
        PDF::MultiCell(3, 6, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(60.925, 6, 'Inspected, verified and found OK as to quantity and inspection.',  0, 'L', 0, 0, '', '', true);


        PDF::ln(); 
        PDF::SetXY(143, 262); 
        PDF::MultiCell(6, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(3, 6, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(60.925, 6, 'Complete',  0, 'L', 0, 0, '', '', true);

        PDF::ln(); 
        PDF::SetXY(143, 270); 
        PDF::MultiCell(6, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(3, 6, '',  0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(60.925, 6, 'Partial',  0, 'L', 0, 0, '', '', true);
        
        if ($res->parentStatus == 'completed') {
            PDF::Image(url('./assets/images/checkmark.png'), 143, 262, 6, 6, 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, false, false);
        } else {
            if (strtolower($res->identityStatus) == 'partial') {
                PDF::Image(url('./assets/images/checkmark.png'), 143, 270, 6, 6, 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, false, false);
            } else {
                PDF::Image(url('./assets/images/checkmark.png'), 143, 262, 6, 6, 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, false, false);
            }
        }
         
        // PDF::Output('purchase_order_'.$poNum.'.pdf');
        $filename = 'purchase_order_'.$poNum.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('gso_inspect_acceptance_approv_accountant');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('gso_inspect_acceptance_approv_accountant');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        // echo $res->inspector->user_id;exit;
        $signature = $this->_commonmodel->getuserSignature($res->inspector->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        
        PDF::Output($folder.$filename,"I");

    }
    

    public function print_disbursement(Request $request, $poNum)
    {
        $res = $this->gsoPurchaseOrderRepository->find_posting($poNum, '');
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        if ($res->posting_status != 'completed') {
            return abort(404);
        }
        $departments = $this->gsoPurchaseOrderRepository->getPoDepartments($poNum);
        $alobs = $this->gsoPurchaseOrderRepository->getAlobs($poNum);
        $divisions = $this->gsoPurchaseOrderRepository->getPoDivisions($poNum);

        PDF::SetTitle('Disbursement Voucher ('.$poNum.')');    
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
        PDF::MultiCell(50, 13, chr(10) .''. strtoupper($res->purchased->supplier->business_name), 'BLR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(22, 13, 'TIN/Emp No:', 'B', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(34, 13, chr(10) .''. $res->purchased->supplier->tin_no , 'BR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(24, 5, 'Obligation No:', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(40, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::ln();
        PDF::MultiCell(131.85, 8, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::setCellHeightRatio(1.15);
        PDF::MultiCell(54, 8, $alobs, 'BR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(25.85, 25.25, 'Address:', 'LRB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(50, 25.25, chr(10) . trim($res->purchased->supplier->address), 'BLR', 'L', 0, 0, '', '', true);
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
        PDF::MultiCell(46, 8, $poNum , 'BR', 'L', 0, 0, '', '', true);   


        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);        
        PDF::MultiCell(54, 8, $divisions, 'BR', 'L', 0, 0, '', '', true);
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
        PDF::MultiCell(46, 8, $departments, 'BR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(54, 8, $res->purchased->rfq->fund->code.' - '. $res->purchased->rfq->fund->description, 'BR', 'L', 0, 0, '', '', true);
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
        PDF::MultiCell(87.925, 80, '        '.$res->purchased->rfq->project_name, 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
        PDF::setXY(112.925, $y);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(87.925, 80, 'Php' . $this->money_format2($res->purchased->total_amount), 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
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
        PDF::MultiCell(77.925, 10, 'CHRISTINA R. YAMBOT', 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, 'MARY JANE F. VILLAREAL', 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
       
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'City Accountant', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'OIC/City Treasurer', 'LBR', 'C', 0, 0, '', '', true);
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
        PDF::MultiCell(77.925, 10, 'HON. VIANDREI NICOLE J. CUEVAS', 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, strtoupper($res->purchased->supplier->business_name), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'City Mayor', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'Payee', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetXY(67, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(117, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(169, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::Output('disbursement_voucher_'.$poNum.'.pdf');
    }
}
