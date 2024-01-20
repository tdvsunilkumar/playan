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
use App\Interfaces\CtoReplenishInterface;
use App\Interfaces\CtoDisburseInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\GsoObligationRequestInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class TreasuryReplenishController extends Controller
{   
    private CtoReplenishInterface $treasuryReplenishRepository;
    private CtoDisburseInterface $treasuryDisburseRepository;
    private CboBudgetAllocationInterface $cboBudgetAllocationRepository;
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private GsoObligationRequestInterface $gsoObligationRequestRepository;
    private $carbon;
    private $slugs;
    private $permission;

    public function __construct(
        CtoReplenishInterface $treasuryReplenishRepository,
        CtoDisburseInterface $treasuryDisburseRepository,
        CboBudgetAllocationInterface $cboBudgetAllocationRepository,
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository,
        GsoObligationRequestInterface $gsoObligationRequestRepository, 
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->treasuryReplenishRepository = $treasuryReplenishRepository;
        $this->treasuryDisburseRepository = $treasuryDisburseRepository;
        $this->cboBudgetAllocationRepository = $cboBudgetAllocationRepository;
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->gsoObligationRequestRepository = $gsoObligationRequestRepository;
        $this->carbon = $carbon;
        $this->slugs = 'treasury/petty-cash/replenishment';
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
        return view('treasury.petty-cash.replenishment.index')->with(compact('permission', 'vouchers'));
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
            $actions2 .= '<a href="#" class="action-btn print-btn bg-print btn m-1 btn-sm align-items-center" title="print this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-printer text-white"></i></a>';
        }
        $result = $this->treasuryReplenishRepository->listItems($request);
        $res = $result->data->map(function($replenish) use ($actions, $actions2, $statusClass) {
            $particulars = $replenish->particulars ? wordwrap($replenish->particulars, 25, "\n") : '';
            return [
                'id' => $replenish->id,
                'obr_no' => $replenish->obligation ? $replenish->obligation->budget_control_no : '',
                'control_no' => $replenish->control_no,
                'control_no_label' => '<strong class="text-primary">'. $replenish->control_no .'</strong>',
                'particulars' => '<div class="showLess" title="'.($replenish->particulars ? $replenish->particulars : '').'">' . $particulars . '</div>',     
                'total' => $replenish->total_amount,
                'total_label' => $this->money_format($replenish->total_amount),
                'modified' => ($replenish->updated_at !== NULL) ? 
                '<strong>'.$replenish->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($replenish->updated_at)) : 
                '<strong>'.$replenish->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($replenish->created_at)),
                'status' => $statusClass[$replenish->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$replenish->status]->bg. ' p-2">' . $statusClass[$replenish->status]->status . '</span>' ,
                'actions' => ($statusClass[$replenish->status]->status !== 'draft') ? $actions2 : $actions
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

    public function update(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'update'); 
        if ($replenishID <= 0) {
            $details = array(
                'control_no' => $this->treasuryReplenishRepository->generate(),
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $petty = $this->treasuryReplenishRepository->create($details);
            $replenishID = $petty->id;
        }         
        if ($request->voucher_id != NULL) {
            $details = array(
                'particulars' => urldecode($request->get('particulars')),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->treasuryReplenishRepository->update($replenishID, $details);
        } else {
            $details = array(
                'particulars' => urldecode($request->get('particulars')),
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            );
            $this->treasuryReplenishRepository->update($replenishID, $details);
        }
        return response()->json([
            'data' => $petty = $this->treasuryReplenishRepository->find($replenishID),
            'total' => $this->treasuryReplenishRepository->computeTotalAmount($replenishID),
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

    public function find(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->treasuryReplenishRepository->find($replenishID),
            'total' => $this->treasuryReplenishRepository->computeTotalAmount($replenishID)
        ]);
    }

    public function fetch_status(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->treasuryReplenishRepository->find($replenishID)->status
        ]);
    }

    public function view_available_disbursements(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->treasuryReplenishRepository->view_available_disbursements($replenishID)
            ->map(function($obr) {
                return [
                    'id' => $obr->id,
                    'control_no' => $obr->control_no,
                    'particulars' => $obr->particulars,
                    'total_amount' => $obr->total_amount,
                ];
            }),
            'title' => 'Well done!',
            'text' => 'The request has been sucessfully viewed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function add_line(Request $request, $replenishID)
    {
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();
        if ($replenishID <= 0) {
            $details = array(
                'control_no' => $this->treasuryReplenishRepository->generate(),
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $rfq = $this->treasuryReplenishRepository->create($details);
            $replenishID = $rfq->id;
        }
        foreach ($request->disbursements as $disbursement) {
            $exist = $this->treasuryReplenishRepository->check_if_exist($replenishID, $disbursement);
            if ($exist->count() > 0) {
                $replenish = $exist->first();
                $details = array(
                    'is_active' => 1,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $this->treasuryReplenishRepository->update_line($replenish->id, $details);
                $details2 = array(
                    'is_replenished' => 1,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $this->treasuryDisburseRepository->update($disbursement, $details2); 
            } else {
                $details = array(
                    'replenish_id' => $replenishID,
                    'disburse_id' => $disbursement,
                    'created_at' => $timestamp,
                    'created_by' => Auth::user()->id
                );
                $this->treasuryReplenishRepository->create_line($details);
                $details2 = array(
                    'is_replenished' => 1,
                    'updated_at' => $timestamp,
                    'updated_by' => Auth::user()->id
                );
                $this->treasuryDisburseRepository->update($disbursement, $details2); 
            }
        }
        return response()->json([
            'data' => $this->treasuryReplenishRepository->find($replenishID),
            'total' => $this->treasuryReplenishRepository->computeTotalAmount($replenishID),
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
        $replenishLine = $this->treasuryReplenishRepository->find_line($id);
        $details = array(
            'is_active' => 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->treasuryReplenishRepository->update_line($id, $details);
        $details2 = array(
            'is_replenished' => 0,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id
        );
        $this->treasuryDisburseRepository->update($replenishLine->disburse_id, $details2); 
        return response()->json([
            'total' => $this->treasuryReplenishRepository->computeTotalAmount($replenishLine->replenish_id),
            'title' => 'Well done!',
            'text' => 'The payables has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function send(Request $request, $status, $replenishID)
    {   
        $res = $this->treasuryReplenishRepository->find($replenishID);
        $timestamp = $this->carbon::now();
        if ($status == 'for-approval' && $res->status == 'draft') {
            if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
                $details = array(
                    'status' => 'completed',
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'approved_at' => $timestamp,
                    'approved_by' => Auth::user()->id,
                    'department_id' => Auth::user()->hr_employee->acctg_department_id
                );
                $details2 = array(
                    'replenishment_date' => date('Y-m-d', strtotime($timestamp)),
                    'is_replenished' => 1
                );
            } else {
                $details = array(
                    'status' => str_replace('-', ' ', $status),
                    'sent_at' => $timestamp,
                    'sent_by' => Auth::user()->id,
                    'department_id' => Auth::user()->hr_employee->acctg_department_id
                );
            }
            return response()->json([
                'data' => $this->treasuryReplenishRepository->update($replenishID, $details),
                'disbursement' => ($this->is_permitted($this->slugs, 'approve', 1) > 0) ? $this->treasuryReplenishRepository->replenish($replenishID, $timestamp, Auth::user()->id, $details2, 1) : '',
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

    public function print(Request $request, $controlNo)
    {
        $res = $this->gsoObligationRequestRepository->findAlobViaControlNo($controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();

        PDF::SetTitle('Disbursement Voucher ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(195.85, 5, 'Republic of the Philippines', 'TLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(195.85, 5, 'Province of Nueva Ecija', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 11);
        PDF::MultiCell(195.85, 5, 'CITY OF PALAYAN', 'BLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(195.85, 5, '', 'LR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(70.425, 5, '', 'L', 'C', 0, 0, '', '', true);
        PDF::MultiCell(55, 5, 'DISBURSEMENT VOUCHER', 'B', 'C', 0, 0, '', '', true);
        PDF::MultiCell(70.425, 5, '', 'R', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(195.85, 5, '', 'BLR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(25.85, 20, 'Mode Of Payment', 'BLR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Check', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Cash', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(50.333333333, 20, 'Others', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=20, $valign='M');
        PDF::MultiCell(19, 20, '', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(25.85, 13, 'Payee:', 'BLR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(50, 13, $res->requestor->fullname, 'BLR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='M');
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(22, 13, 'TIN/Emp No:', 'B', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(34, 13, '' , 'BR', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(24, 5, 'Obligation No:', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(40, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::ln();
        PDF::MultiCell(85.85, 8, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(46, 8, ($res->requestor->tin_no != NULL) ? $res->requestor->tin_no : '' , 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::setCellHeightRatio(1.15);
        PDF::MultiCell(54, 8, $res->alobNo, 'BR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=8, $valign='T');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(25.85, 25.25, 'Address:', 'LRB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(50, 25.25, chr(10) . trim($res->requestor->current_address), 'BLR', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=13, $valign='T');
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(22, 5, 'Po No:', 0, 'L', 0, 0, '', '', true);

        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(34, 5, '', 'R', 'L', 0, 0, '', '', true);

        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(64, 5, 'Responsibility Center:', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(75.85, 8, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);   
        PDF::MultiCell(46, 8, '' , 'BR', 'L', 0, 0, '', '', true);   


        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);        
        PDF::MultiCell(54, 8, $res->department->code.''.$res->division->code, 'BR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::setCellHeightRatio(1.25);
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(75.85, 4.5, '', 0, 'L', 0, 0, '', '', true); 
        PDF::MultiCell(56, 4.25, 'Office/Unit/Project:', 'R', 'L', 0, 0, '', '', true);
        PDF::MultiCell(64, 4.25, 'Fund Code:', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(75.85, 4.25, '', '', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(46, 8, $res->department->shortname, 'BR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10, 8, '', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(54, 8, $res->fund_code->code.' - '.$res->fund_code->description, 'BR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(97.925, 5, 'EXPLANATION', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 5, 'AMOUNT', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        $y = PDF::getY();
        PDF::SetFont('Helvetica', '', 9);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(97.925, 80, '', 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(97.925, 80, '', 'BR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::setXY(15, $y);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(87.925, 80, '        '.$res->alobParticulars, 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
        PDF::setXY(112.925, $y);
        PDF::SetFont('Helvetica', 'B', 11);
        PDF::MultiCell(87.925, 80, 'Php' . $this->money_format2($res->alobAmount), 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=80, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::setCellHeightRatio(1.5);
        PDF::MultiCell(10.4625, 5, '', 'L', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 5, 'Certified', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '',  0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 5, 'Certified', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 5, '', 'R', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10.4625, 12, '', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 12, '    Allotment Obligated for the purpose indicated above. Supporting documents complete.', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 12, '', 'RB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(10.4625, 12, '', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77, 12, '    Funds Available', 'B', 'L', 0, 0, '', '', true);
        PDF::MultiCell(10.4625, 12, '', 'RB', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::setCellHeightRatio(1.25);
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);

        PDF::ln();
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true,);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->budget_officer ? strtoupper($res->obligation->budget_officer->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->treasurer ? strtoupper($res->obligation->treasurer->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
       
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->budget_officer_designation ? ucwords($res->obligation->budget_officer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->treasurer_designation ? ucwords($res->obligation->treasurer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(97.925, 7.5, 'APPROVED PAYMENT', 'LB', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');
        PDF::MultiCell(97.925, 7.5, 'RECEIVED PAYMENT', 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=7.5, $valign='M');

        PDF::ln();
        PDF::MultiCell(20, 10, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 10, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 10, 'Date', 'LBR', 'L', 0, 0, '', '', true);

        PDF::MultiCell(20, 5, 'Check No', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, 'Date', 'LR', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(97.925, 10, '', 0, 'L', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Signature', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(47.925, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::MultiCell(30, 5, '', 'LBR', 'L', 0, 0, '', '', true);
        PDF::ln();

        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true,);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, ($res->obligation->mayor ? strtoupper($res->obligation->mayor->fullname) : ''), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 10, 'Printed Name', 'LB', 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(77.925, 10, strtoupper($res->requestor->fullname), 'LBR', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, ($res->obligation->treasurer_designation ? ucwords($res->obligation->treasurer_designation) : ''), 'LBR', 'C', 0, 0, '', '', true);
        PDF::MultiCell(20, 5, 'Position', 'LB', 'L', 0, 0, '', '', true);
        PDF::MultiCell(77.925, 5, 'Payee', 'LBR', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetXY(67, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(117, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::SetXY(169, 48.75); 
        PDF::MultiCell(12, 6, '',  'TLBR', 'C', 0, 0, '', '', true);
        PDF::Output('disbursement_voucher_'.$controlNo.'.pdf');
    }

    public function money_format2($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }
}
