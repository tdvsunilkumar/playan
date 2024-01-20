<?php

namespace App\Http\Controllers;
use App\Models\MenuGroup;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\AcctgAccountPayableInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgPayablesController extends Controller
{
    private AcctgAccountPayableInterface $acctgAccountPayableRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountPayableInterface $acctgAccountPayableRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountPayableRepository = $acctgAccountPayableRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/account-payables';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $can_create = $this->is_permitted($this->slugs, 'create', 1);
        $can_download = $this->is_permitted($this->slugs, 'download', 1);
        $gl_accounts = $this->acctgAccountPayableRepository->allGLAccounts();
        $uoms = $this->acctgAccountPayableRepository->allUOMs();
        $evats = $this->acctgAccountPayableRepository->allEVAT();
        $ewts = $this->acctgAccountPayableRepository->allEWT();
        $vat_types = ['' => 'select a vat type', 'Vatable' => 'Vatable', 'Non-Vatable' => 'Non-Vatable'];
        $trans_types = ['' => 'select a transaction type', 'Purchase Order' => 'Purchase Order', 'Operational Expenses' => 'Operational Expenses', 'Payroll' => 'Payroll'];
        $fund_codes = $this->acctgAccountPayableRepository->allFundCodes();
        return view('accounting.account-payables.index')->with(compact('fund_codes', 'can_create', 'can_download', 'gl_accounts', 'uoms', 'evats', 'ewts', 'trans_types', 'vat_types'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'pending'],
            'posted' => (object) ['bg' => 'completed-bg', 'status' => 'posted'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->acctgAccountPayableRepository->listItems($request);
        $res = $result->data->map(function($payable) use ($statusClass, $actions) {
            $gl_account = $payable->gl_account ? wordwrap($payable->gl_account->code . ' - ' . $payable->gl_account->description, 25, "\n") : ''; 
            $items = $payable->items ? wordwrap($payable->items, 25, "\n") : '';   
            
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
                'trans_type' => $payable->trans_type,
                'transaction_label' => '<strong class="text-primary">'.$payable->trans_no.'</strong><br/>'.$payable->trans_type,
                'voucher' => $payable->voucher ? $payable->voucher->voucher_no : '',
                'voucher_label' => '<strong class="text-primary">' . ($payable->voucher ? $payable->voucher->voucher_no : '') . '</strong>',
                'gl_account' => '<div class="showLess" title="' . $payable->gl_account->code . ' - ' . $payable->gl_account->description . '">' . $gl_account . '</div>',
                'vat' => $vat,
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
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');  
        // $rows = $this->acctgAccountPayableRepository->validate($request->get('trans_no'), $request->get('trans_type'));
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot create a payables with an existing transaction no.',
        //         'label' => 'This is an existing transaction.',
        //         'type' => 'error',
        //         'class' => 'btn-danger',
        //         'column' => 'trans_no',
        //     ]);
        // }

        $details = array(
            'fund_code_id' => $request->get('fund_code_id'),
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
        $payables = $this->acctgAccountPayableRepository->create($details);

        return response()->json(
            [
                'data' => $payables,
                'title' => 'Well done!',
                'text' => 'The payables has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->acctgAccountPayableRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        // $rows = $this->acctgAccountPayableRepository->validate($request->get('trans_no'), $request->get('trans_type'), $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot create a payables with an existing transaction no.',
        //         'label' => 'This is an existing transaction.',
        //         'type' => 'error',
        //         'class' => 'btn-danger',
        //         'column' => 'trans_no',
        //     ]);
        // }

        $payables = $this->acctgAccountPayableRepository->find($id);
        if ($payables->status == 'draft') {
            if ($payables->trans_type == 'Purchase Order') {            
                $details = array(
                    'fund_code_id' => $payables->fund_code_id,
                    'gl_account_id' => $request->get('gl_account_id'),
                    'sl_account_id' => NULL,
                    'trans_no' => $payables->trans_no,
                    'trans_type' => $payables->trans_type,
                    'items' => $payables->items,
                    'quantity' => $payables->quantity,
                    'uom_id' => $payables->uom_id,
                    'amount' => $payables->amount,
                    'vat_type' => $request->get('vat_type'),
                    'ewt_id' => $request->get('ewt_id'),
                    'evat_id' => $request->get('evat_id'),
                    'remarks' => $request->get('remarks'),
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
            } else {
                $details = array(
                    'fund_code_id' => $request->get('fund_code_id'),
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
            }

            return response()->json([
                'data' => $this->acctgAccountPayableRepository->update($id, $details),
                'title' => 'Well done!',
                'text' => 'The payables has been successfully updated.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        } else {
            return response()->json([
                'title' =>  'Oops...',
                'status' => 'failed',
                'type' => 'danger',
                'text' => 'Unable to update, the item is already processed.',
            ]);
        }
    }
}
