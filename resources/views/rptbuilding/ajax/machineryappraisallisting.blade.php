<div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('Machine Description')}}</th>
                                        <th>{{__('Brand & model')}}</th>
                                        <th>{{__("Capacity / HP")}}</th>
                                        <th>{{__('Date Acquired')}}</th>
                                        <th>{{__('Condition When Acquired')}}</th>
                                        <th>{{__('Eco Life Estimate')}}</th>
                                        <th>{{__('Eco Life Remain')}}</th>
                                        <th>{{__('Date Installed')}}</th>
                                        <th>{{__('Date Operated')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; $totalMarketValue=0; @endphp
                                    @foreach($machineryApprasals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr"><input type="checkbox" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="{{ $val->id }}"/></td>
                                            <td class="app_qurtr">{{ $i }}</td>
                                            <td class="app_qurtr">{{ $val->pc_class_description }}</td>
                                            <td class="app_qurtr">{{ $val->ps_subclass_desc }}</td>
                                             <td class="app_qurtr">{{ $val->pau_actual_use_desc }}</td>
                                            <td class="app_qurtr">{{ $val->rpa_total_land_area.' '.config('constants.lav_unit_measure')[$val->lav_unit_measure] }}</td> 
                                            <td class="app_qurtr">{{ $val->rls_percent }}</td>
                                            <td class="app_qurtr">{{ $val->lav_unit_value }}</td> 
                                            <td class="rpa_base_market_value">{{ $val->rpa_base_market_value }}</td>
                                            <td class="app_qurtr">No</td>
                                            @php $totalMarketValue+=$val->rpa_base_market_value;$i++; @endphp
                                        </tr>
                                    @endforeach
                                    <tr class="font-style last-option">
                                        <td><!-- <input type="checkbox" data-sessionid="12" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="13"/> --></td>
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
                                        <td></td>
                                    </tr>
                                    <tr class="font-style">
                                        <td><!-- <input type="checkbox" data-sessionid="16" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="17"/> --></td>
                                        <td></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />