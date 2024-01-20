                                    <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Plant/Tree')}}</th>
                                        <th>{{__("Class")}}</th>
                                        <th>{{__('Sub Class')}}</th>
                                        <th>{{__('Area Planted')}}</th>
                                        <th>{{__('Non Fruit Bearing')}}</th>
                                        <th>{{__('Fruit Bearing')}}</th>
                                        <th>{{__('Age')}}</th>
                                        <th>{{__('Unit Value')}}</th>
                                        <th>{{__('Market Value')}}</th>

                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1;$totalPlantTreeValue = 0; @endphp
                                    @foreach($plantsTreeApprasals as $key=>$plantTreeApp)
                                 
                                           <tr class="font-style" >
                                            <td>{{ $i }}</td>
                                            <td>{{ $plantTreeApp->pt_ptrees_description }}</td>
                                            <td>{{ $plantTreeApp->pc_class_description }}</td>
                                            <td>{{ $plantTreeApp->ps_subclass_desc }}</td>
                                            <td>{{ Helper::decimal_format($plantTreeApp->rpta_total_area_planted) }}</td>
                                            <td>{{ Helper::decimal_format($plantTreeApp->rpta_non_fruit_bearing) }}</td>
                                            <td>{{ Helper::decimal_format($plantTreeApp->rpta_fruit_bearing_productive) }}</td>
                                            <td>{{ date("Y")-$plantTreeApp->rpta_date_planted }} Years</td>
                                            <td>{{ Helper::money_format($plantTreeApp->rpta_unit_value) }}</td>
                                            <td>{{ Helper::money_format($plantTreeApp->rpta_market_value) }}</td>
                                            
                                            <td class="action"><a href="javascript:void(0)" data-sessionid="{{ (isset($plantTreeApp->id) && $plantTreeApp->id != '')?'':$key }}" data-id="{{ $plantTreeApp->id }}" class="editPlantTreeAppraisal"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" data-sessionid="{{ (isset($plantTreeApp->id) && $plantTreeApp->id != '')?'':$key }}" data-id="{{ $plantTreeApp->id }}" class="deletePlantTreeAppraisal"><i class="fas fa-trash"></i></a></td>
                                            @php $i++;$totalPlantTreeValue += $plantTreeApp->rpta_market_value; @endphp
                                        </tr>
                                    @endforeach
                                    <tr class="font-style">
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
                            <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                       
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group row">
    <label for="staticEmail" class="col-sm-5 col-form-label">Total Plants/Trees Value : </label>
    <div class="col-sm-7">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" value="{{ (isset($totalPlantTreeValue))?number_format((float)$totalPlantTreeValue, 2, '.', ''):0.00 }}" id="landApraisalTotalValueToDisplay" >
      <div class="currency-sign"><span>Php</span></div>
  </div>
    </div>
  </div>
                                    </div>
                                </div>