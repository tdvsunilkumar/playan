
{{ Form::open(array('url' => 'administrative/tax-libraries/type/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            
           
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_class_id', __('Tax Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_class_id',$arrTaxClasses,$data->tax_class_id, array('class' => 'form-control select3','id'=>'tax_class_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
           
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('type_code', __('Tax Type Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('type_code', $data->type_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_type_code"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_short_name', __('Short Name'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('tax_type_short_name', $data->tax_type_short_name, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>
            <div class="form-group col-md-8">
                {{ Form::label('tax_type_description', __('Tax Type Desc'),['class'=>'form-label']) }}
                <span class="text-danger">*</span>
                {!! Form::textarea('tax_type_description', $data->tax_type_description, ['class'=>'form-control','rows'=>'1','required'=>'required']) !!}
                <span class="validate-err" id="err_tax_type_description"></span>
            </div>
            <!-- <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_class_type_code', __('Tax Class Type Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('tax_class_type_code', $data->tax_class_type_code, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div> -->
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_category_id', __('Tax Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_category_id',$arrCategory,$data->tax_category_id, array('class' => 'form-control select3','id'=>'tax_category_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_category_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('column_no', __('Column No.'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('column_no', $data->column_no, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>

            

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tia_account_code', __('Reference'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('tia_account_code', $data->tia_account_code, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('top', __('TOP'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('top', $data->top, array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_is_annual', __('Annual'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_is_annual',$arrYesNo,$data->tax_type_is_annual, array('class' => 'form-control ','id'=>'tax_type_is_annual')) }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_with_surcharge', __('With Surcharge'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_with_surcharge',$arrYesNo,$data->tax_type_with_surcharge, array('class' => 'form-control ','id'=>'tax_type_with_surcharge')) }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_with_intererest', __('With Interest'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_with_intererest',$arrYesNo,$data->tax_type_with_intererest, array('class' => 'form-control ','id'=>'tax_type_with_intererest')) }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_is_fire_code_base', __('Fire Code Base'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_is_fire_code_base',$arrYesNo,$data->tax_type_is_fire_code_base, array('class' => 'form-control ','id'=>'tax_type_is_fire_code_base')) }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_with_engineering_fee', __('Engineering Fee'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_with_engineering_fee',$arrYesNo,$data->tax_type_with_engineering_fee, array('class' => 'form-control ','id'=>'tax_type_with_engineering_fee')) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_initial_amount', __('Initial Amount'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::number('tax_type_initial_amount', $data->tax_type_initial_amount, array('class' => 'form-control','step'=>'0.01')) }}
                    </div>
                </div>
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
