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
use App\Interfaces\AcctgGeneralJournalInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use PDF;
use TCPDF_FONTS;

class AcctgGeneralJournalController extends Controller
{
    private AcctgGeneralJournalInterface $acctgGeneralJournalRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgGeneralJournalInterface $acctgGeneralJournalRepository, 
        Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgGeneralJournalRepository = $acctgGeneralJournalRepository;
        $this->carbon = $carbon;
        $this->slugs = 'accounting/general-journals';
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
        $fixed_assets = ['' => 'select a fixed asset'];
        $fund_codes = $this->acctgGeneralJournalRepository->allFundCodes();
        $payees = $this->acctgGeneralJournalRepository->allPayees();
        $divisions = $this->acctgGeneralJournalRepository->allDivisions();
        $gl_accounts = $this->acctgGeneralJournalRepository->allGLAccounts();
        return view('accounting.general-journals.index')->with(compact('permission', 'fixed_assets', 'fund_codes', 'payees', 'divisions', 'gl_accounts'));
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
            $actions .= '<a href="javascript:;" class="action-btn complete-btn completed-bg btn m-1 btn-sm align-items-center" title="complete this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-check text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->acctgGeneralJournalRepository->listItems($request);
        $res = $result->data->map(function($journal) use ($statusClass, $actions, $actions2) {
            $payee = $journal->payee ? wordwrap($journal->payee->paye_name, 25, "\n") : ''; 
            $particulars = $journal->particulars ? wordwrap($journal->particulars, 25, "\n") : '';   
            return [                
                'id' => $journal->id,
                'journal' => $journal->general_journal_no ? $journal->general_journal_no : '',
                'journal_label' => '<strong class="text-primary">' . ($journal->general_journal_no ? $journal->general_journal_no : '') . '</strong>',
                'fixed_asset_label' => '<strong>' . ($journal->fixed_asset ? $journal->fixed_asset->fixed_asset_no : '') . '</strong>',
                'payee' => '<div class="showLess" title="' . ($journal->payee ? $journal->payee->paye_name : '') . '">' . $payee . '</div>',
                'particulars' => '<div class="showLess" title="' . $journal->particulars . '">' . $particulars . '</div>',
                'transaction_date' => ($journal->transaction_date !== NULL) ? date('d-M-Y', strtotime($journal->transaction_date)) : '',
                'total_debit' => $this->money_format($journal->total_debit),
                'total_credit' => $this->money_format($journal->total_credit),
                'modified' => ($journal->updated_at !== NULL) ? 
                '<strong>'.$journal->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($journal->updated_at)) : 
                '<strong>'.$journal->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($journal->created_at)),
                'status' => $statusClass[$journal->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$journal->status]->bg. ' p-2">' . $statusClass[$journal->status]->status . '</span>' ,
                'actions' => ($journal->status == 'completed') ? $actions2 : $actions
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

    public function reload_fixed_asset(Request $request, $journalID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgGeneralJournalRepository->reload_fixed_asset($journalID),
            'title' => 'Well done!',
            'text' => 'The fixed assets has been successfully reload.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function line_lists(Request $request, $journalID)
    {      
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = ''; $actions2 = ''; $actions3 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions = '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-1 btn-sm align-items-center" title="View"><i class="ti-pencil text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions2 = '<a href="javascript:;" class="action-btn remove-btn bg-danger btn me-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            $actions3 = '<a href="javascript:;" class="action-btn restore-btn bg-info btn me-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
        }
        $result = $this->acctgGeneralJournalRepository->line_listItems($request, $journalID);
        $res = $result->data->map(function($entries, $iteration = 0) use ($actions, $actions2, $actions3, $statusClass) {
            $gl_account = wordwrap($entries->gl_account->code . ' - ' . $entries->gl_account->description, 25, "\n");
            return [
                'id' => $entries->id,
                'code' => $entries->gl_account->code,
                'gl_account' => '<div class="showLess" title="' . $entries->gl_account->code . ' - ' . $entries->gl_account->description . '">' . $gl_account . '</div>',
                'debit' => $this->money_format($entries->debit_amount),
                'credit' => $this->money_format($entries->credit_amount),
                'modified' => ($entries->updated_at !== NULL) ? date('d-M-Y', strtotime($entries->updated_at)).'<br/>'. date('h:i A', strtotime($entries->updated_at)) : date('d-M-Y', strtotime($entries->created_at)).'<br/>'. date('h:i A', strtotime($entries->created_at)),
                'status' => $statusClass[$entries->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$entries->is_active]->bg. ' p-2">' . $statusClass[$entries->is_active]->status . '</span>' ,
                'actions' => ($entries->is_active == 1) ? $actions.' '.$actions2 : $actions.' '.$actions3
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {           
        $timestamp = $this->carbon::now();
        $journalNo = $this->generateVoucherNo($request->get('fund_code'), $id);
        if ($id > 0) {
            $this->is_permitted($this->slugs, 'update'); 
            $details = array(
                'general_journal_no' => $journalNo,
                'fixed_asset_id' => $request->get('fixed_asset'),
                'payee_id' => $request->get('payee'),
                'division_id' => $request->get('division'),
                'fund_code_id' => $request->get('fund_code'),
                'particulars' => $request->get('particulars'),
                'transaction_date' => $request->get('trans_date') ? date('Y-m-d', strtotime($request->get('trans_date'))) : NULL,
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id
            );
            $res = $this->acctgGeneralJournalRepository->update($id, $details);
        } else {
            $this->is_permitted($this->slugs, 'create'); 
            $details = array(
                'general_journal_no' => $journalNo,
                'fixed_asset_id' => $request->get('fixed_asset'),
                'payee_id' => $request->get('payee'),
                'division_id' => $request->get('division'),
                'fund_code_id' => $request->get('fund_code'),
                'particulars' => $request->get('particulars'),
                'transaction_date' => $request->get('trans_date') ? date('Y-m-d', strtotime($request->get('trans_date'))) : NULL,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $res = $this->acctgGeneralJournalRepository->create($details);
            $id = $res->id;
        }

        if ($request->get('fund_code') > 0) {
            $this->acctgGeneralJournalRepository->updateSeries([
                'general_journal_id' => $id,
                'fund_code_id' => $request->get('fund_code'),
                'series' => $journalNo,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            ]);
        }
        return response()->json([
            'data' => $this->acctgGeneralJournalRepository->find($id),
            'journal_no' => $journalNo,
            'title' => 'Well done!',
            'text' => 'The general journal has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function generateVoucherNo($fund_code = 0, $id = 0)
    {
        if ($fund_code > 0) {
            return $this->acctgGeneralJournalRepository->generateVoucherNo($fund_code, $id, Auth::user()->id, $this->carbon::now());
        } 
        return '';
    }

    public function fetch_status(Request $request, $journalID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgGeneralJournalRepository->find($journalID)->status
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgGeneralJournalRepository->find($id)
        ]);
    }

    public function store_entry(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->acctgGeneralJournalRepository->validate_entry($request->gl_account_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a journal entry with an existing gl account.',
                'label' => 'This is an existing gl account.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'gl_account_id',
            ]);
        }

        $details = array(
            'general_journal_id' => $id,
            'gl_account_id' => $request->gl_account_id,
            'debit_amount' => $request->debit_amount,
            'credit_amount' => $request->credit_amount,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $entries = $this->acctgGeneralJournalRepository->create_entry($details);

        $totalDebit = $this->acctgGeneralJournalRepository->getTotalDebitAmount($id);
        $totalCredit = $this->acctgGeneralJournalRepository->getTotalCreditAmount($id);
        return response()->json(
            [
                'data' => $entries,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'title' => 'Well done!',
                'text' => 'The journal entry has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function modify_entry(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $journal = $this->acctgGeneralJournalRepository->find_entry($id);
        $rows = $this->acctgGeneralJournalRepository->validate_entry($request->gl_account_id, $journal->general_journal_id, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a journal entry with an existing gl account.',
                'label' => 'This is an existing gl account.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'gl_account_id',
            ]);
        }

        $details = array(
            'gl_account_id' => $request->gl_account_id,
            'debit_amount' => $request->debit_amount,
            'credit_amount' => $request->credit_amount,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->acctgGeneralJournalRepository->update_entry($id, $details);

        $totalDebit = $this->acctgGeneralJournalRepository->getTotalDebitAmount($journal->general_journal_id);
        $totalCredit = $this->acctgGeneralJournalRepository->getTotalCreditAmount($journal->general_journal_id);
        return response()->json([
            'data' => $journal,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'title' => 'Well done!',
            'text' => 'The budget breakdown has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function find_entry(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->acctgGeneralJournalRepository->find_entry($id)
        ]);
    }

    public function validate_journal(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $journal =  $this->acctgGeneralJournalRepository->find($id);
        return response()->json([
            'validate' => (floatval($journal->total_debit) == floatval($journal->total_credit) && floatval($journal->total_debit) > 0) ? 1 : 0
        ]);
    }

    public function complete(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        $details = array(
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id,
            'status' => 'completed'
        );

        return response()->json([
            'data' => $this->acctgGeneralJournalRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The journal entry has been successfully completed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_entry(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $journal = $this->acctgGeneralJournalRepository->find_entry($id);

        $details = array(
            'is_active' => 0,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );
        $this->acctgGeneralJournalRepository->update_entry($id, $details);

        $totalDebit = $this->acctgGeneralJournalRepository->getTotalDebitAmount($journal->general_journal_id);
        $totalCredit = $this->acctgGeneralJournalRepository->getTotalCreditAmount($journal->general_journal_id);
        return response()->json([
            'data' => $journal,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'title' => 'Well done!',
            'text' => 'The journal entry has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
}
