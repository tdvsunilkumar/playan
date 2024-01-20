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
use App\Interfaces\ReportAcctgLedgerInterface;
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

class ReportAcctgLedgerController extends Controller
{
    private ReportAcctgLedgerInterface $reportAcctgLedgerRepository;
    private $carbon;
    private $slugs;

    public function __construct(ReportAcctgLedgerInterface $reportAcctgLedgerRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->reportAcctgLedgerRepository = $reportAcctgLedgerRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/ledgers';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $types = ['' => 'Select a ledger type', 'general-ledger' => 'General Ledger', 'subsidiary-ledger' => 'Subsidiary Ledger'];
        $codes = ['' => 'Select an account code'];
        $fund_codes = $this->reportAcctgLedgerRepository->allFundCodes();
        $categories = ['' => 'select a category', 'Clients' => 'Clients', 'Suppliers' => 'Suppliers'];
        $name = ['' => 'Select a name'];
        $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.accounting.ledgers.index')->with(compact('types', 'codes', 'fund_codes', 'categories', 'name', 'export_as', 'orders'));
    }

    public function reload(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgLedgerRepository->reload($request->get('type')),
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
            'data' => $this->reportAcctgLedgerRepository->reload_category_name($request->get('category')),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function export_to_excel(Request $request)
    {
        $columns = ['A','B','C','D','E','F','G','H','I','J','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AY','AZ','BA','BB','BC','BD'];
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
        $ledgerType = $request->ledger_type == 'subsidiary-ledger' ? 'SUBSIDIARY LEDGER' : 'GENERAL LEDGER';
        $funds = $request->fund_code_id ? AcctgFundCode::find($request->fund_code_id)->description : '';
        $category = $request->category ? $request->category : '';
        $dates = 'As of '.date('d-M-Y', strtotime($request->date_from)).' to '.date('d-M-Y', strtotime($request->date_to));

        $style = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $results = $this->reportAcctgLedgerRepository->get($request);
        $excel = PHPExcel_IOFactory::createReader('Excel2007');
        $excel = $excel->load('templates/excel/ledger.xls'); 

        $file = 'ledger.xls';
        if (file_exists($file)) {
            unlink($file);
        }

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setCellValue('A5', $ledgerType);
        $excel->getActiveSheet()->setCellValue('A6', $funds);
        $excel->getActiveSheet()->setCellValue('A7', $dates);
        $excel->getActiveSheet()->setCellValue('C10', $titles);
        $excel->getActiveSheet()->setCellValue('F10', $codes);
        $excel->getActiveSheet()->setCellValue('C11', $names);
        $excel->getActiveSheet()->setCellValue('F11', $category);

        if (!empty($results)) {
            $row = 14;
            foreach ($results as $res) {

                $column = 0;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->jev_no)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->posted)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->payee)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->particulars)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->debit)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->credit)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, strval($res->balance))->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $row++;              
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save($file);

        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return response()->noContent();
        }
    }

    public function export_to_pdf(Request $request)
    {

        PDF::SetTitle('Ledgers Report');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // max width 335.6
        // // {{title}} {{codes}}
        if ($request->get('ledger_type') == 'general-ledger') {
            $titles = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->description : '';
            $codes = !empty($request->code) ? AcctgAccountGeneralLedger::find($request->code)->code : '';
        } else {
            $titles = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->description : '';
            $codes = !empty($request->get('code')) ? AcctgAccountSubsidiaryLedger::find($request->get('code'))->code : '';
        }
        
        // // {{names}}
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
        $ledger_type = $request->ledger_type == 'subsidiary-ledger' ? 'SUBSIDIARY' : 'GENERAL';
        $ledger_column = $request->ledger_type == 'subsidiary-ledger' ? 'SL ACCOUNT' : 'GL ACCOUNT';
        $date_from = date('d-M-Y', strtotime($request->input('date_from')));
        $date_to = date('d-M-Y', strtotime($request->input('date_to')));
        $category = $request->category;

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 40, $y = 10, $w = 30, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Capital of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',15);
        PDF::MultiCell(0, 0, $ledger_type." LEDGER", '', 'C', 0, 1, '', '', true, 0, true);

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
        PDF::MultiCell(33.56, 8, "JEV NO.", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.56, 8, $ledger_column, 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.56, 8, "DATE", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(67.12, 8, "PAYEE/PAYER", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(67.12, 8, "PARTICULARS", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.56, 8, "DEBIT", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.56, 8, "CREDIT", 1, 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.56, 8, "BALANCE", 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');

        PDF::SetFont('helvetica','',9);

            $rows = $this->reportAcctgLedgerRepository->get($request);
            // 335.6
            foreach ($rows as $value) {
                // max strlen = 35 5 height per 35strlen 
                // $value->particulars
                // if amount is 0 display empty

                $base_height = 10;
                $particular_len = strlen($value->particulars);
                $payee_len = strlen($value->payee);
                $account_len = strlen($value->account_code);

                $account_quotient = $account_len / 17;
                $payee_quotient =  $payee_len / 35;
                $particular_quotient =  $particular_len / 35;
                

                // $quotient = ($payee_quotient >= $particular_quotient) ? $payee_quotient : $particular_quotient; 
                if ($account_quotient >= $payee_quotient && $account_quotient >= $particular_quotient)
                {
                    $quotient = $account_quotient;
                }
                elseif ($payee_quotient >= $account_quotient && $payee_quotient >= $particular_quotient)
                {
                    $quotient = $payee_quotient;
                }
                elseif ($particular_quotient >= $account_quotient && $particular_quotient >= $particular_quotient)
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
                
                // dd($cell_height);
                $y = PDF::gety(); 

                if ($y > 182)
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
                PDF::MultiCell(33.56, $cell_height, $value->jev_no. " " . $value->type, 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(33.56, $cell_height, $value->account_code." 
".$value->account_desc, 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(33.56, $cell_height, $value->posted, 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(67.12, $cell_height, $value->payee, 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(67.12, $cell_height, $value->particulars, 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(33.56, $cell_height, ($value->debit == 0) ? "" : $value->debit , 1, 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(33.56, $cell_height, ($value->credit == 0) ? "" : $value->credit , 1, 'R', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                PDF::MultiCell(33.56, $cell_height, ($value->balance == 0) ? "" : $value->balance , 1, 'R', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height, $valign='M');
                
            }
        $prepared = $this->reportAcctgLedgerRepository->get_prepared_by();
        $certified = $this->reportAcctgLedgerRepository->get_certified_by();

        // PDF::ln();
        // $x = PDF::gety();
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
        $rows = $this->reportAcctgLedgerRepository->get($request);
        $prepared = $this->reportAcctgLedgerRepository->get_prepared_by();
        $certified = $this->reportAcctgLedgerRepository->get_certified_by();
        return view('reports.accounting.ledgers.pageview')->with(compact('prepared', 'certified', 'titles', 'codes', 'names', 'funds', 'rows'));
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }

    public function money_formatx($money)
    {
        return floor(($money*100))/100;
    }
}
