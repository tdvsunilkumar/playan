{{ Form::open(array('url' => 'locality','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('mun_no', __('Locality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('mun_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('mun_no',$arrMunCode,$data->mun_no, array('class' => 'form-control select3','id'=>'mun_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_local_code"></span>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_local_name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_local_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_local_name', $data->loc_local_name, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_local_name"></span>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_address', __('Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_address') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_address', $data->loc_address, array('class' => 'form-control','maxlength'=>'100','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_address"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_telephone_no', __('Telephone No(s)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_telephone_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_telephone_no', $data->loc_telephone_no, array('class' => 'form-control','maxlength'=>'20','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_telephone_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_fax_no', __('Fax no(s)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_fax_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_fax_no', $data->loc_fax_no, array('class' => 'form-control','maxlength'=>'20','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_fax_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_mayor_id', __('Mayor'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_mayor_id') }}</span>
                                <div class="form-icon-user">
                                     <div class="form-icon-user">
                                    {{ Form::select('loc_mayor_id',$arrHrEmpCode,$data->loc_mayor_id, array('class' => 'form-control select3','id'=>'loc_mayor_id','required'=>'required')) }}
                                    
                                </div>
                                </div>
                                <span class="validate-err" id="err_loc_mayor"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_administrator_id', __('Administrator'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_administrator_id') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::select('loc_administrator_id',$arrHrEmpCode,$data->loc_administrator_id, array('class' => 'form-control select3','id'=>'loc_administrator_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_administrator_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_budget_officer_id', __('Budget Officer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_budget_officer_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('loc_budget_officer_id',$arrHrEmpCode,$data->loc_budget_officer_id, array('class' => 'form-control select3','id'=>'loc_budget_officer_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_budget_officer_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_budget_officer_position', __('Budget Officer Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_budget_officer_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_budget_officer_position', $data->loc_budget_officer_position, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_budget_officer_position"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_treasurer_id', __('Treasurer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_treasurer_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('loc_treasurer_id',$arrHrEmpCode,$data->loc_treasurer_id, array('class' => 'form-control select3','id'=>'loc_treasurer_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_treasurer_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_treasurer_position', __('Treasurer Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_treasurer_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_treasurer_position', $data->loc_treasurer_position, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_treasurer_position"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_chief_land_id', __('Chief, Land Tax'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_chief_land_id') }}</span>
                                <div class="form-icon-user">
                                      {{ Form::select('loc_chief_land_id',$arrHrEmpCode,$data->loc_chief_land_id, array('class' => 'form-control select3','id'=>'loc_chief_land_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_chief_land_tax"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_chief_land_tax_position', __('Chief, Land Tax Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_chief_land_tax_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_chief_land_tax_position', $data->loc_chief_land_tax_position, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_chief_land_tax_position"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_assessor_id', __('Assessor Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_assessor_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('loc_assessor_id',$arrHrEmpCode,$data->loc_assessor_id, array('class' => 'form-control select3','id'=>'loc_assessor_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_assessor_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_assessor_position', __('Assessor Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_assessor_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_assessor_position', $data->loc_assessor_position, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_assessor_position"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_assessor_assistant_id', __('Assessor Assistant Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_assessor_assistant_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('loc_assessor_assistant_id',$arrHrEmpCode,$data->loc_assessor_assistant_id, array('class' => 'form-control select3','id'=>'loc_assessor_assistant_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_assessor_assistant_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('loc_assessor_assistant_position', __('Assessor Assistant Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('loc_assessor_assistant_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('loc_assessor_assistant_position', $data->loc_assessor_assistant_position, array('class' => 'form-control','maxlength'=>'75','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_assessor_assistant_position"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('asment_id', __('Fire Protection Assessment Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('asment_id') }}</span>
                                <div class="form-icon-user">
                                     @php 
                                        $arrAssessmentType=config('constants.arrAssessmentType');
                                     @endphp
                                     {{ Form::select('asment_id',$arrAssessmentType,$data->asment_id, array('class' => 'form-control select3','id'=>'asment_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_loc_assessor_assistant_name"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                    <!-- <div class="row">

                        <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group col-md-1">
                                {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                            </div>
                            <div class="form-check form-check-inline form-group col-md-1">
                                {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                                {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
                            </div>
                        </div>
                    </div> -->
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <input type="submit" name="submit" onclick="return check()" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
                    </div>
            </div>
    {{Form::close()}}
    
   <!-- <script src="{{ asset('js/ajax_validation.js') }}"></script>  -->
   <script src="{{ asset('js/add_locality.js') }}"></script>