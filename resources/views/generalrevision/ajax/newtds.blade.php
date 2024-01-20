 <table class="table" id="">
                                <thead>
                                    <tr>
                                        
                                        <th style="font-size: 10px;">{{__('Drafted TD No.')}}</th>
                                        <th style="font-size: 10px;">{{__('Owner Name')}}</th>
                                        <th style="font-size: 10px;">{{__("PIN")}}</th>
                                        <th style="font-size: 10px;">{{__('Kind')}}</th>
                                        <th style="font-size: 10px;">{{__('Market Value')}}</th>
                                        <th style="font-size: 10px;">{{__('Assessed Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    
                                    @foreach($rptProperties as $key=>$val)
                                        <tr class="font-style">
                                           
                                            <td class="app_qurtr" style="font-size: 10px;">{{ $val->rp_tax_declaration_no }}</td>
                                            <td class="app_qurtr" style="font-size: 10px;"> 
                                                @php
                                                $content = $val->taxpayer_name;
                                                @endphp
                                                @if(strlen($content) > 30)
                                                    <div class="content">
                                                        <span class="short">{{ substr($content, 0, 30) }}</span>
                                                        <span class="full" style="display:none;">{{ $content }}</span>
                                                        <a href="#" class="more-btn">More</a>
                                                        <a href="#" class="less-btn" style="display:none;">Less</a>
                                                    </div>
                                                @else
                                                    {{ $content }}
                                                @endif
                                            </td>
                                            <td class="app_qurtr" style="font-size: 10px;">{{ $val->rp_pin_declaration_no }}</td>
                                            <td class="app_qurtr" style="font-size: 10px;">{{ $val->propertyKindDetails->pk_code }}</td>
                                            <td class="app_qurtr" style="font-size: 10px;">{{ number_format($val->market_value_new, 2, '.', ',') }}</td> 
                                            <td class="app_qurtr" style="font-size: 10px;">{{ number_format($val->assessed_value_new, 2, '.', ',') }}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>