<?php

namespace App\Http\Controllers;
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

class TreasuryDisburseController extends Controller
{   
    private CtoDisburseInterface $treasuryDisburseRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private $carbon;
    private $slugs;
    private $permission;

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
        $this->slugs = 'treasury/petty-cash/disbursement';
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
        return view('treasury.petty-cash.disbursement.index')->with(compact('permission', 'vouchers', 'departments'));
    }

    public function lists(Request $request)
    {       
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'for approval'],
            'completed' => (object) ['bg' => 'completed-bg', 'status' => 'completed'],
            'cancelled' => (object) ['bg' => 'cancelled-bg', 'status' => 'cancelled'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn m-1 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
            $actions .= '<a href="javascript:;" class="action-btn send-btn bg-print btn m-1 btn-sm align-items-center" title="send this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-arrow-right text-white"></i></a>';
        }
        $actions2 = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" title="view this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-search text-white"></i></a>';
            // $actions2 .= '<a href="#" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->treasuryDisburseRepository->listItems($request);
        $res = $result->data->map(function($disburse) use ($actions, $actions2, $statusClass) {
            $payee = $disburse->payee ? wordwrap($disburse->payee->paye_name, 25, "\n") : '';
            $particulars = $disburse->particulars ? wordwrap($disburse->particulars, 25, "\n") : '';
            $department = $disburse->department ? wordwrap($disburse->department->name, 25, "\n") : '';
            return [
                'id' => $disburse->id,
                'voucher' => ($disburse->voucher ? $disburse->voucher->voucher_no : ''),
                'voucher_label' => '<strong>'.($disburse->voucher ? $disburse->voucher->voucher_no : '').'</strong>',
                'control_no' => $disburse->control_no,
                'control_no_label' => '<strong class="text-primary">'. $disburse->control_no .'</strong>',
                'particulars' => '<div class="showLess" title="'.($disburse->particulars ? $disburse->particulars : '').'">' . $particulars . '</div>',
                'payee' => '<div class="showLess" title="'.($disburse->payee ? $disburse->payee->paye_name : '').'">' . $payee . '</div>',  
                'department' => '<div class="showLess" title="'.($disburse->department ? $disburse->department->name : '').'">' . $department . '</div>',              
                'total' => $disburse->total_amount,
                'total_label' => $this->money_format($disburse->total_amount),
                'modified' => ($disburse->updated_at !== NULL) ? 
                '<strong>'.$disburse->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($disburse->updated_at)) : 
                '<strong>'.$disburse->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($disburse->created_at)),
                'status' => $statusClass[$disburse->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$disburse->status]->bg. ' p-2">' . $statusClass[$disburse->status]->status . '</span>' ,
                'actions' => ($statusClass[$disburse->status]->status !== 'draft') ? $actions2 : $actions
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

    public function update(Request $request, $disbursementID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        if ($disbursementID <= 0) {
            $details = array(
                'control_no' => $this->treasuryDisburseRepository->generate(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $disbursement = $this->treasuryDisburseRepository->create($details);
            $disbursementID = $disbursement->id;
        }         
        if ($request->voucher_id != NULL) {
            $voucher = $this->acctgAccountVoucherRepository->find($request->voucher_id);
            $details = array(
                'voucher_id' => $request->voucher_id,
                'payee_id' => $voucher->payee_id,
                'department_id' => $request->department_id,
                'disburse_no' => $request->disburse_no,
                'particulars' => urldecode($request->get('particulars')),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->treasuryDisburseRepository->update($disbursementID, $details);
        } else {
            $details = array(
                'department_id' => $request->department_id,
                'disburse_no' => $request->disburse_no,
                'particulars' => urldecode($request->get('particulars')),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->treasuryDisburseRepository->update($disbursementID, $details);
        }
        return response()->json([
            'data' => $disbursement = $this->treasuryDisburseRepository->find($disbursementID),
            'total' => $this->treasuryDisburseRepository->computeTotalAmount($disbursementID),
            'title' => 'Well done!',
            'text' => 'The alob has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function money_formats($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function find(Request $request, $disbursementID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->treasuryDisburseRepository->find($disbursementID),
            'total' => $this->treasuryDisburseRepository->computeTotalAmount($disbursementID)
        ]);
    }

    public function fetch_status(Request $request, $disbursementID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->treasuryDisburseRepository->find($disbursementID)->status
        ]);
    }

    public function view_available_obligation_requests(Request $request, $disbursementID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->treasuryDisburseRepository->view_available_obligation_requests($disbursementID, $request->get('department'))
            ->map(function($obr) {
                return [
                    'id' => $obr->id,
                    'alob_no' => $obr->alobs_control_no,
                    'department' => $obr->department->code . ' - ' . $obr->department->name,
                    'division' => $obr->division->code . ' - ' . $obr->division->name,
                    'total_amount' => $obr->total_amount,
                ];
            }),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_line(Request $request, $disbursementID)
    {
        $this->is_permitted($this->slugs, 'create');
        if ($disbursementID <= 0) {
            $details = array(
                'control_no' => $this->treasuryDisburseRepository->generate(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $rfq = $this->treasuryDisburseRepository->create($details);
            $disbursementID = $rfq->id;
        }
        foreach ($request->obrs as $obr) {
            $exist = $this->treasuryDisburseRepository->check_if_exist($disbursementID, $obr);
            if ($exist->count() > 0) {
                $disburse = $exist->first();
                $details = array(
                    'is_active' => 1,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->treasuryDisburseRepository->update_line($disburse->id, $details);
                $details2 = array(
                    'is_attached' => 1,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->cboBudgetAllocationRepository->updateAllotment($obr, $details2); 
            } else {
                $details = array(
                    'disburse_id' => $disbursementID,
                    'obligation_id' => $obr,
                    'created_at' => $this->carbon::now(),
                    'created_by' => Auth::user()->id
                );
                $this->treasuryDisburseRepository->create_line($details);
                $details2 = array(
                    'is_attached' => 1,
                    'updated_at' => $this->carbon::now(),
                    'updated_by' => Auth::user()->id
                );
                $this->cboBudgetAllocationRepository->updateAllotment($obr, $details2); 
            }
        }
        return response()->json([
            'data' => $this->treasuryDisburseRepository->find($disbursementID),
            'total' => $this->treasuryDisburseRepository->computeTotalAmount($disbursementID),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_line(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $timestamp = $this->carbon::now();
        $disburseLine = $this->treasuryDisburseRepository->find_line($id);
        $details = array(
            'is_active' => 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->treasuryDisburseRepository->update_line($id, $details);
        $details2 = array(
            'is_attached' => 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->cboBudgetAllocationRepository->updateAllotment($disburseLine->obligation_id, $details2); 
        return response()->json([
            'total' => $this->treasuryDisburseRepository->computeTotalAmount($disburseLine->disburse_id),
            'title' => 'Well done!',
            'text' => 'The payables has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $disbursementID)
    {   
        $res = $this->treasuryDisburseRepository->find($disbursementID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'completed',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'disbursement_date' => date('Y-m-d', strtotime($timestamp)),
                    'is_disbursed' => 1
                );
            } else {
                $details = array(
                    'status' => str_replace('-', ' ', $status),
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id
                );
            }
            return response()->json([
                'data' => $this->treasuryDisburseRepository->update($disbursementID, $details),
                'disbursement' => ($this->is_permitted($this->slugs, 'approve', 1) > 0) ? $this->treasuryDisburseRepository->disburse($disbursementID, $timestamp, Auth::user()->id) : '',
                'text' => 'The request has been successfully sent.',
                'type' => 'success',
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'res' => $res->status,
                'stats' => $status,
                'status' => 'failed',
                'text' => 'Technical error.',
            ]);
        }
    }

    public function print(Request $request, $voucher)
    {
        $res = $this->acctgAccountVoucherRepository->find_voucher($voucher);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        $petty = $this->treasuryDisburseRepository->find_via_column('control_no', $request->get('reference_no'));

        PDF::SetTitle('Journal Entry Voucher ('.$voucher.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', 'B', 15);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(150.85, 7.5, 'JOURNAL ENTRY VOUCHER', 'TLR', 'C', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 12);
        PDF::MultiCell(45, 7.5, 'GENERAL FUND', 'TR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');
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
        PDF::MultiCell(142.85, 5, 'PAYEE: '. '', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
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
        PDF::MultiCell(6, 6, ($request->get('type') == 'collections') ? 'X' : '', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M');
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
        if ($request->get('type') == 'cash') {
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
                $obrDetails = $this->treasuryDisburseRepository->get_details($petty->id);
                if (!empty($obrDetails)) {
                    foreach ($obrDetails as $detail) {
                        $breakdowns = $this->treasuryDisburseRepository->get_obligation_details($detail->obligation->id);
                        if (!empty($breakdowns)) {
                            foreach ($breakdowns as $breakdown) {
                                PDF::SetFont('Helvetica', '', 9);
                                PDF::MultiCell(25.85, 5, $breakdown->obligation->department->code.''.$breakdown->obligation->division->code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(85, 5, $breakdown->gl_account->description, 0, 'L', 0, 0, '', '', true);
                                PDF::MultiCell(25, 5, $breakdown->gl_account->code, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, $this->money_formats($breakdown->amount), 0, 'R', 0, 0, '', '', true);
                                PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);    
                                PDF::ln();
                                $totalDebit += floatval($breakdown->amount);
                            }
                        }
                    }
                }   
            }

            $glPayments = $this->acctgAccountVoucherRepository->get_gl_payments($voucher, $request->get('reference_no'));   
            if (!empty($glPayments)) {
                PDF::SetFont('Helvetica', '', 9);
                foreach ($glPayments as $glPayment) {
                    PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(85, 5, $glPayment->description, 0, 'L', 0, 0, '', '', true);
                    PDF::MultiCell(25, 5, $glPayment->code, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
                    PDF::MultiCell(30, 5, $this->money_formats($glPayment->totalPayment), 0, 'R', 0, 0, '', '', true);
                    PDF::ln();
                    $totalCredit += floatval($glPayment->totalPayment);
                }
            } else {
                PDF::ln();
            }
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, $petty->particulars, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

            if (!($res->is_replenish > 0)) { 
                foreach ($lines as $line) { 
                    PDF::MultiCell(25.85, 5, $line->centre, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                    PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                    PDF::MultiCell(30, 200, number_format($line->totalAmt, 2), 0, 'R', 0, 0, '', '', true);
                    PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                    PDF::ln(6);
                    $totalDebit += floatval($line->totalAmt);
                    $dues = $this->acctgAccountVoucherRepository->get_centre_ewt_payables($voucher, $line->centre);
                    if (!empty($dues)) {    
                        PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(85, 5, $dueToBir->description, 0, 'L', 0, 0, '', '', true);
                        PDF::MultiCell(25, 5, $dueToBir->code, 0, 'C', 0, 0, '', '', true);
                        PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                        PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                        PDF::ln(6);
                        foreach ($dues as $due) { 
                            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(85, 5, '- EWT', 0, 'L', 0, 0, '', '', true);
                            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                            PDF::MultiCell(30, 200, number_format($due->totalEwt, 2), 0, 'R', 0, 0, '', '', true);
                            $totalCredit += floatval($due->totalEwt);
                            PDF::ln(6);
                            PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(85, 5, '- EVAT', 0, 'L', 0, 0, '', '', true);
                            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                            PDF::MultiCell(30, 200, number_format($due->totalEvat, 2), 0, 'R', 0, 0, '', '', true);
                            PDF::ln(6);
                            $totalCredit += floatval($due->totalEvat);
                        }
                    }
                }
                PDF::MultiCell(25.85, 5, '', 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(85, 5, $payable->description, 0, 'L', 0, 0, '', '', true);
                PDF::MultiCell(25, 5, $payable->code, 0, 'C', 0, 0, '', '', true);
                PDF::MultiCell(30, 200, '', 0, 'R', 0, 0, '', '', true);
                PDF::MultiCell(30, 200, number_format(0, 2), 0, 'R', 0, 0, '', '', true);
                PDF::ln();
            }
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
        if (!($res->is_replenish > 0)) {
            $invoiceNo = $this->acctgAccountVoucherRepository->get_invoice_no($voucher);
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, 'INVOICE No:', 0, 'R', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(85, 5, $invoiceNo, 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        } else {
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(25.85, 5, 'REF. No:', 0, 'R', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(85, 5, $request->get('reference_no'), 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(25, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
            PDF::ln();
        }

        PDF::SetXY(10, $y2);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(2, 8, '', 'LB', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        // PDF::MultiCell(31, 8, 'System Control No.', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        // PDF::MultiCell(15, 8, '167866', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
        PDF::MultiCell(31, 8, '', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='M');
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
        PDF::MultiCell(77.925, 5, 'Melodee C. Camparo', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'Christina R. Yambot', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'Accounting Staff', 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(77.925, 5, 'City Accountant', 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(10, 5, '', 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(97.925, 5, '', 'LBR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(97.925, 5, '', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::Output('journal_entry_voucher_'.$voucher.'.pdf');        
    }
}
