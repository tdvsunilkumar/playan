
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Classification')}}</th>
                                        <th>{{__('Actual Use')}}</th>
                                        <th>{{__('Area')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Exempt')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                        <tr class="font-style">
                                            <td class="app_qurtr">1</td>
                                            <td class="app_qurtr">{{ (isset($details->propertyClass->pc_class_description))?$details->propertyClass->pc_class_description:'' }}</td>
                                            <td class="app_qurtr">{{ (isset($details->floorValues[0]->pau_actual_use_code_desc))?$details->floorValues[0]->pau_actual_use_code_desc:'' }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($details->floorValues->sum('rpbfv_floor_area')) }} Sq. m.</td> 
                                            <td class="app_qurtr">{{ Helper::decimal_format($details->rpb_accum_deprec_market_value) }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($details->rpb_assessed_value) }}</td> 
                                            <td class="app_qurtr">{{ ($details->rp_app_taxability == 1)?'No':'Yes'}}</td>
                                        </tr>
                                  
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
                                        <td><!-- <input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/> --></td>
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