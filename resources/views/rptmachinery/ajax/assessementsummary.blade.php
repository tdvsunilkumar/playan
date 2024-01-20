<style type="text/css">
    

.selected {
    background-color: #20B7CC;
    color: #FFF;
}
</style>
<div class="row" style="padding-top: 0px !important;    margin-top: -40px;">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="newAddedAssessementSummary">
                                <thead>
                                    <tr>
                                        <th >{{__('No.')}}</th>
                                        <th >{{__('Property Kind')}}</th>
                                        <th>{{__("Classification")}}</th>
                                        <th>{{__("Market Value")}}</th>
                                        <th>{{__("Assessment Level")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        
                                    </tr>
                                </thead>
                                 @php $i=1; @endphp
                                <tbody>
                                  
                                    <tr class="font-style">
                                        <td class="property_kind">{{$i}}</td>
                                        <td class="property_kind">{{ (isset($newAssesmentSummary->property_kind))?$newAssesmentSummary->property_kind:'' }}</td>
                                        <td>{{ (isset($newAssesmentSummary->actualUse))?$newAssesmentSummary->actualUse:'' }}</td>
                                        <td>{{ (isset($newAssesmentSummary->marketValue))?Helper::money_format($newAssesmentSummary->marketValue):'' }}</td>
                                        <td>{{ (isset($newAssesmentSummary->AssesseMentLevel))?Helper::decimal_format($newAssesmentSummary->AssesseMentLevel).' %':'' }}</td>
                                        <td>{{ (isset($newAssesmentSummary->assessedValue))?Helper::money_format($newAssesmentSummary->assessedValue):'' }}</td>
                                    </tr>
                                   
                                   <!--  <tr class="font-style">
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
    <label for="staticEmail" class="col-sm-7 col-form-label">Total Market Value: </label>
    <div class="col-sm-5">
        <div class="form-icon-user currency">
      <input type="text" class="form-control" name="" value="{{ (isset($newAssesmentSummary->marketValue))?Helper::decimal_format($newAssesmentSummary->marketValue):'' }}" >
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
      <input type="text" class="form-control" name="rp_assessed_value" value="{{ (isset($newAssesmentSummary->assessedValue))?Helper::decimal_format($newAssesmentSummary->assessedValue):'' }}" readonly>
       <div class="currency-sign"><span>Php</span></div>
  </div>
  <span class="validate-err" id="err_rp_assessed_value"></span>
    </div>
  </div>
                                    </div>
                                </div>