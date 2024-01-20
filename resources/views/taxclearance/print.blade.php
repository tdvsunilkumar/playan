<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @page {sheet-size: A4; margin-top: 12mm; margin-bottom: 12mm; margin-left: 10mm; margin-right: 10mm; margin-header: 0mm; margin-footer: 0mm;}
        @page rotated {
            size: landscape;
        }
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif;font-size: 12px; }
        p {margin: 0px}
        table td, table th { border: 1px solid black; padding: 5px; }
        .page-wrap { width: 800px; margin: 100px auto;}
        table { border-collapse: collapse; }
        h2, p, h1{margin: 0px; padding: 0px;}
    </style>
    <title>TAX Clearance</title>
</head>

<body>    
    <watermarkimage src="{{url('/assets/images/rptCertLog/Logo.png')}}" alpha="0.08" position="auto" size="150,150" />
    <div width="100%" style="text-align:center; border:0px solid black; padding-bottom:10px; padding-top: 0px;">
        <div style="text-align:center; padding:0px; border:0px solid black; width: auto; float: left;">
            <p style="">Republic of the Philippines</p>
            <p style=""><strong>CITY OF PALAYAN</strong></p>
            <p style="">PROVINCE OF NUEVA ECIJA</p>
            <p><strong>&nbsp;</strong></p>
            <p style="font-size:14px"><strong>OFFICE OF THE CITY TREASURER</strong></p>
        </div>
    </div>

    <div style="clear:both"></div>

    <div width="100%" style="text-align:center; border:0px solid black; padding-bottom:10px; padding-top: 0px;">
        <div style="text-align:right; padding:0px; border:0px solid black; width: auto; float: left;">
            <p style="">Date: <span style="border-bottom: 1px solid black;">{{(isset($taxes->rptc_date))?date("m/d/Y",strtotime($taxes->rptc_date)):''}}</span></p>
            <p style="">Control No: <span style="border-bottom: 1px solid black;">{{(isset($taxes->rptc_control_no))?$taxes->rptc_control_no:''}}</span></p>
        </div>
    </div>

    <div width="100%" style="text-align:center; border:0px solid black; padding-bottom:10px; padding-top: 0px;">
        <div style="text-align:center; padding:0px; border:0px solid black; width: auto; float: left;">
            <p style="font-size:16px;"><strong><span style="border-bottom: 1px solid black;">TAX CLEARANCE</span></strong></p>
        </div>
    </div>

    <div style="width:100%; float:left;">
        <div style="width: 100%; float: left; margin-left:0px; margin-top: 30px;">
            <p style="font-size: 13px;"><strong>TO WHOM IT MAY CONCERN:</strong></p>
        </div>

        <div style="float: left; font-size: 13px; margin-left:70px; line-height: 25px;">
            <p style="word-spacing: 10px;">THIS IS TO CERTIFY that according to the records of this office.</p>
        </div>

        <div style="float: left; font-size: 13px; line-height: 20px; width:60%">
            <p style="border-bottom: 1px solid black; text-align:center;">{{(isset($taxes->owner->standard_name))?strtoupper($taxes->owner->standard_name):''}}<p>
        </div>

        <div style="float: left; font-size: 13px; line-height: 20px; width:15%">
            <p style="">of Barangay<p>
        </div>

        <div style="float: left; font-size: 13px; line-height: 20px; width:25%">
            <p style="border-bottom: 1px solid black; text-align:center;">{{(isset($taxes->taxCertDetails[0]->realProperty->barangay->brgy_name))?strtoupper($taxes->taxCertDetails[0]->realProperty->barangay->brgy_name):''}}<p>
        </div>

        <div style="float: left; font-size: 13px; line-height: 20px; margin-top:5px;">
            <p style="">has real property/properties declared for taxation in this City to wit:<p>
        </div>

        <div style="clear:both;"></div>

        <table style="width: 100%; margin-top:30px;border-top: none;border: 1px solid #000;">
            <tr>
                <td style="text-align:center; font-weight:bold;">PIN NO.</td>
                <td style="text-align:center; font-weight:bold;">ARP</td>
                <td style="text-align:center; font-weight:bold;">DECLARED OWNER</td>
                <td style="text-align:center; font-weight:bold;">LOCATION</td>
                <td style="text-align:center; font-weight:bold;">ASSESSED VALUE</td>
            </tr>
            @foreach($taxesDetails as $details)
            <tr>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{$details->rp_pin_declaration_no}}</td>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">
                    @php
                $array = explode("-",$details->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                @endphp
                {{ implode("-",$array) }}
            </td>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{strtoupper($details->customername)}}</td>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{strtoupper($details->loc_group_brgy_no)}}</td>
                <td style="text-align:right; padding-bottom: 8px;border:none;">{{Helper::decimal_format($details->assessedValue)}}</td>
            </tr>
            @endforeach
        </table>

        <div style="float: left; font-size: 13px; margin-top: 20px; width: 100%;">
            <div style="float: left; width:65%; word-spacing:5px; margin-left:70px;">THIS is to certify that real property  taxes has been paid up to </div>
            <div style="float: left; font-weight: bold; width:15%; border-bottom: 1px solid black; text-align:center; float:left;">-{{$taxesDetails->max('endYear')}}</div>
            <div style="text-align:left; float:left;">under the</div>
            <div style="text-align:left; float:left;">following Official Receipts No.</div>
        </div>

        <div style="clear:both;"></div>

        <table style="width: 100%; margin-top:30px;border: 1px solid #000;">
            <tr>
                <td colspan="2" style="text-align:center; font-weight:bold;">&nbsp;</td>
                <td colspan="3" style="text-align:center; font-weight:bold;">AMOUNT</td>
            </tr>
            <tr>
                <td style="text-align:center; font-weight:bold;">OR NO.</td>
                <td style="text-align:center; font-weight:bold;">O.R. DATE</td>
                <td style="text-align:center; font-weight:bold;">BASIC</td>
                <td style="text-align:center; font-weight:bold;">SEF</td>
                <td style="text-align:center; font-weight:bold;">SOCIALIZE HOUSING</td>
            </tr>
            @foreach($taxesDetails as $details)
            <tr>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{ $details->or_no }}</td>
                <td style="text-align:center; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{ date("m/d/Y",strtotime($details->cashier_or_date)) }}</td>
                <td style="text-align:right; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{ Helper::decimal_format($details->baiscAmount) }}</td>
                <td style="text-align:right; padding-bottom: 8px;border:none;border-right: 1px solid #000;">{{ Helper::decimal_format($details->sefAmount) }}</td>
                <td style="text-align:right; padding-bottom: 8px;border:none;">{{ Helper::decimal_format($details->shAmount) }}</td>
            </tr>
            @endforeach
           

        </table>

        <div style="float: left; font-size: 13px; margin-top: 20px; width: 100%;">
            <div style="float: left; width:35%; word-spacing:5px; margin-left:70px;">ISSUED upon the request of </div>
            <div style="float: left; font-weight: bold; width:55%; border-bottom: 1px solid black; text-align:center; float:left;">{{(isset($taxes->requester->standard_name))?strtoupper($taxes->requester->standard_name):''}}</div>
            <div style="text-align:left; float:left;">for reference purposes.</div>
        </div>

        <div style="width: 100%; float:left; margin-top: 40px;">
            <div style="width: 50%; float: left;">
                <p style="font-size: 13px;">Prepared by:</p>
            </div>
            <div style="width: 50%; float: left;">
                <p style="font-size: 13px;">Checked by:</p>
            </div>
        </div>
    </div>
    <div style="width: 100%; float:left; margin-top: 50px;">
        <div style="width:50%; float:left;">
            <div style="">
                <p style="font-weight: bold; text-align: center;">
                    <strong>{{(isset($PreparedBy->fullname))?strtoupper($PreparedBy->fullname):''}}</strong>
                </p>
                <p style="text-align:center; font-style:italic;">
                    <small>{{(isset($taxes->rptc_prepared_position))?strtoupper($taxes->rptc_prepared_position):''}}</small>
                </p>
            </div>
        </div>

        <div style="width:50%; float:left; text-align:right;">
            <div style="">
               
                <p style="font-weight: bold; text-align: center;">
                    <strong>{{(isset($CheckedBy->fullname))?strtoupper($CheckedBy->fullname):''}}</strong>
                </p>
                <p style="text-align:center; font-style:italic;">
                    <small>{{(isset($taxes->rptc_checked_position))?strtoupper($taxes->rptc_checked_position):''}}</small>
                </p>
            </div>
        </div>
    </div>

    <div style="clear:both;"></div>

    <div style="font-weight:bold; float: left; margin-top: 20px;">
        <span style="border-bottom: 1px solid black;">ANY MATERIAL ALTERATION OR ERASURE SHALL INVALIDATE THIS CLEARANCE.</span>
    </div>

    <div style="float: left; font-size: 13px; line-height: 22px; width: 150px; text-align:left; padding-right:10px; margin-top:20px;">
        <p>Certification Fee:</p>
        <p>Paid Under OR No.:</p>
        <p>Issued On:</p>
        <p>Palayan City, Nueva Ecija</p>
    </div>

    <div style="float: left; font-size: 13px; width: 100px; line-height: 20px; text-align:center;">
        <p style="font-weight: bold; border-bottom: 1px solid black;">{{(isset($taxes->rptc_or_amount))?Helper::decimal_format($taxes->rptc_or_amount):''}}<p>
        <p style="font-weight: bold; border-bottom: 1px solid black;">{{(isset($taxes->rptc_or_no))?$taxes->rptc_or_no:''}}</p>
        <p style="font-weight: bold; border-bottom: 1px solid black;">{{(isset($taxes->rptc_date))?date("m/d/Y",strtotime($taxes->rptc_date)):''}}</p>
    </div>
</body>
</html>







