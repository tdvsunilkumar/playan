{{Form::open(array('name'=>'forms','url'=>'rptbuilding/storefloorvalue','method'=>'post','id'=>'storelandappraisal'))}}
 {{ Form::hidden('id',(isset($landAppraisal->id))?$landAppraisal->id:'', array('id' => 'id')) }}
 {{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                                <h4 class="modal-title">Add/Update Floor Value</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_floor_no',__("Floor No(s)"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::number('rpbfv_floor_no',(isset($landAppraisal->rpbfv_floor_no))?$landAppraisal->rpbfv_floor_no:'',array('class'=>'form-control rpbfv_floor_no','placeholder'=>'Floor No.','readonly'=>'readonly'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_floor_no"></span>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::number('rpbfv_total_floor',(isset($landAppraisal->rpbfv_total_floor))?$landAppraisal->rpbfv_total_floor:'',array('class'=>'form-control rpbfv_total_floor','placeholder'=>'Total Floors','readonly'=>'readonly'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_total_floor"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('bt_building_type_code',__("Struc. Type"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('bt_building_type_code',$arrPropertyTypes,(isset($landAppraisal->bt_building_type_code))?$landAppraisal->bt_building_type_code:'',array('class'=>'form-control bt_building_type_code','placeholder'=>''))}}
                                        <input type="hidden" class="bt_building_type_code_desc" name="bt_building_type_code_desc" value="{{ (isset($landAppraisal->bt_building_type_code_desc))?$landAppraisal->bt_building_type_code_desc:'' }}">
                                        
                                    </div>
                                    <span class="validate-err" id="err_bt_building_type_code"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('pau_actual_use_code',__("Actual Use"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('pau_actual_use_code',$arrActualUsesCodes,(isset($landAppraisal->pau_actual_use_code))?$landAppraisal->pau_actual_use_code:'',array('class'=>'form-control pau_actual_use_code'))}}
                                        <input type="hidden" class="pau_actual_use_code_desc" name="pau_actual_use_code_desc" value="{{ (isset($landAppraisal->pau_actual_use_code_desc))?$landAppraisal->pau_actual_use_code_desc:'' }}">
                                    </div>
                                    <span class="validate-err" id="err_pau_actual_use_code"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        

                        
                            <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_floor_area',__("Area (Sq. m.)"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpbfv_floor_area',(isset($landAppraisal->rpbfv_floor_area))?$landAppraisal->rpbfv_floor_area:'',array('class'=>'form-control rpbfv_floor_area decimalvalue','placeholder'=>'Area','id'=>'rpbfv_floor_area'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_floor_area"></span>
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
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpbfv_floor_unit_value',(isset($landAppraisal->rpbfv_floor_unit_value))?$landAppraisal->rpbfv_floor_unit_value:'',array('class'=>'form-control rpbfv_floor_unit_value','readonly'=>'readonly','placeholder' => 'Unit Value'))}}
                                       <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_floor_unit_value"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_floor_base_market_value',__("Base Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpbfv_floor_base_market_value',(isset($landAppraisal->rpbfv_floor_base_market_value))?$landAppraisal->rpbfv_floor_base_market_value:'',array('class'=>'form-control rpbfv_floor_base_market_value','placeholder'=>'Base Market Value','readonly'=>'readonly'))}}
                                       <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                    <span class="validate-err" id="err_rpa_base_market_value"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_floor_additional_value',__("Additional Value"),['class'=>'form-label'])}}

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpbfv_floor_additional_value',(isset($landAppraisal->rpbfv_floor_additional_value))?$landAppraisal->rpbfv_floor_additional_value:'',array('class'=>'form-control decimalvalue rpbfv_floor_additional_value'))}}
                                       <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_floor_additional_value"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_floor_adjustment_value',__("Adjustment Value"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpbfv_floor_adjustment_value',(isset($landAppraisal->rpbfv_floor_adjustment_value))?$landAppraisal->rpbfv_floor_adjustment_value:'',array('class'=>'form-control rpbfv_floor_adjustment_value decimalvalue','placeholder'=>'Adjustment Value'))}}
                                       <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_floor_adjustment_value"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpbfv_total_floor_market_value',__("Total Market Value"),['class'=>'form-label','style'=>'margin-left:-4px;'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpbfv_total_floor_market_value',(isset($landAppraisal->rpbfv_total_floor_market_value))?$landAppraisal->rpbfv_total_floor_market_value:'',array('class'=>'form-control rpbfv_total_floor_market_value','placeholder'=>'Total Market Value','id'=>'rpbfv_total_floor_market_value','readonly'=>'readonly'))}}
                                        
                                        <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                    <span class="validate-err" id="err_rpbfv_total_floor_market_value"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('al_assessment_level',__("Assessment Level"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                          <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('al_assessment_level',(isset($landAppraisal->al_assessment_level))?$landAppraisal->al_assessment_level:'',array('class'=>'form-control al_assessment_level decimalvalue','placeholder'=>'Assessment Level','readonly'=>'readonly'))}}
                                        <input type="hidden" name="rpb_assessed_value"  value="{{(isset($landAppraisal->rpb_assessed_value))?$landAppraisal->rpb_assessed_value:''}}">
                                    </div>
                                    <span class="validate-err" id="err_al_assessment_level"></span>
                                </div>
                            </div>
                        </div><br />

                        <div class="row" id="additionalItemsForPreviousOwner">
                            <div class="accordion accordion-flush" id="accordionFlushExample4">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingfour">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfour">
                     <h6 class="sub-title accordiantitle">{{__('Additional Items')}}</h6>
                 </button>
             </h6>

                <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">

                    <div class="row field-requirement-details-status">
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            {{Form::label('bei_extra_item_code',__('Item Code'),['class'=>'form-label'])}}

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            {{Form::label('bei_extra_item_desc',__('Amount'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('rpbfai_total_area',__('Area'),['class'=>'form-label numeric'])}}
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-2">

                            <span class="addMoreAdditionalItemsForFloorValue btn btn-primary" id="addMoreAdditionalItemsForFloorValue" > <i class="ti-plus"></i> </span>
                        </div>
                    </div>
                    <span class="busiactivityDetails activity-details" id="busiactivityDetails">
                        <span class="validate-err" id="err_activitydetailserror"></span>
                        @php $i=0; @endphp
                        @if(!empty($landAppraisal->additionalItems))
                        @foreach($landAppraisal->additionalItems as $key=>$val)
                        @php $val = (object)$val @endphp
                        
                            <div class="row removeactivitydata pt10">
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            
                                        {{ Form::select('bei_extra_item_code[]',$addiItems, $val->bei_extra_item_code, array('class' => 'form-control select3 naofbussi bei_extra_item_code','id'=>'bei_extra_item_code'.$i)) }}
                                        
                                        <input type="hidden" name="rp_code[]" value="{{ (isset($propertyCode->id))?$propertyCode->id:NULL }}">
                                        <input type="hidden" name="rpbfv_code[]" value="{{ (isset($landAppraisal->id))?$landAppraisal->id:NULL}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                           {{Form::text('bei_extra_item_desc[]',$val->bei_extra_item_desc,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user" >
                                          {{Form::text('rpbfai_total_area[]',$val->rpbfai_total_area,array('class'=>'form-control',))}}
                                        </div>
                                    </div>
                                </div>

                               

                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group"><button type="button" class="btn btn-danger cancelMoreAdditionalItemsForFloorValue"><i class="ti-trash" style="padding-left:5px;padding-right: 5px;"></i></button></div>

                                    </div>
                                
                                @php $i++; @endphp
                            </div>
                            
                        @endforeach
                        @endif
                    </span>
                </div>
          </div>
       </div>
                          
                        </div><br />
                               
                               
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
                                <button type="submit" class="btn btn-primary" id="saveLandAppraisalDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                            {{Form::close()}}
                            <div id="hidenactivityHtml" class="hide">
    <div class="removeactivitydata row pt10">
        <div class="col-lg-5 col-md-5 col-sm-5">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            
                                        {{ Form::select('bei_extra_item_code[]',$addiItems, '', array('class' => 'form-control naofbussi bei_extra_item_code','id'=>'bei_extra_item_code'.$i,'placeholder' => 'Select Item')) }}
                                      
                                        <input type="hidden" name="rp_code[]" value="{{ (isset($propertyCode->id))?$propertyCode->id:NULL }}">
                                        <input type="hidden" name="rpbfv_code[]" value="{{ (isset($landAppraisal->id))?$landAppraisal->id:NULL}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                           {{Form::text('bei_extra_item_desc[]','',array('class'=>'form-control bei_extra_item_desc','Placeholder' => 'Item Description'))}}
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user" >
                                          {{Form::text('rpbfai_total_area[]','',array('class'=>'form-control decimalvalue','placeholder'=>'Area'))}}
                                        </div>
                                    </div>
                                </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group"><button type="button" class="btn btn-danger cancelMoreAdditionalItemsForFloorValue"><i class="ti-trash" style="padding-left:5px;padding-right: 5px;"></i></button></div>
        </div>
    </div>
</div>

