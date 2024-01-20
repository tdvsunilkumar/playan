<table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        
                                        <th>{{__('Kind/Class')}}</th>
                                        <th>{{__("Area/FB Trees")}}</th>
                                        <th>{{__('Unit')}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(isset($landAppraisals))
                                    @foreach($landAppraisals as $val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{ $val->pk_code.'-'.$val->pc_class_code }}</td>
                                            <td class="app_qurtr"><input type="text" name="set_land_area" value="{{ $val->rpa_total_land_area }}" data-id="{{ $val->id }}" class="form-control set_land_area"></td>
                                             <td class="app_qurtr">{{ (isset(config('constants.lav_unit_measure')[$val->lav_unit_measure]))?config('constants.lav_unit_measure')[$val->lav_unit_measure]:'' }}</td>
                                            <td class="app_qurtr">{{ Helper::decimal_format( $val->lav_unit_value) }}</td> 
                                             <td class="app_qurtr">{{  Helper::decimal_format($val->rpa_base_market_value) }}</td> 
                                        </tr>
                                    @endforeach
                                    @endif
                                    <tr class="font-style last-option">
                                      
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
                                    </tr>
                                    
                                </tbody>
                            </table>