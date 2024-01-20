<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }

/*        @page {margin: 0mm; margin-header: 0mm;margin-footer: 0mm;}*/

        @page {sheet-size: A4; margin-top: 12mm; margin-bottom: 12mm; margin-left: 12mm; margin-right: 12mm; margin-header: 0mm;margin-footer: 0mm;}

        body { font-family: 'merchantcopy', sans-serif; font-size: 12px; line-height: normal;}
        table td, table th { border: 1px solid black; padding: 5px; vertical-align: top; line-height: normal;}
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4, h1, h2 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
        .indent{text-indent: 70px;}
        .useBefore::before{content:'before'; position:absolute;}
    </style>
    <title>Billing Statement</title>
</head>

<body>
    <div style="">
        <div style="text-align: center; position:relative;padding-bottom: 30px;">
            <p>PALAYAN CITY</p>
            <p>CITY TREASURER'S OFFICE</p>
            <p>Land Tax Division</p>
            <div style="text-align: right; margin-top:10px">
                <p></p>
            </div>
            <p style="padding-top:15px;">REAL PROPERTY TAX COMPUTATION</p>
        </div>
        <table width="100%">
            <tr>
                <td width="20%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     <strong>
                       OWNER/ADMINISTRATOR:
                    
                     </strong>
                </td>
                <td width="60%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  {{(isset($billDetails->rptProperty->taxpayer_name))?$billDetails->rptProperty->taxpayer_name:''}}{{(isset($billDetails->rptProperty->administrator_name))?' / '.$billDetails->rptProperty->administrator_name:''}}
                             
                </td>
                <td width="20%" rowspan="5" colspan="2" style="text-align:center; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 0px;">
                  
                    @if($topNo=='')
                    @else
                    <?php 
                    $qrcode="";
                    $qrcode=QrCode::size(100)->generate(''.$topNo->transaction_no);
                     $code = (string)$qrcode;
                     echo substr($code,38);
                    ?>
                    @endif
                   
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>
                     ADDRESS :
                    </strong>
                </td>
                <td width="65%"   style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;font-size: 12px;">
                     {{(isset($billDetails->rptProperty->property_owner_details->standard_address))?$billDetails->rptProperty->property_owner_details->standard_address:''}}
                            
                </td>
                
            </tr>
            <tr>
                <td width="10%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>Barangay :</strong>
                </td>
                <td width="70%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    {{(isset($billDetails->rptProperty->loc_group_brgy_no))?$billDetails->rptProperty->loc_group_brgy_no:$billDetails->rptProperty->loc_group_brgy_no}}
					@if($billDetails->rptProperty->propertyKindDetails->pk_code == "B")
					{{$billDetails->rptProperty->barangay_details->brgy_name}}
					@endif
					@if($billDetails->rptProperty->propertyKindDetails->pk_code == "L")
					{{$billDetails->rptProperty->barangay_details->brgy_name}}
					@endif
					
                </td>
                
            </tr>
            <tr>
                <td width="10%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>AREA/CCT NO.:</strong>
                </td>
                <td width="70%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     @if($billDetails->rptProperty->propertyKindDetails->pk_code == "L") 
					 @if(config('constants.lav_unit_measure')[$billDetails->rptProperty->landAppraisals[0]->lav_unit_measure] == 'Hectare')
                     {{(isset($billDetails->rptProperty->total_land_area))?number_format($billDetails->rptProperty->total_land_area,4):''}} {{config('constants.lav_unit_measure')[$billDetails->rptProperty->landAppraisals[0]->lav_unit_measure]}} / {{(isset($billDetails->rptProperty->rp_oct_tct_cloa_no))?$billDetails->rptProperty->rp_oct_tct_cloa_no:''}}
                     @else
					 {{(isset($billDetails->rptProperty->total_land_area))?Helper::number_format($billDetails->rptProperty->total_land_area):''}} {{config('constants.lav_unit_measure')[$billDetails->rptProperty->landAppraisals[0]->lav_unit_measure]}} / {{(isset($billDetails->rptProperty->rp_oct_tct_cloa_no))?$billDetails->rptProperty->rp_oct_tct_cloa_no:''}}
				     @endif
					 @endif
                    @if($billDetails->rptProperty->propertyKindDetails->pk_code == "B")
                    {{(isset($billDetails->rptProperty->rp_building_total_area))?number_format($billDetails->rptProperty->rp_building_total_area,3):''}} Sq. m. / {{(isset($billDetails->rptProperty->rp_building_cct_no))?$billDetails->rptProperty->rp_building_cct_no:''}}
                    @endif
                </td>
                
            </tr>
            <tr>
                <td width="10%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>LOT NO.:</strong>
                </td>
                <td width="70%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    
                    @if($billDetails->rptProperty->propertyKindDetails->pk_code == "L" || $billDetails->rptProperty->propertyKindDetails->pk_code == "B" || $billDetails->rptProperty->propertyKindDetails->pk_code == "M")
                    {{(isset($billDetails->rptProperty->rp_app_assessor_lot_no))?$billDetails->rptProperty->rp_app_assessor_lot_no:$billDetails->rptProperty->rp_app_assessor_lot_no}}
                    @endif
                </td>
                
            </tr>
             <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>TD NO.:</strong>

                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                      {{(isset($billDetails->rptProperty->rp_tax_declaration_no))?$billDetails->rp_tax_declaration_no:''}}
                            
                </td>
                <td width="15%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 5px;">
                   <strong> TOP : </strong>
                   
                </td>
                <td width="15%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: ">
                   
                   @if(isset($topNo) && is_object($topNo))
                        {{$topNo->transaction_no}}
                        @endif
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong>PIN :</strong>
                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     {{(isset($billDetails->rptProperty->rp_pin_declaration_no))?$billDetails->rptProperty->rp_pin_declaration_no:''}}
                </td>
                <td width="15%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  <strong>Date : </strong>
                </td>
                <td width="15%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  
                  {{(isset($billDetails->cb_billing_date))?date("m/d/Y",strtotime($billDetails->cb_billing_date)):''}}
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong>KIND :</strong>
                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     {{(isset($billDetails->rptProperty->propertyKindDetails->pk_code))?$billDetails->rptProperty->propertyKindDetails->pk_code:''}} CLASS : {{$class}} USE : {{$actualUse}}
                </td>
                <td width="15%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  <strong> Control No.: </strong>
                   
                </td>
                <td width="15%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  
                   @if(isset($topNo) && is_object($topNo))
                        {{$topNo->cb_control_no}}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong>TAX RATES :</strong>
                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     {{$rates->bsst_basic_rate}}% BT  {{$rates->bsst_sef_rate}}% SEF {{$rates->bsst_sh_rate}}% SH
                </td>
                <td width="15%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  <strong> TAX YEARS : </strong> 
                </td>
                <td width="15%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  {{(isset($billDetails->cb_covered_from_year))?$billDetails->cb_covered_from_year:'1900'}} TO {{(isset($billDetails->cb_covered_to_year))?$billDetails->cb_covered_to_year:'1900'}}
                </td>
                
            </tr>
            <!-- <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong>TAX YEARS :</strong>
                </td>
                <td width="55%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    {{(isset($billDetails->cb_covered_from_year))?$billDetails->cb_covered_from_year:'1900'}} TO {{(isset($billDetails->cb_covered_to_year))?$billDetails->cb_covered_to_year:'1900'}}
                </td>
                 <td width="30%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  <strong> Control No.:  @if(isset($topNo) && is_object($topNo))
                        {{$topNo->cb_control_no}}
                          @endif</strong>
                </td>
            </tr> -->
            <!-- <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                  <strong> Control No.:</strong>
                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     @if(isset($topNo) && is_object($topNo))
                        {{$topNo->cb_control_no}}
                          @endif
                </td>
               
            </tr> -->
        </table>
        <!-- <div style="width: 100%; float:left; margin-top: 25px;">
           
             <div style="width:20%; text-align:left; float:left; margin-right:5px;">
                    <p>OWNER/ADMINISTRSTOR : </p>
                    <p style="padding-bottom:10px;">ADDRESS :</p>
                    <p>LOCATION : </p>
                    <p>TD NO.: </p>
                    <p>PIN : </p>
                    <p>KIND : </p>
                    <p>TAX RATES : </p>
                    <p>TAX YEARS :</p>
                </div>
                <div style="width:50%; float:left; ">
                    <p>{{(isset($billDetails->rptProperty->taxpayer_name))?$billDetails->rptProperty->taxpayer_name:''}}</p>
                    <p>{{(isset($billDetails->rptProperty->property_owner_details->standard_address))?$billDetails->rptProperty->property_owner_details->standard_address:''}}</p>
                    <p>{{(isset($billDetails->rptProperty->loc_group_brgy_no))?$billDetails->rptProperty->loc_group_brgy_no:''}}</p>

                    <p>{{(isset($billDetails->rptProperty->barangay->brgy_code) && isset($billDetails->rptProperty->rp_td_no))?$billDetails->rptProperty->barangay->brgy_code.'-'.$billDetails->rptProperty->rp_td_no:''}}</p>
                    <p>{{(isset($billDetails->rptProperty->complete_pin))?$billDetails->rptProperty->complete_pin:''}}</p>
                    <p>{{(isset($billDetails->rptProperty->propertyKindDetails->pk_code))?$billDetails->rptProperty->propertyKindDetails->pk_code:''}} CLASS : {{$class}} USE : {{$actualUse}}</p>
                    <p>{{$rates->bsst_basic_rate}}% BT  {{$rates->bsst_sef_rate}}% SEF {{$rates->bsst_sh_rate}}% SH</p>
                    <p>{{(isset($billDetails->cb_covered_from_year))?$billDetails->cb_covered_from_year:'1900'}} TO {{(isset($billDetails->cb_covered_to_year))?$billDetails->cb_covered_to_year:'1900'}}</p>
                </div>
            <div style="width: 20%; float: right;">
                
                    <p>TD NO.: </p>
                    <p>PIN : </p>
                    <p>KIND : </p>
                    <p>TAX RATES : </p>
                    <p>TAX YEARS :</p>
                
                
            </div>
        </div> -->

        <div style="margin-top:20px;">
            <p>SIR/MADAM :</p>
            <p class="indent" style="margin-top:20px;">
                This is to inform that the real property taxe(s) due and payable for the years(s) 2024 to 2024 on Use property located in the City and ownership is stated in your name for taxation purposes (as well as subsequent years until you informed on any charges/s), is as follows:
            </p>

            <p class="" style="margin-top:5px;">
                Your last payment ref.: DR No.: {{(isset($lastPayment->or_no))?$lastPayment->or_no:''}}, {{(isset($lastPayment->cashier_or_date))?date("d/m/Y",strtotime($lastPayment->cashier_or_date)):''}}
            </p>
        </div>

        <div style="height:15px"><!-- Spacer div --></div>

        <table width="100%" style="border:none;">
            <tr>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center; vertical-align: bottom;">
                    PERIOD
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    ASSESSED<br>VALUE
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    BASIC
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    PENALTY / <br>(DISCOUNT)
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center; letter-spacing: 5px; vertical-align: bottom;">
                    SEF
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    PENALTY / <br>(DISCOUNT)
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    SOCIALIZE<br>HOUSING
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; border-left: 1px dashed black; border-right: 1px dashed black; text-align: center;">
                    PENALTY / <br>(DISCOUNT)
                </td>
            </tr>
             @php
                                    $totalDue = 0;
                                    $basicAmountTotal = 0;
                                    $basiInterst = 0;
                                    $basicDisc = 0;
                                    $sefAmount = 0;
                                    $sefInterst = 0;
                                    $sefDisc = 0;
                                    $shAmount = 0;
                                    $shInterst = 0;
                                    $shDiscount = 0;
                                    @endphp
                        @foreach($billDetailsForPrint as $bill)
                        @php
                                     $newBasicPenalty = ($bill->basicPenaltyOnly != null)?$bill->basicPenaltyOnly:0;
                                     $newBasicDiscount = ($bill->basicDiscOnly != null)?$bill->basicDiscOnly:0;
                                     $newSefPenalty = ($bill->sefPenaltyOnly != null)?$bill->sefPenaltyOnly:0;
                                     $newSefDiscount = ($bill->sefDiscOnly != null)?$bill->sefDiscOnly:0;
                                     $newShPenalty = ($bill->shPenaltyOnly != null)?$bill->shPenaltyOnly:0;
                                     $newShDiscount = ($bill->shDiscOnly != null)?$bill->shDiscOnly:0;

                                    $totalAmountDue = $bill->totalDueNew;
                                    
                                    $totalDue += $totalAmountDue;
                                    $basicAmountTotal += $bill->basicAmountOnly;
                                    $basiInterst += ($bill->basicPenaltyOnly != null)?$bill->basicPenaltyOnly:0;
                                    $basicDisc  += ($bill->basicDiscOnly != null)?$bill->basicDiscOnly:0;
                                    $sefAmount += $bill->sefAmountOnly;
                                    $sefInterst += ($bill->sefPenaltyOnly != null)?$bill->sefPenaltyOnly:0;
                                    $sefDisc += ($bill->sefDiscOnly != null)?$bill->sefDiscOnly:0;
                                    $shAmount += $bill->shAmountOnly;
                                    $shInterst += ($bill->shPenaltyOnly != null)?$bill->shPenaltyOnly:0;
                                    $shDiscount += ($bill->shDiscOnly != null)?$bill->shDiscOnly:0;
                                    @endphp
            <tr>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{($bill->sd_mode == 14)?$bill->cbd_covered_year.' - Annual':Helper::billing_quarters()[$bill->sd_mode]}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                   {{Helper::decimal_format($bill->cbd_assessed_value)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                   {{Helper::decimal_format($bill->basicAmountOnly)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{($bill->basicPenaltyOnly > 0)?Helper::decimal_format($bill->basicPenaltyOnly):(($bill->basicDiscOnly > 0)?'('.$bill->basicDiscOnly.')':00.00)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{Helper::decimal_format($bill->sefAmountOnly)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{($bill->sefPenaltyOnly > 0)?Helper::decimal_format($bill->sefPenaltyOnly):(($bill->sefDiscOnly > 0)?'('.$bill->sefDiscOnly.')':00.00)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{Helper::decimal_format($bill->shAmountOnly)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-left: 1px dashed black; border-right: 1px dashed black; text-align: center;">
                   {{($bill->shPenaltyOnly > 0)?Helper::decimal_format($bill->shPenaltyOnly):(($bill->shDiscOnly > 0)?'('.$bill->shDiscOnly.')':00.00)}}
                </td>
            </tr>
             @endforeach
            <tr>
                <td style="padding-top:10px; padding-bottom:10px; border: none; text-align: center;">
                    &nbsp;
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    TOTAL :
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{Helper::decimal_format($basicAmountTotal)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    @php
                    if($basiInterst > 0 && $basicDisc > 0){
                        $basicPenDisc = Helper::decimal_format($basiInterst).'('.Helper::decimal_format($basicDisc).')';
                    }else if($basiInterst > 0 && $basicDisc == 0){
                        $basicPenDisc = Helper::decimal_format($basiInterst);
                    }else if($basiInterst == 0 && $basicDisc > 0){
                        $basicPenDisc = '('.Helper::decimal_format($basicDisc).')';
                    }else{
                        $basicPenDisc = 00.00;
                    }
                    @endphp
                    {{$basicPenDisc}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    {{Helper::decimal_format($sefAmount)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                    @php
                    if($sefInterst > 0 && $sefDisc > 0){
                        $sefPenDisc = Helper::decimal_format($sefInterst).'('.Helper::decimal_format($sefDisc).')';
                    }else if($sefInterst > 0 && $sefDisc == 0){
                        $sefPenDisc = Helper::decimal_format($sefInterst);
                    }else if($sefInterst == 0 && $sefDisc > 0){
                        $sefPenDisc = '('.Helper::decimal_format($sefDisc).')';
                    }else{
                        $sefPenDisc = 00.00;
                    }
                    @endphp
                   {{$sefPenDisc}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-right: none; border-left: 1px dashed black; text-align: center;">
                   {{Helper::decimal_format($shAmount)}}
                </td>
                <td style="padding-top:10px; padding-bottom:10px; border-bottom: 1px solid black; border-left: 1px dashed black; border-right: 1px dashed black; text-align: center;">
                    @php
                    if($shInterst > 0 && $shDiscount > 0){
                        $shPenDisc = Helper::decimal_format($shInterst).'('.Helper::decimal_format($shDiscount).')';
                    }else if($shInterst > 0 && $shDiscount == 0){
                        $shPenDisc = Helper::decimal_format($shInterst);
                    }else if($shInterst == 0 && $shDiscount > 0){
                        $shPenDisc = '('.Helper::decimal_format($shDiscount).')';
                    }else{
                        $shPenDisc = 00.00;
                    }
                    @endphp
                    {{$shPenDisc}}
                </td>
            </tr>

        </table>

        <table width="100%" style="border:none; margin-top: 30px;">
            <tr>
                <td style="border: none; padding-top:5px; padding-bottom:5px; text-align: left;">
                    <p>&nbsp;</p>
                </td>
                
                <td colspan="3" style="border: none; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p>&nbsp;</p>
                    <P>TAX:</P>
                    <P>PENALTY:</P>
                    <P>DISCOUNT:</P>
                </td>
                
                <td style="border:none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p>BASIC</p>
                    <p>{{Helper::decimal_format($basicAmountTotal)}}</p>
                    <p>{{Helper::decimal_format($basiInterst)}}</p>
                    <p>{{Helper::decimal_format($basicDisc)}}</p>
                </td>
                <td style="border: none; border-right: 0px solid black; border-bottom: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p style="letter-spacing: 5px;">SEF</p>
                    <p>{{Helper::decimal_format($sefAmount)}}</p>
                    <p>{{Helper::decimal_format($sefInterst)}}</p>
                    <p>{{Helper::decimal_format($sefDisc)}}</p>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p>HOUSING</p>
                    <p>{{Helper::decimal_format($shAmount)}}</p>
                    <p>{{Helper::decimal_format($shInterst)}}</p>
                    <p>{{Helper::decimal_format($shDiscount)}}</p>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="border:none;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <!-- <p>PROCESSED as of {{(isset($billDetails->cb_billing_date))?date("d/m/Y",strtotime($billDetails->cb_billing_date)):''}}</p> -->
                </td>
                <td style="border: none; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <P>SUB TOTAL:</P>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p>{{Helper::decimal_format($basicAmountTotal+$basiInterst-$basicDisc)}}</p>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p>{{Helper::decimal_format($sefAmount+$sefInterst-$sefDisc)}}</p>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p style="font-weight:bold;">{{Helper::decimal_format($shAmount+$shInterst-$shDiscount)}}</p>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:none; margin-top: 30px;">
           <tr>
                <td colspan="3" style="border:none;">
                    <p>PROCESSED as of {{(isset($billDetails->cb_billing_date))?date("m/d/Y",strtotime($billDetails->cb_billing_date)):''}}</p>
                </td>
                <td style="border: none; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <P>TOTAL TAX DUE</P>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p>{{Helper::decimal_format($totalDue)}}</p>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                
                <td colspan="4" style="border: none; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <P>LESS TAX CREDIT </P>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p></p>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
             <tr>

                
                
                <td colspan="4" style="border: none; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <P>NET TAX DUE</P>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p></p>
                </td>
                <td colspan="" style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
        </table>
        <table width="100%" style="padding-top:50px;">
            <tr>
                <td width="60%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize; padding-top: 50px;padding-bottom: 20px;font-size:12px;">
                    Processed By: 
                </td>
                <td width="40%"  style="text-align:left; border:none; padding-top:50px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 20px;padding-bottom: 20px;font-size:12px;">
                  Verified By:
                </td>
            </tr>
           <tr>
                <td width="60%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize; padding-top: 0px;">
                   <strong style="font-size:12px;">
                    @if(isset($processedBy) && is_object($processedBy))
                    {{$processedBy->standard_name}}
                    @endif
                   </strong>
                </td>
                <td width="40%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 20px;">
                   <strong style="font-size:12px;">@if(isset($verified_by) && is_object($verified_by))
                    {{$verified_by->standard_name}} @endif</strong>
                  
                </td>
            </tr> 

            <tr>
                <td width="60%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize; padding-top: 0px;font-size:12px;">
                    @if(isset($processedPosition) && is_object($processedPosition))
                     {{$processedPosition->description}}
                    @endif
                </td>
                <td width="40%"  style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 20px;font-size:12px;">
                    @if(isset($RptLocality) && is_object($RptLocality))
                   {{$RptLocality->loc_treasurer_position}}
                   @endif
                  
                </td>
            </tr> 
        </table>
    </div>
</body>
</html>














