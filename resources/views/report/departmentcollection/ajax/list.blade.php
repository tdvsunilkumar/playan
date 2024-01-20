<thead><tr>
             <th rowspan="2" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;width: 50px;">No.</th>
             <th rowspan="2" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;width: 50px;">Tax Year</th>
             <th colspan="3" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;width: 50px;">Basic Tax</th>
             <th colspan="3" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;width: 500px;">SEF Tax</th>
             <th colspan="4" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;width: 50px;">SH Tax</th>
             <th rowspan="2" colspan="3" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;width: 50px;">Total Amount</th>
             </tr>
              <tr><th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th> 
              <th style="text-align: center;border: 1px solid;width: 500px">&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;</th>
              <th style="text-align: center;border: 1px solid;width: 500px">&nbsp;&nbsp;&nbsp;Discount&nbsp;&nbsp;&nbsp;</th>
              <th style="text-align: center;border: 1px solid;width: 500px">&nbsp;&nbsp;&nbsp;Penalty&nbsp;&nbsp;&nbsp;</th>
              <th style="text-align: center;border: 1px solid;width: 500px">Amount</th>
              <th style="text-align: center;border: 1px solid;width: 500px">Discount</th>
              <th colspan="2" style="text-align: center;border: 1px solid;width: 500px">Penalty</th></tr>
            </thead><tbody>
            	@php 
            	$i=1;
            	$toalYearWiseBasicAmount = 0;
            	$toalYearWiseBasicpenalty = 0;
            	$toalYearWiseBasicDiscount = 0;
            	$toalYearWiseSefAMount = 0;
            	$toalYearWiseSefPenalty = 0;
            	$toalYearWiseSefDiscount = 0;
            	$toalYearWiseShAmount = 0;
            	$toalYearWiseShPenalty = 0;
            	$toalYearWiseShDiscount = 0;
            	$toalYearWiseTotalDue = 0;
            	@endphp
               @foreach($yearWiseData as $yearData)
            	<tr><td style="font-size: 11px;">{{$i}}</td>
            <td  style="font-size: 12px;text-align: center;">{{ $yearData->cbd_covered_year }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicAmount) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicDiscount) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicPenalty) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefAmount) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefDiscount) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefPenalty) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shAmount) }}</td>
            <td style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shDiscount) }}</td>
            <td colspan="2" style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shPenalty) }}</td>
            @php
                $toalYearWiseBasicAmount+= $yearData->basicAmount;
            	$toalYearWiseBasicpenalty += $yearData->basicPenalty;
            	$toalYearWiseBasicDiscount += $yearData->basicDiscount;
            	$toalYearWiseSefAMount += $yearData->sefAmount;
            	$toalYearWiseSefPenalty += $yearData->sefPenalty;
            	$toalYearWiseSefDiscount += $yearData->sefDiscount;
            	$toalYearWiseShAmount += $yearData->shAmount;
            	$toalYearWiseShPenalty += $yearData->shPenalty;
            	$toalYearWiseShDiscount += $yearData->shDiscount;
            	
            $tottalDue = ($yearData->basicAmount+$yearData->basicPenalty-$yearData->basicDiscount)+($yearData->sefAmount+$yearData->sefPenalty-$yearData->sefDiscount)+($yearData->shAmount+$yearData->shPenalty-$yearData->shDiscount);
            $toalYearWiseTotalDue += $tottalDue;
            @endphp
            <td colspan="2" style="font-size: 12px;text-align: center;border-bottom: 3px solid #000;">{{ Helper::decimal_format($tottalDue) }}</td></tr>
            @php $i++; @endphp
            @endforeach
            <tr>
            <th colspan="2" style="font-size: 11px; text-align: end;">Total</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseBasicAmount) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseBasicDiscount) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseBasicpenalty) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseSefAMount) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseSefDiscount) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseSefPenalty) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseShAmount) }}</th>
            <th style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseShDiscount) }}</th>
            <th colspan="2" style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseShPenalty) }}</th>
            <th colspan="2" style="font-size: 12px;text-align: center;">{{ Helper::decimal_format($toalYearWiseTotalDue) }}</th>
            <!-- <th  style="font-size: 12px;text-align: center;"></th> -->
        </tr>
        </tbody>

            <thead><tr>
            <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">No.</th>
            <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Tax Declaration No.</th>
            <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Tax Year</th>
            
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">Basic Tax</th>
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">SEF Tax</th>
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">SH Tax</th>
             <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Total Amount</th>
             </tr>
              <tr><th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th> 
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th>
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th></tr>
            </thead><tbody>
            	@php $i=1;
            	$i=1;
            	$toalTdWiseBasicAmount = 0;
            	$toalTdWiseBasicpenalty = 0;
            	$toalTdWiseBasicDiscount = 0;
            	$toalTdWiseSefAMount = 0;
            	$toalTdWiseSefPenalty = 0;
            	$toalTdWiseSefDiscount = 0;
            	$toalTdWiseShAmount = 0;
            	$toalTdWiseShPenalty = 0;
            	$toalTdWiseShDiscount = 0;
            	$toalTdWiseTotalDue = 0; 
            	@endphp
               @foreach($tdWiseData as $yearData)
            	<tr><td style="font-size: 11px;">{{$i}}</td>
            		<td  style="font-size: 12px;">{{ $yearData->rp_tax_declaration_no }}</td>
            <td  style="font-size: 12px;">{{ $yearData->cbd_covered_year }}</td>
  
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicAmount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicDiscount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->basicPenalty) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefAmount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefDiscount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->sefPenalty) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shAmount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shDiscount) }}</td>
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($yearData->shPenalty) }}</td>
            @php
                $toalTdWiseBasicAmount+= $yearData->basicAmount;
            	$toalTdWiseBasicpenalty += $yearData->basicPenalty;
            	$toalTdWiseBasicDiscount += $yearData->basicDiscount;
            	$toalTdWiseSefAMount += $yearData->sefAmount;
            	$toalTdWiseSefPenalty += $yearData->sefPenalty;
            	$toalTdWiseSefDiscount += $yearData->sefDiscount;
            	$toalTdWiseShAmount += $yearData->shAmount;
            	$toalTdWiseShPenalty += $yearData->shPenalty;
            	$toalTdWiseShDiscount += $yearData->shDiscount;
            $tottalDue = ($yearData->basicAmount+$yearData->basicPenalty-$yearData->basicDiscount)+($yearData->sefAmount+$yearData->sefPenalty-$yearData->sefDiscount)+($yearData->shAmount+$yearData->shPenalty-$yearData->shDiscount);
            $toalTdWiseTotalDue += $tottalDue;
            @endphp
            <td style="font-size: 12px;border-bottom: 3px solid #000;">{{ Helper::decimal_format($tottalDue) }}</td></tr>
            @php $i++; @endphp
            @endforeach
            <tr>
            <th colspan="3" style="font-size: 11px; text-align: end;">Total</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseBasicAmount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseBasicDiscount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseBasicpenalty) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseSefAMount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseSefDiscount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseSefPenalty) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseShAmount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseShDiscount) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseShPenalty) }}</th>
            <th style="font-size: 12px;">{{ Helper::decimal_format($toalTdWiseTotalDue) }}</th>
            
        </tr>

            </tbody>

            <thead><tr>
            <th width="10%" style="text-align: center;border: 1px solid;font-size: 11px;">No.</th>
            <th width="20%" colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">Tax Declaration No.</th>
            <th width="20%" colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">GL Description</th>
            <th width="20%" colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">Payment Description</th>
            
             <th width="30%" colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">TAX CREDIT AMOUNT</th>
             </tr>
            </thead><tbody>
            	@php $i=1; @endphp
                @foreach($taxCreditData as $credit)
            	<tr><td style="font-size: 11px;">{{$i}}</td>
            		<td colspan="3"  style="font-size: 12px;text-align: center;">{{$credit->rp_tax_declaration_no}}</td>
            <td  colspan="3" style="font-size: 12px;text-align: center;">{{$credit->code.'-'.$credit->agDesc}}</td>
  
            <td colspan="3" style="font-size: 12px;text-align: center;">{{$credit->prefix.'-'.$credit->description}}</td>
            <td  colspan="3" style="font-size: 12px;text-align: center;">{{Helper::decimal_format($credit->tax_credit_amount)}}</td></tr>
            @endforeach
            @php $i++; @endphp
            > </tbody>