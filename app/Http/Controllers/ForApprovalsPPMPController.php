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
use App\Interfaces\GsoPPMPInterface;
use App\Interfaces\AcctgAccountPayableInterface;
use App\Interfaces\AcctgAccountDisbursementInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPPMPController extends Controller
{   
    private GsoPPMPInterface $gsoPPMPRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        GsoPPMPInterface $gsoPPMPRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->gsoPPMPRepository = $gsoPPMPRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/ppmp';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $departments = $this->gsoPPMPRepository->allDepartmentsWithRestriction(Auth::user()->id);
        $fund_codes = $this->gsoPPMPRepository->allFundCodes();
        return view('for-approvals.project-procurement-management-plan.index')->with(compact('departments', 'fund_codes'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'locked' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'disapproved'],
        ];

        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->gsoPPMPRepository->approvals_listItems($request, 'modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($ppmp) use ($statusClass, $actions, $actions2) {    
            $department = $ppmp->department ? wordwrap($ppmp->department->code.' - '.$ppmp->department->name, 25, "\n") : '';             
            $remarks = wordwrap($ppmp->identityRemarks, 25, "\n");            
            if ($ppmp->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($ppmp->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($ppmp->disapproved_at));
            } else if($ppmp->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($ppmp->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($ppmp->approved_at));
            } else {
                $approvedBy = '';
            }  
            return [
                'id' => $ppmp->identity,
                'sequence' => $ppmp->approved_counter,
                'budget_year' => $ppmp->budget_year,
                'control_no' => $ppmp->control_no,
                'control_no_label' => '<a class="voucher-link" href="javascript:;" link="'.url('/general-services/project-procurement-management-plan/view/'.$ppmp->identity).'"><strong class="text-primary">' . $ppmp->control_no . '</strong></a>',
                // 'control_no_label' => '<strong class="text-primary">'.$ppmp->control_no.'</strong>',
                'department' => '<div class="showLess" title="' . ($ppmp->department ? $ppmp->department->code.' - '.$ppmp->department->name : '') . '">' . $department . '</div>',
                'remarks' => '<div class="showLess" title="' . $ppmp->identityRemarks . '">' . $remarks . '</div>',
                'total' => $this->money_format($ppmp->total_amount),
                'approved_by' => $approvedBy,
                'modified' => ($ppmp->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($ppmp->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($ppmp->identityUpdatedAt)) : date('d-M-Y', strtotime($ppmp->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($ppmp->identityCreatedAt)),
                'status' => $statusClass[$ppmp->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$ppmp->identityStatus]->bg. ' p-2">' . $statusClass[$ppmp->identityStatus]->status . '</span>' ,
                'actions' => ($ppmp->identityStatus == 'cancelled') ? $actions2 : $actions
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

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->gsoPPMPRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->gsoPPMPRepository->validate_approver($this->gsoPPMPRepository->find($id)->department_id, $sequence, 'modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $ppmpID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->gsoPPMPRepository->find($ppmpID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->gsoPPMPRepository->find($ppmpID)->approved_by);
            }
            $approvers[] = Auth::user()->id;

            $counter = $this->gsoPPMPRepository->find_levels($this->slugs, 'modules');
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => (count($approvers) == $counter) ? 'locked' : 'for approval',
                'approved_at' => $timestamp,
                'approved_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers),
                'approved_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->gsoPPMPRepository->update($ppmpID, $details);

            return response()->json([
                'text' => 'The ppmp has been successfully approved.',
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

    public function disapprove(Request $request, $ppmpID)
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
            $this->gsoPPMPRepository->disapprove($ppmpID, $details);
            return response()->json([
                'text' => 'The ppmp has been successfully disapproved.',
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

    public function fetch_status(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->gsoPPMPRepository->find($ppmpID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $ppmpID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->gsoPPMPRepository->find($ppmpID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->gsoPPMPRepository->find($id)
        ]);
    }
}
