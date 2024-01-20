
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Classification')}}</th>
                                        <th>{{__("Description")}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Exempt')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $desc = array_unique($details->machineAppraisals->pluck('rpma_description')->toArray());

                                    @endphp
                                        <tr class="font-style">
                                            <td class="app_qurtr">1</td>
                                            <td class="app_qurtr">{{ (isset($details->machineAppraisals[0]->pc_class_description))?$details->machineAppraisals[0]->pc_class_description:'' }}</td>
                                            <td class="app_qurtr">{{ implode('; ',$desc) }}</td>
                                             <td class="app_qurtr">{{ Helper::decimal_format($details->machineAppraisals->sum('rpma_market_value')) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($details->machineAppraisals->sum('rpm_assessed_value')) }}</td> 
                                            <td class="app_qurtr">{{ ($details->rp_app_taxability == 1)?'No':'Yes'}}</td>
                                        </tr>
                                    <tr class="font-style last-option">
                                        
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                    <tr class="font-style">
                                        <td><!-- <input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/> --></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                   
                                </tbody>
                            </table>