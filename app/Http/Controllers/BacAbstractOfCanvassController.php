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

class BacAbstractOfCanvassController extends Controller
{   
    private BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository;
    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private BacResolutionInterface $bacResolutionRepository;
    private $carbon;
    private $slugs;

    public function __construct(BacResolutionInterface $bacResolutionRepository, BacAbstractOfCanvassInterface $bacAbstractOfCanvassRepository, BacRequestForQuotationInterface $bacRequestForQuotationRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->bacAbstractOfCanvassRepository = $bacAbstractOfCanvassRepository;
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->bacResolutionRepository = $bacResolutionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'general-services/bac/abstract-of-canvass';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $warranty = $this->bacRequestForQuotationRepository->allExpendableWarranties();
        $non_warranty = $this->bacRequestForQuotationRepository->allNonExpendableWarranties();
        $price_validity = $this->bacRequestForQuotationRepository->allPriceValidities();
        $fund_codes = $this->bacRequestForQuotationRepository->allFundCodes();
        return view('general-services.bac.abstract-of-canvass.index')->with(compact('fund_codes', 'warranty', 'non_warranty', 'price_validity'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => 'draft-bg',
            'pending' => 'for-approval-bg',
            'for approval' => 'for-approval-bg',
            'completed' => 'completed-bg',
            'processed' => 'requested-bg',
            'cancelled' => 'cancelled-bg',
        ]; 
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'download', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $canDownload = $this->is_permitted($this->slugs, 'download', 1);
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1);
        $result = $this->bacAbstractOfCanvassRepository->listItems($request);
        $res = $result->data->map(function($canvass) use ($actions, $actions2, $statusClass, $canDownload, $canUpdate) {
            $project_name = ($canvass->rfq->project_name !== NULL) ? wordwrap($canvass->rfq->project_name, 25, "\n") : '';
            $agencies = wordwrap($this->get_agencies($canvass->rfq->id), 25, "\n");
           
            return [
                'id' => $canvass->identity,
                'rfq' => $canvass->rfq->id,
                'control_no' => $canvass->rfq->control_no,
                'control_no_label' => '<strong class="text-primary">'.$canvass->rfq->control_no.'</strong>',
                'project_name' => '<div class="showLess">' . $project_name . '</div>',
                'agencies' => '<div class="showLess">' . $agencies . '</div>',
                'modified' => ($canvass->identityUpdated !== NULL) ? 
                '<strong>'.$canvass->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($canvass->identityUpdated)) : 
                '<strong>'.$canvass->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($canvass->identityCreated)),
                'status' => $canvass->identityStatus,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$canvass->identityStatus]. ' p-2">' .  $canvass->identityStatus . '</span>',
                'actions' => ($canvass->identityStatus == 'draft') ? $actions : $actions2
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
                'pr_no' => $rfqLine->purchase_request->purchase_request_no,
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
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }

        $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="View"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="Print"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->bacRequestForQuotationRepository->supplier_listItems($request, $id);
        $res = $result->data->map(function($rfqLineSupplier) use ($actions, $actions2, $statusClass) {
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
                'actions' => ($rfqLineSupplier->identityStatus == 'completed') ? $actions2 : $actions
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

    public function fetch_status(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->bacAbstractOfCanvassRepository->find_abstract($rfqID)->status
        ]);
    }

