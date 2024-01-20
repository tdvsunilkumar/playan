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
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsRepairsRequestController extends Controller
{   
    private GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository;
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPreRepairInspectionInterface $gsoPreRepairInspectionRepository, 
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPreRepairInspectionRepository = $gsoPreRepairInspectionRepository;
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/repairs-and-inspections/requests';
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
        return view('for-approvals.repairs-and-inspections.requests.index')->with(compact('permission', 'fixed_assets', 'employees'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'requested' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'for inspection approval' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'disapproved'],
        ];

        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-search text-white"></i></a><br/>';
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoPreRepairInspectionRepository->approvals_listItems($request, 'sub_modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($repair) use ($statusClass, $actions, $actions2) {    
            $requested_by = $repair->employee ? wordwrap($repair->employee->fullname, 25, "\n") : ''; 
            $issues = $repair->issues ?  wordwrap($repair->issues, 25, "\n") : '';
            if ($repair->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($repair->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($repair->disapproved_at));
            } else if($repair->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($repair->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($repair->approved_at));
            } else {
                $approvedBy = '';
            }       
            return [
                'id' => $repair->id,
                'sequence' => $repair->approved_counter,
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
        return $this->gsoPreRepairInspectionRepository->validate_approver($this->gsoPreRepairInspectionRepository->find($id)->employee->acctg_department_id, $sequence, 'sub_modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $repairID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->gsoPreRepairInspectionRepository->find($repairID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->gsoPreRepairInspectionRepository->find($repairID)->approved_by);
            }
            $approvers[] = Auth::user()->id;

            $counter = $this->gsoPreRepairInspectionRepository->find_levels($this->slugs, 'sub_modules');
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => (count($approvers) == $counter) ? 'requested' : 'for approval',
                'approved_at' => $timestamp,
                'approved_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers),
                'approved_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoPreRepairInspectionRepository->update($repairID, $details);

            return response()->json([
                'text' => 'The request for repair has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
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
}
