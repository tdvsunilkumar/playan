<table class="table" id="" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;border: 1px solid #fff; width: 5%;">{{__('No.')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 20%;">{{__('Declared Owner')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('T.D. No.')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('P.I.N.')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__("Survey|CCT|unit no|Description.")}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('Class')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('Period')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('Basic Amount')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('SEF Amount')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 10%;">{{__('SH Amount')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 5%;">{{__('Total Amount')}}</th>
                                        <th style="text-align: center;border: 1px solid #fff;width: 5%;">{{__('Status')}}</th>
                                        <th style="width: 5%;"><a data-toggle="modal" href="javascript:void(0)" data-url="{{ route('billing.showform') }}?cb_billing_mode=1" id="{{(isset($paidButon) && $paidButon == 1)?'addBillingDetails':''}}" class="btn btn-primary" ><i class="ti-plus"></i></a></th>
                                    </tr>
                                </thead>
                                
                                
                                <tbody style="">
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
                                    @foreach($multipleBillingDetails as $billing)
                                    @php
                                    $count=$count+1;
                                     
                                    @endphp
                                    <tr class="font-style rowdetail">
                                           <td class="" style="text-align: center;border: 1px solid #ccc;width: 5%">{{ $count }}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ $billing->full_name }}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ $billing->rp_tax_declaration_no}}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ $billing->rp_pin_declaration_no }}</td>
                                             <td class="" style="border: 1px solid #ccc;width: 10%">
                                                @php 
                                                $rp_cadastral_lot_no = wordwrap($billing->rp_lot_cct_unit_desc, 15, "\n"); 
                                                @endphp
                                               <div class='showLess'>{{ $rp_cadastral_lot_no }}</div> </td>
                                               @php
                                               $class = (isset($billing->rptProperty->class_for_kind->pc_class_code))?$billing->rptProperty->class_for_kind->pc_class_code:'';
                                               @endphp

                                             <td class="" style="border: 1px solid #ccc;width: 10%">{{ ($billing->pk_code != null)?$billing->pk_code.'-'.$class:'' }}</td>
                                             @php
                                                $startMode = 11;
                                                $endMode   = 44;
                                                if($billing->endSdmode != null && !in_array($billing->endSdmode,[14,44])){
                                                    $startMode = $billing->startSdmode;
                                                    $endMode = $billing->endSdmode;
    
                                                }
                                             @endphp
                                             <td class="" style="border: 1px solid #ccc;width: 10%">{{ $billing->startYear.' '.Helper::billing_quarters()[$startMode].'-'.$billing->endYear.' '.Helper::billing_quarters()[$endMode] }}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ Helper::money_format($billing->basicAmount)}}</td> 
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ Helper::money_format($billing->sefAmount)}}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 10%">{{ Helper::money_format($billing->shAmount)}}</td>
                                            <td class="" style="border: 1px solid #ccc;width: 5%">{{ Helper::money_format($billing->totalDueNew)}}</td>
                                             <td class="" style="border: 1px solid #ccc;width: 5%">{{ ($billing->cb_is_paid == 1)?'Paid':'Pending'}}</td>
                                             <td style="text-align:center;border: 1px solid #ccc;width: 5%">
                                                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showBillingDetails" title="View Billing" data-id="{{$billing->id}}" data-url="{{url('billingform/show')}}"  data-title="Update Application">
                        <i class="ti-eye text-white"></i>
                    </a></div>
                    @if($billing->cb_is_paid != 1)
                    <div class="action-btn ms-2">
                        <a href="#" class="mx-3 btn btn-sm btn-danger  align-items-center deleteBillingDetails" title="delete Billing" data-id="{{$billing->id}}" data-url="#"  data-title="delete Application">
                            <i class="ti-trash text-white"></i>
                        </a>
                   </div>
                   @endif
                <!-- <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center deleteBillingDetails" title="Delete Billing" data-id="{{$billing->id}}" data-url="{{url('billingform/delete')}}"  data-title="Update Application">
                        <i class="ti-trash text-white"></i>
                    </a>
                </div> -->
                                             </td>
                                             
                                        </tr>
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
                                        </tr>
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
                                             
                                        </tr>
                                </tbody>
                            </table>