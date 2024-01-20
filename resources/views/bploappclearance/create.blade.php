{{ Form::open(array('url' => 'bploappclearance')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ba_code', __('Pblo Application Form'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ba_code',$bfpapplications,$data->ba_code, array('class' => 'form-control select3','id'=>'ba_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('p_code', __('P Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('p_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('p_code', $data->p_code, array('class' => 'form-control','required'=>'required','id'=>'p_code')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brgy_code', __('Barangay No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('brgy_code', $data->brgy_code, array('class' => 'form-control','required'=>'required','id'=>'brgy_code')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ba_business_account_no', __('Business Account Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ba_business_account_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ba_business_account_no', $data->ba_business_account_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_app_code', __('Department Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_app_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_app_code', $data->ebac_app_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_app_year', __('Year Applied'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_app_year') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_app_year', $data->ebac_app_year, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_app_no', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_app_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_app_no', $data->ebac_app_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_transaction_no', __('Transaction No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_transaction_no') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_transaction_no', $data->ebac_transaction_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_environmental_fee', __('Environmental Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_environmental_fee') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_environmental_fee', $data->ebac_environmental_fee, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_is_paid', __('CTO Payment'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_is_paid') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_is_paid', $data->ebac_is_paid, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_issuance_date') }}</span>
                    <div class="form-icon-user">
                         {{ Form::date('ebac_issuance_date', $data->ebac_issuance_date, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ebac_remarks', __('Remarks or Additional Instruction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ebac_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ebac_remarks', $data->ebac_remarks, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
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
<script src="{{ asset('js/add_pbloappclearance.js') }}"></script>



