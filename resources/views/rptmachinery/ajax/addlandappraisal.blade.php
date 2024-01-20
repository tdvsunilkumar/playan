{{Form::open(array('name'=>'forms','url'=>'rptproperty/storelandappraisal','method'=>'post','id'=>'storelandappraisal'))}}
 {{ Form::hidden('id',(isset($landAppraisal->id))?$landAppraisal->id:'', array('id' => 'id')) }}
 {{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                                <h4 class="modal-title">Land Apraisal Update</h4>
                                <a class="close closeLandAppraisalModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('pc_class_code',__("Classification"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('pc_class_code',$arrPropertyClasses,(isset($landAppraisal->pc_class_code))?$landAppraisal->pc_class_code:'',array('class'=>'form-control pc_class_code'))}}

                                        {{ Form::hidden('pc_class_description',(isset($landAppraisal->pc_class_description))?$landAppraisal->pc_class_description:'', array('class' => 'pc_class_description')) }}
                                        
                                        
                                    </div>
                                    <span class="validate-err" id="err_pc_class_code"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('ps_subclass_code',__("Subclass"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('ps_subclass_code',$arrSubClassesList,(isset($landAppraisal->ps_subclass_code))?$landAppraisal->ps_subclass_code:'',array('class'=>'form-control ps_subclass_code','placeholder'=>''))}}

                                        {{ Form::hidden('ps_subclass_desc',(isset($landAppraisal->ps_subclass_desc))?$landAppraisal->ps_subclass_desc:'', array('class' => 'ps_subclass_desc')) }}

                                        
                                    </div>
                                    <span class="validate-err" id="err_ps_subclass_code"></span>
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

                                        {{ Form::hidden('pau_actual_use_desc',(isset($landAppraisal->pau_actual_use_desc))?$landAppraisal->pau_actual_use_desc:'', array('class' => 'pau_actual_use_desc')) }}
                                        
                                    </div>
                                    <span class="validate-err" id="err_pau_actual_use_code"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('lav_strip_unit_value',__("Stripping"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('land_stripping_id',$arrLandStrippingCodes,'',array('class'=>'form-control land_stripping_id'))}}
                                        {{ Form::hidden('rls_code',(isset($landAppraisal->rls_code))?$landAppraisal->rls_code:'', array('class' => 'rls_code')) }}

                                        {{ Form::hidden('lav_strip_unit_value',(isset($landAppraisal->lav_strip_unit_value))?$landAppraisal->lav_strip_unit_value:'', array('class' => 'lav_strip_unit_value')) }}

                                        {{ Form::hidden('rls_percent',(isset($landAppraisal->rls_percent))?$landAppraisal->rls_percent:'', array('class' => 'rls_percent')) }}
                                       
                                    </div>
                                    <span class="validate-err" id="err_land_stripping_id"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        
                            <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpa_total_land_area',__("Land Area"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpa_total_land_area',(isset($landAppraisal->rpa_total_land_area))?$landAppraisal->rpa_total_land_area:'',array('class'=>'form-control rpa_total_land_area','placeholder'=>'Total Land Area','id'=>'rpa_total_land_area'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpa_total_land_area"></span>
                                </div>
                            </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('lav_unit_measure',__("In"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('lav_unit_measure',$landUnitMeaure,(isset($landAppraisal->lav_unit_measure))?$landAppraisal->lav_unit_measure:'',array('class'=>'form-control lav_unit_measure','readonly'=>'readonly'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_lav_unit_measure"></span>
                                </div>
                            </div>
                        </div><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('lav_unit_value',__("Unit Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('lav_unit_value',(isset($landAppraisal->lav_unit_value))?$landAppraisal->lav_unit_value:'',array('class'=>'form-control lav_unit_value','readonly'=>'readonly'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_lav_unit_value"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpa_base_market_value',__("Base Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpa_base_market_value',(isset($landAppraisal->rpa_base_market_value))?$landAppraisal->rpa_base_market_value:'',array('class'=>'form-control rpa_base_market_value','placeholder'=>'Base Market Value','readonly'=>'readonly'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpa_base_market_value"></span>
                                </div>
                            </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('al_assessment_level',__("Assesment Level"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('al_assessment_level',(isset($landAppraisal->al_assessment_level))?$landAppraisal->al_assessment_level:'',array('class'=>'form-control al_assessment_level','placeholder'=>'Assessment Level','readonly'=>'readonly'))}}
                                        <input type="hidden" name="al_minimum_unit_value" class="al_minimum_unit_value" value="{{ (isset($landAppraisal->al_minimum_unit_value))?$landAppraisal->al_minimum_unit_value:'' }}">
                                        <input type="hidden" name="al_maximum_unit_value" class="al_maximum_unit_value" value="{{ (isset($landAppraisal->al_maximum_unit_value))?$landAppraisal->al_maximum_unit_value:'' }}">
                                        <input type="hidden" name="al_assessment_level_hidden" value="{{ (isset($landAppraisal->al_assessment_level_hidden))?$landAppraisal->al_assessment_level_hidden:'' }}" class="al_assessment_level_hidden">
                                        {{Form::hidden('rpa_adjusted_market_value',(isset($landAppraisal->rpa_adjusted_market_value))?$landAppraisal->rpa_adjusted_market_value:'',array('class'=>'form-control rpa_adjusted_market_value','placeholder'=>'Adjusted Market Value'))}}
                                        {{Form::hidden('rpa_assessed_value',(isset($landAppraisal->rpa_assessed_value))?$landAppraisal->rpa_assessed_value:'',array('class'=>'form-control rpa_assessed_value','placeholder'=>'Assessed Value'))}}
                                        
                                    </div>
                                    <span class="validate-err" id="err_al_assessment_level"></span>
                                </div>
                            </div>
                        </div><br />

                        <!-- <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpa_adjusted_market_value',__("Adjusted Market Value"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                       
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpa_adjusted_market_value"></span>
                                </div>
                            </div>
                           
                        </div><br /> -->

                       <!--  <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpa_assessed_value',__("Assessed Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpa_assessed_value"></span>
                                </div>
                            </div>
                           
                        </div><br /> -->
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn closeLandAppraisalModal" mid=""  type="edit">Close</a>
                                <button class="btn btn-primary" id="saveLandAppraisalDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                            {{Form::close()}}