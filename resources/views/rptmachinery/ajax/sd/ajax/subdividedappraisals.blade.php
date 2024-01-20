<table class="table" >
                                <thead>
                                    <tr>
                                       
                                        <th>{{__("TD No.")}}</th>
                                        <th>{{__("Class")}}</th>
                                        <th>{{__("Description")}}</th>
                                        <th>{{__('No. OF Unit')}}</th>
                                        <th>{{__("ACQUISITION COST")}}</th>
                                        <th>{{__("Base Market Value")}}</th>
                                        <th>{{__('Market Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($selectedProperty->machineAppraisals) && !$selectedProperty->machineAppraisals->isEmpty())
                                    @foreach($selectedProperty->machineAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            
                                            <td class="app_qurtr">{{ $selectedProperty->rp_tax_declaration_no }}</td>
                                            <td class="app_qurtr">{{ (isset($val->pc_class_description))?$val->pc_class_description:'' }}</td>
                                            <td class="app_qurtr">{{ $val->rpma_description }}</td>
                                            <td class="app_qurtr"><input type="number" name="set_land_area" value="{{ $val->rpma_appr_no_units }}" data-id="{{ $val->id }}" data-parentid="{{ $val->created_against }}" class="form-control set_land_area"></td>
                                            <!-- <td class="app_qurtr">{{ $val->rpma_appr_no_units }}</td> -->
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
                                    </tr>
                                    <tr class="font-style">
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