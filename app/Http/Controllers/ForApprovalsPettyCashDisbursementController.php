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
use App\Interfaces\CtoDisburseInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPettyCashDisbursementController extends Controller
{   
    private CtoDisburseInterface $treasuryDisburseRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        CtoDisburseInterface $treasuryDisburseRepository,
        CboBudgetAllocationInterface $cboBudgetAllocationRepository,
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->treasuryDisburseRepository = $treasuryDisburseRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/petty-cash/disbursement';
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
        $vouchers = $this->treasuryDisburseRepository->allPettyVouchers();
        $departments = $this->treasuryDisburseRepository->allDepartments();
        return view('for-approvals.petty-cash.disbursement.index')->with(compact('permission', 'vouchers', 'departments'));
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
        $result = $this->treasuryDisburseRepository->approvals_listItems($request, 'sub_modules', $this->slugs, Auth::user()->id);
        $res = $result->data->map(function($disburse) use ($statusClass, $actions, $actions2) {    
            if ($disburse->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($disburse->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($disburse->disapproved_at));
            } else if($disburse->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($disburse->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($disburse->approved_at));
            } else {
                $approvedBy = '';
            }  
            $payee = $disburse->payee ? wordwrap($disburse->payee->paye_name, 25, "\n") : '';
            $particulars = $disburse->particulars ? wordwrap($disburse->particulars, 25, "\n") : '';
            $department = $disburse->department ? wordwrap($disburse->department->name, 25, "\n") : '';
            return [
                'checkbox' => 
                    (($disburse->status == 'for approval') && ($this->validate_request($disburse->department_id, $disburse->approved_counter, Auth::user()->id) > 0)) ? 
                    '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$disburse->identity.'"></div>' : 
                    '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$disburse->identity.'" disabled="disabled"></div>',
                'id' => $disburse->id,
                'sequence' => $disburse->approved_counter,
                'voucher' => ($disburse->voucher ? $disburse->voucher->voucher_no : ''),
                'voucher_label' => '<strong>'.($disburse->voucher ? $disburse->voucher->voucher_no : '').'</strong>',
                'control_no' => $disburse->control_no,
                'control_no_label' => '<strong class="text-primary">'. $disburse->control_no .'</strong>',
                'particulars' => '<div class="showLess" title="'.($disburse->particulars ? $disburse->particulars : '').'">' . $particulars . '</div>',
                'payee' => '<div class="showLess" title="'.($disburse->payee ? $disburse->payee->paye_name : '').'">' . $payee . '</div>',              
                'department' => '<div class="showLess" title="'.($disburse->department ? $disburse->department->name : '').'">' . $department . '</div>',              
                'total' => $disburse->total_amount,
                'total_label' => $this->money_format($disburse->total_amount),
                'modified' => ($disburse->updated_at !== NULL) ? date('d-M-Y', strtotime($disburse->updated_at)).'<br/>'. date('h:i A', strtotime($disburse->updated_at)) : date('d-M-Y', strtotime($disburse->created_at)).'<br/>'. date('h:i A', strtotime($disburse->created_at)),
                'status' => $statusClass[$disburse->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$disburse->status]->bg. ' p-2">' . $statusClass[$disburse->status]->status . '</span>' ,
                'actions' => ($disburse->status == 'cancelled') ? $actions2 : $actions,
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
        $result = $this->treasuryDisburseRepository->line_listItems($request, $id);
        $res = $result->data->map(function($disburseLine) use ($actions, $statusClass) {
            return [
                'id' => $disburseLine->id,
                'alob_no' => $disburseLine->obligation->alobs_control_no,
                'alob_no_label' => '<strong class="text-primary">'. $disburseLine->obligation->alobs_control_no .'</strong>',
                'total' => $this->money_format($disburseLine->obligation->total_amount),
                'modified' => ($disburseLine->updated_at !== NULL) ? date('d-M-Y', strtotime($disburseLine->updated_at)).'<br/>'. date('h:i A', strtotime($disburseLine->updated_at)) : date('d-M-Y', strtotime($disburseLine->created_at)).'<br/>'. date('h:i A', strtotime($disburseLine->created_at)),
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
            return $this->treasuryDisburseRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_request($departmentID, $sequence, $user)
    {
        return $this->treasuryDisburseRepository->validate_approver($departmentID, $sequence, 'sub_modules', $this->slugs, $user);
    }

    public function validate_approver(Request $request, $id, $sequence)
    {
        return $this->treasuryDisburseRepository->validate_approver($this->treasuryDisburseRepository->find($id)->department_id, $sequence, 'sub_modules', $this->slugs, Auth::user()->id);
    }

    public function approve(Request $request, $disburseID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            if ($this->treasuryDisburseRepository->find($disburseID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->treasuryDisburseRepository->find($disburseID)->approved_by);
            }
            $approvers[] = Auth::user()->id;

            $counter = $this->treasuryDisburseRepository->find_levels($this->slugs, 'sub_modules');
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => (count($approvers) == $counter) ? 'completed' : 'for approval',
                'approved_at' => $timestamp,
                'approved_by' => (count($approvers) == 1) ? implode('',$approvers) : implode(',', $approvers),
                'disbursement_date' => (count($approvers) == $counter) ? date('Y-m-d', strtotime($timestamp)) : NULL,
                'is_disbursed' => (count($approvers) == $counter) ? 1 : 0,
                'approved_counter' => count($approvers) + 1,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
            );
            $this->treasuryDisburseRepository->update($disburseID, $details);
            if (count($approvers) == $counter) {
                $this->treasuryDisburseRepository->disburse($disburseID, $timestamp, Auth::user()->id);
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

    public function disapprove(Request $request, $disburseID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($this->treasuryDisburseRepository->find($disburseID)->approved_by == NULL) {
                $approvers = array();
            } else {
                $approvers = explode(',',$this->treasuryDisburseRepository->find($disburseID)->approved_by);
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
            $this->treasuryDisburseRepository->update($disburseID, $details);
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

    public function fetch_status(Request $request, $disburseID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->treasuryDisburseRepository->find($disburseID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $disburseID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->treasuryDisburseRepository->find($disburseID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->treasuryDisburseRepository->find($id),
            'total' => $this->treasuryDisburseRepository->computeTotalAmount($id)
        ]);
    }
}
