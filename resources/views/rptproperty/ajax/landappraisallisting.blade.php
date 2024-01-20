<div class="row">
            <div class="col-xl-12" >
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Classification')}}</th>
                                        <th>{{__("Sub Class")}}</th>
                                        <th>{{__('Actual Use')}}</th>
                                        <th>{{__('Area')}}</th>
                                        <!-- <th>{{__('Stripping')}}</th> -->
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        <th>{{__('Exempt')}}</th>
                                        <th style="width: 5%;"><a data-toggle="modal" href="javascript:void(0)" id="loadLandApprisalForm" class="btn btn-primary" type="add"><i class="ti-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; $totalMarketValue=0; @endphp
                                    @foreach($landApprasals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr"><input type="checkbox" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="{{ $val->id }}"/></td>
                                            <td class="app_qurtr">{{ $i }}</td>
                                            <td class="app_qurtr">{{ $val->pc_class_description }}</td>
                                            <td class="app_qurtr">{{ $val->ps_subclass_desc }}</td>
                                             <td class="app_qurtr">{{ $val->pau_actual_use_desc }}</td>
                                            <td class="app_qurtr">{{($val->lav_unit_measure == 2)?number_format($val->rpa_total_land_area,4):number_format($val->rpa_total_land_area,3)}} {{($val->lav_unit_measure != '')?config('constants.lav_unit_measure')[$val->lav_unit_measure]:''}}</td> 
                                            <!-- <td class="app_qurtr">{{ $val->rls_percent }}</td> -->
                                            <td class="app_qurtr">{{ Helper::money_format($val->lav_unit_value) }}</td> 
                                            <td class="rpa_base_market_value">{{ Helper::money_format($val->rpa_base_market_value) }}</td>
                                            <td class="app_qurtr">No</td>
                                            
                                             <td class="action"><a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="editLandAppraisal"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="deleteLandAppraisal"><i class="fas fa-trash"></i></a></td>
                                            @php $totalMarketValue += $val->rpa_base_market_value;$i++; @endphp
                                        </tr>
                                    @endforeach
                                    <!-- <tr class="font-style last-option">
                                        <td><input type="checkbox" data-sessionid="12" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="13"/></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr> -->
                                    <!-- <tr class="font-style">
                                        <td><input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr> -->
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />