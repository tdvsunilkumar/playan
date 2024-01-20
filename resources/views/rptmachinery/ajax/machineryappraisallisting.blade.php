<div class="row" style="padding-top: 0px !important;">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                        <th>{{__('No.')}}</th>
                                        <th>{{__('Machine Description')}}</th>
                                        <th>{{__('No. of Unit')}}</th>
                                        <th>{{__("Acquisition Cost")}}</th>
                                        <th>{{__('Additional Cost')}}</th>
                                        <th>{{__('Base Market Value')}}</th>
                                        <th>{{__('Depreciation')}}</th>
                                        <th>{{__('Market Value')}}</th>
                                        
                                        <th style="width: 5%;"><a data-toggle="modal" href="javascript:void(0)" id="loadMachineApprisalForm" class="btn btn-primary" ><i class="ti-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; $totalMarketValue=0; @endphp
                                    @foreach($machineAppraisals as $key=>$val)
                                        <tr class="font-style">
                                            <td class="app_qurtr">{{$i}}</td>
                                            @php $desc = wordwrap($val->rpma_description, 30, "\n"); @endphp
                                            <td class="app_qurtr"><div class='showLess'>{{$desc}}</div></td>
                                            <td class="app_qurtr">{{$val->rpma_appr_no_units}}</td>
                                            <td class="app_qurtr">{{Helper::money_format($val->rpma_acquisition_cost)}}</td>
                                            <td class="app_qurtr">{{Helper::money_format(($val->rpma_freight_cost+$val->rpma_insurance_cost+$val->rpma_insurance_cost+$val->rpma_installation_cost+$val->rpma_other_cost))}}</td>
                                             <td class="app_qurtr">{{Helper::money_format($val->rpma_base_market_value)}}</td>
                                            <td class="app_qurtr">{{Helper::money_format($val->rpma_depreciation)}}</td> 
                                            <td class="app_qurtr">{{Helper::money_format($val->rpma_market_value)}}</td>
                                            <td class="action"><a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="editLandAppraisal"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" data-sessionid="{{ (isset($val->id) && $val->id != '')?'':$key }}" data-id="{{ $val->id }}" class="deleteLandAppraisal"><i class="fas fa-trash"></i></a></td>
                                            @php $totalMarketValue+=$val->rpma_market_value;$i++; @endphp
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
                                        
                                    </tr> -->
                                   <!--  <tr class="font-style">
                                        <td><input type="checkbox" data-sessionid="14" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="15"/></td>
                                       
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
                                        <td><input type="checkbox" data-sessionid="16" class="addLandAppraisalAdjustmentFactorOrPlantTree" value="17"/></td>
                                       
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
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Class : </label>
                <div class="col-sm-4" id="asse_summary_pc_class_code_group">
                    {{Form::select('asse_summary_pc_class_code',$classes,(isset($machineAppraisals[0]->pc_class_code))?$machineAppraisals[0]->pc_class_code:'',array('class'=>'form-control asse_summary_pc_class_code','id'=>'asse_summary_pc_class_code'))}}
                    <span class="validate-err" id="err_asse_summary_pc_class_code"></span>
                </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="form-group row">
                <label for="staticEmail" class="col-sm-5 col-form-label">Total Market Value : </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" value="{{ (isset($totalMarketValue))?Helper::decimal_format($totalMarketValue):0.00 }}" id="landApraisalTotalValueToDisplay" >
                </div>
                </div>
            </div>  
        </div>
        <script>
        $(document).ready(function () {
            $("#asse_summary_pc_class_code").select3({dropdownAutoWidth : false,dropdownParent : '#asse_summary_pc_class_code_group'});
            $(".showLess").shorten({
            "showChars" : 20,
            "moreText"  : "More",
            "lessText"  : "Less",
        });
        });
        </script>
        <!--------------- Land Apraisal Listing End Here------------------><br />