<table class="table" id="">
                                <thead>
                                    <tr>
                                        <th>{{__('Select')}}</th>
                                        <th>{{__('NO.')}}</th>
                                        <th>{{__('BRGY.')}}</th>
                                        <th>{{__('T.D. No.')}}</th>
                                        <th>{{__("Suffix")}}</th>
                                        <th>{{__('Declared Owner')}}</th>
                                        <th>{{__('Lot No.')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Yearly Tax')}}</th>
                                        <th>{{__('OR Number')}}</th>
                                        <th>{{__('OR Date')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @php $count=0; @endphp
                                    @foreach($tdsDetails as $key=>$td)
                                    @php $count=$count+1; @endphp
                                    <tr class="font-style"> 
                                           <td class=""><input {{($key == 0)?'checked':''}} type="checkbox" value="{{(isset($td->propertyId))?$td->propertyId:''}}" class="selectForPaymentDetails" /></td>
                                           <td class="">{{ $count}}</td>
                                           <td class="">{{ $td->brgy_name}}</td>
                                            <td class="">{{ $td->rp_tax_declaration_no}}</td>
                                            <td class="">{{ $td->rp_suffix }}</td>
                                             <td class="">{{ $td->customername}}</td>
                                             <td class="">{{ $td->lotNo }}</td>
                                            <td class="">{{ number_format($td->assessedValue, 2, '.', ',') }}</td> 
                                            <td class="">{{ number_format($td->total_paid_amount, 2, '.', ',') }}</td>
                                            <td class="">{{ $td->or_no }} </td>
                                            <td class="">{{ date("d/m/Y",strtotime($td->cashier_or_date)) }}</td>
                                             <td class="">
												 <div class="action-btn bg-danger ms-2">
													 <a href="#"  class="mx-3 btn btn-sm btn-danger align-items-center deleteSelectedTd" title="Delete TD" data-parentid="{{$id}}" data-id="{{(isset($td->propertyId))?$td->propertyId:''}}" data-url="{{ url('taxclearance/deletedtd') }}"  data-title="Delete TD">
														<i class="ti-trash text-white" ></i>
													</a> 
												</div>
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
                                        </tr>
                                </tbody>
                            </table>