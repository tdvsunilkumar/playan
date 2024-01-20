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
use App\Interfaces\AcctgAccountReceivableInterface;
use App\Exports\AccountReceivableExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TreasuryReceivablesController extends Controller
{
    private AcctgAccountReceivableInterface $acctgAccountReceivableRepository;
    private $carbon;
    private $slugs;

    public function __construct(AcctgAccountReceivableInterface $acctgAccountReceivableRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountReceivableRepository = $acctgAccountReceivableRepository;
        $this->carbon = $carbon;
        $this->slugs = 'treasury/account-receivables';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $permission = (object) [
            'create' => $this->is_permitted($this->slugs, 'create', 1),
            'download' => $this->is_permitted($this->slugs, 'download', 1)
        ];
        return view('treasury.account-receivables.index')->with(compact('permission'));
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'draft-bg', 'status' => 'unpaid'],
            1 => (object) ['bg' => 'purchased-bg', 'status' => 'partial'],
            2 => (object) ['bg' => 'completed-bg', 'status' => 'paid'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $result = $this->acctgAccountReceivableRepository->listItems($request);
        $res = $result->data->map(function($receivable) use ($statusClass, $actions) {
            $gl_account = $receivable->gl_account ? wordwrap($receivable->gl_account->code . ' - ' . $receivable->gl_account->description, 25, "\n") : ''; 
            $description = $receivable->description ? wordwrap($receivable->description, 25, "\n") : '';
            return [
                'id' => $receivable->id,
                'checkbox' => ($receivable->is_paid == 0) ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$receivable->id.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$receivable->id.'" disabled="disabled"></div>',
                'gl_account' => '<div class="showLess" title="' . $receivable->gl_account->code . ' - ' . $receivable->gl_account->description . '">' . $gl_account . '</div>',
                'items' => '<div class="showLess" title="' . $receivable->description . '">' . $description . '</div>',
                'amount_due' => '<strong>'.$this->money_format($receivable->amount_due).'</strong>',
                'amount_paid' => '<strong>'.$this->money_format($receivable->amount_pay).'</strong>',
                'balance' => '<strong class="text-danger">' . $this->money_format($receivable->remaining_amount) . '</strong>',
                'due_date' => date('d-M-Y', strtotime($receivable->due_date)),
                'modified' => ($receivable->updated_at !== NULL) ? date('d-M-Y', strtotime($receivable->updated_at)).'<br/>'. date('h:i A', strtotime($receivable->updated_at)) : date('d-M-Y', strtotime($receivable->created_at)).'<br/>'. date('h:i A', strtotime($receivable->created_at)),
                'status' => $statusClass[$receivable->is_paid]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$receivable->is_paid]->bg. ' p-2">' . $statusClass[$receivable->is_paid]->status . '</span>' ,
            ];
        });

        return response()->json([
            'request' => $request,
            'recordsTotal' => intval($result->count),  
			'recordsFiltered' => intval($result->count),
            'total_due' => $result->total_due,
            'total_pay' => $result->total_pay,
            'total_balance' => $result->total_balance,
            'data' => $res,
        ]);
    }

    public function money_format($money)
    {
        return (floatval($money) > 0 ) ? '₱' . number_format(floor(($money*100))/100, 2) : '';
    }

    public function export(Request $request)
    {   
        return Excel::download(new AccountReceivableExport($request->get('keywords'), $request->get('status')), 'AccountReceivableSheet_'.time().'.xlsx');
    }
}
