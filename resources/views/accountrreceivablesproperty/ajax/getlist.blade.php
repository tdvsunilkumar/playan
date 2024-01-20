
<table class="table" >
                            <thead>
                            <tr>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('No.')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TAXPAYER NAME')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('EFECTIVITY')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TAX YEAR')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('UC CODE')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('TD NO.')}}</th>
                                <th colspan="3" style="border:1px solid #fff;text-align: center;">{{__('Transaction')}}</th>
                                <th colspan="4" style="border:1px solid #fff;text-align: center;">{{__('Basic Tax')}}</th>
                                <th colspan="4" style="border:1px solid #fff;text-align: center;">{{__('SEF')}}</th>
                                <th colspan="4" style="border:1px solid #fff;text-align: center;">{{__('SHT')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Total')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Status')}}</th>
                            </tr>
                            <tr>
                                
                                <th style="border:1px solid #fff;text-align: center;">{{__('TOP')}}</th>
                                <th style="border:1px solid #fff;text-align: center;">{{__('OR-NO')}}</th>
                                <th style="border:1px solid #fff;text-align: center;">{{__('OR-DATE')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TAX AMOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('INTEREST')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('DISCOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TOTAL')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TAX AMOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('INTEREST')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('DISCOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TOTAL')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TAX AMOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('INTEREST')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('DISCOUNT')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 15%;">{{__('TOTAL')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; 
                                //echo date("Y");exit;
                                @endphp
                                @foreach($receiableDetails as $key => $val)
                                @if($val->ar_covered_year <= date("Y") || $val->or_no > 0)
                                @if($val->cbd_is_paid != 2)
                                @include('accountrreceivablesproperty.ajax.list')
                                @else
                                 @php
                                 $relatedDetails = DB::table('cto_accounts_receivable_details as card')
                                 ->select('card.*',DB::raw($customerName.' as customername'),'rp.rp_tax_declaration_no','rp.id','ctt.transaction_no','cc.cashier_or_date')
                                 ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                                 ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                                 ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                                 ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                                 ->where('card.ar_covered_year',$val->ar_covered_year)
                                 ->where('card.rp_property_code',$val->rp_property_code)
                                 ->get();
                                 
                                 @endphp

                                 @foreach($relatedDetails as $val)
                                 <?php// dd($val); ?>
                                 @include('accountrreceivablesproperty.ajax.list')
                                 @php $i++; @endphp
                                 @endforeach
                                @endif
                                @php $i++; @endphp
                                @endif
                                @endforeach
                            </tbody>
                            
                        </table>