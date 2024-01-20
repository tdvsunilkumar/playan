{{ Form::open(array('url' => 'hr-biometrics','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bio_is_copied',0) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_ip', __('IP Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_ip') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_ip',$data->bio_ip, array('class' => 'form-control','id'=>'bio_ip')) }}
                    </div>
                    <span class="validate-err" id="err_bio_ip"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_proxy', __('Proxy'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_proxy') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_proxy',$data->bio_proxy, array('class' => 'form-control','id'=>'bio_proxy')) }}
                    </div>
                    <span class="validate-err" id="err_bio_proxy"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_desc', __('Description'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_desc') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_desc',$data->bio_desc, array('class' => 'form-control','id'=>'bio_desc')) }}
                    </div>
                    <span class="validate-err" id="err_bio_desc"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_model', __('Device Model'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_model') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_model',$data->bio_model, array('class' => 'form-control','id'=>'bio_model')) }}
                    </div>
                    <span class="validate-err" id="err_bio_model"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_code', __('Device Code'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_code') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_code',$data->bio_code, array('class' => 'form-control','id'=>'bio_code')) }}
                    </div>
                    <span class="validate-err" id="err_bio_code"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_department', __('Department'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_department') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_department',$data->bio_department, array('class' => 'form-control','id'=>'bio_department')) }}
                    </div>
                    <span class="validate-err" id="err_bio_department"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>    
{{Form::close()}}
@if($data->id)
{{ Form::open(array('url' => 'hr-biometrics/import','class'=>'formDtls', 'files' => true)) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bio_file', __('Import'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        <input type="file" class="form-control" id="bio_file" name="bio_file" required>
                    </div>
                    <span class="validate-err" id="err_bio_file"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>    
{{Form::close()}}
@endif
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_biometrics.js') }}"></script>   
