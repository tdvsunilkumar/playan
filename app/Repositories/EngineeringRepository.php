<?php

namespace App\Repositories;

use App\Interfaces\EngineeringInterface;
use App\Models\Engneering\EngJobRequest;
use App\Models\CommonModelmaster;
use App\Models\Engneering\EngFixtureType;
use DB;
use File;
use PDF;
use App\Models\Barangay;
use Carbon\Carbon;

class EngineeringRepository implements EngineeringInterface 
{
    public function __construct(){
		$this->_engjobrequest= new EngJobRequest(); 
        $this->_commonmodel = new CommonModelmaster();
        $border = 0;
        $this->font = 8;
    }

    public function signPrint($id)
    {
        
        $data = $this->_engjobrequest->find($id);
        PDF::SetTitle('Sign Permit: '.$data->application_no.'');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', $this->font);
        // Header
        $border = 0;
        $missing = '';
        PDF::MultiCell(0, 0, 'Republic of the Philippines', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'City/Municipality of Palayan', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Province of Nueva Ecija', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OFFICE OF THE BUILDING OFFICIAL</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h1>SIGN PERMIT</h1>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('Helvetica', '', $this->font);
        PDF::MultiCell(70, 0, 'APPLICATION NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(72, 0, 'SP NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'BUILDING PERMIT NO.', $border, 'L', 0, 1, '', '', true, 0, true);

        foreach (str_split($data->application_no) as $value) {
            PDF::MultiCell(4, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
        }
        PDF::MultiCell(34, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        // foreach (str_split($data->ebpa_permit_no) as $value) {
        for ($i=0; $i < 8; $i++) { 
            PDF::MultiCell(4, 0, '', 1, 'C', 0, 0, '', '', true, 0, true);
        }
        PDF::MultiCell(40, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        if ($data->details->permit) {
            foreach (str_split($data->details->permit->ebpa_permit_no) as $value) {
                PDF::MultiCell(4, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
            }
        } else {
            for ($i=0; $i < 12; $i++) { 
                PDF::MultiCell(4, 0, '', 1, 'C', 0, 0, '', '', true, 0, true);
            }
        }
        PDF::MultiCell(15, 0, '', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::setCellPaddings(2,1,2,1);
        PDF::MultiCell(0, 0, '<b>BOX 1.(TO BE ACCOMPLISHED BY THE OWNER/APPLICANT)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(40, 0, 'OWNER/APPLICANT', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, 'LAST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, 'FIRST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'MIDDLE NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'TIN', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        $lastname = $data->applicant->rpo_custom_last_name;
        $firstname = $data->applicant->rpo_first_name;
        $midname = $data->applicant->rpo_middle_name;
        $house = $data->applicant->rpo_address_house_lot_no;
        $st = $data->applicant->rpo_address_street_name;
        $sub = $data->applicant->rpo_address_subdivision;
        $brgy = $data->applicant->brgy_name;
        $municipality = $data->applicant->municipality;
        $tel = $data->applicant->p_telephone_no;

        PDF::MultiCell(40, 0, '', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, '<b>'.$lastname.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, '<b>'.$firstname.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, '<b>'.substr($midname, 0, 1).'.</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, '<b>'.$data->details->taxaccno.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(60, 0, 'FOR CONSTRUCTION OWNED', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'FORM OF OWNERSHIP', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'USE OR CHARACTER OF OCCUPANCY', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, 'BY AN ENTERPRIZE', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, '<b>'.$data->details->esa_form_of_own.'</b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$data->details->esa_economic_act.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(162, 0, 'ADDRESS', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'LR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35, 0, 'NO.', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'STREET', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'BARANGAY', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'CITY/MUNICIPALITY', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(17, 0, 'ZIPCODE', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TELEPHONE NO.', 'LR', 'L', 0, 1, '', '', true, 0, true);

        // PDF::MultiCell(20, 0, '', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::SetFont('helvetica', 'B', $this->font);
        PDF::Cell(35, 0, $house, 'LB', 0, 'L', 0);
        PDF::Cell(30, 0, $st, 'B', 0, 'L', 0);
        PDF::Cell(45, 0, $brgy, 'B', 0, 'L', 0);
        PDF::Cell(35, 0, $municipality, 'B', 0, 'L', 0);
        PDF::Cell(17, 0, "3132", 'B', 0, 'L', 0);
        PDF::Cell(0, 0, $tel, 'LBR', 1, 'L', 0);
        PDF::SetFont('helvetica', '', $this->font);
        
        PDF::MultiCell(50, 0, 'LOCATION OF CONSTRUCTION', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'LOT NO. <b>34'.$data->details->lotno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'BLK NO. <b>'.$data->details->blkno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'TCT NO. <b>'.$data->details->totno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TAX DEC. NO. <b>'.$data->details->taxdecno.'<b>', 'TR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, 'STREET <b>'.$data->details->Street.'<b>', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'BARANGAY <b>'.$brgy.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'CITY/MUNICIPALITY <b>PALAYAN</b>', 'BR', 'L', 0, 1, '', '', true, 0, true);

        $scope = $this->_engjobrequest->GetBuildingScopeselsign();
        $scope = array_chunk($scope,3,TRUE);
        $scopeList = 'SCOPE OF WORK<BR>
        <table>';
            foreach ($scope as $key => $value) {
            $scopeList .= '<tr>';
                foreach ($value as $id => $val) {
                    $check = $data->details->bldgScopeCheck($val->id);
                    if ($val->ebs_description === 'Others') {
                        $scopeList .= '<td>'.$check.'  '.$val->ebs_description.' (Specify)</td>';//add remarks
                    } else {
                        $scopeList .= '<td>'.$check.'  '.$val->ebs_description.'</td>';
                    }
                }
            $scopeList .= '</tr>';
            }
        $scopeList .= '</table>';
        PDF::MultiCell(0, 0, $scopeList, 1, 'L', 0, 1, '', '', true, 0, true);
        
        $display = $this->_engjobrequest->GetSignDisplayTypes();
        array_unshift($display,(object)['id'=>0,'esdt_description'=>'Type of Display']);//mustcremove soon
        $display = array_chunk($display,4,TRUE);
        $install = $this->_engjobrequest->GetSignInstallationTypes();
        array_unshift($install,(object)['id'=>0,'esit_description'=>'Type of Installation']);//mustcremove soon
        $install = array_chunk($install,4,TRUE);
        $html = 'USE OR CHARACTER OF OCCUPANCY
        <ol type="A" style="margin:0;padding:0">
            <li>
                <table>';
                    foreach ($display as $key => $value) {
                    $html .= '<tr>';
                        foreach ($value as $id => $val) {
                            if ($val->id === 0) {
                                $html .= '<td><b>'.$val->esdt_description.'</b></td>';
                            } else {
                                $check = $data->details->displayTypeCheck($val->id);
                                if ($val->esdt_description === 'Others') {
                                    $html .= '<td>'.$check.'  '.$val->esdt_description.' (Specify)</td>';//add remarks
                                } else {
                                    $html .= '<td>'.$check.'  '.$val->esdt_description.'</td>';
                                }
                            }
                        }
                    $html .= '</tr>';
                    }
                $html .= '</table>
            </li>
            <li>
                <table>';
                    foreach ($install as $key => $value) {
                    $html .= '<tr>';
                        foreach ($value as $id => $val) {
                            if ($val->id === 0) {
                                $html .= '<td><b>'.$val->esit_description.'</b></td>';
                            } else {
                                $check = $data->details->installationTypeCheck($val->id);
                                if ($val->esit_description === 'Others') {
                                    $html .= '<td>'.$check.'  '.$val->esit_description.' (Specify)</td>';//add remarks
                                } else {
                                    $html .= '<td>'.$check.'  '.$val->esit_description.'</td>';
                                }
                            }
                        }
                    $html .= '</tr>';
                    }
                $html .= '</table>
            </li>
            <li>
                <table>
                    <tr>
                        <td><b>Display Size/Face:</b></td>
                        <td>L(m)* <b>'.$data->details->length.'</b></td>
                        <td>W(m)* <b>'.$data->details->width.'</b></td>
                        <td>All(m&#178;)* <b>'.$data->details->alllengthwidth.'</b></td>
                    </tr>
                </table>
            </li>';
        PDF::MultiCell(0, 0, $html, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 2.(TO BE CHECKED, RECEIVED AND RECORDED)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        
        $html = '<b>ACCOMPAYING DOCUMENTS: [FIVE (5) SETS EACH SIGNED AND SEALED BY RESPONSIBLE DESIGN PROFESSIONAL]</b><br>
        <table>
            <tr>
                <td>'.config('constants.checkbox.unchecked').' CERTIFIED XEROX COPY OF TCT</td>
                <td>'.config('constants.checkbox.unchecked').' XEROX COPY OF LOT PLAN AND SITE DEVELOPMENT PLAN</td>
            </tr>
            <tr>
                <td>'.config('constants.checkbox.unchecked').' IF NOT OWNED BY THE APPLICANT IN ADDITION TO THE CERTIFIED XEROX COPY OF TCT. XEROX COPY OF CONTRACT OF LEASE</td>
                <td>'.config('constants.checkbox.unchecked').' PLAN OF SIGN STRUCTURES, STRUCTURAL DESIGN & COMPUTATIONS</td>
            </tr>
            <tr>
                <td>'.config('constants.checkbox.unchecked').' XEROX COPY OF TAX DECLARATION AND LATEST REALTY TAX RECEIPT</td>
                <td>'.config('constants.checkbox.unchecked').' SPECIFICATIONS AND COST ESTIMATES</td>
            </tr>
        </table>
        ';
        PDF::SetFont('Helvetica', '', $this->font - 1);
        PDF::MultiCell(0, 0, $html, 1, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(95, 0, '<b>BOX 3.</b>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BOX 4.</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, '<b>DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, '<b>FULL-TIME INSPECTOR AND SUPERVISOR OR CONSTRUCTION WORKS</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 10, '', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 10, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 10, '', 'LR', 'L', 0, 1, '', '', true, 0, true);
        
        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        $incharge = isset($data->details->incharge) ? $data->details->incharge->fullname : '';
        PDF::MultiCell(10, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $consultant, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, 'Date', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(10, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $incharge, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, 'Date', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>ARCHITECT AND/OR CIVIL ENGINEER</b><br>(Signed and Sealed Over Printed Name)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>ARCHITECT OR CIVIL ENGINEER</b><br>(Signed and Sealed Over Printed Name)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, 'Address <b>'.$data->details->signaddress.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, 'Address <b>'.$data->details->inchargenaddress.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'PRC No <b>'.$data->details->signprcno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'Validity <b>'.$data->details->signvalidity.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'PRC No <b>'.$data->details->inchargeprcregno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Validity <b>'.$data->details->inchargevalidity.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'PTR No <b>'.$data->details->signptrno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'Date Issued <b>'.$data->details->signdateissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'PTR No <b>'.$data->details->inchargeptrno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Issued <b>'.$data->details->inchargedateissued.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'Issued at <b>'.$data->details->signplaceissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'TIN <b>'.$data->details->signtin.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'Issued at <b>'.$data->details->inchargeplaceissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TIN <b>'.$data->details->inchargetin.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 4.</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 10, '', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, $data->applicant->fullname, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Date_______________', 0,  'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>Applicant</b><br>(Signed Over Printed Name)', 'LRB', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Address <b>'.$data->applicant->current_address.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'C.T.C. No. <b>'.$data->details->taxDetails('client')->or_no.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'Date Issued <b>'.$data->details->taxDetails('client')->created_at.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'Place Issued <b>'.$data->details->taxDetails('client')->ctc_place_of_issuance.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TIN <b>'.$data->applicant->p_tin_no.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::AddPage();

        PDF::MultiCell(95, 0, '<b>BOX 6.</b>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BOX 7.</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 10, 'BUILDING OWNER', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 10, '', 0, 'LT', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 10, 'WITH MY CONSENT: LOT OWNER', 'LTR', 'L', 0, 1, '', '', true, 0, true);
        
        $applicantConsultant = isset($data->details->applicantConsultant) ? $data->details->applicantConsultant->fullname : '';
        PDF::MultiCell(20, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $applicantConsultant, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(20, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        $owner = isset($data->details->owner) ? $data->details->owner->fullname : '';
        PDF::MultiCell(50, 0, $owner, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>(Signature Over Printed Name)</b><br>Date ______________________', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>(Signature Over Printed Name)</b><br>Date ______________________', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 8, 'Address <b>'.$data->details->applicantaddress.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 8, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 8, 'Address <b>'.$data->details->owneraddress.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(30, 0, 'C.T.C. No. <br><b>'.$data->details->applicant_comtaxcert.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Date Issued <br><b>'.$data->details->applicant_date_issued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Place Issued <br><b>'.$data->details->applicant_place_issued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(30, 0, 'C.T.C. No. <br><b>'.$data->details->owner_comtaxcert.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Date Issued <br><b>'.$data->details->owner_date_issued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Place Issued <br><b>'.$data->details->ownerplaceissued.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('Helvetica', '', $this->font);
        PDF::MultiCell(0, 0, '<b>BOX 8 (TO BE ACCOMPLISHED BY THE PROCESSING AND EVALUATION DIVISION)</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(46.25, 0, 'FEE PAID <b></b>', 'TLB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(46.25, 0, 'DATE PAID <b></b>', 'TB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(46.25, 0, 'OFFICIAL RECEIPT NO <b></b>', 'TB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'DATE ISSUED <b></b>', 'TRB','L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 9. (TO BE ACCOMPLISHED BY THE BUILDING OFFICIAL)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        $html = '
        <b>ACTION TAKEN:</b>
        <b><p>Permit is hereby issued/granted to <u>'.$data->applicant->fullname.'</u> with postal address at <u>'.$data->applicant->current_address.'</u> to install/erect/construct/attach/paint <u>'.$missing.'</u>_____________________ with the word <u>'.$missing.'</u>___________________________ at the premises of <u>'.$missing.'</u>______________________________________ as per submitted plans pursuant to pertinent provisions of the “National Building Code” (PD 1096) and its implementing Rules and Regulations and to the following conditions:</p></b>
        <ol>
            <li>That under Article 1723 of the Civil Code of the Philippines, the engineer or architect who drew up the plans and specifications for a building/structure is responsible for damages if within fifteen (15) years from the completion of the structure, the same should collapse due to defect in the plans or specifications or defects in the ground. The engineer or architect who supervises the construction shall be solidarily liable with the contractor should the edifice collapse due to defect in the construction or the use of inferior materials.</li>
            <li> That the proposed sign shall be in conformity with Rule XX of the “National Building Code” (PD 1096).
                <ol type="a">
                    <li>That prior to commencement of the proposed projects and construction/erection, an actual relocation survey shall be conducted by the responsible licensed geodetic engineer</li>
                    <li>That before commencing the excavation the person making or causing the excavations to be made shall notify in writing the owner of the adjoining building not less than ten (10) days before such excavation is to be made and show how the adjoining building should be protected.</li>
                    <li>That the owner of the sign structure shall engage the services of a responsible licensed architect or civil engineer to undertake the full-time inspection and supervision of the construction work.</li>
                    <li>That there shall be kept at the jobsite a logbook of daily construction activities wherein the actual daily progress of construction including test conducted, weather condition and other pertinent data are to be recorded, same shall be made available for scrutiny and comments by the OBD representative during the conduct of his/her inspection pursuant to Section 207 of the National Building Code.</li>
                    <li>That upon completion of the construction/sign structure, the responsible licensed supervising architect or civil engineer shall submit the logbook duly signed and sealed to the Building Official including as-built plans and other documents.</li>
                    <li>That he shall also prepare and submit a Certificate of Completion of the project stating that the construction/sign structure conforms to the provision of the “National Building Code” (PD 1096) as well as with plans and specifications.</li>
                </ol>
            </li>
            <li>That no sign shall be used and no change in the existing character of occupancy classification of a building/structure or portion thereof shall be made until the Building Official has issued a Certificate of Use or Occupancy thereof as provided in the “National Building Code” (PD 1096).</li>
            <li>That this permit shall not serve as an exemption from securing permit/written clearances from various government authorities exercising regulatory function affecting building and other related structures.</li>
            <li>Signs shall adhere to the Code of Ethics for Advertising and Promotions and to the rules and regulations of the appropriate agency in-charge of the conduct of the business.</li>
            <li>Signs shall promote and uphold the public goods especially in historical monuments and shrines, natural scenic areas, parks, parkways and their immediate approaches. Immediate approaches shall mean a distance not exceeding fifty (50.00) meters from the periphery of said areas.</li>
            <li>Signs shall display or convey only message or visuals that conform to public decency and good taste.</li>
            <li>Signs shall follow standards of design, construction and maintenance in the interest of the public safety, convenience, good viewing and to promote proper urban design or community architecture.</li>
            <li>Sign structure may be constructed only in areas where zoning regulations permit them and in accordance with the accepted standards of design, construction and maintenance.</li>
            <li>Signs and sign structures shall be constructed in accordance with the provisions of Section 2003 of the “National Building Code” (PD 1096). Plans of sign structure exceeding three (3.00) meters in height from the ground shall be signed and sealed by the responsible designing architect or civil engineer.</li>
            <li>Signs and sign structures built within highly restrictive fire zones shall be of incombustible materials. No combustible materials other than approved plastic shall be used in construction of electrical signs.</li>
            <li>Signs and sign structures equipped with electrical devices shall have an electrical plan conforming with the provisions of the latest edition of the Philippines Electrical Code duly signed and sealed by the responsible Professional Electrical Engineer, if the installation or the machinery is rated less than 500 kVA or less than 600 volts.</li>
            <li>Signs shall be placed in a such manner that no part of its surface will interfere in any way with the free use of a doorway, a fire escape, standpipe or other required means of exit and fire-protective devices.</li>
            <li>Signs, which are written in foreign language, shall have corresponding translation in English or in the local dialect.</li>
            <li>The bottom line of all signboards adjacent to each other shall follow a common base line as determined by the Building Official.</li>
        </ol>
        ';
        $name = isset($data->details->official) ? $data->details->official->fullname : '';
        PDF::MultiCell(0, 0, $html, 'TLR', 'L', 0, 1, '', '', false, 0, true);
        PDF::MultiCell(0, 0, 'PERMIT ISSUED BY:', 'LR', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(65, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(58, 0, $name, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BUILDING OFFICIAL</b><br>(Signature Over Printed Name)<br>Date__________________', 'LBR', 'C', 0, 1, '', '', true, 0, true);

        //PDF::Output('Sign Permit '.$data->application_no.'.pdf','I');
        $filename ='SignPermit'.$data->application_no.'.pdf';
            //$filename =$id.$filename."electronicpermit.pdf";
            //$mpdf->Output($filename, "I");
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            // PDF::Output($filename,'I'); exit;
            $isSignVeified = 1;
            $officialid = $this->_commonmodel->getuseridbyempid($data->details->esa_building_official);
            $signType = $this->_commonmodel->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature ="";
                if(!empty($officialid->user_id)){
                $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                }
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = '214,93,376,50,2';
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
                        PDF::Image($path,80,255,50);
                    }
                }
            }
            PDF::Output($filename,"I");
    }

    public function fencePrint($id)
    {
        $data = $this->_engjobrequest->find($id);
        $border = 0;
        $missing = '';
        PDF::SetTitle('Fencing Permit: '.$data->application_no.'');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', $this->font);

        

        // Header
        PDF::MultiCell(0, 0, 'Republic of the Philippines', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'City/Municipality of Palayan', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Province of Nueva Ecija', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OFFICE OF THE BUILDING OFFICIAL</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h1>FENCING PERMIT</h1>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('Helvetica', '', $this->font);
        PDF::MultiCell(70, 0, 'APPLICATION NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(72, 0, 'FP NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'BUILDING PERMIT NO.', $border, 'L', 0, 1, '', '', true, 0, true);

        // application no in box form
        foreach (str_split($data->application_no) as $value) {
            PDF::MultiCell(4, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
        }
        PDF::MultiCell(34, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        // FP no in box form
        for ($i=0; $i < 8; $i++) { 
            PDF::MultiCell(4, 0, '', 1, 'C', 0, 0, '', '', true, 0, true);
        }
        PDF::MultiCell(40, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        // building permit no in box form
        if ($data->details->permit) {
            foreach (str_split($data->details->permit->ebpa_permit_no) as $value) {
                PDF::MultiCell(4, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
            }
        } else {
            for ($i=0; $i < 12; $i++) { 
                PDF::MultiCell(4, 0, '', 1, 'C', 0, 0, '', '', true, 0, true);
            }
        }
        PDF::MultiCell(15, 0, '', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::setCellPaddings(2,1,2,1);
        PDF::MultiCell(0, 0, '<b>BOX 1.(TO BE ACCOMPLISHED BY THE OWNER/APPLICANT)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(45, 0, 'OWNER/APPLICANT', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, 'LAST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, 'FIRST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'MIDDLE NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TIN', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        $lastname = $data->applicant->rpo_custom_last_name;
        $firstname = $data->applicant->rpo_first_name;
        $midname = $data->applicant->rpo_middle_name;
        $tin = $data->applicant->p_tin_no;
        $house = $data->applicant->rpo_address_house_lot_no;
        $st = $data->applicant->rpo_address_street_name;
        $sub = $data->applicant->rpo_address_subdivision;
        $brgy = $data->applicant->brgy_name;
        $municipality = $data->applicant->municipality;
        $tel = $data->applicant->p_telephone_no;

        PDF::MultiCell(45, 0, '', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.$lastname.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.$firstname.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '<b>'.substr($midname, 0, 1).'.</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$tin.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(60, 0, 'FOR CONSTRUCTION OWNED', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'FORM OF OWNERSHIP', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'USE OR CHARACTER OF OCCUPANCY', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, 'BY AN ENTERPRIZE', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, '<b>'.$data->details->efa_form_of_own.'</b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$data->details->maineconomy.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(162, 0, 'ADDRESS', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'LR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35, 0, 'NO.', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'STREET', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'BARANGAY', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'CITY/MUNICIPALITY', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(17, 0, 'ZIPCODE', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TELEPHONE NO.', 'LR', 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('helvetica', 'B', $this->font);
        PDF::Cell(35, 0, $house, 'LB', 0, 'L', 0);
        PDF::Cell(30, 0, $st, 'B', 0, 'L', 0);
        PDF::Cell(45, 0, $brgy, 'B', 0, 'L', 0);
        PDF::Cell(35, 0, $municipality, 'B', 0, 'L', 0);
        PDF::Cell(17, 0, "3132", 'B', 0, 'L', 0);
        PDF::Cell(0, 0, $tel, 'LBR', 1, 'L', 0);
        PDF::SetFont('helvetica', '', $this->font);

        PDF::MultiCell(50, 0, 'LOCATION OF CONSTRUCTION', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'LOT NO. <b>'.$data->details->lotno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'BLK NO. <b>'.$data->details->blkno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'TCT NO. <b>'.$data->details->totno.'<b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TAX DEC. NO. <b>'.$data->details->taxdecno.'<b>', 'TR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, 'STREET <b>'.$data->details->Street.'<b>', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'BARANGAY <b>'.$brgy.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'CITY/MUNICIPALITY OF <b>PALAYAN</b>', 'BR', 'L', 0, 1, '', '', true, 0, true);

        $scope = $this->_engjobrequest->GetBuildingScopeselfetching();
        $scope = array_chunk($scope,3,TRUE);
        $scopeList = 'SCOPE OF WORK<BR>
        <table>';
            foreach ($scope as $key => $value) {
            $scopeList .= '<tr>';
                foreach ($value as $id => $val) {
                    $check = $data->details->bldgScopeCheck($val->id);
                    if ($val->ebs_description === 'Others') {
                        $scopeList .= '<td>'.$check.'  '.$val->ebs_description.' (Specify)</td>';//add remarks
                    } else {
                        $scopeList .= '<td>'.$check.'  '.$val->ebs_description.'</td>';
                    }
                }
            $scopeList .= '</tr>';
            }
        $scopeList .= '</table>';
        PDF::MultiCell(0, 0, $scopeList, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('Helvetica', '', $this->font - 2);
        PDF::MultiCell(95, 0, '<b>BOX 2.</b>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BOX 3.</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, '<b>DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, '<b>FULL-TIME INSPECTOR AND SUPERVISOR OR CONSTRUCTION WORKS</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 7, '', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 7, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 7, '', 'LR', 'L', 0, 1, '', '', true, 0, true);

        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        $incharge = isset($data->details->incharge) ? $data->details->incharge->fullname : '';
        PDF::MultiCell(10, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $consultant, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, 'Date', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(10, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $incharge, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, 'Date', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>ARCHITECT AND/OR CIVIL ENGINEER</b><br>(Signed and Sealed Over Printed Name)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>ARCHITECT OR CIVIL ENGINEER</b><br>(Signed and Sealed Over Printed Name)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, 'Address <b>'.$data->details->signaddress.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, 'Address <b>'.$data->details->inchargenaddress.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'PRC No. <b>'.$data->details->signprcno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'Validity <b>'.$data->details->signvalidity.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'PRC No. <b>'.$data->details->inchargeprcregno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Validity <b>'.$data->details->inchargevalidity.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'PTR No <b>'.$data->details->signptrno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'Date Issued <b>'.$data->details->signdateissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'PTR No <b>'.$data->details->inchargeptrno.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Issued <b>'.$data->details->inchargedateissued.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(45, 0, 'Issued at <b>'.$data->details->signplaceissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'TIN <b>'.$data->details->signtin.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(45, 0, 'Issued at <b>'.$data->details->inchargeplaceissued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TIN <b>'.$data->details->inchargetin.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 4. (TO BE ACCOMPLISHED BY THE APPLICANT)</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, '<b>APPLICANT</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'LT', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, '<b>WITH MY CONSENT: LOT OWNER</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 8, '', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 8, '', 0, 'LT', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 8, '', 'LR', 'L', 0, 1, '', '', true, 0, true);
        
        $applicantConsultant = isset($data->details->applicantConsultant) ? $data->details->applicantConsultant->fullname : '';
        PDF::MultiCell(20, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $applicantConsultant, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(20, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        $owner = isset($data->details->owner) ? $data->details->owner->fullname : '';
        PDF::MultiCell(50, 0, $owner, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>(Signature Over Printed Name)</b><br>Date ______________________', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(20, 0, '&nbsp;<br>&nbsp;', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '<b>(Signature Over Printed Name)</b><br>Date ______________________', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '&nbsp;<br>&nbsp;', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, 'Address <b>'.$data->details->applicantaddress.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(0, 0, 'Address <b>'.$data->details->owneraddress.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'C.T.C. No. <br><b>'.$data->details->applicant_comtaxcert.'</b>', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Date Issued <br><b>'.$data->details->applicant_date_issued.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Place Issued <br><b>'.$data->details->applicant_place_issued.'</b>', 'BR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);//MID SPACE
        PDF::MultiCell(30, 0, 'C.T.C. No. <br><b>'.$data->details->owner_comtaxcert.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'Date Issued <br><b>'.$data->details->owner_date_issued.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Place Issued <br><b>'.$data->details->ownerplaceissued.'</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 5</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(50, 0, 'REPUBLIC OF THE PHILIPPINES<br>CITY/MUNICIPALITY OF <b>PALAYAN</b>', 'TL', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ') S S<br>)', 'TR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, 'BEFORE ME, at the City/Municipality of ________________ on _________________ personally appeared the following:', 'LR', 'C', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(20, 0, '', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, $data->details->applicantnamenew, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->ctcnonew, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->dateissuednew, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->placeissuednew, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, '', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, 'APPLICANT', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'CTC No.', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Date Issued', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Place Issued', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, '', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, $data->details->liancnedapplicant, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->liancnedctcno, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->liancneddateissued, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, $data->details->liancnedplaceissued, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, '', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, 'LICENSED ARCHITECT OR CIVIL ENGINEER<br>(Full-time Inspector and Supervisor of Fencing Works)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'CTC No.', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Date Issued', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Place Issued', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<br>', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '&nbsp;<br>whose signatures appear herein above, known to me to be the same persons who executed this standard prescribed from and acknowledged to me that the same is their free and voluntary act and deed.', 'LR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, ' &nbsp; &nbsp; &nbsp; WITNESS', 'LR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(20, 0, 'Doc. No. ', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(75, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, 'Page. No. ', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(75, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'NOTARY PUBLIC (Until _______________)', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, 'Book. No. ', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(20, 0, 'Series. No. ', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '', 'LBR', 'L', 0, 1, '', '', true, 0, true);

        PDF::AddPage();

        PDF::MultiCell(0, 0, '<b>BOX 6. (TO BE ACCOMPLISHED BY THE DESIGN PROFESSIONAL)</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(30, 0, 'MEASUREMENTS', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, $data->details->measurelength, 'TB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'LENGTH IN METERS', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, $data->details->measureheight, 'TB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'HEIGHT IN METERS', 'RT', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, 'TYPE OF FENCING', 'LR', 'L', 0, 1, '', '', true, 0, true);
        $type = $this->_engjobrequest->GetTypeofFencing();
        $type = array_chunk($type,2,true);
        $html = '<table>';
            foreach ($type as $key => $value) {
                $html .= '<tr>';
                foreach ($value as $id => $val) {
                    $check = ($val->id === (int)$data->details->typeoffencing)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                    $html .= '<td>  '.$check.'  '.$val->eft_description.'</td>';
                }
                $html .= '</tr>';
            }
                
        $html .= '</table>';
        PDF::MultiCell(0, 0, $html, 'LRB', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 7. (TO BE ACCOMPLISHED BY THE PROCESSING AND EVALUATION DIVISION)</b>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>PROGRESS FLOW</b>', 1, 'C', 0, 1, '', '', true, 0, true);

        $html = '
        <table border="1" style="padding:2pt 10pt">
            <tr>
                <td rowspan="2" colspan="1"></td>
                <td rowspan="1" colspan="2"><b>IN</b></td>
                <td rowspan="1" colspan="2"><b>OUT</b></td>
                <td rowspan="2" colspan="1">&nbsp;<br><b>PROCESSED BY</b></td>
            </tr>
            <tr>
                <td><b>DATE</b></td>
                <td><b>TIME</b></td>
                <td><b>DATE</b></td>
                <td><b>TIME</b></td>
            </tr>
            <tr>
                <td style="text-align:left">LINE AND GRADE (GEODETIC)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:left">CIVIL / STRUCTURAL</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:left">ELECTRICAL</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:left">OTHERS (Specify)</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        ';
        PDF::SetFont('Helvetica', '', $this->font );
        PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell(0, 0, $html, 1, 'C', 0, 1, '', '', false, 0, true);

        PDF::setCellPaddings(2,1,2,1);
        PDF::MultiCell(0, 0, '<b>ASSESSED FEES</b>', 1, 'C', 0, 1, '', '', true, 0, true);
        $html = '
        <table border="1" style="padding:2pt 15pt">
            <tr>
                <td><b></b></td>
                <td><b>Amount Due</b></td>
                <td><b>O.R. Number</b></td>
                <td><b>Date Paid</b></td>
                <td><b>Processed By</b></td>
            </tr>
            <tr>
                <td style="text-align:left">LINE AND GRADE (GEODETIC)</td>
                <td>'.$data->details->efa_linegrade_amount.'</td>
                <td>'.$data->details->efa_linegrade_or_no.'</td>
                <td>'.$data->details->efa_linegrade_date_paid.'</td>
                <td>'.$data->details->preparedBy('linegrade').'</td>
            </tr>
            <tr>
                <td style="text-align:left">FENCING</td>
                <td>'.$data->details->efa_fencing_amount.'</td>
                <td>'.$data->details->efa_fencing_or_no.'</td>
                <td>'.$data->details->efa_fencing_date_paid.'</td>
                <td>'.$data->details->preparedBy('fencing').'</td>
            </tr>
            <tr>
                <td style="text-align:left">ELECTRICAL (If any)</td>
                <td>'.$data->details->efa_electrical_amount.'</td>
                <td>'.$data->details->efa_electrical_or_no.'</td>
                <td>'.$data->details->efa_electrical_date_paid.'</td>
                <td>'.$data->details->preparedBy('electrical').'</td>
            </tr>
            <tr>
                <td style="text-align:left">OTHERS (Specify)</td>
                <td>'.$data->details->efa_others_amount.'</td>
                <td>'.$data->details->efa_others_or_no.'</td>
                <td>'.$data->details->efa_others_date_paid.'</td>
                <td>'.$data->details->preparedBy('others').'</td>
            </tr>
            <tr>
                <td style="text-align:left"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:right"><b>TOTAL:</b></td>
                <td>'.$data->details->efa_total_amount.'</td>
                <td>'.$data->details->efa_total_or_no.'</td>
                <td>'.$data->details->efa_total_date_paid.'</td>
                <td>'.$data->details->preparedBy('total').'</td>
            </tr>
        </table>
        ';
        PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell(0, 0, $html, 1, 'C', 0, 1, '', '', false, 0, true);

        PDF::setCellPaddings(2,1,2,1);
        PDF::SetFont('Helvetica', '', $this->font);
        PDF::MultiCell(0, 0, '<b>BOX 9. (TO BE ACCOMPLISHED BY THE BUILDING OFFICIAL)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        $html = '
        <b>ACTION TAKEN:</b>
        <b><p>PERMIT IS HEREBY ISSUED/GRANTED SUBJECT TO THE FOLLOWING CONDITIONS:</p></b>
        <ol>
            <li>That under Article 1723 of the Civil Code of the Philippines, the engineer or architect who drew up the plans and specifications is liable for damages if within fifteen (15) years from the completion of the structure, it should collapse due to defect in the plans or specifications or defects in the ground. The engineer or architect who supervises the construction shall be solitarily liable with the contractor should the edifice collapse due to defect in the construction or the use of inferior materials.</li>
            <li> That the proposed construction/erection/addition, etc. shall be in conformity with the provisions of the “National Building Code” (P.D. 1096) and its implementing Rules and Regulations.
                <ol type="a">
                    <li>That prior to commencement of the proposed projects and construction an actual relocation survey shall be conducted by responsible licensed Geodetic Engineer.</li>
                    <li>That before commencing the excavation the person making or causing the excavation to be made shall verify in writing the owner of adjoining building not less than ten (10) days before such excavation is to be made and show how the adjoining building should be protected.</li>
                    <li>That the owner of the fence shall engage the services of responsible licensed Architect or Civil Engineer to undertake the full time inspection and supervision of the construction work.</li>
                    <li>That there shall be kept at the jobsite at all times a logbook wherein the actual progress of construction including test conducted, weather condition and other pertinent data are to be recorded, same shall be made available for scrutiny and comments by the OBO representative during the conduct of his/her inspection pursuant to Section 207 of the National Building Code.</li>
                </ol>
            </li>
        </ol>
        ';
        PDF::MultiCell(0, 0, $html, 'TLR', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'PERMIT ISSUED BY:', 'LR', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(65, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        $name = isset($data->details->official) ? $data->details->official->fullname : '';
        PDF::MultiCell(58, 0, $name, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BUILDING OFFICIAL</b><br>(Signature Over Printed Name)<br>Date__________________', 'LBR', 'C', 0, 1, '', '', true, 0, true);


        //PDF::Output('Fence Permit '.$data->application_no.'.pdf');
        $filename ='FencePermit.pdf';
            //$filename =$id.$filename."electronicpermit.pdf";
            //$mpdf->Output($filename, "I");
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            // PDF::Output($filename,'I'); exit;
            $isSignVeified = 1;
            $officialid = $this->_commonmodel->getuseridbyempid($data->details->efa_building_official);
            $signType = $this->_commonmodel->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature ="";
                if(!empty($officialid->user_id)){
                 $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                }
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = '231,260,365,213,2';
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
                        PDF::Image($path,80,200,50);
                    }
                }
            }
            PDF::Output($filename,"I");
    }

    public function sanitaryPrint($id)
    {
        $data = $this->_engjobrequest->find($id);
        $missing = '';
        PDF::SetTitle('Sanitary Permit/Plumbing Permit');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);
        $default_font_size = PDF::SetFont('helvetica','',8);

        $default_font_size;
        PDF::Cell(195.85,3,'Republic of the Philippines','',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(0,3,'DEPARTMENT OF PUBLIC WORKS & HIGHWAYS',0 ,0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','',10);
        PDF::Cell(195.85,3,'OFFICE OF THE LOCAL BUILDING OFFICIAL','',0,'C');
        PDF::ln();
        PDF::ln();

        $default_font_size;
        PDF::Cell(135,3,'Application No.',0,0,'L');
        PDF::Cell(55,3,'Permit No.',0,0,'L');
        PDF::ln();
        foreach (str_split($data->application_no) as $value) {
            PDF::Cell(5,3, $value, 1,0,'C');
        }
        PDF::Cell(85,3,'',0,0,'L');
        if ($data->details->permit) {
            foreach (str_split($data->details->permit->ebpa_permit_no) as $value) {
                PDF::Cell(5,3, $value, 1,0,'C');
            }
        } else {
            for ($i=0; $i < 12; $i++) { 
                PDF::Cell(5,3, '', 1,0,'C');
            }
        }

        PDF::ln();
        PDF::ln();
        PDF::ln();
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(195.85,3,'SANITARY/PLUMBING PERMIT',0,0,'C');
        PDF::ln();
        $espa_application_date = Carbon::parse($data->details->espa_application_date);
        $application_date = ($espa_application_date->year != -1)? $espa_application_date->toFormattedDateString():"";
        PDF::Cell(70,3,$application_date,'B',0,'C');
        PDF::Cell(50,3,'',0 ,0,'C');
        $espa_issued_date = Carbon::parse($data->details->espa_issued_date);
        $issued_date = ($espa_issued_date->year != -1)? $espa_issued_date->toFormattedDateString():"";
        PDF::Cell(70,3,$issued_date,'B',0,'C');
        PDF::ln();
        
        PDF::Cell(70,3,'Date of Application','T',0,'C');
        PDF::Cell(50,3,'',0 ,0,'C');
        PDF::Cell(70,3,'Date of Issued','T',0,'C');

        PDF::ln();
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 1 (TO BE ACCOMPLISHED BY SANITARY ENGINEER/MASTER PLUMBER, IN PRINT)','',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::MultiCell(145, 3,'NAME OF OWNER/APPLICANT:', 'LT','L', 0, 0, '', '', true);
        PDF::MultiCell(45, 3,'', 'LTR','L', 0, 0, '', '', true);
        PDF::ln();
        
        $default_font_size;
        PDF::MultiCell(50.5, 3,'LAST NAME', 'L','L', 0, 0, '', '', true);
        PDF::MultiCell(48.5, 3,'FIRST NAME', '','L', 0, 0, '', '', true);
        PDF::MultiCell(46, 3,'MIDDLE NAME', '','L', 0, 0, '', '', true);
        PDF::MultiCell(45, 3,'TAX ACCOUNT NO.', 'LR','C', 0, 0, '', '', true);
        PDF::ln();
        
        PDF::MultiCell(50, 3, '<b>'.$data->applicant->rpo_custom_last_name.'</b>', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 3, '<b>'.$data->applicant->rpo_first_name.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 3, '<b>'.$data->applicant->rpo_middle_name.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 3, '<b>'.$data->details->taxacctno.'</b>', 'LR', 'C', 0, 1, '', '', true, 0, true);

        PDF::Cell(145,3, 'ADDRESS', 'LR',0,'L');
        PDF::Cell(45, 3, '', 'TR',0,'L');
        PDF::ln();
        
        PDF::Cell(20,3,'NO.','L',0,'C');
        PDF::Cell(42,3,'STREET',0,0,'C');
        PDF::Cell(43,3,'BARANGAY',0,0,'C');
        PDF::Cell(40,3,'CITY/MUNICIPALITY',0,0,'C');
        PDF::Cell(45,3,'TELEPHONE NO.','LR',0,'C');
        PDF::ln();

        PDF::MultiCell(20, 3, '<b>'.$data->applicant->rpo_address_house_lot_no.'</b>', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(42, 3, '<b>'.$data->applicant->rpo_address_street_name.'</b>', ' ', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(43, 3, '<b>'.$data->applicant->brgy_name.'</b>', ' ', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 3, '<b>'.$data->applicant->municipality.'</b>', ' ', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 3, '<b>'.$data->applicant->p_telephone_no.'</b>', 'LRB', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(40,3,'Location of Installation:', 'LT',0,'L');
        PDF::Cell(150,3,'No., Street, Barangay, Municipality', 'TR',0,'C');
        PDF::ln();

        PDF::Cell(40,3,' ', 'LB',0,'L');
        PDF::MultiCell(150, 3, '<b>'.$data->details->lotno.' '.$data->details->blkno.' '.$data->details->totno.', '.$data->details->Street.', '.$data->applicant->brgy_name.' Palayan City</b>', 'RB', 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(150,3,$data->details->espa_location, 'BR',0,'C');//still missing data
        PDF::ln();

        $defaultScope = ['New Installation','Addition', 'Repair', 'Removal'];
        $others = !in_array($data->details->bldg_scope, $defaultScope)? $data->details->bldg_scope:'';
        $others_check = !in_array($data->details->bldg_scope, $defaultScope)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(55,3,'SCOPE OF WORK:', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('Addition'));
        PDF::Cell(21,3,'Addition of', 0,0,'L');
        PDF::Cell(40,4,'', 'B',0,'L');
        PDF::Cell(20,3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','','<div style="font-size:2pt">&nbsp;</div>'.$others_check);// fix this
        PDF::Cell(44,3,'OTHERS (SPECIFY)', 'R',0,'L');
        PDF::ln();
        PDF::Cell(15,3,'', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->bldgScopeCheck('New Installation'));
        PDF::Cell(35,3,'NEW INSTALLATION', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('Repair'));
        PDF::Cell(21,3,'Repair of', 0,0,'L');
        PDF::Cell(40,4,'', 'B',0,'L');
        PDF::Cell(9,3,'', 0,0,'L');
        PDF::Cell(26.5,3,$others, 'B',0,'L');
        PDF::Cell(5,3,'of', 0,0,'L');
        PDF::Cell(26.5,3,$data->details->ebsa_scope_remarks, 'B',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(55,3,' ', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('Removal'));
        PDF::Cell(21,3,'Removal of', 0,0,'L');
        PDF::Cell(40,3,'', 'B',0,'L');
        PDF::Cell(9,3,'', 0,0,'L');

        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(5,5,'of', 0,0,'L');
        PDF::Cell(26.5,3,'', 'B',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(0,.2,'', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(190,3,'USE OR TYPE OF OCCUPANCY:', 'LTR',0,'L');
        PDF::ln();

        $defaultType = ['Residential','Agricultural','Commercial','Parks, Plazas, Monuments','Industrial','Recreational','Institutional'];
        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Residential'));
        PDF::Cell(26,3,'RESIDENTIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Agricultural'));
        PDF::Cell(29,3,'AGRICULTURAL', 0,0,'L');
        PDF::Cell(44,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Commercial'));
        PDF::Cell(26,3,'COMMERCIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Parks, Plazas, Monuments'));
        PDF::Cell(50,3,'PARKS, PLAZAS, MONUMENTS', 0,0,'L');
        PDF::Cell(23,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Industrial'));
        PDF::Cell(26,3,'INDUSTRIAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Recreational'));
        PDF::Cell(50,3,'RECREATIONAL', 0,0,'L');
        PDF::Cell(23,3,'', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'', 'L',0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->occupancyTypeCheck('Institutional'));
        PDF::Cell(26,3,'INSTITUTIONAL', 0,0,'L');
        PDF::Cell(59,3,'', '',0,'L');
        PDF::Cell(10,3,'', 0,0,'L');
        $other_check = (!in_array($data->details->occupancy_type,$defaultType) )? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::writeHTMLCell(5,3,'','',$other_check);
        $other_type =(!in_array($data->details->occupancy_type,$defaultType) )? $data->details->occupancy_type:'';
        PDF::Cell(50,3,'OTHERS (SPECIFY): '.$other_type, 0,0,'L');
        PDF::Cell(23,3, '', '',0,'L');
        PDF::Cell(2,3,'', 'R',0,'L');
        PDF::ln();

        // fixtures
        PDF::Cell(190,3,'FIXTURES TO BE INSTALLED', 'LTR',0,'L');
        PDF::ln();

        PDF::Cell(10,3,'QTY', 'L',0,'L');
        PDF::Cell(20,3,'NEW', 0,0,'C');
        PDF::Cell(20,3,'EXISTING', 0,0,'C');

        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'KINDS OF FIXTURES', 0,0,'L');
        
        PDF::Cell(5,3,' ', 0,0,'L');

        PDF::Cell(10,3,'QTY', 0,0,'L');
        PDF::Cell(20,3,'NEW', 0,0,'C');
        PDF::Cell(20,3,'EXISTING', 0,0,'C');

        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'KINDS OF FIXTURES', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10,3,' ', 'L',0,'L');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(10,3,' ', 0,0,'L');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');
        PDF::Cell(20,3,'FIXTURES', 0,0,'C');   
        PDF::Cell(40,3,' ', 'R',0,'C');  

        // PDF::ln();
        PDF::Cell(195.85,3,' ', 'LR',0,'L');
        PDF::ln();

        $water_closet_new_check = ($data->details->espa_water_closet_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $water_closet_exist_check = ($data->details->espa_water_closet_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_water_closet_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$water_closet_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$water_closet_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Closet', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');

        $bidette_new_check = ($data->details->espa_bidettet_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $bidette_exist_check = ($data->details->espa_bidettet_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_bidette_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$bidette_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$bidette_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Bidette', 'R',0,'L');
        PDF::ln();

        $floor_drain_new_check = ($data->details->espa_floor_drain_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $floor_drain_exist_check = ($data->details->espa_floor_drain_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_floor_drain_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$floor_drain_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$floor_drain_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Floor Drain', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $laundry_trays_new_check = ($data->details->espa_laundry_trays_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $laundry_trays_exist_check = ($data->details->espa_laundry_trays_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_laundry_trays_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$laundry_trays_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$laundry_trays_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Laundry Trays', 'R',0,'L');
        PDF::ln();

        $lavatories_new_check = ($data->details->espa_lavatories_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $lavatories_exist_check = ($data->details->espa_lavatories_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_lavatories_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$lavatories_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$lavatories_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Lavatories', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $dental_cuspidor_new_check = ($data->details->espa_dental_cuspidor_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $dental_cuspidor_exist_check = ($data->details->espa_dental_cuspidor_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_dental_cuspidor_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$dental_cuspidor_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$dental_cuspidor_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Dental Cuspidor', 'R',0,'L');
        PDF::ln();
        
        $kitchen_sink_new_check = ($data->details->espa_kitchen_sink_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $kitchen_sink_exist_check = ($data->details->espa_kitchen_sink_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_kitchen_sink_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$kitchen_sink_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$kitchen_sink_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Kitchen Sink', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $gas_heater_new_check = ($data->details->espa_gas_heater_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $gas_heater_exist_check = ($data->details->espa_gas_heater_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_gas_heater_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$gas_heater_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$gas_heater_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Gas Heater', 'R',0,'L');
        PDF::ln();

        $faucet_new_check = ($data->details->espa_faucet_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $faucet_exist_check = ($data->details->espa_faucet_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_faucet_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$faucet_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$faucet_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Faucet', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $electric_heater_new_check = ($data->details->espa_electric_heater_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $electric_heater_exist_check = ($data->details->espa_electric_heater_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_electric_heater_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$electric_heater_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$electric_heater_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Electric Heater', 'R',0,'L');
        PDF::ln();
        
        $shower_head_new_check = ($data->details->espa_shower_head_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $shower_head_exist_check = ($data->details->espa_shower_head_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_shower_head_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$shower_head_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$shower_head_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Shower Head', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $water_boiler_new_check = ($data->details->espa_water_boiler_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $water_boiler_exist_check = ($data->details->espa_water_boiler_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_water_boiler_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$water_boiler_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$water_boiler_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Water Boiler', 'R',0,'L');
        PDF::ln();
        
        $water_meter_new_check = ($data->details->espa_water_meter_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $water_meter_exist_check = ($data->details->espa_water_meter_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_water_meter_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$water_meter_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$water_meter_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Meter', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $drinking_fountain_new_check = ($data->details->espa_drinking_fountain_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $drinking_fountain_exist_check = ($data->details->espa_drinking_fountain_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_drinking_fountain_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$drinking_fountain_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$drinking_fountain_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,5,' ', 0,0,'L');
        PDF::Cell(35,3,'Drinking Fountain', 'R',0,'L');
        PDF::ln();

        $grease_trap_new_check = ($data->details->espa_grease_trap_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $grease_trap_exist_check = ($data->details->espa_grease_trap_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_grease_trap_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$grease_trap_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$grease_trap_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Grease Trap', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $bar_sink_new_check = ($data->details->espa_bar_sink_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $bar_sink_exist_check = ($data->details->espa_bar_sink_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_bar_sink_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$bar_sink_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$bar_sink_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Bar Sink', 'R',0,'L');
        PDF::ln();

        $bath_tubs_new_check = ($data->details->espa_bath_tubs_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $bath_tubs_exist_check = ($data->details->espa_bath_tubs_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_bath_tubs_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$bath_tubs_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$bath_tubs_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Bath Tubs', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $soda_fountain_new_check = ($data->details->espa_soda_fountain_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $soda_fountain_exist_check = ($data->details->espa_soda_fountain_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_soda_fountain_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$soda_fountain_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$soda_fountain_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Soda Fountain Sink', 'R',0,'L');
        PDF::ln();

        $slop_sink_new_check = ($data->details->espa_slop_sink_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $slop_sink_exist_check = ($data->details->espa_slop_sink_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_slop_sink_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$slop_sink_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$slop_sink_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Slop Sink', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $laboratory_new_check = ($data->details->espa_laboratory_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $laboratory_exist_check = ($data->details->espa_laboratory_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_laboratory_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$laboratory_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$laboratory_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Laboratory Sink', 'R',0,'L');
        PDF::ln();

        $urinal_new_check = ($data->details->espa_urinal_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $urinal_exist_check = ($data->details->espa_urinal_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_urinal_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$urinal_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$urinal_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Urinal', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $sterilizer_new_check = ($data->details->espa_sterilizer_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $sterilizer_exist_check = ($data->details->espa_sterilizer_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_sterilizer_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$sterilizer_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$sterilizer_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Sterilizer', 'R',0,'L');
        PDF::ln();

        $airconditioning_unit_new_check = ($data->details->espa_airconditioning_unit_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $airconditioning_unit_exist_check = ($data->details->espa_airconditioning_unit_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_airconditioning_unit_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$airconditioning_unit_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$airconditioning_unit_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Air Conditioning Unit', 0,0,'L');
        PDF::Cell(5,3,'', 0,0,'L');
        
        $swimmingpool_new_check = ($data->details->espa_swimmingpool_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $swimmingpool_exist_check = ($data->details->espa_swimmingpool_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_swimmingpool_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$swimmingpool_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$swimmingpool_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Swimming Pool', 'R',0,'L');
        PDF::ln();

        $water_tank_new_check = ($data->details->espa_water_tank_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $water_tank_exist_check = ($data->details->espa_water_tank_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 'L',0,'L');
        PDF::Cell(6, 3,$data->details->espa_water_tank_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$water_tank_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$water_tank_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(40,3,'Water Tank/Reservoir', 0,0,'L');
        PDF::Cell(5,3,' ', 0,0,'L');
        
        $others_new_check = ($data->details->espa_others_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $others_exist_check = ($data->details->espa_others_type === 2)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::Cell(6, 3,$data->details->espa_others_qty, 'B',0,'C');
        PDF::Cell(2, 3,'', 0,0,'L');
        PDF::writeHTMLCell(20,3,'','',$others_new_check, 0, 0, 0, true, 'C');
        PDF::writeHTMLCell(20,3,'','',$others_exist_check, 0, 0, 0, true, 'C');
        PDF::Cell(5,3,' ', 0,0,'L');
        PDF::Cell(35,3,'Others (specify)______', 'R',0,'L');
        PDF::ln();

        // this is just space
        PDF::Cell(2, 3,'', 'L',0,'L'); 
        PDF::Cell(35, 3,'', 0,0,'C');
        PDF::Cell(30, 3,'', 0,0,'L');
        PDF::Cell(35, 3,'', 0,0,'L');
        PDF::Cell(35, 3,'', 0,0,'C');
        PDF::Cell(30, 3,'', 0,0,'L');
        PDF::Cell(23, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::Cell(70, 3,'WATER SUPPLY:', 'LT',0,'L');
        PDF::Cell(70, 3,'SYSTEM OF DISPOSAL:', 'T',0,'L');
        PDF::Cell(50, 3,'', 'TR',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::writeHTMLCell(10,3,'','',$data->details->waterTypeCheck('Water Distribution System'));
        PDF::Cell(55, 3,'Water Distribution System', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Sanitary Sewer System'));
        PDF::Cell(54, 3,'Sanitary Sewer System', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Storm Drainage System'));
        PDF::Cell(49, 3,'Storm Drainage System', 'R',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::writeHTMLCell(10,3,'','',$data->details->waterTypeCheck('Shallow Well'));
        PDF::Cell(55, 3,'Shallow Well', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Waste Water Treatment Plant'));
        PDF::Cell(54, 3,'Waste Water Treatment Plant', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Surface Drainage'));
        PDF::Cell(49, 3,'Surface Drainage', 'R',0,'L');
        PDF::ln();
        
        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::writeHTMLCell(10,3,'','',$data->details->waterTypeCheck('Deep Well and Pump Set'));
        PDF::Cell(55, 3,'Deep Well & Pump Set', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Septic Vault / Imhoff Tank'));
        PDF::Cell(54, 3,'Septic Vault/Imhoff Tank', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Street Canal'));
        PDF::Cell(49, 3,'Street Canal', 'R',0,'L');
        PDF::ln();

        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::writeHTMLCell(10,3,'','',$data->details->waterTypeCheck('City/Municipal Water System'));
        PDF::Cell(55, 3,'City/Municipal Water System', 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Sanitary Sewer Connection'));
        PDF::Cell(54, 3,'Sanitary Sewer Connection', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Water Course'));
        PDF::Cell(49, 3,'Water Course', 'R',0,'L');
        PDF::ln();

        $defaultWater = ['Water Distribution System', 'Shallow Well', 'Deep Well and Pump Set', 'City/Municipal Water System'];
        $water_other_check = (!in_array($data->details->water_type,$defaultWater))? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $water_other = (!in_array($data->details->water_type,$defaultWater))? $data->details->water_type:' ';
        $defaultDisposal = ['Sanitary Sewer System', 'Storm Drainage System', 'Waste Water Treatment Plant', 'Surface Drainage','Septic Vault / Imhoff Tank','Street Canal','Sanitary Sewer Connection','Water Course', 'Sub Surface Sand Filter'];
        $disposal_other_check = (!in_array($data->details->disposal_type,$defaultDisposal))? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $disposal_other = (!in_array($data->details->disposal_type,$defaultDisposal))? $data->details->disposal_type: '';
        PDF::Cell(6, 3,'', 'L',0,'L');
        PDF::writeHTMLCell(10,3,'','',$water_other_check);
        PDF::Cell(55, 3,'Others: '.$water_other, 0,0,'L');
        PDF::Cell(6, 3,'', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$data->details->disposalTypeCheck('Sub Surface Sand Filter'));
        PDF::Cell(54, 3,'Sub Surface Sand Filter', 0,0,'L');
        PDF::writeHTMLCell(5,3,'','',$disposal_other_check);
        PDF::Cell(49, 3,'Others: '.$disposal_other, 'R',0,'L');
        PDF::ln();

        PDF::Cell(53, 3,'NUMBER OF STOREYS OF BUILDING: ', 'LT',0,'L');
        PDF::MultiCell(47, 3, '<b>'.$data->details->espa_no_of_storey.'</b>', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(90, 3,'TOTAL AREA OF BUILDING/SUBDIVISION', 'TR',0,'L');
        PDF::ln();

        PDF::Cell(30, 3,'Proposed Date', 'L',0,'L');
        PDF::Cell(35, 3,' ', 'B',0,'L');
        PDF::Cell(35, 3,' ', 0,0,'L');
        PDF::Cell(12, 3,'SQM.', 0,0,'L');
        PDF::MultiCell(40, 3, '<b>'.$data->details->espa_floor_area.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(40, 3,$data->details->espa_floor_area, 'B',0,'L');
        PDF::Cell(38, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::Cell(32, 3,'Start of Installation', 'L',0,'L');
        PDF::MultiCell(35, 3, '<b>'.$data->details->espa_installation_date.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(33, 3,' ', 0,0,'L');
        PDF::Cell(40, 3,'Total Cost of Installation', 0,0,'L');
        PDF::MultiCell(40, 3, '<b>'.number_format($data->details->espa_installation_cost).'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(10, 3,' ', 'R',0,'L');
        PDF::ln();


        PDF::Cell(40, 3,'Expected Date of Completion', 'L',0,'L');
        PDF::MultiCell(35, 3, '<b>'.$data->details->espa_completion_date.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(25, 3,' ', 0,0,'L');
        PDF::Cell(20, 3,'Prepared By', 0,0,'L');
        // dd($data->details->assessedBy);
        $assessed_by = ($data->details->assessedBy==null)?'':$data->details->assessedBy->fullname;
        PDF::MultiCell(40, 3, '<b>'.$assessed_by.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(30, 3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 2 (TO BE ACCOMPLISHED BY THE BUILDING OFFICIAL)','TB',0,'l');
        PDF::ln();

        $default_font_size;
        PDF::Cell(190,3,'','LR',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(190, 3,'ACTION TAKEN:', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',8);
        PDF::Cell(190, 3,'Permit is hereby granted to install the sanitary/plumbing fixture enumerated herein subject', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(190, 3,'to the following conditions:', 'LR',0,'L');
        PDF::ln();
        
        PDF::Cell(10, 3,'1.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That the proposed installation shall be in accordance with approval plans filed', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'with this office & in conformity with the national building code.', 0,0,'L');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'2.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a duly licensed sanitary engineer/master plumber in-charge of', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'installation/construction.', 0,0,'L');
        PDF::Cell(20, 3,'', 0,0,'R');
        $name = isset($data->details->official) ? $data->details->official->fullname : '';
        PDF::MultiCell(40, 3, '<b>'.$name.'</b>', '', 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(40, 3,$data->details->official->fullname, 'B',0,'C');
        PDF::Cell(15, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'3.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a certificate of completion duly signed by a sanitary engineer/master', 0,0,'J');
        PDF::Cell(15, 3,'', 0,0,'R');
        PDF::Cell(50, 3,'Building Official', 'T',0,'C');
        PDF::Cell(10, 3,'', 'R',0,'R');
        PDF::ln();
        $isSignVeified = 1;
        $officialid = $this->_commonmodel->getuseridbyempid($data->details->espa_building_official);
        $signature ="";
        if(!empty($officialid->user_id)){
         $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
        }
        $path =  public_path().'/uploads/e-signature/'.$signature;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        if($isSignVeified==1 && $signType==1){
                    // Apply E-Signature
                    if(!empty($signature) && File::exists($path)){
                        PDF::Image($path,140,240,50);
                    }
                }

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'plumber in-charge of installation shall be submitted not later than seven (7) days', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'after completion of the installation.', 0,0,'L');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(10, 3,'4.', 'L',0,'R');
        PDF::Cell(5, 3,' ', 0,0,'R');
        PDF::Cell(100, 3,'That a certificate of final inspection and a certificate of occupancy be secured', 0,0,'J');
        PDF::Cell(15, 3,'', 0,0,'R');
        PDF::Cell(50, 3,'', 'B',0,'C');
        PDF::Cell(10, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'prior to the actual occupancy of the building.', 0,0,'L');
        PDF::Cell(30, 3,'', 0,0,'R');
        PDF::Cell(20, 3,'DATE', 0,0,'C');
        PDF::Cell(25, 3,'', 'R',0,'R');
        PDF::ln();

        PDF::SetFont('helvetica','B',8);
        PDF::Cell(190, 3,'NOTE:', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',8);

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(100, 3,'THIS PERMIT MAY BE CANCELLED OR REVOKED PURSUANT TO THE', 0,0,'J');
        PDF::Cell(75, 3,' ', 'R',0,'R');
        PDF::ln();

        PDF::Cell(15, 3,' ', 'L',0,'R');
        PDF::Cell(175, 3,'SECTION 305 & 306 OF THE "NATIONAL BUILDING CODE"', 'R',0,'L');
        PDF::ln();

        PDF::Cell(190, 3,' ', 'LBR',0,'R');
        
        // 2nd page
        // PDF::AddPage('P', 'LEGAL');
        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 3 (TO BE ACCOMPLISHED BY THE RECEIVING & RECORDING SECTION)', 0,0,'l');
        PDF::ln();

        PDF::Cell(190,3,'BUILDING DOCUMENTS', 'LTR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(15,3,' ', 'L',0,'C');
        PDF::Cell(60,3,'Sanitary Plumbing Plans & Specifications', 0,0,'L');
        PDF::Cell(50,3,' ', 0,0,'C');
        PDF::Cell(60,3,'Cost Estimates', 0,0,'L');
        PDF::Cell(5,3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(15,3,' ', 'L',0,'C');
        PDF::Cell(60,3,'Bill of Materials', 0,0,'L');
        PDF::Cell(50,3,' ', 0,0,'C');
        PDF::Cell(60,3,'Others (Specify) ', 0,0,'L');
        PDF::Cell(5,3,' ', 'R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 4 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)', 'TB',0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'ASSESSED FEES', 'LRB',0,'C');
        PDF::ln();

        // $default_font_size;
        PDF::SetFont('helvetica', '', 8);
        PDF::Cell(38,3,'', 'LR',0,'C');
        PDF::Cell(38,3,'AMOUNT DUE', 'LR',0,'C');
        PDF::Cell(38,3,'ASSESSED BY', 'LR',0,'C');
        PDF::Cell(38,3,'O.R. NUMBER', 'LR',0,'C');
        PDF::Cell(38,3,'DATE PAID', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(38,3,'', 1,0,'C');
        PDF::MultiCell(38, 3, '<b>'.$data->details->espa_amount_due.'</b>', 1, 'C', 0, 0, '', '', true, 0, true);
        $assessed_by = ($data->details->assessedBy==null)?'':$data->details->assessedBy->fullname;
        PDF::MultiCell(38, 3, '<b>'.$assessed_by.'</b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(38, 3, '<b>'.$data->details->espa_or_no.'</b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(38, 3, '<b>'.$data->details->espa_date_paid.'</b>', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::Cell(38,3,'', 1,0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','B',10);
        PDF::Cell(190,3,'BOX 5 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)', 'TB',0,'l');
        PDF::ln();

        PDF::Cell(190,3,'PROGRESS FLOW', 'LRB',0,'C');
        PDF::ln();

        $tbl = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">
                        <tr>
                            <td colspan="1" rowspan="2" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                NOTED:<br>   Chief, Processing Division/Section
                            </td>
                            <td colspan="2" rowspan="1" align="center" width="15%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                IN
                            </td>
                            <td colspan="2" rowspan="1" align="center" width="15%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                OUT
                            </td>
                            <td colspan="1" rowspan="2" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                ACTIONS/REMARKS
                            </td>
                            <td colspan="1" rowspan="2" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                                PROCESSED BY
                            </td>
                        </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Receiving and Recording
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Geodetic (Line & Grade)
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="30%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        Sanitary
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="7.5%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                </table>';
        PDF::writeHTML($tbl, false, false, false, false, '');
    
        PDF::ln();

        PDF::SetFont('helvetica',' ',10);
        PDF::Cell(190,3,'WE HEREBY AFFIX OUR HANDS SIGNIFYING OUR CONFORMITY TO THE INFORMATION HEREIN.', 0,1,'J');
        PDF::Cell(190,3,'ABOVE SETFORTH.', 0,0,'L');
        PDF::ln();PDF::ln();

        PDF::Cell(97.925,3,'BOX 6', 'B',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'BOX 8', 'B',0,'L');
        PDF::ln();
        
        PDF::Cell(65,3,'SANITARY ENGINEER/MASTER', 'LR',0,'L');
        PDF::Cell(32.925,3,'P.R.C. Reg. No.', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'Signature', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'PLUMBER', 'LR',0,'L');
        PDF::MultiCell(32.925, 3, '<b>'.$data->details->signprcno.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'Signed and Sealed Plans & Specifications', 'LRB',0,'J');
        PDF::Cell(32.925,3,' ', 'LRB',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        
        $applicantConsultant = isset($data->details->applicantConsultant) ? $data->details->applicantConsultant->fullname : '';
        PDF::Cell(7,3, '', 'L', 0,'L');
        PDF::MultiCell(72.84, 3, '<b>'.$applicantConsultant.'</b>', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::Cell(8,3, '', 'R',0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'Print Name', 'LR',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,'APPLICANT', 'LR',0,'C');
        PDF::ln();

        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        PDF::MultiCell(97.925, 3, '<b>'.$consultant.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(87.84,3,' ', 'LR',0,'C');
        PDF::ln();

        PDF::MultiCell(97.925, 5, 'Address: <b>'.$data->details->signaddress.'</b>', 'LTR', 'L',0,0,'','',true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0);
        PDF::Cell(5,' ', '',0,'L');
        PDF::Cell(29.28,3,'Res. Cert. No.', 'LTR',0,'C');
        PDF::Cell(29.28,3,'Date Issued', 'LTR',0,'C');
        PDF::Cell(29.28,3,'Place Issued', 'LTR',0,'C');    
        PDF::ln();

        PDF::Cell(97.925,3, '','LR','L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::MultiCell(29.28, 3, '<b>'.$data->details->rescertno.'</b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(29.28, 3, '<b>'.$data->details->dateissued.'</b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(29.28, 3, '<b>'.$data->details->placeissued.'</b>', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(97.925, 5, '', 'LBR', 'L',0,1,'','',true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0);

        PDF::Cell(33.925,3,'P.T.R. No. ', 'LRB',0,'L');
        PDF::Cell(32,3,'Date Issued ', 'LRB',0,'L');
        PDF::Cell(32,3,'Place Issued ', 'LRB',0,'L');
        PDF::Cell(5,' ', '',0,'L');
        PDF::ln();
        
        PDF::MultiCell(33.925, 3, '<b>'.$data->details->signptrno.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(32, 3, '<b>'.$data->details->signdateissued.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(32, 3, '<b>'.$data->details->signplaceissued.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true); 
        PDF::Cell(5,' ', '',0,'L');
        PDF::ln();
        
        PDF::Cell(65.925,3,'Signature', 1,0,'L');
        PDF::MultiCell(32, 3, 'TIN <b>'.$data->details->signtin.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(32,3,'TIN '.$data->details->consultant->tin_no, 1,0,'L');
        PDF::ln();PDF::ln();

        //start
        PDF::Cell(97.925,3,'BOX 7', 'B',0,'L');
        PDF::ln();
        
        PDF::Cell(65,3,'SANITARY ENGINEER/MASTER', 'LR',0,'L');
        PDF::Cell(32.925,3,'P.R.C. Reg. No.', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65,3,'PLUMBER', 'LR',0,'L');
        PDF::MultiCell(32.925, 3, '<b>'.$data->details->inchargeprcregno.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(65,3,'In-charge of Installation', 'LRB',0,'C');
        PDF::Cell(32.925,3,' ', 'LRB',0,'L');
        PDF::ln();

        PDF::Cell(97.925,3,'Print Name ', 'LR',0,'L');
        PDF::ln();

        $incharge = isset($data->details->incharge) ? $data->details->incharge->fullname : '';
        PDF::MultiCell(97.925, 3, '<b>'.$incharge.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(97.925,3,$data->details->incharge->fullname, 'LR',0,'L');
        PDF::ln();

        PDF::MultiCell(97.925, 5, 'Address: <b>'.$data->details->inchargenaddress.'</b>', 'LTR', 'L',0,0,'','',true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0);
        PDF::ln();
        PDF::Cell(97.925,3, '','LRB','L');
        PDF::ln();

        PDF::Cell(33.925,3,'P.T.R. No. ', 'LRB',0,'L');
        PDF::Cell(32,3,'Date Issued ', 'LRB',0,'L');
        PDF::Cell(32,3,'Place Issued ', 'LRB',0,'L');
        PDF::ln();

        PDF::MultiCell(33.925, 3, '<b>'.$data->details->inchargeptrno.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(32, 3, '<b>'.$data->details->inchargedateissued.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(32, 3, '<b>'.$data->details->inchargeplaceissued.'</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(33.925,3,$data->details->incharge->ptr_no, 'LR',0,'L');
        // PDF::Cell(32,3,$data->details->incharge->ptr_date_issued, 'LR',0,'L');
        // PDF::Cell(32,3,$data->details->incharge->ptr_issued_at, 'LR',0,'L');
        PDF::ln();

        PDF::Cell(65.925,3,'Signature', 1,0,'L');
        PDF::MultiCell(32, 3, 'TIN <b>'.$data->details->inchargetin.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(32,3,'TIN '.$data->details->incharge->tin_no, 1,0,'L');
        // // //END

        //PDF::Output('Sanitary Permit/Plumbing Permit'.'.pdf');
        $filename ='SanitaryPermit.pdf';
            //$filename =$id.$filename."electronicpermit.pdf";
            //$mpdf->Output($filename, "I");
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            // PDF::Output($filename,'I'); exit;
            $isSignVeified = 1;
            $officialid = $this->_commonmodel->getuseridbyempid($data->details->espa_building_official);
           
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature ="";
                if(!empty($officialid->user_id)){
                 $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                }
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = '397,157,547,96,1';
                    if(!empty($signature) && File::exists($path)){
                        // Apply Digital Signature
                        PDF::Output($folder.$filename,'F');
                        $arrData['signaturePath'] = $signature;
                        $arrData['filename'] = $filename;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                }
            }
            PDF::Output($filename,"I");
    }

    public function mechanicalPrint($id)
    {
        $data = $this->_engjobrequest->find($id);
        PDF::SetTitle('Mechanical Permit');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(TRUE, 0);

        
        $border = 0;
        $font_size = 8;
        $cell_height = 4;

        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','',$font_size);
        PDF::MultiCell(0, 0, 'Republic of the Philippines', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'City/Municipality of Palayan', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Province of Nueva Echija', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OFFICE OF THE BUILDING OFFICIAL</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h2>MECHANICAL PERMIT</h2>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::SetFont('Helvetica', '', 8);
        PDF::MultiCell(70, 0, 'APPLICATION NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'MP NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'BUILDING PERMIT NO.', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        foreach (str_split($data->application_no) as $value) {
                PDF::Cell(5,$cell_height,$value,1,0,'C');
        }
        
        PDF::Cell(25,$cell_height,'',0,0,'C');
        
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');
        PDF::Cell(5,$cell_height,' ',1,0,'C');

        PDF::Cell(20,$cell_height,' ',0,0,'C');

        if ($data->details->permit) {
            foreach (str_split($data->details->permit->ebpa_permit_no) as $value) {
                PDF::Cell(5,$cell_height,$value,1,0,'C');
            }
        } else {
            for ($i=0; $i < 12; $i++) { 
                PDF::Cell(5,$cell_height,'',1,0,'C');
            }
        }

        PDF::ln();PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0,$cell_height,'BOX 1 (TO BE ACCOMPLISHED IN PRINT BY THE OWNER/APPLICANT)', 'B' ,0,'l');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(40,$cell_height,'OWNER/APPLICANT','L',0,'C');
        PDF::Cell(40,$cell_height,'LAST NAME',$border,0,'C');
        PDF::Cell(40,$cell_height,'FIRST NAME',$border,0,'C');
        PDF::Cell(40,$cell_height,'MIDDLE NAME',$border,0,'C');
        PDF::Cell(30,$cell_height,'TIN','LR',0,'L');
        PDF::ln();
        // dd($data->details);
        PDF::Cell(40,$cell_height,'','L',0,'C');
        PDF::MultiCell(40, $cell_height, '<b>'.$data->applicant->rpo_custom_last_name.'</b>', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, $cell_height, '<b>'.$data->applicant->rpo_first_name.'</b>', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, $cell_height, '<b>'.$data->applicant->rpo_middle_name.'</b>', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, $cell_height, '<b>'.$data->applicant->p_tin_no.'</b>', 'LR', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(70,5,'FOR CONSTRUCTION OWNED','LT',0,'L');
        PDF::Cell(60,5,'FORM OF OWNERSHIP','LT',0,'L');
        PDF::Cell(60,5,'USE OR CHARACTER OF OCCUPANCY','LTR',0,'L');
        PDF::ln();

        PDF::Cell(70,5,'BY AN ENTERPRISE','L',0,'L');
        PDF::MultiCell(60, 5, '<b>'.$data->details->ema_form_of_own.'</b>', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60,5,'<b>'.$data->details->ema_economic_act.'</b>','LR','L', 0, 0, '', '', true, 0, true);
        PDF::ln();
        PDF::Cell(165,3, 'ADDRESS', 'LTR',0,'L');
        PDF::Cell(25, 3, '', 'TR',0,'L');
        PDF::ln();

        PDF::Cell(20,3,'NO.','L',0,'C');
        PDF::Cell(42,3,'STREET',$border,0,'C');
        PDF::Cell(43,3,'BARANGAY',$border,0,'C');
        PDF::Cell(40,3,'CITY/MUNICIPALITY',$border,0,'C');
        PDF::Cell(20,3,'ZIP CODE',$border,0,'C');
        PDF::Cell(25,3,'TELEPHONE NO.','LR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica', 'B', 8);
        PDF::Cell(20,3,$data->applicant->rpo_address_house_lot_no,'LB',0,'C');
        PDF::Cell(42,3,$data->applicant->rpo_address_street_name,'B',0,'C');
        PDF::Cell(43,3,$data->applicant->brgy_name,'B',0,'C');
        PDF::Cell(40,3,$data->applicant->municipality,'B',0,'C');
        PDF::Cell(20,3,'','B',0,'C');
        PDF::Cell(25,3,$data->applicant->p_telephone_no,'LRB',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica', '', 8);
        PDF::Cell(50,5,'LOCATION OF CONSTRUCTION','L',0,'L');
        PDF::Cell(14,5,'LOT NO.',0,0,'L');
        PDF::MultiCell(20,5,'<b>'.$data->details->lotno.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(13,5,'BLK NO.','',0,'L');
        PDF::MultiCell(20,5,'<b>'.$data->details->blkno.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(13,5,'TCT NO.','',0,'L');
        PDF::MultiCell(20,5,'<b>'.$data->details->totno.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(20,5,'TAX DEC. NO.','',0,'L');
        PDF::MultiCell(18,5,'<b>'.$data->details->taxdecno.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(2,5,'','R',0,'L');
        PDF::ln();

        PDF::Cell(13,5,'STREET','L',0,'L');
        PDF::MultiCell(25,5,'<b>'.$data->details->Street.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(18,5,'BARANGAY','',0,'L');
        PDF::MultiCell(60,5,'<b>'.$data->applicant->brgy_name.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(34,5,'CITY/MUNICIPALITY OF','',0,'L');
        PDF::MultiCell(38,5,'<b>'.'Palayan'.'</b>','B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(2,5,'','R',0,'L');
        PDF::ln(5);

        PDF::MultiCell(0, 0, '<b>SCOPE OF WORK</b>', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        $defaultType = ['New Construction','Renovation','Raising','Erection','Conversion','Demolition','Addition','Repair','Accessory Building Structure','Alteration','Moving'];
        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('New Construction'));
        PDF::Cell(50,$cell_height,'NEW CONSTRUCTION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('Renovation'));
        PDF::Cell(50,$cell_height,'RENOVATION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','','<div style="font-size:2pt">&nbsp;</div>'.$data->details->bldgScopeCheck('Raising'));
        PDF::Cell(50,$cell_height,'RAISING', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Erection'));
        PDF::Cell(50,$cell_height,'ERECTION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Conversion'));
        PDF::Cell(50,$cell_height,'CONVERSION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Demolition'));
        PDF::Cell(50,$cell_height,'DEMOLITION', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Addition'));
        PDF::Cell(50,$cell_height,'ADDITION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Repair'));
        PDF::Cell(50,$cell_height,'REPAIR', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Accessory Building Structure'));
        PDF::Cell(50,$cell_height,'ACCESSORY BUILDING STRUCTURE', $border,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        $other_check = (!in_array($data->details->bldg_scope,$defaultType) )? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $other_type =(!in_array($data->details->bldg_scope,$defaultType) )? $data->details->bldg_scope:'';
        PDF::Cell(3,$cell_height,'','L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Alteration'));
        PDF::Cell(50,$cell_height,'ALTERATION', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Moving'));
        PDF::Cell(50,$cell_height,'MOVING', $border,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$other_check);
        PDF::Cell(25,$cell_height,'OTHERS (Specify): '.$other_type, $border,0,'L');
        PDF::Cell(25,$cell_height,'', 'B' ,0,'L');
        PDF::Cell(0,$cell_height,'','R',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, 5,'BOX 2 (TO BE ACCOMPLISHED BY THE DESIGN PROFESSIONAL)','TB',0,'l');
        PDF::ln();

        PDF::Cell(0, 5,'INSTALLATION AND OPERATION OF:','LR',0,'l');
        PDF::ln();

        $defaultInstall = ['Boiler','Central Airconditioning','Dumbwaiter','Pressure Vessel','Mechanical Ventilation','Pumps','Internal Combustion Engine','Escalator','Compressed Air, Vacuum Institutional and/or Industrial Gas','Refrigetation And Ice Making','Moving Sidewalk', 'Window Type Airconditioning','Freight Elevator','Pneumatic Tubes, Conveyors and/or Monorals','Packaged Split Type Airconditioning','Passenger Elevator','Cable Car','Funicular'];
        PDF::SetFont('helvetica','',6);
        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Boiler'));
        PDF::Cell(65,$cell_height,'BOILER', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Central Airconditioning'));
        PDF::Cell(50,$cell_height,'CENTRAL AIRCONDITIONING', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Dumbwaiter'));
        PDF::Cell(50,$cell_height,'DUMBWAITER', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Pressure Vessel'));
        PDF::Cell(65,$cell_height,'PRESSURE VESSEL', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Mechanical Ventilation'));
        PDF::Cell(50,$cell_height,'MECHANICAL VENTILLATION', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Pumps'));
        PDF::Cell(50,$cell_height,'PUMPS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Internal Combustion Engine'));
        PDF::Cell(65,$cell_height,'INTERNAL COMBUSTION ENGINE', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Escalator'));
        PDF::Cell(50,$cell_height,'ESCALATOR', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Compressed Air, Vacuum Institutional and/or Industrial Gas'));
        PDF::Cell(50,$cell_height,'COMPRESSED AIR, VACUUM INSTITUTIONAL', 'R' ,0,'J');
        PDF::ln();

        $install_ref_check = $data->details->installOperationCheck('Refrigetation And Ice Making');
        $install_sidewalk_check = $data->details->installOperationCheck('Moving Sidewalk');
        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Refrigetation And Ice Making'));
        PDF::Cell(65,$cell_height,'REFRIGETATION AND ICE MAKING', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Moving Sidewalk'));
        PDF::Cell(50,$cell_height,'MOVING SIDEWALK', $border ,0,'L');
        PDF::Cell(5,$cell_height,' ', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'and/or INDUSTRIAL GAS', 'R' ,0,'L');
        PDF::ln();

        $install_winaircon_check = $data->details->installOperationCheck('Window Type Airconditioning');
        $install_elevator_check = $data->details->installOperationCheck('Freight Elevator');
        $install_tube_check = $data->details->installOperationCheck('Pneumatic Tubes, Conveyors and/or Monorals');
        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Window Type Airconditioning'));
        PDF::Cell(65,$cell_height,'WINDOW TYPE AIRCONDITIONING', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Freight Elevator'));
        PDF::Cell(50,$cell_height,'FREIGHT ELEVATOR', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Pneumatic Tubes, Conveyors and/or Monorals'));
        PDF::Cell(50,$cell_height,'PNEUMATIC TUBES, CONVEYORS', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Packaged Split Type Airconditioning'));
        PDF::Cell(65,$cell_height,'PACKAGED SPLIT TYPE AIRCONDITIONING', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Passenger Elevator'));
        PDF::Cell(50,$cell_height,'PASSENGER ELEVATOR', $border ,0,'L');
        PDF::Cell(5,$cell_height,' ', 0 ,0,'L');
        PDF::Cell(50,$cell_height,'and/or MONORALS', 'R' ,0,'L');
        PDF::ln();

        $install_other_check = (!in_array($data->details->bldg_scope,$defaultInstall) )? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $install_other_type =(!in_array($data->details->bldg_scope,$defaultInstall) )? (($data->details->bldg_scope === 'Others')?$data->details->bldg_scope:$data->details->others):' ';
        PDF::Cell(10,$cell_height,' ', 'L' ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$install_other_check);
        PDF::Cell(20,$cell_height,'OTHERS (Specify): '.$install_other_type, $border ,0,'L');
        PDF::Cell(25,$cell_height,'', 'B' ,0,'L');
        PDF::Cell(20,$cell_height,'', 0 ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Cable Car'));
        PDF::Cell(50,$cell_height,'CABLE CAR', $border ,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',$data->details->bldgScopeCheck('Funicular'));
        PDF::Cell(50,$cell_height,'FUNICULAR', 'R' ,0,'L');
        PDF::ln();

        PDF::Cell(0,$cell_height,' ', 'LR' ,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(5, $cell_height,'','L',0,'l');
        PDF::Cell(22, $cell_height,'PREPARED BY:', '' ,0,'l');
        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        PDF::MultiCell(150, 5, '<b>'.$consultant.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(13, $cell_height,'','R',0,'l');
        PDF::ln();
        
        PDF::Cell(0, $cell_height,'','LR',0,'l');
        PDF::ln();
        PDF::Cell(0, $cell_height,'','LRB',0,'l');
        PDF::ln();PDF::ln();

        // box 3 start
        
        PDF::Cell(95, $cell_height,'BOX 3',0,0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,'BOX 4', 0,0,'l');
        PDF::ln();

        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        $box3 = '<table width="100%" cellspacing="0" cellpadding="1">
                    <tr>
                        <td align="left" width="100%" style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                            <b>DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="100%" style="border-right: 1px solid black; border-left: 1px solid black;">
                                
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="10%" style="border-left: 1px solid black;">
                            
                        </td>
                        <td align="center" width="80%" style="border-bottom: 1px solid black;">
                            '.$consultant.'
                        </td>
                        <td align="left" width="10%" style="border-right: 1px solid black;">
                                    
                        </td>
                    </tr>
                    <tr>
                        <td align="center" width="100%" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                            <b>PROFESSIONAL MECHANICAL ENGINEER</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" width="100%" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                            (Signed and Sealed Over Printed Name)
                        </td>
                    </tr>
                    <tr>
                        <td align="center" width="100%" height="15" style="border-left: 1px solid black; border-right: 1px solid black; font-size: 8px">
                            Date __________________
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            Address: <b>'.$data->details->signaddress.'</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            PRC. No <b>'.$data->details->signprcno.'</b>
                        </td>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            Validity <b>'.$data->details->signvalidity.'</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            PTR. No <b>'.$data->details->signptrno.'</b>
                        </td>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            Date Issued <b>'.$data->details->signdateissued.'</b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            Issued at <b>'.$data->details->signplaceissued.'</b>
                        </td>
                        <td align="left" width="50%" style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                            TIN <b>'.$data->details->signtin.'</b>
                        </td>
                    </tr>
                </table>';

                $incharge = isset($data->details->incharge) ? $data->details->incharge->fullname : '';
                $box4 = '<table width="100%" cellspacing="0" cellpadding="1">
                <tr>
                    <td align="left" width="100%" style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="60%" style="border-left: 1px solid black; font-size: 6px;padding:15px">
                            
                    &nbsp;<br>&nbsp;&nbsp;'.config('constants.checkbox.unchecked').' &nbsp;PROFESSIONAL MECHANICAL ENGINEER
                    </td>
                    <td align="left" width="40%" style="border-right: 1px solid black; font-size: 6px">
                    &nbsp;<br>&nbsp;&nbsp;'.config('constants.checkbox.unchecked').' &nbsp;MECHANICAL ENGINEER
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black;">
                        
                    </td>
                </tr>
                <tr>
                    <td align="left" width="10%" style="border-left: 1px solid black;">
                        
                    </td>
                    <td align="center" width="80%" style="border-bottom: 1px solid black;">
                        <b>'.$incharge.'</b>
                    </td>
                    <td align="left" width="10%" style="border-right: 1px solid black;">
                        
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                        (Signed and Sealed Over Printed Name)
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" height="15" style="border-left: 1px solid black; border-right: 1px solid black; font-size: 8px">
                        Date __________________
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Address: <b>'.$data->details->inchargenaddress.'</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        PRC. No <b>'.$data->details->inchargeprcregno.'</b>
                    </td>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Validity <b>'.$data->details->inchargevalidity.'</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        PTR. No <b>'.$data->details->inchargeptrno.'</b>
                    </td>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Date Issued <b>'.$data->details->inchargedateissued.'</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Issued at <b>'.$data->details->inchargeplaceissued.'</b>
                    </td>
                    <td align="left" width="50%" style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        TIN <b>'.$data->details->inchargetin.'</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50%">
                        
                    </td>
                </tr>
            </table>';

            $applicantConsultant = isset($data->details->applicantConsultant) ? $data->details->applicantConsultant->fullname : '';
            $box5 = '<table width="100%" cellspacing="0" cellpadding="1">
                <tr>
                    <td align="left" width="100%" style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                        <b>BUILDING OWNER</b>
                    </td>
                </tr>
                <tr>
                    <td width="100%" style="border-left: 1px solid black; border-right: 1px solid black;">
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; ">
                        
                    </td>
                </tr>
                <tr>
                    <td align="left" width="10%" style="border-left: 1px solid black;">
                        
                    </td>
                    <td align="center" width="80%" style="border-bottom: 1px solid black;">
                        <b>'.$applicantConsultant.'</b>
                    </td>
                    <td align="left" width="10%" style="border-right: 1px solid black;">

                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                        (Signed and Sealed Over Printed Name)
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" height="15" style="border-left: 1px solid black; border-right: 1px solid black; font-size: 8px">
                        Date __________________
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Address: <b>'.$data->details->applicantaddress.'</b>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        C.T.C. No. 
                    </td>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Date Issued 
                    </td>
                    <td align="left" width="40%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Place Issued
                    </td>
                </tr>
                <tr>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->applicant_comtaxcert.'</b>
                    </td>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->applicant_date_issued.'</b>
                    </td>
                    <td align="left" width="40%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->applicant_place_issued.'</b>
                    </td>
                </tr>
            </table>';

            $applicantConsultant = isset($data->details->applicantConsultant) ? $data->details->applicantConsultant->fullname : '';
            $box6 = '<table width="100%" cellspacing="0" cellpadding="1">
                <tr>
                    <td align="left" width="100%" style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                        WITH MY CONSENT: <b>LOT OWNER</b>
                    </td>
                </tr>
                <tr>
                    <td width="100%" style="border-left: 1px solid black; border-right: 1px solid black;">
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; ">
                        
                    </td>
                </tr>
                <tr>
                    <td align="left" width="10%" style="border-left: 1px solid black;">
                        
                    </td>
                    <td align="center" width="80%" style="border-bottom: 1px solid black;">
                        <b>'.$applicantConsultant.'</b>
                    </td>
                    <td align="left" width="10%" style="border-right: 1px solid black;">
                                
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 8px">
                        (Signed and Sealed Over Printed Name)
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%" height="15" style="border-left: 1px solid black; border-right: 1px solid black; font-size: 8px">
                        Date __________________
                    </td>
                </tr>
                <tr>
                    <td align="left" width="100%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                    </td>
                </tr>
                <tr>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        C.T.C. No.
                    </td>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Date Issued
                    </td>
                    <td align="left" width="40%" style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px">
                        Place Issued
                    </td>
                </tr>
                <tr>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->ownerctcno.'</b>
                    </td>
                    <td align="left" width="30%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->owner_date_issued.'</b>
                    </td>
                    <td align="left" width="40%" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 8px">
                        <b>'.$data->details->ownerplaceissued.'</b>
                    </td>
                </tr>
            </table>';
    
        PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell(90, 0, $box3, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, $box4, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(95, $cell_height,'BOX 5',0,0,'l');
        PDF::Cell(5, $cell_height,' ', '',0,'l');
        PDF::Cell(0, $cell_height,'BOX 6', 0,0,'l');
        PDF::ln();

        PDF::MultiCell(90, 0, $box5, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, "", 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(90, 0, $box6, 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();
        PDF::setCellPaddings(1,0,1,0);
        // // end

        // old codes
        // PDF::SetFont('helvetica','B',$font_size);
        // PDF::Cell(95, $cell_height,'BOX 3',0,0,'l');
        // PDF::Cell(5, $cell_height,' ', '',0,'l');
        // PDF::Cell(0, $cell_height,'BOX 4', 0,0,'l');
        // PDF::ln();

        
        // PDF::SetFont('helvetica','B',6);
        // PDF::Cell(95, $cell_height,'DESIGN PROFESSIONAL, PLANS AND SPECIFICATIONS',1,0,'l');
        // PDF::Cell(5, $cell_height,' ', 'LR',0,'l');
        // PDF::Cell(90, $cell_height,'SUPERVISOR IN-CHARGE OF MECHANICAL WORKS',1,0,'l');
        // PDF::ln();

        // PDF::SetFont('helvetica','B', 6);
        // PDF::Cell(95, $cell_height,'','LR',0,'l');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::writeHTMLCell(5,$cell_height,'','','<div style="font-size:2pt">&nbsp;</div>'.config('constants.checkbox.unchecked'),'L');
        // PDF::Cell(50, $cell_height,'PROFESSIONAL MECHANICAL ENGINEER', 0,0,'l');
        // PDF::writeHTMLCell(5,$cell_height,'','','<div style="font-size:2pt">&nbsp;</div>'.config('constants.checkbox.unchecked'));
        // PDF::Cell(0, $cell_height,'MECHANICAL ENGINEER', 'R',0,'l');
        // PDF::ln();

        // PDF::Cell(95, $cell_height,'','LR',0,'l');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(90, $cell_height,'','LR',0,'l');
        // PDF::ln();

        // PDF::Cell(15, $cell_height,'','L',0,'l');
        // PDF::MultiCell(65, 3, '<b>'.$data->details->consultant->fullname.'</b>', 'B', 'C', 0, 0, '', '', true, 0, true);
        // PDF::Cell(15, $cell_height,'','R',0,'l');

        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        // PDF::ln();

        // PDF::SetFont('helvetica','', 6);
        // PDF::Cell(95, 3,'PROFESSIONAL MECHANICAL ENGINEER','LR',0,'C');
        // PDF::SetFont('helvetica','', 6);
        // PDF::Cell(5, 3,' ', 0,0,'l');
        // PDF::Cell(12.5, 3,'','L',0,'l');
        // PDF::MultiCell(65, 3, '<b>'.$data->details->incharge->fullname.'</b>', 'B', 'C', 0, 0, '', '', true, 0, true);
        // // PDF::Cell(65, 3,$data->details->incharge->fullname,'B',0,'C');
        // PDF::Cell(12.5, 3,'','R',0,'l');
        // PDF::ln();

        // PDF::Cell(95, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        // PDF::Cell(5, 3,' ', 0,0,'l');
        // PDF::Cell(90, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        // PDF::ln();

        // PDF::Cell(95, 3,'Date ___________________','LR',0,'C');
        // PDF::Cell(5, 3,' ', 0,0,'l');
        // PDF::Cell(90, 3,'Date ___________________','LR',0,'C');
        // PDF::ln();

        // PDF::MultiCell(95, $cell_height, '<b>'.$data->details->consultant->current_address.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::MultiCell(90, $cell_height, '<b>'.$data->details->incharge->current_address.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::ln();
        // PDF::MultiCell(47.5, $cell_height, 'PRC. No: <b>'.$data->details->consultant->prc_no.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::MultiCell(47.5, $cell_height, 'Validity <b>'.$data->details->consultant->prc_validity.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::MultiCell(45, $cell_height, 'PRC. No: <b>'.$data->details->incharge->prc_no.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::MultiCell(45, $cell_height, 'Validity <b>'.$data->details->incharge->prc_validity.'</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        // PDF::ln();

        // PDF::Cell(47.5, $cell_height,'PTR. No '.$data->details->consultant->ptr_no, 1 ,0,'L');
        // PDF::Cell(47.5, $cell_height,'Date Issued '.$data->details->consultant->ptr_date_issued, 1 ,0,'L');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(45, $cell_height,'PTR. No '.$data->details->incharge->ptr_no, 1 ,0,'L');
        // PDF::Cell(45, $cell_height,'Date Issued '.$data->details->incharge->ptr_date_issued, 1 ,0,'L');
        // PDF::ln();

        // PDF::Cell(47.5, $cell_height,'Issued at '.$data->details->consultant->ptr_issued_at, 1 ,0,'L');
        // PDF::Cell(47.5, $cell_height,'TIN '.$data->details->consultant->tin_no, 1 ,0,'L');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(45, $cell_height,'Issued at '.$data->details->incharge->ptr_issued_at, 1 ,0,'L');
        // PDF::Cell(45, $cell_height,'TIN '.$data->details->incharge->tin_no, 1 ,0,'L');
        // PDF::ln();
        
        // PDF::SetFont('helvetica','B',$font_size);
        // PDF::Cell(95, $cell_height,'BOX 5',0,0,'l');
        // PDF::Cell(5, $cell_height,' ', '',0,'l');
        // PDF::Cell(0, $cell_height,'BOX 6', 0,0,'l');
        // PDF::ln();

        // PDF::SetFont('helvetica','', 6);
        // PDF::Cell(95, $cell_height,'BUILDING OWNER','LTR',0,'l');
        // PDF::Cell(5, $cell_height,' ', 'LR',0,'l');
        // PDF::Cell(90, $cell_height,'WITH MY CONCENT: LOT OWNER','LTR',0,'l');
        // PDF::ln();

        // PDF::Cell(95, $cell_height,' ','LR',0,'l');
        // PDF::Cell(5, $cell_height,' ', '',0,'l');
        // PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        // PDF::ln();

        // PDF::Cell(95, $cell_height,' ','LR',0,'l');
        // PDF::Cell(5, $cell_height,' ', '',0,'l');
        // PDF::Cell(0, $cell_height,' ', 'LR',0,'l');
        // PDF::ln();

        // PDF::Cell(15, $cell_height,'','L',0,'l');
        // PDF::Cell(65, $cell_height,$data->details->applicantConsultant->fullname,'B',0,'C');
        // PDF::Cell(15, $cell_height,'','R',0,'l');
        // PDF::Cell(5, $cell_height,' ', '',0,'l');
        // PDF::Cell(12.5, $cell_height,'','L',0,'l');
        // PDF::Cell(65, $cell_height,$data->details->owner->fullname,'B',0,'C');
        // PDF::Cell(12.5, $cell_height,'','R',0,'l');
        // PDF::ln();

        // PDF::Cell(95, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        // PDF::Cell(5, 3,' ', 0,0,'l');
        // PDF::Cell(90, 3,'(Signed and/or Sealed Over Printed Name)','LR',0,'C');
        // PDF::ln();

        // PDF::Cell(95, 3,'Date ___________________','LR',0,'C');
        // PDF::Cell(5, 3,' ', 0,0,'l');
        // PDF::Cell(90, 3,'Date ___________________','LR',0,'C');
        // PDF::ln();

        // PDF::Cell(95, $cell_height,'Address', 1 ,0,'L');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(90, $cell_height,'Address', 1 ,0,'L');
        // PDF::ln();

        // PDF::Cell(35, $cell_height,'C.T.C NO. '.$data->details->taxDetails('applicantConsultant')->or_no, 1 ,0,'L');
        // PDF::Cell(30, $cell_height,'Date Issued '.$data->details->taxDetails('applicantConsultant')->created_at, 1 ,0,'L');
        // PDF::Cell(30, $cell_height,'Place Issued '.$data->details->taxDetails('applicantConsultant')->ctc_place_of_issuance, 1 ,0,'L');
        // PDF::Cell(5, $cell_height,' ', 0,0,'l');
        // PDF::Cell(30, $cell_height,'C.T.C NO. '.$data->details->taxDetails('owner')->or_no, 1 ,0,'L');
        // PDF::Cell(30, $cell_height,'Date Issued '.$data->details->taxDetails('owner')->created_at, 1 ,0,'L');
        // PDF::Cell(30, $cell_height,'Place Issued '.$data->details->taxDetails('owner')->ctc_place_of_issuance, 1 ,0,'L');
        // PDF::ln();

        // page 2
        PDF::AddPage('P', 'cm', array(8.5, 13), true, 'UTF-8', false);

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'TO BE ACCOMPLISHED BY THE PROCESSING AND EVALUATION DIVISION',0,0,'L');
        PDF::ln();
        PDF::Cell(0, $cell_height,'BOX 7',0,0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(95, $cell_height,'RECEIVED BY:', 1,0,'L');
        PDF::Cell(95, $cell_height,'DATE:','TRB',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'FIVE (5) SETS OF MECHANICAL DOCUMENTS', 'LR', 0,'C');
        PDF::ln();
        PDF::Cell(0, $cell_height,' ','LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(5, $cell_height, ' ', 'L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',config('constants.checkbox.unchecked'));
        PDF::Cell(100, $cell_height,'MECHANICAL PLANS AND SPECIFICATIONS', 0,0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',config('constants.checkbox.unchecked'));
        PDF::Cell(0, $cell_height,'COST ESTIMATES', 'R',0,'L');
        PDF::ln();

        PDF::Cell(5, $cell_height,'', 'L',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',config('constants.checkbox.unchecked'));
        PDF::Cell(100, $cell_height,'BILL OF MATERIALS', ' ',0,'L');
        PDF::writeHTMLCell(5,$cell_height,'','',config('constants.checkbox.unchecked'));
        PDF::Cell(28, $cell_height,'OTHERS (SPECIFY)', 0,0,'L');
        PDF::Cell(35, $cell_height,'', 'B',0,'L');
        PDF::Cell(0, $cell_height,' ', 'R',0,'L');
        PDF::ln();
        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();
        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'BOX 8', 'TB',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);

        $tbl1 = '<table id="purhcase-order-print-1-table" width="100%"   cellspacing="0" cellpadding="1" border="0.5">
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="100%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            <B>PROGRESS FLOW</B>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="2" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="2" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            IN
                        </td>
                        <td colspan="2" rowspan="1" align="center" width="20%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            OUT
                        </td>
                        <td colspan="1" rowspan="2" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            PROCESSED BY
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            TIME
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            DATE
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        MECHANICAL
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        OTHERS (Specify)
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" align="left" width="35%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                        
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="10%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                        <td colspan="1" rowspan="1" align="center" width="25%" style="border-top: 0.5px solid black; border-right: 0.5px solid black; border-left: 0.5px solid black; font-size: 8px">
                            
                        </td>
                    </tr>
                </table>';
        PDF::writeHTML($tbl1, false, false, false, false, '');

        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'BOX 9', 'TB',0,'L');
        PDF::ln();

        
        PDF::Cell(0, $cell_height,' ', 'LTR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,'ACTION TAKEN:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,'PERMIT IS HEREBY ISSUED SUBJECT TO THE FOLLOWING:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, $cell_height,' ', 'LR',0,'L');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(10, $cell_height,'1.', 'L',0,'C');
        PDF::Cell(175, $cell_height,'That the proposed mechanical works shall be in accordance with the mechanical plans filed with this OFfice and in conformity with the latest', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'','L',0,'C');
        PDF::Cell(0, $cell_height,'Philippine Mechanical Code, the National Building Code and its IRR.', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'2.', 'L',0,'C');
        PDF::MultiCell(0, $cell_height, 'That prior to any mechanical installation, a duly accomplished prescribed <b>"NOTICE OF CONSTRUCTION"</b> shall be submitted to the Office', 'R', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();
        
        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(0, $cell_height,'of the Building Official', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'3.', 'L',0,'C');
        PDF::Cell(175, $cell_height,'That upon completion of the mechanical works, the licensed supervision in-charge shall submit the entry to the logbook duly signed and', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(175, $cell_height,'sealed to the building official including as-bild plans and other documents and shall also accomplish the certificate of completion stating that the', 0,0,'J');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'', 'L',0,'C');
        PDF::Cell(175, $cell_height,'mechanical works conform to the provision of the Philippine Mechanical Code, the National Building Code and its IRR.', 0,0,'L');
        PDF::Cell(5, $cell_height,'', 'R',0,'C');
        PDF::ln();

        PDF::Cell(10, $cell_height,'4.', 'L',0,'C');
        PDF::MultiCell(175, $cell_height, 'That this permit is <b>null and void</b> unless accompanied by the building permit.', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::Cell(5, $cell_height,'', 'R',0,'L');
        PDF::ln();

        PDF::Cell(10, $cell_height,'5.', 'L',0,'C');
        PDF::MultiCell(0, $cell_height, 'That a certificate of Operation shall be issued for the continuous use of mechanical installations.', 'R', 'L', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::Cell(0, 40,'', 'LR',0,'C');
        PDF::ln();
        
        PDF::SetFont('helvetica','B',$font_size);
        PDF::Cell(0, $cell_height,'PERMIT ISSUED BY:', 'LR',0,'L');
        PDF::ln();

        PDF::Cell(0, 50,'', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(55, $cell_height,'', 'L',0,'C');
        $name = isset($data->details->official) ? $data->details->official->fullname : '';
        PDF::Cell(80, $cell_height,$name, 'B',0,'C');
        PDF::Cell(55, $cell_height,'', 'R',0,'C');
        PDF::ln();

        
        PDF::Cell(0, $cell_height,'BUILDING OFFICIAL', 'LR',0,'C');
        PDF::ln();

        PDF::SetFont('helvetica','',$font_size);
        PDF::Cell(0, $cell_height,'(Signature Over Printed Name)', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(0, $cell_height,'Date __________________', 'LR',0,'C');
        PDF::ln();

        PDF::Cell(0, 20,'', 'LRB',0,'C');
        PDF::ln();
        //PDF::Output('Mechanical Permit'.'.pdf');
        $filename ='MechanicalPermit.pdf';
            //$filename =$id.$filename."electronicpermit.pdf";
            //$mpdf->Output($filename, "I");
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            // PDF::Output($filename,'I'); exit;
            $isSignVeified = 1;
            $officialid = $this->_commonmodel->getuseridbyempid($data->details->ema_building_official);
            $signType = $this->_commonmodel->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature ="";
                if(!empty($officialid->user_id)){
                $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                }
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = '219,236,371,164,2';
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
                        PDF::Image($path,80,200,50);
                    }
                }
            }
            PDF::Output($filename,"I");

    }
    public function buildingPrint($id)
    {
        $data = $this->_engjobrequest->find($id);
        PDF::SetTitle('Building Permit: '.$data->application_no.'');    
        PDF::SetMargins(10, 10, 10,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', $this->font);
        // PDF::SetProtection(array('print','modify','copy','annot-forms','fill-forms','extract','assemble','print-high'), "", "masterpassword123", 1, null);
        // Header
        $border = 0;
        $missing = '';
        $box_space = 125;
        PDF::Cell($box_space, 0, 'REPUBLIC OF THE PHILIPPINES', $border, 1, 'C', 0);
        PDF::MultiCell($box_space, 0, 'OFFICE OF THE CITY / MUNICIPAL MAYOR', $border, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell($box_space-95, 0, '', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-60, 0, 'PALAYAN', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-95, 0, '', $border, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell($box_space, 0, 'CITY / MUNCIPALITY', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::MultiCell($box_space-80, 0, 'APPLICATION NO.', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-105, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-80, 0, 'PERMIT NO.', $border, 'L', 0, 1, '', '', true, 0, true);

        // box id
        foreach (str_split($data->application_no) as $value) {
            PDF::MultiCell(5, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
        }
        PDF::MultiCell($box_space-105, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        if ($data->details->ebpa_permit_no) {
            foreach (str_split($data->details->ebpa_permit_no) as $value) {
                PDF::MultiCell(5, 0, $value, 1, 'C', 0, 0, '', '', true, 0, true);
            }
        } else {
            for ($i=0; $i < 12; $i++) { 
                PDF::MultiCell(5, 0, '', 1, 'C', 0, 0, '', '', true, 0, true);
            }
        }
        PDF::MultiCell(4, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);
        
        PDF::ln();
        PDF::MultiCell($box_space, 0, '<h3>BUILDING PERMIT</h3>', $border, 'C', 0, 1, '', '', true, 0, true);

        $application_date = Carbon::parse($data->details->ebpa_application_date);
        $issued_date = Carbon::parse($data->details->ebpa_issued_date);
        // dd($issued_date->year);
        PDF::MultiCell($box_space-75, 0, ($application_date->year === -1) ? '':$application_date->toFormattedDateString(), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-105, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-75, 0, ($issued_date->year === -1)? '':$issued_date->toFormattedDateString(), 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell($box_space-75, 0, 'DATE OF APPLICATION', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-105, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell($box_space-75, 0, 'DATE ISSUED', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::ln();
        PDF::MultiCell($box_space, 0, $data->details->appTypeCheck('Renewal').' RENEWAL &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$data->details->appTypeCheck('Original').' ORIGINAL', $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::setCellPaddings(2,0,1,0);
        PDF::SetFont('Helvetica', '', $this->font-0.5);
        PDF::MultiCell(0, 0, '<b>BOX 1.(TO BE ACCOMPLISHED BY THE OWNER/APPLICANT)</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(15, 0, 'OWNER', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'LAST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'FIRST NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'MIDDLE NAME', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'TAX ACCT. NO.', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(15, 0, '', 'LB', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>'.$data->applicant->rpo_custom_last_name.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>'.$data->applicant->rpo_first_name.'</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '<b>'.substr($data->applicant->rpo_middle_name, 0, 1).'.</b>', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '<b>'.$data->details->ebpa_tax_acct_no.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'FOR CONSTRUCTION OWNED BY AN ENTERPRIZE', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'FORM OF OWNERSHIP', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, 'MAIN ECONOMIC ACTIVITY/KIND BUSSINESS', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(50, 0, '', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, '<b>'.$data->details->ebpa_form_of_own.'</b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, '<b>'.$data->details->ebpa_economic_act.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell($box_space, 10, 'ADDRESS <br><b>'.$data->current_address.'<b>', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell($box_space, 10, 'LOCATION OF CONSTRUCTION. <br><b>'.$data->details->ebpa_location.'<b>', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        // $check = (strcmp($val->ebs_description,$data->details->bldg_scope) == 0)? '/': '&nbsp;';
        $scopeDefault = ['New Construction','Addition','Repair','Renovation','Demolition'];
        
        $other_scope_check = (!in_array($data->details->bldg_scope, $scopeDefault))?config('constants.checkbox.checked'):config('constants.checkbox.unchecked');
        PDF::MultiCell(35, 0, 'SCOPE OF WORK', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, $data->details->bldgScopeCheck('New Construction').' NEW CONSTRUCTION', 'T', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, $other_scope_check.' OTHERS', 'TR', 'L', 0, 1, '', '', true, 0, true);

        $other_scope = (!in_array($data->details->bldg_scope, $scopeDefault))? $data->details->bldg_scope . ' OF '.$data->details->ebpa_scope_remarks: '';
        PDF::MultiCell(35, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, $data->details->bldgScopeCheck('Addition').' ADDITION '.$data->details->bldgRemarks('Addition'), 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '<b>'.$other_scope.'</b>', 'R', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(35, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0,  $data->details->bldgScopeCheck('Repair').' REPAIR '.$data->details->bldgRemarks('Repair'), 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0,  $data->details->bldgScopeCheck('Renovation').' RENOVATION '.$data->details->bldgRemarks('Renovation'), 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, '', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35, 0, '', 'BL', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0,  $data->details->bldgScopeCheck('Demolition').' DEMOLITION OF '.$data->details->bldgRemarks('Demolition'), 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, 0, 'NUMBER OF UNITS: <b>'.$data->details->no_of_units.'</b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);

        PDF::SetFont('Helvetica', '', $this->font-0.5 );
        $defaultResidential = ['Single','Duplex','Rowhouse (accessoria)'];
        $html = '<b>RESIDENTIAL</b><br>
        '.$data->details->bldgSubtypeCheck('Single').' SINGLE <br>
        '.$data->details->bldgSubtypeCheck('Duplex').' DUPLEX <br>
        '.$data->details->bldgSubtypeCheck('Rowhouse (accessoria)').' ROWHOUSE (ACCESSORIA) <br>
        '.$data->details->bldgSubtypeOtherCheck('Residential',$defaultResidential).' OTHERS (SPECIFY) <b>'.$data->details->bldgSubtypeOther('Residential').'</b>
        ';
        PDF::MultiCell(60, 0, '<b>USE OR TYPE OF OCCUPANCY:</b> <br><br>'.$html, 'L', 'L', 0, 0, '', '', true, 0, true);
        $defaultOthers = ['Factory / Plant','Repair Shop / Machine Shop','Refinery','Printing Press','Warehouse'];
        $html = '
        <b>OTHERS (SPECIFY)</b><br>
        '.$data->details->bldgSubtypeCheck('Factory / Plant').' FACTORY/PLANT <br>
        '.$data->details->bldgSubtypeCheck('Repair Shop / Machine Shop').' REPAIRS SHOP, MACHINE SHOP <br>
        '.$data->details->bldgSubtypeCheck('Refinery').' REFINERY <br>
        '.$data->details->bldgSubtypeCheck('Printing Press').' PRINTING PRESS <br>
        '.$data->details->bldgSubtypeCheck('Warehouse').' WAREHOUSE <br>
        '.$data->details->bldgSubtypeOtherCheck('Others',$defaultOthers).' OTHERS (SPECIFY) <b>'.$data->details->bldgSubtypeOther('Others').'</b>
        ';
        PDF::MultiCell(65, 0, '<br>'.$html, 'R', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(65, 0, '&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;', 'B', 'L', 0, 1, '', '', true, 0, true);

        $defaultCommercial = ['Bank','Store','Hotel / Motel','Office Condo / Business Office Bldg','Restaurant','Shop(E.g Dress Shop. Tailoring Shop, Barber Shop, Etc.)','Gasoline Station','Market','Dormitory or Lodging House'];
        $html = '
        <br><b>COMMERCIAL</b><br>
        '.$data->details->bldgSubtypeCheck('Bank').' BANK <br>
        '.$data->details->bldgSubtypeCheck('Store').' STORE <br>
        '.$data->details->bldgSubtypeCheck('Hotel / Motel').' HOTEL/MOTEL <br>
        '.$data->details->bldgSubtypeCheck('Office Condo / Business Office Bldg').' OFFICE CONDOMINIUM/BUSINESS OFFICE BUILDING <br>
        '.$data->details->bldgSubtypeCheck('Restaurant').' RESTAURANT, ETC <br>
        '.$data->details->bldgSubtypeCheck('Shop(E.g Dress Shop. Tailoring Shop, Barber Shop, Etc.)').' SHOP (e.g. DRESS SHOP, TAILORING SHOP, BARBER SHOP, ETC ) <br>
        '.$data->details->bldgSubtypeCheck('Gasoline Station').' GASOLINE STATION <br>
        '.$data->details->bldgSubtypeCheck('Market').' MARKET <br>
        '.$data->details->bldgSubtypeCheck('Dormitory or Lodging House').' DORMITORY OR OTHER LODGING HOUSE <br>
        '.$data->details->bldgSubtypeOtherCheck('Commercial',$defaultCommercial).' OTHERS (SPECIFY) <b>'.$data->details->bldgSubtypeOther('Commercial').'</b>
        ';
        PDF::MultiCell(95, 0, '<br>'.$html, 'L', 'L', 0, 0, '', '', true, 0, true);
        $defaultOthers = ['School','Church And Other Religious Structures','Hospital Or Similar Structures','Welfare And Charitable Structures','Theather'];
        $html = '
        <b>INSTITUTIONAL</b><br>
        '.$data->details->bldgSubtypeCheck('School').' SCHOOL <br>
        '.$data->details->bldgSubtypeCheck('Church And Other Religious Structures').' CHURCH AND OTHER RELIGIOUS STRUCTURES <br>
        '.$data->details->bldgSubtypeCheck('Hospital Or Similar Structures').' HOSPITAL OR SIMILAR STRUCTURES <br>
        '.$data->details->bldgSubtypeCheck('Welfare And Charitable Structures').' WELFARE AND CHARITABLE STRUCTURES <br>
        '.$data->details->bldgSubtypeCheck('Theather').' THEATER <br>
        '.$data->details->bldgSubtypeOtherCheck('Institutional',$defaultOthers).' OTHERS (SPECIFY) <b>'.$data->details->bldgSubtypeOther('Institutional').'</b>
        ';
        PDF::MultiCell(0, 0, '<br>'.$html, 'R', 'L', 0, 1, '', '', true, 0, true);
        
        $defaultAgri = ['Barn(s) Poultry House(s) Etc.','Grain Mill','Hospital Or Similar Structures','Welfare And Charitable Structures','Theather'];
        $html = '
        <br><b>AGRICULTURAL</b><br>
        '.$data->details->bldgSubtypeCheck('Barn(s) Poultry House(s) Etc.').' BARN(S) POULTRY HOUSE(S), ETC. <br>
        '.$data->details->bldgSubtypeCheck('Grain Mill').' GRAIN MILL <br>
        '.$data->details->bldgSubtypeOtherCheck('Agricultural',$defaultAgri).' OTHERS (SPECIFY) <b>'.$data->details->bldgSubtypeOther('Agricultural').'</b>
        ';
        PDF::MultiCell(95, 0, '', 'L', 'L', 0, 0, '', '', true, 0, true);//space
        PDF::MultiCell(0, 0, '<br>'.$html, 'R', 'L', 0, 1, '', '', true, 0, true);

        $defaultParks = ['Parks, Plaza, Monuments, Pools, Plant Boxes, Etc','Sidewalks, Promenades, Terraces, Landposts, Electric Poles, Telephone Poles, Etc.','Outdoor Ads Signboards, Etc','Fence Enclosure'];
        $html = '
        <br><b>STREET FURNITURES, LANDSCAPING & SIGNBOARDS</b><br>
        '.$data->details->bldgSubtypeCheck('Parks, Plaza, Monuments, Pools, Plant Boxes, Etc').' PARKS, PLAZA, MONUMENTS, POOLS, PLANT BOXES, ETC. <br>
        '.$data->details->bldgSubtypeCheck('Sidewalks, Promenades, Terraces, Landposts, Electric Poles, Telephone Poles, Etc.').' SIDEWALKS, PROMENADES, TERRACES, LANDPOSTS, ELECTRIC POLES, TELEPHONE POLES ETC. <br>
        '.$data->details->bldgSubtypeCheck('Outdoor Ads Signboards, Etc').' OUTDOOR ADS SIGNBOARDS ETC. <br>
        '.$data->details->bldgSubtypeCheck('Fence Enclosure').' FENCE ENCLOSURE : <br>
        ';
        // '.$data->details->bldgSubtypeOtherCheck('Street, Furnitures, Landscaping and Signboards',$defaultParks).' SPECIFY <b>'.$data->details->bldgSubtypeOther('Street, Furnitures, Landscaping and Signboards').'</b>
        PDF::MultiCell(140, 0, '<br>'.$html, 'L', 'L', 0, 0, '', '', true, 0, true);

        $defaultOthers = [];
        $html = '
        <b>OTHER CONSTRUCTION</b><br>
        '.$data->details->bldgSubtypeOtherCheck('Other Construction',$defaultOthers).' SPECIFY <b>'.$data->details->bldgSubtypeOther('Other Construction').'</b>
        ';
        PDF::MultiCell(0, 20, '<br>'.$html, 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 2.(TO BE ACCOMPLISHED BY THE RECEIVING & RECORDING SECTION)</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, 'BLDG. DOCUMENTS (FIVE SETS EACH)', 'LTR', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, 'SITE DEVELOPMENT AND LOCATION PLAN', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'ELECTRICAL PLANS & SPECIFICATIONS', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, 'ARCHITECTURAL PLAN & SPECIFICATIONS', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'MECHANICAL PLANS & SPECIFICATIONS', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, 'STRUCTURAL DESIGN & COMPUTATIONS', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'LOGBOOK (1 COPY)', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, 'SANITARY/PLUMBING PLANS & SPECIFICATIONS', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'OTHERS (SPECIFY) ________________________', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '<b>BOX 3.(TO BE ACCOMPLISHED BY THE BUILDING OFFICIAL)</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        $note = '
        <b>ACTION TAKEN:</b><br>
        PERMIT IS HEREBY GRANTED SUBJECT TO THE FOLLOWING CONDITIONS:
        <ol style="text-align:justify">
            <li>THAT THE PROPOSED CONSTRUCTION/ ADDITION/ REPAIR/ RENOVATION/ INSTALLATION ETC. SHALL BE IN CONFORMITY WITH THE NATIONAL BUILDING CODE (P.D. 1096) AND ITS CORRESPONDING IMPLEMENTING RULES AND REGULATIONS.</li>
            <li>THAT A DULY LICENSED ARCHITECT/CIVIL ENGINEER HAS BEEN ENGAGED TO PREPARE PLANS AND SPECIFICATIONS AND TO UNDERTAKE THE SUPERVISION/INSPECTION OF THE CONSTRUCTION OF THE PROJECT.</li>
            <li>THAT A CERTIFICATE OF COMPLETION DULY SIGNED AND SEALED BY THE DESIGNING ARCHITECT/ENGINEER AND THE ARCHITECT/ENGINEER IN-CHARGED OF THE CONSTRUCTION SHALL BE SUBMITTED NOT LATER THAN SEVEN (7) DAYS AFTER COMPLETION OF THE CONSTRUCTION OF THE PROJECT</li>
            <li>THAT A "CERTIFICATE OF OCCUPANCY" SHALL BE SECURED PRIOR TO ACTUAL OCCUPANCY OF THE BUILDING</li>
        </ol>
        NOTE:
        <span style="text-indent: 30px">THIS PERMIT MAY BE CANCELLED OR REVOKED PURSUANT TO SECTIONS 305 & 306 OF THE "NATIONAL BUILDING CODE"</span>
        ';
        PDF::MultiCell(125, 0, $note, 'LT', 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 17, '', 'TR', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(125, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        $name = isset($data->details->official) ? $data->details->official->fullname : '';
        PDF::MultiCell(50, 0, $name, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(125, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'BUILDING OFFICIAL', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(125, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 10, '', 'R', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(125, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(125, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(5, 0, '', 0, 'J', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'DATE', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'R', 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 20, '', 'LRB', 'J', 0, 1, '', '', true, 0, true);

        $isSignVeified = 1;
        $officialid = $this->_commonmodel->getuseridbyempid($data->details->ebpa_bldg_official_name);
        $signature ="";
        if(!empty($officialid->user_id)){
         $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
        }
        $path =  public_path().'/uploads/e-signature/'.$signature;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        if($isSignVeified==1 && $signType==1){
                    // Apply E-Signature
                    if(!empty($signature) && File::exists($path)){
                        PDF::Image($path,150,230,50);
                    }
                }

        PDF::MultiCell(0, 23, 'To be accomplished in five copies, one each for applicant (original), Assessor, National Statistics Office, Building Official, Fire Department', 0, 'J', 0, 1, '', '', true, 0, true);

        // nso box
        $box_space = 140;
        PDF::MultiCell(0, 0, 'DO NOT FILL-UP (NSO USE ONLY)', 0, 'L', 0, 1, $box_space, 7.5, true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(10, 10, ' ', 1, 'L', 0, 1, $box_space+50, '', true);
        // nso box end

        PDF::AddPage();

        $rowheight =35;
        $box_space = $box_space -10;
        PDF::MultiCell(0, 0, '<b>BOX 3A (TO BE ACCOMPLISHED BY THE DESIGNING ARCHITECT/CIVIL ENGINEER IN PRINT)</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        $html = '
        <p>TOTAL ESTIMATED COST</p>
        <table>
            <tr>
                <td>BUILDING</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_bldg_est_cost,2).'</b></td>
            </tr>
            <tr>
                <td>ELECTRICAL</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_elec_est_cost,2).'</b></td>
            </tr>
            <tr>
                <td>MECHANICAL</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_mech_est_cost,2).'</b></td>
            </tr>
            <tr>
                <td>PLUMBING</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_plum_est_cost,2).'</b></td>
            </tr>
            <tr>
                <td>OTHER</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_other_est_cost,2).'</b></td>
            </tr>
            <tr>
                <td>TOTAL COST</td>
                <td>PHP <b>'.number_format($data->details->fees->ebfd_total_est_cost,2).'</b></td>
            </tr>
        </table>
        ';
        PDF::MultiCell(45, $rowheight, $html, 1, 'L', 0, 0, '', '', true, 0, true);
        $html = '
        <p>COST OF EQUIPMENT INSTALLED</p>
        <p>PHP <b>'.number_format($data->details->fees->ebfd_equip_cost_1,2).'</b></p>
        <p>PHP <b>'.number_format($data->details->fees->ebfd_equip_cost_2,2).'</b></p>
        <p>PHP <b>'.number_format($data->details->fees->ebfd_equip_cost_3,2).'</b></p>
        ';
        PDF::MultiCell(35, $rowheight, $html, 1, 'L', 0, 0, '', '', true, 0, true);
        $html = '
        <table style="padding:2pt 0">
            <tr>
                <td colspan="2">NUMBER OF STOREYS </td>
                <td> <b>'.$data->details->fees->ebfd_no_of_storey.'</b></td>
            </tr>
            <tr>
                <td colspan="2">TOTAL FLOOR AREA</td>
                <td><b>'.$data->details->fees->ebfd_floor_area.'</b></td>
            </tr>
            <tr>
                <td colspan="2">PROPOSED DATE OF CONSTRUCTION</td>
                <td><b>'.$data->details->fees->ebfd_construction_date.'</b></td>
            </tr>
            <tr>
                <td colspan="2">EXPECTED DATE OF COMPLETION</td>
                <td><b>'.$data->details->fees->ebfd_construction_date.'</b></td>
            </tr>
            <tr>
                <td colspan="2">MATERIAL OF COST</td>
                <td><b>'.$data->details->fees->ebfd_mats_const.'</b></td>
            </tr>
        </table>
        ';
        PDF::MultiCell(50, $rowheight, $html, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell($box_space, 0, '<b>BOX 4 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)</b>', 1, 'L', 0, 1, '', '', true, 0, true);
        // dd($data->details->zoning);
        $html = '
        <table border="1" style="padding:2pt">
            <tr>
                <td colspan="5">ASSESSED FEES</td>
            </tr>
            <tr>
                <td></td>
                <td>AMOUNT DUE</td>
                <td>ASSESSED BY</td>
                <td>O.R. NUMBER</td>
                <td>DATE PAID</td>
            </tr>
            <tr>
                <td style="text-align:left">LAND USE/ ZONING</td>
                <td>'.number_format($data->details->asses_fees->ebaf_zoning_amount,2).'</td>
                <td>'.$data->details->preparedBy('zoning').'</td>
                <td>'.$data->details->asses_fees->ebaf_zoning_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_zoning_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">LINE AND GRADE</td>
                <td>'.number_format($data->details->asses_fees->ebaf_linegrade_amount,2).'</td>
                <td>'.$data->details->preparedBy('linegrade').'</td>
                <td>'.$data->details->asses_fees->ebaf_linegrade_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_linegrade_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">BUILDING</td>
                <td>'.number_format($data->details->asses_fees->ebaf_bldg_amount,2).'</td>
                <td>'.$data->details->preparedBy('building').'</td>
                <td>'.$data->details->asses_fees->ebaf_bldg_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_bldg_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">PLUMBING</td>
                <td>'.number_format($data->details->asses_fees->ebaf_plum_amount,2).'</td>
                <td>'.$data->details->preparedBy('plumbing').'</td>
                <td>'.$data->details->asses_fees->ebaf_plum_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_plum_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">ELECTRICAL</td>
                <td>'.number_format($data->details->asses_fees->ebaf_elec_amount,2).'</td>
                <td>'.$data->details->preparedBy('electrical').'</td>
                <td>'.$data->details->asses_fees->ebaf_elec_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_elec_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">MECHANICAL</td>
                <td>'.number_format($data->details->asses_fees->ebaf_mech_amount,2).'</td>
                <td>'.$data->details->preparedBy('mechanical').'</td>
                <td>'.$data->details->asses_fees->ebaf_mech_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_mech_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">OTHERS</td>
                <td>'.number_format($data->details->asses_fees->ebaf_others_amount,2).'</td>
                <td>'.$data->details->preparedBy('others').'</td>
                <td>'.$data->details->asses_fees->ebaf_others_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_others_date_paid.'</td>
            </tr>
            <tr>
                <td style="text-align:left">TOTAL</td>
                <td>'.number_format($data->details->asses_fees->ebaf_total_amount,2).'</td>
                <td>'.$data->details->preparedBy('total').'</td>
                <td>'.$data->details->asses_fees->ebaf_total_or_no.'</td>
                <td>'.$data->details->asses_fees->ebaf_total_date_paid.'</td>
            </tr>
        </table>
        ';
        PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell($box_space, 0, $html, 1, 'C', 0, 1, '', '', true, 0, true);
        PDF::setCellPaddings(2,0,1,0);

        PDF::MultiCell($box_space, 0, '<b>BOX 5 (TO BE ACCOMPLISHED BY THE DIVISION/SECTION CONCERNED)</b>', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::SetFont('Helvetica', '', $this->font-0.5 );
        PDF::MultiCell(0, 0, 'REVIEWED: CHIEF, PROCESSING SECTION', 'L', 'L', 0, 1, '', '', true, 0, true);

        $html = '
        <table border="1" style="padding:3pt">
            <tr>
                <td colspan="9">PROGRESS FLOW</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="2" style="text-align:left">NOTED BY: <br>CHIEF, PROCESSING DIVISION/SECTION</td>
                <td colspan="2">IN</td>
                <td colspan="2">OUT</td>
                <td colspan="1" rowspan="2">ACTION REMARKS</td>
                <td colspan="1" rowspan="2">PROCESSED BY</td>
            </tr>
            <tr>
                <td>TIME</td>
                <td>DATE</td>
                <td>TIME</td>
                <td>DATE</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        ';
        // PDF::SetFont('Helvetica', '', $this->font );
        PDF::setCellPaddings(0,0,0,0);
        PDF::MultiCell(0, 0, $html, 'L', 'C', 0, 1, '', '', true, 0, true);

        PDF::setCellPaddings(2,0,1,0);
        PDF::MultiCell(0, 0, '<p>WE HEREBY AFFIX OUR HANDS SIGNIFYING OUR CONFORMITY TO THE INFORMATION HEREIN ABOVE SETHFORTH</p>', 'LR', 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'LR', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, '<b>BOX 6 </b>', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>BOX 8 </b>', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(65, 0, 'ARCHITECTURE AND CIVIL ENGINEER', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'PRC REG NO', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'SIGNATURE', 'LTR', 'L', 0, 1, '', '', true, 0, true);
        // dd($data->details->consultant);
        PDF::MultiCell(65, 0, 'SIGNED AND SEALED PLANS & SPECIFICATION', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>'.$data->details->fees->ebfd_sign_prc_reg_no.'<b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'LR', 'L', 0, 1, '', '', true, 0, true);

        $consultant = isset($data->details->consultant) ? $data->details->consultant->fullname : '';
        PDF::MultiCell(90, 0, 'PRINT NAME <br> <b>'.$consultant.'<b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$data->applicant->fullname.'<b><br> APPLICANT', 'LBR', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 6, 'ADDRESS <br><b>'.$data->details->fees->ebfd_sign_address_house_lot_no.'<b>', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 6, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 6, 'COMMUNITY TAX CERTIFICATE', 'LTR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 6, 'DATE ISSUED ', 'LTR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 6, 'PLACE ISSUED ', 'LTR', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, '', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '', 'LBR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '', 'LBR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'LBR', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 7, 'PTR NO. <b>'.$data->details->fees->ebfd_sign_ptr_no.'<b>', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 7, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 7, '<b>'.$data->details->fees->ebfd_applicant_comtaxcert.'<b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 7, '<b>'.$data->details->fees->ebfd_applicant_date_issued.'<b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 7, '<b>'.$data->details->fees->ebfd_applicant_place_issued.'<b>', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, 'SIGNATURE <br>&nbsp;', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '&nbsp;<br>&nbsp; ', 'R', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 0, '<b>BOX 7 </b><br>', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>WITH MY CONSENT<br>BOX 9 (TO BE ACCOMPLISHED BY LOT OWNER)</b>', 'R', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(65, 0, 'ARCHITECTURE / CIVIL ENGINEER ', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'PRC REG NO', 'LTR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'TCT/OCT NO.', 'LTR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(65, 0, 'IN-CHARGE OF CONSTRUCTION', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>'.$data->details->fees->ebfd_incharge_prc_reg_no.'<b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$data->details->fees->ebfd_consent_tctoct_no.'<b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);

        $incharge = isset($data->details->incharge) ? $data->details->incharge->fullname : '';
        $owner = isset($data->details->owner) ? $data->details->owner->fullname : '';
        PDF::MultiCell(90, 0, 'PRINT NAME <br><b>'.$incharge.'<b>', 'LBR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'PRINT NAME OF LOT OWNER<br> <b>'.$owner.'<b>', 'LBR', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, 10, 'ADDRESS <br><b>'.$data->details->fees->ebfd_sign_address_house_lot_no.'<b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 10, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 10, 'ADDRESS <br><b>'.$data->details->fees->ebpa_address_house_lotno.'<b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(30, 0, 'PTR NO <br><b>'.$data->details->fees->ebfd_incharge_ptr_no.'<b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'DATE ISSUED <br><b>'.$data->details->fees->ebfd_incharge_ptr_date_issued.'<b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'PLACE ISSUED <br><b>'.$data->details->fees->ebfd_incharge_ptr_place_issued.'<b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'COMMUNITY TAX CERT. <br><b>'.$data->details->fees->ebfd_consent_comtaxcert.'<b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(60, 0, 'SIGNATURE <br>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, 'TAN <br><b>'.$data->details->fees->ebfd_incharge_tan.'<b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '&nbsp;<br>&nbsp;', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'SIGNATURE <br>', 1, 'L', 0, 1, '', '', true, 0, true);
        // nso box
        $box_space = 140;
        PDF::MultiCell(0, 7, 'DO NOT FILL-UP (NSO USE ONLY)', 1, 'C', 0, 1, $box_space, 10, true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+5, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+10, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+15, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 1, 0, $box_space+20, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+25, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+30, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+35, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+40, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+45, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 0, $box_space+50, '', true);
        PDF::MultiCell(5, 5, ' ', 1, 'L', 0, 1, $box_space+55, '', true);
        // nso box end
        //PDF::Output('Building Permit'.'.pdf');
		
        $filename ='BuildingPermit.pdf';
            //$filename =$id.$filename."electronicpermit.pdf";
            //$mpdf->Output($filename, "I");
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            // PDF::Output($filename,'I'); exit;
            $isSignVeified = 1;
            $officialid = $this->_commonmodel->getuseridbyempid($data->details->ebpa_bldg_official_name);
            $signType = $this->_commonmodel->getSettingData('sign_settings');
            if(!$signType || !$isSignVeified){
                PDF::Output($folder.$filename);
            }else{
                $signature ="";
                if(!empty($officialid->user_id)){
                $signature = $this->_commonmodel->getuserSignature($officialid->user_id);
                }
                $path =  public_path().'/uploads/e-signature/'.$signature;
                if($isSignVeified==1 && $signType==2){
                    $arrData['signerXyPage'] = '421,185,529,131,1';
                    if(!empty($signature) && File::exists($path)){
                        // Apply Digital Signature
                        PDF::Output($folder.$filename,'F');
                        $arrData['signaturePath'] = $signature;
                        $arrData['filename'] = $filename;
                        return $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                }
            }
            PDF::Output($filename,"I");
    }
    public function engFees($id)
    {
        
        return $eng_fees = EngJobRequest::select([
            '*', 
            'eng_job_request_fees_details.ejr_id',
            'eng_job_request_fees_details.fees_description',
            'eng_job_request_fees_details.tax_amount as taxAmount'])
        ->leftJoin('eng_job_request_fees_details', function($join)
        {
            $join->on('eng_job_request_fees_details.ejr_id', '=', 'eng_job_requests.id');
        })
        ->where('eng_job_request_fees_details.ejr_id', $id)
        ->where('eng_job_request_fees_details.tax_amount', '>', 0)
        ->get();
    }

    public function getJobRequestDetails($id)
    {
        return $eng_fees = EngJobRequest::select([
            '*',
            'rpo_custom_last_name',
            'rpo_first_name',
            'rpo_middle_name',
            'hr_employees.fullname as employeeFullName',
            'users.name as userfullname',
            'hr_designations.description as hrDesignation'])
        ->leftJoin('clients', function($join)
        {
            $join->on('eng_job_requests.client_id', '=', 'clients.id');
        })
        ->leftJoin('users', function($join)
        {
            $join->on('eng_job_requests.ejr_opd_created_by', '=', 'users.id');
        })
        ->leftJoin('hr_employees', function($join)
        {
            $join->on('users.id', '=', 'hr_employees.user_id');
        })
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_employees.hr_designation_id', '=', 'hr_designations.id');
        })
        ->where('eng_job_requests.id', $id)
        ->first();
    }
}