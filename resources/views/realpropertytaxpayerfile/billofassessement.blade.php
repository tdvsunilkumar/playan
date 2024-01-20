<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {margin: 0mm; margin-header: 0mm;margin-footer: 0mm;}
        body { font-family: 'Montserrat', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; }
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4, h1, h2 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
        .indent{text-indent: 70px;}
    </style>
  <title>Assessment List</title>
</head>

<body>
    <div style="">
        <table width="100%">
            <tr>
                <td width="33.33%" style="text-align:left; border:none; padding:0px;">
                    RPTOP Revised: 1989 
                </td>
                <td width="33.33%" style="text-align:center; border:none; padding:0px; text-transform: uppercase;">
                    Republic of the Philippines
                </td>
                <td width="33.33%" style="text-align:right; border:none; padding:0px;">
                    Control Number: {{ (isset($controlNumber->ntob_control_no)?$controlNumber->ntob_control_no:'') }}
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="100%" style="text-align:center; border:none; padding-top:0px; border-bottom:none;">
                    <p><strong>CITY OF PALAYAN</strong></p>
                    <p>PROVINCE OF NUEVA ECIJA</p>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="100%" style="text-align:left; border:none; padding-top:0px; border-bottom:none;">
                    <p><strong>{{ $propOwnerDetails->standard_name }}</strong></p>
                    <p>{{ $propOwnerDetails->standard_address }}</p>
                    <p>Administrator</p>
                </td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div style="width:100%; float: left; margin-bottom:10px;">
            <h2 style="text-align:center;">NOTICE OF ASSESSMENT AND TAX BILL (NATB)</h2>
            <p>Sir/Madam</p>
            <p class="indent">This is to inform you that the real property tax(es) due and payable for the year <span style="font-weight:bold; margin-left:5px; margin-right:5px; border-bottom:1px solid black;">

            {{ (isset($activeRevisionYear->rvy_revision_year))?$activeRevisionYear->rvy_revision_year:'' }}</span> on the property/properties located in the city and ownership of which is/are stated in your name for taxation purposes, as well as for subsequent years until you are informed of any change(s) is are follows:</p>
        </div>

        <table width="100%" style="border:none; font-size:11px;border-bottom: 1px solid #000;">
            <tr>
                <td style="text-align:center; padding-top:4px; padding-bottom:4px; border-right: none;">
                  <strong>A.</strong>
                </td>
                <td colspan="5" style="text-align:center; padding-top:4px; padding-bottom:4px; border-left: none; letter-spacing: 10px;">
                  <strong>NOTICE OF ASSESSMENT</strong>
                </td>
                <td style="text-align:center; padding-top:4px; padding-bottom:4px; border-right:none;">
                  <strong>B.</strong>
                </td>
                <td colspan="3" style="text-align:center; padding-top:1px; padding-bottom:1px; border-left: none; letter-spacing: 10px;">
                  <strong>TAX BILL</strong>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:center; padding-top:4px; padding-bottom:4px; word-spacing: 10px;">
                    <strong>SUMMARY OF REAL PROPERTY ASSESSMENT</strong>
                </td>
                <td colspan="4" style="text-align:center; padding-top:4px; padding-bottom:4px; word-spacing: 10px;"><strong>TAX DUES</strong></td>
            </tr>
            <tr>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>T.D. NO. /<br>ARP No.</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>PROPERTY <br>INDEX NUMBER</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>LOCATION</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>CLASSIFICATION</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>MARKET VALUE</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>ASSESSED VALUE</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>BASIC TAX</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>SEF TAX</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center;"><strong>SOCIALIZED<br> HOUSING TAX</strong></td>
                <td style="padding-top:4px; padding-bottom:4px; text-align: center; letter-spacing: 5px;"><strong>TOTAL</strong></td>
            </tr> 
              
            @foreach($rptProperties as $prop)
            <tr>
                <td style="border: none; border-right: 1px solid black; border-left: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: center;">{{ $prop->rp_tax_declaration_no }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: center;">{{ $prop->rp_pin_declaration_no }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: center;">{{ $prop->barangay_details->brgy_name }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: center;">{{ ($prop->propertyKindDetails != null && $prop->class_for_kind != null)?$prop->propertyKindDetails->pk_code.' - '. $prop->class_for_kind->pc_class_code:''}}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">{{ Helper::decimal_format( $prop->market_value_for_all_kind ) }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">{{ Helper::decimal_format( $prop->assessed_value_for_all_kind ) }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">{{ Helper::decimal_format( $prop->basic_due ) }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">{{ Helper::decimal_format( $prop->sef_due ) }}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">      {{ Helper::decimal_format( $prop->sh_due ) }}
                </td>
                <td style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; text-align: right;">{{ Helper::decimal_format( $prop->total_due ) }}</td>
            </tr>
            @endforeach
            <tr>
                <td style="border: none; border-right: 1px solid black;border-left: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
                <td style="border: none; border-right: 1px solid black;  border-bottom:1px solid black;border-right:1px solid black;">
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: none; border-right: 1px solid black; border-left: 1px solid black; padding-top:10px; padding-bottom:10px;  padding-left:30px; border-bottom:1px solid black;text-align: left;">
                    <table style="width:60%;">
                        <tr>
                            <td style="border:1px solid black; padding:5px;">* THIS SERVES AS A NOTICE OF ASSESSMENT <br> &nbsp;&nbsp;OF REAL PROPERTY PURSUANT TO SEC 223 <br>&nbsp;&nbsp;OF RA 7160.</td>
                        </tr>
                    </table>
                </td>
                
                <td colspan="2" style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; border-bottom:1px solid black; text-align: center; vertical-align: bottom;"><strong>{{(isset($RptLocality->assessor->standard_name))?$RptLocality->assessor->standard_name:''}}</strong><br><small>City Assessor</small></td>
                <td colspan="4" style="border: none; border-right: 1px solid black; padding-top:4px; padding-bottom:4px; border-bottom:1px solid black; text-align: center; vertical-align: bottom;"><strong>{{(isset($RptLocality->treasurer->standard_name))?$RptLocality->treasurer->standard_name:''}}</strong><br><small>City Treasurer</small></td>
            </tr>
            <tr><td colspan="9" style="border: none; padding-top:15px; padding-bottom:2px; text-align: left;padding-left: 30px;">
                <strong style="">NOTE:</strong>
                
                   &nbsp;&nbsp; 1.Kindly inform the Assessor's Office of any error or omission that you may have discovered in this Notice.
                </td>
            </tr>
            
            <tr>
                
                <td colspan="9" style="border: none;  padding-bottom:4px; text-align: left;padding-left: 76px;">
                    2.Please present this Notice to the Office of the Treasure when payment is made. Payments may be made every quarter in four (4) equal installment not later than <br> &nbsp;&nbsp; March 31 - (1st Quarter); June 30th - (2nd Quarter); September 30th - (3rd Quarter); and December 31st - (4th Quarter).
                </td>
            </tr>
            <tr>
                
                <td colspan="9" style="border: none;  padding-bottom:4px; text-align: left;padding-left: 76px;">
                    3.Payments for the entire year should be made not later than March 31st.
                </td>
            </tr>
            <tr>
                
                <td colspan="9" style="border: none;  padding-bottom:4px; text-align: left;padding-left: 76px;">
                    4.This Notice pertains only to current year taxes and does not include delinquencies for previous years, which are subject to a separate Notice when applicable <br> &nbsp;&nbsp; Delinquent payments are assessed with a penalty of 2% per month of delinquency, or a fraction thereof.
                </td>
            </tr>
            <!-- <tr>
                <td colspan="6" style="border: none; padding-top:15px; padding-bottom:4px; text-align: left;">
                    <ol>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </td>
            </tr> -->
        </table>
    </div>
</body>
</html>














