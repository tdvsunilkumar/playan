{{Form::open(array('name'=>'forms','url'=>'rptproperty/storeplantstreesadjustmentfactor','method'=>'post','id'=>'storeplantstreesadjustmentfactor'))}}
 {{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
 {{ Form::hidden('land_appraisal_id',$ladnAppraisalId, array('id' => 'id')) }}
 {{ Form::hidden('land_appraisal_session_id',$landAppraisalSessionId, array('id' => 'session_id')) }}
 {{ Form::hidden('id',(isset($rptPlantTreeAppraisal->id))?$rptPlantTreeAppraisal->id:'', array('id' => 'id')) }}
 {{ Form::hidden('session_id',$sessionId, array('id' => 'session_id')) }}
<div class="modal-header">
                <h4 class="modal-title">Plants/Trees Adjustment Factors</h4>
                <a class="close closePlantsTreeFormModel" data-dismiss="modal" aria-hidden="true" type="add" mid="">X</a>
                </div><div class="container"></div>
                <div class="modal-body">
                    <div class="basicinfodiv">
                       
                        <div class="row">
                            
                            <div class="col-lg-4 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rp_planttree_code',__("Plant/Tree"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('rp_planttree_code',$arrPlantTreeCode,(isset($rptPlantTreeAppraisal->rp_planttree_code))?$rptPlantTreeAppraisal->rp_planttree_code:'',array('class'=>'form-control rp_planttree_code'))}}
                                        <input type="hidden" name="plant_tree_revision_year_code" value="{{ isset($propertyCode->rvy_revision_year_id)?$propertyCode->rvy_revision_year_id:''}}">
                                        <input type="hidden" value="{{ isset($propertyCode->rvy_revision_year)?$propertyCode->rvy_revision_year:''}}" name="plant_tree_revision_year">
                                        <input type="hidden" name="pt_ptrees_description" value="{{ (isset($rptPlantTreeAppraisal->pt_ptrees_description))?$rptPlantTreeAppraisal->pt_ptrees_description:'' }}">
                                    </div>
                                    <span class="validate-err" id="err_rp_planttree_code"></span>
                                    <!-- <span class="validate-err" id="err_plant_tree_revision_year_code"></span> -->
                                </div>
                            </div>
                           
                        </div><br />
                       

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('plants_tree_pc_class_code',__("Class"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('plants_tree_pc_class_code',$arrPropertyClasses,(isset($rptPlantTreeAppraisal->pc_class_code))?$rptPlantTreeAppraisal->pc_class_code:'',array('class'=>'form-control plants_tree_pc_class_code select3'))}}
                                        <input type="hidden" name="pc_class_description" value="{{ (isset($rptPlantTreeAppraisal->pc_class_description))?$rptPlantTreeAppraisal->pc_class_description:'' }}">
                                        
                                    </div>
                                    <span class="validate-err" id="err_plants_tree_pc_class_code"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('plants_tree_ps_subclass_code',__("Sub Class"),['class'=>'form-label '])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('plants_tree_ps_subclass_code',$arrSubClassesList,(isset($rptPlantTreeAppraisal->ps_subclass_code))?$rptPlantTreeAppraisal->ps_subclass_code:'',array('class'=>'form-control plants_tree_ps_subclass_code'))}}
                                        <input type="hidden" name="ps_subclass_desc" value="{{ (isset($rptPlantTreeAppraisal->ps_subclass_desc))?$rptPlantTreeAppraisal->ps_subclass_desc:'' }}">
                                    </div>
                                    <span class="validate-err" id="err_plants_tree_ps_subclass_code"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_total_area_planted',__("Total Area Planted"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpta_total_area_planted',(isset($rptPlantTreeAppraisal->rpta_total_area_planted))?$rptPlantTreeAppraisal->rpta_total_area_planted:'',array('class'=>'form-control rpta_total_area_planted','placeholder'=>'Total Area Planted'))}}
                                        
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_total_area_planted"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        
                            

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_fruit_bearing_productive',__("Fruit Bearing"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpta_fruit_bearing_productive',(isset($rptPlantTreeAppraisal->rpta_fruit_bearing_productive))?$rptPlantTreeAppraisal->rpta_fruit_bearing_productive:'',array('class'=>'form-control rpta_fruit_bearing_productive','placeholder'=>'Fruit Bearing','id'=>'rpta_fruit_bearing_productive'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_fruit_bearing_productive"></span>
                                </div>
                            </div>
                           
                        </div><br />
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_non_fruit_bearing',__("Non-Fruit Bearing"),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpta_non_fruit_bearing',(isset($rptPlantTreeAppraisal->rpta_non_fruit_bearing))?$rptPlantTreeAppraisal->rpta_non_fruit_bearing:'',array('class'=>'form-control rpta_non_fruit_bearing','placeholder'=>'Non-Fruit Bearing','id'=>'rpa_total_land_area'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_non_fruit_bearing"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_date_planted',__("Age[Year]"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('rpta_date_planted',(isset($rptPlantTreeAppraisal->rpta_date_planted))?$rptPlantTreeAppraisal->rpta_date_planted:'',array('class'=>'form-control yearpicker rpta_date_planted','placeholder'=>'Age','id'=>'rpta_date_planted'))}}
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_date_planted"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_unit_value',__("Unit Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpta_unit_value',(isset($rptPlantTreeAppraisal->rpta_unit_value))?$rptPlantTreeAppraisal->rpta_unit_value:'',array('class'=>'form-control rpta_unit_value','placeholder'=>'Unit Value','id'=>'rpta_unit_value','readonly'=>'readonly'))}}
                                        <div class="currency-sign"><span>Php</span></div>
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_unit_value"></span>
                                </div>
                            </div>
                           
                        </div><br />

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_market_value',__("Market Value"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user currency">
                                        {{Form::text('rpta_market_value',(isset($rptPlantTreeAppraisal->rpta_market_value))?$rptPlantTreeAppraisal->rpta_market_value:'',array('class'=>'form-control rpta_market_value','placeholder'=>'Market Value','id'=>'rpta_market_value'))}}
                                        <div class="currency-sign"><span>Php</span></div>
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_market_value"></span>
                                </div>
                            </div>
                           
                        
                        
                    </div><br />

                    <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('rpta_taxable',__("Tax Exempted"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox('rpta_taxable', '1', (isset($rptPlantTreeAppraisal->rpta_taxable) && $rptPlantTreeAppraisal->rpta_taxable)?true:false, array('id'=>'pau_with_land_stripping','class'=>'form-check-input uc_usage_building code')) }}
                                                    {{ Form::label('Exempted', __('Exempted'),['class'=>'form-label']) }}
                                                </div>
                                        
                                       
                                    </div>
                                    <span class="validate-err" id="err_rpta_market_value"></span>
                                </div>
                            </div>
                           
                        
                        
                    </div>
                   
                </div>

                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn closePlantsTreeFormModel" mid="" type="add">Close</a>
                    <button type="submit" class="btn btn-primary saveLandAppRaisalDetails">Save Changes</button>
                    
                </div>
                {{Form::close()}}
                <script type="text/javascript">
                    $("#rpta_date_planted").yearpicker({
                        year:'{{(isset($rptPlantTreeAppraisal->rpta_date_planted))?$rptPlantTreeAppraisal->rpta_date_planted:''}}'
                    })
                </script>