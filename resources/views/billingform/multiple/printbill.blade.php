<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Montserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        * { margin: 0; padding: 0; }
        @page {sheet-size: A4; margin-top: 12mm; margin-bottom: 12mm; margin-left: 12mm; margin-right: 12mm; margin-header: 0mm;margin-footer: 0mm;}
       
        body { font-family: 'merchantcopydoublesize', sans-serif; font-size: 12px; }
        table td, table th { border: 1px solid black; padding: 5px; vertical-align: top;}
        p {margin: 0px; padding: 0px}
        p .indent{text-indent: 25px; margin-left: 25px;}
        h4, h1, h2 {margin: 0px; padding: 0px}
        textarea { border: 0; font-size: 14px; font-family: 'Montserrat', sans-serif;overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        .mb-2{margin-bottom: 2mm}
        .bg{background-color: red;}
        .bg1{background-color: blue;}
        .indent{text-indent: 70px;}
        .address {
            white-space: pre-wrap;
        }
    </style>
  <title>Multiple Property Billing
</title>
</head>

<body>
    <div style="">
        <table width="100%">
            <tr>
                <td style="text-align:center; border:none; padding:0px; text-transform: uppercase;">
                    Republic of the Philippines
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="100%" style="text-align:center; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-bottom: 20px;">
                    <p style="font-family:merchantcopydoublesize;">CITY TREASURER'S OFFICE</p>
                    <p><strong>Land Tax Division</strong></p>
                    <!-- <p style="font-family:merchantcopydoublesize;margin-top: 30px;">STATEMENT OF REAL PROPERTY TAX COMPUTATION</p> -->
                    <!-- <p><strong>RIVISION ORO VENTURE</strong></p> -->
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="100%" style="text-align:center; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-bottom: 50px;">
                    <p style="font-family:merchantcopydoublesize;margin-top: 30px;">STATEMENT OF REAL PROPERTY TAX COMPUTATION</p>
                    <!-- <p><strong>RIVISION ORO VENTURE</strong></p> -->
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="10%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     <strong>
                        Bill To:
                     </strong>
                </td>
                <td width="70%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   @if(isset($owner) && is_object($owner))
                                        {{$owner->standard_name}}
                                    @endif
                             
                </td>
                <td width="20%" rowspan="3" style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;padding-right: 20px;">
                   @if($topNo=='')
                    @else
                    <?php 
                    $qrcode="";
                    $qrcode=QrCode::size(60)->generate(''.$topNo->transaction_no);
                     $code = (string)$qrcode;
                     echo substr($code,38);
                    ?>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>
                        Address:
                    </strong>
                </td>
                <td width="65%"   style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;font-size: 12px;">
                     @if(isset($owner) && is_object($owner))
                                        {{$owner->standard_address}}
                                    @endif
                            
                </td>
                
            </tr>
            <tr>
                <td width="10%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>
                        Date:
                    </strong>
                </td>
                <td width="70%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    
                         @if(isset($topNo) && is_object($topNo))
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $topNo->created_at)->format('m/d/Y h:i A') }}
                    @endif
                </td>
                
            </tr>
             <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong>  Contact No.:  </strong>

                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                      @if(isset($owner) && is_object($owner))
                        {{$owner->p_telephone_no}}
                    @endif
                            
                </td>
                <td width="20%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                    <strong> TOP: 
                        @if(isset($topNo) && is_object($topNo))
                        {{$topNo->transaction_no}}
                        @endif</strong>
                </td>
            </tr>
            <tr>
                <td width="15%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong> Control No.:</strong>
                </td>
                <td width="65%" style="text-align:left; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                     @if(isset($topNo) && is_object($topNo))
                        {{$topNo->cb_control_no}}
                          @endif
                </td>
                <td width="20%"  style="text-align:right; border:none; padding-top:0px; border-bottom:none; font-family:merchantcopydoublesize;">
                   <strong>
                    Page.: 
                   1 of {{$pageNo}}
                        </strong>
                </td>
            </tr>
        </table>

        <table width="100%" style="border:none;">
            <tr>
                 <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 5%;">
                    NO.
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 28%;">
                    DECLARED OWNER LOCATION
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 10%;">
                    T.D. NO.
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 10%;">
                    ASSESSED<br>VALUE
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 15%;">
                    YEAR<br>COVERED
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 10%;">
                    TAX<br>AMOUNT
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 12%;">
                    PENALTY<br>(DISCOUNT)
                </td>
                <td style="padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;width: 10%;">
                    TOTAL<br>TAX DUE
                </td>
            </tr>
            <?php
            $totalBasic = 0;
            $totalSef   = 0;
            $totalSh    = 0;
            $totalBasicPenality   = 0;
            $totalSefPenality   = 0;
            $totalShPenality   = 0;
            $totalBasicDisc   = 0;
            $totalSefDisc   = 0;
            $totalShDisc   = 0;
            $toalBasicDue = 0;
            $totalSefDue = 0;
            $totalShDue = 0;
            $countNo=0;
            ?>
            @foreach($multipleBillingDetails as $bill)
            @php 

            $countNo=$countNo+1;
            @endphp
            <tr>
                <td style="border: none; border-right: 1px solid black; border-left: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: center;">
                    {{$countNo}}
                </td>
                <td style="border: none; border-right: 1px solid black; border-left: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: left;">
                    <p>{{(isset($bill->full_name))?$bill->full_name:''}}<br/>{{(isset($bill->rptProperty->barangay->brgy_code))?$bill->rptProperty->barangay->brgy_code.'-'.$bill->rptProperty->barangay->brgy_name:''}}</p>
                </td>
                <!-- <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; vertical-align: top; ">
                    <table width="100%"> 
                        <tr>
                            <td width="100%" colspan="2" style="padding:0px; border:none; text-align:right;">00048</td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding:0px; border:none; text-align:left;">01</td>
                            <td width="50%" style="padding:0px; border:none; text-align:right;">049</td>
                        </tr>
                    </table>
                </td> -->
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; vertical-align: top; ">
                    <p>{{(isset($bill->rp_tax_declaration_no))?$bill->rp_tax_declaration_no:''}}</p>
                </td>
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: right;"><p>{{(isset($bill->cbd_assessed_value))?Helper::decimal_format($bill->cbd_assessed_value):''}}</td>
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; vertical-align: top; ">
                    <table width="100%">
                        <tr>
                            <td width="50%" style="padding:0px; padding-right: 10px; border:none; text-align:left;"><p>{{(isset($bill->cbd_covered_year))?$bill->cbd_covered_year:'1900'}}</p></td>
                            <td width="50%" style="padding:0px; padding-left: 10px; border:none; text-align:right;">Basic</td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="2" style="padding:0px; border:none; text-align:right; font-style: italic;">SEF</td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="2" style="padding:0px; border:none; text-align:right; font-style: italic;">Housing</td>
                        </tr>
                    </table>
                </td>
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: right;">
                    <p>{{Helper::decimal_format($bill->basicAmountOnly)}}</p>
                    <p>{{Helper::decimal_format($bill->sefAmountOnly)}}</p>
                    <p>{{Helper::decimal_format($bill->shAmountOnly)}}</p>
                </td>
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: right;">
                    
                    <p>{{($bill->basicPenaltyOnly > 0)?Helper::decimal_format($bill->basicPenaltyOnly):(($bill->basicDiscOnly > 0)?'('.$bill->basicDiscOnly.')':00.00)}}</p>

                    <p>{{($bill->sefPenaltyOnly > 0)?Helper::decimal_format($bill->sefPenaltyOnly):(($bill->sefDiscOnly > 0)?'('.$bill->sefDiscOnly.')':00.00)}}</p>
                    
                    <p>{{($bill->shPenaltyOnly > 0)?Helper::decimal_format($bill->shPenaltyOnly):(($bill->shDiscOnly > 0)?'('.$bill->shDiscOnly.')':00.00)}}</p>
                    
                </td>
                <td style="border: none; border-right: 1px solid black; padding-top:5px; padding-bottom:5px; border-bottom: 1px solid black; text-align: right;">
                    <p>{{Helper::decimal_format($bill->basicAmount)}}</p>
                    <p>{{Helper::decimal_format($bill->sefAmount)}}</p>
                    <p>{{Helper::decimal_format($bill->shAmount)}}</p>
                    <p style="font-weight:bold;">{{Helper::decimal_format($bill->totalDueNew)}}</p>
                </td>
            </tr>
             @php
            $totalBasic += $bill->basicAmountOnly;
            $totalSef += $bill->sefAmountOnly;
            $totalSh += $bill->shAmountOnly;
            $totalBasicPenality += $bill->basicPenaltyOnly;
            $totalSefPenality += $bill->sefPenaltyOnly;
            $totalShPenality += $bill->shPenaltyOnly;

            $totalBasicDisc += $bill->basicDiscOnly;
            $totalSefDisc += $bill->sefDiscOnly;
            $totalShDisc += $bill->shDiscOnly;

            $toalBasicDue += $bill->basicAmount;
            $totalSefDue += $bill->sefAmount;
            $totalShDue += $bill->shAmount;
            @endphp
            @endforeach
            

            <tr>
                <td colspan="8" style="border: none; padding-top:5px; padding-bottom:5px; text-align: left;">
                    <p style="font-style: italic;"><strong>Note:</strong> Penalty up to November 2022</p>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="border: none; padding-top:5px; padding-bottom:5px; text-align: left;">
                   
                </td>
                
                <td colspan="3" style="border: none; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <P>TOTAL BASIC:</P>
                    <P>TOTAL SEF:</P>
                    <P>TOTAL HOUSING:</P>
                </td>
                

                <td style="border: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p>{{Helper::decimal_format($totalBasic)}}</p>
                    <p>{{Helper::decimal_format($totalSef)}}</p>
                    <p>{{Helper::decimal_format($totalSh)}}</p>
                </td>
                <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    @php
                    if($totalBasicPenality > 0 && $totalBasicDisc > 0){
                        $basicPenDisc = Helper::decimal_format($totalBasicPenality).'('.Helper::decimal_format($totalBasicDisc).')';
                    }else if($totalBasicPenality > 0 && $totalBasicDisc == 0){
                        $basicPenDisc = Helper::decimal_format($totalBasicPenality);
                    }else if($totalBasicPenality == 0 && $totalBasicDisc > 0){
                        $basicPenDisc = '('.Helper::decimal_format($totalBasicDisc).')';
                    }else{
                        $basicPenDisc = 00.00;
                    }
                    @endphp
                    <p>{{$basicPenDisc}}</p>
                    @php
                    if($totalSefPenality > 0 && $totalSefDisc > 0){
                        $sefPenDisc = Helper::decimal_format($totalSefPenality).'('.Helper::decimal_format($totalSefDisc).')';
                    }else if($totalSefPenality > 0 && $totalSefDisc == 0){
                        $sefPenDisc = Helper::decimal_format($totalSefPenality);
                    }else if($totalSefPenality == 0 && $totalSefDisc > 0){
                        $sefPenDisc = '('.Helper::decimal_format($totalSefDisc).')';
                    }else{
                        $sefPenDisc = 00.00;
                    }
                    @endphp
                    <p>{{$sefPenDisc}}</p>
                    @php
                    if($totalShPenality > 0 && $totalShDisc > 0){
                        $shPenDisc = Helper::decimal_format($totalShPenality).'('.Helper::decimal_format($totalShDisc).')';
                    }else if($totalShPenality > 0 && $totalShDisc == 0){
                        $shPenDisc = Helper::decimal_format($totalShPenality);
                    }else if($totalShPenality == 0 && $totalShDisc > 0){
                        $shPenDisc = '('.Helper::decimal_format($totalShDisc).')';
                    }else{
                        $shPenDisc = 00.00;
                    }
                    @endphp
                    <p>{{$shPenDisc}}</p>
                </td>
                <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black; border-top: 1px solid black; padding-top:5px; padding-bottom:0px; text-align: right;">
                    <p>{{Helper::decimal_format($toalBasicDue)}}</p>
                    <p>{{Helper::decimal_format($totalSefDue)}}</p>
                    <p>{{Helper::decimal_format($totalShDue)}}</p>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="border: none; padding-top:5px; padding-bottom:5px; text-align: left;">
                     
                    <p>Processed as of 
                        @if(isset($topNo) && is_object($topNo))
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $topNo->created_at)->format('m/d/Y') }}
                    @endif
                    </p>
                </td>
                <td colspan="3" style="border: none; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <P>GRAND TOTAL:</P>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p>{{Helper::decimal_format($totalBasic+$totalSef+$totalSh)}}</p>
                </td>
                <td style="border: none; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    @php
                    $totalPenalty = $totalBasicPenality+$totalSefPenality+$totalShPenality;
                    $totalDisco   = $totalBasicDisc+$totalSefDisc+$totalShDisc;
                    if($totalPenalty > 0 && $totalDisco > 0){
                        $totalPenDisc = Helper::decimal_format($totalPenalty).'('.Helper::decimal_format($totalDisco).')';
                    }else if($totalPenalty > 0 && $totalDisco == 0){
                        $totalPenDisc = Helper::decimal_format($totalPenalty);
                    }else if($totalPenalty == 0 && $totalDisco > 0){
                        $totalPenDisc = '('.Helper::decimal_format($totalDisco).')';
                    }else{
                        $totalPenDisc = 00.00;
                    }
                    @endphp
                    <p>{{$totalPenDisc}}</p>
                </td>
                <td style="border: none; border-right: 1px solid black; border-bottom: 1px solid black; padding-top:5px; padding-bottom:5px; text-align: right;">
                    <p style="font-weight:bold;">{{Helper::decimal_format($toalBasicDue+$totalSefDue+$totalShDue)}}</p>
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














