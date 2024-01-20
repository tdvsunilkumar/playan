<tr>
                                    <td>{{$i}}</td>
                                    <td>{{$val->customername}}</td>
                                    <td>{{$val->rp_app_effective_year}}</td>
                                    <td>{{$val->ar_covered_year}}</td>
                                    <td>{{$val->uc_code}}</td>
                                    <td>{{$val->rp_tax_declaration_no}}</td>
                                    <td>{{($val->transaction_no > 0)?$val->transaction_no:''}}</td>
                                    <td>{{($val->or_no > 0)?$val->or_no:''}}</td>
                                    <td>{{($val->cashier_or_date != '')?$val->cashier_or_date:''}}</td>
                                    <td>{{Helper::decimal_format($val->basic_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->basic_penalty_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->basic_discount_amount)}}</td>
                                    @php $basicTotal = $val->basic_amount+$val->basic_penalty_amount-$val->basic_discount_amount; @endphp
                                    <td>{{Helper::decimal_format($basicTotal)}}</td>
                                    <td>{{Helper::decimal_format($val->sef_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->sef_penalty_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->sef_discount_amount)}}</td>
                                    @php $sefTotal = $val->sef_amount+$val->sef_penalty_amount-$val->sef_discount_amount; @endphp
                                    <td>{{Helper::decimal_format($sefTotal)}}</td>
                                    <td>{{Helper::decimal_format($val->sh_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->sh_penalty_amount)}}</td>
                                    <td>{{Helper::decimal_format($val->sh_discount_amount)}}</td>
                                    @php $shTotal = $val->sh_amount+$val->sh_penalty_amount-$val->sh_discount_amount; @endphp
                                    <td>{{Helper::decimal_format($shTotal)}}</td>
                                    <td class="alltotal">{{ Helper::decimal_format($basicTotal+$sefTotal+$shTotal)}}</td>
                                    <td><button class="btn btn-{{ ($val->cbd_is_paid == 1)?'info':(($val->cbd_is_paid == 2)?'warning':'danger')}}">{{ ($val->cbd_is_paid == 1)?'Paid':(($val->cbd_is_paid == 2)?'Partial':'Pending')}}</button></td>
                                </tr>