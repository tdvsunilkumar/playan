<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\AcctgAccountSubsidiaryLedger;
use App\Models\AcctgFundCode;
use App\Models\GsoSupplier;
use App\Models\Client;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ReportAcctgJournalInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use Jenssegers\Agent\Agent;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Style_Font;
use PHPExcel_Style_Border;
use \PDF;

class ReportAcctgJournalController extends Controller
{
    private ReportAcctgJournalInterface $reportAcctgJournalRepository;
    private $carbon;
    private $slugs;

    public function __construct(ReportAcctgJournalInterface $reportAcctgJournalRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->reportAcctgJournalRepository = $reportAcctgJournalRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/journals';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $categories = ['' => 'Select a journal', 'ap-journal' => 'Accounts Payable Journal', 'check-journal' => 'Check Disbursement Journal', 'cash-journal' => 'Cash Disbursement Journal', 'debit-memo-journal' => 'Debit Memo Journal', 'cash-receipt-journal' => 'Cash Receipts Journal'];
        $fund_codes = $this->reportAcctgJournalRepository->allFundCodes();
        $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.accounting.journals.index')->with(compact('categories', 'fund_codes', 'export_as', 'orders'));
    }

    public function reload(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgJournalRepository->reload($request->get('type')),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function reload_category_name(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgJournalRepository->reload_category_name($request->get('category')),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function export_to_excel(Request $request)
    {
        
    }

    public function export_to_pdf(Request $request)
    {

        // dd($request);
        $categories = ['ap-journal' => 'Accounts Payable Journal', 'check-journal' => 'Check Disbursement Journal', 'cash-journal' => 'Cash Disbursement Journal', 'debit-memo-journal' => 'Debit Memo Journal', 'cash-receipt-journal' => 'Cash Receipts Journal'];
        $category = $categories[$request->category];
        // dd($category);
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $rows = $this->reportAcctgJournalRepository->get($request); 
        // dd($rows); 
        $prepared = $this->reportAcctgJournalRepository->get_prepared_by();
        $certified = $this->reportAcctgJournalRepository->get_certified_by();
        $account_payable = $this->reportAcctgJournalRepository->get_account_payable();
        $cash_in_bank = $this->reportAcctgJournalRepository->get_cash_in_bank();
        $advanced_payment = $this->reportAcctgJournalRepository->get_advanced_payment();
        $petty_cash = $this->reportAcctgJournalRepository->get_petty_cash();
        $disbursements = $this->reportAcctgJournalRepository->get_disbursements($request);
        $memos = $this->reportAcctgJournalRepository->get_debit_memos($request);

        PDF::SetTitle('Fixed Asset Report');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // max width 335.6

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 40, $y = 10, $w = 30, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Capital of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',15);
        PDF::MultiCell(0, 0, strtoupper($category), '', 'C', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, $funds, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "As of <u>" .date('d-M-Y', strtotime($date_from))."</u> to <u>".date('d-M-Y', strtotime($date_to))."</u>" , 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(20);

        switch ($category) {
            case 'Accounts Payable Journal':
                PDF::MultiCell(33.56, $cell_height * 4, "DATE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 4, 'M');
                PDF::MultiCell(33.56, $cell_height * 4, "JEV NO.", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 4, 'M');
                PDF::MultiCell(67.12, $cell_height * 4, "PAYEE/PAYER", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 4, 'M');
                PDF::MultiCell(67.12, $cell_height * 4, "PARTICULARS", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 4, 'M');
                PDF::MultiCell(67.12, $cell_height + 4 , "DEBIT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 4, 'M');
                PDF::MultiCell(67.12, $cell_height + 4, "CREDIT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 4, 'M');

                PDF::MultiCell(0, $cell_height + 4, "", 0, 'l', 0, 1, '', '', true, 0, false, true, $cell_height + 4, 'M'); // 
                PDF::MultiCell(201.36, 0 , "" , 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='M'); //
                
                PDF::MultiCell(33.56, $cell_height + 6 , "ACCOUNT CODE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 6, 'M');
                PDF::MultiCell(33.56, $cell_height + 6 , "AMOUNT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 6, 'M');
                PDF::MultiCell(67.12, $cell_height + 6, "ACCOUNT PAYABLES
20101010", 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height + 6, 'M');

                
                

                // dd($rows);
                $totalAmt = 0;
                foreach ($rows as $row)
                {
                    $base_height = 5;
                    $particular_len = strlen($row->particulars);
                    $payee_len = strlen($row->payee);

                    $payee_quotient =  $payee_len / 35;
                    $particular_quotient =  $particular_len / 35;
                    
                    if ($payee_quotient >= $particular_quotient)
                    {
                        $quotient = $payee_quotient;
                    }
                    elseif ($particular_quotient >= $particular_quotient)
                    {
                        $quotient = $particular_quotient;
                    }
                    else
                    {
                        $quotient = 1;
                    }

                    $whole = ceil($quotient);
                    $multiplier = $whole * 5;
                    $cell_height = $base_height + $multiplier;


                    $account_codes = ""; $account_codes_count = 0; $lastIndex = count($row->payables);
                    foreach ($row->payables as $payable) {
                        $account_codes .= $payable->account_code;
                        $account_codes_count++;
                        if ($lastIndex > $account_codes_count ) {
                            $account_codes .= '<br/>
                            ';
                        }
                    }
                    
                    $account_amount = "";$totalRowAmt = 0;$account_amount_count = 0; 
                    foreach ($row->payables as $payable) {
                        // $payable->account_code;
                        $totalRowAmt += floatval($payable->amount); 
                        $totalAmt += floatval($payable->amount);
                        $account_amount .= $this->money_format($payable->amount);
                        $account_amount_count++;
                        if ($lastIndex > $account_amount_count ) {
                            $account_amount .= '<br/>';
                        }
                        } 

                    $cell_height = $base_height * $account_codes_count;
                    

                    $y = PDF::gety(); 

                    if ($y > 184)
                    {
                        
                        PDF::AddPage('L', 'LEGAL');
                        PDF::SetFont('helvetica','B',9);
                        PDF::MultiCell(33.56, 8, "JEV NO.", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(33.56, 8, $ledger_column, 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(33.56, 8, "DATE", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(67.12, 8, "PAYEE/PAYER", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(67.12, 8, "PARTICULARS", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(33.56, 8, "DEBIT", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(33.56, 8, "CREDIT", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                        PDF::MultiCell(33.56, 8, "BALANCE", 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
                        PDF::SetFont('helvetica','',9);
                    }

                    PDF::MultiCell(33.56, $cell_height, $row->date." ".$cell_height, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(33.56, $cell_height, $row->jev_no, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(67.12, $cell_height, $row->payee, 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(67.12, $cell_height, $row->particulars." ".$cell_height, 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    
                    PDF::setCellPaddings( $left = '', $top = '2.5', $right = '', $bottom = '');
                    PDF::MultiCell(33.56, $cell_height , "<br/>".$account_codes, 1, 'C', 0, 0, '', '', true, 0, true, true, $cell_height, 'M');
                    PDF::MultiCell(33.56, $cell_height , "<br/>".$account_amount, 1, 'R', 0, 0, '', '', true, 0, true, true, $cell_height, 'M');
                    
                    PDF::setCellPaddings( $left = '', $top = '', $right = '', $bottom = '');
                    PDF::MultiCell(67.12, $cell_height , $this->money_format($totalRowAmt), 1, 'R', 0, 1, '', '', true, 0, false, true, $cell_height, 'M');
                    
                }
                    
                $cell_height = 10;
                PDF::MultiCell(234.92, $cell_height ,"TOTAL AMOUNT", 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                PDF::MultiCell(33.56, $cell_height , $this->money_format($totalAmt) , 1, 'R', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                PDF::MultiCell(67.12, $cell_height , $this->money_format($totalAmt) , 1, 'R', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                
                break;
            
            default:
                # code...
                break;
        }
        
        PDF::SetXY(10,185);
        PDF::MultiCell(80, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Approved By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(80, 5,ucwords($prepared->fullname), '', 0, 'C');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::Cell(0, 5, ucwords($certified->fullname), '', 1, 'C');

        PDF::MultiCell(80, 5, ucwords($prepared->designation), 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, ucwords($certified->designation), "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        // ob_end_clean();
        PDF::Output('ledgers_report.pdf');
    }

    public function export_to_pageview(Request $request)
    {         
        $categories = ['' => 'Select a journal', 'ap-journal' => 'Accounts Payable Journal', 'check-journal' => 'Check Disbursement Journal', 'cash-journal' => 'Cash Disbursement Journal', 'debit-memo-journal' => 'Debit Memo Journal', 'cash-receipt-journal' => 'Cash Receipts Journal'];
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $rows = $this->reportAcctgJournalRepository->get($request);  
        $prepared = $this->reportAcctgJournalRepository->get_prepared_by();
        $certified = $this->reportAcctgJournalRepository->get_certified_by();
        $account_payable = $this->reportAcctgJournalRepository->get_account_payable();
        $cash_in_bank = $this->reportAcctgJournalRepository->get_cash_in_bank();
        $advanced_payment = $this->reportAcctgJournalRepository->get_advanced_payment();
        $petty_cash = $this->reportAcctgJournalRepository->get_petty_cash();
        $disbursements = $this->reportAcctgJournalRepository->get_disbursements($request);
        $memos = $this->reportAcctgJournalRepository->get_debit_memos($request);
        return view('reports.accounting.journals.pageview-'.$request->category)->with(compact('petty_cash', 'advanced_payment', 'disbursements', 'memos', 'cash_in_bank', 'account_payable', 'funds', 'rows', 'categories', 'prepared', 'certified'));
    }
    
    public function get_cash_disbursement($voucherNo, $glID)
    {
        return $this->money_format($this->reportAcctgJournalRepository->get_cash_disbursement($voucherNo, $glID));
    }

    public function get_check_disbursement($voucherNo, $slID)
    {   
        return $this->reportAcctgJournalRepository->get_check_disbursement($voucherNo, $slID);
    }

    public function get_check_disbursement_debit_memo($id, $slID)
    {   
        return $this->reportAcctgJournalRepository->get_check_disbursement_debit_memo($id, $slID);
    }

    public function get_debit_memo($voucherNo, $slID)
    {
        return $this->money_format($this->reportAcctgJournalRepository->get_debit_memo($voucherNo, $slID));
    }

    public function money_format($money)
    {
        return ($money > 0) ? number_format(floor(($money*100))/100, 2) : '';
    }
}
