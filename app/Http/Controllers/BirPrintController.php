<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class BirPrintController extends Controller
{
    function print2()
    {
        PDF::SetTitle('BIR 2307:');    
        PDF::SetMargins(0, 0, 0,true);    
        PDF::AddPage('P', 'FOLIO');
        $height = PDF::getPageHeight();
        $width = PDF::getPageWidth();
        PDF::Image('./assets/images/forms/bir2307-1.jpg',0, 0, $width, 0, '', '', '', false, 300, '', false, false, 0);
        $border = 1;
        PDF::SetLineStyle(array('width' => 0.50, 'cap' => 'butt', 'join' => 'miter'));
        PDF::SetFont('helvetica','B',8);

        // data
        // find field: where should data put
        $date = '11372023';//field: For the Period From
        PDF::SetY(39);
        PDF::SetX(53);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: For the Period To
        PDF::SetY(39);
        PDF::SetX(140);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        $date = '333-333-333-55555';//field: Part 1: Taxpayer Identification Number (TIN)
        $x = 68;
        PDF::SetY(50);
        PDF::SetX(72);
        foreach (str_split($date) as $value) {
            if ($value != '-') {
                PDF::MultiCell(5, 0, $value, 0, 'C',0,0);
            } else {
                PDF::MultiCell(3.3, 0, '', 0, 'C',0,0);
            }
        }
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 59, true);//field: Payee’s Name
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 69, true);//field: Registered Address
        $date = '3360';//field: 4A Zipcode
        PDF::SetY(69);
        PDF::SetX(190);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 79, true);//field: 5 Foreign Address, if applicable 

        $date = '333-333-333-55555';//field: Part 2: Taxpayer Identification Number (TIN)
        $x = 68;
        PDF::SetY(90);
        PDF::SetX(72.5);
        foreach (str_split($date) as $value) {
            if ($value != '-') {
                PDF::MultiCell(5, 0, $value, 0, 'C',0,0);
            } else {
                PDF::MultiCell(3.3, 0, '', 0, 'C',0,0);
            }
        }
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 99, true);//field: Payor's Name
        PDF::MultiCell(0, 0, 'Change me', 0, 'L', 0, 1, 12, 109, true);//field: Registered Address
        $date = '3360';//field: 8A Zipcode
        PDF::SetY(109);
        PDF::SetX(190);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        // Part III – Details of Monthly Income Payments and Taxes Withheld
        $column_size ='
        <tr>
            <td width="154px"></td>
            <td width="42px"></td>
            <td width="72px"></td>
            <td width="74px"></td>
            <td width="72px"></td>
            <td width="72px"></td>
            <td></td>
        </tr>
        ';
        //field: Income Payments Subject to Expanded Withholding Tax
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 125, true, 0, true);
        //field: total
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td></td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 172, true, 0, true);

        //field: Money Payments Subject to Withholding of Business Tax (Government & Private)
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
            <tr>
                <td>Income</td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 184, true, 0, true);
        //field: total
        $table = '
        <table style="padding-bottom:3.5px">
            '.$column_size.'
            <tr>
                <td></td>
                <td>WC680</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
                <td>10,000.00</td>
            </tr>
        </table>
        ';
        PDF::MultiCell(0, 0, $table, 0, 'L', 0, 1, 7, 232, true, 0, true);

        PDF::MultiCell(0, 0, 'Change Me', 0, 'C', 0, 1, 0, 259, true, 0, true); //field:  Printed Name of Payor
        PDF::MultiCell(0, 0, 'Change Me', 0, 'L', 0, 1, 48, 272, true, 0, true); //field:  Tax Agent Accreditation No./ Attorney’s Roll No.
        $date = '11172023';//field: Date of Issue
        PDF::SetY(272);
        PDF::SetX(111);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: Date of Expiry
        PDF::SetY(272);
        PDF::SetX(171);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        PDF::MultiCell(0, 0, 'Change Me', 0, 'C', 0, 1, 0, 287, true, 0, true); //field:  Printed Name of Payee
        PDF::MultiCell(0, 0, 'Change Me', 0, 'L', 0, 1, 48, 299, true, 0, true); //field:  Tax Agent Accreditation No./ Attorney’s Roll No.
        $date = '11172023';//field: Date of Issue
        PDF::SetY(299);
        PDF::SetX(111);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }
        $date = '11172023';//field: Date of Expiry
        PDF::SetY(299);
        PDF::SetX(171);
        foreach (str_split($date) as $value) {
            PDF::MultiCell(4.6, 0, $value, 0, 'C',0,0);
        }

        PDF::AddPage();
        PDF::Image('./assets/images/forms/bir2307-2.jpg',0, 0, $width, 0, '', '', '', false, 300, '', false, false, 0);

        PDF::Output('BIR_2307_.pdf');
    }
    public function print()
    {
        PDF::SetTitle('BIR 2307:');    
        PDF::SetMargins(5, 15, 5,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);
        
        // barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 4, // width of a single module in points
            'module_height' => 4// height of a single module in points
        );
        // PDF::write2DBarcode('2307 01/18ENCS', 'Pdf417', 1, 1, 532, 100, $style, '0');
        // PDF::Text(80, 85, '2307 01/18ENCS');
        // PDF::AddPage();
        $height = 5;
        $border0 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter');
        $border1 = array('width' => 0.50, 'cap' => 'butt', 'join' => 'miter');
        $border2 = array('width' => 1, 'cap' => 'butt', 'join' => 'miter');
        
        PDF::SetFont('helvetica','B',8);
        $border = array('LTRB' => $border2);
        PDF::Cell(0,$height,'SCHEDULES OF ALPHANUMERIC TAX CODES',$border,1,'C');

        PDF::SetLineStyle($border1);
        PDF::SetFont('helvetica','B',7);

        $border = array('L' => $border2, 'T' =>$border0, 'B' =>$border1, 'R' =>$border0);
        PDF::Cell(75,$height * 2,'A Income Payments subject to Expanded Withholding Tax',$border,0,'L');
        
        $border = array('L' => $border1, 'T' =>$border1, 'B' =>$border1, 'R' =>$border1);
        PDF::Cell(30,$height,'ATC',$border,1,'C',0,'',0,true,'T','C');

        PDF::Cell(75,$height,'',0,0,'L');//space
        $border = array('L' => $border1, 'T' =>$border1, 'B' =>$border1, 'R' =>$border0);
        PDF::Cell(15,$height,'Individual',1,0,'C');

        $border = array('L' => $border1, 'T' =>$border0, 'B' =>$border1, 'R' =>$border1);
        PDF::Cell(15,$height,'Corporation',1,0,'C');

        $border = array('L' => $border1, 'T' =>$border1, 'B' =>$border1, 'R' =>$border1);
        PDF::Cell(70,$height*2,'A Income Payments subject to Expanded Withholding Tax',$border,0,'L',0,'',0,true,'C','C');

        $border = array('L' => $border1, 'T' =>$border1, 'B' =>$border1, 'R' =>$border1);
        PDF::Cell(30,$height,'ATC',$border,1,'C',0,'',0,true,'B','C');

        PDF::Cell(75,$height,'',0,0,'L');//space
        $border = array('L' => $border1, 'T' =>$border1, 'B' =>$border1, 'R' =>$border0);
        PDF::Cell(15,$height,'Individual',1,0,'C');

        $border = array('L' => $border1, 'T' =>$border0, 'B' =>$border1, 'R' =>$border1);
        PDF::Cell(15,$height,'Corporation',1,0,'C');
        
        PDF::Output('BIR_2307_.pdf');

    }
}
