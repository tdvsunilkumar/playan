{{ Form::open(array('url' => 'hr/policy/update','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('hrsp_description', __('Description'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('hrsp_description',
                        $data->hrsp_description, 
                        array(
                            'class' => 'form-control',
                            'id'=>'hrsp_description'
                            )) }}
                </div>
                <span class="validate-err" id="err_hrsp_description"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('hrsp_value', __('Value'),['class'=>'form-label']) }}
                <div class="form-icon-user">
                    @if($data->hrsp_matrix === 'boolean')
                        <div class="form-inline">
                            {{ Form::radio('hrsp_value',
                                'Yes', 
                                false, 
                                array(
                                    'class' => 'form-check-input',
                                    'id'=>'hrsp_value_yes',
                                    ($data->hrsp_value === 'Yes') ? 'checked' : ''
                                    )) }}
                            {{ Form::label('hrsp_value_yes', __('Yes'),['class'=>'fs-6 fw-bold mx-2']) }}
                        </div>
                        <div class="form-inline">
                            {{ Form::radio('hrsp_value',
                                'No', 
                                false, 
                                array(
                                    'class' => 'form-check-input',
                                    'id'=>'hrsp_value_No',
                                    ($data->hrsp_value === 'No') ? 'checked' : ''
                                    )) }}
                            {{ Form::label('hrsp_value_No', __('No'),['class'=>'fs-6 fw-bold mx-2']) }}
                        </div>
                    @else
                        {{ Form::text('hrsp_value',
                            $data->hrsp_value, 
                            array(
                                'class' => 'form-control',
                                'id'=>'hrsp_value'
                                )) }}
                    @endif
                </div>
                <span class="validate-err" id="err_hrsp_value"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
    </div>
</div>
<!-- <script src="{{ asset('js/partials/forms_validation.js') }}"></script>  -->
<script>
    // FormNormal()
</script>