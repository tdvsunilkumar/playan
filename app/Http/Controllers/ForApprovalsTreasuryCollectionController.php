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
use App\Interfaces\CtoCollectionInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsTreasuryCollectionController extends Controller
{   
    private CtoCollectionInterface $ctoCollectionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CtoCollectionInterface $ctoCollectionRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->ctoCollectionRepository = $ctoCollectionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/departmental-requisition';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $fund_codes = $this->ctoCollectionRepository->allFundCodes();
        $officers = $this->ctoCollectionRepository->allCtoOfficers();
        return view('for-approvals.treasury.collections.index')->with(compact('permission', 'fund_codes', 'officers'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'disapproved'],
        ];

        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        
        $result = $this->ctoCollectionRepository->approvals_listItems($request, 'modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($collection) use ($statusClass, $actions, $actions2) {
            if ($collection->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($collection->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($collection->disapproved_at));
            } else if($collection->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($collection->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($collection->approved_at));
            } else {
                $approvedBy = '';
            }
            $officer = $collection->officer ? wordwrap($collection->officer->name, 25, "\n") : ''; 

            return [
                'id' => $collection->id,
                'sequence' => $collection->approved_counter,
                'transaction_no' => $collection->trans_no,
                'transaction_label' => '<strong class="text-primary">'.$collection->trans_no.'</strong>',
                'transaction_date' => date('d-M-Y', strtotime($collection->trans_date)),
                'officer' => '<div class="showLess" title="' . ($collection->officer ? $collection->officer->name : '') . '">' . $officer . '</div>',
                'total_amount' => $this->money_format($collection->total_amount),
                'modified' => ($collection->updated_at !== NULL) ? date('d-M-Y', strtotime($collection->updated_at)).'<br/>'. date('h:i A', strtotime($collection->updated_at)) : date('d-M-Y', strtotime($collection->created_at)).'<br/>'. date('h:i A', strtotime($collection->created_at)),
                'status' => $statusClass[$collection->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$collection->status]->bg. ' p-2">' . $statusClass[$collection->status]->status . '</span>' ,
                'approved_by' => $approvedBy,
                'actions' => ($collection->status == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function transaction_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],   
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->transaction_listItems($request, $id);
        $res = $result->data->map(function($trans) use ($statusClass, $actions) {
            $taxpayer = $trans->taxpayer_name ? wordwrap($trans->taxpayer_name, 25, "\n") : ''; 
            $form_code = $trans->form_code ? wordwrap($trans->form_code, 25, "\n") : ''; 
            $sl_account = $trans->sl_account ? wordwrap($trans->sl_account, 25, "\n") : ''; 
            return [
                'id' => $trans->identity,
                'trans_date' => $trans->cashier_or_date,
                'or_no' => $trans->or_no,
                'or_label' => '<strong class="text-primary">'. $trans->or_no .'</strong>',
                'taxpayer' => '<div class="showLess" title="' . ($trans->taxpayer_name ? $trans->taxpayer_name : '') . '">' . $taxpayer . '</div>',
                'form_code' => '<div class="showLess" title="' . ($trans->form_code ? $trans->form_code : '') . '">' . $form_code . '</div>',
                'sl_account' => '<div class="showLess" title="' . ($trans->sl_account ? $trans->sl_account : '') . '">' . $sl_account . '</div>',
                'credit' => ($trans->is_discount > 0) ? '<span class="text-danger">('.$this->money_formats($trans->amount).')</span>' : $this->money_formats($trans->amount),
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
            'collections' => $result->collections
        ]);
    }

    public function receipt_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],   
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->receipt_listItems($request, $id);
        $res = $result->data->map(function($receipt) use ($statusClass, $actions) {
            return [
                'id' => $receipt->id,
                'form_no' => $receipt->form_code,
                'or_dept' => $receipt->or_dept,
                'or_from' => $receipt->or_from,
                'or_to' => $receipt->or_to,
                'total_amount' => $this->money_format(floatval($receipt->total_amount) - floatval($receipt->total_discount)),
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
            'total_amount' => $this->money_formatx($result->total)
        ]);
    }

    public function get_denominations(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->ctoCollectionRepository->get_denominations($id),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully fetched.',
            'type' => 'success',
        ]);
    }

    public function money_formatx($money)
    {
        return floor(($money*100))/100;
    }
    
    public function money_formats($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->ctoCollectionRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function fetch_remarks(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->ctoCollectionRepository->find($id)->disapproved_remarks
        ]);
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->ctoCollectionRepository->validate_approver(
            $this->ctoCollectionRepository->find($id)->officer->hr_employee->acctg_department_id, 
            $sequence, 
            'modules', 
            $this->slugs, 
            Auth::user()->id
        );
    }

    public function approve(Request $request, $collectionID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $counter = $this->ctoCollectionRepository->find_levels($this->slugs, 'modules');

            $collection = $this->ctoCollectionRepository->find($collectionID);
            if ($collection->approved_by == NULL) {
                $approvers = array();
                $approvers[] = Auth::user()->id;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'posted' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                );
                $itemDetails = array(
                    'status' => (count($approvers) == $counter) ? 'posted' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers)
                );
            } else {
                $approvers = explode(',', $res->approved_by);
                $approvers[] = Auth::user()->id;
                $details = array(
                    'status' => (count($approvers) == $counter) ? 'posted' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers),
                    'approved_counter' => count($approvers) + 1,
                );
                $itemDetails = array(
                    'status' => (count($approvers) == $counter) ? 'posted' : 'for approval',
                    'approved_at' => $timestamp,
                    'approved_by' => (count($approvers) == 1) ? implode('', $approvers) : implode(',', $approvers)
                );
            }       
            if (count($approvers) == $counter) {     
                $this->ctoCollectionRepository->update($collectionID, $details);
                $this->ctoCollectionRepository->generate_voucher($collection, $timestamp, Auth::user()->id);
            }
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $collectionID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->remarks
            );
            return response()->json([
                'data' => $this->ctoCollectionRepository->update($collectionID, $details),
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function reload_designation(Request $request, $employee) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->ctoCollectionRepository->reload_designation($employee)
        ]);
    }

    public function reload_divisions_employees(Request $request, $department) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'employees' => $this->ctoCollectionRepository->reload_employees($department),
            'divisions' => $this->ctoCollectionRepository->reload_divisions($department)
        ]);
    }

    public function fetch_status(Request $request, $collectionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->ctoCollectionRepository->find($collectionID)->status
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
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn delete-btn bg-danger btn ms-05 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->ctoCollectionRepository->listItemLines($request, $id);
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

    public function view(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->ctoCollectionRepository->get($id)->map(function($trans) {
                return (object) [
                    'fund_code_id' => $trans->fund_code_id,
                    'officer_id' => $trans->officer_id,
                    'transaction_date' => $trans->trans_date,
                    'transaction_no' => $trans->trans_no,
                    'status' => $trans->status,
                    'total_amount' => $trans->total_amount
                ];
            })
        ]);
    }
}
