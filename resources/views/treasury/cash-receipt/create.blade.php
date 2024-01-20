{{ Form::open(array('url' => 'treasury/cash-receipt/store','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('fund_code_id', __('Fund Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('fund_code_id',
                        $fund_code,
                        $data->fund_code_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'fund_code_id',
                            )) }}
                </div>
                <span class="validate-err" id="err_fund_code_id"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('type_of_charge_id', __('Type of Charges'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('type_of_charge_id',
                        $type_of_charge,
                        $data->type_of_charge_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'type_of_charge_id',
                            )) }}
                </div>
                <span class="validate-err" id="err_type_of_charge_id"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::date('date',
                        $data->date, 
                        array(
                            'class' => 'form-control',
                            'id'=>'date'
                            )) }}
                </div>
                <span class="validate-err" id="err_date"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('gl_id', __('GL Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::select('gl_id',
                        $gl,
                        $data->gl_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'gl_id',
                            )) }}
                </div>
                <span class="validate-err" id="err_gl_id"></span>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::text('amount',
                        $data->amount, 
                        array(
                            'class' => 'form-control',
                            'id'=>'amount'
                            )) }}
                </div>
                <span class="validate-err" id="err_amount"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('or_no', __('OR Number'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('or_no',
                        $data->or_no, 
                        array(
                            'class' => 'form-control',
                            'id'=>'or_no'
                            )) }}
                </div>
                <span class="validate-err" id="err_or_no"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                {{ Form::label('particulars', __('Particulars'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ Form::textarea('particulars',
                        $data->particulars, 
                        array(
                            'class' => 'form-control',
                            'id'=>'particulars'
                            )) }}
                </div>
                <span class="validate-err" id="err_particulars"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('is_income', __('Is Income?'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{ 
                        Form::radio('is_income', 
                        1,
                        false, 
                        $attributes = array(
                        'id' => 'is_income_yes',
                        'class' => 'form-check-input',
                        $data->is_income === 1? 'checked':'',
                        )) 
                    }}
                    {{ Form::label('is_income_yes', 'Yes', ['class' => 'fs-6 fw-bold mx-2']) }}
                    </br>
                    {{ 
                        Form::radio('is_income', 
                        0,
                        false, 
                        $attributes = array(
                        'id' => 'is_income_no',
                        'class' => 'form-check-input',
                        $data->is_income === 0? 'checked':'',
                        )) 
                    }}
                    {{ Form::label('is_income_no', 'No', ['class' => 'fs-6 fw-bold mx-2']) }}
                </div>
                <span class="validate-err" id="err_fund_code_id"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
    </div>
</div>    
{{Form::close()}}

<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<script>
    FormNormal()
</script>