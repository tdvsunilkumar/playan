{{Form::open(array('name'=>'forms','url'=>'rptmachinery/storemachineappraisal','method'=>'post','id'=>'storelandappraisal'))}}
 {{ Form::hidden('id',(isset($landAppraisal->id))?$landAppraisal->id:'', array('id' => 'id')) }}
 {{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                                <h4 class="modal-title">Machine Description & Appraisal Record Update</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_description',__("Description"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_description',(isset($landAppraisal->rpma_description))?$landAppraisal->rpma_description:'',array('class'=>'form-control rpma_description','placeholder'=>'Description'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_description"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_brand_model',__("Brand & Model"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_brand_model',(isset($landAppraisal->rpma_brand_model))?$landAppraisal->rpma_brand_model:'',array('class'=>'form-control rpma_brand_model','placeholder'=>'Brand & Model'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_brand_model"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                         <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_capacity_hp',__("Capacity/HP"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_capacity_hp',(isset($landAppraisal->rpma_capacity_hp))?$landAppraisal->rpma_capacity_hp:'',array('class'=>'form-control rpma_capacity_hp','placeholder'=>'Capacity/HP'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_capacity_hp"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_date_acquired',__("Date Acquired"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::date('rpma_date_acquired',(isset($landAppraisal->rpma_date_acquired))?$landAppraisal->rpma_date_acquired:'',array('class'=>'form-control rpma_date_acquired','placeholder'=>'Total Floors'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_date_acquired"></span>
                                </div>
                            </div>
                           
                        </div><br /> 

                         <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_condition',__("Condition"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_condition',(isset($landAppraisal->rpma_condition))?$landAppraisal->rpma_condition:'',array('class'=>'form-control rpma_condition','placeholder'=>'Condition'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_condition"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_estimated_life',__("Estimated Life"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_estimated_life',(isset($landAppraisal->rpma_estimated_life))?$landAppraisal->rpma_estimated_life:'',array('class'=>'form-control rpma_estimated_life decimalvalue','placeholder'=>'Estimated Life'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_estimated_life"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                        

                        
                             <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_remaining_life',__("Remaining Life"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_remaining_life',(isset($landAppraisal->rpma_remaining_life))?$landAppraisal->rpma_remaining_life:'',array('class'=>'form-control decimalvalue rpma_remaining_life','placeholder'=>'Remaining Life'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_remaining_life"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_date_installed',__("Date Installed"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::date('rpma_date_installed',(isset($landAppraisal->rpma_date_installed))?$landAppraisal->rpma_date_installed:'',array('class'=>'form-control rpma_date_installed','placeholder'=>'Total Floors'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_date_installed"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_date_operated',__("Date Operated"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::date('rpma_date_operated',(isset($landAppraisal->rpma_date_operated))?$landAppraisal->rpma_date_operated:'',array('class'=>'form-control rpma_date_operated','placeholder'=>'Floor No.'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_date_operated"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_remarks',__("Remarks"),['class'=>'form-label'])}}<!-- <span class="text-danger">*</span> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_remarks',(isset($landAppraisal->rpma_remarks))?$landAppraisal->rpma_remarks:'',array('class'=>'form-control rpma_remarks','placeholder'=>'Remarks'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_remarks"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                        
                       

                        <div class="row">
                            <div class="accordion accordion-flush" id="accordionFlushExample4">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingfour">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfour">
                     <h6 class="sub-title accordiantitle">{{__('Machine Appraisal Details')}}</h6>
                 </button>
             </h6>
             <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                <div class="basicinfodiv">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_appr_no_units',__("No. of Unit(s)"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::number('rpma_appr_no_units',(isset($landAppraisal->rpma_appr_no_units))?$landAppraisal->rpma_appr_no_units:'',array('class'=>'form-control rpma_appr_no_units calclulatebasemarketvalueandmarketvalue','placeholder'=>'No. of Unit(s)'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_appr_no_units"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_acquisition_cost',__("Acquisition Cost"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_acquisition_cost',(isset($landAppraisal->rpma_acquisition_cost))?$landAppraisal->rpma_acquisition_cost:'',array('class'=>'form-control rpma_acquisition_cost decimalvalue calclulatebasemarketvalueandmarketvalue','placeholder'=>'Acquisition Cost'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_acquisition_cost"></span>
                                </div>
                            </div>
           </div><br />
       </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_freight_cost',__("Freight Cost"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_freight_cost',(isset($landAppraisal->rpma_freight_cost))?$landAppraisal->rpma_freight_cost:'',array('class'=>'form-control rpma_freight_cost decimalvalue calclulatebasemarketvalueandmarketvalue','placeholder'=>'Freight Cost'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_freight_cost"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_insurance_cost',__("Insurance Cost"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_insurance_cost',(isset($landAppraisal->rpma_insurance_cost))?$landAppraisal->rpma_insurance_cost:'',array('class'=>'form-control rpma_insurance_cost decimalvalue calclulatebasemarketvalueandmarketvalue','placeholder'=>'Insurance Cost'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_insurance_cost"></span>
                                </div>
                            </div>
           </div><br />
       </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_installation_cost',__("Installation Cost"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_installation_cost',(isset($landAppraisal->rpma_installation_cost))?$landAppraisal->rpma_installation_cost:'',array('class'=>'form-control decimalvalue rpma_installation_cost calclulatebasemarketvalueandmarketvalue','placeholder'=>'Installation Cost'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_installation_cost"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_other_cost',__("Other Cost"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_other_cost',(isset($landAppraisal->rpma_other_cost))?$landAppraisal->rpma_other_cost:'',array('class'=>'form-control decimalvalue calclulatebasemarketvalueandmarketvalue rpma_other_cost','placeholder'=>'Other Cost'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_other_cost"></span>
                                </div>
                            </div>
           </div><br />
       </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_base_market_value',__("Base Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_base_market_value',(isset($landAppraisal->rpma_base_market_value))?$landAppraisal->rpma_base_market_value:'',array('class'=>'form-control decimalvalue rpma_base_market_value','placeholder'=>'Base Market Value','readonly'=>'readonly'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_base_market_value"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_depreciation_rate',__("Depreciation Rate"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_depreciation_rate',(isset($landAppraisal->rpma_depreciation_rate))?$landAppraisal->rpma_depreciation_rate:'',array('class'=>'form-control calclulatebasemarketvalueandmarketvalue rpma_depreciation_rate decimalvalue','placeholder'=>'Depreciation Rate'))}}
                                        <input type="hidden" name="pc_class_code" value="{{(isset($landAppraisal->pc_class_code))?$landAppraisal->pc_class_code:''}}">
                                        <input type="hidden" name="pau_actual_use_code" value="{{(isset($landAppraisal->pau_actual_use_code))?$landAppraisal->pau_actual_use_code:''}}">
                                        <input type="hidden" name="al_assessment_level" value="{{(isset($landAppraisal->al_assessment_level))?$landAppraisal->al_assessment_level:''}}">
                                        <input type="hidden" name="rpm_assessed_value" value="{{(isset($landAppraisal->rpm_assessed_value))?$landAppraisal->rpm_assessed_value:''}}">
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_depreciation_rate"></span>
                                </div>
                            </div>
           </div><br />
       </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_depreciation',__("Depreciation"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_depreciation',(isset($landAppraisal->rpma_depreciation))?$landAppraisal->rpma_depreciation:'',array('class'=>'form-control rpma_depreciation decimalvalue','placeholder'=>'Depreciation','readonly' => 'readonly'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_depreciation"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpma_market_value',__("Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpma_market_value',(isset($landAppraisal->rpma_market_value))?$landAppraisal->rpma_market_value:'',array('class'=>'form-control rpma_market_value decimalvalue','placeholder'=>'Market Value','readonly'=>'readonly'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_rpma_market_value"></span>
                                </div>
                            </div>
           </div>
       </div>
                    </div>
                </div>
             </div>

                
          </div>
       </div>
                          
                        </div><br />
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
                                <button class="btn btn-primary" id="saveLandAppraisalDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                            {{Form::close()}}
                            