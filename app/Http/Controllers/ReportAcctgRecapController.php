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
use App\Interfaces\ReportAcctgRecapInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use Jenssegers\Agent\Agent;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Style_Font;
use PHPExcel_Style_Border;
use \PDF;

class ReportAcctgRecapController extends Controller
{
    private ReportAcctgRecapInterface $reportAcctgRecapRepository;
    private $carbon;
    private $slugs;

    public function __construct(ReportAcctgRecapInterface $reportAcctgRecapRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->reportAcctgRecapRepository = $reportAcctgRecapRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/recap';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $categories = ['' => 'Select a recap', 'ap-recap' => 'Accounts Payable Recap', 'check-recap' => 'Check Disbursement Recap', 'cash-recap' => 'Cash Disbursement Recap', 'debit-memo-recap' => 'Debit Memo Recap', 'cash-receipt-recap' => 'Cash Receipts Recap'];
        $fund_codes = $this->reportAcctgRecapRepository->allFundCodes();
        $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.accounting.recap.index')->with(compact('categories', 'fund_codes', 'export_as', 'orders'));
    }

    public function reload(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgRecapRepository->reload($request->get('type')),
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
            'data' => $this->reportAcctgRecapRepository->reload_category_name($request->get('category')),
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
        $categories = ['' => 'Select a recap', 'ap-recap' => 'Accounts Payable Recap', 'check-recap' => 'Check Disbursement Recap', 'cash-recap' => 'Cash Disbursement Recap', 'debit-memo-recap' => 'Debit Memo Recap', 'cash-receipt-recap' => 'Cash Receipts Recap'];
        $category = $categories[$request->category];
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $rows = $this->reportAcctgRecapRepository->get($request);  
        $prepared = $this->reportAcctgRecapRepository->get_prepared_by();
        $certified = $this->reportAcctgRecapRepository->get_certified_by();
        $account_payable = $this->reportAcctgRecapRepository->get_account_payable();
        $cash_in_bank = $this->reportAcctgRecapRepository->get_cash_in_bank();
        $advanced_payment = $this->reportAcctgRecapRepository->get_advanced_payment();
        $petty_cash = $this->reportAcctgRecapRepository->get_petty_cash();
        $disbursements = $this->reportAcctgRecapRepository->get_disbursements($request);
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        PDF::SetTitle($category.' Recap Report');    
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

        // header
        PDF::MultiCell(83.9, $cell_height * 3, "ACCOUNT TITLE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 3, 'M');
        PDF::MultiCell(83.9, $cell_height * 3, "ACCOUNT CODE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 3, 'M');
        PDF::MultiCell(83.9, $cell_height * 3, "DEBIT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 3, 'M');
        PDF::MultiCell(83.9, $cell_height * 3, "CREDIT"."
".strtoupper($account_payable->description)."
".$account_payable->code, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 3, 'M');
        PDF::ln();
        
        if (!empty($rows))
        {
            $totalDebit = 0; $totalCredit = 0; 
            foreach ($rows as $row)
            {
                    $base_height = 5;
                    $title_len = strlen($row->account_desc);
                    // $title_len = strlen("asdasdasdasdasdasdasdasdasdasdsasdasdasdasdasdas asdasdasdasdasdasdasdasdasdasdsasdasdasdasdasdas s");

                    $title_quotient =  $title_len / 51;
                    
                    $whole = ceil($title_quotient);
                    
                    $multiplier = $whole * 5;
                    
                    $cell_height = $base_height + $multiplier; 
                    // dd($cell_height);
                    // $cell_height = $base_height * $account_codes_count; "asdasdasdasdasdasdasdasdasdasdsasdasdasdasdasdas"

                $y = PDF::gety(); 

                if ($y > 184)
                {
                    
                    PDF::AddPage('L', 'LEGAL');
                    PDF::SetFont('helvetica','B',9);
                    PDF::MultiCell(83.9, $cell_height, $row->account_desc, 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(83.9, $cell_height, $row->account_code, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(83.9, $cell_height, $this->money_format($row->debit), 1, 'R', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                    PDF::MultiCell(83.9, $cell_height, "", 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height, 'M');
                    
                    PDF::SetFont('helvetica','',9);
                }
                
                PDF::SetFont('helvetica','',9); 
                PDF::MultiCell(83.9, $cell_height, $row->account_desc, 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                PDF::MultiCell(83.9, $cell_height, $row->account_code, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                PDF::MultiCell(83.9, $cell_height, $this->money_format($row->debit), 1, 'R', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
                PDF::MultiCell(83.9, $cell_height, "", 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height, 'M');
                
                
                    if($row->debit > 0) {
                        $totalDebit += floatval($row->debit);
                    }
                    if($row->credit > 0) {
                        $totalCredit += floatval($row->credit);
                    }
                
            }
                
            PDF::MultiCell(83.9, $base_height * 2, $account_payable->description, 1, 'L', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            PDF::MultiCell(83.9, $base_height * 2, $account_payable->code, 1, 'C', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            PDF::MultiCell(83.9, $base_height * 2, "", 1, 'C', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            PDF::MultiCell(83.9, $base_height * 2, $this->money_format($totalCredit), 1, 'R', 0, 1, '', '', true, 0, false, true, $base_height * 2, 'M');
            
            PDF::MultiCell(167.8, $base_height * 2, "TOTAL AMOUNT", 1, 'L', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            PDF::MultiCell(83.9, $base_height * 2, $this->money_format($totalDebit), 1, 'R', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            PDF::MultiCell(83.9, $base_height * 2, $this->money_format($totalCredit), 1, 'R', 0, 0, '', '', true, 0, false, true, $base_height * 2, 'M');
            
        }
            
        else
        {
            PDF::MultiCell(335.6, $cell_height * 3, "NO RECORDS FOUND", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 3, 'M');
        }
            
        
                                
    
    
        // footer
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

        PDF::Output('recap_report.pdf');
    }

    public function export_to_pageview(Request $request)
    {         
        $categories = ['' => 'Select a recap', 'ap-recap' => 'Accounts Payable Recap', 'check-recap' => 'Check Disbursement Recap', 'cash-recap' => 'Cash Disbursement Recap', 'debit-memo-recap' => 'Debit Memo Recap', 'cash-receipt-recap' => 'Cash Receipts Recap'];
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $rows = $this->reportAcctgRecapRepository->get($request);  
        $prepared = $this->reportAcctgRecapRepository->get_prepared_by();
        $certified = $this->reportAcctgRecapRepository->get_certified_by();
        $account_payable = $this->reportAcctgRecapRepository->get_account_payable();
        $cash_in_bank = $this->reportAcctgRecapRepository->get_cash_in_bank();
        $advanced_payment = $this->reportAcctgRecapRepository->get_advanced_payment();
        $petty_cash = $this->reportAcctgRecapRepository->get_petty_cash();
        $disbursements = $this->reportAcctgRecapRepository->get_disbursements($request);
        return view('reports.accounting.recap.pageview-'.$request->category)->with(compact('petty_cash', 'advanced_payment', 'disbursements', 'cash_in_bank', 'account_payable', 'funds', 'rows', 'categories', 'prepared', 'certified'));
    }
    
    public function get_cash_disbursement($voucherNo, $glID)
    {
        return $this->money_format($this->reportAcctgRecapRepository->get_cash_disbursement($voucherNo, $glID));
    }

    public function get_check_disbursement($voucherNo, $slID)
    {   
        return $this->money_format($this->reportAcctgRecapRepository->get_check_disbursement($voucherNo, $slID));
    }

    public function money_format($money)
    {
        return ($money > 0) ? currency_format($money) : '';
    }
}
