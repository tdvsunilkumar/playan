<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ReportTreasuryCollectionInterface;
use App\Interfaces\CtoCollectionInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Style_Font;
use PHPExcel_Style_Border;
use PDF;
use TCPDF_FONTS;

class ReportTreasuryCollectionController extends Controller
{
    private ReportTreasuryCollectionInterface $reportTreausryCollectionRepository;
    private CtoCollectionInterface $ctoCollectionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        ReportTreasuryCollectionInterface $reportTreausryCollectionRepository, 
        CtoCollectionInterface $ctoCollectionRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->reportTreausryCollectionRepository = $reportTreausryCollectionRepository;
        $this->ctoCollectionRepository = $ctoCollectionRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/treasury/collections-and-deposits';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $fund_codes = $this->reportTreausryCollectionRepository->allFundCodes();
        // $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $export_as = ['' => 'Select export type', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        $officers = $this->ctoCollectionRepository->allCtoOfficers();
        return view('reports.treasury.collections.index')->with(compact('fund_codes', 'officers', 'export_as', 'orders'));
    }

    public function export_to_pdf(Request $request) // daily collection (detailed)
    {
        PDF::SetTitle('Daily Collection');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;

        $official = "Susan A. Fajardo";
        $collection_date = "April 11, 2023";

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, 0, "OFFICE OF THE CITY TREASURER", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<B>DAILY COLLECTION REPORT</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $collection_date, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        // column header
        PDF::MultiCell(13, 0, "<B>No.</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, "<B>O.R. No.</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<B>Taxpayer's Name</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<B>Address / Account Description</B>", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "<B>Amount</B>", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        // <--- Data --->
        
        $collections = $this->reportTreausryCollectionRepository->get_details($request->get('fund'), $request->get('officer_id'), $request->get('date_from'), $request->get('date_to'));

        if (!empty($collections)) {
            $rows = 0; $totalAmt = 0;
            foreach ($collections as $collection) {
                $rows++;
                PDF::MultiCell(13, 0, $rows, 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(20, 0, $collection->or_no, 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(60, 0, ucwords(strtolower($collection->taxpayer_name)), 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                PDF::Cell(60, 0, "BRGY. ATATE, PALAYAN CITY", 0, 0, 'L');
                PDF::ln();
                $totalBreakdown = 0;
                $breakdowns = $this->reportTreausryCollectionRepository->get_breakdown_details($collection->or_no);
                foreach ($breakdowns as $breakdown) {
                    PDF::MultiCell(102, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(60, 0, $breakdown->sl_account->description, 0, 'L', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
                    PDF::MultiCell(30.9, 0, number_format($breakdown->amount,2), 0, 'R', 0, 1, '', '', true, 0, true);
                    $totalBreakdown += floatval($breakdown->amount);
                }
                PDF::MultiCell(165, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
                PDF::MultiCell(30.9, 0, "<b>".number_format($totalBreakdown,2)."</b>", "T", 'R', 0, 0, '', '', true, 0, true);
                PDF::ln();
                $totalAmt += floatval($totalBreakdown);
            }
        }       

        // footer
        PDF::MultiCell(0, 0, "", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(50, 0, "No. of Receipts : ".$rows."", "B", 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, "Total : ", "B", 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>".number_format($totalAmt,2)."</b>", "B", 'R', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "By:", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, $official, 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Collector", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::Output('daily_collection.pdf'); 
    }
}
