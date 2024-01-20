
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Classification')}}</th>
                                        <th>{{__("Sub Class")}}</th>
                                        <th>{{__('Actual Use')}}</th>
                                        <th>{{__('Area')}}</th>
                                        <th>{{__('Stripping')}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Exempt')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; $totalMarketValue=0; @endphp
                                    @foreach($details->landAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $i }}</td>
                                            <td class="app_qurtr">{{ (isset($val->pc_class_description))?$val->pc_class_description:'' }}</td>
                                            <td class="app_qurtr">{{ (isset($val->ps_subclass_desc))?$val->ps_subclass_desc:'' }}</td>
                                             <td class="app_qurtr">{{ $val->pau_actual_use_desc }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format($val->rpa_total_land_area) }} {{($val->lav_unit_measure != '')?config('constants.lav_unit_measure')[$val->lav_unit_measure]:''}}</td> 
                                            <td class="app_qurtr">{{ $val->rls_percent }}</td>
                                            <td class="app_qurtr">{{ Helper::money_format($val->lav_unit_value) }}</td> 
                                            <td class="rpa_base_market_value">{{ Helper::money_format($val->rpa_base_market_value) }}</td>
                                            <td class="app_qurtr">{{ ($details->rp_app_taxability == 1)?'No':'Yes'}}</td>
                                            @php $totalMarketValue += $val->rpa_base_market_value;$i++; @endphp
                                        </tr>
                                    @endforeach
                                    <tr class="font-style last-option">
                                        
                                        <td></td>
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
                                        <td><!-- <input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/> --></td>
                                        <td></td>
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