<div class="row" style="padding-top: 0px !important;">
            <div class="col-xl-12" style="margin-top:-33px;" >
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal" >
                                <thead>
                                    <tr>
                                        
                                        <th>{{__('Property Kind')}}</th>
                                        <th>{{__("Actual Use")}}</th>
                                        <th>{{__("Adjusted Market Value")}}</th>
                                        <th>{{__("Assessment Level")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalAdjustedMarketValue = 0;
                                    $totalAsseedValue = 0;
                                    @endphp
                                  @foreach($landApprasals as $land)
                                    @php
                                        $adjustValue = $land->rpa_adjusted_total_planttree_market_value;
                                        if($adjustValue<=0){
                                            $adjustValue = $land->rpa_adjusted_market_value;
                                        }
                                    @endphp
                                    <tr class="font-style">
                                        <td>{{ (isset($land->pk_code))?$land->pk_code:'L' }}</td>
                                        <td>{{ $land->pau_actual_use_desc }}</td>
                                        <td>{{ Helper::money_format($adjustValue) }}</td>
                                        <td>{{ Helper::decimal_format($land->al_assessment_level) }}</td>
                                        <td class="assessedValueAssSumary">{{ Helper::money_format($land->rpa_assessed_value) }}</td>
                                    </tr>
                                    @php 
                                    $totalAdjustedMarketValue += $adjustValue; 
                                    $totalAsseedValue += $land->rpa_assessed_value; 
                                    @endphp
                                    @endforeach
                                    <!-- <tr class="font-style">
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
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--------------- Land Apraisal Listing End Here------------------><br />
        
                            <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                       <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label" style="text-align:end;">Total Adjusted Market Value :</label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" readonly class="form-control decimalvalue" name="" value="{{ isset($totalAdjustedMarketValue)?Helper::number_format($totalAdjustedMarketValue):'0.00'}}" >
       <div class="currency-sign"><span>Php</span></div>
  </div>
    </div>
  </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label" style="text-align:end;">Total Assessed Value :</label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" name="rp_assessed_value" readonly class="form-control decimalvalue" value="{{ (isset($totalAsseedValue) && $totalAsseedValue != 0)?Helper::number_format($totalAsseedValue):((isset($propDetails->rp_assessed_value))?$propDetails->rp_assessed_value:00.00)}}">
       <div class="currency-sign"><span>Php</span></div>
  </div>
  <span class="validate-err" id="err_rp_assessed_value"></span>
    </div>
  </div>
                                    </div>
                                </div>