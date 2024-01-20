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
use App\Interfaces\AcctgAccountPayableInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsAccountPayableController extends Controller
{   
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private AcctgAccountPayableInterface $acctgAccountPayableRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository, 
        AcctgAccountPayableInterface $acctgAccountPayableRepository, 
        Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->acctgAccountPayableRepository = $acctgAccountPayableRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/account-payables';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Purchase Order' => 'Purchase Order', 'Operational Expenses' => 'Operational Expenses', 'Payroll' => 'Payroll'];
        return view('for-approvals.account-payable.index')->with(compact('gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
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
        $result = $this->acctgAccountVoucherRepository->approvals_payables_listItems($request);
        $res = $result->data->map(function($payable) use ($statusClass, $actions, $actions2) {
            $gl_account = $payable->gl_account ? wordwrap($payable->gl_account->code . ' - ' . $payable->gl_account->description, 25, "\n") : ''; 
            $items = $payable->items ? wordwrap($payable->items, 25, "\n") : '';                   
            if ($payable->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($payable->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($payable->disapproved_at));
            } else if($payable->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($payable->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($payable->approved_at));
            } else {
                $approvedBy = '';
            }
            $vat = '';
            $vat .= $payable->vat_type.'<br/>';
            $vat .= $payable->ewt ? '<strong>'.$payable->ewt->code.'</strong>' : '';
            $vat .= ' / ';
            $vat .= $payable->evat ? '<strong>'.$payable->evat->code.'</strong>' : '';
            $vat .= '<br/>';
            $vat .= $payable->ewt ? '('. $payable->ewt->percentage .')' : '';
            $vat .= ' / ';
            $vat .= $payable->evat ? '('. $payable->evat->percentage .')' : '';

            return [
                'id' => $payable->identity,
                'checkbox' => ($payable->identityStatus == 'for approval') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'" disabled="disabled"></div>',
                'trans_type' => $payable->trans_type,
                'transaction_label' => '<strong class="text-primary">'.$payable->trans_no.'</strong><br/>'.$payable->trans_type,
                'voucher' => $payable->voucher ? $payable->voucher->voucher_no : '',
                'voucher_label' => '<a class="voucher-link" href="javascript:;" link="'.url('/accounting/journal-entries/edit/'.$payable->voucher->id).'"><strong class="text-primary">' . ($payable->voucher ? $payable->voucher->voucher_no : '') . '</strong></a>',
                'gl_account' => $payable->gl_account ? $payable->gl_account->code . ' - ' . $payable->gl_account->description : '',
                'gl_account_label' => '<div class="showLess" title="' . $payable->gl_account->code . ' - ' . $payable->gl_account->description . '">' . $gl_account . '</div>',
                'vat' => $vat,
                'ewt' => $payable->ewt ? '<strong>'.$payable->ewt->code.'</strong><br/>('. $payable->ewt->percentage .')' : '',
                'evat' => $payable->evat ? '<strong>'.$payable->evat->code.'</strong><br/>('. $payable->evat->percentage .')' : '',
                'items' => '<div class="showLess" title="' . $payable->items . '">' . $items . '</div>',
                'quantity' => $payable->quantity.' <span class="text-danger">('.$payable->uom->code.')</span>',
                'uom' => $payable->uom->code,
                'amount' => $this->money_format($payable->identityAmount),
                'total' => $payable->quantity.' ('.$payable->uom->code.') x '.$this->money_format($payable->identityAmount).'<br/><strong class="text-danger">' . $this->money_format($payable->identityTotal) . '</strong>',
                'due_date' => date('d-M-Y', strtotime($payable->due_date)),
                'approved_by' => $approvedBy,
                'modified' => ($payable->updated_at !== NULL) ? date('d-M-Y', strtotime($payable->updated_at)).'<br/>'. date('h:i A', strtotime($payable->updated_at)) : date('d-M-Y', strtotime($payable->created_at)).'<br/>'. date('h:i A', strtotime($payable->created_at)),
                'status' => $statusClass[$payable->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$payable->identityStatus]->bg. ' p-2">' . $statusClass[$payable->identityStatus]->status . '</span>' ,
                'actions' =>  ($payable->identityStatus == 'cancelled') ? $actions2 : $actions
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
            return $this->acctgAccountPayableRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountPayableRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $payableID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'posted',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id,
                'posted_at' => $timestamp,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );

            return response()->json([
                'data' => $this->acctgAccountPayableRepository->approve($payableID, $details),
                'text' => 'The payables has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function approve_all(Request $request)
    {   
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'posted',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id,
                'posted_at' => $timestamp,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgAccountPayableRepository->approve_all($request, $details);
            return response()->json([
                'title' => 'Well done!',
                'text' => 'The payables has been successfully approved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function disapprove(Request $request, $payableID)
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
            $this->acctgAccountPayableRepository->disapprove($payableID, $details);
            return response()->json([
                'text' => 'The payables has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove_all(Request $request)
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
            $res = $this->acctgAccountPayableRepository->disapprove_all($request, $details);
            return response()->json([
                'title' => 'Well done!',
                'text' => 'The payables has been successfully disapproved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function fetch_status(Request $request, $payableID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountPayableRepository->find($payableID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $payableID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountPayableRepository->find($payableID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgAccountPayableRepository->find($id)
        ]);
    }
}
