
{{Form::open(array('name'=>'forms','url'=>'rptbuilding/storeBuildingfloorval','method'=>'post','id'=>'storefloorbuildval'))}}
<div class="modal-header testing">

                                <h4 class="modal-title">BUilding Floor Value Comparision And Description</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_floor_no',__("Floor No"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                 {{Form::text('rpbfv_floor_no','',array('class'=>'form-control rpbfv_floor_no','placeholder'=>'Enter Floor No','id'=>'rpbfv_floor_no'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpbfv_floor_no"></span>
                                        </div>
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_floor_area',__("Area(Sq. m."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                 {{Form::text('rpbfv_floor_area','',array('class'=>'form-control rpbfv_floor_area','placeholder'=>'Total Floor Area','id'=>'rpbfv_floor_area'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpbfv_floor_area"></span>
                                        </div>
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('base_market_value',__("Base Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::text('base_market_value','',array('class'=>'form-control base_market_value','id'=>'base_market_value'))}}
                                               
                                            </div>
                                            <span class="validate-err" id="err_base_market_value"></span>
                                        </div>
                                    </div>
                                     
                                </div><br /> 
                                 <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('bt_building_type_code',__("Stru Type"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                   {{Form::select('bt_building_type_code',$arrPropertyTypes,'',array('class'=>'form-control bt_building_type_code select3'))}}
                                            </div>
                                            <span class="validate-err" id="err_bt_building_type_code"></span>
                                        </div>
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('pau_actual_use_code',__("Actual Use"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                  {{Form::select('pau_actual_use_code',$rpt_building_actualuse,'',array('class'=>'form-control pau_actual_use_code select3','id'=>'pau_actual_use_code'))}}
                                            </div>
                                            <span class="validate-err" id="err_pau_actual_use_code"></span>
                                        </div>
                                    </div>
                                </div><br /> 
                                  <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_floor_unit_value',__("Unit Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::text('rpbfv_floor_unit_value','',array('class'=>'form-control rpbfv_floor_unit_value','id'=>'rpbfv_floor_unit_value'))}}
                                               
                                            </div>
                                            <span class="validate-err" id="err_lav_unit_value"></span>
                                        </div>
                                    </div>
                                      <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_floor_adjustment_value',__("Adjustment Value."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                 {{Form::text('rpbfv_floor_adjustment_value','',array('class'=>'form-control rpbfv_floor_adjustment_value','placeholder'=>'Adjustment Value','id'=>'rpbfv_floor_adjustment_value'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpbfv_floor_adjustment_value"></span>
                                        </div>
                                    </div>
                                </div>
                                  <div class="row" style="padding-top: 10px;">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('base_market_value',__("Base Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::text('base_market_value','',array('class'=>'form-control base_market_value'))}}
                                               
                                            </div>
                                            <span class="validate-err" id="err_base_market_value"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_floor_additional_value',__("Additional Value."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                 {{Form::text('rpbfv_floor_additional_value','',array('class'=>'form-control rpbfv_floor_additional_value','placeholder'=>'Additional Value','id'=>'rpbfv_floor_additional_value'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpbfv_floor_additional_value"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('rpbfv_total_floor_market_value',__("Total Market Value."),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <div class="form-icon-user"><!--(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:''-->
                                                 {{Form::text('rpbfv_total_floor_market_value','',array('class'=>'form-control rpbfv_total_floor_market_value','placeholder'=>'Total Market Value','id'=>'rpbfv_total_floor_market_value'))}}
                                            </div>
                                            <span class="validate-err" id="err_rpbfv_total_floor_market_value"></span>
                                        </div>
                                    </div>
                                </div>
                        <div class="row">
                          
                        </div><br />
                      
                        </div>
                        </div>
                        <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
                                <button class="btn btn-primary" id="buildingfloorvalbutton">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                        </div>
                    {{Form::close()}}