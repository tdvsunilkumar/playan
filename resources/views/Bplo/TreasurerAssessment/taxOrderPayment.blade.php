<style type="text/css">
    table{
        width: 100%;
    }
    .main-heading{margin-top: -50px;}
    .main-heading h4{font-size: 1rem;}
    .left-content{ float: left; }
    .right-content{ float: right; }
    .center-content{ text-align:center; }
    .left-content p, .right-content p{ margin: 0px; }
    .left-right-content tr {background: unset;}
    .left-right-content td {border: 1px solid gray;padding-left: 4px;}
    .table-heading th{ border: 0.5px solid gray; border-style: dashed; text-align: center;font-weight: lighter;}
    .table-heading td{ text-align: right;}
    .align-left{text-align: left !important; padding-bottom: 7px;}
    .year-title {font-weight: bold; text-align: left !important;padding-top: 10px;padding-bottom: 10px;border-top: 1px dashed gray;}
    .table-heading tr:nth-child(even){background: unset;}
    .table-heading tr td:first-child{float: left !important;}
    .table-heading tr{padding-bottom: 10px;}
    .sub-details{ padding-top:15px !important; border-top:1px dashed;border-bottom:1px dashed gray;}
</style>
@if($displayType=='pdf')
    <style type="text/css">
         @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif;font-size: 14px; }

        table {font-size: 10px; width: 100%;border-collapse: collapse;}
        .table-heading th {
            border: 0.1px solid gray;
            border-bottom:unset;
            border-collapse: collapse;
            padding: 5px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .right-content{
            float: right;
            padding-left: 30%;
            margin-right: 0px;
        }
        .main-heading{padding-bottom: 20px;}
        .sub-details{ border:1px dashed;border-right:unset;border-left:unset; }
    </style>
@endif
<table class="main-heading">
    <tr>
        <td  style="text-align:center;">
            <h4>PALAYAN CITY</h4>
            <h4>TREASURER'S OFFICE</h4>
            <h4>TAX ORDER OF PAYMENT</h4>
            <h4>Business Permit & License</h4>
        </td>
    </tr>
</table>
<br>

<table class="left-right-content">
    <tr>
        <td width="600px"><b>Business ID No. </b>: {{$data['busns_id_no']}}</td>
        
        <td width="250px"><b>Type Of Application </b>: <span style="text-transform: uppercase;">{{$data['app_type']}}</span></td>
        <td colspan="2" rowspan="3" width="180px" style="text-align:center;">
            <p><?php 
                $qrcode="";
                $qrcode=QrCode::size(60)->generate('Top No.:'.$data['top_trans_no']);
                 $code = (string)$qrcode;
                 echo substr($code,38);
                ?>
            </p>
            
            <p>
            <b>Top No. </b>: {{$data['top_trans_no']}}
        </p></td>
    </tr>
    <tr>
        <td><b>Business Name </b>: <span style="text-transform: uppercase;">{{$data['busn_name']}}</span></td>
        <td><b>Taxpayer </b>: <span style="text-transform: uppercase;">{{$data['ownar_name']}}</span></td>
        
    </tr>
    <tr>
        <td><b>Address </b>: <span style="text-transform: uppercase;">{{$data['address']}}</span></td>
        <td><b>Date Assessed </b>: {{$data['date_assessed']}}</td>
        
    </tr>
    <tr>
        <td rowspan="2" style="padding:0px;margin: 0px;">
            <table style="padding:0px;margin: 0px;text-align: center;">
                 <tr>
                   <td rowspan="2" style="border-top: 0x;border-left: 0px;border-bottom: 0px;"><b>Business Activity </b></td>  
                   <td colspan="2" style="border-top: 0x;border-right: 0px;border-bottom: 0px;"><b>Contact No. </b>: {{$data['mobile']}}</td>
                 </tr>
                 <tr>
                   <td style="border-right: 0px;border-bottom: 0px;"><b>Year </b>: <?php echo date("Y");?></td>
                   <td style="border-right: 0px;border-bottom: 0px;"><b>No. of Employee </b>: {{$data['total_employee']}}</td>
                 </tr>
            </table>
        </td>
        <td rowspan="2"><b>Capital Investment</b></td>
        <td colspan="2"><b>Gross Receipt</b></td>
    </tr>
    <tr>
        <td>Non Essential</td>
        <td>Essential</td>
    </tr>
    <?=$data['activityDetails']?>

    @if(!empty($data['last_amount_paid']))
    <tr >
        <td colspan="5" class="center-content">
            <p style="text-align:center;margin-bottom: 5px;">Last Amount Paid : {{$data['last_amount_paid']}}</p>
        </td>
    </tr>
    @endif
</table>
<br>
<table class="table-heading">
    <tr>
        <th style="border-bottom: 0.1px dashed gray;">TAX CODE</th>
        <th>PARTICULARS</th>
        <th>TAX AMOUNT</th>
        <th>SURCHARGE</th>
        <th>% / F.A</th>
        <th>INTEREST</th>
        <th>% / F.A</th>
        <th>TOTAL AMOUNT</th>
    </tr>
    <?=$html?>
</table>

<?php

if(isset($data['paymentSchduleHtml'])){ ?>
    <h5 style="margin-top: 20px;margin-bottom: 10px;">Payment Schedule</h5>
    <table class="table-heading" style="padding-top: 0px;">
        <tr>
            <th style="border-bottom: 0.1px dashed gray;">Mode - <?=$data['payment_mode']?></th>
            <th style="border-bottom: 0.1px dashed gray;">PAYMENT DUE DATE</th>
            <!-- <th style="border-bottom: 0.1px dashed gray;">STATUS</th> -->
            <th style="border-bottom: 0.1px dashed gray;">AMOUNT</th>
            <th style="border-bottom: 0.1px dashed gray;">INTEREST/SURCHARGE</th>
            <th style="border-bottom: 0.1px dashed gray;">TOTAL AMOUNT</th>
        </tr>
        <?=$data['paymentSchduleHtml']?>
    </table><?php
} ?>
