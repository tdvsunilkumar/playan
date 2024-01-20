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
use App\Interfaces\AcctgAccountDisbursementInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsAccountDisbursementController extends Controller
{   
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private AcctgAccountPayableInterface $acctgAccountPayableRepository;
    private AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository, 
        AcctgAccountPayableInterface $acctgAccountPayableRepository, 
        AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository, 
        Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->acctgAccountPayableRepository = $acctgAccountPayableRepository;
        $this->acctgAccountDisbursementRepository = $acctgAccountDisbursementRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/disbursements';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $payees = $this->acctgAccountVoucherRepository->allPayees();
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $sl_accounts = $this->acctgAccountVoucherRepository->allSLAccounts();
        $payment_types = $this->acctgAccountVoucherRepository->allPaymentType();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Purchase Order' => 'Purchase Order', 'Operational Expenses' => 'Operational Expenses', 'Payroll' => 'Payroll'];
        $fund_codes = $this->acctgAccountVoucherRepository->allFundCodes();
        return view('for-approvals.disbursement.index')->with(compact('fund_codes', 'payees', 'payment_types', 'sl_accounts', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }

    public function lists(Request $request)
    {   
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
            'deposited' => (object) ['bg' => 'completed-bg', 'status' => 'approved'],
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
        $result = $this->acctgAccountDisbursementRepository->approvals_listItems($request);
        $res = $result->data->map(function($payment) use ($statusClass, $actions, $actions2) {
            if ($payment->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($payment->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($payment->disapproved_at));
            } else if($payment->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($payment->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($payment->approved_at));
            } else {
                $approvedBy = '';
            }
            $sl_account = $payment->sl_account ? wordwrap($payment->sl_account->code . ' - ' . $payment->sl_account->description, 25, "\n") : ''; 
            $bank_name = $payment->bank_name ? wordwrap($payment->bank_name, 25, "\n") : '';             
            $bank_account_no = $payment->bank_account_no ? wordwrap($payment->bank_account_no, 25, "\n") : '';        
            $bank_account_name = $payment->bank_account_name ? wordwrap($payment->bank_account_name, 25, "\n") : '';    
            $cheque_no = $payment->cheque_no ? wordwrap($payment->cheque_no, 25, "\n") : '';    
            $cheque_date = $payment->cheque_date ? date('d-M-Y', strtotime($payment->cheque_date)) : '';   
            
            $bank  = '';
            $bank .= 'Bank Name: ' . ($payment->bank_name ? $payment->bank_name : '') .'';     
            $bank .= 'Account No: ' . ($payment->bank_account_no ? $payment->bank_account_no : '') .'';     
            $bank .= 'Account Name: ' . ($payment->bank_account_name ? $payment->bank_account_name : '') .'';     

            return [
                'id' => $payment->identity,
                'voucher_label' => '<a class="voucher-link" href="javascript:;" link="'.url('/treasury/journal-entries/edit/'.$payment->voucher->id).'"><strong class="text-primary">' . ($payment->voucher ? $payment->voucher->voucher_no : '') . '</strong></a>',
                'checkbox' => ($payment->identityStatus == 'for approval') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payment->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payment->identity.'" disabled="disabled"></div>',
                'gl_account' => $payment->sl_account ? $payment->sl_account->code . ' - ' . $payment->sl_account->description : '',
                'gl_account_label' => '<div class="showLess" title="' . ($payment->sl_account ? $payment->sl_account->code . ' - ' . $payment->sl_account->description : '') . '">' . $sl_account . '</div>',
                'type' => $payment->type->name,
                'cheque_details' => '<div class="showLess" title="' . $payment->cheque_no . ' ' . $cheque_date . '">' . $cheque_no . '<br/>' . $cheque_date . '</div>',
                'bank_label' =>  '<div class="showLess" title="' . $bank . '">' . wordwrap($bank, 25, "\n") . '</div>',
                'bank_name' => '<div class="showLess" title="' . $payment->bank_name . '">' . $bank_name . '</div>',
                'account_details' => '<div class="showLess" title="' . $payment->bank_account_no . ' ' . $payment->bank_account_name . '">' . $bank_account_no .'<br/>'. $bank_account_name . '</div>',
                'payment_date' => $payment->payment_date ? date('d-M-Y', strtotime($payment->payment_date)) : '',
                'total' => $this->money_format($payment->identityAmount),
                'modified' => ($payment->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($payment->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($payment->identityUpdatedAt)) : date('d-M-Y', strtotime($payment->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($payment->identityCreatedAt)),
                'approved_by' => $approvedBy,
                'status' => $statusClass[$payment->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$payment->identityStatus]->bg. ' p-2">' . $statusClass[$payment->identityStatus]->status . '</span>' ,
                'actions' =>  ($payment->identityStatus == 'cancelled') ? $actions2 : $actions
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
            return $this->acctgAccountDisbursementRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountDisbursementRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $paymentID)
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
                'data' => $this->acctgAccountDisbursementRepository->approve($paymentID, $details),
                'text' => 'The payments has been successfully approved.',
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
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgAccountDisbursementRepository->approve_all($request, $details);
            return response()->json([
                'title' => 'Well done!',
                'text' => 'The payments has been successfully approved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function disapprove(Request $request, $paymentID)
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
            $this->acctgAccountDisbursementRepository->disapprove($paymentID, $details);
            return response()->json([
                'text' => 'The payments has been successfully disapproved.',
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
            $res = $this->acctgAccountDisbursementRepository->disapprove_all($request, $details);
            return response()->json([
                'title' => 'Well done!',
                'text' => 'The payments has been successfully disapproved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function fetch_status(Request $request, $paymentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountDisbursementRepository->find($paymentID)->status
        ]);
    }

    public function fetch_remarks(Request $request, $paymentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountDisbursementRepository->find($paymentID)->disapproved_remarks
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgAccountDisbursementRepository->find($id)
        ]);
    }
}
