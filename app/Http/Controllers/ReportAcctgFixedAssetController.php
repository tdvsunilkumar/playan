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
use App\Interfaces\ReportAcctgFixedAssetInterface;
use App\Interfaces\AcctgFixedAssetInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Style_Font;
use PHPExcel_Style_Border;
use \PDF;

class ReportAcctgFixedAssetController extends Controller
{
    private ReportAcctgFixedAssetInterface $reportAcctgFixedAssetRepository;
    private AcctgFixedAssetInterface $acctgFixedAssetRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        ReportAcctgFixedAssetInterface $reportAcctgFixedAssetRepository, 
        AcctgFixedAssetInterface $acctgFixedAssetRepository,
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->reportAcctgFixedAssetRepository = $reportAcctgFixedAssetRepository;
        $this->acctgFixedAssetRepository = $acctgFixedAssetRepository;
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/fixed-assets';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $this->acctgFixedAssetRepository->depreciate($this->carbon::now());
        $categories = ['' => 'Select a ledger category', 'detailed' => 'Detailed', 'summary' => 'Summary'];
        $types = $this->reportAcctgFixedAssetRepository->allProperties();
        $fund_codes = $this->reportAcctgFixedAssetRepository->allFundCodes();
        $export_as = ['' => 'Select export type', 'pageview' =>'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        $statuses = ['' => 'select a status', 'active' => 'Active', 'disposed' => 'Disposed', 'sold' => 'Sold', 'all' => 'All'];
        return view('reports.accounting.fixed-assets.index')->with(compact('categories', 'types', 'fund_codes', 'export_as', 'orders', 'statuses'));
    }

    public function reload(Request $request)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->reportAcctgFixedAssetRepository->reload($request->get('type')),
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
            'data' => $this->reportAcctgFixedAssetRepository->reload_category_name($request->get('category')),
            'title' => 'Well done!',
            'text' => 'The group menu has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function export_to_excel(Request $request)
    {
        $columns = ['A','B','C','D','E','F','G','H','I','J','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AY','AZ','BA','BB','BC','BD'];
        $rows = $this->reportAcctgFixedAssetRepository->get($request);
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $categories = $request->category;
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $fund_codes = AcctgFundCode::where('is_active', 1)->get();
        $prepared = $this->reportAcctgFixedAssetRepository->get_prepared_by();
        $certified = $this->reportAcctgFixedAssetRepository->get_certified_by();
        
        $dates = 'As of '.date('d-M-Y', strtotime($request->date_from)).' to '.date('d-M-Y', strtotime($request->date_to));

        $style = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ];

        $results = $this->reportAcctgFixedAssetRepository->get($request);
        $excel = PHPExcel_IOFactory::createReader('Excel2007');
        $excel = $excel->load('templates/excel/qwe.xlsx'); 
        
        $file = 'qwe.xls';
        
        if (file_exists($file)) {
            unlink($file);
        }

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setCellValue('A5', $categories);
        $excel->getActiveSheet()->setCellValue('A6', $funds);
        $excel->getActiveSheet()->setCellValue('A6', $dates);
        // $excel->getActiveSheet()->setCellValue('C10', $titles);
        // $excel->getActiveSheet()->setCellValue('F10', $codes);
        // $excel->getActiveSheet()->setCellValue('C11', $names);
        // $excel->getActiveSheet()->setCellValue('F11', $category);

        $totalUnitCost = 0; $totalDepreciation = 0; $totalBookValue = 0;

