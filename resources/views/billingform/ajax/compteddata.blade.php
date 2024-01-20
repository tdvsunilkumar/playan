 <table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Year')}}</th>
                                        <th>{{__('Quarter')}}</th>
                                        <th>{{__("Assessed Value")}}</th>
                                        <th>{{__('Basic Amount')}}</th>
                                        <th>{{__('Basic Interest')}}</th>
                                        <th>{{__('Basic Discount')}}</th>
                                        <th>{{__('SEF Amount')}}</th>
                                        <th>{{__('SEF Interest')}}</th>
                                        <th>{{__('SEF Discount')}}</th>
                                        <th>{{__('SH Amount')}}</th>
                                        <th>{{__('SH Interest')}}</th>
                                        <th>{{__('SH Discount')}}</th>
                                        <th>{{__('Total Amount Due')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                    $count=0;
                                    @endphp
                                    
                                    @foreach($billing as $bill)
                                    @foreach($bill->billingDetails as $val)
                                  
                                    @php
                                    $count=$count+1;
                                    $totalAmountDue = ($val->cbd_basic_amount+$val->cbd_basic_penalty)-$val->cbd_basic_discount+($val->cbd_sef_amount+$val->cbd_sef_penalty)-$val->cbd_sef_discount+($val->cbd_sh_amount+$val->cbd_sh_penalty)-$val->cbd_sh_discount;
                                    $totalDue += $totalAmountDue;
                                    $basicAmountTotal += $val->cbd_basic_amount;
                                    $basiInterst += $val->cbd_basic_penalty;
                                    $basicDisc  += $val->cbd_basic_discount;
                                    $sefAmount += $val->cbd_sef_amount;
                                    $sefInterst += $val->cbd_sef_penalty;
                                    $sefDisc += $val->cbd_sef_discount;
                                    $shAmount += $val->cbd_sh_amount;
                                    $shInterst += $val->cbd_sh_penalty;
                                    $shDiscount += $val->cbd_sh_discount;
                                    @endphp
                                    
                                        <tr class="font-style">
                                           <td class="app_qurtr" >{{ $count }}</td>
                                            <td class="app_qurtr">{{ $val->cbd_covered_year }}</td>
                                            <td class="app_qurtr">{{ (in_array($val->sd_mode,array_flip(Helper::billing_quarters())))?Helper::billing_quarters()[$val->sd_mode]:'Annually' }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_assessed_value) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_basic_amount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_basic_penalty) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_basic_discount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sef_amount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sef_penalty) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sef_discount) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sh_amount) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sh_penalty) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->cbd_sh_discount) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($totalAmountDue) }}</td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                    <tr class="font-style">
                                           

                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class=""></td>
                                             <td class=""></td>
                                            <td class=""></td> 
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                      
                                      
                                         <tr class="font-style">
                                           
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                             <td class="">Total:</td>
                                             <td class=""><input type="text" class="form-control" value="{{ Helper::decimal_format($basicAmountTotal)}}" readonly="readonly" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($basiInterst)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($basicDisc)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($sefAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($sefInterst)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($sefDisc)}}" /></td>
                                             <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shAmount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shInterst)}}" /></td> 
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($shDiscount)}}" /></td>
                                            <td class=""><input type="text" class="form-control" readonly="readonly" value="{{ Helper::decimal_format($totalDue)}}" /></td>
                                        </tr>
                                </tbody>
                            </table>