    public function view_available_suppliers(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->view_available_suppliers($rfqID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_suppliers(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'create');
        foreach ($request->suppliers as $supplier) {
            $exist = $this->bacRequestForQuotationRepository->find_supplier($rfqID, $supplier);
            if ($exist->count() > 0) {
                $supply = $exist->first();
                $details = array(
                    'is_active' => 1,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->bacRequestForQuotationRepository->update_supplier($supply->id, $details);
            } else {
                $details = array(
                    'rfq_id' => $rfqID,
                    'supplier_id' => $supplier,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $this->bacRequestForQuotationRepository->create_supplier($details);
            }
        }
        return response()->json([
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function view_available_purchase_requests(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->view_available_purchase_requests($rfqID)
            ->map(function($pr) {
                return [
                    'id' => $pr->id,
                    'pr_no' => $pr->purchase_request_no,
                    'department' => $pr->requisition->department->code . ' - ' . $pr->requisition->department->name,
                    'division' => $pr->requisition->division->code . ' - ' . $pr->requisition->division->name,
                ];
            }),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_purchase_request(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'create');
        foreach ($request->purchases as $purchase) {
            $exist = $this->bacRequestForQuotationRepository->find_pr($rfqID, $purchase);
            if ($exist->count() > 0) {
                $pr = $exist->first();
                $details = array(
                    'is_active' => 1,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->bacRequestForQuotationRepository->update_pr($pr->id, $details);
            } else {
                $details = array(
                    'rfq_id' => $rfqID,
                    'purchase_request_id' => $purchase,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $this->bacRequestForQuotationRepository->create_pr($details);
            }
        }
        return response()->json([
            'total' => $this->bacRequestForQuotationRepository->computeTotalBudget($rfqID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update(Request $request, $rfqID)
    {   
        if ($rfqID <= 0) {
            $details = array(
                'remarks' => $request->remarks,
                'recommendations' => $request->recommendations,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $rfq = $this->bacAbstractOfCanvassRepository->create($details);
            $rfqID = $rfq->id;
        } else {
            $details = array(
                'remarks' => $request->remarks,
                'recommendations' => $request->recommendations,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->bacAbstractOfCanvassRepository->update($rfqID, $details);
        }
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->find($rfqID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_pr(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->update_pr($id, $details),
            'total' => $this->bacRequestForQuotationRepository->computeTotalBudget($request->get('rfq')),
            'title' => 'Well done!',
            'text' => 'The purchase request has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_supplier(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->update_supplier($id, $details),
            'title' => 'Well done!',
            'text' => 'The supplier has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
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

    public function update_row(Request $request, $rfqID)
    {   
        $exist = $this->bacRequestForQuotationRepository->find_canvass($rfqID, $request->get('supplier'), $request->get('item'));
        if ($exist->count() > 0) {
            $canvass = $exist->first();
            $details = array(
                'description' => $request->get('description'),
                'unit_cost' => $request->get('unit_cost'),
                'total_cost' => $request->get('total_cost'),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->bacRequestForQuotationRepository->update_canvass($canvass->id, $details);
            
        } else {
            $details = array(
                'rfq_id' => $rfqID,
                'supplier_id' => $request->get('supplier'),
                'item_id' => $request->get('item'),
                'description' => $request->get('description'),
                'unit_cost' => $request->get('unit_cost'),
                'total_cost' => $request->get('total_cost'),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $canvass = $this->bacRequestForQuotationRepository->create_canvass($details);
        }
        return response()->json([
            'total' => $this->computeTotalAmount($rfqID, $request->get('supplier')),
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
        return $this->bacRequestForQuotationRepository->computeTotalAmount($rfqID, $supplierID);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function validate_supplier(Request $request, $rfqID)
    {   
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->validate_supplier($rfqID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_canvass(Request $request, $rfqID, $supplierID)
    {
        $this->is_permitted($this->slugs, 'update');
        $details = array(
            'canvass_by' => $request->canvass_by ? $request->canvass_by : NULL,
            'canvass_date' => $request->canvass_date ? date('Y-m-d', strtotime($request->canvass_date)) : NULL,
            'contact_person' => $request->contact_person ? $request->contact_person  : NULL,
            'contact_number' => $request->contact_number ? $request->contact_number : NULL,
            'email_address' => $request->email_address ? $request->email_address : NULL,
            'delivery_period' => $request->delivery_period ? $request->delivery_period : NULL,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->update_supplier_canvass($rfqID, $supplierID, $details),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function submit_canvass(Request $request, $rfqID, $supplierID)
    {
        $this->is_permitted($this->slugs, 'update');
        $details = array(
            'status' => 'completed',
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        return response()->json([
            'data' => $this->bacRequestForQuotationRepository->update_supplier_canvass($rfqID, $supplierID, $details),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $rfqID)
    {   
        if ($status == 'for-abstract-approval') {
            $timestamp = $this->carbon::now();
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'estimated',
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );

                $details2 = array(
                    'status' => 'completed',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id
                );

                $details3 = array(
                    'rfq_id' => $rfqID,
                    'created_at' => $timestamp,
                    'created_by' => Auth::user()->id
                );
                $this->bacResolutionRepository->create($details3);
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
            }
            return response()->json([
                'data' => $this->bacAbstractOfCanvassRepository->update($rfqID, $details2),
                'data2' => $this->bacRequestForQuotationRepository->update_request($rfqID, $details),
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

    public function view_available_committees(Request $request, $rfqID)
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->bacAbstractOfCanvassRepository->view_available_committees($rfqID)->map(function($committee) {
                $fullname = ($committee->identityName !== NULL) ? wordwrap($committee->identityName, 25, "\n") : '';
                $department = ($committee->departments !== NULL) ? wordwrap($committee->departments, 25, "\n") : '';
                $division = ($committee->divisions !== NULL) ? wordwrap($committee->divisions, 25, "\n") : '';
                $designation = ($committee->designations !== NULL) ? wordwrap($committee->designations, 25, "\n") : '';
                return (object) [
                    'id' => $committee->identity,
                    'name' => $committee->identityName,
                    'department' => $committee->departments,
                    'division' => $committee->divisions,
                    'designation' => $committee->designations
                ];                
            }),            
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_committees(Request $request, $rfqID)
    {
        $this->is_permitted($this->slugs, 'create');
        $committees = $this->bacAbstractOfCanvassRepository->find_abstract($rfqID)->committees;
        $arr = array();
        $arr = (strlen($committees) > 0) ? explode(',', $committees) : [];
        foreach ($request->committees as $committee) {
            if (!in_array($committee, $arr)) {
                $arr[] = $committee;
            }
        }
        $details = array(
            'committees' => (count($arr) > 1) ? implode(',', $arr) : implode('', $arr)
        );
        $this->bacAbstractOfCanvassRepository->update($rfqID, $details);
        return response()->json([
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_committee(Request $request, $rfqID, $id)
    {
        $this->is_permitted($this->slugs, 'delete');
        $committeex = explode(',', $this->bacAbstractOfCanvassRepository->find_abstract($rfqID)->committees);
        if (($key = array_search($id, $committeex)) !== false) {
            unset($committeex[$key]);
        }
        $details = array(
            'committees' => (count($committeex) > 1) ? implode(',', $committeex) : implode('', $committeex)
        );
        $this->bacAbstractOfCanvassRepository->update($rfqID, $details);
        return response()->json([
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully deleted.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function award(Request $request, $rfqID, $supplierID)
    {
        $this->is_permitted($this->slugs, 'update');
        $details = array(
            'is_selected' => 1,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $details2 = array(
            'status' => 'processed',
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        return response()->json([
            'data' => $this->bacResolutionRepository->update_award($rfqID, $supplierID, $details),
            'data' => $this->bacAbstractOfCanvassRepository->update($rfqID, $details2),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
