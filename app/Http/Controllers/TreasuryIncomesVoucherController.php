<?php

namespace App\Http\Controllers;
use App\Models\AcctgVoucher;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\CtoDisburseInterface;
use App\Interfaces\AcctgAccountVoucherInterface;
use App\Interfaces\AcctgAccountIncomeInterface;
use App\Interfaces\AcctgAccountDisbursementInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use PDF;
use TCPDF_FONTS;

class TreasuryIncomesVoucherController extends Controller
{
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private AcctgAccountIncomeInterface $acctgAccountIncomeRepository;    
    private AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository;
    private CtoDisburseInterface $treasuryDisburseRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository, 
        AcctgAccountIncomeInterface $acctgAccountIncomeRepository, 
        AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository, 
        CtoDisburseInterface $treasuryDisburseRepository,
        Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->acctgAccountIncomeRepository = $acctgAccountIncomeRepository;
        $this->acctgAccountDisbursementRepository = $acctgAccountDisbursementRepository;
        $this->treasuryDisburseRepository = $treasuryDisburseRepository;
        $this->carbon = $carbon;
        $this->slugs = 'treasury/journal-entries/incomes';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        Session::forget('voucher');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $can_download = $this->is_permitted($this->slugs, 'download', 1);
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Collections' => 'Collections', 'Deductions' => 'Deductions'];
        $segment = request()->segment(count(request()->segments()));
        return view('treasury.journal-entries.incomes.index')->with(compact('segment', 'can_create', 'can_download', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }

    public function create(Request $request)
    {  
        $accounting_permission = array(
            'create' => $this->is_permitted('accounting/journal-entries/incomes', 'create', 1),
            'read' => $this->is_permitted('accounting/journal-entries/incomes', 'read', 1),
            'update' => $this->is_permitted('accounting/journal-entries/incomes', 'update', 1),
            'delete' => $this->is_permitted('accounting/journal-entries/incomes', 'delete', 1),
            'approve' => $this->is_permitted('accounting/journal-entries/incomes', 'approve', 1),
            'disapprove' => $this->is_permitted('accounting/journal-entries/incomes', 'disapprove', 1),
            'download' => $this->is_permitted('accounting/journal-entries/incomes', 'download', 1)
        );
        $treasury_permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $payees = $this->acctgAccountVoucherRepository->allPayees();
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $sl_accounts = $this->acctgAccountVoucherRepository->allPaymentSLAccounts();
        $sl_accountx = $this->acctgAccountVoucherRepository->allBankSLAccounts();
        $payment_types = $this->acctgAccountVoucherRepository->allPaymentType();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Collections' => 'Collections', 'Deductions' => 'Deductions'];
        $fund_codes = $this->acctgAccountVoucherRepository->allFundCodes();
        $segment = request()->segment(count(request()->segments()));
        $approvers = $this->acctgAccountVoucherRepository->get_voucher_approvers();
        return view('treasury.journal-entries.incomes.create')->with(compact('approvers', 'segment', 'accounting_permission', 'treasury_permission', 'fund_codes', 'payees', 'payment_types', 'sl_accounts', 'sl_accountx', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }

    public function edit(Request $request, $id)
    {  
        $voucher = AcctgVoucher::find($id);
        if ($voucher->is_payables > 0) {
            return abort(404);
        }
        Session::put('voucher', $id);
        $accounting_permission = array(
            'create' => $this->is_permitted('accounting/journal-entries/incomes', 'create', 1),
            'read' => $this->is_permitted('accounting/journal-entries/incomes', 'read', 1),
            'update' => $this->is_permitted('accounting/journal-entries/incomes', 'update', 1),
            'delete' => $this->is_permitted('accounting/journal-entries/incomes', 'delete', 1),
            'approve' => $this->is_permitted('accounting/journal-entries/incomes', 'approve', 1),
            'disapprove' => $this->is_permitted('accounting/journal-entries/incomes', 'disapprove', 1),
            'download' => $this->is_permitted('accounting/journal-entries/incomes', 'download', 1)
        );
        $treasury_permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $payees = $this->acctgAccountVoucherRepository->allPayees();
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $sl_accounts = $this->acctgAccountVoucherRepository->allPaymentSLAccounts();
        $sl_accountx = $this->acctgAccountVoucherRepository->allBankSLAccounts();
        $payment_types = $this->acctgAccountVoucherRepository->allPaymentType();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Collections' => 'Collections', 'Deductions' => 'Deductions'];
        $fund_codes = $this->acctgAccountVoucherRepository->allFundCodes();
        $segment = request()->segment(count(request()->segments()) - 2);
        $approvers = $this->acctgAccountVoucherRepository->get_voucher_approvers();
        return view('treasury.journal-entries.incomes.create')->with(compact('approvers', 'segment', 'accounting_permission', 'treasury_permission', 'fund_codes', 'payees', 'payment_types', 'sl_accounts', 'sl_accountx', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }

    public function view(Request $request, $id)
    {  
        $voucher = AcctgVoucher::find($id);
        if ($voucher->is_payables > 0) {
            return abort(404);
        }
        Session::put('voucher', $id);
        $accounting_permission = array(
            'create' => $this->is_permitted('accounting/journal-entries/incomes', 'create', 1),
            'read' => $this->is_permitted('accounting/journal-entries/incomes', 'read', 1),
            'update' => $this->is_permitted('accounting/journal-entries/incomes', 'update', 1),
            'delete' => $this->is_permitted('accounting/journal-entries/incomes', 'delete', 1),
            'approve' => $this->is_permitted('accounting/journal-entries/incomes', 'approve', 1),
            'disapprove' => $this->is_permitted('accounting/journal-entries/incomes', 'disapprove', 1),
            'download' => $this->is_permitted('accounting/journal-entries/incomes', 'download', 1)
        );
        $treasury_permission = array(
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'read' => $this->is_permitted($this->slugs, 'read', 1),
            'update' => $this->is_permitted($this->slugs, 'update', 1),
            'delete' => $this->is_permitted($this->slugs, 'delete', 1),
            'approve' => $this->is_permitted($this->slugs, 'approve', 1),
            'disapprove' => $this->is_permitted($this->slugs, 'disapprove', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        );
        $payees = $this->acctgAccountVoucherRepository->allPayees();
        $gl_accounts = $this->acctgAccountVoucherRepository->allGLAccounts();
        $sl_accounts = $this->acctgAccountVoucherRepository->allPaymentSLAccounts();
        $sl_accountx = $this->acctgAccountVoucherRepository->allBankSLAccounts();
        $payment_types = $this->acctgAccountVoucherRepository->allPaymentType();
        $uoms = $this->acctgAccountVoucherRepository->allUOMs();
        $evats = $this->acctgAccountVoucherRepository->allEVAT();
        $ewts = $this->acctgAccountVoucherRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Collections' => 'Collections', 'Deductions' => 'Deductions'];
        $fund_codes = $this->acctgAccountVoucherRepository->allFundCodes();
        $segment = request()->segment(count(request()->segments()) - 2);
        $approvers = $this->acctgAccountVoucherRepository->get_voucher_approvers();
        return view('treasury.journal-entries.view')->with(compact('approvers', 'segment', 'accounting_permission', 'treasury_permission', 'fund_codes', 'payees', 'payment_types', 'sl_accounts', 'sl_accountx', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'bg-secondary', 'status' => 'draft'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="modify this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions2 = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            // $actions .= '<a href="javascript:;" class="action-btn complete-btn completed-bg btn m-1 btn-sm align-items-center" title="complete this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-check text-white"></i></a>';
        }
        $result = $this->acctgAccountVoucherRepository->listItems($request, 0);
        $res = $result->data->map(function($voucher) use ($statusClass, $actions, $actions2) {
            $payee = $voucher->payee ? wordwrap($voucher->payee->paye_name, 25, "\n") : ''; 
            $remarks = $voucher->remarks ? wordwrap($voucher->remarks, 25, "\n") : '';     
            $totalAmount = floatval($voucher->identityPayablesAmount) - floatval($this->format_money($voucher->identityEWTAmount) + $this->format_money($voucher->identityEVATAmount));                                  
            return [
                'id' => $voucher->identity,
                'voucher' => $voucher->voucher ? $voucher->voucher->voucher_no : '',
                'voucher_label' => '<strong class="text-primary">' . ($voucher->voucher_no ? $voucher->voucher_no : '') . '</strong>',
                'payee' => '<div class="showLess" title="' . ($voucher->payee ? $voucher->payee->paye_name : '') . '">' . $payee . '</div>',
                'remarks' => '<div class="showLess" title="' . $voucher->remarks . '">' . $remarks . '</div>',
                'total_payables' => $this->money_format($totalAmount),
                'total_ewt' => $this->money_format($voucher->identityEWTAmount),
                'total_evat' => $this->money_format($voucher->identityEVATAmount),
                'total_disbursement' => $this->money_format($voucher->identityDisbursementAmount),
                'total_deduction' => $this->money_format($voucher->identityDeduction),
                'modified' => ($voucher->identityUpdatedAt !== NULL) ? 
                '<strong>'.$voucher->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($voucher->identityUpdatedAt)) : 
                '<strong>'.$voucher->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($voucher->identityCreatedAt)),
                'status' => $statusClass[$voucher->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$voucher->identityStatus]->bg. ' p-2">' . $statusClass[$voucher->identityStatus]->status . '</span>' ,
                'actions' => ($voucher->identityStatus == 'completed') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function payables_lists(Request $request, $id) 
    { 
        $actions = ''; $actions2 = ''; $actions3 = ''; $actions4 = ''; $break = 0;
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions3 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions2 .= '<br/><a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
            $break++;
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($break > 0) {
                $actions2 .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            } else {
     
                $actions2 .= '<br/><a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            }
        }
        $statusClass = [
            'draft' => (object) ['bg' => 'bg-secondary', 'status' => 'draft', 'action' => $actions],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending', 'action' => $actions2],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted', 'action' => $actions3],            
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled', 'action' => $actions4]
        ];
        $result = $this->acctgAccountVoucherRepository->payables_listItems($request, $id);
        $res = $result->data->map(function($payable) use ($statusClass, $actions, $actions2, $actions3, $actions4) {
            $gl_account = $payable->gl_account ? wordwrap($payable->gl_account->code . ' - ' . $payable->gl_account->description, 25, "\n") : ''; 
            $items = $payable->items ? wordwrap($payable->items, 25, "\n") : '';                            
            return [
                'id' => $payable->identity,
                'checkbox' => ($payable->identityStatus == 'draft') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'" disabled="disabled"></div>',
                'trans_type' => $payable->trans_type,
                'voucher' => $payable->voucher ? $payable->voucher->voucher_no : '',
                'voucher_label' => '<strong class="text-primary">' . ($payable->voucher ? $payable->voucher->voucher_no : '') . '</strong>',
                'gl_account' => $payable->gl_account ? $payable->gl_account->code . ' - ' . $payable->gl_account->description : '',
                'gl_account_label' => '<div class="showLess" title="' . $payable->gl_account->code . ' - ' . $payable->gl_account->description . '">' . $gl_account . '</div>',
                'vat' => $payable->vat_type,
                'ewt' => $payable->ewt ? '<strong>'.$payable->ewt->code.'</strong><br/>('. $payable->ewt->percentage .')' : '',
                'evat' => $payable->evat ? '<strong>'.$payable->evat->code.'</strong><br/>('. $payable->evat->percentage .')' : '',
                'items' => '<div class="showLess" title="' . $payable->items . '">' . $items . '</div>',
                'quantity' => $payable->quantity,
                'uom' => $payable->uom->code,
                'amount' => $this->money_format($payable->identityAmount),
                'total' => '<strong>' . $this->money_format($payable->identityTotal) . '</strong>',
                'due_date' => date('d-M-Y', strtotime($payable->due_date)),
                'modified' => ($payable->updated_at !== NULL) ? date('d-M-Y', strtotime($payable->updated_at)).'<br/>'. date('h:i A', strtotime($payable->updated_at)) : date('d-M-Y', strtotime($payable->created_at)).'<br/>'. date('h:i A', strtotime($payable->created_at)),
                'status' => $statusClass[$payable->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$payable->identityStatus]->bg. ' p-2">' . $statusClass[$payable->identityStatus]->status . '</span>' ,
                'actions' => $statusClass[$payable->identityStatus]->action
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function deductions_lists(Request $request, $id) 
    { 
        $actions = ''; $actions2 = ''; $actions3 = ''; $actions4 = ''; $break = 0;
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            // $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions3 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions2 .= '<br/><a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
            $break++;
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($break > 0) {
                $actions2 .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            } else {
                $actions2 .= '<br/><a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            }
        }
        $statusClass = [
            'draft' => (object) ['bg' => 'bg-secondary', 'status' => 'draft', 'action' => $actions],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending', 'action' => $actions2],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted', 'action' => $actions3],            
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled', 'action' => $actions4]
        ];
        $result = $this->acctgAccountVoucherRepository->deductions_listItems($request, $id);
        $res = $result->data->map(function($deduction) use ($statusClass, $actions, $actions2, $actions3, $actions4) {
            $gl_account = $deduction->gl_account ? wordwrap($deduction->gl_account->code . ' - ' . $deduction->gl_account->description, 25, "\n") : ''; 
            $items = $deduction->items ? wordwrap($deduction->items, 25, "\n") : '';                            
            return [
                'id' => $deduction->identity,
                'checkbox' => ($deduction->identityStatus == 'draft') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$deduction->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$deduction->identity.'" disabled="disabled"></div>',
                'trans_type' => $deduction->trans_type,
                'voucher' => $deduction->voucher ? $deduction->voucher->voucher_no : '',
                'voucher_label' => '<strong class="text-primary">' . ($deduction->voucher ? $deduction->voucher->voucher_no : '') . '</strong>',
                'gl_account' => $deduction->gl_account ? $deduction->gl_account->code . ' - ' . $deduction->gl_account->description : '',
                'gl_account_label' => '<div class="showLess" title="' . $deduction->gl_account->code . ' - ' . $deduction->gl_account->description . '">' . $gl_account . '</div>',
                'vat' => $deduction->vat_type,
                'ewt' => $deduction->ewt ? '<strong>'.$deduction->ewt->code.'</strong><br/>('. $deduction->ewt->percentage .')' : '',
                'evat' => $deduction->evat ? '<strong>'.$deduction->evat->code.'</strong><br/>('. $deduction->evat->percentage .')' : '',
                'items' => '<div class="showLess" title="' . $deduction->items . '">' . $items . '</div>',
                'quantity' => $deduction->quantity,
                'uom' => $deduction->uom->code,
                'amount' => $this->money_format($deduction->identityAmount),
                'total' => '<strong>' . $this->money_format($deduction->identityTotal) . '</strong>',
                'due_date' => date('d-M-Y', strtotime($deduction->due_date)),
                'modified' => ($deduction->updated_at !== NULL) ? date('d-M-Y', strtotime($deduction->updated_at)).'<br/>'. date('h:i A', strtotime($deduction->updated_at)) : date('d-M-Y', strtotime($deduction->created_at)).'<br/>'. date('h:i A', strtotime($deduction->created_at)),
                'status' => $statusClass[$deduction->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$deduction->identityStatus]->bg. ' p-2">' . $statusClass[$deduction->identityStatus]->status . '</span>' ,
                'actions' => $statusClass[$deduction->identityStatus]->action
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function collections_lists(Request $request, $id) 
    { 
        $actions = ''; $actions2 = ''; $actions3 = ''; $actions4 = ''; $break = 0;
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            // $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions3 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-comment-alt text-white"></i></a>';
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions2 .= '<br/><a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
            $break++;
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($break > 0) {
                $actions2 .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            } else {
                $actions2 .= '<br/><a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            }
        }
        $statusClass = [
            'draft' => (object) ['bg' => 'bg-secondary', 'status' => 'draft', 'action' => $actions],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending', 'action' => $actions2],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted', 'action' => $actions3],            
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled', 'action' => $actions4]
        ];
        $result = $this->acctgAccountVoucherRepository->collections_listItems($request, $id);
        $res = $result->data->map(function($payable) use ($statusClass, $actions, $actions2, $actions3, $actions4) {
            $gl_account = $payable->gl_account ? wordwrap($payable->gl_account->code . ' - ' . $payable->gl_account->description, 25, "\n") : ''; 
            $items = $payable->items ? wordwrap($payable->items, 25, "\n") : '';                            
            return [
                'id' => $payable->identity,
                'checkbox' => ($payable->identityStatus == 'draft') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payable->identity.'" disabled="disabled"></div>',
                'trans_type' => $payable->trans_type,
                'voucher' => $payable->voucher ? $payable->voucher->voucher_no : '',
                'voucher_label' => '<strong class="text-primary">' . ($payable->voucher ? $payable->voucher->voucher_no : '') . '</strong>',
                'gl_account' => $payable->gl_account ? $payable->gl_account->code . ' - ' . $payable->gl_account->description : '',
                'gl_account_label' => '<div class="showLess" title="' . $payable->gl_account->code . ' - ' . $payable->gl_account->description . '">' . $gl_account . '</div>',
                'vat' => $payable->vat_type,
                'ewt' => $payable->ewt ? '<strong>'.$payable->ewt->code.'</strong><br/>('. $payable->ewt->percentage .')' : '',
                'evat' => $payable->evat ? '<strong>'.$payable->evat->code.'</strong><br/>('. $payable->evat->percentage .')' : '',
                'items' => '<div class="showLess" title="' . $payable->items . '">' . $items . '</div>',
                'quantity' => $payable->quantity,
                'uom' => $payable->uom->code,
                'amount' => $this->money_format($payable->identityAmount),
                'total' => '<strong>' . $this->money_format($payable->identityTotal) . '</strong>',
                'due_date' => date('d-M-Y', strtotime($payable->due_date)),
                'modified' => ($payable->updated_at !== NULL) ? date('d-M-Y', strtotime($payable->updated_at)).'<br/>'. date('h:i A', strtotime($payable->updated_at)) : date('d-M-Y', strtotime($payable->created_at)).'<br/>'. date('h:i A', strtotime($payable->created_at)),
                'status' => $statusClass[$payable->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$payable->identityStatus]->bg. ' p-2">' . $statusClass[$payable->identityStatus]->status . '</span>' ,
                'actions' => $statusClass[$payable->identityStatus]->action
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function payments_lists(Request $request, $id) 
    { 
        $actions = ''; $actions2 = ''; $actions3 = ''; $actions4 = ''; $actions5 = ''; $break = 0;
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm  align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>';
        }
        $actions2 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        $actions3 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions3 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions4 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        $actions5 .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
        $actions5 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="download this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions2 .= '<br/><a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" title="approve this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-up text-white"></i></a>';
            $break++;
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            if ($break > 0) {
                $actions2 .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            } else {
                $actions2 .= '<br/><a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" title="disapprove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-thumb-down text-white"></i></a>';
            }
        }
        $statusClass = [
            'draft' => (object) ['bg' => 'bg-secondary', 'status' => 'draft', 'action' => $actions],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending', 'action' => $actions2],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted', 'action' => $actions3],         
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled', 'action' => $actions4],   
            'deposited' => (object) ['bg' => 'deposited-bg', 'status' => 'deposited', 'action' => $actions5]  
        ];
        $iconClass = [
            'Cash' => (object) ['button' => '<a href="javascript:;" data="cash" class="action-btn print-disbursement-btn bg-info btn m-1 btn-sm align-items-center text-white" title="cash disbursement" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-file-pdf-o"></i></a>'],
            'Cheque' => (object) ['button' => '<a href="javascript:;" data="cheque" class="action-btn print-disbursement-btn bg-info btn m-1 btn-sm align-items-center text-white" title="cheque disbursement" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-file-text""></i></a>'],
            'Deposit' => (object) ['button' => '<a href="javascript:;" data="cash" class="action-btn deposit-btn deposited-bg btn m-1 btn-sm align-items-center text-white" title="deposit" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-share-square-o"></i></a>'],
        ];
        $result = $this->acctgAccountVoucherRepository->payments_listItems($request, $id);
        $res = $result->data->map(function($payment) use ($statusClass, $iconClass, $actions, $actions2, $actions3, $actions4) {
            if ($payment->sl_account_id > 0) {
                $sl_account_label = $payment->sl_account ? wordwrap($payment->sl_account->code . ' - ' . $payment->sl_account->description, 25, "\n") : ''; 
                $sl_account = $payment->sl_account ? $payment->sl_account->code . ' - ' . $payment->sl_account->description : '';
            } else {
                $sl_account_label = $payment->gl_account ? wordwrap($payment->gl_account->code . ' - ' . $payment->gl_account->description, 25, "\n") : ''; 
                $sl_account = $payment->gl_account ? $payment->gl_account->code . ' - ' . $payment->gl_account->description : '';
            }
            $bank_name = $payment->bank_name ? wordwrap($payment->bank_name, 25, "\n") : '';             
            $bank_account_no = $payment->bank_account_no ? wordwrap($payment->bank_account_no, 25, "\n") : '';        
            $bank_account_name = $payment->bank_account_name ? wordwrap($payment->bank_account_name, 25, "\n") : '';    
            $cheque_no = $payment->cheque_no ? wordwrap($payment->cheque_no, 25, "\n") : '';    
            $cheque_date = $payment->cheque_date ? date('d-M-Y', strtotime($payment->cheque_date)) : ''; 
            $buttons = '';
            if ($statusClass[$payment->identityStatus]->status == 'posted' && $payment->type->name == 'Cash') {
                if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                    $buttons .= $iconClass['Deposit']->button;
                }
            }   
            return [
                'id' => $payment->identity,
                'checkbox' => ($payment->identityStatus == 'draft') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payment->identity.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$payment->identity.'" disabled="disabled"></div>',
                'disburse' => $payment->disburse ? $payment->disburse->name.' '.($payment->type ? '<br/>('.$payment->type->name.')' : '') : '',
                'gl_account' => $sl_account,
                'gl_account_label' => '<div class="showLess" title="' . $sl_account . '">' . $sl_account_label . '</div>',
                'type' => $payment->type ? $payment->type->name : '',
                'cheque_details' => '<div class="showLess" title="' . $payment->cheque_no . ' ' . $cheque_date . '">' . $cheque_no . '<br/>' . $cheque_date . '</div>',
                'bank_name' => '<div class="showLess" title="' . $payment->bank_name . '">' . $bank_name . '</div>',
                'account_details' => '<div class="showLess" title="' . $payment->bank_account_no . ' ' . $payment->bank_account_name . '">' . $bank_account_no .'<br/>'. $bank_account_name . '</div>',
                'payment_date' => $payment->payment_date ? date('d-M-Y', strtotime($payment->payment_date)) : '',
                'total' => $this->money_format($payment->identityAmount),
                'modified' => ($payment->identityUpdatedAt !== NULL) ? date('d-M-Y', strtotime($payment->identityUpdatedAt)).'<br/>'. date('h:i A', strtotime($payment->identityUpdatedAt)) : date('d-M-Y', strtotime($payment->identityCreatedAt)).'<br/>'. date('h:i A', strtotime($payment->identityCreatedAt)),
                'status' => $statusClass[$payment->identityStatus]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$payment->identityStatus]->bg. ' p-2">' . $statusClass[$payment->identityStatus]->status . '</span>' ,
                'actions' => (strlen($buttons) > 0) ? $statusClass[$payment->identityStatus]->action . '<br/>' . $buttons : $statusClass[$payment->identityStatus]->action
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
        // return '₱' . number_format(floor(($money*100))/100, 2);
        return '₱' . number_format($money, 2);
    }

    public function money_formatx($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function format_money($money)
    {
        return floatval(floor(($money*100))/100);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($id)
        ]);
    }

    public function generateVoucherNo($fund_code = 0, $id = 0)
    {
        if ($fund_code > 0) {
            return $this->acctgAccountVoucherRepository->generateVoucherNo($fund_code, $id, Auth::user()->id, $this->carbon::now());
        } 
        return '';
    }

    public function update(Request $request, $id): JsonResponse 
    {           
        $voucherNo = $this->generateVoucherNo($request->get('fund_code'), $id);
        if ($id > 0) {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'voucher_no' => $voucherNo,
                'payee_id' => $request->get('payee_id'),
                'fund_code_id' => $request->get('fund_code'),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->update($id, $details);
        } else {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'voucher_no' => $voucherNo,
                'payee_id' => $request->get('payee_id'),
                'fund_code_id' => $request->get('fund_code'),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->create($details);
            Session::put('voucher', $res->id);
            $id = $res->id;
        }

        $this->acctgAccountVoucherRepository->updateSeries([
            'voucher_id' => $id,
            'fund_code_id' => $request->get('fund_code'),
            'series' => $voucherNo,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        ]);
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($id),
            'title' => 'Well done!',
            'text' => 'The voucher has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function get_voucher()
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'voucher' => (Session::get('voucher') > 0) ? Session::get('voucher') : 0,
            'data' =>  (Session::get('voucher') > 0) ? $this->acctgAccountVoucherRepository::find(Session::get('voucher')) : ''
        ]);
    }

    public function fetch_status(Request $request, $voucherID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountVoucherRepository->find($voucherID)->status
        ]);
    }

    public function view_available_payables(Request $request, $voucherID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->view_available_payables($voucherID, $request->get('fund_code'), $request->get('payee'))->map(function($payable) {
                $gl_account = $payable->gl_account ? wordwrap($payable->gl_account->code . ' - ' . $payable->gl_account->description, 25, "\n") : ''; 
                $items = $payable->items ? wordwrap($payable->items, 25, "\n") : '';   
                return (object) [
                    'id' => $payable->identity,
                    'trans_type' => $payable->trans_type,
                    'trans_no' => $payable->trans_no,
                    'voucher' => $payable->voucher ? $payable->voucher->voucher_no : '',
                    'voucher_label' => '<strong class="text-primary">' . ($payable->voucher ? $payable->voucher->voucher_no : '') . '</strong>',
                    'gl_account' => '<div class="showLess" title="' . $payable->gl_account->code . ' - ' . $payable->gl_account->description . '">' . $gl_account . '</div>',
                    'vat' => $payable->vat_type,
                    'ewt' => $payable->ewt ? '<strong>'.$payable->ewt->code.'</strong><br/>('. $payable->ewt->percentage .')' : '',
                    'evat' => $payable->evat ? '<strong>'.$payable->evat->code.'</strong><br/>('. $payable->evat->percentage .')' : '',
                    'items' => '<div class="showLess" title="' . $payable->items . '">' . $items . '</div>',
                    'quantity' => $payable->quantity,
                    'uom' => $payable->uom->code,
                    'amount' => $this->money_format($payable->identityAmount),
                    'total' => '<strong>' . $this->money_format($payable->identityTotal) . '</strong>',
                    'due_date' => date('d-M-Y', strtotime($payable->due_date)),
                    'modified' => ($payable->updated_at !== NULL) ? date('d-M-Y', strtotime($payable->updated_at)).'<br/>'. date('h:i A', strtotime($payable->updated_at)) : date('d-M-Y', strtotime($payable->created_at)).'<br/>'. date('h:i A', strtotime($payable->created_at)),
                ];
            }),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_payables(Request $request, $voucherID) 
    {
        if ($voucherID > 0) {
            $this->acctgAccountVoucherRepository->add_incomes($request, $voucherID);
        } else {
            $details = array(
                'voucher_no' => $this->generateVoucherNo(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->create($details);
            $voucherID = $res->id;
            Session::put('voucher', $voucherID);
            $this->acctgAccountVoucherRepository->add_incomes($request, $voucherID);
        }
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($voucherID),
            'title' => 'Well done!',
            'text' => 'The collections has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function view_deduction(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountIncomeRepository->find_deduction($id)
        ]);
    }

    public function edit_payables(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountIncomeRepository->find($id)
        ]);
    }

    public function update_payables(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $details = array(
            'gl_account_id' => $request->get('gl_account_id'),
            'sl_account_id' => NULL,
            'trans_no' => $request->get('trans_no'),
            'trans_type' => $request->get('trans_type'),
            'items' => $request->get('items'),
            'quantity' => $request->get('quantity'),
            'uom_id' => $request->get('uom_id'),
            'amount' => $request->get('amount'),
            'vat_type' => $request->get('vat_type'),
            'ewt_id' => $request->get('ewt_id'),
            'evat_id' => $request->get('evat_id'),
            'remarks' => $request->get('remarks'),
            'due_date' => date('Y-m-d', strtotime($request->get('due_date'))),
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->acctgAccountPayableRepository->update($id, $details);
        return response()->json([
            'data' => $this->update_vouchers($this->acctgAccountPayableRepository->find($id)->voucher_id),
            'title' => 'Well done!',
            'text' => 'The collections has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function vouchers_update(Request $request, $voucherID)
    {   
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->update_vouchers($voucherID),
            'title' => 'Well done!',
            'text' => 'The payables vouchers has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_vouchers($voucherID)
    {
        $this->acctgAccountVoucherRepository->update_vouchers($voucherID);
        return $this->acctgAccountVoucherRepository->find($voucherID);
    }

    public function remove_collections(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $voucher = $this->acctgAccountIncomeRepository->find($id)->voucher_id;
        $details = array(
            'voucher_id' => NULL,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->acctgAccountVoucherRepository->remove_collections($id, $details);
        return response()->json([
            'data' => $this->update_vouchers($voucher),
            'title' => 'Well done!',
            'text' => 'The collections has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_all_collections(Request $request, $voucherID)
    {   
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $details = array(
                'voucher_id' => NULL,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->remove_all_collections($request, $details);
            return response()->json([
                'data' => $this->update_vouchers($voucherID),
                'title' => 'Well done!',
                'text' => 'The collections has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function send_all_deductions(Request $request, $voucherID)
    {   
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {            
            $timestamp = $this->carbon::now();
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'posted',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'posted_at' => $timestamp,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'status' => 'for approval',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            }
            $res = $this->acctgAccountVoucherRepository->send_all_deductions($request, $details);
            return response()->json([
                'data' => $this->update_vouchers($voucherID),
                'title' => 'Well done!',
                'text' => 'The deductions has been successfully sent.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function send_all_collections(Request $request, $voucherID)
    {   
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {            
            $timestamp = $this->carbon::now();
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'posted',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'posted_at' => $timestamp,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'status' => 'for approval',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
            }
            $res = $this->acctgAccountVoucherRepository->send_all_collections($request, $details);
            return response()->json([
                'data' => $this->update_vouchers($voucherID),
                'title' => 'Well done!',
                'text' => 'The collections has been successfully sent.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function remove_all_payments(Request $request, $voucherID)
    {   
        if ($this->is_permitted('treasury/journal-entries', 'delete', 1) > 0) {
            $details = array(
                'is_active' => 0,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->remove_all_payments($request, $details);
            return response()->json([
                'data' => $this->update_vouchers($voucherID),
                'title' => 'Well done!',
                'text' => 'The deposits has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function send_all_payments(Request $request, $voucherID)
    {   
        if ($this->is_permitted('treasury/journal-entries', 'update', 1) > 0) {
            $timestamp = $this->carbon::now();
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'posted',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'posted_at' => $timestamp,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'status' => 'for approval',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'posted_by' => Auth::user()->id,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
            }
            $res = $this->acctgAccountVoucherRepository->send_all_payments($request, $details);
            return response()->json([
                'data' => $this->update_vouchers($voucherID),
                'title' => 'Well done!',
                'text' => 'The deposits has been successfully sent.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function find_sl_bank(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');         
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find_sl_bank($id),
            'title' => 'Well done!',
            'text' => 'The bank has been successfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function store_payments(Request $request, $voucherID) 
    {
        if ($voucherID > 0) {
            $this->acctgAccountVoucherRepository->add_payments($request, $voucherID, Auth::user()->id, $this->carbon::now());
        } else {
            $details = array(
                'voucher_no' => $this->generateVoucherNo(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $res = $this->acctgAccountVoucherRepository->create($details);
            $voucherID = $res->id;
            Session::put('voucher', $voucherID);
            $this->acctgAccountVoucherRepository->add_payments($request, $voucherID, Auth::user()->id, $this->carbon::now());
        }
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($voucherID),
            'title' => 'Well done!',
            'text' => 'The deposits has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_payments(Request $request, $paymentID) 
    {
        $voucherID = $this->acctgAccountVoucherRepository->update_payments($request, $paymentID, Auth::user()->id, $this->carbon::now());
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($voucherID),
            'title' => 'Well done!',
            'text' => 'The deposits has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_paymentx(Request $request, $paymentID) 
    {
        $voucherID = $this->acctgAccountVoucherRepository->update_paymentx($request, $paymentID, Auth::user()->id, $this->carbon::now());
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find($voucherID),
            'title' => 'Well done!',
            'text' => 'The cash local treasury has been successfully deposited.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_payments(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->find_payments($id)
        ]);
    }

    public function remove_payments(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $voucher = $this->acctgAccountVoucherRepository->find_payments($id)->voucher_id;
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );
        $this->acctgAccountVoucherRepository->remove_payments($id, $details);
        return response()->json([
            'data' => $this->update_vouchers($voucher),
            'title' => 'Well done!',
            'text' => 'The deposits has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_deduction_status(Request $request, $deductionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountIncomeRepository->find_deduction($deductionID)->status
        ]);
    }

    public function validate_deductions_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountIncomeRepository->find_deduction($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve_deduction(Request $request, $deductionID): JsonResponse 
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'posted',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
                'posted_at' => $timestamp
            );

            return response()->json([
                'data' => $this->acctgAccountIncomeRepository->approve_deduction($deductionID, $details),
                'text' => 'The deductions has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove_deduction(Request $request, $deductionID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => urldecode($request->get('remarks')),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->acctgAccountIncomeRepository->disapprove_deduction($deductionID, $details);
            return response()->json([
                'text' => 'The deductions has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_deduction_remarks(Request $request, $deductionID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountIncomeRepository->find_deduction($deductionID)->disapproved_remarks
        ]);
    }

    public function fetch_payable_status(Request $request, $payableID)
    {
        $this->is_permitted('accounting/journal-entries/incomes', 'read'); 
        return response()->json([
            'status' => $this->acctgAccountPayableRepository->find($payableID)->status
        ]);
    }

    public function validate_payables_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountPayableRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve_payable(Request $request, $payableID): JsonResponse 
    {
        if ($this->is_permitted('accounting/journal-entries/incomes', 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'posted',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
                'posted_at' => $timestamp
            );

            return response()->json([
                'data' => $this->acctgAccountPayableRepository->approve($payableID, $details),
                'text' => 'The collections has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove_payable(Request $request, $payableID)
    {
        if ($this->is_permitted('accounting/journal-entries/incomes', 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => urldecode($request->get('remarks')),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->acctgAccountPayableRepository->disapprove($payableID, $details);
            return response()->json([
                'text' => 'The collections has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_payable_remarks(Request $request, $payableID)
    {
        $this->is_permitted('accounting/journal-entries/incomes', 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountPayableRepository->find($payableID)->disapproved_remarks
        ]);
    }

    public function fetch_payment_status(Request $request, $paymentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountDisbursementRepository->find($paymentID)->status
        ]);
    }

    public function validate_payments_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountDisbursementRepository->find($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve_payment(Request $request, $paymentID): JsonResponse 
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'posted',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
                'posted_at' => $timestamp
            );

            return response()->json([
                'data' => $this->acctgAccountDisbursementRepository->approve($paymentID, $details),
                'text' => 'The deposits has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove_payment(Request $request, $paymentID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'cancelled',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => urldecode($request->get('remarks')),
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $this->acctgAccountDisbursementRepository->disapprove($paymentID, $details);
            return response()->json([
                'text' => 'The deposits has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_payment_remarks(Request $request, $paymentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountDisbursementRepository->find($paymentID)->disapproved_remarks
        ]);
    }

    public function print(Request $request, $voucher)
    {
        $res = $this->acctgAccountVoucherRepository->find_voucher($voucher);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        if ($res->is_replenish > 0 && $request->get('reference_no') !== null) {
            $petty = $this->treasuryDisburseRepository->find_via_column('control_no', $request->get('reference_no'));
        }
        $petty = $this->treasuryDisburseRepository->find_via_column('control_no', $request->get('reference_no'));

        PDF::SetTitle('Journal Entry Voucher ('.$voucher.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', 'B', 15);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(150.85, 7.5, 'JOURNAL ENTRY VOUCHER', 'TLR', 'C', 0, 0, '', '', true);
        if (in_array(strtolower($res->fund_code->description), ['general fund', 'trust fund'])) {
            PDF::SetFont('Helvetica', 'B', 12);
        } else {
            PDF::SetFont('Helvetica', 'B', 10);
        }
        PDF::MultiCell(45, 7.5, $res->fund_code->description, 'TR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', 'B', 12);
        PDF::MultiCell(150.85, 5, 'CITY OF PALAYAN', 'LR', 'C', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, 'No', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(32, 5, $voucher, 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(3, 5, 'No', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(150.85, 5, '', 'LR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(45, 5, '', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', 'IB', 10);
        PDF::MultiCell(4, 5, '', 'L', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(142.85, 5, 'PAYEE: '. ($res->payee ? $res->payee->paye_name : '') , 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(4, 5, '', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, 'Date', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(32, 5, date('d F Y', strtotime($res->created_at)), 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(3, 5, 'No', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(150.85, 5, '', 'LRB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(45, 5, '', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $yCheck = PDF::GetY();
        PDF::SetXY(17, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'collection') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(45, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'payables') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(86, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'cheque') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(132, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'cash') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(178, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'others') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');

        PDF::SetXY(10, $yCheck);
        PDF::MultiCell(14, 5, '', 'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(28.37, 5, 'Collection', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(41.37, 5, 'Accounts Payable', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(46.37, 5, 'Check Disbursement', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(46.37, 5, 'Cash Disbursement', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(19.37, 5, 'Others', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(25.85, 13, 'Responsibility Center', 'LR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(85, 13, 'Accounts and Explanation', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(25, 13, 'Acct.' . chr(10) .  'Code', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(60, 6.5, 'Amount', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::ln();
        PDF::MultiCell(135.85, 6.5, '', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::MultiCell(30, 6.5, 'Debit', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::MultiCell(30, 6.5, 'Credit', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::ln();
        $y = PDF::GetY();
        PDF::MultiCell(25.85, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(85, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(25, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 200, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $y2 = PDF::GetY();

        PDF::SetFont('Helvetica', '', 9);
        PDF::setCellHeightRatio(1.5);
        PDF::SetXY(10, $y + 3);

        $totalAmt = 0; $totalDebit = 0; $totalCredit = 0;

        /** IF TYPE IS PAYABLES START **/
        if ($request->get('type') == 'payables') {
            $lines = $this->acctgAccountVoucherRepository->get_payables($voucher);
            if (!empty($lines)) {          
                $iteration = 0; $identities = array();
                foreach ($lines as $line) { 
                    PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                    PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, number_format($line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                    PDF::ln(6);
                    $totalAmt += floatval($line->totalAmt);
                    $totalDebit += floatval($line->totalAmt);
                }
                $totalCredit += floatval($totalAmt);
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::ln();
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();

        } else if ($request->get('type') == 'cash') {
            if (!($res->is_replenish > 0)) {
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                $dueToBir = $this->acctgAccountVoucherRepository->find_due_to_bir_gl();
                $lines = $this->acctgAccountVoucherRepository->get_centre_payables($voucher);    
                $acctgPayables = $this->acctgAccountVoucherRepository->get_sum_payables($voucher)->first();    
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, $acctgPayables->centres, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                PDF::ln();
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, '- ' . ($res->payee ? $res->payee->paye_name : ''), 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($acctgPayables->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                $totalDebit += floatval($acctgPayables->totalAmt);
                PDF::ln(6);
            } else {                
                $lines = $this->acctgAccountVoucherRepository->get_payable_lines($voucher, $request->get('reference_no'));    
                if (!empty($lines)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($lines as $line) {
                        PDF::MultiCell(25.85, 5, $line->responsibility_center, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_account->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $this->money_formatx($line->totalAmt), 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                        PDF::ln();
                        $totalDebit += floatval($line->totalAmt);
                    }
                }   
            }
            $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'));   
            if (!empty($glPayments)) {
                foreach ($glPayments as $glPayment) {
                    if (!($res->is_replenish > 0)) {
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'));   
                        if (!empty($slPayments)) {
                            PDF::SetFont('Helvetica', 'B', 9);
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '- ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);                            
                                $totalCredit += floatval($slPayment->totalPayment);
                            }
                        }
                    } else {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $glPayment->totalPayment, 0, 'R', 0, 0, '', '', true);   
                        $totalCredit += floatval($glPayment->totalPayment);
                    }
                }
                PDF::ln(6);
            }
            if (!($res->is_replenish > 0)) {
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $dueToBir->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $dueToBir->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                $ewt_dues = $this->acctgAccountVoucherRepository->get_due_ewt_payables($voucher);
                if (!empty($ewt_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($ewt_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEwt, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEwt);
                    }
                }
                $evat_dues = $this->acctgAccountVoucherRepository->get_due_evat_payables($voucher);
                if (!empty($evat_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($evat_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEvat, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEvat);
                    }
                    PDF::ln(6);
                }
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        } else if ($request->get('type') == 'cheque') {
            if (!($res->is_replenish > 0)) {
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                $dueToBir = $this->acctgAccountVoucherRepository->find_due_to_bir_gl();
                $lines = $this->acctgAccountVoucherRepository->get_centre_payables($voucher);    
                $acctgPayables = $this->acctgAccountVoucherRepository->get_sum_payables($voucher)->first();    
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, $acctgPayables->centres, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                PDF::ln();
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, '- ' . ($res->payee ? $res->payee->paye_name : ''), 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($acctgPayables->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                $totalDebit += floatval($acctgPayables->totalAmt);
                PDF::ln(6);
            } else {                
                $lines = $this->acctgAccountVoucherRepository->get_payable_lines($voucher, $request->get('reference_no'));    
                if (!empty($lines)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($lines as $line) {
                        PDF::MultiCell(25.85, 5, $line->responsibility_center, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_account->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $this->money_formatx($line->totalAmt), 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                        PDF::ln();
                        $totalDebit += floatval($line->totalAmt);
                    }
                }   
            }
            $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'));   
            if (!empty($glPayments)) {
                foreach ($glPayments as $glPayment) {
                    if (!($res->is_replenish > 0)) {
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'));   
                        if (!empty($slPayments)) {
                            PDF::SetFont('Helvetica', 'B', 9);
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '- ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);                            
                                $totalCredit += floatval($slPayment->totalPayment);
                            }
                        }
                    } else {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $glPayment->totalPayment, 0, 'R', 0, 0, '', '', true);   
                        $totalCredit += floatval($glPayment->totalPayment);
                    }
                }
                PDF::ln(6);
            }
            if (!($res->is_replenish > 0)) {
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $dueToBir->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $dueToBir->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                $ewt_dues = $this->acctgAccountVoucherRepository->get_due_ewt_payables($voucher);
                if (!empty($ewt_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($ewt_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEwt, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEwt);
                    }
                }
                $evat_dues = $this->acctgAccountVoucherRepository->get_due_evat_payables($voucher);
                if (!empty($evat_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($evat_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEvat, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEvat);
                    }
                    PDF::ln(6);
                }
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        } else if ($request->get('type') == 'collection') {
            $deposits = $this->acctgAccountVoucherRepository->get_deposited($voucher);
            if (!($deposits->count() > 0)) {
                $disbursement = $this->acctgAccountVoucherRepository->get_sum_deposits($voucher, $posted = 1);                  
                $totalDebit += floatval($disbursement->totalAmt);
                $collection = $this->acctgAccountVoucherRepository->find_treasury_gl();
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $collection->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $collection->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($disbursement->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::ln();
                $lines = $this->acctgAccountVoucherRepository->get_incomes($voucher, 0, $posted = 1);
                if (!empty($lines)) {   
                    $iteration = 0; $identities = array();
                    foreach ($lines as $line) { 
                        PDF::SetFont('Helvetica', 'B', 9);PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        if (strlen($line->gl_account) > 50) {
                            PDF::ln(); PDF::ln();
                        } else {
                            PDF::ln();
                        }
                        PDF::SetFont('Helvetica', '', 9);
                        $sl_lines = $this->acctgAccountVoucherRepository->get_sl_incomes($voucher, 0, $line->gl_id, $posted = 1);
                        if (!empty($sl_lines)) {
                            foreach ($sl_lines as $sl_line) {
                                PDF::MultiCell(25.85, 5, $sl_line->centre, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$sl_line->sl_account, 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $sl_line->sl_code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($sl_line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                                if (strlen($sl_line->sl_account) > 55) {
                                    PDF::ln(); PDF::ln();
                                } else {
                                    PDF::ln();
                                }
                                $totalAmt += floatval($sl_line->totalAmt);
                                $totalCredit += floatval($sl_line->totalAmt);
                            }
                        }
                    }
                }
                $lines = $this->acctgAccountVoucherRepository->get_incomes($voucher, 1, $posted = 1);
                if (!empty($lines)) {      
                    $iteration = 0; $identities = array();
                    foreach ($lines as $line) { 
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        if (strlen($line->gl_account) > 48) {
                            PDF::ln(); PDF::ln();
                        } else {
                            PDF::ln();
                        }
                        PDF::SetFont('Helvetica', '', 9);
                        $sl_lines = $this->acctgAccountVoucherRepository->get_sl_incomes($voucher, 1, $line->gl_id, $posted = 1);
                        if (!empty($sl_lines)) {
                            foreach ($sl_lines as $sl_line) {
                                PDF::MultiCell(25.85, 5, $sl_line->centre, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$sl_line->sl_account, 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $sl_line->sl_code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($sl_line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                if (strlen($sl_line->sl_account) > 55) {
                                    PDF::ln(); PDF::ln();
                                } else {
                                    PDF::ln();
                                }
                                $totalAmt += floatval($sl_line->totalAmt);
                                $totalDebit += floatval($sl_line->totalAmt);
                            }
                        }
                    }
                }

                $currentTax = $this->acctgAccountVoucherRepository->get_current_tax($voucher, $posted = 1);
                if (count($currentTax) > 0) {
                    foreach ($currentTax as $current) {
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $current['gl_account_desc'], 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $current['gl_account_code'], 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::ln();
                        foreach ($current as $key => $value) {
                            if (is_int($key)) {
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$value['childrens']['sl_account_desc'], 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $value['childrens']['sl_account_code'], 0, 'C', 0, 0, '', '', true);
                                if ($value['childrens']['is_debit'] > 0) {
                                    PDF::MultiCell(30, 5, number_format($value['childrens']['total'], 2), 0, 'R', 0, 0, '', '', true);
                                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                    $totalDebit += floatval($value['childrens']['total']);
                                } else {
                                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                    PDF::MultiCell(30, 5, number_format($value['childrens']['total'], 2), 0, 'R', 0, 0, '', '', true);
                                    $totalCredit += floatval($value['childrens']['total']);
                                }
                                PDF::ln();
                            }
                        }
                    }
                }
            } else {
                $disbursement = $this->acctgAccountVoucherRepository->get_sum_deposits($voucher, $posted = 1);     
                $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'), $posted = 1, $collection = 1); 
                if (!empty($glPayments)) {
                    foreach ($glPayments as $glPayment) {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'), $posted = 1);   
                        if (!empty($slPayments)) {
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $slPayment->code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true); 
                                PDF::ln();                           
                                $totalDebit += floatval($slPayment->totalPayment);
                            }
                        }
                    }
                }
                $collection = $this->acctgAccountVoucherRepository->find_treasury_gl();
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(85, 5, $collection->description, 0, 'L', 0, 0, '', '', true);
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25, 5, $collection->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($disbursement->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::ln();
                $totalCredit += floatval($disbursement->totalAmt);
            }

            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        }
        
        /** IF TYPE IS PAYABLES END **/
        if (!($res->is_replenish > 0)) {
            $obligationNo = $this->acctgAccountVoucherRepository->get_obligation_no($voucher);
        } else {
            $obligationNo = $this->acctgAccountVoucherRepository->get_obligation_no_via_disbursement($voucher, $request->get('reference_no'));
        }
        PDF::SetXY(10, $y2 - 20);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(25.85, 5, 'OBR No:', 0, 'R', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(85, 5, $obligationNo, 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::ln();
        if ($request->get('type') == 'cheque') {
            $checkNo = $this->acctgAccountVoucherRepository->get_checque_no($voucher);
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, 'CHECK No:', 0, 'R', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(85, 5, $checkNo, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        }
        $invoiceNo = $this->acctgAccountVoucherRepository->get_invoice_no($voucher);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(25.85, 5, 'INVOICE No:', 0, 'R', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(85, 5, $invoiceNo, 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::ln();

        PDF::SetXY(10, $y2);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(2, 8, '', 'LB', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(31, 8, 'System Control No.', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(15, 8, '', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(87.85, 8, 'TOTAL', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(30, 8, number_format($totalDebit, 2), 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(30, 8, number_format($totalCredit, 2), 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::ln();

        $document = $this->acctgAccountVoucherRepository->fetch_document($res->id, $request->get('type'));
        if ($document) {
            if ($document->prepared) {
                if (file_exists('uploads/e-signature/'.$document->prepared->identification_no.'_'.urlencode($document->prepared->fullname).'.png')) {
                    PDF::Image(url('./uploads/e-signature/'.$document->prepared->identification_no.'_'.urlencode($document->prepared->fullname).'.png'), 34, 280, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
                }
            }
            if ($document->approved) {
                if (file_exists('uploads/e-signature/'.$document->approved->identification_no.'_'.urlencode($document->approved->fullname).'.png')) {
                    PDF::Image(url('./uploads/e-signature/'.$document->approved->identification_no.'_'.urlencode($document->approved->fullname).'.png'), 132, 280, 50, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, true, true);
                }
            }
        }

        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(97.925, 5, '', 'LR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '', 'R', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(97.925, 5, '     Prepared By:', 'LR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '     Approved By:', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(97.925, 10, '', 'LR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::MultiCell(97.925, 10, '', 'R', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();

        if ($request->get('type') == 'payables') {
            $prepBy  = $res->payables_prepared ? $res->payables_prepared->fullname : '';
            $prepDes = $res->payables_prepared ? $res->payables_prepared->designation->description : '';
            $appBy   = $res->payables_approved ? $res->payables_approved->fullname : '';
            $appDes  = $res->payables_approved ? $res->payables_approved->designation->description : '';
        } else if ($request->get('type') == 'cash') {
            $prepBy  = $res->cash_prepared ? $res->cash_prepared->fullname : '';
            $prepDes = $res->cash_prepared ? $res->cash_prepared->designation->description : '';
            $appBy   = $res->cash_approved ? $res->cash_approved->fullname : '';
            $appDes  = $res->cash_approved ? $res->cash_approved->designation->description : '';
        } else if ($request->get('type') == 'cheque') {
            $prepBy  = $res->cheque_prepared ? $res->cheque_prepared->fullname : '';
            $prepDes = $res->cheque_prepared ? $res->cheque_prepared->designation->description : '';
            $appBy   = $res->cheque_approved ? $res->cheque_approved->fullname : '';
            $appDes  = $res->cheque_approved ? $res->cheque_approved->designation->description : '';
        } else if ($request->get('type') == 'collection') {
            $prepBy  = $res->collections_prepared ? $res->collections_prepared->fullname : '';
            $prepDes = $res->collections_prepared ? $res->collections_prepared->designation->description : '';
            $appBy   = $res->collections_approved ? $res->collections_approved->fullname : '';
            $appDes  = $res->collections_approved ? $res->collections_approved->designation->description : '';
        } else {
            $prepBy  = $res->others_prepared ? $res->others_prepared->fullname : '';
            $prepDes = $res->others_prepared ? $res->others_prepared->designation->description : '';
            $appBy   = $res->others_approved ? $res->others_approved->fullname : '';
            $appDes  = $res->others_approved ? $res->others_approved->designation->description : '';
        }

        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');        
        PDF::MultiCell(77.925, 5, $prepBy, '0', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, $appBy, '0', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');        
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, $prepDes, 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, $appDes, 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();

        $lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        PDF::Line(98, 298.75, 19.85, 298.75, $lineStyle);
        PDF::Line(196, 298.75, 117.85, 298.75, $lineStyle);

        PDF::MultiCell(97.925, 5, '', 'LBR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::Output('journal_entry_voucher_'.$voucher.'.pdf');         
    }
    
    public function preview(Request $request, $voucher)
    {
        $res = $this->acctgAccountVoucherRepository->find_voucher($voucher);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        if ($res->is_replenish > 0 && $request->get('reference_no') !== null) {
            $petty = $this->treasuryDisburseRepository->find_via_column('control_no', $request->get('reference_no'));
        }
        $petty = $this->treasuryDisburseRepository->find_via_column('control_no', $request->get('reference_no'));
        $vouherDate = [
            'collection' => date('d F Y', strtotime($res->collection_voucher_date)),
            'payables' => date('d F Y', strtotime($res->payables_voucher_date)),
            'cash' => date('d F Y', strtotime($res->cash_voucher_date)),
            'cheque' => date('d F Y', strtotime($res->cheque_voucher_date)),
            'others' => date('d F Y', strtotime($res->others_voucher_date)),
        ];

        PDF::SetTitle('Journal Entry Voucher ('.$voucher.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', 'B', 15);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(150.85, 7.5, 'JOURNAL ENTRY VOUCHER', 'TLR', 'C', 0, 0, '', '', true);
        if (in_array(strtolower($res->fund_code->description), ['general fund', 'trust fund'])) {
            PDF::SetFont('Helvetica', 'B', 12);
        } else {
            PDF::SetFont('Helvetica', 'B', 10);
        }
        PDF::MultiCell(45, 7.5, $res->fund_code->description, 'TR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', 'B', 12);
        PDF::MultiCell(150.85, 5, 'CITY OF PALAYAN', 'LR', 'C', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, 'No', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(32, 5, $voucher, 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(3, 5, 'No', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(150.85, 5, '', 'LR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(45, 5, '', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', 'IB', 10);
        PDF::MultiCell(4, 5, '', 'L', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(142.85, 5, 'PAYEE: '. ($res->payee ? $res->payee->paye_name : '') , 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(4, 5, '', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, 'Date', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(35, 5, 'PREPARED DATE', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(150.85, 5, '', 'LRB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(45, 5, '', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $yCheck = PDF::GetY();
        PDF::SetXY(17, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'collection') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(45, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'payables') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(86, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'cheque') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(132, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'cash') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
        PDF::SetXY(178, $yCheck - .5);
        PDF::MultiCell(6, 6, ($request->get('type') == 'others') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');

        PDF::SetXY(10, $yCheck);
        PDF::MultiCell(14, 5, '', 'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(28.37, 5, 'Collection', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(41.37, 5, 'Accounts Payable', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(46.37, 5, 'Check Disbursement', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(46.37, 5, 'Cash Disbursement', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(19.37, 5, 'Others', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(25.85, 13, 'Responsibility Center', 'LR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(85, 13, 'Accounts and Explanation', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(25, 13, 'Acct.' . chr(10) .  'Code', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::MultiCell(60, 6.5, 'Amount', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::ln();
        PDF::MultiCell(135.85, 6.5, '', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::MultiCell(30, 6.5, 'Debit', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::MultiCell(30, 6.5, 'Credit', 'BR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6.5, $valign='M');
        PDF::ln();
        $y = PDF::GetY();
        PDF::MultiCell(25.85, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(85, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(25, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 200, '', 'LB', 'C', 0, 0, '', '', true);
        PDF::MultiCell(30, 200, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $y2 = PDF::GetY();

        PDF::SetFont('Helvetica', '', 9);
        PDF::setCellHeightRatio(1.5);
        PDF::SetXY(10, $y + 3);

        $totalAmt = 0; $totalDebit = 0; $totalCredit = 0;

        /** IF TYPE IS PAYABLES START **/
        if ($request->get('type') == 'payables') {
            $lines = $this->acctgAccountVoucherRepository->get_payables($voucher, $posted = 0);
            if (!empty($lines)) {          
                $iteration = 0; $identities = array();
                foreach ($lines as $line) { 
                    PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                    PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, number_format($line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                    PDF::ln(6);
                    $totalAmt += floatval($line->totalAmt);
                    $totalDebit += floatval($line->totalAmt);
                }
                $totalCredit += floatval($totalAmt);
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::ln();
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        } else if ($request->get('type') == 'cash') {
            if (!($res->is_replenish > 0)) {
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                $dueToBir = $this->acctgAccountVoucherRepository->find_due_to_bir_gl();
                // $lines = $this->acctgAccountVoucherRepository->get_centre_payables($voucher);    
                $acctgPayables = $this->acctgAccountVoucherRepository->get_sum_payables($voucher)->first();    
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, $acctgPayables->centres, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                PDF::ln();
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, '- ' . ($res->payee ? $res->payee->paye_name : ''), 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($acctgPayables->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                $totalDebit += floatval($acctgPayables->totalAmt);
                PDF::ln(6);
            } else {                
                $lines = $this->acctgAccountVoucherRepository->get_payable_lines($voucher, $request->get('reference_no'), $posted = 0);    
                if (!empty($lines)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($lines as $line) {
                        PDF::MultiCell(25.85, 5, $line->responsibility_center, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_account->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $this->money_formatx($line->totalAmt), 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                        PDF::ln();
                        $totalDebit += floatval($line->totalAmt);
                    }
                }   
            }
            $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'), $posted = 0);   
            if (!empty($glPayments)) {
                foreach ($glPayments as $glPayment) {
                    if (!($res->is_replenish > 0)) {
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'), $posted = 0);   
                        if (!empty($slPayments)) {
                            PDF::SetFont('Helvetica', 'B', 9);
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '- ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);                            
                                $totalCredit += floatval($slPayment->totalPayment);
                            }
                        }
                    } else {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $glPayment->totalPayment, 0, 'R', 0, 0, '', '', true);   
                        $totalCredit += floatval($glPayment->totalPayment);
                    }
                }
                PDF::ln(6);
            }
            if (!($res->is_replenish > 0)) {
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $dueToBir->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $dueToBir->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                $ewt_dues = $this->acctgAccountVoucherRepository->get_due_ewt_payables($voucher, $posted = 0);
                if (!empty($ewt_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($ewt_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEwt, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEwt);
                    }
                }
                $evat_dues = $this->acctgAccountVoucherRepository->get_due_evat_payables($voucher, $posted = 0);
                if (!empty($evat_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($evat_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEvat, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEvat);
                    }
                    PDF::ln(6);
                }
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        } else if ($request->get('type') == 'cheque') {
            if (!($res->is_replenish > 0)) {
                $payable = $this->acctgAccountVoucherRepository->find_payable_gl();
                $dueToBir = $this->acctgAccountVoucherRepository->find_due_to_bir_gl();
                // $lines = $this->acctgAccountVoucherRepository->get_centre_payables($voucher);    
                $acctgPayables = $this->acctgAccountVoucherRepository->get_sum_payables($voucher)->first();    
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, $acctgPayables->centres, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                PDF::ln();
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, '- ' . ($res->payee ? $res->payee->paye_name : ''), 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($acctgPayables->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                $totalDebit += floatval($acctgPayables->totalAmt);
                PDF::ln(6);
            } else {                
                $lines = $this->acctgAccountVoucherRepository->get_payable_lines($voucher, $request->get('reference_no'), $posted = 0);    
                if (!empty($lines)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($lines as $line) {
                        PDF::MultiCell(25.85, 5, $line->responsibility_center, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_account->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $this->money_formatx($line->totalAmt), 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                        PDF::ln();
                        $totalDebit += floatval($line->totalAmt);
                    }
                }   
            }
            $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'), $posted = 0);   
            if (!empty($glPayments)) {
                foreach ($glPayments as $glPayment) {
                    if (!($res->is_replenish > 0)) {
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'), $posted = 0);   
                        if (!empty($slPayments)) {
                            PDF::SetFont('Helvetica', 'B', 9);
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '- ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);                            
                                $totalCredit += floatval($slPayment->totalPayment);
                            }
                        }
                    } else {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, $glPayment->totalPayment, 0, 'R', 0, 0, '', '', true);   
                        $totalCredit += floatval($glPayment->totalPayment);
                    }
                }
                PDF::ln(6);
            }
            if (!($res->is_replenish > 0)) {
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $dueToBir->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $dueToBir->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                $ewt_dues = $this->acctgAccountVoucherRepository->get_due_ewt_payables($voucher, $posted = 0);
                if (!empty($ewt_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($ewt_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEwt, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEwt);
                    }
                }
                $evat_dues = $this->acctgAccountVoucherRepository->get_due_evat_payables($voucher, $posted = 0);
                if (!empty($evat_dues)) {
                    PDF::SetFont('Helvetica', '', 9);
                    foreach ($evat_dues as $due) {
                        PDF::ln();
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, '- ' . $due->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, number_format($due->totalEvat, 2), 0, 'R', 0, 0, '', '', true);
                        $totalCredit += floatval($due->totalEvat);
                    }
                    PDF::ln(6);
                }
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
        } else if ($request->get('type') == 'collection') {
            $deposits = $this->acctgAccountVoucherRepository->get_deposited($voucher);
            if (!($deposits->count() > 0)) {
                $disbursement = $this->acctgAccountVoucherRepository->get_sum_deposits($voucher, $posted = 0);                  
                $totalDebit += floatval($disbursement->totalAmt);
                $collection = $this->acctgAccountVoucherRepository->find_treasury_gl();
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $collection->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $collection->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($disbursement->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::ln();
                $lines = $this->acctgAccountVoucherRepository->get_incomes($voucher, 0, $posted = 0);
                if (!empty($lines)) {   
                    $iteration = 0; $identities = array();
                    foreach ($lines as $line) { 
                        PDF::SetFont('Helvetica', 'B', 9);PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        if (strlen($line->gl_account) > 50) {
                            PDF::ln(); PDF::ln();
                        } else {
                            PDF::ln();
                        }
                        PDF::SetFont('Helvetica', '', 9);
                        $sl_lines = $this->acctgAccountVoucherRepository->get_sl_incomes($voucher, 0, $line->gl_id, $posted = 0);
                        if (!empty($sl_lines)) {
                            foreach ($sl_lines as $sl_line) {
                                PDF::MultiCell(25.85, 5, $sl_line->centre, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$sl_line->sl_account, 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $sl_line->sl_code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($sl_line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                                if (strlen($sl_line->sl_account) > 55) {
                                    PDF::ln(); PDF::ln();
                                } else {
                                    PDF::ln();
                                }
                                $totalAmt += floatval($sl_line->totalAmt);
                                $totalCredit += floatval($sl_line->totalAmt);
                            }
                        }
                    }
                }
                $lines = $this->acctgAccountVoucherRepository->get_incomes($voucher, 1, $posted = 0);
                if (!empty($lines)) {      
                    $iteration = 0; $identities = array();
                    foreach ($lines as $line) { 
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $line->gl_account, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $line->gl_code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        if (strlen($line->gl_account) > 48) {
                            PDF::ln(); PDF::ln();
                        } else {
                            PDF::ln();
                        }
                        PDF::SetFont('Helvetica', '', 9);
                        $sl_lines = $this->acctgAccountVoucherRepository->get_sl_incomes($voucher, 1, $line->gl_id, $posted = 0);
                        if (!empty($sl_lines)) {
                            foreach ($sl_lines as $sl_line) {
                                PDF::MultiCell(25.85, 5, $sl_line->centre, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$sl_line->sl_account, 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $sl_line->sl_code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($sl_line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                if (strlen($sl_line->sl_account) > 55) {
                                    PDF::ln(); PDF::ln();
                                } else {
                                    PDF::ln();
                                }
                                $totalAmt += floatval($sl_line->totalAmt);
                                $totalDebit += floatval($sl_line->totalAmt);
                            }
                        }
                    }
                }

                $currentTax = $this->acctgAccountVoucherRepository->get_current_tax($voucher, $posted = 0);
                if (count($currentTax) > 0) {
                    foreach ($currentTax as $current) {
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $current['gl_account_desc'], 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $current['gl_account_code'], 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::ln();
                        foreach ($current as $key => $value) {
                            if (is_int($key)) {
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     '.$value['childrens']['sl_account_desc'], 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $value['childrens']['sl_account_code'], 0, 'C', 0, 0, '', '', true);
                                if ($value['childrens']['is_debit'] > 0) {
                                    PDF::MultiCell(30, 5, number_format($value['childrens']['total'], 2), 0, 'R', 0, 0, '', '', true);
                                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                    $totalDebit += floatval($value['childrens']['total']);
                                } else {
                                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                                    PDF::MultiCell(30, 5, number_format($value['childrens']['total'], 2), 0, 'R', 0, 0, '', '', true);
                                    $totalCredit += floatval($value['childrens']['total']);
                                }
                                PDF::ln();
                            }
                        }
                    }
                }
            } else {
                $disbursement = $this->acctgAccountVoucherRepository->get_sum_deposits($voucher, $posted = 0);     
                $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('type'), $request->get('reference_no'), $posted = 0, $collection = 1); 
                if (!empty($glPayments)) {
                    foreach ($glPayments as $glPayment) {
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::SetFont('Helvetica', 'B', 9);
                        PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                        PDF::SetFont('Helvetica', '', 9);
                        PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 5,'', 0, 'R', 0, 0, '', '', true);
                        $slPayments = $this->acctgAccountVoucherRepository->get_sl_payments($voucher, $glPayment->id, $request->get('reference_no'), $posted = 0);   
                        if (!empty($slPayments)) {
                            foreach ($slPayments as $slPayment) {
                                PDF::ln();
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, '     ' . ((!($res->is_replenish > 0) && $slPayment->description) ? $slPayment->description : 'Petty Cash'), 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $slPayment->code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, number_format($slPayment->totalPayment, 2), 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true); 
                                PDF::ln();                           
                                $totalDebit += floatval($slPayment->totalPayment);
                            }
                        }
                    }
                }
                $collection = $this->acctgAccountVoucherRepository->find_treasury_gl();
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(85, 5, $collection->description, 0, 'L', 0, 0, '', '', true);
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(25, 5, $collection->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, number_format($disbursement->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                PDF::ln();
                $totalCredit += floatval($disbursement->totalAmt);
            }

            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $res->remarks, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        }

        /** IF TYPE IS PAYABLES END **/
        if (!($res->is_replenish > 0)) {
            $obligationNo = $this->acctgAccountVoucherRepository->get_obligation_no($voucher);
        } else {
            $obligationNo = $this->acctgAccountVoucherRepository->get_obligation_no_via_disbursement($voucher, $request->get('reference_no'));
        }
        if ($request->get('type') != 'collection') {
            PDF::SetXY(10, $y2 - 20);
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, 'OBR No:', 0, 'R', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(85, 5, $obligationNo, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
            if ($request->get('type') == 'cheque') {
                $checkNo = $this->acctgAccountVoucherRepository->get_checque_no($voucher);
                PDF::SetFont('Helvetica', 'B', 9);
                PDF::MultiCell(25.85, 5, 'CHECK No:', 0, 'R', 0, 0, '', '', true);
                PDF::SetFont('Helvetica', '', 9);
                PDF::MultiCell(85, 5, $checkNo, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                PDF::ln();
            }
            $invoiceNo = $this->acctgAccountVoucherRepository->get_invoice_no($voucher);
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, 'INVOICE No:', 0, 'R', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(85, 5, $invoiceNo, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        }

        PDF::SetXY(10, $y2);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(2, 8, '', 'LB', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(31, 8, 'System Control No.', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(15, 8, '', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(87.85, 8, 'TOTAL', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(30, 8, number_format($totalDebit, 2), 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(30, 8, number_format($totalCredit, 2), 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::ln();

        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(97.925, 5, '', 'LR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '', 'R', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(97.925, 5, '     Prepared By:', 'LR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '     Approved By:', 'R', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(97.925, 10, '', 'LR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::MultiCell(97.925, 10, '', 'R', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();

        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');        
        PDF::MultiCell(77.925, 5, 'PREPARED BY HERE', '0', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'APPROVED BY HERE', '0', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');        
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'DESIGNATION HERE', 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'DESIGNATION HERE', 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();

        $lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        PDF::Line(98, 298.75, 19.85, 298.75, $lineStyle);
        PDF::Line(196, 298.75, 117.85, 298.75, $lineStyle);

        PDF::MultiCell(97.925, 5, '', 'LBR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::Output('journal_entry_voucher_'.$voucher.'.pdf');         
    }

    public function print_cheque(Request $request, $id)
    {
        $disbursement = $this->acctgAccountDisbursementRepository->find($id);
        if (!($disbursement->count() > 0)) {
            return abort(404);
        }
        $voucher = $this->acctgAccountVoucherRepository->find($disbursement->voucher_id);
        // $res = $res->first();

        $fontname = TCPDF_FONTS::addTTFfont(asset('assets/fonts/font-face/verdana.ttf'), 'TrueTypeUnicode', '', 32);

        PDF::SetTitle('Disbursement Cheque Print');    
        PDF::SetMargins(0, 0, 0,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        $resolution= array(199.136, 75.438);
        PDF::SetFont($fontname, '', 8);
        PDF::AddPage('P', 'LETTER');

        PDF::SetXY(143, 11);
        PDF::MultiCell(50, 5, date('d-M-Y', strtotime($disbursement->cheque_date)), '0', 'C', 0, 0, '', '', true);
        PDF::ln();
        $y = PDF::GetY() + 3;
        PDF::SetXY(23, $y);
        PDF::MultiCell(120, 5, strtoupper($voucher->payee->paye_name), '0', 'L', 0, 0, '', '', true);
        PDF::MultiCell(50, 5, '*** '.$this->money_formatx($disbursement->amount).' ***', '0', 'C', 0, 0, '', '', true);

        PDF::ln();
        $y = PDF::GetY() + 3;
        PDF::SetXY(18, $y);
        PDF::MultiCell(180, 5, strtoupper(trim($this->numberTowords($disbursement->amount))), '0', 'L', 0, 0, '', '', true);

        // PDF::SetFont('Helvetica', 'B', 15);
        // PDF::MultiCell(150.85, 5, $voucher->payee->paye_name, '1', 'C', 0, 0, '', '', true);
        PDF::Output('print_cheque.pdf');        
    }  

    public function numberTowords($amount)
    {
        return $this->acctgAccountVoucherRepository->numberTowords($amount);
    }

    public function validate_voucher($voucher)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'validate' => $this->acctgAccountVoucherRepository->validate_voucher($voucher)
        ]);
    }

    public function fetch_disbursement_type($id)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'type' => $this->acctgAccountDisbursementRepository->find($id)->disburse_type_id
        ]);
    }

    public function fetch_disbursement_reference($id)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'reference' => $this->acctgAccountDisbursementRepository->find($id)->reference_no
        ]);
    }

    public function fetch_voucher_print(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->fetch_voucher_print($request->get('id'), $request->get('type'))
        ]);
    }

    public function update_voucher_date(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->update_voucher_date($id, $request->get('type'), $request->voucher_date, Auth::user()->id, $request->approver, $this->carbon::now()),
            'type' => 'success'
        ]);
    }
    
    public function fetch_document_status(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->fetch_document_status($request->get('id'), $request->get('type'))
        ]);
    }

    public function fetch_document_remarks(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountVoucherRepository->fetch_document_remarks($request->get('id'), $request->get('type'))
        ]);
    }
}
