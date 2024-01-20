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
use App\Interfaces\AcctgCollectionReportInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use PDF;
use TCPDF_FONTS;
use App\Models\Accounting\LegalHousingApplication;
use App\Models\HrEmployee; 
use File;

class AcctgCollectionReportController extends Controller
{
    private AcctgCollectionReportInterface $acctgCollectionReportRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        AcctgCollectionReportInterface $acctgCollectionReportRepository, 
        Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->acctgCollectionReportRepository = $acctgCollectionReportRepository;
		$this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'reports/accounting/collections/deposits';
    }

    //REPORT OF COLLECTION AND DEPOSITS
    public function index(Request $request)
    {
        PDF::SetTitle('Report of Collections And Deposits');    
        PDF::SetMargins(15, 15, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        // $cell_height = 5;
        // 185.9 max width

        PDF::MultiCell(0, 0, "<B>REPORT OF COLLECTION AND DEPOSITS</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "PALAYAN CITY", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(50, 0, "Fund:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<b>SEF (201)</b>", 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Date:", $border, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>8/24/2023</b>", 'B', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(50, 0, "Name of Accountable Officer:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "<b>Evelyn S. Dupra</b>", 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, "Report No.:", $border, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>REPORT</b>", 'B', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "A. COLLECTION", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(5, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "1. For Collectors", $border, 'L', 0, 1, '', '', true, 0, true);

        $collector_table = '<table id="table-collector" width="100%" cellspacing="0" cellpadding="1" border="0">
                        <tr>
                            <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                
                            </td>
                            <td colspan="2" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                OFFICIAL RECEIPTS / SERIAL NUMBERS
                            </td>
                            <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                (Form No.)
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                From
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                To
                            </td>
                            <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                                Amount
                            </td>
                        </tr>
                        ';

        $collections = (object)[
            [
                'form_no'=>'AF0016, #221',
                'from'=>'1796500',
                'to'=>'1796500',
                'amount'=>'50.00',
            ],
            [
                'form_no'=>'AF0016, #212',
                'from'=>'1796901',
                'to'=>'1796908',
                'amount'=>'1,133.12',
            ]
        ];
        $collection_row = 0;
        foreach ($collections as $collection) {
            $collector_table .= '
            <tr>
                <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection['form_no'].'</b>
                </td>
                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection['from'].'</b>
                </td>
                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection['to'].'</b>
                </td>
                <td colspan="1" align="right" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$collection['amount'].'</b>
                </td>
            </tr>';
            $collection_row++;
        }
        while ($collection_row < 7)
            {
                $collector_table .= '
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                </tr>';
                $collection_row++;
            }
            $collector_table .= '
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        TOTAL COLLECTIONS
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="5%" style="border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        P
                    </td>
                    <td colspan="1" align="right" width="25%" style="border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>1,183.12</b>
                    </td>
                </tr>';
        
        $collector_table .= '</table>';
        PDF::writeHTML($collector_table, false, false, false, false, '');

        // PDF::MultiCell(185.9, 4, 'B. REMITTANCES / DEPOSIT', 1, 'C', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=4, $valign='M');
        PDF::MultiCell(0, 4, 'B. REMITTANCES / DEPOSIT', 1, 'L', 0, 1, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=4, $valign='M');
        PDF::MultiCell(93, 10, 'Name of Accountable Officer', 'L', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=10, $valign='M');
        
        PDF::MultiCell(46.5, 5, 'Reference', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(0, 5, 'Amount', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        PDF::MultiCell(93, 5, ' ', 'B', 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');

        PDF::MultiCell(15, 5, 'RCO #', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(31.5, 5, '<b>REF-123</b>', 1, 'C', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(8, 5, 'P', 'B', 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
        PDF::MultiCell(0, 5, '<b>1,000.00</b>', 'BR', 'R', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
        PDF::ln();
        $denominations = (object)[
            [
                'bills'=>'1,000',
                'bills.pieces'=>'12',
                'bills.amount'=>'12,000.00',
                'coins'=>'20 coin',
                'coins.pieces'=>'12',
                'coins.amount'=>'120.00',
            ],
            [
                'bills'=>'500',
                'bills.pieces'=>'11',
                'bills.amount'=>'5,500.00',
                'coins'=>'10 coin',
                'coins.pieces'=>'12',
                'coins.amount'=>'120.00',
            ],
            [
                'bills'=>'200',
                'bills.pieces'=>'10',
                'bills.amount'=>'2,000.00',
                'coins'=>'5 coin',
                'coins.pieces'=>'12',
                'coins.amount'=>'60.00',
            ],
            [
                'bills'=>'100',
                'bills.pieces'=>'9',
                'bills.amount'=>'900.00',
                'coins'=>'1 coin',
                'coins.pieces'=>'12',
                'coins.amount'=>'12.00',
            ],
            [
                'bills'=>'50',
                'bills.pieces'=>'5',
                'bills.amount'=>'250.00',
                'coins'=>'25 cent',
                'coins.pieces'=>'12',
                'coins.amount'=>'3.00',
            ],
            [
                'bills'=>'20',
                'bills.pieces'=>'12',
                'bills.amount'=>'240.00',
                'coins'=>'5 cent',
                'coins.pieces'=>'12',
                'coins.amount'=>'.6',
            ],
            [
                'bills'=>null,
                'bills.pieces'=>null,
                'bills.amount'=>null,
                'coins'=>'1 cent',
                'coins.pieces'=>'12',
                'coins.amount'=>'0.12',
            ],
        ];

        $denomination_table = '<table id="table-denomination" width="100%" cellspacing="0" cellpadding="1" border="0">
                        <tr>
                            <td colspan="1" align="center" width="50%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Bills
                            </td>
                            <td colspan="1" align="center" width="50%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                Coins
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Denominations
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                No. of Pieces
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                                Amount
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                                Denominations
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                No. of Pieces
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                                Amount
                            </td>
                        </tr>';
        $bills_height = PDF::gety();
        foreach ($denominations as $denomination) {
            $denomination_table .= '
            <tr>
                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    '.$denomination['bills'].'
                </td>
                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    '.$denomination['bills.pieces'].'
                </td>
                <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$denomination['bills.amount'].'</b>
                </td>
                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    '.$denomination['coins'].'
                </td>
                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    '.$denomination['coins.pieces'].'
                </td>
                <td colspan="1" align="right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                    <b>'.$denomination['coins.amount'].'</b>
                </td>
            </tr>';
        }
        $denomination_table .= '
                <tr>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                        
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="Right" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Total Cash
                    </td>
                    <td colspan="1" align="Right" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>1,111,121,250.00</b>
                    </td>
                </tr>';
        $denomination_table .= '</table>';
        PDF::writeHTML($denomination_table, false, false, false, false, '');

        $bank_table = '<table id="table-bank" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Bank Code
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Check Number
                    </td>
                    <td colspan="1" align="Center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Amount
                    </td>
                </tr>';
        $banks = (object)[
            [
                'bank_code'=>'ABC',
                'check_number'=>'12345',
                'amount'=>'1,200,000.00',
            ],
            [
                'bank_code'=>'ABC',
                'check_number'=>'12345',
                'amount'=>'1,200,000.00',
            ],
            [
                'bank_code'=>'ABC',
                'check_number'=>'12345',
                'amount'=>'1,200,000.00',
            ],
            
        ];
        $bank_row = 0;
        foreach ($banks as $bank) {
        $bank_table .= '
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$bank['bank_code'].'</b>
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$bank['check_number'].'</b>
                    </td>
                    <td colspan="1" align="right" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>'.$bank['amount'].'</b>
                    </td>
                </tr>';
            $bank_row++;
        }
        while ($bank_row < 3)
            {
                $bank_table .= '
                    <tr>
                        <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            
                        </td>
                        <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            
                        </td>
                        <td colspan="1" align="right" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            
                        </td>
                    </tr>';
                $bank_row++;
            }
        $bank_table .= '
                <tr>
                    <td colspan="1" align="center" width="40%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" align="Left" width="15%" style="border-top: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        Total Check
                    </td>
                    <td colspan="1" align="Right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        <b>1200000.00</b>
                    </td>
                </tr>';
        $bank_table .= '</table>';
        PDF::writeHTML($bank_table, false, false, false, false, '');
        $accountability_table = '
                <table id="table-bank" width="100%" cellspacing="0" cellpadding="1" border="0">
                    <tr>
                        <td colspan="1" align="left" width="100%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Beginning Balance
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Receipt
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Issued
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Ending Balance
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            NAME OF FORMS
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            Qty Serial Number
                        </td>
                    </tr>';
                    
                    $accountable_forms = (object)[
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                        // [
                        //     'form_name'=>'AF056, #039',
                        //     'beg_bal'=>'18 9941483-150',
                        //     'receipt'=>'',
                        //     'issued'=>'1 9941483-1483',
                        //     'end_bal'=>'17 9941483-150',
                        // ],
                    ];
                    $accountable_row = 0;
        foreach ($accountable_forms as $accountable_form) {
            $accountability_table .= '
                <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['form_name'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['beg_bal'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['receipt'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['issued'].'
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                            '.$accountable_form['end_bal'].'
                        </td>
                </tr>';
            $accountable_row++;
        }
        while ($accountable_row < 7)
            {
                $accountability_table .= '
                    <tr>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="Center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                        </td>
                    </tr>';
                $accountable_row++;
            }
        $accountability_table .= '</table>';
        PDF::writeHTML($accountability_table, false, false, false, false, '');

        $summary_table = '
            <table id="table-summary" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        D. ASUMMARY OF COLLECTIONS AND REMITTANCES
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Begginning Balance
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        P
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="  border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Add : Collections
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="  border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Cash
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%">
                        Check(s)
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                    
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        Total
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%" style="border-bottom: 0.7px solid black;">
                        4,626.12
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="60%" style="border-left: 0.7px solid black;">
                        Less : Remittance / Deposit to Cashier
                    </td>
                    <td colspan="1" rowspan="1" align="Right" width="20%" style="border-bottom: 0.7px solid black;">
                        (4,626.12)
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-left: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        Balance
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%">
                        P
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-bottom: 0.7px solid black;">
                        
                    </td>
                    <td colspan="1" rowspan="1" align="Center" width="20%" style="border-right: 0.7px solid black;">
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" style="border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; border-bottom: 0.7px solid black;">
                        
                    </td>
                </tr>';
        $summary_table .= '</table>';
        PDF::writeHTML($summary_table, false, false, false, false, '');

        $footer_table = '
            <table id="table-summary" width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="50%" style="font-size: 9px;">
                        CERTIFICATION
                    </td>
                    <td colspan="1" align="center" width="50%" style="font-size: 9px;">
                        VERIFICATION AND ACKNOWLEDGEMENT
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>

                <tr>
                    <td colspan="1" align="left" width="3%" >
                        
                    </td>
                    <td colspan="1" align="right" width="44%" style="font-size: 8px; font-family: "Times New Roman";">
                        I hereby certify that the foregoing report of collections and
                    </td>
                    <td colspan="1" align="left" width="6%" >
                        
                    </td>
                    <td colspan="1" align="left" width="3%" >
                        
                    </td>
                    <td colspan="1" align="right" width="44%" style="font-size: 8px;">
                        I hereby certify that the foregoing report of collection has been
                    </td>
                </tr>

                <tr>
                    <td colspan="1" align="right" width="47%" style="font-size: 8px;">
                        deposits and accountability of accountable forms is true and correct
                    </td>
                    <td colspan="1" align="left" width="6%" >
                        
                    </td>
                    <td colspan="1" align="left" width="25%" style="font-size: 8px;">
                        verify and acknowledge receipt of
                    </td>
                    <td colspan="1" align="left" width="4%" >
                        P
                    </td>
                    <td colspan="1" align="right" width="18%" >
                        4,626.12
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="right" width="50%" >
                        
                    </td>
                    <td colspan="1" align="Center" width="50%" style="font-size: 8px;">
                        <b>Four Thousand Six Hundred Twenty Six Pesos & 12/100</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="left" width="100%" >
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="30%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>Evelyn S. Dupra</b>
                    </td>
                    <td colspan="1" align="left" width="2%" >
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>8/24/2023</b>
                    </td>

                    <td colspan="1" align="left" width="6%" >
                        
                    </td>

                    <td colspan="1" align="center" width="30%" style="font-size: 8px; border-bottom: 0.7px solid black;">
                        <b>MARIA GEMMA ANDASAN</b>
                    </td>
                    <td colspan="1" align="left" width="2%" >
                        
                    </td>
                    <td colspan="1" align="center" width="15%" style="border-bottom: 0.7px solid black; font-size: 8px;">
                        <b>8/24/2023</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="center" width="32%" style="font-size: 8px;">
                        COLLECTOR
                    </td>
                    <td colspan="1" align="center" width="15%" style="font-size: 8px;">
                        DATE
                    </td>

                    <td colspan="1" align="left" width="6%" >
                        
                    </td>

                    <td colspan="1" align="center" width="32%" style="font-size: 8px;">
                        SIGNATURE CASHIER/TREASURER
                    </td>
                    <td colspan="1" align="center" width="15%" >
                        DATE
                    </td>
                </tr>
                ';
        $footer_table .= '</table>';
        PDF::writeHTML($footer_table, false, false, false, false, '');
        PDF::Output('collection_and_deposits.pdf');
    }

    public function housingPrint(Request $request,LegalHousingApplication $data) // housing order of payment
    {
         //dd();

        PDF::SetTitle('Report of Collections And Deposits');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 185.9 max width

        $requestor = $data->client->cit_fullname;
        $address = $data->current_address;
        $date = $this->carbon->parse($data->app_date)->format('F m, Y');
        $issued_by = strtoupper(Auth::user()->hr_employee->fullname);
        $position = strtoupper("Accountability");
        $trans_type = $data->eco_service->tfoc_name;

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG');

        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "LEGAL OFFICE", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "ORDER OF PAYMENT", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Name:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(150, $cell_height, $requestor, 'B', 0, 'L');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Address:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, $cell_height, $address, 'B', 0, 'L');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Date:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(32, $cell_height, $date, 'B', 0, 'C');

        PDF::Cell(15, $cell_height, '', $border, 0, 'L');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(27, $cell_height, 'Transaction Type:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(41, $cell_height, $trans_type, 'B', 0, 'C');
        PDF::ln(10);
        
        PDF::SetFillColor(220,220,220);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Terms (months)", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Total Amount", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Downpayment", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
        
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, $data->month_terms, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, $data->total_amount, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, $data->initial_monthly, 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::ln(15);
        
        PDF::Cell(20,4,'',0,0,'L');
        PDF::Cell(60,4,'Terms and Conditions',0,1,'L');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(20,4,'',0,0,'L');
        PDF::MultiCell(150, 0, nl2br($data->terms_condition), 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,19,'Issued by:',0,1,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,4,$issued_by,"B",1,'C');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,4,$position,0,0,'C');

        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode($data->top_transaction_no, 'QRCODE,H', 180, 35, 15, 15, $style, 'N');
        PDF::SetFont('helvetica','',7);
        PDF::ln(1);
        if ($data->top_transaction_no) {
            PDF::MultiCell(0, $cell_height, 'TOP No:'. $data->top_transaction_no, $border, 'L', 0, 0, 179, '', true, 0, true);
        }
		
        //PDF::Output('collection_and_deposits.pdf');
		
		$inspectedId = HrEmployee::where('id', Auth::user()->hr_employee->id)->first();
        
		$filename = $data->id."-collection_and_deposits.pdf";
		$arrSign= $this->_commonmodel->isSignApply('econ_investment_housing_app_submitted_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('econ_investment_housing_app_submitted_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($inspectedId->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        PDF::Output($folder.$filename,"I");
    }

    public function potabilityPrint(Request $request) // certificate of potability
    {
        PDF::SetTitle('Requests for Pre-repair');    
        PDF::SetMargins(15, 20, 15,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width

        $business_name = strtoupper("Argo international forwarders incorporated");
        $business_brgy = "Marcos Village";
        $business_mun = "Palayan";
        $business_prov = "Nueva Ecija";
        $date_issued = "September 21, 2023";
        $date_start = "January 21, 2023";
        $date_end = "August 21, 2023";
        $inspected_by = strtoupper("Christy Fabro");
        $inspector_position = strtoupper("Accountability");
        $approved_by = strtoupper("Kenneth Hontucan Hontucan ");
        $approver_position = strtoupper("Accountabilitys");
        $certificate_no = "001";
        $or_no = "ABC-123";
        $or_date_issued = "March 2, 2023";

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 22, $w = 22, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>City of Palayan</b>", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','',11);
        PDF::MultiCell(0, $cell_height, "CITY HEALTH OFFICE", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(0, $cell_height, "<B>CERTIFICATE OF POTABILITY</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(20);

        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">This is to certify that the results of water sample collected from <b>'.$business_name."</b> , located at Barangay ".$business_brgy.", ".$business_mun." City, ".$business_prov." on ".$date_start." and ".$date_end." , showed that the source of water have passed the requirements set by the Philippine National Standard for Drinking Water (PNSWD) for Physical, Chemical and Bacteriological quality.</p>", 0, 'J', 0, 1, '', '', true, 0, true);
        
        PDF::ln();

        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">Based on the results, the City Health Office hereby recommends the issuance of this Certificate to <b>'.$business_name.".</b></p>", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">Issued this '.$date_issued.".</p>", 0, 'J', 0, 1, '', '', true, 0, true);

        PDF::ln(15);

        PDF::Cell(25, $cell_height, 'Inspected by:', $border, 0, 'C');

        PDF::Cell(90, $cell_height, '', $border, 0, 'C');

        PDF::Cell(25, $cell_height, 'Approved by:', $border, 0, 'C');
        PDF::ln(25);

        PDF::Cell(5, $cell_height, '', $border, 0, 'C');
        PDF::Cell(65, $cell_height, $inspected_by, $border, 0, 'C');

        PDF::Cell(45, $cell_height, '', $border, 0, 'C');

        PDF::Cell(65, $cell_height, $approved_by, $border, 0, 'C');
        PDF::ln();

        PDF::Cell(5, $cell_height, '', $border, 0, 'C');
        PDF::Cell(65, $cell_height, $inspector_position, "T", 0, 'C');

        PDF::Cell(45, $cell_height, '', $border, 0, 'C');

        PDF::Cell(65, $cell_height, $approver_position, "T", 0, 'C');
        PDF::ln(20);

        PDF::Cell(35, $cell_height, 'Certificate No:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $certificate_no, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'Date Issued:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $date_issued, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'O.R. No.:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $or_no, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'O.R. Date Issued:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $or_date_issued, $border, 1, 'L');
        PDF::ln();

        PDF::MultiCell(0, $cell_height, "<b>Notes:</b>", 0, 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "1.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "This certificate must be re-validated every after examination based on the standard interval of frequency of sampling.", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "2.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "Copy of results of laboratory examination must be submitted to the City Health Office for information and reference.", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "3.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "Examination of drinking water must be conducted by a Department of Health Accredited Laboratory.", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::Output('pre-repair_request.pdf');
    }

    public function inspectionRepairPrint(Request $request) // inspection repair
    {
        

        PDF::SetTitle('Requests for Pre-repair');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width

        $requestor = "Kenneth";
        $address = "Pitogo, Makati City";
        $date = "September 21, 2023";
        $issued_by = strtoupper("Christy Fabro");
        $position = strtoupper("Accountability");
        $trans_type = "SAN LORENZO HEIGHTS";

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 10, $w = 22, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Capital of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','B',11);
        PDF::MultiCell(0, $cell_height, "REQUESTS FOR PRE-REPAIR INSPECTION-VEHICLE", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "DESCRIPTION OF PROPERTY", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        // 1st row
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(14, 0, "Type:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(76, 0, "", 'B', 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(15.9, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(22, 0, "Brand/Model:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(68, 0, "", 'B', 'L', 0, 1, '', '', true, 0, true);

        // 2nd row
        PDF::MultiCell(20, 0, "Engine No.:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, "", 'B', 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(15.9, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(22, 0, "Plate No.:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(68, 0, "", 'B', 'L', 0, 1, '', '', true, 0, true);

        // 3rd row
        PDF::MultiCell(30, 0, "Acquisition Date:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "", 'B', 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(15.9, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(27, 0, "Acquisition Cost:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(63, 0, "", 'B', 'L', 0, 1, '', '', true, 0, true);

        // 4th row
        PDF::MultiCell(32, 0, "Date of Last Repair:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(58, 0, "", 'B', 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(15.9, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);

        PDF::MultiCell(35, 0, "Nature of Last Repair:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(55, 0, "", 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "DEFECTS/COMPLAINTS:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "Parts to be supplied/replaced:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Requested by:", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln(15);

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "Kenneth Rey B. Hontucan", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "Kenneth Rey B. Hontucan", "B", 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "<B>Chief of Office</b>", $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "<b>CGSO</b>", $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',11);
        PDF::MultiCell(0, $cell_height, "<B>PRE-REPAIR</b>", 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "", "B", 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Pre-inspected by:", $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "Kenneth Rey B. Hontucan", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "Mechanical Shop Foreman", $border, 'C', 0, 0, '', '', true, 0, true);

        PDF::ln(10);

        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(10, 0, "", $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, "Date", $border, 'C', 0, 0, '', '', true, 0, true);

        PDF::Output('pre-repair_request.pdf');
    }

    public function payment_summary_print(Request $request) // payment sumarry
    {
        

        PDF::SetTitle('Requests for Pre-repair');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 185.9 max width

        $requestor = "Kenneth";
        $address = "Pitogo, Makati City";
        $date = "September 21, 2023";
        $issued_by = strtoupper("Christy Fabro");
        $position = strtoupper("Accountability");
        $trans_type = "SAN LORENZO HEIGHTS";

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 10, $w = 22, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','B',11);
        PDF::MultiCell(0, $cell_height, "PAYMENT SUMMARY", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFillColor(220,220,220);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(38, 10, "REFERENCE I.D.", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "AMOUNT COLLECTIBLE", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "COLLECTED", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "PENALTY", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "RECEIVABLES", 1, 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');
        
        PDF::MultiCell(38, 10, "ABC-#123", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "ABC-#123", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "ABC-#123", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "P 100,000,000.00", 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "TOTAL", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(38, 10, "", 1, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');

        PDF::MultiCell(140, 5, 'Prepared by:', $border, 'R', 0, 1, '', '', true, 0, false, true, 20, 'M');
        PDF::ln();

        PDF::MultiCell(120, 0, "", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::Cell(0,0,'EDDIE MADRONA JR',$border,1,'C');

        PDF::MultiCell(120, 0, "", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Positioning", "T", 'C', 0, 0, '', '', true, 0, true);

        
        PDF::ln(15);



        PDF::Output('pre-repair_request.pdf');
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
        // $cell_height = 5;
        // 195.9 max width

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
        
        // parent data
        PDF::MultiCell(13, 0, "1", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, "4974783", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "Kenneth B. Hontucan", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(60, 0, "BRGY. ATATE, PALAYAN CITY", 0, 0, 'L');
        PDF::ln();

        // multiple data
        PDF::MultiCell(102, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "Traffic Violation Fee/No Helmet", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "100.00", 0, 'R', 0, 1, '', '', true, 0, true);

        // single data - total
        PDF::MultiCell(165, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "<b>100.00</b>", "T", 'R', 0, 0, '', '', true, 0, true);
        PDF::ln();

        // parent data
        PDF::MultiCell(13, 0, "2", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, "4974784", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "Kenneth B. Hontucan", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(60, 0, "BRGY. ATATE, PALAYAN CITY", 0, 0, 'L');
        PDF::ln();

        // multiple data
        PDF::MultiCell(102, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "Traffic Violation Fee/No Helmet", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "100.00", 0, 'R', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(102, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, "Traffic Violation Fee/Counter Flow", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(3, 0, "", 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "1000.00", 0, 'R', 0, 1, '', '', true, 0, true);

        // single data - total
        PDF::MultiCell(165, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30.9, 0, "<b>1100.00</b>", "T", 'R', 0, 0, '', '', true, 0, true);
        PDF::ln();

        // <--- Data --->
        // footer
        PDF::MultiCell(0, 0, "", "B", 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(50, 0, "No. of Receipts : 2", "B", 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, "Total : ", "B", 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>1200.00</b>", "B", 'R', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell(0, 0, "By:", 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::MultiCell(0, 0, $official, 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Collector", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::Output('daily_collection.pdf'); 
    }

    public function cashDisbursementRecap(Request $request) // recap - cash disbursement cashDisbursementRecap
    {

        PDF::SetTitle('Daily Collection');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width

        $month_year = 'July, 2023';
        $total_debit = "1,000,000.00";
        $total_credit = "2,000,000.00";
        $prepared_by = strtoupper('Kenneth Hontucan');
        $prepared_by_position = 'IT';
        $corrected_by = strtoupper('Eddie Madrona');
        $corrected_by_position = 'IS';

        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',13);
        PDF::MultiCell(0, 0, "CASH DISBURSEMENT RECAP", '', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "General Fund", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "For the month of ".$month_year, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 10, $w = 25, $h = 0, $type = 'PNG');

        PDF::SetFillColor(217, 225, 242);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(60, 8, "Account Title", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(60, 8, "Account Code", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Debit", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Credit", 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        $tbl = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">';
        $max_row = 1;
        while ($max_row < 20) {
            $tbl .= '<tr >
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Hospital Fees
                        </td>
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        HF#222
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            1000.00
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            2000.00
                        </td>
                    </tr>';
            $max_row++; 
        }
        $tbl .= '</table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
            
        PDF::MultiCell(120, 8, "Total Amount", 1, 'L', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_debit, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_credit, 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        PDF::ln(10);
        PDF::MultiCell(120, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Certified Correct By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(70, 5, $prepared_by, '', 0, 'C');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        // PDF::MultiCell(60, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by, '', 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::MultiCell(70, 5, $prepared_by_position, 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by_position, "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::Output('daily_collection.pdf'); 
    }

    public function checkDisbursementRecap(Request $request) // recap - check disbursement  checkDisbursementRecap
    {
        PDF::SetTitle('Daily Collection');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width

        $month_year = 'July, 2023';
        $total_debit = "1,000,000.00";
        $total_credit = "2,000,000.00";
        $prepared_by = strtoupper('Kenneth Hontucan');
        $prepared_by_position = 'IT';
        $corrected_by = strtoupper('Eddie Madrona');
        $corrected_by_position = 'IS';

        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',13);
        PDF::MultiCell(0, 0, "CHECK DISBURSEMENT RECAP", '', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "General Fund", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "For the month of ".$month_year, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 10, $w = 25, $h = 0, $type = 'PNG');

        PDF::SetFillColor(217, 225, 242);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(60, 8, "Account Title", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(60, 8, "Account Code", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Debit", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Credit", 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        $tbl = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">';
        $max_row = 1;
        while ($max_row < 20) {
            $tbl .= '<tr >
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Hospital Fees
                        </td>
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        HF#222
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            1000.00
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            2000.00
                        </td>
                    </tr>';
            $max_row++; 
        }

        $tbl .= '</table>';
        PDF::writeHTML($tbl, false, false, false, false, '');

        PDF::MultiCell(120, 8, "Total Amount", 1, 'L', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_debit, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_credit, 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        PDF::ln(10);
        PDF::MultiCell(120, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Certified Correct By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(70, 5, $prepared_by, '', 0, 'C');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        // PDF::MultiCell(60, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by, '', 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::MultiCell(70, 5, $prepared_by_position, 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by_position, "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::Output('daily_collection.pdf'); 
    }

    public function cashReceiptRecap(Request $request) // recap - cash receipts cashReceiptRecap
    {
        PDF::SetTitle('Daily Collection');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width

        $month_year = 'July, 2023';
        $total_debit = "1,000,000.00";
        $total_credit = "2,000,000.00";
        $prepared_by = strtoupper('Kenneth Hontucan');
        $prepared_by_position = 'IT';
        $corrected_by = strtoupper('Eddie Madrona');
        $corrected_by_position = 'IS';

        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',13);
        PDF::MultiCell(0, 0, "CASH RECEIPTS RECAP", '', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "General Fund", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "For the month of ".$month_year, '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 10, $w = 25, $h = 0, $type = 'PNG');

        PDF::SetFillColor(217, 225, 242);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(60, 8, "Account Title", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(60, 8, "Account Code", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Debit", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, "Credit", 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        $tbl = '<table id="purhcase-order-print-1-table" width="100%" cellspacing="0" cellpadding="1" border="0.5">';
        $max_row = 1;
        while ($max_row < 20) {
            $tbl .= '<tr >
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Hospital Fees
                        </td>
                        <td align="center" width="30.62%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        HF#222
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            1000.00
                        </td>
                        <td align="center" width="19.4%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            2000.00
                        </td>
                    </tr>';
            $max_row++; 
        }
        $tbl .= '</table>';
        PDF::writeHTML($tbl, false, false, false, false, '');

        PDF::MultiCell(120, 8, "Total Amount", 1, 'L', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_debit, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(38, 8, $total_credit, 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        PDF::ln(10);
        PDF::MultiCell(120, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Certified Correct By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(70, 5, $prepared_by, '', 0, 'C');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        // PDF::MultiCell(60, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by, '', 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::MultiCell(70, 5, $prepared_by_position, 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(50, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by_position, "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::Output('daily_collection.pdf'); 
    }

    public function cashReceiptJournal(Request $request) // journal - cash receipts cashReceiptJournal export_to_pdf
    {
        PDF::SetTitle('Daily Collection');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LEGAL');
        

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 335.56 max width

        $month_year = 'July, 2023';
        $total_debit = "1,000,000.00";
        $total_credit = "2,000,000.00";
        $prepared_by = strtoupper('Kenneth Hontucan');
        $prepared_by_position = 'IT';
        $corrected_by = strtoupper('Eddie Madrona');
        $corrected_by_position = 'IS';

        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::SetFont('helvetica','B',13);
        PDF::MultiCell(0, 0, "CASH RECEIPTS RECAP", '', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::SetFont('helvetica','',10);
        PDF::MultiCell(0, 0, "General Fund", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "For the month of ".$month_year, '', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 75, $y = 10, $w = 25, $h = 0, $type = 'PNG');

        PDF::SetFillColor(217, 225, 242);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(33.556, 8, "Date", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(16.778, 8, "JEV No.", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(50.334, 8, "Payee/Payer", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(60.4008, 8, "Particulars", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(53.6896, 8, "Account Title", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(53.6896, 8, "Account Code", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.556, 8, "Debit", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.556, 8, "Credit", 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

        $tbl = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">';
        $max_row = 1;
        while ($max_row < 20) {
            $tbl .= '<tr >
                        <td align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        September 10, 2023
                        </td>
                        <td align="center" width="5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        JEV No.
                        </td>
                        <td align="center" width="15%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Kenneth Rey B. Hontucan as as as
                        </td>
                        <td align="center" width="18%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Particulars
                        </td>
                        <td align="center" width="16%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Account Title
                        </td>
                        <td align="center" width="16%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Account Code
                        </td>
                        <td align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Debit
                        </td>
                        <td align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Credit
                        </td>
                    </tr>';
            $max_row++; 
        }
        $tbl .= '</table>';
        PDF::writeHTML($tbl, false, false, false, false, '');

        PDF::MultiCell(268.448, 8, "Total Amount", 1, 'L', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.556, 8, $total_debit, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        PDF::MultiCell(33.556, 8, $total_credit, 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');
        PDF::ln(10);

        PDF::MultiCell(80, 5, "Prepared By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, "Certified Correct By:", '', 'L', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::ln(15);

        PDF::Cell(80, 5, $prepared_by, '', 0, 'C');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by, '', 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::MultiCell(80, 5, $prepared_by_position, 'T', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(180, 5, "", '', 'C', 0, 0, '', '', true, 0, false, true, 5, 'M');
        PDF::MultiCell(0, 5, $corrected_by_position, "T", 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');

        PDF::Output('daily_collection.pdf'); 
    }

    public function housing(Request $request)
    {
        

        PDF::SetTitle('Report of Collections And Deposits');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'A4');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 185.9 max width

        $requestor = "Kenneth";
        $address = "Pitogo, Makati City";
        $date = "September 21, 2023";
        $issued_by = strtoupper("Christy Fabro");
        $position = strtoupper("Accountability");
        $trans_type = "SAN LORENZO HEIGHTS";

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 45, $y = 10, $w = 22, $h = 0, $type = 'PNG');

        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "City of Palayan", '', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(0, 0, "LEGAL OFFICE", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "ORDER OF PAYMENT", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Name:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(150, $cell_height, $requestor, 'B', 0, 'L');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Address:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(0, $cell_height, $address, 'B', 0, 'L');
        PDF::ln(10);

        PDF::SetFont('helvetica','',9);
        PDF::Cell(17, $cell_height, 'Date:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(32, $cell_height, $date, 'B', 0, 'C');

        PDF::Cell(15, $cell_height, '', $border, 0, 'L');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(27, $cell_height, 'Transaction Type:', $border, 0, 'L');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(41, $cell_height, $trans_type, 'B', 0, 'C');
        PDF::ln(10);
        
        PDF::SetFillColor(220,220,220);
        PDF::SetFont('helvetica','B',9);
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Terms (months)", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Total Amount", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "Downpayment", 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 1, '', '', true, 0, false, true, 10, 'M');
        
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "120", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "500,000.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(50, 10, "4,200.00", 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::MultiCell(20, 10, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 10, 'M');
        PDF::ln(15);
        
        PDF::Cell(20,4,'',0,0,'L');
        PDF::Cell(60,4,'Terms and Conditions',0,1,'L');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(20,4,'',0,0,'L');
        PDF::MultiCell(150, 0, nl2br(" 
                                1. Downpayment of 4,200.00
                                2. Next monthly payment for the rest of the months will be amounted to 5,000.00
                                3. Due date every months will be 16th day
                                4. Thanks you"), 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,4,'Issued by:',0,1,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,4,$issued_by,"B",1,'C');

        PDF::SetFont('helvetica','',9);
        PDF::Cell(120,4,'',0,0,'L');
        PDF::Cell(0,4,$position,0,0,'C');

        $style = array(
            'border' => true,
            'vpadding' => 3,
            'hpadding' => 3,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        PDF::write2DBarcode("ASD", 'QRCODE,H', 180, 35, 15, 15, $style, 'N');
        PDF::SetFont('helvetica','',7);
        PDF::ln(1);
        // if ($data->top_transaction_no) {
            PDF::MultiCell(0, $cell_height, 'TOP No:'. "ABC", $border, 'L', 0, 0, 179, '', true, 0, true);
        // }

        PDF::Output('collection_and_deposits.pdf');
    }
}
