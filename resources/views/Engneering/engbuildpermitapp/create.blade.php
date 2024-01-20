{{ Form::open(array('url' => 'engbldgpermitapp','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_mun_no', __('Muncipal'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_mun_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebpa_mun_no',$muncipality,$data->ebpa_mun_no, array('class' => 'form-control select3 ','id'=>'ebpa_mun_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_mun_no"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_application_no', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_application_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_application_no',$data->ebpa_application_no, array('class' => 'form-control ','id'=>'ebpa_application_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_application_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_permit_no', __('Permit No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_permit_no',$data->ebpa_permit_no, array('class' => 'form-control','id'=>'ebpa_permit_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_permit_no"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('eba_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('eba_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('eba_id',$arrapptype,$data->eba_id, array('class' => 'form-control select3 ','id'=>'eba_id','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_application_date', __('Date Of Application'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_application_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('ebpa_application_date',$data->ebpa_application_date, array('class' => 'form-control','id'=>'ebpa_application_date','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_application_date"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_issued_date', __('Date Issued'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_issued_date') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::date('ebpa_issued_date',$data->ebpa_issued_date, array('class' => 'form-control','id'=>'ebpa_issued_date','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_issued_date"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_owner_last_name', __('Last Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_owner_last_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_owner_last_name',$data->ebpa_owner_last_name, array('class' => 'form-control','id'=>'ebpa_owner_last_name','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_owner_last_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_owner_first_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_owner_first_name',$data->ebpa_owner_first_name, array('class' => 'form-control','id'=>'ebpa_owner_first_name','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_owner_first_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_owner_mid_name', __('Mid Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_owner_mid_name',$data->ebpa_owner_mid_name, array('class' => 'form-control','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_owner_mid_name"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_owner_suffix_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_owner_suffix_name',$data->ebpa_owner_suffix_name, array('class' => 'form-control','id'=>'ebpa_owner_suffix_name')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_tax_acct_no', __('Tax Acct No'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_tax_acct_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_tax_acct_no',$data->ebpa_tax_acct_no, array('class' => 'form-control','id'=>'ebpa_tax_acct_no')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_tax_acct_no"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_form_of_own', __('Form of Ownership'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_form_of_own') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_form_of_own',$data->ebpa_form_of_own, array('class' => 'form-control','id'=>'ebpa_form_of_own')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_form_of_own"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_economic_act', __('Main Economic Activity'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_economic_act') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_economic_act',$data->ebpa_economic_act, array('class' => 'form-control','id'=>'ebpa_economic_act')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_economic_act"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_address_house_lot_no', __('House/Lot No'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_address_house_lot_no') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_address_house_lot_no',$data->ebpa_address_house_lot_no, array('class' => 'form-control','id'=>'ebpa_address_house_lot_no')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_address_house_lot_no"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_address_street_name', __('Street Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_address_street_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_address_street_name',$data->ebpa_address_street_name, array('class' => 'form-control','id'=>'ebpa_address_street_name')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_address_street_name"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ebpa_address_subdivision') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_address_subdivision',$data->ebpa_address_subdivision, array('class' => 'form-control','id'=>'ebpa_address_subdivision')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_address_subdivision"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('brgy_code', __('Barangay'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('brgy_code',$arrBarangay,$data->brgy_code, array('class' => 'form-control select3 ','id'=>'brgy_code','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_location', __('Location of Construction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_location') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebpa_location',$muncipality,$data->ebpa_location, array('class' => 'form-control select3 ','id'=>'ebpa_location','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebs_id', __('Dropdown (Scope of work)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebs_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebs_id',$buildingscope,$data->ebs_id, array('class' => 'form-control select3 ','id'=>'ebs_id','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_scope_remarks', __('Other Remarks (scope)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_scope_remarks') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ebpa_scope_remarks',$data->ebpa_scope_remarks, array('class' => 'form-control ','id'=>'ebpa_scope_remarks','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('no_of_units', __('Number of Units'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('no_of_units') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('no_of_units',$data->no_of_units, array('class' => 'form-control ','id'=>'no_of_units')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebot_id', __('Radio Button (Use / Type of occupancy)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebot_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebot_id',$buildingOccupancytype,$data->ebot_id, array('class' => 'form-control select3 ','id'=>'ebot_id','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebost_id', __('Dropdown (Use / Type of occupancy)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebost_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ebost_id',$buildingOccupancysubtype,$data->ebost_id, array('class' => 'form-control select3 ','id'=>'ebost_id','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_occ_other_remarks', __('Other Remarks (Residential)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_occ_other_remarks') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('ebpa_occ_other_remarks',$data->ebpa_occ_other_remarks, array('class' => 'form-control ','id'=>'ebpa_occ_other_remarks','required'=>'required','rows'=>'2')) }}
                                </div>
                                 <span class="validate-err" id="err_ebpa_occ_other_remarks"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ebpa_bldg_official_name', __('Building Official Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('ebpa_bldg_official_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('ebpa_bldg_official_name',$data->ebpa_bldg_official_name, array('class' => 'form-control  ','id'=>'ebpa_bldg_official_name','required'=>'required','rows'=>'2')) }}
                                </div>
                                 <span class="validate-err" id="err_eba_id"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">   
                        
                      
                    </div>
                   
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
    {{Form::close()}}

 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script src="{{ asset('js/add_psicsubclass.js') }}"></script>  
 
           