<style type="text/css">
    

.selected {
    background-color: #20B7CC;
    color: #FFF;
}
</style>
<div class="row" style="padding-top: 0px !important;">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="newAddedAssessementSummary">
                                <thead>
                                    <tr>
                                        <th >{{__('No.')}}</th>
                                        <th >{{__('Property Kind')}}</th>
                                        <th>{{__("Actual Use")}}</th>
                                        <th>{{__("Adjusted/Depreciated Market Value")}}</th>
                                        <th>{{__("Assessment Level")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalAdjustedMarketValue = 0; $i=1;
                                    $totalAsseedValue = 0;
                                    @endphp
                                  @foreach($newAssesmentSummary as $land)
                                    <tr class="font-style" data-id="{{ $land['actualUseId'] }}">
                                        <td class="">{{ $i }}</td>
                                        <td class="property_kind">{{ $land['property_kind'] }}</td>
                                        <td>{{ $land['actualUse'] }}</td>
                                        <td>{{ Helper::money_format($land['adjustedDepreciatedMarketValue']) }}</td>
                                        <td>{{ Helper::decimal_format($land['AssesseMentLevel']) }}&nbsp;%</td>
                                        <td>{{ Helper::money_format($land['assessedValue']) }}</td>
                                    </tr>
                                    @php 
                                    $totalAdjustedMarketValue += $land['adjustedDepreciatedMarketValue']; 
                                    $totalAsseedValue += $land['assessedValue'];  $i++;
                                    @endphp
                                    @endforeach
                                    <tr class="font-style">
                                        <td class="property_kind"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="font-style">
                                        <td class="property_kind"></td>
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
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                       <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label">Total Adjusted Market Value: </label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" name="" value="{{ isset($totalAdjustedMarketValue)?Helper::decimal_format($totalAdjustedMarketValue):'0.00'}}" >
       <div class="currency-sign"><span>Php</span></div>
  </div>
    </div>
  </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group row">
    <label for="staticEmail" class="col-sm-7 col-form-label">Total Assessed Value : </label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" name="" value="{{ isset($totalAsseedValue)?Helper::decimal_format($totalAsseedValue):'0.00'}}">
       <div class="currency-sign"><span>Php</span></div>
  </div>
    </div>
  </div>
                                    </div>
                                </div>
                                
  <input type="hidden" name="rp_accum_depreciation" class="rp_accum_depreciation" value="{{(isset($depDetails->rp_accum_depreciation))?$depDetails->rp_accum_depreciation:0}}">

  <input type="hidden" name="rpb_accum_deprec_market_value" class="rpb_accum_deprec_market_value" value="{{(isset($depDetails->rpb_accum_deprec_market_value))?$depDetails->rpb_accum_deprec_market_value:0}}">

  <input type="hidden" name="al_assessment_level" class="al_assessment_level" value="{{(isset($depDetails->al_assessment_level))?$depDetails->al_assessment_level:0}}">

  <input type="hidden" name="rpb_assessed_value" class="rpb_assessed_value" value="{{(isset($depDetails->rpb_assessed_value))?$depDetails->rpb_assessed_value:0}}">                                