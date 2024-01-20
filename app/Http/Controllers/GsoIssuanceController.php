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
use App\Interfaces\GsoIssuanceInterface;
use App\Interfaces\GsoPurchaseOrderInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use App\Models\HrEmployee; 
use File;
class GsoIssuanceController extends Controller
{   
    private GsoIssuanceInterface $gsoIssuanceRepository;
    private GsoPurchaseOrderInterface $gsoPurchaseOrderRepository;
    private $carbon;
    private $slugs;

    public function __construct(GsoIssuanceInterface $gsoIssuanceRepository, GsoPurchaseOrderInterface $gsoPurchaseOrderRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->gsoIssuanceRepository = $gsoIssuanceRepository;
        $this->gsoPurchaseOrderRepository = $gsoPurchaseOrderRepository;
		$this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'general-services/issuance';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $requestor = $this->gsoIssuanceRepository->allEmployees();
        $pr_po = $this->gsoIssuanceRepository->allPrPo();
        return view('general-services.issuance.index')->with(compact('requestor', 'pr_po'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => 'draft-bg',
            'pending' => 'for-approval-bg',
            'for approval' => 'for-approval-bg',
            'issued' => 'completed-bg',
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
        $result = $this->gsoIssuanceRepository->listItems($request);
        $res = $result->data->map(function($issuance) use ($actions, $actions2, $statusClass) {
            $division = $issuance->requestor ? ($issuance->requestor->acctg_department_division_id > 0) ? '[' . $issuance->requestor->division->code . ']' : '' : '';
            $department = $issuance->requestor ? wordwrap($issuance->requestor->department->code . ' - ' . $issuance->requestor->department->name . ' '.$division, 25, "\n") : '';
            $requestedDate = $issuance->requested_date ? date('d-M-Y', strtotime($issuance->requested_date)) : '';
            $issuanceDate = $issuance->issued_date ? date('d-M-Y', strtotime($issuance->issued_date)) : '';
            return [
                'id' => $issuance->identity,
                'control_no' => $issuance->identityNo,
                'control_no_label' => '<strong class="text-primary">'.$issuance->identityNo.'</strong>',
                'requested_by' => $issuance->requestor ? '<strong>'.$issuance->requestor->fullname.'</strong><br/>'. $requestedDate : '',
                'issued_by' => $issuance->issuer ? '<strong>'.$issuance->issuer->fullname.'</strong><br/>'. $issuanceDate : '',
                'department' => '<div class="showLess">' . $department . '</div>',
                'total_amount' => $this->money_format($issuance->identityTotal),
                'modified' => ($issuance->identityUpdated !== NULL) ? 
                '<strong>'.$issuance->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($issuance->identityUpdated)) : 
                '<strong>'.$issuance->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($issuance->identityCreated)),
                'status' => $issuance->identityStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$issuance->identityStatus]. ' p-2">' .  $issuance->identityStatus . '</span>',
                'actions' => ($issuance->identityStatus == 'draft') ? $actions : $actions2
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn m-1 btn-sm align-items-center" title="View"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoIssuanceRepository->item_listItems($request, $id);
        $res = $result->data->map(function($issueItem, $iteration = 0) use ($actions) {
            if (strlen($issueItem->item->remarks) > 0) { 
                $description = wordwrap($issueItem->itemCode . ' - ' . $issueItem->item->name . ' (' . $issueItem->item->remarks . ')', 25, "\n");
                $descriptions = $issueItem->itemCode . ' - ' . $issueItem->item->name . ' (' . $issueItem->item->remarks . ')';
            } else { 
                $description = wordwrap($issueItem->itemCode . ' - ' . $issueItem->item->name, 25, "\n"); 
                $descriptions = $issueItem->itemCode . ' - ' . $issueItem->item->name; 
            } 
            return [
                'no' => $iteration = $iteration + 1,
                'category' => $issueItem->category->code,
                'type' => $issueItem->type->code,
                'id' => $issueItem->identity,
                'description' => '<div class="showLess">' . $description . '</div>',
                'descriptions' => $descriptions,
                'quantity' => $issueItem->itemQuantity,
                'uom' => $issueItem->uom->code,
                'unit_cost' => $this->money_format($issueItem->itemCost),
                'total_cost' => $this->money_format(floatval(floatval($issueItem->itemQuantity) * floatval($issueItem->itemCost))),
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

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function update(Request $request, $issuanceID)
    {   
        $arr = array();
        if (!empty($request->purchase_order_id)) {
            foreach ($request->purchase_order_id as $po) {
                $arr[] = $po;
            }
        }

        $purchase_order = (count($arr) > 0) ? implode(',', $arr) : NULL;
        if ($issuanceID <= 0) {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'control_no' => $this->gsoIssuanceRepository->generate_control_no(),
                'purchase_order_id' => $purchase_order,
                'requested_by' => $request->requested_by,
                'requested_by_designation' => $request->requested_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->requested_by) : NULL,
                'requested_date' => $request->requested_date ? date('Y-m-d', strtotime($request->requested_date)) : NULL,
                'issued_by' =>  $request->issued_by,
                'issued_by_designation' => $request->issued_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->issued_by) : NULL,
                'issued_date' => $request->issued_date ? date('Y-m-d', strtotime($request->issued_date)) : NULL,
                'received_by' =>  $request->received_by,
                'received_by_designation' => $request->received_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->received_by) : NULL,
                'received_date' => $request->received_date ? date('Y-m-d', strtotime($request->received_date)) : NULL,
                'remarks' => $request->remarks,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $issuance = $this->gsoIssuanceRepository->create($details);
            $issuanceID = $issuance->id;
        } else {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'purchase_order_id' => $purchase_order,
                'requested_by' => $request->requested_by,
                'requested_by_designation' => $request->requested_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->requested_by) : NULL,
                'requested_date' => $request->requested_date ? date('Y-m-d', strtotime($request->requested_date)) : NULL,
                'issued_by' =>  $request->issued_by,
                'issued_by_designation' => $request->issued_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->issued_by) : NULL,
                'issued_date' => $request->issued_date ? date('Y-m-d', strtotime($request->issued_date)) : NULL,
                'received_by' =>  $request->received_by,
                'received_by_designation' => $request->received_by ? $this->gsoPurchaseOrderRepository->fetch_designation($request->received_by) : NULL,
                'received_date' => $request->received_date ? date('Y-m-d', strtotime($request->received_date)) : NULL,
                'remarks' => $request->remarks,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->gsoIssuanceRepository->update($issuanceID, $details);
        }
        return response()->json([
            'data' => $this->gsoIssuanceRepository->find($issuanceID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_status(Request $request, $issuanceID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoIssuanceRepository->find($issuanceID)->status
        ]);
    }

    public function validate_issuance(Request $request, $issuanceID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'validated' => $this->gsoIssuanceRepository->validate_par($issuanceID)
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $issuance = $this->gsoIssuanceRepository->find($id);
        return response()->json([
            'data' => 
            (object) [
                'control_no' => $issuance->control_no,
                'requested_by' => $issuance->requested_by,
                'requested_date' => $issuance->requested_date,
                'issued_by' => $issuance->issued_by,
                'issued_date' => $issuance->issued_date,
                'received_by' => $issuance->received_by,
                'received_date' => $issuance->received_date,
                'department' => $issuance->requestor ? $issuance->requestor->department->code . ' - ' . $issuance->requestor->department->name : '',
                'designation' => $issuance->requestor ? $issuance->requestor->department->designation->description : '',
                'remarks' => $issuance->remarks,
                'purchase_order_id' => explode(',',$issuance->purchase_order_id)
            ]
        ]);
    }

    public function view_available_items(Request $request, $issuanceID)
    {
        $this->is_permitted($this->slugs, 'read');
        $inventory = $request->get('inventory');
        $poNo = ($request->get('po_no') !== null) ? $request->get('po_no') : 0;
        $res = $this->gsoIssuanceRepository->view_available_items($issuanceID, $inventory, $poNo)
        ->map(function($issueItem, $iteration = 0) use ($issuanceID, $inventory, $poNo) {
            if ($issueItem->itemRemarks !== NULL) { 
                $description = wordwrap($issueItem->itemName . ' (' . $issueItem->itemRemarks . ')', 25, "\n");
            } else { 
                $description = wordwrap($issueItem->itemName, 25, "\n"); 
            } 
            $withdraw = $this->gsoIssuanceRepository->check_quantity_withdrawn($issuanceID, $issueItem->itemId, $inventory, $poNo);
            $withdrawn = $this->gsoIssuanceRepository->check_all_quantity_withdrawn($issuanceID, $issueItem->itemId, $inventory, $poNo);
            return [
                'no' => $iteration = $iteration + 1,
                'po' => $issueItem->poId,
                'ref' => $issueItem->poNo,
                'id' => $issueItem->itemId,
                'code' => $issueItem->itemCode,
                'description' => '<div class="showLess">' . $description . '</div>',
                // 'withdrawn' => $withdrawn,
                'withdraw' => $withdraw,
                'quantity' => $issueItem->itemQuantity,
                'available' => floatval(floatval($issueItem->itemQuantity) - floatval($withdrawn)),
                'uom_id' => $issueItem->uom->id,
                'uom' => $issueItem->uom->code,
                'amt' => ($inventory > 0) ? $issueItem->itemCost : $this->gsoPurchaseOrderRepository->getItemCost($issueItem->rfq, $issueItem->itemId)
            ];
        });
        return response()->json([
            'data' => $res,
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
    
    public function post(Request $request, $issuanceID)
    {
        $this->is_permitted($this->slugs, 'create'); 
        return response()->json([
            'data' => $this->gsoIssuanceRepository->post($request, $issuanceID, $request->get('inventory'), $this->carbon::now(), Auth::user()->id),
            'total' => $this->gsoIssuanceRepository->getTotalAmount($issuanceID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully posted.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $issuanceID)
    {   
        if ($status == 'for-approval') {
            $timestamp = $this->carbon::now();
            $validated = $this->gsoIssuanceRepository->validate_par($issuanceID);
            if (!($validated > 0)) {
                if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                    $details = array(
                        'status' => 'issued',
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id,
                        'approved_at' => $timestamp,
                        'approved_by' => Auth::user()->id,
                        'approved_by_designation' => $this->gsoPurchaseOrderRepository->fetch_designation(Auth::user()->id)
                    );
                    $this->gsoIssuanceRepository->update($issuanceID, $details);
                    $this->gsoIssuanceRepository->credit_inventory($issuanceID, $timestamp, Auth::user()->id);
                } else {
                    $details = array(
                        'status' => str_replace('-', ' ', $status),
                        'sent_at' => $timestamp,
                        'sent_by' => Auth::user()->id
                    );
                    $this->gsoIssuanceRepository->update($issuanceID, $details);
                }
                return response()->json([
                    'text' => 'The request has been successfully sent.',
                    'type' => 'success',
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'title' =>  'Oops...',
                    'status' => 'failed',
                    'type' => 'error',
                    'text' => 'Unable to send, item invetory is not available.',
                ]);
            }
        } else {
            return response()->json([
                'title' =>  'Oops...',
                'status' => 'failed',
                'type' => 'danger',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function remove_line(Request $request, $lineID)
    {
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'is_active' => 0,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->gsoIssuanceRepository->update_line($lineID, $details);
        $totalAmt = $this->gsoIssuanceRepository->getTotalAmount($request->get('issuance_id'));
        return response()->json([
            'data' => $this->gsoIssuanceRepository->update($request->get('issuance_id'), ['total_amount' => $totalAmt]),
            'total' => $totalAmt,
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function print(Request $request, $controlNo)
    {  
		
        $res = $this->gsoIssuanceRepository->find_issuance($controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        $types = explode(',', $request->get('type'));

        $titles = [
            '1' => (object) ['title' => 'REQUISITION AND ISSUE SLIP', 'abbre' => 'RIS'], 
            '2' => (object) ['title' => 'INVENTORY CUSTODIAN SLIP', 'abbre' => 'ICS'], 
            '3' => (object) ['title' => 'PROPERTY ACKNOWLEDGEMENT RECEIPT', 'abbre' => 'PAR'] 
        ];

        PDF::SetTitle('Issuance ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        foreach ($types as $type) {

            PDF::AddPage('P', 'LEGAL');

            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(195.85, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::ln(4);
            PDF::MultiCell(195.85, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::ln(4.5);
            PDF::SetFont('Helvetica', 'B', 20);
            PDF::MultiCell(195.85, 5, $titles[$type]->title, 0, 'C', 0, 0, '', '', true);
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
            if ($type == 1) {
                PDF::MultiCell(41, 5, 'Responsibility Office: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(75, 5, $res->requestor->department->code.' - '.$res->requestor->department->name .' ('.$res->requestor->department->shortname.')', 'B', 'L', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(18, 5, $titles[$type]->abbre.' No: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(55, 5, $res->control_no, 'B', 'L', 0, 0, '', '', true);
                PDF::ln(); 

                PDF::MultiCell(41, 5, ' ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(75, 5, '', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(21, 5, $titles[$type]->abbre.' Date: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(52, 5, date('d-M-Y', strtotime($res->created_at)), 0, 'L', 0, 0, '', '', true);
                PDF::ln(); 
                PDF::SetXY(10, 47); 
                PDF::MultiCell(120, 10, '', 1, 'L', 0, 0, '', '', true);
                PDF::MultiCell(75.85, 10, '', 1, 'L', 0, 0, '', '', true);
                PDF::ln(); 
            } else {
                PDF::MultiCell(27, 5, 'Entity Name: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(89, 5, 'LOCAL GOVERNMENT UNIT-PALAYAN', 'B', 'L', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(18, 5, $titles[$type]->abbre.' No: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(55, 5, $res->control_no, 'B', 'L', 0, 0, '', '', true);
                PDF::ln(); 

                PDF::MultiCell(27, 5, 'Fund Cluster: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(89, 5, 'GENERAL FUND', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(21, 5, $titles[$type]->abbre.' Date: ', 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(52, 5, date('d-M-Y', strtotime($res->created_at)), 0, 'L', 0, 0, '', '', true);
                PDF::ln(); 
                PDF::SetXY(10, 47); 
                PDF::MultiCell(120, 10, '', 1, 'L', 0, 0, '', '', true);
                PDF::MultiCell(75.85, 10, '', 1, 'L', 0, 0, '', '', true);
                PDF::ln(); 
            }

            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 10);
            PDF::SetXY(10, 57); 
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
            $itemList = $this->gsoIssuanceRepository->posted_items_via_control_no($controlNo, $type);
            $iteration = 0; $totalAmt = 0;
            foreach ($itemList as $item) {
                $iteration++;
                $description = $item->item->code .' - ' . $item->item->name;  
                $unitCost = $item->itemCost;
                $totalCost = floatval($item->itemQuantity) * floatval($unitCost);
                $totalAmt += floatval($totalCost);
                PDF::MultiCell(18, 5, $iteration, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(20, 5, $item->itemQuantity, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(18, 5, $item->uom->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(78, 5, $description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(30.925, 5, number_format(floor(($unitCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30.925, 5, number_format(floor(($totalCost*100))/100, 2), 0, 'R', 0, 0, '', '', true);           
                PDF::ln(8.5); 
            }

            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 11);
            PDF::setCellHeightRatio(1.25);
            PDF::SetXY(10, 62); 
            PDF::MultiCell(18, 178, '', 'LR', 'C', 0, 0, '', '', true);
            PDF::MultiCell(18, 178, '', 'LR', 'C', 0, 0, '', '', true);
            PDF::MultiCell(20, 178, '', 'LR', 'C', 0, 0, '', '', true);
            PDF::MultiCell(78, 178, '', 'LR', 'C', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 178, '', 'LR', 'C', 0, 0, '', '', true);
            PDF::MultiCell(30.925, 178, '', 'LR', 'C', 0, 0, '', '', true);

            PDF::ln(); 
            PDF::MultiCell(164.925, 5, '(Total Amount in Words) '.trim(ucfirst(strtolower($this->gsoIssuanceRepository->numberTowords($totalAmt)))),  'TL', 'L', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', 'B', 11);
            PDF::MultiCell(30.925, 5, 'P'.number_format(floor(($totalAmt*100))/100, 2), 'TR', 'R', 0, 0, '', '', true);
            $arrSign= $this->_commonmodel->isSignApply('gso_issuance_ris_requested_by');
            $isSignVeified = isset($arrSign)?$arrSign->status:0;

            $arrCertified= $this->_commonmodel->isSignApply('gso_issuance_ris_approved_by');
            $isSignCertified = isset($arrCertified)?$arrCertified->status:0;
            
            $arrIssuer= $this->_commonmodel->isSignApply('gso_issuance_ris_issued_by');
            $isSignIssuer = isset($arrIssuer)?$arrIssuer->status:0;
            
            $arrReceived= $this->_commonmodel->isSignApply('gso_issuance_ris_received_by');
            $isSignReceived = isset($arrReceived)?$arrReceived->status:0;

            $arrIcsissued= $this->_commonmodel->isSignApply('gso_issuance_ics_issued_by');
            $isSignIcsissued = isset($arrIcsissued)?$arrIcsissued->status:0;
        
            $arrIcsreceived= $this->_commonmodel->isSignApply('gso_issuance_ics_received_by');
            $isSignIcsreceived = isset($arrIcsreceived)?$arrIcsreceived->status:0;

            $signType = $this->_commonmodel->getSettingData('sign_settings');
            $requestby = HrEmployee::where('id', $res->requestor->id)->first();
            $approverby = HrEmployee::where('id', $res->approver->hr_employee->id)->first();
            $issuerby = HrEmployee::where('id', $res->issuer->id)->first();
            $receiverby = HrEmployee::where('id', $res->receiver->id)->first();
            $ics_issued_by = HrEmployee::where('id', $res->issuer->id)->first();
            $ics_received_by = HrEmployee::where('id', $res->receiver->id)->first();
            if ($type == 1) {
                $varifiedSignature = $this->_commonmodel->getuserSignature($requestby->user_id);
                $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;
                $certifiedSignature = $this->_commonmodel->getuserSignature($approverby->user_id);
                $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
                $IssuerSignature = $this->_commonmodel->getuserSignature($issuerby->user_id);
                $IssuerPath =  public_path().'/uploads/e-signature/'.$IssuerSignature;
                $ReceivedSignature = $this->_commonmodel->getuserSignature($receiverby->user_id);
                $ReceivedPath =  public_path().'/uploads/e-signature/'.$ReceivedSignature;
               
                //Issuer
                PDF::ln();
                PDF::SetFont('Helvetica', '', 11);
                PDF::MultiCell(48.9625, 5, 'Requested By',  'LT', 'C', 0, 0, '', '', true);
                 if($isSignVeified==1 && $signType==1){
                    if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                        PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                    }
                }
                PDF::MultiCell(48.9625, 5, 'Approved By',  'LT', 'C', 0, 0, '', '', true);
                if($isSignCertified==1 && $signType==1){
                if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                        PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
                    }
                }
                PDF::MultiCell(48.9625, 5, 'Issued By',  'LT', 'C', 0, 0, '', '', true);
                 if($isSignIssuer==1 && $signType==1){
                    if(!empty($IssuerSignature) && File::exists($IssuerPath)){
                        PDF::Image($IssuerPath,$arrIssuer->esign_pos_x, $arrIssuer->esign_pos_y, $arrIssuer->esign_resolution);
                    }
                }
                
                PDF::MultiCell(48.9625, 5, 'Received By',  'LRT', 'C', 0, 0, '', '', true);
                 if($isSignReceived==1 && $signType==1){
                    if(!empty($ReceivedSignature) && File::exists($ReceivedPath)){
                        PDF::Image($ReceivedPath,$arrReceived->esign_pos_x, $arrReceived->esign_pos_y, $arrReceived->esign_resolution);
                    }
                }

                PDF::ln();
                PDF::MultiCell(48.9625, 7.5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 7.5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 7.5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 7.5, '',  'LR', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(38.9625, 5, strtoupper($res->requestor->firstname.' '.$res->requestor->lastname),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(38.9625, 5, strtoupper($res->approver ? $res->approver->hr_employee->firstname.' '.$res->approver->hr_employee->lastname : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(38.9625, 5, strtoupper($res->issuer ? $res->issuer->firstname.' '.$res->issuer->lastname : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(38.9625, 5, strtoupper($res->receiver ? $res->receiver->firstname.' '.$res->receiver->lastname : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(5, 5, '',  'R', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(48.9625, 5, ucwords(($res->requestor ? $res->requestor_designation->description : '')),  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, ucwords(($res->approver ? $res->approver_designation->description : '')),  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, ucwords(($res->issuer ? $res->issuer_designation->description : '')),  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, ucwords(($res->receiver ? $res->receiver_designation->description : '')),  'LR', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(48.9625, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'LR', 'C', 0, 0, '', '', true);


                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(13, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.9625, 5, ($res->requested_date ? date('d-M-Y', strtotime($res->requested_date)) : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.9625, 5, ($res->approved_at ? date('d-M-Y', strtotime($res->approved_at)) : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.9625, 5, ($res->issued_date ? date('d-M-Y', strtotime($res->issued_date)) : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.9625, 5, ($res->received_date ? date('d-M-Y', strtotime($res->received_date)) : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(13, 5, '',  'R', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(48.9625, 5, 'Date',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, 'Date',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, 'Date',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, 'Date',  'LR', 'C', 0, 0, '', '', true);
                
                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(48.9625, 5, '',  'LB', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'LB', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'LB', 'C', 0, 0, '', '', true);
                PDF::MultiCell(48.9625, 5, '',  'LBR', 'C', 0, 0, '', '', true);

            } else {
                $IcsissuedSignature = $this->_commonmodel->getuserSignature($ics_issued_by->user_id);
                $IcsissuedPath =  public_path().'/uploads/e-signature/'.$IcsissuedSignature;
                $IcsreceivedSignature = $this->_commonmodel->getuserSignature($ics_received_by->user_id);
                $IcsreceivedPath =  public_path().'/uploads/e-signature/'.$IcsreceivedSignature;
                PDF::ln();
                PDF::SetFont('Helvetica', '', 11);
                PDF::MultiCell(97.925, 5, 'Issued By:',  'LT', 'L', 0, 0, '', '', true);
                if ($type == 2){
                if($isSignIcsissued==1 && $signType==1){
                    if(!empty($IcsissuedSignature) && File::exists($IcsissuedPath)){
                        PDF::Image($IcsissuedPath,$arrIcsissued->esign_pos_x, $arrIcsissued->esign_pos_y, $arrIcsissued->esign_resolution);
                    }
                } }
       
                PDF::MultiCell(97.925, 5, 'Received By:',  'LTR', 'L', 0, 0, '', '', true);
                 //Icsreceived
                if ($type == 2){
                if($isSignIcsreceived==1 && $signType==1){
                    if(!empty($IcsreceivedSignature) && File::exists($IcsreceivedPath)){
                        PDF::Image($IcsreceivedPath,$arrIcsreceived->esign_pos_x, $arrIcsreceived->esign_pos_y, $arrIcsreceived->esign_resolution);
                    }
                } }
                PDF::ln();
                PDF::MultiCell(97.925, 7.55, '',  'L', 'L', 0, 0, '', '', true);
                PDF::MultiCell(97.925, 5, '',  'LR', 'L', 0, 0, '', '', true);
                
                PDF::ln();
                PDF::SetFont('Helvetica', 'B', 10);
                PDF::MultiCell(17.5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(62.925, 5, strtoupper($res->issuer ? $res->issuer->fullname : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(17.5, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(17.5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(62.925, 5, strtoupper($res->receiver ? $res->receiver->fullname : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(17.5, 5, '',  'R', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(97.925, 5, ucwords(($res->issuer ? $res->issuer_designation->description : '')),  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(97.925, 5, ucwords(($res->receiver ? $res->receiver_designation->description : '')),  'LR', 'C', 0, 0, '', '', true);
                
                PDF::ln();
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(97.925, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(97.925, 5, '',  'LR', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::MultiCell(37.5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.925, 5, ($res->issued_date ? date('d-M-Y', strtotime($res->issued_date)) : ''),  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(37.5, 5, '',  0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(37.5, 5, '',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(22.925, 5, ($res->received_date ? date('d-M-Y', strtotime($res->received_date)) : '') ,  'B', 'C', 0, 0, '', '', true);
                PDF::MultiCell(37.5, 5, '',  'R', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(97.925, 5, 'Date',  'L', 'C', 0, 0, '', '', true);
                PDF::MultiCell(97.925, 5, 'Date',  'LR', 'C', 0, 0, '', '', true);

                PDF::ln();
                PDF::setCellHeightRatio(1.25);
                PDF::SetFont('Helvetica', '', 10);
                PDF::MultiCell(97.925, 5, '',  'LB', 'C', 0, 0, '', '', true);
                PDF::MultiCell(97.925, 5, '',  'LBR', 'C', 0, 0, '', '', true);
            }
        }
        
		
		//next
		
		$filename = $controlNo."issuance.pdf";
		
		$arrIcsissued1= $this->_commonmodel->isSignApply('gso_issuance_par_issued_by');
        $isSignIcsissued1 = isset($arrIcsissued1)?$arrIcsissued1->status:0;
		
		$arrIcsreceived2= $this->_commonmodel->isSignApply('gso_issuance_par_received_by');
        $isSignIcsreceived2 = isset($arrIcsreceived2)?$arrIcsreceived2->status:0;
		
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
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($requestby->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $isSignIssuer==0 && $isSignReceived==0 && $isSignIcsissued==0 && $isSignIcsreceived==0 && $isSignIcsissued1==0 && $isSignIcsreceived2==0 && $signType==2) {
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature($approverby->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = 2;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['signaturePath'] = $certifiedSignature;
                if($isSignVeified==0 && $isSignIssuer==0 && $isSignReceived==0 && $isSignIcsissued==0 && $isSignIcsreceived==0 && $isSignIcsissued1==0 && $isSignIcsreceived2==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
		//Issuer
		$IssuerSignature = $this->_commonmodel->getuserSignature($issuerby->user_id);
        $IssuerPath =  public_path().'/uploads/e-signature/'.$IssuerSignature;

        if($isSignIssuer==1 && $signType==2){
            if(!empty($IssuerSignature) && File::exists($IssuerPath)){
                $arrData['isSavePdf'] = 3;
                $arrData['signerXyPage'] = $arrIssuer->pos_x.','.$arrIssuer->pos_y.','.$arrIssuer->pos_x_end.','.$arrIssuer->pos_y_end.','.$arrIssuer->d_page_no;
                $arrData['signaturePath'] = $IssuerSignature;
                if($isSignVeified==0 && $isSignCertified==0 && $isSignReceived==0 && $isSignIcsissued==0 && $isSignIcsreceived==0 && $isSignIcsreceived2==0 && $isSignIcsissued1==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
		//Received
		$ReceivedSignature = $this->_commonmodel->getuserSignature($receiverby->user_id);
        $ReceivedPath =  public_path().'/uploads/e-signature/'.$ReceivedSignature;

        if($isSignReceived==1 && $signType==2){
            if(!empty($ReceivedSignature) && File::exists($ReceivedPath)){
                $arrData['isSavePdf'] = 4;
                $arrData['signerXyPage'] = $arrReceived->pos_x.','.$arrReceived->pos_y.','.$arrReceived->pos_x_end.','.$arrReceived->pos_y_end.','.$arrReceived->d_page_no;
                $arrData['signaturePath'] = $ReceivedSignature;
                if($isSignVeified==0 && $isSignCertified==0 && $isSignIssuer==0 && $isSignIcsissued==0 && $isSignIcsreceived==0 && $isSignIcsreceived2==0 && $isSignIcsissued1==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
		//Icsissued
		$IcsissuedSignature = $this->_commonmodel->getuserSignature($ics_issued_by->user_id);
        $IcsissuedPath =  public_path().'/uploads/e-signature/'.$IcsissuedSignature;

        if($isSignIcsissued==1 && $signType==2){
            if(!empty($IcsissuedSignature) && File::exists($IcsissuedPath)){
                $arrData['isSavePdf'] = 5;
                $arrData['signerXyPage'] = $arrIcsissued->pos_x.','.$arrIcsissued->pos_y.','.$arrIcsissued->pos_x_end.','.$arrIcsissued->pos_y_end.','.$arrIcsissued->d_page_no;
                $arrData['signaturePath'] = $IcsissuedSignature;
                if($isSignVeified==0 && $isSignCertified==0 && $isSignIssuer==0 && $isSignReceived==0 &&  $isSignIcsreceived==0 && $isSignIcsissued1==0 && $isSignIcsreceived2==0 && $signType==2) {
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
		//Icsreceived
		$IcsreceivedSignature = $this->_commonmodel->getuserSignature($ics_received_by->user_id);
        $IcsreceivedPath =  public_path().'/uploads/e-signature/'.$IcsreceivedSignature;

        if($isSignIcsreceived==1 && $signType==2){
            if(!empty($IcsreceivedSignature) && File::exists($IcsreceivedPath)){
                $arrData['isSavePdf'] = 6;
                $arrData['signerXyPage'] = $arrIcsreceived->pos_x.','.$arrIcsreceived->pos_y.','.$arrIcsreceived->pos_x_end.','.$arrIcsreceived->pos_y_end.','.$arrIcsreceived->d_page_no;
                $arrData['signaturePath'] = $IcsreceivedSignature;
                if($isSignVeified==0 && $isSignCertified==0 && $isSignIssuer==0 && $isSignReceived==0 && $isSignIcsissued==0 && $isSignIcsissued1==0 && $isSignIcsreceived2==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }
		
		//Icsissued
		$IcsissuedSignature1 = $this->_commonmodel->getuserSignature($ics_issued_by->user_id);
        $IcsissuedPath1 =  public_path().'/uploads/e-signature/'.$IcsissuedSignature1;

        if($isSignIcsissued1==1 && $signType==2){
            if(!empty($IcsissuedSignature1) && File::exists($IcsissuedPath1)){
                $arrData['isSavePdf'] = 7;
                $arrData['signerXyPage'] = $arrIcsissued1->pos_x.','.$arrIcsissued1->pos_y.','.$arrIcsissued1->pos_x_end.','.$arrIcsissued1->pos_y_end.','.$arrIcsissued1->d_page_no;
                $arrData['signaturePath'] = $IcsissuedSignature1;
                if($isSignVeified==0 && $isSignCertified==0 && $isSignIssuer==0 && $isSignReceived==0 && $isSignIcsissued==0 && $isSignIcsreceived==0 && $isSignIcsreceived2==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

		//Icsreceived
		$IcsreceivedSignature2 = $this->_commonmodel->getuserSignature($ics_received_by->user_id);
        $IcsreceivedPath2 =  public_path().'/uploads/e-signature/'.$IcsreceivedSignature2;

        if($isSignIcsreceived2==1 && $signType==2){
            if(!empty($IcsreceivedSignature2) && File::exists($IcsreceivedPath2)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1 || $arrData['isSavePdf']==2 || $arrData['isSavePdf']==3 || $arrData['isSavePdf']==4 || $arrData['isSavePdf']==5 || $arrData['isSavePdf']==6 || $arrData['isSavePdf']==7)?0:1;
                $arrData['signerXyPage'] = $arrIcsreceived2->pos_x.','.$arrIcsreceived2->pos_y.','.$arrIcsreceived2->pos_x_end.','.$arrIcsreceived2->pos_y_end.','.$arrIcsreceived2->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $IcsreceivedSignature2;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
		
		// Apply E-sign Here
       
		//Icsissued
		
		
		//Icsissued
		if($isSignIcsissued1==1 && $signType==1){
            if(!empty($IcsissuedSignature1) && File::exists($IcsissuedPath1)){
                PDF::Image($IcsissuedPath1,$arrIcsissued1->esign_pos_x, $arrIcsissued1->esign_pos_y, $arrIcsissued1->esign_resolution);
            }
        }
		//Icsreceived
		if($isSignIcsreceived2==1 && $signType==1){
            if(!empty($IcsreceivedSignature2) && File::exists($IcsreceivedPath2)){
                PDF::Image($IcsreceivedPath2,$arrIcsreceived2->esign_pos_x, $arrIcsreceived2->esign_pos_y, $arrIcsreceived2->esign_resolution);
            }
        }
		
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
		PDF::Output($folder.$filename,"I");
    }
}

