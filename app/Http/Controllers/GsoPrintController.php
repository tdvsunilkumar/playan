<?php

namespace App\Http\Controllers;
use App\Models\GsoDepartmentalRequisition;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\BacRequestForQuotationInterface;
use App\Interfaces\BacResolutionInterface;
use App\Interfaces\BacAbstractOfCanvassInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use File;
class GsoPrintController extends Controller
{   

    private BacRequestForQuotationInterface $bacRequestForQuotationRepository;
    private BacResolutionInterface $bacResolutionRepository;
    private BacAbstractOfCanvassInterface $bacAbstractofCanvassRepository;
    private $carbon;
    private $slugs;
    private $iteration;

    public function __construct(BacRequestForQuotationInterface $bacRequestForQuotationRepository, BacResolutionInterface $bacResolutionRepository, BacAbstractOfCanvassInterface $bacAbstractofCanvassRepository, Carbon $carbon) 
    {
        $this->_commonmodel = new CommonModelmaster();
        date_default_timezone_set('Asia/Manila');
        $this->bacRequestForQuotationRepository = $bacRequestForQuotationRepository;
        $this->bacResolutionRepository = $bacResolutionRepository;
        $this->bacAbstractofCanvassRepository = $bacAbstractofCanvassRepository;
        $this->carbon = $carbon;
        $this->iteration = 1;
        $this->slugs = 'general-services/purchase-orders';
    }

