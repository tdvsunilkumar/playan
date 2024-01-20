<table class="table" >
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__("TD No.")}}</th>
                                        <th>{{__("Class")}}</th>
                                        <th>{{__("Description")}}</th>
                                        <th>{{__('No. OF Unit')}}</th>
                                        <th>{{__('Remaining Units')}}</th>
                                        <th>{{__("ACQUISITION COST")}}</th>
                                        <th>{{__("Base Market Value")}}</th>
                                        <th>{{__('Market Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($selectedProperty->machineAppraisals) && !$selectedProperty->machineAppraisals->isEmpty())
                                    @foreach($selectedProperty->machineAppraisals as $key=>$val)
                                    @php 
                                    @endphp
                                        <tr class="font-style">
                                            <td>
                                                @if(in_array($val->id,$assignedFloors) && $val->subdivision_used_units == $val->rpma_appr_no_units)
                                                <span style="color: green;">Assigned</span>
                                                @else
                                                <input type="checkbox" data-landappraisalid="{{ $val->id }}" name="selectedFloorValues[]" class="landAppraisalIdForSubdivision" value="{{ $val->id }}" {{ ($key == '0')?'checked':''}}/>
                                                @endif
                                            </td>
                                            <td class="app_qurtr">{{ $selectedProperty->rp_tax_declaration_no }}</td>
                                            <td class="app_qurtr">{{ (isset($val->pc_class_description))?$val->pc_class_description:'' }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_description }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_appr_no_units }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_appr_no_units-$val->subdivision_used_units }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($val->rpma_acquisition_cost) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->rpma_base_market_value) }}</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->rpma_market_value) }}</td>
                                            
                                        </tr>
                                    @endforeach
                                    @endif
                                    <tr class="font-style last-option">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="font-style">
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