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
use App\Interfaces\ReportAcctgTrialBalanceInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Style_Font;
use PHPExcel_Style_Border;
// use \Mpdf\Mpdf as PDF;
use \PDF;
use TCPDF_FONTS;

class ReportAcctgTrialBalanceController extends Controller
{
    private ReportAcctgTrialBalanceInterface $reportAcctgTrialBalanceRepository;
    private $carbon;
    private $slugs;

    public function __construct(ReportAcctgTrialBalanceInterface $reportAcctgTrialBalanceRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->reportAcctgTrialBalanceRepository = $reportAcctgTrialBalanceRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/trial-balance';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $codes = $this->reportAcctgTrialBalanceRepository->allGLAccounts();
        $fund_codes = $this->reportAcctgTrialBalanceRepository->allFundCodes();
        $categories = ['' => 'select a category', 'Clients' => 'Clients', 'Suppliers' => 'Suppliers'];
        $name = ['' => 'Select a name'];
        $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.accounting.trial-balance.index')->with(compact('codes', 'fund_codes', 'categories', 'name', 'export_as', 'orders'));
    }

    public function reload(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgTrialBalanceRepository->reload($request->get('type')),
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
            'data' => $this->reportAcctgTrialBalanceRepository->reload_category_name($request->get('category')),
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
        PDF::SetTitle('Ledgers Report');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LEGAL');
        PDF::SetFont('helvetica','',9);
        // max width 335.6

        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }
        $names = '';
        if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $suppliers = GsoSupplier::find($request->name);
                $names .= $suppliers->business_name ? ucwords($suppliers->business_name).' ' : '';
                $names .= $suppliers->branch_name ? '('.ucwords($suppliers->branch_name).')' : '';
            }
        } else if ($request->category == 'Clients') {
            if (!empty($request->name)) {
                $clients = Client::find($request->name);
                $names .= $clients->rpo_first_name ? ucwords($clients->rpo_first_name).' ' : '';
                $names .= $clients->rpo_middle_name ? ucwords($clients->rpo_middle_name).' ' : '';
                $names .= $clients->rpo_custom_last_name ? ucwords($clients->rpo_custom_last_name) : '';
            }
        }
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        $rows = $this->reportAcctgTrialBalanceRepository->get($request);
        $prepared = $this->reportAcctgTrialBalanceRepository->get_prepared_by();
        $certified = $this->reportAcctgTrialBalanceRepository->get_certified_by();
        $category = $request->category;
        $date_from = date('d-M-Y', strtotime($request->input('date_from')));
        $date_to = date('d-M-Y', strtotime($request->input('date_to')));

        // dd($request);
        // max width 335.6

        // dd($rows);

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 40, $y = 10, $w = 30, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Capital of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',15);
        PDF::MultiCell(0, 0, "TRIAL BALANCE", '', 'C', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',11);
        PDF::MultiCell(0, 0, $funds, 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "As of <u>" .$date_from."</u> to <u>".$date_to."</u>" , 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(30, 0, "Account's Title: ", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(220, 0, $titles, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Account Code: ", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "$codes", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Name: ", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(220, 0, $names, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Category: ", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $category, 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(134.24, 10, "ACCOUNTS TITLE", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(67.12, 10, "ACCOUNT CODE", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(67.12, 10, "DEBIT BALANCE", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(67.12, 10, "CREDIT BALANCE", 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');

        
        foreach ($rows as $value) {
            // max strlen = 60 5 height per 35strlen 
            // $x = "SJFKEJFKSNEKFJVIEJVNDSLD LSD SDWQEQWERL LWJEORJGB SJEPFJEI JWJE QJSJ JWEJ PWJS QWENVKDNVKDNVKDNVSKSKSJWJS SKS DKSKAKWKEKKKKO";
            
            
            $base_height = 4;
            $title_len = strlen($value->title);
            $title_quotient =  $title_len / 62;
            
            $whole = ceil($title_quotient);
            $multiplier = $whole * 4;
            $cell_height = $base_height + $multiplier;
            
            // dd($cell_height);
            $y = PDF::gety(); 

            if ($y > 184)
            {
                
                PDF::AddPage('L', 'LEGAL');
                PDF::SetFont('helvetica','B',9);
                PDF::MultiCell(134.24, 8, "ACCOUNTS TITLE", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                PDF::MultiCell(67.12, 8, "ACCOUNT CODE", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                PDF::MultiCell(67.12, 8, "DEBIT BALANCE", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
                PDF::MultiCell(67.12, 8, "CREDIT BALANCE", 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
                PDF::SetFont('helvetica','',9);
            }
            PDF::SetFont('helvetica','',9);
            PDF::MultiCell(134.24, $cell_height, $value->title , 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
            PDF::MultiCell(67.12, $cell_height, $value->code , 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
            PDF::MultiCell(67.12, $cell_height, $value->debit , 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height, 'M');
            PDF::MultiCell(67.12, $cell_height, $value->credit , 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height, 'M');
            
        }

        PDF::SetXY(10,185);
        PDF::MultiCell(80, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Approved By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(80, 5, ucwords($prepared->fullname), '', 0, 'C');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::Cell(0, 5, ucwords($certified->fullname), '', 1, 'C');

        PDF::MultiCell(80, 5, ucwords($prepared->designation) , 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, ucwords($certified->designation), "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        ob_end_clean();
        PDF::Output('ledgers_report.pdf'); 

    }

    public function export_to_pageview(Request $request)
    {  
        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }
        $names = '';
        if ($request->category == 'Suppliers') {
            if (!empty($request->name)) {
                $suppliers = GsoSupplier::find($request->name);
                $names .= $suppliers->business_name ? ucwords($suppliers->business_name).' ' : '';
                $names .= $suppliers->branch_name ? '('.ucwords($suppliers->branch_name).')' : '';
            }
        } else if ($request->category == 'Clients') {
            if (!empty($request->name)) {
                $clients = Client::find($request->name);
                $names .= $clients->rpo_first_name ? ucwords($clients->rpo_first_name).' ' : '';
                $names .= $clients->rpo_middle_name ? ucwords($clients->rpo_middle_name).' ' : '';
                $names .= $clients->rpo_custom_last_name ? ucwords($clients->rpo_custom_last_name) : '';
            }
        }
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        $rows = $this->reportAcctgTrialBalanceRepository->get($request);
        $prepared = $this->reportAcctgTrialBalanceRepository->get_prepared_by();
        $certified = $this->reportAcctgTrialBalanceRepository->get_certified_by();
        return view('reports.accounting.trial-balance.pageview')->with(compact('prepared', 'certified', 'titles', 'codes', 'names', 'funds', 'rows'));
    }

    public function money_format($money)
    {
        return ($money != '') ? number_format(floor(($money*100))/100, 2) : '';
    }
}