    public function rfq_footer2($res)
    {
        PDF::SetXY(10, 290);
        //Footer
        PDF::Cell(140,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(30,5,'Delivery Period:',0,0,'R');
        PDF::Cell(25.85,5,$res->delivery_period,'B',0,'C');
        PDF::ln();

        PDF::Cell(140,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(30,5,'Warranty:',0,0,'R');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(25.85,5,'','B',0,'C');
        PDF::ln();

        PDF::Cell(195.85,5,'After having carefully read and accepted your General Conditions, I/We quote you on the item at prices noted above.',0,0,'L');
        PDF::ln();
        PDF::ln();

        PDF::Cell(145,5,'',0,0,'C');
        PDF::Cell(50.85,5,$res->contact_person,'B',0,'C');
        PDF::ln();
        
        PDF::Cell(10,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(40,5,'Canvassed by:',0,0,'L');
        PDF::setFont('helvetica','',9);
        PDF::Cell(60,5,'',0,0,'C');
        PDF::Cell(35,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(50.85,5,'Printed Name/Signature','T',0,'C');
        PDF::setFont('helvetica','',9);
        PDF::ln();

        PDF::Cell(50,5,'',0,0,'C');
        PDF::Cell(60,5,$res->canvass_by,0,0,'C');
        PDF::Cell(35,5,'',0,0,'C');
        PDF::Cell(50.85,5,$res->contact_number,'',0,'C');
        PDF::ln();

        PDF::Cell(50,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(60,5,'Printed Name/Signature','T',0,'C');
        PDF::setFont('helvetica','',9);
        PDF::Cell(35,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(50.85,5,'Contact Number','T',0,'C');
        PDF::setFont('helvetica','',9);
        PDF::ln();

        PDF::Cell(145,5,'',0,0,'C');
        PDF::Cell(50.85,5,$res->email_address,0,0,'C');
        PDF::ln();

        PDF::Cell(145,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(50.85,5,'Email Address','T',0,'C');
        PDF::setFont('helvetica','',9);
        PDF::ln();

        
        PDF::Cell(145,5,'',0,0,'C');
        PDF::Cell(50.85,5,$res->canvass_date,0,0,'C');
        PDF::ln();

        PDF::Cell(145,5,'',0,0,'C');
        PDF::setFont('helvetica','B',9);
        PDF::Cell(50.85,5,'Date','T',0,'C');
        PDF::setFont('helvetica','',9);
    }

    public function abstract_footer($abstract_res)
    {   
        $y = PDF::GetY();
        if ($y > 195) {
            PDF::addPage();
        }
        foreach ($abstract_res as $res) 
        {
            // $ex = (explode(",",$res->committee));
            PDF::Cell(335.55,5,'',0,0,'C');
            PDF::ln();
            PDF::Cell(335.55,5,'',0,0,'C');
            PDF::ln();
        
        $committees = explode(",",$res->committees);
        $employees = $this->bacAbstractofCanvassRepository->get_hr_employee($committees);
        $countData = count($committees); // 7
        $rows = intval($countData / floatval(5)); // 1.2
        $static = 5; 
        $fullwidth = 335.55;
        $width = $fullwidth / $static;
        $remainder = fmod(count($committees), $static); // 2
        $increment = 0; 
        foreach ($employees as $employee) 
        {
            if (floatval($rows) > 0) { //1
                if ($increment < $static) 
                { 
                    $y = PDF::GetX();
                    $y2 = PDF::GetY();
                    PDF::MultiCell($width, 5,$employees[$increment]->fullname. '
'.$employees[$increment]->position, 0, 'C',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
                    $increment++; $countData--;
                } 
                else 
                {
                    PDF::ln();
                    $rows--;
                    PDF::Cell(335.55,5,'',0,0,'C');
                    PDF::ln();
                    PDF::Cell(335.55,5,'',0,0,'C');
                    PDF::ln();
                }
            } 
            else 
            {
                while ($countData != 0) 
                {
                    $x = PDF::GetY();
                    PDF::MultiCell($fullwidth/$remainder, 5,$employees[$increment]->fullname. '
'.$employees[$increment]->position, 0, 'C',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
                    $increment++; 
                    $countData--;
                    
                } 
            }
        }
        PDF::ln();
        PDF::Cell(335.55,5,'',0,0,'C');
        PDF::ln();
        $a = PDF::GetY();
        PDF::Cell(335.55,5,'',0,0,'C');
        
        PDF::ln();
        PDF::Cell(335.55,5,$res->fullname,0,0,'C');
        PDF::ln();
        PDF::Cell(335.55,5,'TWG Head',0,0,'C');
        }
    }
    //method
    
    public function print_rfq2(Request $request, $controlNo)
    {  
        $itemsList = $this->bacRequestForQuotationRepository->item_list($controlNo);
        $supplier_res = $this->bacRequestForQuotationRepository->get_supplier($controlNo);
        $note_dates = $this->bacRequestForQuotationRepository->getNoteDates($controlNo);
        $get_agencies = $this->bacRequestForQuotationRepository->get_agencies_via_control_no($controlNo);

        $rfq_array = array();
        $page = 1;
            
        foreach ($supplier_res as $res) {
            
            $rfqID = $res->rfqID;         
            $rfq_array[] = $res->rfq_no;

            $asd = strtotime($res->deadline_date);
            $dsa = strtotime($res->quotation_date);

            $deadline_date = date("M d, Y", $asd);
            $quotation_date = date("M d, Y", $dsa);

            PDF::SetTitle('Request For Quotation ('.$controlNo.')');    
            PDF::SetMargins(10, 10, 10,true);    
            PDF::SetAutoPageBreak(TRUE, 0);
            PDF::AddPage('P', 'LEGAL');
            PDF::SetFont('helvetica','',9);
            PDF::Cell(195.85,6,'Republic of the Philippines','',0,'C');
            PDF::ln();

            PDF::Cell(195.85,3,'Province of Nueva Ecija','',0,'C');
            PDF::ln();

            PDF::SetFont('helvetica','B',9);
            PDF::Cell(195.85,3,'CITY GOVERNMENT OF PALAYAN','',0,'C');
            PDF::ln();
            PDF::ln();
            
            PDF::Cell(40, 2,'Project Reference',0,0,'L');
            PDF::ln();
            PDF::Cell(40, 2,'Number:',0,0,'L');
            PDF::SetFont('helvetica', '', 9);
            PDF::MultiCell(155.85, 2,$res->control_no, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();

            PDF::SetFont('helvetica', 'B', 9);
            PDF::Cell(40,2,'Name of the Project:',0,0,'L');
            PDF::SetFont('helvetica', '', 9);
            PDF::MultiCell(155.85, 2, $res->rfq->project_name, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();

            PDF::SetFont('helvetica', 'B', 9);
            PDF::Cell(40,2,'Requesting Agency:',0,0,'L');
            PDF::SetFont('helvetica', '', 9);
            PDF::MultiCell(155.85, 2,$get_agencies, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();
            PDF::ln();

            PDF::setFont('helvetica','B',9);
            PDF::Cell(195.85,3,'REQUEST FOR QUOTATION',0,0,'C');
            PDF::ln();
            PDF::ln();

            PDF::Cell(40,2,'Company Name',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::MultiCell(80, 2,$res->supplier->business_name, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::setFont('helvetica','B',9);
            PDF::Cell(30,2,'Date',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::MultiCell(45.85, 2,$res->quotation_date, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();

            PDF::setFont('helvetica','B',9);
            PDF::Cell(40,2,'Address',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::MultiCell(80, 2,$res->supplier->address, 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::setFont('helvetica','B',9);
            PDF::Cell(30,2,'Quotation No.',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::MultiCell(45.85, 2,implode(', ', $rfq_array), 'B', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();
            PDF::ln();

            PDF::setFont('helvetica','',9);
            PDF::MultiCell(195.85, 2,'    Please quote your lowest price on the item/s listed below, subject to the General Conditions on the last page, stating the shortest time of delivery and submit your quotation duly signed by your representative not later than '.$deadline_date.' in the return envelope attached herewith. The opening of quotation will be on '.$quotation_date.'' , '', 'L',0,0,'','',true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
            PDF::ln();
            PDF::ln();

            PDF::Cell(130,2,'',0,0,'L');
            PDF::Cell(65.85,2,$res->fullname,'B',0,'C');
            PDF::ln();
            PDF::Cell(130,2,'',0,0,'L');
            PDF::Cell(65.85,2,'BAC SECRETARIAT',0,0,'C');
            PDF::ln();

            PDF::Cell(13,2,'NOTE:',0,0,'L');
            PDF::Cell(81,2,'1. THE APPROVED BUDGET FOR THE CONTRACT IS',0,0,'L'); //1
            PDF::setFont('helvetica','B',9);
            PDF::Cell(100.85,2,$res->total_budget,0,0,'L');
            PDF::ln();

            PDF::Cell(13,2,'',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::Cell(40,2,'2. ALL ENTRIES MUST BE',0,0,'L');                      //2
            PDF::setFont('helvetica','B',9);
            PDF::Cell(142.85,2,'LEGIBLY WRITTEN',0,0,'L');
            PDF::ln();

            PDF::Cell(13,2,'',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::Cell(45,2,'3. DELIVERY PERIOD WITHIN',0,0,'L');                   //3
            PDF::setFont('helvetica','B',9);
            PDF::Cell(132.85,2,$res->delivery_period,0,0,'L');
            PDF::ln();
            // 182.85
            PDF::setFont('helvetica','',9);
            PDF::Cell(13,2,'',0,0,'L');
            foreach ($note_dates as $note_date) 
            {
                PDF::Cell(61,2,'4. WARRANTY SHALL BE A PERIOD OF ',0,0,'L');
                $warrantyExpCode = '<b>'.$note_date->warrantyExpCode.'</b>';
                PDF::writeHTML($warrantyExpCode, false, false, false, false, '');

                PDF::Cell(50,2,'FOR EXPENDABLE SUPPLIES &',0,0,'L');
                $nonWarrantyExpCode = '<b>'.$note_date->nonWarrantyExpCode.'</b>';
                PDF::writeHTML($nonWarrantyExpCode, false, false, false, false, '');
                PDF::Cell(60,2,'FOR',0,0,'L');
                PDF::ln();

                PDF::Cell(13,2,'',0,0,'L');
                PDF::Cell(182.85,2,' NON-EXPENDABLE SUPPLIES FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY.',0,0,'L');
                PDF::ln();

                PDF::Cell(13,2,'',0,0,'L');
                PDF::Cell(75,2,'5. PRICE VALIDITY SHALL BE FOR A PERIOD OF',0,0,'L');
                $priceValidityCode = '<b>'.$note_date->priceValidityCode.'</b>';
                PDF::writeHTML($priceValidityCode, false, false, false, false, '');
                PDF::ln();
            }
            PDF::Cell(13,2,'',0,0,'L');
            PDF::Cell(4.5,2,'6. ',0,0,'L');
            $business_name = '<b>'.$res->supplier->business_name.'</b>';
            PDF::writeHTML($business_name, false, false, false, false, '');
            PDF::Cell(116.85,2,'SHALL BE ATTACHED UPON SUBMISSIONS OF THE QUOTATION.',0,0,'L');
            PDF::ln();
            
            PDF::Cell(13,2,'',0,0,'L');
            PDF::Cell(30,2,'7. BIDDERS SHALL',0,0,'L');
            PDF::setFont('helvetica','B',9);
            PDF::Cell(52,2,'SUBMIT ORIGINAL BROCHURES',0,0,'L');
            PDF::setFont('helvetica','',9);
            PDF::Cell(105.85,2,'SHOWING SPECIFICATIONS OF THE PRODUCT BEING OFFERED',0,0,'L');
            PDF::ln();
            
            $tbl = '<table id="purhcase-order-print-1-table" width="100%" cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                                <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Item No.
                                </td>
                                <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    QTY
                                </td>
                                <td colspan="1" align="center" width="8%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Unit
                                </td>
                                <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Description
                                </td>
                                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Brand & Model
                                </td>
                                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Unit Price
                                </td>
                                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Total Amount
                                </td>
                            </tr>
                        </thead>';
            $item_no = 0;$total_cost = 0;$page=1;
            // for ($i=0; $i < 12; $i++) { 
                
            
                foreach ($itemsList as $index => $items) 
                {
                    $item_no++;

                    $rfqID = $items->rfq_id;
                    $supplierID = $res->supplier_id;
                    $itemID = $items->item->id;

                    $canvass_details = $this->bacRequestForQuotationRepository->find_canvass($rfqID, $supplierID, $itemID);
                    foreach ($canvass_details as $canvass_detail) 
                    {
                        $tbl .= '
                        <tr>
                            <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$item_no.'
                            </td>
                            <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->itemQuantity.'
                            </td>
                            <td colspan="1" align="center" width="8%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->code.'
                            </td>
                            <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->item->code.'
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$canvass_detail->description.'
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; padding: 1px">
                            '.$canvass_detail->unit_cost.'
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->quantity_requested*$canvass_detail->unit_cost.'
                            </td>
                        </tr>';
                        
                        $total_cost += $items->quantity_requested*$canvass_detail->unit_cost;
                        
                    }
                }
            // }
            $tbl .= '</table>';
            
            PDF::writeHTML($tbl, false, false, false, false, '');
            
            $max_height = PDF::getY();
            $min_height = PDF::getY();
            PDF::SetLineStyle(array('width' => .25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            PDF::Cell(11.751,3,'',1,0,'C');
            PDF::Cell(11.751,3,'',1,0,'C');
            PDF::Cell(15.668,3,'',1,0,'C');
            PDF::setFont('helvetica','B',9);
            PDF::Cell(58.755,3,'***Nothing Follows***',1,0,'C');
            PDF::Cell(39.17,3,'',1,0,'C');
            PDF::Cell(29.3775,3,'',1,0,'C');
            PDF::Cell(29.3775,3,'',1,0,'C');
            PDF::ln();

            while ($min_height < 270)
            {
                PDF::Cell(11.751,3,'',1,0,'C');
                PDF::Cell(11.751,3,'',1,0,'C');
                PDF::Cell(15.668,3,'',1,0,'C');
                PDF::setFont('helvetica','B',9);
                PDF::Cell(58.755,3,'',1,0,'C');
                PDF::Cell(39.17,3,'',1,0,'C');
                PDF::Cell(29.3775,3,'',1,0,'C');
                PDF::Cell(29.3775,3,'',1,0,'C');
                PDF::ln();
                $min_height = $min_height + 4.21875;
            }

            if ($max_height > 270 && $page == 1)
            {
                
                PDF::addPage();
                $page++;
                PDF::SetXY(10, 30);
                $tbl = '<table id="purhcase-order-print-1-table" width="100%" cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                                <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Item No.
                                </td>
                                <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    QTY
                                </td>
                                <td colspan="1" align="center" width="8%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Unit
                                </td>
                                <td colspan="1" align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Description
                                </td>
                                <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Brand & Model
                                </td>
                                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Unit Price
                                </td>
                                <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    Total Amount
                                </td>
                            </tr>
                        </thead>';
                $tbl .= '</table>';
            
                PDF::writeHTML($tbl, false, false, false, false, '');

                $min_height = PDF::getY();
                while ($min_height < 270)
                {
                    PDF::Cell(11.751,3,'',1,0,'C');
                    PDF::Cell(11.751,3,'',1,0,'C');
                    PDF::Cell(15.668,3,'',1,0,'C');
                    PDF::setFont('helvetica','B',9);
                    PDF::Cell(58.755,3,'',1,0,'C');
                    PDF::Cell(39.17,3,'',1,0,'C');
                    PDF::Cell(29.3775,3,'',1,0,'C');
                    PDF::Cell(29.3775,3,'',1,0,'C');
                    PDF::ln();
                    $min_height = $min_height + 4.21875;
                }
            }
            
            
            
            PDF::Cell(11.751,3,'',1,0,'C');
            PDF::Cell(11.751,3,'',1,0,'C');
            PDF::Cell(15.668,3,'',1,0,'C');
            PDF::setFont('helvetica','B',9);
            PDF::Cell(58.755,3,'',1,0,'C');
            PDF::Cell(39.17,3,'',1,0,'C');
            PDF::Cell(29.3775,3,'',1,0,'C');
            PDF::Cell(29.3775,3,$total_cost,1,0,'R');
            PDF::ln();

            PDF::setFont('helvetica','B',9);
            PDF::Cell(36.17,3,'Total amount in words:','LB',0,'L');
            PDF::setFont('helvetica','B',9);

            PDF::MultiCell(159.68, 3, 'One Million Two Hundred Thousand ', 'RB','L', 0, 0, '', '', true);

            $this->rfq_footer($res);
        }
        PDF::Output('request_for_quotation_'.$controlNo.'.pdf');
    }

    public function print_resolution(Request $request, $controlNo)
    {
        $get_agencies = $this->bacRequestForQuotationRepository->get_agencies_via_control_no($controlNo);
        $res = $this->bacResolutionRepository->get_resolution_details($controlNo);
        if ($res->status == 'draft') {
            return abort(404);
        }

        $pr_res = $this->bacResolutionRepository->get_all_pr($controlNo);
        $pr_array = array();
        foreach ($pr_res as $pr) 
        {
            $pr_array[] = $pr->purchase_request_no;

        }

        $created_at = strtotime($res->created_at);
        $date_convert = date("M d, Y",$created_at);
        $date_js = date("jS",$created_at);
        $date_my = date("M, Y",$created_at);
        $date = '<b>'.$date_convert.'</b>';


        PDF::SetTitle('Resolution ('.$controlNo.')');
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::Cell(195.85,3,'','',0,'C');
        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::ln();
        // PDF::Image(public_path('assets\images\palayan-logo-gso.png'), $x=90, $y=5, $w=35, $h='', 'PNG');
        PDF::Image(url('./assets/images/palayan-logo-gso.png'), 90, 5, 35, '', 'PNG', 'http://www.palayan.com', '', false, 150, '', false, false, 1, false, false, false);

        

        PDF::SetFont('helvetica','',7);
        PDF::Cell(195.85,4,'Republic of the Philippines','',0,'C');
        PDF::ln();

        PDF::Cell(195.85,4,'Province of Nueva Ecija','',0,'C');
        PDF::ln();

        PDF::Cell(195.85,4,'Palayan City','',0,'C');
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(195.85,4,'BIDS AND AWARDS COMMITTEE','',0,'C');
        PDF::ln();

        PDF::Cell(195.85,4,'BAC RESOLUTION RECOMMENDING APPROVAL AND AWARD','',0,'C');
        PDF::ln();

        PDF::Cell(195.85,4,'Series 2022','',0,'C');
        PDF::ln();
        PDF::ln();

        PDF::Cell(16,4,'WHEREAS,',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(179.85,4,'LGU - Palayan City intend to procure the following items through Small Value Procurement considering these items are not available in the ',0,0,'L');
        PDF::ln();

        PDF::Cell(195.85,4,'Procurement Services and involving an amount not exceeding the threshold prescribed under IRR of RA 9184',0,0,'L');
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','BU',8);
        PDF::Cell(10,4,'',0,0,'L');
        PDF::Cell(185.85,4,$res->project_name,0,0,'L');
        PDF::ln();

        PDF::Cell(10,4,'',0,0,'L');
        PDF::Cell(185.85,4,'Requesting Agency: '.$get_agencies,0,0,'L');
        PDF::ln();

        PDF::Cell(10,4,'',0,0,'L');
        PDF::Cell(185.85,4,'PR No.: '.implode(', ', $pr_array),0,0,'L');
        PDF::ln();

        PDF::Cell(10,4,'',0,0,'L');
        PDF::Cell(185.85,4,'ABC: '.$res->total_budget,0,0,'L');
        PDF::ln();
        PDF::ln();

        PDF::Cell(16,4,'WHEREAS,',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(179.85,4,'LGU - Palayan City has posted an invitation of request for submission of price quotation for the foregoing item(s) in PhilGEPS website and ',0,0,'L');
        PDF::ln();
        
        PDF::Cell(195.85,4,'City\'s conspicious places;',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(16,4,'WHEREAS, ',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(179.85,4,'in response to the said advertisement only, Three supplier(s) submitted quotation as evidenced by hereto attached abstract of bid;',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(16,4,'WHEREAS, ',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(157.85,4,'the sealed proposal for furnishing and delivering of the above items was opened by (BAC) Bids & Award Committee Office on ',0,0,'L');
        // PDF::Cell(22,4,,0,0,'L');
        PDF::writeHTML($date, false, false, false, false, '');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(195.85,4,'and the result of which is as follows:',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(16,4,'WHEREAS, ',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(179.85,4,'lowest complying offer is as follows:',0,0,'L');
        PDF::ln();
        PDF::ln();

        $tbl = '<table id="purhcase-order-print-1-table" width="100%" cellspacing="0" cellpadding="1" border="1">
                    <thead>
                        <tr>
                            <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                                <b>Qty</b>
                            </td>
                            <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                                <b>Unit</b>
                            </td>
                            <td colspan="1" align="center" width="43%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                                <b>Description</b>
                            </td>
                            <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                                <b>Total Amount</b>
                            </td>
                            <td colspan="1" align="center" width="25%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                                <b>Supplier</b>
                            </td>
                        </tr>
                    </thead>';
        $tbl .= '<tr>
                    <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                    
                    </td>
                    <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                        
                    </td>
                    <td colspan="1" align="center" width="43%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                        <b>'.$res->project_name.'</b> 
                    </td>
                    <td colspan="1" align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                        <b>'.$res->total_canvass.'</b>
                    </td>
                    <td colspan="1" align="center" width="25%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px">
                        <b>'.$res->business_name.'</b>
                    </td>
                </tr>';
        $tbl .= '</table>';

        PDF::writeHTML($tbl, false, false, false, false, '');

        PDF::ln();
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(7,4,'Now,',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(90,4,'therefore, we the members of the Birds and Awards Committee hereby ',0,0,'L');

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(15,4,'RESOLVE',0,0,'L');

        PDF::SetFont('helvetica','',8);
        PDF::Cell(18,4,'as it is hereby',0,0,'L');

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(65.85,4,'RESOLVED:',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(10,4,'a.',0,0,'L');
        PDF::Cell(16,4,'To resort to',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        PDF::MultiCell(36.3, 4, 'Small Value Procurement', 0,'L', 0, 0, '', '', true);
        PDF::SetFont('helvetica','',8);
        PDF::MultiCell(121.55, 4, 'a mode of procurement for the foregoing item;', 0,'L', 0, 0, '', '', true);
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(10,4,'b.',0,0,'L');
        PDF::Cell(5,4,'To',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        $business_name = '<b>'.$res->business_name.'</b>';
        PDF::writeHTML($business_name, false, false, false, false, '');
        // PDF::MultiCell(59, 4, $res->business_name, 0,'L', 0, 0, '', '', true);
        PDF::SetFont('helvetica','',8);
        PDF::MultiCell(109.85, 4, 'with the lowest offer and accept the same; and', 0,'L', 0, 0, '', '', true);
        PDF::ln();

        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(10,4,'c.',0,0,'L');
        PDF::Cell(81,4,'To recommend to the Head of the Procuring Entity the award to ',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        PDF::writeHTML($business_name, false, false, false, false, '');
        // PDF::MultiCell(93.85, 3, $res->business_name, 0,'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(30,4,'RESOLVED FINALLY,',0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::Cell(153.85,4,'that a copy of this resolution be furnished the General Services Office requesting the latter to prepare the purchase order',0,0,'L');
        PDF::ln();

        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(14,4,'in favor of',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        PDF::writeHTML($business_name, false, false, false, false, '');
        // PDF::MultiCell(58.5, 3, $res->business_name, 0,'L', 0, 0, '', '', true);
        PDF::SetFont('helvetica','',8);
        PDF::Cell(111.35,4,'under the terms and conditions set forth in the request quotation and implementing rules',0,0,'L');
        PDF::ln();

        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(183.85,4,'and regulations on the matter.',0,0,'L');
        PDF::ln();
        PDF::ln();
        // $height = PDF::GetY();
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(20,4,'',0,0,'L');
        PDF::Cell(50,4,'DONE in the City of Palayan this day',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        PDF::Cell(8,4,$date_js,0,0,'L');
        PDF::SetFont('helvetica','B',8);
        PDF::Cell(10,4,'day of',0,0,'L');
        PDF::SetFont('helvetica','BU',8);
        PDF::Cell(107.85,4,$date_my,0,0,'L');
        PDF::SetFont('helvetica','',8);
        PDF::ln();
        PDF::ln();

        // $names = array("Kenneth", "Jherome", "Eddie", "Din", "Christy", "Lanie");
        // $positions = array("BAC Chairman", "BAC Vice Chairman", "BAC Member", "BAC Member", "BAC Member", "BAC Member");

        $committees = (explode(",",$res->committees));
        $get_employee = $this->bacResolutionRepository->get_hr_employee($controlNo, $committees);
        PDF::ln();
        PDF::ln();
        
            $x1 = 22; $x2 = 108; $i = 0; $iteration = 1; $y = 0;
            foreach ($get_employee as $name)
            {

                if ($iteration % 2 == 0) {
                    // echo $name->user_id;
                    PDF::SetXY($x2, $y);
                    PDF::SetFont('helvetica','B',10);
                    PDF::MultiCell(85.925,4, $name->fullname, 0,'L', 0, 0, '', '', true);
                    PDF::ln();
                    
                    $y = PDF::GetY();
                    
                    PDF::SetXY($x2, $y);
                    PDF::SetFont('helvetica','',10);
                    PDF::MultiCell(85.925,4, $name->position, 0,'L', 0, 0, '', '', true);
                    PDF::ln();
                    PDF::ln();
                    PDF::ln();
                    PDF::ln();
                    
                } else {
                    // echo $name->user_id;
                    $y = PDF::GetY();
                    
                    PDF::Cell(12,4, '', 0, 0, 'L');
                    PDF::SetXY($x1, $y);
                    PDF::SetFont('helvetica','B',10);
                    PDF::MultiCell(85.925,4, $name->fullname, 0,'L', 0, 0, '', '', true);
                    PDF::ln();

                    PDF::Cell(12,4, '', 0, 0, 'L');
                    PDF::SetFont('helvetica','',10);
                    PDF::MultiCell(85.925,4, $name->position, 0,'L', 0, 0, '', '', true);
                    PDF::ln();
                    PDF::ln();
                }
                $iteration++; $i++;
            }
        
        PDF::SetFont('helvetica','',8);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(183.85,4,'I hereby certify to the correctness of the foregoing resolution.',0,0,'L');
        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(12,4,'',0,0,'L');
        
        PDF::Cell(183.85,4,$res->BacSecretariat,0,0,'L');
        PDF::ln();
        // dd($res);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(183.85,4,'BAC Secretariat',0,0,'L');
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(183.85,4,'Approved:',0,0,'L');
        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(183.85,4,$res->Mayor,0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',10);
        PDF::Cell(12,4,'',0,0,'L');
        PDF::Cell(183.85,4,'City Mayor/Head of Procuring Entity',0,0,'L');
        PDF::ln();
        ob_end_clean();
        $string = $res->committees;
            $values = explode(',', $string);
            if (count($values) >= 2) {
                $a = $values[0];
                $b = $values[1];
                $preparedbyId = HrEmployee::where('id', $a)->first();
                 // echo $preparedbyId->user_id; exit;
                $approvedId = HrEmployee::where('id', $b)->first();
                // echo $approvedId->user_id; exit;
                $filename = 'Resolution_'.$controlNo.'.pdf';
                $arrSign= $this->_commonmodel->isSignApply('gso_resolution_bac_member');
                $isSignVeified = isset($arrSign)?$arrSign->status:0;
                $arrCertified= $this->_commonmodel->isSignApply('gso_resolution_bac_secretariat');
                $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

                $signType = $this->_commonmodel->getSettingData('sign_settings');
                
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                if($signType==2){
                    PDF::Output($folder.$filename,'F');
                    @chmod($folder.$filename, 0777);
                }
                $arrData['filename'] = $filename;
                $arrData['isMultipleSign'] = 1;
                $arrData['isDisplayPdf'] = 0;
                $arrData['isSavePdf'] = 0;
                
                $varifiedSignature = $this->_commonmodel->getuserSignature($preparedbyId->user_id);
                $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

                if($isSignVeified==1 && $signType==2){
                    if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                        $arrData['isSavePdf'] = 1;
                        $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                        $arrData['signaturePath'] = $varifiedSignature;
                        if($isSignCertified==0 && $signType==2){
                            $arrData['isDisplayPdf'] = 1;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }else{
                            $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                }

                $certifiedSignature = $this->_commonmodel->getuserSignature($approvedId->user_id);
                $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

                if($isSignCertified==1 && $signType==2){
                    if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                        $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                        $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                        $arrData['isDisplayPdf'] = 1;
                        $arrData['signaturePath'] = $certifiedSignature;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                }

                if($isSignCertified==1 && $signType==1){
                    // Apply E-sign Here
                    if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                        PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
                    }
                }
                if($isSignVeified==1 && $signType==1){
                    // Apply E-sign Here
                    if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                        PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                    }
                }
                if($signType==2){
                    if(File::exists($folder.$filename)) { 
                        File::delete($folder.$filename);
                    }
                }
                PDF::Output($filename,"I");
            } else {
                echo 'Invalid string format: ' . $string . '<br>';
            }
        // PDF::Output('Resolution'.$controlNo.'.pdf');
    
    }
    public function print_abstract2(Request $request, $controlNo)
    {
        $get_agencies = $this->bacRequestForQuotationRepository->get_agencies_via_control_no($controlNo);
        $supplier_res = $this->bacRequestForQuotationRepository->get_supplier($controlNo);
        $itemsList = $this->bacRequestForQuotationRepository->item_list($controlNo);
        $rfq_res = $this->bacAbstractofCanvassRepository->get_rfq_details($controlNo);
        $abstract_res = $this->bacAbstractofCanvassRepository->get_abstract_details($controlNo);
        
            $res_date = strtotime($rfq_res->approved_at);
            // dd($res_date);
            $approved_date = date("F d, Y", $res_date);
            $suppliers = array();
            $page = 1;
            PDF::SetTitle('Abstract of Canvass ('.$controlNo.')');    
            PDF::SetMargins(10, 10, 10,true);    
            PDF::SetAutoPageBreak(TRUE, 0);
            PDF::AddPage('L', 'LEGAL');

            PDF::SetFont('helvetica','B',9);
            PDF::Cell(335.55,4,'ABSTRACT OF CANVASS',0,0,'C');
            PDF::ln();

            // PDF::SetFont('helvetica','U',9);
            // PDF::Cell(153,5,'',0,0,'C');
            PDF::Cell(335.55,5,'Date: '.$approved_date ,0,0,'C');
            // PDF::Cell(20,5,'','B',0,'C');
            // $date_html = '<u> December 22, 2023</u>';
            // PDF::writeHTML($date_html, false, false, false, false, '');
            
            PDF::ln();

            PDF::SetFont('helvetica','',9);
            
            PDF::Cell(335.55,5,'Name of Project: '.$rfq_res->project_name,0,0,'L');
            PDF::ln();

            PDF::SetFont('helvetica','',9);
            PDF::Cell(335.55,5,'Requesting Agency: '.$get_agencies,0,0,'L');
            PDF::ln();

            PDF::SetFont('helvetica','',9);
            PDF::Cell(335.55,5,'ABC: '.$rfq_res->total_budget,0,0,'L');
            PDF::ln();

            $tbl = '<table id="purhcase-order-print-1-table" width="100%" cellspacing="0" cellpadding="1" border="1">
                        <thead>
                            <tr>
                                <td rowspan="3" colspan="1" align="center" width="3%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    <b>Item No.</b>
                                </td>
                                <td rowspan="3" colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    <b>Articles/Items</b>
                                </td>
                                <td rowspan="3" colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    <b>Qty.</b>
                                </td>
                                <td rowspan="3" colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                    <b>Unit</b>
                                </td>';
            // supplier loop
            foreach ($supplier_res as $supplier_name) {
                $suppliers[] = $supplier_name->supplier->id;
                $tbl .= '<td rowspan="1" colspan="4" align="center" width="24%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                        <b>'.$supplier_name->supplier->business_name.'</b>
                        </td>';
            }
            $supplier_count = count($supplier_res);
            $tbl .=        '
                        </tr>';
            
            $tbl .=         '<tr>';
            
            for ($i=0; $i < $supplier_count; $i++) 
            { 
                
                $tbl .=     '<td rowspan="1" colspan="1" align="center">Unit Cost</td>
                            <td rowspan="1" colspan="1" align="center">Total</td>
                            <td rowspan="1" colspan="2" align="center">Brand and Model</td>';
            }
            $tbl .= '       </tr>';
            $tbl .= '</thead>';
                $item_no = 0; $page=1; $totalArr = array();
                foreach($suppliers as $supplier) {
                    $totalArr[$supplier] = 0;
                }
                foreach ($itemsList as $index => $items) 
                {
                    $item_no++;
                    $rfqID = $items->rfq_id;
                    $itemID = $items->item->id;
                    $canvass_details = $this->bacRequestForQuotationRepository->find_canvass($rfqID, $suppliers, $itemID);
                    foreach ($canvass_details as $canvass_detail) 
                    {
                        $tbl .= '
                        <tr>
                            <td colspan="1" align="center" width="3%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$item_no.'
                            </td>
                            <td colspan="1" align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->item->code.'
                            </td>
                            <td colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->quantity_requested.'
                            </td>   
                            <td colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            '.$items->code.'
                            </td>';
                        
                            foreach ($supplier_res as $supplier_name2)
                            {
                                $supplierID2 = $supplier_name2->supplier_id;
                                // $groups ?? []
                                $canvass_details = $this->bacAbstractofCanvassRepository->find_canvass($rfqID, $supplierID2, $itemID);
                                foreach ($canvass_details as $detail) 
                                {
                                    $totalArr[$supplierID2] += floatVal($detail->total_cost);
                                    $tbl .= '
                                        <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                        '.$detail->unit_cost.'
                                        </td>
                                        <td colspan="1" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                        '.$detail->total_cost.'
                                        </td>
                                        <td colspan="1" align="center" width="12%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                        '.$detail->description.'
                                        </td>
                                        ';
                                }
                                // $totals_cost = $totals_cost + $detail->total_cost;
                            }
                        $tbl .= '</tr>';
                    }
                }

            $tbl .= '<tr>
                <td colspan="3" align="center" width="28%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                    
                </td>';
            // loop here 
            foreach ($supplier_res as $supplier_name2) {
                // $items->quantity_requested*$detail->unit_cost;
                $tbl .= '<td colspan="3" align="center" width="6%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                    Total
                </td>
                <td colspan="3" align="center" width="18%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 14px">
                    '. $totalArr[$supplier_name2->supplier_id] .'
                </td>';
            }
            $tbl .= '</tr>
                        <tr>
                        <td colspan="3" align="center" width="28%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                            
                        </td>';
            // loop here
            foreach ($supplier_res as $canvassed)
                            {
                                $tbl .= '
                                    <td colspan="3" align="center" width="8%" style="border-top: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px">
                                        Canvassed By:
                                    </td>
                                    <td colspan="3" align="center" width="16%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; font-size: 12px">
                                        '.$canvassed->canvass_by.'
                                    </td>';
                            }
                

                $tbl .= '   </tr> 
                        </table>';
                
                PDF::writeHTML($tbl, false, false, false, false, '');

                PDF::Cell(10,5,'',1,0,'L');
                PDF::Cell(30,5,'Remarks Reason: ',1,0,'L');
                PDF::MultiCell(295.55, 5,$rfq_res->remarks, 1,'L', 0, 0, '', '', true);
                PDF::ln();

                PDF::Cell(10,5,'',1,0,'L'); 
                PDF::Cell(30,5,'Recommendation: ',1,0,'L');
                PDF::MultiCell(295.55, 5, $rfq_res->recommendations, 1,'L', 0, 0, '', '', true);
                PDF::ln();

                $this->abstract_footer($abstract_res);                
                    
                
        PDF::Output('abstract_of_canvass_'.$controlNo.'.pdf');
    }

    public function print_abstract(Request $request, $controlNo)
    {   
        $res = $this->bacAbstractofCanvassRepository->find_abstract_via_column('control_no', $controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
         // echo $res; exit;
        $agencies = $this->bacRequestForQuotationRepository->get_agencies($res->identity);
        $totalBudget = $this->bacRequestForQuotationRepository->computeTotalBudget($res->identity);
        $abstract_res = $this->bacAbstractofCanvassRepository->get_abstract_details($controlNo);

        PDF::SetTitle('Abstract of Canvass ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LEGAL');

        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(335.55, 5, 'ABSTRACT OF CANVASS', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(335.55, 5, 'June 06, 2023', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(335.55, 5, 'Name of Project: ' . $res->rfq->project_name, 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(335.55, 5, 'Requesting Agency: ' . $agencies, 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(335.55, 5, 'Approved Budget Cost: ' . $this->money_format($totalBudget), 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', '', 10);
        $page = 0;
        $listItems = $this->bacRequestForQuotationRepository->find_rfq_lines_via_column('bac_rfqs.control_no', $controlNo);
        if(!empty($listItems)) {
            $tbl[$page] = '<table id="rfq-print-1-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
            $tbl[$page] .= '<thead>';
            $tbl[$page] .= '<tr style="background-color:#ccc;">';
            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Item No.<div style="font-size:5pt">&nbsp;</div></th>';
            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="12%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Article / Items<div style="font-size:5pt">&nbsp;</div></th>';
            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Qty<div style="font-size:5pt">&nbsp;</div></th>';
            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Unit<div style="font-size:5pt">&nbsp;</div></th>';
            /**
             * Suppliers 
             * loop through hell
             */
            $suppliers = $this->bacRequestForQuotationRepository->find_rfq_suppliers_via_column('bac_rfqs.control_no', $controlNo); 
            if (!empty($suppliers)) {
                $supplierWidth = floatval(75) / count($suppliers); 
                $colWidth = floatval($supplierWidth) / 3; 
                foreach ($suppliers as $supplier) 
                {
                    $tbl[$page] .= '<th rowspan="1" colspan="3" align="center" width="'.$supplierWidth.'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">'.$supplier->business_name.'</th>';
                }
                $tbl[$page] .= '</tr>';
                $tbl[$page] .= '<tr style="background-color:#ccc;">';
                foreach ($suppliers as $supplier) 
                {
                    $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth + 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">BRAND / MODEL</th>';
                    $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">COST</th>';
                    $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">TOTAL</th>';
                }
                $tbl[$page] .= '</tr>';
            }  
            $tbl[$page] .= '</thead>';
            $tbl[$page] .= '<tbody>';
            $counter = 1; $totalCost = 0; 
            foreach ($listItems as $listItem)
            {   
                if (strlen($listItem->pr_remarks) > 0) { 
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->pr_remarks . ')', 25, "\n");
                } else if (strlen($listItem->itemRemarks) > 0) {
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->itemRemarks . ')', 25, "\n");
                } else { 
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name, 25, "\n"); 
                } 
                
                $tbl[$page] .= '<tr>';
                $tbl[$page] .= '<td colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$counter.'</td>';
                $tbl[$page] .= '<td colspan="1" align="left" width="12%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$this->parseDesc($description).''.wordwrap($description, 50,'\n').'</td>';
                $tbl[$page] .= '<td colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$listItem->itemQuantity.'</td>';
                $tbl[$page] .= '<td colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$listItem->uom->code.'</td>';
                foreach ($suppliers as $supplier) 
                {
                    $canvass = $this->bacRequestForQuotationRepository->find_canvass($res->identity, $supplier->id, $listItem->item->id);
                    $canvassLine = (object) [ 
                        'brand' => ($canvass->count() > 0) ? ($canvass->first()->description) ? $canvass->first()->description : '' : '',
                        'unit_cost' => ($canvass->count() > 0) ? ($canvass->first()->unit_cost) ? $canvass->first()->unit_cost : '' : '',
                        'total_cost' => ($canvass->count() > 0) ? ($canvass->first()->total_cost) ? $canvass->first()->total_cost : '' : '',
                        'remarks' => ($canvass->count() > 0) ? ($canvass->first()->remarks) ? $canvass->first()->remarks : '' : '',
                    ];
                    $tbl[$page] .= '<td colspan="1" align="center" width="'.($colWidth + 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$canvassLine->brand.'</td>';
                    $tbl[$page] .= '<td colspan="1" align="right" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$canvassLine->unit_cost.'</td>';
                    $tbl[$page] .= '<td colspan="1" align="right" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">'.$canvassLine->total_cost.'</td>';
                }
                $tbl[$page] .= '</tr>';
                $counter++;
                if ($page == 0) {
                    if (floatval($this->iteration) > 22) {
                    // if (floatval($this->iteration) > 15) {
                        if (count($listItems) > $counter) {
                            $tbl[$page] .= '</tbody>';
                            $tbl[$page] .= '</table>';
                            PDF::writeHTML($tbl[$page], false, false, false, false, '');
                            PDF::AddPage('L', 'LEGAL');
                            $page++;
                            $this->iteration = 1;
                            $tbl[$page] = '<table id="rfq-print-1-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
                            $tbl[$page] .= '<thead>';
                            $tbl[$page] .= '<tr  style="background-color:#ccc;">';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Item No.<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="12%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Article / Items<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Qty<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Unit<div style="font-size:5pt">&nbsp;</div></th>';
                            
                            foreach ($suppliers as $supplier) 
                            {
                                $tbl[$page] .= '<th rowspan="1" colspan="3" align="center" width="'.$supplierWidth.'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">'.$supplier->business_name.'</th>';
                            }
                            $tbl[$page] .= '</tr>';
                            $tbl[$page] .= '<tr  style="background-color:#ccc;">';
                            foreach ($suppliers as $supplier) 
                            {
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth + 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">BRAND / MODEL</th>';
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">COST</th>';
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">TOTAL</th>';
                            }
                            $tbl[$page] .= '</tr>';
                            $tbl[$page] .= '</thead>';
                            $tbl[$page] .= '<tbody>';
                        }
                    }   
                } else {
                    if (floatval($this->iteration) > 24) {
                    // if (floatval($this->iteration) > 15) {
                        if (count($listItems) > $counter) {
                            $tbl[$page] .= '</tbody>';
                            $tbl[$page] .= '</table>';
                            PDF::writeHTML($tbl[$page], false, false, false, false, '');
                            PDF::AddPage('L', 'LEGAL');
                            $page++;
                            $this->iteration = 1;
                            $tbl[$page] = '<table id="rfq-print-1-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
                            $tbl[$page] .= '<thead>';
                            $tbl[$page] .= '<tr style="background-color:#ccc;">';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Item No.<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="12%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Article / Items<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="4%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Qty<div style="font-size:5pt">&nbsp;</div></th>';
                            $tbl[$page] .= '<th rowspan="2" colspan="1" align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold"><div style="font-size:5pt">&nbsp;</div>Unit<div style="font-size:5pt">&nbsp;</div></th>';
                            foreach ($suppliers as $supplier) 
                            {
                                $tbl[$page] .= '<th rowspan="1" colspan="3" align="center" width="'.$supplierWidth.'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">'.$supplier->business_name.'</th>';
                            }
                            $tbl[$page] .= '</tr>';
                            $tbl[$page] .= '<tr style="background-color:#ccc;">';
                            foreach ($suppliers as $supplier) 
                            {
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth + 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">BRAND / MODEL</th>';
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">COST</th>';
                                $tbl[$page] .= '<th rowspan="1" colspan="1" align="center" width="'.($colWidth - 1.5).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px; font-weight: bold">TOTAL</th>';
                            }
                            $tbl[$page] .= '</tr>';
                            $tbl[$page] .= '</thead>';
                            $tbl[$page] .= '<tbody>';
                        }
                    }
                }
            }
            $tbl[$page] .= '<tr>';
            $tbl[$page] .= '<td colspan="4" align="center" width="25%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 8px;">&nbsp;</td>';
            foreach ($suppliers as $supplier) 
            {   
                $totalAmt = $this->bacRequestForQuotationRepository->getTotalCanvass($res->identity, $supplier->id);
                $tbl[$page] .= '<td colspan="1" align="right" width="'.($colWidth + 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 10px; font-weight: bold">TOTAL</td>';
                $tbl[$page] .= '<td colspan="2" align="right" width="'.(($colWidth + $colWidth) - 3).'%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 10px; font-weight: bold">'.$this->money_format($totalAmt).'</td>';
            }
            $tbl[$page] .= '</tr>';
            $tbl[$page] .= '</tbody>';
            $tbl[$page] .= '</table>';
            PDF::writeHTML($tbl[$page], false, false, false, false, '');
        }
        $this->abstract_footer($abstract_res); 
        foreach ($abstract_res as $res1) {
            $string = $res1->committees;
            $values = explode(',', $string);
            if (count($values) >= 2) {
                $a = $values[0];
                $b = $values[1];
                $preparedbyId = HrEmployee::where('id', $a)->first();
                 // echo $preparedbyId->user_id; exit;
                $approvedId = HrEmployee::where('id', $b)->first();
                // echo $approvedId->user_id; exit;
                $filename = 'abstract_of_canvass_'.$controlNo.'.pdf';
                $arrSign= $this->_commonmodel->isSignApply('gso_abstract_canvass_bac_member');
                $isSignVeified = isset($arrSign)?$arrSign->status:0;
                $arrCertified= $this->_commonmodel->isSignApply('gso_abstract_canvass_twg_head');
                $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

                $signType = $this->_commonmodel->getSettingData('sign_settings');
                
                $folder =  public_path().'/uploads/digital_certificates/';
                if(!File::exists($folder)) { 
                    File::makeDirectory($folder, 0755, true, true);
                }
                if($signType==2){
                    PDF::Output($folder.$filename,'F');
                    @chmod($folder.$filename, 0777);
                }
                $arrData['filename'] = $filename;
                $arrData['isMultipleSign'] = 1;
                $arrData['isDisplayPdf'] = 0;
                $arrData['isSavePdf'] = 0;
                
                $varifiedSignature = $this->_commonmodel->getuserSignature($preparedbyId->user_id);
                $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

                if($isSignVeified==1 && $signType==2){
                    if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                        $arrData['isSavePdf'] = 1;
                        $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                        $arrData['signaturePath'] = $varifiedSignature;
                        if($isSignCertified==0 && $signType==2){
                            $arrData['isDisplayPdf'] = 1;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                        }else{
                            $this->_commonmodel->applyDigitalSignature($arrData);
                        }
                    }
                }

                $certifiedSignature = $this->_commonmodel->getuserSignature($approvedId->user_id);
                $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

                if($isSignCertified==1 && $signType==2){
                    if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                        $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                        $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                        $arrData['isDisplayPdf'] = 1;
                        $arrData['signaturePath'] = $certifiedSignature;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                }

                if($isSignCertified==1 && $signType==1){
                    // Apply E-sign Here
                    if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                        PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
                    }
                }
                if($isSignVeified==1 && $signType==1){
                    // Apply E-sign Here
                    if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                        PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                    }
                }
                if($signType==2){
                    if(File::exists($folder.$filename)) { 
                        File::delete($folder.$filename);
                    }
                }
                PDF::Output($filename,"I");
            } else {
                echo 'Invalid string format: ' . $string . '<br>';
            }
        }
    }

    public function print_rfq(Request $request, $controlNo)
    {
        $res = $this->bacRequestForQuotationRepository->find_rfq_via_column('control_no', $controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        $agencies = $this->bacRequestForQuotationRepository->get_agencies($res->identity);

        PDF::SetTitle('Request for Quotation ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        $suppliers = $this->bacRequestForQuotationRepository->find_rfq_suppliers_via_column('bac_rfqs.control_no', $controlNo, $request->get('supplier')); 
        $quotationNo = $this->bacRequestForQuotationRepository->getQuotationNo($res->identity);
        foreach ($suppliers as $supplier) {
            PDF::AddPage('P', 'LEGAL');
            PDF::SetFont('Helvetica', '', 10);
            PDF::MultiCell(195.85, 5, 'Republic of the Philippines', 0, 'C', 0, 0, '', '', true);
            PDF::ln();
            PDF::MultiCell(195.85, 5, 'Province of Nueva Ecija', 0, 'C', 0, 0, '', '', true);
            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(195.85, 5, 'CITY GOVERNMENT OF PALAYAN', 0, 'C', 0, 0, '', '', true);
            PDF::ln();
            PDF::ln();
            PDF::MultiCell(195.85, 5, 'Project Reference', 0, 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(35.85, 5, 'Number:', 0, 'L', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(160, 5, $res->control_no, 'B', 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(35.85, 5, 'Name of the Project:', 0, 'L', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(160, 5, $res->project_name, 'B', 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(35.85, 5, 'Requesting Agency:', 0, 'L', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(160, 5, $agencies, 'B', 'L', 0, 0, '', '', true);

            PDF::ln();
            PDF::ln();
            PDF::SetFont('Helvetica', 'B', 10);
            PDF::MultiCell(195.85, 5, 'REQUEST FOR QUOTATION', 0, 'C', 0, 0, '', '', true);
            PDF::ln();
            PDF::ln();
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(35.85, 5, 'Company Name:', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(92, 5, $supplier->business_name, 'B', 'L', 0, 0, '', '', true);
            PDF::MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(23, 5, 'Date:', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(39, 5, ($supplier->canvass_date ? date('d-M-Y', strtotime($supplier->canvass_date)) : ''), 'B', 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::MultiCell(35.85, 5, 'Address:', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(92, 5, trim($supplier->address), 'B', 'L', 0, 0, '', '', true);
            PDF::MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(23, 5, 'Quotation No:', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true);
            PDF::MultiCell(39, 5, $quotationNo, 'B', 'L', 0, 0, '', '', true);
            PDF::ln(10); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(195.85, 5, '          Please quote your lowest price on the item/s listed below, subject to the General Conditions on the last page, stating the shortest time of delivery and submit your quotation duly signed by your representative not later than Jun 23, 2023 in the return envelope attached herewith. The opening of quotation will be on Jun 06, 2023.', 0, 'L', 0, 0, '', '', true);
            PDF::ln();
            PDF::ln(7.5);
            PDF::SetFont('Helvetica', '', 10);
            PDF::MultiCell(135.85, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::SetFont('Helvetica', 'B', 10);
            PDF::MultiCell(60, 5, 'BAC SECRETARIAT', 'T', 'C', 0, 0, '', '', true);
            PDF::ln();
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, 'NOTE:', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(180.85, 5, '1. THE APPROVED BUDGET FOR THE CONTRACT IS <b>'.$res->total_budget.'</b>', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(81.85, 5, '2. ALL ENTRIES MUST BE LEGIBLY WRITTEN', 0, 'L', 0, 0, '', '', true);
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(180.85, 5, '3. DELIVERY PERIOD WITHIN <b>'.strtoupper($res->delivery_period).'</b>', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::setCellHeightRatio(1.25);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
            PDF::MultiCell(180.85, 5, '4. WARRANTY SHALL BE A PERIOD OF <b>'.($res->exp ? strtoupper($res->exp->name) : '').'</b> FOR EXPENDABLE SUPPLIES & <b>'.($res->non_exp ? strtoupper($res->non_exp->name) : '').'</b> FOR NON-EXPENDABLE SUPPLIES FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY.', 0, 'L', 0, 0, '', '', true, 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=true, $autopadding=true, $maxh=5, $valign='M');
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(75.85, 5, '5. PRICE VALIDITY SHALL BE FOR A PERIOD OF', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
            PDF::SetFont('Helvetica', 'B', 9);
            PDF::MultiCell(105, 5, ($res->price_validity ? strtoupper($res->price_validity->name) : '').'.', 0, 'L', 0, 0, '', '', true, $strech=0, $ishtml=false, $autopadding=true, $maxh=5, $valign='M');
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(190.85, 5, '6. A.G. COMBE TIRE SUPPLY SHALL BE ATTACHED UPON SUBMISSIONS OF THE QUOTATION', 0, 'L', 0, 0, '', '', true);
            PDF::ln(); 
            PDF::SetFont('Helvetica', '', 9);
            PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(190.85, 5, '7. BIDDERS SHALL SUBMIT ORIGINAL BROCHURES SHOWING SPECIFICATIONS OF THE PRODUCT BEING OFFERED.', 0, 'L', 0, 0, '', '', true);
            PDF::ln(); 
           
            $tbl = '<table id="rfq-print-1-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
            $tbl .= '<thead>';
            $tbl .= '<tr style="background-color:#ccc;">';
            $tbl .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold">Item No.</th>';
            $tbl .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Qty</th>';
            $tbl .= '<th align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit</th>';
            $tbl .= '<th align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Item Description</th>';
            $tbl .= '<th align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Brand & Model</th>';
            $tbl .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit Price</th>';
            $tbl .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Total Amount</th>';
            $tbl .= '</tr>';
            $tbl .= '</thead>';

            $tbl2 = '<table id="rfq-print-2-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
            $tbl2 .= '<thead>';
            $tbl2 .= '<tr style="background-color:#ccc;">';
            $tbl2 .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold">Item No.</th>';
            $tbl2 .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Qty</th>';
            $tbl2 .= '<th align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit</th>';
            $tbl2 .= '<th align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Item Description</th>';
            $tbl2 .= '<th align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Brand & Model</th>';
            $tbl2 .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit Price</th>';
            $tbl2 .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Total Amount</th>';
            $tbl2 .= '</tr>';
            $tbl2 .= '</thead>';

            $y = PDF::GetY();
            $listItems = $this->bacRequestForQuotationRepository->find_rfq_lines_via_column('bac_rfqs.control_no', $controlNo);
            $this->iteration = 1; $counter = 1; $rows = 18; $totalCost = 0; $page = 0;
            if(!empty($listItems)) {
                $totalCanvass = $this->bacRequestForQuotationRepository->getTotalCanvass($res->identity,$supplier->id);
                foreach ($listItems as $listItem) 
                {                    
                    if (strlen($listItem->pr_remarks) > 0) { 
                        $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->pr_remarks . ')', 25, "\n");
                    } else if (strlen($listItem->itemRemarks) > 0) {
                        $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->itemRemarks . ')', 25, "\n");
                    } else { 
                        $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name, 25, "\n"); 
                    } 
                    $canvass = $this->bacRequestForQuotationRepository->find_canvass($res->identity, $supplier->id, $listItem->item->id);
                    $canvassLine = (object) [ 
                        'brand' => ($canvass->count() > 0) ? ($canvass->first()->description) ? $canvass->first()->description : '' : '',
                        'unit_cost' => ($canvass->count() > 0) ? ($canvass->first()->unit_cost) ? $canvass->first()->unit_cost : '' : '',
                        'total_cost' => ($canvass->count() > 0) ? ($canvass->first()->total_cost) ? $canvass->first()->total_cost : '' : '',
                        'remarks' => ($canvass->count() > 0) ? ($canvass->first()->remarks) ? $canvass->first()->remarks : '' : '',
                    ];

                    if (floatval($page) > 0) {
                        PDF::SetFont('Helvetica', '', 9);
                        $tbl2 .= '<tr>';
                        $tbl2 .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$counter.'</td>';
                        $tbl2 .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->itemQuantity.'</td>';
                        $tbl2 .= '<td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->uom->code.'</td>';
                        $tbl2 .= '<td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$this->parseDesc($description).''.wordwrap($description, 50,'\n').'</td>';
                        $tbl2 .= '<td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$canvassLine->brand.'</td>';
                        $tbl2 .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->unit_cost ? $this->money_format($canvassLine->unit_cost) : '').'</td>';
                        $tbl2 .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->total_cost ? $this->money_format($canvassLine->total_cost) : '').'</td>';
                        $tbl2 .= '</tr>';
                    } else {
                        $tbl .= '<tr>';
                        $tbl .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$counter.'</td>';
                        $tbl .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->itemQuantity.'</td>';
                        $tbl .= '<td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->uom->code.'</td>';
                        $tbl .= '<td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$this->parseDesc($description).''.wordwrap($description, 50,'\n').'</td>';
                        $tbl .= '<td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$canvassLine->brand.'</td>';
                        $tbl .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->unit_cost ? $this->money_format($canvassLine->unit_cost) : '').'</td>';
                        $tbl .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->total_cost ? $this->money_format($canvassLine->total_cost) : '').'</td>';
                        $tbl .= '</tr>';
                    }                    
                    if (floatval($canvassLine->total_cost) > 0) {
                        $totalCost += floatval($canvassLine->total_cost);
                    }

                    if (floatval($this->iteration) > 18 && $page == 0) {
                        $tbl .= '<tr>';
                        $tbl .= '<td colspan="6" align="left" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold; padding-left: 10px"><div style="font-size:2pt">&nbsp;</div>TOTAL AMOUNT IN WORDS: '.((floatval($totalCanvass) > 0) ? strtoupper($this->bacRequestForQuotationRepository->numberTowords($totalCanvass)) : '').'<div style="font-size:2pt">&nbsp;</div></td>';
                        $tbl .= '<td align="right" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:2pt">&nbsp;</div>'. $this->money_format($totalCanvass) .'<div style="font-size:2pt">&nbsp;</div></td>';
                        $tbl .= '</tr>';
                        $tbl .= '</tbody>'; 
                        $tbl .= '</table>';       
                        PDF::writeHTML($tbl, false, false, false, false, '');
                        PDF::ln();
                        $y = PDF::GetY();  
                        $this->rfq_footer($res->identity, $supplier->id);  
                        PDF::AddPage('P', 'LEGAL');              
                        $page++;         
                    }

                    $counter++;
                }   
            }
            if (!($page > 0)) {
                while ($this->iteration < $rows) {
                    $tbl .= 
                    '<tr>
                        <td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                        <td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    </tr>';
                    $this->iteration++;
                }
                $tbl .= '<tr>';
                $tbl .= '<td colspan="6" align="left" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:2pt">&nbsp;</div>TOTAL AMOUNT IN WORDS: '.((floatval($totalCanvass) > 0) ? strtoupper($this->bacRequestForQuotationRepository->numberTowords($totalCanvass)) : '').'<div style="font-size:2pt">&nbsp;</div></td>';
                $tbl .= '<td align="right" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:2pt">&nbsp;</div>'. (($totalCanvass > 0) ?  $this->money_format($totalCanvass) : '').'<div style="font-size:2pt">&nbsp;</div></td>';
                $tbl .= '</tr>';
                $tbl .= '</tbody>'; 
                $tbl .= '</table>';    
                PDF::writeHTML($tbl, false, false, false, false, '');
                PDF::ln();
                $y = PDF::GetY();    
                $this->rfq_footer($res->identity, $supplier->id);
            } else {
                $tbl2 .= '</table>';    
                PDF::writeHTML($tbl2, false, false, false, false, '');
            }
        }

        PDF::Output('request_for_quotation_'.$controlNo.'.pdf');
    }

    public function preview_rfq(Request $request, $controlNo)
    {
        $res = $this->bacRequestForQuotationRepository->find_rfq_via_column('control_no', $controlNo);
        if (!($res->count() > 0)) {
            return abort(404);
        }
        $res = $res->first();
        $agencies = $this->bacRequestForQuotationRepository->get_agencies($res->identity);

        PDF::SetTitle('Request for Quotation ('.$controlNo.')');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        $suppliers = $this->bacRequestForQuotationRepository->find_rfq_suppliers_via_column('bac_rfqs.control_no', $controlNo, $request->get('supplier')); 
        $quotationNo = $this->bacRequestForQuotationRepository->getQuotationNo($res->identity);
        
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(195.85, 5, 'Republic of the Philippines', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::MultiCell(195.85, 5, 'Province of Nueva Ecija', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(195.85, 5, 'CITY GOVERNMENT OF PALAYAN', 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::ln();
        PDF::MultiCell(195.85, 5, 'Project Reference', 0, 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(35.85, 5, 'Number:', 0, 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(160, 5, $res->control_no, 'B', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(35.85, 5, 'Name of the Project:', 0, 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(160, 5, $res->project_name, 'B', 'L', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 9);
        PDF::MultiCell(35.85, 5, 'Requesting Agency:', 0, 'L', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(160, 5, $agencies, 'B', 'L', 0, 0, '', '', true);
        PDF::ln(15); 
        
        $tbl = '<table id="rfq-print-1-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
        $tbl .= '<thead>';
        $tbl .= '<tr style="background-color:#ccc;">';
        $tbl .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold">Item No.</th>';
        $tbl .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Qty</th>';
        $tbl .= '<th align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit</th>';
        $tbl .= '<th align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Item Description</th>';
        $tbl .= '<th align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Brand & Model</th>';
        $tbl .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit Price</th>';
        $tbl .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Total Amount</th>';
        $tbl .= '</tr>';
        $tbl .= '</thead>';

        $tbl2 = '<table id="rfq-print-2-table" width="100%" cellspacing="0" cellpadding="3" border="1">';
        $tbl2 .= '<thead>';
        $tbl2 .= '<tr style="background-color:#ccc;">';
        $tbl2 .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold">Item No.</th>';
        $tbl2 .= '<th align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Qty</th>';
        $tbl2 .= '<th align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit</th>';
        $tbl2 .= '<th align="center" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Item Description</th>';
        $tbl2 .= '<th align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Brand & Model</th>';
        $tbl2 .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Unit Price</th>';
        $tbl2 .= '<th align="center" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px; font-weight: bold"><div style="font-size:3pt">&nbsp;</div>Total Amount</th>';
        $tbl2 .= '</tr>';
        $tbl2 .= '</thead>';

        $y = PDF::GetY();
        $listItems = $this->bacRequestForQuotationRepository->find_rfq_lines_via_column('bac_rfqs.control_no', $controlNo);
        $this->iteration = 1; $counter = 1; $rows = 18; $totalCost = 0; $page = 0;
        if(!empty($listItems)) {
            foreach ($listItems as $listItem) 
            {                    
                if (strlen($listItem->pr_remarks) > 0) { 
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->pr_remarks . ')', 25, "\n");
                } else if (strlen($listItem->itemRemarks) > 0) {
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name . ' (' . $listItem->itemRemarks . ')', 25, "\n");
                } else { 
                    $description = wordwrap($listItem->item->code .' - ' . $listItem->item->name, 25, "\n"); 
                } 
                
                if (floatval($page) > 0) {
                    PDF::SetFont('Helvetica', '', 9);
                    $tbl2 .= '<tr>';
                    $tbl2 .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$counter.'</td>';
                    $tbl2 .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->itemQuantity.'</td>';
                    $tbl2 .= '<td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->uom->code.'</td>';
                    $tbl2 .= '<td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$this->parseDesc($description).''.wordwrap($description, 50,'\n').'</td>';
                    $tbl2 .= '<td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$canvassLine->brand.'</td>';
                    $tbl2 .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->unit_cost ? $this->money_format($canvassLine->unit_cost) : '').'</td>';
                    $tbl2 .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.($canvassLine->total_cost ? $this->money_format($canvassLine->total_cost) : '').'</td>';
                    $tbl2 .= '</tr>';
                } else {
                    $tbl .= '<tr>';
                    $tbl .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$counter.'</td>';
                    $tbl .= '<td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->itemQuantity.'</td>';
                    $tbl .= '<td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$listItem->uom->code.'</td>';
                    $tbl .= '<td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;">'.$this->parseDesc($description).''.wordwrap($description, 50,'\n').'</td>';
                    $tbl .= '<td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>';
                    $tbl .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>';
                    $tbl .= '<td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>';
                    $tbl .= '</tr>';
                }     

                if (floatval($this->iteration) > 18 && $page == 0) {
                    $tbl .= '</tbody>'; 
                    $tbl .= '</table>';       
                    PDF::writeHTML($tbl, false, false, false, false, '');
                    PDF::ln();
                    $y = PDF::GetY();  
                    PDF::AddPage('P', 'LEGAL');              
                    $page++;         
                }

                $counter++;
            }   
        }
        if (!($page > 0)) {
            while ($this->iteration < $rows) {
                $tbl .= 
                '<tr>
                    <td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="center" width="5%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="center" width="10%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="left" width="30%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="center" width="20%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                    <td align="right" width="15%" style="border-top: 0.7px solid black; border-right: 0.7px solid black; border-left: 0.7px solid black; font-size: 9px;"></td>
                </tr>';
                $this->iteration++;
            }
            $tbl .= '</tbody>'; 
            $tbl .= '</table>';    
            PDF::writeHTML($tbl, false, false, false, false, '');
            PDF::ln();
            $y = PDF::GetY();    
        } else {
            $tbl2 .= '</table>';    
            PDF::writeHTML($tbl2, false, false, false, false, '');
        }

        PDF::Output('request_for_quotation_'.$controlNo.'.pdf');
    }

    public function rfq_footer($rfqID, $supplierID)
    {
        $canvassDetail = $this->bacRequestForQuotationRepository->find_supplier($rfqID, $supplierID)->first();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(162.85, 5, 'Delivery Period:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(3, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(30, 5, $canvassDetail->delivery_period, 'B', 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(162.85, 5, 'Warranty:', 0, 'R', 0, 0, '', '', true);
        PDF::MultiCell(3, 5, '', 0, 'R', 0, 0, '', '', true);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(30, 5, '', 'B', 'C', 0, 0, '', '', true);
        PDF::ln(7.5);
        PDF::MultiCell(195.85, 5, 'After having carefully read and accepted your General Conditions, I/We quote you on the item at prices noted above.', 0, 'L', 0, 0, '', '', true);
        PDF::ln(7.5);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(135.85, 5, '', 0, 'L', 0, 0, '', '', true);           
        PDF::MultiCell(60.85, 5, ucwords($canvassDetail->contact_person), 0, 'C', 0, 0, '', '', true);
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(10, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(125.85, 5, 'Canvassed By:', 0, 'L', 0, 0, '', '', true);            
        PDF::MultiCell(60.85, 5, 'Printed Name/Signature', 'T', 'C', 0, 0, '', '', true);
        PDF::ln(6.5);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, ucwords($canvassDetail->canvass_by), 0, 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, $canvassDetail->contact_number, 0, 'C', 0, 0, '', '', true);        
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, 'Printed Name/Signature', 'T', 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, 'Contact Number', 'T', 'C', 0, 0, '', '', true);      
        
        PDF::ln(6.5);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, '', 0, 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, $canvassDetail->email_address, 0, 'C', 0, 0, '', '', true);        
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, '', 0, 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, 'Email Address', 'T', 'C', 0, 0, '', '', true);    

        PDF::ln(6.5);
        PDF::SetFont('Helvetica', '', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, '', 0, 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, $canvassDetail->canvass_date ? date('d-M-Y', strtotime($canvassDetail->canvass_date)) : '', 0, 'C', 0, 0, '', '', true);        
        PDF::ln();
        PDF::SetFont('Helvetica', 'B', 10);
        PDF::MultiCell(50, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, '', 0, 'C', 0, 0, '', '', true);    
        PDF::MultiCell(25, 5, '', 0, 'L', 0, 0, '', '', true);   
        PDF::MultiCell(60.85, 5, 'Date', 'T', 'C', 0, 0, '', '', true);
    }

    public function parseDesc($description)
    {   
        $n = floatval(strlen($description)) / 50;
        $whole = floor($n);      
        $fraction = $n - $whole; 
        $count = ($fraction != 0) ? floatval(floatval($whole) + floatval(1)) : floatval($whole); 
        $this->iteration = $this->iteration + $count;
    }

    public function money_format($money)
    {
        return number_format(floor(($money*100))/100, 2);
    }
}
