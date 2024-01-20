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
use App\Interfaces\CtoDisburseInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class ForApprovalsPayablesJEVController extends Controller
{   
    private AcctgAccountVoucherInterface $acctgAccountVoucherRepository;
    private AcctgAccountPayableInterface $acctgAccountPayableRepository;
    private AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository;
    private CtoDisburseInterface $treasuryDisburseRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgAccountVoucherInterface $acctgAccountVoucherRepository, 
        AcctgAccountPayableInterface $acctgAccountPayableRepository, 
        AcctgAccountDisbursementInterface $acctgAccountDisbursementRepository, 
        CtoDisburseInterface $treasuryDisburseRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->acctgAccountVoucherRepository = $acctgAccountVoucherRepository;
        $this->acctgAccountPayableRepository = $acctgAccountPayableRepository;
        $this->acctgAccountDisbursementRepository = $acctgAccountDisbursementRepository;
        $this->treasuryDisburseRepository = $treasuryDisburseRepository;
        $this->carbon = $carbon;
        $this->slugs = 'for-approvals/journal-entries/payables';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $data = '';
        return view('for-approvals.journal-entries.payables.index')->with(compact('data'));
    }

    public function lists(Request $request)
    {           
        $statusClass = [
            'draft' => (object) ['bg' => 'draft-bg', 'status' => 'Draft'],
            'for approval' => (object) ['bg' => 'for-approval-bg', 'status' => 'Pending'],
            'approved' => (object) ['bg' => 'completed-bg', 'status' => 'Approved'],
            'disapproved' => (object) ['bg' => 'cancelled-bg', 'status' => 'Disapproved'],
        ];
        $actions = ''; $actions2 = '';
        if ($this->is_permitted($this->slugs, 'read', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-btn bg-warning btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="view this"><i class="ti-search text-white"></i></a>';
            $actions2 .= '<a href="javascript:;" class="action-btn view-disapprove-btn bg-danger btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="view this"><i class="ti-comment-alt text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn approve-btn bg-success btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="approve this"><i class="ti-thumb-up text-white"></i></a>';
        }
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn disapprove-btn bg-danger btn m-1 btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="disapprove this"><i class="ti-thumb-down text-white"></i></a>';
        }
        $result = $this->acctgAccountVoucherRepository->approval_docListItems($request, Auth::user()->id, 1);
        $res = $result->data->map(function($document) use ($actions, $actions2, $statusClass) {
            $project_name = ($document->project_name !== NULL) ? wordwrap($document->project_name, 25, "\n") : '';
            $remarks = ($document->remarks !== NULL) ? wordwrap($document->remarks, 25, "\n") : '';
            if ($document->disapproved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($document->disapproved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($document->disapproved_at));
            } else if($document->approved_by !== NULL) {
                $approvedBy = '<strong>'.$this->fetchApprovedBy($document->approved_by).'</strong><br/>'.date('d-M-Y H:i a', strtotime($document->approved_at));
            } else {
                $approvedBy = '';
            }
            return [
                'id' => $document->id,
                'voucher_id' => $document->voucher_id,
                'checkbox' => ($document->status == 'for approval') ? '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$document->id.'"></div>' : '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$document->id.'" disabled="disabled"></div>',
                'voucher_no' => $document->voucher->voucher_no,
                'instance' => $document->instance,
                'voucher_no_label' => '<strong class="text-primary">'.$document->voucher->voucher_no.'</strong>',
                'document' => ucwords(strtolower($document->document)),
                'document_label' => ucwords(strtolower($document->document)).' ('.$document->instance.')',
                'modified' => ($document->updated_at !== NULL) ? date('d-M-Y', strtotime($document->updated_at)).'<br/>'. date('h:i A', strtotime($document->updated_at)) : date('d-M-Y', strtotime($document->created_at)).'<br/>'. date('h:i A', strtotime($document->created_at)),
                'prepared_by' => $document->prepared->fullname,
                'approved_by' => $approvedBy,
                'status' => $statusClass[$document->status]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$document->status]->bg. ' p-2">' . $statusClass[$document->status]->status . '</span>',
                'actions' => ($document->status == 'disapproved') ? $actions2 : $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function fetch_status(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'status' => $this->acctgAccountVoucherRepository->find_document($id)->status
        ]);
    }
    
    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function fetchApprovedBy($approvers)
    {
        if (!empty($approvers)) {
            return $this->acctgAccountVoucherRepository->fetchApprovedBy($approvers);
        }
        return '';
    }

    public function validate_approver(Request $request, $id)
    {
        $approvers = explode(',',$this->acctgAccountVoucherRepository->find_document($id)->approved_by);
        if (in_array(Auth::user()->id, $approvers)) {
            return true;
        }
        return false;
    }

    public function approve(Request $request, $documentID)
    {
        if ($this->is_permitted($this->slugs, 'approve', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'approved',
                'approved_at' => $timestamp,
                'approved_by' => Auth::user()->id
            );
            $this->acctgAccountVoucherRepository->update_document($documentID, $details);
            return response()->json([
                'text' => 'The request has been successfully approved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function disapprove(Request $request, $documentID)
    {
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'status' => 'disapproved',
                'disapproved_at' => $timestamp,
                'disapproved_by' => Auth::user()->id,
                'disapproved_remarks' => $request->disapproved_remarks
            );
            $this->acctgAccountVoucherRepository->update_document($documentID, $details);
            return response()->json([
                'text' => 'The request has been successfully disapproved.',
                'type' => 'success',
                'status' => 'success'
            ]);
        }
    }

    public function fetch_remarks(Request $request, $documentID)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'remarks' => $this->acctgAccountVoucherRepository->find_document($documentID)->disapproved_remarks
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
        PDF::MultiCell(35, 5, $vouherDate[$request->get('type')], 'R', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
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
}