        if (!empty($results)) {
            $row = 11;
            foreach($rows as $res) {
                // DD($row->asset_no);
                // $asset_no = (string) $row->asset_no;
                $column = 0;
                // dd($res);
                $totalUnitCost += ($res->unit_cost > 0) ? floatval($res->unit_cost) : 0;
                $totalDepreciation += ($res->depreciation_cost > 0) ? floatval($res->depreciation_cost) : 0;
                $totalBookValue += ($res->book_value > 0) ? floatval($res->book_value) : 0;
                
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->asset_no)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->type)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->type)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->date_acquired)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++;
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->life_span)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->date_ended)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->status)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $res->salvage_value)->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $this->money_format($res->unit_cost))->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $this->money_format($res->depreciation_cost))->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $excel->getActiveSheet()->setCellValue($columns[$column].''.$row, $this->money_format($res->book_value))->getStyle($columns[$column].''.$row)->applyFromArray($style); $column++; 
                $row++;              
            }
        }
        // dd($file);
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
        
        $rows = $this->reportAcctgFixedAssetRepository->get($request);
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $categories = $request->category;
        // dd($categories);
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $fund_codes = AcctgFundCode::where('is_active', 1)->get();
        $prepared = $this->reportAcctgFixedAssetRepository->get_prepared_by();
        $certified = $this->reportAcctgFixedAssetRepository->get_certified_by();

        // dd($rows);
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
        PDF::MultiCell(0, 0, "FIXED ASSET ".strtoupper($categories), '', 'C', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "As of <u>" .date('d-M-Y', strtotime($date_from))."</u> to <u>".date('d-M-Y', strtotime($date_to))."</u>" , 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(20);

        // max width 335.6

        switch ($categories) {
            case 'summary':
                PDF::MultiCell(33.56, $cell_height * 4, "TYPE OF ASSET" , 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height * 4, $valign='M');
                PDF::MultiCell(67.12, $cell_height * 2, "GENERAL FUND PROPER", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(67.12, $cell_height * 2, "SPECIAL EDUCATION FUND", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(67.12, $cell_height * 2, "SOCIALIZE HOUSING FUND", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(67.12, $cell_height * 2, "TRUST FUND", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(33.56, $cell_height * 4, "TOTAL AMOUNT BOOK VALUE" , 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=$cell_height * 4, $valign='M');
                
                PDF::MultiCell(0, $cell_height * 2, "", 1, 'l', 0, 1, '', '', true, 0, false, true, $cell_height * 2, 'M'); // 

                PDF::MultiCell(33.56, 0 , "" , 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='M'); //

                PDF::MultiCell(22.37333333333333, $cell_height * 2, "ACQ. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "DEP. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "BOOK VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "ACQ. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "DEP. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "BOOK VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "ACQ. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "DEP. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "BOOK VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "ACQ. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "DEP. COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::MultiCell(22.37333333333333, $cell_height * 2, "BOOK VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height * 2, 'M');
                PDF::ln();

                if (!empty($rows))
                {

                
                    $total_cost = 0; $total_row = 0;                                         
                        $arr = array(); $iteration = 0; 
                        foreach ($fund_codes as $fund) {
                            $arr[$iteration++] = 0;
                            $arr[$iteration++] = 0;
                            $arr[$iteration++] = 0;
                        }                                      
                    
                    foreach ($rows as $row)
                    {
                        $total_row = 0; $iteration = 0;
                        
                        PDF::MultiCell(33.56, 10, $row->type , 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
                            if (!empty($fund_codes))
                            {
                                foreach ($fund_codes as $fund){
                                    
                                    $acquisition = $this->get_acquisition_cost($row->id, $fund->id, $request->status, $date_from, $date_to );
                                    $depreciation = $this->get_depreciation_cost($row->id, $fund->id, $request->status, $date_from, $date_to);
                                    $total = (floatval($acquisition) - floatval($depreciation));

                                    $arr[$iteration++] += ($acquisition > 0) ? floatval($acquisition) : 0; 
                                    $arr[$iteration++] += ($depreciation > 0) ? floatval($depreciation) : 0;
                                    $arr[$iteration++] += ($total > 0) ? floatval($total) : 0;
                                    
                                    if ($total > 0) {
                                        $total_row += floatval($total);
                                        $total_cost += floatval($total);
                                    }
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($acquisition), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($depreciation), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($total), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                
                                }
                        
                            } 
                            PDF::MultiCell(33.56, 10, $this->money_format($total_row), 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
                            
                        }
                    $iteration = 0; 
                    
                        PDF::MultiCell(33.56, 10, "Total" , 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
                
                        if (!empty($fund_codes)) 
                        {
                            foreach ($fund_codes as $fund)
                            {
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($arr[$iteration++]), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($arr[$iteration++]), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                PDF::MultiCell(22.37333333333333, 10, $this->money_format($arr[$iteration++]), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
                                
                            }
                        }
                        PDF::MultiCell(33.56, 10, $this->money_format($total_cost) , 1, 'C', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        }
                break;

            case 'detailed':

                $totalUnitCost = 0; $totalDepreciation = 0; $totalBookValue = 0;

                $cell_height = 7;
                PDF::MultiCell(30, $cell_height + 3.2, "FIXED ASSET NO.", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "MODE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "TYPE OF ASSET", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(32.8, $cell_height + 3.2, "ACQUISITION DATE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "USEFUL LIFE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "DATE ENDED", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "STATUS", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "SALVAGE VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(32.8, $cell_height + 3.2, "ACQUISITION COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "DEP. AMOUNT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                PDF::MultiCell(30, $cell_height + 3.2, "BOOK VALUE", 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height + 3.2, 'M');

                foreach($rows as $row)
                {
                    $y = PDF::gety(); 
                    if ($y > 184)
                    {
                        PDF::AddPage('L', 'LEGAL');
                        PDF::SetFont('helvetica','B',9);
                        PDF::MultiCell(30, $cell_height + 3.2, "FIXED ASSET NO.", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "MODE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "TYPE OF ASSET", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(32.8, $cell_height + 3.2, "ACQUISITION DATE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "USEFUL LIFE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "DATE ENDED", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "STATUS", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "SALVAGE VALUE", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(32.8, $cell_height + 3.2, "ACQUISITION COST", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "DEP. AMOUNT", 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3.2, 'M');
                        PDF::MultiCell(30, $cell_height + 3.2, "BOOK VALUE", 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height + 3.2, 'M');

                        PDF::SetFont('helvetica','',9);
                    }
                    
                    $totalUnitCost += ($row->unit_cost > 0) ? floatval($row->unit_cost) : 0;
                    $totalDepreciation += ($row->depreciation_cost > 0) ? floatval($row->depreciation_cost) : 0;
                    $totalBookValue += ($row->book_value > 0) ? floatval($row->book_value) : 0;
                    
                    PDF::MultiCell(30, $cell_height + 3, $row->asset_no, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->mode, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->type, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(32.8, $cell_height + 3, $row->date_acquired, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->life_span, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->date_ended, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->status, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $row->salvage_value, 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(32.8, $cell_height + 3, $this->money_format($row->unit_cost), 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $this->money_format($row->depreciation_cost), 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    PDF::MultiCell(30, $cell_height + 3, $this->money_format($row->book_value), 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height + 3, 'M');
                }

                PDF::MultiCell(242.8, $cell_height + 3, "TOTAL AMOUNT", 1, 'L', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                PDF::MultiCell(32.8, $cell_height + 3, $this->money_format($totalUnitCost), 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                PDF::MultiCell(30, $cell_height + 3, $this->money_format($totalDepreciation), 1, 'C', 0, 0, '', '', true, 0, false, true, $cell_height + 3, 'M');
                PDF::MultiCell(30, $cell_height + 3, $this->money_format($totalBookValue), 1, 'C', 0, 1, '', '', true, 0, false, true, $cell_height + 3, 'M');
                    
                break;

            default:
                
                break;
        }
        
        
        // PDF::ln();
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
        $categories = ['' => 'Select a ledger category', 'detailed' => 'Detailed', 'summary' => 'Summary'];
        $funds = $request->fund ? AcctgFundCode::find($request->fund)->description : '';
        $fund_codes = AcctgFundCode::where('is_active', 1)->get();
        $rows = $this->reportAcctgFixedAssetRepository->get($request);
        $prepared = $this->reportAcctgFixedAssetRepository->get_prepared_by();
        $certified = $this->reportAcctgFixedAssetRepository->get_certified_by();
        return view('reports.accounting.fixed-assets.pageview-'.$request->category)->with(compact('prepared', 'certified', 'funds', 'rows', 'categories', 'fund_codes'));
    }

    public function get_acquisition_cost($type, $fund, $status, $dateFrom, $dateTo) 
    {
        return $this->reportAcctgFixedAssetRepository->get_acquisition_cost($type, $fund, $status, $dateFrom, $dateTo);
    }

    public function get_depreciation_cost($type, $fund, $status, $dateFrom, $dateTo) 
    {
        return $this->reportAcctgFixedAssetRepository->get_depreciation_cost($type, $fund, $status, $dateFrom, $dateTo);
    }

    public function money_format($money)
    {
        return ($money > 0) ? number_format(floor(($money*100))/100, 2) : '';
    }
}
