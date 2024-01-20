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
use App\Interfaces\GsoPreRepairInspectionInterface;
use App\Interfaces\AcctgFixedAssetInterface;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsRepairsInspectionController extends Controller
{   
    private GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository;
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository, 
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        GsoDepartmentalRequisitionRepositoryInterface $gsoDepartmentalRequisitionRepository, 
        CboBudgetAllocationInterface $cboBudgetAllocationRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPreRepairInspectionRepository = $gsoPreRepairInspectionRepository;
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->gsoDepartmentalRequisitionRepository = $gsoDepartmentalRequisitionRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/repairs-and-inspections/inspections';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = (object) array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $fixed_assets = $this->gsoPreRepairInspectionRepository->allFixedAssets();
        $employees = $this->gsoPreRepairInspectionRepository->allEmployees();
        $items = $this->gsoPreRepairInspectionRepository->allItems();
        $uoms = $this->gsoPreRepairInspectionRepository->allUOMs();
        return view('for-approvals.repairs-and-inspections.inspections.index')->with(compact('permission', 'fixed_assets', 'employees', 'items', 'uoms'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for inspection approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'disapproved'],
        ];

        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-search text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->approvals2_listItems($request, 'sub_modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($repair) use ($statusClass, $actions, $actions2) {    
            $requested_by = $repair->employee ? wordwrap($repair->employee->fullname, 25, "\n") : ''; 
            $issues = $repair->issues ?  wordwrap($repair->issues, 25, "\n") : '';
            if ($repair->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($repair->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($repair->disapproved_at));
            } else if($repair->checked_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($repair->checked_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($repair->checked_at));
            } else {
                $approvedBy = '';
            }       
            return [
                'id' => $repair->id,
                'sequence' => $repair->checked_counter,
                'repair_no' => $repair->repair_no,
                'repair_no_label' => '<strong class="text-primary">' . $repair->repair_no . '</strong>',
                'fa_no' => $repair->property ? $repair->property->fixed_asset_no : '',
                'fa_no_label' => '<strong>' . ($repair->property ? $repair->property->fixed_asset_no : '') . '</strong>',
                'requested_by' => '<div class="showLess" title="' . ($repair->employee ? $repair->employee->fullname : '') . '">' . $requested_by . '</div>',
                'requested_date' => date('d-M-Y', strtotime($repair->requested_date)),
                'issues' => '<div class="showLess" title="' . ($repair->issues ? $repair->issues : '') . '">' . $issues . '</div>',
                'approved_by' => $approvedBy,
                'modified' => ($repair->updated_at !== NULL) ? date('d-M-Y', strtotime($repair->updated_at)).'<br/>'. date('h:i A', strtotime($repair->updated_at)) : date('d-M-Y', strtotime($repair->created_at)).'<br/>'. date('h:i A', strtotime($repair->created_at)),
                'status' => $statusClass[$repair->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$repair->status]->bg. ' p-2">' . $statusClass[$repair->status]->status . '</span>' ,
                'actions' => ($repair->status == 'cancelled') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function view(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($id)
        ]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoPreRepairInspectionRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->gsoPreRepairInspectionRepository->validate_approver(
            $this->gsoPreRepairInspectionRepository->find($id)->employee->acctg_department_id, $sequence, 'sub_modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $repairID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->gsoPreRepairInspectionRepository->find($repairID)->checked_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->gsoPreRepairInspectionRepository->find($repairID)->checked_by);
            }
            $approvers[] = Auth::user()->id;

            $counter = $this->gsoPreRepairInspectionRepository->find_levels($this->slugs, 'sub_modules');
            $timestamp = $this->carbon::now();
            $details = array(
                'is_checked' => (count($approvers) == $counter) ? 1 : 0,
                'status' => (count($approvers) == $counter) ? 'completed' : 'for inspection approval',
                'checked_at' => $timestamp,
                'checked_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers),
                'checked_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoPreRepairInspectionRepository->update($repairID, $details);
            
            $validate = $this->gsoPreRepairInspectionRepository->validate($repairID);
            if ($validate->count() > 0 && (count($approvers) == $counter)) {
                $this->generate_request($repairID, $validate, $timestamp, Auth::user()->id);
            }

            return response()->json([
                'text' => 'The request for repair has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function generate_request($repairID, $items, $timestamp, $user)
    {   
        $timestamp = $this->carbon::now();
        $repairs = $this->gsoPreRepairInspectionRepository->find($repairID);

        $control_no = $this->gsoDepartmentalRequisitionRepository->generate_control_no($repairs->employee->acctg_department_id);
        $details = array(
            'department_id' => $repairs->employee->acctg_department_id,
            'division_id' => $repairs->employee->acctg_department_division_id,
            'employee_id' => $repairs->employee->id,
            'designation_id' => $repairs->employee->hr_designation_id,
            'request_type_id' => 3,
            'fund_code_id' => $repairs->property->fund_code_id,
            'control_no' => $control_no,
            'requested_date' => $repairs->requested_date,
            'remarks' => $repairs->repair_no,
            'status' => 'requested',
            'sent_at' => $timestamp,
            'sent_by' => $user,
            'approved_at' => $timestamp,
            'approved_by' => $repairs->approved_by,
            'created_at' => $timestamp,
            'created_by' => $user
        );
        $requisition = $this->gsoDepartmentalRequisitionRepository->create($details);

        $totalAmt = 0;
        foreach ($items as $item) {
            $item_details = array(
                'departmental_request_id' => $requisition->id,
                'gl_account_id' => $item->item->gl_account_id,
                'item_id' => $item->item_id,
                'uom_id' => $item->uom_id,
                'remarks' => $item->remarks,
                'quantity_requested' => $item->quantity,
                'request_unit_price' => $item->amount,
                'request_total_price' => $item->total_amount,
                'created_at' => $timestamp,
                'created_by' => $user
            );
            $this->gsoDepartmentalRequisitionRepository->createItem($item_details);
            if (floatval($item->total_amount) > 0) {
                $totalAmt += floatval($item->total_amount);
            }
        }
        $this->gsoDepartmentalRequisitionRepository->update($requisition->id, ['total_amount' => $totalAmt]);
        
        $allotmentDetail = array(
            'obligation_type_id' => 1,
            'budget_control_no' => $this->cboBudgetAllocationRepository->generateBudgetControlNo(date('Y', strtotime($requisition->requested_date))),
            'departmental_request_id' => $requisition->id,
            'department_id' => $requisition->department_id,
            'division_id' => $requisition->division_id,
            'fund_code_id' => $requisition->fund_code_id,
            'employee_id' => $requisition->employee_id,
            'designation_id' => $requisition->designation_id,
            'with_pr' => 1,
            'budget_year' => date('Y', strtotime($requisition->requested_date)),
            'created_at' => $timestamp,
            'created_by' => $user
        );
        $allotment = $this->cboBudgetAllocationRepository->create($allotmentDetail);
        $allotmentRequestDetail = array(
            'allotment_id' => $allotment->id,
            'status' => 'completed',                    
            'sent_at' => $timestamp,
            'sent_by' => $user,
        );
        $allotmentRequest = $this->cboBudgetAllocationRepository->create_request($allotmentRequestDetail);

        return true;
    }

    // public function approve_all(Request $request)
    // {   
    //     if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
    //         $timestamp = $this->carbon::now();
    //         $details = array(
    //             'status' => 'posted',
    //             'approved_at' => $timestamp,
    //             'approved_by' => Auth::user()->id,
    //             'updated_at' => $this->carbon::now(),
    //             'updated_by' => Auth::user()->id
    //         );
    //         $res = $this->acctgAccountDisbursementRepository->approve_all($request, $details);
    //         return response()->json([
    //             'title' => 'Well done!',
    //             'text' => 'The payments has been successfully approved.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
    // }

    public function disapprove(Request $request, $repairID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->get('remarks'),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoPreRepairInspectionRepository->update($repairID, $details);
            return response()->json([
                'text' => 'The request for repair has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    // public function disapprove_all(Request $request)
    // {   
    //     if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
    //         $timestamp = $this->carbon::now();
    //         $details = array(
    //             'status' => 'cancelled',
    //             'disapproved_at' => $timestamp,
    //             'disapproved_by' => Auth::user()->id,
    //             'disapproved_remarks' => $request->get('remarks'),
    //             'updated_at' => $timestamp,
    //             'updated_by' => Auth::user()->id
    //         );
    //         $res = $this->acctgAccountDisbursementRepository->disapprove_all($request, $details);
    //         return response()->json([
    //             'title' => 'Well done!',
    //             'text' => 'The payments has been successfully disapproved.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
    // }

    public function fetch_status(Request $request, $repairID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPreRepairInspectionRepository->find($repairID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $repairID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoPreRepairInspectionRepository->find($repairID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->find($id)
        ]);
    }

    public function preload_fixed_asset($id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgFixedAssetRepository->get($id)->map(function($fixedAsset) {
                return (object) [
                    'requested_by' => $fixedAsset->received_by,
                    'model' => $fixedAsset->model,
                    'engine_no' => $fixedAsset->engine_no,
                    'plate_no' => $fixedAsset->plate_no,
                    'received_date' => $fixedAsset->received_date,
                    'unit_cost' => $fixedAsset->unit_cost,
                    'type' => $fixedAsset->type->code.' - '.$fixedAsset->type->name
                ];
            })
        ]);
    }

    public function history_lists(Request $request, $id) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->history_listItems($request, $id, $request->get('fixed_asset'));
        $res = $result->data->map(function($history) use ($statusClass, $actions) {
            $concerns = $history->concerns ? wordwrap($history->concerns, 25, "\n") : ''; 
            $remarks = $history->remarks ?  wordwrap($history->remarks, 25, "\n") : ''; 
            return [
                'id' => $history->id,
                'date_requested' => date('d-M-Y', strtotime($history->requested_date)),
                'concerns' => '<div class="showLess" title="' . ($history->concerns ? $history->concerns : '') . '">' . $concerns . '</div>',
                'date_accomplished' => date('d-M-Y', strtotime($history->completion_date)),
                'remarks' => '<div class="showLess" title="' . ($history->remarks ? $history->remarks : '') . '">' . $remarks . '</div>',
                'modified' => ($history->updated_at !== NULL) ? date('d-M-Y', strtotime($history->updated_at)).'<br/>'. date('h:i A', strtotime($history->updated_at)) : date('d-M-Y', strtotime($history->created_at)).'<br/>'. date('h:i A', strtotime($history->created_at)),
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
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'requested-bg', 'status' => 'requested'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 = '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->item_listItems($request, $id, $actions);
        $res = $result->data->map(function($item) use ($statusClass, $actions, $actions2) {
            $items = $item->item ? wordwrap($item->item->code.'-'.$item->item->name, 25, "\n") : ''; 
            $remarks = $item->remarks ?  wordwrap($item->remarks, 25, "\n") : ''; 
            return [
                'id' => $item->id,
                'code' => $item->item ? $item->item->code : '',
                'uom' => $item->uom ? ($item->quantity > 1 ? $item->uom->codex : $item->uom->code) : '',
                'item' => '<div class="showLess" title="' . ($item->item ? $item->item->code.' - '.$item->item->name : '') . '">' . $items . '</div>',
                'remarks' => '<div class="showLess" title="' . ($item->remarks ? $item->remarks : '') . '">' . $remarks . '</div>',
                'quantity' => $item->quantity ? $item->quantity : '',
                'amount' => $item->amount ? $this->money_format($item->amount) : '',
                'total' => $item->total_amount ? $this->money_format($item->total_amount) : '',
                'modified' => ($item->updated_at !== NULL) ? date('d-M-Y', strtotime($item->updated_at)).'<br/>'. date('h:i A', strtotime($item->updated_at)) : date('d-M-Y', strtotime($item->created_at)).'<br/>'. date('h:i A', strtotime($item->created_at)),
                'actions' => ($item->request->status == 'requested') ? $actions : $actions2
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function find_item(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->gsoPreRepairInspectionRepository->getItem($id)->map(function($item) {
                return (object) [
                    'item_id' => $item->item_id,
                    'uom_id' => $item->uom_id,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->amount,
                    'total_cost' => $item->total_amount,
                    'remarks' => $item->remarks,
                    'request' => $item->request
                ];
            })
        ]);
    }
}
