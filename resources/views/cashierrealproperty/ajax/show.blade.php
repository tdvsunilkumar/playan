<table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2" style="text-align:center;padding-left: 10px;">NO.</th>
                                                                    <th rowspan="2" style="text-align:center;">T.D. No.</th>
                                                                    <th rowspan="2" style="text-align:center;">Tax Year</th>
                                                                    <th colspan="3" style="text-align:center;">Basic Tax</th>
                                                                    <th colspan="3" style="text-align:center;">SEF Tax</th>
                                                                    <th colspan="3" style="text-align:center;">S-Housing Tax</th>
                                                                    <th rowspan="2" style="text-align:center;    padding-right: 10px;">Total Amount</th>
                                                                </tr>
                                                                <tr>
                                                                    
                                                                    <th style="text-align:center;padding-left: 10px;">Amount</th>
                                                                    <th style="text-align:center;">Discount</th>
                                                                    <th style="text-align:center;">Penalty</th>
                                                                    <th style="text-align:center;">Amount</th>
                                                                    <th style="text-align:center;">Discount</th>
                                                                    <th style="text-align:center;">Penalty</th>
                                                                    <th style="text-align:center;padding-left: 22;">Amount</th>
                                                                    <th style="text-align:center;">Discount</th>
                                                                    <th style="text-align:center;padding-left: 22;">Penalty</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            
                                                            <tbody >
                                                            	 @php
                                                                 $i=1;
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
                                                            	@foreach($billingDetails as $key=>$val)
                                     @php
                                     $newBasicPenalty = ($val->basicPenalty != null)?$val->basicPenalty:0;
                                     $newBasicDiscount = ($val->basicDiscount != null)?$val->basicDiscount:0;
                                     $newSefPenalty = ($val->sefPenalty != null)?$val->sefPenalty:0;
                                     $newSefDiscount = ($val->sefDiscount != null)?$val->sefDiscount:0;
                                     $newShPenalty = ($val->shPenalty != null)?$val->shPenalty:0;
                                     $newShDiscount = ($val->shDiscount != null)?$val->shDiscount:0;

                                    $totalAmountDue = $val->totalDue;
                                    
                                    $totalDue += $totalAmountDue;
                                    $basicAmountTotal += $val->basicAmount;
                                    $basiInterst += ($val->basicPenalty != null)?$val->basicPenalty:0;
                                    $basicDisc  += ($val->basicDiscount != null)?$val->basicDiscount:0;
                                    $sefAmount += $val->sefAmount;
                                    $sefInterst += ($val->sefPenalty != null)?$val->sefPenalty:0;
                                    $sefDisc += ($val->sefDiscount != null)?$val->sefDiscount:0;
                                    $shAmount += $val->shAmount;
                                    $shInterst += ($val->shPenalty != null)?$val->shPenalty:0;
                                    $shDiscount += ($val->shDiscount != null)?$val->shDiscount:0;
                                    @endphp
                                                                 <tr>
                                                                    <td>{{$i}}</td>
                                                                    <td>{{$val->rp_tax_declaration_no }}</td>
                                                                    <td>{{ $val->cbd_covered_year }}</td>
                                                                    <td>{{ Helper::decimal_format($val->basicAmount) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->basicDiscount != null)?$val->basicDiscount:0) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->basicPenalty != null)?$val->basicPenalty:0) }}</td>
                                                                    <td>{{ Helper::decimal_format($val->sefAmount) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->sefDiscount != null)?$val->sefDiscount:0) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->sefPenalty != null)?$val->sefPenalty:0) }}</td>
                                                                    <td>{{ Helper::decimal_format($val->shAmount) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->shDiscount != null)?$val->shDiscount:0) }}</td>
                                                                    <td>{{ Helper::decimal_format(($val->shPenalty != null)?$val->shPenalty:0) }}</td>
                                                                    <td>{{ Helper::decimal_format($totalAmountDue) }}</td>
                                                                </tr>
                                                                @php $i++;@endphp
                                                                @endforeach
                                                                 <tr>
                                                                   <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                 <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                 
                                                            </tbody>

                                                        </table>