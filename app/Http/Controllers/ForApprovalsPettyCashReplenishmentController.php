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
use App\Interfaces\AcctgAccountVoucherInterface;
use App\Interfaces\CtoReplenishInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPettyCashReplenishmentController extends Controller
{   
    private CtoReplenishInterface $treasuryReplenishRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CtoReplenishInterface $treasuryReplenishRepository,
        CboBudgetAllocationInterface $cboBudgetAllocationRepository,
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->treasuryReplenishRepository = $treasuryReplenishRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/petty-cash/replenishment';
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
        $vouchers = $this->treasuryReplenishRepository->allPettyVouchers();
        return view('for-approvals.petty-cash.replenishment.index')->with(compact('permission', 'vouchers'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'disapproved'],
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
        $result = $this->treasuryReplenishRepository->approvals_listItems($request, 'sub_modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($replenish) use ($statusClass, $actions, $actions2) {    
            if ($replenish->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($replenish->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($replenish->disapproved_at));
            } else if($replenish->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($replenish->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($replenish->approved_at));
            } else {
                $approvedBy = '';
            }  
            $particulars = $replenish->particulars ? wordwrap($replenish->particulars, 25, "\n") : '';
            $department = $replenish->department ? wordwrap($replenish->department->name, 25, "\n") : '';
            return [
                'checkbox' => 
                    (($replenish->status == 'for approval') && ($this->validate_request($replenish->department_id, $replenish->approved_counter, Auth::user()->id) > 0)) ? 
                    '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$replenish->id.'"></div>' : 
                    '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$replenish->id.'" disabled="disabled"></div>',
                'id' => $replenish->id,
                'sequence' => $replenish->approved_counter,
                'control_no' => $replenish->control_no,
                'control_no_label' => '<strong class="text-primary">'. $replenish->control_no .'</strong>',
                'department' => '<div class="showLess" title="'.($replenish->department ? $replenish->department->name : '').'">' . $department . '</div>',
                'particulars' => '<div class="showLess" title="'.($replenish->particulars ? $replenish->particulars : '').'">' . $particulars . '</div>',     
                'total' => $replenish->total_amount,
                'total_label' => $this->money_format($replenish->total_amount),
                'modified' => ($replenish->updated_at !== NULL) ? date('d-M-Y', strtotime($replenish->updated_at)).'<br/>'. date('h:i A', strtotime($replenish->updated_at)) : date('d-M-Y', strtotime($replenish->created_at)).'<br/>'. date('h:i A', strtotime($replenish->created_at)),
                'status' => $statusClass[$replenish->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$replenish->status]->bg. ' p-2">' . $statusClass[$replenish->status]->status . '</span>' ,
                'actions' => ($replenish->status == 'cancelled') ? $actions2 : $actions,
                'approved_by' => $approvedBy
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function line_lists(Request $request, $id)
    {       
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'disbursed' => (object) ['bg' => 'prepared-bg', 'status' => 'disbursed'],
            'replenished' => (object) ['bg' => 'completed-bg', 'status' => 'replenished'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $result = $this->treasuryReplenishRepository->line_listItems($request, $id);
        $res = $result->data->map(function($replenishLine) use ($actions, $statusClass) {
            return [
                'id' => $replenishLine->id,
                'control_no' => $replenishLine->disburse->control_no,
                'control_no_label' => '<strong class="text-primary">'. $replenishLine->disburse->control_no .'</strong>',
                'total' => $this->money_format($replenishLine->disburse->total_amount),
                'modified' => ($replenishLine->updated_at !== NULL) ? date('d-M-Y', strtotime($replenishLine->updated_at)).'<br/>'. date('h:i A', strtotime($replenishLine->updated_at)) : date('d-M-Y', strtotime($replenishLine->created_at)).'<br/>'. date('h:i A', strtotime($replenishLine->created_at)),
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

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->treasuryReplenishRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_request($departmentID, $sequence, $user)
    {
        return $this->treasuryReplenishRepository->validate_approver($departmentID, $sequence, 'sub_modules', $this->slugs, $user);
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->treasuryReplenishRepository->validate_approver($this->treasuryReplenishRepository->find($id)->department_id, $sequence, 'sub_modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $replenishID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->treasuryReplenishRepository->find($replenishID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->treasuryReplenishRepository->find($replenishID)->approved_by);
            }
            $approvers[] = Auth::user()->id;

            $counter = $this->treasuryReplenishRepository->find_levels($this->slugs, 'sub_modules');
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => (count($approvers) == $counter) ? 'completed' : 'for approval',
                'approved_at' => $timestamp,
                'approved_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers),
                'approved_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
            );
            $this->treasuryReplenishRepository->update($replenishID, $details);
            if (count($approvers) == $counter) {
                $details2 = array(
                    'replenishment_date' => date('Y-m-d', strtotime($timestamp)),
                    'is_replenished' => 1
                );
                $this->treasuryReplenishRepository->replenish($replenishID, $timestamp, Auth::user()->id, $details2);
            }

            return response()->json([
                'text' => 'The petty cash disbursement has been successfully approved.',
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

    public function disapprove(Request $request, $replenishID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($this->treasuryReplenishRepository->find($replenishID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->treasuryReplenishRepository->find($replenishID)->approved_by);
            }
            $approvers[] = Auth::user()->id;
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => urldecode($request->get('remarks')),
                'approved_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->treasuryReplenishRepository->update($replenishID, $details);
            return response()->json([
                'text' => 'The petty cash disbursement has been successfully disapproved.',
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

    public function fetch_status(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->treasuryReplenishRepository->find($replenishID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->treasuryReplenishRepository->find($replenishID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->treasuryReplenishRepository->find($id),
            'total' => $this->treasuryReplenishRepository->computeTotalAmount($id)
        ]);
    }
}
