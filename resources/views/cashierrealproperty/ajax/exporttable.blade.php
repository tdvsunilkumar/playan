<table class="table" id="dataToExport">
                            <thead>
                            <tr>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('No.')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('DATE')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('OR NO.')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('PAYEE')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('ASSESSED VALUE')}}</th>
                              
                                <th colspan="9" style="border:1px solid #fff;text-align: center;">{{__('Real Property Tax')}}</th>

                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Penalty')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Tax Credit')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Advance Payment')}}</th>
                                <th rowspan="3" style="border:1px solid #fff;text-align: center;">{{__('Amount Collected')}}</th>
                            </tr>
                            <tr>
                                
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('BASIC')}}</th>
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('Special Education Tax')}}</th>
                                <th  colspan="3" style="text-align: center;border:1px solid #fff;">{{__('Socialize Housing')}}</th>
                                
                            </tr>
                            <tr>
                                
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th>

                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th>

                                <th  style="border:1px solid #fff;text-align: center;">{{__('Current Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Previous Year')}}</th>
                                <th  style="border:1px solid #fff;text-align: center;">{{__('Prior Year')}}</th> 
                                
                            </tr> 
                            </thead>
                            <tbody>
                            	@php $srNo = 1;
                                $totalAssValue = 0;

                                $totalbasicCuYear = 0;
                                $totalbasicPreYear = 0;
                                $totalbasicPrioYear = 0;

                                $totalSefCuYear = 0;
                                $totalSefPreYear = 0;
                                $totalSefPrioYear = 0;

                                $totalSHTCuYear = 0;
                                $totalSHTPreYear = 0;
                                $totalSHTPrioYear = 0;

                                $totalPenalty = 0;

                                $totalTaxCred = 0;

                                $totalAdvPay = 0;

                                $amountCollected = 0;
                                @endphp
                            	@foreach($excelData as $row)
                            	<tr>
                            		<td>{{$srNo}}</td>
                            		<td>{{$row->cashier_or_date}}</td>
                            		<td>{{$row->or_no}}</td>
                            		<td>{{$row->full_name}}</td>
                            		<td>{{Helper::decimal_format($row->cbd_assessed_value)}}</td>
                            		<td>{{Helper::decimal_format($row->currentYearBasicTax)}}</td>
                            		<td>{{Helper::decimal_format($row->previousYearBasicTax)}}</td>
                            		<td>{{Helper::decimal_format($row->priorYearBasicTaxes)}}</td>
                            		<td>{{Helper::decimal_format($row->currentYearSefTax)}}</td>
                            		<td>{{Helper::decimal_format($row->previousYearSefTax)}}</td>
                            		<td>{{Helper::decimal_format($row->priorYearSefTaxes)}}</td>
                            		<td>{{Helper::decimal_format($row->currentYearShtTax)}}</td>
                            		<td>{{Helper::decimal_format($row->previousYearShtTax)}}</td>
                            		<td>{{Helper::decimal_format($row->priorYearShtTaxes)}}</td>
                            		<td>{{Helper::decimal_format($row->penalty)}}</td>
                            		<td>{{Helper::decimal_format($row->taxCredit)}}</td>
                            		<td>{{Helper::decimal_format($row->advancePayment)}}</td>
                            		<td>{{Helper::decimal_format($row->total_paid_amount)}}</td>

                            	</tr>
                            	@php $srNo++;
                                $totalAssValue += $row->cbd_assessed_value;

                                $totalbasicCuYear += $row->currentYearBasicTax;
                                $totalbasicPreYear += $row->previousYearBasicTax;
                                $totalbasicPrioYear += $row->priorYearBasicTaxes;

                                $totalSefCuYear += $row->currentYearSefTax;
                                $totalSefPreYear += $row->previousYearSefTax;
                                $totalSefPrioYear += $row->priorYearSefTaxes;

                                $totalSHTCuYear += $row->currentYearShtTax;
                                $totalSHTPreYear += $row->previousYearShtTax;
                                $totalSHTPrioYear += $row->priorYearShtTaxes;

                                $totalPenalty += $row->penalty;

                                $totalTaxCred += $row->taxCredit;

                                $totalAdvPay += $row->advancePayment;

                                $amountCollected += $row->total_paid_amount;
                                 @endphp
                            	@endforeach
                            </tbody>
                             <tfoot>
                                <tr>
                                    <th colspan="4" style="border:1px solid #fff;text-align: center;">Total</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalAssValue)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalbasicCuYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalbasicPreYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalbasicPrioYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSefCuYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSefPreYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSefPrioYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSHTCuYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSHTPreYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalSHTPrioYear)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalPenalty)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalTaxCred)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($totalAdvPay)}}</th>
                                    <th style="border:1px solid #fff;text-align: right;">{{Helper::decimal_format($amountCollected)}}</th>
                                </tr>
                            </tfoot>
                            
                        </table>